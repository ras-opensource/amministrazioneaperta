<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "config.php";
include_once "system_lib.php";

#Costanti
Class AA_Gecop_Const extends AA_Const
{
    const AA_USER_FLAG_GECOP="gecop";

    const AA_USER_FLAG_GECOP_RO="gescop_ro";

    const AA_DBTABLE_CODICI_ISTAT="aa_patrimonio_codici_istat";

    protected static $aRuoloComponente=null;
    const AA_GECOP_RUOLO_PRESIDENTE=1;
    const AA_GECOP_RUOLO_SEGRETARIO=2;
    const AA_GECOP_RUOLO_COMPONENTE=4;
    const AA_GECOP_RUOLO_SUPPLENTE=8;
    public static function GetListaRuoloComponente()
    {
        if(static::$aRuoloComponente==null)
        {
            static::$aRuoloComponente=array(
                static::AA_GECOP_RUOLO_PRESIDENTE=>"Presidente",
                static::AA_GECOP_RUOLO_SEGRETARIO=>"Segretario",
                static::AA_GECOP_RUOLO_COMPONENTE=>"Componente",
                static::AA_GECOP_RUOLO_SUPPLENTE=>"Supplente"
            );
        }

        return static::$aRuoloComponente;
    }

    public static function GetListaComuni()
    {
        $db=new AA_Database();
        $query="SELECT id,comune FROM ".AA_Gecop_Const::AA_DBTABLE_CODICI_ISTAT." ORDER BY comune ASC";
        //$query.=" LIMIT 10";

        //AA_Log::Log(__METHOD__." - query ".$query.print_r($_REQUEST,true),100);
        
        //errore nella query
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - ERRORE ".$db->GetErrorMessage(),100);
            die("[]");
        }

        //Query vuota
        if($db->GetAffectedRows() == 0)
        {
            die("[]");
        }
        
        $result=array();
        $count=1;
        foreach($db->GetResultSet() as $curRow)
        {
            $result[$curRow['id']]=$curRow['comune'];
            $count++;
        }

        return $result;
    }
}

