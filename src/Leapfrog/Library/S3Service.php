<?php
namespace src\Offloader;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class S3Service
{
    private $s3Client;

    public function __construct($accessKey, $secretKey, $region) {
        $this->s3Client = new S3Client([
            'version'     => 'latest',
            'region'      => $region,
            'credentials' => [
                'key'    => $accessKey,
                'secret' => $secretKey,
            ],
        ]);
    }

    public function uploadFile($bucket, $key, $body, $acl = 'private') {
        try {
            $result = $this->s3Client->putObject([
                'Bucket' => $bucket,
                'Key'    => $key,
                'Body'   => $body,
                'ACL'    => $acl
            ]);

            return $result->get('ObjectURL');
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
            return false;
        }
    }

    // Additional methods to handle S3 actions will go here.
}
