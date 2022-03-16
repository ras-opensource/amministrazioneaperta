<?php 
$path = '/home/sitod/web/amministrazione_aperta/utils';
setlocale(LC_ALL, 'it_IT');
set_include_path(get_include_path().PATH_SEPARATOR.$path);

include_once("system_lib.php");

session_start();

//Verifica utente
$user=AA_User::GetCurrentUser();
/*if($user->IsGuest())
{
 header("location: login.php");
 exit;
}*/
?>
<html>
<head>
<title>Amministrazione Aperta</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="stili/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="utils/webix/codebase/webix.min.css" />
<link rel="stylesheet" href="stili/mdi/css/materialdesignicons.min.css" type="text/css" />
<link href="stili/system.css" rel="stylesheet" type="text/css" />
<link href="stili/organismi.css" rel="stylesheet" type="text/css" />
<script src="utils/cryptojs/aes.js"></script>
<script src="utils/jquery-3.5.1.min.js"></script>
<script src="utils/jquery-ui.min.js"></script>
<script type="text/javascript" src="utils/webix/codebase/webix.min.js"></script>
<script type="text/javascript" src="utils/pdfobject.min.js"></script>
<script type="text/javascript" src="utils/system_lib.js"></script>
<script>
    //Abilita la gestione dell'interfaccia grafica integrata
    AA_MainApp.ui.enableGui=true;
</script>
<?php
$platform = AA_Platform::GetInstance($user);
foreach($platform->GetModules() as $curMod)
{
    foreach(glob("utils/modules/".$curMod['id_sidebar']."_".$curMod['id']."/*.js") as $curScript)
    {
        echo '<script src="/web/amministrazione_aperta/'.$curScript.'"></script>';
    }
}
?>
</head>
<body>
</body>
</html>
