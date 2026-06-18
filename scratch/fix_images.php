<?php
$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
foreach($ite as $file) {
    if($file->getExtension() == 'php') {
        $path = $file->getPathname();
        $c = file_get_contents($path);
        $original = $c;
        $c = str_replace('primary_image_url', 'primary_image_medium_url', $c);
        $c = preg_replace('/<img(?![^>]*loading=["\']lazy["\'])[^>]+>/i', '$0 loading="lazy"', $c);
        if ($c !== $original) {
            file_put_contents($path, $c);
        }
    }
}
echo "Done";
