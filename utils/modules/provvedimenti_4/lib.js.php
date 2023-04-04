<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("lib.php");
?>
//modulo
var <?php echo AA_ProvvedimentiModule::AA_ID_MODULE?> = new AA_Module("<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>", "PROVVEDIMENTI");
<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.valid = true;
<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.content = {};
<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.contentType = "json";
<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.ui.module_content_id = "<?php echo AA_ProvvedimentiModule::AA_UI_MODULE_MAIN_BOX?>";

//Handler del cambio di tipo provvedimento
<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].onTipoProvSelectChange = function() {
    try {
        //console.log("eventHandlers.defaultHandlers.onTipoProvSelectChange", this, arguments);
        let tipo = arguments[0];

        //Accordo
        if (tipo == "2") {
            //Visualizza il contraente
            let contraente = $$(<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.id + '_AddNew_Dlg_Field_Contraente');
            if (contraente) {
                contraente.show();
            }

            contraente = $$(<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.id + '_Modify_Dlg_Field_Contraente');
            if (contraente) {
                contraente.show();
            }

            //nascondi la modalità di scelta
            let mod = $$(<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.id + '_AddNew_Dlg_Field_Modalita');
            if (mod) {
                mod.hide();
            }

            //nascondi la modalità di scelta
            mod = $$(<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.id + '_Modify_Dlg_Field_Modalita');
            if (mod) {
                mod.hide();
            }
        }
        if (tipo == "1") {
            //nascondi il contraente
            let contraente = $$(<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.id + '_AddNew_Dlg_Field_Contraente');
            if (contraente) {
                contraente.hide();
            }
            
            contraente = $$(<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.id + '_Modify_Dlg_Field_Contraente');
            if (contraente) {
                contraente.hide();
            }
            //visualizza la modalità di scelta
            let mod = $$(<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.id + '_AddNew_Dlg_Field_Modalita');
            if (mod) {
                mod.show();
            }
            
            mod = $$(<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>.id + '_Modify_Dlg_Field_Modalita');
            if (mod) {
                mod.show();
            }
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.onTipoProvSelectChange", msg, this);
    }
};

//Registrazione modulo
AA_MainApp.registerModule(<?php echo AA_ProvvedimentiModule::AA_ID_MODULE?>);