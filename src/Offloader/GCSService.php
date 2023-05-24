<?php

namespace src\Offloader;

use Google\Cloud\Storage\StorageClient;

class GCSService
{
    private $storage;

    public function __construct($projectId, $keyFilePath) {
        $this->storage = new StorageClient([
            'projectId' => $projectId,
            'keyFilePath' => $keyFilePath
        ]);
    }

    public function uploadFile($bucketName, $sourceFile, $targetName) {
        try {
            $bucket = $this->storage->bucket($bucketName);
            $file = fopen($sourceFile, 'r');
            $object = $bucket->upload($file, [
                'name' => $targetName
            ]);

            return $object->info()['mediaLink'];
        } catch (\Exception $e) {
            // Output error message if fails
            error_log($e->getMessage());
            return false;
        }
    }

    // Additional methods to handle GCS actions will go here.
}
