<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "config.php";
include_once "system_lib.php";

#Costanti
Class AA_Sier_Const extends AA_Const
{
    const AA_USER_FLAG_SIER="sier";

    //percorso file
    const AA_SIER_ALLEGATI_PATH="/sier/allegati";
    const AA_SIER_IMMAGINI_PUBLIC_PATH="/img";
    const AA_SIER_ALLEGATI_PUBLIC_PATH="/pubblicazioni/sier/docs.php";

    //Flags
    const AA_SIER_FLAG_CARICAMENTO_DATIGENERALI=256;
    const AA_SIER_FLAG_CARICAMENTO_CORPO_ELETTORALE=1;
    const AA_SIER_FLAG_CARICAMENTO_COMUNICAZIONI=128;
    const AA_SIER_FLAG_CARICAMENTO_AFFLUENZA=2;
    const AA_SIER_FLAG_CARICAMENTO_RISULTATI=4;
    const AA_SIER_FLAG_EXPORT_AFFLUENZA=8;
    const AA_SIER_FLAG_EXPORT_RISULTATI=16;
    const AA_SIER_FLAG_ACCESSO_OPERATORI=32;
    const AA_SIER_FLAG_CARICAMENTO_RESOCONTI=64;

    static protected $aFlags=null;
    public static function GetFlags()
    {
        if(static::$aFlags==null)
        {
            static::$aFlags=array(
                32=>"Abilita l'accesso da parte degli operatori comunali",
                256=>"Abilita l'aggiornamento dei dati generali dei comuni da parte degli operatori",
                1=>"Abilita l'aggiornamento dei dati del corpo elettorale dei comuni da parte degli operatori",
                2=>"Abilita l'aggiornamento dei dati sull'affluenza da parte degli operatori",
                4=>"Abilita l'aggiornamento dei risultati elettorali da parte degli operatori",
                64=>"Abilita l'aggiornamento dei resoconti dei comuni da parte degli operatori",
                8=>"Abilita l'esportazione dell'affluenza per la visualizzazione sul sito istituzionale",
                16=>"Abilita l'esportazione dei risultati per la visualizzazione sul sito istituzionale"
            );
        }
        return static::$aFlags;
    }

    static protected $aFlagsForTags=null;
    public static function GetFlagsForTags()
    {
        if(static::$aFlagsForTags==null)
        {
            static::$aFlagsForTags=array(
                32=>"accesso",
                256=>"dati generali",
                1=>"corpo elettorale",
                2=>"affluenza",
                4=>"risultati",
                64=>"resoconti",
                8=>"export affluenza",
                16=>"export risultati"
            );
        }
        return static::$aFlagsForTags;
    }

    //Circoscrizioni
    const AA_SIER_CIRCOSCRIZIONE_CAGLIARI=1;
    const AA_SIER_CIRCOSCRIZIONE_CARBONIAIGLESIAS=2;
    const AA_SIER_CIRCOSCRIZIONE_MEDIOCAMPIDANO=4;
    const AA_SIER_CIRCOSCRIZIONE_NUORO=8;
    const AA_SIER_CIRCOSCRIZIONE_OGLIASTRA=16;
    const AA_SIER_CIRCOSCRIZIONE_OLBIATEMPIO=32;
    const AA_SIER_CIRCOSCRIZIONE_ORISTANO=64;
    const AA_SIER_CIRCOSCRIZIONE_SASSARI=128;

    protected static $aCircoscrizioni=null;

    public static function GetCircoscrizioni()
    {
        if(static::$aCircoscrizioni==null)
        {
            static::$aCircoscrizioni=array(
                static::AA_SIER_CIRCOSCRIZIONE_CAGLIARI=>"Cagliari",
                static::AA_SIER_CIRCOSCRIZIONE_CARBONIAIGLESIAS=>"Carbonia-Iglesias",
                static::AA_SIER_CIRCOSCRIZIONE_MEDIOCAMPIDANO=>"Medio Campidano",
                static::AA_SIER_CIRCOSCRIZIONE_NUORO=>"Nuoro",
                static::AA_SIER_CIRCOSCRIZIONE_OGLIASTRA=>"Ogliastra",
                static::AA_SIER_CIRCOSCRIZIONE_OLBIATEMPIO=>"Olbia-Tempio",
                static::AA_SIER_CIRCOSCRIZIONE_ORISTANO=>"Oristano",
                static::AA_SIER_CIRCOSCRIZIONE_SASSARI=>"Sassari"
            );
        }

        return static::$aCircoscrizioni;
    }

    public static function GetCircoscrizione($id=0)
    {
        $circoscrizioni=static::GetCircoscrizioni();
        if(isset($circoscrizioni[$id]))
        {
            return array('id'=>$id,"value"=>$circoscrizioni[$id]);
        }

        return null;
    }

    protected static $aTipoAllegati=null;
    const AA_SIER_ALLEGATO_INFORMAZIONI=1;
    const AA_SIER_ALLEGATO_NORMATIVA=2;
    const AA_SIER_ALLEGATO_MODULISTICA=4;
    const AA_SIER_ALLEGATO_COMUNICAZIONI=8;
    const AA_SIER_ALLEGATO_RISULTATI=16;
    public static function GetTipoAllegati()
    {
        if(static::$aTipoAllegati==null)
        {
            static::$aTipoAllegati=array(
                static::AA_SIER_ALLEGATO_INFORMAZIONI=>"Informazioni generali",
                static::AA_SIER_ALLEGATO_NORMATIVA=>"Normativa",
                static::AA_SIER_ALLEGATO_MODULISTICA=>"Modulistica",
                static::AA_SIER_ALLEGATO_COMUNICAZIONI=>"Comunicazioni",
                static::AA_SIER_ALLEGATO_RISULTATI=>"Risultati"
            );
        }

        return static::$aTipoAllegati;
    }
}

#Classe Coalizioni
Class AA_SierCoalizioni
{
    protected $aProps=array();
    
    //Importa i valori da un array
    protected function Parse($values=null)
    {
        if(is_array($values))
        {
            foreach($values as $key=>$value)
            {
                if(isset($this->aProps[$key]) && $key != "") $this->aProps[$key]=$value;
            }
        }
    }

    public function GetListe()
    {
        return $this->aProps['liste'];
    }

    public function __construct($params=null)
    {
        //Definisce le proprietà dell'oggetto e i valori di default
        $this->aProps['id']=0;
        $this->aProps['id_sier']=0;
        $this->aProps['denominazione']="";
        $this->aProps['nome_candidato']="";
        $this->aProps['liste']=array();
        $this->aProps['image']="";

        if(is_array($params)) $this->Parse($params);
    }

    //imposta il valore di una propietà
    public function SetProp($prop="",$value="")
    {
        if($prop !="" && isset($this->aProps[$prop])) $this->aProps[$prop]=$value;
    }

    //restituisce il valore di una propietà
    public function GetProp($prop="")
    {
        if($prop !="" && isset($this->aProps[$prop])) return $this->aProps[$prop];
        else return "";
    }

    //restituisce tutte le propietà
    public function GetProps()
    {
        return $this->aProps;
    }
}

#Classe Lista
Class AA_SierLista
{
    protected $aProps=array();
    
    //Importa i valori da un array
    protected function Parse($values=null)
    {
        if(is_array($values))
        {
            foreach($values as $key=>$value)
            {
                if(isset($this->aProps[$key]) && $key != "") $this->aProps[$key]=$value;
            }
        }
    }

    public function GetCandidati($circoscrizione="")
    {
        return array();
    }

    public function __construct($params=null)
    {
        //Definisce le proprietà dell'oggetto e i valori di default
        $this->aProps['id']=0;
        $this->aProps['id_coalizione']=0;
        $this->aProps['denominazione']="";
        $this->aProps['image']="";
        $this->aProps['candidati']=array();

        if(is_array($params)) $this->Parse($params);
    }

    //imposta il valore di una propietà
    public function SetProp($prop="",$value="")
    {
        if($prop !="" && isset($this->aProps[$prop])) $this->aProps[$prop]=$value;
    }

    //restituisce il valore di una propietà
    public function GetProp($prop="")
    {
        if($prop !="" && isset($this->aProps[$prop])) return $this->aProps[$prop];
        else return "";
    }

    //restituisce tutte le propietà
    public function GetProps()
    {
        return $this->aProps;
    }
}

#Classe CAndidato
Class AA_SierCandidato
{
    protected $aProps=array();
    
    //Importa i valori da un array
    protected function Parse($values=null)
    {
        if(is_array($values))
        {
            foreach($values as $key=>$value)
            {
                if(isset($this->aProps[$key]) && $key != "") $this->aProps[$key]=$value;
            }
        }
    }

    public function __construct($params=null)
    {
        //Definisce le proprietà dell'oggetto e i valori di default
        $this->aProps['id']=0;
        $this->aProps['ordine']=0;
        $this->aProps['id_lista']=0;
        $this->aProps['lista']="";
        $this->aProps['id_coalizione']=0;
        $this->aProps['coalizione']="";
        $this->aProps['id_circoscrizione']=0;
        $this->aProps['circoscrizione']=0;
        $this->aProps['nome']="";
        $this->aProps['cognome']="";
        $this->aProps['cf']="";
        $this->aProps['cv']="";
        $this->aProps['cg']="";

        if(is_array($params)) $this->Parse($params);
    }

    //imposta il valore di una propietà
    public function SetProp($prop="",$value="")
    {
        if($prop !="" && isset($this->aProps[$prop])) $this->aProps[$prop]=$value;
    }

    //restituisce il valore di una propietà
    public function GetProp($prop="")
    {
        if($prop !="" && isset($this->aProps[$prop])) return $this->aProps[$prop];
        else return "";
    }

    //restituisce tutte le propietà
    public function GetProps()
    {
        return $this->aProps;
    }
}

