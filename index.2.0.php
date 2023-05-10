<?php 
include_once("utils/config.php");
include_once("utils/system_lib.php");

session_start();

//Verifica utente
$user=AA_User::GetCurrentUser();
$platform = AA_Platform::GetInstance($user);

include_once("utils/system_custom.php");

$lib_path=AA_Const::AA_PUBLIC_LIB_PATH;
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html>
<head>
<title>Amministrazione Aperta</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="<?php echo $lib_path;?>/jointjs/joint.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $lib_path;?>/webix/codebase/webix.min.css" />
<link rel="stylesheet" href="stili/mdi/css/materialdesignicons.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $lib_path;?>/ckeditor5/document_style.css" type="text/css" />
<link href="stili/system.css" rel="stylesheet" type="text/css" />
<link href="stili/system_custom.css" rel="stylesheet" type="text/css" />
<script defer src="<?php echo $lib_path;?>/cryptojs/aes.js"></script>
<script defer src="<?php echo $lib_path;?>/jquery/jquery-3.6.0.min.js"></script>
<script defer src="<?php echo $lib_path;?>/jointjs/lodash.js"></script>
<script defer src="<?php echo $lib_path;?>/jointjs/backbone.js"></script>
<script defer src="<?php echo $lib_path;?>/jointjs/joint.min.js"></script>
<script defer src="<?php echo $lib_path;?>/jointjs/joint.shapes.org.min.js"></script>
<script defer type="text/javascript" src="<?php echo $lib_path;?>/webix/codebase/webix.min.js"></script>
<script defer type="text/javascript" src="<?php echo $lib_path;?>/pdfobject.min.js"></script>
<script defer type="text/javascript" src="<?php echo $lib_path;?>/pulltorefresh.js/dist/index.umd.min.js"></script>
<script defer type="text/javascript" src="<?php echo $lib_path;?>/ckeditor5/ckeditor5.js"></script>
<script defer type="text/javascript" src="<?php echo $lib_path;?>/system_legacy.js"></script>
<script defer type="text/javascript" src="<?php echo $lib_path;?>/system_lib.js"></script>
<script defer type="text/javascript" src="<?php echo $lib_path;?>/system_custom.js"></script>
<?php
foreach($platform->GetModules() as $curMod)
{
    //css
    foreach(glob(AA_Const::AA_MODULES_PATH.DIRECTORY_SEPARATOR.$curMod['id_sidebar']."_".$curMod['id'].DIRECTORY_SEPARATOR."*.css*") as $curLink)
    {
        echo '<link href="'.AA_Const::AA_WWW_ROOT.'/'.$curLink.'" rel="stylesheet" type="text/css"></link>';
    }

    //javascript
    foreach(glob(AA_Const::AA_MODULES_PATH.DIRECTORY_SEPARATOR.$curMod['id_sidebar']."_".$curMod['id'].DIRECTORY_SEPARATOR."*.js*") as $curScript)
    {
        echo '<script defer src="'.AA_Const::AA_WWW_ROOT.'/'.$curScript.'"></script>';
    }
}

if(AA_Const::AA_ENABLE_PUBLIC_MODULE && !isset($_REQUEST['reserved']))
{
    //css
    foreach(glob(AA_Const::AA_MODULES_PATH.DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."*.css*") as $curScript)
    {
        echo '<link href="'.AA_Const::AA_WWW_ROOT.'/'.$curScript.'" rel="stylesheet" type="text/css"></link>';
    }

    //js
    foreach(glob(AA_Const::AA_MODULES_PATH.DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR."*.js*") as $curScript)
    {
        echo '<script defer type="text/javascript" src="'.AA_Const::AA_WWW_ROOT.'/'.$curScript.'"></script>';
    }
}
?>
</head>
<body>
   <?php echo AA_Platform::GetOverlay();?>
</body>
</html>
