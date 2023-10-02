<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("lib.php");
?>
//modulo
var <?php echo AA_SierModule::AA_ID_MODULE?> = new AA_Module("<?php echo AA_SierModule::AA_ID_MODULE?>", "SIER");
<?php echo AA_SierModule::AA_ID_MODULE?>.valid = true;
<?php echo AA_SierModule::AA_ID_MODULE?>.content = {};
<?php echo AA_SierModule::AA_ID_MODULE?>.contentType = "json";
<?php echo AA_SierModule::AA_ID_MODULE?>.ui.module_content_id = "<?php echo AA_SierModule::AA_UI_MODULE_MAIN_BOX?>";

//Handler doppio click Lista
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].ListaDblClick = function() {
    try 
    {
        //console.log("eventHandlers.defaultHandlers.ListaDblClick", this, arguments);
        let item=this.getItem(arguments[0]);
       
        let tabbar=$$(this.config.tabbar);
        //console.log("eventHandlers.defaultHandlers.ListaDblClick",tabbar);
        if(tabbar)
        {
            tabbar.setValue("<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_ID_SECTION_DETAIL."_".AA_SierModule::AA_UI_DETAIL_CANDIDATI_BOX;?>");
            let id="<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_ID_SECTION_DETAIL."_".AA_SierModule::AA_UI_DETAIL_CANDIDATI_BOX."_Candidati";?>";
            let candidati_list=$$(id);
            if(candidati_list)
            {
                candidati_list.filter("lista",item.denominazione,false);
                let filter = candidati_list.getFilter("lista");
                filter.value=item.denominazione;
            }
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.ListaDblClick", msg, this);
    }
};

//Handler doppio click coalizione
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].CoalizioneDblClick = function() {
    try 
    {
        //console.log("eventHandlers.defaultHandlers.CoalizioneDblClick", this, arguments);
        let item=this.getItem(arguments[0]);    
        let tabbar=$$(this.config.tabbar);
        //console.log("eventHandlers.defaultHandlers.ListaDblClick",tabbar,item);
        if(tabbar && item)
        {
            tabbar.setValue(item.id_view);
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.CoalizioneDblClick", msg, this);
    }
};

//Handler caricamento candidati con suggerimento della lista, della circoscrizione e della coalizione
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].AddNewCandidato = function() {
    try 
    {
        console.log("eventHandlers.defaultHandlers.CoalizioneDblClick", this, arguments);
        let task=arguments[0].task;
        let params=arguments[0].params[1];
        let table=$$(params.table_id);    
        console.log("eventHandlers.defaultHandlers.AddNewCandidato",task,params);
        if(table && task && params)
        {
            params.task=task;
            let circoscrizione=table.getFilter("circoscrizione_desc").value;
            let lista=table.getFilter("lista").value;
            
            params.params=[{circoscrizione_desc:circoscrizione},{lista_desc:lista}];

            console.log("eventHandlers.defaultHandlers.AddNewCandidato",params);

            //AA_MainApp.utils.callHandler('dlg',params,'<?php echo AA_SierModule::AA_ID_MODULE?>');
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.AddNewCandidato", msg, this);
    }
};

//Registrazione modulo
AA_MainApp.registerModule(<?php echo AA_SierModule::AA_ID_MODULE?>);