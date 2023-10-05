<?php
//Configurazione
Class AA_Config
{
    const AA_DBHOST="localhost";
    const AA_DBNAME="dbname";
    const AA_DBUSER="dbuser";
    const AA_DBPWD="dbpwd";

    //Persorso principale delle librerie
    const AA_LIB_PATH="";
    const AA_PUBLIC_LIB_PATH="";

    //percorso della cartella dello storage (assoluto)
    const AA_STORAGE_PATH="";

    //Locale
    const AA_LOCALE="it_IT";

    //Parametri server SMTP e invio mail
    const AA_SMTP_SERVER="192.168.0.1";
    const AA_SMTP_USERNAME="smtp_user";
    const AA_SMTP_PWD="smtp_pwd";
    const AA_EMAIL_FROM="you@example.org";
    const AA_EMAIL_REPLYTO="you@example.org";
    const AA_EMAIL_FRONT="Your organization";

    //nome di dominio
    const AA_DOMAIN_NAME="your.domain.name";

    //Cartella di archiviazione uploads (percorso assoluto) //deprecated
    const AA_UPLOADS_PATH="<absolute path to uploads folder>";

    //Cartella percorso fisico dell'applicazione
    const AA_APP_FILESYSTEM_FOLDER="";
    
    //Cartella moduli (percorso relativo alla root)
    const AA_MODULES_PATH="utils/modules";
    const AA_PUBLIC_MODULES_PATH="utils/modules";

    //Percorso assoluto root storage
    const AA_ROOT_STORAGE_PATH="<absolute path to storage folder>";

    //Percorso root applicazione
    const AA_WWW_ROOT="<web root app folder without domain part and trailing slash>";

    //Abilita disabilita il modulo pubblico
    const AA_ENABLE_PUBLIC_MODULE=false;

    //Abilita disabilita la gestione dei dati legacy (gestione strutture, legame struttura-utente, etc.)
    const AA_ENABLE_LEGACY_DATA=false;
}

//percorso librerie
if(AA_Config::AA_LIB_PATH !="") set_include_path(get_include_path().PATH_SEPARATOR.AA_Config::AA_LIB_PATH);

//Impostazioni lingua
setlocale(LC_ALL, AA_Config::AA_LOCALE);
