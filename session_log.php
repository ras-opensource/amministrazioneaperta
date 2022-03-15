<?php
session_start();

$path = '/home/sitod/web/amministrazione_aperta/utils';
setlocale(LC_ALL, 'it_IT');
set_include_path(get_include_path().PATH_SEPARATOR.$path);

include_once "system_lib.php";

/*$admin=AA_User::UserAuth("","admin_ente",MD5("admin_ente"));

echo "<br>";
echo $admin;*/

echo AA_Utils::GetSessionLog();
if(isset($_REQUEST['reset'])) AA_Utils::ResetSessionLog();
?> 
