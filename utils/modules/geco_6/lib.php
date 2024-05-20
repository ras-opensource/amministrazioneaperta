<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "config.php";
include_once "system_lib.php";

#Costanti
Class AA_Geco_Const extends AA_Const
{
    const AA_USER_FLAG_GECO="geco";

    const AA_USER_FLAG_GECO_RO="geco_ro";

    //modalita' scelta del beneficiario
    protected static $aModalita=null;
    const AA_GECO_MODALITA_AVVISO_PUBBLICO=1;
    const AA_GECO_MODALITA_ASSEGNAZIONE_DIRETTA=2;
    const AA_GECO_MODALITA_ATTO_NORMATIVO=4;
    const AA_GECO_MODALITA_CONCORSO_PUBBLICO=8;
    const AA_GECO_MODALITA_PROCEDURA_EVIDENZA_PUBBLICA=16;
    public static function GetListaModalita()
    {
        if(static::$aModalita==null)
        {
            static::$aModalita=array(
                static::AA_GECO_MODALITA_AVVISO_PUBBLICO=>"Avviso pubblico",
                static::AA_GECO_MODALITA_ASSEGNAZIONE_DIRETTA=>"Assegnazione diretta su istanza individuale",
                static::AA_GECO_MODALITA_ATTO_NORMATIVO=>"Beneficiario individuato da atto normativo",
                static::AA_GECO_MODALITA_CONCORSO_PUBBLICO=>"Concorso pubblico",
                static::AA_GECO_MODALITA_PROCEDURA_EVIDENZA_PUBBLICA=>"Procedura di evidenza pubblica"
            );
        }

        return static::$aModalita;
    }

}

#Classe oggetto geco
Class AA_Geco extends AA_Object_V2
{
    //tabella dati db
    const AA_DBTABLE_DATA="aa_geco_data";

    //Funzione di cancellazione
    protected function DeleteData($idData = 0, $user = null)
    {
        if(!$this->IsValid() || $this->IsReadOnly() || $idData == 0) return false;

        if($idData != $this->nId_Data && $idData != $this->nId_Data_Rev) return false;

        //Cancella tutti gli allegati
        foreach($this->GetAllegati($idData) as $curAllegato)
        {
            if(!$this->DeleteAllegato($curAllegato,$user))
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

    //Modalita'
    protected $modalita=null;
    public function GetModalita()
    {
        if(!$this->IsValid()) return array();

        if(!is_array($this->modalita))
        {
            $this->modalita=json_decode($this->GetProp('Modalita'),true);
            if(!is_array($this->modalita))
            {
                AA_Log::Log(__METHOD__." - errore nel parsing della modalita'",100);
                return array();
            }

            $this->modalita['descrizione']=AA_Geco_Const::GetListaModalita()[$this->modalita['tipo']];
        }

        return $this->modalita;
    }

    //Norma
    protected $norma=null;
    public function GetNorma()
    {
        if(!$this->IsValid()) return array();

        if(!is_array($this->norma))
        {
            $this->norma=json_decode($this->GetProp('Norma'),true);
            if(!is_array($this->norma))
            {
                AA_Log::Log(__METHOD__." - errore nel parsing della norma'",100);
                return array();
            }
        }

        return $this->norma;
    }

    //Beneficiario
    protected $beneficiario=null;
    public function GetBeneficiario()
    {
        if(!$this->IsValid()) return array();

        if(!is_array($this->beneficiario))
        {
            $this->beneficiario=json_decode($this->GetProp('Beneficiario'),true);
            if(!is_array($this->beneficiario))
            {
                AA_Log::Log(__METHOD__." - errore nel parsing del beneficiario'",100);
                return array();
            }
        }

        return $this->beneficiario;
    }

    //Responsabile
    protected $responsabile=null;
    public function GetResponsabile()
    {
        if(!$this->IsValid()) return array();

        if(!is_array($this->responsabile))
        {
            $this->responsabile=json_decode($this->GetProp('Responsabile'),true);
            if(!is_array($this->responsabile))
            {
                AA_Log::Log(__METHOD__." - errore nel parsing del responsabile'",100);
                return array();
            }
        }

        return $this->responsabile;
    }

    //Revoca
    protected $revoca=null;
    public function GetRevoca()
    {
        if(!$this->IsValid()) return array();

        if(!is_array($this->revoca))
        {
            $this->revoca=json_decode($this->GetProp('Revoca'),true);
            if(!is_array($this->revoca))
            {
                AA_Log::Log(__METHOD__." - errore nel parsing dei dati dio revoca'",100);
                return array();
            }
        }

        return $this->revoca;
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
        $this->AddProp("Norma","","norma");
        $this->AddProp("Anno","","anno");
        $this->AddProp("Modalita",0,"modalita");
        $this->AddProp("Revoca","","revoca");
        $this->AddProp("Responsabile","","responsabile");
        $this->AddProp("Beneficiario","","beneficiario");
        $this->AddProp("Importo_impegnato",0,"importo_impegnato");
        $this->AddProp("Importo_erogato",0,"importo_erogato");
        $this->AddProp("Allegati","","allegati");

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
        $params['class']="AA_Geco";
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
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && !$user->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $perms = AA_Const::AA_PERMS_READ;
        }
        //---------------------------------------

        //Se l'utente ha il flag e può modificare la scheda allora può fare tutto
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && $user->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
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
        if(!$user->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            AA_Log::Log(__METHOD__." - L'utente corrente: ".$user->GetUserName()." non ha i permessi per inserire nuovi elementi.",100);
            return false;
        }

        //Verifica validità oggetto
        if(!($object instanceof AA_Geco))
        {
            AA_Log::Log(__METHOD__." - Errore: oggetto non valido (".print_r($object,true).").",100);
            return false;
        }

        $object->nId=0;
        $object->bValid=true;
        //----------------------------------------------

        return parent::AddNew($object,$user,$bSaveData);
    }

    //Aggiunge un nuovo allegato
    public function AddNewAllegato($allegato=null, $user=null)
    {
        AA_Log::Log(__METHOD__."()");

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
            AA_Log::Log(__METHOD__." - l'utente corrente non può modificare l'oggetto (".$this->GetId().").", 100,false,true);
            return false;
        }

        if(!($allegato instanceof AA_GecoAllegati))
        {
            AA_Log::Log(__METHOD__." - Allegato non valido.", 100,false,true);
            return false;
        }

        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Aggiunta nuovo allegato: ".$allegato->GetEstremi()))
        {
            return false;
        }

        $allegato->SetIdGeco($this->nId_Data);
        if($this->nId_Data_Rev > 0)
        {
            $allegato->SetIdGeco($this->nId_Data_Rev);
        }

        $db= new AA_Database();

        $ordine=$allegato->GetOrdine();
        if($allegato->GetOrdine()==0)
        {
            $query="SELECT count(id) as num FROM ".static::AA_ALLEGATI_DB_TABLE;
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore nella query: ".$query." - errore: ".$db->GetErrorMessage(), 100,true);
            }

            $rs=$db->GetResultSet();
            $ordine=$rs[0]['num']+1;
        }

        $query="INSERT INTO ".static::AA_ALLEGATI_DB_TABLE." SET id_sier='".$allegato->GetIdGeco()."'";
        $query.=", url='".addslashes($allegato->GetUrl())."'";
        $query.=", estremi='".addslashes($allegato->GetEstremi())."'";
        $query.=", file='".addslashes($allegato->GetFileHash())."'";
        $query.=", tipo='".addslashes($allegato->GetTipo())."'";
        $query.=", aggiornamento='".addslashes($allegato->GetAggiornamento())."'";
        $query.=",destinatari='".$allegato->GetDestinatari()."'";
        $query.=",ordine='".$ordine."'";
        
        //AA_Log::Log(__METHOD__." - query: ".$query, 100);
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        return true;
    }

    //Aggiorna un allegato esistente
    public function UpdateAllegato($allegato=null, $user=null)
    {
        AA_Log::Log(__METHOD__."()");

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

        if(!($allegato instanceof AA_GecoAllegati))
        {
            AA_Log::Log(__METHOD__." - Allegato non valido.", 100,false,true);
            return false;
        }

        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Aggiornamento allegato: ".$allegato->GetEstremi()))
        {
            return false;
        }

        $allegato->SetIdGeco($this->nId_Data);
        if($this->nId_Data_Rev > 0)
        {
            $allegato->SetIdGeco($this->nId_Data_Rev);
        }

        $db= new AA_Database();

        $ordine=$allegato->GetOrdine();
        if($allegato->GetOrdine()==0)
        {
            $query="SELECT count(id) as num FROM ".static::AA_ALLEGATI_DB_TABLE;
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore nella query: ".$query." - errore: ".$db->GetErrorMessage(), 100,true);
            }

            $rs=$db->GetResultSet();
            $ordine=$rs[0]['num']+1;
        }
        
        $query="UPDATE ".static::AA_ALLEGATI_DB_TABLE." SET id_sier='".$allegato->GetIdGeco()."'";
        $query.=", url='".addslashes($allegato->GetUrl())."'";
        $query.=", estremi='".addslashes($allegato->GetEstremi())."'";
        $query.=", file='".addslashes($allegato->GetFileHash())."'";
        $query.=", tipo='".addslashes($allegato->GetTipo())."'";
        $query.=", aggiornamento='".addslashes($allegato->GetAggiornamento())."'";
        $query.=",destinatari='".$allegato->GetDestinatari()."'";
        $query.=",ordine='".$ordine."'";
        $query.=" WHERE id='".addslashes($allegato->GetId())."' LIMIT 1";
        
        //AA_Log::Log(__METHOD__." - query: ".$query, 100);
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        return true;
    }

    //Elimina un allegato esistente
    public function DeleteAllegato($allegato=null, $user=null)
    {
        AA_Log::Log(__METHOD__."()");

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

        if(!($allegato instanceof AA_GecoAllegati))
        {
            AA_Log::Log(__METHOD__." - Allegato non valido.", 100,false,true);
            return false;
        }

        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Rimozione allegato: ".$allegato->GetEstremi()))
        {
            return false;
        }

        $allegato->SetIdGeco($this->nId_Data);
        if($this->nId_Data_Rev > 0)
        {
            $allegato->SetIdGeco($this->nId_Data_Rev);
        }
        
        $query="DELETE FROM ".static::AA_ALLEGATI_DB_TABLE;
        $query.=" WHERE id='".addslashes($allegato->GetId())."'";
        if($this->nId_Data_Rev > 0)
        {
            $query.=" AND id_sier = '".$this->nId_Data_Rev."'";
        }
        else $query.=" AND id_sier = '".$this->nId_Data."'";
        
        $query.="LIMIT 1";
        
        $db= new AA_Database();
        
        //AA_Log::Log(__METHOD__." - query: ".$query, 100);
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        $fileHash=$allegato->GetFileHash();
        
        if($fileHash=="") return true;
        
        $storage=AA_Storage::GetInstance($user);
        if($storage->IsValid())
        {
            if(!$storage->DelFile($fileHash))
            {
                AA_Log::Log(__METHOD__." - Errore nella rimozione del file sullo storage. (".$fileHash.")", 100,false,true);
            }
        }

        return true;
    }

    //Restituisce gli allegati
    public function GetAllegati($idData=0)
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__."() - oggetto non valido.");

            return array();
        }

        if($idData==0 || $idData == "") $idData=$this->nId_Data;

        if($idData != $this->nId_Data && $idData !=$this->nId_Data_Rev && $idData > 0)
        {
            $idData=$this->nId_Data;
            if($this->nId_Data_Rev > 0)
            {
                $idData=$this->nId_Data_Rev;
            }
        }

        //Impostazione dei parametri
        $query="SELECT * from ".AA_Geco::AA_ALLEGATI_DB_TABLE." WHERE";

        $query.=" id_sier='".$idData."'";
        
        $query.= " ORDER by aggiornamento DESC, id DESC";

        $db=new AA_Database();
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query: ".$query,100);
            return array();
        }

        $result=array();

        $rs=$db->GetResultSet();
        foreach($rs as $curRec)
        {   
            $allegato=new AA_GecoAllegati($curRec['id'],$idData,$curRec['estremi'],$curRec['url'],$curRec['file'],$curRec['tipo'],$curRec['aggiornamento'],$curRec['destinatari'],$curRec['ordine']);
            $result[$curRec['id']]=$allegato;
        }

        return $result;
    }
}


