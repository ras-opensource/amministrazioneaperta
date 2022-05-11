<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "art22_lib.php";
include_once "pdf_lib.php";

#Classe per il modulo Sines
Class AA_SinesModule extends AA_GenericModule
{
    const AA_UI_PREFIX="AA_Sines";
    const AA_UI_SCADENZARIO_BOX="Scadenzario_Content_Box";

    public function __construct($user=null)
    {
        parent::__construct($user,false);
        
        $this->SetId("AA_MODULE_SINES");
        
        //{"id": "sines", "icon": "mdi mdi-office-building", "value": "Sistema Informativo Enti e Società", "tooltip": "SINES - Sistema Informativo Enti e Società", "module":"AA_MODULE_SINES"}
        //Sidebar config
        $this->SetSideBarId("sines");
        $this->SetSideBarIcon("mdi mdi-office-building");
        $this->SetSideBarTooltip("SINES - Sistema Informativo Enti e Società");
        $this->SetSideBarName("Sistema Informativo Enti e Società");
        
        //Registrazione dei task-------------------
        $taskManager=$this->GetTaskManager();
        
        //$taskManager->RegisterTask("GetSections");
        //$taskManager->RegisterTask("GetLayout");
        //$taskManager->RegisterTask("GetActionMenu");
        //$taskManager->RegisterTask("GetNavbarContent");
        //$taskManager->RegisterTask("GetSectionContent");
        //$taskManager->RegisterTask("GetObjectContent");
        $taskManager->RegisterTask("GetPubblicateFilterDlg");
        $taskManager->RegisterTask("GetBozzeFilterDlg");
        $taskManager->RegisterTask("GetScadenzarioFilterDlg");
        $taskManager->RegisterTask("GetLogDlg");
        
        //organismi
        $taskManager->RegisterTask("GetOrganismoModifyDlg");
        $taskManager->RegisterTask("GetOrganismoAddNewDlg");
        $taskManager->RegisterTask("GetOrganismoTrashDlg");
        $taskManager->RegisterTask("TrashOrganismi");
        $taskManager->RegisterTask("GetOrganismoDeleteDlg");
        $taskManager->RegisterTask("DeleteOrganismi");
        $taskManager->RegisterTask("GetOrganismoResumeDlg");
        $taskManager->RegisterTask("ResumeOrganismi");
        $taskManager->RegisterTask("GetOrganismoReassignDlg");
        $taskManager->RegisterTask("GetOrganismoPublishDlg");
        $taskManager->RegisterTask("ReassignOrganismi");
        $taskManager->RegisterTask("AddNewOrganismo");
        $taskManager->RegisterTask("UpdateOrganismo");
        $taskManager->RegisterTask("PublishOrganismo");
        $taskManager->RegisterTask("GetOrganismoAddNewProvvedimentoDlg");
        $taskManager->RegisterTask("AddNewOrganismoProvvedimento");
        $taskManager->RegisterTask("GetOrganismoModifyProvvedimentoDlg");
        $taskManager->RegisterTask("UpdateOrganismoProvvedimento");
        $taskManager->RegisterTask("GetOrganismoTrashProvvedimentoDlg");
        $taskManager->RegisterTask("TrashOrganismoProvvedimento");
        
        //Generico
        $taskManager->RegisterTask("GetTipoBilanci");
        
        //Dati contabili
        $taskManager->RegisterTask("GetOrganismoModifyDatoContabileDlg");
        $taskManager->RegisterTask("GetOrganismoAddNewDatoContabileDlg");
        $taskManager->RegisterTask("GetOrganismoTrashDatoContabileDlg");
        $taskManager->RegisterTask("AddNewOrganismoDatoContabile");
        $taskManager->RegisterTask("UpdateOrganismoDatoContabile");
        $taskManager->RegisterTask("TrashOrganismoDatoContabile");
        #------------------------------------------
        
        //bilanci
        $taskManager->RegisterTask("GetOrganismoAddNewBilancioDlg");
        $taskManager->RegisterTask("AddNewOrganismoBilancio");
        $taskManager->RegisterTask("GetOrganismoModifyBilancioDlg");
        $taskManager->RegisterTask("UpdateOrganismoBilancio");
        $taskManager->RegisterTask("GetOrganismoTrashBilancioDlg");
        $taskManager->RegisterTask("TrashOrganismoBilancio");
        //--------------------------------------------
        
        //nomine
        $taskManager->RegisterTask("GetOrganismoNomineFilterDlg");
        $taskManager->RegisterTask("GetOrganismoModifyIncaricoDlg");
        $taskManager->RegisterTask("UpdateOrganismoIncarico");
        $taskManager->RegisterTask("GetOrganismoAddNewIncaricoDlg");
        $taskManager->RegisterTask("AddNewOrganismoIncarico");
        $taskManager->RegisterTask("GetOrganismoAddNewNominaDlg");
        $taskManager->RegisterTask("AddNewOrganismoNomina");
        $taskManager->RegisterTask("GetOrganismoRenameNominaDlg");
        $taskManager->RegisterTask("RenameOrganismoNomina");
        $taskManager->RegisterTask("GetOrganismoTrashIncaricoDlg");
        $taskManager->RegisterTask("TrashOrganismoIncarico");
        $taskManager->RegisterTask("GetOrganismoTrashNominaDlg");
        $taskManager->RegisterTask("TrashOrganismoNomina");
        $taskManager->RegisterTask("GetOrganismoAddNewIncaricoDocDlg");
        $taskManager->RegisterTask("AddNewOrganismoIncaricoDoc");
        $taskManager->RegisterTask("GetOrganismoTrashIncaricoDocDlg");
        $taskManager->RegisterTask("TrashOrganismoIncaricoDoc");
        $taskManager->RegisterTask("GetOrganismoAddNewIncaricoCompensoDlg");
        $taskManager->RegisterTask("AddNewOrganismoIncaricoCompenso");
        $taskManager->RegisterTask("GetOrganismoTrashIncaricoCompensoDlg");
        $taskManager->RegisterTask("TrashOrganismoIncaricoCompenso");
        $taskManager->RegisterTask("GetOrganismoModifyIncaricoCompensoDlg");
        $taskManager->RegisterTask("UpdateOrganismoIncaricoCompenso");
        
        //Pdf export
        $taskManager->RegisterTask("PdfExport");
        
        //Sezioni----------------------------------------
        
        //Schede pubblicate
        $navbarTemplate=array($this->TemplateNavbar_Bozze(1,false)->toArray(),$this->TemplateNavbar_Scadenzario(2,true)->toArray());
        $section=new AA_GenericModuleSection("Pubblicate","Schede pubblicate",true, static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX,$this->GetId(),true,true,false,true);
        $section->SetNavbarTemplate($navbarTemplate);
        $this->AddSection($section);
        
        //Bozze
        $navbarTemplate= $this->TemplateNavbar_Pubblicate(1,true)->toArray();
        $section=new AA_GenericModuleSection("Bozze","Schede in bozza",true,static::AA_UI_PREFIX."_".static::AA_UI_BOZZE_BOX,$this->GetId(),false,true,false,true);
        $section->SetNavbarTemplate($navbarTemplate);
        $this->AddSection($section);
        
        //dettaglio
        $navbarTemplate=$this->TemplateNavbar_Back(1,true)->toArray();
        $section=new AA_GenericModuleSection("Dettaglio","Dettaglio",false,static::AA_UI_PREFIX."_".static::AA_UI_DETAIL_BOX,$this->GetId(),false,true,true,true);
        $section->SetNavbarTemplate($navbarTemplate);
        $this->AddSection($section);
        
        //Scadenzario
        $navbarTemplate=$this->TemplateNavbar_Pubblicate(1,true)->toArray();
        $section=new AA_GenericModuleSection("Scadenzario","Scadenzario",true,static::AA_UI_PREFIX."_".static::AA_UI_SCADENZARIO_BOX,$this->GetId(),false,true,false,true);
        $section->SetNavbarTemplate($navbarTemplate);
        $this->AddSection($section);
        
        #-------------------------------------------
    }
    
    //istanza
    protected static $oInstance=null;
    
    //Restituisce l'istanza corrente
    public static function GetInstance($user=null)
    {
        if(self::$oInstance==null)
        {
            self::$oInstance=new AA_SinesModule($user);
        }
        
        return self::$oInstance;
    }
    
    //Layout del modulo
    function TemplateLayout()
    {
        $template=new AA_JSON_Template_Multiview(static::AA_UI_PREFIX."_module_layout",array("type"=>"clean","fitBiggest"=>"true"));
        foreach ($this->GetSections() as $curSection)
        {
            $template->addCell(new AA_JSON_Template_Template($curSection->GetViewId(),array("name"=>$curSection->GetName(),"type"=>"clean","template"=>"","initialized"=>false,"refreshed"=>false)));
        }
        
        //AA_Log::Log("TemplateLayout - ".$template,100);
        return $template;
    }
    
     //Template bozze content
    public function TemplateSection_Placeholder()
    {
        
        $content = new AA_JSON_Template_Template(static::AA_UI_PREFIX."_Placeholder_Content",
                array(
                "type"=>"clean",
                "template"=>"placeholder"
            ));
         
        return $content;
    }
    
    //Template bozze content
    public function TemplateSection_Bozze($params=null)
    {
       $is_enabled = false;
       
        if($this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22))
        {
            $is_enabled=true;
        }
        
        if(!$is_enabled)
        {
            $content = new AA_JSON_Template_Template(static::AA_UI_PREFIX."_".static::AA_UI_BOZZE_BOX,
                array(
                "type"=>"clean",
                "update_time"=>Date("Y-m-d H:i:s"),
                "name"=>"Schede in bozza",
                "template"=>"L'utente corrente non è abilitato alla visualizzazione della sezione."
            ));
        
            return $content;
        }
                
        $content=new AA_GenericPagedSectionTemplate(static::AA_UI_PREFIX."_Bozze",$this->GetId());
        $content->EnablePager();
        $content->SetPagerItemForPage(10);
        $content->EnableFiltering();
        $content->EnableAddNew();
        $content->SetAddNewDlgTask("GetOrganismoAddNewDlg");
        $content->SetFilterDlgTask("GetBozzeFilterDlg");
        $content->ViewExportFunctions();
        
        $content->SetSectionName("Schede in bozza");
        
        //Imposta una dimensione fissa per il contenuto
        //$content->SetContentItemHeight(110);
        
        $content->ViewDetail();
        
        if($_REQUEST['cestinate']==0)
        {
            $content->ViewTrash();
            $content->SetTrashHandlerParams(array("task"=>"GetOrganismoTrashDlg"));
            $content->ViewPublish();
            $content->SetPublishHandlerParams(array("task"=>"GetOrganismoPublishDlg"));
            $content->ViewReassign();
            $content->SetReassignHandlerParams(array("task"=>"GetOrganismoReassignDlg"));
        }
        else 
        {
            $content->SetSectionName("Schede in bozza cestinate");
            $content->ViewResume();
            $content->SetResumeHandlerParams(array("task"=>"GetOrganismoResumeDlg"));
            $content->ViewDelete();
            $content->SetDeleteHandlerParams(array("task"=>"GetOrganismoDeleteDlg"));
            $content->HideReassign();
            $content->EnableAddNew(false);
        }            
                
        $_REQUEST['count']=10;
        
        $contentData=$this->GetDataSectionBozze_List($_REQUEST);
        $content->SetContentBoxData($contentData[1]);
        
        $content->SetPagerItemCount($contentData[0]);
        $content->EnableMultiSelect();
        $content->EnableSelect();
        
        return $content->toObject();
    }
    
    //Template pubblicate
    public function TemplateSection_Pubblicate($params=null)
    {
        $is_admin=false;

        if($this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN) || $this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22))
        {
            $is_admin=true;
        }
        
        //$content_box=$this->TemplateSectionPubblicate_List($_REQUEST);
                
        $content=new AA_GenericPagedSectionTemplate(static::AA_UI_PREFIX."_Pubblicate",$this->GetId());
        $content->EnablePager();
        $content->EnablePaging();
        $content->SetPagerItemForPage(10);
        $content->EnableFiltering();
        $content->SetFilterDlgTask("GetPubblicateFilterDlg");
        $content->ViewExportFunctions();
        
        $content->SetSectionName("Schede pubblicate");
        
        //Imposta una dimensione fissa per il contenuto
        //$content->SetContentItemHeight(110);
        
        $content->ViewDetail();
        
        if($is_admin)
        {
            $content->ViewReassign();
            $content->SetReassignHandlerParams(array("task"=>"GetOrganismoReassignDlg"));
            
            if($_REQUEST['cestinate']==0)
            {
                $content->ViewTrash();
                $content->SetTrashHandlerParams(array("task"=>"GetOrganismoTrashDlg"));
            }
            else 
            {
                $content->SetSectionName("Schede pubblicate cestinate");
                $content->HideReassign();
                $content->ViewResume();
                $content->SetResumeHandlerParams(array("task"=>"GetOrganismoResumeDlg"));
                $content->ViewDelete();
                $content->SetDeleteHandlerParams(array("task"=>"GetOrganismoDeleteDlg"));
            }            
        }
                
        $_REQUEST['count']=10;
        
        $contentData=$this->GetDataSectionPubblicate_List($_REQUEST);
        $content->SetContentBoxData($contentData[1]);
        
        $content->SetPagerItemCount($contentData[0]);
        $content->EnableMultiSelect();
        $content->EnableSelect();
        
        return $content->toObject();
    }
    
    //Template scadenzario
    public function TemplateSection_Scadenzario()
    {
        $is_admin=false;

        if($this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN) || $this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22))
        {
            $is_admin=true;
        }
        
        //$content_box=$this->TemplateSectionPubblicate_List($_REQUEST);
                
        $content=new AA_GenericPagedSectionTemplate(static::AA_UI_PREFIX."_Scadenzario",$this->GetId());
        $content->EnablePager();
        $content->EnablePaging();
        $content->SetPagerItemForPage(10);
        $content->EnableFiltering();
        $content->SetFilterDlgTask("GetScadenzarioFilterDlg");
        $content->ViewExportFunctions();
        
        if($_REQUEST['data_scadenzario'] =="") $_REQUEST['data_scadenzario']=Date("Y-m-d");
        $data=new DateTime($_REQUEST['data_scadenzario']);
        
        $contentBoxTemplate="<div class='AA_DataView_ScadenzarioItem'><div class='AA_DataView_ItemContent'>"
            ."<div><span class='AA_Label AA_Label_Orange'>#pretitolo#</span></div>"
            . "<div><span class='AA_DataView_ItemTitle'>#denominazione#</span></div>"
            . "<div>#tags#</div>"
            . "<div><span class='AA_DataView_ItemSubTitle'>#sottotitolo#</span></div>"
            . "<div><span class='AA_Label AA_Label_LightBlue' title='Stato elemento'>#stato#</span>&nbsp;<span class='AA_DataView_ItemDetails'>#dettagli#</span></div>"
            . "</div><div class='AA_DataView_ScadenzarioItemContent'>#nomine#</div></div>";
        $content->SetContentBoxTemplate($contentBoxTemplate);
        
        $content->SetSectionName("Scadenzario nomine al ".$data->format("Y-m-d"));
        
        $content->ViewDetail();
        
        $_REQUEST['count']=10;        
        
        $contentData=$this->GetDataSectionScadenzario_List($_REQUEST);
        $content->SetContentBoxData($contentData[1]);
        
        $content->SetPagerItemCount($contentData[0]);
        $content->EnableMultiSelect();
        $content->EnableSelect();
        
        return $content->toObject();
    }
    
    public function GetDataSectionPubblicate_List($params=array())
    {
        $templateData=array();
        
        $parametri=array("status"=>AA_Const::AA_STATUS_PUBBLICATA);
        if($params['cestinate'] == 1) $parametri['status']=AA_Const::AA_STATUS_PUBBLICATA+AA_Const::AA_STATUS_CESTINATA;
        if($params['page']) $parametri['from']=($params['page']-1)*$params['count'];
        if($params['denominazione']) $parametri['denominazione']=$params['denominazione'];
        if($params['tipo']) $parametri['tipo']=$params['tipo'];
        if($params['dal']) $parametri['dal']=$params['dal'];
        if($params['al']) $parametri['al']=$params['al'];
        if($params['id_assessorato']) $parametri['id_assessorato']=$params['id_assessorato'];
        if($params['id_direzione']) $parametri['id_direzione']=$params['id_direzione'];
        if($params['incaricato']) $parametri['incaricato']=$params['incaricato'];
        
        $organismi=AA_Organismi::Search($parametri,false,$this->oUser);
        
        foreach($organismi[1] as $id=>$object)
        {
            $userCaps=$object->GetUserCaps($this->oUser);
            $struct=$object->GetStruct();
            $struttura_gest=$struct->GetAssessorato();
            if($struct->GetDirezione() !="") $struttura_gest.=" -> ".$struct->GetDirezione();
            
            #Società-----------
            $soc_tags="";
            if($object->GetTipologia(true)==AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
            {
                //forma giuridica
                $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetFormaSocietaria()."</span>";
                
                if($object->IsInHouse() == true) $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>in house</span>";
                if($object->IsInTUSP() == true) $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>TUSP</span>";
                if($object->GetPartecipazione() == "" || $object->GetPartecipazione() == "0") $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green' title='Società non direttamente partecipata dalla RAS'>indiretta</span>";
                
                //stato società
                if($object->GetStatoOrganismo(true) > AA_Organismi_Const::AA_ORGANISMI_STATO_SOCIETA_ATTIVO) $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetStatoOrganismo()."</span>";
            }
            #------------------------------------------
                           
            #Stato
            if($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) $status="bozza";
            if($object->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $status="pubblicata";
            if($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $status.=" revisionata";
            if($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $status.=" cestinata";
        
            #Dettagli
            if(($userCaps&AA_Const::AA_PERMS_PUBLISH) > 0 && $object->GetAggiornamento() != "")
            {
                //Aggiornamento
                $details="<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;".$object->GetAggiornamento(true)."</span>&nbsp;";
                
                //utente e log
                $lastLog=$object->GetLog()->GetLastLog();
                if($lastLog['user']=="")
                {
                    $lastLog['user']=$object->GetUser()->GetUsername();
                }

                $details.="<span class='AA_Label AA_Label_LightBlue' title=\"Nome dell'utente che ha compiuto l'ultima azione - Fai click per visualizzare il log delle azioni\"><span class='mdi mdi-account' onClick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetLogDlg', 'params': {id: ".$object->GetId()."}},'".$this->GetId()."');\">".$lastLog['user']."</span>&nbsp;";
                
                //id
                $details.="</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;".$object->GetId()."</span>";
            } 
            else
            {
                if($object->GetAggiornamento() != "") $details="<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;".$object->GetAggiornamento(true)."</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;".$object->GetId()."</span>";
            }
            
            if(($userCaps & AA_Const::AA_PERMS_WRITE) ==0) $details.="&nbsp;<span class='AA_Label AA_Label_LightBlue' title=\" L'utente corrente non può apportare modifiche all'organismo\"><span class='mdi mdi-pencil-off'></span>&nbsp; sola lettura</span>";
            
            $templateData[]=array(
                "id"=>$object->GetId(),
                "tags"=>$soc_tags,
                "aggiornamento"=>$object->GetAggiornamento(),
                "denominazione"=>$object->GetDenominazione(),
                "pretitolo"=>$object->GetTipologia(),
                "sottotitolo"=>$struttura_gest,
                "stato"=>$status,
                "dettagli"=>$details,
                "module_id"=>$this->GetId()
            );
        }

        return array($organismi[0],$templateData);
    }
    
    //Scadenzario data
    public function GetDataSectionScadenzario_List($params=array())
    {
        $templateData=array();
        
        $parametri=array("status"=>AA_Const::AA_STATUS_PUBBLICATA);
        if($params['page']) $parametri['from']=($params['page']-1)*$params['count'];
        if($params['denominazione']) $parametri['denominazione']=$params['denominazione'];
        if($params['tipo']) $parametri['tipo']=$params['tipo'];
        if($params['incaricato']) $parametri['incaricato']=$params['incaricato'];
        if($params['dal']) $parametri['dal']=$params['dal'];
        if($params['al']) $parametri['al']=$params['al'];
        if($params['id_assessorato']) $parametri['id_assessorato']=$params['id_assessorato'];
        if($params['id_direzione']) $parametri['id_direzione']=$params['id_direzione'];
        
        $parametri['in_scadenza']=$params['in_scadenza'];
        $parametri['in_corso']=$params['in_corso'];
        $parametri['scadute']=$params['scadute'];
        $parametri['recenti']=$params['recenti'];
        $parametri['data_scadenzario']=$params['data_scadenzario'];
        $parametri['finestra_temporale']=$params['finestra_temporale'];
        $parametri['raggruppamento']=$params['raggruppamento'];
        
        if($parametri['scadute'] == "") $parametri['scadute']="0";
        if($parametri['in_corso'] == "") $parametri['in_corso']="0";
        if($parametri['in_scadenza'] == "") $parametri['in_scadenza']="1";
        if($parametri['recenti'] == "") $parametri['recenti']="1";
        if($parametri['data_scadenzario'] == "") $parametri['data_scadenzario']=Date("Y-m-d");
        if($parametri['finestra_temporale'] == "") $parametri['finestra_temporale']="1";
        if($parametri['raggruppamento'] == "") $parametri['raggruppamento']="0";
        
        $meseProx=new DateTime($parametri['data_scadenzario']);
        $meseProx->modify("+".$parametri['finestra_temporale']." month");
        $mesePrec=new DateTime($parametri['data_scadenzario']);
        $mesePrec->modify("-".$parametri['finestra_temporale']." month");
        $data_scadenzario=new DateTime($parametri['data_scadenzario']);
        
        $organismi=AA_Organismi::Search($parametri,false,$this->oUser);
        
        foreach($organismi[1] as $id=>$object)
        {
            $userCaps=$object->GetUserCaps($this->oUser);
            $struct=$object->GetStruct();
            $struttura_gest=$struct->GetAssessorato();
            if($struct->GetDirezione() !="") $struttura_gest.=" -> ".$struct->GetDirezione();
            
            #Società-----------
            $soc_tags="";
            if($object->GetTipologia(true)==AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
            {
                //forma giuridica
                $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetFormaSocietaria()."</span>";
                
                if($object->IsInHouse() == true) $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>in house</span>";
                if($object->IsInTUSP() == true) $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>TUSP</span>";
                if($object->GetPartecipazione() == "" || $object->GetPartecipazione() == "0") $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green' title='Società non direttamente partecipata dalla RAS'>indiretta</span>";
                
                //stato società
                if($object->GetStatoOrganismo(true) > AA_Organismi_Const::AA_ORGANISMI_STATO_SOCIETA_ATTIVO) $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetStatoOrganismo()."</span>";
            }
            #------------------------------------------
                           
            #Stato
            if($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) $status="bozza";
            if($object->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $status="pubblicata";
            if($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $status.=" revisionata";
            if($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $status.=" cestinata";
        
           #Dettagli
           if(($userCaps&AA_Const::AA_PERMS_PUBLISH) > 0 && $object->GetAggiornamento() != "")
           {
               //Aggiornamento
               $details="<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;".$object->GetAggiornamento(true)."</span>&nbsp;";
               
               //utente e log
               $lastLog=$object->GetLog()->GetLastLog();
               if($lastLog['user']=="") $lastLog['user']=$object->GetUser()->GetUsername();        
               $details.="<span class='AA_Label AA_Label_LightBlue' title=\"Nome dell'utente che ha compiuto l'ultima azione - Fai click per visualizzare il log delle azioni\"><span class='mdi mdi-account' onClick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetLogDlg', 'params': {id: ".$object->GetId()."}},'".$this->GetId()."');\">".$lastLog['user']."</span>&nbsp;";
               
               //id
               $details.="</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;".$object->GetId()."</span>";
           } 
           else
           {
               if($object->GetAggiornamento() != "") $details="<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;".$object->GetAggiornamento(true)."</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;".$object->GetId()."</span>";
           }
            
            if(($userCaps & AA_Const::AA_PERMS_WRITE) == 0) $details.="&nbsp;<span class='AA_Label AA_Label_LightBlue' title=\" L'utente corrente non può apportare modifiche all'organismo\"><span class='mdi mdi-pencil-off'></span>&nbsp; sola lettura</span>";
            
            //Nomine
            $params_nomine=Array('nomina_altri'=>"0");
            
            //Raggruppa per incarico
            $params_nomine['raggruppamento']=$parametri['raggruppamento'];
            
            //Imposta i limiti temporali
            if($parametri['in_scadenza'] != "1" || $parametri['in_corso'] != "1" || $parametri['scadute'] != "1" || $parametri['recenti'] != "1")
            {
                if($parametri['in_scadenza'] == "1" && $parametri['scadute'] != "1") $params_nomine['scadenzario_dal']=$parametri['data_scadenzario'];
                if($parametri['recenti'] == "1" && $parametri['in_corso'] !="1") $params_nomine['scadenzario_al']=$parametri['data_scadenzario'];
                if($parametri['in_scadenza'] == "1" && $parametri['in_corso'] !="1") $params_nomine['scadenzario_al']=$meseProx->format("Y-m-d");
                if($parametri['recenti'] == "1" && $parametri['scadute'] !="1") $params_nomine['scadenzario_dal']=$mesePrec->format("Y-m-d");
            }
            
            $nomine=$object->GetNomineGrouped($params_nomine);
            $nomine_list=array();
            
            foreach($nomine as $nomina)
            {
                $curNomina=current($nomina);
                $datafine=new DateTime($curNomina->GetDataFine());
                
                $view=false;
                if($parametri['in_corso']=="1" && $datafine > $meseProx)
                {
                    $view=true;
                    $label_class="AA_Label_LightGreen";
                    $label_scadenza="Scade tra: ";
                }
                    
                if($parametri['in_scadenza']=="1" && $datafine >= $data_scadenzario && $datafine <= $meseProx)
                {
                    $view=true;
                    $label_class="AA_Label_LightYellow";
                    $label_scadenza="Scade tra: ";
                }
                
                if($parametri['recenti']=="1" && $datafine >= $mesePrec && $datafine <= $data_scadenzario)
                {
                    $view=true;
                    $label_class="AA_Label_LightOrange";
                    $label_scadenza="Scaduta da: ";
                }
                
                if($parametri['scadute']=="1" && $datafine < $mesePrec)
                {
                    $view=true;
                    $label_class="AA_Label_LightRed";
                    $label_scadenza="Scaduta da: ";
                }
                
                //AA_Log::Log(__METHOD__." - data_fine: ".print_r($datafine,true)." - data_scadenzario: ".print_r($data_scadenzario,true)." - mese prox: ".print_r($meseProx,true)." - mese prec: ".print_r($mesePrec,true),100);
                
                if($view)
                {
                    $nomina_label=$curNomina->GetNome()." ".$curNomina->GetCognome();
                    if($curNomina->GetCodiceFiscale() !="") $nomina_label.=" (".$curNomina->GetCodiceFiscale().")";
                    $nomine_list[$curNomina->GetTipologia()][]="<div class='AA_Label ".$label_class."' style='margin-right: 1em;'><div style='font-weight: 900'>".$curNomina->GetTipologia()."</div><div>".$nomina_label."</div><div>".$label_scadenza.$datafine->diff($data_scadenzario)->format("%a")." giorni</div></div>";
                }
            }
            
            $result="";
            foreach($nomine_list as $x)
            {
                foreach($x as $y)
                {
                    $result.=$y;
                }
            }
            
            $templateData[]=array(
                "id"=>$object->GetId(),
                "tags"=>$soc_tags,
                "aggiornamento"=>$object->GetAggiornamento(),
                "denominazione"=>$object->GetDenominazione(),
                "pretitolo"=>$object->GetTipologia(),
                "sottotitolo"=>$struttura_gest,
                "stato"=>$status,
                "dettagli"=>$details,
                "module_id"=>$this->GetId(),
                "nomine"=>$result
            );
        }

        return array($organismi[0],$templateData);
    }
    
    //Restituisce i dati delle bozze
    public function GetDataSectionBozze_List($params=array())
    {
        $templateData=array();
        
        $parametri=array("status"=>AA_Const::AA_STATUS_BOZZA);
        if($params['cestinate'] == 1) $parametri['status']=AA_Const::AA_STATUS_BOZZA+AA_Const::AA_STATUS_CESTINATA;
        if($params['page']) $parametri['from']=($params['page']-1)*$params['count'];
        if($params['denominazione']) $parametri['denominazione']=$params['denominazione'];
        if($params['tipo']) $parametri['tipo']=$params['tipo'];
        if($params['dal']) $parametri['dal']=$params['dal'];
        if($params['al']) $parametri['al']=$params['al'];
        if($params['id_assessorato']) $parametri['id_assessorato']=$params['id_assessorato'];
        if($params['id_direzione']) $parametri['id_direzione']=$params['id_direzione'];
        if($params['incaricato']) $parametri['incaricato']=$params['incaricato'];
        
        $organismi=AA_Organismi::Search($parametri,false,$this->oUser);
        
        foreach($organismi[1] as $id=>$object)
        {
            $userCaps=$object->GetUserCaps($this->oUser);
            $struct=$object->GetStruct();
            $struttura_gest=$struct->GetAssessorato();
            if($struct->GetDirezione() !="") $struttura_gest.=" -> ".$struct->GetDirezione();
            
            #Società-----------
            $soc_tags="";
            if($object->GetTipologia(true)==AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA)
            {
                //forma giuridica
                $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetFormaSocietaria()."</span>";
             
                if($object->IsInHouse() == true) $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>in house</span>";
                if($object->IsInTUSP() == true) $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>TUSP</span>";
                if($object->GetPartecipazione() == "" || $object->GetPartecipazione() == "0") $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green' title='Società non direttamente partecipata dalla RAS'>indiretta</span>";
                
                //stato società
                if($object->GetStatoOrganismo(true) > AA_Organismi_Const::AA_ORGANISMI_STATO_SOCIETA_ATTIVO) $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetStatoOrganismo()."</span>";
            }
            #------------------------------------------
          
            #Stato
            if($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) $status="bozza";
            if($object->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $status="pubblicata";
            if($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $status.=" revisionata";
            if($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $status.=" cestinata";
    
            #Dettagli
            if(($userCaps&AA_Const::AA_PERMS_PUBLISH) > 0 && $object->GetAggiornamento() != "")
            {
                //Aggiornamento
                $details="<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;".$object->GetAggiornamento(true)."</span>&nbsp;";
                
                //utente e log
                $lastLog=$object->GetLog()->GetLastLog();
                if($lastLog['user']=="") $lastLog['user']=$object->GetUser()->GetUsername();
                $details.="<span class='AA_Label AA_Label_LightBlue' title=\"Nome dell'utente che ha compiuto l'ultima azione - Fai click per visualizzare il log delle azioni\"><span class='mdi mdi-account' onClick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetLogDlg', 'params': {id: ".$object->GetId()."}},'".$this->GetId()."');\">".$lastLog['user']."</span>&nbsp;";
                
                //id
                $details.="</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;".$object->GetId()."</span>";
            } 
            else
            {
                if($object->GetAggiornamento() != "") $details="<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;".$object->GetAggiornamento(true)."</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;".$object->GetId()."</span>";
            }

            if(($userCaps & AA_Const::AA_PERMS_WRITE) ==0) $details.="&nbsp;<span class='AA_Label AA_Label_LightBlue' title=\" L'utente corrente non può apportare modifiche all'organismo\"><span class='mdi mdi-pencil-off'></span>&nbsp; sola lettura</span>";
            
            $templateData[]=array(
                "id"=>$object->GetId(),
                "tags"=>$soc_tags,
                "aggiornamento"=>$object->GetAggiornamento(),
                "denominazione"=>$object->GetDenominazione(),
                "pretitolo"=>$object->GetTipologia(),
                "sottotitolo"=>$struttura_gest,
                "stato"=>$status,
                "dettagli"=>$details,
                "module_id"=>$this->GetId()
            );
        }

        return array($organismi[0],$templateData);
    }
    
    //Template Revisionate
    public function TemplateSection_Revisionate()
    {
        
        $content = new AA_JSON_Template_Template(static::AA_UI_PREFIX."_Revisionate_Content",
                array(
                "type"=>"clean",
                "template"=>"revisionate"
            ));
         
        return $content;
    }
    
    //Template organismo trash dlg
    public function Template_GetOrganismoTrashDlg($params)
    {
        //lista organismi da cestinare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Organismi($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_DELETE)>0)
                {
                    $ids_final[$curId]=$organismo->GetDenominazione();
                    unset($organismo);
                }
            }

            $id=$this->id."_TrashDlg";

            //Esiste almeno un organismo che può essere cestinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $forms_data['ids']=json_encode(array_keys($ids_final));
                $wnd=new AA_GenericFormDlg($id, "Cestina", $this->id, $forms_data,$forms_data);
               
                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata=array();
                foreach($ids_final as $id_org=>$desc)
                {
                    $tabledata[]=array("Denominazione"=>$desc);
                }

                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." organismi verranno cestinati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente organismo verrà cestinato, vuoi procedere?")));

                $table=new AA_JSON_Template_Generic($id."_Table", array(
                    "view"=>"datatable",
                    "scrollX"=>false,
                    "autoConfig"=>true,
                    "select"=>false,
                    "data"=>$tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask('TrashOrganismi');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per cestinare gli organismi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }
            
            return $wnd;
        }
    }
    
    //Template organismo publish dlg
    public function Template_GetOrganismoPublishDlg($params)
    {
        //lista organismi da ripristinare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Organismi($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_PUBLISH)>0)
                {
                    $ids_final[$curId]=$organismo->GetDenominazione();
                    unset($organismo);
                }
            }

            $id=$this->id."_PublishDlg";

            //Esiste almeno un organismo che può essere pubblicato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $forms_data['ids']=json_encode(array_keys($ids_final));
                
                $wnd=new AA_GenericFormDlg($id, "Pubblica", $this->id, $forms_data,$forms_data);
               
                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata=array();
                foreach($ids_final as $id_org=>$desc)
                {
                    $tabledata[]=array("Denominazione"=>$desc);
                }

                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." organismi verranno pubblicato, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente organismo verrà pubblicato, vuoi procedere?")));

                $table=new AA_JSON_Template_Generic($id."_Table", array(
                    "view"=>"datatable",
                    "scrollX"=>false,
                    "autoConfig"=>true,
                    "select"=>false,
                    "data"=>$tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask('PublishOrganismo');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per pubblicare gli organismi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }
            
            return $wnd;
        }
    }
    
    //Template organismo resume dlg
    public function Template_GetOrganismoResumeDlg($params)
    {
        //lista organismi da ripristinare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Organismi($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE)>0)
                {
                    $ids_final[$curId]=$organismo->GetDenominazione();
                    unset($organismo);
                }
            }

            $id=$this->id."_ResumeDlg";

            //Esiste almeno un organismo che può essere ripristinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $forms_data['ids']=json_encode(array_keys($ids_final));
                
                $wnd=new AA_GenericFormDlg($id, "Ripristina", $this->id, $forms_data,$forms_data);
               
                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata=array();
                foreach($ids_final as $id_org=>$desc)
                {
                    $tabledata[]=array("Denominazione"=>$desc);
                }

                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." organismi verranno ripristinati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente organismo verrà ripristinato, vuoi procedere?")));

                $table=new AA_JSON_Template_Generic($id."_Table", array(
                    "view"=>"datatable",
                    "scrollX"=>false,
                    "autoConfig"=>true,
                    "select"=>false,
                    "data"=>$tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask('ResumeOrganismi');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per ripristinare gli organismi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }
            
            return $wnd;
        }
    }
    
    //Template organismo reassign dlg
    public function Template_GetOrganismoReassignDlg($params)
    {
        //lista organismi da ripristinare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Organismi($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE)>0)
                {
                    $ids_final[$curId]=$organismo->GetDenominazione();
                    unset($organismo);
                }
            }

            $id=$this->id."_ReassignDlg";

            //Esiste almeno un organismo che può essere ripristinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $forms_data['ids']=json_encode(array_keys($ids_final));
                $struct=$this->oUser->GetStruct();
                $forms_data['id_assessorato']=$struct->GetAssessorato(true);
                $forms_data['id_direzione']=$struct->GetDirezione(true);
                $forms_data['id_servizio']=0;
                if($forms_data['id_direzione'] > 0) $forms_data['struct_desc']=$struct->GetDirezione();
                else $forms_data['struct_desc']=$struct->GetAssessorato();
                
                $wnd=new AA_GenericFormDlg($id, "Riassegna", $this->id, $forms_data,$forms_data);
                
                //Aggiunge il campo per la struttura di riassegnazione
                $wnd->AddStructField(array("hideServices"=>1,"targetForm"=>$wnd->GetFormId()),array("select"=>true, "bottomLabel"=>"*Seleziona la struttura di riassegnazione."));
            
                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata=array();
                foreach($ids_final as $id_org=>$desc)
                {
                    $tabledata[]=array("Denominazione"=>$desc);
                }
                
                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." organismi verranno riassegnati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente organismo verrà riassegnato, vuoi procedere?")));

                $table=new AA_JSON_Template_Generic($id."_Table", array(
                    "view"=>"datatable",
                    "scrollX"=>false,
                    "autoConfig"=>true,
                    "select"=>false,
                    "data"=>$tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask('ReassignOrganismi');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per ripristinare gli organismi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }
            
            return $wnd;
        }
    }
    
    //Template organismo delete dlg
    public function Template_GetOrganismoDeleteDlg($params)
    {
        //lista organismi da eliminare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Organismi($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_DELETE)>0)
                {
                    $ids_final[$curId]=$organismo->GetDenominazione();
                    unset($organismo);
                }
            }

            $id=$this->id."_DeleteDlg";

            //Esiste almeno un organismo che può essere eliminato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $forms_data['ids']=json_encode(array_keys($ids_final));
                $wnd=new AA_GenericFormDlg($id, "Elimina...", $this->id, $forms_data,$forms_data);

                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata=array();
                foreach($ids_final as $id_org=>$desc)
                {
                    $tabledata[]=array("Denominazione"=>$desc);
                }

                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." organismi verranno <span style='text-decoration:underline'>eliminati definitivamente</span>, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente organismo verrà <span style='text-decoration:underline'>eliminato definitivamente</span>, vuoi procedere?")));

                $table=new AA_JSON_Template_Generic($id."_Table", array(
                    "view"=>"datatable",
                    "scrollX"=>false,
                    "autoConfig"=>true,
                    "select"=>false,
                    "data"=>$tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask('DeleteOrganismi');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per eliminare gli organismi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }
            
            return $wnd;
        }
    }
    
    //Template dlg trash bilancio
    public function Template_GetOrganismoTrashBilancioDlg($object=null,$dato_contabile=null,$bilancio=null)
    {
        $id=$this->id."_TrashBilancio_Dlg";
        
        $form_data['id_bilancio']=$bilancio->GetId();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina bilancio", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(480);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("Tipo"=>$bilancio->GetTipo(),"Risultati"=>$bilancio->GetRisultati(),"Note"=>$bilancio->GetNote());
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente bilancio verrà eliminato, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "autoConfig"=>true,
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("TrashOrganismoBilancio");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_dato_contabile"=>$dato_contabile->GetId()));
        
        return $wnd;
    }
    
    //Template dlg trash incarico
    public function Template_GetOrganismoTrashProvvedimentoDlg($object=null,$provvedimento=null)
    {
        $id=$this->id."_TrashProvvedimento_Dlg";
        
        $form_data['id_provvedimento']=$provvedimento->GetId();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina provvedimento", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $url=$provvedimento->GetUrl();
        if($url =="") $url="file locale";
        $tabledata[]=array("Tipo"=>$provvedimento->GetTipologia(),"url"=>$url,"anno"=>$provvedimento->GetAnno());
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente provvedimento verrà eliminato, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"Tipo", "header"=>"Anno", "width"=>15),
              array("id"=>"Tipo", "header"=>"Tipo", "fillspace"=>true),
              array("id"=>"url", "header"=>"Url", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("TrashOrganismoProvvedimento");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_provvedimento"=>$provvedimento->GetId()));
        
        return $wnd;
    }
    
    //Template dlg trash bilancio
    public function Template_GetOrganismoTrashIncaricoDlg($object=null,$incarico=null)
    {
        $id=$this->id."_TrashIncarico_Dlg";
        
        $form_data['id_incarico']=$incarico->GetId();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina incarico di ".$incarico->GetNome()." ".$incarico->GetCognome()." (".$incarico->GetCodiceFiscale().")", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("Tipo"=>$incarico->GetTipologia(),"Data inizio"=>$incarico->GetDataInizio(),"Data Fine"=>$incarico->GetDataFine(),"Note"=>$incarico->GetNote());
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente incarico verrà eliminato, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "autoConfig"=>true,
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("TrashOrganismoIncarico");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_incarico"=>$incarico->GetId()));
        
        return $wnd;
    }
    
    //Template dlg trash documento incarico
    public function Template_GetOrganismoTrashIncaricoDocDlg($object=null,$incarico=null,$doc=null)
    {
        $id=$this->id."_TrashIncaricoDoc_Dlg";
        
        $form_data['anno']=$doc->GetAnno();
        $form_data['tipo']=$doc->GetTipologia(true);
                
        $wnd=new AA_GenericFormDlg($id, "Elimina documento di ".$incarico->GetNome()." ".$incarico->GetCognome()." (".$incarico->GetCodiceFiscale().")", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("Anno"=>$doc->GetAnno(), "Tipo"=>$doc->GetTipologia(), "incarico"=>$incarico->GetTipologia());
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente documento verrà eliminato, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            //"autoConfig"=>true,
            "columns"=>array(
              array("id"=>"incarico", "header"=>"Incarico", "fillspace"=>true),
              array("id"=>"Anno", "header"=>"Anno"),  
              array("id"=>"Tipo", "header"=>"Tipo", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("TrashOrganismoIncaricoDoc");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_incarico"=>$incarico->GetId()));
        
        return $wnd;
    }
    
    //Template dlg trash documento incarico
    public function Template_GetOrganismoTrashIncaricoCompensoDlg($object=null,$incarico=null,$compenso=null)
    {
        $id=$this->id."_TrashIncaricoDoc_Dlg";
        
        $form_data['anno']=$compenso->GetAnno();
         
        $wnd=new AA_GenericFormDlg($id, "Elimina il compenso di ".$incarico->GetNome()." ".$incarico->GetCognome()." (".$incarico->GetCodiceFiscale().")", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("anno"=>$compenso->GetAnno(), "parte_fissa"=>$compenso->GetParteFissa(), "parte_variabile"=>$compenso->GetParteVariabile(),"rimborsi"=>$compenso->GetRimborsi(), "note"=>$compenso->GetNote());
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente compenso, per l'incarico di ".$incarico->GetTipologia()." , verrà eliminato, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            //"autoConfig"=>true,
            "columns"=>array(
              array("id"=>"anno", "header"=>"Anno"),
              array("id"=>"parte_fissa", "header"=>"Parte fissa", "fillspace"=>true),
              array("id"=>"parte_variabile", "header"=>"Parte variabile", "fillspace"=>true),
              array("id"=>"rimborsi", "header"=>"Rimborsi", "fillspace"=>true),
              array("id"=>"note", "header"=>"Note", "fillspace"=>true),
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("TrashOrganismoIncaricoCompenso");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_incarico"=>$incarico->GetId(),"id_compenso"=>$compenso->GetId()));
        
        return $wnd;
    }
    
    //Template dlg aggiungi nuovo compenso incarico
    public function Template_GetOrganismoAddNewIncaricoCompensoDlg($object=null,$incarico=null)
    {
        $id=$this->id."_AddNewIncaricoCompenso_Dlg";

        $form_data['anno']=Date("Y");
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi compenso per l'incarico di ".$incarico->GetTipologia(), $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(520);
        
        //anno
        $options=array();
        $anno_fine=Date('Y');
        $anno_start=($anno_fine-5);
        
        for($i=$anno_fine; $i>=$anno_start; $i--)
        {
            $options[]=array("id"=>$i, "value"=>$i);
        }
        $wnd->AddSelectField("anno","Anno",array("required"=>true,"validateFunction"=>"IsPositive","bottomLabel"=>"*Indicare l'anno di riferimento.", "placeholder"=>"Scegli l'anno di riferimento.","options"=>$options,"value"=>Date('Y')));        
        
        //parte fissa
        $wnd->AddTextField("parte_fissa","Parte fissa",array("validateFunction"=>"IsNumber", "bottomLabel"=>"*Indicare l'importo lordo della parte fissa del trattamento economico.", "placeholder"=>"inserisci qui la parte fissa del compenso."));
        
        //parte variabile
        $wnd->AddTextField("parte_variabile","Parte variabile",array("validateFunction"=>"IsNumber","bottomLabel"=>"*Indicare l'importo lordo dell'indennità di risultato e/o il dato cumulativo dei gettoni di presenza.", "placeholder"=>"inserisci qui la parte variabile del compenso."));

        //rimborsi
        $wnd->AddTextField("rimborsi","Rimborsi",array("validateFunction"=>"IsNumber","bottomLabel"=>"*Indicare l'importo lordo dei rimborsi.", "placeholder"=>"inserisci qui i rimborsi."));
        
        //note
        $wnd->AddTextareaField("note","Note",array("placeholder"=>"inserisci qui la note."));
        
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_incarico"=>$incarico->GetId()));
        $wnd->SetSaveTask("AddNewOrganismoIncaricoCompenso");
        
        return $wnd;
    }
    
    //Template dlg modifica compenso incarico
    public function Template_GetOrganismoModifyIncaricoCompensoDlg($object=null,$incarico=null,$compenso=null)
    {
        $id=$this->id."_CompensoIncaricoCompenso_Dlg";

        $form_data['anno']=$compenso->GetAnno();
        $form_data['parte_fissa']=$compenso->GetParteFissa();
        $form_data['parte_variabile']=$compenso->GetParteVariabile();
        $form_data['rimborsi']=$compenso->GetRimborsi();
        $form_data['note']=$compenso->GetNote();
        
        $wnd=new AA_GenericFormDlg($id, "Modifica compenso per l'incarico di ".$incarico->GetTipologia(), $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(520);
        
        //anno
        $options=array();
        $anno_fine=Date('Y');
        $anno_start=($anno_fine-5);
        
        for($i=$anno_fine; $i>=$anno_start; $i--)
        {
            $options[]=array("id"=>$i, "value"=>$i);
        }
        $wnd->AddSelectField("anno","Anno",array("disabled"=>true,"required"=>true,"validateFunction"=>"IsPositive","bottomLabel"=>"*Dato non modificabile.", "placeholder"=>"Scegli l'anno di riferimento.","options"=>$options,"value"=>Date('Y')));
        
        //parte fissa
        $wnd->AddTextField("parte_fissa","Parte fissa",array("validateFunction"=>"IsNumber", "bottomLabel"=>"*Indicare l'importo lordo della parte fissa del trattamento economico.", "placeholder"=>"inserisci qui la parte fissa del compenso."));
        
        //parte variabile
        $wnd->AddTextField("parte_variabile","Parte variabile",array("validateFunction"=>"IsNumber","bottomLabel"=>"*Indicare l'importo lordo dell'indennità di risultato e/o il dato cumulativo dei gettoni di presenza.", "placeholder"=>"inserisci qui l'indennità di risultato e/o il dato cumulativo dei gettoni di presenza."));

        //rimborsi
        $wnd->AddTextField("rimborsi","Rimborsi",array("validateFunction"=>"IsNumber","bottomLabel"=>"*Indicare l'importo lordo dei rimborsi.", "placeholder"=>"inserisci qui i rimborsi."));
        
        //note
        $wnd->AddTextareaField("note","Note",array("placeholder"=>"inserisci qui la note."));
        
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_incarico"=>$incarico->GetId(),"id_compenso"=>$compenso->GetId()));
        $wnd->SetSaveTask("UpdateOrganismoIncaricoCompenso");
        
        return $wnd;
    }
    
    //Template dlg trash bilancio
    public function Template_GetOrganismoTrashNominaDlg($object=null,$incarichi=array())
    {
        $id=$this->id."_TrashNomina_Dlg";
        $tabledata=array();
        $form_data['ids']=array();
        foreach( $incarichi as $incarico)
        {
            $form_data['ids'][]=$incarico->GetId();
            $tabledata[]=array("Tipo"=>$incarico->GetTipologia(),"Data inizio"=>$incarico->GetDataInizio(),"Data Fine"=>$incarico->GetDataFine(),"Note"=>$incarico->GetNote());
        }
        
        $params['nome']=$incarico->GetNome();
        $params['cognome']=$incarico->GetCognome();
        $params['cf']=$incarico->GetCodiceFiscale();
        
        $form_data['ids']="[".implode(",",$form_data['ids'])."]";
        
        $wnd=new AA_GenericFormDlg($id, "Elimina nomina ".$params['nome']." ".$params['cognome']." (".$params['cf'].")", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti incarichi verranno eliminati, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "autoConfig"=>true,
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("TrashOrganismoNomina");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        
        return $wnd;
    }
    
    //Template dlg trash dato contabile
    public function Template_GetOrganismoTrashDatoContabileDlg($object=null,$dato_contabile=null)
    {
        $id=$this->id."_TrashDatoContabile_Dlg";
        
        $form_data['id_dato_contabile']=$dato_contabile->GetId();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina dato contabile", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(480);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("Descrizione"=>"Dati contabili e dotazione organica per l'anno ".$dato_contabile->GetAnno());
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente dato contabile verrà eliminato, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "autoConfig"=>true,
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("TrashOrganismoDatoContabile");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_dato_contabile"=>$dato_contabile->GetId()));
        
        return $wnd;
    }
        
    //Template dlg addnew organismo
    public function Template_GetOrganismoAddNewDlg()
    {
        $id=$this->id."_AddNew_Dlg";
        
        $form_data=array();
        
        //Struttura
        $struct=$this->oUser->GetStruct();
        $form_data['id_assessorato']=$struct->GetAssessorato(true);
        $form_data['id_direzione']=$struct->GetDirezione(true);
        $form_data['id_servizio']=0;
        if($form_data['id_direzione'] > 0) $form_data['struct_desc']=$struct->GetDirezione();
        else $form_data['struct_desc']=$struct->GetAssessorato();
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi un nuovo organismo", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(600);
        $wnd->SetHeight(420);
        $wnd->EnableValidation();
                
        //Descrizione
        $wnd->AddTextField("sDescrizione","Denominazione",array("required"=>true, "bottomLabel"=>"*Inserisci la denominazione dell'organismo", "placeholder"=>"inserisci qui la denominazione dell'organismo"));
        
        //Tipologia
        $options=array();
        foreach(AA_Organismi_Const::GetTipoOrganismi() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $wnd->AddSelectField("nTipologia","Tipologia",array("required"=>true,"options"=>$options,"bottomLabel"=>"*seleziona il tipo di organismo."));
        
        //Funzioni
        $label="Funzioni attrib.";
        $wnd->AddTextareaField("sFunzioni",$label,array("bottomLabel"=>"*Funzioni attribuite all'organismo.", "required"=>true,"placeHolder"=>"Inserisci qui le funzioni attribuite"));
        
        //Struttura
        $wnd->AddStructField(array("hideServices"=>1,"targetForm"=>$wnd->GetFormId()), array("select"=>true),array("bottomLabel"=>"*Seleziona la struttura controllante."));

        $wnd->EnableCloseWndOnSuccessfulSave();
        //$wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("AddNewOrganismo");
        
        return $wnd;
    }
    
    //Template dlg modify organismo
    public function Template_GetOrganismoModifyDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_GetOrganismoModifyDlg";
        if(!($object instanceof AA_Organismi)) return new AA_GenericWindowTemplate($id, "Modifica i dati generali", $this->id);

        $form_data['id']=$object->GetID();
        foreach($object->GetBindings() as $id_obj=>$field)
        {
            $form_data[$id_obj]=$object->GetProp($id_obj);
        }
        
        $wnd=new AA_GenericFormDlg($id, "Modifica i dati generali", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(160);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(720);
        
        //Descrizione
        $wnd->AddTextField("sDescrizione","Denominazione",array("required"=>true, "bottomLabel"=>"*Denominazione dell'organismo", "placeholder"=>"inserisci qui la denominazione dell'organismo"));
        
        //Tipologia
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Organismi_Const::GetTipoOrganismi() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $wnd->AddSelectField("nTipologia","Tipologia",array("required"=>true, "validateFunction"=>"IsPositive", "customInvalidMessage"=>"*Occorre selezionare la tipologia","options"=>$options,"value"=>"0", "hidden"=>true), false);
        
        if(($object->GetTipologia(true) & AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) >0)
        {
            //forma giuridica
            $options=array();
            foreach(AA_Organismi_Const::GetListaFormaGiuridica() as $id=>$label)
            {
                if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
            }
            $wnd->AddSelectField("nFormaSocietaria","Forma giuridica",array("required"=>true, "validateFunction"=>"IsPositive", "customInvalidMessage"=>"*Occorre selezionare la forma giuridica","bottomLabel"=>"*Selezionare la forma giuridica dalla lista.","options"=>$options,"value"=>"0"));
            
            //in house
            $wnd->AddCheckBoxField("bInHouse","In house",array("bottomLabel"=>"*Abilitare se la società è in house."), false);
            
            //Stato società
            $options=array();
            foreach(AA_Organismi_Const::GetListaStatoOrganismi() as $id=>$label)
            {
                if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
            }
            $wnd->AddSelectField("nStatoOrganismo","Stato",array("bottomLabel"=>"*Selezionare lo stato della società dalla lista.", "validateFunction"=>"IsPositive", "customInvalidMessage"=>"*Occorre selezionare lo stato della società.", "required"=>true,"options"=>$options,"value"=>"0"));
            
            //in Tusp
            $wnd->AddCheckBoxField("bInTUSP","TUSP",array("bottomLabel"=>"*Abilitare se la società rientra nell'allegato A del TUSP."), false);
        }
        
        //partita iva
        $wnd->AddTextField("sPivaCf","Partita iva/cf",array("bottomLabel"=>"*Riportare la partita iva dell'organismo.", "placeholder"=>"inserisci qui la partita iva o il cf dell'organismo"));
        
        //data inizio
        $label="Data costituzione";
        if(($object->GetTipologia(true) & AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) >0) $label="Data inizio impegno";
        $wnd->AddDateField("sDataInizioImpegno",$label,array("bottomLabel"=>"*".$label." dell'organismo.", "stringResult"=>true, "format"=>"%Y-%m-%d", "editable"=>true), false);
        
        //sede
        $wnd->AddTextField("sSedeLegale","Sede legale",array("bottomLabel"=>"*Sede legale dell'organismo.", "placeholder"=>"inserisci qui l'indirizzo della sede legale dell'organismo"));
        
        //data fine
        $label="Data cessazione";
        if(($object->GetTipologia(true) & AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) >0) $label="Data fine impegno";
        $wnd->AddDateField("sDataFineImpegno",$label,array("bottomLabel"=>"*".$label." dell'organismo.", "stringResult"=>true, "format"=>"%Y-%m-%d", "editable"=>true), false);
        
        //pec
        $label="PEC";
        $wnd->AddTextField("sPec",$label,array("bottomLabel"=>"*".$label." dell'organismo.","placeholder"=>"Inserisci qui l'indirizzo pec"));
        
        //sito web
        $label="Sito web";
        $wnd->AddTextField("sSitoWeb",$label,array("bottomLabel"=>"*URL ".$label." dell'organismo.", "placeholder"=>"Inserisci qui l'url del sito web"), false);
        
        //Partecipazione
        if(($object->GetTipologia(true) & AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) >0)
        {
            //partecipazione
            $field_notes=htmlentities("*Indicare solo valori numerici nel formato: <valore in euro delle quote possedute>/<dato percentuale delle quote possedute>");
            $field_notes.="<br>es: 1.000.000/15,25 (1 milione di euro pari al 15,25 percento delle quote totali)";
            $label="Partecipazione";
            $wnd->AddTextField("sPartecipazione",$label,array("bottomLabel"=>"$field_notes","bottomPadding"=>40, "placeholder"=>"Riporta qui la partecipazione"));
            
            //$wnd->AddSpacer(false);
        }
        
        //Funzioni
        $label="Funzioni attribuite";
        $wnd->AddTextareaField("sFunzioni",$label,array("bottomLabel"=>"*".$label." all'organismo.", "required"=>true,"placeHolder"=>"Inserisci qui le funzioni attribuite"));
        
        //note
        $label="Note";
        $wnd->AddTextareaField("sNote",$label,array("placeholder"=>"Riporta qui le eventuali note"));

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateOrganismo");
        
        return $wnd;
    }
    
    //Template dlg modify organismo
    public function Template_GetOrganismoModifyDatoContabileDlg($object=null,$dato=null)
    {
        $id=static::AA_UI_PREFIX."_GetOrganismoModifyDatoContabileDlg";
        
        if(!($object instanceof AA_Organismi)) return new AA_GenericWindowTemplate($id, "Modifica i dati contabili e la dotazione organica", $this->id);
        if(!($dato instanceof AA_OrganismiDatiContabili)) return new AA_GenericWindowTemplate($id, "Modifica i dati contabili e la dotazione organica", $this->id);

        foreach($dato->GetBindings() as $id_obj=>$field)
        {
            $form_data[$id_obj]=$dato->GetProp($id_obj);
        }
        
        AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $wnd=new AA_GenericFormDlg($id, "Modifica i dati contabili e la dotazione organica", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(160);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(650);
        
        //anno
        $wnd->AddTextField("nAnno","Anno",array("tooltip"=>"Anno di riferimento", "validateFunction"=>"IsNumber" ,"required"=>true,"bottomLabel"=>"*Inserire il valore numerico dell'anno a quattro cifre, es. 2021", "bottomPadding"=>30,"placeholder"=>"inserisci qui l'anno di riferimento"));
        
        //oneri totali
        $wnd->AddTextField("sOneriTotali","Oneri totali",array("validateFunction"=>"IsNumber", "tooltip"=>"Inserire solo valori numerici,<br>lasciare vuoto in caso di dati assenti", "bottomLabel"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui gli oneri totali"), false);

        //Spesa incarichi
        $wnd->AddTextField("sSpesaIncarichi","Spesa per incarichi",array("validateFunction"=>"IsNumber", "tooltip"=>"Inserire solo valori numerici,<br>lasciare vuoto in caso di dati assenti", "bottomLabel"=>"*Inserire la spesa (pagamenti) per incarichi di studio e consulenza, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui la spesa per incarichi"));

        //Spesa lavoro flessibile
        $wnd->AddTextField("sSpesaLavoroFlessibile","Spesa per lavoro flessibile",array("validateFunction"=>"IsNumber", "invalidMessage"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti","tooltip"=>"Inserire solo valori numerici,<br>lasciare vuoto in caso di dati assenti", "bottomLabel"=>"*Inserire la spesa (pagamenti) per il lavoro flessibile, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui la spesa per lavoro flessibile"),false);

        //spesa dotazione organica
        $wnd->AddTextField("sSpesaDotazioneOrganica","Spesa dot. organica",array("validateFunction"=>"IsNumber", "invalidMessage"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti","tooltip"=>"Indicare la spesa complessiva per la dotazione organica,<br>inserire solo valori numerici, lasciare vuoto in caso di dati assenti.", "bottomLabel"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui la spesa per la dotazione organica"));

        //Fatturato
        if($object->GetTipologia(true)==AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) $wnd->AddTextField("sFatturato","Fatturato",array("validateFunction"=>"IsNumber", "invalidMessage"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti","tooltip"=>"riportare il fatturato in euro per l'anno di riferimento,<br>inserire solamente valori numerici, lasciare vuoto in caso di dati assenti", "bottomLabel"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci il fatturato"),false);
        else $wnd->AddSpacer(false);

        //field personale a tempo determinato
        $dotazione = new AA_FieldSet("AA_SINES_ORGANISMI_DOTAZIONE_ORGANICA","Personale assunto a tempo determinato");

        //personale a tempo determinato dirigenti
        $dotazione->AddTextField("nDipendentiDetDir","Dirigenti",array("validateFunction"=>"IsInteger", "invalidMessage"=>"*Inserire solo numeri interi, lasciare vuoto in caso di dati assenti","tooltip"=>"Indicare il numero di unità di personale sia esterno che interno,<br> riportare solo valori numerici interi, lasciare vuoto in caso di dati assenti","bottomLabel"=>"*Indicare il numero di unità di personale sia esterno che interno,<br> riportare solo valori numerici interi, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui il numero di dipendenti"));

        //personale a tempo determinato
        $dotazione->AddTextField("nDipendentiDet","Non dirigenti",array("validateFunction"=>"IsInteger", "invalidMessage"=>"*Inserire solo numeri interi, lasciare vuoto in caso di dati assenti","tooltip"=>"Indicare il numero di unità di personale sia esterno che interno,<br> riportare solo valori numerici interi, lasciare vuoto in caso di dati assenti","bottomLabel"=>"*Indicare il numero di unità di personale sia esterno che interno,<br> riportare solo valori numerici interi, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui il numero di dipendenti"),false);

        //field dipendenti
        $dip = new AA_FieldSet("AA_SINES_ORGANISMI_DIPENDENTI","Personale assunto a tempo indeterminato");

        //dipendenti dirigenti
        $dip->AddTextField("nDipendentiDir","Dirigenti",array("validateFunction"=>"IsInteger", "invalidMessage"=>"*Inserire solo numeri interi, lasciare vuoto in caso di dati assenti","tooltip"=>"Indicare il numero di dipendenti (personale dirigente e non assunto a tempo indeterminato),<br>riportare solo valori numerici interi, lasciare vuoto in caso di dati assenti", "bottomLabel"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui il numero di dipendenti"));

        //dipendenti non dirigenti
        $dip->AddTextField("nDipendenti","Non dirigenti",array("validateFunction"=>"IsInteger", "invalidMessage"=>"*Inserire solo numeri interi, lasciare vuoto in caso di dati assenti","tooltip"=>"Indicare il numero di dipendenti (personale dirigente e non assunto a tempo indeterminato),<br>riportare solo valori numerici interi, lasciare vuoto in caso di dati assenti", "bottomLabel"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui il numero di dipendenti"),false);
        
        $wnd->AddGenericObject($dip);
        $wnd->AddGenericObject($dotazione);

        //note
        $label="Note";
        $wnd->AddTextareaField("sNote",$label);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_dato_contabile"=>$dato->GetId()));
        $wnd->SetSaveTask("UpdateOrganismoDatoContabile");
        
        return $wnd;
    }
    
    //Template dlg modify organismo incarico
    public function Template_GetOrganismoModifyIncaricoDlg($object=null,$incarico=null)
    {
        $id=static::AA_UI_PREFIX."_GetOrganismoModifyIncaricoDlg";
        
        if(!($object instanceof AA_Organismi)) return new AA_GenericWindowTemplate($id, "Modifica incarico", $this->id);
        if(!($incarico instanceof AA_OrganismiNomine)) return new AA_GenericWindowTemplate($id, "Modifica incarico", $this->id);

        foreach($incarico->GetBindings() as $id_obj=>$field)
        {
            $form_data[$id_obj]=$incarico->GetProp($id_obj);
        }
        
        AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $wnd=new AA_GenericFormDlg($id, "Modifica incarico di ".$incarico->GetNome()." ".$incarico->GetCognome()." (".$incarico->GetCodiceFiscale().")", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(160);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(600);
        
        //nomina Ras
        $wnd->AddSwitchBoxField("bNominaRas","Tipo nomina",array("onLabel"=>"RAS","offLabel"=>"non RAS","bottomLabel"=>"*Indica se la nomina è effettuata dalla RAS."));        
        
        //Tipologia
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Organismi_Const::GetTipoNomine() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $wnd->AddSelectField("nTipologia","Incarico",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il tipo di incarico.","tooltip"=>"Seleziona il tipo di incarico.","options"=>$options,"value"=>"0"));
        
        //Data inizio
        $wnd->AddDateField("sDataInizio","Data inizio",array("required"=>true,"editable"=>true,"bottomLabel"=>"*Inserire la data di inizio dell'incarico", "placeholder"=>"inserisci qui la data di inizio."));
        
        //Data fine
        $wnd->AddDateField("sDataFine","Data conclusione",array("required"=>true,"editable"=>true,"bottomLabel"=>"*Inserire la data di conclusione dell'incarico", "placeholder"=>"inserisci qui la data di conclusione."));
        
        //Estremi del provvedimento
        $wnd->AddTextField("sEstremiProvvedimento","Estremi provvedimento",array("required"=>true,"bottomLabel"=>"*Riportare gli estremi del provvedimento di nomina.", "placeholder"=>"inserisci qui gli estremi del provvedimento di nomina."));
        
        //note
        $label="Note";
        $wnd->AddTextareaField("sNote",$label);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_incarico"=>$incarico->GetId()));
        $wnd->SetSaveTask("UpdateOrganismoIncarico");
        
        return $wnd;
    }
    
    //Template dlg aggiungi organismo incarico
    public function Template_GetOrganismoAddNewIncaricoDlg($object=null,$params=array())
    {
        $id=static::AA_UI_PREFIX."_GetOrganismoAddNewIncaricoDlg";
        
        if(!($object instanceof AA_Organismi)) return new AA_GenericWindowTemplate($id, "Aggiungi incarico", $this->id);

        $form_data['sNome']=$params['nome'];
        $form_data['sCognome']=$params['cognome'];
        $form_data['sCodiceFiscale']=$params['cf'];
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi incarico a ".$params['nome']." ".$params['cognome']." (".$params['cf'].")", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(160);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(600);
        
        //nomina Ras
        $wnd->AddSwitchBoxField("bNominaRas","Tipo nomina",array("onLabel"=>"RAS","offLabel"=>"non RAS","bottomLabel"=>"*Indica se la nomina è effettuata dalla RAS."));        
        
        //Tipologia
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Organismi_Const::GetTipoNomine() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $wnd->AddSelectField("nTipologia","Incarico",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il tipo di incarico.","tooltip"=>"Seleziona il tipo di incarico.","options"=>$options,"value"=>"0"));
        
        //Data inizio
        $wnd->AddDateField("sDataInizio","Data inizio",array("required"=>true,"editable"=>true,"bottomLabel"=>"*Inserire la data di inizio dell'incarico", "placeholder"=>"inserisci qui la data di inizio."));
        
        //Data fine
        $wnd->AddDateField("sDataFine","Data conclusione",array("required"=>true,"editable"=>true,"bottomLabel"=>"*Inserire la data di conclusione dell'incarico", "placeholder"=>"inserisci qui la data di conclusione."));
        
        //Estremi del provvedimento
        $wnd->AddTextField("sEstremiProvvedimento","Estremi provvedimento",array("required"=>true,"bottomLabel"=>"*Riportare gli estremi del provvedimento di nomina.", "placeholder"=>"inserisci qui gli estremi del provvedimento di nomina."));
        
        //note
        $label="Note";
        $wnd->AddTextareaField("sNote",$label);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("AddNewOrganismoIncarico");
        
        return $wnd;
    }
        
    //Template dlg aggiungi organismo incarico doc
    public function Template_GetOrganismoAddNewIncaricoDocDlg($object=null,$incarico=null)
    {
        $id=static::AA_UI_PREFIX."_GetOrganismoAddNewIncaricoDocDlg";
        
        if(!($object instanceof AA_Organismi) || !($incarico instanceof AA_OrganismiNomine)) return new AA_GenericWindowTemplate($id, "Aggiungi documento", $this->id);
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data['anno']=Date("Y");
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi documento per ".$incarico->GetNome()." ".$incarico->GetCognome()." (".$incarico->GetCodiceFiscale().")", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(160);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(360);
        
        //anno
        $options=array();
        $anno_fine=Date('Y');
        $anno_start=($anno_fine-5);
        
        for($i=$anno_fine; $i>=$anno_start; $i--)
        {
            $options[]=array("id"=>$i, "value"=>$i);
        }
        $wnd->AddSelectField("anno","Anno",array("required"=>true,"validateFunction"=>"IsInteger","bottomLabel"=>"*Indicare l'anno di riferimento.", "placeholder"=>"Scegli l'anno di riferimento.","options"=>$options,"value"=>Date('Y')));
        //$wnd->AddTextField("anno","Anno",array("required"=>true,"validateFunction"=>"IsInteger","bottomLabel"=>"*Riportare l'anno a quattro cifre.", "placeholder"=>"inserisci qui l'anno di riferimento."));
        
        //Tipologia
        $options=array();
        foreach(AA_Organismi_Const::GetTipoDocs() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $wnd->AddSelectField("tipo","Tipo di documento",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il tipo di documento.","bottomLabel"=>"*Seleziona il tipo di documento.","options"=>$options,"value"=>"0"));
        
        //file
        $wnd->AddFileUploadField("NewIncaricoDoc","", array("required"=>true, "validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf con firma digitale in formato PADES (dimensione max: 2Mb).","accept"=>"application/pdf"));
        
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_incarico"=>$incarico->GetId()));
        $wnd->SetSaveTask("AddNewOrganismoIncaricoDoc");
        
        return $wnd;
    }
    
    //Template dlg aggiungi provvedimento organismo
    public function Template_GetOrganismoAddNewProvvedimentoDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_GetOrganismoAddNewProvvedimentoDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi provvedimento", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(60);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(500);

        //Anno
        $anno_fine=Date('Y');
        $anno_start=($anno_fine-50);
        for($i=$anno_fine; $i>=$anno_start; $i--)
        {
            $options[]=array("id"=>$i, "value"=>$i);
        }
        $wnd->AddSelectField("anno","Anno",array("required"=>true,"validateFunction"=>"IsInteger","bottomLabel"=>"*Indicare l'anno di adozione del provvedimento.", "placeholder"=>"Scegli l'anno di adozione del provvedimento.","options"=>$options,"value"=>Date('Y')));
        
        //Tipologia
        $options=array();
        foreach(AA_Organismi_Const::GetTipoProvvedimenti() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $wnd->AddSelectField("tipo","Tipo",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il tipo di documento.","bottomLabel"=>"*Seleziona il tipo di provvedimento.","options"=>$options,"value"=>"0"));
        
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
         
        //url
        $wnd->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Section",array("type"=>"section","template"=>"oppure","align"=>"center")));
        
        //file
        $wnd->AddFileUploadField("NewProvvedimentoDoc","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf (dimensione max: 2Mb).","accept"=>"application/pdf"));
                
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("AddNewOrganismoProvvedimento");
        
        return $wnd;
    }
    
    //Template dlg modifica provvedimento organismo
    public function Template_GetOrganismoModifyProvvedimentoDlg($object=null,$provvedimento=null)
    {
        $id=static::AA_UI_PREFIX."_GetOrganismoModifyProvvedimentoDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("tipo"=>$provvedimento->GetTipologia(true),"url"=>$provvedimento->GetUrl(), "anno"=>$provvedimento->GetAnno());
        
        $wnd=new AA_GenericFormDlg($id, "Modifica provvedimento", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(60);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(500);
        
        //Anno
        $anno_fine=Date('Y');
        $anno_start=($anno_fine-50);
        for($i=$anno_fine; $i>=$anno_start; $i--)
        {
            $options[]=array("id"=>$i, "value"=>$i);
        }
        $wnd->AddSelectField("anno","Anno",array("required"=>true,"validateFunction"=>"IsInteger","bottomLabel"=>"*Indicare l'anno di adozione del provvedimento.", "placeholder"=>"Scegli l'anno di adozione del provvedimento.","options"=>$options,"value"=>Date('Y')));
        
        //Tipologia
        $options=array();
        foreach(AA_Organismi_Const::GetTipoProvvedimenti() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $wnd->AddSelectField("tipo","Tipo",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il tipo di documento.","bottomLabel"=>"*Seleziona il tipo di provvedimento.","options"=>$options,"value"=>"0"));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        //url
        $wnd->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Section",array("type"=>"section","template"=>"oppure","align"=>"center")));
        
        //file
        $wnd->AddFileUploadField("NewProvvedimentoDoc","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf (dimensione max: 2Mb).","accept"=>"application/pdf"));
                
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_provvedimento"=>$provvedimento->GetId()));
        $wnd->SetSaveTask("UpdateOrganismoProvvedimento");
        
        return $wnd;
    }
        
    //Template dlg modifica nominato incarico
    public function Template_GetOrganismoRenameNominaDlg($object=null,$params)
    {
        $id=$this->id."_GetOrganismoRenameNominaDlg";
        
        if(!($object instanceof AA_Organismi)) return new AA_GenericWindowTemplate($id, "Rinomina nomine", $this->id);

        
        $form_data['sNome']=$params['nome'];
        $form_data['sCognome']=$params['cognome'];
        $form_data['sCodiceFiscale']=$params['cf'];
        $form_data['ids']=$params['ids'];
        
        AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $wnd=new AA_GenericFormDlg($id, "Rinomina nomine", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(160);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(800);
        $wnd->SetHeight(400);

        //Nome
        $wnd->AddTextField("sNome","nome",array("required"=>true,"bottomLabel"=>"*Indicare il nuovo nome.", "placeholder"=>"inserisci qui il nome."));

        //cognome
        $wnd->AddTextField("sCognome","cognome",array("required"=>true,"bottomLabel"=>"*Indicare il nuovo cognome.", "placeholder"=>"inserisci qui il cognome."));
        
        //Codice fiscale
        $wnd->AddTextField("sCodiceFiscale","Codice fiscale",array("required"=>true,"bottomLabel"=>"*Indicare il nuovo codice fiscale.", "placeholder"=>"inserisci qui il codice fiscale."));

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("RenameOrganismoNomina");
        
        return $wnd;
    }
        
    //Template dlg aggiungi organismo incarico
    public function Template_GetOrganismoAddNewNominaDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_GetOrganismoAddNewNominaDlg";
        
        if(!($object instanceof AA_Organismi)) return new AA_GenericWindowTemplate($id, "Aggiungi nomina", $this->id);

        $form_data['sNome']="";
        $form_data['sCognome']="";
        $form_data['sCodiceFiscale']="";
        
        AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi nuova nomina", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(860);
        $wnd->SetHeight(520);

        //Nome
        $wnd->AddTextField("sNome","nome",array("required"=>true,"bottomLabel"=>"*Indicare il nome del nominato.", "placeholder"=>"inserisci qui il nome."));

        //Tipologia
        $options=array();
        foreach(AA_Organismi_Const::GetTipoNomine() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $wnd->AddSelectField("nTipologia","Incarico",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il tipo di incarico.","bottomLabel"=>"*Seleziona il tipo di incarico.","options"=>$options,"value"=>"0"),false);
        
        //cognome
        $wnd->AddTextField("sCognome","cognome",array("required"=>true,"bottomLabel"=>"*Indicare il cognome del nominato.", "placeholder"=>"inserisci qui il cognome."));

        //Data inizio
        $wnd->AddDateField("sDataInizio","Data inizio",array("required"=>true,"editable"=>true,"bottomLabel"=>"*Inserire la data di inizio dell'incarico", "placeholder"=>"inserisci qui la data di inizio."),false);
        
        //Codice fiscale
        $wnd->AddTextField("sCodiceFiscale","Codice fiscale",array("required"=>true,"bottomLabel"=>"*Indicare il codice fiscale del nominato.", "placeholder"=>"inserisci qui il codice fiscale."));
        
        //Data fine
        $wnd->AddDateField("sDataFine","Data fine",array("required"=>true,"editable"=>true,"bottomLabel"=>"*Inserire la data di conclusione dell'incarico", "placeholder"=>"inserisci qui la data di conclusione."),false);

        //nomina Ras
        $wnd->AddSwitchBoxField("bNominaRas","Tipo nomina",array("onLabel"=>"RAS","offLabel"=>"non RAS","bottomLabel"=>"*Indica se la nomina è effettuata dalla RAS."));                
        
        //Estremi del provvedimento
        $wnd->AddTextField("sEstremiProvvedimento","Estremi provvedimento",array("bottomLabel"=>"*Riportare gli estremi del provvedimento.", "placeholder"=>"inserisci qui gli estremi del provvedimento."),false);
        
        //note
        $label="Note";
        $wnd->AddTextareaField("sNote",$label);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("AddNewOrganismoNomina");
        
        return $wnd;
    }
    
    //Template dlg addnew dato contabile organismo
    public function Template_GetOrganismoAddNewDatoContabileDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_GetOrganismoAddNewDatoContabileDlg";
        
        $form_data['nIdParent']=$object->GetID();
        $form_data['nAnno']=Date("Y");
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi un nuovo dato contabile e dotazione organica", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(160);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(650);
        
        //anno
        $wnd->AddTextField("nAnno","Anno",array("tooltip"=>"Anno di riferimento", "validateFunction"=>"isNumber", "invalidMessage"=>"L'anno deve essere un numero intero a quatttro cifre","required"=>true,"bottomLabel"=>"*Inserire il valore numerico dell'anno a quattro cifre, es. 2021", "bottomPadding"=>30, "placeholder"=>"inserisci qui l'anno di riferimento"));
        
        //oneri totali
        $wnd->AddTextField("sOneriTotali","Oneri totali",array("validateFunction"=>"IsNumber", "tooltip"=>"Inserire solo valori numerici,<br>lasciare vuoto in caso di dati assenti", "bottomLabel"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui gli oneri totali"), false);

        //Spesa incarichi
        $wnd->AddTextField("sSpesaIncarichi","Spesa per incarichi",array("validateFunction"=>"IsNumber", "tooltip"=>"Inserire solo valori numerici,<br>lasciare vuoto in caso di dati assenti", "bottomLabel"=>"*Inserire la spesa (pagamenti) per incarichi di studio e consulenza, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui la spesa per incarichi"));

        //Spesa lavoro flessibile
        $wnd->AddTextField("sSpesaLavoroFlessibile","Spesa per lavoro flessibile",array("validateFunction"=>"IsNumber", "invalidMessage"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti","tooltip"=>"Inserire solo valori numerici,<br>lasciare vuoto in caso di dati assenti", "bottomLabel"=>"*Inserire la spesa (pagamenti) per il lavoro flessibile, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui la spesa per lavoro flessibile"),false);

        //spesa dotazione organica
        $wnd->AddTextField("sSpesaDotazioneOrganica","Spesa dot. organica",array("validateFunction"=>"IsNumber", "invalidMessage"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti","tooltip"=>"Indicare la spesa complessiva per la dotazione organica,<br>inserire solo valori numerici, lasciare vuoto in caso di dati assenti.", "bottomLabel"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui la spesa per la dotazione organica"));

        //Fatturato
        if($object->GetTipologia(true)==AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) $wnd->AddTextField("sFatturato","Fatturato",array("validateFunction"=>"IsNumber", "invalidMessage"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti","tooltip"=>"riportare il fatturato in euro per l'anno di riferimento,<br>inserire solamente valori numerici, lasciare vuoto in caso di dati assenti", "bottomLabel"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci il fatturato"),false);
        else $wnd->AddSpacer(false);

        //field personale a tempo determinato
        $dotazione = new AA_FieldSet("AA_SINES_ORGANISMI_DOTAZIONE_ORGANICA","Personale assunto a tempo determinato");

        //personale a tempo determinato dirigenti
        $dotazione->AddTextField("nDipendentiDetDir","Dirigenti",array("validateFunction"=>"IsInteger", "invalidMessage"=>"*Inserire solo numeri interi, lasciare vuoto in caso di dati assenti","tooltip"=>"Indicare il numero di unità di personale sia esterno che interno,<br> riportare solo valori numerici interi, lasciare vuoto in caso di dati assenti","bottomLabel"=>"*Indicare il numero di unità di personale sia esterno che interno,<br> riportare solo valori numerici interi, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui il numero di dipendenti"));

        //personale a tempo determinato
        $dotazione->AddTextField("nDipendentiDet","Non dirigenti",array("validateFunction"=>"IsInteger", "invalidMessage"=>"*Inserire solo numeri interi, lasciare vuoto in caso di dati assenti","tooltip"=>"Indicare il numero di unità di personale sia esterno che interno,<br> riportare solo valori numerici interi, lasciare vuoto in caso di dati assenti","bottomLabel"=>"*Indicare il numero di unità di personale sia esterno che interno,<br> riportare solo valori numerici interi, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui il numero di dipendenti"),false);

        //field dipendenti
        $dip = new AA_FieldSet("AA_SINES_ORGANISMI_DIPENDENTI","Personale assunto a tempo indeterminato");

        //dipendenti dirigenti
        $dip->AddTextField("nDipendentiDir","Dirigenti",array("validateFunction"=>"IsInteger", "invalidMessage"=>"*Inserire solo numeri interi, lasciare vuoto in caso di dati assenti","tooltip"=>"Indicare il numero di dipendenti (personale dirigente e non assunto a tempo indeterminato),<br>riportare solo valori numerici interi, lasciare vuoto in caso di dati assenti", "bottomLabel"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui il numero di dipendenti"));

        //dipendenti non dirigenti
        $dip->AddTextField("nDipendenti","Non dirigenti",array("validateFunction"=>"IsInteger", "invalidMessage"=>"*Inserire solo numeri interi, lasciare vuoto in caso di dati assenti","tooltip"=>"Indicare il numero di dipendenti (personale dirigente e non assunto a tempo indeterminato),<br>riportare solo valori numerici interi, lasciare vuoto in caso di dati assenti", "bottomLabel"=>"*Inserire solo valori numerici, lasciare vuoto in caso di dati assenti", "bottomPadding"=>30,"placeholder"=>"inserisci qui il numero di dipendenti"),false);
        
        $wnd->AddGenericObject($dip);
        $wnd->AddGenericObject($dotazione);

        //note
        $label="Note";
        $wnd->AddTextareaField("sNote",$label);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("AddNewOrganismoDatoContabile");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        
        return $wnd;
    }
    
    //Template dlg addnew bilancio
    public function Template_GetOrganismoAddNewBilancioDlg($object=null,$dato_contabile=null)
    {
        $id=$this->id."_AddNewBilancio_Dlg";
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi un nuovo bilancio", $this->id);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(480);
        $wnd->SetHeight(380);
        
        //Tipologia
        $options=array();
        foreach(AA_Organismi_Const::GetTipoBilanci() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $wnd->AddSelectField("nTipologia","Tipologia",array("required"=>true,"options"=>$options,"bottomLabel"=>"*Seleziona il tipo di bilancio."));

        //Risultati
        $wnd->AddTextField("sRisultati","Risultati",array("required"=>true, "validateFunction"=>"IsNumber", "bottomLabel"=>"*Inserire solo valori numerici o lasciare vuoto.", "placeholder"=>"inserisci qui i risultati di bilancio"));
                
        //note
        $label="Note";
        $wnd->AddTextareaField("sNote",$label, array("bottomPadding"=>0));

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("AddNewOrganismoBilancio");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_dato_contabile"=>$dato_contabile->GetId()));
        
        return $wnd;
    }
    
    //Template dlg modify bilancio
    public function Template_GetOrganismoModifyBilancioDlg($object=null,$dato_contabile=null,$bilancio=null)
    {
        $id=$this->id."_ModifyBilancio_Dlg";
        
        $form_data['nTipologia']=$bilancio->GetTipo(true);
        $form_data['sRisultati']=$bilancio->GetRisultati();
        $form_data['sNote']=$bilancio->GetNote();
        
        $wnd=new AA_GenericFormDlg($id, "Modifica bilancio", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(480);
        $wnd->SetHeight(380);
        
        //Tipologia
        $options=array();
        foreach(AA_Organismi_Const::GetTipoBilanci() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $wnd->AddSelectField("nTipologia","Tipologia",array("required"=>true,"options"=>$options,"bottomLabel"=>"*Seleziona il tipo di bilancio."));

        //Risultati
        $wnd->AddTextField("sRisultati","Risultati",array("required"=>true, "validateFunction"=>"IsNumber", "bottomLabel"=>"*Inserire solo valori numerici o lasciare vuoto.", "placeholder"=>"inserisci qui i risultati di bilancio"));
                
        //note
        $label="Note";
        $wnd->AddTextareaField("sNote",$label, array("bottomPadding"=>0));

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateOrganismoBilancio");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_dato_contabile"=>$dato_contabile->GetId(),"id_bilancio"=>$bilancio->GetId()));
        
        return $wnd;
    }
    
    //Template Detail
    public function TemplateSection_Detail($params)
    {
        $id=static::AA_UI_PREFIX."_Detail_";
        $organismo= new AA_Organismi($params['id'],$this->oUser);
        if(!$organismo->isValid())
        {
            return new AA_JSON_Template_Template(
                        static::AA_UI_PREFIX."_".static::AA_UI_DETAIL_BOX,
                        array("update_time"=>Date("Y-m-d H:i:s"),
                        "name"=>"Dettaglio scheda organismo",
                        "type"=>"clean","template"=>AA_Log::$lastErrorLog));            
        }
        
        $perms=$organismo->GetUserCaps($this->oUser);

        #Stato
        if($organismo->GetStatus() & AA_Const::AA_STATUS_BOZZA) $status="bozza";
        if($organismo->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $status="pubblicata";
        if($organismo->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $status.=" revisionata";
        if($organismo->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $status.=" cestinata";
        $status="<span class='AA_Label AA_Label_LightBlue' title='Stato scheda organismo'>".$status."</span>";
        
        #Dettagli
        if(($perms&AA_Const::AA_PERMS_PUBLISH) > 0 && $organismo->GetAggiornamento() != "")
        {
            //Aggiornamento
            $details="<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;".$organismo->GetAggiornamento(true)."</span>&nbsp;";
            
            //utente e log
            $lastLog=$organismo->GetLog()->GetLastLog();
            if($lastLog['user']=="")
            {
                $lastLog['user']=$organismo->GetUser()->GetUsername();
            }

            $details.="<span class='AA_Label AA_Label_LightBlue' title=\"Nome dell'utente che ha compiuto l'ultima azione - Fai click per visualizzare il log delle azioni\"><span class='mdi mdi-account' onClick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetLogDlg', 'params': {id: ".$organismo->GetId()."}},'".$this->GetId()."');\">".$lastLog['user']."</span>&nbsp;";
            
            //id
            $details.="</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;".$organismo->GetId()."</span>";
        } 
        else
        {
            if($organismo->GetAggiornamento() != "") $details="<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;".$organismo->GetAggiornamento(true)."</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;".$organismo->GetId()."</span>";
        }

        $id_org=$organismo->GetID();
        
        if(($perms & AA_Const::AA_PERMS_WRITE) ==0) $details.="&nbsp;<span class='AA_Label AA_Label_LightBlue' title=\" L'utente corrente non può apportare modifiche all'organismo\"><span class='mdi mdi-pencil-off'></span>&nbsp; sola lettura</span>";
        
        $header=new AA_JSON_Template_Layout($id."Header"."_$id_org",array("type"=>"clean", "height"=>38,"css"=>"AA_SectionContentHeader"));
        $header->addCol(new AA_JSON_Template_Generic($id."TabBar"."_$id_org",array(
            "view"=>"tabbar",
            "borderless"=>true,
            "value"=>$id."Generale_Tab"."_$id_org",
            "css"=>"AA_Header_TabBar",
            "width"=>400,
            "multiview"=>true,
            "view_id"=>$id."Multiview"."_$id_org",
            "options"=>array(
                array("id"=>$id."Generale_Tab"."_$id_org", "value"=>"Generale"),
                array("id"=>$id."DatiContabili_Tab"."_$id_org","value"=>"Dati contabili", "tooltip"=>"Dati contabili e dotazione organica"),
                array("id"=>$id."Nomine_Tab"."_$id_org","value"=>"Nomine"))
        )));
        $header->addCol(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $header->addCol(new AA_JSON_Template_Generic($id."Detail"."_$id_org",array(
            "view"=>"template",
            "borderless"=>true,
            "css"=>"AA_SectionContentHeader",
            "minWidth"=>500,
            "template"=>"<div style='display: flex; width:100%; height: 100%; justify-content: center; align-items: center;'>#status#<span>&nbsp;&nbsp;</span><span>#detail#</span></div>",
            "data"=>array("detail"=>$details,"status"=>$status)
        )));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar"."_$id_org",array(
            "type"=>"clean",
            "css"=>array("background"=>"#ebf0fa","border-color"=>"transparent"),
            "width"=>400
        ));
        
        //Inserisce il pulsante di pubblicazione
        if(($perms & AA_Const::AA_PERMS_PUBLISH) > 0 && ($organismo->GetStatus()&AA_Const::AA_STATUS_BOZZA) > 0 && ($organismo->GetStatus()&AA_Const::AA_STATUS_CESTINATA) == 0)
        {
            $menu_data[]= array(
                            "id"=>$this->id."_Publish"."_$id_org",
                            "value"=>"Pubblica",
                            "tooltip"=>"Pubblica l'elemento",
                            "icon"=>"mdi mdi-certificate",
                            "module_id"=>$this->id,
                            "handler"=>"sectionActionMenu.publish",
                            "handler_params"=>array("task"=>"GetOrganismoPublishDlg","object_id"=>$organismo->GetID())
                        );
        }
        
        //Inserisce il pulsante di riassegnazione ripristino
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0)
        {
            if(($organismo->GetStatus()&AA_Const::AA_STATUS_CESTINATA) == 0)
            {
                //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
                //$menu_spacer=true;
                $menu_data[]= array(
                        "id"=>$this->id."_Reassign"."_$id_org",
                        "value"=>"Riassegna",
                        "tooltip"=>"Riassegna l'elemento",
                        "icon"=>"mdi mdi-share-all",
                        "module_id"=>$this->id,
                        "handler"=>"sectionActionMenu.reassign",
                        "handler_params"=>array("task"=>"GetOrganismoReassignDlg","object_id"=>$organismo->GetID())
                    );                
            }
            if(($organismo->GetStatus() & AA_Const::AA_STATUS_CESTINATA) > 0)
            {
                $menu_data[]= array(
                        "id"=>$id."_Resume"."_$id_org",
                        "value"=>"Ripristina",
                        "tooltip"=>"Ripristina gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                        "icon"=>"mdi mdi-recycle",
                        "module_id"=>$this->id,
                        "handler"=>"sectionActionMenu.resume",
                        "handler_params"=>array("task"=>"GetOrganismoResumeDlg","object_id"=>$organismo->GetID())
                    );
            }
        }
        
        //Inserisce le voci di esportazione
        //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
        //$menu_spacer=true;
        $menu_data[]= array(
                    "id"=>$id."_SaveAsPdf"."_$id_org",
                    "value"=>"Esporta in pdf",
                    "tooltip"=>"Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file pdf",
                    "icon"=>"mdi mdi-file-pdf",
                    "module_id"=>$this->id,
                    "handler"=>"sectionActionMenu.saveAsPdf",
                    "handler_params"=>array("task"=>"GetOrganismoSaveAsPdfDlg","object_id"=>$organismo->GetID())
                );  
        $menu_data[]= array(
                    "id"=>$id."_SaveAsCsv"."_$id_org",
                    "value"=>"Esporta in csv",
                    "tooltip"=>"Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file csv",
                    "icon"=>"mdi mdi-file-table",
                    "module_id"=>$this->id,
                    "handler"=>"sectionActionMenu.saveAsCsv",
                    "handler_params"=>array("task"=>"GetOrganismoSaveAsCsvDlg","object_id"=>$organismo->GetID())
                );
        #-------------------------------------
        
        //Inserisce la voce di eliminazione
        if(($perms & AA_Const::AA_PERMS_DELETE) > 0)
        {
            if(($organismo->GetStatus() & AA_Const::AA_STATUS_CESTINATA) == 0)
            {
                //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
                //$menu_spacer=true;
                
                $menu_data[]= array(
                            "id"=>$id."_Trash"."_$id_org",
                            "value"=>"Cestina",
                            "css"=>"AA_Menu_Red",
                            "tooltip"=>"Cestina l'elemento",
                            "icon"=>"mdi mdi-trash-can",
                            "module_id"=>$this->id,
                            "handler"=>"sectionActionMenu.trash",
                            "handler_params"=>array("task"=>"GetOrganismoTrashDlg","object_id"=>$organismo->GetID())
                        );
            }
            else
            {
                
                $menu_data[]= array(
                            "id"=>$id."_Delete"."_$id_org",
                            "value"=>"Elimina",
                            "css"=>"AA_Menu_Red",
                            "tooltip"=>"Elimina definitivamente l'elemento",
                            "icon"=>"mdi mdi-trash-can",
                            "module_id"=>$this->id,
                            "handler"=>"sectionActionMenu.delete",
                            "handler_params"=>array("task"=>"GetOrganismoDeleteDlg","object_id"=>$organismo->GetID())
                        );
            }
        }
        
        //Azioni
        $scriptAzioni="try{"
                . "let azioni_btn=$$('".$id."_Azioni_btn_$id_org');"
                . "if(azioni_btn){"
                . "let azioni_menu=webix.ui(azioni_btn.config.menu_data);"
                . "if(azioni_menu){"
                . "azioni_menu.setContext(azioni_btn);"
                . "azioni_menu.show(azioni_btn.\$view);"
                . "}"
                . "}"
                . "}catch(msg){console.error('".$id."_Azioni_btn_$id_org',this,msg);AA_MainApp.ui.alert(msg);}";
        $azioni_btn=new AA_JSON_Template_Generic($id."_Azioni_btn"."_$id_org",array(
            "view"=>"button",
            "type"=>"icon",
            "icon"=>"mdi mdi-dots-vertical",
            "label"=>"Azioni",
            "align"=>"right",
            "autowidth"=>true,
            "menu_data"=>new AA_JSON_Template_Generic($id."_ActionMenu"."_$id_org",array("view"=>"contextmenu","data"=>$menu_data, "module_id"=>$this->GetId(),"on"=>array("onItemClick"=>"AA_MainApp.utils.getEventHandler('onDetailMenuItemClick','".$this->GetId()."')"))),
            "tooltip"=>"Visualizza le azioni disponibili",
            "click"=>$scriptAzioni
        ));
        
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $toolbar->addElement($azioni_btn);
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>15)));
        
        $header->addCol(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $header->addCol($toolbar);
        
        //Content box
        $content = new AA_JSON_Template_Layout($id."Content_Box",
                array(
                "type"=>"clean",
                "name"=>$organismo->GetDenominazione(),
                "filtered"=>true
            ));
        $content->AddRow($header);
        
        $multiview=new AA_JSON_Template_Multiview($id."Multiview"."_$id_org",array(
            "type"=>"clean",
            "css"=>"AA_Detail_Content"
         ));
        $multiview->addCell($this->TemplateDettaglio_Generale_Tab($organismo));
        $multiview->addCell($this->TemplateDettaglio_DatiContabili_Tab($organismo));
        $multiview->addCell($this->TemplateDettaglio_Nomine_Tab($organismo));
        $content->AddRow($multiview);
        
        return $content;
    }
    
    //Template section detail, tab generale
    public function TemplateDettaglio_Generale_Tab($object=null)
    {
        $id=static::AA_UI_PREFIX."_Detail_Generale_Tab_".$object->GetId();
        $rows_fixed_height=50;
        if(!($object instanceof AA_Organismi)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38));
        
        $soc_tags="";
        $soc_tags="<span class='AA_Label AA_Label_Green' title='Forma giuridica'>".$object->GetFormaSocietaria()."</span>&nbsp;";
        if($object->IsInHouse() == true) $soc_tags.="<span class='AA_Label AA_Label_Green'>in house</span>&nbsp;";
        if($object->IsInTUSP() == true) $soc_tags.="<span class='AA_Label AA_Label_Green'>TUSP</span>";
        if($object->GetPartecipazione() == "" || $object->GetPartecipazione() == "0") $soc_tags.="<span class='AA_DataView_Tag AA_Label AA_Label_Green' title='Società non direttamente partecipata dalla RAS'>indiretta</span>";
        
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        if(($object->GetTipologia(true)&AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0) $toolbar->addElement(new AA_JSON_Template_Generic($id."_SocTags",array("view"=>"label","label"=>$soc_tags, "align"=>"center")));
        
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //Pulsante di modifica
        $canModify=false;
        if(($object->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;
        if($canModify)
        {            
            $modify_btn=new AA_JSON_Template_Generic($id."_Modify_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil",
                "label"=>"Modifica",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Modifica le informazioni generali",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoModifyDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        
        $layout->addRow($toolbar);

        //Piva
        $value=$object->GetPivaCf();
        if($value=="")$value="n.d.";
        $piva=new AA_JSON_Template_Template($id."_Piva",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Partita iva/codice fiscale:","value"=>$value)
        ));
        
        //Sede legale
        $value=$object->GetSedeLegale();
        if($value=="")$value="n.d.";
        $sede_legale=new AA_JSON_Template_Template($id."_SedeLegale",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Sede legale:","value"=>$value)
        ));

        //Pec
        $value=$object->GetPec();
        if($value=="") $value="n.d.";
        $pec=new AA_JSON_Template_Template($id."_Pec",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"PEC:","value"=>$value)
        ));
        
        //Sito web
        $value="<a href='".$object->GetSitoWeb()."' target='_blank'>".$object->GetSitoWeb()."</a>";
        if($object->GetSitoWeb()=="")$value="n.d.";
        $sito=new AA_JSON_Template_Template($id."_SitoWeb",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Sito web:","value"=>$value)
        ));
        
        //Funzioni attribuite
        $value=$object->GetFunzioni();
        if($value=="")$value="n.d.";
        $funzioni=new AA_JSON_Template_Template($id."_Funzioni",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Funzioni attribuite:","value"=>$value)
        ));
        
        //data inizio
        $value=$object->GetDataInizioImpegno();
        if($value=="0000-00-00") $value="n.d.";
        $data_inizio=new AA_JSON_Template_Template($id."_DataInizio",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Data inizio impegno:","value"=>$value)
        ));
        
        //data costituzione
        $value=$object->GetDataInizioImpegno();
        if($value=="0000-00-00") $value="n.d.";
        $data_costituzione=new AA_JSON_Template_Template($id."_DataInizio",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Data costituzione:","value"=>$value)
        ));    

        //data fine
        $value=$object->GetDataFineImpegno();
        if($value=="0000-00-00") $value="n.d.";
        if($value=="9999-12-31") $value="a tempo indeterminato";
        $data_fine=new AA_JSON_Template_Template($id."_DataFine",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Data fine impegno:","value"=>$value)
        ));
        
        //data cessazione
        $value=$object->GetDataFineImpegno();
        if($value=="0000-00-00") $value="n.d.";
        if($value=="9999-12-31") $value="a tempo indeterminato";
        $data_cessazione=new AA_JSON_Template_Template($id."_DataFine",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Data cessazione:","value"=>$value)
        ));
        
        //partecipazione
        $value=$object->GetPartecipazione();
        if($value=="" || $value=="0") $value="indiretta";
        else
        {
            $part=explode("/",$value);
            $value="€ ".$part[0]." pari al ".$part[1]."% delle quote totali";
        }
        $partecipazione=new AA_JSON_Template_Template($id."_DataFine",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Partecipazione:","value"=>$value)
        ));
        
        //Stato
        $value=$object->GetStatoOrganismo();
        if($value=="")$value="n.d.";
        $stato_società=new AA_JSON_Template_Template($id."_DataFine",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Stato società:","value"=>$value)
        ));        
        
        //note
        $value=$object->GetNote();
        if($value=="")$value="n.d.";
        $note=new AA_JSON_Template_Template($id."_Note",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Note:","value"=>$value)
        ));
        
        //Prima riga
        $riga=new AA_JSON_Template_Layout($id."_FirstRow",array("height"=>$rows_fixed_height,"css"=>array("border-top"=>"1px solid #dadee0 !important")));
        $riga->AddCol($piva);
        if(($object->GetTipologia(true)&AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0) $riga->AddCol($data_inizio);
        else $riga->AddCol($data_costituzione);
        $layout->AddRow($riga);
        
        //seconda riga
        $riga=new AA_JSON_Template_Layout($id."_SecondRow",array("height"=>$rows_fixed_height));
        $riga->AddCol($sede_legale);
        if(($object->GetTipologia(true)&AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0) $riga->AddCol($data_fine);
        else $riga->AddCol($data_cessazione);
        $layout->AddRow($riga);
        
        //terza riga
        $riga=new AA_JSON_Template_Layout($id."_ThirdRow",array("height"=>$rows_fixed_height));
        $riga->AddCol($pec);
        if(($object->GetTipologia(true)&AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0) $riga->AddCol($partecipazione);
        else $riga->AddCol(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $layout->AddRow($riga);
        
        //Quarta riga
        $riga=new AA_JSON_Template_Layout($id."_FourRow",array("height"=>$rows_fixed_height));
        $riga->AddCol($sito);
        if(($object->GetTipologia(true)&AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0) $riga->AddCol($stato_società);
        else $riga->AddCol(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $layout->AddRow($riga);
        
        //layout ultima riga
        $last_row=new AA_JSON_Template_Layout($id."_LastRow");
        $riga=new AA_JSON_Template_Layout($id."_FiveRow",array("css"=>array("border-top"=>"1px solid #dadee0 !important")));
        
        //funzioni
        $riga->AddRow($funzioni);
        
        //note
        $riga->AddRow($note);
        
        $last_row->AddCol($riga);
        
        //documenti
        $last_row->AddCol($this->TemplateDettaglio_Provvedimenti($object, $id, $canModify));
        
        $layout->AddRow($last_row);
        
        return $layout;
    }
    
    //Template section detail, tab dati contabili
    public function TemplateDettaglio_DatiContabili_Tab($object=null)
    {
        $id=static::AA_UI_PREFIX."_Detail_DatiContabili_Tab_".$object->GetID();
        if(!($object instanceof AA_Organismi)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
        //flag società
        if(($object->GetTipologia(true)&AA_Organismi_Const::AA_ORGANISMI_SOCIETA_PARTECIPATA) > 0) $società=true;
        else $società=false;
        
        //righe con altezza imposta
        $rows_fixed_height=50;
        
        //permessi
        $perms = $object->GetUserCaps($this->oUser);
        $canModify=false;
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;
        
        //layout generale
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"width"=>130));
        
        if($canModify)
        {            
            //Pulsante di Aggiunta dato contabile
            $addnew_btn=new AA_JSON_Template_Generic($id."_AddNew_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi annualità",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoAddNewDatoContabileDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));
            $toolbar->AddElement($addnew_btn);
        }
        
        $header=new AA_JSON_Template_Layout($id."_Header",array("type"=>"clean", "height"=>38, "css"=>"AA_SectionContentHeader"));
        
        $tabbar=new AA_JSON_Template_Generic($id."_TabBar",array(
            "view"=>"tabbar",
            "borderless"=>true,
            "css"=>"AA_Bottom_TabBar",
            "multiview"=>true,
            "optionWidth"=>100,
            "view_id"=>$id."_Multiview",
            "type"=>"bottom"
        ));
        
        $header->AddCol($tabbar);
        $header->AddCol(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $header->AddCol($toolbar);
        
        $multiview=new AA_JSON_Template_Multiview($id."_Multiview",array(
            "type"=>"clean",
            "css"=>"AA_Detail_Content"
         ));
        $layout->AddRow($multiview);
        $layout->addRow($header);
        
        //Aggiunge gli anni come tab
        $dati_contabili=$object->GetDatiContabili();
        $options=array();
        
        foreach($dati_contabili as $idDato=>$curDato)
        {
            $anno=$curDato->GetAnno();
            if($canModify) $label="<div style='display: flex; justify-content: space-between; align-items: center; padding-left: 5px; padding-right: 5px;'><span>".$anno."</span><a style='margin-left: 1em;' class='AA_DataTable_Ops_Button_Red' title='Elimina annualità' onClick='".'AA_MainApp.utils.callHandler("dlg", {task:"GetOrganismoTrashDatoContabileDlg", params: [{id: "'.$object->GetId().'"},{id_dato_contabile:"'.$curDato->GetId().'"}]},"'.$this->id.'")'."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $label="<div style='display: flex; justify-content: center; align-items: center; padding-left: 5px; padding-right: 5px;'><span>".$anno."</span></div>";
            $options[]=array("id"=>$id."_".$curDato->GetID()."_Tab", "id_rec"=>$idDato, "value"=>$label);
            
            $curAnno=new AA_JSON_Template_Layout($id."_".$curDato->GetID()."_Tab",array("type"=>"clean"));
            
            $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38, "css"=>"AA_Header_Tabbar_Title"));
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
            $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_".$curDato->GetID(),array("view"=>"label","label"=>"<span style='color:#003380'>Dati contabili e dotazione organica - anno ".$anno."</span>", "align"=>"center")));
                
            //Pulsante di Modifica dato contabile
            if($canModify)
            {
                $modify_btn=new AA_JSON_Template_Generic($id."_Modify_".$curDato->GetID()."_btn",array(
                   "view"=>"button",
                    "type"=>"icon",
                    "icon"=>"mdi mdi-pencil",
                    "label"=>"Modifica",
                    "align"=>"right",
                    "width"=>120,
                    "tooltip"=>"Modifica dati contabili e dotazione organica per l'anno ".$anno,
                    "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoModifyDatoContabileDlg\", params: [{id: ".$object->GetId()."},{id_dato_contabile:".$curDato->GetId()."}]},'$this->id')"
                ));                
                $toolbar->AddElement($modify_btn);
            }
            else
            {
                $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
            }
            
            $curAnno->AddRow($toolbar);
            
            //Oneri totali
            $value=$curDato->GetOneriTotali();
            if($value=="")$value="n.d.";
            $val1=new AA_JSON_Template_Template($id."_OneriTotali_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Oneri totali:","value"=>$value)
            ));

            //Dotazione organica
            $value=$curDato->GetDotazioneOrganica();
            if($value=="") $value="n.d.";
            $val2=new AA_JSON_Template_Template($id."_DotazioneOrganica_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Dotazione organica:","value"=>$value)
            ));
            
            //Prima riga
            $riga=new AA_JSON_Template_Layout($id."_FirstRow_".$curDato->GetID(),array("height"=>$rows_fixed_height));
            $riga->AddCol($val1);$riga->AddCol($val2);
            $curAnno->AddRow($riga);
            
            //Spesa lavoro flessibile
            $value=$curDato->GetSpesaLavoroFlessibile();
            if($value=="")$value="n.d.";
            $val1=new AA_JSON_Template_Template($id."_SpesaLavoroFlessibile_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Spesa lavoro flessibile:","value"=>$value)
            ));

            //Dipendenti
            $value=intVal($curDato->GetDipendenti());
            $value2=intVal($curDato->GetDipendentiDir());
            if($value+$value2 == 0)$value="n.d.";
            if($value2 > 0)
            {
                $value=($value+$value2)." di cui ".$value2." dirigenti";
            }
            $val2=new AA_JSON_Template_Template($id."_Dipendenti_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Personale assunto a tempo indeterminato:","value"=>$value)
            ));

            // riga
            $riga=new AA_JSON_Template_Layout($id."_SecondRow_".$curDato->GetID(),array("height"=>$rows_fixed_height));
            $riga->AddCol($val1);$riga->AddCol($val2);
            $curAnno->AddRow($riga);


            //spesa incarichi
            $value=$curDato->GetSpesaIncarichi();
            if($value=="")$value="n.d.";
            $val1=new AA_JSON_Template_Template($id."_SpesaIncarichi_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Spesa incarichi:","value"=>$value)
            ));

            #Dipendenti a tempo
            $value=intVal($curDato->GetDipendentiDet());
            $value2=intVal($curDato->GetDipendentiDetDir());
            if($value+$value2 == 0)$value="n.d.";
            if($value2 > 0)
            {
                $value=($value+$value2)." di cui ".$value2." dirigenti";
            }
            $val2=new AA_JSON_Template_Template($id."_DipendentiDet_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Personale assunto a tempo determinato:","value"=>$value)
            ));          

            //riga
            $riga=new AA_JSON_Template_Layout($id."_FourRow_".$curDato->GetID(),array("height"=>$rows_fixed_height));
            $riga->AddCol($val1);$riga->AddCol($val2);
            $curAnno->AddRow($riga);

            #Fatturato
            if($società)
            {
                $value=$curDato->GetFatturato();
                if($value=="")$value="n.d.";
                $val1=new AA_JSON_Template_Template($id."_Fatturato_".$curDato->GetID(),array(
                    "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                    "data"=>array("title"=>"Fatturato:","value"=>$value)
                ));
            }
            else
            {
                $val1 = new AA_JSON_Template_Generic("",array("view"=>"spacer"));
            }
            
            //Spesa dotazione 
            $value=$curDato->GetSpesaDotazioneOrganica();
            if($value=="")$value="n.d.";
            $val2=new AA_JSON_Template_Template($id."_SpesaDotazione_".$curDato->GetID(),array(
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Spesa dotazione organica:","value"=>$value)
            ));

            //riga
            $riga=new AA_JSON_Template_Layout($id."_FiveRow_".$curDato->GetID(),array("height"=>$rows_fixed_height));           
            $riga->AddCol($val1);$riga->AddCol($val2);
            $curAnno->AddRow($riga);
            
            //note
            $value=$curDato->GetNote();
            $val1=new AA_JSON_Template_Template($id."_Note_".$curDato->GetID(),array("height"=>60,
                "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                "data"=>array("title"=>"Note:","value"=>$value)
            ));

            $riga=new AA_JSON_Template_Layout($id."_SixRow_".$curDato->GetID(), array("css"=>array("border-top"=>"1px solid #dadee0 !important")));
            $riga->AddCol($val1);
            $curAnno->AddRow($riga);
            
            #bilanci----------------------------------
           
            $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar_Bilanci_".$curDato->GetID(),array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

            $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_Bilanci_Title_".$curDato->GetID(),array("view"=>"label","label"=>"<span style='color:#003380'>Bilanci e risultati di amministrazione - anno ".$anno."</span>", "align"=>"center")));

            if($canModify)
            {
                //Pulsante di aggiunta bilancio
                $add_bilancio_btn=new AA_JSON_Template_Generic($id."_AddBilancio_".$curDato->GetID()."_btn",array(
                   "view"=>"button",
                    "type"=>"icon",
                    "icon"=>"mdi mdi-pencil-plus",
                    "label"=>"Aggiungi",
                    "align"=>"right",
                    "width"=>120,
                    "tooltip"=>"Aggiungi bilancio per l'anno ".$anno,
                    "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoAddNewBilancioDlg\", params: [{id: ".$object->GetId()."},{id_dato_contabile:".$curDato->GetId()."}]},'$this->id')"
                ));

                $toolbar->AddElement($add_bilancio_btn);
            }
            else 
            {
                $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
            }
            
            $curAnno->AddRow($toolbar);
            
            $options_bilanci=array();
            
            if($canModify)
            {
                $options_bilanci[]=array("id"=>"tipo", "header"=>"Tipo di bilancio", "width"=>250, "css"=>array("text-align"=>"left"));
                $options_bilanci[]=array("id"=>"risultati", "header"=>"Risultati in €", "width"=>350,"css"=>array("text-align"=>"center"));
                $options_bilanci[]=array("id"=>"note", "header"=>"note", "fillspace"=>true,"css"=>array("text-align"=>"left"));
                $options_bilanci[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
            }
            else
            {
                $options_bilanci[]=array("id"=>"tipo", "header"=>"Tipo di bilancio", "width"=>250, "css"=>array("text-align"=>"left"));
                $options_bilanci[]=array("id"=>"risultati", "header"=>"Risultati in €", "width"=>350,"css"=>array("text-align"=>"center"));
                $options_bilanci[]=array("id"=>"note", "header"=>"note", "fillspace"=>true,"css"=>array("text-align"=>"left"));                
            }
            
            $bilanci=new AA_JSON_Template_Generic($id."_Bilanci_".$curDato->GetID(),array("view"=>"datatable", "scrollX"=>false, "select"=>true,"css"=>"AA_Header_DataTable","headerRowHeight"=>28,"columns"=>$options_bilanci));
            
            $bilanci_data=array();
            foreach($curDato->GetBilanci($this->oUser) as $id_bil=>$curBil)
            {
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetOrganismoModifyBilancioDlg", params: [{id: "'.$object->GetId().'"},{id_dato_contabile:"'.$curDato->GetId().'"},{id_bilancio:"'.$curBil->GetId().'"}]},"'.$this->id.'")';
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetOrganismoTrashBilancioDlg", params: [{id: "'.$object->GetId().'"},{id_dato_contabile:"'.$curDato->GetId().'"},{id_bilancio:"'.$curBil->GetId().'"}]},"'.$this->id.'")';
                $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
                $bilanci_data[]=array("id"=>$curBil->GetId(),"id_tipo"=>$curBil->GetTipo(),"id_dato_contabile"=>$curDato->GetID(),"id_organismo"=>$object->GetID(),"tipo"=>$curBil->GetTipo(),"risultati"=>$curBil->GetRisultati(),"note"=>$curBil->GetNote(),"ops"=>$ops);
            }
            $bilanci->SetProp("data",$bilanci_data);
            if(sizeof($bilanci_data) > 0) 
            {
                //AA_Log::Log(__METHOD__." Aggiungo il bilancio: ".print_r($bilanci,true),100);
                $curAnno->AddRow($bilanci);
            }
            else $curAnno->AddRow(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
            #--------------------------------------
            
            
            //$multiview->AddCell(new AA_JSON_Template_Generic($id."_ScrollView_".$curDato->GetID()."_Tab",array("view"=>"scrollview","scroll"=>"y","body"=>$curAnno)));
            $multiview->AddCell($curAnno);
        }
        
        $tabbar->SetProp("options",$options);
        
        return $layout;
    }
    
    //Template section detail, tab nomina
    public function TemplateDettaglio_Nomine_Tab($object=null,$filterData="")
    {
        $id=static::AA_UI_PREFIX."_Detail_Nomine_Tab_".$object->GetID();
        if(!($object instanceof AA_Organismi)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
        //permessi
        $perms = $object->GetUserCaps($this->oUser);
        $canModify=false;
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;
        
        //layout generale
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"borderless"=>true,"width"=>130));
        
        if($canModify)
        {            
            //Pulsante di Aggiunta nomina
            $addnew_btn=new AA_JSON_Template_Generic($id."_AddNew_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-account-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi nomina",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoAddNewNominaDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));
            
            //pulsante di filtraggio
            $saveFilterId=$id;
            $filterDlgTask="GetOrganismiNomineFilterDlg";
            $filterClickAction= "try{module=AA_MainApp.getModule('".$this->id."'); if(module.isValid()){module.ui.dlg('".$filterDlgTask."',module.getRuntimeValue(".$saveFilterId.",'filter_data'),'".$this->id."')}}catch(msg){console.error(msg)}";

            $filter_btn = new AA_JSON_Template_Generic($id."_Filter_btn",array(
                "view"=>"button",
                "align"=>"right",
                "type"=>"icon",
                "icon"=>"mdi mdi-filter",
                "label"=>"Filtra",
                "width"=>80,
                "filter_data"=>$filterData,
                "tooltip"=>"Imposta un filtro di ricerca",
                "click"=>$filterClickAction
            ));
            $toolbar->AddElement(new AA_JSON_Template_Generic());
            $toolbar->AddElement($addnew_btn);
        }
        
        $footer=new AA_JSON_Template_Layout($id."_Footer",array("type"=>"clean", "height"=>38, "css"=>"AA_SectionContentHeader"));
        
        $tabbar=new AA_JSON_Template_Generic($id."_TabBar",array(
            "view"=>"tabbar",
            "borderless"=>true,
            "css"=>"AA_Bottom_TabBar",
            "multiview"=>true,
            "optionWidth"=>130,
            "view_id"=>$id."_Multiview",
            "type"=>"bottom"
        ));
        
        $footer->AddCol($tabbar);
        //$header->AddCol(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $footer->AddCol($toolbar);
        
        $multiview=new AA_JSON_Template_Multiview($id."_Multiview",array(
            "type"=>"clean",
            "css"=>"AA_Detail_Content"
         ));
        $layout->AddRow($multiview);
        $layout->addRow($footer);
        
        //Recupera le nomine
        $riepilogo_layout_id=$id."_Riepilogo_Layout";
        $filterNomine="";
        $filter= AA_SessionVar::Get($id);
        if($filter->isValid())
        {
            $params=(array)$filter->GetValue();
            //AA_Log::Log(__METHOD__." - ".print_r($params,true),100);
        }
        $nomine=$object->GetNomineGrouped($params);
        #--------------------------------
        
        //AA_Log::Log(__METHOD__.print_r($nomine,true),100);
        
        $options=array();
        
        //Data odierna
        $now = Date("Y-m-d");
        
        foreach($nomine as $id_intestazione_nomina=>$curNomina)
        {
            //Dati riepilogo
            $riepilogo_data_item=array("nome"=>trim(current($curNomina)->GetNome()),"cognome"=>trim(current($curNomina)->GetCognome()),"cf"=>"");
            if(trim(current($curNomina)->GetCodiceFiscale()) !="") $riepilogo_data_item['cf']=" (".trim(current($curNomina)->GetCodiceFiscale()).")";
            
            $tab_label=current($curNomina)->GetNome()." ".current($curNomina)->GetCognome();
            if(strlen($tab_label)>20)
            {
                $original_size=strlen($tab_label);
                $tab_label=explode(" ",$tab_label);
                
                $tab_result=$tab_label[0];
                if((strlen($tab_label[1])+strlen($tab_label[2])) < 13 && $tab_label[2] !="") $tab_result.=" ".$tab_label[1]." ".$tab_label[2];
                else $tab_result.=" ".$tab_label[1];
                    
                if(strlen($tab_result) != $original_size) $tab_result.="...";
                //if(strlen($tab_result) > 24) $tab_result.=substr($tab_result,0,10)."...";
                $tab_label=$tab_result;
            }
            $ids=array_keys($curNomina);
            if($canModify) $tab_label="<div style='display: flex; justify-content: space-between; align-items: center; padding-left: 5px; padding-right: 5px; font-size: smaller'><span>".$tab_label."</span><a style='margin-left: 1em;' class='AA_DataTable_Ops_Button_Red' title='Elimina nomina' onClick='".'AA_MainApp.utils.callHandler("dlg", {task:"GetOrganismoTrashNominaDlg", params: [{ids: "'.json_encode($ids).'"},{id: "'.$object->GetID().'"}]},"'.$this->id.'")'."'><span class='mdi mdi-trash-can'></span></a></div>";
            else$tab_label="<div style='display: flex; justify-content: center; align-items: center; padding-left: 5px; padding-right: 5px; font-size: smaller'><span>".$tab_label."</span></div>";
           
            //Tab label
            $options[]=array("id"=>$id."_".$id_intestazione_nomina."_Tab", "value"=>$tab_label);
            
            $curNominaTab=new AA_JSON_Template_Layout($id."_".$id_intestazione_nomina."_Tab",array("type"=>"clean"));
            
            $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>42, "css"=>"AA_Header_Tabbar_Title"));
            
            //torna al riepilogo
            $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Riepilogo_".$id_intestazione_nomina."_btn",array(
                   "view"=>"button",
                    "type"=>"icon",
                    "icon"=>"mdi mdi-keyboard-backspace",
                    "label"=>"Riepilogo",
                    "align"=>"left",
                    "width"=>120,
                    "tooltip"=>"Torna al riepilogo",
                    "click"=>"$$('".$tabbar->GetId()."').setValue('".$riepilogo_layout_id."')"
                )));
            
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
            
            //Nomina label
            $incaricato_label="<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%;'><span style='color:#003380; font-weight: 900; font-size: larger;'>".current($curNomina)->GetNome()." ".current($curNomina)->GetCognome()."</span>";
            if(current($curNomina)->GetCodiceFiscale() !="") $incaricato_label.="<span style='font-size: smaller;'>(".current($curNomina)->GetCodiceFiscale().")</span>";
            $incaricato_label.="</div>";
            $toolbar->AddElement(new AA_JSON_Template_Template($id."_Toolbar_".$id_intestazione_nomina,array("type"=>"clean","template"=>$incaricato_label)));
                
            //Pulsante di Modifica nome, cognome, cf
            if($canModify)
            {
                $addnew_btn=new AA_JSON_Template_Generic($id."_AddNewIncarico_".$id_intestazione_nomina."_btn",array(
                   "view"=>"button",
                    "type"=>"icon",
                    "icon"=>"mdi mdi-pencil-plus",
                    "label"=>"Aggiungi",
                    "align"=>"right",
                    "width"=>120,
                    "tooltip"=>"Aggiungi un nuovo incarico",
                    "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoAddNewIncaricoDlg\", params: [{id: ".$object->GetId()."},{nome:\"".current($curNomina)->GetNome()."\"},{cognome:\"".current($curNomina)->GetCognome()."\"},{cf:\"".current($curNomina)->GetCodiceFiscale()."\"},{id: ".$object->GetID()."}]},'$this->id')"
                ));
                
                $modify_btn=new AA_JSON_Template_Generic($id."_Modify_".$id_intestazione_nomina."_btn",array(
                   "view"=>"button",
                    "type"=>"icon",
                    "icon"=>"mdi mdi-pencil",
                    "label"=>"Modifica",
                    "align"=>"right",
                    "width"=>120,
                    "tooltip"=>"Modifica nome, cognome e codice fiscale del nominato",
                    "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoRenameNominaDlg\", params: [{id: ".$object->GetId()."},{ids:".json_encode($ids)."},{nome: \"".current($curNomina)->GetNome()."\"},{cognome: \"".current($curNomina)->GetCognome()."\"},{cf: \"".current($curNomina)->GetCodiceFiscale()."\"}]},'$this->id')"
                ));
                $toolbar->AddElement($addnew_btn);
                $toolbar->AddElement($modify_btn);
            }
            else
            {
                $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>240)));
            }
            
            $curNominaTab->AddRow($toolbar);
            
            $incarichi=new AA_JSON_Template_Generic($id."_Incarichi_".$id_intestazione_nomina,array("view"=>"accordion"));
            $collapsed=false;
            foreach($curNomina as $id_incarico=>$incarico)
            {
                //dati incarico per riepilogo
                if($incarico->GetDataFine() > $now) 
                {
                    if($incarico->GetNominaRas() == "1") $riepilogo_data_item['incarichi'].="<span class='AA_Label AA_Label_LightGreen'>".$incarico->GetTipologia()."</span>&nbsp;";
                    else $riepilogo_data_item['incarichi'].="<span class='AA_Label AA_Label_LightBlue'>".$incarico->GetTipologia()."</span>&nbsp;";
                }
                else $riepilogo_data_item['incarichi'].="<span class='AA_Label AA_Label_LightRed'>".$incarico->GetTipologia()."</span>&nbsp;";
                        
                //Intestazione incarico            
                $header="<span class='AA_Accordion_Item_Header_Selected'>".$incarico->GetTipologia()."</span>";
                $headerAlt="<span class='AA_Accordion_Item_Header_Collapsed'>".$incarico->GetTipologia()."</span>";
                $curIncaricoAccordionItem=new AA_JSON_Template_Generic($id."_Incarico_".$id_intestazione_nomina."_".$id_incarico,array("view"=>"accordionitem", "css"=>"AA_AccordionHeaderItem","header"=>$header,"headerAlt"=>$headerAlt));
                $curIncaricoAccordionItem->SetProp("collapsed",$collapsed);
                
                //contenuto incarico
                $incarico_body=new AA_JSON_Template_Layout($id."_Incarico_".$id_intestazione_nomina."_".$id_incarico."_Content",array("type"=>"clean","scroll"=>"auto"));
                $curId=$id."_Incarico_".$id_intestazione_nomina."_".$id_incarico."_Content";

                $toolbar=new AA_JSON_Template_Toolbar($curId."_Toolbar",array("height"=>42, "css"=>"AA_Header_Tabbar_Title"));

                //stato incarico
                $incarico_label="<span class='AA_Label AA_Label_LightBlue'>in corso</span>&nbsp;";
                if($incarico->GetDataFine() < $now) $incarico_label="<span class='AA_Label AA_Label_LightRed'>scaduto</span>&nbsp;";
                if($incarico->GetNominaRas()) $incarico_label.="<span class='AA_Label AA_Label_LightGreen'>nomina RAS</span>";
                $toolbar->AddElement(new AA_JSON_Template_Template($curId."_Nomina_Ras",array("type"=>"clean", "width"=>170,"template"=>"<div style='margin-top: 2px; padding-left: .7em; border-right: 1px solid #dedede;'><span style='font-weight: 700;'>Stato incarico: </span><br>$incarico_label</div>")));
                    
                //Codice fiscale
                //$value=$incarico->GetCodiceFiscale();
                //if($value=="") $value="n.d";
                //$toolbar->AddElement(new AA_JSON_Template_Template($curId."_Codice_Fiscale",array("type"=>"clean", "width"=>150,"template"=>"<div style='padding-left: .7em; margin-top: 2px;'><span style='font-weight: 700;'>Codice fiscale: </span><br>".$value."</div>")));
                
                //data inizio
                $toolbar->AddElement(new AA_JSON_Template_Template($curId."_Data_Inizio",array("type"=>"clean", "width"=>150,"template"=>"<div style='padding-left: .7em;margin-top: 2px;'><span style='font-weight: 700;'>Data inizio incarico: </span><br>".$incarico->GetDataInizio()."</div>")));
                    
                //data fine
                $toolbar->AddElement(new AA_JSON_Template_Template($curId."_Data_Fine",array("type"=>"clean","width"=>150, "template"=>"<div style='padding-left: .7em;margin-top: 2px; border-left: 1px solid #dedede'><span style='font-weight: 700;'>Data fine incarico: </span><br>".$incarico->GetDataFine()."</div>")));

                //estremi provvedimento
                $value=$incarico->GetEstremiProvvedimento();
                if($value=="") $value="n.d.";
                $toolbar->AddElement(new AA_JSON_Template_Template($curId."_Estremi_Provvedimento",array("type"=>"clean","width"=>220, "template"=>"<div style='padding-left: .7em;margin-top: 2px; border-left: 1px solid #dedede'><span style='font-weight: 700;'>Estremi del provvedimento: </span><br>".$value."</div>")));
                
                //Trattamento economico complessivo
                $value=$incarico->GetCompensoSpettante();
                if($value=="") $value="n.d.";
                $toolbar->AddElement(new AA_JSON_Template_Template($curId."_Compenso_Spettante",array("type"=>"clean","width"=>300, "template"=>"<div style='padding-left: .7em;margin-top: 2px; border-left: 1px solid #dedede'><span style='font-weight: 700;'>Trattamento economico complessivo in €: </span><br>".$value."</div>")));
                    
                $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
                
                if($canModify)
                {
                    //Pulsante di Modifica dato incarico                                
                    $modify_btn=new AA_JSON_Template_Generic($curId."_Modify_btn",array(
                       "view"=>"button",
                        "type"=>"icon",
                        "icon"=>"mdi mdi-pencil",
                        "label"=>"Modifica",
                        "align"=>"right",
                        "width"=>120,
                        "tooltip"=>"Modifica i dati dell'incarico visualizzato",
                        "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoModifyIncaricoDlg\", params: [{id: ".$object->GetId()."},{id_incarico:".$id_incarico."}]},'$this->id')"
                    ));
                    
                    //pulsante di eliminazione incarico
                    $trash_btn=new AA_JSON_Template_Generic($curId."_Trash_btn",array(
                       "view"=>"button",
                        "type"=>"icon",
                        "css"=>"AA_Button_Red",
                        "icon"=>"mdi mdi-trash-can",
                        "label"=>"Elimina",
                        "align"=>"right",
                        "width"=>120,
                        "tooltip"=>"Elimina i dati dell'incarico visualizzato",
                        "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoTrashIncaricoDlg\", params: [{id: ".$object->GetId()."},{id_incarico:".$id_incarico."}]},'$this->id')"
                    ));
                    $toolbar->AddElement($modify_btn);
                    $toolbar->AddElement($trash_btn);    
                }
                
                $incarico_body->AddRow($toolbar);
                
                //note
                $value=$incarico->GetNote();
                $val1=new AA_JSON_Template_Template($curId."_Note",array("height"=>60,"css"=>"AA_Header_Tabbar_Title",
                    "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
                    "data"=>array("title"=>"Note:","value"=>$value)
                ));
                $incarico_body->AddRow($val1);                
                
                //Riga compensi e documenti
                $curId=$curId."_Compensi_Documenti";
                $incarico_row_data=new AA_JSON_Template_Layout($curId,array());
                
                #compensi----------------------------------
                $curId=$curId."_Layout_Compensi";
                $incarico_compensi=new AA_JSON_Template_Layout($curId,array("type"=>"clean","css"=>array("border-right"=>"1px solid #dedede !important;")));
                
                $toolbar=new AA_JSON_Template_Toolbar($curId."_Toolbar_Compensi",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
                $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

                $toolbar->AddElement(new AA_JSON_Template_Generic($curId."_Toolbar_Compensi_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Trattamento economico</span>", "align"=>"center")));

                if($canModify)
                {
                    //Pulsante di aggiunta compenso
                    $add_compenso_btn=new AA_JSON_Template_Generic($curId."_AddCompenso_btn",array(
                       "view"=>"button",
                        "type"=>"icon",
                        "icon"=>"mdi mdi-pencil-plus",
                        "label"=>"Aggiungi",
                        "align"=>"right",
                        "width"=>120,
                        "tooltip"=>"Aggiungi trattamento economico per l'incarico",
                        "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoAddNewIncaricoCompensoDlg\", params: [{id: ".$object->GetId()."},{id_incarico:".$incarico->GetId()."}]},'$this->id')"
                    ));

                    $toolbar->AddElement($add_compenso_btn);
                }
                else 
                {
                    $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
                }

                $incarico_compensi->AddRow($toolbar);

                $options_compensi=array();

                if($canModify)
                {
                    $options_compensi[]=array("id"=>"anno", "header"=>"Anno", "width"=>60, "css"=>array("text-align"=>"left"));
                    $options_compensi[]=array("id"=>"parte_fissa", "header"=>"Parte fissa in €", "width"=>150,"css"=>array("text-align"=>"center"));
                    $options_compensi[]=array("id"=>"parte_variabile", "header"=>"Parte variabile in €", "width"=>150,"css"=>array("text-align"=>"center"));
                    $options_compensi[]=array("id"=>"rimborsi", "header"=>"Rimborsi in €", "width"=>150,"css"=>array("text-align"=>"center"));
                    $options_compensi[]=array("id"=>"totale", "header"=>"Totale (fissa+variabile) in €", "fillspace"=>true,"css"=>array("text-align"=>"center"));
                    $options_compensi[]=array("id"=>"note", "header"=>"Note", "fillspace"=>true,"css"=>array("text-align"=>"center"));
                    $options_compensi[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
                }
                else
                {
                    $options_compensi[]=array("id"=>"anno", "header"=>"Anno", "width"=>60, "css"=>array("text-align"=>"left"));
                    $options_compensi[]=array("id"=>"parte_fissa", "header"=>"Parte fissa in €", "width"=>150,"css"=>array("text-align"=>"center"));
                    $options_compensi[]=array("id"=>"parte_variabile", "header"=>"Parte variabile in €", "width"=>150,"css"=>array("text-align"=>"center"));
                    $options_compensi[]=array("id"=>"rimborsi", "header"=>"Rimborsi in €", "width"=>150,"css"=>array("text-align"=>"center"));
                    $options_compensi[]=array("id"=>"totale", "header"=>"Totale (fissa+variabile) in €", "fillspace"=>true,"css"=>array("text-align"=>"center"));
                    $options_compensi[]=array("id"=>"note", "header"=>"Note", "fillspace"=>true,"css"=>array("text-align"=>"center"));
                }

                $compensi=new AA_JSON_Template_Generic($curId."_Compensi_Table",array("view"=>"datatable", "headerRowHeight"=>28, "select"=>true, "scrollX"=>false,"css"=>"AA_Header_DataTable","columns"=>$options_compensi));

                $compensi_data=array();
                foreach($incarico->GetCompensi($this->oUser) as $id_comp=>$curComp)
                {
                    $tot=0;
                    $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetOrganismoModifyIncaricoCompensoDlg", params: [{id: "'.$object->GetId().'"},{id_incarico:"'.$incarico->GetId().'"},{id_compenso:"'.$curComp->GetId().'"}]},"'.$this->id.'")';
                    $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetOrganismoTrashIncaricoCompensoDlg", params: [{id: "'.$object->GetId().'"},{id_incarico:"'.$incarico->GetId().'"},{id_compenso:"'.$curComp->GetId().'"}]},"'.$this->id.'")';
                    $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
                    $tot= number_format(str_replace(array(".",","),array("","."),$curComp->GetParteFissa())+str_replace(array(".",","),array("","."),$curComp->GetParteVariabile()),2,",",".");
                    $compensi_data[]=array("id"=>$id_comp,"anno"=>$curComp->GetAnno(),"parte_fissa"=>number_format(str_replace(array(".",","),array("","."),$curComp->GetParteFissa()),2,",","."),"parte_variabile"=>number_format(str_replace(array(".",","),array("","."),$curComp->GetParteVariabile()),2,",","."),"rimborsi"=>number_format(str_replace(array(".",","),array("","."),$curComp->GetRimborsi()),2,",","."),"note"=>$curComp->GetNote(), "totale"=>$tot,"ops"=>$ops);
                }
                $compensi->SetProp("data",$compensi_data);
                if(sizeof($compensi_data) > 0) $incarico_compensi->AddRow($compensi);
                else $incarico_compensi->AddRow(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
                #--------------------------------------
                
                #documenti----------------------------------
                $curId=$curId."_Layout_Documenti";
                $incarico_documenti=new AA_JSON_Template_Layout($curId,array("type"=>"clean","css"=>array("border-left"=>"1px solid #dedede !important;")));
                
                $toolbar=new AA_JSON_Template_Toolbar($curId."_Toolbar_Documenti",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
                $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

                $toolbar->AddElement(new AA_JSON_Template_Generic($curId."_Toolbar_Documenti_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Documenti</span>", "align"=>"center")));

                if($canModify)
                {
                    //Pulsante di aggiunta documento
                    $add_documento_btn=new AA_JSON_Template_Generic($curId."_AddDocumento_btn",array(
                       "view"=>"button",
                        "type"=>"icon",
                        "icon"=>"mdi mdi-file-plus",
                        "label"=>"Aggiungi",
                        "align"=>"right",
                        "width"=>120,
                        "tooltip"=>"Aggiungi documento per l'incarico",
                        "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoAddNewIncaricoDocDlg\", params: [{id: ".$object->GetId()."},{id_incarico:".$incarico->GetId()."}]},'$this->id')"
                    ));

                    $toolbar->AddElement($add_documento_btn);
                }
                else 
                {
                    $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
                }

                $incarico_documenti->AddRow($toolbar);

                $options_documenti=array();

                if($canModify)
                {
                    $options_documenti[]=array("id"=>"anno", "header"=>"Anno", "width"=>60, "css"=>array("text-align"=>"left"));
                    $options_documenti[]=array("id"=>"tipo", "header"=>"Tipo", "fillspace"=>true,"css"=>array("text-align"=>"center"));
                    $options_documenti[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
                }
                else
                {
                    $options_documenti[]=array("id"=>"anno", "header"=>"Anno", "width"=>60, "css"=>array("text-align"=>"left"));
                    $options_documenti[]=array("id"=>"tipo", "header"=>"Tipo", "fillspace"=>true,"css"=>array("text-align"=>"center"));
                    $options_documenti[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
                }

                $documenti=new AA_JSON_Template_Generic($curId."_Documenti_Table",array("view"=>"datatable", "headerRowHeight"=>28, "select"=>true,"scrollX"=>false,"css"=>"AA_Header_DataTable","columns"=>$options_documenti));

                $documenti_data=array();
                foreach($incarico->GetDocs() as $id_doc=>$curDoc)
                {
                    $modify='AA_MainApp.utils.callHandler("pdfPreview", {url: "'.$curDoc->GetPublicDocumentPath().'&embed=1"},"'.$this->id.'")';
                    $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetOrganismoTrashIncaricoDocDlg", params: [{id: "'.$object->GetId().'"},{id_incarico:"'.$incarico->GetId().'"},{anno:"'.$curDoc->GetAnno().'"},{tipo:"'.$curDoc->GetTipologia(true).'"}]},"'.$this->id.'")';
                    if($canModify) $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Download' onClick='".$modify."'><span class='mdi mdi-floppy'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
                    else $ops="<div class='AA_DataTable_Ops' style='justify-content: center'><a class='AA_DataTable_Ops_Button' title='Download' onClick='".$modify."'><span class='mdi mdi-floppy'></span></a></div>";
                    $documenti_data[]=array("id"=>$id_doc,"anno"=>$curDoc->GetAnno(),"id_tipo"=>$curDoc->GetTipologia(true) ,"tipo"=>$curDoc->GetTipologia(),"ops"=>$ops);
                }
                $documenti->SetProp("data",$documenti_data);
                if(sizeof($documenti_data) > 0) $incarico_documenti->AddRow($documenti);
                else $incarico_documenti->AddRow(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
                #--------------------------------------
                
                //Aggiunge i compensi
                $incarico_row_data->AddCol($incarico_compensi);
                
                //Aggiunge uno spazio
                $incarico_row_data->AddCol(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>10)));
                
                //Aggiunge i documenti
                $incarico_row_data->AddCol($incarico_documenti);
                
                //Aggiunge compensi e documenti al body dell'elemento
                $incarico_body->AddRow($incarico_row_data);
                                
                //Imposta il contenuto dell'incarico corrente
                $curIncaricoAccordionItem->SetProp("body",$incarico_body);
                
                //Aggiunge l'incarico alla lista
                $incarichi->AddRow($curIncaricoAccordionItem);
                
                //Espande di default solo il primo
                $collapsed=true;
            }
            
            //Aggiunge la lista incarichi alla pagina
            $curNominaTab->AddRow($incarichi);
            
            //Aggiunge una barra
            $curNominaTab->AddRow(new AA_JSON_Template_Generic("",array("view"=>"spacer","height"=>3,"css"=>array("border-top"=>"1px solid #dedede !important;"))));
            
            //Imposta l'azione del pulsante di dettaglio
            $riepilogo_data_item["onclick"]='$$("'.$tabbar->GetId().'").setValue("'.$curNominaTab->GetId().'")';
            $riepilogo_data[]=$riepilogo_data_item;
            
            //Aggiunge la pagina alla lista delle pagine
            $multiview->AddCell($curNominaTab);
        }
        
        //Riepilogo tab
        $riepilogo_layout=$this->TemplateDettaglio_Nomine_Riepilogo_Tab($object,$id, $riepilogo_data, $id);
        
        array_unshift($options,array("id"=>$riepilogo_layout->GetId(), "value"=>"Riepilogo"));
        
        $multiview->AddCell($riepilogo_layout,true);
        
        $tabbar->SetProp("options",$options);
        
        return $layout;
    }
    
    //Template dettaglio provvedimenti
    public function TemplateDettaglio_Provvedimenti($object=null,$id="", $canModify=false)
    {
        #documenti----------------------------------
        $curId=$id."_Layout_Provvedimenti";
        $provvedimenti=new AA_JSON_Template_Layout($curId,array("type"=>"clean","css"=>array("border-left"=>"1px solid #dedede !important;")));

        $toolbar=new AA_JSON_Template_Toolbar($curId."_Toolbar_Provvedimenti",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

        $toolbar->AddElement(new AA_JSON_Template_Generic($curId."_Toolbar_Provvedimenti_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Provvedimenti</span>", "align"=>"center")));

        if($canModify)
        {
            //Pulsante di aggiunta documento
            $add_documento_btn=new AA_JSON_Template_Generic($curId."_AddProvvedimento_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-file-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi provvedimento",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoAddNewProvvedimentoDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));

            $toolbar->AddElement($add_documento_btn);
        }
        else 
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        }

        $provvedimenti->AddRow($toolbar);

        $options_documenti=array();

        if($canModify)
        {
            $options_documenti[]=array("id"=>"anno", "header"=>"Anno", "width"=>"auto","css"=>array("text-align"=>"left"));
            $options_documenti[]=array("id"=>"tipo", "header"=>"Tipo", "fillspace"=>true,"css"=>array("text-align"=>"center"));
            $options_documenti[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
        }
        else
        {
            $options_documenti[]=array("id"=>"anno", "header"=>"Anno", "width"=>"auto","css"=>array("text-align"=>"left"));
            $options_documenti[]=array("id"=>"tipo", "header"=>"Tipo", "fillspace"=>true,"css"=>array("text-align"=>"center"));
            $options_documenti[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
        }

        $documenti=new AA_JSON_Template_Generic($curId."_Provvedimenti_Table",array("view"=>"datatable", "headerRowHeight"=>28, "select"=>true,"scrollX"=>false,"css"=>"AA_Header_DataTable","columns"=>$options_documenti));

        $documenti_data=array();
        foreach($object->GetProvvedimenti() as $id_doc=>$curDoc)
        {
            if($curDoc->GetUrl() == "")
            {
                $view='AA_MainApp.utils.callHandler("pdfPreview", {url: "'.$curDoc->GetFilePublicPath().'&embed=1"},"'.$this->id.'")';
                $view_icon="mdi-floppy";
            }
            else 
            {
                $view='AA_MainApp.utils.callHandler("wndOpen", {url: "'.$curDoc->GetUrl().'"},"'.$this->id.'")';
                $view_icon="mdi-eye";
            }
            
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetOrganismoTrashProvvedimentoDlg", params: [{id: "'.$object->GetId().'"},{id_provvedimento:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetOrganismoModifyProvvedimentoDlg", params: [{id: "'.$object->GetId().'"},{id_provvedimento:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            if($canModify) $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Vedi' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $ops="<div class='AA_DataTable_Ops' style='justify-content: center'><a class='AA_DataTable_Ops_Button' title='Vedi' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a></div>";
            $documenti_data[]=array("id"=>$id_doc,"id_tipo"=>$curDoc->GetTipologia(true) ,"tipo"=>$curDoc->GetTipologia(),"anno"=>$curDoc->GetAnno(),"ops"=>$ops);
        }
        $documenti->SetProp("data",$documenti_data);
        if(sizeof($documenti_data) > 0) $provvedimenti->AddRow($documenti);
        else $provvedimenti->AddRow(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        #--------------------------------------
        
        return $provvedimenti;
    }
    
    //Template dettaglio riepilogo nomine
    public function TemplateDettaglio_Nomine_Riepilogo_Tab($object=null,$id="",$riepilogo_data=array(),$filter_id="")
    {
        //permessi
        $perms = $object->GetUserCaps($this->oUser);
        $canModify=false;
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;
        
        $riepilogo_layout=new AA_JSON_Template_Layout($id."_Riepilogo_Layout",array("type"=>"clean"));
        
        $riepilogo_template="<div style='display: flex; justify-content: space-between; align-items: center; height: 100%'><div class='AA_DataView_ItemContent'>"
            . "<div><span class='AA_DataView_ItemTitle'>#nome# #cognome#</span><span style='font-size: smaller'>#cf#</span></div>"
            . "<div><span class='AA_DataView_ItemSubTitle'>#incarichi#</span></div>"
            . "</div><div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%; padding: 5px'><a title='Visualizza i dettagli degli incarichi' onclick='#onclick#' class='AA_Button_Link'><span class='mdi mdi-account-search'></span>&nbsp;<span>Dettagli</span></a></div></div>";
        $riepilogo_tab=new AA_JSON_Template_Generic($id."_Riepilogo_Tab",array(
            "view"=>"dataview",
            "filtered"=>true,
            "xCount"=>1,
            "module_id"=>$this->id,
            "type"=>array(
                "type"=>"tiles",
                "height"=>60,
                "width"=>"auto",
                "css"=>"AA_DataView_Nomine_item",
            ),
            "template"=>$riepilogo_template,
            "data"=>$riepilogo_data
        ));
        
        $toolbar_riepilogo=new AA_JSON_Template_Toolbar($id."_Toolbar_Riepilogo",array("height"=>38,"borderless"=>true));
        
        //Flag filtri
        $filter= AA_SessionVar::Get($filter_id);
        if($filter->isValid())
        {
            $label="<div style='display: flex; height: 100%; justify-content: flex-start; align-items: center;'>Mostra:";
            
            $values=(array)$filter->GetValue();
            
            //Scadute
            if($values['scadute']=="0") $label.="&nbsp;<span class='AA_Label AA_Label_LightBlue'>solo in corso</span>";
            //else $label.="<span class='AA_Label AA_Label_LightBlue'>mostra scadute</span>";

            //in corso
            if($values['in_corso']=="0") $label.="&nbsp;<span class='AA_Label AA_Label_LightBlue'>solo scadute</span>";
            //else $label.="<span class='AA_Label AA_Label_LightBlue'>mostra in corso</span>";
            
            //nomina ras
            if($values['nomina_ras']=="0") $label.="&nbsp;<span class='AA_Label AA_Label_LightBlue'>solo nomine non RAS</span>";
            //else $label.="<span class='AA_Label AA_Label_LightBlue'>mostra nomine RAS</span>";

            //nomina altri
            if($values['nomina_altri']=="0") $label.="&nbsp;<span class='AA_Label AA_Label_LightBlue'>solo nomine RAS</span>";
            //else $label.="<span class='AA_Label AA_Label_LightBlue'>mostra nomine non RAS</span>";
            
            //Incarico
            $incarichi= AA_Organismi_Const::GetTipoNomine();
            if($values['tipo'] > 0) $label.="&nbsp;<span class='AA_Label AA_Label_LightBlue'>".$incarichi[$values['tipo']]."</span>";
            
            //tutte
            if(($values['scadute'] == "1" || !isset($values['scadute'])) && ($values['in_corso'] == "1" || !isset($values['in_corso'])) && ($values['nomina_ras'] =="1" || !isset($values['nomina_ras'])) && ($values['nomina_altri'] =="1" || !isset($values['nomina_altri'])) && ($values['tipo'] == 0 || !isset($values['tipo'])))
            {
                $label.="&nbsp;<span class='AA_Label AA_Label_LightBlue'>tutte</span>";
            }
            
            $label.="</div>";
                    
            $toolbar_riepilogo->AddElement(new AA_JSON_Template_Generic($id."_Filter_Label",array("view"=>"label","label"=>$label, "width"=>"400", "align"=>"left")));
        }
        else
        {
            $toolbar_riepilogo->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>400)));
        }
        
        $toolbar_riepilogo->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        $toolbar_riepilogo->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_Riepilogo_Intestazione",array("view"=>"label","label"=>"<span style='color:#003380'>Riepilogo nomine</span>", "align"=>"center","width"=>"180")));
        $toolbar_riepilogo->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        if($canModify)
        {            
            //Pulsante di Aggiunta nomina
            $addnew_btn=new AA_JSON_Template_Generic($id."_AddNewUp_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-account-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi nomina",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetOrganismoAddNewNominaDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));
            
            //pulsante di filtraggio
            if($filter_id=="") $filter_id=$id;
            
            $filterDlgTask="GetOrganismoNomineFilterDlg";
            $filterClickAction="AA_MainApp.utils.callHandler('dlg',{task: '".$filterDlgTask."', params:[{filter_id: '".$filter_id."'}]},'".$this->id."')";

            $filter_btn = new AA_JSON_Template_Generic($id."_FilterUp_btn",array(
                "view"=>"button",
                "align"=>"right",
                "type"=>"icon",
                "icon"=>"mdi mdi-filter",
                "label"=>"Filtra",
                "width"=>80,
                "tooltip"=>"Imposta un filtro di ricerca",
                "click"=>$filterClickAction
            ));
            
            $toolbar_riepilogo->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>200)));
            $toolbar_riepilogo->AddElement($filter_btn);
            $toolbar_riepilogo->AddElement($addnew_btn);
        }
        else
        {
            $toolbar_riepilogo->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>400)));
        }
        
        $riepilogo_layout->AddRow($toolbar_riepilogo);
        $riepilogo_layout->AddRow($riepilogo_tab);
        
        return $riepilogo_layout;
    }
    
    //Template bozze context menu
    public function TemplateActionMenu_Bozze()
    {
         
        $menu=new AA_JSON_Template_Generic("AA_ActionMenuBozze",
            array(
            "view"=>"contextmenu",
            "data"=>array(array(
                "id"=>"refresh_bozze",
                "value"=>"Aggiorna",
                "icon"=>"mdi mdi-reload",
                "module_id"=>$this->GetId(),
                "handler"=>"refreshUiObject",
                "handler_params"=>array(static::AA_UI_PREFIX."_".static::AA_UI_BOZZE_BOX,true)
                ))
            ));
        
        return $menu; 
    }
    
    //Template scadenzario context menu
    public function TemplateActionMenu_Scadenzario()
    {
         
        $menu=new AA_JSON_Template_Generic("AA_ActionMenuScadenzario",
            array(
            "view"=>"contextmenu",
            "data"=>array(array(
                "id"=>"refresh_scadenzario",
                "value"=>"Aggiorna",
                "icon"=>"mdi mdi-reload",
                "module_id"=>$this->GetId(),
                "handler"=>"refreshUiObject",
                "handler_params"=>array(static::AA_UI_PREFIX."_".static::AA_UI_SCADENZARIO_BOX,true)
                ))
            ));
        
        return $menu; 
    }
    
    //Template pubblicate context menu
    public function TemplateActionMenu_Pubblicate()
    {
         
        $menu=new AA_JSON_Template_Generic("AA_ActionMenuPubblicate",
            array(
            "view"=>"contextmenu",
            "data"=>array(array(
                "id"=>"refresh_pubblicate",
                "value"=>"Aggiorna",
                "icon"=>"mdi mdi-reload",
                "module_id"=>$this->GetId(),
                "handler"=>"refreshUiObject",
                "handler_params"=>array(static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX,true)
                )
                )
            ));
        
        return $menu; 
    }
    
    //Template revisionate context menu
    public function TemplateActionMenu_Revisionate()
    {
         
        $menu=new AA_JSON_Template_Generic("AA_ActionMenuRevisionate",
            array(
            "view"=>"contextmenu",
            "data"=>array(array(
                "id"=>"refresh_revisionate",
                "value"=>"Aggiorna",
                "icon"=>"mdi mdi-reload",
                "module_id"=>$this->GetId(),
                "handler"=>"refreshUiObject",
                "handler_params"=>array(static::AA_UI_PREFIX."_".static::AA_UI_REVISIONATE_BOX,true)
                ))
            ));
        
        return $menu; 
    }
    
    //Template detail context menu
    public function TemplateActionMenu_Detail()
    {
         
        $menu=new AA_JSON_Template_Generic("AA_ActionMenuDetail",
            array(
            "view"=>"contextmenu",
            "data"=>array(array(
                "id"=>"refresh_detail",
                "value"=>"Aggiorna",
                "icon"=>"mdi mdi-reload",
                "panel_id"=>"back",
                "section_id"=>"Dettaglio",
                "module_id"=>$this->GetId(),
                "handler"=>"refreshUiObject",
                "handler_params"=>array(static::AA_UI_PREFIX."_".static::AA_UI_DETAIL_BOX,true)
                ))
            ));
        
        return $menu; 
    }
    
    //Template navbar bozze
    public function TemplateNavbar_Bozze($level=1,$last=false,$refresh_view=true)
    {
        $class="n".$level;
        if($last) $class.=" AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(static::AA_UI_PREFIX."_Navbar_Link_Bozze_Content_Box",array(
                "type"=>"clean",
                "section_id"=>"Bozze",
                "module_id"=>$this->GetId(),
                "refresh_view"=>$refresh_view,
                "tooltip"=>"Fai click per visualizzare le schede in bozza",
                "template"=>"<div class='AA_navbar_link_box_left #class#'><a class='AA_Sines_Navbar_Link_Bozze_Content_Box' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"Bozze\",\"".$this->id."\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data"=>array("label"=>"Bozze","icon"=>"mdi mdi-file-document-edit","class"=>$class))
            );
        return $navbar;  
    }
    
    //Template navbar pubblicate
    public function TemplateNavbar_Pubblicate($level=1,$last=false,$refresh_view=true)
    {
        $class="n".$level;
        if($last) $class.=" AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(static::AA_UI_PREFIX."_Navbar_Link_".static::AA_UI_PUBBLICATE_BOX,array(
                "type"=>"clean",
                "section_id"=>"Pubblicate",
                "module_id"=>$this->GetId(),
                "refresh_view"=>$refresh_view,
                "tooltip"=>"Fai click per visualizzare le schede pubblicate",
                "template"=>"<div class='AA_navbar_link_box_left #class#'><a class='AA_Sines_Navbar_Link_Pubblicate_Content_Box' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"Pubblicate\",\"".$this->id."\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data"=>array("label"=>"Pubblicate","icon"=>"mdi mdi-certificate","class"=>$class))
            );
        return $navbar;  
    }
    
    //Template navbar scadenzario
    public function TemplateNavbar_Scadenzario($level=1,$last=false,$refresh_view=true)
    {
        $class="n".$level;
        if($last) $class.=" AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(static::AA_UI_PREFIX."_Navbar_Link_Scadenzario_Content_Box",array(
                "type"=>"clean",
                "section_id"=>"Scadenzario",
                "module_id"=>$this->GetId(),
                "refresh_view"=>$refresh_view,
                "tooltip"=>"Fai click per visualizzare lo scadenzario delle nomine",
                "template"=>"<div class='AA_navbar_link_box_left #class#'><a class='AA_Sines_Navbar_Link_Scadenzario_Content_Box' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"Scadenzario\",\"".$this->id."\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data"=>array("label"=>"Scadenzario","icon"=>"mdi mdi-clipboard-clock","class"=>$class))
            );
        return $navbar;  
    }
    
    //Template navbar pubblicate
    public function TemplateNavbar_Back($level=1,$last=false,$refresh_view=false)
    {
        $class="n".$level;
        if($last) $class.=" AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(static::AA_UI_PREFIX."_Navbar_Link_Back_Content_Box",array(
                "type"=>"clean",
                "css"=>"AA_NavbarEventListener",
                "module_id"=>"AA_MODULE_SINES",
                "refresh_view"=>$refresh_view,
                "tooltip"=>"Fai click per tornare alla lista",
                "template"=>"<div class='AA_navbar_link_box_left #class#'><a class='AA_Sines_Navbar_Link_Back_Content_Box' onClick='AA_MainApp.utils.callHandler(\"goBack\",null,\"".$this->id."\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data"=>array("label"=>"Indietro","icon"=>"mdi mdi-keyboard-backspace","class"=>$class))
            );
        return $navbar;  
    }
    
    //Template navbar indietro
    public function TemplateNavbar_Revisionate($level=1,$last=false,$refresh_view=true)
    {
        $class="n".$level;
        if($last) $class.=" AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(static::AA_UI_PREFIX."_Navbar_Link_Revisionate_Content_Box",array(
                "type"=>"clean",
                "css"=>"AA_NavbarEventListener",
                "id_panel"=>static::AA_UI_PREFIX."_Revisionate_Content_Box",
                "module_id"=>"AA_MODULE_SINES",
                "refresh_view"=>$refresh_view,
                "tooltip"=>"Fai click per visualizzare le schede pubblicate revisionate",
                "template"=>"<div class='AA_navbar_link_box_left #class#'><a class='AA_Sines_Navbar_Link_Revisionate_Content_Box'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data"=>array("label"=>"Revisionate","icon"=>"mdi mdi-help-rhombus","class"=>$class))
            );
        return $navbar;  
    }
     
    //Task
    public function Task_GetActionMenu($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        
        $content="";
        
        switch($_REQUEST['section'])
        {
            case static::AA_UI_PREFIX."_".static::AA_UI_BOZZE_BOX:
                $content=$this->TemplateActionMenu_Bozze();
                break;
            
            case static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX:
                $content=$this->TemplateActionMenu_Pubblicate();
                break;
               
            case static::AA_UI_PREFIX."_Revisionate_Content_Box":
                $content=$this->TemplateActionMenu_Revisionate();
                break;
            case static::AA_UI_PREFIX."_Scadenzario_Content_Box":
                $content=$this->TemplateActionMenu_Scadenzario();
                break;
            case static::AA_UI_PREFIX."_".static::AA_UI_DETAIL_BOX:
                $content=$this->TemplateActionMenu_Detail();
                break;
            default:
                $content=new AA_JSON_Template_Generic();
                break;        
        }
        
        if($content !="") $sTaskLog.= $content->toBase64();
        
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task tipo bilanci
    public function Task_GetTipoBilanci($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $tipo_bilanci_collection=array();
        foreach(AA_Organismi_Const::GetTipoBilanci() as $id_tipo=>$value_tipo)
        {
            if($id_tipo>0) $tipo_bilanci_collection[]=array("id"=>$id_tipo,"value"=>$value_tipo);
        }
        
        $tipo_bilanci_collection="[";
        $sep="";
        foreach(AA_Organismi_Const::GetTipoBilanci() as $id_tipo=>$value_tipo)
        {
            if($id_tipo>0) 
            {
                $tipo_bilanci_collection.=$sep.'{"id": "'.$id_tipo.'", "value":"'.$value_tipo.'"}';
                $sep=",";
            }
        }
        $tipo_bilanci_collection.="]";
        
        $task->SetLog($tipo_bilanci_collection);
        
        return true;
    }
    
    //Task layout
    public function Task_GetLayout($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json'>";
        $content=$this->TemplateLayout();
        $sTaskLog.= $content;
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //GetLogDlg
    public function Task_GetLogDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $wnd = new AA_SinesLogDlg("AA_SinesLogDlg_".$_REQUEST['id'],"Logs",$this->oUser);
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>".$wnd->toBase64()."</content><error id='error'></error>";

        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task Update Organismo
    public function Task_UpdateOrganismo($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismo non valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        //Aggiorna i dati
        if(!$organismo->ParseData($_REQUEST))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel parsing dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        //Salva i dati
        if(!$organismo->UpdateDb($this->oUser))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salavataggio dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Dati aggiornati con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task trash Organismi
    public function Task_TrashOrganismi($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        //lista organismi da cestinare
        if($_REQUEST['ids'])
        {
            $ids= json_decode($_REQUEST['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Organismi($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_DELETE)>0)
                {
                    $ids_final[$curId]=$organismo;
                    unset($organismo);
                }
            }
            
            //Esiste almeno un organismo che può essere cestinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $count=0;
                foreach( $ids_final as $id=>$organismo)
                {
                    
                    if(!$organismo->Trash($this->oUser))
                    {
                        $count++;
                        $result_error["$organismo->GetDenominazione()"]=AA_Log::$lastErrorLog;
                    }
                }
                
                if(sizeof($result_error)>0)
                {
                    $wnd=new AA_GenericWindowTemplate("TrashOrganismi", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Sono stati cestinati ".(sizeof($ids)-sizeof($result_error))." organismi.<br>I seguenti non sono stati cestinati:")));
                
                    $tabledata=array();
                    foreach($result_error as $org=>$desc)
                    {
                        $tabledata[]=array("Denominazione"=>$org,"Errore"=>$desc);
                    }
                    $table=new AA_JSON_Template_Generic($id."_Table", array(
                        "view"=>"datatable",
                        "scrollX"=>false,
                        "autoConfig"=>true,
                        "select"=>false,
                        "data"=>$tabledata
                    ));
                    $wnd->AddView($table);
                    
                    $sTaskLog="<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog.=$wnd->toBase64();
                    $sTaskLog.="</error>";
                    $task->SetLog($sTaskLog);

                    return false;      
                }
                else
                {
                    $sTaskLog="<status id='status'>0</status><content id='content'>";
                    $sTaskLog.= "SOno stati cestinati ".sizeof($ids_final)." organismi.";
                    $sTaskLog.="</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            }
            else
            {
                $task->SetError("Nella selezione non sono presenti organismi cestinabili dall'utente corrente (".$this->oUser->GetName().").");
                $sTaskLog="<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti organismi cestinabili dall'utente corrente (".$this->oUser->GetName().").</error>";
                $task->SetLog($sTaskLog);

                return false;          
            }
        }
        else
        {
            $task->SetError("Non sono stati selezionati organismi.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Non sono stati selezionati organismi.</error>";
            $task->SetLog($sTaskLog);

            return false;          
        } 
    }
    
    //Task trash Organismi
    public function Task_PdfExport($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sessVar= AA_SessionVar::Get("SaveAsPdf_ids");
        
        //lista organismi da esportare
        if($sessVar->IsValid())
        {
            $ids = $sessVar->GetValue();
            $ids_final=array();
            
            if(is_array($ids))
            {
                foreach($ids as $curId)
                {
                    $organismo=new AA_Organismi($curId,$this->oUser);
                    if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_READ)>0)
                    {
                        $ids_final[$curId]=$organismo;
                        unset($organismo);
                    }
                }    
            }
            
            //Esiste almeno un organismo che può essere letto dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $this->Template_OrganismiPdfExport($ids);
            }
            else
            {
                $task->SetError("Nella selezione non sono presenti organismi leggibili dall'utente corrente (".$this->oUser->GetName().").");
                $sTaskLog="<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti organismi leggibili dall'utente corrente (".$this->oUser->GetName().").</error>";
                $task->SetLog($sTaskLog);

                return false;          
            }
        }
        else
        {
            $task->SetError("Non sono stati selezionati organismi.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Non sono stati selezionati organismi.</error>";
            $task->SetLog($sTaskLog);

            return false;          
        } 
    }
    
    //Task resume Organismi
    public function Task_ResumeOrganismi($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        //lista organismi da ripristinare
        if($_REQUEST['ids'])
        {
            $ids= json_decode($_REQUEST['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Organismi($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE)>0)
                {
                    $ids_final[$curId]=$organismo;
                    unset($organismo);
                }
            }
            
            //Esiste almeno un organismo che può essere ripristinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $count=0;
                foreach( $ids_final as $id=>$organismo)
                {
                    
                    if(!$organismo->Resume($this->oUser))
                    {
                        $count++;
                        $result_error["$organismo->GetDenominazione()"]=AA_Log::$lastErrorLog;
                    }
                }
                
                if(sizeof($result_error)>0)
                {
                    $wnd=new AA_GenericWindowTemplate("ResumeOrganismi", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Sono stati ripristinati ".(sizeof($ids)-sizeof($result_error))." organismi.<br>I seguenti non sono stati ripristinati:")));
                
                    $tabledata=array();
                    foreach($result_error as $org=>$desc)
                    {
                        $tabledata[]=array("Denominazione"=>$org,"Errore"=>$desc);
                    }
                    $table=new AA_JSON_Template_Generic($id."_Table", array(
                        "view"=>"datatable",
                        "scrollX"=>false,
                        "autoConfig"=>true,
                        "select"=>false,
                        "data"=>$tabledata
                    ));
                    $wnd->AddView($table);
                    
                    $sTaskLog="<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog.=$wnd->toBase64();
                    $sTaskLog.="</error>";
                    $task->SetLog($sTaskLog);

                    return false;      
                }
                else
                {
                    $sTaskLog="<status id='status'>0</status><content id='content'>";
                    $sTaskLog.= "Sono stati ripristinati ".sizeof($ids_final)." organismi.";
                    $sTaskLog.="</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            }
            else
            {
                $task->SetError("Nella selezione non sono presenti organismi ripristinabili dall'utente corrente (".$this->oUser->GetName().").");
                $sTaskLog="<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti organismi ripristinabili dall'utente corrente (".$this->oUser->GetName().").</error>";
                $task->SetLog($sTaskLog);

                return false;          
            }
        }
        else
        {
            $task->SetError("Non sono stati selezionati organismi.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Non sono stati selezionati organismi.</error>";
            $task->SetLog($sTaskLog);

            return false;          
        } 
    }
    
    //Task publish Organismi
    public function Task_PublishOrganismo($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        //lista organismi da pubblicare
        if($_REQUEST['ids'])
        {
            $ids= json_decode($_REQUEST['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Organismi($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_PUBLISH)>0)
                {
                    $ids_final[$curId]=$organismo;
                    unset($organismo);
                }
            }
            
            //Esiste almeno un organismo che può essere pubblicato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $count=0;
                foreach( $ids_final as $id=>$organismo)
                {
                    if(!$organismo->Publish($this->oUser))
                    {
                        $count++;
                        $result_error["$organismo->GetDenominazione()"]=AA_Log::$lastErrorLog;
                    }
                }
                
                if(sizeof($result_error)>0)
                {
                    $wnd=new AA_GenericWindowTemplate("PublishOrganismo", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Sono stati pubblicati ".(sizeof($ids)-sizeof($result_error))." organismi.<br>I seguenti non sono stati pubblicati:")));
                
                    $tabledata=array();
                    foreach($result_error as $org=>$desc)
                    {
                        $tabledata[]=array("Denominazione"=>$org,"Errore"=>$desc);
                    }
                    $table=new AA_JSON_Template_Generic($id."_Table", array(
                        "view"=>"datatable",
                        "scrollX"=>false,
                        "autoConfig"=>true,
                        "select"=>false,
                        "data"=>$tabledata
                    ));
                    $wnd->AddView($table);
                    
                    $sTaskLog="<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog.=$wnd->toBase64();
                    $sTaskLog.="</error>";
                    $task->SetLog($sTaskLog);

                    return false;      
                }
                else
                {
                    $sTaskLog="<status id='status'>0</status><content id='content'>";
                    $sTaskLog.= "Sono stati pubblicati ".sizeof($ids_final)." organismi.";
                    $sTaskLog.="</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            }
            else
            {
                $task->SetError("Nella selezione non sono presenti organismi pubblicabili dall'utente corrente (".$this->oUser->GetName().").");
                $sTaskLog="<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti organismi pubblicabili dall'utente corrente (".$this->oUser->GetName().").</error>";
                $task->SetLog($sTaskLog);

                return false;          
            }
        }
        else
        {
            $task->SetError("Non sono stati selezionati organismi.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Non sono stati selezionati organismi.</error>";
            $task->SetLog($sTaskLog);

            return false;          
        } 
    }
    
    //Task reassign Organismi
    public function Task_ReassignOrganismi($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        //lista organismi da riassegnare
        if($_REQUEST['ids'])
        {
            $ids= json_decode($_REQUEST['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Organismi($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE)>0)
                {
                    $ids_final[$curId]=$organismo;
                    unset($organismo);
                }
            }
            
            //Esiste almeno un organismo che può essere ripristinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $count=0;
                $params['riassegna-id-assessorato']=$_REQUEST['id_assessorato'];
                $params['riassegna-id-direzione']=$_REQUEST['id_direzione'];
                $params['riassegna-id-servizio']=$_REQUEST['id_servizio'];
                foreach( $ids_final as $id=>$organismo)
                {                    
                    if(!$organismo->Reassign($params,$this->oUser))
                    {
                        $count++;
                        $result_error["$organismo->GetDenominazione()"]=AA_Log::$lastErrorLog;
                    }
                }
                
                if(sizeof($result_error)>0)
                {
                    $wnd=new AA_GenericWindowTemplate("ReassignOrganismi", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Sono stati riassegnati ".(sizeof($ids)-sizeof($result_error))." organismi alla struttura: ".$_REQUEST['struct_desc'].".<br>I seguenti non sono stati riassegnati:")));
                
                    $tabledata=array();
                    foreach($result_error as $org=>$desc)
                    {
                        $tabledata[]=array("Denominazione"=>$org,"Errore"=>$desc);
                    }
                    $table=new AA_JSON_Template_Generic($id."_Table", array(
                        "view"=>"datatable",
                        "scrollX"=>false,
                        "autoConfig"=>true,
                        "select"=>false,
                        "data"=>$tabledata
                    ));
                    $wnd->AddView($table);
                    
                    $sTaskLog="<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog.=$wnd->toBase64();
                    $sTaskLog.="</error>";
                    $task->SetLog($sTaskLog);

                    return false;      
                }
                else
                {
                    $sTaskLog="<status id='status'>0</status><content id='content'>";
                    $sTaskLog.= "Sono stati riassegnati ".sizeof($ids_final)." organismi.";
                    $sTaskLog.="</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            }
            else
            {
                $task->SetError("Nella selezione non sono presenti organismi riassegnabili dall'utente corrente (".$this->oUser->GetName().").");
                $sTaskLog="<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti organismi riassegnabili dall'utente corrente (".$this->oUser->GetName().").</error>";
                $task->SetLog($sTaskLog);

                return false;          
            }
        }
        else
        {
            $task->SetError("Non sono stati selezionati organismi.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Non sono stati selezionati organismi.</error>";
            $task->SetLog($sTaskLog);

            return false;          
        } 
    }
    
    //Task delete Organismi
    public function Task_DeleteOrganismi($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        //lista organismi da eliminare
        if($_REQUEST['ids'])
        {
            $ids= json_decode($_REQUEST['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Organismi($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_DELETE)>0)
                {
                    $ids_final[$curId]=$organismo;
                    unset($organismo);
                }
            }
            
            //Esiste almeno un organismo che può essere cestinato dall'utente corrente
            if(sizeof($ids_final)>0)
            {
                $count=0;
                foreach( $ids_final as $id=>$organismo)
                {
                    
                    if(!$organismo->Trash($this->oUser,true))
                    {
                        $count++;
                        $result_error["$organismo->GetDenominazione()"]=AA_Log::$lastErrorLog;
                    }
                }
                
                if(sizeof($result_error)>0)
                {
                    $wnd=new AA_GenericWindowTemplate("DeleteOrganismi", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Sono stati eliminati ".(sizeof($ids)-sizeof($result_error))." organismi.<br>I seguenti non sono stati eliminati:")));
                
                    $tabledata=array();
                    foreach($result_error as $org=>$desc)
                    {
                        $tabledata[]=array("Denominazione"=>$org,"Errore"=>$desc);
                    }
                    $table=new AA_JSON_Template_Generic($id."_Table", array(
                        "view"=>"datatable",
                        "scrollX"=>false,
                        "autoConfig"=>true,
                        "select"=>false,
                        "data"=>$tabledata
                    ));
                    $wnd->AddView($table);
                    
                    $sTaskLog="<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog.=$wnd->toBase64();
                    $sTaskLog.="</error>";
                    $task->SetLog($sTaskLog);

                    return false;      
                }
                else
                {
                    $sTaskLog="<status id='status'>0</status><content id='content'>";
                    $sTaskLog.= "Sono stati eliminati ".sizeof($ids_final)." organismi.";
                    $sTaskLog.="</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            }
            else
            {
                $task->SetError("Nella selezione non sono presenti organismi eliminabili dall'utente corrente (".$this->oUser->GetName().").");
                $sTaskLog="<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti organismi eliminabili dall'utente corrente (".$this->oUser->GetName().").</error>";
                $task->SetLog($sTaskLog);

                return false;          
            }
        }
        else
        {
            $task->SetError("Non sono stati selezionati organismi.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Non sono stati selezionati organismi.</error>";
            $task->SetLog($sTaskLog);

            return false;          
        } 
    }
    
    //Task Aggiungi organismo
    public function Task_AddNewOrganismo($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $task->SetError("L'utente corrente non ha i permessi per aggiugere nuovi organismi");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per aggiugere nuovi organismi</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        $organismo= AA_Organismi::AddNewToDb($_REQUEST, $this->oUser);
        
        if(!($organismo instanceof AA_Organismi))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status' id_Rec='".$organismo->GetId()."' action='showDetailView' action_params='".json_encode(array("id"=>$organismo->GetId()))."'>0</status><content id='content'>";
        $sTaskLog.= "Organismo aggiunto con successo (identificativo: ".$organismo->GetId().")";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task Aggiungi dato contabile
    public function Task_AddNewOrganismoDatoContabile($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismo non valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        //Salva i dati
        $new_dato=AA_OrganismiDatiContabili::AddNewToDb($_REQUEST,$organismo,$this->oUser);
        
        if(!($new_dato instanceof AA_OrganismiDatiContabili))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status' id_Rec='".$new_dato->GetId()."'>0</status><content id='content'>";
        $sTaskLog.= "Dato contabile inserito con successo (identificativo: $new_dato->GetId()).";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task Aggiungi dato contabile
    public function Task_AddNewOrganismoNomina($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismo non valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        //Salva i dati
        $new_dato=AA_OrganismiNomine::AddNewToDb($_REQUEST,$organismo,$this->oUser);
        
        if(!($new_dato instanceof AA_OrganismiNomine))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status' id_Rec='".$new_dato->GetId()."'>0</status><content id='content'>";
        $sTaskLog.= "Nomina inserita con successo (identificativo: $new_dato->GetId()).";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task Aggiungi dato contabile
    public function Task_AddNewOrganismoIncarico($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismo non valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        //Salva i dati
        $new_dato=AA_OrganismiNomine::AddNewToDb($_REQUEST,$organismo,$this->oUser);
        
        if(!($new_dato instanceof AA_OrganismiNomine))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status' id_Rec='".$new_dato->GetId()."'>0</status><content id='content'>";
        $sTaskLog.= "Incarico inserito con successo (identificativo: $new_dato->GetId()).";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task Aggiungi dato contabile
    public function Task_AddNewOrganismoIncaricoDoc($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST['id_incarico'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$incarico->isValid())
        {
            $task->SetError("Identificativo organismo o incarico non validi. (".$_REQUEST['id'].",".$_REQUEST['id_incarico'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo o incarico non validi. (".$_REQUEST['id'].",".$_REQUEST['id_incarico'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $file = AA_SessionFileUpload::Get("NewIncaricoDoc");
        
        if(!$file->isValid() || $_REQUEST['anno'] == "" || $_REQUEST['tipo'] == "")
        {
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($file->GetValue(),true)." - ".print_r($_REQUEST,true),100);
            $task->SetError("Parametri non validi: ".print_r($file,true)." - ".print_r($_REQUEST,true));
            $sTaskLog="<status id='status'>-1</status><error id='error'>Parametri non validi: ".print_r($file->GetValue(),true)." - ".print_r($_REQUEST,true)."</error>";
            $task->SetLog($sTaskLog);
            
            return false;
        }
        
        $filespecs=$file->GetValue();
        
        if(!AA_OrganismiNomineDocument::UploadDoc($incarico, $_REQUEST['anno'], $_REQUEST['tipo'],$filespecs['tmp_name'], true, $this->oUser))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio del documento. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Documento caricato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task Aggiungi dato contabile
    public function Task_AddNewOrganismoProvvedimento($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        
        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismo non valido. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $file = AA_SessionFileUpload::Get("NewProvvedimentoDoc");
        
        if(!$file->isValid() && $_REQUEST['url'] == "")
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($file,true)." - ".print_r($_REQUEST,true),100);
            $task->SetError("Parametri non validi occorre indicare un url o un file.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Parametri non validi: occorre indicare un url o un file.</error>";
            $task->SetLog($sTaskLog);
            
            return false;
        }
        
        $provvedimento=new AA_OrganismiProvvedimenti(0,$organismo->GetID(),$_REQUEST['tipo'],$_REQUEST['url'],$_REQUEST['anno']);
        AA_Log::Log(__METHOD__." - "."Provvedimento: ".print_r($provvedimento, true),100);
        
        if($file->isValid()) $filespec=$file->GetValue();
        else $filespec=array();
        
        if(!$organismo->AddNewProvvedimento($provvedimento, $filespec['tmp_name'], $this->oUser))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio del provvedimento. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Provvedimento caricato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task Modifica provvedimento
    public function Task_UpdateOrganismoProvvedimento($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        
        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismo non valido. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $file = AA_SessionFileUpload::Get("NewProvvedimentoDoc");
        $provvedimento=new AA_OrganismiProvvedimenti($_REQUEST['id_provvedimento'],$organismo->GetID(),$_REQUEST['tipo'],$_REQUEST['url'],$_REQUEST['anno']);
        
        if(!$file->isValid() && $_REQUEST['url'] == "" && !is_file($provvedimento->GetFilePath()))
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($file,true)." - ".print_r($_REQUEST,true),100);
            $task->SetError("Parametri non validi occorre indicare un url o un file.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Parametri non validi: occorre indicare un url o un file.</error>";
            $task->SetLog($sTaskLog);
            
            return false;
        }
        
        if($file->isValid()) $filespec=$file->GetValue();
        else $filespec=array();
        
        if(!$organismo->UpdateProvvedimento($provvedimento, $filespec['tmp_name'], $this->oUser))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio del provvedimento. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Provvedimento aggiornato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task Elimina provvedimento
    public function Task_TrashOrganismoProvvedimento($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        
        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismo non valido. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        if(!$organismo->DeleteProvvedimento($_REQUEST['id_provvedimento'],$this->oUser))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nell'eliminazione del provvedimento. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Provvedimento rimosso.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task Aggiungi compenso
    public function Task_AddNewOrganismoIncaricoCompenso($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST['id_incarico'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$incarico->isValid())
        {
            $task->SetError("Identificativo organismo o incarico non validi. (".$_REQUEST['id'].",".$_REQUEST['id_incarico'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo o incarico non validi. (".$_REQUEST['id'].",".$_REQUEST['id_incarico'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        if($_REQUEST['anno'] == "")
        {
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($_REQUEST,true),100);
            $task->SetError("Parametro anno non valido.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Parametro anno non valido.</error>";
            $task->SetLog($sTaskLog);
            
            return false;
        }

        $compenso=new AA_OrganismiNomineCompensi(0,$_REQUEST['anno'],$_REQUEST['parte_fissa'],$_REQUEST['parte_variabile'],$_REQUEST['rimborsi'],$_REQUEST['note']);
                
        if(!$incarico->AddNewCompenso($compenso, $this->oUser))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio del compenso. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Compenso aggiunto con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task modifica dato contabile
    public function Task_UpdateOrganismoDatoContabile($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        $dato=new AA_OrganismiDatiContabili($_REQUEST["id_dato_contabile"],null,$this->oUser);
        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismonon valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(!$dato->isValid())
        {
            $task->SetError("Identificativo dato contabile non valido: ".$_REQUEST['id_dato_contabile']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo dato contabile non valido: ".$_REQUEST['id_dato_contabile']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        //Aggiorna i dati
        if(!$dato->ParseData($_REQUEST))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel parsing dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        //Salva i dati
        if(!$dato->UpdateDb($this->oUser))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salavataggio dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status' id_Rec='".$dato->GetId()."'>0</status><content id='content'>";
        $sTaskLog.= "Dato contabile aggiornato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task aggiorna incarico
    public function Task_UpdateOrganismoIncarico($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST["id_incarico"],null,$this->oUser);
        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismonon valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(!$incarico->isValid())
        {
            $task->SetError("Identificativo nomina non valido: ".$_REQUEST['id_incarico']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo nomina non valido: ".$_REQUEST['id_nomina']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        //Aggiorna i dati
        if(!$incarico->ParseData($_REQUEST))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel parsing dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        //Salva i dati
        if(!$incarico->UpdateDb($this->oUser))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status' id_Rec='".$incarico->GetId()."'>0</status><content id='content'>";
        $sTaskLog.= "Incarico aggiornato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task aggiorna nomine
    public function Task_RenameOrganismoNomina($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismonon valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if($_REQUEST['ids']=="" || trim($_REQUEST['sNome'])=="" || trim($_REQUEST['sCognome'])=="" || trim($_REQUEST['sCodiceFiscale']==""))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Dati nomina non validi (nome, cognome o codice fiscale assenti).</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        //Aggiorna i dati
        $db= new AA_Database();
        $query="UPDATE ".AA_Organismi_Const::AA_ORGANISMI_NOMINE_DB_TABLE." set nome='".addslashes(trim($_REQUEST['sNome']))."', cognome='".addslashes(trim($_REQUEST['sCognome']))."', codice_fiscale='".addslashes(trim($_REQUEST['sCodiceFiscale']))."' " ;
        $query.=" WHERE id in (".$_REQUEST['ids'].")";
        $query.=" AND id_organismo='".$_REQUEST['id']."'";
        
        AA_Log::Log(__METHOD__." - ".$query,100);
        
        if(!$db->Query($query))
        {
            $task->SetError($db->GetLastErrorMessage());
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nell'aggiornamento dei dati. (".$db->GetLastErrorMessage().")</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Nomine aggiornate con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task modifica bilancio
    public function Task_UpdateOrganismoBilancio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        $dato=new AA_OrganismiDatiContabili($_REQUEST["id_dato_contabile"],null,$this->oUser);

        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismonon valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(!$dato->isValid())
        {
            $task->SetError("Identificativo dato contabile non valido: ".$_REQUEST['id_dato_contabile']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo dato contabile non valido: ".$_REQUEST['id_dato_contabile']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
                
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $bilancio=new AA_OrganismiBilanci();
        $bilancio->SetIdDatiContabili($dato->GetId());
        $bilancio->SetTipo($_REQUEST['nTipologia']);
        $bilancio->SetRisultati($_REQUEST['sRisultati']);
        $bilancio->SetNote($_REQUEST['sNote']);
        $bilancio->SetId($_REQUEST['id_bilancio']);

        //Aggiorna i dati
        if(!$dato->UpdateBilancio($bilancio))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $sTaskLog="<status id='status' id_Rec='".$dato->GetId()."'>0</status><content id='content'>";
        $sTaskLog.= "Bilancio aggiornato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task trash bilancio
    public function Task_TrashOrganismoBilancio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        $dato=new AA_OrganismiDatiContabili($_REQUEST["id_dato_contabile"],null,$this->oUser);

        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismonon valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(!$dato->isValid())
        {
            $task->SetError("Identificativo dato contabile non valido: ".$_REQUEST['id_dato_contabile']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo dato contabile non valido: ".$_REQUEST['id_dato_contabile']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
                
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        if($_REQUEST['id_bilancio']=="")
        {
            $task->SetError("identificativo bilancio non impostato");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo bilancio non impostato.</error>";
            $task->SetLog($sTaskLog);
            return false;
        }
       
        //Aggiorna i dati
        if(!$dato->DelBilancio($_REQUEST['id_bilancio'],$this->oUser))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $sTaskLog="<status id='status' id_Rec='".$dato->GetId()."'>0</status><content id='content'>";
        $sTaskLog.= "Bilancio eliminato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task trash dato contabile
    public function Task_TrashOrganismoDatoContabile($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        $dato=new AA_OrganismiDatiContabili($_REQUEST["id_dato_contabile"],null,$this->oUser);

        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismonon valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(!$dato->isValid())
        {
            $task->SetError("Identificativo dato contabile non valido: ".$_REQUEST['id_dato_contabile']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo dato contabile non valido: ".$_REQUEST['id_dato_contabile']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
                
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
       
        //Elimina i dati
        if(!$dato->Trash($this->oUser))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $sTaskLog="<status id='status' id_Rec='".$dato->GetId()."'>0</status><content id='content'>";
        $sTaskLog.= "Dato contabile eliminato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task trash incarico
    public function Task_TrashOrganismoIncarico($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        $dato=new AA_OrganismiNomine($_REQUEST["id_incarico"],null,$this->oUser);

        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismo non valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(!$dato->isValid())
        {
            $task->SetError("Identificativo incarico non valido: ".$_REQUEST['id_incarico']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo incarico non valido: ".$_REQUEST['id_incarico']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
                
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
       
        //Elimina i dati
        if(!$dato->Trash($this->oUser))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $sTaskLog="<status id='status' id_Rec='".$dato->GetId()."'>0</status><content id='content'>";
        $sTaskLog.= "Incarico eliminato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task aggiungi bilancio
    public function Task_AddNewOrganismoBilancio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo=new AA_Organismi($_REQUEST['id'], $this->oUser);
        $dato=new AA_OrganismiDatiContabili($_REQUEST["id_dato_contabile"],null,$this->oUser);
        if(!$organismo->isValid())
        {
            $task->SetError("Identificativo organismonon valido: ".$_REQUEST['id']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo organismo non valido: ".$_REQUEST['id']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(!$dato->isValid())
        {
            $task->SetError("Identificativo dato contabile non valido: ".$_REQUEST['id_dato_contabile']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo dato contabile non valido: ".$_REQUEST['id_dato_contabile']."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'organismo: ".$organismo->GetDenominazione()."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $bilancio=new AA_OrganismiBilanci();
        $bilancio->SetIdDatiContabili($dato->GetId());
        $bilancio->SetTipo($_REQUEST['nTipologia']);
        $bilancio->SetRisultati($_REQUEST['sRisultati']);
        $bilancio->SetNote($_REQUEST['sNote']);
        
        //Aggiorna i dati
        if(!$dato->AddNewBilancio($bilancio))
        {
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $sTaskLog="<status id='status' id_Rec='".$dato->GetId()."'>0</status><content id='content'>";
        $sTaskLog.= "Bilancio aggiunto con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
        
    //Task sections
    public function Task_GetSections($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $sTaskLog.= $this->GetSections("base64");
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task modifica organismo
    public function Task_GetOrganismoModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        if(!$organismo->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoModifyDlg($organismo)->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task resume organismo
    public function Task_GetOrganismoResumeDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per ripristinare organismi.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoResumeDlg($_REQUEST)->toBase64();
            $sTaskLog.="</content>";
            $task->SetLog($sTaskLog);
        
            return true;
        }    
        else
        {
            // to do lista da recuperare con filtro
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativi non presenti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        return true;
    }
    
    //Task publish organismo
    public function Task_GetOrganismoPublishDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per pubblicare organismi.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoPublishDlg($_REQUEST)->toBase64();
            $sTaskLog.="</content>";
            $task->SetLog($sTaskLog);
        
            return true;
        }    
        else
        {
            // to do lista da recuperare con filtro
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativi non presenti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        return true;
    }
    
    //Task resume organismo
    public function Task_GetOrganismoReassignDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per riassegnare organismi.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoReassignDlg($_REQUEST)->toBase64();
            $sTaskLog.="</content>";
            
            $task->SetLog($sTaskLog);
        
            return true;
        }    
        else
        {
            // to do lista da recuperare con filtro
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativi non presenti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        return true;
    }
    
    //Task elimina organismo
    public function Task_GetOrganismoTrashDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per cestinare/eliminare organismi.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoTrashDlg($_REQUEST)->toBase64();
            $sTaskLog.="</content>";
            
            $task->SetLog($sTaskLog);
        
            return true;
        }    
        else
        {
            // to do lista da recuperare con filtro
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativi non presenti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        return true;
    }
    
    //Task elimina bilancio dlg
    public function Task_GetOrganismoTrashBilancioDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $dato_contabile=new AA_OrganismiDatiContabili($_REQUEST['id_dato_contabile'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$dato_contabile->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o dato contabile non valido o permessi insufficienti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        $bilancio=$dato_contabile->GetBilancio($_REQUEST['id_bilancio'],$this->oUser);
        if(!($bilancio instanceof AA_OrganismiBilanci))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativo di bilancio non valido o permessi insufficienti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoTrashBilancioDlg($organismo, $dato_contabile,$bilancio)->toBase64();
            $sTaskLog.="</content>";
            $task->SetLog($sTaskLog);
        
            return true;
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        return true;
    }
    
    //Task elimina incarico dlg
    public function Task_GetOrganismoTrashIncaricoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST['id_incarico'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$incarico->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o incarico non valido o permessi insufficienti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
                
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoTrashIncaricoDlg($organismo, $incarico)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task elimina incarico doc dlg
    public function Task_GetOrganismoTrashIncaricoDocDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST['id_incarico'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$incarico->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o incarico non valido o permessi insufficienti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        $doc = AA_OrganismiNomineDocument::GetDoc($incarico, $_REQUEST['anno'], $_REQUEST['tipo'], $this->oUser);
        if(!$doc->IsValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Parametri documento non validi.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoTrashIncaricoDocDlg($organismo, $incarico,$doc)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task elimina incarico compenso dlg
    public function Task_GetOrganismoTrashIncaricoCompensoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST['id_incarico'],$organismo,$this->oUser);
       
        if(!$organismo->isValid() || !$incarico->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o incarico non valido o permessi insufficienti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        $compenso = $incarico->GetCompenso($_REQUEST['id_compenso'],"",$this->oUser);
        if(!($compenso instanceof AA_OrganismiNomineCompensi))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Parametri compenso non validi.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoTrashIncaricoCompensoDlg($organismo, $incarico,$compenso)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task modifica incarico compenso dlg
    public function Task_GetOrganismoModifyIncaricoCompensoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST['id_incarico'],$organismo,$this->oUser);
       
        if(!$organismo->isValid() || !$incarico->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o incarico non valido o permessi insufficienti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        $compenso = $incarico->GetCompenso($_REQUEST['id_compenso'],"",$this->oUser);
        if(!($compenso instanceof AA_OrganismiNomineCompensi))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Parametri compenso non validi.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoModifyIncaricoCompensoDlg($organismo, $incarico,$compenso)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task elimina incarico doc
    public function Task_TrashOrganismoIncaricoDoc($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST['id_incarico'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$incarico->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o incarico non valido o permessi insufficienti.</error>";
            
            $task->SetLog($sTaskLog);
            
            return false;
        }

        $doc = AA_OrganismiNomineDocument::GetDoc($incarico,$_REQUEST['anno'], $_REQUEST['tipo'],$this->oUser);
        
        if(!$doc->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo documento non corretto.</error>";
            
            $task->SetLog($sTaskLog);
             
            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            if($incarico->DelDoc($_REQUEST['anno'],$_REQUEST['tipo'],$this->oUser))
            {
                $sTaskLog="<status id='status'>0</status><content id='content'>";
                $sTaskLog.="Documento eliminato.";
                $sTaskLog.="</content>";
                
                $task->SetLog($sTaskLog);
                 
                return true;
            }
            else
            {
                $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
                $sTaskLog.= "{}";
                $sTaskLog.="</content><error id='error'>Errore durate l'eliminazione del documento (".AA_Log::$lastErrorLog.").</error>";

                $task->SetLog($sTaskLog);

                return false;
            }
            
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        return true;
    }
    
    //Task elimina incarico compenso
    public function Task_TrashOrganismoIncaricoCompenso($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST['id_incarico'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$incarico->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o incarico non valido o permessi insufficienti.</error>";
            
            $task->SetLog($sTaskLog);
            
            return false;
        }

        $compenso = $incarico->GetCompenso($_REQUEST['id_compenso'],"",$this->oUser);
        
        if(!($compenso instanceof AA_OrganismiNomineCompensi))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo compenso non corretto.</error>";
            
            $task->SetLog($sTaskLog);
             
            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            if($incarico->TrashCompenso($_REQUEST['id_compenso'],$this->oUser))
            {
                $sTaskLog="<status id='status'>0</status><content id='content'>";
                $sTaskLog.="Compenso eliminato.";
                $sTaskLog.="</content>";
                
                $task->SetLog($sTaskLog);
                 
                return true;
            }
            else
            {
                $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
                $sTaskLog.= "{}";
                $sTaskLog.="</content><error id='error'>Errore durate l'eliminazione del documento (".AA_Log::$lastErrorLog.").</error>";

                $task->SetLog($sTaskLog);

                return false;
            }
            
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        return true;
    }
    
    //Task aggiorna incarico compenso
    public function Task_UpdateOrganismoIncaricoCompenso($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST['id_incarico'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$incarico->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o incarico non valido o permessi insufficienti.</error>";
            
            $task->SetLog($sTaskLog);
            
            return false;
        }

        $compenso = $incarico->GetCompenso($_REQUEST['id_compenso'],"",$this->oUser);
        
        if(!($compenso instanceof AA_OrganismiNomineCompensi))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo compenso non corretto.</error>";
            
            $task->SetLog($sTaskLog);
             
            return false;
        }
        
        $compenso= new AA_OrganismiNomineCompensi($_REQUEST['id_compenso'],$_REQUEST['anno'],$_REQUEST['parte_fissa'],$_REQUEST['parte_variabile'],$_REQUEST['rimborsi'],$_REQUEST['note']);
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            if($incarico->UpdateCompenso($compenso,$this->oUser))
            {
                $sTaskLog="<status id='status'>0</status><content id='content'>";
                $sTaskLog.="Compenso aggiornato.";
                $sTaskLog.="</content>";
                
                $task->SetLog($sTaskLog);
                 
                return true;
            }
            else
            {
                $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
                $sTaskLog.= "{}";
                $sTaskLog.="</content><error id='error'>Errore durate l'eliminazione del documento (".AA_Log::$lastErrorLog.").</error>";

                $task->SetLog($sTaskLog);

                return false;
            }
            
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        return true;
    }
    
    //Task aggiungi doc incarico dlg
    public function Task_GetOrganismoAddNewIncaricoDocDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST['id_incarico'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$incarico->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o incarico non valido o permessi insufficienti.</error>";
        }
                
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoAddNewIncaricoDocDlg($organismo, $incarico)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task aggiungi compenso incarico dlg
    public function Task_GetOrganismoAddNewIncaricoCompensoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST['id_incarico'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$incarico->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o incarico non valido o permessi insufficienti.</error>";
        }
                
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoAddNewIncaricoCompensoDlg($organismo, $incarico)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task elimina bilancio dlg
    public function Task_GetOrganismoTrashDatoContabileDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $dato_contabile=new AA_OrganismiDatiContabili($_REQUEST['id_dato_contabile'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$dato_contabile->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o dato contabile non valido o permessi insufficienti.</error>";
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoTrashDatoContabileDlg($organismo, $dato_contabile)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task elimina organismo
    public function Task_GetOrganismoDeleteDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per cestinare/eliminare organismi.</error>";
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoDeleteDlg($_REQUEST)->toBase64();
            $sTaskLog.="</content>";
        }    
        else
        {
            // to do lista da recuperare con filtro
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativi non presenti.</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task aggiunta organismo
    public function Task_GetOrganismoAddNewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per istanziare nuovi organismi.</error>";
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoAddNewDlg()->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task modifica dato contabile
    public function Task_GetOrganismoModifyDatoContabileDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $dato_contabile=new AA_OrganismiDatiContabili($_REQUEST['id_dato_contabile'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$dato_contabile->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o dato contabile non valido o permessi insufficienti.</error>";
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoModifyDatoContabileDlg($organismo, $dato_contabile)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task aggiungi dato contabile
    public function Task_GetOrganismoAddNewDatoContabileDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        
        if(!$organismo->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo non valido o permessi insufficienti.</error>";
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoAddNewDatoContabileDlg($organismo)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task aggiungi dato contabile
    public function Task_GetOrganismoAddNewProvvedimentoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        
        if(!$organismo->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoAddNewProvvedimentoDlg($organismo)->toBase64();
            $sTaskLog.="</content>";
            $task->SetLog($sTaskLog);
        
            return true;
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
    }
    
    //Task modifica Provvedimento
    public function Task_GetOrganismoModifyProvvedimentoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        
        if(!$organismo->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
            return false;
        }
        
        $provvedimento=$organismo->GetProvvedimento($_REQUEST['id_provvedimento'], $this->oUser);
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0 && $provvedimento instanceof AA_OrganismiProvvedimenti)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoModifyProvvedimentoDlg($organismo,$provvedimento)->toBase64();
            $sTaskLog.="</content>";

            $task->SetLog($sTaskLog);
            
            return true;
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
            $task->SetLog($sTaskLog);
            
            return false;
        }
    }
    
    //Task elimina Provvedimento
    public function Task_GetOrganismoTrashProvvedimentoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        
        if(!$organismo->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
            return false;
        }
        
        $provvedimento=$organismo->GetProvvedimento($_REQUEST['id_provvedimento'], $this->oUser);
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0 && $provvedimento instanceof AA_OrganismiProvvedimenti)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoTrashProvvedimentoDlg($organismo,$provvedimento)->toBase64();
            $sTaskLog.="</content>";

            $task->SetLog($sTaskLog);
            
            return true;
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
            $task->SetLog($sTaskLog);
            
            return false;
        }
    }
    
    //Task modifica dato contabile
    public function Task_GetOrganismoModifyIncaricoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $incarico=new AA_OrganismiNomine($_REQUEST['id_incarico'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$incarico->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o dati nomina non validi o permessi insufficienti.</error>";
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoModifyIncaricoDlg($organismo, $incarico)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task modifica bilancio dlg
    public function Task_GetOrganismoModifyBilancioDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $dato_contabile=new AA_OrganismiDatiContabili($_REQUEST['id_dato_contabile'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$dato_contabile->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o dato contabile non valido o permessi insufficienti.</error>";
        }
        
        $bilancio=$dato_contabile->GetBilancio($_REQUEST['id_bilancio'],$this->oUser);
        if(!($bilancio instanceof AA_OrganismiBilanci))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativo di bilancio non valido o permessi insufficienti.</error>";
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoModifyBilancioDlg($organismo, $dato_contabile,$bilancio)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task aggiungi bilancio
    public function Task_GetOrganismoAddNewBilancioDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        $dato_contabile=new AA_OrganismiDatiContabili($_REQUEST['id_dato_contabile'],$organismo,$this->oUser);
        
        if(!$organismo->isValid() || !$dato_contabile->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo o dato contabile non valido o permessi insufficienti.</error>";
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoAddNewBilancioDlg($organismo, $dato_contabile)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task aggiungi dato contabile
    public function Task_GetOrganismoAddNewIncaricoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        
        if(!$organismo->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo non valido o permessi insufficienti.</error>";
        }
        
        if(trim($_REQUEST['nome'])=="" || $_REQUEST['cognome']=="" || $_REQUEST['cf']=="")
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Dati nomina non validi (nome, cognome o codice fiscale assenti).</error>";
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $params['nome']=trim($_REQUEST['nome']);
            $params['cognome']=trim($_REQUEST['cognome']);
            $params['cf']=trim($_REQUEST['cf']);
            
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoAddNewIncaricoDlg($organismo,$params)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
       
    //Task aggiungi dato contabile
    public function Task_GetOrganismoAddNewNominaDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        
        if(!$organismo->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo non valido o permessi insufficienti.</error>";
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {   
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoAddNewNominaDlg($organismo)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task aggiungi dato contabile
    public function Task_GetOrganismoRenameNominaDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        
        if(!$organismo->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo non valido o permessi insufficienti.</error>";
        }
        
        if($_REQUEST['ids'] =="" || trim($_REQUEST['nome'])=="" || trim($_REQUEST['cognome'])=="" || trim($_REQUEST['cf'])=="")
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Dati nomina non validi (nome, cognome o codice fiscale assenti).</error>";
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $params['nome']=trim($_REQUEST['nome']);
            $params['cognome']=trim($_REQUEST['cognome']);
            $params['cf']=trim($_REQUEST['cf']);
            $params['ids']=$_REQUEST['ids'];
            
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoRenameNominaDlg($organismo,$params)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task elimina nomina dlg
    public function Task_GetOrganismoTrashNominaDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        
        if(!$organismo->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo non valido o permessi insufficienti.</error>";
        }
        
        if($_REQUEST['ids'] =="")
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativi nomine non validi o assensti.</error>";
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {                    
            $ids = json_decode($_REQUEST['ids']);
            $incarichi=array();
            foreach($ids as $curId)
            {
                $incarico=new AA_OrganismiNomine($curId,$organismo,$this->oUser);
                if($incarico->IsValid()) $incarichi[]=$incarico;
            }
            
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetOrganismoTrashNominaDlg($organismo,$incarichi)->toBase64();
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task elimina nomina
    public function Task_TrashOrganismoNomina($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $organismo= new AA_Organismi($_REQUEST['id'],$this->oUser);
        
        if(!$organismo->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Organismo non valido o permessi insufficienti.</error>";
            
             $task->SetLog($sTaskLog);
             return false;
        }
        
        if($_REQUEST['ids'] =="")
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativi nomine non validi o assensti.</error>";
            
             $task->SetLog($sTaskLog);
             return false;
        }
        
        if(($organismo->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {                    
            $ids = json_decode($_REQUEST['ids']);
            $incarichi=array();
            foreach($ids as $curId)
            {
                $incarico=new AA_OrganismiNomine($curId,$organismo,$this->oUser);
                if($incarico->IsValid()) $incarichi[]=$incarico;
            }
            
            foreach($incarichi as $incarico)
            {
                if(!$incarico->Trash($this->oUser))
                {
                    $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
                    $sTaskLog.= "{}";
                    $sTaskLog.="</content><error id='error'>Errore durante l'eliminazione degli incarichi.</error>";
                    
                    $task->SetLog($sTaskLog);
                    return false;
                }
            }
            
            $sTaskLog="<status id='status'>0</status><content id='content'>";
            $sTaskLog.= "Nomina eliminata con successo.";
            $sTaskLog.="</content>";
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'organismo (".$organismo->GetDenominazione().").</error>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    
    //Task filter dlg
    public function Task_GetPubblicateFilterDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->TemplatePubblicateFilterDlg();
        $sTaskLog.= base64_encode($content);
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task filter dlg
    public function Task_GetBozzeFilterDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->TemplateBozzeFilterDlg();
        $sTaskLog.= base64_encode($content);
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task filter dlg
    public function Task_GetScadenzarioFilterDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->TemplateScadenzarioFilterDlg();
        $sTaskLog.= base64_encode($content);
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task filter nomine
    public function Task_GetOrganismoNomineFilterDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->Template_GetOrganismoNomineFilterDlg(null,$_REQUEST['filter_id']);
        $sTaskLog.= base64_encode($content);
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task filter dlg
    public function Task_GetObjectData($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        
        $objectData=array(array());
        
        switch($_REQUEST['object'])
        {
            case static::AA_UI_PREFIX."_Pubblicate_List_Box":
                $_REQUEST['count']=10;
                $data=$this->GetDataSectionPubblicate_List($_REQUEST);
                if($data[0]>0) $objectData = $data[1];
                break;
                
            case static::AA_UI_PREFIX."_Scadenzario_List_Box":
                $_REQUEST['count']=10;
                $data=$this->GetDataSectionScadenzario_List($_REQUEST);
                if($data[0]>0) $objectData = $data[1];
                break;
                
            default:
                $objectData=array();
        }
        
        $sTaskLog.= base64_encode(json_encode($objectData));
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task NavBarContent
    public function Task_GetNavbarContent($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        
        $content=array();
        
        //Istanza del modulo
        $module= AA_SinesModule::GetInstance();
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22))
        {
            $_REQUEST['section']=static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX;
        }
        
        switch($_REQUEST['section'])
        {
            case static::AA_UI_PREFIX."_".static::AA_UI_BOZZE_BOX:
                $content[]=$module->TemplateNavbar_Pubblicate(1,true)->toArray();
                //$content[]=$module->TemplateNavbar_Revisionate(2,true)->toArray();
                break;
            case static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX:
                //$content[]=$module->TemplateNavbar_Revisionate()->toArray();
                $content[]=$module->TemplateNavbar_Bozze(1,false)->toArray();
                $content[]=$module->TemplateNavbar_Scadenzario(2,true)->toArray();
                break;
            case static::AA_UI_PREFIX."_".static::AA_UI_DETAIL_BOX:
                $content[]=$module->TemplateNavbar_Back(1,true)->toArray();
                break;
            default:
                $content[]=$module->TemplateNavbar_Pubblicate(1,false)->toArray();
                $content[]=$module->TemplateNavbar_Scadenzario(2,true)->toArray();
        }      
        
        $spacer=new AA_JSON_Template_Generic("navbar_spacer");
        $content[]= $spacer->toArray();
        
        $sTaskLog.=base64_encode(json_encode($content))."</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //TAsk section layout
    public function Task_GetSectionContent($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        
        switch($_REQUEST['section'])
        {
            case "Bozze":
            case static::AA_UI_PREFIX."_".static::AA_UI_BOZZE_BOX:
                $template=$this->TemplateSection_Bozze();
                $content=array("id"=>static::AA_UI_PREFIX."_".static::AA_UI_BOZZE_BOX,"content"=>$template->toArray());
                break;
            
            case "Pubblicate":
            case static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX:
                $template = $this->TemplateSection_Pubblicate();
                $content=array("id"=>static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX,"content"=>$template->toArray());
                break;
            
            case "Dettaglio":
            case static::AA_UI_PREFIX."_".static::AA_UI_DETAIL_BOX:
               $content=array("id"=>static::AA_UI_PREFIX."_".static::AA_UI_DETAIL_BOX,"content"=>$this->TemplateSection_Detail($_REQUEST)->toArray());
                break;
            
            default:
                 $content=array(array("id"=>static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX,"content"=>$this->TemplateSection_Placeholder()->toArray()));
        }
        
        //Codifica il contenuto in base64
        $sTaskLog.= base64_encode(json_encode($content))."</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
     //TAsk section layout
    public function Task_GetObjectContent($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        
        switch($_REQUEST['object'])
        {
            case "Bozze":
            case static::AA_UI_PREFIX."_".static::AA_UI_BOZZE_BOX:
                $template=$this->TemplateSection_Bozze();
                $content=array("id"=>static::AA_UI_PREFIX."_".static::AA_UI_BOZZE_BOX,"content"=>$template->toArray());
                break;
            
            case "Pubblicate":
            case static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX:
                $template = $this->TemplateSection_Pubblicate();
                $content=array("id"=>static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX,"content"=>$template->toArray());
                break;
            
            case "Dettaglio":
            case static::AA_UI_PREFIX."_".static::AA_UI_DETAIL_BOX:
               $template=$this->TemplateSection_Detail($_REQUEST);
               $content=array("id"=>static::AA_UI_PREFIX."_".static::AA_UI_DETAIL_BOX,"content"=>$template->toArray());
                break;
            
            case "Scadenzario":
            case static::AA_UI_PREFIX."_Scadenzario_Content_Box":
                $template = $this->TemplateSection_Scadenzario();
                $content=array("id"=>static::AA_UI_PREFIX."_Scadenzario_Content_Box","content"=>$template->toArray());
                break;
            
            default:
                 $content=array(
                    array("id"=>static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX,"content"=>$this->TemplateSection_Placeholder()->toArray()),
                    array("id"=>static::AA_UI_PREFIX."_".static::AA_UI_DETAIL_BOX,"content"=>$this->TemplateSection_Placeholder()->toArray()));
        }
        
        //Codifica il contenuto in base64
        $sTaskLog.= base64_encode(json_encode($content))."</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Template filtro di ricerca
    public function TemplatePubblicateFilterDlg()
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$_REQUEST['id_assessorato'],"id_direzione"=>$_REQUEST['id_direzione'],"struct_desc"=>$_REQUEST['struct_desc'],"id_struct_tree_select"=>$_REQUEST['id_struct_tree_select'],"tipo"=>$_REQUEST['tipo'],"denominazione"=>$_REQUEST['denominazione'],"cestinate"=>$_REQUEST['cestinate'], "incaricato"=>$_REQUEST['incaricato']);
        
        //Valori default
        if($_REQUEST['tipo']=="") $formData['tipo']="0";
        if($_REQUEST['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($_REQUEST['id_assessorato']=="") $formData['id_assessorato']=0;
        if($_REQUEST['id_direzione']=="") $formData['id_direzione']=0;
        if($_REQUEST['id_servizio']=="") $formData['id_servizio']=0;
        if($_REQUEST['cestinate']=="") $formData['cestinate']=0;
        
        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","tipo"=>0,"denominazione"=>"","cestinate"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Pubblicate_Filter", "Parametri di ricerca per le schede pubblicate",$this->GetId(),$formData,$resetData,$applyActions);
       
        $dlg->SetHeight(580);
        
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
        
        //Denominazione
        $dlg->AddTextField("denominazione","Denominazione/P.IVA",array("bottomLabel"=>"*Filtra in base alla denominazione o alla partita iva dell'organismo.", "placeholder"=>"Denominazione o piva..."));
        
        //Struttura
        $dlg->AddStructField(array("showAll"=>1,"hideServices"=>1,"targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //Tipologia
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Organismi_Const::GetTipoOrganismi() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $dlg->AddSelectField("tipo","Tipologia",array("bottomLabel"=>"*Filtra in base alla tipologia dell'organismo.","options"=>$options,"value"=>"0"));
        
        //Nominato
        $dlg->AddTextField("incaricato","Nominato",array("bottomLabel"=>"*Filtra in base al nome, cognome o cf del nominato.", "placeholder"=>"nome, cognome o cf del nominato..."));
        
        return $dlg->GetObject();
    }
    
    //Template filtro di ricerca
    public function TemplateBozzeFilterDlg()
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$_REQUEST['id_assessorato'],"id_direzione"=>$_REQUEST['id_direzione'],"struct_desc"=>$_REQUEST['struct_desc'],"id_struct_tree_select"=>$_REQUEST['id_struct_tree_select'],"tipo"=>$_REQUEST['tipo'],"denominazione"=>$_REQUEST['denominazione'],"cestinate"=>$_REQUEST['cestinate'],"incaricato"=>$_REQUEST['incaricato']);
        
        //Valori default
        if($_REQUEST['tipo']=="") $formData['tipo']="0";
        if($_REQUEST['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($_REQUEST['id_assessorato']=="") $formData['id_assessorato']=0;
        if($_REQUEST['id_direzione']=="") $formData['id_direzione']=0;
        if($_REQUEST['id_servizio']=="") $formData['id_servizio']=0;
        if($_REQUEST['cestinate']=="") $formData['cestinate']=0;
        
        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","tipo"=>0,"denominazione"=>"","cestinate"=>0,"incaricato"=>"");
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Bozze_Filter", "Parametri di ricerca per le bozze pubblicate",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
        
        //Denominazione
        $dlg->AddTextField("denominazione","Denominazione/P.IVA",array("bottomLabel"=>"*Filtra in base alla denominazione o alla partita iva dell'organismo.", "placeholder"=>"Denominazione o piva..."));
        
        //Struttura
        $dlg->AddStructField(array("hideServices"=>1,"targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //Tipologia
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Organismi_Const::GetTipoOrganismi() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $dlg->AddSelectField("tipo","Tipologia",array("bottomLabel"=>"*Filtra in base alla tipologia dell'organismo.","options"=>$options,"value"=>"0"));
        
        //Nominato
        $dlg->AddTextField("incaricato","Nominato",array("bottomLabel"=>"*Filtra in base al nome, cognome o cf del nominato.", "placeholder"=>"nome, cognome o cf del nominato..."));
        
        return $dlg->GetObject();
    }
    
    //Template filtro di ricerca
    public function TemplateScadenzarioFilterDlg()
    {
        //Valori runtime
        $formData=array("raggruppamento"=>$_REQUEST['raggruppamento'], "finestra_temporale"=>$_REQUEST['finestra_temporale'], "data_scadenzario"=>$_REQUEST['data_scadenzario'],"scadute"=>$_REQUEST['scadute'],"recenti"=>$_REQUEST['recenti'],"in_scadenza"=>$_REQUEST['in_scadenza'],"in_corso"=>$_REQUEST['in_corso'],"id_assessorato"=>$_REQUEST['id_assessorato'],"id_direzione"=>$_REQUEST['id_direzione'],"struct_desc"=>$_REQUEST['struct_desc'],"id_struct_tree_select"=>$_REQUEST['id_struct_tree_select'],"tipo"=>$_REQUEST['tipo'],"denominazione"=>$_REQUEST['denominazione'],"incaricato"=>$_REQUEST['incaricato']);
        
        //Valori default
        if($_REQUEST['tipo']=="") $formData['tipo']="0";
        if($_REQUEST['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($_REQUEST['id_assessorato']=="") $formData['id_assessorato']="0";
        if($_REQUEST['id_direzione']=="") $formData['id_direzione']="0";
        if($_REQUEST['id_servizio']=="") $formData['id_servizio']="0";
        if($_REQUEST['in_corso']=="") $formData['in_corso']="0";
        if($_REQUEST['in_scadenza']=="") $formData['in_scadenza']="1";
        if($_REQUEST['recenti']=="") $formData['recenti']="1";
        if($_REQUEST['scadute']=="") $formData['scadute']="0";
        if($_REQUEST['data_scadenzario']=="") $formData['data_scadenzario'] = Date("Y-m-d");
        if($_REQUEST['finestra_temporale']=="") $formData['finestra_temporale'] = "1";
        if($_REQUEST['raggruppamento']=="") $formData['raggruppamento'] = "0";
        
        //Valori reset
        $resetData=array("raggruppamento"=>"0","finestra_temporale"=>"1","in_corso"=>"0","in_scadenza"=>"1","recenti"=>"1","scadute"=>"0","data_scadenzario"=>Date("Y-m-d"),"id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","tipo"=>0,"denominazione"=>"","incaricato"=>"");
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Scadenzario_Filter", "Parametri di ricerca per lo scadenzario nomine",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(780);
        $dlg->SetWidth(1080);
        $dlg->SetLabelAlign("right");
                        
        //Denominazione
        $dlg->AddTextField("denominazione","Denominazione/P.IVA",array("bottomLabel"=>"*Filtra in base alla denominazione o alla partita iva dell'organismo.", "placeholder"=>"Denominazione o piva..."));
        
        //In corso
        $dlg->AddSwitchBoxField("in_corso","In corso",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le nomine in corso."),false);
        
        //Struttura
        $dlg->AddStructField(array("hideServices"=>1,"targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));

        //In scadenza
        $dlg->AddSwitchBoxField("in_scadenza","In scadenza",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le nomine che scadono entro l'arco temporale impostato."),false);
        
        //Tipologia
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Organismi_Const::GetTipoOrganismi() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $dlg->AddSelectField("tipo","Tipologia",array("bottomLabel"=>"*Filtra in base alla tipologia dell'organismo.","options"=>$options,"value"=>"0"));
        
        //recenti
        $dlg->AddSwitchBoxField("recenti","Recenti",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le nomine scadute da meno dell'arco temporale impostato ."),false);

        //Nominato
        $dlg->AddTextField("incaricato","Nominato",array("bottomLabel"=>"*Filtra in base al nome, cognome o cf del nominato.", "placeholder"=>"nome, cognome o cf del nominato..."));
        
        //Scadute
        $dlg->AddSwitchBoxField("scadute","Scadute",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le nomine scadute da più dell'arco temporale impostato."),false);
 
        //Data scadenzario
        $dlg->AddDateField("data_scadenzario","Data scadenzario",array("editable"=>true,"bottomLabel"=>"*Seleziona la data di riferimento dello scadenzario."));
        
        //Finestra temporale
        $options_finestra=array(array("id"=>1,"value"=>"1 mese"));
        for($i = 2; $i < 13; $i++)
        {
            $options_finestra[]=array("id"=>$i,"value"=>$i." mesi");
        }
        $dlg->AddSelectField("finestra_temporale","Arco temporale",array("bottomLabel"=>"*Seleziona l'arco temporale relativo alla data di riferimento.","options"=>$options_finestra,"value"=>"1"),false);

        //Raggruppamento
        $dlg->AddSwitchBoxField("raggruppamento","Raggruppamento",array("onLabel"=>"nominativi","offLabel"=>"incarico","bottomLabel"=>"*Imposta la modalità di raggruppamento delle nomine (in base alla tipologia di incarico o ai nominativi degli incaricati)."));
        
        return $dlg->GetObject();
    }
    
    //Template filtro di ricerca nomine
    public function Template_GetOrganismoNomineFilterDlg($params=null,$filter_id="")
    {
        if($filter_id=="") $filter_id=$this->id."_Nomine_Filter_Dlg";
        
        if($params==null || $params=="")
        {
            //prende i valori dalla sessione
            $sessionVar=AA_SessionVar::Get($filter_id);
            if($sessionVar->isValid())
            {
                $params=(array) $sessionVar->GetValue();
                //AA_Log::Log(__METHOD__." - ".print_r($params,true),100);
                
            }
            else $params=array();
        }
        
        //Valori runtime
        $formData=array("nomina_altri"=>$params['nomina_altri'],"nomina_ras"=>$params['nomina_ras'],"scadute"=>$params['scadute'], "in_corso"=>$params['in_corso'],"tipo"=>$params['tipo']);
        
        //Valori default
        if(!isset($params['tipo'])) $formData['tipo']="0";
        if(!isset($params['scadute'])) $formData['scadute']="1";
        if(!isset($params['in_corso'])) $formData['in_corso']="1";
        if(!isset($params['nomina_ras'])) $formData['nomina_ras']="1";
        if(!isset($params['nomina_altri'])) $formData['nomina_altri']="1";
        
        //Valori reset
        $resetData=array("tipo"=>"0","nomina_ras"=>"1","nomina_altri"=>"1", "scadute"=>"1","in_corso"=>"1");
        
        $applyActions="AA_MainApp.ui.showWaitMessage('Caricamento in corso...');setTimeout(module.refreshCurSection.bind(module),500)";
        $dlg = new AA_GenericFilterDlg($this->id."_Nomine_Filter_Dlg", "Parametri di filtraggio per le nomine",$this->GetId(),$formData,$resetData,$applyActions);
       
        $dlg->SetHeight(520);
        
        //scadute
        $dlg->AddSwitchBoxField("scadute","Nomine scadute",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le nomine scadute."));
        
        //in corso
        $dlg->AddSwitchBoxField("in_corso","Nomine in corso",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le nomine in corso."));
        
        //nomina ras
        $dlg->AddSwitchBoxField("nomina_ras","Nomine RAS",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le nomine RAS."));
        
        //nomina altri
        $dlg->AddSwitchBoxField("nomina_altri","Nomine non RAS",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le nomine non RAS."));
        
        //Tipologia
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Organismi_Const::GetTipoNomine() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $dlg->AddSelectField("tipo","Incarico",array("tooltip"=>"Filtra in base alla tipologia dell'incarico.","options"=>$options,"value"=>"0"));

        //Imposta l'identificativo di salvataggio
        
        $dlg->SetSaveFilterId($filter_id);
        
        //Salva i valori come variabili di sessione
        $dlg->EnableSessionSave();
        
        return $dlg->GetObject();
    }
    
    //Template pdf export single
    public function Template_OrganismiPdfExport($ids=array(), $bToBrowser=true,$tipo_organismo="")
    {
        if(!is_array($ids)) return "";
        if(sizeof($ids)==0) return "";
        
        //recupero organismi
        
        $organismi=AA_Organismi::Search(array("ids"=>$ids),false,$this->oUser);
        $count = $organismi[0];
        #--------------------------------------------
            
        //nome file
        $filename="pubblicazioni_art22";
        if($tipo_organismo !="")
        {
          $tipo=AA_Organismi_Const::GetTipoOrganismi(true);
          $filename.="-".str_replace(" ","_",$tipo[$tipo_organismo]);
        }
        $filename.="-".date("YmdHis");
        $doc = new AA_PDF_RAS_TEMPLATE_A4_PORTRAIT($filename);
        
        $doc->SetDocumentStyle("font-family: sans-serif; font-size: 3mm;");
        $doc->SetPageCorpoStyle("display: flex; flex-direction: column; justify-content: space-between; padding:0;");
        $curRow=0;
        $rowForPage=1;
        $lastRow=$rowForPage-1;
        $curPage=null;
        $curNumPage=0;
        //$columns_width=array("titolare"=>"10%","incarico"=>"8%","atto"=>"10%","struttura"=>"28%","curriculum"=>"10%","art20"=>"12%","altri_incarichi"=>"10%","1-ter"=>"10%","emolumenti"=>"10%");
        //$columns_width=array("dal"=>"10%","al"=>"10%","inconf"=>"10%","incomp"=>"10%","anno"=>"25%","titolare"=>"50%","tipo_incarico"=>"10%","atto_nomina"=>"10%","struttura"=>"40%","curriculum"=>"25%","altri_incarichi"=>"25%","1-ter"=>"25%","emolumenti"=>"10%");
        $rowContentWidth="width: 99.8%;";

        if($count >1)
        {
            //pagina di intestazione (senza titolo)
            $curPage=$doc->AddPage();
            $curPage->SetCorpoStyle("display: flex; flex-direction: column; justify-content: center; align-items: center; padding:0;");
            $curPage->SetFooterStyle("border-top:.2mm solid black");
            $curPage->ShowPageNumber(false);

            //Intestazione
            $intestazione="<div style='width: 100%; text-align: center; font-size: 24; font-weight: bold'>Pubblicazioni ai sensi dell'art.22 del d.lgs. 33/2013</div>";
            if($tipo_organismo !="") 
            {
              $intestazione.="<div style='width: 100%; text-align: center; font-size: 18; font-weight: bold;'>".$tipo[$tipo_organismo]."</div>";
            }
            $intestazione.="<div style='width: 100%; text-align: center; font-size: x-small; font-weight: normal;margin-top: 3em;'>documento generato il ".date("Y-m-d")."</div>";

            $curPage->SetContent($intestazione);
            $curNumPage++;

            //pagine indice (50 nominativi per pagina)
            $indiceNumVociPerPagina=50;
            for($i=0; $i<$count/$indiceNumVociPerPagina; $i++)
            {
              $curPage=$doc->AddPage();
              $curPage->SetCorpoStyle("display: flex; flex-direction: column; padding:0;");
              $curNumPage++;
            }
            #---------------------------------------
        }
        
        //Imposta il titolo per le pagine successive
        $doc->SetTitle("Pubblicazioni ai sensi dell'art.22 del d.lgs. 33/2013 - report generato il ".date("Y-m-d"));
  
        $indice=array();
        $lastPage=$count/$rowForPage+$curNumPage;
        $curPage_row="";

        //Rendering pagine
        foreach($organismi[1] as $id=>$curOrganismo)
        {
            //inizia una nuova pagina (intestazione)
            if($curRow==$rowForPage) $curRow=0; 
            if($curRow==0)
            {
              $border="";
              if($curPage != null) $curPage->SetContent($curPage_row);
              $curPage=$doc->AddPage();
              $curNumPage++;
              //$curPage->SetCorpoStyle("display: flex; flex-direction: column;  justify-content: space-between; padding:0; border: 1px solid black");
              $curPage_row="";
            }

            $indice[$curOrganismo->GetID()]=$curNumPage."|".$curOrganismo->GetDescrizione();
            $curPage_row.="<div id='".$curOrganismo->GetID()."' style='display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; text-align: center; padding: 0mm; min-height: 9mm;'>";

            $template=new AA_OrganismiPublicReportTemplateView("report_organismo_pdf_".$curOrganismo->GetId(),null,$curOrganismo,$this->oUser);

            //AA_Log::Log($template,100,false,true);

            $curPage_row.=$template;
            $curPage_row.="</div>";
            $curRow++;          
        }
        if($curPage != null) $curPage->SetContent($curPage_row);
        #-----------------------------------------
        
        if($count > 1)
        {
            //Aggiornamento indice
            $curNumPage=1;
            $curPage=$doc->GetPage($curNumPage);
            $vociCount=0;
            $curRow=0;
            $bgColor="";
            $curPage_row="";

            foreach($indice as $id=>$data)
            {
              if($curNumPage != (int)($vociCount/$indiceNumVociPerPagina)+1)
              {
                $curPage->SetContent($curPage_row);
                $curNumPage=(int)($vociCount/$indiceNumVociPerPagina)+1;
                $curPage=$doc->GetPage($curNumPage);
                $curRow=0;
                $bgColor="";
              }

              if($curPage instanceof AA_PDF_Page)
              {
                if($vociCount%2 > 0)
                {
                  $dati=explode("|",$data);
                  $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#".$id."'>".$dati['1']."</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#".$id."'>pag. ".$dati[0]."</a></div>";
                  $curPage_row.="</div>";
                  if($vociCount == (sizeof($indice)-1)) $curPage->SetContent($curPage_row);
                  $curRow++;
                }
                else
                {
                  //Intestazione
                  if($curRow==0) $curPage_row="<div style='width:100%;text-align: center; font-size: 18px; font-weight: bold; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .3em;'>Indice</div>";

                  if($curRow%2) $bgColor="background-color: #f5f5f5;";
                  else $bgColor="";
                  $curPage_row.="<div style='display:flex; ".$rowContentWidth." align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;".$bgColor."'>";
                  $dati=explode("|",$data);
                  $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#".$id."'>".$dati['1']."</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#".$id."'>pag. ".$dati[0]."</a></div>";

                  //ultima voce
                  if($vociCount == (sizeof($indice)-1))
                  {
                    $curPage_row.="<div style='width:40%;text-align: left;padding-left: 10mm'>&nbsp; </div><div style='width:9%;text-align: right;padding-left: 10mm'>&nbsp; </div></div>";
                    $curPage->SetContent($curPage_row);
                  } 
                }
              }

              $vociCount++;
            }            
        }

        if($bToBrowser) $doc->Render();
        else
        {
            $doc->Render(false);
            return $doc->GetFilePath();
        }
    }
}

//Template logView dlg
Class AA_SinesLogDlg extends AA_GenericWindowTemplate
{       
    public function __construct($id = "", $title = "Logs", $user=null)
    {
        parent::__construct($id, $title);
                
        $this->SetWidth("720");
        $this->SetHeight("576");

        //Id oggetto non impostato
        if($_REQUEST['id']=="")
        {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id."_Log_Box",array("type"=>"clean","template"=>"<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>Identificativo oggetto non impostato.</div>")));
            return;
        }

        //Verifica utente
        if($user instanceof AA_User)
        {
            if(!$user->isCurrentUser() || $user->IsGuest())
            {
                $user=AA_User::GetCurrentUser();
            }
        }
        else $user=AA_User::GetCurrentUser();
        
        if($user->IsGuest())
        {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id."_Log_Box",array("type"=>"clean","template"=>"<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>Utente non valido o sessione scaduta.</div>")));
            return;
        }

        $object = AA_Organismi::Load($_REQUEST['id'],$user);
        
        //Invalid object
        if(!$object->IsValid())
        {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id."_Log_Box",array("type"=>"clean","template"=>"<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>Oggetto non valido o permessi insufficienti.</div>")));
            return;
        }
        
        //permessi insufficienti
        if(($object->GetUserCaps($user)&AA_Const::AA_PERMS_WRITE) == 0)
        {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id."_Log_Box",array("type"=>"clean","template"=>"<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>L'utente corrente non ha i permessi per visualizzare i logs dell'oggetto.</div>")));
            return;
        }

        $logs=$object->GetLog();

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "scrollX"=>false,
            "select"=>false,
            "columns"=>array(
                array("id"=>"data","header"=>array("Data",array("content"=>"textFilter")),"width"=>150, "css"=>array("text-align"=>"left")),
                array("id"=>"user","header"=>array("<div style='text-align: center'>Utente</div>",array("content"=>"selectFilter")),"width"=>120, "css"=>array("text-align"=>"center")),
                array("id"=>"msg","header"=>array("Operazione",array("content"=>"selectFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"))
            ),
            "data"=>$logs->GetLog()
        ));

        //riquadro di visualizzazione preview pdf
        $this->body->AddRow($table);
        $this->body->AddRow(new AA_JSON_Template_Generic("",array("view"=>"spacer","height"=>38)));
    }
    
    protected function Update()
    {
        parent::Update();
    }
}
