<?php
/**
 * Modulo SICAR - Sistema Informativo Catasto e Amministrazione Risorse
 * Classe AA_SicarImmobile per la gestione degli immobili
 */
include_once "config.php";

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

class AA_SicarEnte extends AA_GenericParsableDbObject
{
    // Tabella dati per gli enti
    static protected $dbDataTable="aa_sicar_enti";
    static protected $ObjectClass=__CLASS__;
    
    // Costruttore
    public function __construct($params = array())
    {
        
        // Imposta i binding tra proprietà e campi database
        $this->aProps['denominazione']="Nuovo ente";
        $this->aProps['tipologia']=0;
        $this->aProps['indirizzo']="";
        $this->aProps['web']="";
        $this->aProps['pec']="";
        $this->aProps['geolocalizzazione']="";
        $this->aProps['operatori']="";
        $this->aProps['note']="";
        
        //template view props
        $this->aTemplateViewProps['denominazione']=array("label"=>"Descrizione","type"=>"text","visible"=>true);
        $this->aTemplateViewProps['tipologia']=array("label"=>"Tipologia","type"=>"text","function"=>"GetTipologia","visible"=>true);
        $this->aTemplateViewProps['indirizzo']=array("label"=>"Indirizzo","type"=>"text","visible"=>true);
        $this->aTemplateViewProps['web']=array("label"=>"SitoWeb","type"=>"text","function"=>"GetSitoWebView","visible"=>true);
        $this->aTemplateViewProps['pec']=array("label"=>"PEC","type"=>"text","function"=>"GetPecView","visible"=>true);
        $this->aTemplateViewProps['operatori']=array("label"=>"Contatti","type"=>"text","function"=>"GetOperatoriView","visible"=>true);
        $this->aTemplateViewProps['note']=array("label"=>"Note","type"=>"textarea","visible"=>true);

        //areas, cols e rows di default
        $this->aTemplateViewProps['__areas']=array(
            array("denominazione", "denominazione","tipologia"),
            array("indirizzo","web","pec"),
            array("note", "note", "operatori"),
            array("note", "note", "operatori")
        );
        $this->aTemplateViewProps['__cols']=array("1fr","1fr","1fr");
        $this->aTemplateViewProps['__rows']=array("1fr","1fr","1fr","1fr");

        // Chiama il costruttore padre
        parent::__construct($params);
    }

    //lista operatori dell'ente
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
                AA_Log::Log(__METHOD__." - Errore nell'importazione degli operatori: ".$this->aProps['id'],100);
                return array();
            }
        }

        return $this->aProps['operatori'];
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

    public function GetOperatoriView()
    {
        $operatori=$this->GetOperatori(true);
        if(sizeof($operatori)==0) return "n.d.";
        
        $ret="<ul>";
        foreach($operatori as $op)
        {
            $ret.="<li>";
            if(!empty($op['nome']) || !empty($op['cognome'])) $ret.=trim($op['nome']." ".$op['cognome']);
            if(!empty($op['email'])) $ret.=" - <a href='mailto:".$op['email']."'>".$op['email']."</a>";
            if(!empty($op['telefono'])) $ret.=" - Tel: ".$op['telefono'];
            if(!empty($op['cf'])) $ret.=" - cf: ".$op['cf'];
            $ret.="</li>";
        }
        $ret.="</ul>";
        
        return $ret;
    }

    public function GetTemplateView($bRefresh=false)
    {
        return parent::GetTemplateView($bRefresh);
    }
        
    //lista degli enti
    public static function GetListaEnti()
    {
        $db = new AA_Database();
        $query = "SELECT * FROM ".static::$dbDataTable." ORDER BY descrizione";
        
        $return = array();

        if($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach($rs as $row) {
                $return[] = new AA_SicarEnte($row);
            }
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile ottenere la lista degli enti. - ".$db->GetErrorMessage(), 100);
        }

        return $return;
    }

    // Metodi Getter e Setter per le proprietà
    
    // Descrizione
    public function GetDenominazione()
    {
        return $this->GetProp("denominazione");
    }
    
    public function SetDenominazione($var = "")
    {
        $this->SetProp("denominazione", $var);
        return true;
    }

    // Tipologia
    public function GetTipologia($bAsText=true)
    {
        if(!$bAsText) return $this->GetProp("tipologia"); 
        
        $tipo=AA_Sicar_Const::GetListaTipologieEnte(true);
        if(!empty($tipo[$this->GetProp("tipologia")])) return $tipo[$this->GetProp("tipologia")];
        else return "n.d.";
        
    }
    
    public function SetTipologia($var = 0)
    {
        $this->SetProp("tipologia", $var);
        return true;
    }
     // Geolocalizzazione
    public function GetGeolocalizzazione()
    {
        return $this->GetProp("geolocalizzazione");
    }
     public function SetGeolocalizzazione($var="")
    {
        $this->SetProp("geolocalizzazione",$var);
    }

    // Contatti
    public function GetContatti($bAsObject=false)
    {
        if($bAsObject)
        {
            return array(
                "web"=>$this->GetSitoWeb(),
                "pec"=>$this->GetPec(),
            );
        }
        else return $this->GetSitoWebView()." - ".$this->GetPecView();
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

    // web
    public function GetSitoWeb()
    {
        return $this->GetProp("web");
    }

    public function SetSitoWeb($var = "")
    {
        $this->SetProp("web", $var);
        return true;
    }

    public function GetSitoWebView()
    {
        if(empty($this->GetProp("web"))) return "n.d.";

        return "<a href = '".$this->GetProp("web")."' target='_blank'>".$this->GetProp("web")."</a>";
    }

    public function GetPec()
    {
        return $this->GetProp("pec");
    }
    public function SetPec($var = "")
    {
        $this->SetProp("pec", $var);
        return true;
    }
    public function GetPecView()
    {
        if(empty($this->GetProp("pec"))) return "n.d.";

        return "<a href = 'mailto:".$this->GetProp("pec")."' target='_blank'>".$this->GetProp("pec")."</a>";
    }
    
    // Metodi per la validazione
    public function Validate()
    {
        $errors = array();
        
        // Validazione campi obbligatori
        if (empty($this->GetDenominazione())) {
            $errors[] = "La denominazione è obbligatoria";
        }
        
        if (empty($this->GetTipologia())) {
            $errors[] = "La tipologia è obbligatoria";
        }

        if (empty($this->GetIndirizzo())) {
            $errors[] = "L'indirizzo è obbligatorio";
        }

        if (empty($this->GetSitoWeb())) {
            $errors[] = "L'indirizzo web è obbligatorio";
        }
        elseif (!filter_var($this->GetSitoWeb(), FILTER_VALIDATE_URL)) {
            $errors[] = "L'indirizzo web non è valido";
        }

        if (empty($this->GetPec())) {
            $errors[] = "L'indirizzo PEC è obbligatorio";
        } elseif (!filter_var($this->GetPec(), FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'indirizzo PEC non è valido";
        }
        
        return $errors;
    }
    
    // Metodo per ottenere una rappresentazione testuale dell'immobile
    public function GetDisplayName()
    {
        $display = strval($this->GetDenominazione());
        if (!empty($this->GetIndirizzo())) {
            $display .= " - " . $this->GetIndirizzo();
        }

        return $display;
    }
    
    // Metodo per l'esportazione CSV
    protected function CsvDataHeader($separator = "|")
    {
        return "descrizione".$separator . "tipologia" . 
               $separator . "indirizzo" . $separator . "note";
    }
    
    protected function CsvData($separator = "|")
    {
        return $this->GetDenominazione().$separator . $this->GetTipologia() . 
               $separator .  str_replace("\n", ' ', $this->GetIndirizzo()) . 
               $separator . str_replace("\n", ' ', $this->GetNote());
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
        $object = new AA_SicarEnte($params);

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
            AA_Log::Log(__METHOD__ . " - ERRORE: l'utente corrente: " . $user->GetName() . " non ha i permessi per inserire nuovi elementi.", 100);
            return false;
        }

        if(!empty($this->Validate())) 
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: i dati dell'ente non sono validi.", 100);
            return false;
        }

        return parent::Sync();
    }
}

class AA_SicarFinanziamento extends AA_GenericParsableDbObject
{
    // Tabella dati per gli enti
    static protected $dbDataTable="aa_sicar_finanziamenti";
    static protected $ObjectClass=__CLASS__;
    
    // Costruttore
    public function __construct($params = array())
    {
        
        // Imposta i binding tra proprietà e campi database
        $this->aProps['denominazione']="Nuovo finanziamento";
        $this->aProps['note']="";
        
        //template view props
        $this->aTemplateViewProps['denominazione']=array("label"=>"Descrizione","type"=>"text","visible"=>true);
        $this->aTemplateViewProps['tipologia']=array("label"=>"Tipologia","type"=>"text","function"=>"GetTipologia","visible"=>true);
        $this->aTemplateViewProps['indirizzo']=array("label"=>"Indirizzo","type"=>"text","visible"=>true);
        $this->aTemplateViewProps['web']=array("label"=>"SitoWeb","type"=>"text","function"=>"GetSitoWebView","visible"=>true);
        $this->aTemplateViewProps['pec']=array("label"=>"PEC","type"=>"text","function"=>"GetPecView","visible"=>true);
        $this->aTemplateViewProps['operatori']=array("label"=>"Contatti","type"=>"text","function"=>"GetOperatoriView","visible"=>true);
        $this->aTemplateViewProps['note']=array("label"=>"Note","type"=>"textarea","visible"=>true);

        //areas, cols e rows di default
        $this->aTemplateViewProps['__areas']=array(
            array("denominazione", "denominazione","tipologia"),
            array("indirizzo","web","pec"),
            array("note", "note", "operatori"),
            array("note", "note", "operatori")
        );
        $this->aTemplateViewProps['__cols']=array("1fr","1fr","1fr");
        $this->aTemplateViewProps['__rows']=array("1fr","1fr","1fr","1fr");

        // Chiama il costruttore padre
        parent::__construct($params);
    }

    public function GetTemplateView($bRefresh=false)
    {
        return parent::GetTemplateView($bRefresh);
    }
        
    //lista degli enti
    public static function GetListaFinanziamenti()
    {
        $db = new AA_Database();
        $query = "SELECT * FROM ".static::$dbDataTable." ORDER BY descrizione";
        
        $return = array();

        if($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach($rs as $row) {
                $return[] = new AA_SicarFinanziamento($row);
            }
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile ottenere la lista dei finanziamenti. - ".$db->GetErrorMessage(), 100);
        }

        return $return;
    }

    // Metodi Getter e Setter per le proprietà
    
    // Descrizione
    public function GetDenominazione()
    {
        return $this->GetProp("denominazione");
    }
    
    public function SetDenominazione($var = "")
    {
        $this->SetProp("denominazione", $var);
        return true;
    }

    // Tipologia
    public function GetTipologia($bAsText=true)
    {
        if(!$bAsText) return $this->GetProp("tipologia"); 
        
        $tipo=AA_Sicar_Const::GetListaTipologieEnte(true);
        if(!empty($tipo[$this->GetProp("tipologia")])) return $tipo[$this->GetProp("tipologia")];
        else return "n.d.";
        
    }
    
    public function SetTipologia($var = 0)
    {
        $this->SetProp("tipologia", $var);
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
        if (empty($this->GetDenominazione())) {
            $errors[] = "La denominazione è obbligatoria";
        }
        
        if (empty($this->GetTipologia())) {
            $errors[] = "La tipologia è obbligatoria";
        }
        
        return $errors;
    }
    
    // Metodo per ottenere una rappresentazione testuale dell'immobile
    public function GetDisplayName()
    {
        $display = strval($this->GetDenominazione());

        return $display;
    }
    
    // Metodo per l'esportazione CSV
    protected function CsvDataHeader($separator = "|")
    {
        return "descrizione".$separator . "tipologia" . 
               $separator . "indirizzo" . $separator . "note";
    }
    
    protected function CsvData($separator = "|")
    {
        return $this->GetDenominazione().$separator . $this->GetTipologia() . 
               $separator . str_replace("\n", ' ', $this->GetNote());
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
        $object = new AA_SicarFinanziamento($params);

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
            AA_Log::Log(__METHOD__ . " - ERRORE: l'utente corrente: " . $user->GetName() . " non ha i permessi per inserire nuovi elementi.", 100);
            return false;
        }

        if(!empty($this->Validate())) 
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: i dati dell'ente non sono validi.", 100);
            return false;
        }

        return parent::Sync();
    }
}

class AA_SicarGraduatoria extends AA_GenericParsableDbObject
{
    // Tabella dati per gli enti
    static protected $dbDataTable="aa_sicar_graduatorie";
    static protected $ObjectClass=__CLASS__;
    
    // Costruttore
    public function __construct($params = array())
    {
        
        // Imposta i binding tra proprietà e campi database
        $this->aProps['denominazione']="Nuova graduatoria";
        $this->aProps['note']="";
        
        //template view props
        $this->aTemplateViewProps['denominazione']=array("label"=>"Descrizione","type"=>"text","visible"=>true);
        $this->aTemplateViewProps['tipologia']=array("label"=>"Tipologia","type"=>"text","function"=>"GetTipologia","visible"=>true);
        $this->aTemplateViewProps['indirizzo']=array("label"=>"Indirizzo","type"=>"text","visible"=>true);
        $this->aTemplateViewProps['web']=array("label"=>"SitoWeb","type"=>"text","function"=>"GetSitoWebView","visible"=>true);
        $this->aTemplateViewProps['pec']=array("label"=>"PEC","type"=>"text","function"=>"GetPecView","visible"=>true);
        $this->aTemplateViewProps['operatori']=array("label"=>"Contatti","type"=>"text","function"=>"GetOperatoriView","visible"=>true);
        $this->aTemplateViewProps['note']=array("label"=>"Note","type"=>"textarea","visible"=>true);

        //areas, cols e rows di default
        $this->aTemplateViewProps['__areas']=array(
            array("denominazione", "denominazione","tipologia"),
            array("indirizzo","web","pec"),
            array("note", "note", "operatori"),
            array("note", "note", "operatori")
        );
        $this->aTemplateViewProps['__cols']=array("1fr","1fr","1fr");
        $this->aTemplateViewProps['__rows']=array("1fr","1fr","1fr","1fr");

        // Chiama il costruttore padre
        parent::__construct($params);
    }

    public function GetTemplateView($bRefresh=false)
    {
        return parent::GetTemplateView($bRefresh);
    }
        
    //lista degli enti
    public static function GetListaGraduatoria()
    {
        $db = new AA_Database();
        $query = "SELECT * FROM ".static::$dbDataTable." ORDER BY descrizione";
        
        $return = array();

        if($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach($rs as $row) {
                $return[] = new AA_SicarGraduatoria($row);
            }
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile ottenere la lista dei finanziamenti. - ".$db->GetErrorMessage(), 100);
        }

        return $return;
    }

    // Metodi Getter e Setter per le proprietà
    
    // Descrizione
    public function GetDenominazione()
    {
        return $this->GetProp("denominazione");
    }
    
    public function SetDenominazione($var = "")
    {
        $this->SetProp("denominazione", $var);
        return true;
    }

    // Tipologia
    public function GetTipologia($bAsText=true)
    {
        if(!$bAsText) return $this->GetProp("tipologia"); 
        
        $tipo=AA_Sicar_Const::GetListaTipologieEnte(true);
        if(!empty($tipo[$this->GetProp("tipologia")])) return $tipo[$this->GetProp("tipologia")];
        else return "n.d.";
        
    }
    
    public function SetTipologia($var = 0)
    {
        $this->SetProp("tipologia", $var);
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
        if (empty($this->GetDenominazione())) {
            $errors[] = "La denominazione è obbligatoria";
        }
        
        if (empty($this->GetTipologia())) {
            $errors[] = "La tipologia è obbligatoria";
        }
        
        return $errors;
    }
    
    // Metodo per ottenere una rappresentazione testuale dell'immobile
    public function GetDisplayName()
    {
        $display = strval($this->GetDenominazione());

        return $display;
    }
    
    // Metodo per l'esportazione CSV
    protected function CsvDataHeader($separator = "|")
    {
        return "descrizione".$separator . "tipologia" . 
               $separator . "indirizzo" . $separator . "note";
    }
    
    protected function CsvData($separator = "|")
    {
        return $this->GetDenominazione().$separator . $this->GetTipologia() . 
               $separator . str_replace("\n", ' ', $this->GetNote());
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
        $object = new AA_SicarFinanziamento($params);

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
            AA_Log::Log(__METHOD__ . " - ERRORE: l'utente corrente: " . $user->GetName() . " non ha i permessi per inserire nuovi elementi.", 100);
            return false;
        }

        if(!empty($this->Validate())) 
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: i dati dell'ente non sono validi.", 100);
            return false;
        }

        return parent::Sync();
    }
}

class AA_SicarNucleo extends AA_GenericParsableDbObject
{
    // Tabella dati per gli enti
    static protected $dbDataTable="aa_sicar_nuclei";
    static protected $ObjectClass=__CLASS__;
    
    // Costruttore
    public function __construct($params = array())
    {
        
        // Imposta i binding tra proprietà e campi database
        $this->aProps['descrizione']="Nuovo nucleo";
        $this->aProps['cf']="";
        $this->aProps['comune']="";
        $this->aProps['indirizzo']="";
        $this->aProps['note']="";
        $this->aProps['alloggio_attuale']=0;
        $this->aProps['storico_assegnazioni']="";
        
        //template view props
        $this->aTemplateViewProps['descrizione']=array("label"=>"Descrizione","type"=>"text","maxlength"=>AA_Sicar_Const::MAX_DESCRIZIONE_LENGTH,"required"=>true,"visible"=>true);
        $this->aTemplateViewProps['cf']=array("label"=>"Codice fiscale","type"=>"text","required"=>true,"visible"=>true);
        $this->aTemplateViewProps['comune']=array("label"=>"Comune di residenza","type"=>"text","required"=>true,"function"=>"GetComune","visible"=>true);
        $this->aTemplateViewProps['indirizzo']=array("label"=>"Indirizzo di residenza","type"=>"text","required"=>true,"visible"=>true);
        $this->aTemplateViewProps['note']=array("label"=>"Note","type"=>"textarea","required"=>false,"visible"=>true);

        // Chiama il costruttore padre
        parent::__construct($params);
    }

    public function GetTemplateView($bResfresh=false)
    {
        if($this->oTemplateView !=null && !$bResfresh) return $this->oTemplateView;
        
        $templateView=new AA_GenericTemplate_Grid();
        $templateAreas=array(
            array("descrizione", "descrizione","cf"),
            array("indirizzo", "indirizzo", "comune"),
            array("note", "note", "note")
        );
        $templateView->SetTemplateAreas($templateAreas);
        $templateView->SetTemplateCols(array("1fr","1fr","1fr"));
        $templateView->SetTemplateRows(array("1fr","1fr","1fr"));        

        foreach($this->aTemplateViewProps as $propName=>$propConfig)
        {
            if($propConfig['visible'])
            {
                $class='';
                if(!empty($propConfig['class'])) $class=$propConfig['class'];
                else $class='aa-templateview-prop-'.$propName;

                $value="";
                if(empty($propConfig['function'])) $value = "<span class='".$class."'>" . $this->GetProp($propName) . "</span>";
                else 
                {
                    if(method_exists($this,$propConfig['function'])) $value = "<div class='".$class."'>".$this->{$propConfig['function']}()."</div>";
                    else $value = "<span class='".$class."'>n.d.</span>";
                }

                if(!$templateView->AddCellToGrid(new AA_JSON_Template_Template("", array(
                    "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
                    "data" => array("title" => "".$propConfig['label'].":", "value" => $value),
                    "css" => array("border-bottom" => "1px solid #dadee0 !important","width"=>"auto !important","height"=> "auto !important"),
                )), $propName))
                {
                    AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile aggiungere la cella alla template view per la proprietà: " . $propName, 100);
                }
            }
        }

        $this->SetTemplateView($templateView);

        //AA_Log::Log(__METHOD__ . " - INFO: generata la template view per l'immobile con ID: " . $this->GetProp("id")." - ".print_r($templateView,true), 100);
        return $templateView;
    }
    
    public function Delete($user = null)
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

        // Controlla se il nucleo è assegnato ad un alloggio e in tal caso libera l'alloggio
        $alloggioAttuale = $this->GetAlloggioAttuale();
        AA_Log::Log(__METHOD__ . " - INFO: controllo l'alloggio attuale per il nucleo con ID: " . $this->GetProp('id')." - alloggio: ".($alloggioAttuale ? $alloggioAttuale->GetDisplayName() : "nessuno o non trovato"), 100);
        if ($alloggioAttuale instanceof AA_SicarAlloggio) {
            //AA_Log::Log(__METHOD__ . " - ERRORE: impossibile eliminare il nucleo in quanto è attualmente assegnato all'alloggio: " . $alloggioAttuale->GetDisplayName(), 100);
            
            $occupazione = $alloggioAttuale->GetOccupazione();
            $lastAssegnazione = current($occupazione);
            if($lastAssegnazione['occupazione_id_nucleo'] == $this->GetProp('id')) 
            {
                //imposto lo stato dell'alloggio a libero
                $occupazione[array_key_first($occupazione)] = array(
                    "tipo" => 0,
                    "note"=>"Liberato il ".date("Y-m-d")." per eliminazione nucleo occupante.",
                    'occupazione_id_nucleo' => 0,
                    'occupazione_data_assegnazione'=>"",
                    'occupazione_tipo_canone'=>0,
                    'occupazione_riserva'=>0,
                    'occupazione_abusivo'=>0,
                    'occupazione_residenza'=>0
                );
                
                $alloggioAttuale->SetOccupazione($occupazione);
                $alloggioAttuale->Update($user ,true,"Aggiornamento stato occupazione - libero");
            }
        }

