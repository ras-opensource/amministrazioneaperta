<?php
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
