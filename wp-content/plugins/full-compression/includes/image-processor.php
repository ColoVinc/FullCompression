<?php
add_filter('wp_handle_upload', 'fc_convert_image_format');

function fc_convert_image_format($upload)
{
    $enabled = get_option('fc_enable_compression', '1');

    if ($enabled !== '1') {
        return $upload;
    }
    
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
    $success = false;

    if (extension_loaded('imagick')) {
        try {
            $imagick = new Imagick($file_path);
            $imagick->setImageFormat($format);

            if ($format === 'jpg' || $format === 'jpeg') {
                $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
                $imagick->setImageCompressionQuality(65); // 0-100
            } elseif ($format === 'png') {
                // PNG è lossless, quindi meno compressione possibile
                $imagick->setImageCompression(Imagick::COMPRESSION_ZIP);
                $imagick->setImageCompressionQuality(80); // Ha effetto parziale
            } elseif ($format === 'webp') {
                $imagick->setImageCompressionQuality(60);
            }

            $imagick->stripImage(); // Rimuove metadati (EXIF, ICC, GPS)
            $imagick->writeImage($new_path);
            $imagick->destroy();
            $success = true;
        } catch (Exception $e) {
            error_log('Errore Imagick: ' . $e->getMessage());
        }
    } else {
        // fallback se Imagick non disponibile (opzionale)
        // oppure lascia vuoto
    }

    imagedestroy($image);

    // Elimina file originale e aggiorna percorso
    unlink($file_path);
    $upload['file'] = $new_path;
    $upload['url'] = str_replace(basename($upload['url']), basename($new_path), $upload['url']);
    $upload['type'] = mime_content_type($new_path);

    return $upload;
}
