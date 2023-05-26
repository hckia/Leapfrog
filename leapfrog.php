<?php
/**
 * Plugin Name: Leapfrog
 * Description: A plugin that allows you to leapfrog limitations related to files with WordPress, and the environment that supports it.
 * Version: 0.1.0
 * Author: Cyrus Kia
 * License: GPL v2 or later
 * Text Domain: leapfrog-plugin
 */

require 'vendor/autoload.php';

use Leapfrog\Bootstrap;

// Prevent direct access to the file.
defined('ABSPATH') or die('No script kiddies please!');

if (!class_exists('Leapfrog')) {
    class Leapfrog
    {
        public function __construct() {
            // Load the text domain for internationalization
            add_action('plugins_loaded', [$this, 'load_textdomain']);
            add_action('admin_notices', [$this, 'admin_notices']);
            // Initialize the Bootstrap class
            $bootstrap = new Bootstrap();
            $bootstrap->run();

            // Plugin activation
            register_activation_hook(__FILE__, [$this, 'activate']);
            // Plugin deactivation
            register_deactivation_hook(__FILE__, [$this, 'deactivate']);
            // Plugin uninstall
            register_uninstall_hook(__FILE__, ['Leapfrog', 'uninstall']);
        }

        public static function uninstall() {
            // Cleanup tasks here. this is a placeholder for  now.
            //delete_option('leapfrog_option_name');
            //delete_post_meta_by_key('leapfrog_meta_key');
        }

        public function activate() {
            if (!defined('BUCKET_SERVICE')) {
                error_log('BUCKET_SERVICE constant is not defined.', 0);
                set_transient('leapfrog_missing_constants', true, 5);
            } else if (BUCKET_SERVICE === 'gcs') {
                if (!defined('GCS_PROJECT_ID')) {
                    error_log('GCS_PROJECT_ID constant is not defined.', 0);
                    set_transient('leapfrog_missing_constants', true, 5);
                }
                if (!defined('GCS_KEY_FILE_PATH')) {
                    error_log('GCS_KEY_FILE_PATH constant is not defined.', 0);
                    set_transient('leapfrog_missing_constants', true, 5);
                }
            } else if (BUCKET_SERVICE === 's3') {
                if (!defined('AWS_ACCESS_KEY')) {
                    error_log('AWS_ACCESS_KEY constant is not defined.', 0);
                    set_transient('leapfrog_missing_constants', true, 5);
                }
                if (!defined('AWS_SECRET_KEY')) {
                    error_log('AWS_SECRET_KEY constant is not defined.', 0);
                    set_transient('leapfrog_missing_constants', true, 5);
                }
                if (!defined('AWS_REGION')) {
                    error_log('AWS_REGION constant is not defined.', 0);
                    set_transient('leapfrog_missing_constants', true, 5);
                }
            } else {
                error_log('Invalid BUCKET_SERVICE value. It should be either "gcs" or "s3".', 0);
                set_transient('leapfrog_missing_constants', true, 5);
            }
        }
        

        public function admin_notices() {
            // Check whether our transient is set. If it is, display our notice.
            if (get_transient('leapfrog_missing_constants')) {
                ?>
                <div class="notice notice-error">
                    <p><?php _e('Leapfrog plugin could not be activated - missing required constants, or invalid BUCKET_SERVICE defined.', 'leapfrog-plugin'); ?></p>
                </div>
                <?php
                // Delete the transient so we don't keep displaying the notice.
                delete_transient('leapfrog_missing_constants');
            }
        }
        

        public function deactivate() {
            // What to do on plugin deactivation

            // Unscheduling a cron event
            //wp_clear_scheduled_hook('placeholder_scheduled_event');

            // Removing a shortcode
            //remove_shortcode('placeholder_shortcode');

            // Flush rewrite rules
            //flush_rewrite_rules();
            // Delete the transient if it exists.
            if (get_transient('leapfrog_missing_constants')) {
                delete_transient('leapfrog_missing_constants');
            }
        }

        public function load_textdomain() {
            load_plugin_textdomain('leapfrog-plugin', false, basename(dirname(__FILE__)) . '/languages/');
        }
    }

    // Initialize our plugin
    $my_s3_gcs_plugin = new Leapfrog();
}