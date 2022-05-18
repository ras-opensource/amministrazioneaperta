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

//Funzione di generazione moduli
function AA_Module(id = "AA_MODULE_DUMMY", name = "Modulo generico") {
    this.type = "AA_MODULE";
    this.id = id;
    this.name = name;
    this.valid = false; //flag di validità

    //Sezioni modulo
    this.sections = [];

    //sezione esempio
    //this.section["section_name"]={
    //    id: "section_id",
    //    valid: true,
    //    view_id: "view_id",
    //    others...
    //}
    this.getSection = function(section_id) {
        try {
            let fake = { id: "fake_section", name: "fake section", valid: false };
            if (!AA_MainApp.utils.isDefined(section_id)) {
                console.error(this.name + "getSection - identificativo sezione non indicato");
                return fake;
            }

            for (section of this.sections) {
                if (section.id == section_id) return section;
            }

            console.error(this.name + "getSection - sezione non trovata", section_id);
            return fake;
        } catch (msg) {
            console.error(this.name + "getSection", section_id);
            return fake;
        }
    };

    //Restituisce la sezione di dettaglio
    this.getDetailSection = function() {
            try {
                let fake = { id: "fake_section", name: "fake section", valid: false };

                for (section of this.sections) {
                    if (section.detail == true) return section;
                }

                console.error(this.name + ".getDetailSection - sezione non trovata");
                return fake;
            } catch (msg) {
                console.error(this.name + ".getDetailSection", msg);
                return fake;
            }

        }
        //Imposta la sezione corrente
    this.setCurrentSection = async function(section_id) {
        try {
            if (!AA_MainApp.utils.isDefined(section_id)) {
                console.error(this.name + "setCurrentSection - identificativo sezione non indicato");
                return false;
            }

            let newSection = null;

            for (section of this.sections) {
                if (section.id == section_id) {
                    newSection = section;
                }
            }

            if (newSection) {
                this.curSection = newSection;

                //Aggiorna l'interfaccia
                await this.refreshSectionUi(section.refresh_view, section.refresh_view);

                return true;
            }
        } catch (msg) {
            console.error(this.name + "setCurrentSection", section_id);
            return false;
        }
    };

    //Esegue un task
    this.doTask = async function(params = null) {
        try {
            if (!AA_MainApp.utils.isDefined(params)) {
                console.error(this.name + ".doTask - parametri non impostati");
                return false;
            }
            if (AA_MainApp.utils.isDefined(params.task)) {
                console.log(this.name + ".doTask", params);
            } else {
                console.error(this.name + ".doTask - task non impostato", params);
                return false;
            }
        } catch (msg) {
            console.error(this.name + ".doTask", msg, params);
            return false;
        }
    };

    //Visualizza una finestra di dialogo
    this.dlg = async function(params) {
        try {
            if (!AA_MainApp.utils.isDefined(params)) {
                console.error(this.name + ".dlg - parametri non impostati");
                return false;
            }
            if (AA_MainApp.utils.isDefined(params.task)) {
                console.log(this.name + ".dlg", params);
            } else {
                console.error(this.name + ".dlg - task non impostato", params);
                return false;
            }

            let taskManager = params.taskManager;
            if (taskManager == null) taskManager = this.taskManager;

            let result = await AA_VerboseTask(params.task, taskManager, params.params, params.postParams);
            if (result.status.value == 0) {
                //console.log(this.name + ".dlg", params, result.content.value);
                if (result.content.value) {

                    //Gestione ridimensionamento finestra
                    let btn_resize = (result.content.value.head.elements[1]);

                    if (btn_resize) {
                        result.content.value.head.elements[1].click = function() {
                            if ($$(result.content.value.id).config.fullscreen) {
                                webix.fullscreen.exit();
                                $$(wnd.config.id + '_btn_resize').define({ icon: "mdi mdi-fullscreen", tooltip: "Mostra la finestra a schermo intero" });
                                $$(wnd.config.id + '_btn_resize').refresh();
                            } else {
                                webix.fullscreen.set($$(result.content.value.id));
                                $$(wnd.config.id + '_btn_resize').define({ icon: "mdi mdi-fullscreen-exit", tooltip: "Torna alla visualizzazione normale" });
                                $$(wnd.config.id + '_btn_resize').refresh();
                            }
                        };
                    }
                    //-----------------------------------------------

                    let wnd = webix.ui(result.content.value);

                    wnd.show();

                    //Imposta la validazione del form (se presente)
                    let forms = wnd.queryView("form", "all");
                    for (form of forms) {
                        let oldValues = form.getValues();
                        if (AA_MainApp.utils.isDefined(form.config.validation)) {
                            form.config.rules = { $all: AA_MainApp.utils.getEventHandler(form.config.validation, this.id) };
                        }
                        form.reconstruct();
                        form.setValues(oldValues);
                    }

                    return true;
                }

                return false;
            } else {
                console.error(this.name + ".dlg", result.error.value);
                AA_MainApp.ui.alert(result.error.value);
                return Promise.reject(result.error.value);
            }
        } catch (msg) {
            console.error(this.name + ".dlg", msg);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };

    //Sezione corrente
    this.curSection = { id: "fake_section", name: "fake section", valid: false };

    //Variabili globali
    this.globals = [];

    //Imposta un parametro globale del modulo
    this.setGlobal = function(gName = "", gValue = null) {
        if (AA_MainApp.utils.isDefined(gName)) this.globals[name] = {
            name: gName,
            value: gValue,
            valid: true
        }
    };

    //Elimina un parametro globale
    this.unsetGlobal = function(gName = "") {
        if (AA_MainApp.utils.isDefined(gName) && this.getGlobal(gName).valid) {
            this.globals[gName].valid = false;
            this.globals[gName].value = null;
        }
    };

    //Recupera un parametro globale
    this.getGlobal = function(gName = "") {
        if (AA_MainApp.utils.isDefined(gName) && this.globals[gName]) return this.globals[gName];
        else return {
            name: gName,
            value: null,
            valid: false
        }
    };

    this.isValid = function() { return this.valid; };
    this.toString = function() { return this.name; };
    this.initializeDefault = async function() {
        try {
            var sections = await AA_VerboseTask("GetSections", this.taskManager);
            if (sections.status.value == 0) {
                this.sections = sections.content.value;
                console.log(this.name + "::initializeDefault()", this);

                //Imposta come sezione di default la prima
                this.curSection = this.sections[0];

                //Imposta la sezione di default se ha il flag
                for (section of this.sections) {
                    if (section.default) this.curSection = section;
                }
            } else {
                console.error(this.name + "::initializeDefault() - errore nel recupero delle sezioni: ", sections.error.value);
                AA_MainApp.ui.alert(sections.error.value);
                return Promise.reject(sections.error.value);
            }

            var layout = await AA_VerboseTask("GetLayout", this.taskManager);
            if (layout.status.value == 0) {
                this.ui.layout = layout.content.value;
            } else {
                console.error(this.name + "::initializeDefault() - errore nel recupero del layout: ", layout.error.value);
                AA_MainApp.ui.alert(layout.error.value);
                return Promise.reject(layout.error.value);
            }

            return true;
        } catch (msg) {
            console.error(this.name + "::initializeDefault() - errore: ", msg);
            return Promise.reject(msg);
        }
    };
    this.initialize = this.initializeDefault; //funzione di inizializzazione

    this.refreshSectionUiDefault = async function(refreshView = false, bResetView = false) {
        try {
            //vista sezione corrente
            activeView = $$(this.curSection.view_id);

            //Aggiorna il titolo del modulo
            AA_MainApp.ui.MainUI.setModuleHeaderContent({ icon: this.ui.icon, title: this.ui.name });

            //Aggiorna il titolo della sezione
            AA_MainApp.ui.MainUI.setModuleSectionHeaderContent({ title: this.curSection.name });


            if (activeView) {
                activeView.show();
                await this.refreshUiObject(this.curSection.view_id, refreshView, bResetView);
            }

            //aggiorno la navbar
            await AA_MainApp.ui.navbar.refresh();

            //Aggiorno il contenuto del menu contestuale
            await this.refreshActionMenuContent();

            return 1;
        } catch (msg) {
            console.error(this.name + "::refreshSectionUiDefault()", msg);
            return Promise.reject(msg);
        }
    };
    this.refreshSectionUi = this.refreshSectionUiDefault; //funzione di refresh dell'interfaccia principale

    this.refreshUiObjectDefault = async function(idObj, bRefreshContent = false, bResetView = false) {
        try {
            //console.log("Home::AA_HomeRefreshUiObject("+idObj+")");
            let obj = $$(idObj);
            if (obj) {
                //console.log(this.name+"::refreshUiObjectDefault("+idObj+")", obj);

                //Se la sezione è paginata salva la pagina corrente
                let curPage = 0;
                let params = "";

                if (obj.config.paged == true) {
                    curPage = this.getRuntimeValue(obj.config.pager_id, "curPage");
                    params = [{ "page": curPage }];
                }

                //Verifica se ci sono oggetti con dei tab e ne salva lo stato
                if (!bResetView) {
                    let tabbarObjs = obj.queryView({ view: "tabbar" }, "all");
                    if (Array.isArray(tabbarObjs) && tabbarObjs.length > 0) {
                        for (item of tabbarObjs) {
                            let status = item.getValue();
                            if (AA_MainApp.utils.isDefined(status)) {
                                console.log(this.name + "::refreshUiObjectDefault -saved status (" + item.config.id + "): ", status);
                                module.setRuntimeValue("tabBarStatus", item.config.id, status);
                            }
                        }
                    }

                    let accordionObjs = obj.queryView({ view: "accordionitem", collapsed: false }, "all");
                    if (Array.isArray(accordionObjs) && accordionObjs.length > 0) {
                        for (item of accordionObjs) {
                            let status = 1;
                            console.log(this.name + "::refreshUiObjectDefault -saved status (" + item.config.id + "): ", status);
                            module.setRuntimeValue("accordionItemStatus", item.config.id, status);
                        }
                    }
                }

                if (bRefreshContent || !$$(idObj).config.initialized); {
                    //aggiorna il contenuto dell'oggetto
                    let postParams = "";
                    if (obj.config.filtered == true) {
                        let filter_id = obj.config.filter_id;
                        if (!AA_MainApp.utils.isDefined(filter_id)) filter_id = module.getActiveView();
                        postParams = this.getRuntimeValue(filter_id, "filter_data");
                        //console.log(this.name+"::refreshUiObjectDefault("+idObj+")", filter_id,postParams);
                    }

                    //Disabilita la schermata
                    $$(idObj).disable();

                    //console.log(this.name+"::refreshUiObjectDefault("+idObj+")",postParams);
                    let result = await this.refreshObjectContent(idObj, params, postParams);
                    if (result != 1) {
                        console.error(this.name + "::refreshUiObjectDefault(" + idObj + ") - errore: ", result);
                        return Promise.reject(result);
                    }
                }

                //Aggiorna il componente grafico
                let newObj = this.content[idObj];
                if (newObj) {
                    console.log(this.name + "::refreshUiObjectDefault(" + idObj + ") - Aggiorno l'interfaccia.");
                    //webix.ui(newObj,$$(this.ui.module_content_id),obj);
                    webix.ui(newObj, obj.getParentView(), obj);

                    obj = $$(newObj.id);
                    if (obj) {
                        if (obj.config.view == "layout") {
                            console.log(this.name + "::refreshUiObjectDefault(" + idObj + ") - ricostruisco il layout.")
                            obj.reconstruct();
                            /*
                            let tables=obj.queryView({view: "datatable"},"all");
                            if(tables)
                            {
                                for(table of tables)
                                {
                                    console.log(this.name+"::refreshUiObjectDefault("+idObj+") - ricostruisco la tabella.", table);
                                    
                                    table.adjust();
                                }
                            }*/
                        }

                        //Aggiorna il titolo della sezione.
                        if (this.getActiveView() == newObj.id) AA_MainApp.ui.MainUI.setModuleSectionHeaderContent({ title: newObj.name });

                        //Se la sezione è paginata visualizza la pagina visualizzata precedentemente
                        if (obj.config.paged == true) {
                            let pager = $$(obj.config.pager_id);
                            if (pager && curPage > 0 && curPage < pager.data.count) {
                                pager.select(curPage - 1);
                                if (pager.config.title_id) {
                                    let pager_title = $$(pager.config.title_id);
                                    if (pager_title) {
                                        pager_title.define("data", { "curPage": parseInt(pager.data.page) + 1, "totPages": pager.data.limit });
                                    }
                                }
                            }
                        }

                        //Ripristine lo stato dei tabs se ci sono
                        let tabbarObjs = obj.queryView({ view: "tabbar" }, "all");
                        if (Array.isArray(tabbarObjs) && tabbarObjs.length > 0) {
                            for (item of tabbarObjs) {
                                let status = module.getRuntimeValue("tabBarStatus", item.config.id);
                                if (AA_MainApp.utils.isDefined(status) && $$(status)) {
                                    let view = $$(item.config.view_id);
                                    if (view) {
                                        let animate = view.config.animate;
                                        view.define('animate', false);
                                        item.setValue(status);
                                        view.config.animate = animate;
                                        module.unsetRuntimeValue("tabBarStatus", item.config.id);
                                        console.log(this.name + "::refreshUiObjectDefault - ripristino lo status del tab (" + item.config.view_id + ")", status);
                                    }
                                }
                            }
                        }

                        //Ripristine lo stato degli accordion se ci sono
                        let accordionObjs = obj.queryView({ view: "accordionitem" }, "all");
                        if (Array.isArray(accordionObjs) && accordionObjs.length > 0) {
                            for (item of accordionObjs) {
                                let status = module.getRuntimeValue("accordionItemStatus", item.config.id);
                                if (AA_MainApp.utils.isDefined(status)) {
                                    let view = $$(item.config.id);
                                    if (view) {
                                        item.expand();
                                        module.unsetRuntimeValue("accordionItemStatus", item.config.id);
                                        console.log(this.name + "::refreshUiObjectDefault - ripristino lo status dell'accordion item (" + item.config.id + ")", status);
                                    }
                                }
                            }
                        }

                        obj.define("initialized", true);

                        return true;
                    }
                } else {
                    console.error(this.name + "::refreshUiObjectDefault(" + idObj + ") - Oggetto non valido: ", obj);
                    return false;
                }
            }
            return true;
        } catch (msg) {
            console.error(this.name + "::refreshUiObjectDefault(" + idObj + ") - " + msg);
            return Promise.reject(msg);
        }
    };
    this.refreshUiObject = this.refreshUiObjectDefault; //funzione di refresh di un oggetto dell'interfaccia

    this.refreshCurSection = async function() {
        console.log("refreshCurSection", this, arguments);
        await this.refreshSectionUi(true);
    };

    this.getActiveView = function() {
        return this.curSection.view_id;
    };

    this.updateSearchParams = function(params) {}; //Funzione per l'aggiornamento dei parametri di ricerca
    this.resetSearchParams = function(params) {}; //Funzione per il reset dei parametri di ricerca
    this.taskManager = "utils/system_ops.php"; //URL del task manager
    this.content = {}; //Contenuto del modulo
    this.contentType = "json"; //tipo di contenuto "json","xml","text"
    this.refreshSectionContentDefault = async function(section_id = "", params = "", postParams = "") {
        try {
            if (!Array.isArray(params)) params = [];
            params.push({ "section": this.curSection.id });

            var module_content = await AA_VerboseTask("GetSectionContent", this.taskManager, params, postParams);

            if (module_content.status.value == "0") {
                if (module_content.error.value) AA_MainApp.ui.message(module_content.error.value);

                //console.log("Nuovo contenuto del modulo (scaricato): ", home_module_content.content.value);

                if (module_content.content.value) {
                    if (Array.isArray(module_content.content.value)) {
                        for (const curItem of module_content.content.value) {
                            this.content[curItem.id] = curItem.content;
                        }
                    } else this.content[module_content.content.value.id] = module_content.content.value.content;

                    this.contentType = module_content.content.type;

                    console.log(this.name + "::refreshSectionContentDefault(" + section_id + ") - Nuovo contenuto della sezione: ", module_content.content.value);
                    return 1;
                } else return Promise.reject("Contenuto della sezione non trovato.");
            } else {
                console.error(this.name + "::refreshObjectContentDefault(" + section_id + ") errore: " + module_content.error.value);
                return Promise.reject(module_content.error.value);
            }
        } catch (msg) {
            console.error("Home::AA_HomeRefreshContent()", msg);
            return Promise.reject(msg);
        }
    };
    this.refreshSectionContent = this.refreshSectionContentDefault;

    this.refreshObjectContentDefault = async function(object_id = "", params = "", postParams = "") {
        try {
            if (!Array.isArray(params)) params = [];
            params.push({ "section": this.curSection.id });
            if (AA_MainApp.utils.isDefined(object_id)) params.push({ "object": object_id })
            else params.push({ "object": this.curSection.view_id });

            let view = $$(object_id);

            //Disabilita il componente da aggiornare
            if (view) view.disable();

            var module_content = await AA_VerboseTask("GetObjectContent", this.taskManager, params, postParams);

            if (module_content.status.value == "0") {
                if (module_content.error.value) AA_MainApp.ui.message(module_content.error.value);

                //console.log("Nuovo contenuto del modulo (scaricato): ", module_content.content.value);

                if (module_content.content.value) {
                    if (Array.isArray(module_content.content.value)) {
                        for (const curItem of module_content.content.value) {
                            this.content[curItem.id] = curItem.content;
                        }
                    } else this.content[module_content.content.value.id] = module_content.content.value.content;

                    this.contentType = module_content.content.type;

                    if (AA_MainApp.debug) console.log(this.name + "::refreshObjectContentDefault(" + object_id + ") - Nuovo contenuto dell'oggetto: ", this.content);

                    //riabilita il componente
                    if (view) view.enable();

                    return 1;
                } else {
                    //riabilita il componente
                    if (view) view.enable();
                    return Promise.reject("Contenuto del modulo non trovato.");
                }
            } else {
                //riabilita il componente
                if (view) view.enable();
                console.error(this.name + "::refreshObjectContentDefault(" + object_id + ") errore: " + module_content.error.value);
                return Promise.reject(module_content.error.value);
            }
        } catch (msg) {
            //riabilita il componente
            if (view) view.enable();
            console.error(this.name + "::refreshObjectContentDefault()", msg);
            return Promise.reject(msg);
        }
    };
    this.refreshObjectContent = this.refreshObjectContentDefault;

    //Rinfresca i dati di un componente
    this.refreshObjectDataDefault = async function(idObj, params = "") {
        try {
            //console.log(this.name+"::refreshObjectDataDefault("+idObj+")");
            let obj = $$(idObj);
            if (obj) {
                //console.log(this.name+"::refreshObjectDataDefault("+idObj+")", obj);
                //Se la sezione è paginata salva la pagina corrente
                let curPage = 0;
                if (!AA_MainApp.utils.isDefined(params)) {
                    params = [];
                }

                params.push({ "object": idObj });

                if (obj.config.paged == true) {
                    curPage = this.getRuntimeValue(obj.config.pager_id, "curPage");
                    params.push({ "page": curPage });
                }

                let postParams = "";
                if (obj.config.filtered == true) {
                    let filter_id = obj.config.filter_id;
                    if (!AA_MainApp.utils.isDefined(filter_id)) filter_id = module.getActiveView();
                    postParams = this.getRuntimeValue(filter_id, "filter_data");
                    //console.log(this.name+"::refreshObjectDataDefault("+idObj+")", filter_id,postParams);
                }

                //console.log(this.name+"::refreshObjectDataDefault("+idObj+")",postParams);
                //disabilita l'oggetto
                obj.disable();

                let result = await AA_VerboseTask("GetObjectData", this.taskManager, params, postParams);
                if (result.status.value != 0) {
                    obj.enable();
                    console.error(this.name + "::refreshObjectDataDefault(" + idObj + ") - errore: ", result.error.value);
                    AA_MainApp.ui.alert(result.error.value)
                    return Promise.reject(result.error.value);
                }

                //Aggiorna i dati del componente
                if (Array.isArray(result.content.value) && result.content.value.length > 0) {
                    if (AA_MainApp.debug) console.log(this.name + "::refreshObjectDataDefault(" + idObj + ") - nuovi dati: ", result.content.value, params, postParams);
                    obj.clearAll();
                    obj.parse(result.content.value);
                    if (obj.config.select == true || obj.config.multiselect) {
                        obj.callEvent("onSelectChange");
                    }

                    //Riabilita l'oggetto
                    obj.enable();

                    return true;
                }

                console.error(this.name + "::refreshObjectDataDefault(" + idObj + ") - errore: array vuoto.");
                return false;
            }

            console.error(this.name + "::refreshObjectDataDefault(" + idObj + ") - errore: oggetto non valido.");
            return false;
        } catch (msg) {
            if (obj) obj.enable();
            console.error(this.name + "::refreshObjectDataDefault(" + idObj + ") - " + msg);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };
    this.refreshObjectData = this.refreshObjectDataDefault;

    this.menuEventHandlerDefault = async function() {
        try {
            //console.log(this.name+"::menuEventHandlerDefault",arguments,AA_MainApp.ui.MainUI.activeMenu);
            if (AA_MainApp.ui.MainUI.activeMenu) {
                item = AA_MainApp.ui.MainUI.activeMenu.getItem(arguments[0]);
                //console.log(this.name+"::menuEventHandlerDefault",this,typeof AA_MainApp.curModule[item.handler]);

                //Cerca nel modulo
                if (item.module_id) {
                    module = AA_MainApp.getModule(item.module_id);
                    if (module.isValid() && typeof module[item.handler] == "function") {
                        if (Array.isArray(item.handler_params)) module[item.handler](...item.handler_params);
                        else module[item.handler]();
                    }
                } else {
                    if (typeof AA_MainApp.curModule[item.handler] == "function") {
                        if (Array.isArray(item.handler_params)) AA_MainApp.curModule[item.handler](...item.handler_params);
                        else AA_MainApp.curModule[item.handler]();
                    }
                }

                //Cerca nelle funzioni globali
                if (typeof window[item.handler] == "function") {
                    if (Array.isArray(item.handler_params)) window[item.handler](...item.handler_params);
                    else window[item.handler]();
                }
            }
            return true;
        } catch (msg) {
            console.error(AA_MainApp.curModule.name + "::menuEventHandlerDefault()", arguments);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };
    this.menuEventHandler = this.menuEventHandlerDefault;

    this.refreshActionMenuContentDefault = async function() {
        try {
            //console.log("Home::AA_HomeRefreshActionMenuContent()", arguments);
            let result = await AA_VerboseTask("GetActionMenu", this.taskManager, [{ section: this.getActiveView() }]);

            //console.log("Home::AA_HomeRefreshActionMenuContent()",result);

            if (result.status.value == 0) {
                //console.log("refreshActionMenuContentDefault",this);
                result.content.value.on = { "onItemClick": this.menuEventHandler };
                //console.log("refreshActionMenuContentDefault",result.content.value);
                this.ui.activeMenuContent = result.content.value;
                return true;
            } else {
                console.error(this.name + "::refreshActionMenuContentDefault() - errore: ", result.error.value);
                return Promise.reject(result.error.value);
            }
        } catch (msg) {
            console.error(this.name + "::refreshActionMenuContentDefault", arguments);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };
    this.refreshActionMenuContent = this.refreshActionMenuContentDefault;

    //Gestione degli eventi sul pager
    this.pagerEventHandlerDefault = async function() {
        try {
            //console.log("::pagerEventHandlerDefault",arguments);

            if (arguments[2] && arguments[2].parentElement) {
                let pager_id = arguments[2].parentElement.attributes["pager"].value;
                //console.log("AA_SinesPagerEventHandler", pager_id);
                let pager = $$(pager_id);
                //console.log("AA_SinesPagerEventHandler", pager);
                if (pager) {
                    let module = AA_MainApp.getModule(pager.config.module_id);
                    if (!module.isValid()) module = AA_MainApp.curModule;

                    console.log(module.name + "::pagerEventHandlerDefault", arguments);

                    let pager_title = $$(pager.config.title_id);
                    if (pager_title) {
                        let curPage = Number(arguments[0]) + 1;
                        if (arguments[0] == "prev") curPage = pager.data.page;
                        if (arguments[0] == "next") curPage = Number(pager.data.page) + 2;
                        if (arguments[0] == "first") curPage = 1;
                        if (arguments[0] == "last") curPage = pager.data.limit;

                        //Salva la pagina nel registro
                        module.setRuntimeValue(pager.config.id, "curPage", curPage);

                        pager_title.define("data", { "curPage": curPage, "totPages": pager.data.limit });

                        //Aggiorna il componente associato
                        let target = $$(pager.config.target);

                        if (target) {
                            let targetAction = pager.config.targetAction;
                            if (targetAction == "refreshData" || !AA_MainApp.utils.isDefined(targetAction)) {
                                console.log(this.name + "::pagerEventHandlerDefault() - aggiorno i dati del componente: " + pager.config.target, targetAction);
                                module.refreshObjectData(pager.config.target);
                            }
                        }
                    } else {
                        console.error(module.name + "::pagerEventHandlerDefault() - pager_title non trovato", arguments, pager);
                        return false;
                    }
                } else {
                    console.error(AA_MainApp.curModule.name + "::pagerEventHandlerDefault() - pager non trovato", arguments);
                    return false;
                }
            } else {
                console.error(AA_MainApp.curModule.name + "::pagerEventHandlerDefault() - elemento non trovato", arguments);
                return false;
            }
        } catch (msg) {
            console.error(AA_MainApp.curModule.name + "::pagerEventHandlerDefault()", msg);
            return Promise.reject(msg);
        }
    };
    this.pagerEventHandler = this.pagerEventHandlerDefault;

    //Gestione valori runtime
    this.runtimeValues = [];
    this.setRuntimeValue = function(id = "", key = "", value = "") {
        try {
            if (id == "") id = "common";
            if (key == "") key = "value";
            if (!this.runtimeValues[id]) this.runtimeValues[id] = {};
            this.runtimeValues[id][key] = value;
            //console.log(this.name+"::setRuntimeValue", id,key,value, this.runtimeValues);

            return true;
        } catch (msg) {
            console.error(this.name + "::setRuntimeValue", id, key, value);
            return false;
        }
    };

    this.unsetRuntimeValue = function(id = "", key = "") {
        try {
            if (id == "") id = "common";
            if (key == "") key = "value";
            if (this.runtimeValues[id]) {
                if (AA_MainApp.utils.isDefined(this.runtimeValues[id][key])) {
                    delete(this.runtimeValues[id][key]);
                    //console.log(this.name+"::unsetRuntimeValue", id,key, this.runtimeValues);
                }
            }

            return true;
        } catch (msg) {
            console.error(this.name + "::unsetRuntimeValue", id, key);
            return false;
        }
    };

    this.getRuntimeValue = function(id = "", key = "") {
        try {
            //console.log(this.name+"::getRuntimeValue", id,key);
            if (id == "") id = "common";
            if (key == "") key = "value";
            if (this.runtimeValues[id]) return this.runtimeValues[id][key];

            return "";
        } catch (msg) {
            console.error(this.name + "::getRuntimeValue", id, key);
            return "";
        }
    };

    //Event handlers
    this.eventHandlers = [];
    this.eventHandlers['defaultHandlers'] = [];

    //DefaultFormSaveHandler
    this.eventHandlers['defaultHandlers'].saveData = async function(params) {
        try {
            let module = this;

            let postFunction = async function(params) {
                if (AA_MainApp.utils.isDefined(params.task)) {
                    let result = await AA_VerboseTask(params.task, this.taskManager, params.taskParams, params.data);
                    if (result.status.value == 0) {
                        AA_MainApp.ui.message(result.content.value);
                        if (AA_MainApp.utils.isDefined(params.wnd_id)) $$(params.wnd_id).close();
                        if (AA_MainApp.utils.isDefined(params.refresh)) {
                            if (AA_MainApp.utils.isDefined(params.refresh_obj_id)) this.refreshUiObject(params.refresh_obj_id, true);
                            else this.refreshCurSection();
                        }

                        //Verifica se ci sono ulteriori azioni da intraprendere
                        if (AA_MainApp.utils.isDefined(result.status.action)) {
                            console.log(this.name + "eventHandlers.defaultHandlers.saveData", result.status);
                            AA_MainApp.utils.callHandler(result.status.action, JSON.parse(result.status.action_params), this.id);
                        }
                        return true;
                    } else {
                        if (result.error.type != "json") {
                            AA_MainApp.ui.alert(result.error.value);
                            return Promise.reject(result.error.value);
                        } else {
                            webix.ui(result.error.value).show();
                            return false;
                        }
                    }
                }
            };

            //console.log(this.name+"eventHandlers.defaultHandlers.saveData", params);
            if (AA_MainApp.utils.isDefined(params.fileUploader_id)) {
                let fileUploader = $$(params.fileUploader_id);
                if (fileUploader && fileUploader.files.data.order.length > 0) {
                    //console.log("eventHandlers.defaultHandlers.saveData - uploader",fileUploader);
                    fileUploader.define("upload", AA_MainApp.taskManager + "?task=UploadSessionFile");
                    let prova = fileUploader.send(function() {
                        //getting file properties
                        fileUploader.files.data.each(function(obj) {
                            if (obj.status == "error") {
                                throw (obj.value);
                            }

                            if (obj.status == "server") {
                                console.log(this.name + ".eventHandlers.defaultHandlers.saveData - file: " + obj.name + " caricato correttamente.");
                                return postFunction.call(module, params);
                            }
                        });
                    });
                } else {
                    return await postFunction.call(module, params);
                }
            } else {
                return await postFunction.call(module, params);
            }
        } catch (msg) {
            console.error(this.name + ".eventHandlers.defaultHandlers.saveData", msg);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };

    //DefaultListSelectionHandler
    this.eventHandlers['defaultHandlers'].onSelectChange = function() {
        try {
            let sel = this.getSelectedItem(true);

            //Salva gli elementi selezionati
            let module = AA_MainApp.getModule(this.config.module_id);
            if (!module.isValid()) module = AA_MainApp.curModule;

            let selection = [];
            for (item of sel) {
                selection.push(parseInt(item.id));
            }

            module.setRuntimeValue(this.config.id, "itemSelected", selection);

            //Toolbar associata alla lista o dataview
            let toolbar = $$(this.config.toolbar_id);

            //console.log(this.name+".defaultHandlers.onSelectChange",toolbar, sel);

            //Abilitazione pulsanti toolbar in caso di elementi selezionati
            if (sel.length > 0) {
                if (toolbar) {
                    //console.log("AA_Sines_Pubblicate_SelectionChange",toolbar.getChildViews(), toolbar.elements);
                    for (element of toolbar.getChildViews()) {
                        if (element.config.enableOnItemSelected == true && !element.isEnabled() && element.isVisible()) {
                            element.enable();
                        }
                    }
                }
            } else {
                if (toolbar) {
                    //console.log("AA_Sines_Pubblicate_SelectionChange",toolbar.getChildViews(), toolbar.elements);
                    for (element of toolbar.getChildViews()) {
                        if (element.config.enableOnItemSelected == true && element.isEnabled() && element.isVisible()) {
                            element.disable();
                        }
                    }
                }
            }
            //-------------------------------------------
        } catch (msg) {
            console.error(this.name + "eventHandlers.defaultHandlers.onSelectChange", msg, this);
        }
    };

    //DefaultItemMenuHandler
    this.eventHandlers['defaultHandlers'].onMenuItemClick = function() {
        try {
            let item = this.getItem(arguments[0]);
            if (item) {
                //console.log(module.name+".eventHandlers.defaultHandlers.onMenuItemClick",this,item);
                if (!AA_MainApp.utils.isDefined(item.handler_params)) params = {};
                else params = item.handler_params;
                params.menuItem = { menuItem: item };
                AA_MainApp.utils.callHandler(item.handler, params, item.module_id);
            }
        } catch (msg) {
            console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.onMenuItemClick", msg, this);
        }
    };

    //DefaultDetailItemMenuHandler
    this.eventHandlers['defaultHandlers'].onDetailMenuItemClick = function() {
        try {
            let item = this.getItem(arguments[0]);
            if (item) {
                //console.log(module.name+".eventHandlers.defaultHandlers.onMenuItemClick",this,item);
                if (!AA_MainApp.utils.isDefined(item.handler_params)) params = {};
                else params = item.handler_params;
                params.menuItem = { menuItem: item };
                AA_MainApp.utils.callHandler(item.handler, params, item.module_id);
            }
        } catch (msg) {
            console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.onDetailMenuItemClick", msg, this);
        }
    };

    //DefaultPdfPreview
    this.eventHandlers['defaultHandlers'].pdfPreview = async function(params = null) {
        try {

            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.url)) {
                let result = await AA_VerboseTask("GetPdfPreviewDlg", AA_MainApp.taskManager);
                if (result.status.value == 0) {

                    //pulsante di ridimensionamento
                    let btn_resize = (result.content.value.head.elements[1]);

                    if (btn_resize) {
                        result.content.value.head.elements[1].click = function() {
                            if ($$(result.content.value.id).config.fullscreen) {
                                webix.fullscreen.exit();
                                $$(wnd.config.id + '_btn_resize').define({ icon: "mdi mdi-fullscreen", tooltip: "Mostra la finestra a schermo intero" });
                                $$(wnd.config.id + '_btn_resize').refresh();
                            } else {
                                webix.fullscreen.set($$(result.content.value.id));
                                $$(wnd.config.id + '_btn_resize').define({ icon: "mdi mdi-fullscreen-exit", tooltip: "Torna alla visualizzazione normale" });
                                $$(wnd.config.id + '_btn_resize').refresh();
                            }
                        };
                    }

                    let wnd = webix.ui(result.content.value);

                    wnd.show();

                    var options = {
                        pdfOpenParams: {
                            pagemode: "none",
                            navpanes: 0,
                            toolbar: 1,
                            view: "FitV"
                        }
                    };

                    var pdf = PDFObject.embed(params.url, "#pdf_preview_box", options);

                    if (pdf) console.log("PDFObject successfully added");
                } else {
                    console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.pdfPreview", result.error.value, this);
                }
            }
        } catch (msg) {
            console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.pdfPreview", msg, this);
        }
    };

    //DefaultWndOpen
    this.eventHandlers['defaultHandlers'].wndOpen = async function(params = null) {
        try {
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.url)) {
                let wnd = window.open(params.url);
            }

            return true;
        } catch (msg) {
            console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.pdfPreview", msg, this);
        }
    };

    //default form validation
    this.eventHandlers['defaultHandlers'].validateForm = function() {
        try {
            //console.log(AA_MainApp.curModule.name+"eventHandlers.defaultHandlers.validateForm",this, arguments);

            let val = true;

            //Verifica se il campo ha l'attributo "validateForm" impostato
            if (AA_MainApp.utils.isDefined(arguments[2]) && AA_MainApp.utils.isDefined(this.elements) && AA_MainApp.utils.isDefined(this.elements[arguments[2]]) && AA_MainApp.utils.isDefined(this.elements[arguments[2]].config)) {
                let valFunc = this.elements[arguments[2]].config.validateFunction;

                //console.log(AA_MainApp.curModule.name+"eventHandlers.defaultHandlers.validateForm", valFunc, this, arguments);

                if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.invalidMessage) && this.elements[arguments[2]].config.required) {
                    let invalidMessage = "*Il dato è obbligatorio";
                    this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                }

                if (AA_MainApp.utils.isDefined(valFunc)) {
                    if (valFunc == "IsPositive") {
                        if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.customInvalidMessage)) {
                            let invalidMessage = "*Inserire esclusivamente numeri positivi";
                            if (!this.elements[arguments[2]].config.required) invalidMessage += " o lasciare vuoto";
                            this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                        } else {
                            this.elements[arguments[2]].config.invalidMessage = this.elements[arguments[2]].config.customInvalidMessage;
                        }

                        if (arguments[0] != "" || this.elements[arguments[2]].config.required) {
                            let found = /^[\+]?[0-9]+(\.[0-9]{3})*(\,[0-9]{2})?$/.test(arguments[0]);
                            if (!found) {
                                val = false;
                            }
                        }
                    }

                    if (valFunc == "IsSelected") {
                        if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.customInvalidMessage)) {
                            let invalidMessage = "*Occorre selezionare una voce dalla lista";
                            if (!this.elements[arguments[2]].config.required) invalidMessage += " o lasciare vuoto";
                            this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                        } else {
                            this.elements[arguments[2]].config.invalidMessage = this.elements[arguments[2]].config.customInvalidMessage;
                        }

                        if (arguments[0] != "" || this.elements[arguments[2]].config.required) {
                            let found = /^[\+]?[0-9]+(\.[0-9]{3})*(\,[0-9]{2})?$/.test(arguments[0]);
                            if (arguments[0] <= 0) {
                                val = false;
                            }
                        }
                    }

                    if (valFunc == "IsNumber") {
                        if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.customInvalidMessage)) {
                            let invalidMessage = "*Inserire esclusivamente numeri";
                            if (!this.elements[arguments[2]].config.required) invalidMessage += " o lasciare vuoto";
                            this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                        } else {
                            this.elements[arguments[2]].config.invalidMessage = this.elements[arguments[2]].config.customInvalidMessage;
                        }

                        if (arguments[0] != "" || this.elements[arguments[2]].config.required) {
                            let found = /^[\-\+]?[0-9]+(\.[0-9]{3})*(\,[0-9]{2})?$/.test(arguments[0]);
                            if (!found) {
                                val = false;
                            }
                        }
                        //console.log(AA_MainApp.curModule.name+"eventHandlers.defaultHandlers.validateForm - value:", value, valFunc, val);
                    }

                    if (valFunc == "IsUrl") {
                        if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.customInvalidMessage)) {
                            let invalidMessage = "*Inserire esclusivamente URL sicure, es. https://www.regione.sardegna.it";
                            if (!this.elements[arguments[2]].config.required) invalidMessage += " o lasciare vuoto";
                            this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                        } else {
                            this.elements[arguments[2]].config.invalidMessage = this.elements[arguments[2]].config.customInvalidMessage;
                        }

                        if (arguments[0] != "" || this.elements[arguments[2]].config.required) {
                            let found = /^https:\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])$/.test(arguments[0]);
                            if (!found) {
                                val = false;
                            }
                        }
                        //console.log(AA_MainApp.curModule.name+"eventHandlers.defaultHandlers.validateForm - value:", value, valFunc, val);
                    }

                    if (valFunc == "IsFile") {
                        if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.customInvalidMessage)) {
                            let invalidMessage = "*Occorre selezionare un file";
                            if (!this.elements[arguments[2]].config.required) invalidMessage += " o lasciare vuoto";
                            this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                        } else {
                            this.elements[arguments[2]].config.invalidMessage = this.elements[arguments[2]].config.customInvalidMessage;
                        }

                        //console.log(AA_MainApp.curModule.name+"eventHandlers.defaultHandlers.validateForm",this, arguments);

                        let fileField = this.queryView({ "view": "uploader" });
                        if (fileField) {
                            let order = fileField.files.data.order;
                            if (order.length == 0 && fileField.config.required) {
                                val = false;
                                let layout = $$(fileField.config.layout_id);
                                if (layout) {
                                    layout.$view.style.backgroundColor = "#ffe6e6";
                                }
                            } else {
                                let layout = $$(fileField.config.layout_id);
                                if (layout) {
                                    layout.$view.style.backgroundColor = "transparent";
                                }
                            }
                            //console.log(AA_MainApp.curModule.name+"eventHandlers.defaultHandlers.validateForm - value:", order);
                        }
                    }

                    if (valFunc == "IsInteger") {
                        if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.customInvalidMessage)) {
                            let invalidMessage = "*Inserire esclusivamente numeri interi";
                            if (!this.elements[arguments[2]].config.required) invalidMessage += " o lasciare vuoto";
                            this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                        } else {
                            this.elements[arguments[2]].config.invalidMessage = this.elements[arguments[2]].config.customInvalidMessage;
                        }

                        if (arguments[0] != "" || this.elements[arguments[2]].config.required) {
                            let found = /^[\-\+]?[0-9]+(\.[0-9]{3})*$/.test(arguments[0] || "");
                            if (!found) {
                                val = false;
                            }
                        }
                        //console.log(AA_MainApp.curModule.name+"eventHandlers.defaultHandlers.validateForm - value:", arguments[0], valFunc, val);
                    }

                    if (valFunc == "IsMail") {
                        if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.customInvalidMessage)) {
                            let invalidMessage = "*Inserire un indirizzo email valido";
                            if (!this.elements[arguments[2]].config.required) invalidMessage += " o lasciare vuoto";
                            this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                        } else {
                            this.elements[arguments[2]].config.invalidMessage = this.elements[arguments[2]].config.customInvalidMessage;
                        }

                        let found = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(arguments[0] || "");
                        if (!found) {
                            val = false;
                        }
                        //console.log(AA_MainApp.curModule.name+"eventHandlers.defaultHandlers.validateForm - value:", arguments[0], valFunc, val);
                    }

                    if (valFunc == "IsIsoDate") {
                        if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.customInvalidMessage)) {
                            let invalidMessage = "*Inserire una data nel formato: yyyy-mm-gg";
                            if (!this.elements[arguments[2]].config.required) invalidMessage += " o lasciare vuoto";
                            this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                        } else {
                            this.elements[arguments[2]].config.invalidMessage = this.elements[arguments[2]].config.customInvalidMessage;
                        }

                        val = true;
                        if (this.elements[arguments[2]].config.required && arguments[0] == "") val = false;
                        if (arguments[0] != "") {
                            if (arguments[0].match(/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/)) val = false; // Invalid format
                            var d = new Date(arguments[0]);
                            var dNum = d.getTime();
                            if (!dNum && dNum !== 0) val = false;
                        }
                    }
                }
            }

            return val;
        } catch (msg) {
            console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.validateForm", msg, this, arguments);
        }
    };

    //Default GoBack
    this.eventHandlers['defaultHandlers'].goBack = async function() {
        try {
            let backSection = this.getRuntimeValue("goBack", "section");
            if (AA_MainApp.utils.isDefined(backSection)) {
                //console.log(module.name+".eventHandlers.defaultHandlers.onMenuItemClick",this,item);
                await this.setCurrentSection(backSection);

                //Seleziona gli elementi selezionati in precedenza
                let view = $$(this.curSection.view_id);
                if (view) {
                    let list = $$(view.config.list_view_id);
                    if (list) {
                        let sel = this.getRuntimeValue(list.config.id, "itemSelected");
                        if (Array.isArray(sel) && sel.length > 0) {
                            //Seleziono l'oggetto selezionato in precedenza
                            let scroll = this.getRuntimeValue("goBack", "scroll");
                            if (scroll.y > 0) {
                                list.scrollTo(0, scroll.y);
                            }
                            list.select(sel);
                        }
                    }

                    this.setRuntimeValue("goBack", "section", "");
                }
            }
        } catch (msg) {
            console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.onMenuItemClick", msg, this);
            return Promise.reject(msg);
        }
    };

    //DefaultShowDetailView
    this.eventHandlers['defaultHandlers'].showDetailView = async function(item = null) {
        try {

            //Double click event
            if (typeof item == "string") {
                //console.log("showDetailView - doubleclick", arguments,this);
                item = $$(this.config.id).getItem(item);
            }

            //detail button event
            if (Array.isArray(item)) item = item[0];
            if (AA_MainApp.utils.isDefined(item) && AA_MainApp.utils.isDefined(item.id)) {
                if (arguments.length > 0) {
                    //di default 'this' è il modulo indicato dalla funzione "callHandler"
                    module = this;
                    if (module.type != "AA_MODULE") {
                        if (item.module_id) module = AA_MainApp.getModule(item.module_id);
                        else module = AA_MainApp.curModule;
                    }
                }

                if (!module.isValid()) {
                    console.error("module.eventHandlers.defaultHandlers.showDetailView - modulo non valido", module, arguments);
                    return Promise.reject("Modulo non valido");
                }

                //Imposta la sezione
                let detailSection = module.getDetailSection();
                if (!detailSection.valid) {
                    console.error("module.eventHandlers.defaultHandlers.showDetailView - sezione non valida", module, arguments, detailSection);
                    return Promise.reject("sezione non valida");
                }

                //salva la posizione di scroll della lista
                let view = $$(module.curSection.view_id);
                if (view) {
                    let lista = $$(view.config.list_view_id);
                    if (lista) {
                        module.setRuntimeValue("goBack", "scroll", lista.getScrollState());
                    }
                }

                //Imposta il filtro per l'oggetto
                module.setRuntimeValue(detailSection.view_id, "filter_data", item);

                //Imposta la sezione attuale come target per il goBack
                module.setRuntimeValue("goBack", "section", module.curSection.id);

                //console.log(module.name+".eventHandlers.defaultHandlers.showDetailView", module, arguments);
                module.curSection = detailSection;

                //Qualora sia la visualizzazione di default imposta l'attributo filtered
                $$(detailSection.view_id).define("filtered", true);

                module.refreshSectionUi(false, true);

                return true;
            } else {
                console.error("module.eventHandlers.defaultHandlers.showDetailView - identificativo non definito", arguments);
                return Promise.reject("Identificativo non definito");
            }
        } catch (msg) {
            console.error("module.eventHandlers.defaultHandlers.showDetailView", msg, this, arguments);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };

    //Gestore evento sectionActionMenu
    this.eventHandlers['defaultHandlers'].sectionActionMenu = [];

    //SaveAsPdf
    this.eventHandlers['defaultHandlers']['sectionActionMenu'].saveAsPdf = async function(params) {
        try {
            console.log("defaultHandlers.saveAsPdf", this, arguments);
            let sel = [];

            //lista
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.list_id)) {
                let list_box = $$(params.list_id);
                if (list_box) {
                    sel = list_box.getSelectedId(true);
                    if (sel.length == 0) {
                        sel = list_box.data.order;
                    }
                }
            }

            //Visualizzazione di dettaglio
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.object_id)) {
                sel = [params.object_id];
            }

            if (sel.length > 0) {
                let result = await AA_MainApp.setSessionVar("SaveAsPdf_ids", sel);
                if (result) {
                    await AA_MainApp.utils.callHandler("pdfPreview", { url: this.taskManager + "?task=PdfExport" }, this.id);
                    return true;
                } else {
                    console.error("defaultHandlers.saveAsPdf", this, arguments);
                    return false;
                }
            }
        } catch (msg) {
            console.error("defaultHandlers.saveAsPdf", msg, this, arguments);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };
    this.eventHandlers['defaultHandlers'].saveAsPdf = this.eventHandlers['defaultHandlers']['sectionActionMenu'].saveAsPdf;

    //SaveAsCsv
    this.eventHandlers['defaultHandlers']['sectionActionMenu'].saveAsCsv = async function() {
        try {
            console.log("defaultHandlers.saveAsCsv", this, arguments);
            let sel = [];

            //lista
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.list_id)) {
                let list_box = $$(params.list_id);
                if (list_box) {
                    sel = list_box.getSelectedId(true);
                    if (sel.length == 0) {
                        sel = list_box.data.order;
                    }
                }
            }

            //Visualizzazione di dettaglio
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.object_id)) {
                sel = [params.object_id];
            }

            if (sel.length > 0) {
                if (!AA_MainApp.utils.isDefined(params.task)) params.task = "saveAsCsvItems";

                params.postParams = { ids: JSON.stringify(sel) };
                this.dlg(params);
            }
        } catch (msg) {
            console.error("defaultHandlers.saveAsCsv", msg, this, arguments);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };
    this.eventHandlers['defaultHandlers'].saveAsCsv = this.eventHandlers['defaultHandlers']['sectionActionMenu'].saveAsCsv;

    //trash
    this.eventHandlers['defaultHandlers']['sectionActionMenu'].trash = async function(params) {
        try {
            console.log("defaultHandlers.sectionActionMenu.trash", this, arguments);

            let sel = [];

            //lista
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.list_id)) {
                let list_box = $$(params.list_id);
                if (list_box) {
                    sel = list_box.getSelectedId(true);
                    if (sel.length == 0) {
                        sel = list_box.data.order;
                    }
                }
            }

            //Visualizzazione di dettaglio
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.object_id)) {
                sel = [params.object_id];
            }

            if (sel.length > 0) {
                if (!AA_MainApp.utils.isDefined(params.task)) params.task = "trashItems";

                params.postParams = { ids: JSON.stringify(sel) };
                this.dlg(params);
            }

        } catch (msg) {
            console.error("defaultHandlers.sectionActionMenu.trash", msg, this, arguments);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };
    this.eventHandlers['defaultHandlers'].trash = this.eventHandlers['defaultHandlers']['sectionActionMenu'].trash;

    //delete
    this.eventHandlers['defaultHandlers']['sectionActionMenu'].delete = async function(params) {
        try {
            console.log("defaultHandlers.sectionActionMenu.delete", this, arguments);

            let sel = [];

            //lista
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.list_id)) {
                let list_box = $$(params.list_id);
                if (list_box) {
                    sel = list_box.getSelectedId(true);
                    if (sel.length == 0) {
                        sel = list_box.data.order;
                    }
                }
            }

            //Visualizzazione di dettaglio
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.object_id)) {
                sel = [params.object_id];
            }

            if (sel.length > 0) {
                if (!AA_MainApp.utils.isDefined(params.task)) params.task = "deleteItems";

                params.postParams = { ids: JSON.stringify(sel) };
                this.dlg(params);
            }
        } catch (msg) {
            console.error("defaultHandlers.sectionActionMenu.delete", msg, this, arguments);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };
    this.eventHandlers['defaultHandlers'].delete = this.eventHandlers['defaultHandlers']['sectionActionMenu'].delete;

    //publish
    this.eventHandlers['defaultHandlers']['sectionActionMenu'].publish = async function(params) {
        try {
            console.log("defaultHandlers.sectionActionMenu.publish", this, arguments);

            let sel = [];

            //lista
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.list_id)) {
                let list_box = $$(params.list_id);
                if (list_box) {
                    sel = list_box.getSelectedId(true);
                    if (sel.length == 0) {
                        sel = list_box.data.order;
                    }
                }
            }

            //Visualizzazione di dettaglio
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.object_id)) {
                sel = [params.object_id];
            }

            if (sel.length > 0) {
                if (!AA_MainApp.utils.isDefined(params.task)) params.task = "publishItems";

                params.postParams = { ids: JSON.stringify(sel) };
                this.dlg(params);
            }
        } catch (msg) {
            console.error("defaultHandlers.sectionActionMenu.publish", msg, this, arguments);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };
    this.eventHandlers['defaultHandlers'].publish = this.eventHandlers['defaultHandlers']['sectionActionMenu'].publish;

    //resume
    this.eventHandlers['defaultHandlers']['sectionActionMenu'].resume = async function(params) {
        try {
            console.log("defaultHandlers.sectionActionMenu.resume", this, arguments);

            let sel = [];

            //lista
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.list_id)) {
                let list_box = $$(params.list_id);
                if (list_box) {
                    sel = list_box.getSelectedId(true);
                    if (sel.length == 0) {
                        sel = list_box.data.order;
                    }
                }
            }

            //Visualizzazione di dettaglio
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.object_id)) {
                sel = [params.object_id];
            }

            if (sel.length > 0) {
                if (!AA_MainApp.utils.isDefined(params.task)) params.task = "resumeItems";

                params.postParams = { ids: JSON.stringify(sel) };
                this.dlg(params);
            }
        } catch (msg) {
            console.error("defaultHandlers.sectionActionMenu.resume", msg, this, arguments);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };
    this.eventHandlers['defaultHandlers'].resume = this.eventHandlers['defaultHandlers']['sectionActionMenu'].resume;

    //reassign
    this.eventHandlers['defaultHandlers']['sectionActionMenu'].reassign = async function(params) {
        try {
            console.log("defaultHandlers.sectionActionMenu.reassign", this, arguments);

            let sel = [];

            //lista
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.list_id)) {
                let list_box = $$(params.list_id);
                if (list_box) {
                    sel = list_box.getSelectedId(true);
                    if (sel.length == 0) {
                        sel = list_box.data.order;
                    }
                }
            }

            //Visualizzazione di dettaglio
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.object_id)) {
                sel = [params.object_id];
            }

            if (sel.length > 0) {
                if (!AA_MainApp.utils.isDefined(params.task)) params.task = "reassignItems";

                params.postParams = { ids: JSON.stringify(sel) };
                this.dlg(params);
            }
        } catch (msg) {
            console.error("defaultHandlers.sectionActionMenu.reassign", msg, this, arguments);
            AA_MainApp.ui.alert(msg);
            return Promise.reject(msg);
        }
    };
    this.eventHandlers['defaultHandlers'].reassign = this.eventHandlers['defaultHandlers']['sectionActionMenu'].reassign;
    //--------------------------------------------

    this.ui = {
        icon: "",
        name: "Modulo generico",
        activeMenuContent: "", //menu contestuale alla sezione attiva
        layout: {},
        module_content_id: "AA_ModuleContent",
        dlg: async function(task, params, module, taskManager = "") {
            try {
                //console.log(".ui.dlg("+task+","+params+","+module+")",params);
                let mod = AA_MainApp.getModule(module);
                if (mod.isValid()) {
                    if (taskManager == "") taskManager = mod.taskManager;
                    let result = await AA_VerboseTask(task, taskManager, params);
                    if (result.status.value == 0) {
                        //console.log(".ui.dlg("+task+","+params+","+module+","+taskManager+")",params,result.content.value);
                        if (result.content.value) await webix.ui(result.content.value).show();
                    } else {
                        console.error(".ui.dlg(" + task + "," + params + "," + module + "," + taskManager + ")", result.error.value);
                        AA_MainApp.ui.alert(result.error.value);
                        return Promise.reject(result.error.value);
                    }
                } else {
                    console.error(".ui.dlg(" + task + "," + params + "," + module + "," + taskManager + ") - modulo non valido.");
                    return false;
                }
            } catch (msg) {
                console.error(".ui.dlg(" + task + "," + params + "," + module + "," + taskManager + ")", msg);
                AA_MainApp.ui.alert(msg);
                return Promise.reject(msg);
            }
        }
    };
}

