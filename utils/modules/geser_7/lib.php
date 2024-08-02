<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "config.php";
include_once "system_lib.php";

#Costanti
Class AA_Geser_Const extends AA_Const
{
    const AA_USER_FLAG_GESER="geser";

    const AA_USER_FLAG_GESER_RO="geser_ro";

    const AA_DBTABLE_CODICI_ISTAT="aa_patrimonio_codici_istat";

    //Stato impianto;
    static protected $nStatoImpianto=null;
    const AA_GESER_STATO_AUTORIZZAZIONE=1;
    const AA_GESER_STATO_AUTORIZZATO=2;
    const AA_GESER_STATO_INCOSTRUZIONE=64;
    const AA_GESER_STATO_ESERCIZIO=4;
    const AA_GESER_STATO_DISMISSIONE=8;
    const AA_GESER_STATO_DISMESSO=16;
    const AA_GESER_STATO_AMPLIAMENTO=32;
    const AA_GESER_STATO_FUORI_ESERCIZIO=64;
    public static function GetListaStatiImpianto()
    {
        if(static::$nStatoImpianto==null)
        {
            static::$nStatoImpianto=array(
                static::AA_GESER_STATO_AUTORIZZAZIONE=>"Richiesta autorizzazione",
                static::AA_GESER_STATO_AUTORIZZATO=>"Autorizzato",
                static::AA_GESER_STATO_INCOSTRUZIONE=>"In costruzione",
                static::AA_GESER_STATO_ESERCIZIO=>"In esercizio",
                static::AA_GESER_STATO_FUORI_ESERCIZIO=>"Inattivo",
                static::AA_GESER_STATO_AMPLIAMENTO=>"In fase di modifica",
                static::AA_GESER_STATO_DISMISSIONE=>"In dismissione",
                static::AA_GESER_STATO_DISMESSO=>"Dismesso"
            );
        }

        return static::$nStatoImpianto;
    }

    protected static $aTipoVia=null;
    const AA_GESER_TIPO_VIA_NESSUNO=1;
    const AA_GESER_TIPO_VIA_REGIONALE=2;
    const AA_GESER_TIPO_VIA_MINISTERIALE=4;
    public static function GetListaTipoVia()
    {
        if(static::$aTipoVia==null)
        {
            static::$aTipoVia=array(
                static::AA_GESER_TIPO_VIA_NESSUNO=>"Non soggetta",
                static::AA_GESER_TIPO_VIA_REGIONALE=>"Regionale",
                static::AA_GESER_TIPO_VIA_MINISTERIALE=>"Ministeriale"
            );
        }

        return static::$aTipoVia;
    }

    protected static $aStatoPratica=null;
    const AA_GESER_STATO_PRATICA_DAISTRUIRE=1;
    const AA_GESER_STATO_PRATICA_INLAVORAZIONE=2;
    const AA_GESER_STATO_PRATICA_AUTORIZZATA=4;
    const AA_GESER_STATO_PRATICA_SOSPESA_VIA=8;
    const AA_GESER_STATO_PRATICA_NEGATA=16;
    public static function GetListaStatiPratica()
    {
        if(static::$aStatoPratica==null)
        {
            static::$aStatoPratica=array(
                static::AA_GESER_STATO_PRATICA_DAISTRUIRE=>"Da istruire",
                static::AA_GESER_STATO_PRATICA_INLAVORAZIONE=>"In lavorazione",
                static::AA_GESER_STATO_PRATICA_AUTORIZZATA=>"Approvata",
                static::AA_GESER_STATO_PRATICA_SOSPESA_VIA=>"Sospesa per VIA",
                static::AA_GESER_STATO_PRATICA_NEGATA=>"Rigettata"
            );
        }

        return static::$aStatoPratica;
    }

    protected static $aTipoPratica=null;
    const AA_GESER_TIPO_PRATICA_AU=1;
    const AA_GESER_TIPO_PRATICA_VARIANTE=2;
    const AA_GESER_TIPO_PRATICA_VOLTURA=4;
    public static function GetListaTipoPratica()
    {
        if(static::$aTipoPratica==null)
        {
            static::$aTipoPratica=array(
                static::AA_GESER_TIPO_PRATICA_AU=>"Autorizzazione Unica",
                static::AA_GESER_TIPO_PRATICA_VARIANTE=>"Variante",
                static::AA_GESER_TIPO_PRATICA_VOLTURA=>"Voltura"
            );
        }

        return static::$aTipoPratica;
    }

    protected static $aTipoImpianti=null;
    const AA_GESER_TIPOIMPIANTO_FOTOVOLTAICO=1;
    const AA_GESER_TIPOIMPIANTO_EOLICO=2;
    const AA_GESER_TIPOIMPIANTO_AGRIVOLTAICO=4;
    const AA_GESER_TIPOIMPIANTO_BIOGAS=8;
    const AA_GESER_TIPOIMPIANTO_BIOMASSA=16;
    const AA_GESER_TIPOIMPIANTO_IDROELETTRICO=32;
    const AA_GESER_TIPOIMPIANTO_ELETTRODOTTO=64;
    const AA_GESER_TIPOIMPIANTO_TERMODINAMICO=128;
    const AA_GESER_TIPOIMPIANTO_OFFSHORE=256;
    public static function GetListaTipoImpianti()
    {
        if(static::$aTipoImpianti==null)
        {
            static::$aTipoImpianti=array(
                static::AA_GESER_TIPOIMPIANTO_AGRIVOLTAICO=>"Agrivoltaico",
                static::AA_GESER_TIPOIMPIANTO_BIOGAS=>"Biogas",
                static::AA_GESER_TIPOIMPIANTO_BIOMASSA=>"Biomassa",
                static::AA_GESER_TIPOIMPIANTO_ELETTRODOTTO=>"Elettrodotto",
                static::AA_GESER_TIPOIMPIANTO_EOLICO=>"Eolico",
                static::AA_GESER_TIPOIMPIANTO_FOTOVOLTAICO=>"Fotovoltaico",
                static::AA_GESER_TIPOIMPIANTO_IDROELETTRICO=>"Idroelettrico",
                static::AA_GESER_TIPOIMPIANTO_OFFSHORE=>"Off-shore",
                static::AA_GESER_TIPOIMPIANTO_TERMODINAMICO=>"Solare termodinamico",
            );
        }

        return static::$aTipoImpianti;
    }
}

#Classe oggetto geser
Class AA_Geser extends AA_Object_V2
{
    //tabella dati db
    const AA_DBTABLE_DATA="aa_geser_data";
    static protected $AA_DBTABLE_OBJECTS="aa_geser_objects";

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

        return parent::DeleteData($idData,$user);
    }

    public function serialize()
    {
        $result=get_object_vars($this);
        return json_encode($result);
    }


    //geolocalizzazione
    protected $geolocalizzazione=null;
    public function GetGeolocalizzazione()
    {
        if(!$this->IsValid()) return array();

        if(!is_array($this->geolocalizzazione))
        {
            $this->geolocalizzazione=json_decode($this->GetProp('Geolocalizzazione'),true);
            if(!is_array($this->geolocalizzazione))
            {
                //AA_Log::Log(__METHOD__." - errore nel parsing",100);
                return array();
            }
        }

        return $this->geolocalizzazione;
    }

    public function GetTipo()
    {
        if($this->GetProp("Tipologia")<=0) return "Non definito";
        $tipologia=AA_Geser_Const::GetListaTipoImpianti();
        return $tipologia[$this->GetProp("Tipologia")];
    }

    public function GetStato()
    {
        if($this->GetProp("Stato")<=0) return "Non definito";
        $tipologia=AA_Geser_Const::GetListaStatiImpianto();
        return $tipologia[$this->GetProp("Stato")];
    }

    //pratiche
    protected $pratiche=null;
    public function GetPratiche()
    {
        if(!$this->IsValid()) return array();

        if(!is_array($this->pratiche))
        {
            $this->pratiche=json_decode($this->GetProp('Pratiche'),true);
            if(!is_array($this->pratiche))
            {
                AA_Log::Log(__METHOD__." - errore nel parsing",100);
                return array();
            }
        }

        return $this->pratiche;
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
        $this->AddProp("Tipologia",0,"tipologia");
        $this->AddProp("Superficie",0,"superficie");
        $this->AddProp("Stato",0,"stato");
        $this->AddProp("AnnoAutorizzazione","","anno_autorizzazione");
        $this->AddProp("AnnoCostruzione","","anno_costruzione");
        $this->AddProp("AnnoEsercizio","","anno_entrata_esercizio");
        $this->AddProp("AnnoDismissione","","anno_dismissione");
        $this->AddProp("Potenza",0,"potenza");
        $this->AddProp("Geolocalizzazione","","geolocalizzazione");
        $this->AddProp("Pratiche","","pratiche");
        //$this->AddProp("Allegati","","allegati");

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
        $params['class']="AA_Geser";
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
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && !$user->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $perms = AA_Const::AA_PERMS_READ;
        }
        //---------------------------------------

        //Se l'utente ha il flag e può modificare la scheda allora può fare tutto
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && $user->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
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
        if(!$user->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            AA_Log::Log(__METHOD__." - L'utente corrente: ".$user->GetUserName()." non ha i permessi per inserire nuovi elementi.",100);
            return false;
        }

        //Verifica validità oggetto
        if(!($object instanceof AA_Geser))
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


