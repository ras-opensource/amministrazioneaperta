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
                }
            }
        }
        if(AA_MainApp.ui.enableSidemenu)
        {
            for(let item of AA_MainApp.ui.sidemenu.aMenuData)
            {
                //console.log("eventHandlers.defaultHandlers.ModuleBoxClick", item);
                if(item.module==arguments[0])
                {
                    AA_MainApp.ui.sidemenu.selectItem(item.id);
                }
            }
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

AA_MainApp.registerModule(<?php echo AA_HomeModule::AA_ID_MODULE?>);

