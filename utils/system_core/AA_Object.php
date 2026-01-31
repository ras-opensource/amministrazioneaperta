<?php
class AA_Object
{
    //Identificativo
    protected $nID = 0;
    protected function SetId($val = 0)
    {
        if ($val >= 0) {
            $this->nID = $val;
        }
    }

    //Tipo di oggetto
    protected $nType = 0;
    public function GetType()
    {
        return $this->nType;
    }
    protected function SetType($val = 0)
    {
        $this->nType = $val;
    }

    //Oggetto padre
    private $oParent = null;
    public function SetParent($parent = null)
    {
        $this->oParent = null;
        if ($parent instanceof AA_Object) $this->oParent = $parent;
    }
    public function GetParent()
    {
        return $this->oParent;
    }

    //Abilita l'aggiornamento del padre quando viene aggiornato il figlio
    private $bEnableUpdateParent = false;
    public function EnableUpdateParent($bEnable = true)
    {
        $this->bEnableUpdateParent = $bEnable;
    }
    public function IsParentUpdateEnabled()
    {
        return $this->bEnableUpdateParent;
    }

    //Abilita il controllo dei permessi del parente invece di quello locale
    private $bEnableParentPermsCheck = false;
    public function EnableParentPermsCheck($bEnable = true)
    {
        if ($this->oParent instanceof AA_Object && $bEnable) {
            $this->bEnableParentPermsCheck = true;
            //AA_Log::Log("Abilito il controllo dei permessi del genitore.", 100, false,true);
        } else $this->bEnableParentPermsCheck = false;
    }
    public function IsParentPermsCheckEnabled()
    {
        return $this->bEnableParentPermsCheck;
    }

    //Disabilita il controllo dei permessi locale
    private $bDisableLocalPermsCheck = false;
    public function DisableLocalPermsCheck($bDisable = true)
    {
        $this->bDisableLocalPermsCheck = $bDisable;
    }
    public function IsLocalPermsCheckDisabled()
    {
        return $this->bDisableLocalPermsCheck;
    }

    //flag di validità
    protected $bValid = false;

    //Timestamp ultimo aggiornamento
    protected $tAggiornamento = "";
    protected function SetAggiornamento($val = "")
    {
        $this->bChanged = true;
        $this->tAggiornamento = $val;
    }
    //Abilita/disabilita l'aggiornamento del campo sul db
    protected $bEnableAggiornamentoDbSync = false;
    protected function EnableAggiornamentoDbSync($bEnable = true)
    {
        $this->bEnableAggiornamentoDbSync = $bEnable;
    }
    public function IsAggiornamentoDbSyncEnabled()
    {
        return $this->bEnableAggiornamentoDbSync;
    }

    //------------------- Log -----------------
    protected $sLog = "";
    protected $bLogEnabled = false;
    public function GetLog($bFormated = true)
    {
        if (!$bFormated) return $this->sLog;

        return new AA_Object_Log($this->sLog);
    }

    //Aggiungi un log
    protected function AddLog($log = "", $actionType = "0", $user = null)
    {
        //Verifica utente valido
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($this->sLog != "") $this->sLog .= "\n";
        $this->sLog .= Date("Y-m-d H:i:s") . "|" . $user->GetUsername() . "|" . $actionType . "|" . $log;
    }

    //resetta il log
    protected function ResetLog()
    {
        $this->sLog = "";
    }
    #-----------------------------------------------------------
    //Struttura
    protected $oStruct = null;
    protected function SetStruct($struct = null)
    {
        if ($struct instanceof AA_Struct) {
            $this->oStruct = $struct;
            $this->SetChanged();
        }
    }

    //Abilita/disabilita la sincronozzazione sul db
    protected $bEnableStructDbSync = false;
    protected function EnableStructDbSync($bEnable = true)
    {
        $this->bEnableStructDbSync = $bEnable;
    }
    public function IsStructDbSyncEnabled()
    {
        return $this->bEnableStructDbSync;
    }

    //Utente
    protected $oUser = null;

    //Abilita/disabilita la sincronozzazione sul db
    protected $bEnableUserDbSync = false;
    protected function EnableUserDbSync($bEnable = true)
    {
        $this->bEnableUserDbSync = $bEnable;
    }
    public function IsUserDbSyncEnabled()
    {
        return $this->bEnableUserDbSync;
    }

    //Stato
    protected $nStatus = 0;
    public function SetStatus($val = 0)
    {
        if (($val & $this->nStatusMask) > 0) {
            $this->nStatus = $val & $this->GetStatusMask();
            $this->SetChanged();
        }
    }

