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
                    lista_val=lista.value;
                }
                else
                {
                    console.log("eventHandlers.defaultHandlers.AddNewCandidato - lista non valida.",lista);
                }
                
                params.params=[{circoscrizione_desc:circoscrizione_val},{lista_desc:lista_val},id_object];
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
var AA_SierWebAppParams={
    affluenza:{
        regionale:
        {
            data: null,
            view_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_AffluenzaBox"?>",
            container_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_AffluenzaContent"?>",
            realtime_container_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_AffluenzaRealtimeContent"?>",
            footer_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_Affluenza_Footer"?>",
            aggiornamento:null
        },
        circoscrizionale:
        {
            circoscrizione:0,
            data: null,
            view_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_AffluenzaCircoscrizionaleBox"?>",
            container_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_AffluenzaCircoscrizionaleContent"?>",
            realtime_container_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_AffluenzaCircoscrizionaleRealtimeContent"?>",
            footer_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_AffluenzaCircoscrizionale_Footer"?>",
            aggiornamento:null
        }
    },
    risultati:{
        aggiornamento: null,
        id_circoscrizione:0,
        id_comune:0,
        livello_dettaglio_label:"Tutta la Regione Sardegna",
        data:null,
        view_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_PresidentiBox"?>",
        container_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_PresidentiContent"?>",
        realtime_container_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_PresidentiRealtimeContent"?>",
        footer_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_Presidenti_Footer"?>",
        liste:
        {
            aggiornamento: null,
            id_coalizione:0,
            data: null,
            view_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_ListeBox"?>",
            container_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_ListeContent"?>",
            realtime_container_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_ListeRealtimeContent"?>",
            footer_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_Liste_Footer"?>",
        },
        candidati:
        {
            aggiornamento: null,
            id_lista:0,
            data: null,
            view_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_CandidatiBox"?>",
            container_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_CandidatiContent"?>",
            realtime_container_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_CandidatiRealtimeContent"?>",
            footer_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_Candidati_Footer"?>"
        }
    },
    ui_prefix:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI?>",
    sezione_corrente:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_AffluenzaBox"?>",
    timeoutRisultati:null,
    data:null
}

//Inizializza l'app dei risultati
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].StartRisultatiApp = async function() {
    try 
    {
        //build UI if not present
        if(!$$("<?php echo AA_SierModule::AA_ID_APP?>"))
        {
            let result = await AA_VerboseTask("GetSierWebApp", <?php echo AA_SierModule::AA_ID_MODULE?>.taskManager);
            if (result.status.value == 0) 
            {
                //---------  Show App  --------------
                let wnd = webix.ui(result.content.value);
                wnd.show();

                //inizializzazione
                AA_SierWebAppParams.data=null;
                AA_SierWebAppParams.sezione_corrente="<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_AffluenzaBox"?>";
                AA_SierWebAppParams.affluenza.regionale.data=null;
                AA_SierWebAppParams.affluenza.regionale.aggiornamento=null;
                AA_SierWebAppParams.affluenza.circoscrizionale.data=null;
                AA_SierWebAppParams.affluenza.circoscrizionale.aggiornamento=null;
                <?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppRefreshUi(null,AA_SierWebAppParams.sezione_corrente);
                //----------------------------------

                //-------- refresh data  -----------
                let url=arguments[0]['url'];
                <?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].RefreshRisultatiData(url,true);
                if(AA_SierWebAppParams.timeoutRisultati)
                {
                    clearTimeout(AA_SierWebAppParams.timeoutRisultati);
                }
                AA_SierWebAppParams.timeoutRisultati=setTimeout(<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].RefreshRisultatiData,600000,url,true);
                //-----------------------------------
            }
            else
            {
                console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.StartRisultatiApp",result.error.value);
            }
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.StartRisultatiApp", msg);
    }
};

