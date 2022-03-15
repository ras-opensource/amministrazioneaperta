$(function() {
    var email = $("#email"),
        allFields = $([]).add(email),
        tips = $(".validateTips");
    log = $(".log");

    function updateTips(t) {
        tips.each(function() {
            $(this).text(t);
            $(this).addClass("ui-state-highlight");
            setTimeout(function() {
                tips.each(function() { $(this).removeClass("ui-state-highlight", 1500) });
            }, 500);
        });
    }

    function resetTips() {
        tips.each(function() {
            $(this).text("");
        });
    }

    function checkLength(o, n, min, max) {
        if (o.val().length > max || o.val().length < min) {
            o.addClass("ui-state-error");
            if (min == max) updateTips("Il contenuto del campo " + n + " deve avere esattamente " +
                min + " caratteri.");
            else updateTips("Il contenuto del campo " + n + " deve essere compreso tra " +
                min + " e " + max + " caratteri.");
            return false;
        } else {
            return true;
        }
    }

    function checkRegexp(o, regexp, n) {
        if (!(regexp.test($(o).val()))) {
            if (n != "") {
                $(o).addClass("ui-state-error");
                updateTips(n);
            }
            return false;
        } else {
            return true;
        }
    }

    function Update(e, handler) {
        console.log("Update")
        var $element = e;
        if (handler) var done = handler;
        else var done = function(response) { console.log(response); };

        if ($element.val() == '') {
            console.log("file non impostato");
        }
        // Creates the form, extra inputs and iframe used to 
        //  submit / upload the file
        //wrapElement($element);
        var frame_id = 'ajaxUploader-iframe-' + Math.round(new Date().getTime() / 1000);
        $('body').after('<iframe width="0" height="0" style="display:none;" name="' + frame_id + '" id="' + frame_id + '"/>');
        $element.wrap(function() {
            return '<form action="smartcv.php?task=update&nome=' + $("#nome").val() + '&cognome=' + $("#cognome").val() + '&tipo_incarico=' + $("#tipo_incarico").val() + '&id_assessorato=' + $("#id_assessorato").val() + '&id_direzione=' + $("#id_direzione").val() + '&id_servizio=' + $("#id_servizio").val() +'" method="POST" enctype="multipart/form-data" target="' + frame_id + '" />'
        });
        $('#' + frame_id).load(function() {
            var response = this.contentWindow.document.body.innerHTML;
            //alert(response);
            // Tear-down the wrapper form
            $element.siblings().remove();
            $element.unwrap();
            // Pass back to the user
            done.apply(this, [response]);
        });

        $element.parent('form').submit(function(e) { e.stopPropagation(); }).submit();
    }

    $("#dialog-msg").dialog({
        autoOpen: false,
        resizable: false,
        height: "auto",
        width: 480,
        modal: true,
        buttons: {
            "Chiudi": function() {
                $(this).dialog("close");
            }
        }
    });

    $("body").ready(function() {
        if (page.includes('third-page')) {
            console.log("fase-3");
            $("#first-page").hide();
            $("#second-page").hide();
            $("#third-page").show();
        }

        if (page.includes('second-page')) {
            console.log("fase-2");
            $("#first-page").hide();
            $("#second-page").show();
            $("#third-page").hide();
        }

        if (page.includes('first-page')) {
            console.log("fase-1");
            $("#first-page").show();
            $("#second-page").hide();
            $("#third-page").hide();
        }

    });

    $("#inserisci").on("click", function() {
        console.log("Inserimento dati");
        $("#dialog-update").dialog("option", "title", "Inserimento dati personali");
        $("#nome").val("");
        $("#cognome").val("");
        $("#dialog-update").dialog("open");
    });

    $("#modifica").on("click", function() {
        console.log("Aggiornamento dati");
        $("#dialog-update").dialog("option", "title", "Aggiornamento dati personali");
        $("#nome").val($("#nome_text").text());
        $("#cognome").val($("#cognome_text").text());
        $("#dialog-update").dialog("open");
    });

    $("#elimina").on("click", function() {
        console.log("Eliminazione dati");
        $("#dialog-confirm-delete").dialog("option", "title", "Rimozione dei dati personali");
        $("#dialog-confirm-delete").dialog("open");
    });

    //Dialogo conferma codice
    $("#dialog-confirm-code").dialog({
        autoOpen: false,
        resizable: false,
        height: "auto",
        width: $(window).width() * .25,
        modal: true,
        buttons: {
            "Conferma": function() {
                var bValid = true;
                allFields.removeClass("ui-state-error");
                if (bValid) {
                    //salva i dati
                    var salva = $.post("smartcv.php?task=verify", $("#form-confirm").serialize());
                    salva.done(function(data) {
                        //console.log(data);
                        var status=$(data).filter("div").first().attr("status");
                        console.log("status="+status);

                        if (status == 1 || status == 2 || status == 3) {
                            $("#dialog-confirm-code").dialog("close");
                            $("#first-page").hide();
                            $("#second-page").hide();
                            $("#third-page").hide();
                            if (status == 1) $("#first-page").show();
                            if (status == 2) $("#second-page").show();
                            if (status == 3)
                            {
                                /*var nome = $(data).filter("nome").contents().text();
                                var cognome = $(data).filter("cognome").contents().text();
                                var curriculum = $(data).filter("curriculum").first().attr("status");
                                var email = $(data).filter("email").contents().text();
                                var aggiornamento = $(data).filter("aggiornamento").contents().text();
    
                                $("#nome_text").text(nome);
                                $("#cognome_text").text(cognome);
                                $("#aggiornamento_text").text(aggiornamento);
                                if (curriculum == 1) {
                                    $("#curriculum_link").empty();
                                    var url = 'https://sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/curriculum/?email=' + email + '&po=1';
                                    $("#curriculum_link").append("<a href='" + url + "' target='_blank' title='Fai click per scaricare il curriculum'>presente</a>");
                                } else {
                                    $("#curriculum_link").empty();
                                    $("#curriculum_link").append("non ancora caricato.");
                                }

                                $("#third-page").show();*/
                                location.reload();
                            } 
                            return;
                        } else {
                            $("#msg-content").html("<p>" + data + "</p>");
                            $("#dialog-msg").dialog("open");
                        }
                    });
                }
            },
            "Annulla": function() {
                $(this).dialog("close");
            }
        }
    });

    //Verify user credentials
    $("#login").submit(function(event) {

        //Disable default handler
        event.preventDefault();

        console.log("Login in corso...");

        email = $("#email");

        console.log(email.val());

        var bValid = true;
        bValid = bValid && checkRegexp(email, /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, "L'indirizzo email non è valido.");
        if (!bValid) {
            alert("Occorre indicare una mail valida");
            return;
        }

        var result = $.post("smartcv.php?task=send", $("#login").serialize());
        result.done(function(data) {
            console.log(data);
            if (data == "1") {
                $("#dialog-confirm-code").dialog("open");
                return;
            } else {
                $("#msg-content").html("<p>" + data + "</p>");
                $("#dialog-msg").dialog("open");
            }
        });
    });

    //Dialogo aggiorna dati
    $("#dialog-update").dialog({
        autoOpen: false,
        resizable: false,
        height: "auto",
        width: $(window).width() * .30,
        modal: true,
        buttons: {
            "Salva": function() {
                var bValid = true;
                allFields.removeClass("ui-state-error");
                if (bValid) {
                    //salva i dati
                    //var salva = $.post("index_email.php?task=update", $("#form-update").serialize());
                    Update($("#curriculum"), function(data) {
                        //console.log(data);

                        if ($(data).filter("div").attr("status") == "1") {
                            location.reload();
                            /*$("#dialog-update").dialog("close");
                            $("#msg-content").empty();

                            /*
                            $(data).filter("div").each(function(index, e) {
                                $("#msg-content").append(e);
                            });
                            $("#dialog-msg").dialog("open");

                            var nome = $(data).filter("nome").contents().text();
                            var cognome = $(data).filter("cognome").contents().text();
                            var curriculum = $(data).filter("curriculum").first().attr("status");
                            var email = $(data).filter("email").contents().text();
                            var aggiornamento = $(data).filter("aggiornamento").contents().text();

                            $("#nome_text").text(nome);
                            $("#cognome_text").text(cognome);
                            $("#aggiornamento_text").text(aggiornamento);
                            if (curriculum == 1) {
                                $("#curriculum_link").empty();
                                var url = 'https://sitod.regione.sardegna.it/web/amministrazione_trasparente/pubblicazioni/art14/curriculum/?email=' + email + '&po=1';
                                $("#curriculum_link").append("<a href='" + url + "' target='_blank' title='Fai click per scaricare il curriculum'><img src='immagini/icon-pdf.png' class='aa-icon-pdf' alt='scarica il curriculum'/></a>");
                            } else {
                                $("#curriculum_link").empty();
                                $("#curriculum_link").append("non ancora caricato.");
                            }

                            $("#first-page").hide();
                            $("#second-page").hide();
                            $("#third-page").show();
                            return;*/
                        } else {
                            $("#msg-content").empty();
                            $(data).filter("div").each(function(index, e) {
                                $("#msg-content").append(e);
                            });
                            $("#dialog-msg").dialog("open");
                        }
                    });
                }
            },
            "Annulla": function() {
                $(this).dialog("close");
            }
        }
    });

    //Selezione della struttura
    $("#struttura_desc").click(function() {
        console.log("Seleziona la struttura");

        params={
            getURL: "utils/system_ops.php?task=struttura-utente",
            successFunction: SelectStruct,
            id_assessorato: $("#id_assessorato").val(),
            id_direzione: $("#id_direzione").val(),
            id_servizio: $("#id_servizio").val(),
            show_all: 1
        }

        AA_ModalStructTreeDlg(params);
    });


    //Dialogo eliminazione dei dati
    $("#dialog-confirm-delete").dialog({
        autoOpen: false,
        resizable: false,
        height: "auto",
        width: $(window).width() * .25,
        modal: true,
        buttons: {
            "Si": function() {
                var bValid = true;
                allFields.removeClass("ui-state-error");
                if (bValid) {
                    //salva i dati
                    var salva = $.get("smartcv.php?task=delete");
                    salva.done(function(data) {
                        console.log(data);
                        if (data == "1") {
                            $("#dialog-delete").dialog("close");
                            $("#msg-content").html("<p>Il tuo profilo è stato eliminato.</p>");
                            window.location.assign("https://sitod.regione.sardegna.it/web/amministrazione_aperta/index_email.php");
                            return;
                        } else {
                            $("#msg-content").html("<p>" + data + "</p>");
                            $("#dialog-msg").dialog("open");
                        }
                    });
                }
            },
            "Annulla": function() {
                $(this).dialog("close");
            }
        }
    });
});