#Classe per il modulo art26 - contributi
Class AA_GeserModule extends AA_GenericModule
{
    const AA_UI_PREFIX="AA_Geser";

    //Id modulo
    const AA_ID_MODULE="AA_MODULE_GESER";

    //main ui layout box
    const AA_UI_MODULE_MAIN_BOX="AA_Geser_module_layout";

    const AA_MODULE_OBJECTS_CLASS="AA_Geser";

    //Task per la gestione dei dialoghi standard
    const AA_UI_TASK_PUBBLICATE_FILTER_DLG="GetGeserPubblicateFilterDlg";
    const AA_UI_TASK_BOZZE_FILTER_DLG="GetGeserBozzeFilterDlg";
    const AA_UI_TASK_REASSIGN_DLG="GetGeserReassignDlg";
    const AA_UI_TASK_PUBLISH_DLG="GetGeserPublishDlg";
    const AA_UI_TASK_TRASH_DLG="GetGeserTrashDlg";
    const AA_UI_TASK_RESUME_DLG="GetGeserResumeDlg";
    const AA_UI_TASK_DELETE_DLG="GetGeserDeleteDlg";
    const AA_UI_TASK_ADDNEW_DLG="GetGeserAddNewDlg";
    const AA_UI_TASK_MODIFY_DLG="GetGeserModifyDlg";
    const AA_UI_TASK_ADDNEWMULTI_DLG="GetGeserAddNewMultiDlg";
    //------------------------------------

    //Dialoghi
    
    //report
   
    //Section id
    const AA_ID_SECTION_CRITERI="Criteri";
    //section ui ids
    const AA_UI_DETAIL_GENERALE_BOX = "Generale_Box";

    const AA_UI_TEMPLATE_PRATICHE="Pratiche";

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
        $taskManager->RegisterTask("GetGeserPubblicateFilterDlg");
        $taskManager->RegisterTask("GetGeserBozzeFilterDlg");


        //dati
        $taskManager->RegisterTask("GetGeserModifyDlg");
        $taskManager->RegisterTask("GetGeserBeneficiarioModifyDlg");
        $taskManager->RegisterTask("GetGeserAddNewDlg");
        $taskManager->RegisterTask("GetGeserAddNewMultiDlg");
        $taskManager->RegisterTask("GetGeserAddNewMultiPreviewCalc");
        $taskManager->RegisterTask("GetGeserAddNewMultiPreviewDlg");
        $taskManager->RegisterTask("GetGeserTrashDlg");
        $taskManager->RegisterTask("TrashGeser");
        $taskManager->RegisterTask("GetGeserDeleteDlg");
        $taskManager->RegisterTask("DeleteGeser");
        $taskManager->RegisterTask("GetGeserResumeDlg");
        $taskManager->RegisterTask("ResumeGeser");
        $taskManager->RegisterTask("GetGeserReassignDlg");
        $taskManager->RegisterTask("GetGeserPublishDlg");
        $taskManager->RegisterTask("ReassignGeser");
        $taskManager->RegisterTask("AddNewGeser");
        $taskManager->RegisterTask("UpdateGeserDatiGenerali");
        $taskManager->RegisterTask("UpdateGeserDatiBeneficiario");
        $taskManager->RegisterTask("PublishGeser");
        $taskManager->RegisterTask("GetGeserConfirmPrivacyDlg");
        $taskManager->RegisterTask("GetGeserRevocaModifyDlg");
        $taskManager->RegisterTask("GetGeserRevisionDlg");
        $taskManager->RegisterTask("GetGeserRevisionViewDlg");
        $taskManager->RegisterTask("UpdateGeserDatiRevoca");
        $taskManager->RegisterTask("GetGeserListaCodiciIstat");
        
        //pratiche
        $taskManager->RegisterTask("GetGeserAddNewPraticaDlg");
        $taskManager->RegisterTask("AddNewGeserPratica");
        $taskManager->RegisterTask("GetGeserModifyPraticaDlg");
        $taskManager->RegisterTask("UpdateGeserPratica");
        $taskManager->RegisterTask("GetGeserTrashPraticaDlg");
        $taskManager->RegisterTask("DeleteGeserPratica");
        $taskManager->RegisterTask("GetGeserCopyPraticaDlg");

        //Allegati
        $taskManager->RegisterTask("GetGeserAddNewAllegatoDlg");
        $taskManager->RegisterTask("AddNewGeserAllegato");
        $taskManager->RegisterTask("GetGeserModifyAllegatoDlg");
        $taskManager->RegisterTask("UpdateGeserAllegato");
        $taskManager->RegisterTask("GetGeserTrashAllegatoDlg");
        $taskManager->RegisterTask("DeleteGeserAllegato");
        
        //template dettaglio
        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL,array(
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateGeserDettaglio_Generale_Tab")
        ));

        //$criteri=new AA_GenericModuleSection(static::AA_ID_SECTION_CRITERI,"Criteri e modalita'",true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_CRITERI,$this->GetId(),false,true,false,false,'mdi-text-box-multiple',"TemplateSection_Criteri");
        //$this->AddSection($criteri);

        //$pubblicate=$this->GetSection(static::AA_ID_SECTION_PUBBLICATE);
        //$pubblicate->SetNavbarTemplate(array($this->TemplateGenericNavbar_Bozze(1)->toArray(),$this->TemplateGenericNavbar_Section($criteri,2,true)->toArray()));

        //$criteri->SetNavbarTemplate(array($this->TemplateGenericNavbar_Atti(1,true,true)->toArray()));

        //Custom object template
        //$this->AddObjectTemplate(static::AA_UI_PREFIX."_".static::AA_UI_WND_RENDICONTI_COMUNALI."_".static::AA_UI_LAYOUT_RENDICONTI_COMUNALI,"Template_GetGeserComuneRendicontiViewLayout");
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
            self::$oInstance=new AA_GeserModule($user);
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
        if($this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
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
        if($this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
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
            $params['where'][]=" AND ".AA_Geser::AA_DBTABLE_DATA.".tipologia = '".addslashes($params['tipo'])."'";
        }

        //stato
        if(isset($params['stato']) && $params['stato'] > 0)
        {
            $params['where'][]=" AND ".AA_Geser::AA_DBTABLE_DATA.".stato = '".addslashes($params['stato'])."'";
        }

        return $params;
    }

     //Personalizza il template dei dati delle schede pubblicate per il modulo corrente
     protected function GetDataSectionPubblicate_CustomDataTemplate($data = array(),$object=null)
     {
        $data['pretitolo']=$object->GetTipo();
        $tags="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetStato()."</span>";
        $potenza=$object->GetProp("Potenza");
        if(intVal($potenza)>0)
        {
            $tags.="&nbsp;<span class='AA_DataView_Tag AA_Label AA_Label_Orange'>".$potenza." MWatt</span>";
        }
        $data['tags']=$tags;
        $geolocalizzazione=$object->GetGeolocalizzazione();
        $data['sottotitolo']="<span>".$geolocalizzazione['localita'].", ".$geolocalizzazione['comune']."</span>";

        return $data;
     }

    //Restituisce i dati delle bozze
    public function GetDataSectionBozze_List($params=array())
    {
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER) && !$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER_RO))
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
            $params['where'][]=" AND ".AA_Geser::AA_DBTABLE_DATA.".tipologia = '".addslashes($params['tipo'])."'";
        }

        //stato
        if(isset($params['stato']) && $params['stato'] > 0)
        {
            $params['where'][]=" AND ".AA_Geser::AA_DBTABLE_DATA.".stato = '".addslashes($params['stato'])."'";
        }

        return $params;
    }

    //Personalizza il template dei dati delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomDataTemplate($data = array(),$object=null)
    {
        
        if($object instanceof AA_Geser)
        {

            $data['pretitolo']=$object->GetTipo();
            $tags="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetStato()."</span>";
            $potenza=$object->GetProp("Potenza");
            if(intVal($potenza)>0)
            {
                $tags.="&nbsp;<span class='AA_DataView_Tag AA_Label AA_Label_Orange'>".$potenza." MWatt</span>";
            }
            $data['tags']=$tags;
            $geolocalizzazione=$object->GetGeolocalizzazione();
            $data['sottotitolo']="<span>".$geolocalizzazione['localita'].", ".$geolocalizzazione['comune']."</span>";
        }

        return $data;
    }
    
    //Template publish dlg
    public function Template_GetGeserPublishDlg($params)
    {
        //lista organismi da pubblicare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Geser($curId,$this->oUser);
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
                $wnd->SetSaveTask('PublishGeser');
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
    public function Template_GetGeserDeleteDlg($params)
    {
        return $this->Template_GetGenericObjectDeleteDlg($params,"DeleteGeser");
    }
        
    //Template dlg addnew
    public function Template_GetGeserAddNewDlg()
    {
        $id=$this->GetId()."_AddNew_Dlg_".uniqid();
        
        $form_data=array();
        
        $form_data['Note']="";
        $form_data['AnnoAutorizzazione']="";
        $form_data['AnnoCostruzione']="";
        $form_data['AnnoEsercizio']="";
        $form_data['AnnoDismissione']="";
        $form_data['Stato']=0;
        $form_data['Tipologia']=0;
        $form_data['nome']="";
        $form_data['Potenza']="";
        $form_data['Superficie']="";
        $form_data['Geo_comune']="";
        $form_data['Geo_localita']="";
        $form_data['Geo_coordinate']="";

        $stato=AA_Geser_Const::GetListaStatiImpianto();
        $stato_options=array();
        foreach($stato as $num=>$val)
        {
            $stato_options[]=array("id"=>$num,"value"=>$val);
        }

        $tipologia=AA_Geser_Const::GetListaTipoImpianti();
        $tipo_options=array();
        foreach($tipologia as $num=>$val)
        {
            $tipo_options[]=array("id"=>$num,"value"=>$val);
        }

        $wnd=new AA_GenericFormDlg($id, "Aggiungi un nuovo impianto", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(720);
        $wnd->EnableValidation();

        //Tipologia
        $wnd->AddSelectField("Tipologia","Tipologia",array("required"=>true,"gravity"=>1.5,"validateFunction"=>"IsSelected","bottomPadding"=>32, "bottomLabel"=>"*Selezionare la tipologia di impianto.", "placeholder"=>"...","options"=>$tipo_options));
        
        //Stato
        $wnd->AddSelectField("Stato","Stato attuale",array("required"=>true,"gravity"=>1.5,"validateFunction"=>"IsSelected","bottomPadding"=>32, "bottomLabel"=>"*Selezionare lo stato attuale dell'impianto.", "placeholder"=>"...","options"=>$stato_options),false);
        
        //superficie
        $wnd->AddTextField("Superficie","Superficie",array("required"=>true,"gravity"=>1,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci la superficie (mq) dell'impianto.", "placeholder"=>"es. 150"),false);

        //Nome
        $wnd->AddTextField("nome","Titolo",array("required"=>true,"gravity"=>3,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci una denominazione per l'impianto.", "placeholder"=>"..."));

        //potenza
        $wnd->AddTextField("Potenza","Potenza",array("required"=>true,"gravity"=>1,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci la potenza in megawatt dell'impianto.", "placeholder"=>"es. 150"),false);

        $section=new AA_FieldSet($id."_Riferimenti","Riferimenti temporali");

        //anno autorizzazione
        $section->AddTextField("AnnoAutorizzazione","Anno autorizzazione",array("bottomPadding"=>32, "labelWidth"=>150,"bottomLabel"=>"*Inserisci l'anno in cui e' stata autorizzata la costruzione dell'impianto.", "placeholder"=>"es. 2024"));
        
        //anno costruzione
        $section->AddTextField("AnnoCostruzione","Anno costruzione",array("bottomPadding"=>32, "labelWidth"=>150,"bottomLabel"=>"*Inserisci l'anno in cui e' stata terminata la costruzione dell'impianto.", "placeholder"=>"es. 2024"),false);

        //anno esercizio
        $section->AddTextField("AnnoEsercizio","Anno esercizio",array("bottomPadding"=>32, "labelWidth"=>150,"bottomLabel"=>"*Inserisci l'anno in cui l'impianto e' entrato in esercizio.", "placeholder"=>"es. 2024"));

        //anno dismissione
        $section->AddTextField("AnnoDismissione","Anno dismissione",array("bottomPadding"=>32,"labelWidth"=>150, "bottomLabel"=>"*Inserisci l'anno in cui l'impianto e' stato dismesso.", "placeholder"=>"es. 2024"),false);

        $wnd->AddGenericObject($section);

        //Norma
        $section=new AA_FieldSet($id."_Geolocalizzazione","Geolocalizzazione");

        //localita'
        $section->AddTextField("Geo_localita","Ubicazione",array("required"=>true, "gravity"=>3,"labelWidth"=>90,"bottomLabel"=>"*Inserisci la localita'/indirizzo dell'impianto.", "placeholder"=>"..."));

        //comune
        $section->AddTextField("Geo_comune","Comune",array("required"=>true, "gravity"=>2,"bottomPadding"=>38,"labelWidth"=>90,"bottomLabel"=>"*Inserisci il Comune in cui e' sito l'impianto.", "placeholder"=>"es. Cagliari","suggest"=>array("template"=>"#value#","url"=>$this->taskManagerUrl."?task=GetGeserListaCodiciIstat")));

        //coordinate
        $section->AddTextField("Geo_coordinate","Coordinate",array("gravity"=>1,"bottomPadding"=>38,"labelWidth"=>90, "bottomLabel"=>"*Coordinate geografiche dell'impianto (formato: latitudine,longitudine).", "placeholder"=>"es. 39.217199,9.113311"),false);

        $wnd->AddGenericObject($section);

        //Note
        $label="Note";
        $wnd->AddTextareaField("Note",$label,array("labelWidth"=>90,"bottomLabel"=>"*Eventuali annotazioni (max 4096 caratteri).", "placeholder"=>"Inserisci qui le note..."));
        
        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->SetSaveTask("AddNewGeser");
        
        return $wnd;
    }

    //Template dlg addnew geco da csv
    public function Template_GetGeserAddNewMultiDlg()
    {
        $id=static::AA_UI_PREFIX."_GetGeserAddNewMultiDlg_".uniqid();

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
        $descr.="<p>Tramite il seguente <a href='".$platform->GetModulePathURL($this->GetId())."/docs/geco_addnew_multi.ods' target='_blank'>link</a> è possibile scaricare un foglio elettronico da utilizzarsi come base per la predisposizione del file csv.</p>";
        $descr.="<p>Per la generazione del file csv si consiglia l'utilizzo del software opensource <a href='https://www.libreoffice.org' target='_blank'>Libreoffice</a> in quanto consente di impostare il carattere di delimitazione dei campi e la codifica dei caratteri in fase di esportazione senza dover apportare modifiche al sistema.</p>";
        $descr.="<hr/>";

        $wnd->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","autoheight"=>true,"template"=>"<div style='margin-bottom: 1em;'>Questa funzionalità permette di generare più bozze tramite importazione da file csv.".$descr."</div>")));
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("height"=>30)));

        //csv
        $wnd->AddFileUploadField("GeserAddNewMultiCSV","Scegli il file csv...", array("required"=>true,"validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti in formato csv (dimensione max: 2Mb).","accept"=>"application/csv"));

        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->enableRefreshOnSuccessfulSave(false);

        $wnd->SetApplyButtonName("Procedi");

        $wnd->SetSaveTask("GetGeserAddNewMultiPreviewCalc");
        
        return $wnd;
    }

    //Template dlg addnew multi preview
    public function Template_GetGeserAddNewMultiPreviewDlg()
    {
        $id=static::AA_UI_PREFIX."_GetGeserAddNewMultiPreviewDlg_".uniqid();

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
            array("id"=>"anno","header"=>array("<div style='text-align: center'>Anno</div>",array("content"=>"selectFilter")),"width"=>60, "css"=>array("text-align"=>"left"),"sort"=>"text"),
            array("id"=>"titolo","header"=>array("<div style='text-align: center'>Titolo</div>",array("content"=>"textFilter")),"width"=>250, "css"=>array("text-align"=>"left"),"sort"=>"text"),
            array("id"=>"responsabile","header"=>array("<div style='text-align: center'>Responsabile</div>",array("content"=>"textFilter")),"width"=>250, "css"=>array("text-align"=>"left"),"sort"=>"text"),
            array("id"=>"norma","header"=>array("<div style='text-align: center'>Norma</div>",array("content"=>"textFilter")),"width"=>250, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"modalita","header"=>array("<div style='text-align: center'>Modalita'</div>",array("content"=>"textFilter")),"width"=>150, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"importo_impegnato","header"=>array("<div style='text-align: center'>Impegnato</div>",array("content"=>"textFilter")),"width"=>90, "css"=>array("text-align"=>"right")),
            array("id"=>"importo_erogato","header"=>array("<div style='text-align: center'>Erogato</div>",array("content"=>"textFilter")),"width"=>90, "css"=>array("text-align"=>"right")),
            array("id"=>"beneficiario","header"=>array("<div style='text-align: center'>Beneficiario</div>",array("content"=>"textFilter")),"width"=>250, "sort"=>"text","css"=>array("text-align"=>"left")),
            array("id"=>"persona_fisica","header"=>array("<div style='text-align: center'>Persona fisica</div>",array("content"=>"selectFilter")),"width"=>90, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"privacy","header"=>array("<div style='text-align: center'>Privacy</div>",array("content"=>"selectFilter")),"width"=>90, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"note","header"=>array("<div style='text-align: center'>Note</div>",array("content"=>"textFilter")),"width"=>250, "css"=>array("text-align"=>"right"),"sort"=>"text")
        );

        $data=AA_SessionVar::Get("GeserAddNewMultiFromCSV_ParsedData")->GetValue();
        
        AA_SessionVar::UnsetVar("GeserAddNewMultiFromCSV_ParsedData");

        if(!is_array($data))
        {
            AA_Log::Log(__METHOD__." - dati csv non validi: ".print_r($data,TRUE),100);
            $data=array();
        }

        //AA_Log::Log(__METHOD__." - dati csv: ".print_r($data,TRUE),100);

        $desc="<p>Sono stati riconosciute <b>".sizeof((array)$data)." voci</b> differenti.</p>";
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
            "columns"=>$columns,
            "data"=>array_values($data)
        ));
        $scrollview->addRowToBody($table);

        $wnd->AddGenericObject($scrollview);

        $wnd->EnableCloseWndOnSuccessfulSave();

        //$wnd->enableRefreshOnSuccessfulSave();

        $wnd->SetApplyButtonName("Procedi");

        $wnd->SetSaveTask("GeserAddNewMulti");
        
        return $wnd;
    }

    //Template confirm 
    public function Template_GetGeserConfirmPrivacyDlg($form_id='')
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

    //Template view revision 
    public function Template_GetGeserRevisionViewDlg($object=null)
    {
        $id=$this->GetId()."_".uniqid();

        $wnd=new AA_GenericWindowTemplate($id, "Revisioni pubblicazione", $this->id);
        
        $wnd->SetWidth(1280);
        $wnd->SetHeight(720);

        if($object instanceof AA_Geser)
        {
            $revisioni=$object->GetRevisione();
            $data=array();
            foreach($revisioni as $key=>$val)
            {
                $tipo="redazionale";
                if($val['tipo']==1) $tipo="sostanziale";
                $data[]=array("id"=>$key,"date"=>$val['data'],"tipo"=>$tipo,"estremi"=>$val['estremi'],"causale"=>$val['causale']);
            }

            $table=new AA_GenericDatatableTemplate($id."_Table","",4);
            $table->EnableScroll(false,true);
            $table->EnableRowOver();
            $table->EnableAutoRowsHeight();
            $table->SetColumnHeaderInfo(0,"date","Data",120,"textFilter","text","CriteriTable");
            $table->SetColumnHeaderInfo(1,"tipo","Tipo",120,"selectFilter","text","CriteriTable");
            $table->SetColumnHeaderInfo(2,"estremi","Estremi",300,"textFilter","text","CriteriTable_left");
            $table->SetColumnHeaderInfo(3,"causale","Causale","fillspace","textFilter","text","CriteriTable_left");
            $table->SetData($data);

            $wnd->AddView($table);
        }
        else
        {
            $wnd->AddView(new AA_JSON_Template_Generic());
        }
        
        return $wnd;
    }

    //Template dlg aggiungi allegato/link
    public function Template_GetGeserAddNewAllegatoDlg($object=null)
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
        $tipo_allegati=AA_Geser_Const::GetListaTipoAllegati();
        foreach($tipo_allegati as $key=>$val)
        {
            $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("tipo", "Tipologia", array("gravity"=>1,"required"=>true,"validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere la tipologia di allegato/link", "placeholder" => "Scegli un elemento della lista...","options"=>$options));
        
        //Descrizione
        $wnd->AddTextField("descrizione", "Descrizione", array("gravity"=>1,"required"=>true,"bottomLabel" => "*Inserisci una breve descrizione dell'allegato/link","placeholder" => "es. DGR..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        //categorie
        /*$tipi=AA_Geser_Const::GetCategorieAllegati();$curRow=1;
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
        $wnd->SetSaveTask("AddNewGeserAllegato");
        
        return $wnd;
    }

    //Template dlg aggiungi pratica
    public function Template_GetGeserAddNewPraticaDlg($object=null)
    {
        $id=uniqid();
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        if(!($object instanceof AA_Geser))
        {
            return new AA_GenericWindowTemplate(uniqid(),"Aggiungi nuova pratica",$this->GetId());
        }

        $form_data=array();
        $form_data['id']=$object->GetId();
        $wnd=new AA_GenericFormDlg($id, "Aggiungi nuova pratica", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(980);
        $wnd->SetHeight(680);

        //tipo
        $options=array();
        $listaTipo=AA_Geser_Const::GetListaTipoPratica();
        foreach($listaTipo as $key=>$val)
        {
            if($key > 0) $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("tipo","Tipo",array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il tipo di pratica.","options"=>$options));

        //stato
        $options=array();
        $listaTipo=AA_Geser_Const::GetListaStatiPratica();
        foreach($listaTipo as $key=>$val)
        {
            if($key > 0) $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("stato","Stato",array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare lo stato della pratica.","options"=>$options),false);

        //Estremi
        $wnd->AddTextField("estremi", "Estremi", array("gravity"=>2,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Inserisci il numero e la data della pratica.","placeholder" => "es. prot. n.xx del xxxx/xx/xx..."));

        //via
        $options=array();
        $listaTipo=AA_Geser_Const::GetListaTipoVia();
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
        $wnd->SetSaveTask("AddNewGeserPratica");
        
        return $wnd;
    }

    //Template dlg modifica pratica
    public function Template_GetGeserModifyPraticaDlg($object=null,$pratica=null)
    {
        $id=uniqid();
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        if(!($object instanceof AA_Geser) || !is_array($pratica))
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
        $listaTipo=AA_Geser_Const::GetListaTipoPratica();
        foreach($listaTipo as $key=>$val)
        {
            if($key > 0) $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("tipo","Tipo",array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il tipo di pratica.","options"=>$options));

        //stato
        $options=array();
        $listaTipo=AA_Geser_Const::GetListaStatiPratica();
        foreach($listaTipo as $key=>$val)
        {
            if($key > 0) $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("stato","Stato",array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare lo stato della pratica.","options"=>$options),false);

        //Estremi
        $wnd->AddTextField("estremi", "Estremi", array("gravity"=>2,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Inserisci il numero e la data della pratica.","placeholder" => "es. prot. n.xx del xxxx/xx/xx..."));

        //via
        $options=array();
        $listaTipo=AA_Geser_Const::GetListaTipoVia();
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
        $wnd->SetSaveTask("UpdateGeserPratica");
        
        return $wnd;
    }

    //Template dlg copia pratica
    public function Template_GetGeserCopyPraticaDlg($object=null,$pratica=null)
    {
        $id=uniqid();
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        if(!($object instanceof AA_Geser) || !is_array($pratica))
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
        $listaTipo=AA_Geser_Const::GetListaTipoPratica();
        foreach($listaTipo as $key=>$val)
        {
            if($key > 0) $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("tipo","Tipo",array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il tipo di pratica.","options"=>$options));

        //stato
        $options=array();
        $listaTipo=AA_Geser_Const::GetListaStatiPratica();
        foreach($listaTipo as $key=>$val)
        {
            if($key > 0) $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("stato","Stato",array("gravity"=>1,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare lo stato della pratica.","options"=>$options),false);

        //Estremi
        $wnd->AddTextField("estremi", "Estremi", array("gravity"=>2,"required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Inserisci il numero e la data della pratica.","placeholder" => "es. prot. n.xx del xxxx/xx/xx..."));

        //via
        $options=array();
        $listaTipo=AA_Geser_Const::GetListaTipoVia();
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
        $wnd->SetSaveTask("AddNewGeserPratica");
        
        return $wnd;
    }

    //Template dlg aggiungi criteri
    public function Template_GetGeserModifyCriteriDlg($criterio=null)
    {
        $id=uniqid();
        
        if(!($criterio instanceof AA_Geser_Criteri))
        {
            $wnd=new AA_GenericWindowTemplate($id, "Modifica Criteri e modalita'", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<div>id criterio non valido.</div>")));

            return $wnd;
        }

        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        $form_data=array(
            "id"=>$criterio->GetProp('id'),
            "estremi"=>$criterio->GetProp('estremi'),
            "tipo"=>$criterio->GetProp('tipo'),
            "descrizione"=>$criterio->GetProp('descrizione'),
            "url"=>$criterio->GetProp('url'),
            "anno"=>$criterio->GetProp('anno')
        );

        //categorie
        $tipi=AA_Geser_Const::GetCategorieAllegati();
        $section=new AA_FieldSet($id."_Section_Tipo","Categorie");
        $categorie=$criterio->GetProp('categorie');
        $curRow=0;
        foreach($tipi as $tipo=>$descr)
        {
            $newLine=false;
            if($curRow%4 == 0 && $curRow >= 4) $newLine=true;
            $section->AddCheckBoxField("categoria_".$tipo, $descr, array("value"=>1,"bottomPadding"=>8,"labelAlign"=>"right","labelWidth"=>180),$newLine);
            if(($categorie&$tipo)>0) $form_data['categoria_'.$tipo]=1;
            $curRow++;
        }

        for($i=$curRow%4;$i<4;$i++)
        {
            $section->AddSpacer(false);
        }
        //----------------------

        $wnd=new AA_GenericFormDlg($id, "Modifica Criteri e modalita'", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(980);
        $wnd->SetHeight(820);

        //anno
        $options=array();
        for($id=date("Y"); $id>=date("Y")-20;$id--)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$id);
        }
        $wnd->AddSelectField("anno","Anno",array("gravity"=>1,"required"=>true,"labelWidth"=>80,"validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare l'anno di riferimento.","options"=>$options,"value"=>"0"));

        //tipologia
        $options=array();
        $listaTipo=AA_Geser_Const::GetTipoCriteri();
        foreach($listaTipo as $id=>$val)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$val);
        }
        $wnd->AddSelectField("tipo","Tipo",array("gravity"=>2,"required"=>true,"labelWidth"=>120,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il tipo di atto.","options"=>$options),false);

        //Estremi
        $wnd->AddTextareaField("estremi", "Estremi", array("gravity"=>1,"required"=>true,"labelWidth"=>80,"labelAlign"=>"right","bottomLabel" => "*Inserisci numero e data del documento.","placeholder" => "es. DGR..."));

        //descrizione
        $wnd->AddTextareaField("descrizione", "Descrizione", array("gravity"=>2,"required"=>true,"labelWidth"=>120,"labelAlign"=>"right","bottomLabel" => "*Inserisci una breve dscrizione (max 1024).","placeholder" => "..."),false);
    
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>20)));

        $wnd->AddGenericObject($section);

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
        $wnd->SetSaveTask("UpdateGeserCriteri");
        
        return $wnd;
    }

    //Template dlg modifca allegato/link
    public function Template_GetGeserModifyAllegatoDlg($object=null,$allegato=null)
    {
        $id=static::AA_UI_PREFIX."_GetGeserModifyAllegatoDlg";
        
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
        $tipo_allegati=AA_Geser_Const::GetListaTipoAllegati();
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
        $wnd->SetSaveTask("UpdateGeserAllegato");
        
        return $wnd;
    }

    //Template dlg trash allegato
    public function Template_GetGeserTrashCriteriDlg($object=null)
    {
        $id=uniqid();
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina criterio", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("descrizione"=>$object->GetProp('descrizione'),"estremi"=>$object->GetProp('estremi'));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente criterio verrà eliminato, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"estremi", "header"=>"Estremi", "fillspace"=>true),
              array("id"=>"descrizione", "header"=>"Descrizione", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteGeserCriteri");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetProp("id")));
        
        return $wnd;
    }

    //Template dlg trash pratica
    public function Template_GetGeserTrashPraticaDlg($object=null,$pratica=null)
    {
        $id=uniqid();
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina pratica", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(400);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("descrizione"=>$pratica['descrizione'],"estremi"=>$pratica['estremi']);
      
        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p></div>";
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"La seguente pratica verrà eliminata, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"descrizione", "header"=>"Descrizione", "fillspace"=>true),
              array("id"=>"estremi", "header"=>"Estremi", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteGeserPratica");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_pratica"=>$pratica['id']));
        
        return $wnd;
    }

    //Template dlg trash allegato
    public function Template_GetGeserTrashAllegatoDlg($object=null,$allegato=null)
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
        $wnd->SetSaveTask("DeleteGeserAllegato");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_allegato"=>$allegato['id']));
        
        return $wnd;
    }

    //Task Aggiungi allegato
    public function Task_AddNewGeserAllegato($task)
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

        $object=new AA_Geser($_REQUEST['id'], $this->oUser);
        
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

    //Template dlg modify geser
    public function Template_GetGeserModifyDlg($object=null)
    {
        $id=$this->GetId()."_Modify_Dlg_".uniqid();
        if(!($object instanceof AA_Geser)) return new AA_GenericWindowTemplate($id, "Modifica i dati generali dell'impianto", $this->id);

        $form_data=array();
        
        $form_data['id']=$object->GetId();
        $form_data['Note']=$object->GetProp("Note");
        $form_data['AnnoAutorizzazione']=$object->GetProp("AnnoAutorizzazione");
        $form_data['AnnoCostruzione']=$object->GetProp("AnnoCostruzione");
        $form_data['AnnoEsercizio']=$object->GetProp("AnnoEsercizio");
        $form_data['AnnoDismissione']=$object->GetProp("AnnoDismissione");
        $form_data['Stato']=$object->GetProp("Stato");;
        $form_data['Tipologia']=$object->GetProp("Tipologia");
        $form_data['nome']=$object->GetName();
        $form_data['Potenza']=$object->GetProp("Potenza");
        $form_data['Superficie']=$object->GetProp("Superficie");
       
        $geolocalizzazione=$object->GetGeolocalizzazione();
        if(sizeof($geolocalizzazione)==0)
        {
            $form_data['Geo_comune']="";
            $form_data['Geo_localita']="";
            $form_data['Geo_coordinate']="";
        }
        else
        {
            $form_data['Geo_comune']=$geolocalizzazione['comune'];
            $form_data['Geo_localita']=$geolocalizzazione['localita'];
            $form_data['Geo_coordinate']=$geolocalizzazione['coordinate'];
        }

        $stato=AA_Geser_Const::GetListaStatiImpianto();
        $stato_options=array();
        foreach($stato as $num=>$val)
        {
            $stato_options[]=array("id"=>$num,"value"=>$val);
        }

        $tipologia=AA_Geser_Const::GetListaTipoImpianti();
        $tipo_options=array();
        foreach($tipologia as $num=>$val)
        {
            $tipo_options[]=array("id"=>$num,"value"=>$val);
        }

        $wnd=new AA_GenericFormDlg($id, "Aggiungi un nuovo impianto", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(720);
        $wnd->EnableValidation();

        //Tipologia
        $wnd->AddSelectField("Tipologia","Tipologia",array("required"=>true,"gravity"=>1.5,"validateFunction"=>"IsSelected","bottomPadding"=>32, "bottomLabel"=>"*Selezionare la tipologia di impianto.", "placeholder"=>"...","options"=>$tipo_options));
        
        //Stato
        $wnd->AddSelectField("Stato","Stato attuale",array("required"=>true,"gravity"=>1.5,"validateFunction"=>"IsSelected","bottomPadding"=>32, "bottomLabel"=>"*Selezionare lo stato attuale dell'impianto.", "placeholder"=>"...","options"=>$stato_options),false);
        
        //superficie
        $wnd->AddTextField("Superficie","Superficie",array("required"=>true,"gravity"=>1,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci la superficie (mq) dell'impianto.", "placeholder"=>"es. 150"),false);

        //Nome
        $wnd->AddTextField("nome","Titolo",array("required"=>true,"gravity"=>3,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci una denominazione per l'impianto.", "placeholder"=>"..."));

        //potenza
        $wnd->AddTextField("Potenza","Potenza",array("required"=>true,"gravity"=>1,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci la potenza in megawatt dell'impianto.", "placeholder"=>"es. 150"),false);

        $section=new AA_FieldSet($id."_Riferimenti","Riferimenti temporali");

        //anno autorizzazione
        $section->AddTextField("AnnoAutorizzazione","Anno autorizzazione",array("bottomPadding"=>32, "labelWidth"=>150,"bottomLabel"=>"*Inserisci l'anno in cui e' stata autorizzata la costruzione dell'impianto.", "placeholder"=>"es. 2024"));
        
        //anno costruzione
        $section->AddTextField("AnnoCostruzione","Anno costruzione",array("bottomPadding"=>32, "labelWidth"=>150,"bottomLabel"=>"*Inserisci l'anno in cui e' stata terminata la costruzione dell'impianto.", "placeholder"=>"es. 2024"),false);

        //anno esercizio
        $section->AddTextField("AnnoEsercizio","Anno esercizio",array("bottomPadding"=>32, "labelWidth"=>150,"bottomLabel"=>"*Inserisci l'anno in cui l'impianto e' entrato in esercizio.", "placeholder"=>"es. 2024"));

        //anno dismissione
        $section->AddTextField("AnnoDismissione","Anno dismissione",array("bottomPadding"=>32,"labelWidth"=>150, "bottomLabel"=>"*Inserisci l'anno in cui l'impianto e' stato dismesso.", "placeholder"=>"es. 2024"),false);

        $wnd->AddGenericObject($section);

        //Norma
        $section=new AA_FieldSet($id."_Geolocalizzazione","Geolocalizzazione");

        //localita'
        $section->AddTextField("Geo_localita","Ubicazione",array("required"=>true, "gravity"=>3,"labelWidth"=>90,"bottomLabel"=>"*Inserisci la localita'/indirizzo dell'impianto.", "placeholder"=>"..."));

        //comune
        $section->AddTextField("Geo_comune","Comune",array("required"=>true, "gravity"=>2,"bottomPadding"=>38,"labelWidth"=>90,"bottomLabel"=>"*Inserisci il Comune in cui e' sito l'impianto.", "placeholder"=>"es. Cagliari","suggest"=>array("template"=>"#value#","url"=>$this->taskManagerUrl."?task=GetGeserListaCodiciIstat")));

        //coordinate
        $section->AddTextField("Geo_coordinate","Coordinate",array("gravity"=>1,"bottomPadding"=>38,"labelWidth"=>90, "bottomLabel"=>"*Coordinate geografiche dell'impianto (formato: latitudine,longitudine).", "placeholder"=>"es. 39.217199,9.113311"),false);

        $wnd->AddGenericObject($section);

        //Note
        $label="Note";
        $wnd->AddTextareaField("Note",$label,array("labelWidth"=>90,"bottomLabel"=>"*Eventuali annotazioni (max 4096 caratteri).", "placeholder"=>"Inserisci qui le note..."));

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateGeserDatiGenerali");
        
        return $wnd;
    }

    //Template dlg modify beneficiario
    public function Template_GetGeserBeneficiarioModifyDlg($object=null)
    {
        $id=$this->GetId()."_Modify_Dlg";
        if(!($object instanceof AA_Geser)) return new AA_GenericWindowTemplate($id, "Modifica i dati beneficiario", $this->id);

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
        $wnd->SetSaveTask("UpdateGeserDatiBeneficiario");
        
        return $wnd;
    }

    //Template dlg modify beneficiario
    public function Template_GetGeserRevocaDlg($object=null)
    {
        $id=$this->GetId()."_Revoca_Dlg_".uniqid();
        if(!($object instanceof AA_Geser)) return new AA_GenericWindowTemplate($id, "Modifica i dati di revoca", $this->id);

        $revoca=$object->GetRevoca();
        $form_data=array();
        $form_data['id']=$object->GetId();
        $form_data['Revoca']=0;
        if(isset($revoca['data']) && $revoca['data'] != "")
        {
            $form_data['Revocata']=1;
            $form_data['Revoca_data']=$revoca['data'];
            $form_data['Revoca_estremi']=$revoca['estremi'];
            $form_data['Revoca_causale']=$revoca['causale'];
        }
        else
        {
            $form_data['Revoca_data']="";
            $form_data['Revoca_estremi']="";
            $form_data['Revoca_causale']="";    
        }
       
        $wnd=new AA_GenericFormDlg($id, "Modifica i dati di revoca", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(520);
        $wnd->EnableValidation();
              
        //Revocata
        $wnd->AddCheckBoxField("Revocata","Contributo revocato", array("bottomPadding"=>32,"labelWidth"=>140, "gravity"=>1, "bottomLabel"=>"*Abilita se il contributo e' stato revocato.","relatedView"=>$id."_Dati_revoca", "relatedAction"=>"show"));

        $section=new AA_FieldSet($id."_Dati_revoca","Dati di revoca",$wnd->GetFormId(),1,array("type"=>"clean","hidden"=>true));
        
        $section->AddDateField("Revoca_data","Data",array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>80));

        $section->AddSpacer(false);

        $section->AddTextField("Revoca_estremi","Estremi",array("gravity"=>1,"required"=>true,"labelWidth"=>80,"bottomPadding"=>32, "bottomLabel"=>"*Inserisci gli estremi del documento di revoca (max 250 caratteri).", "placeholder"=>"es. prot.n. 123 del 2024-06-01..."));
       
        $section->AddTextareaField("Revoca_causale","Causale",array("gravity"=>1,"required"=>true,"labelWidth"=>80,"bottomPadding"=>32, "bottomLabel"=>"*Inserisci una breve descrizione dei motivi della revoca (max 1000 caratteri).", "placeholder"=>"es. Revocato per assenza dei requisiti..."));

        $wnd->AddGenericObject($section);

        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateGeserDatiRevoca");
        
        return $wnd;
    }

    //Template dlg modify beneficiario
    public function Template_GetGeserRevisionDlg($params=null)
    {
        $id=$this->GetId()."_Revisione_Dlg_".uniqid();
        if(!is_array($params)) return new AA_GenericWindowTemplate($id, "Dati di revisione", $this->id);

        foreach($params as $key=>$val)   
        {
            $form_data[$key]=$val;
        }

        $form_data['Revisione_data']=date("Y-m-d");
        $form_data['Revisione_estremi']="";
        $form_data['Revisione_causale']="";
       
        $wnd=new AA_GenericFormDlg($id, "Dati di revisione", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(400);
        $wnd->EnableValidation();
              
        //Revocata
        $wnd->AddCheckBoxField("Revisione_tipo"," ", array("bottomPadding"=>32,"labelWidth"=>80, "gravity"=>1, "labelRight"=>"<b>Modifica sostanziale</b>","bottomLabel"=>"*Abilita se la modifica e' di tipo sostanziale.","relatedView"=>$id."_Field_Revisione_estremi", "relatedAction"=>"show"));

        //$section=new AA_FieldSet($id."_Dati_revisione","Dettagli revisione",$wnd->GetFormId(),1,array("type"=>"clean","hidden"=>true));
        
        //$section->AddDateField("Revoca_data","Data",array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>80));

        //$section->AddSpacer(false);

        $wnd->AddTextField("Revisione_estremi","Estremi",array("gravity"=>1,"required"=>true,"labelWidth"=>80,"bottomPadding"=>32, "bottomLabel"=>"*Inserisci il numero e la data del documento di modifica (max 250 caratteri).", "placeholder"=>"es. prot.n. 123 del 2024-06-01..."));
       
        //$wnd->AddGenericObject($section);

        $wnd->AddTextareaField("Revisione_causale","Causale",array("gravity"=>1,"required"=>true,"labelWidth"=>80,"bottomPadding"=>32, "bottomLabel"=>"*Inserisci una breve descrizione dei motivi della modifica (max 1000 caratteri).", "placeholder"=>"..."));

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateGeserDatiGenerali");
        
        return $wnd;
    }

    public function Template_GetGeserHelpDlg()
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
        //$params['DetailOptionTab']=array(array("id"=>$id, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateGeserDettaglio_Generale_Tab"));
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER)) $params['readonly']=true;
        
        $params['MultiviewEventHandlers']=array("onViewChange"=>array("handler"=>"onDetailViewChange"));

        $params['disable_SaveAsPdf']=true;
        $params['disable_SaveAsCsv']=true;
        //$params['disable_trash']=true;
        //$params['disable_public_trash']=true;

        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER)) $params['disable_MenuAzioni']=true;
        
        $detail = $this->TemplateGenericSection_Detail($params);

        return $detail;
    }

    //lista pratiche
    public function TemplateDettaglio_Pratiche($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_TEMPLATE_PRATICHE;
        $canModify=false;

        #pratiche----------------------------------
        if($this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER)) $canModify=true;

        $stati_pratica=AA_Geser_Const::GetListaStatiPratica();
        $tipo_pratica=AA_Geser_Const::GetListaTipoPratica();
        $tipo_via=AA_Geser_Const::GetListaTipoVia();
        $pratiche=$object->GetPratiche();
        foreach($pratiche as $id_pratica=>$curPratica)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGeserTrashPraticaDlg", params: [{id:"'.$object->GetId().'"},{id_pratica:"'.$id_pratica.'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGeserModifyPraticaDlg", params: [{id:"'.$object->GetId().'"},{id_pratica:"'.$id_pratica.'"}]},"'.$this->id.'")';
            $copy='AA_MainApp.utils.callHandler("dlg", {task:"GetGeserCopyPraticaDlg", params: [{id:"'.$object->GetId().'"},{id_pratica:"'.$id_pratica.'"}]},"'.$this->id.'")';
            if($canModify) $ops="<div class='AA_DataTable_Ops' style='justify-content: space-between;width: 100%'><a class='AA_DataTable_Ops_Button' title='Copia' onClick='".$copy."'><span class='mdi mdi-content-copy'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $ops="&nbsp;";

            $pratiche_data[]=array("id"=>$id_pratica,"rif_temporali"=>"<div style='display:flex; flex-direction:column; justify-content:center'><span><b>Data inizio</b>: ".$curPratica['data_inizio']."</span><span><b>Data fine</b>: ".$curPratica['data_fine']."</span></div>","stato"=>$stati_pratica[$curPratica['stato']],"tipo"=>$tipo_pratica[$curPratica['tipo']],"estremi"=>$curPratica['estremi'],"descrizione"=>$curPratica['descrizione'],"via"=>$tipo_via[$curPratica['via']],"societa"=>$curPratica['societa'],"note"=>$curPratica['note'],"ops"=>$ops);
        }

        $template=new AA_GenericDatatableTemplate($id,"Gestione pratiche",9,null,array("css"=>"AA_Header_DataTable"));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader();
        $template->SetHeaderHeight(38);

        if($canModify) 
        {
            $template->EnableAddNew(true,"GetGeserAddNewPraticaDlg");
            $template->SetAddNewTaskParams(array("postParams"=>array("id"=>$object->GetId())));
        }

        $template->SetColumnHeaderInfo(0,"stato","<div style='text-align: center'>Stato</div>",120,"selectFilter","text","PraticheTable_left");
        $template->SetColumnHeaderInfo(1,"tipo","<div style='text-align: center'>Tipologia</div>",160,"selectFilter","text","PraticheTable_left");
        $template->SetColumnHeaderInfo(2,"rif_temporali","<div style='text-align: center'>Rif. temporali</div>",200,"textFilter","text","PraticheTable_left");
        $template->SetColumnHeaderInfo(3,"descrizione","<div style='text-align: center'>Descrizione</div>","fillspace","textFilter","text","PraticheTable_left");
        $template->SetColumnHeaderInfo(4,"estremi","<div style='text-align: center'>Estremi</div>","fillspace","textFilter","text","PraticheTable");
        $template->SetColumnHeaderInfo(5,"via","<div style='text-align: center'>Tipo VIA</div>",120,"selectFilter","text","PraticheTable");
        $template->SetColumnHeaderInfo(6,"societa","<div style='text-align: center'>Ragione sociale</div>",200,"selectFilter","text","PraticheTable");
        $template->SetColumnHeaderInfo(7,"note","<div style='text-align: center'>Note</div>","fillspace","textFilter","text","PraticheTable_left");
        $template->SetColumnHeaderInfo(8,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"PraticheTable");

        $template->SetData($pratiche_data);

        return $template;
    }
    
    //Template section detail, tab generale
    public function TemplateGeserDettaglio_Generale_Tab($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX;

        if(!($object instanceof AA_Geser)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));

        $rows_fixed_height=50;
        $canModify=false;
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0 && $this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER)) $canModify=true;

        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>32,"type"=>"clean","borderless"=>true));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("width"=>120)));
        $toolbar->AddElement(new AA_JSON_Template_Generic());

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
                 "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetGeserRevisionViewDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
             ));
             $toolbar->AddElement($revision_btn);
        }
        else*/
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("width"=>120)));
        }

        $layout=$this->TemplateGenericDettaglio_Header_Generale_Tab($object,$id,$toolbar,$canModify);
        
        //stato
        $value="<span class='AA_Label AA_Label_Green'>".$object->GetStato()."</span>";
        $stato=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Stato:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //tipologia
        $value="<span class='AA_Label AA_Label_Blue_Simo'>".$object->GetTipo()."</span>";
        $tipo=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Tipologia impianto:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //potenza
        $value=$object->GetProp("Potenza");
        if(intVal($value)>0) $value="<span class='AA_Label AA_Label_Orange'>".$value." MWatt</span>";
        $potenza=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Potenza:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //superficie
        $value=$object->GetProp("Superficie");
        if(intVal($value)>0) $value.=" mq";
        $superficie=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Superficie:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //denominazione
        $value=$object->GetDescr();
        $nome=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Denominazione:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //anno autorizzazione
        $value=$object->GetProp("AnnoAutorizzazione");
        if($value=="")$value="n.d.";
        $anno_autorizzazione=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "gravity"=>1,
            "width"=>180,
            "data"=>array("title"=>"Anno autorizzazione:","value"=>$value)
        ));

        //anno costruzione
        $value=$object->GetProp("AnnoCostruzione");
        if($value=="")$value="n.d.";
        $anno_costruzione=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "gravity"=>1,
            "width"=>180,
            "data"=>array("title"=>"Anno costruzione:","value"=>$value)
        ));

        //anno esercizio
        $value=$object->GetProp("AnnoEsercizio");
        if($value=="")$value="n.d.";
        $anno_esercizio=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "gravity"=>1,
            "width"=>200,
            "data"=>array("title"=>"Anno entrata in esercizio:","value"=>$value)
        ));

        //anno dismissione
        $value=$object->GetProp("AnnoDismissione");
        if($value=="")$value="n.d.";
        $anno_dismissione=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "gravity"=>1,
            "width"=>150,
            "data"=>array("title"=>"Anno dismissione:","value"=>$value)
        ));
        
        //note
        $value = $object->GetProp("Note");
        $note=new AA_JSON_Template_Template($id."_Note",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Note:","value"=>$value)
        ));

        //geolocalizzazione
        $geolocalizzazione=$object->GetGeolocalizzazione();

        $value = $geolocalizzazione['localita'];
        $localita=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Ubicazione:","value"=>$value)
        ));
        $value = $geolocalizzazione['comune'];
        $comune=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Comune:","value"=>$value)
        ));
        $value = $geolocalizzazione['coordinate'];
        $coordinate=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Coordinate:","value"=>$value)
        ));
        
        //prima riga
        $riga=new AA_JSON_Template_Layout("",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($tipo);
        $riga->AddCol($stato);
        $riga->AddCol($potenza);
        $riga->AddCol($superficie);
        $riga->AddCol($anno_autorizzazione);
        $riga->AddCol($anno_costruzione);
        $riga->AddCol($anno_esercizio);
        $riga->AddCol($anno_dismissione);
        $layout->AddRow($riga);

        //seconda riga
        $riga=new AA_JSON_Template_Layout("",array("gravity"=>1,"height"=>180,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $layout_gen=new AA_JSON_Template_Layout("",array("gravity"=>2,"type"=>"clean"));
        $layout_geo=new AA_JSON_Template_Layout("",array("gravity"=>1,"type"=>"clean"));
        //$layout_gen->addRow($nome);
        $layout_gen->addRow($note);
        $layout_geo->AddRow($localita);
        $layout_geo->AddRow($comune);
        $layout_geo->AddRow($coordinate);
        $riga->addCol($layout_geo);
        $riga->addCol($layout_gen);
        $layout->AddRow($riga);

        //-------------------- Allegati --------------------------------------
        //$allegati_box->AddRow($this->TemplateDettaglio_Allegati($object,$id,$canModify));
        //$riga->AddCol($allegati_box);
        //------------------------------------------------------------------------
      
        //-------------------- Pratiche --------------------------------------
        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        $toolbar->AddElement(new AA_JSON_Template_Generic(""));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"<span style='color:#003380'>Gestione Pratiche</span>", "align"=>"center")));
        $toolbar->AddElement(new AA_JSON_Template_Generic(""));
        $layout->AddRow($toolbar);
        $layout->AddRow($this->TemplateDettaglio_Pratiche($object));
        //------------------------------------------------------------------------

        return $layout;
    }

    //Template section detail, tab generale
    public function TemplateGeserDettaglio_Allegati_Tab($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_ALLEGATI_BOX;

        if(!($object instanceof AA_Geser)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
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
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetGeserAddNewAllegatoDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
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
            
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGeserTrashAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGeserModifyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            $copy='AA_MainApp.utils.callHandler("dlg", {task:"GetGeserCopyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
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

    //Task Update Geser
    public function Task_UpdateGeserDatiGenerali($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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
            $task->SetError("Il titolo non puo' essere vuoto o composto da soli spazi.",false);

            return false;
        }

        $geolocalizzazione=array();
        
        if(isset($_REQUEST['Geo_comune'])) $geolocalizzazione['comune']=trim($_REQUEST['Geo_comune']);
        if(isset($_REQUEST['Geo_localita'])) $geolocalizzazione['localita']=trim($_REQUEST['Geo_localita']);
        if(isset($_REQUEST['Geo_coordinate'])) $geolocalizzazione['coordinate']=trim($_REQUEST['Geo_coordinate']);

        if(sizeof($geolocalizzazione)>0) $_REQUEST['Geolocalizzazione']=json_encode($geolocalizzazione);
        //-----------------------------------------------
        
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

    //Task Update Geser
    public function Task_UpdateGeserDatiBeneficiario($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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

    //Task Update Geser Revoca
    public function Task_UpdateGeserDatiRevoca($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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

        $revoca=array();

        //----------- verify values ---------------------
        if($_REQUEST['Revocata']==1)
        {
            if(!isset($_REQUEST['Revoca_data']) || !AA_Utils::validateDate($_REQUEST['Revoca_data'],"Y-m-d H:i:s"))
            {
                $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
                $task->SetError("La data indicata non e' valida.",false);
    
                return false;
            }

            if(!isset($_REQUEST['Revoca_estremi']) || trim($_REQUEST['Revoca_estremi'])=="")
            {
                $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare gli estremi del documento di revoca.",false);
    
                return false;
            }

            if(!isset($_REQUEST['Revoca_causale']) || trim($_REQUEST['Revoca_causale'])=="")
            {
                $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare la causale della revoca.",false);
    
                return false;
            }

            $revoca['data']=substr($_REQUEST['Revoca_data'],0,10);
            $revoca['estremi']=trim($_REQUEST['Revoca_estremi']);
            $revoca['causale']=trim($_REQUEST['Revoca_causale']);
        }
        else
        {
            $revoca['data']="";
            $revoca['estremi']="";
            $revoca['causale']="";
        }
        //--------------------------------------------------------------------------
        
        $_REQUEST['Revoca']=json_encode($revoca);
        $object->Parse($_REQUEST);
        
        //AA_Log::Log(__METHOD__." - object: ".print_r($object,true),100);
        
        if(!$object->Update($this->oUser,true,"Aggiornamento dati di revoca"))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dei dati di revoca.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }
    
    //Task trash Geser
    public function Task_TrashGeser($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $task->SetError("L'utente corrente non ha i permessi per cestinare l'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per cestinare l'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        return $this->Task_GenericTrashObject($task,$_REQUEST);
    }
    
    //Task resume Geser
    public function Task_ResumeGeser($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericResumeObject($task,$_REQUEST);
    }
    
    //Task publish Geser
    public function Task_PublishGeser($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericPublishObject($task,$_REQUEST);
    }
    
    //Task reassign Geser
    public function Task_ReassignGeser($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        return $this->Task_GenericReassignObject($task,$_REQUEST);
    }
    
    //Task delete Geser
    public function Task_DeleteGeser($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
         
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $task->SetError("L'utente corrente non ha i permessi per eliminare l'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per eliminare l'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        return $this->Task_GenericDeleteObject($task,$_REQUEST);
    }
    
    //Task Aggiungi provvedimenti
    public function Task_AddNewGeser($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi",false);

            return false;
        }
        
        //----------- verify values ---------------------
        if(trim($_REQUEST['nome']) == "")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Il titolo non puo' essere vuoto o composto da soli spazi.",false);

            return false;
        }

        $geolocalizzazione=array();
        
        if(isset($_REQUEST['Geo_comune'])) $geolocalizzazione['comune']=trim($_REQUEST['Geo_comune']);
        if(isset($_REQUEST['Geo_localita'])) $geolocalizzazione['localita']=trim($_REQUEST['Geo_localita']);
        if(isset($_REQUEST['Geo_coordinate'])) $geolocalizzazione['coordinate']=trim($_REQUEST['Geo_coordinate']);

        $_REQUEST['Geolocalizzazione']=json_encode($geolocalizzazione);
        //-----------------------------------------------

        return $this->Task_GenericAddNew($task,$_REQUEST);
    }

    //Task modifica dati generali elemento
    public function Task_GetGeserModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGeserModifyDlg($object),true);
        return true;
    }

    //Task modifica dati generali elemento
    public function Task_GetGeserRevisionViewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_READ)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di visualizzazione dell'elemento",false);

            return false;
        }
            
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGeserRevisionViewDlg($object),true);
        return true;
    }

    //Task modifica dati generali elemento
    public function Task_GetGeserRevisionDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGeserRevisionDlg($_POST),true);
        return true;
    }

    //Task modifica dati generali elemento
    public function Task_GetGeserRevocaModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGeserRevocaDlg($object),true);
        return true;
    }

    //Task richiesta oscurtamento dati personali
    public function Task_GetGeserConfirmPrivacyDlg($task)
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
        $task->SetContent($this->Template_GetGeserConfirmPrivacyDlg($form_id),true);
        
        return true;
    }

    //Task modifica dati beneficiario
    public function Task_GetGeserBeneficiarioModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGeserBeneficiarioModifyDlg($object),true);

        return true;
    }

    //Task resume
    public function Task_GetGeserResumeDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }

        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericResumeObjectDlg($_REQUEST,"ResumeGeser"),true);
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
    public function Task_GetGeserPublishDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }
        
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericPublishObjectDlg($_REQUEST,"PublishGeser"),true);
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
    public function Task_GetGeserReassignDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }

        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericReassignObjectDlg($_REQUEST,"ReassignGeser"),true);
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
    public function Task_GetGeserTrashDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericObjectTrashDlg($_REQUEST,"TrashGeser"),true);
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
    public function Task_GetGeserDeleteDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGeserDeleteDlg($_REQUEST),true);
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
    public function Task_GetGeserListaCodiciIstat($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        $filter=$_REQUEST["filter"];

        $db=new AA_Database();
        $query="SELECT codice,comune FROM ".AA_Geser_Const::AA_DBTABLE_CODICI_ISTAT;
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
    public function Task_GetGeserAddNewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);
            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGeserAddNewDlg(),true);
            return true;
        }
    }

    //Task Add new pratica
    public function Task_AddNewGeserPratica($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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

        if($_REQUEST['stato'] != AA_Geser_Const::AA_GESER_STATO_PRATICA_DAISTRUIRE && $_REQUEST['data_inizio']=="")
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare la data di inizio",false);

            return false;
        }

        if($_REQUEST['stato'] == AA_Geser_Const::AA_GESER_STATO_PRATICA_AUTORIZZATA && $_REQUEST['data_fine']=="")
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

        if($_REQUEST['stato'] == AA_Geser_Const::AA_GESER_STATO_PRATICA_NEGATA && $_REQUEST['data_fine']=="")
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

    //Task update pratica
    public function Task_UpdateGeserPratica($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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

        $pratiche=$object->GetPratiche();
        if(!isset($pratiche[$_REQUEST['id_pratica']]))
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo pratica mancante o non corretto",false);

            return false;
        }
        $id_pratica=$_REQUEST['id_pratica'];

        //----------- verify values ---------------------
        if($_REQUEST['stato'] != AA_Geser_Const::AA_GESER_STATO_PRATICA_DAISTRUIRE && $_REQUEST['data_inizio']=="")
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare la data di inizio",false);

            return false;
        }

        if($_REQUEST['stato'] == AA_Geser_Const::AA_GESER_STATO_PRATICA_AUTORIZZATA && $_REQUEST['data_fine']=="")
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare la data di fine procedimento",false);

            return false;
        }

        if($_REQUEST['stato'] == AA_Geser_Const::AA_GESER_STATO_PRATICA_NEGATA && $_REQUEST['data_fine']=="")
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

        $pratiche[$id_pratica]=$pratica;
        $_REQUEST["Pratiche"]=json_encode($pratiche);
        //-----------------------------------------------
        
        $object->Parse($_REQUEST);

        if(!$object->Update($this->oUser,true,"Modifica pratica - id: ".$id_pratica))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento della pratica id: ".$id_pratica,false);

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
    public function Task_DeleteGeserPratica($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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

        $pratiche=$object->GetPratiche();
        if(!isset($pratiche[$_REQUEST['id_pratica']]))
        {
            $task->SetStatus(AA_GenericModuleTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo pratica mancante o non corretto",false);

            return false;
        }
        $id_pratica=$_REQUEST['id_pratica'];
        unset($pratiche[$id_pratica]);
        $_REQUEST["Pratiche"]=json_encode($pratiche);
        //-----------------------------------------------
        
        $object->Parse($_REQUEST);

        if(!$object->Update($this->oUser,true,"Elimina pratica - id: ".$id_pratica))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'eliminazione della pratica id: ".$id_pratica,false);

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
    public function Task_GetGeserAddNewMultiDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);
            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGeserAddNewMultiDlg(),true);
            return true;
        }
    }

    //Task aggiunta multipla preview
    public function Task_GetGeserAddNewMultiPreviewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);
            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGeserAddNewMultiPreviewDlg(),true);
            return true;
        }
    }

    //Task aggiunta geco da csv, passo 2 di 3
    public function Task_GetGeserAddNewMultiPreviewCalc($task)
    {
        $csvFile=AA_SessionFileUpload::Get("GeserAddNewMultiCSV");
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

        //Parsing della posizione dei campi
        $fieldPos=array(
            "anno"=>-1,
            "titolo"=>-1,
            "descrizione"=>-1,
            "responsabile"=>-1,
            "norma_estremi"=>-1,
            "norma_link"=>-1,
            "modalita_tipo"=>-1,
            "modalita_link"=>-1,
            "importo_impegnato"=>-1,
            "importo_erogato"=>-1,
            "beneficiario_nominativo"=>-1,
            "beneficiario_cf"=>-1,
            "beneficiario_piva"=>-1,
            "beneficiario_persona_fisica"=>-1,
            "beneficiario_privacy"=>-1,
            "note"=>-1
        );
        
        $recognizedFields=0;
        foreach(explode("|",$csvRows[0]) as $pos=>$curFieldName)
        {
            if($fieldPos[trim(strtolower($curFieldName))] == -1)
            {
                $fieldPos[trim(strtolower($curFieldName))] = $pos;
                $recognizedFields++;
            }
        }
        //----------------------------------------

        if($fieldPos['titolo']==-1 || $fieldPos['responsabile'] ==-1 || $fieldPos['norma_estremi'] ==-1 || $fieldPos['norma_link'] ==-1 || $fieldPos['modalita_tipo'] ==-1 || $fieldPos['modalita_link'] ==-1 || $fieldPos['importo_impegnato'] ==-1 || $fieldPos['beneficiario_nominativo'] ==-1 || $fieldPos['beneficiario_cf'] ==-1 || $fieldPos['beneficiario_persona_fisica'] ==-1 || $fieldPos['beneficiario_privacy'] ==-1)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono stati trovati tutti i campi relativi a: titolo,responsabile,norma_estremi,norma_link,modalita_tipo,modalita_link,importo_impegnato,beneficiario_nominativo,beneficiario_cf,beneficiario_persona_fisica,beneficiario_privacy. Verificare che il file csv sia strutturato correttamente e riprovare",false);
            return false;
        }

        //parsing dei dati
        $data=array();
        $curRowNum=0;
        $modalita=AA_Geser_Const::GetListaModalita();

        foreach($csvRows as $curCsvRow)
        {
            //salta la prima riga
            if($curRowNum > 0 && $curCsvRow !="")
            {
                $csvValues=explode("|",$curCsvRow);
                if(sizeof($csvValues) >= $recognizedFields)
                {
                    $curDataValues=array();
                    foreach($fieldPos as $fieldName=>$pos)
                    {
                        if($pos>=0)
                        {
                            $curDataValues[$fieldName]=trim($csvValues[$pos]);                            
                        }
                    }

                    $curDataValues['norma']="<a href='".$curDataValues['norma_link']."'>".$curDataValues['norma_estremi']."</a>";
                    $curDataValues['modalita']="<a href='".$curDataValues['modalita_link']."'>".$modalita[$curDataValues['modalita_tipo']]."</a>";
                    $curDataValues['beneficiario']=$curDataValues['beneficiario_nominativo']." - ".$curDataValues['beneficiario_cf'];
                    $curDataValues['persona_fisica']="no";
                    if($curDataValues['beneficiario_persona_fisica']==1) $curDataValues['persona_fisica']="si";
                    $curDataValues['privacy']="no";
                    if($curDataValues['beneficiario_privacy']==1) $curDataValues['privacy']="si";

                    $curDataValues['importo_impegnato']=AA_Utils::number_format(str_replace(",",".",str_replace(".","",$curDataValues['importo_impegnato'])),2,",",".");
                    $curDataValues['importo_erogato']=AA_Utils::number_format(str_replace(",",".",str_replace(".","",$curDataValues['importo_erogato'])),2,",",".");
                    
                    $data[]=$curDataValues;
                }
            }
            $curRowNum++;
        }

        AA_SessionVar::Set("GeserAddNewMultiFromCSV_ParsedData",$data,false);
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetStatusAction('dlg',array("task"=>"GetGeserAddNewMultiPreviewDlg"),true);
        $task->SetContent("Csv elaborato.",false);
                
        return true;
    }

    //Task aggiungi allegato
    public function Task_GetGeserAddNewAllegatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGeserAddNewAllegatoDlg($object),true);
        return true;
    }

    //Task aggiungi allegato
    public function Task_GetGeserAddNewPraticaDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGeserAddNewPraticaDlg($object),true);
        return true;
    }

    //Task modifica pratica esistente
    public function Task_GetGeserModifyPraticaDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGeserModifyPraticaDlg($object,$pratica),true);
        return true;
    }

    //Task modifica pratica esistente
    public function Task_GetGeserCopyPraticaDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGeserCopyPraticaDlg($object,$pratica),true);
        return true;
    }

    //Task elimina pratica esistente
    public function Task_GetGeserTrashPraticaDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGeserTrashPraticaDlg($object,$pratica),true);
        return true;
    }
    
    
    //Task aggiorna allegato
    public function Task_UpdateGeserAllegato($task)
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

        $object= new AA_Geser($_REQUEST['id'],$this->oUser);

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
    public function Task_DeleteGeserAllegato($task)
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

        $object= new AA_Geser($_REQUEST['id'],$this->oUser);
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
    public function Task_GetGeserModifyAllegatoDlg($task)
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

        $object=new AA_Geser($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGeserModifyAllegatoDlg($object,$allegato),true);

        return true;
    }

    //Task trash allegato
    public function Task_GetGeserTrashAllegatoDlg($task)
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

        $object= new AA_Geser($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGeserTrashAllegatoDlg($object,$allegato),true);
        return true;
    }
    
    //Task filter dlg
    public function Task_GetGeserPubblicateFilterDlg($task)
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
    public function Task_GetGeserBozzeFilterDlg($task)
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
        if(!$this->oUser->HasFlag(AA_Geser_Const::AA_USER_FLAG_GESER))
        {
            $_REQUEST['section']=static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX;
        }
        
        return $this->Task_GetGenericNavbarContent($task,$_REQUEST);
    }
    
    //Template filtro di ricerca
    public function TemplatePubblicateFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate'],"tipo"=>$params['tipo'],"stato"=>$params['stato']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['nome']=="") $formData['nome']="";
        if($params['tipo']=="") $formData['tipo']=0;
        if($params['stato']=="") $formData['stato']=0;

        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","nome"=>"","cestinate"=>0,"tipo"=>0,"stato"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Pubblicate_Filter".uniqid(), "Parametri di ricerca per le schede in bozza",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
      
        //tipo
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Geser_Const::GetListaTipoImpianti() as $key=>$val)
        {
            $options[]=array("id"=>$key,"value"=>$val);
        }
        $dlg->AddSelectField("tipo","Tipo impianto",array("gravity"=>2,"bottomLabel"=>"*Filtra in base al tipo di impianto.","options"=>$options,"value"=>"0"));

        //stato
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Geser_Const::GetListaStatiImpianto() as $key=>$val)
        {
            $options[]=array("id"=>$key,"value"=>$val);
        }
        $dlg->AddSelectField("stato","Stato impianto",array("gravity"=>2,"bottomLabel"=>"*Filtra in base allo stato dell'impianto.","options"=>$options,"value"=>"0"));

        //titolo
        $dlg->AddTextField("nome","Denominazione",array("bottomLabel"=>"*Filtra in base alla denominazione dell'impianto.", "placeholder"=>"..."));

        $dlg->SetApplyButtonName("Filtra");

        return $dlg->GetObject();
    }
    
    //Template filtro di ricerca
    public function TemplateBozzeFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate'],"tipo"=>$params['tipo'],"stato"=>$params['stato']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['nome']=="") $formData['nome']="";
        if($params['tipo']=="") $formData['tipo']=0;
        if($params['stato']=="") $formData['stato']=0;

        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","nome"=>"","cestinate"=>0,"tipo"=>0,"stato"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Bozze_Filter".uniqid(), "Parametri di ricerca per le schede in bozza",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
      
        //tipo
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Geser_Const::GetListaTipoImpianti() as $key=>$val)
        {
            $options[]=array("id"=>$key,"value"=>$val);
        }
        $dlg->AddSelectField("tipo","Tipo impianto",array("gravity"=>2,"bottomLabel"=>"*Filtra in base al tipo di impianto.","options"=>$options,"value"=>"0"));

        //stato
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Geser_Const::GetListaStatiImpianto() as $key=>$val)
        {
            $options[]=array("id"=>$key,"value"=>$val);
        }
        $dlg->AddSelectField("stato","Stato impianto",array("gravity"=>2,"bottomLabel"=>"*Filtra in base allo stato dell'impianto.","options"=>$options,"value"=>"0"));

        //titolo
        $dlg->AddTextField("nome","Denominazione",array("bottomLabel"=>"*Filtra in base alla denominazione dell'impianto.", "placeholder"=>"..."));
        
        $dlg->SetApplyButtonName("Filtra");

        return $dlg->GetObject();
    }
    
    //Funzione di esportazione in pdf (da specializzare)
    public function Template_PdfExport($ids=array(),$toBrowser=true,$title="Pubblicazione ai sensi dell'art.26-27 del d.lgs. 33/2013",$rowsForPage=20,$index=false,$subTitle="")
    {
        return $this->Template_GenericPdfExport($ids,$toBrowser,$title,"Template_GeserPdfExport", $rowsForPage, $index,$subTitle);
    }

    //funzione di aiuto
    public function Task_AMAAI_Start($task)
    {
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        
        $task->SetContent($this->Template_GetGeserHelpDlg(),true);
        
        $help_url="";
        $action='AA_MainApp.utils.callHandler("pdfPreview", { url: this.taskManager + "?task=PdfExport&section=" + this.curSection.id }, this.id);';
        
        return true;

    }

    //Template pdf export single
    public function Template_GeserPdfExport($id="", $parent=null,$object=null,$user=null)
    {
        if(!($object instanceof AA_Geser))
        {
            return "";
        }
        
        if($id=="") $id="Template_GeserPdfExport_".$object->GetId();

        return new AA_GeserPublicReportTemplateView($id,$parent,$object);
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
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetGeserAddNewAllegatoDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
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
        $listaTipo=AA_Geser_Const::GetListaTipoAllegati();
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
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGeserTrashAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$id_doc.'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGeserModifyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$id_doc.'"}]},"'.$this->id.'")';
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
Class AA_GeserPublicReportTemplateView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_GeserPublicReportTemplateView",$parent=null,$object=null)
    {
        if(!($object instanceof AA_Geser))
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

        /*if($object->GetTipo(true) == AA_Geser_Const::AA_TIPO_PROVVEDIMENTO_SCELTA_CONTRAENTE)
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
