<?php
/**
 * Plugin Name: Leapfrong
 * Description: A plugin that allows you to leapfrog limitations related to files with WordPress, and the environment that supports it.
 * Version: 0.1.0
 * Author: CyrusKia
 * License: GPL v2 or later
 * Text Domain: leapfrog-plugin
 */

require 'vendor/autoload.php';

use src\Offloader\S3Service;
use src\Offloader\GCSService;


// Prevent direct access to the file.
defined('ABSPATH') or die('No script kiddies please!');

class Leapfrog
{
    public function __construct() {
        // Plugin activation
        register_activation_hook(__FILE__, [$this, 'activate']);

        // Plugin deactivation
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
    }

    public function activate() {
        // What to do on plugin activation
    }

    public function deactivate() {
        // What to do on plugin deactivation
    }
}

// Initialize our plugin
$my_s3_gcs_plugin = new Leapfrog();
