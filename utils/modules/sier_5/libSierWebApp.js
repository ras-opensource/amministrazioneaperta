function AA_GenericSierWebApp()
{
    this.refreshMainUi= async function()
    {
        if(AA_SierWebAppParams.embedded==true) 
        {
            //console.log("AA_SierWebApp.refreshMainUi",AA_SierWebAppParams.taskManager);
            let result = await AA_VerboseTask("GetSierWebApp", AA_SierWebAppParams.taskManager);
            if (result.status.value == 0) 
            {
                //---------  Show App  --------------
                let wnd = webix.ui(result.content.value);
                wnd.show();

                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            AA_SierWebApp.ui.overlay.show();

            if (typeof cookieconsent === 'object') {
                console.log("AA_DefaultSystemInitialization - abilito la gestione dei cookie.");
                cookieconsent.run({ "notice_banner_type": "interstitial", "consent_type": "express", "palette": "light", "language": "it", "page_load_consent_levels": ["strictly-necessary"], "notice_banner_reject_button_hide": false, "preferences_center_close_button_hide": false, "page_refresh_confirmation_buttons": false, "website_name": AA_SierWebAppParams.web_url, "website_privacy_policy_url": AA_SierWebAppParams.privacy_policy_url });
            }

            this.ui.setup();

            //pull to refresh
            if (AA_SierWebAppParams.enablePullToRefresh) {
                console.log("AA_DefaultSystemInitialization - abilito il pull to refresh sull'elemento: "+AA_SierWebAppParams.ui_prefix+"_HeaderPull");
                const ptr = PullToRefresh.init({
                    mainElement: 'body',
                    triggerElement: "#"+AA_SierWebAppParams.ui_prefix+"_HeaderPull",
                    instructionsReleaseToRefresh: "Rilascia per aggiornare...",
                    instructionsPullToRefresh: "Trascina per aggiornare...",
                    instructionsRefreshing: "Aggiornamento...",
                    onRefresh() {
                        window.location.reload();
                    }
                });
            }

            AA_SierWebApp.ui.overlay.hide();

            return true;
        }
    };

    //Inizializza l'app dei risultati
    this.StartApp = async function() 
    {
        try 
        {
            //console.log("AA_SierWebApp.StartApp",arguments);
            //build UI if not present
            if(!$$(AA_SierWebAppParams.mainUi_id))
            {
                let result = await this.refreshMainUi();
                if (result) 
                {
                    //inizializzazione
                    AA_SierWebAppParams.data=null;
                    AA_SierWebAppParams.sezione_corrente=AA_SierWebAppParams.affluenza.regionale.view_id;
                    AA_SierWebAppParams.affluenza.regionale.data=null;
                    AA_SierWebAppParams.affluenza.regionale.aggiornamento=null;
                    AA_SierWebAppParams.affluenza.circoscrizionale.data=null;
                    AA_SierWebAppParams.affluenza.circoscrizionale.aggiornamento=null;
                    AA_SierWebAppParams.risultati.id_comune=0;
                    AA_SierWebAppParams.risultati.id_circoscrizione=0;
                    AA_SierWebAppParams.risultati.livello_dettaglio_label="tutta la Regione Sardegna";
                    AA_SierWebAppParams.risultati.data=null;
                    AA_SierWebAppParams.risultati.liste.id_coalizione=0;
                    AA_SierWebAppParams.risultati.liste.data=null;
                    AA_SierWebAppParams.risultati.candidati.id_lista=0;
                    AA_SierWebAppParams.risultati.candidati.data=null;
                    AA_SierWebApp.RefreshUi(null,AA_SierWebAppParams.sezione_corrente);
                    //----------------------------------

                    //-------- refresh data  -----------
                    AA_SierWebApp.RefreshRisultatiData(AA_SierWebAppParams.data_url,true,true);
                    //-----------------------------------
                }
                else
                {
                    console.error("AA_SierWebApp.StartRisultatiApp",result.error.value);
                }
            }
        } catch (msg) {
            console.error("AA_SierWebApp.StartRisultatiApp", msg);
        }
    };

    this.ui=
    {
        setup: function()
        {
            console.log("AA_SierWebApp::ui::setup()");
        
            if (webix.CustomScroll && !webix.env.touch) webix.CustomScroll.init();
        
            //Verifica se si sta visualizzando da un cellulare
            /*if (webix.env.mobile) {
                //Cambia il tema css
                if ($("#webix_style")) {
                    let old_style = $("#webix_style").attr("href");
                    let new_style = old_style.replace("webix.min.css", "skins/mini.min.css");
                    $("#webix_style").attr("href", new_style);
                }
            }*/

            //Imposta la posìzione dei messaggi
            webix.message.position = "bottom";

            //<div style='font-weight:700;font-size:larger;text-align:center'><span style='color:#fff'>A</span>mministrazione <span style='color:#fff'>A</span>perta</div>
            //<div style='font-weight:600;font-size:smaller;font-variant:default; text-align:center'>Regione Autonoma della Sardegna</div>
            app_box = 
            {
                id: AA_SierWebAppParams.ui_prefix,
                type:"clean",
                rows:
                [
                    {
                        id: AA_SierWebAppParams.ui_prefix+"_Header",
                        view:"template",
                        type:"clean",
                        borderless:true,
                        template:"<div id='"+AA_SierWebAppParams.ui_prefix+"_HeaderPull' style='width:100%;height:100%;display:flex;justify-content:space-between;align-items:center;background-color:#17324d'><div style='height:100%; width:60px; display:flex; align-items:center;justify-content:center'><img src='immagini/gonfalone_bianco.png' title='Regione Autonoma della Sardegna' alt='Regione Autonoma della Sardegna' height='50%'/></div><div style='height:100%; display:flex; align_items:center; justify-content:space-evenly;flex-direction:column; color:rgb(215, 238, 255);font-weight: 700; font-size:larger'><div style='text-align:center'><span style='color:#fff; '>ELEZIONI REGIONALI</span><br><span style='font-size:larger'><i>2019</i></span></div></div><div style='height:100%; width:60px; display:flex; align-items:center;justify-content:center'><img src='immagini/aa_logo.png' height='50%' title='Amministrazione Aperta' alt='Amministrazione Aperta'/></div></div>",
                        height: 60
                    },
                    {
                        view:"tabbar",
                        borderless:true,
                        value:AA_SierWebAppParams.affluenza.regionale.view_id,
                        css: "AA_Header_TabBar",
                        multiview:true,
                        view_id:AA_SierWebAppParams.ui_prefix+"_Multiview",
                        options:
                        [
                            {id:AA_SierWebAppParams.affluenza.regionale.view_id,value:"Affluenza", css:"AA_SierWebAppTab"},
                            {id:AA_SierWebAppParams.risultati.view_id,value:"Risultati", css:"AA_SierWebAppTab"}
                        ]
                    },
                    {
                        id: AA_SierWebAppParams.ui_prefix+"_Multiview",
                        view:"multiview",
                        type: "clean",
                        on:
                        {
                            "onViewChange":AA_SierWebApp.RefreshUi
                        },
                        cells:
                        [
                            {
                                id:AA_SierWebAppParams.affluenza.regionale.view_id,
                                type:"clean",
                                rows:
                                [
                                    {
                                        id:AA_SierWebAppParams.ui_prefix+"_Affluenza",
                                        template:"<div id='"+AA_SierWebAppParams.affluenza.regionale.container_id+"'style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; border-radius:15px; background-color:#ebedf0'></div>",
                                        css:{"background-color":"#f4f5f9"}
                                    },
                                    {
                                        id:AA_SierWebAppParams.affluenza.regionale.footer_id,
                                        template:"<div style='display: flex; justify-content: center; align-items: center; width: min-content; height: 100%; overflow: visible; white-space:nowrap;' class='scrollTextFromLeft'><span>#footer#</span></div>",
                                        height:24,
                                        css:{"background-color":"#08324f","color":"#fff","text-tramsform":"uppercase"},
                                        data:{"footer":"&nbsp;"}
                                    },
                                ]
                            },
                            {
                                id:AA_SierWebAppParams.affluenza.circoscrizionale.view_id,
                                type:"clean",
                                rows:
                                [
                                    {
                                        id:AA_SierWebAppParams.ui_prefix+"_AffluenzaCircoscrizionale",
                                        template:"<div id='"+AA_SierWebAppParams.affluenza.circoscrizionale.container_id+"'style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; border-radius:15px; background-color:#ebedf0'></div>",
                                        css:{"background-color":"#f4f5f9"}
                                    },
                                    {
                                        id:AA_SierWebAppParams.affluenza.circoscrizionale.footer_id,
                                        template:"<div style='display: flex; justify-content: center; align-items: center; width: min-content; height: 100%; overflow: visible; white-space:nowrap;' class='scrollTextFromLeft'><span>#footer#</span></div>",
                                        height:24,
                                        css:{"background-color":"#08324f","color":"#fff","text-tramsform":"uppercase"},
                                        data:{"footer":"&nbsp;"}
                                    },
                                ]
                            },
                            {
                                id:AA_SierWebAppParams.risultati.view_id,
                                type:"clean",
                                rows:
                                [
                                    {
                                        id:AA_SierWebAppParams.ui_prefix+"_Presidenti",
                                        template:"<div id='"+AA_SierWebAppParams.risultati.container_id+"'style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; border-radius:15px; background-color:#ebedf0'></div>",
                                        css:{"background-color":"#f4f5f9"}
                                    },
                                    {
                                        id:AA_SierWebAppParams.risultati.footer_id,
                                        template:"<div style='display: flex; justify-content: center; align-items: center; width: min-content; height: 100%; overflow: visible; white-space:nowrap;' class='scrollTextFromLeft'><span>#footer#</span></div>",
                                        height:24,
                                        css:{"background-color":"#08324f","color":"#fff","text-tramsform":"uppercase"},
                                        data:{"footer":"&nbsp;"}
                                    },
                                ]
                            },
                            {
                                id:AA_SierWebAppParams.risultati.liste.view_id,
                                type:"clean",
                                rows:
                                [
                                    {
                                        id:AA_SierWebAppParams.ui_prefix+"_Liste",
                                        template:"<div id='"+AA_SierWebAppParams.risultati.liste.container_id+"'style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; border-radius:15px; background-color:#ebedf0'></div>",
                                        css:{"background-color":"#f4f5f9"}
                                    },
                                    {
                                        id:AA_SierWebAppParams.risultati.liste.footer_id,
                                        template:"<div style='display: flex; justify-content: center; align-items: center; width: min-content; height: 100%; overflow: visible; white-space:nowrap;' class='scrollTextFromLeft'><span>#footer#</span></div>",
                                        height:24,
                                        css:{"background-color":"#08324f","color":"#fff","text-tramsform":"uppercase"},
                                        data:{"footer":"&nbsp;"}
                                    },
                                ]
                            },
                            {
                                id:AA_SierWebAppParams.risultati.candidati.view_id,
                                type:"clean",
                                rows:
                                [
                                    {
                                        id:AA_SierWebAppParams.ui_prefix+"_Candidati",
                                        template:"<div id='"+AA_SierWebAppParams.risultati.candidati.container_id+"'style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; border-radius:15px; background-color:#ebedf0'></div>",
                                        css:{"background-color":"#f4f5f9"}
                                    },
                                    {
                                        id:AA_SierWebAppParams.risultati.candidati.footer_id,
                                        template:"<div style='display: flex; justify-content: center; align-items: center; width: min-content; height: 100%; overflow: visible; white-space:nowrap;' class='scrollTextFromLeft'><span>#footer#</span></div>",
                                        height:24,
                                        css:{"background-color":"#08324f","color":"#fff","text-tramsform":"uppercase"},
                                        data:{"footer":"&nbsp;"}
                                    },
                                ]
                            },
                            {
                                id:AA_SierWebAppParams.livello_dettaglio_view_id,
                                type:"clean",
                                rows:
                                [
                                    {
                                        id:AA_SierWebAppParams.ui_prefix+"__DettaglioTreeBoxContent",
                                        template:"<div id='AA_SierWebAppSideMenuBox'style='display: flex; justify-content: center; align-items: center;width: 100%; height: 100%; border-radius:15px; background-color:#ebedf0'>",
                                        css:{"background-color":"#f4f5f9"}
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }

            webix.ui(app_box);
        },
        overlay: 
        {
            isVisible: function() {
                try {
                    let overlay = document.getElementById("AA_MainOverlay");
                    if (overlay) {
                        if (overlay.style.display == "block") {
                            return true;
                        }
                    }

                    return false;
                } catch (msg) {
                    console.error(msg);
                    return false;
                }
            },
            show: function() {
                try {
                    let overlay = document.getElementById("AA_MainOverlay");
                    if (overlay) {
                        if (overlay.style.display == "none") {
                            console.log("AA_MainApp.ui.overlay.show - mostro l'overlay");
                            overlay.classList.remove('AA_MainOverlayFadeOff');
                            overlay.classList.add('AA_MainOverlayFadeIn');
                            overlay.style.display = "block";
                        }
                    }
                } catch (msg) {
                    console.error("AA_SierWebApp.ui.overlay.show", msg);
                }
            },
            hide: function(delay = 1000) {
                try {
                    let overlay = document.getElementById("AA_MainOverlay");
                    //console.log("AA_MainApp.ui.overlay.hide", overlay);
                    if (overlay) {
                        if (overlay.style.display == "flex") {
                            console.log("AA_SierWebApp.ui.overlay.hide");

                            if (delay > 0) {
                                setTimeout(function() {
                                    overlay.classList.remove('AA_MainOverlayFadeIn');
                                    overlay.classList.add('AA_MainOverlayFadeOff');
                                }, delay)
                            } else {
                                overlay.classList.remove('AA_MainOverlayFadeIn');
                                overlay.classList.add('AA_MainOverlayFadeOff');
                            }

                            setTimeout(function() {
                                overlay.style.display = "none";
                            }, 2100 + delay);
                        }
                    }
                } catch (msg) {
                    console.error("AA_SierWebApp.ui.overlay.hide", msg);
                }
            }
        }
    }
};

var AA_SierWebApp = new AA_GenericSierWebApp();

//Rinfresca l'interfaccia web app
AA_SierWebApp.RefreshUi = async function() {
    try 
    {
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

        //-------------------------------- Sidemenu ----------------------------------------
        if(arguments[1]==AA_SierWebAppParams.livello_dettaglio_view_id)
        {
            if($$("AA_SierWebAppSideMenuBoxContent"))
            {
                $$("AA_SierWebAppSideMenuBoxContent").destructor();
            }
           
            console.log("AA_SierWebApp.RefreshUi - costruisco il tree view del livello di dettaglio");
            let SierWebAppSideMenu=
            {
                id: "AA_SierWebAppSideMenuBoxContent",
                container: "AA_SierWebAppSideMenuBox",
                view: "layout",
                type: "space",
                css: {"border-radius": "15px","border-width":"1px 1px 1px !important"},
                rows:
                [
                    {
                        id:"AA_SierWebAppSideMenuBoxContentLabel",
                        view:"template",
                        template: "<div style='display:flex;align-items:center; justify-content:center; height:100%; width:100%; flex-direction: column;'><div style='display:flex; justify-content: space-between; align-items:center;width:100%;height:100%'><a href='#' onClick='$$(AA_SierWebAppParams.livello_dettaglio_prev_view_id).show();' style='font-weight: 700;font-size: larger;color: #0c467f;' title='Indietro'><span class='mdi mdi-keyboard-backspace'></span></a><div style='text-align:center;'><span>livello di dettaglio attuale:</span><br><span style='font-weight:bold; color: #0c467f;'>#livello_dettaglio_label#</span></div><span>&nbsp;</span></div></div></div>",
                        css:{"background-color":"#ebedf0","border-radius": "15px"},
                        data:{livello_dettaglio_label:AA_SierWebAppParams.risultati.livello_dettaglio_label},
                        height: 42,
                        borderless:true,
                    },
                    {
                        view:"template",
                        borderless: true,
                        template:"<div style='font-size: smaller'>Selezione una voce dalla lista per cambiare il livello di dettaglio.</div>",
                        autoheight:true,
                        css:{"background-color":"transparent"}
                    },
                    {
                        id:"AA_SierWebAppDettaglioFilterSearch",
                        view: "search",
                        value: "",
                        label:"",
                        clear: true,
                        on:
                        {
                            "onTimedKeyPress":
                            function() 
                            { 
                                //console.log("AA_SierWebApp.RefreshUi - click");
                                if ($$('AA_SierWebAppSideMenuTree')) 
                                {
                                    $$('AA_SierWebAppSideMenuTree').filter(function(obj) 
                                    {
                                        if($$('AA_SierWebAppDettaglioFilterSearch') && obj.value !="") 
                                        {
                                            return obj.value.toLowerCase().indexOf($$('AA_SierWebAppDettaglioFilterSearch').getValue().toLowerCase()) !== -1; 
                                        }
                                        else return true;
                                    });
                                }
                            },
                            "onChange":
                            function() 
                            { 
                                //console.log("AA_SierWebApp.RefreshUi - click");
                                if ($$('AA_SierWebAppSideMenuTree')) 
                                {
                                    $$('AA_SierWebAppSideMenuTree').filter(function(obj) 
                                    {
                                        if($$('AA_SierWebAppDettaglioFilterSearch') && obj.value !="") 
                                        {
                                            return obj.value.toLowerCase().indexOf($$('AA_SierWebAppDettaglioFilterSearch').getValue().toLowerCase()) !== -1; 
                                        }
                                        else return true;
                                    });
                                }
                            }
                        }
                    },
                    {
                        view: "tree",
                        id: "AA_SierWebAppSideMenuTree",
                        borderless: false,
                        scroll: true,
                        "template" : "{common.icon()}&nbsp;{common.folder()}&nbsp;<span style='cursor:pointer;'>#value#</span>",
                        data: JSON.parse(JSON.stringify(AA_SierWebAppParams.livello_dettaglio_data_tree)),
                        select: true,
                        type: {
                            height: 40
                        },
                        on: {
                            onAfterSelect: async function(id) {
                                try {
                                    let sidemenu = $$("AA_SierWebAppSideMenuTree");

                                    item = sidemenu.getItem(id);
                                    if(!item)
                                    {
                                        console.log("AA_SierWebAppSideMenuTree - item non valido.");
                                        return false;
                                    }
                                    //console.log("AA_MainApp.sidebar.onAfterSelect("+id+")",item);
                                    
                                    AA_SierWebAppParams.risultati.id_circoscrizione=0;
                                    AA_SierWebAppParams.risultati.id_comune=0;
                                    AA_SierWebAppParams.risultati.livello_dettaglio_label=item.value;
                    
                                    if(Number(item.circoscrizione) > 0) 
                                    {
                                        //console.log("AA_MainApp.sidebar.onAfterSelect("+id+") - imposto la circoscrizione: "+item.value);
                                        AA_SierWebAppParams.risultati.id_circoscrizione=Number(item.circoscrizione);
                                        AA_SierWebAppParams.risultati.id_comune=0;
                                    }

                                    if(Number(item.comune) > 0) 
                                    {
                                        //console.log("AA_MainApp.sidebar.onAfterSelect("+id+") - imposto il comune: "+item.value);
                                        AA_SierWebAppParams.risultati.id_comune=Number(item.comune);
                                    }

                                    if(AA_SierWebAppParams.livello_dettaglio_prev_view_id == AA_SierWebAppParams.risultati.candidati.view_id && AA_SierWebAppParams.risultati.id_comune==0)
                                    {
                                        AA_SierWebAppParams.livello_dettaglio_prev_view_id=AA_SierWebAppParams.risultati.liste.view_id;
                                    }

                                    if(AA_SierWebAppParams.livello_dettaglio_prev_view_id && $$(AA_SierWebAppParams.livello_dettaglio_prev_view_id))
                                    {
                                        console.log("AA_MainApp.sidebar.onAfterSelect("+id+") - visualizzo la view precedente "+AA_SierWebAppParams.livello_dettaglio_prev_view_id);
                                        $$(AA_SierWebAppParams.livello_dettaglio_prev_view_id).show();
                                        AA_SierWebAppParams.livello_dettaglio_prev_view_id=null;
                                    }

                                    return true;
                                } catch (msg) {
                                    //console.error("AA_MainApp.ui.sidemenu.onAfterSelect(" + id + ")");
                                    AA_MainApp.ui.alert(msg);
                                    return Promise.reject(msg);
                                }
                            }
                        }
                    }
                ]
            }

            //console.log("AA_SierWebApp.RefreshUi - visualizzo il tree view",AA_SierWebAppParams.livello_dettaglio_data_tree);
            webix.ui(SierWebAppSideMenu).show();            
        }
        //----------------------------------------------------------------------------------

        //---------------------------- Affluenza regione-----------------------------------
        if(arguments[1]==AA_SierWebAppParams.affluenza.regionale.view_id)
        {
            AA_SierWebApp.UpdateAffluenzaData();
        
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
                console.log("AA_SierWebApp.RefreshUi - rimuovo il box affluenza: "+AA_SierWebAppParams.affluenza.regionale.realtime_container_id);
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

            console.log("AA_SierWebApp.RefreshUi - Aggiorno il box affluenza: "+AA_SierWebAppParams.affluenza.regionale.view_id);

            //Aggiorna l'affluenza
            let affluenza_cols=[
                {id:"denominazione",header:["<div style='text-align: left'>Circoscrizione</div>"],"fillspace":true, "sort":"text","css":{"text-align":"left"}},
                {id:"count",header:["<div style='text-align: right'>votanti</div>"],"width":90, "css":{"text-align":"right"}},
                {id:"percent",header:["<div style='text-align: right'>%</div>"],"width":60, "sort":"int","css":{"text-align":"right","font-weight":"700"}}
            ];
            //console.log("AA_SierWebApp.RefreshRisultatiData - affluenza_cols",affluenza_cols);
    
            if($$(AA_SierWebAppParams.affluenza.regionale.container_id))
            {
                console.log("AA_SierWebApp.RefreshUi - implemento il box affluenza: "+AA_SierWebAppParams.affluenza.regionale.realtime_container_id);
                let votanti_tot=0;
                let elettori_tot=0;
                for(let affluenza_data of AA_SierWebAppParams.affluenza.regionale.data)
                {
                    votanti_tot+=affluenza_data.count_plain;
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
                            template: "<div style='display:flex;align-items:center; justify-content:space-between; height:100%; width:100%; flex-direction: column;'><div style='font-size:larger; font-weight:bold; border-bottom:1px solid #b6bcbf;width:70%;text-align: center; color: #0c467f;height:22px;padding-top:8px'>Regione Sardegna</div><div style='display:flex;align-items:center; justify-content:space-between;height:100px; width:100%'><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; font-weight: 600; width:33%; color: #0c467f; border-right: 1px solid #dadee0'><span>ELETTORI</span><hr style='width:96%;color: #eef9ff'><span>#elettori#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center;font-weight: 600; width:33%; color: #0c467f'><span>VOTANTI</span><hr style='width:100%; color: #eef9ff'><span>#votanti#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; width:33%; font-weight:700; font-size: 24px; color: #0c467f'><span>#percent#%</span></div></div></div>",
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
                                    template:"<div style='font-weight:bold; width:100%;text-align: center'>Dettaglio per circoscrizione</div>",
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
                                    template:"<div style='font-size:smaller; width:100%;text-align: left'><i>Fai click sulla circoscrizione per visualizzare il dettaglio al livello comunale.</i></div>",
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
                    console.log("AA_SierWebApp.RefreshUi - visualizzo il box affluenza: "+AA_SierWebAppParams.affluenza.regionale.container_id);
                    affluenza_ui.show();
                }

                if($$(AA_SierWebAppParams.affluenza.regionale.footer_id))
                {
                    $$(AA_SierWebAppParams.affluenza.regionale.footer_id).parse({"footer":"Dati aggiornati al "+aggiornamento});
                }
            }
            else
            {
                console.error("AA_SierWebApp.RefreshUi - Errore nell'aggiornamento del box affluenza.");
            }
        }
        //-------------------------------------------------------------------------------

        //---------------------------- Affluenza circoscrizionale-----------------------------------
        if(arguments[1]==AA_SierWebAppParams.affluenza.circoscrizionale.view_id)
        {
            AA_SierWebApp.UpdateAffluenzaData();
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
                console.log("AA_SierWebApp.RefreshUi - rimuovo il box affluenza: "+AA_SierWebAppParams.affluenza.circoscrizionale.realtime_container_id);
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

            console.log("AA_SierWebApp.RefreshUi - Aggiorno il box affluenza: "+AA_SierWebAppParams.affluenza.circoscrizionale.view_id);

            //Aggiorna l'affluenza
            let affluenza_cols=[
                {id:"denominazione",header:["<div style='text-align: left'>Comune</div>"],"fillspace":true, "sort":"text","css":{"text-align":"left"}},
                {id:"count",header:["<div style='text-align: right'>votanti</div>"],"width":90, "css":{"text-align":"right"}},
                {id:"percent",header:["<div style='text-align: right'>%<sup>*</sup></div>"],"width":60, "sort":"int","css":{"text-align":"right","font-weight":"700"}}
            ];
            //console.log("AA_SierWebApp.RefreshRisultatiData - affluenza_cols",affluenza_cols);
    
            if($$(AA_SierWebAppParams.affluenza.circoscrizionale.container_id))
            {
                console.log("AA_SierWebApp.RefreshUi - implemento il box affluenza: "+AA_SierWebAppParams.affluenza.circoscrizionale.realtime_container_id);
                let votanti_tot=0;
                let elettori_tot=0;
                for(let affluenza_data of AA_SierWebAppParams.affluenza.circoscrizionale.data)
                {
                    votanti_tot+=affluenza_data.count_plain;
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
                    console.log("AA_SierWebApp.RefreshUi - visualizzo il box affluenza: "+AA_SierWebAppParams.affluenza.circoscrizionale.view_id);
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
                console.error("AA_SierWebApp.RefreshUi - Errore nell'aggiornamento del box affluenza.");
            }
        }
        //------------------------------------------------------------------------------------------

        //---------------------------------------- Risultati Presidente/coalizione ----------------------------------
        if(arguments[1]==AA_SierWebAppParams.risultati.view_id)
        {
            AA_SierWebApp.UpdateRisultatiData();
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
                console.log("AA_SierWebApp.RefreshUi - rimuovo il box risultati: "+AA_SierWebAppParams.risultati.realtime_container_id);
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

            console.log("AA_SierWebApp.RefreshUi - Aggiorno il box risultati: "+AA_SierWebAppParams.risultati.container_id);
            if($$(AA_SierWebAppParams.risultati.container_id))
            {
                console.log("AA_SierWebApp.RefreshUi - implemento il box risultati: "+AA_SierWebAppParams.risultati.realtime_container_id);
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
                            template: "<div style='display:flex;align-items:center; justify-content:space-between; height:100%; width:100%; flex-direction: column;'><div style='display:flex; justify-content: center; align-items:center;width:100%;height:100%'><span>&nbsp;</span><div style='text-align:center;'><span>Livello di dettaglio:</span><br><a href='#' onclick='AA_SierWebAppParams.livello_dettaglio_prev_view_id=AA_SierWebAppParams.risultati.view_id;$$(AA_SierWebAppParams.livello_dettaglio_view_id).show();' title='Fai click per cambiare il livello di dettaglio' style='text-decoration: none'><span style='font-weight:bold; color: #0c467f;'>"+AA_SierWebAppParams.risultati.livello_dettaglio_label+"</span></a></div>&nbsp;</div></div></div>",
                            css:{"background-color":"#ebedf0","border-radius": "15px","border-width":"1px 1px 1px !important"},
                            height: 42,
                        },
                        {height:10},
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
                                    options:[{value:"Voti presidente",id:"voti_presidente",css:"AA_SierWebAppTab"},{value:"Voti coalizione",id:"voti_coalizione",css:"AA_SierWebAppTab"}]
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
                                            template:"<div style='display: flex;justify-content: center; align-items: center; width: 100%; height:100%;cursor:"+cursor+"'><div style='display: flex; justify-content: space-between; align-items: center; width: 100%; height:96%; border: 1px solid #5ccce7;background: #fff; border-radius: 10px'><div style='width:35px;display:flex;align-items:center;justify-content:center'><img src='#image#' style='border-radius:50%; width:30px'></img></div><div style='width: 55%;text-align:left;font-weight: 500;color: #0c467f;'>&nbsp;#presidente#</div><div style='width:15%;text-align:right;font-size: smaller'>#voti#</div><div style='width: 60px;text-align:right;font-size:larger;font-weight:bold;color: #0c467f;'>#percent#%&nbsp;</div></div></div>",
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
                                            template:"<div style='display: flex;justify-content: center; align-items: center; width: 100%; height:100%;cursor:"+cursor+"'><div style='display: flex; justify-content: space-between; align-items: center; width: 100%; height:96%; border: 1px solid #5ccce7;background: #fff; border-radius: 10px'><div style='width:35px;display:flex;align-items:center;justify-content:center'><img src='#image#' style='border-radius:50%; width:30px'></img></div><div style='width:55%; text-align:left;font-weight: 500;color: #0c467f;'>&nbsp;#presidente#</div><div style='width:15%;text-align:right;font-size: smaller'>#voti#</div><div style='width: 60px;text-align:right;font-size:larger;font-weight:bold;color: #0c467f;'>#percent#%&nbsp;</div></div></div>",
                                            data: AA_SierWebAppParams.risultati.data_coalizioni                                    
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            view:"template",
                            borderless: true,
                            css:{"background-color":"transparent"},
                            template:"<div style='font-size:smaller'><i>Fai click sul nome del candidato presidente per visualizzare il dettaglio sui voti di lista della coalizione.</i></div>",
                            autoheight:true
                        }
                    ]
                };
                
                let risultati_ui=webix.ui(risultati_box);
                if(risultati_box) 
                {
                    console.log("AA_SierWebApp.RefreshUi - visualizzo il box risultati: "+AA_SierWebAppParams.risultati.view_id);
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
            AA_SierWebApp.UpdateRisultatiData();
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
                console.log("AA_SierWebApp.RefreshUi - rimuovo il box risultati: "+AA_SierWebAppParams.risultati.liste.realtime_container_id);
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

            console.log("AA_SierWebApp.RefreshUi - Aggiorno il box risultati: "+AA_SierWebAppParams.risultati.liste.container_id);
            if($$(AA_SierWebAppParams.risultati.liste.container_id))
            {
                console.log("AA_SierWebApp.RefreshUi - implemento il box risultati: "+AA_SierWebAppParams.risultati.liste.realtime_container_id);
                let bottomText="Per visualizzare il dettaglio dei voti candidati consigliere cambia il livello di dettaglio su una circoscrizione o un comune.";
                if(AA_SierWebAppParams.risultati.id_circoscrizione > 0)
                {
                   bottomText="Fai click sulla lista per visualizzare il dettaglio dei voti ai canditati consigliere.";
                }
                let risultati=AA_SierWebAppParams.data;
                let dettaglio=risultati.stats.regionale.risultati;
                if(AA_SierWebAppParams.risultati.id_circoscrizione>0 ) dettaglio=risultati.stats.circoscrizionale[AA_SierWebAppParams.risultati.id_circoscrizione].risultati;
                if(AA_SierWebAppParams.risultati.id_comune>0 ) dettaglio=risultati.comuni[AA_SierWebAppParams.risultati.id_comune].risultati;
                let voti_percent_coalizione=0;
                let voti_percent_presidente=0;
                if(dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].percent_coalizione > 0) voti_percent_coalizione=new Intl.NumberFormat('it-IT').format(Number(dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].percent_coalizione).toFixed(1));
                if(dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].percent > 0) voti_percent_presidente=new Intl.NumberFormat('it-IT').format(Number(dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].percent).toFixed(1));
                let cursor="zoom-in";
                if(AA_SierWebAppParams.risultati.id_circoscrizione == 0)
                {
                    cursor="default";
                }
                let onItemClick=function(){if(AA_SierWebAppParams.risultati.id_circoscrizione == 0) {webix.message(bottomText); return;}; AA_SierWebAppParams.risultati.candidati.id_lista=arguments[0];$$(AA_SierWebAppParams.risultati.candidati.view_id).show()};
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
                            template: "<div style='display:flex;align-items:center; justify-content:center; height:100%; width:100%; flex-direction: column;'><div style='display:flex; justify-content: space-between; align-items:center;width:100%;height:100%;'><a href='#' onClick='AA_SierWebAppParams.risultati.liste.id_coalizione=0;$$(AA_SierWebAppParams.risultati.view_id).show();' style='font-weight: 700;font-size: larger;color: #0c467f;' title='Indietro'><span class='mdi mdi-keyboard-backspace'></span></a><div style='text-align:center;'><span>Livello di dettaglio:</span><br><a href='#' onclick='AA_SierWebAppParams.livello_dettaglio_prev_view_id=AA_SierWebAppParams.risultati.liste.view_id;$$(AA_SierWebAppParams.livello_dettaglio_view_id).show();' title='Fai click per cambiare il livello di dettaglio' style='text-decoration: none'><span style='font-weight:bold; color: #0c467f;'>"+AA_SierWebAppParams.risultati.livello_dettaglio_label+"</span></a></div><div style='display:flex;justify-content:center;align-items:center'><span class='mdi mdi-home' style='font-size:larger;cursor:pointer;color: #0c467f;' onClick='$$(AA_SierWebAppParams.risultati.view_id).show()'></span></div></div></div>",
                            css:{"background-color":"#ebedf0","border-radius": "15px","border-width":"1px 1px 1px !important"},
                            height: 42,
                        },
                        {height:10},
                        {
                            view:"template",
                            template: "<div style='display:flex;align-items:center; justify-content:space-between; height:100%; width:100%; flex-direction: column;'><div style='display:flex; justify-content: center; align-items:center;width:100%'><div style='width:60px;height:60px;display:flex;align-items:center;justify-content:center;border-radius:50%; overflow:hidden'><img src='#image#' style='width:40px'></img></div><div style='text-align:center;'><span style='font-size:larger; font-weight:bold; color: #0c467f;'>"+dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].denominazione+"</span></div><div style='width:45px;display:flex;align-items:center;justify-content:center'>&nbsp;</div></div><div style='display:flex;align-items:center; justify-content:space-between;height:40px; width:100%;border-bottom: 1px solid #dadee0;border-top:1px solid #b6bcbf;background-color:#fbfbfa'><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; font-weight: 600; width:40%; color: #0c467f;'><span>&nbsp;VOTI PRESIDENTE</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center;font-weight: 600; width:38%; color: #0c467f'><span>#voti_presidente#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; width:25%; font-weight:700; font-size: 24px; color: #0c467f'><span>#percent_presidente#%</span></div></div><div style='display:flex; justify-content: space-between; align-items:center; width:100%'><div style='display:flex;align-items:center; justify-content:space-between;height:40px; width:100%;background-color:#fbfbfa;border-bottom: 1px solid #dadee0;'><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; font-weight: 600; width:40%; color: #0c467f;'><span>&nbsp;VOTI COALIZIONE</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center;font-weight: 600; width:38%; color: #0c467f'><span>#voti_coalizione#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; width:25%; font-weight:700; font-size: 24px; color: #0c467f'><span>#percent_coalizione#%</span></div></div></div>",
                            data:{voti_presidente : fmtNumber.format(Number(dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].voti)), percent_presidente: voti_percent_presidente,voti_coalizione : fmtNumber.format(Number(dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].voti_coalizione)), percent_coalizione: voti_percent_coalizione,image:"https://amministrazioneaperta.regione.sardegna.it"+dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].image},
                            height: 150,
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
                                    id: "DettaglioListeCoalizione",
                                    view:"dataview",
                                    scrollX:false,
                                    xCount:1,
                                    select:false,
                                    borderless:true,
                                    css:{"background":"transparent","cursor": "default"},
                                    on:{"onItemClick":onItemClick},
                                    type: {
                                        height: 50,
                                        width:"auto",
                                        css:"AA_SierWebAppDataviewItem"
                                    },
                                    template:"<div style='display: flex;justify-content: center; align-items: center; width: 100%; height:100%;cursor:"+cursor+"'><div style='display: flex; justify-content: space-between; align-items: center; width: 100%; height:96%; border: 1px solid #5ccce7;background: #fff; border-radius: 10px'><div style='width:40px;display:flex;align-items:center;justify-content:center'><img src='#image#' style='border-radius:50%; width:30px'></img></div><div style='width: 55%;text-align:left;font-weight: 500;color: #0c467f;#font_size#'>#denominazione#</div><div style='width:15%;text-align:right;font-size: smaller'>#voti#</div><div style='width: 60px;text-align:right;font-size:larger;font-weight:bold;color: #0c467f;'>#percent#%&nbsp;</div></div></div>",
                                    data: AA_SierWebAppParams.risultati.liste.data
                                }
                            ]
                        },
                        {
                            view:"template",
                            borderless: true,
                            css:{"background-color":"transparent"},
                            template:"<div style='font-size:smaller'><i>"+bottomText+"</i></div>",
                            autoheight:true
                        }
                    ]
                };

                let risultati_ui=webix.ui(risultati_box);
                if(risultati_box) 
                {
                    console.log("AA_SierWebApp.RefreshUi - visualizzo il box risultati: "+AA_SierWebAppParams.risultati.liste.view_id);
                    risultati_ui.show();
                    if(AA_SierWebAppParams.risultati.id_circoscrizione > 0)
                    {
                        console.log("AA_SierWebApp.RefreshUi - verifico l'handler per il dettaglio candidati - "+AA_SierWebAppParams.risultati.liste.view_id);
                        if(!$$("DettaglioListeCoalizione").hasEvent("onItemClick"))
                        {
                            console.log("AA_SierWebApp.RefreshUi - aggiungo l'handler per il dettaglio candidati - "+AA_SierWebAppParams.risultati.liste.view_id);
                            $$("DettaglioListeCoalizione").attachEvent("onItemClick",function(){AA_SierWebAppParams.risultati.candidati.id_lista=arguments[0];$$(AA_SierWebAppParams.risultati.candidati.view_id).show()});
                        }
                    }
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
            AA_SierWebApp.UpdateRisultatiData();
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
                console.log("AA_SierWebApp.RefreshUi - rimuovo il box risultati: "+AA_SierWebAppParams.risultati.candidati.realtime_container_id);
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

            console.log("AA_SierWebApp.RefreshUi - Aggiorno il box risultati: "+AA_SierWebAppParams.risultati.candidati.container_id);
            if($$(AA_SierWebAppParams.risultati.candidati.container_id))
            {
                console.log("AA_SierWebApp.RefreshUi - implemento il box risultati: "+AA_SierWebAppParams.risultati.candidati.realtime_container_id);
                let risultati=AA_SierWebAppParams.data;
                let dettaglio=risultati.stats.regionale.risultati;
                if(AA_SierWebAppParams.risultati.id_circoscrizione>0 ) dettaglio=risultati.stats.circoscrizionale[AA_SierWebAppParams.risultati.id_circoscrizione].risultati;
                if(AA_SierWebAppParams.risultati.id_comune>0 ) dettaglio=risultati.comuni[AA_SierWebAppParams.risultati.id_comune].risultati;
                let voti_percent=0;
                if(dettaglio.voti_lista[AA_SierWebAppParams.risultati.candidati.id_lista].percent > 0) voti_percent=new Intl.NumberFormat('it-IT').format(Number(dettaglio.voti_lista[AA_SierWebAppParams.risultati.candidati.id_lista].percent).toFixed(1));
                let cursor="default";
                let risultati_box={
                    id: AA_SierWebAppParams.risultati.candidati.realtime_container_id,
                    view: "layout",
                    css:{"background-color":"#f4f5f9"},
                    container: AA_SierWebAppParams.risultati.candidati.container_id,
                    type:"clean",
                    rows:
                    [
                        {
                            view:"template",
                            template: "<div style='display:flex;align-items:center; justify-content:center; height:100%; width:100%; flex-direction: column;'><div style='display:flex; justify-content: space-between; align-items:center;width:100%;height:100%'><a href='#' onClick='AA_SierWebAppParams.risultati.candidati.id_lista=0;$$(AA_SierWebAppParams.risultati.liste.view_id).show();' style='font-weight: 700;font-size: larger;color: #0c467f;' title='Indietro'><span class='mdi mdi-keyboard-backspace'></span></a><div style='text-align:center;'><span>Livello di dettaglio:</span><br><a href='#' onclick='AA_SierWebAppParams.livello_dettaglio_prev_view_id=AA_SierWebAppParams.risultati.candidati.view_id;$$(AA_SierWebAppParams.livello_dettaglio_view_id).show();' title='Fai click per cambiare il livello di dettaglio' style='text-decoration: none'><span style='font-weight:bold; color: #0c467f;'>"+AA_SierWebAppParams.risultati.livello_dettaglio_label+"</span></a></div><div style='display:flex;justify-content:center;align-items:center'><span class='mdi mdi-home' style='font-size:larger;cursor:pointer;color: #0c467f;' onClick='$$(AA_SierWebAppParams.risultati.view_id).show()'></span></div></div></div>",
                            css:{"background-color":"#ebedf0","border-radius": "15px","border-width":"1px 1px 1px !important"},
                            height: 42,
                        },
                        {height:10},
                        {
                            view:"template",
                            template: "<div style='display:flex;align-items:center; justify-content:space-between; height:100%; width:100%; flex-direction: column;'><div style='display:flex; justify-content: center; align-items:center;width:100%'><div style='width:60px;height:60px;display:flex;align-items:center;justify-content:center;border-radius:50%; overflow:hidden'><img src='#image#' style='width:40px'></img></div><div style='text-align:center;'><span style='font-size:larger; font-weight:bold; color: #0c467f;'>"+dettaglio.voti_lista[AA_SierWebAppParams.risultati.candidati.id_lista].denominazione+"</span></div><div style='width:45px;display:flex;align-items:center;justify-content:center'>&nbsp;</div></div><div style='display:flex;align-items:center; justify-content:space-between;height:40px; width:100%;border-bottom: 1px solid #dadee0;border-top:1px solid #b6bcbf;background-color:#fbfbfa'><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; font-weight: 600; width:40%; color: #0c467f;'><span>&nbsp;VOTI di LISTA</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center;font-weight: 600; width:38%; color: #0c467f'><span>#voti_lista#</span></div><div style='display:flex; flex-direction:column;justify-content:center;align-items:center; width:25%; font-weight:700; font-size: 24px; color: #0c467f'><span>#percent_lista#%</span></div></div></div>",
                            data:{voti_lista : fmtNumber.format(Number(dettaglio.voti_lista[AA_SierWebAppParams.risultati.candidati.id_lista].voti)), percent_lista: voti_percent,image:"https://amministrazioneaperta.regione.sardegna.it"+dettaglio.voti_lista[AA_SierWebAppParams.risultati.candidati.id_lista].image},
                            height: 100,
                            css: {"border-radius": "15px","border-width":"1px 1px 1px !important"}
                        },
                        {height: 10},
                        {
                            view:"layout",
                            type:"space",
                            css:{"border-radius":"15px","background-color":"#ebedf0","border":"1px solid #dee1e4 !important"},
                            rows:
                            [
                                {
                                    view:"label",
                                    label:"<span style='width:90%;text-align:center; border-bottom:1px solid gray'>Voti candidati consigliere</span>",
                                    align:"center"
                                },
                                {
                                    view:"dataview",
                                    scrollX:false,
                                    xCount:1,
                                    select:false,
                                    borderless:true,
                                    css:{"background-color":"#ebedf0","cursor": "default"},
                                    type: {
                                        height: 50,
                                        width:"auto",
                                        css:"AA_SierWebAppCandidatiDataviewItem"
                                    },
                                    template:"<div style='display: flex;justify-content: center; align-items: center; width: 100%; height:100%;cursor:"+cursor+"'><div style='display: flex; justify-content: space-between; align-items: center; width: 100%; height:96%; border: 1px solid #5ccce7;background: #fff; border-radius: 10px'><div style='width:10px'>&nbsp;</div><div style='width: 60%;text-align:left;font-weight: 500;color: #0c467f;#font_size#'>#denominazione#</div><div style='width:35%;text-align:right;font-weight: 500'>#voti#</div><div style='width:10px'>&nbsp;</div></div></div>",
                                    data: AA_SierWebAppParams.risultati.candidati.data
                                }
                            ]
                        },
                        {height:10}
                    ]
                };
                
                let risultati_ui=webix.ui(risultati_box);
                if(risultati_box) 
                {
                    console.log("AA_SierWebApp.RefreshUi - visualizzo il box risultati: "+AA_SierWebAppParams.risultati.candidati.view_id);
                    risultati_ui.show();
                }

                if($$(AA_SierWebAppParams.risultati.candidati.footer_id))
                {
                    $$(AA_SierWebAppParams.risultati.candidati.footer_id).parse({"footer":"Dati aggiornati al "+aggiornamento});
                }
            }
        }
        //------------------------------------------------------------------------------------------------
    }catch (msg) {
        console.error("AA_SierWebApp.RefreshUi", msg);
    }
}