//Funzione di selezione della struttura.
function SelectStruct()
{
    console.log(AA_MainApp.structTreeDlgParams);
    $("#id_assessorato").val(AA_MainApp.structTreeDlgParams.id_assessorato);
    $("#id_direzione").val(AA_MainApp.structTreeDlgParams.id_direzione);
    $("#id_servizio").val(AA_MainApp.structTreeDlgParams.id_servizio);

    if(AA_MainApp.structTreeDlgParams.id_servizio > 0) $("#struttura_desc").html(AA_MainApp.structTreeDlgParams.servizio_desc);
    else if(AA_MainApp.structTreeDlgParams.id_direzione > 0) $("#struttura_desc").html(AA_MainApp.structTreeDlgParams.direzione_desc);
    else if(AA_MainApp.structTreeDlgParams.id_assessorato > 0) $("#struttura_desc").html(AA_MainApp.structTreeDlgParams.assessorato_desc);
}

//MD5 sum functions
function MD5(d) { result = M(V(Y(X(d), 8 * d.length))); return result.toLowerCase() };

function M(d) { for (var _, m = "0123456789ABCDEF", f = "", r = 0; r < d.length; r++) _ = d.charCodeAt(r), f += m.charAt(_ >>> 4 & 15) + m.charAt(15 & _); return f }

