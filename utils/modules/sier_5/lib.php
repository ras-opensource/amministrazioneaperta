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
    const AA_USER_FLAG_SIER_OC="sier_oc";

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
    const AA_SIER_FLAG_CARICAMENTO_RENDICONTI=64;

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
                64=>"rendiconti",
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
    const AA_SIER_ALLEGATO_AVVISI=32;
    const AA_SIER_ALLEGATO_CIRCOLARI=64;
    const AA_SIER_ALLEGATO_ISTRUZIONI=128;
    const AA_SIER_ALLEGATO_MANIFESTI=256;

    public static function GetTipoAllegati()
    {
        if(static::$aTipoAllegati==null)
        {
            static::$aTipoAllegati=array(
                static::AA_SIER_ALLEGATO_AVVISI=>"Avvisi",
                static::AA_SIER_ALLEGATO_CIRCOLARI=>"Circolari",
                static::AA_SIER_ALLEGATO_COMUNICAZIONI=>"Comunicazioni",
                static::AA_SIER_ALLEGATO_INFORMAZIONI=>"Info generali",
                static::AA_SIER_ALLEGATO_ISTRUZIONI=>"Istruzioni",
                static::AA_SIER_ALLEGATO_MANIFESTI=>"Manifesti",
                static::AA_SIER_ALLEGATO_MODULISTICA=>"Modulistica",
                static::AA_SIER_ALLEGATO_NORMATIVA=>"Normativa",
                static::AA_SIER_ALLEGATO_RISULTATI=>"Risultati"
            );
        }

        return static::$aTipoAllegati;
    }

    protected static $aDestinatari=null;
    const AA_SIER_ALLEGATO_COMUNI=1;
    const AA_SIER_ALLEGATO_CANDIDATI=2;
    const AA_SIER_ALLEGATO_PREFETTURE=4;
    const AA_SIER_ALLEGATO_CITTADINI=8;


    public static function GetDestinatari()
    {
        if(static::$aDestinatari==null)
        {
            static::$aDestinatari=array(
                static::AA_SIER_ALLEGATO_CANDIDATI=>"Candidati",
                static::AA_SIER_ALLEGATO_CITTADINI=>"Cittadini",
                static::AA_SIER_ALLEGATO_COMUNI=>"Comuni",
                static::AA_SIER_ALLEGATO_PREFETTURE=>"Prefetture"
            );
        }

        return static::$aDestinatari;
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
        $this->aProps['ordine']=0;
        $this->aProps['candidati']=array();
        foreach(AA_Sier_Const::GetCircoscrizioni() as $key=>$val)
        {
            $this->aProps["ordine_".$key]=0;
        }

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

#Classe Comune
Class AA_SierComune
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

    public function GetOperatori($bAsObject=false)
    {
        $ret="";
        if($bAsObject) $ret=array();
        if(!isset($this->aProps['operatori']) || $this->aProps['operatori']=="") return $ret;
        
        if($bAsObject)
        {
            $ret=json_decode($this->aProps['operatori'],true);
            if($ret) return $ret;
            else
            {
                AA_Log::Log(__METHOD__." - Errore nell'importazione degli operatori del comune: ".$this->aProps['id'],100);
                return array();
            }
        }

        return $this->aProps['operatori'];
    }

    public function GetAffluenza($bAsObject=false)
    {
        $ret="";
        if($bAsObject) $ret=array();
        if(!isset($this->aProps['affluenza']) || $this->aProps['affluenza']=="") return $ret;
        
        if($bAsObject)
        {
            $ret=json_decode($this->aProps['affluenza'],true);
            //AA_Log::Log(__METHOD__." - affluenza: ".print_r($ret,true),100);

            if($ret) return $ret;
            else
            {
                AA_Log::Log(__METHOD__." - Errore nell'importazione dell'affluenza del comune: ".$this->aProps['id'],100);
                return array();
            }
        }

        return $this->aProps['affluenza'];
    }

    public function GetRisultati($bAsObject=false)
    {
        $ret="";
        if($bAsObject) $ret=array();
        if(!isset($this->aProps['risultati']) || $this->aProps['risultati']=="") return $ret;
        
        if($bAsObject)
        {
            $ret=json_decode($this->aProps['risultati'],true);
            //AA_Log::Log(__METHOD__." - affluenza: ".print_r($ret,true),100);

            if($ret) return $ret;
            else
            {
                AA_Log::Log(__METHOD__." - Errore nell'importazione dei risultati del comune: ".$this->aProps['id'],100);
                return array();
            }
        }

        return $this->aProps['rsultati'];
    }

    public function SetOperatori($operatori="")
    {
        if(is_array($operatori))
        {
            if(sizeof($operatori)>0)
            {
                $operatori=json_encode($operatori);
                if($operatori===false)
                {
                    AA_Log::Log(__METHOD__." - Errore nella codifica degli operatori. ".print_r($operatori,true),100);
                    return false;
                }    
            }
            else $operatori="";
        }

        $this->SetProp("operatori",$operatori);
        return true;
    }

    public function SetAffluenza($val="")
    {
        if(is_array($val))
        {
            if(sizeof($val)>0)
            {
                $affluenza=json_encode($val);
                if($affluenza===false)
                {
                    AA_Log::Log(__METHOD__." - Errore nella codifica degli operatori. ".print_r($affluenza,true),100);
                    return false;
                }    
            }
            else $affluenza="";
        }

        $this->SetProp("affluenza",$affluenza);
        return true;
    }

    public function SetRisultati($val="")
    {
        if(is_array($val))
        {
            if(sizeof($val)>0)
            {
                $risultati=json_encode($val);
                if($risultati===false)
                {
                    AA_Log::Log(__METHOD__." - Errore nella codifica dei risultati. ".print_r($risultati,true),100);
                    return false;
                }    
            }
            else $risultati="";
        }

        $this->SetProp("risultati",$risultati);
        return true;
    }

    public function __construct($params=null)
    {
        //Definisce le proprietà dell'oggetto e i valori di default
        $this->aProps['id']=0;
        $this->aProps['id_sier']=0;
        $this->aProps['id_circoscrizione']=0;
        $this->aProps['circoscrizione']="";
        $this->aProps['denominazione']="";
        $this->aProps['pec']="";
        $this->aProps['contatti']="";
        $this->aProps['indirizzo']="";
        $this->aProps['sezioni']=0;
        $this->aProps['elettori_m']=0;
        $this->aProps['elettori_f']=0;
        $this->aProps['affluenza']="";
        $this->aProps['risultati']="";
        $this->aProps['rendiconti']="";
        $this->aProps['operatori']="";
        $this->aProps['lastupdate']="";

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
                $query="SELECT * from ".static::AA_LISTE_DB_TABLE." WHERE id_coalizione='".$curRow['id']."' order by ".static::AA_LISTE_DB_TABLE.".ordine, ".static::AA_LISTE_DB_TABLE.".id";
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

    //Costruisce il feed dei risultati
    public function BuildRisultatiAffluenzaFeed($params=array())
    {
        if(!$this->bValid) return false;

        $feed=array("aggiornamento"=>date("Y-m-d H:i:s"),"comuni"=>array(),"stats"=>array("regionale"=>array("affluenza"=>array(),"risultati"=>array("sezioni_scrutinate"=>0,"votanti_m"=>0,"votanti_f"=>0,'voti_presidente'=>array(),"voti_lista"=>array(),"voti_candidato"=>array())),"circoscrizionale"=>array()));

        $comuni=$this->GetComuni();
        $coalizioni=$this->GetCoalizioni();
        $liste=$this->GetListe();
        $candidati=$this->GetCandidati();
        $giornateAffluenza=$this->GetGiornate();

        $voti_validi_regione=0;
        $voti_validi_circoscrizione=array();

        $platform=AA_Platform::GetInstance();
        $DefaultImagePath=AA_Const::AA_WWW_ROOT."/".$platform->GetModulePathURL(AA_SierModule::AA_ID_MODULE)."/img";

        foreach($comuni as $idComune=>$curComune)
        {
            $feed["comuni"][$idComune]=array("denominazione"=>$curComune->GetProp('denominazione'),"circoscrizione"=>$curComune->GetProp('circoscrizione'),"sezioni"=>$curComune->GetProp('sezioni'),"elettori_m"=>$curComune->GetProp('elettori_m'),"elettori_f"=>$curComune->GetProp('elettori_f'));
            $affluenza=$curComune->GetAffluenza(true);

            //dati generali
            if(!isset($feed['stats']['regionale']['sezioni'])) $feed['stats']['regionale']['sezioni']=0;
            if(!isset($feed['stats']['regionale']['elettori_m'])) $feed['stats']['regionale']['elettori_m']=0;
            if(!isset($feed['stats']['regionale']['elettori_f'])) $feed['stats']['regionale']['elettori_f']=0;
            $feed['stats']['regionale']['sezioni']+=$curComune->GetProp('sezioni');
            $feed['stats']['regionale']['elettori_m']+=$curComune->GetProp('elettori_m');
            $feed['stats']['regionale']['elettori_f']+=$curComune->GetProp('elettori_f');

            if(!isset($feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')])) $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]=array();
            if(!isset($feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['sezioni'])) $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['sezioni']=0;
            if(!isset($feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['elettori_m'])) $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['elettori_m']=0;
            if(!isset($feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['elettori_f'])) $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['elettori_f']=0;
            $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['denominazione']=$curComune->GetProp('circoscrizione');
            $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['sezioni']+=$curComune->GetProp('sezioni');
            $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['elettori_m']+=$curComune->GetProp('elettori_m');
            $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['elettori_f']+=$curComune->GetProp('elettori_f');
        
            //affluenza
            foreach($giornateAffluenza as $giornata=>$giornataValues)
            {
                if($giornataValues['affluenza']==1)
                {
                    if(!isset($feed['stats']['regionale']['affluenza'][$giornata])) $feed['stats']['regionale']['affluenza'][$giornata]=array("ore_12"=>0,"ore_19"=>0,"ore_22"=>0);
                    if(!isset($feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')])) $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]=array("affluenza"=>array(),"risultati"=>array("sezioni_scrutinate"=>0,"votanti_m"=>0,"votanti_f"=>0));
                    if(!isset($feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['affluenza'][$giornata])) $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['affluenza'][$giornata]=array("ore_12"=>0,"ore_19"=>0,"ore_22"=>0);

                    if(sizeof($affluenza)>0)
                    {
                        if(isset($affluenza[$giornata]))
                        {    
                            $feed['stats']['regionale']['affluenza'][$giornata]['ore_12']+=$affluenza[$giornata]['ore_12'];
                            $feed['stats']['regionale']['affluenza'][$giornata]['ore_19']+=$affluenza[$giornata]['ore_19'];
                            $feed['stats']['regionale']['affluenza'][$giornata]['ore_22']+=$affluenza[$giornata]['ore_22'];

                            $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['affluenza'][$giornata]['ore_12']+=$affluenza[$giornata]['ore_12'];
                            $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['affluenza'][$giornata]['ore_19']+=$affluenza[$giornata]['ore_19'];
                            $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['affluenza'][$giornata]['ore_22']+=$affluenza[$giornata]['ore_22'];

                            $feed['comuni'][$idComune]['affluenza']=$affluenza;
                        }
                    }
                    else
                    {
                        $feed['comuni'][$idComune]['affluenza']=array($giornata=>array("ore_12"=>-1,"ore_19"=>-1,"ore_22"=>-1));
                    }
                }
            }

            //risultati
            $risultati=$curComune->GetRisultati(true);
            if(!isset($feed['stats']['regionale']['risultati']['voti_presidente'])) $feed['stats']['regionale']['risultati']['voti_presidente']=array();
            if(!isset($feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')])) $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]=array("affluenza"=>array(),"risultati"=>array("sezioni_scrutinate"=>0,"votanti_m"=>0,"votanti_f"=>0));
            if(!isset($feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_presidente'])) $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_presidente']=array();
            if(!isset($feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_lista'])) $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_lista']=array();
            if(!isset($feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_candidato'])) $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_candidato']=array();

            $feed['comuni'][$idComune]['risultati']=array('sezioni_scrutinate'=>0,"votanti_m"=>0,"votanti_f"=>0);

            //risultati generali
            if(isset($risultati['sezioni_scrutinate']))
            {
                $feed['stats']['regionale']['risultati']['sezioni_scrutinate'] +=$risultati['sezioni_scrutinate'];
                $feed['stats']['regionale']['risultati']['votanti_m'] +=$risultati['votanti_m'];
                $feed['stats']['regionale']['risultati']['votanti_f'] +=$risultati['votanti_f'];

                $voti_validi_regione+=$risultati['votanti_f']+$risultati['votanti_m']-$risultati['schede_bianche']-$risultati['schede_nulle']-$risultati['voti_contestati_na_presidente']-$risultati['voti_contestati_na_liste']-$risultati['schede_voti_nulli'];
                $voti_validi_circoscrizione[$curComune->GetProp("id_circoscrizione")]+=$risultati['votanti_f']+$risultati['votanti_m']-$risultati['schede_bianche']-$risultati['schede_nulle']-$risultati['voti_contestati_na_presidente']-$risultati['voti_contestati_na_liste']-$risultati['schede_voti_nulli'];
                
                $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['sezioni_scrutinate'] +=$risultati['sezioni_scrutinate'];
                $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['votanti_m'] +=$risultati['votanti_m'];
                $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['votanti_f'] +=$risultati['votanti_f'];

                $feed['comuni'][$idComune]['risultati']=array('sezioni_scrutinate'=>$risultati['sezioni_scrutinate'],"votanti_m"=>$risultati['votanti_m'],"votanti_f"=>$risultati['votanti_f']);
            }

            //coalizioni
            foreach($coalizioni as $idCoalizione=>$curCoalizione)
            {
                $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
                if($curCoalizione->GetProp('image') != "")
                {
                    $curImagePath=AA_Const::AA_WWW_ROOT."/storage.php?object=".$curCoalizione->GetProp('image');
                }
                if(!isset($feed['stats']['regionale']['risultati']['voti_presidente'][$idCoalizione])) $feed['stats']['regionale']['risultati']['voti_presidente'][$idCoalizione]=array("denominazione"=>$curCoalizione->GetProp('nome_candidato'),"voti"=>0,"image"=>$curImagePath);
                if(!isset($feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_presidente'][$idCoalizione])) $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_presidente'][$idCoalizione]=array("denominazione"=>$curCoalizione->GetProp('nome_candidato'),"voti"=>0,"image"=>$curImagePath);
                
                if(isset($risultati['voti_presidente'][$idCoalizione]))
                {

                    $feed['stats']['regionale']['risultati']['voti_presidente'][$idCoalizione]['voti']+=$risultati['voti_presidente'][$idCoalizione];
                    $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_presidente'][$idCoalizione]['voti']+=$risultati['voti_presidente'][$idCoalizione];
                    $feed['comuni'][$idComune]['risultati']['voti_presidente'][$idCoalizione]=array("denominazione"=>$curCoalizione->GetProp('nome_candidato'),"voti"=>$risultati['voti_presidente'][$idCoalizione],"image"=>$curImagePath);
                }
                else
                {
                    $feed['comuni'][$idComune]['risultati']['voti_presidente'][$idCoalizione]=array("denominazione"=>$curCoalizione->GetProp('nome_candidato'),"voti"=>0,"image"=>$curImagePath);
                }
            }

            //liste
            foreach($liste as $idLista=>$curLista)
            {
                $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
                if($curLista->GetProp('image') != "")
                {
                    $curImagePath=AA_Const::AA_WWW_ROOT."/storage.php?object=".$curLista->GetProp('image');
                }
                if(!isset($feed['stats']['regionale']['risultati']['voti_lista'][$idLista])) $feed['stats']['regionale']['risultati']['voti_lista'][$idLista]=array("denominazione"=>$curLista->GetProp('denominazione'),"voti"=>0,"image"=>$curImagePath);
                if(!isset($feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_lista'][$idLista])) $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_lista'][$idLista]=array("denominazione"=>$curLista->GetProp('denominazione'),"voti"=>0,"image"=>$curImagePath);
                
                if(isset($risultati['voti_lista'][$idLista]))
                {

                    $feed['stats']['regionale']['risultati']['voti_lista'][$idLista]['voti']+=$risultati['voti_lista'][$idLista];
                    $feed['stats']['circoscrizionale'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_lista'][$idLista]['voti']+=$risultati['voti_lista'][$idLista];

                    $feed['comuni'][$idComune]['risultati']['voti_lista'][$idLista]=array("denominazione"=>$curLista->GetProp('denominazione'),"voti"=>$risultati['voti_lista'][$idLista],"image"=>$curImagePath);
                }
                else
                {
                    $feed['comuni'][$idComune]['risultati']['voti_lista'][$idLista]=array("denominazione"=>$curLista->GetProp('denominazione'),"voti"=>0,"image"=>$curImagePath);
                }
            }

            //preferenze
            foreach($candidati as $idCandidato=>$curCandidato)
            {                
                if(isset($risultati['voti_candidato'][$idCandidato]) && $risultati['voti_candidato'][$idCandidato]['voti'] > 0)
                {
                    $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
                    $lista=$liste[$curCandidato->GetProp('id_lista')];
                    $coalizione=$coalizioni[$risultati['voti_candidato'][$idCandidato]['id_coalizione']];

                    if($lista->GetProp('image') != "")
                    {
                        $curImagePath=AA_Const::AA_WWW_ROOT."/storage.php?object=".$lista->GetProp('image');
                    }

                    if(!isset($feed['stats']['regionale']['risultati']['voti_candidato'][$idCandidato]))
                    {
                        $feed['stats']['regionale']['risultati']['voti_candidato'][$idCandidato]=array("denominazione"=>$curCandidato->GetProp('cognome')." ".$curCandidato->GetProp('nome'),$risultati['voti_candidato'][$idCandidato]['id_lista'],$risultati['voti_candidato'][$idCandidato]['lista'],"id_presidente"=>$risultati['voti_candidato'][$idCandidato]['id_coalizione'],"presidente"=>$coalizione->GetProp("nome_candidato"),"image"=>$curImagePath,"voti"=>$risultati['voti_candidato'][$idCandidato]['voti']);
                    }
                    else $feed['stats']['regionale']['risultati']['voti_candidato'][$idCandidato]['voti'] += $risultati['voti_candidato'][$idCandidato]['voti'];

                    if(!isset($feed['stats']['circoscrizionale'][$curCandidato->GetProp('id_circoscrizione')]['risultati']['voti_candidato'][$idCandidato]))
                    {
                        $feed['stats']['circoscrizionale'][$curCandidato->GetProp('id_circoscrizione')]['risultati']['voti_candidato'][$idCandidato]=array("denominazione"=>$curCandidato->GetProp('cognome')." ".$curCandidato->GetProp('nome'),$risultati['voti_candidato'][$idCandidato]['id_lista'],$risultati['voti_candidato'][$idCandidato]['lista'],"id_presidente"=>$risultati['voti_candidato'][$idCandidato]['id_coalizione'],"presidente"=>$coalizione->GetProp("nome_candidato"),"image"=>"","voti"=>$risultati['voti_candidato'][$idCandidato]['voti']);
                    }
                    else $feed['stats']['circoscrizionale'][$curCandidato->GetProp('id_circoscrizione')]['risultati']['voti_candidato'][$idCandidato]['voti']+=$risultati['voti_candidato'][$idCandidato]['voti'];

                    //$feed['regionale']['stats']['risultati']['voti_lista'][$idLista]['voti']+=$risultati['voti_lista'][$idLista];
                    //$feed['circoscrizionale']['stats'][$curComune->GetProp('id_circoscrizione')]['risultati']['voti_lista']['voti']+=$risultati['voti_lista'][$idLista];

                    $feed['comuni'][$idComune]['risultati']['voti_candidato'][$idCandidato]=array("denominazione"=>$curCandidato->GetProp('cognome')." ".$curCandidato->GetProp('nome'));
                    $feed['comuni'][$idComune]['risultati']['voti_candidato'][$idCandidato]["id_lista"]=$risultati['voti_candidato'][$idCandidato]['id_lista'];
                    $feed['comuni'][$idComune]['risultati']['voti_candidato'][$idCandidato]["lista"]=$risultati['voti_candidato'][$idCandidato]['lista'];
                    $feed['comuni'][$idComune]['risultati']['voti_candidato'][$idCandidato]["image"]=$curImagePath;
                    $feed['comuni'][$idComune]['risultati']['voti_candidato'][$idCandidato]["id_presidente"]=$risultati['voti_candidato'][$idCandidato]['id_coalizione'];
                    $feed['comuni'][$idComune]['risultati']['voti_candidato'][$idCandidato]["presidente"]=$coalizione->GetProp("nome_candidato");
                    $feed['comuni'][$idComune]['risultati']['voti_candidato'][$idCandidato]["id_circoscrizione"]=$risultati['voti_candidato'][$idCandidato]['id_circoscrizione'];
                    $feed['comuni'][$idComune]['risultati']['voti_candidato'][$idCandidato]["circoscrizione"]=$risultati['voti_candidato'][$idCandidato]['circoscrizione'];
                    $feed['comuni'][$idComune]['risultati']['voti_candidato'][$idCandidato]["voti"]=$risultati['voti_candidato'][$idCandidato]['voti'];
                }
            }
        }

        //AA_Log::Log(__METHOD__." - voti validi regione: ".$voti_validi_regione,100);

        //calcolo percentuali
        $max_percent_coalizioni=0;
        $total_percent_coalizioni=0;
        foreach($coalizioni as $idPresidente=>$curPresidente)
        {
            if($voti_validi_regione > 0)
            {
                $feed['stats']['regionale']['risultati']['voti_presidente'][$idPresidente]['percent']=round($feed['stats']['regionale']['risultati']['voti_presidente'][$idPresidente]['voti']*100/(intVal($voti_validi_regione)),1);
                $total_percent_coalizioni+=$feed['stats']['regionale']['risultati']['voti_presidente'][$idPresidente]['percent'];
                if($max_percent_coalizioni==0 || $feed['stats']['regionale']['risultati']['voti_presidente'][$idPresidente]['percent']>$feed['stats']['regionale']['risultati']['voti_presidente'][$max_percent_coalizioni]['percent']) $max_percent_coalizioni=$idPresidente;
            }
        }

        if($total_percent_coalizioni < 100 && $max_percent_coalizioni > 0)
        {
            $feed['stats']['regionale']['risultati']['voti_presidente'][$max_percent_coalizioni]['percent']+=100-$total_percent_coalizioni;
        }

        foreach($liste as $idLista=>$curLista)
        {
            if($voti_validi_regione > 0) 
            {
                $feed['stats']['regionale']['risultati']['voti_lista'][$idLista]['percent']=round($feed['stats']['regionale']['risultati']['voti_lista'][$idLista]['voti']*100/(intVal($voti_validi_regione)),1);
            }
        }

        foreach(AA_Sier_Const::GetCircoscrizioni() as $idCircoscrizione=>$curCircoscrizione)
        {
            //AA_Log::Log(__METHOD__." - circoscrizione: ".print_r($curCircoscrizione,true),100);
            $max_percent_coalizioni=0;
            $total_percent_coalizioni=0;
            foreach($coalizioni as $idPresidente=>$curPresidente)
            {
                $voti=$feed['stats']['circoscrizionale'][$idCircoscrizione]['risultati']['voti_presidente'][$idPresidente]['voti'];
                AA_Log::Log(__METHOD__." - voti: ".print_r($voti,true),100);
                if($voti_validi_regione > 0) 
                {
                    $feed['stats']['circoscrizionale'][$idCircoscrizione]['risultati']['voti_presidente'][$idPresidente]['percent']=round($voti*100/(intVal($voti_validi_regione)),1);
                    $total_percent_coalizioni+=$feed['stats']['circoscrizionale'][$idCircoscrizione]['risultati']['voti_presidente'][$idPresidente]['percent'];
                    if($max_percent_coalizioni==0 || $feed['stats']['circoscrizionale'][$idCircoscrizione]['risultati']['voti_presidente'][$idPresidente]['percent']>$feed['stats']['circoscrizionale'][$idCircoscrizione]['risultati']['voti_presidente'][$max_percent_coalizioni]['percent']) $max_percent_coalizioni=$idPresidente;
                }
            }

            if($total_percent_coalizioni < 100 && $max_percent_coalizioni > 0)
            {
                $feed['stats']['circoscrizionale'][$idCircoscrizione]['risultati']['voti_presidente'][$max_percent_coalizioni]['percent']+=100-$total_percent_coalizioni;
            }

            foreach($liste as $idLista=>$curLista)
            {
                if($voti_validi_regione > 0) 
                {
                    $feed['stats']['circoscrizionale'][$idCircoscrizione]['risultati']['voti_lista'][$idLista]['percent']=round($feed['stats']['circoscrizionale'][$idCircoscrizione]['risultati']['voti_lista'][$idLista]['voti']*100/(intVal($voti_validi_regione)),1);
                }
            }
        }


        return $feed;

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
                $query="SELECT * from ".static::AA_LISTE_DB_TABLE." WHERE id_coalizione='".$curRow['id']."' order by ".static::AA_LISTE_DB_TABLE.".ordine, ".static::AA_LISTE_DB_TABLE.".id";
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
    public function GetListe($coalizione=null,$orderedForCircoscrizione=0)
    {
        if(!$this->bValid) return array();

        $db=new AA_Database();
        $query="SELECT ".static::AA_LISTE_DB_TABLE.".* from ".static::AA_LISTE_DB_TABLE." INNER JOIN ".static::AA_COALIZIONI_DB_TABLE." ON ".static::AA_LISTE_DB_TABLE.".id_coalizione=".static::AA_COALIZIONI_DB_TABLE.".id WHERE ".static::AA_COALIZIONI_DB_TABLE.".id_sier='".$this->nId_Data."'";

        if($coalizione instanceof AA_SierCoalizioni)
        {
            $query.=" AND ".static::AA_COALIZIONI_DB_TABLE.".id='".addslashes($coalizione->GetProp('id'))."'";
        }

        if($orderedForCircoscrizione==0) $query.=" ORDER BY ".static::AA_LISTE_DB_TABLE.".denominazione ";
        else $query.=" ORDER BY ".static::AA_LISTE_DB_TABLE.".ordine_".$orderedForCircoscrizione;

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore query: ".$query,100);
            return array();
        }

        //AA_Log::Log(__METHOD__." - query: ".$query,100);

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
    public function GetCandidati($coalizione=null,$lista=null,$circoscrizione=0)
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

        if($circoscrizione>0)
        {
            $query.=" AND ".static::AA_CANDIDATI_DB_TABLE.".id_circoscrizione='".addslashes($circoscrizione)."'";
        }

        $query.=" ORDER by ".static::AA_CANDIDATI_DB_TABLE.".ordine, ".static::AA_CANDIDATI_DB_TABLE.".id,".static::AA_CANDIDATI_DB_TABLE.".cognome, ".static::AA_CANDIDATI_DB_TABLE.".nome";

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

    //Restituisce i comuni
    public function GetComuni($circoscrizione=null,$params=null)
    {
        if(!$this->bValid) return array();

        $db=new AA_Database();
        $query="SELECT ".static::AA_COMUNI_DB_TABLE.".* from ".static::AA_COMUNI_DB_TABLE." WHERE ".static::AA_COMUNI_DB_TABLE.".id_sier='".$this->nId_Data."'";

        if(is_array($params))
        {
            if(isset($params['senza_operatori']) && $params['senza_operatori']==1)
            {
                $query.=" AND operatori like ''";
            }

            if(isset($params['senza_affluenza']) && $params['senza_affluenza']==1)
            {
                $query.=" AND affluenza like ''";
            }

            if(isset($params['senza_risultati']) && $params['senza_risultati']==1)
            {
                $query.=" AND risultati like ''";
            }

            if(isset($params['senza_rendiconti']) && $params['senza_rendiconti']==1)
            {
                $query.=" AND rendiconti like ''";
            }
        }

        $query.=" ORDER by ".static::AA_COMUNI_DB_TABLE.".denominazione";

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
                $result[$curRow['id']]=new AA_SierComune($curRow);
            }
        }

        return $result;
    }

    //Restituisce un comune specifico
    public function GetComune($id="",$cf_oc="")
    {
        if(!$this->bValid || (($id<=0 || $id=="") && $cf_oc=="")) return null;

        $db=new AA_Database();
        $query="SELECT ".static::AA_COMUNI_DB_TABLE.".* from ".static::AA_COMUNI_DB_TABLE." WHERE ".static::AA_COMUNI_DB_TABLE.".id_sier='".$this->nId_Data."'";

        if($id > 0)
        {
            $query.=" AND ".static::AA_COMUNI_DB_TABLE.".id ='".addslashes($id)."'";
        }

        if($cf_oc !="")
        {
            $query.=" AND (".static::AA_COMUNI_DB_TABLE.".operatori like '%\"cf\":\"".addslashes(strtolower($cf_oc))."\"%' OR ".static::AA_COMUNI_DB_TABLE.".operatori like '%\"cf\":\"".addslashes(strtoupper($cf_oc))."\"%')";
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
                $circoscrizione=AA_Sier_Const::GetCircoscrizione($curRow['id_circoscrizione']);
                if($circoscrizione) $curRow['circoscrizione']=$circoscrizione['value'];
                $result=new AA_SierComune($curRow);
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
        if(($perms & AA_Const::AA_PERMS_WRITE) > 0 && !$user->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER) && !$user->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
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
            $object=new AA_SierAllegati($rs[0]['id'],$id_sier,$rs[0]['estremi'],$rs[0]['url'],$rs[0]['file'],$rs[0]['tipo'],$rs[0]['aggiornamento'],$rs[0]['destinatari'],$rs[0]['ordine']);
            
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

        $db = new AA_Database();
        
        /*/Recupera il numero delle liste
        $query="SELECT count(id) as num FROM ".static::AA_LISTE_DB_TABLE." INNER JOIN ".static::AA_COALIZIONI_DB_TABLE." on ".static::AA_LISTE_DB_TABLE.".id_coalizione=".static::AA_COALIZIONI_DB_TABLE.".id WHERE ".static::AA_COALIZIONI_DB_TABLE.".id_sier='".$this->GetIdData()."'";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore nella query: ".$query, 100,false,true);
            return false;            
        }
        
        $ordine=1;
        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            $ordine=$rs[0]['num']+1;
        }*/
        
        $query="INSERT INTO ".static::AA_LISTE_DB_TABLE." SET id_coalizione='".$coalizione->GetProp('id')."'";
        $query.=", denominazione='".addslashes($lista->GetProp('denominazione'))."'";
        $query.=", image='".addslashes($lista->GetProp('image'))."'";
        
        foreach(AA_Sier_Const::GetCircoscrizioni() as $key=>$val)
        {
            $query.=" ,ordine_".$key." = '".$lista->GetProp("ordine_".$key)."'";
        }
        
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
         
        foreach(AA_Sier_Const::GetCircoscrizioni() as $key=>$val)
        {
            $query.=" ,ordine_".$key." = '".$lista->GetProp("ordine_".$key)."'";
        }

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

    //Aggiorna un comune esistente
    public function UpdateComune($newComune=null, $user=null,$AppendLog="")
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

        if(!($newComune instanceof AA_SierComune))
        {
            AA_Log::Log(__METHOD__." - Dati Comune non validi.", 100,false,true);
            return false;
        }
        
        $this->IsChanged();

        //Aggiorna l'elemento e lo versiona se necessario
        $log="Modifica comune: ".$newComune->GetProp('denominazione');
        if($AppendLog !="") $log.= " - ".$AppendLog;
        if(!$this->Update($user,true,$log))
        {
            AA_Log::Log(__METHOD__." - Errore nell'aggiornamento dell'oggetto.",100);
            return false;
        }

        $newComune->SetProp('id_sier',$this->nId_Data);
        if($this->nId_Data_Rev > 0)
        {
            $newComune->SetProp('id_sier',$this->nId_Data_Rev);
        }

        $query="UPDATE ".static::AA_COMUNI_DB_TABLE." SET id_sier='".$newComune->GetProp('id_sier')."'";
        $query.=", id_circoscrizione='".addslashes($newComune->GetProp('id_circoscrizione'))."'";
        $query.=", denominazione='".addslashes($newComune->GetProp('denominazione'))."'";
        $query.=", pec='".addslashes($newComune->GetProp('pec'))."'";
        $query.=", indirizzo='".addslashes($newComune->GetProp('indirizzo'))."'";
        $query.=", contatti='".addslashes($newComune->GetProp('contatti'))."'";
        $query.=", indirizzo='".addslashes($newComune->GetProp('indirizzo'))."'";
        $query.=", sezioni='".addslashes($newComune->GetProp('sezioni'))."'";
        $query.=", elettori_m='".addslashes($newComune->GetProp('elettori_m'))."'";
        $query.=", elettori_f='".addslashes($newComune->GetProp('elettori_f'))."'";
        $query.=", affluenza='".addslashes($newComune->GetProp('affluenza'))."'";
        $query.=", risultati='".addslashes($newComune->GetProp('risultati'))."'";
        $query.=", operatori='".addslashes($newComune->GetProp('operatori'))."'";
        $query.=", lastupdate='".date("Y-m-d H:i:s")."'";
        $query.=" WHERE id='".$newComune->GetProp('id')."' LIMIT 1";
        
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

        $query="INSERT INTO ".static::AA_ALLEGATI_DB_TABLE." SET id_sier='".$allegato->GetIdSier()."'";
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
        
        $query="UPDATE ".static::AA_ALLEGATI_DB_TABLE." SET id_sier='".$allegato->GetIdSier()."'";
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
            $allegato=new AA_SierAllegati($curRec['id'],$idData,$curRec['estremi'],$curRec['url'],$curRec['file'],$curRec['tipo'],$curRec['aggiornamento'],$curRec['destinatari'],$curRec['ordine']);
            $result[$curRec['id']]=$allegato;
        }

        return $result;
    }
}

#Classe operatore comunale
Class AA_SierOperatoreComunale
{
    protected static $oInstance=null;
    protected function __construct()
    {
       
    }

    static public function GetInstance()
    {
        if(static::$oInstance instanceof AA_SierOperatoreComunale) return static::$oInstance;

        if(isset($_SESSION['oc_object']) && static::$oInstance == null)
        {
            static::$oInstance=unserialize($_SESSION['oc_object']);

            if(static::$oInstance instanceof AA_SierOperatoreComunale) return static::$oInstance;
            else return new AA_SierOperatoreComunale();
        }

        return new AA_SierOperatoreComunale();
    }

    #funzioni di autenticazione
    public function ChallengeLogin($cf="",$objectId=0)
    {
        $object= new AA_Sier($objectId);
        if(!$object->IsValid())
        {
            AA_Log::Log(__METHOD__." - Oggetto SIER non valido.",100);
            return false;
        }

        $comune=$object->GetComune("",$cf);
        if($comune == null)
        {
            AA_Log::Log(__METHOD__." - Operatore non abilitato.",100);
            return false;
        }

        $operatori=$comune->GetOperatori(true);
        $operatore=$operatori[strtolower($cf)];
        if(!is_array($operatore))
        {
            $operatore=$operatori[strtoupper($cf)];
            if(!is_array($operatore))
            {
                AA_Log::Log(__METHOD__." - Operatore non valido.",100);
                return false;
            }
        }

        $this->nOperatoreComunaleComune=$comune->GetProp("id");
        $this->nOperatoreComunaleObjectId=$object->GetId();
        $this->sOperatoreComunaleCf=$operatore['cf'];
        $this->sOperatoreComunaleEmail=$operatore['email'];
        $this->sOperatoreComunaleNome=$operatore['nome'];
        $this->sOperatoreComunaleCognome=$operatore['cognome'];
        $this->sOperatoreComunaleRuolo=$operatore['ruolo'];
        $this->sOperatoreComunaleLastlogin=$operatore['lastlogin'];
        $this->bValid=true;

        //auth token
        $token="";
        for($i=0;$i<4;$i++)
        {
            $token.=random_int(10,99);
        }
        //AA_Log::Log(__METHOD__." - operatore comunale: ".$cf." - token: ".$token,100);

        $_SESSION['oc_auth_token']=$token;

        $_SESSION['oc_object']=serialize($this);

        //invia l'email

        $object="Amministrazione Aperta - autenticazione operatore comunale.";
        $corpo="Ciao ".$this->GetOperatoreComunaleNome().",<br>";
        $corpo.="di seguito il codice per l'accesso all'area operatori comunali della piattaforma 'Amministrazione Aperta', modulo SIER - Sistema Informativo Elettorale Regionale, per il caricamento dei dati elettorali e la rispettiva redincontazione.";
        $corpo.="<div style='text-align: center;border: 1px solid gray; width: 400px; font-size:larger'><span><b>".$token."</b></span></div>";
        $corpo.="Inserisci il codice sull'apposita finestra a video e fai click sul pulsante 'Verifica' per accedere al cruscotto applicativo.";
        $corpo.="<p>Per ogni eventuale richiesta di supporto o segnalazione di malfunzionamenti è disponibile la casella: amministrazioneaperta@regione.sardegna.it</p>";
        $corpo.="Cordiali Saluti.";
        
        $mailparams=AA_User::GetResetPwdEmailParams();

        if(AA_Const::AA_ENABLE_SENDMAIL)
        {
            if(!SendMail(array($operatore['email']),"",$object,$corpo.$mailparams['firma'],null,1))
            {
                AA_Log::Log(__METHoD__." - Errore nell'invio della email a: ".$operatore['email'],100);
                return false;
            }
            return true;
        }
        else
        {
            AA_Log::Log(__METHOD__." - cf: ".$cf." - auth token: ".$token,100);
            return true;
        }

        return false;
    }
    public function VerifyLogin($token="")
    {
        if(!$this->IsValid())
        {
            AA_Log::Log(__METHOD__." - Operatore non valido.",100);
            return false;
        }

        if($token=="" || strlen($token) != 8)
        {
            AA_Log::Log(__METHOD__." - Token non valido.",100);
            return false;
        }

        if(!isset($_SESSION['oc_auth_token']))
        {
            AA_Log::Log(__METHOD__." - Token non valido (2).",100);
            return false;
        }

        if($_SESSION['oc_auth_token']!=$token)
        {
            AA_Log::Log(__METHOD__." - Il codice indicato non corrisponde a quello inviato via email.",100);
            return false;
        }

        unset($_SESSION['oc_auth_token']);

        $this->bOperatoreComunaleLogged=true;
        $this->sOperatoreComunaleLastlogin=date("Y-m-d");
        $_SESSION['oc_object']=serialize($this);
        return true;
    }
    #----------------------------

    protected $sOperatoreComunaleNome="Nessuno";
    protected $sOperatoreComunaleCognome="";
    protected $sOperatoreComunaleRuolo=0;
    protected $sOperatoreComunaleLastlogin="";
    protected $sOperatoreComunaleCf="";
    protected $sOperatoreComunaleEmail="";
    protected $bOperatoreComunaleLogged=false;
    protected $nOperatoreComunaleComune=0;
    protected $nOperatoreComunaleObjectId=0;
    protected $bValid=false;
    public function IsValid()
    {
        return $this->bValid;
    }
    public function IsLogged()
    {
        return $this->bOperatoreComunaleLogged;
    }
    public function GetOperatoreComunaleNome()
    {
        return $this->sOperatoreComunaleNome;
    }
    protected function SetOperatoreComunaleNome($val="")
    {
        $this->sOperatoreComunaleNome=$val;
    }
    public function GetOperatoreComunaleCognome()
    {
        return $this->sOperatoreComunaleCognome;
    }
    protected function SetOperatoreComunaleCognome($val="")
    {
        $this->sOperatoreComunaleCognome=$val;
    }
    public function GetOperatoreComunaleRuolo()
    {
        return $this->sOperatoreComunaleRuolo;
    }
    protected function SetOperatoreComunaleRuolo($val=0)
    {
        $this->sOperatoreComunaleRuolo=$val;
    }
    public function GetOperatoreComunaleLastlogin()
    {
        return $this->sOperatoreComunaleLastlogin;
    }
    protected function SetOperatoreComunaleLastlogin($val="")
    {
        $this->sOperatoreComunaleLastlogin=$val;
    }
    public function GetOperatoreComunaleCf()
    {
        return $this->sOperatoreComunaleCf;
    }
    protected function SetOperatoreComunaleCf($val="")
    {
        $this->sOperatoreComunaleCf=$val;
    }
    public function GetOperatoreComunaleComune()
    {
        return $this->nOperatoreComunaleComune;
    }
    protected function SetOperatoreComunaleComune($val=0)
    {
        $this->nOperatoreComunaleComune=$val;
    }
    public function GetOperatoreComunaleObjectId()
    {
        return $this->nOperatoreComunaleObjectId;
    }
    protected function SetOperatoreComunaleObject($val=0)
    {
        $this->nOperatoreComunaleObjectId=$val;
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

    //Dialoghi
    const AA_UI_WND_OPERATORI_COMUNALI="OperatoriComunaliWnd";
    const AA_UI_LAYOUT_OPERATORI_COMUNALI="OperatoriComunaliLayout";
    const AA_UI_WND_RENDICONTI_COMUNALI="RendicontiComunaliWnd";
    const AA_UI_LAYOUT_RENDICONTI_COMUNALI="RendicontiComunaliLayout";
    const AA_UI_WND_RISULTATI_COMUNALI="RisultatiComunaliWnd";
    const AA_UI_LAYOUT_RISULTATI_COMUNALI="RisultatiComunaliLayout";
    const AA_UI_WND_AFFLUENZA_COMUNALE="AffluenzaComunaleWnd";
    const AA_UI_LAYOUT_AFFLUENZA_COMUNALE="AffluenzaComunaleLayout";

    //Section id
    const AA_ID_SECTION_OC_LOGIN="OperatoriComunaliLogin";
    const AA_ID_SECTION_OC_DESKTOP= "OperatoriComunaliDesktop";

    //section ui ids
    const AA_UI_DETAIL_GENERALE_BOX = "Generale_Box";
    const AA_UI_DETAIL_LISTE_BOX = "Liste_Box";
    const AA_UI_DETAIL_CANDIDATI_BOX = "Candidati_Box";
    const AA_UI_DETAIL_COMUNI_BOX = "Comuni_Box";
    const AA_UI_DETAIL_CRUSCOTTO_BOX = "Cruscotto_Box";
    const AA_UI_DETAIL_ALLEGATI_BOX = "Allegati_Box";
    const AA_UI_SECTION_OC_LOGIN = "OperatoriComunaliLoginBox";
    const AA_UI_SECTION_OC_DESKTOP= "OperatoriComunaliDesktopBox";

    protected $bOperatoreComunaleInterface=false;
    public function IsOperatoreComunaleUIEnabled()
    {
        return $this->bOperatoreComunaleInterface;
    }
    
    public function EnableOperatoreComunaleInterface($val=true)
    {
        if($val) $this->bOperatoreComunaleInterface=true;
        else $this->bOperatoreComunaleInterface=false;
    }

    public function __construct($user=null,$bDefaultSections=true)
    {
        if(!($user instanceof AA_user))
        {
            $user=AA_User::GetCurrentUser();
        }

        #-------------------------------- Verifica se è un operatore comunale ----------------
        if(isset($_SESSION['oc_ui_enable']) && $_SESSION['oc_ui_enable']==1 && isset($_SESSION['oc_sier_object']) && $_SESSION['oc_sier_object'] > 0 && $user->IsValid() && $user->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
        {
            $this->bOperatoreComunaleInterface=true;
            $bDefaultSections=false;
        }
        #-------------------------------------------------------------------------------------

        parent::__construct($user,$bDefaultSections);

        #-------------------------------- Registrazione dei task -----------------------------
        $taskManager=$this->GetTaskManager();
        
        if(!$this->IsOperatoreComunaleUIEnabled())
        {
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

            //comune
            $taskManager->RegisterTask("GetSierComuneDatiGeneraliViewDlg");
            $taskManager->RegisterTask("UpdateSierComuneDatiGenerali");

            $taskManager->RegisterTask("GetSierComuneAffluenzaViewDlg");
            $taskManager->RegisterTask("GetSierComuneAffluenzaAddNewDlg");
            $taskManager->RegisterTask("AddNewSierComuneAffluenza");
            $taskManager->RegisterTask("GetSierComuneAffluenzaModifyDlg");
            $taskManager->RegisterTask("UpdateSierComuneAffluenza");

            $taskManager->RegisterTask("GetSierComuneRisultatiViewDlg");
            $taskManager->RegisterTask("GetSierComuneRisultatiGeneraliModifyDlg");
            $taskManager->RegisterTask("UpdateSierComuneRisultatiGenerali");
            $taskManager->RegisterTask("GetSierComuneRisultatiCoalizioniModifyDlg");
            $taskManager->RegisterTask("UpdateSierComuneRisultatiCoalizioni");
            $taskManager->RegisterTask("GetSierComuneRisultatiListeModifyDlg");
            $taskManager->RegisterTask("UpdateSierComuneRisultatiListe");
            $taskManager->RegisterTask("GetSierComuneRisultatiPreferenzeModifyDlg");
            $taskManager->RegisterTask("UpdateSierComuneRisultatiPreferenze");
            $taskManager->RegisterTask("GetSierComuneRisultatiPreferenzeTrashDlg");
            $taskManager->RegisterTask("TrashSierComuneRisultatiPreferenze");

            $taskManager->RegisterTask("GetSierComuneOperatoriViewDlg");
            $taskManager->RegisterTask("GetSierComuneOperatoriAddNewDlg");
            $taskManager->RegisterTask("AddNewSierComuneOperatore");
            $taskManager->RegisterTask("GetSierComuneOperatoriModifyDlg");
            $taskManager->RegisterTask("UpdateSierComuneOperatore");
            $taskManager->RegisterTask("GetSierComuneOperatoriTrashDlg");
            $taskManager->RegisterTask("TrashSierComuneOperatore");
            $taskManager->RegisterTask("GetSierComuneFilterDlg");

            //feed
            $taskManager->RegisterTask("GetSierFeedRisultatiAffluenza");

            //template dettaglio
            $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL,array(
                array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateSierDettaglio_Generale_Tab"),
                //array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_CRUSCOTTO_TAB, "value"=>"Cruscotto","tooltip"=>"Cruscotto di gestione","template"=>"TemplateSierDettaglio_Cruscotto_Tab"),
                array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_LISTE_BOX, "value"=>"<span style='font-size: smaller'>Coalizioni e Liste</span>","tooltip"=>"Gestione coalizioni e liste","template"=>"TemplateSierDettaglio_Coalizioni_Tab"),
                array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_CANDIDATI_BOX, "value"=>"Candidati","tooltip"=>"Gestione dei Candidati","template"=>"TemplateSierDettaglio_Candidati_Tab"),
                array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_COMUNI_BOX, "value"=>"Comuni","tooltip"=>"Gestione dei Comuni","template"=>"TemplateSierDettaglio_Comuni_Tab"),
                array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_ALLEGATI_BOX, "value"=>"<span style='font-size: smaller'>Documenti</span>","tooltip"=>"Gestione degli allegati e links","template"=>"TemplateSierDettaglio_Allegati_Tab"),
            ));
        }
        else
        {
            $oc=AA_SierOperatoreComunale::GetInstance();
            //AA_Log::Log(__METHOD__." - operatore: ".print_r($oc,true),100);
            $template="TemplateSection_OC_Desktop";

            if(!$oc->IsLogged())
            {
                //login tasks
                $taskManager->RegisterTask("OCLogin");
                $taskManager->RegisterTask("GetOCLoginVerifyDlg");
                $taskManager->RegisterTask("OCVerifyLogin");

                $template="TemplateSection_OC_Login";
            }
            else
            {
                $taskManager->RegisterTask("GetSierOCModifyDatiGeneraliDlg");
                $taskManager->RegisterTask("Update_OC_ComuneDatiGenerali");
                $taskManager->RegisterTask("GetSierOCAffluenzaAddNewDlg");
                $taskManager->RegisterTask("Update_OC_ComuneAffluenza");
                $taskManager->RegisterTask("GetSierOCModifyRisultatiGeneraliDlg");
                $taskManager->RegisterTask("Update_OC_ComuneRisultatiGenerali");
                $taskManager->RegisterTask("GetSierOCModifyRisultatiCoalizioniDlg");
                $taskManager->RegisterTask("Update_OC_ComuneRisultatiCoalizioni");
                $taskManager->RegisterTask("GetSierOCModifyRisultatiListeDlg");
                $taskManager->RegisterTask("Update_OC_ComuneRisultatiListe");
                $taskManager->RegisterTask("GetSierOCModifyRisultatiPreferenzeDlg");
                $taskManager->RegisterTask("Update_OC_ComuneRisultatiPreferenze");
            }

            //desktop
            $section=new AA_GenericModuleSection(static::AA_ID_SECTION_OC_DESKTOP,"Cruscotto operatore comunale",true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_OC_DESKTOP,$this->GetId(),true,true,false,true);
            $section->SetNavbarTemplate(array($this->TemplateGenericNavbar_Void(1,true)->toArray()));

            $this->AddSection($section);
            $this->SetSectionItemTemplate(static::AA_ID_SECTION_OC_DESKTOP,$template);
        }
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
        //anno rif
        if($params['Anno'] > 0)
        {
            $params['where'][]=" AND ".AA_Sier::AA_DBTABLE_DATA.".anno = '".addslashes($params['AnnoRiferimento'])."'";
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
       
        if($this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER) || $this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
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
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER))
        {
            $content->EnableAddNew(false);
            $content->EnablePublish(false);
            $content->EnableReassign(false);
            $content->EnableTrash(false);
        } 

        return $content->toObject();
    }
    
    //Restituisce i dati delle bozze
    public function GetDataSectionBozze_List($params=array())
    {
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER) && !$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
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
        $circoscrizioni=AA_Sier_Const::GetCircoscrizioni();
        foreach($circoscrizioni as $key=>$val)
        {
            $form_data["ordine_".$key]=0;
        }

        $wnd=new AA_GenericFormDlg($id, "Aggiungi una nuova Lista", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(800);

        //denominazione
        $wnd->AddTextField("denominazione", "Denominazione", array("required"=>true,"labelWidth"=>150,"bottomLabel" => "*Indicare la denominazione della Lista.", "placeholder" => "..."));

        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("type"=>"spacer","height"=>30)));

        //ordine
        $section=new AA_FieldSet($id."_Section_Ordine","Ordine di visualizzazione per circoscrizione");
        $numCirc=0;
        foreach($circoscrizioni as $key=>$val)
        {
            if($numCirc%2) $section->AddTextField("ordine_".$key, $val, array("required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Ordine per ".$val, "value" => 0),false);
            else $section->AddTextField("ordine_".$key, $val, array("required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Ordine per ".$val, "value" => 0));
            $numCirc++;
        }
        $wnd->AddGenericObject($section);

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
        
        $form_data=array("id_coalizione"=>$coalizione->GetProp('id'),"id_lista"=>$lista->GetProp('id'),"denominazione"=>$lista->GetProp("denominazione"),'ordine'=>$lista->GetProp("ordine"));
        $circoscrizioni=AA_Sier_Const::GetCircoscrizioni();
        foreach($circoscrizioni as $key=>$val)
        {
            $form_data["ordine_".$key]=$lista->GetProp("ordine_".$key);
        }

        $wnd=new AA_GenericFormDlg($id, "Modifica Lista", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(800);

        //denominazione
        $wnd->AddTextField("denominazione", "Denominazione", array("required"=>true,"gravity"=>3,"labelWidth"=>150,"bottomLabel" => "*Indicare la denominazione della Lista.", "placeholder" => "..."));

        //ordine
        $section=new AA_FieldSet($id."_Section_Ordine","Ordine di visualizzazione per circoscrizione");
        $numCirc=0;
        foreach($circoscrizioni as $key=>$val)
        {
            if($numCirc%2) $section->AddTextField("ordine_".$key, $val, array("required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Ordine per ".$val, "value" => 0),false);
            else $section->AddTextField("ordine_".$key, $val, array("required"=>true,"labelWidth"=>150,"labelAlign"=>"right","bottomLabel" => "*Ordine per ".$val, "value" => 0));
            $numCirc++;
        }
        $wnd->AddGenericObject($section);
        
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

    //Template dlg aggiungi operatore
    public function Template_GetSierComuneOperatoriAddNewDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierComuneOperatoriAddNewDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("aggiornamento"=>date("Y-m-d"),"id_comune"=>$comune->GetProp("id"));
        
        $wnd=new AA_GenericFormDlg($id, "Aggiungi operatore", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(480);

        //Imposta il controllo su cui abilitare il focus
        $wnd->SetDefaultFocusedItem("cognome");

        //Cognome
        $wnd->AddTextField("cognome", "Cognome", array("required"=>true,"bottomLabel" => "*Indicare il cognome dell'operatore", "placeholder" => "es. Verdi"));

        //nome
        $wnd->AddTextField("nome", "Nome", array("required"=>true,"bottomLabel" => "*Indicare il nome dell'operatore", "placeholder" => "es. Giuseppe"));

        //cf
        $wnd->AddTextField("cf", "Codice fiscale", array("required"=>true,"bottomLabel" => "*Indicare il codice fiscale dell'operatore, se presente."));

        //email
        $wnd->AddTextField("email", "Email", array("required"=>true,"bottomLabel" => "*Indicare l'email dell'operatore", "placeholder" => "giuseppe@verdi.it"));

        //Ruolo
        $options=array();
        $options[]=array("id"=>"1","value"=>"Caricamento dati");
        $options[]=array("id"=>"2","value"=>"Caricamento rendiconti");
        $options[]=array("id"=>"3","value"=>"Caricamento dati e rendiconti");
        
        $wnd->AddSelectField("ruolo", "Ruolo", array("required"=>true, "validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere una voce dal menu a tendina", "options"=>$options));

        $wnd->EnableCloseWndOnSuccessfulSave();
        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("AddNewSierComuneOperatore");
        
        return $wnd;
    }
    
    //Template dlg modifca operatore
    public function Template_GetSierComuneOperatoriModifyDlg($object=null,$comune=null,$operatore=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierComuneOperatoriModifyDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("aggiornamento"=>date("Y-m-d"),"id_comune"=>$comune->GetProp("id"));

        if($operatore)
        {
            $form_data["nome"]=$operatore['nome'];
            $form_data["cognome"]=$operatore['cognome'];
            $form_data["cf"]=strtoupper($operatore['cf']);
            $form_data["ruolo"]=$operatore['ruolo'];
            $form_data["email"]=$operatore['email'];
            $form_data["lastlogin"]=$operatore['lastlogin'];
        }

        $wnd=new AA_GenericFormDlg($id, "Modifica operatore", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(480);

        //Imposta il controllo su cui abilitare il focus
        $wnd->SetDefaultFocusedItem("cognome");

        //Cognome
        $wnd->AddTextField("cognome", "Cognome", array("required"=>true,"bottomLabel" => "*Indicare il cognome dell'operatore", "placeholder" => "es. Verdi"));

        //nome
        $wnd->AddTextField("nome", "Nome", array("required"=>true,"bottomLabel" => "*Indicare il nome dell'operatore", "placeholder" => "es. Giuseppe"));

        //cf
        $wnd->AddTextField("cf", "Codice fiscale", array("required"=>true,"bottomLabel" => "*Indicare il codice fiscale dell'operatore, se presente."));

        //email
        $wnd->AddTextField("email", "Email", array("required"=>true,"bottomLabel" => "*Indicare l'email dell'operatore", "placeholder" => "giuseppe@verdi.it"));

        //Ruolo
        $options=array();
        $options[]=array("id"=>"1","value"=>"Caricamento dati");
        $options[]=array("id"=>"2","value"=>"Caricamento rendiconti");
        $options[]=array("id"=>"3","value"=>"Caricamento dati e rendiconti");
        
        $wnd->AddSelectField("ruolo", "Ruolo", array("required"=>true, "validateFunction"=>"IsSelected","bottomLabel" => "*Scegliere una voce dal menu a tendina", "options"=>$options));

        $wnd->EnableCloseWndOnSuccessfulSave();
        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("UpdateSierComuneOperatore");
        
        return $wnd;
    }

    //Template dlg modifica candidato
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

    //Template dlg modifica candidato
    public function Template_GetOCVerifyLoginDlg($object=null)
    {
        $id=static::AA_UI_PREFIX."_GetOCVerifyLoginDlg";
        
        //AA_Log:Log(__METHOD__." form data: ".print_r($form_data,true),100);
        
        $form_data=array("aggiornamento"=>date("Y-m-d"));
        
        $wnd=new AA_GenericFormDlg($id, "Codice di verifica operatore", $this->id,$form_data,$form_data);
        
        //$wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(100);
        $wnd->SetBottomPadding(30);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(640);
        $wnd->SetHeight(480);

        //Imposta il controllo su cui abilitare il focus
        $wnd->SetDefaultFocusedItem("codice");

        //Cognome
        $wnd->AddTextField("codice", "Codice", array("required"=>true,"bottomLabel" => "*Indicare il codice di 8 cifre pervenuto via email."));

        $wnd->SetApplyButtonName("Verifica");
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        $wnd->SetSaveTask("OCVerifyLogin");
        
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
        $tipi=AA_Sier_Const::GetTipoAllegati();$curRow=1;
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
        $destinatari=AA_Sier_Const::GetDestinatari();$curRow=1;
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
        $tipologia=AA_Sier_Const::GetTipoAllegati();
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
        $tipi=AA_Sier_Const::GetTipoAllegati();$curRow=1;
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
        $destinatari=AA_Sier_Const::GetDestinatari();$curRow=1;
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

    //Template dlg trash candidato
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

    //Template dlg trash candidato
    public function Template_GetSierComuneOperatoriTrashDlg($object=null,$comune=null,$operatore=null)
    {
        $id=$this->id."_TrashComuneOperatori_Dlg";
        
        $form_data=$operatore;
        $form_data["id_comune"]=$comune->GetProp('id');
        
        $wnd=new AA_GenericFormDlg($id, "Elimina operatore", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("denominazione"=>$operatore["nome"]." ".$operatore["cognome"],"cf"=>strtoupper($operatore["cf"]),"email"=>$operatore["email"]);
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Le informazioni del seguente candidato verrànno eliminate, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"denominazione", "header"=>"Candidato", "fillspace"=>true),
              array("id"=>"cf", "header"=>"cf", "width"=>150),
              array("id"=>"email", "header"=>"email", "width"=>150)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
        $wnd->SetSaveTask("TrashSierComuneOperatore");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        
        return $wnd;
    }

    //Template dlg trash candidato
    public function Template_GetSierComuneRisultatiPreferenzeTrashDlg($object=null,$comune=null,$candidato=null)
    {
        $id=$this->id."_TrashComuneRisultatiPreferenze_Dlg";
        
        $form_data=array("id_candidato"=>$candidato->GetProp("id"));
        $form_data["id_comune"]=$comune->GetProp('id');
        
        $wnd=new AA_GenericFormDlg($id, "Elimina preferenze candidato", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(280);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("denominazione"=>$candidato->GetProp("cognome")." ".$candidato->GetProp("nome"),"lista"=>$candidato->GetProp("lista"));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"I voti attribuiti al seguente candidato verrànno eliminati, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"denominazione", "header"=>"Candidato", "fillspace"=>true),
              array("id"=>"lista", "header"=>"Lista", "width"=>150)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
        $wnd->SetSaveTask("TrashSierComuneRisultatiPreferenze");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId()));
        
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

        //destinatari
        $destinatari=AA_Sier_Const::GetDestinatari();
        $newDestinatari=array();
        foreach($destinatari as $destinatario=>$descr)
        {
            if(isset($_REQUEST['destinatari_'.$destinatario]) && $_REQUEST['destinatari_'.$destinatario]==1) $newDestinatari[]=$destinatario;
        }
        //----

        //tipologia
        $tipi=AA_Sier_Const::GetTipoAllegati();
        $newTipo=array();
        foreach($tipi as $tipo=>$descr)
        {
            if(isset($_REQUEST['tipo_'.$tipo]) && $_REQUEST['tipo_'.$tipo]==1) $newTipo[]=$tipo;
        }
        //--------------

        $ordine=0;
        if(isset($_REQUEST['ordine']) && $_REQUEST['ordine']>0) $ordine=$_REQUEST['ordine'];
        $allegato=new AA_SierAllegati(0,$id_sier,$_REQUEST['estremi'],$_REQUEST['url'],$fileHash,implode(",",$newTipo),$aggiornamento,implode(",",$newDestinatari),addslashes($ordine));
        
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
        //$sTaskLog="<status id='status' action='dlg' action_params='".json_encode($params)."'>0</status><content id='content'>";
        $sTaskLog="<status id='status'>0</status><content id='content'>";
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
            "cv"=>$fileHash,
            "ordine"=>$candidato->GetProp('ordine')
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
            "cg"=>$fileHash,
            "ordine"=>$candidato->GetProp('ordine')

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
            "cg"=>$fileHash,
            "ordine"=>$candidato->GetProp('ordine')
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
            "cv"=>$fileHash,
            "ordine"=>$candidato->GetProp('ordine')
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
            "cv"=>"",
            "ordine"=>$candidato->GetProp('ordine')
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
            "cg"=>"",
            "ordine"=>$candidato->GetProp('ordine')
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

        foreach(AA_Sier_Const::GetCircoscrizioni() as $key=>$val)
        {
            $params["ordine_".$key]=0;
            if(isset($_REQUEST['ordine_'.$key])) $params['ordine_'.$key]=$_REQUEST['ordine_'.$key];
        }

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

        $params=array(
            'id'=>$lista->GetProp('id'),
            'id_coalizione'=>$coalizione->GetProp('id'),
            'denominazione'=>$_REQUEST['denominazione'],
            'image'=>$imageFileHash
        );
         
        foreach(AA_Sier_Const::GetCircoscrizioni() as $key=>$val)
        {
            $params["ordine_".$key]=0;
            if(isset($_REQUEST['ordine_'.$key])) $params['ordine_'.$key]=$_REQUEST['ordine_'.$key];
        }

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
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER)) $params['readonly']=true;
        
        $detail = $this->TemplateGenericSection_Detail($params);

        return $detail;
    }   
    
    //Template section detail, tab generale
    public function TemplateSierDettaglio_Generale_Tab($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX;

        if(!($object instanceof AA_Sier)) return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        
        $rows_fixed_height=50;
        $canModify=false;
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0 && $this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER)) $canModify=true;

        $layout=$this->TemplateGenericDettaglio_Header_Generale_Tab($object,$id,null,$canModify);

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

    //Template section oc login
    public function TemplateSection_OC_Login($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_OC_DESKTOP;

        if(!($object instanceof AA_Sier))
        {
            $object=new AA_Sier($_SESSION['oc_sier_object']);
            if(!$object->IsValid())
            {
                return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi","name"=>"Accesso operatore comunale"));
            }
        } 
        
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean","name"=>"Accesso operatore comunale"));

        //login disabilitato
        $sier_flags=$object->GetAbilitazioni();
        if(($sier_flags&AA_Sier_Const::AA_SIER_FLAG_ACCESSO_OPERATORI)==0)
        {
            $layout->AddRow(new AA_JSON_Template_Template($id."_TemplateVoid",array("template"=>"<div style='display: flex; justify-content:center; align-items: center;width:100%;height:100%; font-weight: bold'><span>Accesso operatori temporaneamente disabilitato.</span></div>")));
            return $layout;
        }
        
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $layout->AddRow(new AA_JSON_Template_Template($id."_TemplateVoid",array("template"=>"<div style='display: flex; justify-content:center; align-items: center'><div>L'utente corrente non è abilitato all'accesso come operatore comunale.</div></div>")));
            return $layout;
        }

        $form=new AA_JSON_Template_Form($id."_LoginOCForm",array(
            "maxWidth"=>400,
            "elementsConfig"=>array("labelWidth"=>180, "labelAlign"=>"left", "labelPosition"=>"top","bottomPadding"=>15),
            "padding"=>15,
            "validation"=>"validateForm",
            "css"=>array("background-color"=>"#F3FAFD !important", "border"=>"solid 1px 1198FF","border-radius"=>"5px !important", "font-size"=>"smaller")
        ));

        $formBox=new AA_JSON_Template_Layout($id."_FormBox",array("type"=>"space","css"=>array("background-color"=>"transparent")));
        $form->AddElement(new AA_JSON_Template_Text($id."LoginOC_cf",array("required"=>true,"name"=>"cf","label"=>"<b>Codice fiscale</b>")));

        $params = "{task: 'OCLogin'";
        $params .= ", data: $$('" . $id . "_LoginOCForm').getValues()}";
        $script="if($$('" . $id . "_LoginOCForm').validate())";
        $script.=" AA_MainApp.utils.callHandler('saveData',$params,'".$this->GetId()."')";

        $form_button_layout=new AA_JSON_Template_Layout($id."_LoginButton",array("type"=>"clean","autoheight"=>true,"css"=>array("background-color"=>"transparent")));
        $form_button_layout->AddCol(new AA_JSON_Template_Generic(""));
        $btn_login=new AA_JSON_Template_Generic($id."_Accedi_btn",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-login",
             "label"=>"Accedi",
             "css"=>"webix_primary",
             "align"=>"center",
             "width"=>120,
             "tooltip"=>"Accedi",
             "click"=>$script
         ));
        $form_button_layout->AddCol($btn_login);
        $form_button_layout->AddCol(new AA_JSON_Template_Generic(""));

        $form->AddElement($form_button_layout);
        $header_layout=new AA_JSON_Template_Layout($id."_Header_box",array("type"=>"clean"));
        $header_layout->AddRow(new AA_JSON_Template_Generic());
        $header_layout->AddRow(new AA_JSON_Template_Template($id."_PreviewHeaderTitle",array("template"=>"<div style='display:flex;justify-content:center; align-items:center;'><span style='font-size: 24px; color:#006699'>".$object->GetName()."</span></div>","align"=>"center")));
        $header_layout->AddRow(new AA_JSON_Template_Generic());
        $formBox->AddCol(new AA_JSON_Template_Generic());
        $formBox->AddCol($form);
        $formBox->AddCol(new AA_JSON_Template_Generic());

        $layout->AddRow($header_layout);
        $layout->AddRow($formBox);
        $layout->AddRow(new AA_JSON_Template_Generic());

        return $layout;
    }

    //Template section oc login
    public function TemplateSection_OC_Desktop($object=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_OC_DESKTOP;

        if(!($object instanceof AA_Sier))
        {
            $object=new AA_Sier($_SESSION['oc_sier_object']);
            if(!$object->IsValid())
            {
                return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi","name"=>"Accesso operatore comunale"));
            }
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        $intestazione="Cruscotto operatore - comune di ";
        if($comune instanceof AA_SierComune)
        {
            $intestazione.=$comune->GetProp("denominazione");     
        }
        
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean","name"=>$intestazione));

        $sier_flags=$object->GetAbilitazioni();
        $sections=array(
            array("id"=>$id."_Generale_".$operatore->GetOperatoreComunaleComune(),"value"=>"Dati generali e corpo elettorale","icon"=>"mdi mdi-information-variant-circle"),
            array("id"=>$id."_Affluenza_".$operatore->GetOperatoreComunaleComune(),"value"=>"Dati affluenza","icon"=>"mdi mdi-vote"),
            array("id"=>$id."_Risultati_".$operatore->GetOperatoreComunaleComune(),"value"=>"Risultati","icon"=>"mdi mdi-stack-overflow"),
            array("id"=>$id."_Rendiconti_".$operatore->GetOperatoreComunaleComune(),"value"=>"Rendicontazione","icon"=>"mdi mdi-cash-sync")
        );

        $multiview = new AA_JSON_Template_Multiview($id . "_Multiview_".$object->GetId(), array(
            "type" => "clean",
            "css" => "AA_Detail_Content",
            "value" => $id."_OC_PreviewBox".$operatore->GetOperatoreComunaleComune()
        ));

        $riepilogo_template="<div class='AA_DataView_Moduli_item' onclick=\"#onclick#\" style='cursor: pointer; border: 1px solid; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 97%; margin:5px;'>";
        //icon
        $riepilogo_template.="<div style='display: flex; align-items: center; height: 120px; font-size: 90px;'><span class='#icon#'></span></div>";
        //name
        $riepilogo_template.="<div style='display: flex; align-items: center;justify-content: center; flex-direction: column; font-size: larger;height: 60px'>#name#</div>";
        $riepilogo_template.="</div>";

        $nMod=0;
        $moduli_view=new AA_JSON_Template_Layout($id."_OC_PreviewBox".$operatore->GetOperatoreComunaleComune(),array("type"=>"clean","css"=>array("background-color"=>"transparent")));
        foreach($sections as $curModId=>$curMod)
        {
            $nMod++;
            //AA_Log::Log(__METHOD__." - Aggiungo la slide: ".$id."_ModuliView_".$nSlide." - nMod: ".$nMod ,100);
            $name="<span style='font-weight:900;'>".implode("</span><span>",explode("-",$curMod['value']))."</span>";
            $onclick="AA_MainApp.utils.callHandler('OC_SectionBoxClick','".$curMod['id']."','".$this->GetId()."')";
            $moduli_data=array("id"=>$curModId,"name"=>$name,'descr'=>$curMod['descrizione'],"icon"=>$curMod['icon'],"onclick"=>$onclick);
            $moduli_view->AddCol(new AA_JSON_Template_Template($id."_ModuleBox_".$moduli_data['id'],array("template"=>$riepilogo_template,"borderless"=>true,"data"=>array($moduli_data))));
        }
        $preview_layout=new AA_JSON_Template_Layout($id."_PreviewBox_".$operatore->GetOperatoreComunaleComune(),array("type"=>"clean","minHeight"=>300));
        $preview_header_box=new AA_JSON_Template_Layout($id."_PreviewHeaderBox_".$operatore->GetOperatoreComunaleComune(),array("type"=>"clean","minHeight"=>300));
        $preview_header_box->AddRow(new AA_JSON_Template_Generic());
        $preview_header_box->AddRow(new AA_JSON_Template_Generic($id."_PreviewHeaderTitle",array("view"=>"label","label"=>"<span style='font-size: 24px; color:#006699'>".$object->GetName()."</span>","align"=>"center")));
        $preview_header_box->AddRow(new AA_JSON_Template_Template($id."_PreviewHeaderContent",array("template"=>"<div style='display: flex; justify-content:center; align-center: center; width: 100%; height:100%'><p>Ciao <b>".$operatore->GetOperatoreComunaleNome()."</b>, fai click su uno dei box sottostanti per accedere alla relativa sezione.</p></div>")));
        $preview_layout->AddRow($preview_header_box);
        $preview_layout->AddRow($moduli_view);
        $preview_layout->AddRow(new AA_JSON_Template_Generic());
        $multiview->AddCell($preview_layout);

        //------------- header ------------------
        $header = new AA_JSON_Template_Layout($id . "_Header" . "_".$object->GetId(), array("type" => "clean", "height" => 38, "css" => "AA_SectionContentHeader"));
        $canModify=false;
        $layout_tab=new AA_JSON_Template_Layout($id . "_Layout_TabBar_".$object->GetId(),array("type"=>"clean","minWidth"=>500));
        $gravity_tabbar=4;
        $layout_tab->AddCol(new AA_JSON_Template_Generic($id . "_TabBar_".$object->GetId(), array(
            "view" => "tabbar",
            "gravity"=>$gravity_tabbar,
            "borderless" => true,
            "value" => $id."_Generale_".$operatore->GetOperatoreComunaleComune(),
            "css" => "AA_Header_TabBar",
            "multiview" => true,
            "view_id" => $id . "_Multiview_".$object->GetId(),
            "options" => array(
                array("id"=>$id."_Generale_".$operatore->GetOperatoreComunaleComune(),"value"=>"Dati generali e corpo elettorale"),
                array("id"=>$id."_Affluenza_".$operatore->GetOperatoreComunaleComune(),"value"=>"Dati affluenza"),
                array("id"=>$id."_Risultati_".$operatore->GetOperatoreComunaleComune(),"value"=>"Risultati"),
                array("id"=>$id."_Rendiconti_".$operatore->GetOperatoreComunaleComune(),"value"=>"Rendicontazione"),
            )
        )));
        $header->AddCol($layout_tab);
        //$layout->AddRow($layout_tab);
        //-------------------------------------------------------

        //-------------------   Scheda generale  ------------------------------
        if (($sier_flags&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_DATIGENERALI) > 0) $canModify=true;
        $layout_generale=new AA_JSON_Template_Layout($id."_Generale_".$operatore->GetOperatoreComunaleComune(),array("type"=>"clean"));
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #c0c0c0 !important","background-color"=>"#dedede !important")));
        //$toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        
        //torna al riepilogo
        $toolbar->AddElement(new AA_JSON_Template_Generic($id."_OC_Generale_Back".$operatore->GetOperatoreComunaleComune()."_btn",array(
            "view"=>"button",
            "type"=>"icon",
            "icon"=>"mdi mdi-keyboard-backspace",
            "label"=>"Riepilogo",
            "align"=>"left",
            "width"=>120,
            "tooltip"=>"Torna al riepilogo",
            "click"=>"$$('".$id."_PreviewBox_".$operatore->GetOperatoreComunaleComune()."').show()"
        )));

        $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_OC_Generale_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Dati generali</span>", "align"=>"center")));
        
        //Pulsante di modifica
        if($canModify)
        {            
            $modify_btn=new AA_JSON_Template_Generic($id."_OC_Modify_Generale_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil",
                "label"=>"Modifica",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Modifica dati generali e corpo elettorale",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierOCModifyDatiGeneraliDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        else
        {
            $toolbar->addElement(new AA_JSON_Template_Generic("",array("width"=>120)));
        }
        
        $layout_generale->addRow($toolbar);
        $layout_generale->addRow($this->Template_OC_Generale($id,$object,$comune));
        $multiview->addCell($layout_generale);
        //----------------------------------------------------------------------------

        //-------------------   Affluenza  ------------------------------
        $canModify=false;
        if (($sier_flags&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_AFFLUENZA) > 0) $canModify=true;
        $layout_generale=new AA_JSON_Template_Layout($id."_Affluenza_".$operatore->GetOperatoreComunaleComune(),array("type"=>"clean"));
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #c0c0c0 !important","background-color"=>"#dedede !important")));
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        //torna al riepilogo
        $toolbar->AddElement(new AA_JSON_Template_Generic($id."_OC_Affluenza_Back_".$operatore->GetOperatoreComunaleComune()."_btn",array(
            "view"=>"button",
            "type"=>"icon",
            "icon"=>"mdi mdi-keyboard-backspace",
            "label"=>"Riepilogo",
            "align"=>"left",
            "width"=>120,
            "tooltip"=>"Torna al riepilogo",
            "click"=>"$$('".$id."_PreviewBox_".$operatore->GetOperatoreComunaleComune()."').show()"
        )));
        $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_OC_Affluenza_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Dati affluenza alle urne</span>", "align"=>"center")));
        
        //Pulsante di modifica
        if($canModify)
        {            
            $modify_btn=new AA_JSON_Template_Generic($id."_OC_Modify_Affluenza_btn",array(
                "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil",
                "label"=>"Modifica",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi/Modifica dati affluenza",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierOCAffluenzaAddNewDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        else
        {
            $toolbar->addElement(new AA_JSON_Template_Generic("",array("width"=>120)));
        }
        
        $layout_generale->addRow($toolbar);
        $layout_generale->addRow($this->Template_OC_Affluenza($id,$object,$comune));
        $multiview->addCell($layout_generale);
        //----------------------------------------------------------------------------

        //------------------------------  Risultati  ---------------------------------
        $canModify=false;
        if (($sier_flags&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI) > 0) $canModify=true;
        $layout_generale=new AA_JSON_Template_Layout($id."_Risultati_".$operatore->GetOperatoreComunaleComune(),array("type"=>"clean"));
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #c0c0c0 !important","background-color"=>"#dedede !important")));
        //torna al riepilogo
        $toolbar->AddElement(new AA_JSON_Template_Generic($id."_OC_Risultati_Back_".$operatore->GetOperatoreComunaleComune()."_btn",array(
            "view"=>"button",
            "type"=>"icon",
            "icon"=>"mdi mdi-keyboard-backspace",
            "label"=>"Riepilogo",
            "align"=>"left",
            "width"=>120,
            "tooltip"=>"Torna al riepilogo",
            "click"=>"$$('".$id."_PreviewBox_".$operatore->GetOperatoreComunaleComune()."').show()"
        )));
        $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_OC_Risultati_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Dati risultati consultazioni</span>", "align"=>"center")));
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("width"=>120)));
        
        $layout_generale->addRow($toolbar);
        $layout_generale->addRow($this->Template_OC_Risultati($id,$object,$comune));
        $multiview->addCell($layout_generale);
        //----------------------------------------------------------------------------

        //--------------------------------   Rendiconti  -----------------------------
        $canModify=false;
        if (($sier_flags&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RENDICONTI) > 0) $canModify=true;
        $layout_generale=new AA_JSON_Template_Layout($id."_Rendiconti_".$operatore->GetOperatoreComunaleComune(),array("type"=>"clean"));
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        //torna al riepilogo
        $toolbar->AddElement(new AA_JSON_Template_Generic($id."_OC_Rendiconti_Back".$operatore->GetOperatoreComunaleComune()."_btn",array(
            "view"=>"button",
            "type"=>"icon",
            "icon"=>"mdi mdi-keyboard-backspace",
            "label"=>"Riepilogo",
            "align"=>"left",
            "width"=>120,
            "tooltip"=>"Torna al riepilogo",
            "click"=>"$$('".$id."_PreviewBox_".$operatore->GetOperatoreComunaleComune()."').show()"
        )));
        $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_OC_Rendiconti_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Rendicontazione spese e rimborsi</span>", "align"=>"center")));
        
        //Pulsante di modifica
        if($canModify)
        {            
            $modify_btn=new AA_JSON_Template_Generic($id."_OC_Modify_Rendiconti_btn",array(
                "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil",
                "label"=>"Modifica",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Modifica rendicontazioni",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierOCModifyRendicontiDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        else
        {
            $toolbar->addElement(new AA_JSON_Template_Generic("",array("width"=>120)));
        }
        
        $layout_generale->addRow($toolbar);
        $layout_generale->addRow(new AA_JSON_Template_Generic());

        //to do

        $multiview->addCell($layout_generale);
        //----------------------------------------------------------------------------

        $layout->AddRow($multiview);
    
        return $layout;
    }

    //Template OC generale
    public function Template_OC_Generale($id,$object=null,$comune=null,$modify=false)
    {
        $id.=$id."_Generale_Content";
        $operatore=AA_SierOperatoreComunale::GetInstance();
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
        
        //Descrizione
        $value=$comune->GetProp("denominazione");
        $descr=new AA_JSON_Template_Template($id."_Denominazione",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Denominazione:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //Circoscrizione
        $value=$comune->GetProp("circoscrizione");
        $circoscrizione=new AA_JSON_Template_Template($id."_Circoscrizione",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Circoscrizione:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //Pec
        $value=$comune->GetProp("pec");
        $pec=new AA_JSON_Template_Template($id."_Pec",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Pec:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));
        
        //Indirizzo
        $value=$comune->GetProp("indirizzo");
        $indirizzo=new AA_JSON_Template_Template($id."_Indirizzo",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Indirizzo:","value"=>$value),
            "css"=>array("border-bottom"=>"1px solid #dadee0 !important")
        ));

        //note
        $value = $comune->GetProp("contatti");
        $note=new AA_JSON_Template_Template($id."_Contatti",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Note e contatti:","value"=>$value)
        ));

        //sezioni
        $value = $comune->GetProp("sezioni");
        $sezioni=new AA_JSON_Template_Template($id."_Sezioni",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Sezioni:","value"=>$value)
        ));

        //elettori maschi
        $value = $comune->GetProp("elettori_m");
        $elettori_m=new AA_JSON_Template_Template($id."_Elettori_m",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Elettori maschi:","value"=>$value)
        ));

        //elettori femmine
        $value = $comune->GetProp("elettori_f");
        $elettori_f=new AA_JSON_Template_Template($id."_Elettori_f",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Elettori femmine:","value"=>$value)
        ));
        
        $rows_fixed_height=60;
        
        //prima riga
        $riga=new AA_JSON_Template_Layout($id."_FirstRow",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($descr);
        $riga->AddCol($circoscrizione);
        $layout->AddRow($riga);

        //seconda riga
        $riga=new AA_JSON_Template_Layout($id."_SecondRow",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($indirizzo);
        $riga->AddCol($pec);
        $layout->AddRow($riga);

        //terza riga
        $riga=new AA_JSON_Template_Layout($id."_ThirdRow",array("type"=>"clean","height"=>80,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($note);
        $layout->AddRow($riga);

        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important","background-color"=>"#dedede !important")));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("width"=>120)));
        $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_OC_Generale_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Corpo elettorale</span>", "align"=>"center")));
        
        //Pulsante di modifica
        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_CORPO_ELETTORALE)>0)
        {            
            $modify_btn=new AA_JSON_Template_Generic($id."_OC_Modify_Generale_btn",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil",
                "label"=>"Modifica",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Modifica dati generali e corpo elettorale",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierOCModifyDatiGeneraliDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        else
        {
            $toolbar->addElement(new AA_JSON_Template_Generic("",array("width"=>120)));
        }

        $layout->AddRow($toolbar);
        //corpo elettorale
        $riga=new AA_JSON_Template_Layout($id."_QuadRow",array("type"=>"clean"));
        $riga->AddCol($sezioni);
        $riga->AddCol($elettori_m);
        $riga->AddCol($elettori_f);
        $layout->AddRow($riga);

        return $layout;
    }

    //Template OC generale
    public function Template_OC_Risultati($id,$object=null,$comune=null,$modify=false)
    {
        $id.=$id."_Risultati_Content";
        $operatore=AA_SierOperatoreComunale::GetInstance();
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
        
        if(!$object) $object=new AA_Sier($_REQUEST['id']);
        if(!$object->isValid())
        {
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
            $layout->AddRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Errore nel recupero dei dati.</span></div>")));
            return $layout;
        }

        if(!$comune) $comune = $object->GetComune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
            $layout->AddRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Errore nel recupero dei dati del comune.</span></div>")));
            return $layout;
        }

        $risultati=$comune->GetRisultati(true);
        $rows_fixed_height=50;

        $id.="_".static::AA_UI_LAYOUT_RISULTATI_COMUNALI;
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        $multiview=new AA_JSON_Template_Multiview($id . "_Multiview_".$object->GetId(),array(
            "type" => "clean",
            "css" => "AA_Detail_Content",
            "value" => $id."_RisultatiGeneraleBox")
        );

        //---------------------------- header --------------------------------
        $header = new AA_JSON_Template_Layout($id . "_Header" . "_".$object->GetId(), array("type" => "clean", "height" => 38, "css" => "AA_SectionContentHeader"));
        $canModify=false;
        $layout_tab=new AA_JSON_Template_Layout($id . "_Layout_TabBar_".$object->GetId(),array("type"=>"clean","minWidth"=>500));
        $gravity_tabbar=4;
        $layout_tab->AddCol(new AA_JSON_Template_Generic($id . "_TabBar_".$object->GetId(), array(
            "view" => "tabbar",
            "gravity"=>$gravity_tabbar,
            "borderless" => true,
            "value" => $id."_RisultatiGeneraleBox",
            "css" => "AA_Header_TabBar",
            "multiview" => true,
            "view_id" => $id . "_Multiview_".$object->GetId(),
            "options" => array(
                array("id"=>$id."_RisultatiGeneraleBox","value"=>"Risultati generali"),
                array("id"=>$id."_RisultatiCoalizioniBox","value"=>"Voti candidati Presidente"),
                array("id"=>$id."_RisultatiListeBox","value"=>"Voti Liste"),
                array("id"=>$id."_RisultatiPreferenzeBox","value"=>"Voti candidati Consiglio Regionale"),
            )
        )));
        $header->AddCol($layout_tab);
        $layout->AddRow($header);
        //---------------------------------------------------------------------
        $layout->AddRow($multiview);
        
        //--------------------------- Dati generali ----------------------------
        $generaleLayout=new AA_JSON_Template_Layout($id."_RisultatiGeneraleBox",array("type"=>"clean"));
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));

        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI) > 0)
        {
            
            $modify_btn=new AA_JSON_Template_Generic($id."_ModifyRisultatiGenerali_btn",array(
                "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil",
                "label"=>"Modifica",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Modifica dati generali dei risultati",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierOCModifyRisultatiGeneraliDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},'".$this->id."')"
            ));

            $toolbar->AddElement($modify_btn);
        }
        $generaleLayout->addRow($toolbar);

        $template="<div style='display: flex; align-items:center;justify-content: flex-start; width:99%;height:100%;padding-left:1%;'><div style='font-weight:700;width: 350px;'>#title#</div><div style='width: 150px; text-align: right; padding-right:50px;'>#value#</div></div>";
        
        //Sezioni scrutinate
        if(isset($risultati['sezioni_scrutinate']))$value=$risultati['sezioni_scrutinate'];
        else $value=0;
        $sezioni=new AA_JSON_Template_Template($id."_SezioniScrutinate",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Sezioni scrutinate:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        //votanti maschi
        if(isset($risultati['votanti_m']))$value=$risultati['votanti_m'];
        else $value=0;
        $votanti_m=new AA_JSON_Template_Template($id."_VotantiM",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Votanti maschi:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        //votanti femmine
        if(isset($risultati['votanti_f']))$value=$risultati['votanti_f'];
        else $value=0;
        $votanti_f=new AA_JSON_Template_Template($id."_VotantiF",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Votanti femmine:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        $riga=new AA_JSON_Template_Layout($id."_DatiGeneraliRow",array("css"=>array("border-bottom"=>"1px solid #dadee0 !important","type"=>"clean")));
        $riga->AddRow($sezioni);
        $riga->AddRow($votanti_m);
        $riga->AddRow($votanti_f);
        //$generaleLayout->AddRow($riga);

        //Voti contestati non assegnati
        if(isset($risultati['voti_contestati_na']))$value=$risultati['voti_contestati_na'];
        else $value=0;
        $voti_contestati=new AA_JSON_Template_Template($id."_VotiContestatiNA",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Voti contestati non assegnati:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        //Schede nulle
        if(isset($risultati['schede_nulle']))$value=$risultati['schede_nulle'];
        else $value=0;
        $schede_nulle=new AA_JSON_Template_Template($id."_SchedeNulle",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Schede nulle:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        //Schede bianche
        if(isset($risultati['schede_bianche']))$value=$risultati['schede_bianche'];
        else $value=0;
        $schede_bianche=new AA_JSON_Template_Template($id."_SchedeBianche",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Schede bianche:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        //Schede contenenti esclusivamente voti nulli
        if(isset($risultati['schede_voti_nulli']))$value=$risultati['schede_voti_nulli'];
        else $value=0;
        $schede_voti_nulli=new AA_JSON_Template_Template($id."_SchedeVotiNulli",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Schede contenenti esclusivamente voti nulli:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        //$riga=new AA_JSON_Template_Layout($id."_SecondRow",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddRow($schede_bianche);
        $riga->AddRow($schede_nulle);
        $riga->AddRow($voti_contestati);
        //$generaleLayout->AddRow($riga);

        //$riga=new AA_JSON_Template_Layout($id."_ThirdRow",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddRow($schede_voti_nulli);
        
        //$generaleLayout->AddCol(new AA_JSON_Template_Generic());
        $generaleLayout->AddRow($riga);
        $generaleLayout->AddRow(new AA_JSON_Template_Generic("",array("height"=>38,"css"=>array("border-top"=>"1px solid #dadee0"))));

        //$generaleLayout->AddRow(new AA_JSON_Template_Generic());
        $multiview->AddRow($generaleLayout);
        //-------------------------------------------------------------------------------------

        //----------------------------- Risultati voti coalizioni -----------------------------
        $generaleLayout=new AA_JSON_Template_Layout($id."_RisultatiCoalizioniBox",array("type"=>"clean"));

        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI) > 0)
        {
            $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar_RisultatiCoalizioni",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
            $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
    
           
            $modify_btn=new AA_JSON_Template_Generic($id."_ModifyRisultatiCoalizioni_btn",array(
                "view"=>"button",
                 "type"=>"icon",
                 "icon"=>"mdi mdi-pencil",
                 "label"=>"Modifica",
                 "css"=>"webix_primary",
                 "align"=>"right",
                 "width"=>120,
                 "tooltip"=>"Modifica dati delle coalizioni",
                 "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierOCModifyRisultatiCoalizioniDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},'".$this->id."')"
            ));

            $toolbar->AddElement($modify_btn);
            $generaleLayout->addRow($toolbar);
        }

        $platform=AA_Platform::GetInstance($this->oUser);
        $DefaultImagePath=AA_Const::AA_WWW_ROOT."/".$platform->GetModulePathURL($this->id)."/img";

        $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
        $template="<div style='display: flex; align-items:center;justify-content: flex-start; width:99%;height:100%;padding-left:1%;'><div class='AA_DataView_Sier_item' style='display: flex; align-items: center; max-height:80px'><div style='display: flex; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; overflow: clip; margin-right: 1em;'><img src='#image#' height='100%'/></div><div style='font-weight:700;width: 350px;'>#title#</div><div style='width: 150px; text-align: right; padding-right: 50px'>#value#</div></div></div>";
        $coalizioni=$object->GetCoalizioni();
        if(sizeof($coalizioni)>0)
        {
            foreach($coalizioni as $idCoalizione=>$curCoalizione)
            {
                if($curCoalizione->GetProp('image') != "")
                {
                    $curImagePath=AA_Const::AA_WWW_ROOT."/storage.php?object=".$curCoalizione->GetProp('image');
                }

                //voti coalizione
                if(isset($risultati['voti_presidente']) && isset($risultati['voti_presidente'][$idCoalizione])) $value=$risultati['voti_presidente'][$idCoalizione];
                else $value=0;
                $row=new AA_JSON_Template_Template($id."_VotiCoalizione_".$idCoalizione,array(
                    "template"=>$template,
                    "gravity"=>1,
                    "type"=>"clean",
                    "data"=>array("title"=>$curCoalizione->GetProp("nome_candidato").":","value"=>$value,"image"=>$curImagePath),
                    "css"=>array("border-right"=>"1px solid #dadee0")
                ));
                $generaleLayout->AddRow($row);
            }    
        }
        else
        {
            $generaleLayout->AddRow(new AA_JSON_Template_Generic());
        }

        $generaleLayout->AddRow(new AA_JSON_Template_Generic("",array("height"=>38,"css"=>array("border-top"=>"1px solid #dadee0 !important"))));

        $multiview->AddRow($generaleLayout);
        //-------------------------------------------------------------------------------------

        //------------------------------- Risultati voti liste --------------------------------
        $generaleLayout=new AA_JSON_Template_Layout($id."_RisultatiListeBox",array("type"=>"clean"));

        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI) > 0)
        {
            $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar_RisultatiListe",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
            $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));

            $modify_btn=new AA_JSON_Template_Generic($id."_ModifyRisultatiListe_btn",array(
                "view"=>"button",
                 "type"=>"icon",
                 "icon"=>"mdi mdi-pencil",
                 "label"=>"Modifica",
                 "css"=>"webix_primary",
                 "align"=>"right",
                 "width"=>120,
                 "tooltip"=>"Modifica voti Liste circoscrizionali",
                 "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierOCModifyRisultatiListeDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},'".$this->id."')"
            ));

            $toolbar->AddElement($modify_btn);
            $generaleLayout->addRow($toolbar);
        }

        $platform=AA_Platform::GetInstance($this->oUser);
        $DefaultImagePath=AA_Const::AA_WWW_ROOT."/".$platform->GetModulePathURL($this->id)."/img";

        $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
        $template="<div style='display: flex; align-items:center;justify-content: space-between; width:99%;height:100%;padding-left:1%;'><div style='display: flex; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; overflow: clip; margin-right: 1em;'><img src='#image#' height='100%'/></div><div style='font-weight:700; width: 250px'>#title#</div><div style='width: 80px; text-align:center'>#value#</div></div>";
        
        $liste=$object->GetListe();
        //AA_Log::Log(__METHOD__." - liste: ".print_r($liste,true),100);
        if(sizeof($liste)>0)
        {
            $liste_data=array();
            foreach($liste as $idLista=>$curLista)
            {
                if($curLista->GetProp('image') != "")
                {
                    $curImagePath=AA_Const::AA_WWW_ROOT."/storage.php?object=".$curLista->GetProp('image');
                }
                else
                {
                    $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
                }

                $value=0;
                if(isset($risultati['voti_lista']) && isset($risultati['voti_lista'][$idLista]) && $risultati['voti_lista'][$idLista]>0) $value=intVal($risultati['voti_lista'][$idLista]);
                $liste_data[]=array("id"=>$idLista,"title"=>$curLista->GetProp("denominazione"),"value"=>$value,"image"=>$curImagePath);
            }
            
            //AA_Log::Log(__METHOD__." - liste: ".print_r($liste_data,true),100);

            $dataview_liste=new AA_JSON_Template_Generic($id."_ListeDataView",array(
                "view"=>"dataview",
                "xCount"=>4,
                "module_id"=>$this->id,
                "type"=>array(
                    "type"=>"tiles",
                    "height"=>80,
                    "width"=>"auto",
                    "css"=>"AA_DataView_Sier_item",
                ),
                //"on" => array("onItemDblClick" => "AA_MainApp.utils.getEventHandler('ListaDblClick','".$this->GetId()."')"),
                "template"=>$template,
                "data"=>$liste_data
            ));
            $generaleLayout->AddRow($dataview_liste);
        }
        else
        {
            $generaleLayout->AddRow(new AA_JSON_Template_Template($id."_FakeListe",array("template"=>"non ci sono liste definite.")));
        }

        $generaleLayout->AddRow(new AA_JSON_Template_Generic("",array("height"=>38,"css"=>array("border-top"=>"1px solid #dadee0 !important"))));
        $multiview->AddRow($generaleLayout);
        //-------------------------------------------------------------------------------------

        //------------------------------ Risultati preferenze ---------------------------------
        $generaleLayout=new AA_JSON_Template_Layout($id."_RisultatiPreferenzeBox",array("type"=>"clean"));

        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI) > 0)
        {
            $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar_RisultatiPreferenze",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
            $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
    
           
            $modify_btn=new AA_JSON_Template_Generic($id."_OC_ModifyRisultatiPreferenze_btn",array(
                "view"=>"button",
                 "type"=>"icon",
                 "icon"=>"mdi mdi-pencil",
                 "label"=>"Modifica",
                 "css"=>"webix_primary",
                 "align"=>"right",
                 "width"=>120,
                 "tooltip"=>"Modifica preferenze candidato",
                 "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierOCModifyRisultatiPreferenzeDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},'".$this->id."')"
            ));

            $toolbar->AddElement($modify_btn);
            $generaleLayout->addRow($toolbar);
        }

        $platform=AA_Platform::GetInstance($this->oUser);
        $DefaultImagePath=AA_Const::AA_WWW_ROOT."/".$platform->GetModulePathURL($this->id)."/img";

        $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
        $template="<div style='display: flex; align-items:center;justify-content: space-between; width:99%;height:100%;padding-left:1%;'><div style='display: flex; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; overflow: clip; margin-right: 1em;'><img src='#image#' height='100%' width='100%'/></div><div style='font-weight:700; width: 250px'>#title#</div><div style='width: 80px; text-align:center'>#value#</div><div style='display: flex;  align-items: center; justify-content: space-between; height: 100%; padding: 5px; width: 60px'>#modify#&nbsp;#trash#</div></div>";
        
        $candidati=$object->GetCandidati(null,null,$comune->GetProp("id_circoscrizione"));
        //AA_Log::Log(__METHOD__." - liste: ".print_r($liste,true),100);
        $candidati_data=array();
        foreach($candidati as $idCandidato=>$curCandidato)
        {
            $lista=$object->GetLista($curCandidato->GetProp("id_lista"));
            if($lista->GetProp('image') != "")
            {
                $curImagePath=AA_Const::AA_WWW_ROOT."/storage.php?object=".$curLista->GetProp('image');
            }
            else
            {
                $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
            }

            if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI) > 0)
            {
                $modify="<a title='Modifica' class='AA_Button_Link' onclick='AA_MainApp.utils.callHandler(\"dlg\", {task:\"GetSierOCModifyRisultatiPreferenzeDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",id_candidato:".$idCandidato.",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},\"".$this->id."\")'><span class='mdi mdi-pencil'></span></a>";
                $trash="<a title='Rimuovi' class='AA_Button_Link AA_DataTable_Ops_Button_Red' style='color: red' onclick='AA_MainApp.utils.callHandler(\"dlg\", {task:\"GetSierOCTrashRisultatiPreferenzeDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",id_candidato:".$idCandidato.",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},\"".$this->id."\")'><span class='mdi mdi-trash-can'></span></a>";
            }
            else
            {
                $modify="&nbsp;";
                $trash="&nbsp;";
            }

            $value=0;
            if(isset($risultati['voti_candidato']) && isset($risultati['voti_candidato'][$idCandidato]) && $risultati['voti_candidato'][$idCandidato]['voti']>0)
            {
                $value=intVal($risultati['voti_candidato'][$idCandidato]['voti']);
                $candidati_data[]=array("id"=>$idCandidato,"title"=>$curCandidato->GetProp("cognome")." ".$curCandidato->GetProp("nome"),"value"=>$value,"image"=>$curImagePath,"modify"=>$modify,"trash"=>$trash);
            }
        }
    
        if(sizeof($candidati_data)>0)
        {
            $dataview_liste=new AA_JSON_Template_Generic($id."_OC_CandidatiDataView",array(
                "view"=>"dataview",
                "xCount"=>3,
                "module_id"=>$this->id,
                "type"=>array(
                    "type"=>"tiles",
                    "height"=>80,
                    "width"=>"auto",
                    "css"=>"AA_DataView_Sier_item",
                ),
                //"on" => array("onItemDblClick" => "AA_MainApp.utils.getEventHandler('ListaDblClick','".$this->GetId()."')"),
                "template"=>$template,
                "data"=>$candidati_data
            ));
            $generaleLayout->AddRow($dataview_liste);
        }
        else
        {
            $generaleLayout->AddRow(new AA_JSON_Template_Template($id."_FakePreferenze",array("template"=>"<div style='display:flex;justify-content: center; align-items: center;widht:100;height:100%'>non ci sono preferenze definite.</div>")));
        }

        $generaleLayout->AddRow(new AA_JSON_Template_Generic("",array("height"=>38,"css"=>array("border-top"=>"1px solid #dadee0 !important"))));
        $multiview->AddRow($generaleLayout);
        //-------------------------------------------------------------------------------------

        return $layout;
    }

    //Template OC affuenza
    public function Template_OC_Affluenza($id,$object=null,$comune=null,$modify=false)
    {
        $id.=$id."_Affluenza_Content";
        $operatore=AA_SierOperatoreComunale::GetInstance();
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
        
        
        if(!$object) $object=new AA_Sier($_REQUEST['id']);
        if(!$object->isValid())
        {
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
            $layout->AddRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Errore nel recupero dei dati.</span></div>")));
            return $layout;
        }

        if(!$comune) $comune = $object->GetComune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
            $layout->AddRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Errore nel recupero dei dati del comune.</span></div>")));
            return $layout;
        }

        $id.="_".static::AA_UI_LAYOUT_AFFLUENZA_COMUNALE;
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $columns=array(
            array("id"=>"giornata","header"=>"<div style='text-align: center'>Giornata</div>","width"=>150, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"ore_12","header"=>"<div style='text-align: center'>Votanti ore 12</div>","fillspace"=>true, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"ore_19","header"=>"<div style='text-align: center'>Votanti ore 19</div>","fillspace"=>true, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"ore_22","header"=>"<div style='text-align: center'>Votanti ore 22</div>","fillspace"=>true, "css"=>array("text-align"=>"center"),"sort"=>"text")
            //array("id"=>"ops","header"=>"<div style='text-align: center'>Operazioni</div>","width"=>120, "css"=>array("text-align"=>"center"))
        );

        $data=array();
        $affluenza=$comune->GetAffluenza(true);
        if(sizeof($affluenza) > 0)
        {
            foreach($affluenza as $giornata=>$curAffluenza)
            {
                //AA_Log::Log(__METHOD__." - ".print_r($curAffluenza,true),100);
                {
                    $modify_op='AA_MainApp.utils.callHandler("dlg", {task:"GetSierOCAffluenzaModifyDlg",postParams: {id: '.$object->GetId().',id_comune:'.$comune->GetProp('id').', giornata: "'.strtolower($giornata).'",refresh: 1,refresh_obj_id:"'.$id.'"}},"'.$this->id.'");';
                    $trash_op='AA_MainApp.utils.callHandler("dlg", {task:"GetSierOCAffluenzaTrashDlg",postParams: {id: '.$object->GetId().',id_comune:'.$comune->GetProp('id').', giornata: "'.strtolower($giornata).'",refresh: 1,refresh_obj_id:"'.$id.'"}},"'.$this->id.'");';
                    if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_AFFLUENZA)>0) $ops="<div class='AA_DataTable_Ops'><span>&nbsp;</span><a class='AA_DataTable_Ops_Button' title='Modifica dato' onClick='".$modify_op."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina dato' onClick='".$trash_op."'><span class='mdi mdi-trash-can'></span></a><span>&nbsp;</span></div>";
                    else $ops="&nbsp;";
                }
                $data[]=array("id"=>$giornata,"ops"=>$ops, "giornata"=>$giornata,"ore_12"=>$curAffluenza['ore_12'],"ore_19"=>$curAffluenza['ore_19'],"ore_22"=>$curAffluenza['ore_22']);
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
            $table=new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Non sono presenti dati.</span></div>"));
        }

        $layout->AddRow($table);
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
            $options_documenti[]=array("id"=>"ordine","header"=>array("<div style='text-align: center'>n.</div>",array("content"=>"textFilter")),"width"=>60, "css"=>array("text-align"=>"center"),"sort"=>"int");
            $options_documenti[]=array("id"=>"aggiornamento","header"=>array("<div style='text-align: center'>Data</div>",array("content"=>"textFilter")),"width"=>100, "css"=>array("text-align"=>"left"),"sort"=>"text");
            $options_documenti[]=array("id"=>"tipoDescr","header"=>array("<div style='text-align: center'>Categorie</div>",array("content"=>"textFilter")),"width"=>300, "css"=>array("text-align"=>"center"),"sort"=>"text");
            $options_documenti[]=array("id"=>"destinatariDescr","header"=>array("<div style='text-align: center'>Destinatari</div>",array("content"=>"textFilter")),"width"=>300, "css"=>array("text-align"=>"center"),"sort"=>"text");
            $options_documenti[]=array("id"=>"estremi","header"=>array("<div style='text-align: center'>Descrizione</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text");
            $options_documenti[]=array("id"=>"ops", "header"=>"operazioni", "width"=>100,"css"=>array("text-align"=>"center"));
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

        $documenti=new AA_JSON_Template_Generic($curId."_Allegati_Table",array("view"=>"datatable", "select"=>true,"scrollX"=>false,"css"=>"AA_Header_DataTable","columns"=>$options_documenti));

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
            
            
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSierTrashAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSierModifyAllegatoDlg", params: [{id: "'.$object->GetId().'"},{id_allegato:"'.$curDoc->GetId().'"}]},"'.$this->id.'")';
            if($canModify) $ops="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='".$tip."' onClick='".$view."'><span class='mdi ".$view_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
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

        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_Coalizioni_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Coalizioni e lista</span>", "align"=>"center")));
        
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
            $addnew_btn=new AA_JSON_Template_Generic($id."_AddNewCoalizione_btn",array(
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

            $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
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
                    $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
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
                    
                    $dataview_liste_data[]=array("id"=>$id_lista,"id_coalizione"=>$curLista->GetProp('id_coalizione'),"denominazione"=>$curLista->GetProp('denominazione'),"n"=>$curLista->GetProp('ordine'),'image'=>$curImagePath,'modify'=>$modify,'trash'=>$trash,'addnew'=>$addnew);
                }

                $liste_template="<div style='display: flex; align-items: center; height: 100%; justify-content: space-between;' id_view='".$curId."_Liste"."'><div style='display: flex; align-items: center; width: 270px; padding: 5px;'>"
                . "<img src='#image#' width='50px'/><div style='height: 100%;display:flex; align-items: left; justify-content: space-evenly; flex-direction:column'><span style='margin-left: 1em; font-weight: 400;'>Lista</span><span style='margin-left: 1em; font-weight: 700;'>#denominazione#</span></div></div>"
                . "<div style='display: flex;  align-items: center; justify-content: space-between; height: 100%; padding: 5px; width: 100px'>#addnew#&nbsp;#modify#&nbsp;#trash#</div></div>";
                
                $dataview_liste=new AA_JSON_Template_Generic($curId."_Liste",array(
                    "view"=>"dataview",
                    "xCount"=>$ListeItemsForRow,
                    "module_id"=>$this->id,
                    "tabbar"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_TabBar_".$object->GetId(),
                    "type"=>array(
                        "type"=>"tiles",
                        "height"=>80,
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

        $toolbar->AddElement(new AA_JSON_Template_Generic($id."_Toolbar_Candidati_Title",array("view"=>"label","label"=>"<span style='color:#003380'>Candidati</span>", "align"=>"center")));
        
        //Pulsante di modifica
        $canModify=false;
        if(($object->GetUserCaps($this->oUser)&AA_Const::AA_PERMS_WRITE) > 0) $canModify=true;
        if($canModify)
        {            
            $modify_btn=new AA_JSON_Template_Generic($id."_AddNewCandidato_btn",array(
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
            array("id"=>"cognome","header"=>array("<div style='text-align: center'>Cognome</div>",array("content"=>"textFilter")),"width"=>150, "sort"=>"text","css"=>array("text-align"=>"left")),
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

        if(!($object instanceof AA_Sier))
        {
            if(isset($_REQUEST['id']))
            {
                $object=new AA_Sier($_REQUEST['id'],$this->oUser);
                if(!$object->IsValid())
                {
                    return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
                }
            }
            else return new AA_JSON_Template_Template($id,array("template"=>"Dati non validi"));
        }

        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean","filtered"=>true,"filter_id"=>$id));
        
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

        if(isset($params['senza_rendiconti']) && $params['senza_rendiconti'] > 0)
        {
            $filter.="<span class='AA_Label AA_Label_LightOrange'>solo comuni con rendiconti non caricati</span>&nbsp;";
        }

        if($filter=="") $filter="<span class='AA_Label AA_Label_LightOrange'>tutti</span>";
        
        $toolbar->addElement(new AA_JSON_Template_Generic($id."_FilterLabel",array("view"=>"label","align"=>"left","label"=>"<div>Visualizza: ".$filter."</div>")));
        
        //filtro
        $modify_btn=new AA_JSON_Template_Generic($id."_FilterComuni_btn",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-filter-cog",
             "label"=>"Filtra",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Opzioni di filtraggio",
             "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierComuneFilterDlg\",params: {id: ".$object->GetId()."},postParams: module.getRuntimeValue('" . $id . "','filter_data'), module: \"" . $this->id . "\"},'".$this->id."')"
         ));
         $toolbar->AddElement($modify_btn);

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
                "tooltip"=>"Aggiungi un nuovo comune",
                //"click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierAddNewCandidatoDlg\", params: [{id: ".$object->GetId()."}]},'".$this->id."')"
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierAddNewComuneDlg\", params: [{id: ".$object->GetId()."},{table_id:\"".$id."_Comuni\"}]},'".$this->id."')"
            ));
            $toolbar->AddElement($modify_btn);
        }
        
        $layout->addRow($toolbar);        
        $columns=array(
            array("id"=>"denominazione","header"=>array("<div style='text-align: center'>Comune</div>",array("content"=>"selectFilter")),"fillspace"=>true, "sort"=>"text","css"=>array("text-align"=>"left")),
            array("id"=>"circoscrizione","header"=>array("<div style='text-align: center'>Circoscrizione</div>",array("content"=>"selectFilter")),"fillspace"=>true, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"lastupdate","header"=>array("<div style='text-align: center'>Data e ora di aggiornamento</div>",array("content"=>"textFilter")),"width"=>250, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"dati_generali","header"=>array("<div style='text-align: center'>Dati Generali</div>"),"width"=>120, "css"=>array("text-align"=>"center")),
            array("id"=>"affluenza","header"=>array("<div style='text-align: center'>Affluenza</div>"),"width"=>120, "css"=>array("text-align"=>"center")),
            array("id"=>"risultati","header"=>array("<div style='text-align: center'>Risultati</div>"),"width"=>120, "css"=>array("text-align"=>"center")),
            array("id"=>"completamento","header"=>array("<div style='text-align: center'>%</div>"),"width"=>120, "css"=>array("text-align"=>"center"),"sort"=>"int"),
            array("id"=>"rendiconti","header"=>array("<div style='text-align: center'>Rendiconti</div>"),"width"=>120, "css"=>array("text-align"=>"center")),
            array("id"=>"operatori","header"=>array("<div style='text-align: center'>Operatori</div>"),"width"=>120, "css"=>array("text-align"=>"center")),
        );

        if($canModify)
        {
            $columns[]=array("id"=>"ops","header"=>"<div style='text-align: center'>Operazioni</div>","width"=>100, "css"=>array("text-align"=>"center"));
        }

        $data=array();
        $circoscrizioni=AA_Sier_Const::GetCircoscrizioni();

        $comuni=$object->GetComuni(null,$params);
        foreach($comuni as $curComune)
        {
            $data[]=$curComune->GetProps();
            $index=sizeof($data)-1;

            //AA_Log::Log(__METHOD__." - candidato: ".print_r($curCandidato,true),100);

            //Circoscrizione
            $data[$index]['circoscrizione_desc']=$circoscrizioni[$curComune->GetProp("id_circoscrizione")];

            //--------- Dati generali ---------
            $view='AA_MainApp.utils.callHandler("dlg", {task:"GetSierComuneDatiGeneraliViewDlg", params: [{id: "'.$object->GetId().'"},{id_comune:"'.$curComune->GetProp("id").'"}]},"'.$this->id.'")';
            $data[$index]['dati_generali']="<div class='AA_DataTable_Ops' style='justify-content: space-evenly'><a class='AA_DataTable_Ops_Button' title='Vedi e gestisci i dati generali e del corpo elettorale' onClick='".$view."'><span class='mdi mdi-eye'></span></a>";
            $data[$index]['dati_generali'].="</div>";
            //------------------------------

            //--------- Affluenza ---------
            $class="AA_DataTable_Ops_Button";
            $icon="mdi mdi-eye";
            $text="Vedi e gestisci i dati sull&apos;affluenza alle urne";
            if($object->GetProp("affluenza") == "") 
            {
                $class="AA_DataTable_Ops_Button";
                $icon="mdi mdi-upload";
                $text="Gestisci i dati sull&apos;affluenza alle urne";
            }
            $id_layout_op=static::AA_UI_PREFIX."_".static::AA_UI_WND_AFFLUENZA_COMUNALE."_".static::AA_UI_LAYOUT_AFFLUENZA_COMUNALE;
            $view='AA_MainApp.curModule.setRuntimeValue("'.$id_layout_op.'","filter_data",{id:'.$object->GetId().',id_comune: '.$curComune->GetProp('id').'});AA_MainApp.utils.callHandler("dlg", {task:"GetSierComuneAffluenzaViewDlg", params: [{id: "'.$object->GetId().'"},{id_comune:"'.$curComune->GetProp("id").'"}]},"'.$this->id.'")';
            $data[$index]['affluenza']="<div class='AA_DataTable_Ops' style='justify-content: space-evenly'><a class='".$class."' title='$text' onClick='".$view."'><span class='mdi $icon'></span></a>";
            $data[$index]['affluenza'].="</div>";
            //------------------------------

            //--------- risultati ---------
            $class="AA_DataTable_Ops_Button";
            $icon="mdi mdi-eye";
            $text="Vedi e gestisci i risultati delle consultazioni";
            if($object->GetProp("risultati") == "") 
            {
                $class="AA_DataTable_Ops_Button";
                $icon="mdi mdi-upload";
                $text="Gestisci i risultati delle consultazioni";
                $completamento=0;
            }
            else
            {
                $risultati=json_decode($object->GetProp("risultati"),true);
                if($risultati)
                {
                    $sezioni_scrutinate=intval($risultati['sezioni_scrutinate']);
                    if($object->GetProp("sezioni")>0) $completamento=round($sezioni_scrutinate/$object->GetProp("sezioni"));
                    else $completamento=0;
                }
                else
                {
                    AA_Log::Log(__METHOD__," - Errore nel parsing dei risultati: ".$object->GetProp("risultati"),100);
                }
            }
            $id_layout_op=static::AA_UI_PREFIX."_".static::AA_UI_WND_RISULTATI_COMUNALI."_".static::AA_UI_LAYOUT_RISULTATI_COMUNALI;
            $data[$index]['completamento']=$completamento;
            $view='AA_MainApp.curModule.setRuntimeValue("'.$id_layout_op.'","filter_data",{id:'.$object->GetId().',id_comune: '.$curComune->GetProp('id').'});AA_MainApp.utils.callHandler("dlg", {task:"GetSierComuneRisultatiViewDlg", params: [{id: "'.$object->GetId().'"},{id_comune:"'.$curComune->GetProp("id").'"}]},"'.$this->id.'")';
            $data[$index]['risultati']="<div class='AA_DataTable_Ops' style='justify-content: space-evenly'><a class='".$class."' title='$text' onClick='".$view."'><span class='mdi $icon'></span></a>";
            //if($canModify)
            //{
            //    $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSierRisultatiModifyDlg", params: [{id: "'.$object->GetId().'"},{id_comune:"'.$curComune->GetProp("id").'"}]},"'.$this->id.'")';
            //    $data[$index]['risultati'].="<a class='AA_DataTable_Ops_Button' title='Modifica i risultati delle consultazioni' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a>";
            //}
            $data[$index]['risultati'].="</div>";
            //------------------------------

            //--------- rendiconti ---------
            $class="AA_DataTable_Ops_Button";
            $icon="mdi mdi-eye";
            $text="Vedi e gestisci i rendiconti";
            if($object->GetProp("rendiconti") == "") 
            {
                $class="AA_DataTable_Ops_Button";
                $icon="mdi mdi-upload";
                $text="Gestisci i rendiconti";
            }
            $id_layout_op=static::AA_UI_PREFIX."_".static::AA_UI_WND_RENDICONTI_COMUNALI."_".static::AA_UI_LAYOUT_RENDICONTI_COMUNALI;
            $view='AA_MainApp.curModule.setRuntimeValue("'.$id_layout_op.'","filter_data",{id:'.$object->GetId().',id_comune: '.$curComune->GetProp('id').'});AA_MainApp.utils.callHandler("dlg", {task:"GetSierComuneRendicontiViewDlg", params: [{id: "'.$object->GetId().'"},{id_comune:"'.$curComune->GetProp("id").'"}]},"'.$this->id.'")';
            $data[$index]['rendiconti']="<div class='AA_DataTable_Ops' style='justify-content: space-evenly'><a class='".$class."' title='$text' onClick='".$view."'><span class='mdi $icon'></span></a>";
            $data[$index]['rendiconti'].="</div>";
            //------------------------------

            //--------- operatori ---------
            $class="AA_DataTable_Ops_Button";
            $icon="mdi mdi-eye";
            $text="Vedi e gestisci gli operatori comunali abilitati";
            if($object->GetProp("operatori") == "") 
            {
                $class="AA_DataTable_Ops_Button";
                $icon="mdi mdi-upload";
                $text="Gestisci gli operatori comunali abilitati";
            }
            $id_layout_op=static::AA_UI_PREFIX."_".static::AA_UI_WND_OPERATORI_COMUNALI."_".static::AA_UI_LAYOUT_OPERATORI_COMUNALI;
            $view='AA_MainApp.curModule.setRuntimeValue("'.$id_layout_op.'","filter_data",{id:'.$object->GetId().',id_comune: '.$curComune->GetProp('id').'});AA_MainApp.utils.callHandler("dlg", {task:"GetSierComuneOperatoriViewDlg", params: [{id: "'.$object->GetId().'"},{id_comune:"'.$curComune->GetProp("id").'"}]},"'.$this->id.'")';
            $data[$index]['operatori']="<div class='AA_DataTable_Ops' style='justify-content: space-evenly'><a class='".$class."' title='$text' onClick='".$view."'><span class='mdi $icon'></span></a>";
            $data[$index]['operatori'].="</div>";
            //------------------------------
            
            if($canModify)
            {
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSierTrashComuneDlg", params: [{id: "'.$object->GetId().'"},{id_comune:"'.$curComune->GetProp("id").'"}]},"'.$this->id.'")';
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSierModifyComuneDlg", params: [{id: "'.$object->GetId().'"},{id_comune:"'.$curComune->GetProp("id").'"}]},"'.$this->id.'")';
                //$data[$index]['ops']="<div class='AA_DataTable_Ops'><a class='AA_DataTable_Ops_Button' title='Modifica i dati generali del Comune' onClick='".$modify."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina il Comune' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a></div>";
                $data[$index]['ops']="<div class='AA_DataTable_Ops'>&nbsp;<a class='AA_DataTable_Ops_Button_Red' title='Elimina il Comune' onClick='".$trash."'><span class='mdi mdi-trash-can'></span></a>&nbsp;</div>";
            }
        }

        //AA_Log::Log(__METHOD__." - candidati: ".print_r($data,true),100);

        if(sizeof($comuni) > 0)
        {
            $table=new AA_JSON_Template_Generic($id."_Comuni", array(
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
            $layout->addRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Non sono presenti comuni.</span></div>")));
        }

        return $layout;
    }

    //Template filtro di ricerca comuni
    public function Template_GetSierComuneFilterDlg()
    {
        $session_params=AA_SessionVar::Get(static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_COMUNI_BOX);
        if($session_params->IsValid())
        {
            $formData=(array)$session_params->GetValue();
            //AA_Log::Log(__METHOD__." - session var: ".$id." - value: ".print_r($params,true),100);
            foreach($formData as $key=>$curParam)
            {
                if(isset($_REQUEST[$key])) $formData[$key]=$_REQUEST[$key];
            }
            if(isset($_REQUEST['id']) && $_REQUEST['id'] > 0) $formData['id']=$_REQUEST['id'];
        }
        else
        {
            //Valori runtime
            $formData=array("id"=>$_REQUEST['id'],"senza_operatori"=>$_REQUEST['senza_operatori'],"senza_affluenza"=>$_REQUEST['senza_affluenza'],"senza_risultati"=>$_REQUEST['senza_risultati'],"senza_rendiconti"=>$_REQUEST['senza_rendiconti']);
        }
                
        //Valori reset
        $resetData=array("senza_operatori"=>0,"senza_affluenza"=>0,"senza_risultati"=>0,"senza_rendiconti"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="AA_MainApp.curModule.refreshUiObject('".static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_COMUNI_BOX."',true)";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Comune_Filter", "Parametri di filtraggio",$this->GetId(),$formData,$resetData,$applyActions,static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_COMUNI_BOX);
        
        $dlg->SetHeight(480);
        $dlg->SetLabelWidth(250);
        
        //Senza operatori
        $dlg->AddSwitchBoxField("senza_operatori","Comuni senza operatori",array("onLabel"=>"mostra esclusivamente","offLabel"=>"mostra tutti","bottomLabel"=>"*Abilta per mostrare ESCLUSIVAMENTE i comuni senza operatori."));

        //Senza affluenza
        $dlg->AddSwitchBoxField("senza_affluenza","Comuni senza affluenza",array("onLabel"=>"mostra esclusivamente","offLabel"=>"mostra tutti","bottomLabel"=>"*Abilta per mostrare ESCLUSIVAMENTE i comuni senza affluenza."));

        //Senza risultati
        $dlg->AddSwitchBoxField("senza_risultati","Comuni senza risultati",array("onLabel"=>"mostra esclusivamente","offLabel"=>"mostra tutti","bottomLabel"=>"*Abilta per mostrare ESCLUSIVAMENTE i comuni senza risultati."));

        //Senza rendiconti
        $dlg->AddSwitchBoxField("senza_rendiconti","Comuni senza rendiconti",array("onLabel"=>"mostra esclusivamente","offLabel"=>"mostra tutti","bottomLabel"=>"*Abilta per mostrare ESCLUSIVAMENTE i comuni senza rendiconti."));

        //Enable session save
        $dlg->EnableSessionSave();

        $dlg->SetApplyButtonName("Filtra");
        $dlg->EnableApplyHotkey();

        return $dlg->GetObject();
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


    //Task login operatore comunale 
    public function Task_OCLogin($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può accedere come utente comunale",false);
            return false;
        }

        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SiER non valido.",false);
            return false;
        }

        $cf=$_REQUEST['cf'];
        if($cf == "")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Codice fiscale errato",false);
            return false;
        }

        $comune=$object->GetComune("",$cf);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non abilitato.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->ChallengeLogin($cf,$object->GetId()))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError(AA_Log::$lastErrorLog,false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetStatusAction("dlg",array("task"=>"GetOCLoginVerifyDlg","postParams"=>array("id"=>$object->GetId())),true);

        $task->SetContent("Token di autenticazione inviato alla email dell'operatore.",false);
        return true;
    }

    //Task login operatore comunale 
    public function Task_OCVerifyLogin($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può accedere come utente comunale",false);
            return false;
        }

        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->VerifyLogin($_REQUEST['codice']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError(AA_Log::$lastErrorLog,false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Autenticazione avvenuta con successo.",false);
        return true;
    }

    //Task aggiungi affluenza operatore comunale 
    public function Task_GetSierOCAffluenzaAddNewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può accedere come utente comunale",false);
            return false;
        }

        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }

        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierOCAffluenzaAddNewDlg($object,$comune),true);
        return true;
    }

    //Task modifica dati generali operatore comunale 
    public function Task_GetSierOCModifyDatiGeneraliDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può accedere come utente comunale",false);
            return false;
        }

        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }

        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_OC_DatiGeneraliModifyDlg($object,$comune),true);
        return true;
    }

    //Task login operatore comunale 
    public function Task_Update_OC_ComuneDatiGenerali($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può accedere come utente comunale",false);
            return false;
        }

        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }

        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido.",false);
            return false;
        }

        $comune->SetProp("pec",$_REQUEST['pec']);
        $comune->SetProp("contatti",$_REQUEST['contatti']);
        $comune->SetProp("indirizzo",$_REQUEST['indirizzo']);
        if(isset($_REQUEST['sezioni']) && $_REQUEST['sezioni']>0) $comune->SetProp("sezioni",intVal($_REQUEST['sezioni']));
        if(isset($_REQUEST['elettori_m']) && $_REQUEST['elettori_m']>0) $comune->SetProp("elettori_m",intVal($_REQUEST['elettori_m']));
        if(isset($_REQUEST['elettori_f']) && $_REQUEST['elettori_f']>0) $comune->SetProp("elettori_f",intVal($_REQUEST['elettori_f']));

        if(!$object->UpdateComune($comune,$this->oUser," - operatore comunale: ".$operatore->GetOperatoreComunaleCf()))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError(AA_Log::$lastErrorLog,false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati generali e corpo elettorale aggiornati con successo.",false);
        return true;
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

    //Task modifica Comune
    public function Task_GetSierComuneDatiGeneraliViewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneDatiGeneraliViewDlg($object,$comune),true);
        return true;
    }

    //Task affluenza view Comune
    public function Task_GetSierComuneAffluenzaViewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneAffluenzaViewDlg($object,$comune),true);
        return true;
    }

    //Task affluenza add new Comune
    public function Task_GetSierComuneAffluenzaAddNewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneAffluenzaAddNewDlg($object,$comune),true);
        return true;
    }

     //Task affluenza modify affluenza
     public function Task_GetSierComuneAffluenzaModifyDlg($task)
     {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        if(!isset($_REQUEST['giornata']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Giornata non valida",false);
            return false;
        }
        $giornate=$comune->GetAffluenza(true);
        if(!isset($giornate[$_REQUEST['giornata']]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Giornata non valida",false);
            return false;
        }
        $giornata = $_REQUEST['giornata'];
     
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneAffluenzaModifyDlg($object,$comune,$giornata),true);
        return true;
     }

    //Task aggiungi operatore comunale
    public function Task_GetSierComuneOperatoriAddNewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneOperatoriAddNewDlg($object,$comune),true);
        return true;
    }

    //Task modifica operatore comunale
    public function Task_GetSierComuneOperatoriModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        $operatori=$comune->GetOperatori(true);
        $operatore=$operatori[strtolower(trim($_REQUEST['cf']))];
        if(!isset($operatori[strtolower(trim($_REQUEST['cf']))]))
        {
            $operatore=$operatori[strtoupper(trim($_REQUEST['cf']))];
            if(!isset($operatori[strtoupper(trim($_REQUEST['cf']))]))
            {
                AA_Log::Log(__METHOD__." - operatore non valido: ".print_r($operatori,true)." - ".print_r($_REQUEST,true),100);
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Operatore non valido",false);
                return false;    
            }
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneOperatoriModifyDlg($object,$comune,$operatore),true);
        return true;
    }

    //Task modifica dati generali comunale
    public function Task_GetSierComuneRisultatiGeneraliModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneRisultatiGeneraliModifyDlg($object,$comune),true);
        return true;
    }

    //Task modifica risultati coalizioni comunale
    public function Task_GetSierComuneRisultatiCoalizioniModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }
        
        //controlli
        $risultati=$comune->GetRisultati(true);

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];

        if($votanti==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti votanti, caricare prima i dati generali.",false);
            return false;
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneRisultatiCoalizioniModifyDlg($object,$comune),true);
        return true;
    }

    //Task modifica risultati preferenze comunale
    public function Task_GetSierComuneRisultatiPreferenzeModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }
        
        //controlli
        $risultati=$comune->GetRisultati(true);

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];

        if($votanti==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti votanti, caricare prima i risultati generali.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneRisultatiPreferenzeModifyDlg($object,$comune),true);
        return true;
    }

    //Task trash risultati preferenze comunale
    public function Task_GetSierComuneRisultatiPreferenzeTrashDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        $candidato=$object->GetCandidato($_REQUEST['id_candidato']);
        if($candidato == null)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Candidato non valido",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneRisultatiPreferenzeTrashDlg($object,$comune,$candidato),true);
        return true;
    }

    //Task modifica risultati coalizioni comunale
    public function Task_GetSierComuneRisultatiListeModifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }
        
        //controlli
        $risultati=$comune->GetRisultati(true);

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];

        if($votanti==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti votanti, caricare prima i risultati generali.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneRisultatiListeModifyDlg($object,$comune),true);
        return true;
    }

    //Task modifica dati generali comunale (OC)
    public function Task_GetSierOCModifyRisultatiGeneraliDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }

        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido.",false);
            return false;
        }

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];
        
        if($votanti==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti votanti, caricare prima i dati generali.",false);
            return false;
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierOCModifyRisultatiGeneraliDlg($object,$comune),true);
        return true;
    }

    //Task modifica risultati coalizioni (OC)
    public function Task_GetSierOCModifyRisultatiCoalizioniDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }

        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido.",false);
            return false;
        }

        //controlli
        $risultati=$comune->GetRisultati(true);

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];

        if($votanti==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti votanti, caricare prima i dati generali.",false);
            return false;
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierOCModifyRisultatiCoalizioniDlg($object,$comune),true);
        return true;
    }

    //Task modifica risultati liste (OC)
    public function Task_GetSierOCModifyRisultatiListeDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }

        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido.",false);
            return false;
        }
        //controlli
        $risultati=$comune->GetRisultati(true);

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];

        if($votanti==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti votanti, caricare prima i dati generali.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierOCModifyRisultatiListeDlg($object,$comune),true);
        return true;
    }

    //Task modifica risultati liste (OC)
    public function Task_GetSierOCModifyRisultatiPreferenzeDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }

        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido.",false);
            return false;
        }
        //controlli
        $risultati=$comune->GetRisultati(true);

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];

        if($votanti==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti votanti, caricare prima i dati generali.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierOCModifyRisultatiPreferenzeDlg($object,$comune),true);
        return true;
    }

    //Task modifica operatore comunale
    public function Task_GetSierComuneOperatoriTrashDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        $operatori=$comune->GetOperatori(true);
        $operatore=$operatori[strtolower(trim($_REQUEST['cf']))];
        if(!isset($operatori[strtolower(trim($_REQUEST['cf']))]))
        {
            $operatore=$operatori[strtoupper(trim($_REQUEST['cf']))];
            if(!isset($operatori[strtoupper(trim($_REQUEST['cf']))]))
            {
                AA_Log::Log(__METHOD__." - operatore non valido: ".print_r($operatori,true)." - ".print_r($_REQUEST,true),100);
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Operatore non valido",false);
                return false;    
            }
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneOperatoriTrashDlg($object,$comune,$operatore),true);
        return true;
    }

    //Task operatore comunale auth token verify dlg operatore comunale
    public function Task_GetOCLoginVerifyDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        if(isset($_SESSION['oc_sier_object'])) $object=new AA_Sier($_SESSION['oc_sier_object'],$this->oUser);
        else $object= new AA_Sier($_REQUEST['id'],$this->oUser);

        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetOCVerifyLoginDlg($object),true);
        return true;
    }

    //Task operatori view
    public function Task_GetSierComuneOperatoriViewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneOperatoriViewDlg($object,$comune),true);
        return true;
    }

    //Task operatori view
    public function Task_GetSierFeedRisultatiAffluenza($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }
    
        header('Content-Type: application/json');
        die(json_encode($object->BuildRisultatiAffluenzaFeed()));

        //$task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        //$task->SetContent($this->Template_GetSierComuneOperatoriViewDlg($object,$comune),false);
        return true;
    }

    //Task risultati Comune view
    public function Task_GetSierComuneRisultatiViewDlg($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }
    
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSierComuneRisultatiViewDlg($object,$comune),true);
        return true;
    }

    //Task aggiunta operatore Comune
    public function Task_AddNewSierComuneAffluenza($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può modificare l'oggetto: ".$object,false);
            return false;
        }
    
        //Verifica
        if(!isset($_REQUEST['giornata']) || trim($_REQUEST['giornata'])=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare la giornata.",false);
            return false;
        }

        if(!isset($_REQUEST['ore_12']) || $_REQUEST['ore_12'] == "") $_REQUEST['ore_12']=0;
        if(!isset($_REQUEST['ore_19']) || $_REQUEST['ore_19'] == "") $_REQUEST['ore_19']=0;
        if(!isset($_REQUEST['ore_22']) || $_REQUEST['ore_22'] == "") $_REQUEST['ore_22']=0;

        $affluenza=$comune->GetAffluenza(true);
        if(!is_array($affluenza)) $affluenza=array();
        $affluenza[$_REQUEST['giornata']]=array("ore_12"=>strtolower(trim($_REQUEST['ore_12'])),"ore_19"=>strtolower(trim($_REQUEST['ore_19'])),"ore_22"=>strtolower(trim($_REQUEST['ore_22'])));
        $comune->SetAffluenza($affluenza);
        if(!$object->UpdateComune($comune,$this->oUser,"Aggiunta affluenza per la giornata: ".$_REQUEST['giornata']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dell'affluenza.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetStatusAction("refreshCurSection");
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task aggiornamento affluenza
    public function Task_UpdateSierComuneAffluenza($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può modificare l'oggetto: ".$object,false);
            return false;
        }
    
        //Verifica
        if(!isset($_REQUEST['giornata']) || trim($_REQUEST['giornata'])=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare la giornata.",false);
            return false;
        }

        if(!isset($_REQUEST['ore_12']) || $_REQUEST['ore_12'] == "") $_REQUEST['ore_12']=0;
        if(!isset($_REQUEST['ore_19']) || $_REQUEST['ore_19'] == "") $_REQUEST['ore_19']=0;
        if(!isset($_REQUEST['ore_22']) || $_REQUEST['ore_22'] == "") $_REQUEST['ore_22']=0;

        $affluenza=$comune->GetAffluenza(true);
        if(!is_array($affluenza)) $affluenza=array();
        $affluenza[$_REQUEST['giornata']]=array("ore_12"=>strtolower(trim($_REQUEST['ore_12'])),"ore_19"=>strtolower(trim($_REQUEST['ore_19'])),"ore_22"=>strtolower(trim($_REQUEST['ore_22'])));
        $comune->SetAffluenza($affluenza);
        if(!$object->UpdateComune($comune,$this->oUser,"Aggiornamento affluenza per la giornata: ".$_REQUEST['giornata']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dell'affluenza.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetStatusAction("refreshCurSection");
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task aggiornamento risultati generali
    public function Task_UpdateSierComuneRisultatiGenerali($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può modificare l'oggetto: ".$object,false);
            return false;
        }

        //controlli
        if(!isset($_REQUEST['sezioni_scrutinate']) || $_REQUEST['sezioni_scrutinate']==0 || $_REQUEST['sezioni_scrutinate'] =="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Le sezioni scrutinate devono essere un numero maggiore di zero.",false);
            return false;
        }

        if(isset($_REQUEST['sezioni_scrutinate']) && $_REQUEST['sezioni_scrutinate']>$comune->GetProp('sezioni'))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Le sezioni scrutinate non possono superare il numero di sezioni del comune.",false);
            return false;
        }
        
        $elettori=intVal($comune->GetProp('elettori_m'))+intVal($comune->GetProp('elettori_f'));

        $votanti=0;
        if(isset($_REQUEST['votanti_m']) && $_REQUEST['votanti_m']>0) $votanti+=$_REQUEST['votanti_m'];        
        if(isset($_REQUEST['votanti_f']) && $_REQUEST['votanti_f']>0) $votanti+=$_REQUEST['votanti_f'];
        
        if($votanti>$elettori)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("il numero di votanti non possono superare il numero di elettori.",false);
            return false;
        }

        if(isset($_REQUEST['votanti_m']) && $_REQUEST['votanti_m']>$comune->GetProp('elettori_m'))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("il numero di votanti maschi non possono superare il numero di elettori maschi.",false);
            return false;
        }

        if(isset($_REQUEST['votanti_f']) && $_REQUEST['votanti_f']>$comune->GetProp('elettori_f'))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("il numero di votanti femmine non possono superare il numero di elettori femmine.",false);
            return false;
        }

        $voti_non_validi=0;
        if(isset($_REQUEST['schede_bianche']) && $_REQUEST['schede_bianche']>0) $voti_non_validi+=$_REQUEST['schede_bianche'];
        if(isset($_REQUEST['schede_nulle']) && $_REQUEST['schede_nulle']>0) $voti_non_validi+=$_REQUEST['schede_nulle'];
        if(isset($_REQUEST['voti_contestati_na']) && $_REQUEST['voti_contestati_na']>0) $voti_non_validi+=$_REQUEST['voti_contestati_na'];
        if(isset($_REQUEST['schede_voti_nulli']) && $_REQUEST['schede_voti_nulli']>0) $voti_non_validi+=$_REQUEST['schede_voti_nulli'];
    
        if($voti_non_validi>$votanti)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Il numero totale dei votanti non può essere minore del numero dei voti non validi (schede bianche+schede nulle+voti contestati+voti nulli).",false);
            return false;
        }

        $risultati=$comune->GetRisultati(true);
        if(!is_array($risultati)) $risultati=array();
        if(isset($_REQUEST['sezioni_scrutinate']) && $_REQUEST['sezioni_scrutinate']>=0) $risultati['sezioni_scrutinate']=$_REQUEST['sezioni_scrutinate'];
        if(isset($_REQUEST['votanti_m']) && $_REQUEST['votanti_m']>=0) $risultati['votanti_m']=$_REQUEST['votanti_m'];
        if(isset($_REQUEST['votanti_f']) && $_REQUEST['votanti_f']>=0) $risultati['votanti_f']=$_REQUEST['votanti_f'];
        if(isset($_REQUEST['schede_bianche']) && $_REQUEST['schede_bianche']>=0) $risultati['schede_bianche']=$_REQUEST['schede_bianche'];
        if(isset($_REQUEST['schede_nulle']) && $_REQUEST['schede_nulle']>=0) $risultati['schede_nulle']=$_REQUEST['schede_nulle'];
        if(isset($_REQUEST['voti_contestati_na']) && $_REQUEST['voti_contestati_na']>=0) $risultati['voti_contestati_na']=$_REQUEST['voti_contestati_na'];
        if(isset($_REQUEST['schede_voti_nulli']) && $_REQUEST['schede_voti_nulli']>=0) $risultati['schede_voti_nulli']=$_REQUEST['schede_voti_nulli'];
        $comune->SetRisultati($risultati);
        if(!$object->UpdateComune($comune,$this->oUser,"Aggiornamento risultati generali"))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dei risultati generali.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task aggiornamento risultati coalizioni
    public function Task_UpdateSierComuneRisultatiCoalizioni($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può modificare l'oggetto: ".$object,false);
            return false;
        }

        //controlli
        $risultati=$comune->GetRisultati(true);

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];

        $voti_non_validi=0;
        if(isset($risultati['schede_bianche']) && $risultati['schede_bianche']>0) $voti_non_validi+=$risultati['schede_bianche'];
        if(isset($risultati['schede_nulle']) && $risultati['schede_nulle']>0) $voti_non_validi+=$risultati['schede_nulle'];
        if(isset($risultati['voti_contestati_na']) && $risultati['voti_contestati_na']>0) $voti_non_validi+=$risultati['voti_contestati_na'];
        if(isset($risultati['schede_voti_nulli']) && $risultati['schede_voti_nulli']>0) $voti_non_validi+=$risultati['schede_voti_nulli'];

        AA_Log::Log(__METHOD__." - votanti: ".$votanti." - voti non validi: ".$voti_non_validi,100);

        $coalizioni=$object->GetCoalizioni();
        if(sizeof($coalizioni)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti coalizioni.",false);
            return false;
        }

        $voti_presidente=array();
        $voti_totali=0;
        foreach($coalizioni as $idCoalizione=>$curCoalizione)
        {
            $voti_presidente[$idCoalizione]=0;
            if(isset($_REQUEST[$idCoalizione]) && $_REQUEST[$idCoalizione]>0)
            {
                $voti_presidente[$idCoalizione]=$_REQUEST[$idCoalizione];
                $voti_totali+=$_REQUEST[$idCoalizione];
            }
        }

        if($voti_totali>($votanti-$voti_non_validi))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Il totale dei voti coalizione non possono essere maggiori del numero di voti validi (num. votanti - voti non validi).",false);
            return false;
        }
        
        $risultati['voti_presidente']=$voti_presidente;

        $comune->SetRisultati($risultati);
        if(!$object->UpdateComune($comune,$this->oUser,"Aggiornamento risultati voti presidente"))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dei risultati coalizioni.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task aggiornamento risultati coalizioni
    public function Task_UpdateSierComuneRisultatiListe($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può modificare l'oggetto: ".$object,false);
            return false;
        }

        //controlli
        $risultati=$comune->GetRisultati(true);

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];

        $voti_non_validi=0;
        if(isset($risultati['schede_bianche']) && $risultati['schede_bianche']>0) $voti_non_validi+=$risultati['schede_bianche'];
        if(isset($risultati['schede_nulle']) && $risultati['schede_nulle']>0) $voti_non_validi+=$risultati['schede_nulle'];
        if(isset($risultati['voti_contestati_na']) && $risultati['voti_contestati_na']>0) $voti_non_validi+=$risultati['voti_contestati_na'];
        if(isset($risultati['schede_voti_nulli']) && $risultati['schede_voti_nulli']>0) $voti_non_validi+=$risultati['schede_voti_nulli'];

        $liste=$object->GetListe();
        if(sizeof($liste)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti liste.",false);
            return false;
        }

        $voti_liste=array();
        $voti_totali=0;
        foreach($liste as $idLista=>$curCoalizione)
        {
            $voti_liste[$idLista]=0;
            if(isset($_REQUEST["lista_".$idLista]) && $_REQUEST["lista_".$idLista]>0)
            {
                $voti_liste[$idLista]=$_REQUEST["lista_".$idLista];
                $voti_totali+=$_REQUEST["lista_".$idLista];
            }
        }

        if($voti_totali>($votanti+$voti_non_validi))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Il totale dei voti di lista non possono essere maggiori del numero di voti validi (num. votanti - voti non validi).",false);
            return false;
        }
        
        $risultati['voti_lista']=$voti_liste;

        $comune->SetRisultati($risultati);
        if(!$object->UpdateComune($comune,$this->oUser,"Aggiornamento risultati voti lista"))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dei risultati lista.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task aggiornamento risultati preferenze
    public function Task_UpdateSierComuneRisultatiPreferenze($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può modificare l'oggetto: ".$object,false);
            return false;
        }

        //controlli
        $risultati=$comune->GetRisultati(true);

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];

        $voti_non_validi=0;
        if(isset($risultati['schede_bianche']) && $risultati['schede_bianche']>0) $voti_non_validi+=$risultati['schede_bianche'];
        if(isset($risultati['schede_nulle']) && $risultati['schede_nulle']>0) $voti_non_validi+=$risultati['schede_nulle'];
        if(isset($risultati['voti_contestati_na']) && $risultati['voti_contestati_na']>0) $voti_non_validi+=$risultati['voti_contestati_na'];
        if(isset($risultati['schede_voti_nulli']) && $risultati['schede_voti_nulli']>0) $voti_non_validi+=$risultati['schede_voti_nulli'];

        if($votanti==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti votanti.",false);
            return false;
        }

        $candidati=$object->GetCandidati(null,null,$comune->GetProp("id_circoscrizione"));
        if(sizeof($candidati)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti candidati.",false);
            return false;
        }

        $voti_candidato=$risultati['voti_candidato'];
        if(!is_array($voti_candidato)) $voti_candidato=array();


        if(isset($candidati[$_REQUEST['id_candidato']]) && $_REQUEST['voti']>0)
        {
            $candidato=$candidati[$_REQUEST['id_candidato']]->GetProps();
            $candidato['voti']=$_REQUEST['voti'];
            $voti_candidato[$_REQUEST['id_candidato']]=$candidato;
        }

        $risultati['voti_candidato']=$voti_candidato;

        $comune->SetRisultati($risultati);
        if(!$object->UpdateComune($comune,$this->oUser,"Aggiornamento risultati voti candidato."))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dei voti candidato.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task aggiornamento risultati preferenze
    public function Task_TrashSierComuneRisultatiPreferenze($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può modificare l'oggetto: ".$object,false);
            return false;
        }

        //controlli
        $risultati=$comune->GetRisultati(true);

        $voti_candidato=$risultati['voti_candidato'];
        if(is_array($voti_candidato)) $voti_candidato=array();


        if(isset($candidati[$_REQUEST['id_candidato']]) && isset($voti_candidato[$_REQUEST['id_candidato']]))
        {
            unset($voti_candidato[$_REQUEST['id_candidato']]);
        }

        $risultati['voti_candidato']=$voti_candidato;

        $comune->SetRisultati($risultati);
        if(!$object->UpdateComune($comune,$this->oUser,"Rimozione voti candidato."))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dei voti candidato.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task aggiornamento risultati coalizioni
    public function Task_Update_OC_ComuneRisultatiListe($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può accedere come utente comunale",false);
            return false;
        }

        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }

        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido.",false);
            return false;
        }

        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Modifica risultati non abilitata.",false);
            return false;
        }

        //controlli
        $risultati=$comune->GetRisultati(true);

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];

        $voti_non_validi=0;
        if(isset($risultati['schede_bianche']) && $risultati['schede_bianche']>0) $voti_non_validi+=$risultati['schede_bianche'];
        if(isset($risultati['schede_nulle']) && $risultati['schede_nulle']>0) $voti_non_validi+=$risultati['schede_nulle'];
        if(isset($risultati['voti_contestati_na']) && $risultati['voti_contestati_na']>0) $voti_non_validi+=$risultati['voti_contestati_na'];
        if(isset($risultati['schede_voti_nulli']) && $risultati['schede_voti_nulli']>0) $voti_non_validi+=$risultati['schede_voti_nulli'];

        $liste=$object->GetListe(null,$comune->GetProp("id_circoscrizione"));
        if(sizeof($liste)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti liste.",false);
            return false;
        }

        $voti_liste=array();
        $voti_totali=0;
        foreach($liste as $idLista=>$curCoalizione)
        {
            $voti_liste[$idLista]=0;
            if(isset($_REQUEST["lista_".$idLista]) && $_REQUEST["lista_".$idLista]>0)
            {
                $voti_liste[$idLista]=$_REQUEST["lista_".$idLista];
                $voti_totali+=$_REQUEST["lista_".$idLista];
            }
        }

        if($voti_totali>($votanti+$voti_non_validi))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Il totale dei voti di lista non possono essere maggiori del numero di voti validi (num. votanti - voti non validi).",false);
            return false;
        }
        
        $risultati['voti_lista']=$voti_liste;

        $comune->SetRisultati($risultati);
        if(!$object->UpdateComune($comune,$this->oUser,"Aggiornamento risultati voti lista - operatore: ".$operatore->GetOperatoreComunaleCf()))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dei risultati lista.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task aggiornamento risultati coalizioni
    public function Task_Update_OC_ComuneRisultatiPreferenze($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può accedere come utente comunale",false);
            return false;
        }

        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }

        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido.",false);
            return false;
        }

        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Modifica risultati non abilitata.",false);
            return false;
        }

        //controlli
        $risultati=$comune->GetRisultati(true);

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];

        $voti_non_validi=0;
        if(isset($risultati['schede_bianche']) && $risultati['schede_bianche']>0) $voti_non_validi+=$risultati['schede_bianche'];
        if(isset($risultati['schede_nulle']) && $risultati['schede_nulle']>0) $voti_non_validi+=$risultati['schede_nulle'];
        if(isset($risultati['voti_contestati_na']) && $risultati['voti_contestati_na']>0) $voti_non_validi+=$risultati['voti_contestati_na'];
        if(isset($risultati['schede_voti_nulli']) && $risultati['schede_voti_nulli']>0) $voti_non_validi+=$risultati['schede_voti_nulli'];

        if($votanti==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti votanti.",false);
            return false;
        }

        $candidati=$object->GetCandidati(null,null,$comune->GetProp("id_circoscrizione"));
        if(sizeof($candidati)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti candidati.",false);
            return false;
        }

        $voti_candidato=$risultati['voti_candidato'];
        if(!is_array($voti_candidato)) $voti_candidato=array();


        if(isset($candidati[$_REQUEST['id_candidato']]) && $_REQUEST['voti']>0)
        {
            $candidato=$candidati[$_REQUEST['id_candidato']]->GetProps();
            $candidato['voti']=$_REQUEST['voti'];
            $voti_candidato[$_REQUEST['id_candidato']]=$candidato;
        }

        $risultati['voti_candidato']=$voti_candidato;

        $comune->SetRisultati($risultati);
        if(!$object->UpdateComune($comune,$this->oUser,"Aggiornamento risultati voti preferenze - operatore: ".$operatore->GetOperatoreComunaleCf()))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento delle preferenze.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task aggiornamento risultati generali
    public function Task_Update_OC_ComuneRisultatiGenerali($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può accedere come utente comunale",false);
            return false;
        }

        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }

        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido.",false);
            return false;
        }

        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Modifica risultati non abilitata.",false);
            return false;
        }

        //controlli
        if(!isset($_REQUEST['sezioni_scrutinate']) || $_REQUEST['sezioni_scrutinate']==0 || $_REQUEST['sezioni_scrutinate'] =="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Le sezioni scrutinate devono essere un numero maggiore di zero.",false);
            return false;
        }

        if(isset($_REQUEST['sezioni_scrutinate']) && $_REQUEST['sezioni_scrutinate']>$comune->GetProp('sezioni'))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Le sezioni scrutinate non possono superare il numero di sezioni del comune.",false);
            return false;
        }
        
        $elettori=intVal($comune->GetProp('elettori_m'))+intVal($comune->GetProp('elettori_f'));

        $votanti=0;
        if(isset($_REQUEST['votanti_m']) && $_REQUEST['votanti_m']>0) $votanti+=$_REQUEST['votanti_m'];        
        if(isset($_REQUEST['votanti_f']) && $_REQUEST['votanti_f']>0) $votanti+=$_REQUEST['votanti_f'];
        
        if($votanti>$elettori)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("il numero di votanti non possono superare il numero di elettori.",false);
            return false;
        }

        if(isset($_REQUEST['votanti_m']) && $_REQUEST['votanti_m']>$comune->GetProp('elettori_m'))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("il numero di votanti maschi non possono superare il numero di elettori maschi.",false);
            return false;
        }

        if(isset($_REQUEST['votanti_f']) && $_REQUEST['votanti_f']>$comune->GetProp('elettori_f'))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("il numero di votanti femmine non possono superare il numero di elettori femmine.",false);
            return false;
        }

        $voti_non_validi=0;
        if(isset($_REQUEST['schede_bianche']) && $_REQUEST['schede_bianche']>0) $voti_non_validi+=$_REQUEST['schede_bianche'];
        if(isset($_REQUEST['schede_nulle']) && $_REQUEST['schede_nulle']>0) $voti_non_validi+=$_REQUEST['schede_nulle'];
        if(isset($_REQUEST['voti_contestati_na']) && $_REQUEST['voti_contestati_na']>0) $voti_non_validi+=$_REQUEST['voti_contestati_na'];
        if(isset($_REQUEST['schede_voti_nulli']) && $_REQUEST['schede_voti_nulli']>0) $voti_non_validi+=$_REQUEST['schede_voti_nulli'];
    
        if($voti_non_validi>$votanti)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Il numero totale dei votanti non può essere minore del numero dei voti non validi (schede bianche+schede nulle+voti contestati+voti nulli).",false);
            return false;
        }

        $risultati=$comune->GetRisultati(true);
        if(!is_array($risultati)) $risultati=array();
        if(isset($_REQUEST['sezioni_scrutinate']) && $_REQUEST['sezioni_scrutinate']>=0) $risultati['sezioni_scrutinate']=intVal($_REQUEST['sezioni_scrutinate']);
        if(isset($_REQUEST['votanti_m']) && $_REQUEST['votanti_m']>=0) $risultati['votanti_m']=intVal($_REQUEST['votanti_m']);
        if(isset($_REQUEST['votanti_f']) && $_REQUEST['votanti_f']>=0) $risultati['votanti_f']=intVal($_REQUEST['votanti_f']);
        if(isset($_REQUEST['schede_bianche']) && $_REQUEST['schede_bianche']>=0) $risultati['schede_bianche']=intVal($_REQUEST['schede_bianche']);
        if(isset($_REQUEST['schede_nulle']) && $_REQUEST['schede_nulle']>=0) $risultati['schede_nulle']=intVal($_REQUEST['schede_nulle']);
        if(isset($_REQUEST['voti_contestati_na']) && $_REQUEST['voti_contestati_na']>=0) $risultati['voti_contestati_na']=intVal($_REQUEST['voti_contestati_na']);
        if(isset($_REQUEST['schede_voti_nulli']) && $_REQUEST['schede_voti_nulli']>=0) $risultati['schede_voti_nulli']=intVal($_REQUEST['schede_voti_nulli']);
        $comune->SetRisultati($risultati);
        if(!$object->UpdateComune($comune,$this->oUser,"Aggiornamento risultati generali - operatore comunale: ".$operatore->GetOperatoreComunaleCf()))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dei risultati generali.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task modifica operatore Comune
    public function Task_Update_OC_ComuneAffluenza($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può accedere come utente comunale",false);
            return false;
        }

        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }

        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido.",false);
            return false;
        }

        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_AFFLUENZA)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Modifica affluenza non abilitata.",false);
            return false;
        }

        //Verifica
        if(!isset($_REQUEST['giornata']) || trim($_REQUEST['giornata'])=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare la giornata.",false);
            return false;
        }

        if(!isset($_REQUEST['ore_12']) || $_REQUEST['ore_12'] == "") $_REQUEST['ore_12']=0;
        if(!isset($_REQUEST['ore_19']) || $_REQUEST['ore_19'] == "") $_REQUEST['ore_19']=0;
        if(!isset($_REQUEST['ore_22']) || $_REQUEST['ore_22'] == "") $_REQUEST['ore_22']=0;

        $operatore=AA_SierOperatoreComunale::GetInstance();

        $affluenza=$comune->GetAffluenza(true);
        if(!is_array($affluenza)) $affluenza=array();
        $affluenza[$_REQUEST['giornata']]=array("ore_12"=>strtolower(trim($_REQUEST['ore_12'])),"ore_19"=>strtolower(trim($_REQUEST['ore_19'])),"ore_22"=>strtolower(trim($_REQUEST['ore_22'])));
        $comune->SetAffluenza($affluenza);
        if(!$object->UpdateComune($comune,$this->oUser,"Aggiornamento affluenza per la giornata: ".$_REQUEST['giornata']." - operatore comunale: ".$operatore->GetOperatoreComunaleCf()))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dell'affluenza.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        //$task->SetStatusAction("refreshCurSection");
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task aggiornamento risultati coalizioni (OC)
    public function Task_Update_OC_ComuneRisultatiCoalizioni($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sier_Const::AA_USER_FLAG_SIER_OC))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può accedere come utente comunale",false);
            return false;
        }

        $object=new AA_Sier($_SESSION['oc_sier_object']);
        if(!$object->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Oggetto SIER non valido.",false);
            return false;
        }

        $operatore=AA_SierOperatoreComunale::GetInstance();
        if(!$operatore->IsValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;
        }

        $comune=$object->GetComune($operatore->GetOperatoreComunaleComune());
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido.",false);
            return false;
        }

        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Modifica risultati non abilitata.",false);
            return false;
        }

        //controlli
        $risultati=$comune->GetRisultati(true);

        $votanti=0;
        if(isset($risultati['votanti_m'])) $votanti+=$risultati['votanti_m'];
        if(isset($risultati['votanti_f'])) $votanti+=$risultati['votanti_f'];

        $voti_non_validi=0;
        if(isset($risultati['schede_bianche']) && $risultati['schede_bianche']>0) $voti_non_validi+=$risultati['schede_bianche'];
        if(isset($risultati['schede_nulle']) && $risultati['schede_nulle']>0) $voti_non_validi+=$risultati['schede_nulle'];
        if(isset($risultati['voti_contestati_na']) && $risultati['voti_contestati_na']>0) $voti_non_validi+=$risultati['voti_contestati_na'];
        if(isset($risultati['schede_voti_nulli']) && $risultati['schede_voti_nulli']>0) $voti_non_validi+=$risultati['schede_voti_nulli'];

        $coalizioni=$object->GetCoalizioni();
        if(sizeof($coalizioni)==0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non sono presenti candidati Presidente.",false);
            return false;
        }

        $voti_presidente=array();
        $voti_totali=0;
        foreach($coalizioni as $idCoalizione=>$curCoalizione)
        {
            $voti_presidente[$idCoalizione]=0;
            if(isset($_REQUEST[$idCoalizione]) && $_REQUEST[$idCoalizione]>0)
            {
                $voti_presidente[$idCoalizione]=intVal($_REQUEST[$idCoalizione]);
                $voti_totali+=$_REQUEST[$idCoalizione];
            }
        }

        if($voti_totali>($votanti-$voti_non_validi))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Il totale dei voti candidati Presidente non possono essere maggiori del numero di voti validi (num. votanti - voti non validi).",false);
            return false;
        }
        
        $risultati['voti_presidente']=$voti_presidente;

        $comune->SetRisultati($risultati);
        if(!$object->UpdateComune($comune,$this->oUser,"Aggiornamento voti Presidente - operatore comunale: ".$operatore->GetOperatoreComunaleCf()))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dei voti candidati Presidente.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task aggiunta operatore Comune
    public function Task_AddNewSierComuneOperatore($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può modificare l'oggetto: ".$object,false);
            return false;
        }
    
        //Verifica
        if(!isset($_REQUEST['nome']) || trim($_REQUEST['nome'])=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare il nome dell'operatore.",false);
            return false;
        }

        if(!isset($_REQUEST['cognome']) || trim($_REQUEST['cognome'])=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare il cognome dell'operatore.",false);
            return false;
        }

        if(!isset($_REQUEST['cf']) || trim($_REQUEST['cf'])=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare il cf dell'operatore.",false);
            return false;
        }

        if(!isset($_REQUEST['email']) || trim($_REQUEST['email'])=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare l'email dell'operatore.",false);
            return false;
        }

        $operatori=$comune->GetOperatori(true);
        if(!is_array($operatori)) $operatori=array();
        $operatori[$_REQUEST['cf']]=array("cf"=>strtolower(trim($_REQUEST['cf'])),"email"=>$_REQUEST['email'],"nome"=>$_REQUEST['nome'],"cognome"=>$_REQUEST['cognome'],"lastlogin"=>"","ruolo"=>$_REQUEST['ruolo']);
        $comune->SetOperatori($operatori);
        if(!$object->UpdateComune($comune,$this->oUser,"Aggiunta operatore: ".$_REQUEST['cf']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento degli operatori.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetStatusAction("refreshCurSection");
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task modifica operatore Comune
    public function Task_UpdateSierComuneOperatore($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può modificare l'oggetto: ".$object,false);
            return false;
        }
    
        //Verifica
        if(!isset($_REQUEST['nome']) || trim($_REQUEST['nome'])=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare il nome dell'operatore.",false);
            return false;
        }

        if(!isset($_REQUEST['cognome']) || trim($_REQUEST['cognome'])=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare il cognome dell'operatore.",false);
            return false;
        }

        if(!isset($_REQUEST['cf']) || trim($_REQUEST['cf'])=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare il cf dell'operatore.",false);
            return false;
        }

        if(!isset($_REQUEST['email']) || trim($_REQUEST['email'])=="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Occorre indicare l'email dell'operatore.",false);
            return false;
        }

        $operatori=$comune->GetOperatori(true);
        if(!is_array($operatori)) $operatori=array();
        if(isset($operatori[strtoupper(trim($_REQUEST['cf']))])) unset($operatori[strtoupper(trim($_REQUEST['cf']))]);
        $operatori[strtolower(trim($_REQUEST['cf']))]=array("cf"=>strtolower(trim($_REQUEST['cf'])),"email"=>$_REQUEST['email'],"nome"=>$_REQUEST['nome'],"cognome"=>$_REQUEST['cognome'],"lastlogin"=>$_REQUEST['lastlogin'],"ruolo"=>$_REQUEST['ruolo']);
        
        $comune->SetOperatori($operatori);
        if(!$object->UpdateComune($comune,$this->oUser,"Modifica operatore: ".$_REQUEST['cf']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento degli operatori.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task modifica operatore Comune
    public function Task_TrashSierComuneOperatore($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->Getcomune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non può modificare l'oggetto: ".$object,false);
            return false;
        }
    
        //Verifica
        $operatori=$comune->GetOperatori(true);
        if(!isset($operatori[strtolower(trim($_REQUEST['cf']))]) && !isset($operatori[strtoupper(trim($_REQUEST['cf']))]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Operatore non valido.",false);
            return false;    
        }

        if(isset($operatori[strtolower(trim($_REQUEST['cf']))])) unset($operatori[strtolower(trim($_REQUEST['cf']))]);
        if(isset($operatori[strtoupper(trim($_REQUEST['cf']))])) unset($operatori[strtoupper(trim($_REQUEST['cf']))]);

        $comune->SetOperatori($operatori);
        if(!$object->UpdateComune($comune,$this->oUser,"Elimina operatore: ".$_REQUEST['cf']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento degli operatori.",false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati aggiornati con successo.",false);
        return true;
    }

    //Task modifica dati generali Comune
    public function Task_UpdateSierComuneDatiGenerali($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        $object= new AA_Sier($_REQUEST['id'],$this->oUser);
        
        if(!$object->isValid())
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Elemento non valido o permessi insufficienti.",false);
            return false;
        }

        $comune = $object->GetComune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Comune non valido",false);
            return false;
        }

        if(isset($_REQUEST['id_circoscrizione']) && $_REQUEST['id_circoscrizione']>0) $comune->SetProp("id_circoscrizione",$_REQUEST['id_circoscrizione']);
        if(isset($_REQUEST['denominazione']) && $_REQUEST['denominazione'] !="") $comune->SetProp("denominazione",$_REQUEST['denominazione']);
        if(isset($_REQUEST['pec']) && $_REQUEST['pec'] !="") $comune->SetProp("pec",$_REQUEST['pec']);
        if(isset($_REQUEST['indirizzo']) && $_REQUEST['indirizzo'] !="") $comune->SetProp("indirizzo",$_REQUEST['indirizzo']);
        if(isset($_REQUEST['contatti']) && $_REQUEST['contatti'] !="") $comune->SetProp("contatti",$_REQUEST['contatti']);
        if(isset($_REQUEST['sezioni']) && $_REQUEST['sezioni'] > 0) $comune->SetProp("sezioni",$_REQUEST['sezioni']);
        if(isset($_REQUEST['elettori_m']) && $_REQUEST['elettori_m'] > 0) $comune->SetProp("elettori_m",$_REQUEST['elettori_m']);
        if(isset($_REQUEST['elettori_f']) && $_REQUEST['elettori_f'] > 0) $comune->SetProp("elettori_f",$_REQUEST['elettori_f']);

        if(!$object->UpdateComune($comune,$this->oUser,"Modifica dati generali"))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError(AA_Log::$lastErrorLog,false);
            return false;            
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Dati generali aggiornati con successo.",false);

        return true;
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
        
        //destinatari
        $destinatari=AA_Sier_Const::GetDestinatari();
        $newDestinatari=array();
        foreach($destinatari as $destinatario=>$descr)
        {
            if(isset($_REQUEST['destinatari_'.$destinatario]) && $_REQUEST['destinatari_'.$destinatario]==1) $newDestinatari[]=$destinatario;
        }
        //----

        //tipologia
        $tipi=AA_Sier_Const::GetTipoAllegati();
        $newTipo=array();
        foreach($tipi as $tipo=>$descr)
        {
            if(isset($_REQUEST['tipo_'.$tipo]) && $_REQUEST['tipo_'.$tipo]==1) $newTipo[]=$tipo;
        }
        //--------------

        $ordine=0;
        if(isset($_REQUEST['ordine']) && $_REQUEST['ordine']>0) $ordine=$_REQUEST['ordine'];
        $allegato=new AA_SierAllegati($_REQUEST['id_allegato'],$allegato->GetIdSier(),$_REQUEST['estremi'],$_REQUEST['url'],$fileHash,implode(",",$newTipo),$aggiornamento,implode(",",$newDestinatari),addslashes($ordine));
        
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

     //Task filter comune dlg
     public function Task_GetSierComuneFilterDlg($task)
     {
         AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
         
         $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
         $task->SetContent($this->Template_GetSierComuneFilterDlg($_REQUEST),true);
         
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

    //Template dlg modify user
    public function Template_GetSierComuneDatiGeneraliViewDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierComuneDatiGeneraliViewDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Dati generali e corpo elettorale", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Dati generali e corpo elettorale", $this->id);

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)>0)
        {
            $form_data['id']=$object->GetId();
            $form_data['id_sier']=$object->GetId();
            $form_data['id_comune']=$comune->GetProp('id');
            $form_data['id_circoscrizione']=$comune->GetProp('id_circoscrizione');
            $form_data['denominazione']=$comune->GetProp('denominazione');
            $form_data['pec']=$comune->GetProp('pec');
            $form_data['contatti']=$comune->GetProp('contatti');
            $form_data['indirizzo']=$comune->GetProp('indirizzo');
            $form_data['sezioni']=$comune->GetProp('sezioni');
            $form_data['elettori_m']=$comune->GetProp('elettori_m');
            $form_data['elettori_f']=$comune->GetProp('elettori_f');
    
            $wnd=new AA_GenericFormDlg($id, "Dati generali e corpo elettorale", $this->id,$form_data,$form_data);
            
            $wnd->SetLabelAlign("right");
            $wnd->SetLabelWidth(120);
            $wnd->EnableValidation();
            
            $wnd->SetWidth(800);
            $wnd->SetHeight(600);
            
            //circoscrizione
            $circoscrizioni=AA_Sier_Const::GetCircoscrizioni();
            foreach($circoscrizioni as $key=>$value)
            {
                $options[]=array("id"=>$key,"value"=>$value);
            }
            $wnd->AddSelectField("id_circoscrizione","Circoscrizione",array("gravity"=>1,"required"=>true, "validateFunction"=>"IsSelected","bottomLabel"=>"*Seleziona la circoscrizione di cui fa parte il comune.","options"=>$options),false);
    
            //denominazione
            $wnd->AddTextField("denominazione","Denominazione",array("required"=>true,"gravity"=>1, "bottomLabel"=>"*Denominazione del comune"));
    
            //pec
            $wnd->AddTextField("pec","Pec",array("required"=>true,"gravity"=>1,"validateFunction"=>"IsMail", "bottomLabel"=>"*Indirizzo di posta certificata."));
    
            //indirizzo
            $wnd->AddTextField("indirizzo","Indirizzo",array("required"=>true,"gravity"=>1, "bottomLabel"=>"*Via e numero civico."));
    
            //contatti
            $wnd->AddTextareaField("contatti","Note e contatti",array("gravity"=>1, "bottomLabel"=>"*Eventuali informazioni di recapito utili."));
    
            //Dati corpo elettorale
            $section=new AA_FieldSet($id."_Section_DatiCorpoElettorale","Corpo elettorale");
            $section->AddTextField("sezioni", "Sezioni", array("required"=>true,"bottomPadding"=>32,"validateFunction"=>"IsInteger","bottomLabel"=>"*Numero di sezioni"));
            $section->AddTextField("elettori_m", "Maschi", array("required"=>true,"bottomPadding"=>32,"validateFunction"=>"IsInteger","bottomLabel"=>"*Numero elettori."),false);
            $section->AddTextField("elettori_f", "Femmine", array("required"=>true,"bottomPadding"=>32,"validateFunction"=>"IsInteger","bottomLabel"=>"*Numero elettrici."),false);
            $wnd->AddGenericObject($section);
                    
            $wnd->EnableCloseWndOnSuccessfulSave();
            $wnd->enableRefreshOnSuccessfulSave();
            $wnd->SetSaveTask("UpdateSierComuneDatiGenerali");
            
            return $wnd;    
        }
        else
        {
            //to do view only
            $wnd = new AA_GenericWindowTemplate($id, "Dati generali e corpo elettorale", $this->id);

            return $wnd;
        }
    }

    //Template dlg add new affluenza comune
    public function Template_GetSierOCAffluenzaAddNewDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierOCAffluenzaAddNewDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Nuovo dato affluenza", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Nuovo dato affluenza", $this->id);

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)>0)
        {
            $form_data['id']=$object->GetId();
            $form_data['id_sier']=$object->GetId();
            $form_data['id_comune']=$comune->GetProp('id');
            $form_data['ore_12']=0;
            $form_data['ore_19']=0;
            $form_data['ore_22']=0;

            $affluenza=$comune->GetAffluenza(true);
            if(sizeof($affluenza)>0)
            {
                foreach($affluenza as $giornata=>$values)
                {
                    $form_data['giornata']=$giornata;
                    $form_data['ore_12']=$values['ore_12'];
                    $form_data['ore_19']=$values['ore_19'];
                    $form_data['ore_22']=$values['ore_22'];
                }
            }
            $wnd=new AA_GenericFormDlg($id, "Nuovo dato affluenza", $this->id,$form_data,$form_data);
            
            $wnd->SetLabelAlign("right");
            $wnd->SetLabelWidth(120);
            $wnd->SetBottomPadding(32);
            $wnd->EnableValidation();
            
            $wnd->SetWidth(450);
            $wnd->SetHeight(450);
            
            $giornate=$object->GetGiornate();
            if(sizeof($giornate)>0)
            {
                //giornate
                $data=array();
                foreach($giornate as $curData=>$curGiornata)
                {
                    if($curGiornata["affluenza"]>0)
                    {
                        $data[]=array("id"=>$curData,"value"=>$curData);
                    }
                }
                if(sizeof($data)==0)
                {
                    $wnd = new AA_GenericWindowTemplate($id, "Affluenza", $this->id);
                    $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non sono presenti giornate per le quali inserire l'affluenza.")));

                    return $wnd;
                }
                $wnd->AddSelectField("giornata","Giornata",array("gravity"=>1,"required"=>true, "bottomLabel"=>"*Seleziona la giornata di riferimento.","options"=>$data));
        
                //votanti ore 12
                $wnd->AddTextField("ore_12","Votanti ore 12",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti ore 12"));
                //votanti ore 19
                $wnd->AddTextField("ore_19","Votanti ore 19",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti ore 19"));
                //votanti ore 22
                $wnd->AddTextField("ore_22","Votanti ore 22",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti ore 22"));
                            
                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();

                $wnd->SetSaveTask("Update_OC_ComuneAffluenza");
            }
            else
            {
                $wnd = new AA_GenericWindowTemplate($id, "Affluenza", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non sono presenti giornate per le quali inserire l'affluenza.")));
            }
            
            return $wnd;    
        }
        else
        {
            //to do view only
            $wnd = new AA_GenericWindowTemplate($id, "Affluenza", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"L'utente corrente non può apportare modifiche.")));

            return $wnd;
        }
    }

    //Template dlg add new affluenza comune
    public function Template_GetSierComuneAffluenzaAddNewDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierComuneAffluenzaAddNewDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Nuovo dato affluenza", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Nuovo dato affluenza", $this->id);

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)>0)
        {
            $form_data['id']=$object->GetId();
            $form_data['id_sier']=$object->GetId();
            $form_data['id_comune']=$comune->GetProp('id');

            $wnd=new AA_GenericFormDlg($id, "Nuovo dato affluenza", $this->id,$form_data,$form_data);
            
            $wnd->SetLabelAlign("right");
            $wnd->SetLabelWidth(120);
            $wnd->SetBottomPadding(32);
            $wnd->EnableValidation();
            
            $wnd->SetWidth(450);
            $wnd->SetHeight(450);
            
            $giornate=$object->GetGiornate();
            if(sizeof($giornate)>0)
            {
                //giornate
                $data=array();
                foreach($giornate as $curData=>$curGiornata)
                {
                    if($curGiornata["affluenza"]>0)
                    {
                        $data[]=array("id"=>$curData,"value"=>$curData);
                    }
                }
                if(sizeof($data)==0)
                {
                    $wnd = new AA_GenericWindowTemplate($id, "Affluenza", $this->id);
                    $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non sono presenti giornate per le quali inserire l'affluenza.")));

                    return $wnd;
                }
                $wnd->AddSelectField("giornata","Giornata",array("gravity"=>1,"required"=>true, "bottomLabel"=>"*Seleziona la giornata di riferimento.","options"=>$data));
        
                //votanti ore 12
                $wnd->AddTextField("ore_12","Votanti ore 12",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti ore 12"));
                //votanti ore 19
                $wnd->AddTextField("ore_19","Votanti ore 19",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti ore 19"));
                //votanti ore 22
                $wnd->AddTextField("ore_22","Votanti ore 22",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti ore 22"));
                            
                $wnd->EnableCloseWndOnSuccessfulSave();
                if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
                if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

                $wnd->SetSaveTask("AddNewSierComuneAffluenza");
            }
            else
            {
                $wnd = new AA_GenericWindowTemplate($id, "Affluenza", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non sono presenti giornate per le quali inserire l'affluenza.")));
            }
            
            return $wnd;    
        }
        else
        {
            //to do view only
            $wnd = new AA_GenericWindowTemplate($id, "Affluenza", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"L'utente corrente non può apportare modifiche.")));

            return $wnd;
        }
    }

    //Template dlg modify affluenza comune
    public function Template_GetSierComuneAffluenzaModifyDlg($object=null,$comune=null,$giornata="")
    {
        $id=static::AA_UI_PREFIX."_GetSierComuneAffluenzaModifyDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Modifica affluenza", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Modifica affluenza", $this->id);

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)>0)
        {
            $form_data['id']=$object->GetId();
            $form_data['id_sier']=$object->GetId();
            $form_data['id_comune']=$comune->GetProp('id');
            
            $affluenza=$comune->GetAffluenza(true);
            $form_data['giornata']=$giornata;
            if(isset($affluenza[$giornata]))
            {
                $form_data['ore_12']=$affluenza[$giornata]['ore_12'];
                $form_data['ore_19']=$affluenza[$giornata]['ore_19'];
                $form_data['ore_22']=$affluenza[$giornata]['ore_22'];
            }
    
            $wnd=new AA_GenericFormDlg($id, "Modifica affluenza", $this->id,$form_data,$form_data);
            
            $wnd->SetLabelAlign("right");
            $wnd->SetLabelWidth(120);
            $wnd->SetBottomPadding(32);
            $wnd->EnableValidation();
            
            $wnd->SetWidth(450);
            $wnd->SetHeight(450);
            
            $giornate=$object->GetGiornate();
            if(sizeof($giornate)>0)
            {
                //giornate
                $data=array();
                foreach($giornate as $giornata=>$curGiornata)
                {
                    if($curGiornata["affluenza"]>0)
                    {
                        $data[]=array("id"=>$giornata,"value"=>$giornata);
                    }
                }
                if(sizeof($data)==0)
                {
                    $wnd = new AA_GenericWindowTemplate($id, "Modifica affluenza", $this->id);
                    $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non sono presenti giornate per le quali inserire l'affluenza.")));

                    return $wnd;
                }
                $wnd->AddSelectField("giornata","Giornata",array("gravity"=>1,"required"=>true, "validateFunction"=>"IsSelected","bottomLabel"=>"*Seleziona la giornata di riferimento.","options"=>$data));

                //votanti ore 12
                $wnd->AddTextField("ore_12","Votanti ore 12",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti ore 12"));
                //votanti ore 19
                $wnd->AddTextField("ore_19","Votanti ore 19",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti ore 19"));
                //votanti ore 22
                $wnd->AddTextField("ore_22","Votanti ore 22",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti ore 22"));
                            
                $wnd->EnableCloseWndOnSuccessfulSave();
                if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
                if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
                $wnd->SetSaveTask("UpdateSierComuneAffluenza");
            }
            else
            {
                $wnd = new AA_GenericWindowTemplate($id, "Modifica affluenza", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non sono presenti giornate per le quali inserire l'affluenza.")));
            }
            
            return $wnd;    
        }
        else
        {
            //to do view only
            $wnd = new AA_GenericWindowTemplate($id, "Affluenza", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"L'utente corrente non può apportare modifiche.")));

            return $wnd;
        }
    }

    //Template dlg modify user
    public function Template_OC_DatiGeneraliModifyDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierComuneDatiGeneraliModifyDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Dati generali e corpo elettorale", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Dati generali e corpo elettorale", $this->id);

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)>0)
        {
            $form_data['id']=$object->GetId();
            $form_data['id_sier']=$object->GetId();
            $form_data['id_comune']=$comune->GetProp('id');
            $form_data['id_circoscrizione']=$comune->GetProp('id_circoscrizione');
            $form_data['denominazione']=$comune->GetProp('denominazione');
            $form_data['pec']=$comune->GetProp('pec');
            $form_data['contatti']=$comune->GetProp('contatti');
            $form_data['indirizzo']=$comune->GetProp('indirizzo');
            $form_data['sezioni']=$comune->GetProp('sezioni');
            $form_data['elettori_m']=$comune->GetProp('elettori_m');
            $form_data['elettori_f']=$comune->GetProp('elettori_f');

            $wnd=new AA_GenericFormDlg($id, "Dati generali e corpo elettorale", $this->id,$form_data,$form_data);
            
            $wnd->SetLabelAlign("right");
            $wnd->SetLabelWidth(120);
            $wnd->EnableValidation();
            
            $wnd->SetWidth(800);
            $height=0;

            if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_DATIGENERALI)>0)
            {
                $height+=350;
                //pec
                $wnd->AddTextField("pec","Pec",array("required"=>true,"gravity"=>1,"validateFunction"=>"IsMail", "bottomLabel"=>"*Indirizzo di posta certificata."));

                //indirizzo
                $wnd->AddTextField("indirizzo","Indirizzo",array("required"=>true,"gravity"=>1, "bottomLabel"=>"*Via e numero civico."));

                //contatti
                $wnd->AddTextareaField("contatti","Note e contatti",array("gravity"=>1, "bottomLabel"=>"*Eventuali informazioni di recapito utili."));
            }

            //Dati corpo elettorale
            if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_CORPO_ELETTORALE)>0)
            {
                $height+=300;
                $section=new AA_FieldSet($id."_Section_DatiCorpoElettorale","Corpo elettorale");
                $section->AddTextField("sezioni", "Sezioni", array("required"=>true,"bottomPadding"=>32,"validateFunction"=>"IsInteger","bottomLabel"=>"*Numero di sezioni"));
                $section->AddTextField("elettori_m", "Maschi", array("required"=>true,"bottomPadding"=>32,"validateFunction"=>"IsInteger","bottomLabel"=>"*Numero elettori."),false);
                $section->AddTextField("elettori_f", "Femmine", array("required"=>true,"bottomPadding"=>32,"validateFunction"=>"IsInteger","bottomLabel"=>"*Numero elettrici."),false);
                $wnd->AddGenericObject($section);
            }
                    
            $wnd->EnableCloseWndOnSuccessfulSave();
            $wnd->enableRefreshOnSuccessfulSave();
            $wnd->SetSaveTask("Update_OC_ComuneDatiGenerali");
            $wnd->SetHeight($height);

            return $wnd;    
        }
        else
        {
            //to do view only
            $wnd = new AA_GenericWindowTemplate($id, "Dati generali e corpo elettorale", $this->id);

            return $wnd;
        }
    }

    //Template layout risultati
    public function Template_GetSierComuneRisultatiViewLayout($object=null,$comune=null,$id="")
    {
        if(!$object) $object=new AA_Sier($_REQUEST['id']);
        if(!$object->isValid())
        {
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
            $layout->AddRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Errore nel recupero dei dati.</span></div>")));
            return $layout;
        }

        if(!$comune) $comune = $object->GetComune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
            $layout->AddRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Errore nel recupero dei dati del comune.</span></div>")));
            return $layout;
        }

        $risultati=$comune->GetRisultati(true);
        $rows_fixed_height=50;

        $id.="_".static::AA_UI_LAYOUT_RISULTATI_COMUNALI;
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        $multiview=new AA_JSON_Template_Multiview($id."_Multiview_".$object->GetId(),array(
            "type" => "clean",
            "css" => "AA_Detail_Content",
            "value" => $id."_RisultatiGeneraleBox")
        );

        //---------------------------- header --------------------------------
        $header = new AA_JSON_Template_Layout($id . "_Header" . "_".$object->GetId(), array("type" => "clean", "height" => 38, "css" => "AA_SectionContentHeader"));
        $canModify=false;
        $layout_tab=new AA_JSON_Template_Layout($id . "_Layout_TabBar_".$object->GetId(),array("type"=>"clean","minWidth"=>500));
        $gravity_tabbar=4;
        $layout_tab->AddCol(new AA_JSON_Template_Generic($id . "_TabBar_".$object->GetId(), array(
            "view" => "tabbar",
            "gravity"=>$gravity_tabbar,
            "borderless" => true,
            "value" => $id."_RisultatiGeneraleBox",
            "css" => "AA_Header_TabBar",
            "multiview" => true,
            "view_id" => $id . "_Multiview_".$object->GetId(),
            "options" => array(
                array("id"=>$id."_RisultatiGeneraleBox","value"=>"Risultati generali"),
                array("id"=>$id."_RisultatiCoalizioniBox","value"=>"Voti candidati Presidente"),
                array("id"=>$id."_RisultatiListeBox","value"=>"Voti Liste"),
                array("id"=>$id."_RisultatiPreferenzeBox","value"=>"Voti candidati Consiglio regionale"),
            )
        )));
        $header->AddCol($layout_tab);
        $layout->AddRow($header);
        //---------------------------------------------------------------------
        $layout->AddRow($multiview);
        
        //--------------------------- Dati generali ----------------------------
        $generaleLayout=new AA_JSON_Template_Layout($id."_RisultatiGeneraleBox",array("type"=>"clean"));
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));

        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI) > 0)
        {
            
            $modify_btn=new AA_JSON_Template_Generic($id."_ModifyRisultatiGenerali_btn",array(
                "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-pencil",
                "label"=>"Modifica",
                "css"=>"webix_primary",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Modifica dati generali dei risultati",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierComuneRisultatiGeneraliModifyDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},'".$this->id."')"
            ));

            $toolbar->AddElement($modify_btn);
        }
        $generaleLayout->addRow($toolbar);

        $template="<div style='display: flex; align-items:center;justify-content: flex-start; width:99%;height:100%;padding-left:1%;'><div style='font-weight:700;width: 350px;'>#title#</div><div style='width: 150px; text-align: right;padding-right: 50px'>#value#</div></div>";
        
        //Sezioni scrutinate
        if(isset($risultati['sezioni_scrutinate']))$value=$risultati['sezioni_scrutinate'];
        else $value=0;
        $sezioni=new AA_JSON_Template_Template($id."_SezioniScrutinate",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Sezioni scrutinate:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        //votanti maschi
        if(isset($risultati['votanti_m']))$value=$risultati['votanti_m'];
        else $value=0;
        $votanti_m=new AA_JSON_Template_Template($id."_VotantiM",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Votanti maschi:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        //votanti femmine
        if(isset($risultati['votanti_f']))$value=$risultati['votanti_f'];
        else $value=0;
        $votanti_f=new AA_JSON_Template_Template($id."_VotantiF",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Votanti femmine:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        $riga=new AA_JSON_Template_Layout($id."_DatiGeneraliRow",array("css"=>array("border-bottom"=>"1px solid #dadee0 !important","type"=>"clean")));
        $riga->AddRow($sezioni);
        $riga->AddRow($votanti_m);
        $riga->AddRow($votanti_f);
        //$generaleLayout->AddRow($riga);

        //Voti contestati non assegnati
        if(isset($risultati['voti_contestati_na']))$value=$risultati['voti_contestati_na'];
        else $value=0;
        $voti_contestati=new AA_JSON_Template_Template($id."_VotiContestatiNA",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Voti contestati non assegnati:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        //Schede nulle
        if(isset($risultati['schede_nulle']))$value=$risultati['schede_nulle'];
        else $value=0;
        $schede_nulle=new AA_JSON_Template_Template($id."_SchedeNulle",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Schede nulle:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        //Schede bianche
        if(isset($risultati['schede_bianche']))$value=$risultati['schede_bianche'];
        else $value=0;
        $schede_bianche=new AA_JSON_Template_Template($id."_SchedeBianche",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Schede bianche:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        //Schede contenenti esclusivamente voti nulli
        if(isset($risultati['schede_voti_nulli']))$value=$risultati['schede_voti_nulli'];
        else $value=0;
        $schede_voti_nulli=new AA_JSON_Template_Template($id."_SchedeVotiNulli",array(
            "template"=>$template,
            "gravity"=>1,
            "type"=>"clean",
            "data"=>array("title"=>"Schede contenenti esclusivamente voti nulli:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0")
        ));

        //$riga=new AA_JSON_Template_Layout($id."_SecondRow",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddRow($schede_bianche);
        $riga->AddRow($schede_nulle);
        $riga->AddRow($voti_contestati);
        //$generaleLayout->AddRow($riga);

        //$riga=new AA_JSON_Template_Layout($id."_ThirdRow",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddRow($schede_voti_nulli);
        
        //$generaleLayout->AddCol(new AA_JSON_Template_Generic());
        $generaleLayout->AddRow($riga);
        $generaleLayout->AddRow(new AA_JSON_Template_Generic("",array("height"=>38,"css"=>array("border-top"=>"1px solid #dadee0"))));

        //$generaleLayout->AddRow(new AA_JSON_Template_Generic());
        $multiview->AddRow($generaleLayout);
        //-------------------------------------------------------------------------------------

        //----------------------------- Risultati voti coalizioni -----------------------------
        $generaleLayout=new AA_JSON_Template_Layout($id."_RisultatiCoalizioniBox",array("type"=>"clean"));

        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar_RisultatiCoalizioni",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI) > 0)
        {  
            $modify_btn=new AA_JSON_Template_Generic($id."_ModifyRisultatiCoalizioni_btn",array(
                "view"=>"button",
                 "type"=>"icon",
                 "icon"=>"mdi mdi-pencil",
                 "label"=>"Modifica",
                 "css"=>"webix_primary",
                 "align"=>"right",
                 "width"=>120,
                 "tooltip"=>"Modifica dati delle coalizioni",
                 "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierComuneRisultatiCoalizioniModifyDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},'".$this->id."')"
            ));

            $toolbar->AddElement($modify_btn);
        }

        $generaleLayout->addRow($toolbar);

        $platform=AA_Platform::GetInstance($this->oUser);
        $DefaultImagePath=AA_Const::AA_WWW_ROOT."/".$platform->GetModulePathURL($this->id)."/img";

        $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
        $template="<div style='display: flex; align-items:center;justify-content: flex-start; width:99%;height:100%;padding-left:1%;'><div class='AA_DataView_Sier_item' style='display: flex; align-items: center; max-height:80px'><div style='display: flex; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; overflow: clip; margin-right: 1em;'><img src='#image#' height='100%'/></div><div style='font-weight:700;width: 350px;'>#title#</div><div style='width: 150px; text-align: right; padding-right: 50px'>#value#</div></div></div>";
        $coalizioni=$object->GetCoalizioni();
        if(sizeof($coalizioni)>0)
        {
            foreach($coalizioni as $idCoalizione=>$curCoalizione)
            {
                if($curCoalizione->GetProp('image') != "")
                {
                    $curImagePath=AA_Const::AA_WWW_ROOT."/storage.php?object=".$curCoalizione->GetProp('image');
                }

                //voti coalizione
                if(isset($risultati['voti_presidente']) && isset($risultati['voti_presidente'][$idCoalizione])) $value=$risultati['voti_presidente'][$idCoalizione];
                else $value=0;
                $row=new AA_JSON_Template_Template($id."_VotiCoalizione_".$idCoalizione,array(
                    "template"=>$template,
                    "gravity"=>1,
                    "type"=>"clean",
                    "data"=>array("title"=>$curCoalizione->GetProp("nome_candidato").":","value"=>$value,"image"=>$curImagePath),
                    "css"=>array("border-right"=>"1px solid #dadee0")
                ));
                $generaleLayout->AddRow($row);
            }    
        }
        else
        {
            $generaleLayout->AddRow(new AA_JSON_Template_Generic());
        }

        $generaleLayout->AddRow(new AA_JSON_Template_Generic("",array("height"=>38,"css"=>array("border-top"=>"1px solid #dadee0 !important"))));

        $multiview->AddRow($generaleLayout);
        //-------------------------------------------------------------------------------------

        //------------------------------- Risultati voti liste --------------------------------
        $generaleLayout=new AA_JSON_Template_Layout($id."_RisultatiListeBox",array("type"=>"clean"));

        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar_RisultatiListe",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI) > 0)
        {
            $modify_btn=new AA_JSON_Template_Generic($id."_ModifyRisultatiListe_btn",array(
                "view"=>"button",
                 "type"=>"icon",
                 "icon"=>"mdi mdi-pencil",
                 "label"=>"Modifica",
                 "css"=>"webix_primary",
                 "align"=>"right",
                 "width"=>120,
                 "tooltip"=>"Modifica dati generali dei risultati",
                 "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierComuneRisultatiListeModifyDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},'".$this->id."')"
            ));

            $toolbar->AddElement($modify_btn);
        }
        $generaleLayout->addRow($toolbar);

        $platform=AA_Platform::GetInstance($this->oUser);
        $DefaultImagePath=AA_Const::AA_WWW_ROOT."/".$platform->GetModulePathURL($this->id)."/img";

        $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
        $template="<div style='display: flex; align-items:center;justify-content: space-between; width:99%;height:100%;padding-left:1%;'><div style='display: flex; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; overflow: clip; margin-right: 1em;'><img src='#image#' height='100%'/></div><div style='font-weight:700; width: 250px'>#title#</div><div style='width: 80px; text-align:center'>#value#</div></div>";
        
        $liste=$object->GetListe();
        //AA_Log::Log(__METHOD__." - liste: ".print_r($liste,true),100);
        if(sizeof($liste)>0)
        {
            $liste_data=array();
            foreach($liste as $idLista=>$curLista)
            {
                if($curLista->GetProp('image') != "")
                {
                    $curImagePath=AA_Const::AA_WWW_ROOT."/storage.php?object=".$curLista->GetProp('image');
                }
                else
                {
                    $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
                }

                $value=0;
                if(isset($risultati['voti_lista']) && isset($risultati['voti_lista'][$idLista]) && $risultati['voti_lista'][$idLista]>0) $value=intVal($risultati['voti_lista'][$idLista]);
                $liste_data[]=array("id"=>$idLista,"title"=>$curLista->GetProp("denominazione"),"value"=>$value,"image"=>$curImagePath);
            }
            
            //AA_Log::Log(__METHOD__." - liste: ".print_r($liste_data,true),100);

            $dataview_liste=new AA_JSON_Template_Generic($id."_ListeDataView",array(
                "view"=>"dataview",
                "xCount"=>4,
                "module_id"=>$this->id,
                "type"=>array(
                    "type"=>"tiles",
                    "height"=>80,
                    "width"=>"auto",
                    "css"=>"AA_DataView_Sier_item",
                ),
                //"on" => array("onItemDblClick" => "AA_MainApp.utils.getEventHandler('ListaDblClick','".$this->GetId()."')"),
                "template"=>$template,
                "data"=>$liste_data
            ));
            $generaleLayout->AddRow($dataview_liste);
        }
        else
        {
            $generaleLayout->AddRow(new AA_JSON_Template_Template($id."_FakeListe",array("template"=>"non ci sono liste definite.")));
        }

        $generaleLayout->AddRow(new AA_JSON_Template_Generic("",array("height"=>38,"css"=>array("border-top"=>"1px solid #dadee0 !important"))));
        $multiview->AddRow($generaleLayout);
        //-------------------------------------------------------------------------------------

        //------------------------------ Risultati preferenze ---------------------------------
        $generaleLayout=new AA_JSON_Template_Layout($id."_RisultatiPreferenzeBox",array("type"=>"clean"));
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar_RisultatiPreferenze",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));

        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI) > 0)
        {   
            $modify_btn=new AA_JSON_Template_Generic($id."_ModifyRisultatiPreferenze_btn",array(
                "view"=>"button",
                 "type"=>"icon",
                 "icon"=>"mdi mdi-pencil",
                 "label"=>"Modifica",
                 "css"=>"webix_primary",
                 "align"=>"right",
                 "width"=>120,
                 "tooltip"=>"Modifica preferenze",
                 "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierComuneRisultatiPreferenzeModifyDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},'".$this->id."')"
            ));
            $toolbar->addElement($modify_btn);
        }
            
        $generaleLayout->addRow($toolbar);

        $platform=AA_Platform::GetInstance($this->oUser);
        $DefaultImagePath=AA_Const::AA_WWW_ROOT."/".$platform->GetModulePathURL($this->id)."/img";

        $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
        $template="<div style='display: flex; align-items:center;justify-content: space-between; width:99%;height:100%;padding-left:1%;'><div style='display: flex; align-items: center; justify-content: center; height: 60px; width: 60px; border-radius: 50%; overflow: clip; margin-right: 1em;'><img src='#image#' height='100%'/></div><div style='font-weight:700; width: 250px'>#title#</div><div style='width: 80px; text-align:center'>#value#</div><div style='display: flex;  align-items: center; justify-content: space-between; height: 100%; padding: 5px; width: 60px'>#modify#&nbsp;#trash#</div></div>";
        
        $candidati=$object->GetCandidati(null,null,$comune->GetProp("id_circoscrizione"));
        //AA_Log::Log(__METHOD__." - liste: ".print_r($liste,true),100);
        $candidati_data=array();
        foreach($candidati as $idCandidato=>$curCandidato)
        {
            $lista=$object->GetLista($curCandidato->GetProp("id_lista"));
            if($lista->GetProp('image') != "")
            {
                $curImagePath=AA_Const::AA_WWW_ROOT."/storage.php?object=".$curLista->GetProp('image');
            }
            else
            {
                $curImagePath=$DefaultImagePath."/placeholder_coalizioni.png";
            }

            if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI) > 0)
            {
                $modify="<a title='Modifica' class='AA_Button_Link' onclick='AA_MainApp.utils.callHandler(\"dlg\", {task:\"GetSierComuneRisultatiPreferenzeModifyDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",id_candidato:".$idCandidato.",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},\"".$this->id."\")'><span class='mdi mdi-pencil'></span></a>";
                $trash="<a title='Rimuovi' class='AA_Button_Link AA_DataTable_Ops_Button_Red' style='color: red' onclick='AA_MainApp.utils.callHandler(\"dlg\", {task:\"GetSierComuneRisultatiPreferenzeTrashDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",id_candidato:".$idCandidato.",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},\"".$this->id."\")'><span class='mdi mdi-trash-can'></span></a>";
            }
            else
            {
                $modify="&nbsp;";
                $trash="&nbsp;";
            }

            $value=0;
            if(isset($risultati['voti_candidato']) && isset($risultati['voti_candidato'][$idCandidato]) && $risultati['voti_candidato'][$idCandidato]['voti']>0)
            {
                $value=intVal($risultati['voti_candidato'][$idCandidato]['voti']);
                $candidati_data[]=array("id"=>$idCandidato,"title"=>$curCandidato->GetProp("cognome")." ".$curCandidato->GetProp("nome"),"value"=>$value,"image"=>$curImagePath,"modify"=>$modify,"trash"=>$trash);
            }
        }
    
        if(sizeof($candidati_data)>0)
        {
            $dataview_liste=new AA_JSON_Template_Generic($id."_CandidatiDataView",array(
                "view"=>"dataview",
                "xCount"=>3,
                "module_id"=>$this->id,
                "type"=>array(
                    "type"=>"tiles",
                    "height"=>80,
                    "width"=>"auto",
                    "css"=>"AA_DataView_Sier_item",
                ),
                //"on" => array("onItemDblClick" => "AA_MainApp.utils.getEventHandler('ListaDblClick','".$this->GetId()."')"),
                "template"=>$template,
                "data"=>$candidati_data
            ));
            $generaleLayout->AddRow($dataview_liste);
        }
        else
        {
            $generaleLayout->AddRow(new AA_JSON_Template_Template($id."_FakePreferenze",array("template"=>"<div style='display:flex;justify-content: center; align-items: center;widht:100;height:100%'>non ci sono preferenze definite.</div>")));
        }

        $generaleLayout->AddRow(new AA_JSON_Template_Generic("",array("height"=>38,"css"=>array("border-top"=>"1px solid #dadee0 !important"))));
        $multiview->AddRow($generaleLayout);
        //-------------------------------------------------------------------------------------

        return $layout;
    }

    //Template dlg modify risultati generali comune
    public function Template_GetSierComuneRisultatiGeneraliModifyDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierComuneRisultatiGeneraliModifyDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Modifica risultati generali", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Modifica risultati generali", $this->id);

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)>0)
        {
        $form_data['id']=$object->GetId();
        $form_data['id_sier']=$object->GetId();
        $form_data['id_comune']=$comune->GetProp('id');
        $form_data['sezioni_scrutinate']=0;
        $form_data['votanti_m']=0;
        $form_data['votanti_f']=0;
        $form_data['schede_bianche']=0;
        $form_data['schede_nulle']=0;
        $form_data['voti_contestati_na']=0;
        $form_data['schede_voti_nulli']=0;

        $risultati=$comune->GetRisultati(true);
        foreach($risultati as $key=>$val)
        {
            if(isset($form_data[$key])) $form_data[$key]=$val;
        }

        $wnd=new AA_GenericFormDlg($id, "Modifica risultati generali", $this->id,$form_data,$form_data);
            
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(230);
        $wnd->SetBottomPadding(32);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(450);
        $wnd->SetHeight(650);
        
        //Sezioni scrutinate
        $wnd->AddTextField("sezioni_scrutinate","Sezioni scrutinate",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero di sezioni scrutinate."));
        //votanti maschi
        $wnd->AddTextField("votanti_m","Votanti maschi",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti maschi."));
        //votanti femminie
        $wnd->AddTextField("votanti_f","Votanti femmine",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti femmine."));
        //schede bianche
        $wnd->AddTextField("schede_bianche","Schede bianche",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero delle schede bianche."));
        //schede nulle
        $wnd->AddTextField("schede_nulle","Schede nulle",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero delle schede nulle."));
        //voti contestati non assegnati
        $wnd->AddTextField("voti_contestati_na","Voti contestati non assegnati",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero di voti contestati non assegnati."));
        //schede contenenti voti nulli
        $wnd->AddTextField("schede_voti_nulli","Schede con voti nulli",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero delle schede contenenti esclusivamente voti nulli."));

        $wnd->EnableCloseWndOnSuccessfulSave();
        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
        $wnd->SetSaveTask("UpdateSierComuneRisultatiGenerali");

        return $wnd;    
        }
        else
        {
            //to do view only
            $wnd = new AA_GenericWindowTemplate($id,"Modifica risultati generali", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"L'utente corrente non può apportare modifiche.")));

            return $wnd;
        }
    }

    //Template dlg modify risultati coalizioni comune
    public function Template_GetSierComuneRisultatiCoalizioniModifyDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierComuneRisultatiCoalizioniModifyDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Modifica voti candidati Presidente", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Modifica voti candidati Presidente", $this->id);
        $coalizioni=$object->GetCoalizioni();

        if(sizeof($coalizioni)==0)
        {
            $wnd = new AA_GenericWindowTemplate($id,"Modifica voti candidati Presidente", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non ci sono coalizioni impostate.")));

            return $wnd;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)>0)
        {
            $form_data['id']=$object->GetId();
            $form_data['id_sier']=$object->GetId();
            $form_data['id_comune']=$comune->GetProp('id');
            
            foreach($coalizioni as $idCoalizione=>$curCoalizione)
            {
                $form_data[$idCoalizione]=0;
            }

            $risultati=$comune->GetRisultati(true);
            foreach($risultati['voti_presidente'] as $key=>$val)
            {
                if(isset($form_data[$key])) $form_data[$key]=$val;
            }

            $wnd=new AA_GenericFormDlg($id, "Modifica voti candidati Presidente", $this->id,$form_data,$form_data);
                
            $wnd->SetLabelAlign("right");
            $wnd->SetLabelWidth(230);
            $wnd->SetBottomPadding(32);
            $wnd->EnableValidation();
            
            $wnd->SetWidth(450);
            $wnd->SetHeight(120+90*sizeof($coalizioni));
            
            foreach($coalizioni as $idCoalizione=>$curCoalizione)
            {
                //coalizioni
                $wnd->AddTextField("$idCoalizione",$curCoalizione->GetProp("nome_candidato"),array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*Inserire soli il numero dei voti validi."));
            }
           
            $wnd->EnableCloseWndOnSuccessfulSave();
            if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
            if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
            $wnd->SetSaveTask("UpdateSierComuneRisultatiCoalizioni");

            return $wnd;    
        }
        else
        {
            $wnd = new AA_GenericWindowTemplate($id,"Modifica voti Presidente", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non ci sono colaizioni impostate.")));

            return $wnd;
        }
    }

    //Template dlg modify risultati preferenze comune
    public function Template_GetSierComuneRisultatiPreferenzeModifyDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierComuneRisultatiCoalizioniModifyDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Modifica voti candidato", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Modifica voti candidato", $this->id);
        
        $candidati=$object->GetCandidati(null,null,$comune->GetProp("id_circoscrizione"));
        if(sizeof($candidati)==0)
        {
            $wnd = new AA_GenericWindowTemplate($id,"Modifica voti candidato", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non ci sono candidati caricati.")));

            return $wnd;
        }

        $lista_candidati=array();
        $risultati=$comune->GetRisultati(true);
        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)>0)
        {
            $form_data['id']=$object->GetId();
            $form_data['id_sier']=$object->GetId();
            $form_data['id_comune']=$comune->GetProp('id');
            $form_data['voti']=0;
            foreach($candidati as $idCandidato=>$curCandidato)
            {
                $lista_candidati[]=array("id"=>$idCandidato,"value"=>$curCandidato->GetProp("cognome")." ".$curCandidato->GetProp("nome"));
            }

            AA_Log::Log(__METHOD__." - lista candidati: ".print_r($lista_candidati,true),100);

            if(isset($_REQUEST['id_candidato']) && $_REQUEST['id_candidato']>0)
            {
                $form_data['id_candidato']=$_REQUEST['id_candidato'];
                if(isset($risultati['voti_candidato']) && isset($risultati['voti_candidato'][$_REQUEST['id_candidato']]))
                {
                    $form_data['voti']=$risultati['voti_candidato'][$_REQUEST['id_candidato']]['voti'];
                }
            }

            $wnd=new AA_GenericFormDlg($id, "Modifica voti candidati", $this->id,$form_data,$form_data);
                
            $wnd->SetLabelAlign("right");
            $wnd->SetLabelWidth(100);
            $wnd->SetBottomPadding(32);
            $wnd->EnableValidation();
            
            $wnd->SetWidth(450);
            $wnd->SetHeight(340);

            //Candidato
            $wnd->AddSelectField("id_candidato","Candidato",array("required"=>true,"validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il nominativo del candidato.", "placeholder"=>"...","options"=>$lista_candidati));
            
            //voti
            $wnd->AddTextField("voti","Voti",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*Inserire soli il numero dei voti validi."));
           
            $wnd->EnableCloseWndOnSuccessfulSave();
            if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
            if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
            $wnd->SetSaveTask("UpdateSierComuneRisultatiPreferenze");

            return $wnd;    
        }
        else
        {
            $wnd = new AA_GenericWindowTemplate($id,"Modifica voti Presidente", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non ci sono colaizioni impostate.")));

            return $wnd;
        }
    }

    //Template dlg modify risultati preferenze comune
    public function Template_GetSierOCModifyRisultatiPreferenzeDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierOCModifyRisultatiCoalizioniDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Modifica voti candidato", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Modifica voti candidato", $this->id);
        
        $candidati=$object->GetCandidati(null,null,$comune->GetProp("id_circoscrizione"));
        if(sizeof($candidati)==0)
        {
            $wnd = new AA_GenericWindowTemplate($id,"Modifica voti candidato", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non ci sono candidati caricati.")));

            return $wnd;
        }

        $lista_candidati=array();
        $risultati=$comune->GetRisultati(true);
        if(($object->GetAbilitazioni() & AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI)>0)
        {
            $form_data['id']=$object->GetId();
            $form_data['id_sier']=$object->GetId();
            $form_data['id_comune']=$comune->GetProp('id');
            $form_data['voti']=0;
            foreach($candidati as $idCandidato=>$curCandidato)
            {
                $lista_candidati[]=array("id"=>$idCandidato,"value"=>$curCandidato->GetProp("cognome")." ".$curCandidato->GetProp("nome"));
            }

            //AA_Log::Log(__METHOD__." - lista candidati: ".print_r($lista_candidati,true),100);

            if(isset($_REQUEST['id_candidato']) && $_REQUEST['id_candidato']>0)
            {
                $form_data['id_candidato']=$_REQUEST['id_candidato'];
                if(isset($risultati['voti_candidato']) && isset($risultati['voti_candidato'][$_REQUEST['id_candidato']]))
                {
                    $form_data['voti']=$risultati['voti_candidato'][$_REQUEST['id_candidato']]['voti'];
                }
            }

            $wnd=new AA_GenericFormDlg($id, "Modifica voti candidati", $this->id,$form_data,$form_data);
                
            $wnd->SetLabelAlign("right");
            $wnd->SetLabelWidth(100);
            $wnd->SetBottomPadding(32);
            $wnd->EnableValidation();
            
            $wnd->SetWidth(450);
            $wnd->SetHeight(340);

            //Candidato
            $wnd->AddSelectField("id_candidato","Candidato",array("required"=>true,"validateFunction"=>"IsSelected","bottomLabel"=>"*Selezionare il nominativo del candidato.", "placeholder"=>"...","options"=>$lista_candidati));
            
            //voti
            $wnd->AddTextField("voti","Voti",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*Inserire soli il numero dei voti validi."));
           
            $wnd->EnableCloseWndOnSuccessfulSave();
            $wnd->enableRefreshOnSuccessfulSave();
            //if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
            $wnd->SetSaveTask("Update_OC_ComuneRisultatiPreferenze");

            return $wnd;    
        }
        else
        {
            $wnd = new AA_GenericWindowTemplate($id,"Modifica voti candidato", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Caricamento risultati disabilitato.")));

            return $wnd;
        }
    }

    //Template dlg modify risultati coalizioni comune
    public function Template_GetSierComuneRisultatiListeModifyDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierComuneRisultatiListeModifyDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Modifica voti Liste circoscrizionali", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Modifica voti voti Liste circoscrizionali", $this->id);
        $liste=$object->GetListe();

        if(sizeof($liste)==0)
        {
            $wnd = new AA_GenericWindowTemplate($id,"Modifica voti Liste circoscrizionali", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non ci sono liste impostate.")));

            return $wnd;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)>0)
        {
            $form_data['id']=$object->GetId();
            $form_data['id_sier']=$object->GetId();
            $form_data['id_comune']=$comune->GetProp('id');
            
            foreach($liste as $idLista=>$curLista)
            {
                $form_data["lista_".$idLista]=0;
            }

            $risultati=$comune->GetRisultati(true);
            foreach($risultati['voti_lista'] as $key=>$val)
            {
                if(isset($form_data["lista_".$key])) $form_data["lista_".$key]=$val;
            }

            $wnd=new AA_GenericFormDlg($id, "Modifica voti candidati liste circoscrizionali", $this->id,$form_data,$form_data);
                
            $wnd->SetLabelAlign("right");
            $wnd->SetLabelWidth(200);
            $wnd->SetBottomPadding(18);
            $wnd->EnableValidation();
            if(sizeof($liste)>=4) $wnd->SetWidth(1280);
            else $wnd->SetWidth(300*sizeof($liste));
            $wnd->SetHeight(130+25*sizeof($liste));
            
            $numforRow=4;
            $curNumRow=0;
            foreach($liste as $idLista=>$curLista)
            {
                //liste
                if($curNumRow%$numforRow) $wnd->AddTextField("lista_".$idLista,$curLista->GetProp("denominazione"),array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive"),false);
                else $wnd->AddTextField("lista_".$idLista,$curLista->GetProp("denominazione"),array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive"));
                $curNumRow++;
            }
           
            $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_BottomNote",array("type"=>"clean","template"=>"<span style='font-size: smaller'>*Inserire solo il numero dei voti validi.</span>")));
            $wnd->EnableCloseWndOnSuccessfulSave();
            if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
            if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
            $wnd->SetSaveTask("UpdateSierComuneRisultatiListe");

            return $wnd;    
        }
        else
        {
            $wnd = new AA_GenericWindowTemplate($id,"Modifica voti Liste circoscrizionali", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"L'utente corrente non può modificare l'oggetto.")));

            return $wnd;
        }
    }

    //Template dlg modify risultati generali comune
    public function Template_GetSierOCModifyRisultatiGeneraliDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierOCModifyRisultatiGeneraliDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Modifica risultati generali", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Modifica risultati generali", $this->id);

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)>0)
        {
        $form_data['id']=$object->GetId();
        $form_data['id_sier']=$object->GetId();
        $form_data['id_comune']=$comune->GetProp('id');
        $form_data['sezioni_scrutinate']=0;
        $form_data['votanti_m']=0;
        $form_data['votanti_f']=0;
        $form_data['schede_bianche']=0;
        $form_data['schede_nulle']=0;
        $form_data['voti_contestati_na']=0;
        $form_data['schede_voti_nulli']=0;

        $risultati=$comune->GetRisultati(true);
        foreach($risultati as $key=>$val)
        {
            if(isset($form_data[$key])) $form_data[$key]=$val;
        }

        $wnd=new AA_GenericFormDlg($id, "Modifica risultati generali", $this->id,$form_data,$form_data);
            
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(230);
        $wnd->SetBottomPadding(32);
        $wnd->EnableValidation();
        
        $wnd->SetWidth(450);
        $wnd->SetHeight(650);
        
        //Sezioni scrutinate
        $wnd->AddTextField("sezioni_scrutinate","Sezioni scrutinate",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero di sezioni scrutinate."));
        //votanti maschi
        $wnd->AddTextField("votanti_m","Votanti maschi",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti maschi."));
        //votanti femminie
        $wnd->AddTextField("votanti_f","Votanti femmine",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero dei votanti femmine."));
        //schede bianche
        $wnd->AddTextField("schede_bianche","Schede bianche",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero delle schede bianche."));
        //schede nulle
        $wnd->AddTextField("schede_nulle","Schede nulle",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero delle schede nulle."));
        //voti contestati non assegnati
        $wnd->AddTextField("voti_contestati_na","Voti contestati non assegnati",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero di voti contestati non assegnati."));
        //schede contenenti voti nulli
        $wnd->AddTextField("schede_voti_nulli","Schede con voti nulli",array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*numero delle schede contenenti esclusivamente voti nulli."));

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        //if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
        $wnd->SetSaveTask("Update_OC_ComuneRisultatiGenerali");

        return $wnd;    
        }
        else
        {
            //to do view only
            $wnd = new AA_GenericWindowTemplate($id,"Modifica risultati generali", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"L'utente corrente non può apportare modifiche.")));

            return $wnd;
        }
    }

    //Template dlg modify risultati generali comune
    public function Template_GetSierOCModifyRisultatiCoalizioniDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierOCModifyRisultatiCoalizioniDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Modifica voti candidati Presidente", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Modifica voti candidati Presidente", $this->id);
        $coalizioni=$object->GetCoalizioni();

        if(sizeof($coalizioni)==0)
        {
            $wnd = new AA_GenericWindowTemplate($id,"Modifica voti candidati Presidente", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non ci sono coalizioni impostate.")));

            return $wnd;
        }

        if(($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE)>0)
        {
            $form_data['id']=$object->GetId();
            $form_data['id_sier']=$object->GetId();
            $form_data['id_comune']=$comune->GetProp('id');
            
            foreach($coalizioni as $idCoalizione=>$curCoalizione)
            {
                $form_data[$idCoalizione]=0;
            }

            $risultati=$comune->GetRisultati(true);
            foreach($risultati['voti_presidente'] as $key=>$val)
            {
                if(isset($form_data[$key])) $form_data[$key]=$val;
            }

            $wnd=new AA_GenericFormDlg($id, "Modifica voti candidati Presidente", $this->id,$form_data,$form_data);
                
            $wnd->SetLabelAlign("right");
            $wnd->SetLabelWidth(230);
            $wnd->SetBottomPadding(32);
            $wnd->EnableValidation();
            
            $wnd->SetWidth(450);
            $wnd->SetHeight(120+90*sizeof($coalizioni));
            
            foreach($coalizioni as $idCoalizione=>$curCoalizione)
            {
                //coalizioni
                $wnd->AddTextField("$idCoalizione",$curCoalizione->GetProp("nome_candidato"),array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive","bottomLabel"=>"*Inserire soli il numero dei voti validi."));
            }
           
            $wnd->EnableCloseWndOnSuccessfulSave();
            $wnd->enableRefreshOnSuccessfulSave();
            $wnd->SetSaveTask("Update_OC_ComuneRisultatiCoalizioni");

            return $wnd;    
        }
        else
        {
            $wnd = new AA_GenericWindowTemplate($id,"Modifica voti Presidente", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non ci sono coalizioni impostate.")));

            return $wnd;
        }
    }

    //Template dlg modify risultati liste comune (OC)
    public function Template_GetSierOCModifyRisultatiListeDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_GetSierOCModifyRisultatiListeDlg";
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Modifica voti Liste circoscrizionali", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Modifica voti Liste circoscrizionali", $this->id);
        $liste=$object->Getliste(null,$comune->GetProp("id_circoscrizione"));

        if(sizeof($liste)==0)
        {
            $wnd = new AA_GenericWindowTemplate($id,"Modifica voti liste circoscrizionali", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Non sono presenti liste.")));

            return $wnd;
        }

        if(($object->GetAbilitazioni()&AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RISULTATI)>0)
        {
            $form_data['id']=$object->GetId();
            $form_data['id_sier']=$object->GetId();
            $form_data['id_comune']=$comune->GetProp('id');
        
            foreach($liste as $idLista=>$curLista)
            {
                $form_data["lista_".$idLista]=0;
            }

            $risultati=$comune->GetRisultati(true);
            foreach($risultati['voti_lista'] as $key=>$val)
            {
                if(isset($form_data["lista_".$key])) $form_data["lista_".$key]=$val;
            }

            $wnd=new AA_GenericFormDlg($id, "Modifica voti candidati liste circoscrizionali", $this->id,$form_data,$form_data);
                
            $wnd->SetLabelAlign("right");
            $wnd->SetLabelWidth(190);
            $wnd->SetBottomPadding(18);
            $wnd->EnableValidation();
            if(sizeof($liste)>=4) $wnd->SetWidth(1280);
            else $wnd->SetWidth(300*sizeof($liste));
            $wnd->SetHeight(130+25*sizeof($liste));
            
            $numforRow=4;
            $curNumRow=0;
            foreach($liste as $idLista=>$curLista)
            {
                //liste
                if($curNumRow%$numforRow) $wnd->AddTextField("lista_".$idLista,$curLista->GetProp("denominazione"),array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive"),false);
                else $wnd->AddTextField("lista_".$idLista,$curLista->GetProp("denominazione"),array("required"=>true,"gravity"=>1, "validateFunction"=>"IsPositive"));
                $curNumRow++;
            }
           
            $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_BottomNote",array("type"=>"clean","template"=>"<span style='font-size: smaller'>*Inserire solo il numero dei voti validi.</span>")));
            $wnd->EnableCloseWndOnSuccessfulSave();
            $wnd->enableRefreshOnSuccessfulSave();
            $wnd->SetSaveTask("Update_OC_ComuneRisultatiListe");

            return $wnd;    
        }
        else
        {
            $wnd = new AA_GenericWindowTemplate($id,"Modifica voti lite circoscrizionali", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template($id."_Fake",array("template"=>"Modifica risultati disabilitata.")));

            return $wnd;
        }
    }

    //Template dlg risutati user
    public function Template_GetSierComuneRisultatiViewDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_WND_RISULTATI_COMUNALI;
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Gestione risultati", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Gestione risultati", $this->id);

        $wnd = new AA_GenericWindowTemplate($id, "Gestione risultati comune di ".$comune->GetProp("denominazione"), $this->id);

        $layout=$this->Template_GetSierComuneRisultatiViewLayout($object,$comune,$id);
        $wnd->AddView($layout);
        return $wnd;
    }
 
    //Template dlg affluenza user
    public function Template_GetSierComuneAffluenzaViewDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_WND_AFFLUENZA_COMUNALE;
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Gestione affluenza", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Gestione affluenza", $this->id);


        $wnd = new AA_GenericWindowTemplate($id, "Gestione affluenza comune di ".$comune->GetProp("denominazione"), $this->id);

        $layout=$this->Template_GetSierComuneAffluenzaViewLayout($object,$comune,$id);
        $wnd->AddView($layout);
        return $wnd;
    }

    //Template layout affluenza
    public function Template_GetSierComuneAffluenzaViewLayout($object=null,$comune=null,$id="")
    {
        if(!$object) $object=new AA_Sier($_REQUEST['id']);
        if(!$object->isValid())
        {
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
            $layout->AddRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Errore nel recupero dei dati.</span></div>")));
            return $layout;
        }

        if(!$comune) $comune = $object->GetComune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
            $layout->AddRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Errore nel recupero dei dati del comune.</span></div>")));
            return $layout;
        }

        $id.="_".static::AA_UI_LAYOUT_AFFLUENZA_COMUNALE;
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));

        //nuovo
        $modify_btn=new AA_JSON_Template_Generic($id."_AddNewAffluenza_btn",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-account-plus",
             "label"=>"Aggiungi",
             "css"=>"webix_primary",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Aggiungi nuovo dato sull'affluenza",
             "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierComuneAffluenzaAddNewDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},'".$this->id."')"
        ));
        $toolbar->AddElement($modify_btn);
        
        $layout->addRow($toolbar);

        $columns=array(
            array("id"=>"giornata","header"=>array("<div style='text-align: center'>Giornata</div>",array("content"=>"textFilter")),"width"=>150, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"ore_12","header"=>array("<div style='text-align: center'>Votanti ore 12</div>",array("content"=>"textFilter")),"fillspace"=>90, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"ore_19","header"=>array("<div style='text-align: center'>Votanti ore 19</div>",array("content"=>"textFilter")),"fillspace"=>90, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"ore_22","header"=>array("<div style='text-align: center'>Votanti ore 22</div>",array("content"=>"textFilter")),"fillspace"=>90, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"ops","header"=>"<div style='text-align: center'>Operazioni</div>","width"=>120, "css"=>array("text-align"=>"center"))
        );

        $data=array();
        $affluenza=$comune->GetAffluenza(true);
        if(sizeof($affluenza) > 0)
        {
            foreach($affluenza as $giornata=>$curAffluenza)
            {
                AA_Log::Log(__METHOD__." - ".print_r($curAffluenza,true),100);
                {
                    $modify_op='AA_MainApp.utils.callHandler("dlg", {task:"GetSierComuneAffluenzaModifyDlg",postParams: {id: '.$object->GetId().',id_comune:'.$comune->GetProp('id').', giornata: "'.strtolower($giornata).'",refresh: 1,refresh_obj_id:"'.$id.'"}},"'.$this->id.'");';
                    $trash_op='AA_MainApp.utils.callHandler("dlg", {task:"GetSierComuneAffluenzaTrashDlg",postParams: {id: '.$object->GetId().',id_comune:'.$comune->GetProp('id').', giornata: "'.strtolower($giornata).'",refresh: 1,refresh_obj_id:"'.$id.'"}},"'.$this->id.'");';
                    $ops="<div class='AA_DataTable_Ops'><span>&nbsp;</span><a class='AA_DataTable_Ops_Button' title='Modifica dato' onClick='".$modify_op."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina dato' onClick='".$trash_op."'><span class='mdi mdi-trash-can'></span></a><span>&nbsp;</span></div>";
                }
                $data[]=array("id"=>$giornata,"ops"=>$ops, "giornata"=>$giornata,"ore_12"=>$curAffluenza['ore_12'],"ore_19"=>$curAffluenza['ore_19'],"ore_22"=>$curAffluenza['ore_22']);
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
            $table=new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Non sono presenti dati.</span></div>"));
        }

        $layout->AddRow($table);
        return $layout;
    }

    //Template dlg modify user
    public function Template_GetSierComuneOperatoriViewDlg($object=null,$comune=null)
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_WND_OPERATORI_COMUNALI;
        if(!($object instanceof AA_Sier)) return new AA_GenericWindowTemplate($id, "Gestione operatori comunali", $this->id);
        if(!($comune instanceof AA_SierComune)) return new AA_GenericWindowTemplate($id, "Gestione operatori comunali", $this->id);


        $wnd = new AA_GenericWindowTemplate($id, "Gestione operatori comune di ".$comune->GetProp("denominazione"), $this->id);

        $layout=$this->Template_GetSierComuneOperatoriViewLayout($object,$comune,$id);
        $wnd->AddView($layout);
        return $wnd;
    }

    //Template layout operatori comune
    public function Template_GetSierComuneOperatoriViewLayout($object=null,$comune=null,$id="")
    {
        if(!$object) $object=new AA_Sier($_REQUEST['id']);
        if(!$object->isValid())
        {
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
            $layout->AddRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Errore nel recupero della lista degli operatori.</span></div>")));
            return $layout;
        }

        if(!$comune) $comune = $object->GetComune($_REQUEST['id_comune']);
        if(!($comune instanceof AA_SierComune))
        {
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
            $layout->AddRow(new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Errore nel recupero della lista degli operatori.</span></div>")));
            return $layout;
        }

        $id.="_".static::AA_UI_LAYOUT_OPERATORI_COMUNALI;
        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $toolbar=new AA_JSON_Template_Toolbar($id."_Toolbar",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        $filter="";

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
             "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierComuneOperatoriFilterDlg\",postParams: module.getRuntimeValue('" . $id . "','filter_data'), module: \"" . $this->id . "\"},'".$this->id."')"
        ));
        //$toolbar->AddElement($modify_btn);

                 //filtro
        $modify_btn=new AA_JSON_Template_Generic($id."_AddNewOperatore_btn",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-account-plus",
             "label"=>"Aggiungi",
             "css"=>"webix_primary",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Aggiungi nuovo operatore",
             "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSierComuneOperatoriAddNewDlg\", postParams: {id: ".$object->GetId().",id_comune:".$comune->GetProp('id').",refresh: 1,refresh_obj_id:\"$id\"},module: \"" . $this->id . "\"},'".$this->id."')"
         ));
         $toolbar->AddElement($modify_btn);

        
        $layout->addRow($toolbar);

        $columns=array(
            array("id"=>"lastLogin","header"=>array("<div style='text-align: center'>Data Login</div>",array("content"=>"textFilter")),"width"=>150, "sort"=>"text","css"=>array("text-align"=>"center")),
            array("id"=>"denominazione","header"=>array("<div style='text-align: center'>Cognome e nome</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>array("text-align"=>"left"),"sort"=>"text"),
            array("id"=>"cf","header"=>array("<div style='text-align: center'>Codice fiscale</div>",array("content"=>"textFilter")),"fillspace"=>true, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"email","header"=>array("<div style='text-align: center'>Email</div>",array("content"=>"textFilter")),"width"=>300, "css"=>array("text-align"=>"center"),"sort"=>"text"),            
            array("id"=>"ruolo","header"=>array("<div style='text-align: center'>Ruolo</div>",array("content"=>"selectFilter")),"width"=>250, "css"=>array("text-align"=>"center"),"sort"=>"text"),
            array("id"=>"ops","header"=>"<div style='text-align: center'>Operazioni</div>","width"=>120, "css"=>array("text-align"=>"center"))
        );
        
        $utenti=$comune->GetOperatori(true);
        $ruolo=array(1=>"Caricamento dati",2=>"Caricamento rendiconti",3=>"Caricamento dati e rendiconti");
        $data=array();
        if(sizeof($utenti) > 0)
        {
            foreach($utenti as $curUser)
            {
                {
                    $modify_op='AA_MainApp.utils.callHandler("dlg", {task:"GetSierComuneOperatoriModifyDlg",postParams: {id: '.$object->GetId().',id_comune:'.$comune->GetProp('id').', cf: "'.strtolower($curUser['cf']).'",refresh: 1,refresh_obj_id:"'.$id.'"}},"'.$this->id.'");';
                    $trash_op='AA_MainApp.utils.callHandler("dlg", {task:"GetSierComuneOperatoriTrashDlg",postParams: {id: '.$object->GetId().',id_comune:'.$comune->GetProp('id').', cf: "'.strtolower($curUser['cf']).'",refresh: 1,refresh_obj_id:"'.$id.'"}},"'.$this->id.'");';
                    $ops="<div class='AA_DataTable_Ops'><span>&nbsp;</span><a class='AA_DataTable_Ops_Button' title='Modifica operatore' onClick='".$modify_op."'><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina operatore' onClick='".$trash_op."'><span class='mdi mdi-trash-can'></span></a><span>&nbsp;</span></div>";
                }
                $data[]=array("id"=>$curUser['cf'],"ops"=>$ops, "lastLogin"=>$curUser['lastlogin'],"email"=>$curUser['email'],"denominazione"=>$curUser['cognome']." ".$curUser['nome'],"cf"=>strtoupper($curUser['cf']),"ruolo"=>$ruolo[$curUser['ruolo']]);
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
            $table=new AA_JSON_Template_Template($id."_vuoto",array("type"=>"clean","template"=>"<div style='display: flex; align-items: center; justify-content: center; width:100%;height:100%'><span>Non sono presenti operatori.</span></div>"));
        }

        $layout->AddRow($table);
        return $layout;
    }

    //Task object content (da specializzare)
    public function Task_GetObjectContent($task)
    {
        if($_REQUEST['object']==static::AA_UI_PREFIX."_".static::AA_UI_WND_OPERATORI_COMUNALI."_".static::AA_UI_LAYOUT_OPERATORI_COMUNALI)
        {
            $content = array("id" =>static::AA_UI_PREFIX."_".static::AA_UI_WND_OPERATORI_COMUNALI."_".static::AA_UI_LAYOUT_OPERATORI_COMUNALI, "content" => $this->Template_GetSierComuneOperatoriViewLayout(null,null,static::AA_UI_PREFIX."_".static::AA_UI_WND_OPERATORI_COMUNALI)->toArray());
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent(json_encode($content),true);
            return true;
        }

        if($_REQUEST['object']==static::AA_UI_PREFIX."_".static::AA_UI_WND_RISULTATI_COMUNALI."_".static::AA_UI_LAYOUT_RISULTATI_COMUNALI)
        {
            $content = array("id" =>static::AA_UI_PREFIX."_".static::AA_UI_WND_RISULTATI_COMUNALI."_".static::AA_UI_LAYOUT_RISULTATI_COMUNALI, "content" => $this->Template_GetSierComuneRisultatiViewLayout(null,null,static::AA_UI_PREFIX."_".static::AA_UI_WND_RISULTATI_COMUNALI)->toArray());
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent(json_encode($content),true);
            return true;
        }

        if($_REQUEST['object']==static::AA_UI_PREFIX."_".static::AA_UI_WND_AFFLUENZA_COMUNALE."_".static::AA_UI_LAYOUT_AFFLUENZA_COMUNALE)
        {
            $content = array("id" =>static::AA_UI_PREFIX."_".static::AA_UI_WND_AFFLUENZA_COMUNALE."_".static::AA_UI_LAYOUT_AFFLUENZA_COMUNALE, "content" => $this->Template_GetSierComuneAffluenzaViewLayout(null,null,static::AA_UI_PREFIX."_".static::AA_UI_WND_AFFLUENZA_COMUNALE)->toArray());
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent(json_encode($content),true);
            return true;
        }

        if($_REQUEST['object']==static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_COMUNI_BOX)
        {
            //AA_Log::Log(__METHOD__." - object id: ".$_REQUEST['object'],100);

            $content = array("id" =>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_COMUNI_BOX, "content" => $this->TemplateSierDettaglio_Comuni_Tab()->toArray());
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent(json_encode($content),true);
            return true;
        }

        return $this->Task_GetGenericObjectContent($task, $_REQUEST);
    }

    //Task action menù
    public function Task_GetActionMenu($task)
    {
        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $content = "";

        switch ($_REQUEST['section']) {
            case static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_OC_DESKTOP:
                $content = $this->TemplateActionMenu_OC_Desktop();
                break;
            default:
                return parent::Task_GetActionMenu($task);
                break;
        }

        if ($content != "") $sTaskLog .= $content->toBase64();

        $sTaskLog .= "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

     //Template OC login context menu
     public function TemplateActionMenu_OC_Desktop()
     {
         $menu=new AA_JSON_Template_Generic(
             static::AA_UI_PREFIX."_".static::AA_ID_SECTION_OC_DESKTOP."_ActionMenu",array(
             "view"=>"contextmenu",
             "data"=>array(array(
                    "id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_OC_DESKTOP."_ActionMenuItem_Aggiorna",
                    "value"=>"Aggiorna",
                    "icon"=>"mdi mdi-reload",
                    "module_id" => $this->GetId(),
                    "handler"=>"OC_RefreshSection",
                    "handler_params"=>array(static::AA_UI_PREFIX."_".static::AA_UI_SECTION_OC_DESKTOP,true)
                 ))
             ));
         
         return $menu;
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

        //Abilitazione modifica corpo elettorale
        $value="<span class='AA_Label AA_Label_LightGray'>Disabilitato</span>";
        if(($abilitazioni & AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_CORPO_ELETTORALE) > 0)  $value="<span class='AA_Label AA_Label_LightGreen'>Abilitato</span>";
        $campo=new AA_JSON_Template_Template($id."_DatiGenerali",array(
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
        $layout->addCol($campo);
        #--------------------------------------
        
        //Abilitazione caricamento rendicontazione
        $value="<span class='AA_Label AA_Label_LightGray'>Disabilitato</span>";
        if(($abilitazioni & AA_Sier_Const::AA_SIER_FLAG_CARICAMENTO_RENDICONTI) > 0)  $value="<span class='AA_Label AA_Label_LightGreen'>Abilitato</span>";
        $campo=new AA_JSON_Template_Template($id."_Rendiconti",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Rendiconti:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0 !important")
        ));
        $layout->addCol($campo);
        #----------------------------------------

        //Abilitazione esportazione affluenza
        $value="<span class='AA_Label AA_Label_LightGray'>Disabilitato</span>";
        if(($abilitazioni & AA_Sier_Const::AA_SIER_FLAG_EXPORT_AFFLUENZA) > 0)  $value="<span class='AA_Label AA_Label_LightGreen'>Abilitato</span>";
        $campo=new AA_JSON_Template_Template($id."_ExportAffluenza",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity"=>1,
            "data"=>array("title"=>"Esportazione affluenza:","value"=>$value),
            "css"=>array("border-right"=>"1px solid #dadee0 !important")
        ));
        #----------------------------------------
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
    
    protected $sTipo="";
    public function GetTipo($asArray=false)
    {
        if($asArray) return explode(",",$this->sTipo);
        return $this->sTipo;
    }
    public function SetTipo($val="")
    {
        $this->sTipo=$val;
    }
    public function GetTipoDescr($asArray=false)
    {
        $tipi=AA_Sier_Const::GetTipoAllegati();
        
        $result=array();
        $tipo=explode(",",$this->sTipo);

        foreach($tipi as $curTipo=>$desc)
        {
            if(array_search($curTipo,$tipo)!==false) $result[$curTipo]=$desc;
        }

        if($asArray) return $result;
        return implode(",",$result);
    }

    protected $sDestinatari="";
    public function GetDestinatari($asArray=false)
    {
        if($asArray) return explode(",",$this->sDestinatari);
        return $this->sDestinatari;
    }
    public function SetDestinatari($val="")
    {
        if(is_array($val)) $this->sDestinatari=implode(",",$val);
        else $this->sDestinatari=$val;
    }
    public function GetDestinatariDescr($asArray=false)
    {
        $destinatari=AA_Sier_Const::GetDestinatari();
        
        $result=array();
        $destinatario=explode(",",$this->sDestinatari);

        foreach($destinatari as $curDestinatario=>$desc)
        {
            if(array_search($curDestinatario,$destinatario)!==false) $result[$curDestinatario]=$desc;
        }

        if($asArray) return $result;
        return implode(",",$result);
    }

    protected $estremi="";
    public function GetEstremi()
    {
        return $this->estremi;
    }
    public function SetEstremi($val="")
    {
        $this->estremi=substr($val,0,254);
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

    protected $nOrdine=0;
    public function GetOrdine()
    {
        return $this->nOrdine;
    }
    public function SetOrdine($val=0)
    {
        $this->nOrdine=$val;
    }
    
    public function __construct($id=0,$id_sier=0,$estremi="",$url="",$file="",$tipo="",$aggiornamento="",$destinatari="",$ordine=0)
    {
        //AA_Log::Log(__METHOD__." id: $id, id_organismo: $id_organismo, tipo: $tipo, url: $url",100);
        
        $this->id=$id;
        $this->id_sier=$id_sier;
        $this->nOrdine=$ordine;
        $this->url=$url;
        $this->estremi=$estremi;
        $this->sFile=$file;
        $this->sTipo=$tipo;
        $this->sDestinatari=$destinatari;
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