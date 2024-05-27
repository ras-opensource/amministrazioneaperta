<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
include_once("lib.php");
header('Content-Type: text/javascript');
?>
//modulo
var <?php echo AA_GecoModule::AA_ID_MODULE?> = new AA_Module("<?php echo AA_GecoModule::AA_ID_MODULE?>", "SIER");
<?php echo AA_GecoModule::AA_ID_MODULE?>.valid = true;
<?php echo AA_GecoModule::AA_ID_MODULE?>.content = {};
<?php echo AA_GecoModule::AA_ID_MODULE?>.contentType = "json";
<?php echo AA_GecoModule::AA_ID_MODULE?>.ui.module_content_id = "<?php echo AA_GecoModule::AA_UI_MODULE_MAIN_BOX?>";

//aggiusta automagicamente l'altezza delle righe della tabella
<?php echo AA_GecoModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].adjustRowHeight = function() {
    try 
    {
        //console.log("adjustRowHeight",arguments,this)
        this.adjustRowHeight(null, true);
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.adjustRowHeight", msg, this);
    }
};

//Chiede all' utente se deve oscurare i dati personali 
<?php echo AA_GecoModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].onPersonaFisicaChange = function() {
    try 
    {
        if(arguments[2]=='user')
        {
            if(arguments[0]==1)
            {
                let form=this.getFormView();
                if(form)
                {
                    let form_id=form.config.id;
                    //console.log("onPersonaFisicaChange",form_id);

                    let params={task:"GetGecoConfirmPrivacyDlg",params:[{form:form_id}]};

                    AA_MainApp.utils.callHandler('dlg', params);
               }
            }
            else
            {
                //console.log("onPersonaFisicaChange - unflag");
                let form=this.getFormView();
                if(form)
                {
                    values={"Beneficiario_privacy": 0}
                    form.setValues(values,true);
                }
            }
         }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.onPersonaFisicaChange", msg, this);
    }
};

//imposta o rimuove il flag di oscuramento dei dati eprsonali
<?php echo AA_GecoModule::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].flagPrivacy = function() {
    try 
    {
        
        let form=$$(arguments[0].form);
        if(form)
        {
            values={"Beneficiario_privacy":arguments[0].value}
            form.setValues(values,true);
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.defaultHandlers.onPersonaFisicaChange", msg, this);
    }
};

//Registrazione modulo
AA_MainApp.registerModule(<?php echo AA_GecoModule::AA_ID_MODULE?>);