    //Abilita l'aggiornamento dello stato sul db
    protected $bEnableStatusDbSync = false;
    protected function EnableStatusDbSync($bEnable = true)
    {
        $this->bEnableStatusDbSync = $bEnable;
    }
    public function IsStatusDbSyncEnabled()
    {
        return $this->bEnableStatusDbSync;
    }

    //Maschera degli stati possibili
    private $nStatusMask = AA_Const::AA_STATUS_ALL;
    public function SetStatusMask($mask = AA_Const::AA_STATUS_ALL)
    {
        $newMask = $mask & AA_Const::AA_STATUS_ALL;

        $this->nStatusMask = $newMask;
    }
    public function GetStatusMask()
    {
        return $this->nStatusMask;
    }

    //Titolo
    protected $sTitolo = "";
    protected function SetTitolo($val = "")
    {
        $this->sTitolo = $val;
    }

    //Descrizione
    protected $sDescrizione = "";
    protected function SetDescrizione($val = "")
    {
        $this->sDescrizione = $val;
    }

    //tags
    protected $sTags = "";

    //Costruttore di default
    public function __construct($user = null)
    {
        AA_Log::Log(get_class() . "->__construct()");

        if ($user instanceof AA_User && $user->IsCurrentUser()) $this->oUser = $user;
        else $this->oUser = AA_User::GetCurrentUser();
        $this->oStruct = $this->oUser->GetStruct();

        $this->oDbBind = new AA_DbBind();

        $this->nStatusMask = AA_Const::AA_STATUS_BOZZA | AA_Const::AA_STATUS_PUBBLICATA | AA_Const::AA_STATUS_REVISIONATA | AA_Const::AA_STATUS_CESTINATA;
    }

    //Cache dei permessi
    private $aCachePerms = array();
    private $bEnablePermsCaching = true;
    public function EnablePermsCaching($bEnable = true)
    {
        $this->bEnablePermsCaching = $bEnable;
    }
    public function IsPermsCachingEnabled()
    {
        return $this->bEnablePermsCaching;
    }
    public function ClearCache()
    {
        $this->aCachePerms = array();
    }

    //Verifica i livelli di permesso
    public function GetUserCaps($user = null)
    {
        AA_Log::Log(get_class() . "->GetUserCaps($user)");

        $perms = AA_Const::AA_PERMS_NONE;

        //Utente non indicato
        if (!($user instanceof AA_User) || $user == null || !$user->isCurrentUser()) {
            $user = AA_User::GetCurrentUser();
        }

        //Verifica i permessi tramite l'oggetto padre
        if ($this->bEnableParentPermsCheck && $this->oParent instanceof AA_Object) {
            $perms = $this->oParent->GetUserCaps($user);
            //AA_Log::Log("Controllo permessi genitore. permessi: ".$perms, 100,false,true);
        }

        if ($this->IsLocalPermsCheckDisabled()) {
            //AA_Log::Log("Permessi locali disattivati. permessi: ".$perms, 100,false,true);
            return $perms;
        }

        //Permessi super user
        if ($user->IsSuperUser()) return AA_Const::AA_PERMS_ALL;

        //Restituisce i permessi in cache
        if ($this->IsPermsCachingEnabled() && isset($this->aCachePerms[$user->GetId()])) return $this->aCachePerms[$user->GetId()];

        //Stato pubblicata
        if ($this->nStatus & (AA_Const::AA_STATUS_PUBBLICATA + AA_Const::AA_STATUS_CESTINATA + AA_Const::AA_STATUS_REVISIONATA) == AA_Const::AA_STATUS_PUBBLICATA) $perms += AA_Const::AA_PERMS_READ;

        //Utente guest
        if ($user->IsGuest()) {
            //Cache dei permessi
            if ($this->IsPermsCachingEnabled()) $this->aCachePerms[$user->GetId()] = $perms;
            return $perms;
        }

        $sameStruct = false;
        $userStruct = $user->GetStruct();

        //Verifica l'assessorato
        if ($userStruct->GetAssessorato(true) == 0 || $this->oStruct->GetAssessorato(true) == $userStruct->GetAssessorato(true)) {
            //Verifica la direzione
            if ($userStruct->GetDirezione(true) == 0 || $this->oStruct->GetDirezione(true) == $userStruct->GetDirezione(true)) {
                //Verifica il servizio
                if ($userStruct->GetServizio(true) == 0 || $this->oStruct->GetServizio(true) == $userStruct->GetServizio(true)) {
                    $sameStruct = true;
                }
            }
        }

        //Stessa struttura stato bozza
        if ($sameStruct && ($this->nStatus & AA_Const::AA_STATUS_BOZZA) > 0) {
            $perms = AA_Const::AA_PERMS_READ;
            //if($user->GetLevel() == AA_Const::AA_USER_LEVEL_GUEST && $this->nStatus & !AA_Const::AA_STATUS_CESTINATA) $perms+=AA_Const::AA_PERMS_READ;
            if ($user->GetLevel() == AA_Const::AA_USER_LEVEL_OPERATOR) $perms += AA_Const::AA_PERMS_WRITE + AA_Const::AA_PERMS_DELETE;
            if ($user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN) $perms += AA_Const::AA_PERMS_WRITE + AA_Const::AA_PERMS_PUBLISH + AA_Const::AA_PERMS_DELETE;
        }

        //Stessa struttura stato pubblicata
        if ($sameStruct && ($this->nStatus & AA_Const::AA_STATUS_PUBBLICATA) > 0) {
            $perms = AA_Const::AA_PERMS_READ;
            if ($user->GetLevel() == AA_Const::AA_USER_LEVEL_OPERATOR) $perms += AA_Const::AA_PERMS_WRITE;
            if ($user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN) $perms += AA_Const::AA_PERMS_WRITE + AA_Const::AA_PERMS_PUBLISH + AA_Const::AA_PERMS_DELETE; //Solo l'amministratore può rimuovere le schede pubblicate
        }

        //Struttura diversa scheda pubblicata
        if (!$sameStruct && ($this->nStatus & (AA_Const::AA_STATUS_PUBBLICATA | AA_Const::AA_STATUS_REVISIONATA | AA_Const::AA_STATUS_CESTINATA)) == AA_Const::AA_STATUS_PUBBLICATA) {
            $perms = AA_Const::AA_PERMS_READ;
        }

        //Cache dei permessi
        if ($this->IsPermsCachingEnabled()) $this->aCachePerms[$user->GetId()] = $perms;
        return $perms;
    }

