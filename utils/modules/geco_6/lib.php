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

    const AA_USER_FLAG_GECO_CRITERI="geco_criteri";

    //tabella criteri e modaliota'
    const AA_GECO_DBTABLE_CRITERI="aa_geco_criteri";

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

    protected static $aTipoAllegati=null;
    const AA_GECO_TIPO_ALLEGATO_CURRICULUM=1;

    const AA_GECO_TIPO_ALLEGATO_PROGETTO=2;
    const AA_GECO_TIPO_ALLEGATO_LINK_NORMA=4;
    public static function GetListaTipoAllegati()
    {
        if(static::$aTipoAllegati==null)
        {
            static::$aTipoAllegati=array(
                static::AA_GECO_TIPO_ALLEGATO_CURRICULUM=>"Curriculum",
                static::AA_GECO_TIPO_ALLEGATO_PROGETTO=>"Progetto",
                static::AA_GECO_TIPO_ALLEGATO_LINK_NORMA=>"Normativa"
            );
        }

        return static::$aTipoAllegati;
    }

    protected static $aTipoCriteri=null;
    const AA_GECO_TIPO_CRITERI_ATTO_NORMATIVO=1;
    const AA_GECO_TIPO_CRITERI_ATTO_GENERALE=2;

    public static function GetTipoCriteri()
    {
        if(static::$aTipoCriteri==null)
        {
            static::$aTipoCriteri=array(
                static::AA_GECO_TIPO_CRITERI_ATTO_NORMATIVO=>"Atto normativo (legge e regolamento)",
                static::AA_GECO_TIPO_CRITERI_ATTO_GENERALE=>"Atto di carattere amministrativo generale"
            );
        }

        return static::$aTipoCriteri;
    }

    protected static $aCategorieAllegati=null;
    const AA_GECO_CATEGORIA_ALLEGATO_AGRICOLTURA=1;
    const AA_GECO_CATEGORIA_ALLEGATO_ALLEVAMENTO=2;
    const AA_GECO_CATEGORIA_ALLEGATO_AMBIENTE=4;
    const AA_GECO_CATEGORIA_ALLEGATO_ARTIGIANATO=8;
    const AA_GECO_CATEGORIA_ALLEGATO_ALTRO=16;
    const AA_GECO_CATEGORIA_ALLEGATO_INDUSTRIA=32;
    const AA_GECO_CATEGORIA_ALLEGATO_INFORMAZIONE=64;
    const AA_GECO_CATEGORIA_ALLEGATO_INNOVAZIONE=128;
    const AA_GECO_CATEGORIA_ALLEGATO_INTERNAZIONALIZZAZIONE=256;
    const AA_GECO_CATEGORIA_ALLEGATO_ISTRUZIONE=512;
    const AA_GECO_CATEGORIA_ALLEGATO_LAVORO=1024;
    const AA_GECO_CATEGORIA_ALLEGATO_PESCA=2048;
    const AA_GECO_CATEGORIA_ALLEGATO_POLITICHE_GIOVANILI=4096;
    const AA_GECO_CATEGORIA_ALLEGATO_POLITICHE_SOCIALI=8192;
    const AA_GECO_CATEGORIA_ALLEGATO_SANITA=16384;
    const AA_GECO_CATEGORIA_ALLEGATO_SERVIZI=32768;
    const AA_GECO_CATEGORIA_ALLEGATO_SPORT=65536;
    const AA_GECO_CATEGORIA_ALLEGATO_TRASPORTI=131072;
    const AA_GECO_CATEGORIA_ALLEGATO_TURISMO=262144;
    const AA_GECO_CATEGORIA_ALLEGATO_VOLONTARIATO=524288;

    public static function GetCategorieAllegati()
    {
        if(static::$aCategorieAllegati==null)
        {
            static::$aCategorieAllegati=array(
                static::AA_GECO_CATEGORIA_ALLEGATO_AGRICOLTURA=>"Agricoltura",
                static::AA_GECO_CATEGORIA_ALLEGATO_ALLEVAMENTO=>"Allevamento e pesca",
                static::AA_GECO_CATEGORIA_ALLEGATO_AMBIENTE=>"Ambiente e territorio",
                static::AA_GECO_CATEGORIA_ALLEGATO_ARTIGIANATO=>"Artigianato e commercio",
                //static::AA_GECO_CATEGORIA_ALLEGATO_COMMERCIO=>"Commercio",
                static::AA_GECO_CATEGORIA_ALLEGATO_ISTRUZIONE=>"Cultura e istruzione",
                static::AA_GECO_CATEGORIA_ALLEGATO_INFORMAZIONE=>"Editoria e informazione",
                static::AA_GECO_CATEGORIA_ALLEGATO_INDUSTRIA=>"Industria",
                static::AA_GECO_CATEGORIA_ALLEGATO_INNOVAZIONE=>"Innovazione e ricerca",
                static::AA_GECO_CATEGORIA_ALLEGATO_INTERNAZIONALIZZAZIONE=>"Internazionalizzazione",
                static::AA_GECO_CATEGORIA_ALLEGATO_LAVORO=>"Lavoro",
                //static::AA_GECO_CATEGORIA_ALLEGATO_PESCA=>"Pesca",
                //static::AA_GECO_CATEGORIA_ALLEGATO_POLITICHE_GIOVANILI=>"Politiche giovanili",
                static::AA_GECO_CATEGORIA_ALLEGATO_POLITICHE_SOCIALI=>"Politiche sociali",
                static::AA_GECO_CATEGORIA_ALLEGATO_SANITA=>"Sanita'",
                static::AA_GECO_CATEGORIA_ALLEGATO_SERVIZI=>"Servizi",
                static::AA_GECO_CATEGORIA_ALLEGATO_SPORT=>"Sport",
                static::AA_GECO_CATEGORIA_ALLEGATO_TRASPORTI=>"Trasporti",
                static::AA_GECO_CATEGORIA_ALLEGATO_TURISMO=>"Turismo",
                static::AA_GECO_CATEGORIA_ALLEGATO_VOLONTARIATO=>"Volontariato",
                static::AA_GECO_CATEGORIA_ALLEGATO_ALTRO=>"Altro",
            );
        }

        return static::$aCategorieAllegati;
    }

}

