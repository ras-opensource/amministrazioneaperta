//Variabili di riassegnazione
var struct_tree = "";
//------------------------

function AA_SetupStructTree(params) {

    console.log("AA_SetupStructTree");
    //console.log(params);

    //Rimuovo i dialoghi precedenti il dialogo per il riassegnamento...
    $("#dialog-riassegna").remove();
    $(".dialog-riassegna").remove();

    var dlg = $("<div id='dialog-riassegna' class='dialog-riassegna' title='Assegna ad altra struttura'></div>").dialog({
        autoOpen: false,
        resizable: false,
        height: "auto",
        width: 750,
        position: { my: "center", at: "top-5%", of: window },
        modal: true,
        buttons: {
            "Applica": function() {

                //Imposta le variabili applicative
                AA_MainApp.structTreeDlgParams.id_assessorato = $("input#riassegna-id-assessorato").val();
                AA_MainApp.structTreeDlgParams.assessorato_desc = $("input#riassegna-assessorato-text").val();
                AA_MainApp.structTreeDlgParams.id_direzione = $("input#riassegna-id-direzione").val();
                AA_MainApp.structTreeDlgParams.direzione_desc = $("input#riassegna-direzione-text").val();
                AA_MainApp.structTreeDlgParams.id_servizio = $("input#riassegna-id-servizio").val();
                AA_MainApp.structTreeDlgParams.servizio_desc = $("input#riassegna-servizio-text").val();

                if (params.postURL) {
                    var salva = $.post(params.postURL, $("#form-riassegna", this).serialize());
                    salva.done(function(data) {
                        var status = $(data).filter("#status").contents().text();
                        if (status == "0") {
                            if (params.successFunction) params.successFunction(params.successFunctionParams, $("#form-riassegna"), data);
                            $('.dialog-riassegna').dialog("close");
                        } else {
                            alert($(data).filter("#error").contents().text());
                            if (params.failFunction) params.failFunction(params.failFunctionParams, $("#form-riassegna"), data);
                        }
                    });

                    salva.fail(function() {
                        alert("Si è verificato un errore di rete.");
                        console.log("AA_SetupStructTree - ERRORE - fail to update - url: " + params.postURL);
                        $('.dialog-riassegna').dialog("close");
                        if (params.failFunction) params.failFunction(params.failFunctionParams, $("#form-riassegna"));
                    });
                } else {
                    if (params.successFunction) params.successFunction(params.failFunctionParams, $("#form-riassegna", this));
                    $('.dialog-riassegna').dialog("close");
                }
            },
            "Annulla": function() {
                $(this).dialog("close");
            }
        }
    });

    var dlgcontent = '<p class="validateTips"> </p>\
    <form id="form-riassegna">\
      <fieldset class="ui-helper-reset">\
        <input type="hidden" name="riassegna-id-object" id="riassegna-id-object" value="0">\
        <input type="hidden" name="riassegna-id-assessorato" id="riassegna-id-assessorato" value="0">\
        <input type="hidden" name="riassegna-assessorato-text" id="riassegna-assessorato-text" value="">\
        <input type="hidden" name="riassegna-id-direzione" id="riassegna-id-direzione" value="0">\
        <input type="hidden" name="riassegna-direzione-text" id="riassegna-direzione-text" value="">\
        <input type="hidden" name="riassegna-id-servizio" id="riassegna-id-servizio" value="0">\
        <input type="hidden" name="riassegna-servizio-text" id="riassegna-servizio-text" value="">\
      </fieldset>\
    </form>\
    <div id="struct_tree"></div>';

    dlg.html(dlgcontent);

    //---------------------------------------------

    //Refresh albero
    $("#struct_tree").on('refresh.jstree', function() {

        console.log("Refresh");

        var id_servizio = $("#riassegna-id-servizio").val();
        var id_direzione = $("#riassegna-id-direzione").val();
        var id_assessorato = $("#riassegna-id-assessorato").val();

        if (id_servizio > 0) {
            if ($('#struct_tree').jstree('is_loaded', 'servizio_' + id_servizio)) {
                $('#struct_tree').jstree('activate_node', 'servizio_' + id_servizio);
            } else console.log("Node not loaded");
        } else {
            if (id_direzione > 0) {
                $('#struct_tree').jstree('activate_node', 'direzione_' + id_direzione);
            } else {
                if (id_assessorato > 0) $('#struct_tree').jstree('activate_node', 'assessorato_' + id_assessorato);
            }
        }
    });

    //Seleziona nuova struttura
    $("#struct_tree").on("activate_node.jstree", function(evt, data) {
        //selected node object: data.node;
        //console.log(data);

        var id = data.node.id.split("_");

        if (id[0] == "servizio") {
            $("#riassegna-id-servizio").val(id[1]);
            $("#riassegna-servizio-text").val(data.node.text);

            id = data.node.parents[0].split("_");
            $("#riassegna-id-direzione").val(id[1]);
            $("#riassegna-direzione-text").val(data.instance.get_node(data.node.parent).text);

            id = data.node.parents[1].split("_");
            $("#riassegna-id-assessorato").val(id[1]);
            $("#riassegna-assessorato-text").val(data.instance.get_node(data.node.parents[1]).text);

            id[0] = "";
        }

        if (id[0] == "direzione") {
            $("#riassegna-id-servizio").val(0);
            $("#riassegna-servizio-text").val("");

            console.log("id_direzione: " + id[1]);
            $("#riassegna-id-direzione").val(id[1]);
            $("#riassegna-direzione-text").val(data.node.text);

            id = data.node.parents[0].split("_");
            $("#riassegna-id-assessorato").val(id[1]);
            $("#riassegna-assessorato-text").val(data.instance.get_node(data.node.parent).text);

            id[0] = "";

        }

        if (id[0] == "assessorato") {
            $("#riassegna-id-servizio").val(0);
            $("#riassegna-servizio-text").val("");
            $("#riassegna-id-direzione").val(0);
            $("#riassegna-direzione-text").val("");

            $("#riassegna-id-assessorato").val(id[1]);
            $("#riassegna-assessorato-text").val(data.node.text);
        }

        //console.log("id-object: "+$("#riassegna-id-object").val());
        //console.log("Assessorato: "+$("#riassegna-assessorato-text").val());
        //console.log("Direzione: "+$("#riassegna-direzione-text").val());
        //console.log("Servizio: "+$("#riassegna-servizio-text").val());
    });
}
//-----------------------------

