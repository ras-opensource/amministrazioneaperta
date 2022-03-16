<?php
//Configurazione
Class AA_Config
{
    const AA_DBHOST="localhost";
    const AA_DBNAME="monitspese";
    const AA_DBUSER="root";
    const AA_DBPWD="Ab123456";

    //Persorso principale delle librerie
    const AA_LIB_PATH="/home/sitod/web/amministrazione_aperta/utils";

    //Locale
    const AA_LOCALE="it_IT";

    //Parametri server SMTP
    const AA_SMTP_SERVER="192.168.0.1";
    const AA_SMTP_USERNAME="smtp_user";
    const AA_SMTP_PWD="smtp_pwd";
}

//percorso librerie
if(AA_Config::AA_LIB_PATH !="") set_include_path(get_include_path().PATH_SEPARATOR.AA_Config::AA_LIB_PATH);

//Impostazioni lingua
setlocale(LC_ALL, AA_Config::AA_LOCALE);
