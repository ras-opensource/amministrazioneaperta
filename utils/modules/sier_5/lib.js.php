<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("lib.php");
?>
//modulo
var <?php echo AA_SierModule::AA_ID_MODULE?> = new AA_Module("<?php echo AA_SierModule::AA_ID_MODULE?>", "SIER");
<?php echo AA_SierModule::AA_ID_MODULE?>.valid = true;
<?php echo AA_SierModule::AA_ID_MODULE?>.content = {};
<?php echo AA_SierModule::AA_ID_MODULE?>.contentType = "json";
<?php echo AA_SierModule::AA_ID_MODULE?>.ui.module_content_id = "<?php echo AA_SierModule::AA_UI_MODULE_MAIN_BOX?>";

//Handler doppio click Lista
<?php echo AA_SierModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].ListaDblClick = function() {
    try 
    {
        //console.log("eventHandlers.defaultHandlers.ListaDblClick", this, arguments[2].firstChild.attributes['id_view']);
        let id="<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_ID_SECTION_DETAIL."_".AA_SierModule::AA_UI_DETAIL_CANDIDATI_BOX."_Candidati";?>";
        let candidati_list=$$(id);
        //console.log("eventHandlers.defaultHandlers.ListaDblClick",id);
        let item=this.getItem(arguments[0]);
        if(candidati_list)
        {
            //console.log("eventHandlers.defaultHandlers.ListaDblClick",candidati_list,item);
            let tabbar=$$("<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_ID_SECTION_DETAIL."_TabBar_";?>"+this.config.id_object);
            //console.log("eventHandlers.defaultHandlers.ListaDblClick",tabbar);
            if(tabbar)
            {
                //console.log("eventHandlers.defaultHandlers.ListaDblClick",tabbar);
                tabbar.setValue("<?php echo AA_SierModule::AA_UI_PREFIX."_".AA_SierModule::AA_ID_SECTION_DETAIL."_".AA_SierModule::AA_UI_DETAIL_CANDIDATI_BOX;?>");
                candidati_list.filter("lista",item.denominazione,false);
                let filter = candidati_list.getFilter("lista");
                filter.value=item.denominazione;
            }            
        }

    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.onTipoProvSelectChange", msg, this);
    }
};

//Registrazione modulo
AA_MainApp.registerModule(<?php echo AA_SierModule::AA_ID_MODULE?>);