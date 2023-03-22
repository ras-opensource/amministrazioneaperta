<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "config.php";
include_once "system_lib.php";

#Costanti
Class AA_Patrimonio_Const extends AA_Const
{
    const AA_USER_FLAG_PATRIMONIO="patrimonio";

    //Risorse per codici Comuni: https://dait.interno.gov.it/territorio-e-autonomie-locali/sut/open_data.php

    //titolo di possesso
    const AA_PATRIMONIO_TITOLO_PROPRIETA=1;
    const AA_PATRIMONIO_TITOLO_POSSEDUTO=2;
    const AA_PATRIMONIO_TITOLO_DETENUTO=4;

    static $titoloList=null;
    private static function Inizialize()
    {
        if(self::$titoloList==null)
        {
            self::$titoloList=array(
                self::AA_PATRIMONIO_TITOLO_PROPRIETA=>"di proprietà",
                self::AA_PATRIMONIO_TITOLO_POSSEDUTO=>"posseduto",
                self::AA_PATRIMONIO_TITOLO_DETENUTO=>"detenuto",
            );    
        }

        if(self::$sezioneList==null)
        {
            self::$sezioneList=array(
                self::AA_PATRIMONIO_CATASTO_URBANO=>"catasto urbano",
                self::AA_PATRIMONIO_CATASTO_TERRENI=>"catasto terreni"
            );    
        }

        if(self::$tipoCanoneList==null)
        {
            self::$tipoCanoneList=array(
                self::AA_PATRIMONIO_CANONE_ATTIVO=>"Attivo",
                self::AA_PATRIMONIO_CANONE_PASSIVO=>"Passivo",
                self::AA_PATRIMONIO_CANONE_INDENNITA_OCCUPAZIONE=>"Indenità di occupazione"
            );    
        }
    }
    public static function GetTitoloList()
    {
        self::Inizialize();
        return self::$titoloList;
    }

    //Sezione catastale
    const AA_PATRIMONIO_CATASTO_URBANO=0;
    const AA_PATRIMONIO_CATASTO_TERRENI=1;
    static $sezioneList=null;
    public static function GetSezioneList()
    {
        self::Inizialize();
        return self::$sezioneList;
    }

    //Tipo Canone
    static $tipoCanoneList=null;
    const AA_PATRIMONIO_CANONE_ATTIVO=1;
    const AA_PATRIMONIO_CANONE_PASSIVO=2;
    const AA_PATRIMONIO_CANONE_INDENNITA_OCCUPAZIONE=4;
    public static function GetTipoCanoneList()
    {
        self::Inizialize();
        return self::$tipoCanoneList;
    }

}

#Classe oggetto patrimonio
Class AA_Patrimonio extends AA_Object_V2
{
    //tabella dati db
    const AA_DBTABLE_DATA="aa_patrimonio_data";

    //tabella canoni db
    const AA_DBTABLE_CANONI="aa_patrimonio_canoni";

    //file codici istat
    const AA_DBTABLE_CODICI_ISTAT="aa_patrimonio_codici_istat";
    static public function GetComuneFromCodice($codice="")
    {
        if($codice !="")
        {
            $db=new AA_Database();
            $query="SELECT comune FROM ".AA_Patrimonio::AA_DBTABLE_CODICI_ISTAT;
            $query.=" WHERE codice like '".addslashes($codice)."' LIMIT 1";

            if(!$db->query($query))
            {
                AA_Log::Log(__METHOD__." - ERRORE - ".$db->GetErrorMessage(),100);
                return "";
            }

            if($db->GetAffectedRows()>0)
            {
                foreach($db->GetResultSet() as $key=>$curRow)
                {
                    return ucwords(strtolower($curRow['comune']));
                }
            }

            return "";
        }
    }

    //Caricamento massivo
    static public function AddNewMulti($objects=null,$user=null,$bSaveData=true,$struct=null)
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
        if(!$user->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            AA_Log::Log(__METHOD__." - L'utente corrente: ".$user->GetUserName()." non ha i permessi per inserire nuovi elementi.",100);
            return false;
        }
        
        //Verifica validità oggetto
        if(!(is_array($objects)))
        {
            AA_Log::Log(__METHOD__." - Errore: array non valido (".print_r($objects,true).").",100);
            return false;
        }
        //----------------------------------------------

        //Solo il super amministratore può inserire massivamente su una struttura qualunque
        if(!$user->IsSuperUser() || $struct==null)
        {
            $struct=$user->GetStruct();
        }

        $caricati=0;
        $scartati=0;
        foreach($objects as $newObject)
        {
            if(is_array($newObject))
            {
                $newPatrimonio=new AA_Patrimonio(0,$user);
                if($newPatrimonio->Parse($newObject))
                {
                    //Imposta la struttura qualora si tratti del super user
                    if($struct instanceof AA_Struct)
                    {
                        $newPatrimonio->SetStruct($struct);
                    }

                    if(!AA_Patrimonio::AddNew($newPatrimonio,$user,$bSaveData))
                    {
                        AA_Log::Log(__METHOD__." - Errore nell'inserimento del nuovo immobile: (".print_r($newPatrimonio,true).").",100);
                        $scartati++;
                    }
                    else
                    {
                        $caricati++;
                    }
                }
                else
                {
                    AA_Log::Log(__METHOD__." - Errore nel parsing dell'array: (".print_r($newObject,true).").",100);
                    $scartati++;
                }
            }
            else
            {
                AA_Log::Log(__METHOD__." - Errore: array non valido (".print_r($newObject,true).").",100);
                $scartati++;
            }
        }

        return array($caricati,$scartati);
    }


    //lista canoni
    protected $aCanoni=null;
    protected function LoadCanoni($idData=0)
    {
        if(!$this->IsValid())
        {
            $this->aCanoni=array();
            return false;
        } 

        if($idData != $this->nId_Data && $idData != $this->nId_Data_Rev)
        {
            $idData=$this->nId_Data;
        }

        if(!$this->IsReadOnly() && $this->nId_Data_Rev > 0)
        {
            $idData=$this->nId_Data_Rev;
        }

        if($idData==0)
        {
            $idData=$this->nId_Data;
        }

        if($idData > 0)
        {
            $db=new AA_Database();

            $query="SELECT * from ".static::AA_DBTABLE_CANONI." WHERE id_patrimonio=".$idData." ORDER BY tipologia,data_inizio DESC, data_fine";

            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - ERRORE - ".$db->GetErrorMessage(),100);
                $this->aCanoni=array();
                return false;
            }

            if($db->GetAffectedRows()>0)
            {
                foreach($db->GetResultSet() as $key=>$curRow)
                {
                    $this->aCanoni[$curRow['serial']]=new AA_Patrimonio_Canone($curRow);
                }

                return true;
            }
            else $this->aCanoni=array();

            return true;
        }
        else $this->aCanoni=array();

        return true;
    }

    //Restituisce i canoni associati all'oggetto
    public function GetCanoni()
    {
        if($this->aCanoni==null) $this->LoadCanoni();

        return $this->aCanoni;
    }

    //Restituisce il canone con il seriale corrispondente
    public function GetCanone($serial="")
    {
        if($serial=="" || !$this->IsValid()) return null;
        
        if($this->aCanoni == null) $this->LoadCanoni();

        foreach($this->aCanoni as $curCanone)
        {
            if($curCanone->GetProp("serial")==$serial) return $curCanone;
        }

        return null;
    }

    //Funzione di cancellazione
    protected function DeleteData($idData = 0, $user = null)
    {
        if(!$this->IsValid() || $this->IsReadOnly() || $idData == 0) return false;

        if($idData != $this->nId_Data && $idData != $this->nId_Data_Rev) return false;

        if(parent::DeleteData($idData,$user))
        {
            $db=new AA_Database();
            if(!$db->Query("DELETE FROM ".static::AA_DBTABLE_CANONI." WHERE id_patrimonio = '".addslashes($idData)."'"))
            {
                AA_Log::Log(__METHOD__." - ERRORE -".$db->GetErrorMessage(),100);
                return false;
            }
            else return true;
        }
        else return false;
    }

    //Funzione di clonazione dei dati
    protected function CloneData($idData = 0, $user = null)
    {
        if(!$this->IsValid() || $this->IsReadOnly()) return 0;
        
        $this->LoadCanoni($idData);

        $newIdData=parent::CloneData($idData,$user);

        if(sizeof($this->aCanoni)>0 && $newIdData > 0)
        {
            foreach($this->aCanoni as $curCanone)
            {
                $newCanone=$curCanone;
                $newCanone->SetProp('id_patrimonio',$newIdData);

                if(!$this->AddNewCanonePriv($newCanone,$user))
                {
                    AA_Log::Log(__METHOD__." - ERRORE - nell'aggiunta del canone: ".print_r($newCanone,true),100);
                }
            }
        }

        return $newIdData;
    }

    //Funzione di aggiunta di un canone (funzione privilegiata)
    protected function AddNewCanonePriv($newCanone=null,$user=null)
    {
        if(!$this->IsValid() || !($newCanone instanceof AA_Patrimonio_Canone) || $this->IsReadOnly()) 
        {
            AA_Log::Log(__METHOD__." - ERRORE - oggetto non valido".print_r($newCanone,true),100);
            return false;
        }
        if($newCanone->GetProp('id_patrimonio') == 0)
        {
            AA_Log::Log(__METHOD__." - ERRORE - id_patrimonio non valido".print_r($newCanone,true),100);
            return false;
        } 

        $db=new AA_Database();
        $query="INSERT INTO ".static::AA_DBTABLE_CANONI." SET ";
        $sep="";
        foreach($newCanone->GetProps() as $key=>$value)
        {
            if($key !="id")
            {
                $query.=$sep.$key."='".addslashes($value)."'";
                $sep=",";    
            }
        }

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - ERRORE - ".$db->GetErrorMessage(),100);
            return false;
        }

        $this->aCanoni=null;

        return true;
    }

    //Aggiunta di un canone
    public function AddNewCanone($newCanone=null,$user=null)
    {
        if(!$this->IsValid() || !($newCanone instanceof AA_Patrimonio_Canone) || $this->IsReadOnly()) return false;

        //Verifiche di coerenza
        if(strcmp($newCanone->GetProp("data_fine"),$newCanone->GetProp("data_inizio")) < 0)
        {
            AA_Log::Log(__METHOD__." - ERRORE - la data di fine deve essere maggiore di quella di inizio.",100);
            return false;
        }

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Aggiunta nuovo canone: ".$newCanone->GetProp("serial")))
        {
            return false;
        } 

        $newCanone->SetProp('id_patrimonio',$this->nId_Data);
        if($this->nId_Data_Rev > 0)
        {
            $newCanone->SetProp('id_patrimonio',$this->nId_Data_Rev);
        }

        return $this->AddNewCanonePriv($newCanone,$user);
    }

    //Aggiornamento di un canone
    public function UpdateCanone($canone=null, $user=null)
    {
        if(!$this->IsValid() || !($canone instanceof AA_Patrimonio_Canone) || $this->IsReadOnly()) return false;
        if($canone->GetProp('id') == 0 && $canone->GetProp('serial') == "") return false;

        //Verifiche di coerenza
        if(strcmp($canone->GetProp("data_fine"),$canone->GetProp("data_inizio")) < 0)
        {
            AA_Log::Log(__METHOD__." - ERRORE - la data di fine deve essere maggiore di quella di inizio.",100);
            return false;
        }

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Modifica canone: ".$canone->GetProp("serial"))) return false;

        $canone->SetProp('id_patrimonio', $this->nId_Data);
        if($this->nId_Data_Rev > 0)
        {
            $canone->SetProp('id_patrimonio',$this->nId_Data_Rev);
        }

        $query="UPDATE ".static::AA_DBTABLE_CANONI." SET ";
        $sep="";
        foreach($canone->GetProps() as $key=>$value)
        {
            if($key != "id" && $key !="id_patrimonio" && $key !="serial")
            {
                $query.=$sep.$key." = '".addslashes($value)."'";
                $sep=",";
            }
        }

        $query.=" WHERE id_patrimonio='".$canone->GetProp('id_patrimonio')."' AND serial = '".$canone->GetProp('serial')."' LIMIT 1";

        $db=new AA_Database();
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - ERRORE - ".$db->GetErrorMessage(),100);
            return false;
        }

        $this->aCanoni=null;

        return true;
    }

    //Elimina un canone
    public function DeleteCanone($canone=null,$user=null)
    {
        if(!$this->IsValid() || !($canone instanceof AA_Patrimonio_Canone) || $this->IsReadOnly()) return false;
        if($canone->GetProp('id') == 0 && $canone->GetProp('serial') == "") return false;

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true,"Eliminazione canone: ".$canone->GetProp("serial"))) return false;

        $canone->SetProp('id_patrimonio',$this->nId_Data);
        if($this->nId_Data_Rev > 0)
        {
            $canone->SetProp('id_patrimonio',$this->nId_Data_Rev);
        }

        $query="DELETE FROM ".static::AA_DBTABLE_CANONI." WHERE id_patrimonio='".$canone->GetProp('id_patrimonio')."' AND serial = '".$canone->GetProp('serial')."' LIMIT 1";

        $db=new AA_Database();
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - ERRORE - ".$db->GetErrorMessage(),100);
            return false;
        }

        $this->aCanoni=null;

        return true;
    }

    //Costruttore
    public function __construct($id=0, $user=null)
    {
        //data table
        $this->SetDbDataTable(static::AA_DBTABLE_DATA);

        $this->EnableRevision(false);

        //Db data binding
        $this->SetBind("Descrizione","descrizione");
        $this->SetBind("CodiceComune","codice_comune");
        $this->SetBind("SezioneCatasto","sezione_catasto");
        $this->SetBind("FoglioCatasto","foglio_catasto");
        $this->SetBind("ParticellaCatasto","particella_catasto");
        $this->SetBind("Indirizzo","indirizzo");
        $this->SetBind("RenditaCatasto","rendita_catasto");
        $this->SetBind("ConsistenzaCatasto","consistenza_catasto");
        $this->SetBind("ClasseCatasto","classe_catasto");
        $this->SetBind("Titolo","titolo");
        $this->SetBind("Cespite","cespite");
        $this->SetBind("Subalterno","subalterno");
        $this->SetBind("SubCespite","subcespite");
        $this->SetBind("Note","note");

        //Valori iniziali
        $this->SetProp("IdData",0);
        $this->SetProp("Titolo",0);

        //chiama il costruttore genitore
        parent::__construct($id,$user,false);

        //Carica i dati dell'oggetto
        if($this->bValid && $this->nId > 0)
        {
            if(!$this->LoadData($user))
            {
                $this->bValid=false;
            }
        }
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
        $params['class']="AA_Patrimonio";
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
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && !$user->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $perms = AA_Const::AA_PERMS_READ;
        }
        //---------------------------------------

        //Se l'utente ha il flag e può modificare la scheda allora può fare tutto
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && $user->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
        if(!$user->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            AA_Log::Log(__METHOD__." - L'utente corrente: ".$user->GetUserName()." non ha i permessi per inserire nuovi elementi.",100);
            return false;
        }

        //Verifica validità oggetto
        if(!($object instanceof AA_Patrimonio))
        {
            AA_Log::Log(__METHOD__." - Errore: oggetto non valido (".print_r($object,true).").",100);
            return false;
        }
        //----------------------------------------------

        return parent::AddNew($object,$user,$bSaveData);
    }

    //restituisce il titolo di possesso
    public function GetTitolo($bNumeric=false)
    {
        if($bNumeric) return $this->GetProp("Titolo");
        $titoloList=AA_Patrimonio_Const::GetTitoloList();
        return $titoloList[$this->GetProp("Titolo")];
    }

    //restituisce la sezione catastale
    public function GetSezione($bNumeric=false)
    {
        if($bNumeric) return $this->GetProp("SezioneCatasto");
        $titoloList=AA_Patrimonio_Const::GetSezioneList();
        return $titoloList[$this->GetProp("SezioneCatasto")];
    }
}

