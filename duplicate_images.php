<?php
$dir = __DIR__ . '/storage/app/public/products';
$files = scandir($dir);
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'jpg') {
        if (!str_starts_with($file, 'medium_') && !str_starts_with($file, 'thumb_') && !str_starts_with($file, 'original_')) {
            copy($dir . '/' . $file, $dir . '/medium_' . $file);
            copy($dir . '/' . $file, $dir . '/thumb_' . $file);
            copy($dir . '/' . $file, $dir . '/original_' . $file);
            echo "Đã copy: $file\n";
        }
    }
}
echo "Hoàn tất copy ảnh!\n";