        return parent::Delete($user);
    }
    //stato assegnazione
    public function GetStatoAssegnazione($bAsObject=true,$last=true)
    {
        $return = array();
        $last_assegnazione=array();
        $last_assegnazione_dal="";
        $db=new AA_Database();
        $query="SELECT id,occupazione from ".AA_SicarAlloggio::AA_DBTABLE_DATA." WHERE occupazione like '%\"nucleo\":".$this->GetProp('id')."}'";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore query: ".$db->GetErrorMessage(),100);
            if($bAsObject) return array();
            else return "Nessuna";
        }
        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            foreach($rs as $curAlloggio)
            {
                $occupazione=json_decode($curAlloggio['occupazione'],true);
                $idAlloggio=$curAlloggio['id'];

                if(is_array($occupazione))
                {
                    foreach($occupazione as $curOccupazione)
                    {
                        if($curOccupazione['nucleo']==$this->GetProp("id"))
                        {
                            $return[$idAlloggio]=$curOccupazione;
                            if($curOccupazione['dal'] > $last_assegnazione_dal) 
                            {
                                $last_assegnazione_dal=$curOccupazione['dal'];
                                $last_assegnazione=$curOccupazione;
                            }
                        }
                        else
                        {
                            if($curOccupazione['dal'] > $last_assegnazione_dal) 
                            {
                                $last_assegnazione_dal = "";
                                $last_assegnazione=array();
                            }
                        }
                    }
                }
            }
            ksort($return);
        }

        if($bAsObject)
        {
            if(!$last) return $return;
            return $last_assegnazione;
        }
        else
        {
            if(sizeof($last_assegnazione) > 0) return $last_assegnazione['tipo'];
            else return "Nessuna";
        }
    }

    //Restituisce l'alloggio attuale se presente
    public function GetAlloggioAttuale($bAsObject=true)
    {
        if(!$bAsObject) return $this->GetProp('alloggio_attuale');

        if($this->GetProp('alloggio_attuale')>0)
        {
            $alloggio=new AA_SicarAlloggio($this->GetProp("alloggio_attuale"));
            if($alloggio->IsValid())
            {
                return $alloggio;
            }
            else return null;
        }

        return null;
    }

     public function GetComponenti($bAsObject=true)
    {
        $ret="";
        if($bAsObject) $ret=array();
        if(!isset($this->aProps['componenti']) || $this->aProps['componenti']=="") return $ret;
        
        if($bAsObject)
        {
            $ret=json_decode($this->aProps['componenti'],true);
            if($ret) return $ret;
            else
            {
                AA_Log::Log(__METHOD__." - Errore nell'importazione dei componenti: ".$this->aProps['id'],100);
                return array();
            }
        }

        return $this->aProps['componenti'];
    }

    //isee
    public function GetIseePreview()
    {
        return "n.d.";
    }

    public function SetComponenti($var="")
    {
        if(is_array($var))
        {
            if(sizeof($var)>0)
            {
                $var=json_encode($var);
                if($var===false)
                {
                    AA_Log::Log(__METHOD__." - Errore nella codifica dei componenti. ".print_r($var,true),100);
                    return false;
                }    
            }
            else $var="";
        }

        $this->SetProp("componenti",$var);
        return true;
    }

    //lista dei nuclei
    public static function GetListaNuclei()
    {
        $db = new AA_Database();
        $query = "SELECT * FROM ".static::$dbDataTable." ORDER BY descrizione";
        
        $return = array();

        if($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach($rs as $row) {
                $return[] = new AA_SicarNucleo($row);
            }
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile ottenere la lista degli enti. - ".$db->GetErrorMessage(), 100);
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

    // CF
    public function GetCf()
    {
        return $this->GetProp("cf");
    }
    
    public function SetCf($var = "")
    {
        $this->SetProp("cf", $var);
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
            $errors[] = "La descrizione è obbligatoria.";
        }
        if (empty($this->GetCf())) {
            $errors[] = "il codice fiscale è obbligatorio.";
        }
        
        //if (empty($this->GetIndirizzo())) {
        //    $errors[] = "L'indirizzo è obbligatorio.";
        //}

        //if (empty($this->GetComune(false))) {
        //    $errors[] = "Il Comune di residenza è obbligatorio.";
        //}
        
        return $errors;
    }
    
    // Metodo per ottenere una rappresentazione testuale dell'immobile
    public function GetDisplayName()
    {
        $display = strval($this->GetDescrizione());
        if (!empty($this->GetIndirizzo())) {
            $display .= " - " . $this->GetIndirizzo();
        }

        return $display;
    }

     // Comune
    public function GetComune($bDescr=true)
    {
        if($bDescr) 
        {
            if(empty($this->GetProp("comune"))) return "n.d.";
            else return AA_Sicar_Const::GetComuneDescrFromCodiceIstat($this->GetProp("comune"));
        }

        return $this->GetProp("comune");
    }

    public function SetComune($var = "")
    {
        $this->SetProp("comune", $var);
        return true;
    }
    
    // Metodo per l'esportazione CSV
    protected function CsvDataHeader($separator = "|")
    {
        return "descrizione".$separator . "tipologia" . 
               $separator . "indirizzo" . $separator . "note";
    }
    
    protected function CsvData($separator = "|")
    {
        return $this->GetDescrizione().$separator .  
               $separator .  str_replace("\n", ' ', $this->GetIndirizzo()) . 
               $separator . str_replace("\n", ' ', $this->GetNote());
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
    
    //verifica se il nucleo è attualmente assegnato ad un alloggio
    public function HasAlloggio()
    {   
        $statoAssegnazione=$this->GetProp("alloggio_attuale");
        if($statoAssegnazione > 0)
        {
            //AA_Log::Log(__METHOD__." - Il nucleo con ID: ".$this->GetProp("cf")." è attualmente assegnato all'alloggio con ID: ".$statoAssegnazione, 100);
            return true;
        } 
        else return false;
    }

    /**
     * Funzione statica per l'aggiunta di nuovi immobili
    * @param array $params dati dell'immobile
     * @return bool|int ID dell'immobile creato o false in caso di errore
     */
    static public function AddNew($params, $user = null)
    {
        $object = new AA_SicarNucleo();
        $object->Parse($params);
        $object->SetProp("id",0);

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
            AA_Log::Log(__METHOD__ . " - ERRORE: l'utente corrente: " . $user->GetName() . " non ha i permessi per inserire nuovi elementi.", 100);
            return false;
        }

        if(!empty($this->Validate())) 
        {
            AA_Log::Log(__METHOD__ . " - ERRORE: i dati del nucleo non sono validi.", 100);
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
    const AA_UI_SECTION_PUBBLICATE_ICON="mdi mdi-home-city";

    
    //id sezione gestione immobili
    const AA_ID_SECTION_IMMOBILI = "GestImmobili";
    const AA_UI_SECTION_IMMOBILI_BOX = "GestImmobiliBox";
    const AA_UI_SECTION_IMMOBILI_NAME = "Gestione immobili";
    const AA_UI_SECTION_IMMOBILI_ICON = "mdi mdi-office-building-marker";
    const AA_UI_SECTION_IMMOBILI_DESC = "Visualizza e gestisci gli immobili";
    const AA_UI_SECTION_IMMOBILI_TOOLTIP = "Visualizza e gestisci gli immobili";

    //id sezione gestione enti
    const AA_ID_SECTION_ENTI = "GestEnti";
    const AA_UI_SECTION_ENTI_BOX = "GestEntiBox";
    const AA_UI_SECTION_ENTI_NAME = "Gestione enti";
    const AA_UI_SECTION_ENTI_ICON = "mdi mdi-home-group";
    const AA_UI_SECTION_ENTI_DESC = "Visualizza e gestisci gli enti";
    const AA_UI_SECTION_ENTI_TOOLTIP = "Visualizza e gestisci gli enti";

    //operatori ente
    const AA_UI_WND_OPERATORI_ENTE = "SicarOperatoriEnteWnd";
    const AA_UI_TABLE_OPERATORI_ENTE = "TableOperatoriEnte";

    //id sezione gestione nuclei
    const AA_ID_SECTION_NUCLEI = "GestNuclei";
    const AA_UI_SECTION_NUCLEI_BOX = "GestNucleiBox";
    const AA_UI_SECTION_NUCLEI_NAME = "Gestione nuclei";
    const AA_UI_SECTION_NUCLEI_ICON = "mdi mdi-account-group";
    const AA_UI_SECTION_NUCLEI_DESC = "Visualizza e gestisci i nuclei familiari";
    const AA_UI_SECTION_NUCLEI_TOOLTIP = "Visualizza e gestisci i nuclei familiari";
    const AA_UI_WND_SEARCH_NUCLEI = "SicarSearchWnd";
    const AA_UI_TABLE_SEARCH_NUCLEI = "TableSearchNuclei";
    const AA_UI_WND_DETAIL_NUCLEI = "SicarDetailNucleoWnd";

    //stato occupazione wnd
    const AA_UI_WND_DETAIL_STATO_OCCUPAZIONE_ALLOGGIO = "SicarDetailStatoOccupazioneAlloggioWnd";

    //id sezione finanziamenti
    const AA_ID_SECTION_FINANZIAMENTI = "GestFinanziamenti";
    const AA_UI_SECTION_FINANZIAMENTI_BOX = "GestFinanziamentiBox";
    const AA_UI_SECTION_FINANZIAMENTI_NAME = "Gestione finanziamenti";
    const AA_UI_SECTION_FINANZIAMENTI_ICON = "mdi mdi-cash-fast";
    const AA_UI_SECTION_FINANZIAMENTI_DESC = "Visualizza e gestisci i Finanziamenti";
    const AA_UI_SECTION_FINANZIAMENTI_TOOLTIP = "Visualizza e gestisci i Finanziamenti";

    //id sezione finanziamenti
    const AA_ID_SECTION_GRADUATORIE = "GestGraduatorie";
    const AA_UI_SECTION_GRADUATORIE_BOX = "GestGraduatorieBox";
    const AA_UI_SECTION_GRADUATORIE_NAME = "Gestione graduatorie";
    const AA_UI_SECTION_GRADUATORIE_ICON = "mdi mdi-format-list-numbered";
    const AA_UI_SECTION_GRADUATORIE_DESC = "Visualizza e gestisci le graduatorie";
    const AA_UI_SECTION_GRADUATORIE_TOOLTIP = "Visualizza e gestisci le graduatorie";

    //id sezione tables
    const AA_ID_SECTION_TABLES = "GestTables";
    const AA_UI_SECTION_TABLES_BOX = "GestTablesBox";
    const AA_UI_SECTION_TABLES_NAME = "Gestione Enti,immobili e nuclei";
    const AA_UI_SECTION_TABLES_ICON = "mdi mdi-table";

    //------- Sezione cruscotto -------
    //Id sezione
    const AA_ID_SECTION_DESKTOP="sicar_desktop";

    //nome sezione
    const AA_UI_SECTION_DESKTOP_NAME="Cruscotto";

    const AA_UI_SECTION_DESKTOP_BOX="Sicar_Desktop_Content_Box";

    const AA_UI_SECTION_DESKTOP_ICON="mdi mdi-desktop-classic";
    //------------------------------

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
    const AA_UI_TABLE_SEARCH_ENTI = "TableSearchEnti";
    
    //ricerca immobili
    const AA_UI_WND_SEARCH_IMMOBILI = "SicarSearchWnd";
    const AA_UI_WND_DETAIL_IMMOBILI = "SicarDetailImmobileWnd";

    //ricerca enti
    const AA_UI_WND_SEARCH_ENTI = "SicarSearchEntiWnd";
    const AA_UI_WND_DETAIL_ENTI = "SicarDetailEnteWnd";

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
        $taskManager->RegisterTask("AddNewImmobileSicar");
        $taskManager->RegisterTask("GetSicarModifyImmobileDlg");
        $taskManager->RegisterTask("UpdateImmobileSicar");
        $taskManager->RegisterTask("GetSicarDeleteImmobileDlg");
        $taskManager->RegisterTask("DeleteImmobileSicar");
        $taskManager->RegisterTask("GetSicarDetailImmobileDlg");

        //enti
        $taskManager->RegisterTask("GetSicarAddNewEnteDlg");
        $taskManager->RegisterTask("AddNewEnteSicar");
        $taskManager->RegisterTask("GetSicarModifyEnteDlg");
        $taskManager->RegisterTask("UpdateEnteSicar");
        $taskManager->RegisterTask("GetSicarOperatoriEnteDlg");
        $taskManager->RegisterTask("GetSicarSearchEntiDlg");

        //nuclei
        $taskManager->RegisterTask("GetSicarAddNewNucleoDlg");
        $taskManager->RegisterTask("AddNewNucleoSicar");
        $taskManager->RegisterTask("GetSicarModifyNucleoDlg");
        $taskManager->RegisterTask("UpdateNucleoSicar");
        $taskManager->RegisterTask("GetSicarSearchNucleiDlg");
        $taskManager->RegisterTask("GetSicarDeleteNucleoDlg");
        $taskManager->RegisterTask("DeleteNucleoSicar");

        //stato occupazione
        $taskManager->RegisterTask("GetSicarAddNewStatoOccupazioneAlloggioDlg");
        $taskManager->RegisterTask("AddNewStatoOccupazioneAlloggioSicar");
        $taskManager->RegisterTask("GetSicarModifyStatoOccupazioneAlloggioDlg");
        $taskManager->RegisterTask("UpdateStatoOccupazioneAlloggioSicar");
        $taskManager->RegisterTask("GetSicarDeleteStatoOccupazioneAlloggioDlg");
        $taskManager->RegisterTask("DeleteStatoOccupazioneAlloggioSicar");

        // Task per le operazioni CRUD
        $taskManager->RegisterTask("AddNewAlloggioSicar");        
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

        #----------------------search enti --------------------
        $this->AddObjectTemplate(static::AA_UI_WND_SEARCH_ENTI."_".static::AA_UI_TABLE_SEARCH_ENTI,"Template_DatatableSearchEnti");
        #---------------------------------------------------------------

        #---------------------- operatori ente --------------------
        $this->AddObjectTemplate(static::AA_UI_WND_OPERATORI_ENTE."_".static::AA_UI_TABLE_OPERATORI_ENTE,"Template_DatatableOperatoriEnte");
        #---------------------------------------------------------------

        #----------------------search nuclei --------------------
        $this->AddObjectTemplate(static::AA_UI_WND_SEARCH_NUCLEI."_".static::AA_UI_TABLE_SEARCH_NUCLEI,"Template_DatatableSearchNuclei");
        #---------------------------------------------------------------

        #------------------------------- desktop -----------------------
        $desktop=new AA_GenericModuleSection(static::AA_ID_SECTION_DESKTOP,static::AA_UI_SECTION_DESKTOP_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_DESKTOP_BOX,$this->GetId(),true,true,false,true,static::AA_UI_SECTION_DESKTOP_ICON,"TemplateSection_Desktop");
        $desktop->SetNavbarTemplate($this->TemplateGenericNavbar_Void(1,true)->toArray());
        $desktop->SetIcon(static::AA_UI_SECTION_DESKTOP_ICON);
        $this->AddSection($desktop);
        #---------------------------------------------------------------
        
        #----------------------- Gest immobili -------------------------
        $gest_immobili=new AA_GenericModuleSection(static::AA_ID_SECTION_IMMOBILI,static::AA_UI_SECTION_IMMOBILI_NAME,true,static::AA_UI_PREFIX."_".static::AA_ID_SECTION_IMMOBILI,$this->GetId(),false,true,false,false,static::AA_UI_SECTION_IMMOBILI_ICON,"TemplateSection_Immobili");
        $gest_immobili->SetNavbarTemplate(array($this->TemplateGenericNavbar_Desktop(1,true,true)->toArray()));
        $this->AddSection($gest_immobili);
        #---------------------------------------------------------------

        #----------------------- Gest enti -------------------------
        $section=new AA_GenericModuleSection(static::AA_ID_SECTION_ENTI,static::AA_UI_SECTION_ENTI_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_ENTI_BOX,$this->GetId(),false,true,false,false,static::AA_UI_SECTION_ENTI_ICON,"TemplateSection_Enti");
        $section->SetNavbarTemplate(array($this->TemplateGenericNavbar_Desktop(1,true,true)->toArray()));
        $this->AddSection($section);
        #---------------------------------------------------------------

        #----------------------- Gest nuclei -------------------------
        $section=new AA_GenericModuleSection(static::AA_ID_SECTION_NUCLEI,static::AA_UI_SECTION_NUCLEI_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_NUCLEI_BOX,$this->GetId(),false,true,false,false,static::AA_UI_SECTION_NUCLEI_ICON,"TemplateSection_Nuclei");
        $section->SetNavbarTemplate(array($this->TemplateGenericNavbar_Desktop(1,true,true)->toArray()));
        $this->AddSection($section);
        #---------------------------------------------------------------

        #----------------------- Gest finanziamenti -------------------------
        $section=new AA_GenericModuleSection(static::AA_ID_SECTION_FINANZIAMENTI,static::AA_UI_SECTION_FINANZIAMENTI_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_FINANZIAMENTI_BOX,$this->GetId(),false,true,false,false,static::AA_UI_SECTION_FINANZIAMENTI_ICON,"TemplateSection_Finanziamenti");
        $section->SetNavbarTemplate(array($this->TemplateGenericNavbar_Desktop(1,true,true)->toArray()));
        $this->AddSection($section);
        #---------------------------------------------------------------

        #----------------------- Gest graduatorie -------------------------
        $section=new AA_GenericModuleSection(static::AA_ID_SECTION_GRADUATORIE,static::AA_UI_SECTION_GRADUATORIE_NAME,true,static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GRADUATORIE_BOX,$this->GetId(),false,true,false,false,static::AA_UI_SECTION_FINANZIAMENTI_ICON,"TemplateSection_Graduatorie");
        $section->SetNavbarTemplate(array($this->TemplateGenericNavbar_Desktop(1,true,true)->toArray()));
        $this->AddSection($section);
        #---------------------------------------------------------------

        $bozze=$this->GetSection(static::AA_ID_SECTION_BOZZE);
        $bozze->SetNavbarTemplate(array($this->TemplateGenericNavbar_Section($desktop,1)->toArray(),$this->TemplateGenericNavbar_Pubblicate(2,true)->toArray(),));

        $pubblicate=$this->GetSection(static::AA_ID_SECTION_PUBBLICATE);
        $pubblicate->SetNavbarTemplate(array($this->TemplateGenericNavbar_Section($desktop,1)->toArray(),$this->TemplateGenericNavbar_Bozze(2,true)->toArray()));  
    }
    
    //Navbar Desktop
    protected function TemplateGenericNavbar_Desktop($level = 1, $last = false, $refresh_view = true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(
            "",
            array(
                "type" => "clean",
                "section_id" => static::AA_ID_SECTION_DESKTOP,
                "module_id" => $this->GetId(),
                "refresh_view" => $refresh_view,
                "tooltip" => "Fai click per tornare al dekstop",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" . static::AA_UI_PREFIX . "_Navbar_Link_" . static::AA_ID_SECTION_DESKTOP . "' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"".static::AA_ID_SECTION_DESKTOP."\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => static::AA_UI_SECTION_DESKTOP_NAME, "icon" => static::AA_UI_SECTION_DESKTOP_ICON, "class" => $class)
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
            $gestore=$object->GetGestore();
            if($gestore instanceof AA_SicarEnte)
            {
                $data['sottotitolo'] =" <span class='AA_DataView_Tag AA_Label AA_Label_LightOrange' title='Ente gestore'>Ente gestore: <b>".$gestore->GetDenominazione()."</b></span>";
            }
            else $data['sottotitolo'] = "Nessun ente gestore definito";
            $tags="<span class='AA_DataView_Tag AA_Label AA_Label_LightYellow' title='Tipo di utilizzo'>Tipologia di utilizzo: <b>".$object->GetTipologiaUtilizzo()."</b></span>";
            if(!empty($object->GetAnnoRistrutturazione()))$tags.=" <span class='AA_DataView_Tag AA_Label AA_Label_LightYellow' title='Anno ultima ristrutturazione'>Anno ultima ristrutturazione: <b>".$object->GetAnnoRistrutturazione()."</b></span>";
            $data['tags']=$tags;

            //occupazione
            $occupazione=$object->GetOccupazione();
            $last_occupazione=current($occupazione);
            if(empty($occupazione))
            {

                    $data['occupazione']="<span class='AA_DataView_Tag AA_Label AA_Label_LightGray' title='Stato occupazione'>Nessuna informazione disponibile</span>";
            }
            else
            {
                $tipo_occupazione=AA_Sicar_Const::GetListaTipologieOccupazione(true);
                $colors=array(0=>"LightGray",1=>"LightGreen",2=>"LightYellow",3=>"LightOrange",4=>"LightRed");
                if($last_occupazione['occupazione_tipo']>0) $data['occupazione']="<span class='AA_Label AA_Label_".$colors[$last_occupazione['occupazione_tipo']]."' style='font-size:large; padding:4px;font-weight:900' title='Stato occupazione'>".$tipo_occupazione[$last_occupazione['occupazione_tipo']]."</span>";
                else $data['occupazione']="<span class='AA_Label AA_Label_".$colors[0]."' style='font-size:large; padding:4px;font-weight:900' title='Stato occupazione'>Libero</span>";
                
                //libero
                if($last_occupazione['occupazione_tipo']==0)
                {
                    $data['occupazione'].="<div><span style='font-size:small'>dal: </span><span style='font-weight:600'>".key($occupazione)."</span></div>";
                    $data['occupazione'].="</span>&nbsp;</span>";
                    $data['occupazione'].="</span>&nbsp;</span>";
                }

                //assegnato
                if($last_occupazione['occupazione_tipo']==1)
                {
                    $nucleo=new AA_SicarNucleo();
                    if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>a: </span><span style='font-weight:600'>".$nucleo->GetDescrizione()."</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'>".key($occupazione)."</span></div>";
                        $data['occupazione'].="</span>&nbsp;</span>";
                    }   
                    else
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>a: </span><span style='font-weight:600'> n.d.</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'> n.d.</span></div>";
                        $data['occupazione'].="</span>&nbsp;</span>";
                    }
                }

                //occupato
                if($last_occupazione['occupazione_tipo']>1)
                {
                    $nucleo=new AA_SicarNucleo();
                    if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>da: </span><span style='font-weight:600'>".$nucleo->GetDescrizione()."</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'>".key($occupazione)."</span></div>";
                        $data['occupazione'].="</span>&nbsp;</span>";
                    }
                    else
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>da: </span><span style='font-weight:600'> n.d.</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'> n.d.</span></div>";
                        $data['occupazione'].="</span>&nbsp;</span>";
                    }
                }
            }

            //interventi
            $data['interventi']="&nbsp;";
        }
        else
        {
            AA_Log::Log(__METHOD__." - oggetto non valido: ".print_r($object,true),100);
        }

        //AA_Log::Log(__METHOD__." - oggetto: ".print_r($object,true),100);
        return $data;
    }
    
    //Funzione di filtro personalizzata per la verifica se la sezione è filtrata
    protected function CustomDataSectionIsFiltered($params = array())
    {
        if(isset($params['immobile']) && $params['immobile'] > 0) return true;
        if(!empty($params['comune'])) return true;
        if(!empty($params['indirizzo'])) return true;
        if(!empty($params['stato_conservazione']) && $params['stato_conservazione'] > 0) return true;
        return false;
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

         $contentBoxTemplate = "<div class='AA_DataViewSicarAlloggi'><div style='width:33%; min-width:500px' class='AA_DataView_ItemContent'>"
            . "<div>#pretitolo#</div>"
            . "<div><span class='AA_DataView_ItemTitle'>#denominazione#</span></div>"
            . "<div>#tags#</div>"
            . "<div><span class='AA_DataView_ItemSubTitle'>#sottotitolo#</span></div>"
            . "<div><span class='AA_Label AA_Label_LightBlue' title='Stato elemento'>#stato#</span>&nbsp;<span class='AA_DataView_ItemDetails'>#dettagli#</span></div>"
            . "</div>"
            . "<div class='AA_DataView_SicarAlloggiOccupazione'>#occupazione#</div>"
            . "<div class='AA_DataView_SicarAlloggiInterventi'>#interventi#</div>"
            . "</div>";
        
        // Qui puoi usare AA_GenericSection_Bozze o un template simile a GECOP
        $content = $this->TemplateGenericSection_Bozze($params,null);
        $content->SetContentBoxTemplate($contentBoxTemplate);

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
        return $this->GetDataGenericSectionPubblicate_List($params,"GetDataSectionPubblicate_CustomFilter","GetDataSectionPubblicate_CustomDataTemplate");

    }

    // Personalizza il template dei dati delle pubblicate per il modulo corrente
    protected function GetDataSectionPubblicate_CustomDataTemplate($data = array(), $object = null)
    {
        if ($object instanceof AA_SicarAlloggio) {
            $data['pretitolo'] = $object->GetImmobile(false);
            $data['titolo'] = $object->GetDisplayName();
            $gestore=$object->GetGestore();
            if($gestore instanceof AA_SicarEnte)
            {
                $data['sottotitolo'] =" <span class='AA_DataView_Tag AA_Label AA_Label_LightOrange' title='Ente gestore'>Ente gestore: <b>".$gestore->GetDenominazione()."</b></span>";
            }
            else $data['sottotitolo'] = "Nessun ente gestore definito";
            $tags="<span class='AA_DataView_Tag AA_Label AA_Label_LightYellow' title='Tipo di utilizzo'>Tipologia di utilizzo: <b>".$object->GetTipologiaUtilizzo()."</b></span>";
            if(!empty($object->GetAnnoRistrutturazione()))$tags.=" <span class='AA_DataView_Tag AA_Label AA_Label_LightYellow' title='Anno ultima ristrutturazione'>Anno ultima ristrutturazione: <b>".$object->GetAnnoRistrutturazione()."</b></span>";
            $data['tags']=$tags;

            //occupazione
            $occupazione=$object->GetOccupazione();
            $last_occupazione=current($occupazione);
            if(empty($occupazione))
            {

                    $data['occupazione']="<span>Nessuna informazione disponibile</span><span>&nbsp;</span>";
            }
            else
            {
                $tipo_occupazione=AA_Sicar_Const::GetListaTipologieOccupazione(true);
                
                $clickDetailOccupazione="AA_MainApp.utils.callHandler('dlg', {task:'GetSicarDetailStatoOccupazioneAlloggioDlg', params: [{id: ".$object->GetId()."},{dal: '".key($occupazione)."'}]},'$this->id')";
                
                $colors=array(0=>"LightGray",1=>"LightGreen",2=>"LightYellow",3=>"LightOrange",4=>"LightRed");
                if($last_occupazione['occupazione_tipo']>0) $data['occupazione']="<span class='AA_Label AA_Label_".$colors[$last_occupazione['occupazione_tipo']]."' style='font-size:large; padding:4px;font-weight:900' title='Stato occupazione'>".$tipo_occupazione[$last_occupazione['occupazione_tipo']]."</span>";
                else $data['occupazione']="<span class='AA_Label AA_Label_".$colors[0]."' style='font-size:large; padding:4px;font-weight:900' title='Stato occupazione'>Libero</span>";
                
                //libero
                if($last_occupazione['occupazione_tipo']==0)
                {
                    $data['occupazione'].="<div><span style='font-size:small'>dal: </span><span style='font-weight:600'>".key($occupazione)."</span></div>";
                    $data['occupazione'].="<span>&nbsp;</span>";
                    if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                    else $data['occupazione'].="<span>&nbsp;</span>";
                }

                $nucleo=new AA_SicarNucleo();
                $tipo_canone=AA_Sicar_Const::GetListaTipologieCanoneAlloggio(true);
                
                //assegnato
                if($last_occupazione['occupazione_tipo']==1)
                {
                    if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>a: </span><span style='font-weight:600'>".$nucleo->GetDescrizione()."</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'>".key($occupazione)."</span></div>";
                        $data['occupazione'].="<span>&nbsp;</span>";
                        if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                        else $data['occupazione'].="<span>&nbsp;</span>";
                    }   
                    else
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>a: </span><span style='font-weight:600'> n.d.</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'> n.d.</span></div>";
                        $data['occupazione'].="<span>&nbsp;</span>";
                        if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                        else $data['occupazione'].="<span>&nbsp;</span>";
                    }
                }

                //occupato
                if($last_occupazione['occupazione_tipo']>1)
                {
                    if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>da: </span><span style='font-weight:600'>".$nucleo->GetDescrizione()."</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'>".key($occupazione)."</span></div>";
                        if($last_occupazione['occupazione_tipo']==2) $data['occupazione'].="<div><span style='font-size:small'>tipo canone: </span><span style='font-weight:600'>".$tipo_canone[$last_occupazione['occupazione_tipo_canone']]."</span></div>";
                        else $data['occupazione'].="<span>&nbsp;</span>";
                        if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                        else $data['occupazione'].="<span>&nbsp;</span>";
                    }
                    else
                    {
                        $data['occupazione'].="<div><span style='font-size:small'>da: </span><span style='font-weight:600'> n.d.</span>";
                        $data['occupazione'].="<span style='font-size:small'> - dal: </span><span style='font-weight:600'> n.d.</span></div>";
                        $data['occupazione'].="<span>&nbsp;</span>";
                        if($last_occupazione['note']!="") $data['occupazione'].='<a href="#" onClick="'.$clickDetailOccupazione.'" class="AA_Link AA_Link_Icon AA_Link_Icon_Info" title="Dettagli occupazione">Fai click qui per visualizzare le note</a>';
                        else $data['occupazione'].="<span>&nbsp;</span>";
                    }
                }
            }

            //interventi
            $data['interventi']="&nbsp;";
        }
        else
        {
            AA_Log::Log(__METHOD__." - oggetto non valido: ".print_r($object,true),100);
        }

        //restituisce il record al template base
        return $data;
    }

    //Personalizza il filtro delle bozze per il modulo corrente
    protected function GetDataSectionPubblicate_CustomFilter($params = array())
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

    //Template cruscotto content
    public function TemplateSection_Desktop()
    {
        //AA_Log::Log(__METHOD__,100);
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_DESKTOP_BOX;
        $layout = new AA_JSON_Template_Layout($id,array("type"=>"clean","name" => static::AA_UI_SECTION_DESKTOP_NAME));

        $second_row=new AA_JSON_Template_Layout("",array("type"=>"space","css"=>array("background-color"=>"transparent")));
        $second_row->AddCol($this->TemplateSection_News());
        $layout->AddRow($second_row);

        $minCountModulesToCarousel=6;

        //Moduli Row
        $modules_added=0;
        $modules=array(
            array("id_section"=>static::AA_ID_SECTION_PUBBLICATE,"icon"=>static::AA_UI_SECTION_PUBBLICATE_ICON,"label"=>"Gestione alloggi","descrizione"=>"Visualizza e gestisci gli alloggi","tooltip"=>"Visualizza e gestisci gli alloggi","visible"=>true),
            array("id_section"=>static::AA_ID_SECTION_IMMOBILI,"icon"=>static::AA_UI_SECTION_IMMOBILI_ICON,"label"=>static::AA_UI_SECTION_IMMOBILI_NAME,"descrizione"=>static::AA_UI_SECTION_IMMOBILI_DESC,"tooltip"=>static::AA_UI_SECTION_IMMOBILI_TOOLTIP),
            array("id_section"=>static::AA_ID_SECTION_ENTI,"icon"=>static::AA_UI_SECTION_ENTI_ICON,"label"=>static::AA_UI_SECTION_ENTI_NAME,"descrizione"=>static::AA_UI_SECTION_ENTI_DESC,"tooltip"=>static::AA_UI_SECTION_ENTI_TOOLTIP),
            array("id_section"=>static::AA_ID_SECTION_NUCLEI,"icon"=>static::AA_UI_SECTION_NUCLEI_ICON,"label"=>static::AA_UI_SECTION_NUCLEI_NAME,"descrizione"=>static::AA_UI_SECTION_NUCLEI_DESC,"tooltip"=>static::AA_UI_SECTION_NUCLEI_TOOLTIP),
            array("id_section"=>static::AA_ID_SECTION_FINANZIAMENTI,"icon"=>static::AA_UI_SECTION_FINANZIAMENTI_ICON,"label"=>static::AA_UI_SECTION_FINANZIAMENTI_NAME,"descrizione"=>static::AA_UI_SECTION_FINANZIAMENTI_DESC,"tooltip"=>static::AA_UI_SECTION_FINANZIAMENTI_TOOLTIP),
            array("id_section"=>static::AA_ID_SECTION_GRADUATORIE,"icon"=>static::AA_UI_SECTION_GRADUATORIE_ICON,"label"=>static::AA_UI_SECTION_GRADUATORIE_NAME,"descrizione"=>static::AA_UI_SECTION_GRADUATORIE_DESC,"tooltip"=>static::AA_UI_SECTION_GRADUATORIE_TOOLTIP)
        );
 
        $minHeightModuliItem=intval(($_REQUEST['vh']-180)/2);
        //$numModuliBoxForrow=intval(sqrt(sizeof($moduli_data)));
        $WidthModuliItem=intval(($_REQUEST['vw']-110)/4);
        //$HeightModuliItem=intval(/$numModuliBoxForrow);"css"=>"AA_DataView_Moduli_item","margin"=>10

        if(sizeof($modules) < $minCountModulesToCarousel ) 
        {
            AA_Log::Log(__METHOD__." - Aggiungo layout: ".$id."_ModuliBox" ,100);
            $moduli_box=new AA_JSON_Template_Layout($id."_ModuliBox",array("type"=>"clean","css"=>array("background-color"=>"transparent")));
        }
        else 
        {
            AA_Log::Log(__METHOD__." - Aggiungo carosello: ".$id."_ModuliBox" ,100);
            $moduli_box=new AA_JSON_Template_Carousel($id."_ModuliBox",array("type"=>"clean","css"=>array("background-color"=>"transparent")));
        }

        $riepilogo_template="<div class='AA_DataView_Moduli_item' onclick=\"#onclick#\" style='cursor: pointer; border: 1px solid; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 97%; margin:5px;'>";
        //icon
        $riepilogo_template.="<div style='display: flex; align-items: center; height: 120px; font-size: 90px;'><span class='#icon#'></span></div>";
        //name
        $riepilogo_template.="<div style='display: flex; align-items: center;justify-content: center; flex-direction: column; font-size: larger;height: 60px'>#name#</div>";
        //descr
        //$riepilogo_template.="<div style='display: flex; align-items: center;padding: 10px;height: 120px'><span>#descr#</span></div>";
        //go
        //$riepilogo_template.="<div style='display: flex; flex-direction: column; justify-content: center; align-items: center; height: 48px; padding: 5px'><a title='Apri il modulo' onclick=\"#onclick#\" class='AA_Button_Link'><span>Vai</span>&nbsp;<span class='mdi mdi-arrow-right-thick'></span></a></div>";
        $riepilogo_template.="</div>";

        $nSlide=0;
        $nMod=0;
        $moduli_view=null;
        foreach($modules as $curModId => $curMod)
        {
            $nMod++;
            $modules_added++;
            AA_Log::Log(__METHOD__." - Aggiungo il modulo: ".$curModId,100);
            $name="<span style='font-weight:900;font-variant-caps: all-small-caps;font-size:larger'>".$curMod['label']."</span><span>".$curMod['tooltip']."</span>";
            $onclick="AA_MainApp.utils.callHandler('setCurrentSection','".$curMod['id_section']."','".$this->GetId()."')";
            $moduli_data=array("id"=>$curMod['id_section'],"name"=>$name,'descr'=>$curMod['descrizione'],"icon"=>$curMod['icon'],"onclick"=>$onclick);
            if($moduli_view==null) $moduli_view=new AA_JSON_Template_Layout($id."_ModuliView_".$nSlide,array("type"=>"clean","css"=>array("background-color"=>"transparent")));
            $moduli_view->AddCol(new AA_JSON_Template_Template($id."_ModuleBox_".$moduli_data['id'],array("template"=>$riepilogo_template,"borderless"=>true,"data"=>array($moduli_data))));
            
            if($nMod%4==0)
            {
                
                if(sizeof($modules) < $minCountModulesToCarousel) 
                {
                    AA_Log::Log(__METHOD__." - Aggiungo box moduli: ".$id."_ModuliView_".$nSlide." - nMod: ".$nMod ,100);
                    $moduli_box->AddRow($moduli_view);
                }
                else 
                {
                    AA_Log::Log(__METHOD__." - Aggiungo la slide: ".$id."_ModuliView_".$nSlide." - nMod: ".$nMod ,100);
                    $moduli_box->AddSlide($moduli_view);
                }
                $nSlide++;
                $moduli_view=null;
                $nMod=0;
            }   
        }

        //AA_Log::Log(__METHOD__." - nMod: ".$nMod. " - %: ".$nMod%4,100);
        if($nMod%4 || $nMod < 4)
        {
            //AA_Log::Log(__METHOD__." - Aggiungo la slide: ".$id."_ModuliView_".$nSlide,100);
            $i=$nMod;
            if($nMod > 4) $i=$nMod%4;
            for($i;$i < 4;$i++)
            {
                if($moduli_view !=null) $moduli_view->addCol(new AA_JSON_Template_Generic());
            }
        }

        if($moduli_view != null)
        {
            if(sizeof($modules) < $minCountModulesToCarousel) 
            {
                AA_Log::Log(__METHOD__." - Aggiungo il box al layout: ".$id."_ModuliView_".$nSlide." - nMod: ".$nMod ,100);
                if($moduli_view !=null) $moduli_box->AddRow($moduli_view);
            }
            else 
            {
                AA_Log::Log(__METHOD__." - Aggiungo la slide: ".$id."_ModuliView_".$nSlide." - nMod: ".$nMod ,100);
                if($moduli_view !=null) $moduli_box->AddSlide($moduli_view);
            }
        }
          
        if($moduli_box)
        {
            if($modules_added==0)
            {
                $moduli_box->AddRow(new AA_JSON_Template_Template(uniqid(),array("template"=>"<div style='display: flex; justify-content: center; align-items: center; width: 100%;height:100%'><div>&nbsp;</div></div>")));
            }
            $layout->AddRow($moduli_box);
        }
        else
        {
            $layout->AddRow(new AA_JSON_Template_Template(uniqid(),array("template"=>"<div style='display: flex; justify-content: center; align-items: center; width: 100%;height:100%'><div>&nbsp;</div></div>")));
        }
        
        return $layout;
    }

    //Template news content
    public function TemplateSection_News()
    {
        $news_box=new AA_JSON_Template_Layout("",array("type"=>"space","css"=>"AA_Desktop_Section_Box"));
      
         
        $db = new AA_Database();
        
        $query="SELECT * from aa_sicar_news WHERE archivio='0' order by data DESC";
        
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__."() - errore: ".$db->GetErrorMessage()." nella query: ".$query,100);
        }

        $data=array();
        foreach($db->GetResultSet() as $row)
        {
            $data[]=array("id"=>$row['id'],"date"=>$row['data'],"value"=>$row['oggetto'],"details"=>$row['corpo']);
        }
        
        $news_box->AddRow(new AA_JSON_Template_Generic("",array("view"=>"label","align"=>"center","label"=>"<span class='AA_Desktop_Section_Label'>News</span>")));
        if(sizeof($data)>0)
        {
            $news_layout = new AA_JSON_Template_Generic("",
            array(
            "view"=>"timeline",
            "css"=>array("background-color"=>"transparent"),
            "type"=>array(
                "height"=>"auto",
                "width"=>800,
                "type"=>"left",
                "lineColor"=>"skyblue"
            )));
            $news_layout->setProp('data',$data);
            $news_box->AddRow($news_layout);
        }
        else
        {
            $news_box->AddRow(new AA_JSON_Template_Template("",array("template"=>"<div style='display: flex; justify-content: center; align-items: center; width: 100%;height:100%'><div>Non sono presenti news</div></div>")));
        }

        return $news_box;
    }

    // Template della sezione pubblicate
    public function TemplateSection_Pubblicate($params = array())
    {
        $bCanModify = $this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR);

        $contentBoxTemplate = "<div class='AA_DataViewSicarAlloggi'><div style='width:33%; min-width:500px' class='AA_DataView_ItemContent'>"
            . "<div>#pretitolo#</div>"
            . "<div><span class='AA_DataView_ItemTitle'>#denominazione#</span></div>"
            . "<div>#tags#</div>"
            . "<div><span class='AA_DataView_ItemSubTitle'>#sottotitolo#</span></div>"
            . "<div><span class='AA_Label AA_Label_LightBlue' title='Stato elemento'>#stato#</span>&nbsp;<span class='AA_DataView_ItemDetails'>#dettagli#</span></div>"
            . "</div>"
            . "<div class='AA_DataView_SicarAlloggiOccupazione'><span class='AA_DataView_SicarItemViewBoxLabel'>Stato occupazione</span>#occupazione#</span></div>"
            . "<div class='AA_DataView_SicarAlloggiInterventi'><span class='AA_DataView_SicarItemViewBoxLabel'>Stato interventi</span>#interventi#</div>"
            . "</div>";

        $content=$this->TemplateGenericSection_Pubblicate($params,$bCanModify,null);
        $content->SetContentBoxTemplate($contentBoxTemplate);
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
        $form_data['superficie_utile_abitabile'] = 0;
        $form_data['superficie_non_residenziale'] = 0;
        $form_data['superficie_parcheggi'] = 0;
        $form_data['vani_abitabili'] = 0;
        $form_data['piano'] = 0;
        $form_data['ascensore'] = 0;
        $form_data['fruibile_dis'] = 0;
        $form_data['note'] = "";

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo alloggio", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(150);
        $wnd->SetWidth(1280);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

         // Campo testuale: descrizione
        $wnd->AddTextField("nome", "Denominazione", ["required" => true, "bottomLabel" => "*Descrizione dell'alloggio"]);

        // Campo testuale: riferimento immobile
        $immobili = AA_SicarImmobile::GetListaImmobili();
        $options = array();
        foreach($immobili as $option)
        {
            $options[] = array("id" => $option->GetProp("id"), "value" => $option->GetDisplayName());
        }
        //$wnd->AddSelectField("immobile", "Immobile", ["required" => true, "bottomLabel" => "*Scegli un elemento della lista o fai click su nuovo se non e' presente l'immobile nella lista.","options" => $options]);
        $dlgImmobiliParams = array("task" => "GetSicarSearchImmobiliDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"immobile","field_desc"=>"immobile_desc"));
        $wnd->AddSearchField("dlg",$dlgImmobiliParams,$this->GetId(),["required" => true,"label"=>"Immobile","name"=>"immobile_desc", "bottomLabel" => "*Cerca un immobile gia' esistente o aggiungine uno se non e' presente."],false);        
        // Campo testuale: tipologia utilizzo
        $options = AA_Sicar_Const::GetListaTipologieUtilizzoAlloggio();
        $wnd->AddSelectField("tipologia_utilizzo", "Tipologia utilizzo", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        // Campo testuale: stato conservazione
        $options = AA_Sicar_Const::GetListaStatiConservazioneAlloggio();
        $wnd->AddSelectField("stato_conservazione", "Stato conservazione", ["required" => true,"validateFunction"=>"IsSelected","labelWidth"=>160, "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options],false);
        
        //superfici
        $superfici=new AA_FieldSet("AA_SICAR_ALLOGGI_SUPERFICI","Dati sulle superfici",$wnd->GetFormId(),1);
        // Campo numerico: superficie non residenziale
        $superfici->AddTextField("superficie_non_residenziale", "Non residenziale", ["required" => false,"labelWidth"=>120, "bottomLabel" => "Valore in metri quadri"]);
        // Campo numerico: superficie utile abitabile
        $superfici->AddTextField("superficie_utile_abitabile", "Abitabile", ["required" => true,"labelWidth"=>120, "bottomLabel" => "Superficie utile in mq"],false);
        // Campo numerico: superficie parcheggi
        $superfici->AddTextField("superficie_parcheggi", "Parcheggi", ["required" => false,"labelWidth"=>120, "bottomLabel" => "Superficie utile in mq"],false);
        $wnd->AddGenericObject($superfici);

        //altri dati
        $altro=new AA_FieldSet("AA_SICAR_ALLOGGI_ALTRO","Altri dati",$wnd->GetFormId(),1);
        // Campo numerico: anno ristrutturazione
        $altro->AddTextField("anno_ristrutturazione", "Anno ristrutturazione", ["required" => false,"labelWidth"=>150, "bottomLabel" => "Anno (se presente)"]);
        // Campo numerico: vani abitabili
        $altro->AddTextField("vani_abitabili", "Vani abitabili", ["required" => true, "labelWidth"=>120,"width"=>200,"bottomLabel" => "Numero di vani abitabili."],false);       
        // Campo numerico: piano
        $altro->AddTextField("piano", "Piano", ["required" => true,"labelWidth"=>90,"width"=>170, "bottomLabel" => "Numero del piano"],false);
        $wnd->AddGenericObject($altro,false);

        // Campo booleano: condominio misto
        //$wnd->AddCheckBoxField("condominio_misto", " ", ["required" => false, "labelWidth"=>150,"labelRight" => "Condominio misto"]);
        
        //ente gestore
        $dlgEntiParams = array("task" => "GetSicarSearchEntiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"gestione_ente","field_desc"=>"gestione_ente_desc"));
        $ente=new AA_FieldSet("AA_SICAR_ENTE_GESTORE","Ente gestore",$wnd->GetFormId(),3);
        $ente->AddSearchField("dlg",$dlgEntiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Denominazione","name"=>"gestione_ente_desc", "bottomLabel" => "*Cerca un ente gia' esistente o aggiungine uno se non e' presente."]);
        $ente->AddDateField('gestione_dal','Dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>80),false);
        $wnd->AddGenericObject($ente);

        $attributi=new AA_FieldSet("AA_SICAR_ALLOGGIO_ATTRIBUTI","Caratteristiche",$wnd->GetFormId(),2);
        
        // Campo select: fruibile per disabili
        $options = AA_Sicar_Const::GetListaFruibilitaDisabile();
        $attributi->AddSelectField("fruibile_dis", "Fruibilità da disabile", ["required" => true, "labelWidth"=>160,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        
        // Campo booleano: ascensore
        $attributi->AddCheckBoxField("ascensore", " ", ["required" => false,"labelWidth"=>10,"width"=>160,"bottomPadding"=>0,"labelRight" => "Ascensore"],false);
        
        $wnd->AddGenericObject($attributi,false);
        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false,"bottomPadding"=>0, "bottomLabel" => "Note aggiuntive"]);

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
        $form_data['superficie_non_residenziale'] = $object->GetSuperficieNonResidenziale();
        $form_data['superficie_parcheggi'] = $object->GetSuperficieParcheggi();
        $form_data['vani_abitabili'] = $object->GetVaniAbitabili();
        $form_data['superficie_utile_abitabile'] = $object->GetSuperficieUtileAbitabile();
        $form_data['piano'] = $object->GetPiano();
        $form_data['ascensore'] = $object->GetAscensore();
        $form_data['fruibile_dis'] = $object->GetFruibileDis(true);

        $form_data['note'] = $object->GetNote();

        $gestore=$object->GetGestore();
        if($gestore)
        {
            $form_data['gestione_ente']=$gestore->GetProp("id");
            $form_data['gestione_ente_desc']=$gestore->GetDisplayName();
            $form_data['gestione_dal']=$object->GetGestioneDal();
        }
        else
        {
            AA_Log::Log(__METHOD__." - Ente gestore non impostato o non trovato. (".print_r($object->GetProp("gestione")));
            $form_data['gestione_ente']="";
            $form_data['gestione_ente_desc']="";
            $form_data['gestione_dal']="";
        }

        $wnd = new AA_GenericFormDlg($id, "Modifica alloggio", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(150);
        $wnd->SetWidth(1280);
        $wnd->SetHeight(650);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: denominazione
        $wnd->AddTextField("nome", "Denominazione", ["required" => true, "bottomLabel" => "*Descrizione dell'alloggio"]);
       
        // Campo testuale: riferimento immobile (opzionale)
        $immobili = AA_SicarImmobile::GetListaImmobili();
        $options = array();
        foreach($immobili as $option)
        {
            $options[] = array("id" => $option->GetProp("id"), "value" => $option->GetDisplayName());
        }
        //$wnd->AddSelectField("immobile", "Immobile", ["required" => true, "bottomLabel" => "*Scegli un elemento della lista o fai click su nuovo se non e' presente l'immobile nella lista.","options" => $options]);
        $dlgParams = array("task" => "GetSicarSearchImmobiliDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"immobile","field_desc"=>"immobile_desc"));
        $wnd->AddSearchField("dlg",$dlgParams,$this->GetId(),["required" => true,"label"=>"Immobile","name"=>"immobile_desc", "bottomLabel" => "*Cerca un immobile gia' esistente o aggiungine uno se non e' presente."],false);

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
 
        // Campo testuale: tipologia utilizzo
        $options = AA_Sicar_Const::GetListaTipologieUtilizzoAlloggio();
        $wnd->AddSelectField("tipologia_utilizzo", "Tipologia utilizzo", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        // Campo testuale: stato conservazione
        $options = AA_Sicar_Const::GetListaStatiConservazioneAlloggio();
        $wnd->AddSelectField("stato_conservazione", "Stato conservazione", ["required" => true,"validateFunction"=>"IsSelected","labelWidth"=>160, "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options],false);
        
        //superfici
        $superfici=new AA_FieldSet("AA_SICAR_ALLOGGI_SUPERFICI","Dati sulle superfici",$wnd->GetFormId(),1);
        // Campo numerico: superficie non residenziale
        $superfici->AddTextField("superficie_non_residenziale", "Non residenziale", ["required" => false,"labelWidth"=>120, "bottomLabel" => "Valore in metri quadri"]);
        // Campo numerico: superficie utile abitabile
        $superfici->AddTextField("superficie_utile_abitabile", "Abitabile", ["required" => true,"labelWidth"=>120, "bottomLabel" => "Superficie utile in mq"],false);
        // Campo numerico: superficie parcheggi
        $superfici->AddTextField("superficie_parcheggi", "Parcheggi", ["required" => false,"labelWidth"=>120, "bottomLabel" => "Superficie utile in mq"],false);
        $wnd->AddGenericObject($superfici);

        //altri dati
        $altro=new AA_FieldSet("AA_SICAR_ALLOGGI_ALTRO","Altri dati",$wnd->GetFormId(),1);
        // Campo numerico: anno ristrutturazione
        $altro->AddTextField("anno_ristrutturazione", "Anno ristrutturazione", ["required" => false,"labelWidth"=>150, "bottomLabel" => "Anno (se presente)"]);
        // Campo numerico: vani abitabili
        $altro->AddTextField("vani_abitabili", "Vani abitabili", ["required" => true, "labelWidth"=>120,"width"=>200,"bottomLabel" => "Numero di vani abitabili."],false);       
        // Campo numerico: piano
        $altro->AddTextField("piano", "Piano", ["required" => true,"labelWidth"=>90,"width"=>170, "bottomLabel" => "Numero del piano"],false);
        $wnd->AddGenericObject($altro,false);

        //ente gestore
        $dlgEntiParams = array("task" => "GetSicarSearchEntiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"gestione_ente","field_desc"=>"gestione_ente_desc"));
        $ente=new AA_FieldSet("AA_SICAR_ENTE_GESTORE","Ente gestore",$wnd->GetFormId(),3);
        $ente->AddSearchField("dlg",$dlgEntiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Denominazione","name"=>"gestione_ente_desc", "bottomLabel" => "*Cerca un ente gia' esistente o aggiungine uno se non e' presente."]);
        $ente->AddDateField('gestione_dal','Dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>80),false);
        $wnd->AddGenericObject($ente);

        $attributi=new AA_FieldSet("AA_SICAR_ALLOGGIO_ATTRIBUTI","Caratteristiche",$wnd->GetFormId(),2);
        
        // Campo select: fruibile per disabili
        $options = AA_Sicar_Const::GetListaFruibilitaDisabile();
        $attributi->AddSelectField("fruibile_dis", "Fruibilità da disabile", ["required" => true, "labelWidth"=>160,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        
        // Campo booleano: ascensore
        $attributi->AddCheckBoxField("ascensore", " ", ["required" => false,"labelWidth"=>10,"width"=>160,"bottomPadding"=>0,"labelRight" => "Ascensore"],false);
        
        $wnd->AddGenericObject($attributi,false);

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

        //attributi
        $form_data['attributi']="";
        $form_data['attributi_condominio_misto']=0;
        $form_data['attributi_alloggi']="";

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
        $wnd->SetWidth(1280);
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
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>1, "bottomLabel" => "*Indirizzo dell'immobile comprensivo del numero civico."]);
        
        // Campo testuale: geolocalizzazione
        $wnd->AddTextField("geolocalizzazione", "Geoloc.", ["required" => true,"gravity"=>1, "bottomLabel" => "*Geolocalizzazione dell'immobile (latitudine e longitudine) in gradi decimali separate da virgola.","placeholder"=>"es. 39.22, 9.10"],false);

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

        //ente gestore
        $dlgEntiParams = array("task" => "GetSicarSearchEntiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"immobile_gestione_ente","field_desc"=>"immobile_gestione_ente_desc"));
        $ente=new AA_FieldSet("AA_SICAR_ENTE_GESTORE".uniqid(),"Ente gestore",$wnd->GetFormId(),3);
        $ente->AddSearchField("dlg",$dlgEntiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Denominazione","name"=>"immobile_gestione_ente_desc", "bottomLabel" => "*Cerca un ente gia' esistente o aggiungine uno se non e' presente."]);
        $ente->AddDateField('immobile_gestione_dal','Dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>80),false);
        $wnd->AddGenericObject($ente);

        //Attributi
        $attributi = new AA_FieldSet("AA_SICAR_ATTRIBUTI".uniqid(),"Caratteristiche",$wnd->GetFormId(),2);
        //alloggi totali
        $label="Alloggi tot.";
        $attributi->AddTextField("attributi_alloggi",$label,array("gravity"=>2,"bottomLabel"=>"*Inserire il numero totale di alloggi (anche solo previsti).", "required"=>true,"placeholder"=>"..."));

        //condominio misto 
        $attributi->AddCheckBoxField("attributi_condominio_misto", " ", ["required" => false,"labelWidth"=>10,"width"=>180,"labelRight"=>"Condominio misto","bottomPadding"=>36, "bottomLabel" => ""],false);
        $wnd->AddGenericObject($attributi,false);

        $wnd->SetSaveTask("AddNewImmobileSicar");
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false,"labelWidth"=>60, "bottomLabel" => "Note aggiuntive"]);

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di aggiunta nuovo ente
    public function Template_GetSicarAddNewEnteDlg()
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
        
        $id = $this->GetId() . "_AddNewEnte_Dlg_" . uniqid();
        $form_data = array();

        $newImmobile = new AA_SicarEnte();
        foreach($newImmobile->GetProps() as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo Ente", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(980);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: descrizione
        $wnd->AddTextField("denominazione", "Denominazione", ["required" => true, "bottomLabel" => "*Denominazione dell'ente"]);
        
        // Campo testuale: tipologia
        $options = AA_Sicar_Const::GetListaTipologieEnte();
        $wnd->AddSelectField("tipologia", "Tipologia", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Tipologia dell'ente", "options" => $options]);
        
        // Campo testuale: geolocalizzazione
        $wnd->AddTextField("geolocalizzazione", "Geoloc.", ["gravity"=>1, "bottomLabel" => "*Geolocalizzazione dell'ente (latitudine e longitudine) in gradi decimali separate da virgola.","placeholder"=>"es. 39.225048459422766, 9.102739130435575"],false);

        // Campo testuale: indirizzo
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>3, "bottomLabel" => "*Indirizzo dell'ente comprensivo del numero civico."]);
        
        // Campo testuale: web
        $wnd->AddTextField("web", "Sito web", ["required" => true,"gravity"=>1, "validateFunction"=>"IsUrl", "bottomLabel" => "*Url del sito web dell'ente","placeholder"=>"es. https://www.miosito.it"]);

        // Campo testuale: pec
        $wnd->AddTextField("pec", "PEC", ["required" => true,"gravity"=>1, "validateFunction"=>"IsEmail", "bottomLabel" => "*PEC dell'ente","placeholder"=>"es. pec@ente.it"],false);
        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("AddNewEnteSicar");
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di aggiunta nuovo nucleo
    public function Template_GetSicarAddNewNucleoDlg()
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
        
        $id = $this->GetId() . "_AddNewNucleo_Dlg_" . uniqid();
        $form_data = array();

        $newNucleo = new AA_SicarNucleo();
        foreach($newNucleo->GetProps() as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo Nucleo", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(980);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: descrizione
        $wnd->AddTextField("descrizione", "Descrizione", ["required" => true,"gravity"=>2, "bottomLabel" => "*descrizione del nucleo","placeholder"=>"famiglia Rossi"]);
        
        // Campo testuale: cf
        $wnd->AddTextField("cf", "Codice fiscale", ["required" => true, "bottomLabel" => "*codice fiscale del capofmiglia","placeholder"=>"..."],false);
        
        // Campo testuale: indirizzo
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>2, "bottomLabel" => "*Indirizzo di residenza."]);
        
        // Campo testuale: comune
        $wnd->AddTextField("comune", "Comune", ["required" => true, "bottomLabel" => "*Comune dell'immobile (codice ISTAT)", "suggest"=>array("template"=>"#value#","url"=>$this->taskManagerUrl."?task=GetSicarListaCodiciIstat")],false);
       
        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("AddNewNucleoSicar");
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di aggiunta nuovo stato occupazione dell'alloggio
    public function Template_GetSicarAddNewStatoOccupazioneAlloggioDlg($object=null)
    {
        $id = $this->GetId() . "_AddNewStatoOccupazioneAlloggio_Dlg_" . uniqid();
        if(!($object instanceof AA_SicarAlloggio) || $object===null || !$object->IsValid() || $object->GetId()==0)
        {
            $wnd = new AA_GenericWindowTemplate($id, "Aggiunta nuovo stato di occupazione", $this->id);
            $wnd->AddView(new AA_JSON_Template_Generic("",array(
                "view"=>"label",
                "label"=>"Impossibile aggiungere un nuovo stato occupazione. Prima e' necessario selezionare un alloggio valido.",
                "align"=>"center",
                "autowidth"=>true,
                "height"=>100
            )));
            return $wnd;
        }
        
        $form_data = array();
        $form_data['id']=$object->GetId();
        $form_data['data_dal'] = date("Y-m-d");
        $form_data['stato'] = 0; //"libero"
        $form_data['occupazione_tipo'] = 0;
        $form_data['occupazione_id_nucleo'] = 0;
        $form_data['occupazione_nucleo_desc'] = "";
        $form_data['occupazione_data_assegnazione'] = date("Y-m-d");
        $form_data['occupazione_tipo_canone'] = 0;
        $form_data['occupazione_residenza'] = 0;
        $form_data['note'] = "";

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo Stato occupazione", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(760);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        //campo stato alloggio
        $wnd->AddSwitchBoxField("stato", "Stato alloggio", ["onLabel"=>"assegnato/occupato","offLabel"=>"libero", "relatedView"=>$id."_AA_SICAR_DETTAGLIO_OCCUPAZIONE", "relatedAction"=>"show"]);
        
        //campo data: data dal
        $wnd->AddDateField("data_dal", "dal", ["required" => true,"validateFunction"=>"IsIsoDate", "bottomLabel" => "*Data di inizio dello stato dell'alloggio"], false);
        
        $dettaglioOccupazione = new AA_FieldSet($id."_AA_SICAR_DETTAGLIO_OCCUPAZIONE", "Dettaglio occupazione", $wnd->GetFormId(), 1,array("type"=>"clean","hidden"=>true));
        
        $options=array(
            array("id"=>1,"value"=>"Assegnato"),
            array("id"=>2,"value"=>"Occupato"),
            array("id"=>3,"value"=>"Occupato con riserva"),
            array("id"=>4,"value"=>"Occupato abusivo")
        );
        $dettaglioOccupazione->AddRadioField("occupazione_tipo", "Tipo", ["required" => true,"labelWidth"=>120,"options"=>$options, "bottomPadding"=>36, "bottomLabel" => "*Scegliere il tipo di occupazione tra quelli disponibili."]);
        
        // Campo booleano: occupazione riserva
        //$dettaglioOccupazione->AddCheckBoxField("occupazione_riserva", " ", ["required" => false,"labelWidth"=>10,"labelRight"=>"Occupazione con riserva","bottomPadding"=>36, "bottomLabel" => ""]);
        // Campo booleano: occupazione abusivo
        //$dettaglioOccupazione->AddCheckBoxField("occupazione_abusivo", " ", ["required" => false,"labelWidth"=>10,"labelRight"=>"Occupazione abusiva","bottomPadding"=>36, "bottomLabel" => ""],false);
        // Campo booleano: occupazione indirizzo di residenza
        
        //campo riferimento nucleo occupante
        $dlgNucleiParams = array("task" => "GetSicarSearchNucleiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"occupazione_id_nucleo","field_desc"=>"occupazione_nucleo_desc"));
        $dettaglioOccupazione->AddSearchField("dlg",$dlgNucleiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Nucleo","name"=>"occupazione_nucleo_desc", "bottomLabel" => "*Cerca un nucleo gia' esistente o aggiungine uno se non e' presente."]);
       
        $dettaglioOccupazione->AddCheckBoxField("occupazione_residenza", " ", ["required" => false,"labelWidth"=>1,"labelRight"=>"indirizzo di residenza","bottomPadding"=>36, "bottomLabel" => "Aggiorna l'indirizzo di residenza del nucleo occupante."],false);
        
        //campo tipo canone
        $options = AA_Sicar_Const::GetListaTipologieCanoneAlloggio();
        $dettaglioOccupazione->AddSelectField("occupazione_tipo_canone", "Tipo canone", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        
        //campo data assegnazione
        $dettaglioOccupazione->AddDateField('occupazione_data_assegnazione','Assegnato dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>150),false);

        $wnd->AddGenericObject($dettaglioOccupazione);

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Eventuali note aggiuntive"]);

        $wnd->SetSaveTask("AddNewStatoOccupazioneAlloggioSicar");

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di modifica stato occupazione dell'alloggio
    public function Template_GetSicarModifyStatoOccupazioneAlloggioDlg($object=null,$dal="")
    {
        $id = $this->GetId() . "_ModifyStatoOccupazioneAlloggio_Dlg_" . uniqid();
        if(!($object instanceof AA_SicarAlloggio) || $object===null || !$object->IsValid() || $object->GetId()==0)
        {
            $wnd = new AA_GenericWindowTemplate($id, "Modifica stato di occupazione", $this->id);
            $wnd->AddView(new AA_JSON_Template_Generic("",array(
                "view"=>"label",
                "label"=>"Impossibile modificare lo stato occupazione. Prima e' necessario selezionare un alloggio valido.",
                "align"=>"center",
                "autowidth"=>true,
                "height"=>100
            )));
            return $wnd;
        }
        $occupazione=$object->GetOccupazione();
        if(!isset($occupazione[$dal]) || empty($occupazione[$dal]))
        {
            $wnd = new AA_GenericWindowTemplate($id, "Modifica stato di occupazione", $this->id);
            $wnd->AddView(new AA_JSON_Template_Generic("",array(
                "view"=>"label",
                "label"=>"Stato occupazione non trovato.",
                "align"=>"center",
                "autowidth"=>true,
                "height"=>100
            )));
            return $wnd;
        }

        $occupazione=$occupazione[$dal];
        if($occupazione['occupazione_id_nucleo']>0)
        {
            $nucleo = new AA_SicarNucleo();
            if($nucleo->Load($occupazione['occupazione_id_nucleo']))
            {
                $occupazione['occupazione_nucleo_desc']=$nucleo->GetDescrizione();
            }
            else 
            {
                $occupazione['occupazione_nucleo_desc']="Nucleo non trovato.";
            }
        }
       
        $form_data = array();
        $form_data['id']=$object->GetId();
        $form_data['data_dal'] = $dal;
        $form_data['stato'] = $occupazione['stato'];
        $form_data['occupazione_tipo'] = $occupazione['occupazione_tipo'];
        $form_data['occupazione_id_nucleo'] = $occupazione['occupazione_id_nucleo'];
        $form_data['occupazione_nucleo_desc'] = $occupazione['occupazione_nucleo_desc'];
        $form_data['occupazione_data_assegnazione'] = $occupazione['occupazione_data_assegnazione'];
        $form_data['occupazione_tipo_canone'] = $occupazione['occupazione_tipo_canone'];
        $form_data['occupazione_residenza'] = $occupazione['occupazione_residenza'];
        $form_data['note'] = $occupazione['note'];

        $wnd = new AA_GenericFormDlg($id, "Modifica Stato occupazione per data dal: ".$dal, $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(760);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        //campo stato alloggio
        $wnd->AddSwitchBoxField("stato", "Stato alloggio", ["onLabel"=>"assegnato/occupato","offLabel"=>"libero", "relatedView"=>$id."_AA_SICAR_DETTAGLIO_OCCUPAZIONE", "relatedAction"=>"show"]);
        
        $dettaglioOccupazione = new AA_FieldSet($id."_AA_SICAR_DETTAGLIO_OCCUPAZIONE", "Dettaglio occupazione", $wnd->GetFormId(), 1,array("type"=>"clean","hidden"=>true));
        $options=array(
            array("id"=>1,"value"=>"Assegnato"),
            array("id"=>2,"value"=>"Occupato"),
            array("id"=>3,"value"=>"Occupato con riserva"),
            array("id"=>4,"value"=>"Occupato abusivo")
        );
        $dettaglioOccupazione->AddRadioField("occupazione_tipo", "Tipo", ["required" => true,"labelWidth"=>120,"options"=>$options, "bottomPadding"=>36, "bottomLabel" => "*Scegliere il tipo di occupazione tra quelli disponibili."]);
           
        //campo riferimento nucleo occupante
        $dlgNucleiParams = array("task" => "GetSicarSearchNucleiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"occupazione_id_nucleo","field_desc"=>"occupazione_nucleo_desc"));
        $dettaglioOccupazione->AddSearchField("dlg",$dlgNucleiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Nucleo","name"=>"occupazione_nucleo_desc", "bottomLabel" => "*Cerca un nucleo gia' esistente o aggiungine uno se non e' presente."]);
       
        // Campo booleano: occupazione indirizzo di residenza
        $dettaglioOccupazione->AddCheckBoxField("occupazione_residenza", " ", ["required" => false,"labelWidth"=>1,"labelRight"=>"indirizzo di residenza","bottomPadding"=>36, "bottomLabel" => "Aggiorna l'indirizzo di residenza del nucleo occupante."],false);
        
        //campo tipo canone
        $options = AA_Sicar_Const::GetListaTipologieCanoneAlloggio();
        $dettaglioOccupazione->AddSelectField("occupazione_tipo_canone", "Tipo canone", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Scegliere una voce dall'elenco", "options" => $options]);
        
        //campo data assegnazione
        $dettaglioOccupazione->AddDateField('occupazione_data_assegnazione','Assegnato dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>150),false);

        $wnd->AddGenericObject($dettaglioOccupazione);

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Eventuali note aggiuntive"]);

        $wnd->SetSaveTask("UpdateStatoOccupazioneAlloggioSicar");

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di modifica nucleo
    public function Template_GetSicarModifyNucleoDlg($object)
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
        
        $id = $this->GetId() . "_ModifyNucleo_Dlg_" . uniqid();
        $form_data = array();

        foreach($object->GetProps() as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }
        $form_data['comune']=AA_Sicar_Const::GetComuneDescrFromCodiceIstat($object->GetProp('comune'))." (".$object->GetProp("comune").")";

        $wnd = new AA_GenericFormDlg($id, "Modifica Nucleo", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(980);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: descrizione
        $wnd->AddTextField("descrizione", "Descrizione", ["required" => true,"gravity"=>2, "bottomLabel" => "*descrizione del nucleo","placeholder"=>"famiglia Rossi"]);
        
        // Campo testuale: cf
        $wnd->AddTextField("cf", "Codice fiscale", ["required" => true, "bottomLabel" => "*codice fiscale del capofmiglia","placeholder"=>"..."],false);
        
        // Campo testuale: indirizzo
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>2, "bottomLabel" => "*Indirizzo di residenza."]);
        
        // Campo testuale: comune
        $wnd->AddTextField("comune", "Comune", ["required" => true, "bottomLabel" => "*Comune dell'immobile (codice ISTAT)", "suggest"=>array("template"=>"#value#","url"=>$this->taskManagerUrl."?task=GetSicarListaCodiciIstat")],false);
       
        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("UpdateNucleoSicar");
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di modifica ente
    public function Template_GetSicarModifyEnteDlg($object=null)
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
        
        $id = $this->GetId() . "_ModifyEnte_Dlg_" . uniqid();
        $form_data = array();

        if(!($object instanceof AA_SicarEnte))
        {
            AA_Log::Log(__METHOD__." - Ento0 non specificato: ".print_r($object,true),100);
            return new AA_GenericFormDlg($id, "Aggiungi nuovo Ente", $this->id, $form_data, $form_data);
        }

        $newImmobile = $object;
        $form_data['id']=$newImmobile->GetProp('id');
        foreach($newImmobile->GetProps() as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }

        $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo Ente", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(980);
        $wnd->SetHeight(600);
        $wnd->SetBottomPadding(36);
        $wnd->EnableValidation();
        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();

        // Campo testuale: descrizione
        $wnd->AddTextField("denominazione", "Denominazione", ["required" => true, "bottomLabel" => "*Denominazione dell'ente"]);
        
        // Campo testuale: tipologia
        $options = AA_Sicar_Const::GetListaTipologieEnte();
        $wnd->AddSelectField("tipologia", "Tipologia", ["required" => true,"validateFunction"=>"IsSelected", "bottomLabel" => "*Tipologia dell'ente", "options" => $options]);
        
        // Campo testuale: geolocalizzazione
        $wnd->AddTextField("geolocalizzazione", "Geoloc.", ["gravity"=>1, "bottomLabel" => "*Geolocalizzazione dell'ente (latitudine e longitudine) in gradi decimali separate da virgola.","placeholder"=>"es. 39.225048459422766, 9.102739130435575"],false);

        // Campo testuale: indirizzo
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>3, "bottomLabel" => "*Indirizzo dell'ente comprensivo del numero civico."]);
        
        // Campo testuale: web
        $wnd->AddTextField("web", "Sito web", ["required" => true,"gravity"=>1, "validateFunction"=>"IsUrl", "bottomLabel" => "*Url del sito web dell'ente","placeholder"=>"es. https://www.miosito.it"]);

        // Campo testuale: pec
        $wnd->AddTextField("pec", "PEC", ["required" => true,"gravity"=>1, "validateFunction"=>"IsEmail", "bottomLabel" => "*PEC dell'ente","placeholder"=>"es. pec@ente.it"],false);
        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("AddNewEnteSicar");
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Template per la finestra di dialogo di modifica immobile esistente
    public function Template_GetSicarModifyImmobileDlg($immobile=null)
    {        
        $id = $this->GetId() . "_Modify_Dlg_" . uniqid();
        $form_data = array();

        foreach($immobile->GetProps() as $prop=>$value)
        {
            $form_data[$prop] = $value;
        }

        $form_data['id']=$immobile->GetProp("id");

        //dati catastali
        $catasto=$immobile->GetCatasto();

        $form_data['catasto']="";
        $form_data['SezioneCatasto'] = $catasto['SezioneCatasto'];
        $form_data['FoglioCatasto'] = $catasto['FoglioCatasto'];
        $form_data['MappaleCatasto'] = $catasto['MappaleCatasto'];
        $form_data['ParticellaCatasto'] = $catasto['ParticellaCatasto'];
        $form_data['Subalterno'] = $catasto['Subalterno'];

        $attributiData=$immobile->GetAttributi();
        $form_data['attributi']="";

        $form_data['attributi_condominio_misto']="";
        $form_data['attributi_alloggi']="";
        
        $gestore=$immobile->GetGestore();
        if($gestore)
        {
            $form_data['immobile_gestione_ente']=$gestore->GetProp("id");
            $form_data['immobile_gestione_ente_desc']=$gestore->GetDisplayName();
            $form_data['immobile_gestione_dal']=$immobile->GetGestioneDal();
        }
        else
        {
            AA_Log::Log(__METHOD__." - Ente gestore non impostato o non trovato. (".print_r($immobile->GetProp("attributi")));
            $form_data['immobile_gestione_ente']="";
            $form_data['immobile_gestione_ente_desc']="";
            $form_data['immobile_gestione_dal']="";
        }

        if(!empty($attributiData['condominio_misto'])) $form_data['attributi_condominio_misto']=$attributiData['condominio_misto'];
        if(!empty($attributiData['alloggi'])) $form_data['attributi_alloggi']=$attributiData['alloggi'];

        $wnd = new AA_GenericFormDlg($id, "Modifica immobile", $this->id, $form_data, $form_data);
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(120);
        $wnd->SetWidth(1280);
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
        $wnd->AddTextField("indirizzo", "Indirizzo", ["required" => true,"gravity"=>1, "bottomLabel" => "*Indirizzo dell'immobile comprensivo del numero civico."]);
        
        // Campo testuale: geolocalizzazione
        $wnd->AddTextField("geolocalizzazione", "Geoloc.", ["required" => true,"gravity"=>1, "bottomLabel" => "*Geolocalizzazione dell'immobile (latitudine e longitudine) in gradi decimali separate da virgola (es.: 39.22, 9.10)","placeholder"=>"es. 39.22, 9.10"],false);

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

       //ente gestore
        $dlgEntiParams = array("task" => "GetSicarSearchEntiDlg", "postParams" => array("form" => $wnd->GetFormId(),"field_id"=>"immobile_gestione_ente","field_desc"=>"immobile_gestione_ente_desc"));
        $ente=new AA_FieldSet("AA_SICAR_ENTE_GESTORE".uniqid(),"Ente gestore",$wnd->GetFormId(),3);
        $ente->AddSearchField("dlg",$dlgEntiParams,$this->GetId(),["required" => true,"gravity"=>2,"label"=>"Denominazione","name"=>"immobile_gestione_ente_desc", "bottomLabel" => "*Cerca un ente gia' esistente o aggiungine uno se non e' presente."]);
        $ente->AddDateField('immobile_gestione_dal','Dal',array("required"=>true,"validateFunction"=>"IsIsoDate","bottomPadding"=>32,"labelWidth"=>80),false);
        $wnd->AddGenericObject($ente);

        //Attributi
        $attributi = new AA_FieldSet("AA_SICAR_ATTRIBUTI".uniqid(),"Caratteristiche",$wnd->GetFormId(),2);
        //alloggi totali
        $label="Alloggi tot.";
        $attributi->AddTextField("attributi_alloggi",$label,array("gravity"=>2,"bottomLabel"=>"*Inserire il numero totale di alloggi (anche solo previsti).", "required"=>true,"placeholder"=>"..."));

        //condominio misto 
        $attributi->AddCheckBoxField("attributi_condominio_misto", " ", ["required" => false,"labelWidth"=>10,"width"=>180,"labelRight"=>"Condominio misto","bottomPadding"=>36, "bottomLabel" => ""],false);
        $wnd->AddGenericObject($attributi,false);

        // Campo testuale: note
        $wnd->AddTextareaField("note", "Note", ["required" => false,"labelWidth"=>60, "bottomLabel" => "Note aggiuntive"]);

        $wnd->SetSaveTask("UpdateImmobileSicar");

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);

        return $wnd;
    }

    // Task per la restituzione della finestra di dialogo di eliminazione immobile
    public function Task_GetSicarDeleteImmobileDlg($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per eliminare l'immobile");
            return false;
        }

        $immobile=new AA_SicarImmobile();
        if (!$immobile->Load($_REQUEST['id'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Immobile non trovato (id: ".$_REQUEST['id'].")");
            return false;
        }

        $alloggi=$immobile->GetAlloggi(false,$this->oUser);
        if($alloggi===false || sizeof($alloggi)>0)
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'immobile contiene uno o piu' alloggi, è possibile eliminare esclusivamene immobili senza alloggi.");
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarDeleteImmobileDlg($immobile), true);
        return true;
    }

    //Template dlg delete immobile
    public function Template_GetSicarDeleteImmobileDlg($object=null)
    {
        $id=uniqid();
        
        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina immobile", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(380);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("descrizione"=>$object->GetDisplayName());

        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p></div>";
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente immobile verrà eliminato definitivamente, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"descrizione", "header"=>"Descrizione", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteImmobileSicar");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetProp("id")));
        
        return $wnd;
    }

    //Template dlg delete nucleo
    public function Template_GetSicarDeleteNucleoDlg($object=null)
    {
        $id=uniqid();
        
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

        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina nucleo", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(380);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");
                
        $tabledata=array();
        $tabledata[]=array("descrizione"=>$object->GetDescrizione(),"cf"=>$object->GetProp("cf"));

        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p></div>";
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente nucleo verrà eliminato definitivamente, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"descrizione", "header"=>"Descrizione", "fillspace"=>true),
              array("id"=>"cf", "header"=>"Codice Fiscale", "width"=>160)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteNucleoSicar");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetProp("id")));
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("id"=>$object->GetProp("id"),"form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
        return $wnd;
    }

    //Template dlg delete nucleo
    public function Template_GetSicarDeleteStatoOccupazioneAlloggioDlg($object=null,$dal="")
    {
        $id=uniqid();
        
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

        $form_data=array();
        
        $wnd=new AA_GenericFormDlg($id, "Elimina stato assegnazione occupazione", $this->id,$form_data,$form_data);
        
        $wnd->SetLabelAlign("right");
        $wnd->SetLabelWidth(80);
        
        $wnd->SetWidth(580);
        $wnd->SetHeight(380);
        
        //Disattiva il pulsante di reset
        $wnd->EnableResetButton(false);

        //Imposta il nome del pulsante di conferma
        $wnd->SetApplyButtonName("Procedi");

        $nucleo=$object->GetNucleoAssegnatario($dal);
        AA_Log::Log(__METHOD__." - Nucleo assegnatario dello stato di occupazione con data dal ".$dal.": ".print_r($nucleo,true),100);

        if($nucleo)
        {
            $nucleo_desc=$nucleo->GetProp("descrizione")." (".$nucleo->GetProp("cf").")";
        }
        else $nucleo_desc="n.d.";

        $tabledata=array();
        $tabledata[]=array("data_stato_occupazione"=>$dal,"nucleo_assegnatario"=>$nucleo_desc);

        $template="<div style='display: flex; justify-content: center; align-items: center; flex-direction:column'><p class='blinking' style='font-size: larger;font-weight:900;color: red'>ATTENZIONE!</p></div>";
        $wnd->AddGenericObject(new AA_JSON_Template_Template($id."_Content",array("type"=>"clean","autoheight"=>true,"template"=>$template)));
      
        $wnd->AddGenericObject(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"Il seguente stato di occupazione verrà eliminato definitivamente, vuoi procedere?")));

        $table=new AA_JSON_Template_Generic($id."_Table", array(
            "view"=>"datatable",
            "autoheight"=>true,
            "scrollX"=>false,
            "columns"=>array(
              array("id"=>"data_stato_occupazione", "header"=>"Data dal", "width"=>120),
              array("id"=>"nucleo_assegnatario", "header"=>"Codice Fiscale", "fillspace"=>true)
            ),
            "select"=>false,
            "data"=>$tabledata
        ));

        $wnd->AddGenericObject($table);

        $wnd->EnableCloseWndOnSuccessfulSave();
        $wnd->enableRefreshOnSuccessfulSave();
        $wnd->SetSaveTask("DeleteStatoOccupazioneAlloggioSicar");
        $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"dal"=>$dal));
        if(!empty($form))
        {
            $wnd->SetSaveTaskParams(array("id"=>$object->GetId(),"dal"=>$dal,"form" => $form,"field_id"=>$field_id,"field_desc"=>$field_desc));
        }

        if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] !="") $wnd->enableRefreshOnSuccessfulSave();
        if(isset($_REQUEST['refresh_obj_id']) && $_REQUEST['refresh_obj_id'] !="") $wnd->SetRefreshObjId($_REQUEST['refresh_obj_id']);
        return $wnd;
    }

    // Task per la restituzione della finestra di dialogo di eliminazione immobile
    public function Task_GetSicarDeleteNucleoDlg($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per eliminare il nucleo");
            return false;
        }

        $nucleo=new AA_SicarNucleo();
        if (!$nucleo->Load($_REQUEST['id'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nucleo non trovato (id: ".$_REQUEST['id'].")");
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarDeleteNucleoDlg($nucleo), true);
        return true;
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

    // Task per la restituzione della finestra di dialogo di filtro pubblicate
    public function Task_GetSicarPubblicateFilterDlg($task)
    {
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->TemplatePubblicateFilterDlg($_REQUEST), true);
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

    //Task search Enti
    public function Task_GetSicarSearchEntiDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per visualizzare gli enti", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarSearchEntiDlg(),true);
        return true;
    }

    //Task search Nuclei
    public function Task_GetSicarSearchNucleiDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per visualizzare i nuclei", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarSearchNucleiDlg(),true);
        return true;
    }

    //Task operatori ente
    public function Task_GetSicarOperatoriEnteDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per visualizzare gli operatori dell'ente", false);
            return false;
        }

        $ente=new AA_SicarEnte();
        if(!$ente->Load($_REQUEST['id_ente']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Ente non trovato (id: ".$_REQUEST['id_ente'].")", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarOperatoriEnteDlg($ente),true);
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

    //Template dlg search enti
    public function Template_GetSicarSearchEntiDlg()
    {
        $id=static::AA_UI_WND_SEARCH_ENTI;
        
        $wnd=new AA_GenericWindowTemplate($id, "Ricerca Enti", $this->id);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(640);
        
        $wnd->AddView($this->Template_DatatableSearchEnti($id));
        
        return $wnd;
    }

    //Template dlg search nuclei
    public function Template_GetSicarSearchNucleiDlg()
    {
        $id=static::AA_UI_WND_SEARCH_NUCLEI;
        
        $wnd=new AA_GenericWindowTemplate($id, "Ricerca nuclei", $this->id);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(640);
        
        $wnd->AddView($this->Template_DatatableSearchNuclei($id));
        
        return $wnd;
    }

    //Template dlg operatori ente
    public function Template_GetSicarOperatoriEnteDlg($ente=null)
    {
        $id=static::AA_UI_WND_OPERATORI_ENTE;
        
        $wnd=new AA_GenericWindowTemplate($id, "Operatori ente", $this->id);
        
        $wnd->SetWidth(1080);
        $wnd->SetHeight(640);
        
        $wnd->AddView($this->Template_DatatableOperatoriEnte($id,$ente));
        
        return $wnd;
    }

    //Template data table Operatori ente
    public function Template_DatatableOperatoriEnte($id="",$ente=null)
    {
        if($id=="") $id=static::AA_UI_WND_OPERATORI_ENTE;
        $id.="_".static::AA_UI_TABLE_OPERATORI_ENTE;

        if(!($ente instanceof AA_SicarEnte))
        {
            $layout=new AA_JSON_Template_Layout($id,array("type"=>"clean"));
            $layout->addRow(new AA_JSON_Template_Template("",array("template"=>"Ente non trovato.")));
            return $layout;
        }
        
        $canModify=false;
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

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
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) $filter=array("id_ente"=>$ente->GetProp('id'),"refresh"=>1,"refresh_obj_id"=>$id,"form"=>$form,"field_id"=>$field_id,"field_desc"=>$field_desc);
        else $filter=array("refresh"=>1,"refresh_obj_id"=>$id,"id_ente"=>$ente->GetProp('id'));
        $modify_btn=new AA_JSON_Template_Generic("",array(
            "view"=>"button",
             "type"=>"icon",
             "icon"=>"mdi mdi-filter-cog",
             "label"=>"Aggiungi",
             "align"=>"right",
             "width"=>120,
             "tooltip"=>"Aggiungi un nuovo operatore",
             "click"=>"AA_MainApp.curModule.setRuntimeValue('" . $id . "','filter_data',".json_encode($filter)."); AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewOperatoreEnteDlg\",postParams: AA_MainApp.curModule.getRuntimeValue('" . $id . "','filter_data'), module: '" . $this->id . "'},'".$this->id."')"
        ));
        $toolbar->AddElement($modify_btn);
        
        $layout->addRow($toolbar);

        #criteri----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        $operatori=$ente->GetOperatori();

        $data=[];
        //$trash='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopTrashComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        //$modify='AA_MainApp.utils.callHandler("dlg", {task:"GetGecopModifyComponenteDlg", params: [{id:"'.$object->GetId().'"},{id_componente:"'.$id_componente.'"}]},"'.$this->id.'")';
        if(!empty($form) && !empty($field_id) && !empty($field_desc)) 
        {
            $select_icon="mdi mdi-cursor-pointer";
        }
    
        foreach($operatori as $curOperatore)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if(!empty($form) && !empty($field_id) && !empty($field_desc))
            {
                $select="try{if($$('".$form."')){ AA_MainApp.utils.callHandler('SicarSelectOperatoreEnte', {form:'".$form."', values:{'".$field_id."':'".$curOperatore['cf']."','".$field_desc."':'".$curOperatore['nome']." ".$curOperatore['cognome']."'}},'".$this->GetId()."'); $$('".static::AA_UI_WND_ENTE_OPERATORI."_Wnd').close();}}catch(msg){console.error(msg)}";
                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Scegli' onClick=\"".$select."\"><span class='mdi ".$select_icon."'></span></a></div>";
                
                $data[]=array("id"=>$curOperatore['cf'],"nome"=>$curOperatore['nome']." ".$curOperatore['cognome'],"cf"=>$curOperatore['cf'],"email"=>$curOperatore['email'],"ops"=>$ops);
            }
            else
            {
                $ops="";
                if($canModify)
                {
                    $trash="AA_MainApp.utils.callHandler('dlg', {task:'GetSicarDeleteOperatoreEnteDlg', postParams: {id_ente:'".$ente->GetProp('id')."', cf:'".$curOperatore['cf']."'}, module: '".$this->id."'},'".$this->id."')";
                    $modify="AA_MainApp.utils.callHandler('dlg', {task:'GetSicarModifyOperatoreEnteDlg', postParams: {id_ente:'".$ente->GetProp('id')."', cf:'".$curOperatore['cf']."'}, module: '".$this->id."'},'".$this->id."')";
                    $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Modifica' onClick=\"".$modify."\"><span class='mdi mdi-pencil'></span></a><a class='AA_DataTable_Ops_Button' title='Elimina' onClick=\"".$trash."\"><span class='mdi mdi-trash-can'></span></a></div>";
                }
                $data[]=array("id"=>$curOperatore['cf'],"nome"=>$curOperatore['nome']." ".$curOperatore['cognome'],"cf"=>$curOperatore['cf'],"email"=>$curOperatore['email'],"ops"=>$ops);
            }
        }

        if(!$canModify) $template=new AA_GenericDatatableTemplate($id."_TableEnteOperatori_".uniqid(),"",3,null,array("css"=>"AA_Header_DataTable"));
        else $template=new AA_GenericDatatableTemplate($id."_TableEnteOperatori_".uniqid(),"",4,null,array("css"=>"AA_Header_DataTable"));
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

        $template->SetColumnHeaderInfo(0,"nome","<div style='text-align: center'>Nome e cognome</div>",250,"textFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(1,"cf","<div style='text-align: center'>Cf</div>",250,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(2,"email","<div style='text-align: center'>Email</div>",250,"textFilter","text","GenericAutosizedRowTable");
       
        if($canModify) $template->SetColumnHeaderInfo(3,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"GenericAutosizedRowTable");

        $template->SetData($data);

        $layout->AddRow($template);
        return $layout;
    }

    //Template dlg Detail immobile
    public function Template_GetSicarDetailImmobileDlg($immobile=null)
    {
        $id=static::AA_UI_WND_DETAIL_IMMOBILI;
        
        $wnd=new AA_GenericWindowTemplate($id, "Dettaglio immobile", $this->id);

        $wnd->SetWidth(800);
        $wnd->SetHeight(600);
        
        if(!($immobile instanceof AA_SicarImmobile))
        {
            $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Immobile non trovato")));
            return $wnd;
        }

        $wnd->AddView($immobile->GetTemplateView());
        
        return $wnd;
    }

    //Template dlg Detail occupazione alloggio
    public function Template_GetSicarDetailStatoOccupazioneAlloggioDlg($stato=null)
    {
        $id=static::AA_UI_WND_DETAIL_STATO_OCCUPAZIONE_ALLOGGIO;
        
        $wnd=new AA_GenericWindowTemplate($id, "Dettaglio stato occupazione alloggio", $this->id);

        $wnd->SetWidth(800);
        $wnd->SetHeight(600);
        
        if(!is_array($stato))
        {
            $wnd->AddView(new AA_JSON_Template_Template("",array("template"=>"Stato occupazione alloggio non valido")));
            return $wnd;
        }

        $wnd=new AA_GenericWindowTemplate($id, "Dettaglio stato occupazione alloggio", $this->id);

        $oTemplateView=new AA_GenericTemplate_Grid();
        $templateAreas=array();

        if($stato['stato'] > 0)
        {
            $tipo_occupazione=AA_Sicar_Const::GetListaTipologieOccupazione(true);

            //template view props
            $aTemplateViewProps['stato']=array("label"=>"Stato occupazione","value"=>$tipo_occupazione[$stato['stato']],"visible"=>true);
            $aTemplateViewProps['nucleo']=array("label"=>"Comune","type"=>"text","required"=>true,"bottomLabel"=>"Comune dove e' situato l'immobile","function"=>"GetComune","visible"=>true);
            $aTemplateViewProps['canone']=array("label"=>"Ubicazione","type"=>"text","required"=>true,"bottomLabel"=>"Ubicazione dell'immobile all'interno del territorio comunale","function"=>"GetUbicazione","visible"=>true);
            $aTemplateViewProps['data_assegnazione']=array("label"=>"Indirizzo","type"=>"text","required"=>true,"bottomLabel"=>"Indirizzo dell'immobile","visible"=>true);
            $aTemplateViewProps['note']=array("label"=>"Dati catastali","type"=>"text","required"=>true,"function"=>"GetTemplateViewCatasto","visible"=>true);
        

            $aTemplateViewProps['__areas']=array(
                array("stato", "stato"),
                array("nucleo","nucleo"),
                array("canone", "data_assegnazione"),
                array("note", "note")
            );
            $aTemplateViewProps['__cols']=array("2fr","1fr");
            $aTemplateViewProps['__rows']=array("1fr","1fr","2fr");
        }
        else
        {
            $aTemplateViewProps['stato']=array("label"=>"Descrizione","type"=>"text","maxlength"=>AA_Sicar_Const::MAX_DESCRIZIONE_LENGTH,"required"=>true,"bottomLabel"=>"Inserisci la descrizione dell'immobile","visible"=>true);
            $aTemplateViewProps['note']=array("label"=>"Note","type"=>"text","required"=>true,"function"=>"GetTemplateViewNote","visible"=>true);

            $aTemplateViewProps['__areas']=array(
                array("descrizione", "descrizione","descrizione"),
                array("note", "note", "note")
            );
            $aTemplateViewProps['__cols']=array("1fr","1fr");
            $aTemplateViewProps['__rows']=array("1fr","3fr");
        }

        foreach($aTemplateViewProps as $propName=>$propConfig)
        {
            if($propConfig['visible'])
            {
                $templateAreas[]=$propName;
                $class='';
                if(!empty($propConfig['class'])) $class=$propConfig['class'];
                else $class='aa-templateview-prop-'.$propName;

                $value="";
                if(empty($propConfig['function'])) $value = "<span class='".$class."'>" . $propConfig['value']. "</span>";
                else 
                {
                    if(method_exists($this,$propConfig['function'])) $value = "<div class='".$class."'>".$this->{$propConfig['function']}()."</div>";
                    else $value = "<span class='".$class."'>n.d.</span>";
                }

                if(!$oTemplateView->AddCellToGrid(new AA_JSON_Template_Template("", array(
                    "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
                    "data" => array("title" => "".$propConfig['label'].":", "value" => $value),
                    "css" => array("border-bottom" => "1px solid #dadee0 !important","width"=>"auto !important","height"=> "auto !important")
                )), $propName))
                {
                    AA_Log::Log(__METHOD__ . " - ERRORE: non è stato possibile aggiungere la cella alla template view per la proprietà: " . $propName, 100);
                }
            }
        }

        $oTemplateView->SetTemplateAreas($templateAreas);
        $oTemplateView->SetTemplateCols($aTemplateViewProps['__cols']);
        $oTemplateView->SetTemplateRows($aTemplateViewProps['__rows']);

        $wnd->AddView($oTemplateView);
        
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

    //Template data table SearchEnti
    public function Template_DatatableSearchEnti($id="")
    {
        if($id=="") $id=static::AA_UI_WND_SEARCH_ENTI;
        $id.="_".static::AA_UI_TABLE_SEARCH_ENTI;
        
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
             "tooltip"=>"Aggiungi un nuovo Ente",
             "click"=>"AA_MainApp.curModule.setRuntimeValue('" . $id . "','filter_data',".json_encode($filter)."); AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewEnteDlg\",postParams: AA_MainApp.curModule.getRuntimeValue('" . $id . "','filter_data'), module: '" . $this->id . "'},'".$this->id."')"
        ));
        $toolbar->AddElement($modify_btn);
        
        $layout->addRow($toolbar);

        #criteri----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        $immobili=AA_SicarEnte::Search();
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
                $select="try{if($$('".$form."')){ AA_MainApp.utils.callHandler('SicarSelectFormItem', {form:'".$form."', values:{'".$field_id."':'".$curImmobile->GetProp('id')."','".$field_desc."':'".$curImmobile->GetDisplayName()."'}},'".$this->GetId()."'); $$('".static::AA_UI_WND_SEARCH_ENTI."_Wnd').close();}}catch(msg){console.error(msg)}";
                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Scegli' onClick=\"".$select."\"><span class='mdi ".$select_icon."'></span></a></div>";
                $data[]=array("id"=>$curImmobile->GetProp("id"),"denominazione"=>$curImmobile->GetDenominazione(),"indirizzo"=>$curImmobile->GetIndirizzo(),"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curImmobile->GetProp("id"),"denominazione"=>$curImmobile->GetDenominazione(),"indirizzo"=>$curImmobile->GetIndirizzo());
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id."_TableSearch_".uniqid(),"Ricerca enti",2,null,array("css"=>"AA_Header_DataTable"));
        else $template=new AA_GenericDatatableTemplate($id."_TableSearch_".uniqid(),"Ricerca enti",3,null,array("css"=>"AA_Header_DataTable"));
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

        $template->SetColumnHeaderInfo(0,"denominazione","<div style='text-align: center'>Denominazione</div>",250,"textFilter","int","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo</div>","fillspace","textFilter","text","ImmobiliTable_left");
        //$template->SetColumnHeaderInfo(2,"comune","<div style='text-align: center'>Comune</div>",250,"textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(2,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"ImmobiliTable");

        $template->SetData($data);

        $layout->AddRow($template);
        return $layout;
    }

    //Template data table SearchNuclei
    public function Template_DatatableSearchNuclei($id="")
    {
        if($id=="") $id=static::AA_UI_WND_SEARCH_NUCLEI;
        $id.="_".static::AA_UI_TABLE_SEARCH_NUCLEI;
        
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
             "tooltip"=>"Aggiungi un nuovo Nucleo",
             "click"=>"AA_MainApp.curModule.setRuntimeValue('" . $id . "','filter_data',".json_encode($filter)."); AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewNucleoDlg\",postParams: AA_MainApp.curModule.getRuntimeValue('" . $id . "','filter_data'), module: '" . $this->id . "'},'".$this->id."')"
        ));
        $toolbar->AddElement($modify_btn);
        
        $layout->addRow($toolbar);

        #criteri----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //Solo nuclei senza alloggio attuale
        $params=array();
        $params['WHERE']=array(
            array("FIELD"=>"alloggio_attuale","OPERATOR"=>"=","VALUE"=>0)
        );

        $nuclei=AA_SicarNucleo::Search();
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

        foreach($nuclei as $curNucleo)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if(!empty($form) && !empty($field_id) && !empty($field_desc))
            {
                $select="try{if($$('".$form."')){ AA_MainApp.utils.callHandler('SicarSelectFormItem', {form:'".$form."', values:{'".$field_id."':'".$curNucleo->GetProp('id')."','".$field_desc."':'".$curNucleo->GetDescrizione()."'}},'".$this->GetId()."'); $$('".static::AA_UI_WND_SEARCH_NUCLEI."_Wnd').close();}}catch(msg){console.error(msg)}";
                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Scegli' onClick=\"".$select."\"><span class='mdi ".$select_icon."'></span></a></div>";
                if(!$curNucleo->HasAlloggio())
                { 
                    $alloggio_attuale_str="<span class='AA_Label AA_Label_LightRed'>Nessun alloggio assegnato</span>";
                }
                else
                {
                    $alloggio_attuale=$curNucleo->GetAlloggioAttuale();
                    $alloggio_attuale_str="<span class='AA_Label AA_Label_LightGreen'>".$alloggio_attuale->GetDescrizione()."</span>";
                    $ops="&nbsp;"; //non mostrare il pulsante di selezione per i nuclei con alloggio attuale
                }

                $data[]=array("id"=>$curNucleo->GetProp("id"),"denominazione"=>$curNucleo->GetDescrizione(),"cf"=>$curNucleo->GetCf(),"alloggio_attuale"=>$alloggio_attuale_str,"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curNucleo->GetProp("id"),"denominazione"=>$curNucleo->GetDenominazione(),"alloggio_attuale"=>$alloggio_attuale_str,"cf"=>$curNucleo->GetCf());
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id."_TableSearch_".uniqid(),"Ricerca nuclei",3,null,array("css"=>"AA_Header_DataTable"));
        else $template=new AA_GenericDatatableTemplate($id."_TableSearch_".uniqid(),"Ricerca nuclei",4,null,array("css"=>"AA_Header_DataTable"));
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

        $template->SetColumnHeaderInfo(0,"denominazione","<div style='text-align: center'>Descrizione</div>",250,"textFilter","text","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(1,"cf","<div style='text-align: center'>Codice fiscale</div>","fillspace","textFilter","text","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(2,"alloggio_attuale","<div style='text-align: center'>Alloggio assegnato</div>","fillspace","textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(2,"comune","<div style='text-align: center'>Comune</div>",250,"textFilter","text","ImmobiliTable");
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
        //AA_Log::Log(__METHOD__." - ".print_r($options,true),100);
        $dlg->AddSelectField("stato_conservazione","Stato di conservazione",array("bottomLabel"=>"*Filtra in base allo stato di conservazione dell'alloggio", "options"=>$options));
        
        //ids
        $dlg->AddTextField("ids","Identificativi",array("bottomLabel"=>"*Filtra in base a uno o piu' identificativi (separati da virgola es. 101,105,205).", "placeholder"=>"..."));

        $dlg->SetApplyButtonName("Filtra");

        return $dlg->GetObject();
    }

    //Template filtro di ricerca bozze
    public function TemplatePubblicateFilterDlg($params=array())
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
        //AA_Log::Log(__METHOD__." - ".print_r($options,true),100);
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

    // Task per la restituzione della finestra di dialogo di aggiunta nuovo ente
    public function Task_GetSicarAddNewEnteDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi immobili", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarAddNewEnteDlg(), true);
        return true;
    }

    // Task per la restituzione della finestra di dialogo di aggiunta nuovo nucleo
    public function Task_GetSicarAddNewNucleoDlg($task)
    {
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi nuclei", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarAddNewNucleoDlg(), true);
        return true;
    }

    // Task per la finestra di modifica dati generali immobile
    public function Task_GetSicarModifyEnteDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare l'ente");
            return false;
        }

        $object = new AA_SicarEnte();
        if (!$object->Load($_REQUEST['id'],$this->oUser)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarModifyEnteDlg($object),true);
        return true;
    }

    // Task per la finestra di modifica dati generali nucleo
    public function Task_GetSicarModifyNucleoDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare il nucleo");
            return false;
        }

        $object = new AA_SicarNucleo();
        if (!$object->Load($_REQUEST['id'],$this->oUser)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarModifyNucleoDlg($object),true);
        return true;
    }

    // Task per la finestra di modifica dati generali immobile
    public function Task_GetSicarModifyImmobileDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare l'immmobile");
            return false;
        }

        $object = new AA_SicarImmobile();
        if (!$object->Load($_REQUEST['id'],$this->oUser)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarModifyImmobileDlg($object),true);
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

    // Task per la finestra di aggiunta nuovo stato alloggio
    public function Task_GetSicarAddNewStatoOccupazioneAlloggioDlg($task)
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
        $task->SetContent($this->Template_GetSicarAddNewStatoOccupazioneAlloggioDlg($object),true);
        return true;
    }

    // Task per la finestra di modifica stato alloggio
    public function Task_GetSicarModifyStatoOccupazioneAlloggioDlg($task)
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

         if (empty($_REQUEST['dal'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non e' stata specificato l'identificativo della data di inizio occupazione", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarModifyStatoOccupazioneAlloggioDlg($object,$_REQUEST['dal']),true);
        return true;
    }

    // Task per la finestra di eliminazione stato alloggio
    public function Task_GetSicarDeleteStatoOccupazioneAlloggioDlg($task)
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

         if (empty($_REQUEST['dal'])) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Non e' stata specificato l'identificativo della data di inizio occupazione", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarDeleteStatoOccupazioneAlloggioDlg($object,$_REQUEST['dal']),true);
        return true;
    }

    // Task per la finestra visualizzazione dettaglio immobile
    public function Task_GetSicarDetailImmobileDlg($task)
    {
        // Controllo permessi e validità id
        if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $object = new AA_SicarImmobile();
        if (!$object->Load($_REQUEST['id'],$this->oUser)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo oggetto non valido.", false);
            return false;
        }

        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent($this->Template_GetSicarDetailImmobileDlg($object),true);
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
        
        if(isset($_REQUEST['superficie_utile_abitabile']) && $_REQUEST['superficie_utile_abitabile']!="")
        {
            $_REQUEST['superficie_utile_abitabile']=str_replace(",",".",str_replace(".","",$_REQUEST['superficie_utile_abitabile']));
        }
        if(isset($_REQUEST['superficie_netta']) && $_REQUEST['superficie_netta']!="")
        {
            $_REQUEST['superficie_netta']=str_replace(",",".",str_replace(".","",$_REQUEST['superficie_netta']));
        }

        if(empty($_REQUEST['gestione_ente']) || empty($_REQUEST['gestione_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare un ente gestore e una data iniziale di gestione.", false);
            return false;
        }

        $gestione=array(mb_substr($_REQUEST['gestione_dal'],0,10)=>$_REQUEST['gestione_ente']);

        $_REQUEST['gestione']=json_encode($gestione);

        // Utilizza il metodo generico della classe base
        return $this->Task_GenericAddNew($task, $_REQUEST);
    }

    // Task per aggiungere un nuovo stato alloggio
    public function Task_AddNewStatoOccupazioneAlloggioSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare lo stato di occupazione dell'alloggio", false);
            return false;
        }
        
        // Verifica che l'utente abbia i permessi di scrittura sull'oggetto specifico
        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if ($id > 0) {
            $alloggio = new AA_SicarAlloggio($id, $this->oUser);
            if ($alloggio->IsValid()) {
                if (($alloggio->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("L'utente corrente non ha i permessi di modifica dello stato di occupazione dell'alloggio", false);
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

        $occupazione=$alloggio->GetOccupazione();

        if(empty($_REQUEST['data_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare la data di inizio del nuovo stato di occupazione.", false);
            return false;
        }

        $dettaglioOccupazione=array();
        $dettaglioOccupazione['stato']=0;
        $dettaglioOccupazione['note']=$_REQUEST['note'];

        if(!empty($_REQUEST['stato']) && intval($_REQUEST['stato'])>=1)
        {
            $dettaglioOccupazione['stato']=1;
            if(empty($_REQUEST['occupazione_id_nucleo']) || $_REQUEST['occupazione_id_nucleo']<=0)
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("E' necessario specificare il nucleo occupante per lo stato di occupazione selezionato.", false);
                return false;
            }
            $dettaglioOccupazione['occupazione_id_nucleo']=$_REQUEST['occupazione_id_nucleo'];

            if(empty($_REQUEST['occupazione_data_assegnazione']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("E' necessario specificare la data di assegnazione per lo stato di occupazione selezionato.", false);
                return false;
            }

            //Aggiorna il nucleo per assegnare l'alloggio al nucleo se e' l'utlimo stato di occupazione
            $update_nucleo=true;
            if(!empty($occupazione))
            {
                $last_date=current(array_keys($occupazione));
                if(strtotime($last_date)>strtotime(mb_substr($_REQUEST['data_dal'],0,10)))
                {
                    //non e' l'ultimo stato di occupazione, non aggiorna il nucleo
                    AA_Log::Log(__METHOD__." - Lo stato di occupazione inserito non e' il piu' recente, non viene aggiornato il nucleo.",100);
                    $update_nucleo=false;
                }
            }

            if($update_nucleo)
            {
                $nucleo=new AA_SicarNucleo();
                if($nucleo->Load($_REQUEST['occupazione_id_nucleo']))
                {
                    $nucleo->SetProp("alloggio_attuale",$alloggio->GetID());

                    //aggiorna l'indirizzo di residenza del nucleo se richiesto
                    if(!empty($_REQUEST['occupazione_residenza']) && $_REQUEST['occupazione_residenza']==1)
                    {
                        $immobile=$alloggio->GetImmobile();
                        $nucleo->SetProp("indirizzo",$immobile->GetIndirizzo());
                        $nucleo->SetProp("comune",$immobile->GetComune(false));
                    }
                    $nucleo->Sync($this->oUser);  
                }
                else
                {
                    AA_Log::Log(__METHOD__." - Impossibile caricare il nucleo con id: ".$_REQUEST['occupazione_id_nucleo'],100);
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("Nucleo occupante non trovato.", false);
                    return false;
                }
            }

            $dettaglioOccupazione['occupazione_data_assegnazione']=mb_substr($_REQUEST['occupazione_data_assegnazione'],0,10);

            $dettaglioOccupazione['occupazione_tipo_canone']=!empty($_REQUEST['occupazione_tipo_canone']) ? $_REQUEST['occupazione_tipo_canone'] : 0;

            $dettaglioOccupazione['occupazione_tipo']=!empty($_REQUEST['occupazione_tipo']) ? $_REQUEST['occupazione_tipo'] : 0;
            $dettaglioOccupazione['occupazione_residenza']=!empty($_REQUEST['occupazione_residenza']) ? 1 : 0;

            //rimuove l'alloggio dal nucleo a cui era assegnato in precedenza, se presente
            $last_occupazione=current($occupazione);
            if($last_occupazione['occupazione_id_nucleo']>0 && $last_occupazione['occupazione_id_nucleo']!=$dettaglioOccupazione['occupazione_id_nucleo'])
            {
                $nucleo=new AA_SicarNucleo();
                if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                {
                    $nucleo->SetProp("alloggio_attuale",0);
                    $nucleo->SetProp("indirizzo","n.d.");
                    $nucleo->SetProp("comune","");
                    $nucleo->Sync($this->oUser);
                }
                else
                {
                    AA_Log::Log(__METHOD__." - Impossibile caricare il nucleo con id: ".$last_occupazione['occupazione_id_nucleo'],100); 
                }
            }
        }
        else
        {
            $dettaglioOccupazione['occupazione_id_nucleo']=0;
            $dettaglioOccupazione['occupazione_data_assegnazione']="";
            $dettaglioOccupazione['occupazione_tipo_canone']=0;
            $dettaglioOccupazione['occupazione_tipo']=0;
            $dettaglioOccupazione['occupazione_residenza']=0;
        }

        $occupazione[mb_substr($_REQUEST['data_dal'],0,10)]=$dettaglioOccupazione;
        
        //ordina l'arrai in modo che la data piu' recente sia la prima
        krsort($occupazione, SORT_STRING);

        $_REQUEST['occupazione']=json_encode($occupazione);

        $alloggio->Parse($_REQUEST);

        $validate = $alloggio->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$alloggio->Update($this->oUser,true,"Aggiunta nuovo stato di occupazione"))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiunta del nuovo stato di occupazione.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
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

        $attributi=array();
        if(empty($_REQUEST['attributi']))
        {
            $attributi['condominio_misto']=0;
            if(!empty($_REQUEST['attributi_condominio_misto'])) $attributi['condominio_misto']=1;

            //num alloggi
            $attributi['alloggi']=0;
            if(!empty($_REQUEST['attributi_alloggi'])) $attributi['alloggi']=intval($_REQUEST['attributi_alloggi']);

            //ente gestore
            $attributi['gestione']=array();
            if(!empty($_REQUEST['immobile_gestione_ente']) && !empty($_REQUEST['immobile_gestione_dal'])) 
            {
                $attributi['gestione']=array(mb_substr($_REQUEST['immobile_gestione_dal'],0,10)=>$_REQUEST['immobile_gestione_ente']);
            }
            else
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre specificare un ente gestore e una data iniziale di gestione", false);
                return false;
            }
        }

        $_REQUEST['attributi']=json_encode($attributi);
       
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

    // Task per aggiungere un nuovo ente
    public function Task_AddNewEnteSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi enti", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun dato indicato per il nuovo ente", false);
            return false;
        }
       
        $var = new AA_SicarEnte($_REQUEST);
        $validate=$var->Validate();
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

        if(!$var->Sync($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiunta dell'ente", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Ente aggiunto con successo", false);
        return true;
    }

    // Task per aggiungere un nuovo nucleo
    public function Task_AddNewNucleoSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi enti", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun dato indicato per il nuovo nucleo", false);
            return false;
        }
       
        //comune
        if(!empty($_REQUEST['comune']))
        {
            $_REQUEST['comune']=mb_substr($_REQUEST['comune'],-5,4);
        }

        $var = new AA_SicarNucleo($_REQUEST);
        $validate=$var->Validate();
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

        if(!$var->Sync($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiunta dell'ente", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Ente aggiunto con successo", false);
        return true;
    }

    // Task per modificare un ente esistente
    public function Task_UpdateEnteSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare enti", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun ente indicato", false);
            return false;
        }
       

        $var = new AA_SicarEnte();
        if(!$var->Load($_REQUEST['id']))
        {
            AA_Log::Log(__METHOD__." - Ente non valido.",100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo ente non specificato o non valido.", false);
            return false;
        }
        $var->Parse($_REQUEST);
        $validate=$var->Validate();
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

        if(!$var->Sync($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dell'ente", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Ente aggiornato con successo", false);
        return true;
    }

    // Task per modificare un nucleo esistente
    public function Task_UpdateNucleoSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare i nuclei", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun nucleo indicato", false);
            return false;
        }
       
        //comune
        if(!empty($_REQUEST['comune']))
        {
            $_REQUEST['comune']=mb_substr($_REQUEST['comune'],-5,4);
        }

        $var = new AA_SicarNucleo();
        if(!$var->Load($_REQUEST['id']))
        {
            AA_Log::Log(__METHOD__." - Nucleo non valido.",100);
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Identificativo nucleo non specificato o non valido.", false);
            return false;
        }
        $var->Parse($_REQUEST);
        $validate=$var->Validate();
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

        if(!$var->Sync($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento del nucleo", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Nucleo aggiornato con successo", false);
        return true;
    }

    // Task per aggiornare un immobile esistente
    public function Task_UpdateImmobileSicar($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per modificare l'immobile", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun dato indicato per l'immobile", false);
            return false;
        }

        $immobile=new AA_SicarImmobile();
        if(!$immobile->Load($_REQUEST['id']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Immobile non trovato (id: ".$_REQUEST['id'].")", false);
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
            
            $_REQUEST['catasto']=json_encode($catasto);
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
       
        $attributi=$immobile->GetAttributi();
        if(empty($_REQUEST['attributi']))
        {
            $attributi['condominio_misto']=0;
            if(!empty($_REQUEST['attributi_condominio_misto'])) $attributi['condominio_misto']=1;

            //num alloggi
            $attributi['alloggi']=0;
            if(!empty($_REQUEST['attributi_alloggi'])) $attributi['alloggi']=intval($_REQUEST['attributi_alloggi']);

            //ente gestore
            $attributi['gestione']=array();
            if(!empty($_REQUEST['immobile_gestione_ente']) && !empty($_REQUEST['immobile_gestione_dal'])) 
            {
                $attributi['gestione']=array(mb_substr($_REQUEST['immobile_gestione_dal'],0,10)=>$_REQUEST['immobile_gestione_ente']);
            }
            else
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("Occorre specificare un ente gestore e una data iniziale di gestione", false);
                return false;
            }
        }
        else
        {
            $new_attributi=json_decode($_REQUEST['attributi'],true);
      
            if(is_array($new_attributi)) $attributi=$new_attributi;
        }       

        $_REQUEST['attributi']=json_encode($attributi);
        //AA_Log::Log(__METHOD__." - Attributi: ".print_r($attributi,true),100);

        $immobile->Parse($_REQUEST);

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
            $task->SetError("Errore nell'aggiornamento dell'immobile", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Immobile aggiornato con successo", false);
        return true;
    }

    // Task per eliminare un immobile esistente
    public function Task_DeleteImmobileSicar($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per eliminare l'immobile", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun dato indicato per l'immobile", false);
            return false;
        }

        $immobile=new AA_SicarImmobile();
        if(!$immobile->Load($_REQUEST['id']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Immobile non trovato (id: ".$_REQUEST['id'].")", false);
            return false;
        }

        if(!$immobile->Delete($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'eliminazione dell'immobile", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Immobile eliminato con successo", false);
        return true;
    }
    
    // Task per eliminare un nucleo esistente
    public function Task_DeleteNucleoSicar($task)
    {
        //AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
        
        // Verifica che l'utente abbia i permessi per aggiungere nuovi alloggi
        if (!$this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("L'utente corrente non ha i permessi per eliminare il nucleo", false);
            return false;
        }
        
        if(empty($_REQUEST))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nessun dato indicato per nucleo", false);
            return false;
        }

        $nucleo=new AA_SicarNucleo();
        if(!$nucleo->Load($_REQUEST['id']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Nucleo non trovato (id: ".$_REQUEST['id'].")", false);
            return false;
        }

        if(!$nucleo->Delete($this->oUser))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'eliminazione del nucleo", false);
            return false;
        }
        $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
        $task->SetContent("Nucleo eliminato con successo", false);
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
        
        if(isset($_REQUEST['superficie_utile_abitabile']) && $_REQUEST['superficie_utile_abitabile']!="")
        {
            $_REQUEST['superficie_utile_abitabile']=str_replace(",",".",str_replace(".","",$_REQUEST['superficie_utile_abitabile']));
        }
        if(isset($_REQUEST['superficie_non_residenziale']) && $_REQUEST['superficie_non_residenziale']!="")
        {
            $_REQUEST['superficie_non_residenziale']=str_replace(",",".",str_replace(".","",$_REQUEST['superficie_non_residenziale']));
        }
        if(isset($_REQUEST['superficie_parcheggi']) && $_REQUEST['superficie_parcheggi']!="")
        {
            $_REQUEST['superficie_parcheggi']=str_replace(",",".",str_replace(".","",$_REQUEST['superficie_parcheggi']));
        }
        if(isset($_REQUEST['vani_abitabili']) && $_REQUEST['vani_abitabili']!="")
        {
            $_REQUEST['vani_abitabili']=str_replace(",",".",str_replace(".","",$_REQUEST['vani_abitabili']));
        }
        
        if(empty($_REQUEST['gestione_ente']) || empty($_REQUEST['gestione_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("E' necessario specificare un ente gestore e una data iniziale di gestione.", false);
            return false;
        }

        $gestione=array(mb_substr($_REQUEST['gestione_dal'],0,10)=>$_REQUEST['gestione_ente']);

        $_REQUEST['gestione']=json_encode($gestione);
    
        $alloggio->Parse($_REQUEST);

        $validate = $alloggio->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true)." - ".print_r($alloggio, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

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

    // Task per aggiornare uno stato di occupazione di un alloggio
    public function Task_UpdateStatoOccupazioneAlloggioSicar($task)
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
        
        $occupazione=$alloggio->GetOccupazione();

        if(empty($_REQUEST['data_dal']))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Stato di occupazione non valido.", false);
            return false;
        }

        $dettaglioOccupazione=array();
        $dettaglioOccupazione['stato']=0;
        $dettaglioOccupazione['note']=$_REQUEST['note'];
        
        //Aggiorna il nucleo associato se e' l'ultimo stato di occupazione
        $update_nucleo=true;
        if(!empty($occupazione))
        {
            $last_date=current(array_keys($occupazione));
            if(strtotime($last_date) > strtotime(mb_substr($_REQUEST['data_dal'],0,10)))
            {
                //non e' l'ultimo stato di occupazione, non aggiorna il nucleo
                AA_Log::Log(__METHOD__." - Lo stato di occupazione inserito non e' il piu' recente, non viene aggiornato il nucleo.",100);
                $update_nucleo=false;
            }
        }

        if(!empty($_REQUEST['stato']) && intval($_REQUEST['stato'])>=1)
        {
            $dettaglioOccupazione['stato']=1;
            if(empty($_REQUEST['occupazione_id_nucleo']) || $_REQUEST['occupazione_id_nucleo']<=0)
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("E' necessario specificare il nucleo occupante per lo stato di occupazione selezionato.", false);
                return false;
            }
            
            if(empty($_REQUEST['occupazione_data_assegnazione']))
            {
                $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                $task->SetError("E' necessario specificare la data di assegnazione per lo stato di occupazione selezionato.", false);
                return false;
            }

            $dettaglioOccupazione['occupazione_id_nucleo']=$_REQUEST['occupazione_id_nucleo'];
            
            if($update_nucleo)
            {
                //rimuove l'alloggio dal nucleo a cui era assegnato in precedenza, se presente
                $last_occupazione=current($occupazione);
                AA_Log::Log(__METHOD__." - Last occupazione: ".print_r($last_occupazione,true),100);
                if($last_occupazione['occupazione_id_nucleo']>0 && $last_occupazione['occupazione_id_nucleo']!=$dettaglioOccupazione['occupazione_id_nucleo'])
                {
                    $nucleo=new AA_SicarNucleo();
                    if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                    {
                        $nucleo->SetProp("alloggio_attuale",0);
                        $nucleo->SetProp("indirizzo","n.d.");
                        $nucleo->SetProp("comune","");
                        $nucleo->Sync($this->oUser);
                    }
                    else
                    {
                        AA_Log::Log(__METHOD__." - Impossibile caricare il nucleo con id: ".$last_occupazione['occupazione_id_nucleo'],100); 
                    }
                }

                //Aggiorna il nucleo per assegnare l'alloggio al nucleo
                $nucleo=new AA_SicarNucleo();
                if($nucleo->Load($_REQUEST['occupazione_id_nucleo']))
                {
                    $nucleo->SetProp("alloggio_attuale",$alloggio->GetID());

                    //aggiorna l'indirizzo di residenza del nucleo se richiesto
                    if(!empty($_REQUEST['occupazione_residenza']) && $_REQUEST['occupazione_residenza']==1)
                    {
                        $immobile=$alloggio->GetImmobile();
                        $nucleo->SetProp("indirizzo",$immobile->GetIndirizzo());
                        $nucleo->SetProp("comune",$immobile->GetComune(false));
                    }
                    $nucleo->Sync($this->oUser);  
                }
                else
                {
                    AA_Log::Log(__METHOD__." - Impossibile caricare il nucleo con id: ".$_REQUEST['occupazione_id_nucleo'],100);
                    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
                    $task->SetError("Nucleo occupante non trovato.", false);
                    return false;
                }
            }

            $dettaglioOccupazione['occupazione_data_assegnazione']=mb_substr($_REQUEST['occupazione_data_assegnazione'],0,10);

            $dettaglioOccupazione['occupazione_tipo_canone']=!empty($_REQUEST['occupazione_tipo_canone']) ? $_REQUEST['occupazione_tipo_canone'] : 0;

            $dettaglioOccupazione['occupazione_tipo']=!empty($_REQUEST['occupazione_tipo']) ? $_REQUEST['occupazione_tipo'] : 0;
            $dettaglioOccupazione['occupazione_residenza']=!empty($_REQUEST['occupazione_residenza']) ? 1 : 0;
        }
        else
        {
            if($update_nucleo)
            {
                //rimuove l'alloggio dal nucleo a cui era assegnato in precedenza, se presente
                $last_occupazione=current($occupazione);
                AA_Log::Log(__METHOD__." - Last occupazione: ".print_r($last_occupazione,true),100);
                if($last_occupazione['occupazione_id_nucleo']>0 && $last_occupazione['occupazione_id_nucleo']!=$dettaglioOccupazione['occupazione_id_nucleo'])
                {
                    $nucleo=new AA_SicarNucleo();
                    if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
                    {
                        $nucleo->SetProp("alloggio_attuale",0);
                        $nucleo->SetProp("indirizzo","n.d.");
                        $nucleo->SetProp("comune","");
                        $nucleo->Sync($this->oUser);
                    }
                    else
                    {
                        AA_Log::Log(__METHOD__." - Impossibile caricare il nucleo con id: ".$last_occupazione['occupazione_id_nucleo'],100); 
                    }
                }
            }

            $dettaglioOccupazione['occupazione_id_nucleo']=0;
            $dettaglioOccupazione['occupazione_data_assegnazione']="";
            $dettaglioOccupazione['occupazione_tipo_canone']=0;
            $dettaglioOccupazione['occupazione_tipo']=0;
            $dettaglioOccupazione['occupazione_residenza']=0;
        }

        $occupazione[mb_substr($_REQUEST['data_dal'],0,10)]=$dettaglioOccupazione;
        
        //ordina l'arrai in modo che la data piu' recente sia la prima
        krsort($occupazione, SORT_STRING);

        $_REQUEST['occupazione']=json_encode($occupazione);
        
        $alloggio->Parse($_REQUEST);

        $validate = $alloggio->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$alloggio->Update($this->oUser,true,"Aggiornamento stato occupazione dal: ".mb_substr($_REQUEST['data_dal'],0,10)))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dello stato di occupazione.",false);

            return false;
        }
        else
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
            $task->SetContent("Dati aggiornati.",false);

            return true;
        }
    }
    
    // Task per rimuovere uno stato di occupazione di un alloggio
    public function Task_DeleteStatoOccupazioneAlloggioSicar($task)
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
        
        $occupazione=$alloggio->GetOccupazione();
        
        //Verifica se deve agiornare il nucleo associato
        $update_nucleo=true;
        if(!empty($occupazione))
        {
            $last_date=current(array_keys($occupazione));
            if(strtotime($last_date)>strtotime(mb_substr($_REQUEST['dal'],0,10)))
            {
                //non e' l'ultimo stato di occupazione, non aggiorna il nucleo
                AA_Log::Log(__METHOD__." - Lo stato di occupazione inserito non e' il piu' recente, non viene aggiornato il nucleo.",100);
                $update_nucleo=false;
            }
        }

        if(empty($_REQUEST['dal']) || !isset($occupazione[mb_substr($_REQUEST['dal'],0,10)]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Stato di occupazione non valido o non presente.", false);
            return false;
        }

        if(!isset($occupazione[mb_substr($_REQUEST['dal'],0,10)]))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Stato di occupazione non valido o non presente.", false);
            return false;
        }

        $thisOccupazione=$occupazione[mb_substr($_REQUEST['dal'],0,10)];
        if(key($occupazione)==mb_substr($_REQUEST['dal'],0,10) && $update_nucleo)
        {
            //se si sta eliminando lo stato di occupazione piu' recente con un nucleo oassociato, occorre inserire un nuovo stato di occupazione libero come stato sostitutivo
            $occupazione[mb_substr($_REQUEST['dal'],0,10)]=array(
                'stato'=>0,
                'occupazione_id_nucleo'=>0,
                'occupazione_data_assegnazione'=>'',
                'occupazione_tipo_canone'=>0,
                'occupazione_tipo'=>0,
                'occupazione_residenza'=>0,
                'note'=>''
            );
        }
        else
        {
            //rimuove lo stato di occupazione selezionato
            unset($occupazione[mb_substr($_REQUEST['dal'],0,10)]);
        }

        if($update_nucleo)
        {
            //rimuove l'alloggio dal nucleo a cui era assegnato in precedenza, se presente
            AA_Log::Log(__METHOD__." - Last occupazione: ".print_r($thisOccupazione,true),100);
            if($thisOccupazione['occupazione_id_nucleo']>0)
            {
                $nucleo=new AA_SicarNucleo();
                if($nucleo->Load($thisOccupazione['occupazione_id_nucleo']))
                {
                    $nucleo->SetProp("alloggio_attuale",0);
                    $nucleo->SetProp("indirizzo","n.d.");
                    $nucleo->SetProp("comune","");
                    $nucleo->Update($this->oUser);
                }
                else
                {
                    AA_Log::Log(__METHOD__." - Impossibile caricare il nucleo con id: ".$thisOccupazione['occupazione_id_nucleo'],100); 
                }
            }
        }
        
        //ordina l'arrai in modo che la data piu' recente sia la prima
        krsort($occupazione, SORT_STRING);
        
        $alloggio->setOccupazione($occupazione);

        $validate = $alloggio->Validate();
        if (sizeof($validate) > 0) 
        {
            AA_Log::Log(__METHOD__ . " - Sono stati trovati i seguenti errori: " . print_r($validate, true), 100);
            $error = "Sono state riscontrate le seguenti criticita': <br>";
            foreach ($validate as $curError) {
                $error .= "<li>" . $curError . "</li>";
            }
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError($error, false);
            return false;
        }

        if(!$alloggio->Update($this->oUser,true,"Eliminazione stato occupazione dal: ".mb_substr($_REQUEST['dal'],0,10)))
        {
            $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
            $task->SetError("Errore nell'aggiornamento dello stato di occupazione.",false);

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
        $value = "<span>" . $object->GetAnnoRistrutturazione() . "</span>";
        $ultima_ristrutturazione = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Anno ultima ristrutturazione:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        // superficie
        $superficie="";
        if(!empty($object->GetSuperficieNonResidenziale())) $superficie.="<span>Non residenziale: ".$object->GetSuperficieNonResidenziale()."</span>";
        else $superficie.="<span>Non residenziale: n.d.</span>";
        if(!empty($superficie)) $superficie.=" - "; 
        if(!empty($object->GetSuperficieParcheggi())) $superficie.=" <span>parcheggi: ".$object->GetSuperficieParcheggi()."</span>";
        else $superficie.=" <span>parcheggi: n.d.</span>";
        if(!empty($object->GetSuperficieUtileAbitabile())) 
        {
            if(!empty($superficie)) $superficie.=" - "; 
            $superficie.=" <span>abitabile: ".$object->GetSuperficieUtileAbitabile()."</span>";
        }
        else 
        {
            if(!empty($superficie)) $superficie.=" - "; 
            $superficie.=" <span>abitabile: n.d.</span>";
        }

        $value =  $superficie;
        $superficie = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 2,
            "data" => array("title" => "Superfici (mq):", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //piano
        if(!empty($object->GetPiano())) $value = "<span>" . $object->GetPiano() . "</span>";
        else $value = "<span>terra</span>";
        $piano = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Piano:", "value" => $value),
            "css" => array("border-bottom" => "1px solid #dadee0 !important")
        ));

        //vani abitabili
        if(!empty($object->GetVaniAbitabili())) $value = "<span>" . $object->GetVaniAbitabili() . "</span>";
        else $value = "<span>n.d.</span>";
        $piano = new AA_JSON_Template_Template("", array(
            "template" => "<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "gravity" => 1,
            "data" => array("title" => "Vani abitabili:", "value" => $value),
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
        $val=$object->GetFruibileDis();
        $color="LightGreen";
        
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
        //$layout_riga=new AA_JSON_Template_Layout("",array("gravity"=>1,"type"=>"clean"));
        
        //immobile
        $immobile_obj=$object->GetImmobile();
        $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailImmobileDlg", params: [{id:"'.$immobile_obj->GetProp("id").'"}]},"'.$this->id.'")';
        $immobile=new AA_JSON_Template_Template("",array(
            "maxHeight"=>100,
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Immobile:","value"=>"<a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'>".$immobile_obj->GetDescrizione()."</a><br>".$immobile_obj->GetIndirizzo()." (".$immobile_obj->GetComune().") <a href='https://www.google.com/maps/search/?api=1&query=".$immobile_obj->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a><br><i>".$immobile_obj->GetTipologia()."</i>")
        ));
        $riga->addCol($immobile);

        //Gestore
        $gestore_obj=$object->GetGestore();
        if($gestore_obj) 
        {
            $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailEnteGestoreDlg", params: [{id:"'.$gestore_obj->GetProp("id").'"}]},"'.$this->id.'")';
            $detail_gestore=$gestore_obj->GetDenominazione();
            $gestore=new AA_JSON_Template_Template("",array(
                "maxHeight"=>100,
                "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "data"=>array("title"=>"Ente gestore:","value"=>"<a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'>".$detail_gestore."</a><br>dal ".$object->GetGestioneDaL()."</br>")
            ));
        }
        else 
        {
            $detail="";
            $detail_gestore="Ente gestore non definito";
            $gestore=new AA_JSON_Template_Template("",array(
                "maxHeight"=>100,
                "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
                "data"=>array("title"=>"Ente gestore:","value"=>$detail_gestore)
            ));

        }
       
        $riga->addCol($gestore);
        $layout->AddRow($riga);

        //note
        $value = $object->GetNote();
        $note=new AA_JSON_Template_Template("",array(
            "height"=>100,
            "template"=>"<span style='font-weight:700'>#title#</span><div>#value#</div>",
            "data"=>array("title"=>"Note:","value"=>$value)
        ));
        $layout->AddRow($note);

        $riga=new AA_JSON_Template_Layout("",array("type"=>"clean","css"=>array("border-bottom"=>"1px solid #dadee0 !important")));
        //occupazione
        $riga->AddCol($this->TemplateDettaglio_Occupazione($object,$id."_Occupazione",$canModify));
        $riga->AddCol($this->TemplateDettaglio_Interventi($object,$id."_Interventi",$canModify));
        $layout->AddRow($riga);
        
        return $layout;
    }

    //Template dettaglio occupazione
    public function TemplateDettaglio_Occupazione($object=null,$id="", $canModify=false)
    {
        #documenti----------------------------------
        $curId=$id."_Layout_Occupazione";
        $provvedimenti=new AA_JSON_Template_Layout($curId,array("type"=>"clean","gravity"=>4,"css"=>array("border-left"=>"1px solid gray !important;","border-top"=>"1px solid gray !important;")));

        $toolbar=new AA_JSON_Template_Toolbar($curId."_Toolbar_occupazione",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"<span style='color:#003380'>Stato occupazione</span>", "align"=>"center")));

        if($canModify)
        {
            //Pulsante di aggiunta documento
            $add_documento_btn=new AA_JSON_Template_Generic("",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-file-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi un nuovo stato occupazione",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewStatoOccupazioneAlloggioDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));

            $toolbar->AddElement($add_documento_btn);
        }
        else 
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        }

        $provvedimenti->AddRow($toolbar);

        $occupazioni=$object->GetOccupazione();
        $occupazione_data=[];
        $tipologia_occupazione_desc=AA_Sicar_Const::GetListaTipologieOccupazione(true);
        foreach($occupazioni as $dal=>$curOccupazione)
        {
            $nucleo_desc="n.d.";
            if($curOccupazione['stato'] >= 1)    
            {
                
                $nucleo=new AA_SicarNucleo();
                if($nucleo->Load($curOccupazione['occupazione_id_nucleo']))
                {
                    $nucleo_desc=$nucleo->GetDescrizione();
                }
            }

            if($curOccupazione['stato']>=1) 
            {
                $tipo_occupazione=$tipologia_occupazione_desc[$curOccupazione['occupazione_tipo']];
            }
            else $tipo_occupazione="libero";

            $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailStatoOccupazioneAlloggioDlg", params: [{id:"'.$object->GetId().'"},{dal:"'.$dal.'"}]},"'.$this->id.'")';
            $detail_icon="mdi mdi-eye";
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteStatoOccupazioneAlloggioDlg", params: [{id:"'.$object->GetId().'"},{dal:"'.$dal.'"}]},"'.$this->id.'")';
            $trash_icon="mdi mdi-trash-can";
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyStatoOccupazioneAlloggioDlg", params: [{id:"'.$object->GetId().'"},{dal:"'.$dal.'"}]},"'.$this->id.'")';
            $modify_icon="mdi mdi-pencil";
            $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
            
            $occupazione_data[]=array(
                "id"=>$dal,
                "dal"=>$dal,
                "tipologia_desc"=>$tipo_occupazione,
                "nucleo"=>$curOccupazione['occupazione_id_nucleo'],
                "nucleo_desc"=>$nucleo_desc,
                "canone"=>$curOccupazione['occupazione_tipo_canone'],
                "note"=>$curOccupazione['note'],
                "ops"=>$ops
            );
        }

        if(!$canModify) $template=new AA_GenericDatatableTemplate($id,"",3,array("type"=>"clean"),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"",4,array("type"=>"clean"),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(false);
        //$template->SetHeaderHeight(38);
        $template->EnableAddNew(false);
        $template->SetColumnHeaderInfo(0,"tipologia_desc","<div style='text-align: center'>Tipo</div>",160,"selectFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(1,"dal","<div style='text-align: center'>Dal</div>",100,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(2,"nucleo_desc","<div style='text-align: center'>Nucleo</div>","fillspace","textFilter","text","GenericAutosizedRowTable");
        //$template->SetColumnHeaderInfo(3,"canone","<div style='text-align: center'>Canone</div>",100,"textFilter","text","GenericAutosizedRowTable");
        //$template->SetColumnHeaderInfo(4,"note","<div style='text-align: center'>Note</div>",90,"","","GenericAutosizedRowTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if($canModify) $template->SetColumnHeaderInfo(3,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"GenericAutosizedRowTable");

        $template->SetData($occupazione_data);
        #--------------------------------------
        
        $provvedimenti->AddRow($template);
        return $provvedimenti;
    }

    //Template dettaglio interventi
    public function TemplateDettaglio_Interventi($object=null,$id="", $canModify=false)
    {
        #documenti----------------------------------
        $curId=$id."_Layout_Interventi";
        $provvedimenti=new AA_JSON_Template_Layout($curId,array("type"=>"clean","gravity"=>4,"css"=>array("border-left"=>"1px solid gray !important;","border-top"=>"1px solid gray !important;")));

        $toolbar=new AA_JSON_Template_Toolbar($curId."_Toolbar_interventi",array("height"=>38, "css"=>array("background"=>"#dadee0 !important;")));
        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));

        $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"label","label"=>"<span style='color:#003380'>Stato interventi</span>", "align"=>"center")));

        if($canModify)
        {
            //Pulsante di aggiunta documento
            $add_documento_btn=new AA_JSON_Template_Generic("",array(
               "view"=>"button",
                "type"=>"icon",
                "icon"=>"mdi mdi-file-plus",
                "label"=>"Aggiungi",
                "align"=>"right",
                "width"=>120,
                "tooltip"=>"Aggiungi un intervento",
                "click"=>"AA_MainApp.utils.callHandler('dlg', {task:\"GetSicarAddNewInterventoDlg\", params: [{id: ".$object->GetId()."}]},'$this->id')"
            ));

            $toolbar->AddElement($add_documento_btn);
        }
        else 
        {
            $toolbar->AddElement(new AA_JSON_Template_Generic("",array("view"=>"spacer","width"=>120)));
        }

        $provvedimenti->AddRow($toolbar);

        $interventi=$object->GetInterventi();
        $interventi_data=[];
        $tipologia_intervento_desc=AA_Sicar_Const::GetListaTipologieIntervento();
        foreach($interventi as $curIntervento)
        {
            $finanziamento_desc="n.d.";
            if($curIntervento['tipologia'] > 1)    
            {
                
                $finanziamento=new AA_SicarFinanziamento();
                if($finanziamento->Load($curIntervento['finanziamento']))
                {
                    $finanziamento_desc=$finanziamento->GetDenominazione();
                }
            }

            $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailInterventoDlg", params: [{id:"'.$curIntervento->GetProp("id").'"}]},"'.$this->id.'")';
            $detail_icon="mdi mdi-eye";
            $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteInterventoDlg", params: [{id:"'.$curIntervento->GetProp("id").'"}]},"'.$this->id.'")';
            $trash_icon="mdi mdi-trash-can";
            $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyInterventoDlg", params: [{id:"'.$curIntervento->GetProp("id").'"}]},"'.$this->id.'")';
            $modify_icon="mdi mdi-pencil";
            $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
            
            $intervento_data[]=array(
                "id"=>$curIntervento['id'],
                "data_inizio"=>$curIntervento['data_inizio'],
                "tipologia_desc"=>$tipologia_intervento_desc[$curIntervento['tipologia']],
                "finanziamento"=>$curIntervento['finanaziamento'],
                "finanziamento_desc"=>$finanziamento_desc,
                "ops"=>$ops
            );
        }

        if(!$canModify) $template=new AA_GenericDatatableTemplate($id,"",4,array("type"=>"clean"),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"",5,array("type"=>"clean"),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(true);
        $template->SetHeaderHeight(38);
        $template->EnableAddNew(false);
        $template->SetColumnHeaderInfo(0,"tipologia_desc","<div style='text-align: center'>Tipologia</div>",250,"textFilter","int","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(1,"dal","<div style='text-align: center'>Dal</div>","fillspace","textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(2,"finanziamento_desc","<div style='text-align: center'>Finanziamento</div>",250,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(3,"note","<div style='text-align: center'>Note</div>",250,"textFilter","text","GenericAutosizedRowTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if($canModify) $template->SetColumnHeaderInfo(4,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"GenericAutosizedRowTable");

        $template->SetData($intervento_data);
        #--------------------------------------
        
        $provvedimenti->AddRow($template);
        return $provvedimenti;
    }

    //Template section immobili
    public function TemplateSection_Immobili($params=array())
    {
        $id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_IMMOBILI;
        $canModify=false;

        #immobili----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //$layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        //filtrare in base al comune dell'operatore comunale
        //to do
        //-------------------------------------------------

        $immobili=AA_SicarImmobile::Search($params);
        $data=[];
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
            if($canModify)
            {
                $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailImmobileDlg", params: [{id:"'.$curImmobile->GetProp("id").'"}]},"'.$this->id.'")';
                $detail_icon="mdi mdi-eye";
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteImmobileDlg", params: [{id:"'.$curImmobile->GetProp("id").'"}]},"'.$this->id.'")';
                $trash_icon="mdi mdi-trash-can";
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyImmobileDlg", params: [{id:"'.$curImmobile->GetProp("id").'"}]},"'.$this->id.'")';
                $modify_icon="mdi mdi-pencil";

                //alloggi
                //$alloggi_list='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarLiastaAlloggiImmobileDlg", params: [{id:"'.$curImmobile->GetProp("id").'"}]},"'.$this->id.'")';
                $alloggi_list='try{module=AA_MainApp.curModule; if(module.isValid()) {console.log(module); module.setRuntimeValue("' . static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX . '","filter_data", '.json_encode(array("immobile"=>$curImmobile->GetProp("id"),"immobile_desc"=>$curImmobile->GetDisplayName())).');} AA_MainApp.curModule.setCurrentSection("'.static::AA_ID_SECTION_PUBBLICATE.'")}catch(msg){console.error(msg)}';
                $alloggi_list_icon="mdi mdi-home-search";

                $gestore=$curImmobile->GetGestore();
                $gestore_desc="Nessuno";
                if($gestore instanceof AA_SicarEnte)
                {
                    $gestore_desc=$gestore->GetDenominazione();
                }

                $num_alloggi=$curImmobile->GetNumeroAlloggiTot();
                $alloggi_censiti=sizeof($curImmobile->GetAlloggi());
                $condominio_misto="No";
                if($curImmobile->IsCondominioMisto()) $condominio_misto="Si";
                if($alloggi_censiti>0) $num_alloggi.=" (".sizeof($curImmobile->GetAlloggi())." <a class='AA_DataTable_Ops_Button' title='Visualizza gli alloggi censiti associati all&apos;immobile' onClick='".$alloggi_list."'><span class='".$alloggi_list_icon."'></span></a>)";
                else $num_alloggi.=" (0)";
                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
                $data[]=array("id"=>$curImmobile->GetProp("id"),"condominio_misto"=>$condominio_misto,"alloggi"=>$num_alloggi,"descrizione"=>$curImmobile->GetDescrizione(),"indirizzo"=>$curImmobile->GetIndirizzo()."(".AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curImmobile->GetProp("comune")).")<a href='https://www.google.com/maps/search/?api=1&query=".$curImmobile->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a>","gestore"=>$gestore_desc,"ops"=>$ops);
            }
            else
            {
                $gestore=$curImmobile->GetGestore();
                $gestore_desc="Nessuno";
                if($gestore instanceof AA_SicarEnte)
                {
                    $gestore_desc=$gestore->GetDenominazione();
                }
                $num_alloggi=$curImmobile->GetNumeroAlloggiTot();
                $alloggi_censiti=sizeof($curImmobile->GetAlloggi());
                if($alloggi_censiti>0) $num_alloggi.=" (".sizeof($curImmobile->GetAlloggi())." <a class='AA_DataTable_Ops_Button' title='Visualizza gli alloggi censitiassociati all&apos;immobile' onClick='".$alloggi_list."'><span class='".$alloggi_list_icon."'></span></a>)";
                else $num_alloggi.=" (0)";
                $condominio_misto="No";
                if($curImmobile->IsCondominioMisto()) $condominio_misto="Si";
                $data[]=array("id"=>$curImmobile->GetProp("id"),"condominio_misto"=>$condominio_misto,"alloggi"=>$num_alloggi,"descrizione"=>$curImmobile->GetDescrizione(),"indirizzo"=>$curImmobile->GetIndirizzo()."(".AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curImmobile->GetProp("comune")).")<a href='https://www.google.com/maps/search/?api=1&query=".$curImmobile->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a>","gestore"=>$gestore_desc);
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id,"",5,array("type"=>"clean","name"=>static::AA_UI_SECTION_IMMOBILI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"",6,array("type"=>"clean","name"=>static::AA_UI_SECTION_IMMOBILI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(true);
        $template->SetHeaderHeight(38);

        
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewImmobileDlg");
            $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1)));
        }

        $template->SetColumnHeaderInfo(0,"descrizione","<div style='text-align: center'>Denominazione</div>",250,"textFilter","int","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo</div>","fillspace","textFilter","text","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(2,"condominio_misto","<div style='text-align: center'>Condominio Misto</div>",150,"selectFilter","text","ImmobiliTable");
        $template->SetColumnHeaderInfo(3,"alloggi","<div style='text-align: center'>Alloggi</div>",130,null,null,"ImmobiliTable");
        $template->SetColumnHeaderInfo(4,"gestore","<div style='text-align: center'>Gestore</div>",260,"textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(5,"ops","<div style='text-align: center'>Operazioni</div>",130,null,null,"ImmobiliTable");

        $template->SetData($data);

        //$layout->AddRow($template);

        return $template;
    }

    //Template section enti
    public function TemplateSection_Enti($params=array())
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_ENTI_BOX;
        $canModify=false;

        #enti----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //$layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $objs=AA_SicarEnte::Search($params);
        $data=[];

        $ops="";
        
        foreach($objs as $curObj)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if($canModify)
            {
                $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailEnteDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $detail_icon="mdi mdi-eye";
                $operatori='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarOperatoriEnteDlg", params: [{id_ente:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $operatori_icon="mdi mdi-account-multiple";
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteEnteDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $trash_icon="mdi mdi-trash-can";
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyEnteDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $modify_icon="mdi mdi-pencil";

                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Operatori' onClick='".$operatori."'><span class='mdi ".$operatori_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
                $data[]=array("id"=>$curObj->GetProp("id"),"denominazione"=>$curObj->GetDenominazione(),"indirizzo"=>$curObj->GetIndirizzo()."<a href='https://www.google.com/maps/search/?api=1&query=".$curObj->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a>","contatti"=>$curObj->GetContatti(false),"note"=>$curObj->GetNote(),"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curObj->GetProp("id"),"denominazione"=>$curObj->GetDenominazione(),"indirizzo"=>$curObj->GetIndirizzo()."<a href='https://www.google.com/maps/search/?api=1&query=".$curObj->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a>","contatti"=>$curObj->GetContatti(),"note"=>$curObj->GetNote());
            }
        }

        $nCols=4;
        if($canModify) $nCols=5;
        $template=new AA_GenericDatatableTemplate($id,"",$nCols,array("type"=>"clean","name"=>static::AA_UI_SECTION_ENTI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(true);
        $template->SetHeaderHeight(38);

        
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewEnteDlg");
            $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1)));
        }
        else
        {
            $template->EnableHeader(false);
        }

        $template->SetColumnHeaderInfo(0,"denominazione","<div style='text-align: center'>Denominazione</div>",250,"textFilter","int","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo</div>",400,"textFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(2,"contatti","<div style='text-align: center'>Contatti</div>","fillspace","textFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(3,"note","<div style='text-align: center'>Note</div>","fillspace","textFilter","text","GenericAutosizedRowTabl_left");
        if($canModify) 
        {
            $template->SetColumnHeaderInfo(4,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"GenericAutosizedRowTable");
        }

        $template->SetData($data);

        //$layout->AddRow($template);

        return $template;
    }

    //Template section nuclei
    public function TemplateSection_Nuclei($params=array())
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_NUCLEI_BOX;
        $canModify=false;

        #immobili----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //$layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $objs=AA_SicarNucleo::Search($params);
        $data=[];

        $ops="";
        
        foreach($objs as $curObj)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if($canModify)
            {
                $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailNucleoDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $detail_icon="mdi mdi-eye";
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteNucleoDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $trash_icon="mdi mdi-trash-can";
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyNucleoDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $modify_icon="mdi mdi-pencil";

                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
                
                $AlloggioAttuale=$curObj->GetAlloggioAttuale(true);
                if($AlloggioAttuale)
                {
                    $last_stato_assegnazione="<strong>".$AlloggioAttuale->GetDescrizione()."</strong>";
                }
                else
                {
                    $last_stato_assegnazione="Senza alloggio assegnato";
                }

                $indirizzo=$curObj->GetIndirizzo();
                if(!empty($curObj->GetProp("comune")))
                {
                    $indirizzo.=" (".$curObj->GetComune().")";
                }

                $data[]=array(
                    "id"=>$curObj->GetProp("id"),
                    "descrizione"=>$curObj->GetDescrizione(),
                    "indirizzo"=>$indirizzo,
                    "stato_assegnazione"=>$last_stato_assegnazione,
                    "componenti"=>sizeof($curObj->GetComponenti()),
                    "isee"=>$curObj->GetIseePreview(),
                    "cf"=>$curObj->GetCf(),
                    "ops"=>$ops
                );
            }
            else
            {
                $indirizzo=$curObj->GetIndirizzo();
                if(!empty($curObj->GetProp("comune")))
                {
                    $indirizzo.=" (".$curObj->GetComune().")";
                }

                $data[]=array(
                    "id"=>$curObj->GetProp("id"),
                    "descrizione"=>$curObj->GetDescrizione(),
                    "indirizzo"=>$indirizzo,
                    "stato_assegnazione"=>$last_stato_assegnazione,
                    "componenti"=>sizeof($curObj->GetComponenti()),
                    "isee"=>$curObj->GetIseePreview(),
                    "cf"=>$curObj->GetCf()
                );
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id,"",6,array("type"=>"clean","name"=>static::AA_UI_SECTION_NUCLEI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"",7,array("type"=>"clean","name"=>static::AA_UI_SECTION_NUCLEI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(true);
        $template->SetHeaderHeight(38);

        
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewNucleoDlg");
            $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1)));
        }

        $template->SetColumnHeaderInfo(0,"descrizione","<div style='text-align: center'>Descrizione</div>",250,"textFilter","int","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo di residenza</div>","fillspace","textFilter","text","GenericAutosizedRowTable_left");
        $template->SetColumnHeaderInfo(2,"cf","<div style='text-align: center'>Codice fiscale</div>",250,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(3,"stato_assegnazione","<div style='text-align: center'>Alloggio assegnato</div>",250,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(4,"componenti","<div style='text-align: center'>Componenti</div>",120,"textFilter","text","GenericAutosizedRowTable");
        $template->SetColumnHeaderInfo(5,"isee","<div style='text-align: center'>ISEE</div>",120,"textFilter","text","GenericAutosizedRowTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(6,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"GenericAutosizedRowTable");

        $template->SetData($data);

        return $template;
    }

    //Template section finanziamenti
    public function TemplateSection_Finanziamenti($params=array())
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_FINANZIAMENTI_BOX;
        $canModify=false;

        #immobili----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //$layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $objs=AA_SicarFinanziamento::Search($params);
        $data=[];

        $ops="";
        
        foreach($objs as $curObj)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if($canModify)
            {
                $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailFinanziamentoDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $detail_icon="mdi mdi-eye";
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteFinaziamentoDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $trash_icon="mdi mdi-trash-can";
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifyFinanziamentoDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $modify_icon="mdi mdi-pencil";

                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
                $data[]=array("id"=>$curObj->GetProp("id"),"descrizione"=>$curObj->GetDescrizione(),"indirizzo"=>$curObj->GetIndirizzo()."<a href='https://www.google.com/maps/search/?api=1&query=".$curObj->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a>","comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curObj->GetProp("comune")),"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curObj->GetProp("id"),"descrizione"=>$curObj->GetDescrizione(),"indirizzo"=>$curObj->GetIndirizzo(),"comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curObj->GetProp("comune")));
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id,"",3,array("type"=>"clean","name"=>static::AA_UI_SECTION_FINANZIAMENTI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"",4,array("type"=>"clean","name"=>static::AA_UI_SECTION_FINANZIAMENTI_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(true);
        $template->SetHeaderHeight(38);

        
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewFinanziamentoDlg");
            $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1)));
        }

        $template->SetColumnHeaderInfo(0,"descrizione","<div style='text-align: center'>Descrizione</div>",250,"textFilter","int","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(1,"indirizzo","<div style='text-align: center'>Indirizzo</div>","fillspace","textFilter","text","ImmobiliTable_left");
        $template->SetColumnHeaderInfo(2,"comune","<div style='text-align: center'>Comune</div>",250,"textFilter","text","ImmobiliTable");
        //$template->SetColumnHeaderInfo(3,"tipoDescr","<div style='text-align: center'>Categorie</div>","fillspace","textFilter","text","CriteriTable");
        if(!empty($ops)) $template->SetColumnHeaderInfo(3,"ops","<div style='text-align: center'>Operazioni</div>",120,null,null,"ImmobiliTable");

        $template->SetData($data);

        //$layout->AddRow($template);

        return $template;
    }

    //Template section graduatorie
    public function TemplateSection_Graduatorie($params=array())
    {
        $id=static::AA_UI_PREFIX."_".static::AA_UI_SECTION_GRADUATORIE_BOX;
        $canModify=false;

        #immobili----------------------------------
        if($this->oUser->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) $canModify=true;

        //$layout=new AA_JSON_Template_Layout($id,array("type"=>"clean", "filtered"=>true,"filter_id"=>$id));
        
        $objs=AA_SicarGraduatoria::Search($params);
        $data=[];

        $ops="";
        
        foreach($objs as $curObj)
        {
            //AA_Log::Log(__METHOD__." - criterio: ".print_r($curDoc,true),100);
            if($canModify)
            {
                $detail='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDetailGraduatoriaDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $detail_icon="mdi mdi-eye";
                $trash='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarDeleteGraduatoriaDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $trash_icon="mdi mdi-trash-can";
                $modify='AA_MainApp.utils.callHandler("dlg", {task:"GetSicarModifygraduatoriaDlg", params: [{id:"'.$curObj->GetProp("id").'"}]},"'.$this->id.'")';
                $modify_icon="mdi mdi-pencil";

                $ops="<div class='AA_DataTable_Ops' style='justify-content: space-evenly;width: 100%'><a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'><span class='mdi ".$detail_icon."'></span></a><a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'><span class='mdi ".$modify_icon."'></span></a><a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick='".$trash."'><span class='mdi ".$trash_icon."'></span></a></div>";
                $data[]=array("id"=>$curObj->GetProp("id"),"descrizione"=>$curObj->GetDescrizione(),"indirizzo"=>$curObj->GetIndirizzo()."<a href='https://www.google.com/maps/search/?api=1&query=".$curObj->GetGeolocalizzazione()."' target='_blank' alt='Visualizza su Google Maps' title='Visualizza su Google Maps'><span class='mdi mdi-google-maps'></a>","comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curObj->GetProp("comune")),"ops"=>$ops);
            }
            else
            {
                $data[]=array("id"=>$curObj->GetProp("id"),"descrizione"=>$curObj->GetDescrizione(),"indirizzo"=>$curObj->GetIndirizzo(),"comune"=>AA_Sicar_Const::GetComuneDescrFromCodiceIstat($curObj->GetProp("comune")));
            }
        }

        if(empty($ops)) $template=new AA_GenericDatatableTemplate($id,"",3,array("type"=>"clean","name"=>static::AA_UI_SECTION_GRADUATORIE_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        else $template=new AA_GenericDatatableTemplate($id,"",4,array("type"=>"clean","name"=>static::AA_UI_SECTION_GRADUATORIE_NAME),array("css"=>"AA_Header_DataTable","filtered"=>true,"filter_id"=>$id));
        $template->EnableScroll(false,true);
        $template->EnableRowOver();
        $template->EnableHeader(true);
        $template->SetHeaderHeight(38);

        
        if($canModify) 
        {
            $template->EnableAddNew(true,"GetSicarAddNewNucleoDlg");
            $template->SetAddNewTaskParams(array("postParams"=>array("refresh"=>1)));
        }

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

    public function GetCondominioMisto() 
    { 
        $immobile=$this->GetImmobile();
        $attributi=$immobile->GetAttributi();
        if(!empty($attributi['condominio_misto'])) return 1;
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
    public function SetOccupazione($var = null) 
    { 
        if(is_array($var)) $var=json_encode($var);
        $this->SetProp("occupazione", $var); 
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

    public function GetInterventi() 
    { 
        //to do
        return array();
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
