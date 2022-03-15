<?php
//percorso librerie
$path = '/home/sitod/web/amministrazione_aperta/utils';
set_include_path(get_include_path().PATH_SEPARATOR.$path);

//Impostazioni lingua
setlocale(LC_ALL, 'it_IT');

//Configurazione
Class AA_Config
{
    const AA_DBHOST="localhost";
    const AA_DBNAME="dbname";
    const AA_DBUSER="dbuser";
    const AA_DBPWD="dbpwd";
}