    //Restituisce l'identificativo
    public function GetID()
    {
        return $this->nID;
    }

    //Verifica se l'oggetto è valido
    public function isValid()
    {
        return $this->bValid;
    }

    //Restituisce lo status
    public function GetStatus()
    {
        return $this->nStatus;
    }

    //Restituisce il titolo
    public function GetTitolo()
    {
        return $this->sTitolo;
    }

    //Restituisce la descrizione
    public function GetDescrizione()
    {
        return $this->sDescrizione;
    }

    //Restituisce la data dell'ultimo aggiornamento
    public function GetAggiornamento($bShort = false)
    {
        if ($bShort) {
            $val = explode(" ", $this->tAggiornamento);
            return $val[0];
        }
        return $this->tAggiornamento;
    }

    //Restituisce l'utente associato
    public function GetUser()
    {
        return $this->oUser;
    }

    //Restituisce la struttura associata
    public function GetStruct()
    {
        return $this->oStruct;
    }

    //Rappresentazione stringa
    public function __toString()
    {
        AA_Log::Log(get_class() . "__toString()");

        return $this->toXml();
    }

    //Storicizza l'oggetto (solo per oggetti pubblicati)
    static protected function Snapshot($date = "", $object = null)
    {
        AA_Log::Log(__METHOD__ . "()");
        if (!($object instanceof AA_Object)) {
            AA_Log::Log(__METHOD__ . " - oggetto non valido", 100, true, true);
            return false;
        }

        if ($object->bValid && ($object->nStatus & AA_Const::AA_STATUS_PUBBLICATA) > 0) {
            //Costruisce il contenuto
            if ($date == "") $date = date("Y-m-d H:i:s");

            if (!AA_Archivio::Snapshot($date, $object->nID, $object->nType, $object->toXML())) {
                AA_Log::Log(__METHOD__ . " - errore nell'archiviazione dell'oggetto", 100, true, true);
                return false;
            }

            return true;
        } else {
            AA_Log::Log(__METHOD__ . " - oggetto non valido o non pubblicato", 100, true, true);
            return false;
        }

        return false;
    }

    //Verifica se l'oggetto è pubblicato
    public function IsPublished()
    {
        if (($this->nStatus & AA_Const::AA_STATUS_PUBBLICATA) > 0) {
            return true;
        }

        return false;
    }

    //Restituisce una rappresentazione in xml dell'oggetto
    public function toXml()
    {
        $xml = "<aa_xml_object id='" . $this->nType . "' version='1.0'><meta><timestamp>" . $this->tAggiornamento . "</timestamp><license>IODL</license></meta>";
        $xml .= "<content>";
        $xml .= "<titolo>" . $this->sTitolo . "</titolo>";
        $xml .= "<descrizione>" . $this->sTitolo . "</descrizione>";
        $xml .= "<status>" . $this->nStatus . "</status>";
        $xml .= "</content>";
        $xml .= "</aa_xml_object>";

        return $xml;
    }

