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
var <?php echo AA_GecopModule::AA_ID_MODULE?> = new AA_Module("<?php echo AA_GecopModule::AA_ID_MODULE?>", "GECOP");
<?php echo AA_GecopModule::AA_ID_MODULE?>.valid = true;
<?php echo AA_GecopModule::AA_ID_MODULE?>.content = {};
<?php echo AA_GecopModule::AA_ID_MODULE?>.contentType = "json";
<?php echo AA_GecopModule::AA_ID_MODULE?>.ui.module_content_id = "<?php echo AA_GecopModule::AA_UI_MODULE_MAIN_BOX?>";

//aggiusta automagicamente l'altezza delle righe della tabella
<?php echo AA_GecopModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].adjustRowHeight = function() {
    try 
    {
        //console.log("adjustRowHeight",arguments,this)
        this.adjustRowHeight(null, true);
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.adjustRowHeight", msg, this);
    }
};


//Visualizza i dettagli relativi all'errore di importazione da csv
<?php echo AA_GecopModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].onCsvImportErrorsDetail = function() {
    try 
    {
        params={task:"GetGecopCsvImportErrorDetailDlg", postParams: {errors: arguments[0]}};

        AA_MainApp.utils.callHandler('dlg',params,'<?php echo AA_GecopModule::AA_ID_MODULE?>');
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.onCsvImportErrorsDetail", msg, this);
    }
};
//Registrazione modulo
AA_MainApp.registerModule(<?php echo AA_GecopModule::AA_ID_MODULE?>);

