<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("home_lib.php");
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
        for(let item of AA_MainApp.ui.sidebar.content)
        {
            //console.log("eventHandlers.defaultHandlers.ModuleBoxClick", item);
            if(item.module==arguments[0])
            {
                AA_MainApp.ui.sidebar.select(item.id);
            }
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.CoalizioneDblClick", msg, this);
    }
};

AA_MainApp.registerModule(<?php echo AA_HomeModule::AA_ID_MODULE?>);

