<?php
class AA_SicarAlloggio extends AA_Object_V2
{
    const AA_DBTABLE_DATA = "aa_sicar_data";
    static protected $AA_DBTABLE_OBJECTS="aa_sicar_objects";

    public function __construct($id = 0, $user = null, $bLoadData = true, $bCheckPerms = true)
    {
        $this->sDbDataTable = self::AA_DBTABLE_DATA;

        // Bind proprietà-campi
        $this->SetBind("immobile", "immobile", true);
        $this->SetBind("tipologia_utilizzo", "tipologia_utilizzo", true);
        $this->SetBind("stato_conservazione", "stato_conservazione", true);
        $this->SetBind("anno_ristrutturazione", "anno_ristrutturazione", true);
        $this->SetBind("superficie_utile_abitabile", "superficie_utile_abitabile", true);
        $this->SetBind("superficie_non_residenziale", "superficie_non_residenziale", true);
        $this->SetBind("superficie_parcheggi", "superficie_parcheggi", true);
        $this->SetBind("vani_abitabili", "vani_abitabili", true);
        $this->SetBind("piano", "piano", true);
        $this->SetBind("ascensore", "ascensore", true);
        $this->SetBind("fruibile_dis", "fruibile_dis", true);
        $this->SetBind("note", "note", true);
        
        //da strutturare
        $this->SetBind("gestione", "gestione", true);
        $this->SetBind("proprieta", "proprieta", true);
        $this->SetBind("occupazione", "occupazione", true);
        $this->SetBind("interventi", "interventi", true);

        $this->SetClass("AA_SicarAlloggio");
        $this->EnableRevision(false);
        parent::__construct($id, $user, $bLoadData, $bCheckPerms);
    }

    // Getter e Setter stile AA_SicarImmobile
    public function GetImmobile($bAsObject=true) 
    {
        $immobile=new AA_SicarImmobile();
        if($immobile->Load($this->GetProp("immobile")))
        {
            if($bAsObject) return $immobile;
            else return $immobile->GetDisplayName();
        }
        else
        {
            if($bAsObject) return null;
            return "Immobile non trovato (id: ".$this->GetProp("immobile").")";
        }
    }
    public function SetImmobile($var = 0) { $this->SetProp("immobile", $var); $this->SetChanged(true); return true; }

    public function GetDescrizione() { return $this->GetName(); }
    public function SetDescrizione($var = "") { $this->SetName($var); $this->SetChanged(true); return true; }

    public function GetTipologiaUtilizzo($asText=true) 
    { 
       if(!$asText) return $this->GetProp("tipologia_utilizzo"); 
        
        $tipo=AA_Sicar_Const::GetListaTipologieUtilizzoAlloggio(true);
        if(!empty($tipo[$this->GetProp("tipologia_utilizzo")])) return $tipo[$this->GetProp("tipologia_utilizzo")];
        else return "n.d."; 
    }
    public function SetTipologiaUtilizzo($var = "") { $this->SetProp("tipologia_utilizzo", $var); $this->SetChanged(true); return true; }

    public function GetStatoConservazione($asText=true) 
    {
        if(!$asText) return $this->GetProp("stato_conservazione"); 
        
        $stati_conservazione=AA_Sicar_Const::GetListaStatiConservazioneAlloggio(true);
        if(!empty($stati_conservazione[$this->GetProp("stato_conservazione")])) return $stati_conservazione[$this->GetProp("stato_conservazione")];
        else return "n.d."; 
         
    }
    public function SetStatoConservazione($var = "") 
    { 
        $this->SetProp("stato_conservazione", $var); 
        $this->SetChanged(true); 
        return true; 
    }

    public function GetAnnoRistrutturazione() { return $this->GetProp("anno_ristrutturazione"); }
    public function SetAnnoRistrutturazione($var = 0) { $this->SetProp("anno_ristrutturazione", $var); $this->SetChanged(true); return true; }

    public function GetCondominioMisto() 
    { 
        $immobile=$this->GetImmobile();
        if($immobile) 
        {
            $attributi=$immobile->GetAttributi();
            if(!empty($attributi['condominio_misto'])) return 1;
        }
        else return 0; 
    }

    public function GetSuperficieNetta() { return AA_Utils::number_format($this->GetProp("superficie_netta"),2,",","."); }
    public function SetSuperficieNetta($var = 0) 
    {
        $var=str_replace(",",".",str_replace(".","",$var));
        $this->SetProp("superficie_netta", $var); 
        $this->SetChanged(true); 
        return true; 
    }

