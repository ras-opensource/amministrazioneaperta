<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("home_lib.php");
?>

//Modulo HOME
var <?php echo AA_HomeModule::AA_ID_MODULE?> = new AA_Module("<?php echo AA_HomeModule::AA_ID_MODULE?>", "HOME");
<?php echo AA_HomeModule::AA_ID_MODULE?>.valid = true;
<?php echo AA_HomeModule::AA_ID_MODULE?>.content = {};
<?php echo AA_HomeModule::AA_ID_MODULE?>.contentType = "json";
<?php echo AA_HomeModule::AA_ID_MODULE?>.ui.module_content_id = "<?php echo AA_HomeModule::AA_UI_MODULE_MAIN_BOX?>";

AA_MainApp.registerModule(<?php echo AA_HomeModule::AA_ID_MODULE?>);

