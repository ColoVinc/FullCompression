<?php
function fc_add_admin_menu()
{
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

function fc_register_settings()
{
    register_setting('fc_settings_group', 'fc_image_format', [
        'sanitize_callback' => function($input) {
            $allowed = ['jpg', 'png', 'webp'];
            return in_array($input, $allowed) ? $input : 'jpg';
        }
    ]);
    register_setting('fc_settings_group', 'fc_enable_compression', [
        'sanitize_callback' => function($input) {
            return $input === '1' ? '1' : '0';
        }
    ]);

    add_settings_section('fc_settings_section', 'Impostazioni di compressione', null, 'fullcompression');

    add_settings_field(
        'fc_image_format',
        __('Formato immagine desiderato', 'fullcompression'),
        'fc_image_format_field_html',
        'fullcompression',
        'fc_settings_section'
    );

    add_settings_field(
        'fc_enable_compression',
        __('Abilita/Disattiva compressione', 'fullcompression'),
        'fc_enable_compression_field_html',
        'fullcompression',
        'fc_settings_section'
    );
}
add_action('admin_init', 'fc_register_settings');

function fc_image_format_field_html()
{
    $value = get_option('fc_image_format', 'webp');
?>
    <select name="fc_image_format" id="fc_image_format">
        <option value="jpg" <?php selected($value, 'jpg'); ?>>JPG</option>
        <option value="png" <?php selected($value, 'png'); ?>>PNG</option>
        <option value="webp" <?php selected($value, 'webp'); ?>>WEBP</option>
    </select>
<?php
}

function fc_options_page_html()
{
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

function fc_enable_compression_field_html()
{
    $value = get_option('fc_enable_compression', '1');
?>
    <label>
        <input type="checkbox" name="fc_enable_compression" id="fc_enable_compression" value="1" <?php checked($value, '1'); ?> />
        Abilita compressione immagini
    </label>
<?php
}

add_action('admin_enqueue_scripts', 'fc_enqueue_admin_scripts');
function fc_enqueue_admin_scripts($hook) {
    //Carica lo script solo se siamo nella pagina del plugin
    if($hook !== 'toplevel_page_fullcompression') {
        return;
    }

    wp_enqueue_script('fc-admin-js', plugin_dir_url(__FILE__) . '../assets/js/admin.js', [], '1.0.0', true);
}