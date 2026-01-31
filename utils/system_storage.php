<?php
/**
 * system_storage.php
 *
 * This file sets up an autoloader for the classes located in the 'system_storage' directory.
 * It uses spl_autoload_register to automatically include class files when they are first referenced.
 */

/**
 * Registers an autoloader function to load classes from the 'system_storage' directory.
 *
 * The autoloader expects class names to directly correspond to their file names
 * within the 'system_storage' directory, with a '.php' extension.
 * For example, 'MyClass' will try to load 'system_storage/MyClass.php'.
 *
 * @param string $className The name of the class to load.
 * @return void
 */
spl_autoload_register(function ($className) {
    // Define the base directory where your class files are located
    $baseDir = __DIR__ . '/system_storage/';

    // Construct the full path to the class file
    $filePath = $baseDir . $className . '.php';

    // Check if the file exists and include it
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});