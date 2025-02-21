<?php
include_once "config.php";
include_once "db.php";
include_once "lib_mail.php";

//Database --------------------
class AA_Database extends PDO_Database
{
    static protected $oPdo=null;
    static protected $dbName="dbname";
	static protected $dbHost="localhost";
	static protected $dbUser="dbuser";
	static protected $dbPwd="dbpwd";
    protected $nLastInsertId = 0;
    static protected $sLastQuery = "";
    static protected $sLastErrorMessage="";

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

class AA_AccountsDatabase extends PDO_Database
{
    //Parametri di connessione al DB
    static protected $oPdo=null;
    static protected $dbName="dbname";
	static protected $dbHost="localhost";
	static protected $dbUser="dbuser";
	static protected $dbPwd="dbpwd";
    protected $nLastInsertId = 0;
    static protected $sLastQuery = "";
    static protected $sLastErrorMessage="";

    private $AA_DBHOST = AA_Config::AA_ACCOUNTS_DBHOST;
    private $AA_DBNAME = AA_Config::AA_ACCOUNTS_DBNAME;
    private $AA_DBUSER = AA_config::AA_ACCOUNTS_DBUSER;
    private $AA_DBPWD = AA_Config::AA_ACCOUNTS_DBPWD;

    public function __construct($bReset = false)
    {
        if (!$this->Initialize($this->AA_DBNAME,$this->AA_DBHOST, $this->AA_DBUSER, $this->AA_DBPWD, $bReset)) {
            AA_Log::Log(__METHOD__ . " - Errore nella connessione al DB: " . $this->GetErrorMessage(), 100);
            return;
        }
    }
}
#--------------------------------

//Costanti
class AA_Const extends AA_Config
{
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
            $time = date("Y-M-d H:i:s");

            //self::$oLog[] = $time . "*" . $level . "*" . $msg . "\n";

            //if ($level == 100 || $bWithbacktrace) {
            //    $array = array_keys(self::$oLog);
            //    $id = end($array);
            //    self::$oBackTrace[$id]=debug_backtrace();
            //}

            if ($level == 100) {
                self::$lastErrorLog = $msg;
            }

            if ($bLogToSession) {
                $session_log = array();
                if (isset($_SESSION['log'])) $session_log = unserialize($_SESSION['log']);

                //rimuove gli elementi più vecchi
                while (sizeof($session_log) > AA_Log::AA_LOG_MAX_ENTRIES) {
                    array_shift($session_log);
                }

                if($bWithbacktrace) $newlog = new AA_Log($level, $msg, $time, debug_backtrace(0));
                else $newlog = new AA_Log($level, $msg, $time, array());

                try
                {
                    $session_log[]=serialize($newlog);
                    $_SESSION['log'] = serialize($session_log);
                }
                catch( Exception $e ) {
                    $session_log[]= serialize(new AA_Log($level, $msg, $time));
                    $_SESSION['log'] = serialize($session_log);
                }
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
        //if ($user instanceof AA_User) $this->oUser = $user;
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

        self::Log("AA_Log::LogAction($id_utente,$op,$sql)", 100);
    }
    #----------------------------
}

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

    public function __construct()
    {

    }

    static public function GetStruct($id_assessorato = '', $id_direzione = '', $id_servizio = '', $type = '')
    {
        //AA_Log::Log(get_class() . "GetStruct($id_assessorato,$id_direzione,$id_servizio,$type)");

        $db = new AA_AccountsDatabase();
        $struct = new AA_Struct();

        if ($type != '') $struct->nTipo = $type;

        $now = Date("Y-m-d");

        //Servizio impostato
        if ($id_servizio != '' && $id_servizio > 0) 
        {
            $db->Query("SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from servizi inner join direzioni on servizi.id_direzione = direzioni.id inner join assessorati on direzioni.id_assessorato=assessorati.id where servizi.id='$id_servizio'");
            $rs = $db->GetResultSet();
            if (sizeof($rs) > 0) 
            {
                $struct->bIsValid = true;

                //Assessorato
                $struct->nID_Assessorato = $rs[0]["id_assessorato"];
                $struct->sAssessorato = $rs[0]["assessorato"];
                $struct->nTipo = $rs[0]["tipo"];

                //Direzione
                $struct->nID_Direzione = $rs[0]["id_direzione"];
                $struct->sDirezione = $rs[0]["direzione"];

                //Servizio
                $struct->nID_Servizio = $rs[0]["id_servizio"];
                $struct->sServizio = $rs[0]["servizio"];

                $soppresso_servizio = 0;
                if ($rs[0]["data_soppressione_servizio"] < $now) $soppresso_servizio = 1;
                $soppresso = 0;
                if ($rs[0]["data_soppressione_direzione"] < $now) $soppresso = 1;

                $struct->aTree['assessorati'][$rs[0]["id_assessorato"]] = array('descrizione' => $rs[0]["assessorato"], 'tipo' => $rs[0]["tipo"], 'direzioni' => array($rs[0]["id_direzione"] => array('descrizione' => $rs[0]["direzione"], 'data_soppressione' => $rs[0]["data_soppressione_direzione"], "soppresso" => $soppresso, 'servizi' => array($rs[0]["id_servizio"] => array("descrizione" => $rs[0]["servizio"], "data_soppressione" => $rs[0]["data_soppressione_servizio"], "soppresso" => $soppresso_servizio)))));

                return $struct;
            }
        }

        //Direzione impostata
        if ($id_direzione != '' && $id_direzione > 0) {
            $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione from direzioni inner join assessorati on direzioni.id_assessorato=assessorati.id where direzioni.id='$id_direzione'";
            $db->Query($query);
            $rs = $db->GetResultSet();
            if (sizeof($rs) > 0) 
            {
                $struct->bIsValid = true;

                //Assessorato
                $struct->nID_Assessorato = $rs[0]["id_assessorato"];
                $struct->sAssessorato = $rs[0]["assessorato"];
                $struct->nTipo = $rs[0]["tipo"];

                //Direzione
                $struct->nID_Direzione = $rs[0]["id_direzione"];
                $struct->sDirezione = $rs[0]["direzione"];

                $soppresso = 0;
                if ($rs[0]["data_soppressione_direzione"] < $now) $soppresso = 1;

                $struct->aTree['assessorati'][$rs[0]["id_assessorato"]] = array('descrizione' => $rs[0]["assessorato"], 'tipo' => $rs[0]["tipo"], 'direzioni' => array());
                $struct->aTree['assessorati'][$rs[0]["id_assessorato"]]['direzioni'][$rs[0]["id_direzione"]] = array('descrizione' => $rs[0]["direzione"], "data_soppressione" => $rs[0]['data_soppressione_direzione'], "soppresso" => $soppresso, 'servizi' => array());

                return $struct;
            }
        }

        //Assessorato impostato
        if ($id_assessorato != '' && $id_assessorato > 0) 
        {
            $db->Query("SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo from assessorati where assessorati.id='$id_assessorato'");
            $rs = $db->GetResultSet();
            if (sizeof($rs) > 0) 
            {
                $struct->bIsValid = true;

                //Assessorato
                $struct->nID_Assessorato = $rs[0]["id_assessorato"];
                $struct->sAssessorato = $rs[0]["assessorato"];
                $struct->nTipo = $rs[0]["tipo"];

                $struct->aTree['assessorati'][$rs[0]["id_assessorato"]] = array('descrizione' => $rs[0]["assessorato"], 'tipo' => $rs[0]["tipo"], 'direzioni' => array());

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

        //Servizio impostato
        if ($this->nID_Servizio != '' && $this->nID_Servizio > 0) {
            return $this->aTree;
        }

        $db = new AA_AccountsDatabase();

        //Direzione impostata
        if ($this->nID_Direzione != '' && $this->nID_Direzione > 0) {
            $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from assessorati left join direzioni on direzioni.id_assessorato = assessorati.id left join servizi on servizi.id_direzione=direzioni.id where direzioni.id='$this->nID_Direzione' order by assessorati.descrizione, direzioni.descrizione, servizi.descrizione";
            if (!$db->Query($query)) {
                AA_Log::Log(get_class() . "->GetStructTree() - Errore: " . $db->GetErrorMessage(), 100);
                return $this->aTree;
            }

            $rs = $db->GetResultSet();
            foreach($rs as $curRow) 
            {
                if ($curRow["id_direzione"] != "") {
                    
                        //AA_Log::Log(get_class()."->GetStructTree() ".print_r($this->aTree,TRUE),100,true,true);
                        $soppresso = 0;
                        if ($curRow["data_soppressione_servizio"] <= $now) $soppresso = 1;
                        if ($curRow["id_servizio"] != "") $this->aTree['assessorati'][$curRow["id_assessorato"]]['direzioni'][$curRow["id_direzione"]]['servizi'][$curRow["id_servizio"]] = array("descrizione" => $curRow["servizio"], "data_soppressione" => $curRow["data_soppressione_servizio"], "soppresso" => $soppresso);
                }
            }

            return $this->aTree;
        }

        //Assessorato impostato
        if ($this->nID_Assessorato != '' && $this->nID_Assessorato > 0) 
        {
            $query = "SELECT assessorati.id as id_assessorato,assessorati.descrizione as assessorato, assessorati.tipo, direzioni.id as id_direzione, direzioni.descrizione as direzione, direzioni.data_soppressione as data_soppressione_direzione, servizi.id as id_servizio, servizi.descrizione as servizio, servizi.data_soppressione as data_soppressione_servizio from assessorati left join direzioni on direzioni.id_assessorato = assessorati.id left join servizi on servizi.id_direzione=direzioni.id where assessorati.id='$this->nID_Assessorato' order by assessorati.descrizione, direzioni.descrizione,servizi.descrizione";
            if (!$db->Query($query)) {
                AA_Log::Log(get_class() . "->GetStructTree() - Errore nella query: " . $query, 100, true, true);
                return $this->aTree;
            }

            //AA_Log::Log(get_class()."->GetStructTree() - query: ".$query,100,true,true);

            $curDirezione = 0;
            $rs = $db->GetResultSet();
            foreach($rs as $curRow) 
            {
                if ($curRow["id_direzione"] != $curDirezione && $curRow["id_direzione"] != "") 
                {
                    $soppresso = 0;
                    if ($curRow["data_soppressione_direzione"]<= $now) $soppresso = 1;
                    $this->aTree['assessorati'][$curRow["id_assessorato"]]['direzioni'][$curRow["id_direzione"]] = array('descrizione' => $curRow["direzione"], "data_soppressione" => $curRow["data_soppressione_direzione"], "soppresso" => $soppresso, 'servizi' => array());
                    $curDirezione = $curRow["id_direzione"];
                }

                $soppresso = 0;
                if ($curRow["data_soppressione_servizio"] <= $now) $soppresso = 1;
                if ($curRow["id_servizio"] != "") $this->aTree['assessorati'][$curRow["id_assessorato"]]['direzioni'][$curDirezione]['servizi'][$curRow["id_servizio"]] = array("descrizione" => $curRow["servizio"], "data_soppressione" => $curRow["data_soppressione_servizio"], "soppresso" => $soppresso);
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

            $rs = $db->GetResultSet();
            foreach($rs as $curRow) 
            {
                
                if ($curAssessorato != $curRow["id_assessorato"]) {
                    $this->aTree['assessorati'][$curRow["id_assessorato"]] = array('descrizione' => $curRow["assessorato"], 'tipo' => $curRow["tipo"], 'direzioni' => array());
                    $curAssessorato = $curRow["id_assessorato"];
                }

                if ($curRow["id_direzione"] != $curDirezione && $curRow["id_direzione"] != "") {
                    $soppresso = 0;
                    if ($curRow["data_soppressione_direzione"] <= $now) $soppresso = 1;
                    $this->aTree['assessorati'][$curAssessorato]['direzioni'][$curRow["id_direzione"]] = array('descrizione' => $curRow["direzione"], "data_soppressione" => $curRow["data_soppressione_direzione"], "soppresso" => $soppresso, 'servizi' => array());
                    $curDirezione = $curRow["id_direzione"];
                }

                $soppresso = 0;
                if ($curRow["data_soppressione_servizio"] <= $now) $soppresso = 1;
                if ($curRow["id_servizio"] != "") $this->aTree['assessorati'][$curAssessorato]['direzioni'][$curDirezione]['servizi'][$curRow["id_servizio"]] = array("descrizione" => $curRow["servizio"], "data_soppressione" => $curRow["data_soppressione_servizio"], "soppresso" => $soppresso);
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
        //AA_Log::Log(get_class() . "->toArray()");

        $this->aTree = $this->GetStructTree();

        $root = "root";
        $assessorato_num = 1;
        $direzione_num = 1;
        $servizio_num = 1;
        $result = array(array("id" => $root, "value" => "Strutture", "open" => true,"ops"=>"","parent"=>0, "data" => array()));
        foreach ($this->aTree['assessorati'] as $id_ass => $ass) {
            if (sizeof($ass['direzioni']) > 0 && (!isset($params['hideDirs']) || $params['hideDirs'] !=1)) $curAssessorato = array("id" => $id_ass, "id_assessorato" => $id_ass, "id_direzione" => 0, "id_servizio" => 0,"parent"=>$root, "tipo" => $ass['tipo'], "value" => $ass['descrizione'], "soppresso" => 0, "data" => array());
            else $curAssessorato = array("id" => $id_ass, "id_assessorato" => $id_ass, "id_direzione" => 0, "id_servizio" => 0, "tipo" => $ass['tipo'],"parent"=>$root, "value" => $ass['descrizione'], "soppresso" => 0);

            //AA_Log::Log(__METHOD__." - curAssessorato: ".print_r($curAssessorato,true),100);

            if ((!isset($params['hideDirs']) || $params['hideDirs'] !=1)) {
                foreach ($ass['direzioni'] as $id_dir => $dir) {
                    //AA_Log::Log(get_class()."->toArray() - direzione: ".$dir['descrizione'],100);
                    if(empty($params['bHideSuppressed'])  || (empty($dir['soppresso']) && !empty($params['bHideSuppressed'])))
                    {
                        if (sizeof($dir['servizi']) > 0 && (!isset($params['hideServices']) || $params['hideServices'] !=1)) $curDirezione = array("id" => $id_ass . "." . $id_dir, "parent"=>$id_ass, "id_direzione" => $id_dir, "id_assessorato" => $id_ass, "id_servizio" => 0, "value" => $dir['descrizione'], "data_soppressione" => $dir['data_soppressione'], "soppresso" => $dir['soppresso'], "data" => array());
                        else $curDirezione = array("id" => $id_ass . "." . $id_dir, "parent"=>$id_ass,"id_direzione" => $id_dir, "id_assessorato" => $id_ass, "id_servizio" => 0, "value" => $dir['descrizione'], "data_soppressione" => $dir['data_soppressione'], "soppresso" => $dir['soppresso']);
                        if ((!isset($params['hideServices']) || $params['hideServices'] !=1)) 
                        {
                            foreach ($dir['servizi'] as $id_ser => $ser) {
                                if(empty($params['bHideSuppressed'])  || (empty($ser['soppresso']) && !empty($params['bHideSuppressed'])))
                                {
                                    $curDirezione['data'][] = array("id" => $id_ass . "." . $id_dir . "." . $id_ser, "parent"=>$id_ass . "." . $id_dir,"id_servizio" => $id_ser, "id_assessorato" => $id_ass, "id_direzione" => $id_dir, "data_soppressione" => $ser['data_soppressione'], "soppresso" => $ser['soppresso'], "value" => $ser['descrizione']);
                                    $servizio_num++;
                                }
                            }
                        }
                        $direzione_num++;
                        $curAssessorato['data'][] = $curDirezione;
                    }

                }
            }
            $assessorato_num++;
            $result[0]['data'][] = $curAssessorato;
        }

        //AA_Log::Log(__METHOD__." - ".print_r($result,true),100);
        return $result;
    }

    //Rappresentazione stringa
    public function __toString()
    {
        AA_Log::Log(get_class() . "__toString()");

        return $this->toXML();
    }
}

//Classe per la gestione dei gruppi
class AA_Group
{
    //propietà
    protected $aProps=array();
    public function GetProp($key)
    {
        if($key !="" && isset($this->aProps[$key])) return $this->aProps[$key];
        return "";
    }

    //flag di validità
    protected $bValid=false;
    public function IsValid()
    {
        return $this->bValid;
    }

    const AA_DB_TABLE="aa_groups";

    protected function Parse($props=array())
    {
        if(is_array($props))
        {
            foreach($props as $key=>$val)
            {
                if(isset($this->aProps[$key])) $this->aProps[$key]=$val;
            }
        }
    }

    protected function __construct($props=array())
    {
        $this->aProps['id']=0;
        $this->aProps['id_parent']=0;
        $this->aProps['descr']="";
        $this->aProps['system']=0;

        $this->Parse($props);
    }

    static public function GetDescendants($id_group)
    {
        if($id_group <=0)
        {
            AA_Log::Log(__METHOD__." - identificativo di gruppo non valido. (".$id_group.")",100);
            return array();            
        }

        $result=array();
        $db=new AA_Database();
        $query="SELECT GROUP_CONCAT(id) as ids FROM ".static::AA_DB_TABLE." WHERE id_parent='".$id_group."'";
        //AA_Log::Log(__METHOD__." - query: ".$query,100);
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage()."  - query: ".$query,100);
            return array();
        }
        $rs=$db->GetResultSet();

        $num=0;
        while($rs[0]['ids'] != "" && $num < 10)
        {
            $result=array_merge($result,explode(",",$rs[0]['ids']));

            $query="SELECT GROUP_CONCAT(id) as ids FROM ".static::AA_DB_TABLE." WHERE FIND_IN_SET(id_parent,'".$rs[0]['ids']."')";
            //AA_Log::Log(__METHOD__." ids: ".$rs[0]['ids']." - result: ".print_r($result,true)." - query: ".$query,100);
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage()."  - query: ".$query,100);
                return array();
            }
            $rs=$db->GetResultSet();
            $num++;
        }

        //AA_Log::Log(__METHOD__." - result: ".print_r($result,true),100);
        return $result;
    }

    //Restituisce il gruppo con l'id specificato
    static public function GetGroup($id="",$user=null)
    {
        if($user == null) $user=AA_User::GetCurrentUser();
        if($user->IsGuest())
        {
            AA_Log::Log(__METHOD__." - utente non valido.",100);
            return new AA_Group();
        }

        if($id <=0)
        {
            AA_Log::Log(__METHOD__." - identificativo di gruppo non valido. (".$id.")",100);
            return new AA_Group();            
        }

        $db=new AA_Database();
        $query="SELECT * FROM ".static::AA_DB_TABLE." WHERE id='".addslashes($id)."' LIMIT 1";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query. (".$db->GetErrorMessage().") - query: ".$query,100);
            return new AA_Group();
        }

        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            
            $group = new AA_Group($rs[0]);
            $group->bValid=true;

            return $group;
        }

        return new AA_Group();
    }

    //Aggiunge un nuovo gruppo (restituisce il gruppo appena generato)
    static public function AddGroup($newGroupData=null,$user=null)
    {
        if($user == null) $user=AA_User::GetCurrentUser();
        if($user->IsGuest())
        {
            AA_Log::Log(__METHOD__." - utente non valido.",100);
            return new AA_Group();
        }

        if(!is_array($newGroupData))
        {
            AA_Log::Log(__METHOD__." - dati gruppo non validi.",100);
            return new AA_Group();
        }

        if(!isset($newGroupData['descr']) || $newGroupData['descr']=="")
        {
            AA_Log::Log(__METHOD__." - dati gruppo non validi.",100);
            return new AA_Group();
        }

        $db=new AA_Database();
        $query="INSERT INTO ".static::AA_DB_TABLE." SET descr='".addslashes($newGroupData['descr'])."'";
        
        if($user->IsSuperUser())
        {
            if(isset($newGroupData['system']) && $newGroupData['system'] > 0)
            {
                $query.=", system=1";
            }

            if(isset($newGroupData['id_parent']) && $newGroupData['id_parent'] > 0)
            {
                $query.=", id_parent='".addslashes($newGroupData['id_parent'])."'";
            }
        }

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query. (".$db->GetErrorMessage().") - query: ".$query,100);
            return new AA_Group();
        }

        return static::GetGroup($db->GetLastInsertId(),$user);
    }

    //modifica il gruppo indicato con quello passato
    static public function ModifyGroup($id_group=0,$groupData=null,$user=null)
    {
        if($user == null) $user=AA_User::GetCurrentUser();
        if($user->IsGuest())
        {
            AA_Log::Log(__METHOD__." - utente non valido.",100);
            return false;
        }

        $group=static::GetGroup($id_group,$user);
        if(!$group->IsValid())
        {
            AA_Log::Log(__METHOD__." - identificativo gruppo non valido.",100);
            return false;
        }

        if(!is_array($groupData))
        {
            AA_Log::Log(__METHOD__." - dati gruppo non validi.",100);
            return false;
        }

        if(!isset($groupData['descr']) || $groupData['descr']=="")
        {
            AA_Log::Log(__METHOD__." - dati gruppo non validi.",100);
            return false;
        }

        if(!$user->IsSuperUser())
        {
            if($group->GetProp("system") == 1)
            {
                AA_Log::Log(__METHOD__." - Gruppo non modificabile dall'utente corrente. (".$user->GetNome()." ".$user->GetCognome.")",100);
                return false;
            }
    
            if($group->GetProp("id_parent") > 0)
            {
                AA_Log::Log(__METHOD__." - Gruppo non modificabile dall'utente corrente. (".$user->GetNome()." ".$user->GetCognome.")",100);
                return false;
            }    
        }

        $db=new AA_Database();
        $query="UPDATE ".static::AA_DB_TABLE." SET descr='".addslashes($groupData['descr'])."'";

        if($user->IsSuperUser())
        {
            if(isset($groupData['system']))
            {
                if($groupData['system'] > 0) $query.=", system=1";
                else $query.=", system=0";
            }

            if(isset($groupData['id_parent']))
            {
                if($groupData['id_parent'] > 0) $query.=", id_parent='".addslashes($groupData['id_parent'])."'";
                else $query.=", id_parent='0'";
            }
        }

        $query.=" WHERE id='".addslashes($group->GetProp('id'))."' LIMIT 1";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query. (".$db->GetErrorMessage().") - query: ".$query,100);
            return false;
        }

        return true;
    }

    //Elimina un gruppo esistente
    static public function DelGroup($id_group=0,$user=null)
    {
        if($user == null) $user=AA_User::GetCurrentUser();
        if($user->IsGuest())
        {
            AA_Log::Log(__METHOD__." - utente non valido.",100);
            return false;
        }

        $group=static::GetGroup($id_group,$user);
        if(!$group->IsValid())
        {
            AA_Log::Log(__METHOD__." - identificativo gruppo non valido.",100);
            return false;
        }

        if(!$user->IsSuperUser())
        {
            if($group->GetProp("system") == 1)
            {
                AA_Log::Log(__METHOD__." - Gruppo non modificabile dall'utente corrente. (".$user->GetNome()." ".$user->GetCognome.")",100);
                return false;
            }
    
            if($group->GetProp("id_parent") > 0)
            {
                AA_Log::Log(__METHOD__." - Gruppo non modificabile dall'utente corrente. (".$user->GetNome()." ".$user->GetCognome.")",100);
                return false;
            }    
        }

        $db=new AA_Database();
        $query="DELETE FROM ".static::AA_DB_TABLE;
        $query.=" WHERE id='".addslashes($group->GetProp('id'))."' LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore nella query. (".$db->GetErrorMessage().") - query: ".$query,100);
            return false;
        }

        return true;
    }

    //Restituisce un array di gruppi che soddisfano i criteri indicati
    static public function SearchGroups($params=array())
    {

        return array();
    }

    //restituisce gli utenti che partecipano del gruppo corrente
    public function GetUsers()
    {

        return array();
    }
}

