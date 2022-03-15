<?php
session_start();
$path = '/home/sitod/web/amministrazione_aperta/utils';
setlocale(LC_ALL, 'it_IT');
set_include_path(get_include_path().PATH_SEPARATOR.$path);

include_once "system_lib.php";

if(isset($_REQUEST['email']))
{
    $error=false;
    if(!AA_User::ResetPassword($_REQUEST['email']))
    {
        $result=AA_Log::$lastErrorLog;
        $error=true;
    }
    else $result="Le nuove credenziali sono state inviate alla mail indicata.";
}
die("<status id='status'>0</status><error id='error'>$result</error>");
?> 
