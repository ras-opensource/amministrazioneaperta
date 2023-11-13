<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
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
                //Resetta le impostazioni attuali del filtro
                let filter = candidati_list.getFilter("ordine");
                if(filter) filter.value="";
                filter = candidati_list.getFilter("cognome");
                if(filter) filter.value="";
                filter = candidati_list.getFilter("nome");
                if(filter) filter.value="";
                filter = candidati_list.getFilter("cf");
                if(filter) filter.value="";
                filter = candidati_list.getFilter("circoscrizione_desc");
                if(filter) filter.value="";
                filter = candidati_list.getFilter("coalizione");
                if(filter) filter.value="";
                filter = candidati_list.getFilter("lista");
                if(filter) filter.value=item.denominazione;

                candidati_list.filter("lista",item.denominazione,false);
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

//Handler operatore comunale click preview
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].OC_SectionBoxClick = function() {
    try 
    {
        //console.log("eventHandlers.defaultHandlers.OC_SectionBoxClick", this, arguments);
        if($$(arguments[0])) $$(arguments[0]).show(); 
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.CoalizioneDblClick", msg, this);
    }
};

//OC refresh view
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].OC_RefreshSection = function() {
    try 
    {
        //console.log("eventHandlers.defaultHandlers.OC_RefreshSection", this, arguments);
        window.location.reload(true);
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.OC_RefreshSection", msg, this);
    }
};

//Handler caricamento candidati con suggerimento della lista, della circoscrizione e della coalizione
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].AddNewCandidato = function() {
    try 
    {
        //console.log("eventHandlers.defaultHandlers.AddNewCandidato", this, arguments);
        let task=arguments[0].task;
        let id_object=arguments[0].params[0];
        let params=arguments[0].params[1];
        let table=$$(params.table_id);    
        //console.log("eventHandlers.defaultHandlers.AddNewCandidato",task,params);
        if(table && task && params)
        {
            params.task=task;
            let circoscrizione_val="";
            let lista_val="";
            
            let circoscrizione=table.getFilter("circoscrizione_desc");
            if(circoscrizione)
            {
                circoscrizione_val=circoscrizione.value;
            }
            else
            {
                console.log("eventHandlers.defaultHandlers.AddNewCandidato - circoscrizione non valida.",circoscrizione);
            }

            let lista=table.getFilter("lista");
            if(lista) 
            {
                lista_val=lista.value;
            }
            else
            {
                console.log("eventHandlers.defaultHandlers.AddNewCandidato - lista non valida.",lista);
            }
            
            params.params=[{circoscrizione_desc:circoscrizione_val},{lista_desc:lista_val},id_object];

            //console.log("eventHandlers.defaultHandlers.AddNewCandidato",params);

            AA_MainApp.utils.callHandler('dlg',params,'<?php echo AA_SierModule::AA_ID_MODULE?>');
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.AddNewCandidato", msg, this);
    }
};

<?php
//parte operatori comunali
if(isset($_SESSION['oc_ui_enable']) && $_SESSION['oc_ui_enable']==1)
{
    echo "AA_MainApp.defaultModule='".AA_SierModule::AA_ID_MODULE."';";
    echo "AA_MainApp.defaultSidebarModule='sier';";
    echo "AA_MainApp.userAuth=function(){location.reload(true)};";
    echo "AA_MainApp.logIn=function(){location.reload(true)};";
    echo "AA_MainApp.resetPwd=AA_LogOut;";
    echo "AA_MainApp.register=function(){location.reload(true)};";
}
else
{
    AA_Log::Log(__METHOD__." - variabile di sessione non impostata",100);
}
?>

//Registrazione modulo
AA_MainApp.registerModule(<?php echo AA_SierModule::AA_ID_MODULE?>);

