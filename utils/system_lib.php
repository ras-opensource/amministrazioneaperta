<?php
include_once "config.php";
include_once "db.php";
include_once "lib_mail.php";

//Costanti
class AA_Const extends AA_Config
{
    //Tabella db oggetti
    const AA_DBTABLE_OBJECTS = "aa_objects";

    //Tabella Assessorati
    const AA_DBTABLE_ASSESSORATI = "assessorati";

    //tabella db moduli
    const AA_DBTABLE_MODULES = "aa_platform_modules";

    //Permessi
    const AA_PERMS_NONE = 0;
    const AA_PERMS_READ = 1;
    const AA_PERMS_WRITE = 2;
    const AA_PERMS_PUBLISH = 4;
    const AA_PERMS_DELETE = 8;
    const AA_PERMS_ALL = 15;

    //Livelli utente
    const AA_USER_LEVEL_ADMIN = 0;
    const AA_USER_LEVEL_OPERATOR = 1;
    const AA_USER_LEVEL_GUEST = 2;

    //Stato
    const AA_STATUS_NONE = -1;
    const AA_STATUS_BOZZA = 1;
    const AA_STATUS_PUBBLICATA = 2;
    const AA_STATUS_REVISIONATA = 4;
    const AA_STATUS_CESTINATA = 8;
    const AA_STATUS_ALL = 15;

    //User flags (deprecated, compat only) - moved to modules
    const AA_USER_FLAG_PROCESSI = "processi";
    const AA_USER_FLAG_INCARICHI_TITOLARI = "incarichi_titolari";
    const AA_USER_FLAG_INCARICHI = "incarichi";
    const AA_USER_FLAG_ART22 = "art22";
    const AA_USER_FLAG_ART22_ADMIN = "art22_admin";

    //Task constant
    const AA_TASK_STATUS_OK = 0;
    const AA_TASK_STATUS_FAIL = -1;

    //Oggetti (deprecated, compat only) - moved to modules
    const AA_OBJECT_ART26 = 26;
    const AA_OBJECT_ART37 = 37;
    const AA_OBJECT_ART22 = 22;
    const AA_OBJECT_ART22_BILANCI = 23;
    const AA_OBJECT_ART22_NOMINE = 24;
    const AA_OBJECT_RISICO = 25;

    //Moduli (deprecated, compat only) - moved to modules
    const AA_MODULE_HOME = "AA_MODULE_HOME";
    const AA_MODULE_STRUTTURE = "AA_MODULE_STRUTTURE";
    const AA_MODULE_UTENTI = "AA_MODULE_UTENTI";
    const AA_MODULE_ART26 = "AA_MODULE_ART26";
    const AA_MODULE_ART37 = "AA_MODULE_ART37";
    const AA_MODULE_SINES = "AA_MODULE_SINES";
    const AA_MODULE_INCARICHI = "AA_MODULE_INCARICHI";

    //Operazioni
    const AA_OPS_ADDNEW = 1;
    const AA_OPS_UPDATE = 2;
    const AA_OPS_PUBLISH = 3;
    const AA_OPS_REASSIGN = 4;
    const AA_OPS_TRASH = 5;
    const AA_OPS_RESUME = 6;
}

//Logger class
class AA_Log
{
    static private $nLogLevel = 100;
    static private $oLog = array();
    static private $oBackTrace = array();
    static public $lastErrorLog = "";

    protected $sTime = "";
    public function GetTime()
    {
        return $this->sTime;
    }

    protected $nLevel = 0;
    public function GetLevel()
    {
        return $this->nLevel;
    }

    protected $sMsg = "";
    public function GetMsg()
    {
        return $this->sMsg;
    }

    protected $aBacktrace;
    public function GetBackTrace()
    {
        return $this->aBacktrace;
    }

    protected $oUser = null;
    public function GetUser()
    {
        return $this->oUser;
    }

    //Numero massimo di voci
    const  AA_LOG_MAX_ENTRIES = 200;

    static public function Log($msg, $level = 0, $bWithbacktrace = false, $bLogToSession = true, $user = null)
    {
        if ($level >= AA_Log::$nLogLevel || $bWithbacktrace) {
            $time = date("Y-M-d H:i");

            self::$oLog[] = $time . "*" . $level . "*" . $msg . "\n";

            if ($level == 100 || $bWithbacktrace) {
                $array = array_keys(self::$oLog);
                $id = end($array);
                //if($bWithbacktrace) self::$oBackTrace[$id]=debug_backtrace();
            }

            if ($level == 100) {
                self::$lastErrorLog = $msg;
            }

            if ($bLogToSession) {
                $session_log = array();
                if (isset($_SESSION['log'])) $session_log = unserialize($_SESSION['log']);

                $session_log[] = new AA_Log($level, $msg, $time, debug_backtrace());

                //rimuove gli elementi più vecchi
                while (sizeof($session_log) > AA_Log::AA_LOG_MAX_ENTRIES) {
                    array_shift($session_log);
                }

                $_SESSION['log'] = serialize($session_log);
            }
        }
    }

    public function __construct($level = 0, $msg = "", $time = "", $backtrace = null, $user = null)
    {
        if ($time == "") $time = date("Y-m-d H:i");

        $this->sTime = $time;
        $this->nLevel = $level;
        $this->sMsg = $msg;
        if (is_array($backtrace)) $this->aBacktrace = $backtrace;
        if ($user instanceof AA_User) $this->oUser = $user;
    }

    static public function toHTML($bWithbacktrace = false)
    {
        $html = '<table style="width: 100%; border: 1px solid;">';
        foreach (self::$oLog as $id => $curRow) {
            $html .= "<tr>";
            foreach (explode("*", $curRow) as $key => $log_data) {
                $html .= '<td style="border: 1px solid;">' . $log_data . "</td>";
            }
            $html .= '<td style="border: 1px solid;">';
            if (self::$oBackTrace[$id] != "" && $bWithbacktrace) {
                $curBacktrace = self::$oBackTrace[$id];

                foreach ($curBacktrace as $key => $value) {
                    $html .= "<p>#" . $key . " - " . $value['file'] . " (line: " . $value['line'] . ")";
                    $html .= "<br/>" . $value['class'] . "->" . $value['function'] . "(" . htmlentities(print_r($value['args'], TRUE)) . ")</p>";
                }
            }
            $html .= "</td></tr>";
        }

        $html .= '</table>';

        return $html;
    }

    static public function SetLogLevel($nNewLogLevel = 100)
    {
        $_SESSION['loglevel'] = $nNewLogLevel;

        self::$nLogLevel = $nNewLogLevel;
    }

    static public function GetLog()
    {
        return implode("\n", self::$oLog);
    }

    //Old stuff compatibility
    static public function LogAction($id_utente = 0, $op, $sql)
    {
        //formato $op:
        //1=new,2=update,3=delete,4=pubblica,5=resume
        //1=scheda,2=referenti,3=responsabili,4=normativa,5=documenti,6=soggetti,7=collegamenti,8=allegati,9=utenti,10=struttura,11=news,12=pubblicazioni art.37, 13=lotti, 14=partecipanti, 15=aggiudicatari
        //20 = art15
        //30 = polizze
        //40 = accessi
        //41 = art12
        //formato: cod_op,sezione,id_rec

        $db = new AA_Database();

        if ($id_utente == 0) $id_utente = $_SESSION['id_user'];
        $update_sql = sprintf("INSERT INTO log VALUES('','%s',NOW(),'%s','%s')", $id_utente, $op, addslashes(htmlentities($sql)));
        $db->Query($update_sql);

        self::Log("AA_Log::LogAction($id_utente,$op,$sql)", 100, true, true);
    }
    #----------------------------
}

//Database --------------------
class AA_Database extends PDO_Database
{
    //Parametri di connessione al DB
    private $AA_DBHOST = AA_Config::AA_DBHOST;
    private $AA_DBNAME = AA_Config::AA_DBNAME;
    private $AA_DBUSER = AA_config::AA_DBUSER;
    private $AA_DBPWD = AA_Config::AA_DBPWD;

    public function __construct($bReset = false)
    {
        if (!$this->Initialize(AA_Config::AA_DBNAME, AA_Config::AA_DBHOST, AA_Config::AA_DBUSER, AA_Config::AA_DBPWD, $bReset)) {
            AA_Log::Log(__METHOD__ . " - Errore nella connessione al DB: " . $this->GetErrorMessage(), 100);
            return;
        }
    }
}
#--------------------------------

//STRUCT Class
class AA_Struct
{
    //Assessorato
    protected $nID_Assessorato = '0';
    protected $sAssessorato = 'Qualunque';

    //Direzione
    protected $nID_Direzione = '0';
    protected $sDirezione = 'Qualunque';

    //Servizio
    protected $nID_Servizio = '0';
    protected $sServizio = 'Qualunque';

    //Tipo di struttura
    protected $nTipo = -1;

    //Flag di validità
    protected $bIsValid = false;
    public function IsValid()
    {
        return $this->bIsValid;
    }

    //Albero della struttura
    protected $aTree = array();

    static public function GetStruct($id_assessorato = '', $id_direzione = '', $id_servizio = '', $type = '')
    {
        AA_Log::Log(get_class() . "GetStruct($id_assessorato,$id_direzione,$id_servizio,$type)");

        $db = new Database();
        $struct = new AA_Struct();

        if ($type != '') $struct->nTipo = $type;

        $now = Date("Y-m-d");

        //Servizio impostato
        if ($id_servizio != '' && $id_servizio > 0) {
            $db->Query("SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from servizi inner join direzioni on servizi.id_direzione = direzioni.id inner join assessorati on direzioni.id_assessorato=assessorati.id where servizi.id='$id_servizio'");
            $rs = $db->GetRecordSet();
            if ($rs->GetCount() > 0) {
                $struct->bIsValid = true;

                //Assessorato
                $struct->nID_Assessorato = $rs->Get("id_assessorato");
                $struct->sAssessorato = $rs->Get("assessorato");
                $struct->nTipo = $rs->Get("tipo");

                //Direzione
                $struct->nID_Direzione = $rs->Get("id_direzione");
                $struct->sDirezione = $rs->Get("direzione");

                //Servizio
                $struct->nID_Servizio = $rs->Get("id_servizio");
                $struct->sServizio = $rs->Get("servizio");

                $soppresso_servizio = 0;
                if ($rs->Get("data_soppressione_servizio") < $now) $sopresso_servizio = 1;
                $soppresso = 0;
                if ($rs->Get("data_soppressione_direzione") < $now) $sopresso = 1;

                $struct->aTree['assessorati'][$rs->Get("id_assessorato")] = array('descrizione' => $rs->Get("assessorato"), 'tipo' => $rs->Get("tipo"), 'direzioni' => array($rs->Get("id_direzione") => array('descrizione' => $rs->Get("direzione"), 'data_soppressione' => $rs->Get("data_soppressione_direzione"), "soppresso" => $soppresso, 'servizi' => array($rs->Get("id_servizio") => array("descrizione" => $rs->Get("servizio"), "data_soppressione" => $rs->Get("data_soppressione_servizio"), "soppresso" => $soppresso_servizio)))));

                return $struct;
            }
        }

        //Direzione impostata
        if ($id_direzione != '' && $id_direzione > 0) {
            $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione from direzioni inner join assessorati on direzioni.id_assessorato=assessorati.id where direzioni.id='$id_direzione'";
            $db->Query($query);
            $rs = $db->GetRecordSet();
            if ($rs->GetCount() > 0) {
                $struct->bIsValid = true;

                //Assessorato
                $struct->nID_Assessorato = $rs->Get("id_assessorato");
                $struct->sAssessorato = $rs->Get("assessorato");
                $struct->nTipo = $rs->Get("tipo");

                //Direzione
                $struct->nID_Direzione = $rs->Get("id_direzione");
                $struct->sDirezione = $rs->Get("direzione");

                $soppresso = 0;
                if ($rs->Get("data_soppressione_direzione") < $now) $sopresso = 1;

                $struct->aTree['assessorati'][$rs->Get("id_assessorato")] = array('descrizione' => $rs->Get("assessorato"), 'tipo' => $rs->Get("tipo"), 'direzioni' => array());
                $struct->aTree['assessorati'][$rs->Get("id_assessorato")]['direzioni'][$rs->Get("id_direzione")] = array('descrizione' => $rs->Get("direzione"), "data_soppressione" => $rs->Get('data_soppressione_direzione'), "soppresso" => $soppresso, 'servizi' => array());

                return $struct;
            }
        }

        //Assessorato impostato
        if ($id_assessorato != '' && $id_assessorato > 0) {
            $db->Query("SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo from assessorati where assessorati.id='$id_assessorato'");
            $rs = $db->GetRecordSet();
            if ($rs->GetCount() > 0) {
                $struct->bIsValid = true;

                //Assessorato
                $struct->nID_Assessorato = $rs->Get("id_assessorato");
                $struct->sAssessorato = $rs->Get("assessorato");
                $struct->nTipo = $rs->Get("tipo");

                $struct->aTree['assessorati'][$rs->Get("id_assessorato")] = array('descrizione' => $rs->Get("assessorato"), 'tipo' => $rs->Get("tipo"), 'direzioni' => array());

                return $struct;
            }
        }

        return $struct;
    }

    //Restituisce l'albero della struttura
    public function GetStructTree()
    {
        //if(!$this->bIsValid) return $this->aTree

        $now = Date("Y-m-d");

        $db = new Database();

        //Servizio impostato
        if ($this->nID_Servizio != '' && $this->nID_Servizio > 0) {
            return $this->aTree;
        }

        //Direzione impostata
        if ($this->nID_Direzione != '' && $this->nID_Direzione > 0) {
            $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from assessorati left join direzioni on direzioni.id_assessorato = assessorati.id left join servizi on servizi.id_direzione=direzioni.id where direzioni.id='$this->nID_Direzione' order by assessorati.descrizione, direzioni.descrizione, servizi.descrizione";
            if (!$db->Query($query)) {
                AA_Log::Log(get_class() . "->GetStructTree() - Errore nella query: " . $query, 100, true, true);
                return $this->aTree;
            }

            $rs = $db->GetRecordSet();
            if ($rs->GetCount() > 0) {
                if ($rs->Get("id_direzione") != "") {
                    do {
                        //AA_Log::Log(get_class()."->GetStructTree() ".print_r($this->aTree,TRUE),100,true,true);
                        $soppresso = 0;
                        if ($rs->Get("data_soppressione_servizio") < $now) $sopresso = 1;
                        if ($rs->Get("id_servizio") != "") $this->aTree['assessorati'][$rs->Get("id_assessorato")]['direzioni'][$rs->Get("id_direzione")]['servizi'][$rs->Get("id_servizio")] = array("descrizione" => $rs->Get("servizio"), "data_soppressione" => $rs->Get("data_soppressione_servizio"), "soppresso" => $soppresso);
                    } while ($rs->MoveNext());
                }
            }

            return $this->aTree;
        }

        //Assessorato impostato
        if ($this->nID_Assessorato != '' && $this->nID_Assessorato > 0) {
            $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from assessorati left join direzioni on direzioni.id_assessorato = assessorati.id left join servizi on servizi.id_direzione=direzioni.id where assessorati.id='$this->nID_Assessorato' order by assessorati.descrizione, direzioni.descrizione,servizi.descrizione";
            if (!$db->Query($query)) {
                AA_Log::Log(get_class() . "->GetStructTree() - Errore nella query: " . $query, 100, true, true);
                return $this->aTree;
            }

            //AA_Log::Log(get_class()."->GetStructTree() - query: ".$query,100,true,true);

            $curDirezione = 0;
            $rs = $db->GetRecordSet();
            if ($rs->GetCount() > 0) {
                do {
                    if ($rs->Get("id_direzione") != $curDirezione && $rs->Get("id_direzione") != "") {
                        $soppresso = 0;
                        if ($rs->Get("data_soppressione_direzione") < $now) $sopresso = 1;
                        $this->aTree['assessorati'][$rs->Get("id_assessorato")]['direzioni'][$rs->Get("id_direzione")] = array('descrizione' => $rs->Get("direzione"), "data_soppressione" => $rs->Get("data_soppressione_direzione"), "soppresso" => $soppresso, 'servizi' => array());
                        $curDirezione = $rs->Get("id_direzione");
                    }

                    $soppresso = 0;
                    if ($rs->Get("data_soppressione_servizio") < $now) $sopresso = 1;
                    if ($rs->Get("id_servizio") != "") $this->aTree['assessorati'][$rs->Get("id_assessorato")]['direzioni'][$curDirezione]['servizi'][$rs->Get("id_servizio")] = array("descrizione" => $rs->Get("servizio"), "data_soppressione" => $rs->Get("data_soppressione_servizio"), "soppresso" => $soppresso);
                } while ($rs->MoveNext());
            }

            return $this->aTree;
        }

        //Tutte le strutture del reame 'type'
        if ($this->nID_Assessorato == '' || $this->nID_Assessorato == 0) {
            if ($this->nTipo < 0) $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from assessorati left join direzioni on direzioni.id_assessorato = assessorati.id left join servizi on servizi.id_direzione=direzioni.id order by assessorati.descrizione,direzioni.descrizione,servizi.descrizione";
            else $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from assessorati left join direzioni on direzioni.id_assessorato = assessorati.id left join servizi on servizi.id_direzione=direzioni.id where assessorati.tipo='" . $this->nTipo . "' order by assessorati.descrizione,direzioni.descrizione,servizi.descrizione";

            $curAssessorato = 0;
            $curDirezione = 0;
            if (!$db->Query($query)) {
                AA_Log::Log(get_class() . "->GetStructTree() - Errore nella query: " . $query, 100, true, true);
                return $this->aTree;
            }

            //AA_Log::Log(get_class()."->GetStructTree(nTipo: ".$this->nTipo.") - query: ".$query,100,true,true);

            $rs = $db->GetRecordSet();
            if ($rs->GetCount() > 0) {
                do {
                    if ($curAssessorato != $rs->Get("id_assessorato")) {
                        $this->aTree['assessorati'][$rs->Get("id_assessorato")] = array('descrizione' => $rs->Get("assessorato"), 'tipo' => $rs->Get("tipo"), 'direzioni' => array());
                        $curAssessorato = $rs->Get("id_assessorato");
                    }

                    if ($rs->Get("id_direzione") != $curDirezione && $rs->Get("id_direzione") != "") {
                        $soppresso = 0;
                        if ($rs->Get("data_soppressione_direzione") < $now) $soppresso = 1;
                        $this->aTree['assessorati'][$curAssessorato]['direzioni'][$rs->Get("id_direzione")] = array('descrizione' => $rs->Get("direzione"), "data_soppressione" => $rs->Get("data_soppressione_direzione"), "soppresso" => $soppresso, 'servizi' => array());
                        $curDirezione = $rs->Get("id_direzione");
                    }

                    $soppresso = 0;
                    if ($rs->Get("data_soppressione_servizio") < $now) $soppresso = 1;
                    if ($rs->Get("id_servizio") != "") $this->aTree['assessorati'][$curAssessorato]['direzioni'][$curDirezione]['servizi'][$rs->Get("id_servizio")] = array("descrizione" => $rs->Get("servizio"), "data_soppressione" => $rs->Get("data_soppressione_servizio"), "soppresso" => $soppresso);
                } while ($rs->MoveNext());
            }
        }

        return $this->aTree;
    }

    //Restituisce l'id o la descrizione dell'assessorato
    public function GetAssessorato($getID = false)
    {
        if ($getID) return $this->nID_Assessorato;
        else return $this->sAssessorato;
    }

    //Restituisce il tipo di struttura
    public function GetTipo()
    {
        return $this->nTipo;
    }

    //Restituisce l'id o la descrizione della direzione
    public function GetDirezione($getID = false)
    {
        if ($getID) return $this->nID_Direzione;
        else return $this->sDirezione;
    }

    //Restituisce l'id o la descrizone del servizio
    public function GetServizio($getID = false)
    {
        if ($getID) return $this->nID_Servizio;
        else return $this->sServizio;
    }

    //Stampa la struttura in formato xml
    public function toXML()
    {
        AA_Log::Log(get_class() . "->toXML()");

        $this->aTree = $this->GetStructTree();

        $result = "<struttura tipo='" . $this->GetTipo() . "'>";
        foreach ($this->aTree['assessorati'] as $id_ass => $ass) {
            $result .= '<assessorato id="' . $id_ass . '" tipo="' . $ass['tipo'] . '"><descrizione>' . $ass['descrizione'] . "</descrizione>";
            foreach ($ass['direzioni'] as $id_dir => $dir) {
                $result .= '<direzione id="' . $id_dir . '"><descrizione>' . $dir['descrizione'] . "</descrizione>";
                foreach ($dir['servizi'] as $id_ser => $ser) {
                    $result .= '<servizio id="' . $id_ser . '">' . $ser['descrizione'] . "</servizio>";
                }
                $result .= '</direzione>';
            }
            $result .= '</assessorato>';
        }
        $result .= "</struttura>";

        return $result;
    }

    //Restituisce la struttura in formato JSON
    public function toJSON($bEncode = false)
    {
        AA_Log::Log(get_class() . "->toJSON()");

        if ($bEncode) return base64_encode(json_encode($this->toArray()));
        else return json_encode($this->toArray());
    }

    //Restituisce la struttura in formato JSON
    public function toArray($params = array())
    {
        AA_Log::Log(get_class() . "->toArray()");

        $this->aTree = $this->GetStructTree();

        $root = "root";
        $assessorato_num = 1;
        $direzione_num = 1;
        $servizio_num = 1;
        $result = array(array("id" => $root, "value" => "Strutture", "open" => true, "data" => array()));
        foreach ($this->aTree['assessorati'] as $id_ass => $ass) {
            if (sizeof($ass['direzioni']) > 0 && $params['hideDirs'] != 1) $curAssessorato = array("id" => $assessorato_num, "id_assessorato" => $id_ass, "id_direzione" => 0, "id_servizio" => 0, "tipo" => $ass['tipo'], "value" => $ass['descrizione'], "soppresso" => 0, "data" => array());
            else $curAssessorato = array("id" => $assessorato_num, "id_assessorato" => $id_ass, "id_direzione" => 0, "id_servizio" => 0, "tipo" => $ass['tipo'], "value" => $ass['descrizione'], "soppresso" => 0);

            if ($params['hideDirs'] != 1) {
                foreach ($ass['direzioni'] as $id_dir => $dir) {
                    //AA_Log::Log(get_class()."->toArray() - direzione: ".$dir['descrizione'],100);

                    if (sizeof($dir['servizi']) && $params['hideServices'] != 1) $curDirezione = array("id" => $assessorato_num . "." . $direzione_num, "id_direzione" => $id_dir, "id_assessorato" => $id_ass, "id_servizio" => 0, "value" => $dir['descrizione'], "data_soppressione" => $dir['data_soppressione'], "soppresso" => $dir['soppresso'], "data" => array());
                    else $curDirezione = array("id" => $assessorato_num . "." . $direzione_num, "id_direzione" => $id_dir, "id_assessorato" => $id_ass, "id_servizio" => 0, "value" => $dir['descrizione'], "data_soppressione" => $dir['data_soppressione'], "soppresso" => $dir['soppresso']);
                    if ($params['hideServices'] != 1) {
                        foreach ($dir['servizi'] as $id_ser => $ser) {
                            $curDirezione['data'][] = array("id" => $assessorato_num . "." . $direzione_num . "." . $servizio_num, "id_servizio" => $id_ser, "id_assessorato" => $id_ass, "id_direzione" => $id_dir, "data_soppressione" => $ser['data_soppressione'], "soppresso" => $ser['soppresso'], "value" => $ser['descrizione']);
                            $servizio_num++;
                        }
                    }
                    $direzione_num++;
                    $curAssessorato['data'][] = $curDirezione;
                }
            }
            $assessorato_num++;
            $result[0]['data'][] = $curAssessorato;
        }

        //AA_Log::Log(get_class()."->toArray() - ".print_r($result,true),100);
        return $result;
    }

    //Rappresentazione stringa
    public function __toString()
    {
        AA_Log::Log(get_class() . "__toString()");

        return $this->toXML();
    }
}

//STRUCT_TREE Classe
//Albero di oggetti AA_Struct
class AA_TStruct
{
    protected $root = array();
}

//USER classe
class AA_User
{
    //Nome
    protected $sNome = "Nessuno";

    //Cognome
    protected $sCognome = "Nessuno";

    //email
    protected $sEmail = "";

    //Nome utente
    protected $sUser = "Nessuno";

    //ID utente
    protected $nID = "0";

    //Struttura
    protected $oStruct = null;

    //Flags
    protected $sFlags = "";

    //Livello;
    protected $nLivello = 3;

    //Flag disabilitato;
    protected $nDisabled = 1;

    //Flag di validità
    protected $bIsValid = false;

    //Flag utente corrente
    private $bCurrentUser = false;

    public function __construct()
    {
        AA_Log::Log(get_class() . "__construct()");

        $this->oStruct = new AA_Struct();
    }

    //Verifica se l'utente è valido
    public function IsValid()
    {
        return $this->bIsValid;
    }

    //Verifica se l'utente è disabilitato
    public function IsDisabled()
    {
        return $this->nDisabled;
    }

    //Verifica se è l'utente guest
    public function IsGuest()
    {
        return !$this->bIsValid;
    }

    //Verifica se è l'utente amministratore
    public function IsAdmin()
    {
        return $this->IsSuperUser();

        /*if($this->IsSuperUser()) return true;
        else return false;*/
    }

    //Verifica se è l'utente super user
    public function IsSuperUser()
    {
        //$this->nID ==1909
        if ($this->nID == 1) return true;
        if ($this->HasFlag("SU")) return true;

        return false;
    }

    //Restituisce la struttura
    public function GetStruct()
    {
        return $this->oStruct;
    }

    //Restituisce il livello
    public function GetLevel()
    {
        return $this->nLivello;
    }

    //Restituisce l'identificativo
    public function GetID()
    {
        return $this->nID;
    }

    //Restituisce il flag utente corrente
    public function isCurrentUser()
    {
        return $this->bCurrentUser;
    }

    //Popola i dati dell'utente
    static public function LoadUser($id_user)
    {
        AA_Log::Log(get_class() . "->LoadUser($id_user)");

        $user = new AA_User();
        $user->bCurrentUser = false;

        $db = new AA_Database();
        $db->Query("SELECT utenti.* from utenti where id = '" . addslashes($id_user) . "'");
        if ($db->GetAffectedRows() > 0) {
            $row = $db->GetResultSet();

            $user->nID = $row[0]['id'];
            $user->sNome = $row[0]['nome'];
            $user->sCognome = $row[0]['cognome'];
            $user->sUser = $row[0]['user'];
            $user->sEmail = $row[0]['email'];
            $user->nLivello = $row[0]['livello'];
            $user->nDisabled = $row[0]['disable'];
            $user->sFlags = $row[0]['flags'];
            $user->bIsValid = true;

            //Popola i dati della struttura
            $user->oStruct = AA_Struct::GetStruct($row[0]['id_assessorato'], $row[0]['id_direzione'], $row[0]['id_servizio']);
        }

        return $user;
    }

    //Popola i dati dell'utente a partire dal nome utente
    static public function LoadUserFromUserName($userName)
    {
        AA_Log::Log(get_class() . "LoadUserFromUserName($userName)");

        $user = new AA_User();
        $user->bCurrentUser = false;

        $db = new Database();
        $db->Query("SELECT utenti.* from utenti where user = '" . $userName . "' and eliminato='0'");
        $rs = $db->GetRecordSet();
        if ($rs->GetCount() > 0) {
            $user->nID = $rs->Get('id');
            $user->sNome = $rs->Get('nome');
            $user->sCognome = $rs->Get('cognome');
            $user->sUser = $rs->Get('user');
            $user->sEmail = $rs->Get('email');
            $user->nLivello = $rs->Get('livello');
            $user->sFlags = $rs->Get('flags');
            $user->nDisabled = $rs->Get('disable');
            $user->bIsValid = true;

            //Popola i dati della struttura
            $user->oStruct = AA_Struct::GetStruct($rs->Get('id_assessorato'), $rs->Get('id_direzione'), $rs->Get('id_servizio'));
        }

        return $user;
    }

    //Popola i dati dell'utente a partire dal nome utente
    //Restituisce un array di oggetti AA_User
    static public function LoadUsersFromEmail($email)
    {
        AA_Log::Log(get_class() . "->LoadUserFromEmail($email)");

        $users = array();

        $db = new Database();
        $db->Query("SELECT utenti.* from utenti where email = '" . addslashes($email) . "' and eliminato='0' and disable='0'");
        $rs = $db->GetRecordSet();
        if ($rs->GetCount() > 0) {
            do {
                $user = new AA_User();

                $user->nID = $rs->Get('id');
                $user->sNome = $rs->Get('nome');
                $user->sCognome = $rs->Get('cognome');
                $user->sUser = $rs->Get('user');
                $user->sEmail = $rs->Get('email');
                $user->nLivello = $rs->Get('livello');
                $user->sFlags = $rs->Get('flags');
                $user->nDisabled = $rs->Get('disable');
                $user->bCurrentUser = false;
                $user->bIsValid = true;

                //Popola i dati della struttura
                $user->oStruct = AA_Struct::GetStruct($rs->Get('id_assessorato'), $rs->Get('id_direzione'), $rs->Get('id_servizio'));

                $users[] = $user;
            } while ($rs->MoveNext());
        }

        return $users;
    }

    //Autenticazione
    static public function UserAuth($sToken = "", $sUserName = "", $sUserPwd = "")
    {
        //AA_Log::Log(get_class()."->UserAuth($sToken,$sUserName, $sUserPwd)");

        $db = new AA_Database();

        if ($sUserName != null && $sUserPwd != null) {
            AA_Log::Log(get_class() . "->UserAuth($sToken,$sUserName, $sUserPwd) - autenticazione in base al nome utente.");

            if (filter_var($sUserName, FILTER_VALIDATE_EMAIL)) {
                //Login tramite email
                AA_Log::Log(get_class() . "->UserAuth($sUserName) - autenticazione in base alla mail.");
                $query_utenti = sprintf("SELECT utenti.*,assessorati.tipo, assessorati.descrizione as assessorato, direzioni.descrizione as direzione, servizi.descrizione as servizio, settori.descrizione as settore FROM utenti left join assessorati on utenti.id_assessorato=assessorati.id left join direzioni on utenti.id_direzione=direzioni.id left join servizi on utenti.id_servizio=servizi.id left join settori on utenti.id_settore=settori.id WHERE utenti.email = '%s' AND passwd= '%s' ", addslashes($sUserName), addslashes($sUserPwd));
            } else {
                //Login ordinario tramite username
                $query_utenti = sprintf("SELECT utenti.*,assessorati.tipo, assessorati.descrizione as assessorato, direzioni.descrizione as direzione, servizi.descrizione as servizio, settori.descrizione as settore FROM utenti left join assessorati on utenti.id_assessorato=assessorati.id left join direzioni on utenti.id_direzione=direzioni.id left join servizi on utenti.id_servizio=servizi.id left join settori on utenti.id_settore=settori.id WHERE user = '%s' AND passwd= '%s' ", addslashes($sUserName), addslashes($sUserPwd));
            }

            if ($db->Query($query_utenti)) {
                $result = $db->GetResult();
                $rs = $result->fetch(PDO::FETCH_ASSOC);
            } else {
                AA_Log::Log(__METHOD__ . " - errore nell'accesso al db: " . $db->GetErrorMessage(), 100);
                return AA_User::Guest();
            }

            if ($db->GetAffectedRows() > 0) {
                if ($rs['disable'] == '1') {
                    AA_Log::Log(get_class() . "->UserAuth($sUserName) - L'utente è disattivato (id: " . $rs["id"] . ").", 100);
                }

                if ($rs['eliminato'] == '1') {
                    AA_Log::Log(get_class() . "->UserAuth($sUserName) - L'utente è stato disattivato permanentemente (id: " . $rs["id"] . ").", 100);
                }

                if ($rs['disable'] == '0' && $rs['eliminato'] == '0') {
                    //Old stuff compatibility
                    $_SESSION['user'] = $rs['user'];
                    $_SESSION['nome'] = $rs['nome'];
                    $_SESSION['cognome'] = $rs['cognome'];
                    $_SESSION['email'] = $rs['email'];
                    $_SESSION['user_home'] = $rs['home'];
                    $_SESSION['id_user'] = $rs['id'];
                    $_SESSION['id_utente'] = $rs['id'];
                    $_SESSION['id_assessorato'] = $rs['id_assessorato'];
                    $_SESSION['tipo_struct'] = $rs['tipo'];
                    $_SESSION['id_direzione'] = $rs['id_direzione'];
                    $_SESSION['id_servizio'] = $rs['id_servizio'];
                    $_SESSION['id_settore'] = $rs['id_settore'];
                    $_SESSION['livello'] = $rs['livello'];
                    $_SESSION['level'] = $rs['livello'];
                    $_SESSION['assessorato'] = $rs['assessorato'];
                    $_SESSION['direzione'] = $rs['direzione'];
                    $_SESSION['servizio'] = $rs['servizio'];
                    $_SESSION['settore'] = $rs['settore'];
                    $_SESSION['user_flags'] = $rs['flags'];
                    $_SESSION['flags'] = $rs['flags'];

                    AA_Log::LogAction($rs['id'], 0, "Log In"); //old stuff

                    //New stuff
                    AA_Log::Log(get_class() . "->UserAuth($sToken,$sUserName, $sUserPwd) - Autenticazione avvenuta con successo (credenziali corrette).", 50);
                    $_SESSION['token'] = AA_User::GenerateToken($rs['id']);

                    $user = AA_User::LoadUser($rs['id']);
                    $user->bCurrentUser = true;
                    return $user;
                }

                return AA_User::Guest();
            }

            AA_Log::Log(get_class() . "->UserAuth($sUserName) - Autenticazione fallita (credenziali errate).", 100);
            return AA_User::Guest();
        }

        if ($sToken == null || $sToken == "") $sToken = $_SESSION['token'];

        if ($sToken != null) {
            //AA_Log::Log(get_class()."->UserAuth($sToken) - autenticazione in base al token.");

            $token_timeout_m = 30;
            $query_token = sprintf("SELECT * FROM tokens where TIMESTAMPDIFF(MINUTE,data_rilascio, NOW()) < '%s' and ip_src = '%s' and token ='%s'", $token_timeout_m, $_SERVER['REMOTE_ADDR'], $sToken);

            if ($db->Query($query_token)) {
                $result = $db->GetResult();
                $rs = $result->fetch(PDO::FETCH_ASSOC);
            } else {
                AA_Log::Log(__METHOD__ . " - errore nell'accesso al db: " . $db->GetErrorMessage(), 100);
                return AA_User::Guest();
            }

            if ($db->GetAffectedRows() > 0) {

                if (strcmp($rs['token'], $sToken) == 0) {
                    //AA_Log::Log(get_class()."->UserAuth($sToken) - Authenticate token ($sToken) - success", 50);

                    $user = AA_User::LoadUser($rs['id_utente']);
                    if ($user->IsDisabled()) {
                        AA_Log::Log(get_class() . "->UserAuth($sToken) - L'utente è disattivato.", 100);
                        return AA_User::Guest();
                    }

                    //Rinfresco della durata del token
                    AA_User::RefreshToken($sToken);
                    $_SESSION['token'] = $sToken;

                    //Old stuff
                    //AA_Log::LogAction($rs->Get('id_utente'),0,"Authenticate token ($sToken) - success");
                    //*

                    $user->bCurrentUser = true;
                    return $user;
                }
            }

            //Old stuff
            if (isset($log)) AA_Log::LogAction($rs->Get('id'), 0, "Authenticate token ($sToken) - failed");
            //*

            AA_Log::Log(get_class() . "->UserAuth($sToken) - Authenticate token ($sToken) - failed", 100);
            return AA_User::Guest();
        }

        AA_Log::Log(get_class() . "->UserAuth($sToken,$sUserName) - Autenticazione fallita.", 100);
        return AA_User::Guest();
    }

    //Cambia il profilo dell'utente corrente
    static public function ChangeProfile($newProfileID = "")
    {
        AA_Log::Log(get_class() . "->ChangeProfile($newProfileID)");

        $user = self::GetCurrentUser();
        if ($user->IsGuest()) {
            AA_Log::Log(get_class() . "->ChangeProfile($newProfileID) - utente non valido o sessione scaduta.", 100, true, true);
            return false;
        }

        foreach (self::LoadUsersFromEmail($user->GetEmail()) as $curProfile) {
            if ($curProfile->GetID() == $newProfileID) {
                $sToken = $_SESSION['token'];

                //Aggiorna il token con il nuovo id utente
                $db = new Database();
                $query = "UPDATE tokens set id_utente='" . $newProfileID . "' where token='" . $sToken . "' LIMIT 1";
                if (!$db->Query($query)) {
                    AA_Log::Log(get_class() . "->ChangeProfile($newProfileID) - errore nella query:" . $query, 100, true, true);
                    return false;
                }

                $newUser = self::UserAuth($sToken);
                if ($newUser->IsGuest()) {
                    AA_Log::Log(get_class() . "->ChangeProfile($newProfileID) - cambio di profilo fallito, sessione non valida o scaduta.", 100, true, true);
                    return false;
                }

                //Old stuff compatibility
                $_SESSION['user'] = $newUser->GetUsername();
                $_SESSION['nome'] = $newUser->GetNome();
                $_SESSION['cognome'] = $newUser->GetCognome();
                $_SESSION['email'] = $newUser->GetEmail();
                $_SESSION['user_home'] = "";
                $_SESSION['id_user'] = $newUser->GetID();
                $_SESSION['id_utente'] = $newUser->GetID();

                $struct = $newUser->GetStruct();
                $_SESSION['id_assessorato'] = $struct->GetAssessorato(true);
                $_SESSION['tipo_struct'] = $struct->GetTipo();
                $_SESSION['id_direzione'] = $struct->GetDirezione(true);
                $_SESSION['id_servizio'] = $struct->GetServizio(true);
                $_SESSION['id_settore'] = 0;
                $_SESSION['livello'] = $newUser->GetLevel();
                $_SESSION['level'] = $newUser->GetLevel();
                $_SESSION['assessorato'] = $struct->GetAssessorato();
                $_SESSION['direzione'] = $struct->GetDirezione();
                $_SESSION['servizio'] = $struct->GetServizio();
                $_SESSION['settore'] = "";
                $_SESSION['user_flags'] = $newUser->GetFlags();
                $_SESSION['flags'] = $newUser->GetFlags();

                return true;
            }
        }

        AA_Log::Log(get_class() . "->ChangeProfile($newProfileID) - cambio di profilo fallito, nessun profilo corrispondente trovato per l'utente corrente.", 100, true, true);
        return false;
    }


    //Autenticazione via mail OTP - passo 1
    static public function MailOTPAuthSend($email = null, $register = true)
    {
        AA_Log::Log(get_class() . "->MailOTPAuthSend($email) - Authenticate mail OTP");

        $email = str_replace("'", "", trim($email));

        if ($email == "") {
            AA_Log::Log(get_class() . "->MailOTPAuthSend($email) - mail non impostata.", 100, true, true);
            return false;
        }

        if ($register) {
            //Verifica se la mail è già registrata sul database (SmartCV).
            $registered = self::MailOTPAuthRegisterEmail($email);
            if (!$registered) {
                AA_Log::Log(get_class() . "->MailOTPAuthSend($email) - registrazione email fallita.", 100, true, true);
                return false;
            }
        } else {
            //Verifica che alla email sia associato un utente esistente e valido
            //to do
        }

        //genera ed invia il codice di controllo alla email indicata
        $_SESSION['MailOTP-user'] = "";

        $_SESSION['MailOTP-email'] = $email;

        $code = substr(md5(uniqid(mt_rand(), true)), 0, 5);
        $_SESSION['MailOTP-code'] = $code;

        //------ Procedura smartCV
        if ($register) {
            //Registra il codice nel db
            $db = new AA_Database();
            $query = "UPDATE email_login set codice='" . $code . "' WHERE email='" . addslashes($email) . "' LIMIT 1";
            if (!$db->Query($query)) {
                AA_Log::Log(get_class() . "->MailOTPAuthSend($email) - errore: " . $db->GetErrorMessage() . " - nella query: " . $query, 100, true, true);
                return false;
            }
        }
        //---------------------------

        $subject = "Amministrazione Aperta - Verifica email";
        $body = "Stai ricevendo questa email perchè è stai cercando di accedere sulla piattaforma \"Amministrazione Aperta\" della Regione Autonoma della Sardegna";
        $body .= "di seguito è riportato il codice di verifica da inserire sulla pagina di autenticazione:<br/>";
        $body .= "<p>codice di verifica: <span style='font-weight: bold; font-size: 150%;'>" . $code . "</span></p>";
        $body .= "<p>Qualora non sia stato tu ad avviare la procedura di verifica, puoi ignorare questo messaggio o segnalare l'anomalia alla casella: amministrazioneaperta@regione.sardegna.it</p>";

        $result = SendMail(array(0 => $email), "", $subject, $body);

        if (!$result) {
            AA_Log::Log(get_class() . "->MailOTPAuthSend($email) - invio mail fallito - errore: " . $result, 100, true, true);
            return false;
        }

        return true;
    }
    //-------------------------------

    //Autenticazione via mail OTP - passo 2
    static public function MailOTPAuthVerify($codice = null)
    {
        AA_Log::Log(get_class() . "->MailOTPAuthVerify($codice) - Authenticate mail OTP - passo 2");

        if ($codice == "") {
            AA_Log::Log(get_class() . "->MailOTPAuthVerify($codice) - codice non valido.", 100, true, true);
            return false;
        }

        $email = $_SESSION['MailOTP-email'];
        if ($email == '') {
            AA_Log::Log(get_class() . "->MailOTPAuthVerify($codice) - email non valida.", 100, true, true);
            return false;
        }

        //Verifica il paio email-codice
        $db = new Database();
        $query = "SELECT * from email_login WHERE email='" . $email . "' AND codice='" . str_replace("'", "", trim($codice)) . "' LIMIT 1";

        if (!$db->Query($query)) {
            AA_Log::Log(get_class() . "->MailOTPAuthVerify($email) - errore: " . $db->lastError . " - nella query: " . $query, 100, true, true);
            return false;
        }

        $rs = $db->GetRecordSet();
        if ($rs->GetCount() > 0) {
            $_SESSION['MailOTP-user'] = $rs->Get("id");
            $_SESSION['MailOTP-nome'] = $rs->Get("nome");
            $_SESSION['MailOTP-cognome'] = $rs->Get("cognome");
            $aggiornamento = $rs->Get("aggiornamento");
            if ($aggiornamento != "") {
                $aggiornamento = explode("-", $aggiornamento);
                $aggiornamento = $aggiornamento[2] . "/" . $aggiornamento[1] . "/" . $aggiornamento[0];
            }
            $_SESSION['MailOTP-aggiornamento'] = $aggiornamento;

            return true;
        }

        AA_Log::Log(get_class() . "->MailOTPAuthVerify($email) - codice errato.", 100, true, true);
        return false;
    }
    //-------------------------------

    //Verifica se la mail è già registrata sul sistema
    static public function MailOTPAuthIsMailRegistered($email = "")
    {
        AA_Log::Log(get_class() . "->MailOTPAuthIsMailRegistered($email)");

        if ($email == "") {
            AA_Log::Log(get_class() . "->MailOTPAuthIsMailRegistered($email) - mail non impostata.", 100, true, true);
            return false;
        }

        $db = new Database();
        $query = "SELECT email from email_login where email='" . str_replace("'", "", trim($email)) . "' LIMIT 1";
        if (!$db->Query($query)) {
            AA_Log::Log(get_class() . "->MailOTPAuthIsMailRegistered($email) - errore: " . $db->lastError . " - nella query: " . $query, 100, true, true);
            return false;
        }

        $rs = $db->GetRecordSet();
        if ($rs->GetCount() > 0) return true;
        return false;
    }
    //---------------------------------------------

    //Registra una nuova mail sul sistema
    static public function MailOTPAuthRegisterEmail($email = "")
    {
        AA_Log::Log(get_class() . "->MailOTPAuthRegisterEmail($email)");

        if (self::MailOTPAuthIsMailRegistered($email)) return true;

        if ($email == "") {
            AA_Log::Log(get_class() . "->MailOTPAuthRegisterEmail($email) - mail non impostata.", 100, true, true);
            return false;
        }

        $db = new Database();
        $query = "INSERT INTO email_login set email='" . str_replace("'", "", trim($email)) . "', aggiornamento=NOW()";
        if (!$db->Query($query)) {
            AA_Log::Log(get_class() . "->MailOTPAuthRegisterEmail($email) - errore: " . $db->lastError . " - nella query: " . $query, 100, true, true);
            return false;
        }

        return true;
    }
    //-----------------------------------------------------

    //Rimuovi le informazioni di autenticazione
    public function LogOut()
    {
        AA_Log::Log(get_class() . "->LogOut() - " . $this->sUser . "(" . $this->nID . ")");

        if ($this->bIsValid && $this->bCurrentUser) {
            $db = new Database();
            $query = "DELETE from tokens WHERE id_utente='" . $this->nID . "'";
            $db->Query($query);

            $_SESSION['token'] = null;
            unset($_SESSION);
            session_destroy();
        }
    }

    //Genera il token di autenticazione
    static private function GenerateToken($id_user)
    {
        AA_Log::Log(get_class() . "->GenerateToken($id_user)");

        $token = hash("sha256", $id_user . date("Y-m-d H:i:s") . uniqid() . $_SERVER['REMOTE_ADDR']);

        AA_Log::Log(get_class() . "->GenerateToken($id_user) - new token: " . $token);

        $db = new AA_Database();

        $query = "DELETE from tokens where id_utente='" . $id_user . "' and ip_src='" . $_SERVER['REMOTE_ADDR'] . "'";
        $db->Query($query);

        $query = "INSERT INTO tokens set token='" . $token . "', id_utente='" . $id_user . "',ip_src='" . $_SERVER['REMOTE_ADDR'] . "'";

        $db->Query($query);

        return $token;
    }

    //Rinfresca il token di autenticazione
    static private function RefreshToken($token)
    {
        AA_Log::Log(get_class() . "->RefreshToken($token)");

        $db = new AA_Database();

        $query = "UPDATE tokens SET data_rilascio=NOW() where token ='" . addslashes($token) . "'";

        $db->Query($query);
    }

    //Restituisce l'utente attualmente loggato (guest se non c'è nessun utente loggato)
    static public function GetCurrentUser()
    {
        //AA_Log::Log(get_class()."->GetCurrentUser()");
        $platform = AA_Platform::GetInstance();

        if ($platform->isValid()) {
            //AA_Log::Log(__METHOD__." - ".print_r($platform,true),100);
            return $platform->GetCurrentUser();
        }

        return AA_User::UserAuth();
    }

    //Restituisce l'utente guest
    static public function Guest()
    {
        AA_Log::Log(get_class() . "->Guest()");

        return new AA_User();
    }

    public function toXML()
    {
        AA_Log::Log(get_class() . "->toXML()");

        $result = '<utente id="' . $this->nID . '" livello="' . $this->nLivello . '" valid="' . $this->bIsValid . '" disabled="' . $this->nDisabled . '">';
        $result .= '<nome>' . $this->sNome . '</nome>';
        $result .= '<cognome>' . $this->sCognome . '</cognome>';
        $result .= '<user>' . $this->sUser . '</user>';
        $result .= '<email>' . $this->sEmail . '</email>';
        $result .= '<flags>' . $this->sFlags . '</flags>';
        //$result.=$this->oStruct->toXML();
        $result .= '</utente>';

        return $result;
    }

    //Rappresentazione stringa
    public function __toString()
    {
        AA_Log::Log(get_class() . "->__toString()");

        return $this->toXML();
    }

    //Verifica la presenza di qualche flag
    public function HasFlag($flag)
    {
        //AA_Log::Log(get_class()."->HasFlag($flag)");

        if ($flag == "") return false;

        $flags = explode("|", $this->sFlags);

        if (in_array($flag, $flags) || in_array("SU", $flags) || $this->nID == 1) {
            //AA_Log::Log(get_class()."->HasFlag($flag) - l'utente: ".$this->sUser."(".$this->nID.") ha il flag",100,FALSE,TRUE);
            return true;
        }

        //AA_Log::Log(get_class()."->HasFlag($flag) - l'utente: ".$this->sUser."(".$this->nID.") non ha il flag",100, false,true);
        return false;

        /*if(strpos($this->sFlags,$flag) !== false || $this->nID==1 || strpos($this->sFlags,"SU") !== false)
        {
            AA_Log::Log(get_class()."->HasFlag($flag) - l'utente: ".$this->sUser."(".$this->nID.") ha il flag",50);
            return true;
        }

        AA_Log::Log(get_class()."->HasFlag($flag) - l'utente: ".$this->sUser."(".$this->nID.") non ha il flag");
        return false;*/
    }

    //Restituisce il nome
    public function GetNome()
    {
        return $this->sNome;
    }

    //Restituisce il cognome
    public function GetCognome()
    {
        return $this->sCognome;
    }

    //Restituisce il nome utente
    public function GetUsername()
    {
        return $this->sUser;
    }

    //Restituisce l'email
    public function GetEmail()
    {
        return $this->sEmail;
    }

    //Restituisce il nome
    public function GetFlags($bArray = false)
    {
        if ($bArray) return explode("|", $this->sFlags);

        return $this->sFlags;
    }

    //Verifica se il nome utente esiste già
    static public function UserNameExist($userName = null)
    {
        AA_Log::Log(get_class() . "->UserNameExist($userName)");
        if ($userName == null) return false;

        $db = new AA_Database();
        $sql = "SELECT user FROM utenti where user='" . $userName . "' and eliminato = 0";
        if (!$db->Query($sql)) {
            AA_Log::Log(get_class() . "->UserNameExist($userName) - Errore nella query: " . $db->GetErrorMessage(), 100);
            return false;
        }

        if ($db->GetAffectedRows() > 0) return true;

        return false;
    }


    //Verifica se l'utente corrente può gestire gli utenti
    public function CanGestUtenti()
    {
        //AA_Log::Log(get_class()."->CanGestUtenti()");

        if (!$this->bIsValid) return false;

        if ($this->IsSuperUser()) return true;

        if ($this->nLivello != AA_Const::AA_USER_LEVEL_ADMIN) return false;

        if ($this->HasFlag("U0")) return false;

        return true;
    }

    //Verifica se l'utente corrente può gestire le strutture
    public function CanGestStruct()
    {
        AA_Log::Log(get_class() . "->CanGestStruct()");

        if (!$this->bIsValid) return false;

        if ($this->IsSuperUser()) return true;

        if ($this->nLivello != AA_Const::AA_USER_LEVEL_ADMIN) return false;

        if ($this->HasFlag("S0")) return false;

        return true;
    }

    //Verifica se l'utente corrente può modificare il livello dell'utente indicato 
    public function CanPromoteUserAsAdmin($idUser = null)
    {
        AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser)");

        if (!$this->IsValid()) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - utente corrente non valido: " . $this->GetUsername(), 100);
            return false;
        }

        //Il super utente può modificare tutto
        if ($this->IsSuperUser()) return true;

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //L'utente non può cambiare il suo livello
        if ($this->nID == $user->GetID()) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - l'utente non può modificare il proprio livello", 100);
            return false;
        }

        //Non si possono modificare i livelli di utenti dello stesso livello gerarchico (super user escluso)
        $struct = $this->GetStruct();
        if ($struct->GetServizio(true) == $user->GetStruct()->GetServizio(true) && $struct->GetServizio(true) != 0) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - utente corrente non può modificare il livello dell'utente: " . $this->GetUsername() . " (stesso servizio)", 100);
            return false;
        }
        if ($struct->GetDirezione(true) == $user->GetStruct()->GetDirezione(true) && $user->GetStruct()->GetServizio(true) == 0 && $struct->GetDirezione(true) != 0) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - utente corrente non può modificare il livello dell'utente: " . $this->GetUsername() . " (stessa direzione)", 100);
            return false;
        }
        if ($struct->GetAssessorato(true) == $user->GetStruct()->GetAssessorato(true) && $user->GetStruct()->GetDirezione(true) == 0 && $struct->GetAssessorato(true) != 0) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - utente corrente non può modificare il livello dell'utente: " . $this->GetUsername() . " (stesso assessorato)", 100);
            return false;
        }

        //Controlla se l'utente corrente può modificare l'utente
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - utente corrente non può modificare l'utente: " . $this->GetUsername(), 100);
            return false;
        }

        return true;
    }

    //Verifica se l'utente corrente può modificare l'utente indicato
    public function CanModifyUser($idUser = null)
    {
        AA_Log::Log(get_class() . "->CanModifyUser($idUser)");

        if (!$this->IsValid()) {
            AA_Log::Log(get_class() . "->CanModifyUser($idUser) - utente corrente non valido: " . $this->GetUsername(), 100);
            return false;
        }

        //Il super utente può modificare tutto
        if ($this->IsSuperUser()) return true;

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(get_class() . "->CanModifyUser($idUser) - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //L'utente può modificare se stesso
        if ($this->nID == $user->GetID()) return true;

        //Controlla se l'utente corrente è abilitato alla gestione utenti
        if (!$this->CanGestUtenti()) {
            AA_Log::Log(get_class() . "->CanModifyUser($idUser) - utente corrente non autorizzato alla gestione utenti: " . $this->GetUsername(), 100);
            return false;
        }

        if ($this->GetStruct()->GetAssessorato(true) != 0 && $this->GetStruct()->GetAssessorato(true) != $user->GetStruct()->GetAssessorato(true)) {
            AA_Log::Log(get_class() . "->CanModifyUser($idUser) - L'utente corrente non può modificare utenti di altre strutture.", 100);
            return false;
        }

        if ($this->GetStruct()->GetDirezione(true) != 0 && $this->GetStruct()->GetDirezione(true) != $user->GetStruct()->GetDirezione(true)) {
            AA_Log::Log(get_class() . "->CanModifyUser($idUser) - L'utente corrente non può modificare utenti di altre strutture.", 100);
            return false;
        }

        if ($this->GetStruct()->GetServizio(true) != 0 && $this->GetStruct()->GetServizio(true) != $user->GetStruct()->GetServizio(true)) {
            AA_Log::Log(get_class() . "->CanModifyUser($idUser) - L'utente corrente non può modificare utenti di altre strutture.", 100);
            return false;
        }

        //Non può modificare utenti amministratori dello stesso livello gerarchico
        if ($this->GetStruct()->GetServizio(true) == $user->GetStruct()->GetServizio(true) && $user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN && $this->GetStruct()->GetServizio(true) != 0) {
            AA_Log::Log(get_class() . "->CanModifyUser($idUser) - L'utente corrente (" . $this . ") non può modificare utenti amministratori dello stesso livello gerarchico (stesso servizio).", 100);
            return false;
        }

        if ($this->GetStruct()->GetDirezione(true) == $user->GetStruct()->GetDirezione(true) && $user->GetStruct()->GetServizio(true) == 0 && $user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN && $this->GetStruct()->GetDirezione(true) != 0) {
            AA_Log::Log(get_class() . "->CanModifyUser($idUser) - L'utente corrente (" . $this . ") non può modificare utenti amministratori dello stesso livello gerarchico (stessa direzione).", 100);
            return false;
        }

        if ($this->GetStruct()->GetAssessorato(true) == $user->GetStruct()->GetAssessorato(true) && $user->GetStruct()->GetDirezione(true) == 0 && $user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN && $this->GetStruct()->GetAssessorato(true) != 0) {
            AA_Log::Log(get_class() . "->CanModifyUser($idUser) - L'utente corrente (" . $this . ") non può modificare utenti amministratori dello stesso livello gerarchico (stesso assessorato).", 100);
            return false;
        }

        return true;
    }

    //Aggiungi un nuovo utente
    public function AddNewUser($params)
    {
        AA_Log::Log(get_class() . "->AddNewUser($params)");

        if ($this->IsGuest()) {
            AA_Log::Log(get_class() . "->AddNewUser($params) - utente corrente non valido", 100);
            return false;
        }

        //Recupera l'utente corrente
        if (!$this->isCurrentUser() || !$this->CanGestUtenti()) {
            AA_Log::Log(get_class() . "->AddNewUser($params) - utente non autenticato o non autorizzato alla gestione utenti", 100);
            return false;
        }

        //Verifica se il nome utente sia valido
        if ($params['user'] == "") {
            AA_Log::Log(get_class() . "->AddNewUser($params) - nome utente non impostato", 100);
            return false;
        }

        $struct = $this->GetStruct();
        if ($struct->GetAssessorato(true) != 0 && $struct->GetAssessorato(true) != $params['assessorato']) {
            AA_Log::Log(get_class() . "->AddNewUser($params) - Assessorato diverso", 100);
            return false;
        }
        if ($struct->GetDirezione(true) != 0 && $struct->GetDirezione(true) != $params['direzione']) {
            AA_Log::Log(get_class() . "->AddNewUser($params) - Direzione diversa", 100);
            return false;
        }
        if ($struct->GetServizio(true) != 0 && $struct->GetServizio(true) != $params['servizio']) {
            AA_Log::Log(get_class() . "->AddNewUser($params) - Servizio diverso", 100);
            return false;
        }

        //Non si possono istanziare utenti amministratori dello stesso livello gerarchico (super user escluso)
        if ($struct->GetServizio(true) == $params['servizio'] && $params['livello'] == 0  && $struct->GetServizio(true) != 0) {
            $params['livello'] = "1";
            AA_Log::Log(get_class() . "->AddNewUser($params) - L'utente corrente (" . $this->GetUsername() . ") non può istanziare utenti amministratori dello stesso livello gerarchico", 100);
        }
        if ($struct->GetDirezione(true) == $params['direzione'] && $params['servizio'] == 0 && $params['livello'] == 0 && $struct->GetDirezione(true) != 0) {
            $params['livello'] = "1";
            AA_Log::Log(get_class() . "->AddNewUser($params) - L'utente corrente (" . $this->GetUsername() . ") non può istanziare utenti amministratori dello stesso livello gerarchico", 100);
        }
        if ($struct->GetAssessorato(true) == $params['assessorato'] && $params['direzione'] == 0 && $params['livello'] == 0 && $struct->GetAssessorato(true) != 0) {
            $params['livello'] = "1";
            AA_Log::Log(get_class() . "->AddNewUser($params) - L'utente corrente (" . $this->GetUsername() . ") non può istanziare utenti amministratori dello stesso livello gerarchico", 100);
        }

        //Verifica se l'utente esiste già
        if (AA_user::UserNameExist($params['user'])) {
            AA_Log::Log(get_class() . "->AddNewUser($params) - nome utente già esistente.", 100);
            return false;
        }

        $flags = "U0|S0";
        $separatore = "";

        //Solo admin imposta le flags
        if ($this->IsSuperUser()) {
            if (!isset($params['gest_utenti'])) {
                $flags .= $separatore . "U0";
                $separatore = "|";
            }
            if (!isset($params['gest_struct'])) {
                $flags .= $separatore . "S0";
                $separatore = "|";
            }
            if (isset($params['gest_polizze'])) {
                $flags .= $separatore . "polizze";
                $separatore = "|";
            }
            if (isset($params['gest_debitori'])) {
                $flags .= $separatore . "debitori";
                $separatore = "|";
            }
            if (isset($params['gest_accessi'])) {
                $flags .= $separatore . "accessi";
                $separatore = "|";
            }
            if (isset($params['admin_gest_accessi'])) {
                $flags .= $separatore . "admin_accessi";
                $separatore = "|";
            }
            if (isset($params['art12'])) {
                $flags .= $separatore . "art12";
                $separatore = "|";
            }
            if (isset($params['art14c1a'])) {
                $flags .= $separatore . "art14c1a|art14";
                $separatore = "|";
            }
            if (isset($params['art14c1c'])) {
                $flags .= $separatore . "art14c1c|art14";
                $separatore = "|";
            }
            if (isset($params['art14c1bis'])) {
                $flags .= $separatore . "art14|art14c1bis";
                $separatore = "|";
            }
            if (isset($params['art23'])) {
                $flags .= $separatore . "art23";
                $separatore = "|";
            }
            if (isset($params['art22'])) {
                $flags .= $separatore . "art22";
                $separatore = "|";
            }
            if (isset($params['art22_admin'])) {
                $flags .= $separatore . "art22_admin";
                $separatore = "|";
            }
            if (isset($params['art30'])) {
                $flags .= $separatore . "art30";
                $separatore = "|";
            }
            if (isset($params['gest_processi'])) {
                $flags .= $separatore . "processi";
                $separatore = "|";
            }
            if (isset($params['gest_incarichi_titolari'])) {
                $flags .= $separatore . AA_Const::AA_USER_FLAG_INCARICHI_TITOLARI;
                $separatore = "|";
            }
            if (isset($params['gest_incarichi'])) {
                $flags .= $separatore . AA_Const::AA_USER_FLAG_INCARICHI;
                $separatore = "|";
            }
            if (isset($params['patrimonio'])) {
                $flags .= $separatore . "patrimonio";
                $separatore = "|";
            }
        }

        //la modifica delle schede pubblicate può essere abilitata anche dagli altri utenti amministratori
        if (isset($params['unlock']) && $params['livello'] == 0) {
            $flags .= $separatore . "P1";
            $separatore = "|";
        }

        //Inserisce l'utente
        $db = new Database();
        $sql = "INSERT INTO utenti SET ";
        $sql .= "id_assessorato='" . $params['assessorato'] . "'";
        $sql .= ",id_direzione='" . $params['direzione'] . "'";
        $sql .= ",id_servizio='" . $params['servizio'] . "'";
        $sql .= ",id_settore='" . $params['settore'] . "'";
        $sql .= ",user='" . addslashes($params['user']) . "'";
        if (isset($params['passwd'])) $sql .= ",passwd=MD5('" . $params['passwd'] . "')";
        else $sql .= ",passwd=MD5('" . date("Y/m/d H:i") . "')";
        $sql .= ",livello='" . $params['livello'] . "'";
        $sql .= ",nome='" . addslashes($params['nome']) . "'";
        $sql .= ",cognome='" . addslashes($params['cognome']) . "'";
        $sql .= ",email='" . $params['email'] . "'";
        $sql .= ",flags='" . $flags . "'";
        if (isset($params['disable'])) $sql .= ",disable='1'";
        else $sql .= ",disable='0'";

        if ($db->Query($sql) === false) {
            AA_Log::Log(get_class() . "->AddNewUser($params) - Errore: " . $db->lastError . " - nella query: " . $sql, 100);
            return false;
        }

        AA_Log::LogAction($this->GetID(), "1,9," . $db->lastInsertId, Database::$lastQuery); //Old stuff

        return true;
    }

    //Aggiorna L'utente
    public function UpdateUser($idUser, $params)
    {
        AA_Log::Log(get_class() . "->UpdateUser($idUser, $params)");

        if ($this->IsGuest()) {
            AA_Log::Log(get_class() . "->UpdateUser($idUser, $params) - utente corrente non valido", 100);
            return false;
        }

        //Verifica se l'utente corrente può gestire gli utenti
        if (!$this->isCurrentUser()) {
            AA_Log::Log(get_class() . "->UpdateUser($idUser, $params) - utente non autenticato.", 100);
            return false;
        }

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(get_class() . "->UpdateUser($idUser, $params) - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica se l'utente corrente può modificare l'utente indicato
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(get_class() . "->UpdateUser($idUser, $params) - L'utente corrente (" . $this->GetUsername() . ") non può modificare l'utente indicato: " . $user->GetUsername(), 100);
            return false;
        }

        //Non si può modificare il livello per utenti amministratori dello stesso livello gerarchico (super user escluso)
        $struct = $this->GetStruct();
        if ($struct->GetServizio(true) == $params['servizio'] && $params['livello'] == 0 && $struct->GetServizio(true) != 0) {
            $params['livello'] = "";
            AA_Log::Log(get_class() . "->UpdateUser($idUser, $params) - L'utente corrente (" . $this->GetUsername() . ") non può modificare il livello dell'utente indicato: " . $user->GetUsername(), 100);
        }
        if ($struct->GetDirezione(true) == $params['direzione'] && $params['servizio'] == 0 && $params['livello'] == 0 && $struct->GetDirezione(true) != 0) {
            $params['livello'] = "";
            AA_Log::Log(get_class() . "->UpdateUser($idUser, $params) - L'utente corrente (" . $this->GetUsername() . ") non può modificare il livello dell'utente indicato: " . $user->GetUsername(), 100);
        }
        if ($struct->GetAssessorato(true) == $params['assessorato'] && $params['direzione'] == 0 && $params['livello'] == 0 && $struct->GetAssessorato(true) != 0) {
            $params['livello'] = "";
            AA_Log::Log(get_class() . "->UpdateUser($idUser, $params) - L'utente corrente (" . $this->GetUsername() . ") non può modificare il livello dell'utente indicato: " . $user->GetUsername(), 100);
        }

        $flags = "";
        $separatore = "";

        //Solo admin imposta le flags
        if ($this->IsSuperUser()) {
            if (!isset($params['gest_utenti'])) {
                $flags .= $separatore . "U0";
                $separatore = "|";
            }
            if (!isset($params['gest_struct'])) {
                $flags .= $separatore . "S0";
                $separatore = "|";
            }
            if (isset($params['gest_polizze'])) {
                $flags .= $separatore . "polizze";
                $separatore = "|";
            }
            if (isset($params['gest_debitori'])) {
                $flags .= $separatore . "debitori";
                $separatore = "|";
            }
            if (isset($params['gest_accessi'])) {
                $flags .= $separatore . "accessi";
                $separatore = "|";
            }
            if (isset($params['admin_gest_accessi'])) {
                $flags .= $separatore . "admin_accessi";
                $separatore = "|";
            }
            if (isset($params['art12'])) {
                $flags .= $separatore . "art12";
                $separatore = "|";
            }
            if (isset($params['art14c1a'])) {
                $flags .= $separatore . "art14c1a|art14";
                $separatore = "|";
            }
            if (isset($params['art14c1c'])) {
                $flags .= $separatore . "art14c1c|art14";
                $separatore = "|";
            }
            if (isset($params['art14c1bis'])) {
                $flags .= $separatore . "art14|art14c1bis";
                $separatore = "|";
            }
            if (isset($params['art23'])) {
                $flags .= $separatore . "art23";
                $separatore = "|";
            }
            if (isset($params['art22'])) {
                $flags .= $separatore . "art22";
                $separatore = "|";
            }
            if (isset($params['art22_admin'])) {
                $flags .= $separatore . "art22_admin";
                $separatore = "|";
            }
            if (isset($params['art30'])) {
                $flags .= $separatore . "art30";
                $separatore = "|";
            } //old

            if (isset($params['gest_processi'])) {
                $flags .= $separatore . "processi";
                $separatore = "|";
            }
            if (isset($params['gest_incarichi_titolari'])) {
                $flags .= $separatore . AA_Const::AA_USER_FLAG_INCARICHI_TITOLARI;
                $separatore = "|";
            }
            if (isset($params['gest_incarichi'])) {
                $flags .= $separatore . AA_Const::AA_USER_FLAG_INCARICHI;
                $separatore = "|";
            }
            if (isset($params['patrimonio'])) {
                $flags .= $separatore . "patrimonio";
                $separatore = "|";
            }

            //AA_Log::Log(get_class()."->UpdateUser($idUser, $params)", 100, false,true);
        }

        //la modifica delle schede pubblicate può essere abilitata anche dagli altri utenti amministratori
        if (isset($params['unlock']) && $params['livello'] == 0) {
            $flags .= $separatore . "P1";
            $separatore = "|";
        }

        //Aggiorna l'utente
        $db = new Database();
        $sql = "UPDATE utenti SET user=user";
        if ($params['passwd'] != "") $sql .= ",passwd=MD5('" . $params['passwd'] . "')";

        //Dati aggionabili solo se utenti diversi
        if ($this->GetID() != $user->GetID()) {
            $sql .= ",id_assessorato='" . $params['assessorato'] . "'";
            $sql .= ",id_direzione='" . $params['direzione'] . "'";
            $sql .= ",id_servizio='" . $params['servizio'] . "'";
            $sql .= ",id_settore='" . $params['settore'] . "'";
            if ($params['livello'] != "") $sql .= ",livello='" . $params['livello'] . "'";
            if ($this->IsSuperUser()) $sql .= ",flags='" . $flags . "'";
            if (isset($params['disable'])) $sql .= ",disable='1'";
            else $sql .= ",disable='0'";
        }

        $sql .= ",nome='" . addslashes($params['nome']) . "'";
        $sql .= ",cognome='" . addslashes($params['cognome']) . "'";
        $sql .= ",email='" . $params['email'] . "'";

        $sql .= " where id='" . $user->GetID() . "' LIMIT 1";

        if ($db->Query($sql) === false) {
            AA_Log::Log(get_class() . "->UpdateUser($idUser, $params)  - Errore: " . $db->lastError . " - nella query: " . $sql, 100);
            return false;
        }

        AA_Log::LogAction($this->GetID(), "2,9," . $user->GetID(), Database::$lastQuery); //Old stuff

        return true;
    }

    //Elimina l'utente indicato
    public function DeleteUser($idUser)
    {
        AA_Log::Log(get_class() . "->DeleteUser($idUser)");

        if ($this->IsGuest()) {
            AA_Log::Log(get_class() . "->DeleteUser($idUser) - utente corrente non valido", 100);
            return false;
        }

        //Verifica se l'utente corrente può gestire gli utenti
        if (!$this->isCurrentUser()) {
            AA_Log::Log(get_class() . "->DeleteUser($idUser) - utente corrente non autenticato.", 100);
            return false;
        }

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(get_class() . "->DeleteUser($idUser) - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica se l'utente corrente può modificare l'utente indicato
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(get_class() . "->DeleteUser($idUser) - L'utente corrente (" . $this->GetUsername() . ") non può modificare l'utente indicato: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica che non sia l'utente corrente
        if (!$this->GetID() == $user->GetID()) {
            AA_Log::Log(get_class() . "->DeleteUser($idUser) - L'utente corrente (" . $this->GetUsername() . ") non può eliminare se stesso", 100);
            return false;
        }

        //Elimina l'utente indicato
        $db = new Database();
        $sql = "UPDATE utenti SET eliminato=1 where id='" . $user->GetID() . "' LIMIT 1";

        if ($db->Query($sql) === false) {
            AA_Log::Log(get_class() . "->DeleteUser($idUser)  - Errore: " . $db->lastError . " - nella query: " . $sql, 100);
            return false;
        }

        AA_Log::LogAction($this->GetID(), "3,9," . $user->GetID(), Database::$lastQuery); //Old stuff

        return true;
    }

    //Resetta la password dell'utente associato alla email indicata e la spedisce alla casella indicata
    static public function ResetPassword($email, $bSendEmail = true)
    {
        AA_Log::Log(get_class() . "->RecoverPassword($email)");

        $users = AA_User::LoadUsersFromEmail($email);

        if (is_array($users) && count($users) > 0) {
            $credenziali = "";
            $db = new Database();

            foreach ($users as $user) {
                //Verifica che l'utente sia valido
                if (!$user->IsValid()) {
                    AA_Log::Log(get_class() . "->RecoverPassword($email) - Utente non trovato.", 100);
                }

                //Verifica se l'utente è disattivato
                if ($user->IsDisabled()) {
                    AA_Log::Log(get_class() . "->RecoverPassword($email) - Utente disattivato.", 100);
                }

                //Reimposta la password
                if ($user->IsValid() && !$user->IsDisabled()) {
                    $struttura = $user->GetStruct()->GetAssessorato();
                    if ($user->GetStruct()->GetDirezione(true) != 0) $struttura .= " - " . $user->GetStruct()->GetDirezione();
                    if ($user->GetStruct()->GetServizio(true) != 0) $struttura .= " - " . $user->GetStruct()->GetServizio();

                    $newPwd = substr(md5(uniqid(mt_rand(), true)), 0, 8);

                    //Reimposta le credenziali dell'utente
                    $query = "UPDATE utenti set passwd=MD5('" . $newPwd . "') where id='" . $user->GetID() . "' LIMIT 1";

                    if (!$db->Query($query)) {
                        AA_Log::Log(get_class() . "->RecoverPassword($email) - Errore durante l'aggiornamento della password per l'utente: " . $user->GetUserName() . " - " . $db->lastError, 100);
                    } else {
                        $credenziali .= '
                        struttura: ' . $struttura . '
                        nome utente: ' . $user->GetUserName() . '
                        password: ' . $newPwd . '
                        ';
                    }
                }
            }

            if ($credenziali != "") {
                $oggetto = "Amministrazione Aperta - Reset della password.";

                $corpo = '<p>Buongiorno,
                E\' stato richiesto il reset della password per l\'accesso alla piattaforma applicativa "Amministrazione Aperta", per le pubblicazioni sul sito istituzionale di cui al d.lgs.33/2013.
                               
                url: http://sitod.regione.sardegna.it/web/amministrazione_aperta

                di seguito le credenziali per l\'accesso:

                ' . $credenziali . '
        
                E\' possibile cambiare la password accedendo al proprio profilo utente, dopo aver effettuato il login sulla piattaforma.

                Le utenze che hanno associato l\'indirizzo email sul proprio profilo possono effettuare il login sulla piattaforma indicando l\'indirizzo email in vece del nome utente
        
                Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>';

                $firma = '<div>--
                            <div><strong>Amministrazione Aperta</strong></div>
                            <div>Presidentzia</div>
                            <div>Presidenza</div>
                            <div>V.le Trento, 69 - 09123 Cagliari</div>
                            <img src="http://sitod.regione.sardegna.it/web/logo.jpg" data-mce-src="http://sitod.regione.sardegna.it/web/logo.jpg" moz-do-not-send="true" width="205" height="60"></div>';

                if ($bSendEmail) {
                    if (!SendMail(array($email), array(), $oggetto, nl2br($corpo) . $firma, array(), 1)) {
                        AA_Log::Log(get_class() . "->RecoverPassword($email) - Errore nell'invio della email a: " . $email, 100);
                        return false;
                    }
                }

                return true;
            } else {
                AA_Log::Log(get_class() . "->RecoverPassword($email) - Nessun utente valido trovato.", 100);
                return false;
            }
        } else {
            AA_Log::Log(get_class() . "->RecoverPassword($email) - Nessun utente valido trovato.", 100);
            return false;
        }

        return false;
    }
}

//Utilità
class AA_Utils
{
    //Formata un numero
    static public function number_format($number, $decimals='', $sep1='', $sep2='',$round=true) 
    {
        if($round) return number_format($number, $decimals, $sep1, $sep2);

        $resto=($number * pow(10 , $decimals + 1) % 10 );
        if ($resto >= 5)
        {
            $diff=$resto*pow(10 , -($decimals+1));
            //AA_Log::Log(__METHOD__." - cambio da: ".$number." a: ".($number-$diff),100);
            $number -= $diff;
        }  
        return number_format($number, $decimals, $sep1, $sep2);
    }

    //Accoda il log attuale al log di sessione
    static public function AppendLogToSession()
    {
        //$_SESSION['log'].=AA_Log::toHTML(true);
    }

    //funzione per la generazione di identificativi univoci
    static public function uniqid($lenght = 13)
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            return uniqid();
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

    //Restituisce il log di sessione in formato html
    static public function GetSessionLog()
    {
        $return = "";

        $session_log = array_reverse(unserialize($_SESSION['log']));

        foreach ($session_log as $key => $curLog) {
            $return .= '<div style="display:flex; flex-direction: row; justify-content: space-between; align-items: stretch; flex-wrap: wrap; width: 100%; border: 1px solid black; margin-bottom: 1em; font-size: smaller">';
            $return .= '<div style="width: 8%; border: 1px solid black; text-align: center; font-weight: bold; background-color: #DBDBDB; padding: .1em;">Data</div>';
            $return .= '<div style="display: flex; align-items: flex-start; width: 4%; border: 1px solid black; text-align: center; font-weight: bold; background-color: #DBDBDB; padding: .1em;"><div style="width: 100%">Livello</div></div>';
            $return .= '<div style="width: 42%; border: 1px solid black;text-align: center; font-weight: bold; background-color: #DBDBDB; padding: .1em;">Messaggio</div>';
            $return .= '<div style="width: 45%; border: 1px solid black;text-align: center; font-weight: bold; background-color: #DBDBDB;padding: .1em;">backtrace</div>';
            $return .= '<div style="width: 8%; border: 1px solid black;text-align: center; padding: .1em;"><span>' . $curLog->GetTime() . '</span></div>';
            $return .= '<div style="display: flex; align-items: flex-start; width: 4%; border: 1px solid black; text-align: center; padding: .1em;"><div style="width: 100%">' . $curLog->GetLevel() . '</div></div>';
            $return .= '<div style="width: 42%; border: 1px solid black; padding: .1em; overflow: auto; word-break: break-all;">' . htmlentities($curLog->GetMsg()) . '</div>';
            $return .= '<div style="width: 45%; border: 1px solid black; padding: .1em; font-size: smaller">';
            $html = "";
            $i = 0;
            foreach ($curLog->GetBackTrace() as $key => $value) {
                if ($i > 0) {
                    $html .= "<p>#" . $key . " - " . $value['file'] . " (line: " . $value['line'] . ")";
                    $html .= "<br/>" . $value['class'] . $value['type'] . $value['function'] . "(";
                    $separatore = "";
                    foreach ($value['args'] as $curArg) {
                        if ($curArg == "") $html .= $separatore . '""';
                        else if (!is_array($curArg)) $html .= $separatore . htmlentities($curArg);
                        $separatore = ",";
                    }
                    $html .= ")</p>";
                }
                $i++;
            }
            if ($html == "") $html = "&nbsp;";

            $return .= $html . '</div></div>';
        }

        return $return;
    }

    //Reinizializza il log di sessione
    static public function ResetSessionLog()
    {
        $_SESSION['log'] = "";
    }

    //Check SQL strings
    static public function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        AA_Log::Log(get_class() . "->GetSQLValueString($theValue, $theType, $theDefinedValue, $theNotDefinedValue)");

        $theValue = addslashes($theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }

        return $theValue;
    }

    //Old stuff user check
    static public function CheckUser($reqUser, $levelMin, $id_assessorato = 0, $id_direzione = 0, $id_servizio = 0, $id_settore = 0)
    {
        AA_Log::Log(get_class() . "->CheckUser($reqUser, $levelMin, $id_assessorato, $id_direzione, $id_servizio, $id_settore)");

        if (!$reqUser) return true;

        $user = AA_User::GetCurrentUser();
        if ($user->IsGuest()) return false;

        if ($user->GetLevel() > $levelMin) return false;

        $struct = $user->GetStruct();
        if ($id_assessorato != 0 && $struct->GetAssessorato(true) != $id_assessorato) return false;
        if ($id_direzione != 0 && $struct->GetDirezione(true) != $id_direzione) return false;
        if ($id_servizio != 0 && $struct->GetServizio(true) != $id_servizio) return false;

        return true;
    }

    //Rimuove le informazioni di autenticazione più vecchie di 1 giorno
    static public function CleanOldTokens()
    {
        AA_Log::Log(get_class() . "->CleanOldTokens()", 100);

        $db = new Database();

        $query = "DELETE from tokens where data_rilascio < '" . date("Y-m-d") . "'";
        $db->Query($query);
    }

    //Sostituisce le entità xml con i codici
    static public function Xml_entities($string)
    {
        return strtr(
            $string,
            array(
                "<" => "&lt;",
                ">" => "&gt;",
                '"' => "&quot;",
                "'" => "&apos;",
                "&" => "&amp;",
                "€" => "&#8364;"
            )
        );
    }

    //Sostituisce le entità xml con i codici
    static public function xmlentities($string)
    {
        return strtr(
            $string,
            array(
                "<" => "&lt;",
                ">" => "&gt;",
                '"' => "&quot;",
                "'" => "&apos;",
                "&" => "&amp;",
                "€" => "&#8364;"
            )
        );
    }

    //Verifica se l'URL esiste
    static public function CheckURL($url)
    {
        //no internet // da sistemare perchè il server non esce su internet
        return true;

        $handle = curl_init($url);
        //curl_setopt($handle, CURLOPT_PROXY, '172.30.3.100');
        //curl_setopt($handle, CURLOPT_PROXYTYPE, 'HTTP');
        //curl_setopt($handle, CURLOPT_PROXYPORT, '80');
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($handle, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($handle, CURLOPT_HEADER, 1);
        curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML,like Gecko) Chrome/27.0.1453.94 Safari/537.36");
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 2);

        // Get the HTML or whatever is linked in $url. 
        $risposta = curl_exec($handle);
        //error_log("errore: ".curl_error($handle));

        // Check for 404 (file not found). 
        $httpCode = curl_getinfo($handle);
        curl_close($handle);

        //timeout

        // If the document has loaded successfully without any redirection or error 
        if ($httpCode['http_code'] != 200) {
            //echo $httpCode."<br/>";
            error_log("codice http: " . $httpCode['http_code']);
            //error_log("risposta: ".$risposta."\n");
            return false;
        } else {
            //echo $httpCode."<br/>";
            return true;
        }
    }
}

//Generic object
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
    public function UpdateDb($user = null, $data = null, $bLog = true)
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
            $this->AddLog("Modifica", AA_Const::AA_OPS_UPDATE, $user);
        }

        if ($this->DbSync($user)) {
            if ($this->oParent instanceof AA_Object && $this->IsParentUpdateEnabled()) return $this->oParent->UpdateDb($user);
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

        if (($this->nStatus & AA_Const::AA_STATUS_PUBBLICATA) > 0 && ($this->nStatus & AA_Const::AA_STATUS_REVISIONATA) > 0) {
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

        if (!$this->VerifyDbLoad($user)) {
            return false;
        }

        $db = new Database();
        $query = "SELECT * from " . $this->oDbBind->GetTable() . " WHERE id='" . $id . "' LIMIT 1";
        if (!$db->Query($query)) {
            AA_Log::Log(__METHOD__ . " - Errore nella query: " . $query, 100, false, true);
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

#------------- Template View Class ----------------------------
#Classe base per la gestione delle views degli oggetti
class AA_ObjectTemplateView
{
    #Restituisce la visualizzazione per la finestra di dettaglio
    private $sDetail = "";
    public function SetDetail($newContent)
    {
        $this->sDetail = $newContent;
    }

    public function GetDetail()
    {
        return $this->sDetail;
    }

    public function DetailView()
    {
    }
}
#--------------------------------------------------

#Classe generico elemento html
class AA_XML_Element_Generic
{
    private $oParent = null;
    public function __construct($type = "div", $id = "AA_XML_ELEMENT_GENERIC", $parent = null)
    {
        $this->sElement = $type;
        $this->sId = $id;

        $this->sInnerClass = get_class();

        if ($parent instanceof AA_XML_Element_Generic) {
            $parent->AppendChild($this);
        }
    }

    //Flag di visibilità
    private $bHide = false;
    public function Hide($bHide = true)
    {
        $this->bHide = $bHide;
    }
    public function IsHidden()
    {
        return $this->bHide;
    }
    public function IsVisible()
    {
        return !$this->bHide;
    }
    public function Show($bShow = true)
    {
        if ($bShow) $this->bHide = false;
        else $this->bHide = true;
    }

    //Aggiungi sempre il tag di chiusura anche se il contenuto è vuoto;
    private $bAlwaysAddEndTag = false;
    public function AddAlwaysEndTag($bEnable = true)
    {
        $this->bAlwaysAddEndTag = $bEnable;
    }

    //Imposta il genitore
    public function SetParent($parent = null)
    {
        $this->oParent = null;
        if ($parent instanceof AA_XML_Element_Generic) {
            $this->oParent = $parent;
        }
    }
    public function GetParent()
    {
        return $this->oParent;
    }

    private $sId = "AA_XML_ELEMENT_GENERIC";
    public function SetId($id = "AA_XML_ELEMENT_GENERIC")
    {
        if ($id != "") $this->sId = $id;
    }
    public function GetId()
    {
        return $this->sId;
    }

    //Imposta il tipo di elemento
    private $sElement = "div";
    public function SetElement($element = "div")
    {
        if ($element != "") $this->sElement = $element;
    }
    public function GetElement()
    {
        return $this->sElement;
    }

    //stile
    private $sStyle = "";
    public function SetStyle($style = "", $bAppend = false)
    {
        if (!$bAppend) $this->sStyle = $style;
        else {
            if ($this->sStyle != "") $this->sStyle .= ";" . $style;
            else $this->sStyle .= $style;
        }
    }
    public function GetStyle()
    {
        return $this->sStyle;
    }

    //Imposta la classe
    private $sClass = "";
    protected $sInnerClass = "AA_XML_Generic_Element";

    public function SetClass($class = "")
    {
        $this->sClass = $class;
    }
    public function GetClass()
    {
        return $this->sClass;
    }

    //Imposta gli attributi
    private $aAttribs = array();
    public function SetAttribs($attribs)
    {
        if (is_array($attribs)) $this->aAttribs = $attribs;
    }
    public function GetAttribs()
    {
        return $this->aAttribs;
    }
    public function SetAttribute($attribute = "", $value = "")
    {
        if ($attribute != "" && $attribute != "id" && $attribute != "style" && $attribute != "class") $this->aAttribs[$attribute] = $value;
    }
    public function GetAttribute($attribute = "")
    {
        if ($attribute == "id") return $this->sId;
        if ($attribute == "style") return $this->sStyle;
        if ($attribute == "class") return $this->sClass;
        if ($attribute != "" && array_key_exists($attribute, $this->aAttribs)) return $this->aAttribs[$attribute];
        return "";
    }

    //Elementi figli
    private $aChildren = array();
    public function AppendChild($child = "")
    {
        if ($child instanceof AA_XML_Element_Generic) {
            $this->aChildren[] = $child;
            $child->SetParent($this);
        } else $this->aChildren[] = new AA_XML_Element_Generic($child);
    }

    //Restituisce il primo figlio con l'identificativo indicato
    public function GetChild($id = "")
    {
        if ($id == "") return null;

        foreach ($this->aChildren as $curChild) {
            if (strcmp($curChild->GetId(), $id) == 0) return $curChild;
        }

        return null;
    }

    //Restituisce la chiave di ordinamento del figlio indicato
    public function GetChildKey($child = null)
    {
        if ($child instanceof AA_XML_Element_Generic) {
            $key = array_search($child, $this->aChildren);
            if ($key !== false) return $key;
        }

        if ($child == "") return -1;

        foreach ($this->aChildren as $key => $curChild) {
            if (strcmp($curChild->GetId(), $child) == 0) return $key;
        }

        return -1;
    }

    //inserisce un figlio all'inizio
    public function InsertChild($child = "")
    {
        if ($child instanceof AA_XML_Element_Generic) {
            array_splice($this->aChildren, 0, 0, array($child));
            $child->SetParent($this);
        }
    }

    //inserisce un figlio dopo un altro
    public function InsertChildAfter($child = null, $childRef = null)
    {
        if ($child instanceof AA_XML_Element_Generic && !($childRef instanceof AA_XML_Element_Generic)) return $this->AppendChild($child);
        if ($child instanceof AA_XML_Element_Generic && $childRef instanceof AA_XML_Element_Generic) {
            $key = array_search($childRef, $this->aChildren);
            if ($key !== false) {
                array_splice($this->aChildren, ($key + 1), 0, array($child));
                $child->SetParent($this);
                return;
            } else {
                return $this->AppendChild($child);
            }
        }
    }

    //inserisce un figlio prima di un altro
    public function InsertChildBefore($child = null, $childRef = null)
    {
        if ($child instanceof AA_XML_Element_Generic && !($childRef instanceof AA_XML_Element_Generic)) return $this->InsertChild($child);
        if ($child instanceof AA_XML_Element_Generic && $childRef instanceof AA_XML_Element_Generic) {
            $key = array_search($childRef, $this->aChildren);
            if ($key !== false) {
                array_splice($this->aChildren, $key, 0, array($child));
                $child->SetParent($this);
                return;
            } else {
                return $this->InsertChild($child);
            }
        }
    }

    //Rimuove un figlio
    public function RemoveChild($child = null)
    {
        if ($child instanceof AA_XML_Element_Generic) {
            $key = array_search($child, $this->aChildren);
            if ($key !== false) {
                array_splice($this->aChildren, $key + 1, 1);
                $child->SetParent(null);
                return true;
            }
        }

        //Non viene passato un oggetto
        foreach ($this->aChildren as $key => $curChild) {
            if (strcmp($curChild->GetId(), $child) == 0) {
                array_splice($this->aChildren, $key + 1, 1);
                $curChild->SetParent(null);
                return true;
            }
        }

        return false;
    }

    //Scambia due elementi
    public function Swap($child_one = null, $child_two = null)
    {
        if (!($child_one instanceof AA_XML_Element_Generic)) $child_one = $this->GetChild($child_one);
        if ($child_one == null) return false;

        if (!($child_two instanceof AA_XML_Element_Generic)) $child_two = $this->GetChild($child_two);
        if ($child_two == null) return false;

        $key_child_one = $this->GetChildKey($child_one);
        $key_child_two = $this->GetChildKey($child_two);

        $this->aChildren[$key_child_one] = $child_two;
        $this->aChildren[$key_child_two] = $child_one;

        return true;
    }

    //Rimuove tutti i figli
    public function RemoveAllChildren()
    {
        foreach ($this->aChildren as $curChild) {
            $curChild->SetParent(null);
        }
        $this->aChildren = array();
    }

    //Testo da inserire prima o dopo dei figli
    private $sTextBeforeChildren = "";
    private $sTextAfterChildren = "";
    public function SetText($text = "", $bBeforeChildren = true)
    {
        if ($bBeforeChildren) $this->sTextBeforeChildren = $text;
        else $this->sTextAfterChildren = $text;
    }
    public function GetText($bBeforeChildren = true)
    {
        if ($bBeforeChildren) return $this->sTextBeforeChildren;
        else return $this->sTextAfterChildren;
    }

    //Restituisce la rappresentazione dell'elemento come stringa di testo
    public function __toString()
    {
        //Restituisce una string vuota se l'elemento è invisibile
        if ($this->bHide) return "";

        $out = "<" . $this->sElement;
        if ($this->sId != "") $out .= ' id="' . addslashes($this->sId) . '"';
        if ($this->sStyle != "") $out .= ' style="' . $this->sStyle . '"';
        if ($this->sInnerClass != "" || $this->sClass != "") $out .= ' class="' . $this->sInnerClass . ' ' . $this->sClass . '"';

        foreach ($this->aAttribs as $attr => $value) {
            $out .= " " . $attr . '="' . $value . '"';
        }

        $content = "";
        foreach ($this->aChildren as $curChild) {
            $content .= $curChild;
        }

        //if($content == "" && $this->sTextBeforeChildren == "" && $this->sTextAfterChildren == "" && !$this->bAddAlwaysEndTag) $out.=" />";
        $out .= ">" . $this->sTextBeforeChildren . $content . $this->sTextAfterChildren . "</" . $this->sElement . ">";

        return $out;
    }
}
#--------------------------------------------------

#Classe div
class AA_XML_Div_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_DIV_ELEMENT", $parent = null)
    {
        parent::__construct("div", $id, $parent);

        $this->sInnerClass = get_class();

        //Aggiungi sempre il tag finale.
        $this->AddAlwaysEndTag();
    }
}
#-------------------------------------------------

#Classe a
class AA_XML_A_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_A_ELEMENT", $parent = null)
    {
        parent::__construct("a", $id, $parent);

        $this->sInnerClass = get_class();

        //Aggiungi sempre il tag finale.
        $this->AddAlwaysEndTag();
    }
}
#-------------------------------------------------

#Classe form
class AA_XML_Form_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_FORM_ELEMENT", $parent = null)
    {
        parent::__construct("form", $id, $parent);

        $this->sInnerClass = get_class();

        //Aggiungi sempre il tag finale.
        $this->AddAlwaysEndTag();
    }
}
#-------------------------------------------------


#Classe input
class AA_XML_Input_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_INPUT_ELEMENT", $parent = null)
    {
        parent::__construct("input", $id, $parent);

        $this->sInnerClass = get_class();
    }
}
#-------------------------------------------------

#Classe file
class AA_XML_File_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_FILE_ELEMENT", $parent = null)
    {
        parent::__construct("file", $id, $parent);

        $this->sInnerClass = get_class();
    }
}
#-------------------------------------------------

#Classe textarea
class AA_XML_Textarea_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_TEXTAREA_ELEMENT", $parent = null)
    {
        parent::__construct("textarea", $id, $parent);

        $this->sInnerClass = get_class();

        //Aggiungi sempre il tag finale.
        $this->AddAlwaysEndTag();
    }
}
#-------------------------------------------------

#Classe select
class AA_XML_Select_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_SELECT_ELEMENT", $parent = null)
    {
        parent::__construct("select", $id, $parent);

        $this->sInnerClass = get_class();

        //Aggiungi sempre il tag finale.
        $this->AddAlwaysEndTag();
    }
}
#-------------------------------------------------

#Classe option
class AA_XML_Option_Element extends AA_XML_Element_Generic
{
    public function __construct($id = "AA_OPTION_ELEMENT", $parent = null)
    {
        parent::__construct("option", $id, $parent);

        $this->sInnerClass = get_class();

        //Aggiungi sempre il tag finale.
        $this->AddAlwaysEndTag();
    }
}
#-------------------------------------------------

#Classe per il generico box
class AA_GenericBoxTemplateView extends AA_XML_Div_Element
{
    public function __construct($content = "", $id = "", $parent = null)
    {
        if ($id == "") $id = get_class();

        //error_log(__METHOD__."(".$id.")");

        parent::__construct($id, $parent);

        $this->SetText($content);
    }

    public function SetContent($newContent)
    {
        $this->SetText($newContent);
    }
    public function GetContent()
    {
        return $this->GetText();
    }
    public function ToHtml()
    {
        return $this->__toString();
    }
    public function ContentView()
    {
        return $this->ToHtml();
    }
}

#classe per la gestione della vista delle voci di un accordion
class AA_GenericAccordionItemTemplateView
{
    #Identificativo dell'item (deve essere un valore univoco)
    protected $sId = "";
    public function GetId()
    {
        return $this->sId;
    }
    public function SetId($val = "")
    {
        $this->sId = $val;
    }

    #Identificativo della classe di oggetti
    protected $sClass = "GenericObject";
    public function GetClass()
    {
        return $this->sClass;
    }
    public function SetClass($val = "GenericObject")
    {
        $this->sClass = $val;
    }

    #Titolo dell'item sull'header (può essere una stringa html)
    protected $sTitle = "";
    protected $sTitleStyle = "width:100%; font-size: 1.2em; font-weight: bold; margin-bottom: .2em;";
    protected $bShowTitle = true;

    #sottotitolo dell'item sull'header (può essere una stringa html)
    protected $sSubTitle = "";
    protected $sSubTitleStyle = "width:100%; margin-bottom: .2em;";
    protected $bShowSubTitle = true;

    #pretitolo dell'item sull'header (può essere una stringa html)
    protected $sPreTitle = "";
    protected $sPreTitleStyle = "width:100%; margin-bottom: .2em;";
    protected $bShowPreTitle = true;
    public function ShowPreTitle($bVal = true)
    {
        $this->bShowPreTitle = $bVal;
    }
    public function SetPreTitle($val = "")
    {
        $this->sPreTitle = $val;
    }
    public function GetPreTitle()
    {
        return $this->sPreTitle;
    }

    #stato dell'item (bozza, pubblicata, revisionata, etc.)
    protected $sStatus = "";
    protected $sStatusStyle = "width:100%";
    protected $bShowStatus = true;

    #dettagli (data ultimo aggiornamento, utente, etc.)
    protected $sDetails = "";
    protected $sDetailsStyle = "width:100%; margin-bottom: .3em;";
    protected $bShowDetails = true;

    #tags
    protected $aTags = array();
    protected $aTagsStyle = "width:100%";
    protected $bShowTags = false;

    #utente
    protected $oUser = null;

    #Gestione del command box content
    protected $sHeaderCommandBoxContent = "";
    public function GetHeaderCommandBoxContent()
    {
        return $this->sHeaderCommandBoxContent;
    }
    public function SetHeaderCommandBoxContent($val = "")
    {
        $this->sHeaderCommandBoxContent = $val;
    }

    #Costruttore standard
    public function __construct($Title = "", $SubTitle = "", $Content = null, $user = null)
    {
        $this->sTitle = $Title;
        $this->sSubTitle = $SubTitle;
        if ($Content != null) $this->SetContent($Content);
        if ($user instanceof AA_User && $user->isCurrentUser()) $this->oUser = $user;
        else $this->oUser = AA_User::GetCurrentUser();
    }

    #Gestisce lo style dell'HeaderBox
    protected $sHeaderBoxStyle = "display: flex; flex-direction: row; justify-content: space-between; width:100%";
    public function GetHeaderBoxStyle()
    {
        return $this->sHeaderBoxStyle;
    }
    public function SetHeaderBoxStyle($val = "display: flex; flex-direction: row; justify-content: space-between; width:100%")
    {
        $this->sHeaderBoxStyle = $val;
    }

    #Ordine dell'item nella lista
    protected $nIndex = 0;
    public function SetIndex($val)
    {
        $this->nIndex = $val;
    }

    #Restituisce l'header view dell'item in html
    protected $sHeaderPreviewBoxStyle = "display: flex; flex-direction: column; justify-content: space-between; align-items: center; width:80%; font-size: .8em";
    protected $sHeaderCommandBoxStyle = "display: flex; flex-direction: row; justify-content: space-between; align-items: center; width:19%; font-size: .8em";
    protected $sTagsStyle="";
    protected $sTags="";
    public function HeaderView()
    {
        $HeaderBox = "<h3 class='" . $this->sClass . "_HeaderBox' id-object='" . $this->sId . "' order='" . $this->nIndex . "'><div style='" . $this->sHeaderBoxStyle . "'>";

        #inserisce l'header preview box
        $HeaderBox .= "<div class='" . $this->sClass . "_HeaderPreviewBox' style='" . $this->sHeaderPreviewBoxStyle . "'>";

        #Inserisce il preTitolo
        if ($this->bShowPreTitle && $this->sPreTitle != "") {
            $HeaderBox .= "<div class='" . $this->sClass . "_HeaderSubTitleBox' style='" . $this->sPreTitleStyle . "'>";
            $HeaderBox .= $this->sPreTitle;
            $HeaderBox .= "</div>";
        }

        #Inserisce il titolo
        if ($this->bShowTitle) {
            $HeaderBox .= "<div class='" . $this->sClass . "_HeaderTitleBox' style='" . $this->sTitleStyle . "'>";
            $HeaderBox .= $this->sTitle;
            $HeaderBox .= "</div>";
        }

        #Inserisce il sotto titolo
        if ($this->bShowSubTitle && $this->sSubTitle != "") {
            $HeaderBox .= "<div class='" . $this->sClass . "_HeaderSubTitleBox' style='" . $this->sSubTitleStyle . "'>";
            $HeaderBox .= $this->sSubTitle;
            $HeaderBox .= "</div>";
        }

        #Inserisce i dettagli
        if ($this->bShowDetails && $this->bShowDetails != "") {
            $HeaderBox .= "<div class='" . $this->sClass . "_HeaderDetailBox' style='" . $this->sDetailsStyle . "'>";
            $HeaderBox .= $this->sDetails;
            $HeaderBox .= "</div>";
        }

        #Inserisce lo status
        if ($this->bShowStatus && $this->bShowStatus != "") {
            $HeaderBox .= "<div class='" . $this->sClass . "_HeaderSubTitleBox' style='" . $this->sStatusStyle . "'>";
            $HeaderBox .= $this->sStatus;
            $HeaderBox .= "</div>";
        }

        #Inserisce i tags
        if ($this->bShowTags && $this->bShowTags != "") {
            $HeaderBox .= "<div class='" . $this->sClass . "_HeaderTagsBox' style='" . $this->sTagsStyle . "'>";
            $HeaderBox .= $this->sTags;
            $HeaderBox .= "</div>";
        }

        #Chiude l'header preview box
        $HeaderBox .= "</div>";

        #Inserisce il command box
        $HeaderBox .= "<div class='" . $this->sClass . "_HeaderCommandBox' style='" . $this->sHeaderCommandBoxStyle . "'>";

        $HeaderBox .= $this->sHeaderCommandBoxContent;

        $HeaderBox .= "</div>";

        #Chiude l'headerbox e l'header tag
        $HeaderBox .= "</div></h3>";

        //AA_Log::Log(get_class()."->HeaderView(): ".$HeaderBox,100,true,true);
        return $HeaderBox;
    }
    #---------------------------------------------------------------------

    #contenuto dell'item
    protected $sContent = null;
    public function SetContent($newContent)
    {
        $this->sContent = $newContent;
    }
    public function GetContent()
    {
        return $this->sContent;
    }

    #Restituisce il content view dell'item
    protected $sContentBoxStyle = "display: flex; flex-direction: column; justify-content: space-between; padding: 0.1em; font-size: 0.9em;";
    public function ContentView()
    {
        $ContentBox = "<div class='" . $this->sClass . "_ContentBox' id-object='" . $this->sId . "' style='" . $this->sContentBoxStyle . "'>";
        $ContentBox .= $this->sContent;
        $ContentBox .= "</div>";

        return $ContentBox;
    }
    #----------------------------------------------------------------------
}
#---------------------------------------------------------

#Classe per la gestione della vista della lista degli item dell'accordion
class AA_GenericAccordionTemplateView
{
    #header box
    private $oHeaderBox = null;
    private $bShowHeader = true;
    private $sHeaderBoxStyle = "width: 100%; height: 5%";

    #content box
    private $oContentBox = null;
    private $bShowContent = true;
    private $sContentBoxStyle = "width: 100%";

    #footer box
    private $oFooterBox = null;
    private $bShowFooter = true;
    private $sFooterBoxStyle = "width: 100%; height: 5%";

    #costruttore
    public function _construct()
    {
        $this->oHeaderBox = new AA_GenericBoxTemplateView();
        $this->oContentBox = new AA_GenericBoxTemplateView();
        $this->oFooterBox = new AA_GenericBoxTemplateView();
    }

    #restituisce l'header della lista
    private $sHeader = "";
    public function SetHeaderContent($newContent)
    {
        $this->sHeader = $newContent;
    }
    public function GetHeaderContent()
    {
        return $this->sHeader;
    }
    public function HeaderView()
    {
        #Custom header
        $this->oHeaderBox->SetContent($this->sHeader);
        $this->oHeaderBox->SetStyle($this->sHeaderBoxStyle);
        return $this->oHeaderBox->ContentView();
    }

    #Restituisce la visualizzazione del contenuto dell'accordion
    private $sContent = "";
    public function SetContent($newContent)
    {
        $this->sContent = $newContent;
    }
    public function GetContent()
    {
        return $this->sContent;
    }
    public function ContentView($boxed = true)
    {
        $custom_content = "";

        #Custom content
        $custom_content .= $this->sContent;

        $accordion_content = "";
        foreach ($this->aItems as $curItem) {
            $accordion_content .= $curItem->HeaderView() . $curItem->ContentView();
        }

        if ($boxed) {
            #accordion box
            $this->oContentBox->SetContent($custom_content . $accordion_content);
            $this->oContentBox->SetStyle($this->sContentBoxStyle);
            return $this->oContentBox->ContentView();
        } else {
            return $custom_content . $accordion_content;
        }
    }

    #restituisce il footer della lista
    private $sFooter = "";
    public function SetFooter($newContent)
    {
        $this->sFooter = $newContent;
    }
    public function GetFooter()
    {
        return $this->sFooter;
    }
    public function FooterView()
    {
        #Custom footer
        $this->oFooterBox->SetContent($this->sFooter);
        $this->oFooterBox->SetStyle($this->sFooterBoxStyle);
        return $this->oFooterBox->ContentView();
    }

    #Aggiunge un oggetto alla lista degli item gestiti
    private $aItems = array();
    public function AddItem($newObject = null)
    {
        if ($newObject instanceof AA_GenericAccordionItemTemplateView) {
            $newObject->SetIndex(count($this->aItems));
            $this->aItems[] = $newObject;
        } else {
            AA_Log::Log(get_class() . "->AddObject() - oggetto non valido", 100, true, true);
            return false;
        }
        return true;
    }

    #Restituisce l'array degli item gestiti
    public function GetItems()
    {
        return $this->aItems;
    }
}

//Classi per la gestione dei task
class AA_GenericTaskManager
{
    //task
    protected $aTasks = array();

    //Utente
    protected $oUser = null;

    //log dei task
    protected $aTaskLog = array();

    public function __construct($user = null)
    {
        if ($user instanceof AA_User && $user->isCurrentUser()) $this->oUser = $user;
        else $this->oUser = AA_User::GetCurrentUser();
    }

    //Registrazione di un nuovo task
    public function RegisterTask($task = null, $class = null)
    {
        if ($task instanceof AA_GenericTask) {
            AA_Log::Log(__METHOD__ . "() - Aggiunta di un nuovo task: " . $task->GetName());
            $this->aTasks[$task->GetName()] = $task;
        }

        if ($task != "" && class_exists($class)) {
            AA_Log::Log(__METHOD__ . "() - Aggiunta di un nuovo task (differito): " . $task . " - classe di gestione: " . $class);
            $this->aTasks[$task] = $class;
        }
    }

    //Svuotamento dei task
    public function Clear()
    {
        $this->aTasks = array();
    }

    //Restituisce il task in base al nome
    public function GetTask($name = "")
    {
        if ($name != "") {
            $task = $this->aTasks[$name];
            if ($task instanceof AA_GenericTask) return $task;
            else {
                if (class_exists($task)) {
                    $this->aTasks[$name] = new $task($this->oUser);
                    return $this->aTasks[$name];
                }
            }
        }

        return null;
    }

    //rimuove il task in base al nome
    public function UnregisterTask($name = "")
    {
        if ($name != "") {
            foreach ($this->aTasks as $key => $value) {
                if ($name == $key) $this->aTasks[$key] = null;
            }
        }
    }

    //Esegue un task
    public function RunTask($taskName = "")
    {
        $task = $this->GetTask($taskName);
        if ($task) {
            if ($task->Run()) {
                return true;
            }

            return false;
        }

        $this->aTaskLog[$taskName] = "<status id='status'>-1</status><error id='error'>" . __METHOD__ . "() - Task non registrato: " . $taskName . ".</error>";
        AA_Log::Log(__METHOD__ . "() - " . $this->aTaskLog[$taskName], 100, true, true);

        return false;
    }

    //Restituisce il log di un task
    public function GetTaskLog($taskName = "")
    {
        if ($this->aTasks[$taskName] instanceof AA_GenericTask) return $this->aTasks[$taskName]->GetLog();
        else return $this->aTaskLog[$taskName];
    }

    //Restituisce il log di errore di un task
    public function GetTaskError($taskName = "")
    {
        if ($this->aTasks[$taskName] instanceof AA_GenericTask) return $this->aTasks[$taskName]->GetError();
        else return $this->aTaskLog[$taskName];
    }

    //Verifica se un task è gestito
    public function IsManaged($taskName = "")
    {
        if ($taskName != "") {
            if ($this->aTasks[$taskName] != "") {
                if ($this->aTasks[$taskName] instanceof AA_GenericTask) return true;
                if (class_exists($this->aTasks[$taskName])) return true;
            }
        }

        return false;
    }
}

class AA_GenericModuleTaskManager extends AA_GenericTaskManager
{
    protected $oModule = null;
    public function GetModule()
    {
        return $this->oModule;
    }

    public function __construct($module = null, $user = null)
    {
        parent::__construct($user);

        if ($module instanceof AA_GenericModule) $this->oModule = $module;
        else $this->oModule = new AA_GenericModule($user);
    }

    //Registrazione di un nuovo task
    public function RegisterTask($task = null, $taskFunction = "")
    {

        if ($taskFunction == "") $taskFunction = "Task_" . $task;
        $module = $this->GetModule();
        if (method_exists($module, $taskFunction)) {
            AA_Log::Log(__METHOD__ . "() - Aggiunta di un nuovo task: " . $task . " - funzione: " . $taskFunction);
            $this->aTasks[$task] = new AA_GenericModuleTask($task, $this->oUser, $this, $taskFunction);
        } else {
            AA_Log::Log(__METHOD__ . "() - Errore task non registrato - task: " . $task . " - funzione: " . $taskFunction, 100);
        }
    }
}

class AA_GenericTask
{
    protected $sTaskName = "";
    protected $sTaskLog = "";
    protected $sTaskError = "";
    protected $oUser = null;
    protected $taskManager = null;

    public function GetTaskManager()
    {
        return $this->taskManager;
    }

    public function SetTaskManager($taskManager = null)
    {
        if ($taskManager instanceof AA_GenericModuleTaskManager) $this->taskManager = $taskManager;
    }

    public function GetName()
    {
        return $this->sTaskName;
    }

    public function SetName($name = "")
    {
        if ($name != "") {
            $this->sTaskName = $name;
        }
    }

    public function GetError()
    {
        return $this->sTaskError;
    }

    public function SetError($error)
    {
        $this->sTaskError = $error;
    }

    //Funzione per la gestione del task
    public function Run()
    {

        return true;
    }

    public function __construct($taskName = "", $user = null, $taskManager = null)
    {
        $this->SetTaskManager($taskManager);

        if ($taskName != "") {
            $this->sTaskName = $taskName;

            if ($user instanceof AA_User && $user->isCurrentUser()) $this->oUser = $user;
            else $this->oUser = AA_User::GetCurrentUser();
        }
    }

    public function GetLog()
    {
        return $this->sTaskLog;
    }

    public function SetLog($log)
    {
        $this->sTaskLog = $log;
    }
}

//Task generico modulo
class AA_GenericModuleTask extends AA_GenericTask
{
    protected $taskFunction = "";
    public function __construct($task = "", $user = null, $taskManager = null, $taskFunction = "")
    {
        if ($task == "") {
            $task = "GenericTask";
        }

        if ($taskFunction == "") {
            $this->taskFunction = "Task_" . $task;
        } else $this->taskFunction = $taskFunction;

        parent::__construct($task, $user, $taskManager);

        //AA_Log::Log(__METHOD__." - ".print_r($this,true),100);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        $module = $this->GetTaskManager()->GetModule();
        $taskFunction = $this->taskFunction;
        if (method_exists($module, $taskFunction)) return $module->$taskFunction($this);
        else {
            $this->sTaskLog = "<status id='status'>-1</status><error id='error'>" . __METHOD__ . "() - Task non registrato: " . $this->sTaskName . ".</error>";
            return false;
        }
    }
}
#--------------------------------------------

//Classe per la gestione delle risposte dei task
class AA_GenericTaskResponse
{
    protected $sError = "";
    protected $sContent = "";
    protected $nStatus = AA_Const::AA_TASK_STATUS_FAIL;

    public function SetStatus($newStatus = AA_Const::AA_TASK_STATUS_OK)
    {
        $status = AA_Const::AA_TASK_STATUS_OK | AA_Const::AA_TASK_STATUS_FAIL;
        $this->nStatus = $newStatus & $status;
    }
    public function GetStatus()
    {
        return $this->nStatus;
    }

    public function SetError($error = "")
    {
        $this->sError = $error;
    }

    public function GetError()
    {
        return $this->sError;
    }

    public function SetMsg($error = "")
    {
        $this->sError = $error;
    }

    public function GetMsg()
    {
        return $this->sError;
    }

    public function SetContent($val = "")
    {
        $this->sContent = $val;
    }

    public function GetContent()
    {
        return $this->sContent;
    }

    public function toString()
    {
    }
}

//Classe per la gestione dei task di sistema
class AA_SystemTaskManager extends AA_GenericTaskManager
{
    public function __construct($user = null)
    {
        parent::__construct($user);

        //Registrazione task per l'albero delle strutture
        $this->RegisterTask("struttura-utente", "AA_SystemTask_TreeStruct");

        //Restituisce lo stato dell piattaforma
        $this->RegisterTask("GetAppStatus", "AA_SystemTask_GetAppStatus");

        //Registrazione task per la finestra dell'albero delle strutture utente (nuova versione)
        $this->RegisterTask("GetStructDlg", "AA_SystemTask_GetStructDlg");

        //Restituisce la struttura base della finestra AMAAI
        $this->RegisterTask("AMAAI_Start", "AA_SystemTask_AMAAI_Start");

        //Restituisce la la finestra del pdf preview
        $this->RegisterTask("GetPdfPreviewDlg", "AA_SystemTask_GetPdfPreviewDlg");

        //imposta una variabile di sessione
        $this->RegisterTask("SetSessionVar", "AA_SystemTask_SetSessionVar");

        //Upload session file
        $this->RegisterTask("UploadSessionFile", "AA_SystemTask_UploadSessionFile");

        //Restituisce la finestra dei log di un oggetto
        $this->RegisterTask("GetLogDlg", "AA_SystemTask_GetLogDlg");
    }
}

//Task per la gestione dell'albero delle strutture
class AA_SystemTask_TreeStruct extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("struttura-utente", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: " + $this->GetName());
        $userStruct = $this->oUser->GetStruct();
        if ($this->oUser->isGuest()) $userStruct = AA_Struct::GetStruct(1, 0, 0, 0);

        if (!isset($_REQUEST['show_all'])) $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), $userStruct->GetDirezione(true), 0, $userStruct->GetTipo());
        else {
            if ($userStruct->GetTipo() > 0) $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), 0, 0, $userStruct->GetTipo());
            else $struct = AA_Struct::GetStruct(0, 0, 0, $userStruct->GetTipo());
        }

        if ($_REQUEST['format'] != "json") $this->sTaskLog = "<status id='status'>0</status>" . $struct->toXML() . "<error id='error'></error>";
        else $this->sTaskLog = "<status id='status'>0</status><content id'content' type='json' encode='base64'>" . $struct->toJSON(true) . "</content><error id='error'></error>";
        return true;
    }
}

//Task per la gestione dell'albero delle strutture
class AA_SystemTask_GetStructDlg extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetStructDlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: " + $this->GetName());
        $wnd = new AA_GenericStructDlg("AA_SystemStructDlg", "Organigramma", $_REQUEST, "", $_REQUEST['module'], $this->oUser);

        //AA_Log::Log(__METHOD__." - ".$wnd->toString(),100);

        $this->sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>" . $wnd->toBase64() . "</content><error id='error'></error>";
        return true;
    }
}

//Task per la visualizzazione del log di un oggetto
class AA_SystemTask_GetLogDlg extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetLogDlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: " + $this->GetName());
        $wnd = new AA_GenericLogDlg("AA_SystemLogDlg_" . $_REQUEST['id'], "Logs", $this->oUser);

        $this->sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>" . $wnd->toBase64() . "</content><error id='error'></error>";
        return true;
    }
}

//Task per la gestione del preview pdf
class AA_SystemTask_GetPdfPreviewDlg extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetPdfPreviewDlg", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: " + $this->GetName());
        $wnd = new AA_GenericPdfPreviewDlg();

        //AA_Log::Log(__METHOD__." - ".$wnd->toString(),100);

        $this->sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>" . $wnd->toBase64() . "</content><error id='error'></error>";
        return true;
    }
}

//Task per la memorizzazione di variabili di sessione
class AA_SystemTask_SetSessionVar extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("SetSessionVar", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: " + $this->GetName());

        $name = $_REQUEST['name'];
        if ($name != "") {
            AA_SessionVar::Set($_REQUEST['name'], $_REQUEST['value']);
            $this->sTaskLog = "<status id='status'>0</status><error id='error'>variabile impostata correttamente</error>";
            return true;
        } else {
            $this->sTaskLog = "<status id='status'>-1</status><error id='error'>variabile non impostata (nome non definito).</error>";
            return true;
        }
    }
}

//Task per la memorizzazione di file di sessione
class AA_SystemTask_UploadSessionFile extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("UploadSessionFile", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: " + $this->GetName());

        $value = false;
        foreach ($_FILES as $id => $curFile) {
            $value = AA_SessionFileUpload::Add($_REQUEST['file_id'], $curFile);
        }

        if ($value !== false) {
            $this->sTaskLog = json_encode(array("status" => "server", "value" => $value['tmp_name']));
            return true;
        } else {
            return $this->sTaskLog = json_encode(array("status" => "error", "value" => "errore nel caricamento del file."));
            return false;
        }
    }
}

//Task che restituisce lo stato corrente della piattaforma (utente loggato e sidebar)
class AA_SystemTask_GetAppStatus extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("GetAppStatus", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $this->GetName());

        $this->sTaskLog = "<status id='status'>0</status><content id='content' type='xml'>";

        //dati utente
        $this->sTaskLog .= $this->oUser->toXml();

        //registered mods
        $platform = AA_Platform::GetInstance($this->oUser);

        //AA_Log::Log(__METHOD__." - ".print_r($_REQUEST,true),100);

        if ($platform->IsValid()) {
            $sideBarContent = array();
            $mods = $platform->GetModules();

            foreach ($mods as $curMod) {
                //Modulo da selezionare
                if ($_REQUEST['module'] == $curMod['id_modulo']) {
                    //AA_Log::Log(__METHOD__." - Seleziono il modulo: ".$_REQUEST['module'],100);
                    $itemSelected = $curMod['id_sidebar'];
                }

                $modules[] = array("id" => $curMod['id_modulo'], "remote_folder" => AA_Const::AA_PUBLIC_MODULES_PATH . DIRECTORY_SEPARATOR . $curMod['id_sidebar'] . "_" . $curMod['id'], "icon" => $curMod['icon'], "name" => $curMod['tooltip']);

                $sideBarContent[] = array("id" => $curMod['id_sidebar'], "icon" => $curMod['icon'], "value" => $curMod['name'], "tooltip" => $curMod['tooltip'], "module" => $curMod['id_modulo']);
            }

            $this->sTaskLog .= "<sidebar id='sidebar' itemSelected='$itemSelected'>";
            $this->sTaskLog .= json_encode($sideBarContent);

            $this->sTaskLog .= '</sidebar>';

            $this->sTaskLog .= "<sidebar id='sidebar'>";

            //configurazione moduli
            $this->sTaskLog .= "<modules id='modules'>";
            $this->sTaskLog .= json_encode($modules);
            $this->sTaskLog .= "</modules>";
            #------------------------

            $this->sTaskLog .= "</content>";

            return true;
        }
        /*else
        {
            $this->sTaskLog.="<sidebar id='sidebar'>".'[';
        }
            
        //home
        $this->sTaskLog.='{"id": "home", "icon": "mdi mdi-home", "value": "Home", "tooltip" : "Home page", "module": "AA_MODULE_HOME"}';
        
        //Strumenti di amministrazione
        if($this->oUser->IsSuperUser())
        {
            $this->sTaskLog.=',{"id": "admin_tools", "icon": "mdi mdi-security", "value": "Strumenti amministrativi", "tooltip":"Gestione strutture","data":[';
            
            //Gestione strutture
            $this->sTaskLog.='{"id": "gest_struct", "icon":"mdi mdi-family-tree", "value": "Gestione strutture", "tooltip":"Gestione strutture", "module":"AA_MODULE_GEST_STRUCT"}';
            
            //Gestione utenti
            $this->sTaskLog.=',{"id": "gest_utenti", "icon":"mdi mdi-account-box-multiple", "value": "Gestione utenti", "tooltip":"Gestione utenti", "module":"AA_MODULE_GEST_UTENTI"}';
            
            $this->sTaskLog.=']}';
        }
        
        //pubblicazioni-----------
        $this->sTaskLog.=',{"id": "pubblicazioni", "icon": "mdi mdi-certificate", "value": "Pubblicazioni", "tooltip":"Gestione delle pubblicazioni ai sensi del d.lgs.33/2013","data":[';
        
        //art26
        $this->sTaskLog.='{"id": "art26", "icon":"mdi mdi-cash-register", "value": "Contributi (Art. 26,27)", "tooltip":"Contributi, sovvenzioni, vantaggi economici (Art. 26,27)", "module":"AA_MODULE_ART26"}';
       
        //art37
        $this->sTaskLog.=',{"id": "art37", "icon":"mdi mdi-cart-variant", "value": "Bandi di gara e contratti (Art. 37)", "tooltip":"Bandi di gara e contratti (Art. 37)", "module":"AA_MODULE_ART37"}';
        
        $this->sTaskLog.=']}';
        #-------------------------

        //Gestione incarichi
        if($this->oUser->HasFlag(AA_Const::AA_USER_FLAG_INCARICHI) || $this->oUser->HasFlag(AA_Const::AA_USER_FLAG_INCARICHI_TITOLARI || $this->oUser->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN)) $this->sTaskLog.=',{"id": "gest_incarichi", "icon": "mdi mdi-briefcase-variant-outline", "value": "Gestione incarichi", "tooltip": "Gestione incarichi", "module":"AA_MODULE_INCARICHI"}';
        
        //SIMVRC
        if($this->oUser->HasFlag(AA_Const::AA_USER_FLAG_PROCESSI) || $this->oUser->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN) $this->sTaskLog.=',{"id": "simvrc", "icon": "mdi mdi-format-list-checks", "value": "Mappatura processi", "tooltip": "SIMVRC - Sistema Informativo per la mappatura e la valutazione del rischio corruttivo dei processi", "module":"AA_MODULE_SIMVRC"}';
        
        //SINES
        if($this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22) || $this->oUser->HasFlag(AA_Const::AA_USER_FLAG_ART22_ADMIN)) $this->sTaskLog.=',{"id": "sines", "icon": "mdi mdi-office-building", "value": "Sistema Informativo Enti e Società", "tooltip": "SINES - Sistema Informativo Enti e Società", "module":"AA_MODULE_SINES"}';        
        
        $this->sTaskLog.=']</sidebar>';
        
        $this->sTaskLog.="</content>";
        return true;*/
    }
}
#--------------------------------------------

//Classe per la gestione dei feed xml
class AA_XML_FEED
{
    //Identificativo del feed
    protected $id = "AA_GENERIC_XML_FEED";

    //versione
    protected $version = "1.0";

    //licenza
    protected $sLicense = "IODL";

    //url del feed
    protected $sUrl = "";
    public function SetURL($var = "")
    {
        $this->sUrl = $var;
    }
    public function GetURL()
    {
        return $this->sUrl;
    }

    //timestamp
    protected $sTimestamp = "";
    public function Timestamp()
    {
        return $this->sTimestamp;
    }

    //params
    protected $aParams = array();
    public function GetParams()
    {
        return $this->aParams;
    }
    public function SetParams($params = array())
    {
        if (is_array($params)) $this->aParams = $params;
    }

    //content
    protected $sContent = "";
    public function GetContent()
    {
        return $this->sContent;
    }
    public function SetContent($var)
    {
        $this->sContent = $var;
    }

    //Restituisce il feed in formato xml
    public function toXML()
    {
        $return = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $return .= "<aa_xml_feed id='" . $this->id . "' version='" . $this->version . "'><meta>";
        $return .= "<url>" . htmlspecialchars($this->sUrl, ENT_QUOTES) . "</url>";
        $return .= "<timestamp>" . $this->sTimestamp . "</timestamp>";
        $return .= "<license>" . $this->sLicense . "</license>";
        $return .= "<params>";
        foreach ($this->aParams as $key => $value) {
            $return .= "<param id='" . htmlspecialchars($key, ENT_QUOTES) . "'>" . htmlspecialchars($value, ENT_QUOTES) . "</param>";
        }
        $return .= "</params></meta><content>";
        $return .= $this->sContent;
        $return .= "</content></aa_xml_feed>";

        return $return;
    }

    public function __toString()
    {
        return $this->toXML();
    }
}

class AA_XML_FEED_ARCHIVIO extends AA_XML_FEED
{
    public function __construct()
    {
        $this->id = "AA_XML_FEED_ARCHIVIO";
    }
}

class AA_Archivio
{
    //Archivia un oggetto
    static public function Snapshot($date = "", $id_object = 0, $object_type = 0, $content = "", $user = null)
    {
        AA_Log::Log(__METHOD__ . "()");

        //Costruisce il contenuto
        if ($date == "") $date = date("Y-m-d H:i:s");
        if ($user == null) $user = AA_User::GetCurrentUser();
        if (!$user->IsValid() && !$user->isCurrentUser()) {
            AA_Log::Log(__METHOD__ . " - utente non valido.", 100, true, true);
            return false;
        }

        if ($content == "") {
            AA_Log::Log(__METHOD__ . " - contenuto non presente: ", 100, true, true);
            return false;
        }

        $db = new Database();
        $query = "INSERT into archivio set data='" . $date . "', object_type='" . $object_type . "', id_object='" . $id_object . "', content='" . addslashes($content) . "', user='" . addslashes($user->toXml()) . "'";
        if (!$db->Query($query)) {
            AA_Log::Log(__METHOD__ . " - errore nella query: " . htmlspecialchars($query), 100, true, true);
            return false;
        }

        return true;
    }

    //Recupera la rappresentazione di un oggetto dall'archivio
    static public function Resume($date = "", $id_object = 0, $object_type = 0)
    {
        AA_Log::Log(__METHOD__ . "()");

        $objects = AA_Archivio::ResumeMulti($id_object, $object_type, $date, 1);

        if (sizeof($objects) > 0) return array_pop($objects);

        return null;
    }

    //Recupera le prime n rappresentazioni dell'oggetto dall'archivio
    static public function ResumeMulti($id_object = 0, $object_type = 0, $date = "", $num = 1)
    {
        AA_Log::Log(__METHOD__ . "()");

        $return = array();

        if ($date == "") $date = date("Y-m-d H:i:s");
        if ($num <= 0) $num = 1;
        if ($num > 50) $num = 50;
        $db = new Database();
        $query = "SELECT * from archivio WHERE data <= '" . $date . "'";
        if ($id_object > 0) $query .= " AND id_object='" . $id_object . "'";
        if ($object_type > 0) $query .= " AND object_type='" . $object_type . "'";
        $query .= " ORDER by data DESC,id DESC LIMIT " . $num;

        if (!$db->Query($query)) {
            AA_Log::Log(__METHOD__ . " - errore nella query: " . $query, 100, true, true);
            return $return;
        }

        $rs = $db->GetRecordSet();
        if ($rs->GetCount()) {
            do {
                $xml = new AA_XML_FEED_ARCHIVIO();
                $xml->SetContent("<data>" . $rs->Get('content') . $rs->Get('user') . "</data>");
                $return[$rs->Get('id')] = $xml;
            } while ($rs->MoveNext());
        }

        return $return;
    }
}

//Sincronizzazione col db
class AA_DbBind
{
    //bind variabili -> campi db
    protected $aBindings = array();
    public function GetBindings()
    {
        return $this->aBindings;
    }

    //nome tabella
    protected $sTable = "";
    public function SetTable($table = "")
    {
        $this->sTable = $table;
    }
    public function GetTable()
    {
        return $this->sTable;
    }

    //Aggiungi un collegamento
    public function AddBind($nomeVariabile = "", $nomeCampo = "")
    {
        if ($nomeVariabile == "" || $nomeCampo == "" || $nomeCampo == "id") return false;

        $this->aBindings[$nomeVariabile] = $nomeCampo;
        return true;
    }

    //rimuovi un collegamento
    public function DelBind($nomeVariabile = "")
    {
        if (isset($this->aBindings[$nomeVariabile])) $this->aBindings[$nomeVariabile] = "";
        else {
            foreach ($this->aBindings as $key => $value) {
                if ($value == $nomeVariabile) $this->aBindings[$key] = "";
            }
        }
    }
}

//Classe per la gestione del mapping delle variabili per le viste
class AA_ObjectVarMapping
{
    //Oggetto collegato
    private $oObject = null;
    public function GetObject()
    {
        return $this->oObject;
    }
    public function SetObject($object = null)
    {
        if ($object instanceof AA_Object) $this->oObject = $object;
    }

    public function __construct($object = null)
    {
        if ($object instanceof AA_Object) $this->oObject = $object;
    }

    //Aggiunge un mapping alle variabili
    private $aVarMapping = array();
    public function AddVar($var_name = "", $name = "", $type = "", $label = "")
    {
        if ($name == "") $name = $var_name;
        if ($type == "") $type = "text";
        if ($label == "") $label = $name;
        if ($var_name != "") $this->aVarMapping[$var_name] = $name . "|" . $type . "|" . $label;
    }

    //Rimuove un mapping ad una variabile
    public function DelVar($var_name = "")
    {
        if ($var_name != "" && $this->aVarMapping[$var_name] != "") {
            $this->aVarMapping[$var_name] = "";
        }
    }

    //restituisce il nome di una variabile mappata
    public function GetName($var_name = "")
    {
        if ($var_name != "" && $this->aVarMapping[$var_name] != "") {
            $mapping = explode("|", $this->aVarMapping[$var_name]);
            return $mapping[0];
        }

        return $var_name;
    }

    //Restituisce il tipo di una variabile mappata
    public function GetType($var_name = "")
    {
        if ($var_name != "" && $this->aVarMapping[$var_name] != "") {
            $mapping = explode("|", $this->aVarMapping[$var_name]);
            return $mapping[1];
        }

        return "text";
    }

    //restituisce il lable di una variabile mappata
    public function GetLabel($var_name = "")
    {
        if ($var_name != "" && $this->aVarMapping[$var_name] != "") {
            $mapping = explode("|", $this->aVarMapping[$var_name]);
            return $mapping[2];
        }

        return $var_name;
    }
}

#Classe per i template view
class AA_GenericObjectTemplateView extends AA_GenericBoxTemplateView
{
    protected $obj=null;

    //costruttore
    public function __construct($id = "", $parent = null, $obj = null)
    {
        if ($id == "") $id = get_class();

        //error_log(__METHOD__."(".$id.")");

        parent::__construct("", $id, $parent);

        //Imposta l'oggetto
        if ($obj instanceof AA_Object) $this->obj = $obj;
        //else $this->obj=new AA_Object();
    }

    //oggetto collegato
    private $oObject = null;
    public function SetObject($obj = null)
    {
        $this->oObject = null;
        if ($obj instanceof AA_Object) $this->oObject = $obj;
    }
    public function GetObject()
    {
        return $this->oObject;
    }
}

//Classe per la gestione dei form
class AA_GenericFormTemplateView extends AA_GenericObjectTemplateView
{
    //Dimensione del box label
    private $nLabelFieldBoxSize = "25%";
    public function SetLabelFieldBoxSize($val = "25%")
    {
        $this->nLabelFieldBoxSize = $val;
    }
    public function GetLabelFieldBoxSize()
    {
        return $this->nLabelFieldBoxSize;
    }

    //Dimensione del box field
    private $nFieldBoxSize = "75%";
    public function SetFieldBoxSize($val = "75%")
    {
        $this->nFieldBoxSize = $val;
    }
    public function GetFieldBoxSize()
    {
        return $this->nFieldBoxSize;
    }

    private $oContentBox = null;
    public function __construct($id = "", $parent = null, $obj = null)
    {
        parent::__construct($id, $parent, $obj);

        $this->sInnerClass = get_class();

        $form = new AA_XML_Form_Element($id . "_Form", $this);
        $form->SetClass("form-data");
        $this->oContentBox = new AA_XML_Div_Element($id . "_Form_ContentBox", $form);
        $this->oContentBox->SetStyle("display:flex; flex-direction: column; justify-content: space-between; width:99%");
    }

    //Aggiunge un campo al form
    public function AddField($field_label = "Campo generico", $field_id = "id_campo", $field_type = "text", $field_value = "", $values = null, $field_notes = "", $field_style = "", $field_class = "")
    {
        $row = new AA_XML_Div_Element($this->oContentBox->GetId() . "_Row_" . $field_id, $this->oContentBox);
        $row->SetStyle("display: flex; justify-content: space-between; margin-bottom: 1em; width:99%");

        $label = new AA_XML_Div_Element($this->oContentBox->GetId() . "_Label_" . $field_id, $row);
        $label->SetStyle("width: " . $this->GetLabelFieldBoxSize());
        $label->SetText($field_label);

        $field_box = new AA_XML_Div_Element($this->oContentBox->GetId() . "_FieldBox_" . $field_id, $row);
        $field_box->SetStyle("width: " . $this->GetFieldBoxSize());
        switch ($field_type) {
            default:
            case "text":
                $field = new AA_XML_Input_Element($field_id, $field_box);
                $field->SetAttribute("name", $field_id);
                if ($field_style != "") $field->SetStyle($field_style);
                if ($field_value != "") $field->SetAttribute("value", $field_value);
                $field->SetClass("text ui-widget-content ui-corner-all " . $field_class);
                break;

            case "file":
                $field = new AA_XML_Input_Element($field_id, $field_box);
                $field->SetAttribute("name", $field_id);
                $field->SetAttribute("type", "file");
                if ($field_style != "") $field->SetStyle($field_style);
                if ($field_value != "") $field->SetAttribute("value", $field_value);
                $field->SetClass("file ui-widget-content ui-corner-all " . $field_class);
                break;

            case "select":
                $field = new AA_XML_Select_Element($field_id, $field_box);
                if ($field_style != "") $field->SetStyle($field_style);
                $field->SetAttribute("name", $field_id);
                $field->SetClass("select ui-widget-content ui-corner-all " . $field_class);
                if (is_array($values)) {
                    foreach ($values as $key => $curValue) {
                        $option = new AA_XML_Option_Element($field_id . "_Option_" . $key, $field);
                        $option->SetAttribute("value", $key);
                        $option->SetText($curValue);
                        if ($key == $field_value) $option->SetAttribute("selected", "true");
                    }
                }
                break;

            case "textarea":
            case "text-area":
                $field = new AA_XML_Textarea_Element($field_id, $field_box);
                $field->SetAttribute("name", $field_id);
                if ($field_style != "") $field->SetStyle($field_style);
                if ($field_value != "") $field->SetText($field_value);
                $field->SetClass("textarea ui-widget-content ui-corner-all " . $field_class);
                break;

            case "checkbox":
                $field = new AA_XML_Input_Element($field_id, $field_box);
                $field->SetAttribute("name", $field_id);
                if ($field_style != "") $field->SetStyle($field_style);
                if ($field_value != "") $field->SetAttribute("value", $field_value);
                if ($values == true) $field->SetAttribute("checked", "true");
                $field->SetAttribute("type", "checkbox");
                $field->SetClass("checkbox ui-widget-content ui-corner-all " . $field_class);
                break;
        }
        if ($field_notes != "") {
            $field_box->SetText("<div style='font-size: smaller'>" . $field_notes . "</div>", false);
        }
    }

    //Aggiunge un campo di testo semplice
    public function AddTextInput($field_label = "Campo generico", $field_id = "id_campo", $field_value = "", $field_notes = "", $field_style = "", $field_class = "")
    {
        if ($field_style == "") $field_style = "width: 99%";
        return $this->AddField($field_label, $field_id, "text", $field_value, null, $field_notes, $field_style, $field_class);
    }

    //Aggiunge un campo check box
    public function AddCheckBoxInput($field_label = "Campo generico", $field_id = "id_campo", $field_value = "", $isChecked = false, $field_notes = "", $field_style = "", $field_class = "")
    {
        return $this->AddField($field_label, $field_id, "checkbox", $field_value, $isChecked, $field_notes, $field_style, $field_class);
    }

    //Aggiunge un campo di textarea semplice
    public function AddTextareaInput($field_label = "Campo generico", $field_id = "id_campo", $field_value = "", $field_notes = "", $field_style = "", $field_class = "")
    {
        if ($field_style == "") $field_style = "width: 99%";
        return $this->AddField($field_label, $field_id, "textarea", $field_value, null, $field_notes, $field_style, $field_class);
    }

    //Aggiunge un campo per l'input di un file
    public function AddFileInput($field_label = "Documento", $field_id = "file-upload", $field_value = "", $field_notes = "<br>Selezionare solo file pdf firmati digitalmente in modalità PADES e inferiori a 2 Mbyte.", $field_style = "", $field_class = "")
    {
        if ($field_style == "") $field_style = "width: 99%";
        return $this->AddField($field_label, $field_id, "file", $field_value, null, $field_notes, $field_style, $field_class);
    }

    //Aggiunge un campo di combo a discesa
    public function AddSelectInput($field_label = "Campo generico", $field_id = "id_campo", $field_value = "", $field_values = null, $field_notes = "", $field_style = "", $field_class = "")
    {
        return $this->AddField($field_label, $field_id, "select", $field_value, $field_values, $field_notes, $field_style, $field_class);
    }

    //Aggiunge un campo di date picker
    public function AddDateInput($field_label = "Campo generico", $field_id = "id_campo", $field_value = "", $field_notes = "", $field_style = "", $field_class = "")
    {
        if ($field_style == "") $field_style = "width: 25%";
        $field_class .= " AA_DatePicker";
        return $this->AddField($field_label, $field_id, "text", $field_value, null, $field_notes, $field_style, $field_class);
    }

    //Aggiunge un campo nascosto
    public function AddHiddenField($field_id = "id_campo", $field_value = "")
    {
        //AA_Log::Log(__METHOD__." - parent: ".$this->oContentBox->GetParent(),100,false,true);

        $input = new AA_XML_Input_Element($field_id, $this->oContentBox->GetParent());
        $input->SetAttribute("type", "hidden");
        $input->SetAttribute("value", $field_value);
        $input->SetAttribute("name", $field_id);
    }
}

//Classe generic template list
class AA_GenericTableTemplateView extends AA_GenericObjectTemplateView
{
    //righe
    protected $aRows = array();
    public function GetRowsCount()
    {
        return sizeof($this->aRows);
    }
    public function GetRows()
    {
        return $this->aRows;
    }
    public function GetRow($RowNumber = 0)
    {
        if (isset($this->aRows[$RowNumber])) return $this->aRows[$RowNumber];

        return null;
    }
    public function AddRow()
    {
        $row = count($this->aRows);
        $this->aRows[$row] = new AA_GenericTableRowTemplateView("AA_GenericTableRowTemplateView_" . $row, $this);
        $this->aRows[$row]->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; width:100%; border-bottom: 1px solid black");
        $this->aRows[$row]->SetClass("AA_GenericTableRowTemplateView_evidenzia");

        return $this->aRows[$row];
    }

    //Colonne
    private $aColsSizes = array();
    private $aColsLabels = array();
    private $nCols = 1;
    public function SetColLabels($labels)
    {
        if (is_array($labels)) $this->aColsLabels = $labels;
    }
    public function GetColSize($nCol = 0)
    {
        if (isset($this->aColsSizes[$nCol])) return $this->aColsSizes[$nCol];
        return 0;
    }

    public function SetColSize($nCol = 0, $size = 1)
    {
        if (isset($this->aColsSizes[$nCol])) $this->aColsSizes[$nCol] = $size;
    }

    //Imposta l'header della tabella
    public function SetColSizes($sizes = null)
    {
        if (is_array($sizes)) {
            $i = 0;
            $this->nCols = count($sizes);
            $this->aColsSizes = array();
            foreach ($sizes as $nCol => $size) {
                if ($i < $this->nCols) $this->aColsSizes[$i] = $size;
                $i++;
            }
        }

        //AA_Log::Log(__METHOD__." - numero di colonne. (".$this->nCols.")",100,false,true);
    }

    //Imposta il testo dell'header delle colonne
    public function SetHeaderLabels($labels = null)
    {
        if (is_array($labels)) {
            $i = 0;
            foreach ($labels as $curLabel) {
                if ($i < $this->nCols) $this->aColsLabels[$i] = $curLabel;
                $this->SetCellText(0, $i, $curLabel, "center");
                $i++;
            }
        }
    }

    //Celle
    protected $aCells = array();
    public function GetCell($row = 0, $col = 0)
    {
        if ($col < count($this->aColsSizes)) {
            if (isset($this->aCells[$row . "_" . $col])) return $this->aCells[$row . "_" . $col];
            else {
                if (isset($this->aRows[$row])) {
                    $this->aCells[$row . "_" . $col] = new AA_GenericTableCellTemplateView("AA_GenericTableCellTemplateView_" . $row . "_" . $col, $this->aRows[$row]);
                    return $this->aCells[$row . "_" . $col];
                } else {
                    $this->aRows[$row] = new AA_GenericTableRowTemplateView("AA_GenericTableRowTemplateView_" . $row, $this);
                    $this->aRows[$row]->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; width:100%; border-bottom: 1px solid $this->defaultBorderColor");
                    $this->aRows[$row]->SetClass("AA_GenericTableRowTemplateView_evidenzia");

                    $this->aCells[$row . "_" . $col] = new AA_GenericTableCellTemplateView("AA_GenericTableCellTemplateView_" . $row . "_" . $col, $this->aRows[$row]);

                    return $this->aCells[$row . "_" . $col];
                }
            }
        }

        AA_Log::Log(__METHOD__ . " - indice di colonna oltre il massimo impostato. (" . $col . ")", 100, false, true);

        return null;
    }

    //colore di default del bordo
    protected $defaultBorderColor = "black";

    //colore di sfondo della riga di intestazione
    protected $h_bgcolor = "rgb(215, 215, 215)";

    public function __construct($id = "", $parent = null, $obj = null, $props = null)
    {
        if ($id == "") $id = get_class();

        parent::__construct($id, $parent, $obj);

        $this->SetStyle("display:flex; flex-direction: column; justify-content: space-between");

        //colore di default del bordo
        $this->defaultBorderColor = "black";

        //colore di sfondo della riga di intestazione
        $this->h_bgcolor = "rgb(215, 215, 215)";

        //proprietà
        if (is_array($props)) {
            //Imposta lo stile
            if (isset($props["style"])) {
                $this->SetStyle($props["style"], true);
            }
            if (isset($props["col_sizes"])) $this->SetColSizes($props["col_sizes"]);
            if (isset($props["col_label"])) $this->SetColLabels($props["col_label"]);
            if (isset($props["align-items"])) $this->SetStyle("align-items: " . $props["align-items"], true);
            else $this->SetStyle("align-items: center", true);
            if (isset($props["width"])) $this->SetStyle("width: " . $props["width"], true);
            else $this->SetStyle("width: 100%", true);

            if (isset($props["default-border-color"])) $this->defaultBorderColor = $props["default-border-color"];

            //bordo
            if (isset($props["border"])) {
                $this->SetStyle("border: " . $props["border"], true);
                $this->bBorder = true;
            }

            //titolo
            if (isset($props["title"])) $this->SetText("<div style='width:100%; font-size: 16px; font-weight: bold; border-bottom: 1px solid $this->defaultBorderColor'>" . $props["title"] . "</div>", true);

            //evidenzia le righe
            if (isset($props["evidentiate-rows"])) $this->bEvidenziateRows = true;

            //colore di sfondo dell'intestazione
            if (isset($props['h_bgcolor'])) $this->h_bgcolor = $props['h_bgcolor'];
        } else {
            $this->SetStyle("align-items: center; width: 100%", true);
        }

        //Riga di intestazione
        $this->aRows[0] = new AA_XML_Div_Element($id . "_header", $this);
        $this->aRows[0]->SetStyle("display:flex; flex-direction: row; justify-content: space-between; align-items: center; width: 100%; font-weight: bold; border-bottom: 1px solid $this->defaultBorderColor; background-color: $this->h_bgcolor;");
    }

    //Imposta il contenuto di una cella
    public function SetCellText($row = 1, $col = 1, $content = "", $alignment = "left", $color = "", $grassetto = "")
    {
        $cell = $this->GetCell($row, $col);
        if ($cell instanceof AA_XML_Div_Element) {
            $cell->SetText($content);
            if (strpos("text-align:", $this->GetStyle()) !== false) $cell->SetStyle(preg_replace("/(text-align:\ [center|left|right];)+/", "text-align: $alignment;", $cell->GetStyle()));
            else $cell->SetStyle("text-align: $alignment;", true);

            if ($color != "") {
                if (strpos("color:", $this->GetStyle()) !== false) $cell->SetStyle(preg_replace("/(color:\ [.*];)+/", "color: $color;", $cell->GetStyle()));
                else $cell->SetStyle("color: $color", true);
            }

            if ($grassetto != "") {
                if (strpos("font-weight:", $this->GetStyle()) !== false) $cell->SetStyle(preg_replace("/(font-weight:\ [.*];)+/", "font-weight: bold;", $cell->GetStyle()));
                else $cell->SetStyle("font-weight: bold", true);
            }

            return true;
        }

        return false;
    }

    //Restituisce il testo di una cella
    public function GetCellText($row = 1, $col = 1)
    {
        $cell = $this->GetCell($row, $col);
        if ($cell instanceof AA_XML_Div_Element) {
            return $cell->GetText();
        }

        return "";
    }

    //Imposta il contenuto da renderizzare prima del rendering dei figli
    private $oHeader = null;
    public function SetHeader($header = null)
    {
        if ($header instanceof AA_XML_Element_Generic) {
            if ($this->oHeader instanceof AA_XML_Element_Generic) $this->oHeader->SetParent(null);
            $this->oHeader = $header;
            $this->oHeader->SetParent($this);
        } else {
            $this->oHeader = new AA_GenericBoxTemplateView($header, get_class() . "_" . $this->GetId() . "_header", $this);
        }
    }
    public function GetHeader()
    {
        return $this->oHeader;
    }

    //Imposta il contenuto dell'header
    public function SetHeaderContent($val = "", $bConvertEntities = false)
    {
        if ($this->oHeader instanceof AA_XML_Element_Generic) {
            if ($bConvertEntities) $this->oHeader->SetText(htmlentities($val));
            else $this->oHeader->SetText($val);
        }
    }

    //Imposta il contenuto da renderizzare il rendering dei figli
    private $oFooter = null;
    public function SetFooter($footer = null)
    {
        if ($footer instanceof AA_XML_Element_Generic) {
            if ($this->oFooter instanceof AA_XML_Element_Generic) $this->oFooter->SetParent(null);
            $this->oFooter = $footer;
            $this->oFooter->SetParent($this);
        } else {
            $this->oFooter = new AA_GenericBoxTemplateView($footer, get_class() . "_" . $this->GetId() . "_footer", $this);
        }
    }
    public function GetFooter()
    {
        return $this->oFooter;
    }

    //Imposta il contenuto del footer
    public function SetFooterContent($val = "", $bConvertEntities = false)
    {
        if ($this->oFooter instanceof AA_XML_Element_Generic) {
            if ($bConvertEntities) $this->oFooter->SetText(htmlentities($val));
            else $this->oFooter->SetText($val);
        }
    }

    protected $bEvidenziateRows=false;
    protected $bBorder=false;

    //funzione di normalizzazione (aggiunge le celle mancanti e fa altri controlli di coerenza)
    protected function Normalize()
    {
        //Calcola la dimensione (relativa) totale impostata per le singole colonne
        $totalsize = 0;
        foreach ($this->aColsSizes as $curSize) {
            if ($curSize > 0) $totalsize += $curSize;
        }

        $curIndexRow = 0;

        //Scandisce tutte le righe, aggiunge le celle mancanti, impostando la dimensione effettiva
        foreach ($this->aRows as $row => $curRow) {
            if ($this->bEvidenziateRows && $curIndexRow > 0) {
                if (!($curIndexRow % 2)) $bgColor = "background-color: #f5f5f5;";
                else $bgColor = "";
                $curRow->SetStyle($bgColor, true);
            }
            $curIndexRow++;

            //Rimuove il bordo dell'ultima riga se la tabella ha il bordo e non c'è testo dopo l'ultima riga
            if ($this->bBorder && $curIndexRow == $this->GetRowsCount() && $this->GetText(false) == "") {
                $curRow->SetStyle(preg_replace("/(border-bottom:\ 1px\ solid\ black)+/", "", $curRow->GetStyle()));
            }

            for ($col = 0; $col < count($this->aColsSizes); $col++) {
                //AA_Log::Log("normalizzo la cella: ".$row." - ".$col, 100,false,true);

                $newSize = round($this->aColsSizes[$col] * 100 / $totalsize);

                if ($this->aCells[$row . "_" . $col] instanceof AA_XML_Element_Generic) {
                    if (strpos("width:", $this->aCells[$row . "_" . $col]->GetStyle()) !== false) $this->aCells[$row . "_" . $col]->SetStyle(preg_replace("/(width:\ [0-9]+)+/", "width: " . $newSize, $this->aCells[$row . "_" . $col]->GetStyle()));
                    else $this->aCells[$row . "_" . $col]->SetStyle("width: " . $newSize . "%", true);
                } else {
                    $this->aCells[$row . "_" . $col] = new AA_GenericTableCellTemplateView("AA_GenericTableCellTemplateView_" . $row . "_" . $col, $curRow);
                    $this->aCells[$row . "_" . $col]->SetStyle("width: " . $newSize . "%");
                }
            }
        }

        //Nascondi l'intestazione se c'è solo una riga
        if (count($this->aRows) == 1) $this->aRows[0]->Hide();
        else $this->aRows[0]->Show();
    }

    //Funzione di renderizzazione
    public function __toString()
    {
        $this->Normalize();
        return parent::__toString();
    }
}

//Classe per la gestione delle righe di una tabella
class AA_GenericTableRowTemplateView extends AA_XML_Div_Element
{
    public function __construct($id = "", $parent = null)
    {
        if ($id == "") $id = get_class();
        parent::__construct($id, $parent);
    }
}

//Classe per la gestione delle celle di una tabella
class AA_GenericTableCellTemplateView extends AA_XML_Div_Element
{
    //Dimensione relativa della cella
    private $nSize = 1;
    public function GetSize()
    {
        return $this->nSize;
    }
    public function SetSize($size = 1)
    {
        if ($size > 1 && $size < 100) $this->nSize = $size;
    }

    public function __construct($id = "", $parent = null, $size = 1)
    {
        if ($id == "") $id = get_class();
        parent::__construct($id, $parent);

        $this->SetSize($size);
    }
}

//Classe per la gestione dei moduli
class AA_GenericModule
{
    //Classe per la gestione degli oggetti del modulo
    const AA_MODULE_OBJECTS_CLASS = "AA_Object_V2";

    //prefisso per la generazione degli identificativi degli oggetti dell'interfaccia
    const AA_UI_PREFIX = "AA_Generic";

    //id ui sezione pubblicate
    const AA_UI_PUBBLICATE_BOX = "Pubblicate_Content_Box";

    //id ui lista sezione pubblicate
    const AA_UI_PUBBLICATE_LISTBOX = "Pubblicate_List_Box";

    //id ui sezione bozze
    const AA_UI_BOZZE_BOX = "Bozze_Content_Box";

    //id ui lista sezione bozze
    const AA_UI_BOZZE_LISTBOX = "Bozze_List_Box";

    //id ui sezione dettaglio
    const AA_UI_DETAIL_BOX = "Detail_Content_Box";

    //id ui sezione revisionate
    const AA_UI_REVISIONATE_BOX = "Revisionate_Content_Box";

    //id ui sezione bozze
    const AA_ID_SECTION_BOZZE = "Bozze";

    //id ui sezione pubblicate
    const AA_ID_SECTION_PUBBLICATE = "Pubblicate";

    //id ui sezione dettaglio
    const AA_ID_SECTION_DETAIL = "Dettaglio";

    //dicitura interfaccia sezione dettaglio
    const AA_UI_SECTION_DETAIL_NAME = "Dettaglio";

    //dicitura interfaccia sezione bozze
    const AA_UI_SECTION_BOZZE_NAME = "Bozze";

    //dicitura interfaccia sezione bozze cestinate
    const AA_UI_SECTION_BOZZE_CESTINATE_NAME = "Bozze cestinate";

    //dicitura interfaccia sezione pubblicate
    const AA_UI_SECTION_PUBBLICATE_NAME = "Pubblicate";

    //dicitura interfaccia sezione pubblicate cestinate
    const AA_UI_SECTION_PUBBLICATE_CESTINATE_NAME = "Pubblicate cestinate";

    //Task per la gestione dei dialoghi standard
    const AA_UI_TASK_PUBBLICATE_FILTER_DLG = "GetGenericPubblicateFilterDlg";
    const AA_UI_TASK_BOZZE_FILTER_DLG = "GetGenericBozzeFilterDlg";
    const AA_UI_TASK_REASSIGN_DLG = "GetGenericReassignDlg";
    const AA_UI_TASK_PUBLISH_DLG = "GetGenericPublishDlg";
    const AA_UI_TASK_TRASH_DLG = "GetGenericTrashDlg";
    const AA_UI_TASK_RESUME_DLG = "GetGenericResumeDlg";
    const AA_UI_TASK_DELETE_DLG = "GetGenericDeleteDlg";
    const AA_UI_TASK_ADDNEW_DLG = "GetGenericAddNewDlg";
    const AA_UI_TASK_MODIFY_DLG = "GetGenericModifyDlg";
    const AA_UI_TASK_SAVEASPDF_DLG = "GetGenericSaveAsPdfDlg";
    const AA_UI_TASK_SAVEASCSV_DLG = "GetGenericSaveAsCsvDlg";
    //------------------------------------

    //---------Task azioni standard-------
    const AA_UI_TASK_TRASH = "GenericTrashObject";
    const AA_UI_TASK_RESUME = "GenericResumeObject";
    const AA_UI_TASK_PUBLISH = "GenericPublishObject";
    const AA_UI_TASK_REASSIGN = "GenericReassignObject";
    const AA_UI_TASK_DELETE = "GenericDeleteObject";
    //------------------------------------

    protected $taskManagerUrl = "system_ops.php";
    public function GetTaskManagerUrl()
    {
        return $this->taskManagerUrl;
    }

    protected $sections = array();
    public function AddSection($section)
    {
        if ($section instanceof AA_GenericModuleSection) $this->sections[$section->GetId()] = $section;
    }
    public function GetSection($section = "AA_GENERIC_MODULE_SECTION")
    {
        if ($this->sections[$section]) return $this->sections[$section];
        else return new AA_GenericModuleSection();
    }

    //Restituisce le sezioni del modulo
    public function GetSections($format = "raw")
    {
        if ($format == "raw") return $this->sections;
        if ($format == "array") {
            $return = array();
            foreach ($this->sections as $curSection) {
                $return[] = $curSection->toArray();
            }
        }

        if ($format == "string") {
            $return = array();
            foreach ($this->sections as $curSection) {
                $return[] = $curSection->toArray();
            }

            $return = json_encode($return);
        }

        if ($format == "base64") {
            $return = array();
            foreach ($this->sections as $curSection) {
                $return[] = $curSection->toArray();
            }

            $return = base64_encode(json_encode($return));
        }

        return $return;
    }

    //Restituisce le flags collegate al modulo
    protected $flags = null;
    public function GetFlags()
    {
        if (!is_array($this->flags)) {
            if ($this->id != "AA_MODULE_GENERIC") {
                $db = new AA_Database();
                $query = "SELECT flags FROM " . AA_Const::AA_DBTABLE_MODULES;
                $query .= " WHERE id_modulo like '" . addslashes($this->id) . "' LIMIT 1";

                if (!$db->query($query)) {
                    AA_Log::Log(__METHOD__ . " - ERRORE - " . $db->GetErrorMessage(), 100);
                    $this->flags = array();
                    return array();
                }

                if ($db->GetAffectedRows() > 0) {
                    foreach ($db->GetResultSet() as $key => $curRow) {
                        $this->flags = json_decode($curRow, true);
                        if (!is_array($this->flags)) {
                            if (json_last_error() > 0) AA_Log::Log(__METHOD__ . " - module flags:" . print_r($this->flags, true) . " - error: " . json_last_error(), 100);
                            $this->flags = array();
                        }
                    }
                } else $this->flags = array();
            } else $this->flags = array();
        }

        return $this->flags;
    }

    //Item templates
    protected $aSectionItemTemplates = null;
    public function SetSectionItemTemplate($section = "", $template = "")
    {
        if ($section == static::AA_ID_SECTION_BOZZE || $section == static::AA_ID_SECTION_DETAIL || $section == static::AA_ID_SECTION_PUBBLICATE) {
            if (!is_array($this->aSectionItemTemplates)) {
                $this->aSectionItemTemplates = array();
            }

            $this->aSectionItemTemplates[$section] = $template;
        }
    }
    public function GetSectionItemTemplate($section = "")
    {
        if (is_array($this->aSectionItemTemplates)) return $this->aSectionItemTemplates[$section];
        else return "";
    }

    //Task sections
    public function Task_GetSections($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";
        $sTaskLog .= $this->GetSections("base64");
        $sTaskLog .= "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Task layout
    public function Task_GetLayout($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json'>";
        $content = $this->TemplateLayout();
        $sTaskLog .= $content;
        $sTaskLog .= "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Restituisce la configurazione sulla sidebar
    public function GetSideBarConfig($format = "raw")
    {

        $conf = array();
        $conf['id'] = $this->sSideBarId;
        $conf['icon'] = $this->sSideBarIcon;
        $conf['value'] = $this->sSideBarName;
        $conf['tooltip'] = $this->sSideBarTooltip;
        $conf['module'] = $this->id;

        if ($format == "string" || $format == "json") return json_encode($conf);
        if ($format == "base64") return base64_encode(json_encode($conf));

        return $conf;
    }

    //sidebar id
    protected $sSideBarId = "";
    public function GetSideBarId()
    {
        return $this->sSideBarId;
    }
    public function SetSideBarId($var = "")
    {
        $this->sSideBarId = $var;
    }

    //sidebar icon
    protected $sSideBarIcon = "mdi mdi-home";
    public function GetSideBarIcon()
    {
        return $this->sSideBarIcon;
    }
    public function SetSideBarIcon($var = "")
    {
        $this->sSideBarIcon = $var;
    }

    //sidebar name
    protected $sSideBarName = "";
    public function GetSideBarName()
    {
        return $this->sSideBarName;
    }
    public function SetSideBarName($var = "")
    {
        $this->sSideBarName = $var;
    }

    //sidebar tooltip
    protected $sSideBarTooltip = "";
    public function GetSideBarTooltip()
    {
        return $this->sSideBarTooltip;
    }
    public function SetSideBarTooltip($var = "")
    {
        $this->sSideBarTooltip = $var;
    }

    protected $oUser = null;
    public function GetUser()
    {
        return $this->oUser;
    }

    public function __construct($user = null, $bDefaultSections = true)
    {

        if (!($user instanceof AA_User) || !$user->isCurrentUser()) $user = AA_User::GetCurrentUser();

        $this->oUser = $user;

        //Task manager url
        $platform = AA_Platform::GetInstance($user);
        $this->taskManagerUrl = $platform->GetModuleTaskManagerURL($this->id);

        //Registrazione dei task-------------------
        $taskManager = $this->GetTaskManager();

        $taskManager->RegisterTask("GetSections");
        $taskManager->RegisterTask("GetLayout");
        $taskManager->RegisterTask("GetActionMenu");
        $taskManager->RegisterTask("GetNavbarContent");
        $taskManager->RegisterTask("GetSectionContent");
        $taskManager->RegisterTask("GetObjectContent");
        $taskManager->RegisterTask("GetObjectData");
        $taskManager->RegisterTask("PdfExport");

        if ($bDefaultSections) {
            #Sezioni default

            //Schede pubblicate
            $navbarTemplate = array($this->TemplateNavbar_Bozze(1, true)->toArray());
            $section = new AA_GenericModuleSection(static::AA_ID_SECTION_PUBBLICATE, static::AA_UI_SECTION_PUBBLICATE_NAME, true, static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX, $this->GetId(), true, true, false, true);
            $section->SetNavbarTemplate($navbarTemplate);
            $this->AddSection($section);

            //Bozze
            $navbarTemplate = $this->TemplateNavbar_Pubblicate(1, true)->toArray();
            $section = new AA_GenericModuleSection(static::AA_ID_SECTION_BOZZE, static::AA_UI_SECTION_BOZZE_NAME, true, static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX, $this->GetId(), false, true, false, true);
            $section->SetNavbarTemplate($navbarTemplate);
            $this->AddSection($section);

            //dettaglio
            $navbarTemplate = $this->TemplateNavbar_Back(1, true)->toArray();
            $section = new AA_GenericModuleSection(static::AA_ID_SECTION_DETAIL, static::AA_UI_SECTION_DETAIL_NAME, false, static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX, $this->GetId(), false, true, true, true);
            $section->SetNavbarTemplate($navbarTemplate);
            $this->AddSection($section);
            #-------------------------------------------
        }

        //SectionItemsTemplate
        //$sectionTemplate="";
        //$this->SetSectionItemTemplate(static::AA_ID_SECTION_BOZZE,$sectionTemplate);
        //$this->SetSectionItemTemplate(static::AA_ID_SECTION_PUBBLICATE,$sectionTemplate);
        $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL, array(array("id" => static::AA_UI_PREFIX . "_" . static::AA_ID_SECTION_DETAIL . "_Generale_Tab", "value" => "Generale", "tooltip" => "Dati generali", "template" => "TemplateGenericDettaglio_Generale_Tab")));

        return;
    }

    protected $id = "AA_MODULE_GENERIC";
    public function GetId()
    {
        return $this->id;
    }

    //Imposta l'identificativo
    protected function SetId($newId = "")
    {
        if ($newId != "") $this->id = $newId;
    }

    protected static $oTaskManager = null;

    //Restituisce il task manager del modulo
    public function GetTaskManager()
    {
        if (self::$oTaskManager == null) {
            AA_Log::Log(__METHOD__ . " - istanzio il task manager.");
            self::$oTaskManager = new AA_GenericModuleTaskManager($this, $this->GetUser());
        }

        return self::$oTaskManager;
    }

    //Restituisce la lista delle schede pubblicate 
    protected function GetDataGenericSectionPubblicate_List($params = array(), $customFilterFunction = null, $customTemplateDataFunction = null)
    {
        $templateData = array();

        $parametri = array("status" => AA_Const::AA_STATUS_PUBBLICATA);
        if ($params['cestinate'] == 1) {
            $parametri['status'] |= AA_Const::AA_STATUS_CESTINATA;
        }

        if ($params['revisionate'] == 1) {
            $parametri['status'] |= AA_Const::AA_STATUS_REVISIONATA;
        }

        if ($params['page'] > 0) $parametri['from'] = ($params['page'] - 1) * $params['count'];
        if ($params['id_assessorato'] != "") $parametri['id_assessorato'] = $params['id_assessorato'];
        if ($params['id_direzione'] != "") $parametri['id_direzione'] = $params['id_direzione'];
        if ($params['id_servizio'] != "") $parametri['id_servizio'] = $params['id_servizio'];
        if ($params['id'] != "") $parametri['id'] = $params['id'];
        if ($params['nome'] != "") $parametri['nome'] = $params['nome'];

        //Richiama la funzione custom per la personalizzazione dei parametri
        if (function_exists($customFilterFunction)) $parametri = array_merge($parametri, $customFilterFunction($params));
        if (method_exists($this, $customFilterFunction)) $parametri = array_merge($parametri, $this->$customFilterFunction($params));

        //Richiama la classe di gestione degli oggetti gestiti dal modulo
        $objectClass = static::AA_MODULE_OBJECTS_CLASS;
        if (class_exists($objectClass)) {
            if (method_exists($objectClass, "Search")) $data = $objectClass::Search($parametri, $this->oUser);
            else {
                AA_Log::Log(__METHOD__ . " Errore: Funzione di ricerca non definita ($objectClass::Search)", 100);
                $data[1] = array();
                $data[0] = 0;
            }
        } else {
            AA_Log::Log(__METHOD__ . " Errore: Classe di gestione dei moduli non definita ($objectClass)", 100);
            $data[1] = array();
            $data[0] = 0;
        }

        foreach ($data[1] as $id => $object) {
            $struct = $object->GetStruct();
            $userCaps = $object->GetUserCaps($this->oUser);
            $struttura_gest = $struct->GetAssessorato();
            if ($struct->GetDirezione(true) > 0) $struttura_gest .= " -> " . $struct->GetDirezione();
            if ($struct->GetServizio(true) > 0) $struttura_gest .= " -> " . $struct->GetServizio();

            #Stato
            if ($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) $status = "bozza";
            if ($object->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $status = "pubblicata";
            if ($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $status .= " revisionata";
            if ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $status .= " cestinata";

            #Dettagli
            if (($userCaps & AA_Const::AA_PERMS_PUBLISH) > 0 && $object->GetAggiornamento() != "") {
                //Aggiornamento
                $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento(true) . "</span>&nbsp;";

                //utente e log
                $lastLog = $object->GetLog()->GetLastLog();
                $details .= "<span class='AA_Label AA_Label_LightBlue' title=\"Nome dell'utente che ha compiuto l'ultima azione - Fai click per visualizzare il log delle azioni\"><span class='mdi mdi-account' onClick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetLogDlg', taskManager: AA_MainApp.taskManager,'params': {id: " . $object->GetId() . "}},'" . $this->GetId() . "');\">" . $lastLog['user'] . "</span>&nbsp;";

                //id
                $details .= "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
            } else {
                if ($object->GetAggiornamento() != "") $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento(true) . "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
            }

            if (($userCaps & AA_Const::AA_PERMS_WRITE) == 0) $details .= "&nbsp;<span class='AA_Label AA_Label_LightBlue' title=\" L'utente corrente non può apportare modifiche all'organismo\"><span class='mdi mdi-pencil-off'></span>&nbsp; sola lettura</span>";

            $data = array(
                "id" => $object->GetId(),
                "tags" => "",
                "aggiornamento" => $object->GetAggiornamento(),
                "denominazione" => $object->GetName(),
                "pretitolo" => "",
                "sottotitolo" => $struttura_gest,
                "stato" => $status,
                "dettagli" => $details,
                "module_id" => $this->GetId()
            );

            if (method_exists($this, $customTemplateDataFunction)) {
                $templateData[] = $this->$customTemplateDataFunction($data, $object);
            } else if (function_exists($customTemplateDataFunction)) {
                $templateData[] = $customTemplateDataFunction($data, $object);
            } else {
                $templateData[] = $data;
            }
        }

        return array($data[0], $templateData);
    }

    //Restituisce la lista delle schede pubblicate (dati - da specializzare)
    public function GetDataSectionPubblicate_List($params = array())
    {
        return $this->GetDataGenericSectionPubblicate_List($params);
    }

    //Layout del modulo
    protected function TemplateGenericLayout()
    {
        $template = new AA_JSON_Template_Multiview(static::AA_UI_PREFIX . "_module_layout", array("type" => "clean", "fitBiggest" => "true"));
        foreach ($this->GetSections() as $curSection) {
            $template->addCell(new AA_JSON_Template_Template($curSection->GetViewId(), array("name" => $curSection->GetName(), "type" => "clean", "template" => "", "initialized" => false, "refreshed" => false)));
        }

        return $template;
    }

    //Layout del modulo (da specializzare)
    public function TemplateLayout()
    {
        return $this->TemplateGenericLayout();
    }

    //Template generic section placeholder
    protected function TemplateGenericSection_Placeholder()
    {
        $content = new AA_JSON_Template_Template(
            static::AA_UI_PREFIX . "_Placeholder_Content",
            array(
                "type" => "clean",
                "template" => "placeholder"
            )
        );

        return $content;
    }

    //Template section placeholder
    public function TemplateSection_Placeholder()
    {
        return $this->TemplateGenericSection_Placeholder();
    }

    //Task Generic Action Menù
    protected function Task_GetGenericActionMenu($task)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $content = "";

        switch ($_REQUEST['section']) {
            case static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX:
                $content = $this->TemplateActionMenu_Bozze();
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX:
                $content = $this->TemplateActionMenu_Pubblicate();
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX:
                $content = $this->TemplateActionMenu_Detail();
                break;
            default:
                $content = new AA_JSON_Template_Generic();
                break;
        }

        if ($content != "") $sTaskLog .= $content->toBase64();

        $sTaskLog .= "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Task action menù (da specializzare)
    public function Task_GetActionMenu($task)
    {
        return $this->Task_GetGenericActionMenu($task);
    }

    //Template bozze context menu
    protected function TemplateGenericActionMenu_Bozze()
    {

        $menu = new AA_JSON_Template_Generic(
            "AA_ActionMenuBozze",
            array(
                "view" => "contextmenu",
                "data" => array(array(
                    "id" => "refresh_bozze",
                    "value" => "Aggiorna",
                    "icon" => "mdi mdi-reload",
                    "module_id" => $this->GetId(),
                    "handler" => "refreshUiObject",
                    "handler_params" => array(static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX, true)
                ))
            )
        );

        return $menu;
    }

    //Task NavBarContent
    protected function Task_GetGenericNavbarContent($task, $params = array())
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $content = array();
        switch ($params['section']) {
            case static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX:
                $content[] = $this->TemplateNavbar_Pubblicate(1, true)->toArray();
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX:
                $content[] = $this->TemplateNavbar_Bozze(1, true)->toArray();
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX:
                $content[] = $this->TemplateNavbar_Back(1, true)->toArray();
                break;
            default:
                $content[] = $this->TemplateNavbar_Pubblicate(1, true)->toArray();
        }

        $spacer = new AA_JSON_Template_Generic("navbar_spacer");
        $content[] = $spacer->toArray();

        $sTaskLog .= base64_encode(json_encode($content)) . "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Task NavBarContent
    public function Task_GetNavbarContent($task)
    {
        return $this->Task_GetGenericNavbarContent($task, $_REQUEST);
    }

    //Task Generico di aggiunta elemento
    public function Task_GenericAddNew($task, $params = array(), $bSaveData = true)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        if (class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            if (method_exists(static::AA_MODULE_OBJECTS_CLASS, "AddNew")) {
                $objectClass = static::AA_MODULE_OBJECTS_CLASS;
                $object = new $objectClass(0, $this->oUser);
                if (!$object->IsValid()) {
                    AA_Log::Log(__METHOD__ . " - ERRORE: L'utente corrente non ha i permessi per aggiungere nuovi elementi di tipo (" . static::AA_MODULE_OBJECTS_CLASS . ")", 100);
                    $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi di tipo (" . static::AA_MODULE_OBJECTS_CLASS . ")");
                    $sTaskLog = "<status id='status'>-1</status><error id='error'>L'utente corrente non ha i permessi per aggiugere nuovi elementi</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                }

                //Popolamento oggetto
                $object->Parse($params);
                $object->SetStruct($this->oUser->GetStruct());

                if (!$objectClass::AddNew($object, $this->oUser, $bSaveData)) {
                    $task->SetError(AA_Log::$lastErrorLog);
                    $sTaskLog = "<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (" . AA_Log::$lastErrorLog . ")</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                }

                $sTaskLog = "<status id='status' id_Rec='" . $object->GetId() . "' action='showDetailView' action_params='" . json_encode(array("id" => $object->GetId())) . "'>0</status><content id='content'>";
                $sTaskLog .= "Elemento aggiunto con successo (identificativo: " . $object->GetId() . ")";
                $sTaskLog .= "</content>";

                $task->SetLog($sTaskLog);

                return true;
            } else {
                AA_Log::Log(__METHOD__ . " - ERRORE: Metodo di aggiunta nuovi elementi (AddNew) non definito nella classe di gestione degli elementi (" . static::AA_MODULE_OBJECTS_CLASS . ")", 100);
                $task->SetError(AA_Log::$lastErrorLog);
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (" . AA_Log::$lastErrorLog . ")</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            AA_Log::Log(__METHOD__ . " - ERRORE: Classe di gestione degli elementi non trovata (" . static::AA_MODULE_OBJECTS_CLASS . ")", 100);
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (" . AA_Log::$lastErrorLog . ")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Task Generic Update object
    public function Task_GenericUpdateObject($task, $params = array(), $bSaveData = true)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;
            $object = new $objectClass($params['id'], $this->oUser);
            if (!$object->isValid()) {
                $task->SetError("Identificativo oggetto non valido: " . $params['id']);
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Identificativo oggetto non valido: " . $params['id'] . "</error>";
                $task->SetLog($sTaskLog);

                return false;
            }

            if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) {
                $task->SetError("L'utente corrente (" . $this->oUser->GetName() . ") non ha i privileggi per modificare l'oggetto: " . $object->GetName());
                $sTaskLog = "<status id='status'>-1</status><error id='error'>L'utente corrente (" . $this->oUser->GetName() . ") non ha i privileggi per modificare l'oggetto: " . $object->GetName() . "</error>";
                $task->SetLog($sTaskLog);

                return false;
            }

            //Aggiorna i dati
            $object->Parse($params);

            //Salva i dati
            if (!$object->Update($this->oUser, $bSaveData)) {
                $task->SetError(AA_Log::$lastErrorLog);
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (" . AA_Log::$lastErrorLog . ")</error>";
                $task->SetLog($sTaskLog);

                return false;
            }

            $sTaskLog = "<status id='status'>0</status><content id='content'>";
            $sTaskLog .= "Dati aggiornati con successo.";
            $sTaskLog .= "</content>";

            $task->SetLog($sTaskLog);

            return true;
        } else {
            AA_Log::Log(__METHOD__ . " - ERRORE: Classe di gestione degli elementi non trovata (" . static::AA_MODULE_OBJECTS_CLASS . ")", 100);
            $task->SetError(AA_Log::$lastErrorLog);
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Errore nel salvataggio dei dati. (" . AA_Log::$lastErrorLog . ")</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Template object trash dlg
    public function Template_GetGenericObjectTrashDlg($params, $saveTask = "TrashObject")
    {
        $id = static::AA_UI_PREFIX . "_TrashDlg";

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>La classe di gestione degli oggetti non è stata trovata.</p>")));
            $wnd->SetWidth(380);
            $wnd->SetHeight(115);

            return $wnd;
        }

        //lista oggetti da cestinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object->GetName();
                }
            }

            //Esiste almeno un organismo che può essere cestinato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $forms_data['ids'] = json_encode(array_keys($ids_final));
                $wnd = new AA_GenericFormDlg($id, "Cestina", $this->id, $forms_data, $forms_data);

                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata = array();
                foreach ($ids_final as $id_org => $desc) {
                    $tabledata[] = array("Denominazione" => $desc);
                }

                if (sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "I seguenti " . sizeof($ids_final) . " elementi verranno cestinati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "Il seguente elemento verrà cestinato, vuoi procedere?")));

                $table = new AA_JSON_Template_Generic($id . "_Table", array(
                    "view" => "datatable",
                    "scrollX" => false,
                    "autoConfig" => true,
                    "select" => false,
                    "data" => $tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask($saveTask);
            } else {
                $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>L'utente corrente non ha i permessi per cestinare gli elementi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }

            return $wnd;
        }
    }

    //Template object trash dlg
    public function Template_GetGenericObjectDeleteDlg($params, $saveTask = "GenericDeleteObject")
    {
        $id = static::AA_UI_PREFIX . "_DeleteDlg";

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>La classe di gestione degli oggetti non è stata trovata.</p>")));
            $wnd->SetWidth(380);
            $wnd->SetHeight(115);

            return $wnd;
        }

        //lista oggetti da cestinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object->GetName();
                }
            }

            //Esiste almeno un organismo che può essere cestinato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $forms_data['ids'] = json_encode(array_keys($ids_final));
                $wnd = new AA_GenericFormDlg($id, "Elimina", $this->id, $forms_data, $forms_data);

                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata = array();
                foreach ($ids_final as $id_org => $desc) {
                    $tabledata[] = array("Denominazione" => $desc);
                }

                if (sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "I seguenti " . sizeof($ids_final) . " elementi verranno eliminati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "Il seguente elemento verrà eliminato, vuoi procedere?")));

                $table = new AA_JSON_Template_Generic($id . "_Table", array(
                    "view" => "datatable",
                    "scrollX" => false,
                    "autoConfig" => true,
                    "select" => false,
                    "data" => $tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask($saveTask);
            } else {
                $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>L'utente corrente non ha i permessi per cestinare gli elementi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }

            return $wnd;
        }
    }

    //Template generic resume dlg
    public function Template_GetGenericResumeObjectDlg($params = array(), $saveTask = "ResumeObject")
    {
        $id = static::AA_UI_PREFIX . "_ResumeDlg";

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>La classe di gestione degli oggetti non è stata trovata.</p>")));
            $wnd->SetWidth(380);
            $wnd->SetHeight(115);

            return $wnd;
        }

        //lista elementi da ripristinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object->GetName();
                }
            }

            //Esiste almeno un organismo che può essere ripristinato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $forms_data['ids'] = json_encode(array_keys($ids_final));

                $wnd = new AA_GenericFormDlg($id, "Ripristina", $this->id, $forms_data, $forms_data);

                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata = array();
                foreach ($ids_final as $id_org => $desc) {
                    $tabledata[] = array("Denominazione" => $desc);
                }

                if (sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "I seguenti " . sizeof($ids_final) . " elementi verranno ripristinati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "Il seguente elemento verrà ripristinato, vuoi procedere?")));

                $table = new AA_JSON_Template_Generic($id . "_Table", array(
                    "view" => "datatable",
                    "scrollX" => false,
                    "autoConfig" => true,
                    "select" => false,
                    "data" => $tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask($saveTask);
            } else {
                $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>L'utente corrente non ha i permessi per ripristinare gli elementi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }

            return $wnd;
        }
    }

    //Task generic resume object
    public function Task_GenericResumeObject($task, $params = array(), $bStandardCheck = true)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $objectClass = static::AA_MODULE_OBJECTS_CLASS;

        //lista elementi da ripristinare
        if ($params['ids']) {
            $ids = json_decode($_REQUEST['ids']);

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object;
                }
            }

            //Esiste almeno un elemento che può essere ripristinato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $count = 0;
                foreach ($ids_final as $id => $object) {

                    if (!$object->Resume($this->oUser, $bStandardCheck)) {
                        $count++;
                        $result_error["$object->GetName()"] = AA_Log::$lastErrorLog;
                    }
                }

                if (sizeof($result_error) > 0) {
                    $id = static::AA_UI_PREFIX . "_ResumeDlg";
                    $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template($id . "_ResumeReport", array("template" => "Sono stati ripristinati " . (sizeof($ids) - sizeof($result_error)) . " elementi.<br>I seguenti non sono stati ripristinati:")));

                    $tabledata = array();
                    foreach ($result_error as $org => $desc) {
                        $tabledata[] = array("Denominazione" => $org, "Errore" => $desc);
                    }
                    $table = new AA_JSON_Template_Generic($id . "_Table", array(
                        "view" => "datatable",
                        "scrollX" => false,
                        "autoConfig" => true,
                        "select" => false,
                        "data" => $tabledata
                    ));
                    $wnd->AddView($table);

                    $sTaskLog = "<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog .= $wnd->toBase64();
                    $sTaskLog .= "</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                } else {
                    $sTaskLog = "<status id='status'>0</status><content id='content'>";
                    $sTaskLog .= "Sono stati ripristinati " . sizeof($ids_final) . " elementi.";
                    $sTaskLog .= "</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            } else {
                $task->SetError("Nella selezione non sono presenti elementi ripristinabili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi ripristinabili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            $task->SetError("Non sono stati selezionati elementi.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Non sono stati selezionati elementi.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Template generic reassign dlg
    public function Template_GetGenericReassignObjectDlg($params = array(), $saveTask = "GenericReassignObject")
    {
        $id = static::AA_UI_PREFIX . "_ReassignDlg";

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>La classe di gestione degli oggetti non è stata trovata.</p>")));
            $wnd->SetWidth(380);
            $wnd->SetHeight(115);

            return $wnd;
        }

        //lista elementi da ripristinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object->GetName();
                }
            }

            //Esiste almeno un organismo che può essere ripristinato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $forms_data['ids'] = json_encode(array_keys($ids_final));

                $wnd = new AA_GenericFormDlg($id, "Riassegna", $this->id, $forms_data, $forms_data);
                $wnd->EnableValidation();

                //Aggiunge il campo per la struttura di riassegnazione
                $wnd->AddStructField(array("targetForm" => $wnd->GetFormId()), array("select" => true), array("required" => true, "bottomLabel" => "*Seleziona la struttura di riassegnazione.", "placeholder" => "Scegli..."));

                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata = array();
                foreach ($ids_final as $id_org => $desc) {
                    $tabledata[] = array("Denominazione" => $desc);
                }

                if (sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "I seguenti " . sizeof($ids_final) . " elementi verranno riassegnati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "Il seguente elemento verrà riassegnato, vuoi procedere?")));

                $table = new AA_JSON_Template_Generic($id . "_Table", array(
                    "view" => "datatable",
                    "scrollX" => false,
                    "autoConfig" => true,
                    "select" => false,
                    "data" => $tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask($saveTask);
            } else {
                $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>L'utente corrente non ha i permessi per ripristinare gli elementi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }

            return $wnd;
        }
    }

    //Task generic reassign object
    public function Task_GenericReassignObject($task, $params = array(), $bStandardCheck = true)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $objectClass = static::AA_MODULE_OBJECTS_CLASS;

        //Verifica che l'utente possa riassegnare alla struttura indicata
        $struct = AA_Struct::GetStruct($params['id_assessorato'], $params['id_direzione'], $params['id_servizio']);
        if (!$struct->isValid()) {
            $task->SetError("Struttura indicata non valida.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Struttura indicata non valida.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        //lista elementi da ripristinare
        if ($params['ids']) {
            $ids = json_decode($_REQUEST['ids']);

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0) {
                    $ids_final[$curId] = $object;
                }
            }

            //Esiste almeno un elemento che può essere riassegnato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $count = 0;
                foreach ($ids_final as $id => $object) {

                    if (!$object->Reassign($struct, $this->oUser, $bStandardCheck)) {
                        $count++;
                        $result_error["$object->GetName()"] = AA_Log::$lastErrorLog;
                    }
                }

                if (sizeof($result_error) > 0) {
                    $id = static::AA_UI_PREFIX . "_ReassignDlg";
                    $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template($id . "_ReassignReport", array("template" => "Sono stati riassegnati " . (sizeof($ids) - sizeof($result_error)) . " elementi.<br>I seguenti non sono stati riassegnati:")));

                    $tabledata = array();
                    foreach ($result_error as $org => $desc) {
                        $tabledata[] = array("Denominazione" => $org, "Errore" => $desc);
                    }
                    $table = new AA_JSON_Template_Generic($id . "_Table", array(
                        "view" => "datatable",
                        "scrollX" => false,
                        "autoConfig" => true,
                        "select" => false,
                        "data" => $tabledata
                    ));
                    $wnd->AddView($table);

                    $sTaskLog = "<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog .= $wnd->toBase64();
                    $sTaskLog .= "</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                } else {
                    $sTaskLog = "<status id='status'>0</status><content id='content'>";
                    $sTaskLog .= "Sono stati riassegnati " . sizeof($ids_final) . " elementi.";
                    $sTaskLog .= "</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            } else {
                $task->SetError("Nella selezione non sono presenti elementi riassegnabili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi riassegnabili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            $task->SetError("Non sono stati selezionati elementi.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Non sono stati selezionati elementi.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Task generic trash object
    public function Task_GenericTrashObject($task, $params = array(), $bStandardCheck = true)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $objectClass = static::AA_MODULE_OBJECTS_CLASS;

        //lista oggetti da cestinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object;
                }
            }

            //Esiste almeno un organismo che può essere cestinato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $count = 0;
                foreach ($ids_final as $id => $object) {

                    if (!$object->Trash($this->oUser, $bStandardCheck, false)) {
                        $count++;
                        $result_error[$object->GetName()] = AA_Log::$lastErrorLog;
                    }
                }

                if (sizeof($result_error) > 0) {
                    $id = static::AA_UI_PREFIX . "_Trash";
                    $wnd = new AA_GenericWindowTemplate(static::AA_UI_PREFIX . "_Trash", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("", array("template" => "Sono stati cestinati " . (sizeof($ids) - sizeof($result_error)) . " elementi.<br>I seguenti non sono stati cestinati:")));

                    $tabledata = array();
                    foreach ($result_error as $org => $desc) {
                        $tabledata[] = array("Denominazione" => $org, "Errore" => $desc);
                    }
                    $table = new AA_JSON_Template_Generic($id . "_Table", array(
                        "view" => "datatable",
                        "scrollX" => false,
                        "autoConfig" => true,
                        "select" => false,
                        "data" => $tabledata
                    ));
                    $wnd->AddView($table);

                    $sTaskLog = "<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog .= $wnd->toBase64();
                    $sTaskLog .= "</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                } else {
                    $sTaskLog = "<status id='status'>0</status><content id='content'>";
                    $sTaskLog .= "SOno stati cestinati " . sizeof($ids_final) . " elementi.";
                    $sTaskLog .= "</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            } else {
                $task->SetError("Nella selezione non sono presenti elementi cestinabili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi cestinabili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            $task->SetError("Non sono stati selezionati elementi.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Non sono stati selezionati elementi.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Task generic trash object
    public function Task_GenericDeleteObject($task, $params = array(), $bStandardCheck = true)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $objectClass = static::AA_MODULE_OBJECTS_CLASS;

        //lista oggetti da cestinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_DELETE) > 0) {
                    $ids_final[$curId] = $object;
                }
            }

            //Esiste almeno un organismo che può essere eliminato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $count = 0;
                foreach ($ids_final as $id => $object) {

                    if (!$object->Delete($this->oUser, $bStandardCheck, false)) {
                        $count++;
                        $result_error[$object->GetName()] = AA_Log::$lastErrorLog;
                    }
                }

                if (sizeof($result_error) > 0) {
                    $id = static::AA_UI_PREFIX . "_Trash";
                    $wnd = new AA_GenericWindowTemplate(static::AA_UI_PREFIX . "_Trash", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("", array("template" => "Sono stati eliminati " . (sizeof($ids) - sizeof($result_error)) . " elementi.<br>I seguenti non sono stati eliminati:")));

                    $tabledata = array();
                    foreach ($result_error as $org => $desc) {
                        $tabledata[] = array("Denominazione" => $org, "Errore" => $desc);
                    }
                    $table = new AA_JSON_Template_Generic($id . "_Table", array(
                        "view" => "datatable",
                        "scrollX" => false,
                        "autoConfig" => true,
                        "select" => false,
                        "data" => $tabledata
                    ));
                    $wnd->AddView($table);

                    $sTaskLog = "<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog .= $wnd->toBase64();
                    $sTaskLog .= "</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                } else {
                    $sTaskLog = "<status id='status' action='goBack' action_params='" . json_encode(array()) . "'>0</status><content id='content'>";
                    $sTaskLog .= "Sono stati eliminati " . sizeof($ids_final) . " elementi.";
                    $sTaskLog .= "</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            } else {
                $task->SetError("Nella selezione non sono presenti elementi eliminabili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi eliminabili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            $task->SetError("Non sono stati selezionati elementi.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Non sono stati selezionati elementi.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Template generic publish dlg
    public function Template_GetGenericPublishObjectDlg($params = array(), $saveTask = "GenericPublishObject")
    {
        $id = static::AA_UI_PREFIX . "_PublishDlg";

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {
            $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
            $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>La classe di gestione degli oggetti non è stata trovata.</p>")));
            $wnd->SetWidth(380);
            $wnd->SetHeight(115);

            return $wnd;
        }

        //lista elementi da ripristinare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);
            $objectClass = static::AA_MODULE_OBJECTS_CLASS;

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_PUBLISH) > 0) {
                    $ids_final[$curId] = $object->GetName();
                }
            }

            //Esiste almeno un organismo che può essere pubblicato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $forms_data['ids'] = json_encode(array_keys($ids_final));

                $wnd = new AA_GenericFormDlg($id, "Pubblica", $this->id, $forms_data, $forms_data);

                //Disattiva il pulsante di reset
                $wnd->EnableResetButton(false);

                //Imposta il nome del pulsante di conferma
                $wnd->SetApplyButtonName("Procedi");

                $tabledata = array();
                foreach ($ids_final as $id_org => $desc) {
                    $tabledata[] = array("Denominazione" => $desc);
                }

                if (sizeof($ids_final) > 1) $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "I seguenti " . sizeof($ids_final) . " elementi verranno pubblicati, vuoi procedere?")));
                else $wnd->AddGenericObject(new AA_JSON_Template_Generic("", array("view" => "label", "label" => "Il seguente elemento verrà pubblicato, vuoi procedere?")));

                $table = new AA_JSON_Template_Generic($id . "_Table", array(
                    "view" => "datatable",
                    "scrollX" => false,
                    "autoConfig" => true,
                    "select" => false,
                    "data" => $tabledata
                ));

                $wnd->AddGenericObject($table);

                $wnd->EnableCloseWndOnSuccessfulSave();
                $wnd->enableRefreshOnSuccessfulSave();
                $wnd->SetSaveTask($saveTask);
            } else {
                $wnd = new AA_GenericWindowTemplate($id, "Avviso", $this->id);
                $wnd->AddView(new AA_JSON_Template_Template("", array("css" => array("text-align" => "center"), "template" => "<p>L'utente corrente non ha i permessi per ripristinare gli elementi selezionati.</p>")));
                $wnd->SetWidth(380);
                $wnd->SetHeight(115);
            }

            return $wnd;
        }
    }

    //Task generic trash object
    public function Task_GenericPublishObject($task, $params = array(), $bStandardCheck = true)
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $objectClass = static::AA_MODULE_OBJECTS_CLASS;

        //lista oggetti da pubblicare
        if ($params['ids']) {
            $ids = json_decode($params['ids']);

            foreach ($ids as $curId) {
                $object = new $objectClass($curId, $this->oUser);
                if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_PUBLISH > 0)) {
                    $ids_final[$curId] = $object;
                }
            }

            //Esiste almeno un organismo che può essere pubblicato dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $count = 0;
                foreach ($ids_final as $id => $object) {

                    if (!$object->Publish($this->oUser, $bStandardCheck, false)) {
                        $count++;
                        $result_error[$object->GetName()] = AA_Log::$lastErrorLog;
                    }
                }

                if (sizeof($result_error) > 0) {
                    $id = static::AA_UI_PREFIX . "_Trash";
                    $wnd = new AA_GenericWindowTemplate(static::AA_UI_PREFIX . "_Publish", "Avviso", $this->id);
                    $wnd->SetWidth("640");
                    $wnd->SetHeight("400");
                    $wnd->AddView(new AA_JSON_Template_Template("", array("template" => "Sono stati pubblicati " . (sizeof($ids) - sizeof($result_error)) . " elementi.<br>I seguenti non sono stati pubblicati:")));

                    $tabledata = array();
                    foreach ($result_error as $org => $desc) {
                        $tabledata[] = array("Denominazione" => $org, "Errore" => $desc);
                    }
                    $table = new AA_JSON_Template_Generic($id . "_Table", array(
                        "view" => "datatable",
                        "scrollX" => false,
                        "autoConfig" => true,
                        "select" => false,
                        "data" => $tabledata
                    ));
                    $wnd->AddView($table);

                    $sTaskLog = "<status id='status'>-1</status><error id='error' type='json' encode='base64'>";
                    $sTaskLog .= $wnd->toBase64();
                    $sTaskLog .= "</error>";
                    $task->SetLog($sTaskLog);

                    return false;
                } else {
                    $sTaskLog = "<status id='status'>0</status><content id='content'>";
                    $sTaskLog .= "SOno stati pubblicati " . sizeof($ids_final) . " elementi.";
                    $sTaskLog .= "</content>";

                    $task->SetLog($sTaskLog);

                    return true;
                }
            } else {
                $task->SetError("Nella selezione non sono presenti elementi pubblicabili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi pubblicabili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            $task->SetError("Non sono stati selezionati elementi.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Non sono stati selezionati elementi.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }
    }

    //Task generic export pdf 
    public function Task_PdfExport($task)
    {
        //AA_Log::Log(__METHOD__."() - task: ".$task->GetName());

        //Verifica della classe degli oggetti
        if (!class_exists(static::AA_MODULE_OBJECTS_CLASS)) {

            $task->SetError("Classe di gestione degli oggetti non definita.");
            $sTaskLog = "<status id='status'>-1</status><error id='error'>Classe di gestione degli oggetti non definita.</error>";
            $task->SetLog($sTaskLog);

            return false;
        }

        $sessVar = AA_SessionVar::Get("SaveAsPdf_ids");
        $sessParams = AA_SessionVar::Get("SaveAsPdf_params");
        $objectClass = static::AA_MODULE_OBJECTS_CLASS;

        //lista elementi da esportare
        if ($sessVar->IsValid() && !isset($_REQUEST['fromParams'])) {
            $ids = $sessVar->GetValue();

            if (is_array($ids)) {
                foreach ($ids as $curId) {
                    $object = new $objectClass($curId, $this->oUser);
                    if ($object->isValid() && ($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_READ) > 0) {
                        $ids_final[$curId] = $object;
                    }
                }
            }

            //Esiste almeno un organismo che può essere letto dall'utente corrente
            if (sizeof($ids_final) > 0) {
                $this->Template_PdfExport($ids_final);
            } else {
                $task->SetError("Nella selezione non sono presenti dati leggibili dall'utente corrente (" . $this->oUser->GetName() . ").");
                $sTaskLog = "<status id='status'>-1</status><error id='error'>Nella selezione non sono presenti elementi leggibili dall'utente corrente (" . $this->oUser->GetName() . ").</error>";
                $task->SetLog($sTaskLog);

                return false;
            }
        } else {
            if ($sessParams->isValid()) {
                $params = (array) $sessParams->GetValue();

                //Verifica della sezione 
                if ($params['section'] == static::AA_ID_SECTION_BOZZE) {
                    $params["status"] = AA_Const::AA_STATUS_BOZZA;
                } else {
                    $params["status"] = AA_Const::AA_STATUS_PUBBLICATA;
                }

                if ($params['cestinate'] == 1) {
                    $params['status'] |= AA_Const::AA_STATUS_CESTINATA;
                }

                if ($objectClass == "AA_Object") $objects = $objectClass::Search($params, false, $this->oUser);
                else $objects = $objectClass::Search($params, $this->oUser);

                if ($objects[0] == 0) {
                    $task->SetError("Non è stata individuata nessa corrispondenza in base ai parametri indicati.");
                    $sTaskLog = "<status id='status'>-1</status><error id='error'>Non è stata individuata nessa corrispondenza in base ai parametri indicati.</error>";
                    $task->SetLog($sTaskLog);
                    return false;
                } else {
                    $this->Template_PdfExport($objects[1]);
                }
            }
        }
    }

    //Funzione di esportazione in pdf (da specializzare)
    public function Template_PdfExport($objects = array())
    {
        return $this->Template_GenericPdfExport($objects);
    }

    //Template pdf export generic
    protected function Template_GenericPdfExport($objects = array(), $bToBrowser = true, $title = "Esportazione in pdf", $pageTemplateFunc = "Template_GenericObjectPdfExport")
    {
        include_once "pdf_lib.php";

        if (!is_array($objects)) return "";
        if (sizeof($objects) == 0) return "";

        //recupero elementi
        //$objectClass="AA_Object_V2";
        //if(class_exists(static::AA_MODULE_OBJECTS_CLASS))
        //{
        //    $objectClass=static::AA_MODULE_OBJECTS_CLASS;
        //}

        //$objects=$objectClass::Search(array("id"=>implode(",",$ids)),false,$this->oUser);
        //$count = sizeof($objects);
        #--------------------------------------------

        //nome file
        $filename = "pdf_export";
        $filename .= "-" . date("YmdHis");
        $doc = new AA_PDF_RAS_TEMPLATE_A4_PORTRAIT($filename);

        $doc->SetDocumentStyle("font-family: sans-serif; font-size: 3mm;");
        $doc->SetPageCorpoStyle("display: flex; flex-direction: column; justify-content: space-between; padding:0;");
        $curRow = 0;
        $rowForPage = 1;
        $lastRow = $rowForPage - 1;
        $curPage = null;
        $curPage_row = "";
        $curNumPage = 0;
        //$columns_width=array("titolare"=>"10%","incarico"=>"8%","atto"=>"10%","struttura"=>"28%","curriculum"=>"10%","art20"=>"12%","altri_incarichi"=>"10%","1-ter"=>"10%","emolumenti"=>"10%");
        //$columns_width=array("dal"=>"10%","al"=>"10%","inconf"=>"10%","incomp"=>"10%","anno"=>"25%","titolare"=>"50%","tipo_incarico"=>"10%","atto_nomina"=>"10%","struttura"=>"40%","curriculum"=>"25%","altri_incarichi"=>"25%","1-ter"=>"25%","emolumenti"=>"10%");
        $rowContentWidth = "width: 99.8%;";

        if ($count > 1) {
            //pagina di intestazione (senza titolo)
            $curPage = $doc->AddPage();
            $curPage->SetCorpoStyle("display: flex; flex-direction: column; justify-content: center; align-items: center; padding:0;");
            $curPage->SetFooterStyle("border-top:.2mm solid black");
            $curPage->ShowPageNumber(false);

            //Intestazione
            $intestazione = "<div style='width: 100%; text-align: center; font-size: 24; font-weight: bold'>$title</div>";
            $intestazione .= "<div style='width: 100%; text-align: center; font-size: x-small; font-weight: normal;margin-top: 3em;'>documento generato il " . date("Y-m-d") . "</div>";

            $curPage->SetContent($intestazione);
            $curNumPage++;

            //pagine indice (50 nominativi per pagina)
            $indiceNumVociPerPagina = 50;
            for ($i = 0; $i < $count / $indiceNumVociPerPagina; $i++) {
                $curPage = $doc->AddPage();
                $curPage->SetCorpoStyle("display: flex; flex-direction: column; padding:0;");
                $curNumPage++;
            }
            #---------------------------------------
        }

        //Imposta il titolo per le pagine successive
        $doc->SetTitle("$title - report generato il " . date("Y-m-d"));

        $indice = array();
        $lastPage = $count / $rowForPage + $curNumPage;

        //Rendering pagine
        foreach ($objects as $id => $curObject) {
            //inizia una nuova pagina (intestazione)
            if ($curRow == $rowForPage) $curRow = 0;
            if ($curRow == 0) {
                $border = "";
                if ($curPage != null) $curPage->SetContent($curPage_row);
                $curPage = $doc->AddPage();
                $curNumPage++;
                //$curPage->SetCorpoStyle("display: flex; flex-direction: column;  justify-content: space-between; padding:0; border: 1px solid black");
                $curPage_row = "";
            }

            $indice[$curObject->GetID()] = $curNumPage . "|" . $curObject->GetName();
            $curPage_row .= "<div id='" . $curObject->GetID() . "' style='display:flex;  flex-direction: column; width:100%; align-items: center; justify-content: space-between; text-align: center; padding: 0mm; min-height: 9mm;'>";

            if (method_exists($this, $pageTemplateFunc)) $template = $this->$pageTemplateFunc("report_object_pdf_" . $curObject->GetId(), null, $curObject, $this->oUser);
            else $template = "";

            //AA_Log::Log($template,100,false,true);

            $curPage_row .= $template;
            $curPage_row .= "</div>";
            $curRow++;
        }
        if ($curPage != null) $curPage->SetContent($curPage_row);
        #-----------------------------------------

        if ($count > 1) {
            //Aggiornamento indice
            $curNumPage = 1;
            $curPage = $doc->GetPage($curNumPage);
            $vociCount = 0;
            $curRow = 0;
            $bgColor = "";
            $curPage_row = "";

            foreach ($indice as $id => $data) {
                if ($curNumPage != (int)($vociCount / $indiceNumVociPerPagina) + 1) {
                    $curPage->SetContent($curPage_row);
                    $curNumPage = (int)($vociCount / $indiceNumVociPerPagina) + 1;
                    $curPage = $doc->GetPage($curNumPage);
                    $curRow = 0;
                    $bgColor = "";
                }

                if ($curPage instanceof AA_PDF_Page) {
                    if ($vociCount % 2 > 0) {
                        $dati = explode("|", $data);
                        $curPage_row .= "<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#" . $id . "'>" . $dati['1'] . "</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#" . $id . "'>pag. " . $dati[0] . "</a></div>";
                        $curPage_row .= "</div>";
                        if ($vociCount == (sizeof($indice) - 1)) $curPage->SetContent($curPage_row);
                        $curRow++;
                    } else {
                        //Intestazione
                        if ($curRow == 0) $curPage_row = "<div style='width:100%;text-align: center; font-size: 18px; font-weight: bold; border-bottom: 1px solid gray; margin-bottom: .5em; margin-top: .3em;'>Indice</div>";

                        if ($curRow % 2) $bgColor = "background-color: #f5f5f5;";
                        else $bgColor = "";
                        $curPage_row .= "<div style='display:flex; " . $rowContentWidth . " align-items: center; justify-content: space-between; text-align: center; padding: .3mm; min-height: 9mm;" . $bgColor . "'>";
                        $dati = explode("|", $data);
                        $curPage_row .= "<div style='width:40%;text-align: left;padding-left: 10mm'><a href='#" . $id . "'>" . $dati['1'] . "</a></div><div style='width:9%;text-align: right;padding-right: 10mm'><a href='#" . $id . "'>pag. " . $dati[0] . "</a></div>";

                        //ultima voce
                        if ($vociCount == (sizeof($indice) - 1)) {
                            $curPage_row .= "<div style='width:40%;text-align: left;padding-left: 10mm'>&nbsp; </div><div style='width:9%;text-align: right;padding-left: 10mm'>&nbsp; </div></div>";
                            $curPage->SetContent($curPage_row);
                        }
                    }
                }

                $vociCount++;
            }
        }

        if ($bToBrowser) $doc->Render();
        else {
            $doc->Render(false);
            return $doc->GetFilePath();
        }
    }

    //Template navbar bozze
    protected function TemplateGenericNavbar_Bozze($level = 1, $last = false, $refresh_view = true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(
            static::AA_UI_PREFIX . "_Navbar_Link_" . static::AA_UI_BOZZE_BOX,
            array(
                "type" => "clean",
                "section_id" => "Bozze",
                "module_id" => $this->GetId(),
                "refresh_view" => $refresh_view,
                "tooltip" => "Fai click per visualizzare le schede in bozza",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" . static::AA_UI_PREFIX . "_Navbar_Link_" . static::AA_UI_BOZZE_BOX . "' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"Bozze\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => "Bozze", "icon" => "mdi mdi-file-document-edit", "class" => $class)
            )
        );
        return $navbar;
    }

    //Task section object content
    protected function Task_GetGenericObjectContent($task, $params = array(), $param = "object")
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        switch ($params[$param]) {
            case "Bozze":
            case static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX:
                $template = $this->TemplateSection_Bozze($params);
                $content = array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX, "content" => $template->toArray());
                break;

            case "Pubblicate":
            case static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX:
                $template = $this->TemplateSection_Pubblicate($params);
                $content = array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX, "content" => $template->toArray());
                break;

            case "Dettaglio":
            case static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX:
                $template = $this->TemplateSection_Detail($params);
                $content = array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX, "content" => $template->toArray());
                break;
            default:
                $content = array(
                    array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX, "content" => $this->TemplateSection_Placeholder()->toArray()),
                    array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_BOX, "content" => $this->TemplateSection_Placeholder()->toArray()),
                    array("id" => static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX, "content" => $this->TemplateSection_Placeholder()->toArray())
                );
        }

        //Codifica il contenuto in base64
        $sTaskLog .= base64_encode(json_encode($content)) . "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Task object content (da specializzare)
    public function Task_GetObjectContent($task)
    {
        return $this->Task_GetGenericObjectContent($task, $_REQUEST);
    }

    //Task section layout (da specializzare)
    public function Task_GetSectionContent($task)
    {
        return $this->Task_GetGenericObjectContent($task, $_REQUEST, "section");
    }

    //Task object data
    protected function Task_GetGenericObjectData($task, $params = array())
    {
        AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());

        $sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $objectData = array(array());

        switch ($params['object']) {
            case static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_LISTBOX:
                $_REQUEST['count'] = 10;
                $data = $this->GetDataSectionPubblicate_List($params);
                if ($data[0] > 0) $objectData = $data[1];
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_LISTBOX:
                $_REQUEST['count'] = 10;
                $data = $this->GetDataSectionBozze_List($params);
                if ($data[0] > 0) $objectData = $data[1];
                break;
            default:
                $objectData = array();
        }

        $sTaskLog .= base64_encode(json_encode($objectData));
        $sTaskLog .= "</content>";

        $task->SetLog($sTaskLog);

        return true;
    }

    //Task object data (da specializzare)
    public function Task_GetObjectData($task)
    {
        return $this->Task_GetGenericObjectData($task, $_REQUEST);
    }

    //Template detail (da specializzare)
    public function TemplateSection_Detail($params)
    {
        return $this->TemplateGenericSection_Detail($params);
    }

    //Template navbar bozze (da specializzare)
    public function TemplateNavbar_Bozze($level = 1, $last = false, $refresh_view = true)
    {
        return $this->TemplateGenericNavbar_Bozze($level, $last, $refresh_view);
    }

    //Template navbar pubblicate
    protected function TemplateGenericNavbar_Pubblicate($level = 1, $last = false, $refresh_view = true)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(
            static::AA_UI_PREFIX . "_Navbar_Link_" . static::AA_UI_PUBBLICATE_BOX,
            array(
                "type" => "clean",
                "section_id" => "Pubblicate",
                "module_id" => $this->GetId(),
                "refresh_view" => $refresh_view,
                "tooltip" => "Fai click per visualizzare le schede pubblicate",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" . static::AA_UI_PREFIX . "_Navbar_Link_" . static::AA_UI_PUBBLICATE_BOX . "' onClick='AA_MainApp.utils.callHandler(\"setCurrentSection\",\"Pubblicate\",\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => "Pubblicate", "icon" => "mdi mdi-certificate", "class" => $class)
            )
        );
        return $navbar;
    }

    //Template navbar pubblicate (da specializzare)
    public function TemplateNavbar_Pubblicate($level = 1, $last = false, $refresh_view = true)
    {
        return $this->TemplateGenericNavbar_Pubblicate($level, $last, $refresh_view);
    }

    //Template navbar indietro
    protected function TemplateGenericNavbar_Back($level = 1, $last = false, $refresh_view = false)
    {
        $class = "n" . $level;
        if ($last) $class .= " AA_navbar_terminator_left";
        $navbar =  new AA_JSON_Template_Template(
            static::AA_UI_PREFIX . "_Navbar_Link_Back_Content_Box",
            array(
                "type" => "clean",
                "css" => "AA_NavbarEventListener",
                "module_id" => $this->GetId(),
                "refresh_view" => $refresh_view,
                "tooltip" => "Fai click per tornare alla lista",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" . static::AA_UI_PREFIX . "_Navbar_Link_Back_Content_Box' onClick='AA_MainApp.utils.callHandler(\"goBack\",null,\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
                "data" => array("label" => "Indietro", "icon" => "mdi mdi-keyboard-backspace", "class" => $class)
            )
        );
        return $navbar;
    }

    //Template navbar indietro (da specializzare)
    public function TemplateNavbar_Back($level = 1, $last = false, $refresh_view = true)
    {
        return $this->TemplateGenericNavbar_Back($level, $last, $refresh_view);
    }

    //Template bozze context menu (da specializzare)
    public function TemplateActionMenu_Bozze()
    {
        return $this->TemplateGenericActionMenu_Bozze();
    }

    //Template pubblicate context menu
    protected function TemplateGenericActionMenu_Pubblicate()
    {

        $menu = new AA_JSON_Template_Generic(
            "AA_ActionMenuPubblicate",
            array(
                "view" => "contextmenu",
                "data" => array(
                    array(
                        "id" => "refresh_pubblicate",
                        "value" => "Aggiorna",
                        "icon" => "mdi mdi-reload",
                        "module_id" => $this->GetId(),
                        "handler" => "refreshUiObject",
                        "handler_params" => array(static::AA_UI_PREFIX . "_" . static::AA_UI_PUBBLICATE_BOX, true)
                    )
                )
            )
        );

        return $menu;
    }

    //Template pubblicate context menu (da specializzare)
    public function TemplateActionMenu_Pubblicate()
    {
        return $this->TemplateGenericActionMenu_Pubblicate();
    }

    //Template detail context menu
    protected function TemplateGenericActionMenu_Detail()
    {

        $menu = new AA_JSON_Template_Generic(
            "AA_ActionMenuDetail",
            array(
                "view" => "contextmenu",
                "data" => array(array(
                    "id" => "refresh_detail",
                    "value" => "Aggiorna",
                    "icon" => "mdi mdi-reload",
                    "panel_id" => "back",
                    "section_id" => "Dettaglio",
                    "module_id" => $this->GetId(),
                    "handler" => "refreshUiObject",
                    "handler_params" => array(static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX, true)
                ))
            )
        );

        return $menu;
    }

    //Template detail context menu (da specializzare)
    public function TemplateActionMenu_Detail()
    {
        return $this->TemplateGenericActionMenu_Detail();
    }

    //Template sezione pubblicate
    protected function TemplateGenericSection_Pubblicate($params = array(), $bCanModify = false, $contentData = null)
    {
        $content = new AA_GenericPagedSectionTemplate(static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_PUBBLICATE_NAME, $this->GetId());

        //custom items templates
        if (is_array($this->aSectionItemTemplates)) {
            if (isset($this->aSectionItemTemplates[static::AA_ID_SECTION_PUBBLICATE]) && $this->aSectionItemTemplates[static::AA_ID_SECTION_PUBBLICATE] != "") {
                $content->SetContentBoxTemplate($this->aSectionItemTemplates[static::AA_ID_SECTION_PUBBLICATE]);
            }
        }

        $content->EnablePager();
        $content->EnablePaging();
        $content->SetPagerItemForPage(10);
        $content->EnableFiltering();
        $content->SetFilterDlgTask(static::AA_UI_TASK_PUBBLICATE_FILTER_DLG);
        $content->ViewExportFunctions();

        $sectionName = static::AA_UI_SECTION_PUBBLICATE_NAME;

        $content->ViewDetail();

        if ($bCanModify) {
            $content->ViewReassign();
            $content->SetReassignHandlerParams(array("task" => static::AA_UI_TASK_REASSIGN_DLG));

            if ($params['revisionate'] == 1) {
                $sectionName .= " revisionate";
                $content->HideReassign();
                $content->ViewPublish();
                $content->SetPublishHandlerParams(array("task" => static::AA_UI_TASK_PUBLISH_DLG));
            }

            if ($_REQUEST['cestinate'] == 0) {
                $content->ViewTrash();
                $content->SetTrashHandlerParams(array("task" => static::AA_UI_TASK_TRASH_DLG));
            } else {
                $sectionName = static::AA_UI_SECTION_PUBBLICATE_CESTINATE_NAME;
                $content->HideReassign();
                $content->HidePublish();
                $content->ViewResume();
                $content->SetResumeHandlerParams(array("task" => static::AA_UI_TASK_RESUME_DLG));
                $content->ViewDelete();
                $content->SetDeleteHandlerParams(array("task" => static::AA_UI_TASK_DELETE_DLG));
            }
        }

        $content->SetSectionName($sectionName);

        if ($contentData == null) {
            $params['count'] = 10;
            $contentData = $this->GetDataSectionPubblicate_List($params);
        }

        $content->SetContentBoxData($contentData[1]);
        $content->SetPagerItemCount($contentData[0]);
        $content->EnableMultiSelect();
        $content->EnableSelect();

        return $content;
    }

    //Template sezione pubblicate (da specializzare)
    public function TemplateSection_Pubblicate($params = array())
    {
        $content = $this->TemplateGenericSection_Pubblicate($params, false);
        return $content->toObject();
    }

    //Restituisce la lista delle schede pubblicate 
    protected function GetDataGenericSectionBozze_List($params = array(), $customFilterFunction = null, $customTemplateDataFunction = null)
    {
        $templateData = array();

        $parametri = array("status" => AA_Const::AA_STATUS_BOZZA);
        if ($params['cestinate'] == 1) {
            $parametri['status'] |= AA_Const::AA_STATUS_CESTINATA;
        }

        if ($params['page'] > 0) $parametri['from'] = ($params['page'] - 1) * $params['count'];
        if ($params['id_assessorato'] != "") $parametri['id_assessorato'] = $params['id_assessorato'];
        if ($params['id_direzione'] != "") $parametri['id_direzione'] = $params['id_direzione'];
        if ($params['id_servizio'] != "") $parametri['id_servizio'] = $params['id_servizio'];
        if ($params['id'] != "") $parametri['id'] = $params['id'];
        if ($params['nome'] != "") $parametri['nome'] = $params['nome'];

        //Richiama la funzione custom per la personalizzazione dei parametri
        if (function_exists($customFilterFunction)) $parametri = array_merge($parametri, $customFilterFunction($params));
        if (method_exists($this, $customFilterFunction)) $parametri = array_merge($parametri, $this->$customFilterFunction($params));

        //Richiama la classe di gestione degli oggetti gestiti dal modulo
        $objectClass = static::AA_MODULE_OBJECTS_CLASS;
        if (class_exists($objectClass)) {
            if (method_exists($objectClass, "Search")) $data = $objectClass::Search($parametri, $this->oUser);
            else {
                AA_Log::Log(__METHOD__ . " Errore: Funzione di ricerca non definita ($objectClass::Search)", 100);
                $data[1] = array();
                $data[0] = 0;
            }
        } else {
            AA_Log::Log(__METHOD__ . " Errore: Classe di gestione dei moduli non definita ($objectClass)", 100);
            $data[1] = array();
            $data[0] = 0;
        }

        foreach ($data[1] as $id => $object) {
            $struct = $object->GetStruct();
            $struttura_gest = $struct->GetAssessorato();
            if ($struct->GetDirezione(true) > 0) $struttura_gest .= " -> " . $struct->GetDirezione();
            if ($struct->GetServizio(true) > 0) $struttura_gest .= " -> " . $struct->GetServizio();

            #Stato
            if ($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) $status = "bozza";
            if ($object->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $status = "pubblicata";
            if ($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $status .= " revisionata";
            if ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $status .= " cestinata";

            #Dettagli
            if ($this->oUser->IsSuperUser() && $object->GetAggiornamento() != "") {
                //Aggiornamento
                $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento(true) . "</span>&nbsp;";

                //utente e log
                $lastLog = $object->GetLog()->GetLastLog();
                $details .= "<span class='AA_Label AA_Label_LightBlue' title=\"Nome dell'utente che ha compiuto l'ultima azione - Fai click per visualizzare il log delle azioni\"><span class='mdi mdi-account' onClick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetLogDlg', taskManager: AA_MainApp.taskManager,'params': {id: " . $object->GetId() . "}},'" . $this->GetId() . "');\">" . $lastLog['user'] . "</span>&nbsp;";

                //id
                $details .= "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
            } else {
                if ($object->GetAggiornamento() != "") $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento(true) . "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
            }

            if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) == 0) $details .= "&nbsp;<span class='AA_Label AA_Label_LightBlue' title=\" L'utente corrente non può apportare modifiche all'organismo\"><span class='mdi mdi-pencil-off'></span>&nbsp; sola lettura</span>";

            $data = array(
                "id" => $object->GetId(),
                "tags" => "",
                "aggiornamento" => $object->GetAggiornamento(),
                "denominazione" => $object->GetName(),
                "pretitolo" => "",
                "sottotitolo" => $struttura_gest,
                "stato" => $status,
                "dettagli" => $details,
                "module_id" => $this->GetId()
            );

            if (method_exists($this, $customTemplateDataFunction)) {
                $templateData[] = $this->$customTemplateDataFunction($data, $object);
            } else if (function_exists($customTemplateDataFunction)) {
                $templateData[] = $customTemplateDataFunction($data, $object);
            } else {
                $templateData[] = $data;
            }
        }

        return array($data[0], $templateData);
    }

    //Restituisce la lista delle bozze (dati - da specializzare)
    public function GetDataSectionBozze_List($params = array())
    {
        return $this->GetDataGenericSectionPubblicate_List($params);
    }

    //Template section bozze content
    protected function TemplateGenericSection_Bozze($params, $contentData = null)
    {
        $content = new AA_GenericPagedSectionTemplate(static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_BOZZE_NAME, $this->GetId());

        //custom items templates
        if (is_array($this->aSectionItemTemplates)) {
            if (isset($this->aSectionItemTemplates[static::AA_ID_SECTION_BOZZE]) && $this->aSectionItemTemplates[static::AA_ID_SECTION_BOZZE] != "") {
                $content->SetContentBoxTemplate($this->aSectionItemTemplates[static::AA_ID_SECTION_BOZZE]);
            }
        }

        $content->EnablePager();
        $content->SetPagerItemForPage(10);
        $content->EnableFiltering();
        $content->EnableAddNew();
        $content->SetAddNewDlgTask(static::AA_UI_TASK_ADDNEW_DLG);
        $content->SetFilterDlgTask(static::AA_UI_TASK_BOZZE_FILTER_DLG);
        $content->ViewExportFunctions();

        $content->SetSectionName(static::AA_UI_SECTION_BOZZE_NAME);

        $content->ViewDetail();

        if ($params['cestinate'] == 0) {
            $content->ViewTrash();
            $content->SetTrashHandlerParams(array("task" => static::AA_UI_TASK_TRASH_DLG));
            $content->ViewPublish();
            $content->SetPublishHandlerParams(array("task" => static::AA_UI_TASK_PUBLISH_DLG));
            $content->ViewReassign();
            $content->SetReassignHandlerParams(array("task" => static::AA_UI_TASK_REASSIGN_DLG));
        } else {
            $content->SetSectionName(static::AA_UI_SECTION_BOZZE_CESTINATE_NAME);
            $content->ViewResume();
            $content->SetResumeHandlerParams(array("task" => static::AA_UI_TASK_RESUME_DLG));
            $content->ViewDelete();
            $content->SetDeleteHandlerParams(array("task" => static::AA_UI_TASK_DELETE_DLG));
            $content->HidePublish();
            $content->HideReassign();
            $content->EnableAddNew(false);
        }

        if ($contentData == null) {
            $params['count'] = 10;
            $contentData = $this->GetDataSectionBozze_List($params);
        }
        $content->SetContentBoxData($contentData[1]);
        $content->SetPagerItemCount($contentData[0]);
        $content->EnableMultiSelect();
        $content->EnableSelect();

        return $content;
    }

    //Template sezione bozze (da specializzare)
    public function TemplateSection_Bozze($params = array())
    {
        $content = $this->TemplateGenericSection_Bozze($params, false);
        return $content->toObject();
    }

    //Template Detail
    public function TemplateGenericSection_Detail($params)
    {
        $id = static::AA_UI_PREFIX . "_" . static::AA_ID_SECTION_DETAIL . "_";
        $objectClass = static::AA_MODULE_OBJECTS_CLASS;
        if (class_exists($objectClass)) {
            $object = new $objectClass($params['id'], $this->oUser);
        } else {
            return new AA_JSON_Template_Template(
                static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX,
                array(
                    "update_time" => Date("Y-m-d H:i:s"),
                    "name" => static::AA_UI_SECTION_DETAIL_NAME,
                    "type" => "clean", "template" => "Tipo di elemento non gestito."
                )
            );
        }

        #Stato
        if ($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) $status = "bozza";
        if ($object->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) $status = "pubblicata";
        if ($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) $status .= " revisionata";
        if ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) $status .= " cestinata";
        $status = "<span class='AA_Label AA_Label_LightBlue' title='Stato scheda organismo'>" . $status . "</span>";

        #Dettagli
        if ($this->oUser->IsSuperUser() && $object->GetAggiornamento() != "") {
            //Aggiornamento
            $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento() . "</span>&nbsp;";

            //utente
            $lastLog = $object->GetLog()->GetLastLog();
            //AA_Log::Log(__METHOD__." - ".print_r($lastLog,true),100);
            $details .= "<span class='AA_Label AA_Label_LightBlue' title=\"Nome dell'utente che ha compiuto l'ultima azione - Fai click per visualizzare il log delle azioni\"><span class='mdi mdi-account' onClick=\"AA_MainApp.utils.callHandler('dlg',{task: 'GetLogDlg', taskManager: AA_MainApp.taskManager,'params': {id: " . $object->GetId() . "}},'" . $this->GetId() . "');\">" . $lastLog['user'] . "</span>&nbsp;";
            $details .= "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
        } else {
            if ($object->GetAggiornamento() != "") $details = "<span class='AA_Label AA_Label_LightBlue' title='Data ultimo aggiornamento'><span class='mdi mdi-update'></span>&nbsp;" . $object->GetAggiornamento() . "</span>&nbsp;<span class='AA_Label AA_Label_LightBlue' title='Identificativo'><span class='mdi mdi-identifier'></span>&nbsp;" . $object->GetId() . "</span>";
        }

        $perms = $object->GetUserCaps($this->oUser);
        $id_org = $object->GetID();

        if (($perms & AA_Const::AA_PERMS_WRITE) == 0) $details .= "&nbsp;<span class='AA_Label AA_Label_LightBlue' title=\" L'utente corrente non può apportare modifiche all'organismo\"><span class='mdi mdi-pencil-off'></span>&nbsp; sola lettura</span>";

        $header = new AA_JSON_Template_Layout($id . "Header" . "_$id_org", array("type" => "clean", "height" => 38, "css" => "AA_SectionContentHeader"));

        //Scheda generale di default
        if (!is_array($this->aSectionItemTemplates)) {
            if ($this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL] == "") {
                $this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL] = array(array("id" => $id . "Generale_Tab_" . $id_org, "value" => "Generale", "tooltip" => "Dati generali", "template" => "TemplateGenericDettaglio_Generale_Tab"));
            }
        }

        //AA_Log::Log(__METHOD__." - ".print_r($this->aSectionItemTemplates,true),100);

        $header->addCol(new AA_JSON_Template_Generic($id . "TabBar" . "_$id_org", array(
            "view" => "tabbar",
            "borderless" => true,
            "value" => $this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL][0]['id'],
            "css" => "AA_Header_TabBar",
            "width" => 400,
            "multiview" => true,
            "view_id" => $id . "Multiview" . "_$id_org",
            "options" => $this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL]
        )));
        $header->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer")));
        $header->addCol(new AA_JSON_Template_Generic($id . "Detail" . "_$id_org", array(
            "view" => "template",
            "borderless" => true,
            "css" => "AA_SectionContentHeader",
            "minWidth" => 500,
            "template" => "<div style='display: flex; width:100%; height: 100%; justify-content: center; align-items: center;'>#status#<span>&nbsp;&nbsp;</span><span>#detail#</span></div>",
            "data" => array("detail" => $details, "status" => $status)
        )));

        $toolbar = new AA_JSON_Template_Toolbar($id . "_Toolbar" . "_$id_org", array(
            "type" => "clean",
            "css" => array("background" => "#ebf0fa", "border-color" => "transparent"),
            "width" => 400
        ));

        //Inserisce il pulsante di pubblicazione
        if (($perms & AA_Const::AA_PERMS_PUBLISH) > 0 && ($object->GetStatus() & AA_Const::AA_STATUS_BOZZA) > 0 && ($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) == 0) {
            $menu_data[] = array(
                "id" => $this->id . "_Publish" . "_$id_org",
                "value" => "Pubblica",
                "tooltip" => "Pubblica l'elemento",
                "icon" => "mdi mdi-certificate",
                "module_id" => $this->id,
                "handler" => "sectionActionMenu.publish",
                "handler_params" => array("task" => static::AA_UI_TASK_PUBLISH_DLG, "object_id" => $object->GetID())
            );
        }

        //Inserisce il pulsante di riassegnazione ripristino
        if (($perms & AA_Const::AA_PERMS_WRITE) > 0) {
            if (($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) == 0) {
                //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
                //$menu_spacer=true;
                $menu_data[] = array(
                    "id" => $this->id . "_Reassign" . "_$id_org",
                    "value" => "Riassegna",
                    "tooltip" => "Riassegna l'elemento",
                    "icon" => "mdi mdi-share-all",
                    "module_id" => $this->id,
                    "handler" => "sectionActionMenu.reassign",
                    "handler_params" => array("task" => static::AA_UI_TASK_REASSIGN_DLG, "object_id" => $object->GetID())
                );
            }
            if (($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) > 0) {
                $menu_data[] = array(
                    "id" => $id . "_Resume" . "_$id_org",
                    "value" => "Ripristina",
                    "tooltip" => "Ripristina gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                    "icon" => "mdi mdi-recycle",
                    "module_id" => $this->id,
                    "handler" => "sectionActionMenu.resume",
                    "handler_params" => array("task" => static::AA_UI_TASK_RESUME_DLG, "object_id" => $object->GetID())
                );
            }
        }

        //Inserisce le voci di esportazione
        //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
        //$menu_spacer=true;
        $menu_data[] = array(
            "id" => $id . "_SaveAsPdf" . "_$id_org",
            "value" => "Esporta in pdf",
            "tooltip" => "Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file pdf",
            "icon" => "mdi mdi-file-pdf",
            "module_id" => $this->id,
            "handler" => "sectionActionMenu.saveAsPdf",
            "handler_params" => array("task" => static::AA_UI_TASK_SAVEASPDF_DLG, "object_id" => $object->GetID())
        );
        $menu_data[] = array(
            "id" => $id . "_SaveAsCsv" . "_$id_org",
            "value" => "Esporta in csv",
            "tooltip" => "Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file csv",
            "icon" => "mdi mdi-file-table",
            "module_id" => $this->id,
            "handler" => "sectionActionMenu.saveAsCsv",
            "handler_params" => array("task" => static::AA_UI_TASK_SAVEASCSV_DLG, "object_id" => $object->GetID())
        );
        #-------------------------------------

        //Inserisce la voce di eliminazione
        if (($perms & AA_Const::AA_PERMS_DELETE) > 0) {
            if (($object->GetStatus() & AA_Const::AA_STATUS_CESTINATA) == 0) {
                //if($menu_spacer) $menu_data[]=array("\$template"=>"Separator");
                //$menu_spacer=true;

                $menu_data[] = array(
                    "id" => $id . "_Trash" . "_$id_org",
                    "value" => "Cestina",
                    "css" => "AA_Menu_Red",
                    "tooltip" => "Cestina l'elemento",
                    "icon" => "mdi mdi-trash-can",
                    "module_id" => $this->id,
                    "handler" => "sectionActionMenu.trash",
                    "handler_params" => array("task" => static::AA_UI_TASK_TRASH_DLG, "object_id" => $object->GetID())
                );
            } else {

                $menu_data[] = array(
                    "id" => $id . "_Delete" . "_$id_org",
                    "value" => "Elimina",
                    "css" => "AA_Menu_Red",
                    "tooltip" => "Elimina definitivamente l'elemento",
                    "icon" => "mdi mdi-trash-can",
                    "module_id" => $this->id,
                    "handler" => "sectionActionMenu.delete",
                    "handler_params" => array("task" => static::AA_UI_TASK_DELETE_DLG, "object_id" => $object->GetID())
                );
            }
        }

        //Azioni
        $scriptAzioni = "try{"
            . "let azioni_btn=$$('" . $id . "_Azioni_btn_$id_org');"
            . "if(azioni_btn){"
            . "let azioni_menu=webix.ui(azioni_btn.config.menu_data);"
            . "if(azioni_menu){"
            . "azioni_menu.setContext(azioni_btn);"
            . "azioni_menu.show(azioni_btn.\$view);"
            . "}"
            . "}"
            . "}catch(msg){console.error('" . $id . "_Azioni_btn_$id_org',this,msg);AA_MainApp.ui.alert(msg);}";
        $azioni_btn = new AA_JSON_Template_Generic($id . "_Azioni_btn" . "_$id_org", array(
            "view" => "button",
            "type" => "icon",
            "icon" => "mdi mdi-dots-vertical",
            "label" => "Azioni",
            "align" => "right",
            "autowidth" => true,
            "menu_data" => new AA_JSON_Template_Generic($id . "_ActionMenu" . "_$id_org", array("view" => "contextmenu", "data" => $menu_data, "module_id" => $this->GetId(), "on" => array("onItemClick" => "AA_MainApp.utils.getEventHandler('onDetailMenuItemClick','" . $this->GetId() . "')"))),
            "tooltip" => "Visualizza le azioni disponibili",
            "click" => $scriptAzioni
        ));

        $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer")));
        $toolbar->addElement($azioni_btn);
        $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 15)));

        $header->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer")));
        $header->addCol($toolbar);

        //Content box
        $content = new AA_JSON_Template_Layout(
            static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX,
            array(
                "type" => "clean",
                "name" => $object->GetName(),
                "filtered" => true
            )
        );
        $content->AddRow($header);

        $multiview = new AA_JSON_Template_Multiview($id . "Multiview" . "_$id_org", array(
            "type" => "clean",
            "css" => "AA_Detail_Content"
        ));

        foreach ($this->aSectionItemTemplates[static::AA_ID_SECTION_DETAIL] as $curTab) {
            if (method_exists($this, $curTab['template']) && $curTab['template'] != "") {
                $multiview->addCell($this->$curTab['template']($object));
            }
        }
        $content->AddRow($multiview);

        return $content;
    }

    //Template generic section detail, tab generale
    public function TemplateGenericDettaglio_Generale_Tab($object = null)
    {
        $sectionTemplate = $this->GetSectionItemTemplate(static::AA_ID_SECTION_DETAIL);
        if (!is_array($sectionTemplate)) {
            $id = static::AA_UI_PREFIX . "_" . static::AA_ID_SECTION_DETAIL . "_Generale_Tab_" . date("Y-m-d_h:i:s");
        } else {
            $id = $sectionTemplate[0]['id'];
        }

        if (!($object instanceof AA_Object_V2)) return new AA_JSON_Template_Template($id, array("template" => "Dati non validi"));

        //$id=static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_Generale_Tab_".$object->GetId();

        $layout = $this->TemplateGenericDettaglio_Header_Generale_Tab($object, $id);

        $rows_fixed_height = 50;

        //Nome
        $value = $object->GetName();
        if ($value == "") $value = "n.d.";
        $nome = new AA_JSON_Template_Template($id . "_Denominazione", array(
            "template" => "<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data" => array("title" => "Denominazione:", "value" => $value)
        ));

        //Descrizione
        $value = $object->GetDescr();
        if ($value == "") $value = "n.d.";
        $descr = new AA_JSON_Template_Template($id . "_Descrizione", array(
            "template" => "<span style='font-weight:700'>#title#</span><br><span>#value#</span>",
            "data" => array("title" => "Descrizione:", "value" => $value)
        ));

        //Prima riga
        $riga = new AA_JSON_Template_Layout($id . "_FirstRow", array("height" => $rows_fixed_height));
        $riga->AddCol($nome);
        $layout->AddRow($riga);

        //seconda riga
        $riga = new AA_JSON_Template_Layout($id . "_SecondRow");
        $riga->AddCol($descr);
        $layout->AddRow($riga);

        return $layout;
    }

    //Template generic section detail, tab generale header
    public function TemplateGenericDettaglio_Header_Generale_Tab($object = null, $id = "")
    {
        if (!($object instanceof AA_Object_V2)) return new AA_JSON_Template_Template($id, array("template" => "Dati non validi"));

        $layout = new AA_JSON_Template_Layout($id, array("type" => "clean"));

        $toolbar = new AA_JSON_Template_Toolbar($id . "_Toolbar", array("height" => 38, "css" => array("border-bottom" => "1px solid #dadee0 !important")));

        $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 120)));
        $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer")));

        $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer")));

        //Pulsante di modifica
        $canModify = false;
        if (($object->GetUserCaps($this->oUser) & AA_Const::AA_PERMS_WRITE) > 0) $canModify = true;
        if ($canModify) {
            $modify_btn = new AA_JSON_Template_Generic($id . "_Modify_btn", array(
                "view" => "button",
                "type" => "icon",
                "icon" => "mdi mdi-pencil",
                "label" => "Modifica",
                "align" => "right",
                "width" => 120,
                "tooltip" => "Modifica le informazioni generali",
                "click" => "AA_MainApp.utils.callHandler('dlg', {task:\"" . static::AA_UI_TASK_MODIFY_DLG . "\", params: [{id: " . $object->GetId() . "}]},'$this->id')"
            ));
            $toolbar->AddElement($modify_btn);
        }

        $layout->addRow($toolbar);

        return $layout;
    }
}

//Classe gestione sezioni dei moduli
class AA_GenericModuleSection
{
    protected $id = "AA_GENERIC_MODULE_SECTION";
    public function GetId()
    {
        return $this->id;
    }

    protected $name = "section name";
    public function GetName()
    {
        return $this->name;
    }
    public function SetName($val = "new name")
    {
        $this->name = $val;
    }

    //indica se deve esserci il riferimento sulla navbar
    protected $navbar = false;
    public function IsVisibleInNavbar()
    {
        return $this->navbar;
    }

    protected $view_id = "AA_Section_Content_Box";
    public function GetViewId()
    {
        return $this->view_id;
    }

    protected $module_id = "AA_GENERIC_MODULE";
    public function GetModuleId()
    {
        return $this->module_id;
    }

    protected $valid = false;
    public function isValid()
    {
        return $this->valid;
    }

    protected $default = false;
    public function IsDefault()
    {
        return $this->default;
    }

    protected $detail = false;
    public function IsDetail()
    {
        return $this->detail;
    }
    public function SetDetail($bVal = true)
    {
        $this->detail = $bVal;
    }

    protected $navbar_template = "{}";
    public function SetNavbarTemplate($template = "{}")
    {
        $this->navbar_template = $template;
    }

    protected $refresh_view = true;
    public function EnableRefreshView($bVal = true)
    {
        $this->refresh_view = $bVal;
    }

    public function toArray()
    {
        return array(
            "id" => $this->id,
            "name" => $this->name,
            "navbar" => $this->navbar,
            "view_id" => $this->view_id,
            "module_id" => $this->module_id,
            "default" => $this->default,
            "valid" => $this->valid,
            "navbar_template" => $this->navbar_template,
            "refresh_view" => $this->refresh_view,
            "detail" => $this->detail
        );
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function toBase64()
    {
        return base64_encode($this->toString());
    }

    public function __construct($id = "AA_GENERIC_MODULE_SECTION", $name = "section name", $navbar = false, $view_id = "AA_Section_Content_Box", $module_id = "AA_GENERIC_MODULE", $default = false, $refresh_view = true, $detail = false, $valid = false)
    {
        $this->name = $name;
        $this->id = $id;
        $this->navbar = $navbar;
        $this->view_id = $view_id;
        $this->module_id = $module_id;
        $this->default = $default;
        $this->valid = $valid;
        $this->refresh_view = $refresh_view;
        $this->detail = $detail;
    }
}
#----------------------------------------------
//Classe dell'assistente digitale AMAAI
class AA_AMAAI
{
    private static $oInstance = null;
    public static function GetInstance()
    {
        if (self::$oInstance == null) self::$oInstance = new AA_AMAAI;

        return self::$oInstance;
    }

    //Restituisce il template del layout della finestra
    public function TemplateLayout()
    {
        return new AA_GenericWindowTemplate("AA_AMAAI", "AMAAI - Navigazione Assistita");
    }

    //Restituisce la pagina iniziale della navigazione assistita
    public function TemplateStart()
    {
        $content = "<div style='display:flex; flex-direction: column; justify-content: space-between; align-items: center; width:100%; height:100%; font-size: larger'>";
        //$content.="<div style='display:flex; flex-direction: column; justify-content: flex-start; align-items: center; width:100%; height: 30%'>";
        //$content.="<p style='font-weight: 700'>Benvenuti!</p>";
        //$content.="<p style='border-bottom: 1px solid blue'>L'assistente digitale AMAAI vi assisterà nell'utilizzo delle funzionalità della piattaforma.</p>";
        //$content.="<br>";
        $content .= "<p style='font-weight: 700'>Come posso esserti d'aiuto?</p>";
        //$content.="</div>";
        $content .= "<div style='display:flex; flex-direction: column; justify-content: space-between; align-items: stretch; width:100%; height:50%; margin-bottom: 3em'>";
        $content .= "<div class='AA_AMAAI_QUEST'><a class='AA_AMAAI_QUEST_1'>Voglio effettuare una nuova pubblicazione...</a></div>";
        $content .= "<div class='AA_AMAAI_QUEST'><a class='AA_AMAAI_QUEST_2'>Voglio modificare una pubblicazione...</a></div>";
        $content .= "<div class='AA_AMAAI_QUEST'><a class='AA_AMAAI_QUEST_3'>Voglio cercare una pubblicazione...</a></div>";
        $content .= "<div class='AA_AMAAI_QUEST'><a class='AA_AMAAI_QUEST_4'>Voglio annullare una pubblicazione...</a></div>";
        $content .= "</div>";
        $content .= "</div>";
        return array(
            "id" => "AA_AMAAI_START",
            "view" => "template",
            "template" => $content
        );
    }
}

//Task che restituisce il layout del modulo AMAAI
class AA_SystemTask_AMAAI_Start extends AA_GenericTask
{
    public function __construct($user = null)
    {
        parent::__construct("AMAAI_Start", $user);
    }

    //Funzione per la gestione del task
    public function Run()
    {
        AA_Log::Log(__METHOD__ . "() - task: " + $this->GetName());

        $module = AA_AMAAI::GetInstance();

        $this->sTaskLog = "<status id='status'>0</status><content id='content' type='json' encode='base64'>";

        $template = $module->TemplateLayout();
        $template->SetWidth(720);
        $template->SetHeight(580);

        $template->AddView($module->TemplateStart());

        $content = $template->toString();
        $this->sTaskLog .= base64_encode($content);
        $this->sTaskLog .= "</content>";
        return true;
    }
}
#--------------------------------------------

//Classe per la gestione del layout delle finestre
class AA_GenericWindowTemplate
{
    protected $id = "AA_TemplateGenericWnd";
    public function SetId($id = "")
    {
        if ($id != "") $this->id = $id;
    }

    public function GetId()
    {
        return $this->id;
    }

    protected $body = "";
    protected $head = "";
    protected $wnd = "";

    protected $modal = true;
    public function EnableModal()
    {
        $this->modal = true;
    }
    public function DisableModal()
    {
        $this->modal = false;
    }

    protected $module = "";
    public function SetModule($idModule)
    {
        $this->module = $idModule;
    }
    public function GetModule()
    {
        return $this->module;
    }

    private $title = "finestra di dialogo";
    public function __construct($id = "", $title = "", $module = "")
    {
        if ($id != "") $this->id = $id;
        if ($title != "") $this->title = $title;

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->module = $module;

        $script = 'try{if($$(\'' . $this->id . '_Wnd\').config.fullscreen){webix.fullscreen.exit();$$(\'' . $this->id . '_btn_resize\').define({icon:"mdi mdi-fullscreen", tooltip:"Mostra la finestra a schermo intero"});$$(\'' . $this->id . '_btn_resize\').refresh();}else{webix.fullscreen.set($$(\'' . $this->id . '_Wnd\'));$$(\'' . $this->id . '_btn_resize\').define({icon:"mdi mdi-fullscreen-exit", tooltip:"Torna alla visualizzazione normale"});$$(\'' . $this->id . '_btn_resize\').refresh();}}catch(msg){console.error(msg);}';

        $this->body = new AA_JSON_Template_Layout($this->id . "_Content_Box", array("type" => "clean"));
        $this->head = new AA_JSON_Template_Generic($this->id . "_head", array("css" => "AA_Wnd_header_box", "view" => "toolbar", "height" => "38", "elements" => array(
            array("id" => $this->id . "_Title", "css" => "AA_Wnd_title", "template" => $this->title),
            array("id" => $this->id . "_btn_resize", "view" => "icon", "icon" => "mdi mdi-fullscreen", "css" => "AA_Wnd_btn_fullscreen", "width" => 24, "height" => 24, "tooltip" => "Mostra la finestra a schermo intero", "click" => $script),
            array("id" => $this->id . "_btn_close", "view" => "icon", "icon" => "mdi mdi-close", "css" => "AA_Wnd_btn_close", "width" => 24, "height" => 24, "tooltip" => "Chiudi la finestra", "click" => "try{if($$('" . $this->id . "_Wnd').config.fullscreen){webix.fullscreen.exit();};$$('" . $this->id . "_Wnd').close();}catch(msg){console.error(msg)}")
        )));

        $this->wnd = new AA_JSON_Template_Generic($this->id . "_Wnd", array(
            "view" => "window",
            "height" => $this->height,
            "width" => $this->width,
            "position" => "center",
            "modal" => $this->modal,
            "move" => true,
            "resize" => true,
            "css" => "AA_Wnd"
        ));

        $this->wnd->SetProp("head", $this->head);
        $this->wnd->SetProp("body", $this->body);
    }

    protected function Update()
    {
        $this->wnd->setProp("height", $this->height);
        $this->wnd->setProp("width", $this->width);
        $this->wnd->setProp("modal", $this->modal);
    }

    protected $width = "1280";
    public function SetWidth($width = "1280")
    {
        if ($width > 0) $this->width = $width;
    }
    public function GetWidth()
    {
        return $this->width;
    }

    protected $height = "720";
    public function SetHeight($height = "720")
    {
        if ($height > 0) $this->height = $height;
    }
    public function GetHeight()
    {
        return $this->height;
    }

    //Gestione del contenuto
    public function AddView($view)
    {
        if (is_array($view) && $view['id'] != "") {
            $this->body->AddRow(new AA_JSON_Template_Generic($view['id'], $view));
        }

        if ($view instanceof AA_JSON_Template_Generic) $this->body->AddRow($view);
    }

    public function __toString()
    {
        $this->Update();
        return json_encode($this->wnd->toArray());
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function GetObject()
    {
        $this->Update();
        return $this->wnd;
    }

    public function toBase64()
    {
        $this->Update();

        return $this->wnd->toBase64();
    }
}

//Template generic filter box
class AA_GenericFilterDlg extends AA_GenericFormDlg
{
    protected $saveFilterId = "";
    public function SetSaveFilterId($id = "")
    {
        $this->saveFilterId = $id;
    }
    public function GetSaveFilterId()
    {
        return $this->saveFilterId;
    }

    protected $enableSessionSave = false;
    public function EnableSessionSave($bVal = true)
    {
        $this->enableSessionSave = $bVal;
    }

    public function __construct($id = "", $title = "", $module = "", $formData = array(), $resetData = array(), $applyActions = "", $save_filter_id = "")
    {
        parent::__construct($id, $title, $module, $formData, $resetData, $applyActions, $save_filter_id);

        $this->SetWidth("700");
        $this->SetHeight("400");

        $this->applyActions = $applyActions;
        $this->saveFilterId = $save_filter_id;

        /*$this->form=new AA_JSON_Template_Form($this->id."_Form",array(
            "data"=>$formData,
            "elementsConfig"=>array("labelWidth"=>180)
        ));
        
        $this->body->AddRow($this->form);
        
        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view"=>"spacer", "height"=>10, "css"=>array("border-top"=>"1px solid #e6f2ff !important;"))));
        
        //Apply button
        $this->applyButton = new AA_JSON_Template_Generic($this->id."_Button_Bar_Apply",array("view"=>"button","width"=>80, "label"=>"Applica"));
        
        //Toolbar
        $toolbar=new AA_JSON_Template_Layout($this->id."_Button_Bar",array("height"=>38));
        $toolbar->addCol(new AA_JSON_Template_Generic("spacer",array("view"=>"spacer","width"=>15)));
        
        //reset form button
        if(is_array($resetData))$resetAction="if($$('".$this->id."_Form')) $$('".$this->id."_Form').setValues(".json_encode($resetData).")";
        else $resetAction="";
        $toolbar->addCol(new AA_JSON_Template_Generic($this->id."_Button_Bar_Reset",array("view"=>"button","width"=>80,"label"=>"Reset", "tooltip"=>"Reimposta i valori di default", "click"=>$resetAction)));
        
        $toolbar->addCol(new AA_JSON_Template_Generic());
        
        $toolbar->addCol($this->applyButton);
        $toolbar->addCol(new AA_JSON_Template_Generic("spacer",array("view"=>"spacer","width"=>15)));
        $this->body->AddRow($toolbar);
        $this->body->AddRow(new AA_JSON_Template_Generic("spacer",array("view"=>"spacer","height"=>10)));*/
    }

    protected function Update()
    {
        parent::Update();

        if ($this->module == "") $module = "module=AA_MainApp.curModule";
        else $module = "module=AA_MainApp.getModule('" . $this->module . "')";

        if ($this->saveFilterId == "") $filter_id = "module.getActiveView()";
        else $filter_id = "'" . $this->saveFilterId . "'";

        if ($this->enableSessionSave) {
            $sessionSave = "AA_MainApp.setSessionVar(" . $filter_id . ", $$('" . $this->id . "_Form').getValues());";
        }

        $this->applyButton->SetProp("click", "try{" . $module . "; if(module.isValid()) {" . $sessionSave . "module.setRuntimeValue(" . $filter_id . ",'filter_data',$$('" . $this->id . "_Form').getValues());" . $this->applyActions . ";}$$('" . $this->id . "_Wnd').close()}catch(msg){console.error(msg)}");
    }

    /*
    //Aggiungi un campo al form
    public function AddField($name="", $label="", $type="text", $props=array())
    {
        if($name !="" && $label !="")
        {
            $props['name']=$name;
            $props['label']=$label;
            
            if($type=="text") $this->form->AddElement(new AA_JSON_Template_Text($this->id."_Field_".$name,$props));
            if($type=="textarea") $this->form->AddElement(new AA_JSON_Template_Textarea($this->id."_Field_".$name,$props));
            if($type=="checkbox") $this->form->AddElement(new AA_JSON_Template_Checkbox($this->id."_Field_".$name,$props));
            if($type=="select") $this->form->AddElement(new AA_JSON_Template_Select($this->id."_Field_".$name,$props));
            if($type=="switch") $this->form->AddElement(new AA_JSON_Template_Switch($this->id."_Field_".$name,$props));
        }
    }
    
    //Aggiungi un campo di testo
    public function AddTextField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"text",$props);
    }
    
    //Aggiungi un campo di area di testo
    public function AddTextareaField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"textarea",$props);
    }
    
    //Aggiungi un checkbox
    public function AddCheckBoxField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"checkbox",$props);
    }
    
    //Aggiungi un switchbox
    public function AddSwitchBoxField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"switch",$props);
    }
    
    //Aggiungi una select
    public function AddSelectField($name="", $label="", $props=array())
    {
        return $this->AddField($name,$label,"select",$props);
    }
    
    //Aggiungi un campo per la scelta delle strutture
    public function AddStructField($taskParams=array(),$params=array(), $fieldParams=array())
    {
        $onSearchScript="try{ if($$('".$this->id."_Form').getValues().id_struct_tree_select) AA_MainApp.ui.MainUI.structDlg.lastSelectedItem={id: $$('".$this->id."_Form').getValues().id_struct_tree_select}; AA_MainApp.ui.MainUI.structDlg.show(". json_encode($taskParams).",".json_encode($params).");}catch(msg){console.error(msg)}";
        
        if($fieldParams['name']== "") $fieldParams['name']="struct_desc";
        if($fieldParams['label']== "") $fieldParams['label']="Struttura";
        if($fieldParams['readonly']== "") $fieldParams['readonly']=true;
        if($fieldParams['click']== "") $fieldParams['click']=$onSearchScript;
        
        $this->form->AddElement(new AA_JSON_Template_Search($this->id."_Field_Struct_Search",$fieldParams));
    }*/
}

//Template generic filter box
class AA_GenericFormDlg extends AA_GenericWindowTemplate
{
    protected $form = "";
    public function GetForm()
    {
        return $this->form;
    }
    public function GetFormId()
    {
        if ($this->form instanceof AA_JSON_Template_Form) return $this->form->GetId();
    }

    protected $layout = "";
    public function GetLayout()
    {
        return $this->layout;
    }
    public function GetLayoutId()
    {
        if ($this->layout instanceof AA_JSON_Template_Generic) return $this->layout->GetId();
    }

    protected $curRow = null;
    public function GetCurRow()
    {
        return $this->curRow;
    }

    protected $bottomPadding = 18;
    public function SetBottomPadding($val = 18)
    {
        $this->bottomPadding = $val;
    }

    protected $validation = false;
    public function EnableValidation($bVal = true)
    {
        $this->validation = $bVal;
    }

    protected $applyActions = "";
    public function SetApplyActions($actions = "")
    {
        $this->applyActions = $actions;
    }

    protected $saveFormDataId = "";
    public function SetSaveformDataId($id = "")
    {
        $this->saveFormDataId = $id;
    }
    public function GetSaveFormDataId()
    {
        return $this->saveFormDataId;
    }

    protected $labelWidth = 120;
    public function SetLabelWidth($val = 120)
    {
        $this->labelWidth = $val;
    }

    protected $labelAlign = "left";
    public function SetLabelAlign($val = "left")
    {
        $this->labelAlign = $val;
    }

    //Gestione pulsanti
    protected $applyButton = null;
    protected $applyButtonName = "Salva";
    public function SetApplyButtonName($val = "Salva")
    {
        $this->applyButtonName = $val;
    }
    protected $resetButtonName = "Reset";
    public function SetResetButtonName($val = "Reset")
    {
        $this->resetButtonName = $val;
    }
    protected $enableReset = true;
    public function EnableResetButton($bVal = true)
    {
        $this->enableReset = $bVal;
    }
    #----------------------------------------------------

    //Valori form
    protected $formData = array();
    protected $resetData = array();

    public function __construct($id = "", $title = "", $module = "", $formData = array(), $resetData = array(), $applyActions = "", $save_formdata_id = "")
    {
        parent::__construct($id, $title, $module);

        //AA_Log::Log(__METHOD__." - ".$module,100);

        $this->SetWidth("700");
        $this->SetHeight("400");

        $this->applyActions = $applyActions;
        $this->saveFormDataId = $save_formdata_id;
        $this->formData = $formData;
        if (sizeof($resetData) == 0) $resetData = $formData;
        $this->resetData = $resetData;

        $this->form = new AA_JSON_Template_Form($this->id . "_Form", array(
            "data" => $formData,
        ));

        $this->body->AddRow($this->form);
        $this->layout = new AA_JSON_Template_Layout($id . "_Form_Layout", array("type" => "clean"));
        $this->form->AddRow($this->layout);

        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 10, "css" => array("border-top" => "1px solid #e6f2ff !important;"))));
    }

    //File upload id
    protected $fileUploader_id = "";

    #Gestione salvataggio dati
    protected $refresh = true; //Rinfresca la view in caso di salvataggio 
    public function enableRefreshOnSuccessfulSave($bVal = true)
    {
        $this->refresh = $bVal;
    }
    protected $refresh_obj_id = "";
    public function SetRefreshObjId($id = "")
    {
        $this->refresh_obj_id = $id;
    }
    protected $closeWnd = true;
    public function EnableCloseWndOnSuccessfulSave($bVal = true)
    {
        $this->closeWnd = $bVal;
    }
    protected $saveTask = "";
    public function SetSaveTask($task = "")
    {
        $this->saveTask = $task;
    }
    protected $saveTaskParams = array();
    public function SetSaveTaskParams($params = array())
    {
        if (is_array($params)) $this->saveTaskParams = $params;
    }
    #-----------------------------------------------------    
    protected function Update()
    {
        $elementsConfig = array("labelWidth" => $this->labelWidth, "labelAlign" => $this->labelAlign, "bottomPadding" => $this->bottomPadding);
        if ($this->validation) {
            $this->form->SetProp("validation", "validateForm");
        }

        $this->form->SetProp("elementsConfig", $elementsConfig);

        if ($this->applyActions == "") {
            if ($this->saveTask != "") {
                $params = "{task: '$this->saveTask'";
                if (sizeof($this->saveTaskParams) > 0) $params .= ", taskParams: " . json_encode(array($this->saveTaskParams));
                if ($this->closeWnd) $params .= ", wnd_id: '" . $this->id . "_Wnd'";
                if ($this->refresh) $params .= ", refresh: true";
                if ($this->refresh_obj_id) $params .= ", refresh_obj_id: '$this->refresh_obj_id'";
                if ($this->fileUploader_id != "") $params .= ", fileUploader_id: '$this->fileUploader_id'";
                $params .= ", data: $$('" . $this->id . "_Form').getValues()}";
                if ($this->validation) $validate = "if($$('" . $this->id . "_Form').validate())";
                else $validate = "";
                $this->applyActions = $validate . "AA_MainApp.utils.callHandler('saveData',$params,'$this->module')";
            }
        }

        //Apply button
        $this->applyButton = new AA_JSON_Template_Generic($this->id . "_Button_Bar_Apply", array("view" => "button", "width" => 80, "css"=>"webix_primary","label" => $this->applyButtonName));

        //Toolbar
        $toolbar = new AA_JSON_Template_Layout($this->id . "_Button_Bar", array("height" => 38));
        $toolbar->addCol(new AA_JSON_Template_Generic("spacer", array("view" => "spacer", "width" => 15)));

        //reset form button
        if ($this->enableReset && is_array($this->resetData)) {
            $resetAction = "if($$('" . $this->id . "_Form')) $$('" . $this->id . "_Form').setValues(" . json_encode($this->resetData) . ")";
            $toolbar->addCol(new AA_JSON_Template_Generic($this->id . "_Button_Bar_Reset", array("view" => "button", "width" => 80, "label" => $this->resetButtonName, "tooltip" => "Reimposta i valori di default", "click" => $resetAction)));
        }
        $toolbar->addCol(new AA_JSON_Template_Generic());

        $toolbar->addCol($this->applyButton);
        $toolbar->addCol(new AA_JSON_Template_Generic("spacer", array("view" => "spacer", "width" => 15)));
        $this->body->AddRow($toolbar);
        $this->body->AddRow(new AA_JSON_Template_Generic("spacer", array("view" => "spacer", "height" => 10)));
        $this->applyButton->SetProp("click", $this->applyActions);

        parent::Update();
    }

    //Aggiungi un campo al form
    public function AddField($name = "", $label = "", $type = "text", $props = array(), $newRow = true)
    {
        if ($name != "" && $label != "") {
            $props['name'] = $name;
            $props['label'] = $label;

            if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
                $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row");
                $this->layout->AddRow($this->curRow);
            }
            if ($type == "text") $this->curRow->AddCol(new AA_JSON_Template_Text($this->id . "_Field_" . $name, $props));
            if ($type == "richtext") $this->curRow->AddCol(new AA_JSON_Template_Richtext($this->id . "_Field_" . $name, $props));
            if ($type == "textarea") $this->curRow->AddCol(new AA_JSON_Template_Textarea($this->id . "_Field_" . $name, $props));
            if ($type == "checkbox") $this->curRow->AddCol(new AA_JSON_Template_Checkbox($this->id . "_Field_" . $name, $props));
            if ($type == "select") $this->curRow->AddCol(new AA_JSON_Template_Select($this->id . "_Field_" . $name, $props));
            if ($type == "switch") $this->curRow->AddCol(new AA_JSON_Template_Switch($this->id . "_Field_" . $name, $props));
            if ($type == "datepicker") $this->curRow->AddCol(new AA_JSON_Template_Datepicker($this->id . "_Field_" . $name, $props));
            if ($type == "radio") $this->curRow->AddCol(new AA_JSON_Template_Radio($this->id . "_Field_" . $name, $props));

            //Se il campo è invisibile aggiunge uno spacer
            if ($props['hidden'] == true) {
                $this->curRow->AddCol(new AA_JSON_Template_Generic($this->id . "_Spacer_" . $name, array("view" => "spacer", "minHeight" => "0", "minWidth" => "0", "height" => 1)));
            }
        }
    }

    //Aggiungi una nuova sezione
    public function AddSection($name = "New Section", $newRow = true)
    {
        if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row");
            $this->layout->AddRow($this->curRow);
            $this->curRow->AddCol(new AA_JSON_Template_Generic($this->id . "_Section_", array("type" => "section", "template" => $name)));
        } else {
            $this->curRow->AddCol(new AA_JSON_Template_Generic($this->id . "_Section_" . $name, array("type" => "section", "template" => $name)));
        }
    }

    //Aggiungi uno spazio
    public function AddSpacer($newRow = true)
    {
        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row");
            $this->layout->AddRow($this->curRow);
        }

        $this->curRow->AddCol(new AA_JSON_Template_Generic($this->id . "_Field_Spacer", array("view" => "spacer")));
    }

    //Aggiungi un campo di testo
    public function AddTextField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "text", $props, $newRow);
    }

    //Aggiungi un campo di area di testo
    public function AddTextareaField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "textarea", $props, $newRow);
    }

    //Aggiungi un campo richtext
    public function AddRichtextField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "richtext", $props, $newRow);
    }

    //Aggiungi un checkbox
    public function AddCheckBoxField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "checkbox", $props, $newRow);
    }

    //Aggiungi un switchbox
    public function AddSwitchBoxField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "switch", $props, $newRow);
    }

    //Aggiungi una select
    public function AddSelectField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "select", $props, $newRow);
    }

    //Aggiungi un radio control
    public function AddRadioField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "radio", $props, $newRow);
    }

    //Aggiungi un campo per la scelta delle strutture
    public function AddStructField($taskParams = array(), $params = array(), $fieldParams = array(), $newRow = true)
    {
        $onSearchScript = "try{ if($$('" . $this->id . "_Form').getValues().id_struct_tree_select) AA_MainApp.ui.MainUI.structDlg.lastSelectedItem={id: $$('" . $this->id . "_Form').getValues().id_struct_tree_select}; AA_MainApp.ui.MainUI.structDlg.show(" . json_encode($taskParams) . "," . json_encode($params) . ");}catch(msg){console.error(msg)}";

        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row");
            $this->layout->AddRow($this->curRow);
        }

        if ($fieldParams['name'] == "") $fieldParams['name'] = "struct_desc";
        if ($fieldParams['label'] == "") $fieldParams['label'] = "Struttura";
        if ($fieldParams['readonly'] == "") $fieldParams['readonly'] = true;
        if ($fieldParams['click'] == "") $fieldParams['click'] = $onSearchScript;

        $this->curRow->AddCol(new AA_JSON_Template_Search($this->id . "_Field_Struct_Search", $fieldParams));
    }

    //Aggiungi un campo per l'upload di file
    public function AddFileUploadField($name = "AA_FileUploader", $label = "Sfoglia...", $props = array(), $newRow = true)
    {
        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row");
            $this->layout->AddRow($this->curRow);
        }

        $props['name'] = "AA_FileUploader";
        if ($label == "") $props['value'] = "Sfoglia...";
        else $props['value'] = $label;
        $props['autosend'] = false;
        if ($props['multiple'] == "") $props['multiple'] = false;
        $props['view'] = "uploader";
        $props['link'] = $this->id . "_FileUpload_List";
        $props['layout_id'] = $this->id . "_FileUpload_Layout";
        $props['formData'] = array("file_id" => $name);

        $this->fileUploader_id = $this->id . "_FileUpload_Field";

        $template = new AA_JSON_Template_Layout($this->id . "_FileUpload_Layout", array("type" => "clean", "borderless" => true));
        $template->AddRow(new AA_JSON_Template_Generic($this->id . "_FileUpload_Field", $props));
        $template->AddRow(new AA_JSON_Template_Generic($this->id . "_FileUpload_List", array(
            "view" => "list",
            "scroll" => false,
            "type" => "uploader",
            "css" => array("background" => "transparent")
        )));

        if ($props['bottomLabel']) {
            $template->AddRow(new AA_JSON_Template_Template($this->id . "_FileUpload_BottomLabel", array(
                "template" => "<span style='font-size: smaller; font-style:italic'>" . $props['bottomLabel'] . "</span>",
                "css" => array("background" => "transparent")
            )));
        }

        $this->curRow->AddCol($template);
    }

    //Aggiungi un campo data
    public function AddDateField($name = "", $label = "", $props = array(), $newRow = true)
    {
        $props['timepick'] = false;
        if ($props['format'] == "") $props['format'] = "%Y-%m-%d";
        $props['stringResult'] = true;
        return $this->AddField($name, $label, "datepicker", $props, $newRow);
    }

    //Aggiungi un generico oggetto
    public function AddGenericObject($obj, $newRow = true)
    {
        if ($obj instanceof AA_JSON_Template_Generic) {
            if ($newRow) {
                $this->curRow = new AA_JSON_Template_Layout($this->id . "_Layout_Row");
                $this->layout->AddRow($this->curRow);
            }

            $this->curRow->AddCol($obj);
        }
    }
}

//Classe gestione set di campi 
class AA_FieldSet extends AA_JSON_Template_Generic
{
    public function __construct($id = "field_set", $label = "Generic field set")
    {
        $this->props['view'] = "fieldset";
        $this->props['label'] = $label;
        $this->layout = new AA_JSON_Template_Layout($id . "_FieldSet_Layout", array("type" => "clean"));
        $this->addRowToBody($this->layout);
    }

    protected $layout = null;
    public function GetLayout()
    {
        return $this->layout;
    }
    public function GetLayoutId()
    {
        if ($this->layout instanceof AA_JSON_Template_Generic) return $this->layout->GetId();
    }

    protected $curRow = null;
    public function GetCurRow()
    {
        return $this->curRow;
    }

    //Aggiungi un campo al field set
    public function AddField($name = "", $label = "", $type = "text", $props = array(), $newRow = true)
    {
        if ($name != "" && $label != "") {
            $props['name'] = $name;
            $props['label'] = $label;

            if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
                $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row");
                $this->layout->AddRow($this->curRow);
            }

            if ($type == "text") $this->curRow->AddCol(new AA_JSON_Template_Text($this->GetId() . "_Field_" . $name, $props));
            if ($type == "textarea") $this->curRow->AddCol(new AA_JSON_Template_Textarea($this->GetId() . "_Field_" . $name, $props));
            if ($type == "checkbox") $this->curRow->AddCol(new AA_JSON_Template_Checkbox($this->GetId() . "_Field_" . $name, $props));
            if ($type == "select") $this->curRow->AddCol(new AA_JSON_Template_Select($this->GetId() . "_Field_" . $name, $props));
            if ($type == "switch") $this->curRow->AddCol(new AA_JSON_Template_Switch($this->GetId() . "_Field_" . $name, $props));
            if ($type == "datepicker") $this->curRow->AddCol(new AA_JSON_Template_Datepicker($this->GetId() . "_Field_" . $name, $props));
            if ($type == "radio") $this->curRow->AddCol(new AA_JSON_Template_Radio($this->GetId() . "_Field_" . $name, $props));
        }
    }

    //Aggiungi una nuova sezione
    public function AddSection($name = "New Section", $newRow = true)
    {
        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row");
            $this->layout->AddRow($this->curRow);
            $this->curRow->AddCol(new AA_JSON_Template_Generic($this->GetId() . "_Section_", array("type" => "section", "template" => $name)));
        } else {
            $this->curRow->AddCol(new AA_JSON_Template_Generic($this->GetId() . "_Section_" . $name, array("type" => "section", "template" => $name)));
        }
    }

    //Aggiungi uno spazio
    public function AddSpacer($newRow = true)
    {
        if ($newRow || !($this->curRow instanceof AA_JSON_Template_Layout)) {
            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row");
            $this->layout->AddRow($this->curRow);
        }

        $this->curRow->AddCol(new AA_JSON_Template_Generic($this->GetId() . "_Field_Spacer", array("view" => "spacer")));
    }

    //Aggiungi un campo di testo
    public function AddTextField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "text", $props, $newRow);
    }

    //Aggiungi un campo di area di testo
    public function AddTextareaField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "textarea", $props, $newRow);
    }

    //Aggiungi un checkbox
    public function AddCheckBoxField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "checkbox", $props, $newRow);
    }

    //Aggiungi un switchbox
    public function AddSwitchBoxField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "switch", $props, $newRow);
    }

    //Aggiungi una select
    public function AddSelectField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "select", $props, $newRow);
    }

    //Aggiungi un radio control
    public function AddRadioField($name = "", $label = "", $props = array(), $newRow = true)
    {
        return $this->AddField($name, $label, "radio", $props, $newRow);
    }

    //Aggiungi un campo per la scelta delle strutture
    public function AddStructField($taskParams = array(), $params = array(), $fieldParams = array(), $newRow = true)
    {
        $onSearchScript = "try{ if($$('" . $this->GetId() . "_Form').getValues().id_struct_tree_select) AA_MainApp.ui.MainUI.structDlg.lastSelectedItem={id: $$('" . $this->GetId() . "_Form').getValues().id_struct_tree_select}; AA_MainApp.ui.MainUI.structDlg.show(" . json_encode($taskParams) . "," . json_encode($params) . ");}catch(msg){console.error(msg)}";

        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row");
            $this->layout->AddRow($this->curRow);
        }

        if ($fieldParams['name'] == "") $fieldParams['name'] = "struct_desc";
        if ($fieldParams['label'] == "") $fieldParams['label'] = "Struttura";
        if ($fieldParams['readonly'] == "") $fieldParams['readonly'] = true;
        if ($fieldParams['click'] == "") $fieldParams['click'] = $onSearchScript;

        $this->curRow->AddCol(new AA_JSON_Template_Search($this->GetId() . "_Field_Struct_Search", $fieldParams));
    }

    protected $fileUploader_id="";

    //Aggiungi un campo per l'upload di file
    public function AddFileUploadField($name = "AA_FileUploader", $label = "Sfoglia...", $props = array(), $newRow = true)
    {
        if ($newRow) {
            $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row");
            $this->layout->AddRow($this->curRow);
        }

        $props['name'] = "AA_FileUploader";
        if ($label == "") $props['value'] = "Sfoglia...";
        else $props['value'] = $label;
        $props['autosend'] = false;
        if ($props['multiple'] == "") $props['multiple'] = false;
        $props['view'] = "uploader";
        $props['link'] = $this->GetId() . "_FileUpload_List";
        $props['layout_id'] = $this->GetId() . "_FileUpload_Layout";
        $props['formData'] = array("file_id" => $name);

        $this->fileUploader_id = $this->GetId() . "_FileUpload_Field";

        $template = new AA_JSON_Template_Layout($this->GetId() . "_FileUpload_Layout", array("type" => "clean", "borderless" => true));
        $template->AddRow(new AA_JSON_Template_Generic($this->GetId() . "_FileUpload_Field", $props));
        $template->AddRow(new AA_JSON_Template_Generic($this->GetId() . "_FileUpload_List", array(
            "view" => "list",
            "scroll" => false,
            "type" => "uploader",
            "css" => array("background" => "transparent")
        )));

        if ($props['bottomLabel']) {
            $template->AddRow(new AA_JSON_Template_Template($this->GetId() . "_FileUpload_BottomLabel", array(
                "template" => "<span style='font-size: smaller; font-style:italic'>" . $props['bottomLabel'] . "</span>",
                "css" => array("background" => "transparent")
            )));
        }

        $this->curRow->AddCol($template);
    }

    //Aggiungi un campo data
    public function AddDateField($name = "", $label = "", $props = array(), $newRow = true)
    {
        $props['timepick'] = false;
        if ($props['format'] == "") $props['format'] = "%Y-%m-%d";
        $props['stringResult'] = true;
        return $this->AddField($name, $label, "datepicker", $props, $newRow);
    }

    //Aggiungi un generico oggetto
    public function AddGenericObject($obj, $newRow = true)
    {
        if ($obj instanceof AA_JSON_Template_Generic) {
            if ($newRow) {
                $this->curRow = new AA_JSON_Template_Layout($this->GetId() . "_Layout_Row");
                $this->layout->AddRow($this->curRow);
            }

            $this->curRow->AddCol($obj);
        }
    }
}

//Template generic  struct dlg
class AA_GenericStructDlg extends AA_GenericWindowTemplate
{
    protected $applyActions = "";
    /**
     * @var mixed
     */
    protected  $targetForm = "";
    public function GetTargetForm()
    {
        return $this->targetForm;
    }

    public function SetApplyActions($actions = "")
    {
        $this->applyActions = $actions;
    }

    protected $applyButton = "";

    public function __construct($id = "", $title = "", $options = null, $applyActions = "", $module = "", $user = null)
    {
        parent::__construct($id, $title, $module);

        if (!($user instanceof AA_User)) $user = AA_User::GetCurrentUser();

        $this->SetWidth("800");
        $this->SetHeight("600");

        $this->applyActions = $applyActions;

        //target Form
        if ($options['targetForm'] != "") $this->targetForm = $options['targetForm'];

        if ($user->IsValid()) {
            $struct = "";
            $userStruct = $user->GetStruct();
            if (is_array($options)) {
                if ($options['showAll'] == 1) {
                    if ($userStruct->GetTipo() <= 0) $struct = AA_Struct::GetStruct(0, 0, 0, $userStruct->GetTipo()); //RAS
                    else $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), 0, 0, $userStruct->GetTipo()); //Altri
                }

                if ($options['showAllDir'] == 1) {
                    $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), 0, 0, $userStruct->GetTipo());
                }

                if ($options['showAllServ'] == 1) {
                    $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), $userStruct->GetDirezione(true), 0, $userStruct->GetTipo());
                }
            }
            if (!($struct instanceof AA_Struct)) $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), $userStruct->GetDirezione(true), $userStruct->GetServizio(true), $userStruct->GetTipo());
        } else {
            $struct = array(array("id" => "root", "value" => "strutture"));
        }

        //Struttura
        $filterLevel = 4;

        if ($options['hideServices']) $filterLevel = 3;

        $tree = new AA_JSON_Template_Tree($this->id . "_Tree", array(
            "data" => $struct->toArray($options),
            "select" => true,
            "filterMode" => array("showSubItems" => false, "level" => $filterLevel, "openParents" => false),
            "template" => "{common.icon()} {common.folder()} <span>#value#</span>"
        ));

        //Filtra in base al testo
        $this->body->AddRow(new AA_JSON_Template_Search($this->id . "_Search_Text", array("placeholder" => "Digita qui per filtrare le strutture")));

        $this->body->AddRow($tree);

        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 10, "css" => array("border-top" => "1px solid #e6f2ff !important;"))));

        //Apply button
        $this->applyButton = new AA_JSON_Template_Generic($this->id . "_Button_Bar_Apply", array("view" => "button", "width" => 80, "label" => "Applica"));

        //Toolbar
        $toolbar = new AA_JSON_Template_Layout($this->id . "_Button_Bar", array("height" => 38));
        $toolbar->addCol(new AA_JSON_Template_Generic("spacer", array("view" => "spacer", "width" => 15)));

        //mostra/nascondi strutture soppresse
        $toolbar->addCol(new AA_JSON_Template_Generic($this->id . "_Switch_Supressed", array("view" => "switch", "width" => 350, "label" => "Strutture soppresse:", "labelWidth" => 150, "onLabel" => "visibili", "offLabel" => "nascoste", "tooltip" => "mostra/nascondi le strutture soppresse")));

        $toolbar->addCol(new AA_JSON_Template_Generic());
        $toolbar->addCol($this->applyButton);
        $toolbar->addCol(new AA_JSON_Template_Generic("spacer", array("view" => "spacer", "width" => 15)));
        $this->body->AddRow($toolbar);
        $this->body->AddRow(new AA_JSON_Template_Generic("spacer", array("view" => "spacer", "height" => 10)));
    }

    protected function Update()
    {
        if ($this->targetForm != "") $this->applyActions .= "; if($$('" . $this->targetForm . "')) { $$('" . $this->targetForm . "').setValues({id_assessorato : AA_MainApp.ui.MainUI.structDlg.lastSelectedItem['id_assessorato'], \"id_direzione\" : AA_MainApp.ui.MainUI.structDlg.lastSelectedItem['id_direzione'], id_servizio : AA_MainApp.ui.MainUI.structDlg.lastSelectedItem['id_servizio'], struct_desc : AA_MainApp.ui.MainUI.structDlg.lastSelectedItem['value'], id_struct_tree_select: AA_MainApp.ui.MainUI.structDlg.lastSelectedItem['id']},true);}";

        $this->applyButton->SetProp("click", "try{AA_MainApp.ui.MainUI.structDlg.lastSelectedItem=$$('" . $this->id . "_Tree').getSelectedItem();" . $this->applyActions . "; $$('" . $this->id . "_Wnd').close()}catch(msg){console.error(msg)}");

        parent::Update();
    }
}

//Template generic  pdfPreview dlg
class AA_GenericPdfPreviewDlg extends AA_GenericWindowTemplate
{
    public function __construct($id = "", $title = "Pdf Viewer", $module = "")
    {
        if ($id == "") $id = "PdfPreviewDlg_" . time();
        parent::__construct($id, $title, $module);

        $this->SetWidth("720");
        $this->SetHeight("576");

        //riquadro di visualizzazione preview pdf
        $this->body->AddRow(new AA_JSON_Template_Template($this->id . "_Pdf_Preview_Box", array("type" => "clean", "template" => "<div id='pdf_preview_box' style='width: 100%; height: 100%'><span style='mdi mdi-spin'></span><span> Caricamento in corso</span></div>")));
    }

    protected function Update()
    {
        parent::Update();
    }
}

//Template generic  logView dlg
class AA_GenericLogDlg extends AA_GenericWindowTemplate
{
    public function __construct($id = "", $title = "Logs", $user = null)
    {
        parent::__construct($id, $title);

        $this->SetWidth("720");
        $this->SetHeight("576");

        //Id oggetto non impostato
        if ($_REQUEST['id'] == "") {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id . "_Log_Box", array("type" => "clean", "template" => "<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>Identificativo oggetto non impostato.</div>")));
            return;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id . "_Log_Box", array("type" => "clean", "template" => "<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>Utente non valido o sessione scaduta.</div>")));
            return;
        }

        $object = AA_Object_V2::Load($_REQUEST['id'], $user, false);

        //Invalid object
        if (!$object->IsValid()) {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id . "_Log_Box", array("type" => "clean", "template" => "<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>Oggetto non valido o permessi insufficienti.</div>")));
            return;
        }

        //permessi insufficienti
        if (($object->GetUserCaps($user) & AA_Const::AA_PERMS_WRITE) == 0) {
            $this->body->AddRow(new AA_JSON_Template_Template($this->id . "_Log_Box", array("type" => "clean", "template" => "<div style='text-align: center;'id='pdf_preview_box' style='width: 100%; height: 100%'>L'utente corrente non ha i permessi per visualizzare i logs dell'oggetto.</div>")));
            return;
        }

        $logs = $object->GetLog();

        $table = new AA_JSON_Template_Generic($id . "_Table", array(
            "view" => "datatable",
            "scrollX" => false,
            "select" => false,
            "columns" => array(
                array("id" => "data", "header" => array("Data", array("content" => "textFilter")), "width" => 150, "css" => array("text-align" => "left")),
                array("id" => "user", "header" => array("<div style='text-align: center'>Utente</div>", array("content" => "selectFilter")), "width" => 120, "css" => array("text-align" => "center")),
                array("id" => "msg", "header" => array("Operazione", array("content" => "selectFilter")), "fillspace" => true, "css" => array("text-align" => "left"))
            ),
            "data" => $logs->GetLog()
        ));

        //riquadro di visualizzazione preview pdf
        $this->body->AddRow($table);
        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 38)));
    }

    protected function Update()
    {
        parent::Update();
    }
}


//Template sezione paginata
class AA_GenericPagedSectionTemplate
{
    //Header box
    protected $header_box = "";
    public function GetHeader()
    {
        return $this->header_box;
    }
    public function SetHeader($obj = "")
    {
        if ($obj instanceof AA_JSON_Template_Generic) $this->header_box = $obj;
    }

    //Content box
    protected $content = null;
    protected $content_box = null;
    public function SetContentBox($obj = "")
    {
        if ($obj instanceof AA_JSON_Template_Generic) $this->content_box = $obj;
    }

    //Content box template
    protected $contentBoxTemplate = "";
    public function SetContentBoxTemplate($template = "")
    {
        $this->contentBoxTemplate = $template;
    }
    public function GetContentBoxTemplate()
    {
        return $this->contentBoxTemplate;
    }

    //Content box data
    protected $contentBoxData = array();
    public function SetContentBoxData($data = array())
    {
        $this->contentBoxData = $data;
    }
    public function GetContentBoxData()
    {
        return $this->contentBoxData;
    }
    protected $contentEnableSelect = false;
    protected $contentEnableMultiSelect = false;
    public function EnableSelect($bVal = true)
    {
        $this->contentEnableSelect = $bVal;
    }
    public function EnableMultiSelect($bVal = true)
    {
        $this->contentEnableMultiSelect = $bVal;
    }
    protected $contentItemsForPage = "5";
    public function SetContentItemsForPage($val = "5")
    {
        $this->contentItemsForPage = $val;
    }
    public function GetContentItemsForPage()
    {
        return $this->contentItemsForPage;
    }
    protected $contentItemHeight = "auto";
    public function SetContentItemHeight($val = "auto")
    {
        $this->contentItemHeight = $val;
    }
    public function GetContentItemHeight()
    {
        return $this->contentItemHeight;
    }

    //Funzioni di rendering
    public function toObject()
    {
        $this->Update();
        return $this->content;
    }
    public function __toString()
    {
        return $this->toObject()->toString();
    }
    public function toArray()
    {
        return $this->toObject()->toArray();
    }
    public function toBase64()
    {
        return $this->toObject()->toBase64();
    }
    #----------------------------------

    //pager box
    protected $pager_box = "";
    public function GetPager()
    {
        return $this->pager_box;
    }
    public function SetPager($obj = "")
    {
        if ($obj instanceof AA_JSON_Template_Generic) $this->pager_box = $obj;
    }

    //Pager title box
    protected $pagerTitle_box = "";
    public function GetPagerTitle()
    {
        return $this->pagerTitle_box;
    }
    public function SetPagerTitle($obj = "")
    {
        if ($obj instanceof AA_JSON_Template_Generic) $this->pagerTitle_box = $obj;
    }

    //Toolbar box
    protected $toolbar_box = "";
    public function GetToolbar()
    {
        return $this->toolbar_box;
    }
    public function SetToolbar($obj = "")
    {
        if ($obj instanceof AA_JSON_Template_Generic) $this->toolbar_box = $obj;
    }

    protected $module = "";
    public function SetModule($id)
    {
        $this->module = $id;
    }
    public function GetModule()
    {
        return $this->module;
    }

    protected $id = "AA_GenericPagedSectionTemplate";

    public function __construct($id = "AA_GenericPagedSectionTemplate", $module = "", $content_box = "")
    {
        $this->module = $module;
        $this->id = $id;
        $this->content_box = $content_box;
        $this->contentBoxTemplate = "<div class='AA_DataView_ItemContent'>"
            . "<div><span class='AA_Label AA_Label_Orange'>#pretitolo#</span></div>"
            . "<div><span class='AA_DataView_ItemTitle'>#denominazione#</span></div>"
            . "<div>#tags#</div>"
            . "<div><span class='AA_DataView_ItemSubTitle'>#sottotitolo#</span></div>"
            . "<div><span class='AA_Label AA_Label_LightBlue' title='Stato elemento'>#stato#</span>&nbsp;<span class='AA_DataView_ItemDetails'>#dettagli#</span></div>"
            . "</div>";
    }

    protected function Update()
    {
        if (!($this->content_box instanceof AA_JSON_Template_Generic)) {
            $module = "AA_MainApp.getModule('" . $this->module . "')";
            if ($this->module == "") $module = "AA_MainApp.curModule";

            $selectionChangeEvent = "try{AA_MainApp.utils.getEventHandler('onSelectChange','" . $this->module . "','" . $this->id . "_List_Box')}catch(msg){console.error(msg)}";
            $onDblClickEvent = "try{AA_MainApp.utils.getEventHandler('showDetailView','" . $this->module . "','" . $this->id . "_List_Box')}catch(msg){console.error(msg)}";
            if (sizeof($this->contentBoxData) > 0 && $this->contentBoxTemplate != "") {

                $this->content_box = new AA_JSON_Template_Generic($this->id . "_List_Box", array(
                    "view" => "dataview",
                    "paged" => true,
                    "pager_id" => $this->id . "_Pager",
                    "filtered" => $this->filtered,
                    "filter_id" => $this->saveFilterId,
                    "xCount" => 1,
                    "yCount" => $this->contentItemsForPage,
                    "select" => $this->contentEnableSelect,
                    "multiselect" => $this->contentEnableMultiSelect,
                    "toolbar_id" => $this->id . "_Toolbar",
                    "module_id" => $this->module,
                    "type" => array(
                        "type" => "tiles",
                        "height" => $this->contentItemHeight,
                        "width" => "auto",
                        "css" => "AA_DataView_item"
                    ),
                    "template" => $this->contentBoxTemplate,
                    "data" => $this->contentBoxData,
                    "on" => array("onSelectChange" => $selectionChangeEvent, "onItemDblClick" => $onDblClickEvent)
                ));
            } else {
                $this->content_box = new AA_JSON_Template_Template(
                    $this->id . "_List_Box",
                    array(
                        "template" => "<div style='text-align: center'>#contenuto#</div>",
                        "data" => array("contenuto" => "Non sono presenti elementi."),
                        "is_void" => true
                    )
                );
            }
        }

        if ($this->paged || $this->withPager) {
            if ($this->pagerTarget == "") $this->pagerTarget = $this->content_box->GetId();

            if ($this->pagerItemsCount % $this->pagerItemsForPage) $totPages = intVal($this->pagerItemsCount / $this->pagerItemsForPage) + 1;
            else $totPages = intVal($this->pagerItemsCount / $this->pagerItemsForPage);
            if ($totPages == 0) $totPages = 1;

            $pager = new AA_JSON_Template_Generic($this->id . "_Pager", array(
                "view" => "pager",
                "minWidth" => "400",
                "master" => false,
                "size" => $this->pagerItemsForPage,
                "group" => $this->pagerGroup,
                "count" => $this->pagerItemsCount,
                "title_id" => $this->id . "_Pager_Title",
                "module_id" => $this->module,
                "target" => $this->pagerTarget,
                "targetAction" => $this->pagerTargetAction,
                "template" => "<div style='display: flex; justify-content:flex-start; align-items: center; height:100%' pager='" . $this->id . "_Pager'>{common.first()} {common.prev()} {common.pages()} {common.next()} {common.last()}<div>",
                //"on"=>array("onItemClick"=>"try{module=AA_MainApp.getModule('".$this->module."'); if(module.isValid()) module.pagerEventHandler;}catch(msg){console.error(msg)}")
                "on" => array("onItemClick" => "try{AA_MainApp.utils.getEventHandler('pagerEventHandler','$this->module','" . $this->id . "_Content_Box')}catch(msg){console.error(msg)}")
            ));

            $pager_title = new AA_JSON_Template_Generic($this->id . "_Pager_Title", array("view" => "template", "type" => "clean", "minWidth" => "150", "align" => "center", "template" => "<div style='display: flex; justify-content: center; align-items: center; height: 100%; color: #006699;'>Pagina #curPage# di #totPages#</div>", "data" => array("curPage" => ($this->pagerCurPage + 1), "totPages" => $totPages)));
        }

        if ($this->withPager || $this->filtered || $this->saveAsPdfView || $this->saveAsCsvView || $this->trashView || $this->reassignView || $this->publishView || $this->resumeView || $this->detailView) {
            $header_box = new AA_JSON_Template_Layout(
                $this->id . "_Header_box",
                array(
                    "css" => "AA_DataView",
                    "height" => 38
                )
            );

            if ($this->withPager) {
                $header_box->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 10)));
                $header_box->addCol($pager);
                $header_box->AddCol($pager_title);
            } else {
                $header_box->AddCol(new AA_JSON_Template_Generic());
            }

            if ($this->filtered || $this->enableAddNew || $this->saveAsPdfView || $this->saveAsCsvView || $this->trashView || $this->reassignView || $this->publishView || $this->resumeView || $this->detailView) {
                $toolbar = new AA_JSON_Template_Generic($this->id . "_Toolbar", array(
                    "view" => "toolbar",
                    "type" => "clean",
                    "css" => array("background" => "#ebf0fa", "border-color" => "transparent"),
                    "minWidth" => 500
                ));

                $menu_data = array();

                $toolbar->addElement(new AA_JSON_Template_Generic());

                if ($this->filtered && $this->filterDlgTask != "") {
                    if ($this->saveFilterId != "") $saveFilterId = "'" . $this->saveFilterId . "'";
                    else $saveFilterId = "module.getActiveView()";

                    $filterClickAction = "try{module=AA_MainApp.getModule('" . $this->module . "'); if(module.isValid()){module.dlg({task:'" . $this->filterDlgTask . "',postParams: module.getRuntimeValue(" . $saveFilterId . ",'filter_data'), module: '" . $this->module . "'})}}catch(msg){console.error(msg)}";

                    $filter_btn = new AA_JSON_Template_Generic($this->id . "_Filter_btn", array(
                        "view" => "button",
                        "align" => "right",
                        "type" => "icon",
                        "icon" => "mdi mdi-filter",
                        "label" => "Filtra",
                        "width" => 80,
                        "filter_data" => $this->filterData,
                        "tooltip" => "Imposta un filtro di ricerca",
                        "click" => $filterClickAction
                    ));

                    $toolbar->addElement($filter_btn);
                    $toolbar_spacer = true;
                }

                //Aggiunta elementi
                if ($this->enableAddNew && $this->addNewDlgTask != "") {
                    if ($toolbar_spacer) $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 10)));
                    $toolbar_spacer = true;

                    $addnewClickAction = "try{module=AA_MainApp.getModule('" . $this->module . "'); if(module.isValid()){module.dlg({task:'" . $this->addNewDlgTask . "',module:'" . $this->module . "'})}}catch(msg){console.error(msg)}";

                    $addnew_btn = new AA_JSON_Template_Generic($this->id . "_AddNew_btn", array(
                        "view" => "button",
                        "align" => "right",
                        "type" => "icon",
                        "icon" => "mdi mdi-pencil-plus",
                        "label" => "Aggiungi",
                        "width" => 110,
                        "tooltip" => "Aggiungi una nuova bozza",
                        "click" => $addnewClickAction
                    ));

                    $toolbar->addElement($addnew_btn);
                    $toolbar_spacer = true;
                }

                if ($this->detailView) {
                    if ($toolbar_spacer) $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 10)));
                    $toolbar_spacer = true;

                    $toolbar->addElement(new AA_JSON_Template_Generic($this->id . "_Detail_btn", array(
                        "view" => "button",
                        "css" => "AA_Detail_btn",
                        "type" => "icon",
                        "icon" => "mdi mdi-text-box-search",
                        "label" => "Dettagli",
                        "enableOnItemSelected" => true,
                        "align" => "right",
                        "width" => 100,
                        "disabled" => !$this->detailEnable,
                        "tooltip" => "Visualizza i dettagli dell'elemento selezionato",
                        "click" => "AA_MainApp.utils.callHandler('showDetailView',$$('" . $this->id . "_List_Box').getSelectedItem(),'" . $this->module . "','" . $this->id . "_Content_Box')"
                    )));
                }

                if ($this->reassignView || $this->publishView || $this->resumeView) {
                    $menu_spacer = true;

                    if ($this->publishView) {
                        $this->publishHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_Publish",
                            "value" => "Pubblica",
                            "tooltip" => "Pubblica gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                            "icon" => "mdi mdi-certificate",
                            "module_id" => $this->module,
                            "handler" => $this->publishHandler,
                            "handler_params" => $this->publishHandlerParams

                        );
                    }

                    if ($this->reassignView) {
                        $this->reassignHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_Reassign",
                            "value" => "Riassegna",
                            "tooltip" => "Riassegna gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) ad altra struttura",
                            "icon" => "mdi mdi-share-all",
                            "module_id" => $this->module,
                            "handler" => $this->reassignHandler,
                            "handler_params" => $this->reassignHandlerParams

                        );
                    }

                    if ($this->resumeView) {
                        $this->resumeHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_Resume",
                            "value" => "Ripristina",
                            "tooltip" => "Ripristina gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                            "icon" => "mdi mdi-recycle",
                            "module_id" => $this->module,
                            "handler" => $this->resumeHandler,
                            "handler_params" => $this->resumeHandlerParams
                        );
                    }
                }

                if ($this->saveAsPdfView || $this->saveAsCsvView) {
                    if ($menu_spacer) $menu_data[] = array("\$template" => "Separator");
                    $menu_spacer = true;

                    if ($this->saveAsPdfView) {
                        $this->saveAsPdfHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_SaveAsPdf",
                            "value" => "Esporta in pdf",
                            "tooltip" => "Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file pdf",
                            "icon" => "mdi mdi-file-pdf",
                            "module_id" => $this->module,
                            "handler" => $this->saveAsPdfHandler, //"defaultHandlers.saveAsPdf",
                            "handler_params" => $this->saveAsPdfHandlerParams, //array($this->id."_Content_Box",true)
                        );
                    }

                    if ($this->saveAsCsvView) {
                        $this->saveAsCsvHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_SaveAsCsv",
                            "value" => "Esporta in csv",
                            "tooltip" => "Esporta gli elementi selezionati (tutta la lista se non ci sono elementi selezionati) come file csv",
                            "icon" => "mdi mdi-file-table",
                            "module_id" => $this->module,
                            "handler" => $this->saveAsCsvHandler,
                            "handler_params" => $this->saveAsCsvHandlerParams //array($this->id."_Content_Box",true)
                        );
                    }
                }

                if ($this->deleteView || $this->trashView) {
                    if ($menu_spacer) $menu_data[] = array("\$template" => "Separator");
                    $menu_spacer = true;

                    if ($this->trashView) {

                        $this->trashHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_Trash",
                            "value" => "Cestina",
                            "css" => "AA_Menu_Red",
                            "tooltip" => "Cestina gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                            "icon" => "mdi mdi-trash-can",
                            "module_id" => $this->module,
                            "handler" => $this->trashHandler,
                            "handler_params" => $this->trashHandlerParams
                        );
                    }

                    if ($this->deleteView) {
                        $this->deleteHandlerParams["list_id"] = $this->id . "_List_Box";

                        $menu_data[] = array(
                            "id" => $this->id . "_Delete",
                            "value" => "Elimina",
                            "css" => "AA_Menu_Red",
                            "tooltip" => "Elimina definitivamente gli elementi selezionati (tutta la lista se non ci sono elementi selezionati)",
                            "icon" => "mdi mdi-trash-can",
                            "module_id" => $this->module,
                            "handler" => $this->deleteHandler,
                            "handler_params" => $this->deleteHandlerParams
                        );
                    }
                }

                if ($toolbar_spacer) $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 10)));
                $toolbar_spacer = true;

                //Azioni
                $scriptAzioni = "try{"
                    . "let azioni_btn=$$('" . $this->id . "_Azioni_btn');"
                    . "if(azioni_btn){"
                    . "let azioni_menu=webix.ui(azioni_btn.config.menu_data);"
                    . "if(azioni_menu){"
                    . "azioni_menu.setContext(azioni_btn);"
                    . "azioni_menu.show(azioni_btn.\$view);"
                    . "}"
                    . "}"
                    . "}catch(msg){console.error('" . $this->id . "_Azioni_btn'.this,msg);AA_MainApp.ui.alert(msg);}";
                $azioni_btn = new AA_JSON_Template_Generic($this->id . "_Azioni_btn", array(
                    "view" => "button",
                    "type" => "icon",
                    "icon" => "mdi mdi-dots-vertical",
                    "label" => "Azioni",
                    "align" => "right",
                    "disabled" => $this->content_box->GetProp("is_void"),
                    "width" => 90,
                    "menu_data" => new AA_JSON_Template_Generic($this->id . "_ActionMenu", array("view" => "contextmenu", "data" => $menu_data, "module_id" => $this->module, "on" => array("onItemClick" => "AA_MainApp.utils.getEventHandler('onMenuItemClick','$this->module')"))),
                    "tooltip" => "Visualizza le azioni disponibili",
                    "click" => $scriptAzioni
                ));

                $toolbar->addElement($azioni_btn);

                $header_box->addCol($toolbar);
                $header_box->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 10)));
            } else {
                $header_box->AddCol(new AA_JSON_Template_Generic());
            }
        }

        $this->content = new AA_JSON_Template_Layout($this->id . "_Content_Box", array(
            "update_time" => Date("Y-m-d H:i:s"),
            "paged" => $this->paged,
            "filtered" => $this->filtered,
            "filter_id" => $this->saveFilterId,
            "list_view_id" => $this->id . "_List_Box",
            "name" => $this->sectionName
        ));

        if ($this->paged || $this->withPager) {
            $this->content->SetProp("pager_id", $this->id . "_Pager");
        }

        if ($header_box) $this->content->AddRow($header_box);
        $this->content->AddRow($this->content_box);
    }

    //Nome della sezione
    protected $sectionName = "Titolo";
    public function SetSectionName($val = "Titolo")
    {
        $this->sectionName = $val;
    }
    public function GetSectionName()
    {
        return $this->sectionName;
    }

    //Gestione paginazione
    protected $paged = false;
    protected $withPager = false;
    protected $pagerCurPage = 0;
    protected $pagerItemsForPage = 10;
    protected $pagerGroup = 5;
    protected $pagerItemsCount = 10;
    protected $pagerTarget = "";
    protected $pagerTargetAction = "refreshData";

    public function EnablePaging($bVal = true)
    {
        $this->paged = $bVal;
    }

    public function EnablePager($bVal = true)
    {
        $this->withPager = $bVal;
    }
    public function DisablePaging()
    {
        $this->paged = false;
    }
    public function IsPaged()
    {
        return $this->paged;
    }
    public function SetPagerCurPage($page = 0)
    {
        $this->pagerCurPage = $page;
    }
    public function SetPagerItemForPage($var = 10)
    {
        $this->pagerItemsForPage = $var;
    }
    public function SetPagerItemCount($var = 10)
    {
        $this->pagerItemsCount = $var;
    }
    public function SetPagerTarget($var = "")
    {
        $this->pagerTarget = $var;
    }
    public function SetPagerTargetAction($var = "")
    {
        $this->pagerTargetAction = $var;
    }

    #-----------------------------------------

    //Gestione filtraggio
    protected $filtered = false;
    public function EnableFiltering()
    {
        $this->filtered = true;
    }
    public function DisableFiltering()
    {
        $this->filtered = false;
    }
    public function IsFiltered()
    {
        return $this->filtered;
    }

    protected $saveFilterId = "";
    public function SetSaveFilterId($id = "")
    {
        $this->saveFilterId = $id;
    }
    public function GetSaveFilterId()
    {
        return $this->saveFilterId;
    }

    protected $filterData = array();
    public function SetFilterData($data = array())
    {
        $this->filterData = $data;
    }
    public function GetFilterData()
    {
        return $this->filterData;
    }

    protected $filterDlgTask = "";
    public function SetFilterDlgTask($var = "")
    {
        $this->filterDlgTask = $var;
    }
    public function GetFilterDlgTask()
    {
        return $this->filterDlgTask;
    }

    //Gestione aggiunta
    protected $enableAddNew = false;
    public function EnableAddNew($bVal = true)
    {
        $this->enableAddNew = $bVal;
    }
    protected $addNewDlgTask = "";
    public function SetAddNewDlgTask($task = "")
    {
        $this->addNewDlgTask = $task;
    }
    #----------------------------

    //Dettaglio
    protected $detailView = false;
    protected $detailEnable = false;
    public function ViewDetail($bVal = true)
    {
        $this->detailView = $bVal;
    }
    public function HideDetail()
    {
        $this->detailEnable = false;
    }
    public function EnableDetail($bVal = true)
    {
        $this->detailEnable = $bVal;
    }
    public function DisableDetail()
    {
        $this->detailEnable = false;
    }

    //cestino
    protected $trashView = false;
    protected $trashEnable = false;
    protected $trashHandler = "sectionActionMenu.trash";
    protected $trashHandlerParams = "";
    public function ViewTrash($bVal = true)
    {
        $this->trashView = $bVal;
    }
    public function HideTrash()
    {
        $this->trashView = false;
    }
    public function EnableTrash($bVal = true)
    {
        $this->trashEnable = $bVal;
    }
    public function DisableTrash()
    {
        $this->trashEnable = false;
    }
    public function SetTrashHandler($handler = null, $params = null)
    {
        $this->trashHandler = $handler;
        if ($params) $this->trashHandlerParams = $params;
    }
    public function SetTrashHandlerParams($params = null)
    {
        $this->trashHandlerParams = $params;
    }
    #-----------------------------

    //elimina
    protected $deleteView = false;
    protected $deleteEnable = false;
    public function ViewDelete($bVal = true)
    {
        $this->deleteView = $bVal;
    }
    public function HideDelete()
    {
        $this->deleteView = false;
    }
    public function EnableDelete($bVal = true)
    {
        $this->deleteEnable = $bVal;
    }
    public function DisableDelete()
    {
        $this->deleteEnable = false;
    }
    protected $deleteHandler = "sectionActionMenu.delete";
    protected $deleteHandlerParams = "";
    public function SetDeleteHandler($handler = null, $params = null)
    {
        $this->deleteHandler = $handler;
        if ($params) $this->deleteHandlerParams = $params;
    }
    public function SetDeleteHandlerParams($params = null)
    {
        $this->deleteHandlerParams = $params;
    }

    //riassegna
    protected $reassignView = false;
    protected $reassignEnable = false;
    public function ViewReassign($bVal = true)
    {
        $this->reassignView = $bVal;
    }
    public function HideReassign()
    {
        $this->reassignView = false;
    }
    public function EnableReassign($bVal = true)
    {
        $this->reassignEnable = $bVal;
    }
    public function DisableReassign()
    {
        $this->reassignEnable = false;
    }
    protected $reassignHandler = "sectionActionMenu.reassign";
    protected $reassignHandlerParams = "";
    public function SetReassignHandler($handler = null, $params = null)
    {
        $this->reassignHandler = $handler;
        if ($params) $this->reassignHandlerParams = $params;
    }
    public function SetReassignHandlerParams($params = null)
    {
        $this->reassignHandlerParams = $params;
    }
    #------------------------------------------------

    //Ripristina
    protected $resumeView = false;
    protected $resumeEnable = false;
    public function ViewResume($bVal = true)
    {
        $this->resumeView = $bVal;
    }
    public function HideResume()
    {
        $this->resumeView = false;
    }
    public function EnableResume($bVal = true)
    {
        $this->resumeEnable = $bVal;
    }
    public function DisableResume()
    {
        $this->resumeEnable = false;
    }
    protected $resumeHandler = "sectionActionMenu.resume";
    protected $resumeHandlerParams = "";
    public function SetResumeHandler($handler = null, $params = null)
    {
        $this->resumeHandler = $handler;
        if ($params) $this->resumeHandlerParams = $params;
    }
    public function SetResumeHandlerParams($params = null)
    {
        $this->resumeHandlerParams = $params;
    }
    #---------------------------------------

    //pubblica
    protected $publishEnable = false;
    protected $publishView = false;
    public function ViewPublish($bVal = true)
    {
        $this->publishView = $bVal;
    }
    public function HidePublish()
    {
        $this->publishView = false;
    }
    public function EnablePublish($bVal = true)
    {
        $this->publishEnable = $bVal;
    }
    public function DisablePublish()
    {
        $this->publishEnable = false;
    }
    protected $publishHandler = "sectionActionMenu.publish";
    protected $publishHandlerParams = "";
    public function SetPublishHandler($handler = null, $params = null)
    {
        $this->publishHandler = $handler;
        if ($params) $this->publishHandlerParams = $params;
    }
    public function SetPublishHandlerParams($params = null)
    {
        $this->publishHandlerParams = $params;
    }
    #--------------------------------------------

    //Gestione export
    protected $saveAsPdfEnable = false;
    protected $saveAsPdfView = false;
    public function ViewSaveAsPdf($bVal = true)
    {
        $this->saveAsPdfView = $bVal;
    }
    public function HideSaveAsPdf()
    {
        $this->saveAsPdfView = false;
    }
    public function EnableSaveAsPdf($bVal = true)
    {
        $this->saveAsPdfEnable = $bVal;
    }
    public function DisableSaveAsPdf()
    {
        $this->saveAsPdfEnable = false;
    }

    protected $saveAsCsvEnable = false;
    protected $saveAsCsvView = false;
    public function ViewSaveAsCsv($bVal = true)
    {
        $this->saveAsCsvView = $bVal;
    }
    public function HideSaveAsCsv()
    {
        $this->ViewSaveAsCsv(false);
    }
    public function EnableSaveAsCsv($bVal = true)
    {
        $this->saveAsCsvEnable = $bVal;
    }
    public function DisableSaveAsCsv()
    {
        $this->EnableSaveAsCsv(false);
    }
    public function ViewExportFunctions($bVal = true)
    {
        $this->saveAsCsvView = $bVal;
        $this->saveAsPdfView = $bVal;
    }
    public function HideExportFunctions()
    {
        $this->ViewExportFunctions(false);
    }
    public function EnableExportFunctions($bVal = true)
    {
        $this->saveAsCsvEnable = $bVal;
        $this->saveAsPdfEnable = $bVal;
    }
    public function DisableExportFunctions()
    {
        $this->EnableExportFunctions(false);
    }
    protected $saveAsPdfHandler = "sectionActionMenu.saveAsPdf";
    protected $saveAsPdfHandlerParams = "";
    public function SetSaveAsPdfHandler($handler = null, $params = null)
    {
        $this->saveAsPdfHandler = $handler;
        if ($params) $this->saveAsPdfHandlerParams = $params;
    }
    public function SetSaveAsPdfHandlerParams($params = null)
    {
        $this->saveAsPdfHandlerParams = $params;
    }
    protected $saveAsCsvHandler = "sectionActionMenu.saveAsCsv";
    protected $saveAsCsvHandlerParams = "";
    public function SetSaveAsCsvHandler($handler = null, $params = null)
    {
        $this->saveAsCsvHandler = $handler;
        if ($params) $this->saveAsCsvHandlerParams = $params;
    }
    public function SetSaveAsCsvHandlerParams($params = null)
    {
        $this->saveAsCsvHandlerParams = $params;
    }
    #-------------------------------
}

//Template generico JSON webix
class AA_JSON_Template_Generic
{
    //Restituisce la reppresentazione dell'oggetto come una una stringa
    public function __toString()
    {
        //Restituisce l'oggetto come stringa
        return json_encode($this->toArray());
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function toBase64()
    {
        return base64_encode($this->__toString());
    }

    public function toArray()
    {
        $result = array();

        //Proprietà
        foreach ($this->props as $key => $prop) {
            if ($prop instanceof AA_JSON_Template_Generic) $result[$key] = $prop->toArray();
            else $result[$key] = $prop;
        }

        //rows
        if (is_array($this->rows)) {
            $result['rows'] = array();
            foreach ($this->rows as $curRow) {
                $result['rows'][] = $curRow->toArray();
            }
        }

        //cols
        if (is_array($this->cols)) {
            $result['cols'] = array();
            foreach ($this->cols as $curCol) {
                $result['cols'][] = $curCol->toArray();
            }
        }
        if ($result['view'] == "layout" && !is_array($result['rows']) && !is_array($result['cols'])) $result['rows'] = array(array("view" => "spacer"));

        //cells
        if (is_array($this->cells)) {
            $result['cells'] = array();
            foreach ($this->cells as $curCell) {
                $result['cells'][] = $curCell->toArray();
            }
        }
        if ($result['view'] == "multiview" && !is_array($result['cells'])) $result['cells'] = array(array("view" => "spacer"));

        //elements
        if (is_array($this->elements)) {
            $result['elements'] = array();
            foreach ($this->elements as $curCell) {
                $result['elements'][] = $curCell->toArray();
            }
        }
        if ($result['view'] == "toolbar" && !is_array($result['elements'])) $result['elements'] = array(array("view" => "spacer"));

        //bodyRows
        if (is_array($this->bodyRows) || is_array($this->bodyCols)) {
            $result['body'] = array();
            if (is_array($this->bodyRows)) {
                foreach ($this->bodyRows as $curBodyRow) {
                    if (!is_array($result['body']['rows'])) $result['body']['rows'] = array();
                    $result['body']['rows'][] = $curBodyRow->toArray();
                }
            }

            if (is_array($this->bodyCols)) {
                foreach ($this->bodyCols as $curBodyCol) {
                    if (!is_array($result['body']['cols'])) $result['body']['cols'] = array();
                    $result['body']['cols'][] = $curBodyCol->toArray();
                }
            }
        }

        //Restituisce l'oggetto come array
        return $result;
    }

    protected $props = array();
    public function SetProp($prop = "", $value = "")
    {
        $this->props[$prop] = $value;
    }
    public function GetProp($prop)
    {
        return $this->props[$prop];
    }

    //Aggiunta righe
    protected $rows = null;
    public function addRow($row = null)
    {
        if ($row instanceof AA_JSON_Template_Generic) {
            //AA_Log::Log(__METHOD__." ".$row->toString(),100);

            if (!is_array($this->rows)) $this->rows = array();
            $this->rows[] = $row;
        }
    }

    //Aggiunta row al body
    protected $bodyRows = null;
    public function addRowToBody($row = null)
    {
        if ($row instanceof AA_JSON_Template_Generic) {
            //AA_Log::Log(__METHOD__." ".$row->toString(),100);

            if (!is_array($this->bodyRows)) $this->bodyRows = array();
            $this->bodyRows[] = $row;
        }
    }

    //Aggiunta col al body
    protected $bodyCols = null;
    public function addColToBody($col = null)
    {
        if ($col instanceof AA_JSON_Template_Generic) {
            //AA_Log::Log(__METHOD__." ".$row->toString(),100);

            if (!is_array($this->bodyCols)) $this->bodyCols = array();
            $this->bodyCols[] = $col;
        }
    }

    //Aggiunta colonne
    protected $cols = null;
    public function addCol($col = null)
    {
        if ($col instanceof AA_JSON_Template_Generic) {
            if (!is_array($this->cols)) $this->cols = array();
            $this->cols[] = $col;
        }
    }

    //Aggiunta celle
    protected $cells = null;
    public function addCell($cell = null, $bFromHead = false)
    {
        if ($cell instanceof AA_JSON_Template_Generic) {
            if (!is_array($this->cells)) $this->cells = array();
            if (!$bFromHead) $this->cells[] = $cell;
            else array_unshift($this->cells, $cell);
        }
    }

    //Aggiunta elementi
    protected $elements = null;
    public function addElement($obj = null)
    {
        if ($obj instanceof AA_JSON_Template_Generic) {
            if (!is_array($this->elements)) $this->elements = array();
            $this->elements[] = $obj;
        }
    }
    public function __construct($id = "", $props = null)
    {
        if ($id != "") $this->props["id"] = $id;
        if (is_array($props)) {
            foreach ($props as $key => $value) {
                $this->props[$key] = $value;
            }
        }
    }

    public function GetId()
    {
        return $this->props['id'];
    }
}

//Classe per la gestione delle multiviste
class AA_JSON_Template_Multiview extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "multiview";
        if ($id == "") $id = "AA_JSON_TEMPLATE_MULTIVIEW";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei layout
class AA_JSON_Template_Layout extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "layout";
        if ($id == "") $id = "AA_JSON_TEMPLATE_LAYOUT";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione delle toolbar
class AA_JSON_Template_Toolbar extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "toolbar";
        if ($id == "") $id = "AA_JSON_TEMPLATE_TOOLBAR";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione delle tree view
class AA_JSON_Template_Search extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "search";
        if ($id == "") $id = "AA_JSON_TEMPLATE_SEARCH";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione delle date
class AA_JSON_Template_Datepicker extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "datepicker";
        if ($id == "") $id = "AA_JSON_TEMPLATE_DATEPICKER";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione delle tree view
class AA_JSON_Template_Tree extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "tree";
        if ($id == "") $id = "AA_JSON_TEMPLATE_TREE";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei template
class AA_JSON_Template_Template extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "template";
        if ($id == "") $id = "AA_JSON_TEMPLATE_TEMPLATE";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei checkbox
class AA_JSON_Template_Checkbox extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "checkbox";
        if ($id == "") $id = "AA_JSON_TEMPLATE_CHECKBOX";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei switch
class AA_JSON_Template_Switch extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "switch";
        if ($id == "") $id = "AA_JSON_TEMPLATE_SWITCH";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei campi di testo
class AA_JSON_Template_Text extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "text";
        if ($id == "") $id = "AA_JSON_TEMPLATE_TEMPLATE";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei campi di testo
class AA_JSON_Template_Richtext extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "richtext";
        if ($id == "") $id = "AA_JSON_TEMPLATE_RICHTEXT";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei campi di testo
class AA_JSON_Template_Select extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "richselect";
        if ($id == "") $id = "AA_JSON_TEMPLATE_RICHSELECT";

        parent::__construct($id, $props);
    }
}



//Classe per la gestione dei campi radio
class AA_JSON_Template_Radio extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "radio";
        if ($id == "") $id = "AA_JSON_TEMPLATE_RADIO";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione del textarea
class AA_JSON_Template_Textarea extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "textarea";
        if ($id == "") $id = "AA_JSON_TEMPLATE_TEMPLATE";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione dei form
class AA_JSON_Template_Form extends AA_JSON_Template_Generic
{
    public function __construct($id = "", $props = null)
    {
        $this->props["view"] = "form";
        if ($id == "") $id = "AA_JSON_TEMPLATE_FORM";

        parent::__construct($id, $props);
    }
}

//Classe per la gestione delle variabili di sessione
class AA_SessionVar
{
    protected $name = "varName";
    public function GetName()
    {
        return $this->name;
    }

    protected $value = null;
    public function GetValue()
    {
        return $this->value;
    }

    protected $bValid = false;
    public function IsValid()
    {
        return $this->bValid;
    }

    //Costruttore
    protected function __construct($name = "varName", $value = "")
    {
        $this->name = $name;
        $this->value = $value;

        if ($this->name != "") {
            $this->bValid = true;
        }
    }

    static public function Get($name = "varName")
    {
        if (isset($_SESSION['SessionVars'][$name]) && $name != "") {
            return new AA_SessionVar($name, unserialize($_SESSION['SessionVars'][$name]));
        } else return new AA_SessionVar();
    }

    static public function Set($name = "varName", $value = "")
    {
        if ($name != "" && $value != "") {
            $var = json_decode($value);
            if (is_string($value)) {
                if (json_last_error() === JSON_ERROR_NONE) {
                    $_SESSION['SessionVars'][$name] = serialize($var);

                    //AA_Log::Log(__METHOD__." - name:".$name." - value: ".print_r($var,true),100);
                } else $_SESSION['SessionVars'][$name] = $value;
            } else {
                $_SESSION['SessionVars'][$name] = serialize($var);
                //AA_Log::Log(__METHOD__." - name:".$name." - value: ".print_r($var,true),100);
            }

            return true;
        } else return false;
    }
}

//Classe per la gestione delle variabili di sessione
class AA_SessionFileUpload
{
    protected $id = "file";
    public function GetId()
    {
        return $this->id;
    }

    protected $value = "";
    public function GetValue()
    {
        return $this->value;
    }

    protected $bValid = false;
    public function IsValid()
    {
        return $this->bValid;
    }

    protected function __construct($id = "", $value = "")
    {
        $this->value = $value;
        $this->id = $id;

        if (is_array($value)) {
            if (is_file($value['tmp_name'])) {
                $this->bValid = true;

                //AA_Log::Log(__METHOD__." file: ".$curFile['tmp_name']);
            }
        }
    }

    static public function Get($id = "")
    {
        if (isset($_SESSION['SessionFiles']) && $id != "") {
            $files = unserialize($_SESSION['SessionFiles']);

            //AA_Log::Log(__METHOD__." - SessionFiles: ".$_SESSION['SessionFiles'],100);

            if (isset($files[$id])) return new AA_SessionFileUpload($id, $files[$id]);
        }

        return new AA_SessionFileUpload();
    }

    static public function Add($id = "", $value = "")
    {
        if ($id == "" || !is_array($value)) return false;

        $sessionFiles = unserialize($_SESSION['SessionFiles']);

        if (!is_array($sessionFiles)) {
            $sessionFiles = array();
        }

        if (is_file($value['tmp_name'])) {
            $dir = DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . "session_files";
            if (!is_dir($dir)) {
                mkdir($dir);
            }

            $filename = $dir . DIRECTORY_SEPARATOR . session_id() . "_" . Date("Ymdhis");

            if (move_uploaded_file($value['tmp_name'], $filename)) {
                $value['tmp_name'] = $filename;
                $sessionFiles[$id] = $value;

                $_SESSION['SessionFiles'] = serialize($sessionFiles);

                //AA_Log::Log(__METHOD__." - SessionFiles: ".$_SESSION['SessionFiles'],100);

                return $value;
            } else return false;
        }

        return false;
    }
}

//AA_Object v2
class AA_Object_V2
{
    //identificativo
    protected $nId = 0;
    public function GetId()
    {
        return $this->nId;
    }
    protected function SetId($var = 0)
    {
        $this->nId = $var;

        $this->bChanged = true;

        return true;
    }

    //stato
    protected $nStatus = 1;
    public function GetStatus()
    {
        return $this->nStatus;
    }
    protected function SetStatus($var = 0)
    {
        if (($var & $this->nStatusMask) > 0) {
            $this->nStatus = $var & $this->nStatusMask;

            $this->bChanged = true;

            return true;
        }

        return false;
    }

    //aggiornamento
    protected $sAggiornamento = "";
    public function GetAggiornamento()
    {
        return $this->sAggiornamento;
    }

    //nome
    protected $sName = "";
    public function GetName()
    {
        return $this->sName;
    }
    protected function SetName($var = "")
    {
        $this->sName = $var;
    }

    //Descrizione
    protected $sDescr = "";
    public function GetDescr()
    {
        return $this->sDescr;
    }
    protected function SetDescr($var = "")
    {
        $this->sDescr = $var;
    }

    //maschera degli stati possibili
    protected $nStatusMask = AA_Const::AA_STATUS_ALL;
    public function GetStatusMask()
    {
        return $this->nStatusMask;
    }
    protected function SetStatusMask($var = AA_Const::AA_STATUS_ALL)
    {
        $this->nStatusMask = AA_Const::AA_STATUS_ALL & $var;

        $this->bChanged = true;

        return true;
    }

    //Abilita o disabilita le revisioni
    public function EnableRevision($var = true)
    {
        if ($var) $this->nStatusMask |= AA_Const::AA_STATUS_REVISIONATA;
        else $this->nStatusMask = AA_Const::AA_STATUS_ALL - AA_Const::AA_STATUS_REVISIONATA;
    }

    //Tabella dati
    const AA_DBTABLE_DATA = "";

    //id data object
    protected $nId_Data = 0;
    public function GetIdData()
    {
        return $this->nId_Data;
    }
    protected function SetIdData($var = 0)
    {
        if ($var >= 0) {
            $this->nId_Data = $var;

            $this->bChanged = true;

            return true;
        }

        return false;
    }

    //id data rev object
    protected $nId_Data_Rev = 0;
    public function GetIdDataRev()
    {
        return $this->nId_Data_Rev;
    }
    protected function SetIdDataRev($var = 0)
    {
        if ($var >= 0) {
            $this->nId_Data_Rev = $var;

            $this->bChanged = true;

            return true;
        }

        return false;
    }

    //Class
    protected $sClass = "AA_Object_V2";
    public function GetClass()
    {
        return $this->sClass;
    }
    protected function SetClass($var = "AA_Object_V2")
    {
        if (class_exists($var)) {
            $this->sClass = $var;

            $this->bChanged = true;

            return true;
        }

        return false;
    }

    //------------------- Log -----------------
    protected $sLog = "";
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

    //Gestione struttura
    protected $oStruct = null;
    public function GetStruct()
    {
        if ($this->oStruct instanceof AA_Struct) return $this->oStruct;
        else return AA_Struct::GetStruct(0, 0, 0);
    }
    public function SetStruct($var = null)
    {
        if ($var instanceof AA_Struct) {
            $this->oStruct = $var;

            $this->bChanged = true;

            return true;
        }

        return false;
    }
    #-----------------------------------------------------

    //Flag di variazione
    protected $bChanged = false;
    public function IsChanged()
    {
        return $this->bChanged;
    }
    protected function SetChanged($var = true)
    {
        if ($var == true) $this->bChanged = true;
        else $this->bChanged = false;
    }

    protected static function SaveToDb($object = null, $user = null, $bSaveData = false)
    {
        //AA_Log::Log(__METHOD__);

        if (!($object instanceof AA_Object_V2)) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        if (!$object->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        if (!$object->IsChanged()) return true;

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        //Verifica solo se oggetto diretto di classe "AA_Object_V2" 
        $object_class = get_class($object);

        //Verifica permessi
        if ($object->GetId() > 0) //Oggetto esistente
        {
            $originalObject = new $object_class($object->GetId(), $user);
            if ($originalObject->isValid()) {
                $orgPerms = $originalObject->GetUserCaps($user);
                $perms = $object->GetUserCaps($user);

                $objStatus = $object->GetStatus();
                $orgObjStatus = $originalObject->GetStatus();

                //Cambio di stato
                if ($objStatus != $orgObjStatus) {
                    if (($orgObjStatus & AA_Const::AA_STATUS_BOZZA) > 0) {
                        //pubblicazione
                        if (($objStatus & AA_Const::AA_STATUS_PUBBLICATA) > 0 && (($perms & AA_Const::AA_PERMS_WRITE) == 0 || ($orgPerms & AA_Const::AA_PERMS_PUBLISH) == 0)) {
                            AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                            return false;
                        }

                        //cestinazione eliminazione
                        if (($objStatus & AA_Const::AA_STATUS_CESTINATA) > 0 && (($perms & AA_Const::AA_PERMS_DELETE) == 0 || ($orgPerms & AA_Const::AA_PERMS_DELETE) == 0)) {
                            AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                            return false;
                        }
                    } else {
                        //cestinazione eliminazione
                        if (($objStatus & AA_Const::AA_STATUS_CESTINATA) > 0 && (($perms & AA_Const::AA_PERMS_DELETE) == 0 || ($orgPerms & AA_Const::AA_PERMS_DELETE) == 0)) {
                            AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                            return false;
                        }

                        //revisione
                        if (($objStatus & AA_Const::AA_STATUS_REVISIONATA) > 0 && (($perms & AA_Const::AA_PERMS_WRITE) == 0 || ($orgPerms & AA_Const::AA_PERMS_WRITE) == 0)) {
                            AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                            return false;
                        }

                        //pubblicazione revisioni
                        if (($objStatus & AA_Const::AA_STATUS_REVISIONATA) == 0 && ($orgObjStatus & AA_Const::AA_STATUS_REVISIONATA) > 0  && (($perms & AA_Const::AA_PERMS_PUBLISH) == 0 || ($orgPerms & AA_Const::AA_PERMS_PUBLISH) == 0)) {
                            AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                            return false;
                        }
                    }
                } else {
                    //Modifica generica
                    if (($perms & AA_Const::AA_PERMS_WRITE) == 0 || ($orgPerms & AA_Const::AA_PERMS_WRITE) == 0) {
                        AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                        return false;
                    }
                }
            } else {
                AA_Log::Log(__METHOD__ . " - Oggetto originale non valido.", 100);
                return false;
            }

            //Tiene conto della revisione
            if ($object->GetDbDataTable() != "" && ($object->GetStatusMask() & AA_Const::AA_STATUS_REVISIONATA) > 0 && $object->GetId() > 0) {
                //Revisione
                if (($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) > 0 && $object->GetIdDataRev() == 0) {
                    $newIdData = $object->CloneData($object->nId_Data);
                    if ($newIdData > 0) {
                        $object->nId_Data_Rev = $object->nId_Data;
                        $object->nId_Data = $newIdData;
                    } else {
                        AA_Log::Log(__METHOD__ . " - ERRORE nel revisionamento dei dati", 100);
                        return false;
                    }
                }

                //pubblicazione Revisione
                if (($object->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) == 0 && $object->GetIdDataRev() > 0) {
                    if ($object->nId_Data > 0) {
                        if ($object->DeleteData($object->nId_Data)) {
                            $object->nId_Data = $object->nId_Data_Rev;
                            $object->nId_Data_Rev = 0;
                        } else {
                            AA_Log::Log(__METHOD__ . " - ERRORE nell'eliminazione dei dati clonati.", 100);
                            return false;
                        }
                    }
                }
            }
        } else {
            $object->SetStatus(AA_Const::AA_STATUS_BOZZA);
            $perms = $object->GetUserCaps($user);

            //Modifica generica
            if (($perms & AA_Const::AA_PERMS_WRITE) == 0) {
                AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha sufficienti permessi per salvare le modifiche all'oggetto: " . $object->GetName(), 100);
                return false;
            }

            /*
            //cestinazione
            if(($objStatus&AA_Const::AA_STATUS_CESTINATA) > 0 && ($perms&AA_Const::AA_PERMS_DELETE) == 0)
            {
                AA_Log::Log(__METHOD__." - L'utente corrente: ".$user->GetUsername()." non ha sufficienti permessi per salvare le modifiche all'oggetto: ".$object->GetName(),100);
                return false;
            }

            //pubblicazione
            if(($objStatus&AA_Const::AA_STATUS_PUBBLICATA) > 0 && ($perms&AA_Const::AA_PERMS_PUBLISH) == 0)
            {
                AA_Log::Log(__METHOD__." - L'utente corrente: ".$user->GetUsername()." non ha sufficienti permessi per salvare le modifiche all'oggetto: ".$object->GetName(),100);
                return false;
            }*/
        }

        $db = new AA_Database();
        $where = "";
        $query = "";
        $sep = "";

        //Aggiorna la tabella dati se è impostata
        if ($bSaveData) {
            if (!$object->SaveData()) {
                AA_Log::Log(__METHOD__ . " - Errore nel salvataggio dei dati collegati all'oggetto.", 100);
                return false;
            }
        }

        //Salvataggio sul db        
        if ($object->GetId() == 0) {
            $query = "INSERT INTO " . AA_Const::AA_DBTABLE_OBJECTS . " SET ";
            $where = "";
        } else {
            $query = "UPDATE " . AA_Const::AA_DBTABLE_OBJECTS . " SET ";
            $where = " WHERE " . AA_Const::AA_DBTABLE_OBJECTS . ".id='" . addslashes($object->GetId()) . "' LIMIT 1";
        }

        $struct = $object->GetStruct();

        $query .= " id_data='" . $object->GetIdData() . "'";
        $query .= ", id_data_rev='" . $object->GetIdDataRev() . "'";
        $query .= ", status='" . $object->GetStatus() . "'";
        $query .= ", nome='" . addslashes($object->GetName()) . "'";
        $query .= ", descrizione='" . addslashes($object->GetDescr()) . "'";
        $query .= ", id_assessorato='" . $struct->GetAssessorato(true) . "'";
        $query .= ", id_direzione='" . $struct->GetDirezione(true) . "'";
        $query .= ", id_servizio='" . $struct->GetServizio(true) . "'";
        $query .= ", class='" . $object_class . "'";
        $query .= ", logs='" . addslashes($object->GetLog(false)) . "'";

        //AA_Log::Log(__METHOD__ . "query: " . $query, 100);

        if (!$db->Query($query . $where)) {
            AA_Log::Log(__METHOD__ . " - Errore nell'aggiornamento al db - " . $db->GetErrorMessage(), 100);
            return false;
        }

        if ($object->GetId() == 0) {
            $object->SetId($db->GetLastInsertId());
        }

        $object->SetChanged(false);
        return true;
    }

    //funzione di clonazione dei dati collegati
    protected function CloneData($idData = 0, $user = null)
    {
        if ($this->GetDbDataTable() != "" && ($idData == $this->nId_Data || $idData == $this->nId_Data_Rev) && $idData > 0) {
            $select = "SELECT * FROM " . $this->GetDbDataTable() . " ";
            $where = " WHERE id='" . addslashes($idData) . "' LIMIT 1";

            $db = new AA_Database();
            if (!$db->Query($select . $where)) {
                AA_Log::Log(__METHOD__ . " - Errore nella clonazione dei dati - " . $db->GetErrorMessage(), 100);
                return 0;
            }

            if ($db->GetAffectedRows() == 0) {
                AA_Log::Log(__METHOD__ . " - Errore nella clonazione dei dati, dati non trovati ($idData)", 100);
                return 0;
            }

            $query = "INSERT INTO " . $this->GetDbDataTable() . " SET ";
            $rs = $db->GetResultSet();
            $sep = "";
            foreach ($this->GetDbBindings() as $prop => $dbField) {
                $query .= $sep . $dbField . " = '" . addslashes($rs[0][$dbField]) . "'";
                $sep = ",";
            }

            if (!$db->Query($query)) {
                AA_Log::Log(__METHOD__ . " - Errore nella clonazione dei dati - " . $db->GetErrorMessage(), 100);
                return 0;
            }

            return $db->GetLastInsertId();
        }

        return 0;
    }

    //funzione di eliminazione dei dati collegati
    protected function DeleteData($idData = 0, $user = null)
    {
        if ($this->GetDbDataTable() != "" && ($idData == $this->nId_Data || $idData == $this->nId_Data_Rev) && $idData > 0) {
            $query = "DELETE FROM " . $this->GetDbDataTable() . " WHERE id='" . addslashes($idData) . "' LIMIT 1";

            $db = new AA_Database();
            if (!$db->Query($query)) {
                AA_Log::Log(__METHOD__ . " - Errore nella eliminazione dei dati - " . $db->GetErrorMessage(), 100);
                return false;
            }

            return true;
        }

        return false;
    }

    //Funzione di salvataggio dei dati legati all'oggetto
    protected function SaveData()
    {
        if ($this->GetDbDataTable() != "") {
            if ($this->nId_Data_Rev > 0) {
                $query = "UPDATE " . $this->GetDbDataTable() . " SET ";
                $where = " WHERE " . $this->GetDbDataTable() . ".id = " . $this->nId_Data_Rev . " LIMIT 1";
            } else {
                //Primo inserimento
                if ($this->GetIdData() == 0) {
                    $query = "INSERT INTO " . $this->GetDbDataTable() . " SET ";
                    $where = "";
                } else {
                    $query = "UPDATE " . $this->GetDbDataTable() . " SET ";
                    $where = " WHERE " . $this->GetDbDataTable() . ".id = " . $this->nId_Data . " LIMIT 1";
                }
            }

            $sep = "";
            foreach ($this->GetDbBindings() as $prop => $dbField) {
                $query .= $sep . $dbField . " = '" . addslashes($this->GetProp($prop)) . "'";
                $sep = ",";
            }

            //AA_Log::Log(__METHOD__."query: ".$query,100);

            $db = new AA_Database();

            //Aggiorna tabella dati
            if (!$db->Query($query . $where)) {
                AA_Log::Log(__METHOD__ . " - Errore nell'aggiornamento della tabella dati - " . $db->GetErrorMessage() . " - Query:" . $query, 100);
                return false;
            }

            if (($this->GetStatus() & AA_Const::AA_STATUS_REVISIONATA) == 0 && $this->GetIdData() == 0) {
                $this->SetIdData($db->GetLastInsertId());
            }
        }

        return true;
    }

    protected function Save($user = null, $bForce = false, $bSaveData = false)
    {
        if ($bForce) $this->bChanged = true;

        return static::SaveToDb($this, $user, $bSaveData);
    }

    //pubblica
    public function Publish($user = null, $bSaveData = false)
    {
        //Verifica se l'oggetto è valido
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        if (($this->GetUserCaps($user) & AA_Const::AA_PERMS_PUBLISH) == 0) {
            AA_Log::Log(__METHOD__ . " - Utente corrente non ha i permessi per pubblicare l'oggetto.", 100);
            return false;
        }

        $oldStatus = $this->GetStatus();
        $oldLog = $this->GetLog(false);
        $this->SetStatus(AA_Const::AA_STATUS_PUBBLICATA);
        $this->AddLog("Pubblicazione", AA_Const::AA_OPS_PUBLISH, $user);
        if (!$this->Save($user, true, $bSaveData)) {
            $this->nStatus = $oldStatus;
            $this->sLog = $oldLog;
            $this->bChanged = false;

            return false;
        }

        return true;
    }

    //Aggiungi nuovo oggetto
    static public function AddNew($object = null, $user = null, $bSaveData = false)
    {
        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        if (!($object instanceof AA_Object_V2)) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido", 100);
            return false;
        }

        $object->SetId(0);
        $object->AddLog("Inserimento", AA_Const::AA_OPS_ADDNEW, $user);

        if (!$object->Save($user, true, $bSaveData)) {
            return false;
        }

        return true;
    }

    //Aggiorna
    public function Update($user = null, $bSaveData = true, $logMsg = "")
    {
        //Verifica se l'oggetto è valido
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        $oldStatus = $this->GetStatus();
        $oldLog = $this->GetLog(false);
        $log = "modifica";

        if (($this->nStatusMask & AA_Const::AA_STATUS_REVISIONATA) > 0) {
            if (($oldStatus & AA_Const::AA_STATUS_PUBBLICATA) > 0 && ($this->GetUserCaps($user) &  AA_Const::AA_PERMS_PUBLISH) == 0) {
                $this->nStatus = $oldStatus | AA_Const::AA_STATUS_REVISIONATA;
                $log .= " (revisione)";
            }
        }

        if ($logMsg != "") $log .= " - " . $logMsg;

        $this->AddLog($log, AA_Const::AA_OPS_UPDATE, $user);
        if (!$this->Save($user, true, $bSaveData)) {
            $this->nStatus = $oldStatus;
            $this->sLog = $oldLog;

            return false;
        }

        return true;
    }

    //cestina
    public function Trash($user = null, $bSaveData = false)
    {
        //Verifica se l'oggetto è valido
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        $oldStatus = $this->GetStatus();
        $oldLog = $this->GetLog(false);

        $this->nStatus |= AA_Const::AA_STATUS_CESTINATA;

        $this->AddLog("Cestina", AA_Const::AA_OPS_TRASH, $user);
        if (!$this->Save($user, true, $bSaveData)) {
            $this->nStatus = $oldStatus;
            $this->sLog = $oldLog;

            return false;
        }

        return true;
    }

    //riassegna
    public function Reassign($oStruct = null, $user = null, $bSaveData = false)
    {
        //Verifica se l'oggetto è valido
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        //Verifica se la struttura è valida
        if (!($oStruct instanceof AA_Struct)) {
            AA_Log::Log(__METHOD__ . " - Struttura non valida.", 100);
            return false;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        $oldStruct = $this->GetStruct();
        $oldLog = $this->GetLog(false);

        $this->oStruct = $oStruct;

        $this->AddLog("Riassegna", AA_Const::AA_OPS_REASSIGN, $user);
        if (!$this->Save($user, true, $bSaveData)) {
            $this->sLog = $oldLog;
            $this->oStruct = $oldStruct;

            return false;
        }

        return true;
    }

    //riassegna
    public function Resume($user = null, $bSaveData = false)
    {
        //Verifica se l'oggetto è valido
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        $oldStatus = $this->GetStatus();
        $oldLog = $this->GetLog(false);

        if (($oldStatus & AA_Const::AA_STATUS_CESTINATA) > 0) {
            $this->SetStatus($oldStatus - AA_Const::AA_STATUS_CESTINATA);
        }

        $this->AddLog("Ripristina", AA_Const::AA_OPS_RESUME, $user);
        if (!$this->Save($user, true, $bSaveData)) {
            $this->nStatus = $oldStatus;
            $this->sLog = $oldLog;
            $this->bChanged = false;

            return false;
        }

        return true;
    }

    //Delete
    public function Delete($user = null)
    {
        //Verifica se l'oggetto è valido
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__ . " - Oggetto non valido.", 100);
            return false;
        }

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return false;
        }

        //Verifica permessi
        if ($this->GetId() > 0) {
            $class = get_class($this);
            $originalObject = new $class($this->GetId(), $user);
            if ($originalObject->GetId() > 0) {
                $perms = $originalObject->GetUserCaps($user);
                if (($perms & AA_Const::AA_PERMS_DELETE) > 0) {
                    //Elimina i dati associati
                    if ($this->nId_Data > 0) {
                        if (!$this->DeleteData($this->nId_Data)) {
                            return false;
                        }
                    }

                    if ($this->nId_Data_Rev > 0) {
                        if (!$this->DeleteData($this->nId_Data_Rev)) {
                            return false;
                        }
                    }

                    $db = new AA_Database();
                    $query = "DELETE from " . AA_Const::AA_DBTABLE_OBJECTS . " WHERE id = '" . $this->GetId() . "' LIMIT 1";
                    if (!$db->Query($query)) {
                        AA_Log::Log(__METHOD__ . " - Errore durante l'eliminazione dell'oggetto (" . $this->GetId() . ") - " . $db->GetErrorMessage(), 100);
                        return false;
                    }

                    return true;
                } else {
                    AA_Log::Log(__METHOD__ . " - L'utente corrente: " . $user->GetUsername() . " non ha i permessi per eliminare l'oggetto (" . $this->GetId() . ")", 100);
                    return false;
                }
            } else {
                AA_Log::Log(__METHOD__ . " - Oggetto non persistente o non trovato (" . $this->GetId() . ")", 100);
                return false;
            }
        } else return true;
    }

    //Verifica dei permessi
    public function GetUserCaps($user = null)
    {
        $perms = AA_Const::AA_PERMS_NONE;

        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsSuperUser()) return AA_Const::AA_PERMS_ALL;

        //L'utente guest non vede le bozze o le schede cestinate
        if ($user->IsGuest() && ($this->nStatus & (AA_Const::AA_STATUS_BOZZA | AA_Const::AA_STATUS_CESTINATA)) > 0) return $perms;

        //Le pubblicate sono visibili a tutti
        if (($this->nStatus & (AA_Const::AA_STATUS_PUBBLICATA | AA_Const::AA_STATUS_CESTINATA)) == AA_Const::AA_STATUS_PUBBLICATA) {
            $perms += AA_Const::AA_PERMS_READ;
            if ($user->IsGuest()) return $perms;
        }

        $samestruct = true;

        $thisStruct = $this->GetStruct();
        $userStruct = $user->GetStruct();
        if ($userStruct->GetServizio(true) > 0 && $thisStruct->GetServizio(true) != $userStruct->GetServizio(true)) {
            $samestruct = false;
        } else {
            if ($userStruct->GetDirezione(true) > 0 && $thisStruct->GetDirezione(true) != $userStruct->GetDirezione(true)) {
                $samestruct = false;
            } else {
                if ($userStruct->GetAssessorato(true) > 0 && $thisStruct->GetAssessorato(true) != $userStruct->GetAssessorato(true)) {
                    $samestruct = false;
                }
            }
        }

        if ($samestruct && ($this->nStatus & AA_Const::AA_STATUS_BOZZA) > 0) {
            $perms = AA_Const::AA_PERMS_READ | AA_Const::AA_PERMS_WRITE | AA_Const::AA_PERMS_DELETE;
            if ($user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN) $perms = AA_Const::AA_PERMS_ALL;
        }

        if ($samestruct && ($this->nStatus & AA_Const::AA_STATUS_PUBBLICATA) > 0) {
            $perms = AA_Const::AA_PERMS_READ | AA_Const::AA_PERMS_WRITE;
            if ($user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN) $perms = AA_Const::AA_PERMS_ALL;
        }

        return $perms;
    }

    //Funzione di caricamento
    static public function Load($id = 0, $user = null, $bLoadData = true)
    {
        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        /*
        if($user->IsGuest())
        {
            AA_Log::Log(__METHOD__." - Utente non valido o sessione scaduta.",100);
            return false;
        }*/

        $object = new AA_Object_V2(0, $user, false);
        $object->bValid = false;

        $db = new AA_Database();
        $query = "SELECT * from " . AA_Const::AA_DBTABLE_OBJECTS . " WHERE id ='" . addslashes($id) . "' LIMIT 1";
        if (!$db->Query($query)) {
            AA_Log::Log(__METHOD__ . " - Errore: " . $db->GetErrorMessage(), 100);
            return $object;
        }

        if ($db->GetAffectedRows() > 0) {
            $rs = $db->GetResultSet();

            $objectClass = "AA_Object_V2";
            if (class_exists($rs[0]['class'])) {
                $objectClass = $rs[0]['class'];
            }

            $object = new $objectClass(0, $user);
            $object->oStruct = AA_Struct::GetStruct($rs[0]['id_assessorato'], $rs[0]['id_direzione'], $rs[0]['id_servizio']);
            $object->nStatus = $rs[0]['status'];
            $object->sClass = $rs[0]['class'];
            $object->sAggiornamento = $rs[0]['aggiornamento'];
            $object->sName = $rs[0]['nome'];
            $object->sDescr = $rs[0]['descrizione'];

            $perms = $object->GetUserCaps($user);
            if (($perms & AA_Const::AA_PERMS_READ) == 0) {
                AA_Log::Log(__METHOD__ . " - Errore: l'utente corrente non ha i permessi per visualizzare l'oggetto.", 100);
                $object->bValid = false;
                return $object;
            }

            if (($perms & AA_Const::AA_PERMS_WRITE) == 0) {
                //AA_Log::Log(__METHOD__." - readonly: ".$perms." ".print_r($user,true),100);
                $object->bReadOnly = true;
            } else {
                //AA_Log::Log(__METHOD__." - writable: ".print_r($object,true),100);
                $object->bReadOnly = false;
                //AA_Log::Log(__METHOD__." - oggetto: ".print_r($object,true),100);
            }

            $object->nId = $rs[0]['id'];
            $object->nId_Data = $rs[0]['id_data'];
            $object->nId_Data_Rev = $rs[0]['id_data_rev'];
            $object->sLog = $rs[0]['logs'];
            $object->bValid = true;

            //Carica i dati collegati
            if ($object->sDbDataTable != "" && $bLoadData && $object->nId > 0) {
                if (!$object->LoadData()) {
                    AA_Log::Log(__METHOD__ . " - Errore: dati non caricati.", 100);
                    $object->bValid = false;
                } else $object->bValid = true;
            }
        } else {
            AA_Log::Log(__METHOD__ . " - Errore: oggetto non trovato ($id)", 100);
        }

        //AA_Log::Log(__METHOD__." - oggetto: ".print_r($object,true),100);
        return $object;
    }

    //Carica  i dati collegati all'oggetto.
    protected function LoadData($user = null)
    {
        if ($this->sDbDataTable != "" && $this->bValid && $this->nId > 0 && ($this->nId_Data > 0 || $this->nId_Data_Rev > 0)) {
            //Verifica utente
            if ($user instanceof AA_User) {
                if (!$user->isCurrentUser() || $user->IsGuest()) {
                    $user = AA_User::GetCurrentUser();
                }
            } else $user = AA_User::GetCurrentUser();

            if (($this->nStatus & AA_Const::AA_STATUS_REVISIONATA) > 0 && !$this->IsReadOnly() && $this->nId_Data_Rev > 0) {
                $query = "SELECT * FROM " . $this->sDbDataTable . " WHERE id = " . $this->nId_Data_Rev . " LIMIT 1";
            } else {
                $query = "SELECT * FROM " . $this->sDbDataTable . " WHERE id = " . $this->nId_Data . " LIMIT 1";
            }

            $db = new AA_Database();
            if (!$db->Query($query)) {
                AA_Log::Log(__METHOD__ . " - Errore: " . $db->GetErrorMessage(), 100);
                return false;
            }

            if ($db->GetAffectedRows() > 0) {
                $data = $db->GetResultSet();
                foreach ($this->GetDbBindings() as $prop => $db_field) {
                    $this->aProps[$prop] = $data[0][$db_field];
                }
            } else {
                AA_Log::Log(__METHOD__ . " - Errore nessun dato trovato. " . $query, 100);
                return false;
            }
        }

        return true;
    }

    //Flag di validità
    protected $bValid = false;
    public function IsValid()
    {
        return $this->bValid;
    }

    //Flag di sola lettura
    protected $bReadOnly = true;
    public function  IsReadOnly()
    {
        return $this->bReadOnly;
    }

    //Costruttore standard
    public function __construct($id = 0, $user = null, $bLoadData = true)
    {
        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return;
        }

        if ($id > 0) {
            $object = static::Load($id, $user, $bLoadData);

            //AA_Log::Log(__METHOD__." - oggetto: ".print_r($object,true),100);

            if ($object->isValid()) {
                $this->nId = $id;
                $this->oStruct = $object->oStruct;
                $this->nStatus = $object->nStatus;
                $this->sClass = $object->sClass;
                $this->sAggiornamento = $object->sAggiornamento;
                $this->sName = $object->sName;
                $this->sDescr = $object->sDescr;
                $this->nId_Data = $object->nId_Data;
                $this->nId_Data_Rev = $object->nId_Data_Rev;
                $this->sLog = $object->sLog;
                $this->bValid = true;
                $this->bReadOnly = $object->bReadOnly;

                //AA_Log::Log(__METHOD__." - oggetto: ".print_r($object,true)." - this: ".print_r($this,true),100);
            } else {
                AA_Log::Log(__METHOD__ . " - Errore oggetto non valido", 100);
            }
        } else {
            $this->bValid = true;
        }
    }

    //Proprietà
    protected $aProps = array();
    public function GetProp($prop = "")
    {
        if ($prop != "" && $prop != null) {
            if (in_array($prop, array_keys($this->aProps))) return $this->aProps[$prop];
        }
        return "";
    }
    public function SetProp($prop = "", $val = null)
    {
        if ($prop != "" && $prop != null) {
            if (in_array($prop, array_keys($this->aProps))) {
                $this->aProps[$prop] = $val;
                return true;
            }
        }

        return false;
    }
    public function GetProps()
    {
        return $this->aProps;
    }

    //Parsing dei dati
    public function Parse($data = array(), $bOnlyData = false)
    {
        //Nome
        foreach ($data as $key => $value) {
            if (!$bOnlyData) {
                if ($key == "nome" || $key == "name") $this->SetName($value);
                if ($key == "descrizione" || $key == "descr") $this->SetDescr($value);
            }

            if (isset($this->aProps[$key])) $this->aProps[$key] = $value;
        }
    }

    //Db binding
    protected $aDbBindings = array();
    public function SetBind($prop = "", $dbField = "", $bAddProp = true)
    {
        if ($prop != "" && $prop != null && $dbField != "" && $dbField != null) {
            if (in_array($prop, array_keys($this->aProps))) {
                $this->aDbBindings[$prop] = $dbField;
                return true;
            } else {
                if ($bAddProp) $this->aProps[$prop] = "";
                $this->aDbBindings[$prop] = $dbField;

                return true;
            }
        }

        return false;
    }
    public function GetBind($prop = "")
    {
        if ($prop != "" && $prop != null) {
            if (in_array($prop, array_keys($this->aDbBindings))) {
                return $this->aDbBindings[$prop];
            }
        }

        return "";
    }
    public function GetDbBindings()
    {
        return $this->aDbBindings;
    }

    //Tabella dati
    protected $sDbDataTable = "";
    public function GetDbDataTable()
    {
        return $this->sDbDataTable;
    }
    public function SetDbDataTable($var = "")
    {
        $this->sDbDataTable = $var;
    }

    //funzione di ricerca
    static public function Search($params = array(), $user = null)
    {
        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        $public = false;
        if ($user->IsGuest()) {
            $public = true;
        }

        //Imposta la query base
        $select = "SELECT DISTINCT " . AA_Const::AA_DBTABLE_OBJECTS . ".id FROM " . AA_Const::AA_DBTABLE_OBJECTS . " ";
        $join = "";
        $where = "";
        $group = " GROUP by " . AA_Const::AA_DBTABLE_OBJECTS . ".id";
        $having = "";
        $order = "";

        //Verifica del parametro class
        if ($params['class'] == "" || !class_exists($params['class']) || $params['class'] == null) {
            $params['class'] = "AA_Object_V2";
            $where .= " WHERE " . AA_Const::AA_DBTABLE_OBJECTS . ".class='AA_Object_V2' ";
        } else {
            $where .= " WHERE " . AA_Const::AA_DBTABLE_OBJECTS . ".class='" . addslashes($params['class']) . "' ";
        }

        //Collegamento tabella dati
        if ($params['class']::AA_DBTABLE_DATA != "") {
            $join .= " INNER JOIN " . $params['class']::AA_DBTABLE_DATA . " ON " . $params['class']::AA_DBTABLE_DATA . ".id in (" . AA_Const::AA_DBTABLE_OBJECTS . ".id_data," . AA_Const::AA_DBTABLE_OBJECTS . ".id_data_rev)";
        }

        //Parametro status non impostato o non valido
        if ($params['status'] == "" || !isset($params['status']) || $public || ($params['status'] & AA_Const::AA_STATUS_ALL) == 0) $params['status'] = AA_Const::AA_STATUS_PUBBLICATA;

        $userStruct = $user->GetStruct();

        if ($params['class'] == "AA_Object_V2" && !$public) {
            if ($params['gestiti'] != "" || ($params['status'] & (AA_Const::AA_STATUS_BOZZA + AA_Const::AA_STATUS_CESTINATA + AA_Const::AA_STATUS_REVISIONATA) > 0)) {
                //Visualizza solo quelle della sua struttura
                if ($userStruct->GetAssessorato(true) > 0) {
                    $params['id_assessorato'] = $userStruct->GetAssessorato(true);
                    //$where.=" AND ".AA_Const::AA_DBTABLE_OBJECTS.".id_assessorato='".$userStruct->GetAssessorato(true)."'";    
                }

                if ($userStruct->GetDirezione(true) > 0) {
                    $params['id_direzione'] = $userStruct->GetDirezione(true);
                    //$where.=" AND ".AA_Const::AA_DBTABLE_OBJECTS.".id_direzione='".$userStruct->GetDirezione(true)."'";    
                }

                if ($userStruct->GetServizio(true) > 0) {
                    $params['id_servizio'] = $userStruct->GetServizio(true);
                    //$where.=" AND ".AA_Const::AA_DBTABLE_OBJECTS.".id_servizio='".$userStruct->GetDirezione(true)."'";    
                }
            }
        }

        //Filtra in base allo stato della scheda
        if ($params['id'] == "") {
            $where .= " AND status ='" . $params['status'] . "' ";

            //filtra in base al nome
            if ($params['nome'] != "") {
                $where .= " AND " . AA_Const::AA_DBTABLE_OBJECTS . ".nome like '%" . addslashes($params['nome']) . "%'";
            }

            //filtra in base alla descrizione
            if ($params['descrizione'] != "") {
                $where .= " AND " . AA_Const::AA_DBTABLE_OBJECTS . ".descrizione like '%" . addslashes($params['descrizione']) . "%'";
            }
        }

        //Filtra solo oggetti della RAS
        if ($userStruct->GetTipo() == 0) {
            $join .= " INNER JOIN " . AA_Const::AA_DBTABLE_ASSESSORATI . " ON " . AA_Const::AA_DBTABLE_OBJECTS . ".id_assessorato=" . AA_Const::AA_DBTABLE_ASSESSORATI . ".id ";

            //RAS
            $where .= " AND " . AA_Const::AA_DBTABLE_ASSESSORATI . ".tipo = 0";
        }

        //solo oggetti dell'ente
        if ($userStruct->GetTipo() > 0) {
            $params['id_assessorato'] = $userStruct->GetAssessorato(true);
        }

        //filtro struttura
        if ($params['id_assessorato'] != "" && $params['id_assessorato'] > 0) {
            $where .= " AND " . AA_Const::AA_DBTABLE_OBJECTS . ".id_assessorato = '" . addslashes($params['id_assessorato']) . "'";
        }

        if ($params['id_direzione'] != "" && $params['id_direzione'] > 0) {
            $where .= " AND " . AA_Const::AA_DBTABLE_OBJECTS . ".id_direzione = '" . addslashes($params['id_direzione']) . "'";
        }

        if ($params['id_servizio'] != "" && $params['id_servizio'] > 0) {
            $where .= " AND " . AA_Const::AA_DBTABLE_OBJECTS . ".id_servizio = '" . addslashes($params['id_servizio']) . "'";
        }
        //------------------------

        //filtra in base all'id(s)
        if ($params['id'] != "") {
            $ids = array();
            preg_match("/([0-9]+\,*)+/", $params['id'], $ids);

            if (count($ids) > 0) {
                $where .= " AND " . AA_Const::AA_DBTABLE_OBJECTS . ".id in (" . $ids[0] . ")";
            } else $where .= " AND " . AA_Const::AA_DBTABLE_OBJECTS . ".id = '" . addslashes($params['id']) . "'";
        }

        //aggiunge i join
        if (is_array($params['join'])) {
            foreach ($params['join'] as $curJoin) {
                $join .= " " . $curJoin . " ";
            }
        }
        //-----------------------

        //aggiunge i where
        if (is_array($params['where'])) {
            foreach ($params['where'] as $curParam) {
                if ($where == "") $where = " WHERE " . $curParam;
                $where .= "  " . $curParam;
            }
        }
        //-----------------------

        //aggiunge i having
        if (is_array($params['having'])) {
            foreach ($params['having'] as $curParam) {
                if ($having == "") $having = " HAVING " . $curParam;
                else $having .= " AND " . $curParam;
            }
        }
        //-----------------------

        //aggiunge i group
        if (is_array($params['group'])) {
            foreach ($params['group'] as $curParam) {
                if ($group == "") $group = " GROUP BY " . $curParam;
                else $group .= ", " . $curParam;
            }
        }
        //-----------------------

        //aggiunge gli order
        if (is_array($params['order'])) {
            foreach ($params['order'] as $curParam) {
                if ($order == "") $order = " ORDER BY " . $curParam;
                else $order .= ", " . $curParam;
            }
        }
        //-----------------------

        $db = new AA_Database();

        //Conta i risultati
        $query = "SELECT COUNT(id) as tot FROM (" . $select . $join . $where . $group . $having . ") as count_filter";

        //AA_Log::Log(get_class()."->Search(".print_r($params,TRUE).") - query: $query",100);

        if (!$db->Query($query)) {
            //Imposta lo stato di errore
            AA_Log::Log(__METHOD__ . "(" . print_r($params, TRUE) . ") - Errore :" . $db->GetErrorMessage(), 100);

            return array(0 => -1, array());
        }

        $rs = $db->GetResultSet();
        $tot_count = $rs['0']['tot'];

        //Restituisce un array vuoto se non trova nulla
        if ($tot_count == 0) {
            //AA_Log::Log(__METHOD__."(".print_r($params,TRUE).") - query vuota: $query");
            return array(0 => 0, array());
        }

        //Restituisce solo il numero
        if ($params['onlyCount'] != "") return array(0 => $tot_count, array());

        //Limita a 10 risultati di default
        if (!isset($params['from']) || $params['from'] < 0 || $params['from'] > $tot_count) $params['from'] = 0;
        if (!isset($params['count']) || $params['count'] < 0) $params['count'] = 10;

        //Effettua la query
        $query = $select . $join;
        if (strlen($where) > 0) $query .= $where;
        if ($params['count'] != "all") $query .= $group . $having . $order . " LIMIT " . $params['from'] . "," . $params['count'];
        else $query .= $group . $having . $order;
        if (!$db->Query($query)) {
            //Errore query
            AA_Log::Log(__METHOD__ . "(" . print_r($params, TRUE) . ") - Errore nella query:" . $db->GetErrorMessage(), 100);
            return array(0 => -1, array());
        }

        //AA_Log::Log(get_class()."->Search(".print_r($params,TRUE).") - query: $query",100);

        //Popola l'array dei risultati
        $results = array();

        if ($db->GetAffectedRows() > 0) {
            foreach ($db->GetResultSet() as $curRec) {
                $curResult = new $params['class']($curRec['id'], $user);
                if ($curResult->isValid()) $results[$curRec['id']] = $curResult;
            }
        }

        return array(0 => $tot_count, $results);
    }
}

//Object log
class AA_Object_Log
{
    protected $aLog = array();
    public function __construct($log = "")
    {
        $log = explode("\n", $log);

        foreach ($log as $curRow) {
            $row = explode("|", $curRow);
            $this->aLog[] = array(
                'data' => $row[0],
                'user' => $row[1],
                'op' => $row[2],
                'msg' => $row[3]
            );
        }
    }

    public function GetLog()
    {
        return $this->aLog;
    }

    //Restituisce l'ultimo log
    public function GetLastLog()
    {
        $count = sizeof($this->aLog);
        if ($count > 0) {
            return ($this->aLog[$count - 1]);
        } else return array();
    }
}

#Classe gestione impostazioni piattaforma
class AA_Platform
{
    //Istanza
    static private $oInstance = null;

    //utente
    protected $oUser = null;

    //flag di validità
    protected $bValid = false;
    public function IsValid()
    {
        return $this->bValid;
    }

    //restituisce l'istanza unica
    static public function GetInstance($user = null)
    {
        if (self::$oInstance == null) {
            self::$oInstance = new AA_Platform($user);

            //AA_Log::Log(__METHOD__." - istanzio l'istanza: ".print_r(self::$oInstance,true),100);
        }

        //AA_Log::Log(__METHOD__." - restituisco l'istanza: ".print_r(self::$oInstance,true),100);
        return self::$oInstance;
    }

    //Restituisce l'url del task manager
    public function GetModuleTaskManagerURL($id_module = "")
    {
        if (!$this->IsValid()) {
            return AA_Const::AA_PUBLIC_LIB_PATH . DIRECTORY_SEPARATOR . "system_ops.php";
        }

        $module = $this->GetModule($id_module);
        if ($module == null) {
            return AA_Const::AA_PUBLIC_LIB_PATH . DIRECTORY_SEPARATOR . "system_ops.php";
        } else {
            return AA_Const::AA_PUBLIC_MODULES_PATH . "/" . $module['id_sidebar'] . "_" . $module['id'] . "/taskmanager.php";
        }
    }

    protected function __construct($user = null)
    {
        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                //AA_Log::Log(__METHOD__." - Autenticazione utente - ".$user,100);
                $user = AA_User::UserAuth();
            }

            //AA_Log::Log(__METHOD__." - Utente autenticato - ".$user,100);
        } else {
            //AA_Log::Log(__METHOD__." - Autenticazione utente ",100);
            $user = AA_User::UserAuth();
        }

        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente non valido o sessione scaduta.", 100);
            return;
        }

        $this->oUser = $user;
        $this->bValid = true;
    }

    //Gestione moduli
    protected $aModules = null;
    protected function LoadModules($bDisableCache = false)
    {
        if (!$this->bValid) {
            return;
        }

        //Carica i moduli
        if (!isset($_SESSION['platform_modules_cache']) || isset($_REQUEST['disable_cache']) || $bDisableCache) {
            $db = new AA_Database();
            $query = "SELECT * from aa_platform_modules";
            if (!$db->Query($query)) {
                AA_Log::Log(__METHOD__ . " - errore: " . $db->GetErrorMessage(), 100);
                return;
            }

            if ($db->GetAffectedRows() > 0) {
                $userFlags = $this->oUser->GetFlags(true);

                foreach ($db->GetResultSet() as $curMod) {
                    $admins = explode(",", $curMod['admins']);
                    $mod_flags = json_decode($curMod['flags'], true);
                    if (!is_array($mod_flags)) {
                        if (json_last_error() > 0) AA_Log::Log(__METHOD__ . " - module flags:" . print_r($mod_flags, true) . " - error: " . json_last_error(), 100);
                        $flags = array();
                    } else {
                        $flags = array_keys($mod_flags);
                        //AA_Log::Log(__METHOD__." - module flags:".print_r($mod_flags,true),100);
                    }

                    if (in_array($this->oUser->GetId(), $admins)) {
                        //Amministratori del modulo
                        $this->aModules[$curMod['id_modulo']] = $curMod;
                    } else {
                        //Utilizzatori del modulo
                        if ($curMod['enable'] == 1) {
                            if (sizeof($flags) == 0) {
                                //modulo pubblico
                                $this->aModules[$curMod['id_modulo']] = $curMod;
                            } else {
                                //Modulo a visibilità limitata
                                if (sizeof($userFlags) > 0) {
                                    foreach ($userFlags as $curFlag) {
                                        if (in_array($curFlag, $flags)) $this->aModules[$curMod['id_modulo']] = $curMod;
                                    }
                                } else {
                                    AA_Log::Log(__METHOD__ . " - L'utente corrente (" . $this->oUser->GetUsername() . ") non ha i permessi per accedere al modulo: " . $curMod['id_modulo'] . " - userFlags: " . print_r($userFlags, true) . " - module flags:" . print_r($flags, true), 100);
                                }
                            }
                        }
                    }
                }
            }

            //AA_Log::Log(__METHOD__." - salvo sessione: ".print_r($this->aModules,true),100);
            $_SESSION['platform_modules_cache'] = serialize($this->aModules);
        } else {
            //AA_Log::Log(__METHOD__." - sessione: ".$_SESSION['platform_modules_cache'],100);
            $this->aModules = unserialize($_SESSION['platform_modules_cache']);
        }
    }

    //registra un modulo
    static public function RegisterModule($idMod = "", $class = "", $user = null)
    {
        //to do
    }

    //Verifica se un modulo è registrato
    static public function IsRegistered($id = "", $user = null)
    {
        $platform = AA_Platform::GetInstance($user);

        if (!$platform->bValid) return false;

        if ($platform->aModules == null) $platform->LoadModules();

        foreach ($platform->aModules as $curId => $class) {
            if ($curId == $id) return true;
        }

        return false;
    }

    //Restituisce il modulo
    public function GetModule($id = "", $user = null)
    {
        if (!$this->bValid) return null;

        if ($this->aModules == null) $this->LoadModules();

        foreach ($this->aModules as $curId => $curMod) {
            if ($curId == $id) return $curMod;
        }

        return null;
    }

    //Restituisce la lista dei moduli registrati
    public function GetModules()
    {
        if (!$this->bValid) return array();

        //$modules=array();

        //AA_Log::Log(__METHOD__." - ".print_r($this,true),100);

        if ($this->aModules == null) $this->LoadModules();

        return $this->aModules;
    }

    //Restituisce l'utente corrente
    public function GetCurrentUser()
    {
        if ($this->bValid) return $this->oUser;
        else return AA_User::UserAuth();
    }

    //Autenticazione
    public function Auth($token = "", $user = "", $pwd = "")
    {
        $user = AA_User::UserAuth($token, $user, $pwd);
        if ($user->isCurrentUser() && !$user->IsGuest()) {
            $this->oUser = $user;
            return true;
        }

        return false;
    }
}