//Aggiorna i dati dell'affluenza
AA_SierWebApp.UpdateAffluenzaData = async function() {
    try 
    {
        let affluenza_data=[];
        AA_SierWebAppParams.affluenza.regionale.data=null;
        AA_SierWebAppParams.affluenza.regionale.aggiornamento=null;
     
        let risultati=AA_SierWebAppParams.data;
        if(!risultati) return;

        fmtNumber= new Intl.NumberFormat('it-IT');
        
        //--------------------- Affluenza Regione-----------------------------
        console.log("AA_SierWebApp.UpdateAffluenzaData - Aggiorno i dati  affluenza Regione");
        if(risultati.stats.circoscrizionale)
        {
            //console.log("AA_SierWebApp.UpdateAffluenzaData",risultati.stats.circoscrizionale);
            let num=1;
            for(let idCircoscrizione in risultati.stats.circoscrizionale)
            {
                //console.log("AA_SierWebApp.UpdateAffluenzaData - circoscrizione",idCircoscrizione);
                let count=0;
                let percent=0;
                let count_plain=0;
                for(let giornata in risultati.stats.circoscrizionale[idCircoscrizione].affluenza)
                {
                    if(AA_SierWebAppParams.affluenza.regionale.aggiornamento==null || risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].aggiornamento > AA_SierWebAppParams.affluenza.regionale.aggiornamento) AA_SierWebAppParams.affluenza.regionale.aggiornamento=risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].aggiornamento;
                    if(risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_12.count > 0)  
                    {
                        count_plain=risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_12.count;
                        count=fmtNumber.format(Number(count_plain));
                        percent=fmtNumber.format(Number(risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_12.percent));
                    }
                    if(risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_19.count > 0)  
                    {
                        count_plain=risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_19.count;
                        count=fmtNumber.format(Number(risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_19.count));
                        percent=fmtNumber.format(Number(risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_19.percent));
                    }
                    if(risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_22.count > 0)  
                    {
                        count_plain=risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_22.count;
                        count=fmtNumber.format(Number(risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_22.count));
                        percent=fmtNumber.format(Number(risultati.stats.circoscrizionale[idCircoscrizione].affluenza[giornata].ore_22.percent));
                    }                            
                }
                let script="AA_SierWebAppParams.affluenza.circoscrizionale.id_circoscrizione="+idCircoscrizione+";$$(AA_SierWebAppParams.affluenza.circoscrizionale.view_id).show();";
                affluenza_data.push({"number":num,"id":idCircoscrizione,"denominazione":"<a href='#' style='text-decoration: none; color: #0c467f' onClick='"+script+"'>"+risultati.stats.circoscrizionale[idCircoscrizione].denominazione+"</a>","count":count,"percent":percent,
                    "elettori":risultati.stats.circoscrizionale[idCircoscrizione].elettori_tot,
                    "count_plain":count_plain
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
            console.log("AA_SierWebApp.UpdateAffluenzaData - Aggiorno i dati circoscrizione di "+risultati.stats.circoscrizionale[AA_SierWebAppParams.affluenza.circoscrizionale.id_circoscrizione].denominazione);
            AA_SierWebAppParams.affluenza.circoscrizionale.data=[];
            let num=1;
            for(let comune in risultati.comuni)
            {
                if(risultati.comuni[comune].id_circoscrizione == AA_SierWebAppParams.affluenza.circoscrizionale.id_circoscrizione)
                {
                    let count=0;
                    let percent=0;
                    let count_plain=0;
                    for(let giornata in risultati.comuni[comune].affluenza)
                    {
                        if(AA_SierWebAppParams.affluenza.circoscrizionale.aggiornamento==null || risultati.comuni[comune].affluenza[giornata].aggiornamento > AA_SierWebAppParams.affluenza.circoscrizionale.aggiornamento) AA_SierWebAppParams.affluenza.circoscrizionale.aggiornamento=risultati.comuni[comune].affluenza[giornata].aggiornamento;
                        if(risultati.comuni[comune].affluenza[giornata].ore_12.count > 0)  
                        {
                            count_plain=risultati.comuni[comune].affluenza[giornata].ore_12.count;
                            count=fmtNumber.format(Number(risultati.comuni[comune].affluenza[giornata].ore_12.count));
                            percent=fmtNumber.format(Number(risultati.comuni[comune].affluenza[giornata].ore_12.percent));
                        }
                        if(risultati.comuni[comune].affluenza[giornata].ore_19.count > 0)  
                        {
                            count_plain=risultati.comuni[comune].affluenza[giornata].ore_19.count;
                            count=fmtNumber.format(Number(risultati.comuni[comune].affluenza[giornata].ore_19.count));
                            percent=fmtNumber.format(Number(risultati.comuni[comune].affluenza[giornata].ore_19.percent));
                        }
                        if(risultati.comuni[comune].affluenza[giornata].ore_22.count > 0)  
                        {
                            count_plain=risultati.comuni[comune].affluenza[giornata].ore_22.count;
                            count=fmtNumber.format(Number(risultati.comuni[comune].affluenza[giornata].ore_22.count));
                            percent=fmtNumber.format(Number(risultati.comuni[comune].affluenza[giornata].ore_22.percent));
                        }
                    }

                    AA_SierWebAppParams.affluenza.circoscrizionale.data.push({number:num, id:comune,"denominazione":risultati.comuni[comune].denominazione,"count":count,"percent":percent,"count_plain":count_plain});
                    num++;
                }
            }
            //console.log("AA_SierWebApp.UpdateAffluenzaData - dati circoscrizione di "+risultati.stats.circoscrizionale[AA_SierWebAppParams.affluenza.circoscrizionale.id_circoscrizione], AA_SierWebAppParams.affluenza.circoscrizionale.data);
        }
        //-------------------------------------------------------------------
    } catch (msg) {
        console.error("AA_SierWebApp.StartRisultatiApp", msg);
    }
};

//Aggiorna i dati dei risultati
AA_SierWebApp.UpdateRisultatiData = async function() {
    try 
    {
        AA_SierWebAppParams.risultati.data=null;
        AA_SierWebAppParams.risultati.data_coalizioni=null;
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
        console.log("AA_SierWebApp.UpdateRisultatiData - Aggiorno i dati  risultati Presidenti/coalizioni");
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

        //console.log("AA_SierWebApp.UpdateRisultatiData",risultati.stats.regionale);
        AA_SierWebAppParams.risultati.data=[];
        AA_SierWebAppParams.risultati.data_coalizioni=[];
        AA_SierWebAppParams.risultati.aggiornamento=dettaglio.aggiornamento;
        for(let idCoalizione in dettaglio)
        {
            if(typeof dettaglio[idCoalizione] === 'object')
            {
                //console.log("AA_SierWebApp.UpdateRisultatiData - coalizione",idCoalizione);
                AA_SierWebAppParams.risultati.data.push({id:idCoalizione,"presidente":dettaglio[idCoalizione].denominazione,denominazione_coalizione:"Coalizione "+dettaglio[idCoalizione].denominazione,"voti_raw":dettaglio[idCoalizione].voti,"voti":fmtNumber.format(Number(dettaglio[idCoalizione].voti)),"percent":fmtNumber.format(Number(dettaglio[idCoalizione].percent)),"image":"https://amministrazioneaperta.regione.sardegna.it"+dettaglio[idCoalizione].image});
                AA_SierWebAppParams.risultati.data_coalizioni.push({id:idCoalizione,"presidente":dettaglio[idCoalizione].denominazione,denominazione_coalizione:"Coalizione "+dettaglio[idCoalizione].denominazione,"voti_raw":dettaglio[idCoalizione].voti_coalizione,"percent":fmtNumber.format(Number(dettaglio[idCoalizione].percent_coalizione)),"voti":fmtNumber.format(Number(dettaglio[idCoalizione].voti_coalizione)),"image":"https://amministrazioneaperta.regione.sardegna.it"+dettaglio[idCoalizione].image});
            }
        }
    
        //ordina
        AA_SierWebAppParams.risultati.data.sort(function(a,b){if(Number(a.voti_raw) > Number(b.voti_raw)) return -1;if(Number(a.voti_raw) == Number(b.voti_raw)) return 0; return 1;});
        AA_SierWebAppParams.risultati.data_coalizioni.sort(function(a,b){if(Number(a.voti_raw) > Number(b.voti_raw)) return -1;if(Number(a.voti_raw) == Number(b.voti_raw)) return 0; return 1;});
        
        //console.log("AA_SierWebApp.UpdateRisultatiData - AA_SierWebAppParams.risultati.data",AA_SierWebAppParams.risultati.data);
        //-------------------------------------------------------------------

        //--------------------- Risultati Liste -----------------------------
        if(AA_SierWebAppParams.risultati.liste.id_coalizione > 0)
        {
            dettaglio=risultati.stats.regionale.risultati;
            if(AA_SierWebAppParams.risultati.id_circoscrizione>0 ) dettaglio=risultati.stats.circoscrizionale[AA_SierWebAppParams.risultati.id_circoscrizione].risultati;
            if(AA_SierWebAppParams.risultati.id_comune>0 ) dettaglio=risultati.comuni[AA_SierWebAppParams.risultati.id_comune].risultati;
            
            console.log("AA_SierWebApp.UpdateRisultatiData - Aggiorno i dati  risultati liste della coalizione: "+dettaglio.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].denominazione);
       
            AA_SierWebAppParams.risultati.liste.data=[];
            AA_SierWebAppParams.risultati.liste.aggiornamento=dettaglio.voti_lista.aggiornamento;
            for(let idLista in dettaglio.voti_lista)
            {
                if(typeof dettaglio.voti_lista[idLista] === 'object' && dettaglio.voti_lista[idLista].id_presidente==AA_SierWebAppParams.risultati.liste.id_coalizione)
                {
                    let font_size="font-size: normal";
                    if(String(dettaglio.voti_lista[idLista].denominazione).length > 24) font_size="font-size:smaller";
                    AA_SierWebAppParams.risultati.liste.data.push({id:idLista,"font_size":font_size,"denominazione":dettaglio.voti_lista[idLista].denominazione,"presidente":risultati.stats.regionale.risultati.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].denominazione,denominazione_coalizione:"Coalizione "+risultati.stats.regionale.risultati.voti_presidente[AA_SierWebAppParams.risultati.liste.id_coalizione].denominazione,"voti_raw":dettaglio.voti_lista[idLista].voti,"voti":fmtNumber.format(Number(dettaglio.voti_lista[idLista].voti)),"percent":fmtNumber.format(Number(dettaglio.voti_lista[idLista].percent)),"image":"https://amministrazioneaperta.regione.sardegna.it"+dettaglio.voti_lista[idLista].image});
                }
            }
        
            //ordina
            AA_SierWebAppParams.risultati.liste.data.sort(function(a,b){if(Number(a.voti_raw) > Number(b.voti_raw)) return -1;if(Number(a.voti_raw) == Number(b.voti_raw)) return 0; return 1;});

            //console.log("AA_SierWebApp.UpdateRisultatiData - AA_SierWebAppParams.risultati.liste.data",AA_SierWebAppParams.risultati.liste.data);
        }
        //-------------------------------------------------------------------

        //--------------------- Risultati candidato -----------------------------
        if(AA_SierWebAppParams.risultati.candidati.id_lista > 0 && AA_SierWebAppParams.risultati.id_circoscrizione > 0)
        {
            dettaglio=risultati.stats.circoscrizionale[AA_SierWebAppParams.risultati.id_circoscrizione].risultati;
            if(AA_SierWebAppParams.risultati.id_comune > 0 ) dettaglio=risultati.comuni[AA_SierWebAppParams.risultati.id_comune].risultati;
            
            console.log("AA_SierWebApp.UpdateRisultatiData - Aggiorno i dati  risultati candidati della lista: "+dettaglio.voti_lista[AA_SierWebAppParams.risultati.candidati.id_lista].denominazione);
       
            AA_SierWebAppParams.risultati.candidati.aggiornamento=dettaglio.voti_candidato.aggiornamento;

            AA_SierWebAppParams.risultati.candidati.data=[];
            for(let idCandidato in dettaglio.voti_candidato)
            {
                if(typeof dettaglio.voti_candidato[idCandidato] === 'object' && risultati.candidati[idCandidato].id_lista==AA_SierWebAppParams.risultati.candidati.id_lista && Number(dettaglio.voti_candidato[idCandidato].voti) > 0)
                {
                    AA_SierWebAppParams.risultati.candidati.data.push({id:idCandidato,"denominazione":risultati.candidati[idCandidato].nome+" "+risultati.candidati[idCandidato].cognome,"presidente":risultati.candidati[idCandidato].presidente,lista:risultati.candidati[idCandidato].lista,"voti_raw":dettaglio.voti_candidato[idCandidato].voti,"voti":fmtNumber.format(Number(dettaglio.voti_candidato[idCandidato].voti)),"percent":dettaglio.voti_candidato[idCandidato].percent,"image":"https://amministrazioneaperta.regione.sardegna.it"+risultati.candidati[idCandidato].image});
                }
            }

            //ordina
            AA_SierWebAppParams.risultati.candidati.data.sort(function(a,b){if(Number(a.voti_raw) > Number(b.voti_raw)) return -1;if(Number(a.voti_raw) == Number(b.voti_raw)) return 0; return 1;});
        
            //console.log("AA_SierWebApp.UpdateRisultatiData - AA_SierWebAppParams.risultati.candidati.data",AA_SierWebAppParams.risultati.candidati.data);
        }
        //-------------------------------------------------------------------

    } catch (msg) {
        console.error("AA_SierWebApp.UpdateRisultatiData", msg);
    }
};