//USER classe
class AA_User
{
    //stato utenti
    const AA_USER_STATUS_DELETED=-1;
    const AA_USER_STATUS_DISABLED=0;
    const AA_USER_STATUS_ENABLED=1;

    //built in groups ids
    const AA_USER_GROUP_GUESTS=0;
    const AA_USER_GROUP_SUPERUSER=1;
    const AA_USER_GROUP_ADMINS=2;
    const AA_USER_GROUP_OPERATORS=3;
    const AA_USER_GROUP_USERS=4;
    const AA_USER_GROUP_SERVEROPERATORS=5;

    //tabella utenti
    const AA_DB_TABLE="aa_users";

    //Nome
    protected $sNome = "Nessuno";

    //Cognome
    protected $sCognome = "Nessuno";

    //email
    protected $sEmail = "";

     //cf
     protected $sCf = "";


    //Nome utente
    protected $sUser = "Nessuno";

    //ID utente
    protected $nID = "0";

    //Struttura //old stuff
    protected $oStruct = null;

    //Flags
    protected $sFlags = "";

    //legacy Flags
    protected $sLegacyFlags = "";

    //Livello; //old stuff
    protected $nLivello = 3;

    //Flag disabilitato;
    protected $nDisabled = 1;

    //Flag di validità
    protected $bIsValid = false;

    //Flag utente corrente
    private $bCurrentUser = false;

    //legacy data
    protected $aLegacyData=array("id_assessorato"=>0,"id_direzione"=>0,"id_servizio"=>0,"level"=>3,"flags"=>"");
    public function GetLegacyData()
    {
        return $this->aLegacyData;
    }

    //status
    protected $nStatus=0;
    public function GetStatus()
    {
        return $this->nStatus;
    }

    //gruppi utente (primari)
    protected $aGroups=array();
    public function GetGroups()
    {
        return $this->aGroups;
    }

    //Restituisce una stringa formattata con i flags
    public function GetLabelFlags()
    {
        $flags=$this->GetFlags(true);
        $platformFlags=AA_Platform::GetAllModulesFlags();
        $result="";
        foreach($flags as $curFlag)
        {
            if($curFlag=="accessi") $result.="<span class='AA_Label AA_Label_LightGreen'>RIA</span>&nbsp;";
            if($curFlag=="admin_accessi") $result.="<span class='AA_Label AA_Label_LightGreen'>RIA(adm)</span>&nbsp;";
            //($curFlag=="art22") $result.="<span class='AA_Label AA_Label_LightGreen'>SINES</span>&nbsp;";
            //if($curFlag=="art22_admin") $result.="<span class='AA_Label AA_Label_LightGreen'>SINES(adm)</span>&nbsp;";
            //if($curFlag=="art23") $result.="<span class='AA_Label AA_Label_LightGreen'>GESPA</span>&nbsp;";
            //if($curFlag=="patrimonio") $result.="<span class='AA_Label AA_Label_LightGreen'>GESPI</span>&nbsp;";
            //if($curFlag=="sier") $result.="<span class='AA_Label AA_Label_LightGreen'>SIER</span>&nbsp;";
            if($curFlag=="processi") $result.="<span class='AA_Label AA_Label_LightGreen'>SIMAP</span>&nbsp;";
            if($curFlag=="processi_admin") $result.="<span class='AA_Label AA_Label_LightGreen'>SIMAP(adm)</span>&nbsp;";
            if($curFlag=="incarichi") $result.="<span class='AA_Label AA_Label_LightGreen'>Incarichi</span>&nbsp;";
            if($curFlag=="incarichi_titolari") $result.="<span class='AA_Label AA_Label_LightGreen'>Incarichi(adm)</span>&nbsp;";
            if(isset($platformFlags[$curFlag])) $result.="<span class='AA_Label AA_Label_LightGreen'>".$platformFlags[$curFlag]."</span>&nbsp;";
        }
        
        return $result;
    }

    //ruolo
    public function GetRuolo($bNumeric=false)
    {

        $ruolo=static::AA_USER_GROUP_GUESTS;
        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if($this->nLivello==0) $ruolo=static::AA_USER_GROUP_ADMINS;
            if($this->nLivello==1) $ruolo=static::AA_USER_GROUP_OPERATORS;
        }

        if(array_search(static::AA_USER_GROUP_USERS,$this->aGroups) !== false) $ruolo=static::AA_USER_GROUP_USERS;
        if(array_search(static::AA_USER_GROUP_OPERATORS,$this->aGroups) !== false) $ruolo=static::AA_USER_GROUP_OPERATORS;
        if(array_search(static::AA_USER_GROUP_ADMINS,$this->aGroups) !==false) $ruolo=static::AA_USER_GROUP_ADMINS;
        if(array_search(static::AA_USER_GROUP_SERVEROPERATORS,$this->aGroups) !==false) $ruolo=static::AA_USER_GROUP_SERVEROPERATORS;
        if(array_search(static::AA_USER_GROUP_SUPERUSER,$this->aGroups) !==false) $ruolo=static::AA_USER_GROUP_SUPERUSER;
        
        if($bNumeric) return $ruolo;

        $ruoli=static::GetDefaultGroups();

