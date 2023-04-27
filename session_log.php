<?php
session_start();
include_once "utils/system_lib.php";

/*$admin=AA_User::UserAuth("","admin_ente",MD5("admin_ente"));

echo "<br>";
echo $admin;*/

echo AA_Utils::GetSessionLog();
if(isset($_REQUEST['reset'])) AA_Utils::ResetSessionLog();
?> 