//Rinfresca i dati sui risultati
AA_SierWebApp.RefreshRisultatiData = async function(feed_url,updateView=true,autoUpdate=true) {
    try 
    {
        console.log("AA_SierWebApp.RefreshRisultatiData - recupero il feed",feed_url);
        webix.ajax().get(feed_url).then(function(data)
        {
            let risultati=data.json();
            AA_SierWebAppParams.data=risultati;

            console.log("AA_SierWebApp.RefreshRisultatiData - faccio il parsing del feed");

            //-------------- Livelli di dettaglio --------------------------
            AA_SierWebAppParams.livello_dettaglio_data_tree=[{"id":"1","value":" tutta la Regione Sardegna","livello_dettaglio":0,"comune":0,"circoscrizione":0,"open":true,data:[]}];
            let circoscrizioneMap={};
            let index=0;
            for(let circoscrizione in risultati.stats.circoscrizionale)
            {
                AA_SierWebAppParams.livello_dettaglio_data_tree[0].data.push({"id":"1."+circoscrizione,"value":"Circoscrizione di "+risultati.stats.circoscrizionale[circoscrizione].denominazione,"comune":0,"circoscrizione":circoscrizione,data:[]});
                circoscrizioneMap[circoscrizione]=index;
                index++;
            }

            //console.log("AA_SierWebApp.RefreshRisultatiData - circoscrizionemap",circoscrizioneMap);

            for(let comune in risultati.comuni)
            {
                AA_SierWebAppParams.livello_dettaglio_data_tree[0].data[circoscrizioneMap[risultati.comuni[comune].id_circoscrizione]].data.push({"id":"1."+risultati.comuni[comune].id_circoscrizione+"."+comune,"value":"Comune di "+risultati.comuni[comune].denominazione,"livello_dettaglio":2,"comune":comune,"circoscrizione":risultati.comuni[comune].id_circoscrizione});
            }

            //console.log("AA_SierWebApp.RefreshRisultatiData - tree",AA_SierWebAppParams.livello_dettaglio_data_tree);
            //--------------------------------------------------------------

            //Aggiorna i dati dell'affluenza
            //AA_SierWebApp.UpdateAffluenzaData();
            //AA_SierWebApp.UpdateRisultatiData();
            
            if(updateView)
            {
                //Rinfresca la visualizzazione della sezione corrente
                console.log("AA_SierWebApp.RefreshRisultatiData - rinfresco la visualizzazione della sezioe corrente: "+AA_SierWebAppParams.sezione_corrente);
                if($$(AA_SierWebAppParams.sezione_corrente) && !$$(AA_SierWebAppParams.sezione_corrente).isVisible()) $$(AA_SierWebAppParams.sezione_corrente).show();
                else
                {
                    AA_SierWebApp.RefreshUi(AA_SierWebAppParams.sezione_corrente,AA_SierWebAppParams.sezione_corrente);
                }
            }
            else
            {
                console.error("AA_SierWebApp.RefreshRisultatiData",arguments);
            }

            if($$(AA_SierWebAppParams.mainUi_id) && autoUpdate && AA_SierWebAppParams.autoUpdateTime >= 60000)
            {
                console.log("AA_SierWebApp.RefreshRisultatiData - imposto l'aggiornamento automatico tra "+String(Number(AA_SierWebAppParams.autoUpdateTime)/60000)+" min");
                if(AA_SierWebAppParams.timeoutRisultati)
                {
                    clearTimeout(AA_SierWebAppParams.timeoutRisultati);
                }
                AA_SierWebAppParams.timeoutRisultati=setTimeout(AA_SierWebApp.RefreshRisultatiData,AA_SierWebAppParams.autoUpdateTime,feed_url,true);
            } 
        });
    } catch (msg) {
        console.error("AA_SierWebApp.RefreshRisultatiData", msg);
    }
};
//------------------------------------------------------------------------------------------------------------------------