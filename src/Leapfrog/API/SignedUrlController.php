<?php

namespace Leapfrog\API;

use Leapfrog\Library\StorageServiceInterface;

class SignedUrlController {
    protected $namespace = 'my-s3-gcs-plugin/v1';
    protected $resource_name = 'gcs-signed-url';

    protected $storageService;

    public function __construct(StorageServiceInterface $storageService) {
        $this->storageService = $storageService;
    }

    public function register_routes() {
        register_rest_route($this->namespace, '/' . $this->resource_name, [
            'methods' => 'GET',
            'callback' => [$this, 'get_signed_url'],
            'args' => [
                'objectName' => [
                    'required' => true,
                    'type' => 'string',
                    'description' => __('The name of the object in the bucket', 'my-s3-gcs-plugin'),
                ],
                'contentType' => [
                    'required' => true,
                    'type' => 'string',
                    'description' => __('The content type of the object', 'my-s3-gcs-plugin'),
                ],
            ],
        ]);

        register_rest_route($this->namespace, '/allowed-mime-types', [
            'methods' => 'GET',
            'callback' => [$this, 'get_allowed_mime_types'],
        ]);
    }
    
    public function get_allowed_mime_types() {
        // Fetch allowed mime types from WordPress
        $mime_types = get_allowed_mime_types();
    
        return rest_ensure_response($mime_types);
    }

    public function get_signed_url($request) {
        $objectName = $request->get_param('objectName');
        $contentType = $request->get_param('contentType');

        $signedUrl = $this->storageService->getSignedUrl(BUCKET_NAME, $objectName, $contentType);

        return rest_ensure_response($signedUrl);
    }
}