    //esportazione in csv
    public function ToCsv($separator="|",$bHeader=true,$bDetail=true,$bToBrowser=true)
    {
        $csv="";
        $header="";
        $rowSeparator="\n";

        if ($bHeader)
        {
            $header="id";
            if($bDetail)
            {
                $header.=$separator."aggiornamento".$separator."stato".$separator."struttura";
            }

            $header.=$separator."nome";
            $header.=$separator."descrizione";
            $header.=$this->CsvDataHeader($separator);
        }

        if(!empty($header))$csv=$header.$rowSeparator;

        $csv.=$this->GetID();
        if($bDetail)
        {
            $csv.=$separator.$this->GetAggiornamento();
            $csv.=$separator.$this->GetStatus();
            $struct= $this->GetStruct();
            $struct_text="Nessuna";
            if(!empty($struct->GetAssessorato(true))) $struct_text = (String) $struct->GetAssessorato();
            if(!empty($struct->GetDirezione(true))) $struct_text .= " - ".(String) $struct->GetDirezione();
            if(!empty($struct->GetServizio(true))) $struct_text .= " - ".(String) $struct->GetServizio();
            $csv.=$separator.$struct_text;
        }

        $csv.=$separator.str_replace("\n",' ',$this->GetTitolo());
        $csv.= $separator.str_replace("\n",' ',$this->GetDescrizione());
        
        $data=$this->CsvData($separator);
        if(!empty($data)) $csv.= $data;

        if($bToBrowser)
        {
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="export.csv"');
            die($csv);
        }

        return $csv;
    }

    //da specializzare
    protected function CsvDataHeader($separator="|")
    {
        return "";
    }

    //da specializzare
    protected function CsvData($separator= "|")
    {
        return "";
    }
    //--------------------------------------------

    //flag di modifica dall'ultima sincronizzazione col db
    private $bChanged = false;
    protected function SetChanged($bVal = true)
    {
        //Invalida la cache dei permessi
        if ($bVal) $this->aCachePerms = array();

        $this->bChanged = $bVal;
    }
    public function IsChanged()
    {

        return $this->bChanged;
    }

    //Db bindings
    protected $oDbBind = null;
    public function GetBindings()
    {
        return $this->oDbBind->GetBindings();
    }

    //Flag di abilitazione alla sincronizzazione del DB
    private $bEnableDbSync = false;
    public function IsDbSyncEnabled()
    {
        return $this->bEnableDbSync;
    }
    protected function EnableDbSync($val = true)
    {
        $this->bEnableDbSync = $val;
    }

