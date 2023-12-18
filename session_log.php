<?php
include_once "utils/config.php";
include_once "utils/system_lib.php";
session_start();

/*$admin=AA_User::UserAuth("","admin_ente",MD5("admin_ente"));

echo "<br>";
echo $admin;*/

echo AA_Utils::GetSessionLog();
if(isset($_REQUEST['reset'])) AA_Utils::ResetSessionLog();
?> 
