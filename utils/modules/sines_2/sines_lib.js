/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//modulo
var AA_sines_module= new AA_Module("AA_MODULE_SINES","SINES");
AA_sines_module.valid = true;
AA_sines_module.content={};
AA_sines_module.contentType = "json";
AA_sines_module.ui.icon = "mdi mdi-office-building";
AA_sines_module.ui.name = "SINES - Sistema Informativo Enti e Società";
AA_sines_module.ui.module_content_id = "AA_sines_module_layout";

//Gestore eventi selezione
//AA_sines_module.eventHandlers['AA_Sines_Pubblicate_List_Box']={};
//AA_sines_module.eventHandlers['AA_Sines_Pubblicate_List_Box'].onSelectChange=AA_Sines_Pubblicate_SelectionChange;

//Handler doppio click nomina
AA_sines_module.eventHandlers['defaultHandlers'].NominaDblClick = function() 
{
    try 
    {
        //console.log("eventHandlers.defaultHandlers.CoalizioneDblClick", this, arguments);
        let item=this.getItem(arguments[0]);    
        let tabbar=$$(this.config.tabbar);
        console.log("eventHandlers.defaultHandlers.ListaDblClick",tabbar,item);
        if(tabbar && item)
        {
            tabbar.setValue(item.id_view);
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.NominaDblClick", msg, this);
    }
};

//Registrazione modulo
AA_MainApp.registerModule(AA_sines_module);
