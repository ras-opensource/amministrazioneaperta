<?php
class AA_SicarImmobile extends AA_GenericParsableDbObject
{
    // Tabella dati per gli immobili
    static protected $dbDataTable="aa_sicar_immobili";
    static protected $ObjectClass=__CLASS__;
    
    // Costruttore
    public function __construct($params = array())
    {
        
        // Imposta i binding tra proprietà e campi database
        $this->aProps['descrizione']="Nuovo immobile";
        $this->aProps['tipologia']=0;
        $this->aProps['comune']="";
        $this->aProps['ubicazione']="";
        $this->aProps['indirizzo']="";
        $this->aProps['catasto']="";
        $this->aProps['zona_urbanistica']="";
        $this->aProps['geolocalizzazione']="";
        $this->aProps['piani']=1;
        $this->aProps['attributi']="";
        $this->aProps['interventi']="";
        
        $this->aProps['note']="";
        
        //template view props
        $this->aTemplateViewProps['descrizione']=array("label"=>"Descrizione","type"=>"text","maxlength"=>AA_Sicar_Const::MAX_DESCRIZIONE_LENGTH,"required"=>true,"bottomLabel"=>"Inserisci la descrizione dell'immobile","visible"=>true);
        $this->aTemplateViewProps['tipologia']=array("label"=>"Tipologia","type"=>"text","required"=>true,"function"=>"GetTipologia","bottomLabel"=>"Scegli la tipologia dell'immobile","visible"=>true);
        $this->aTemplateViewProps['comune']=array("label"=>"Comune","type"=>"text","required"=>true,"bottomLabel"=>"Comune dove e' situato l'immobile","function"=>"GetComune","visible"=>true);
        $this->aTemplateViewProps['ubicazione']=array("label"=>"Ubicazione","type"=>"text","required"=>true,"bottomLabel"=>"Ubicazione dell'immobile all'interno del territorio comunale","function"=>"GetUbicazione","visible"=>true);
        $this->aTemplateViewProps['indirizzo']=array("label"=>"Indirizzo","type"=>"text","required"=>true,"bottomLabel"=>"Indirizzo dell'immobile","visible"=>true);
        $this->aTemplateViewProps['catasto']=array("label"=>"Dati catastali","type"=>"text","required"=>true,"function"=>"GetTemplateViewCatasto","visible"=>true);
        $this->aTemplateViewProps['zona_urbanistica']=array("label"=>"Zona urbanistica","type"=>"text","required"=>true,"bottomLabel"=>"Zona urbanistica dell'immobile","function"=>"GetZonaUrbanistica","visible"=>true);
        $this->aTemplateViewProps['piani']=array("label"=>"Piani","type"=>"text","required"=>true,"bottomLabel"=>"Numero di piani dell'immobile","visible"=>true);
        $this->aTemplateViewProps['attributi']=array("label"=>"Caratteristiche","type"=>"text","required"=>true,"visible"=>true,"function"=>"GetTemplateViewAttributi");
        $this->aTemplateViewProps['gestore']=array("label"=>"Ente gestore","type"=>"text","required"=>true,"visible"=>true,"function"=>"GetTemplateViewGestore");
        $this->aTemplateViewProps['note']=array("label"=>"Note","type"=>"textarea","maxlength"=>AA_Sicar_Const::MAX_NOTE_LENGTH,"required"=>false,"bottomLabel"=>"Inserisci eventuali note sull'immobile","visible"=>true);

        $this->aTemplateViewProps['__areas']=array(
            array("descrizione", "descrizione","descrizione"),
            array("tipologia",".","piani"),
            array("comune", "ubicazione", "zona_urbanistica"),
            array("indirizzo", "catasto", "catasto"),
            array("attributi", "gestore","gestore"),
            array("note", "note", "note")
        );
        $this->aTemplateViewProps['__cols']=array("1fr","1fr","1fr");
        $this->aTemplateViewProps['__rows']=array("1fr","1fr","1fr","1fr","2fr","2fr");

        // Chiama il costruttore padre
        parent::__construct($params);
    }

