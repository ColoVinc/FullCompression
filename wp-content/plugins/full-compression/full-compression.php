<?php
/**
 * Plugin Name: FullCompression
 * Description: Comprimi e converti automaticamente le immagini caricate su WordPress.
 * Version: 1.0.0
 * Author: Vincenzo Colonna
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/image-processor.php';