//Prototipo di modulo
var AA_dummy_module = new AA_Module();

//Variabile applicazione principale
var AA_MainApp = {
    //Funzione di inizializzazione del sistema
    bootUpFunction: AA_DefaultSystemInitialization,

    //task manager
    taskManager: "utils/system_ops.php",

    //utility functions
    utils: {
        isDefined: function(obj) {
            if (obj === true) return true;
            if (typeof(obj) !== "undefined" && obj !== null && obj != "") return true;

            //verifica oggetto jquery
            if (typeof(obj) !== "undefined" && obj !== null && obj.length > 0) return true;

            return false;
        },

        callHandler: async function(handler = "", params = null, module_id = null) {
            try {
                if (!AA_MainApp.utils.isDefined(handler)) return false;

                if (!AA_MainApp.utils.isDefined(module_id)) {
                    module = AA_MainApp.curModule;
                    if (this.config && this.config.module_id) module = AA_MainApp.getModule(this.config.module_id);
                } else module = AA_MainApp.getModule(module_id);

                //Modulo non valido
                if (!module.isValid()) {
                    console.error("AA_MainApp.utils.callHandler - Modulo non valido", this, arguments, module);
                    return false;
                }

                let funct = handler.split('.');
                let hand = null;
                if (module['eventHandlers']['defaultHandlers'][funct[0]]) hand = module['eventHandlers']['defaultHandlers'][funct[0]];
                if (module[funct[0]]) hand = module[funct[0]];
                if (module['eventHandlers'][funct[0]]) hand = module['eventHandlers'][funct[0]];

                for (i = 1; i < funct.length; i++) {
                    if (hand) hand = hand[funct[i]];
                }

                if (typeof hand == "function") return await hand.call(module, params);
                if (typeof window[handler] == "function") return await window[handler].call(module, params);

                console.error("AA_MainApp.utils.callHandler - handler non trovato: ", this, arguments, module);
                return false;
            } catch (msg) {
                console.error("AA_MainApp.utils.callHandler", msg, this, arguments);
                AA_MainApp.ui.alert(msg);
                return Promise.reject(msg);
            }
        },

        getEventHandler: function(event = "", module_id = "", key = "defaultHandlers", bVoid = false) {
            try {
                //console.error("AA_MainApp.utils.getEventHandler",this,arguments, module);

                //funzione vuota di default
                if (!bVoid) vuota = () => {};
                else vuota = null;

                if (!AA_MainApp.utils.isDefined(event)) return vuota;

                if (!AA_MainApp.utils.isDefined(module_id)) {
                    module = AA_MainApp.curModule;
                    if (this.config && this.config.module_id) module = AA_MainApp.getModule(this.config.module_id);
                } else module = AA_MainApp.getModule(module_id);


                //Modulo non valido
                if (!module.isValid()) {
                    console.error("AA_MainApp.utils.getEventHandler - Modulo non valido", this, arguments, module);
                    return vuota;
                }

                let funct = event.split('.');
                let hand = null;
                if (module['eventHandlers'][key] && module['eventHandlers'][key][funct[0]]) hand = module['eventHandlers'][key][funct[0]];
                else {
                    if (module['eventHandlers']['defaultHandlers'][funct[0]]) hand = module['eventHandlers']['defaultHandlers'][funct[0]];
                    if (module[funct[0]]) hand = module[funct[0]];
                    if (module['eventHandlers'][funct[0]]) hand = module['eventHandlers'][funct[0]];
                }

                for (i = 1; i < funct.length; i++) {
                    if (hand) hand = hand[funct[i]];
                }

                if (typeof hand == "function") return hand;
                if (typeof window[hand] == "function") return window[hand];

                console.error("AA_MainApp.utils.getEvenHandler - handler non trovato: ", this, arguments, module);
                return vuota;
            } catch (msg) {
                console.error("AA_MainApp.utils.getEventHandler", msg, this, arguments);
                AA_MainApp.ui.alert(msg);
                return Promise.reject(msg);
            }
        }
    },

    //Array dei moduli registrati
    modules: [],

    //Funzione di registrazione dei moduli
    registerModule: AA_RegisterModule,
    setCurrentModule: AA_SetCurrentModule,
    getModule: AA_GetModule,

    //Funzione per la memorizzazione di variabili di sessione
    setSessionVar: async function(name, value) {
        try {
            let postParams = { name: name, value: JSON.stringify(value) };
            let result = await AA_VerboseTask("SetSessionVar", AA_MainApp.taskManager, "", postParams);
            if (result.status.value == 0) {
                return true;
            } else {
                console.error("AA_MainApp.setSessionVar", result.error.value, arguments);
                return false;
            }
        } catch (msg) {
            console.error("AA_MainApp.setSessionVar", msg, arguments);
            return Promise.reject(msg);
        }
    },

    //modulo corrente
    curModule: AA_dummy_module,

    searchBoxParams: {
        id: "AA_MainSearchBox",
        query: "stato_scheda_search=1",
        getURL: "",
        postURL: "system_ops.php?task=search",
        defaultSuccessFunction: AA_RefreshAccordion,

        //parametri di ricerca generici
        stato_scheda_search: 1,
        stato_cestinata_search: 0,
        stato_revisionata_search: 0,
        struttura_desc_search: "Qualunque",
        id_assessorato_search: 0,
        id_direzione_search: 0,
        id_servizio_search: 0,
        goToPage: 1
    },

    userAuth: AA_UserAuth,

    ui: {
        enableGui: false,
        alert: AA_AlertModalDlg,
        message: AA_Message,
        showWaitMessage: AA_ShowWaitMessage,
        hideWaitMessage: AA_HideWaitMessage,
        closeWnd: function(id) {
            console.log("closeWnd", arguments);
            if ($$(id)) $$(id).close();
        },
        sidebar: {
            content: "",
            select: function(id) { $$("AA_MainSidebar").select(id); },
            itemSelected: ""
        },
        navbar: {
            refreshContent: function() { AA_RefreshNavbarContent(AA_MainApp.curModule.id, false); },
            refresh: function() { AA_RefreshNavbarContent(AA_MainApp.curModule.id, true); },
            clearAll: function() {

                webix.ui({ id: "AA_navbar_path", view: "layout", minwidth: 120, cols: [{ id: "navbar_spacer", view: "spacer" }], type: "clean", align: "left" }, $$("AA_navbar_box"), $$("AA_navbar_path"));
            },
            refreshUi: function() {
                AA_RefreshNavbarContent(AA_MainApp.curModule.id, true, true);
            },
            content: [{ id: "navbar_spacer", view: "spacer" }]
        },
        MainUI: {
            setup: AA_SetupMainUi,
            refresh: AA_RefreshMainUi,
            moduleContentBox: "AA_ModuleContentBox",
            setModuleContentBox: function(content, bAppend) {
                let moduleContentBox = $$("AA_ModuleContentBox");

                if (typeof content == "string") {
                    if (!bAppend) {
                        $('#' + this.moduleContentBox).empty();
                    }

                    $('#' + this.moduleContentBox).html(content);
                }

                if (typeof content == "object") {
                    webix.ui(content, moduleContentBox, moduleContentBox.getChildViews()[0]);
                }

                //Visualizza un messaggio di successo
                console.log("MainUI::setModuleContentBox() - il layout del box del modulo è stato aggiornato con il contenuto indicato.");
                //AA_MainApp.ui.message("il layout del box del modulo è stato aggiornato.","success");
            },

            refreshModuleContentBox: async function(bRefreshModuleContent = true) {
                if (AA_MainApp.curModule.isValid()) {
                    //console.log("refreshModuleContentBox()",AA_MainApp.curModule);
                    if (bRefreshModuleContent) {
                        try {
                            AA_MainApp.ui.showWaitMessage("Caricamento in corso...");
                            await AA_MainApp.curModule.refreshContent();
                            AA_MainApp.ui.hideWaitMessage();
                        } catch (msg) {
                            //Chiudi la finestra di attesa
                            AA_MainApp.ui.hideWaitMessage();

                            //Visualizza un messaggio di errore
                            console.error("MainUI::refreshModuleContentBox(" + bRefreshModuleContent + ") - ", msg);
                            AA_MainApp.ui.alert(msg);
                            return msg;
                        }
                    }

                    //Aggiorna l'interfaccia grafica del modulo
                    AA_MainApp.curModule.refreshSectionUi();

                    //Visualizza un messaggio di successo
                    console.log("MainUI::refreshModuleContentBox(" + bRefreshModuleContent + ") - La visualizzazione del modulo: " + AA_MainApp.curModule.id + " è stata aggiornata.");
                    AA_MainApp.ui.message("La visualizzazione del modulo: " + AA_MainApp.curModule.id + " è stata aggiornata.", "success");
                    return true;
                }
                return Promise.reject(false);
            },
            setModuleHeaderContent: function(content = { icon: "", title: "" }) {
                if ($$("AA_navbar_module_title") && content) {
                    $$("AA_navbar_module_title").define("data", content);
                    $$("AA_navbar_module_title").refresh();
                } else {
                    console.error("AA_MainApp.ui.MainUI.setModuleHeaderContent()", content);
                }
            },
            setModuleSectionHeaderContent: function(content = { title: "" }) {
                if ($$("AA_Module_Section_Header_Title_Box") && content) {
                    $$("AA_Module_Section_Header_Title_Box").define("data", content);
                    $$("AA_Module_Section_Header_Title_Box").refresh();
                } else {
                    console.error("AA_MainApp.ui.MainUI.setModuleSectionHeaderContent()", content);
                }

            },
            showActionMenu: AA_ShowActionMenu,
            AMAAI: {
                start: AA_StartAMAAI,
                close: function() { if (AA_MainApp.ui.MainUI.AMAAI.wnd) AA_MainApp.ui.MainUI.AMAAI.wnd.close(); },
                hide: function() { if (AA_MainApp.ui.MainUI.AMAAI.wnd) AA_MainApp.ui.MainUI.AMAAI.wnd.hide(); },
                show: function() { if (AA_MainApp.ui.MainUI.AMAAI.wnd) AA_MainApp.ui.MainUI.AMAAI.wnd.show(); }
            },

            structDlg: {
                show: async function(taskParams = "", params = "") {
                    try {
                        let result = await AA_VerboseTask("GetStructDlg", AA_MainApp.taskManager, taskParams);
                        if (result.status.value != 0) {
                            console.error("AA_MainApp.ui.MainUI.StructDlg.show", params, result.error.value);
                            AA_MainApp.ui.alert(result.error.value);
                            return Promise.reject(result.error.value);
                        }

                        console.log("AA_MainApp.ui.MainUI.StructDlg.show", params, result.content.value);

                        if (typeof result.content.value == "object") {
                            //Imposto la selezione se l'oggetto è un albero
                            if (result.content.value['body']['rows'][1]['view'] == "tree") {
                                result.content.value['body']['rows'][1]['ready'] = function() {
                                    let tree_view = $$("AA_SystemStructDlg_Tree");
                                    let switch_supressed = $$("AA_SystemStructDlg_Switch_Supressed");
                                    let search_text = $$("AA_SystemStructDlg_Search_Text");
                                    let filter_function = function(obj) {
                                        let ret = true;
                                        if (switch_supressed) {
                                            if (switch_supressed.getValue() == 0 && obj.soppresso == 1) ret = false;
                                        }

                                        if (search_text) {
                                            ret = obj.value.toLowerCase().indexOf(search_text.getValue().toLowerCase()) !== -1;
                                        }

                                        //console.log("AA_SystemStructDlg_Tree",ret,obj);
                                        return ret;
                                    };

                                    if (tree_view) {
                                        tree_view.filter(filter_function);
                                    }

                                    if (switch_supressed) {
                                        switch_supressed.attachEvent("onChange", function(newValue, oldValue, config) {
                                            if (tree_view) tree_view.filter(filter_function);
                                        });
                                    }

                                    if (search_text) {
                                        search_text.attachEvent("onTimedKeyPress", function() { if (tree_view) tree_view.filter(filter_function); });
                                    }

                                    if (params['select']) {
                                        //console.log("AA_MainApp.ui.MainUI.StructDlg.show.ready", this);
                                        if (AA_MainApp.ui.MainUI.structDlg.lastSelectedItem['id']) {
                                            this.select(AA_MainApp.ui.MainUI.structDlg.lastSelectedItem['id']);
                                        }

                                        let itemSelected = this.getSelectedItem();
                                        if (AA_MainApp.utils.isDefined(itemSelected)) {
                                            //console.log("AA_MainApp.ui.MainUI.StructDlg.show.select", itemSelected);
                                            this.open(itemSelected.$parent);
                                        }
                                    };
                                }
                            }

                            webix.ui(result.content.value).show();
                        }
                    } catch (msg) {
                        console.error("AA_MainApp.ui.MainUI.StructDlg.show", params, msg);
                        AA_MainApp.ui.alert(msg);
                        return Promise.reject(msg);
                    }
                },
                lastSelectedItem: {}
            }
        },
        SearchDlg: {
            show: AA_MainSearchDlg,
            initialize: async function() { await AA_MainSearchDlg({ hidden: true }) }
        },
        StructTreeDlg: {
            show: AA_ModalStructTreeDlg
        },
        UploadDlg: {
            show: AA_ModalUploadFileDlg
        },
        ModalDlg: {
            show: AA_ModalFeedbackDlg
        },
        MainAccordionBox: {
            refresh: AA_RefreshAccordion,
            refreshItemView: AA_RefreshAccordionItemView
        }
    },

    accordionBoxParams: {
        id: "AA_MainAccordionBox",
        curActiveAccordionItem: 0,
        itemsForPage: 10,
        curPage: 0,
        totalPages: 0,
        userFunction: function(params) {}
    },

    structTreeDlgParams: {
        id_assessorato: 0,
        assessorato_desc: "Qualunque",
        id_direzione: 0,
        direzione_desc: "Qualunque",
        id_servizio: 0,
        servizio_desc: "Qualunque"
    }
}

