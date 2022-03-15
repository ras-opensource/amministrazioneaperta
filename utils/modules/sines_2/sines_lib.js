/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//modulo
var AA_sines_module= new AA_Module("AA_MODULE_SINES","SINES");
AA_sines_module.valid = true;
AA_sines_module.content={};
AA_sines_modulecontentType = "json";
AA_sines_module.ui.icon = "mdi mdi-office-building";
AA_sines_module.ui.name = "SINES - Sistema Informativo Enti e Società";
AA_sines_module.ui.module_content_id = "AA_sines_module_layout";

//Gestore eventi selezione
//AA_sines_module.eventHandlers['AA_Sines_Pubblicate_List_Box']={};
//AA_sines_module.eventHandlers['AA_Sines_Pubblicate_List_Box'].onSelectChange=AA_Sines_Pubblicate_SelectionChange;

//Registrazione modulo
AA_MainApp.registerModule(AA_sines_module);
