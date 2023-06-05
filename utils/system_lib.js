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
            let taskManager = params.taskManager;
            if (taskManager == null) taskManager = this.taskManager;

            let result = await AA_VerboseTask(params.task, taskManager, params.params, params.postParams);
            if (result.status.value == 0) {
                if (result.content.value != "") {
                    AA_MainApp.ui.message(result.content.value);
                } else {
                    if (result.error.value != "") {
                        AA_MainApp.ui.message(result.error.value);
                    }
                }
                if (AA_MainApp.utils.isDefined(params.wnd_id)) $$(params.wnd_id).close();
                if (AA_MainApp.utils.isDefined(params.refresh)) {
                    if (AA_MainApp.utils.isDefined(params.refresh_obj_id)) this.refreshUiObject(params.refresh_obj_id, true);
                    else this.refreshCurSection();
                }
                return true;
            } else {
                console.error(this.name + ".doTask", result.error.value);
                if (result.status.value > -2) AA_MainApp.ui.alert(result.error.value);
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
            if (!AA_MainApp.utils.isDefined(params.task)) {
                console.error(this.name + ".dlg - task non impostato", params);
                return false;
            }

            let taskManager = params.taskManager;
            if (taskManager == null) taskManager = this.taskManager;

            let result = await AA_VerboseTask(params.task, taskManager, params.params, params.postParams);
            if (result.status.value == 0) {

                let wnd = webix.ui(result.content.value);

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

                //Imposta lo stato dei sidemenu (se presenti)
                let menus = wnd.queryView("sidemenu", "all");
                for (menu of menus) {
                    if (AA_MainApp.utils.isDefined(menu.config.stateFunction)) {
                        console.log("dlg - sidemenu");
                        menu.config.state = AA_MainApp.utils.getEventHandler(menu.config.stateFunction, this.id);
                    }
                }

                if (AA_MainApp.utils.isDefined(wnd.config.stateFunction)) {
                    console.log("dlg - sidemenu");
                    wnd.config.state = AA_MainApp.utils.getEventHandler(wnd.config.stateFunction, this.id);
                }

                //verifica se ci sono campi di ricerca
                let searchObjs = wnd.queryView({ view: "search" }, "all");
                if (Array.isArray(searchObjs) && searchObjs.length > 0) {
                    for (item of searchObjs) {
                        let funct = item.config.filterFunction;
                        if (AA_MainApp.utils.isDefined(funct)) {

                            if (!item.hasEvent("onTimedKeyPress")) {
                                console.log(this.name + "::refreshUiObjectDefault - imposto l'handler (" + item.config.id + "): ", funct);
                                item.attachEvent("onTimedKeyPress", AA_MainApp.utils.getEventHandler("onTimedKeyPressEventHandler", this.id));
                            }

                            if (!item.hasEvent("onChange") && item.config.clear == true) {
                                console.log(this.name + "::refreshUiObjectDefault - imposto l'handler (" + item.config.id + "): ", funct);
                                item.attachEvent("onChange", AA_MainApp.utils.getEventHandler("onTimedKeyPressEventHandler", this.id));
                            }
                        }
                    }
                }

                wnd.show();

                return true;
            } else {
                console.error(this.name + ".dlg", result.error.value);
                if (result.status.value > -2) AA_MainApp.ui.alert(result.error.value);
                return false;
                //return Promise.reject(result.error.value);
            }
        } catch (msg) {
            console.error(this.name + ".dlg", msg);
            AA_MainApp.ui.alert(msg);
            return false;
            //return Promise.reject(msg);
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
                //console.log(this.name + "::initializeDefault()");

                //Imposta come sezione di default la prima
                this.curSection = this.sections[0];

                //Imposta la sezione di default se ha il flag
                for (section of this.sections) {
                    if (section.default) this.curSection = section;
                }
            } else {
                console.error(this.name + "::initializeDefault() - errore nel recupero delle sezioni: ", sections.error.value);
                if (sections.status.value > -2) AA_MainApp.ui.alert(sections.error.value);
                //return Promise.reject(sections.error.value);
                return false;
            }

            var layout = await AA_VerboseTask("GetLayout", this.taskManager);
            if (layout.status.value == 0) {
                this.ui.layout = layout.content.value;
            } else {
                console.error(this.name + "::initializeDefault() - errore nel recupero del layout: ", layout.error.value);
                if (sections.status.value > -2) AA_MainApp.ui.alert(layout.error.value);
                //return Promise.reject(layout.error.value);
                return false;
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
            if (AA_MainApp.ui.enableModuleHeader) AA_MainApp.ui.MainUI.setModuleHeaderContent({ icon: this.ui.icon, title: this.ui.name });

            //Aggiorna il titolo della sezione
            if (AA_MainApp.ui.enableSectionHeader) AA_MainApp.ui.MainUI.setModuleSectionHeaderContent({ title: this.curSection.name });


            if (activeView) {
                activeView.show();
                await this.refreshUiObject(this.curSection.view_id, refreshView, bResetView);
            }

            //aggiorno la navbar
            if (AA_MainApp.ui.enableNavbar) {
                await AA_MainApp.ui.navbar.refresh();

                //Aggiorno il contenuto del menu contestuale
                await this.refreshActionMenuContent();
            }

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
                let module = AA_MainApp.curModule;

                if (this instanceof AA_Module) module = this;

                if (obj.config.paged == true) {
                    curPage = this.getRuntimeValue(obj.config.pager_id, "curPage");
                    params = [{ "page": curPage }];
                }

                //Verifica se ci sono oggetti con dei tab e ne salva lo stato
                if (!bResetView) {
                    let multiObjs = obj.queryView({ view: "multiview" }, "all");
                    if (Array.isArray(multiObjs) && multiObjs.length > 0) {
                        for (item of multiObjs) {
                            let status = item.getValue();
                            if (AA_MainApp.utils.isDefined(status)) {
                                console.log(module.name + "::refreshUiObjectDefault -saved status (" + item.config.id + "): ", status);
                                module.setRuntimeValue("multiviewStatus", item.config.id, status);
                            }
                        }
                    }

                    let tabbarObjs = obj.queryView({ view: "tabbar" }, "all");
                    if (Array.isArray(tabbarObjs) && tabbarObjs.length > 0) {
                        for (item of tabbarObjs) {
                            let status = item.getValue();
                            if (AA_MainApp.utils.isDefined(status)) {
                                console.log(module.name + "::refreshUiObjectDefault -saved status (" + item.config.id + "): ", status);
                                module.setRuntimeValue("tabBarStatus", item.config.id, status);
                            }
                        }
                    }

                    let accordionObjs = obj.queryView({ view: "accordionitem", collapsed: false }, "all");
                    if (Array.isArray(accordionObjs) && accordionObjs.length > 0) {
                        for (item of accordionObjs) {
                            let status = 1;
                            console.log(module.name + "::refreshUiObjectDefault -saved status (" + item.config.id + "): ", status);
                            module.setRuntimeValue("accordionItemStatus", item.config.id, status);
                        }
                    }
                }

                if (bRefreshContent || !$$(idObj).config.initialized) {
                    //aggiorna il contenuto dell'oggetto
                    let postParams = "";
                    if (obj.config.filtered == true) {
                        let filter_id = obj.config.filter_id;
                        if (!AA_MainApp.utils.isDefined(filter_id)) filter_id = module.getActiveView();
                        postParams = module.getRuntimeValue(filter_id, "filter_data");
                        //console.log(this.name+"::refreshUiObjectDefault("+idObj+")", filter_id,postParams);
                    }

                    //Disabilita la schermata
                    $$(idObj).disable();

                    //console.log(this.name+"::refreshUiObjectDefault("+idObj+")",postParams);
                    let result = await module.refreshObjectContent(idObj, params, postParams);
                    if (result != 1) {
                        console.error(module.name + "::refreshUiObjectDefault(" + idObj + ") - errore: ", result);
                        return Promise.reject(result);
                    }
                }

                //Aggiorna il componente grafico
                let newObj = this.content[idObj];
                if (newObj) {
                    console.log(AA_MainApp.curModule.name + "::refreshUiObjectDefault(" + idObj + ") - Aggiorno l'interfaccia.");
                    //webix.ui(newObj,$$(this.ui.module_content_id),obj);
                    webix.ui(newObj, obj.getParentView(), obj);

                    obj = $$(newObj.id);
                    if (obj) {
                        if (obj.config.view == "layout") {
                            console.log(module.name + "::refreshUiObjectDefault(" + idObj + ") - ricostruisco il layout.")
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

                        //verifica se ci sono campi di ricerca
                        let searchObjs = obj.queryView({ view: "search" }, "all");
                        if (Array.isArray(searchObjs) && searchObjs.length > 0) {
                            for (item of searchObjs) {
                                let funct = item.config.filterFunction;
                                if (AA_MainApp.utils.isDefined(funct)) {
                                    if (!item.hasEvent("onTimedKeyPress")) {
                                        console.log(module.name + "::refreshUiObjectDefault - imposto l'handler (" + item.config.id + "): ", funct);
                                        item.attachEvent("onTimedKeyPress", AA_MainApp.utils.getEventHandler("onTimedKeyPressEventHandler", AA_MainApp.curModule.id));
                                    }

                                    if (!item.hasEvent("onChange") && item.config.clear == true) {
                                        console.log(module.name + "::refreshUiObjectDefault - imposto l'handler (" + item.config.id + "): ", funct);
                                        item.attachEvent("onChange", AA_MainApp.utils.getEventHandler("onTimedKeyPressEventHandler", AA_MainApp.curModule.id));
                                    }
                                }
                            }
                        }

                        //Aggiorna il titolo della sezione.
                        if (this.getActiveView() == newObj.id && AA_MainApp.ui.enableSectionHeader) AA_MainApp.ui.MainUI.setModuleSectionHeaderContent({ title: newObj.name });

                        //Abilita l'auto animazione dei caroselli
                        let carouselObjs = obj.queryView({ view: "carousel" }, "all");
                        if (Array.isArray(carouselObjs) && carouselObjs.length > 0) {
                            for (item of carouselObjs) {

                                if (item.config.autoScroll == true && item.config.autoScrollSlideTime > 1000 && item.config.slidesCount > 1) {
                                    let autoScroll = function(objId) {
                                        let carousel = $$(objId);
                                        if (carousel) {
                                            if (carousel.isVisible()) {
                                                let index = carousel.getActiveIndex();
                                                //console.log(this.name + "::refreshUiObjectDefault - cambio slide ("+index+" of "+carousel.config.slidesCount+").");
                                                if (index == (carousel.config.slidesCount - 1)) {
                                                    carousel.setActiveIndex(0);
                                                } else {
                                                    carousel.showNext();
                                                }
                                            }
                                        }
                                    };

                                    //console.log(this.name + "::refreshUiObjectDefault - imposto la funzione di autoscroll sul carosello.");
                                    //rimuove le precedenti funzioni di impostazione di intervallo
                                    if (module.getRuntimeValue(item.config.id, "autoScrollIntervalFunction")) {
                                        //console.log(this.name + "::refreshUiObjectDefault - Rimuovo la precedente funzione di autoScroll");
                                        window.clearInterval(module.getRuntimeValue(item.config.id, "autoScrollIntervalFunction"));
                                    }
                                    module.setRuntimeValue(item.config.id, "autoScrollIntervalFunction", window.setInterval(autoScroll, item.config.autoScrollSlideTime, item.config.id));
                                }
                            }
                        }

                        //imposta la validazione per i form presenti
                        let forms = obj.queryView("form", "all");
                        for (form of forms) {
                            let oldValues = form.getValues();
                            if (AA_MainApp.utils.isDefined(form.config.validation)) {
                                form.config.rules = { $all: AA_MainApp.utils.getEventHandler(form.config.validation, module.id) };
                            }
                            form.reconstruct();
                            form.setValues(oldValues);
                        }

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
                                        console.log(module.name + "::refreshUiObjectDefault - ripristino lo status del tab (" + item.config.view_id + ")", status);
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

                        //Ripristine lo stato dei multiview se ci sono
                        let multiObjs = obj.queryView({ view: "multiview" }, "all");
                        if (Array.isArray(multiObjs) && multiObjs.length > 0) {
                            for (item of multiObjs) {
                                let status = module.getRuntimeValue("multiviewStatus", item.config.id);
                                if (AA_MainApp.utils.isDefined(status) && $$(status)) {
                                    let view = $$(item.config.id);
                                    if (view) {
                                        let animate = view.config.animate;
                                        view.define('animate', false);
                                        item.setValue(status);
                                        view.config.animate = animate;
                                        module.unsetRuntimeValue("multiviewStatus", item.config.id);
                                        console.log(this.name + "::refreshUiObjectDefault - ripristino lo status del multiview (" + item.config.id + ")", status);
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
                } else {
                    console.error(this.name + "::refreshSectionContentDefault(" + section_id + ") - Contenuto della sezione non trovato.");
                    return 0;
                }
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
                    console.log(this.name + "::refreshObjectContentDefault(" + object_id + ") - Contenuto del modulo non trovato.");
                    return 0;
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
                return false;
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

                    //console.log(module.name + "::pagerEventHandlerDefault", arguments);

                    let pager_title = $$(pager.config.title_id);
                    if (pager_title) {
                        let curPage = Number(arguments[0]) + 1;
                        if (arguments[0] == "prev") curPage = pager.data.page;
                        if (arguments[0] == "next") curPage = Number(pager.data.page) + 2;
                        if (arguments[0] == "first") curPage = 1;
                        if (arguments[0] == "last") curPage = pager.data.limit;

                        //Non va oltre l'ultima pagina
                        if (curPage > pager.data.limit) return true;

                        //non va prima della prima pagina
                        if (curPage < 1) return true;

                        //Salva la pagina nel registro
                        module.setRuntimeValue(pager.config.id, "curPage", curPage)

                        pager_title.define("data", { "curPage": curPage, "totPages": pager.data.limit });

                        //Aggiorna il componente associato
                        let target = $$(pager.config.target);

                        if (target) {

                            let targetAction = pager.config.targetAction;
                            if (targetAction == "refreshData" || !AA_MainApp.utils.isDefined(targetAction)) {
                                //Resetta la selezione quando si sposta di pagina
                                target.unselectAll();

                                //console.log(this.name + "::pagerEventHandlerDefault() - aggiorno i dati del componente: " + pager.config.target, targetAction);
                                await module.refreshObjectData(pager.config.target);

                                //resetta il valore di scrolling della view
                                target.scrollTo(0, 0);
                            }
                        }
                        return true;
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

                    let taskManager = this.taskManager;
                    if (AA_MainApp.utils.isDefined(params.taskManager)) {
                        taskManager = params.taskManager;
                    }

                    let result = await AA_VerboseTask(params.task, taskManager, params.taskParams, params.data);
                    if (result.status.value == 0) {
                        AA_MainApp.ui.message(result.content.value);
                        if (AA_MainApp.utils.isDefined(params.wnd_id) && $$(params.wnd_id)) $$(params.wnd_id).close();
                        if (AA_MainApp.utils.isDefined(params.refresh)) {
                            if (AA_MainApp.utils.isDefined(params.refresh_obj_id)) this.refreshUiObject(params.refresh_obj_id, true);
                            else {
                                if (AA_MainApp.utils.isDefined(params.refreshApp)) {
                                    AA_MainApp.ui.MainUI.refresh();
                                } else this.refreshCurSection();

                                //aggiorna l'immagine del profilo
                                if (AA_MainApp.utils.isDefined(params.refreshUserProfile)) {
                                    const urlParams = new URLSearchParams(window.location.search);
                                    var getAppStatus = await AA_VerboseTask("GetAppStatus", AA_MainApp.taskManager, "module=" + urlParams.get("module") + "&mobile=" + AA_MainApp.device.isMobile + "&viewport_width=" + AA_MainApp.ui.viewport.width + "&viewport_height=" + AA_MainApp.ui.viewport.height);
                                    if (getAppStatus.status.value == "0") {
                                        //Aggiorna il nome utente e l'immagine
                                        var user = $(getAppStatus.content.value)[0].childNodes[0].innerText;
                                        if (user.length > 0) {
                                            $$("AA_icon_user").define("tooltip", user);
                                            $$("AA_icon_user").define("data", { "user_image_path": $(getAppStatus.content.value)[0].childNodes[5].nextSibling.data });
                                            AA_MainApp.ui.user = user;
                                        }
                                    }
                                }
                            }
                        }

                        //Verifica se ci sono ulteriori azioni da intraprendere
                        if (AA_MainApp.utils.isDefined(result.status.action)) {
                            console.log(this.name + "eventHandlers.defaultHandlers.saveData", result.status);
                            AA_MainApp.utils.callHandler(result.status.action, JSON.parse(result.status.action_params), this.id);
                        }
                        return true;
                    } else {

                        if (result.status.value > -2) {
                            if (result.error.type != "json") {
                                AA_MainApp.ui.alert(result.error.value);
                                return false;
                            } else {
                                webix.ui(result.error.value).show();
                                return false;
                            }
                        }
                        return false;
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
                if (item) selection.push(parseInt(item.id));
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

                console.log("eventHandlers.defaultHandlers.pdfPreview params:", params);

                let result = await AA_VerboseTask("GetPdfPreviewDlg", AA_MainApp.taskManager, params.queryString, params.postParams);
                if (result.status.value == 0) {
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
            //console.log(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.validateForm", this, arguments);

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

                                if (this.elements[arguments[2]].config.showMessage) {
                                    AA_MainApp.ui.message(this.elements[arguments[2]].config.invalidMessage, "error");
                                }
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

                                if (this.elements[arguments[2]].config.showMessage) {
                                    AA_MainApp.ui.message(this.elements[arguments[2]].config.invalidMessage, "error");
                                }
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

                                if (this.elements[arguments[2]].config.showMessage) {
                                    AA_MainApp.ui.message(this.elements[arguments[2]].config.invalidMessage, "error");
                                }
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

                                if (this.elements[arguments[2]].config.showMessage) {
                                    AA_MainApp.ui.message(this.elements[arguments[2]].config.invalidMessage, "error");
                                }
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

                                if (this.elements[arguments[2]].config.showMessage) {
                                    AA_MainApp.ui.message(this.elements[arguments[2]].config.invalidMessage, "error");
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

                                if (this.elements[arguments[2]].config.showMessage) {
                                    AA_MainApp.ui.message(this.elements[arguments[2]].config.invalidMessage, "error");
                                }
                            }
                        }
                        //console.log(AA_MainApp.curModule.name+"eventHandlers.defaultHandlers.validateForm - value:", arguments[0], valFunc, val);
                    }

                    if (valFunc == "IsMail" || valFunc == "IsEmail") {
                        if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.customInvalidMessage)) {
                            let invalidMessage = "*Inserire un indirizzo email valido";
                            if (!this.elements[arguments[2]].config.required) invalidMessage += " o lasciare vuoto";
                            this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                        } else {
                            this.elements[arguments[2]].config.invalidMessage = this.elements[arguments[2]].config.customInvalidMessage;
                        }

                        let email = arguments[0].trim();

                        let found = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email || "");
                        if (!found) {
                            val = false;
                        }

                        if (this.elements[arguments[2]].config.required && email == "") val = false;
                        if (!val && this.elements[arguments[2]].config.showMessage) {
                            AA_MainApp.ui.message(this.elements[arguments[2]].config.invalidMessage, "error");
                        }
                        //console.log(AA_MainApp.curModule.name+"eventHandlers.defaultHandlers.validateForm - value:", arguments[0], valFunc, val);
                    }

                    if (valFunc == "IsChecked") {
                        if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.customInvalidMessage) && this.elements[arguments[2]].config.required) {
                            let invalidMessage = "*Occorre impostare il check.";
                            this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                        } else {
                            this.elements[arguments[2]].config.invalidMessage = this.elements[arguments[2]].config.customInvalidMessage;
                        }

                        if (arguments[0] == 1) val = true;
                        else {
                            val = false;
                            if (this.elements[arguments[2]].config.showMessage) {
                                AA_MainApp.ui.message(this.elements[arguments[2]].config.invalidMessage, "error");
                            }
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
                            if (!dNum && dNum !== 0) {
                                val = false;
                                if (this.elements[arguments[2]].config.showMessage) {
                                    AA_MainApp.ui.message(this.elements[arguments[2]].config.invalidMessage, "error");
                                }
                            }
                        }
                    }

                    if (valFunc == "IsSecurePwd") {
                        if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.customInvalidMessage)) {
                            let invalidMessage = "*La password deve avere min. 8 car., almeno una lettera minuscola, almeno una lettera maiuscola, almeno un numero.";
                            if (!this.elements[arguments[2]].config.required) invalidMessage += " o lasciare vuoto";
                            this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                        } else {
                            this.elements[arguments[2]].config.invalidMessage = this.elements[arguments[2]].config.customInvalidMessage;
                        }

                        if (arguments[0] != "" || this.elements[arguments[2]].config.required) {
                            let found = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/.test(arguments[0]);
                            if (!found) {
                                val = false;
                                if (this.elements[arguments[2]].config.showMessage) {
                                    AA_MainApp.ui.message(this.elements[arguments[2]].config.invalidMessage, "error");
                                }
                            }
                        }

                        //console.log(AA_MainApp.curModule.name+"eventHandlers.defaultHandlers.validateForm - value:", arguments[0], valFunc, val);
                    }
                } else {
                    if (this.elements[arguments[2]].config.required) {
                        if (!AA_MainApp.utils.isDefined(this.elements[arguments[2]].config.customInvalidMessage)) {
                            let invalidMessage = "*Il campo non può essere vuoto";
                            this.elements[arguments[2]].config.invalidMessage = invalidMessage;
                        } else {
                            this.elements[arguments[2]].config.invalidMessage = this.elements[arguments[2]].config.customInvalidMessage;
                        }

                        if (String(arguments[0]).length == 0) {
                            val = false;

                            if (this.elements[arguments[2]].config.showMessage) {
                                AA_MainApp.ui.message(this.elements[arguments[2]].config.invalidMessage, "error");
                            }
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
            //console.log("defaultHandlers.saveAsPdf", this, arguments);
            let sel = [];
            let queryString = null;

            //lista
            if (AA_MainApp.utils.isDefined(params) && AA_MainApp.utils.isDefined(params.list_id)) {
                let list_box = $$(params.list_id);
                if (list_box) {
                    sel = list_box.getSelectedId(true);
                    if (sel.length == 0) {
                        //sel = list_box.data.order;
                        let filter_id = list_box.config.filter_id;
                        if (!AA_MainApp.utils.isDefined(filter_id)) filter_id = this.getActiveView();
                        queryString = this.getRuntimeValue(filter_id, "filter_data");
                        if (!AA_MainApp.utils.isDefined(queryString)) {
                            queryString = { count: "all", section: this.curSection.id };
                        } else {
                            queryString.count = "all";
                            queryString.section = this.curSection.id;
                        }
                        console.log("defaultHandlers.saveAsPdf - queryString:", queryString);
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
                    await AA_MainApp.utils.callHandler("pdfPreview", { url: this.taskManager + "?task=PdfExport&section=" + this.curSection.id }, this.id);
                    return true;
                } else {
                    console.error("defaultHandlers.saveAsPdf", this, arguments);
                    return false;
                }
            } else {
                let result = await AA_MainApp.setSessionVar("SaveAsPdf_params", queryString);
                if (result) {
                    await AA_MainApp.utils.callHandler("pdfPreview", { url: this.taskManager + "?task=PdfExport&fromParams=1&section=" + this.curSection.id }, this.id);
                }
                return true;
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
        dlg: async function(task, param, module, taskManager = "") {
            try {
                //console.log(".ui.dlg(" + task + "," + param + "," + module + ")", param);
                let params = { "task": task, "params": param, "module": module, "taskManager": taskManager };
                let mod = AA_MainApp.getModule(module);
                if (mod.isValid()) {
                    return mod.dlg(params);
                } else return AA_MainApp.setCurrentModule.dlg(params);
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
    //sito web o url principale
    web_url: "https://sitod.regione.sardegna.it",

    //policy url
    privacy_policy_url: "https://sitod.regione.sardegna.it",

    //enable legacy
    bEnableLegacy: false,

    //Device
    device: {
        isMobile: false
    },

    //Funzione di inizializzazione del sistema
    bootUpFunction: AA_DefaultSystemInitialization,

    //task manager
    taskManager: "utils/system_ops.php",

    //utility functions
    utils: {
        getMaxZindex: function() {
            return Math.max(
                ...Array.from(document.querySelectorAll('body *'), el =>
                    parseFloat(window.getComputedStyle(el).zIndex),
                ).filter(zIndex => !Number.isNaN(zIndex)),
                0,
            );
        },
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

    //Funzione per la memorizzazione di variabili di sessione
    unsetSessionVar: async function(name, value) {
        try {
            let postParams = { name: name, value: JSON.stringify(value) };
            let result = await AA_VerboseTask("UnsetSessionVar", AA_MainApp.taskManager, "", postParams);
            if (result.status.value == 0) {
                return true;
            } else {
                console.error("AA_MainApp.unsetSessionVar", result.error.value, arguments);
                return false;
            }
        } catch (msg) {
            console.error("AA_MainApp.unsetSessionVar", msg, arguments);
            return Promise.reject(msg);
        }
    },

    //modulo corrente
    curModule: AA_dummy_module,

    //modulo di default (sidebar id)
    defaultSidebarModule: "home",

    //modulo di default (id)
    defaultModule: "AA_MODULE_HOME",

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

    //Autenticazione utente
    userAuth: AA_UserAuth,
    logIn: AA_UserAuth,
    logOut: AA_LogOut,
    resetPwd: AA_UserResetPwd,
    register: AA_UserRegister,

    ui: {
        overlay: {
            content: {
                view: "popup",
                id: "AA_MainOverlay",
                fullscreen: true,
                body: {
                    type: "clean",
                    template: "<div class='AA_MainOverlay'><div class='AA_MainOverlayContent'><img class='AA_Header_Logo' src='immagini/logo_ras.svg' alt='logo RAS' title='www.regione.sardegna.it'><h1><span>A</span>mministrazione <span>A</span>perta</h1></div></div>"
                }
            },
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

                    /*
                    if(!$$("AA_MainOverlay"))
                    {
                        AA_MainApp.ui.overlay.content.width=document.documentElement.clientWidth,
                        AA_MainApp.ui.overlay.content.height=document.documentElement.clientHeight,
                        webix.ui(AA_MainApp.ui.overlay.content).show();
                    }
                    else 
                    {
                        if(!$$("AA_MainOverlay").isVisible())
                        {
                            $$("AA_MainOverlay").define("css", "AA_MainOverlayFadeIn");
                            $$("AA_MainOverlay").show();
                        } 
                    }*/
                } catch (msg) {
                    console.error("AA_MainApp.ui.overlay.show", msg);
                }
            },
            hide: function(delay = 1000) {
                try {
                    let overlay = document.getElementById("AA_MainOverlay");
                    //console.log("AA_MainApp.ui.overlay.hide", overlay);
                    if (overlay) {
                        if (overlay.style.display == "block") {
                            console.log("AA_MainApp.ui.overlay.hide");

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

                    /*
                    if($$("AA_MainOverlay") && $$("AA_MainOverlay").isVisible())
                    {
                        if(delay > 0)
                        {
                            setTimeout(function (){$$("AA_MainOverlay").define("css", "AA_MainOverlayFadeOff");},delay);
                        }
                        else $$("AA_MainOverlay").define("css", "AA_MainOverlayFadeOff");
                        
                        setTimeout(function (){$$("AA_MainOverlay").hide();},2100+delay);
                    }*/
                } catch (msg) {
                    console.error("AA_MainApp.ui.overlay.hide", msg);
                }
            }
        },
        viewport: {
            width: document.documentElement.clientWidth,
            height: document.documentElement.clientHeight
        },

        enableGui: true,
        enableSidebar: true,
        enableNavbar: true,
        enableSidemenu: false,
        enableModuleHeader: true,
        enableSectionHeader: true,
        enablePullToRefresh: "",

        alert: AA_AlertModalDlg,
        message: AA_Message,
        showWaitMessage: AA_ShowWaitMessage,
        hideWaitMessage: AA_HideWaitMessage,
        closeWnd: function(id) {
            console.log("closeWnd", arguments);
            if ($$(id)) $$(id).close();
        },
        sidemenu: {
            bInitialized: false,
            initialize: function() {
                console.log("AA_MainApp.ui.sidemenu.initialize - Inizializzazione sidemenu");

                if (!AA_MainApp.ui.sidemenu.bInitialized) webix.ui(AA_MainApp.ui.sidemenu.content);

                for (i = 0; i < AA_MainApp.ui.sidemenu.content.body.data.length; i++) {
                    if (AA_MainApp.ui.sidemenu.content.body.data[i].section == AA_MainApp.curModule.curSection.id) {
                        AA_MainApp.ui.sidemenu.itemSelected = AA_MainApp.ui.sidemenu.content.body.data[i].id;
                    }
                }

                if (AA_MainApp.ui.sidemenu.itemSelected > 0 && $$("AA_MainSidemenu")) {
                    $$("AA_MainSidemenu").select(AA_MainApp.ui.sidemenu.itemSelected);
                }
                this.bInitialized = true;
            },
            itemSelected: null,
            content: {
                id: "AA_MainSidemenuBox",
                view: "sidemenu",
                width: "300",
                state: function(state) {
                    state.top = 60;
                    state.height -= 60;
                },
                body: {
                    view: "list",
                    id: "AA_MainSidemenu",
                    css: "AA_SidemenuList",
                    borderless: true,
                    scroll: false,
                    template: "<span class='webix_icon mdi mdi-#icon#'></span> #value#",
                    data: [
                        { id: 1, value: "Home", icon: "home", section: "SERVIZI_HOME" },
                        { id: 2, value: "Servizi fiscali", icon: "account", section: "SERVIZI_FISCALI" },
                    ],
                    select: true,
                    type: {
                        height: 40
                    },
                    on: {
                        onAfterSelect: async function(id) {
                            try {
                                let sidemenu = $$("AA_MainSidemenu");

                                item = sidemenu.getItem(id);

                                //Nascondi il pulsante torna alla home se siamo già nella home
                                if (id == 1) {
                                    if ($$("AA_GoBack_Home")) $$("AA_GoBack_Home").hide();
                                } else {
                                    if ($$("AA_GoBack_Home") && !$$("AA_GoBack_Home").isVisible()) $$("AA_GoBack_Home").show();
                                }

                                //console.log("AA_MainApp.sidebar.onAfterSelect("+id+")",item,AA_MainApp.ui.sidemenu.itemSelected);

                                if (AA_MainApp.ui.sidemenu.itemSelected == null) AA_MainApp.ui.sidemenu.itemSelected = item.id;

                                if (AA_MainApp.ui.sidemenu.itemSelected != item.id && AA_MainApp.ui.sidemenu.itemSelected != null && item.type == "section" && item.section != AA_MainApp.curModule.curSection.id) {
                                    AA_MainApp.ui.sidemenu.itemSelected = item.id;
                                    let result = await AA_MainApp.curModule.setCurrentSection(item.section);

                                    if (sidemenu.isVisible()) AA_MainApp.ui.sidemenu.toggle();
                                }

                                if (AA_MainApp.ui.sidemenu.itemSelected != item.id && AA_MainApp.ui.sidemenu.itemSelected != null && item.type == "task") {
                                    //let result = await AA_MainApp.curModule.setCurrentSection(item.section);
                                    //this.itemSelected=item.id;
                                    //console.log("AA_MainApp.sidebar.onAfterSelect("+id+") - task: "+item.task);

                                    //logout
                                    if (item.task == "logout") {
                                        AA_MainApp.logOut();
                                    }
                                    if (sidemenu.isVisible()) AA_MainApp.ui.sidemenu.toggle();
                                }

                                //console.log("AA_MainApp.sidebar.onAfterSelect("+id+")",item, AA_MainApp.ui.sidemenu.itemSelected);

                                return true;
                            } catch (msg) {
                                console.error("AA_MainApp.ui.sidemenu.onAfterSelect(" + id + ")");
                                AA_MainApp.ui.alert(msg);
                                return Promise.reject(msg);
                            }
                        }
                    }
                }
            },
            selectItem: function(id) {
                try {
                    let sidemenu = $$("AA_MainSidemenu");

                    idSelected = sidemenu.getSelectedId();

                    if (idSelected == id) return true;
                    else sidemenu.select(id);

                    return true;
                } catch (msg) {
                    console.error("AA_MainApp.ui.sidemenu.selectItem(" + item + ")");
                    AA_MainApp.ui.alert(msg);
                    return false;
                }

            },
            isVisible: function() {
                if (!this.bInitialized) this.initialize();
                if ($$("AA_MainSidemenuBox").config.hidden) return false;
                return true;
            },
            show: function() {

                if (!this.bInitialized) this.initialize();
                if ($$("AA_MainSidemenuBox")) $$("AA_MainSidemenuBox").show();
            },
            hide: function() {
                if (!this.bInitialized) this.initialize();
                if ($$("AA_MainSidemenuBox")) $$("AA_MainSidemenuBox").hide();
            },
            toggle: function() {
                if (!this.bInitialized) this.initialize();
                if ($$("AA_MainSidemenuBox")) {
                    if ($$("AA_MainSidemenuBox").config.hidden) {
                        //console.log("AA_MainApp.ui.sidemenu.toggle - mostro il sidemenu.");
                        $$("AA_MainSidemenuBox").show();
                    } else {
                        //console.log("AA_MainApp.ui.sidemenu.toggle - nascondo il sidemenu.");
                        $$("AA_MainSidemenuBox").hide();
                    }
                } else {
                    console.log("AA_MainApp.ui.sidemenu.toggle - sidemenu non trovato.");
                }
            },
            refresh: async function() {
                if (!this.bInitialized) this.initialize();
                try {
                    let sidemenu = $$("AA_MainSidemenu");
                    if (!sidemenu) {
                        console.error("AA_MainApp.ui.sidemenu.refresh - sidemenu non trovato");
                        return false;
                    }

                    let sidemenucontent = await AA_VerboseTask("GetSideMenuContent", AA_MainApp.curModule.taskManager);

                    if (sidemenucontent.status.value != 0) {
                        console.error("AA_MainApp.ui.sidemenu.refresh", sidemenucontent.error.content);
                        return false;
                    }

                    let curItemSelected = sidemenu.getSelectedId();
                    sidemenu.parse(sidemenucontent.content.value);

                    if (curItemSelected) {
                        //console.log("AA_MainApp.ui.sidemenu.refresh - curItemSelected: "+curItemSelected);
                        sidemenu.select(curItemSelected);
                    } else {
                        if (AA_MainApp.ui.sidebar.itemSelected) {
                            //console.log("AA_MainApp.ui.sidemenu.refresh - sidebar.ItemSelected: "+AA_MainApp.ui.sidebar.itemSelected);
                            sidemenu.select(AA_MainApp.ui.sidebar.itemSelected);
                        } else {
                            for (i = 0; i < sidemenu.config.data.length; i++) {
                                if (sidemenu.config.data[i].section == AA_MainApp.curModule.curSection.id) {
                                    AA_MainApp.ui.sidemenu.itemSelected = AA_MainApp.ui.sidemenu.content.body.data[i].id;
                                }
                            }

                            if (AA_MainApp.ui.sidemenu.itemSelected > 0) sidemenu.select(AA_MainApp.ui.sidemenu.itemSelected);
                        }
                    }

                    return true;
                } catch (msg) {
                    console.error("AA_MainApp.ui.sidemenu.refresh", msg);
                    return Promise.reject(false);
                }
            }
        },
        sidebar: {
            content: "",
            select: function(id) { $$("AA_MainSidebar").select(id); },
            itemSelected: ""
        },
        navbar: {
            refreshContent: async function() { AA_RefreshNavbarContent(AA_MainApp.curModule.id, false); },
            refresh: async function() { AA_RefreshNavbarContent(AA_MainApp.curModule.id, true); },
            clearAll: function() {

                webix.ui({ id: "AA_navbar_path", view: "layout", minwidth: 120, cols: [{ id: "navbar_spacer", view: "spacer" }], type: "clean", align: "left" }, $$("AA_navbar_box"), $$("AA_navbar_path"));
            },
            refreshUi: function() {
                AA_RefreshNavbarContent(AA_MainApp.curModule.id, true, true);
            },
            content: [{ id: "navbar_spacer", view: "spacer" }]
        },
        MainUI: {
            //titolo dell'App
            appTitle: "<span class='AA_header_title_incipit'>A</span><span class='AA_header_title'>mministrazione</span> <span class='AA_header_title_incipit'>A</span><span class='AA_header_title'>perta</span>",

            //logo
            appLogo: "<a href='https://www.regione.sardegna.it' target='_blank'><img class='AA_Header_Logo' src='immagini/logo_ras.svg' alt='logo RAS' title='www.regione.sardegna.it'/></a>",

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
                    await AA_MainApp.curModule.refreshSectionUi();

                    //Aggiorna il contenuto del sidemenù
                    if (AA_MainApp.ui.enableSidemenu) await AA_MainApp.ui.sidemenu.refresh();

                    //Visualizza un messaggio di successo
                    console.log("MainUI::refreshModuleContentBox(" + bRefreshModuleContent + ") - La visualizzazione del modulo: " + AA_MainApp.curModule.id + " è stata aggiornata.");
                    //AA_MainApp.ui.message("La visualizzazione del modulo: " + AA_MainApp.curModule.id + " è stata aggiornata.", "success");
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
async function AA_DefaultSystemInitialization(params) {
    console.log("Amministrazione Aperta - Inizializzazione di sistema...");

    try {
        //nuova interfaccia
        if (AA_MainApp.ui.enableGui && !AA_MainApp.bEnableLegacy) {

            AA_MainApp.ui.overlay.show();

            //Nasconde lo sfondo di default del body
            let bodyBg = document.getElementById("AA_MainOverlayBg");
            if (bodyBg) {
                bodyBg.style.display = "none";
            }

            //titolo dell'App
            //AA_MainApp.ui.MainUI.appTitle = "<span class='AA_header_title_incipit'>A</span><span class='AA_header_title'>mministrazione</span> <span class='AA_header_title_incipit'>A</span><span class='AA_header_title'>perta</span>";

            //logo
            //AA_MainApp.ui.MainUI.appLogo = "<a href='https://www.regione.sardegna.it' target='_blank'><img class='AA_Header_Logo' src='immagini/logo_ras.svg' alt='logo RAS' title='www.regione.sardegna.it'/></a>";

            //web site url 
            if (params && params.web_url) {
                AA_MainApp.web_url = params.web_url;
            }

            //policy url 
            if (params && params.privacy_policy_url) {
                AA_MainApp.privacy_policy_url = params.privacy_policy_url;
            }

            if (typeof cookieconsent === 'object') {
                console.log("AA_DefaultSystemInitialization - abilito la gestione dei cookie.");
                cookieconsent.run({ "notice_banner_type": "interstitial", "consent_type": "express", "palette": "dark", "language": "it", "page_load_consent_levels": ["strictly-necessary"], "notice_banner_reject_button_hide": false, "preferences_center_close_button_hide": false, "page_refresh_confirmation_buttons": false, "website_name": AA_MainApp.web_url, "website_privacy_policy_url": AA_MainApp.privacy_policy_url });
                //console.log("AA_DefaultSystemInitialization", cookieconsent);
            }

            //inizializza l'interfaccia principale
            await AA_MainApp.ui.MainUI.setup();

            //pull to refresh
            if (AA_MainApp.ui.enablePullToRefresh != "") {
                console.log("AA_DefaultSystemInitialization - abilito il pull to refresh sull'elemento: ", AA_MainApp.ui.enablePullToRefresh);
                const ptr = PullToRefresh.init({
                    mainElement: 'body',
                    triggerElement: AA_MainApp.ui.enablePullToRefresh,
                    instructionsReleaseToRefresh: "Rilascia per aggiornare...",
                    instructionsPullToRefresh: "Trascina per aggiornare...",
                    instructionsRefreshing: "Aggiornamento...",
                    onRefresh() {
                        window.location.reload();
                    }
                });
            }

            let result = await AA_MainApp.ui.MainUI.refresh();
            if (result) AA_MainApp.ui.overlay.hide();

            console.log("Amministrazione Aperta - Inizializzazione di sistema conclusa.");

            return true;
        }
        //-------------------------------
    } catch (msg) {
        console.error(msg);
        return false;
    }

    if (AA_MainApp.bEnableLegacy) {
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

    return null;
}

//Imposta il modulo corrente
async function AA_SetCurrentModule(id) {
    //console.log("System::AA_SetCurrentModule("+id+")");
    var module = AA_MainApp.getModule(id);
    //console.log("AA_SetCurrentModule",module);
    if (module != null && module.isValid()) {
        //console.log("System::AA_SetCurrentModule("+id+")")
        //inizializza il modulo
        var result = false;
        try {
            result = await module.initialize();

            //Visualizza un messaggio di successo
            console.log("System::AA_SetCurrentModule(" + id + ") - Il modulo: " + id + " è stato inizializzato correttamente.");
            //AA_MainApp.ui.message("Il modulo: " + id + " è stato inizializzato correttamente.", "success");
        } catch (msg) {
            console.error("System::AA_SetCurrentModule(" + id + ") - errore: ", msg);
            //return Promise.reject(msg);
            return false;
        }

        if (result === true) {
            //imposta il modulo corrente
            AA_MainApp.curModule = module;

            //Imposta il layout principale del modulo
            AA_MainApp.ui.MainUI.setModuleContentBox(module.ui.layout);

            //Aggiorna la visualizzazione del contenuto del modulo
            await AA_MainApp.ui.MainUI.refreshModuleContentBox(false);

            return true;
        }

        console.error("System::AA_SetCurrentModule(" + id + ") - errore durante l'inizializzazione del modulo: ", module);
        return false;
    }

    console.error("System::AA_SetCurrentModule(" + id + ") - Il modulo: " + id + " non è stato trovato tra i moduli registrati.");
    return Promise.reject("Il modulo: " + id + " non è stato trovato tra i moduli registrati.");
}

//Default system initialization
if (webix) {
    webix.ready(async function() {
        try {
            setTimeout(async function() { await AA_MainApp.bootUpFunction() }, 1000);
        } catch (msg) {
            console.error(msg);
        }
    });
}


//Carica le informazioni per l'interfaccia principale
async function AA_RefreshMainUi(params) {
    console.log("System::AA_RefreshMainUi()");

    try {

        //Parametri url
        const urlParams = new URLSearchParams(window.location.search);
        //console.log("System::AA_RefreshMainUi() - parametri: ", urlParams);

        //Recupero i dati della piattaforma
        var getAppStatus = await AA_VerboseTask("GetAppStatus", AA_MainApp.taskManager, "module=" + urlParams.get("module") + "&mobile=" + AA_MainApp.device.isMobile + "&viewport_width=" + AA_MainApp.ui.viewport.width + "&viewport_height=" + AA_MainApp.ui.viewport.height);

        if (getAppStatus.status.value == "0") {
            if (getAppStatus.error.value != "") AA_MainApp.ui.message(getAppStatus.error.value);

            //Aggiorna i taskmanager dei moduli
            var modules = JSON.parse($(getAppStatus.content.value)[2].innerText);
            //console.log("System::AA_RefreshMainUi() - moduli",modules);
            if (typeof modules != "undefined") {
                for (curMod in modules) {
                    let module = AA_MainApp.getModule(modules[curMod].id);
                    //console.log("System::AA_RefreshMainUi() - modulo", module, modules[curMod]);

                    if (module.id !== "AA_MODULE_DUMMY") {
                        module.taskManager = modules[curMod].remote_folder + "/taskmanager.php";
                        module.ui.icon = modules[curMod].icon;
                        module.ui.name = modules[curMod].name;
                    }
                }
            }
            //--------------------------------------------------------

            //Aggiorna il nome utente
            var user = $(getAppStatus.content.value)[0].childNodes[0].innerText;
            if (user.length > 0 && $$("AA_icon_user")) {
                $$("AA_icon_user").define("tooltip", user);
                $$("AA_icon_user").define("data", { "user_image_path": $(getAppStatus.content.value)[0].childNodes[5].nextSibling.data });
                AA_MainApp.ui.user = user;
            }

            //Aggiorna la sidebar
            if (AA_MainApp.ui.enableSidebar && $$("AA_MainSidebar")) {
                var sidebar = JSON.parse($(getAppStatus.content.value)[1].innerText);

                if (typeof sidebar != "undefined") {
                    //console.log("System::AA_RefreshMainUi() sidebar: ", $(getAppStatus.content.value)[1].attributes["itemSelected"]);

                    let itemSelected = "";
                    if ($(getAppStatus.content.value)[1].attributes["itemSelected"]) {
                        itemSelected = $(getAppStatus.content.value)[1].attributes["itemSelected"].nodeValue;
                    }

                    $$("AA_MainSidebar").parse(sidebar);

                    AA_MainApp.ui.sidebar.content = sidebar;

                    if (AA_MainApp.ui.sidebar.itemSelected == "" && itemSelected != "") AA_MainApp.ui.sidebar.itemSelected = itemSelected;

                    if (AA_MainApp.ui.sidebar.itemSelected != "") {
                        //Seleziona l'item corrente
                        AA_MainApp.ui.sidebar.select(AA_MainApp.ui.sidebar.itemSelected);
                        return true;
                    } else {
                        //Seleziona il modulo di default
                        if (AA_MainApp.defaultSidebarModule) {
                            AA_MainApp.ui.sidebar.select(AA_MainApp.defaultSidebarModule);
                            return true;
                        }
                    }
                }
            }

            //seleziona il modulo default
            if (AA_MainApp.defaultModule) await AA_MainApp.setCurrentModule(AA_MainApp.defaultModule);

            return true;

        } else {
            console.error("AA_RefreshMainUi() - " + getAppStatus.error.value);
            if (getAppStatus.error.value != "" && getAppStatus.status.value > -2) AA_MainApp.ui.alert(getAppStatus.error.value);
            return false;
        }
    } catch (msg) {
        AA_MainApp.ui.hideWaitMessage();
        AA_MainApp.ui.alert(msg);
        console.error("AA_RefreshMainUi() - " + msg);
        return Promise.reject(msg);
    }
}

//Compone l'interfaccia principale
async function AA_SetupMainUi() {
    console.log("System::AA_SetupMainUi()");

    if (webix.CustomScroll && !webix.env.touch) webix.CustomScroll.init();

    //Verifica se si sta visualizzando da un cellulare
    if (webix.env.mobile) {
        AA_MainApp.device.isMobile = 1;
        AA_MainApp.ui.viewport.width = document.documentElement.clientWidth;
        AA_MainApp.ui.viewport.height = document.documentElement.clientHeight;

        //Cambia il tema css
        if ($("#webix_style")) {
            let old_style = $("#webix_style").attr("href");
            let new_style = old_style.replace("webix.min.css", "skins/mini.min.css");
            $("#webix_style").attr("href", new_style);
        }
    } else {
        AA_MainApp.device.isMobile = 0;
        AA_MainApp.ui.viewport.width = document.documentElement.clientWidth;
        AA_MainApp.ui.viewport.height = document.documentElement.clientHeight;
    }

    let minWidth = AA_MainApp.ui.viewport.width;

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
                    { view: "label", width: 200, align: "left", template: AA_MainApp.ui.MainUI.appLogo },
                    {},
                    { view: "label", label: AA_MainApp.ui.MainUI.appTitle, align: "center", minWidth: 500 },
                    {},
                    { view: "spacer", width: "36" },
                    { id: "AA_icon_user", view: "icon", type: "icon", width: 60, css: "AA_header_icon_color", icon: "mdi mdi-account" },
                    { id: "AA_icon_logout", view: "icon", type: "icon", width: 60, css: "AA_header_icon_color", icon: "mdi mdi-logout", tooltip: "Esci", click: AA_MainApp.logOut },
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
    if (!AA_MainApp.ui.overlay.isVisible()) {
        webix.message({
            text: msg,
            type: type,
            expire: timeout
        });
    }
}

//Funzione per la visualizzazione del messaggio di attesa
function AA_ShowWaitMessage(msg = "Attendere prego...", type = "info") {
    if (typeof AA_MainApp.ui.waitMessage == "string") {
        console.log("AA_ShowWaitMessage - messaggio già impostato.");
        return;
    }

    if (!AA_MainApp.ui.overlay.isVisible()) {
        AA_MainApp.ui.waitMessage = webix.message({
            text: "<span class='lds-dual-ring'></span><span style='margin-left: .5em'>" + msg + "</span>",
            type: type,
            expire: -1,
            id: "AA_WaitMessage"
        });
    }
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

        //console.log("AA_Get", arguments);

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
        //console.log("AA_Task", arguments);

        if (taskManagerURL == "") taskManagerURL = AA_MainApp.curModule.taskManager;

        let url = taskManagerURL + "?task=" + task;
        if (typeof params == "string" && params != "") url += "&" + params;

        //passa la dimensione della viewport
        url += "&vw=" + document.documentElement.clientWidth;
        url += "&vh=" + document.documentElement.clientHeight;
        url += "&mobile=" + AA_MainApp.device.isMobile;

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
        if (verbose) AA_MainApp.ui.hideWaitMessage();

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
            AA_MainApp.ui.hideWaitMessage();
            AA_MainApp.ui.overlay.show();
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
        //Se è visibile l'overlay non scrive nulla
        if (AA_MainApp.ui.overlay.isVisible()) {
            return await AA_Task(task, taskManagerURL, params, postParams, false, raw);
        } else {
            return await AA_Task(task, taskManagerURL, params, postParams, true, raw);
        }
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
            if (result.status.value > -2) AA_MainApp.ui.alert(result.error.value);
            return false;
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
                width: 300,
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
                height: 38,
                cols: [
                    {},
                    {
                        id: "AA_UserAuth_Apply_btn",
                        view: "button",
                        label: "Accedi",
                        hotkey: "enter",
                        type: "icon",
                        css: "webix_primary ",
                        icon: "mdi mdi-login",
                        width: 100,
                        align: "center",
                        params: params,
                        click: async function() {
                            try {
                                //console.log("AA_UserAuth", arguments);

                                if (!$$("AA_UserAuth_Form").validate()) {
                                    return;
                                }

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
                                    console.error("AA_UserAuthDlg - " + result.error.value);
                                }
                            } catch (msg) {
                                console.error("AA_MainApp.AA_UserAuth", msg);
                                AA_MainApp.ui.alert(msg);
                                return;
                            }
                        }
                    },
                    {}
                ]
            }

            let body = {
                view: "layout",
                type: "clean",
                rows: [{
                    id: "AA_UserAuth_Form",
                    view: "form",
                    borderless: true,
                    elementsConfig: { labelWidth: 90, labelAlign: "left", labelPosition: "top", iconPosition: "left" },
                    elements: [{
                            view: "text",
                            icon: "mdi mdi-account",
                            name: "user",
                            bottomLabel: "Inserisci il tuo nome utente o la tua email.",
                            required: true,
                            label: "utente"
                        },
                        {
                            view: "text",
                            type: "password",
                            icon: "mdi mdi-key",
                            name: "pwd",
                            bottomLabel: "Inserisci la tua password.",
                            required: true,
                            label: "password"
                        },
                        {
                            type: "space",
                            css: { "background-color": "transparent" },
                            rows: [
                                apply_btn
                            ]
                        },
                        {
                            type: "clean",
                            borderless: true,
                            cols: [{
                                    template: "<div style='display:flex;justify-content:center;align-items:center;height:100%; font-size: smaller'><a onClick='AA_MainApp.resetPwd()'>recupero credenziali</a></div>",
                                    tooltip: "Fai click qui se hai dimenticato il nome utente, la password o entrambi."
                                },
                                {
                                    view: "checkbox",
                                    id: "remember_me",
                                    name: "remember_me",
                                    label: "Ricordami",
                                    //labelRight:"Ricordami",
                                    labelAlign: "right",
                                    labelPosition: "right",
                                    labelWidth: 95,
                                    align: "right",
                                    bottomPadding: 0,
                                    tooltip: "Se abilitato, ricorda l'utente (su questo browser) per 30 giorni.",
                                    value: 0,
                                }
                            ]
                        }
                    ]
                }]
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

//Recupero credenziali
async function AA_UserResetPwd(params = null) {
    try {
        if ($$("AA_UserResetPwdDlg")) {
            $$("AA_UserResetPwdDlg").show();
        } else {
            let resetpwd_dlg = {
                id: "AA_UserResetPwdDlg",
                view: "window",
                height: 310,
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
                    template: "Recupero credenziali"
                }]
            }

            let apply_btn = {
                view: "layout",
                height: 38,
                cols: [{
                        view: "button",
                        label: "Annulla",
                        hotkey: "enter",
                        type: "icon",
                        icon: "mdi mdi-close",
                        hotkey: "esc",
                        width: 100,
                        align: "left",
                        click: function() { $$("AA_UserResetPwdDlg").close() }
                    },
                    {},
                    {
                        id: "AA_UserResetPwd_Apply_btn",
                        view: "button",
                        label: "Invia",
                        hotkey: "enter",
                        type: "icon",
                        css: "webix_primary",
                        icon: "mdi mdi-email-send",
                        width: 100,
                        align: "center",
                        params: params,
                        click: async function() {
                            try {
                                //console.log("AA_UserAuth", arguments);
                                if (!$$("AA_UserResetPwd_Form").validate()) {
                                    return;
                                }

                                let form_data = $$("AA_UserResetPwd_Form").getValues();

                                if (form_data['email'].length < 1) {
                                    AA_MainApp.ui.alert("Occorre inserire una mail valida.");
                                    return;
                                }

                                //console.log("AA_UserAuth",form_data);

                                let result = await AA_VerboseTask("ResetPassword", AA_MainApp.taskManager, "", form_data);

                                //console.log("AA_UserAuth", result);
                                if (result.status.value == 0) {
                                    $$("AA_UserResetPwdDlg").close();
                                    AA_MainApp.ui.alert(result.content.value, "Info", "");
                                    return true;
                                } else {
                                    AA_MainApp.ui.alert(result.error.value);
                                    return false;
                                }
                            } catch (msg) {
                                console.error("AA_MainApp.AA_UserResetPwdDlg", msg);
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
                rows: [{
                    id: "AA_UserResetPwd_Form",
                    view: "form",
                    borderless: true,
                    rules: {
                        $all: AA_MainApp.curModule.eventHandlers['defaultHandlers'].validateForm
                    },
                    elementsConfig: { labelWidth: 90, labelAlign: "left", labelPosition: "top", iconPosition: "left" },
                    elements: [{
                            view: "template",
                            template: "<p>Inserisci l'indirizzo email associato al tuo account.<br>il sistema ti invierà delle nuove credenziali per l'accesso alla piattaforma.</p>"
                        },
                        {
                            view: "text",
                            icon: "mdi mdi-email",
                            name: "email",
                            bottomLabel: "Inserisci l'indirizzo email associato all'account.",
                            required: true,
                            label: "email"
                        },
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

            resetpwd_dlg['head'] = header_box;
            resetpwd_dlg['body'] = body;

            webix.ui(resetpwd_dlg).show();
        }
    } catch (msg) {
        console.error("AA_UserResetPwdDlg() - " + msg);
        AA_MainApp.ui.alert(msg);
        return Promise.reject(msg);
    }
}

//LogOut
async function AA_LogOut(params = null) {
    try {

        //Log out
        let result = await AA_VerboseTask("UserLogOut", AA_MainApp.taskManager);

        if (result.status.value == 0) {

            //ricarica la pagina
            window.location.reload();

            return;
        } else {
            AA_MainApp.ui.alert(result.error.value);
            return;
        }
    } catch (msg) {
        console.error("AA_MainApp.logOut", msg);
        AA_MainApp.ui.alert(msg);
        return Promise.reject(msg);
    }
}

//Registrazione
function AA_UserRegister(params = null) {
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
                                    AA_MainApp.ui.alert(result.content.value, "Info", "");
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
                rows: [{
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
                        elements: [{
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
                                bottomLabel: "Fai <a href='#'>click qui</a> per leggere l'informativa su come trattiamo i tuoi dati e su come puoi esercitare i tuoi diritti.",
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
                    }
                ]
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