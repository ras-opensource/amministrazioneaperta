<?php
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

    /**
     * Restituisce la lista delle tipologie di programma di finanziamento
     * @return array
     */

    const AA_DBTABLE_TIPOLOGIE_PROG_FIN = 'aa_sicar_tipologie_finanziamenti';
    public static function GetListaTipologieProgFinanziamento($bSimpleArray=false)
    {
        $options = array();
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM ".self::AA_DBTABLE_TIPOLOGIE_PROG_FIN." ORDER BY descrizione";
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach ($rs as $row) {
                if(!$bSimpleArray) $options[] = array("id" => $row['id'], "value" => $row['descrizione']);
                else $options[$row['id']] = $row['descrizione'];
            }
        }
        return $options;
    }

    /**
     * Restituisce la lista delle tipologie di immobile
     * @return array
     */

    const AA_DBTABLE_TIPOLOGIE_ENTE = 'aa_sicar_tipologie_ente';
    public static function GetListaTipologieEnte($bSimpleArray=false)
    {
        $options = array();
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM ".self::AA_DBTABLE_TIPOLOGIE_ENTE." ORDER BY descrizione";
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

    //stato lavori intervento
    const AA_DBTABLE_STATO_LAVORI_INTERVENTO = 'aa_sicar_stato_lavori';
    public static function GetListaStatoLavori($bSimpleArray=false)
    {
        $options = array();
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM ".self::AA_DBTABLE_STATO_LAVORI_INTERVENTO." ORDER BY descrizione";
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach ($rs as $row) {
                if(!$bSimpleArray) $options[] = array("id" => $row['id'], "value" => $row['descrizione']);
                else $options[$row['id']] = $row['descrizione'];
            }
        }
        return $options;
    }

     //tipo canone alloggio
    const AA_DBTABLE_TIPOLOGIE_CANONE = 'aa_sicar_tipologie_canone_alloggio';
    public static function GetListaTipologieCanoneAlloggio($bSimpleArray=false)
    {
        $options = array();
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM ".self::AA_DBTABLE_TIPOLOGIE_CANONE." ORDER BY descrizione";
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

    //lista tipologia occupazione
    const AA_DBTABLE_TIPOLOGIE_OCCUPAZIONE = 'aa_sicar_tipologie_occupazione';
    public static function GetListaTipologieOccupazione($bSimpleArray=false)
    {
        if(!$bSimpleArray)
        {
            $options=array(
                array("id"=>1,"value"=>"Assegnato"),
                array("id"=>2,"value"=>"Occupato"),
                array("id"=>3,"value"=>"Occupato con riserva"),
                array("id"=>4,"value"=>"Occupato abusivo")
            );
        }
        else
        {
            $options = array(
                1 => "Assegnato",
                2 => "Occupato",
                3 => "Occupato con riserva",
                4 => "Occupato abusivo"
            );
        }

        return $options;
    }

    //lista tipologia interventi
    const AA_DBTABLE_TIPOLOGIE_INTERVENTO = 'aa_sicar_tipologie_intervento';
    public static function GetListaTipologieIntervento($bSimpleArray=false)
    {
        $options = array();
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM ".self::AA_DBTABLE_TIPOLOGIE_INTERVENTO." ORDER BY descrizione";
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
        if ($db->Query($query) && $db->GetAffectedRows()>0) {
            $rs = $db->GetResultSet();
            return $rs[0]['comune'];
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile ottenere la descrizione del comune per il codice istat: " . $codice_istat, 100);
        }
        return "";
    }

    const DB_TABLE_IMMOBILI = 'aa_sicar_immobili';
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

    static public function GetListaFruibilitaDisabile()
    {
        return array(
            array("id"=>1,"value"=>"Non adattabile"),
            array("id"=>2,"value"=>"Adattabile"),
            array("id"=>3,"value"=>"Accessibile"),
            array("id"=>4,"value"=>"Visitabile"),
        );
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