    public function GetTemplateViewCatasto()
    {
        $catasto=$this->GetCatasto();
        if(empty($catasto)) return "n.d.";
        $sezione=array("Fabbricati","Terreni");
        $return="";
        $return.="Sezione: ".$sezione[$catasto['SezioneCatasto']];
        $return.=" - Foglio: ".$catasto['FoglioCatasto'];
        $return.=" - Mappale: ".$catasto['MappaleCatasto'];
        $return.=" - Particella: ".$catasto['ParticellaCatasto'];
        $return.=" - Subalterno: ".$catasto['Subalterno'];
        
        return $return;
    }
    public function GetTemplateView($bRefresh=false)
    {
        return parent::GetTemplateView($bRefresh);
    }

    //restituisce il dettaglio delle caratteristiche dell'immobile
    public function GetTemplateViewAttributi()
    {
        $attributi=$this->GetAttributi();
        if(sizeof($attributi) > 0)
        {
            $content="<div style='display: flex, flex-direction: column;'>";
            //condominio misto
            if(isset($attributi['condominio_misto'])) 
            {
                $content.="<div style='display: flex'><div style='min-width:50%; font-weight: 300'>Condominio misto:</div>";
                if($attributi['condominio_misto']) $content.="<div style='width:100%;'>Si</div>";
                else $content.="<div style='width:100%;'>No</div>";
                $content.="</div>";
            }
            else
            {
                $content.="<div style='display: flex'><div style='min-width:30%; font-weight: 300'>Condominio misto</div><div style='width:100%;'>No</div></div>";
            }

            $content.="</div>";
            //AA_Log::Log(__METHOD__." - content: ".$content,100);

            return $content;
        }
        else
        {
            return("Non ci sono caratteristiche particolari definite.");
        }
    }

    public function GetTemplateViewGestore()
    {
        $gestore=$this->GetGestore();
        if($gestore)
        {
            $content="<div style='display: flex'>";
            $content.=$gestore->GetDenominazione();
            $content.=", dal: ".$this->GetGestioneDal();
            $content.="</div>";
            //AA_Log::Log(__METHOD__." - content: ".$content,100);

            return $content;
        }
        else
        {
            return("Nessuno.");
        }
    }
        
    //lista degli immobili
    public static function GetListaImmobili($comune = "")
    {
        $db = new AA_Database();
        $query = "SELECT * FROM aa_sicar_immobili ORDER BY descrizione";
        if(!empty($comune)) {
            $query .= " WHERE comune = '".addslashes($comune)."'";
        }

        $return = array();

        if($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach($rs as $row) {
                $return[] = new AA_SicarImmobile($row);
            }
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile ottenere la lista degli immobili. - ".$db->GetErrorMessage(), 100);
        }

        return $return;
    }

    //lista alloggi associati
    public function GetAlloggi($bBozze=false,$bCestinate=false,$bAsObject=true,$user=null)
    {
        return AA_SicarAlloggio::GetAssociatedOfImmobile($this->GetProp("id"),$bBozze,$bCestinate,$bAsObject,$user);
    }

    // Metodi Getter e Setter per le proprietà
    
    // Descrizione
    public function GetDescrizione()
    {
        return $this->GetProp("descrizione");
    }
    
    public function SetDescrizione($var = "")
    {
        $this->SetProp("descrizione", $var);
        return true;
    }

    // Tipologia
    public function GetTipologia($bAsText=true)
    {
        if(!$bAsText) return $this->GetProp("tipologia"); 
        
        $tipo=AA_Sicar_Const::GetListaTipologie(true);
        if(!empty($tipo[$this->GetProp("tipologia")])) return $tipo[$this->GetProp("tipologia")];
        else return "n.d.";
        
    }
    
    public function SetTipologia($var = 0)
    {
        $this->SetProp("tipologia", $var);
        return true;
    }
    
    // Comune
    public function GetComune($bDescr=true)
    {
        if($bDescr) return AA_Sicar_Const::GetComuneDescrFromCodiceIstat($this->GetProp("comune"));
        return $this->GetProp("comune");
    }

    public function SetComune($var = "")
    {
        $this->SetProp("comune", $var);
        return true;
    }
    
    //Geolocalizzazione
    public function GetGeolocalizzazione()
    {
        return $this->GetProp("geolocalizzazione");
    }
    
    public function SetGeolocalizzazione($var = "")
    {
        $this->SetProp("geolocalizzazione", $var);
        return true;
    }
    
