/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Modulo HOME
var AA_home_module=new AA_Module("AA_MODULE_HOME","Modulo Home");
AA_home_module.valid=true;
//AA_home_module.taskManager = "utils/modules/home_1/home_ops.php"; //URL del task manager
AA_home_module.ui.icon = "mdi mdi-home";
AA_home_module.ui.name = "Home page";
AA_home_module.ui.module_content_id = "AA_home_module_layout";

//Registrazione modulo
AA_MainApp.registerModule(AA_home_module);

//Gestisce gli eventi della navbar per il modulo Home
async function AA_HomeNavbarEvent(event,param,element)
{
    //console.log("Home::AA_HomeNavbarEvent()",arguments);
    
    try
    {
        let obj=$$(element.attributes.view_id.value);
        
        //verifica se esiste il panel indicato sull'oggetto
        if(obj)
        {
            let panel=obj.config.id_panel;
            if($$(panel))
            {
              
                if(!$$(panel).isVisible())
                {
                    //rende visibile il pannello
                    $$(panel).show();
                    AA_MainApp.ui.MainUI.setModuleSectionHeaderContent({title : $$(panel).config.name});
                    if(!$$(panel).config.refreshed)
                    {
                        console.log("Home::AA_HomeNavbarEvent() - Aggiorno il contenuto del pannello: "+panel);
                        await AA_home_module.refreshUiObject(panel);
                    }

                    //aggiorno la navbar
                    console.log("Home::AA_HomeNavbarEvent() - Aggiorno la navbar.");
                    await AA_MainApp.ui.navbar.refresh();

                    //Aggiorno il contenuto del menu contestuale
                    console.log("Home::AA_HomeNavbarEvent() - Aggiorno il menu contestuale.");
                    await AA_HomeRefreshActionMenuContent();
                }

                return true;
            }
            else
            {
                console.error("Home::AA_HomeNavbarEvent() - pannello non definito: ",panel,arguments);
                return false;
            }
        }
        else
        {
            console.error("Home::AA_HomeNavbarEvent() - oggetto non definito: ",obj,arguments);
            return false;
        }
    }
    catch(msg)
    {
        console.error("Home::AA_HomeNavbarEvent() - errore: ",msg,arguments);
        AA_MainApp.ui.alert(msg);
        return Promise.reject(msg);
    }
}

//Restituisce l'id della vista attiva per il modulo Home
function AA_HomeGetActiveView()
{
    if($$("AA_home_module_layout")) return $$("AA_home_module_layout").getActiveId();
    else return "";
}

//Aggiorna un componente grafico della Home
async function AA_HomeRefreshUiObject(idObj, bRefreshContent=false)
{
    try
    {
        //console.log("Home::AA_HomeRefreshUiObject("+idObj+")");
        if($$(idObj))
        { 
            if(bRefreshContent || !$$(idObj).config.initialized);
            {
                //aggiorna il contenuto dell'oggetto
                let result = await AA_home_module.refreshContent(idObj);
                if(result != 1)
                {
                    console.error("Home::AA_HomeRefreshUiObject("+idObj+") - errore: ", result);
                    return Promise.reject(result);
                }
            }
            
            //Aggiorna il componente grafico
            obj=AA_home_module.content[idObj];
            if(obj)
            {
                
                webix.ui(obj,$$("AA_home_module_layout"),$$(idObj));
                $$(idObj).define("initialized",true);
                    
                return true;
            }
            else
            {
                console.error("Home::AA_HomeRefreshUiObject("+idObj+") - Oggetto non valido: ",obj);
                return false;
            }
        }
        return true;
    }
    catch(msg)
    {
        console.error("Home::AA_HomeRefreshUiObject("+idObj+") - "+msg);
        return Promise.reject(msg);
    }
}

//Funzione di inizializzazione del modulo home
async function AA_HomeInitialize()
{
    try
    {
        var home_layout = await AA_VerboseTask("GetLayout",AA_home_module.taskManager);
        
        if (home_layout.status.value == "0") 
        {
            if(home_layout.error.value) AA_MainApp.ui.message(home_layout.error.value);
            if(home_layout.content.value)
            {
                layout=home_layout.content.value;
                contentType=home_layout.content.type;
            }
        }
        else
        {
            return Promise.reject(home_layout.error.value);
        }
        
        //Imposta l'interfaccia
        if(layout) 
        {
            if(contentType =="json") AA_MainApp.ui.MainUI.setModuleContentBox(layout);
            else
            {
                AA_MainApp.ui.MainUI.setModuleContentBox("<div id='AA_Home_Main_Content_Box' class='AA_Box_Border AA_Box_Rounded AA_Box_Shadow' style='height: 97.5%; padding: 2px; margin: 5px'"+layout+"</div>");
            }
            return true;
        }
        else return Promise.reject("Errore generico: "+layout);
        
    }
    catch(msg)
    {
        console.error("Home::AA_HomeInitialize()",msg);
        return Promise.reject(msg);
    }
}

