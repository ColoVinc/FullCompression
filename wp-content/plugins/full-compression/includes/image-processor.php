<?php
add_filter('wp_handle_upload', 'fc_convert_image_format');

function fc_convert_image_format($upload) {
    $format = get_option('fc_image_format', 'webp');

    $file_path = $upload['file'];
    $file_type = mime_content_type($file_path);

    // Evita se non è un'immagine
    if (strpos($file_type, 'image/') !== 0) {
        return $upload;
    }

    $image = null;

    switch ($file_type) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($file_path);
            break;
        case 'image/png':
            $image = imagecreatefrompng($file_path);
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($file_path);
            break;
        default:
            return $upload;
    }

    if (!$image) return $upload;

    $new_path = preg_replace('/\.(jpe?g|png|webp)$/i', '.' . $format, $file_path);

    switch ($format) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($image, $new_path, 85);
            break;
        case 'png':
            imagepng($image, $new_path, 6);
            break;
        case 'webp':
            imagewebp($image, $new_path, 80);
            break;
    }

    imagedestroy($image);

    // Elimina file originale e aggiorna percorso
    unlink($file_path);
    $upload['file'] = $new_path;
    $upload['url'] = str_replace(basename($upload['url']), basename($new_path), $upload['url']);
    $upload['type'] = mime_content_type($new_path);

    return $upload;
}