#Classe oggetto Gecop
Class AA_Gecop extends AA_Object_V2
{
    //tabella dati db
    const AA_DBTABLE_DATA="aa_gecop_data";
    static protected $AA_DBTABLE_OBJECTS="aa_gecop_objects";

    //Funzione di cancellazione
    protected function DeleteData($idData = 0, $user = null)
    {
        if(!$this->IsValid() || $this->IsReadOnly() || $idData == 0) return false;

        if($idData != $this->nId_Data && $idData != $this->nId_Data_Rev) return false;

        //Cancella tutti gli allegati
        $allegati=$this->GetAllegati();
        foreach( $allegati as $key=>$curAllegato)
        {
            if(!$this->DeleteAllegato($key,$user))
            {
                return false;
            }
        }

        //cancella i cv della commissione
        $commissione=$this->GetCommissione();
        foreach($commissione as $curComponente)
        {
            if(!empty($curComponente['filehash']))
            {
                $storage=AA_Storage::GetInstance($user);
                if($storage->IsValid())
                {
                    if(!$storage->DelFile($curComponente['filehash']))
                    {
                        AA_Log::Log(__METHOD__." - errore nell'eliminazione del cv per il componente: ".$curComponente['nominativo'],100);
                    }
                }
            }
        }

        return parent::DeleteData($idData,$user);
    }

    public function serialize()
    {
        $result=get_object_vars($this);
        return json_encode($result);
    }


    //aggiudicatario
    protected $aggiudicatario=null;
    public function GetAggiudicatario()
    {
        if(!$this->IsValid()) return array();

        if(!is_array($this->aggiudicatario))
        {
            $this->aggiudicatario=json_decode($this->GetProp('Aggiudicatario'),true);
            if(!is_array($this->aggiudicatario))
            {
                //AA_Log::Log(__METHOD__." - errore nel parsing",100);
                return array();
            }
        }

        return $this->aggiudicatario;
    }

    //links
    protected $links=null;
    public function GetLinks()
    {
        if(!$this->IsValid()) return array();

        if(!is_array($this->links))
        {
            $this->links=json_decode($this->GetProp('Links'),true);
            if(!is_array($this->links))
            {
                //AA_Log::Log(__METHOD__." - errore nel parsing",100);
                return array();
            }
        }

        return $this->links;
    }

    //links
    protected $commissione=null;
    public function GetCommissione()
    {
        if(!$this->IsValid()) return array();

        if(!is_array($this->commissione))
        {
            $this->commissione=json_decode($this->GetProp('Commissione'),true);
            if(!is_array($this->commissione))
            {
                //AA_Log::Log(__METHOD__." - errore nel parsing",100);
                return array();
            }
        }

        return $this->commissione;
    }

    //links
    protected $gestione_finanziaria=null;
    public function GetGestioneFinanziaria()
    {
        if(!$this->IsValid()) return array();

        if(!is_array($this->gestione_finanziaria))
        {
            $this->gestione_finanziaria=json_decode($this->GetProp('GestioneFinanziaria'),true);
            if(!is_array($this->gestione_finanziaria))
            {
                //AA_Log::Log(__METHOD__." - errore nel parsing",100);
                return array();
            }
        }

        return $this->gestione_finanziaria;
    }

    //Funzione di clonazione dei dati
    protected function CloneData($idData = 0, $user = null)
    {
        if(!$this->IsValid() || $this->IsReadOnly()) return 0;
        
        $newIdData=parent::CloneData($idData,$user);

        return $newIdData;
    }

    //Costruttore
    public function __construct($id=0, $user=null)
    {
        //data table
        $this->SetDbDataTable(static::AA_DBTABLE_DATA);

        //Db data binding
        $this->AddProp("Note","","note");
        $this->AddProp("Cig",0,"cig");
        $this->AddProp("Anno",0,"anno");
        $this->AddProp("Aggiudicatario",0,"aggiudicatario");
        $this->AddProp("Links","","links");
        $this->AddProp("Commissione","","commissione");
        $this->AddProp("GestioneFinanziaria","","gestione_finanziaria");

        //disabilita la revisione
        $this->EnableRevision(false);

        //chiama il costruttore genitore
        parent::__construct($id,$user);
    }

    //funzione di ricerca
    static public function Search($params=array(),$user=null)
    {
        //Verifica utente
        if($user instanceof AA_User)
        {
            if(!$user->isCurrentUser())
            {
                $user=AA_User::GetCurrentUser();
            }
        }
        else $user=AA_User::GetCurrentUser();

        //---------local checks-------------
        $params['class']="AA_Gecop";
        //----------------------------------

        return parent::Search($params,$user);
    }

    //Funzione di verifica dei permessi
    public function GetUserCaps($user=null)
    {
        //Verifica utente
        if($user instanceof AA_User)
        {
            if(!$user->isCurrentUser())
            {
               $user=AA_User::GetCurrentUser();
            }
        }
        else $user=AA_User::GetCurrentUser();

        $perms=parent::GetUserCaps($user);

        //------------local checks---------------

        //Se l'utente non ha il flag può al massimo visualizzare la scheda
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && !$user->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $perms = AA_Const::AA_PERMS_READ;
        }
        //---------------------------------------

        //Se l'utente ha il flag e può modificare la scheda allora può fare tutto
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && $user->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $perms = AA_Const::AA_PERMS_ALL;
        }
        //---------------------------------------

        return $perms;
    }

    static public function AddNew($object=null,$user=null,$bSaveData=true)
    {
        //Verifica utente
        if($user instanceof AA_User)
        {
            if(!$user->isCurrentUser())
            {
               $user=AA_User::GetCurrentUser();
            }
        }
        else $user=AA_User::GetCurrentUser();

        //-------------local checks---------------------
        $bStandardCheck=false; //disable standard checks
        $bSaveData=true; //enable save data

        //Chi non ha il flag non può inserire nuovi elementi
        if(!$user->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            AA_Log::Log(__METHOD__." - L'utente corrente: ".$user->GetUserName()." non ha i permessi per inserire nuovi elementi.",100);
            return false;
        }

        //Verifica validità oggetto
        if(!($object instanceof AA_Gecop))
        {
            AA_Log::Log(__METHOD__." - Errore: oggetto non valido (".print_r($object,true).").",100);
            return false;
        }

        $object->nId=0;
        $object->bValid=true;
        //----------------------------------------------

        return parent::AddNew($object,$user,$bSaveData);
    }

    public function Update($user = null, $bSaveData = true, $logMsg = '')
    {
        //AA_Log::Log(__METHOD__." - Aggiornamento: ".print_r($this,true),100);
        return parent::Update($user,true,$logMsg);
    }

    public static function CigIsValid($cig='',$escludeId=0)
    {
        if(empty($cig)) return false;

        $db=new AA_Database();

        $query="SELECT id from ".AA_Gecop::AA_DBTABLE_DATA." where cig='".trim($cig)."' ";
        if($escludeId > 0) $query.=" AND id <> ".$escludeId;
        $query.=" LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore db: ".print_r($db->GetErrorMessage(),true),100);
            return false;
        }

        //AA_Log::Log(__METHOD__." - query: ".print_r($query,true),100);

        if($db->GetAffectedRows()> 0) return false;

        return true;
    }

    protected $allegati=null;

    //Restituisce gli allegati
    public function GetAllegati()
    {
         if(!$this->IsValid()) return array();

        if(!is_array($this->allegati))
        {
            $this->allegati=json_decode($this->GetProp('Allegati'),true);
            if(!is_array($this->allegati))
            {
                AA_Log::Log(__METHOD__." - errore nel parsing  degli allegati'",100);
                return array();
            }
        }

        return $this->allegati;
    }

    public function GetAllegato($id_allegato="")
    {
         if(!$this->IsValid() || $id_allegato <=0 || $id_allegato=="") return null;

        if(!is_array($this->allegati))
        {
            $this->allegati=json_decode($this->GetProp('Allegati'),true);
            if(!is_array($this->allegati))
            {
                AA_Log::Log(__METHOD__." - errore nel parsing  degli allegati'",100);
                return null;
            }
        }

        if(!isset($this->allegati[$id_allegato])) return null;
        $allegato=$this->allegati[$id_allegato];
        $allegato['id']=$id_allegato;

        return $allegato;
    }

    //Elimina un allegato esistente
    public function DeleteAllegato($id_allegato="", $user=null)
    {
        if(!$this->isValid())
        {
                AA_Log::Log(__METHOD__." - elemento non valido.", 100,false,true);
                return false;            
        }
        
        //Verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return false;
            }
        }

        //Verifica Flags
        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE)==0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non può modificare l'elemento.", 100,false,true);
            return false;
        }

        $allegati=$this->GetAllegati();
        if(!isset($allegati[$id_allegato]))
        {
            AA_Log::Log(__METHOD__." - Allegato/link non valido.", 100,false,true);
            return false;
        }

        $allegato=$allegati[$id_allegato];
        $fileHash=$allegato['filehash'];
        
        if($fileHash !="")
        {
            $storage=AA_Storage::GetInstance($user);
            if($storage->IsValid())
            {
                if(!$storage->DelFile($fileHash))
                {
                    AA_Log::Log(__METHOD__." - Errore nella rimozione del file sullo storage. (".$fileHash.")", 100,false,true);
                }
            }
        }
        
        unset($allegati[$id_allegato]);
        $this->SetAllegati($allegati);

        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Rimozione allegato/link: ".$id_allegato))
        {
            return false;
        }

        return true;
    }

    public function SetAllegati($val="")
    {
        if(is_array($val)) $this->aProps['Allegati']=json_encode($val);
        else $this->aProps['Allegati']=$val;
    }
}
Class AA_GecopModule extends AA_GenericModule
{
    const AA_UI_PREFIX="AA_Gecop";

    //Id modulo
    const AA_ID_MODULE="AA_MODULE_GECOP";

    //main ui layout box
    const AA_UI_MODULE_MAIN_BOX="AA_Gecop_module_layout";

    const AA_MODULE_OBJECTS_CLASS="AA_Gecop";

    //Task per la gestione dei dialoghi standard
    const AA_UI_TASK_PUBBLICATE_FILTER_DLG="GetGecopPubblicateFilterDlg";
    const AA_UI_TASK_BOZZE_FILTER_DLG="GetGecopBozzeFilterDlg";
    const AA_UI_TASK_REASSIGN_DLG="GetGecopReassignDlg";
    const AA_UI_TASK_PUBLISH_DLG="GetGecopPublishDlg";
    const AA_UI_TASK_TRASH_DLG="GetGecopTrashDlg";
    const AA_UI_TASK_RESUME_DLG="GetGecopResumeDlg";
    const AA_UI_TASK_DELETE_DLG="GetGecopDeleteDlg";
    const AA_UI_TASK_ADDNEW_DLG="GetGecopAddNewDlg";
    const AA_UI_TASK_MODIFY_DLG="GetGecopModifyDlg";
    const AA_UI_TASK_ADDNEWMULTI_DLG="GetGecopAddNewMultiDlg";
    //------------------------------------

    //Dialoghi
    
    //report
   
    //Section id
    const AA_ID_SECTION_CRITERI="Criteri";
    //section ui ids
    const AA_UI_DETAIL_GENERALE_BOX = "Generale_Box";

    const AA_UI_TEMPLATE_COMMISSIONE="Commissione";
    const AA_UI_TEMPLATE_RENDICONTO="Rendiconto";

    public function __construct($user=null,$bDefaultSections=true)
    {
        if(!($user instanceof AA_user))
        {
            $user=AA_User::GetCurrentUser();
        }

        parent::__construct($user,$bDefaultSections);

        #-------------------------------- Registrazione dei task -----------------------------
        $taskManager=$this->GetTaskManager();
        
        //Dialoghi di filtraggio
        $taskManager->RegisterTask("GetGecopPubblicateFilterDlg");
        $taskManager->RegisterTask("GetGecopBozzeFilterDlg");

        //dati
        $taskManager->RegisterTask("GetGecopModifyDlg");
        $taskManager->RegisterTask("GetGecopAddNewDlg");
        $taskManager->RegisterTask("GetGecopAddNewMultiDlg");
        $taskManager->RegisterTask("GetGecopAddNewMultiPreviewCalc");
        $taskManager->RegisterTask("GetGecopAddNewMultiPreviewDlg");
        $taskManager->RegisterTask("AddNewMultiGecop");
        $taskManager->RegisterTask("GetGecopAddNewMultiResultDlg");

        $taskManager->RegisterTask("GetGecopTrashDlg");
        $taskManager->RegisterTask("TrashGecop");
        $taskManager->RegisterTask("GetGecopDeleteDlg");
        $taskManager->RegisterTask("DeleteGecop");
        $taskManager->RegisterTask("GetGecopResumeDlg");
        $taskManager->RegisterTask("ResumeGecop");
        $taskManager->RegisterTask("GetGecopReassignDlg");
        $taskManager->RegisterTask("GetGecopPublishDlg");
        $taskManager->RegisterTask("ReassignGecop");
        $taskManager->RegisterTask("AddNewGecop");
        $taskManager->RegisterTask("UpdateGecopDatiGenerali");
        $taskManager->RegisterTask("PublishGecop");
        $taskManager->RegisterTask("GetGecopListaCodiciIstat");
        $taskManager->RegisterTask("GetGecopModifyRendicontoDlg");
        $taskManager->RegisterTask("UpdateGecopRendiconto");

        //commissione
        $taskManager->RegisterTask("GetGecopAddNewComponenteDlg");
        $taskManager->RegisterTask("AddNewGecopComponente");
        $taskManager->RegisterTask("GetGecopModifyComponenteDlg");
        $taskManager->RegisterTask("UpdateGecopComponente");
        $taskManager->RegisterTask("GetGecopTrashComponenteDlg");
        $taskManager->RegisterTask("DeleteGecopComponente");
        
        //template dettaglio
        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL,array(
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateGecopDettaglio_Generale_Tab")
        ));

        //$criteri=new AA_GenericModuleSection(static::AA_ID_SECTION_CRITERI,"Criteri e modalita'",true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_CRITERI,$this->GetId(),false,true,false,false,'mdi-text-box-multiple',"TemplateSection_Criteri");
        //$this->AddSection($criteri);

        //$pubblicate=$this->GetSection(static::AA_ID_SECTION_PUBBLICATE);
        //$pubblicate->SetNavbarTemplate(array($this->TemplateGenericNavbar_Bozze(1)->toArray(),$this->TemplateGenericNavbar_Section($criteri,2,true)->toArray()));

        //$criteri->SetNavbarTemplate(array($this->TemplateGenericNavbar_Atti(1,true,true)->toArray()));

        //Custom object template
        //$this->AddObjectTemplate(static::AA_UI_PREFIX."_".static::AA_UI_WND_RENDICONTI_COMUNALI."_".static::AA_UI_LAYOUT_RENDICONTI_COMUNALI,"Template_GetGecopComuneRendicontiViewLayout");
    }
    
    protected function TemplateGenericNavbar_Atti($level = 1, $last = false, $refresh_view = true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(
            "",
            array(
                "type" => "clean",
                "section_id" => static::AA_ID_SECTION_PUBBLICATE,
                "module_id" => $this->GetId(),
                "refresh_view" => $refresh_view,
                "tooltip" => "Fai click per visualizzare la sezione relativa agli atti di concessione",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" . static::AA_UI_PREFIX . "_Navbar_Link_" . static::AA_UI_SECTION_PUBBLICATE_NAME . "' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"".static::AA_ID_SECTION_PUBBLICATE."\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => "Atti di concessione", "icon" => "mdi mdi-certificate", "class" => $class)
            )
        );
        return $navbar;
    }
    //istanza
    protected static $oInstance=null;
    
    //Restituisce l'istanza corrente
    public static function GetInstance($user=null)
    {
        if(self::$oInstance==null)
        {
            self::$oInstance=new AA_GecopModule($user);
        }
        
        return self::$oInstance;
    }
    
    //Layout del modulo
    function TemplateLayout()
    {
        return $this->TemplateGenericLayout();
    }
    
    //Template placeholder
    public function TemplateSection_Placeholder()
    {
        return $this->TemplateGenericSection_Placeholder();
    }
    
    //Template pubblicate content
    public function TemplateSection_Pubblicate($params=array())
    {
        $bCanModify=false;
        if($this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $bCanModify=true;
        }

        $content=$this->TemplateGenericSection_Pubblicate($params,$bCanModify);
        $content->EnableExportFunctions(false);
        //$content->EnableTrash(false);

        return $content->toObject();
    }

    //Template pubblicate content
    public function TemplateSection_Bozze($params=array())
    {
        $bCanModify=false;
        if($this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $bCanModify=true;
        }

        $params['enableAddNewMultiFromCsv']=true;
        $content=$this->TemplateGenericSection_Bozze($params,null);

        return $content->toObject();
    }

    //Restituisce la lista delle schede pubblicate (dati)
    public function GetDataSectionPubblicate_List($params=array())
    {
        return $this->GetDataGenericSectionPubblicate_List($params,"GetDataSectionPubblicate_CustomFilter","GetDataSectionPubblicate_CustomDataTemplate");
    }

    //Personalizza il filtro delle schede pubblicate per il modulo corrente
    protected function GetDataSectionPubblicate_CustomFilter($params = array())
    {
        //tipo
        if(isset($params['tipo']) && $params['tipo'] > 0)
        {
            $params['where'][]=" AND ".AA_Gecop::AA_DBTABLE_DATA.".tipologia = '".addslashes($params['tipo'])."'";
        }

        //stato
        if(isset($params['stato']) && $params['stato'] > 0)
        {
            $params['where'][]=" AND ".AA_Gecop::AA_DBTABLE_DATA.".stato = '".addslashes($params['stato'])."'";
        }

        //comune
        if(isset($params['comune']) && $params['comune'] !="Qualunque")
        {
            $params['where'][]=" AND ".AA_Gecop::AA_DBTABLE_DATA.".geolocalizzazione like '{\"comune\":\"".addslashes($params['comune'])."\"%'";
        }

        return $params;
    }

     //Personalizza il template dei dati delle schede pubblicate per il modulo corrente
     protected function GetDataSectionPubblicate_CustomDataTemplate($data = array(),$object=null)
     {
        if($object instanceof AA_Gecop)
        {

            $data['pretitolo']=$object->GetProp("Anno");
            $tags="<span class='AA_DataView_Tag AA_Label AA_Label_LightYellow'>".$object->GetProp("Cig")."</span>";
            $data['tags']=$tags;
        }

        return $data;
     }

    //Restituisce i dati delle bozze
    public function GetDataSectionBozze_List($params=array())
    {
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP) && !$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP_RO))
        {
            AA_Log::Log(__METHOD__." - ERRORE: l'utente corrente: ".$this->oUser->GetUserName()." non è abilitato alla visualizzazione delle bozze.",100);
            return array();
        }

        return $this->GetDataGenericSectionBozze_List($params,"GetDataSectionBozze_CustomFilter","GetDataSectionBozze_CustomDataTemplate");
    }

    //Personalizza il filtro delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomFilter($params = array())
    {
        //tipo
        if(isset($params['tipo']) && $params['tipo'] > 0)
        {
            $params['where'][]=" AND ".AA_Gecop::AA_DBTABLE_DATA.".tipologia = '".addslashes($params['tipo'])."'";
        }

        //stato
        if(isset($params['stato']) && $params['stato'] > 0)
        {
            $params['where'][]=" AND ".AA_Gecop::AA_DBTABLE_DATA.".stato = '".addslashes($params['stato'])."'";
        }

        //comune
        if(isset($params['comune']) && $params['comune'] !="Qualunque")
        {
            $params['where'][]=" AND ".AA_Gecop::AA_DBTABLE_DATA.".geolocalizzazione like '{\"comune\":\"".addslashes($params['comune'])."\"%'";
        }

        return $params;
    }

    //Personalizza il template dei dati delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomDataTemplate($data = array(),$object=null)
    {
        
        if($object instanceof AA_Gecop)
        {

            $data['pretitolo']=$object->GetProp("Anno");
            $tags="<span class='AA_DataView_Tag AA_Label AA_Label_LightYellow'>".$object->GetProp("Cig")."</span>";
            $data['tags']=$tags;
        }

        return $data;
    }
    
    //Template publish dlg
    public function Template_GetGecopPublishDlg($params)
    {
        //lista organismi da pubblicare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Gecop($curId,$this->oUser);
                if($organismo->isValid() && ($organismo->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_PUBLISH)>0)
                {
                    $ids_final[$curId]=$organismo->GetDescr();
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

                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." provvedimenti/accordi verranno pubblicati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente elemento/accordo verrà pubblicato, vuoi procedere?")));

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
                $wnd->SetSaveTask('PublishGecop');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per pubblicare i provvedimenti/accordi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }
            
            return $wnd;
        }
    }
    
    //Template organismo delete dlg
    public function Template_GetGecopDeleteDlg($params)
    {
        return $this->Template_GetGenericObjectDeleteDlg($params,"DeleteGecop");
    }
        
    //Template dlg addnew
    public function Template_GetGecopAddNewDlg()
    {
        $id=$this->GetId()."_AddNew_Dlg_".uniqid();
        
        $form_data=array();

        $form_data['Note']="";
        $form_data['nome']="";
        $form_data['Cig']="";
        $form_data['Anno']=Date("Y");
        $form_data['links_atti']="";
        $form_data['aggiudicatario_nominativo']="";
        $form_data['aggiudicatario_cf']="";
        $form_data['gestione_finanziaria_data_inizio']="";
        $form_data['gestione_finanziaria_data_fine']="";
        $form_data['gestione_finanziaria_importo_aggiudicazione']="";
        $form_data['gestione_finanziaria_importo_liquidato']="";
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi un nuovo contratto", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(800);
        $wnd->SetHeight(480);
        $wnd->EnableValidation();

        //Cig
        $wnd->AddTextField("Cig","Cig",array("required"=>true,"gravity"=>2.5,"bottomPadding"=>32, "bottomLabel"=>"*Inseririe il cig relativo al contratto.", "placeholder"=>"..."));
        
        //Anno
        $wnd->AddTextField("Anno","Anno",array("required"=>true,"gravity"=>1,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci l'anno di riferimento.", "placeholder"=>"es. 2024"),false);

        //Note
        $label="Oggetto";
        $wnd->AddTextareaField("nome",$label,array("required"=>true,"bottomLabel"=>"*Oggetto del contratto.", "placeholder"=>"Inserisci qui l'oggetto..."));
        
        //link atti
        $wnd->AddTextField("links_atti","Url documenti",array("required"=>true,"gravity"=>1,"validateFunction"=>"IsUrl","bottomPadding"=>32,"bottomLabel"=>"*Inserisci l'url in cui e' pubblicata la documentazione prevista dalla normativa vigente in materia di contratti pubblici.", "placeholder"=>"..."));

        //$section=new AA_FieldSet($id."_Aggiudicatario","Aggiudicatario");
        //$section->AddTextField("aggiudicatario_nominativo","Nominativo",array("gravity"=>1,"bottomPadding"=>32, "bottomLabel"=>"*Inserire la ragione sociale o il nome e cognome dell'aggiudicatario.", "placeholder"=>"..."));
       // $section->AddTextField("aggiudicatario_cf","Cf/Piva",array("gravity"=>1,"bottomPadding"=>32, "bottomLabel"=>"*Inserire il codice fiscale o la partita iva dell'aggiudicatario.", "placeholder"=>"..."),false);

        //$wnd->AddGenericObject($section);

        //Note
        $label="Note";
        $wnd->AddTextareaField("Note",$label,array("bottomLabel"=>"*Eventuali annotazioni (max 4096 caratteri).", "placeholder"=>"Inserisci qui le note..."));
        
        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->SetSaveTask("AddNewGecop");
        
        return $wnd;
    }

    //Template dlg addnew geco da csv
    public function Template_GetGecopAddNewMultiDlg()
    {
        $id=static::AA_UI_PREFIX."_GetGecopAddNewMultiDlg_".uniqid();

        $form_data=array();

        $platform=AA_Platform::GetInstance($this->oUser);

        $wnd=new AA_GenericFormDlg($id, "Caricamento multiplo da file CSV", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();

        $descr="<ul>Il file csv deve avere le seguenti caratteristiche:";
        $descr.="<li>la prima riga deve contenere i nomi dei campi;</li>";
        $descr.="<li>la codifica dei caratteri deve essere in formato UTF-8;</li>";
        $descr.="<li>il carattere \"|\" (pipe) deve essere utilizzato come separatore dei campi;</li>";
        $descr.="</ul>";
        //$descr.="<p>Tramite il seguente <a href='".$platform->GetModulePathURL($this->GetId())."/docs/geco_addnew_multi.ods' target='_blank'>link</a> è possibile scaricare un foglio elettronico da utilizzarsi come base per la predisposizione del file csv.</p>";
        $descr.="<p>Per la generazione del file csv si consiglia l'utilizzo del software opensource <a href='https://www.libreoffice.org' target='_blank'>Libreoffice</a> in quanto consente di impostare il carattere di delimitazione dei campi e la codifica dei caratteri in fase di esportazione senza dover apportare modifiche al sistema.</p>";
        $descr.="<hr/>";

        $wnd->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","autoheight"=>true,"template"=>"<div style='margin-bottom: 1em;'>Questa funzionalità permette di generare più bozze tramite importazione da file csv.".$descr."</div>")));
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("height"=>30)));

        $options=array(
            array("id"=>1,"value"=>"Terna"),
            array("id"=>2,"value"=>"Ass. Industria"),
            array("id"=>3,"value"=>"Suape")
        );

        $wnd->AddSelectField("tracciato","Tipo tracciato",array("required"=>true,"validateFunction"=>"IsSelected","bottomPadding"=>32, "bottomLabel"=>"*Selezionare il tipo di importazione.", "placeholder"=>"...","options"=>$options));
        
        //csv
        $wnd->AddFileUploadField("GecopAddNewMultiCSV","Scegli il file csv...", array("required"=>true,"validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti in formato csv (dimensione max: 2Mb).","accept"=>"application/csv"));

        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->enableRefreshOnSuccessfulSave(false);

        $wnd->SetApplyButtonName("Procedi");

        $wnd->SetSaveTask("GetGecopAddNewMultiPreviewCalc");
        
        return $wnd;
    }

    //Template dlg addnew multi preview
    public function Template_GetGecopAddNewMultiPreviewDlg()
    {
        $id=static::AA_UI_PREFIX."_GetGecopAddNewMultiPreviewDlg_".uniqid();

        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Caricamento multiplo da file CSV - fase 2 di 3", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        //$wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(1280);
        $wnd->SetHeight(720);
        //$wnd->SetBottomPadding(36);
        //$wnd->EnableValidation();
        //anno	titolo	descrizione	responsabile	norma_estremi	norma_link	modalita_tipo	modalita_link	importo_impegnato	importo_erogato	beneficiario_nominativo	beneficiario_cf	beneficiario_piva	beneficiario_persona_fisica	beneficiario_privacy	note

        $columns=array(
            //array("id"=>"anno","header"=>array("<div style='text-align: center'>Anno</div>",array("content"=>"selectFilter")),"width"=>60, "css"=>array("text-align"=>"left"),"sort"=>"text"),
            array("id"=>"titolo","header"=>array("<div style='text-align: center'>Descrizione</div>",array("content"=>"textFilter")),"fillspace"=>true,"sort"=>"text","css"=>"PraticheTable_left"),
            array("id"=>"tipologia","header"=>array("<div style='text-align: center'>Tipologia</div>",array("content"=>"selectFilter")),"width"=>200, "sort"=>"text","css"=>"PraticheTable"),
            array("id"=>"potenza","header"=>array("<div style='text-align: center'>Potenza (MW)</div>",array("content"=>"textFilter")),"width"=>90, "sort"=>"text","css"=>"PraticheTable"),
            array("id"=>"comune","header"=>array("<div style='text-align: center'>Comune</div>",array("content"=>"selectFilter")),"width"=>250, "sort"=>"text","css"=>"PraticheTable"),
            array("id"=>"date","header"=>array("<div style='text-align: center'>Date</div>",array("content"=>"textFilter")),"width"=>250, "css"=>"PraticheTable_left"),
            array("id"=>"nuovo","header"=>array("<div style='text-align: center'>Nuovo</div>",array("content"=>"selectFilter")),"width"=>60, "css"=>"PraticheTable")
        );

        $data=AA_SessionVar::Get("GecopAddNewMultiFromCSV_ParsedData")->GetValue();

        if(!is_array($data))
        {
            AA_Log::Log(__METHOD__." - dati csv non validi: ".print_r($data,TRUE),100);
            $data=array();
        }

        $tipologiaImpianti=AA_Gecop_Const::GetListaTipoImpianti();
        $tabledata=array();
        $nuovi_inserimenti=0;
        foreach($data as $key=>$row)
        {
            if($row['id_impianto']!=0) 
            {
                $impianto=new AA_Gecop($row['id_impianto'],$this->oUser);
                $geolocalizzazione=$impianto->GetProp('geolocalizzazione');
                $tabledata[]=array(
                    "titolo"=>$impianto->GetProp('nome'),
                    "tipologia"=>$impianto->GetTipo(),
                    "potenza"=>AA_Utils::number_format($impianto->GetProp('Potenza'),2,",","."),
                    "comune"=>$geolocalizzazione['comune'],
                    "date"=>"<div style='display: flex;flex-direction: column;justify-content: space-between;align-items: start;font-size: smaller;'><div>autorizzazione: ".$impianto->GetProp('AnnoAutorizzazione')."</div><div>costruzione: ".$impianto->GetProp('AnnoCostruzione')."</div><div>esercizio: ".$impianto->GetProp('AnnoEsercizio')."</div><div>dismissione: ".$impianto->GetProp('AnnoDismissione')."</div></div>",
                    "nuovo"=>"No"
                );
            }
            else
            {
                $tabledata[]=array(
                    "titolo"=>$row['nome'],
                    "tipologia"=>$tipologiaImpianti[$row['Tipologia']],
                    "potenza"=>AA_Utils::number_format($row['Potenza'],2,",","."),
                    "comune"=>$row['Geo_comune'],
                    "date"=>"<div style='display: flex;flex-direction: column;justify-content: space-between;align-items: start;font-size: smaller;'><div>autorizzazione: ".$row['AnnoAutorizzazione']."</div><div>costruzione: ".$row['AnnoCostruzione']."</div><div>esercizio: ".$row['AnnoEsercizio']."</div><div>dismissione: ".$row['AnnoDismissione']."</div></div>",
                    "nuovo"=>"Si"
                );
                $nuovi_inserimenti++;
            }
        }
        //AA_Log::Log(__METHOD__." - dati csv: ".print_r($data,TRUE),100);

        $desc="<p>Sono stati riconosciuti <b>".$nuovi_inserimenti." nuovi impianti</b>.<br>Verranno aggiornati ".(sizeof((array)$data)-$nuovi_inserimenti)." impianti esistenti</p>";
        $wnd->AddGenericObject(new AA_JSON_Template_Template("",array("style"=>"clean","template"=>$desc,"autoheight"=>true)));

        $scrollview=new AA_JSON_Template_Generic($id."_ScrollCsvImportPreviewTable",array(
            "type"=>"clean",
            "view"=>"scrollview",
            "scroll"=>"x"
        ));
        $table=new AA_JSON_Template_Generic($id."_CsvImportPreviewTable", array(
            "view"=>"datatable",
            "css"=>"AA_Header_DataTable",
            "hover"=>"AA_DataTable_Row_Hover",
            "fixedRowHeight"=>false,
            "rowHeight"=>80,
            "rowLineHeight"=>18,
            "eventHandlers"=>array("onresize"=>array("handler"=>"adjustRowHeight","module_id"=>$this->GetId())),
            "columns"=>$columns,
            "data"=>array_values($tabledata)
        ));
        $scrollview->addRowToBody($table);

        $wnd->AddGenericObject($scrollview);

        $wnd->EnableCloseWndOnSuccessfulSave();

        //$wnd->enableRefreshOnSuccessfulSave();

        $wnd->SetApplyButtonName("Procedi");

        $wnd->SetSaveTask("AddNewMultiGecop");
        
        return $wnd;
    }

    //Visualizza il risultato dell'importazione da csv
    public function Template_GetGecopAddNewMultiResultDlg()
    {
        $id=$this->GetId()."_AddNewMultiResult_Dlg_".uniqid();
        
        $wnd=new AA_GenericWindowTemplate($id, "Caricamento multiplo da file CSV - fase 3 di 3");
        
        //$wnd->SetLabelAlign("right");
        //$wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(350);
        //$wnd->SetBottomPadding(36);
        //$wnd->EnableValidation();

        $data=AA_SessionVar::Get("GecopAddNewMultiResult")->GetValue();
        //elimina le variabili di sessione
        AA_SessionVar::UnsetVar("GecopAddNewMultiResult");

        if(!is_array($data))
        {
            AA_Log::Log(__METHOD__." - dati result non validi: ".print_r($data,TRUE),100);
            $data=array();
            $desc="Non sono stati trovati i risultati di importazione da csv.";
            $wnd->AddView(new AA_JSON_Template_Template("",array("style"=>"clean","template"=>$desc,"autoheight"=>true)));
            return $wnd;
        }

        $desc="<p>Sono stati inseriti <b>".$data[0]." nuovi impianti in bozza</b>.</p>";
        if($data[1] > 0) $desc.="<p>Sono stati modificati <b>".$data[1]." impianti pubblicati esistenti</b>.</p>";
        $wnd->AddView(new AA_JSON_Template_Template("",array("style"=>"clean","template"=>$desc,"autoheight"=>true)));
        
        return $wnd;
    }

    //Template confirm 
    public function Template_GetGecopConfirmPrivacyDlg($form_id='')
    {
        $id=$this->GetId()."_".uniqid();

        $wnd=new AA_GenericWindowTemplate($id, "Conferma oscuramento dati personali", $this->id);
        
        $wnd->SetWidth(540);
        $wnd->SetHeight(400);
        
        $layout=new AA_JSON_Template_Layout("",array("type"=>"clean"));

        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p><p style='padding:10px'>Dalla pubblicazione e' possibile ricavare, <u>anche solo potenzialmente</u>, informazioni relative allo <b>stato di salute</b> e/o alla situazione di <b>disagio economico-sociale</b> del beneficiario?</p></div>";
        $layout->AddRow(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));

        $flag_action='AA_MainApp.utils.callHandler("flagPrivacy", { form: "'.$form_id.'",value : 1}, "'.$this->GetId().'");$$("'.$id.'_Wnd").close();';
        $unflag_action='AA_MainApp.utils.callHandler("flagPrivacy", { form: "'.$form_id.'",value : 0}, "'.$this->GetId().'");$$("'.$id.'_Wnd").close();';

        $layout->AddRow(new AA_JSON_Template_Generic("",array("height"=>20)));
        $toolbar=new AA_JSON_Template_Toolbar("",array("type"=>"clean","borderless"=>true));

        //oscura i dati personali
        $flag_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
            "type"=>"icon",
            "icon"=>"mdi mdi-check-circle",
            "label"=>"Si",
            "css"=>"webix_primary",
            "align"=>"center",
            "inputWidth"=>80,
            "click"=>$flag_action,
            "tooltip"=>"Oscura i dati personali."
        ));

        //manuale operatore comunale
        $unflag_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
            "type"=>"icon",
            "icon"=>"mdi mdi-alert-circle",
            "label"=>"No",
            "align"=>"center",
            "inputWidth"=>80,
            "click"=>$unflag_action,
            "tooltip"=>"Lascia in chiaro i dati personali."
        ));

        $toolbar->addElement($unflag_btn);
        $toolbar->addElement(new AA_JSON_Template_Generic(""));
        $toolbar->addElement($flag_btn);
        $layout->AddRow($toolbar);
        $layout->AddRow(new AA_JSON_Template_Generic("",array("height"=>20)));

        $wnd->AddView($layout);
        
        return $wnd;
    }

    //Template dlg aggiungi allegato/link
    public function Template_GetGecopAddNewAllegatoDlg($object=null)
    {
        $id=uniqid();
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array();
        $wnd=new AA_GenericFormDlg($id, "Aggiungi allegato/link", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(520);

        //Tipo
        $options=array();
        $tipo_allegati=AA_Gecop_Const::GetListaTipoAllegati();
        foreach($tipo_allegati as $key=>$val)
        {
            $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("tipo", "Tipologia", array("gravity"=>1,"required"=>true,"validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere la tipologia di allegato/link", "placeholder" => "Scegli un elemento della lista...","options"=>$options));
        
        //Descrizione
        $wnd->AddTextField("descrizione", "Descrizione", array("gravity"=>1,"required"=>true,"bottomLabel" => "*Inserisci una breve descrizione dell'allegato/link","placeholder" => "es. DGR..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        //categorie
        /*$tipi=AA_Gecop_Const::GetCategorieAllegati();$curRow=1;
        $section=new AA_FieldSet($id."_Section_Tipo","Categorie");
        $curRow=0;
        foreach($tipi as $tipo=>$descr)
        {
            $newLine=false;
            if($curRow%4 == 0 && $curRow >= 4) $newLine=true;
            $section->AddCheckBoxField("tipo_".$tipo, $descr, array("value"=>1,"bottomPadding"=>8,"labelWidth"=>160),$newLine);
            $curRow++;
        }
        $wnd->AddGenericObject($section);
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        //----------------------
        */
        //file upload------------------
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        $section=new AA_FieldSet($id."_Section_Url","Inserire un'url oppure scegliere un file");

        //url
        $section->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $section->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<hr/>","height"=>18)));

        //file
        $section->AddFileUploadField("NewAllegatoDoc","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf o file zip (dimensione max: 30Mb).","accept"=>"application/pdf,application/zip"));
        
        $wnd->AddGenericObject($section);
        //---------------------------------

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("AddNewGecopAllegato");
        
        return $wnd;
    }

    //Template dlg aggiungi componente
    public function Template_GetGecopAddNewComponenteDlg($object=null)
    {
        $id=uniqid();
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        if(!($object instanceof AA_Gecop))
        {
            return new AA_GenericWindowTemplate(uniqid(),"Aggiungi nuovo componente di commissione",$this->GetId());
        }

        $form_data=array();
        $form_data['id']=$object->GetId();
        $wnd=new AA_GenericFormDlg($id, "Aggiungi nuovo componente commissione", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(800);
        $wnd->SetHeight(680);

        //Estremi
        $wnd->AddTextField("nominativo", "Nominativo", array("gravity"=>2,"required"=>true,"labelAlign"=>"right","bottomLabel" => "*Inserisci il nome e cognome del componente.","placeholder" => "es. Giulio Cesare..."));

        //cf
        $wnd->AddTextField("cf", "Cf", array("gravity"=>1,"labelWidth"=>60,"labelAlign"=>"right","bottomLabel" => "*Inserisci il cf del componente."),false);

        //ruolo
        $lista=AA_Gecop_Const::GetListaRuoloComponente();
        $options=array();
        foreach($lista as $idRuolo=>$ruolo)
        {
            $options[]=array("id"=>$idRuolo,"value"=>$ruolo);
        }
        $wnd->AddSelectField("ruolo","Ruolo",array("gravity"=>1,"required"=>true,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il ruolo del componente.","options"=>$options));

        //professione
        $wnd->AddTextField("professione", "Professione", array("gravity"=>1,"labelAlign"=>"right","bottomLabel" => "*Inserisci la professione o l'amministrazione di provenienza del componente."));

        //file upload------------------
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        $section=new AA_FieldSet($id."_Section_Url","Curriculum - Inserire un'url oppure scegliere un file");

        //url
        $section->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $section->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<hr/>","height"=>18)));

        //file
        $section->AddFileUploadField("NewAllegatoDoc","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf o file zip (dimensione max: 2Mb).","accept"=>"application/pdf,application/zip"));
        
        $wnd->AddGenericObject($section);
        //---------------------------------

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>20)));
        
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("AddNewGecopComponente");
        
        return $wnd;
    }

    //Template dlg aggiungi componente
    public function Template_GetGecopModifyComponenteDlg($object=null,$componente=null)
    {
        $id=uniqid();
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        if(!($object instanceof AA_Gecop) || !is_array($componente))
        {
            return new AA_GenericWindowTemplate(uniqid(),"Aggiungi nuovo componente di commissione",$this->GetId());
        }

        $form_data=array();
        $form_data['id']=$object->GetId();
        $form_data['id_componente']=$componente['id'];
        $form_data['nominativo']=$componente['nominativo'];
        $form_data['ruolo']=$componente['ruolo'];
        $form_data['cf']=$componente['cf'];
        $form_data['professione']=$componente['professione'];
        $form_data['url']=$componente['url'];

        $wnd=new AA_GenericFormDlg($id, "Modifica componente commissione", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(800);
        $wnd->SetHeight(680);

        //Estremi
        $wnd->AddTextField("nominativo", "Nominativo", array("gravity"=>2,"required"=>true,"labelAlign"=>"right","bottomLabel" => "*Inserisci il nome e cognome del componente.","placeholder" => "es. Giulio Cesare..."));

        //cf
        $wnd->AddTextField("cf", "Cf", array("gravity"=>1,"labelWidth"=>60,"labelAlign"=>"right","bottomLabel" => "*Inserisci il cf del componente."),false);

        //ruolo
        $lista=AA_Gecop_Const::GetListaRuoloComponente();
        $options=array();
        foreach($lista as $idRuolo=>$ruolo)
        {
            $options[]=array("id"=>$idRuolo,"value"=>$ruolo);
        }
        $wnd->AddSelectField("ruolo","Ruolo",array("gravity"=>1,"required"=>true,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il ruolo del componente.","options"=>$options));

        //professione
        $wnd->AddTextField("professione", "Professione", array("gravity"=>1,"labelAlign"=>"right","bottomLabel" => "*Inserisci la professione o l'amministrazione di provenienza del componente."));

        //file upload------------------
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        $section=new AA_FieldSet($id."_Section_Url","Curriculum - Inserire un'url oppure scegliere un file");

        //url
        $section->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $section->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<hr/>","height"=>18)));

        //file
        $section->AddFileUploadField("NewAllegatoDoc","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf o file zip (dimensione max: 2Mb).","accept"=>"application/pdf,application/zip"));
        
        $wnd->AddGenericObject($section);
        //---------------------------------

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>20)));
        
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateGecopComponente");
        
        return $wnd;
    }

    //Template dlg modifica pratica
    public function Template_GetGecopModifyPraticaDlg($object=null,$pratica=null)
    {
        $id=uniqid();
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        if(!($object instanceof AA_Gecop) || !is_array($pratica))
        {
            return new AA_GenericWindowTemplate(uniqid(),"Modifica pratica esistente",$this->GetId());
        }

        $form_data=array();
        $form_data['id']=$object->GetId();
        $form_data['id_pratica']=$pratica['id'];
        $form_data['stato']=$pratica['stato'];
        $form_data['tipo']=$pratica['tipo'];
        $form_data['estremi']=$pratica['estremi'];
        $form_data['descrizione']=$pratica['descrizione'];
        $form_data['via']=$pratica['via'];
        $form_data['societa']=$pratica['societa'];
        $form_data['data_inizio']=$pratica['data_inizio'];
        $form_data['data_fine']=$pratica['data_fine'];
        $form_data['note']=$pratica['note'];

        $wnd=new AA_GenericFormDlg($id, "Modifica pratica n. ".$pratica['id'], $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(980);
        $wnd->SetHeight(680);

        //tipo
        $options=array();
        $listaTipo=AA_Gecop_Const::GetListaTipoPratica();
        foreach($listaTipo as $key=>$val)
        {
            if($key > 0) $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("tipo","Tipo",array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il tipo di pratica.","options"=>$options));

        //stato
        $options=array();
        $listaTipo=AA_Gecop_Const::GetListaStatiPratica();
        foreach($listaTipo as $key=>$val)
        {
            if($key > 0) $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("stato","Stato",array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare lo stato della pratica.","options"=>$options),false);

        //Estremi
        $wnd->AddTextField("estremi", "Estremi", array("gravity"=>2,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Inserisci il numero e la data della pratica.","placeholder" => "es. prot. n.xx del xxxx/xx/xx..."));

        //via
        $options=array();
        $listaTipo=AA_Gecop_Const::GetListaTipoVia();
        foreach($listaTipo as $key=>$val)
        {
            if($key > 0) $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("via","Tipo VIA",array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il tipo di VIA.","options"=>$options),false);

        //descrizione
        $wnd->AddTextField("descrizione", "Descrizione", array("gravity"=>2,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Inserisci una breve descrizione (max 200 caratteri).","placeholder" => "..."));
        
        //societa'
        $wnd->AddTextField("societa", "Ragione sociale'", array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Inserisci la denominazione della societa' richiedente.","placeholder" => "..."));

        //riferimenti temporali
        $section=new AA_FieldSet($id."_Riferimenti","Riferimenti temporali");

        //data inizio
        $section->AddDateField("data_inizio","Data inizio",array("bottomPadding"=>32, "labelAlign"=>"right","labelWidth"=>150,"bottomLabel"=>"*Indica la data di inizio del procedimento."));
        //data fine
        $section->AddDateField("data_fine","Data fine",array("bottomPadding"=>32, "labelAlign"=>"right","labelWidth"=>150,"bottomLabel"=>"*Indica la data di conclusione del procedimento."),false);
        $wnd->AddGenericObject($section);

        //note
        $wnd->AddTextareaField("note", "Note", array("gravity"=>1,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*eventuali note (max 1024 caratteri).","placeholder" => "..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>20)));
        
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateGecopPratica");
        
        return $wnd;
    }

    //Template dlg copia pratica
    public function Template_GetGecopCopyPraticaDlg($object=null,$pratica=null)
    {
        $id=uniqid();
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        if(!($object instanceof AA_Gecop) || !is_array($pratica))
        {
            return new AA_GenericWindowTemplate(uniqid(),"Copia pratica esistente",$this->GetId());
        }

        $form_data=array();
        $form_data['id']=$object->GetId();
        $form_data['stato']=$pratica['stato'];
        $form_data['tipo']=$pratica['tipo'];
        $form_data['estremi']=$pratica['estremi'];
        $form_data['descrizione']=$pratica['descrizione'];
        $form_data['via']=$pratica['via'];
        $form_data['societa']=$pratica['societa'];
        $form_data['data_inizio']=$pratica['data_inizio'];
        $form_data['data_fine']=$pratica['data_fine'];
        $form_data['note']=$pratica['note'];

        $wnd=new AA_GenericFormDlg($id, "Copia pratica n. ".$pratica['id'], $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(980);
        $wnd->SetHeight(680);

        //tipo
        $options=array();
        $listaTipo=AA_Gecop_Const::GetListaTipoPratica();
        foreach($listaTipo as $key=>$val)
        {
            if($key > 0) $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("tipo","Tipo",array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il tipo di pratica.","options"=>$options));

        //stato
        $options=array();
        $listaTipo=AA_Gecop_Const::GetListaStatiPratica();
        foreach($listaTipo as $key=>$val)
        {
            if($key > 0) $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("stato","Stato",array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare lo stato della pratica.","options"=>$options),false);

        //Estremi
        $wnd->AddTextField("estremi", "Estremi", array("gravity"=>2,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Inserisci il numero e la data della pratica.","placeholder" => "es. prot. n.xx del xxxx/xx/xx..."));

        //via
        $options=array();
        $listaTipo=AA_Gecop_Const::GetListaTipoVia();
        foreach($listaTipo as $key=>$val)
        {
            if($key > 0) $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("via","Tipo VIA",array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il tipo di VIA.","options"=>$options),false);

        //descrizione
        $wnd->AddTextField("descrizione", "Descrizione", array("gravity"=>2,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Inserisci una breve descrizione (max 200 caratteri).","placeholder" => "..."));
        
        //societa'
        $wnd->AddTextField("societa", "Ragione sociale'", array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Inserisci la denominazione della societa' richiedente.","placeholder" => "..."));

        //riferimenti temporali
        $section=new AA_FieldSet($id."_Riferimenti","Riferimenti temporali");

        //data inizio
        $section->AddDateField("data_inizio","Data inizio",array("bottomPadding"=>32, "labelAlign"=>"right","labelWidth"=>150,"bottomLabel"=>"*Indica la data di inizio del procedimento."));
        //data fine
        $section->AddDateField("data_fine","Data fine",array("bottomPadding"=>32, "labelAlign"=>"right","labelWidth"=>150,"bottomLabel"=>"*Indica la data di conclusione del procedimento."),false);
        $wnd->AddGenericObject($section);

        //note
        $wnd->AddTextareaField("note", "Note", array("gravity"=>1,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*eventuali note (max 1024 caratteri).","placeholder" => "..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>20)));
        
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("AddNewGecopPratica");
        
        return $wnd;
    }

    //Template dlg modifca allegato/link
    public function Template_GetGecopModifyAllegatoDlg($object=null,$allegato=null)
    {
        $id=static::AA_UI_PREFIX."_GetGecopModifyAllegatoDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array();
        $form_data["descrizione"]=$allegato['descrizione'];
        $form_data["url"]=$allegato['url'];
        $form_data["tipo"]=$allegato['tipo'];

        $wnd=new AA_GenericFormDlg($id, "Modifica allegato/link", $this->id,$form_data,$form_data);

        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(520);

        //Tipo
        $options=array();
        $tipo_allegati=AA_Gecop_Const::GetListaTipoAllegati();
        foreach($tipo_allegati as $key=>$val)
        {
            $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("tipo", "Tipologia", array("gravity"=>1,"required"=>true,"validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere la tipologia di allegato/link", "placeholder" => "Scegli un elemento della lista...","options"=>$options));
        
        //Descrizione
        $wnd->AddTextField("descrizione", "Descrizione", array("gravity"=>1,"required"=>true,"bottomLabel" => "*Inserisci una breve descrizione dell'allegato/link","placeholder" => "es. DGR..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        //file upload------------------
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        $section=new AA_FieldSet($id."_Section_Url","Inserire un'url oppure scegliere un file");

        //url
        $section->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $section->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<hr/>","height"=>18)));

        //file
        $section->AddFileUploadField("NewAllegatoDoc","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf o file zip (dimensione max: 30Mb).","accept"=>"application/pdf,application/zip"));
        
        $wnd->AddGenericObject($section);
        //---------------------------------

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_allegato"=>$allegato['id']));
        $wnd->SetSaveTask("UpdateGecopAllegato");
        
        return $wnd;
    }

    //Template dlg trash componente
    public function Template_GetGecopTrashComponenteDlg($object=null,$componente=null)
    {
        $id=uniqid();
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina componente", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(400);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $ruoli=AA_Gecop_Const::GetListaRuoloComponente();
        $tabledata=array();
        $tabledata[]=array("descrizione"=>$componente['nominativo'],"ruolo"=>$ruoli[$componente['ruolo']]);
      
        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p></div>";
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente componente verra' rimosso, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"descrizione", "header"=>"Descrizione", "fillspace"=>true),
              array("id"=>"ruolo", "header"=>"ruolo", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteGecopComponente");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_componente"=>$componente['id']));
        
        return $wnd;
    }

    //Template dlg trash allegato
    public function Template_GetGecopTrashAllegatoDlg($object=null,$allegato=null)
    {
        $id=uniqid();
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina allegato", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $url=$allegato['url'];
        if($url =="") $url="file locale";
        $tabledata[]=array("descrizione"=>$allegato['descrizione'],"url"=>$url);
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente allegato/link verrà eliminato, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"descrizione", "header"=>"Descrizione", "fillspace"=>true),
              array("id"=>"url", "header"=>"Url", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteGecopAllegato");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_allegato"=>$allegato['id']));
        
        return $wnd;
    }

    //Task Aggiungi allegato
    public function Task_AddNewGecopAllegato($task)
    {        
        $uploadedFile = AA_SessionFileUpload::Get("NewAllegatoDoc");

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);
            
            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'], $this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")",false);

            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     

            return false;
        }
        
        if($object->IsReadOnly())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente (".$this->oUser->GetNome().") non ha i privileggi per modificare l'elemento: ".$object->GetName(),false);

            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     

            return false;            
        }

        if(!isset($_REQUEST['descrizione']) || $_REQUEST['descrizione'] == "")
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Parametri non validi occorre specificare una descrizione.",false);
            
            return false;
        }

        if(!isset($_REQUEST['tipo']) || $_REQUEST['tipo'] <= 0)
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Parametri non validi occorre specificare una tipo di allegato/link.",false);
            
            return false;
        }

        if(!$uploadedFile->isValid() && $_REQUEST['url'] == "")
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Parametri non validi occorre indicare un url o un file.",false);
            
            return false;
        }
        
        $fileHash="";
        if($uploadedFile->isValid()) 
        {
            //Se c'è un file uploadato l'url non viene salvata.
            $_REQUEST['url']="";

            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                $file=$uploadedFile->GetValue();
                $storageFile=$storage->Addfile($file['tmp_name'],$file['name'],$file['type'],1);
                if($storageFile->IsValid())
                {
                    $fileHash=$storageFile->GetFileHash();
                }
                else
                {
                    AA_Log::Log(__METHOD__." - errore nell'aggiunta allo storage. file non salvato.",100);
                }
            }
            else AA_Log::Log(__METHOD__." - storage non inizializzato. file non salvato.",100);

            //Elimina il file temporaneo
            if(file_exists($file['tmp_name']))
            {
                if(!unlink($file['tmp_name']))
                {
                    AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$file['tmp_name'],100);
                }
            }
        }

        $allegati=$object->GetAllegati();
        $allegati[uniqid()]=array(
            "descrizione"=>$_REQUEST['descrizione'],
            "tipo"=>$_REQUEST['tipo'],
            "url"=>$_REQUEST['url'],
            "filehash"=>$fileHash
        );

        if(sizeof($allegati) > 0)
        {
            $object->SetAllegati($allegati);

            if(!$object->Update($this->oUser,true,"Aggiunta allegato/link"))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Errore nell'aggiunta allegato/link.",false);

                return false;
            }
            else
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
                $task->SetContent("Allegato/link aggiunto con successo.",false);

                return true;
            }
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Nessun allegato o link da aggiungere.",false);
        return true;
    }

    //Task Aggiungi componente
    public function Task_AddNewGecopComponente($task)
    {        
        $uploadedFile = AA_SessionFileUpload::Get("NewAllegatoDoc");

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);
            
            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'], $this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")",false);

            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     

            return false;
        }
        
        if($object->IsReadOnly())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente (".$this->oUser->GetNome().") non ha i privileggi per modificare l'elemento: ".$object->GetName(),false);

            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     

            return false;            
        }

        if(empty(trim($_REQUEST['nominativo'])))
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre specificare il nominativo del componente.",false);
            
            return false;
        }

        if(empty($_REQUEST['ruolo']))
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre specificare il ruolo del componente",false);
            
            return false;
        }

        if(!$uploadedFile->isValid() && $_REQUEST['url'] == "")
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare un url o un file.",false);
            
            return false;
        }
        
        $fileHash="";
        if($uploadedFile->isValid()) 
        {
            //Se c'è un file uploadato l'url non viene salvata.
            $_REQUEST['url']="";

            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                $file=$uploadedFile->GetValue();
                $storageFile=$storage->Addfile($file['tmp_name'],$file['name'],$file['type'],1);
                if($storageFile->IsValid())
                {
                    $fileHash=$storageFile->GetFileHash();
                }
                else
                {
                    AA_Log::Log(__METHOD__." - errore nell'aggiunta allo storage. file non salvato.",100);
                }
            }
            else AA_Log::Log(__METHOD__." - storage non inizializzato. file non salvato.",100);

            //Elimina il file temporaneo
            if(file_exists($file['tmp_name']))
            {
                if(!unlink($file['tmp_name']))
                {
                    AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$file['tmp_name'],100);
                }
            }
        }

        $commissione=$object->GetCommissione();
        $commissione[uniqid()]=array(
            "nominativo"=>trim($_REQUEST['nominativo']),
            "ruolo"=>$_REQUEST['ruolo'],
            "cf"=>trim($_REQUEST['cf']),
            "professione"=>trim($_REQUEST['professione']),
            "url"=>$_REQUEST['url'],
            "filehash"=>$fileHash
        );

        if(sizeof($commissione) > 0)
        {
            $object->SetProp("Commissione",json_encode($commissione));

            if(!$object->Update($this->oUser,true,"Aggiunta componente commissione: ".$_REQUEST['nominativo']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Errore nell'aggiunta del componente.",false);

                return false;
            }
            else
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
                $task->SetContent("Componente aggiunto con successo.",false);

                return true;
            }
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Nessun componente da aggiungere.",false);
        return true;
    }

    //Task Aggiungi componente
    public function Task_UpdateGecopComponente($task)
    {        
        $uploadedFile = AA_SessionFileUpload::Get("NewAllegatoDoc");

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);
            
            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'], $this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")",false);

            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     

            return false;
        }
        
        if($object->IsReadOnly())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente (".$this->oUser->GetNome().") non ha i privileggi per modificare l'elemento: ".$object->GetName(),false);

            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     

            return false;            
        }

        if(empty(trim($_REQUEST['nominativo'])))
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre specificare il nominativo del componente.",false);
            
            return false;
        }

        if(empty($_REQUEST['ruolo']))
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre specificare il ruolo del componente",false);
            
            return false;
        }

        $commissione=$object->GetCommissione();
        if(empty($_REQUEST['id_componente']) || !isset($commissione[$_REQUEST['id_componente']]))
        {
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo componente non valido.",false);
            
            return false;
        }

        $componente=$commissione[$_REQUEST['id_componente']];

        if(!$uploadedFile->isValid() && empty($_REQUEST['url']) && empty($componente['filehash']))
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare un url o un file.",false);
            
            return false;
        }

        $fileHash=$componente['filehash'];
        $storage=AA_Storage::GetInstance($this->oUser);
        if(!empty($componente['filehash']) && !empty($_REQUEST['url']))
        {
            if($storage->IsValid())
            {
                if(!$storage->DelFile($componente['filehash']))
                {
                    AA_Log::Log(__METHOD__." - errore nell'eliminazione del file: ".$componente['filehash'],100);
                }

                $componente['filehash']=$fileHash="";
            }
        }

        if($uploadedFile->isValid()) 
        {
            //Se c'è un file uploadato l'url non viene salvata.
            $_REQUEST['url']="";

            if($storage->IsValid())
            {
                if(!empty($componente['filehash']))
                {
                    if(!$storage->DelFile($componente['filehash']))
                    {
                        AA_Log::Log(__METHOD__." - errore nell'eliminazione del file: ".$componente['filehash'],100);
                    }
                }

                $file=$uploadedFile->GetValue();
                $storageFile=$storage->Addfile($file['tmp_name'],$file['name'],$file['type'],1);
                if($storageFile->IsValid())
                {
                    $fileHash=$storageFile->GetFileHash();
                }
                else
                {
                    AA_Log::Log(__METHOD__." - errore nell'aggiunta allo storage. file non salvato.",100);
                }
            }
            else AA_Log::Log(__METHOD__." - storage non inizializzato. file non salvato.",100);

            //Elimina il file temporaneo
            if(file_exists($file['tmp_name']))
            {
                if(!unlink($file['tmp_name']))
                {
                    AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$file['tmp_name'],100);
                }
            }
        }

        $commissione=$object->GetCommissione();
        $commissione[$_REQUEST['id_componente']]=array(
            "nominativo"=>trim($_REQUEST['nominativo']),
            "ruolo"=>$_REQUEST['ruolo'],
            "cf"=>trim($_REQUEST['cf']),
            "professione"=>trim($_REQUEST['professione']),
            "url"=>trim($_REQUEST['url']),
            "filehash"=>$fileHash
        );

        if(sizeof($commissione) > 0)
        {
            $object->SetProp("Commissione",json_encode($commissione));

            if(!$object->Update($this->oUser,true,"Modifica componente commissione: ".$commissione[$_REQUEST['id_componente']]['nominativo']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Errore nell'aggiornamento del componente.",false);

                return false;
            }
            else
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
                $task->SetContent("Dati aggiornati con successo.",false);

                return true;
            }
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Nessun componente da aggiornare.",false);
        return true;
    }

    //Template dlg modify Gecop
    public function Template_GetGecopModifyDlg($object=null)
    {
        $id=$this->GetId()."_Modify_Dlg_".uniqid();
        if(!($object instanceof AA_Gecop)) return new AA_GenericWindowTemplate($id, "Modifica i dati generali dell'impianto", $this->id);

        $form_data=array();
        $form_data['id']=$object->GetId();
        $form_data['Note']=$object->GetProp("Note");
        $form_data['nome']=$object->GetName();
        $form_data['Cig']=$object->GetProp("Cig");
        $form_data['Anno']=$object->GetProp("Anno");

        $links=$object->GetLinks();
        $form_data['links_atti']="";
        if(!empty($links['atti'])) $form_data['links_atti']=$links['atti'];
        
        $wnd=new AA_GenericFormDlg($id, "Modifica contratto", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(800);
        $wnd->SetHeight(480);
        $wnd->EnableValidation();

        //Cig
        $wnd->AddTextField("Cig","Cig",array("required"=>true,"gravity"=>2.5,"bottomPadding"=>32, "bottomLabel"=>"*Inseririe il cig relativo al contratto.", "placeholder"=>"..."));
        
        //Anno
        $wnd->AddTextField("Anno","Anno",array("required"=>true,"gravity"=>1,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci l'anno di riferimento.", "placeholder"=>"es. 2024"),false);

        //Note
        $label="Oggetto";
        $wnd->AddTextareaField("nome",$label,array("required"=>true,"bottomLabel"=>"*Oggetto del contratto.", "placeholder"=>"Inserisci qui l'oggetto..."));
        
        //link atti
        $wnd->AddTextField("links_atti","Url documenti",array("required"=>true,"gravity"=>1,"validateFunction"=>"IsUrl","bottomPadding"=>32,"bottomLabel"=>"*Inserisci l'url in cui e' pubblicata la documentazione prevista dalla normativa vigente in materia di contratti pubblici.", "placeholder"=>"..."));

        //$section=new AA_FieldSet($id."_Aggiudicatario","Aggiudicatario");
        //$section->AddTextField("aggiudicatario_nominativo","Nominativo",array("gravity"=>1,"bottomPadding"=>32, "bottomLabel"=>"*Inserire la ragione sociale o il nome e cognome dell'aggiudicatario.", "placeholder"=>"..."));
       // $section->AddTextField("aggiudicatario_cf","Cf/Piva",array("gravity"=>1,"bottomPadding"=>32, "bottomLabel"=>"*Inserire il codice fiscale o la partita iva dell'aggiudicatario.", "placeholder"=>"..."),false);

        //$wnd->AddGenericObject($section);

        //Note
        $label="Note";
        $wnd->AddTextareaField("Note",$label,array("bottomLabel"=>"*Eventuali annotazioni (max 4096 caratteri).", "placeholder"=>"Inserisci qui le note..."));
        
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateGecopDatiGenerali");
        
        return $wnd;
    }

    //Template dlg modify Gecop
    public function Template_GetGecopModifyRendicontoDlg($object=null)
    {
        $id=$this->GetId()."_Modify_Dlg_".uniqid();
        if(!($object instanceof AA_Gecop)) return new AA_GenericWindowTemplate($id, "Modifica rendicontazione finanziaria", $this->id);

        $form_data=array();
        $form_data['id']=$object->GetId();

        $aggiudicatario=$object->GetAggiudicatario();
        $rendiconto=$object->GetGestioneFinanziaria();

        $form_data['aggiudicatario_nominativo']=$aggiudicatario['nominativo'];
        $form_data['aggiudicatario_cf']=$aggiudicatario['cf'];
        $form_data['data_inizio']=$rendiconto['data_inizio'];
        $form_data['data_fine']=$rendiconto['data_fine'];
        $form_data['data_fine_effettiva']=$rendiconto['data_fine_effettiva'];
        $form_data['importo_aggiudicazione']=AA_Utils::number_format($rendiconto['importo_aggiudicazione'],2,",",".");
        $form_data['importo_liquidato']=AA_Utils::number_format($rendiconto['importo_liquidato'],2,",",".");
        
        $wnd=new AA_GenericFormDlg($id, "Modifica rendicontazione finanziaria", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(480);
        $wnd->EnableValidation();

        //aggiudicatario
        $section=new AA_FieldSet($id."_Aggiudicatario","Aggiudicatario");
        $section->AddTextField("aggiudicatario_nominativo","Nominativo",array("gravity"=>1,"bottomPadding"=>32, "bottomLabel"=>"*Inserire la ragione sociale o il nome e cognome dell'aggiudicatario.", "placeholder"=>"..."));
        $section->AddTextField("aggiudicatario_cf","Cf/Piva",array("gravity"=>1,"bottomPadding"=>32, "bottomLabel"=>"*Inserire il codice fiscale o la partita iva dell'aggiudicatario.", "placeholder"=>"..."),false);
        $wnd->AddGenericObject($section);

        //gestione finanziario
        $section=new AA_FieldSet($id."_Dati_temporali","Riferimenti temporali");
        $section->AddDateField("data_inizio","Inizio esecuzione",array("gravity"=>1,"width"=>300,"labelWidth"=>130,"bottomPadding"=>32, "bottomLabel"=>"*Indicare la data di inizio esecuzione del contratto.", "placeholder"=>"..."));
        $section->AddDateField("data_fine","Conclusione (prevista)",array("gravity"=>1,"labelWidth"=>190,"bottomPadding"=>32, "bottomLabel"=>"*Indicare la data di conclusione prevista.", "placeholder"=>"..."),false);
        $section->AddDateField("data_fine_effettiva","Conclusione (effettiva)",array("gravity"=>1,"labelWidth"=>190,"bottomPadding"=>32, "bottomLabel"=>"*Indicare la data di conclusione effettiva.", "placeholder"=>"..."),false);
        $wnd->AddGenericObject($section);

        //importi
        $section=new AA_FieldSet($id."_Importi","Importi");
        $section->AddTextField("importo_aggiudicazione","Contrattuale",array("gravity"=>1,"bottomPadding"=>32, "bottomLabel"=>"*Inserire l'importo totale del contratto al netto dell'IVA.", "placeholder"=>"..."));
        $section->AddTextField("importo_liquidato","Liquidato",array("gravity"=>1,"bottomPadding"=>32, "bottomLabel"=>"*Inserire l'importo totale liquidato al netto dell'IVA.", "placeholder"=>"..."),false);
        $wnd->AddGenericObject($section);
       
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateGecopRendiconto");
        
        return $wnd;
    }

    //Template dlg modify beneficiario
    public function Template_GetGecopBeneficiarioModifyDlg($object=null)
    {
        $id=$this->GetId()."_Modify_Dlg";
        if(!($object instanceof AA_Gecop)) return new AA_GenericWindowTemplate($id, "Modifica i dati beneficiario", $this->id);

        $beneficiario=$object->GetBeneficiario();
        $form_data=array();
        $form_data['id']=$object->GetId();
        $form_data['Beneficiario_nome']=$beneficiario['nome'];
        $form_data['Beneficiario_cf']=$beneficiario['cf'];
        $form_data['Beneficiario_piva']=$beneficiario['piva'];
        $form_data['Beneficiario_tipo']=$beneficiario['tipo'];
        $form_data['Beneficiario_privacy']=$beneficiario['privacy'];

        $wnd=new AA_GenericFormDlg($id, "Modifica i dati beneficiario", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(820);
        $wnd->EnableValidation();
              
        //Beneficiario
        //$section=new AA_FieldSet($id."_Beneficario","Beneficiario");
        
        //Nome e cognome
        $wnd->AddTextField("Beneficiario_nome","Nominativo",array("required"=>true,"gravity"=>2,"bottomPadding"=>32, "bottomLabel"=>"*Inserisci il nominativo/ragione sociale (max 255 caratteri).", "placeholder"=>"es. Mario Rossi..."));
        
        //cf
        $wnd->AddTextField("Beneficiario_cf","C.F.",array("required"=>true, "gravity"=>1,"bottomPadding"=>32,"labelWidth"=>60,"bottomLabel"=>"*Inserisci il codice fiscale del beneficiario."),false);

        //piva
        $wnd->AddTextField("Beneficiario_piva","P.IVA",array("gravity"=>1,"labelWidth"=>60,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci la partita iva del beneficiario (se applicabile)."),false);

        //Tipo
        $wnd->AddCheckBoxField("Beneficiario_tipo"," ",array("bottomPadding"=>32,"labelWidth"=>60, "labelRight"=>"<b>Persona fisica/Ditta individuale/Libero professionista</b>", "gravity"=>1, "bottomLabel"=>"*Abilita se il beneficiario e' una persona fisica, una ditta individuale o un libero professionista.","eventHandlers"=>array("onChange"=>array("handler"=>"onPersonaFisicaChange","module_id"=>$this->GetId()))));

        //Privacy
        $wnd->AddCheckBoxField("Beneficiario_privacy"," ",array("bottomPadding"=>32,"gravity"=>1,"labelWidth"=>60,"labelRight"=>"<b>Oscuramento dati personali</b>", "bottomLabel"=>"*Abilita se dalla pubblicazione sia possibile ricavare informazioni relative allo stato di salute e alla situazione di disagio economico-sociale degli interessati."),false);

        //$wnd->AddGenericObject($section);
        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateGecopDatiBeneficiario");
        
        return $wnd;
    }

    public function Template_GetGecopHelpDlg()
    {
        $id=$this->GetId()."_Help_Dlg";
        
        $wnd=new AA_GenericWindowTemplate($id, "Aiuto", $this->id);
        
        $wnd->SetWidth(350);

        $platform=AA_Platform::GetInstance($this->oUser);
        $manualPath=$platform->GetModulePathURL($this->GetId())."/docs/manuale_oc.pdf";
        $action='AA_MainApp.utils.callHandler("pdfPreview", { url: "'.$manualPath.'" }, "'.$this->GetId().'");';

        $layout=new AA_JSON_Template_Layout($id."_Aiuto_box",array("type"=>"clean"));
        $layout->AddRow(new AA_JSON_Template_Generic("",array("height"=>20)));
        $toolbar_oc=new AA_JSON_Template_Toolbar($id."_ToolbarOC",array("type"=>"clean","borderless"=>true));

        //manuale operatore comunale
        $btn=new AA_JSON_Template_Generic($id."_Manuale_btn",array(
            "view"=>"button",
            "type"=>"icon",
            "icon"=>"mdi mdi-help-circle",
            "label"=>"Manuale caricamento risultati",
            "align"=>"center",
            "inputWidth"=>300,
            "click"=>$action,
            "tooltip"=>"Visualizza o scarica il manuale operatore comunale per iul caricamento dei risultati elettorali"
        ));

        $toolbar_oc->AddCol($btn);
        $layout->AddRow($toolbar_oc);

        $layout->AddRow(new AA_JSON_Template_Generic("",array("height"=>20)));

        $toolbar_oc=new AA_JSON_Template_Toolbar($id."_ToolbarOC",array("type"=>"clean","borderless"=>true));
        $manualPath=$platform->GetModulePathURL($this->GetId())."/docs/manuale_oc_rendiconti.pdf";
        $action='AA_MainApp.utils.callHandler("pdfPreview", { url: "'.$manualPath.'" }, "'.$this->GetId().'");';
        //manuale operatore comunale rendiconti
        $btn=new AA_JSON_Template_Generic($id."_ManualeRendiconti_btn",array(
            "view"=>"button",
            "type"=>"icon",
            "icon"=>"mdi mdi-help-circle",
            "label"=>"Manuale caricamento rendiconti",
            "align"=>"center",
            "inputWidth"=>300,
            "click"=>$action,
            "tooltip"=>"Visualizza o scarica il manuale operatore comunale per la compilazione dei rendiconti"
        ));

        $toolbar_oc->AddCol($btn);
        $layout->AddRow($toolbar_oc);

        $layout->AddRow(new AA_JSON_Template_Generic("",array("height"=>20)));

        $wnd->AddView($layout);        

        return $wnd;
    }

    //Template detail (da specializzare)
    public function TemplateSection_Detail($params)
    {
        //Gestione dei tab
        //$id=static::AA_UI_PREFIX."_Detail_Generale_Tab_".$params['id'];
        //$params['DetailOptionTab']=array(array("id"=>$id, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateGecopDettaglio_Generale_Tab"));
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP)) $params['readonly']=true;
        
        $params['MultiviewEventHandlers']=array("onViewChange"=>array("handler"=>"onDetailViewChange"));

        //$params['disable_SaveAsPdf']=true;
        //$params['disable_SaveAsCsv']=true;
        //$params['disable_trash']=true;
        //$params['disable_public_trash']=true;

        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP)) $params['disable_MenuAzioni']=true;
        
        $detail = $this->TemplateGenericSection_Detail($params);

        return $detail;
    }

    //lista pratiche
    public function TemplateDettaglio_Commissione($object=null,$canModify=false)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_TEMPLATE_COMMISSIONE;

        #commissione----------------------------------
        $commissione=$object->GetCommissione();
        $listaRuolo=AA_Gecop_Const::GetListaRuoloComponente();
        $commissione_data=array();
        foreach($commissione as $id_componente=>$curComponente)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if($curComponente['filehash'] != "")
            {
                $view='AA_MainApp.utils.callHandler("pdfPreview", {url: "storage.php?object='.$curComponente['filehash'].'"},"'.$this->id.'")';
                $view_icon="mdi-floppy";
            }
            else 
            {
                $view='AA_MainApp.utils.callHandler("wndOpen", {url: "'.$curComponente['url'].'"},"'.$this->id.'")';
                $view_icon="mdi-eye";
            }

            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopTrashComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopModifyComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
            $copy='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopCopyComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
            if($canModify) $ops="<div class='AA_DataTable_Ops' style='justify-content: space-between;width: 100%'><a class='AA_DataTable_Ops_Button' title='Vedi' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Copia' onClick='".$copy."'><span class='mdi mdi-content-copy'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $ops="<div class='AA_DataTable_Ops' style='justify-content: space-between;width: 100%'><a class='AA_DataTable_Ops_Button' title='Vedi' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a></div>";

            $commissione_data[]=array("id"=>$id_componente,"nominativo"=>$curComponente['nominativo'],"ruolo"=>$listaRuolo[$curComponente['ruolo']],"cf"=>$curComponente['cf'],"professione"=>$curComponente['professione'],"ops"=>$ops);
        }

        $numCols=4;
        if($canModify) $numCols=5;
        $template=new AA_GenericDatatableTemplate($id,"<span style='color:#003380'>Composizione commissione aggiudicatrice</span>",$numCols,null,array("css"=>"AA_Header_DataTable"));
        $template->SetHeaderCss(array("background-color"=>"#dadee0 !important"));
        $template->SetAddNewBtnCss("webix_primary");
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader();
        $template->SetHeaderHeight(38);

        if($canModify) 
        {
            $template->EnableAddNew(true,"GetGecopAddNewComponenteDlg");
            $template->SetAddNewBtnTooltip("Aggiungi un nuovo componente");
            $template->SetAddNewTaskParams(array("postParams"=>array("id"=>$object->GetId())));
        }

        $template->SetColumnHeaderInfo(0,"nominativo","<div style='text-align: center'>Nominativo</div>","fillspace","textFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(1,"ruolo","<div style='text-align: center'>Ruolo</div>",120,"selectFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(2,"cf","<div style='text-align: center'>Cf</div>",120,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(3,"professione","<div style='text-align: center'>Professione</div>","fillspace","textFilter","text","GenericAutosizedRowTable");
        if($canModify) $template->SetColumnHeaderInfo(4,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"GenericAutosizedRowTable");

        $template->SetData($commissione_data);

        return $template;
    }

    //lista pratiche
    public function TemplateDettaglio_RendicontoFinanziario($object=null,$canModify=false)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_TEMPLATE_RENDICONTO;
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean","css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        //-------------------- Rendiconto finanziario --------------------------------------
        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("width"=>120)));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"<span style='color:#003380'>Rendicontazione gestione finanziaria</span>", "align"=>"center")));
        
        if($canModify)
        {
            $revision_btn=new AA_JSON_Template_Generic("",array(
                "view"=>"button",
                 "type"=>"icon",
                 "icon"=>"mdi mdi-pencil",
                 "label"=>"Modifica",
                 "align"=>"right",
                 "autowidth"=>true,
                 "tooltip"=>"Modifica i dati della rendicontazione finanziaria",
                 "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetGecopModifyRendicontoDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
             ));
             $toolbar->AddElement($revision_btn);
        }
        else
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("width"=>120)));
        }
        $layout->AddRow($toolbar);

        $riga=new AA_JSON_Template_Layout("",array("height"=>48,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        $gestione_finanziaria=$object->GetGestioneFinanziaria();
        $aggiudicatario=$object->GetAggiudicatario();

        //aggiudicatario
        $value="n.d.";
        if(!empty($aggiudicatario['nominativo'])) $value=$aggiudicatario['nominativo'];
        if(!empty($aggiudicatario['cf'])) $value.=" - ".$aggiudicatario['cf'];
        $aggiudicatario_text=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Aggiudicatario:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));
        $riga->addCol($aggiudicatario_text);
        $layout->addRow($riga);

        $riga=new AA_JSON_Template_Layout("",array("height"=>48,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        //data inizio
        $value="n.d.";
        if(!empty($gestione_finanziaria['data_inizio'])) $value=$gestione_finanziaria['data_inizio'];
        $data_inizio=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Data inizio esecuzione:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        $riga->addCol($data_inizio);

        //data fine
        $value="n.d.";
        if(!empty($gestione_finanziaria['data_fine'])) $value=$gestione_finanziaria['data_fine'];
        $data_fine=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Data conclusione prevista:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));
        $riga->AddCol($data_fine);

        //data fine effettiva
        $value="n.d.";
        if(!empty($gestione_finanziaria['data_fine_effettiva'])) $value=$gestione_finanziaria['data_fine_effettiva'];
        $data_fine_effettiva=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Data conclusione effettiva:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));
        $riga->AddCol($data_fine_effettiva);
        $layout->addRow($riga);


        //seconda riga
        $riga=new AA_JSON_Template_Layout("",array("height"=>48,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        //importo aggiudicazione
        $value="n.d.";
        if(!empty($gestione_finanziaria['importo_aggiudicazione'])) $value=AA_Utils::number_format($gestione_finanziaria['importo_aggiudicazione'],2,",",".");
        $importo_aggiudicazione=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Importo del contratto:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));
        $riga->addCol($importo_aggiudicazione);

        //importo liquidato
        $value="0,00";
        $value=AA_Utils::number_format($gestione_finanziaria['importo_liquidato'],2,",",".");
        $importo_liquidato=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Importo liquidato:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));
        $riga->addCol($importo_liquidato);

        //scostamento
        $value="n.d.";
        if(!empty($gestione_finanziaria['data_fine_effettiva']))
        {
            $data_inizio=new DateTime($gestione_finanziaria['data_inizio']);
            $data_fine=new DateTime($gestione_finanziaria['data_fine']);
            if(!empty($gestione_finanziaria['data_fine_effettiva'])) $data_fine=new DateTime($gestione_finanziaria['data_fine_effettiva']);

            $value="gg: ".date_diff($data_inizio, $data_fine)->days;

            if(!empty($gestione_finanziaria['importo_liquidato']))
            {
                $value.=" - &euro;: ".AA_Utils::number_format(floatVal($gestione_finanziaria['importo_liquidato']-$gestione_finanziaria['importo_aggiudicazione']),2,",",".");
            }
        }

        $scostamento=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Scostamento temporale e finanziario:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        $riga->addCol($scostamento);
        $layout->addRow($riga);
        $layout->addRow(new AA_JSON_Template_Generic());

        return $layout;
    }
    
    //Template section detail, tab generale
    public function TemplateGecopDettaglio_Generale_Tab($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX;

        if(!($object instanceof AA_Gecop)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));

        $rows_fixed_height=50;
        $canModify=false;
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0 && $this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP)) $canModify=true;

        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>32,"type"=>"clean","borderless"=>true));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("width"=>120)));
        //$toolbar->AddElement(new AA_JSON_Template_Generic());

        $toolbar->AddElement(new AA_JSON_Template_Generic());
        /*if(($object->GetStatus()&AA_Const::AA_STATUS_PUBBLICATA)>0)
        {
            $revision_btn=new AA_JSON_Template_Generic("",array(
                "view"=>"button",
                 "type"=>"icon",
                 "icon"=>"mdi mdi-table-eye",
                 "label"=>"Revisioni",
                 "align"=>"right",
                 "autowidth"=>true,
                 "tooltip"=>"Visualizza i dati di revisione",
                 "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetGecopRevisionViewDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
             ));
             $toolbar->AddElement($revision_btn);
        }
        else*/
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("width"=>120)));
        }

        $layout=$this->TemplateGenericDettaglio_Header_Generale_Tab($object,$id,$toolbar,$canModify);
        
        //anno
        $value="<span class='AA_Label AA_Label_Blue_Simo'>".$object->GetProp("Anno")."</span>";
        $anno=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Anno:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //cig
        $value="<span class='AA_Label AA_Label_LightYellow'>".$object->GetProp("Cig")."</span>";
        $cig=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Cig:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //url documenti
        $links=$object->GetLinks();
        $value="<a href='".$links['atti']."' target='_blank'>consulta...</a>";
        $docs=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Documentazione:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //url bdncp
        $value="<a href='https://dati.anticorruzione.it/superset/dashboard/dettaglio_cig/?cig=".$object->GetProp("Cig")."&standalone=2' target='_blank'>consulta...</a>";
        $bdncp=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Link BDNCP:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //note
        $value = $object->GetProp("Note");
        $note=new AA_JSON_Template_Template($id."_Note",array(
            "maxHeight"=>100,
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Note:","value"=>$value)
        ));
        
        //prima riga
        $riga=new AA_JSON_Template_Layout("",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($anno);
        $riga->AddCol($cig);
        $riga->AddCol($docs);
        $riga->AddCol($bdncp);
        $layout->AddRow($riga);
        $layout->AddRow($note);

        //seconda riga
        $riga=new AA_JSON_Template_Layout("",array("gravity"=>1,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        //$layout_left=new AA_JSON_Template_Layout("",array("gravity"=>1,"type"=>"clean"));
        $layout_right=new AA_JSON_Template_Layout("",array("gravity"=>1,"type"=>"clean"));
        //$layout_gen->addRow($nome);
        $riga->addCol($this->TemplateDettaglio_Commissione($object,$canModify));
        //$riga->addCol($layout_left);

        $layout_right->addRow($this->TemplateDettaglio_RendicontoFinanziario($object,$canModify));
        $riga->addCol($layout_right);

        $layout->AddRow($riga);

        //-------------------- Allegati --------------------------------------
        //$allegati_box->AddRow($this->TemplateDettaglio_Allegati($object,$id,$canModify));
        //$riga->AddCol($allegati_box);
        //------------------------------------------------------------------------
      
        return $layout;
    }

    //Template section detail, tab generale
    public function TemplateGecopDettaglio_Allegati_Tab($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_ALLEGATI_BOX;

        if(!($object instanceof AA_Gecop)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
        $rows_fixed_height=50;
        $canModify=false;
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;

        #documenti----------------------------------
        $curId=$id;
        $layout=new AA_JSON_Template_Layout($curId,array("type"=>"clean","gravity"=>4));

        $toolbar=new AA_JSON_Template_Toolbar($curId."_Toolbar_allegati",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

        $toolbar->AddElement(new AA_JSON_Template_Generic($curId."_Toolbar_Allegati_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Documenti</span>", "align"=>"center")));

        if($canModify)
        {
            //Pulsante di aggiunta documento
            $add_documento_btn=new AA_JSON_Template_Generic($curId."_AddAllegato_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-file-plus",
                "label"=>"Aggiungi",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi allegato o link",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetGecopAddNewAllegatoDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));

            $toolbar->AddElement($add_documento_btn);
        }
        else 
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        }

        $layout->AddRow($toolbar);

        $options_documenti=array();

        if($canModify)
        {
            $options_documenti[]=array("id"=>"ordine","header"=>array("<div style='text-align: center'>n.</div>",array("content"=>"textFilter")),"width"=>60, "css"=>array("text-align"=>"center"),"sort"=>"int");
            $options_documenti[]=array("id"=>"aggiornamento","header"=>array("<div style='text-align: center'>Data</div>",array("content"=>"textFilter")),"width"=>100, "css"=>array("text-align"=>"left"),"sort"=>"text");
            $options_documenti[]=array("id"=>"tipoDescr","header"=>array("<div style='text-align: center'>Categorie</div>",array("content"=>"textFilter")),"width"=>300, "css"=>array("text-align"=>"center"),"sort"=>"text");
            $options_documenti[]=array("id"=>"destinatariDescr","header"=>array("<div style='text-align: center'>Destinatari</div>",array("content"=>"textFilter")),"width"=>300, "css"=>array("text-align"=>"center"),"sort"=>"text");
            $options_documenti[]=array("id"=>"estremi","header"=>array("<div style='text-align: center'>Descrizione</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text");
            $options_documenti[]=array("id"=>"ops", "header"=>"operazioni", "width"=>120,"css"=>array("text-align"=>"center"));
        }
        else
        {
            $options_documenti[]=array("id"=>"ordine","header"=>array("<div style='text-align: center'>n.</div>",array("content"=>"textFilter")),"width"=>60, "css"=>array("text-align"=>"center"),"sort"=>"int");
            $options_documenti[]=array("id"=>"aggiornamento","header"=>array("<div style='text-align: center'>Data</div>",array("content"=>"textFilter")),"width"=>100, "css"=>array("text-align"=>"left"),"sort"=>"text");
            $options_documenti[]=array("id"=>"tipoDescr","header"=>array("<div style='text-align: center'>Categorie</div>",array("content"=>"textFilter")),"width"=>300, "css"=>array("text-align"=>"center"),"sort"=>"text");
            $options_documenti[]=array("id"=>"destinatariDescr","header"=>array("<div style='text-align: center'>Destinatari</div>",array("content"=>"textFilter")),"width"=>300, "css"=>array("text-align"=>"center"),"sort"=>"text");
            $options_documenti[]=array("id"=>"estremi","header"=>array("<div style='text-align: center'>Descrizione</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text");
            $options_documenti[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
        }

        $documenti=new AA_JSON_Template_Generic($curId."_Allegati_Table",array("view"=>"datatable", "select"=>true,"scrollX"=>false,"css"=>"AA_Header_DataTable","hover"=>"AA_DataTable_Row_Hover","columns"=>$options_documenti));

        $storage=AA_Storage::GetInstance();

        $documenti_data=array();
        foreach($object->GetAllegati() as $id_doc=>$curDoc)
        {
            if($curDoc->GetUrl() == "")
            {
                $view='AA_MainApp.utils.callHandler("wndOpen", {url: "storage.php?object='.$curDoc->GetFileHash().'"},"'.$this->id.'")';
                $view_icon="mdi-floppy";
                $tip="Scarica";

                if($storage->IsValid())
                {
                    $file=$storage->GetFileByHash($curDoc->GetFileHash());
                    if($file->IsValid())
                    {
                        if(strpos($file->GetmimeType(),"pdf",0) !==false)
                        {
                            $view='AA_MainApp.utils.callHandler("pdfPreview", {url: "storage.php?object='.$curDoc->GetFileHash().'"},"'.$this->id.'")';
                            $view_icon="mdi-eye";
                            $tip="Consulta";
                        }
                    }
                }
            }
            else 
            {
                $view='AA_MainApp.utils.callHandler("wndOpen", {url: "'.$curDoc->GetUrl().'"},"'.$this->id.'")';
                $view_icon="mdi-eye";
                $tip="Naviga (in un'altra finestra)";
            }
            
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopTrashAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopModifyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            $copy='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopCopyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            if($canModify) $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='".$tip."' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Copia' onClick='".$copy."'><span class='mdi mdi-content-copy'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $ops="<div class='AA_DataTable_Ops' style='justify-content: center'><a class='AA_DataTable_Ops_Button' title='".$tip."' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a></div>";
            $docDestinatari=array();
            foreach($curDoc->GetDestinatariDescr(true) as $curDestinatario)
            {
                $docDestinatari[]="<span class='AA_Label AA_Label_LightGreen'>".$curDestinatario."</span>";
            }
            $docTipo=array();
            foreach($curDoc->GetTipoDescr(true) as $curTipo)
            {
                $docTipo[]="<span class='AA_Label AA_Label_LightGreen'>".$curTipo."</span>";
            }
            
            $documenti_data[]=array("id"=>$id_doc,"ordine"=>$curDoc->GetOrdine(),"destinatariDescr"=>implode("&nbsp;",$docDestinatari),"estremi"=>$curDoc->GetEstremi(),"tipoDescr"=>implode("&nbsp;",$docTipo),"tipo"=>$curDoc->GetTipo(),"aggiornamento"=>$curDoc->GetAggiornamento(),"ops"=>$ops);
        }
        $documenti->SetProp("data",$documenti_data);
        if(sizeof($documenti_data) > 0) $layout->AddRow($documenti);
        else $layout->AddRow(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        #--------------------------------------
        
        return $layout;
    }

    //Task Update Gecop
    public function Task_UpdateGecopDatiGenerali($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        //----------- verify values ---------------------
        if(trim($_REQUEST['nome']) == "")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'oggetto non puo' essere vuoto o composto da soli spazi.",false);

            return false;
        }

        //date
        if(!isset($_REQUEST['Anno']) || intval($_REQUEST['Anno']) < 2024 || intval($_REQUEST['Anno']) > 2099)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Anno di riferimento non valido.",false);

            return false;
        }

        if(!isset($_REQUEST['Cig']) || !AA_Gecop::CigIsValid(trim($_REQUEST['Cig']),$_REQUEST['id']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Cig non valido o gia' presente.",false);

            return false;
        }
        
        $links=array();
        if(!empty($_REQUEST['links_atti'])) $links['atti']=trim($_REQUEST['links_atti']); 
        $_REQUEST['Links']=json_encode($links);
        //----------------------------------------------------------

        $object->Parse($_REQUEST);

        if(!$object->Update($this->oUser,true,"Aggiornamento dati generali"))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dei dati generali.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }

    //Task Update Gecop
    public function Task_UpdateGecopDatiBeneficiario($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        $beneficiario=array();

        //----------- verify values ---------------------
        if(isset($_REQUEST['Beneficiario_nome'])) $beneficiario['nome']=trim($_REQUEST['Beneficiario_nome']);
        if(isset($_REQUEST['Beneficiario_cf'])) $beneficiario['cf']=trim($_REQUEST['Beneficiario_cf']);

        if(trim($_REQUEST['Beneficiario_nome']) == "" || trim($_REQUEST['Beneficiario_cf']) =="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Il nome e il codice fiscale del beneficiario non possono essere vuoti o composti da soli spazi.",false);

            return false;
        }
        if(isset($_REQUEST['Beneficiario_piva'])) $beneficiario['piva']=trim($_REQUEST['Beneficiario_piva']);
        if(isset($_REQUEST['Beneficiario_tipo'])) $beneficiario['tipo']=intVal($_REQUEST['Beneficiario_tipo']);
        if(isset($_REQUEST['Beneficiario_privacy'])) $beneficiario['privacy']=intVal($_REQUEST['Beneficiario_privacy']);

        //Se il beneficiario non e' una persona fisica disabilita l'oscuramento dei dati personali.
        if($beneficiario['tipo'] == 0) 
        {
            $beneficiario['privacy'] = 0;
        }
        //-----------------------------------------------
        
        $_REQUEST['Beneficiario']=json_encode($beneficiario);
        $object->Parse($_REQUEST);

        if(!$object->Update($this->oUser,true,"Aggiornamento dati beneficiario"))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dei dati beneficiario.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }
    
    //Task trash Gecop
    public function Task_TrashGecop($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetError("L'utente corrente non ha i permessi per cestinare l'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per cestinare l'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        return $this->Task_GenericTrashObject($task,$_REQUEST);
    }
    
    //Task resume Gecop
    public function Task_ResumeGecop($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericResumeObject($task,$_REQUEST);
    }
    
    //Task publish Gecop
    public function Task_PublishGecop($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        //torna alla lista
        //$_REQUEST['goBack']=1;

        return $this->Task_GenericPublishObject($task,$_REQUEST);
    }
    
    //Task reassign Gecop
    public function Task_ReassignGecop($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        return $this->Task_GenericReassignObject($task,$_REQUEST);
    }
    
    //Task delete Gecop
    public function Task_DeleteGecop($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
         
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetError("L'utente corrente non ha i permessi per eliminare l'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per eliminare l'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        return $this->Task_GenericDeleteObject($task,$_REQUEST);
    }
    
    //Task Aggiungi provvedimenti
    public function Task_AddNewGecop($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi",false);

            return false;
        }
        
        //----------- verify values ---------------------
        if(trim($_REQUEST['nome']) == "")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'oggetto non puo' essere vuoto o composto da soli spazi.",false);

            return false;
        }

        //date
        if(!isset($_REQUEST['Anno']) || intval($_REQUEST['Anno']) < 2024 || intval($_REQUEST['Anno']) > 2099)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Anno di riferimento non valido.",false);

            return false;
        }

        if(!isset($_REQUEST['Cig']) || !AA_Gecop::CigIsValid(trim($_REQUEST['Cig'])))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Cig non valido o gia' presente.",false);

            return false;
        }
        
        $links=array();
        if(!empty($_REQUEST['links_atti'])) $links['atti']=trim($_REQUEST['links_atti']); 
        $_REQUEST['Links']=json_encode($links);

        return $this->Task_GenericAddNew($task,$_REQUEST);
    }

    //Task Aggiungi nuovi impianti da csv
    public function Task_AddNewMultiGecop($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi",false);

            return false;
        }
        
        $data=AA_SessionVar::Get("GecopAddNewMultiFromCSV_ParsedData")->GetValue();
        AA_SessionVar::UnsetVar("GecopAddNewMultiFromCSV_ParsedData");

        if(!is_array($data))
        {
            AA_Log::Log(__METHOD__." - dati csv non validi: ".print_r($data,TRUE),100);

            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Dati CSV non validi",false);

            return false;
        }

        $count=array("inseriti"=>0,"modificati"=>0);

        foreach($data as $curData)
        {
            if($curData['id_impianto'] > 0)
            {
                if($curData['societa'] !="")
                {
                    $impianto=new AA_Gecop($curData['id_impianto'],$this->oUser);
                    if($impianto->IsValid())
                    {
                        $pratiche=$impianto->GetPratiche();
                        $pratica=array();
                        if(isset($curData['pratica_tipo'])) $pratica["tipo"]=$curData['pratica_tipo'];
                        else $pratica["tipo"]=AA_Gecop_Const::AA_GECOP_TIPO_PRATICA_AU;
                        if(isset($curData['pratica_stato'])) $pratica["stato"]=$curData['pratica_stato'];
                        else $pratica["stato"]=AA_Gecop_Const::AA_GECOP_STATO_PRATICA_DAISTRUIRE;
                        if(isset($curData['pratica_estremi'])) $pratica["estremi"]=$curData['pratica_estremi'];
                        else $pratica["estremi"]='';
                        if(isset($curData['pratica_descrizione'])) $pratica["descrizione"]=$curData['pratica_descrizione'];
                        else $pratica["descrizione"]='';
                        if(isset($curData['pratica_via'])) $pratica["via"]=$curData['pratica_via'];
                        else $pratica["via"]=AA_Gecop_Const::AA_GECOP_TIPO_VIA_NESSUNO;
                        if(isset($curData['pratica_societa'])) $pratica["societa"]=trim($curData['pratica_societa']);
                        else $pratica["societa"]='';
                        if(isset($curData['pratica_data_inizio'])) $pratica["data_inizio"]=$curData['pratica_data_inizio'];
                        else $pratica["data_inizio"]='';
                        if(isset($curData['pratica_data_fine'])) $pratica["data_fine"]=$curData['pratica_data_fine'];
                        else $pratica["data_fine"]='';
                        if(isset($curData['pratica_note'])) $pratica["note"]=$curData['pratica_note'];
                        else $pratica["note"]='';
                        $newId=uniqid();
                        $pratiche[$newId]=$pratica;
                        $impianto->SetProp('pratiche',json_encode($pratiche));
                        
                        if(!$impianto->Update($this->oUser,true,"Aggiunta pratica - id: ".$newId))
                        {
                            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                            $task->SetError("Errore nell'aggiunta della nuova pratica all'impianto: ".$impianto->GetName(),false);
    
                            return false;
                        }

                        $count['modificati']++;
                    }
                }
            }
            else
            {
                $newImpianto=new AA_Gecop(0,$this->oUser);
                $geolocalizzazione=array();
                $geolocalizzazione['comune']=$curData['Geo_comune'];
                $geolocalizzazione['localita']=$curData['Geo_localita'];
                $geolocalizzazione['coordinate']=$curData['Geo_coordinate'];
                $newImpianto->SetProp('Geolocalizzazione',json_encode($geolocalizzazione));

                $newImpianto->Parse($curData);

                if(!AA_Gecop::AddNew($newImpianto,$this->oUser))
                {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("Errore nell'aggiunta del nuovo impianto: ".$newImpianto->GetName(),false);

                    return false;
                }

                if($newImpianto->IsValid())
                {
                    $pratiche=$newImpianto->GetPratiche();
                    $pratica=array();
                    if(isset($curData['pratica_tipo'])) $pratica["tipo"]=$curData['pratica_tipo'];
                    else $pratica["tipo"]=AA_Gecop_Const::AA_GECOP_TIPO_PRATICA_AU;
                    if(isset($curData['pratica_stato'])) $pratica["stato"]=$curData['pratica_stato'];
                    else $pratica["stato"]=AA_Gecop_Const::AA_GECOP_STATO_PRATICA_DAISTRUIRE;
                    if(isset($curData['pratica_estremi'])) $pratica["estremi"]=$curData['pratica_estremi'];
                    else $pratica["estremi"]='';
                    if(isset($curData['pratica_descrizione'])) $pratica["descrizione"]=$curData['pratica_descrizione'];
                    else $pratica["descrizione"]='';
                    if(isset($curData['pratica_via'])) $pratica["via"]=$curData['pratica_via'];
                    else $pratica["via"]=AA_Gecop_Const::AA_GECOP_TIPO_VIA_NESSUNO;
                    if(isset($curData['pratica_societa'])) $pratica["societa"]=trim($curData['pratica_societa']);
                    else $pratica["societa"]='';
                    if(isset($curData['pratica_data_inizio'])) $pratica["data_inizio"]=$curData['pratica_data_inizio'];
                    else $pratica["data_inizio"]='';
                    if(isset($curData['pratica_data_fine'])) $pratica["data_fine"]=$curData['pratica_data_fine'];
                    else $pratica["data_fine"]='';
                    if(isset($curData['pratica_note'])) $pratica["note"]=$curData['pratica_note'];
                    else $pratica["note"]='';

                    $newId=uniqid();
                    $pratiche[$newId]=$pratica;

                    $newImpianto->SetProp('Pratiche',json_encode($pratiche));
                    
                    if(!$newImpianto->Update($this->oUser,true,"Aggiunta pratica - id: ".$newId))
                    {
                        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                        $task->SetError("Errore nell'aggiunta della nuova pratica all'impianto: ".$impianto->GetName(),false);

                        return false;
                    }

                    $count['inseriti']++;
                }
            }
        }

        AA_SessionVar::Set("GecopAddNewMultiResult",$count,false);

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Impianti aggiunti con successo",false);
        $task->SetStatusAction("dlg",json_encode(array("params"=>array("task"=>"GetGecopAddNewMultiResultDlg"))));
        return true;
    }

    //Task modifica dati generali elemento
    public function Task_GetGecopModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }
            
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecopModifyDlg($object),true);
        return true;
    }

    //Task richiesta oscurtamento dati personali
    public function Task_GetGecopConfirmPrivacyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        $form_id=$_REQUEST['form'];
        if($form_id=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non e' stato impostato l'identificativo del form corrispondente.",false);
        
            return false;
        }
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecopConfirmPrivacyDlg($form_id),true);
        
        return true;
    }

    //Task modifica dati beneficiario
    public function Task_GetGecopModifyRendicontoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecopModifyRendicontoDlg($object),true);

        return true;
    }

    //Task resume
    public function Task_GetGecopResumeDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }

        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericResumeObjectDlg($_REQUEST,"ResumeGecop"),true);
            return true;
        }    
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.",false);
            return false;
        }
    }
    
    //Task publish organismo
    public function Task_GetGecopPublishDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }
        
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericPublishObjectDlg($_REQUEST,"PublishGecop"),true);
            return true;
        }    
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.",false);
            return false;
        }
    }
    
    //Task Riassegna
    public function Task_GetGecopReassignDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }

        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericReassignObjectDlg($_REQUEST,"ReassignGecop"),true);
            return true;
        }    
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.",false);
            return false;
        }
    }
    
    //Task elimina
    public function Task_GetGecopTrashDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericObjectTrashDlg($_REQUEST,"TrashGecop"),true);
            return true;
        }    
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.",false);
            return false;
        }
    }
       
    //Task dialogo elimina
    public function Task_GetGecopDeleteDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGecopDeleteDlg($_REQUEST),true);
            return true;
        }    
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.",false);
            return false;
        }
    }
    
    //Task lista
    public function Task_GetGecopListaCodiciIstat($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        $filter=$_REQUEST["filter"];

        $db=new AA_Database();
        $query="SELECT codice,comune FROM ".AA_Gecop_Const::AA_DBTABLE_CODICI_ISTAT;
        if($filter !="") $query.=" WHERE codice like '".addslashes($filter['value'])."%' OR comune like '".addslashes($filter['value'])."%'";
        //$query.=" LIMIT 10";

        //AA_Log::Log(__METHOD__." - query ".$query.print_r($_REQUEST,true),100);
        
        //errore nella query
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - ERRORE ".$db->GetErrorMessage(),100);
            die("[]");
        }

        //Query vuota
        if($db->GetAffectedRows() == 0)
        {
            die("[]");
        }
        
        $result=array();
        $count=1;
        foreach($db->GetResultSet() as $curRow)
        {
            $result[]=array("id"=>$count,"codice"=>$curRow['codice'],"value"=>$curRow['comune']);
            $count++;
        }

        die(json_encode($result));
    }

    //Task aggiunta 
    public function Task_GetGecopAddNewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);
            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGecopAddNewDlg(),true);
            return true;
        }
    }

    //Task Add new pratica
    public function Task_AddNewGecopPratica($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        //----------- verify values --------------------

        if($_REQUEST['stato'] != AA_Gecop_Const::AA_GECOP_STATO_PRATICA_DAISTRUIRE && $_REQUEST['data_inizio']=="")
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare la data di inizio",false);

            return false;
        }

        if($_REQUEST['stato'] == AA_Gecop_Const::AA_GECOP_STATO_PRATICA_AUTORIZZATA && $_REQUEST['data_fine']=="")
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare la data di fine procedimento",false);

            return false;
        }

        if($_REQUEST['data_fine'] < $_REQUEST['data_inizio'])
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("La data di fine non puo' essere precedente a quella di inizio",false);

            return false;
        }

        if($_REQUEST['stato'] == AA_Gecop_Const::AA_GECOP_STATO_PRATICA_NEGATA && $_REQUEST['data_fine']=="")
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare la data di fine procedimento",false);

            return false;
        }

        $pratiche=$object->GetPratiche();
        $pratica=array(
            "tipo"=>$_REQUEST['tipo'],
            "stato"=>$_REQUEST['stato'],
            "estremi"=>$_REQUEST['estremi'],
            "descrizione"=>$_REQUEST['descrizione'],
            "via"=>$_REQUEST['via'],
            "societa"=>$_REQUEST['societa'],
            "data_inizio"=>substr($_REQUEST['data_inizio'],0,10),
            "data_fine"=>substr($_REQUEST['data_fine'],0,10),
            "note"=>$_REQUEST['note'],
        );
        $newId=uniqid();
        $pratiche[$newId]=$pratica;
        $_REQUEST["Pratiche"]=json_encode($pratiche);
        //-----------------------------------------------
        
        $object->Parse($_REQUEST);

        if(!$object->Update($this->oUser,true,"Aggiunta pratica - id: ".$newId))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiunta della nuova pratica.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }

    //Task update gestione finanziaria
    public function Task_UpdateGecopRendiconto($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        $aggiudicatario=$object->GetAggiudicatario();
        $gestionefinanziaria=$object->GetGestioneFinanziaria();

        //----------- verify values ---------------------
        if(!empty($_REQUEST['aggiudicatario_nominativo']))
        {
            $aggiudicatario['nominativo']=trim($_REQUEST['aggiudicatario_nominativo']);
        }
        if(!empty($_REQUEST['aggiudicatario_cf']))
        {
            $aggiudicatario['cf']=trim($_REQUEST['aggiudicatario_cf']);
        }

        if(!empty($_REQUEST['data_inizio']))
        {
            $gestionefinanziaria['data_inizio']=substr(trim($_REQUEST['data_inizio']),0,10);
        }
        else $gestionefinanziaria['data_inizio']="";

        if(!empty($_REQUEST['data_fine']))
        {
            $gestionefinanziaria['data_fine']=substr(trim($_REQUEST['data_fine']),0,10);
        }
        else $gestionefinanziaria['data_fine']="";
    
        if(!empty($_REQUEST['data_fine_effettiva']))
        {
            $gestionefinanziaria['data_fine_effettiva']=substr(trim($_REQUEST['data_fine_effettiva']),0,10);
        }
        else $gestionefinanziaria['data_fine_effettiva']="";

        if(!empty($_REQUEST['importo_aggiudicazione']))
        {
            $gestionefinanziaria['importo_aggiudicazione']=floatVal(AA_Utils::number_format(str_replace(",",".",str_replace(".","",$_REQUEST['importo_aggiudicazione'])),2,"."));
        }
        else $gestionefinanziaria['importo_aggiudicazione']=0.00;

        if(!empty($_REQUEST['importo_liquidato']))
        {
            $gestionefinanziaria['importo_liquidato']=floatVal(AA_Utils::number_format(str_replace(",",".",str_replace(".","",$_REQUEST['importo_liquidato'])),2,"."));
        }
        else  $gestionefinanziaria['importo_liquidato']=0.00;

        if(empty($aggiudicatario['nominativo']) && $gestionefinanziaria['importo_aggiudicazione'] > 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre specificare l'aggiudicatario se e' presente un importo contrattuale.",false);

            return false;
        }

        if(!empty($aggiudicatario['nominativo']) && empty($gestionefinanziaria['importo_aggiudicazione']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre specificare almeno l'importo del contratto se e' presente l'aggiudicatario.",false);

            return false;
        }

        if(!empty($gestionefinanziaria['data_fine']) && $gestionefinanziaria['data_fine'] < $gestionefinanziaria['data_inizio'])
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("La data di conclusione prevista non puo' essere precedente a quella di inizio.",false);

            return false;
        }

        if(!empty($gestionefinanziaria['data_fine_effettiva']) && $gestionefinanziaria['data_fine_effettiva'] < $gestionefinanziaria['data_inizio'])
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("La data di conclusione effettiva non puo' essere precedente a quella di inizio.",false);

            return false;
        }
        //-----------------------------------------------

        $_REQUEST['GestioneFinanziaria']=json_encode($gestionefinanziaria);
        $_REQUEST['Aggiudicatario']=json_encode($aggiudicatario);
        
        $object->Parse($_REQUEST);

        if(!$object->Update($this->oUser,true,"Modifica rendicontazione gestione finanziaria - id: ".$object->GetId()))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento della rendicontazione finanaziaria id: ".$object->GetId(),false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }

    //Task delete pratica
    public function Task_DeleteGecopComponente($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        $commissione=$object->GetCommissione();
        if(!isset($commissione[$_REQUEST['id_componente']]))
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo componente mancante o non corretto",false);

            return false;
        }
        $id_componente=$_REQUEST['id_componente'];
        $componente=$commissione[$id_componente]['nominativo'];
        if(!empty($commissione[$id_componente]['filehash']))
        {
            //rimuove il cv
            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                if(!$storage->DelFile($commissione[$id_componente]['filehash']))
                {
                    AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$commissione[$id_componente]['filehash'],100);
                }
            }
        }

        unset($commissione[$id_componente]);
        $_REQUEST["Commissione"]=json_encode($commissione);
        //-----------------------------------------------
        
        $object->Parse($_REQUEST);

        if(!$object->Update($this->oUser,true,"Rimozione componente: ".$componente))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'eliminazione del componente: ".$componente,false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }

    //Task aggiunta multipla
    public function Task_GetGecopAddNewMultiDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);
            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGecopAddNewMultiDlg(),true);
            return true;
        }
    }

    //Task aggiunta multipla preview
    public function Task_GetGecopAddNewMultiPreviewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);
            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGecopAddNewMultiPreviewDlg(),true);
            return true;
        }
    }

    //Task aggiunta multipla result
    public function Task_GetGecopAddNewMultiResultDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);
            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGecopAddNewMultiResultDlg(),true);
            return true;
        }
    }

    //Task aggiunta geco da csv, passo 2 di 3
    public function Task_GetGecopAddNewMultiPreviewCalc($task)
    {
        $csvFile=AA_SessionFileUpload::Get("GecopAddNewMultiCSV");
        if(!$csvFile->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("File non valido",false);        
            return false;
        }

        $csv=$csvFile->GetValue();
        if(!is_file($csv["tmp_name"]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("File non valido (1)",false);
            return false;
        }

        $csvRows=explode("\n",str_replace("\r","",file_get_contents($csv["tmp_name"])));
        //Elimina il file temporaneo
        if(is_file($csv["tmp_name"]))
        {
            unlink($csv["tmp_name"]);
        }

        $data=false;

        if($_REQUEST['tracciato']==1)
        {
            $data=$this->ElaborateCsvImportTerna($csvRows);
        }
        
        if($_REQUEST['tracciato']==2)
        {
            $data=$this->ElaborateCsvImportAssIndustria($csvRows);
        }

        if($_REQUEST['tracciato']==3)
        {
            $data=$this->ElaborateCsvImportSuape($csvRows);
        }

        if($data===false)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'elaborazione del file",false);
            return false;
        }

        if(is_array($data) && sizeof($data) > 0)
        {
            AA_SessionVar::Set("GecopAddNewMultiFromCSV_ParsedData",$data,false);
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetStatusAction('dlg',array("task"=>"GetGecopAddNewMultiPreviewDlg"),true);
        $task->SetContent("Csv elaborato.",false);
                
        return true;
    }

    //Task aggiungi allegato
    public function Task_GetGecopAddNewAllegatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecopAddNewAllegatoDlg($object),true);
        return true;
    }

    //Task aggiungi componente commissione
    public function Task_GetGecopAddNewComponenteDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecopAddNewComponenteDlg($object),true);
        return true;
    }

    //Task modifica pratica esistente
    public function Task_GetGecopModifyPraticaDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        $pratiche=$object->GetPratiche();
        if(!isset($pratiche[$_REQUEST['id_pratica']]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo pratica non presente",false);

            return false;
        }

        $pratica=$pratiche[$_REQUEST['id_pratica']];
        $pratica['id']=$_REQUEST['id_pratica'];

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecopModifyPraticaDlg($object,$pratica),true);
        return true;
    }

    //Task modifica pratica esistente
    public function Task_GetGecopCopyPraticaDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        $pratiche=$object->GetPratiche();
        if(!isset($pratiche[$_REQUEST['id_pratica']]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo pratica non presente",false);

            return false;
        }

        $pratica=$pratiche[$_REQUEST['id_pratica']];
        $pratica['id']=$_REQUEST['id_pratica'];

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecopCopyPraticaDlg($object,$pratica),true);
        return true;
    }

    //Task elimina pratica esistente
    public function Task_GetGecopTrashComponenteDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        $commissione=$object->GetCommissione();
        if(!isset($commissione[$_REQUEST['id_componente']]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo componente non presente",false);

            return false;
        }

        $componente=$commissione[$_REQUEST['id_componente']];
        $componente['id']=$_REQUEST['id_componente'];

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecopTrashComponenteDlg($object,$componente),true);
        return true;
    }
    
    
    //Task aggiorna allegato
    public function Task_UpdateGecopAllegato($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        $uploadedFile = AA_SessionFileUpload::Get("NewAllegatoDoc");

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);
            
            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     

            return false;
        }

        if($_REQUEST['id_allegato']=="" || $_REQUEST['id_allegato']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo allegato non valido.",false);

            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }

            return false;
        }

        $object= new AA_Gecop($_REQUEST['id'],$this->oUser);

        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")",false);

            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     

            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per poter modificare l'elemento (".$object->GetId().").",false);

            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     
        
            return true;
        }

        $allegati=$object->GetAllegati();
        $allegato=$allegati[$_REQUEST['id_allegato']];
        if($allegato==null)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("identificativo allegato non valido (".$_REQUEST['id_allegato'].").",false);

            //Elimina il file temporaneo
            if($uploadedFile->isValid())
            {   
                $file=$uploadedFile->GetValue();
                if(file_exists($file['tmp_name']))
                {
                    if(!unlink($file['tmp_name']))
                    {
                        AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                    }
                }
            }     
        
            return false;
        }

        //Se c'è un file uploadato l'url non viene salvata.
        if($uploadedFile->isValid()) 
        {
            $_REQUEST['url']="";

            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$allegato['filehash'];
                if($oldFile !="")
                {
                    if(!$storage->DelFile($oldFile))
                    {
                        AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$oldFile,100);
                    }
                }

                $file=$uploadedFile->GetValue();
                $storageFile=$storage->Addfile($file['tmp_name'],$file['name'],$file['type'],1);
                if($storageFile->IsValid())
                {
                    $allegato['filehash']=$storageFile->GetFileHash();
                }
                else
                {
                    AA_Log::Log(__METHOD__." - errore nell'aggiunta allo storage. file non salvato.",100);
                }
            }
            else AA_Log::Log(__METHOD__." - storage non inizializzato. file non salvato.",100);

            //Elimina il file temporaneo
            if(file_exists($file['tmp_name']))
            {
                if(!unlink($file['tmp_name']))
                {
                    AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$file['tmp_name'],100);
                }
            }
        }

        //Elimina il file precedentemente associato se viene impostato un url
        if($_REQUEST['url'] !="" && $allegato['filehash'] !="")
        {
            $allegato['url']=$_REQUEST['url'];
            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$allegato['filehash'];
                if($oldFile !="")
                {
                    if(!$storage->DelFile($oldFile))
                    {
                        AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$oldFile,100);
                    }
                    $allegato['filehash']="";
                }
            }
            else AA_Log::Log(__METHOD__." - storage non inizializzato. file non eliminato.",100);
        }

        if(isset($_REQUEST['descrizione']) && $_REQUEST['descrizione'] !="")
        {
            $allegato['descrizione']=$_REQUEST['descrizione'];
        }

        if(isset($_REQUEST['tipo']) && $_REQUEST['tipo'] > 0)
        {
            $allegato['tipo']=$_REQUEST['tipo'];
        }

        $allegati[$_REQUEST['id_allegato']]=$allegato;
        $object->SetAllegati($allegati);
        
        if(!$object->Update($this->oUser,true,"Aggiornamento allegato: ".$_REQUEST['id_allegato']))
        {        
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dell'allegato. (".AA_Log::$lastErrorLog.")",false);
            
            return false;       
        }
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Allegato aggiornato con successo.",false);

        return true;
    }

    //Task elimina allegato
    public function Task_DeleteGecopAllegato($task)
    {
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);
            return false;
        }

        if($_REQUEST['id_allegato']=="" || $_REQUEST['id_allegato']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo allegato non valido.",false);
            return false;
        }

        $object= new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")",false);
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per poter modificare l'elemento (".$object->GetId().").",false);
            return true;
        }

        if(!$object->DeleteAllegato($_REQUEST['id_allegato'],$this->oUser))
        {        
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nella rimozione dell'allegato/link. (".AA_Log::$lastErrorLog.")",false);
            
            return false;       
        }
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Allegato/link rimosso con successo.",false);

        return true;
    }

    
    //Task modifica allegato
    public function Task_GetGecopModifyAllegatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if($_REQUEST['id_allegato']=="" || $_REQUEST['id_allegato']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo allegato non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        $allegati=$object->GetAllegati();
        if(!isset($allegati[$_REQUEST['id_allegato']]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo allegato non valido.",false);

            return false;
        }

        $allegato=$allegati[$_REQUEST['id_allegato']];
        $allegato['id']=$_REQUEST['id_allegato'];

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecopModifyAllegatoDlg($object,$allegato),true);

        return true;
    }

    //Task modifica allegato
    public function Task_GetGecopModifyComponenteDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if($_REQUEST['id_componente']=="" || $_REQUEST['id_componente']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo componente non valido.",false);

            return false;
        }

        $object=new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento",false);

            return false;
        }

        $commissione=$object->GetCommissione();
        if(!isset($commissione[$_REQUEST['id_componente']]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo componente non valido.",false);

            return false;
        }

        $componente=$commissione[$_REQUEST['id_componente']];
        $componente['id']=$_REQUEST['id_componente'];

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecopModifyComponenteDlg($object,$componente),true);

        return true;
    }

    //Task trash allegato
    public function Task_GetGecopTrashAllegatoDlg($task)
    {
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);
            return false;
        }

        if($_REQUEST['id_allegato']=="" || $_REQUEST['id_allegato']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo allegato non valido.",false);
            return false;
        }

        $object= new AA_Gecop($_REQUEST['id'],$this->oUser);
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")",false);
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per poter modificare l'elemento (".$object->GetId().").",false);
            return true;
        }

        $allegato=$object->GetAllegato($_REQUEST['id_allegato']);
        if($allegato==null)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("identificativo allegato non valido (".$_REQUEST['id_allegato'].").",false);
        
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecopTrashAllegatoDlg($object,$allegato),true);
        return true;
    }
    
    //Task filter dlg
    public function Task_GetGecopPubblicateFilterDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->TemplatePubblicateFilterDlg($_REQUEST);
        $sTaskLog.= base64_encode($content);
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task filter dlg
    public function Task_GetGecopBozzeFilterDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $content=$this->TemplateBozzeFilterDlg($_REQUEST);
        $sTaskLog.= base64_encode($content);
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task NavBarContent
    public function Task_GetNavbarContent($task)
    {
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $_REQUEST['section']=static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX;
        }
        
        return $this->Task_GetGenericNavbarContent($task,$_REQUEST);
    }
    
    //Template filtro di ricerca
    public function TemplatePubblicateFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("comune"=>$params['comune'],"id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate'],"tipo"=>$params['tipo'],"stato"=>$params['stato']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['nome']=="") $formData['nome']="";
    
        //Valori reset
        $resetData=array("comune"=>"Qualunque","id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","nome"=>"","cestinate"=>0,"tipo"=>0,"stato"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Pubblicate_Filter".uniqid(), "Parametri di ricerca per le schede in bozza",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
      
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));

        //titolo
        $dlg->AddTextField("nome","Oggetto",array("bottomLabel"=>"*Filtra in base alla denominazione dell'impianto.", "placeholder"=>"..."));

        $dlg->SetApplyButtonName("Filtra");

        return $dlg->GetObject();
    }
    
    //Template filtro di ricerca
    public function TemplateBozzeFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("comune"=>$params['comune'],"id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate'],"tipo"=>$params['tipo'],"stato"=>$params['stato']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['nome']=="") $formData['nome']="";

        //Valori reset
        $resetData=array("comune"=>"Qualunque","id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","nome"=>"","cestinate"=>0,"tipo"=>0,"stato"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Bozze_Filter".uniqid(), "Parametri di ricerca per le schede in bozza",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
      
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
    
        //titolo
        $dlg->AddTextField("nome","Oggetto",array("bottomLabel"=>"*Filtra in base all'oggetto del contratto.", "placeholder"=>"..."));
        
        $dlg->SetApplyButtonName("Filtra");

        return $dlg->GetObject();
    }
    
    //Funzione di esportazione in pdf (da specializzare)
    public function Template_PdfExport($ids=array(),$toBrowser=true,$title="Pubblicazione ai sensi dell'art.26-27 del d.lgs. 33/2013",$rowsForPage=20,$index=false,$subTitle="")
    {
        return $this->Template_GenericPdfExport($ids,$toBrowser,$title,"Template_GecopPdfExport", $rowsForPage, $index,$subTitle);
    }

    //funzione di aiuto
    public function Task_AMAAI_Start($task)
    {
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        
        $task->SetContent($this->Template_GetGecopHelpDlg(),true);
        
        $help_url="";
        $action='AA_MainApp.utils.callHandler("pdfPreview", { url: this.taskManager + "?task=PdfExport&section=" + this.curSection.id }, this.id);';
        
        return true;

    }

    //Template pdf export single
    public function Template_GecopPdfExport($id="", $parent=null,$object=null,$user=null)
    {
        if(!($object instanceof AA_Gecop))
        {
            return "";
        }
        
        if($id=="") $id="Template_GecopPdfExport_".$object->GetId();

        return new AA_GecopPublicReportTemplateView($id,$parent,$object);
    }

    //Template dettaglio allegati
    public function TemplateDettaglio_Allegati($object=null,$id="", $canModify=false)
    {
        #documenti----------------------------------
        $curId=$id."_Layout_Allegati";
        $provvedimenti=new AA_JSON_Template_Layout($curId,array("type"=>"clean","gravity"=>4,"css"=>array("border-left"=>"1px solid gray !important;","border-top"=>"1px solid gray !important;")));

        $toolbar=new AA_JSON_Template_Toolbar($curId."_Toolbar_allegati",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

        $toolbar->AddElement(new AA_JSON_Template_Generic($curId."_Toolbar_Allegati_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Allegati e link</span>", "align"=>"center")));

        if($canModify)
        {
            //Pulsante di aggiunta documento
            $add_documento_btn=new AA_JSON_Template_Generic($curId."_AddAllegato_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-file-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi allegato o link",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetGecopAddNewAllegatoDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));

            $toolbar->AddElement($add_documento_btn);
        }
        else 
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        }

        $provvedimenti->AddRow($toolbar);

        $options_documenti=array();

      
        $options_documenti[]=array("id"=>"tipo","header"=>array("<div style='text-align: center'>Tipo</div>"),"width"=>100, "css"=>array("text-align"=>"left"),"sort"=>"text");
        $options_documenti[]=array("id"=>"descrizione","header"=>array("<div style='text-align: center'>Descrizione</div>"),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text");
        $options_documenti[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
    
        $documenti=new AA_JSON_Template_Generic($curId."_Allegati_Table",array("view"=>"datatable", "select"=>true,"scrollX"=>false,"css"=>"AA_Header_DataTable","columns"=>$options_documenti));

        $documenti_data=array();
        $allegati=$object->GetAllegati();
        $listaTipo=AA_Gecop_Const::GetListaTipoAllegati();
        foreach($allegati as $id_doc=>$curDoc)
        {
            if($curDoc['filehash'] != "")
            {
                $view='AA_MainApp.utils.callHandler("pdfPreview", {url: "storage.php?object='.$curDoc['filehash'].'"},"'.$this->id.'")';
                $view_icon="mdi-floppy";
            }
            else 
            {
                $view='AA_MainApp.utils.callHandler("wndOpen", {url: "'.$curDoc['url'].'"},"'.$this->id.'")';
                $view_icon="mdi-eye";
            }
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopTrashAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$id_doc.'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopModifyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$id_doc.'"}]},"'.$this->id.'")';
            if($canModify) $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Vedi' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $ops="<div class='AA_DataTable_Ops' style='justify-content: center'><a class='AA_DataTable_Ops_Button' title='Vedi' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a></div>";
            $documenti_data[]=array("id"=>$id_doc,"descrizione"=>$curDoc['descrizione'],"tipo"=>$listaTipo[$curDoc['tipo']],"ops"=>$ops);
        }
        $documenti->SetProp("data",$documenti_data);
        if(sizeof($documenti_data) > 0) $provvedimenti->AddRow($documenti);
        else $provvedimenti->AddRow(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        #--------------------------------------
        
        return $provvedimenti;
    }

    //Task action menù
    public function Task_GetActionMenu($task)
    {
        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $content = "";

        switch ($_REQUEST['section']) {
            default:
                return parent::Task_GetActionMenu($task);
        }

        if ($content != "") $sTaskLog .= $content->toBase64();

        $sTaskLog .= "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }
}

#Classe template per la gestione del report pdf dell'oggetto
Class AA_GecopPublicReportTemplateView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_GecopPublicReportTemplateView",$parent=null,$object=null)
    {
        if(!($object instanceof AA_Gecop))
        {
            AA_Log::Log(__METHOD__." - oggetto non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$object);
        
        $this->SetStyle("width: 100%; display:flex; flex-direction: row; align-items: center; justify-content: space-between; border-bottom: 1px solid  gray; height: 100%");

        #Ufficio----------------------------------
        $struct=$object->GetStruct();
        $struct_desc=$struct->GetAssessorato();
        if($struct->GetDirezione(true) > 0) $struct_desc.="<br>".$struct->GetDirezione();
        if($struct->GetServizio(true) >0) $struct_desc.="<br>".$struct->GetServizio();

        $ufficio=new AA_XML_Div_Element($id."_ufficio",$this);
        $ufficio->SetStyle('width:30%; font-size: .6em; padding: .1em');
        $ufficio->SetText($struct_desc);
        #-----------------------------------------------
        
        #descrizione----------------------------------
        $oggetto=new AA_XML_Div_Element($id."_descrizione",$this);
        $oggetto->SetStyle('width:30%; font-size: .6em; padding: .1em; text-align: justify');
        $oggetto->SetText(substr($object->GetName(),0,320));
        #-----------------------------------------------

        /*if($object->GetTipo(true) == AA_Gecop_Const::AA_TIPO_PROVVEDIMENTO_SCELTA_CONTRAENTE)
        {
            #modalità----------------------------------
            $oggetto=new AA_XML_Div_Element($id."_modalita",$this);
            $oggetto->SetStyle('width:20%; font-size: .5em; padding: .1em');
            $oggetto->SetText($object->GetModalita());
            #-----------------------------------------------
        }
        else
        {
            #contraente----------------------------------
            $oggetto=new AA_XML_Div_Element($id."_contraente",$this);
            $oggetto->SetStyle('width:20%; font-size: .6em; padding: .1em');
            $oggetto->SetText($object->GetProp("Contraente"));
            #-----------------------------------------------                        
        }*/

        #estremi----------------------------------
        $oggetto=new AA_XML_Div_Element($id."_estremi",$this);
        $oggetto->SetStyle('width:19%; font-size: .6em; padding: .1em');
        $oggetto->SetText($object->GetProp("Estremi"));
        #-----------------------------------------------        
    }
}
