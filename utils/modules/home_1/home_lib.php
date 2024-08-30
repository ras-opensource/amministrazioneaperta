<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("config.php");
include_once("system_lib.php");

//Classe per la gestione del modulo home
Class AA_HomeModule extends AA_GenericModule
{
    const AA_UI_PREFIX="AA_Home";

    //Id modulo
    const AA_ID_MODULE="AA_MODULE_HOME";

    //main ui layout box
    const AA_UI_MODULE_MAIN_BOX="AA_Home_module_layout";

    //------- Sezione cruscotto -------
    //Id sezione
    const AA_ID_SECTION_DESKTOP="home_desktop";

    //nome sezione
    const AA_UI_SECTION_DESKTOP_NAME="Cruscotto";

    const AA_UI_SECTION_DESKTOP="Desktop_Content_Box";

    const AA_UI_SECTION_DESKTOP_ICON="mdi mdi-desktop-classic";
    //------------------------------

    //------- Sezione gestione utenti -------
    //Id sezione
    const AA_ID_SECTION_GESTUTENTI="home_gestutenti";

    //nome sezione
    const AA_UI_SECTION_GESTUTENTI_NAME="Gestione utenti";

    const AA_UI_SECTION_GESTUTENTI="Gestutenti_Content_Box";

    const AA_UI_WND_IMPORT_UTENTI_LEGACY="AA_HomeUtentiLegacyImport_Dlg";
    const AA_UI_TABLE_IMPORT_UTENTI_LEGACY="DataTable";

    const AA_UI_SECTION_GESTUTENTI_ICON="mdi mdi-account-edit";
    //------------------------------

    //------- Sezione gestione strutture -------
    //Id sezione
    const AA_ID_SECTION_GESTSTRUCT="home_geststruct";

    //nome sezione
    const AA_UI_SECTION_GESTSTRUCT_NAME="Gestione strutture";

    const AA_UI_SECTION_GESTSTRUCT="Geststruct_Content_Box";

    const AA_UI_SECTION_GESTSTRUCT_ICON="mdi mdi-office-building-cog";
    //------------------------------

    //istanza
    protected static $oInstance=null;
    
    //Restituisce l'istanza corrente
    public static function GetInstance($user=null)
    {
        if(self::$oInstance==null)
        {
            self::$oInstance=new AA_HomeModule($user);
        }
        
        return self::$oInstance;
    }
    
    public function __construct($user=null) {
        parent::__construct($user,false);
        
        #--------------------------------Registrazione dei task-----------------------------
        $taskManager=$this->GetTaskManager();

        //Gestione utenti
        $taskManager->RegisterTask("GetHomeUtentiFilterDlg");
        $taskManager->RegisterTask("GetHomeUtentiModifyDlg");
        $taskManager->RegisterTask("HomeUtentiUpdate");
        $taskManager->RegisterTask("HomeUtentiSendCredenzials");
        $taskManager->RegisterTask("HomeUtentiConfirmSendCredenzials");
        $taskManager->RegisterTask("GetHomeUtentiAddNewDlg");
        $taskManager->RegisterTask("HomeUtentiAddNew");
        $taskManager->RegisterTask("GetHomeRngDlg");
        $taskManager->RegisterTask("HomeRngOut");
        $taskManager->RegisterTask("HomeRngOutPdf");
        $taskManager->RegisterTask("GetHomeUtentiTrashDlg");
        $taskManager->RegisterTask("HomeUtentiTrash");
        $taskManager->RegisterTask("HomeUtentiResume");
        
        //gestione strutture
        $taskManager->RegisterTask("GetHomeStructAddNewDlg");
        $taskManager->RegisterTask("HomeStructAddNew");
        $taskManager->RegisterTask("GetHomeStructModifyDlg");
        $taskManager->RegisterTask("HomeStructUpdate");
        $taskManager->RegisterTask("GetHomeStructTrashDlg");
        $taskManager->RegisterTask("HomeStructDelete");

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $taskManager->RegisterTask("GetHomeUtentiLegacyImportDlg");
            $taskManager->RegisterTask("GetHomeUtentiLegacyFilterDlg");
            $taskManager->RegisterTask("HomeUtentiImport");
        }

        $taskManager->RegisterTask("GetHomeUtentiModifyDlg");
        //----------------------------------------------------------------------------------
        
        //Sezioni
        $gestutenti=false;
        if($user instanceof AA_User && $user->CanGestUtenti()) $gestutenti =true;
        
        $geststrutture=false;
        if($user instanceof AA_User && $user->CanGestStruct() && AA_Const::AA_ENABLE_LEGACY_DATA) $geststrutture=true;

        //Gestione Gruppi
        $gestGruppi=false;
        if($user instanceof AA_User && $user->CanGestUtenti()) $gestGruppi =true;

        //main
        $section=new AA_GenericModuleSection(static::AA_ID_SECTION_DESKTOP,static::AA_UI_SECTION_DESKTOP_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_DESKTOP,$this->GetId(),true,true,false,true);
        $section->SetIcon(static::AA_UI_SECTION_DESKTOP_ICON);
        if($gestutenti) 
        {
            if($geststrutture) $section->SetNavbarTemplate(array($this->TemplateNavbar_Gestutenti(1,false)->toArray(),$this->TemplateNavbar_GestStruct(2)->toArray()));
            else $section->SetNavbarTemplate(array($this->TemplateNavbar_Gestutenti(1)->toArray()));
        }
        else $section->SetNavbarTemplate($this->TemplateGenericNavbar_Void(1,true)->toArray());

        $this->AddSection($section);
        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DESKTOP,"TemplateSection_Desktop");

        if($gestutenti)
        {
            //Gestione utenti
            $section=new AA_GenericModuleSection(static::AA_ID_SECTION_GESTUTENTI,static::AA_UI_SECTION_GESTUTENTI_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GESTUTENTI,$this->GetId(),false,true,false,true);
            $section->SetIcon(static::AA_UI_SECTION_GESTUTENTI_ICON);
            if($geststrutture)
            {
                $section->SetNavbarTemplate(array($this->TemplateNavbar_Cruscotto(1,false)->toArray(),$this->TemplateNavbar_Geststruct(2)->toArray()));
            }
            else $section->SetNavbarTemplate($this->TemplateNavbar_Cruscotto()->toArray());
            $this->AddSection($section);
            $this->SetSectionItemTemplate(static::AA_ID_SECTION_GESTUTENTI,"TemplateSection_GestUtenti");
        }
        
        if($geststrutture)
        {
            //Gestione strutture
            $section=new AA_GenericModuleSection(static::AA_ID_SECTION_GESTSTRUCT,static::AA_UI_SECTION_GESTSTRUCT_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GESTSTRUCT,$this->GetId(),false,true,false,true);
            $section->SetIcon(static::AA_UI_SECTION_GESTSTRUCT_ICON);
            if($gestutenti)
            {
                $section->SetNavbarTemplate(array($this->TemplateNavbar_Cruscotto(1,false)->toArray(),$this->TemplateNavbar_Gestutenti(2)->toArray()));
            }
            else $section->SetNavbarTemplate($this->TemplateNavbar_Cruscotto()->toArray());
            $this->AddSection($section);
            $this->SetSectionItemTemplate(static::AA_ID_SECTION_GESTSTRUCT,"TemplateSection_GestStruct");
        }
        #-------------------------------------------
    }

    //Task filter dlg
    public function Task_GetHomeUtentiFilterDlg($task)
    {
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->TemplateHomeUtentiFilterDlg($_REQUEST);
        $sTaskLog.= base64_encode($content);
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

     //Task filter dlg
     public function Task_GetHomeUtentiLegacyFilterDlg($task)
     {
         $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
         $content=$this->TemplateHomeUtentiLegacyFilterDlg($_REQUEST);
         $sTaskLog.= base64_encode($content);
         $sTaskLog.="</content>";
         
         $task->SetLog($sTaskLog);
         
         return true;
     }

    //Task modify user dlg
    public function Task_GetHomeUtentiModifyDlg($task)
    {
        if(!$this->oUser->CanGestUtenti())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non è abilitato alla gestione utenti.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        $user=AA_User::LoadUser($_REQUEST['id']);
        if(!$user->IsValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente specificato non è stato trovato.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        if(!$this->oUser->CanModifyUser($user))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non può modificare l'utente specificato.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->Template_GetHomeUtentiModifyDlg($user);
        $sTaskLog.= base64_encode($content);
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task trash user
    public function Task_HomeUtentiTrash($task)
    {
        if(!$this->oUser->CanGestUtenti())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non è abilitato alla gestione utenti.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        $user=AA_User::LoadUser($_REQUEST['id']);
        if(!$user->IsValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente specificato non è stato trovato.</error>";
            $task->SetLog($sTaskLog);
            return false;
        }

        if(!$this->oUser->CanModifyUser($user))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non può modificare l'utente specificato.</error>";
            $task->SetLog($sTaskLog);
            return false;
        }

        $trash=true;
        if($user->GetStatus()==AA_User::AA_USER_STATUS_DELETED) $trash=false;
        if(!$this->oUser->DeleteUser($user,$trash))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError(AA_Log::$lastErrorLog,false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Utente cestinato/eliminato con successo.",false);
        return true;
    }

    //Task trash user
    public function Task_HomeUtentiImport($task)
    {
        if(!$this->oUser->CanGestUtenti())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non è abilitato alla gestione utenti.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        $user=AA_User::LegacyLoadUser($_REQUEST['id']);
        if(!$user->IsValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente specificato non è stato trovato.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        if(!$this->oUser->CanModifyUser($user))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non può modificare l'utente specificato.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        $db = new AA_Database();
        if(!$db->Query("SELECT passwd FROM utenti WHERE id='".$user->GetId()."'"))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore generico di accesso al db.");
            return false;
        }

        $rs=$db->GetResultSet();

        if(!AA_User::MigrateLegacyUser($user,"",$rs[0]['passwd']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError(AA_Log::$lastErrorLog,false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Utente migrato con successo.",false);
        return true;
    }

    //Task trash user
    public function Task_HomeUtentiResume($task)
    {
        if(!$this->oUser->CanGestUtenti())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non è abilitato alla gestione utenti.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        $user=AA_User::LoadUser($_REQUEST['id']);
        if(!$user->IsValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente specificato non è stato trovato.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        if(!$this->oUser->CanModifyUser($user))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non può modificare l'utente specificato.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        if(!$this->oUser->ResumeUser($user))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError(AA_Log::$lastErrorLog,false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Utente ripristinato con successo.",false);
        return true;
    }

    //Task trash user dlg
    public function Task_GetHomeUtentiTrashDlg($task)
    {
        if(!$this->oUser->CanGestUtenti())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non è abilitato alla gestione utenti.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        $user=AA_User::LoadUser($_REQUEST['id']);
        if(!$user->IsValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente specificato non è stato trovato.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        if(!$this->oUser->CanModifyUser($user))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non può modificare l'utente specificato.</error>";
            $task->SetLog($sTaskLog);
            return false; 
        }

        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->Template_GetHomeUtentiTrashDlg($user);
        $sTaskLog.= base64_encode($content);
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task trash user dlg
    public function Task_GetHomeRngDlg($task)
    {
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetHomeRngDlg(),true);
        return true;
    }

    //Task rng out
    public function Task_HomeRngOut($task)
    {
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        //AA_MainApp.utils.callHandler("pdfPreview", {url: "'.$this->GetTaskManagerUrl().'?task=GetSierComuneRendicontiExportRasPdf&id='.$object->GetId().'&id_comune='.$comune->GetProp('id').'"},"'.$this->id.'")
        $task->SetStatusAction("pdfPreview",'{"url": "'.$this->GetTaskManagerUrl().'?task=HomeRngOutPdf&start='.$_REQUEST['start'].'&end='.$_REQUEST['end'].'&count='.$_REQUEST['count'].'"}',false);
        $task->SetContent("Generazione prospetto pdf in corso...",false);
        return true;
    }

    //Task rng out pdf
    public function Task_HomeRngOutPdf($task)
    {
        die($this->Template_HomeRngOutPdf($_REQUEST['start'],$_REQUEST['end'],$_REQUEST['count']));
    }
    
    //Task add new user dlg
    public function Task_GetHomeUtentiAddNewDlg($task)
    {
        if(!$this->oUser->CanGestUtenti())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione utenti.");
            return false; 
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetHomeUtentiAddNewDlg(),true);
        return true;
    }

    //Task add new struct dlg
    public function Task_GetHomeStructAddNewDlg($task)
    {
        if(!$this->oUser->CanGestStruct())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione strutture.");
            return false; 
        }

        $struct=$this->oUser->GetStruct();
        if(!isset($_REQUEST['id_assessorato'])) $_REQUEST['id_assessorato'] = $struct->GetAssessorato(true);
        if(!isset($_REQUEST['id_direzione'])) $_REQUEST['id_direzione'] = $struct->GetDirezione(true);

        if($_REQUEST['id_assessorato'] != $struct->GetAssessorato(true) && $struct->GetAssessorato(true) !=0)
        {
            $_REQUEST['id_assessorato']=$struct->GetAssessorato(true);
        }

        if($_REQUEST['id_direzione'] != $struct->GetDirezione(true) && $struct->GetDirezione(true) !=0)
        {
            $_REQUEST['id_direzione']=$struct->GetDirezione(true);
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetHomeStructAddNewDlg($_REQUEST['id_assessorato'],$_REQUEST['id_direzione']),true);
        return true;
    }

    //Task modify struct dlg
    public function Task_GetHomeStructModifyDlg($task)
    {
        if(!$this->oUser->CanGestStruct())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione strutture.");
            return false; 
        }

        $struct=$this->oUser->GetStruct();
        if(!isset($_REQUEST['id_assessorato']) || $_REQUEST['id_assessorato']==0) 
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Struttura non indicata.");
            return false; 
        }

        if(isset($_REQUEST['id_assessorato']) && $_REQUEST['id_assessorato']!=$struct->GetAssessorato(true) && $struct->GetAssessorato(true) !=0) 
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' modificare la struttura indicata.");
            return false; 
        }

        if($struct->GetDirezione(true) !=0 &&(!isset($_REQUEST['id_direzione']) || $_REQUEST['id_direzione'] != $struct->GetDirezione(true)) && $struct->GetDirezione(true) !=0) 
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' modificare la struttura indicata.");
            return false; 
        }

        if($struct->GetServizio(true) !=0 &&(!isset($_REQUEST['id_servizio']) || $_REQUEST['id_servizio'] != $struct->GetServizio(true)) && $struct->GetServizio(true) !=0) 
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' modificare la struttura indicata.");
            return false; 
        }

        $oStruct=null;
        if(isset($_REQUEST['id_assessorato']) && $_REQUEST['id_assessorato']>0) 
        {
            $oStruct=new AA_Assessorato();
            if(!$oStruct->Load($_REQUEST['id_assessorato']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Struttura non trovata (0).");
                return false; 
            }
        }
        if(isset($_REQUEST['id_direzione']) && $_REQUEST['id_direzione']>0) 
        {
            $oStruct=new AA_Direzione();
            if(!$oStruct->Load($_REQUEST['id_direzione']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Struttura non trovata (1).");
                return false; 
            }
        }
        if(isset($_REQUEST['id_servizio']) && $_REQUEST['id_servizio']>0) 
        {
            $oStruct=new AA_Servizio();
            if(!$oStruct->Load($_REQUEST['id_servizio']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Struttura non trovata (0).");
                return false; 
            }
        }

        if($oStruct)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetHomeStructModifyDlg($oStruct),true);
            return true;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("Struttura non trovata.",false);
        return false;     
    }

    //Task modify trash dlg
    public function Task_GetHomeStructTrashDlg($task)
    {
        if(!$this->oUser->CanGestStruct())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione strutture.");
            return false; 
        }

        $struct=$this->oUser->GetStruct();
        if(!isset($_REQUEST['id_assessorato']) || $_REQUEST['id_assessorato']==0) 
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Struttura non indicata.");
            return false; 
        }

        if(isset($_REQUEST['id_assessorato']) && $_REQUEST['id_assessorato']!=$struct->GetAssessorato(true) && $struct->GetAssessorato(true) !=0) 
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' modificare la struttura indicata.");
            return false; 
        }

        if($struct->GetDirezione(true) !=0 &&(!isset($_REQUEST['id_direzione']) || $_REQUEST['id_direzione'] != $struct->GetServizio(true))) 
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' modificare la struttura indicata.");
            return false; 
        }

        if($struct->GetServizio(true) !=0 &&(!isset($_REQUEST['id_servizio']) || $_REQUEST['id_servizio'] != $struct->GetServizio(true))) 
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' modificare la struttura indicata.");
            return false; 
        }

        $oStruct=null;
        if(isset($_REQUEST['id_assessorato']) && $_REQUEST['id_assessorato']>0) 
        {
            $oStruct=new AA_Assessorato();
            if(!$oStruct->Load($_REQUEST['id_assessorato']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Struttura non trovata (0).");
                return false; 
            }
        }
        if(isset($_REQUEST['id_direzione']) && $_REQUEST['id_direzione']>0) 
        {
            $oStruct=new AA_Direzione();
            if(!$oStruct->Load($_REQUEST['id_direzione']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Struttura non trovata (1).");
                return false; 
            }
        }
        if(isset($_REQUEST['id_servizio']) && $_REQUEST['id_servizio']>0) 
        {
            $oStruct=new AA_Servizio();
            if(!$oStruct->Load($_REQUEST['id_servizio']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Struttura non trovata (0).");
                return false; 
            }
        }

        if($oStruct)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetHomeStructTrashDlg($oStruct),true);
            return true;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("Struttura non trovata.",false);
        return false;     
    }

    //Task import legacy user dlg
    public function Task_GetHomeUtentiLegacyImportDlg($task)
    {
        if(!$this->oUser->CanGestUtenti())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione utenti.");
            return false; 
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetHomeUtentiLegacyImportDlg(),true);
        return true;
    }

    //Task update user
    public function Task_HomeUtentiUpdate($task)
    {
        if(!$this->oUser->CanGestUtenti())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione utenti.");
            return false; 
        }

        $user=AA_User::LoadUser($_REQUEST['id']);
        if(!$user->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente specificato non è stato trovato.");
            return false; 
        }

        if(!$this->oUser->CanModifyUser($user))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può modificare l'utente specificato.");
            return false; 
        }

        $params['id']=$_REQUEST['id'];
        if(isset($_REQUEST['user']) && $_REQUEST['user'] !="") $params['user']=$_REQUEST['user'];
        if(isset($_REQUEST['email']) && $_REQUEST['email'] !="") $params['email']=$_REQUEST['email'];
        $params['phone']=trim($_REQUEST['phone']);
        $params['nome']=trim($_REQUEST['nome']);
        $params['cognome']=trim($_REQUEST['cognome']);
        
        //ruolo
        if(isset($_REQUEST['ruolo']) && $_REQUEST['ruolo']>0) $params['ruolo']=$_REQUEST['ruolo'];
        else $params['ruolo']=$user->GetRuolo(true);
        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if($params['ruolo'] < AA_User::AA_USER_GROUP_OPERATORS) $params['livello']=0;
            else $params['livello']=1;
            if($params['ruolo']==AA_User::AA_USER_GROUP_USERS) $params['livello']=2;
        }

        //stato
        if(isset($_REQUEST['status']))
        {
            //AA_log::Log(__METHOD__." - status: ".$_REQUEST['status'],100);
            $params['status']=$_REQUEST['status'];
        } 
        else $params['status']=$user->GetStatus();

        $userFlags=array();

        //accesso concorrente
        if(isset($_REQUEST['concurrent']) && $_REQUEST['concurrent'] > 0) 
        {
            $userFlags[]="concurrent";
            if(AA_Const::AA_ENABLE_LEGACY_DATA) $params['concurrent']=1;
        }
        else 
        {
            if(AA_Const::AA_ENABLE_LEGACY_DATA) $params['concurrent']=0;
        }

        //flags
        $modulesFlagsKeys=array_keys(AA_Platform::GetAllModulesFlags());
        
        foreach($modulesFlagsKeys as $curFlag)
        {
            if(isset($_REQUEST['flag_'.$curFlag]) && $_REQUEST['flag_'.$curFlag]==1)
            {
                $userFlags[]=$curFlag;
            } 
        }
        if(sizeof($userFlags)>0)
        {
            $params['flags']=implode("|",$userFlags);
        }
        else $params['flags']="";
        
        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            //Legacy flags
            if($user->CanGestUtenti()) $params['gest_utenti']=1;
            if($user->CanGestStruct()) $params['gest_struct']=1;
            $params['unlock']=1;
            
            $legacyFlags=array_keys(AA_Platform::GetLegacyFlags());
            foreach($legacyFlags as $curFlag)
            {
                if(isset($_REQUEST['legacyFlag_'.$curFlag]) && $_REQUEST['legacyFlag_'.$curFlag]==1) $params['legacyFlag_'.$curFlag]=1;
            }

            //struttura
            $struct=$user->GetStruct();
            if(isset($_REQUEST['id_assessorato'])) $params['assessorato']=$_REQUEST['id_assessorato'];
            else $params['assessorato']=$struct->GetAssessorato(true);
            if(isset($_REQUEST['id_direzione'])) $params['direzione']=$_REQUEST['id_direzione'];
            else $params['direzione']=$struct->GetDirezione(true);
            if(isset($_REQUEST['id_servizio'])) $params['servizio']=$_REQUEST['id_servizio'];
            else $params['servizio']=$struct->GetServizio(true);
        }

        if(!$this->oUser->UpdateUser($user,$params))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError(AA_log::$lastErrorLog);
            return false;      
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Utente aggiornato con successo.");
        
        return true;
    }

    //Task addnew user
    public function Task_HomeUtentiAddNew($task)
    {
        if(!$this->oUser->CanGestUtenti())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione utenti.");
            return false; 
        }

        $params['id']=$_REQUEST['id'];
        if(isset($_REQUEST['user']) && $_REQUEST['user'] !="") $params['user']=$_REQUEST['user'];
        if(isset($_REQUEST['email']) && $_REQUEST['email'] !="") $params['email']=$_REQUEST['email'];
        $params['phone']=$_REQUEST['phone'];
        $params['nome']=$_REQUEST['nome'];
        $params['cognome']=$_REQUEST['cognome'];
        
        //ruolo
        if(isset($_REQUEST['ruolo']) && $_REQUEST['ruolo']>0) $params['ruolo']=$_REQUEST['ruolo'];
        else $params['ruolo']=AA_User::AA_USER_GROUP_USERS;
        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if($params['ruolo'] < AA_User::AA_USER_GROUP_OPERATORS) $params['livello']=0;
            else $params['livello']=1;
            if($params['ruolo']==AA_User::AA_USER_GROUP_USERS) $params['livello']=2;
        }

        //stato
        if(isset($_REQUEST['status']))
        {
            //AA_log::Log(__METHOD__." - status: ".$_REQUEST['status'],100);
            $params['status']=$_REQUEST['status'];
        } 
        else $params['status']=AA_User::AA_USER_STATUS_DISABLED;

        $userFlags=array();

        //accesso concorrente
        if(isset($_REQUEST['concurrent']) && $_REQUEST['concurrent'] > 0) 
        {
            $userFlags[]="concurrent";
            if(AA_Const::AA_ENABLE_LEGACY_DATA) $params['concurrent']=1;
        }
        else 
        {
            if(AA_Const::AA_ENABLE_LEGACY_DATA) $params['concurrent']=0;
        }

        //flags
        $modulesFlagsKeys=array_keys(AA_Platform::GetAllModulesFlags());
        
        foreach($modulesFlagsKeys as $curFlag)
        {
            if(isset($_REQUEST['flag_'.$curFlag]) && $_REQUEST['flag_'.$curFlag]==1)
            {
                $userFlags[]=$curFlag;
            } 
        }
        if(sizeof($userFlags)>0)
        {
            $params['flags']=implode("|",$userFlags);
        }
        
        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            //Legacy flags
            if($params['ruolo']==AA_User::AA_USER_GROUP_SERVEROPERATORS)
            {
                $params['gest_utenti']=1;
                $params['gest_struct']=1;
            } 
            $params['unlock']=1;
            
            $legacyFlags=array_keys(AA_Platform::GetLegacyFlags());
            foreach($legacyFlags as $curFlag)
            {
                if(isset($_REQUEST['legacyFlag_'.$curFlag]) && $_REQUEST['legacyFlag_'.$curFlag]==1) $params['legacyFlag_'.$curFlag]=1;
            }

            //struttura
            $struct=$this->oUser->GetStruct();
            if($struct->GetAssessorato(true)>0) $params['assessorato']=$struct->GetAssessorato(true);
            else $params['assessorato']=$_REQUEST['id_assessorato'];
            if($struct->GetDirezione(true)>0) $params['direzione']=$struct->GetDirezione(true);
            else $params['direzione']=$_REQUEST['id_direzione'];
            if($struct->GetServizio(true)>0) $params['servizio']=$struct->GetServizio(true);
            else $params['servizio']=$_REQUEST['id_servizio'];
        }

        if(!$this->oUser->AddNewUser($params))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError(AA_Log::$lastErrorLog);
            return false;      
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Utente inserito con successo.");
        
        return true;
    }

    //Task addnew struct
    public function Task_HomeStructAddNew($task)
    {
        if(!$this->oUser->CanGestStruct())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione strutture.");
            return false; 
        }

        $struct=$this->oUser->GetStruct();
        if($_REQUEST['id_assessorato'] != $struct->GetAssessorato(true) && $struct->GetAssessorato(true) !=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' aggiungere la struttura impostata (0).");
            return false; 
        }

        if($_REQUEST['id_direzione'] != $struct->GetDirezione(true) && $struct->GetDirezione(true) !=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' aggiungere la struttura impostata (1).");
            return false; 
        }

        if($struct->GetServizio(true) !=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' aggiungere la struttura impostata (3).");
            return false; 
        }

        if(isset($_REQUEST['data_istituzione']) && $_REQUEST['data_istituzione']>Date("Y-m-d")) $_REQUEST['data_istituzione']=Date("Y-m-d");

        //backward compatib=ility
        if(isset($_REQUEST['tipo'])) $_REQUEST['tipo']-=1;
        if(isset($_REQUEST['data_istituzione'])) $_REQUEST['data_istituzione']=substr($_REQUEST['data_istituzione'],0,10);
        if(isset($_REQUEST['data_soppressione'])) $_REQUEST['data_soppressione']=substr($_REQUEST['data_soppressione'],0,10);

        $newStruct=null;

        if($_REQUEST['id_direzione'] !=0)
        {
            $newStruct=new AA_Servizio($_REQUEST);
        }
        else
        {
            if($_REQUEST['id_assessorato'] !=0)
            {
                $newStruct=new AA_Direzione($_REQUEST);
            }
            else
            {
                $newStruct=new AA_Assessorato($_REQUEST);
            }
        }

        if($newStruct)
        {
            if(!$newStruct->Update(null,$this->oUser))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Errore nell'aggiunta della nuova struttura (0).");
                return false; 
            }
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Errore nell'aggiunta della nuova struttura (1).");
                return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Struttura aggiunta con successo.");
        
        return true;
    }

    //Task Update struct
    public function Task_HomeStructUpdate($task)
    {
        if(!$this->oUser->CanGestStruct())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione strutture.");
            return false; 
        }

        $struct=$this->oUser->GetStruct();
        if($_REQUEST['id_assessorato'] != $struct->GetAssessorato(true) && $struct->GetAssessorato(true) !=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' modificare la struttura (0).");
            return false; 
        }

        if($_REQUEST['id_direzione'] != $struct->GetDirezione(true) && $struct->GetDirezione(true) !=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' modificare la struttura (1).");
            return false; 
        }

        if($struct->GetServizio(true) !=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' modificare la struttura (3).");
            return false; 
        }

        if(isset($_REQUEST['soppressa']) && $_REQUEST['data_soppressione'] > Date("Y-m-d 23:59:59") && $_REQUEST['soppressa']==1)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("La data di soppressione non puo' essere una data futura.");
            return false; 
        }

        //backward compatib=ility
        if(isset($_REQUEST['tipo'])) $_REQUEST['tipo']-=1;

        if(isset($_REQUEST['data_istituzione']) && $_REQUEST['data_istituzione']>Date("Y-m-d")) $_REQUEST['data_istituzione']=Date("Y-m-d");
        if(isset($_REQUEST['data_istituzione'])) $_REQUEST['data_istituzione']=substr($_REQUEST['data_istituzione'],0,10);
        if(isset($_REQUEST['data_soppressione'])) $_REQUEST['data_soppressione']=substr($_REQUEST['data_soppressione'],0,10);

        $newStruct=null;

        if($_REQUEST['id_direzione'] !=0)
        {
            $newStruct=new AA_Servizio($_REQUEST);
        }
        else
        {
            if($_REQUEST['id_assessorato'] !=0)
            {
                $newStruct=new AA_Direzione($_REQUEST);
            }
            else
            {
                $newStruct=new AA_Assessorato($_REQUEST);
            }
        }

        if($newStruct)
        {
            if(!$newStruct->Update(null,$this->oUser))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Errore nell'aggiornamento della struttura (0).");
                return false; 
            }
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Errore nell'aggiornamento della struttura (1).");
                return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Struttura aggiornata con successo.");
        
        return true;
    }

    //Task delete struct
    public function Task_HomeStructDelete($task)
    {
        if(!$this->oUser->CanGestStruct())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione strutture.");
            return false; 
        }

        $struct=$this->oUser->GetStruct();
        if($_REQUEST['id_assessorato'] != $struct->GetAssessorato(true) && $struct->GetAssessorato(true) !=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' modificare la struttura (0).");
            return false; 
        }

        if($_REQUEST['id_direzione'] != $struct->GetDirezione(true) && $struct->GetDirezione(true) !=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' modificare la struttura (1).");
            return false; 
        }

        if($struct->GetServizio(true) !=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non puo' modificare la struttura (3).");
            return false; 
        }

        $struct=null;

        if($_REQUEST['id_direzione'] !=0)
        {
            $struct=new AA_Servizio();
        }
        else
        {
            if($_REQUEST['id_assessorato'] !=0)
            {
                $struct=new AA_Direzione();
            }
            else
            {
                $struct=new AA_Assessorato();
            }
        }

        if(!$struct->Load($_REQUEST['id']))
        {
            AA_Log::Log(__METHOD__." - Errore nel caricamento della struttura: ".print_r($struct,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'eliminazione della struttura - struttura non trovata.");
            return false; 
        }
     
        if(!$struct->Delete($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'eliminazione della struttura.");
            return false; 
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Struttura eliminata con successo.",false);
        
        return true;
    }

    //Template navbar cruscotto
    protected function TemplateNavbar_Cruscotto($level=1,$last=true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $id=static::AA_UI_PREFIX . "_Navbar_Link_".uniqid(time());
        $navbar =  new AA_JSON_Template_Template(
            $id,
            array(
                "type" => "clean",
                "css" => "AA_NavbarEventListener",
                "module_id" => $this->GetId(),
                "tooltip" => "Visualizza il cruscotto",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='".$id."' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"".static::AA_ID_SECTION_DESKTOP."\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => static::AA_UI_SECTION_DESKTOP_NAME, "class" => $class,"icon"=>static::AA_UI_SECTION_DESKTOP_ICON)
            )
        );
        return $navbar;
    }

    //Template navbar gestione utenti
    protected function TemplateNavbar_GestUtenti($level=1,$last=true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $id=static::AA_UI_PREFIX . "_Navbar_Link_".uniqid(time());
        $navbar =  new AA_JSON_Template_Template(
            $id,
            array(
                "type" => "clean",
                "css" => "AA_NavbarEventListener",
                "module_id" => $this->GetId(),
                "tooltip" => "Visualizza la gestione utenti",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='".$id."' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"".static::AA_ID_SECTION_GESTUTENTI."\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => static::AA_UI_SECTION_GESTUTENTI_NAME, "class" => $class,"icon"=>static::AA_UI_SECTION_GESTUTENTI_ICON)
            )
        );
        return $navbar;
    }

    //Template navbar gestione utenti
    protected function TemplateNavbar_GestStruct($level=1,$last=true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $id=static::AA_UI_PREFIX . "_Navbar_Link_".uniqid(time());
        $navbar =  new AA_JSON_Template_Template(
            $id,
            array(
                "type" => "clean",
                "css" => "AA_NavbarEventListener",
                "module_id" => $this->GetId(),
                "tooltip" => "Visualizza la gestione strutture",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='".$id."' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"".static::AA_ID_SECTION_GESTSTRUCT."\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => static::AA_UI_SECTION_GESTSTRUCT_NAME, "class" => $class,"icon"=>static::AA_UI_SECTION_GESTSTRUCT_ICON)
            )
        );
        return $navbar;
    }

    //Template cruscotto context menu
    public function TemplateActionMenu_Cruscotto()
    {
        $menu=new AA_JSON_Template_Generic(
            static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DESKTOP."_ActionMenu",array(
            "view"=>"contextmenu",
            "data"=>array(array(
                   "id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DESKTOP."_ActionMenuItem_Aggiorna",
                   "value"=>"Aggiorna",
                   "icon"=>"mdi mdi-reload",
                   "module_id" => $this->GetId(),
                   "handler"=>"refreshUiObject",
                   "handler_params"=>array(static::AA_UI_PREFIX."_".static::AA_UI_SECTION_DESKTOP,true)
                ))
            ));
        
        return $menu;  
    }

    //Template gestutenti context menu
    public function TemplateActionMenu_Gestutenti()
    {
        $menu=new AA_JSON_Template_Generic(
            static::AA_UI_PREFIX."_".static::AA_ID_SECTION_GESTUTENTI."_ActionMenu",array(
            "view"=>"contextmenu",
            "data"=>array(array(
                   "id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_GESTUTENTI."_ActionMenuItem_Aggiorna",
                   "value"=>"Aggiorna",
                   "icon"=>"mdi mdi-reload",
                   "module_id" => $this->GetId(),
                   "handler"=>"refreshUiObject",
                   "handler_params"=>array(static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GESTUTENTI,true)
                ))
            ));
        
        return $menu;  
    }

    //Template gestutenti context menu
    public function TemplateActionMenu_GestStruct()
    {
        $menu=new AA_JSON_Template_Generic(
            static::AA_UI_PREFIX."_".static::AA_ID_SECTION_GESTSTRUCT."_ActionMenu",array(
            "view"=>"contextmenu",
            "data"=>array(array(
                   "id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_GESTSTRUCT."_ActionMenuItem_Aggiorna",
                   "value"=>"Aggiorna",
                   "icon"=>"mdi mdi-reload",
                   "module_id" => $this->GetId(),
                   "handler"=>"refreshUiObject",
                   "handler_params"=>array(static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GESTSTRUCT,true)
                ))
            ));
        
        return $menu;  
    }
    
    //Template news content
    public function TemplateSection_News()
    {
        $news_box=new AA_JSON_Template_Layout("AA_Home_News_Box",array("type"=>"space","css"=>"AA_Desktop_Section_Box"));
        $news_layout = new AA_JSON_Template_Generic("AA_Home_News_Content",
                array(
                "view"=>"timeline",
                "css"=>array("background-color"=>"transparent"),
                "type"=>array(
                    "height"=>"auto",
                    "width"=>800,
                    "type"=>"left",
                    "lineColor"=>"skyblue"
                )
            ));
         
        $db = new AA_Database();
        
        $query="SELECT * from news WHERE archivio='0' order by data DESC";
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__."() - errore: ".$db->GetErrorMessage()." nella query: ".$query,100);
            return "";
        }

        $data=array();
        foreach($db->GetResultSet() as $row)
        {
            $data[]=array("id"=>$row['id'],"date"=>$row['data'],"value"=>$row['oggetto'],"details"=>$row['corpo']);
        }
        
        $news_layout->setProp('data',$data);
        
        $news_box->AddRow(new AA_JSON_Template_Generic("HomeNewsBoxTitle",array("view"=>"label","align"=>"center","label"=>"<span class='AA_Desktop_Section_Label'>News</span>")));
        $news_box->AddRow($news_layout);

        return $news_box;
    }

    //Template risorse content
    public function TemplateSection_Risorse()
    {

        $section_box=new AA_JSON_Template_Layout("AA_Home_Risorse_Box",array("type"=>"space","css"=>"AA_Desktop_Section_Box"));
        
        //mdi-server-security mdi-finance
        $section_box->AddRow(new AA_JSON_Template_Generic("HomeRisorseBoxTitle",array("view"=>"label","align"=>"center","label"=>"<span class='AA_Desktop_Section_Label'>Risorse</span>")));
 
        $template="<div style='width:100%;height:100%;display:flex;flex-direction:column;'>";
        $template.="<div><a href=\"#\" onclick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetHomeRngDlg'},'".$this->id."');\">Generatore estrazioni casuali</a></div>";
        if($this->oUser->IsSuperUser())
        {
            $template.="<div><a href=\"#\" onclick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetServerStatusDlg', taskManager: AA_MainApp.taskManager},'".$this->id."');\">Stato del server</a></div>";
        }
        $template.="</div>";

        $section_box_content=new AA_JSON_Template_Template("AA_ServerStatusLink",array("template"=>$template));
        $section_box->addRow($section_box_content);

        return $section_box;
    }


    //Template dlg trash utente
    public function Template_GetHomeUtentiTrashDlg($object=null)
    {
        $id=$this->id."_HomeUtentiTrash_Dlg";
        
        $form_data=array("id"=>$object->GetId());
        
        $wnd=new AA_GenericFormDlg($id, "Cestina/elimina utente", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("lastlogin"=>$object->GetLastLogin(),"login"=>$object->GetUsername(),"email"=>$object->GetEmail(),"nome"=>$object->GetNome(),"cognome"=>$object->GetCognome());
      
        $label="cestinato";
        if($object->GetStatus()==AA_User::AA_USER_STATUS_DELETED) $label="<b>eliminato definitivamente</b>";

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente utente verrà ".$label.", vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"login", "header"=>"Login", "width"=>120),
              array("id"=>"email", "header"=>"Email", "width"=>230),
              array("id"=>"nome", "header"=>"Nome", "fillspace"=>true),
              array("id"=>"cognome", "header"=>"Cognome", "fillspace"=>true),
              array("id"=>"lastlogin", "header"=>"Ultimo login", "width"=>120,"align"=>"center")
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("HomeUtentiTrash");
        
        return $wnd;
    }

    //Template dlg trash utente
    public function Template_GetHomeStructTrashDlg($object=null)
    {
        $id=$this->id."_HomeStructTrash_Dlg";
        
        $form_data=array("id"=>$object->GetProp('id'));
        $form_data["id_assessorato"]=$object->GetProp('id_assessorato');
        if($form_data["id_assessorato"]=="") $form_data["id_assessorato"]=0;
        $form_data["id_direzione"]=$object->GetProp('id_direzione');
        if($form_data["id_direzione"]=="") $form_data["id_direzione"]=0;
        
        $wnd=new AA_GenericFormDlg($id, "Elimina struttura", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(800);
        $wnd->SetHeight(480);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
        
        $space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        $tabledata=array();
        $tabledata[]=array("struttura"=>$object->getProp('descrizione'));
        if($object instanceof AA_Direzione)
        {
            $servizi=$object->GetServizi(true);
            foreach($servizi as $curServizio)
            {
                $tabledata[]=array("struttura"=>$space.$curServizio->getProp('descrizione'));
            }
        }

        if($object instanceof AA_Assessorato)
        {
            $direzioni=$object->GetDirezioni(true);
            foreach($direzioni as $curDirezione)
            {
                $tabledata[]=array("struttura"=>$space.$curDirezione->getProp('descrizione'));

                $servizi=$curDirezione->GetServizi(true);
                foreach($servizi as $curServizio)
                {
                    $tabledata[]=array("struttura"=>$space.$space.$curServizio->getProp('descrizione'));
                }
            }
        }

        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p></div>";
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));
        $wnd->AddGenericObject(new AA_JSON_Template_Template("",array("borderless"=>true,"autoheight"=>true,"template"=>"Le seguenti strutture <b>verranno eliminate definitivamente</b>, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"struttura", "header"=>"Strutture che verranno eliminate", "fillspace"=>true),
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("HomeStructDelete");
        
        return $wnd;
    }

    //Template dlg trash utente
    public function Template_GetHomeUtentiLegacyImportDlg()
    {
        $id=static::AA_UI_WND_IMPORT_UTENTI_LEGACY;
        
        $wnd=new AA_GenericWindowTemplate($id, "Importa utente legacy", $this->id);
        
        $wnd->SetWidth($_REQUEST['vw']);
        $wnd->SetHeight($_REQUEST['vh']);
        
        $wnd->AddView($this->Template_DatatableUtentiLegacy($id));
        
        return $wnd;
    }

    //Template data tabel utenti legacy
    public function Template_DatatableUtentiLegacy($id="")
    {
        $id.="_".static::AA_UI_TABLE_IMPORT_UTENTI_LEGACY;
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        $filter="";
        
        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if(isset($_REQUEST['id_assessorato']) && $_REQUEST['id_assessorato']>0) $filter="<span class='AA_Label AA_Label_LightOrange'>".$_REQUEST['struct_desc']."</span>&nbsp;";
            if(isset($_REQUEST['id_direzione']) && $_REQUEST['id_direzione']>0) $filter="<span class='AA_Label AA_Label_LightOrange'>".$_REQUEST['struct_desc']."</span>&nbsp;";
            if(isset($_REQUEST['id_servizio']) && $_REQUEST['id_servizio']>0) $filter="<span class='AA_Label AA_Label_LightOrange'>".$_REQUEST['struct_desc']."</span>&nbsp;";
        }

        if(isset($_REQUEST['status']) && $_REQUEST['status'] > -1)
        {
            if($_REQUEST['status']==1) $filter.="<span class='AA_Label AA_Label_LightOrange'>utenti abilitati</span>&nbsp;";
            if($_REQUEST['status']==0) $filter.="<span class='AA_Label AA_Label_LightOrange'>utenti disabilitati</span>&nbsp;";
        }

        if(isset($_REQUEST['ruolo']) && $_REQUEST['ruolo'] > -1)
        {
            if($_REQUEST['ruolo']==0) $filter.="<span class='AA_Label AA_Label_LightOrange'>solo amministratori</span>&nbsp;";
            if($_REQUEST['ruolo']==1) $filter.="<span class='AA_Label AA_Label_LightOrange'>solo operatori</span>&nbsp;";
        }

        //filtro username
        if(isset($_REQUEST['user']) && $_REQUEST['user'] !="")
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>username contiene: ".$_REQUEST['user']."</span>&nbsp;";
        }

        //filtro email
        if(isset($_REQUEST['email']) && $_REQUEST['email'] !="")
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>email contiene: ".$_REQUEST['email']."</span>&nbsp;";
        }

        if($filter=="") $filter="<span class='AA_Label AA_Label_LightOrange'>tutti</span>";
        
        $toolbar->addElement(new AA_JSON_Template_Generic($id."_FilterLabel",array("view"=>"label","align"=>"left","label"=>"<div>Visualizza: ".$filter."</div>")));
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //filtro
        $modify_btn=new AA_JSON_Template_Generic($id."_FilterUtenti_btn",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-filter-cog",
             "label"=>"Filtra",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Opzioni di filtraggio",
             "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetHomeUtentiLegacyFilterDlg\",postParams: module.getRuntimeValue('" . $id . "','filter_data'), module: \"" . $this->id . "\"},'".$this->id."')"
         ));
         $toolbar->AddElement($modify_btn);
        
        $layout->addRow($toolbar);

        $columns=array(
            array("id"=>"stato","header"=>array("<div style='text-align: center'>Stato</div>",array("content"=>"selectFilter")),"width"=>100, "sort"=>"text","css"=>array("text-align"=>"left")),
            array("id"=>"lastLogin","header"=>array("<div style='text-align: center'>Data Login</div>",array("content"=>"textFilter")),"width"=>120, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"user","header"=>array("<div style='text-align: center'>User</div>",array("content"=>"textFilter")),"width"=>200, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"email","header"=>array("<div style='text-align: center'>Email</div>",array("content"=>"textFilter")),"width"=>300, "css"=>array("text-align"=>"center"),"sort"=>"text"),            
            array("id"=>"denominazione","header"=>array("<div style='text-align: center'>Nome e cognome</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text"),
            array("id"=>"ruolo","header"=>array("<div style='text-align: center'>Ruolo</div>",array("content"=>"selectFilter")),"width"=>150, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"flags","header"=>array("<div style='text-align: center'>Abilitazioni</div>",array("content"=>"textFilter")), "fillspace"=>true,"css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"struttura","header"=>array("<div style='text-align: center'>Struttura</div>"), "width"=>90,"css"=>array("text-align"=>"center")),
            array("id"=>"ops","header"=>"<div style='text-align: center'>Operazioni</div>","width"=>120, "css"=>array("text-align"=>"center"))
        );
        
        $utenti=AA_User::LegacySearch($_REQUEST,$this->oUser);
        $data=array();
        if(sizeof($utenti) > 0)
        {
            foreach($utenti as $curUser)
            {
                $flags=$curUser->GetLabelFlags();
                
                if(!$curUser->IsDisabled()) $status="<span class='AA_Label AA_Label_LightGreen'>Abilitato</span>";
                else
                {
                    $status="<span class='AA_Label AA_Label_LightYellow'>Disabilitato</span>";
                }

                {
                    $import_op='AA_MainApp.utils.callHandler("ImportLegacyUser", {task:"HomeUtentiImport",refresh: 1,refresh_obj_id:"'.$id.'",params: [{id: "'.$curUser->GetId().'"}]},"'.$this->id.'");';
                    $ops="<div class='AA_DataTable_Ops'><span>&nbsp;</span><a class='AA_DataTable_Ops_Button' title='Importa utente' onClick='".$import_op."'><span class='mdi mdi-database-import'></span></a><span>&nbsp;</span></div>";
                }

                if(AA_Const::AA_ENABLE_LEGACY_DATA)
                {
                    $struct=$curUser->GetStruct();
                    $id_assessorato=$struct->GetAssessorato(true);
                    $id_direzione=$struct->GetDirezione(true);
                    $id_servizio=$struct->GetServizio(true);

                    $struttura=$struct->GetAssessorato();
                    if($id_direzione>0) $struttura.="<br>".$struct->GetDirezione();
                    if($id_servizio>0)$struttura.="<br>".$struct->GetServizio();
                    
                    $struct_view='<a href="#" onClick=\'let note=CryptoJS.enc.Utf8.stringify(CryptoJS.enc.Base64.parse("'.base64_encode($struttura).'"));AA_MainApp.ui.modalBox(note,"Struttura")\'><span class="mdi mdi-eye"></span></a>';
                    $data[]=array("id"=>$curUser->GetId(),"ops"=>$ops,"stato"=>$status,"lastLogin"=>$curUser->GetLastLogin(),"user"=>$curUser->GetUsername(),"email"=>$curUser->GetEmail(),"denominazione"=>$curUser->GetNome()." ".$curUser->GetCognome(),"ruolo"=>$curUser->GetRuolo(),"flags"=>$flags,
                        "id_assessorato"=>$id_assessorato,
                        "id_direzione"=>$id_direzione,
                        "id_servizio"=>$id_servizio,
                        "struttura"=>$struct_view
                    );
                }
                else $data[]=array("id"=>$curUser->GetId(),"ops"=>$ops,"lastLogin"=>$curUser->GetLastLogin(),"stato"=>$status,"user"=>$curUser->GetUsername(),"email"=>$curUser->GetEmail(),"denominazione"=>$curUser->GetNome()." ".$curUser->GetCognome(),"ruolo"=>$curUser->GetRuolo(),"flags"=>$flags);
            }
            $table=new AA_JSON_Template_Generic($id."_View", array(
                "view"=>"datatable",
                "scrollX"=>false,
                "select"=>false,
                "css"=>"AA_Header_DataTable",
                "hover"=>"AA_DataTable_Row_Hover",
                "columns"=>$columns,
                "data"=>$data
            ));
        }
        else
        {
            $table=new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Non sono presenti utenti legacy.</span></div>"));
        }

        $layout->AddRow($table);
        return $layout;
    }

    //Task object content (da specializzare)
    public function Task_GetObjectContent($task)
    {
        if($_REQUEST['object']==static::AA_UI_WND_IMPORT_UTENTI_LEGACY."_".static::AA_UI_TABLE_IMPORT_UTENTI_LEGACY)
        {
            $content = array("id" => static::AA_UI_WND_IMPORT_UTENTI_LEGACY."_".static::AA_UI_TABLE_IMPORT_UTENTI_LEGACY, "content" => $this->Template_DatatableUtentiLegacy(static::AA_UI_WND_IMPORT_UTENTI_LEGACY)->toArray());
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent(json_encode($content),true);
            return true;
        }

        return $this->Task_GetGenericObjectContent($task, $_REQUEST);
    }

    //Template cruscotto content
    public function TemplateSection_Desktop()
    {
        //AA_Log::Log(__METHOD__,100);
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_DESKTOP;
        $layout = new AA_JSON_Template_Layout($id,array("type"=>"clean","name" => static::AA_UI_SECTION_DESKTOP_NAME));

        $second_row=new AA_JSON_Template_Layout($id."_SecondRowBox",array("type"=>"space","css"=>array("background-color"=>"transparent")));
        $second_row->AddCol($this->TemplateSection_News());
        $second_row->AddCol($this->TemplateSection_Risorse());
        
        $layout->AddRow($second_row);

        $minCountModulesToCarousel=5;
        if(AA_Config::AA_ENABLE_LEGACY_DATA && AA_Config::AA_SHOW_LEGACY_MODULES_BOX)
        {
            $minCountModulesToCarousel=4;
        }

        $sso_auth_token=$this->oUser->GetSSOAuthToken();

        //Moduli Row
        $platform=AA_Platform::GetInstance($this->oUser);
        if($platform->IsValid())
        {
            $platform_modules=$platform->GetModules();
            if(sizeof($platform_modules) <=$minCountModulesToCarousel ) 
            {
                $moduli_box=new AA_JSON_Template_Layout($id."_ModuliBox",array("type"=>"clean","css"=>array("background-color"=>"transparent")));
            }
            else 
            {
                $moduli_box=new AA_JSON_Template_Carousel($id."_ModuliBox",array("type"=>"clean","css"=>array("background-color"=>"transparent")));
            }

            if(is_array($platform_modules) && sizeof($platform_modules) > 0)
            {
                $minHeightModuliItem=intval(($_REQUEST['vh']-180)/2);
                //$numModuliBoxForrow=intval(sqrt(sizeof($moduli_data)));
                $WidthModuliItem=intval(($_REQUEST['vw']-110)/4);
                //$HeightModuliItem=intval(/$numModuliBoxForrow);"css"=>"AA_DataView_Moduli_item","margin"=>10

                $riepilogo_template="<div class='AA_DataView_Moduli_item' onclick=\"#onclick#\" style='cursor: pointer; border: 1px solid; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 97%; margin:5px;'>";
                //icon
                $riepilogo_template.="<div style='display: flex; align-items: center; height: 120px; font-size: 90px;'><span class='#icon#'></span></div>";
                //name
                $riepilogo_template.="<div style='display: flex; align-items: center;justify-content: center; flex-direction: column; font-size: larger;height: 60px'>#name#</div>";
                //descr
                //$riepilogo_template.="<div style='display: flex; align-items: center;padding: 10px;height: 120px'><span>#descr#</span></div>";
                //go
                //$riepilogo_template.="<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 48px; padding: 5px'><a title='Apri il modulo' onclick=\"#onclick#\" class='AA_Button_Link'><span>Vai</span>&nbsp;<span class='mdi mdi-arrow-right-thick'></span></a></div>";
                $riepilogo_template.="</div>";

                $nSlide=0;
                $nMod=0;
                $moduli_view=new AA_JSON_Template_Layout($id."_ModuliView_".$nSlide,array("type"=>"clean","css"=>array("background-color"=>"transparent")));
                foreach($platform_modules as $curModId => $curMod)
                {
                    if($curModId != $this->GetId())
                    {
                        $nMod++;
                        //AA_Log::Log(__METHOD__." - Aggiungo la slide: ".$id."_ModuliView_".$nSlide." - nMod: ".$nMod ,100);

                        $name="<span style='font-weight:900;'>".implode("</span><span>",explode("-",$curMod['tooltip']))."</span>";
                        $onclick="AA_MainApp.utils.callHandler('ModuleBoxClick','".$curModId."','".$this->GetId()."')";
                        $moduli_data=array("id"=>$curModId,"name"=>$name,'descr'=>$curMod['descrizione'],"icon"=>$curMod['icon'],"onclick"=>$onclick);
                        $moduli_view->AddCol(new AA_JSON_Template_Template($id."_ModuleBox_".$moduli_data['id'],array("template"=>$riepilogo_template,"borderless"=>true,"data"=>array($moduli_data),"eventHandlers"=>array("onItemClick"=>array("handler"=>"ModuleBoxClick","module_id"=>$this->GetId())))));
                        
                        if($nMod%4==0)
                        {
                            if(sizeof($platform_modules) <=$minCountModulesToCarousel) $moduli_box->AddRow($moduli_view);
                            else $moduli_box->AddSlide($moduli_view);
                            $nSlide++;
                            $moduli_view=new AA_JSON_Template_Layout($id."_ModuliView_".$nSlide,array("type"=>"clean","css"=>array("background-color"=>"transparent")));
                        }
                    }
                }

                if(AA_Config::AA_ENABLE_LEGACY_DATA && AA_Config::AA_SHOW_LEGACY_MODULES_BOX)
                {
                    if($nMod%4==0 && $nMod > 0)
                    {
                        if(sizeof($platform_modules) <= $minCountModulesToCarousel) $moduli_box->AddRow($moduli_view);
                        else $moduli_box->AddSlide($moduli_view);
                        $moduli_view=new AA_JSON_Template_Layout($id."_ModuliView_Legacy",array("type"=>"clean","css"=>array("background-color"=>"transparent")));
                    }
                    $nMod++;

                    $riepilogo_template="<div class='AA_DataView_Moduli_item' onclick=\"#onclick#\" style='cursor: pointer; border: 1px solid; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 97%; margin:5px;'>";
                    //icon
                    $riepilogo_template.="<div style='display: flex; align-items: center; height: 120px; font-size: 90px;'><span class='#icon#'></span></div>";
                    //name
                    $riepilogo_template.="<div style='display: flex; align-items: center;justify-content: center; flex-direction: column; font-size: larger;height: 60px'>#name#</div>";
                    //descr
                    $riepilogo_template.="<div style='display: flex; align-items: center; flex-direction: column; justify-content: space-between; padding: 10px;'>#descr#</div>";
                    //go
                    //$riepilogo_template.="<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 48px; padding: 5px'><a title='Apri il modulo' onclick=\"#onclick#\" class='AA_Button_Link'><span>Vai</span>&nbsp;<span class='mdi mdi-arrow-right-thick'></span></a></div>";
                    $riepilogo_template.="</div>";

                    $name="<span style='font-weight:900;'>ALTRI MODULI APPLICATIVI</span>";
                    $descr="<span>Registro accessi</span><span>Mappatura processi</span><span>Gestione incarichi</span><span>Contributi e vantaggi economici</span><span>Bandi di gara e contratti</span>";
                    $onclick="AA_MainApp.utils.callHandler('ModuleLegacyBoxClick',{'SSO_AUTH_TOKEN':'".$sso_auth_token."','url':'/web/amministrazione_aperta/admin'},'".$this->GetId()."')";
                    $moduli_data=array("id"=>"Legacy_Modules","name"=>$name,'descr'=>$descr,"icon"=>"mdi mdi-table","onclick"=>$onclick);
                    $moduli_view->AddCol(new AA_JSON_Template_Template($id."_ModuleBox_".$moduli_data['id'],array("template"=>$riepilogo_template,"borderless"=>true,"data"=>array($moduli_data),"eventHandlers"=>array("onItemClick"=>array("handler"=>"ModuleBoxClick","module_id"=>$this->GetId())))));
                }

                //AA_Log::Log(__METHOD__." - nMod: ".$nMod. " - %: ".$nMod%4,100);
                if($nMod%4 || $nMod < 4)
                {
                    //AA_Log::Log(__METHOD__." - Aggiungo la slide: ".$id."_ModuliView_".$nSlide,100);
                    $i=$nMod;
                    if($nMod > 4) $i=$nMod%4;
                    for($i;$i < 4;$i++)
                    {
                        $moduli_view->addCol(new AA_JSON_Template_Generic());
                    }
                }
                if(sizeof($platform_modules) <= $minCountModulesToCarousel) 
                {
                    $moduli_box->AddRow($moduli_view);
                }
                else 
                {
                    $moduli_box->AddSlide($moduli_view);
                }
            }
            else
            {
                if(AA_Config::AA_ENABLE_LEGACY_DATA && AA_Config::AA_SHOW_LEGACY_MODULES_BOX)
                {

                    $moduli_view=new AA_JSON_Template_Layout($id."_ModuliView_Legacy",array("type"=>"clean","css"=>array("background-color"=>"transparent")));
                    
                    $riepilogo_template="<div class='AA_DataView_Moduli_item' onclick=\"#onclick#\" style='cursor: pointer; border: 1px solid; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 97%; margin:5px;'>";
                    //icon
                    $riepilogo_template.="<div style='display: flex; align-items: center; height: 120px; font-size: 90px;'><span class='#icon#'></span></div>";
                    //name
                    $riepilogo_template.="<div style='display: flex; align-items: center;justify-content: center; flex-direction: column; font-size: larger;height: 60px'>#name#</div>";
                    //descr
                    $riepilogo_template.="<div style='display: flex; align-items: center; flex-direction: column; justify-content: space-between; padding: 10px;'>#descr#</div>";
                    //go
                    //$riepilogo_template.="<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 48px; padding: 5px'><a title='Apri il modulo' onclick=\"#onclick#\" class='AA_Button_Link'><span>Vai</span>&nbsp;<span class='mdi mdi-arrow-right-thick'></span></a></div>";
                    $riepilogo_template.="</div>";

                    $name="<span style='font-weight:900;'>ALTRI MODULI APPLICATIVI</span>";
                    $descr="<span>Registro accessi</span><span>Mappatura processi</span><span>Gestione incarichi</span><span>Contributi e vantaggi economici</span><span>Bandi di gara e contratti</span>";
                    $onclick="AA_MainApp.utils.callHandler('ModuleLegacyBoxClick',{'SSO_AUTH_TOKEN':'".$sso_auth_token."','url':'/web/amministrazione_aperta/admin'},'".$this->GetId()."')";
                    $moduli_data=array("id"=>"Legacy_Modules","name"=>$name,'descr'=>$descr,"icon"=>"mdi mdi-table","onclick"=>$onclick);
                    $moduli_view->AddCol(new AA_JSON_Template_Template($id."_ModuleBox_".$moduli_data['id'],array("template"=>$riepilogo_template,"borderless"=>true,"data"=>array($moduli_data),"eventHandlers"=>array("onItemClick"=>array("handler"=>"ModuleBoxClick","module_id"=>$this->GetId())))));
                }
                else
                {
                    $moduli_view=new AA_JSON_Template_Template($id."_Riepilogo_Tab",array("template"=>"<div style='display: flex; justify-content: center; align-items: center; width: 100%;height:100%'><div>Non sono presenti elementi.</div></div>"));
                }

                $moduli_box->AddRow($moduli_view);
            }
          
            $layout->AddRow($moduli_box);
        }
        
        return $layout;
    }

    //Template gestutenti content
    public function TemplateSection_GestUtenti()
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GESTUTENTI;

        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean","name" => static::AA_UI_SECTION_GESTUTENTI_NAME,"filtered"=>true));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        $filter="";
        
        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if(isset($_REQUEST['id_assessorato']) && $_REQUEST['id_assessorato']>0) $filter="<span class='AA_Label AA_Label_LightOrange'>".$_REQUEST['struct_desc']."</span>&nbsp;";
            if(isset($_REQUEST['id_direzione']) && $_REQUEST['id_direzione']>0) $filter="<span class='AA_Label AA_Label_LightOrange'>".$_REQUEST['struct_desc']."</span>&nbsp;";
            if(isset($_REQUEST['id_servizio']) && $_REQUEST['id_servizio']>0) $filter="<span class='AA_Label AA_Label_LightOrange'>".$_REQUEST['struct_desc']."</span>&nbsp;";
        }

        if(isset($_REQUEST['status']) && $_REQUEST['status'] > -2)
        {
            if($_REQUEST['status']==AA_User::AA_USER_STATUS_ENABLED) $filter.="<span class='AA_Label AA_Label_LightOrange'>utenti abilitati</span>&nbsp;";
            if($_REQUEST['status']==AA_User::AA_USER_STATUS_DISABLED) $filter.="<span class='AA_Label AA_Label_LightOrange'>utenti disabilitati</span>&nbsp;";
            if($_REQUEST['status']==AA_User::AA_USER_STATUS_DELETED) $filter.="<span class='AA_Label AA_Label_LightOrange'>utenti cestinati</span>&nbsp;";
        }

        if(isset($_REQUEST['ruolo']) && $_REQUEST['ruolo'] > 0)
        {
            if($_REQUEST['ruolo']==AA_User::AA_USER_GROUP_SUPERUSER) $filter.="<span class='AA_Label AA_Label_LightOrange'>solo super utenti</span>&nbsp;";
            if($_REQUEST['ruolo']==AA_User::AA_USER_GROUP_SERVEROPERATORS) $filter.="<span class='AA_Label AA_Label_LightOrange'>solo operatori server</span>&nbsp;";
            if($_REQUEST['ruolo']==AA_User::AA_USER_GROUP_ADMINS) $filter.="<span class='AA_Label AA_Label_LightOrange'>solo amministratori</span>&nbsp;";
            if($_REQUEST['ruolo']==AA_User::AA_USER_GROUP_OPERATORS) $filter.="<span class='AA_Label AA_Label_LightOrange'>solo operatori</span>&nbsp;";
            if($_REQUEST['ruolo']==AA_User::AA_USER_GROUP_USERS) $filter.="<span class='AA_Label AA_Label_LightOrange'>solo utenti</span>&nbsp;";
        }

        //filtro username
        if(isset($_REQUEST['user']) && $_REQUEST['user'] !="")
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>username contiene: ".$_REQUEST['user']."</span>&nbsp;";
        }

        //filtro email
        if(isset($_REQUEST['email']) && $_REQUEST['email'] !="")
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>email contiene: ".$_REQUEST['email']."</span>&nbsp;";
        }

        if($filter=="") $filter="<span class='AA_Label AA_Label_LightOrange'>tutti</span>";
        
        $toolbar->addElement(new AA_JSON_Template_Generic($id."_FilterLabel",array("view"=>"label","align"=>"left","label"=>"<div>Visualizza: ".$filter."</div>")));
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //filtro
        $modify_btn=new AA_JSON_Template_Generic($id."_FilterUtenti_btn",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-filter-cog",
             "label"=>"Filtra",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Opzioni di filtraggio",
             "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetHomeUtentiFilterDlg\",postParams: module.getRuntimeValue('" . $id . "','filter_data'), module: \"" . $this->id . "\"},'".$this->id."')"
         ));
         $toolbar->AddElement($modify_btn);

        //pulsante di importazione utenti legacy (solo super user)
        if($this->oUser->IsSuperUser() && AA_Const::AA_ENABLE_LEGACY_DATA)
        {            
            $modify_btn=new AA_JSON_Template_Generic($id."_ImportLegacy_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-application-import",
                "label"=>"Importa",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Importa utenti legacy",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetHomeUtentiLegacyImportDlg\"},'".$this->id."');$$('".$id."_ImportLegacy_btn').disable();setTimeout(function(){ $$('".$id."_ImportLegacy_btn').enable();},5000);"
            ));
            $toolbar->AddElement($modify_btn);
        }

        //Pulsante di modifica
        $canModify=false;
        if($this->oUser->CanGestUtenti()) $canModify=true;
        if($canModify)
        {            
            $modify_btn=new AA_JSON_Template_Generic($id."_AddNew_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-account-plus",
                "label"=>"Aggiungi",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi un nuovo utente",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetHomeUtentiAddNewDlg\"},'".$this->id."')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        
        $layout->addRow($toolbar);        
        $columns=array(
            array("id"=>"stato","header"=>array("<div style='text-align: center'>Stato</div>",array("content"=>"selectFilter")),"width"=>100, "sort"=>"text","css"=>array("text-align"=>"left")),
            array("id"=>"lastLogin","header"=>array("<div style='text-align: center'>Data Login</div>",array("content"=>"textFilter")),"width"=>120, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"user","header"=>array("<div style='text-align: center'>Nome profilo</div>",array("content"=>"textFilter")),"width"=>200, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"email","header"=>array("<div style='text-align: center'>Email</div>",array("content"=>"textFilter")),"width"=>300, "css"=>array("text-align"=>"center"),"sort"=>"text"),            
            array("id"=>"denominazione","header"=>array("<div style='text-align: center'>Cognome e nome</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text"),
            array("id"=>"ruolo","header"=>array("<div style='text-align: center'>Ruolo</div>",array("content"=>"selectFilter")),"width"=>150, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"flags","header"=>array("<div style='text-align: center'>Abilitazioni</div>",array("content"=>"textFilter")), "fillspace"=>true,"css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"struttura","header"=>array("<div style='text-align: center'>Struttura</div>"), "width"=>90,"css"=>array("text-align"=>"center"))
        );

        if($canModify)
        {
            $columns[]=array("id"=>"ops","header"=>"<div style='text-align: center'>Operazioni</div>","width"=>120, "css"=>array("text-align"=>"center"));
        }

        $utenti=AA_User::Search($_REQUEST,$this->oUser);
        $data=array();
        if(sizeof($utenti) > 0)
        {
            foreach($utenti as $curUser)
            {
                $flags=$curUser->GetLabelFlags();
                $status=$curUser->GetStatus();
                $trash=true;
                $tip_trash="Cestina";
                $icon_trash="mdi mdi-trash-can";

                if($status==AA_User::AA_USER_STATUS_DELETED)
                {
                    $trash=false;
                    $tip_trash="Elimina definitivamente";
                    $icon_trash="mdi mdi-account-remove";
                }

                if($status==AA_User::AA_USER_STATUS_ENABLED) $status="<span class='AA_Label AA_Label_LightGreen'>Abilitato</span>";
                else
                {
                    if($status==AA_User::AA_USER_STATUS_DISABLED) $status="<span class='AA_Label AA_Label_LightYellow'>Disabilitato</span>";
                    else $status="<span class='AA_Label AA_Label_LightRed'>Cestinato</span>";
                }

                if($canModify)
                {
                    $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetHomeUtentiModifyDlg", params: [{id: "'.$curUser->GetId().'"}]},"'.$this->id.'")';
                    $send='AA_MainApp.utils.callHandler("dlg", {task:"HomeUtentiConfirmSendCredenzials", params: [{id: "'.$curUser->GetId().'"}]},"'.$this->id.'")';
                    $trash_op='AA_MainApp.utils.callHandler("dlg", {task:"GetHomeUtentiTrashDlg", params: [{id: "'.$curUser->GetId().'"}]},"'.$this->id.'")';
                    $resume_op='AA_MainApp.utils.callHandler("doTask", {task:"HomeUtentiResume", params: [{id: "'.$curUser->GetId().'"}]},"'.$this->id.'");AA_MainApp.curModule.refreshCurSection();';
                    if($trash)
                    {
                        $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Invia credenziali' onClick='".$send."'><span class='mdi mdi-email-fast'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='".$tip_trash."' onClick='".$trash_op."'><span class='".$icon_trash."'></span></a></div>";
                    }
                    else
                    {
                        $ops="<div class='AA_DataTable_Ops'><span>&nbsp;</span><a class='AA_DataTable_Ops_Button' title='Ripristina l&apos;utente' onClick='".$resume_op."'><span class='mdi mdi-account-reactivate'></span></a><a class='AA_DataTable_Ops_Button_Red' title='".$tip_trash."' onClick='".$trash_op."'><span class='".$icon_trash."'></span></a></div>";
                    }
                }
                else $ops="&nbsp;";

                if(AA_Const::AA_ENABLE_LEGACY_DATA)
                {
                    $struct=$curUser->GetStruct();
                    $id_assessorato=$struct->GetAssessorato(true);
                    $id_direzione=$struct->GetDirezione(true);
                    $id_servizio=$struct->GetServizio(true);

                    $struttura=$struct->GetAssessorato();
                    if($id_direzione>0) $struttura.="<br>".$struct->GetDirezione();
                    if($id_servizio>0)$struttura.="<br>".$struct->GetServizio();
                    
                    $struct_view='<a href="#" onClick=\'let note=CryptoJS.enc.Utf8.stringify(CryptoJS.enc.Base64.parse("'.base64_encode($struttura).'"));AA_MainApp.ui.modalBox(note,"Struttura")\'><span class="mdi mdi-eye"></span></a>';
                    if($id_servizio == 0 && $curUser->GetRuolo(true)==AA_User::AA_USER_GROUP_ADMINS) $struct_view='<a href="#" onClick=\'let note=CryptoJS.enc.Utf8.stringify(CryptoJS.enc.Base64.parse("'.base64_encode($struttura).'"));AA_MainApp.ui.modalBox(note,"Struttura")\'><span class="mdi mdi-account-cowboy-hat"></span></a>';
                    $data[]=array("id"=>$curUser->GetId(),"ops"=>$ops,"stato"=>$status,"lastLogin"=>$curUser->GetLastLogin(),"user"=>$curUser->GetUsername(),"email"=>$curUser->GetEmail(),"denominazione"=>$curUser->Getcognome()." ".$curUser->GetNome(),"ruolo"=>$curUser->GetRuolo(),"flags"=>$flags,
                        "id_assessorato"=>$id_assessorato,
                        "id_direzione"=>$id_direzione,
                        "id_servizio"=>$id_servizio,
                        "struttura"=>$struct_view
                    );
                }
                else $data[]=array("id"=>$curUser->GetId(),"ops"=>$ops,"lastLogin"=>$curUser->GetLastLogin(),"stato"=>$status,"user"=>$curUser->GetUsername(),"email"=>$curUser->GetEmail(),"denominazione"=>$curUser->GetCognome()." ".$curUser->GetNome(),"ruolo"=>$curUser->GetRuolo(),"flags"=>$flags);
            }
            $table=new AA_JSON_Template_Generic($id."_UtentiTable", array(
                "view"=>"datatable",
                "scrollX"=>false,
                "select"=>false,
                "css"=>"AA_Header_DataTable",
                "hover"=>"AA_DataTable_Row_Hover",
                "columns"=>$columns,
                "data"=>$data
            ));
    
            $layout->addRow($table);
        }
        else
        {
            $layout->addRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Non sono presenti utenti.</span></div>")));
        }

        return $layout;
    }

    //Template filtro di ricerca utenti
    public function TemplateHomeUtentiFilterDlg()
    {
        //Valori runtime
        $formData=array("status"=>$_REQUEST['status'],"ruolo"=>$_REQUEST['ruolo'],"user"=>$_REQUEST['user'],"email"=>$_REQUEST['email']);
        if(AA_const::AA_ENABLE_LEGACY_DATA)
        {
            $formData['id_assessorato']=$_REQUEST['id_assessorato'];
            $formData['id_direzione']=$_REQUEST['id_direzione'];
            $formData['id_servizio']=$_REQUEST['id_servizio'];
            $formData['struct_desc']=$_REQUEST['struct_desc'];
            $formData['id_struct_tree_select']=$_REQUEST['id_struct_tree_select'];
            if($formData['id_struct_tree_select'] == "")
            {
                if($formData['id_assessorato']>0) $form_data['id_struct_tree_select']=$formData['id_assessorato'];
                if($formData['id_direzione']>0) $form_data['id_struct_tree_select'].=".".$formData['id_direzione'];
                if($formData['id_servizio']>0) $form_data['id_struct_tree_select'].=".".$formData['id_servizio'];
            }

            if($_REQUEST['struct_desc']=="") $formData['struct_desc']="Qualunque";
            if($_REQUEST['id_assessorato']=="") $formData['id_assessorato']=0;
            if($_REQUEST['id_direzione']=="") $formData['id_direzione']=0;
            if($_REQUEST['id_servizio']=="") $formData['id_servizio']=0;
        }

        if(!isset($_REQUEST['ruolo'])) $formData['ruolo']=0;
        if(!isset($_REQUEST['status'])) $formData['status']=-2;
                
        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","status"=>-2,"ruolo"=>0,"email"=>"","user"=>"");
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="AA_MainApp.curModule.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Utenti_Filter", "Parametri di filtraggio",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(480);
        
        //nome utente
        $dlg->AddTextField("user","Profilo utente",array("bottomLabel"=>"*Filtra in base al profilo utente.", "placeholder"=>"..."));

        //email
        $dlg->AddTextField("email","Email",array("bottomLabel"=>"*Filtra in base alla email.", "placeholder"=>"..."));

        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura di incardinamento."));
        
        //Ruolo
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        if($this->oUser->IsSuperUser()) 
        {
            $options[]=array("id"=>AA_User::AA_USER_GROUP_SUPERUSER,"value"=>"Super utente");
            $options[]=array("id"=>AA_User::AA_USER_GROUP_ADMINS,"value"=>"Amministratore");
            $options[]=array("id"=>AA_User::AA_USER_GROUP_OPERATORS,"value"=>"Operatore");
            $options[]=array("id"=>AA_User::AA_USER_GROUP_USERS,"value"=>"Utente");
            $options[]=array("id"=>AA_User::AA_USER_GROUP_SERVEROPERATORS,"value"=>"Operatori server");
        }
        else
        {
            if($this->oUser->GetRuolo(true) == AA_User::AA_USER_GROUP_SERVEROPERATORS) 
            {
                $options[]=array("id"=>AA_User::AA_USER_GROUP_ADMINS,"value"=>"Amministratore");
                $options[]=array("id"=>AA_User::AA_USER_GROUP_OPERATORS,"value"=>"Operatore");
                $options[]=array("id"=>AA_User::AA_USER_GROUP_USERS,"value"=>"Utente");
                //$options[]=array("id"=>AA_User::AA_USER_GROUP_SERVEROPERATORS,"value"=>"Operatori server");
            }
            else
            {
                //$options[]=array("id"=>AA_User::AA_USER_GROUP_ADMINS,"value"=>"Amministratore");
                $options[]=array("id"=>AA_User::AA_USER_GROUP_OPERATORS,"value"=>"Operatore");
                $options[]=array("id"=>AA_User::AA_USER_GROUP_USERS,"value"=>"Utente");
            }
        }

        $dlg->AddSelectField("ruolo","Ruolo",array("bottomLabel"=>"*Filtra in base al ruolo dell'utente.","options"=>$options));
        
        //stato utente
        $options=array(array("id"=>"-2","value"=>"Qualunque"));
        $options[]=array("id"=>"1","value"=>"Abilitato");
        $options[]=array("id"=>"0","value"=>"Disabilitato");
        if($this->oUser->IsSuperUser() || $this->oUser->GetRuolo()==AA_User::AA_USER_GROUP_SERVEROPERATORS)
        {
            $options[]=array("id"=>"-1","value"=>"Cestinato");
        }
        $dlg->AddSelectField("status","Stato",array("bottomLabel"=>"*Filtra in base allo stato dell'utente.","options"=>$options));

        $dlg->SetApplyButtonName("Filtra");
        $dlg->EnableApplyHotkey();

        return $dlg->GetObject();
    }

    //Template rng dlg
    public function Template_GetHomeRngDlg()
    {
        //Valori runtime
        $formData=array("start"=>$_REQUEST['start'],"end"=>$_REQUEST['end'],"count"=>$_REQUEST['count']);

        if($formData['start']=="") $formData['start']=1;
        if($formData['end']=="") $formData['end']=100;
        if($formData['count']=="") $formData['count']=1;
        //Valori reset
        $resetData=array("start"=>1,"end"=>100,"count"=>1);
        
        $dlg = new AA_GenericFormDlg(static::AA_UI_PREFIX."_RngDlg", "Estrattore numeri casuali",$this->GetId(),$formData,$resetData);
        
        $dlg->SetHeight(480);
        $dlg->SetWidth(450);
        
        //start
        $dlg->AddTextField("start","Numero minimo",array("required"=>true,"validateFunction"=>"IsNumber","bottomLabel"=>"*Inserisci il numero piu' piccolo."));
        
        //end
        $dlg->AddTextField("end","Numero massimo",array("required"=>true,"validateFunction"=>"IsNumber","bottomLabel"=>"*Inserisci il numero piu' grande."));

        //count
        $dlg->AddTextField("count","Numero di estrazioni",array("required"=>true,"validateFunction"=>"IsNumber","bottomLabel"=>"*Inserisci il numero di estrazioni."));

        $dlg->SetApplyButtonName("Estrai");
        $dlg->enableRefreshOnSuccessfulSave(false);
        $dlg->EnableValidation();
        $dlg->SetLabelWidth(190);
        $dlg->SetSaveTask("HomeRngOut");
        $dlg->EnableApplyHotkey();

        return $dlg;
    }

    //Template pdf rendicontazione
    protected function Template_HomeRngOutPdf($start=1,$end=100,$numCount=1,$bToBrowser=true)
    {
        include_once "pdf_lib.php";

        $count = 1;

        $rowsForPage=1;
        $vociIndice=array(
            0=>"Estrazione"
        );

        //nome file
        $filename = "estrazione";
        $filename .= "-" . date("YmdHis");
        $doc = new AA_PDF_RAS_TEMPLATE_A4_PORTRAIT($filename);

        $doc->SetHeaderHeight("25mm");
        $doc->SetDocumentStyle("font-family: sans-serif; font-size: 3mm;");
        $doc->SetPageCorpoStyle("display: flex; flex-direction: column; justify-content: space-between; padding:0;");
        $curRow = 0;
        $rowForPage = $rowsForPage;
        $lastRow = $rowForPage - 1;
        $curPage = null;
        $curPage_row = "";
        $curNumPage = 0;
        $maxItemHeight=intval(100/$rowsForPage);
        //$columns_width=array("titolare"=>"10%","incarico"=>"8%","atto"=>"10%","struttura"=>"28%","curriculum"=>"10%","art20"=>"12%","altri_incarichi"=>"10%","1-ter"=>"10%","emolumenti"=>"10%");
        //$columns_width=array("dal"=>"10%","al"=>"10%","inconf"=>"10%","incomp"=>"10%","anno"=>"25%","titolare"=>"50%","tipo_incarico"=>"10%","atto_nomina"=>"10%","struttura"=>"40%","curriculum"=>"25%","altri_incarichi"=>"25%","1-ter"=>"25%","emolumenti"=>"10%");
        $rowContentWidth = "width: 99.8%;";

        if ($count > 1) 
        {
            //pagina di intestazione (senza titolo)
            $curPage = $doc->AddPage();
            $curPage->SetCorpoStyle("display: flex; flex-direction: column; justify-content: center; align-items: center; padding:0;");
            $curPage->SetFooterStyle("border-top:.2mm solid black");
            $curPage->ShowPageNumber(false);

            //Intestazione
            $intestazione = "<div style='width: 100%; text-align: center; font-size: 28; font-weight: bold; margin-bottom: 2em;'>Titolo</div>";
            $intestazione .= "<div style='width: 100%; text-align: center; font-size: x-small; font-weight: normal;margin-top: 3em;'>documento generato il " . date("Y-m-d") . "</div>";
            
            $curPage->EnableFooter(true);
            $curPage->SetFooterContent("<div style='width: 100%; text-align: center; font-weight: normal;font-size:smaller'>seriale: ".$serial." </div>");
            $curPage->SetContent($intestazione);
            $curNumPage++;

            $doc->SetTitle("<div style='display:flex;justify-content:space-around;align-items:center;flex-direction:column; height:70%;width:100%;padding-top: 1em'><span style='font-size:18px'>Comune di ".$comune->GetProp("denominazione")."</span><hr style='width:25%'><div><span>".$title."</span><br><span style='font-weight:normal; font-size:smaller'>" . $object->GetName()."</span></div></div>");
            if($index)
            {
                //pagine indice (50 nominativi per pagina)
                $indiceNumVociPerPagina = 20;
                for ($i = 0; $i < $count / $indiceNumVociPerPagina; $i++) 
                {
                    $curPage = $doc->AddPage();
                    $curPage->SetCorpoStyle("display: flex; flex-direction: column; padding:0;");
                    $curPage->SetFooterContent("<div style='width: 100%; text-align: center; font-weight: normal;font-size:smaller'>seriale: ".$serial." - documento generato il " . date("Y-m-d")."</div>");
                    $curNumPage++;
                }
            }
            $curPage=null;
            #---------------------------------------
        }

        //Imposta il titolo per le pagine successive
        
        //$doc->SetTitle("$subTitle - report generato il " . date("Y-m-d"));

        $indice = array();
        $lastPage = $count / $rowForPage + $curNumPage;

        //Rendering pagine
        for($i=0;$i<$count;$i++)
        {
            //inizia una nuova pagina (intestazione)
            if ($curRow == $rowForPage) $curRow = 0;
            if ($curRow == 0) {
                $border = "";
                if ($curPage != null) 
                {
                    if($curPage_row !="")
                    {
                        $curPage->SetContent($curPage_row);
                        $curPage = $doc->AddPage();
                        $curNumPage++;
                        $curPage_row="";
                    }
                }
                else 
                {
                    $curPage_row="";
                    $curPage = $doc->AddPage();
                    $curNumPage++;
                }
                
                $curPage->SetCorpoStyle("display: flex; flex-direction: column;  justify-content: flex-start; padding:0;");
                $curPage_row = "";
            }

            if($i<3) $indice[$i] = $curNumPage . "|" . $vociIndice[$i];
            if($i==4) $indice[$i] = $curNumPage . "|" . $vociIndice[$i];
            if($i==(4+$numPagineRendicontiAssunzioni)) $indice[$i] = $curNumPage . "|" . $vociIndice[$i];
            if($i==(4+$numPagineRendicontiAssunzioni+1)) $indice[$i] = $curNumPage . "|" . $vociIndice[$i];

            $curPage_row .= "<div id='".$i."' style='display:flex;  flex-direction: column; width: 99.8%; height:100%; align-items: center; text-align: center; padding: 0mm; margin-top: 2mm; min-height: 9mm; max-height:".$maxItemHeight."%; overflow: hidden;'>";

            //---------------------------------------- Estrazione ----------------------------------------------
            if($i==0)
            {
                $curPage_row.= "<div style='width: 100%; font-weight:bold; font-size:18px'>Estrazione numeri casuali</div>";
                $curPage_row.=$this->Template_RngEstrazionePage($start,$end,$numCount);
            }
            //-------------------------------------------------------------------------------------------------

            $curPage_row .= "</div>";
            $curPage->SetFooterContent("<div style='width: 100%; text-align: center; font-weight: normal;font-size:smaller'>documento generato il " . date("Y-m-d")."</div>");
            $curRow++;
        }
        if ($curPage != null) $curPage->SetContent($curPage_row);
        #-----------------------------------------

        if ($count > 1 && $index) 
        {
            //Aggiornamento indice
            $curNumPage = 1;
            $curPage = $doc->GetPage($curNumPage);
            $vociCount = 0;
            $curRow = 0;
            $bgColor = "";
            $curPage_row = "";

            foreach ($indice as $id => $data) 
            {
                if ($curNumPage != (int)($vociCount / $indiceNumVociPerPagina) + 1) {
                    $curPage->SetContent($curPage_row);
                    $curNumPage = (int)($vociCount / $indiceNumVociPerPagina) + 1;
                    $curPage = $doc->GetPage($curNumPage);
                    $curRow = 0;
                    $bgColor = "";
                }

                //$indexBgColor = "#f5f5f5";
                $indexBgColor = "#fff";
                if ($curPage instanceof AA_PDF_Page) 
                {
                    //Intestazione
                    if ($curRow == 0) $curPage_row = "<div style='width:100%;text-align: center; font-size: larger; font-weight: bold; border-bottom: 1px solid #dedede; margin-bottom: .5em; margin-top: .3em;'>Indice</div>";
                    if ($curRow % 2) $bgColor = "background-color:$indexBgColor;";
                    else $bgColor = "";
                    $curPage_row .= "<div style='display:flex; " . $rowContentWidth . " align-items: center; justify-content: space-between; font-size:larger; padding: .3mm; min-height: 9mm;" . $bgColor . "'>";
                    $dati = explode("|", $data);
                    $curPage_row .= "<div style='width:90%;text-align: left;padding-left: 10mm'><a style='text-decoration: none' href='#" . $id . "'>" . $dati['1'] . "</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a style='text-decoration: none' href='#" . $id . "'>pag. " . $dati[0] . "</a></div>";
                    $curPage_row .= "</div>";

                    //ultima voce
                    if ($vociCount == (sizeof($indice) - 1)) 
                    {
                        $curPage->SetContent($curPage_row);
                    }
                    $curRow++;
                }

                $vociCount++;
            }
        }

        if ($bToBrowser) $doc->Render();
        else {
            $doc->Render(false);
            return $doc->GetFilePath();
        }
    }

    public function Template_RngEstrazionePage($start=1,$end=100,$count=1)
    {
        $layout=new AA_XML_Div_Element(uniqid());
        $layout->SetStyle("width:99%; height:100%;");

        if($start<0) $start=0;
        if($end<0 || $end<=$start) $end=$start+100;
        if($count<=0) $count=1;
        
        if($count>($end-$start)/2) $count=1;
        
        $val="<ul>Si attesta che:";
        $val.="<li>Ogni numero puo' essere estratto una sola volta.</li>";
        if($count>1) $val.="<li>Sono stati estratti ".$count." numeri diversi.</li>";
        $val.="<li>I numeri estratti appartengono all'intervallo: [".$start."-".$end."] estremi compresi.</li>";
        $val.="<li>L'estrazione e' stata effettuata in data: ".date("d-m-Y")." alle ".date("H:i").".</li>";
        $val.="</ul>";
        $attestazione=new AA_XML_Div_Element(uniqid(),$layout);
        $attestazione->SetStyle("text-align:justify;");
        $attestazione->SetText($val);

        $row_template="<div style='display:flex; width: 96.8%;flex-direction:column; padding: .5em;'>";
        $row_template.="<div style='width:100%;font-weight: bold; margin-bottom: .2em;'>#label#</div><div style='width:100%'>#value#</div>";
        $row_template.="</div>";

        $row_template_2col="<div style='display:flex; width: 96.8%;'>";
        $row_template_2col.="<div style='display:flex; width: 50%;min-width:50%; flex-direction:column; padding: .5em;'>";
        $row_template_2col.="<div style='width:100%;font-weight: bold;margin-bottom: .2em;'>#label#</div><div style='width:100%'>#value#</div>";
        $row_template_2col.="</div>";
        $row_template_2col.="<div style='display:flex; width: 49%;flex-direction:column; border-left:1px solid #d7dbdd; padding: .5em;'>";
        $row_template_2col.="<div style='width:100%;font-weight: bold;margin-bottom: .2em;'>#label_2#</div><div style='width:100%'>#value_2#</div>";
        $row_template_2col.="</div>";
        $row_template_2col.="</div>";

        $template= new AA_GenericTableTemplateView(uniqid(),$layout,null,array("evidentiate-rows"=>true,"title"=>"","default-border-color"=>"#d7dbdd","h_bgcolor"=>"#d7dbdd","border"=>"1px solid #d7dbdd;","width"=>"99.7%","style"=>"margin-bottom: 1em; margin-top: 1em"));
        $template->SetColSizes(array("100"));
        //$template->SetCellPadding("10px");
        $titolo="Numero estratto";
        if($count>1) $titolo="Numeri estratti";
        $template->SetHeaderLabels(array("<span style='font-weight:bold;line-height:18px;'>".$titolo."</span>"));
        $curRow=1;

        $rngExtract=array();
        while(sizeof($rngExtract)<$count)
        {
            $num=rand($start,$end);
            while(array_search($num,$rngExtract) !==false)
            {
                $num=rand($start,$end);
            }

            $rngExtract[]=$num;
        }

        $content="<div style='display:flex;justify-content:space-around'>";
        foreach($rngExtract as $curVal)
        {
            if($content=="") $content.="<span>".$curVal."</span>";
            else $content.="<span>".$curVal."</span>";
        }
        $content.="</div>";
        $template->SetCellText($curRow,0,$content,"center");
        
        return $layout->__toString();
    }

    //Template filtro di ricerca utenti
    public function TemplateHomeUtentiLegacyFilterDlg()
    {
        //Valori runtime
        $formData=array("status"=>$_REQUEST['status'],"ruolo"=>$_REQUEST['ruolo'],"user"=>$_REQUEST['user'],"email"=>$_REQUEST['email']);
        if(AA_const::AA_ENABLE_LEGACY_DATA)
        {
            $formData['id_assessorato']=$_REQUEST['id_assessorato'];
            $formData['id_direzione']=$_REQUEST['id_direzione'];
            $formData['id_servizio']=$_REQUEST['id_servizio'];
            $formData['struct_desc']=$_REQUEST['struct_desc'];
            $formData['id_struct_tree_select']=$_REQUEST['id_struct_tree_select'];
            if($formData['id_struct_tree_select'] == "")
            {
                if($formData['id_assessorato']>0) $form_data['id_struct_tree_select']=$formData['id_assessorato'];
                if($formData['id_direzione']>0) $form_data['id_struct_tree_select'].=".".$formData['id_direzione'];
                if($formData['id_servizio']>0) $form_data['id_struct_tree_select'].=".".$formData['id_servizio'];
            }

            if($_REQUEST['struct_desc']=="") $formData['struct_desc']="Qualunque";
            if($_REQUEST['id_assessorato']=="") $formData['id_assessorato']=0;
            if($_REQUEST['id_direzione']=="") $formData['id_direzione']=0;
            if($_REQUEST['id_servizio']=="") $formData['id_servizio']=0;
        }

        if(!isset($_REQUEST['ruolo'])) $formData['ruolo']=-1;
        if(!isset($_REQUEST['status'])) $formData['status']=-1;
                
        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","status"=>-1,"ruolo"=>-1,"email"=>"","user"=>"");
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="AA_MainApp.curModule.refreshUiObject('".static::AA_UI_WND_IMPORT_UTENTI_LEGACY."_".static::AA_UI_TABLE_IMPORT_UTENTI_LEGACY."',true)";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_UtentiLegacy_Filter", "Parametri di filtraggio",$this->GetId(),$formData,$resetData,$applyActions,static::AA_UI_WND_IMPORT_UTENTI_LEGACY."_".static::AA_UI_TABLE_IMPORT_UTENTI_LEGACY);
        
        $dlg->SetHeight(480);
        
        //nome utente
        $dlg->AddTextField("user","Login utente",array("bottomLabel"=>"*Filtra in base al login utente.", "placeholder"=>"..."));

        //email
        $dlg->AddTextField("email","Email",array("bottomLabel"=>"*Filtra in base alla email.", "placeholder"=>"..."));

        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura di incardinamento."));
        
        //Ruolo
        $options=array(array("id"=>"-1","value"=>"Qualunque"));
        $options[]=array("id"=>"0","value"=>"Amministratore");
        $options[]=array("id"=>"1","value"=>"Operatore");

        $dlg->AddSelectField("ruolo","Ruolo",array("bottomLabel"=>"*Filtra in base al ruolo dell'utente.","options"=>$options));
        
        //stato utente
        $options=array(array("id"=>"-1","value"=>"Qualunque"));
        $options[]=array("id"=>"1","value"=>"Abilitato");
        $options[]=array("id"=>"0","value"=>"Disabilitato");
        $dlg->AddSelectField("status","Stato",array("bottomLabel"=>"*Filtra in base allo stato dell'utente.","options"=>$options));

        $dlg->SetApplyButtonName("Filtra");
        $dlg->EnableApplyHotkey();

        return $dlg->GetObject();
    }

    //Template dlg modify user
    public function Template_GetHomeUtentiModifyDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_GetHomeUtentiModifyDlg";
        if(!($object instanceof AA_User)) return new AA_GenericWindowTemplate($id, "Modifica utente", $this->id);

        $form_data['id']=$object->GetID();
        $form_data['user']=$object->GetUsername();
        $form_data['email']=$object->GetEmail();
        $form_data['nome']=$object->GetNome();
        $form_data['cognome']=$object->GetCognome();
        $form_data['phone']=$object->GetPhone();
        $form_data['image']=$object->GetImage();
        $form_data['ruolo']=$object->GetRuolo(true);
        $form_data['status']=$object->GetStatus();
        if($object->IsConcurrentEnabled()) $form_data['concurrent']=1;

        foreach($object->GetFlags(true,false) as $curFlag)
        {
            $form_data['flag_'.$curFlag]=1;
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            foreach($object->GetLegacyFlags(true) as $curFlag)
            {
                $form_data['legacyFlag_'.$curFlag]=1;
            }
            
            $struct=$object->GetStruct();
            $form_data['id_assessorato']=$struct->GetAssessorato(true);
            $form_data['id_direzione']=$struct->GetDirezione(true);
            $form_data['id_servizio']=$struct->GetServizio(true);
            $form_data['struct_desc']="Nessuna";
            
            $form_data['id_struct_tree_select']="root";
            if($form_data['id_assessorato']>0) $form_data['id_struct_tree_select']=$form_data['id_assessorato'];
            if($form_data['id_direzione']>0) $form_data['id_struct_tree_select'].=".".$form_data['id_direzione'];
            if($form_data['id_servizio']>0) $form_data['id_struct_tree_select'].=".".$form_data['id_servizio'];

            if($struct->GetAssessorato(true)>0) $form_data['struct_desc']=$struct->GetAssessorato();
            if($struct->GetDirezione(true)>0) $form_data['struct_desc']=$struct->GetDirezione();
            if($struct->GetServizio(true)>0) $form_data['struct_desc']=$struct->GetServizio();
        }

        $wnd=new AA_GenericFormDlg($id, "Modifica utente", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(160);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(800);
        
        //username
        $wnd->AddTextField("user","Nome profilo",array("required"=>true,"gravity"=>2, "disabled"=>true,"bottomLabel"=>"*Puo' essere usato in fase di autenticazione."));

        //Ruolo
        if($this->oUser->IsSuperUser()) 
        {
            $options[]=array("id"=>AA_User::AA_USER_GROUP_SUPERUSER,"value"=>"Super utente");
            $options[]=array("id"=>AA_User::AA_USER_GROUP_ADMINS,"value"=>"Amministratore");
            $options[]=array("id"=>AA_User::AA_USER_GROUP_OPERATORS,"value"=>"Operatore");
            $options[]=array("id"=>AA_User::AA_USER_GROUP_USERS,"value"=>"Utente");
            $options[]=array("id"=>AA_User::AA_USER_GROUP_SERVEROPERATORS,"value"=>"Operatori server");
        }
        else
        {
            if($this->oUser->GetRuolo(true) == AA_User::AA_USER_GROUP_SERVEROPERATORS) 
            {
                $options[]=array("id"=>AA_User::AA_USER_GROUP_ADMINS,"value"=>"Amministratore");
                $options[]=array("id"=>AA_User::AA_USER_GROUP_OPERATORS,"value"=>"Operatore");
                $options[]=array("id"=>AA_User::AA_USER_GROUP_USERS,"value"=>"Utente");
                //$options[]=array("id"=>AA_User::AA_USER_GROUP_SERVEROPERATORS,"value"=>"Operatori server");
            }
            else
            {
                //$options[]=array("id"=>AA_User::AA_USER_GROUP_ADMINS,"value"=>"Amministratore");
                $options[]=array("id"=>AA_User::AA_USER_GROUP_OPERATORS,"value"=>"Operatore");
                $options[]=array("id"=>AA_User::AA_USER_GROUP_USERS,"value"=>"Utente");
            }
        }
        $wnd->AddSelectField("ruolo","Ruolo",array("gravity"=>1,"required"=>true, "validateFunction"=>"IsSelected","bottomLabel"=>"*Ruolo da assegnare all'utente.","options"=>$options),false);
        
        //email
        $wnd->AddTextField("email","Email",array("required"=>true,"gravity"=>2, "validateFunction"=>"IsEmail","bottomLabel"=>"*Email", "placeholder"=>"Email associata all'utente."));

        //stato
        $wnd->AddSwitchBoxField("status","Stato",array("gravity"=>1,"onLabel"=>"Abilitato","offLabel"=>"Disabilitato","bottomLabel"=>"*stato dell'utente","value"=>1),false);

        //Dati personali
        $section=new AA_FieldSet($id."_Section_DatiPersonali","Dati personali");
        $section->AddTextField("nome", "Nome", array("required"=>true,"bottomLabel"=>"*Nome dell'utente", "placeholder"=>"Caio"));
        $section->AddTextField("cognome", "Cognome", array("required"=>true,"bottomLabel"=>"*Cognome dell'utente", "placeholder"=>"Sempronio"),false);
        $section->AddTextField("phone", "Telefono", array("bottomLabel"=>"*Recapito telefonico", "placeholder"=>"..."));
        $section->AddSpacer(false);
        $wnd->AddGenericObject($section);
        
        //----------- Ordinary Flags ---------------
        $section=new AA_FieldSet($id."_Section_Flags","Abilitazioni");
        $platform=AA_Platform::GetInstance($this->oUser);
        $moduli=$platform->GetModulesFlags();
        $curRow=0;
        foreach($moduli as $curFlag=>$descr)
        {
            $newLine=false;
            if($curRow%4 == 0 && $curRow >= 4) $newLine=true;
            $section->AddCheckBoxField("flag_".$curFlag, $descr, array("bottomPadding"=>8),$newLine);
            $curRow++;
        }
        for($i=$curRow;$i<4;$i++)
        {
            $section->AddSpacer(false);
        }

        $section->AddCheckBoxField("concurrent", "Login concorrente", array("bottomLabel"=>"Abilita l'accesso concorrente."));
        $wnd->AddGenericObject($section);
        //-------------------------------------------

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $section=new AA_FieldSet($id."_Section_LegacyFlags","Abilitazioni legacy",$wnd->GetFormId());
            if($this->oUser->IsSuperUser())
            {
                //--------------- Legacy flags --------------
                $legacyFlags=AA_Platform::GetLegacyFlags();
                $curRow=0;
                foreach($legacyFlags as $curFlag=>$descr)
                {
                    $newLine=false;
                    if($curRow%4 == 0 && $curRow >= 4) $newLine=true;
                    $section->AddCheckBoxField("legacyFlag_".$curFlag, $descr, array("value"=>0,"bottomPadding"=>8),$newLine);
                    $curRow++;
                }
                //-------------------------------------------
                for($i=$curRow;$i<4;$i++)
                {
                    $section->AddSpacer(false);
                }
            }

            //Struttura
            $section->AddStructField(array("targetForm"=>$wnd->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Struttura di incardinamento dell'utente."));

            $wnd->AddGenericObject($section);
        }

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("HomeUtentiUpdate");
        
        return $wnd;
    }

    //Template dlg add new user
    public function Template_GetHomeUtentiAddNewDlg()
    {
        $id=static::AA_UI_PREFIX."_GetHomeUtentiAddNewDlg_".uniqid();

        $form_data['ruolo']=AA_User::AA_USER_GROUP_OPERATORS;
        $form_data['status']=AA_User::AA_USER_STATUS_ENABLED;
        
        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            foreach(AA_Platform::GetLegacyFlags() as $curFlag)
            {
                $form_data['legacyFlag_'.$curFlag]=0;
            }
            
            $struct=$this->oUser->GetStruct();
            $form_data['id_assessorato']=$struct->GetAssessorato(true);
            $form_data['id_direzione']=$struct->GetDirezione(true);
            $form_data['id_servizio']=$struct->GetServizio(true);
            $form_data['struct_desc']="Nessuna";
            
            $form_data['id_struct_tree_select']="root";
            if($form_data['id_assessorato']>0) $form_data['id_struct_tree_select']=$form_data['id_assessorato'];
            if($form_data['id_direzione']>0) $form_data['id_struct_tree_select'].=".".$form_data['id_direzione'];
            if($form_data['id_servizio']>0) $form_data['id_struct_tree_select'].=".".$form_data['id_servizio'];

            if($struct->GetAssessorato(true)>0) $form_data['struct_desc']=$struct->GetAssessorato();
            if($struct->GetDirezione(true)>0) $form_data['struct_desc']=$struct->GetDirezione();
            if($struct->GetServizio(true)>0) $form_data['struct_desc']=$struct->GetServizio();
        }

        $wnd=new AA_GenericFormDlg($id, "Aggiungi profilo utente", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(160);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(800);
        
        //Ruolo
        if($this->oUser->IsSuperUser()) 
        {
            $options[]=array("id"=>AA_User::AA_USER_GROUP_SUPERUSER,"value"=>"Super utente");
            $options[]=array("id"=>AA_User::AA_USER_GROUP_ADMINS,"value"=>"Amministratore");
            $options[]=array("id"=>AA_User::AA_USER_GROUP_OPERATORS,"value"=>"Operatore");
            $options[]=array("id"=>AA_User::AA_USER_GROUP_USERS,"value"=>"Utente");
            $options[]=array("id"=>AA_User::AA_USER_GROUP_SERVEROPERATORS,"value"=>"Operatori server");
        }
        else
        {
            if($this->oUser->GetRuolo(true) == AA_User::AA_USER_GROUP_SERVEROPERATORS) 
            {
                $options[]=array("id"=>AA_User::AA_USER_GROUP_ADMINS,"value"=>"Amministratore");
                $options[]=array("id"=>AA_User::AA_USER_GROUP_OPERATORS,"value"=>"Operatore");
                $options[]=array("id"=>AA_User::AA_USER_GROUP_USERS,"value"=>"Utente");
                //$options[]=array("id"=>AA_User::AA_USER_GROUP_SERVEROPERATORS,"value"=>"Operatori server");
            }
            else
            {
                //$options[]=array("id"=>AA_User::AA_USER_GROUP_ADMINS,"value"=>"Amministratore");
                $options[]=array("id"=>AA_User::AA_USER_GROUP_OPERATORS,"value"=>"Operatore");
                $options[]=array("id"=>AA_User::AA_USER_GROUP_USERS,"value"=>"Utente");
            }
        }

        if($this->oUser->IsSuperUser())
        {
             //username
            $wnd->AddTextField("user","Nome profilo",array("gravity"=>2, "bottomLabel"=>"*Deve essere univoco (lasciare vuoto per impostazione automatica)."));

            $wnd->AddSelectField("ruolo","Ruolo",array("gravity"=>1,"required"=>true, "validateFunction"=>"IsSelected","bottomLabel"=>"*Ruolo da assegnare all'utente.","options"=>$options),false);
        }
        else
        {
            $wnd->AddSelectField("ruolo","Ruolo",array("gravity"=>1,"required"=>true, "validateFunction"=>"IsSelected","bottomLabel"=>"*Ruolo da assegnare all'utente.","options"=>$options));

            $wnd->AddSpacer(false);
            $wnd->AddSpacer(false);
        }
       
        //email
        $wnd->AddTextField("email","Email",array("required"=>true,"gravity"=>2, "validateFunction"=>"IsEmail","bottomLabel"=>"*Email", "placeholder"=>"Email associata all'utente."));

        //stato
        $wnd->AddSwitchBoxField("status","Stato",array("gravity"=>1,"onLabel"=>"Abilitato","offLabel"=>"Disabilitato","bottomLabel"=>"*stato dell'utente","value"=>1),false);

        //Dati personali
        $section=new AA_FieldSet($id."_Section_DatiPersonali","Dati personali");
        $section->AddTextField("nome", "Nome", array("required"=>true,"bottomLabel"=>"*Nome dell'utente", "placeholder"=>"nome"));
        $section->AddTextField("cognome", "Cognome", array("required"=>true,"bottomLabel"=>"*Cognome dell'utente", "placeholder"=>"cognome"),false);
        $section->AddTextField("phone", "Telefono", array("bottomLabel"=>"*Recapito telefonico", "placeholder"=>"..."));
        $section->AddSpacer(false);
        $wnd->AddGenericObject($section);
        
        //----------- Ordinary Flags ---------------
        $section=new AA_FieldSet($id."_Section_Flags","Abilitazioni");
        $platform=AA_Platform::GetInstance($this->oUser);
        $moduli=$platform->GetModulesFlags();
        $curRow=0;
        foreach($moduli as $curFlag=>$descr)
        {
            $newLine=false;
            if($curRow%4 == 0 && $curRow >= 4) $newLine=true;
            $section->AddCheckBoxField("flag_".$curFlag, $descr, array("value"=>0,"bottomPadding"=>8),$newLine);
            $curRow++;
        }
        for($i=$curRow;$i<4;$i++)
        {
            $section->AddSpacer(false);
        }

        $section->AddCheckBoxField("concurrent", "Login concorrente", array("value"=>0,"bottomLabel"=>"Abilita l'accesso concorrente."));
        $wnd->AddGenericObject($section);
        //-------------------------------------------

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $section=new AA_FieldSet($id."_Section_LegacyFlags","Abilitazioni legacy",$wnd->GetFormId());

            if($this->oUser->IsSuperUser())
            {
                //--------------- Legacy flags --------------
                $legacyFlags=AA_Platform::GetLegacyFlags();
                $curRow=0;
                foreach($legacyFlags as $curFlag=>$descr)
                {
                    $newLine=false;
                    if($curRow%4 == 0 && $curRow >= 4) $newLine=true;
                    $section->AddCheckBoxField("legacyFlag_".$curFlag, $descr, array("value"=>0,"bottomPadding"=>8),$newLine);
                    $curRow++;
                }
                //-------------------------------------------
                for($i=$curRow;$i<4;$i++)
                {
                    $section->AddSpacer(false);
                }
            }

            //Struttura
            $section->AddStructField(array("targetForm"=>$wnd->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Struttura di incardinamento dell'utente."));

            $wnd->AddGenericObject($section);
        }

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("HomeUtentiAddNew");
        
        return $wnd;
    }

    //Template dlg add new user
    public function Template_GetHomeStructAddNewDlg($id_assessorato=0,$id_direzione=0)
    {
        $id=static::AA_UI_PREFIX."_GetHomeUtentiAddNewDlg_".uniqid();

        $form_data=array();
        $form_data['id_assessorato']=$id_assessorato;
        $form_data['id_direzione']=$id_direzione;
        $form_data['data_istituzione']=date("Y-m-d");
        $form_data['data_soppressione']="9999-12-31";

        $wnd=new AA_GenericFormDlg($id, "Aggiungi nuova struttura", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(540);
        $wnd->SetHeight(300);
        
        //tipologia
        if($id_assessorato==0)
        {
            $tipo=AA_Struttura::GetTipologie();
            $options=array();
            foreach($tipo as $key=>$val)
            {
                $options[]=array("id"=>($key+1),"value"=>$val);
            }

            $wnd->AddSelectField("tipo","Tipologia",array("required"=>true,"validateFunction"=>"IsSelected","bottomLabel"=>"*Scegli una tipologia di struttura","options"=>$options));
        }
        
        //descrizione
        $wnd->AddTextField("descrizione","Denominazione",array("required"=>true,"gravity"=>2, "bottomLabel"=>"*Denominazione struttura", "placeholder"=>"..."));

        $wnd->AddDateField("data_istituzione","Data istituzione",array("required"=>true));

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("HomeStructAddNew");
        
        return $wnd;
    }

    //Template dlg add new user
    public function Template_GetHomeStructModifyDlg($struct=null)
    {
        $id=static::AA_UI_PREFIX."_GetHomeStructModifyDlg_".uniqid();

        $form_data=array();
        foreach($struct->GetProps() as $key=>$val)
        {
            if($key !="tipo") $form_data[$key]=$val;
            else $form_data[$key]=$val+1;  
        }

        if($form_data['data_soppressione']<= Date("Y-m-d")) $form_data['soppressa']=1;

        //AA_Log::Log(__METHOD__." - ".print_r($form_data,true),100);

        $wnd=new AA_GenericFormDlg($id, "Modifica struttura", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(140);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(540);
        $wnd->SetHeight(540);
        
        //tipologia
        if($struct instanceof AA_Assessorato)
        {
            $tipo=AA_Struttura::GetTipologie();
            $options=array();
            foreach($tipo as $key=>$val)
            {
                $options[]=array("id"=>($key+1),"value"=>$val);
            }

            $wnd->AddSelectField("tipo","Tipologia",array("required"=>true,"validateFunction"=>"IsSelected","bottomLabel"=>"*Scegli una tipologia di struttura","options"=>$options));
        }
        
        //descrizione
        $wnd->AddTextField("descrizione","Denominazione",array("required"=>true,"gravity"=>2, "bottomLabel"=>"*Denominazione struttura", "placeholder"=>"..."));

        $wnd->AddDateField("data_istituzione","Data istituzione",array("required"=>true));

        $wnd->AddCheckBoxField("soppressa","Soppressa",array("bottomLabel"=>"*Abilitare se la struttura e' stata soppressa","relatedView"=>$id."_Field_data_soppressione", "relatedAction"=>"show","eventHandlers"=>array("onChange"=>array("handler"=>"onStructSuppress","module_id"=>$this->GetId()))));

        $wnd->AddDateField("data_soppressione","Data soppressione",array("required"=>true));

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("HomeStructUpdate");
        
        return $wnd;
    }

    //Template gestione strutture content
    public function TemplateSection_GestStruct()
    {
        //AA_Log::Log(__METHOD__,100);
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GESTSTRUCT;
        $tree_view_id=$id."_tree_".uniqid();

        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean","name" => static::AA_UI_SECTION_GESTSTRUCT_NAME,"filtered"=>true));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

         $filter="";
        if(!isset($_REQUEST['show_suppressed']) || $_REQUEST['show_suppressed']==0) $filter="<span class='AA_Label AA_Label_LightOrange'>Solo strutture attive</span>";

        if($filter=="") $filter="<span class='AA_Label AA_Label_LightOrange'>tutti</span>";
        
        $toolbar->addElement(new AA_JSON_Template_Generic($id."_FilterLabel",array("view"=>"label","gravity"=>3,"align"=>"left","label"=>"<div>Visualizza: ".$filter."</div>")));

        //$toolbar->AddElement(new AA_JSON_Template_Generic($this->id . "_Switch_Supressed", array("view" => "switch", "width" => 350, "label" => "Strutture soppresse:", "labelWidth" => 150, "onLabel" => "visibili", "offLabel" => "nascoste", "tooltip" => "mostra/nascondi le strutture soppresse")));

        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("gravity"=>3)));
        
        $toolbar->AddElement(new AA_JSON_Template_Search("", array("gravity"=>1,"tree_view_id"=>$tree_view_id,"placeholder" => "Digita qui per filtrare le strutture","eventHandlers"=>array("onTimedKeyPress"=>array("handler"=>"onFilterStructChange","module_id"=>$this->GetId())))));

        //Pulsante di modifica
        $canModify=false;
        if($this->oUser->IsSuperUser()) $canModify=true;
        if($canModify)
        {            
            $modify_btn=new AA_JSON_Template_Generic("",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-office-building-plus",
                "label"=>"Aggiungi",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi una nuova struttura di primo livello",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetHomeStructAddNewDlg\"},'".$this->id."')"
            ));
            $toolbar->AddElement($modify_btn);
        }

        $layout->AddRow($toolbar);

        if($this->oUser->IsValid()) 
        {
            $struct = "";
            $userStruct = $this->oUser->GetStruct();
            if ($this->oUser->IsSuperUser()) 
            {
                $struct = AA_Struct::GetStruct(0, 0, 0, $userStruct->GetTipo()); //RAS
            }
            else 
            {
                $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), 0, 0, $userStruct->GetTipo()); //Altri
            }

            $tree=$struct->toArray();
            foreach($tree[0]['data'] as $id_assessorato=>$curAssessorato)
            {
                if($struct->GetAssessorato(true) == 0) 
                {
                    $addnew='AA_MainApp.utils.callHandler("dlg", {task:"GetHomeStructAddNewDlg", params: [{id_assessorato: "'.$curAssessorato['id_assessorato'].'"}]},"'.$this->id.'")';
                    $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetHomeStructModifyDlg", params: [{id_assessorato: "'.$curAssessorato['id_assessorato'].'"}]},"'.$this->id.'")';
                    $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetHomeStructTrashDlg", params: [{id_assessorato: "'.$curAssessorato['id_assessorato'].'"}]},"'.$this->id.'")';
                    $tree[0]['data'][$id_assessorato]['ops']="<a class='AA_DataTable_Ops_Button' title='Aggiungi una sottostruttura' onClick='".$addnew."'><span class='mdi mdi-office-building-plus'></span></a>&nbsp;<a class='AA_DataTable_Ops_Button' title='Modifica questa struttura' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a>&nbsp;<a class='AA_DataTable_Ops_Button_Red' title='Elimina questa struttura' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a>";
                }
                else $tree[0]['data'][$id_assessorato]['ops']="";

                if($curAssessorato['soppresso']==1) $tree[0]['data'][$id_assessorato]['class']='AA_Struct_soppressa';
                else $tree[0]['data'][$id_assessorato]['class']='';
                
                if(isset($curAssessorato['data']))
                {
                    foreach($curAssessorato['data'] as $id_direzione=>$curDirezione)
                    {
                        if($struct->GetDirezione(true) == 0) 
                        {
                            $addnew='AA_MainApp.utils.callHandler("dlg", {task:"GetHomeStructAddNewDlg", params: [{id_assessorato: "'.$curDirezione['id_assessorato'].'"},{id_direzione: "'.$curDirezione['id_direzione'].'"}]},"'.$this->id.'")';
                            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetHomeStructModifyDlg", params: [{id_assessorato: "'.$curDirezione['id_assessorato'].'"},{id_direzione: "'.$curDirezione['id_direzione'].'"}]},"'.$this->id.'")';
                            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetHomeStructTrashDlg", params: [{id_assessorato: "'.$curDirezione['id_assessorato'].'"},{id_direzione: "'.$curDirezione['id_direzione'].'"}]},"'.$this->id.'")';
                            $tree[0]['data'][$id_assessorato]['data'][$id_direzione]['ops']="<a class='AA_DataTable_Ops_Button' title='Aggiungi una sottostruttura' onClick='".$addnew."'><span class='mdi mdi-office-building-plus'></span></a>&nbsp;<a class='AA_DataTable_Ops_Button' title='Modifica questa struttura' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a>&nbsp;<a class='AA_DataTable_Ops_Button_Red' title='Elimina questa struttura' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a>";
                        }
                        else $tree[0]['data'][$id_assessorato]['data'][$id_direzione]['ops']="";

                        if($curDirezione['soppresso']==1) $tree[0]['data'][$id_assessorato]['data'][$id_direzione]['class']='AA_Struct_soppressa';
                        else $tree[0]['data'][$id_assessorato]['data'][$id_direzione]['class']='';

                        if(isset($curDirezione['data']))
                        {
                            foreach($curDirezione['data'] as $id_servizio=>$curServizio)
                            {
                                if($curServizio['soppresso']==1) $tree[0]['data'][$id_assessorato]['data'][$id_direzione]['data'][$id_servizio]['class']='AA_Struct_soppressa';
                                else $tree[0]['data'][$id_assessorato]['data'][$id_direzione]['data'][$id_servizio]['class']='';
                                if($struct->GetServizio(true) == 0) 
                                {
                                    $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetHomeStructModifyDlg", params: [{id_assessorato: "'.$curDirezione['id_assessorato'].'"},{id_direzione: "'.$curDirezione['id_direzione'].'"},{id_servizio: "'.$curServizio['id_servizio'].'"}]},"'.$this->id.'")';
                                    $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetHomeStructTrashDlg", params: [{id_assessorato: "'.$curDirezione['id_assessorato'].'"},{id_direzione: "'.$curDirezione['id_direzione'].'"},{id_servizio: "'.$curServizio['id_servizio'].'"}]},"'.$this->id.'")';
                                    $tree[0]['data'][$id_assessorato]['data'][$id_direzione]['data'][$id_servizio]['ops']="<a class='AA_DataTable_Ops_Button' title='Modifica questa struttura' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a>&nbsp;<a class='AA_DataTable_Ops_Button_Red' title='Elimina questa struttura' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a>";
                                }
                                else $tree[0]['data'][$id_assessorato]['data'][$id_direzione]['data'][$id_servizio]['ops']="";
                            }
                        }
                    }
                }
            }

            $tree = new AA_JSON_Template_Tree($tree_view_id, array(
                "data" => json_encode($tree),
                "status_id"=>$id."_tree",
                "select" => false,
                "template" => "{common.icon()}&nbsp;{common.folder()}&nbsp;<span class='#class#'>#value#</span>&nbsp;#ops#",
                "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
            ));
        } 
        else 
        {
            $tree=new AA_JSON_Template_Template($id,array("type"=>"clean","name" => static::AA_UI_SECTION_GESTSTRUCT_NAME,"template"=>"Non sono presenti strutture."));
        }

        $layout->AddRow($tree);
        $layout->AddRow(new AA_JSON_Template_Generic($this->id . "_Switch_Supressed", array("view" => "switch", "width" => 350, "label" => "Strutture cessate:", "labelWidth" => 150, "onLabel" => "visibili", "offLabel" => "nascoste", "tooltip" => "mostra/nascondi le strutture cessate")));
        return $layout;
    }

    //Task send credentials
    public function Task_HomeUtentiSendCredenzials($task)
    {
        if(!$this->oUser->CanGestUtenti())
        {
            AA_Log::Log(__METHOD__." - L'utente corrente (".$this->oUser->GetUsername().") non è abilitato alla gestione utenti.",100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione utenti.");
            return false;
        }

        $user=AA_User::LoadUser($_REQUEST['id']);
        if(!$user->IsValid())
        {
            AA_Log::Log(__METHOD__." - L'utente inidcato non è stato trovato (".$_REQUEST['id'].").",100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente indicato non valido.");
            return false;
        }

        if(!$this->oUser->CanModifyUser($user))
        {
            AA_Log::Log(__METHOD__." - L'utente corrente (".$this->oUser->GetUsername().") non può gestire l'utente indicato.",100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione dell'utente indicato.");
            return false;
        }

        if(!AA_User::SendCredentials($user->GetId(),AA_Const::AA_ENABLE_SENDMAIL))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError(AA_Log::$lastErrorLog,"");
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        if(AA_Const::AA_ENABLE_SENDMAIL) $task->SetContent("Credenziali reimpostate e inviate con successo.");
        else $task->SetContent("Credenziali reimpostate con successo.");
        return true;
    }

    //Task send credentials
    public function Task_HomeUtentiConfirmSendCredenzials($task)
    {
        if(!$this->oUser->CanGestUtenti())
        {
            AA_Log::Log(__METHOD__." - L'utente corrente (".$this->oUser->GetUsername().") non è abilitato alla gestione utenti.",100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione utenti.");
            return false;
        }

        $user=AA_User::LoadUser($_REQUEST['id']);
        if(!$user->IsValid())
        {
            AA_Log::Log(__METHOD__." - L'utente inidcato non è stato trovato (".$_REQUEST['id'].").",100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente indicato non valido.");
            return false;
        }

        if(!$this->oUser->CanModifyUser($user))
        {
            AA_Log::Log(__METHOD__." - L'utente corrente (".$this->oUser->GetUsername().") non può gestire l'utente indicato.",100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non è abilitato alla gestione dell'utente indicato.");
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_HomeUtentiConfirmSendUserCredentials($user),true);

        return true;
    }

    //Template confirm delete personale detyerminato comuni
    public function Template_HomeUtentiConfirmSendUserCredentials($user=null)
    {
        $id=$this->GetId()."_".uniqid();
        if(!($user instanceof AA_User)) return new AA_GenericWindowTemplate($id, "Conferma invio credenziali utente", $this->id);


        $form_data=array();
        $form_data['id']=$user->GetID();
        $wnd=new AA_GenericFormDlg($id, "Conferma invio credenziali utente", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(540);
        $wnd->SetHeight(480);
        
        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p><p>Questa operazione <b>reimpostera' le credenziali</b> per il profilo utente:<p><b>".$user->GetUsername()." </b></p></p><p style='font-size: larger;'>Vuoi procedere?</p></div>";
        $layout=new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template));

        $wnd->AddGenericObject($layout);

        $wnd->EnableCloseWndOnSuccessfulSave();
        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
        $wnd->SetApplyButtonName("Procedi");
        $wnd->EnableResetButton(false);
        $wnd->SetSaveTask("HomeUtentiSendCredenzials");
        
        return $wnd;
    }

    //Task action menù
    public function Task_GetActionMenu($task)
    {
        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $content = "";

        switch ($_REQUEST['section']) {
            case static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_DESKTOP:
                $content = $this->TemplateActionMenu_Cruscotto();
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_GESTUTENTI:
                $content = $this->TemplateActionMenu_Gestutenti();
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_GESTSTRUCT:
                $content = $this->TemplateActionMenu_GestStruct();
                break;
            default:
                $content = new AA_JSON_Template_Generic();
                break;
        }

        if ($content != "") $sTaskLog .= $content->toBase64();

        $sTaskLog .= "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }
}
#----------------------------------------