//dialogo con l'albero della struttura utente
//params.title - titolo del dialogo
//params.id_object - identificativo oggetto gestito
//params.id_assessorato - identificativo assessorato
//params.id_direzione - identificativo direzione
//params.id_servizio - identificativo servizio
//params.only_user_struct - visualizza solamente la struttura dell'utente anzichè l'intera gerarchia dell'assessorato (default)
//params.hide_servizi - nasconde la visualizzazione dei servizi
//params.hide_direzioni - nasconde la visualizzazione delle direzioni
//params.show_all - visualizza tutta la struttura della RAS/ente
//params.getURL - URL al quale recuperare il contenuto  del dialogo
//params.postURL - URL al quale postare il contenuto del form del dialogo
//params.userFunction - Funzione utente da chiamare per la formattazione del contenuto del dialogo
//params.userFunctionParams - parametri da passare alla funzione userFunction
//params.successFunction - funzione da chiamare in caso venga premuto il pulsante "Procedi"
//params.successFunctionParams - parametri per la funzione da chiamare in caso venga premuto il pulsante "Procedi"
//params.failFunction - funzione da chiamare in caso qualcosa vada storto
//params.failFunctionParams - parametri per la funzione da chiamare in caso qualcosa vada storto

function AA_ModalStructTreeDlg(params) {
    console.log("AA_ModalStructTreeDlg");
    //console.log(params);

    //default values
    if (!params.title) params.title = "Assegna ad altra struttura";

    if (!params.getURL) {
        params.getURL = "system_ops.php?task=struttura-utente";
    }
    //--------------

    //Visualizza tutta la struttura della RAS - Ente
    if (params.show_all) params.getURL += "&show_all=1";
    //------------------------------------

    //Inizializza il dialogo
    AA_SetupStructTree(params);

    var salva = $.get(params.getURL);
    salva.done(function(data) {
        struct_tree = '{ "core" : { "data" : [ ';
        var status = $(data).filter("#status").contents().text();
        if (status == "0") {
            $(data).filter("struttura").children().each(function(index, e) {

                assessorato_desc = $(e).children().filter("descrizione").contents().text();

                struct_tree += ' { "id" : "assessorato_' + $(e).attr("id") + '", "text" : ' + JSON.stringify(assessorato_desc) + ', "children" : [';

                $(e).children().filter("direzione").each(function(index, dir) {
                    direzione_desc = $(dir).children().filter("descrizione").contents().text();

                    struct_tree += ' { "id" : "direzione_' + $(dir).attr("id") + '", "text" : ' + JSON.stringify(direzione_desc) + ', "children" : [';
                    $(dir).children().filter("servizio").each(function(index, ser) {
                        servizio_desc = $(ser).contents().text();
                        struct_tree += ' { "id" : "servizio_' + $(ser).attr("id") + '", "text" : ' + JSON.stringify(servizio_desc);
                        if ($(ser).is(':last-child')) struct_tree += " }";
                        else struct_tree += " } ,";
                    });

                    if ($(dir).is(':last-child')) struct_tree += " ] }";
                    else struct_tree += " ] } , ";
                });

                if ($(e).is(':last-child')) struct_tree += " ] }";
                else struct_tree += " ] } , ";
            });

            struct_tree += " ] } }";
            //console.log(struct_tree);

            //Aggiorna l'albero della struttura
            $("#struct_tree").jstree(JSON.parse(struct_tree));

            //Imposta i valori iniziali
            if (params.id_object) $("#riassegna-id-object").val(params.id_object);
            if (params.id_assessorato) $("#riassegna-id-assessorato").val(params.id_assessorato);
            if (params.id_direzione) $("#riassegna-id-direzione").val(params.id_direzione);
            if (params.id_servizio) $("#riassegna-id-servizio").val(params.id_servizio);

            if (params.userFunction) params.userFunction(params.userFunctionParams);

            //Elimina il dialogo alla chiusura
            $("#dialog-riassegna").on("dialogclose", function(event, ui) {
                $(this).remove();
            });

            //Rinfresca la visualizzazione dell'albero
            $("#struct_tree").jstree("refresh");

            $("#dialog-riassegna").dialog("open");
        } else {
            alert($(data).filter("#error").contents().text());
            if (params.failFunction) {
                params.failFunction(params.failFunctionParams);
            }
        }
    });

    salva.fail(function() {
        alert("Si è verificato un errore di rete.");
        console.log("AA_ModalStructTreeDlg - ERRORE - fail to update - url: " + params.getURL);
        if (params.failFunction) params.failFunction(params.failFunctionParams);
    });
}