//Rinfresca l'interfaccia web app
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppRefreshUi = async function() {
    try 
    {
        //console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi",arguments);
        AA_SierWebAppParams.sezione_corrente=arguments[1];
        let aggiornamento="";
        if(AA_SierWebAppParams.data)
        {
            date=new Date(AA_SierWebAppParams.data.aggiornamento);
            aggiornamento=date.toLocaleDateString('it-IT',{
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour12: false,
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        //---------------------------- Affluenza regione-----------------------------------
        if(arguments[1]==AA_SierWebAppParams.affluenza.regionale.view_id)
        {
            <?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppUpdateAffluenzaData();
        
            if(AA_SierWebAppParams.affluenza.regionale.aggiornamento)
            {
                date=new Date(AA_SierWebAppParams.affluenza.regionale.aggiornamento);
                aggiornamento=date.toLocaleDateString('it-IT',{
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            //Rimuove la view precedente
            if($$(AA_SierWebAppParams.affluenza.regionale.realtime_container_id))
            {
                console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - rimuovo il box affluenza: "+AA_SierWebAppParams.affluenza.regionale.realtime_container_id);
                $$(AA_SierWebAppParams.affluenza.regionale.realtime_container_id).destructor();

                if($$(AA_SierWebAppParams.affluenza.regionale.footer_id))
                {
                    $$(AA_SierWebAppParams.affluenza.regionale.footer_id).parse({"footer":"&nbsp;"});
                }
            }

            if(AA_SierWebAppParams.affluenza.regionale.data==null)
            {
                let preview_template={
                    id: AA_SierWebAppParams.affluenza.regionale.realtime_container_id,
                    container: AA_SierWebAppParams.affluenza.regionale.container_id,
                    view:"template",
                    borderless: true,
                    css:{"background-color":"#f4f5f9"},
                    template: "<div style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; font-size: larger; font-weight: 600; color: rgb(0, 102, 153);' class='blinking'>Caricamento in corso...</div>"
                };

                webix.ui(preview_template).show();
                
                return;
            }

            console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - Aggiorno il box affluenza: "+AA_SierWebAppParams.affluenza.regionale.view_id);

            //Aggiorna l'affluenza
            let affluenza_cols=[
                {id:"denominazione",header:["<div style='text-align: left'>Circoscrizione</div>"],"fillspace":true, "sort":"text","css":{"text-align":"left"}},
                {id:"count",header:["<div style='text-align: right'>votanti</div>"],"width":90, "sort":"text","css":{"text-align":"right"}},
                {id:"percent",header:["<div style='text-align: right'>%<sup>*</sup></div>"],"width":60, "sort":"text","css":{"text-align":"right"}}
            ];
            //console.log("eventHandlers.defaultHandlers.RefreshRisultatiData - affluenza_cols",affluenza_cols);
    
            if($$(AA_SierWebAppParams.affluenza.regionale.container_id))
            {
                console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - implemento il box affluenza: "+AA_SierWebAppParams.affluenza.regionale.realtime_container_id);
                let votanti_tot=0;
                let elettori_tot=0;
                for(let affluenza_data of AA_SierWebAppParams.affluenza.regionale.data)
                {
                    votanti_tot+=affluenza_data.count;
                    elettori_tot+=affluenza_data.elettori;
                }
                let votanti_percent=0;
                if(elettori_tot>0) votanti_percent=new Intl.NumberFormat('it-IT').format(Number(votanti_tot/elettori_tot).toFixed(1));
                if(votanti_percent==0 && votanti_tot>0) votanti_percent='&lt;0,1';
                elettori_tot=new Intl.NumberFormat('it-IT').format(Number(elettori_tot));
                votanti_tot=new Intl.NumberFormat('it-IT').format(Number(votanti_tot));
                let affluenza_box={
                    id: AA_SierWebAppParams.affluenza.regionale.realtime_container_id,
                    view: "layout",
                    css:{"background-color":"#f4f5f9"},
                    container: AA_SierWebAppParams.affluenza.regionale.container_id,
                    type:"clean",
                    rows:
                    [
                        {height: 10},
                        {
                            view:"template",
                            template: "<div style='display:flex;align-items:center; justify-content:space-between; height:100%; width:100%; flex-direction: column;'><div style='font-size:larger; font-weight:bold; border-bottom:1px solid #b6bcbf;width:70%;text-align: center; color: #0c467f;'>Regione Sardegna</div><div style='display:flex;align-items:center; justify-content:space-between;height:100px; width:100%'><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; font-weight: 600; width:33%; color: #0c467f; border-right: 1px solid #dadee0'><span>ELETTORI</span><hr style='width:96%;color: #eef9ff'><span>#elettori#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center;font-weight: 600; width:33%; color: #0c467f'><span>VOTANTI</span><hr style='width:100%; color: #eef9ff'><span>#votanti#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; width:33%; font-weight:700; font-size: 24px; color: #0c467f'><span>#percent#%</span></div></div></div>",
                            data:{votanti : votanti_tot,percent: votanti_percent,elettori:elettori_tot},
                            height: 140,
                            css: {"border-radius": "15px","border-width":"1px 1px 1px !important"}
                        },
                        {height: 10},
                        {
                            type:"space",
                            css:{"border-radius":"15px","background-color":"#fff"},
                            rows:
                            [
                                {
                                    template:"<div style='font-weight:bold; border-bottom:1px solid #b6bcbf;width:100%;text-align: center'>Dettaglio per circoscrizione</div>",
                                    autoheight: true,
                                    borderless: true,
                                },
                                {
                                    view:"datatable",
                                    scrollX:false,
                                    select:false,
                                    autoheight: true,
                                    css:"AA_Header_DataTable",
                                    scheme:{$change:function(item)
                                        {
                                            if (item.number%2) item.$css = "AA_DataTable_Row_AlternateColor";
                                        }
                                    },
                                    columns:affluenza_cols,
                                    data: AA_SierWebAppParams.affluenza.regionale.data
                                },
                                {
                                    template:"<div style='font-size:smaller; width:100%;text-align: left'><i>*I valori percentuale sono riferiti agli elettori totali della circoscrizione.</i></div>",
                                    autoheight: true,
                                    borderless: true,
                                }
                            ]
                        },
                        {}
                    ]
                };
                
                let affluenza_ui=webix.ui(affluenza_box);
                if(affluenza_ui) 
                {
                    console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - visualizzo il box affluenza: "+AA_SierWebAppParams.affluenza.regionale.container_id);
                    affluenza_ui.show();
                }

                if($$(AA_SierWebAppParams.affluenza.regionale.footer_id))
                {
                    $$(AA_SierWebAppParams.affluenza.regionale.footer_id).parse({"footer":"Dati aggiornati al "+aggiornamento});
                }
            }
            else
            {
                console.error("eventHandlers.defaultHandlers.SierWebAppRefreshUi - Errore nell'aggiornamento del box affluenza.");
            }
        }
        //-------------------------------------------------------------------------------

        //---------------------------- Affluenza circoscrizionale-----------------------------------
        if(arguments[1]==AA_SierWebAppParams.affluenza.circoscrizionale.view_id)
        {
            <?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppUpdateAffluenzaData();
            if(AA_SierWebAppParams.affluenza.circoscrizionale.aggiornamento)
            {
                date=new Date(AA_SierWebAppParams.affluenza.circoscrizionale.aggiornamento);
                aggiornamento=date.toLocaleDateString('it-IT',{
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            //Rimuove la view precedente
            if($$(AA_SierWebAppParams.affluenza.circoscrizionale.realtime_container_id))
            {
                console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - rimuovo il box affluenza: "+AA_SierWebAppParams.affluenza.circoscrizionale.realtime_container_id);
                $$(AA_SierWebAppParams.affluenza.circoscrizionale.realtime_container_id).destructor();

                if($$(AA_SierWebAppParams.affluenza.circoscrizionale.footer_id))
                {
                    $$(AA_SierWebAppParams.affluenza.circoscrizionale.footer_id).parse({"footer":"&nbsp;"});
                }
            }

            if(AA_SierWebAppParams.affluenza.circoscrizionale.data==null)
            {
                let preview_template={
                    id: AA_SierWebAppParams.affluenza.circoscrizionale.realtime_container_id,
                    container: AA_SierWebAppParams.circoscrizionale.regionale.container_id,
                    view:"template",
                    borderless: true,
                    css:{"background-color":"#f4f5f9"},
                    template: "<div style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; font-size: larger; font-weight: 600; color: rgb(0, 102, 153);' class='blinking'>Caricamento in corso...</div>"
                };

                webix.ui(preview_template).show();
                
                return;
            }

            console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - Aggiorno il box affluenza: "+AA_SierWebAppParams.affluenza.circoscrizionale.view_id);

            //Aggiorna l'affluenza
            let affluenza_cols=[
                {id:"denominazione",header:["<div style='text-align: left'>Comune</div>"],"fillspace":true, "sort":"text","css":{"text-align":"left"}},
                {id:"count",header:["<div style='text-align: right'>votanti</div>"],"width":90, "sort":"text","css":{"text-align":"right"}},
                {id:"percent",header:["<div style='text-align: right'>%<sup>*</sup></div>"],"width":60, "sort":"text","css":{"text-align":"right"}}
            ];
            //console.log("eventHandlers.defaultHandlers.RefreshRisultatiData - affluenza_cols",affluenza_cols);
    
            if($$(AA_SierWebAppParams.affluenza.circoscrizionale.container_id))
            {
                console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - implemento il box affluenza: "+AA_SierWebAppParams.affluenza.circoscrizionale.realtime_container_id);
                let votanti_tot=0;
                let elettori_tot=0;
                for(let affluenza_data of AA_SierWebAppParams.affluenza.circoscrizionale.data)
                {
                    votanti_tot+=affluenza_data.count;
                }
                elettori_tot=AA_SierWebAppParams.data.stats.circoscrizionale[AA_SierWebAppParams.affluenza.circoscrizionale.id_circoscrizione].elettori_tot;
                let votanti_percent=0;
                if(elettori_tot>0) votanti_percent=new Intl.NumberFormat('it-IT').format(Number(votanti_tot*100/elettori_tot).toFixed(1));
                if(votanti_percent==0 && votanti_tot>0) votanti_percent='&lt;0,1';
                elettori_tot=new Intl.NumberFormat('it-IT').format(Number(elettori_tot));
                votanti_tot=new Intl.NumberFormat('it-IT').format(Number(votanti_tot));
                let affluenza_box={
                    id: AA_SierWebAppParams.affluenza.circoscrizionale.realtime_container_id,
                    view: "layout",
                    css:{"background-color":"#f4f5f9"},
                    container: AA_SierWebAppParams.affluenza.circoscrizionale.container_id,
                    type:"clean",
                    rows:
                    [
                        {height: 10},
                        {
                            view:"template",
                            template: "<div style='display:flex;align-items:center; justify-content:space-between; height:100%; width:100%; flex-direction: column;'><div style='display:flex; justify-content: space-between; align-items:center; border-bottom:1px solid #b6bcbf;width:100%;'><a href='#' onClick='AA_SierWebAppParams.affluenza.circoscrizionale.id_circoscrizione=0;$$(AA_SierWebAppParams.affluenza.regionale.view_id).show();' style='font-weight: 700;font-size: larger;color: #0c467f;' title='Indietro'><span class='mdi mdi-keyboard-backspace'></span></a><div style='text-align:center'><span style='font-size:larger; font-weight:bold; color: #0c467f;'>"+AA_SierWebAppParams.data.stats.circoscrizionale[AA_SierWebAppParams.affluenza.circoscrizionale.id_circoscrizione].denominazione+"</span><br><span style='font-size: smaller'>circoscrizione</span></div><div>&nbsp;</div></div><div style='display:flex;align-items:center; justify-content:space-between;height:100px; width:100%'><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; font-weight: 600; width:33%; color: #0c467f; border-right: 1px solid #dadee0'><span>ELETTORI</span><hr style='width:96%;color: #eef9ff'><span>#elettori#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center;font-weight: 600; width:33%; color: #0c467f'><span>VOTANTI</span><hr style='width:100%; color: #eef9ff'><span>#votanti#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; width:33%; font-weight:700; font-size: 24px; color: #0c467f'><span>#percent#%</span></div></div></div>",
                            data:{votanti : votanti_tot,percent: votanti_percent,elettori:elettori_tot},
                            height: 140,
                            css: {"border-radius": "15px","border-width":"1px 1px 1px !important"}
                        },
                        {height: 10},
                        {
                            type:"space",
                            css:{"border-radius":"15px","background-color":"#fff"},
                            rows:
                            [
                                {
                                    template:"<div style='font-weight:bold; border-bottom:1px solid #b6bcbf;width:100%;text-align: center'>Dettaglio per comune</div>",
                                    autoheight: true,
                                    borderless: true,
                                },
                                {
                                    view:"datatable",
                                    scrollX:false,
                                    select:false,
                                    css:"AA_Header_DataTable",
                                    height: 300,
                                    scheme:{$change:function(item)
                                        {
                                            if (item.number%2) item.$css = "AA_DataTable_Row_AlternateColor";
                                        }
                                    },
                                    columns:affluenza_cols,
                                    data: AA_SierWebAppParams.affluenza.circoscrizionale.data
                                },
                                {
                                    template:"<div style='font-size:smaller; width:100%;text-align: left'><i>*I valori percentuale sono riferiti agli elettori totali del comune.</i></div>",
                                    autoheight: true,
                                    borderless: true,
                                }
                            ]
                        },
                        {}
                    ]
                };
                
                let affluenza_ui=webix.ui(affluenza_box);
                if(affluenza_ui) 
                {
                    console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - visualizzo il box affluenza: "+AA_SierWebAppParams.affluenza.circoscrizionale.view_id);
                    affluenza_ui.show();
                }

                if($$(AA_SierWebAppParams.affluenza.circoscrizionale.footer_id))
                {
                    $$(AA_SierWebAppParams.affluenza.circoscrizionale.footer_id).parse({"footer":"Dati aggiornati al "+aggiornamento});
                }
                //------------------------------------------------------------------------------------------------------------------------
            }
            else
            {
                console.error("eventHandlers.defaultHandlers.SierWebAppRefreshUi - Errore nell'aggiornamento del box affluenza.");
            }
        }
        //------------------------------------------------------------------------------------------

        //---------------------------------------- Risultati Presidente/coalizione ----------------------------------
        if(arguments[1]==AA_SierWebAppParams.risultati.view_id)
        {
            <?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppUpdateRisultatiData();
            if(AA_SierWebAppParams.risultati.aggiornamento)
            {
                date=new Date(AA_SierWebAppParams.risultati.aggiornamento);
                aggiornamento=date.toLocaleDateString('it-IT',{
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            //Rimuove la view precedente
            if($$(AA_SierWebAppParams.risultati.realtime_container_id))
            {
                console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - rimuovo il box risultati: "+AA_SierWebAppParams.risultati.realtime_container_id);
                $$(AA_SierWebAppParams.risultati.realtime_container_id).destructor();

                if($$(AA_SierWebAppParams.risultati.footer_id))
                {
                    $$(AA_SierWebAppParams.risultati.footer_id).parse({"footer":"&nbsp;"});
                }
            }

            if(AA_SierWebAppParams.risultati.data==null)
            {
                let preview_template={
                    id: AA_SierWebAppParams.risultati.realtime_container_id,
                    container: AA_SierWebAppParams.risultati.container_id,
                    view:"template",
                    borderless: true,
                    css:{"background-color":"#f4f5f9"},
                    template: "<div style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; font-size: larger; font-weight: 600; color: rgb(0, 102, 153);' class='blinking'>Caricamento in corso...</div>"
                };

                webix.ui(preview_template).show();
                
                return;
            }

            console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - Aggiorno il box risultati: "+AA_SierWebAppParams.risultati.container_id);
            if($$(AA_SierWebAppParams.risultati.container_id))
            {
                console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - implemento il box risultati: "+AA_SierWebAppParams.risultati.realtime_container_id);
                let sezioni_percent=0;
                if(AA_SierWebAppParams.risultati.sezioni) sezioni_percent=new Intl.NumberFormat('it-IT').format(Number(AA_SierWebAppParams.risultati.sezioni_scrutinate*100/AA_SierWebAppParams.risultati.sezioni).toFixed(1));                
                let cursor="zoom-in";
                let risultati_box={
                    id: AA_SierWebAppParams.risultati.realtime_container_id,
                    view: "layout",
                    css:{"background-color":"#f4f5f9"},
                    container: AA_SierWebAppParams.risultati.container_id,
                    type:"clean",
                    rows:
                    [
                        {
                            view:"template",
                            template: "<div style='display:flex;align-items:center; justify-content:space-between; height:100%; width:100%; flex-direction: column;'><div style='display:flex;align-items:center; justify-content:space-between;height:60px; width:100%'><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; font-weight: 600; width:25%; color: #0c467f; border-right: 1px solid #dadee0'><span>SEZIONI</span><hr style='width:96%;color: #eef9ff'><span>#sezioni#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center;font-weight: 600; width:49%; color: #0c467f'><span>SEZ. SCRUTINATE</span><hr style='width:100%; color: #eef9ff'><span>#sezioni_scrutinate#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; width:25%; font-weight:700; font-size: 24px; color: #0c467f'><span>#percent#%</span></div></div></div>",
                            data:{sezioni : AA_SierWebAppParams.risultati.sezioni,sezioni_scrutinate: AA_SierWebAppParams.risultati.sezioni_scrutinate,percent: sezioni_percent},
                            height: 60,
                            css: {"border-radius": "15px","border-width":"1px 1px 1px !important"}
                        },
                        {height: 10},
                        {
                            view:"layout",
                            type:"clean",
                            css:{"border-radius":"15px","background-color":"#f4f5f9"},
                            borderless:true,
                            rows:
                            [
                                {
                                    view:"tabbar",
                                    css:"AA_SierWebAppHeader_TabBar",
                                    borderless: true,
                                    multiview:true,
                                    options:[{value:"Voti presidente",id:"voti_presidente"},{value:"Voti coalizione",id:"voti_coalizione"}]
                                },
                                {
                                    view: "multiview",
                                    css:{"margin-top":"2px !important","background":"transparent !important"},
                                    borderless: true,
                                    cells:
                                    [
                                        {
                                            id:"voti_presidente",
                                            view:"dataview",
                                            scrollX:false,
                                            xCount:1,
                                            select:false,
                                            borderless:true,
                                            css:{"background":"transparent","cursor":"default"},
                                            on:{"onItemClick":function(){AA_SierWebAppParams.risultati.liste.id_coalizione=arguments[0];$$(AA_SierWebAppParams.risultati.liste.view_id).show()}},
                                            type: {
                                                height: 50,
                                                width:"auto",
                                                css:"AA_SierWebAppDataviewItem"
                                            },
                                            template:"<div style='display: flex;justify-content: center; align-items: center; width: 100%; height:100%;cursor:"+cursor+"'><div style='display: flex; justify-content: space-between; align-items: center; width: 100%; height:96%; border: 1px solid #5ccce7;background: #fff; border-radius: 10px'><div style='width:35px;display:flex;align-items:center;justify-content:center'><img src='#image#' style='border-radius:50%; width:30px'></img></div><div style='width: 57%;text-align:left;font-weight: 500;color: #0c467f;'>&nbsp;#presidente#</div><div style='width:15%;text-align:right;font-size: smaller'>#voti#</div><div style='width: 60px;text-align:right;font-size:larger;font-weight:bold;color: #0c467f;'>#percent#%&nbsp;</div></div></div>",
                                            data: AA_SierWebAppParams.risultati.data
                                        },
                                        {
                                            id:"voti_coalizione",
                                            view:"dataview",
                                            scrollX:false,
                                            xCount:1,
                                            select:false,
                                            borderless:true,
                                            css:{"background":"transparent","cursor":"default"},
                                            on:{"onItemClick":function(){AA_SierWebAppParams.risultati.liste.id_coalizione=arguments[0];$$(AA_SierWebAppParams.risultati.liste.view_id).show()}},
                                            type: {
                                                height: 50,
                                                width:"auto",
                                                css:"AA_SierWebAppDataviewItem"
                                            },
                                            template:"<div style='display: flex;justify-content: center; align-items: center; width: 100%; height:100%;cursor:"+cursor+"'><div style='display: flex; justify-content: space-between; align-items: center; width: 100%; height:96%; border: 1px solid #5ccce7;background: #fff; border-radius: 10px'><div style='width:35px;display:flex;align-items:center;justify-content:center'><img src='#image#' style='border-radius:50%; width:30px'></img></div><div style='width:57%; text-align:left;font-weight: 500;color: #0c467f;'>&nbsp;#presidente#</div><div style='width:15%;text-align:right;font-size: smaller'>#voti_coalizione#</div><div style='width: 60px;text-align:right;font-size:larger;font-weight:bold;color: #0c467f;'>#percent_coalizione#%&nbsp;</div></div></div>",
                                            data: AA_SierWebAppParams.risultati.data                                    
                                        }
                                    ]
                                }
                            ]
                        },
                        {height:10}
                    ]
                };
                
                let risultati_ui=webix.ui(risultati_box);
                if(risultati_box) 
                {
                    console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - visualizzo il box risultati: "+AA_SierWebAppParams.risultati.view_id);
                    risultati_ui.show();
                }

                if($$(AA_SierWebAppParams.risultati.footer_id))
                {
                    $$(AA_SierWebAppParams.risultati.footer_id).parse({"footer":"Dati aggiornati al "+aggiornamento});
                }
            }
        }
        //------------------------------------------------------------------------------------------------

        //---------------------------------------- Risultati Liste (coalizione) ----------------------------------
        if(arguments[1]==AA_SierWebAppParams.risultati.liste.view_id)
        {
            <?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppUpdateRisultatiData();
            if(AA_SierWebAppParams.risultati.liste.aggiornamento)
            {
                date=new Date(AA_SierWebAppParams.risultati.liste.aggiornamento);
                aggiornamento=date.toLocaleDateString('it-IT',{
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            //Rimuove la view precedente
            if($$(AA_SierWebAppParams.risultati.liste.realtime_container_id))
            {
                console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - rimuovo il box risultati: "+AA_SierWebAppParams.risultati.liste.realtime_container_id);
                $$(AA_SierWebAppParams.risultati.liste.realtime_container_id).destructor();

                if($$(AA_SierWebAppParams.risultati.liste.footer_id))
                {
                    $$(AA_SierWebAppParams.risultati.liste.footer_id).parse({"footer":"&nbsp;"});
                }
            }

            if(AA_SierWebAppParams.risultati.liste.data==null)
            {
                let preview_template={
                    id: AA_SierWebAppParams.risultati.liste.realtime_container_id,
                    container: AA_SierWebAppParams.risultati.liste.container_id,
                    view:"template",
                    borderless: true,
                    css:{"background-color":"#f4f5f9"},
                    template: "<div style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; font-size: larger; font-weight: 600; color: rgb(0, 102, 153);' class='blinking'>Caricamento in corso...</div>"
                };

                webix.ui(preview_template).show();
                
                return;
            }

            console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - Aggiorno il box risultati: "+AA_SierWebAppParams.risultati.liste.container_id);
            if($$(AA_SierWebAppParams.risultati.liste.container_id))
            {
                console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - implemento il box risultati: "+AA_SierWebAppParams.risultati.liste.realtime_container_id);
                let risultati=AA_SierWebAppParams.data;
                let dettaglio=risultati.stats.regionale.risultati;
                if(AA_SierWebAppParams.risultati.id_circoscrizione>0 ) dettaglio=risultati.stats.circoscrizionale[AA_SierWebAppParams.risultati.id_circoscrizione].risultati;
                if(AA_SierWebAppParams.risultati.id_comune>0 ) dettaglio=risultati.comuni[AA_SierWebAppParams.risultati.id_comune].risultati;
                let voti_percent=0;
                if(dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].percent_coalizione > 0) voti_percent=new Intl.NumberFormat('it-IT').format(Number(dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].percent_coalizione).toFixed(1));                
                let cursor="zoom-in";
                if(AA_SierWebAppParams.risultati.id_circoscrizione == 0)
                {
                    cursor="default";
                }
                let risultati_box={
                    id: AA_SierWebAppParams.risultati.liste.realtime_container_id,
                    view: "layout",
                    css:{"background-color":"#f4f5f9"},
                    container: AA_SierWebAppParams.risultati.liste.container_id,
                    type:"clean",
                    rows:
                    [
                        {
                            view:"template",
                            template: "<div style='display:flex;align-items:center; justify-content:space-between; height:100%; width:100%; flex-direction: column;'><div style='display:flex; justify-content: space-between; align-items:center; border-bottom:1px solid #b6bcbf;width:100%'><a href='#' onClick='AA_SierWebAppParams.risultati.liste.id_coalizione=0;$$(AA_SierWebAppParams.risultati.view_id).show();' style='font-weight: 700;font-size: larger;color: #0c467f;' title='Indietro'><span class='mdi mdi-keyboard-backspace'></span></a><div style='text-align:center;'><span style='font-size:larger; font-weight:bold; color: #0c467f;'>"+dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].denominazione+"</span><br><span style='font-size: smaller'>dettaglio coalizione</span></div><span>&nbsp;</span></div><div style='display:flex;align-items:center; justify-content:space-between;height:60px; width:100%'><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; font-weight: 600; width:25%; color: #0c467f;'><span>VOTI</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center;font-weight: 600; width:49%; color: #0c467f'><span>#voti#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; width:25%; font-weight:700; font-size: 24px; color: #0c467f'><span>#percent#%</span></div></div></div>",
                            data:{voti : dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].voti_coalizione,percent: voti_percent},
                            height: 100,
                            css: {"border-radius": "15px","border-width":"1px 1px 1px !important"}
                        },
                        {height: 10},
                        {
                            view:"layout",
                            type:"clean",
                            css:{"border-radius":"15px","background-color":"#f4f5f9"},
                            borderless:true,
                            rows:
                            [
                                {
                                    view:"dataview",
                                    scrollX:false,
                                    xCount:1,
                                    select:false,
                                    borderless:true,
                                    css:{"background":"transparent","cursor": "default"},
                                    type: {
                                        height: 50,
                                        width:"auto",
                                        css:"AA_SierWebAppDataviewItem"
                                    },
                                    template:"<div style='display: flex;justify-content: center; align-items: center; width: 100%; height:100%;cursor:"+cursor+"'><div style='display: flex; justify-content: space-between; align-items: center; width: 100%; height:96%; border: 1px solid #5ccce7;background: #fff; border-radius: 10px'><div style='width:35px;display:flex;align-items:center;justify-content:center'><img src='#image#' style='border-radius:50%; width:30px'></img></div><div style='width: 57%;text-align:left;font-weight: 500;color: #0c467f;'>&nbsp;#denominazione#</div><div style='width:15%;text-align:right;font-size: smaller'>#voti#</div><div style='width: 60px;text-align:right;font-size:larger;font-weight:bold;color: #0c467f;'>#percent#%&nbsp;</div></div></div>",
                                    data: AA_SierWebAppParams.risultati.liste.data
                                }
                            ]
                        },
                        {height:10}
                    ]
                };

                
                
                let risultati_ui=webix.ui(risultati_box);
                if(risultati_box) 
                {
                    console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - visualizzo il box risultati: "+AA_SierWebAppParams.risultati.liste.view_id);
                    risultati_ui.show();
                }

                if($$(AA_SierWebAppParams.risultati.liste.footer_id))
                {
                    $$(AA_SierWebAppParams.risultati.liste.footer_id).parse({"footer":"Dati aggiornati al "+aggiornamento});
                }
            }
        }
        //------------------------------------------------------------------------------------------------

        //---------------------------------------- Risultati Candidati lista ----------------------------------
        if(arguments[1]==AA_SierWebAppParams.risultati.candidati.view_id)
        {
            <?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppUpdateRisultatiData();
            if(AA_SierWebAppParams.risultati.candidati.aggiornamento)
            {
                date=new Date(AA_SierWebAppParams.risultati.candidati.aggiornamento);
                aggiornamento=date.toLocaleDateString('it-IT',{
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            //Rimuove la view precedente
            if($$(AA_SierWebAppParams.risultati.candidati.realtime_container_id))
            {
                console.log("eventHandlers.defaultHandlers.SierWebAppRefreshUi - rimuovo il box risultati: "+AA_SierWebAppParams.risultati.candidati.realtime_container_id);
                $$(AA_SierWebAppParams.risultati.candidati.realtime_container_id).destructor();

                if($$(AA_SierWebAppParams.risultati.candidati.footer_id))
                {
                    $$(AA_SierWebAppParams.risultati.candidati.footer_id).parse({"footer":"&nbsp;"});
                }
            }

            if(AA_SierWebAppParams.risultati.candidati.data==null)
            {
                let preview_template={
                    id: AA_SierWebAppParams.risultati.candidati.realtime_container_id,
                    container: AA_SierWebAppParams.risultati.candidati.container_id,
                    view:"template",
                    borderless: true,
                    css:{"background-color":"#f4f5f9"},
                    template: "<div style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; font-size: larger; font-weight: 600; color: rgb(0, 102, 153);' class='blinking'>Caricamento in corso...</div>"
                };

                webix.ui(preview_template).show();
                
                return;
            }
        }
        //------------------------------------------------------------------------------------------------
    }catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.SierWebAppRefreshUi", msg);
    }
}

//Aggiorna i dati dell'affluenza
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppUpdateAffluenzaData = async function() {
    try 
    {
        let affluenza_data=[];
        AA_SierWebAppParams.affluenza.regionale.data=null;
        AA_SierWebAppParams.affluenza.regionale.aggiornamento=null;
     
        let risultati=AA_SierWebAppParams.data;
        if(!risultati) return;
        
        //--------------------- Affluenza Regione-----------------------------
        console.log("eventHandlers.defaultHandlers.SierWebAppUpdateAffluenzaData - Aggiorno i dati  affluenza Regione");
        if(risultati.stats.circoscrizionale)
        {
            //console.log("eventHandlers.defaultHandlers.SierWebAppUpdateAffluenzaData",risultati.stats.circoscrizionale);
            let num=1;
            for(let idCircoscrizione in risultati.stats.circoscrizionale)
            {
                //console.log("eventHandlers.defaultHandlers.SierWebAppUpdateAffluenzaData - circoscrizione",idCircoscrizione);
                let count=0;
                let percent=0;
                for(let giornata in risultati.stats.circoscrizionale[idCircoscrizione].affluenza)
                {
                    if(AA_SierWebAppParams.affluenza.regionale.aggiornamento==null || risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].aggiornamento > AA_SierWebAppParams.affluenza.regionale.aggiornamento) AA_SierWebAppParams.affluenza.regionale.aggiornamento=risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].aggiornamento;
                    if(risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_12.count > 0)  
                    {
                        count=risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_12.count;
                        percent=risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_12.percent;
                    }
                    if(risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_19.count > 0)  
                    {
                        count=risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_19.count;
                        percent=risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_19.percent;
                    }
                    if(risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_22.count > 0)  
                    {
                        count=risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_22.count;
                        percent=risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_22.percent;
                    }                            
                }
                let script="AA_SierWebAppParams.affluenza.circoscrizionale.id_circoscrizione="+idCircoscrizione+";$$(AA_SierWebAppParams.affluenza.circoscrizionale.view_id).show();";
                affluenza_data.push({"number":num,"id":idCircoscrizione,"denominazione":"<a href='#' onClick='"+script+"'>"+risultati.stats.circoscrizionale[idCircoscrizione].denominazione+"</a>","count":count,"percent":percent,
                    "elettori":risultati.stats.circoscrizionale[idCircoscrizione].elettori_tot,
                });
                num++;
            }
            AA_SierWebAppParams.affluenza.regionale.data=affluenza_data;
        }

        //circoscrizionale
        AA_SierWebAppParams.affluenza.circoscrizionale.data=null;
        AA_SierWebAppParams.affluenza.circoscrizionale.aggiornamento=null;
        if(AA_SierWebAppParams.affluenza.circoscrizionale.id_circoscrizione > 0 && risultati.comuni)
        {
            console.log("eventHandlers.defaultHandlers.SierWebAppUpdateAffluenzaData - Aggiorno i dati circoscrizione di "+risultati.stats.circoscrizionale[AA_SierWebAppParams.affluenza.circoscrizionale.id_circoscrizione].denominazione);
            AA_SierWebAppParams.affluenza.circoscrizionale.data=[];
            let num=1;
            for(let comune in risultati.comuni)
            {
                if(risultati.comuni[comune].id_circoscrizione == AA_SierWebAppParams.affluenza.circoscrizionale.id_circoscrizione)
                {
                    let count=0;
                    let percent=0;
                    for(let giornata in risultati.comuni[comune].affluenza)
                    {
                        if(AA_SierWebAppParams.affluenza.circoscrizionale.aggiornamento==null || risultati.comuni[comune].affluenza[giornata].aggiornamento > AA_SierWebAppParams.affluenza.circoscrizionale.aggiornamento) AA_SierWebAppParams.affluenza.circoscrizionale.aggiornamento=risultati.comuni[comune].affluenza[giornata].aggiornamento;
                        if(risultati.comuni[comune].affluenza[giornata].ore_12.count > 0)  
                        {
                            count=risultati.comuni[comune].affluenza[giornata].ore_12.count;
                            percent=risultati.comuni[comune].affluenza[giornata].ore_12.percent;
                        }
                        if(risultati.comuni[comune].affluenza[giornata].ore_19.count > 0)  
                        {
                            count=risultati.comuni[comune].affluenza[giornata].ore_19.count;
                            percent=risultati.comuni[comune].affluenza[giornata].ore_19.percent;
                        }
                        if(risultati.comuni[comune].affluenza[giornata].ore_22.count > 0)  
                        {
                            count=risultati.comuni[comune].affluenza[giornata].ore_22.count;
                            percent=risultati.comuni[comune].affluenza[giornata].ore_22.percent;
                        }
                    }

                    AA_SierWebAppParams.affluenza.circoscrizionale.data.push({number:num, id:comune,"denominazione":risultati.comuni[comune].denominazione,"count":count,"percent":percent});
                    num++;
                }
            }
            //console.log("eventHandlers.defaultHandlers.SierWebAppUpdateAffluenzaData - dati circoscrizione di "+risultati.stats.circoscrizionale[AA_SierWebAppParams.affluenza.circoscrizionale.id_circoscrizione], AA_SierWebAppParams.affluenza.circoscrizionale.data);
        }
        //-------------------------------------------------------------------
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.StartRisultatiApp", msg);
    }
};

//Aggiorna i dati dei risultati
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppUpdateRisultatiData = async function() {
    try 
    {
        AA_SierWebAppParams.risultati.data=null;
        AA_SierWebAppParams.risultati.liste.data=null;
        AA_SierWebAppParams.risultati.candidati.data=null;
        AA_SierWebAppParams.risultati.aggiornamento=null;
        AA_SierWebAppParams.risultati.liste.aggiornamento=null;
        AA_SierWebAppParams.risultati.candidati.aggiornamento=null;
     
        let risultati=AA_SierWebAppParams.data;
        if(!risultati) return;
        if(!risultati.stats) return;
        if(!risultati.stats.regionale) return;
        if(!risultati.stats.regionale.risultati) return;
        if(!risultati.stats.circoscrizionale) return;
        if(!risultati.comuni) return;

        fmtNumber= new Intl.NumberFormat('it-IT');
        
        //--------------------- Risultati Presidenti/coalizioni-----------------------------
        console.log("eventHandlers.defaultHandlers.SierWebAppUpdateRisultatiData - Aggiorno i dati  risultati Presidenti/coalizioni");
        let dettaglio=risultati.stats.regionale.risultati.voti_presidente;
        AA_SierWebAppParams.risultati.sezioni=risultati.stats.regionale.sezioni;
        AA_SierWebAppParams.risultati.sezioni_scrutinate=risultati.stats.regionale.risultati.sezioni_scrutinate;
        if(AA_SierWebAppParams.risultati.id_circoscrizione>0 ) 
        {
            dettaglio=risultati.stats.circoscrizionale[AA_SierWebAppParams.risultati.id_circoscrizione].risultati.voti_presidente;
            AA_SierWebAppParams.risultati.sezioni=risultati.stats.circoscrizionale[AA_SierWebAppParams.risultati.id_circoscrizione].sezioni;
            AA_SierWebAppParams.risultati.sezioni_scrutinate=risultati.stats.circoscrizionale[AA_SierWebAppParams.risultati.id_circoscrizione].risultati.sezioni_scrutinate;
        }
        if(AA_SierWebAppParams.risultati.id_comune>0 ) 
        {
            dettaglio=risultati.comuni[AA_SierWebAppParams.risultati.id_comune].risultati.voti_presidente;
            AA_SierWebAppParams.risultati.sezioni=risultati.comuni[AA_SierWebAppParams.risultati.id_comune].sezioni;
            AA_SierWebAppParams.risultati.sezioni_scrutinate=risultati.comuni[AA_SierWebAppParams.risultati.id_comune].risultati.sezioni_scrutinate;
        }

        //console.log("eventHandlers.defaultHandlers.SierWebAppUpdateRisultatiData",risultati.stats.regionale);
        AA_SierWebAppParams.risultati.data=[];
        AA_SierWebAppParams.risultati.aggiornamento=dettaglio.aggiornamento;
        for(let idCoalizione in dettaglio)
        {
            if(typeof dettaglio[idCoalizione] === 'object')
            {
                //console.log("eventHandlers.defaultHandlers.SierWebAppUpdateRisultatiData - coalizione",idCoalizione);
                AA_SierWebAppParams.risultati.data.push({id:idCoalizione,"presidente":dettaglio[idCoalizione].denominazione,denominazione_coalizione:"Coalizione "+dettaglio[idCoalizione].denominazione,"voti":fmtNumber.format(Number(dettaglio[idCoalizione].voti)),"percent":fmtNumber.format(Number(dettaglio[idCoalizione].percent)),"voti_coalizione":fmtNumber.format(Number(dettaglio[idCoalizione].voti_coalizione)),"percent_coalizione":fmtNumber.format(Number(dettaglio[idCoalizione].percent_coalizione)),"image":"https://amministrazioneaperta.regione.sardegna.it"+dettaglio[idCoalizione].image});
            }
        }
    
        //console.log("eventHandlers.defaultHandlers.SierWebAppUpdateRisultatiData - AA_SierWebAppParams.risultati.data",AA_SierWebAppParams.risultati.data);
        //-------------------------------------------------------------------

        //--------------------- Risultati Liste -----------------------------
        if(AA_SierWebAppParams.risultati.liste.id_coalizione > 0)
        {
            dettaglio=risultati.stats.regionale.risultati;
            if(AA_SierWebAppParams.risultati.id_circoscrizione>0 ) dettaglio=risultati.stats.circoscrizionale[AA_SierWebAppParams.risultati.id_circoscrizione].risultati;
            if(AA_SierWebAppParams.risultati.id_comune>0 ) dettaglio=risultati.comuni[AA_SierWebAppParams.risultati.id_comune].risultati;
            
            console.log("eventHandlers.defaultHandlers.SierWebAppUpdateRisultatiData - Aggiorno i dati  risultati liste della coalizione: "+dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].denominazione);
       
            AA_SierWebAppParams.risultati.liste.data=[];
            AA_SierWebAppParams.risultati.liste.aggiornamento=dettaglio.voti_lista.aggiornamento;
            for(let idLista in dettaglio.voti_lista)
            {
                if(typeof dettaglio.voti_lista[idLista] === 'object' && dettaglio.voti_lista[idLista].id_presidente==AA_SierWebAppParams.risultati.liste.id_coalizione)
                {
                    AA_SierWebAppParams.risultati.liste.data.push({id:idLista,"denominazione":dettaglio.voti_lista[idLista].denominazione,"presidente":risultati.stats.regionale.risultati.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].denominazione,denominazione_coalizione:"Coalizione "+risultati.stats.regionale.risultati.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].denominazione,"voti":fmtNumber.format(Number(dettaglio.voti_lista[idLista].voti)),"percent":fmtNumber.format(Number(dettaglio.voti_lista[idLista].percent)),"image":"https://amministrazioneaperta.regione.sardegna.it"+dettaglio.voti_lista[idLista].image});
                }
            }
        
            //console.log("eventHandlers.defaultHandlers.SierWebAppUpdateRisultatiData - AA_SierWebAppParams.risultati.liste.data",AA_SierWebAppParams.risultati.liste.data);
        }
        //-------------------------------------------------------------------

        //--------------------- Risultati candidato -----------------------------
        if(AA_SierWebAppParams.risultati.candidati.id_lista > 0 && AA_SierWebAppParams.risultati.id_circoscrizione > 0)
        {
            dettaglio=risultati.stats.circoscrizionale[AA_SierWebAppParams.risultati.id_circoscrizione].risultati;
            if(AA_SierWebAppParams.risultati.id_comune > 0 ) dettaglio=risultati.comuni[AA_SierWebAppParams.risultati.id_comune].risultati;
            
            console.log("eventHandlers.defaultHandlers.SierWebAppUpdateRisultatiData - Aggiorno i dati  risultati candidati della lista: "+dettaglio.voti_lista[AA_SierWebAppParams.risultati.candidati.id_lista].denominazione);
       
            AA_SierWebAppParams.risultati.candidati.aggiornamento=dettaglio.voti_candidato.aggiornamento;

            AA_SierWebAppParams.risultati.candidati.data=[];
            for(let idCandidato in dettaglio.voti_candidato)
            {
                if(typeof dettaglio.voti_candidato[idCandidato] === 'object' && risultati.candidati[idCandidato].id_lista==AA_SierWebAppParams.risultati.candidati.id_lista)
                {
                    AA_SierWebAppParams.risultati.candidati.data.push({id:idCandidato,"denominazione":risultati.candidati[idCandidato].nome+" "+risultati.candidati[idCandidato].cognome,"presidente":risultati.candidati[idCandidato].presidente,lista:risultati.candidati[idCandidato].lista,"voti":dettaglio.voti_candidato[idCandidato].voti,"percent":dettaglio.voti_candidato[idCandidato].percent,"image":"https://amministrazioneaperta.regione.sardegna.it"+risultati.candidati[idCandidato].image});
                }
            }
        
            console.log("eventHandlers.defaultHandlers.SierWebAppUpdateRisultatiData - AA_SierWebAppParams.risultati.candidati.data",AA_SierWebAppParams.risultati.candidati.data);
        }
        //-------------------------------------------------------------------

    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.SierWebAppUpdateRisultatiData", msg);
    }
};

//Rinfresca i dati sui risultati
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].RefreshRisultatiData = async function(feed_url,updateView=true) {
    try 
    {
        console.log("eventHandlers.defaultHandlers.RefreshRisultatiData - recupero il feed",feed_url);
        webix.ajax().get(feed_url).then(function(data)
        {
            let risultati=data.json();
            AA_SierWebAppParams.data=risultati;

            //-------------- Livelli di dettaglio --------------------------
            AA_SierWebAppParams.livelli_dettaglio_data=[{"label":"tutta la Regione Sardegna","livello_dettaglio":0,"comune":0,"circoscrizione":0}];
            for(let comune in risultati.comuni)
            {
                AA_SierWebAppParams.livelli_dettaglio_data.push({"label":"comune di "+risultati.comuni[comune].denominazione,"livello_dettaglio":2,"comune":comune,"circoscrizione":risultati.comuni[comune].id_circoscrizione});
            }
            for(let circoscrizione in risultati.stats.circoscrizionale)
            {
                AA_SierWebAppParams.livelli_dettaglio_data.push({"label":"Circoscrizione di "+risultati.stats.circoscrizionale[circoscrizione].denominazione,"livello_dettaglio":1,"comune":0,"circoscrizione":circoscrizione});
            }
            //console.log("eventHandlers.defaultHandlers.RefreshRisultatiData - livelli_dettaglio",AA_SierWebAppParams.livelli_dettaglio_data);
            //--------------------------------------------------------------

            //Aggiorna i dati dell'affluenza
            <?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppUpdateAffluenzaData();

            //console.log("eventHandlers.defaultHandlers.RefreshRisultatiData", risultati);
            
            if(updateView)
            {
                //Rinfresca la visualizzazione della sezione corrente
                if($$(AA_SierWebAppParams.sezione_corrente) && !$$(AA_SierWebAppParams.sezione_corrente).isVisible()) $$(AA_SierWebAppParams.sezione_corrente).show();
                else
                {
                    <?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppRefreshUi(AA_SierWebAppParams.sezione_corrente,AA_SierWebAppParams.sezione_corrente);
                }
            }
        });
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.RefreshRisultatiData", msg);
    }
};
//------------------------------------------------------------------------------------------------------------------------
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