#Classe per il modulo art30 gestione del patrimonio
Class AA_PatrimonioModule extends AA_GenericModule
{
    const AA_UI_PREFIX="AA_Patrimonio";

    const AA_MODULE_OBJECTS_CLASS="AA_Patrimonio";

    //Task per la gestione dei dialoghi standard
    const AA_UI_TASK_PUBBLICATE_FILTER_DLG="GetPatrimonioPubblicateFilterDlg";
    const AA_UI_TASK_BOZZE_FILTER_DLG="GetPatrimonioBozzeFilterDlg";
    const AA_UI_TASK_REASSIGN_DLG="GetPatrimonioReassignDlg";
    const AA_UI_TASK_PUBLISH_DLG="GetPatrimonioPublishDlg";
    const AA_UI_TASK_TRASH_DLG="GetPatrimonioTrashDlg";
    const AA_UI_TASK_RESUME_DLG="GetPatrimonioResumeDlg";
    const AA_UI_TASK_DELETE_DLG="GetPatrimonioDeleteDlg";
    const AA_UI_TASK_ADDNEW_DLG="GetPatrimonioAddNewDlg";
    const AA_UI_TASK_ADDNEWMULTI_DLG="GetPatrimonioAddNewMultiDlg";
    const AA_UI_TASK_MODIFY_DLG="GetPatrimonioModifyDlg";
    //------------------------------------

    public function __construct($user=null,$bDefaultSections=true)
    {
        $this->SetId("AA_MODULE_PATRIMONIO");
        
        parent::__construct($user,$bDefaultSections);
        
        #--------------------------------Registrazione dei task-----------------------------
        $taskManager=$this->GetTaskManager();
        
        //Dialoghi di filtraggio
        $taskManager->RegisterTask("GetPatrimonioPubblicateFilterDlg");
        $taskManager->RegisterTask("GetPatrimonioBozzeFilterDlg");

        //patrimonio
        $taskManager->RegisterTask("GetPatrimonioModifyDlg");
        $taskManager->RegisterTask("GetPatrimonioAddNewDlg");
        $taskManager->RegisterTask("GetPatrimonioTrashDlg");
        $taskManager->RegisterTask("TrashPatrimonio");
        $taskManager->RegisterTask("GetPatrimonioDeleteDlg");
        $taskManager->RegisterTask("DeletePatrimonio");
        $taskManager->RegisterTask("GetPatrimonioResumeDlg");
        $taskManager->RegisterTask("ResumePatrimonio");
        $taskManager->RegisterTask("GetPatrimonioReassignDlg");
        $taskManager->RegisterTask("GetPatrimonioPublishDlg");
        $taskManager->RegisterTask("ReassignPatrimonio");
        $taskManager->RegisterTask("AddNewPatrimonio");
        $taskManager->RegisterTask("UpdatePatrimonio");
        $taskManager->RegisterTask("PublishPatrimonio");

        //caricamento multiplo
        $taskManager->RegisterTask("GetPatrimonioAddNewMultiDlg");
        $taskManager->RegisterTask("GetPatrimonioAddNewMultiPreviewCalc");
        $taskManager->RegisterTask("GetPatrimonioAddNewMultiPreviewDlg");

        //Canoni
        $taskManager->RegisterTask("GetPatrimonioAddNewCanoneDlg");
        $taskManager->RegisterTask("AddNewCanone");
        $taskManager->RegisterTask("GetPatrimonioModifyCanoneDlg");
        $taskManager->RegisterTask("UpdateCanone");
        $taskManager->RegisterTask("GetPatrimonioTrashCanoneDlg");
        $taskManager->RegisterTask("TrashCanone");
        #------------------------------------------------------------------------------------

        //Task Lista codici istat
        $taskManager->RegisterTask("GetPatrimonioListaCodiciIstat");

        //template dettaglio
        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL,array(
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_Generale_Tab", "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplatePatrimonioDettaglio_Generale_Tab"),
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_Canoni_Tab", "value"=>"Canoni","tooltip"=>"Canoni legati all'immobile","template"=>"TemplatePatrimonioDettaglio_Canoni_Tab")
        ));
    }
    
    //istanza
    protected static $oInstance=null;
    
    //Restituisce l'istanza corrente
    public static function GetInstance($user=null)
    {
        if(self::$oInstance==null)
        {
            self::$oInstance=new AA_PatrimonioModule($user);
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
        if($this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $bCanModify=true;
        }

        $content=$this->TemplateGenericSection_Pubblicate($params,$bCanModify);
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
        //Titolo di possesso
        if($params['Titolo'] > 0)
        {
            $params['where'][]=" AND ".AA_Patrimonio::AA_DBTABLE_DATA.".titolo = '".addslashes($params['Titolo'])."'";
        }
        return $params;
    }

     //Personalizza il template dei dati delle schede pubblicate per il modulo corrente
     protected function GetDataSectionPubblicate_CustomDataTemplate($data = array(),$object=null)
     {
         if($object instanceof AA_Patrimonio)
         {
             $data['pretitolo']=$object->GetTitolo();
             $data['tags']="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetSezione()."</span>";
         }
 
         return $data;
     }

    //Template sezione bozze (da specializzare)
    public function TemplateSection_Bozze($params=array())
    {
        $is_enabled= false;
       
        if($this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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

        $content=$this->TemplateGenericSection_Bozze($params,false);
        
        //solo per super user -beta testing-
        if($this->oUser->IsSuperUser())
        {
            $content->EnableAddNewMulti();
        }
        //-----------------------------------

        return $content->toObject();
    }
    
    //Restituisce i dati delle bozze
    public function GetDataSectionBozze_List($params=array())
    {
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            AA_Log::Log(__METHOD__." - ERRORE: l'utente corrente: ".$this->oUser->GetUserName()." non è abilitato alla visualizzazione delle bozze.",100);
            return array();
        }

        return $this->GetDataGenericSectionBozze_List($params,"GetDataSectionBozze_CustomFilter","GetDataSectionBozze_CustomDataTemplate");
    }

    //Personalizza il filtro delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomFilter($params = array())
    {
        //Titolo di possesso
        if($params['Titolo'] > 0)
        {
            $params['where'][]=" AND ".AA_Patrimonio::AA_DBTABLE_DATA.".titolo = '".addslashes($params['Titolo'])."'";
        }
        return $params;
    }

    //Personalizza il template dei dati delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomDataTemplate($data = array(),$object=null)
    {
        if($object instanceof AA_Patrimonio)
        {
            $data['pretitolo']=$object->GetTitolo();
            $data['tags']="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetSezione()."</span>";
        }

        return $data;
    }
    
    //Template organismo publish dlg
    public function Template_GetPatrimonioPublishDlg($params)
    {
        //lista elementi da ripristinare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Patrimonio($curId,$this->oUser);
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

                if(sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I seguenti ".sizeof($ids_final)." elementi verranno pubblicati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente elemento verrà pubblicato, vuoi procedere?")));

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
                $wnd->SetSaveTask('PublishPatrimonio');
            }
            else
            {
                $wnd=new AA_GenericWindowTemplate($id, "Avviso",$this->id);
                $wnd->AddView(new AA_JSON_Template_Template("",array("css"=>array("text-align"=>"center"),"template"=>"<p>L'utente corrente non ha i permessi per pubblicare gli elementi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }
            
            return $wnd;
        }
    }
    
    //Template organismo delete dlg
    public function Template_GetPatrimonioDeleteDlg($params)
    {
        return $this->Template_GetGenericObjectDeleteDlg($params,"DeletePatrimonio");
    }
        
    //Template dlg addnew patrimonio
    public function Template_GetPatrimonioAddNewDlg()
    {
        $id=$this->GetId()."_AddNew_Dlg";
        
        $form_data=array();
        
        //Struttura
        $form_data['SezioneCatasto']=0;
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi un nuovo immobile", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(720);
        $wnd->SetBottomPadding(24);
        $wnd->EnableValidation();
              
        //titolo di possesso
        $options=array(
            array("id"=>"1","value"=>"di proprietà"),
            array("id"=>"2","value"=>"posseduto"),
            array("id"=>"4","value"=>"detenuto")
        );
        $wnd->AddSelectField("Titolo","Titolo",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il titolo.","bottomLabel"=>"*Indicare il titolo di possesso","placeholder"=>"Scegli una voce...","options"=>$options,"value"=>"1"));
        
        //Nome
        $wnd->AddTextField("nome","Denominazione",array("required"=>true, "bottomLabel"=>"*Inserisci la denominazione dell'immobile.", "placeholder"=>"inserisci qui la denominazione dell'immobile"));
        
        //Descrizione
        $label="Descrizione";
        $wnd->AddTextareaField("Descrizione",$label,array("bottomLabel"=>"*Breve descrizione dell'immobile.", "required"=>true,"placeholder"=>"Inserisci qui la descrizione dell'immobile"));

        //Note
        $label="Note";
        $wnd->AddTextareaField("Note",$label,array("placeholder"=>"..."));
        
        //Dati catastali
        $catasto = new AA_FieldSet("AA_PATRIMONIO_CATASTO","Dati catastali");

        //sezione catasto
        $label="Sezione";
        $options=array(
            array("id"=>0,"value"=>"Catasto urbano"),
            array("id"=>1,"value"=>"Catasto terreni")
        );
        $catasto->AddRadioField("SezioneCatasto",$label,array("options"=>$options,"bottomLabel"=>"*Indicare la sezione in cui è accatastato l'immobile.", "value"=>0,"required"=>true));

        //codice comune
        $label="Cod. Comune";
        $catasto->AddTextField("CodiceComune",$label,array("width"=>308,"bottomLabel"=>"*Codice istat del comune.", "tooltip"=>"Inserisci il nome del comune per attivare l'autocompletamento.","required"=>true,"placeholder"=>"es. cagliari","suggest"=>array("template"=>"#codice#","url"=>$this->taskManagerUrl."?task=GetPatrimonioListaCodiciIstat")));

        //Cespite
        $label="Cespite";
        $catasto->AddTextField("Cespite",$label,array("width"=>308,"bottomLabel"=>"*Inserisci il numero del cespite.", "tooltip"=>"Inserisci il numero del cespite","required"=>true,"placeholder"=>"..."), false);

        //SubCespite
        $label="Sub cespite";
        $catasto->AddTextField("SubCespite",$label,array("tooltip"=>"Inserisci il numero del sub cespite se presente.","placeholder"=>"..."),false);
        
        //classe
        $label="Classe";
        $catasto->AddTextField("ClasseCatasto",$label,array("tooltip"=>"Inserisci qui la classe dell'immobile se presente.", "placeholder"=>"..."), false); 
        
        //foglio catasto
        $label="Foglio";
        $catasto->AddTextField("FoglioCatasto",$label,array("tooltip"=>"*Inserire il numero del foglio in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."));
        
        //particella catasto
        $label="Particella";
        $catasto->AddTextField("ParticellaCatasto",$label,array("tooltip"=>"*Inserire il numero della particella in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //subalterno
        $label="Subalterno";
        $catasto->AddTextField("Subalterno",$label,array("tooltip"=>"*Inserire il numero del sublaternose presente.", "placeholder"=>"..."),false);

        //rendita catasto
        $label="Rendita";
        $catasto->AddTextField("RenditaCatasto",$label,array("tooltip"=>"*Inserire la rendita catatastale dell'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //consistenza catasto
        $label="Consistenza";
        $catasto->AddTextField("ConsistenzaCatasto",$label,array("tooltip"=>"*Inserire la consistenza dell'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //Indirizzo
        $label="Indirizzo";
        $catasto->AddTextField("Indirizzo",$label,array("bottomLabel"=>"*Inserire l'indirizzo dell'immobile senza indicare il comune.", "required"=>true,"placeholder"=>"es.: viale Trento 69"));
        
        $wnd->AddGenericObject($catasto);

        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->SetSaveTask("AddNewPatrimonio");
        
        return $wnd;
    }

    //Template dlg addnew patrimonio
    public function Template_GetPatrimonioAddNewMultiDlg()
    {
        $id=$this->GetId()."_AddNewMulti_Dlg";
        
        $form_data=array();
        
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
        $descr.="<li>usare il carattere \"|\" (pipe) come separatore dei campi;</li>";
        $descr.="</ul>";
        $descr.="<p>Tramite il seguente <a href='docs/art30_multi.ods' target='_blank'>link</a> è possibile scaricare un foglio elettronico da utilizzarsi come base per la predisposizione del file csv.</p>";
        $descr.="<p>Per la generazione del file csv si consiglia l'utilizzo del software opensource <a href='https://www.libreoffice.org' target='_blank'>Libreoffice</a> in quanto consente di impostare il carattere di delimitazione dei campi e la codifica dei caratteri in fase di esportazione senza dover apportare modifiche al sistema.</p>";
        $descr.="<hr/>";

        $wnd->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","autoheight"=>true,"template"=>"<div style='margin-bottom: 1em;'>Questa funzionalità permette di caricare più immobili/cespiti tramite importazione da file csv.".$descr."</div>")));
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("height"=>30)));

        if($this->oUser->IsSuperUser())
        {
            //Struttura
            $wnd->AddStructField(array("targetForm"=>$wnd->GetFormId()), array("select"=>true),array("required"=>true,"bottomLabel"=>"*Imposta la struttura presso la quale verranno incardinate le nuove bozze."));
        }

        //$wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("height"=>30)));

        //csv
        $wnd->AddFileUploadField("PatrimonioMultiCSV","Scegli il file csv...", array("required"=>true,"validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti in formato csv (dimensione max: 2Mb).","accept"=>"application/csv"));

        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->enableRefreshOnSuccessfulSave(false);

        $wnd->SetApplyButtonName("Preview");

        $wnd->SetSaveTask("GetPatrimonioAddNewMultiPreviewCalc");
        
        return $wnd;
    }
    
    //Template dlg addnew patrimonio
    public function Template_GetPatrimonioAddNewMultiPreviewDlg()
    {
        $id=$this->GetId()."_AddNewMultiPreview_Dlg";
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Caricamento multiplo da file CSV - fase 2 di 3", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        //$wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(1280);
        $wnd->SetHeight(720);
        //$wnd->SetBottomPadding(36);
        //$wnd->EnableValidation();

        $columns=array(
            array("id"=>"descrizione","header"=>array("<div style='text-align: center'>Descrizione</div>",array("content"=>"selectFilter")),"width"=>250, "css"=>array("text-align"=>"left"),"sort"=>"text"),
            array("id"=>"titolo_desc","header"=>array("<div style='text-align: center'>Titolo</div>",array("content"=>"selectFilter")),"width"=>120, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"cespite","header"=>array("<div style='text-align: center'>Cespite</div>",array("content"=>"textFilter")),"width"=>150, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"subcespite","header"=>array("<div style='text-align: center'>Sub cespite</div>",array("content"=>"textFilter")),"width"=>120, "css"=>array("text-align"=>"right"),"sort"=>"text"),
            array("id"=>"codice_comune","header"=>array("<div style='text-align: center'>Cod. Comune</div>",array("content"=>"textFilter")),"width"=>120, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"indirizzo","header"=>array("<div style='text-align: center'>Indirizzo</div>",array("content"=>"textFilter")),"width"=>200, "css"=>array("text-align"=>"right"),"sort"=>"text"),
            array("id"=>"sezione_catasto_desc","header"=>array("<div style='text-align: center'>Sezione catasto</div>",array("content"=>"selectFilter")),"width"=>120, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"foglio_catasto","header"=>array("<div style='text-align: center'>Foglio</div>",array("content"=>"selectFilter")),"width"=>90, "css"=>array("text-align"=>"right"),"sort"=>"text"),
            array("id"=>"particella_catasto","header"=>array("<div style='text-align: center'>Particella</div>",array("content"=>"selectFilter")),"width"=>90, "css"=>array("text-align"=>"right"),"sort"=>"text"),
            array("id"=>"subalterno","header"=>array("<div style='text-align: center'>Subalterno</div>",array("content"=>"selectFilter")),"width"=>90, "css"=>array("text-align"=>"right"),"sort"=>"text"),
            array("id"=>"rendita_catasto","header"=>array("<div style='text-align: center'>Rendita</div>",array("content"=>"selectFilter")),"width"=>90, "css"=>array("text-align"=>"right"),"sort"=>"text"),
            array("id"=>"classe_catasto","header"=>array("<div style='text-align: center'>Classe</div>",array("content"=>"selectFilter")),"width"=>90, "css"=>array("text-align"=>"right"),"sort"=>"text"),
            array("id"=>"consistenza_catasto","header"=>array("<div style='text-align: center'>Consistenza</div>",array("content"=>"selectFilter")),"width"=>120, "css"=>array("text-align"=>"right"),"sort"=>"text"),
            array("id"=>"note","header"=>array("Note",array("content"=>"textFilter")),"width"=>350, "css"=>array("text-align"=>"left"),"sort"=>"text")
        );

        $data=AA_SessionVar::Get("PatrimonioMultiFromCSV_ParsedData")->GetValue();
        $struct=AA_SessionVar::Get("PatrimonioMultiFromCSV_Struct")->GetValue();
        //$wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("height"=>30)));

        if(!is_array($data))
        {
            AA_Log::Log(__METHOD__." - dati csv non validi: ".print_r($data,TRUE),100);
            $data=array();
        }

        //AA_Log::Log(__METHOD__." - dati csv: ".print_r($data,TRUE),100);
        $struct_desc=$struct->GetAssessorato();
        if($struct->GetDirezione(true) > 0) $struct_desc.="->".$struct->GetDirezione();
        if($struct->GetServizio(true) > 0) $struct_desc.="->".$struct->GetServizio();

        $desc="<p>Sono stati riconosciuti <b>".sizeof((array)$data)." cespiti</b> differenti.</p>";
        $desc.="<p>Verranno attestati alla struttura:<br/><b>".$struct_desc."</b></p>";
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
            "autowidth"=>true,
            "data"=>array_values($data)
        ));
        $scrollview->addRowToBody($table);

        $wnd->AddGenericObject($scrollview);

        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->SetApplyButtonName("Procedi");

        $wnd->SetSaveTask("GetPatrimonioAddNewMultiResultDlg");
        
        return $wnd;
    }

    //Template dlg modify immobile
    public function Template_GetPatrimonioModifyDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_TASK_MODIFY_DLG;
        if(!($object instanceof AA_Patrimonio)) return new AA_GenericWindowTemplate($id, "Modifica i dati generali", $this->id);

        $id.="_".$object->GetId();
        $form_data['id']=$object->GetID();
        $form_data['nome']=$object->GetName();

        foreach($object->GetDbBindings() as $prop=>$field)
        {
            $form_data[$prop]=$object->GetProp($prop);
        }
        
        $wnd=new AA_GenericFormDlg($id, "Modifica i dati generali", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(720);
        $wnd->SetBottomPadding(24);
        
        //titolo di possesso
        $options=array(
            array("id"=>"1","value"=>"di proprietà"),
            array("id"=>"2","value"=>"posseduto"),
            array("id"=>"4","value"=>"detenuto")
        );
        $wnd->AddSelectField("Titolo","Titolo",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il titolo.","bottomLabel"=>"*Indicare il titolo di possesso","placeholder"=>"Scegli una voce...","options"=>$options,"value"=>"1"));

        //Nome
        $wnd->AddTextField("nome","Denominazione",array("required"=>true, "bottomLabel"=>"*Inserisci la denominazione dell'immobile.", "placeholder"=>"inserisci qui la denominazione dell'immobile"));

        //Descrizione
        $label="Descrizione";
        $wnd->AddTextareaField("Descrizione",$label,array("bottomLabel"=>"*Breve descrizione dell'immobile.", "required"=>true,"placeholder"=>"Inserisci qui la descrizione dell'immobile"));

        //Descrizione
        $label="Note";
        $wnd->AddTextareaField("Note",$label,array("placeholder"=>"..."));

        //Dati catastali
        $catasto = new AA_FieldSet("AA_PATRIMONIO_CATASTO","Dati catastali");

        //sezione catasto
        $label="Sezione";
        $options=array(
            array("id"=>0,"value"=>"Catasto urbano"),
            array("id"=>1,"value"=>"Catasto terreni")
        );
        $catasto->AddRadioField("SezioneCatasto",$label,array("options"=>$options,"bottomLabel"=>"*Indicare la sezione in cui è accatastato l'immobile.", "required"=>true));

        //codice comune
        $label="Cod. Comune";
        $catasto->AddTextField("CodiceComune",$label,array("width"=>308,"bottomLabel"=>"*Codice istat del comune.", "tooltip"=>"Inserisci il nome del comune per attivare l'autocompletamento","required"=>true,"placeholder"=>"es. cagliari...","suggest"=>array("template"=>"#codice#","url"=>$this->taskManagerUrl."?task=GetPatrimonioListaCodiciIstat")));

        //Cespite
        $label="Cespite";
        $catasto->AddTextField("Cespite",$label,array("width"=>308, "bottomLabel"=>"*Inserisci il numero del cespite.", "required"=>true,"tooltip"=>"Inserisci il numero del cespite","placeholder"=>"..."), false); 

        //Sub cespite
        $label="Sub Cespite";
        $catasto->AddTextField("SubCespite",$label,array("placeholder"=>"...","tooltip"=>"Inserisci il numero del cespite se presente."), false); 
                
        //classe
        $label="Classe";
        $catasto->AddTextField("ClasseCatasto",$label,array("placeholder"=>"...","tooltip"=>"Inserisci la classe dell'immobile, se presente."), false); 

        //foglio catasto
        $label="Foglio";
        $catasto->AddTextField("FoglioCatasto",$label,array("tooltip"=>"*Inserire il numero del foglio in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."));

        //particella catasto
        $label="Particella";
        $catasto->AddTextField("ParticellaCatasto",$label,array("tooltip"=>"*Inserire il numero della particella in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //subalterno
        $label="Subalterno";
        $catasto->AddTextField("Subalterno",$label,array("tooltip"=>"Inserire il numero del subalterno, se presente.", "placeholder"=>"..."),false);
        
        //rendita catasto
        $label="Rendita";
        $catasto->AddTextField("RenditaCatasto",$label,array("tooltip"=>"*Inserire la rendita catatastale dell'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //consistenza catasto
        $label="Consistenza";
        $catasto->AddTextField("ConsistenzaCatasto",$label,array("tooltip"=>"*Inserire la consistenza dell'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //Indirizzo
        $label="Indirizzo";
        $catasto->AddTextField("Indirizzo",$label,array("bottomLabel"=>"*Inserire l'indirizzo dell'immobile senza indicare il comune.", "required"=>true,"placeholder"=>"es.: viale Trento 69"));

        $wnd->AddGenericObject($catasto);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdatePatrimonio");
        
        return $wnd;
    }
    
    //Template dlg addnew patrimonio
    public function Template_GetPatrimonioAddNewCanoneDlg($object=null)
    {
        if(!($object instanceof AA_Patrimonio) || !$object->isValid())
        {
            $wnd=new AA_GenericWindowTemplate($this->GetId()."_FakeDlg", "Aggiungi un nuovo canone",$this->GetId());
            $wnd->AddView(new AA_JSON_Template_Template($this->GetId()."_Fakecontent",array("template"=>"oggetto non valido")));

            return $wnd;
        }

        $id=$this->GetId()."_AddNewCanone_Dlg";
        
        $form_data=array("id"=>$object->GetId());
        $form_data["serial"]=AA_Utils::uniqid();
        $form_data["tipologia"]=1;
        $form_data["data_inizio"]=date("Y-m-d");
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi un nuovo canone ".$form_data["serial"], $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetBottomPadding(32);
        
        $wnd->SetWidth(920);
        $wnd->SetHeight(640);
        $wnd->EnableValidation();
              
        //tipologia
        $options=array();
        foreach(AA_Patrimonio_Const::GetTipoCanoneList() as $key=>$value)
        {
            $options[]=array("id"=>$key,"value"=>$value);
        }
        
        $wnd->AddRadioField("tipologia","Tipo",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il tipo di canone.","bottomLabel"=>"*Indicare il tipo di canone","placeholder"=>"Scegli una voce...","options"=>$options,"value"=>"1"));
        
        //data_inizio
        $wnd->AddDateField("data_inizio","Data inizio",array("required"=>true,"editable"=>true,"validateFunction"=>"IsIsoDate","bottomLabel"=>"*Inserire la data di decorrenza del canone", "placeholder"=>"inserisci qui la data di decorrenza."));

        //data_fine
        //data_fine
        $wnd->AddDateField("data_fine","Data fine",array("required"=>true,"editable"=>true,"validateFunction"=>"IsIsoDate","bottomLabel"=>"*Inserire la data di scadenza del canone o la dicitura 9999-12-31 se non è presente una data di scadenza.", "placeholder"=>"inserisci qui la data di scadenza."),false);

        //importo
        $label="Importo";
        $wnd->AddTextField("importo",$label,array("bottomLabel"=>"*Inserire l'importo, su base annua, in cifre.", "required"=>true,"validateFunction"=>"IsNumber","customInvalidMessage"=>"*Indicare esclusivamente numeri interi o decimali.","placeholder"=>"Inserisci qui l'importo."));

        //repertorio
        $label="Repertorio";
        $wnd->AddTextField("repertorio",$label,array("bottomLabel"=>"*Indica il numero di repertorio del contratto o la dicitura n.d. se non disponibile o non applicabile.", "required"=>true,"placeholder"=>"Inserisci qui il numero di repertorio"),false);

        //conduttore
        $label="Conduttore";
        $wnd->AddTextField("conduttore",$label,array("bottomLabel"=>"*Indica il conduttore.", "required"=>true, "placeholder"=>"Inserisci qui il conduttore"));

        //note
        $label="Note";
        $wnd->AddTextareaField("note",$label,array("bottomLabel"=>"*note.", "placeholder"=>"Inserisci qui le note"));
        
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        $wnd->SetSaveTask("AddNewCanone");
        
        return $wnd;
    }

    //Template dlg addnew patrimonio
    public function Template_GetPatrimonioModifyCanoneDlg($object=null,$canone=null)
    {
        if(!($object instanceof AA_Patrimonio) || !$object->isValid() || !($canone instanceof AA_Patrimonio_Canone))
        {
            $wnd=new AA_GenericWindowTemplate($this->GetId()."_FakeDlg", "Aggiungi un nuovo canone",$this->GetId());
            $wnd->AddView(new AA_JSON_Template_Template($this->GetId()."_Fakecontent",array("template"=>"oggetto o canone non valido")));

            return $wnd;
        }

        $id=$this->GetId()."_ModifyCanone_Dlg";
        
        foreach($canone->GetProps() as $key=>$value)
        {
            $form_data[$key]=$value;
        }
        $form_data['id']=$object->GetId();
        
        $wnd=new AA_GenericFormDlg($id, "Modifica canone ".$canone->GetProp("serial"), $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetBottomPadding(32);
        
        $wnd->SetWidth(920);
        $wnd->SetHeight(640);
        $wnd->EnableValidation();
              
        //tipologia
        $options=array();
        foreach(AA_Patrimonio_Const::GetTipoCanoneList() as $key=>$value)
        {
            $options[]=array("id"=>$key,"value"=>$value);
        }
        $wnd->AddRadioField("tipologia","Tipo",array("required"=>true,"validateFunction"=>"IsPositive","customInvalidMessage"=>"*Occorre selezionare il tipo di canone.","bottomLabel"=>"*Indicare il tipo di canone","placeholder"=>"Scegli una voce...","options"=>$options,"value"=>"1"));
        
        //data_inizio
        $wnd->AddDateField("data_inizio","Data inizio",array("required"=>true,"editable"=>true,"validateFunction"=>"IsIsoDate","bottomLabel"=>"*Inserire la data di decorrenza del canone", "placeholder"=>"inserisci qui la data di decorrenza."));

        //data_fine
        $wnd->AddDateField("data_fine","Data fine",array("required"=>true,"editable"=>true,"validateFunction"=>"IsIsoDate","bottomLabel"=>"*Inserire la data di scadenza del canone o la dicitura 9999-12-31 se non c'è una data di scadenza.", "placeholder"=>"inserisci qui la data di scadenza."),false);

        //importo
        $label="Importo";
        $wnd->AddTextField("importo",$label,array("bottomLabel"=>"*Inserire l'importo, su base annua, in cifre.", "required"=>true,"validateFunction"=>"IsNumber","customInvalidMessage"=>"*Indicare esclusivamente numeri interi o decimali.","placeholder"=>"Inserisci qui l'importo."));

        //repertorio
        $label="Repertorio";
        $wnd->AddTextField("repertorio",$label,array("bottomLabel"=>"*Indica il numero di repertorio del contratto o la dicitura n.d. se non disponibile o non applicabile.", "required"=>true,"placeholder"=>"Inserisci qui il numero di repertorio"),false);

        //conduttore
        $label="Conduttore";
        $wnd->AddTextField("conduttore",$label,array("bottomLabel"=>"*Indica il conduttore.", "required"=>true, "placeholder"=>"Inserisci qui il conduttore"));

        //note
        $label="Note";
        $wnd->AddTextareaField("note",$label,array("bottomLabel"=>"*note.", "placeholder"=>"Inserisci qui le note"));
        
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        $wnd->SetSaveTask("UpdateCanone");
        
        return $wnd;
    }

    //Template dlg addnew patrimonio
    public function Template_GetPatrimonioTrashCanoneDlg($object=null,$canone=null)
    {
        if(!($object instanceof AA_Patrimonio) || !$object->isValid() || !($canone instanceof AA_Patrimonio_Canone) || $object->IsReadOnly())
        {
            $wnd=new AA_GenericWindowTemplate($this->GetId()."_FakeDlg", "Elimina canone",$this->GetId());
            $wnd->AddView(new AA_JSON_Template_Template($this->GetId()."_Fakecontent",array("template"=>"oggetto o canone non valido")));

            return $wnd;
        }

        $id=$this->GetId()."_TrashCanone_Dlg";
        $forms_data['id']=$object->GetId();
        $forms_data['serial']=$canone->GetProp("serial");
        
        $wnd=new AA_GenericFormDlg($id, "Elimina canone ".$canone->GetProp("serial"), $this->id, $forms_data,$forms_data);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");

        $tabledata=array();
        $tabledata[]=array(
                "immobile"=>$object->GetName(),
                "canone"=>$canone->GetProp("serial")
        );
        $columns=array(
            array("id"=>"immobile","header"=>"immobile","fillspace"=>true),
            array("id"=>"canone","header"=>"id Canone","fillspace"=>true)
        );

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente canone verrà eliminato, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "scrollX"=>false,
            "select"=>false,
            "columns"=>$columns,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("TrashCanone");
    
        return $wnd;
    }

    //Template detail (da specializzare)
    public function TemplateSection_Detail($params)
    {
        //Gestione dei tab
        //$id=static::AA_UI_PREFIX."_Detail_Generale_Tab_".$params['id'];
        //$params['DetailOptionTab']=array(array("id"=>$id, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplatePatrimonioDettaglio_Generale_Tab"));
        
        return $this->TemplateGenericSection_Detail($params);
    }   
    
    //Template section detail, tab generale
    public function TemplatePatrimonioDettaglio_Generale_Tab($object=null)
    {
        $sectionTemplate=$this->GetSectionItemTemplate(static::AA_ID_SECTION_DETAIL);
        if(!is_array($sectionTemplate))
        {
            $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_Generale_Tab_".date("Y-m-d_h:i:s");
        }
        else
        {
            $id=$sectionTemplate[0]['id'];
        }

        if(!($object instanceof AA_Patrimonio)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
        //$id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_Generale_Tab_".$object->GetId();
        $rows_fixed_height=50;

        $layout=$this->TemplateGenericDettaglio_Header_Generale_Tab($object,$id);
        
        //Descrizione
        $value=$object->GetProp("Descrizione");
        if($value=="")$value="n.d.";
        $descr=new AA_JSON_Template_Template($id."_Descrizione",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Descrizione:","value"=>$value)
        ));

        //Titolo di possesso
        $value= $object->GetTitolo();
        if($value=="") $value="n.d.";
        $titolo=new AA_JSON_Template_Template($id."_Titolo",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Titolo di possesso:","value"=>"<span class='AA_DataView_Tag AA_Label AA_Label_Orange'>".$value."</span>")
        ));

        //Sezione catastale
        $value= $object->GetSezione();
        if($value=="") $value="n.d.";
        $sezione=new AA_JSON_Template_Template($id."_Sezione",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Sezione:","value"=>"<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$value."</span>")
        ));

        //Codice Comune
        $value= $object->GetProp("CodiceComune");
        if($value=="") $value="n.d.";
        $comune=new AA_JSON_Template_Template($id."_CodiceComune",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Codice Comune:","value"=>$value)
        ));

        //Classe
        $value= $object->GetProp("ClasseCatasto");
        if($value=="") $value="n.d.";
        $classe=new AA_JSON_Template_Template($id."_ClasseCatasto",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Classe:","value"=>$value)
        ));
        
        //foglio
        $value= $object->GetProp("FoglioCatasto");
        if($value=="") $value="n.d.";
        $foglio=new AA_JSON_Template_Template($id."_FoglioCatasto",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Foglio:","value"=>$value)
        ));
        
        //Particella
        $value= $object->GetProp("ParticellaCatasto");
        if($value=="") $value="n.d.";
        $particella=new AA_JSON_Template_Template($id."_ParticellaCatasto",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Particella:","value"=>$value)
        ));

        //Cespite
        $value= $object->GetProp("Cespite");
        if($value=="") $value="n.d.";
        $cespite=new AA_JSON_Template_Template($id."_Cespite",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Cespite:","value"=>$value)
        ));

        //Subcespite
        $value= $object->GetProp("SubCespite");
        if($value=="") $value="n.d.";
        $subcespite=new AA_JSON_Template_Template($id."_Subcespite",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Sub Cespite:","value"=>$value)
        ));

         //Subalterno
         $value= $object->GetProp("Subalterno");
         if($value=="") $value="n.d.";
         $subalterno=new AA_JSON_Template_Template($id."_Subalterno",array(
             "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
             "data"=>array("title"=>"Subalterno:","value"=>$value)
         ));

        //Rendita
        $value= $object->GetProp("RenditaCatasto");
        if($value=="") $value="n.d.";
        $rendita=new AA_JSON_Template_Template($id."_RenditaCatasto",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Rendita:","value"=>$value)
        ));

        //Consistenza
        $value= $object->GetProp("ConsistenzaCatasto");
        if($value=="") $value="n.d.";
        $consistenza=new AA_JSON_Template_Template($id."_Consistenza",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Consistenza:","value"=>$value)
        ));

        //Indirizzo
        $value= $object->GetProp("Indirizzo");
        $localit=AA_Patrimonio::GetComuneFromCodice($object->GetProp("CodiceComune"));
        if($value=="") 
        {
            $value=$localit;
        }
        else $value.=", ".$localit;
        $template="<span style='font-weight:700'>#title#</span><br><a title='Fai click per visualizzare l&#39;immobile o il terreno su Google maps' href='https://www.google.it/maps/place/".str_replace(" ","+",$value)."' target='_blank'><span>#value#</span><span class='mdi mdi-google-maps'></span></a>";
        
        $indirizzo=new AA_JSON_Template_Template($id."_Indirizzo",array(
            "template"=>$template,
            "data"=>array("title"=>"Indirizzo:","value"=>$value)
        ));
        
        //Note
        $value=$object->GetProp("Note");
        $note=new AA_JSON_Template_Template($id."_Note",array(
            "template"=>"<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data"=>array("title"=>"Note:","value"=>$value)
        ));

        //Prima riga
        $riga=new AA_JSON_Template_Layout($id."_FirstRow",array("height"=>$rows_fixed_height));
        $riga->AddCol($titolo);
        $layout->AddRow($riga);
        
        //seconda riga
        $riga=new AA_JSON_Template_Layout($id."_SecondRow",array("height"=>$rows_fixed_height));
        $riga->AddCol($descr);
        $layout->AddRow($riga);
        
        //terza riga
        $riga=new AA_JSON_Template_Layout($id."_ThirdRow",array("height"=>38,"type"=>"section","css"=>array("background"=>"#dadee0 !important;")));
        $riga->AddCol(new AA_JSON_Template_Generic($id."_Catasto_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Dati catastali</span>", "align"=>"center")));
        $layout->AddRow($riga);
        
        //Quarta riga
        $riga=new AA_JSON_Template_Layout($id."_FourRow",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($sezione);
        $riga->AddCol($comune);
        $riga->AddCol($cespite);
        $riga->AddCol($subcespite);
        $riga->AddCol($classe);
        $layout->AddRow($riga);

        //Quinta riga
        $riga=new AA_JSON_Template_Layout($id."_FiveRow",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($foglio);
        $riga->AddCol($particella);
        $riga->AddCol($subalterno);
        $riga->AddCol($rendita);
        $riga->AddCol($consistenza);
        $layout->AddRow($riga);
        
        //layout ultima riga
        $last_row=new AA_JSON_Template_Layout($id."_LastRow");
        $last_row->addCol($indirizzo);
        $last_row->addCol($note);
        $layout->AddRow($last_row);
        
        return $layout;
    }

    //Template section detail, tab canoni
    public function TemplatePatrimonioDettaglio_Canoni_Tab($object=null)
    {
        $sectionTemplate=$this->GetSectionItemTemplate(static::AA_ID_SECTION_DETAIL);
        if(!is_array($sectionTemplate))
        {
            $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_Canoni_Tab_".date("Y-m-d_h:i:s");
        }
        else
        {
            $id=$sectionTemplate[1]['id'];
        }

        if(!($object instanceof AA_Patrimonio)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
        //$id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_Canoni_Tab_".$object->GetId();
        $rows_fixed_height=50;

        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //Pulsante di modifica
        $canModify=false;
        if(($object->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;
        if($canModify)
        {            
            $modify_btn=new AA_JSON_Template_Generic($id."_AddNew_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi un nuovo canone",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetPatrimonioAddNewCanoneDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        
        $layout->addRow($toolbar);        
        $columns=array(
            array("id"=>"tipo","header"=>array("<div style='text-align: center'>Tipologia</div>",array("content"=>"selectFilter")),"width"=>150, "css"=>array("text-align"=>"left"),"sort"=>"text"),
            array("id"=>"serial","header"=>array("<div style='text-align: center'>Id</div>",array("content"=>"textFilter")),"width"=>150, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"data_inizio","header"=>array("<div style='text-align: center'>Data inizio</div>",array("content"=>"textFilter")),"width"=>120, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"data_fine","header"=>array("<div style='text-align: center'>Data fine</div>",array("content"=>"textFilter")),"width"=>120, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"importo","header"=>array("<div style='text-align: center'>Importo</div>",array("content"=>"textFilter")),"width"=>120, "css"=>array("text-align"=>"right"),"sort"=>"int"),
            array("id"=>"repertorio","header"=>array("<div style='text-align: center'>Repertorio n.</div>",array("content"=>"selectFilter")),"width"=>120, "css"=>array("text-align"=>"right"),"sort"=>"text"),
            array("id"=>"conduttore","header"=>array("<div style='text-align: center'>Conduttore</div>",array("content"=>"selectFilter")),"width"=>340, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"note","header"=>array("Note",array("content"=>"textFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text")
        );

        if(!$object->IsReadOnly())
        {
            $columns[]=array("id"=>"ops","header"=>"<div style='text-align: center'>Operazioni</div>","width"=>100, "css"=>array("text-align"=>"center"));
        }

        $data=array();
        $tipoCanone=AA_Patrimonio_Const::GetTipoCanoneList();
        $canoni=$object->GetCanoni();
        foreach($canoni as $curCanone)
        {
            $data[]=$curCanone->GetProps();
            $index=sizeof($data)-1;
            $data[$index]['tipo']=$tipoCanone[$curCanone->GetProp("tipologia")];
            if(!$object->IsReadOnly())
            {
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetPatrimonioTrashCanoneDlg", params: [{id: "'.$object->GetId().'"},{serial:"'.$curCanone->GetProp("serial").'"}]},"'.$this->id.'")';
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetPatrimonioModifyCanoneDlg", params: [{id: "'.$object->GetId().'"},{serial:"'.$curCanone->GetProp("serial").'"}]},"'.$this->id.'")';
                $data[$index]['ops']="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
            }
        }

        if(sizeof($canoni) > 0)
        {
            $table=new AA_JSON_Template_Generic($id."_Canoni", array(
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
            $layout->addRow(new AA_JSON_Template_Template($id."_vuoto",array("template"=>"<div style='text-align: center'>Non sono presenti canoni.</div>")));
        }
       
        $layout->addRow(new AA_JSON_Template_Generic($id."_spacer"));

        return $layout;
    }
     
    //Task Update Patrimonio
    public function Task_UpdatePatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi di modifica dell'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        return $this->Task_GenericUpdateObject($task,$_REQUEST,true);   
    }
    
    //Task trash Patrimonio
    public function Task_TrashPatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $task->SetError("L'utente corrente non ha i permessi per cestinare l'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per cestinare l'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        return $this->Task_GenericTrashObject($task,$_REQUEST);
    }
    
    //Task resume Patrimonio
    public function Task_ResumePatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericResumeObject($task,$_REQUEST);
    }
    
    //Task publish Patrimonio
    public function Task_PublishPatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericPublishObject($task,$_REQUEST);
    }
    
    //Task reassign Patrimonio
    public function Task_ReassignPatrimonio($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        return $this->Task_GenericReassignObject($task,$_REQUEST);
    }
    
    //Task delete Patrimonio
    public function Task_DeletePatrimonio($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        return $this->Task_GenericDeleteObject($task,$_REQUEST);
    }
    
    //Task Aggiungi patrimonio
    public function Task_AddNewPatrimonio($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per aggiungere nuovi elementi</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        return $this->Task_GenericAddNew($task,$_REQUEST);
    }
    
    //Task Aggiungi un canone
    public function Task_AddNewCanone($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per aggiungere nuovi elementi</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        $object= new AA_Patrimonio($_REQUEST['id'],$this->oUser);
        if(!$object->isValid() || $object->IsReadOnly() || $_REQUEST['serial']=="")
        {
            $task->SetError("Oggetto non valido o permessi insufficienti.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Oggetto non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $newCanone=new AA_Patrimonio_Canone($_REQUEST);

        if(!$object->AddNewCanone($newCanone,$this->oUser))
        {
            $task->SetError("Errore durante l'aggiunta del nuovo canone.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        else
        {
            $sTaskLog="<status id='status' id_Rec='".$object->GetId()."'>0</status><content id='content'>";
            $sTaskLog.= "Canone aggiunto con successo";
            $sTaskLog.="</content>";
            
            $task->SetLog($sTaskLog);
            
            return true;
        }
    }

    //Task aggio0rna un canone
    public function Task_UpdateCanone($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $task->SetError("L'utente corrente non ha i permessi per modifcare elementi di questo tipo.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per modifcare elementi di questo tipo.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        $object= new AA_Patrimonio($_REQUEST['id'],$this->oUser);
        if(!$object->isValid() || $object->IsReadOnly() || $_REQUEST['serial']=="")
        {
            $task->SetError("Oggetto non valido o permessi insufficienti.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Oggetto non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $canone=$object->GetCanone($_REQUEST['serial']);
        if(!($canone instanceof AA_Patrimonio_Canone))
        {
            $task->SetError("Canone non valido.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Canone non valido.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        if(!$object->UpdateCanone(new AA_Patrimonio_Canone($_REQUEST),$this->oUser))
        {
            $task->SetError("Errore durante l'aggiornamento del canone: ".$canone->GetProp("serial"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        else
        {
            $sTaskLog="<status id='status' id_Rec='".$object->GetId()."'>0</status><content id='content'>";
            $sTaskLog.= "Canone aggiunto con successo";
            $sTaskLog.="</content>";
            
            $task->SetLog($sTaskLog);
            
            return true;
        }
    }

    //Task elimina un canone
    public function Task_TrashCanone($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $task->SetError("L'utente corrente non ha i permessi per modifcare elementi di questo tipo.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per modifcare elementi di questo tipo.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        $object= new AA_Patrimonio($_REQUEST['id'],$this->oUser);
        if(!$object->isValid() || $object->IsReadOnly() || $_REQUEST['serial']=="")
        {
            $task->SetError("Oggetto non valido o permessi insufficienti.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Oggetto non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $canone=$object->GetCanone($_REQUEST['serial']);
        if(!($canone instanceof AA_Patrimonio_Canone))
        {
            $task->SetError("Canone non valido.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Canone non valido.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        if(!$object->DeleteCanone(new AA_Patrimonio_Canone($_REQUEST),$this->oUser))
        {
            $task->SetError("Errore durante la rimozione del canone: ".$canone->GetProp("serial"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>".AA_Log::$lastErrorLog."</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        else
        {
            $sTaskLog="<status id='status' id_Rec='".$object->GetId()."'>0</status><content id='content'>";
            $sTaskLog.= "Canone rimosso con successo";
            $sTaskLog.="</content>";
            
            $task->SetLog($sTaskLog);
            
            return true;
        }
    }


    //Task modifica organismo
    public function Task_GetPatrimonioModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non può modifcrae l'elemento.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $object= new AA_Patrimonio($_REQUEST['id'],$this->oUser);
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
            $sTaskLog.= $this->Template_GetPatrimonioModifyDlg($object)->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task resume organismo
    public function Task_GetPatrimonioResumeDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
            $sTaskLog.= $this->Template_GetGenericResumeObjectDlg($_REQUEST,"ResumePatrimonio")->toBase64();
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
        
        return true;
    }
    
    //Task publish organismo
    public function Task_GetPatrimonioPublishDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
            $sTaskLog.= $this->Template_GetGenericPublishObjectDlg($_REQUEST,"PublishPatrimonio")->toBase64();
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
    
    //Task Riassegna patrimonio
    public function Task_GetPatrimonioReassignDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
         if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
            $sTaskLog.= $this->Template_GetGenericReassignObjectDlg($_REQUEST,"ReassignPatrimonio")->toBase64();
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
    public function Task_GetPatrimonioTrashDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
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
            $sTaskLog.= $this->Template_GetGenericObjectTrashDlg($_REQUEST,"TrashPatrimonio")->toBase64();
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

        return true;
    }
       
    //Task dialogo elimina patrimonio
    public function Task_GetPatrimonioDeleteDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) && !$this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per cestinare/eliminare elementi.</error>";
        }
        if($_REQUEST['ids']!="")
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetPatrimonioDeleteDlg($_REQUEST)->toBase64();
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
    
    //Task aggiunta patrimonio
    public function Task_GetPatrimonioAddNewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per istanziare nuovi elementi.</error>";
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetPatrimonioAddNewDlg()->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task aggiunta patrimonio
    public function Task_GetPatrimonioAddNewMultiDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per istanziare nuovi elementi.</error>";
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetPatrimonioAddNewMultiDlg()->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task aggiunta patrimonio da csv, passo 2 di 3
    public function Task_GetPatrimonioAddNewMultiPreviewCalc($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per istanziare nuovi elementi.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $csvFile=AA_SessionFileUpload::Get("PatrimonioMultiCSV");
        if(!$csvFile->IsValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>File csv non trovato.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        //Struttura di assegnazione
        $struct=AA_Struct::GetStruct($_REQUEST['id_assessorato'],$_REQUEST['id_direzione'],$_REQUEST['id_servizio']);
        if($_REQUEST['id_assessorato'] == "" || !$this->oUser->isSuperUser() || $_REQUEST['id_assessorato'] == 0)
        {
            $struct=$this->oUser->GetStruct();
        }

        AA_SessionVar::Set("PatrimonioMultiFromCSV_Struct",$struct,false);

        $csv=$csvFile->GetValue();
        if(!is_file($csv["tmp_name"]))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>File csv non valido.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $csvRows=explode("\n",str_replace("\r","",file_get_contents($csv["tmp_name"])));
        //Elimina il file temporaneo
        if(is_file($csv["tmp_name"]))
        {
            unlink($csv["tmp_name"]);
        }

        $tipo_catasto=AA_Patrimonio_Const::GetSezioneList();
        $titolo_list=AA_Patrimonio_Const::GetTitoloList();

        //Parsing della posizione dei campi
        $fieldPos=array(
            "descrizione"=>-1,
            "codice_comune"=>-1,
            "sezione_catasto"=>-1,
            "foglio_catasto"=>-1,
            "particella_catasto"=>-1,
            "indirizzo"=>-1,
            "rendita_catasto"=>-1,
            "consistenza_catasto"=>-1,
            "classe_catasto"=>-1,
            "titolo"=>-1,
            "cespite"=>-1,
            "subalterno"=>-1,
            "subcespite"=>-1,
            "note"=>-1
        );
        
        $recognizedFields=0;
        foreach(explode("|",$csvRows[0]) as $pos=>$curFieldName)
        {
            if($fieldPos[trim($curFieldName)] == -1)
            {
                $fieldPos[trim($curFieldName)] = $pos;
                $recognizedFields++;
            }
        }
        //----------------------------------------

        if($fieldPos['cespite'] ==-1 || $fieldPos['codice_comune'] ==-1 || $fieldPos['titolo'] ==-1 || $fieldPos['sezione_catasto'] ==-1)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Non sono stati trovati tutti i campi relativi a: cespite,codice_comune,titolo,sezione_catasto. Verificare che il file csv sia strutturato correttamente e riprovare.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        //parsing dei dati
        $data=array();
        $curRowNum=0;
        foreach($csvRows as $curCsvRow)
        {
            //salta la prima riga
            if($curRowNum > 0 && $curCsvRow !="")
            {
                $csvValues=explode("|",$curCsvRow);
                if(sizeof($csvValues) == $recognizedFields)
                {
                    $curDataValues=array();

                    $cespite=$csvValues[$fieldPos["cespite"]];
                    foreach($fieldPos as $fieldName=>$pos)
                    {
                        if($pos>=0)
                        {
                            $curDataValues[$fieldName]=$csvValues[$pos];
                            if($fieldName=="titolo")
                            {
                                $curDataValues["titolo_desc"]=$titolo_list[$csvValues[$pos]];
                            }
                            if($fieldName=="sezione_catasto")
                            {
                                $curDataValues["sezione_catasto_desc"]=$tipo_catasto[$csvValues[$pos]];
                            }
                        }
                    }
                    if(!is_array($data[$cespite]))
                    {
                        $data[$cespite]=$curDataValues;
                    }
                    else
                    {
                        //merge sub cespite
                        if($data[$cespite]['subcespite'] != "" && $curDataValues['subcespite'] > 0) $data[$cespite]['subcespite'].=",".$curDataValues['subcespite'];
                        if($data[$cespite]['subcespite'] == "" && $curDataValues['subcespite'] > 0) $data[$cespite]['subcespite'] = $curDataValues['subcespite'];
    
                        //merge foglio
                        if($data[$cespite]['foglio_catasto'] != "" && $curDataValues['foglio_catasto'] > 0 && strpos($data[$cespite]['foglio_catasto'],$curDataValues['foglio_catasto']) === false) $data[$cespite]['foglio_catasto'].=",".$curDataValues['foglio_catasto'];
                        if($data[$cespite]['foglio_catasto'] == "" && $curDataValues['foglio_catasto'] > 0) $data[$cespite]['foglio_catasto'] = $curDataValues['foglio_catasto'];
    
                        //merge particella
                        if($data[$cespite]['particella_catasto'] != "" && $curDataValues['particella_catasto'] > 0 && strpos($data[$cespite]['particella_catasto'],$curDataValues['particella_catasto']) === false) $data[$cespite]['particella_catasto'].=",".$curDataValues['particella_catasto'];
                        if($data[$cespite]['particella_catasto'] == "" && $curDataValues['particella_catasto'] > 0) $data[$cespite]['particella_catasto'] = $curDataValues['particella_catasto'];
    
                        //merge subalterno
                        if($data[$cespite]['subalterno'] != "" && $curDataValues['subalterno'] > 0 && strpos($data[$cespite]['subalterno'],$curDataValues['subalterno']) === false) $data[$cespite]['subalterno'].=",".$curDataValues['subalterno'];
                        if($data[$cespite]['subalterno'] == "" && $curDataValues['subalterno'] > 0) $data[$cespite]['subalterno'] = $curDataValues['subalterno'];
    
                    }
                }
            }
            $curRowNum++;
        }

        AA_SessionVar::Set("PatrimonioMultiFromCSV_ParsedData",$data,false);
        
        $sTaskLog="<status id='status' action='dlg' action_params='".json_encode(array("task"=>"GetPatrimonioAddNewMultiPreviewDlg"))."'>0</status><content id='content'>";
        $sTaskLog.="csv elaborato.</content>";
        $task->SetLog($sTaskLog);
                
        return true;
    }

    //Task aggiunta patrimonio da csv, passo 2 di 3
    public function Task_GetPatrimonioAddNewMultiPreviewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per istanziare nuovi elementi.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $sTaskLog.= $this->Template_GetPatrimonioAddNewMultiPreviewDlg()->toBase64();
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }
    
    //Task aggiunta organismo
    public function Task_GetPatrimonioListaCodiciIstat($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        $filter=$_REQUEST["filter"];

        $db=new AA_Database();
        $query="SELECT codice,comune FROM ".AA_Patrimonio::AA_DBTABLE_CODICI_ISTAT;
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
            $result[]=array("id"=>$count,"codice"=>$curRow['codice'],"value"=>$curRow['comune']." (".$curRow['codice'].")");
            $count++;
        }

        die(json_encode($result));
    }

    //Task aggiunta Canone
    public function Task_GetPatrimonioAddNewCanoneDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per istanziare nuovi elementi.</error>";
        }
        
        $object=new AA_Patrimonio($_REQUEST['id'],$this->oUser);
        //AA_Log::Log(__METHOD__." - object: ".print_r($object,TRUE),100);

        if(!$object->IsValid() || $object->IsReadOnly() || $_REQUEST['id']=="")
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per istanziare nuovi elementi.</error>";
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetPatrimonioAddNewCanoneDlg($object)->toBase64();
            $sTaskLog.="</content>";
        }
        
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task modifica Canone
    public function Task_GetPatrimonioModifyCanoneDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per modificare elementi di questo tipo.</error>";
        }
        
        $object=new AA_Patrimonio($_REQUEST['id'],$this->oUser);
        //AA_Log::Log(__METHOD__." - object: ".print_r($object,TRUE),100);

        $canone=$object->GetCanone($_REQUEST['serial']);

        if(!$object->IsValid() || $object->IsReadOnly() || $_REQUEST['id']=="" || $canone == null)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per modificare l'oggetto: ".$object->GetName()." o canone non valido.</error>";
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetPatrimonioModifyCanoneDlg($object,$canone)->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task elimina Canone
    public function Task_GetPatrimonioTrashCanoneDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per modificare elementi di questo tipo.</error>";
        }
        
        $object=new AA_Patrimonio($_REQUEST['id'],$this->oUser);
        AA_Log::Log(__METHOD__." - object: ".print_r($object,TRUE),100);

        $canone=$object->GetCanone($_REQUEST['serial']);

        if(!$object->IsValid() || $object->IsReadOnly() || $_REQUEST['id']=="" || $canone == null)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per modificare l'oggetto: ".$object->GetName()." o canone non valido.</error>";
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetPatrimonioTrashCanoneDlg($object,$canone)->toBase64();
            $sTaskLog.="</content>";
        }
        
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task filter dlg
    public function Task_GetPatrimonioPubblicateFilterDlg($task)
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
    public function Task_GetPatrimonioBozzeFilterDlg($task)
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
        if(!$this->oUser->HasFlag(AA_Patrimonio_Const::AA_USER_FLAG_PATRIMONIO))
        {
            $_REQUEST['section']=static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX;
        }
        
        return $this->Task_GetGenericNavbarContent($task,$_REQUEST);
    }
    
    //Template filtro di ricerca
    public function TemplatePubblicateFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate'],"revisionate"=>$params['revisionate'], "Titolo"=>$params['Titolo']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['revisionate']=="") $formData['revisionate']=0;
        if($params['Titolo']=="") $formData['Titolo']=0;

        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","Titolo"=>0,"nome"=>"","cestinate"=>0,"revisionate"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Pubblicate_Filter", "Parametri di ricerca per le schede pubblicate",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));

        //Revisionate
        $dlg->AddSwitchBoxField("revisionate","Revisionate",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede revisionate."));
        
        //Denominazione
        $dlg->AddTextField("nome","Denominazione",array("bottomLabel"=>"*Filtra in base alla denominazione dell'immobile.", "placeholder"=>"Denominazione..."));
        
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //titolo di possesso
        $options=array(
            array("id"=>"0","value"=>"Qualunque"),
            array("id"=>"1","value"=>"di proprietà"),
            array("id"=>"2","value"=>"posseduto"),
            array("id"=>"4","value"=>"detenuto")
        );
        $dlg->AddSelectField("Titolo","Titolo",array("bottomLabel"=>"*Indicare il titolo di possesso","options"=>$options));
        
        $dlg->SetApplyButtonName("Filtra");
        
        return $dlg->GetObject();
    }
    
    //Template filtro di ricerca
    public function TemplateBozzeFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"Titolo"=>$params['Titolo'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['Titolo']=="") $formData['Titolo']=0;
        
        //Valori reset
        $resetData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","Titolo"=>0,"nome"=>"","cestinate"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Bozze_Filter", "Parametri di ricerca per le bozze",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
        
        //Denominazione
        $dlg->AddTextField("nome","Denominazione",array("bottomLabel"=>"*Filtra in base alla denominazione dell'immobile.", "placeholder"=>"Denominazione..."));
        
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //titolo di possesso
        $options=array(
            array("id"=>"0","value"=>"Qualunque"),
            array("id"=>"1","value"=>"di proprietà"),
            array("id"=>"2","value"=>"posseduto"),
            array("id"=>"4","value"=>"detenuto")
        );
        $dlg->AddSelectField("Titolo","Titolo",array("bottomLabel"=>"*Indicare il titolo di possesso","options"=>$options));
        
        $dlg->SetApplyButtonName("Filtra");

        return $dlg->GetObject();
    }
    
    //Funzione di esportazione in pdf (da specializzare)
    public function Template_PdfExport($ids=array())
    {
        return $this->Template_GenericPdfExport($ids,true,"Pubblicazione ai sensi dell'art.30 del d.lgs. 33/2013","Template_PatrimonioPdfExport");
    }

    //Template pdf export single
    public function Template_PatrimonioPdfExport($id="", $parent=null,$object=null,$user=null)
    {
        if(!($object instanceof AA_Patrimonio))
        {
            return "";
        }
        
        if($id=="") $id="Template_PatrimonioPdfExport_".$object->GetId();

        return new AA_PatrimonioPublicReportTemplateView($id,$parent,$object,$user);
    }
}

#Classe template per la gestione del report pdf dell'oggetto
Class AA_PatrimonioPublicReportTemplateView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_PatrimonioPublicReportTemplateView",$parent=null,$object=null)
    {
        if(!($object instanceof AA_Patrimonio))
        {
            AA_Log::Log(__METHOD__." - oggetto non valido.", 100,false,true);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$object);
        
        $this->SetStyle("width: 99%; display:flex; flex-direction: column; align-items: center;");

        #Parte generale---------------------------------
        $generale=new AA_XML_Div_Element("AA_PatrimonioPublicReportTemplateView-generale",$this);
        $generale->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; width: 100%");

        #Denominazione----------------------------------
        $denominazione=new AA_XML_Div_Element("generale-tab-denominazione",$generale);
        $denominazione->SetStyle('width:100%; border-bottom: 1px solid  gray; margin-bottom: 1em; margin-top: .2em; font-size: 20px; font-weight: bold; padding: .1em');
        $denominazione->SetText($object->GetName()."<div style='font-size: x-small; font-weight: normal; margin-top: .1em;'>".$object->GetTitolo()."</div>");
        #-----------------------------------------------

        //left panel-------
        $left_panel= new AA_XML_Div_Element("generale-tab-left-panel",$generale);
        $left_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; align-self: start; width:70%; flex: 1; align-self: stretch; border: 1px solid #d7dbdd;");
        
        //Etichetta descrizione
        $descr= new AA_XML_Div_Element("generale-tab-left-panel-descrizione",$left_panel);
        $descr->SetStyle("width:100%; margin-bottom: .8em; text-align: center; background: #d7dbdd; border-bottom: 1px solid #d7dbdd;");
        $descr->SetText('<span style="font-weight:bold">Descrizione</span>');

        //Descrizione
        $val=$object->GetProp("Descrizione");
        if($val=="") $val="n.d.";
        $descr=new AA_XML_Div_Element("descr",$left_panel);
        $descr->SetStyle("display: flex; width: 100%; margin-bottom: .8em; text-align: left; align-self: stretch; flex: 1; padding: .3em");
        $descr->SetText($val);

        //Etichetta indirizzo
        $descr= new AA_XML_Div_Element("generale-tab-left-panel-indirizzo",$left_panel);
        $descr->SetStyle("width:100%; margin-bottom: .8em; text-align: center; background: #d7dbdd; border-bottom: 1px solid #d7dbdd;");
        $descr->SetText('<span style="font-weight:bold">Indirizzo</span>');

        //Indirizzo
        $val=$object->GetProp("Indirizzo");
        if($val=="") $val="n.d.";
        $descr=new AA_XML_Div_Element("indirizzo",$left_panel);
        $descr->SetStyle("width: 100%; margin-bottom: .8em; text-align: left; padding: .3em;");
        $descr->SetText($val);
        #-------------------
        
        //right panel ------
        $right_panel= new AA_XML_Div_Element("generale-tab-right-panel",$generale);
        $right_panel->SetStyle("display:flex; flex-direction: column; justify-content: space-between; align-items: left; width:29%; border: 1px solid  #d7dbdd");
        
        $databoxStyle="display: flex; justify-content: space-between; width: 98%; margin-bottom: .8em; border-bottom: 1px solid #d7dbdd; padding: 1%;";
        $databoxStyleLastRow="display: flex; justify-content: space-between; width: 98%; margin-bottom: .8em; padding: 1%;";

        //Etichetta dati catastali
        $dati_catastali= new AA_XML_Div_Element("generale-tab-right-panel-dati_catastali",$right_panel);
        $dati_catastali->SetStyle("width: 100%; margin-bottom: .8em; text-align: center; border-bottom: 1px solid  #d7dbdd; background: #d7dbdd");
        $dati_catastali->SetText('<span style="font-weight:bold">Dati catastali</span>');

        //Sezione
        $val=$object->GetSezione();
        if($val=="") $val="n.d.";
        $sezione = new AA_XML_Div_Element("generale-tab-right-panel-data_inizio",$right_panel);
        $sezione->SetStyle($databoxStyle);
        $sezione->SetText('<span style="font-weight:bold;">Sezione:</span><span>'.$val.'</span>');

        //codice comune
        $val=$object->GetProp("CodiceComune");
        if($val=="") $val="n.d.";
        $codcomune = new AA_XML_Div_Element("generale-tab-right-panel-cod_comune",$right_panel);
        $codcomune->SetStyle($databoxStyle);
        $codcomune->SetText('<span style="font-weight:bold">Codice Comune:</span><span>'.$val.'</span>');

        //classe
        $val=$object->GetProp("ClasseCatasto");
        if($val=="") $val="n.d.";
        $classe = new AA_XML_Div_Element("generale-tab-right-panel-classe",$right_panel);
        $classe->SetStyle($databoxStyle);
        $classe->SetText('<span style="font-weight:bold">Classe:</span>'.$val.'</span>');

        //foglio
        $val=$object->GetProp("FoglioCatasto");
        if($val=="") $val="n.d.";
        $foglio = new AA_XML_Div_Element("generale-tab-right-panel-foglio",$right_panel);
        $foglio->SetStyle($databoxStyle);
        $foglio->SetText('<span style="font-weight:bold">Foglio:</span>'.$val.'</span>');

        //particella
        $val=$object->GetProp("ParticellaCatasto");
        if($val=="") $val="n.d.";
        $particella = new AA_XML_Div_Element("generale-tab-right-panel-particella",$right_panel);
        $particella->SetStyle($databoxStyle);
        $particella->SetText('<span style="font-weight:bold">Particella:</span><span>'.$val.'</span>');

        //codice comune
        $val=$object->GetProp("RenditaCatasto");
        if($val=="") $val="n.d.";
        $rendita = new AA_XML_Div_Element("generale-tab-right-panel-rendita",$right_panel);
        $rendita->SetStyle($databoxStyle);
        $rendita->SetText('<span style="font-weight:bold">Rendita:</span><span>'.$val.'</span>');

        //consistenza
        $val=$object->GetProp("ConsistenzaCatasto");
        if($val=="") $val="n.d.";
        $consistenza = new AA_XML_Div_Element("generale-tab-right-panel-consistenza",$right_panel);
        $consistenza->SetStyle($databoxStyleLastRow);
        $consistenza->SetText('<span style="font-weight:bold">Consistenza:</span><span>'.$val.'</span>');
        #-------------------

        //Dati sui canoni
        $canoni=new AA_PatrimonioReportCanoniListTemplateView($id."_Canoni",$this,$object);

        //legenda
        $footer="<div style='font-style: italic; font-size: smaller; text-align: left; width: 100%; margin-top: 1em;'>La dicitura 'n.d.' indica che l'informazione corrispondente non è disponibile o non è presente negli archivi dell'Amministrazione Regionale.<br><span>Le informazioni del presente prospetto sono state aggiornate l'ultima volta il ".$object->GetAggiornamento()."</span></div>";
        $this->SetText($footer,false);
    }
}

//Classe per la gestione dei canoni
Class AA_Patrimonio_Canone
{
    protected $aProps=null;
    public function GetProps()
    {
        if(is_array($this->aProps)) return $this->aProps;
        else return array();
    }
    public function SetProp($prop="",$value="")
    {
        if(is_array($this->aProps) && $prop !="")
        {
            if(isset($this->aProps[$prop])) $this->aProps[$prop]=$value;
            return true;
        }

        return false;
    }
    public function GetProp($prop="")
    {
        if(is_array($this->aProps) && $prop !="")
        {
            if(isset($this->aProps[$prop])) return $this->aProps[$prop];
        }

        return "";
    }

    public function __construct($props=null)
    {
        //proprietà
        $this->aProps=array(
            "id"=>0,
            "serial"=>"",
            "id_patrimonio"=>0,
            "conduttore"=>"",
            "repertorio"=>"",
            "data_inizio"=>"",
            "data_fine"=>"",
            "importo"=>"",
            "note"=>"",
            "tipologia"=>0
        );

        if(is_array($props))
        {
            foreach($props as $key=>$value)
            {
                $this->SetProp($key,$value);
            }
        }
    }
}

#Classe template per la visualizzazione della lista dei canoni sul report
Class AA_PatrimonioReportCanoniListTemplateView extends AA_GenericTableTemplateView
{
    public function __construct($id="AA_PatrimonioReportCanoniListTemplateView",$parent=null,$object=null)
    {
        if(!($object instanceof AA_Patrimonio))
        {
            AA_Log::Log(__METHOD__." - oggetto non valido.", 100);
            return;
        }

        //Chiama il costruttore della classe base
        parent::__construct($id,$parent,$object,array("evidentiate-rows"=>true,"title"=>"Canoni","default-border-color"=>"#d7dbdd","h_bgcolor"=>"#d7dbdd","border"=>"1px solid #d7dbdd;","style"=>"font-size: smaller; margin-bottom: 1em; margin-top: 1em"));
        
        $canoni=$object->GetCanoni();

        if(sizeof($canoni)>0)
        {
            $this->SetColSizes(array("10", "10","10","10","10","10","10","25"));
            $this->SetHeaderLabels(array("Tipo","Id","Data inizio", "Data fine", "Importo<sup>1</sup>", "Repertorio", "Conduttore","Note"));
         
            $curRow=1;
            foreach($canoni as $id=>$curCanone)
            {
                //tipologia
                $tipo=AA_Patrimonio_Const::GetTipoCanoneList();
                $this->SetCellText($curRow,0,$tipo[$curCanone->GetProp("tipologia")], "center");

                //id
                $this->SetCellText($curRow,1,$curCanone->GetProp("serial"), "center");

                //Data inizio
                $this->SetCellText($curRow,2,$curCanone->GetProp("data_inizio"), "center");

                //Data fine
                $this->SetCellText($curRow,3,$curCanone->GetProp("data_fine"),"center");

                //importo
                $curVal="€ ".preg_replace("/[\)|\(|€|\ |A-Za-z_]/", "", $curCanone->GetProp("importo"));
                if($curVal=="€ ") $curVal="n.d.";
                $this->SetCellText($curRow,4,$curVal, "center");

                //repertorio
                $curVal=$curCanone->GetProp("repertorio");
                if($curVal=="") $curVal="n.d.";
                $this->SetCellText($curRow,5,$curVal, "center");

                //conduttore
                $curVal=$curCanone->GetProp("conduttore");
                if($curVal=="") $curVal="n.d.";
                $this->SetCellText($curRow,6,$curVal, "center");
                
                //note
                $curVal=$curCanone->GetProp("note");
                $this->SetCellText($curRow,7,$curVal, "center");
                
                $curRow++;
            }

            $footer="<div style='font-style: italic; text-align: left; width: 100%; margin-top: .3em;font-size: smaller;'>1. L'importo del canone, se non specificato diversamente nelle note, si intende su base annua.</div>";

            $this->SetText($footer,false);
        }
        else
        {
            $this->SetColSizes(array("100"));
            $this->SetCellText(1,0,"Non sono presenti canoni per questo immobile", "center");
        }
    }
}
