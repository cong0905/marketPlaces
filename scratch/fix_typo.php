<?php
$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
foreach($ite as $file) {
    if($file->getExtension() == 'php') {
        $path = $file->getPathname();
        $c = file_get_contents($path);
        $original = $c;
        $c = str_replace(' loading="lazy" loading="lazy"', '', $c);
        $c = str_replace(' loading="lazy"loading="lazy"', '', $c);
        if ($c !== $original) {
            file_put_contents($path, $c);
        }
    }
}
echo "Done";