    //Sincronizzazione db
    private function DbSync($user = null, $bForce = false)
    {
        //restituisce true se non è abilitata la sincronizzzione del DB
        if (!$this->bEnableDbSync) return true;

        //Restituisce true se non è cambiato nulla e se non viene forzata la modifica
        if (!$this->bChanged && !$bForce) return true;

        //Verifica utente
        if ($user == null || !$user->isValid() || !$user->isCurrentUser()) {
            if ($this->oUser->IsCurrentUser()) $user = $this->oUser;
            else $user = AA_User::GetCurrentUser();

            if ($user == null || !$user->isValid() || !$user->isCurrentUser()) {
                AA_Log::Log(__METHOD__ . " - utente non valido.", 100, true, true);
                return false;
            }
        }

        if (!($this->oUser instanceof AA_User)) $this->oUser = $user;

        //Aggiorna il database
        if ($this->nID == 0) {
            $query = "INSERT INTO " . $this->oDbBind->GetTable() . " SET ";
        } else {
            $query = "UPDATE " . $this->oDbBind->GetTable() . " SET ";
        }

        $separator = "";

        //Verifica se l'aggiornamento è abilitato per l'aggiornamento del db
        if ($this->bEnableAggiornamentoDbSync) {
            $this->tAggiornamento = date("Y-m-d H:i:s");
            $query .= $separator . "aggiornamento='" . $this->tAggiornamento . "'";
            $separator = ",";
        }

        //Verifica se lo status è abilitato per l'aggiornamento del db
        if ($this->bEnableStatusDbSync) {
            //safe value
            if ($this->nStatus == 0) $this->nStatus = AA_Const::AA_STATUS_BOZZA;

            $query .= $separator . "status='" . addslashes($this->nStatus) . "'";
            $separator = ",";
        }

        if (!($this->oStruct instanceof AA_Struct)) $this->oStruct = $user->GetStruct();

        //Aggiornamento della struttura
        if ($this->IsStructDbSyncEnabled()) {
            //verifica che la struttura sia visibile all'utente corrente
            $assessorato = $this->oStruct->GetAssessorato(true);
            $direzione = $this->oStruct->GetDirezione(true);
            $servizio = $this->oStruct->GetServizio(true);
            /*
            $userstruct=$user->GetStruct();
            if($userstruct->GetAssessorato(true) !=0 && $userstruct->GetAssessorato(true) != $assessorato)
            {
                $assessorato=$userstruct->GetAssessorato(true);
                $direzione=0;
                $servizio=0;
            }

            if($userstruct->GetDirezione(true) !=0 && $userstruct->GetDirezione(true) != $direzione)
            {
                $assessorato=$userstruct->GetAssessorato(true);
                $direzione=$userstruct->GetDirezione(true);
                $servizio=0;
            }

            if($userstruct->GetServizio(true) !=0 && $userstruct->GetServizio(true) != $servizio)
            {
                $assessorato=$userstruct->GetAssessorato(true);
                $direzione=$userstruct->GetDirezione(true);
                $servizio=$userstruct->GetServizio(true);
            }*/

            $query .= $separator . "id_assessorato='" . $assessorato . "'";
            $separator = ",";
            $query .= $separator . "id_direzione='" . $direzione . "'";
            $query .= $separator . "id_servizio='" . $servizio . "'";

            //$this->oStruct=AA_Struct::GetStruct($assessorato,$direzione,$servizio);

            $separator = ",";
        }

        //Aggiornamento dell'utente
        if ($this->IsUserDbSyncEnabled()) {
            $this->oUser = $user;
            $query .= $separator . "id_user='" . $this->oUser->GetID() . "'";
            $separator = ",";
        }

        //Aggiornamento degli altri campi
        foreach ($this->oDbBind->GetBindings() as $var => $field) {
            if (property_exists($this, $var)) {
                $query .= $separator . $field . "='" . addslashes($this->$var) . "'";
                $separator = ",";
            }
        }

        //Salvataggio dei logs
        if ($this->bLogEnabled) {
            $query .= $separator . "log='" . addslashes($this->sLog) . "'";
        }

        if ($this->nID > 0) $query .= " WHERE id='" . $this->GetId() . "' LIMIT 1";

        $db = new Database();
        if (!$db->Query($query)) {
            AA_Log::Log(__METHOD__ . " - Errore nella query: " . $query, 100, true, true);
            return false;
        }

        //AA_Log::Log(__METHOD__." - query: ".$query, 100);

        //Reimposta il flag di modifica
        $this->bChanged = false;

        if ($this->nID == 0) {
            $this->nID = $db->LastInsertId();
        }

        $this->bValid = true;

        return true;
    }

    //Restituisce una proprietà dell'oggetto
    public function GetProp($prop = null)
    {
        if (property_exists($this, $prop)) {
            return $this->$prop;
        }

        return "";
    }