#Classe oggetto elezioni
Class AA_Sier extends AA_Object_V2
{
    //tabella dati db
    const AA_DBTABLE_DATA="aa_sier_data";
    const AA_ALLEGATI_DB_TABLE="aa_sier_allegati";
    const AA_COALIZIONI_DB_TABLE="aa_sier_coalizioni";
    const AA_LISTE_DB_TABLE="aa_sier_liste";
    const AA_CANDIDATI_DB_TABLE="aa_sier_candidati";
    const AA_COMUNI_DB_TABLE="aa_sier_comuni";

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

        //Cancella comuni
        //to do

        //Cancella le coalizioni e le liste
        foreach($this->GetCoalizioni() as $curCoalizione)
        {
            if(!$this->DeleteCoalizione($curCoalizione,$user))
            {
                return false;
            }
        }

        return parent::DeleteData($idData,$user);
    }

    //Restituisce le abilitazioni
    public function GetAbilitazioni()
    {
        return $this->GetProp("Flags");
    }

    //Restituisce le giornate
    public function GetGiornate()
    {
        $value=json_decode($this->GetProp("Giornate"),true);
        if(!is_array($value))
        {
            AA_Log::Log(__METHOD__." - Errore durante la decodifica delle giornate: ".$this->GetProp("Giornate")." - ".print_r($value,true),100);
            return array();
        }

        return $value;
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
        $this->SetBind("Note","note");
        $this->SetBind("Flags","flags");
        $this->SetBind("Anno","anno");
        $this->SetBind("Giornate","giornate");

        //Valori iniziali
        $this->SetProp("IdData",0);
        $this->SetProp("Flags",0);

        //disabilita la revisione
        $this->EnableRevision(false);

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
        else
        {
            $this->bValid=false;
        }
    }

    //Restituisce le coalizioni
    public function GetCoalizioni($params=array())
    {
        if(!$this->bValid) return array();

        $db=new AA_Database();
        $query="SELECT ".static::AA_COALIZIONI_DB_TABLE.".* from ".static::AA_COALIZIONI_DB_TABLE." WHERE id_sier='".$this->nId_Data."'";

        //eventuali parametri di filtraggio
        if(is_array($params))
        {
            if(isset($params["id_coalizione"]) && $params["id_coalizione"]!="")
            {
                $query.=" AND id='".addslashes(trim($params['id_coalizione']))."'";
            }

            if(isset($params["denominazione"]) && $params["denominazione"]!="")
            {
                $query.=" AND denominazione like '%".addslashes(trim($params['denominazione']))."%'";
            }
        }

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore query: ".$query,100);
            return array();
        }

        $result=array();
        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            foreach($rs as $curRow)
            {
                $liste=array();
                
                //Recupero liste
                $query="SELECT * from ".static::AA_LISTE_DB_TABLE." WHERE id_coalizione='".$curRow['id']."'";
                if(!$db->Query($query))
                {
                    AA_Log::Log(__METHOD__." - Errore query: ".$query,100);
                }
                else
                {
                    foreach($db->GetResultSet() as $curLista)
                    {
                        $liste[$curLista['id']]=new AA_SierLista($curLista);
                    }
                    $curRow['liste']=$liste;
                }

                $curCoalizione= new AA_SierCoalizioni($curRow);
                $result[$curRow['id']]=$curCoalizione;
            }
        }

        return $result;
    }

    public function GetCoalizione($id_coalizione=0)
    {
        if(!$this->bValid) return null;

        if($id_coalizione <= 0)
        {
            AA_Log::Log(__METHOD__." - id coalizione non valido",100);
            return null;
        }

        $db=new AA_Database();
        $query="SELECT ".static::AA_COALIZIONI_DB_TABLE.".* from ".static::AA_COALIZIONI_DB_TABLE." WHERE id_sier='".$this->nId_Data."' AND id='".addslashes($id_coalizione)."' LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore query: ".$query,100);
            return null;
        }

        $result=null;
        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            foreach($rs as $curRow)
            {
                $liste=array();
                
                //Recupero liste
                $query="SELECT * from ".static::AA_LISTE_DB_TABLE." WHERE id_coalizione='".$curRow['id']."'";
                if(!$db->Query($query))
                {
                    AA_Log::Log(__METHOD__." - Errore query: ".$query,100);
                }
                else
                {
                    foreach($db->GetResultSet() as $curLista)
                    {
                        $liste[$curLista['id']]=new AA_SierLista($curLista);
                    }
                    $curRow['liste']=$liste;
                }

                $result = new AA_SierCoalizioni($curRow);
            }
        }

        return $result;
    }

    //Restituisce le liste
    public function GetListe($coalizione=null)
    {
        if(!$this->bValid) return array();

        $db=new AA_Database();
        $query="SELECT ".static::AA_LISTE_DB_TABLE.".* from ".static::AA_LISTE_DB_TABLE." INNER JOIN ".static::AA_COALIZIONI_DB_TABLE." ON ".static::AA_LISTE_DB_TABLE.".id_coalizione=".static::AA_COALIZIONI_DB_TABLE.".id WHERE ".static::AA_COALIZIONI_DB_TABLE.".id_sier='".$this->nId_Data."'";

        if($coalizione instanceof AA_SierCoalizioni)
        {
            $query.=" AND ".static::AA_COALIZIONI_DB_TABLE.".id='".addslashes($coalizione->GetProp('id'))."'";
        }

        $query.=" ORDER BY ".static::AA_LISTE_DB_TABLE.".denominazione ";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore query: ".$query,100);
            return array();
        }

        AA_Log::Log(__METHOD__." - query: ".$query,100);

        $result=array();
        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            foreach($rs as $curRow)
            {
                $result[$curRow['id']]=new AA_SierLista($curRow);
            }
        }

        //AA_Log::Log(__METHOD__." - liste: ".print_r($result,true),100);

        return $result;
    }

    //restituisce la lista indicata
    public function GetLista($id_lista="")
    {
        if(!$this->bValid) return array();

        $db=new AA_Database();
        $query="SELECT ".static::AA_LISTE_DB_TABLE.".* from ".static::AA_LISTE_DB_TABLE." INNER JOIN ".static::AA_COALIZIONI_DB_TABLE." ON ".static::AA_LISTE_DB_TABLE.".id_coalizione=".static::AA_COALIZIONI_DB_TABLE.".id WHERE ".static::AA_COALIZIONI_DB_TABLE.".id_sier='".$this->nId_Data."'";

        if($id_lista > 0)
        {
            $query.=" AND ".static::AA_LISTE_DB_TABLE.".id='".addslashes($id_lista)."'";
        }

        $query.=" LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore query: ".$query,100);
            return null;
        }

        //AA_Log::Log(__METHOD__." - query: ".$query,100);

        $result=null;
        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            foreach($rs as $curRow)
            {
                $result=new AA_SierLista($curRow);
            }
        }

        return $result;
    }

    //Restituisce i candidati
    public function GetCandidati($coalizione=null,$lista=null)
    {
        if(!$this->bValid) return array();

        $db=new AA_Database();
        $query="SELECT ".static::AA_CANDIDATI_DB_TABLE.".*,".static::AA_COALIZIONI_DB_TABLE.".id as id_coalizione,".static::AA_COALIZIONI_DB_TABLE.".denominazione as coalizione,".static::AA_LISTE_DB_TABLE.".denominazione as lista from ".static::AA_CANDIDATI_DB_TABLE." INNER JOIN ".static::AA_LISTE_DB_TABLE." ON ".static::AA_CANDIDATI_DB_TABLE.".id_lista=".static::AA_LISTE_DB_TABLE.".id INNER JOIN ".static::AA_COALIZIONI_DB_TABLE." ON ".static::AA_LISTE_DB_TABLE.".id_coalizione=".static::AA_COALIZIONI_DB_TABLE.".id WHERE ".static::AA_COALIZIONI_DB_TABLE.".id_sier='".$this->nId_Data."'";

        if($coalizione instanceof AA_SierCoalizioni)
        {
            $query.=" AND ".static::AA_COALIZIONI_DB_TABLE.".id='".addslashes($coalizione->GetProp('id'))."'";
        }

        if($lista instanceof AA_SierLista)
        {
            $query.=" AND ".static::AA_CANDIDATI_DB_TABLE.".id_lista='".addslashes($lista->GetProp('id'))."'";
        }

        $query.=" ORDER by ".static::AA_CANDIDATI_DB_TABLE.".ordine, ".static::AA_CANDIDATI_DB_TABLE.".cognome, ".static::AA_CANDIDATI_DB_TABLE.".nome";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore query: ".$query,100);
            return array();
        }

        $result=array();
        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            foreach($rs as $curRow)
            {
                $circoscrizione=AA_Sier_Const::GetCircoscrizione($curRow['id_circoscrizione']);
                if($circoscrizione) $curRow['circoscrizione']=$circoscrizione['value'];
                $result[$curRow['id']]=new AA_SierCandidato($curRow);
            }
        }

        return $result;
    }

    //Restituisce un candidato specifico
    public function GetCandidato($id="")
    {
        if(!$this->bValid || $id<=0 || $id=="") return null;

        $db=new AA_Database();
        $query="SELECT ".static::AA_CANDIDATI_DB_TABLE.".*,".static::AA_COALIZIONI_DB_TABLE.".id as id_coalizione,".static::AA_COALIZIONI_DB_TABLE.".denominazione as coalizione,".static::AA_LISTE_DB_TABLE.".denominazione as lista from ".static::AA_CANDIDATI_DB_TABLE." INNER JOIN ".static::AA_LISTE_DB_TABLE." ON ".static::AA_CANDIDATI_DB_TABLE.".id_lista=".static::AA_LISTE_DB_TABLE.".id INNER JOIN ".static::AA_COALIZIONI_DB_TABLE." ON ".static::AA_LISTE_DB_TABLE.".id_coalizione=".static::AA_COALIZIONI_DB_TABLE.".id WHERE ".static::AA_COALIZIONI_DB_TABLE.".id_sier='".$this->nId_Data."'";

        if($id > 0)
        {
            $query.=" AND ".static::AA_CANDIDATI_DB_TABLE.".id ='".addslashes($id)."'";
        }

        $query.=" LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore query: ".$query,100);
            return null;
        }

        $result=null;
        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            foreach($rs as $curRow)
            {
                $circoscrizione=AA_Sier_Const::GetCircoscrizione($curRow['id_circoscrizione']);
                if($circoscrizione) $curRow['circoscrizione']=$circoscrizione['value'];
                $result=new AA_SierCandidato($curRow);
            }
        }

        return $result;
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
        $params['class']="AA_Sier";
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
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && !$user->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
        {
            $perms = AA_Const::AA_PERMS_READ;
        }
        //---------------------------------------

        //Se l'utente ha il flag e può modificare la scheda allora può fare tutto
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && $user->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
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
        if(!$user->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
        {
            AA_Log::Log(__METHOD__." - L'utente corrente: ".$user->GetUserName()." non ha i permessi per inserire nuovi elementi.",100);
            return false;
        }

        //Verifica validità oggetto
        if(!($object instanceof AA_Sier))
        {
            AA_Log::Log(__METHOD__." - Errore: oggetto non valido (".print_r($object,true).").",100);
            return false;
        }

        $object->nId=0;
        $object->bValid=true;
        //----------------------------------------------

        return parent::AddNew($object,$user,$bSaveData);
    }

    //Restituisce un allegato esistente
    public function GetAllegato($id=null, $user=null)
    {
        AA_Log::Log(__METHOD__."()");

        if(!$this->isValid())
        {
                AA_Log::Log(__METHOD__." - oggetto non valido.", 100,false,true);
                return null;            
        }
        
        //Verifica utente
        if($user==null || !$user->isValid() || !$user->isCurrentUser()) 
        {
            $user=AA_User::GetCurrentUser();
        
            if($user==null || !$user->isValid() || !$user->isCurrentUser())
            {
                AA_Log::Log(__METHOD__." - utente non valido.", 100,false,true);
                return null;
            }
        }

        //Verifica Flags
        if(($this->GetUserCaps($user) & AA_Const::AA_PERMS_READ)==0)
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non ha accesso all'oggetto.", 100,false,true);
            return null;
        }
        
        $id_sier=$this->nId_Data;
        if($this->nId_Data_Rev > 0)
        {
            $id_sier=$this->nId_Data_Rev;
        }

        $query="SELECT * FROM ".AA_Sier::AA_ALLEGATI_DB_TABLE." WHERE id_sier='".$id_sier."'";
        $query.=" AND id='".addslashes($id)."' LIMIT 1";
        
        $db= new AA_Database();
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return null;            
        }
        
        if($db->GetAffectedRows() > 0)
        {
            $rs=$db->GetResultSet();
            $object=new AA_SierAllegati($rs[0]['id'],$id_sier,$rs[0]['estremi'],$rs[0]['url'],$rs[0]['file'],$rs[0]['tipo'],$rs[0]['aggiornamento']);
            
            return $object;
        }
        
        return null;
    }
    
    //Aggiunge una nuova lista
    public function AddNewLista($lista=null, $coalizione=null, $user=null)
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

        if(!($coalizione instanceof AA_SierCoalizioni))
        {
            AA_Log::Log(__METHOD__." - Dati Coalizione non validi.", 100,false,true);
            return false;
        }

        if(!($lista instanceof AA_SierLista))
        {
            AA_Log::Log(__METHOD__." - Dati Lista non validi.", 100,false,true);
            return false;
        }
        
        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Aggiunta nuova lista: ".$lista->GetProp('denominazione')))
        {
            return false;
        }

        //AA_Log::Log(__METHOD__." - nuova lista: ".print_r($lista,true), 100);

        $query="INSERT INTO ".static::AA_LISTE_DB_TABLE." SET id_coalizione='".$coalizione->GetProp('id')."'";
        $query.=", denominazione='".addslashes($lista->GetProp('denominazione'))."'";
        $query.=", image='".addslashes($lista->GetProp('image'))."'";
        
        $db = new AA_Database();
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        return true;
    }

    //Aggiorna una lista esistente
    public function UpdateLista($lista=null, $coalizione=null, $user=null)
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

        if(!($coalizione instanceof AA_SierCoalizioni))
        {
            AA_Log::Log(__METHOD__." - Dati Coalizione non validi.", 100,false,true);
            return false;
        }

        if(!($lista instanceof AA_SierLista))
        {
            AA_Log::Log(__METHOD__." - Dati Lista non validi.", 100,false,true);
            return false;
        }
        
        $query="UPDATE ".static::AA_LISTE_DB_TABLE." SET id_coalizione='".$coalizione->GetProp('id')."'";
        $query.=", denominazione='".addslashes($lista->GetProp('denominazione'))."'";
        $query.=", image='".addslashes($lista->GetProp('image'))."'";
        $query.=" WHERE id='".addslashes($lista->GetProp('id'))."' LIMIT 1";
        
        $db = new AA_Database();
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }

        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Aggiorna la lista: ".$lista->GetProp('denominazione')))
        {
            return false;
        }

        return true;
    }

    //Aggiunge una nuova coalizione
    public function AddNewCoalizione($newCoalizione=null, $user=null)
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

        if(!($newCoalizione instanceof AA_SierCoalizioni))
        {
            AA_Log::Log(__METHOD__." - Dati Coalizione non validi.", 100,false,true);
            return false;
        }
        
        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Aggiunta nuova coalizione: ".$newCoalizione->GetProp('denominazione')))
        {
            return false;
        }

        $newCoalizione->SetProp('id_sier',$this->nId_Data);
        if($this->nId_Data_Rev > 0)
        {
            $newCoalizione->SetProp('id_sier',$this->nId_Data_Rev);
        }

        $query="INSERT INTO ".static::AA_COALIZIONI_DB_TABLE." SET id_sier='".$newCoalizione->GetProp('id_sier')."'";
        $query.=", denominazione='".addslashes($newCoalizione->GetProp('denominazione'))."'";
        $query.=", nome_candidato='".addslashes($newCoalizione->GetProp('nome_candidato'))."'";
        $query.=", image='".addslashes($newCoalizione->GetProp('image'))."'";
        
        $db = new AA_Database();
        
        //AA_Log::Log(__METHOD__." - query: ".$query, 100);
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        return true;
    }

    //Aggiorna una coalizione esistente
    public function UpdateCoalizione($newCoalizione=null, $user=null)
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

        if(!($newCoalizione instanceof AA_SierCoalizioni))
        {
            AA_Log::Log(__METHOD__." - Dati Coalizione non validi.", 100,false,true);
            return false;
        }
        
        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Modifica coalizione: ".$newCoalizione->GetProp('denominazione')))
        {
            return false;
        }

        $newCoalizione->SetProp('id_sier',$this->nId_Data);
        if($this->nId_Data_Rev > 0)
        {
            $newCoalizione->SetProp('id_sier',$this->nId_Data_Rev);
        }

        $query="UPDATE ".static::AA_COALIZIONI_DB_TABLE." SET id_sier='".$newCoalizione->GetProp('id_sier')."'";
        $query.=", denominazione='".addslashes($newCoalizione->GetProp('denominazione'))."'";
        $query.=", nome_candidato='".addslashes($newCoalizione->GetProp('nome_candidato'))."'";
        $query.=", image='".addslashes($newCoalizione->GetProp('image'))."'";
        $query.=" WHERE id='".$newCoalizione->GetProp('id')."' LIMIT 1";
        
        $db = new AA_Database();
        
        //AA_Log::Log(__METHOD__." - query: ".$query, 100);
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        return true;
    }

    //Elimina una coalizione esistente
    public function DeleteCoalizione($coalizione=null, $user=null)
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

        if(!($coalizione instanceof AA_SierCoalizioni))
        {
            if($coalizione == "" && $coalizione == null)
            {
                AA_Log::Log(__METHOD__." - Dati Coalizione non validi.", 100);
                return false;
            }
            $coalizioni=$this->GetCoalizioni(array('id_coalizione'=>$coalizione));
            if(isset($coalizioni[$coalizione])) $coalizione=$coalizioni[$coalizione];
            else
            {
                AA_Log::Log(__METHOD__." - Dati Coalizione non validi.", 100);
                return false;
            }
        }

        if($coalizione->GetProp('id')<=0)
        {
            AA_Log::Log(__METHOD__." - Dati Coalizione non validi.", 100,false,true);
            return false;            
        }

        //Elimina le liste
        foreach($coalizione->GetListe() as $curLista)
        {
            if(!$this->DeleteLista($curLista,$coalizione,$user))
            {
                return false;
            }
        }

        //Elimina l'immagine
        if($coalizione->GetProp("image") !="")
        {
            $storage=AA_Storage::GetInstance();
            if($storage->IsValid())
            {
                if(!$storage->DelFile($coalizione->GetProp("image")))
                {
                    AA_Log::Log(__METHOD__." - Errore durante l'eliminazione dell'immagine della coalizione.", 100);
                    //return false;      
                }
            }
        }

        $query="DELETE FROM ".static::AA_COALIZIONI_DB_TABLE." WHERE id_sier='".$this->nId_Data."'";
        $query.=" AND id='".addslashes($coalizione->GetProp('id'))."' LIMIT 1";
        
        $db = new AA_Database();
        
        //AA_Log::Log(__METHOD__." - query: ".$query, 100);
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Elimina coalizione: ".$coalizione->GetProp('denominazione')))
        {
            return false;
        }
        
        return true;
    }

    //Elimina una lista esistente
    public function DeleteLista($lista=null,$coalizione=null, $user=null)
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

        if(!($coalizione instanceof AA_SierCoalizioni))
        {
            if($coalizione == "" && $coalizione == null)
            {
                AA_Log::Log(__METHOD__." - Dati Coalizione non validi.", 100);
                return false;
            }
            $coalizioni=$this->GetCoalizioni(array('id_coalizione'=>$coalizione));
            if(isset($coalizioni[$coalizione])) $coalizione=$coalizioni[$coalizione];
            else
            {
                AA_Log::Log(__METHOD__." - Dati Coalizione non validi.", 100);
                return false;
            }
        }

        if($coalizione->GetProp('id')<=0)
        {
            AA_Log::Log(__METHOD__." - Dati Coalizione non validi.", 100,false,true);
            return false;            
        }

        $liste=$coalizione->GetListe();

        if(!($lista instanceof AA_SierLista))
        {
            if($lista == "" || $lista == null)
            {
                AA_Log::Log(__METHOD__." - Dati Lista non validi.", 100,false,true);
                return false;   
            }
                
            if(!isset($liste[$lista]))
            {
                AA_Log::Log(__METHOD__." - Dati Lista non validi.", 100,false,true);
                return false;                   
            }

            $lista=$liste[$lista];
        }
        else
        {
            if(!isset($liste[$lista->GetProp('id')]))
            {
                AA_Log::Log(__METHOD__." - Dati Lista non validi.", 100,false,true);
                return false;                   
            }
        }

        //Elimina i candidati associati alla lista
        $candidati=$this->GetCandidati($coalizione,$lista);
        foreach($candidati as $curCandidato)
        {
            if(!$this->DeleteCandidato($curCandidato))
            {
                AA_Log::Log(__METHOD__." - Errore nella cancellazione delle informazioni sui candidati associati alla lista.", 100,false,true);
                return false;
            }
        }

        if($lista->GetProp("image") !="")
        {
            $storage=AA_Storage::GetInstance();
            if($storage->IsValid())
            {
                if(!$storage->DelFile($lista->GetProp("image")))
                {
                    AA_Log::Log(__METHOD__." - Errore durante l'eliminazione dell'immagine della lista.", 100);
                    //return false;      
                }
            }
        }

        $query="DELETE FROM ".static::AA_LISTE_DB_TABLE." WHERE id_coalizione='".addslashes($coalizione->GetProp('id'))."'";
        $query.=" AND id='".addslashes($lista->GetProp('id'))."' LIMIT 1";
        
        $db = new AA_Database();
        
        //AA_Log::Log(__METHOD__." - query: ".$query, 100);
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Elimina la Lista: ".$lista->GetProp('denominazione')))
        {
            return false;
        }
        
        return true;
    }

    //Aggiunge un nuovo candidato
    public function AddNewCandidato($candidato=null, $user=null)
    {
        //AA_Log::Log(__METHOD__."()");

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

        if(!($candidato instanceof AA_SierCandidato))
        {
            AA_Log::Log(__METHOD__." - Candidato non valido.", 100,false,true);
            return false;
        }

        $db= new AA_Database();

        //Calcolo dell'ordine
        $ordine=0;
        $query="SELECT count(id) as num FROM ".static::AA_CANDIDATI_DB_TABLE." WHERE id_circoscrizione='".$candidato->GetProp("id_circoscrizione")."' AND id_lista='".addslashes($candidato->GetProp("id_lista"))."'";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }

        $rs=$db->GetResultSet();
        $ordine=$rs[0]['num']+1;

        $query="INSERT INTO ".static::AA_CANDIDATI_DB_TABLE." SET id_circoscrizione='".$candidato->GetProp("id_circoscrizione")."'";
        $query.=", id_lista='".addslashes($candidato->GetProp("id_lista"))."'";
        $query.=", nome='".addslashes($candidato->GetProp("nome"))."'";
        $query.=", cognome='".addslashes($candidato->GetProp("cognome"))."'";
        $query.=", cf='".addslashes($candidato->GetProp("cf"))."'";
        $query.=", cv='".addslashes($candidato->GetProp("cv"))."'";
        $query.=", cg='".addslashes($candidato->GetProp("cg"))."'";
        $query.=", ordine='".$ordine."'";
        
        //AA_Log::Log(__METHOD__." - query: ".$query, 100);
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }

        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Aggiunta nuovo candidato: ".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")))
        {
            return false;
        }

        $newId=$db->GetLastInsertId();
        //AA_Log::Log(__METHOD__." - new id: ".$newId, 100,false,true);

        return $newId;
    }

    //Aggiorna un candidato
    public function UpdateCandidato($candidato=null, $user=null,$logMsg="")
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

        if(!($candidato instanceof AA_SierCandidato))
        {
            AA_Log::Log(__METHOD__." - Candidato non valido.", 100,false,true);
            return false;
        }

        $query="UPDATE ".static::AA_CANDIDATI_DB_TABLE." SET id_circoscrizione='".$candidato->GetProp("id_circoscrizione")."'";
        $query.=", id_lista='".addslashes($candidato->GetProp("id_lista"))."'";
        $query.=", nome='".addslashes(trim($candidato->GetProp("nome")))."'";
        $query.=", cognome='".addslashes(trim($candidato->GetProp("cognome")))."'";
        $query.=", cf='".addslashes(trim($candidato->GetProp("cf")))."'";
        $query.=", cv='".addslashes($candidato->GetProp("cv"))."'";
        $query.=", cg='".addslashes($candidato->GetProp("cg"))."'";
        $query.=", ordine='".addslashes(trim($candidato->GetProp("ordine")))."'";
        $query.=" WHERE id='".$candidato->GetProp('id')."' LIMIT 1";
        
        $db= new AA_Database();
        
        //AA_Log::Log(__METHOD__." - query: ".$query, 100);
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }

        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if($logMsg=="") $logMsg="Aggiornamento candidato: ".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome");
        if(!$this->Update($user,true, $logMsg))
        {
            return false;
        }

        return true;
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

        if(!($allegato instanceof AA_SierAllegati))
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

        $allegato->SetIdSier($this->nId_Data);
        if($this->nId_Data_Rev > 0)
        {
            $allegato->SetIdSier($this->nId_Data_Rev);
        }

        $query="INSERT INTO ".static::AA_ALLEGATI_DB_TABLE." SET id_sier='".$allegato->GetIdSier()."'";
        $query.=", url='".addslashes($allegato->GetUrl())."'";
        $query.=", estremi='".addslashes($allegato->GetEstremi())."'";
        $query.=", file='".addslashes($allegato->GetFileHash())."'";
        $query.=", tipo='".addslashes($allegato->GetTipo())."'";
        $query.=", aggiornamento='".addslashes($allegato->GetAggiornamento())."'";
        
        $db= new AA_Database();
        
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

        if(!($allegato instanceof AA_SierAllegati))
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

        $allegato->SetIdSier($this->nId_Data);
        if($this->nId_Data_Rev > 0)
        {
            $allegato->SetIdSier($this->nId_Data_Rev);
        }
        
        $query="UPDATE ".static::AA_ALLEGATI_DB_TABLE." SET id_sier='".$allegato->GetIdSier()."'";
        $query.=", url='".addslashes($allegato->GetUrl())."'";
        $query.=", estremi='".addslashes($allegato->GetEstremi())."'";
        $query.=", file='".addslashes($allegato->GetFileHash())."'";
        $query.=", tipo='".addslashes($allegato->GetTipo())."'";
        $query.=", aggiornamento='".addslashes($allegato->GetAggiornamento())."'";
        $query.=" WHERE id='".addslashes($allegato->GetId())."' LIMIT 1";
        
        $db= new AA_Database();
        
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

        if(!($allegato instanceof AA_SierAllegati))
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

        $allegato->SetIdSier($this->nId_Data);
        if($this->nId_Data_Rev > 0)
        {
            $allegato->SetIdSier($this->nId_Data_Rev);
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

    //Elimina un candidato esistente
    public function DeleteCandidato($candidato=null, $user=null)
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

        if(!($candidato instanceof AA_SierCandidato))
        {
            $candidato=$this->GetCandidato($candidato);
        }

        if(!($candidato instanceof AA_SierCandidato))
        {            
            AA_Log::Log(__METHOD__." - Candidato non valido.", 100,false,true);
            return false;
        }

        //elimina il cv e il casellario
        $storage=AA_Storage::GetInstance($user);
        if($storage->isValid())
        {
            if($candidato->GetProp('cv') !="" && strpos($candidato->GetProp('cv'),"http") ===false)
            {
                if(!$storage->DelFile($candidato->GetProp('cv')))
                {
                    AA_Log::Log(__METHOD__." - Eliminazione del file: ".$candidato->GetProp('cv')." non riuscita (file non trovato).",100);
                }
            }

            if($candidato->GetProp('cg') !="" && strpos($candidato->GetProp('cg'),"http") ===false)
            {
                if(!$storage->DelFile($candidato->GetProp('cg')))
                {
                    AA_Log::Log(__METHOD__." - Eliminazione del file: ".$candidato->GetProp('cv')." non riuscita (file non trovato).",100);
                }
            }
        }
        else
        {
            AA_Log::Log(__METHOD__." - Storage non inizializzato.",100);
        }
        
        $query="DELETE FROM ".static::AA_CANDIDATI_DB_TABLE;
        $query.=" WHERE id='".addslashes($candidato->GetProp("id"))."'";
        $query.="LIMIT 1";
        
        $db= new AA_Database();
        
        //AA_Log::Log(__METHOD__." - query: ".$query, 100);
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        if(!$this->Update($user,true, "Rimozione candidato: ".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")))
        {
            return false;
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
        $query="SELECT * from ".AA_Sier::AA_ALLEGATI_DB_TABLE." WHERE";

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
            $allegato=new AA_SierAllegati($curRec['id'],$idData,$curRec['estremi'],$curRec['url'],$curRec['file'],$curRec['tipo'],$curRec['aggiornamento']);
            $result[$curRec['id']]=$allegato;
        }

        return $result;
    }
}

#Classe per il modulo art23 - provvedimenti dirigenziali e accordi
Class AA_SierModule extends AA_GenericModule
{
    const AA_UI_PREFIX="AA_Sier";

    //Id modulo
    const AA_ID_MODULE="AA_MODULE_SIER";

    //main ui layout box
    const AA_UI_MODULE_MAIN_BOX="AA_Sier_module_layout";

    const AA_MODULE_OBJECTS_CLASS="AA_Sier";

    //Task per la gestione dei dialoghi standard
    const AA_UI_TASK_PUBBLICATE_FILTER_DLG="GetSierPubblicateFilterDlg";
    const AA_UI_TASK_BOZZE_FILTER_DLG="GetSierBozzeFilterDlg";
    const AA_UI_TASK_REASSIGN_DLG="GetSierReassignDlg";
    const AA_UI_TASK_PUBLISH_DLG="GetSierPublishDlg";
    const AA_UI_TASK_TRASH_DLG="GetSierTrashDlg";
    const AA_UI_TASK_RESUME_DLG="GetSierResumeDlg";
    const AA_UI_TASK_DELETE_DLG="GetSierDeleteDlg";
    const AA_UI_TASK_ADDNEW_DLG="GetSierAddNewDlg";
    const AA_UI_TASK_MODIFY_DLG="GetSierModifyDlg";
    //------------------------------------

    //section ui ids
    const AA_UI_DETAIL_GENERALE_BOX = "Generale_Box";
    const AA_UI_DETAIL_LISTE_BOX = "Liste_Box";
    const AA_UI_DETAIL_CANDIDATI_BOX = "Candidati_Box";
    const AA_UI_DETAIL_COMUNI_BOX = "Comuni_Box";
    const AA_UI_DETAIL_CRUSCOTTO_BOX = "Cruscotto_Box";
    const AA_UI_DETAIL_ALLEGATI_BOX = "Allegati_Box";

    public function __construct($user=null,$bDefaultSections=true)
    {
        parent::__construct($user,$bDefaultSections);
        
        #--------------------------------Registrazione dei task-----------------------------
        $taskManager=$this->GetTaskManager();
        
        //Dialoghi di filtraggio
        $taskManager->RegisterTask("GetSierPubblicateFilterDlg");
        $taskManager->RegisterTask("GetSierBozzeFilterDlg");

        //elezioni
        $taskManager->RegisterTask("GetSierModifyDlg");
        $taskManager->RegisterTask("GetSierAddNewDlg");
        $taskManager->RegisterTask("GetSierTrashDlg");
        $taskManager->RegisterTask("TrashSier");
        $taskManager->RegisterTask("GetSierDeleteDlg");
        $taskManager->RegisterTask("DeleteSier");
        $taskManager->RegisterTask("GetSierResumeDlg");
        $taskManager->RegisterTask("ResumeSier");
        $taskManager->RegisterTask("GetSierReassignDlg");
        $taskManager->RegisterTask("GetSierPublishDlg");
        $taskManager->RegisterTask("ReassignSier");
        $taskManager->RegisterTask("AddNewSier");
        $taskManager->RegisterTask("UpdateSier");
        $taskManager->RegisterTask("PublishSier");

        //Allegati
        $taskManager->RegisterTask("GetSierAddNewAllegatoDlg");
        $taskManager->RegisterTask("AddNewSierAllegato");
        $taskManager->RegisterTask("GetSierModifyAllegatoDlg");
        $taskManager->RegisterTask("UpdateSierAllegato");
        $taskManager->RegisterTask("GetSierTrashAllegatoDlg");
        $taskManager->RegisterTask("DeleteSierAllegato");

        //giornate
        $taskManager->RegisterTask("GetSierAddNewGiornataDlg");
        $taskManager->RegisterTask("AddNewSierGiornata");
        $taskManager->RegisterTask("GetSierModifyGiornataDlg");
        $taskManager->RegisterTask("UpdateSierGiornata");
        $taskManager->RegisterTask("GetSierTrashGiornataDlg");
        $taskManager->RegisterTask("DeleteSierGiornata");

        //Coalizioni
        $taskManager->RegisterTask("GetSierAddNewCoalizioneDlg");
        $taskManager->RegisterTask("AddNewSierCoalizione");
        $taskManager->RegisterTask("GetSierModifyCoalizioneDlg");
        $taskManager->RegisterTask("UpdateSierCoalizione");
        $taskManager->RegisterTask("GetSierTrashCoalizioneDlg");
        $taskManager->RegisterTask("DeleteSierCoalizione");

        //Liste
        $taskManager->RegisterTask("GetSierAddNewListaDlg");
        $taskManager->RegisterTask("AddNewSierLista");
        $taskManager->RegisterTask("GetSierModifyListaDlg");
        $taskManager->RegisterTask("UpdateSierLista");
        $taskManager->RegisterTask("GetSierTrashListaDlg");
        $taskManager->RegisterTask("DeleteSierLista");

        //candidati
        $taskManager->RegisterTask("GetSierAddNewCandidatoDlg");
        $taskManager->RegisterTask("AddNewSierCandidato");
        $taskManager->RegisterTask("GetSierModifyCandidatoDlg");
        $taskManager->RegisterTask("UpdateSierCandidato");
        $taskManager->RegisterTask("GetSierAddNewCandidatoCVDlg");
        $taskManager->RegisterTask("AddNewSierCandidatoCV");
        $taskManager->RegisterTask("GetSierAddNewCandidatoCGDlg");
        $taskManager->RegisterTask("AddNewSierCandidatoCG");
        $taskManager->RegisterTask("GetSierModifyCandidatoCVDlg");
        $taskManager->RegisterTask("UpdateSierCandidatoCV");
        $taskManager->RegisterTask("GetSierModifyCandidatoCGDlg");
        $taskManager->RegisterTask("UpdateSierCandidatoCG");
        $taskManager->RegisterTask("GetSierTrashCandidatoCGDlg");
        $taskManager->RegisterTask("DeleteSierCandidatoCG");
        $taskManager->RegisterTask("GetSierTrashCandidatoCVDlg");
        $taskManager->RegisterTask("DeleteSierCandidatoCV");
        $taskManager->RegisterTask("GetSierTrashCandidatoDlg");
        $taskManager->RegisterTask("DeleteSierCandidato");

        //template dettaglio
        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL,array(
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateSierDettaglio_Generale_Tab"),
            //array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_CRUSCOTTO_TAB, "value"=>"Cruscotto","tooltip"=>"Cruscotto di gestione","template"=>"TemplateSierDettaglio_Cruscotto_Tab"),
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_LISTE_BOX, "value"=>"<span style='font-size: smaller'>Coalizioni e Liste</span>","tooltip"=>"Gestione coalizioni e liste","template"=>"TemplateSierDettaglio_Coalizioni_Tab"),
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_CANDIDATI_BOX, "value"=>"Candidati","tooltip"=>"Gestione dei Candidati","template"=>"TemplateSierDettaglio_Candidati_Tab"),
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_COMUNI_BOX, "value"=>"Comuni","tooltip"=>"Gestione dei Comuni","template"=>"TemplateSierDettaglio_Comuni_Tab"),
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_ALLEGATI_BOX, "value"=>"<span style='font-size: smaller'>Allegati e link</span>","tooltip"=>"Gestione degli allegati e links","template"=>"TemplateSierDettaglio_Allegati_Tab"),
        ));
    }
    
    //istanza
    protected static $oInstance=null;
    
    //Restituisce l'istanza corrente
    public static function GetInstance($user=null)
    {
        if(self::$oInstance==null)
        {
            self::$oInstance=new AA_SierModule($user);
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
        if($this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
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
       //Tipo
       if($params['Tipo'] > 0)
       {
           $params['where'][]=" AND ".AA_Sier::AA_DBTABLE_DATA.".tipo = '".addslashes($params['Tipo'])."'";
       }

        //anno rif
        if($params['AnnoRiferimento'] > 0)
        {
            $params['where'][]=" AND ".AA_Sier::AA_DBTABLE_DATA.".anno_rif = '".addslashes($params['AnnoRiferimento'])."'";
        }

        //Estremi
        if($params['Estremi'] !="")
        {
            $params['where'][]=" AND ".AA_Sier::AA_DBTABLE_DATA.".estremi_atto like '%".addslashes($params['Estremi'])."%'";
        }
       
       return $params;
    }

     //Personalizza il template dei dati delle schede pubblicate per il modulo corrente
     protected function GetDataSectionPubblicate_CustomDataTemplate($data = array(),$object=null)
     {
        if($object instanceof AA_Sier)
        {

            /*$data['pretitolo']=$object->GetTipo();
            if($object->GetTipo(true) != AA_Sier_Const::AA_TIPO_PROVVEDIMENTO_ACCORDO)
            {
                $data['tags']="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$object->GetModalita()."</span>";
            } 
            else
            {
                $tag="";
                foreach(explode("|",$object->GetProp('Contraente')) as $value)
                {
                    $tag.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$value."</span>";
                }
                $data['tags']=$tag;
            } */
        }
        
        return $data;
     }

    //Template sezione bozze (da specializzare)
    public function TemplateSection_Bozze($params=array())
    {
        $is_enabled= false;
       
        if($this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
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
        return $content->toObject();
    }
    
    //Restituisce i dati delle bozze
    public function GetDataSectionBozze_List($params=array())
    {
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
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
            $params['where'][]=" AND ".AA_Sier::AA_DBTABLE_DATA.".anno = '".addslashes($params['AnnoRiferimento'])."'";
        }

        return $params;
    }

    //Personalizza il template dei dati delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomDataTemplate($data = array(),$object=null)
    {
        
        if($object instanceof AA_Sier)
        {

            $data['pretitolo']=$object->GetProp("Anno");
            $tag="";
            $flags=$object->GetProp('Flags');
            if($flags==0)
            {
                $tag="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>accesso disabilitato</span>";
            }
            else
            {
                foreach(AA_Sier_Const::GetFlagsForTags() as $key=>$value)
                {
                    if(($flags & $key) > 0) $tag.="<span class='AA_DataView_Tag AA_Label AA_Label_Green'>".$value."</span>";
                }
            }
        }

        $data['tags']=$tag;
        return $data;
    }
    
    //Template organismo publish dlg
    public function Template_GetSierPublishDlg($params)
    {
        //lista organismi da ripristinare
        if($params['ids'])
        {
            $ids= json_decode($params['ids']);
            
            foreach($ids as $curId)
            {
                $organismo=new AA_Sier($curId,$this->oUser);
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
                $wnd->SetSaveTask('PublishSier');
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
    public function Template_GetSierDeleteDlg($params)
    {
        return $this->Template_GetGenericObjectDeleteDlg($params,"DeleteSier");
    }
        
    //Template dlg addnew provvedimenti
    public function Template_GetSierAddNewDlg()
    {
        $id=$this->GetId()."_AddNew_Dlg";
        
        $form_data=array();
        
        $form_data['Note']="";
        $form_data['Anno']=date("Y");
        $form_data['Flags']=0;
        $form_data['nome']="Elezioni regionali ".date("Y");
        $form_data['descrizione']="";
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi elezioni regionali", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(400);
        $wnd->EnableValidation();
              
        $anno_fine=date("Y")+5;
        $anno_start=($anno_fine-10);
        //anno riferimento
        $options=array();
        for($i=$anno_fine; $i>=$anno_start; $i--)
        {
            $options[]=array("id"=>$i, "value"=>$i);
        }
        $wnd->AddSelectField("Anno","Anno",array("required"=>true,"validateFunction"=>"IsSelected","bottomLabel"=>"*Indicare l'anno in cui si dovrebbero svolgere le elezioni.", "placeholder"=>"...","options"=>$options,"value"=>Date('Y')));

        //Nome
        $wnd->AddTextField("nome","Titolo",array("required"=>true, "bottomLabel"=>"*Inserisci il titolo.", "placeholder"=>"es. Nuove elezioni regionali..."));

        //Descrizione
        $label="Descrizione";
        $wnd->AddTextareaField("descrizione",$label,array("bottomLabel"=>"*Breve descrizione.", "placeholder"=>"Inserisci qui la descrizione..."));

        //Note
        $label="Note";
        $wnd->AddTextareaField("Note",$label,array("bottomLabel"=>"*Eventuali annotazioni.", "placeholder"=>"Inserisci qui le note..."));
        
        $wnd->EnableCloseWndOnSuccessfulSave();

        $wnd->SetSaveTask("AddNewSier");
        
        return $wnd;
    }
    
    //Template dlg aggiungi giornata
    public function Template_GetSierAddNewGiornataDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierAddNewGiornataDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array();
        $form_data['giornata']=date("Y-m-d");
        $form_data['affluenza']=0;
        $form_data['risultati']=0;

        $wnd=new AA_GenericFormDlg($id, "Aggiungi giornata", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(480);
        $wnd->SetHeight(350);

        //data
        $wnd->AddDateField("giornata", "Data", array("required"=>true,"bottomLabel" => "*Selezionare una data dal calendario","width"=>350,"labelWidth"=>180));
        $wnd->AddGenericObject(new AA_JSON_Template_Generic(),false);

        //Abilita/disabilita il caricamento dell'affluenza
        $wnd->AddSwitchBoxField("affluenza","Caricamento affluenza",array("onLabel"=>"Abilitato","bottomPadding"=>28,"labelWidth"=>180,"offLabel"=>"Disabilitato","bottomLabel"=>"*Abilita/disabilita il caricamento dell'affluenza."));

        //Abilita/disabilita il caricamento dei risultati
        $wnd->AddSwitchBoxField("risultati","Caricamento risultati",array("onLabel"=>"Abilitato","bottomPadding"=>28,"labelWidth"=>180,"offLabel"=>"Disabilitato","bottomLabel"=>"*Abilita/disabilita il caricamento dei risultati."));
        
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("AddNewSierGiornata");
        
        return $wnd;
    }

    //Template dlg aggiungi giornata
    public function Template_GetSierModifyGiornataDlg($object=null,$data="")
    {
        $id=static::AA_UI_PREFIX."_GetSierModifyGiornataDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $giornate=$object->GetGiornate();

        $form_data=array();
        if($data !="")
        {
            $form_data['giornata']=$data;
            $form_data['affluenza']=$giornate[$data]['affluenza'];
            $form_data['risultati']=$giornate[$data]['risultati'];
            $form_data['old_giornata']=$data;
        }
        else
        {
            $form_data['giornata']=date("Y-m-d");
            $form_data['affluenza']=0;
            $form_data['risultati']=0;
        }

        $wnd=new AA_GenericFormDlg($id, "Modifica giornata", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(480);
        $wnd->SetHeight(350);

        //data
        $wnd->AddDateField("giornata", "Data", array("required"=>true,"bottomLabel" => "*Selezionare una data dal calendario","width"=>350,"labelWidth"=>180));
        $wnd->AddGenericObject(new AA_JSON_Template_Generic(),false);

        //Abilita/disabilita il caricamento dell'affluenza
        $wnd->AddSwitchBoxField("affluenza","Caricamento affluenza",array("onLabel"=>"Abilitato","bottomPadding"=>28,"labelWidth"=>180,"offLabel"=>"Disabilitato","bottomLabel"=>"*Abilita/disabilita il caricamento dell'affluenza."));

        //Abilita/disabilita il caricamento dei risultati
        $wnd->AddSwitchBoxField("risultati","Caricamento risultati",array("onLabel"=>"Abilitato","bottomPadding"=>28,"labelWidth"=>180,"offLabel"=>"Disabilitato","bottomLabel"=>"*Abilita/disabilita il caricamento dei risultati."));
        
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("UpdateSierGiornata");
        
        return $wnd;
    }

    //Template dlg aggiungi lista
    public function Template_GetSierAddNewListaDlg($object=null,$coalizione=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierAddNewListaDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("id_coalizione"=>$coalizione->GetProp('id'));
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi una nuova Lista", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(400);

        //denominazione
        $wnd->AddTextField("denominazione", "Denominazione", array("required"=>true,"labelWidth"=>150,"bottomLabel" => "*Indicare la denominazione della Lista.", "placeholder" => "..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        $section=new AA_FieldSet($id."_Section_Url","Scegliere un'immagine per la Lista.");
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        //file
        $section->AddFileUploadField("NewListaImage","", array("bottomLabel"=>"*Caricare solo immagini in formato jpg o png (dimensione max: 1Mb).","accept"=>"image/jpg,image/png"));
        
        $wnd->AddGenericObject($section);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("AddNewSierLista");
        
        return $wnd;
    }

    //Template dlg aggiungi lista
    public function Template_GetSierModifyListaDlg($object=null,$coalizione=null,$lista=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierModifyListaDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("id_coalizione"=>$coalizione->GetProp('id'),"id_lista"=>$lista->GetProp('id'),"denominazione"=>$lista->GetProp("denominazione"));
        
        $wnd=new AA_GenericFormDlg($id, "Modifica Lista", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(400);

        //denominazione
        $wnd->AddTextField("denominazione", "Denominazione", array("required"=>true,"labelWidth"=>150,"bottomLabel" => "*Indicare la denominazione della Lista.", "placeholder" => "..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        $section=new AA_FieldSet($id."_Section_Url","Scegliere un'immagine per la Lista.");
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        //file
        $section->AddFileUploadField("UpdateListaImage","", array("bottomLabel"=>"*Caricare solo immagini in formato jpg o png (dimensione max: 1Mb).<br>Non selezionare nessun file per mantenere quello corrente.","accept"=>"image/jpg,image/png"));
        
        $wnd->AddGenericObject($section);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("UpdateSierLista");
        
        return $wnd;
    }

    //Template dlg aggiungi Coaiizione
    public function Template_GetSierAddNewCoalizioneDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierAddNewCoalizioneDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi Coalizione", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(480);

        //denominazione
        $wnd->AddTextField("denominazione", "Denominazione", array("required"=>true,"labelWidth"=>150,"bottomLabel" => "*Indicare la denominazione della coalizione.", "placeholder" => "..."));

        //nome candidato
        $wnd->AddTextField("nome_candidato", "Presidente", array("required"=>true,"labelWidth"=>150,"bottomLabel" => "*Indicare il nome e cognome del candidato Presidente.", "placeholder" => "..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        $section=new AA_FieldSet($id."_Section_Url","Scegliere un'immagine per la Coalizione.");
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        //file
        $section->AddFileUploadField("NewCoalizioneImage","", array("bottomLabel"=>"*Caricare solo immagini in formato jpg o png (dimensione max: 1Mb).","accept"=>"image/jpg,image/png"));
        
        $wnd->AddGenericObject($section);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("AddNewSierCoalizione");
        
        return $wnd;
    }

    //Template dlg modifica Coalizione
    public function Template_GetSierModifyCoalizioneDlg($object=null,$coalizione=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierModifyCoalizioneDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array();
        if($coalizione instanceof AA_SierCoalizioni)
        {
            $form_data['denominazione']=$coalizione->GetProp("denominazione");
            $form_data['nome_candidato']=$coalizione->GetProp("nome_candidato");
        }
        else
        {
            AA_Log::Log(__METHOD__." - coalizione non valida: ".print_r($coalizione,true),100);
        }
        
        $wnd=new AA_GenericFormDlg($id, "Modifica Coalizione", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(480);

        //denominazione
        $wnd->AddTextField("denominazione", "Denominazione", array("required"=>true,"labelWidth"=>150,"bottomLabel" => "*Indicare la denominazione della coalizione.", "placeholder" => "..."));

        //nome candidato
        $wnd->AddTextField("nome_candidato", "Presidente", array("required"=>true,"labelWidth"=>150,"bottomLabel" => "*Indicare il nome e cognome del candidato Presidente.", "placeholder" => "..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        $section=new AA_FieldSet($id."_Section_Url","Scegliere un'immagine per la Coalizione.");
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        //file
        $section->AddFileUploadField("NewCoalizioneImage","", array("bottomLabel"=>"*Caricare solo immagini in formato jpg o png (dimensione max: 1Mb).<br>Non selezionare nessun file per mantenere quello corrente.","accept"=>"image/jpg,image/png"));
        
        $wnd->AddGenericObject($section);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_coalizione"=>$coalizione->GetProp("id")));
        $wnd->SetSaveTask("UpdateSierCoalizione");
        
        return $wnd;
    }

    //Template dlg aggiungi candidato
    public function Template_GetSierAddNewCandidatoDlg($object=null,$lista=null,$id_circoscrizione=0)
    {
        $id=static::AA_UI_PREFIX."_GetSierAddNewCandidatoDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("aggiornamento"=>date("Y-m-d"));
        if($lista instanceof AA_SierLista)
        {
            $form_data['id_lista']=$lista->GetProp("id");
        }

        if($id_circoscrizione>0)
        {
            $form_data['id_circoscrizione']=$id_circoscrizione;
        }
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi candidato", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(480);

        //Imposta il controllo su cui abilitare il focus
        $wnd->SetDefaultFocusedItem("cognome");

        //Cognome
        $wnd->AddTextField("cognome", "Cognome", array("required"=>true,"bottomLabel" => "*Indicare il cognome del candidato", "placeholder" => "es. Verdi"));

        //nome
        $wnd->AddTextField("nome", "Nome", array("required"=>true,"bottomLabel" => "*Indicare il nome del candidato", "placeholder" => "es. Giuseppe"));

        //cf
        $wnd->AddTextField("cf", "Codice fiscale", array("bottomLabel" => "*Indicare il codice fisclae del candidato, se presente."));

        //Circoscrizione
        $circoscrizioni=AA_Sier_Const::GetCircoscrizioni();
        $options=array();
        foreach($circoscrizioni as $id=>$descr)
        {
            $options[]=array("id"=>$id,"value"=>$descr);
        }
        $wnd->AddSelectField("id_circoscrizione", "Circoscrizione", array("required"=>true, "validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere una voce dal menu a tendina", "options"=>$options));

        //Lista
        $liste=$object->GetListe();
        $options=array();
        foreach($liste as $id=>$lista)
        {
            $options[]=array("id"=>$id,"value"=>$lista->GetProp("denominazione"));
        }
        $wnd->AddSelectField("id_lista", "Lista", array("required"=>true, "validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere una voce dal menu a tendina", "options"=>$options));
        
        /*
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        $section=new AA_FieldSet($id."_Section_Url","Curriculum - Inserire un'url oppure scegliere un file");
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        //url
        $section->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $section->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<hr/>","height"=>18)));

        //file
        $section->AddFileUploadField("NewCandidatoCV","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf (dimensione max: 2Mb).","accept"=>"application/pdf"));
        
        $wnd->AddGenericObject($section);*/

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("AddNewSierCandidato");
        
        return $wnd;
    }

    //Template dlg aggiungi candidato
    public function Template_GetSierModifyCandidatoDlg($object=null,$candidato=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierModifyCandidatoDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("aggiornamento"=>date("Y-m-d"));
        foreach($candidato->GetProps() as $key=>$val)
        {
            if($key !="id") $form_data[$key]=$val;
        }
        
        $wnd=new AA_GenericFormDlg($id, "Modifica candidato", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(480);

        //Imposta il controllo su cui abilitare il focus
        $wnd->SetDefaultFocusedItem("cognome");

        //Cognome
        $wnd->AddTextField("cognome", "Cognome", array("required"=>true,"bottomLabel" => "*Indicare il cognome del candidato", "placeholder" => "es. Verdi"));

        //nome
        $wnd->AddTextField("nome", "Nome", array("required"=>true,"bottomLabel" => "*Indicare il nome del candidato", "placeholder" => "es. Giuseppe"));

        //cf
        $wnd->AddTextField("cf", "Codice fiscale", array("bottomLabel" => "*Indicare il codice fisclae del candidato, se presente."));
        
        //Circoscrizione
        $circoscrizioni=AA_Sier_Const::GetCircoscrizioni();
        $options=array();
        foreach($circoscrizioni as $id=>$descr)
        {
            $options[]=array("id"=>$id,"value"=>$descr);
        }
        $wnd->AddSelectField("id_circoscrizione", "Circoscrizione", array("required"=>true, "validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere una voce dal menu a tendina", "options"=>$options));

        //Lista
        $liste=$object->GetListe();
        $options=array();
        foreach($liste as $id=>$lista)
        {
            $options[]=array("id"=>$id,"value"=>$lista->GetProp("denominazione"));
        }
        $wnd->AddSelectField("id_lista", "Lista", array("required"=>true, "gravity"=>2,"validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere una voce dal menu a tendina", "options"=>$options));

        //ordine
        $wnd->AddTextField("ordine", "Ordine", array("gravity"=>1,"labelAlign"=>"right","bottomLabel" => "*Posizione nella Lista."),false);

        /*
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        $section=new AA_FieldSet($id."_Section_Url","Curriculum - Inserire un'url oppure scegliere un file");
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        //url
        $section->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $section->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<hr/>","height"=>18)));

        //file
        $section->AddFileUploadField("NewCandidatoCV","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf (dimensione max: 2Mb).","accept"=>"application/pdf"));
        
        $wnd->AddGenericObject($section);*/

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_candidato"=>$candidato->GetProp('id')));
        $wnd->SetSaveTask("UpdateSierCandidato");
        
        return $wnd;
    }

    //Template dlg aggiungi candidato
    public function Template_GetSierAddNewCandidatoCVDlg($object=null,$candidato=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierAddNewCandidatoCVDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("aggiornamento"=>date("Y-m-d"),"id_candidato"=>$candidato->GetProp("id"));
        
        $wnd=new AA_GenericFormDlg($id, "Imposta cv candidato", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(480);

        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_IntroCvCandidato",array("type"=>"clean","autoheight"=>true,"template"=>"<div style='display: flex; justify-content: center; align-items:center; width: 100%;height:100%'><div>Imposta il curriculum per il candidato: <b>".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")."</b>.</div></div>")));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        $section=new AA_FieldSet($id."_Section_Url","Inserire un'url oppure scegliere un file");
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        //url
        $section->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $section->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<hr/>","height"=>18)));

        //file
        $section->AddFileUploadField("NewCandidatoCV","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf (dimensione max: 2Mb).","accept"=>"application/pdf"));
        
        $wnd->AddGenericObject($section);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("AddNewSierCandidatoCV");
        
        return $wnd;
    }

    //Template dlg modifica candidato cv
    public function Template_GetSierModifyCandidatoCVDlg($object=null,$candidato=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierModifyCandidatoCVDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("aggiornamento"=>date("Y-m-d"),"id_candidato"=>$candidato->GetProp("id"));
        if(strpos($candidato->GetProp("cv"),"http") !==false) $form_data['url']=$candidato->GetProp("cv");

        $wnd=new AA_GenericFormDlg($id, "Imposta cv candidato", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(480);

        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_IntroCvCandidato",array("type"=>"clean","autoheight"=>true,"template"=>"<div style='display: flex; justify-content: center; align-items:center; width: 100%;height:100%'><div>Imposta il curriculum per il candidato: <b>".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")."</b>.</div></div>")));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        $section=new AA_FieldSet($id."_Section_Url","Inserire un'url oppure scegliere un file");
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        //url
        $section->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $section->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<hr/>","height"=>18)));

        //file
        $section->AddFileUploadField("NewCandidatoCV","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf (dimensione max: 2Mb).","accept"=>"application/pdf"));
        
        $wnd->AddGenericObject($section);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("UpdateSierCandidatoCV");
        
        return $wnd;
    }

    //Template dlg aggiungi candidato cg
    public function Template_GetSierAddNewCandidatoCGDlg($object=null,$candidato=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierAddNewCandidatoCGDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("aggiornamento"=>date("Y-m-d"),"id_candidato"=>$candidato->GetProp("id"));
        
        $wnd=new AA_GenericFormDlg($id, "Imposta casellario", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(480);

        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_IntroCgCandidato",array("type"=>"clean","autoheight"=>true,"template"=>"<div style='display: flex; justify-content: center; align-items:center; width: 100%;height:100%'><div>Imposta il casellario giudiziale per il candidato: <b>".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")."</b>.</div></div>")));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        $section=new AA_FieldSet($id."_Section_Url","Inserire un'url oppure scegliere un file");
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        //url
        $section->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $section->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<hr/>","height"=>18)));

        //file
        $section->AddFileUploadField("NewCandidatoCG","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf (dimensione max: 2Mb).","accept"=>"application/pdf"));
        
        $wnd->AddGenericObject($section);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("AddNewSierCandidatoCG");
        
        return $wnd;
    }

    //Template dlg modifica candidato cg
    public function Template_GetSierModifyCandidatoCGDlg($object=null,$candidato=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierModifyCandidatoCGDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);

        
        $form_data=array("aggiornamento"=>date("Y-m-d"),"id_candidato"=>$candidato->GetProp("id"));
        if(strpos($candidato->GetProp("cg"),"http") !==false) $form_data['url']=$candidato->GetProp("cg");

        $wnd=new AA_GenericFormDlg($id, "Imposta casellario", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(480);

        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_IntroCgCandidato",array("type"=>"clean","autoheight"=>true,"template"=>"<div style='display: flex; justify-content: center; align-items:center; width: 100%;height:100%'><div>Imposta il casellario giudiziale per il candidato: <b>".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")."</b>.</div></div>")));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        $section=new AA_FieldSet($id."_Section_Url","Inserire un'url oppure scegliere un file");
        $wnd->SetFileUploaderId($id."_Section_Url_FileUpload_Field");

        //url
        $section->AddTextField("url", "Url", array("validateFunction"=>"IsUrl","bottomLabel"=>"*Indicare un'URL sicura, es. https://www.regione.sardegna.it", "placeholder"=>"https://..."));
        
        $section->AddGenericObject(new AA_JSON_Template_Template("",array("type"=>"clean","template"=>"<hr/>","height"=>18)));

        //file
        $section->AddFileUploadField("NewCandidatoCG","", array("validateFunction"=>"IsFile","bottomLabel"=>"*Caricare solo documenti pdf (dimensione max: 2Mb).","accept"=>"application/pdf"));
        
        $wnd->AddGenericObject($section);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("UpdateSierCandidatoCG");
        
        return $wnd;
    }

    //Template dlg aggiungi allegato/link
    public function Template_GetSierAddNewAllegatoDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierAddNewAllegatoDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("aggiornamento"=>date("Y-m-d"));
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi allegato/link", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(540);

        //tipo
        $tipologia=AA_Sier_Const::GetTipoAllegati();
        $options=array();
        foreach($tipologia as $id=>$descr)
        {
            $options[]=array("id"=>$id,"value"=>$descr);
        }
        $wnd->AddSelectField("tipo", "Categoria", array("required"=>true, "validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere una categoria dalla lista", "placeholder" => "...","options"=>$options));

        //descrizione
        $wnd->AddTextField("estremi", "Descrizione", array("required"=>true,"bottomLabel" => "*Indicare una descrizione per l'allegato o il link", "placeholder" => "es. DGR ..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
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
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("AddNewSierAllegato");
        
        return $wnd;
    }

    //Template dlg modifca allegato/link
    public function Template_GetSierModifyAllegatoDlg($object=null,$allegato=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierModifyAllegatoDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array();
        $form_data["estremi"]=$allegato->GetEstremi();
        $form_data["url"]=$allegato->GetUrl();
        $form_data["tipo"]=$allegato->GetTipo();
        $form_data["aggiornamento"]=date("Y-m-d");
        
        $wnd=new AA_GenericFormDlg($id, "Modifica allegato/link", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(540);

        //tipo
        $tipologia=AA_Sier_Const::GetTipoAllegati();
        $options=array();
        foreach($tipologia as $id=>$descr)
        {
            $options[]=array("id"=>$id,"value"=>$descr);
        }
        $wnd->AddSelectField("tipo", "Categoria", array("required"=>true, "validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere una categoria dalla lista", "placeholder" => "...","options"=>$options));

        //descrizione
        $wnd->AddTextField("estremi", "Descrizione", array("required"=>true,"bottomLabel" => "*Indicare una descrizione per l'allegato o il link", "placeholder" => "es. DGR ..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));
        
        
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
        $wnd->SetSaveTask("UpdateSierAllegato");
        
        return $wnd;
    }

    //Template dlg trash allegato
    public function Template_GetSierTrashAllegatoDlg($object=null,$allegato=null)
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
        $wnd->SetSaveTask("DeleteSierAllegato");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_allegato"=>$allegato->GetId()));
        
        return $wnd;
    }

    //Template dlg trash curriculum
    public function Template_GetSierTrashCandidatoCVDlg($object=null,$candidato=null)
    {
        $id=$this->id."_TrashCandidatoCV_Dlg";
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina curriculum", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tipo_doc="link";
        if(strpos($candidato->GetProp("cv"),"http")===false) $tipo_doc="file";
        $tabledata[]=array("denominazione"=>$candidato->GetProp("nome")." ".$candidato->GetProp("cognome"),"tipo_doc"=>$tipo_doc);
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il curriculum per il seguente candidato verrà eliminato, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"denominazione", "header"=>"Candidato", "fillspace"=>true),
              array("id"=>"tipo_doc", "header"=>"tipo doc", "width"=>150)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteSierCandidatoCV");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_candidato"=>$candidato->GetProp('id')));
        
        return $wnd;
    }

    //Template dlg trash casellario
    public function Template_GetSierTrashCandidatoCGDlg($object=null,$candidato=null)
    {
        $id=$this->id."_TrashCandidatoCG_Dlg";
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina casellario", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tipo_doc="link";
        if(strpos($candidato->GetProp("cg"),"http")===false) $tipo_doc="file";
        $tabledata[]=array("denominazione"=>$candidato->GetProp("nome")." ".$candidato->GetProp("cognome"),"tipo_doc"=>$tipo_doc);
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il casellario per il seguente candidato verrà eliminato, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"denominazione", "header"=>"Candidato", "fillspace"=>true),
              array("id"=>"tipo_doc", "header"=>"tipo doc", "width"=>150)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteSierCandidatoCG");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_candidato"=>$candidato->GetProp('id')));
        
        return $wnd;
    }

    //Template dlg trash casellario
    public function Template_GetSierTrashCandidatoDlg($object=null,$candidato=null)
    {
        $id=$this->id."_TrashCandidato_Dlg";
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina informazioni candidato", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("denominazione"=>$candidato->GetProp("nome")." ".$candidato->GetProp("cognome"));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Le informazioni del seguente candidato verrànno eliminate, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"denominazione", "header"=>"Candidato", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteSierCandidato");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_candidato"=>$candidato->GetProp('id')));
        
        return $wnd;
    }

    //Task Aggiungi giornata
    public function Task_AddNewSierGiornata($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetError("Identificativo oggetto non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo oggetto non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if($object->IsReadOnly())
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'oggetto: ".$object->GetProp("titolo"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'oggetto: ".$object->GetProp("titolo")."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $giornata=substr($_REQUEST['giornata'],0,10);
        if(strlen($giornata) != 10)
        {
            $task->SetError("Data non valida");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Data non valida</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }

        $giornate=$object->GetGiornate();
        
        $affluenza=0;
        if($_REQUEST['affluenza'] > 0) $affluenza=1;
        $risultati=0;
        if($_REQUEST['risultati'] > 0) $risultati=1;

        $giornate[$giornata]=array("affluenza"=>$affluenza,"risultati"=>$risultati);
        $object->SetProp("Giornate",json_encode($giornate));
        if(!$object->Update($this->oUser,true,"Aggiunta giornata - ".$_REQUEST['giornata']))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nell'aggiunta della giornata. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Giornata aggiunta con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task modifica giornata
    public function Task_UpdateSierGiornata($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetError("Identificativo oggetto non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo oggetto non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if($object->IsReadOnly())
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'oggetto: ".$object->GetProp("titolo"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'oggetto: ".$object->GetProp("titolo")."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $giornata=substr($_REQUEST['giornata'],0,10);
        if(strlen($giornata) != 10)
        {
            $task->SetError("Data non valida");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Data non valida</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }

        $giornate=$object->GetGiornate();

        //controlla se e' cambiata la data
        if($_REQUEST['giornata']!=$_REQUEST['old_giornata'])
        {
            unset($giornate[$_REQUEST['old_giornata']]);
        }
        
        $affluenza=0;
        if($_REQUEST['affluenza'] > 0) $affluenza=1;
        $risultati=0;
        if($_REQUEST['risultati'] > 0) $risultati=1;

        $giornate[$giornata]=array("affluenza"=>$affluenza,"risultati"=>$risultati);
        ksort($giornate);

        $object->SetProp("Giornate",json_encode($giornate));
        if(!$object->Update($this->oUser,true,"Modifica giornata - ".$_REQUEST['giornata']))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nell'aggiunta della giornata. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Giornata aggiornata con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task modifica giornata
    public function Task_DeleteSierGiornata($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetError("Identificativo oggetto non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo oggetto non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if($object->IsReadOnly())
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'oggetto: ".$object->GetProp("titolo"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'oggetto: ".$object->GetProp("titolo")."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $giornata=substr($_REQUEST['giornata'],0,10);
        if(strlen($giornata) != 10)
        {
            $task->SetError("Data non valida");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Data non valida</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }

        $giornate=$object->GetGiornate();
        if(isset($giornate[$_REQUEST['giornata']]))unset($giornate[$_REQUEST['giornata']]);

        $object->SetProp("Giornate",json_encode($giornate));
        if(!$object->Update($this->oUser,true,"Elimina giornata - ".$_REQUEST['giornata']))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nell'eliminazione della giornata. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Giornata eliminata con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Template dlg trash giornata
    public function Template_GetSierTrashGiornataDlg($object=null,$giornata="")
    {
        $id=$this->id."_TrashSierGiornata_Dlg";
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina giornata", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("giornata"=>$giornata);
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"La seguente giornata verrà eliminata, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"giornata", "header"=>"Data", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteSierGiornata");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"giornata"=>$giornata));
        
        return $wnd;
    }


    //Template dlg trash giornata
    public function Template_GetSierTrashCoalizioneDlg($object=null,$coalizione=null)
    {
        $id=$this->id."_TrashSierCoalizione_Dlg";
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina coalizione", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("coalizione"=>$coalizione->GetProp("denominazione"));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"La seguente Coalizione verrà eliminata, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"coalizione", "header"=>"Denominazione", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteSierCoalizione");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_coalizione"=>$coalizione->GetProp("id")));
        
        return $wnd;
    }

    //Template dlg trash lista
    public function Template_GetSierTrashListaDlg($object=null,$coalizione=null,$lista=null)
    {
        $id=$this->id."_TrashSierLista_Dlg";
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina Lista", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("lista"=>$lista->GetProp("denominazione"));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"La seguente Lista verrà eliminata, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"lista", "header"=>"Denominazione", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteSierLista");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"id_coalizione"=>$coalizione->GetProp("id"),"id_lista"=>$lista->GetProp('id')));
        
        return $wnd;
    }

    //Task Aggiungi allegato
    public function Task_AddNewSierAllegato($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
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
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
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
        $allegato=new AA_SierAllegati(0,$id_sier,$_REQUEST['estremi'],$_REQUEST['url'],$fileHash,$_REQUEST['tipo'],$aggiornamento);
        
        //AA_Log::Log(__METHOD__." - "."Provvedimento: ".print_r($elemento, true),100);
        
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

    //Task Aggiungi candidato
    public function Task_AddNewSierCandidato($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if($object->IsReadOnly())
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $circoscrizione=AA_Sier_Const::GetCircoscrizione($_REQUEST['id_circoscrizione']);
        //AA_Log::Log(__METHOD__." - circoscrizione: ".print_r($circoscrizione,true),100);
        if(!is_array($circoscrizione))
        {
            AA_Log::Log(__METHOD__." - "."Parametri non validi: ".print_r($_REQUEST,true),100);
            $task->SetError("Parametri non validi occorre indicare una circoscrizione valida.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Parametri non validi: occorre indicare una circoscrizione valida.</error>";
            $task->SetLog($sTaskLog);
            
            return false;
        }

        $lista=$object->GetLista($_REQUEST['id_lista']);
        //AA_Log::Log(__METHOD__." - lista: ".print_r($lista,true),100);
        if(!($lista instanceof AA_SierLista))
        {
            AA_Log::Log(__METHOD__." - "."Lista non valida: ".print_r($lista,true)." - parametri".print_r($_REQUEST,true),100);
            $task->SetError("Occorre indicare una lista valida.");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Occorre indicare una lista valida.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $params=array(
            "id_circoscrizione"=>$circoscrizione['id'],
            "id_lista"=>$lista->GetProp("id"),
            "nome"=>$_REQUEST['nome'],
            "cognome"=>$_REQUEST['cognome'],
            "cf"=>$_REQUEST['cf']
        );
        $candidato=new AA_SierCandidato($params);
        
        //AA_Log::Log(__METHOD__." - "."Provvedimento: ".print_r($elemento, true),100);
        $newId=$object->AddNewCandidato($candidato, $this->oUser);
        if($newId===false)
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio del candidato. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $params=array(
            "task"=>"GetSierAddNewCandidatoCVDlg",
            "params"=>array(
                "id"=>$object->Getid(),
                "id_candidato"=>$newId
            )
        );
        $sTaskLog="<status id='status' action='dlg' action_params='".json_encode($params)."'>0</status><content id='content'>";
        $sTaskLog.= "Candidato caricato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task Update candidato
    public function Task_AddNewSierCandidatoCV($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        $uploadedFile = AA_SessionFileUpload::Get("NewCandidatoCV");
        
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
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
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
        
        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if($candidato == null)
        {
            $task->SetError("identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")</error>";
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

        $fileHash=$_REQUEST['url'];
        if($uploadedFile->isValid()) 
        {
            //Se c'è un file uploadato l'url non viene salvata.
            $_REQUEST['url']="";

            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$candidato->GetProp("cv");
                if($oldFile !="" && strpos($oldFile,"http") === false)
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

        //Elimina il cv precedente se presente
        if($_REQUEST['url'] !="" && strpos($candidato->GetProp("cv"),"http")===false && $candidato->GetProp("cv") != "")
        {
            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$candidato->GetProp("cv");
                if($oldFile !="" && strpos($oldFile,"http") === false)
                {
                    if(!$storage->DelFile($oldFile))
                    {
                        AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$oldFile,100);
                    }
                }
            }
        }

        $params=array(
            "id"=>$candidato->GetProp('id'),
            "id_circoscrizione"=>$candidato->GetProp('id_circoscrizione'),
            "id_lista"=>$candidato->GetProp("id_lista"),
            "nome"=>$candidato->GetProp('nome'),
            "cognome"=>$candidato->GetProp('cognome'),
            "cf"=>$candidato->GetProp('cf'),
            "cg"=>$candidato->GetProp('cg'),
            "cv"=>$fileHash
        );
        $candidato=new AA_SierCandidato($params);
        
        //AA_Log::Log(__METHOD__." - candidato: ".print_r($candidato, true),100);

        if(!$object->UpdateCandidato($candidato, $this->oUser,"Aggiornamento del curriculum per: ".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nell'aggiornameto del curriculum. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $params=array(
            "task"=>"GetSierAddNewCandidatoCGDlg",
            "params"=>array(
                "id"=>$object->Getid(),
                "id_candidato"=>$candidato->GetProp("id")
            )
        );
        $sTaskLog="<status id='status' action='dlg' action_params='".json_encode($params)."'>0</status><content id='content'>";
        $sTaskLog.= "Curriculum aggiornato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task aggiungi nuovo casellario candidato
    public function Task_AddNewSierCandidatoCG($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        $uploadedFile = AA_SessionFileUpload::Get("NewCandidatoCG");
        
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
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
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
        
        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if($candidato == null)
        {
            $task->SetError("identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")</error>";
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

        $fileHash=$_REQUEST['url'];
        if($uploadedFile->isValid()) 
        {
            //Se c'è un file uploadato l'url non viene salvata.
            $_REQUEST['url']="";

            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$candidato->GetProp("cg");
                if($oldFile !="" && strpos($oldFile,"http") === false)
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

        //Elimina il cg precedente se presente
        if($_REQUEST['url'] !="" && strpos($candidato->GetProp("cg"),"http")===false && $candidato->GetProp("cg") != "")
        {
            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$candidato->GetProp("cg");
                if($oldFile !="" && strpos($oldFile,"http") === false)
                {
                    if(!$storage->DelFile($oldFile))
                    {
                        AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$oldFile,100);
                    }
                }
            }
        }

        $params=array(
            "id"=>$candidato->GetProp('id'),
            "id_circoscrizione"=>$candidato->GetProp('id_circoscrizione'),
            "id_lista"=>$candidato->GetProp("id_lista"),
            "nome"=>$candidato->GetProp('nome'),
            "cognome"=>$candidato->GetProp('cognome'),
            "cf"=>$candidato->GetProp('cf'),
            "cv"=>$candidato->GetProp('cv'),
            "cg"=>$fileHash
        );
        $candidato=new AA_SierCandidato($params);
        
        //AA_Log::Log(__METHOD__." - candidato: ".print_r($candidato, true),100);

        if(!$object->UpdateCandidato($candidato, $this->oUser,"Aggiornamento del casellario giudiziale per: ".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nell'aggiornamento del casellario. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Casellario aggiornato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task modifica casellario candidato
    public function Task_UpdateSierCandidato($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if($object->IsReadOnly())
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if($candidato == null)
        {
            $task->SetError("identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $ordine=$candidato->GetProp('ordine');
        if($_REQUEST['ordine'] > 0) $ordine=$_REQUEST['ordine'];

        $params=array(
            "id"=>$candidato->GetProp('id'),
            "id_circoscrizione"=>$_REQUEST['id_circoscrizione'],
            "id_lista"=>$_REQUEST['id_lista'],
            "nome"=>$_REQUEST['nome'],
            "cognome"=>$_REQUEST['cognome'],
            "cf"=>$_REQUEST['cf'],
            "cv"=>$candidato->GetProp('cv'),
            "cg"=>$candidato->GetProp('cg'),
            "ordine"=>$ordine
        );
        $candidato=new AA_SierCandidato($params);
        
        //AA_Log::Log(__METHOD__." - candidato: ".print_r($candidato, true),100);

        if(!$object->UpdateCandidato($candidato, $this->oUser,"Aggiornamento dati del candidato: ".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nell'aggiornamento dei dati del candidato. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "dati Candidato aggiornati con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task modifica casellario candidato
    public function Task_UpdateSierCandidatoCG($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        $uploadedFile = AA_SessionFileUpload::Get("NewCandidatoCG");
        
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
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
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
        
        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if($candidato == null)
        {
            $task->SetError("identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")</error>";
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

        $fileHash=$_REQUEST['url'];
        if($uploadedFile->isValid()) 
        {
            //Se c'è un file uploadato l'url non viene salvata.
            $_REQUEST['url']="";

            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$candidato->GetProp("cg");
                if($oldFile !="" && strpos($oldFile,"http") === false)
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

        //Elimina il cg precedente se presente
        if($_REQUEST['url'] !="" && strpos($candidato->GetProp("cg"),"http")===false && $candidato->GetProp("cg") != "")
        {
            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$candidato->GetProp("cg");
                if($oldFile !="" && strpos($oldFile,"http") === false)
                {
                    if(!$storage->DelFile($oldFile))
                    {
                        AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$oldFile,100);
                    }
                }
            }
        }

        $params=array(
            "id"=>$candidato->GetProp('id'),
            "id_circoscrizione"=>$candidato->GetProp('id_circoscrizione'),
            "id_lista"=>$candidato->GetProp("id_lista"),
            "nome"=>$candidato->GetProp('nome'),
            "cognome"=>$candidato->GetProp('cognome'),
            "cf"=>$candidato->GetProp('cf'),
            "cv"=>$candidato->GetProp('cv'),
            "cg"=>$fileHash
        );
        $candidato=new AA_SierCandidato($params);
        
        //AA_Log::Log(__METHOD__." - candidato: ".print_r($candidato, true),100);

        if(!$object->UpdateCandidato($candidato, $this->oUser,"Aggiornamento del casellario giudiziale per: ".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nell'aggiornamento del casellario. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Casellario aggiornato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task modifica cv candidato
    public function Task_UpdateSierCandidatoCV($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        $uploadedFile = AA_SessionFileUpload::Get("NewCandidatoCV");
        
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
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
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
        
        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if($candidato == null)
        {
            $task->SetError("identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")</error>";
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

        $fileHash=$_REQUEST['url'];
        if($uploadedFile->isValid()) 
        {
            //Se c'è un file uploadato l'url non viene salvata.
            $_REQUEST['url']="";

            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$candidato->GetProp("cv");
                if($oldFile !="" && strpos($oldFile,"http") === false)
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

        //Elimina il cg precedente se presente
        if($_REQUEST['url'] !="" && strpos($candidato->GetProp("cv"),"http")===false && $candidato->GetProp("cv") != "")
        {
            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$candidato->GetProp("cv");
                if($oldFile !="" && strpos($oldFile,"http") === false)
                {
                    if(!$storage->DelFile($oldFile))
                    {
                        AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$oldFile,100);
                    }
                }
            }
        }

        $params=array(
            "id"=>$candidato->GetProp('id'),
            "id_circoscrizione"=>$candidato->GetProp('id_circoscrizione'),
            "id_lista"=>$candidato->GetProp("id_lista"),
            "nome"=>$candidato->GetProp('nome'),
            "cognome"=>$candidato->GetProp('cognome'),
            "cf"=>$candidato->GetProp('cf'),
            "cg"=>$candidato->GetProp('cg'),
            "cv"=>$fileHash
        );
        $candidato=new AA_SierCandidato($params);
        
        //AA_Log::Log(__METHOD__." - candidato: ".print_r($candidato, true),100);

        if(!$object->UpdateCandidato($candidato, $this->oUser,"Aggiornamento del curriculum giudiziale per: ".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nell'aggiornamento del curriculum. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Curriculum aggiornato con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task elimina cv candidato
    public function Task_DeleteSierCandidatoCV($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if($object->IsReadOnly())
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if($candidato == null)
        {
            $task->SetError("identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        //Elimina il cg precedente se presente
        if(strpos($candidato->GetProp("cv"),"http")===false && $candidato->GetProp("cv") != "")
        {
            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$candidato->GetProp("cv");
                if($oldFile !="" && strpos($oldFile,"http") === false)
                {
                    if(!$storage->DelFile($oldFile))
                    {
                        AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$oldFile,100);
                    }
                }
            }
        }

        $params=array(
            "id"=>$candidato->GetProp('id'),
            "id_circoscrizione"=>$candidato->GetProp('id_circoscrizione'),
            "id_lista"=>$candidato->GetProp("id_lista"),
            "nome"=>$candidato->GetProp('nome'),
            "cognome"=>$candidato->GetProp('cognome'),
            "cf"=>$candidato->GetProp('cf'),
            "cg"=>$candidato->GetProp('cg'),
            "cv"=>""
        );
        $candidato=new AA_SierCandidato($params);
        
        //AA_Log::Log(__METHOD__." - candidato: ".print_r($candidato, true),100);

        if(!$object->UpdateCandidato($candidato, $this->oUser,"Rimozione del curriculum per: ".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nella rimozione del curriculum. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Curriculum rimosso con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task elimina cv candidato
    public function Task_DeleteSierCandidatoCG($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if($object->IsReadOnly())
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if($candidato == null)
        {
            $task->SetError("identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        //Elimina il cg precedente se presente
        if(strpos($candidato->GetProp("cg"),"http")===false && $candidato->GetProp("cg") != "")
        {
            $storage=AA_Storage::GetInstance($this->oUser);
            if($storage->IsValid())
            {
                //Se l'allegato era sullo storage lo elimina
                $oldFile=$candidato->GetProp("cg");
                if($oldFile !="" && strpos($oldFile,"http") === false)
                {
                    if(!$storage->DelFile($oldFile))
                    {
                        AA_Log::Log(__METHOD__." - errore nella rimozione del file: ".$oldFile,100);
                    }
                }
            }
        }

        $params=array(
            "id"=>$candidato->GetProp('id'),
            "id_circoscrizione"=>$candidato->GetProp('id_circoscrizione'),
            "id_lista"=>$candidato->GetProp("id_lista"),
            "nome"=>$candidato->GetProp('nome'),
            "cognome"=>$candidato->GetProp('cognome'),
            "cf"=>$candidato->GetProp('cf'),
            "cv"=>$candidato->GetProp('cv'),
            "cg"=>""
        );
        $candidato=new AA_SierCandidato($params);
        
        //AA_Log::Log(__METHOD__." - candidato: ".print_r($candidato, true),100);

        if(!$object->UpdateCandidato($candidato, $this->oUser,"Rimozione del casellario per: ".$candidato->GetProp("nome")." ".$candidato->GetProp("cognome")))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nella rimozione del casellario. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Casellario rimosso con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task elimina cv candidato
    public function Task_DeleteSierCandidato($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        if($object->IsReadOnly())
        {
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
            $task->SetLog($sTaskLog);

            return false;            
        }
        
        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if($candidato == null)
        {
            $task->SetError("identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>identificativo Candidato non valido. (".$_REQUEST['id_candidato'].")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }        
        
        if(!$object->DeleteCandidato($candidato, $this->oUser))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nella rimozione delle informazioni del candidato. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Informazioni candidato rimosse con successo.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task Aggiungi Lista
    public function Task_AddNewSierLista($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        $fileUpload = AA_SessionFileUpload::Get("NewListaImage");

        if(!$object->isValid())
        {
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
            $task->SetLog($sTaskLog);
            
            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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

        //Verifica coalizione
        $coalizione=$object->GetCoalizione($_REQUEST['id_coalizione']);
        if(!($coalizione instanceof AA_SierCoalizioni))
        {
            $task->SetError("Coalizione non valida");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Coalizione non valida</error>";
            $task->SetLog($sTaskLog);
            
            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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
        
        $imageFileHash="";
        $compliance=true;
        
        if($fileUpload->isValid())
        {   
            $file=$fileUpload->GetValue();

            //Verifica che l'immagine rispetti le specifiche (png o jpg, 1mb max)
            if($file['type'] != "image/png" && ($file['type'] != "image/jpeg" && $file['type'] != "image/jpg"))
            {
                $compliance=false;
            }

            if(filesize($file['tmp_name']) > 1024*1024)
            {
                $compliance=false;
            }

            if($compliance)
            {
                //salva l'immagine della lista
                $storage=AA_Storage::GetInstance($this->oUser);
                if($storage->IsValid())
                {
                    $storageFile=$storage->AddFile($file['tmp_name'],$file['name'],$file['type'],1);
                    if(!$storageFile->isValid())
                    {
                        AA_Log::Log(__METHOD__." - Errore durante il salvataggio del file nello storage, immagine non salvata. ".print_r($storageFile,true),100);
                    }
                    else $imageFileHash=$storageFile->GetFileHash();
                }
                else AA_Log::Log(__METHOD__." - Storage non valido, immagine non salvata",100);
            }

            //elimina il file temporaneo (se presente)
            if(file_exists($file['tmp_name']))
            {
                if(!unlink($file['tmp_name']))
                {
                    AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                }
            }
        }
        
        //Nuova lista
        $params=array(
            'id_coalizione'=>$coalizione->GetProp('id'),
            'denominazione'=>$_REQUEST['denominazione'],
            'image'=>$imageFileHash
        );

        $lista=new AA_SierLista($params);
        if(!($lista instanceof AA_SierLista))
        {
            $task->SetError("dati Lista non validi");
            $sTaskLog="<status id='status'>-1</status><error id='error'>dati Lista non validi</error>";
            $task->SetLog($sTaskLog);
            
            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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

        if(!$object->AddNewLista($lista,$coalizione,$this->oUser))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio della lista. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        if($compliance) $sTaskLog.= "Lista aggiunta con successo.";
        else $sTaskLog.= "Lista aggiunta con successo. Immagine non salvata in quanto non conforme alle specifiche.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task aggiorna Lista
    public function Task_UpdateSierLista($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        $fileUpload = AA_SessionFileUpload::Get("UpdateListaImage");

        if(!$object->isValid())
        {
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
            $task->SetLog($sTaskLog);
            
            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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

        //Verifica coalizione
        $coalizione=$object->GetCoalizione($_REQUEST['id_coalizione']);
        if(!($coalizione instanceof AA_SierCoalizioni))
        {
            $task->SetError("Coalizione non valida");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Coalizione non valida</error>";
            $task->SetLog($sTaskLog);
            
            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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

        //verifica lista
        $liste=$coalizione->GetListe();
        if(!($liste[$_REQUEST['id_lista']] instanceof AA_SierLista))
        {
            $task->SetError("Lista non valida");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Lista non valida</error>";
            $task->SetLog($sTaskLog);
            
            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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

        $lista=$liste[$_REQUEST['id_lista']];

        $imageFileHash=$lista->GetProp("image");
        $compliance=true;
        
        if($fileUpload->isValid())
        {   
            $file=$fileUpload->GetValue();

            //Verifica che l'immagine rispetti le specifiche (png o jpg, 1mb max)
            if($file['type'] != "image/png" && ($file['type'] != "image/jpeg" && $file['type'] != "image/jpg"))
            {
                $compliance=false;
            }

            if(filesize($file['tmp_name']) > 1024*1024)
            {
                $compliance=false;
            }

            if($compliance)
            {
                //salva l'immagine della lista
                $storage=AA_Storage::GetInstance($this->oUser);
                if($storage->IsValid())
                {
                    if($lista->GetProp('image') !="")
                    {
                        $oldFile=$storage->GetFileByHash($lista->GetProp('image'));
                        if($oldFile->IsValid())
                        {
                            $storage->DelFile($oldFile);
                        }
                    }
    
                    $storageFile=$storage->AddFile($file['tmp_name'],$file['name'],$file['type'],1);
                    if(!$storageFile->isValid())
                    {
                        AA_Log::Log(__METHOD__." - Errore durante il salvataggio del file nello storage, immagine non salvata. ".print_r($storageFile,true),100);
                    }
                    else $imageFileHash=$storageFile->GetFileHash();
                }
                else AA_Log::Log(__METHOD__." - Storage non valido, immagine non salvata",100);
            }

            //elimina il file temporaneo (se presente)
            if(file_exists($file['tmp_name']))
            {
                if(!unlink($file['tmp_name']))
                {
                    AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                }
            }
        }
        
        //Nuova lista
        $params=array(
            'id'=>$lista->GetProp('id'),
            'id_coalizione'=>$coalizione->GetProp('id'),
            'denominazione'=>$_REQUEST['denominazione'],
            'image'=>$imageFileHash
        );

        $lista=new AA_SierLista($params);
        if(!($lista instanceof AA_SierLista))
        {
            $task->SetError("dati Lista non validi");
            $sTaskLog="<status id='status'>-1</status><error id='error'>dati Lista non validi</error>";
            $task->SetLog($sTaskLog);
            
            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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

        //AA_Log::Log(__METHOD__." - lista: ".print_r($lista,true),100);

        if(!$object->UpdateLista($lista,$coalizione,$this->oUser))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio della lista. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        if($compliance) $sTaskLog.= "Lista aggiornata con successo.";
        else $sTaskLog.= "Lista aggiornata con successo. Immagine non salvata in quanto non conforme alle specifiche.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task Aggiungi coalizione
    public function Task_AddNewSierCoalizione($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        $fileUpload = AA_SessionFileUpload::Get("NewCoalizioneImage");

        if(!$object->isValid())
        {
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi"));
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetProp("estremi")."</error>";
            $task->SetLog($sTaskLog);
            
            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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
        
        $imageFileHash="";
        $compliance=true;
        
        if($fileUpload->isValid())
        {   
            $file=$fileUpload->GetValue();

            //Verifica che l'immagine rispetti le specifiche (png o jpg, 1mb max)
            if($file['type'] != "image/png" && ($file['type'] != "image/jpeg" && $file['type'] != "image/jpg"))
            {
                $compliance=false;
            }

            if(filesize($file['tmp_name']) > 1024*1024)
            {
                $compliance=false;
            }

            if($compliance)
            {
                //salva l'immagine della coalizione
                $storage=AA_Storage::GetInstance($this->oUser);
                if($storage->IsValid())
                {
                    $storageFile=$storage->AddFile($file['tmp_name'],$file['name'],$file['type'],1);
                    if(!$storageFile->isValid())
                    {
                        AA_Log::Log(__METHOD__." - Errore durante il salvataggio del file nello storage, immagine non salvata. ".print_r($storageFile,true),100);
                    }
                    else $imageFileHash=$storageFile->GetFileHash();
                }
                else AA_Log::Log(__METHOD__." - Storage non valido, immagine non salvata",100);
            }

            //elimina il file temporaneo (se presente)
            if(file_exists($file['tmp_name']))
            {
                if(!unlink($file['tmp_name']))
                {
                    AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                }
            }
        }
        
        $id_sier=$object->GetIdData();
        if($object->GetIdDataRev() > 0)
        {
            $id_sier=$object->GetIdDataRev();
        }
        
        //Nuova coalizione
        $params=array(
            'id_sier'=>$id_sier,
            'denominazione'=>$_REQUEST['denominazione'],
            'nome_candidato'=>$_REQUEST['nome_candidato'],
            'image'=>$imageFileHash
        );

        $newCoalizione=new AA_SierCoalizioni($params);

        if(!$object->AddNewCoalizione($newCoalizione, $this->oUser))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nel salvataggio della coalizione. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        if($compliance) $sTaskLog.= "Coalizione aggiunta con successo.";
        else $sTaskLog.= "Coalizione aggiunta con successo. Immagine non salvata in quanto non conforme alle specifiche.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task aggiorna coalizione
    public function Task_UpdateSierCoalizione($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_REQUEST['id'], $this->oUser);
        $fileUpload = AA_SessionFileUpload::Get("NewCoalizioneImage");

        if(!$object->isValid())
        {
            $task->SetError("Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Identificativo elemento non valido o permessi insufficienti. (".$_REQUEST['id'].")</error>";
            $task->SetLog($sTaskLog);

            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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
            $task->SetError("L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->Getid());
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente (".$this->oUser->GetName().") non ha i privileggi per modificare l'elemento: ".$object->GetId()."</error>";
            $task->SetLog($sTaskLog);
            
            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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
        
        if(!isset($_REQUEST['id_coalizione']) || $_REQUEST['id_coalizione'] == "")
        {
            $task->SetError("Coalizione non valida");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Coalizione non valida.</error>";
            $task->SetLog($sTaskLog);

            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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

        $params=array("id_coalizione"=>$_REQUEST['id_coalizione']);
        $coalizioni=$object->GetCoalizioni($params);
        //AA_Log::Log(__METHOD__." - coalizioni: ".print_r($coalizioni,true),100);

        if(sizeof($coalizioni)==0)
        {
            $task->SetError("Coalizione non valida");
            $sTaskLog="<status id='status'>-1</status><error id='error'>Coalizione non valida.</error>";
            $task->SetLog($sTaskLog);

            //Elimina il file temporaneo
            if($fileUpload->isValid())
            {   
                $file=$fileUpload->GetValue();
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

        $coalizione=$coalizioni[$_REQUEST['id_coalizione']];
        //AA_Log::Log(__METHOD__." - curCoalizione: ".print_r($coalizione,true),100);
        $imageFileHash="";

        $fileUpload = AA_SessionFileUpload::Get("NewCoalizioneImage");
        $compliance=true;
        if($fileUpload->isValid())
        {   
            $file=$fileUpload->GetValue();
            $imageFileHash=hash_file("sha256",$file['tmp_name']);
            
            //Verifica che l'immagine rispetti le specifiche (png o jpg, 1mb max)
            if($file['type'] != "image/png" && ($file['type'] != "image/jpeg" && $file['type'] != "image/jpg"))
            {
                AA_Log::Log(__METHOD__." - tipo file non conforme: ".$file['type'],100);
                $compliance=false;
            }

            if(filesize($file['tmp_name']) > 1024*1024)
            {
                AA_Log::Log(__METHOD__." - dimensione file non conforme: ".$file['size'],100);
                $compliance=false;
            }

            //Verifica che l'immagine sia cambiata
            if($coalizione->GetProp("image") == $imageFileHash)
            {
                AA_Log::Log(__METHOD__." - i file sono uguali: old: ".$coalizione->GetProp("image")." - new: ".$imageFileHash,100);
                $compliance=false;
            }

            if($compliance)
            {
                //salva l'immagine della coalizione
                $storage=AA_Storage::GetInstance($this->oUser);
                if($storage->IsValid())
                {
                    //Elimina l'immagine precedente
                    if($coalizione->GetProp("image") !="")
                    {
                        $oldImage=$storage->GetFileByHash($coalizione->GetProp("image"));
                        if($oldImage->IsValid())
                        {
                            if(!$storage->DelFile($oldImage))
                            {
                                AA_Log::Log(__METHOD__." - Errore durante la cancellazione della vecchia immagine. ".print_r($oldImage,true),100);

                                return false;
                            }
                        }
                    }

                    $storageFile=$storage->AddFile($file['tmp_name'],$file['name'],$file['type'],1);
                    if(!$storageFile->isValid())
                    {
                        AA_Log::Log(__METHOD__." - Errore durante il salvataggio del file nello storage, immagine non salvata. ".print_r($storageFile,true),100);
                    }
                    else
                    {
                        $imageFileHash=$storageFile->GetFileHash();
                    }
                    
                }
                else AA_Log::Log(__METHOD__." - Storage non valido, immagine non cambiata",100);
            }
            else $imageFileHash="";

            //elimina il file temporaneo (se presente)
            if(file_exists($file['tmp_name']))
            {
                //AA_Log::Log(__METHOD__." - Elimino il file temporaneo. ".$file['tmp_name'],100);
                if(!unlink($file['tmp_name']))
                {
                    AA_Log::Log(__METHOD__." - Errore nella rimozione del file temporaneo. ".$file['tmp_name'],100);
                }
            }
        }
        
        $id_sier=$object->GetIdData();
        if($object->GetIdDataRev() > 0)
        {
            $id_sier=$object->GetIdDataRev();
        }

        //Nuova coalizione

        $params=array(
            'id'=>$_REQUEST['id_coalizione'],
            'id_sier'=>$id_sier,
            'denominazione'=>$_REQUEST['denominazione'],
            'nome_candidato'=>$_REQUEST['nome_candidato']
        );

        $params['image'] = $imageFileHash;
        if($imageFileHash == "") $params['image']=$coalizione->GetProp('image');

        $newCoalizione=new AA_SierCoalizioni($params);
        //AA_Log::Log(__METHOD__." - newCoalizione: ".print_r($newCoalizione,true),100);

        if(!$object->UpdateCoalizione($newCoalizione, $this->oUser))
        {        
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore nell'aggiornamento della coalizione. (".AA_Log::$lastErrorLog.")</error>";
            $task->SetLog($sTaskLog);

            return false;       
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        if($compliance) $sTaskLog.= "Coalizione aggiornata con successo.";
        else $sTaskLog.= "Coalizione aggiornata con successo. Immagine non modificata.";
        $sTaskLog.="</content>";
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task elimina coalizione
    public function Task_DeleteSierCoalizione($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid() || $object->GetId()<=0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>elemento non valido o permessi insufficienti.</error>";
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
 
        if(!$object->DeleteCoalizione($_REQUEST['id_coalizione'],$this->oUser))
        {   
            $task->SetError("Errore durante l'eliminazione della coalizione: ".$_REQUEST['id_coalizione']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore durante l'eliminazione della coalizione: ".$_REQUEST['id_coalizione']."</error>";
            $task->SetLog($sTaskLog);
            
            return false;
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Coalizione eliminata con successo.";
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }

    //Task elimina lista
    public function Task_DeleteSierLista($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid() || $object->GetId()<=0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>elemento non valido o permessi insufficienti.</error>";
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
 
        if(!$object->DeleteLista($_REQUEST['id_lista'],$_REQUEST['id_coalizione'],$this->oUser))
        {   
            $task->SetError("Errore durante l'eliminazione della lista: ".$_REQUEST['id_lista']);
            $sTaskLog="<status id='status'>-1</status><error id='error'>Errore durante l'eliminazione della lista: ".$_REQUEST['id_coalizione']."</error>";
            $task->SetLog($sTaskLog);
            
            return false;
        }
        
        $sTaskLog="<status id='status'>0</status><content id='content'>";
        $sTaskLog.= "Lista eliminata con successo.";
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }

    //Template dlg modify sier
    public function Template_GetSierModifyDlg($object=null)
    {
        $id=$this->GetId()."_Modify_Dlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Modifica i dati generali", $this->id);

        $form_data['id']=$object->GetID();
        $form_data['nome']=$object->GetName();
        $form_data['descrizione']=$object->GetDescr();

        $flags=$object->GetAbilitazioni();
        foreach(AA_Sier_Const::GetFlags() as $key=>$value)
        {
            if(($flags & $key) >0) $form_data[$key]=1;
            else $form_data[$key] = 0;
        }

        foreach($object->GetDbBindings() as $prop=>$field)
        {
            $form_data[$prop]=$object->GetProp($prop);
        }
        
        $wnd=new AA_GenericFormDlg($id, "Modifica i dati generali", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(1024);
        $wnd->SetHeight(640);
        
        $anno_fine=date("Y")+5;
        $anno_start=($anno_fine-10);
        //anno riferimento
        $options=array();
        for($i=$anno_fine; $i>=$anno_start; $i--)
        {
            $options[]=array("id"=>$i, "value"=>$i);
        }
        $wnd->AddSelectField("Anno","Anno",array("required"=>true,"width"=>200,"validateFunction"=>"IsSelected","tooltip"=>"*Indicare l'anno in cui si dovrebbero svolgere le elezioni.", "placeholder"=>"...","options"=>$options));

        //titolo
        $wnd->AddTextField("nome","Titolo",array("required"=>true, "bottomLabel"=>"*Inserisci il titolo.", "placeholder"=>"es. Nuove elezioni regionali..."),false);

        //Descrizione
        $label="Descrizione";
        $wnd->AddTextareaField("descrizione",$label,array("bottomLabel"=>"*Breve descrizione.", "placeholder"=>"Inserisci qui la descrizione..."));

        //Note
        $label="Note";
        $wnd->AddTextareaField("Note",$label,array("bottomLabel"=>"*Eventuali annotazioni.", "placeholder"=>"Inserisci qui le note..."));

        $abilitazioni = new AA_FieldSet("AA_SIER_ABILITAZIONI","Abilitazioni");

        //Accesso operatori
        $abilitazioni->AddSwitchBoxField(AA_Sier_Const::AA_SIER_FLAG_ACCESSO_OPERATORI,"Accesso operatori",array("onLabel"=>"Abilitato","labelWidth"=>150,"offLabel"=>"Disabilitato","bottomLabel"=>"*Stato accesso operatori comunali."));

        //Modifica info generali
        $abilitazioni->AddSwitchBoxField(AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_DATIGENERALI,"Info generali",array("onLabel"=>"Abilitato","bottomPadding"=>28,"labelWidth"=>150,"offLabel"=>"Disabilitato","bottomLabel"=>"*Abilita/disabilita la modifica info generali del comune."));

        //Caricamento corpo elettorale
        $abilitazioni->AddSwitchBoxField(AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_CORPO_ELETTORALE,"Corpo elettorale",array("onLabel"=>"Abilitato","bottomPadding"=>28,"labelWidth"=>150,"offLabel"=>"Disabilitato","bottomLabel"=>"*Abilita/disabilita il caricamento dati corpo elettorale."),false);

        //Affluenza
        $abilitazioni->AddSwitchBoxField(AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_AFFLUENZA,"Affluenza",array("onLabel"=>"Abilitato","bottomPadding"=>28,"labelWidth"=>150,"offLabel"=>"Disabilitato","bottomLabel"=>"*Abilita/disabilita il caricamento dell'affluenza."));

        //Risultati
        $abilitazioni->AddSwitchBoxField(AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI,"Risultati",array("onLabel"=>"Abilitato","bottomPadding"=>28,"labelWidth"=>150,"offLabel"=>"Disabilitato","bottomLabel"=>"*Abilita/disabilita il caricamento dei risultati."),false);

        //esportazione Affluenza
        $abilitazioni->AddSwitchBoxField(AA_Sier_Const::AA_SIER_FLAG_EXPORT_AFFLUENZA,"Esporta affluenza",array("onLabel"=>"Abilitato","bottomPadding"=>28,"labelWidth"=>150,"offLabel"=>"Disabilitato","bottomLabel"=>"*Abilita/disabilita l'esportazione dell'affluenza."));

        //esportazione Risultati
        $abilitazioni->AddSwitchBoxField(AA_Sier_Const::AA_SIER_FLAG_EXPORT_RISULTATI,"Esporta risultati",array("onLabel"=>"Abilitato","bottomPadding"=>28,"labelWidth"=>150,"offLabel"=>"Disabilitato","bottomLabel"=>"*Abilita/disabilita l'esportazione dei risultati."),false);

        $wnd->AddGenericObject($abilitazioni);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("UpdateSier");
        
        return $wnd;
    }  

    //Template detail (da specializzare)
    public function TemplateSection_Detail($params)
    {
        //Gestione dei tab
        //$id=static::AA_UI_PREFIX."_Detail_Generale_Tab_".$params['id'];
        //$params['DetailOptionTab']=array(array("id"=>$id, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateSierDettaglio_Generale_Tab"));
        
        return $this->TemplateGenericSection_Detail($params);
    }   
    
    //Template section detail, tab generale
    public function TemplateSierDettaglio_Generale_Tab($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX;

        if(!($object instanceof AA_Sier)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
        $rows_fixed_height=50;
        $canModify=false;
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;

        $layout=$this->TemplateGenericDettaglio_Header_Generale_Tab($object,$id);

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
            "data"=>array("title"=>"Anno:","value"=>$value)
        ));
        
        //note
        $value = $object->GetProp("Note");
        $note=new AA_JSON_Template_Template($id."_Note",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Note:","value"=>$value)
        ));
        
        //prima riga
        $riga=new AA_JSON_Template_Layout($id."_FirstRow",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($anno_rif);
        $riga->AddCol($this->TemplateDettaglio_Abilitazioni($object,$id."_Abilitazioni"));
        $layout->AddRow($riga);

        //seconda riga
        $riga=new AA_JSON_Template_Layout($id."_SecondRow",array("gravity"=>1,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $layout_gen=new AA_JSON_Template_Layout($id."_DescrNoteLayout",array("gravity"=>3,"type"=>"clean"));
        $layout_gen->addRow($descr);
        $layout_gen->addRow($note);
        $riga->addCol($layout_gen);

        //$riga->addCol($this->TemplateDettaglio_Allegati($object,$id,$canModify));
        $riga->addCol($this->TemplateDettaglio_Giornate($object,$id,$canModify));

        //$layout->AddRow($riga);

        //terza riga
        //$riga=new AA_JSON_Template_Layout($id."_ThirdRow",array("gravity"=>1));
      

        $layout->AddRow($riga);

        return $layout;
    }

    //Template section detail, tab generale
    public function TemplateSierDettaglio_Allegati_Tab($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_ALLEGATI_BOX;

        if(!($object instanceof AA_Sier)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
        $rows_fixed_height=50;
        $canModify=false;
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;

        #documenti----------------------------------
        $curId=$id;
        $layout=new AA_JSON_Template_Layout($curId,array("type"=>"clean","gravity"=>4,"css"=>array("border-left"=>"1px solid gray !important;","border-top"=>"1px solid gray !important;")));

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
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi allegato o link",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierAddNewAllegatoDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
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
            
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSierTrashAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSierModifyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            if($canModify) $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Vedi' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $ops="<div class='AA_DataTable_Ops' style='justify-content: center'><a class='AA_DataTable_Ops_Button' title='Vedi' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a></div>";
            $documenti_data[]=array("id"=>$id_doc,"estremi"=>$curDoc->GetEstremi(),"tipoDescr"=>$curDoc->GetTipoDescr(),"tipo"=>$curDoc->GetTipo(),"aggiornamento"=>$curDoc->GetAggiornamento(),"ops"=>$ops);
        }
        $documenti->SetProp("data",$documenti_data);
        if(sizeof($documenti_data) > 0) $layout->AddRow($documenti);
        else $layout->AddRow(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        #--------------------------------------
        
        return $layout;
    }

    //Template dettaglio riepilogo nomine
    public function TemplateDettaglio_Coalizioni_Riepilogo_Tab($object=null,$id="",$riepilogo_data=array())
    {
        //permessi
        $perms = $object->GetUserCaps($this->oUser);
        $canModify=false;
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;
        
        $riepilogo_layout=new AA_JSON_Template_Layout($id."_Riepilogo_Layout",array("type"=>"clean"));

        if(is_array($riepilogo_data) && sizeof($riepilogo_data) > 0)
        {
            $riepilogo_template="<div style='display: flex; justify-content: space-between; align-items: center; height: 100%'><div style='display: flex; align-items: center; height: 98%; width: auto%; margin-left: 1em;'>"
            ."<img src='#image#' width='100px'/><div style='height: 100%; display: flex; flex-direction: column; align-items: flex-start; justify-content: space-evenly; margin-left: 1em;'><span class='AA_DataView_ItemTitle'>#denominazione#</span><span>Presidente: <b>#presidente#</b></span></div></div>"
            ."<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%; width: 120px; padding: 5px'><a title='Visualizza i dettagli' onclick='#onclick#' class='AA_Button_Link'><span class='mdi mdi-card-account-details'></span>&nbsp;<span>Dettagli</span></a></div></div>";
            $riepilogo_tab=new AA_JSON_Template_Generic($id."_Riepilogo_Tab",array(
                "view"=>"dataview",
                "filtered"=>true,
                "xCount"=>1,
                "module_id"=>$this->id,
                "tabbar"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_LISTE_BOX."_TabBar",
                "type"=>array(
                    "type"=>"tiles",
                    "height"=>150,
                    "width"=>"auto",
                    "css"=>"AA_DataView_Nomine_item",
                ),
                "template"=>$riepilogo_template,
                "data"=>$riepilogo_data,
                "eventHandlers"=>array("onItemDblClick"=>array("handler"=>"CoalizioneDblClick","module_id"=>$this->GetId()))
            ));
        }
        else
        {
            if($canModify) $riepilogo_tab=new AA_JSON_Template_Template($id."_Riepilogo_Tab",array("template"=>"<div style='display: flex; justify-content: center; align-items: center; width: 100%;height:100%'><div>Non sono presenti elementi, fai click sul pulsante 'Aggiungi' per aggiungerne.</div></div>"));
            else $riepilogo_tab=new AA_JSON_Template_Template($id."_Riepilogo_Tab",array("template"=>"<div style='display: flex; justify-content: center; align-items: center; width: 100%;height:100%'><div>Non sono presenti elementi.</div></div>"));
        }
        
        $toolbar_riepilogo=new AA_JSON_Template_Toolbar($id."_Toolbar_Riepilogo",array("height"=>38,"borderless"=>true));
        
        //Flag filtri
        $filter_id=$id."_".$object->GetId();
        $filter= AA_SessionVar::Get($filter_id);
        if($filter->isValid())
        {
            $label="<div style='display: flex; height: 100%; justify-content: flex-start; align-items: center;'>Mostra:";
            
            $values=(array)$filter->GetValue();
            
            //tutte
            $label.="&nbsp;<span class='AA_Label AA_Label_LightBlue'>tutte</span>";
            
            $label.="</div>";
        }
        else
        {
            $label="<div style='display: flex; height: 100%; justify-content: flex-start; align-items: center;'>Mostra:&nbsp;<span class='AA_Label AA_Label_LightBlue'>tutte</span></div>";
        }

        $toolbar_riepilogo->AddElement(new AA_JSON_Template_Generic($id."_Filter_Label",array("view"=>"label","label"=>$label, "width"=>400, "align"=>"left")));
        
        $toolbar_riepilogo->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","gravity"=>1)));
        $toolbar_riepilogo->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_Riepilogo_Intestazione",array("view"=>"label","label"=>"<span style='color:#003380'>Riepilogo Coalizioni e Liste</span>", "align"=>"center","gravity"=>10)));
        $toolbar_riepilogo->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","gravity"=>1)));
        if($canModify)
        {            
            //Pulsante di Aggiunta nomina
            $addnew_btn=new AA_JSON_Template_Generic($id."_AddNewUp_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil-plus",
                "label"=>"Aggiungi",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi coalizione",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierAddNewCoalizioneDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));
            
            //pulsante di filtraggio
            if($filter_id=="") $filter_id=$id;
            
            $filterDlgTask="GetSierCoalizioniFilterDlg";
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

    //Template section detail, tab liste
    public function TemplateSierDettaglio_Coalizioni_Tab($object=null,$filterData="")
    {
       $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_LISTE_BOX;

        if(!($object instanceof AA_Sier)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));

        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));

        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"borderless"=>true,"width"=>130));
        
        $tabbar=new AA_JSON_Template_Generic($id."_TabBar",array(
            "view"=>"tabbar",
            "borderless"=>true,
            "css"=>"AA_Bottom_TabBar",
            "multiview"=>true,
            "optionWidth"=>200,
            "view_id"=>$id."_Multiview",
            "type"=>"bottom"
        ));

        //permessi
        $perms = $object->GetUserCaps($this->oUser);
        $canModify=false;
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;

        if($canModify)
        {            
            //Pulsante di Aggiunta coalizione
            $addnew_btn=new AA_JSON_Template_Generic($id."_AddNew_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil-plus",
                "label"=>"Aggiungi",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi coalizione",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierAddNewCoalizioneDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));
            
            $toolbar->AddElement(new AA_JSON_Template_Generic());
            $toolbar->AddElement($addnew_btn);
        }
        else
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic());
        }
        
        $footer=new AA_JSON_Template_Layout($id."_Footer",array("type"=>"clean", "height"=>38, "css"=>"AA_SectionContentHeader"));
        
        $footer->AddCol($tabbar);
        $footer->AddCol($toolbar);
        
        $multiview=new AA_JSON_Template_Multiview($id."_Multiview",array(
            "type"=>"clean",
            "css"=>"AA_Detail_Content"
         ));

        $layout->AddRow($multiview);
        $layout->addRow($footer);
        
        //Array dati riepilogo e opzioni tab
        $riepilogo_data=array();
        $options_tabbar=array();

        //Recupero coalizioni
        $params=array();
        $filter= AA_SessionVar::Get($id."_".$object->GetId());
        if($filter->isValid())
        {
            $params=(array)$filter->GetValue();
            //AA_Log::Log(__METHOD__." - ".print_r($params,true),100);
        }

        //immagine
        $platform=AA_Platform::GetInstance();
        $DefaultImagePath=AA_Const::AA_WWW_ROOT."/".$platform->GetModulePathURL($this->id)."/img";

        $minWidthListeItem=400;
        $ListeItemsForRow=intval($_REQUEST['vw']/$minWidthListeItem);
        foreach($object->GetCoalizioni($params) as $id_coalizione=>$curCoalizione)
        {
            $id_detail_coalizione=$id."_CoalizioneDetail_".$id_coalizione;
            $layout_dettaglio_coalizione=new AA_JSON_Template_Layout($id_detail_coalizione,array("type"=>"clean"));

            $curImagePath=$DefaultImagePath."/coalizioni_placeholder.png";
            if($curCoalizione->GetProp('image') != "")
            {
                $curImagePath=AA_Const::AA_WWW_ROOT."/storage.php?object=".$curCoalizione->GetProp('image');
            }
            //-------------- Dati di riepilogo --------------------
            $riepilogo_data[]=array(
                "id"=>$id_coalizione,
                "denominazione"=>$curCoalizione->GetProp("denominazione"),
                "presidente"=>$curCoalizione->GetProp("nome_candidato"),
                "image"=>$curImagePath,
                "onclick"=>'$$("'.$tabbar->GetId().'").setValue("'.$id_detail_coalizione.'")',
                "id_view"=>$id_detail_coalizione
            );
            $tab_label=$curCoalizione->GetProp("denominazione");
            if($canModify) $tab_label="<div style='display: flex; justify-content: space-between; align-items: center; padding-left: 5px; padding-right: 5px; font-size: smaller'><span>".$tab_label."</span><a style='margin-left: 1em;' class='AA_DataTable_Ops_Button_Red' title='Elimina organigramma' onClick='".'AA_MainApp.utils.callHandler("dlg", {task:"GetSierTrashCoalizioneDlg", params: [{id: "'.$object->GetId().'"},{id_coalizione: "'.$id_coalizione.'"}]},"'.$this->id.'")'."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $tab_label="<div style='display: flex; justify-content: center; align-items: center; padding-left: 5px; padding-right: 5px; font-size: smaller'><span>".$tab_label."</span></div>";
           
            //Tab label
            $options_tabbar[]=array("id"=>$id_detail_coalizione, "value"=>$tab_label);
            //------------------------------------------------------
           
            //-----------header--------------------
            $toolbar = new AA_JSON_Template_Toolbar($id_detail_coalizione."_Toolbar", array("height" => 38, "css" => array("border-bottom" => "1px solid #dadee0 !important")));
            
            //torna al riepilogo
            $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Riepilogo_".$id_coalizione."_btn",array(
                "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-keyboard-backspace",
                "label"=>"Riepilogo",
                "align"=>"left",
                "width"=>120,
                "tooltip"=>"Torna al riepilogo",
                "click"=>"$$('".$tabbar->GetId()."').setValue('".$id."_Riepilogo_Layout')"
            )));

            //$toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 120)));
            $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer")));
            
            $toolbar->addElement(new AA_JSON_Template_Generic($id_detail_coalizione."_header_content",array("view"=>"label","align"=>"center","label"=>$curCoalizione->GetProp('denominazione'))));
    
            $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer")));
    
            //Pulsante di modifica
            $canModify = false;
            if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0) $canModify = true;
            if ($canModify) {
                $modify_btn = new AA_JSON_Template_Generic($id_detail_coalizione."_Modify_btn", array(
                    "view" => "button",
                    "type" => "icon",
                    "icon" => "mdi mdi-pencil",
                    "label" => "Modifica",
                    "align" => "right",
                    "width" => 120,
                    "tooltip" => "Modifica Coalizione",
                    "click" => "AA_MainApp.utils.callHandler('dlg', {task:\"GetSierModifyCoalizioneDlg\", params: [{id: " . $object->GetId() . "},{id_coalizione:\"".$id_coalizione."\"}]},'$this->id')"
                ));
                $toolbar->AddElement($modify_btn);
            }
            $layout_dettaglio_coalizione->addRow($toolbar);
            //-----------------------
            
            //------------ Contenuto Coalizione ---------------------
            $coalizione_content_box=new AA_JSON_Template_Layout($id_detail_coalizione."_ContentBox",array("type"=>"clean"));

            $coalizione_content_box->AddCol(new AA_JSON_Template_Template($id_detail_coalizione."_CoalizioneImage",array(
                "type"=>"clean",
                "width"=>120,
                "height"=>120,
                "template"=>"<div style='width: 100%;height:100%; display:flex; flex-direction:column; justify-content: center; align-items: center'><img src='".$curImagePath."' width='100px' /></div>"
            )));

            //Candidato Presidente
            $coalizione_content_box->AddCol(new AA_JSON_Template_Template($id_detail_coalizione."_CoalizionePresidente",array(
                "type"=>"clean",
                "template"=>"<div style='width: 100%; height: 100%; display: flex; flex-direction: column; justify-content: center; margin-left: 1em'><span style='font-weight: 900'>Presidente:</span><div>#presidente#</div></div>",
                "data"=>array("presidente"=>$curCoalizione->GetProp('nome_candidato'))
            )));
            $layout_dettaglio_coalizione->AddRow($coalizione_content_box);
            //-------------------------------------------------------

            //------------------- Liste -----------------------------
            $curId=$id_detail_coalizione."_ListeBox";
            $coalizione_liste_box=new AA_JSON_Template_Layout($curId,array("type"=>"clean"));           

            $toolbar=new AA_JSON_Template_Toolbar($curId."_Toolbar_allegati",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

            $toolbar->AddElement(new AA_JSON_Template_Generic($curId."_Toolbar_Liste_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Liste</span>", "align"=>"center")));

            if($canModify)
            {
                //Pulsante di aggiunta nuova lista
                $add_lista_btn=new AA_JSON_Template_Generic($curId."_AddLista_btn",array(
                "view"=>"button",
                    "type"=>"icon",
                    "icon"=>"mdi mdi-file-plus",
                    "label"=>"Aggiungi",
                    "align"=>"right",
                    "width"=>120,
                    "tooltip"=>"Aggiungi Lista",
                    "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierAddNewListaDlg\", params: [{id: ".$object->GetId()."},{id_coalizione:".$curCoalizione->GetProp('id')."}]},'$this->id')"
                ));

                $toolbar->AddElement($add_lista_btn);
            }
            else 
            {
                $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
            }
            $coalizione_liste_box->AddRow($toolbar);

            $liste=$curCoalizione->GetListe();
            
            //AA_Log::Log(__METHOD__." - liste: ".print_r($liste,true),100);

            if(sizeof($liste)>0)
            {
                $dataview_liste_data=array();
                foreach($liste as $id_lista=>$curLista)
                {
                    $curImagePath=$DefaultImagePath."/coalizioni_placeholder.png";
                    if($curLista->GetProp('image') != "")
                    {
                        $curImagePath=AA_Const::AA_WWW_ROOT."/storage.php?object=".$curLista->GetProp('image');
                    }

                    if($canModify)
                    {
                        $addnew="<a title='Aggiungi candidato' class='AA_Button_Link' onclick='AA_MainApp.utils.callHandler(\"dlg\", {task:\"GetSierAddNewCandidatoDlg\", params: [{id: ".$object->GetId()."},{id_coalizione:".$curCoalizione->GetProp('id')."},{id_lista:".$curLista->GetProp('id')."}]},\"$this->id\")'><span class='mdi mdi-account-plus'></span></a>";
                        $modify="<a title='Modifica' class='AA_Button_Link' onclick='AA_MainApp.utils.callHandler(\"dlg\", {task:\"GetSierModifyListaDlg\", params: [{id: ".$object->GetId()."},{id_coalizione:".$curCoalizione->GetProp('id')."},{id_lista:".$curLista->GetProp('id')."}]},\"$this->id\")'><span class='mdi mdi-pencil'></span></a>";
                        $trash="<a title='Elimina' class='AA_Button_Link AA_DataTable_Ops_Button_Red' style='color: red' onclick='AA_MainApp.utils.callHandler(\"dlg\", {task:\"GetSierTrashListaDlg\", params: [{id: ".$object->GetId()."},{id_coalizione:".$curCoalizione->GetProp('id')."},{id_lista:".$curLista->GetProp('id')."}]},\"$this->id\")'><span class='mdi mdi-trash-can'></span></a>";    
                    }
                    else
                    {
                        $addnew="&nbsp;";
                        $modify="&nbsp;";
                        $trash="&nbsp;";
                    }
                    
                    $dataview_liste_data[]=array("id"=>$id_lista,"id_coalizione"=>$curLista->GetProp('id_coalizione'),"denominazione"=>$curLista->GetProp('denominazione'),'image'=>$curImagePath,'modify'=>$modify,'trash'=>$trash,'addnew'=>$addnew);
                }

                $liste_template="<div style='display: flex; align-items: center; height: 100%; justify-content: space-between;' id_view='".$curId."_Liste"."'><div style='display: flex; align-items: center; width: 270px; padding: 5px;'>"
                . "<img src='#image#' width='50px'/><div style='height: 100%;display:flex; align-items: center; justify-content: space-between'><span style='margin-left: 1em; font-weight: 700;'>#denominazione#</span></div></div>"
                . "<div style='display: flex;  align-items: center; justify-content: space-between; height: 100%; padding: 5px; width: 100px'>#addnew#&nbsp;#modify#&nbsp;#trash#</div></div>";
                
                $dataview_liste=new AA_JSON_Template_Generic($curId."_Liste",array(
                    "view"=>"dataview",
                    "xCount"=>$ListeItemsForRow,
                    "module_id"=>$this->id,
                    "tabbar"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_TabBar_".$object->GetId(),
                    "type"=>array(
                        "type"=>"tiles",
                        "height"=>60,
                        "width"=>"auto",
                        "css"=>"AA_DataView_Nomine_item",
                    ),
                    "on" => array("onItemDblClick" => "AA_MainApp.utils.getEventHandler('ListaDblClick','".$this->GetId()."')"),
                    "template"=>$liste_template,
                    "data"=>$dataview_liste_data
                ));

                $coalizione_liste_box->AddRow($dataview_liste);
            }
            else
            {
                $coalizione_liste_box->AddRow(new AA_JSON_Template_Template($curId."_Liste",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Non sono presenti elementi.</span></div>")));
            }
            $layout_dettaglio_coalizione->AddRow($coalizione_liste_box);
            //-------------------------------------------------------
            
            $multiview->addCell($layout_dettaglio_coalizione);
        }
        //------------------

        //Riepilogo tab
        $riepilogo_layout=$this->TemplateDettaglio_Coalizioni_Riepilogo_Tab($object,$id, $riepilogo_data);
    
        array_unshift($options_tabbar,array("id"=>$riepilogo_layout->GetId(), "value"=>"Riepilogo"));
        
        $multiview->AddCell($riepilogo_layout,true);
        
        $tabbar->SetProp("options",$options_tabbar);
        return $layout;
    }

    //Template section detail, tab candidati
    public function TemplateSierDettaglio_Candidati_Tab($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_CANDIDATI_BOX;

        if(!($object instanceof AA_Sier)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));

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
                "icon"=>"mdi mdi-account-plus",
                "label"=>"Aggiungi",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi un nuovo candidato",
                //"click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierAddNewCandidatoDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
                "click"=>"AA_MainApp.utils.callHandler('AddNewCandidato', {task:\"GetSierAddNewCandidatoDlg\", params: [{id: ".$object->GetId()."},{table_id:\"".$id."_Candidati\"}]},'".$this->id."')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        
        $layout->addRow($toolbar);        
        $columns=array(
            array("id"=>"ordine","header"=>array("<div style='text-align: center'>n.</div>",array("content"=>"selectFilter")),"width"=>50, "sort"=>"int","css"=>array("text-align"=>"center")),
            array("id"=>"cognome","header"=>array("<div style='text-align: center'>Cognome</div>",array("content"=>"textFilter")),"fillspace"=>true, "sort"=>"text","css"=>array("text-align"=>"left")),
            array("id"=>"nome","header"=>array("<div style='text-align: center'>Nome</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text"),
            array("id"=>"cf","header"=>array("<div style='text-align: center'>CF</div>",array("content"=>"textFilter")),"width"=>150, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"cv","header"=>array("<div style='text-align: center'>Curriculum</div>"),"width"=>120, "css"=>array("text-align"=>"center")),
            array("id"=>"cg","header"=>array("<div style='text-align: center'>Casellario</div>"),"width"=>120, "css"=>array("text-align"=>"center")),
            array("id"=>"circoscrizione_desc","header"=>array("<div style='text-align: center'>Circoscrizione</div>",array("content"=>"selectFilter")),"width"=>180, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"lista","header"=>array("<div style='text-align: center'>Lista</div>",array("content"=>"selectFilter")),"width"=>250, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"coalizione","header"=>array("<div style='text-align: center'>Coalizione</div>",array("content"=>"selectFilter")),"width"=>300, "css"=>array("text-align"=>"center"),"sort"=>"text")
        );

        if($canModify)
        {
            $columns[]=array("id"=>"ops","header"=>"<div style='text-align: center'>Operazioni</div>","width"=>100, "css"=>array("text-align"=>"center"));
        }

        $data=array();
        //$circoscrizioni=AA_Sier_Const::GetCircoscrizioni();

        $candidati=$object->GetCandidati();
        foreach($candidati as $curCandidato)
        {
            $data[]=$curCandidato->GetProps();
            $index=sizeof($data)-1;

            //AA_Log::Log(__METHOD__." - candidato: ".print_r($curCandidato,true),100);

            //Circoscrizione
            $data[$index]['circoscrizione_desc']=$curCandidato->GetProp("circoscrizione");

            //Curriculum
            if($curCandidato->GetProp('cv') !="")
            {
                if(strpos($curCandidato->GetProp('cv'),"http") === false) $view='AA_MainApp.utils.callHandler("pdfPreview", {url: "storage.php?object='.$curCandidato->GetProp('cv').'"},"'.$this->id.'")';
                else $view='window.open("'.$curCandidato->GetProp('cv').'")';
                $data[$index]['cv']="<div class='AA_DataTable_Ops' style='justify-content: space-evenly'><a class='AA_DataTable_Ops_Button' title='Consulta il curriculum' onClick='".$view."'><span class='mdi mdi-eye'></span></a>";
                if($canModify)
                {
                    $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSierModifyCandidatoCVDlg", params: [{id: "'.$object->GetId().'"},{id_candidato:"'.$curCandidato->GetProp("id").'"}]},"'.$this->id.'")';
                    $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSierTrashCandidatoCVDlg", params: [{id: "'.$object->GetId().'"},{id_candidato:"'.$curCandidato->GetProp("id").'"}]},"'.$this->id.'")';
                    $data[$index]['cv'].="<a class='AA_DataTable_Ops_Button' title='Modifica il curriculum' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina il curriculum' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a>";
                }
                $data[$index]['cv'].="</div>";
            }
            else
            {
                if($canModify)
                {
                    $add='AA_MainApp.utils.callHandler("dlg", {task:"GetSierModifyCandidatoCVDlg", params: [{id: "'.$object->GetId().'"},{id_candidato:"'.$curCandidato->GetProp("id").'"}]},"'.$this->id.'")';
                    $data[$index]['cv'].="<div class='AA_DataTable_Ops' style='justify-content: space-evenly'><a class='AA_DataTable_Ops_Button' title='Carica il curriculum' onClick='".$add."'><span class='mdi mdi-file-upload'></span></a></div>";
                }
            }

            //Casellario giudiziale
            if($curCandidato->GetProp('cg') !="")
            {
                if(strpos($curCandidato->GetProp('cv'),"http") === false) $view='AA_MainApp.utils.callHandler("pdfPreview", {url: "storage.php?object='.$curCandidato->GetProp('cg').'"},"'.$this->id.'")';
                else $view='window.open("'.$curCandidato->GetProp('cg').'")';
                $data[$index]['cg']="<div class='AA_DataTable_Ops' style='justify-content: space-evenly'><a class='AA_DataTable_Ops_Button' title='Consulta il casellario' onClick='".$view."'><span class='mdi mdi-eye'></span></a>";
                if($canModify)
                {
                    $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSierModifyCandidatoCGDlg", params: [{id: "'.$object->GetId().'"},{id_candidato:"'.$curCandidato->GetProp("id").'"}]},"'.$this->id.'")';
                    $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSierTrashCandidatoCGDlg", params: [{id: "'.$object->GetId().'"},{id_candidato:"'.$curCandidato->GetProp("id").'"}]},"'.$this->id.'")';
                    $data[$index]['cg'].="<a class='AA_DataTable_Ops_Button' title='Modifica il casellario' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina il casellario' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a>";
                }
                $data[$index]['cg'].="</div>";
            }
            else
            {
                if($canModify)
                {
                    $add='AA_MainApp.utils.callHandler("dlg", {task:"GetSierModifyCandidatoCGDlg", params: [{id: "'.$object->GetId().'"},{id_candidato:"'.$curCandidato->GetProp("id").'"}]},"'.$this->id.'")';
                    $data[$index]['cg'].="<div class='AA_DataTable_Ops' style='justify-content: space-evenly'><a class='AA_DataTable_Ops_Button' title='Carica il casellario' onClick='".$add."'><span class='mdi mdi-file-upload'></span></a></div>";
                }
            }

            if($canModify)
            {
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSierTrashCandidatoDlg", params: [{id: "'.$object->GetId().'"},{id_candidato:"'.$curCandidato->GetProp("id").'"}]},"'.$this->id.'")';
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSierModifyCandidatoDlg", params: [{id: "'.$object->GetId().'"},{id_candidato:"'.$curCandidato->GetProp("id").'"}]},"'.$this->id.'")';
                $data[$index]['ops']="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Modifica i dati generali del candidato' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina il candidato' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
            }
        }

        //AA_Log::Log(__METHOD__." - candidati: ".print_r($data,true),100);

        if(sizeof($candidati) > 0)
        {
            $table=new AA_JSON_Template_Generic($id."_Candidati", array(
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
            $layout->addRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Non sono presenti candidati.</span></div>")));
        }

        return $layout;
    }

    //Template section detail, tab liste
    public function TemplateSierDettaglio_Comuni_Tab($object=null)
    {
       $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_COMUNI_BOX;

       return new AA_JSON_Template_Template($id,array("template"=>"sezione in fase di sviluppo"));

       if(!($object instanceof AA_Sier)) return new AA_JSON_Template_Template($id,array("template"=>"oggetto non valido"));
        
       $rows_fixed_height=50;

       $layout=$this->TemplateGenericDettaglio_Header_Generale_Tab($object,$id);

       return $layout;
    }
    
    //Task Update Sier
    public function Task_UpdateSier($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
        {
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi di modifica dell'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $flags=array_keys(AA_Sier_Const::GetFlags());
        
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
    
    //Task trash Sier
    public function Task_TrashSier($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
        {
            $task->SetError("L'utente corrente non ha i permessi per cestinare l'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per cestinare l'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        return $this->Task_GenericTrashObject($task,$_REQUEST);
    }
    
    //Task resume Sier
    public function Task_ResumeSier($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericResumeObject($task,$_REQUEST);
    }
    
    //Task publish Sier
    public function Task_PublishSier($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        return $this->Task_GenericPublishObject($task,$_REQUEST);
    }
    
    //Task reassign Sier
    public function Task_ReassignSier($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        return $this->Task_GenericReassignObject($task,$_REQUEST);
    }
    
    //Task delete Sier
    public function Task_DeleteSier($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
         
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
        {
            $task->SetError("L'utente corrente non ha i permessi per eliminare l'elemento");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per eliminare l'elemento</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        return $this->Task_GenericDeleteObject($task,$_REQUEST);
    }
    
    //Task Aggiungi provvedimenti
    public function Task_AddNewSier($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
        {
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi");
            $sTaskLog="<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per aggiungere nuovi elementi</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
        
        return $this->Task_GenericAddNew($task,$_REQUEST);
    }

    //Task modifica elemento
    public function Task_GetSierModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non può modifcare l'elemento.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
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
            $sTaskLog.= $this->Template_GetSierModifyDlg($object)->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }
    
    //Task resume organismo
    public function Task_GetSierResumeDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
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
            $sTaskLog.= $this->Template_GetGenericResumeObjectDlg($_REQUEST,"ResumeSier")->toBase64();
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
    public function Task_GetSierPublishDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
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
            $sTaskLog.= $this->Template_GetGenericPublishObjectDlg($_REQUEST,"PublishSier")->toBase64();
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
    
    //Task Riassegna provvedimenti
    public function Task_GetSierReassignDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
         if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
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
            $sTaskLog.= $this->Template_GetGenericReassignObjectDlg($_REQUEST,"ReassignSier")->toBase64();
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
    public function Task_GetSierTrashDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
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
            $sTaskLog.= $this->Template_GetGenericObjectTrashDlg($_REQUEST,"TrashSier")->toBase64();
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
       
    //Task dialogo elimina provvedimenti
    public function Task_GetSierDeleteDlg($task)
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
            $sTaskLog.= $this->Template_GetSierDeleteDlg($_REQUEST)->toBase64();
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
    
    //Task aggiunta provvedimenti
    public function Task_GetSierAddNewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per istanziare nuovi elementi.</error>";
        }
        else
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierAddNewDlg()->toBase64();
            $sTaskLog.="</content>";
        }
        
        $task->SetLog($sTaskLog);
        
        return true;
    }

    //Task aggiungi giornata
    public function Task_GetSierAddNewGiornataDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Oggetto non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierAddNewGiornataDlg($object)->toBase64();
            $sTaskLog.="</content>";
            $task->SetLog($sTaskLog);
        
            return true;
        }
        else
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'oggetto (".$object->GetId().").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
    }

    //Task aggiungi dato contabile
    public function Task_GetSierModifyGiornataDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Oggetto non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'oggetto (".$object->GetId().").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $giornata=$_REQUEST['data'];
        if($giornata==null)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Data non valida (".$_REQUEST['data'].").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $sTaskLog.= $this->Template_GetSierModifyGiornataDlg($object,$giornata)->toBase64();
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }

    //Task aggiungi dato contabile
    public function Task_GetSierTrashGiornataDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Oggetto non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>L'utente corrente non ha i permessi per poter modificare l'oggetto (".$object->GetId().").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $giornata=$_REQUEST['data'];
        if($giornata==null)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Data non valida (".$_REQUEST['data'].").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $sTaskLog.= $this->Template_GetSierTrashGiornataDlg($object,$giornata)->toBase64();
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }

    //Task aggiungi cv candidato
    public function Task_GetSierAddNewCandidatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $liste=$object->GetListe();
        if(sizeof($liste)==0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Non sono presenti Liste da associare al candidato, occorre inserire almeno una Lista per procedere.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $lista=null;
        if($_REQUEST['id_lista'] !="" && isset($liste[$_REQUEST['id_lista']]))
        {
            $lista=$liste[$_REQUEST['id_lista']];
        }

        //AA_Log::Log(__METHOD__." - lista_desc: ".$_REQUEST['lista_desc']." - ".print_r(array_keys($liste,[$_REQUEST['lista_desc']]),true),100);
        if($_REQUEST['lista_desc'] !="")
        {
            foreach($liste as $curlista)
            {
                if($curlista->GetProp("denominazione")==$_REQUEST['lista_desc']) $lista=$curlista;
            }
        }

        $id_circoscrizione=0;
        if($_REQUEST['circoscrizione_desc'] !="")
        {
            foreach(AA_Sier_Const::GetCircoscrizioni() as $id=>$curCircoscrizione)
            {
                if($curCircoscrizione==$_REQUEST['circoscrizione_desc']) $id_circoscrizione=$id;
            }
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierAddNewCandidatoDlg($object,$lista,$id_circoscrizione)->toBase64();
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

    //Task aggiungi cv candidato
    public function Task_GetSierModifyCandidatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $liste=$object->GetListe();
        if(sizeof($liste)==0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Non sono presenti Liste da associare al candidato, occorre inserire almeno una Lista per procedere.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if($candidato==null)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo Candidato non valido.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSiermodifyCandidatoDlg($object,$candidato)->toBase64();
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


    //Task aggiungi cv candidato
    public function Task_GetSierAddNewCandidatoCVDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if(!($candidato instanceof AA_SierCandidato))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Candidato non valido.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierAddNewCandidatoCVDlg($object,$candidato)->toBase64();
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

    //Task aggiungi cg candidato
    public function Task_GetSierAddNewCandidatoCGDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if(!($candidato instanceof AA_SierCandidato))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Candidato non valido.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierAddNewCandidatoCGDlg($object,$candidato)->toBase64();
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

    //Task modifica cg candidato
    public function Task_GetSierModifyCandidatoCGDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if(!($candidato instanceof AA_SierCandidato))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Candidato non valido.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierModifyCandidatoCGDlg($object,$candidato)->toBase64();
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

    //Task elimina cg candidato
    public function Task_GetSierTrashCandidatoCGDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if(!($candidato instanceof AA_SierCandidato))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Candidato non valido.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierTrashCandidatoCGDlg($object,$candidato)->toBase64();
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

    //Task elimina cg candidato
    public function Task_GetSierTrashCandidatoCVDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if(!($candidato instanceof AA_SierCandidato))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Candidato non valido.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierTrashCandidatoCVDlg($object,$candidato)->toBase64();
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

    //Task elimina cg candidato
    public function Task_GetSierTrashCandidatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if(!($candidato instanceof AA_SierCandidato))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Candidato non valido.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierTrashCandidatoDlg($object,$candidato)->toBase64();
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

    //Task modifica cg candidato
    public function Task_GetSierModifyCandidatoCVDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if(!($candidato instanceof AA_SierCandidato))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Candidato non valido.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierModifyCandidatoCVDlg($object,$candidato)->toBase64();
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

    //Task aggiungi allegato
    public function Task_GetSierAddNewAllegatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
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
            $sTaskLog.= $this->Template_GetSierAddNewAllegatoDlg($object)->toBase64();
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

    //Task aggiungi Lista
    public function Task_GetSierAddNewListaDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $coalizione=$object->GetCoalizione($_REQUEST['id_coalizione']);
        if(!($coalizione instanceof AA_SierCoalizioni))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo Coalizione non valido (".$_REQUEST['id_coalizione'].").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierAddNewListaDlg($object,$coalizione)->toBase64();
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

    //Task aggiungi Lista
    public function Task_GetSierModifyListaDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $coalizione=$object->GetCoalizione($_REQUEST['id_coalizione']);
        if(!($coalizione instanceof AA_SierCoalizioni))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo Coalizione non valido (".$_REQUEST['id_coalizione'].").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $liste=$coalizione->GetListe();
        //AA_Log::Log(__METHOD__." - liste: ".print_r($liste,true),100);

        if(!isset($liste[$_REQUEST['id_lista']]))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo Lista non valido (".$_REQUEST['id_lista'].").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        $lista=$liste[$_REQUEST['id_lista']];
        //AA_Log::Log(__METHOD__." - lista: ".print_r($lista,true),100);

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierModifyListaDlg($object,$coalizione,$lista)->toBase64();
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

    //Task aggiungi Coalizione
    public function Task_GetSierAddNewCoalizioneDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
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
            $sTaskLog.= $this->Template_GetSierAddNewCoalizioneDlg($object)->toBase64();
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

    //Task modifica Coalizione
    public function Task_GetSierModifyCoalizioneDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $coalizione = $object->GetCoalizioni($_REQUEST);
        if(sizeof($coalizione) == 0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Coalizione non valida.</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
    
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0)
        {
            $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
            $sTaskLog.= $this->Template_GetSierModifyCoalizioneDlg($object,$coalizione[$_REQUEST['id_coalizione']])->toBase64();
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
    public function Task_UpdateSierAllegato($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
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
        $allegato=new AA_SierAllegati($_REQUEST['id_allegato'],$allegato->GetIdSier(),$_REQUEST['estremi'],$_REQUEST['url'],$fileHash,$_REQUEST['tipo'],$aggiornamento);
        
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
    public function Task_DeleteSierAllegato($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
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
    public function Task_GetSierModifyAllegatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
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
        $sTaskLog.= $this->Template_GetSierModifyAllegatoDlg($object,$allegato)->toBase64();
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }

    //Task sier trash coalizione
    public function Task_GetSierTrashCoalizioneDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid() || $object->GetId()<= 0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
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

        $coalizione=$object->GetCoalizione($_REQUEST['id_coalizione']);
        if($coalizione==null)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo coalizione non valido (".$_REQUEST['id_coalizione'].").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $sTaskLog.= $this->Template_GetSierTrashCoalizioneDlg($object,$coalizione)->toBase64();
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }

    //Task sier trash lista
    public function Task_GetSierTrashListaDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid() || $object->GetId()<= 0)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>Elemento non valido o permessi insufficienti.</error>";
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

        $coalizione=$object->GetCoalizione($_REQUEST['id_coalizione']);
        if($coalizione==null)
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo Coalizione non valido (".$_REQUEST['id_coalizione'].").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }

        $liste=$coalizione->GetListe();
        if(!isset($liste[$_REQUEST['id_lista']]))
        {
            $sTaskLog="<status id='status'>-1</status><content id='content' type='json'>";
            $sTaskLog.= "{}";
            $sTaskLog.="</content><error id='error'>identificativo Lista non valido (".$_REQUEST['id_lista'].").</error>";
            $task->SetLog($sTaskLog);
        
            return false;
        }
        $lista=$liste[$_REQUEST['id_lista']];

        $sTaskLog="<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $sTaskLog.= $this->Template_GetSierTrashListaDlg($object,$coalizione,$lista)->toBase64();
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }

    //Task trash allegato
    public function Task_GetSierTrashAllegatoDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
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
        $sTaskLog.= $this->Template_GetSierTrashAllegatoDlg($object,$allegato)->toBase64();
        $sTaskLog.="</content>";
        $task->SetLog($sTaskLog);

        return true;
    }

    //Task filter dlg
    public function Task_GetSierPubblicateFilterDlg($task)
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
    public function Task_GetSierBozzeFilterDlg($task)
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
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
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
        foreach(AA_Sier_Const::GetListaTipologia() as $key=>$value)
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
      
        //Revisionate
        //$dlg->AddSwitchBoxField("revisionate","Revisionate",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede revisionate."));
        
        //oggetto
        $dlg->AddTextField("nome","Oggetto",array("bottomLabel"=>"*Filtra in base all'oggetto del elemento/accordo.", "placeholder"=>"Oggetto..."));
        
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura controllante."));
        
        //tipo
        /*$selectionChangeEvent="try{AA_MainApp.utils.getEventHandler('onTipoProvSelectChange','".$this->id."','".$this->id."_Field_Tipo')}catch(msg){console.error(msg)}";
        $options=array();
        $options[0]="Qualunque";
        foreach(AA_Sier_Const::GetListaTipologia() as $key=>$value)
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
    public function Template_PdfExport($ids=array(),$toBrowser=true,$title="Pubblicazione ai sensi dell'art.23 del d.lgs. 33/2013",$rowsForPage=20,$index=false,$subTitle="")
    {
        return $this->Template_GenericPdfExport($ids,$toBrowser,$title,"Template_SierPdfExport", $rowsForPage, $index,$subTitle);
    }

    //Template pdf export single
    public function Template_SierPdfExport($id="", $parent=null,$object=null,$user=null)
    {
        if(!($object instanceof AA_Sier))
        {
            return "";
        }
        
        if($id=="") $id="Template_SierPdfExport_".$object->GetId();

        return new AA_SierPublicReportTemplateView($id,$parent,$object,$user);
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
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierAddNewAllegatoDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
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
            
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSierTrashAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSierModifyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
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

    //Template dettaglio giornate
    public function TemplateDettaglio_Giornate($object=null,$id="", $canModify=false)
    {
        #documenti----------------------------------
        $curId=$id."_Layout_Allegati";
        $giornate=new AA_JSON_Template_Layout($curId,array("type"=>"clean","gravity"=>2,"minWidth"=>400,"css"=>array("border-left"=>"1px solid gray !important;","border-top"=>"1px solid gray !important;")));

        $toolbar=new AA_JSON_Template_Toolbar($curId."_Toolbar_allegati",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

        $toolbar->AddElement(new AA_JSON_Template_Generic($curId."_Toolbar_Allegati_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Giornate</span>", "align"=>"center")));

        if($canModify)
        {
            //Pulsante di aggiunta documento
            $add_giornata_btn=new AA_JSON_Template_Generic($curId."_AddGiornata_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-file-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi una giornata",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierAddNewGiornataDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));

            $toolbar->AddElement($add_giornata_btn);
        }
        else 
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        }

        $giornate->AddRow($toolbar);

        $options_giornate=array();

        if($canModify)
        {
            $options_giornate[]=array("id"=>"data", "header"=>"Data", "fillspace"=>true,"css"=>array("text-align"=>"left"));
            $options_giornate[]=array("id"=>"affluenza", "header"=>"Affluenza", "width"=>90,"css"=>array("text-align"=>"center"));
            $options_giornate[]=array("id"=>"risultati", "header"=>"Risultati", "width"=>90,"css"=>array("text-align"=>"center"));
            $options_giornate[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
        }
        else
        {
            $options_giornate[]=array("id"=>"data", "header"=>"Data", "fillspace"=>true,"css"=>array("text-align"=>"left"));
            $options_giornate[]=array("id"=>"affluenza", "header"=>"Affluenza", "width"=>90,"css"=>array("text-align"=>"center"));
            $options_giornate[]=array("id"=>"risultati", "header"=>"Risultati", "width"=>90,"css"=>array("text-align"=>"center"));
        }

        $lista=new AA_JSON_Template_Generic($curId."_Giornate_Table",array("view"=>"datatable", "headerRowHeight"=>28, "select"=>true,"scrollX"=>false,"css"=>"AA_Header_DataTable","columns"=>$options_giornate));

        $giornate_data=array();
        foreach($object->GetGiornate() as $id_giornata=>$curFlags)
        { 
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSierTrashGiornataDlg", params: [{id: "'.$object->GetId().'"},{data:"'.$id_giornata.'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSierModifyGiornataDlg", params: [{id: "'.$object->GetId().'"},{data:"'.$id_giornata.'"}]},"'.$this->id.'")';
            if($canModify) $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
            else $ops="<div class='AA_DataTable_Ops' style='justify-content: center'>&nbsp;</div>";
            $affluenza="No";
            if($curFlags['affluenza']==1) $affluenza="Si";
            $risultati="No";
            if($curFlags['risultati']==1) $risultati="Si";
            $giornate_data[]=array("data"=>$id_giornata,"affluenza"=>$affluenza,"risultati"=>$risultati,"ops"=>$ops);
        }
        $lista->SetProp("data",$giornate_data);
        if(sizeof($giornate_data) > 0) $giornate->AddRow($lista);
        else $giornate->AddRow(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        #--------------------------------------
        
        return $giornate;
    }

    //Template dettaglio allegati
    public function TemplateDettaglio_Abilitazioni($object=null,$id="")
    {
        #Abilitazioni----------------------------------
        $curId=$id."_Layout_Abilitazioni";
        $layout=new AA_JSON_Template_Layout($curId,array("type"=>"clean","title"=>"Abilitazioni di caricamento per gli operatori ed esportazione info per il sito istituzionale.","gravity"=>10));

        //oggetto non valido
        if(!($object instanceof AA_Sier) || !$object->isValid())
        {
            $layout->addRow(new AA_JSON_Template_Generic());
            return $layout;
        }

        $abilitazioni=$object->GetAbilitazioni();

        //Abilitazione accesso operatori
        $value="<span class='AA_Label AA_Label_LightGray'>Disabilitato</span>";
        
        if(($abilitazioni & AA_Sier_Const::AA_SIER_FLAG_ACCESSO_OPERATORI) > 0) 
        {
            $value="<span class='AA_Label AA_Label_LightGreen'>Abilitato</span>";
        }
        $campo=new AA_JSON_Template_Template($id."_AccessoOperatori",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Accesso operatori:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0 !important")
        ));
        $layout->addCol($campo);
        #--------------------------------------

        //Abilitazione modifica info generali
        $value="<span class='AA_Label AA_Label_LightGray'>Disabilitato</span>";
        if(($abilitazioni & AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_DATIGENERALI) > 0)  $value="<span class='AA_Label AA_Label_LightGreen'>Abilitato</span>";
        $campo=new AA_JSON_Template_Template($id."_DatiGenerali",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Info generali Comune:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0 !important")
        ));
        $layout->addCol($campo);
        #--------------------------------------

        //Abilitazione caricamento corpo elettorale
        $value="<span class='AA_Label AA_Label_LightGray'>Disabilitato</span>";
        if(($abilitazioni & AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_CORPO_ELETTORALE) > 0)  $value="<span class='AA_Label AA_Label_LightGreen'>Abilitato</span>";
        $campo=new AA_JSON_Template_Template($id."_CorpoElettorale",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Corpo elettorale:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0 !important")
        ));
        $layout->addCol($campo);
        #--------------------------------------
        
        //Abilitazione caricamento affluenza
        $value="<span class='AA_Label AA_Label_LightGray'>Disabilitato</span>";
        if(($abilitazioni & AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_AFFLUENZA) > 0)  $value="<span class='AA_Label AA_Label_LightGreen'>Abilitato</span>";
        $campo=new AA_JSON_Template_Template($id."_Affluenza",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Affluenza:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0 !important")
        ));
        $layout->addCol($campo);
        #--------------------------------------

        //Abilitazione caricamento risultati
        $value="<span class='AA_Label AA_Label_LightGray'>Disabilitato</span>";
        if(($abilitazioni & AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI) > 0)  $value="<span class='AA_Label AA_Label_LightGreen'>Abilitato</span>";
        $campo=new AA_JSON_Template_Template($id."_Risultati",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Risultati:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0 !important")
        ));
        #--------------------------------------
        $layout->addCol($campo);

        //Abilitazione esportazione affluenza
        $value="<span class='AA_Label AA_Label_LightGray'>Disabilitato</span>";
        if(($abilitazioni & AA_Sier_Const::AA_SIER_FLAG_EXPORT_AFFLUENZA) > 0)  $value="<span class='AA_Label AA_Label_LightGreen'>Abilitato</span>";
        $campo=new AA_JSON_Template_Template($id."_ExportAffluenza",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Esportazione affluenza:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0 !important")
        ));
        #--------------------------------------
        $layout->addCol($campo);

        //Abilitazione esportazione risultati
        $value="<span class='AA_Label AA_Label_LightGray'>Disabilitato</span>";
        $color="#000000";
        if(($abilitazioni & AA_Sier_Const::AA_SIER_FLAG_EXPORT_RISULTATI) > 0)  $value="<span class='AA_Label AA_Label_LightGreen'>Abilitato</span>";
        $campo=new AA_JSON_Template_Template($id."_ExportRisultati",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Esportazione risultati:","value"=>$value)
        ));
        #--------------------------------------
        $layout->addCol($campo);

        return $layout;
    }
}

#Classe template per la gestione del report pdf dell'oggetto
Class AA_SierPublicReportTemplateView extends AA_GenericObjectTemplateView
{
    public function __construct($id="AA_SierPublicReportTemplateView",$parent=null,$object=null)
    {
        if(!($object instanceof AA_Sier))
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

        /*if($object->GetTipo(true) == AA_Sier_Const::AA_TIPO_PROVVEDIMENTO_SCELTA_CONTRAENTE)
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

//Classe per la gestione degli allegati
Class AA_SierAllegati
{
    protected $id=0;
    public function GetId()
    {
        return $this->id;
    }
    public function SetId($id=0)
    {
        $this->id=$id;
    }
    
    protected $url="";
    public function GetUrl()
    {
        return $this->url;
    }
    public function SetUrl($url="")
    {
        $this->url=$url;
    }
    
    protected $nTipo=0;
    public function GetTipo()
    {
        return $this->nTipo;
    }
    public function SetTipo($val=0)
    {
        if($val > 0) $this->nTipo=$val;
    }
    public function GetTipoDescr()
    {
        $tipo=AA_Sier_Const::GetTipoAllegati();
        
        if(isset($tipo[$this->nTipo])) return $tipo[$this->nTipo];
        return "n.d.";
    }

    protected $estremi="";
    public function GetEstremi()
    {
        return $this->estremi;
    }
    public function SetEstremi($val="")
    {
        $this->estremi=$val;
    }

    protected $sAggiornamento="";
    public function GetAggiornamento()
    {
        return $this->sAggiornamento;
    }
    public function SetAggiornamento($val="")
    {
        $this->sAggiornamento=$val;
    }

    protected $sFile="";
    public function GetFileHash()
    {
        return $this->sFile;
    }
    public function SetFileHash($val="")
    {
        $this->sFile=$val;
    }

    public function GetFilePath()
    {
        if(is_file(AA_Const::AA_UPLOADS_PATH.AA_Sier_Const::AA_SIER_ALLEGATI_PATH."/".$this->id.".pdf"))
        {
            return AA_Const::AA_UPLOADS_PATH.AA_Sier_Const::AA_SIER_ALLEGATI_PATH."/".$this->id.".pdf";
        }
        
        return "";
    }

    public function GetFileLocalPath()
    {        
        return $this->GetFilePath();
    }
    
    public function GetFilePublicPath()
    {
        if(is_file(AA_Const::AA_UPLOADS_PATH.AA_Sier_Const::AA_SIER_ALLEGATI_PATH."/".$this->id.".pdf"))
        {
            return AA_Sier_Const::AA_WWW_ROOT.AA_Sier_Const::AA_SIER_ALLEGATI_PUBLIC_PATH."?id=".$this->id."&id_sier=".$this->id_sier;
        }
        
        return "";
    }
    
    protected $id_sier=0;
    public function GetIdSier()
    {
        return $this->id_sier;
    }
    public function SetIdSier($id=0)
    {
        $this->id_sier=$id;
    }
    
    public function __construct($id=0,$id_sier=0,$estremi="",$url="",$file="",$tipo=0,$aggiornamento="")
    {
        //AA_Log::Log(__METHOD__." id: $id, id_organismo: $id_organismo, tipo: $tipo, url: $url",100);
        
        $this->id=$id;
        $this->id_sier=$id_sier;
        $this->url=$url;
        $this->estremi=$estremi;
        $this->sFile=$file;
        $this->nTipo=$tipo;
        $this->sAggiornamento=$aggiornamento;
    }
    
    //Download del documento
    public function Download($embed=false)
    {
        if($this->sFile=="")
        {
            die($this->url);
        }

        $storage=AA_Storage::GetInstance();
        if(!$storage->IsValid() )
        {
            die("file non trovato");
        }

        $file=$storage->GetFileByHash($this->sFile);
        if($file->IsValid())
        {
            header("Cache-control: private");
            header("Content-type: ".$file->GetMimeType());
            header("Content-Length: ".$file->GetFileSize());
            if(!$embed) header('Content-Disposition: attachment; filename="'.$file->GetName()."'");

            $fd = fopen ($file->GetFilePath(), "rb");
            echo fread ($fd, filesize ($file->GetFilePath()));
            fclose ($fd);
            die();
        }
        else
        {
            die("file non trovato");
        }
    }
}