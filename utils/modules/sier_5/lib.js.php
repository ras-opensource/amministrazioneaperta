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
        }
    },
    risultati:{
        livello_dettaglio:0,
        livello_dettaglio_label:"Tutta la Regione Sardegna",
        data:null,
        view_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_PresidentiBox"?>",
        container_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_PresidentiContent"?>",
        realtime_container_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_PresidentiRealtimeContent"?>",
        footer_id:"<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_UI_WND_REPORT_RISULTATI."_".AA_SierModule::AA_UI_LAYOUT_REPORT_RISULTATI."_Presidenti_Footer"?>"
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
                <?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].SierWebAppRefreshUi(null,AA_SierWebAppParams.sezione_corrente);
                //----------------------------------

                //-------- refresh data  -----------
                let url=arguments[0]['url'];
                <?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].RefreshRisultatiData(url,true);
                if(AA_SierWebAppParams.timeoutRisultati)
                {
                    clearTimeout(AA_SierWebAppParams.timeoutRisultati);
                }
                AA_SierWebAppParams.timeoutRisultati=setTimeout(<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].RefreshRisultatiData,10000,url,true);
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
                            template: "<div style='display:flex;align-items:center; justify-content:space-between; height:100%; width:100%; flex-direction: column;'><div style='font-size:larger; font-weight:bold; border-bottom:1px solid #b6bcbf;width:70%;text-align: center'>Regione Sardegna</div><div style='display:flex;align-items:center; justify-content:space-between;height:100px; width:100%'><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; font-weight: 600; width:33%; color: #0c467f; border-right: 1px solid #dadee0'><span>ELETTORI</span><hr style='width:96%;color: #eef9ff'><span>#elettori#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center;font-weight: 600; width:33%; color: #0c467f'><span>VOTANTI</span><hr style='width:100%; color: #eef9ff'><span>#votanti#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; width:33%; font-weight:700; font-size: 24px; color: #0c467f'><span>#percent#%</span></div></div></div>",
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
        console.log("eventHandlers.defaultHandlers.RefreshRisultatiData - Aggiorno i dati del dettaglio Regione");
        if(risultati.stats.circoscrizionale)
        {
            //console.log("eventHandlers.defaultHandlers.RefreshRisultatiData",risultati.stats.circoscrizionale);
            let num=1;
            for(let idCircoscrizione in risultati.stats.circoscrizionale)
            {
                //console.log("eventHandlers.defaultHandlers.RefreshRisultatiData - circoscrizione",idCircoscrizione);
                let count=0;
                let percent=0;
                for(let giornata in risultati.stats.circoscrizionale[idCircoscrizione].affluenza)
                {
                    if(AA_SierWebAppParams.affluenza.aggiornamento==null || risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].aggiornamento > AA_SierWebAppParams.affluenza.aggiornamento) AA_SierWebAppParams.affluenza.aggiornamento=risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].aggiornamento;
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
                affluenza_data.push({"number":num,"id":idCircoscrizione,"denominazione":risultati.stats.circoscrizionale[idCircoscrizione].denominazione,"count":count,"percent":percent,
                    "elettori":risultati.stats.circoscrizionale[idCircoscrizione].elettori_tot,
                });
                num++;
            }
            AA_SierWebAppParams.affluenza.regionale.data=affluenza_data;
        }
        //-------------------------------------------------------------------
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.StartRisultatiApp", msg);
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