    public function GetSuperficieNonResidenziale() { return AA_Utils::number_format($this->GetProp("superficie_non_residenziale"),2,",","."); }
    public function SetSuperficieNonResidenziale($var = 0) 
    {
        $var=str_replace(",",".",str_replace(".","",$var));
        $this->SetProp("superficie_non_residenziale", $var); 
        $this->SetChanged(true); 
        return true; 
    }

    public function GetSuperficieParcheggi() { return AA_Utils::number_format($this->GetProp("superficie_parcheggi"),2,",","."); }
    public function SetSuperficieParcheggi($var = 0) 
    {
        $var=str_replace(",",".",str_replace(".","",$var));
        $this->SetProp("superficie_parcheggi", $var); 
        $this->SetChanged(true); 
        return true; 
    }

    public function GetVaniAbitabili() { return AA_Utils::number_format($this->GetProp("vani_abitabili"),0,",","."); }          
    public function SetVaniAbitabili($var = 0) 
    {
        $var=str_replace(",",".",str_replace(".","",$var));
        $this->SetProp("vani_abitabili", intVal($var)); 
        $this->SetChanged(true); 
        return true; 
    }

    public function GetSuperficieUtileAbitabile() { return AA_Utils::number_format($this->GetProp("superficie_utile_abitabile"),2,",","."); }
    public function SetSuperficieUtileAbitabile($var = 0) 
    {
        $var=str_replace(",",".",str_replace(".","",$var));
        $this->SetProp("superficie_utile_abitabile", $var); 
        $this->SetChanged(true); 
        return true; 
    }

    public function GetPiano() { return $this->GetProp("piano"); }
    public function SetPiano($var = 0) { $this->SetProp("piano", $var); $this->SetChanged(true); return true; }

    public function GetAscensore() { return $this->GetProp("ascensore"); }
    public function SetAscensore($var = false) { $this->SetProp("ascensore", $var); $this->SetChanged(true); return true; }

    public function GetFruibileDis($bNumeric=false) 
    { 
        if($bNumeric) return $this->GetProp("fruibile_dis");
        else
        {
            $lista=AA_Sicar_Const::GetListaFruibilitaDisabile();
            foreach($lista as $val)
            {
                if($val['id']==$this->GetProp("fruibile_dis")) return $val['value'];
            }

            return "n.d.";
        }
    }
    public function SetFruibileDis($var = false) { $this->SetProp("fruibile_dis", $var); $this->SetChanged(true); return true; }

