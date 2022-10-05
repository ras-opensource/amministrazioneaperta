/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//modulo
var AA_provvedimenti_module = new AA_Module("AA_MODULE_PROVVEDIMENTI", "PROVVEDIMENTI");
AA_provvedimenti_module.valid = true;
AA_provvedimenti_module.content = {};
AA_provvedimenti_module.contentType = "json";
AA_provvedimenti_module.ui.module_content_id = "AA_Provvedimenti_module_layout";

//Handler del cambio di tipo provvedimento
AA_provvedimenti_module.eventHandlers['defaultHandlers'].onTipoProvSelectChange = function() {
    try {
        //console.log("eventHandlers.defaultHandlers.onTipoProvSelectChange", this, arguments);
        let tipo = arguments[0];

        //Accordo
        if (tipo == "2") {
            //Visualizza il contraente
            let contraente = $$(AA_provvedimenti_module.id + '_AddNew_Dlg_Field_Contraente');
            if (contraente) {
                contraente.show();
            }

            //nascondi la modalità di scelta
            let mod = $$(AA_provvedimenti_module.id + '_AddNew_Dlg_Field_Modalita');
            if (mod) {
                mod.hide();
            }
        }
        if (tipo == "1") {
            //nascondi il contraente
            let contraente = $$(AA_provvedimenti_module.id + '_AddNew_Dlg_Field_Contraente');
            if (contraente) {
                contraente.hide();
            }
            //visualizza la modalità di scelta
            let mod = $$(AA_provvedimenti_module.id + '_AddNew_Dlg_Field_Modalita');
            if (mod) {
                mod.show();
            }
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.onTipoProvSelectChange", msg, this);
    }
};

//Registrazione modulo
AA_MainApp.registerModule(AA_provvedimenti_module);