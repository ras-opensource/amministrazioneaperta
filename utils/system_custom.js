//Registrazione
function AA_UserRegister(params=null) {
    try {
        if ($$("AA_UserRegisterDlg")) {
            $$("AA_UserRegisterDlg").show();
        } else {
            let register_dlg = {
                id: "AA_UserRegisterDlg",
                view: "window",
                height: 590,
                width: 320,
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
                    template: "Nuova registrazione"
                }]
            }

            let apply_btn = {
                view: "layout",
                height: 38,
                cols: [{
                        view: "button",
                        label: "Annulla",
                        css: "AA_Button_secondary",
                        hotkey: "enter",
                        type: "icon",
                        icon: "mdi mdi-close",
                        hotkey: "esc",
                        width: 100,
                        align: "left",
                        click: function() { $$("AA_UserRegisterDlg").close() }
                    },
                    {},
                    {
                        id: "AA_UserRegister_Apply_btn",
                        view: "button",
                        label: "Invia",
                        hotkey: "enter",
                        type: "icon",
                        css: "AA_Button_primary webix_primary",
                        icon: "mdi mdi-email-send",
                        width: 100,
                        align: "center",
                        params: params,
                        click: async function() {
                            try {
                                //console.log("AA_UserAuth", arguments);
                                if (!$$("AA_UserRegister_Form").validate()) {
                                    return false;
                                }

                                let form_data = $$("AA_UserRegister_Form").getValues();

                                //email
                                if (form_data['email'].length < 1) {
                                    AA_MainApp.ui.alert("Occorre inserire una mail valida.");
                                    return false;
                                }

                                //nome 
                                if (form_data['nome'].length < 1) {
                                    AA_MainApp.ui.alert("Occorre inserire il nome.");
                                    return false;
                                }

                                if (form_data['cognome'].length < 1) {
                                    AA_MainApp.ui.alert("Occorre inserire il cognome.");
                                    return false;
                                }
                                //console.log("AA_UserAuth",form_data);

                                let result = await AA_VerboseTask("RegisterNewUser", AA_MainApp.taskManager, "", form_data);

                                //console.log("AA_UserAuth", result);
                                if (result.status.value == 0) {
                                    $$("AA_UserRegisterDlg").close();
                                    AA_MainApp.ui.alert(result.content.value);
                                    return true;
                                } else {
                                    AA_MainApp.ui.alert(result.error.value);
                                    return true;
                                }
                            } catch (msg) {
                                console.error("AA_MainApp.AA_UserRegisterDlg", msg);
                                AA_MainApp.ui.alert(msg);
                                return Promise.reject(msg);
                            }
                        }
                    }
                ]
            }

            let body = {
                view: "layout",
                type: "clean",
                css: "AA_RegisterDlg_bg",
                autoHeight: true,
                rows: [
                    {
                        view: "template",
                        autoHeight: true,
                        template: "<div style='text-align: center;'><div style='text-align: center; font-weight: bold'>Benvenuto!</div><br>Inserisci i tuoi dati sui campi sottostanti.<br>il sistema ti invierà delle credenziali per l'accesso alla piattaforma.</div>"
                    },
                    {
                    id: "AA_UserRegister_Form",
                    view: "form",
                    borderless: true,
                    rules: {
                        "email": webix.rules.isEmail,
                        "nome": webix.rules.isNotEmpty,
                        "cognome": webix.rules.isNotEmpty,
                        "privacy": webix.rules.isChecked
                    },
                    elementsConfig: { labelWidth: 90, labelAlign: "left", labelPosition: "top", iconPosition: "left" },
                    elements: [
                        {
                            view: "text",
                            icon: "mdi mdi-account",
                            name: "nome",
                            bottomLabel: "Inserisci il tuo nome.",
                            required: true,
                            label: "Nome"
                        },
                        {
                            view: "text",
                            icon: "mdi mdi-account",
                            name: "cognome",
                            bottomLabel: "Inserisci il tuo cognome.",
                            required: true,
                            label: "Cognome"
                        },
                        {
                            view: "text",
                            icon: "mdi mdi-email",
                            name: "email",
                            bottomLabel: "Inserisci il tuo indirizzo email.",
                            required: true,
                            label: "Email"
                        },
                        {
                            view: "checkbox",
                            labelRight: "<span style='font-size:smaller'>Dichiaro di aver letto l'informativa privacy.</span>",
                            bottomLabel:"Fai <a href='#'>click qui</a> per leggere l'informativa su come trattiamo i tuoi dati e su come puoi esercitare i tuoi diritti.",
                            bottomPadding: 48,
                            name: "privacy",
                            value: 0,
                            label: "",
                            required: true
                        },
                        {},
                        {
                            type: "space",
                            css: { "background-color": "transparent" },
                            rows: [
                                apply_btn
                            ]
                        }
                    ]
                }]
            }

            register_dlg['head'] = header_box;
            register_dlg['body'] = body;

            webix.ui(register_dlg).show();
            return true;
        }
    } catch (msg) {
        console.error("AA_UserRegisterDlg() - " + msg);
        AA_MainApp.ui.alert(msg);
        return Promise.reject(msg);
    }
}