    public function GetGestione($bAsObject=true) 
    { 
        if(!$bAsObject) return $this->GetProp("gestione");
        
        $val=json_decode($this->aProps['gestione'],true);
        if(is_array($val)) return $val;
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

    public function GetProprietario()
    {
        $proprieta=$this->GetProprieta();
        if(empty($proprieta)) return null;

        $proprietario=new AA_SicarEnte();
        if($proprietario->Load(current($proprieta))) return $proprietario;
        else return null;
    }

    public function GetGestioneDal()
    {
        $gestione=$this->GetGestione();
        if(empty($gestione)) return "";
        
        return array_key_first($gestione);
    }

    public function SetGestione($var = array()) { $this->SetProp("gestione", $var); $this->SetChanged(true); return true; }

    public function GetProprieta($bAsObject=true) 
    { 
        if(!$bAsObject) return $this->GetProp("proprieta");
        
        $val=json_decode($this->aProps['proprieta'],true);
        if(is_array($val)) return $val;
        else return array();
    }

    public function SetProprieta($var = array()) { $this->SetProp("proprieta", $var); $this->SetChanged(true); return true; }

    //restituisce la data iniziale di proprieta' dell'ultimo proprietario impostato
    public function GetProprietaDal()
    {
        $proprieta=$this->GetProprieta();
        if(empty($proprieta)) return "";
        
        return array_key_first($proprieta);
    }

    public function SetOccupazione($var = null) 
    { 
        if(is_array($var)) $var=json_encode($var);
        $this->SetProp("occupazione", $var); 
        $this->SetChanged(true); 
        return true; 
    }

    public function SetInterventi($var = null) 
    { 
        if(is_array($var)) $var=json_encode($var);
        $this->SetProp("interventi", $var); 
        $this->SetChanged(true); 
        return true; 
    }
    public function GetNote() { return $this->GetProp("note"); }
    public function SetNote($var = "") { $this->SetProp("note", $var); $this->SetChanged(true); return true; }

    public function Update($user = null, $bSaveData = true,$logMsg="Aggiornamento dati generali alloggio")
    {
        return parent::Update($user, $bSaveData, $logMsg);
    }

    public function GetOccupazione($bAsObject=true) 
    { 
        if(!$bAsObject) return $this->GetProp("occupazione");
        
        $val=json_decode($this->aProps['occupazione'],true);
        if(is_array($val)) return $val;
        else return array();
    }

    public function GetInterventi($bAsObject=true) 
    { 
       if(!$bAsObject) return $this->GetProp("interventi");
        
        $val=json_decode($this->aProps['interventi'],true);
        if(is_array($val)) return $val;
        else return array();
    }

    // Validazione campi obbligatori e di tipo
    public function Validate()
    {
        $errors = array();

        if (empty($this->GetImmobile())) {
            $errors[] = "L'immobile è obbligatorio";
        }
        if (empty($this->GetDescrizione())) {
            $errors[] = "La descrizione è obbligatoria";
        }
        if (empty($this->GetTipologiaUtilizzo())) {
            $errors[] = "La tipologia di utilizzo è obbligatoria";
        }
        if (intval($this->GetAnnoRistrutturazione()) < 1900 || intval($this->GetAnnoRistrutturazione()) > intval(date("Y"))) {
            $errors[] = "Anno di ristrutturazione non valido";
        }
        
        if (!is_numeric($this->GetProp("superficie_non_residenziale")) || floatval($this->GetProp("superficie_non_residenziale")) < 0) {
            $errors[] = "Superficie non residenziale non valida (".$this->GetProp("superficie_non_residenziale").")";
        }
        if (!is_numeric($this->GetProp("superficie_parcheggi")) || floatval($this->GetProp("superficie_parcheggi")) < 0) {
            $errors[] = "Superficie parcheggi non valida (".$this->GetProp("superficie_parcheggi").")";
        }
        
        if (!is_numeric($this->GetProp("vani_abitabili")) || floatval($this->GetProp("vani_abitabili")) < 0) {
            $errors[] = "Vani abitabili non valida (".$this->GetProp("vani_abitabili").")";
        }

        if (!is_numeric($this->GetProp('superficie_utile_abitabile')) || floatval($this->GetProp('superficie_utile_abitabile')) <= 0) {
            $errors[] = "Superficie utile abitabile non valida (".$this->GetSuperficieUtileAbitabile().")";
        }
        if (empty($this->GetPiano()) && $this->GetPiano() !== 0 && $this->GetPiano() !== "0") {
            $errors[] = "Il piano è obbligatorio";
        }

        return $errors;
    }

    // Rappresentazione testuale
    public function GetDisplayName()
    {
        $display = $this->GetName();
        if (!empty($this->GetPiano())) {
            $display .= " - Piano " . $this->GetPiano();
        }
        return $display;
    }

    // Supporto CSV
    protected function CsvDataHeader($separator = "|")
    {
        return $separator . "immobile" . $separator . "descrizione" . $separator . "tipologia_utilizzo" .
               $separator . "stato_conservazione" . $separator . "anno_ristrutturazione" .
               $separator . "condominio_misto" . $separator . "superficie_netta" .
               $separator . "superficie_utile_abitabile" . $separator . "piano" .
               $separator . "ascensore" . $separator . "fruibile_dis" . $separator . "note";
    }

    protected function CsvData($separator = "|")
    {
        return $separator . $this->GetImmobile() . $separator . str_replace("\n", ' ', $this->GetDescrizione()) .
               $separator . $this->GetTipologiaUtilizzo() . $separator . $this->GetStatoConservazione() .
               $separator . $this->GetAnnoRistrutturazione() . $separator . ($this->GetCondominioMisto() ? 1 : 0) .
               $separator . $this->GetSuperficieNetta() . $separator . $this->GetSuperficieUtileAbitabile() .
               $separator . $this->GetPiano() . $separator . ($this->GetAscensore() ? 1 : 0) .
               $separator . ($this->GetFruibileDis() ? 1 : 0) . $separator . str_replace("\n", ' ', $this->GetNote());
    }

    //restituisce il nucleo assegnatario se presente
    public function GetNucleoAssegnatario($dal="",$bAsObject=true)
    {
        $occupazione=$this->GetOccupazione();
        if(empty($occupazione)) 
        {
            if($bAsObject) return null;
            return 0;
        }

        if($dal=="") $lastOccupazione=current($occupazione);
        else 
        {
            if(!isset($occupazione[$dal]))
            {
                if($bAsObject) return null;
                else return 0;
            }
            $lastOccupazione=$occupazione[$dal];
        }

        //AA_Log::Log(__METHOD__." - occupazione: ".print_r($lastOccupazione,true),100);

        if(!empty($lastOccupazione['occupazione_id_nucleo']))
        {
            $nucleo=new AA_SicarNucleo();
            if($nucleo->Load($lastOccupazione['occupazione_id_nucleo']))
            {
                if($bAsObject) return $nucleo;
                else return $lastOccupazione['occupazione_id_nucleo'];
            }
            else 
            {
                if($bAsObject) return null;
                else return 0;
            }
        }

        if($$bAsObject)
        {
            return null;
        }
        else
        {
            return 0;
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
        $params['class']=__CLASS__;
        //----------------------------------

        return parent::Search($params,$user);
    }


    // Parse parametri stile AA_SicarImmobile
    public function Parse($params = array(), $bOnlyData = false)
    {
        return parent::Parse($params,$bOnlyData);
    }

    // Permessi utente
    public function GetUserCaps($user = null)
    {
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser()) {
                $user = AA_User::GetCurrentUser();
            }
        } else {
            $user = AA_User::GetCurrentUser();
        }

        $perms = parent::GetUserCaps($user);

        if (($perms & AA_Const::AA_PERMS_WRITE) > 0 && !$user->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $perms = AA_Const::AA_PERMS_READ;
        }
        if (($perms & AA_Const::AA_PERMS_WRITE) > 0 && $user->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $perms = AA_Const::AA_PERMS_ALL;
        }

        return $perms;
    }