    // Ubicazione
    public function GetUbicazione($bDescr=true)
    {
        if($bDescr)
        {
            $ubic=AA_Sicar_Const::GetListaUbicazioni(true);
            if(!empty($ubic[$this->GetProp("ubicazione")])) return $ubic[$this->GetProp("ubicazione")];
            else return "n.d.";
        }

        return $this->GetProp("ubicazione");
    }
    
    public function SetUbicazione($var = 0)
    {
        $this->SetProp("ubicazione", $var);
        return true;
    }
    
    // Indirizzo
    public function GetIndirizzo()
    {
        return $this->GetProp("indirizzo");
    }
    
    public function SetIndirizzo($var = "")
    {
        $this->SetProp("indirizzo", $var);
        return true;
    }
    
    //numero alloggi
    public function GetNumeroAlloggiTot()
    {
        $attirbuti=$this->GetAttributi();
        if(isset($attirbuti['alloggi'])) return $attirbuti['alloggi'];
        else return 0;
    }

    //gestione
    public function GetGestione($bAsObject=true) 
    { 
         if(!$bAsObject) return $this->GetProp("attributi");
        
        $val=json_decode($this->aProps['attributi'],true);
        if(is_array($val) && !empty($val['gestione'])) return $val['gestione'];
        else return array();
    }

    public function GetGestore()
    {
        $gestione=$this->GetGestione();
        if(empty($gestione)) return null;

        $gestore=new AA_SicarEnte();
        if($gestore->Load(current($gestione))) return $gestore;
        else return null;
    }

    public function GetGestioneDal()
    {
        $gestione=$this->GetGestione();
        if(empty($gestione)) return "";
        
        return array_key_first($gestione);
    }

    public function SetGestione($var = array()) 
    { 
        if(is_array($var))
        {
            $attributi=$this->GetAttributi();
            $attributi['gestione']=$var;
            
            $this->SetProp("attributi",json_encode($attributi));
        }
    }

    // Catasto
    public function GetCatasto($bAsObject=true)
    {
        if(!$bAsObject) return $this->GetProp("catasto");
        
        $val=json_decode($this->aProps['catasto'],true);
        if(is_array($val)) return $val;
        else return array();
    }
    
    public function SetCatasto($var = "")
    {
        if(is_array($var)) $this->SetProp("catasto", json_encode($var));
        else $this->SetProp("catasto",$var);
        return true;
    }

    // Attributi
    public function GetAttributi($bAsObject=true)
    {
        if(!$bAsObject) return $this->GetProp("attributi");
        
        $val=json_decode($this->aProps['attributi'],true);
        if(is_array($val)) return $val;
        else return array();
    }

    // interventi
    public function GetInterventi($bAsObject=true)
    {
        if(!$bAsObject) return $this->GetProp("interventi");
        
        $val=json_decode($this->aProps['interventi'],true);
        if(is_array($val)) return $val;
        else return array();
    }

    //condominio misto
    public function IsCondominioMisto()
    {
        $attributi=$this->GetAttributi();
        if(isset($attributi['condominio_misto'])) return $attributi['condominio_misto'];
        else return false;
    }
    
    public function SetAttributi($var = "")
    {
        if(is_array($var)) $this->SetProp("attributi", json_encode($var));
        else $this->SetProp("attributi",$var);
        return true;
    }

    public function SetInterventi($var = "")
    {
        if(is_array($var)) $this->SetProp("interventi", json_encode($var));
        else $this->SetProp("interventi",$var);
        return true;
    }
    
    // Zona Urbanistica
    public function GetZonaUrbanistica($bDescr=true)
    {
        if($bDescr)
        {
            $zone=AA_Sicar_Const::GetListaZoneUrbanistiche();
            foreach($zone as $z)
            {
                if($z['id']==$this->GetProp("zona_urbanistica")) return $z['value'];
            }
            return "n.d.";
        }
        return $this->GetProp("zona_urbanistica");
    }
    
    public function SetZonaUrbanistica($var = "")
    {
        $this->SetProp("zona_urbanistica", $var);
        return true;
    }
    
    // Piani
    public function GetPiani()
    {
        return $this->GetProp("piani");
    }
    
    public function SetPiani($var = 0)
    {
        $this->SetProp("piani", $var);
        return true;
    }
    
    // Note
    public function GetNote()
    {
        return $this->GetProp("note");
    }
    
    public function SetNote($var = "")
    {
        $this->SetProp("note", $var);
        return true;
    }
    
