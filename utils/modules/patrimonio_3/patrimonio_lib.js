/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//modulo
var AA_patrimonio_module= new AA_Module("AA_MODULE_PATRIMONIO","PATRIMONIO");
AA_patrimonio_module.valid = true;
AA_patrimonio_module.content={};
AA_patrimonio_module.contentType = "json";
AA_patrimonio_module.ui.icon = "mdi mdi-office-building-marker";
AA_patrimonio_module.ui.name = "Sistema Informativo Patrimonio Immobiliare";
AA_patrimonio_module.ui.module_content_id = "AA_Patrimonio_module_layout";

//Registrazione modulo
AA_MainApp.registerModule(AA_patrimonio_module);