#Classe per il modulo art26 - contributi
Class AA_GecoModule extends AA_GenericModule
{
    const AA_UI_PREFIX="AA_Geco";

    //Id modulo
    const AA_ID_MODULE="AA_MODULE_GECO";

    //main ui layout box
    const AA_UI_MODULE_MAIN_BOX="AA_Geco_module_layout";

    const AA_MODULE_OBJECTS_CLASS="AA_Geco";

    //Task per la gestione dei dialoghi standard
    const AA_UI_TASK_PUBBLICATE_FILTER_DLG="GetGecoPubblicateFilterDlg";
    const AA_UI_TASK_BOZZE_FILTER_DLG="GetGecoBozzeFilterDlg";
    const AA_UI_TASK_REASSIGN_DLG="GetGecoReassignDlg";
    const AA_UI_TASK_PUBLISH_DLG="GetGecoPublishDlg";
    const AA_UI_TASK_TRASH_DLG="GetGecoTrashDlg";
    const AA_UI_TASK_RESUME_DLG="GetGecoResumeDlg";
    const AA_UI_TASK_DELETE_DLG="GetGecoDeleteDlg";
    const AA_UI_TASK_ADDNEW_DLG="GetGecoAddNewDlg";
    const AA_UI_TASK_MODIFY_DLG="GetGecoModifyDlg";
    //------------------------------------

    //Dialoghi
    

    //report
   
    //Section id

    //section ui ids
    const AA_UI_DETAIL_GENERALE_BOX = "Generale_Box";

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
        $taskManager->RegisterTask("GetGecoPubblicateFilterDlg");
        $taskManager->RegisterTask("GetGecoBozzeFilterDlg");


        //dati
        $taskManager->RegisterTask("GetGecoModifyDlg");
        $taskManager->RegisterTask("GetGecoBeneficiarioModifyDlg");
        $taskManager->RegisterTask("GetGecoAddNewDlg");
        $taskManager->RegisterTask("GetGecoTrashDlg");
        $taskManager->RegisterTask("TrashGeco");
        $taskManager->RegisterTask("GetGecoDeleteDlg");
        $taskManager->RegisterTask("DeleteGeco");
        $taskManager->RegisterTask("GetGecoResumeDlg");
        $taskManager->RegisterTask("ResumeGeco");
        $taskManager->RegisterTask("GetGecoReassignDlg");
        $taskManager->RegisterTask("GetGecoPublishDlg");
        $taskManager->RegisterTask("ReassignGeco");
        $taskManager->RegisterTask("AddNewGeco");
        $taskManager->RegisterTask("UpdateGeco");
        $taskManager->RegisterTask("PublishGeco");
        
        //Allegati
        $taskManager->RegisterTask("GetGecoAddNewAllegatoDlg");
        $taskManager->RegisterTask("AddNewGecoAllegato");
        $taskManager->RegisterTask("GetGecoModifyAllegatoDlg");
        $taskManager->RegisterTask("GetGecoCopyAllegatoDlg");
        $taskManager->RegisterTask("UpdateGecoAllegato");
        $taskManager->RegisterTask("GetGecoTrashAllegatoDlg");
        $taskManager->RegisterTask("DeleteGecoAllegato");
        
        //template dettaglio
        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL,array(
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateGecoDettaglio_Generale_Tab")
        ));

        //Custom object template
        //$this->AddObjectTemplate(static::AA_UI_PREFIX."_".static::AA_UI_WND_RENDICONTI_COMUNALI."_".static::AA_UI_LAYOUT_RENDICONTI_COMUNALI,"Template_GetGecoComuneRendicontiViewLayout");
    }
    
    //istanza
    protected static $oInstance=null;
    
    //Restituisce l'istanza corrente
    public static function GetInstance($user=null)
    {
        if(self::$oInstance==null)
        {
            self::$oInstance=new AA_GecoModule($user);
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
        if($this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $bCanModify=true;
        }

        $content=$this->TemplateGenericSection_Pubblicate($params,$bCanModify);
        $content->EnableExportFunctions(false);
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
        //anno rif
        if($params['Anno'] > 0)
        {
            $params['where'][]=" AND ".AA_Geco::AA_DBTABLE_DATA.".anno = '".addslashes($params['Anno'])."'";
        }
       return $params;
    }

     //Personalizza il template dei dati delle schede pubblicate per il modulo corrente
     protected function GetDataSectionPubblicate_CustomDataTemplate($data = array(),$object=null)
     {
        $tag="";
        if($object instanceof AA_Geco)
        {

            $data['pretitolo']=$object->GetProp("Anno");
            $modalita=$object->GetModalita();
            $tag="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$modalita['descrizione']."</span>";
        }

        $data['tags']=$tag;
        return $data;
     }

    //Restituisce i dati delle bozze
    public function GetDataSectionBozze_List($params=array())
    {
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO) && !$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO_RO))
        {
            AA_Log::Log(__METHOD__." - ERRORE: l'utente corrente: ".$this->oUser->GetUserName()." non è abilitato alla visualizzazione delle bozze.",100);
            return array();
        }

        return $this->GetDataGenericSectionBozze_List($params,"GetDataSectionBozze_CustomFilter","GetDataSectionBozze_CustomDataTemplate");
    }

    //Personalizza il filtro delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomFilter($params = array())
    {
        //anno rif
        if($params['Anno'] > 0)
        {
            $params['where'][]=" AND ".AA_Geco::AA_DBTABLE_DATA.".anno = '".addslashes($params['Anno'])."'";
        }

        return $params;
    }

    //Personalizza il template dei dati delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomDataTemplate($data = array(),$object=null)
    {
        
        if($object instanceof AA_Geco)
        {

            $data['pretitolo']=$object->GetProp("Anno");
            $beneficiario=$object->GetBeneficiario();
            $class="AA_DataView_Tag AA_Label AA_Label_Green";
            if($beneficiario['tipo']==1) $class.=' mdi mdi-account';
            $tag="<span class='".$class."'>".$beneficiario['nome']."</span>";
        }

        $data['tags']=$tag;
        return $data;
    }
    
    //Template publish dlg
    public function Template_GetGecoPublishDlg($params)
    {
        //lista organismi da pubblicare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Geco($curId,$this->oUser);
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
                $wnd->SetSaveTask('PublishGeco');
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
    public function Template_GetGecoDeleteDlg($params)
    {
        return $this->Template_GetGenericObjectDeleteDlg($params,"DeleteGeco");
    }
        
    //Template dlg addnew provvedimenti
    public function Template_GetGecoAddNewDlg()
    {
        $id=$this->GetId()."_AddNew_Dlg_".uniqid();
        
        $form_data=array();
        
        $form_data['Note']="";
        $form_data['Anno']=date("Y");
        $form_data['nome']="";
        $form_data['descrizione']="";
        $form_data['Modalita_tipo']=0;
        $form_data['Modalita_link']='';

        $form_data['Norma_estremi']='';
        $form_data['Norma_link']='';
        
        $form_data['Beneficiario_nome']="";
        $form_data['Beneficiario_cf']="";
        $form_data['Beneficiario_piva']="";
        $form_data['Beneficiario_tipo']=0;
        $form_data['Beneficiario_privacy']=0;

        $form_data['Responsabile_nome']="";

        $form_data['Importo_impegnato']="";
        $form_data['Importo_erogato']=0;

        $form_data['Note']="";

        $modalita=AA_Geco_Const::GetListaModalita();
        $modalita_options=array();
        foreach($modalita as $id=>$val)
        {
            $modalita_options[]=array("id"=>$id,"value"=>$val);
        }

        $wnd=new AA_GenericFormDlg($id, "Aggiungi un nuovo contributo", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(820);
        $wnd->EnableValidation();
              
        $anno_fine=date("Y");
        $anno_start=($anno_fine-5);
        //anno riferimento
        $options=array();
        for($i=$anno_fine; $i>=$anno_start; $i--)
        {
            $options[]=array("id"=>$i, "value"=>$i);
        }
        $wnd->AddSelectField("Anno","Anno",array("required"=>true,"gravity"=>1,"validateFunction"=>"IsSelected","bottomPadding"=>32, "bottomLabel"=>"*Indicare l'anno di riferimento.", "placeholder"=>"...","options"=>$options,"value"=>Date('Y')));

        //Nome
        $wnd->AddTextField("nome","Titolo",array("required"=>true,"gravity"=>3,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci un testo breve al fine di facilitare la ricerca della pubblicazione (max 255 caratteri, visibilita' solo interna).", "placeholder"=>"Titolo a uso interno..."),false);

        //Descrizione
        $label="Descrizione";
        $wnd->AddTextareaField("descrizione",$label,array("required"=>true,"bottomLabel"=>"*Inserisci un breve testo esplicativo per il cittadino (max 1024 caratteri, visibilita' pubblica).", "placeholder"=>"Breve descrizione ad uso esterno..."));

        //Responsabile
        $wnd->AddTextField("Responsabile_nome","Responsabile",array("required"=>true,"bottomPadding"=>32, "bottomLabel"=>"*Inserisci il nominativo del responsabile del procedimento amministrativo.", "placeholder"=>"es. Direttore del servizio..."));

        //Norma
        $section=new AA_FieldSet($id."_Norma","Norma o titolo a base dell'attribuzione");

        //estremi
        $section->AddTextField("Norma_estremi","Estremi",array("required"=>true, "gravity"=>2,"labelWidth"=>90,"bottomLabel"=>"*Inserisci gli estremi della norma o dell'atto amministrativo generale.", "placeholder"=>"es. art.26 del d.lgs. 33/2013..."));

        //link alla norma
        $section->AddTextField("Norma_link","Link",array("required"=>true,"gravity"=>3,"labelWidth"=>90, "validateFunction"=>"IsUrl","bottomLabel"=>"*Inserisci il link alla norma o all'atto amministrativo generale.", "placeholder"=>"es. https://www.regione.sardegna.it..."),false);

        $wnd->AddGenericObject($section);

        //Modalita' di scelta del beneficiario
        $section=new AA_FieldSet($id."_Modalita","Modalita' di scelta del beneficiario");

        //Modalita'
        $section->AddSelectField("Modalita_tipo","Modalita'",array("required"=>true, "gravity"=>2, "labelWidth"=>90, "validateFunction"=>"IsSelected","bottomLabel"=>"*Indicare la modalita' di scelta del beneficiario.", "placeholder"=>"...","options"=>$modalita_options),false);

        //link alla modalita'
        $section->AddTextField("Modalita_link","Link",array("required"=>true,"gravity"=>3, "labelWidth"=>90, "validateFunction"=>"IsUrl","bottomLabel"=>"*Inserisci il link al documento indicante le modalita' di scelta del beneficiario.", "placeholder"=>"es. https://www.regione.sardegna.it..."),false);

        $wnd->AddGenericObject($section);
        
        //Beneficiario
        $section=new AA_FieldSet($id."_Beneficario","Beneficiario");
        
        //Nome e cognome
        $section->AddTextField("Beneficiario_nome","Nome",array("required"=>true,"gravity"=>2,"bottomPadding"=>32, "bottomLabel"=>"*Inserisci il nominativo/ragione sociale (max 255 caratteri).", "placeholder"=>"es. Mario Rossi..."));
        
        //cf
        $section->AddTextField("Beneficiario_cf","C.F.",array("required"=>true, "gravity"=>1,"bottomPadding"=>32,"labelWidth"=>60,"bottomLabel"=>"*Inserisci il codice fiscale del beneficiario."),false);

        //piva
        $section->AddTextField("Beneficiario_piva","P.IVA",array("gravity"=>1,"labelWidth"=>60,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci la partita iva del beneficiario (se applicabile)."),false);

        //Tipo
        $section->AddCheckBoxField("Beneficiario_tipo","Persona fisica",array("bottomPadding"=>32,"labelWidth"=>120, "gravity"=>1, "bottomLabel"=>"*Abilita se il beneficiario e' una persona fisica."));

        //Privacy
        $section->AddCheckBoxField("Beneficiario_privacy","Oscuramento dati personali",array("bottomPadding"=>32,"gravity"=>2, "labelWidth"=>200, "bottomLabel"=>"*Abilita se dalla pubblicazione sia possibile ricavare informazioni relative allo stato di salute e alla situazione di disagio economico-sociale degli interessati."),false);

        $wnd->AddGenericObject($section);

        //Importi
        $section=new AA_FieldSet($id."_Importi","Importi");
        
        //Impegnato
        $section->AddTextField("Importo_impegnato","Impegnato",array("required"=>true, "validateFunction"=>"IsNumber","bottomPadding"=>32,"bottomLabel"=>"*Inserisci l'importo impegnato.", "placeholder"=>"es. 12345,67"));
        
        //Erogato
        $section->AddTextField("Importo_erogato","Erogato",array("required"=>true, "validateFunction"=>"IsNumber","bottomPadding"=>32, "bottomLabel"=>"*Inserisci l'importo erogato (se presente, diversamente inserisci il valore 0).", "placeholder"=>"es. 12345,67"),false);
        
        $wnd->AddGenericObject($section);

        //Note
        //$label="Note";
        //$wnd->AddTextareaField("Note",$label,array("bottomLabel"=>"*Eventuali annotazioni (max 4096 caratteri).", "placeholder"=>"Inserisci qui le note..."));
        
        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->SetSaveTask("AddNewGeco");
        
        return $wnd;
    }

    //Template dlg aggiungi allegato/link
    public function Template_GetGecoAddNewAllegatoDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_GetGecoAddNewAllegatoDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("aggiornamento"=>date("Y-m-d"),"ordine"=>0);
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi allegato/link", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(800);

        //descrizione
        $wnd->AddTextField("estremi", "Descrizione", array("gravity"=>3,"required"=>true,"bottomLabel" => "*Indicare una descrizione per l'allegato o il link", "placeholder" => "es. DGR ..."));
        
        //ordine
        $wnd->AddTextField("ordine", "Ordine", array("gravity"=>1,"bottomLabel" => "*0=auto","labelAlign"=>"right"),false);

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        //categorie
        $tipi=AA_Geco_Const::GetTipoAllegati();$curRow=1;
        $section=new AA_FieldSet($id."_Section_Tipo","Categorie");
        $curRow=0;
        foreach($tipi as $tipo=>$descr)
        {
            $newLine=false;
            if($curRow%4 == 0 && $curRow >= 4) $newLine=true;
            $section->AddCheckBoxField("tipo_".$tipo, $descr, array("value"=>1,"bottomPadding"=>8),$newLine);
            $curRow++;
        }
        $wnd->AddGenericObject($section);
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        //----------------------

        //destinatari
        $destinatari=AA_Geco_Const::GetDestinatari();$curRow=1;
        $section=new AA_FieldSet($id."_Section_Destinatari","Destinatari");
        $curRow=0;
        foreach($destinatari as $destinatario=>$descr)
        {
            $newLine=false;
            if($curRow%4 == 0 && $curRow >= 4) $newLine=true;
            $section->AddCheckBoxField("destinatari_".$destinatario, $descr, array("value"=>1,"bottomPadding"=>8),$newLine);
            $curRow++;
        }
        $wnd->AddGenericObject($section);
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        //----------------------
        
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
        $wnd->SetSaveTask("AddNewGecoAllegato");
        
        return $wnd;
    }

    //Template dlg modifca allegato/link
    public function Template_GetGecoModifyAllegatoDlg($object=null,$allegato=null)
    {
        $id=static::AA_UI_PREFIX."_GetGecoModifyAllegatoDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array();
        $form_data["estremi"]=$allegato->GetEstremi();
        $form_data["url"]=$allegato->GetUrl();
        $form_data["tipo"]=$allegato->GetTipo();
        $form_data["aggiornamento"]=date("Y-m-d");
        $form_data["ordine"]=$allegato->GetOrdine();

        $destinatari=$allegato->GetDestinatari(true);
        foreach($destinatari as $curDestinatario)
        {
            $form_data["destinatari_".$curDestinatario]=1;
        }

        $tipi=$allegato->GetTipo(true);
        foreach($tipi as $curTipo)
        {
            $form_data["tipo_".$curTipo]=1;
        }

        $wnd=new AA_GenericFormDlg($id, "Modifica allegato/link", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(720);
        $wnd->SetHeight(800);

        /*//tipo
        $tipologia=AA_Geco_Const::GetTipoAllegati();
        $options=array();
        foreach($tipologia as $id=>$descr)
        {
            $options[]=array("id"=>$id,"value"=>$descr);
        }
        $wnd->AddSelectField("tipo", "Categoria", array("required"=>true, "validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere una categoria dalla lista", "placeholder" => "...","options"=>$options));*/

        //descrizione
        $wnd->AddTextField("estremi", "Descrizione", array("gravity"=>3,"required"=>true,"bottomLabel" => "*Indicare una descrizione per l'allegato o il link", "placeholder" => "es. DGR ..."));

        //ordine
        $wnd->AddTextField("ordine", "Ordine", array("gravity"=>1,"bottomLabel" => "*0=auto","labelAlign"=>"right"),false);

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        //categorie
        $tipi=AA_Geco_Const::GetTipoAllegati();$curRow=1;
        $section=new AA_FieldSet($id."_Section_Tipo","Categorie");
        $curRow=0;
        foreach($tipi as $tipo=>$descr)
        {
            $newLine=false;
            if($curRow%4 == 0 && $curRow >= 4) $newLine=true;
            $section->AddCheckBoxField("tipo_".$tipo, $descr, array("value"=>1,"bottomPadding"=>8),$newLine);
            $curRow++;
        }
        $wnd->AddGenericObject($section);
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        //----------------------

        //destinatari
        $destinatari=AA_Geco_Const::GetDestinatari();$curRow=1;
        $section=new AA_FieldSet($id."_Section_Destinatari","Destinatari");
        $curRow=0;
        foreach($destinatari as $destinatario=>$descr)
        {
            $newLine=false;
            if($curRow%4 == 0 && $curRow >= 4) $newLine=true;
            $section->AddCheckBoxField("destinatari_".$destinatario, $descr, array("value"=>1,"bottomPadding"=>8),$newLine);
            $curRow++;
        }
        $wnd->AddGenericObject($section);
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        //----------------------

        $section=new AA_FieldSet($id."_Section_Url","Inserire un'url oppure scegliere un file");
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        //url
        $section->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $section->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<hr/>","height"=>18)));

        //file
        $section->AddFileUploadField("NewAllegatoDoc","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf (dimensione max: 2Mb).","accept"=>"application/pdf"));
        
        $wnd->AddGenericObject($section);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_allegato"=>$allegato->GetId()));
        $wnd->SetSaveTask("UpdateGecoAllegato");
        
        return $wnd;
    }

    //Template dlg trash allegato
    public function Template_GetGecoTrashAllegatoDlg($object=null,$allegato=null)
    {
        $id=$this->id."_TrashProvvedimentoAllegato_Dlg";
        
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
        $url=$allegato->GetUrl();
        if($url =="") $url="file locale";
        $tabledata[]=array("estremi"=>$allegato->GetEstremi(),"url"=>$url);
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente allegato verrà eliminato, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"estremi", "header"=>"Descrizione", "fillspace"=>true),
              array("id"=>"url", "header"=>"Url", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteGecoAllegato");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_allegato"=>$allegato->GetId()));
        
        return $wnd;
    }

    //Task Aggiungi allegato
    public function Task_AddNewGecoAllegato($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Geco($_REQUEST['id'], $this->oUser);
        $uploadedFile = AA_SessionFileUpload::Get("NewAllegatoDoc");
        
        if(!$object->isValid())
        {
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

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
            $task->SetError("L'utente corrente (".$this->oUser->GetNome().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetNome().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
            $task->SetLog($sTaskLog);

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
        
        if(!$uploadedFile->isValid() && $_REQUEST['url'] == "")
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetError("Parametri non validi occorre indicare un url o un file.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Parametri non validi: occorre indicare un url o un file.</error>";
            $task->SetLog($sTaskLog);

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
        
        $id_sier=$object->GetIdData();
        if($object->GetIdDataRev() > 0)
        {
            $id_sier=$object->GetIdDataRev();
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

        $aggiornamento=substr($_REQUEST['aggiornamento'],0,10);
        if($aggiornamento=="") $aggiornamento=date("Y-m-d");

        //destinatari
        $destinatari=AA_Geco_Const::GetDestinatari();
        $newDestinatari=array();
        foreach($destinatari as $destinatario=>$descr)
        {
            if(isset($_REQUEST['destinatari_'.$destinatario]) && $_REQUEST['destinatari_'.$destinatario]==1) $newDestinatari[]=$destinatario;
        }
        //----

        //tipologia
        $tipi=AA_Geco_Const::GetTipoAllegati();
        $newTipo=array();
        foreach($tipi as $tipo=>$descr)
        {
            if(isset($_REQUEST['tipo_'.$tipo]) && $_REQUEST['tipo_'.$tipo]==1) $newTipo[]=$tipo;
        }
        //--------------

        $ordine=0;
        if(isset($_REQUEST['ordine']) && $_REQUEST['ordine']>0) $ordine=$_REQUEST['ordine'];
        $allegato=new AA_GecoAllegati(0,$id_sier,$_REQUEST['estremi'],$_REQUEST['url'],$fileHash,implode(",",$newTipo),$aggiornamento,implode(",",$newDestinatari),addslashes($ordine));
        
        //AA_Log::Log(__METHOD__." - "."allegato: ".print_r($allegato, true),100);
        
        if(!$object->AddNewAllegato($allegato, $this->oUser))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio dell'allegato. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Allegato caricato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Template dlg modify sier
    public function Template_GetGecoModifyDlg($object=null)
    {
        $id=$this->GetId()."_Modify_Dlg";
        if(!($object instanceof AA_Geco)) return new AA_GenericWindowTemplate($id, "Modifica i dati generali del contributo", $this->id);

        $form_data=array();

        $form_data['Anno']=$object->GetProp('Anno');
        $form_data['nome']=$object->GetName();
        $form_data['descrizione']=$object->GetDescr();

        $modalita=$object->GetModalita();
        $form_data['Modalita_tipo']=$modalita['tipo'];
        $form_data['Modalita_link']=$modalita['link'];

        $norma=$object->GetNorma();
        $form_data['Norma_estremi']=$norma['estremi'];
        $form_data['Norma_link']=$norma['link'];

        $responsabile=$object->GetResponsabile();
        $form_data['Responsabile_nome']=$responsabile['nome'];

        $form_data['Importo_impegnato']=AA_utils::number_format(floatVal($object->GetProp('Importo_impegnato')),2,",",".");
        $form_data['Importo_erogato']=AA_utils::number_format(floatVal($object->GetProp('Importo_erogato')),2,",",".");

        $form_data['note']=$object->GetProp('Note');

        $modalita=AA_Geco_Const::GetListaModalita();
        $modalita_options=array();
        foreach($modalita as $id=>$val)
        {
            $modalita_options[]=array("id"=>$id,"value"=>$val);
        }

        $wnd=new AA_GenericFormDlg($id, "Modifica i dati generali del contributo", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(820);
        $wnd->EnableValidation();
              
        $anno_fine=date("Y");
        $anno_start=($anno_fine-5);
        //anno riferimento
        $options=array();
        for($i=$anno_fine; $i>=$anno_start; $i--)
        {
            $options[]=array("id"=>$i, "value"=>$i);
        }
        $wnd->AddSelectField("Anno","Anno",array("required"=>true,"gravity"=>1,"validateFunction"=>"IsSelected","bottomPadding"=>32, "bottomLabel"=>"*Indicare l'anno di riferimento.", "placeholder"=>"...","options"=>$options,"value"=>Date('Y')));

        //Nome
        $wnd->AddTextField("nome","Titolo",array("required"=>true,"gravity"=>3,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci un testo breve al fine di facilitare la ricerca della pubblicazione (max 255 caratteri, visibilita' solo interna).", "placeholder"=>"Titolo a uso interno..."),false);

        //Descrizione
        $label="Descrizione";
        $wnd->AddTextareaField("descrizione",$label,array("required"=>true,"bottomLabel"=>"*Inserisci un breve testo esplicativo per il cittadino (max 1024 caratteri, visibilita' pubblica).", "placeholder"=>"Breve descrizione ad uso esterno..."));

        //Responsabile
        $wnd->AddTextField("Responsabile_nome","Responsabile",array("required"=>true,"bottomPadding"=>32, "bottomLabel"=>"*Inserisci il nominativo del responsabile del procedimento amministrativo.", "placeholder"=>"es. Direttore del servizio..."));

        //Norma
        $section=new AA_FieldSet($id."_Norma","Norma o titolo a base dell'attribuzione");

        //estremi
        $section->AddTextField("Norma_estremi","Estremi",array("required"=>true, "gravity"=>2,"labelWidth"=>90,"bottomLabel"=>"*Inserisci gli estremi della norma o dell'atto amministrativo generale.", "placeholder"=>"es. art.26 del d.lgs. 33/2013..."));

        //link alla norma
        $section->AddTextField("Norma_link","Link",array("required"=>true,"gravity"=>3,"labelWidth"=>90, "validateFunction"=>"IsUrl","bottomLabel"=>"*Inserisci il link alla norma o all'atto amministrativo generale.", "placeholder"=>"es. https://www.regione.sardegna.it..."),false);

        $wnd->AddGenericObject($section);

        //Modalita' di scelta del beneficiario
        $section=new AA_FieldSet($id."_Modalita","Modalita' di scelta del beneficiario");

        //Modalita'
        $section->AddSelectField("Modalita_tipo","Modalita'",array("required"=>true, "gravity"=>2, "labelWidth"=>90, "validateFunction"=>"IsSelected","bottomLabel"=>"*Indicare la modalita' di scelta del beneficiario.", "placeholder"=>"...","options"=>$modalita_options),false);

        //link alla modalita'
        $section->AddTextField("Modalita_link","Link",array("required"=>true,"gravity"=>3, "labelWidth"=>90, "validateFunction"=>"IsUrl","bottomLabel"=>"*Inserisci il link al documento indicante le modalita' di scelta del beneficiario.", "placeholder"=>"es. https://www.regione.sardegna.it..."),false);

        $wnd->AddGenericObject($section);

        //Importi
        $section=new AA_FieldSet($id."_Importi","Importi");
        
        //Impegnato
        $section->AddTextField("Importo_impegnato","Impegnato",array("required"=>true, "validateFunction"=>"IsNumber","bottomPadding"=>32,"bottomLabel"=>"*Inserisci l'importo impegnato.", "placeholder"=>"es. 12345,67"));
        
        //Erogato
        $section->AddTextField("Importo_erogato","Erogato",array("required"=>true, "validateFunction"=>"IsNumber","bottomPadding"=>32, "bottomLabel"=>"*Inserisci l'importo erogato (se presente, diversamente inserisci il valore 0).", "placeholder"=>"es. 12345,67"),false);
        
        $wnd->AddGenericObject($section);

        //Nota
        $label="Note";
        $wnd->AddTextareaField("Note",$label,array("required"=>true,"bottomLabel"=>"*Inserisci qui le note (max 1024 caratteri, visibilita' pubblica).", "placeholder"=>"..."));

        //Note
        //$label="Note";
        //$wnd->AddTextareaField("Note",$label,array("bottomLabel"=>"*Eventuali annotazioni (max 4096 caratteri).", "placeholder"=>"Inserisci qui le note..."));
        
        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateGecoDatiGenerali");
        
        return $wnd;
    }

    //Template dlg modify sier
    public function Template_GetGecoBeneficiarioModifyDlg($object=null)
    {
        $id=$this->GetId()."_Modify_Dlg";
        if(!($object instanceof AA_Geco)) return new AA_GenericWindowTemplate($id, "Modifica i dati beneficiario", $this->id);

        $beneficiario=$object->GetBeneficiario();
        $form_data=array();
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
        $wnd->AddTextField("Beneficiario_nome","Nome",array("required"=>true,"gravity"=>2,"bottomPadding"=>32, "bottomLabel"=>"*Inserisci il nominativo/ragione sociale (max 255 caratteri).", "placeholder"=>"es. Mario Rossi..."));
        
        //cf
        $wnd->AddTextField("Beneficiario_cf","C.F.",array("required"=>true, "gravity"=>1,"bottomPadding"=>32,"labelWidth"=>60,"bottomLabel"=>"*Inserisci il codice fiscale del beneficiario."),false);

        //piva
        $wnd->AddTextField("Beneficiario_piva","P.IVA",array("gravity"=>1,"labelWidth"=>60,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci la partita iva del beneficiario (se applicabile)."),false);

        //Tipo
        $wnd->AddCheckBoxField("Beneficiario_tipo","Persona fisica",array("bottomPadding"=>32,"labelWidth"=>120, "gravity"=>1, "bottomLabel"=>"*Abilita se il beneficiario e' una persona fisica."));

        //Privacy
        $wnd->AddCheckBoxField("Beneficiario_privacy","Oscuramento dati personali",array("bottomPadding"=>32,"gravity"=>2, "labelWidth"=>200, "bottomLabel"=>"*Abilita se dalla pubblicazione sia possibile ricavare informazioni relative allo stato di salute e alla situazione di disagio economico-sociale degli interessati."),false);

        //$wnd->AddGenericObject($section);
        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateGecoDatiBeneficiario");
        
        return $wnd;
    }

    public function Template_GetGecoHelpDlg()
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
        //$params['DetailOptionTab']=array(array("id"=>$id, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateGecoDettaglio_Generale_Tab"));
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO)) $params['readonly']=true;
        
        $params['MultiviewEventHandlers']=array("onViewChange"=>array("handler"=>"onDetailViewChange"));

        $params['disable_SaveAsPdf']=true;
        $params['disable_SaveAsCsv']=true;
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO)) $params['disable_MenuAzioni']=true;
        
        $detail = $this->TemplateGenericSection_Detail($params);

        return $detail;
    }
    
    //Template section detail, tab generale
    public function TemplateGecoDettaglio_Generale_Tab($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX;

        if(!($object instanceof AA_Geco)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));

        $rows_fixed_height=50;
        $canModify=false;
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0 && $this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO)) $canModify=true;

        $revoca=$object->GetRevoca();
        if(sizeof($revoca)>0)
        {
            $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>32,"type"=>"clean","borderless"=>true));
            $revocato="<span class='mdi mdi-trash-can' style='font-size:larger;color: #fff'>&nbsp;Contributo revocato</span>";
            $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_OC_Certified_Title",array("view"=>"label","label"=>$revocato,"width"=>240, "css"=>array("background"=>"#6cb456 !important","border-radius"=>"10px"),"align"=>"center")));
            $toolbar->AddElement(new AA_JSON_Template_Generic());
           
            $layout=$this->TemplateGenericDettaglio_Header_Generale_Tab($object,$id,$toolbar,$canModify);
        }
        else $layout=$this->TemplateGenericDettaglio_Header_Generale_Tab($object,$id,null,$canModify);
        

        //Descrizione
        $value=$object->GetDescr();
        $descr=new AA_JSON_Template_Template($id."_Descrizione",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Descrizione:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //anno riferimento
        $value=$object->GetProp("Anno");
        if($value=="")$value="n.d.";
        $anno_rif=new AA_JSON_Template_Template($id."_AnnoRif",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "gravity"=>1,
            "width"=>90,
            "data"=>array("title"=>"Anno:","value"=>$value)
        ));
        
        //modalita'
        $modalita=$object->GetModalita();
        if(sizeof($modalita)==0)$value="n.d.";
        else
        {
            $value="<a href='".$modalita['link']."' target='_blank'>".$modalita['descrizione']."</a>";
        }
        $modalita_text=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "gravity"=>1,
            "data"=>array("title"=>"Modalita' di scelta del beneficiario:","value"=>$value)
        ));

        //norma
        $norma=$object->GetNorma();
        if(sizeof($norma)==0)$value="n.d.";
        else
        {
            $value="<a href='".$norma['link']."' target='_blank'>".$norma['estremi']."</a>";
        }
        $norma_text=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "gravity"=>1,
            "data"=>array("title"=>"Norma:","value"=>$value)
        ));

        //note
        $value = $object->GetProp("Note");
        $note=new AA_JSON_Template_Template($id."_Note",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Note:","value"=>$value)
        ));

        //responsabile
        $value = $object->GetResponsabile();
        $responsabile=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "width"=>250,
            "data"=>array("title"=>"Responsabile:","value"=>$value['nome'])
        ));

        //importo impegnato
        $value = AA_Utils::number_format(floatVal($object->GetProp("Importo_impegnato")),2,",",".");
        $importo_impegnato=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "width"=>200,
            "data"=>array("title"=>"Importo impegnato:","value"=>$value)
        ));

        //importo erogato
        $value = AA_Utils::number_format(floatVal($object->GetProp("Importo_erogato")),2,",",".");
        $importo_liquidato=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "width"=>200,
            "data"=>array("title"=>"Importo erogato:","value"=>$value)
        ));
        
        //prima riga
        $riga=new AA_JSON_Template_Layout("",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($anno_rif);
        $riga->AddCol($modalita_text);
        $riga->AddCol($norma_text);
        $riga->AddCol($responsabile);
        $riga->AddCol($importo_impegnato);
        $riga->AddCol($importo_liquidato);
        $layout->AddRow($riga);

        //seconda riga
        $riga=new AA_JSON_Template_Layout("",array("gravity"=>1,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $layout_gen=new AA_JSON_Template_Layout("",array("gravity"=>3,"type"=>"clean"));
        $layout_gen->addRow($descr);
        $layout_gen->addRow($note);
        $riga->addCol($layout_gen);
        $layout->AddRow($riga);

        //terza riga
        $riga=new AA_JSON_Template_Layout("",array("gravity"=>1));
        $beneficiario_box=new AA_JSON_Template_Layout("",array("type"=>"clean","gravity"=>1,"minWidth"=>400,"css"=>array("border-right"=>"1px solid #dadee0 !important")));
        $revoca_box=new AA_JSON_Template_Layout("",array("type"=>"clean","gravity"=>1,"minWidth"=>400,"css"=>array("border-right"=>"1px solid #dadee0 !important")));
        $allegati_box=new AA_JSON_Template_Layout("",array("type"=>"clean","gravity"=>2,"minWidth"=>400));
        $riga->AddCol($beneficiario_box);
        $riga->AddCol($revoca_box);
        $riga->AddCol($allegati_box);

        //-------------------- Beneficiario --------------------------------------
        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        $toolbar->AddElement(new AA_JSON_Template_Generic(""));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"<span style='color:#003380'>Beneficiario</span>", "align"=>"center")));
        $toolbar->AddElement(new AA_JSON_Template_Generic(""));
        if($canModify)
        {
            $modify_btn=new AA_JSON_Template_Generic("",array(
                "view"=>"button",
                 "type"=>"icon",
                 "icon"=>"mdi mdi-pencil",
                 "label"=>"Modifica",
                 "css"=>"webix_primary",
                 "align"=>"right",
                 "autowidth"=>true,
                 "tooltip"=>"Modifica i dati del beneficiario",
                 "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetGecoBeneficiarioModifyDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
             ));
             $toolbar->AddElement($modify_btn);
        }

        $beneficiario_box->AddRow($toolbar);
        if($canModify)
        {
            $beneficiario=$object->GetBeneficiario();
            if(sizeof($beneficiario)==0)
            {
                $beneficiario['nome']="n.d.";
                $beneficiario['cf']="n.d.";
                $beneficiario['piva']="n.d.";
                $beneficiario['tipo_descr']="n.d.";
                $beneficiario['privacy_descr']="n.d.";
            }

            if($beneficiario['privacy']==0) $beneficiario['privacy_descr']="Visibili";
            else $beneficiario['privacy_descr']="Oscurati";

            if($beneficiario['tipo']==1) $beneficiario['tipo_descr']="Persona fisica";
            else 
            {
                $beneficiario['tipo_descr']="Persona giuridica";
                $beneficiario['privacy_descr']="Non applicabile";
            }

            $nome=new AA_JSON_Template_Template("",array(
                "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "height"=>48,
                "data"=>array("title"=>"Nome:","value"=>$beneficiario['nome'])
            ));

            $cf=new AA_JSON_Template_Template("",array(
                "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "height"=>48,
                "data"=>array("title"=>"C.f.:","value"=>$beneficiario['cf'])
            ));

            $piva=new AA_JSON_Template_Template("",array(
                "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "height"=>48,
                "data"=>array("title"=>"P.Iva:","value"=>$beneficiario['piva'])
            ));

            $tipo=new AA_JSON_Template_Template("",array(
                "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "height"=>48,
                "data"=>array("title"=>"Tipologia:","value"=>$beneficiario['tipo_descr'])
            ));

            $privacy=new AA_JSON_Template_Template("",array(
                "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "data"=>array("title"=>"Dati personali:","value"=>$beneficiario['privacy_descr'])
            ));

            $beneficiario_box->AddRow($nome);
            $beneficiario_box->AddRow($cf);
            $beneficiario_box->AddRow($piva);
            $beneficiario_box->AddRow($tipo);
            $beneficiario_box->AddRow($privacy);
        }
        else
        {
            $beneficiario_box->AddRow(new AA_JSON_Template_Template("",array("template"=>"<div style='display: flex; width:100%; height:100%; justify-content:center; align-items: center'>Dati non visualizzati a tutela della privacy del beneficiario</div>")));
        }
        //------------------------------------------------------------------------

        //-------------------- Revoca --------------------------------------
        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        $toolbar->AddElement(new AA_JSON_Template_Generic(""));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"<span style='color:#003380'>Revoca</span>", "align"=>"center")));
        $toolbar->AddElement(new AA_JSON_Template_Generic(""));
        if($canModify)
        {
            $modify_btn=new AA_JSON_Template_Generic("",array(
                "view"=>"button",
                 "type"=>"icon",
                 "icon"=>"mdi mdi-pencil",
                 "label"=>"Modifica",
                 "css"=>"webix_primary",
                 "align"=>"right",
                 "autowidth"=>true,
                 "tooltip"=>"Modifica i dati di revoca",
                 "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetRevocaDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
             ));
             $toolbar->AddElement($modify_btn);
        }
        $revoca_box->AddRow($toolbar);
        if(sizeof($revoca)==0)
        {
            $revoca['data']="n.d.";
            $revoca['estremi']="n.d.";
            $revoca['causale']="n.d.";
        }
        else
        {
            $revoca['data']=date("d-m-Y",strtotime($revoca['data']));
        }

        $data=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "height"=>48,
            "data"=>array("title"=>"Data provvedimento:","value"=>$revoca['data'])
        ));

        $estremi=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "height"=>48,
            "data"=>array("title"=>"Estremi provvedimento:","value"=>$revoca['estremi'])
        ));

        $causale=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Causale:","value"=>$revoca['causale'])
        ));

        $revoca_box->AddRow($data);
        $revoca_box->AddRow($estremi);
        $revoca_box->AddRow($causale);
        //------------------------------------------------------------------------

        //-------------------- Allegati --------------------------------------
        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        $toolbar->AddElement(new AA_JSON_Template_Generic(""));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"<span style='color:#003380'>Allegati</span>", "align"=>"center")));
        $toolbar->AddElement(new AA_JSON_Template_Generic(""));
        $allegati_box->AddRow($toolbar);
        $allegati_box->AddRow(new AA_JSON_Template_Generic());

        //------------------------------------------------------------------------

        //$riga->addCol($this->TemplateDettaglio_Allegati($object,$id,$canModify));
        //$riga->addCol($this->TemplateDettaglio_Giornate($object,$id,$canModify));

        //$layout->AddRow($riga);

        //terza riga
        //$riga=new AA_JSON_Template_Layout($id."_ThirdRow",array("gravity"=>1));
      
        $layout->AddRow($riga);

        return $layout;
    }

    //Template section detail, tab generale
    public function TemplateGecoDettaglio_Allegati_Tab($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_ALLEGATI_BOX;

        if(!($object instanceof AA_Geco)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
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
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetGecoAddNewAllegatoDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
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
            
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoTrashAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoModifyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            $copy='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoCopyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
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
   
    //Task Update Geco
    public function Task_UpdateGeco($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi di modifica dell'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $flags=array_keys(AA_Geco_Const::GetFlags());
        
        $abilitazioni=0;
        foreach($_REQUEST as $key=>$value)
        {
            if($value==1 && in_array($key,$flags))
            {
                $abilitazioni+=$key;
            } 
        }

        //AA_Log::Log(__METHOD__." - Flags: ".$abilitazioni,100);

        $_REQUEST['Flags']=$abilitazioni;
        
        return $this->Task_GenericUpdateObject($task,$_REQUEST,true);   
    }
    
    //Task trash Geco
    public function Task_TrashGeco($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetError("L'utente corrente non ha i permessi per cestinare l'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per cestinare l'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        return $this->Task_GenericTrashObject($task,$_REQUEST);
    }
    
    //Task resume Geco
    public function Task_ResumeGeco($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericResumeObject($task,$_REQUEST);
    }
    
    //Task publish Geco
    public function Task_PublishGeco($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericPublishObject($task,$_REQUEST);
    }
    
    //Task reassign Geco
    public function Task_ReassignGeco($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        return $this->Task_GenericReassignObject($task,$_REQUEST);
    }
    
    //Task delete Geco
    public function Task_DeleteGeco($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
         
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetError("L'utente corrente non ha i permessi per eliminare l'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per eliminare l'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        return $this->Task_GenericDeleteObject($task,$_REQUEST);
    }
    
    //Task Aggiungi provvedimenti
    public function Task_AddNewGeco($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi",false);

            return false;
        }
        
        //----------- verify values ---------------------
        if(trim($_REQUEST['nome']) == "" || trim($_REQUEST['descrizione']) =="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Il titolo e la descrizione non possono essere vuoti o composti da soli spazi.",false);

            return false;
        }

        $modalita=array();
        $norma=array();
        $beneficiario=array();
        $responsabile=array();
        
        if(isset($_REQUEST['Modalita_tipo'])) $modalita['tipo']=intVal($_REQUEST['Modalita_tipo']);
        if(isset($_REQUEST['Modalita_link'])) $modalita['link']=trim($_REQUEST['Modalita_link']);
        if(strpos($_REQUEST['Modalita_link'],"https") === false)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Il link al documento indicante le modalita' di scelta deve essere una URL pubblica accessbile tramite protocollo https.",false);

            return false;
        }
        if(intVal($_REQUEST['Modalita_tipo']) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Tipo di modalita' errato.",false);

            return false;
        }
        
        if(isset($_REQUEST['Responsabile_nome'])) $responsabile['nome']=trim($_REQUEST['Responsabile_nome']);
        
        if(isset($_REQUEST['Norma_estremi'])) $norma['estremi']=trim($_REQUEST['Norma_estremi']);
        if(isset($_REQUEST['Norma_link'])) $norma['link']=trim($_REQUEST['Norma_link']);
        if(strpos($_REQUEST['Norma_link'],"https") === false)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Il link alla norma deve essere una URL pubblica accessbile tramite protocollo https.",false);

            return false;
        }

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

        if(isset($_REQUEST['Importo_impegnato'])) $importo['impegnato']=AA_utils::number_format(floatVal(str_replace(",",".",str_replace(".","",$_REQUEST['Importo_impegnato']))),2,".");
        if(isset($_REQUEST['Importo_erogato'])) $importo['erogato']=AA_utils::number_format(floatVal(str_replace(",",".",str_replace(".","",$_REQUEST['Importo_erogato']))),2,".");
        if($importo['impegnato'] <= 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'importo impegnato non puo' essere nullo o negativo.",false);

            return false;
        }
        if($importo['erogato'] < 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'importo erogato non puo' essere negativo.",false);

            return false;
        }
        $_REQUEST['Importo_impegnato']=$importo['impegnato'];
        $_REQUEST['Importo_erogato']=$importo['erogato'];

        $_REQUEST['Modalita']=json_encode($modalita);
        $_REQUEST['Norma']=json_encode($norma);
        $_REQUEST['Beneficiario']=json_encode($beneficiario);
        $_REQUEST['Responsabile']=json_encode($responsabile);
        //-----------------------------------------------

        return $this->Task_GenericAddNew($task,$_REQUEST);
    }

    //Task modifica dati generali elemento
    public function Task_GetGecoModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non può modifcare l'elemento.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $object= new AA_Geco($_REQUEST['id'],$this->oUser);
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetGecoModifyDlg($object)->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task modifica dati beneficiario
    public function Task_GetGecoBeneficiarioModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non può modifcare l'elemento.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $object= new AA_Geco($_REQUEST['id'],$this->oUser);
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetGecoBeneficiarioModifyDlg($object)->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task resume
    public function Task_GetGecoResumeDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per ripristinare elementi.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetGenericResumeObjectDlg($_REQUEST,"ResumeGeco")->toBase64();
            $sTaskLog.="</content>";
            $task->SetLog($sTaskLog);
        
            return true;
        }    
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativi non presenti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
    }
    
    //Task publish organismo
    public function Task_GetGecoPublishDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per pubblicare elementi.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetGenericPublishObjectDlg($_REQUEST,"PublishGeco")->toBase64();
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
    }
    
    //Task Riassegna
    public function Task_GetGecoReassignDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
         if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per riassegnare elementi.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetGenericReassignObjectDlg($_REQUEST,"ReassignGeco")->toBase64();
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
    }
    
    //Task elimina organismo
    public function Task_GetGecoTrashDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per cestinare/eliminare elementi di questo tipo.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetGenericObjectTrashDlg($_REQUEST,"TrashGeco")->toBase64();
            $sTaskLog.="</content>";
            
            $task->SetLog($sTaskLog);
        
            return true;
        }    
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Identificativi non presenti.</error>";
            
            $task->SetLog($sTaskLog);
        
            return false;
        }
    }
       
    //Task dialogo elimina
    public function Task_GetGecoDeleteDlg($task)
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
            $sTaskLog.= $this->Template_GetGecoDeleteDlg($_REQUEST)->toBase64();
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
    
    //Task aggiunta 
    public function Task_GetGecoAddNewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGecoAddNewDlg(),true);
        }

        return true;
    }

    //Task aggiungi allegato
    public function Task_GetGecoAddNewAllegatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Geco($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetGecoAddNewAllegatoDlg($object)->toBase64();
            $sTaskLog.="</content>";
            $task->SetLog($sTaskLog);
        
            return true;
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'elemento (".$object->GetId().").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
    }

    
    //Task aggiorna allegato
    public function Task_UpdateGecoAllegato($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Geco($_REQUEST['id'],$this->oUser);
        $uploadedFile = AA_SessionFileUpload::Get("NewAllegatoDoc");

        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);

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
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'elemento (".$object->GetId().").</error>";
            $task->SetLog($sTaskLog);

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

        $allegato=$object->GetAllegato($_REQUEST['id_allegato'],$this->oUser);
        if($allegato==null)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo allegato non valido (".$_REQUEST['id_allegato'].").</error>";
            $task->SetLog($sTaskLog);

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
        $fileHash=$allegato->GetFileHash();
        if($uploadedFile->isValid()) 
        {
            $_REQUEST['url']="";

            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$allegato->GetFileHash();
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

        //Elimina il file precedentemente associato se viene impostato un url
        if($_REQUEST['url'] !="" && $allegato->GetFileHash() !="")
        {
            $fileHash="";
            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$allegato->GetFileHash();
                if($oldFile !="")
                {
                    if(!$storage->DelFile($oldFile))
                    {
                        AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$oldFile,100);
                    }
                }
            }
            else AA_Log::Log(__METHOD__." - storage non inizializzato. file non eliminato.",100);
        }

        $aggiornamento=substr($_REQUEST['aggiornamento'],0,10);
        if($aggiornamento=="") $aggiornamento=date("Y-m-d");
        
        //destinatari
        $destinatari=AA_Geco_Const::GetDestinatari();
        $newDestinatari=array();
        foreach($destinatari as $destinatario=>$descr)
        {
            if(isset($_REQUEST['destinatari_'.$destinatario]) && $_REQUEST['destinatari_'.$destinatario]==1) $newDestinatari[]=$destinatario;
        }
        //----

        //tipologia
        $tipi=AA_Geco_Const::GetTipoAllegati();
        $newTipo=array();
        foreach($tipi as $tipo=>$descr)
        {
            if(isset($_REQUEST['tipo_'.$tipo]) && $_REQUEST['tipo_'.$tipo]==1) $newTipo[]=$tipo;
        }
        //--------------

        $ordine=0;
        if(isset($_REQUEST['ordine']) && $_REQUEST['ordine']>0) $ordine=$_REQUEST['ordine'];
        $allegato=new AA_GecoAllegati($_REQUEST['id_allegato'],$allegato->GetIdGeco(),$_REQUEST['estremi'],$_REQUEST['url'],$fileHash,implode(",",$newTipo),$aggiornamento,implode(",",$newDestinatari),addslashes($ordine));
        
        if(!$object->UpdateAllegato($allegato, $this->oUser))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nell'aggiornamento dell'allegato. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Allegato aggiornato con successo.";
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }

    //Task elimina allegato
    public function Task_DeleteGecoAllegato($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Geco($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid() || $object->GetId()<=0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Provvedimento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'elemento (".$object->GetId().").</error>";
            $task->SetLog($sTaskLog);
        
            return true;
        }

        $allegato=$object->GetAllegato($_REQUEST['id_allegato'],$this->oUser);
        if($allegato==null)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo allegato non valido (".$_REQUEST['id_allegato'].").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(!$object->DeleteAllegato($allegato))
        {   
            $task->SetError("Errore durante l'eliminazione dell'allegato: ".$allegato->GetEstremi());
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore durante l'eliminazione dell'allegato: ".$allegato->GetEstremi()."</error>";
            $task->SetLog($sTaskLog);
            
            return false;
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Allegato eliminato con successo.";
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }

    
    //Task modifica allegato
    public function Task_GetGecoModifyAllegatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Geco($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Provvedimento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'elemento (".$object->GetId().").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $allegato=$object->GetAllegato($_REQUEST['id_allegato'],$this->oUser);
        if($allegato==null)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo allegato non valido (".$_REQUEST['id_allegato'].").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $sTaskLog.= $this->Template_GetGecoModifyAllegatoDlg($object,$allegato)->toBase64();
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }

    //Task trash allegato
    public function Task_GetGecoTrashAllegatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Geco($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid() || $object->GetId()<= 0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Provvedimento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'elemento (".$object->GetId().").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $allegato=$object->GetAllegato($_REQUEST['id_allegato'],$this->oUser);
        if($allegato==null)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo allegato non valido (".$_REQUEST['id_allegato'].").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $sTaskLog.= $this->Template_GetGecoTrashAllegatoDlg($object,$allegato)->toBase64();
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }

    
    //Task filter dlg
    public function Task_GetGecoPubblicateFilterDlg($task)
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
    public function Task_GetGecoBozzeFilterDlg($task)
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
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $_REQUEST['section']=static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX;
        }
        
        return $this->Task_GetGenericNavbarContent($task,$_REQUEST);
    }
    
    //Template filtro di ricerca
    public function TemplatePubblicateFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"descrizione"=>$params['descrizione'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate'],"Tipo"=>$params['Tipo'],"Estremi"=>$params['Estremi']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['revisionate']=="") $formData['revisionate']=0;
        if($params['Tipo']=="") $formData['Tipo']=0;

        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","descrizione"=>"","nome"=>"","cestinate"=>0,"revisionate"=>0,"Estremi"=>"", "Tipo"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Pubblicate_Filter", "Parametri di ricerca per le schede pubblicate",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
      
        //Revisionate
        //$dlg->AddSwitchBoxField("revisionate","Revisionate",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede revisionate."));
        
        //oggetto
        $dlg->AddTextField("nome","Oggetto",array("bottomLabel"=>"*Filtra in base all'oggetto del elemento/accordo.", "placeholder"=>"Oggetto..."));
        
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //tipo
        /*
        $selectionChangeEvent="try{AA_MainApp.utils.getEventHandler('onTipoProvSelectChange','".$this->id."','".$this->id."_Field_Tipo')}catch(msg){console.error(msg)}";
        $options=array();
        $options[0]="Qualunque";
        foreach(AA_Geco_Const::GetListaTipologia() as $key=>$value)
        {
            $options[]=array("id"=>$key,"value"=>$value);
        }
        $dlg->AddSelectField("Tipo","Tipo",array("bottomLabel"=>"*filtra in base al tipo di elemento","placeholder"=>"Scegli una voce...","options"=>$options));

        //descrizione
        $dlg->AddTextField("descrizione","Descrizione",array("bottomLabel"=>"*Filtra in base alla descrizione del elemento/accordo.", "placeholder"=>"Descrizione..."));

        //Estremi elemento
        $dlg->AddTextField("Estremi","Estremi",array("bottomLabel"=>"*Filtra in base agli estremi del elemento/accordo.", "placeholder"=>"Estremi..."));*/
        
        $dlg->SetApplyButtonName("Filtra");

        return $dlg->GetObject();
    }
    
    //Template filtro di ricerca
    public function TemplateBozzeFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"descrizione"=>$params['descrizione'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate'],"Tipo"=>$params['Tipo'],"Estremi"=>$params['Estremi']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['Tipo']=="") $formData['Tipo']=0;

        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","descrizione"=>"","nome"=>"","cestinate"=>0,"revisionate"=>0,"Estremi"=>"", "Tipo"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Pubblicate_Filter", "Parametri di ricerca per le schede pubblicate",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
      
        //Revocate
        $dlg->AddSwitchBoxField("revocati","Revocati",array("onLabel"=>"mostra esclusivamente","offLabel"=>"Mostra tutto","bottomLabel"=>"*Mostra i contributi revocati esclusivamente o insieme agli altri."));
        
        //oggetto
        $dlg->AddTextField("nome","Oggetto",array("bottomLabel"=>"*Filtra in base all'oggetto del elemento/accordo.", "placeholder"=>"Oggetto..."));
        
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //tipo
        /*$selectionChangeEvent="try{AA_MainApp.utils.getEventHandler('onTipoProvSelectChange','".$this->id."','".$this->id."_Field_Tipo')}catch(msg){console.error(msg)}";
        $options=array();
        $options[0]="Qualunque";
        foreach(AA_Geco_Const::GetListaTipologia() as $key=>$value)
        {
            $options[]=array("id"=>$key,"value"=>$value);
        }
        $dlg->AddSelectField("Tipo","Tipo",array("bottomLabel"=>"*filtra in base al tipo di elemento","placeholder"=>"Scegli una voce...","options"=>$options));*/

        //descrizione
        $dlg->AddTextField("descrizione","Descrizione",array("bottomLabel"=>"*Filtra in base alla descrizione del elemento/accordo.", "placeholder"=>"Descrizione..."));

        //Estremi elemento
        $dlg->AddTextField("Estremi","Estremi",array("bottomLabel"=>"*Filtra in base agli estremi del elemento/accordo.", "placeholder"=>"Estremi..."));

        $dlg->SetApplyButtonName("Filtra");

        return $dlg->GetObject();
    }
    
    //Funzione di esportazione in pdf (da specializzare)
    public function Template_PdfExport($ids=array(),$toBrowser=true,$title="Pubblicazione ai sensi dell'art.26-27 del d.lgs. 33/2013",$rowsForPage=20,$index=false,$subTitle="")
    {
        return $this->Template_GenericPdfExport($ids,$toBrowser,$title,"Template_GecoPdfExport", $rowsForPage, $index,$subTitle);
    }

    //funzione di aiuto
    public function Task_AMAAI_Start($task)
    {
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        
        $task->SetContent($this->Template_GetGecoHelpDlg(),true);
        
        $help_url="";
        $action='AA_MainApp.utils.callHandler("pdfPreview", { url: this.taskManager + "?task=PdfExport&section=" + this.curSection.id }, this.id);';
        
        return true;

    }

    //Template pdf export single
    public function Template_GecoPdfExport($id="", $parent=null,$object=null,$user=null)
    {
        if(!($object instanceof AA_Geco))
        {
            return "";
        }
        
        if($id=="") $id="Template_GecoPdfExport_".$object->GetId();

        return new AA_GecoPublicReportTemplateView($id,$parent,$object);
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
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetGecoAddNewAllegatoDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
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
            $options_documenti[]=array("id"=>"aggiornamento","header"=>array("<div style='text-align: center'>Data</div>",array("content"=>"textFilter")),"width"=>100, "css"=>array("text-align"=>"left"),"sort"=>"text");
            $options_documenti[]=array("id"=>"tipoDescr","header"=>array("<div style='text-align: center'>Tipo</div>",array("content"=>"selectFilter")),"width"=>200, "css"=>array("text-align"=>"center"),"sort"=>"text");
            $options_documenti[]=array("id"=>"estremi","header"=>array("<div style='text-align: center'>Descrizione</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text");
            $options_documenti[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
        }
        else
        {
            $options_documenti[]=array("id"=>"aggiornamento","header"=>array("<div style='text-align: center'>Data</div>",array("content"=>"textFilter")),"width"=>100, "css"=>array("text-align"=>"left"),"sort"=>"text");
            $options_documenti[]=array("id"=>"tipoDescr","header"=>array("<div style='text-align: center'>Tipo</div>",array("content"=>"selectFilter")),"width"=>200, "css"=>array("text-align"=>"center"),"sort"=>"text");
            $options_documenti[]=array("id"=>"estremi","header"=>array("<div style='text-align: center'>Descrizione</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text");
            $options_documenti[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
        }

        $documenti=new AA_JSON_Template_Generic($curId."_Allegati_Table",array("view"=>"datatable", "select"=>true,"scrollX"=>false,"css"=>"AA_Header_DataTable","columns"=>$options_documenti));

        $documenti_data=array();
        foreach($object->GetAllegati() as $id_doc=>$curDoc)
        {
            if($curDoc->GetUrl() == "")
            {
                $view='AA_MainApp.utils.callHandler("pdfPreview", {url: "storage.php?object='.$curDoc->GetFileHash().'"},"'.$this->id.'")';
                $view_icon="mdi-floppy";
            }
            else 
            {
                $view='AA_MainApp.utils.callHandler("wndOpen", {url: "'.$curDoc->GetUrl().'"},"'.$this->id.'")';
                $view_icon="mdi-eye";
            }
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoTrashAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoModifyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            if($canModify) $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Vedi' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $ops="<div class='AA_DataTable_Ops' style='justify-content: center'><a class='AA_DataTable_Ops_Button' title='Vedi' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a></div>";
            $documenti_data[]=array("id"=>$id_doc,"estremi"=>$curDoc->GetEstremi(),"tipoDescr"=>$curDoc->GetTipoDescr(),"tipo"=>$curDoc->GetTipo(),"aggiornamento"=>$curDoc->GetAggiornamento(),"ops"=>$ops);
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
Class AA_GecoPublicReportTemplateView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_GecoPublicReportTemplateView",$parent=null,$object=null)
    {
        if(!($object instanceof AA_Geco))
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

        /*if($object->GetTipo(true) == AA_Geco_Const::AA_TIPO_PROVVEDIMENTO_SCELTA_CONTRAENTE)
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

Class AA_GecoAllegato extends AA_GenericParsableObject
{
    public function __construct($params = null)
    {
        $this->aProps['descrizione']="";
        $this->aProps['url']="";
        $this->aProps['file']="";

        parent::__construct($params);
    }
}