    //Carica i dati a partire da un array
    public function ParseData($data = null, $user = null)
    {
        //verifica utente
        if ($user == null || !$user->isValid() || !$user->isCurrentUser()) {
            if ($this->oUser->IsCurrentUser()) $user = $this->oUser;
            else $user = AA_User::GetCurrentUser();

            if ($user == null || !$user->isValid() || !$user->isCurrentUser()) {
                AA_Log::Log(__METHOD__ . " - utente non valido.", 100, true, true);
                return false;
            }
        }

        //Verifica che ci siano dati da parsare
        if (!is_array($data)) {
            AA_Log::Log(__METHOD__ . " - non ci sono dati da caricare.", 100, true, true);
            return false;
        }

        if (isset($data['id_assessorato']) && $data['id_assessorato'] >= 0) $assessorato = $data['id_assessorato'];
        if (isset($data['id_direzione']) && $data['id_direzione'] >= 0) $direzione = $data['id_direzione'];
        if (isset($data['id_servizio']) && $data['id_servizio'] >= 0) $servizio = $data['id_servizio'];

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            } else {
                $var = array_search($key, $this->oDbBind->GetBindings());
                if ($var !== false) {
                    if (property_exists($this, $var)) $this->$var = $value;
                }
            }
        }

        if ($assessorato != "") $this->oStruct = AA_Struct::GetStruct($assessorato, $direzione, $servizio);
        $this->oUser = $user;

        if (isset($data['status'])) {
            $status = $this->nStatusMask & $data['status'];
            if ($status == 0) $status = 1;
            $this->nStatus = $status;
        }

        $this->SetChanged();
        $this->bValid = true;
        $this->bEnableDbSync = false;

        return true;
    }

    //Aggiorna il db in base all'utente corrente ed eventualmente ai dati passati
    public function UpdateDb($user = null, $data = null, $bLog = true,$sDetailLog="")
    {
        //verifica utente
        if ($user == null || !$user->isValid() || !$user->isCurrentUser()) {
            if ($this->oUser->IsCurrentUser()) $user = $this->oUser;
            else $user = AA_User::GetCurrentUser();

            if ($user == null || !$user->isValid() || !$user->isCurrentUser()) {
                AA_Log::Log(__METHOD__ . " - utente non valido.", 100, true, true);
                return false;
            }
        }

        $assessorato = "";
        $direzione = "";
        $servizio = "";
        if (is_array($data) && isset($data['id_assessorato']) && $data['id_assessorato'] >= 0) $assessorato = $data['id_assessorato'];
        if (is_array($data) && isset($data['id_direzione']) && $data['id_direzione'] >= 0) $direzione = $data['id_direzione'];
        if (is_array($data) && isset($data['id_servizio']) && $data['id_servizio'] >= 0) $servizio = $data['id_servizio'];

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            } else {
                $var = array_search($this->oDbBind->GetBindings(), $key);
                if ($var !== false) {
                    if (property_exists($this, $var)) $this->$var = $value;
                }
            }
        }

        if ($this->IsStructDbSyncEnabled() && ($assessorato != "" || $direzione != "" || $servizio != "")) $this->oStruct = AA_Struct::GetStruct($assessorato, $direzione, $servizio);
        if ($this->IsUserDbSyncEnabled()) $this->oUser = $user;

        if (!$this->VerifyDbSync($user)) {
            return false;
        }

        if (($this->nStatus & AA_Const::AA_STATUS_PUBBLICATA) > 0 && ($this->nStatus & AA_Const::AA_STATUS_REVISIONATA) == 0 && ($this->nStatusMask & AA_Const::AA_STATUS_REVISIONATA) > 0) {
            //Imposta il flag "REVISIONATA"
            $this->nStatus |= AA_Const::AA_STATUS_REVISIONATA;
        }

        $this->SetChanged();

        //Aggiorna il db
        if ($this->bLogEnabled && $bLog) {
            $sLog="Modifica";
            if($sDetailLog) $sLog.=" - ".$sDetailLog;
            $this->AddLog($sLog, AA_Const::AA_OPS_UPDATE, $user);
        }

        if ($this->DbSync($user)) {
            if ($this->oParent instanceof AA_Object && $this->IsParentUpdateEnabled()) return $this->oParent->UpdateDb($user,null,$bLog,$sDetailLog);
            else return true;
        }

        return false;
    }

    //Cestina l'oggetto
    public function Trash($user = null, $bDelete = false)
    {
        //Verifica permessi
        if (!$this->VerifyDbSync($user) || !$this->IsValid()) {
            return false;
        }

        $perms = $this->GetUserCaps($user);
        if (($perms & AA_Const::AA_PERMS_DELETE) == 0) {
            AA_Log::Log(__METHOD__ . " - L'utente: " . $user->GetNome() . " non ha i permessi per cestinare/eliminare l'oggetto", 100, false, true);
            return false;
        }

        if ((($this->nStatus & AA_Const::AA_STATUS_CESTINATA) > 0 || !$this->IsStatusDbSyncEnabled()) && $bDelete) {
            //Cancella fisicamente il record;
            $db = new Database();
            $query = "DELETE FROM " . $this->oDbBind->GetTable() . " WHERE id='" . $this->GetID() . "' LIMIT 1";
            if (!$db->Query($query)) {
                AA_Log::Log(__METHOD__ . " - Errore nella query: " . $query, 100, false, true);
                return false;
            }

            $this->nID = 0;
            $this->nStatus = AA_Const::AA_STATUS_BOZZA;
            $this->bValid = false;
            $this->bEnableDbSync = false;

            //Aggiorna il genitore
            if ($this->oParent instanceof AA_Object && $this->IsParentUpdateEnabled()) return $this->oParent->UpdateDb($user);
            else return true;
        }

        if (($this->nStatus & AA_Const::AA_STATUS_CESTINATA) == 0) {
            //Imposta il flag "CESTINATA"
            $this->nStatus |= AA_Const::AA_STATUS_CESTINATA;
            $this->SetChanged();

            //Aggiorna il db
            if ($this->bLogEnabled) {
                $this->AddLog("Cestina", AA_Const::AA_OPS_TRASH, $user);
            }

            if ($this->DbSync($user)) {
                //Aggiorna il genitore
                if ($this->oParent instanceof AA_Object && $this->IsParentUpdateEnabled()) return $this->oParent->UpdateDb($user);
                else return true;
            } else return false;
        }

        return true;
    }

    //Ripristina l'oggetto dal cestino
    public function Resume($user = null)
    {
        //Verifica permessi
        if (!$this->VerifyDbSync($user) || !$this->IsValid()) {
            return false;
        }

        if (($this->nStatus & AA_Const::AA_STATUS_PUBBLICATA) > 0) {
            //Imposta il flag "PUBBLICATA"
            $this->nStatus = AA_Const::AA_STATUS_PUBBLICATA;
            $this->SetChanged();
        }

        if (($this->nStatus & AA_Const::AA_STATUS_BOZZA) > 0) {
            //Imposta il flag "BOZZA"
            $this->nStatus = AA_Const::AA_STATUS_BOZZA;
            $this->SetChanged();
        }

        //Aggiorna il db
        if ($this->bLogEnabled) {
            $this->AddLog("Ripristina", AA_Const::AA_OPS_RESUME, $user);
        }

        //Aggiornamento db
        if ($this->DbSync()) {
            if ($this->oParent instanceof AA_Object && $this->IsParentUpdateEnabled()) return $this->oParent->UpdateDb($user);
            else return true;
        }

        return false;
    }

    //Ripristina l'oggetto dal cestino
    public function Reassign($params = null, $user = null)
    {
        if (is_array($params)) {
            //Verifica dei parametri
            $struct = $user->GetStruct();

            $idAssessorato = $params['riassegna-id-assessorato'];
            if ($idAssessorato == '') $struct->GetAssessorato(true);
            $idDirezione = $params['riassegna-id-direzione'];
            if ($idDirezione == '') $idDirezione = $idDirezione = $struct->GetDirezione(true);
            $idServizio = $params['riassegna-id-servizio'];
            if ($idServizio == '') $idServizio = $struct->GetServizio(true);

            $this->SetStruct(AA_Struct::GetStruct($idAssessorato, $idDirezione, $idServizio));
        } else {
            AA_Log::Log(__METHOD__ . " - Parametri di riassegnazione errati.", 100, false, true);
            return false;
        }

        //Verifica permessi
        if (!$this->VerifyDbSync($user) || !$this->IsValid()) {
            return false;
        }

        //Aggiorna il db
        if ($this->bLogEnabled) {
            $this->AddLog("Riassegna", AA_Const::AA_OPS_REASSIGN, $user);
        }

        //Aggiornamento db
        if ($this->DbSync()) {
            if ($this->oParent instanceof AA_Object && $this->IsParentUpdateEnabled()) return $this->oParent->UpdateDb($user);
            else return true;
        }

        return false;
    }

    //Pubblica l'oggetto
    public function Publish($user = null)
    {
        //Verifica permessi
        if (!$this->VerifyDbSync($user)) {
            return false;
        }

        if ($this->IsPublished() == true) {
            AA_Log::Log(__METHOD__ . " - L'oggetto è già pubblicato.", 100, false, true);
            return false;
        }

        $perms = $this->GetUserCaps($user);
        if (($perms & AA_Const::AA_PERMS_PUBLISH) == 0) {
            AA_Log::Log(__METHOD__ . " - L'utente: " . $user . " non ha i permessi per pubblicare l'oggetto.", 100, false, true);
            return false;
        }

        //Verifica se l'oggetto è pubblicabile (verifiche sui valori)
        if (!$this->CanPublish()) {
            return false;
        }

        $this->nStatus = AA_Const::AA_STATUS_PUBBLICATA;
        $this->SetChanged();

        //Aggiorna il log
        if ($this->bLogEnabled) {
            $this->AddLog("Pubblica", AA_Const::AA_OPS_PUBLISH, $user);
        }

        //Aggiorna il db
        if ($this->DbSync()) {
            if ($this->oParent instanceof AA_Object && $this->IsParentUpdateEnabled()) return $this->oParent->UpdateDb($user);
            else return true;
        }

        return false;
    }

    public function CanPublish()
    {
        //default
        return true;
    }

    //Verifica che l'oggetto collegato sia aggiornabile dall'utente corrente
    protected function VerifyDbSync($user = null)
    {
        if (!$this->bEnableDbSync) {
            AA_Log::Log(__METHOD__ . " - Aggiornamento DB disattivato.", 100, false, true);
            return false;
        }

        //Verifica che siano impostati i valori corretti
        if ($this->oDbBind == null) {
            AA_Log::Log(__METHOD__ . " - Bind record non definito.", 100, false, true);
            return false;
        }

        //Verifica tabella
        if ($this->oDbBind->GetTable() == "") {
            AA_Log::Log(__METHOD__ . " - Tabella non  definita.", 100, false, true);
            return false;
        }

        //Verifica che ci siano campi da sincronizzare
        if (sizeof($this->oDbBind->GetBindings()) == 0) {
            AA_Log::Log(__METHOD__ . " - Non ci sono associazioni definite.", 100, false, true);
            return false;
        }

        //Verifica utente
        if ($this->IsUserDbSyncEnabled() && ($user == null || !$user->isValid() || !$user->isCurrentUser())) {
            if ($this->oUser->IsCurrentUser()) $user = $this->oUser;
            else $user = AA_User::GetCurrentUser();

            if ($user == null || !$user->isValid() || !$user->isCurrentUser()) {
                AA_Log::Log(__METHOD__ . " - utente non valido.", 100, true, true);
                return false;
            }
        }

        //Verifica permessi
        if ($this->IsStructDbSyncEnabled() || ($this->oParent instanceof AA_Object && $this->IsParentPermsCheckEnabled())) {
            $permessi = $this->GetUserCaps($user);

            if (($permessi & AA_Const::AA_PERMS_WRITE) == 0) {
                AA_Log::Log(__METHOD__ . " - utente non ha privilegi sufficienti a modificare l'oggetto.", 100, true, true);
                return false;
            }
        }

        return true;
    }

    //Verifica che l'oggetto collegato sia visibile dall'utente corrente
    protected function VerifyDbLoad()
    {
        //Verifica che siano impostati i valori corretti
        if ($this->oDbBind == null) {
            AA_Log::Log(__METHOD__ . " - Bind record non definito.", 100, true, true);
            return false;
        }

        if ($this->oDbBind->GetTable() == "") {
            AA_Log::Log(__METHOD__ . " - Tabella non  definita.", 100, true, true);
            return false;
        }

        //Verifica che ci siano campi da sincronizzare
        if (sizeof($this->oDbBind->GetBindings()) == 0) {
            AA_Log::Log(__METHOD__ . " - Non ci sono associazioni definite.", 100, true, true);
            return false;
        }

        return true;
    }

    //carica un nuovo oggetto dal db (ed abilita il suo aggiornamento in caso di successo)
    protected function LoadFromDb($id = 0, $user = null)
    {
        $this->bValid = false;
        $this->bEnableDbSync = false;
        $this->ClearCache();

        if ($id <= 0) {
            AA_Log::Log(__METHOD__ . " - identificativo non valido.", 100, true, true);
            return false;
        }

        if (!$this->VerifyDbLoad()) {
            return false;
        }

        $db = new Database();
        $query = "SELECT * from " . $this->oDbBind->GetTable() . " WHERE id='" . $id . "' LIMIT 1";
        if (!$db->Query($query)) {
            AA_Log::Log(__METHOD__ . " - Errore - ".$db->GetErrorMessage()."nella query: " . $query, 100, false, true);
            return false;
        }

        $rs = $db->GetRecordSet();
        if ($rs->GetCount() > 0) {
            $this->bValid = true;
            $this->SetId($id);
            if ($rs->Get("id_user") > 0 && $this->IsUserDbSyncEnabled()) $this->oUser = AA_User::LoadUser($rs->Get("id_user"));
            else $this->oUser = $user;

            if ($rs->Get("id_assessorato") >= 0 && $this->IsStructDbSyncEnabled()) $this->oStruct = AA_Struct::GetStruct($rs->Get("id_assessorato"), $rs->Get("id_direzione"), $rs->Get("id_servizio"));
            else $this->oStruct = $user->GetStruct();

            if ($rs->Get("status") >= 0 && $this->IsStatusDbSyncEnabled()) $this->nStatus = $rs->Get("status");
            else $this->nStatus = 0;

            if ($rs->Get("aggiornamento") != "" && $this->IsAggiornamentoDbSyncEnabled()) $this->tAggiornamento = $rs->Get("aggiornamento");
            else $this->tAggiornamento = date("Y-m-d");

            if ($rs->Get("log") != "" && $this->bLogEnabled) $this->sLog = $rs->Get("log");
            else $this->sLog = "";

            //campi collegati
            foreach ($this->oDbBind->GetBindings() as $var => $db_field) {
                if (property_exists($this, $var)) {
                    //AA_Log::Log(__METHOD__." - campo db: ".$db_field." - variabile: ".$var." (".$rs->Get($db_field).")", 100,false,true);
                    $this->$var = $rs->Get($db_field);
                }
            }

            if (($user == null || !$user->isCurrentUser()) && ($this->GetStatus() & (AA_Const::AA_STATUS_BOZZA + AA_Const::AA_STATUS_REVISIONATA + AA_Const::AA_STATUS_CESTINATA)) > 0 && $this->IsStatusDbSyncEnabled()) {
                if ($this->oUser->IsCurrentUser()) $user = $this->oUser;
                else $user = AA_User::GetCurrentUser();

                if ($user == null || !$user->isCurrentUser()) {
                    AA_Log::Log(__METHOD__ . " - utente non valido.", 100, true, true);
                    return false;
                }
            }

            $this->bValid = true;
            $this->bEnableDbSync = true;

            return true;
        }

        AA_Log::Log(__METHOD__ . " - oggetto non trovato (id: " . $id . ")", 100, false, true);
        return false;
    }
}
