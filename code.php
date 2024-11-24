<?php
function zipFolder($source, $destination) {
    if (!extension_loaded('zip')) {
        exit("The zip extension is not enabled.");
    }

    if (!file_exists($source)) {
        exit("Source folder does not exist.");
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
        exit("Failed to create zip file.");
    }

    $source = realpath($source);

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($files as $file) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($source) + 1);

        if ($file->isDir()) {
            $zip->addEmptyDir($relativePath);
        } else {
            $zip->addFile($filePath, $relativePath);
        }
    }

    $zip->close();
    echo "Folder successfully zipped to: $destination";
}

// Usage example:
$sourceFolder = __DIR__;  // Current folder
$zipFile = __DIR__ . '/your-archive.zip';  // Save zip file in the current folder
zipFolder($sourceFolder, $zipFile);
?>
