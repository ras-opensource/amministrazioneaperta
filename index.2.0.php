<?php 
include_once("utils/config.php");
include_once("utils/system_lib.php");

session_start();

//Verifica utente
$user=AA_User::GetCurrentUser();
$platform = AA_Platform::GetInstance($user);

$lib_path=AA_Const::AA_PUBLIC_LIB_PATH;
?>
<!DOCTYPE html>
<html>
<head>
<title>Amministrazione Aperta</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="<?php echo $lib_path;?>/jquery/jquery-ui.css" />
<link rel="stylesheet" href="<?php echo $lib_path;?>/jointjs/joint.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $lib_path;?>/webix/codebase/webix.min.css" />
<link rel="stylesheet" href="stili/mdi/css/materialdesignicons.min.css" type="text/css" />
<link href="stili/system.css" rel="stylesheet" type="text/css" />
<link href="stili/organismi.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $lib_path;?>/cryptojs/aes.js"></script>
<script src="<?php echo $lib_path;?>/jquery/jquery-3.6.0.min.js"></script>
<script src="<?php echo $lib_path;?>/jquery/jquery-ui.min.js"></script>
<script src="<?php echo $lib_path;?>/jointjs/lodash.js"></script>
<script src="<?php echo $lib_path;?>/jointjs/backbone.js"></script>
<script src="<?php echo $lib_path;?>/jointjs/joint.min.js"></script>
<script src="<?php echo $lib_path;?>/jointjs/joint.shapes.org.min.js"></script>
<script type="text/javascript" src="<?php echo $lib_path;?>/webix/codebase/webix.min.js"></script>
<script type="text/javascript" src="<?php echo $lib_path;?>/pdfobject.min.js"></script>
<script type="text/javascript" src="<?php echo $lib_path;?>/system_lib.js"></script>
<script>
    //Abilita la gestione dell'interfaccia grafica integrata
    AA_MainApp.ui.enableGui=true;
</script>
<?php

foreach($platform->GetModules() as $curMod)
{
    foreach(glob(AA_Const::AA_MODULES_PATH.DIRECTORY_SEPARATOR.$curMod['id_sidebar']."_".$curMod['id'].DIRECTORY_SEPARATOR."*.js*") as $curScript)
    {
        echo '<script src="'.AA_Const::AA_WWW_ROOT.'/'.$curScript.'"></script>';
    }
}
?>
</head>
<body>
</body>
</html>