    // Metodi per la validazione
    public function Validate()
    {
        $errors = array();
        
        // Validazione campi obbligatori
        if (empty($this->GetDescrizione())) {
            $errors[] = "La descrizione è obbligatoria";
        }
        
        if (empty($this->GetTipologia())) {
            $errors[] = "La tipologia è obbligatoria";
        }
        
        if (empty($this->GetComune())) {
            $errors[] = "Il comune è obbligatorio";
        }
        
        if (empty($this->GetUbicazione())) {
            $errors[] = "L'ubicazione è obbligatoria";
        }
        
        if (empty($this->GetIndirizzo())) {
            $errors[] = "L'indirizzo è obbligatorio";
        }
        
        if (empty($this->GetCatasto())) {
            $errors[] = "I dati catastali sono obbligatori";
        }
        
        if (empty($this->GetZonaUrbanistica())) {
            $errors[] = "La zona urbanistica è obbligatoria";
        }
        
        if (empty($this->GetPiani()) || $this->GetPiani() <= 0) {
            $errors[] = "Il numero di piani è obbligatorio e deve essere maggiore di zero";
        }
        
        return $errors;
    }
    
    // Metodo per ottenere una rappresentazione testuale dell'immobile
    public function GetDisplayName()
    {
        $display = strval($this->GetDescrizione());
        if (!empty($this->GetIndirizzo())) {
            $display .= " - " . $this->GetIndirizzo();
        }
        $comune=$this->GetComune();
        if (!empty($comune)) {
            $display .= " (" . $comune . ")";
        }
        return $display;
    }
    
    // Metodo per l'esportazione CSV
    protected function CsvDataHeader($separator = "|")
    {
        return $separator . "tipologia" . $separator . "comune" . $separator . "ubicazione" . 
               $separator . "indirizzo" . $separator . "catasto" . $separator . "zona_urbanistica" . 
               $separator . "piani" . $separator . "note";
    }
    
    protected function CsvData($separator = "|")
    {
        return $separator . $this->GetTipologia() . $separator . $this->GetComune() . 
               $separator . $this->GetUbicazione() . $separator . str_replace("\n", ' ', $this->GetIndirizzo()) . 
               $separator . $this->GetCatasto() . $separator . $this->GetZonaUrbanistica() . 
               $separator . $this->GetPiani() . $separator . str_replace("\n", ' ', $this->GetNote());
    }
    
    /**
     * Metodo per popolare l'oggetto dai parametri
     * @param array $params Parametri da parsare
     */
    public function Parse($params = array())
    {
        // Chiama il metodo padre per le proprietà base
        return parent::Parse($params);
    }
    
    /**
     * Funzione per verificare i permessi dell'utente
     * @param AA_User $user Utente da verificare
     * @return int Permessi dell'utente
     */
    public function GetUserCaps($user = null)
    {
        // Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser()) {
                $user = AA_User::GetCurrentUser();
            }
        } else {
            $user = AA_User::GetCurrentUser();
        }
        
        $perms = AA_Const::AA_PERMS_READ;
        
        // Se l'utente ha il flag e può modificare l'immobile allora può fare tutto
        if ($user->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $perms = AA_Const::AA_PERMS_ALL;
        }
        
        return $perms;
    }
    
    /**
     * Funzione statica per l'aggiunta di nuovi immobili
    * @param array $params dati dell'immobile
     * @return bool|int ID dell'immobile creato o false in caso di errore
     */
    static public function AddNew($params, $user = null)
    {
        $object = new AA_SicarImmobile($params);

        $validate=$object->Validate();
        if(sizeof($validate)>0)
        {
            AA_Log::Log(__METHOD__." - Sono stati trovati i seguenti errori: ".print_r($validate,true),100);
            return false;
        }

        return $object->Sync($user);
    }

    public function Sync($user = null)
    {
        if($user instanceof AA_User)
        {
            if(!$user->isCurrentUser())
            {
                $user = AA_User::GetCurrentUser();
            }
        }
        else
        {
            $user = AA_User::GetCurrentUser();
        }
        
        if($this->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE==0) 
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: l'utente corrente: " . $user->GetName() . " non ha i permessi per inserire nuovi immobili.", 100);
            return false;
        }

        if(!empty($this->Validate())) 
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: i dati dell'immobile non sono validi.", 100);
            return false;
        }

        return parent::Sync();
    }
}