//Funzione di refresh del contenuto del modulo home
async function AA_HomeRefreshContent(section="")
{
    try
    {
        let params=[{section: AA_home_module.getActiveView()}];
        if(section != "") params.section=section;
        
        var home_module_content = await AA_VerboseTask("GetSectionContent",AA_home_module.taskManager,params);
        
        if (home_module_content.status.value == "0") 
        {
            if(home_module_content.error.value) AA_MainApp.ui.message(home_module_content.error.value);
            
            //console.log("Nuovo contenuto del modulo (scaricato): ", home_module_content.content.value);
            
            if(home_module_content.content.value)
            {
                if(Array.isArray(home_module_content.content.value))
                {
                    for (const curItem of home_module_content.content.value)
                    {
                        AA_home_module.content[curItem.id]=curItem.content;
                    }
                }
                else AA_home_module.content[home_module_content.content.value.id]=home_module_content.content.value.content;
                
                AA_home_module.contentType=home_module_content.content.type;
                
                console.log("Nuovo contenuto del modulo: ", AA_home_module.content);
                return 1;
            }
            else return Promise.reject("Contenuto del modulo non trovato.");
        }
        else
        {
            return Promise.reject(home_module_content.error.value);
        }
    }
    catch(msg)
    {
        console.error("Home::AA_HomeRefreshContent()",msg);
        return Promise.reject(msg);
    }
}

//Funzione di refresh dell'interfaccia del modulo home
async function AA_HomeRefreshUI()
{
    try
    {
        for (const curItem in AA_home_module.content)
        {
            if($$(curItem))
            {
                console.log("Home::AA_HomeRefreshUI() - Aggiorno l'interfaccia", AA_home_module.content[curItem]);
                webix.ui(AA_home_module.content[curItem],$$("AA_home_module_layout"),$$(curItem));
            }
            else
            {
                if($$("AA_home_module_layout"))
                {
                    $$("AA_home_module_layout").addView(AA_home_module.content[curItem]);
                }
                else $$("AA_Module_Content").addView(AA_home_module.content[curItem]);
            }
        }

        //Aggiorna il titolo del modulo
        AA_MainApp.ui.MainUI.setModuleHeaderContent({icon: AA_home_module.ui.icon, title: AA_home_module.ui.name});                

        //rinfresca il contenuto del pannello attivo
        activeView=$$("AA_home_module_layout").getActiveId();
        if($$(activeView))
        {
            AA_MainApp.ui.MainUI.setModuleSectionHeaderContent({title: $$(activeView).config.name});
            AA_home_module.refreshUiObject(activeView);
        }

        //aggiorno la navbar
        await AA_MainApp.ui.navbar.refresh();
        
        //Aggiorno il contenuto del menu contestuale
        await AA_HomeRefreshActionMenuContent();
        
        return 1;
    }
    catch(msg)
    {
        console.error("Home::AA_HomeRefreshUI()",msg);
        return Promise.reject(msg);
    }
}

async function AA_HomeRefreshActionMenuContent()
{
    try
    {
        //console.log("Home::AA_HomeRefreshActionMenuContent()", arguments);
        let result = await AA_VerboseTask("GetActionMenu",AA_home_module.taskManager,[{section: AA_home_module.getActiveView()}]);
        
        //console.log("Home::AA_HomeRefreshActionMenuContent()",result);
        
        if(result.status.value==0)
        {
            AA_home_module.ui.activeMenuContent=result.content.value;
            return true;
        }
        else
        {
            console.error("Home::AA_HomeRefreshActionMenuContent() - errore: ",result.error.value);
            return Promise.reject(result.error.value);
        }
    }
    catch(msg)
    {
        console.error("Home::AA_HomeRefreshActionMenuContent",arguments);
        AA_MainApp.ui.alert(msg);
        return Promise.reject(msg);
    }
}

async function AA_HomeActionMenuEvent()
{
    try
    {
        //console.log("Home::AA_HomeActionMenuEvent",arguments);
        if(AA_MainApp.ui.MainUI.activeMenu)
        {
            item=AA_MainApp.ui.MainUI.activeMenu.getItem(arguments[0]);
            if(typeof window[item.handler] == "function")
            {
                if(Array.isArray(item.handler_params)) window[item.handler](...item.handler_params);
                else window[item.handler]();
            }
        }
        return true;
    }
    catch(msg)
    {
        console.error("Home::AA_HomeActionMenuEvent",arguments);
        AA_MainApp.ui.alert(msg);
        return Promise.reject(msg);
    }
}
