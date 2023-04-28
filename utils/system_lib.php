<?php
include_once "system_core.php";
include_once "system_ui.php";
include_once "system_ui.legacy.php";

//STRUCT_TREE Classe
//Albero di oggetti AA_Struct
class AA_TStruct
{
    protected $root = array();
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
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());
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
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());
        $module="";
        if(isset($_REQUEST['module'])) $module = $_REQUEST['module'];
        $wnd = new AA_GenericStructDlg("AA_SystemStructDlg", "Organigramma", $_REQUEST, "", $module, $this->oUser);

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
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());
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
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());
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
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());

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
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());

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

            $itemSelected="";
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

    //Caricamento multiplo
    const AA_UI_TASK_ADDNEWMULTI_DLG = "GetGenericAddNewMultiDlg";
    //--------------------------------------

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
        if (isset ($params['cestinate']) && $params['cestinate'] == 1) {
            $parametri['status'] |= AA_Const::AA_STATUS_CESTINATA;
        }

        if (isset($params['revisionate']) && $params['revisionate'] == 1) {
            $parametri['status'] |= AA_Const::AA_STATUS_REVISIONATA;
        }

        if (isset($params['page']) && $params['page'] > 0) $parametri['from'] = ($params['page'] - 1) * $params['count'];
        if (isset($params['id_assessorato']) && $params['id_assessorato'] != "") $parametri['id_assessorato'] = $params['id_assessorato'];
        if (isset($params['id_direzione']) && $params['id_direzione'] != "") $parametri['id_direzione'] = $params['id_direzione'];
        if (isset($params['id_servizio']) && $params['id_servizio'] != "") $parametri['id_servizio'] = $params['id_servizio'];
        if (isset($params['id']) && $params['id'] != "") $parametri['id'] = $params['id'];
        if (isset($params['nome']) && $params['nome'] != "") $parametri['nome'] = $params['nome'];

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

            $result = array(
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
                $templateData[] = $this->$customTemplateDataFunction($result, $object);
            } else if (function_exists($customTemplateDataFunction)) {
                $templateData[] = $customTemplateDataFunction($result, $object);
            } else {
                $templateData[] = $result;
            }
        }

        //AA_Log::Log(__METHOD__." - numcount: ".$data[0],100);
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
                $params['count'] = 10;
                $data = $this->GetDataSectionPubblicate_List($params);
                if ($data[0] > 0) $objectData = $data[1];
                break;
            case static::AA_UI_PREFIX . "_" . static::AA_UI_BOZZE_LISTBOX:
                $params['count'] = 10;
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
        $id=static::AA_UI_PREFIX . "_Navbar_Link_Back_Content_Box_".uniqid(time());
        $navbar =  new AA_JSON_Template_Template(
            $id,
            array(
                "type" => "clean",
                "css" => "AA_NavbarEventListener",
                "module_id" => $this->GetId(),
                "refresh_view" => $refresh_view,
                "tooltip" => "Fai click per tornare alla lista",
                "template" => "<div class='AA_navbar_link_box_left #class#'><a class='" .$id."' onClick='AA_MainApp.utils.callHandler(\"goBack\",null,\"" . $this->id . "\")'><span class='#icon#' style='margin-right: .5em'></span><span>#label#</span></a></div>",
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

            if (isset($params['revisionate']) && $params['revisionate'] == 1) {
                $sectionName .= " revisionate";
                $content->HideReassign();
                $content->ViewPublish();
                $content->SetPublishHandlerParams(array("task" => static::AA_UI_TASK_PUBLISH_DLG));
            }

            if (isset($params['cestinate']) && $params['cestinate'] == 0) {
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
        if (isset($params['cestinate']) && $params['cestinate'] == 1) {
            $parametri['status'] |= AA_Const::AA_STATUS_CESTINATA;
        }

        if (isset($params['page']) && $params['page'] > 0) $parametri['from'] = ($params['page'] - 1) * $params['count'];
        if (isset($params['id_assessorato']) && $params['id_assessorato'] != "") $parametri['id_assessorato'] = $params['id_assessorato'];
        if (isset($params['id_direzione']) && $params['id_direzione'] != "") $parametri['id_direzione'] = $params['id_direzione'];
        if (isset($params['id_servizio']) && $params['id_servizio'] != "") $parametri['id_servizio'] = $params['id_servizio'];
        if (isset($params['id']) && $params['id'] != "") $parametri['id'] = $params['id'];
        if (isset($params['nome']) && $params['nome'] != "") $parametri['nome'] = $params['nome'];

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

            $newData = array(
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
                $templateData[] = $this->$customTemplateDataFunction($newData, $object);
            } else if (function_exists($customTemplateDataFunction)) {
                $templateData[] = $customTemplateDataFunction($newData, $object);
            } else {
                $templateData[] = $newData;
            }
        }

        return array($data[0], $templateData);
    }

    //Restituisce la lista delle bozze (dati - da specializzare)
    public function GetDataSectionBozze_List($params = array())
    {
        return $this->GetDataGenericSectionBozze_List($params);
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
        $content->EnablePaging();
        $content->SetPagerItemForPage(10);
        $content->EnableFiltering();
        $content->EnableAddNew();
        if(isset($params['enableAddNewMultiFromCsv']) && $params['enableAddNewMultiFromCsv'] == true)
        {
            $content->EnableAddNewMulti();
        }
        if(isset($params['enableAddNewMultiFromCsvDlgTask']) && $params['enableAddNewMultiFromCsvDlgTask'] != "")
        {
            $content->SetAddNewMultiDlgTask($params['enableAddNewMultiFromCsvDlgTask']);
        }
        else
        {
            $content->SetAddNewMultiDlgTask(static::AA_UI_TASK_ADDNEWMULTI_DLG);
        }
        

        $content->SetAddNewDlgTask(static::AA_UI_TASK_ADDNEW_DLG);
        $content->SetFilterDlgTask(static::AA_UI_TASK_BOZZE_FILTER_DLG);
        $content->ViewExportFunctions();

        $content->SetSectionName(static::AA_UI_SECTION_BOZZE_NAME);

        $content->ViewDetail();

        if (!isset($params['cestinate']) || $params['cestinate'] == 0) {
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

        //AA_Log::Log(__METHOD__ . " - oggetti trovati: ".$contentData[0]." - oggetti nell'array: ".sizeof($contentData[1]), 100);

        $content->SetContentBoxData($contentData[1]);
        $content->SetPagerItemCount($contentData[0]);
        $content->EnableMultiSelect();
        $content->EnableSelect();

        return $content;
    }

    //Template sezione bozze (da specializzare)
    public function TemplateSection_Bozze($params = array())
    {
        $content = $this->TemplateGenericSection_Bozze($params);
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
            if (method_exists($this, $curTab['template']) && $curTab['template'] != "" && is_string($curTab['template'])) {
                $multiview->addCell($this->{$curTab['template']}($object));
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
        AA_Log::Log(__METHOD__ . "() - task: ".$this->GetName());

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
                if (isset($options['showAll']) && $options['showAll'] == 1) {
                    if ($userStruct->GetTipo() <= 0) $struct = AA_Struct::GetStruct(0, 0, 0, $userStruct->GetTipo()); //RAS
                    else $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), 0, 0, $userStruct->GetTipo()); //Altri
                }

                if (isset($options['showAllDir']) && $options['showAllDir'] == 1) {
                    $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), 0, 0, $userStruct->GetTipo());
                }

                if (isset($options['showAllServ']) && $options['showAllServ'] == 1) {
                    $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), $userStruct->GetDirezione(true), 0, $userStruct->GetTipo());
                }
            }
            if (!($struct instanceof AA_Struct)) $struct = AA_Struct::GetStruct($userStruct->GetAssessorato(true), $userStruct->GetDirezione(true), $userStruct->GetServizio(true), $userStruct->GetTipo());
        } else {
            $struct = array(array("id" => "root", "value" => "strutture"));
        }

        //Struttura
        $filterLevel = 4;

        if (isset($options['hideServices'])) $filterLevel = 3;

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
        $toolbar->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 15)));

        //mostra/nascondi strutture soppresse
        $toolbar->addCol(new AA_JSON_Template_Generic($this->id . "_Switch_Supressed", array("view" => "switch", "width" => 350, "label" => "Strutture soppresse:", "labelWidth" => 150, "onLabel" => "visibili", "offLabel" => "nascoste", "tooltip" => "mostra/nascondi le strutture soppresse")));

        $toolbar->addCol(new AA_JSON_Template_Generic());
        $toolbar->addCol($this->applyButton);
        $toolbar->addCol(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 15)));
        $this->body->AddRow($toolbar);
        $this->body->AddRow(new AA_JSON_Template_Generic("", array("view" => "spacer", "height" => 10)));
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

            if ($this->filtered || $this->enableAddNewMulti || $this->enableAddNew || $this->saveAsPdfView || $this->saveAsCsvView || $this->trashView || $this->reassignView || $this->publishView || $this->resumeView || $this->detailView) {
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
                        "css"=>"webix_primary",
                        "tooltip" => "Aggiungi una nuova bozza",
                        "click" => $addnewClickAction
                    ));

                    $toolbar->addElement($addnew_btn);
                    $toolbar_spacer = true;
                }

                //Aggiunta elementi da csv
                if ($this->enableAddNewMulti && $this->addNewMultiDlgTask != "") {
                    if ($toolbar_spacer) $toolbar->addElement(new AA_JSON_Template_Generic("", array("view" => "spacer", "width" => 10)));
                    $toolbar_spacer = true;

                    $addnewMultiClickAction = "try{module=AA_MainApp.getModule('" . $this->module . "'); if(module.isValid()){module.dlg({task:'" . $this->addNewMultiDlgTask . "',module:'" . $this->module . "'})}}catch(msg){console.error(msg)}";

                    $addnewmulti_btn = new AA_JSON_Template_Generic($this->id . "_AddNewMulti_btn", array(
                        "view" => "button",
                        "align" => "right",
                        "type" => "icon",
                        "icon" => "mdi mdi-plus-box-multiple",
                        "label" => "da CSV",
                        "width" => 110,
                        "tooltip" => "Caricamento multiplo da file CSV",
                        "click" => $addnewMultiClickAction
                    ));

                    $toolbar->addElement($addnewmulti_btn);
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

    //Gestione aggiunta multipla
    protected $enableAddNewMulti = false;
    public function EnableAddNewMulti($bVal = true)
    {
        $this->enableAddNewMulti = $bVal;
    }
    
    protected $addNewMultiDlgTask = "";
    public function SetAddNewMultiDlgTask($task = "")
    {
        $this->addNewMultiDlgTask = $task;
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
    protected $saveAsPdfHandlerParams = array();
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
    protected $saveAsCsvHandlerParams = array();
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

//template reset pwd
class AA_GenericResetPwdDlg extends AA_GenericFormDlg
{
    public function ___construct($id = "", $title = "", $formData = array(), $resetData = array(), $applyActions = "", $save_formdata_id = "")
    {
        parent::__construct($id, $title);

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

        $this->AddTextField("email_verification_code","OTP",array("required"=>true,"bottomLabel"=>"Inserisci il codice OTP ricevut via email."));
        $this->SetSaveTask("User");
    }
}