    /**
     * Aggiunge un nuovo alloggio
     * @param AA_SicarAlloggio $object Oggetto alloggio da aggiungere
     * @param AA_User $user Utente che esegue l'operazione
     * @param bool $bSaveData Se salvare i dati
     * @return bool|int ID dell'alloggio creato o false in caso di errore
     */
    static public function AddNew($object = null, $user = null, $bSaveData = true)
    {
        // Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser()) {
                $user = AA_User::GetCurrentUser();
            }
        } else {
            $user = AA_User::GetCurrentUser();
        }

        // Controlli locali
        $bStandardCheck = false; // disabilita controlli standard
        $bSaveData = true; // abilita salvataggio dati

        // Verifica permessi modulo SICAR
        if (!$user->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUserName() . " non ha i permessi per inserire nuovi alloggi.", 100);
            return false;
        }

        // Validità oggetto
        if (!($object instanceof AA_SicarAlloggio)) {
            AA_Log::Log(__METHOD__ . " - Errore: oggetto non valido (" . print_r($object, true) . ").", 100);
            return false;
        }

        // Validazione
        $errors = $object->Validate();
        if (!empty($errors)) {
            AA_Log::Log(__METHOD__ . " - Errori di validazione: " . implode(", ", $errors), 100);
            return false;
        }

        // Reset ID e marcatura validità
        $object->nId = 0;
        $object->bValid = true;

        // Salvataggio tramite classe base
        return parent::AddNew($object, $user, $bSaveData);
    }

    //Restituisce gli alloggi associati ad un immobile avente identificativo indicato
    static public function GetAssociatedOfImmobile($id=0,$bBozze=false,$bCestinate=false,$bAsObjects=true,$user=null)
    {
        if($id<=0) return false;

        $db= new AA_Database();
        $query="SELECT DISTINCT ".static::$AA_DBTABLE_OBJECTS.".id FROM ".static::$AA_DBTABLE_OBJECTS." INNER JOIN ".static::AA_DBTABLE_DATA." on ".static::$AA_DBTABLE_OBJECTS.".id_data=".static::AA_DBTABLE_DATA.".id WHERE ".static::AA_DBTABLE_DATA.".immobile='".addslashes($id)."'";
        
        if(!$bBozze)
        {
            $query.=" AND ".static::$AA_DBTABLE_OBJECTS.".status & ".AA_Const::AA_STATUS_BOZZA." = 0";
        }
        if(!$bCestinate)
        {
            $query.=" AND ".static::$AA_DBTABLE_OBJECTS.".status & ".AA_Const::AA_STATUS_CESTINATA." = 0";
        }

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__ . " - Errori nella query: " . $db->GetErrorMessage(), 100);
            return false;
        }

        if($db->GetAffectedRows()==0) return array();

        $rs=$db->GetResultSet();

        if(!$bAsObjects) return $rs;
        
        $return = array();
        foreach($rs as $curId)
        {
            $return[$curId['id']] = new AA_SicarAlloggio($curId['id'],$user);
        }

        return $return;
    }
}