        if($ruolo > 0) return $ruoli[$ruolo];
        else return "Ospite";
    }
    public function GetRole($bNumeric=false)
    {
        return $this->GetRuolo($bNumeric);
    }

    public function IsServerOperator()
    {
        if(array_search(static::AA_USER_GROUP_SERVEROPERATORS,$this->aGroups) !==false) return true;
        return false;
    }

    public function isAdministrator()
    {
        if(array_search(static::AA_USER_GROUP_ADMINS,$this->aGroups) !==false) return true;
        return false;
    }

    public function isOperator()
    {
        if(array_search(static::AA_USER_GROUP_OPERATORS,$this->aGroups) !==false) return true;
        return false;
    }

    static public function GetDefaultGroups()
    {
        return array(
            1=>"Super utente",
            2=>"Amministratore",
            3=>"Operatore",
            4=>"Utente",
            5=>"Operatore server"
        );
    }

    //gruppi utente compresi quelli secondari 
    protected $aAllGroups=array();
    protected $aSecondaryGroups=array();
    public function GetAllGroups()
    {
        if(sizeof($this->aGroups) > 0 && sizeof($this->aAllGroups) == 0)
        {
            $this->LoadAllGroups();
        }

        return $this->aAllGroups;
    }
    protected function LoadAllGroups()
    {
        if(sizeof($this->aGroups)==0) return array();

        if($this->IsServerOperator())
        {
            $this->aSecondaryGroups[]=AA_User::AA_USER_GROUP_ADMINS;
            $this->aSecondaryGroups[]=AA_User::AA_USER_GROUP_OPERATORS;
            $this->aSecondaryGroups[]=AA_User::AA_USER_GROUP_USERS;
        }

        foreach($this->aGroups as $curGroup)
        {
            $this->aSecondaryGroups=array_merge($this->aSecondaryGroups,AA_Group::GetDescendants($curGroup));
        }

        $this->aAllGroups=array_unique(array_merge($this->aGroups,$this->aSecondaryGroups));

        //AA_Log::Log(__METHOD__." - allGroups: ".print_r($this->aAllGroups,true)." - secondary: ".print_r($this->aSecondaryGroups,true)." - Primary: ".print_r($this->aGroups,true),100);
    }

    public function __construct()
    {
        //AA_Log::Log(get_class() . "__construct()");

        $this->oStruct = new AA_Struct();
    }

    //Login multiplo
    protected $nConcurrent=0;
    public function IsConcurrentEnabled()
    {
        if($this->nConcurrent>0) return true;

        return false;
    }

    //Verifica se l'utente è valido
    public function IsValid()
    {
        return $this->bIsValid;
    }

    //restituisce l'immagine associata all'utente (percorso pubblico)
    public function GetProfileImagePublicPath()
    {
        if(AA_Const::AA_ROOT_STORAGE_PATH==null || AA_Const::AA_ROOT_STORAGE_PATH =="")
        {
            $imgFile=AA_Const::AA_APP_FILESYSTEM_FOLDER."/immagini/profili/".$this->GetImage();
            if(is_file($imgFile))
            {
                return AA_Const::AA_WWW_ROOT."/immagini/profili/".$this->GetImage();
            }
            else
            {
                return AA_Const::AA_WWW_ROOT."/immagini/profili/generic.png";
            }    
        }
        else
        {
            $storage=AA_Storage::GetInstance();
            if($storage->IsValid())
            {
                $file=$storage->GetFileByHash($this->GetImage());
                if($file->IsValid())
                {
                    return AA_Const::AA_WWW_ROOT."/storage.php?object=".$this->GetImage();
                }
            }
            return AA_Const::AA_WWW_ROOT."/immagini/profili/generic.png";
        }
    }
    
    //restituisce l'immagine associata all'utente (percorso locale)
    public function GetProfileImageLocalPath()
    {
        if(AA_Const::AA_ROOT_STORAGE_PATH==null || AA_Const::AA_ROOT_STORAGE_PATH =="")
        {
            $imgfile=AA_Const::AA_APP_FILESYSTEM_FOLDER."/immagini/profili/".$this->GetImage();
            if(is_file($imgfile)) return $imgfile;
            else return AA_Const::AA_APP_FILESYSTEM_FOLDER."/immagini/profili/generic.png";    
        }
        else
        {
            $storage=AA_Storage::GetInstance();
            if($storage->IsValid())
            {
                $file=$storage->GetFileByHash($this->GetImage());
                if($file->IsValid())
                {
                    return AA_Const::AA_ROOT_STORAGE_PATH.DIRECTORY_SEPARATOR.$file->GetFilePath();
                }
            }
            return AA_Const::AA_APP_FILESYSTEM_FOLDER."/immagini/profili/generic.png";
        }
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

    //numero di telefono
    protected $sPhone="";
    public function GetPhone()
    {
        return $this->sPhone;
    }

    //last login time
    protected $sLastLogin="";
    public function GetLastLogin()
    {
        return $this->sLastLogin;
    }

    //ultima volta in cui è stato notificato il reset della password
    protected $sLastNotify="";
    public function GetLastNotify()
    {
        return $this->sLastNotify;
    }

    //immagine del profilo
    protected $sImage="";
    public function GetImage()
    {
        return $this->sImage;
    } 

    public function IsAdmin()
    {
        //if(AA_Const::AA_ENABLE_LEGACY_DATA)
        //{
        //    return $this->IsSuperUser();
        //}
        
        if(array_search(AA_USER::AA_USER_GROUP_ADMINS,$this->GetGroups())) return true;

        return false;
    }

    //Verifica se è l'utente super user
    public function IsSuperUser()
    {
        if ($this->nID == 1) return true;
        
        //if(AA_Const::AA_ENABLE_LEGACY_DATA && $this->HasFlag("SU")) return true;

        //gruppo super user
        if(array_search(AA_USER::AA_USER_GROUP_SUPERUSER,$this->GetGroups()) !==false) return true;

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
        //AA_Log::Log(get_class() . "->LoadUser($id_user)");
        $user = new AA_User();
        $user->bCurrentUser = false;

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT ".static::AA_DB_TABLE.".* from ".static::AA_DB_TABLE." where id = '" . addslashes($id_user) . "'");
        if ($db->GetAffectedRows() > 0) 
        {
            $row = $db->GetResultSet();
            $info=json_decode($row[0]['info'],true);
            if($info==null)
            {
                AA_Log::Log(__METHOD__." - errore nel parsing dei dati info: ".$row[0]['info'],100);
            }

            $user->nID = $row[0]['id'];
            if(is_array($info))
            {
                $user->sNome = $info['nome'];
                $user->sCognome = $info['cognome'];    
                $user->sImage = $info['image'];
                $user->sCf = $info['cf'];
                $user->sPhone = $info['phone'];    
            }

            $user->sUser = $row[0]['user'];
            $user->sEmail = $row[0]['email'];
            $user->nStatus= $row[0]['status'];
            if($user->nStatus==AA_User::AA_USER_STATUS_DISABLED) $user->nDisabled=1;
            else $user->nDisabled=0;

            $user->aGroups=explode(",",$row[0]['groups']);
            
            $user->LoadAllGroups();
            
            $user->sLastLogin = $row[0]['lastlogin'];
            $user->sLastNotify = $row[0]['lastnotify'];
            $user->sFlags = $row[0]['flags'];
            $user->bIsValid = true;

            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                if($user->nStatus==AA_User::AA_USER_STATUS_DISABLED) $user->nDisabled=1;
                else $user->nDisabled=0;

                $legacy_data=json_decode($row[0]['legacy_data'],true);
                if(is_array($legacy_data))
                {
                    $user->aLegacyData=$legacy_data;
                    $user->oStruct = AA_Struct::GetStruct($legacy_data['id_assessorato'], $legacy_data['id_direzione'], $legacy_data['id_servizio']);
                    $user->nLivello=$legacy_data['level'];
                    if(is_array($legacy_data['flags']))$user->sLegacyFlags=implode("|",$legacy_data['flags']);
                    else $user->sLegacyFlags=$legacy_data['flags'];
                }
                else
                {
                    AA_Log::Log(__METHOD__." - errore nel parsing dei dati legacy: ".$row[0]['legacy_data'],100);
                }
            }

            //Concurrent flag
            if(strpos($user->sFlags,"concurrent")!==false || strpos($user->sLegacyFlags,"concurrent")!==false)
            {
                //AA_Log::Log(__METHOD__." - accesso concorrente abilitato: ".$row[0]['user'],100);
                $user->nConcurrent=1;
            }

            return $user;
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            return static::LegacyLoadUser($id_user);
        }

        return $user;
    }

    //Popola i dati dell'utente (legacy)
    static public function LegacyLoadUser($id_user)
    {
        //AA_Log::Log(get_class() . "->LoadUser($id_user)");

        $user = new AA_User();
        $user->bCurrentUser = false;

        $db = new AA_AccountsDatabase();
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
            $user->sImage = $row[0]['image'];
            $user->sPhone = $row[0]['phone'];
            $user->sLastLogin = $row[0]['lastlogin'];
            $user->sLegacyFlags = $row[0]['flags'];
            $user->nConcurrent = $row[0]['concurrent'];
            $user->bIsValid = true;

            //Popola i dati della struttura
            $user->oStruct = AA_Struct::GetStruct($row[0]['id_assessorato'], $row[0]['id_direzione'], $row[0]['id_servizio']);
        }

        return $user;
    }

    //Reset password email params
    protected static $aResetPasswordEmailParams=array(
        "oggetto"=>'Amministrazione Aperta - Credenziali accesso.',
        "incipit"=>"<p>Buongiorno,<br>Di seguito le credenziali per l'accesso alla piattaforma applicativa \"Amministrazione Aperta\":<br>url d'accesso: https://#www#",
        "bShowStruct"=>true,
        "sendToUs"=>true,
        "post"=>'<p>Per ragioni di sicurezza e\' preferibile effettuare l\'accesso in piattaforma nella modalita\' email/otp.</p>E\' possibile cambiare la password accedendo al proprio profilo utente, dopo aver effettuato il login sulla piattaforma.<br>E&apos; anche possibile effettuare il login sulla piattaforma indicando l\'indirizzo email in vece del nome utente.<br>Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>',
        "firma"=>'<div>--
        <div><strong>Amministrazione Aperta</strong></div>
        <div>Presidentzia</div>
        <div>Presidenza</div>
        <div>V.le Trento, 69 - 09123 Cagliari</div>
        <img src="https://#www#/immagini/logo.jpg" data-mce-src="https://#www#/immagini/logo.jpg" moz-do-not-send="true" width="205" height="60"></div>'
    );

    protected static $aSendCredentialsEmailParams=array(
        "oggetto"=>'Amministrazione Aperta - Credenziali accesso.',
        "incipit"=>"<p>Buongiorno,<br>Di seguito le credenziali per l'accesso alla piattaforma applicativa \"Amministrazione Aperta\":<br>url d'accesso: https://#www#",
        "bShowStruct"=>true,
        "sendToUs"=>true,
        "post"=>'<p>Per ragioni di sicurezza e\' preferibile effettuare l\'accesso in piattaforma nella modalita\' email/otp.</p>Per le richieste di supporto o la segnalazione di anomalie è disponibile la casella: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p>',
        "firma"=>'<div>--
        <div><strong>Amministrazione Aperta</strong></div>
        <div>Presidentzia</div>
        <div>Presidenza</div>
        <div>V.le Trento, 69 - 09123 Cagliari</div>
        <img src="https://#www#/immagini/logo.jpg" data-mce-src="https://#www#/immagini/logo.jpg" moz-do-not-send="true" width="205" height="60"></div>'
    );


    //send OTP password email params
    protected static $aOTPAuthEmailParams=array(
        "oggetto"=>'Amministrazione Aperta - OTP accesso piattaforma.',
        "incipit"=>"<p>Gentile collega,<br>è pervenuta una richiesta di accesso alla piattaforma applicativa \"<b><i>A</i></b>mministrazione <b><i>A</i></b>perta\" con il tuo indirizzo email.<br>Di seguito il codice temporaneo per l'accesso; hai a disposizione 5 minuti per utilizzarlo, trascorsi i quali dovrai ripetere la procedura.",
        "post"=>'Qualora non dovessi essere tu l\'autore della richiesta ti invitiamo a segnalarci l\'anomalia inviando una mail alla casella:: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p><p>Cordiali Saluti.</p>',
        "firma"=>'<div>--
        <div><strong><i>A</i>mministrazione <i>A</i>perta</strong></div>
        <div>Presidenza</div>
        <div>Direzione generale della Presidenza</div>
        <div>Servizio supporti direzionali</div>
        <div>V.le Trento, 69 - 09123 Cagliari</div>
        <div>url d\'accesso: https://#www#</div>
        <div>email: amministrazioneaperta@regione.sardegna.it</div>
        <img src="https://#www#/immagini/logo.jpg" data-mce-src="https://#www#/immagini/logo.jpg" moz-do-not-send="true" width="205" height="60"></div>'
    );

    //send OTP change password email params
    protected static $aOTPChangePwdEmailParams=array(
        "oggetto"=>'Amministrazione Aperta - OTP verifica utente.',
        "incipit"=>"<p>Gentile collega,<br>è pervenuta una richiesta di reimpostazione della password d'accesso alla piattaforma applicativa \"<b><i>A</i></b>mministrazione <b><i>A</i></b>perta\" con il tuo indirizzo email.<br>Di seguito il codice temporaneo di verifica; hai a disposizione 5 minuti per utilizzarlo, trascorsi i quali dovrai ripetere la procedura.",
        "post"=>'Qualora non dovessi essere tu l\'autore della richiesta ti invitiamo a segnalarci l\'anomalia inviando una mail alla casella:: <a href="mailto:amministrazioneaperta@regione.sardegna.it">amministrazioneaperta@regione.sardegna.it</a></p><p>Cordiali Saluti.</p>',
        "firma"=>'<div>--
        <div><strong><i>A</i>mministrazione <i>A</i>perta</strong></div>
        <div>Presidenza</div>
        <div>Direzione generale della Presidenza</div>
        <div>Servizio supporti direzionali</div>
        <div>V.le Trento, 69 - 09123 Cagliari</div>
        <div>url d\'accesso: https://#www#</div>
        <div>email: amministrazioneaperta@regione.sardegna.it</div>
        <img src="https://#www#/immagini/logo.jpg" data-mce-src="https://#www#/immagini/logo.jpg" moz-do-not-send="true" width="205" height="60"></div>'
    );

    public static function SetResetPwdEmailParams($params=array())
    {
        foreach($params as $key=>$val)
        {
            if(isset(static::$aResetPasswordEmailParams[$key])) static::$aResetPasswordEmailParams[$key]=$val;
        }
    }

    public static function SetSendCredentialsEmailParams($params=array())
    {
        foreach($params as $key=>$val)
        {
            if(isset(static::$aSendCredentialsEmailParams[$key])) static::$aSendCredentialsEmailParams[$key]=$val;
        }
    }

    public static function GetResetPwdEmailParams()
    {
        $params=static::$aResetPasswordEmailParams;
        foreach($params as $key=>$param)
        {
            $params[$key]=str_replace("#www#",AA_Const::AA_DOMAIN_NAME.AA_Const::AA_WWW_ROOT,$param);
        }

        return $params;
    }

    //Popola i dati dell'utente a partire dal nome utente
    static public function LoadUserFromUserName($userName)
    {
        //AA_Log::Log(get_class() . "LoadUserFromUserName($userName)");

        $user = new AA_User();
        $user->bCurrentUser = false;

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT ".static::AA_DB_TABLE.".id from ".static::AA_DB_TABLE." where user = '" . $userName . "' and status >= 0 LIMIT 1");
        if($db->GetAffectedRows() > 0)
        {
            $row = $db->GetResultSet();
             return static::LoadUser($row[0]['id']);
        }
        
        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
             return static::LegacyLoadUserFromUserName($userName);
        }

        return $user;
    }

    //Popola i dati dell'utente a partire dal nome utente (legacy)
    static public function LegacyLoadUserFromUserName($userName)
    {
        //AA_Log::Log(get_class() . "LoadUserFromUserName($userName)");

        $user = new AA_User();
        $user->bCurrentUser = false;

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT utenti.* from utenti where user = '" . $userName . "' and eliminato='0'");
        if($db->GetAffectedRows() > 0)
        {
            $row = $db->GetResultSet();

            $user->nID = $row[0]['id'];
            $user->sNome = $row[0]['nome'];
            $user->sCognome = $row[0]['cognome'];
            $user->sUser = $row[0]['user'];
            $user->sEmail = $row[0]['email'];
            $user->nLivello = $row[0]['livello'];
            $user->nDisabled = $row[0]['disable'];
            $user->sImage = $row[0]['image'];
            $user->sPhone = $row[0]['phone'];
            $user->sLegacyFlags = $row[0]['flags'];
            $user->sLastLogin = $row[0]['lastlogin'];
            $user->nConcurrent = $row[0]['concurrent'];
            $user->bIsValid = true;

            //Popola i dati della struttura
            $user->oStruct = AA_Struct::GetStruct($row[0]['id_assessorato'], $row[0]['id_direzione'], $row[0]['id_servizio']);
        }
        
        return $user;
    }

    //Restituisce un array di oggetti AA_User
    static public function LoadUsersFromEmail($email,$bLegacyUsers=true)
    {
        $users = array();

        if(AA_Const::AA_ENABLE_LEGACY_DATA && $bLegacyUsers)
        {
            $users = static::LegacyLoadUsersFromEmail($email);
        }

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT ".static::AA_DB_TABLE.".id from ".static::AA_DB_TABLE." where email = '" . $email . "' and status > 0 ORDER by lastlogin desc");
        if($db->GetAffectedRows() > 0)
        {
            $rs = $db->GetResultSet();
            foreach($rs as $curRow)
            {
                $user = static::LoadUser($curRow['id']);    
                $users[$curRow['id']] = $user;    
            }
        }

        return $users;
    }

    static public function LoadLastLoggedUsers($bLegacyUsers=true)
    {
        $users = array();

        if(AA_Const::AA_ENABLE_LEGACY_DATA && $bLegacyUsers)
        {
            $users = static::LegacyLoadLastLoggedUsers();
        }

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT max(".static::AA_DB_TABLE.".id) from ".static::AA_DB_TABLE." where lastlogin != '' and status > 0 and email != '' GROUP BY email ORDER by lastlogin desc");
        if($db->GetAffectedRows() > 0)
        {
            $rs = $db->GetResultSet();
            foreach($rs as $curRow)
            {
                $user = static::LoadUser($curRow['id']);    
                $users[$curRow['id']] = $user;    
            }
        }

        return $users;
    }

    //Restituisce la lista dei profili per l'utente corrente
    public function GetProfiles()
    {
        if(!$this->IsValid()) return array();

        return static::LoadUsersFromEmail($this->GetEmail(),false);
    }

    //Popola i dati dell'utente a partire dal nome utente
    //Restituisce un array di oggetti AA_User
    static public function LegacyLoadUsersFromEmail($email)
    {
        AA_Log::Log(get_class() . "->LoadUserFromEmail($email)");

        $users = array();

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT utenti.* from utenti where email = '" . addslashes($email) . "' and eliminato='0' and disable='0'");
        if($db->GetAffectedRows() > 0)
        {
            $rs = $db->GetResultSet();
            foreach($rs as $curRow)
            {
                $user = new AA_User();

                $user->nID = $curRow['id'];
                $user->sNome = $curRow['nome'];
                $user->sCognome = $curRow['cognome'];
                $user->sUser = $curRow['user'];
                $user->sEmail = $curRow['email'];
                $user->nLivello = $curRow['livello'];
                $user->sLegacyFlags = $curRow['flags'];
                $user->sImage = $curRow['image'];
                $user->sPhone = $curRow['phone'];
                $user->nDisabled = $curRow['disable'];
                $user->sLastLogin = $curRow['lastlogin'];
                $user->nConcurrent = $curRow['concurrent'];
                $user->bCurrentUser = false;
                $user->bIsValid = true;
    
                //Popola i dati della struttura
                $user->oStruct = AA_Struct::GetStruct($curRow['id_assessorato'], $curRow['id_direzione'], $curRow['id_servizio']);
    
                $users[$curRow['id']] = $user;    
            }
        }

        return $users;
    }

    static public function LegacyLoadLastLoggedUsers()
    {
        //AA_Log::Log(get_class() . "->LoadUserFromEmail($email)");

        $users = array();

        $db = new AA_AccountsDatabase();
        $db->Query("SELECT utenti.* from utenti where lastlogin != '' and email !='' and eliminato='0' and disable='0'");
        if($db->GetAffectedRows() > 0)
        {
            $rs = $db->GetResultSet();
            foreach($rs as $curRow)
            {
                $user = new AA_User();

                $user->nID = $curRow['id'];
                $user->sNome = $curRow['nome'];
                $user->sCognome = $curRow['cognome'];
                $user->sUser = $curRow['user'];
                $user->sEmail = $curRow['email'];
                $user->nLivello = $curRow['livello'];
                $user->sLegacyFlags = $curRow['flags'];
                $user->sImage = $curRow['image'];
                $user->sPhone = $curRow['phone'];
                $user->nDisabled = $curRow['disable'];
                $user->sLastLogin = $curRow['lastlogin'];
                $user->nConcurrent = $curRow['concurrent'];
                $user->bCurrentUser = false;
                $user->bIsValid = true;
    
                //Popola i dati della struttura
                $user->oStruct = AA_Struct::GetStruct($curRow['id_assessorato'], $curRow['id_direzione'], $curRow['id_servizio']);
    
                $users[$curRow['id']] = $user;    
            }
        }

        return $users;
    }

    //temporary auth (autenticazione valida solamente per questa esecuzione dello script)
    static public function TemporaryUserAuth($sUserName = "", $sUserPwd = "")
    {
        return static::UserAuth("",$sUserName,$sUserPwd,false,true);
    }

    //Autenticazione
    static public function UserAuth($sToken = "", $sUserName = "", $sUserPwd = "", $remember_me=false,$bTemporary=false)
    {
        //AA_Log::Log(get_class()."->UserAuth($sToken,$sUserName, $sUserPwd)");

        $db = new AA_AccountsDatabase();
        $user = AA_User::Guest();

        if ($sUserName != "" && $sUserPwd != "") 
        {
            //AA_Log::Log(__METHOD__."($sToken,$sUserName, $sUserPwd)",100);
            $rs=null;

            if (filter_var($sUserName, FILTER_VALIDATE_EMAIL)) 
            {
                //Login tramite email
                //AA_Log::Log(__METHOD__." - autenticazione in base alla mail.");
                $query_utenti = "SELECT ".static::AA_DB_TABLE.".* FROM ".static::AA_DB_TABLE." WHERE ".static::AA_DB_TABLE.".email = '".addslashes(trim($sUserName))."' AND status>=0";

                if($db->Query($query_utenti))
                {
                    if($db->GetAffectedRows()>0)
                    {
                        $result = $db->GetResultSet();
                        foreach($result as $curRow)
                        {
                            if(AA_Utils::password_verify($sUserPwd,$curRow['passwd']) || AA_Utils::password_verify(md5($sUserPwd),$curRow['passwd']))
                            {
                                $rs=$curRow;
                                break;
                            }
                        }
                        if(!is_array($rs))
                        {
                            AA_Log::Log(__METHOD__." - Credenziali errate.", 100);
                            return AA_User::Guest();    
                        }
                    }
                    else
                    {
                        AA_Log::Log(__METHOD__." - Non sono stati trovati utenti associati alla email indicata.", 100);
                    }
                } 
                else 
                {
                    AA_Log::Log(__METHOD__ . " - errore nell'accesso al db: " . $db->GetErrorMessage(), 100);
                    AA_Log::Log(__METHOD__ . " - errore di sistema.", 100);
                    return AA_User::Guest();
                }    
            } 
            else
            {
                //Login ordinario tramite username
                $query_utenti = sprintf("SELECT ".static::AA_DB_TABLE.".* FROM ".static::AA_DB_TABLE." WHERE ".static::AA_DB_TABLE.".user = '%s' AND status >= 0", addslashes(trim($sUserName)));
                if($db->Query($query_utenti))
                {
                    if($db->GetAffectedRows()>0)
                    {
                        $result = $db->GetResultSet();
                        if(AA_Utils::password_verify($sUserPwd,$result[0]['passwd']) || AA_Utils::password_verify(md5($sUserPwd),$result[0]['passwd']))
                        {
                            $rs=$result[0];
                        }
                        else
                        {
                            AA_Log::Log(__METHOD__." - Credenziali errate.", 100);
                            return AA_User::Guest();    
                        }
                    }
                    else
                    {
                        AA_Log::Log(__METHOD__." - Non sono stati trovati utenti con l'username indicato.", 100);
                    }
                } 
                else 
                {
                    AA_Log::Log(__METHOD__ . " - errore nell'accesso al db: " . $db->GetErrorMessage(), 100);
                    AA_Log::Log(__METHOD__ . " - errore di sistema.", 100);
                    return AA_User::Guest();
                }    
            }

            if (is_array($rs)) 
            {
                if ($rs['status'] == AA_USER::AA_USER_STATUS_DISABLED) {
                    AA_Log::Log(__METHOD__." - L'utente è disattivato (id: " . $rs["id"] . ").", 100);
                    return AA_User::Guest();
                }

                if ($rs['status'] == AA_User::AA_USER_STATUS_DELETED) {
                    AA_Log::Log(__METHOD__." - L'utente è stato disattivato permanentemente (id: " . $rs["id"] . ").", 100);
                    return AA_User::Guest();
                }

                if ($rs['status'] == AA_User::AA_USER_STATUS_ENABLED) 
                {
                    $user = AA_User::LoadUser($rs['id']);
                    $user->bCurrentUser = true;

                    if($bTemporary)
                    {
                        static::$oCurrentUser=$user;
                        return $user;
                    }

                    if(AA_Const::AA_ENABLE_LEGACY_DATA)
                    {
                        //Old stuff compatibility
                        $_SESSION['user'] = $user->GetUsername();
                        $_SESSION['nome'] = $user->GetNome();
                        $_SESSION['cognome'] = $user->GetCognome();
                        $_SESSION['email'] = $user->GetEmail();
                        $_SESSION['user_home'] = "reserved/index.php";
                        $_SESSION['id_user'] = $user->GetId();
                        $_SESSION['id_utente'] = $user->GetId();

                        $struct=$user->GetStruct();

                        $_SESSION['id_assessorato'] = $struct->GetAssessorato(true);
                        $_SESSION['tipo_struct'] = $struct->GetTipo();
                        $_SESSION['id_direzione'] = $struct->GetDirezione(true);
                        $_SESSION['id_servizio'] = $struct->GetServizio(true);
                        $_SESSION['id_settore'] = 0;
                        $_SESSION['livello'] = $user->GetLevel();
                        $_SESSION['level'] = $user->GetLevel();
                        $_SESSION['assessorato'] = $struct->GetAssessorato();
                        $_SESSION['direzione'] = $struct->GetDirezione();
                        $_SESSION['servizio'] = $struct->GetServizio();
                        $_SESSION['settore'] = "";
                        $_SESSION['user_flags'] = $user->GetFlags();
                        $_SESSION['flags'] = $user->GetFlags();
                        
                        AA_Log::LogAction($user->GetId(), 0, "Log In"); //old stuff
                    }

                    $concurrent=false;
                    if($user->IsConcurrentEnabled()) $concurrent=true;
                    
                    //imposta l'utente corrente
                    static::$oCurrentUser=$user;

                    if(!$bTemporary)
                    {
                        $_SESSION['token'] = AA_User::GenerateToken($user->GetId(),$remember_me,$concurrent);

                        if($remember_me)
                        {
                            //token di autenticazione valido per 30 giorni, utilizzabile solo in https.
                            setcookie("AA_AUTH_TOKEN",$_SESSION['token'],time()+(86400 * 30), "/",AA_Const::AA_DOMAIN_NAME,true, true);
                        }
    
                        //update last login time
                        $db->Query("UPDATE ".static::AA_DB_TABLE." set lastlogin = '".date("Y-m-d")."' WHERE id='".$user->GetId()."' LIMIT 1");
                    }

                    return $user;
                }

                AA_Log::Log(__METHOD__." - Errore nel caricamento dei dati utente.", 100);
                return AA_User::Guest();
            }

            if(AA_Const::AA_ENABLE_LEGACY_DATA && AA_Const::AA_MIGRATE_LEGACY_USERS)
            {
                AA_Log::Log(__METHOD__." - legacy login", 100);
                $user=AA_User::legacyUserAuth($sToken,$sUserName,md5($sUserPwd),$remember_me);

                if($user->IsValid())
                {
                    AA_Log::Log(__METHOD__." - Migrazione utente legacy: ".$user->GetNome()." ".$user->GetCognome()." (".$user->GetId().")",100);
                    static::MigrateLegacyUser($user, $sUserPwd);

                    return $user;
                }
            }

            return $user;
        }

        if($bTemporary) return AA_User::Guest();

        if ($sToken == null || $sToken == "" || $_COOKIE["AA_AUTH_TOKEN"] !="") 
        {
            if(static::$oCurrentUser instanceof AA_User) return static::$oCurrentUser;

            if(isset($_SESSION['token'])) $sToken = $_SESSION['token'];
            if($sToken == "" && isset($_COOKIE["AA_AUTH_TOKEN"]))
            {   
                $sToken=$_COOKIE["AA_AUTH_TOKEN"];
                AA_Log::Log(__METHOD__." - auth token login.",100);
            }
        }

        if ($sToken != null) {
            //AA_Log::Log(__METHOD__." - autenticazione in base al token.");

            $token_timeout_m = 30;
            $query_token = sprintf("SELECT * FROM tokens where (TIMESTAMPDIFF(MINUTE,data_rilascio, NOW()) < '%s' OR remember_me='1') and token ='%s' order by data_rilascio DESC LIMIT 1", $token_timeout_m, $sToken);

            if ($db->Query($query_token)) {
                $rs = $db->GetResultSet();
            } else {
                AA_Log::Log(__METHOD__ . " - errore nell'accesso al db: " . $db->GetErrorMessage(), 100);
                return AA_User::Guest();
            }

            if ($db->GetAffectedRows() > 0) 
            {
                if (strcmp($rs[0]['token'], $sToken) == 0) {
                    //AA_Log::Log(__METHOD__." - Authenticate token ($sToken) - success", 50);

                    $user = AA_User::LoadUser($rs[0]['id_utente']);
                    if ($user->IsDisabled()) {
                        AA_Log::Log(__METHOD__." - L'utente è disattivato.", 100);
                        return AA_User::Guest();
                    }

                    if(AA_Const::AA_ENABLE_LEGACY_DATA)
                    {
                        //Old stuff compatibility
                        $_SESSION['user'] = $user->GetUsername();
                        $_SESSION['nome'] = $user->GetNome();
                        $_SESSION['cognome'] = $user->GetCognome();
                        $_SESSION['email'] = $user->GetEmail();
                        $_SESSION['user_home'] = "admin/index.php";
                        $_SESSION['id_user'] = $user->GetId();
                        $_SESSION['id_utente'] = $user->GetId();
                        $struct=$user->GetStruct();
                        $_SESSION['id_assessorato'] = $struct->GetAssessorato(true);
                        $_SESSION['tipo_struct'] = $struct->GetTipo();
                        $_SESSION['id_direzione'] = $struct->GetDirezione(true);
                        $_SESSION['id_servizio'] = $struct->GetServizio(true);
                        $_SESSION['id_settore'] = 0;
                        $_SESSION['livello'] = $user->GetLevel();
                        $_SESSION['level'] = $user->GetLevel();
                        $_SESSION['assessorato'] = $struct->GetAssessorato();
                        $_SESSION['direzione'] = $struct->GetDirezione();
                        $_SESSION['servizio'] = $struct->GetServizio();
                        $_SESSION['settore'] = "";
                        $_SESSION['user_flags'] = $user->GetFlags();
                        $_SESSION['flags'] = $user->GetFlags();

                        //update last login time
                        $db->Query("UPDATE utenti set lastlogin = NOW() WHERE id='".$user->GetId()."' LIMIT 1");
                    }

                    //Rinfresco della durata del token
                    AA_User::RefreshToken($sToken);
                    $_SESSION['token'] = $sToken;

                    $user->bCurrentUser = true;

                    //update last login time
                    $db->Query("UPDATE ".static::AA_DB_TABLE." set lastlogin = NOW() WHERE id='".$user->GetId()."' LIMIT 1");
                    
                    return $user;
                }
            }

            //Old stuff
            //if (isset($log)) AA_Log::LogAction(), 0, "Authenticate token ($sToken) - failed");
            //----------

            AA_Log::Log(get_class() . "->UserAuth($sToken) - Authenticate token ($sToken) - failed", 100);
            return AA_User::Guest();
        }

        AA_Log::Log(get_class() . "->UserAuth($sToken,$sUserName) - Autenticazione fallita.", 100);
        return AA_User::Guest();
    }

    public function GetSSOAuthToken()
    {
        if(!$this->IsValid())
        {
            return "";
        }

        if(!$this->isCurrentUser())
        {
            return "";
        }

        if($_SESSION['token'])
        {
            return crypt($_SESSION['token'],uniqid());
        }
    }

    static public function VerifySSOAuthToken($token='',$bRegisterToken=false)
    {
        if($token=='') return false;

        $db=new AA_AccountsDatabase();

        $query="SELECT token FROM tokens ORDER by data_rilascio DESC";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__," - errore: ".$db->GetErrorMessage(),100);
        }

        $rs=$db->GetResultSet();
        foreach($rs as $curToken)
        {
            if (crypt($curToken['token'], $token)==$token)
            {
                $savedToken = $_SESSION['token'];
                $curUser=AA_User::UserAuth($curToken['token']);

                if($curUser->IsValid()) 
                {
                    if(!$bRegisterToken) AA_User::UserAuth($savedToken);

                    return true;
                }
                else
                {
                    if(!$bRegisterToken) AA_User::UserAuth($savedToken);

                    AA_Log::Log(__METHOD__," - errore: sso token scaduto o non valido.",100);
                    return false;
                }
            }
        }

        AA_Log::Log(__METHOD__," - errore: sso token non valido.",100);
        return false;
    }

    //Autenticazione legacy (md5 password)
    static public function legacyUserAuth($sToken = "", $sUserName = "", $sUserPwd = "", $remember_me=false)
    {
        //AA_Log::Log(get_class()."->legacyUserAuth($sToken,$sUserName, $sUserPwd)",100);

        $db = new AA_AccountsDatabase();

        if ($sUserName != null && $sUserPwd != null) {
            AA_Log::Log(__METHOD__." - autenticazione in base al nome utente.");

            if (filter_var($sUserName, FILTER_VALIDATE_EMAIL)) {
                //Login tramite email
                AA_Log::Log(__METHOD__." - autenticazione in base alla mail.");
                $query_utenti = sprintf("SELECT utenti.*,assessorati.tipo, assessorati.descrizione as assessorato, direzioni.descrizione as direzione, servizi.descrizione as servizio FROM utenti left join assessorati on utenti.id_assessorato=assessorati.id left join direzioni on utenti.id_direzione=direzioni.id left join servizi on utenti.id_servizio=servizi.id WHERE utenti.email = '%s' AND passwd= '%s' ", addslashes($sUserName), addslashes($sUserPwd));
            } else {
                //Login ordinario tramite username
                $query_utenti = sprintf("SELECT utenti.*,assessorati.tipo, assessorati.descrizione as assessorato, direzioni.descrizione as direzione, servizi.descrizione as servizio FROM utenti left join assessorati on utenti.id_assessorato=assessorati.id left join direzioni on utenti.id_direzione=direzioni.id left join servizi on utenti.id_servizio=servizi.id WHERE user = '%s' AND passwd= '%s' ", addslashes($sUserName), addslashes($sUserPwd));
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
                    AA_Log::Log(__METHOD__." - L'utente è disattivato (id: " . $rs["id"] . ").", 100);
                }

                if ($rs['eliminato'] == '1') {
                    AA_Log::Log(__METHOD__." - L'utente è stato disattivato permanentemente (id: " . $rs["id"] . ").", 100);
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

                    //AA_Log::LogAction($rs['id'], 0, "Log In"); //old stuff

                    //New stuff
                    AA_Log::Log(__METHOD__." - Autenticazione avvenuta con successo (credenziali corrette).", 50);
                    $concurrent=false;
                    if(isset($rs['concurrent']) && $rs['concurrent'] > 0) $concurrent=true;
                    $_SESSION['token'] = AA_User::GenerateToken($rs['id'],$remember_me,$concurrent);

                    if($remember_me)
                    {
                        //token di autenticazione valido per 30 giorni, utilizzabile solo in https.
                        setcookie("AA_AUTH_TOKEN",$_SESSION['token'],time()+(86400 * 30), "/",AA_Const::AA_DOMAIN_NAME,true, true);
                    }

                    $user = AA_User::LoadUser($rs['id']);
                    $user->bCurrentUser = true;

                    //update last login time
                    $db->Query("UPDATE utenti set lastlogin = NOW() WHERE id='".$rs['id']."' LIMIT 1");

                    return $user;
                }

                return AA_User::Guest();
            }

            AA_Log::Log(__METHOD__." - Autenticazione fallita (credenziali errate).", 100);
            return AA_User::Guest();
        }

        if ($sToken == null || $sToken == "") 
        {
            if(isset($_SESSION['token'])) $sToken = $_SESSION['token'];
            if($sToken == "" && isset($_COOKIE["AA_AUTH_TOKEN"]))
            {   
                $sToken=$_COOKIE["AA_AUTH_TOKEN"];
                AA_Log::Log(__METHOD__." - auth token login.",100);
            }
        }

        if ($sToken != null) {
            //AA_Log::Log(__METHOD__." - autenticazione in base al token.");

            $token_timeout_m = 30;
            $query_token = sprintf("SELECT * FROM tokens where (TIMESTAMPDIFF(MINUTE,data_rilascio, NOW()) < '%s' OR remember_me='1') and ip_src = '%s' and token ='%s'", $token_timeout_m, $_SERVER['REMOTE_ADDR'], $sToken);

            if ($db->Query($query_token)) {
                $result = $db->GetResult();
                $rs = $result->fetch(PDO::FETCH_ASSOC);
            } else {
                AA_Log::Log(__METHOD__ . " - errore nell'accesso al db: " . $db->GetErrorMessage(), 100);
                return AA_User::Guest();
            }

            if ($db->GetAffectedRows() > 0) {

                if (strcmp($rs['token'], $sToken) == 0) {
                    //AA_Log::Log(__METHOD__." - Authenticate token ($sToken) - success", 50);

                    $user = AA_User::LoadUser($rs['id_utente']);
                    if ($user->IsDisabled()) {
                        AA_Log::Log(get_class() . "->LegacyUserAuth($sToken) - L'utente è disattivato.", 100);
                        return AA_User::Guest();
                    }

                     //Old stuff compatibility
                     $_SESSION['user'] = $user->GetUsername();
                     $_SESSION['nome'] = $user->GetNome();
                     $_SESSION['cognome'] = $user->GetCognome();
                     $_SESSION['email'] = $user->GetEmail();
                     $_SESSION['user_home'] = "admin/index.php";
                     $_SESSION['id_user'] = $user->GetId();
                     $_SESSION['id_utente'] = $user->GetId();
                     $struct=$user->GetStruct();
                     $_SESSION['id_assessorato'] = $struct->GetAssessorato(true);
                     $_SESSION['tipo_struct'] = $struct->GetTipo();
                     $_SESSION['id_direzione'] = $struct->GetDirezione(true);
                     $_SESSION['id_servizio'] = $struct->GetServizio(true);
                     $_SESSION['id_settore'] = 0;
                     $_SESSION['livello'] = $user->GetLevel();
                     $_SESSION['level'] = $user->GetLevel();
                     $_SESSION['assessorato'] = $struct->GetAssessorato();
                     $_SESSION['direzione'] = $struct->GetDirezione();
                     $_SESSION['servizio'] = $struct->GetServizio();
                     $_SESSION['settore'] = "";
                     $_SESSION['user_flags'] = $user->GetFlags();
                     $_SESSION['flags'] = $user->GetFlags();
                    //AA_Log::LogAction($rs['id'], 0, "Log In"); //old stuff

                    //Rinfresco della durata del token
                    AA_User::RefreshToken($sToken);
                    $_SESSION['token'] = $sToken;

                    $user->bCurrentUser = true;

                    //update last login time
                    $db->Query("UPDATE utenti set lastlogin = NOW() WHERE id='".$rs['id_utente']."' LIMIT 1");

                    return $user;
                }
            }

            //Old stuff
            if (isset($log)) AA_Log::LogAction($rs->Get('id'), 0, "Authenticate token ($sToken) - failed");
            //----------

            AA_Log::Log(get_class() . "->LegacyUserAuth($sToken) - Authenticate token ($sToken) - failed", 100);
            return AA_User::Guest();
        }

        AA_Log::Log(get_class() . "->LegacyUserAuth($sToken,$sUserName) - Autenticazione fallita.", 100);
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

        foreach (self::LoadUsersFromEmail($user->GetEmail(),false) as $curProfile) {
            if ($curProfile->GetID() == $newProfileID) {
                $sToken = $_SESSION['token'];

                //Aggiorna il token con il nuovo id utente
                $db = new AA_AccountsDatabase();
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
    static public function MailOTPAuthChallenge($email = null,$remember_me=false)
    {
        //AA_Log::Log(__METHOD__." - Authenticate mail OTP");

        unset($_SESSION['MailOTP-code']);
        unset($_SESSION['MailOTP-email']);
        unset($_SESSION['MailOTP-remember']);

        $email = str_replace("'", "", trim($email));

        if ($email == "") {
            AA_Log::Log(__METHOD__." - mail non impostata.", 100, true, true);
            return false;
        }

        $users=static::LoadUsersFromEmail($email);
        if(sizeof($users) == 0)
        {
            AA_Log::Log(__METHOD__ . " - Nessuna utenza trovata.", 100);
            return false;
        }

        //genera ed invia il codice di controllo alla email indicata
        $code = substr(md5(uniqid(mt_rand(), true)), 0, 6);
        $_SESSION['MailOTP-code'] = $code;
        $_SESSION['MailOTP-email'] = $email;
        if($remember_me) $_SESSION['MailOTP-remember'] = true;
        else  $_SESSION['MailOTP-remember'] = false;

        $subject =AA_User::$aOTPAuthEmailParams['oggetto'];
        $body = str_replace("#www#",AA_Const::AA_DOMAIN_NAME.AA_Const::AA_WWW_ROOT,AA_User::$aOTPAuthEmailParams['incipit']);
        $body .= "<p>codice OTP: <span style='font-weight: bold; font-size: 150%;'>" . $code . "</span></p>";
        $body .= str_replace("#www#",AA_Const::AA_DOMAIN_NAME.AA_Const::AA_WWW_ROOT,AA_User::$aOTPAuthEmailParams['post'].AA_User::$aOTPAuthEmailParams['firma']);

        if(AA_Const::AA_ENABLE_SENDMAIL)
        {
            $result = SendMail(array(0 => $email), "", $subject, $body);

            if (!$result) {
                AA_Log::Log(__METHOD__." - invio mail fallito - errore: " . $result, 100, true, true);
                return false;
            }
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - OTP: " . $code, 100);
            return true;
        }
       
        return true;
    }
    //-------------------------------

    //Autenticazione via mail OTP - passo 2
    static public function MailOTPAuthChallengeVerify($code="")
    {
        //AA_Log::Log(__METHOD__. " - Verifica mail OTP");
        
        if(!isset($_SESSION['MailOTP-code']) || !isset($_SESSION['MailOTP-email']))
        {
            AA_Log::Log(__METHOD__. " - Errore nella verifica del codice OTP (0)", 100);
            return false;
        }

        if($code =="" || $code != $_SESSION['MailOTP-code'])
        {
            AA_Log::Log(__METHOD__. " - Errore nella verifica del codice OTP (1)", 100);
            return false;
        }

        unset($_SESSION['MailOTP-code']);
        
        $remember_me=$_SESSION['MailOTP-remember'];
        $users=AA_User::LoadUsersFromEmail($_SESSION['MailOTP-email']);
        
        unset($_SESSION['MailOTP-email']);
        unset($_SESSION['MailOTP-remember']);

        if(sizeof($users)>0)
        {
            $user=array_shift($users);
            $_SESSION['token'] = AA_User::GenerateToken($user->GetId(),$remember_me);

            if($remember_me)
            {
                //token di autenticazione valido per 30 giorni, utilizzabile solo in https.
                setcookie("AA_AUTH_TOKEN",$_SESSION['token'],time()+(86400 * 30), "/",AA_Const::AA_DOMAIN_NAME,true, true);
            }
        }
        else
        {
            return false;
        }
                
        return true;
    }
    //-------------------------------

    //Verifica utente via mail OTP - passo 1
    public function MailOTPChangePwdChallenge()
    {
        //AA_Log::Log(__METHOD__." - Authenticate mail OTP");
        if(!$this->IsValid() || !$this->IsCurrentUser())
        {
            AA_Log::Log(__METHOD__." - Utente non valido.");
            return false;
        }
        
        unset($_SESSION['MailOTP-changepwd-code']);

        $email = str_replace("'", "", trim($this->GetEmail()));

        if ($email == "") {
            AA_Log::Log(__METHOD__." - mail non impostata.", 100, true, true);
            return false;
        }

        //genera ed invia il codice di controllo alla email indicata
        $code = substr(md5(uniqid(mt_rand(), true)), 0, 6);
        $_SESSION['MailOTP-changepwd-code'] = $code;

        $subject =AA_User::$aOTPChangePwdEmailParams['oggetto'];
        $body = str_replace("#www#",AA_Const::AA_DOMAIN_NAME.AA_Const::AA_WWW_ROOT,AA_User::$aOTPChangePwdEmailParams['incipit']);
        $body .= "<p>codice OTP: <span style='font-weight: bold; font-size: 150%;'>" . $code . "</span></p>";
        $body .= str_replace("#www#",AA_Const::AA_DOMAIN_NAME.AA_Const::AA_WWW_ROOT,AA_User::$aOTPChangePwdEmailParams['post'].AA_User::$aOTPChangePwdEmailParams['firma']);

        if(AA_Const::AA_ENABLE_SENDMAIL)
        {
            $result = SendMail(array(0 => $email), "", $subject, $body);

            if (!$result) {
                AA_Log::Log(__METHOD__." - invio mail fallito - errore: " . $result, 100, true, true);
                return false;
            }
        }
        else
        {
            AA_Log::Log(__METHOD__ . " - OTP: " . $code, 100);
            return true;
        }
       
        return true;
    }
    //-------------------------------

    //verifica utente per cambio pwd via mail OTP - passo 2
    public function MailOTPChangePwdChallengeVerify($code="")
    {
        //AA_Log::Log(__METHOD__. " - Verifica mail OTP");

        if(!$this->IsValid() || !$this->IsCurrentUser())
        {
            AA_Log::Log(__METHOD__. " - Utente non valido.", 100);
            return false;
        }
        
        if(!isset($_SESSION['MailOTP-changepwd-code']))
        {
            AA_Log::Log(__METHOD__. " - Errore nella verifica del codice OTP (0)", 100);
            return false;
        }

        if($code =="" || $code != $_SESSION['MailOTP-changepwd-code'])
        {
            AA_Log::Log(__METHOD__. " - Errore nella verifica del codice OTP (1)", 100);
            return false;
        }
                
        return true;
    }
    //-------------------------------

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
            $db = new AA_AccountsDatabase();
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
        $db = new AA_AccountsDatabase();
        $query = "SELECT * from email_login WHERE email='" . $email . "' AND codice='" . str_replace("'", "", trim($codice)) . "' LIMIT 1";

        if (!$db->Query($query)) {
            AA_Log::Log(get_class() . "->MailOTPAuthVerify($email) - errore: " . $db->GetErrorMessage() . " - nella query: " . $query, 100, true, true);
            return false;
        }

        $rs = $db->GetResultSet();
        if (sizeof($rs)> 0) {
            $_SESSION['MailOTP-user'] = $rs[0]["id"];
            $_SESSION['MailOTP-nome'] = $rs[0]["nome"];
            $_SESSION['MailOTP-cognome'] = $rs[0]["cognome"];
            $aggiornamento = $rs[0]["aggiornamento"];
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

        $db = new AA_AccountsDatabase();
        $query = "SELECT email from email_login where email='" . str_replace("'", "", trim($email)) . "' LIMIT 1";
        if (!$db->Query($query)) {
            AA_Log::Log(get_class() . "->MailOTPAuthIsMailRegistered($email) - errore: " . $db->lastError . " - nella query: " . $query, 100, true, true);
            return false;
        }

        $rs = $db->GetResultSet();
        if (sizeof($rs) > 0) return true;
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

        $db = new AA_AccountsDatabase();
        $query = "INSERT INTO email_login set email='" . str_replace("'", "", trim($email)) . "', aggiornamento=NOW()";
        if (!$db->Query($query)) {
            AA_Log::Log(get_class() . "->MailOTPAuthRegisterEmail($email) - errore: " . $db->GetErrorMessage() . " - nella query: " . $query, 100, true, true);
            return false;
        }

        return true;
    }
    //-----------------------------------------------------

    //Rimuovi le informazioni di autenticazione
    public function LogOut()
    {
        //AA_Log::Log(get_class() . "->LogOut() - " . $this->sUser . "(" . $this->nID . ")");

        if ($this->bIsValid && $this->bCurrentUser) {
            $db = new AA_AccountsDatabase();
            $query = "DELETE from tokens WHERE token='" . $_SESSION['token'] . "'";
            $db->Query($query);

            $_SESSION['token'] = null;
            setcookie("AA_AUTH_TOKEN","");

            unset($_SESSION);
            session_destroy();
        }
    }

    //Genera il token di autenticazione
    static private function GenerateToken($id_user, $remember_me=false, $concurrent_access=false)
    {
        //AA_Log::Log(__METHOD__."(".print_r($id_user,true).",".print_r($remember_me,true).",".print_r($concurrent_access,true).")",100);

        $token = hash("sha256", $id_user . date("Y-m-d H:i:s") . uniqid() . $_SERVER['REMOTE_ADDR']);

        //AA_Log::Log(get_class() . "->GenerateToken($id_user) - new token: " . $token);

        $db = new AA_AccountsDatabase();

        if(!$concurrent_access && !$remember_me)
        {
            AA_Log::Log(__METHOD__." - accesso concorrente disattivato, elimino token precedenti.",100);
            $query = "DELETE from tokens where id_utente='" . $id_user . "'";
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
            }   
        }

        //cancella i token vecchi
        $query = "DELETE from tokens where id_utente='" . $id_user . "' and TIMESTAMPDIFF(MINUTE,data_rilascio, NOW()) > '60' and remember_me='0'";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
        }
        //-----------------------

        $query = "INSERT INTO tokens set token='" . $token . "', id_utente='" . $id_user . "',ip_src='" . $_SERVER['REMOTE_ADDR'] . "'";

        if($remember_me === true || $remember_me > 0)
        {
            $query.=", remember_me='1'";
        }

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
        } 

        //AA_Log::Log(__METHOD__." - nuovo token: ".$token,100);

        return $token;
    }

    //Rinfresca il token di autenticazione
    static private function RefreshToken($token)
    {
        //AA_Log::Log(get_class() . "->RefreshToken($token)");

        $db = new AA_AccountsDatabase();

        $query = "UPDATE tokens SET data_rilascio=NOW() where token ='" . addslashes($token) . "'";

        $db->Query($query);
    }

    //Restituisce l'utente attualmente loggato (guest se non c'è nessun utente loggato)
    static protected $oCurrentUser=null;
    static public function GetCurrentUser()
    {
        if(static::$oCurrentUser instanceof AA_User) return static::$oCurrentUser;

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
        $result .= '<image>' . $this->GetProfileImagePublicPath() . '</image>';
        //$result.=$this->oStruct->toXML();
        $result .= '</utente>';

        return $result;
    }

    //Rappresentazione stringa
    public function __toString()
    {
        //AA_Log::Log(get_class() . "->__toString()");

        return $this->toXML();
    }

    //Verifica la presenza di qualche flag
    public function HasFlag($flag)
    {
        //AA_Log::Log(get_class()."->HasFlag($flag)");

        if($flag == "") return false;

        if(array_search("1",$this->GetGroups()) !==false || $this->nID==1) return true;

        $flags = explode("|", $this->sFlags);
        if (in_array($flag, $flags,true) != false)
        {
            //AA_Log::Log(get_class()."->HasFlag($flag) - l'utente: ".$this->sUser."(".$this->nID.") ha il flag - flags:".print_r($flags,true),100);
            return true;
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $legacy_flags=explode("|", $this->sLegacyFlags);
            if (in_array($flag, $legacy_flags,true) != false) {
                //AA_Log::Log(get_class()."->HasFlag($flag) - l'utente: ".$this->sUser."(".$this->nID.") ha il flag",100,FALSE,TRUE);
                return true;
            }
        }

        //AA_Log::Log(get_class()."->HasFlag($flag) - l'utente: ".$this->sUser."(".$this->nID.") non ha il flag - ".print_r($flags,true),100, false,true);
        return false;
    }

    //Restituisce il nome
    public function GetNome()
    {
        return $this->sNome;
    }

    //Restituisce il nome
    public function GetName()
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

     //Restituisce il codice fiscale
     public function GetCf()
     {
         return $this->sCf;
     }

    //Restituisce l'email
    public function GetEmail()
    {
        return $this->sEmail;
    }

    //Restituisce il nome
    public function GetFlags($bArray = false, $bLegacyFlags=true)
    {
        $flags=$this->sFlags;
        if(AA_Const::AA_ENABLE_LEGACY_DATA && $this->sLegacyFlags !="" && $bLegacyFlags)
        {
            if($flags!="") $flags.="|".$this->sLegacyFlags;
            else $flags=$this->sLegacyFlags;
        }
        if ($bArray)
        {
            if($flags=="") return array();

            return array_unique(explode("|", $flags));
        }

        if($flags=="") return "";

        return implode("|",array_unique(explode("|", $flags)));
    }

    //Restituisce i flags legacy
    public function GetLegacyFlags($bArray = false)
    {
        $flags=$this->sLegacyFlags;
        if ($bArray)
        {
            if($flags=="") return array();

            return explode("|", $flags);
        } 

        return $flags;
    }

    //Verifica se il nome utente esiste già
    static public function UserNameExist($userName = "")
    {
        //AA_Log::Log(get_class() . "->UserNameExist($userName)");
        if ($userName == "") return false;

        $db = new AA_AccountsDatabase();

        $sql = "SELECT user FROM ".static::AA_DB_TABLE." where user='" . $userName . "' ";
        if (!$db->Query($sql)) {
            AA_Log::Log(__METHOD__." - Errore nella query: " . $db->GetErrorMessage(), 100);
            return false;
        }
        if ($db->GetAffectedRows() > 0) return true;

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $sql = "SELECT user FROM utenti where user='" . $userName . "' AND eliminato=0";
            if (!$db->Query($sql)) {
                AA_Log::Log(get_class() . "->UserNameExist($userName) - Errore nella query: " . $db->GetErrorMessage(), 100);
                return false;
            } 

            if ($db->GetAffectedRows() > 0) return true;   
        }

        return false;
    }

    //Ricerca utenti
    static public function Search($params=array(),$user=null)
    {
        if(!($user instanceof AA_User))
        {
            $user=AA_User::GetCurrentUser();
        }

        if(!$user->CanGestUtenti())
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non è abilitato alla gestione utenti.",100);
            return array();
        }

        $query="SELECT id from ".static::AA_DB_TABLE." WHERE id <> '".$user->GetId()."'";
        if($user->GetId() !=1 ) $query.=" AND id <> 1 ";
        
        switch($user->GetRuolo(true))
        {
            case AA_User::AA_USER_GROUP_SUPERUSER:
                break;
            case AA_User::AA_USER_GROUP_SERVEROPERATORS:
                $query.=" AND (FIND_IN_SET('1',".static::AA_DB_TABLE.".groups) = 0 OR ".static::AA_DB_TABLE.".groups like '')";
                break;
            case AA_User::AA_USER_GROUP_ADMINS:
                $query.=" AND (FIND_IN_SET(".static::AA_DB_TABLE.".groups,'3,4') > 0 OR ".static::AA_DB_TABLE.".groups like '')";
                break;
            default:
            $query.=" AND id=".$user->GetId();
        }

        //username
        if(isset($params['user']) && $params['user']!="")
        {
            $query.=" AND ".static::AA_DB_TABLE.".user like '%".addslashes($params['user'])."%'";
        }

        //email
        if(isset($params['email']) && $params['email']!="")
        {
            $query.=" AND email like '%".addslashes($params['email'])."%'";
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            //if(!$user->IsSuperUser()) $query.=" AND status >=-1 ";

            $struct=$user->GetStruct();
            if($struct->GetAssessorato(true)>0)
            {
                $query.=" AND (legacy_data like '%\"id_assessorato\":\"".$struct->GetAssessorato(true)."\"%' OR legacy_data like '%\"id_assessorato\":".$struct->GetAssessorato(true).",%')";
            }
            else
            {
                if($params['id_assessorato']>0)
                {
                    $query.=" AND (legacy_data like '%\"id_assessorato\":\"".$params['id_assessorato']."\"%' OR legacy_data like '%\"id_assessorato\":".$params['id_assessorato'].",%')";
                }
            }

            if($struct->GetDirezione(true)>0)
            {
                $query.=" AND (legacy_data like '%\"id_direzione\":\"".$struct->GetDirezione(true)."\"%' OR legacy_data like '%\"id_direzione\":".$struct->GetDirezione(true).",%')";
            }
            else
            {
                if($params['id_direzione']>0)
                {
                    $query.=" AND (legacy_data like '%\"id_direzione\":\"".$params['id_direzione']."\"%' OR legacy_data like '%\"id_direzione\":".$params['id_direzione'].",%')";
                }
            }

            if($struct->GetServizio(true)>0)
            {
                $query.=" AND (legacy_data like '%\"id_servizio\":\"".$struct->GetServizio(true)."\"%' OR legacy_data like '%\"id_servizio\":".$struct->GetServizio(true).",%')";
            }
            else
            {
                if($params['id_servizio']>0)
                {
                    $query.=" AND (legacy_data like '%\"id_servizio\":\"".$params['id_servizio']."\"%' OR legacy_data like '%\"id_servizio\":".$params['id_servizio'].",%')";
                }
            }
        }

        //stato
        if(isset($params['status']) && $params['status']>-2)
        {
            $query.=" AND status='".addslashes($params['status'])."'";
        }

        if(isset($params['ruolo']) && $params['ruolo'] > 0)
        {
            $query.=" AND FIND_IN_SET('".addslashes($params['ruolo'])."',".static::AA_DB_TABLE.".groups) > 0 ";
        }

        $db=new AA_AccountsDatabase();

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore: ".$db->GetErrorMessage(),100);
            return array();
        }

        //Limita la ricerca ai primi 500
        $query.=" LIMIT 500";

        //AA_Log::Log(__METHOD__." - query: ".$query,100);

        $rs=$db->GetResultSet();
        if(sizeof($rs)>0)
        {
            $result=array();
            foreach($rs as $curRow)
            {
                $user=AA_User::LoadUser($curRow['id']);
                if($user->IsValid()) $result[]=$user;
            }

            return $result;
        }

        return array();
    }

    //Ricerca utenti (solo utenti di livello "user")
    static public function SearchUsers($params=array(),$user=null)
    {
        if(!($user instanceof AA_User))
        {
            $user=AA_User::GetCurrentUser();
        }

        if($user->IsGuest())
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non è abilitato alla funzione richiesta.",100);
            return array();
        }

        $query="SELECT id from ".static::AA_DB_TABLE." WHERE ".static::AA_DB_TABLE.".groups = ".AA_User::AA_USER_GROUP_USERS." AND status=".AA_User::AA_USER_STATUS_ENABLED;

        //username
        if(isset($params['user']) && $params['user']!="")
        {
            $query.=" AND ".static::AA_DB_TABLE.".user like '%".addslashes($params['user'])."%'";
        }

        //email
        if(isset($params['email']) && $params['email']!="")
        {
            $query.=" AND email like '%".addslashes($params['email'])."%'";
        }

        //flags
        if(isset($params['flags']) && sizeof($params['flags'])>0)
        {
            $query.=" AND (";
            $curQuery="";
            foreach($params['flags'] as $curFlag)
            {
                if($curQuery=="") $curQuery.=" flags like '".addslashes($curFlag)."' OR flags like '".addslashes($curFlag)."|%' OR flags like '%|".addslashes($curFlag)."' OR flags like '%|".addslashes($curFlag)."|%'";
                else $curQuery.=" OR flags like '".addslashes($curFlag)."' OR flags like '".addslashes($curFlag)."|%' OR flags like '%|".addslashes($curFlag)."' OR flags like '%|".addslashes($curFlag)."|%'";
            }
            $query.=$curQuery.")";
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if(!$user->IsSuperUser()) $query.=" AND status >=0 ";

            $struct=$user->GetStruct();
            if($struct->GetAssessorato(true)>0)
            {
                $query.=" AND legacy_data like '%\"id_assessorato\":\"".$struct->GetAssessorato(true)."\"%'";
            }
            else
            {
                if($params['id_assessorato']>0)
                {
                    $query.=" AND legacy_data like '%\"id_assessorato\":\"".$params['id_assessorato']."\"%'";
                }
            }

            if($struct->GetDirezione(true)>0)
            {
                $query.=" AND legacy_data like '%\"id_direzione\":\"".$struct->GetDirezione(true)."\"%'";
            }
            else
            {
                if($params['id_direzione']>0)
                {
                    $query.=" AND legacy_data like '%\"id_direzione\":\"".$params['id_direzione']."\"%'";
                }
            }

            if($struct->GetServizio(true)>0)
            {
                $query.=" AND legacy_data like '%\"id_servizio\":\"".$struct->GetServizio(true)."\"%'";
            }
            else
            {
                if($params['id_servizio']>0)
                {
                    $query.=" AND legacy_data like '%\"id_servizio\":\"".$params['id_servizio']."\"%'";
                }
            }
        }

        $db=new AA_AccountsDatabase();

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore: ".$db->GetErrorMessage(),100);
            return array();
        }

        //Limita la ricerca ai primi 500
        $query.=" LIMIT 500";

        //AA_Log::Log(__METHOD__." - query: ".$query,100);

        $rs=$db->GetResultSet();
        if(sizeof($rs)>0)
        {
            $result=array();
            foreach($rs as $curRow)
            {
                $user=AA_User::LoadUser($curRow['id']);
                if($user->IsValid()) $result[]=$user;
            }

            return $result;
        }

        return array();
    }

    //Ricerca utenti
    static public function LegacySearch($params=array(),$user=null,$bOnlyLegacy=true)
    {
        if(!($user instanceof AA_User))
        {
            $user=AA_User::GetCurrentUser();
        }

        if(!$user->CanGestUtenti())
        {
            AA_Log::Log(__METHOD__." - l'utente corrente non è abilitato alla gestione utenti.",100);
            return array();
        }

        $query="SELECT utenti.id as legacyId, ".static::AA_DB_TABLE.".id as nuovoId from utenti ";
        
        $query.=" LEFt JOIN ".static::AA_DB_TABLE." on utenti.id=".static::AA_DB_TABLE.".id WHERE utenti.id <> '".$user->GetId()."' AND utenti.eliminato=0";

        //username
        if(isset($params['user']) && $params['user']!="")
        {
            $query.=" AND utenti.user like '%".addslashes($params['user'])."%'";
        }

        //email
        if(isset($params['email']) && $params['email']!="")
        {
            $query.=" AND utenti.email like '%".addslashes($params['email'])."%'";
        }

        $query.=" AND eliminato = 0 ";

        $struct=$user->GetStruct();
        if($struct->GetAssessorato(true)>0)
        {
            $query.=" AND utenti.id_assessorato='".$struct->GetAssessorato(true)."'";
        }
        else
        {
            if($params['id_assessorato']>0)
            {
                $query.=" AND utenti.id_assessorato='".$params['id_assessorato']."'";
            }
        }

        if($struct->GetDirezione(true)>0)
        {
            $query.=" AND utenti.id_direzione='".$struct->GetDirezione(true)."'";
        }
        else
        {
            if($params['id_direzione']>0)
            {
                $query.=" AND utenti.id_direzione='".$params['id_direzione']."'";
            }
        }

        if($struct->GetServizio(true)>0)
        {
            $query.=" AND utenti.id_servizio='".$struct->GetServizio(true)."'";
        }
        else
        {
            if($params['id_servizio']>0)
            {
                $query.=" AND utenti.id_servizio='".$params['id_servizio']."'";
            }
        }

        //ruolo
        if(isset($params['ruolo']))
        {
            if($params['ruolo']==0) $query.=" AND utenti.livello='0'";
            if($params['ruolo']==1) $query.=" AND utenti.livello='1'";
        }

        //stato
        if(isset($params['status']))
        {
            if($params['status']==0) $query.=" AND utenti.disable='1'";
            if($params['status']==1) $query.=" AND utenti.disable='0'";
        }

        $db=new AA_AccountsDatabase();

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - errore: ".$db->GetErrorMessage()." - ".$query,100);
            return array();
        }

        //Limita la ricerca ai primi 500
        //$query.=" LIMIT 500";

        //AA_Log::Log(__METHOD__." - query: ".$query,100);

        $rs=$db->GetResultSet();
        if(sizeof($rs)>0)
        {
            $result=array();
            foreach($rs as $curRow)
            {
                if($bOnlyLegacy)
                {
                    if($curRow['nuovoId'] == "")
                    {
                        $user=AA_User::LegacyLoadUser($curRow['legacyId']);
                        if($user->IsValid()) $result[]=$user;        
                    }
                }
                else
                {
                    $user=AA_User::LegacyLoadUser($curRow['legacyId']);
                    if($user->IsValid()) $result[]=$user;
                }
            }

            return $result;
        }

        return array();
    }

    //Verifica se l'utente corrente può gestire gli utenti
    public function CanGestUtenti()
    {
        //AA_Log::Log(get_class()."->CanGestUtenti()");

        if (!$this->bIsValid) return false;

        if(!$this->isCurrentUser()) return false;

        if ($this->IsSuperUser()) return true;

        //AA_Log::Log(__METHOD__." - Verifica gestione utenti - ruolo: ".print_r($this->GetRuolo(true),true),100);
        if($this->GetRuolo(true) == AA_User::AA_USER_GROUP_SERVEROPERATORS) return true;

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            //AA_Log::Log(__METHOD__." - Verifica gestione utenti - legacy",100);
            if ($this->nLivello != AA_Const::AA_USER_LEVEL_ADMIN) return false;

            //if (!$this->HasFlag("U0")) return true;
        }

        return false;
    }

    //Verifica se l'utente corrente può gestire le strutture
    public function CanGestStruct()
    {
        //AA_Log::Log(get_class() . "->CanGestStruct()");

        if (!$this->bIsValid) return false;

        if(!$this->isCurrentUser()) return false;

        if ($this->IsSuperUser()) return true;

        if(array_search(AA_User::AA_USER_GROUP_SERVEROPERATORS,$this->GetAllGroups()) !== false) return true;

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {

            return false;
            //if ($this->nLivello != AA_Const::AA_USER_LEVEL_ADMIN) return false;

            //old
            //if (!$this->HasFlag("S0")) return true;  
        }

        return false;
    }

    //Verifica se l'utente corrente può modificare il livello dell'utente indicato (legacy)
    public function CanPromoteUserAsAdmin($idUser = null)
    {
        //AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser)");

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

        if ($this->oStruct->GetServizio(true) == $user->GetStruct()->GetServizio(true) && $this->oStruct->GetServizio(true) != 0) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - utente corrente non può modificare il livello dell'utente: " . $this->GetUsername() . " (stesso servizio)", 100);
            return false;
        }
        if ($this->oStruct->GetDirezione(true) == $user->GetStruct()->GetDirezione(true) && $user->GetStruct()->GetServizio(true) == 0 && $this->oStruct->GetDirezione(true) != 0) {
            AA_Log::Log(get_class() . "->CanModifyUserLevel($idUser) - utente corrente non può modificare il livello dell'utente: " . $this->GetUsername() . " (stessa direzione)", 100);
            return false;
        }
        if ($this->oStruct->GetAssessorato(true) == $user->GetStruct()->GetAssessorato(true) && $user->GetStruct()->GetDirezione(true) == 0 && $this->oStruct->GetAssessorato(true) != 0) {
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
        if (!$this->IsValid()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido: " . $this->GetUsername(), 100);
            return false;
        }

        //Il super utente può modificare tutto
        if ($this->IsSuperUser()) return true;

        //AA_Log::Log(__METHOD__." - utente: " . $idUser. " - ".$this, 100);

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(__METHOD__." - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //L'utente può modificare se stesso
        if ($this->nID == $user->GetID()) return true;

        //Controlla se l'utente corrente è abilitato alla gestione utenti
        if (!$this->CanGestUtenti()) {
            AA_Log::Log(__METHOD__." - utente corrente non autorizzato alla gestione utenti: " . $this->GetUsername(), 100);
            return false;
        }

        //L'utente root può essere modificato solament da se stesso
        if($this->nID != 1 && $user->GetID()==1)
        {
            AA_Log::Log(__METHOD__." - utente corrente non autorizzato alla modifica dell'utente: " . $user->GetUsername(), 100);
            return false;
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if ($this->GetStruct()->GetAssessorato(true) != 0 && $this->GetStruct()->GetAssessorato(true) != $user->GetStruct()->GetAssessorato(true)) {
                AA_Log::Log(__METHOD__." - L'utente corrente non può modificare utenti di altre strutture.", 100);
                return false;
            }
    
            if ($this->GetStruct()->GetDirezione(true) != 0 && $this->GetStruct()->GetDirezione(true) != $user->GetStruct()->GetDirezione(true)) {
                AA_Log::Log(__METHOD__." - L'utente corrente non può modificare utenti di altre strutture.", 100);
                return false;
            }
    
            if ($this->GetStruct()->GetServizio(true) != 0 && $this->GetStruct()->GetServizio(true) != $user->GetStruct()->GetServizio(true)) {
                AA_Log::Log(__METHOD__." - L'utente corrente non può modificare utenti di altre strutture.", 100);
                return false;
            }

            if($this->GetRuolo(true)== AA_User::AA_USER_GROUP_SERVEROPERATORS) return true;
    
            //Non può modificare utenti amministratori dello stesso livello gerarchico
            if ($this->GetStruct()->GetServizio(true) == $user->GetStruct()->GetServizio(true) && $user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN && $this->GetStruct()->GetServizio(true) != 0) {
                AA_Log::Log(__METHOD__." - L'utente corrente (" . $this . ") non può modificare utenti amministratori dello stesso livello gerarchico (stesso servizio).", 100);
                return false;
            }
    
            if ($this->GetStruct()->GetDirezione(true) == $user->GetStruct()->GetDirezione(true) && $user->GetStruct()->GetServizio(true) == 0 && $user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN && $this->GetStruct()->GetDirezione(true) != 0) {
                AA_Log::Log(__METHOD__." - L'utente corrente (" . $this . ") non può modificare utenti amministratori dello stesso livello gerarchico (stessa direzione).", 100);
                return false;
            }
    
            if ($this->GetStruct()->GetAssessorato(true) == $user->GetStruct()->GetAssessorato(true) && $user->GetStruct()->GetDirezione(true) == 0 && $user->GetLevel() == AA_Const::AA_USER_LEVEL_ADMIN && $this->GetStruct()->GetAssessorato(true) != 0) {
                AA_Log::Log(__METHOD__." - L'utente corrente (" . $this . ") non può modificare utenti amministratori dello stesso livello gerarchico (stesso assessorato).", 100);
                return false;
            }    
        }

        return true;
    }

    //Aggiungi un nuovo utente
    public function AddNewUser($params)
    {
        if ($this->IsGuest()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido", 100);
            return false;
        }

        //Recupera l'utente corrente
        if (!$this->isCurrentUser() || !$this->CanGestUtenti()) {
            AA_Log::Log(__METHOD__." - utente non autenticato o non autorizzato alla gestione utenti", 100);
            return false;
        }

        //verifica che sia presente l'email
        if ($params['email'] == "")
        {
            AA_Log::Log(__METHOD__." - occore indicare una email di riferimento.", 100);
            return false;
        }

        //Verifica se il nome utente sia valido
        if (!isset($params['user']) || $params['user'] == "") {

            $email=trim(strtolower($params['email']));
            $account=explode("@",$email);
            $suff="";
            $num=0;
            $univoco=AA_user::UserNameExist($account[0].$suff);
            while($univoco)
            {
                $num++;
                $suff="_".$num;
                
                $univoco=AA_user::UserNameExist($account[0].$suff);
            }

            $params['user']=$account[0].$suff;
        }
        else
        {
            //Verifica se l'utente esiste già
            if (AA_user::UserNameExist($params['user'])) {
                AA_Log::Log(__METHOD__." - nome utente già esistente.", 100);
                return false;
            }
        }

        $allUserGroups=$this->GetAllGroups();

        //------------- Ruolo -----------------
        if(!isset($params['ruolo'])) 
        {
            $ruolo=static::AA_USER_GROUP_OPERATORS;
            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                if(isset($params['livello']) && $params['livello']==1) $ruolo=static::AA_USER_GROUP_OPERATORS;
                if(isset($params['livello']) && $params['livello']==0) $ruolo=static::AA_USER_GROUP_ADMINS;    
            }
        }
        else
        {
            $ruolo=$params['ruolo'];
        }

        if(array_search($ruolo,$allUserGroups)===false)
        {
            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                $params['livello']==2;
            }   
            $ruolo=static::AA_USER_GROUP_USERS;
        } 
        //-------------------------------------

        //--------------- Gruppi --------------
        if(!isset($params['groups'])) $params['groups']=array();
        
        $groups=array($ruolo);
        foreach($params['groups'] as $curGroup)
        {
            if($curGroup > 1000 && array_search($curGroup,$allUserGroups) !== false)
            {
                $groups[]=$curGroup;
            }
        }

        if(sizeof($groups)==0)
        {
            //default group (utenti)
            $groups=array(static::AA_USER_GROUP_USERS);
        }
        //-------------------------------------

        //--------------- Status --------------
        $status=static::AA_USER_STATUS_DISABLED;
        if(isset($params['status']) && $params['status']==static::AA_USER_STATUS_ENABLED) $status=static::AA_USER_STATUS_ENABLED;
        if(isset($params['status']) && $params['status']==static::AA_USER_STATUS_DELETED) $status=static::AA_USER_STATUS_DELETED;
        if(isset($params['status']) && $params['status']==static::AA_USER_STATUS_DISABLED) $status=static::AA_USER_STATUS_DISABLED;

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if(isset($params['status']))
            {
                if($status==static::AA_USER_STATUS_DISABLED) $params['disable']=1;
                if($status==static::AA_USER_STATUS_DELETED) $params['eliminato']=1;
            }
            else
            {   $status=static::AA_USER_STATUS_ENABLED;
                if(isset($params['disable']) && $params['disable'] > 0) $status=static::AA_USER_STATUS_DISABLED;
                if(isset($params['eliminato']) && $params['eliminato'] > 0) $status=static::AA_USER_STATUS_DELETED;
            }
        }
        //--------------------------------------

        $db = new AA_AccountsDatabase();

        //new stuff
        $info=json_encode(array(
            "nome"=>addslashes(trim($params['nome'])),
            "cognome"=>addslashes(trim($params['cognome'])),
            "phone"=>addslashes(trim($params['phone'])),
            "image"=>addslashes(trim($params['image'])),
            "cf"=>addslashes(trim($params['cf'])),
        ));

        $sql="INSERT INTO ".static::AA_DB_TABLE." SET ";
        $sql.="user='".addslashes(trim($params['user']))."'";
        $sql.=", email='".addslashes(trim($params['email']))."'";
        $sql.=", flags='".addslashes(trim($params['flags']))."'";
        $sql.=", info='".addslashes($info)."'";
        $sql.=", data_abilitazione='".date("Y-m-d")."'";
        $sql.=", status='".$status."'";
        if (isset($params['passwd']) && $params['passwd'] !="") $sql.=", passwd='".AA_Utils::password_hash($params['passwd'])."'";
        else $sql.=", passwd='".AA_Utils::password_hash(uniqid(date("Y-m-d")))."'";
        $sql.=", ".static::AA_DB_TABLE.".groups='".implode(",",$groups)."'";
        
        if (!$db->Query($sql)) 
        {
            AA_Log::Log(__METHOD__ . " - new stuff - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }

        $params['new_id']=$db->GetLastInsertId();

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            return $this->LegacyAddNewUser($params);
        }

        return true;
    }

    //Migra un utente legacy sul nuovo framework
    static public function MigrateLegacyUser($legacyUser,$legacyPwd,$oldMd5Pwd="")
    {
        $user=static::GetCurrentUser();
        if(!$user->IsValid())
        {
            AA_Log::Log(__METHOD__." - utente non valido.",100);
            return false;
        }

        if(!($legacyUser instanceof AA_User))
        {
            AA_Log::Log(__METHOD__." - utente legacy non valido.",100);
            return false;
        }

        if(!$user->CanModifyUser($legacyUser))
        {
            AA_Log::Log(__METHOD__." - l'utente  corrente non può modificare l'utente legacy.",100);
            return false;
        }

        $db = new AA_AccountsDatabase();
        if(!$db->Query("SELECT id from ".AA_User::AA_DB_TABLE." WHERE id='".$legacyUser->GetId()."'"))
        {
            AA_Log::Log(__METHOD__." - errore nel recupero dei dati. ".$db->GetErrorMessage(),100);
            return false;
        }

        $update=false;
        if($db->GetAffectedRows()>0)
        {
            AA_Log::Log(__METHOD__." - utente già presente, aggiorno i dati ".$db->GetErrorMessage(),100,true);
            
            $update=true;
        }

        //Stato utente
        $status=static::AA_USER_STATUS_ENABLED;
        if($legacyUser->nDisabled>0) $status=static::AA_USER_STATUS_DISABLED;

        //new stuff
        $info=json_encode(array(
            "nome"=>addslashes(trim($legacyUser->GetNome())),
            "cognome"=>addslashes(trim($legacyUser->GetCognome())),
            "phone"=>addslashes(trim($legacyUser->GetPhone())),
            "image"=>addslashes(trim($legacyUser->GetImage()))
        ));

        if(!$update) $sql="INSERT INTO ".static::AA_DB_TABLE." SET id='".$legacyUser->GetId()."' , user='".addslashes(trim($legacyUser->GetUsername()))."'";
        else $sql="UPDATE ".static::AA_DB_TABLE." SET user='".addslashes(trim($legacyUser->GetUsername()))."'";
        $sql.=", email='".addslashes(trim($legacyUser->GetEmail()))."'";
        $sql.=", info='".addslashes($info)."'";
        $sql.=", data_abilitazione='".date("Y-m-d")."'";
        $sql.=", status='".$status."'";
        
        if($legacyPwd && $legacyPwd !="") $sql.=", passwd='".AA_Utils::password_hash($legacyPwd)."'";
        else 
        {
            if ($oldMd5Pwd && $oldMd5Pwd !="") 
            {
                AA_Log::Log(__METHOD__." - legacyPwd: ".$oldMd5Pwd,100);
                $sql.=", passwd='".AA_Utils::password_hash($oldMd5Pwd)."'";
            }
            else
            {
                $sql.=", passwd='".AA_Utils::password_hash(uniqid())."'";
            }
        }

        {
            $groups=AA_USER::AA_USER_GROUP_USERS;
            if($legacyUser->nLivello==1) $groups=AA_User::AA_USER_GROUP_OPERATORS;
            if($legacyUser->nLivello==0) $groups=AA_User::AA_USER_GROUP_ADMINS;
            if($legacyUser->IsSuperUser()) $groups=AA_USER::AA_USER_GROUP_SUPERUSER;
            $sql.=", groups='".$groups."'";
        }

        //legacy flags import
        $legacyflags=$legacyUser->GetLegacyFlags(true);
        //AA_Log::Log(__METHOD__." - LegacyFlags: ".print_r($legacyflags,true),100);

        $modulesFlags=array_keys(AA_Platform::GetAllModulesFlags());
        //AA_Log::Log(__METHOD__." - modulesFlagsKeys: ".print_r($modulesFlags,true),100);

        $userFlags=$legacyUser->GetFlags(true,false);
        foreach($legacyflags as $curFlag)
        {
            if(array_search($curFlag,$modulesFlags) !==false) $userFlags[]=$curFlag;
        }
        if(sizeof($userFlags)>0) $userFlags=implode("|",array_unique($userFlags));
        else $userFlags="";
        $sql.=", flags='".addslashes($userFlags)."' ";

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $struct=$legacyUser->GetStruct();
            $legacy_data=json_encode(array(
                "id_assessorato"=>$struct->GetAssessorato(true),
                "id_direzione"=>$struct->GetDirezione(true),
                "id_servizio"=>$struct->GetServizio(true),
                "level"=>$legacyUser->nLivello,
                "flags"=>$legacyUser->sLegacyFlags
            ));
            $sql.=", legacy_data='".$legacy_data."'";
        }

        if($update) $sql.=" WHERE id='".$legacyUser->GetId()."' LIMIT 1"; 

        if (!$db->Query($sql)) {
            AA_Log::Log(__METHOD__ . " - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }

        return true;
    }

    //Aggiungi un nuovo utente legacy
    public function LegacyAddNewUser($params)
    {
        AA_Log::Log(get_class() . "->LegacyAddNewUser($params)");

        if ($this->IsGuest()) {
            AA_Log::Log(get_class() . "->LegacyAddNewUser($params) - utente corrente non valido", 100);
            return false;
        }

        //Recupera l'utente corrente
        if (!$this->isCurrentUser() || !$this->CanGestUtenti()) {
            AA_Log::Log(get_class() . "->LegacyAddNewUser($params) - utente non autenticato o non autorizzato alla gestione utenti", 100);
            return false;
        }

        //Verifica se il nome utente sia valido
        if ($params['user'] == "") {
            AA_Log::Log(get_class() . "->LegacyAddNewUser($params) - nome utente non impostato", 100);
            return false;
        }

        //Verifica se l'utente esiste già
        if (!isset($params['new_id']) && AA_user::UserNameExist($params['user'])) {
            AA_Log::Log(get_class() . "->LegacyAddNewUser($params) - nome utente già esistente.", 100);
            return false;
        }

        $db = new AA_AccountsDatabase();

        $new_id=0;

        if(isset($params['new_id']) && $params['new_id'] > 0) $new_id=$params['new_id'];
    
        if ($this->oStruct->GetAssessorato(true) != 0 && $this->oStruct->GetAssessorato(true) != $params['assessorato']) {
            AA_Log::Log(__METHOD__." - Assessorato diverso", 100);
            return false;
        }
        if ($this->oStruct->GetDirezione(true) != 0 && $this->oStruct->GetDirezione(true) != $params['direzione']) {
            AA_Log::Log(__METHOD__." - Direzione diversa", 100);
            return false;
        }
        if ($this->oStruct->GetServizio(true) != 0 && $this->oStruct->GetServizio(true) != $params['servizio']) {
            AA_Log::Log(__METHOD__." - Servizio diverso", 100);
            return false;
        }

        //Non si possono istanziare utenti amministratori dello stesso livello gerarchico (super user escluso)
        if ($this->oStruct->GetServizio(true) == $params['servizio'] && $params['livello'] == 0  && $this->oStruct->GetServizio(true) != 0) {
            $params['livello'] = "1";
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può istanziare utenti amministratori dello stesso livello gerarchico", 100);
        }
        if ($this->oStruct->GetDirezione(true) == $params['direzione'] && $params['servizio'] == 0 && $params['livello'] == 0 && $this->oStruct->GetDirezione(true) != 0) {
            $params['livello'] = "1";
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può istanziare utenti amministratori dello stesso livello gerarchico", 100);
        }
        if ($this->oStruct->GetAssessorato(true) == $params['assessorato'] && $params['direzione'] == 0 && $params['livello'] == 0 && $this->oStruct->GetAssessorato(true) != 0) {
            $params['livello'] = "1";
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può istanziare utenti amministratori dello stesso livello gerarchico", 100);
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
            if (isset($params['gest_accessi']) || (isset($params['legacyFlag_accessi']) && $params['legacyFlag_accessi'] > 0)) {
                $flags .= $separatore . "accessi";
                $separatore = "|";
            }
            if (isset($params['admin_gest_accessi']) || (isset($params['legacyFlag_admin_accessi']) && $params['legacyFlag_admin_accessi'] > 0)) {
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

            if (isset($params['gest_processi']) || (isset($params['legacyFlag_processi']) && $params['legacyFlag_processi']>0)) {
                $flags .= $separatore . "processi";
                $separatore = "|";
            }
            if (isset($params['gest_processi_admin']) || (isset($params['legacyFlag_processi_admin']) && $params['legacyFlag_processi_admin']>0)) {
                $flags .= $separatore . "processi_admin";
                $separatore = "|";
            }
            if (isset($params['gest_incarichi_titolari']) || (isset($params['legacyFlag_incarichi_titolari']) && $params['legacyFlag_incarichi_titolari']>0)) {
                $flags .= $separatore . AA_Const::AA_USER_FLAG_INCARICHI_TITOLARI;
                $separatore = "|";
            }
            if (isset($params['gest_incarichi']) || (isset($params['legacyFlag_incarichi']) && $params['legacyFlag_incarichi']>0)) {
                $flags .= $separatore . AA_Const::AA_USER_FLAG_INCARICHI;
                $separatore = "|";
            }
            if (isset($params['patrimonio'])) {
                $flags .= $separatore . "patrimonio";
                $separatore = "|";
            }
            if (isset($params['concurrent']) && $params['concurrent']>0) {
                $flags .= $separatore . "concurrent";
                $separatore = "|";
            }

            //AA_Log::Log(get_class()."->UpdateUser($idUser, $params)", 100, false,true);
        }

        //la modifica delle schede pubblicate può essere abilitata anche dagli altri utenti amministratori
        if (isset($params['unlock']) && $params['livello'] == 0) {
            $flags .= $separatore . "P1";
            $separatore = "|";
        }

        //Inserisce l'utente
        $sql = "INSERT INTO utenti SET ";
        if($new_id > 0) $sql .= "id='".$new_id."', id_assessorato='" . $params['assessorato'] . "'";
        else $sql .= "id_assessorato='" . $params['assessorato'] . "'";
        $sql .= ",id_direzione='" . $params['direzione'] . "'";
        $sql .= ",id_servizio='" . $params['servizio'] . "'";
        $sql .= ",id_settore='0'";
        $sql .= ",user='" . addslashes(trim($params['user'])) . "'";
        if (isset($params['passwd'])) $sql .= ",passwd=MD5('" . $params['passwd'] . "')";
        else $sql .= ",passwd=MD5('" . date("Y/m/d H:i") . "')";
        $sql .= ",livello='" . $params['livello'] . "'";
        $sql .= ",nome='" . addslashes($params['nome']) . "'";
        $sql .= ",cognome='" . addslashes($params['cognome']) . "'";
        $sql .= ",email='" . addslashes($params['email']) . "'";
        $sql .= ",phone='" . addslashes($params['phone']) . "'";
        $sql .= ",image=''";
        $sql .= ",lastlogin=''";
        $sql .= ",flags='" . $flags . "'";
        if (isset($params['disable'])) $sql .= ",disable='1'";
        else $sql .= ",disable='0'";
        if (isset($params['concurrent']) && $params['concurrent']>0) $sql .= ",concurrent='1'";
        else $sql .= ",concurrent='0'";

        if (!$db->Query($sql)) {
            AA_Log::Log(__METHOD__." - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }

        $legacy_data=array(
            "id_assessorato"=>$params['assessorato'],
            "id_direzione"=>$params['direzione'],
            "id_servizio"=>$params['servizio'],
            "id"=>$new_id,
            "level"=>$params['livello'],
            "flags"=>$flags
        );

        if (isset($params['passwd']) && $params['passwd'] !="") $legacy_data['pwd']=md5($params['passwd']);

        //Aggiorna la nuova tabella
        if($new_id > 0) $db->Query("UPDATE ".static::AA_DB_TABLE." SET legacy_data='".addslashes(json_encode($legacy_data))."' WHERE id='".$new_id."' LIMIT 1");
        
        AA_Log::LogAction($this->GetID(), "1,9," . $new_id, $sql); //Old stuff

        return true;
    }

    //Aggiorna L'utente
    public function UpdateUser($idUser, $params)
    {
        if ($this->IsGuest()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido", 100);
            return false;
        }

        //Verifica se l'utente corrente può gestire gli utenti
        if (!$this->isCurrentUser()) {
            AA_Log::Log(__METHOD__." - utente non autenticato.", 100);
            return false;
        }

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(__METHOD__." - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica se l'utente corrente può modificare l'utente indicato
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare l'utente indicato: " . $user->GetUsername(), 100);
            return false;
        }

        if(!isset($params['user']) || $params['user']=="")
        {
            $params['user']=$user->GetUsername();
        }

        if($params['user'] !="" && $params['user'] !=$user->GetUsername())
        {
            if($this->UserNameExist($params['user']))
            {
                AA_Log::Log(__METHOD__." - Nome utente già in uso.", 100);
                return false;
            }
        }

        if(!isset($params['email']) || $params['email'] == "")
        {
            $params['email']=$user->GetEmail();
        }

        if(!isset($params['nome']) || $params['nome'] == "")
        {
            $params['nome']=$user->GetNome();
        }

        if(!isset($params['cognome']) || $params['cognome'] == "")
        {
            $params['cognome']=$user->GetCognome();
        }

        $allUserGroups=$this->GetAllGroups();

        //------------- Ruolo -----------------
        if(!isset($params['ruolo'])) 
        {
            $ruolo=$user->GetRuolo(true);
            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                if(isset($params['livello']) && $params['livello']==1) $ruolo=static::AA_USER_GROUP_OPERATORS;
                if(isset($params['livello']) && $params['livello']==0) $ruolo=static::AA_USER_GROUP_ADMINS;    
            }
        }
        else
        {
            $ruolo=$params['ruolo'];
        }

        if(array_search($ruolo,$allUserGroups)===false)
        {
            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                $params['livello']==2;
            }   
            $ruolo=static::AA_USER_GROUP_USERS;
        } 
        //-------------------------------------

        //--------------- Gruppi --------------
        if(!isset($params['groups'])) $params['groups']=$user->GetGroups();
        
        $groups=array($ruolo);
        foreach($params['groups'] as $curGroup)
        {
            if($curGroup > 1000 && array_search($curGroup,$allUserGroups) !== false)
            {
                $groups[]=$curGroup;
            }
        }

        if(sizeof($groups)==0)
        {
            //default group (utenti)
            $groups=array(static::AA_USER_GROUP_USERS);
        }
        //-------------------------------------

        //--------------- Status --------------
        $status=$user->GetStatus();
        if(isset($params['status']) && $params['status']==static::AA_USER_STATUS_ENABLED) $status=static::AA_USER_STATUS_ENABLED;
        if(isset($params['status']) && $params['status']==static::AA_USER_STATUS_DELETED) $status=static::AA_USER_STATUS_DELETED;
        if(isset($params['status']) && $params['status']==static::AA_USER_STATUS_DISABLED) $status=static::AA_USER_STATUS_DISABLED;

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if(isset($params['status']))
            {
                if($status==static::AA_USER_STATUS_DISABLED) $params['disable']=1;
                if($status==static::AA_USER_STATUS_DELETED) $params['eliminato']=1;
            }
            else
            {   
                if(isset($params['disable']) && $params['disable'] > 0) $status=static::AA_USER_STATUS_DISABLED;
                if(isset($params['eliminato']) && $params['eliminato'] > 0) $status=static::AA_USER_STATUS_DELETED;
            }

            if(!$this->LegacyUpdateUser($idUser,$params))
            {
                return false;
            }
        }
        //--------------------------------------

        if(!isset($params['flags']))
        {
            $params['flags']=$user->GetFlags(false,false);
        }

        $info=json_encode(array(
            "nome"=>trim($params['nome']),
            "cognome"=>trim($params['cognome']),
            "phone"=>trim($params['phone']),
            "image"=>trim($params['image']),
            "cf"=>trim($params['cf']),
        ));

        $sql="UPDATE ".static::AA_DB_TABLE." SET ";
        $sql.="user='".addslashes(trim($params['user']))."'";
        $sql.=", email='".addslashes(trim($params['email']))."'";
        $sql.=", flags='".addslashes(trim($params['flags']))."'";
        $sql.=", info='".addslashes($info)."'";
        $sql.=", data_abilitazione='".date("Y-m-d")."'";
        $sql.=", status='".$status."'";
        if (isset($params['passwd']) && $params['passwd'] !="") $sql.=", passwd='".AA_Utils::password_hash($params['passwd'])."'";

        $sql.=", ".static::AA_DB_TABLE.".groups='".addslashes(implode(",",$groups))."'";

        $sql.=" WHERE id='".$user->GetId()."' LIMIT 1";

        $db=new AA_AccountsDatabase();

        if ($db->Query($sql) === false) {
            AA_Log::Log(__METHOD__."  - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            AA_Log::Log(__METHOD__."  - Errore nell'aggiornamento dell'utente:  " . $user->GetUsername(), 100);
            return false;
        }

       return true;
    }

    //Aggiorna L'utente (legacy)
    public function LegacyUpdateUser($idUser, $params)
    {
        //AA_Log::Log(__METHOD__."");

        if ($this->IsGuest()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido", 100);
            return false;
        }

        //Verifica se l'utente corrente può gestire gli utenti
        if (!$this->isCurrentUser()) {
            AA_Log::Log(__METHOD__." - utente non autenticato.", 100);
            return false;
        }

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(__METHOD__." - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica se l'utente corrente può modificare l'utente indicato
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare l'utente indicato: " . $user->GetUsername(), 100);
            return false;
        }

        //Non si può modificare il livello per utenti amministratori dello stesso livello gerarchico (super user escluso)
        $struct = $this->oStruct;
        if ($struct->GetServizio(true) == $params['servizio'] && $params['livello'] == 0 && $struct->GetServizio(true) != 0) {
            $params['livello'] = "";
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare il livello dell'utente indicato: " . $user->GetUsername(), 100);
        }
        if ($struct->GetDirezione(true) == $params['direzione'] && $params['servizio'] == 0 && $params['livello'] == 0 && $struct->GetDirezione(true) != 0) {
            $params['livello'] = "";
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare il livello dell'utente indicato: " . $user->GetUsername(), 100);
        }
        if ($struct->GetAssessorato(true) == $params['assessorato'] && $params['direzione'] == 0 && $params['livello'] == 0 && $struct->GetAssessorato(true) != 0) {
            $params['livello'] = "";
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare il livello dell'utente indicato: " . $user->GetUsername(), 100);
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
            if (isset($params['gest_accessi']) || (isset($params['legacyFlag_accessi']) && $params['legacyFlag_accessi'] > 0)) {
                $flags .= $separatore . "accessi";
                $separatore = "|";
            }
            if (isset($params['admin_gest_accessi']) || (isset($params['legacyFlag_admin_accessi']) && $params['legacyFlag_admin_accessi'] > 0)) {
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
            if (isset($params['art23']) || (isset($params['flag_art23']) && $params['flag_art23'] > 0)) {
                $flags .= $separatore . "art23";
                $separatore = "|";
            }
            if (isset($params['art22']) || (isset($params['flag_art22']) && $params['flag_art22'] > 0)) {
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

            if (isset($params['gest_processi']) || (isset($params['legacyFlag_processi']) && $params['legacyFlag_processi']>0)) {
                $flags .= $separatore . "processi";
                $separatore = "|";
            }
            if (isset($params['gest_processi_admin']) || (isset($params['legacyFlag_processi_admin']) && $params['legacyFlag_processi_admin']>0)) {
                $flags .= $separatore . "processi_admin";
                $separatore = "|";
            }
            if (isset($params['gest_incarichi_titolari']) || (isset($params['legacyFlag_incarichi_titolari']) && $params['legacyFlag_incarichi_titolari']>0)) {
                $flags .= $separatore . AA_Const::AA_USER_FLAG_INCARICHI_TITOLARI;
                $separatore = "|";
            }
            if (isset($params['gest_incarichi']) || (isset($params['legacyFlag_incarichi']) && $params['legacyFlag_incarichi']>0)) {
                $flags .= $separatore . AA_Const::AA_USER_FLAG_INCARICHI;
                $separatore = "|";
            }
            if (isset($params['patrimonio']) || (isset($params['flag_patrimonio']) && $params['flag_patrimonio'] > 0)) {
                $flags .= $separatore . "patrimonio";
                $separatore = "|";
            }
            if (isset($params['concurrent']) && $params['concurrent']>0) {
                $flags .= $separatore . "concurrent";
                $separatore = "|";
            }

            //AA_Log::Log(get_class()."->UpdateUser($idUser, $params)", 100, false,true);
        }
        else
        {
            $flags=$user->GetLegacyFlags();
        }

        //la modifica delle schede pubblicate può essere abilitata anche dagli altri utenti amministratori
        if (isset($params['unlock']) && $params['livello'] == 0) {
            $flags .= $separatore . "P1";
            $separatore = "|";
        }

        //Aggiorna l'utente
        $db = new AA_AccountsDatabase();
        $sql = "UPDATE utenti SET user=user";
        if ($params['passwd'] != "") $sql .= ",passwd=MD5('" . $params['passwd'] . "')";

        //Dati aggionabili solo se utenti diversi
        if ($this->GetID() != $user->GetID()) {
            $sql .= ",id_assessorato='" . $params['assessorato'] . "'";
            $sql .= ",id_direzione='" . $params['direzione'] . "'";
            $sql .= ",id_servizio='" . $params['servizio'] . "'";
            $sql .= ",id_settore='0'";
            if ($params['livello'] != "") $sql .= ",livello='" . $params['livello'] . "'";
            if ($this->IsSuperUser()) $sql .= ",flags='" . $flags . "'";
            if (isset($params['disable'])) $sql .= ",disable='1'";
            else $sql .= ",disable='0'";
            if (isset($params['concurrent']) && $params['concurrent']>0) $sql .= ",concurrent='1'";
            else $sql .= ",concurrent='0'";
        }

        $sql .= ",nome='" . addslashes($params['nome']) . "'";
        $sql .= ",cognome='" . addslashes($params['cognome']) . "'";
        $sql .= ",email='" . $params['email'] . "'";

        $sql .= " where id='" . $user->GetID() . "' LIMIT 1";

        if ($db->Query($sql) === false) {
            AA_Log::Log(__METHOD__."  - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }

        $legacy_data=json_encode(array(
            "id_assessorato"=>$params['assessorato'],
            "id_direzione"=>$params['direzione'],
            "id_servizio"=>$params['servizio'],
            "level"=>$params['livello'],
            "flags"=>$flags
        ));

        //if (isset($params['passwd']) && $params['passwd'] !="") $legacy_data['pwd']=md5($params['passwd']);

        //Aggiorna la nuova tabella
        $db->Query("UPDATE ".static::AA_DB_TABLE." SET legacy_data='".addslashes($legacy_data)."' WHERE id='".$user->GetId()."' LIMIT 1");
        
        AA_Log::LogAction($this->GetID(), "2,9," . $user->GetID(), $sql); //Old stuff

        return true;
    }

    //Aggiornamento della password utente
    public function ChangePwd($params=null)
    {
        if(!$this->isCurrentUser() || !$this->IsValid() || !is_array($params))
        {
            AA_Log::Log(__METHOD__." - Utente o parametri non validi",100);
            return false;
        }

        $newPwd=str_replace(array(",",";","'",'"',"+","-"," "),"",trim($params['new_user_pwd']));
        $reNewPwd=str_replace(array(",",";","'",'"',"+","-"," "),"",trim($params['re_new_user_pwd']));
        $otp=trim($params['otp']);

        //verifica OTP
        if(!$this->MailOTPChangePwdChallengeVerify($otp))
        {
            AA_Log::Log(__METHOD__." - Codice OTP errato.",100);
            return false;
        }

        //Verifica che la nuova password abbia almeno 12 caratteri, contenga un numero, una lettera maiuscola e una lettera minuscola e non contenga la vecchia password
        $password_regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/"; 

        if(preg_match($password_regex, $newPwd)==0 || $newPwd == "")
        {
            AA_Log::Log(__METHOD__ . " - La nuova password deve contenere:<br>almeno 12 caratteri<br>almeno una lettera maiuscola<br>almeno un numero<br>almeno una lettera minuscola<br>almeno uno dei seguenti simboli: @$!%*?&", 100);
            return false;
        }

        if($newPwd != $reNewPwd)
        {
           AA_Log::Log(__METHOD__ . " - La nuova password deve coincidere con quella ridigitata.", 100);
           return false;
        }

        //verifica vecchia password
        $db=new AA_AccountsDatabase();

        //salva la nuova password
        $query = "UPDATE ".static::AA_DB_TABLE." set passwd='" . AA_Utils::password_hash($newPwd) . "' where id='" . $this->GetID() . "' LIMIT 1";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__ . " - Errore query db. ".$query, 100);
            return false;
        }

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            $query = "UPDATE utenti set passwd='" .md5($newPwd)."' where id='" . $this->GetID() . "' LIMIT 1";
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__ . " - Errore query db. ".$query, 100);
                return false;
            }
        }

        if(isset($_SESSION['MailOTP-changepwd-code'])) unset($_SESSION['MailOTP-changepwd-code']);

        return true;
    }

    //Funzione di aggiornamento del profilo utente corrente
    public static function UpdateCurrentUserProfile($params,$imageFileName="")
    {
        $user=AA_User::GetCurrentUser();
        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente corrente non valido", 100);
            return false;
        }

        if($params["email"] != $user->GetEmail())
        {
            AA_Log::Log(__METHOD__ . " - L'utente corrente ha una email diversa da quella indicata.", 100);
            return false;
        }

        if($params['nome'] =="" || $params['cognome']=="")
        {
            AA_Log::Log(__METHOD__ . " - il nome e il cognome non possono essere vuoti.", 100);
            return false;            
        }

        $db=new AA_AccountsDatabase();
        $pwd=false;

        if($params["old_pwd"] !="" && $params['new_pwd'] !="" && $params["new_pwd_retype"] !="")
        {
            //Verifica che la nuova password abbia almeno 8 caratteri, contenga un numero, una lettera maiuscola e una lettera minuscola e non contenga la vecchia password
            $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{12,}$/"; 
            if(preg_match($password_regex, $params['new_pwd'])==0)
            {
                AA_Log::Log(__METHOD__ . " - La nuova password deve avere almeno 12 caratteri, almeno una lettera maiuscola e almeno una lettera minuscola.", 100);
                return false;
            }

            if($params['new_pwd'] != $params['new_pwd_retype'])
            {
                AA_Log::Log(__METHOD__ . " - La nuova password deve coincidere con quella ridigitata.", 100);
                return false;
            }

            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                if(!static::LegacyUpdateCurrentUserProfile($params,$imageFileName))
                {
                    return false;
                }
            }

            //verifica che la password attuale sia corretta
            $query="SELECT passwd,info from ".static::AA_DB_TABLE." WHERE id='".addslashes($user->getId())."' LIMIT 1";
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__ . " - Errore nella verifica delle credenziali impostate.", 100);
                return false;
            }

            if($db->GetAffectedRows()==0)
            {
                AA_Log::Log(__METHOD__ . " - Utente non trovato.", 100);

                if(!static::MigrateLegacyUser($user,$params['new_pwd']))
                {
                    return false;
                }

                return true;
            }

            $rs=$db->GetResultSet();
            if(!password_verify($params['old_pwd'],$rs[0]['passwd']))
            {
                AA_Log::Log(__METHOD__ . " - Vecchia password errata.", 100);
                return false;
            }

            $pwd=true;
        }

        $info=json_decode($rs[0]['info'],true);
        if(!is_array($info))
        {
            $info=array("nome"=>$params['nome'],"cognome"=>$params['cognome'],"phone"=>$params['phone'],"image"=>$imageFileName);
        }
        else
        {
            $info['nome']=$params['nome'];
            $info['cognome']=$params['cognome'];
            $info['phone']=$params['phone'];
            $info['image']=$imageFileName;
        }

        $query="UPDATE ".static::AA_DB_TABLE." SET info='".addSlashes(json_encode($info))."'";
        if($pwd) $query.=",passwd='".addslashes(AA_Utils::password_hash($params['new_pwd']))."'";
        $query.=" WHERE id='".$user->GetId()."' LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__ . " - Errore durante l'aggiornamento dei dati.", 100);
            return false;
        }

        return true;
    }

    //Funzione di aggiornamento del profilo utente corrente (legacy)
    public static function LegacyUpdateCurrentUserProfile($params,$imageFileName="")
    {
        $user=AA_User::GetCurrentUser();
        if ($user->IsGuest()) {
            AA_Log::Log(__METHOD__ . " - Utente corrente non valido", 100);
            return false;
        }

        if($params["email"] != $user->GetEmail())
        {
            AA_Log::Log(__METHOD__ . " - L'utente corrente ha una email diversa da quella indicata.", 100);
            return false;
        }

        if($params['nome'] =="" || $params['cognome']=="")
        {
            AA_Log::Log(__METHOD__ . " - il nome e il cognome non possono essere vuoti.", 100);
            return false;            
        }

        $db=new AA_AccountsDatabase();
        $pwd=false;

        if($params["old_pwd"] !="" && $params['new_pwd'] !="" && $params["new_pwd_retype"] !="")
        {
            //verifica che la password attuale sia corretta
            $query="SELECT id from utenti WHERE email='".addslashes($params['email'])."' AND passwd=MD5('".addslashes($params['old_pwd'])."') LIMIT 1";
            if(!$db->Query($query))
            {
                AA_Log::Log(__METHOD__ . " - Errore nella verifica delle credenziali impostate.", 100);
                return false;
            }

            if($db->GetAffectedRows()==0)
            {
                AA_Log::Log(__METHOD__ . " - La password corrente è errata.", 100);
                return false;
            }

            //Verifica che la nuova password abbia almeno 8 caratteri, contenga un numero, una lettera maiuscola e una lettera minuscola e non contenga la vecchia password
            $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/"; 
            if(preg_match($password_regex, $params['new_pwd'])==0)
            {
                AA_Log::Log(__METHOD__ . " - La nuova password deve avere almeno 8 caratteri, almeno una lettera maiuscola e almeno una lettera minuscola.", 100);
                return false;
            }

            if($params['new_pwd'] != $params['new_pwd_retype'])
            {
                AA_Log::Log(__METHOD__ . " - La nuova password deve coincidere con quella ridigitata.", 100);
                return false;
            }

            $pwd=true;
        }

        $query="UPDATE utenti SET nome='".addSlashes($params['nome'])."',cognome='".addSlashes($params['cognome'])."',phone='".addslashes($params['phone'])."'";
        if($pwd) $query.=",passwd=MD5('".addslashes($params['new_pwd'])."')";
        if($imageFileName !="") $query.=", image='".addslashes($imageFileName)."'";
        $query.=" WHERE id='".$user->GetId()."' LIMIT 1";

        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__ . " - Errore durante l'aggiornamento dei dati.", 100);
            return false;
        }

        //AA_Log::Log(__METHOD__ . " - query: ".$query, 100);

        return true;
    }

    //Elimina l'utente indicato
    public function LegacyDeleteUser($idUser)
    {
        //AA_Log::Log(__METHOD__,100);

        if ($this->IsGuest()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido", 100);
            return false;
        }

        //Verifica se l'utente corrente può gestire gli utenti
        if (!$this->isCurrentUser()) {
            AA_Log::Log(__METHOD__." - utente corrente non autenticato.", 100);
            return false;
        }

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(__METHOD__." - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica se l'utente corrente può modificare l'utente indicato
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare l'utente indicato: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica che non sia l'utente corrente
        if ($this->GetID() == $user->GetID()) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può eliminare se stesso", 100);
            return false;
        }

        //Elimina l'utente indicato
        $db = new AA_AccountsDatabase();
        $sql = "DELETE FROM utenti where id='" . $user->GetID() . "' LIMIT 1";

        if ($db->Query($sql) === false) {
            AA_Log::Log(__METHOD__." - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }

        //AA_Log::LogAction($this->GetID(), "3,9," . $user->GetID(), Database::$lastQuery); //Old stuff

        return true;
    }

    //Elimina l'utente indicato
    public function DeleteUser($idUser,$bOnlyTrash=true)
    {
        //AA_Log::Log(__METHOD__, 100);

        if ($this->IsGuest()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido", 100);
            return false;
        }

        //Verifica se l'utente corrente può gestire gli utenti
        if (!$this->isCurrentUser()) {
            AA_Log::Log(__METHOD__." - utente corrente non autenticato.", 100);
            return false;
        }

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(__METHOD__." - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica se l'utente corrente può modificare l'utente indicato
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare l'utente indicato: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica che non sia l'utente corrente
        if ($this->GetID() == $user->GetID()) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può eliminare se stesso", 100);
            return false;
        }

        $db = new AA_AccountsDatabase();

        if(AA_Const::AA_ENABLE_LEGACY_DATA)
        {
            if(!$bOnlyTrash)
            {
                if(!$this->LegacyDeleteUser($user))
                {
                    return false;
                }    
            }
            else
            {
                $sql = "UPDATE utenti SET disable='1' where id='" . $user->GetID() . "' LIMIT 1";

                if ($db->Query($sql) === false) {
                    AA_Log::Log(__METHOD__." - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
                    return false;
                }
            }
        }

        //Elimina l'utente indicato
        if($bOnlyTrash)
        {
            $sql = "UPDATE ".static::AA_DB_TABLE." SET status='".static::AA_USER_STATUS_DELETED."' where id='" . $user->GetID() . "' LIMIT 1";

            if ($db->Query($sql) === false) {
                AA_Log::Log(__METHOD__." - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
                return false;
            }
    
            return true;    
        }

        $userimage=$user->GetImage();
        if($userimage !="")
        {
            $storage=AA_Storage::GetInstance($this);
            if($storage->IsValid())
            {
                if(!$storage->DelFile($userimage))
                {
                    AA_Log::Log(__METHOD__." - Errore: immagine utente (".$userimage.") non trovata." , 100);
                }
            }
        }

        $sql = "DELETE FROM ".static::AA_DB_TABLE." WHERE id='" . $user->GetID() . "' LIMIT 1";

        if ($db->Query($sql) === false) {
            AA_Log::Log(__METHOD__." - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }
    
        return true;
    }

    //Ripristina l'utente indicato
    public function ResumeUser($idUser)
    {
        //AA_Log::Log(__METHOD__, 100);

        if ($this->IsGuest()) {
            AA_Log::Log(__METHOD__." - utente corrente non valido", 100);
            return false;
        }

        //Verifica se l'utente corrente può gestire gli utenti
        if (!$this->isCurrentUser()) {
            AA_Log::Log(__METHOD__." - utente corrente non autenticato.", 100);
            return false;
        }

        if (!($idUser instanceof AA_User)) {
            $user = AA_User::LoadUser($idUser);
        } else $user = $idUser;

        if (!$user->IsValid()) {
            AA_Log::Log(__METHOD__." - Id utente non valido: $idUser o utente non valido: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica se l'utente corrente può modificare l'utente indicato
        if (!$this->CanModifyUser($user)) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare l'utente indicato: " . $user->GetUsername(), 100);
            return false;
        }

        //Verifica che non sia l'utente corrente
        if ($this->GetID() == $user->GetID()) {
            AA_Log::Log(__METHOD__." - L'utente corrente (" . $this->GetUsername() . ") non può modificare lo stato di se stesso.", 100);
            return false;
        }

        //Elimina l'utente indicato
        $db = new AA_AccountsDatabase();
        
        $sql = "UPDATE ".static::AA_DB_TABLE." SET status='".static::AA_USER_STATUS_DISABLED."' where id='" . $user->GetID() . "' LIMIT 1";

        if ($db->Query($sql) === false) {
            AA_Log::Log(__METHOD__." - Errore: " . $db->GetErrorMessage() . " - nella query: " . $sql, 100);
            return false;
        }
    
        return true;
    }

    //Resetta la password dell'utente associato alla email indicata e la spedisce alla casella indicata
    static public function ResetPassword($email, $bSendEmail = true)
    {
        //AA_Log::Log(get_class() . "->RecoverPassword($email)");

        $users = AA_User::LoadUsersFromEmail($email);

        if (is_array($users) && count($users) > 0) {
            $credenziali = "";
            $db = new AA_AccountsDatabase();

            foreach ($users as $user) {
                //Verifica che l'utente sia valido
                if (!$user->IsValid()) {
                    AA_Log::Log(__METHOD__."- Utente non trovato.", 100);
                }

                //Verifica se l'utente è disattivato
                if ($user->IsDisabled()) {
                    AA_Log::Log(__METHOD__."- Utente disattivato.", 100);
                }

                //Reimposta la password
                if ($user->IsValid() && !$user->IsDisabled()) 
                {
                    $newPwd = "A".substr(md5(uniqid(mt_rand(), true)), 0, 8)."a";
                    $struttura="";
                    if(AA_Const::AA_ENABLE_LEGACY_DATA)
                    {
                        if(static::$aResetPasswordEmailParams['bShowStruct'])
                        {
                            $struttura = $user->GetStruct()->GetAssessorato();
                            if ($user->GetStruct()->GetDirezione(true) != 0) $struttura .= " - " . $user->GetStruct()->GetDirezione();
                            if ($user->GetStruct()->GetServizio(true) != 0) $struttura .= " - " . $user->GetStruct()->GetServizio();    
                        }

                        //Reimposta le credenziali dell'utente
                        $query = "UPDATE utenti set passwd=MD5('" . $newPwd . "') where id='" . $user->GetID() . "' LIMIT 1";
                        if (!$db->Query($query)) 
                        {
                            AA_Log::Log(__METHOD__."- Errore durante l'aggiornamento della password per l'utente legacy: " . $user->GetUserName() . " - " . $db->GetErrorMessage()." - query: ".$query, 100);
                        }
                    }
                    
                    //Reimposta le credenziali dell'utente
                    $query = "UPDATE ".static::AA_DB_TABLE." set passwd='" . AA_Utils::password_hash($newPwd) . "' where id='" . $user->GetID() . "' LIMIT 1";
                    if (!$db->Query($query)) 
                    {
                        AA_Log::Log(__METHOD__."- Errore durante l'aggiornamento della password per l'utente: " . $user->GetUserName() . " - " . $db->GetErrorMessage(), 100);
                    } 
                    else 
                    {
                        if(static::$aResetPasswordEmailParams['bShowStruct']) $credenziali .= '<p>struttura: ' . $struttura;
                        else $credenziali .= '<p>';
                        $credenziali .= '
                        nome utente: <b>' . $user->GetUserName() . '</b>
                        password: <b>' . $newPwd . '</b></p>';
                    }
                }
            }

            if ($credenziali != "") {
                $oggetto = str_replace(array("%NOME%","%COGNOME%"),array($user->GetName(),$user->GetCognome()),static::$aResetPasswordEmailParams['oggetto']);

                //$corpo = static::$aResetPasswordEmailParams['incipit'].$credenziali.static::$aResetPasswordEmailParams['post'];
                //$firma = static::$aResetPasswordEmailParams['firma'];

                $corpo = str_replace(array("#www#","%NOME%","%COGNOME%"),array(AA_Const::AA_DOMAIN_NAME.AA_const::AA_WWW_ROOT,$user->GetName(),$user->GetCognome()),static::$aResetPasswordEmailParams['incipit']).$credenziali.str_replace(array("%NOME%","%COGNOME%"),array($user->GetName(),$user->GetCognome()),static::$aResetPasswordEmailParams['post']);
                $firma = str_replace("#www#",AA_Const::AA_DOMAIN_NAME.AA_const::AA_WWW_ROOT,static::$aResetPasswordEmailParams['firma']);

                if ($bSendEmail) 
                {
                    if (!SendMail(array($email), array(), $oggetto, nl2br($corpo) . $firma, array(), 1)) {
                        AA_Log::Log(__METHOD__."- Errore nell'invio della email a: " . $email, 100);
                        return false;
                    }
                }

                return true;
            } else {
                AA_Log::Log(__METHOD__."- Nessun utente valido trovato.", 100);
                return false;
            }
        } else {
            AA_Log::Log(__METHOD__."- Nessun utente valido trovato.", 100);
            return false;
        }

        return false;
    }

    //Resetta la password dell'utente associato alla email indicata e la spedisce alla casella indicata
    static public function SendCredentials($userId=0, $bSendEmail = true)
    {
        //AA_Log::Log(get_class() . "->SendCredentials($email)");

        $user = AA_User::LoadUser($userId);

        if ($user->IsValid()) 
        {
            $credenziali = "";
            $db = new AA_AccountsDatabase();

            //Verifica se l'utente è disattivato
            if ($user->IsDisabled()) {
                AA_Log::Log(__METHOD__."- Utente disattivato.", 100);
                return false;
            }

            //Verifica se l'utente ha una email valida
            if ($user->GetEmail()=="") {
                AA_Log::Log(__METHOD__."- Utente senza email valida.", 100);
                return false;
            }

            $newPwd = "A".substr(md5(uniqid(mt_rand(), true)), 0, 8)."a";
            $struttura="";
            if(AA_Const::AA_ENABLE_LEGACY_DATA)
            {
                if(static::$aSendCredentialsEmailParams['bShowStruct'])
                {
                    $struttura = $user->GetStruct()->GetAssessorato();
                    if ($user->GetStruct()->GetDirezione(true) != 0) $struttura .= " - " . $user->GetStruct()->GetDirezione();
                    if ($user->GetStruct()->GetServizio(true) != 0) $struttura .= " - " . $user->GetStruct()->GetServizio();    
                }

                //Reimposta le credenziali dell'utente
                $query = "UPDATE utenti set passwd=MD5('" . $newPwd . "') where id='" . $user->GetID() . "' LIMIT 1";
                if (!$db->Query($query)) 
                {
                    AA_Log::Log(__METHOD__."- Errore durante l'aggiornamento della password per l'utente: " . $user->GetUserName() . " - " . $db->GetErrorMessage()." - query: ".$query, 100);
                }
            }
            
            //Reimposta le credenziali dell'utente
            $query = "UPDATE ".static::AA_DB_TABLE." set passwd='" . AA_Utils::password_hash($newPwd) . "', lastnotify='".date("Y-m-d H:i:s")."' where id='" . $user->GetID() . "' LIMIT 1";
            if (!$db->Query($query)) {
                AA_Log::Log(__METHOD__."- Errore durante l'aggiornamento della password per l'utente: " . $user->GetUserName() . " - " . $db->GetErrorMessage(), 100);
            } else {
                if(static::$aSendCredentialsEmailParams['bShowStruct']) $credenziali .= '<br>struttura: ' . $struttura;
                $credenziali .= '
                nome utente: <b>' . $user->GetUserName() . '</b>
                password: <b>' . $newPwd . '</b>';
            }
        
            if ($credenziali != "") {
                $oggetto = str_replace(array("%NOME%","%COGNOME%"),array($user->GetName(),$user->GetCognome()),static::$aSendCredentialsEmailParams['oggetto']);

                $corpo = str_replace(array("#www#","%NOME%","%COGNOME%"),array(AA_Const::AA_DOMAIN_NAME.AA_const::AA_WWW_ROOT,$user->GetName(),$user->GetCognome()),static::$aSendCredentialsEmailParams['incipit']).$credenziali.str_replace(array("%NOME%","%COGNOME%"),array($user->GetName(),$user->GetCognome()),static::$aSendCredentialsEmailParams['post']);
                $firma = str_replace(array("#www#"),array(AA_Const::AA_DOMAIN_NAME.AA_const::AA_WWW_ROOT),static::$aSendCredentialsEmailParams['firma']);

                if ($bSendEmail) {
                    if (!SendMail(array($user->GetEmail()), array(), $oggetto, nl2br($corpo) . $firma, array(), 1)) 
                    {
                        AA_Log::Log(__METHOD__."- Errore nell'invio della email a: " . $user->GetEmail(), 100);
                        return false;
                    }

                    if(isset(static::$aSendCredentialsEmailParams['sendToUs']) && static::$aSendCredentialsEmailParams['sendToUs'])
                    {
                        //invio notifica a se stesso
                        if(!SendMail(array(AA_Const::AA_EMAIL_FROM),array(),"Notifica reinvio credenziali utente: ". $user->GetUserName()." - email: ".$user->GetEmail(),"Reinvio credenziali all'utente:<br>login: ".$user->GetUsername()."<br>email: ".$user->GetEmail()."<br>Data: ".date("Y-m-d").$firma))
                        {
                            AA_Log::Log(__METHOD__."- Errore nell'invio notifica", 100);
                        }
                    }
                }
                else
                {
                    AA_Log::Log(__METHOD__."- Set new password to: " . $newPwd, 100);
                }

                return true;
            } else {
                AA_Log::Log(__METHOD__."- Nessun utente valido trovato.", 100);
                return false;
            }
        }
        else 
        {
            AA_Log::Log(__METHOD__."- Nessun utente valido trovato.", 100);
            return false;
        }

        return false;
    }
}

//Utilità
class AA_Utils
{
    //password_hashes
    static public function password_hash($password="")
    {
        if(function_exists("password_hash"))
        {
            return password_hash($password,PASSWORD_DEFAULT);
        }

        return crypt($password,uniqid());
    }

    //password_verify
    static public function password_verify($password="",$hash="")
    {
        if(function_exists("password_verify"))
        {
            return password_verify($password,$hash);
        }

        AA_Log::Log(__METHOD__." - crypt()",100);
        if(crypt($password,$hash)==$hash) return true;
    
        return false;
    }

    //Formata un numero
    static public function number_format($number, $decimals='', $sep1='', $sep2='',$round=true) 
    {
        if($round) return number_format(floatval($number), $decimals, $sep1, $sep2);

        $resto=($number * pow(10 , $decimals + 1) % 10 );
        if ($resto >= 5)
        {
            $diff=$resto*pow(10 , -($decimals+1));
            //AA_Log::Log(__METHOD__." - cambio da: ".$number." a: ".($number-$diff),100);
            $number -= $diff;
        }  
        return number_format(floatVal($number), $decimals, $sep1, $sep2);
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

    static public function GetSessionLog()
    {
        $return = "";
        if(!isset($_SESSION['log'])) $session_log=array();
        else $session_log = array_reverse(unserialize($_SESSION['log']));

        foreach ($session_log as $key => $curLogString) {
            if(is_string($curLogString)) $curLog=unserialize($curLogString);
            else $curLog=$curLogString;
            $return .= '<div style="display:flex; flex-direction: row; justify-content: space-between; align-items: stretch; flex-wrap: wrap; width: 100%; border: 1px solid black; margin-bottom: 1em; font-size: smaller">';
            $return .= '<div style="width: 8%; border: 1px solid black; text-align: center; font-weight: bold; background-color: #DBDBDB; padding: .1em;">Data</div>';
            $return .= '<div style="display: flex; align-items: flex-start; width: 4%; border: 1px solid black; text-align: center; font-weight: bold; background-color: #DBDBDB; padding: .1em;"><div style="width: 100%">Livello</div></div>';
            $return .= '<div style="width: 42%; border: 1px solid black;text-align: center; font-weight: bold; background-color: #DBDBDB; padding: .1em;">Messaggio</div>';
            $return .= '<div style="width: 45%; border: 1px solid black;text-align: center; font-weight: bold; background-color: #DBDBDB;padding: .1em;">backtrace</div>';
            $return .= '<div style="width: 8%; border: 1px solid black;text-align: center; padding: .1em;"><span>' . $curLog->GetTime() . '</span></div>';
            $return .= '<div style="display: flex; align-items: flex-start; width: 4%; border: 1px solid black; text-align: center; padding: .1em;"><div style="width: 100%">' . $curLog->GetLevel() . '</div></div>';
            
            $msg=$curLog->GetMsg();
            if(is_array($msg))
            {
                $result="";
                foreach($msg as $curMsg)
                {
                    $result.=htmlentities($curMsg)."<br>";
                }

                $msg=$result;
            }
            else
            {
                $msg=htmlentities($msg);
            }
            $return .= '<div style="width: 42%; border: 1px solid black; padding: .1em; overflow: auto; word-break: break-all;">' .$msg. '</div>';
            $return .= '<div style="width: 45%; border: 1px solid black; padding: .1em; font-size: smaller">';
            $html = "";
            $i = 0;
            foreach ($curLog->GetBackTrace() as $key => $value) {
                if ($i > 0) {
                    $html .= "<p>#" . $key . " - " . $value['file'] . " (line: " . $value['line'] . ")";
                    $html .= "<br/>";
                    if(isset($value['class'])) $html.=$value['class'];
                    if(isset($value['type']))  $html.=$value['type'];
                    if(isset($value['function'])) 
                    {
                        $html.=$value['function'] . "(";
                        $separatore = "";
                        foreach ($value['args'] as $curArg) {
                            if ($curArg == "") $html .= $separatore . '""';
                            else if (!is_array($curArg))
                            {
                                if(is_string($curArg)) $html .= $separatore . htmlentities($curArg);
                                else $html .= $separatore . htmlentities(print_r($curArg, true));
                            } 
                            $separatore = ",";
                        }
                        $html .= ")";
                    }
                    $html.="</p>";
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

    //Verifica che la stringa siua una data valida
    static function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
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

//AA_Object v2
class AA_Object_V2
{
    //tabella oggetti
    static protected $AA_DBTABLE_OBJECTS = "aa_objects";

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

    //restituisce una rappresentazione testula dell'oggetto
    public function serialize()
    {
        return json_encode(get_object_vars($this));
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

        if($this->sLog != "") $logs=explode("\n",$this->sLog);
        else $logs=array();

        $logs[]=Date("Y-m-d H:i:s") . "|" . $user->GetUsername() . "|" . $actionType . "|" . $log;

        if(sizeof($logs)>1000)
        {
            //lascia solo gli ultimi 100 log
            $logs=array_slice($logs, -1000);
        }

        $this->sLog = implode("\n",$logs);
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

        $csv.=$this->GetId();
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

        $csv.=$separator.str_replace("\n",' ',$this->GetName());
        $csv.= $separator.str_replace("\n",' ',$this->GetDescr());
        
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
            $query = "INSERT INTO " . static::$AA_DBTABLE_OBJECTS . " SET ";
            $where = "";
        } else {
            $query = "UPDATE " . static::$AA_DBTABLE_OBJECTS . " SET ";
            $where = " WHERE " . static::$AA_DBTABLE_OBJECTS . ".id='" . addslashes($object->GetId()) . "' LIMIT 1";
        }

        $struct = $object->GetStruct();

        //Imposta un nome di default se non ce l'ha
        if($object->GetName() =="") $object->SetName("Nuovo oggetto da rinominare");

        $query .= " id_data='" . $object->GetIdData() . "'";
        $query .= ", id_data_rev='" . $object->GetIdDataRev() . "'";
        $query .= ", status='" . $object->GetStatus() . "'";
        $query .= ", nome='" . addslashes(mb_substr($object->GetName(),0,250)) . "'";
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
                    $query = "DELETE from " . static::$AA_DBTABLE_OBJECTS . " WHERE id = '" . $this->GetId() . "' LIMIT 1";
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
    static public function Load($id = 0, $user = null, $bLoadData = true, $bCheckPerms=true)
    {
        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        $db = new AA_Database();
        $query = "SELECT * from " . static::$AA_DBTABLE_OBJECTS . " WHERE id ='" . addslashes($id) . "' LIMIT 1";
        if (!$db->Query($query)) 
        {
            AA_Log::Log(__METHOD__ . " - Errore: " . $db->GetErrorMessage(), 100);
            $object = new AA_Object_V2(0, $user, false);
            $object->bValid = false;
            return $object;
        }

        if ($db->GetAffectedRows() > 0) 
        {
            $rs = $db->GetResultSet();

            $objectClass = "AA_Object_V2";
            if (class_exists($rs[0]['class'])) {
                $objectClass = $rs[0]['class'];
            }

            $object = new $objectClass(0, $user);
            if(AA_Const::AA_ENABLE_LEGACY_DATA) $object->oStruct = AA_Struct::GetStruct($rs[0]['id_assessorato'], $rs[0]['id_direzione'], $rs[0]['id_servizio']);
            else $object->oStruct = AA_Struct::GetStruct(0,0,0);
            $object->nStatus = $rs[0]['status'];
            $object->sClass = $rs[0]['class'];
            $object->sAggiornamento = $rs[0]['aggiornamento'];
            $object->sName = $rs[0]['nome'];
            $object->sDescr = $rs[0]['descrizione'];
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

            if($object->bValid && $bCheckPerms)
            {
                $perms = $object->GetUserCaps($user);
                if (($perms & AA_Const::AA_PERMS_READ) == 0) {
                    AA_Log::Log(__METHOD__ . " - Errore: l'utente corrente non ha i permessi per visualizzare l'oggetto. ".print_r($object,true), 100);
                    $object->bValid = false;
                    $object->nId = 0;
                    $object->nId_Data = 0;
                    $object->nId_Data_Rev = 0;
                    $object->sLog = "";
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
            }
        } 
        else 
        {
            AA_Log::Log(__METHOD__ . " - Errore: oggetto non trovato ($id)", 100);
            $object = new AA_Object_V2(0, $user, false);
            $object->bValid = false;
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
    public function __construct($id = 0, $user = null, $bLoadData = true, $bCheckPerms=true)
    {
        //Verifica utente
        if ($user instanceof AA_User) {
            if (!$user->isCurrentUser() || $user->IsGuest()) {
                $user = AA_User::GetCurrentUser();
            }
        } else $user = AA_User::GetCurrentUser();

        if ($id > 0) 
        {
            $db = new AA_Database();
            $query = "SELECT * from " . static::$AA_DBTABLE_OBJECTS . " WHERE id ='" . addslashes($id) . "' LIMIT 1";
            if (!$db->Query($query)) 
            {
                AA_Log::Log(__METHOD__ . " - Errore: " . $db->GetErrorMessage(), 100);
                return;
            }

            if ($db->GetAffectedRows() > 0) 
            {
                $rs = $db->GetResultSet();
                $this->nStatus = $rs[0]['status'];
                if(AA_Const::AA_ENABLE_LEGACY_DATA) $this->oStruct = AA_Struct::GetStruct($rs[0]['id_assessorato'], $rs[0]['id_direzione'], $rs[0]['id_servizio']);
                else $this->oStruct = AA_Struct::GetStruct(0,0,0);
                $this->sClass = $rs[0]['class'];
                $this->sAggiornamento = $rs[0]['aggiornamento'];
                $this->sName = $rs[0]['nome'];
                $this->sDescr = $rs[0]['descrizione'];
                $this->nId = $rs[0]['id'];
                $this->nId_Data = $rs[0]['id_data'];
                $this->nId_Data_Rev = $rs[0]['id_data_rev'];
                $this->sLog = $rs[0]['logs'];
                $this->bValid = true;
                
                if($bLoadData && $this->sDbDataTable != "" ) $this->LoadData($user);

                if($bCheckPerms || $user->IsGuest())
                {
                    $perms=$this->GetUserCaps($user);
                    if(($perms&AA_Const::AA_PERMS_WRITE) > 0) $this->bReadOnly=false;
                    if(($perms & AA_Const::AA_PERMS_READ) == 0)
                    {
                        $this->nStatus = AA_Const::AA_STATUS_NONE;
                        $this->oStruct = AA_Struct::GetStruct(0,0,0);
                        $this->sClass = "AA_Object_V2";
                        $this->sAggiornamento = "";
                        $this->sName = "";
                        $this->sDescr = "";
                        $this->nId = 0;
                        $this->nId_Data = 0;
                        $this->nId_Data_Rev = 0;
                        $this->sLog = "";
                        $this->aProps=array();
                        $this->bValid=false;
                    } 
                }
            }

            /*$object = static::Load($id, $user, $bLoadData,false);

            $perms=$this->GetUserCaps($user);

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
                if($bLoadData) $this->aProps=$object->aProps;

                //AA_Log::Log(__METHOD__." - oggetto: ".print_r($object,true)." - this: ".print_r($this,true),100);
            } else {
                AA_Log::Log(__METHOD__ . " - Errore oggetto non valido - ".print_r($object,true), 100);
            }*/
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

    //Aggiunge una proprietà all'oggetto ed eventualmente la associa ad un campo db
    public function AddProp($prop = "", $initValue="",$dbBind="")
    {
        if ($prop != "" && $prop != null) {
            if (in_array($prop, array_keys($this->aProps))) {
                if($dbBind=="")
                {
                    if(isset($this->aDbBindings[$prop])) unset($this->aDbBindings[$prop]);
                }
                else $this->aDbBindings[$prop]=$dbBind;
                
            }
            else
            {
                $this->aProps[$prop]=$initValue;
                if($dbBind != "")
                {
                    $this->aDbBindings[$prop]=$dbBind;
                }
            }

            return true;
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
        $select = "SELECT DISTINCT " . static::$AA_DBTABLE_OBJECTS . ".id FROM " . static::$AA_DBTABLE_OBJECTS . " ";
        $join = "";
        $where = "";
        $group = " GROUP by " . static::$AA_DBTABLE_OBJECTS . ".id";
        $having = "";
        $order = "";

        //Verifica del parametro class
        if ($params['class'] == "" || !class_exists($params['class']) || $params['class'] == null) {
            $params['class'] = "AA_Object_V2";
            $where .= " WHERE " . static::$AA_DBTABLE_OBJECTS . ".class='AA_Object_V2' ";
        } else {
            $where .= " WHERE " . static::$AA_DBTABLE_OBJECTS . ".class='" . addslashes($params['class']) . "' ";
        }

        //Collegamento tabella dati
        if ($params['class']::AA_DBTABLE_DATA != "") {
            //$join .= " INNER JOIN " . $params['class']::AA_DBTABLE_DATA . " ON " . $params['class']::AA_DBTABLE_DATA . ".id in (" . static::$AA_DBTABLE_OBJECTS . ".id_data," . static::$AA_DBTABLE_OBJECTS . ".id_data_rev)";
            $join .= " INNER JOIN " . $params['class']::AA_DBTABLE_DATA . " ON " . $params['class']::AA_DBTABLE_DATA . ".id = ". static::$AA_DBTABLE_OBJECTS . ".id_data ";
        }

        //Parametro status non impostato o non valido
        if ($params['status'] == "" || !isset($params['status']) || $public || ($params['status'] & AA_Const::AA_STATUS_ALL) == 0) $params['status'] = AA_Const::AA_STATUS_PUBBLICATA;

        $userStruct = $user->GetStruct();

        if (!$public) {
            if ($params['gestiti'] != "" || ($params['status'] & (AA_Const::AA_STATUS_BOZZA + AA_Const::AA_STATUS_CESTINATA + AA_Const::AA_STATUS_REVISIONATA) > 0)) {
                //Visualizza solo quelle della sua struttura
                if ($userStruct->GetAssessorato(true) > 0) {
                    $params['id_assessorato'] = $userStruct->GetAssessorato(true);
                    //$where.=" AND ".static::$AA_DBTABLE_OBJECTS.".id_assessorato='".$userStruct->GetAssessorato(true)."'";    
                }

                if ($userStruct->GetDirezione(true) > 0) {
                    $params['id_direzione'] = $userStruct->GetDirezione(true);
                    //$where.=" AND ".static::$AA_DBTABLE_OBJECTS.".id_direzione='".$userStruct->GetDirezione(true)."'";    
                }

                if ($userStruct->GetServizio(true) > 0) {
                    $params['id_servizio'] = $userStruct->GetServizio(true);
                    //$where.=" AND ".static::$AA_DBTABLE_OBJECTS.".id_servizio='".$userStruct->GetDirezione(true)."'";    
                }
            }
        }

        //Filtra solo oggetti della RAS
        if ($userStruct->GetTipo() == 0) {
            $join .= " INNER JOIN " . AA_Const::AA_DBTABLE_ASSESSORATI . " ON " . static::$AA_DBTABLE_OBJECTS . ".id_assessorato=" . AA_Const::AA_DBTABLE_ASSESSORATI . ".id ";

            //RAS
            $where .= " AND " . AA_Const::AA_DBTABLE_ASSESSORATI . ".tipo = 0";
        }

        //solo oggetti dell'ente
        if ($userStruct->GetTipo() > 0) {
            $params['id_assessorato'] = $userStruct->GetAssessorato(true);
        }

        //filtro struttura
        if (isset($params['id_assessorato']) && $params['id_assessorato'] != "" && $params['id_assessorato'] > 0) {
            $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".id_assessorato = '" . addslashes($params['id_assessorato']) . "'";
        }

        if (isset($params['id_direzione']) && $params['id_direzione'] != "" && $params['id_direzione'] > 0) {
            $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".id_direzione = '" . addslashes($params['id_direzione']) . "'";
        }

        if (isset($params['id_servizio']) && $params['id_servizio'] != "" && $params['id_servizio'] > 0) {
            $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".id_servizio = '" . addslashes($params['id_servizio']) . "'";
        }
        //------------------------

        //Filtra in base allo stato della scheda
        if (!isset($params['id']) || $params['id'] == "")
        {
            $where .= " AND status ='" . $params['status'] . "' ";

            //filtra in base al nome
            if (isset($params['nome']) && $params['nome'] != "") {
                $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".nome like '%" . addslashes($params['nome']) . "%'";
            }

            //filtra in base alla descrizione
            if (isset($params['descrizione']) && $params['descrizione'] != "") {
                $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".descrizione like '%" . addslashes($params['descrizione']) . "%'";
            }
        }

        //filtra in base all'id(s)
        if (isset($params['id']) && $params['id'] != "") {
            $ids = array();
            preg_match("/([0-9]+\,*)+/", $params['id'], $ids);

            if (count($ids) > 0) {
                $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".id in (" . $ids[0] . ")";
            } else $where .= " AND " . static::$AA_DBTABLE_OBJECTS . ".id = '" . addslashes($params['id']) . "'";
        }

        //aggiunge i join
        if (isset($params['join']) && is_array($params['join'])) {
            foreach ((array)$params['join'] as $curJoin) {
                $join .= " " . $curJoin . " ";
            }
        }
        //-----------------------

        //aggiunge i where
        if (isset($params['where']) && is_array($params['where'])) {
            foreach ((array)$params['where'] as $curParam) {
                if ($where == "") $where = " WHERE " . $curParam;
                else
                {
                    if(strpos($curParam," AND ") !==false || strpos($curParam," OR ") !==false) $where .= "  " . $curParam;
                    else $where .= "  AND " . $curParam;    
                }
            }
        }
        //-----------------------

        //aggiunge i having
        if (isset($params['having']) && is_array($params['having'])) {
            foreach ((array)$params['having'] as $curParam) {
                if ($having == "") $having = " HAVING " . $curParam;
                else $having .= " AND " . $curParam;
            }
        }
        //-----------------------

        //aggiunge i group
        if (isset($params['group']) && is_array($params['group'])) {
            foreach ((array)$params['group'] as $curParam) {
                if ($group == "") $group = " GROUP BY " . $curParam;
                else $group .= ", " . $curParam;
            }
        }
        //-----------------------

        //aggiunge gli order
        if (isset($params['order']) && is_array($params['order'])) {
            foreach ((array)$params['order'] as $curParam) {
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
            AA_Log::Log(__METHOD__ . "(" . print_r($params, TRUE) . ") - Errore :" . $db->GetErrorMessage()." - ".$query, 100);

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
        if (isset($params['onlyCount']) && $params['onlyCount'] != "") return array(0 => $tot_count, array());

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
            AA_Log::Log(__METHOD__ . "(" . print_r($params, TRUE) . ") - Errore nella query:" . $db->GetErrorMessage()." - ".$query, 100);
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

    static public function UnsetVar($name="")
    {
        if (isset($_SESSION['SessionVars'][$name]) && $name != "") 
        {
            unset($_SESSION['SessionVars'][$name]);
        }
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

    static public function Set($name = "varName", $value = "", $parse=true)
    {
        if ($name != "" && $value != "") {
            if($parse) $var = json_decode($value);
            else $var=$value;
            if (is_string($value)) {
                if (json_last_error() === JSON_ERROR_NONE && $parse) {
                    $_SESSION['SessionVars'][$name] = serialize($var);
                    //AA_Log::Log(__METHOD__." - name:".$name." - value: ".print_r($var,true),100);
                    //AA_Log::Log(__METHOD__." - name:".$name." - value: ".$_SESSION['SessionVars'][$name],100);
                } else $_SESSION['SessionVars'][$name] = $value;
            } else {
                $_SESSION['SessionVars'][$name] = serialize($var);
                //AA_Log::Log(__METHOD__." - name:".$name." - value: ".print_r($var,true),100);
                //AA_Log::Log(__METHOD__." - name:".$name." - value: ".$_SESSION['SessionVars'][$name],100);
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
            $dir = sys_get_temp_dir(). DIRECTORY_SEPARATOR . "session_files";
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

    //public overlay
    static protected $sOverlay='<div id="AA_MainOverlay" class="AA_MainOverlay" style="display: block;">
        <div class="AA_MainOverlayContent">
            <img class="AA_Header_Logo" src="immagini/logo_ras.svg" alt="logo RAS" title="www.regione.sardegna.it">
            <h1><span>A</span>mministrazione <span>A</span>perta</h1>
            <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        </div>
    </div>';

    static protected $sManutention='<div id="AA_MainOverlay" class="AA_MainOverlay" style="display: block;">
        <div class="AA_MainOverlayContent">
            <img class="AA_Header_Logo" src="immagini/logo_ras.svg" alt="logo RAS" title="www.regione.sardegna.it">
            <h1><span>A</span>mministrazione <span>A</span>perta</h1>
            <div>
            <p style="color:#fff; font-size:32px; font-weight: bold;text-align:center">
            Sito in manutenzione.
            </p>
            <p style="color:#fff; font-size:24px;text-align:center">
            Il servizio verra ripristinato nel piu breve tempo possibile.
            </p>
            </div>
        </div>
    </div>';
    public static function GetManutention()
    {
        return static::$sManutention;
    }

    public static function GetOverlay()
    {
        return static::$sOverlay;
    }

    public static function SetOverlay($var="")
    {
        static::$sOverlay=$var;
    }
    //---------------------------

    //restituisce l'istanza unica
    static public function GetInstance($user = null)
    {
        if (self::$oInstance == null || !self::$oInstance->bValid) {
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

    //Restituisce l'url della cartella del modulo
    public function GetModulePathURL($id_module = "")
    {
        if (!$this->IsValid()) {
            return AA_Const::AA_WWW_ROOT;
        }

        $module = $this->GetModule($id_module);
        if ($module == null) {
            return AA_Const::AA_WWW_ROOT;
        } else {
            if(isset($module["path"]) && $module["path"] != "") return AA_Const::AA_PUBLIC_MODULES_PATH . "/" . $module['path'];
            return AA_Const::AA_PUBLIC_MODULES_PATH . "/" . $module['id_sidebar'] . "_" . $module['id'];
        }
    }

    //public services
    protected $aPublicServices=array();

    public static function RegisterPublicService($service="",$serviceFunc=null)
    {
        $platform=AA_Platform::GetInstance(AA_User::GetCurrentUser());
        if(!$platform->IsValid())
        {
            AA_Log::Log(__METHOD__." - Piattaforma non inizializzata o utente non valido: ",100);
            return false;
        }

        if(!empty($service) && is_callable($serviceFunc,true))
        {
            $platform->aPublicServices[$service]=$serviceFunc;
            AA_Log::Log(__METHOD__." - Servizio registrato correttamente: ".$service." - Funzione: ".print_r($serviceFunc,true),100);
            return true;
        }
        else
        {
            AA_Log::Log(__METHOD__." - Servizio non registrato: ".$service." - Funzione: ".print_r($serviceFunc,true),100);
            return false;
        }
    }
    public static function RunPublicService($service="")
    {
        $platform=AA_Platform::GetInstance(AA_User::GetCurrentUser());
        if(!$platform->IsValid())
        {
            AA_Log::Log(__METHOD__." - Piattaforma non inizializzata o utente non valido: ",100);
            return false;
        }

        if(!empty($platform->aPublicServices[$service]) && is_callable($platform->aPublicServices[$service])) return call_user_func($platform->aPublicServices[$service]);
        else
        {
            AA_Log::Log(__METHOD__." - Servizio non registrato o non funzione non definita: ".$service." - Funzione: ".print_r($platform->aPublicServices[$service],true),100);
            return false;
        }
    }

    public function IsPublicServiceRegistered($service="")
    {
        $platform=AA_Platform::GetInstance(AA_User::GetCurrentUser());
        if(!$platform->IsValid())
        {
            AA_Log::Log(__METHOD__." - Piattaforma non inizializzata o utente non valido: ",100);
            return false;
        }

        if(!empty($this->aPublicServices[$service]) && is_callable($this->aPublicServices[$service])) return true;
        return false;
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
            $query = "SELECT * from aa_platform_modules ORDER by ordine";
            if (!$db->Query($query)) {
                AA_Log::Log(__METHOD__ . " - errore: " . $db->GetErrorMessage(), 100);
                return;
            }

            if ($db->GetAffectedRows() > 0) {
                $userFlags = $this->oUser->GetFlags(true);

                foreach ($db->GetResultSet() as $curMod) 
                {
                    $flags = array();
                    if($curMod['flags'] !="")
                    {
                        $mod_flags = json_decode($curMod['flags'], true);
                        if (!is_array($mod_flags)) 
                        {
                            if (json_last_error() > 0) AA_Log::Log(__METHOD__ . " - id module: ".$curMod['id_modulo']." - module flags:" . print_r($mod_flags, true) . " - error: " . json_last_error(), 100);
                        } 
                        else 
                        {
                            $flags = array_keys($mod_flags);
                            //AA_Log::Log(__METHOD__." - module flags:".print_r($mod_flags,true),100);
                        }
                    }

                    $admins = explode(",", $curMod['admins']);
                    if (in_array($this->oUser->GetId(), $admins) || $this->oUser->IsSuperUser()) 
                    {
                        //Amministratori del modulo
                        $this->aModules[$curMod['id_modulo']] = $curMod;
                    } 
                    else 
                    {
                        //Utilizzatori del modulo
                        if ($curMod['enable'] == 1) 
                        {
                            if (sizeof($flags) == 0) 
                            {
                                //modulo pubblico
                                $this->aModules[$curMod['id_modulo']] = $curMod;
                            } 
                            else 
                            {
                                //Modulo a visibilità limitata
                                if (sizeof($userFlags) > 0) 
                                {
                                    foreach ($userFlags as $curFlag) 
                                    {
                                        if (in_array($curFlag, $flags)) $this->aModules[$curMod['id_modulo']] = $curMod;
                                    }
                                } 
                                else 
                                {
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

    //Restituisce la lista dei flag dei moduli registrati per l'utente loggato
    public function GetModulesFlags()
    {
        if (!$this->bValid) return array();

        if ($this->aModules == null) $this->LoadModules();

        $flags=array();

        foreach($this->aModules as $curMod)
        {
            if($curMod['flags'] != "" && $curMod['flags'] !="{}")
            {
                $flags=array_merge($flags,json_decode($curMod['flags'], true));
            }
        }

        return $flags;
    }

    //Restituisce la lista dei flag dei moduli registrati
    static public function GetAllModulesFlags()
    {
        $flags=array();
        $db=new AA_Database();
        $query="SELECT flags FROM aa_platform_modules WHERE enable=1";
        if(!$db->Query($query))
        {
            AA_Log::Log(__METHOD__." - Errore: ".$db->GetErrorMessage(),100);
            return $flags;
        }

        if($db->GetAffectedRows()>0)
        {
            $rs=$db->GetResultSet();
            foreach($rs as $curFlag)
            {
                if($curFlag !="")
                {
                    $moduleFlag=json_decode($curFlag['flags'],true);
                    if(is_array($moduleFlag) && sizeof($moduleFlag)>0)
                    {
                        $flags=array_merge($moduleFlag,$flags);
                    }
                    else
                    {
                        AA_Log::Log(__METHOD__." - Errore nel parsing del flag: ".$curFlag['flags'],100);
                    }    
                }
            }
        }

        return $flags;
    }

    //Restituisce la lista dei flag legacy
    static public function GetLegacyFlags()
    {
        $result=array(
            "accessi"=>"RIA",
            "admin_accessi"=>"RIA(adm)",
            "processi"=>"Mappatura processi",
            "processi_admin"=>"Mappatura processi(adm)",
            "incarichi"=>"Gestione incarichi",
            "incarichi_titolari"=>"Gestione Incarichi(adm)"
        );

        return $result;
    }

    //Restituisce l'utente corrente
    public function GetCurrentUser()
    {
        if ($this->bValid) return $this->oUser;
        else return AA_User::GetCurrentUser();
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
