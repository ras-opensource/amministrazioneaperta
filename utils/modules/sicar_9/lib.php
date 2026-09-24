<?php
/**
 * Modulo SICAR - Sistema Informativo Catasto e Amministrazione Risorse
 * File di caricamento automatico delle classi
 */
include_once "config.php";

/**
 * Registers an autoloader function to load classes from the 'classes' directory.
 *
 * The autoloader expects class names to directly correspond to their file names
 * within the 'classes' directory, with a '.php' extension.
 * For example, 'AA_SicarImmobile' will try to load 'classes/AA_SicarImmobile.php'.
 *
 * @param string $className The name of the class to load.
 * @return void
 */
spl_autoload_register(function ($className) {
    // Define the base directory where your class files are located
    $baseDir = __DIR__ . '/classes/';

    // Construct the full path to the class file
    $filePath = $baseDir . $className . '.php';

    // Check if the file exists and include it
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});
