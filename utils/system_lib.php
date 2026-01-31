<?php
/**
 * system_lib.php
 *
 * This file sets up an autoloader for the classes located in the 'system_lib' directory
 * and includes the autoloader for 'system_core' classes.
 * It uses spl_autoload_register to automatically include class files when they are first referenced.
 */

include_once 'system_core.php';
include_once 'system_storage.php';
include_once 'system_ui.php';
include_once "system_ui.legacy.php";
include_once "system_custom.php";

/**
 * Registers an autoloader function to load classes from the 'system_lib' directory.
 *
 * The autoloader expects class names to directly correspond to their file names
 * within the 'system_lib' directory, with a '.php' extension.
 * For example, 'MyClass' will try to load 'system_lib/MyClass.php'.
 *
 * @param string $className The name of the class to load.
 * @return void
 */
spl_autoload_register(function ($className) {
    // Define the base directory where your class files are located
    $baseDir = __DIR__ . '/system_lib/';

    // Construct the full path to the class file
    $filePath = $baseDir . $className . '.php';

    // Check if the file exists and include it
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});
