<?php
$files = glob("*.{html,php}", GLOB_BRACE);

foreach ($files as $file) {
    // Skip index files to prevent unnecessary nesting
    if (basename($file) === "index.html" || basename($file) === "index.php") {
        continue;
    }

    // Extract filename without extension
    $name = pathinfo($file, PATHINFO_FILENAME);
    $ext = pathinfo($file, PATHINFO_EXTENSION);

    // Create a directory if it doesn't exist
    if (!is_dir($name)) {
        mkdir($name);
    }

    // Move file into the new directory as index.html or index.php
    rename($file, "$name/index.$ext");

    echo "Moved $file → $name/index.$ext\n";
}

echo "Restructuring complete!\n";
?>