function X(d) { for (var _ = Array(d.length >> 2), m = 0; m < _.length; m++) _[m] = 0; for (m = 0; m < 8 * d.length; m += 8) _[m >> 5] |= (255 & d.charCodeAt(m / 8)) << m % 32; return _ }

function V(d) { for (var _ = "", m = 0; m < 32 * d.length; m += 8) _ += String.fromCharCode(d[m >> 5] >>> m % 32 & 255); return _ }

function Y(d, _) {
    d[_ >> 5] |= 128 << _ % 32, d[14 + (_ + 64 >>> 9 << 4)] = _;
    for (var m = 1732584193, f = -271733879, r = -1732584194, i = 271733878, n = 0; n < d.length; n += 16) {
        var h = m,
            t = f,
            g = r,
            e = i;
        f = md5_ii(f = md5_ii(f = md5_ii(f = md5_ii(f = md5_hh(f = md5_hh(f = md5_hh(f = md5_hh(f = md5_gg(f = md5_gg(f = md5_gg(f = md5_gg(f = md5_ff(f = md5_ff(f = md5_ff(f = md5_ff(f, r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 0], 7, -680876936), f, r, d[n + 1], 12, -389564586), m, f, d[n + 2], 17, 606105819), i, m, d[n + 3], 22, -1044525330), r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 4], 7, -176418897), f, r, d[n + 5], 12, 1200080426), m, f, d[n + 6], 17, -1473231341), i, m, d[n + 7], 22, -45705983), r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 8], 7, 1770035416), f, r, d[n + 9], 12, -1958414417), m, f, d[n + 10], 17, -42063), i, m, d[n + 11], 22, -1990404162), r = md5_ff(r, i = md5_ff(i, m = md5_ff(m, f, r, i, d[n + 12], 7, 1804603682), f, r, d[n + 13], 12, -40341101), m, f, d[n + 14], 17, -1502002290), i, m, d[n + 15], 22, 1236535329), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 1], 5, -165796510), f, r, d[n + 6], 9, -1069501632), m, f, d[n + 11], 14, 643717713), i, m, d[n + 0], 20, -373897302), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 5], 5, -701558691), f, r, d[n + 10], 9, 38016083), m, f, d[n + 15], 14, -660478335), i, m, d[n + 4], 20, -405537848), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 9], 5, 568446438), f, r, d[n + 14], 9, -1019803690), m, f, d[n + 3], 14, -187363961), i, m, d[n + 8], 20, 1163531501), r = md5_gg(r, i = md5_gg(i, m = md5_gg(m, f, r, i, d[n + 13], 5, -1444681467), f, r, d[n + 2], 9, -51403784), m, f, d[n + 7], 14, 1735328473), i, m, d[n + 12], 20, -1926607734), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 5], 4, -378558), f, r, d[n + 8], 11, -2022574463), m, f, d[n + 11], 16, 1839030562), i, m, d[n + 14], 23, -35309556), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 1], 4, -1530992060), f, r, d[n + 4], 11, 1272893353), m, f, d[n + 7], 16, -155497632), i, m, d[n + 10], 23, -1094730640), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 13], 4, 681279174), f, r, d[n + 0], 11, -358537222), m, f, d[n + 3], 16, -722521979), i, m, d[n + 6], 23, 76029189), r = md5_hh(r, i = md5_hh(i, m = md5_hh(m, f, r, i, d[n + 9], 4, -640364487), f, r, d[n + 12], 11, -421815835), m, f, d[n + 15], 16, 530742520), i, m, d[n + 2], 23, -995338651), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 0], 6, -198630844), f, r, d[n + 7], 10, 1126891415), m, f, d[n + 14], 15, -1416354905), i, m, d[n + 5], 21, -57434055), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 12], 6, 1700485571), f, r, d[n + 3], 10, -1894986606), m, f, d[n + 10], 15, -1051523), i, m, d[n + 1], 21, -2054922799), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 8], 6, 1873313359), f, r, d[n + 15], 10, -30611744), m, f, d[n + 6], 15, -1560198380), i, m, d[n + 13], 21, 1309151649), r = md5_ii(r, i = md5_ii(i, m = md5_ii(m, f, r, i, d[n + 4], 6, -145523070), f, r, d[n + 11], 10, -1120210379), m, f, d[n + 2], 15, 718787259), i, m, d[n + 9], 21, -343485551), m = safe_add(m, h), f = safe_add(f, t), r = safe_add(r, g), i = safe_add(i, e)
    }
    return Array(m, f, r, i)
}

function md5_cmn(d, _, m, f, r, i) { return safe_add(bit_rol(safe_add(safe_add(_, d), safe_add(f, i)), r), m) }

function md5_ff(d, _, m, f, r, i, n) { return md5_cmn(_ & m | ~_ & f, d, _, r, i, n) }

function md5_gg(d, _, m, f, r, i, n) { return md5_cmn(_ & f | m & ~f, d, _, r, i, n) }

function md5_hh(d, _, m, f, r, i, n) { return md5_cmn(_ ^ m ^ f, d, _, r, i, n) }

function md5_ii(d, _, m, f, r, i, n) { return md5_cmn(m ^ (_ | ~f), d, _, r, i, n) }

function safe_add(d, _) { var m = (65535 & d) + (65535 & _); return (d >> 16) + (_ >> 16) + (m >> 16) << 16 | 65535 & m }

function bit_rol(d, _) { return d << _ | d >>> 32 - _ }