//dialogo di ricerca
async function AA_MainSearchDlg(params) {
    console.log("AA_MainSearchDlg()");

    var searchBox = $("div#" + AA_MainApp.searchBoxParams.id);

    //rimuove il box esistente
    if (searchBox.length > 0) {
        //console.log("AA_MainSearchDlg",searchBox);
        console.log("AA_MainSearchDlg - Rimuovo il box esistente...");
        searchBox.remove();
    }

    var dlg = $("<div id='" + AA_MainApp.searchBoxParams.id + "' class='" + AA_MainApp.searchBoxParams.id + "' title='Cerca...'></div>").dialog({
        autoOpen: false,
        resizable: false,
        height: "auto",
        width: 750,
        position: { my: "center", at: "top+5%", of: window },
        modal: true,
        buttons: {
            "Reset": AA_ResetFilterParams,

            "Applica": function() {
                AA_UpdateFilterParams();

                if (AA_MainApp.searchBoxParams.defaultSuccessFunction) AA_MainApp.searchBoxParams.defaultSuccessFunction($(AA_MainApp.searchBoxParams.defaultSuccessFunctionParams, "form#" + AA_MainApp.searchBoxParams.id + "_form", this));
                if (params && params.successFunction) params.successFunction(params.successFunctionParams, $("form#" + AA_MainApp.searchBoxParams.id + "_form", this));
                $('div#' + AA_MainApp.searchBoxParams.id).dialog("close");
            },
            "Annulla": function() {
                $(this).dialog("close");
            }
        }
    });

    //Elimina il dialogo alla chiusura
    dlg.on("dialogclose", function(event, ui) {
        $(this).remove();
    });

    //Flag revisionata
    var bShowRevisionataFlag = true; //default
    if (params && (params.showRevisionataFlag == false || params.hideRevisionataFlag == true)) bShowRevisionataFlag = false;
    else {
        if (AA_MainApp.searchBoxParams.hideRevisionata == true || AA_MainApp.searchBoxParams.showRevisionataFlag == false) bShowRevisionataFlag = false;
    }

    //Struttura
    var bShowStruttura = true; //default
    if (params && (params.showStruttura == false || params.hideStruttura == true)) bShowStruttura = false;
    else {
        if (AA_MainApp.searchBoxParams.hideStruttura == true || AA_MainApp.searchBoxParams.showStruttura == false) bShowStruttura = false;
    }

    var dlgcontent = '<p class="validateTips"> </p>\
    <form id="' + AA_MainApp.searchBoxParams.id + '_form">\
    <div style="display: flex; flex-direction: column; width:100%;">\
        <div style="display: flex; width:100%; margin-bottom: .5em;">\
            <div style="width: 25%;">Stato della scheda: </div>\
            <div id="status_set_search" style="width: 75%;">\
                <input type="radio" id="stato_pubblicata_search" class="text ui-widget-content ui-corner-all" name="stato_scheda_search" value="2"/><label for="stato_pubblicata_search">Pubblicata</label>\
                <input type="radio" id="stato_bozza_search" class="text ui-widget-content ui-corner-all" name="stato_scheda_search" value="1"/><label for="stato_bozza_search">Bozza</label>\
                <input type="checkbox" id="stato_cestinata_search" class="text ui-widget-content ui-corner-all" name="stato_cestinata_search"/><label for="stato_cestinata_search">Cestinata</label>';
    if (bShowRevisionataFlag) dlgcontent += '<input type="checkbox" id="stato_revisionata_search" class="text ui-widget-content ui-corner-all" name="stato_revisionata_search"/><label for="stato_revisionata_search">Revisionata</label>';
    dlgcontent += '</div></div>';

    //Struttura
    if (bShowStruttura) {
        dlgcontent += '<div style="display: flex; width:100%; margin-bottom: .5em;">\
            <div style="width: 25%;">Struttura: </div>\
            <div style="width: 75%;"><a href="#" id="struttura_desc_search">' + AA_MainApp.searchBoxParams.struttura_desc_search + '</a></div>\
            </div>\
            <div style="width: 100%;"><input type="hidden" id="id_assessorato_search" name="id_assessorato_search" value="' + AA_MainApp.searchBoxParams.id_assessorato_search + '"><input type="hidden" id="id_direzione_search" name="id_direzione_search" value="' + AA_MainApp.searchBoxParams.id_direzione_search + '"><input type="hidden" id="id_servizio_search" name="id_servizio_search" value="' + AA_MainApp.searchBoxParams.id_servizio_search + '"></div>';
    }

    dlgcontent += '<div style="display: flex; flex-direction: column; width:100%; margin-bottom: .5em;" id="userDefined"></div>\
        <div style="display: flex; width:100%; margin-top: .5em; border-top: 1px solid gray; padding-top: .3em;">\
            <div style="width: 25%;">Vai alla pagina</div>\
            <div style="width: 75%;"><input type="text" id="goPage" class="text ui-widget-content ui-corner-all" name="goPage" style="width: 5em;" value="" /></div>\
        </div>\
    </div>\
    </form>';

    dlg.html(dlgcontent);

    //Gestione della selezione della struttura di filtraggio
    if (bShowStruttura) {
        $("a#struttura_desc_search", dlg).click(function() {
            params = {
                successFunction: function(params, form) {

                    id_assessorato = AA_MainApp.structTreeDlgParams.id_assessorato;
                    id_direzione = AA_MainApp.structTreeDlgParams.id_direzione;
                    id_servizio = AA_MainApp.structTreeDlgParams.id_servizio;

                    $("input#id_assessorato_search").val(id_assessorato);
                    $("input#id_direzione_search").val(id_direzione);
                    $("input#id_servizio_search").val(id_servizio);

                    var descrizione = "Qualunque";
                    if (id_assessorato > 0) descrizione = AA_MainApp.structTreeDlgParams.assessorato_desc;
                    if (id_direzione > 0) descrizione = AA_MainApp.structTreeDlgParams.direzione_desc;
                    if (id_servizio > 0) descrizione = AA_MainApp.structTreeDlgParams.servizio_desc;

                    $("a#struttura_desc_search").html('<span>' + descrizione + '</span>');
                },
                id_object: 0,
                id_assessorato: $("input#id_assessorato_search").val(),
                id_direzione: $("input#id_direzione_search").val(),
                id_servizio: $("input#id_servizio_search").val()
            }

            //Visualizza tutte le strutture della RAS/Ente
            if (AA_MainApp.searchBoxParams.showAllStructs == 1) {
                params.show_all = 1;
            }

            AA_ModalStructTreeDlg(params);
        });
    }

    //Chiama la funzione definita sui parametri generali per integrare i campi di ricerca
    var getURL = AA_MainApp.searchBoxParams.getURL;
    if (params && params.getURL) getURL = params.getURL;
    if (getURL) {
        var content = $.get(getURL);

        let data = await $.get(getURL);

        var status = $(data).filter("#status").contents().text();
        if (status == "0") {
            $(data).filter("#content").contents().each(function(index, e) { $("div#userDefined", $('div#' + AA_MainApp.searchBoxParams.id)).append(e); });
        }

        //Aggiorna i campi
        AA_UpdateFilterParams(true);

        //Apre il dialogo
        if (params && params.hidden == true) return;
        else $('div#' + AA_MainApp.searchBoxParams.id).dialog("open");

        /*content.done(function (data){
            var status = $(data).filter("#status").contents().text();
            if (status == "0") {
                $(data).filter("#content").contents().each(function(index, e) { $("div#userDefined",$('div#'+AA_MainApp.searchBoxParams.id)).append(e); });
            }

            //Aggiorna i campi
            AA_UpdateFilterParams(true);

            //Apre il dialogo
            if(params && params.hidden == true) return;
            else $('div#'+AA_MainApp.searchBoxParams.id).dialog("open");            
        });

        /*content.fail(function(){
            console.log("AA_MainSearchDlg() - Errore di rete.");

            //Aggiorna i campi
            AA_UpdateFilterParams(true);

            //Apre il dialogo
            if(params && params.hidden == true)
            {
                return;
            } 
            else $('div#'+AA_MainApp.searchBoxParams.id).dialog("open");
        });*/
    } else {
        //Aggiorna i campi
        AA_UpdateFilterParams(true);

        //Apre il dialogo
        if (params && params.hidden == true) return;
        else $('div#' + AA_MainApp.searchBoxParams.id).dialog("open");
    }
}

