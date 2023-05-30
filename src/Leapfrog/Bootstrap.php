<?php

namespace Leapfrog;

use Leapfrog\Library\GCSService;
use Leapfrog\Library\S3Service;
use Leapfrog\API\SignedUrlController;

class Bootstrap {
    public static function initialize() {
        Admin\Menu::register();

        $storageService = null;

        if (BUCKET_SERVICE === 'GCS') {
            $storageService = new GCSService();
        } elseif (BUCKET_SERVICE === 'S3') {
            $storageService = new S3Service();
        } else {
            // Throw an exception or handle this case however you like.
            error_log('BUCKET_SERVICE constant is not defined as GCS or S3.', 0);
        }

        if ($storageService) {
            $signedUrlController = new SignedUrlController($storageService);
            $signedUrlController->register_routes();
        }
        
        // CLI\Commands::register();
        // Cron\Tasks::register();
        
        echo "initialized!";
    }
}


/* 

Enqueue Scripts and Styles: If the plugin includes scripts or styles, we would want to enqueue them here.

public static function enqueue_scripts() {
    wp_enqueue_script('my_script', plugins_url('my_script.js', __FILE__));
}
add_action('wp_enqueue_scripts', ['Bootstrap', 'enqueue_scripts']);

Register Custom Post Types or Taxonomies: If the plugin introduces new custom post types or taxonomies, we would want to register them here.

AJAX Endpoints: If the plugin uses AJAX, we could register our AJAX endpoints here.

Shortcodes: Register any shortcodes that the plugin provides.

Filters & Hooks: Add any actions or filters that the plugin needs.
*/