<?php
/**
 * Modulo SICAR - Sistema Informativo Catasto e Amministrazione Risorse
 * Classe AA_SicarImmobile per la gestione degli immobili
 */
include_once "config.php";
include_once "system_lib.php";

/**
 * Costanti per il modulo SICAR
 */
class AA_Sicar_Const extends AA_Const
{
    // Flag utente per il modulo SICAR
    const AA_USER_FLAG_SICAR = "sicar"; // Flag per permessi SICAR

    /**
     * Restituisce la lista delle tipologie di immobile
     * @return array
     */

    const AA_DBTABLE_TIPOLOGIE_IMM = 'aa_sicar_tipologie_immobile';
    public static function GetListaTipologie($bSimpleArray=false)
    {
        $options = array();
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM ".self::AA_DBTABLE_TIPOLOGIE_IMM." ORDER BY descrizione";
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach ($rs as $row) {
                if(!$bSimpleArray) $options[] = array("id" => $row['id'], "value" => $row['descrizione']);
                else $options[$row['id']] = $row['descrizione'];
            }
        }
        return $options;
    }

    //stato conservazione alloggio
    const AA_DBTABLE_STATI_CONSERVAZIONE_ALL = 'aa_sicar_stati_conservazione_alloggio';
    public static function GetListaStatiConservazioneAlloggio($bSimpleArray=false)
    {
        $options = array();
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM ".self::AA_DBTABLE_STATI_CONSERVAZIONE_ALL." ORDER BY descrizione";
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach ($rs as $row) {
                if(!$bSimpleArray) $options[] = array("id" => $row['id'], "value" => $row['descrizione']);
                else $options[$row['id']] = $row['descrizione'];
            }
        }
        return $options;
    }

    //tipologia utilizzo alloggio
    const AA_DBTABLE_TIPOLOGIE_UTILIZZO_ALL = 'aa_sicar_tipologie_utilizzo_alloggio';
    public static function GetListaTipologieUtilizzoAlloggio($bSimpleArray=false)
    {
        $options = array();
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM ".self::AA_DBTABLE_TIPOLOGIE_UTILIZZO_ALL." ORDER BY descrizione";
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach ($rs as $row) {
                if(!$bSimpleArray) $options[] = array("id" => $row['id'], "value" => $row['descrizione']);
                else $options[$row['id']] = $row['descrizione'];
            }
        }
        return $options;
    }

    const AA_DBTABLE_UBICAZIONI_IMM = 'aa_sicar_ubicazioni_immobile';
    public static function GetListaUbicazioni($bSimpleArray=false)
    {
        $options = array();
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM ".self::AA_DBTABLE_UBICAZIONI_IMM." ORDER BY descrizione";
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach ($rs as $row) {
                if(!$bSimpleArray) $options[] = array("id" => $row['id'], "value" => $row['descrizione']);
                else $options[$row['id']] = $row['descrizione'];
            }
        }
        return $options;
    }

    const AA_DBTABLE_ZONE_URBANISTICHE_IMM = 'aa_sicar_zone_urbanistiche_immobile';
    public static function GetListaZoneUrbanistiche()
    {
        $options = array();
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM ".self::AA_DBTABLE_ZONE_URBANISTICHE_IMM." ORDER BY descrizione";
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach ($rs as $row) {
                $options[] = array("id" => $row['id'], "value" => $row['descrizione']);
            }
        }
        return $options;
    }

    // Costanti
    const MODULE_ID = 'AA_SICAR';
    const MODULE_NAME = 'SICAR - Sistema Informativo Catasto e Amministrazione Risorse';
    const MODULE_VERSION = '1.0.0';

    //tabella codici istat
    const AA_DBTABLE_CODICI_ISTAT = 'aa_sicar_codici_istat';
    public static function GetComuneDescrFromCodiceIstat($codice_istat)
    {
        $db = new AA_Database();
        $query = "SELECT comune FROM ".self::AA_DBTABLE_CODICI_ISTAT." WHERE codice = '".addslashes($codice_istat)."'";
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            return $rs[0]['comune'];
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile ottenere la descrizione del comune per il codice istat: " . $codice_istat, 100);
        }
        return "";
    }

    const DB_TABLE_IMMOBILI = 'aa_sicar_data';
    const DB_TABLE_TIPOLOGIE = 'aa_sicar_tipologie';
    const DB_TABLE_UBICAZIONI = 'aa_sicar_ubicazioni';
    const DB_TABLE_ZONE_URBANISTICHE = 'aa_sicar_zone_urbanistiche';

    const UI_ICON = 'mdi mdi-home-city';
    const UI_COLOR = '#667eea';

    const PAGE_SIZE = 20;
    const MAX_PAGE_SIZE = 100;

    const MAX_DESCRIZIONE_LENGTH = 250;
    const MAX_INDIRIZZO_LENGTH = 255;
    const MAX_CATASTO_LENGTH = 100;
    const MAX_NOTE_LENGTH = 1000;
    const MIN_PIANI = 1;
    const MAX_PIANI = 100;

    const CSV_SEPARATOR = ';';
    const CSV_ENCODING = 'UTF-8';

    const SEARCH_MIN_LENGTH = 3;
    const SEARCH_TIMEOUT = 500;

    const CACHE_ENABLED = true;
    const CACHE_TTL = 3600;

    const LOG_ENABLED = true;
    const LOG_LEVEL = 'INFO';

    const REQUIRE_AUTH = true;
    const CHECK_PERMISSIONS = true;

    const UPLOAD_ENABLED = false;
    const UPLOAD_MAX_SIZE = 5242880;
    const UPLOAD_ALLOWED_TYPES = ['jpg', 'jpeg', 'png', 'pdf'];

    const API_ENABLED = false;
    const API_VERSION = 'v1';
    const API_RATE_LIMIT = 100;

    const NOTIFICATIONS_ENABLED = true;
    const NOTIFICATION_EMAIL = '';

    const BACKUP_ENABLED = true;
    const BACKUP_RETENTION_DAYS = 30;

    const REPORTS_ENABLED = true;
    const REPORTS_PATH = 'reports/';

    const GEO_ENABLED = false;
    const GEO_API_KEY = '';

    const CATASTO_API_ENABLED = false;
    const CATASTO_API_URL = '';
    const CATASTO_API_KEY = '';

    const URBANISTICA_API_ENABLED = false;
    const URBANISTICA_API_URL = '';
    const URBANISTICA_API_KEY = '';

    const MAPS_ENABLED = false;
    const MAPS_API_KEY = '';
    const MAPS_DEFAULT_CENTER = '39.2238,9.1217';
    const MAPS_DEFAULT_ZOOM = 10;

    const PRINT_ENABLED = true;
    const PRINT_TEMPLATE_PATH = 'templates/print/';

    const IMPORT_ENABLED = false;
    const IMPORT_MAX_ROWS = 1000;
    const IMPORT_ALLOWED_FORMATS = ['csv', 'xlsx'];

    const SYNC_ENABLED = false;
    const SYNC_INTERVAL = 3600;

    const AUDIT_ENABLED = true;
    const AUDIT_RETENTION_DAYS = 365;

    const QUERY_TIMEOUT = 30;
    const MAX_RESULTS = 10000;

    const DEFAULT_LOCALE = 'it_IT';
    const DATE_FORMAT = 'd/m/Y';
    const TIME_FORMAT = 'H:i:s';
    const DATETIME_FORMAT = 'd/m/Y H:i:s';

    const DECIMAL_SEPARATOR = ',';
    const THOUSANDS_SEPARATOR = '.';

    // Array di configurazione
    public static $VALIDATION_RULES = [
        'nome' => [
            'required' => true,
            'min_length' => 3,
            'max_length' => self::MAX_DESCRIZIONE_LENGTH,
            'pattern' => '/^[a-zA-Z0-9\\s\\-_\\.]+$/'
        ],
        'tipologia' => [
            'required' => true,
            'type' => 'integer',
            'min' => 1
        ],
        'comune' => [
            'required' => true,
            'pattern' => '/^[0-9]{6}$/'
        ],
        'ubicazione' => [
            'required' => true,
            'type' => 'integer',
            'min' => 1
        ],
        'indirizzo' => [
            'required' => true,
            'min_length' => 5,
            'max_length' => self::MAX_INDIRIZZO_LENGTH
        ],
        'catasto' => [
            'required' => true,
            'max_length' => self::MAX_CATASTO_LENGTH
        ],
        'zona_urbanistica' => [
            'required' => true,
            'max_length' => 50
        ],
        'piani' => [
            'required' => true,
            'type' => 'integer',
            'min' => self::MIN_PIANI,
            'max' => self::MAX_PIANI
        ],
        'note' => [
            'required' => false,
            'max_length' => self::MAX_NOTE_LENGTH
        ]
    ];

    public static $MESSAGES = [
        'it' => [
            'success_save' => 'Immobile salvato con successo',
            'success_delete' => 'Immobile eliminato con successo',
            'success_publish' => 'Immobile pubblicato con successo',
            'error_save' => 'Errore nel salvataggio dell\'immobile',
            'error_delete' => 'Errore nell\'eliminazione dell\'immobile',
            'error_publish' => 'Errore nella pubblicazione dell\'immobile',
            'error_validation' => 'Errori di validazione',
            'error_not_found' => 'Immobile non trovato',
            'error_permission' => 'Permessi insufficienti',
            'confirm_delete' => 'Sei sicuro di voler eliminare questo immobile?',
            'confirm_publish' => 'Sei sicuro di voler pubblicare questo immobile?',
            'loading' => 'Caricamento in corso...',
            'no_data' => 'Nessun dato trovato',
            'search_placeholder' => 'Cerca immobili...',
            'export_success' => 'Esportazione completata con successo',
            'export_error' => 'Errore durante l\'esportazione'
        ],
        'en' => [
            'success_save' => 'Property saved successfully',
            'success_delete' => 'Property deleted successfully',
            'success_publish' => 'Property published successfully',
            'error_save' => 'Error saving property',
            'error_delete' => 'Error deleting property',
            'error_publish' => 'Error publishing property',
            'error_validation' => 'Validation errors',
            'error_not_found' => 'Property not found',
            'error_permission' => 'Insufficient permissions',
            'confirm_delete' => 'Are you sure you want to delete this property?',
            'confirm_publish' => 'Are you sure you want to publish this property?',
            'loading' => 'Loading...',
            'no_data' => 'No data found',
            'search_placeholder' => 'Search properties...',
            'export_success' => 'Export completed successfully',
            'export_error' => 'Error during export'
        ]
    ];

    public static $STATUSES = [
        'draft' => [
            'id' => 1,
            'name' => 'Bozza',
            'color' => '#ffc107',
            'icon' => 'mdi mdi-file-document-outline'
        ],
        'published' => [
            'id' => 2,
            'name' => 'Pubblicato',
            'color' => '#28a745',
            'icon' => 'mdi mdi-check-circle'
        ],
        'trashed' => [
            'id' => 4,
            'name' => 'Cestinato',
            'color' => '#dc3545',
            'icon' => 'mdi mdi-delete'
        ],
        'revision' => [
            'id' => 8,
            'name' => 'In revisione',
            'color' => '#007bff',
            'icon' => 'mdi mdi-pencil'
        ]
    ];

    public static $DEFAULT_TIPOLOGIE = [
        ['codice' => '001', 'descrizione' => 'Ufficio', 'ordine' => 1],
        ['codice' => '002', 'descrizione' => 'Magazzino', 'ordine' => 2],
        ['codice' => '003', 'descrizione' => 'Officina', 'ordine' => 3],
        ['codice' => '004', 'descrizione' => 'Laboratorio', 'ordine' => 4],
        ['codice' => '005', 'descrizione' => 'Aula', 'ordine' => 5],
        ['codice' => '006', 'descrizione' => 'Sala riunioni', 'ordine' => 6],
        ['codice' => '007', 'descrizione' => 'Mensa', 'ordine' => 7],
        ['codice' => '008', 'descrizione' => 'Palestra', 'ordine' => 8],
        ['codice' => '009', 'descrizione' => 'Biblioteca', 'ordine' => 9],
        ['codice' => '010', 'descrizione' => 'Altro', 'ordine' => 10]
    ];

    public static $DEFAULT_COMUNI = [
        ['codice' => '092009', 'nome' => 'Cagliari'],
        ['codice' => '092003', 'nome' => 'Alghero'],
        ['codice' => '092015', 'nome' => 'Carbonia'],
        ['codice' => '092025', 'nome' => 'Iglesias'],
        ['codice' => '092035', 'nome' => 'Nuoro'],
        ['codice' => '092050', 'nome' => 'Oristano'],
        ['codice' => '092051', 'nome' => 'Olbia'],
        ['codice' => '092064', 'nome' => 'Sassari']
    ];

    // Funzioni statiche
    public static function GetConfig($key = null) {
        if ($key === null) {
            return [
                'validation_rules' => self::$VALIDATION_RULES,
                'messages' => self::$MESSAGES,
                'statuses' => self::$STATUSES
            ];
        }
        switch ($key) {
            case 'validation_rules':
                return self::$VALIDATION_RULES;
            case 'messages':
                return self::$MESSAGES;
            case 'statuses':
                return self::$STATUSES;
            default:
                return defined('self::' . $key) ? constant('self::' . $key) : null;
        }
    }

    public static function GetMessage($key, $locale = 'it') {
        $messages = self::GetConfig('messages');
        return isset($messages[$locale][$key]) ? $messages[$locale][$key] : $key;
    }

    public static function ValidateField($field, $value) {
        $rules = self::GetConfig('validation_rules');
        if (!isset($rules[$field])) {
            return true;
        }
        $rule = $rules[$field];
        if (isset($rule['required']) && $rule['required'] && empty($value)) {
            return false;
        }
        if (isset($rule['min_length']) && strlen($value) < $rule['min_length']) {
            return false;
        }
        if (isset($rule['max_length']) && strlen($value) > $rule['max_length']) {
            return false;
        }
        if (isset($rule['pattern']) && !preg_match($rule['pattern'], $value)) {
            return false;
        }
        if (isset($rule['type']) && $rule['type'] === 'integer') {
            if (!is_numeric($value)) {
                return false;
            }
            $value = intval($value);
            if (isset($rule['min']) && $value < $rule['min']) {
                return false;
            }
            if (isset($rule['max']) && $value > $rule['max']) {
                return false;
            }
        }
        return true;
    }
}

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
        $this->aProps['note']="";
        
        // Chiama il costruttore padre
        parent::__construct($params);
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
    public function GetComune()
    {
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
    public function GetUbicazione()
    {
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
    
    // Catasto
    public function GetCatasto()
    {
        return $this->GetProp("catasto");
    }
    
    public function SetCatasto($var = "")
    {
        $this->SetProp("catasto", $var);
        return true;
    }
    
    // Zona Urbanistica
    public function GetZonaUrbanistica()
    {
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
        if (!empty($this->GetComune())) {
            $display .= " (" . AA_Sicar_Const::GetComuneDescrFromCodiceIstat($this->GetComune()) . ")";
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


/**
 * Classe principale del modulo SICAR
 */
class AA_SicarModule extends AA_GenericModule
{
    const AA_UI_PREFIX = "AA_Sicar";
    
    // Id modulo
    const AA_ID_MODULE = "AA_MODULE_SICAR";
    
    // Main ui layout box
    const AA_UI_MODULE_MAIN_BOX = "AA_Sicar_module_layout";

    //ui id sezione dettaglio
    const AA_UI_DETAIL_GENERALE_BOX = "Generale_Box";

    const AA_UI_SECTION_BOZZE_NAME="Alloggi (bozze)";
     const AA_UI_SECTION_PUBBLICATE_NAME="Alloggi (pubblicate)";
    
    //id sezione gestione immobili
    const AA_ID_SECTION_IMMOBILI = "GestImmobili";
    const AA_UI_SECTION_IMMOBILI_BOX = "GestImmobiliBox";
    const AA_UI_SECTION_IMMOBILI_NAME = "Gestione Immobili";

    const AA_MODULE_OBJECTS_CLASS = "AA_SicarAlloggio";
    
    // Task per la gestione dei dialoghi standard
    const AA_UI_TASK_PUBBLICATE_FILTER_DLG = "GetSicarPubblicateFilterDlg";
    const AA_UI_TASK_BOZZE_FILTER_DLG = "GetSicarBozzeFilterDlg";
    const AA_UI_TASK_REASSIGN_DLG = "GetSicarReassignDlg";
    const AA_UI_TASK_PUBLISH_DLG = "GetSicarPublishDlg";
    const AA_UI_TASK_TRASH_DLG = "GetSicarTrashDlg";
    const AA_UI_TASK_RESUME_DLG = "GetSicarResumeDlg";
    const AA_UI_TASK_DELETE_DLG = "GetSicarDeleteDlg";
    const AA_UI_TASK_ADDNEW_DLG = "GetSicarAddNewDlg";
    const AA_UI_TASK_MODIFY_DLG = "GetSicarModifyDlg";
    
    // Task specifici per SICAR
    const AA_UI_TASK_GET_TIPOLOGIE = "GetSicarTipologie";
    const AA_UI_TASK_GET_UBICAZIONI = "GetSicarUbicazioni";
    const AA_UI_TASK_GET_ZONE_URBANISTICHE = "GetSicarZoneUrbanistiche";
    const AA_UI_TASK_GET_COMUNI = "GetSicarComuni";
    const AA_UI_TASK_EXPORT_CSV = "ExportSicarCsv";

    const AA_UI_TABLE_SEARCH_IMMOBILI = "TableSearchImmobili";
    
    //ricerca immobili
    const AA_UI_WND_SEARCH_IMMOBILI = "SicarSearchWnd";
    public function __construct($user = null, $bDefaultSections = true)
    {
        if (!($user instanceof AA_User)) {
            $user = AA_User::GetCurrentUser();
        }
        
        parent::__construct($user, $bDefaultSections);
        
        // Registrazione dei task
        $taskManager = $this->GetTaskManager();
        
        // Task standard per la gestione degli oggetti
        $taskManager->RegisterTask("GetSicarPubblicateFilterDlg");
        $taskManager->RegisterTask("GetSicarBozzeFilterDlg");
        $taskManager->RegisterTask("GetSicarReassignDlg");
        $taskManager->RegisterTask("GetSicarPublishDlg");
        $taskManager->RegisterTask("GetSicarTrashDlg");
        $taskManager->RegisterTask("GetSicarResumeDlg");
        $taskManager->RegisterTask("GetSicarDeleteDlg");
        $taskManager->RegisterTask("GetSicarAddNewDlg");
        $taskManager->RegisterTask("GetSicarModifyDlg");
        
        //immobili
        $taskManager->RegisterTask("GetSicarAddNewImmobileDlg");
        // Task per le operazioni CRUD
        $taskManager->RegisterTask("AddNewAlloggioSicar");
        $taskManager->RegisterTask("AddNewImmobileSicar");

        $taskManager->RegisterTask("UpdateSicar");
        $taskManager->RegisterTask("DeleteSicar");
        $taskManager->RegisterTask("PublishSicar");
        $taskManager->RegisterTask("TrashSicar");
        $taskManager->RegisterTask("ResumeSicar");
        $taskManager->RegisterTask("ReassignSicar");
        
        // Task specifici per SICAR
        $taskManager->RegisterTask("GetSicarTipologie");
        $taskManager->RegisterTask("GetSicarUbicazioni");
        $taskManager->RegisterTask("GetSicarZoneUrbanistiche");
        $taskManager->RegisterTask("GetSicarComuni");
        $taskManager->RegisterTask("ExportSicarCsv");
        $taskManager->RegisterTask("GetSicarListaCodiciIstat");
        $taskManager->RegisterTask("GetSicarSearchImmobiliDlg");

        //template dettaglio
        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL,array(
            array("id"=>static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateSicarDettaglio_Generale_Tab")
        ));

        #----------------------search immobili --------------------
        $this->AddObjectTemplate(static::AA_UI_WND_SEARCH_IMMOBILI."_".static::AA_UI_TABLE_SEARCH_IMMOBILI,"Template_DatatableSearchImmobili");
        #---------------------------------------------------------------

        #----------------------- Gest immobili -------------------------
        $gest_immobili=new AA_GenericModuleSection(static::AA_ID_SECTION_IMMOBILI,"Gestione Immobili",true,static::AA_UI_PREFIX."_".static::AA_ID_SECTION_IMMOBILI,$this->GetId(),false,true,false,false,'mdi-office-building-marker',"TemplateSection_Immobili");
        $this->AddSection($gest_immobili);

        $bozze=$this->GetSection(static::AA_ID_SECTION_BOZZE);
        $bozze->SetNavbarTemplate(array($this->TemplateGenericNavbar_Pubblicate(1)->toArray(),$this->TemplateGenericNavbar_Section($gest_immobili,2,true)->toArray()));

        $gest_immobili->SetNavbarTemplate(array($this->TemplateGenericNavbar_Immobili(1,true,true)->toArray()));
    }
    
    //Navbar Immobili
    protected function TemplateGenericNavbar_Immobili($level = 1, $last = false, $refresh_view = true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(
            "",
            array(
                "type" => "clean",
                "section_id" => static::AA_ID_SECTION_BOZZE,
                "module_id" => $this->GetId(),
                "refresh_view" => $refresh_view,
                "tooltip" => "Fai click per visualizzare la sezione relativa alle bozze",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" . static::AA_UI_PREFIX . "_Navbar_Link_" . static::AA_ID_SECTION_IMMOBILI . "' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"".static::AA_ID_SECTION_BOZZE."\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => "Gestione alloggi (bozze)", "icon" => "mdi mdi-office-building", "class" => $class)
            )
        );
        return $navbar;
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

    // Restituisce i dati delle bozze
    public function GetDataSectionBozze_List($params = array())
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            AA_Log::Log(__METHOD__ . " - ERRORE: l'utente corrente: " . $this->oUser->GetUserName() . " non è abilitato alla visualizzazione delle bozze.", 100);
            return array();
        }

        // Recupera immobili in stato bozza
        return $this->GetDataGenericSectionBozze_List($params,"GetDataSectionBozze_CustomFilter","GetDataSectionBozze_CustomDataTemplate");
    }

    // Personalizza il template dei dati delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomDataTemplate($data = array(), $object = null)
    {
        if ($object instanceof AA_SicarAlloggio) {
            $data['pretitolo'] = $object->GetImmobile(false);
            $data['titolo'] = $object->GetDisplayName();
            $tags="<span class='AA_DataView_Tag AA_Label AA_Label_LightYellow' title='Tipo di utilizzo'>".$object->GetTipologiaUtilizzo()."</span>";
            if(!empty($object->GetAnnoRistrutturazione()))$tags.=" <span class='AA_DataView_Tag AA_Label AA_Label_LightGreen' title='Anno ultima ristrutturazione'>".$object->GetAnnoRistrutturazione()."</span>";
            $data['tags']=$tags;
        }
        else
        {
            AA_Log::Log(__METHOD__." - oggetto non valido: ".print_r($object,true),100);
        }

        //AA_Log::Log(__METHOD__." - oggetto: ".print_r($object,true),100);
        return $data;
    }

    //Personalizza il filtro delle bozze per il modulo corrente
    protected function GetDataSectionBozze_CustomFilter($params = array())
    {
        //immobile
        if(isset($params['immobile']) && $params['immobile'] > 0)
        {
            $params['where'][]=" AND ".AA_SicarAlloggio::AA_DBTABLE_DATA.".immobile like '%".addslashes($params['immobile'])."%'";
        }

        //comune
        if(!empty($params['comune']))
        {
            $params['join'][]=" LEFT JOIN ".AA_SicarImmobile::GetDatatable()." ON ".AA_SicarAlloggio::AA_DBTABLE_DATA.".immobile=".AA_SicarImmobile::GetDatatable().".id";
            $params['where'][]=" AND ".AA_SicarImmobile::GetDatatable().".comune like '".addslashes($params['comune'])."'";
        }

        //indirizzo
        if(!empty($params['indirizzo']))
        {
            $params['join'][]=" LEFT JOIN ".AA_SicarImmobile::GetDatatable()." ON ".AA_SicarAlloggio::AA_DBTABLE_DATA.".immobile=".AA_SicarImmobile::GetDatatable().".id";
            $params['where'][]=" AND ".AA_SicarImmobile::GetDatatable().".indirizzo like '%".addslashes($params['indirizzo'])."%'";
        }

        if(!empty($params['stato_conservazione']) && $params['stato_conservazione'] > 0)
        {
            $params['where'][]=" AND ".AA_SicarAlloggio::AA_DBTABLE_DATA.".stato_conservazione like '".addslashes($params['stato_conservazione'])."'";
        }

        //ids
        if(isset($params['ids']) &&  $params['ids']!="")
        {
            $params['where'][]=" AND ".AA_SicarAlloggio::GetObjectsDbDataTable().".id in (".addslashes($params['ids']).")";
        }
    
        return $params;
    }


    // Template della sezione bozze
    public function TemplateSection_Bozze($params = array())
    {
        $bCanModify = $this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR);
        $params['enableAddNewMultiFromCsv'] = false;

        // Qui puoi usare AA_GenericSection_Bozze o un template simile a GECOP
        $content = $this->TemplateGenericSection_Bozze($params,null);
        return $content->toObject();
    }

        // Restituisce i dati delle pubblicate
        public function GetDataSectionPubblicate_List($params = array())
        {
            if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
                AA_Log::Log(__METHOD__ . " - ERRORE: l'utente corrente: " . $this->oUser->GetUserName() . " non è abilitato alla visualizzazione delle pubblicate.", 100);
                return array();
            }

            // Recupera immobili in stato pubblicato
            return AA_SicarAlloggio::Search($params, $this->oUser);
        }

        // Personalizza il template dei dati delle pubblicate per il modulo corrente
        protected function GetDataSectionPubblicate_CustomDataTemplate($data = array(), $object = null)
        {
            if ($object instanceof AA_SicarAlloggio) {
                $data['pretitolo'] = $object->GetProp("Anno");
                $data['titolo'] = $object->GetDisplayName();
                $data['tags'] = "<span class='AA_DataView_Tag AA_Label AA_Label_LightGreen'>Pubblicata</span>";
            }
            return $data;
        }

        // Template della sezione pubblicate
        public function TemplateSection_Pubblicate($params = array())
        {
            $bCanModify = $this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR);
            $params['enableAddNewMultiFromCsv'] = false;

            // Qui puoi usare AA_GenericSection_Pubblicate o un template simile a GECOP
            $content=$this->TemplateGenericSection_Pubblicate($params,null);
            return $content->toObject();
        }

    // Template per la finestra di dialogo di aggiunta nuovo alloggio
    public function Template_GetSicarAddNewAlloggioDlg()
    {
        $id = $this->GetId() . "_AddNew_Dlg_" . uniqid();
        $form_data = array();
        $form_data['nome'] = "Nuovo alloggio";
        $form_data['tipologia_utilizzo'] = 0;
        $form_data['stato_conservazione'] = 0;
        $form_data['anno_ristrutturazione'] = "";
        $form_data['condominio_misto'] = 0;
        $form_data['superficie_netta'] = 0;
        $form_data['superficie_utile_abitabile'] = 0;
        $form_data['piano'] = 0;
        $form_data['ascensore'] = 0;
        $form_data['fruibile_dis'] = 0;
        $form_data['note'] = "";

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo alloggio", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(150);
        $wnd->SetWidth(980);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: riferimento immobile (opzionale)
        $immobili = AA_SicarImmobile::GetListaImmobili();
        $options = array();
        foreach($immobili as $option)
        {
            $options[] = array("id" => $option->GetProp("id"), "value" => $option->GetDisplayName());
        }
        //$wnd->AddSelectField("immobile", "Immobile", ["required" => true, "bottomLabel" => "*Scegli un elemento della lista o fai click su nuovo se non e' presente l'immobile nella lista.","options" => $options]);
        $dlgParams = array("task" => "GetSicarSearchImmobiliDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"immobile","field_desc"=>"immobile_desc"));
        $wnd->AddSearchField("dlg",$dlgParams,$this->GetId(),["required" => true,"label"=>"Immobile","name"=>"immobile_desc", "bottomLabel" => "*Cerca un immobile gia' esistente o aggiungine uno se non e' presente."]);

        $addnew_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-plus",
             "label"=>"Nuovo immobile",
             "align"=>"right",
             "autowidth"=>true,
             "tooltip"=>"Aggiungi un nuovo immobile",
             "click"=>"AA_MainApp.utils.callHandler('dlg', {task:'GetSicarAddNewImmobileDlg', params: [{wnd_alloggio: '".$wnd->GetId()."'}]},'".$this->id."')"
         ));
        //$wnd->AddGenericObject($addnew_btn,false);

        // Campo testuale: descrizione
        $wnd->AddTextField("nome", "nome", ["required" => true, "bottomLabel" => "*Descrizione dell'alloggio"]);
        
        // Campo testuale: tipologia utilizzo
        $options = AA_Sicar_Const::GetListaTipologieUtilizzoAlloggio();
        $wnd->AddSelectField("tipologia_utilizzo", "Tipologia utilizzo", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        // Campo testuale: stato conservazione
        $options = AA_Sicar_Const::GetListaStatiConservazioneAlloggio();
        $wnd->AddSelectField("stato_conservazione", "Stato conservazione", ["required" => true,"validateFunction"=>"IsSelected","labelWidth"=>160, "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options],false);
        
        // Campo numerico: anno ristrutturazione
        $wnd->AddTextField("anno_ristrutturazione", "Anno ristrutturazione", ["required" => false, "bottomLabel" => "Anno (se presente)"]);
    
        // Campo numerico: superficie netta
        $wnd->AddTextField("superficie_netta", "Superficie netta", ["required" => false, "bottomLabel" => "Valore in metri quadri"],false);
        // Campo numerico: superficie utile abitabile
        $wnd->AddTextField("superficie_utile_abitabile", "Superficie abitabile", ["required" => false, "bottomLabel" => "Superficie utile in mq"],false);
        // Campo numerico: piano
        $wnd->AddTextField("piano", "Piano", ["required" => true, "bottomLabel" => "Numero del piano"],false);

        // Campo booleano: condominio misto
        $wnd->AddCheckBoxField("condominio_misto", " ", ["required" => false, "labelWidth"=>150,"labelRight" => "Condominio misto"]);
        
        // Campo booleano: ascensore
        $wnd->AddCheckBoxField("ascensore", " ", ["required" => false,"labelWidth"=>90, "labelRight" => "Servito da ascensore"],false);
        
        // Campo booleano: fruibile per disabili
        $wnd->AddCheckBoxField("fruibile_dis", " ", ["required" => false,"labelWidth"=>90, "labelRight" => "Fruibile da disabile"],false);
       
        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("AddNewAlloggioSicar");
        return $wnd;
    }

    // Template per la finestra di dialogo di aggiunta nuovo alloggio
    public function Template_GetSicarModifyAlloggioDlg($object=null)
    {
        $id = $this->GetId() . "_Modify_Dlg_" . uniqid();
        if(!($object instanceof AA_SicarAlloggio))
        {
            $wnd = new AA_GenericWindowTemplate($id, "Modifica alloggio", $this->id);
            return $wnd;
        }
        if(!$object->IsValid()) return new AA_GenericWindowTemplate("", "Modifica alloggio", $this->id);
        if($object->GetId()==0) return new AA_GenericWindowTemplate("", "Modifica alloggio", $this->id);

        $form_data = array();
        $form_data['id'] = $object->GetId();
        $form_data['nome'] = $object->GetName();

        $immobile=$object->GetImmobile();
        $form_data["immobile"]=$immobile->GetProp("id");
        $form_data["immobile_desc"]=$immobile->GetDisplayName();
        $form_data['tipologia_utilizzo'] = $object->GetTipologiaUtilizzo(false);
        $form_data['stato_conservazione'] = $object->GetStatoConservazione(false);
        $form_data['anno_ristrutturazione'] = $object->GetAnnoRistrutturazione();
        $form_data['condominio_misto'] = $object->GetCondominioMisto();
        $form_data['superficie_netta'] = $object->GetSuperficieNetta();
        $form_data['superficie_utile_abitabile'] = $object->GetSuperficieUtileAbitabile();
        $form_data['piano'] = $object->GetPiano();
        $form_data['ascensore'] = $object->GetAscensore();
        $form_data['fruibile_dis'] = $object->GetFruibileDis();
        $form_data['note'] = $object->GetNote();

        $wnd = new AA_GenericFormDlg($id, "Modifica alloggio", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(150);
        $wnd->SetWidth(980);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: riferimento immobile (opzionale)
        $immobili = AA_SicarImmobile::GetListaImmobili();
        $options = array();
        foreach($immobili as $option)
        {
            $options[] = array("id" => $option->GetProp("id"), "value" => $option->GetDisplayName());
        }
        //$wnd->AddSelectField("immobile", "Immobile", ["required" => true, "bottomLabel" => "*Scegli un elemento della lista o fai click su nuovo se non e' presente l'immobile nella lista.","options" => $options]);
        $dlgParams = array("task" => "GetSicarSearchImmobiliDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"immobile","field_desc"=>"immobile_desc"));
        $wnd->AddSearchField("dlg",$dlgParams,$this->GetId(),["required" => true,"label"=>"Immobile","name"=>"immobile_desc", "bottomLabel" => "*Cerca un immobile gia' esistente o aggiungine uno se non e' presente."]);

        $addnew_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-plus",
             "label"=>"Nuovo immobile",
             "align"=>"right",
             "autowidth"=>true,
             "tooltip"=>"Aggiungi un nuovo immobile",
             "click"=>"AA_MainApp.utils.callHandler('dlg', {task:'GetSicarAddNewImmobileDlg', params: [{wnd_alloggio: '".$wnd->GetId()."'}]},'".$this->id."')"
         ));
        //$wnd->AddGenericObject($addnew_btn,false);

        // Campo testuale: descrizione
        $wnd->AddTextField("nome", "nome", ["required" => true, "bottomLabel" => "*Descrizione dell'alloggio"]);
        
        // Campo testuale: tipologia utilizzo
        $options = AA_Sicar_Const::GetListaTipologieUtilizzoAlloggio();
        $wnd->AddSelectField("tipologia_utilizzo", "Tipologia utilizzo", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        // Campo testuale: stato conservazione
        $options = AA_Sicar_Const::GetListaStatiConservazioneAlloggio();
        $wnd->AddSelectField("stato_conservazione", "Stato conservazione", ["required" => true,"validateFunction"=>"IsSelected","labelWidth"=>160, "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options],false);
        
        // Campo numerico: anno ristrutturazione
        $wnd->AddTextField("anno_ristrutturazione", "Anno ristrutturazione", ["required" => false, "bottomLabel" => "Anno (se presente)"]);
    
        // Campo numerico: superficie netta
        $wnd->AddTextField("superficie_netta", "Superficie netta", ["required" => false, "bottomLabel" => "Valore in metri quadri"],false);
        // Campo numerico: superficie utile abitabile
        $wnd->AddTextField("superficie_utile_abitabile", "Superficie abitabile", ["required" => false, "bottomLabel" => "Superficie utile in mq"],false);
        // Campo numerico: piano
        $wnd->AddTextField("piano", "Piano", ["required" => true, "bottomLabel" => "Numero del piano"],false);

        // Campo booleano: condominio misto
        $wnd->AddCheckBoxField("condominio_misto", " ", ["required" => false, "labelWidth"=>150,"labelRight" => "Condominio misto"]);
        
        // Campo booleano: ascensore
        $wnd->AddCheckBoxField("ascensore", " ", ["required" => false,"labelWidth"=>90, "labelRight" => "Servito da ascensore"],false);
        
        // Campo booleano: fruibile per disabili
        $wnd->AddCheckBoxField("fruibile_dis", " ", ["required" => false,"labelWidth"=>90, "labelRight" => "Fruibile da disabile"],false);
       
        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("UpdateSicar");
        return $wnd;
    }

    // Template per la finestra di dialogo di aggiunta nuovo immobile
    public function Template_GetSicarAddNewImmobileDlg()
    {
        $form = "";
        if(!empty($_REQUEST['form']))
        {
            $form = $_REQUEST['form'];
        }

        $field_id="";
        if(!empty($_REQUEST['field_id']))
        {
            $field_id = $_REQUEST['field_id'];
        }

        $field_desc="";
        if(!empty($_REQUEST['field_desc']))
        {
            $field_desc = $_REQUEST['field_desc'];
        }
        
        $id = $this->GetId() . "_AddNew_Dlg_" . uniqid();
        $form_data = array();

        $newImmobile = new AA_SicarImmobile();
        foreach($newImmobile->GetProps() as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }
        //dati catastali
        $form_data['catasto'] = "";
        $form_data['SezioneCatasto'] = 0;
        $form_data['FoglioCatasto'] = "";
        $form_data['MappaleCatasto'] = "";
        $form_data['ParticellaCatasto'] = "";
        $form_data['Subalterno'] = "";

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo Immobile", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(980);
        $wnd->SetHeight(800);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: descrizione
        $wnd->AddTextField("descrizione", "Descrizione", ["required" => true, "bottomLabel" => "*Descrizione dell'immobile"]);
        
        // Campo testuale: tipologia
        $options = AA_Sicar_Const::GetListaTipologie();
        $wnd->AddSelectField("tipologia", "Tipologia", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Tipologia dell'immobile", "options" => $options]);
        
        // Campo testuale: comune
        $wnd->AddTextField("comune", "Comune", ["required" => true, "bottomLabel" => "*Comune dell'immobile (codice ISTAT)", "suggest"=>array("template"=>"#codice#","url"=>$this->taskManagerUrl."?task=GetSicarListaCodiciIstat")],false);
       
        // Campo testuale: ubicazione
        $options= AA_Sicar_Const::GetListaUbicazioni();
        $wnd->AddSelectField("ubicazione", "Ubicazione", ["required" => true,"validateFunction"=>"IsSelected","gravity"=>2, "bottomLabel" => "*Ubicazione dell'immobile", "options" => $options]);
        
        // Campo testuale: zona urbanistica
        $options = AA_Sicar_Const::GetListaZoneUrbanistiche();
        $wnd->AddSelectField("zona_urbanistica", "Zona urb.", ["required" => true,"gravity"=>2, "bottomLabel" => "*Zona urbanistica dell'immobile", "options" => $options],false);
        
        // Campo numerico: piani
        $wnd->AddTextField("piani", "Piani", ["required" => true,"gravity"=>1,"validateFunction"=>"IsPositive", "bottomLabel" => "*Numero di piani dell'immobile"],false);

        // Campo testuale: indirizzo
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>3, "bottomLabel" => "*Indirizzo dell'immobile comprensivo del numero civico."]);
        
        // Campo testuale: geolocalizzazione
        $wnd->AddTextField("geolocalizzazione", "Geoloc.", ["required" => true,"gravity"=>1, "bottomLabel" => "*Geolocalizzazione dell'immobile (latitudine e longitudine) in gradi decimali separate da virgola (es.: 39.225048459422766, 9.102739130435575)","placeholder"=>"es. 39.225048459422766, 9.102739130435575"]);

        //Dati catastali
        $catasto = new AA_FieldSet("AA_SICAR_CATASTO","Dati catastali");

        //sezione catasto
        $label="Sezione";
        $options=array(
            array("id"=>0,"value"=>"Catasto urbano"),
            array("id"=>1,"value"=>"Catasto terreni")
        );
        $catasto->AddRadioField("SezioneCatasto",$label,array("options"=>$options,"bottomLabel"=>"*Indicare la sezione in cui è accatastato l'immobile.", "value"=>0,"required"=>true));
        //foglio catasto
        $label="Foglio";
        $catasto->AddTextField("FoglioCatasto",$label,array("tooltip"=>"*Inserire il numero del foglio in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."));
        
        //mappale catasto
        $label="Mappale";
        $catasto->AddTextField("MappaleCatasto",$label,array("tooltip"=>"*Inserire il numero di mappale in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //particella catasto
        $label="Particella";
        $catasto->AddTextField("ParticellaCatasto",$label,array("tooltip"=>"*Inserire il numero della particella in cui è accastato l'immobile.", "required"=>true,"placeholder"=>"..."),false);

        //subalterno
        $label="Subalterno";
        $catasto->AddTextField("Subalterno",$label,array("required"=>true,"tooltip"=>"*Inserire il numero del sublaternose presente.", "placeholder"=>"..."),false);

        $wnd->AddGenericObject($catasto);

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("AddNewImmobileSicar");
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    //restituisce la lista dei comuni
    public function Task_GetSicarListaCodiciIstat($task)
    {
        AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
       
        $filter=$_REQUEST["filter"];

        $db=new AA_Database();
        $query="SELECT codice,comune FROM ".AA_Sicar_Const::AA_DBTABLE_CODICI_ISTAT;
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

    // Task per la restituzione della finestra di dialogo di aggiunta nuovo alloggio
    public function Task_GetSicarAddNewDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi alloggi", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarAddNewAlloggioDlg(), true);
        return true;
    }

    // Task per la restituzione della finestra di dialogo di filtro bozze
    public function Task_GetSicarBozzeFilterDlg($task)
    {
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->TemplateBozzeFilterDlg($_REQUEST), true);
        return true;
    }
     //Task search immobili
     public function Task_GetSicarSearchImmobiliDlg($task)
     {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per visualizzare gli immobili", false);
            return false;
        }
 
         $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
         $task->SetContent($this->Template_GetSicarSearchImmobiliDlg(),true);
         return true;
     }

     //Template dlg search immobili
    public function Template_GetSicarSearchImmobiliDlg()
    {
        $id=static::AA_UI_WND_SEARCH_IMMOBILI;
        
        $wnd=new AA_GenericWindowTemplate($id, "Ricerca immobili", $this->id);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(640);
        
        $wnd->AddView($this->Template_DatatableSearchImmobili($id));
        
        return $wnd;
    }

    //Template data table SearchImmobili
    public function Template_DatatableSearchImmobili($id="")
    {
        if($id=="") $id=static::AA_UI_WND_SEARCH_IMMOBILI;
        $id.="_".static::AA_UI_TABLE_SEARCH_IMMOBILI;
        
        //form di destinazione
        if(!empty($_REQUEST['form'])) $form=trim($_REQUEST['form']);

        //campo di destinazione
        if(!empty($_REQUEST['field_id'])) $field_id=trim($_REQUEST['field_id']);
        if(!empty($_REQUEST['field_desc'])) $field_desc=trim($_REQUEST['field_desc']);

        $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $toolbar=new AA_JSON_Template_Toolbar("",array("height"=>38,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));

        $filter="";

        if($filter=="") $filter="<span class='AA_Label AA_Label_LightOrange'>tutti</span>";
        
        //$toolbar->addElement(new AA_JSON_Template_Generic($id."_FilterLabel",array("view"=>"label","align"=>"left","label"=>"<div>Visualizza: ".$filter."</div>")));
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //$toolbar->addElement(new AA_JSON_Template_Generic("",array("view"=>"spacer")));
        
        //Aggiunta
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) $filter=array("refresh"=>1,"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc);
        else $filter=array("refresh"=>1,"refresh_obj_id"=>$id);
        $modify_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-filter-cog",
             "label"=>"Aggiungi",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Aggiungi un nuovo immobile",
             "click"=>"AA_MainApp.curModule.setRuntimeValue('" . $id . "','filter_data',".json_encode($filter)."); AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewImmobileDlg\",postParams: AA_MainApp.curModule.getRuntimeValue('" . $id . "','filter_data'), module: '" . $this->id . "'},'".$this->id."')"
        ));
        $toolbar->AddElement($modify_btn);
        
        $layout->addRow($toolbar);

        #criteri----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        $immobili=AA_SicarImmobile::Search();
        $data=[];
        //$trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopTrashComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        //$modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopModifyComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) 
        {
            $select_icon="mdi mdi-cursor-pointer";
        }
        else
        {
            $ops="";
        }

        foreach($immobili as $curImmobile)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if(!empty($form) && !empty($field_id) && !empty($field_desc))
            {
                $select="try{if($$('".$form."')){ AA_MainApp.utils.callHandler('SicarSelectImmobile', {form:'".$form."', values:{'".$field_id."':'".$curImmobile->GetProp('id')."','".$field_desc."':'".$curImmobile->GetDisplayName()."'}},'".$this->GetId()."'); $$('".static::AA_UI_WND_SEARCH_IMMOBILI."_Wnd').close();}}catch(msg){console.error(msg)}";
                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Scegli' onClick=\"".$select."\"><span class='mdi ".$select_icon."'></span></a></div>";
                $data[]=array("id"=>$curImmobile->GetProp("id"),"descrizione"=>$curImmobile->GetDescrizione(),"indirizzo"=>$curImmobile->GetIndirizzo(),"comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curImmobile->GetProp("comune")),"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curImmobile->GetProp("id"),"descrizione"=>$curImmobile->GetDescrizione(),"indirizzo"=>$curImmobile->GetIndirizzo(),"comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curImmobile->GetProp("comune")));
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id."_TableSearch_".uniqid(),"Ricerca immobili",3,null,array("css"=>"AA_Header_DataTable"));
        else $template=new AA_GenericDatatableTemplate($id."_TableSearch_".uniqid(),"Ricerca immobili",4,null,array("css"=>"AA_Header_DataTable"));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(false);
        $template->SetHeaderHeight(38);

        /*
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewImmobileDlg");
            if(!empty($form) && !empty($field_id) && !empty($field_desc)) $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1,"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc)));
            else $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1,"refresh_obj_id"=>$id)));
        }*/

        $template->SetColumnHeaderInfo(0,"descrizione","<div style='text-align: center'>Descrizione</div>",250,"textFilter","int","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo</div>","fillspace","textFilter","text","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(2,"comune","<div style='text-align: center'>Comune</div>",250,"textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(3,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"ImmobiliTable");

        $template->SetData($data);

        $layout->AddRow($template);
        return $layout;
    }

    //Template filtro di ricerca bozze
    public function TemplateBozzeFilterDlg($params=array())
    {
        //Valori runtime
        $formData=array("stato_conservazione"=>$params["stato_conservazione"],"indirizzo"=>$params["indirizzo"],"comune"=>$params["comune"],"immobile_desc"=>$params["immobile_desc"],"immobile"=>$params["immobile"],"ids"=>$params['ids'],"id_assessorato"=>$params['id_assessorato'],"id_direzione"=>$params['id_direzione'],"struct_desc"=>$params['struct_desc'],"id_struct_tree_select"=>$params['id_struct_tree_select'],"nome"=>$params['nome'],"cestinate"=>$params['cestinate']);
        
        //Valori default
        if($params['struct_desc']=="") $formData['struct_desc']="Qualunque";
        if($params['id_assessorato']=="") $formData['id_assessorato']=0;
        if($params['id_direzione']=="") $formData['id_direzione']=0;
        if($params['id_servizio']=="") $formData['id_servizio']=0;
        if($params['cestinate']=="") $formData['cestinate']=0;
        if($params['nome']=="") $formData['nome']="";

        //Immobile
        if($params['immobile_desc']=="") $formData['immobile_desc']="Qualunque";
        if($params['immobile']=="") $formData['immobile']=0;
        if($params['indirizzo']=="") $formData['indirizzo']="";

        //stato conservazione
        if(empty($params['stato_conservazione'])) $formData['stato_conservazione']=-1;

        //Valori reset
        $resetData=array("stato_conservazione"=>-1,"indirizzo"=>"","comune"=>"","immobile_desc"=>"Qualunque","immobile"=>0,"ids"=>"","id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0, "struct_desc"=>"Qualunque","id_struct_tree_select"=>"","nome"=>"","cestinate"=>0);
        
        //Azioni da eseguire dopo l'applicazione del filtro
        $applyActions="module.refreshCurSection()";
        
        $dlg = new AA_GenericFilterDlg(static::AA_UI_PREFIX."_Bozze_Filter".uniqid(), "Parametri di ricerca per gli elementi in bozza",$this->GetId(),$formData,$resetData,$applyActions);
        
        $dlg->SetHeight(580);
                
        //Cestinate
        $dlg->AddSwitchBoxField("cestinate","Cestino",array("onLabel"=>"mostra","offLabel"=>"nascondi","bottomLabel"=>"*Mostra/nascondi le schede cestinate."));
      
        //Struttura
        $dlg->AddStructField(array("targetForm"=>$dlg->GetFormId()),array("select"=>true),array("bottomLabel"=>"*Filtra in base alla struttura di gestione."));
    
        //titolo
        $dlg->AddTextField("nome","Descrizione",array("bottomLabel"=>"*Filtra in base alla descrizione dell'alloggio", "placeholder"=>"..."));
 
        //comune
        $dlg->AddTextField("comune", "Comune", ["bottomLabel" => "*Comune dell'immobile (codice ISTAT)", "suggest"=>array("template"=>"#codice#","url"=>$this->taskManagerUrl."?task=GetSicarListaCodiciIstat")]);
       
        //immobile
        $dlgParams = array("task" => "GetSicarSearchImmobiliDlg", "postParams" => array("form" => $dlg->GetFormId(),"field_id"=>"immobile","field_desc"=>"immobile_desc"));
        $dlg->AddSearchField("dlg",$dlgParams,$this->GetId(),["label"=>"Immobile","name"=>"immobile_desc", "bottomLabel" => "*Immobile di cui fa parte l'alloggio."]);

        //indirizzo
        $dlg->AddTextField("indirizzo","Indirizzo",array("bottomLabel"=>"*Filtra in base all'indirizzo dell'immobile di cui fa parte l'alloggio", "placeholder"=>"..."));
 
        //Stato conservazione
        $options=[0=>array("id"=>-1,"value"=>"Qualunque")];
        $options=array_merge($options,AA_Sicar_Const::GetListaStatiConservazioneAlloggio());
        AA_Log::Log(__METHOD__." - ".print_r($options,true),100);
        $dlg->AddSelectField("stato_conservazione","Stato di conservazione",array("bottomLabel"=>"*Filtra in base allo stato di conservazione dell'alloggio", "options"=>$options));
        
        //ids
        $dlg->AddTextField("ids","Identificativi",array("bottomLabel"=>"*Filtra in base a uno o piu' identificativi (separati da virgola es. 101,105,205).", "placeholder"=>"..."));

        $dlg->SetApplyButtonName("Filtra");

        return $dlg->GetObject();
    }
     // Task per la restituzione della finestra di dialogo di aggiunta nuovo immobile
     public function Task_GetSicarAddNewImmobileDlg($task)
     {
         if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
             $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
             $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi immobili", false);
             return false;
         }
         $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
         $task->SetContent($this->Template_GetSicarAddNewImmobileDlg(), true);
         return true;
     }
    
    // Task per la finestra di modifica dati generali alloggio
    public function Task_GetSicarModifyDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $object = new AA_SicarAlloggio($_REQUEST['id'], $this->oUser);
        if (!$object->IsValid()) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi di modifica dell'elemento", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarModifyDlg($object),true);
        return true;
    }

    // Template per la finestra di dialogo di modifica alloggio
    public function Template_GetSicarModifyDlg($object)
    {
        return $this->Template_GetSicarModifyAlloggioDlg($object);
    }

    // Metodi specifici del modulo SICAR (Alloggi)
    public function GetAlloggio($id = 0, $user = null)
    {
        return AA_SicarAlloggio::Load($id, $user);
    }
    
    public function SearchAlloggi($params = array(), $user = null)
    {
        $params['class'] = 'AA_SicarAlloggio';
        return AA_SicarAlloggio::Search($params, $user);
    }
    
    public function AddAlloggio($alloggio = null, $user = null)
    {
        if ($alloggio instanceof AA_SicarAlloggio) {
            return AA_SicarAlloggio::AddNew($alloggio, $user, true);
        }
        return false;
    }
    
    // Task per ottenere le tipologie
    public function Task_GetSicarTipologie($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
            $options = AA_Sicar_Const::GetListaTipologie();
            if (!empty($options)) {
                $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
                $task->SetContent(json_encode($options), true);
                return true;
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nel recupero delle tipologie", false);
            return false;
    }
    
    // Task per ottenere le ubicazioni
    public function Task_GetSicarUbicazioni($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        $comune = isset($_REQUEST['comune']) ? $_REQUEST['comune'] : "";
        
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM aa_sicar_ubicazioni WHERE attivo = 1";
        if (!empty($comune)) {
            $query .= " AND comune = '" . addslashes($comune) . "'";
        }
        $query .= " ORDER BY ordine, descrizione";
        
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            $options = array();
            foreach ($rs as $row) {
                $options[] = array("id" => $row['id'], "value" => $row['descrizione']);
            }
            
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent(json_encode($options), true);
            return true;
        }
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("Errore nel recupero delle ubicazioni", false);
        return false;
    }
    
    // Task per ottenere le zone urbanistiche
    public function Task_GetSicarZoneUrbanistiche($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        $comune = isset($_REQUEST['comune']) ? $_REQUEST['comune'] : "";
        
        $db = new AA_Database();
        $query = "SELECT codice, descrizione FROM aa_sicar_zone_urbanistiche WHERE attivo = 1";
        if (!empty($comune)) {
            $query .= " AND comune = '" . addslashes($comune) . "'";
        }
        $query .= " ORDER BY ordine, descrizione";
        
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            $options = array();
            foreach ($rs as $row) {
                $options[] = array("id" => $row['codice'], "value" => $row['descrizione']);
            }
            
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent(json_encode($options), true);
            return true;
        }
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("Errore nel recupero delle zone urbanistiche", false);
        return false;
    }
    
    // Task per ottenere i comuni
    public function Task_GetSicarComuni($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // In un'implementazione reale, questa funzione dovrebbe caricare i comuni da una tabella
        // Per ora restituiamo alcuni comuni di esempio
        $comuni = array(
            array("id" => "092009", "value" => "Cagliari"),
            array("id" => "092003", "value" => "Alghero"),
            array("id" => "092015", "value" => "Carbonia"),
            array("id" => "092025", "value" => "Iglesias"),
            array("id" => "092035", "value" => "Nuoro"),
            array("id" => "092050", "value" => "Oristano"),
            array("id" => "092051", "value" => "Olbia"),
            array("id" => "092064", "value" => "Sassari")
        );
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent(json_encode($comuni), true);
        return true;
    }
    
    // Task per esportare in CSV
    public function Task_ExportSicarCsv($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        $searchParams = array(
            'class' => 'AA_SicarAlloggio',
            'status' => AA_Const::AA_STATUS_PUBBLICATA
        );
        
    $alloggi = AA_SicarAlloggio::Search($searchParams, $this->oUser);
        
        if (is_array($alloggi)) {
            $csv = "ID;Descrizione;Tipologia;Comune;Indirizzo;Piani;Stato\n";
            
            foreach ($alloggi as $alloggio) {
                $tipologia = $this->GetTipologiaDesc($alloggio->GetTipologia());
                $status = $this->GetStatusDesc($alloggio->GetStatus());
                
                $csv .= $alloggio->GetId() . ";" .
                       str_replace(";", ",", $alloggio->GetName()) . ";" .
                       str_replace(";", ",", $tipologia) . ";" .
                       $alloggio->GetComune() . ";" .
                       str_replace(";", ",", $alloggio->GetIndirizzo()) . ";" .
                       $alloggio->GetPiani() . ";" .
                       $status . "\n";
            }
            
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($csv, true);
            return true;
        }
        
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("Nessun dato da esportare", false);
        return false;
    }
    
    // Task per aggiungere un nuovo alloggio
    public function Task_AddNewAlloggioSicar($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi alloggi", false);
            return false;
        }
        
        // Utilizza il metodo generico della classe base
        return $this->Task_GenericAddNew($task, $_REQUEST);
    }

    //Task NavBarContent
    public function Task_GetNavbarContent($task)
    {
        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR))
        {
            $_REQUEST['section']=static::AA_UI_PREFIX."_".static::AA_UI_PUBBLICATE_BOX;
        }
        
        return $this->Task_GetGenericNavbarContent($task,$_REQUEST);
    }

    // Task per aggiungere un nuovo immobile
    public function Task_AddNewImmobileSicar($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi alloggi", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun dato indicato per il nuovo immobile", false);
            return false;
        }

        if(empty($_REQUEST['catasto']))
        {
            $catasto=array();
            if(empty($_REQUEST['SezioneCatasto']))
            {
                $catasto['SezioneCatasto']=0;
            }
            $catasto['SezioneCatasto']=$_REQUEST['SezioneCatasto'];

            if(empty($_REQUEST['FoglioCatasto']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare il foglio catastale", false);
                return false;
            }
            $catasto['FoglioCatasto']=$_REQUEST['FoglioCatasto'];

            if(empty($_REQUEST['MappaleCatasto']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare il mappale catastale", false);
                return false;
            }
            $catasto['MappaleCatasto']=$_REQUEST['MappaleCatasto'];

            if(empty($_REQUEST['ParticellaCatasto']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare la particella catastale", false);
                return false;
            }
            $catasto['ParticellaCatasto']=$_REQUEST['ParticellaCatasto'];

            if(empty($_REQUEST['Subalterno']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre indicare il subalterno", false);
                return false;
            }
            $catasto['Subalterno']=$_REQUEST['Subalterno'];
           
        }
        else
        {
            $catasto=json_decode($_REQUEST['catasto'],true);
            if(empty($catasto))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("I dati catastali non sono correttamente formattati", false);
                return false;
            }
        }

        $_REQUEST['catasto']=json_encode($catasto);
       
        $immobile = new AA_SicarImmobile($_REQUEST);
        $validate=$immobile->Validate();
        if(sizeof($validate)>0)
        {
            AA_Log::Log(__METHOD__." - Sono stati trovati i seguenti errori: ".print_r($validate,true),100);
            $error="Sono state riscontrate le seguenti criticita': <br>";
            foreach($validate as $curError)
            {
                $error.="<li>".$curError."</li>";
            }

            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$immobile->Sync($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiunta dell'immobile", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Immobile aggiunto con successo", false);
        return true;
    }
    
    // Task per aggiornare un alloggio
    public function Task_UpdateSicar($task)
    {
        
        
        // Verifica che l'utente abbia i permessi per modificare gli alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare gli alloggi", false);
            return false;
        }
        
        // Verifica che l'utente abbia i permessi di scrittura sull'oggetto specifico
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $alloggio = new AA_SicarAlloggio($id, $this->oUser);
            if ($alloggio->IsValid()) {
                if (($alloggio->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("L'utente corrente non ha i permessi di modifica dell'alloggio", false);
                    return false;
                }
            }
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo non presente, aggiornamento non possibile.", false);
            return false;
        }
        
        $alloggio->Parse($_REQUEST);

        if(!$alloggio->Update($this->oUser,true,"Aggiornamento dati generali"))
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
    
    // Task per eliminare un alloggio
    public function Task_DeleteSicar($task)
    {
        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per eliminare l'elemento.", false);
            return false;
        }

        return $this->Task_GenericDeleteObject($task,$_REQUEST);
    }
    
    // Task per pubblicare un alloggio
    public function Task_PublishSicar($task)
    {
         return $this->Task_GenericPublishObject($task,$_REQUEST);
    }
    
    // Task per cestinare un alloggio
    public function Task_TrashSicar($task)
    {
        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR))
        {
            
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per cestinare l'elemento.", false);

            return false;
        }

        return $this->Task_GenericTrashObject($task,$_REQUEST);
    }
    
    // Task per ripristinare un alloggio
    public function Task_ResumeSicar($task)
    {
        return $this->Task_GenericResumeObject($task,$_REQUEST);
    }
    
    // Task per riassegnare un alloggio
    public function Task_ReassignSicar($task)
    {
        return $this->Task_GenericReassignObject($task,$_REQUEST);
    }

    // Task per la restituzione della finestra di dialogo di cestinazione alloggio
    public function Task_GetSicarTrashDlg($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per cestinare gli alloggi");
            return false;
        }

        if ($_REQUEST['ids'] != "") {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericObjectTrashDlg($_REQUEST, "TrashSicar"), true);
            return true;
        } else {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.", false);
            return false;
        }
    }

    //Task publish organismo
    public function Task_GetSicarPublishDlg($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per pubblicare elementi.");
            return false;
        }
        
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericPublishObjectDlg($_REQUEST,"PublishSicar"),true);
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
    public function Task_GetSicarReassignDlg($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Gecop_Const::AA_USER_FLAG_GECOP))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per riassegnare elementi.");
            return false;
        }

        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericReassignObjectDlg($_REQUEST,"ReassignSicar"),true);
            return true;
        }    
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.",false);
            return false;
        }
    }

    // Task per la restituzione della finestra di dialogo di ripristino alloggio
    public function Task_GetSicarResumeDlg($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per ripristinare gli alloggi");
            return false;
        }

        if ($_REQUEST['ids'] != "") {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent($this->Template_GetGenericResumeObjectDlg($_REQUEST, "ResumeSicar"), true);
            return true;
        } else {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.", false);
            return false;
        }
    }

    //Task dialogo elimina
    public function Task_GetSicarDeleteDlg($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());
        
        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per eliminare elementi.");
            return false;
        }
        if($_REQUEST['ids']!="")
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent( $this->Template_GetGenericObjectDeleteDlg($_REQUEST,"DeleteSicar"),true);
            return true;
        }    
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativi non presenti.",false);
            return false;
        }
    }

     //Template detail (da specializzare)
    public function TemplateSection_Detail($params)
    {
        //Gestione dei tab
        //$id=static::AA_UI_PREFIX."_Detail_Generale_Tab_".$params['id'];
        //$params['DetailOptionTab']=array(array("id"=>$id, "value"=>"Generale","tooltip"=>"Dati generali","template"=>"TemplateGecopDettaglio_Generale_Tab"));
        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $params['readonly']=true;
        
        $params['MultiviewEventHandlers']=array("onViewChange"=>array("handler"=>"onDetailViewChange"));

        //$params['disable_SaveAsPdf']=true;
        //$params['disable_SaveAsCsv']=true;
        //$params['disable_trash']=true;
        //$params['disable_public_trash']=true;

        if(!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $params['disable_MenuAzioni']=true;
        
        $detail = $this->TemplateGenericSection_Detail($params);

        return $detail;
    }

    // Template dettaglio generale alloggio
    public function TemplateSicarDettaglio_Generale_Tab($object = null)
    {
        $id = static::AA_UI_PREFIX . "_" . static::AA_ID_SECTION_DETAIL . "_" . static::AA_UI_DETAIL_GENERALE_BOX;
        if (!($object instanceof AA_SicarAlloggio)) {
            return new AA_JSON_Template_Template($id, array("template" => "Dati non validi"));
        }

        $rows_fixed_height=50;
        $canModify = (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0 && $this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR));

        $toolbar = new AA_JSON_Template_Toolbar("", array("height" => 32, "type" => "clean", "borderless" => true));
        $toolbar->AddElement(new AA_JSON_Template_Generic("", array("width" => 120)));
        $toolbar->AddElement(new AA_JSON_Template_Generic());

        $layout = $this->TemplateGenericDettaglio_Header_Generale_Tab($object, $id, $toolbar, $canModify);

        // stato conservazione
        $value = "<span class='AA_Label AA_Label_LightYellow'>" . $object->GetStatoConservazione(true) . "</span>";
        $stato_conservazione = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Stato conservazione:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //tipologia utilizzo
        $value = "<span class='AA_Label AA_Label_LightYellow'>" . $object->GetTipologiaUtilizzo() . "</span>";
        $tipologia_utilizzo = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Tipo utilizzo:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //ultima ristrutturazione
        $value = "<span class='AA_Label AA_Label_LightYellow'>" . $object->GetAnnoRistrutturazione() . "</span>";
        $ultima_ristrutturazione = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Anno ultima ristrutturazione:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        // superficie
        $superficie="";
        if(!empty($object->GetSuperficieNetta())) $superficie="netta: ".$object->GetSuperficieNetta();
        if(!empty($object->GetSuperficieNetta())) 
        {
            if(!empty($superficie)) $superficie.="<br>"; 
            $superficie="utile abitabile: ".$object->GetSuperficieUtileAbitabile();
        }
        $value = "<span class='AA_Label AA_Label_LightYellow'>" . $superficie . "</span>";
        $superficie = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Superficie:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //piano
        $piano="n.d.";
        $value = "<span class='AA_Label AA_Label_LightYellow'>" . $object->GetPiano() . "</span>";
        $piano = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Piano:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //ascensore
        $val="No";
        $color="LightRed";
        if(!empty($object->GetAscensore())) 
        {
            $val="Si";
            $color="LightGreen";
        }
        $value = "<span class='AA_Label AA_Label_$color'>" . $val. "</span>";
        $ascensore = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Servito da ascensore:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //condominio misto
        $val="No";
        $color="LightRed";
        if(!empty($object->GetCondominioMisto()))
        {
            $val="Si";
            $color="LightGreen";
        }
        $value = "<span class='AA_Label AA_Label_$color'>" . $val. "</span>";
        $condominio = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Condominio misto:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));
        
        //disabile
        $val="No";
        $color="LightRed";
        if(!empty($object->GetFruibileDis()))
        {
            $val="Si";
            $color="LightGreen";
        }
        $value = "<span class='AA_Label AA_Label_$color'>" . $val. "</span>";
        $fruibile_dis = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Fruibile da disabile:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));
        
        //prima riga
        $riga=new AA_JSON_Template_Layout("",array("height"=>$rows_fixed_height,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $riga->AddCol($stato_conservazione);
        $riga->AddCol($tipologia_utilizzo);
        $riga->AddCol($ultima_ristrutturazione);
        $riga->AddCol($superficie);
        $riga->AddCol($piano);
        $riga->AddCol($condominio);
        $riga->AddCol($ascensore);
        $riga->AddCol($fruibile_dis);
        $layout->AddRow($riga);
        
        //seconda riga
        $riga=new AA_JSON_Template_Layout("",array("gravity"=>1,"css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        $layout_right=new AA_JSON_Template_Layout("",array("gravity"=>1,"type"=>"clean"));
        
        //titolo
        $value = $object->GetName();
        $titolo=new AA_JSON_Template_Template("",array(
            "maxHeight"=>100,
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Descrizione:","value"=>$value)
        ));
        
        //immobile
        $immobile_obj=$object->GetImmobile();
        $immobile=new AA_JSON_Template_Template("",array(
            "maxHeight"=>100,
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Immobile:","value"=>$immobile_obj->GetDescrizione()."<br>".$immobile_obj->GetIndirizzo()." (".AA_Sicar_Const::GetComuneDescrFromCodiceIstat($immobile_obj->GetComune()).") <a href='https://www.google.com/maps/search/?api=1&query=".$immobile_obj->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a><br><i>".$immobile_obj->GetTipologia()."</i>")
        ));
        $riga->addCol($titolo);

        $layout_right->addRow($immobile);
        $riga->addCol($layout_right);

        $layout->AddRow($riga);

        //note
        $value = $object->GetNote();
        $note=new AA_JSON_Template_Template("",array(
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Note:","value"=>$value)
        ));
        $layout->AddRow($note);

        return $layout;
    }

    //Template section immobili
    public function TemplateSection_Immobili($params=array())
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_IMMOBILI;
        $canModify=false;

        #immobili----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //$layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $immobili=AA_SicarImmobile::Search($params);
        $data=[];
        //$trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopTrashComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        //$modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopModifyComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) 
        {
            $select_icon="mdi mdi-cursor-pointer";
        }
        else
        {
            $ops="";
        }

        foreach($immobili as $curImmobile)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if(!empty($form) && !empty($field_id) && !empty($field_desc))
            {
                $select="try{if($$('".$form."')){ AA_MainApp.utils.callHandler('SicarSelectImmobile', {form:'".$form."', values:{'".$field_id."':'".$curImmobile->GetProp('id')."','".$field_desc."':'".$curImmobile->GetDisplayName()."'}},'".$this->GetId()."'); $$('".static::AA_UI_WND_SEARCH_IMMOBILI."_Wnd').close();}}catch(msg){console.error(msg)}";
                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Scegli' onClick=\"".$select."\"><span class='mdi ".$select_icon."'></span></a></div>";
                $data[]=array("id"=>$curImmobile->GetProp("id"),"descrizione"=>$curImmobile->GetDescrizione(),"indirizzo"=>$curImmobile->GetIndirizzo(),"comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curImmobile->GetProp("comune")),"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curImmobile->GetProp("id"),"descrizione"=>$curImmobile->GetDescrizione(),"indirizzo"=>$curImmobile->GetIndirizzo(),"comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curImmobile->GetProp("comune")));
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id,"Gestione immobili",3,null,array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"Gestione immobili",4,null,array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(false);
        $template->SetHeaderHeight(38);

        /*
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewImmobileDlg");
            if(!empty($form) && !empty($field_id) && !empty($field_desc)) $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1,"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc)));
            else $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1,"refresh_obj_id"=>$id)));
        }*/

        $template->SetColumnHeaderInfo(0,"descrizione","<div style='text-align: center'>Descrizione</div>",250,"textFilter","int","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo</div>","fillspace","textFilter","text","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(2,"comune","<div style='text-align: center'>Comune</div>",250,"textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(3,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"ImmobiliTable");

        $template->SetData($data);

        //$layout->AddRow($template);

        return $template;
    }

    // Metodi di utilità
    private function GetTipologiaDesc($tipologia_id)
    {
        $tipologie = AA_Sicar_Const::GetListaTipologie();
        foreach ($tipologie as $row) {
            if ($row['id'] == $tipologia_id) {
                return $row['value'];
            }
        }
        return "Non specificato";
    }
    
    private function GetStatusDesc($status)
    {
        if (($status & AA_Const::AA_STATUS_PUBBLICATA) > 0) {
            return "Pubblicato";
        } elseif (($status & AA_Const::AA_STATUS_BOZZA) > 0) {
            return "Bozza";
        } elseif (($status & AA_Const::AA_STATUS_CESTINATA) > 0) {
            return "Cestinato";
        } elseif (($status & AA_Const::AA_STATUS_REVISIONATA) > 0) {
            return "In revisione";
        }
        
        return "Non specificato";
    }
}

/**
 * Classe AA_SicarAlloggio - rappresenta un alloggio associato ad un immobile
 */
class AA_SicarAlloggio extends AA_Object_V2
{
    const AA_DBTABLE_DATA = "aa_sicar_data";
    static protected $AA_DBTABLE_OBJECTS="aa_sicar_objects";

    // Proprietà dell'alloggio
    protected $nImmobile = 0;
    protected $sDescrizione = "";
    protected $sTipologiaUtilizzo = "";
    protected $sStatoConservazione = "";
    protected $nAnnoRistrutturazione = 0;
    protected $bCondominioMisto = false;
    protected $nSuperficieNetta = 0.0;
    protected $nSuperficieUtileAbitabile = 0.0;
    protected $nPiano = 0;
    protected $bAscensore = false;
    protected $bFruibileDis = false;
    protected $sNote = "";
    protected $aGestione = array();
    protected $aProprieta = array();
    protected $aStato = array();

    public function __construct($id = 0, $user = null, $bLoadData = true, $bCheckPerms = true)
    {
        $this->sDbDataTable = self::AA_DBTABLE_DATA;

        // Bind proprietà-campi
        $this->SetBind("immobile", "immobile", true);
        $this->SetBind("tipologia_utilizzo", "tipologia_utilizzo", true);
        $this->SetBind("stato_conservazione", "stato_conservazione", true);
        $this->SetBind("anno_ristrutturazione", "anno_ristrutturazione", true);
        $this->SetBind("condominio_misto", "condominio_misto", true);
        $this->SetBind("superficie_netta", "superficie_netta", true);
        $this->SetBind("superficie_utile_abitabile", "superficie_utile_abitabile", true);
        $this->SetBind("piano", "piano", true);
        $this->SetBind("ascensore", "ascensore", true);
        $this->SetBind("fruibile_dis", "fruibile_dis", true);
        $this->SetBind("note", "note", true);
        
        //da strutturare
        $this->SetBind("gestione", "gestione", true);
        $this->SetBind("proprieta", "proprieta", true);
        $this->SetBind("stato", "stato", true);

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

    public function GetCondominioMisto() { return $this->GetProp("condominio_misto"); }
    public function SetCondominioMisto($var = false) { $this->SetProp("condominio_misto", $var); $this->SetChanged(true); return true; }

    public function GetSuperficieNetta() { return $this->GetProp("superficie_netta"); }
    public function SetSuperficieNetta($var = 0.0) { $this->SetProp("superficie_netta", $var); $this->SetChanged(true); return true; }

    public function GetSuperficieUtileAbitabile() { return $this->GetProp("superficie_utile_abitabile"); }
    public function SetSuperficieUtileAbitabile($var = 0.0) { $this->SetProp("superficie_utile_abitabile", $var); $this->SetChanged(true); return true; }

    public function GetPiano() { return $this->GetProp("piano"); }
    public function SetPiano($var = 0) { $this->SetProp("piano", $var); $this->SetChanged(true); return true; }

    public function GetAscensore() { return $this->GetProp("ascensore"); }
    public function SetAscensore($var = false) { $this->SetProp("ascensore", $var); $this->SetChanged(true); return true; }

    public function GetFruibileDis() { return $this->GetProp("fruibile_dis"); }
    public function SetFruibileDis($var = false) { $this->SetProp("fruibile_dis", $var); $this->SetChanged(true); return true; }

    public function GetGestione() { return $this->GetProp("gestione"); }
    public function SetGestione($var = array()) { $this->SetProp("gestione", $var); $this->SetChanged(true); return true; }

    public function GetProprieta() { return $this->GetProp("proprieta"); }
    public function SetProprieta($var = array()) { $this->SetProp("proprieta", $var); $this->SetChanged(true); return true; }

    public function GetStato() { return $this->GetProp("stato"); }
    public function SetStato($var = array()) { $this->SetProp("stato", $var); $this->SetChanged(true); return true; }

    public function GetNote() { return $this->GetProp("note"); }
    public function SetNote($var = "") { $this->SetProp("note", $var); $this->SetChanged(true); return true; }

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
        if (!is_null($this->GetAnnoRistrutturazione()) && intval($this->GetAnnoRistrutturazione()) < 0) {
            $errors[] = "Anno di ristrutturazione non valido";
        }
        if (!is_numeric($this->GetSuperficieNetta()) || floatval($this->GetSuperficieNetta()) < 0) {
            $errors[] = "Superficie netta non valida";
        }
        if (!is_numeric($this->GetSuperficieUtileAbitabile()) || floatval($this->GetSuperficieUtileAbitabile()) < 0) {
            $errors[] = "Superficie utile abitabile non valida";
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
        parent::Parse($params, $bOnlyData);

        if (isset($params['immobile'])) { $this->SetImmobile($params['immobile']); }
        if (isset($params['tipologia_utilizzo'])) { $this->SetTipologiaUtilizzo($params['tipologia_utilizzo']); }
        if (isset($params['stato_conservazione'])) { $this->SetStatoConservazione($params['stato_conservazione']); }
        if (isset($params['anno_ristrutturazione'])) { $this->SetAnnoRistrutturazione($params['anno_ristrutturazione']); }
        if (isset($params['condominio_misto'])) { $this->SetCondominioMisto($params['condominio_misto']); }
        if (isset($params['superficie_netta'])) { $this->SetSuperficieNetta($params['superficie_netta']); }
        if (isset($params['superficie_utile_abitabile'])) { $this->SetSuperficieUtileAbitabile($params['superficie_utile_abitabile']); }
        if (isset($params['piano'])) { $this->SetPiano($params['piano']); }
        if (isset($params['ascensore'])) { $this->SetAscensore($params['ascensore']); }
        if (isset($params['fruibile_dis'])) { $this->SetFruibileDis($params['fruibile_dis']); }
        if (isset($params['note'])) { $this->SetNote($params['note']); }
        if (isset($params['gestione'])) { $this->SetGestione($params['gestione']); }
        if (isset($params['proprieta'])) { $this->SetProprieta($params['proprieta']); }
        if (isset($params['stato'])) { $this->SetStato($params['stato']); }

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
}
