<?php

namespace Leapfrog\Admin;

class Menu {
    const PAGE_SLUG = 'leapfrogger-media-uploads';

    public function __construct() {
        echo 'Menu::__construct called';
        error_log('Menu::__construct called');
        //error_log(print_r(debug_backtrace(), true));  // Add this line
        add_action('admin_menu', [$this, 'add_media_upload_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    public static function register() {
        if (is_admin()) { // prevents wp-blog-header.php from loading this on Front-end
            new self();
        }
    } 

    public function enqueue_admin_scripts() {
        wp_enqueue_script('uppy', 'https://transloadit.edgly.net/releases/uppy/v2.0.1/uppy.min.js', [], null, true);
        wp_enqueue_style('uppy', 'https://transloadit.edgly.net/releases/uppy/v2.0.1/uppy.min.css', [], null);
        wp_enqueue_script('my-uppy-script', plugin_dir_url(dirname(__FILE__, 3)) . 'js/uppy-setup.js', ['uppy'], '1.0.0', true);
        $script_data = [
            'BUCKET_SERVICE' => BUCKET_SERVICE, // Replace BUCKET_SERVICE with your bucket service constant or variable
        ];
        
        if (BUCKET_SERVICE === 'S3') {
            $script_data['BUCKET_NAME'] = BUCKET_NAME; // Replace BUCKET_NAME with your bucket name constant or variable
        }
    
        wp_localize_script('my-uppy-script', 'wpData', $script_data);
    }

    public function add_media_upload_page() {
        echo "'Menu::add_media_upload_page called')";
        error_log('Menu::add_media_upload_page called');
        add_media_page(
            'Leapfrogger Media Uploads', // Page title
            'Leapfrogger Uploads',       // Menu title
            'upload_files',              // Capability
            self::PAGE_SLUG,             // Menu slug
            [$this, 'render_upload_page']// Function to call
        );
    }

    public function render_upload_page() {
        echo '<div id="uppy"></div>';
    }
}
