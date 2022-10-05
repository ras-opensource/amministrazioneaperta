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
AA_patrimonio_module.ui.module_content_id = "AA_Provvedimenti_module_layout";

//Registrazione modulo
AA_MainApp.registerModule(AA_provvedimenti_module);