#Classe oggetto geco
Class AA_Geco extends AA_Object_V2
{
    //tabella dati db
    const AA_DBTABLE_DATA="aa_geco_data";
    static protected $AA_DBTABLE_OBJECTS="aa_geco_objects";

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
                AA_Log::Log(__METHOD__." - errore nel parsing dei dati di revoca'",100);
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

    public function Update($user = null, $bSaveData = true, $logMsg = '')
    {
        //AA_Log::Log(__METHOD__." - Aggiornamento: ".print_r($this,true),100);
        return parent::Update($user,true,$logMsg);
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

    public function SetAllegati($val="")
    {
        if(is_array($val)) $this->aProps['Allegati']=json_encode($val);
        else $this->aProps['Allegati']=$val;
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
    const AA_UI_TASK_ADDNEWMULTI_DLG="GetGecoAddNewMultiDlg";
    //------------------------------------

    //Dialoghi
    
    //report
   
    //Section id
    const AA_ID_SECTION_CRITERI="Criteri";
    //section ui ids
    const AA_UI_DETAIL_GENERALE_BOX = "Generale_Box";

    const AA_UI_SECTION_CRITERI="Criteri";

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
        $taskManager->RegisterTask("GetGecoAddNewMultiDlg");
        $taskManager->RegisterTask("GetGecoAddNewMultiPreviewCalc");
        $taskManager->RegisterTask("GetGecoAddNewMultiPreviewDlg");
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
        $taskManager->RegisterTask("UpdateGecoDatiGenerali");
        $taskManager->RegisterTask("UpdateGecoDatiBeneficiario");
        $taskManager->RegisterTask("PublishGeco");
        $taskManager->RegisterTask("GetGecoConfirmPrivacyDlg");
        $taskManager->RegisterTask("GetGecoRevocaModifyDlg");
        $taskManager->RegisterTask("UpdateGecoDatiRevoca");
        
        //criteri
        $taskManager->RegisterTask("GetGecoAddNewCriteriDlg");
        $taskManager->RegisterTask("AddNewGecoCriteri");
        $taskManager->RegisterTask("GetGecoModifyCriteriDlg");
        $taskManager->RegisterTask("GetGecoCopyCriteriDlg");
        $taskManager->RegisterTask("UpdateGecoCriteri");
        $taskManager->RegisterTask("GetGecoTrashCriteriDlg");
        $taskManager->RegisterTask("DeleteGecoCriteri");

        //Allegati
        $taskManager->RegisterTask("GetGecoAddNewAllegatoDlg");
        $taskManager->RegisterTask("AddNewGecoAllegato");
        $taskManager->RegisterTask("GetGecoModifyAllegatoDlg");
        $taskManager->RegisterTask("UpdateGecoAllegato");
        $taskManager->RegisterTask("GetGecoTrashAllegatoDlg");
        $taskManager->RegisterTask("DeleteGecoAllegato");
        
        //template dettaglio
        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL,array(
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateGecoDettaglio_Generale_Tab")
        ));

        $criteri=new AA_GenericModuleSection(static::AA_ID_SECTION_CRITERI,"Criteri e modalita'",true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_CRITERI,$this->GetId(),false,true,false,false,'mdi-text-box-multiple',"TemplateSection_Criteri");
        $this->AddSection($criteri);

        $pubblicate=$this->GetSection(static::AA_ID_SECTION_PUBBLICATE);
        $pubblicate->SetNavbarTemplate(array($this->TemplateGenericNavbar_Bozze(1)->toArray(),$this->TemplateGenericNavbar_Section($criteri,2,true)->toArray()));

        $criteri->SetNavbarTemplate(array($this->TemplateGenericNavbar_Atti(1,true,true)->toArray()));

        //Custom object template
        //$this->AddObjectTemplate(static::AA_UI_PREFIX."_".static::AA_UI_WND_RENDICONTI_COMUNALI."_".static::AA_UI_LAYOUT_RENDICONTI_COMUNALI,"Template_GetGecoComuneRendicontiViewLayout");
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
        $content->EnableTrash(false);

        return $content->toObject();
    }

    //Template pubblicate content
    public function TemplateSection_Bozze($params=array())
    {
        $bCanModify=false;
        if($this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
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
        //anno rif
        if(isset($params['Anno']) && $params['Anno']> 0)
        {
            $params['where'][]=" AND ".AA_Geco::AA_DBTABLE_DATA.".anno = '".addslashes($params['Anno'])."'";
        }
        
        //revocate
        if(isset($params['revocate']) &&  $params['revocate']>0)
        {
            $params['where'][]=" AND ".AA_Geco::AA_DBTABLE_DATA.".revoca like '{\"data\":\"%\",%'";
        }

        //beneficiario
        if(isset($params['Beneficiario']) &&  $params['Beneficiario']!="")
        {
            $query=AA_Geco::AA_DBTABLE_DATA.".beneficiario like '{\"nome\":\"%". addslashes($params['Beneficiario'])."%\",%'";
            $query.=" OR ".AA_Geco::AA_DBTABLE_DATA.".beneficiario like '{%,\"cf\":\"%". addslashes($params['Beneficiario'])."%\",%'";
            $query.=" OR ".AA_Geco::AA_DBTABLE_DATA.".beneficiario like '{%,\"piva\":\"%". addslashes($params['Beneficiario'])."%\",%'";
            $params['where'][]=" AND (".$query.")";
        }

        //responsabile
        if(isset($params['Responsabile']) &&  $params['Responsabile']!="")
        {
            $query=AA_Geco::AA_DBTABLE_DATA.".responsabile like '{\"nome\":\"%". addslashes($params['Responsabile'])."%\"%'";
            $params['where'][]=" AND ".$query;
        }

        //modalita'
        if(isset($params['Modalita']) &&  $params['Modalita']>0)
        {
            $query=AA_Geco::AA_DBTABLE_DATA.".modalita like '{\"tipo\":". addslashes($params['Modalita']).",%'";
            $params['where'][]=" AND ".$query;
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
            
            $tag="";
            $modalita=$object->GetModalita();
            $tag.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$modalita['descrizione']."</span>";

            $beneficiario=$object->GetBeneficiario();
            if(($object->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE)>0 || $beneficiario['privacy']==0)
            {
                $class="AA_DataView_Tag AA_Label AA_Label_LightYellow";
                if($beneficiario['tipo']==1) $class.=' mdi mdi-account-eye';
                if($beneficiario['privacy']==1) $class.=' mdi mdi-account-off';

                $tag.="<span class='".$class."'>".$beneficiario['nome']."</span>";
            }
           
            $revoca=$object->GetRevoca();
            if(isset($revoca['data']) && $revoca['data'] !="")
            {
                 $tag.="&nbsp;<span class='AA_Label AA_Label_LightOrange'>Revocato</span>";
            }
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
        if(isset($params['Anno']) && $params['Anno'] > 0)
        {
            $params['where'][]=" AND ".AA_Geco::AA_DBTABLE_DATA.".anno = '".addslashes($params['Anno'])."'";
        }

        //beneficiario
        if(isset($params['Beneficiario']) &&  $params['Beneficiario']!="")
        {
            $query=AA_Geco::AA_DBTABLE_DATA.".beneficiario like '{\"nome\":\"%". addslashes($params['Beneficiario'])."%\",%'";
            $query.=" OR ".AA_Geco::AA_DBTABLE_DATA.".beneficiario like '{%,\"cf\":\"%". addslashes($params['Beneficiario'])."%\",%'";
            $query.=" OR ".AA_Geco::AA_DBTABLE_DATA.".beneficiario like '{%,\"piva\":\"%". addslashes($params['Beneficiario'])."%\",%'";
            $params['where'][]=" AND (".$query.")";
        }

        //responsabile
        if(isset($params['Responsabile']) &&  $params['Responsabile']!="")
        {
            $query=AA_Geco::AA_DBTABLE_DATA.".responsabile like '{\"nome\":\"%". addslashes($params['Responsabile'])."%\"%'";
            $params['where'][]=" AND ".$query;
        }

        //modalita'
        if(isset($params['Modalita']) &&  $params['Modalita']>0)
        {
            $query=AA_Geco::AA_DBTABLE_DATA.".modalita like '{\"tipo\":". addslashes($params['Modalita']).",%'";
            $params['where'][]=" AND ".$query;
        }

        return $params;
    }

    //Personalizza il template dei dati delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomDataTemplate($data = array(),$object=null)
    {
        
        if($object instanceof AA_Geco)
        {

            $data['pretitolo']=$object->GetProp("Anno");
            
            $tag="";
            $modalita=$object->GetModalita();
            $tag.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$modalita['descrizione']."</span>";

            $beneficiario=$object->GetBeneficiario();
            $class="AA_DataView_Tag AA_Label AA_Label_LightYellow";
            if($beneficiario['tipo']==1) $class.=' mdi mdi-account-eye';
            if($beneficiario['privacy']==1) $class.=' mdi mdi-account-off';
            $tag.="<span class='".$class."'>".$beneficiario['nome']."</span>";
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
        
    //Template dlg addnew
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
        foreach($modalita as $num=>$val)
        {
            $modalita_options[]=array("id"=>$num,"value"=>$val);
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
        $wnd->AddTextField("Responsabile_nome","Responsabile",array("required"=>true,"bottomPadding"=>32, "bottomLabel"=>"*Inserisci il nominativo e qualifica del responsabile del procedimento amministrativo.", "placeholder"=>"es. Nome, cognome e qualifica ..."));

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
        $section=new AA_FieldSet($id."_Beneficiario","Beneficiario");
        
        //Nome e cognome
        $section->AddTextField("Beneficiario_nome","Nominativo",array("required"=>true,"gravity"=>2,"bottomPadding"=>32, "bottomLabel"=>"*Inserisci il nominativo/ragione sociale (max 255 caratteri).", "placeholder"=>"es. Mario Rossi..."));
        
        //cf
        $section->AddTextField("Beneficiario_cf","C.F.",array("required"=>true, "gravity"=>1,"bottomPadding"=>32,"labelWidth"=>60,"bottomLabel"=>"*Inserisci il codice fiscale del beneficiario."),false);

        //piva
        $section->AddTextField("Beneficiario_piva","P.IVA",array("gravity"=>1,"labelWidth"=>60,"bottomPadding"=>32,"bottomLabel"=>"*Inserisci la partita iva del beneficiario (se applicabile)."),false);

        //Tipo
        $section->AddCheckBoxField("Beneficiario_tipo"," ",array("bottomPadding"=>32,"labelWidth"=>60, "labelRight"=>"<b>Persona fisica/Ditta individuale/Libero professionista</b>", "gravity"=>1, "bottomLabel"=>"*Abilita se il beneficiario e' una persona fisica, una ditta individuale o un libero professionista.","eventHandlers"=>array("onChange"=>array("handler"=>"onPersonaFisicaChange","module_id"=>$this->GetId()))));

        //Privacy
        $section->AddCheckBoxField("Beneficiario_privacy"," ",array("bottomPadding"=>32,"gravity"=>1,"labelWidth"=>60,"labelRight"=>"<b>Oscuramento dati personali</b>", "bottomLabel"=>"*Abilita se dalla pubblicazione sia possibile ricavare informazioni relative allo stato di salute e alla situazione di disagio economico-sociale degli interessati."),false);
 
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

    //Template dlg addnew geco da csv
    public function Template_GetGecoAddNewMultiDlg()
    {
        $id=static::AA_UI_PREFIX."_GetGecoAddNewMultiDlg_".uniqid();

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
        $wnd->AddFileUploadField("GecoAddNewMultiCSV","Scegli il file csv...", array("required"=>true,"validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti in formato csv (dimensione max: 2Mb).","accept"=>"application/csv"));

        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->enableRefreshOnSuccessfulSave(false);

        $wnd->SetApplyButtonName("Procedi");

        $wnd->SetSaveTask("GetGecoAddNewMultiPreviewCalc");
        
        return $wnd;
    }

    //Template dlg addnew multi preview
    public function Template_GetGecoAddNewMultiPreviewDlg()
    {
        $id=static::AA_UI_PREFIX."_GetGecoAddNewMultiPreviewDlg_".uniqid();

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

        $data=AA_SessionVar::Get("GecoAddNewMultiFromCSV_ParsedData")->GetValue();
        
        AA_SessionVar::UnsetVar("GecoAddNewMultiFromCSV_ParsedData");

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

        $wnd->SetSaveTask("GecoAddNewMulti");
        
        return $wnd;
    }

    //Template confirm 
    public function Template_GetGecoConfirmPrivacyDlg($form_id='')
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
    public function Template_GetGecoAddNewAllegatoDlg($object=null)
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
        $tipo_allegati=AA_Geco_Const::GetListaTipoAllegati();
        foreach($tipo_allegati as $key=>$val)
        {
            $options[]=array("id"=>$key,"value"=>$val);
        }
        $wnd->AddSelectField("tipo", "Tipologia", array("gravity"=>1,"required"=>true,"validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere la tipologia di allegato/link", "placeholder" => "Scegli un elemento della lista...","options"=>$options));
        
        //Descrizione
        $wnd->AddTextField("descrizione", "Descrizione", array("gravity"=>1,"required"=>true,"bottomLabel" => "*Inserisci una breve descrizione dell'allegato/link","placeholder" => "es. DGR..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        //categorie
        /*$tipi=AA_Geco_Const::GetCategorieAllegati();$curRow=1;
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
        $wnd->SetSaveTask("AddNewGecoAllegato");
        
        return $wnd;
    }

    //Template dlg aggiungi criteri
    public function Template_GetGecoAddNewCriteriDlg()
    {
        $id=uniqid();
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array();
        $wnd=new AA_GenericFormDlg($id, "Aggiungi Criteri e modalita'", $this->id,$form_data,$form_data);
        
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
        $listaTipo=AA_Geco_Const::GetTipoCriteri();
        foreach($listaTipo as $id=>$val)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$val);
        }
        $wnd->AddSelectField("tipo","Tipo",array("gravity"=>2,"required"=>true,"labelWidth"=>120,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il tipo di atto.","options"=>$options),false);

        //Estremi
        $wnd->AddTextareaField("estremi", "Estremi", array("gravity"=>1,"required"=>true,"labelWidth"=>80,"labelAlign"=>"right","bottomLabel" => "*Inserisci il numero e la data del documento.","placeholder" => "es. DGR..."));

        //descrizione
        $wnd->AddTextareaField("descrizione", "Descrizione", array("gravity"=>2,"required"=>true,"labelWidth"=>120,"labelAlign"=>"right","bottomLabel" => "*Inserisci una breve descrizione (max 1024).","placeholder" => "..."),false);

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>20)));
        
        //categorie
        $tipi=AA_Geco_Const::GetCategorieAllegati();$curRow=1;
        $section=new AA_FieldSet($id."_Section_Tipo","Categorie");
        $curRow=0;
        foreach($tipi as $tipo=>$descr)
        {
            $newLine=false;
            if($curRow%4 == 0 && $curRow >= 4) $newLine=true;
            $section->AddCheckBoxField("categoria_".$tipo, $descr, array("value"=>1,"bottomPadding"=>8,"labelAlign"=>"right","labelWidth"=>180),$newLine);
            $curRow++;
        }

        for($i=$curRow%4;$i<4;$i++)
        {
            $section->AddSpacer(false);
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
        $wnd->SetSaveTask("AddNewGecoCriteri");
        
        return $wnd;
    }

    //Template dlg aggiungi criteri
    public function Template_GetGecoModifyCriteriDlg($criterio=null)
    {
        $id=uniqid();
        
        if(!($criterio instanceof AA_Geco_Criteri))
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
        $tipi=AA_Geco_Const::GetCategorieAllegati();
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
        $listaTipo=AA_Geco_Const::GetTipoCriteri();
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
        $wnd->SetSaveTask("UpdateGecoCriteri");
        
        return $wnd;
    }

    //Template dlg aggiungi criteri
    public function Template_GetGecoCopyCriteriDlg($criterio=null)
    {
        $id=uniqid();
        
        if(!($criterio instanceof AA_Geco_Criteri))
        {
            $wnd=new AA_GenericWindowTemplate($id, "Copia Criteri e modalita'", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<div>id criterio non valido.</div>")));

            return $wnd;
        }

        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        $form_data=array(
            "estremi"=>$criterio->GetProp('estremi'),
            "tipo"=>$criterio->GetProp('tipo'),
            "descrizione"=>$criterio->GetProp('descrizione'),
            "url"=>$criterio->GetProp('url'),
            "anno"=>$criterio->GetProp('anno')
        );

        //categorie
        $tipi=AA_Geco_Const::GetCategorieAllegati();
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

        $wnd=new AA_GenericFormDlg($id, "Copia Criteri e modalita'", $this->id,$form_data,$form_data);
        
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
        $listaTipo=AA_Geco_Const::GetTipoCriteri();
        foreach($listaTipo as $id=>$val)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$val);
        }
        $wnd->AddSelectField("tipo","Tipo",array("gravity"=>2,"required"=>true,"labelWidth"=>120,"labelAlign"=>"right","validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il tipo di atto.","options"=>$options),false);

        //Estremi
        $wnd->AddTextareaField("estremi", "Estremi", array("gravity"=>1,"required"=>true,"labelWidth"=>80,"labelAlign"=>"right","bottomLabel" => "*Inserisci gli estremi del documento","placeholder" => "es. DGR..."));

        //descrizione
        $wnd->AddTextareaField("descrizione", "Descrizione", array("gravity"=>2,"required"=>true,"labelWidth"=>120,"labelAlign"=>"right","bottomLabel" => "*Inserisci una breve dscrizione (max 1024)","placeholder" => "..."),false);
    
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
        $wnd->SetSaveTask("AddNewGecoCriteri");
        
        return $wnd;
    }

    //Template dlg modifca allegato/link
    public function Template_GetGecoModifyAllegatoDlg($object=null,$allegato=null)
    {
        $id=static::AA_UI_PREFIX."_GetGecoModifyAllegatoDlg";
        
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
        $tipo_allegati=AA_Geco_Const::GetListaTipoAllegati();
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
        $wnd->SetSaveTask("UpdateGecoAllegato");
        
        return $wnd;
    }

    //Template dlg trash allegato
    public function Template_GetGecoTrashCriteriDlg($object=null)
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
        $wnd->SetSaveTask("DeleteGecoCriteri");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetProp("id")));
        
        return $wnd;
    }

    //Template dlg trash allegato
    public function Template_GetGecoTrashAllegatoDlg($object=null,$allegato=null)
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
        $wnd->SetSaveTask("DeleteGecoAllegato");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_allegato"=>$allegato['id']));
        
        return $wnd;
    }

    //Task Aggiungi allegato
    public function Task_AddNewGecoAllegato($task)
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

        $object=new AA_Geco($_REQUEST['id'], $this->oUser);
        
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

    //Task Aggiungi criterio
    public function Task_AddNewGecoCriteri($task)
    {        
        $uploadedFile = AA_SessionFileUpload::Get("NewAllegatoDoc");

        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO_CRITERI) && !$this->oUser->IsSuperUser())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);

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

        if(!isset($_REQUEST['estremi']) || $_REQUEST['estremi'] == "")
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Parametri non validi occorre specificare gli estremi del documento.",false);

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

        if(!isset($_REQUEST['anno']) || $_REQUEST['anno'] == "")
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Parametri non validi occorre specificare l'anno di riferimento.",false);

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
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Parametri non validi occorre indicare un url o un file.",false);

            return false;
        }

        $categorie=0;
        $lista_categorie=AA_Geco_Const::GetCategorieAllegati();
        foreach($lista_categorie as $key=>$val)
        {
            if(isset($_REQUEST['categoria_'.$key]) && $_REQUEST['categoria_'.$key]==1) $categorie+=intVal($key);
        }
        
        if($categorie==0)
        {
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre selezionare almeno una categoria.",false);

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
                    $_REQUEST['file']=$storageFile->GetFileHash();
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

        $_REQUEST['categorie']=$categorie;

        $criterio=new AA_Geco_Criteri();

        if(!$criterio->Update($_REQUEST,$this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiunta del nuovo criterio/modalita'.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Criterio/modalita' aggiunta con successo.",false);

            return true;
        }
    }

    public function Task_UpdateGecoCriteri($task)
    {        
        $uploadedFile = AA_SessionFileUpload::Get("NewAllegatoDoc");

        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO_CRITERI) && !$this->oUser->IsSuperUser())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare elementi.",false);

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

        $criterio=new AA_Geco_Criteri();
        if(!$criterio->Load($_REQUEST['id'],$this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Criterio non trovato.",false);

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

        if(!isset($_REQUEST['estremi']) || $_REQUEST['estremi'] == "")
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Parametri non validi occorre specificare gli estremi del documento.",false);

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

        if(!isset($_REQUEST['anno']) || $_REQUEST['anno'] == "")
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Parametri non validi occorre specificare l'anno di riferimento.",false);

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

        $categorie=0;
        $lista_categorie=AA_Geco_Const::GetCategorieAllegati();
        foreach($lista_categorie as $key=>$val)
        {
            if(isset($_REQUEST['categoria_'.$key]) && $_REQUEST['categoria_'.$key]==1) $categorie+=intVal($key);
        }
        
        if($categorie==0)
        {
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre selezionare almeno una categoria.",false);

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

        if(!$uploadedFile->isValid() && $_REQUEST['url'] == "" && $criterio->GetProp('file') == "")
        {   
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($uploadedFile,true)." - ".print_r($_REQUEST,true),100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Parametri non validi occorre indicare un url o un file.",false);

            return false;
        }
        
        if($uploadedFile->isValid()) 
        {
            //Se c'è un file uploadato l'url non viene salvata.
            $_REQUEST['url']="";

            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                if($criterio->GetProp('file') !="")
                {
                    $storage->DelFile($criterio->GetProp('file'));
                }

                $file=$uploadedFile->GetValue();
                $storageFile=$storage->Addfile($file['tmp_name'],$file['name'],$file['type'],1);
                if($storageFile->IsValid())
                {
                    $_REQUEST['file']=$storageFile->GetFileHash();
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
        else
        {
            if($_REQUEST['url'] !="" && $criterio->GetProp('file') !="")
            {
                $_REQUEST['file']="";
                $storage=AA_Storage::GetInstance($this->oUser);
                if($storage->IsValid())
                {
                    $storage->DelFile($criterio->GetProp('file'));
                }
            }
        }

        $_REQUEST['categorie']=$categorie;

        if(!$criterio->Update($_REQUEST,$this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nella modifica del criterio/modalita'.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati con successo.",false);

            return true;
        }
    }

    public function Task_DeleteGecoCriteri($task)
    {        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO_CRITERI) && !$this->oUser->IsSuperUser())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare elementi.",false);

            return false;
        }

        $criterio=new AA_Geco_Criteri();
        if(!$criterio->Load($_REQUEST['id'],$this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Criterio non trovato.",false);

            return false;
        }

        if(!$criterio->Delete($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nella rimozione del criterio/modalita'.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Criterio/modalita' eliminato con successo.",false);

            return true;
        }
    }

    //Template dlg modify geco
    public function Template_GetGecoModifyDlg($object=null)
    {
        $id=$this->GetId()."_Modify_Dlg_".uniqid();
        if(!($object instanceof AA_Geco)) return new AA_GenericWindowTemplate($id, "Modifica i dati generali del contributo", $this->id);

        $form_data=array();

        $form_data['id']=$object->GetId();
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

        $form_data['Note']=$object->GetProp('Note');

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
        $wnd->AddTextField("Responsabile_nome","Responsabile",array("required"=>true,"bottomPadding"=>32, "bottomLabel"=>"*Inserisci il nominativo del responsabile e qualifica del procedimento amministrativo.", "placeholder"=>"es. Nome, cognome e qualifica..."));

        //Norma
        $section=new AA_FieldSet($id."_Norma","Norma o titolo a base dell'attribuzione");

        //estremi
        $section->AddTextField("Norma_estremi","Estremi",array("required"=>true, "gravity"=>2,"bottomPadding"=>32,"labelWidth"=>90,"bottomLabel"=>"*Inserisci gli estremi della norma o dell'atto amministrativo generale.", "placeholder"=>"es. art.26 del d.lgs. 33/2013..."));

        //link alla norma
        $section->AddTextField("Norma_link","Link",array("required"=>true,"gravity"=>3,"bottomPadding"=>32,"labelWidth"=>90, "validateFunction"=>"IsUrl","bottomLabel"=>"*Inserisci il link alla norma o all'atto amministrativo generale.", "placeholder"=>"es. https://www.regione.sardegna.it..."),false);

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
        $wnd->AddTextareaField("Note",$label,array("bottomLabel"=>"*Inserisci qui le note (max 1024 caratteri, visibilita' pubblica).", "placeholder"=>"..."));

        //Note
        //$label="Note";
        //$wnd->AddTextareaField("Note",$label,array("bottomLabel"=>"*Eventuali annotazioni (max 4096 caratteri).", "placeholder"=>"Inserisci qui le note..."));
        
        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateGecoDatiGenerali");
        
        return $wnd;
    }

    //Template dlg modify beneficiario
    public function Template_GetGecoBeneficiarioModifyDlg($object=null)
    {
        $id=$this->GetId()."_Modify_Dlg";
        if(!($object instanceof AA_Geco)) return new AA_GenericWindowTemplate($id, "Modifica i dati beneficiario", $this->id);

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
        $wnd->SetSaveTask("UpdateGecoDatiBeneficiario");
        
        return $wnd;
    }

    //Template dlg modify beneficiario
    public function Template_GetGecoRevocaDlg($object=null)
    {
        $id=$this->GetId()."_Revoca_Dlg_".uniqid();
        if(!($object instanceof AA_Geco)) return new AA_GenericWindowTemplate($id, "Modifica i dati di revoca", $this->id);

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
        $wnd->SetSaveTask("UpdateGecoDatiRevoca");
        
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
        //$params['disable_trash']=true;
        $params['disable_public_trash']=true;

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
        if(isset($revoca['data']) && AA_Utils::validateDate($revoca['data']))
        {
            $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>32,"type"=>"clean","borderless"=>true));
            $revocato="<span class='AA_Label AA_Label_LightOrange mdi mdi-cash-off' style='font-size:larger;line-height: 28px;'>&nbsp;Contributo revocato</span>";
            $toolbar->AddElement(new AA_JSON_Template_Generic());
            $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_OC_Certified_Title",array("view"=>"label","label"=>$revocato,"width"=>240,"align"=>"center")));
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
                "data"=>array("title"=>"Nominativo:","value"=>$beneficiario['nome'])
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

        if(($object->GetStatus()&AA_Const::AA_STATUS_PUBBLICATA)>0)
        {
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
                    "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetGecoRevocaModifyDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
                ));
                $toolbar->AddElement($modify_btn);
            }
            $revoca_box->AddRow($toolbar);
            if(isset($revoca['data']) && $revoca['data'] !="")
            {
                $revoca['data']=date("d-m-Y",strtotime($revoca['data']));

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
            }
            else
            {
                $revoca_box->AddRow(new AA_JSON_Template_Template("",array("template"=>"<div style='display: flex; justify-content:center; align-items:center;widht:100%;height:100%'><span>Contributo non revocato</span></div>")));
            }
            //------------------------------------------------------------------------
            $riga->AddCol($revoca_box);
        }
        
        //-------------------- Allegati --------------------------------------
        //$toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        //$toolbar->AddElement(new AA_JSON_Template_Generic(""));
        //$toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"<span style='color:#003380'>Allegati</span>", "align"=>"center")));
        //$toolbar->AddElement(new AA_JSON_Template_Generic(""));
        //$allegati_box->AddRow($toolbar);
        $allegati_box->AddRow($this->TemplateDettaglio_Allegati($object,$id,$canModify));
        $riga->AddCol($allegati_box);
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

    //Template section criteri
    public function TemplateSection_Criteri($params=array())
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_CRITERI;
        $canModify=false;

        #criteri----------------------------------
        if($this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO_CRITERI)) $canModify=true;

        $storage=AA_Storage::GetInstance();

        $documenti_data=array();
        $criteri=AA_Geco_Criteri::Search();
        $categorie=AA_Geco_Const::GetCategorieAllegati();
        foreach($criteri as $id_doc=>$curDoc)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);

            if(($curDoc->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_ALL) > 0) $canModify=true;
            else $canModify=false;

            if($curDoc->GetProp("url") == "")
            {
                $view='AA_MainApp.utils.callHandler("wndOpen", {url: "storage.php?object='.$curDoc->GetProp("file").'"},"'.$this->id.'")';
                $view_icon="mdi-floppy";
                $tip="Scarica";

                if($storage->IsValid())
                {
                    $file=$storage->GetFileByHash($curDoc->GetProp("file"));
                    if($file->IsValid())
                    {
                        if(strpos($file->GetmimeType(),"pdf",0) !==false)
                        {
                            $view='AA_MainApp.utils.callHandler("pdfPreview", {url: "storage.php?object='.$curDoc->GetProp("file").'"},"'.$this->id.'")';
                            $view_icon="mdi-eye";
                            $tip="Consulta";
                        }
                    }
                }
            }
            else 
            {
                $view='AA_MainApp.utils.callHandler("wndOpen", {url: "'.$curDoc->GetProp("url").'"},"'.$this->id.'")';
                $view_icon="mdi-eye";
                $tip="Naviga (in un&apos;altra finestra)";
            }
            
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoTrashCriteriDlg", params: [{id:"'.$curDoc->GetProp("id").'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoModifyCriteriDlg", params: [{id:"'.$curDoc->GetProp("id").'"}]},"'.$this->id.'")';
            $copy='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoCopyCriteriDlg", params: [{id:"'.$curDoc->GetProp("id").'"}]},"'.$this->id.'")';
            if($canModify) $ops="<div class='AA_DataTable_Ops' style='justify-content: space-between;width: 100%'><a class='AA_DataTable_Ops_Button' title='".$tip."' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Copia' onClick='".$copy."'><span class='mdi mdi-content-copy'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $ops="<div class='AA_DataTable_Ops' style='justify-content: center; width: 100%'><a class='AA_DataTable_Ops_Button' title='".$tip."' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a></div>";

            $docTipo=array();
            foreach($categorie as $key=>$val)
            {
                if(($curDoc->GetProp('categorie')&$key)>0) $docTipo[]="<span class='AA_Label AA_Label_LightGreen'>".$val."</span>";
            }
            
            $documenti_data[]=array("id"=>$id_doc,"anno"=>$curDoc->GetProp("anno"),"descrizione"=>$curDoc->GetProp("descrizione"),"estremi"=>$curDoc->GetProp("estremi"),"tipoDescr"=>implode("&nbsp;",$docTipo),"ops"=>$ops);
        }

        $template=new AA_GenericDatatableTemplate($id,"Criteri e modalita'",5,null,array("css"=>"AA_Header_DataTable"));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader();
        $template->SetHeaderHeight(38);

        if($canModify) 
        {
            $template->EnableAddNew(true,"GetGecoAddNewCriteriDlg");
            //$template->SetAddNewTaskParams(array("postParams"=>array("postParam1"=>0)));
        }

        $template->SetColumnHeaderInfo(0,"anno","<div style='text-align: center'>Anno</div>",90,"textFilter","int","CriteriTable_left");
        $template->SetColumnHeaderInfo(1,"estremi","<div style='text-align: center'>Estremi</div>","fillspace","textFilter","text","CriteriTable_left");
        $template->SetColumnHeaderInfo(2,"descrizione","<div style='text-align: center'>Descrizione</div>","fillspace","textFilter","text","CriteriTable_left");
        $template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        $template->SetColumnHeaderInfo(4,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"CriteriTable");

        $template->SetData($documenti_data);

        return $template;

        /*
        $layout=new AA_JSON_Template_Layout($curId,array("type"=>"clean","name"=>"Criteri e modalita'","filtered"=>true,"gravity"=>1));

        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        
        //pulsante di filtro
        $filter="";

        $session_params=AA_SessionVar::Get($id);
        if($session_params->IsValid())
        {
            $params=(array)$session_params->GetValue();
            //AA_Log::Log(__METHOD__." - session var: ".$id." - value: ".print_r($params,true),100);
        }
        foreach($params as $key=>$curParam)
        {
            if(isset($_REQUEST[$key])) $params[$key]=$_REQUEST[$key];
        }

        if(isset($params['senza_operatori']) && $params['senza_operatori'])
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>solo comuni con operatori non caricati</span>&nbsp;";
        }

        if(isset($params['senza_affluenza']) && $params['senza_affluenza'] > 0)
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>solo comuni con affluenza non caricata</span>&nbsp;";
        }

        if(isset($params['senza_risultati']) && $params['senza_risultati'] > 0)
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>solo comuni con risultati non caricati</span>&nbsp;";
        }

        if(isset($params['senza_voti_lista']) && $params['senza_voti_lista'] > 0)
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>solo comuni senza voti di lista</span>&nbsp;";
        }

        if(isset($params['scrutinio_parziale']) && $params['scrutinio_parziale'] > 0)
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>solo comuni con scrutinio parziale</span>&nbsp;";
        }

        if(isset($params['senza_rendiconti']) && $params['senza_rendiconti'] > 0)
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>solo comuni con rendiconti non caricati</span>&nbsp;";
        }
        
        if(isset($params['con_rendiconti']) && $params['con_rendiconti'] > 0)
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>solo comuni con rendiconti caricati</span>&nbsp;";
        }

        if(isset($params['con_criticita']) && $params['con_criticita'] > 0)
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>solo comuni con criticità</span>&nbsp;";
        }

        if($filter=="") $filter="<span class='AA_Label AA_Label_LightOrange'>tutti</span>";
        
        $toolbar->addElement(new AA_JSON_Template_Generic($id."_FilterLabel",array("view"=>"label","align"=>"left","label"=>"<div>Visualizza: ".$filter."</div>")));

        //filtro
        $modify_btn=new AA_JSON_Template_Generic($id."_FilterCriteri_btn",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-filter-cog",
             "label"=>"Filtra",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Opzioni di filtraggio",
             "click"=>"AA_MainApp.utils.callHandler('dlg', {task:'GetGecoFilterCriteriDlg'},'".$this->id."')"
        ));
        $toolbar->AddElement($modify_btn);

        //Pulsante di aggiunta
        $canModify=false;
        if($this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO_CRITERI)) $canModify=true;
        if($canModify)
        {            
            $modify_btn=new AA_JSON_Template_Generic($id."_AddNew_btn_".uniqid(),array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil-plus",
                "label"=>"Aggiungi",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi un nuovo criterio",
                //"click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierAddNewCandidatoDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetGecoAddNewCriteriDlg\"},'".$this->id."')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        $layout->AddRow($toolbar);

        $options_documenti=array();

        $options_documenti[]=array("id"=>"anno","header"=>array("<div style='text-align: center'>Anno</div>",array("content"=>"textFilter")),"width"=>90, "css"=>"CriteriTable_left","sort"=>"int");
        $options_documenti[]=array("id"=>"estremi","header"=>array("<div style='text-align: center'>Estremi</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>"CriteriTable_left","sort"=>"text");
        $options_documenti[]=array("id"=>"descrizione","header"=>array("<div style='text-align: center'>Descrizione</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>"CriteriTable_left","sort"=>"text");
        $options_documenti[]=array("id"=>"tipoDescr","header"=>array("<div style='text-align: center'>Categorie</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>"CriteriTable");
        $options_documenti[]=array("id"=>"ops", "header"=>"operazioni", "width"=>120,"css"=>"CriteriTable");
   
        $documenti=new AA_JSON_Template_Generic($curId."_Criteri_Table",array("view"=>"datatable", "select"=>true,"scrollX"=>false,"css"=>"AA_Header_DataTable","fixedRowHeight"=>false,"rowLineHeight"=>24,"hover"=>"AA_DataTable_Row_Hover","columns"=>$options_documenti));

        $storage=AA_Storage::GetInstance();

        $documenti_data=array();
        $criteri=AA_Geco_Criteri::Search();
        $categorie=AA_Geco_Const::GetCategorieAllegati();
        foreach($criteri as $id_doc=>$curDoc)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);

            if(($curDoc->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_ALL) > 0) $canModify=true;
            else $canModify=false;

            if($curDoc->GetProp("url") == "")
            {
                $view='AA_MainApp.utils.callHandler("wndOpen", {url: "storage.php?object='.$curDoc->GetProp("file").'"},"'.$this->id.'")';
                $view_icon="mdi-floppy";
                $tip="Scarica";

                if($storage->IsValid())
                {
                    $file=$storage->GetFileByHash($curDoc->GetProp("file"));
                    if($file->IsValid())
                    {
                        if(strpos($file->GetmimeType(),"pdf",0) !==false)
                        {
                            $view='AA_MainApp.utils.callHandler("pdfPreview", {url: "storage.php?object='.$curDoc->GetProp("file").'"},"'.$this->id.'")';
                            $view_icon="mdi-eye";
                            $tip="Consulta";
                        }
                    }
                }
            }
            else 
            {
                $view='AA_MainApp.utils.callHandler("wndOpen", {url: "'.$curDoc->GetProp("url").'"},"'.$this->id.'")';
                $view_icon="mdi-eye";
                $tip="Naviga (in un&apos;altra finestra)";
            }
            
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoTrashCriteriDlg", params: [{id:"'.$curDoc->GetProp("id").'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoModifyCriteriDlg", params: [{id:"'.$curDoc->GetProp("id").'"}]},"'.$this->id.'")';
            $copy='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoCopyCriteriDlg", params: [{id:"'.$curDoc->GetProp("id").'"}]},"'.$this->id.'")';
            if($canModify) $ops="<div class='AA_DataTable_Ops' style='justify-content: space-between;width: 100%'><a class='AA_DataTable_Ops_Button' title='".$tip."' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Copia' onClick='".$copy."'><span class='mdi mdi-content-copy'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $ops="<div class='AA_DataTable_Ops' style='justify-content: center; width: 100%'><a class='AA_DataTable_Ops_Button' title='".$tip."' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a></div>";

            $docTipo=array();
            foreach($categorie as $key=>$val)
            {
                if(($curDoc->GetProp('categorie')&$key)>0) $docTipo[]="<span class='AA_Label AA_Label_LightGreen'>".$val."</span>";
            }
            
            $documenti_data[]=array("id"=>$id_doc,"anno"=>$curDoc->GetProp("anno"),"descrizione"=>$curDoc->GetProp("descrizione"),"estremi"=>$curDoc->GetProp("estremi"),"tipoDescr"=>implode("&nbsp;",$docTipo),"ops"=>$ops);
        }
        $documenti->SetProp("data",$documenti_data);
        if(sizeof($documenti_data) > 0) 
        {
            $layout->AddRow($documenti);
        }
        else 
        {
            $layout->AddRow(new AA_JSON_Template_Template($id."_OC_Documenti_Void",array("type"=>"clean","template"=>"<div style='display:flex; justify-content:center; align-items:center; width:100%;height:100%'><span>Non sono presenti elementi</span></div>")));
        }
        #--------------------------------------

        return $layout;*/
    }

    //Task Update Geco
    public function Task_UpdateGecoDatiGenerali($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geco($_REQUEST['id'],$this->oUser);
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
        if(trim($_REQUEST['nome']) == "" || trim($_REQUEST['descrizione']) =="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Il titolo e la descrizione non possono essere vuoti o composti da soli spazi.",false);

            return false;
        }

        $modalita=array();
        $norma=array();
        $responsabile=array();
        $log="Aggiornamento dati generali";

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
            $task->SetError("Il link alla norma deve essere una URL pubblica accessibile tramite protocollo https.",false);

            return false;
        }

        $check_notes=false;
        if(isset($_REQUEST['Importo_impegnato'])) 
        {
            $importo['impegnato']=AA_utils::number_format(floatVal(str_replace(",",".",str_replace(".","",$_REQUEST['Importo_impegnato']))),2,".");

            if(strcmp($importo['impegnato'],$object->GetProp('Importo_impegnato')) !=0) $check_notes=true;
        }
        if(isset($_REQUEST['Importo_erogato'])) 
        {
            $importo['erogato']=AA_utils::number_format(floatVal(str_replace(",",".",str_replace(".","",$_REQUEST['Importo_erogato']))),2,".");
            if(strcmp($importo['erogato'],$object->GetProp('Importo_erogato')) !=0) $check_notes=true;
        }

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

        if($check_notes)
        {
            $notes=trim(str_replace(" ","",$object->GetProp('Note')));
            if(!isset($_REQUEST['Note']) || strcmp(trim(str_replace(" ","",$_REQUEST['Note'])),$notes)==0)
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare nelle note il motivo della variazione degli importi.",false);

                return false;
            }

            $log.=" - revisione importi.";
        }

        $_REQUEST['Importo_impegnato']=$importo['impegnato'];
        $_REQUEST['Importo_erogato']=$importo['erogato'];

        $_REQUEST['Modalita']=json_encode($modalita);
        $_REQUEST['Norma']=json_encode($norma);
        $_REQUEST['Responsabile']=json_encode($responsabile);
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

    //Task Update Geco
    public function Task_UpdateGecoDatiBeneficiario($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geco($_REQUEST['id'],$this->oUser);
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

    //Task Update Geco Revoca
    public function Task_UpdateGecoDatiRevoca($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geco($_REQUEST['id'],$this->oUser);
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

        //Se il beneficiario non e' una persona fisica disabilita l'oscuramento dei dati personali.
        if($beneficiario['tipo'] == 0) 
        {
            $beneficiario['privacy'] = 0;
        }

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
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geco($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGecoModifyDlg($object),true);
        return true;
    }

    //Task modifica dati generali elemento
    public function Task_GetGecoRevocaModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geco($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGecoRevocaDlg($object),true);
        return true;
    }

    //Task richiesta oscurtamento dati personali
    public function Task_GetGecoConfirmPrivacyDlg($task)
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
        $task->SetContent($this->Template_GetGecoConfirmPrivacyDlg($form_id),true);
        
        return true;
    }

    //Task modifica dati beneficiario
    public function Task_GetGecoBeneficiarioModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geco($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGecoBeneficiarioModifyDlg($object),true);

        return true;
    }

    //Task resume
    public function Task_GetGecoResumeDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }

        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericResumeObjectDlg($_REQUEST,"ResumeGeco"),true);
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
    public function Task_GetGecoPublishDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }
        
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericPublishObjectDlg($_REQUEST,"PublishGeco"),true);
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
    public function Task_GetGecoReassignDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }

        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericReassignObjectDlg($_REQUEST,"ReassignGeco"),true);
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
    public function Task_GetGecoTrashDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericObjectTrashDlg($_REQUEST,"TrashGeco"),true);
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
    public function Task_GetGecoDeleteDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare elementi.");
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGecoDeleteDlg($_REQUEST),true);
            return true;
        }    
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.",false);
            return false;
        }
    }
    
    //Task aggiunta 
    public function Task_GetGecoAddNewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);
            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGecoAddNewDlg(),true);
            return true;
        }
    }

    //Task aggiunta multipla
    public function Task_GetGecoAddNewMultiDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);
            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGecoAddNewMultiDlg(),true);
            return true;
        }
    }

    //Task aggiunta multipla preview
    public function Task_GetGecoAddNewMultiPreviewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);
            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGecoAddNewMultiPreviewDlg(),true);
            return true;
        }
    }

    //Task aggiunta geco da csv, passo 2 di 3
    public function Task_GetGecoAddNewMultiPreviewCalc($task)
    {
        $csvFile=AA_SessionFileUpload::Get("GecoAddNewMultiCSV");
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
        $modalita=AA_Geco_Const::GetListaModalita();

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

        AA_SessionVar::Set("GecoAddNewMultiFromCSV_ParsedData",$data,false);
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetStatusAction('dlg',array("task"=>"GetGecoAddNewMultiPreviewDlg"),true);
        $task->SetContent("Csv elaborato.",false);
                
        return true;
    }

    //Task aggiungi allegato
    public function Task_GetGecoAddNewAllegatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);

            return false;
        }

        $object=new AA_Geco($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGecoAddNewAllegatoDlg($object),true);
        return true;
    }

    //Task aggiungi criteri
    public function Task_GetGecoAddNewCriteriDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
       if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO_CRITERI) && !$this->oUser->IsSuperUser())
       {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per istanziare nuovi elementi.",false);
            return false;
       }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecoAddNewCriteriDlg(),true);
        return true;
    }

    public function Task_GetGecoModifyCriteriDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO_CRITERI) && !$this->oUser->IsSuperUser())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare elementi.",false);
            return false;
        }

        $criterio=new AA_Geco_Criteri();

        if(!$criterio->Load($_REQUEST['id'],$this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Criterio non trovato.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecoModifyCriteriDlg($criterio),true);
        return true;
    }
    
    public function Task_GetGecoCopyCriteriDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO_CRITERI) && !$this->oUser->IsSuperUser())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare elementi.",false);
            return false;
        }

        $criterio=new AA_Geco_Criteri();

        if(!$criterio->Load($_REQUEST['id'],$this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Criterio non trovato.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecoCopyCriteriDlg($criterio),true);
        return true;
    }
    
    //Task aggiorna allegato
    public function Task_UpdateGecoAllegato($task)
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

        $object= new AA_Geco($_REQUEST['id'],$this->oUser);

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
    public function Task_DeleteGecoAllegato($task)
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

        $object= new AA_Geco($_REQUEST['id'],$this->oUser);
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
    public function Task_GetGecoModifyAllegatoDlg($task)
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

        $object=new AA_Geco($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGecoModifyAllegatoDlg($object,$allegato),true);

        return true;
    }

    //Task trash allegato
    public function Task_GetGecoTrashAllegatoDlg($task)
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

        $object= new AA_Geco($_REQUEST['id'],$this->oUser);
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
        $task->SetContent($this->Template_GetGecoTrashAllegatoDlg($object,$allegato),true);
        return true;
    }

    //Task trash criteri
    public function Task_GetGecoTrashCriteriDlg($task)
    {
        if($_REQUEST['id']=="" || $_REQUEST['id']<=0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.",false);
            return false;
        }

        $object= new AA_Geco_Criteri();
        if(!$object->Load($_REQUEST['id'],$this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")",false);
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per poter modificare l'elemento (".$object->GetProp("id").").",false);
            return true;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetGecoTrashCriteriDlg($object),true);
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
        $formData=array("id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"nome"=>$params['nome'],"revocate"=>$params['revocate'],"Responsabile"=>$params['Responsabile'],"Beneficiario"=>$params['Beneficiario'],"Anno"=>$params['Anno'],"Modalita"=>$params['Modalita']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['revocate']=="") $formData['revocate']=0;
        if($params['nome']=="") $formData['nome']="";
        if($params['Responsabile']=="") $formData['Responsabile']="";
        if($params['Beneficiario']=="") $formData['Beneficiario']="";
        if($params['Anno']=="") $formData['Anno']=0;
        if($params['Modalita']=="") $formData['Modalita']=0;

        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","nome"=>"","revocate"=>0,"Responsabile"=>"", "Beneficiario"=>"","Anno"=>0,"Modalita"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Pubblicate_Filter_".uniqid(), "Parametri di ricerca per le schede pubblicate",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddCheckBoxField("revocate","Revocati",array("bottomLabel"=>"*Abilita per mostrare esclusivamente i contributi revocati."));
      
        //anno
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        for($id=date("Y"); $id>=date("Y")-6;$id--)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$id);
        }
        $dlg->AddSelectField("Anno","Anno",array("gravity"=>2,"bottomLabel"=>"*Filtra in base all'anno.","options"=>$options,"value"=>"0"));

        //Tipologia
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Geco_Const::GetListaModalita() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $dlg->AddSelectField("Modalita","Modalita'",array("gravity"=>3,"labelAlign"=>"right","labelWidth"=>90,"bottomLabel"=>"*Filtra in base alla modalita' di scelta del beneficiario.","options"=>$options,"value"=>"0"),false);

        //titolo
        $dlg->AddTextField("nome","Titolo",array("bottomLabel"=>"*Filtra in base al titolo/descrizione dell'elemento.", "placeholder"=>"..."));
        
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //Beneficiario
        $dlg->AddTextField("Beneficiario","Beneficiario",array("bottomLabel"=>"*Filtra in base al Nominativo/C.f./P.iva del beneficiario.", "placeholder"=>"..."));

        //Responsabile
        $dlg->AddTextField("Responsabile","Responsabile",array("bottomLabel"=>"*Filtra in base al nominativo del responsabile del procedimento.", "placeholder"=>"..."));
        
        $dlg->SetApplyButtonName("Filtra");

        return $dlg->GetObject();
    }
    
    //Template filtro di ricerca
    public function TemplateBozzeFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate'],"Beneficiario"=>$params['Beneficiario'],"Responsabile"=>$params['Responsabile'],"Anno"=>$params['Anno'],"Modalita"=>$params['Modalita']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['nome']=="") $formData['nome']="";
        if($params['Responsabile']=="") $formData['Responsabile']="";
        if($params['Beneficiario']=="") $formData['Beneficiario']="";
        if($params['Anno']=="") $formData['Anno']=0;
        if($params['Modalita']=="") $formData['Modalita']=0;

        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","nome"=>"","cestinate"=>0,"Responsabile"=>"","Beneficiario"=>"","Anno"=>0,"Modalita"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Bozze_Filter".uniqid(), "Parametri di ricerca per le schede in bozza",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
      
        //anno
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        for($id=date("Y"); $id>=date("Y")-6;$id--)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$id);
        }
        $dlg->AddSelectField("Anno","Anno",array("gravity"=>2,"bottomLabel"=>"*Filtra in base all'anno.","options"=>$options,"value"=>"0"));

        //Tipologia
        $options=array(array("id"=>"0","value"=>"Qualunque"));
        foreach(AA_Geco_Const::GetListaModalita() as $id=>$label)
        {
            if($id > 0) $options[]=array("id"=>$id,"value"=>$label);
        }
        $dlg->AddSelectField("Modalita","Modalita'",array("gravity"=>3,"labelAlign"=>"right","labelWidth"=>90,"bottomLabel"=>"*Filtra in base alla modalita' di scelta del beneficiario.","options"=>$options,"value"=>"0"),false);

        //titolo
        $dlg->AddTextField("nome","Titolo",array("bottomLabel"=>"*Filtra in base al titolo/descrizione dell'elemento.", "placeholder"=>"..."));
        
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //Beneficiario
        $dlg->AddTextField("Beneficiario","Beneficiario",array("bottomLabel"=>"*Filtra in base al Nominativo/C.f./P.iva del beneficiario.", "placeholder"=>"..."));

        //Responsabile
        $dlg->AddTextField("Responsabile","Responsabile",array("bottomLabel"=>"*Filtra in base al nominativo del responsabile del procedimento.", "placeholder"=>"..."));

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

      
        $options_documenti[]=array("id"=>"tipo","header"=>array("<div style='text-align: center'>Tipo</div>"),"width"=>100, "css"=>array("text-align"=>"left"),"sort"=>"text");
        $options_documenti[]=array("id"=>"descrizione","header"=>array("<div style='text-align: center'>Descrizione</div>"),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text");
        $options_documenti[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
    
        $documenti=new AA_JSON_Template_Generic($curId."_Allegati_Table",array("view"=>"datatable", "select"=>true,"scrollX"=>false,"css"=>"AA_Header_DataTable","columns"=>$options_documenti));

        $documenti_data=array();
        $allegati=$object->GetAllegati();
        $listaTipo=AA_Geco_Const::GetListaTipoAllegati();
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
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoTrashAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$id_doc.'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecoModifyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$id_doc.'"}]},"'.$this->id.'")';
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


//Oggetto criteri e modalita
Class AA_Geco_Criteri extends AA_GenericParsableDbObject
{
    protected static $dbDataTable=AA_Geco_Const::AA_GECO_DBTABLE_CRITERI;
    protected static $ObjectClass=__CLASS__;
    public function __construct($params=null,$user=null)
    {
        if(!($user instanceof AA_User))
        {
            $user=AA_User::GetCurrentUser();
        }

        $struct=$user->GetStruct();
        $user_struct_level_0=intVal($struct->GetAssessorato(true)*1000000);
        $user_struct_level_1=intVal($struct->GetDirezione(true)*1000);
        $user_struct_level_2=intVal($struct->GetServizio(true));

        $this->aProps['id']=0;
        $this->aProps['estremi']="";
        $this->aProps['anno']="";
        $this->aProps['tipo']=0;
        $this->aProps['categorie']=0;
        $this->aProps['descrizione']="";
        $this->aProps['url']="";
        $this->aProps['file']="";
        $this->aProps['struttura']=$user_struct_level_0+$user_struct_level_1+$user_struct_level_2;

        if(is_array($params))
        {
            $this->Parse($params);
        }
    }

    public function Parse($params=null, $user=null)
    {
        if(is_array($params))
        {
            if(isset($params['categorie'])) $params['categorie']=intVal($params['categorie']);
        }
        
        return parent::Parse($params);
    }

    static public function Search($params=null)
    {
        if(!$params)
        {
            $params=array();
        }

        $params['ORDER']=array("anno DESC","id DESC");

        return parent::Search($params);
    }

    public function Load($id=0,$user=null)
    {
        $data=$this->LoadDataFromDb($id);
        if(is_array($data))
        {
            $this->Parse($data);
            return true;
        }

        return false;
    }

    public function GetUserCaps($user=null)
    {
        if(!($user instanceof AA_User) || $user->IsCurrentUser()) $user=AA_User::GetCurrentUser();

        $userStruct=$user->GetStruct();
        $perms=AA_Const::AA_PERMS_READ;

        if(!$user->HasFlag(AA_Geco_Const::AA_USER_FLAG_GECO_CRITERI) && !$user->IsSuperUser()) return $perms;

        if($userStruct->GetAssessorato(true)==0) return AA_Const::AA_PERMS_ALL;

        $assessorato=intVal(substr($this->GetProp("struttura"),0,3));
        $direzione=intVal(substr($this->GetProp("struttura"),3,3));
        $servizio=intVal(substr($this->GetProp("struttura"),6,3));
        
        if($assessorato==$userStruct->GetAssessorato(true))
        {
            if($userStruct->GetDirezione(true)==0) return AA_Const::AA_PERMS_ALL;

            if($userStruct->GetDirezione(true)==$direzione)
            {
                if($userStruct->GetServizio(true)==0) return AA_Const::AA_PERMS_ALL;

                if($userStruct->GetServizio(true)==$servizio)
                {
                    $perms=AA_Const::AA_PERMS_ALL;
                }
            }
        }

        return $perms;
    }

    public function Update($params=null,$user=null)
    {
        if(!($user instanceof AA_User))
        {
            $user=AA_User::GetCurrentUser();
        }

        if(!$user->IsValid())
        {
            AA_Log::Log(__METHOD__." - Utente non valido.", 100);
            return false;
        }

        $struct=$user->GetStruct();

        if($this->aProps['id']>0)
        {
            $user_struct_level_0=intVal($struct->GetAssessorato(true));
            $user_struct_level_1=intVal($struct->GetDirezione(true));
            $user_struct_level_2=intVal($struct->GetServizio(true));
            if($user_struct_level_0 > 0)
            {
                if($user_struct_level_0-intVal($this->aProps['struttura']*0.000001) != 0)
                {
                    AA_Log::Log(__METHOD__." - Assessorato differente.", 100);
                    return false;
                }            
            }
    
            if($user_struct_level_1 > 0)
            {
                if($user_struct_level_1-intVal(($this->aProps['struttura']*0.001-$user_struct_level_0*1000) != 0))
                {
                    AA_Log::Log(__METHOD__." - Direzione differente.", 100);
                    return false;
                }            
            }
    
            if($user_struct_level_2 > 0)
            {
                if($user_struct_level_2-intVal($this->aProps['struttura']-$user_struct_level_0*1000000-$user_struct_level_1*1000) != 0)
                {
                    AA_Log::Log(__METHOD__." - Servizio differente.", 100);
                    return false;
                }            
            }
    
            if(is_array($params))
            {
                if(isset($params['id'])) unset($params['id']);
    
                if(isset($params['struttura']))
                {
                    if($user_struct_level_0 > 0)
                    {
                        if(($user_struct_level_0-$params['struttura']*0.000001) != 0)
                        {
                           $params['struttura']=$this->aProps['struttura'];
                        }            
                    }
    
                    if($user_struct_level_1 > 0)
                    {
                        if($user_struct_level_1-intVal(($params['struttura']*0.001-$user_struct_level_0*1000)) != 0)
                        {
                            $params['struttura']=$this->aProps['struttura'];
                        }            
                    }
    
                    if($user_struct_level_2 > 0)
                    {
                        if($user_struct_level_2-intVal($params['struttura']-$user_struct_level_0*1000000-$user_struct_level_1*1000) != 0)
                        {
                            $params['struttura']=$this->aProps['struttura'];
                        }            
                    }
                }
            }
        }
        else
        {
            $params['struttura']=str_pad($struct->GetAssessorato(true),3,"0",STR_PAD_LEFT).str_pad($struct->GetDirezione(true),3,"0",STR_PAD_LEFT).str_pad($struct->GetServizio(true),3,"0",STR_PAD_LEFT);
        }

        return parent::Update($params, $user);
    }

    public function Delete($user=null)
    {
        if(!($user instanceof AA_User))
        {
            $user=AA_User::GetCurrentUser();
        }

        if(!$user->IsValid())
        {
            AA_Log::Log(__METHOD__." - Utente non valido.", 100);
            return false;
        }

        $struct=$user->GetStruct();
        $user_struct_level_0=intVal($struct->GetAssessorato(true));
        $user_struct_level_1=intVal($struct->GetDirezione(true));
        $user_struct_level_2=intVal($struct->GetServizio(true));
        if($user_struct_level_0 > 0)
        {
            if($user_struct_level_0-intVal($this->aProps['struttura']*0.000001) != 0)
            {
                AA_Log::Log(__METHOD__." - Assessorato differente.", 100);
                return false;
            }            
        }

        if($user_struct_level_1 > 0)
        {
            if($user_struct_level_1-intVal(($this->aProps['struttura']*0.001-$user_struct_level_0*1000) != 0))
            {
                AA_Log::Log(__METHOD__." - Direzione differente.", 100);
                return false;
            }            
        }

        if($user_struct_level_2 > 0)
        {
            if($user_struct_level_2-intVal($this->aProps['struttura']-$user_struct_level_0*1000000-$user_struct_level_1*1000) != 0)
            {
                AA_Log::Log(__METHOD__." - Servizio differente.", 100);
                return false;
            }            
        }

        $storage=AA_Storage::GetInstance($user);
        if($storage->IsValid())
        {
            if($this->GetProp('file') !="")
            {
                $storage->DelFile($this->GetProp('file'));
            }
        }
        else AA_Log::Log(__METHOD__." - storage non inizializzato. file non eliminato.",100);


        return parent::Delete();
    }
}
