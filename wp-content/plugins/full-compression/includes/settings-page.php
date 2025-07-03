<?php
function fc_add_admin_menu() {
    add_menu_page(
        'FullCompression',
        'FullCompression',
        'manage_options',
        'fullcompression',
        'fc_options_page_html',
        'dashicons-format-image',
        10
    );
}
add_action('admin_menu', 'fc_add_admin_menu');

function fc_register_settings() {
    register_setting('fc_settings_group', 'fc_image_format');
    
    add_settings_section('fc_settings_section', 'Impostazioni di compressione', null, 'fullcompression');
    
    add_settings_field(
        'fc_image_format',
        'Formato immagine desiderato',
        'fc_image_format_field_html',
        'fullcompression',
        'fc_settings_section'
    );
}
add_action('admin_init', 'fc_register_settings');

function fc_image_format_field_html() {
    $value = get_option('fc_image_format', 'webp');
    ?>
    <select name="fc_image_format">
        <option value="jpg" <?php selected($value, 'jpg'); ?>>JPG</option>
        <option value="png" <?php selected($value, 'png'); ?>>PNG</option>
        <option value="webp" <?php selected($value, 'webp'); ?>>WEBP</option>
    </select>
    <?php
}

function fc_options_page_html() {
    ?>
    <div class="wrap">
        <h1>Impostazioni FullCompression</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('fc_settings_group');
            do_settings_sections('fullcompression');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
