<?php

namespace Leapfrog\Library;

interface StorageServiceInterface {
    /**
     * Generate a signed URL for the object in the specified bucket.
     *
     * @param string $bucket The name of the bucket.
     * @param string $objectName The name of the object.
     *
     * @return string The signed URL.
     */
    public function getSignedUrl($bucket, $objectName);

    /**
     * Uploads a file to the specified bucket.
     *
     * @param string $bucket The name of the bucket.
     * @param string $filePath The path of the file to be uploaded.
     * @param string $objectName The name of the object in the bucket.
     *
     * @return void
     */
    public function uploadFile($bucket, $filePath, $objectName);

    /**
     * Check if a file exists in the specified bucket.
     *
     * @param string $bucket The name of the bucket.
     * @param string $objectName The name of the object.
     *
     * @return bool True if the object exists in the bucket, otherwise false.
     */
    public function fileExists($bucket, $objectName);

    /**
     * Deletes a file from the specified bucket.
     *
     * @param string $bucket The name of the bucket.
     * @param string $objectName The name of the object.
     *
     * @return void
     */
    public function deleteFile($bucket, $objectName);
}
