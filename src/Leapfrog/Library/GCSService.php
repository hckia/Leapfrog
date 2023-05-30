<?php

namespace Leapfrog\Library;

use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Core\Timestamp;

class GCSService implements StorageServiceInterface {
    private $storageClient;

    public function __construct()
    {
        $this->storageClient = new StorageClient([
            'projectId' => GCS_PROJECT_ID,
            'keyFilePath' => GCS_KEY_FILE_PATH,
        ]);
    }

    public function getSignedUrl($bucket, $objectName, $contentType)
    {
        $bucket = $this->storageClient->bucket($bucket);
        $object = $bucket->object($objectName);
        
        $url = $object->signedUrl(new Timestamp(new \DateTime('+1 hour')), [
            'method' => 'PUT',
            'contentType' => $contentType,
        ]);

        return $url;
    }

    public function uploadFile($bucket, $filePath, $objectName)
    {
        // Upload the file to the bucket.
        $file = fopen($filePath, 'r');
        $bucket = $this->storageClient->bucket($bucket);
        $bucket->upload($file, ['name' => $objectName]);
    }

    public function fileExists($bucket, $objectName)
    {
        $bucket = $this->storageClient->bucket($bucket);
        $object = $bucket->object($objectName);

        return $object->exists();
    }

    public function deleteFile($bucket, $objectName)
    {
        $bucket = $this->storageClient->bucket($bucket);
        $object = $bucket->object($objectName);
        $object->delete();
    }
}
