<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
include_once("lib.php");
header('Content-Type: text/javascript');
?>
//modulo
var <?php echo AA_GecoModule::AA_ID_MODULE?> = new AA_Module("<?php echo AA_GecoModule::AA_ID_MODULE?>", "SIER");
<?php echo AA_GecoModule::AA_ID_MODULE?>.valid = true;
<?php echo AA_GecoModule::AA_ID_MODULE?>.content = {};
<?php echo AA_GecoModule::AA_ID_MODULE?>.contentType = "json";
<?php echo AA_GecoModule::AA_ID_MODULE?>.ui.module_content_id = "<?php echo AA_GecoModule::AA_UI_MODULE_MAIN_BOX?>";

//aggiusta automagicamente l'altezza delle righe della tabella
<?php echo AA_GecoModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].adjustRowHeight = function() {
    try 
    {
        //console.log("adjustRowHeight",arguments,this)
        this.adjustRowHeight(null, true);
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.adjustRowHeight", msg, this);
    }
};

//Registrazione modulo
AA_MainApp.registerModule(<?php echo AA_GecoModule::AA_ID_MODULE?>);

