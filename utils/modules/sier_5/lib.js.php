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

//Handler modifica massiva
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].RisultatiPreferenzeModifyAll = function() {
    try 
    {
        console.log("eventHandlers.defaultHandlers.RisultatiPreferenzeModifyAll", this, arguments);

        let id=arguments[0].postParams['refresh_obj_id']+"_PreferenzeCandidati";
        let candidati_list=$$(id);
        if(candidati_list)
        {
            filter = candidati_list.getFilter("lista");
        
            arguments[0].postParams['lista_desc']=filter.value;

            AA_MainApp.utils.callHandler('dlg',arguments[0],'<?php echo AA_SierModule::AA_ID_MODULE?>');
        }
        else
        {
            console.log("eventHandlers.defaultHandlers.RisultatiPreferenzeModifyAll - lista candidati non trovata", id);
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
        if(task && params)
        {
            params.task=task;
            let circoscrizione_val="";
            let lista_val="";
            
            if(table)
            {
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
                    lista_val=String(lista.value);
                }
                else
                {
                    console.log("eventHandlers.defaultHandlers.AddNewCandidato - lista non valida.",lista);
                }
                
                params.params=[{circoscrizione_desc:circoscrizione_val},{lista_desc:String(lista_val)},id_object];
            }
            else
            {
                params.params=[id_object];
            }
            //console.log("eventHandlers.defaultHandlers.AddNewCandidato",params);

            AA_MainApp.utils.callHandler('dlg',params,'<?php echo AA_SierModule::AA_ID_MODULE?>');
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.AddNewCandidato", msg, this);
    }
};

//Esporta gli operatori comunali
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].ExportOperatoriComunali = function() {
    try 
    {
        //console.log("eventHandlers.defaultHandlers.ExportOperatoriComunali", this, arguments);
        window.open(<?php echo AA_SierModule::AA_ID_MODULE?>.taskManager+"?task="+arguments[0].task+"&id="+arguments[0].params.id);  
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.ExportOperatoriComunali", msg, this);
    }
};

//Esporta gli operatori comunali
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].ExportCorpoElettoraleCSV = function() {
    try 
    {
        //console.log("eventHandlers.defaultHandlers.ExportOperatoriComunali", this, arguments);
        window.open(<?php echo AA_SierModule::AA_ID_MODULE?>.taskManager+"?task="+arguments[0].task+"&id="+arguments[0].params.id);  
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.ExportOperatoriComunali", msg, this);
    }
};

//----------------------------------------------  Funzioni di reportistica  ----------------------------------------------
function AA_SierWebAppGenericParams()
{
    this.ui_prefix="<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI?>";
    this.data_url="";
    this.web_url="";
    this.privacy_policy_url="";
    this.enablePullToRefresh=false;
    this.affluenza=
    {
        regionale:
        {
            data: null,
            view_id: this.ui_prefix+"_AffluenzaBox",
            container_id:this.ui_prefix+"_AffluenzaContent",
            realtime_container_id: this.ui_prefix+"_AffluenzaRealtimeContent",
            footer_id:this.ui_prefix+"_Affluenza_Footer",
            aggiornamento:null
        },
        circoscrizionale:
        {
            circoscrizione:0,
            data: null,
            view_id:this.ui_prefix+"_AffluenzaCircoscrizionaleBox",
            container_id:this.ui_prefix+"_AffluenzaCircoscrizionaleContent",
            realtime_container_id:this.ui_prefix+"_AffluenzaCircoscrizionaleRealtimeContent",
            footer_id:this.ui_prefix+"_AffluenzaCircoscrizionale_Footer",
            aggiornamento:null
        }
    };
    this.risultati=
    {
        aggiornamento: null,
        id_circoscrizione:0,
        id_comune:0,
        livello_dettaglio_label:"tutta la Regione Sardegna",
        data:null,
        view_id:this.ui_prefix+"_PresidentiBox",
        container_id:this.ui_prefix+"_PresidentiContent",
        realtime_container_id:this.ui_prefix+"_PresidentiRealtimeContent",
        footer_id:this.ui_prefix+"_Presidenti_Footer",
        liste:
        {
            aggiornamento: null,
            id_coalizione:0,
            data: null,
            view_id:this.ui_prefix+"_ListeBox",
            container_id:this.ui_prefix+"_ListeContent",
            realtime_container_id:this.ui_prefix+"_ListeRealtimeContent",
            footer_id:this.ui_prefix+"_Liste_Footer",
        },
        candidati:
        {
            aggiornamento: null,
            id_lista:0,
            data: null,
            view_id:this.ui_prefix+"_CandidatiBox",
            container_id:this.ui_prefix+"_CandidatiContent",
            realtime_container_id:this.ui_prefix+"_CandidatiRealtimeContent",
            footer_id:this.ui_prefix+"_Candidati_Footer"
        }
    };
    this.mainUi_id=this.ui_prefix;
    this.autoUpdateTime = 300000;
    this.sezione_corrente = this.ui_prefix+"_AffluenzaBox";
    this.timeoutRisultati = null;
    this.livello_dettaglio_data_tree = [{ id: "1", value: "tutta la Regione Sardegna (1)", "open":true, comune:0, circoscrizione:0, data:[]}];
    this.livello_dettaglio_view_id = this.ui_prefix+"_DettaglioTreeBox";
    this.livello_dettaglio_prev_view_id= null;
    this.embedded= true;
    this.data=null;
}

var AA_SierWebAppParams = new AA_SierWebAppGenericParams();

//Embedded refresh app gui
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppRefreshUi = function() {
    try 
    {
        AA_SierWebAppParams.taskManager=<?php echo AA_SierModule::AA_ID_MODULE?>.taskManager;
        return AA_SierWebApp.RefreshUi(arguments[0],arguments[1]);
    } catch (msg) {
        console.error("<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppRefreshUi", msg);
    }
};

//Embedded refresh app gui
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].StartRisultatiApp = function() {
    try 
    {
        AA_SierWebAppParams.data_url=arguments[0]['url'];
        AA_SierWebAppParams.taskManager=<?php echo AA_SierModule::AA_ID_MODULE?>.taskManager;
        return AA_SierWebApp.StartApp(arguments[0]);
    } catch (msg) {
        console.error("<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].StartRisultatiApp", msg);
    }
};
//---------------------------------------------------------------------------------------------------------------------------

<?php
//parte operatori comunali
if(isset($_SESSION['oc_ui_enable']) && $_SESSION['oc_ui_enable']==1)
{
    echo "AA_MainApp.defaultModule='".AA_SierModule::AA_ID_MODULE."';";
    echo "AA_MainApp.defaultSidebarModule='sier';";
    echo "AA_MainApp.userAuth=function(){window.location.reload(true)};";
    echo "AA_MainApp.logIn=function(){window.location.reload(true)};";
    echo "AA_MainApp.resetPwd=AA_LogOut;";
    echo "AA_MainApp.register=function(){window.location.reload(true)};";
    echo "AA_MainApp.ui.MainUI.UserChangePwdDlg=function(){return true};";
}
else
{
    //AA_Log::Log(__METHOD__." - variabile di sessione non impostata",100);
}
?>

//Registrazione modulo
AA_MainApp.registerModule(<?php echo AA_SierModule::AA_ID_MODULE?>);

