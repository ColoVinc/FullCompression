<?php
/**
 * Plugin Name: FullCompression
 * Description: Comprimi e converti automaticamente le immagini caricate su WordPress.
 * Version: 1.0.0
 * Author: Vincenzo Colonna
 * Text Domain: full-compression
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/image-processor.php';

add_action('plugins_loaded', function () {
    load_plugin_textdomain('full-compression', false, dirname(plugin_basename(__FILE__)) . '/languages');
});