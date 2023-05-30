const { Uppy } = require('@uppy/core')
const XHRUpload = require('@uppy/xhr-upload')

const WARNING_FILE_SIZE = 256 * 1024 * 1024; // 256MB in bytes

// Fetch allowed mime types from the server
fetch('/wp-json/my-s3-gcs-plugin/v1/allowed-mime-types')
    .then(response => response.json())
    .then(allowedMimeTypes => {
        // Instantiate Uppy with the restrictions on file types
        let uppy = Uppy({
            restrictions: {
                allowedFileTypes: allowedMimeTypes
            },
            onBeforeFileAdded: (currentFile, files) => {
                // If the file size exceeds our warning size, log it but allow it
                if (currentFile.size > WARNING_FILE_SIZE) {
                    console.error('File size warning', {
                        date: new Date(),
                        path: currentFile.path,
                        name: currentFile.name,
                        type: currentFile.type,
                        location: currentFile.data
                    });
                }
                return currentFile;
            }
        });

        // On file addition, request a signed URL for the file
        uppy.on('file-added', (file) => {
            fetch('/wp-json/my-s3-gcs-plugin/v1/gcs-signed-url', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    objectName: file.name,
                    contentType: file.type,
                }),
            })
            .then(response => response.text())
            .then(signedUrl => {
                file.meta.signedUrl = signedUrl;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        // Use the XHRUpload plugin with the signedUrl we got from the server
        uppy.use(XHRUpload, {
            method: 'PUT',
            headers: file => ({
                'Content-Type': file.type
            }),
            endpoint: file => file.meta.signedUrl
        });

        // On successful upload, create a WordPress media attachment for the file
        uppy.on('upload-success', (file, response) => {
            fetch('/wp-json/wp/v2/media', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    file: {
                        name: file.name,
                        type: file.type,
                        path: file.data,
                    },
                    post: response.uploadURL
                }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Creating media failed: ' + response.statusText);
                }
                return response.json();
            })
            .then(media => {
                // Here you would update your UI with the newly created media item
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        uppy.run();
    })
    .catch(error => {
        console.error('Error:', error);
    });
