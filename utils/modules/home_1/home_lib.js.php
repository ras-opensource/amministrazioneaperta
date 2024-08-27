<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("home_lib.php");
header('Content-Type: text/javascript');
?>

//Modulo HOME
var <?php echo AA_HomeModule::AA_ID_MODULE?> = new AA_Module("<?php echo AA_HomeModule::AA_ID_MODULE?>", "HOME");
<?php echo AA_HomeModule::AA_ID_MODULE?>.valid = true;
<?php echo AA_HomeModule::AA_ID_MODULE?>.content = {};
<?php echo AA_HomeModule::AA_ID_MODULE?>.contentType = "json";
<?php echo AA_HomeModule::AA_ID_MODULE?>.ui.module_content_id = "<?php echo AA_HomeModule::AA_UI_MODULE_MAIN_BOX?>";


//Handler click modulo box
<?php echo AA_HomeModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].ModuleBoxClick = function() {
    try 
    {
        //console.log("eventHandlers.defaultHandlers.ModuleBoxClick", this, arguments);
        if(AA_MainApp.ui.enableSidebar)
        {
            for(let item of AA_MainApp.ui.sidebar.content)
            {
                //console.log("eventHandlers.defaultHandlers.ModuleBoxClick", item);
                if(item.module==arguments[0])
                {
                    AA_MainApp.ui.sidebar.select(item.id);
                    return;
                }
            }
        }

        if(AA_MainApp.ui.enableSidemenu && AA_MainApp.ui.sidemenu.sType=='module')
        {
            for(let item of AA_MainApp.ui.sidemenu.aMenuData)
            {
                //console.log("eventHandlers.defaultHandlers.ModuleBoxClick", item);
                if(item.module==arguments[0])
                {
                    AA_MainApp.ui.sidemenu.selectItem(item.id);
                    return;
                }
            }
        }

        if((!AA_MainApp.ui.enableSidemenu && !AA_MainApp.ui.enableSidebar) || (AA_MainApp.ui.sidemenu.sType=='section' && AA_MainApp.ui.enableSidemenu))
        {
            AA_MainApp.setCurrentModule(arguments[0])
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.CoalizioneDblClick", msg, this);
    }
};

//Handler import legacy user
<?php echo AA_HomeModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].ImportLegacyUser = async function() {
    try 
    {
        console.log("eventHandlers.defaultHandlers.ImportLegacyUser", this, arguments);
        if (arguments[0].params && arguments[0].task)
        {
            let import_user = await this.doTask(arguments['0']);
            if(import_user)
            {
                //console.log("eventHandlers.defaultHandlers.ImportLegacyUser - refresh");
                this.refreshCurSection();
            }
            else
            {
                console.error(this.name + "eventHandlers.defaultHandlers.ImportLegacyUser - errore nell'iportazione dell'utente.", arguments[0].params.id);
                return false;
            }
        }   
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.ImportLegacyUser", msg, this);
    }
};

//Handler legacy modules
<?php echo AA_HomeModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].ModuleLegacyBoxClick = async function() {
    try 
    {
        console.log("eventHandlers.defaultHandlers.ModuleLegacyBoxClick", this, arguments);
        let wnd = window.open("<?php echo AA_Config::AA_LEGACY_ROOT_URL?>/sso_legacy_login.php?SSO_AUTH_TOKEN="+arguments[0]['SSO_AUTH_TOKEN']+"&url="+arguments[0]['url']);
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.ModuleLegacyBoxClick", msg, this);
    }
};

//Imposta  la data di soppressione se e' quella predefinita
<?php echo AA_HomeModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].onStructSuppress = function() {
    try 
    {
        if(arguments[2]=='user')
        {
            if(arguments[0]==1)
            {
                let form=this.getFormView();
                if(form)
                {
                    let data_soppressione=String(form.getValues()["data_soppressione"]).substr(0,10);
                    
                    console.log(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.onStructSuppress",data_soppressione);

                    let now=new Date();
                    if(data_soppressione=="9999-12-31")
                    {
                        values={"data_soppressione": now.toISOString().substr(0,10)}
                        form.setValues(values,true);
                    }
                }
            }
            else
            {
                let form=this.getFormView();
                if(form)
                {
                    let values={"data_soppressione": "9999-12-31"}
                    form.setValues(values,true);
                }
            }
         }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.onStructSuppress", msg, this);
    }
};

//Struct filter
<?php echo AA_HomeModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].onFilterStructChange = async function() {
    try 
    {
        console.log("eventHandlers.defaultHandlers.onFilterStructChange", this, arguments);

        let TreeView=$$(this.config.tree_view_id);
        let search=this;
        if(TreeView)
        {
            TreeView.filter(function(obj){return obj.value.toLowerCase().indexOf(search.getValue().toLowerCase()) !== -1;});
        }

    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.onFilterStructChange", msg, this);
    }
};

AA_MainApp.registerModule(<?php echo AA_HomeModule::AA_ID_MODULE?>);