//Dialogo modale con feedback e callback function
//params.id - identificativo del dialogo
//params.title - titolo del dialogo
//params.getURL - URL al quale recuperare il contenuto  del dialogo
//params.task - task (da utilizzarsi in assenza del paramtro getURL)
//params.postURL - URL al quale postare il contenuto del form del dialogo
//params.userFunction - Funzione utente da chiamare per la formattazione del contenuto del dialogo
//params.userFunctionParams - parametri da passare alla funzione userFunction
//params.successFunction - funzione da chiamare in caso venga premuto il pulsante "Procedi"
//params.successFunctionParams - parametri per la funzione da chiamare in caso venga premuto il pulsante "Procedi"
//params.failFunction - funzione da chiamare in caso qualcosa vada storto
//params.failFunctionParams - parametri per la funzione da chiamare in caso qualcosa vada storto
//params.dlgContent - contenuto del dialogo invece del parametro getURL
//params.isPopUp - non visualizzare pulsanti
//params.showWait - Visualizza la finestra di attesa
function AA_ModalFeedbackDlg(params) {
    //Inizializzazione parametri di default
    if (!params.id) params.id = "AA_FeedBackSystemDlg";
    if (!params.title) params.id = "Nuova finestra di dialogo";
    //-------------------------------------

    if (!params.getURL && !params.dlgContent) {
        console.log("AA_ModalFeedbackDlg - ERRORE - parametro: getURL o dlgContent non impostato.");
        console.log(params);

        if (params.failFunction) {
            params.failFunction(params.failFunctionParams);
        }
    }

    if ($("#" + params.id).length > 0) {
        //Rimuovo i dialoghi già esistenti con lo stesso id
        console.log("ModalFeedbackDlg() - Rimuovo i dialoghi già esistenti con lo stesso id (" + params.id + ")");
        $("#" + params.id).remove();
        $("." + params.id).remove();
    }

    //larghezza
    dlgWidth = $(window).width() * .40;
    if (params.width) dlgWidth = params.width;

    //Salva la posizione di scroll della finestra
    var scrollsize = window.scrollY;

    var newDlg = $("<div id='" + params.id + "' class='" + params.id + "'></div>").dialog({
        autoOpen: false,
        resizable: false,
        height: "auto",
        width: dlgWidth,
        modal: true
    });

    if (params.getURL) {
        var dlg = $.get(params.getURL);
        dlg.done(function(data) {
            var status = $(data).filter("#status").contents().text();
            if (status == "0") {
                if (!params.isPopUp) {
                    newDlg.dialog({
                        buttons: {
                            "Annulla": function() {
                                $(this).dialog("close");
                            },

                            "Procedi": function() {
                                var bValid = true;
                                if (bValid) {
                                    //salva i dati
                                    if (params.postURL) {
                                        if (params.showWait) {
                                            //visualizza la schermata di attesa
                                            $("#message-content").html("Elaborazione in corso...<br />attendere prego");
                                            $("#dialog-message").dialog("open");
                                        }
                                        var salva = $.post(params.postURL, $(".form-data", this).serialize());
                                        salva.done(function(data) {
                                            if (params.showWait) {
                                                //nascondi la schermata di attesa
                                                $("#dialog-message").dialog("close");
                                            }
                                            var status = $(data).filter("#status").contents().text();
                                            if (status == "0") {
                                                if ($(data).filter("#error").contents().text().length > 0) alert($(data).filter("#error").contents().text());
                                                if (params.successFunction) {
                                                    params.successFunction(params.successFunctionParams, $(".form-data"), data);
                                                }
                                                $("#" + params.id).dialog("close");
                                            } else {
                                                if ($(data).filter("#error").contents().text().length > 0) alert($(data).filter("#error").contents().text());
                                                if (params.failFunction) {
                                                    params.failFunction(params.failFunctionParams, $(".form-data"), data);
                                                }
                                            }
                                        });

                                        salva.fail(function() {
                                            alert("Si è verificato un errore di rete. i dati potrebbero non essere stati salvati.");
                                            console.log("AA_ModalFeedbackDlg - ERRORE - fail to post url: " + params.postURL);
                                            if (params.failFunction) {
                                                params.failFunction(params.failFunctionParams, $(".form-data"), data);
                                            }
                                        });
                                    } else {
                                        if (params.successFunction) {
                                            params.successFunction(params.successFunctionParams, $(".form-data", this));
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    newDlg.dialog({
                        buttons: {
                            "Chiudi": function() {
                                $(this).dialog("close");
                                if (params.successFunction) {
                                    params.successFunction(params.successFunctionParams, $(".form-data", this));
                                }
                            }
                        }
                    });
                }

                var content = $(data).filter("#content").contents();
                newDlg.html(content);

                //Calendario
                $(".AA_DatePicker").each(function() {
                    $(this).datepicker({
                        showOn: "button",
                        buttonImage: "/web/amministrazione_aperta/immagini/calendar.gif",
                        buttonImageOnly: true,
                        buttonText: "Mostra il calendario",
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: "yy-mm-dd"
                    })
                });

                if (params.userFunction) {
                    //console.log("UserFunction"); 
                    params.userFunction(params.userFunctionParams);
                }

                newDlg.on("dialogclose", function(event, ui) {
                    $(this).remove();
                });

                var height = newDlg.dialog("option", "height");

                //posizione
                var dlgPosition = {
                        my: "center top",
                        at: "center top+220"
                    }
                    //console.log(dlgPosition);
                window.scrollTo(0, scrollsize);
                newDlg.dialog("option", "position", dlgPosition);
                newDlg.dialog("option", "title", params.title);
                newDlg.dialog("open");

            } else {
                alert($(data).filter("#error").contents().text());
                if (params.failFunction) {
                    params.failFunction(params.failFunctionParams);
                }
            }
        });

        dlg.fail(function() {
            alert("Si è verificato un errore di rete. i dati potrebbero non essere stati salvati.");
            console.log("AA_ModalFeedbackDlg - ERRORE - fail to get url: " + params.getURL);
        });
    } else {
        if ($("#" + params.id).length > 0) {
            //Rimuovo i dialoghi già esistenti con lo stesso id
            console.log("ModalFeedbackDlg() - Rimuovo i dialoghi già esistenti con lo stesso id (" + params.id + ")");
            $("#" + params.id).remove();
            $("." + params.id).remove();
        }

        if (!params.isPopUp) {
            newDlg.dialog({
                buttons: {
                    "Annulla": function() {
                        $(this).dialog("close");
                    },
                    "Procedi": function() {
                        var bValid = true;
                        if (bValid) {
                            //salva i dati
                            if (params.postURL) {
                                if (params.showWait) {
                                    //visualizza la schermata di attesa
                                    $("#message-content").html("Elaborazione in corso...<br />attendere prego");
                                    $("#dialog-message").dialog("open");
                                }
                                var salva = $.post(params.postURL, $(".form-data", this).serialize());
                                salva.done(function(data) {
                                    if (params.showWait) {
                                        //nascondi la schermata di attesa
                                        $("#dialog-message").dialog("close");
                                    }
                                    var status = $(data).filter("#status").contents().text();
                                    if (status == "0") {
                                        if ($(data).filter("#error").contents().text().length > 0) alert($(data).filter("#error").contents().text());
                                        if (params.successFunction) {
                                            params.successFunction(params.successFunctionParams, $(".form-data"), data);
                                        }
                                        $("#" + params.id).dialog("close");
                                    } else {
                                        if ($(data).filter("#error").contents().text().length > 0) alert($(data).filter("#error").contents().text());
                                        if (params.failFunction) {
                                            params.failFunction(params.failFunctionParams, $(".form-data"), data);
                                        }
                                    }
                                });

                                salva.fail(function() {
                                    alert("Si è verificato un errore di rete. i dati potrebbero non essere stati salvati.");
                                    console.log("AA_ModalFeedbackDlg - ERRORE - fail to post url: " + params.postURL);
                                    if (params.failFunction) {
                                        params.failFunction(params.failFunctionParams, $(".form-data"), data);
                                    }
                                });
                            } else {
                                if (params.successFunction) {
                                    params.successFunction(params.successFunctionParams, $(".form-data", this));
                                }
                            }
                        }
                    }
                }
            });
        } else {
            newDlg.dialog({
                buttons: {
                    "Chiudi": function() {
                        $(this).dialog("close");
                        if (params.successFunction) {
                            params.successFunction(params.successFunctionParams, $(".form-data", this));
                        }
                    }
                }
            });
        }


        if (params.dlgContent) {
            newDlg.html(params.dlgContent);
        }

        //Calendario
        $(".AA_DatePicker").each(function() {
            $(this).datepicker({
                showOn: "button",
                buttonImage: "/web/amministrazione_aperta/immagini/calendar.gif",
                buttonImageOnly: true,
                buttonText: "Mostra il calendario",
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd"
            })
        });

        if (params.userFunction) {
            //console.log("UserFunction"); 
            params.userFunction(params.userFunctionParams);
        }

        newDlg.on("dialogclose", function(event, ui) {
            $(this).remove();
        });

        height = newDlg.dialog("option", "height");
        //posizione
        var dlgPosition = {
            my: "center top",
            at: "center top+240"
        }
        window.scrollTo(0, scrollsize);
        newDlg.dialog("option", "position", dlgPosition);
        newDlg.dialog("option", "title", params.title);
        newDlg.dialog("open");

    }
}

//old stuff (deprecated)
function ModalFeedbackDlg(id, title = "Nuovo dialogo", getURL, postURL, successFunction, successfuncParams, failFunction, failFuncParams) {
    var dlg = $.get(getURL);
    dlg.done(function(data) {
        var status = $(data).filter("#status").contents().text();
        if (status == "0") {
            if ($("#" + id).length > 0) {
                //Rimuovo i dialoghi già esistenti con lo stesso id
                console.log("ModalFeedbackDlg() - Rimuovo i dialoghi già esistenti con lo stesso id (" + id + ")");
                $("#" + id).remove();
                $("." + id).remove();
            }

            //larghezza
            dlgWidth = $(window).width() * .40;

            //posizione
            dlgPosition = {
                my: "center top",
                at: "center center"
            }

            var newDlg = $("<div id='" + id + "' class='" + id + "'></div>").dialog({
                autoOpen: false,
                resizable: false,
                height: "auto",
                width: dlgWidth,
                position: dlgPosition,
                modal: true,
                buttons: {
                    "Procedi": function() {
                        var bValid = true;
                        if (bValid) {
                            //salva i dati
                            if (postURL) {
                                var salva = $.post(postURL, $(".form-data", this).serialize());
                                salva.done(function(data) {
                                    var status = $(data).filter("#status").contents().text();
                                    if (status == "0") {
                                        alert($(data).filter("#error").contents().text());
                                        if (successFunction) {
                                            successFunction(successfuncParams, data);
                                        }
                                        $("#" + id).dialog("close");
                                    } else {
                                        alert($(data).filter("#error").contents().text());
                                        if (failFunction) {
                                            failFunction(failFuncParams, data);
                                        }
                                    }
                                });

                                salva.fail(function() {
                                    alert("Si è verificato un errore di rete. i dati potrebbero non essere stati salvati.");
                                    console.log("fail to update - url: " + params.postURL);
                                    if (failFunction) {
                                        failFunction(failFuncParams, data);
                                    }
                                });
                            } else {
                                if (successFunction) {
                                    successFunction(successfuncParams, $(".form-data", this).serialize());
                                }
                            }
                        }
                    },
                    "Chiudi": function() {
                        $(this).dialog("close");
                        $(this).remove();
                    }
                }
            });

            var content = $(data).filter("#content").contents();
            newDlg.html(content);

            //Calendario
            $(".AA_DatePicker").datepicker({ changeMonth: true, changeYear: true });
            $(".AA_DatePicker").datepicker("option", "dateFormat", "yy-mm-dd");

            newDlg.dialog("option", "title", title)
            newDlg.dialog("open");
        } else {
            alert($(data).filter("#error").contents().text());
        }
    });

    dlg.fail(function() {
        alert("Si è verificato un errore di rete. i dati potrebbero non essere stati salvati.");
        console.log("fail to update - url: " + getURL);
    });
}

//Dialogo modale con feedback e callback function per il caricamento di file
//params.id - identificativo del dialogo
//params.title - titolo del dialogo
//params.getUrl - Url al quale recuperare il contenuto del dialogo
//params.postUrl - Url al quale fare il post del form
//params.successFunction - Funzione da chiamare in caso di successo
//params.successFunctionParams - Parametri da passare alla funzione di successo
//params.failFunction - funzione da chiamare in caso di fallimento
//params.failFunctionParams - parametri da passare alla funzione di fallimento
//params.showAlertOnSuccess - visualizza un alert in caso di successo (default disattivato)
//params.hideAlertOnFail - nascondi un alert in caso di insuccesso (default disattivato)
function AA_ModalUploadFileDlg(params) {
    //Imposta unidentificativo qualora non sia impostato
    if (!params.id) params.id = "AA_ModalFeedbackUploadDlg";

    //Salva la posizione di scroll della finestra
    var scrollsize = window.scrollY;

    var dlg = $.get(params.getURL);
    dlg.done(function(data) {
        var status = $(data).filter("#status").contents().text();
        if (status == "0") {
            if ($("#" + params.id).length > 0) {
                //Rimuovo i dialoghi già esistenti con lo stesso id
                console.log("AA_ModalFeedbackUploadDlg() - Rimuovo i dialoghi già esistenti con lo stesso id (" + params.id + ")");
                $("#" + params.id).remove();
                $("." + params.id).remove();
            }

            var newDlg = $("<div id='" + params.id + "' class='" + params.id + "'></div>").dialog({
                autoOpen: false,
                resizable: false,
                height: "auto",
                width: window.width * .40,
                modal: true,
                buttons: {
                    "Chiudi": function() {
                        $(this).dialog("close");
                        $(this).remove();
                    }
                }
            });

            var content = $(data).filter("#content").contents();
            newDlg.html(content);

            $('#progress-box', newDlg).hide();

            $('#file-upload', newDlg).fileupload({
                url: params.postURL,
                done: function(e, response) {
                    var status = $(response._response.result).filter("#status").contents().text();
                    if (status == "0") {
                        if (params.showAlertOnSuccess) alert($(response._response.result).filter("#error").contents().text());
                        if (params.successFunction) {
                            params.successFunction(params.successFunctionParams, response);
                        }
                        $("#" + params.id).dialog("close");
                    } else {
                        $('#progress-box', newDlg).hide();
                        $('#UploadDlgContent', newDlg).show();
                        if (!params.hideAlertOnFail) alert($(response._response.result).filter("#error").contents().text());
                        if (params.failFunction) {
                            params.failFunction(params.failFunctionParams, response);
                        }
                    }
                },
                fail: function() {
                    alert("Si è verificato un errore di rete. i dati potrebbero non essere stati salvati.");
                    console.log("fail to update - url: " + params.postURL);
                    $("#" + params.id).dialog("close");
                },

                progress: function(e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress-box', newDlg).show();
                    //$('#upload-progress-percent', newDlg).html("("+progress+"%)");
                    $('#UploadDlgContent', newDlg).hide();
                }
            });

            newDlg.on("dialogclose", function(event, ui) {
                $(this).remove();
            });

            //posizione
            var dlgPosition = {
                    my: "center top",
                    at: "center top+240"
                }
                //console.log(dlgPosition);
            window.scrollTo(0, scrollsize);
            newDlg.dialog("option", "position", dlgPosition);
            newDlg.dialog("option", "title", params.title);
            newDlg.dialog("open");
        } else {
            alert($(data).filter("#error").contents().text());
        }
    });

    dlg.fail(function() {
        alert("Si è verificato un errore di rete. i dati potrebbero non essere stati salvati.");
        console.log("fail to update - url: " + params.getURL);
    });
}

//Dialogo modale con feedback e callback function per il caricamento di file
function ModalUploadFileDlg(id, title = "Upload file", getURL, postURL, successFunction, successfuncParams, failFunction, failFuncParams) {
    var dlg = $.get(getURL);
    dlg.done(function(data) {
        var status = $(data).filter("#status").contents().text();
        if (status == "0") {
            if ($("#" + id).length > 0) {
                //Rimuovo i dialoghi già esistenti con lo stesso id
                console.log("ModalFeedbackUploadDlg() - Rimuovo i dialoghi già esistenti con lo stesso id (" + id + ")");
                $("#" + id).remove();
                $("." + id).remove();
            }

            var newDlg = $("<div id='" + id + "' class='" + id + "'></div>").dialog({
                autoOpen: false,
                resizable: false,
                height: "auto",
                width: $(window).width() * .40,
                modal: true,
                buttons: {
                    "Chiudi": function() {
                        $(this).dialog("close");
                        $(this).remove();
                    }
                }
            });

            var content = $(data).filter("#content").contents();
            newDlg.html(content);

            $('#progress-box', newDlg).hide();

            $('#file-upload', newDlg).fileupload({
                url: postURL,
                done: function(e, response) {
                    var status = $(response._response.result).filter("#status").contents().text();
                    if (status == "0") {
                        alert($(response._response.result).filter("#error").contents().text());
                        if (successFunction) {
                            successFunction(successfuncParams, response);
                        }
                        $("#" + id).dialog("close");
                    } else {
                        $('#progress-box', newDlg).hide();
                        $('#UploadDlgContent', newDlg).show();
                        alert($(response._response.result).filter("#error").contents().text());
                        if (failFunction) {
                            failFunction(failFuncParams, response);
                        }
                    }
                },
                fail: function() {
                    alert("Si è verificato un errore di rete. i dati potrebbero non essere stati salvati.");
                    console.log("fail to update - url: " + postURL);
                    $("#" + id).dialog("close");
                },

                progress: function(e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress-box', newDlg).show();
                    //$('#upload-progress-percent', newDlg).html("("+progress+"%)");
                    $('#UploadDlgContent', newDlg).hide();
                }
            });

            newDlg.on("dialogclose", function(event, ui) {
                $(this).remove();
            });

            //posizione
            var dlgPosition = {
                my: "center top",
                at: "center top+240"
            }

            newDlg.dialog("option", "position", dlgPosition);
            newDlg.dialog("option", "title", title)
            newDlg.dialog("open");
        } else {
            alert($(data).filter("#error").contents().text());
        }
    });

    dlg.fail(function() {
        alert("Si è verificato un errore di rete. i dati potrebbero non essere stati salvati.");
        console.log("fail to update - url: " + getURL);
    });
}

async function AA_RefreshAccordion(params) {

    console.log("AA_RefreshAccordion() - parametri: ", params, AA_MainApp);

    if ($("#" + AA_MainApp.accordionBoxParams.id).length == 0) {
        console.log("Accordion box non trovato...");
        return;
    }

    var activeAccordionItem = AA_MainApp.accordionBoxParams.activeAccordionItem;
    if (params && params.activeAccordionItem) {
        activeAccordionItem = params.activeAccordionItem;
    }

    await AA_UpdateFilterParams(true);

    if (params && params.userUpdateFilterFunction) params.userUpdateFilterFunction(params.userUpdateFilterFunctionParams);

    $("#message-content").html("Caricamento in corso...<br />attendere prego");
    $("#dialog-message").dialog("open");

    //Recupera la lista delle voci
    console.log("query params: " + AA_MainApp.searchBoxParams.query);

    //var lista = $.post("incarichi_ops.php?task=search-titolari", $("#form-incarichi-search").serialize() + "&" + queryString);
    if (!queryString) var queryString = '';
    var lista = $.post(AA_MainApp.searchBoxParams.postURL, queryString + "&" + AA_MainApp.searchBoxParams.query);
    lista.done(function(data) {
        console.log("Lista caricata.");
        //console.log($(data).filter("#content")[0].innerHTML);
        $("#dialog-message").dialog("close");

        //navigator section
        var titolo = "Schede";
        if (AA_MainApp.searchBoxParams.stato_scheda_search == 1) titolo = "bozze";
        else titolo = "schede pubblicate";
        if (AA_MainApp.searchBoxParams.stato_cestinata_search == 1) titolo += " cestinate";
        if (AA_MainApp.searchBoxParams.stato_revisionata_search == 1) titolo += " revisionate";

        var curPage = parseInt($(data).filter("#navigator").attr("curPage")) + 1;
        if (AA_MainApp.accordionBoxParams.curPage != curPage) {
            console.log("Aggiorno la pagina corrente");
            AA_MainApp.accordionBoxParams.curPage = curPage;
            AA_MainApp.accordionBoxParams.curActiveAccordionItem = 0;
            activeAccordionItem = 0;
        }
        AA_MainApp.accordionBoxParams.totalPages = parseInt($(data).filter("#navigator").attr("totalPages"));

        $("#navigator").empty();
        $(data).filter("#navigator").contents().each(function(index, e) { $("#navigator").append(e); });
        $("#first").button({ icons: { primary: "ui-icon-seek-first" }, text: false }).click(function() {
            AA_MainApp.searchBoxParams.goToPage = 1;
            AA_RefreshAccordion();
        });
        $("#next").button({ icons: { primary: "ui-icon-seek-next" }, text: false }).click(function() {
            AA_MainApp.searchBoxParams.goToPage = AA_MainApp.accordionBoxParams.curPage + 1;
            AA_RefreshAccordion();
        });
        $("#prev").button({ icons: { primary: "ui-icon-seek-prev" }, text: false }).click(function() {
            AA_MainApp.searchBoxParams.goToPage = parseInt(AA_MainApp.accordionBoxParams.curPage) - 1;
            AA_RefreshAccordion();
        });
        $("#last").button({ icons: { primary: "ui-icon-seek-end" }, text: false }).click(function() {
            AA_MainApp.searchBoxParams.goToPage = parseInt(AA_MainApp.accordionBoxParams.totalPages);
            AA_RefreshAccordion();
        });
        if ($(data).filter("#content").attr("count") > 0) $("#navigatorTitle").text(titolo + " totali: " + $(data).filter("#content").attr("count") + " - pagina " + AA_MainApp.accordionBoxParams.curPage + " di " + AA_MainApp.accordionBoxParams.totalPages);
        else $("#navigatorTitle").text("Nessun dato presente (" + titolo + ")");
        //end navigator section 

        //Imposta il messaggio iniziale
        $("#message_list").empty();
        if ($(data).filter("#content").attr("count") == 0) $("#message_list").append("<p style='text-align: center;'>Il registro è vuoto o non sono state trovate voci corrispondenti ai criteri di ricerca impostati.<br/>Fai click sul pulsante '<span><strong>+</strong></span>' per inserire una nuova voce</p>");

        //Riempi la lista
        var status = $(data).filter("#status").contents().text();
        if (status == "0") {
            $("#" + AA_MainApp.accordionBoxParams.id).empty();

            $(data).filter("#content").contents().each(function(index, e) { $("#" + AA_MainApp.accordionBoxParams.id).append(e); });

            //Popola l'accordion;
            $("#" + AA_MainApp.accordionBoxParams.id).accordion({ heightStyle: "content" });
            $("#" + AA_MainApp.accordionBoxParams.id).accordion("refresh");

            //Seleziona l'item corrente
            if (activeAccordionItem == -1) activeAccordionItem = AA_MainApp.accordionBoxParams.curActiveAccordionItem;
            if (activeAccordionItem > 0) {
                console.log("AA_RefreshAccordion - Attivo l'item: " + parseInt(activeAccordionItem));
                $("#" + AA_MainApp.accordionBoxParams.id).accordion("option", "active", parseInt(activeAccordionItem));
                $("#" + AA_MainApp.accordionBoxParams.id).accordion("refresh");
            }
            //-------------------------------

            $("#" + AA_MainApp.accordionBoxParams.id).accordion({
                activate: function(event, ui) {
                    if (ui.newHeader.length == 0) {
                        return;
                    }
                    AA_MainApp.accordionBoxParams.curActiveAccordionItem = ui.newHeader.attr("order");
                    console.log("Accordion->activate: curActiveAccordionItem: " + AA_MainApp.accordionBoxParams.curActiveAccordionItem);
                }
            });

            //chiama la funzione custom definita dall'utente nei parametri
            if (params && params.userFunction) {
                console.log("chiamo la funzione definita dall'utente (params).");
                params.userFunction(params, params.userFunctionParams);
            }

            //Chiama la funzione definita dall'utente
            if (AA_MainApp.accordionBoxParams.userFunction) {
                console.log("chiamo la funzione definita dall'utente (accordionParams).");
                AA_MainApp.accordionBoxParams.userFunction(params);
            }

        } else {
            //Visualizza il messaggio d'errore
            alert("errore: " + $(data).filter("#error").contents().text());
        }
    });

    lista.fail(function() {
        alert("Si è verificato un errore di rete, riprovare più tardi, se il problema persiste inviare una segnalazione alla casella: amministrazioneaperta@regione.sardegna.it");
    });
}

//Aggiorna un item dell'accordion
function AA_RefreshAccordionItemView(params) {
    //Chiama la funzione definita dall'utente
    if (AA_MainApp.accordionBoxParams.userRefreshAccordionItemViewFunction) {
        console.log("chiamo la funzione definita dall'utente (accordionParams).");
        AA_MainApp.accordionBoxParams.userRefreshAccordionItemViewFunction(params);
    }

    //Chiama la funzione di aggiornamento del modulo corrente
    if (AA_MainApp.curModule) {
        AA_MainApp.curModule.refreshUiObject(params);
    }
}

//Reimposta i parametri di ricerca ai valori di default
function AA_ResetFilterParams(toDefault = true) {
    if (toDefault) {
        $("#stato_pubblicata_search").prop("checked", false);
        $("#stato_bozza_search").prop("checked", true);
        $("#stato_cestinata_search").prop("checked", false);
        $("#stato_revisionata_search").prop("checked", false);

        $("#id_assessorato_search").val(0);
        $("#id_direzione_search").val(0);
        $("#id_servizio_search").val(0);
        $("#struttura_desc_search").html("Qualunque");
    } else {
        //current values
        if (AA_MainApp.searchBoxParams.stato_scheda_search == 1) {
            $("#stato_pubblicata_search").prop("checked", false);
            $("#stato_bozza_search").prop("checked", true);
        } else {
            $("#stato_pubblicata_search").prop("checked", true);
            $("#stato_bozza_search").prop("checked", false);
        }

        if (AA_MainApp.searchBoxParams.stato_revisionata_search == 1) {
            $("#stato_revisionata_search").prop("checked", true);
        } else $("#stato_revisionata_search").prop("checked", false);

        if (AA_MainApp.searchBoxParams.stato_cestinata_search == 1) {
            $("#stato_cestinata_search").prop("checked", true);
        } else $("#stato_cestinata_search").prop("checked", false);

        if (AA_MainApp.searchBoxParams.stato_revisionata_search == 1) {
            $("#stato_revisionata_search").prop("checked", true);
        } else $("#stato_revisionata_search").prop("checked", false);

        $("#id_assessorato_search").val(AA_MainApp.searchBoxParams.id_assessorato_search);
        $("#id_direzione_search").val(AA_MainApp.searchBoxParams.id_direzione_search);
        $("#id_servizio_search").val(AA_MainApp.searchBoxParams.id_servizio_search);
        $("#struttura_desc_search").html(AA_MainApp.searchBoxParams.struttura_desc_search);
    }

    //Chiama la funzione definita dall'utente
    if (AA_MainApp.searchBoxParams.userResetFilterFunction) AA_MainApp.searchBoxParams.userResetFilterFunction(toDefault, AA_MainApp.searchBoxParams.userResetFilterFunctionParams);
    if (typeof(AA_MainApp.curModule) !== "undefined") AA_MainApp.curModule.resetSearchParams(toDefault);
}

//Aggiorna i parametri di ricerca in base ai valori delle variabili
async function AA_UpdateFilterParams(reverse = false) {
    console.log("AA_UpdateFilterParams(" + reverse + ")");

    //prepara la query dei parametri la prima volta
    if (AA_MainApp.searchBoxParams.query == "") {
        AA_MainApp.searchBoxParams.query = "stato_scheda_search=1";
        if (stato_scheda_search == 2) {
            AA_MainApp.searchBoxParams.query = "stato_scheda_search=2";
        }
        if (stato_cestinata_search == 1) AA_MainApp.searchBoxParams.query += "&stato_cestinata_search=1";
        if (stato_revisionata_search == 1) AA_MainApp.searchBoxParams.query += "&stato_revisionata_search=1";

        if (goToPage > 0) AA_MainApp.searchBoxParams.query += "&goPage=" + AA_MainApp.searchBoxParams.goToPage;

        AA_MainApp.searchBoxParams.query += "&id_assessorato_search=" + id_assessorato_search;
        AA_MainApp.searchBoxParams.query += "&id_direzione_search=" + id_direzione_search;
        AA_MainApp.searchBoxParams.query += "&id_servizio_search=" + id_servizio_search;

        console.log("AA_UpdateFilterParams - query iniziale: " + AA_MainApp.searchBoxParams.query);
    }

    var dlg = $("div#" + AA_MainApp.searchBoxParams.id);
    if (!dlg.length) {
        console.log("AA_UpdateFilterParams() - dialogo non trovato - ne istanzio uno nuovo");
        await AA_MainApp.ui.SearchDlg.initialize();

        return;
    }

    if (!reverse) {
        //Generic parameters
        AA_MainApp.searchBoxParams.stato_scheda_search = 1;
        if ($("#stato_pubblicata_search", dlg).prop("checked") == true) {
            AA_MainApp.searchBoxParams.stato_scheda_search = 2;
        } else $("#stato_bozza_search", dlg).prop("checked", true);

        AA_MainApp.searchBoxParams.stato_cestinata_search = 0;
        if ($("#stato_cestinata_search", dlg).prop("checked") == true) {
            AA_MainApp.searchBoxParams.stato_cestinata_search = 1;
        }

        AA_MainApp.searchBoxParams.stato_revisionata_search = 0;
        if ($("#stato_revisionata_search", dlg).prop("checked") == true) {
            AA_MainApp.searchBoxParams.stato_revisionata_search = 1;
        }

        AA_MainApp.searchBoxParams.goToPage = $("#goPage", dlg).val();
        //----------------

        AA_MainApp.searchBoxParams.id_assessorato_search = $("#id_assessorato_search").val();
        AA_MainApp.searchBoxParams.id_direzione_search = $("#id_direzione_search").val();
        AA_MainApp.searchBoxParams.id_servizio_search = $("#id_servizio_search").val();
        AA_MainApp.searchBoxParams.struttura_desc_search = $("#struttura_desc_search").text();
    } else {
        //Generic parameters
        if (AA_MainApp.searchBoxParams.stato_scheda_search == 2) $("#stato_pubblicata_search", dlg).prop("checked", true);
        else $("#stato_pubblicata_search", dlg).prop("checked", false);
        if (AA_MainApp.searchBoxParams.stato_scheda_search == 1) $("#stato_bozza_search", dlg).prop("checked", true);
        else $("#stato_bozza_search", dlg).prop("checked", false);
        if (AA_MainApp.searchBoxParams.stato_scheda_search != 1 && AA_MainApp.searchBoxParams.stato_scheda_search != 2) {
            AA_MainApp.searchBoxParams.stato_scheda_search = 2;
            $("#stato_pubblicata_search", dlg).prop("checked", true);
        }
        if (AA_MainApp.searchBoxParams.stato_cestinata_search == 1) $("#stato_cestinata_search", dlg).prop("checked", true);
        else $("#stato_cestinata_search", dlg).prop("checked", false);
        if (AA_MainApp.searchBoxParams.stato_revisionata_search == 1) $("#stato_revisionata_search", dlg).prop("checked", true);
        else $("#stato_revisionata_search", dlg).prop("checked", false);

        $("#goPage", dlg).val(AA_MainApp.searchBoxParams.goToPage);

        $("#id_assessorato_search").val(AA_MainApp.searchBoxParams.id_assessorato_search);
        $("#id_direzione_search").val(AA_MainApp.searchBoxParams.id_direzione_search);
        $("#id_servizio_search").val(AA_MainApp.searchBoxParams.id_servizio_search);
        $("#struttura_desc_search").text(AA_MainApp.searchBoxParams.struttura_desc_search);
    }

    //Chiama la funzione definita dall'utente
    if (AA_MainApp.searchBoxParams.userUpdateFilterFunction) AA_MainApp.searchBoxParams.userUpdateFilterFunction(reverse, AA_MainApp.searchBoxParams.userUpdateFilterFunctionParams);
    if (typeof(AA_MainApp.curModule) !== "undefined" && AA_MainApp.curModule.isValid()) AA_MainApp.curModule.updateSearchParams(reverse);

    let formSearch = $("form#" + AA_MainApp.searchBoxParams.id + "_form", dlg);
    if (formSearch.length == 0) {
        console.error("AA_UpdateFilterParams - finestra principale di ricerca non trovata");
    } else {
        //console.log("AA_UpdateFilterParams",formSearch.serialize());
        AA_MainApp.searchBoxParams.query = formSearch.serialize();
    }

    //console.log("AA_UpdateFilterParams",AA_MainApp);

}