//Default system initialization
function AA_DefaultSystemInitialization(params) {
    console.log("Amministrazione Aperta - Inizializzazione di sistema...");

    //valori iniziali di ricerca
    if (typeof(stato_scheda_search) !== "undefined" && stato_scheda_search == 2) {
        AA_MainApp.searchBoxParams.query = "stato_scheda_search=2";
        AA_MainApp.searchBoxParams.stato_scheda_search = 2;
    }

    if (typeof(stato_scheda_search) !== "undefined" && stato_scheda_search == 1) {
        AA_MainApp.searchBoxParams.query = "stato_scheda_search=1";
        AA_MainApp.searchBoxParams.stato_scheda_search = 1;
    }

    if (typeof(stato_bozza_search) !== "undefined" && stato_bozza_search == 1) {
        AA_MainApp.searchBoxParams.query = "stato_scheda_search=1";
        AA_MainApp.searchBoxParams.stato_scheda_search = 1;
    }
    if (typeof(stato_pubblicata_search) !== "undefined" && stato_pubblicata_search == 1) {
        AA_MainApp.searchBoxParams.query = "stato_scheda_search=2";
        AA_MainApp.searchBoxParams.stato_scheda_search = 2;
    }

    if (typeof(stato_revisionata_search) !== "undefined" && stato_revisionata_search == 1) {
        AA_MainApp.searchBoxParams.query = "stato_scheda_search=2&stato_revisionata_search=1";
        AA_MainApp.searchBoxParams.stato_scheda_search = 2;
        AA_MainApp.searchBoxParams.stato_revisionata_search = 1;
    }
    if (typeof(stato_cestinata_search) !== "undefined" && stato_cestinata_search == 1) {
        AA_MainApp.searchBoxParams.query = "stato_scheda_search=1&stato_cestinata_search=1";
        AA_MainApp.searchBoxParams.stato_scheda_search = 1;
        AA_MainApp.searchBoxParams.stato_cestinata_search = 1;
    }

    if (typeof(id_assessorato_search) !== "undefined" && id_assessorato_search > 0) {
        AA_MainApp.searchBoxParams.query += "&id_assessorato_search=" + id_assessorato_search;
        AA_MainApp.searchBoxParams.id_assessorato_search = id_assessorato_search;
    }

    if (typeof(id_direzione_search) !== "undefined" && id_direzione_search > 0) {
        AA_MainApp.searchBoxParams.query += "&id_direzione_search=" + id_direzione_search;
        AA_MainApp.searchBoxParams.id_direzione_search = id_direzione_search;
    }

    if (typeof(id_servizio_search) !== "undefined" && id_servizio_search > 0) {
        AA_MainApp.searchBoxParams.query += "&id_assessorato_search=" + id_servizio_search;
        AA_MainApp.searchBoxParams.id_servizio_search = id_servizio_search;
    }

    if (typeof(struct_descr_search) !== "undefined" && struct_descr_search != "Qualunque") {
        AA_MainApp.searchBoxParams.struttura_desc_search = struct_descr_search;
    }

    if (AA_MainApp.ui.enableGui) {
        //inizializza l'interfaccia principale
        AA_MainApp.ui.MainUI.setup();

        AA_MainApp.ui.MainUI.refresh();

        console.log("Amministrazione Aperta - Inizializzazione di sistema conclusa.");
        return;
    }

    //old stuff
    if (AA_MainApp.InitializeFunction) {
        AA_MainApp.InitializeFunction(AA_MainApp.InitializeFunctionParams);
    }

    console.log("Amministrazione Aperta - Inizializzazione di sistema conclusa (vecchio metodo).");

    //Riempi la lista delle voci
    if (AA_MainApp.accordionBoxParams.id !== "") {
        console.log("Amministrazione Aperta - Popolamento dell'accordion.");
        $("div#" + AA_MainApp.accordionBoxParams.id).ready(function() {
            //UpdateFilterParams(true);
            params = { activeAccordionItem: AA_MainApp.accordionBoxParams.curActiveAccordionItem };
            console.log("Refresh accordion");
            AA_RefreshAccordion(params);
        });
    }
    //----
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

//Register new module
function AA_RegisterModule(newModule) {
    console.log("System::AA_RegisterModule(" + newModule + ")");

    if (typeof(newModule) !== "undefined") {
        if (newModule.hasOwnProperty("id") && newModule.id != "" && newModule.hasOwnProperty("type") && newModule.type == 'AA_MODULE') {
            AA_MainApp.modules.push(newModule);
            console.log("System::AA_RegisterModule() - Modulo registrato: " + newModule);
            return true;
        }
    } else {
        console.log("System::AA_RegisterModule() - Modulo non valido.");
        return false;
    }
}

//Ottiene il modulo avente l'identificativo specificato
function AA_GetModule(id) {
    //console.log("AA_GetModule",id);

    for (let i = 0; i < AA_MainApp.modules.length; i++) {
        if (AA_MainApp.modules[i].id == id) {
            return AA_MainApp.modules[i];
        }
    }

    return AA_dummy_module;
}

//Imposta il modulo corrente
async function AA_SetCurrentModule(id) {
    //console.log("System::AA_SetCurrentModule("+id+")");
    var module = AA_MainApp.getModule(id);
    //console.log("AA_SetCurrentModule",module);
    if (module.isValid()) {
        //console.log("System::AA_SetCurrentModule("+id+")")
        //inizializza il modulo
        var result = false;
        try {
            result = await module.initialize();

            //Visualizza un messaggio di successo
            console.log("System::AA_SetCurrentModule(" + id + ") - Il modulo: " + id + " è stato inizializzato correttamente.");
            AA_MainApp.ui.message("Il modulo: " + id + " è stato inizializzato correttamente.", "success");
        } catch (msg) {
            console.error("System::AA_SetCurrentModule(" + id + ") - errore: ", msg);
            return Promise.reject(msg);
        }

        if (result === true) {
            //imposta il modulo corrente
            AA_MainApp.curModule = module;

            //Imposta il layout principale del modulo
            AA_MainApp.ui.MainUI.setModuleContentBox(module.ui.layout);

            //Aggiorna la visualizzazione del contenuto del modulo
            AA_MainApp.ui.MainUI.refreshModuleContentBox(false);

            return true;
        }

        console.error("System::AA_SetCurrentModule(" + id + ") - errore durante l'inizializzazione del modulo: ", module);
        return false;
    }

    console.error("System::AA_SetCurrentModule(" + id + ") - Il modulo: " + id + " non è stato trovato tra i moduli registrati.");
    return Promise.reject("Il modulo: " + id + " non è stato trovato tra i moduli registrati.");
}

//Default system initialization
$(function() {
    AA_MainApp.bootUpFunction();
});

//Carica le informazioni per l'interfaccia principale
async function AA_RefreshMainUi(params) {
    console.log("System::AA_RefreshMainUi()");

    try {
        //Recupero i dati della piattaforma
        var getAppStatus = await AA_VerboseTask("GetAppStatus", AA_MainApp.taskManager);

        if (getAppStatus.status.value == "0") {
            if (getAppStatus.error.value != "") AA_MainApp.ui.message(getAppStatus.error.value);

            //Aggiorna i taskmanager dei moduli
            var modules = JSON.parse($(getAppStatus.content.value)[2].innerText);
            //console.log("System::AA_RefreshMainUi() - moduli",modules);
            if (typeof modules != "undefined") {
                for (curMod in modules) {

                    let module = AA_MainApp.getModule(modules[curMod].id);
                    console.log("System::AA_RefreshMainUi() - modulo", module, modules[curMod]);

                    if (module.id !== "AA_MODULE_DUMMY") {
                        module.taskManager = modules[curMod].remote_folder + "/taskmanager.php";
                        module.ui.icon = modules[curMod].icon;
                        module.ui.name = modules[curMod].name;
                    }
                }
            }
            //--------------------------------------------------------

            //Aggiorna la sidebar
            var sidebar = JSON.parse($(getAppStatus.content.value)[1].innerText);

            if (typeof sidebar != "undefined") {
                $$("AA_MainSidebar").parse(sidebar);

                AA_MainApp.ui.sidebar.content = sidebar;

                if (AA_MainApp.ui.sidebar.itemSelected != "") {
                    //Seleziona l'item corrente
                    AA_MainApp.ui.sidebar.select(AA_MainApp.ui.sidebar.itemSelected);
                } else {
                    //Seleziona l'item home
                    AA_MainApp.ui.sidebar.select("home");
                }
            }

            //Aggiorna il nome utente
            var user = $(getAppStatus.content.value)[0].childNodes[0].innerText;
            if (user.length > 0) {
                $$("AA_icon_user").define("tooltip", user);
                $$("AA_icon_user").define("click", function() {
                    AA_MainApp.ui.message("Selected: " + user, "success");
                });
                AA_MainApp.ui.user = user;
            }
        } else {
            console.error("AA_RefreshMainUi() - " + getAppStatus.error.value);
            if (getAppStatus.error.value != "") AA_MainApp.ui.alert(getAppStatus.error.value);
            return Promise.reject(getAppStatus.error.value);
        }
    } catch (msg) {
        AA_MainApp.ui.hideWaitMessage();
        AA_MainApp.ui.alert(msg);
        console.error("AA_RefreshMainUi() - " + msg);
        return Promise.reject(msg);
    }
}

//Compone l'interfaccia principale
function AA_SetupMainUi() {
    console.log("System::AA_SetupMainUi()");

    //Imposta la posìzione dei messaggi
    webix.message.position = "bottom";

    app_box = {
        id: "AA_app_box",
        rows: [{
                view: "layout",
                type: "clean",
                height: 44,
                cols: [

                    {
                        view: "template",
                        type: "clean",
                        template: "<div class='AA_Module_Section_Title'>&nbsp;</div><div class='AA_Module_Section_Title_Gradient'></div>",
                        width: 44
                    },
                    {
                        id: "AA_Module_Section_Header_Title_Box",
                        view: "template",
                        minWidth: 600,
                        type: "clean",
                        template: "<div class='AA_Module_Section_Title'><span>#title#</span></div><div class='AA_Module_Section_Title_Gradient'></div>",
                        data: { "title": "titolo" }
                    },
                    {
                        view: "template",
                        type: "clean",
                        template: "<div class='AA_Module_Section_Title'>&nbsp;</div><div class='AA_Module_Section_Title_Gradient'></div>",
                        width: 44
                    }
                ]
            },
            {
                view: "layout",
                type: "clean",
                cols: [{
                        id: "AA_MainSidebar",
                        view: "sidebar",
                        collapsedWidth: 44,
                        css: "webix_dark",
                        collapsed: true,
                        width: "300",
                        autoheight: true,
                        template: AA_Sidebar_CustomTemplate,
                        data: AA_MainApp.ui.sidebar.content,
                        on: {
                            onAfterSelect: async function(id) {
                                try {
                                    item = $$(this).getItem(id);
                                    //console.log("AA_MainApp.sidebar.onAfterSelect("+id+")",item);
                                    let result = await AA_MainApp.setCurrentModule(item.module);

                                    return result;
                                } catch (msg) {
                                    console.error("AA_MainApp.sidebar.onAfterSelect(" + id + ")");
                                    AA_MainApp.ui.alert(msg);
                                }
                            }
                        }
                    },
                    {
                        id: "AA_ModuleContentBox",
                        view: "layout",
                        scroll: "auto",
                        cols: [{
                            id: "AA_ModuleContent",
                            template: "",
                            type: "clean"
                        }]
                    },
                    {
                        view: "spacer",
                        css: "AA_bg_dark",
                        width: 44
                    }
                ]
            }
        ]
    };

    navbar_template = {
        id: "AA_navbar_box",
        view: "layout",
        type: "clean",
        height: 40,
        css: "AA_navbar_box",
        cols: [{
                id: "AA_navbar_sidebar_hide_button",
                view: "button",
                type: "icon",
                icon: "mdi mdi-menu",
                width: 44,
                align: "left",
                css: "AA_App_button",
                tooltip: "Mostra/nascondi la sidebar",
                click: function() { $$("AA_MainSidebar").toggle(); }
            },
            { id: "AA_navbar_path", view: "layout", minwidth: 130, cols: [{ id: "navbar_placeholder", view: "spacer" }], type: "clean", align: "left" },
            { id: "AA_navbar_module_title", view: "template", type: "clean", minWidth: 520, template: "<div class='AA_header_module_title'><span class='#icon#'></span><span>#title#</span></div>", data: { icon: "mdi mdi-home", title: "Titolo" } },
            { id: "AA_navbar_assistant", view: "layout", type: "clean", cols: [{}, { view: "template", type: "clean", width: "130", template: "<div class='AA_navbar_link_box_right AA_AMAAI_btn'><a><span class='mdi mdi-compass'></span>Aiuto</a></div>", tooltip: "Avvia l'assistente digitale (AMAAI) per la navigazione assistita.", onClick: { "AA_AMAAI_btn": AA_MainApp.ui.MainUI.AMAAI.start } }] },
            {
                id: "AA_navbar_module_section_context_menu_button",
                view: "button",
                type: "icon",
                icon: "mdi mdi-dots-vertical",
                width: 44,
                align: "left",
                css: "AA_App_button",
                tooltip: "Fai click per visualizzare le azioni disponibili per la sezione corrente.",
                click: AA_MainApp.ui.MainUI.showActionMenu
            }
        ]
    };

    //Main interface
    webix.ui({
        //container: "AA_MainAppBox",
        id: "AA_MainAppBox_content",
        rows: [{
                view: "layout",
                type: "clean",
                css: "AA_header",
                height: 60,
                cols: [
                    { view: "label", width: 200, align: "left", template: "<a href='https://www.regione.sardegna.it' target='_blank'><img class='AA_Header_Logo' src='immagini/logo_ras.svg' alt='logo RAS' title='www.regione.sardegna.it'/></a>" },
                    {},
                    { view: "label", label: "<span class='AA_header_title_incipit'>A</span><span class='AA_header_title'>mministrazione</span> <span class='AA_header_title_incipit'>A</span><span class='AA_header_title'>perta</span>", align: "center", minWidth: 500 },
                    {},
                    { view: "spacer", width: "36" },
                    { id: "AA_icon_user", view: "icon", type: "icon", width: 60, css: "AA_header_icon_color", icon: "mdi mdi-account" },
                    { id: "AA_icon_logout", view: "icon", type: "icon", width: 60, css: "AA_header_icon_color", icon: "mdi mdi-logout", tooltip: "Esci" },
                    { view: "spacer", width: "44" }
                ]
            },
            { view: "spacer", type: "clean", css: "AA_header_separator", height: 2 },
            navbar_template, //Nav bar
            app_box,
            {
                view: "template",
                type: "clean",
                template: "<div class='AA_Footer_Gradient'></div>",
                height: 6
            },
            {
                id: "AA_footer_box",
                view: "layout",
                css: "AA_footer_box",
                cols: [
                    { view: "spacer" }
                ],
                height: 12
            }
        ]
    });
}

//Funzione per i messaggi bloccanti
function AA_AlertModalDlg(msg = "", title = "ERRORE", type = "alert-error") {
    webix.alert({
        title: title,
        text: msg,
        type: type
    });
}

//Funzione per le notifiche
function AA_Message(msg = "", type = "success", timeout = 4000) {
    webix.message({
        text: msg,
        type: type,
        expire: timeout
    });
}

//Funzione per la visualizzazione del messaggio di attesa
function AA_ShowWaitMessage(msg = "Attendere prego...", type = "info") {
    if (typeof AA_MainApp.ui.waitMessage == "string") {
        console.log("AA_ShowWaitMessage - messaggio già impostato.");
        return;
    }

    AA_MainApp.ui.waitMessage = webix.message({
        text: "<span class='lds-dual-ring'></span><span style='margin-left: .5em'>" + msg + "</span>",
        type: type,
        expire: -1,
        id: "AA_WaitMessage"
    });
}

function AA_HideWaitMessage() {
    if (typeof AA_MainApp.ui.waitMessage == "string") {
        webix.message.hide(AA_MainApp.ui.waitMessage);
        AA_MainApp.ui.waitMessage = null;
    }

}

//Funzione per la selezione di un item sulla sidebar
async function AA_Sidebar_SelectItem(id, refreshModule = true, force = false) {
    //console.log("AA_Sidebar_SelectItem("+id+")");

    obj = $$("AA_MainSidebar").getItem(id);

    if (typeof obj != "undefined") {
        var sidebar_item_selected = $$("AA_MainSidebar").getSelectedId();

        //Imposta il modulo corrente qualora sia diverso da quello attuale
        if ((AA_MainApp.curModule != "undefined" && AA_MainApp.curModule.id != obj.module) || typeof AA_MainApp.curModule == "undefined") {
            var result = "";
            try {
                result = await AA_MainApp.setCurrentModule(obj.module);
            } catch (msg) {
                console.error("System::AA_Sidebar_SelectItem(" + id + "," + refreshModule + ") errore durante l'impostazione del modulo corrente: " + msg);
                AA_MainApp.ui.alert(msg);
                result = 0;
            }

            if (result == 1) {
                AA_MainApp.ui.sidebar.itemSelected = id;

                if (sidebar_item_selected != id) {
                    console.log("System::AA_Sidebar_SelectItem(" + id + ") - seleziono l'item sulla sidebar.");
                    $$("AA_MainSidebar").select(id);
                }

                return true;
            } else {
                //Riseleziona l'item precedente
                console.log("System::AA_Sidebar_SelectItem(" + id + ") - riseleziono l'item precedente (" + AA_MainApp.ui.sidebar.itemSelected + ").");
                if ($$("AA_MainSidebar").getSelectedId() != AA_MainApp.ui.sidebar.itemSelected) $$("AA_MainSidebar").select(AA_MainApp.ui.sidebar.itemSelected);
            }

            return false;
        } else {
            console.log("System::AA_Sidebar_SelectItem(" + id + ") - il modulo: " + AA_MainApp.curModule.id + " è già il modulo corrente.");
        }

        if (refreshModule) {
            console.log("System::AA_Sidebar_SelectItem(" + id + ") - Aggiornamento forzato del modulo: " + AA_MainApp.curModule.id);
            return await AA_MainApp.ui.MainUI.refreshModuleContentBox(true);
        }

        return false;
    }

    console.error("System::AA_Sidebar_SelectItem(" + id + ") - item non trovato.");
    return false;
}

//Funzione per il disegno delle voci sulla sidebar
function AA_Sidebar_CustomTemplate(obj, common) {
    if (common.collapsed) {
        return AA_Sidebar_CustomIconTemplate(obj);
    }

    return common.arrow(obj, common) + common.icon(obj, common) + "<span title='" + obj.tooltip + "'>" + obj.value + "</span>";
}

//Funzione per il disegno delle icone sulla sidebar
function AA_Sidebar_CustomIconTemplate(obj) {
    var style = "";
    if (obj.$level > 2) {
        style = "style=\"padding-left:" + (40 * (obj.$level - 2)) + "px\"";
    }
    if (obj.icon)
        return "<span class='webix_icon webix_sidebar_icon " + obj.icon + "' " + style + " title='" + obj.tooltip + "'></span>";
    return "<span " + style + " title='" + obj.tooltip + "'></span>";
}

//Funzione per il recupero di risorse tramite GET
async function AA_Get(url, postParams = "") {
    let result;
    try {
        if (postParams == "") result = await $.get(url);
        else result = await $.post(url, postParams);
        return result;
    } catch (xhr) {
        console.error("AA_Get(" + url + "," + postParams + ")", xhr);
        return Promise.reject(xhr.statusText);
    }
}

//Funzione che restituisce il risultato di un task
async function AA_Task(task, taskManagerURL = "", params = "", postParams = "", verbose = false, raw = false) {
    try {
        if (taskManagerURL == "") taskManagerURL = AA_MainApp.curModule.taskManager;

        let url = taskManagerURL + "?task=" + task;
        if (typeof params == "string" && params != "") url += "&" + params;

        if (typeof params == "object") {
            if (Array.isArray(params)) {
                for (let param of params) {
                    for (var i in param) {
                        url += '&' + i + '=' + param[i];
                    }
                }
            } else {
                for (let i in params) {
                    url += '&' + i + '=' + params[i];
                }
            }
        }

        if (verbose) AA_MainApp.ui.showWaitMessage("Caricamento in corso...");
        var result = await AA_Get(url, postParams);
        AA_MainApp.ui.hideWaitMessage();

        //Restituisce il risultato grezzo
        if (raw) {
            //console.log("raw result: ",result);
            return result;
        }

        res = {
            status: { value: "" },
            error: { value: "", type: "" },
            content: { value: "", type: "" }
        }

        //Status
        var status = $(result).filter("#status");
        if (status.length > 0) {
            res.status.value = status[0].innerText;
            for (attribute of status[0].attributes) {
                res.status[attribute.name] = attribute.value;
            }
        }

        //Error
        var error = $(result).filter("#error");
        if (error.length > 0) {
            res.error.value = error[0].innerHTML;
            for (attribute of error[0].attributes) {
                res.error[attribute.name] = attribute.value;
            }
            if (res.error.encode == "base64") {
                res.error.value = CryptoJS.enc.Utf8.stringify(CryptoJS.enc.Base64.parse(res.error.value));
                //console.log(res.content.value);
            }
            if (res.error.type == "json" && res.error.value != "") res.error.value = JSON.parse(res.error.value);
        }

        //Content
        var content = $(result).filter("#content");
        if (content.length > 0) {
            res.content.value = content[0].innerHTML;
            for (attribute of content[0].attributes) {
                res.content[attribute.name] = attribute.value;
            }
            if (res.content.encode == "base64") {
                res.content.value = CryptoJS.enc.Utf8.stringify(CryptoJS.enc.Base64.parse(res.content.value));
                //console.log(res.content.value);
            }
            if (res.content.type == "json" && res.content.value != "") res.content.value = JSON.parse(res.content.value);
        }

        if (res.status.value == -2) {
            //await AA_MainApp.alert(res.error.value);
            AA_MainApp.userAuth(arguments);
        }

        //console.log("result: ",res);
        return res;
    } catch (msg) {
        AA_MainApp.ui.hideWaitMessage();
        console.error("AA_Task(" + task + "," + taskManagerURL + "," + params + "," + postParams + "," + raw + ")", msg);
        return Promise.reject(msg);
    }
}

//Esegue un task con notifica grafica
async function AA_VerboseTask(task, taskManagerURL = "", params = "", postParams = "", raw = false) {
    try {
        return await AA_Task(task, taskManagerURL, params, postParams, true, raw);
    } catch (msg) {
        return Promise.reject(msg);
    }
}

//Recupera la navbar relativa al modulo indicato
async function AA_RefreshNavbarContent(idModule = "", updateUI = true, updateOnlyUI = false) {
    try {
        let module = AA_MainApp.getModule(idModule);

        //Restituisce una stringa vuota se il modulo non è valido
        if (!module.isValid()) {
            console.error("AA_GetModuleNavbar(" + idModule + ") - modulo non trovato.");
            return "";
        }

        if (!updateOnlyUI) {
            //let params=[{section: module.getActiveView()}];
            //let params="section="+module.getActiveView();
            //let result = await AA_VerboseTask("GetNavbarContent", module.taskManager,params);

            //if(result.status.value == 0)
            //{
            //console.log("AA_RefreshNavbarContent - ", result.content.value);
            if (!Array.isArray(module.curSection.navbar_template)) {
                AA_MainApp.ui.navbar.content = [module.curSection.navbar_template, { view: "spacer" }];
            } else {
                AA_MainApp.ui.navbar.content = module.curSection.navbar_template.concat({ view: "spacer" });
            }

            //for (var item of AA_MainApp.ui.navbar.content)
            //{
            //    if(item.css == "AA_NavbarEventListener") item.onClick={"AA_NavbarEventListener": module.navbarEventHandler};
            //}
            //}
            //else
            //{
            //    console.error("AA_RefreshNavbarContent("+idModule+") - "+result.error.value);
            //    return Promise.reject(result.error.value);
            //}*/         
        }


        if (updateUI || updateOnlyUI) {
            webix.ui({ id: "AA_navbar_path", view: "layout", minwidth: 120, cols: AA_MainApp.ui.navbar.content, type: "clean", align: "left" }, $$("AA_navbar_box"), $$("AA_navbar_path"));

            for (item of AA_MainApp.ui.navbar.content) {
                if (item.view != "spacer") {
                    let label = document.querySelector("." + item.id);
                    if (label) {
                        $$(item.id).define("width", label.offsetWidth);
                        $$(item.id).resize();
                    }
                }
            }
        }
    } catch (msg) {
        console.error("AA_RefreshNavbarContent(" + idModule + ") - " + msg);
        AA_MainApp.ui.alert(msg);
        return Promise.reject(msg);
    }
}

//Visualizza il menu contestuale alla sezione attiva
function AA_ShowActionMenu() {
    try {
        //console.log("AA_ShowActionMenu",arguments,AA_MainApp.curModule);

        if (AA_MainApp.curModule.isValid()) {
            if (typeof AA_MainApp.curModule.ui.activeMenuContent == "object") {
                let obj = $$(arguments[0]);
                if (obj) {
                    if (AA_MainApp.ui.MainUI.activeMenu) {
                        AA_MainApp.ui.MainUI.activeMenu.destructor();
                    }

                    if (AA_MainApp.curModule.ui.activeMenuContent) {
                        AA_MainApp.ui.MainUI.activeMenu = webix.ui(AA_MainApp.curModule.ui.activeMenuContent);
                        AA_MainApp.ui.MainUI.activeMenu.setContext(obj);
                        AA_MainApp.ui.MainUI.activeMenu.show(obj.$view);
                        return true;
                    } else {
                        console.error("AA_ShowActionMenu() - menu non valido", AA_MainApp.curModule.ui.activeMenuContent);
                        return false;
                    }
                } else {
                    console.error("AA_ShowActionMenu() - oggetto non valido", arguments[0]);
                    return false;
                }
            } else {
                console.error("AA_ShowActionMenu() - menu non valido", AA_MainApp.curModule);
                return false;
            }
        }

        console.error("AA_ShowActionMenu() - modulo corrente non valido", AA_MainApp.curModule);
        return false;
    } catch (msg) {
        console.error("AA_ShowActionMenu", msg, arguments);
        return Promise.reject(msg);
    }
}

//Visualizza l'assistente interattivo
async function AA_StartAMAAI() {
    try {
        let result = await AA_VerboseTask("AMAAI_Start", AA_MainApp.taskManager);

        console.log("AA_StartAMAAI", result);

        if (result.status.value == 0) {
            if (AA_MainApp.ui.MainUI.AMAAI.wnd) AA_MainApp.ui.MainUI.AMAAI.wnd.close();

            AA_MainApp.ui.MainUI.AMAAI.wnd = webix.ui(result.content.value);
            AA_MainApp.ui.MainUI.AMAAI.wnd.show();

            return true;
        } else {
            console.error("AA_ShowAMAAI() - " + result.error.value);
            AA_MainApp.ui.alert(result.error.value);
            return Promise.reject(result.error.value);
        }
    } catch (msg) {
        console.error("AA_ShowAMAAI() - " + msg);
        AA_MainApp.ui.alert(msg);
        return Promise.reject(msg);
    }
}

async function AA_UserAuth(params = null) {
    try {
        if ($$("AA_UserAuthDlg")) {
            $$("AA_UserAuthDlg").show();
        } else {
            let login_dlg = {
                id: "AA_UserAuthDlg",
                view: "window",
                height: 400,
                width: 350,
                position: "center",
                modal: true,
                css: "AA_Wnd"
            }

            let header_box = {
                css: "AA_Wnd_header_box",
                view: "toolbar",
                height: 38,
                elements: [{
                    css: "AA_Wnd_title",
                    template: "Autenticazione"
                }]
            }

            let apply_btn = {
                view: "layout",
                height: 48,
                cols: [
                    {},
                    {
                        id: "AA_UserAuth_Apply_btn",
                        view: "button",
                        label: "Accedi",
                        type: "icon",
                        icon: "mdi mdi-login",
                        width: 100,
                        align: "center",
                        params: params,
                        click: async function() {
                            try {
                                //console.log("AA_UserAuth", arguments);

                                let button = $$(arguments[0]);
                                let lastTask = "";
                                if (button) {
                                    //console.log("AA_UserAuth",button);
                                    lastTask = button.config.params[0];
                                }

                                let form_data = $$("AA_UserAuth_Form").getValues();

                                if (form_data['user'].length < 1) {
                                    AA_MainApp.ui.alert("Occorre indicare il nome utente");
                                    return;
                                }

                                if (form_data['pwd'].length < 1) {
                                    AA_MainApp.ui.alert("Occorre indicare una password");
                                    return;
                                }

                                //Hashing password
                                form_data['pwd'] = CryptoJS.MD5(form_data['pwd']).toString();

                                //console.log("AA_UserAuth",form_data);

                                let result = await AA_VerboseTask("UserAuth", AA_MainApp.taskManager, "", form_data);

                                //console.log("AA_UserAuth", result);

                                if (result.status.value == 0) {
                                    $$("AA_UserAuthDlg").close();
                                    //ricarica la pagina
                                    window.location.reload();

                                    //AA_MainApp.ui.MainUI.refresh();
                                    return;
                                } else {
                                    AA_MainApp.ui.alert(result.error.value);
                                    return;
                                }
                            } catch (msg) {
                                console.error("AA_MainApp.AA_UserAuth", msg);
                                AA_MainApp.ui.alert(msg);
                                return Promise.reject(msg);
                            }
                        }
                    },
                    {}
                ]
            }

            let body = {
                view: "layout",
                rows: [{
                        id: "AA_UserAuth_Form",
                        view: "form",
                        elementsConfig: { labelWidth: 90, labelAlign: "right" },
                        elements: [{
                                view: "text",
                                name: "user",
                                label: "utente"
                            },
                            {
                                view: "text",
                                type: "password",
                                name: "pwd",
                                label: "password"
                            }
                        ]
                    },
                    apply_btn
                ]
            }

            login_dlg['head'] = header_box;
            login_dlg['body'] = body;

            webix.ui(login_dlg).show();
        }
    } catch (msg) {
        console.error("AA_UserAuth() - " + msg);
        AA_MainApp.ui.alert(msg);
        return Promise.reject(msg);
    }
}