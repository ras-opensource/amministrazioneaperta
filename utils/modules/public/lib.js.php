<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("lib.php");
header("Content-Type: text/javascript");
?>
//modulo
let <?php echo AA_PublicModule::AA_ID_MODULE?> = new AA_Module("<?php echo AA_PublicModule::AA_ID_MODULE?>", "<?php echo AA_PublicModule::AA_MODULE_DEFAULT_NAME?>"); //L'identificativo del modulo deve essere uguale a quello utilizzato nella relativa classe di gestione php
<?php echo AA_PublicModule::AA_ID_MODULE?>.valid = true;
<?php echo AA_PublicModule::AA_ID_MODULE?>.content = {};
<?php echo AA_PublicModule::AA_ID_MODULE?>.contentType = "json";
<?php echo AA_PublicModule::AA_ID_MODULE?>.taskManager = "<?php echo AA_Public_Const::AA_MODULES_PATH?>/public/taskmanager.php";
<?php echo AA_PublicModule::AA_ID_MODULE?>.name = "<?php echo AA_PublicModule::AA_MODULE_DEFAULT_NAME?>";
<?php echo AA_PublicModule::AA_ID_MODULE?>.icon = "<?php echo AA_PublicModule::AA_MODULE_DEFAULT_ICON?>";
<?php echo AA_PublicModule::AA_ID_MODULE?>.ui.module_content_id = "<?php echo AA_PublicModule::AA_UI_MAIN_MODULE_BOX?>"; //L'identificativo deve essere uguale a quello impostato nel layout principale del modulo nella relativa classe di gestione php

//disattivazione sezioni interfaccia standard
AA_MainApp.ui.enableSidebar=false;
AA_MainApp.ui.enableSidemenu=false;
AA_MainApp.ui.enableModuleHeader=false;
AA_MainApp.ui.enableSectionHeader=false;
//-----------------------------------

//Registrazione modulo
AA_MainApp.registerModule(<?php echo AA_PublicModule::AA_ID_MODULE?>);
//--------------------

//setup function
AA_MainApp.ui.MainUI.setup=function()
{
    console.log("<?php echo AA_PublicModule::AA_ID_MODULE?>::AA_SetupMainUi()");

    //Verifica se si sta visualizzando da un cellulare
    //console.log("<?php echo AA_PublicModule::AA_ID_MODULE?>::AA_SetupMainUi() - user agent: ",window.navigator.userAgent);
    if(webix.env.mobile || window.navigator.userAgent.indexOf("iPhone") >-1)
    {
        AA_MainApp.device.isMobile=1;
        AA_MainApp.ui.viewport.width = document.documentElement.clientWidth;
        AA_MainApp.ui.viewport.height = document.documentElement.clientHeight;

        //Cambia il tema css
        if($("#webix_style"))
        {
            let old_style=$("#webix_style").attr("href");
            let new_style=old_style.replace("webix.min.css","skins/mini.min.css");
            $("#webix_style").attr("href",new_style);
        }
    }
    else
    {
        AA_MainApp.device.isMobile=0;
        AA_MainApp.ui.viewport.width = document.documentElement.clientWidth;
        AA_MainApp.ui.viewport.height = document.documentElement.clientHeight;
    }

    let minWidth=AA_MainApp.ui.viewport.width;

    //Imposta la posìzione dei messaggi
    webix.message.position = "bottom";

    if (webix.CustomScroll && !webix.env.touch) webix.CustomScroll.init();

    //Main interface
    webix.ui({
        //container: "AA_MainAppBox",
        id: "AA_MainAppBox_content",
        rows: 
        [
            {
                view: "layout",
                type: "clean",
                cols: 
                [
                    {
                        id: "AA_ModuleContentBox",
                        view: "scrollview",
                        scroll: "auto",
                        minWidth: minWidth,
                        borderless: true,
                        type: "clean",
                        body:
                        {
                            view: "flexlayout",
                            type:"wide"
                            borderless: true,
                            cols: 
                            [
                                {
                                    id: "AA_ModuleContent",
                                    template: "",
                                    type: "clean"
                                }
                            ]    
                        }
                    }
                ]
            }
        ]
    });
}

//Main ui refresh function
AA_MainApp.ui.MainUI.refresh= async function (params)
{
    console.log("<?php echo AA_PublicModule::AA_ID_MODULE?>::refresh()");
    try 
    {
        return await AA_MainApp.setCurrentModule("<?php echo AA_PublicModule::AA_ID_MODULE?>");
    }
    catch (msg) 
    {
        AA_MainApp.ui.hideWaitMessage();
        AA_MainApp.ui.alert(msg);
        console.error("<?php echo AA_PublicModule::AA_ID_MODULE?>::refresh() - " + msg);
        return Promise.reject(msg);
    }
}
