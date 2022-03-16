<?php
//Configurazione
Class AA_Config
{
    const AA_DBHOST="localhost";
    const AA_DBNAME="dbname";
    const AA_DBUSER="dbuser";
    const AA_DBPWD="dbpwd";
    
    //Percorso assoluto principale delle librerie
    const AA_LIB_PATH="";

    //Locale
    const AA_LOCALE="it_IT";
}

//percorso librerie
if(AA_Config::AA_LIB_PATH != "") set_include_path(get_include_path().PATH_SEPARATOR.AA_Config::AA_LIB_PATH);

//Impostazioni lingua
setlocale(LC_ALL, AA_Config::AA_LOCALE);
