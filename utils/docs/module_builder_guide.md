# Guida alla Costruzione di un Modulo - Amministrazione Aperta

## Indice

1. [Struttura File Minimale](#1-struttura-file-minimale)
2. [Struttura della Classe Modulo](#2-struttura-della-classe-modulo)
3. [Tipi di Task](#3-tipi-di-task)
4. [Esempi Dettagliati dei Task](#4-esempi-dettagliati-dei-task)
5. [Creazione Classe Oggetto](#5-creazione-classe-oggetto)
6. [Permessi Utente](#6-permessi-utente)
7. [Template UI](#7-template-ui)
8. [Sezioni del Modulo](#8-sezioni-del-modulo)
9. [Workflow degli Oggetti](#9-workflow-degli-oggetti)
10. [JavaScript Client-Side](#10-javascript-client-side)
11. [Personalizzazione Dati Sezioni](#11-personalizzazione-dati-sezioni-customfilter-e-customdatatemplate)
12. [Template delle Sezioni](#12-template-delle-sezioni-templatesection_)
13. [Riepilogo](#13-riepilogo-catena-di-personalizzazione)

---

## 1. Struttura File Minimale

| File | Obbligatorio | Descrizione |
|------|--------------|-------------|
| `lib.php` | **Sì** | Classe principale del modulo |
| `taskmanager.php` | **Sì** | Entry point per AJAX |
| `config.php` | **Sì** | Configurazione (include base) |
| `lib.js.php` | Consigliato | JavaScript client-side |
| `default.css` | Opzionale | Stili CSS |

### 1.1 Config.php

```php
<?php
include_once "../../config.php";
include_once "../../system_lib.php";
?>
```

### 1.2 Taskmanager.php

```php
<?php
include_once("lib.php");

session_start();

$user = AA_User::GetCurrentUser();

// Verifica autenticazione
if($user->IsGuest()) {
    die("<status id='status'>-2</status><error id='error'>Credenziali non impostate o sessione scaduta.</error>");
}

// Verifica task
if($_REQUEST['task'] == "") {
    die("<status id='status'>-1</status><error id='error'>parametro task non impostato.</error>");
}

$task = $_REQUEST['task'];

// Istanzia il modulo
$module = new AA_MioModuloModule($user);
$taskManager = $module->GetTaskManager();

// Esegui il task
if($taskManager->IsManaged($task)) {
    if(!$taskManager->RunTask($task)) {
        AA_Log::Log("task manager - task: " . $task . " - " . $taskManager->GetTaskError($task), 100, false, true);
    }
    die($taskManager->GetTaskLog($task));
}

die("<status id='status'>-1</status><error id='error'>Task non gestito: " . $task . ".</error>");
?>
```

---

## 2. Struttura della Classe Modulo

### 2.1 Struttura Base

```php
<?php
include_once "config.php";

/**
 * Costanti specifiche del modulo
 */
class AA_MioModulo_Const extends AA_Const
{
    // Flag utente per il modulo
    const AA_USER_FLAG_MIOMODULO = "miomodulo";
    
    // Table lookup
    const AA_DBTABLE_TIPOLOGIE = 'aa_mio_modulo_tipologie';
    
    /**
     * Restituisce la lista delle tipologie
     * @return array
     */
    public static function GetListaTipologie($bSimpleArray = false)
    {
        $options = array();
        $db = new AA_Database();
        $query = "SELECT id, descrizione FROM " . self::AA_DBTABLE_TIPOLOGIE . " ORDER BY descrizione";
        if ($db->Query($query)) {
            $rs = $db->GetResultSet();
            foreach ($rs as $row) {
                if (!$bSimpleArray) {
                    $options[] = array("id" => $row['id'], "value" => $row['descrizione']);
                } else {
                    $options[$row['id']] = $row['descrizione'];
                }
            }
        }
        return $options;
    }
}

/**
 * Classe principale del modulo
 */
class AA_MioModuloModule extends AA_GenericModule
{
    // Costanti UI
    const AA_UI_PREFIX = "AA_MioModulo";
    const AA_ID_MODULE = "AA_MODULE_MIOMODULO";
    const AA_UI_MODULE_MAIN_BOX = "AA_MioModulo_module_layout";
    const AA_MODULE_OBJECTS_CLASS = "AA_MioOggetto";
    
    // Costanti sezioni
    const AA_ID_SECTION_DESKTOP = "mio_desktop";
    const AA_UI_SECTION_DESKTOP_NAME = "Cruscotto";
    const AA_UI_SECTION_DESKTOP_BOX = "MioModulo_Desktop_Content_Box";
    const AA_UI_SECTION_DESKTOP_ICON = "mdi mdi-desktop-classic";
    
    // Costanti task standard
    const AA_UI_TASK_PUBBLICATE_FILTER_DLG = "GetMioModuloPubblicateFilterDlg";
    const AA_UI_TASK_BOZZE_FILTER_DLG = "GetMioModuloBozzeFilterDlg";
    const AA_UI_TASK_ADDNEW_DLG = "GetMioModuloAddNewDlg";
    const AA_UI_TASK_MODIFY_DLG = "GetMioModuloModifyDlg";
    const AA_UI_TASK_DELETE_DLG = "GetMioModuloDeleteDlg";
    const AA_UI_TASK_TRASH_DLG = "GetMioModuloTrashDlg";
    const AA_UI_TASK_RESUME_DLG = "GetMioModuloResumeDlg";
    const AA_UI_TASK_PUBLISH_DLG = "GetMioModuloPublishDlg";
    
    // Istanza singleton
    protected static $oInstance = null;
    
    /**
     * Restituisce l'istanza corrente del modulo
     */
    public static function GetInstance($user = null)
    {
        if (self::$oInstance == null) {
            self::$oInstance = new AA_MioModuloModule($user);
        }
        return self::$oInstance;
    }
    
    /**
     * Costruttore
     */
    public function __construct($user = null, $bDefaultSections = true)
    {
        if (!($user instanceof AA_User)) {
            $user = AA_User::GetCurrentUser();
        }
        
        parent::__construct($user, $bDefaultSections);
        
        // Registrazione dei task
        $taskManager = $this->GetTaskManager();
        
        // Task standard per la gestione degli oggetti
        $taskManager->RegisterTask("GetMioModuloPubblicateFilterDlg");
        $taskManager->RegisterTask("GetMioModuloBozzeFilterDlg");
        $taskManager->RegisterTask("GetMioModuloAddNewDlg");
        $taskManager->RegisterTask("GetMioModuloModifyDlg");
        $taskManager->RegisterTask("GetMioModuloDeleteDlg");
        $taskManager->RegisterTask("GetMioModuloTrashDlg");
        $taskManager->RegisterTask("GetMioModuloResumeDlg");
        $taskManager->RegisterTask("GetMioModuloPublishDlg");
        
        // Task CRUD
        $taskManager->RegisterTask("AddNewMioOggetto");
        $taskManager->RegisterTask("UpdateMioOggetto");
        $taskManager->RegisterTask("DeleteMioOggetto");
        $taskManager->RegisterTask("PublishMioOggetto");
        $taskManager->RegisterTask("TrashMioOggetto");
        $taskManager->RegisterTask("ResumeMioOggetto");
        
        // Task specifici
        $taskManager->RegisterTask("GetMioModuloTipologie");
        $taskManager->RegisterTask("ExportMioModuloCsv");
        
        // Sezioni personalizzate (se $bDefaultSections = false)
        $this->SetupCustomSections();
    }
    
    /**
     * Setup sezioni personalizzate
     */
    protected function SetupCustomSections()
    {
        // Desktop
        $desktop = new AA_GenericModuleSection(
            static::AA_ID_SECTION_DESKTOP,
            static::AA_UI_SECTION_DESKTOP_NAME,
            true,
            static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_DESKTOP_BOX,
            $this->GetId(),
            true,  // default
            true,  // visible
            false, // detail
            true,  // enabled
            static::AA_UI_SECTION_DESKTOP_ICON,
            "TemplateSection_Desktop"
        );
        $desktop->SetNavbarTemplate($this->TemplateGenericNavbar_Void(1, true)->toArray());
        $this->AddSection($desktop);
    }
}
?>
```

---

## 3. Tipi di Task

### 3.1 Categorie di Task

| Categoria | Prefisso | Descrizione |
|-----------|----------|-------------|
| **Dialog** | `Get*Dlg` | Restituisce HTML per finestre dialog |
| **AddNew** | `AddNew*` | Inserimento nuovi oggetti |
| **Update** | `Update*` | Aggiornamento oggetti esistenti |
| **Delete** | `Delete*` | Eliminazione oggetti |
| **Publish** | `Publish*` | Pubblicazione oggetti |
| **Trash** | `Trash*` | Cestinazione oggetti |
| **Resume** | `Resume*` | Ripristino oggetti |
| **Get** | `Get*` | Recupero dati (lookup, liste) |
| **Export** | `Export*` | Esportazione dati |

### 3.2 Convenzioni di Nomenclatura

- **Nome metodo**: `Task_NomeTask` (es. `Task_GetMioModuloAddNewDlg`)
- **Registrazione**: `$taskManager->RegisterTask("NomeTask")` → cerca automaticamente `Task_NomeTask()`
- **Parametro**: Oggetto `AA_GenericModuleTask`

### 3.3 Metodi di AA_GenericModuleTask

I metodi Task ricevono un oggetto `AA_GenericModuleTask` che permette di restituire i risultati:

| Metodo | Descrizione |
|--------|-------------|
| `$task->SetContent($content, $json, $encode)` | Imposta il contenuto della risposta |
| `$task->SetStatus($status)` | Imposta lo stato (0=successo, -1=errore, -2=...) |
| `$task->SetError($error, $json, $encode)` | Imposta un messaggio di errore |
| `$task->SetStatusAction($action, $params, $json_encode)` | Azione post-task (es. refresh) |
| `$task->GetName()` | Restituisce il nome del task |

**Esempio:**

```php
public function Task_MioTask($task)
{
    // Leggi parametri
    $params = $_REQUEST;
    
    // Esegui operazione
    if ($operazioneRiuscita) {
        $task->SetStatus(0);
        $task->SetStatusAction("refreshCurSection", null, true);
        return true;
    } else {
        $task->SetError("Messaggio di errore");
        $task->SetStatus(-1);
        return false;
    }
}
```

---

## 4. Esempi Dettagliati dei Task

### 4.1 Task Dialog - Aggiungi Nuovo

```php
/**
 * Task per la finestra di dialogo di aggiunta nuovo oggetto
 */
public function Task_GetMioModuloAddNewDlg($task)
{
    AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
    
    // Verifica permessi
    if (!$this->oUser->HasFlag(AA_MioModulo_Const::AA_USER_FLAG_MIOMODULO)) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi", false);
        return false;
    }
    
    // Genera e restituisce il template del dialog
    $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
    $task->SetContent($this->Template_GetMioModuloAddNewDlg(), true);
    return true;
}
```

### 4.2 Task Dialog - Modifica

```php
/**
 * Task per la finestra di dialogo di modifica oggetto
 */
public function Task_GetMioModuloModifyDlg($task)
{
    AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
    
    // Verifica ID
    if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("Identificativo oggetto non valido.", false);
        return false;
    }
    
    // Verifica permessi
    if (!$this->oUser->HasFlag(AA_MioModulo_Const::AA_USER_FLAG_MIOMODULO)) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("L'utente corrente non ha i permessi per modificare l'elemento", false);
        return false;
    }
    
    // Carica l'oggetto
    $object = new AA_MioOggetto();
    if (!$object->Load($_REQUEST['id'], $this->oUser)) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("Oggetto non trovato o non accessibile.", false);
        return false;
    }
    
    // Genera il template con i dati dell'oggetto
    $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
    $task->SetContent($this->Template_GetMioModuloModifyDlg($object), true);
    return true;
}
```

### 4.3 Task Dialog - Conferma Eliminazione

```php
/**
 * Task per la finestra di dialogo di conferma eliminazione
 */
public function Task_GetMioModuloDeleteDlg($task)
{
    if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("Identificativo oggetto non valido.", false);
        return false;
    }
    
    if (!$this->oUser->HasFlag(AA_MioModulo_Const::AA_USER_FLAG_MIOMODULO)) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("L'utente corrente non ha i permessi per eliminare l'elemento", false);
        return false;
    }
    
    $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
    $task->SetContent($this->Template_GetMioModuloDeleteDlg($_REQUEST['id']), true);
    return true;
}
```

### 4.4 Task - Inserimento Nuovo Oggetto

```php
/**
 * Task per aggiungere un nuovo oggetto
 */
public function Task_AddNewMioOggetto($task)
{
    AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
    
    // Verifica permessi
    if (!$this->oUser->HasFlag(AA_MioModulo_Const::AA_USER_FLAG_MIOMODULO)) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("L'utente corrente non ha i permessi per aggiungere nuovi elementi", false);
        return false;
    }
    
    // Validazione campi obbligatori
    if (empty($_REQUEST['nome'])) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("E' necessario specificare il nome.", false);
        return false;
    }
    
    // Utilizza il metodo generico della classe base
    return $this->Task_GenericAddNew($task, $_REQUEST);
}
```

### 4.5 Task - Aggiornamento Oggetto

```php
/**
 * Task per aggiornare un oggetto esistente
 */
public function Task_UpdateMioOggetto($task)
{
    AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
    
    // Verifica permessi
    if (!$this->oUser->HasFlag(AA_MioModulo_Const::AA_USER_FLAG_MIOMODULO)) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("L'utente corrente non ha i permessi per modificare l'elemento", false);
        return false;
    }
    
    // Validazione
    if (!isset($_REQUEST['id']) || $_REQUEST['id'] <= 0) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("Identificativo oggetto non valido.", false);
        return false;
    }
    
    // Conversione campi numerici (italiano -> inglese)
    if (isset($_REQUEST['importo']) && $_REQUEST['importo'] != "") {
        $_REQUEST['importo'] = str_replace(",", ".", str_replace(".", "", $_REQUEST['importo']));
    }
    
    // Utilizza il metodo generico
    return $this->Task_GenericUpdateObject($task, $_REQUEST, true);
}
```

### 4.6 Task - Eliminazione Oggetto

```php
/**
 * Task per eliminare un oggetto
 */
public function Task_DeleteMioOggetto($task)
{
    AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
    
    if (!$this->oUser->HasFlag(AA_MioModulo_Const::AA_USER_FLAG_MIOMODULO)) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("L'utente corrente non ha i permessi per eliminare l'elemento", false);
        return false;
    }
    
    return $this->Task_GenericDeleteObject($task, $_REQUEST);
}
```

### 4.7 Task - Pubblicazione Oggetto

```php
/**
 * Task per pubblicare un oggetto
 */
public function Task_PublishMioOggetto($task)
{
    AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
    
    if (!$this->oUser->HasFlag(AA_MioModulo_Const::AA_USER_FLAG_MIOMODULO)) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("L'utente corrente non ha i permessi per pubblicare l'elemento", false);
        return false;
    }
    
    return $this->Task_GenericPublishObject($task, $_REQUEST);
}
```

### 4.8 Task - Cestinazione Oggetto

```php
/**
 * Task per cestinare un oggetto
 */
public function Task_TrashMioOggetto($task)
{
    AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
    
    if (!$this->oUser->HasFlag(AA_MioModulo_Const::AA_USER_FLAG_MIOMODULO)) {
        $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
        $task->SetError("L'utente corrente non ha i permessi per cestinare l'elemento", false);
        return false;
    }
    
    return $this->Task_GenericTrashObject($task, $_REQUEST);
}
```

### 4.9 Task - Recupero Dati Lookup

```php
/**
 * Task per recuperare la lista delle tipologie
 */
public function Task_GetMioModuloTipologie($task)
{
    $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
    
    $tipologie = AA_MioModulo_Const::GetListaTipologie();
    
    // Restituisce in formato JSON
    $task->SetContent(json_encode($tipologie), false); // false = non è già codificato
    return true;
}
```

### 4.10 Task - Esportazione CSV

```php
/**
 * Task per esportare i dati in formato CSV
 */
public function Task_ExportMioModuloCsv($task)
{
    AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
    
    // Recupera i dati
    $params = array("status" => AA_Const::AA_STATUS_PUBBLICATA);
    $data = AA_MioOggetto::Search($params, $this->oUser);
    
    // Costruisci il CSV
    $csv = "ID;Nome;Descrizione;Data Creazione\n";
    foreach ($data[1] as $object) {
        $csv .= $object->GetId() . ";";
        $csv .= $object->GetName() . ";";
        $csv .= $object->GetDescr() . ";";
        $csv .= $object->GetAggiornamento() . "\n";
    }
    
    $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
    $task->SetContent($csv, false);
    return true;
}
```

---

## 5. Creazione Classe Oggetto

### 5.1 Struttura Base

```php
<?php
include_once("config.php");
include_once(AA_Const::AA_SYSTEM_CORE_PATH . "/AA_Object_V2.php");

/**
 * Classe per la gestione degli oggetti del modulo
 */
class AA_MioOggetto extends AA_Object_V2
{
    // Tabella database
    static protected $AA_DBTABLE_OBJECTS = "aa_mio_oggetto";
    
    // Costanti proprietà
    const PROP_TIPOLOGIA = "tipologia";
    const PROP_COMUNE = "comune";
    const PROP_INDIRIZZO = "indirizzo";
    const PROP_DESCRIZIONE = "descrizione";
    
    // Costruttore
    public function __construct()
    {
        parent::__construct();
        
        // Definisci le proprietà dell'oggetto
        $this->DefineObject(
            " Mio Oggetto",      // nome oggetto
            "MioOggetto",        // nome classe
            "aa_mio_oggetto",   // tabella
            array(               // campi
                "id" => array("type" => AA_Const::AA_FIELD_INT, "key" => true),
                "nome" => array("type" => AA_Const::AA_FIELD_STRING, "size" => 255, "required" => true),
                "descrizione" => array("type" => AA_Const::AA_FIELD_TEXT),
                "tipologia" => array("type" => AA_Const::AA_FIELD_INT),
                "comune" => array("type" => AA_Const::AA_FIELD_STRING, "size" => 10),
                "indirizzo" => array("type" => AA_Const::AA_FIELD_STRING, "size" => 255),
                "importo" => array("type" => AA_Const::AA_FIELD_FLOAT),
                "data_inizio" => array("type" => AA_Const::AA_FIELD_DATE),
                "data_fine" => array("type" => AA_Const::AA_FIELD_DATE),
            ),
            array(               // relazioni
                "tipologia" => array("table" => "aa_mio_modulo_tipologie", "key" => "id", "display" => "descrizione"),
                "comune" => array("table" => "aa_mio_modulo_comuni", "key" => "codice", "display" => "denominazione"),
            )
        );
        
        // Abilita revisioni (opzionale)
        $this->EnableRevision(true);
    }
    
    /**
     * Validazione dei dati
     */
    public function Validate($deep = true)
    {
        $errors = parent::Validate($deep);
        
        // Validazione nome
        if (empty($this->GetProp("nome"))) {
            $errors[] = "Il campo 'Nome' è obbligatorio";
        }
        
        // Validazione importo
        $importo = $this->GetProp("importo");
        if ($importo !== null && $importo < 0) {
            $errors[] = "L'importo non può essere negativo";
        }
        
        // Validazione date
        $data_inizio = $this->GetProp("data_inizio");
        $data_fine = $this->GetProp("data_fine");
        if ($data_inizio && $data_fine && $data_inizio > $data_fine) {
            $errors[] = "La data di inizio non può essere successiva alla data di fine";
        }
        
        return $errors;
    }
    
    /**
     * Restituisce il nome visualizzato
     */
    public function GetDisplayName()
    {
        $name = $this->GetName();
        if (empty($name)) {
            $name = "Oggetto " . $this->GetId();
        }
        return $name;
    }
    
    /**
     * Esporta in formato CSV
     */
    public function ToCsv($separator = ";")
    {
        $csv = "";
        $csv .= $this->GetId() . $separator;
        $csv .= $this->GetName() . $separator;
        $csv .= $this->GetDescr() . $separator;
        $csv .= $this->GetProp("tipologia") . $separator;
        $csv .= $this->GetProp("indirizzo") . $separator;
        $csv .= $this->GetAggiornamento();
        return $csv;
    }
    
    /**
     * Ricerca oggetti
     */
    public static function Search($params = array(), $user = null)
    {
        // Implementa la logica di ricerca
        // Restituisce array(totali, array oggetti)
        return parent::Search($params, $user);
    }
}
?>
```

### 5.2 Metodi Get/Set Proprietà

```php
// Get proprietà
$value = $object->GetProp("nome");
$value = $object->GetNome();  // metodo generato automaticamente

// Set proprietà
$object->SetProp("nome", " valore");
$object->SetNome("valore");   // metodo generato automaticamente
```

---

## 6. Permessi Utente

### 6.1 Flag Utente

I flag utente sono definiti nelle costanti del modulo e verificano l'appartenenza a gruppi o ruoli:

```php
// Nella classe AA_MioModulo_Const
const AA_USER_FLAG_MIOMODULO = "miomodulo";

// Verifica nel task
if (!$this->oUser->HasFlag(AA_MioModulo_Const::AA_USER_FLAG_MIOMODULO)) {
    $task->SetStatus(AA_GenericTask::AA_STATUS_FAILED);
    $task->SetError("L'utente corrente non ha i permessi necessari", false);
    return false;
}
```

### 6.2 Permessi Oggetto

Oltre ai flag utente, ogni oggetto può avere permessi specifici basati sulle capability:

```php
// Verifica capability sull'oggetto
$userCaps = $object->GetUserCaps($this->oUser);

// Costanti permessi (in AA_Const)
const AA_PERMS_READ = 1;
const AA_PERMS_WRITE = 2;
const AA_PERMS_PUBLISH = 4;
const AA_PERMS_DELETE = 8;

// Verifica permesso di lettura
if (($userCaps & AA_Const::AA_PERMS_READ) == 0) {
    // Accesso negato
}

// Verifica permesso di scrittura
if (($userCaps & AA_Const::AA_PERMS_WRITE) == 0) {
    // Non può modificare
}
```

### 6.3 Assegnazione Flag Utente

I flag vengono assegnati nella tabella utenti del database o tramite la gestione utenti dell'applicazione.

---

## 7. Template UI

### 7.1 Template Dialog - Struttura Base

```php
/**
 * Template per il dialog di inserimento nuovo oggetto
 */
public function Template_GetMioModuloAddNewDlg()
{
    // Costruisci l'array del template Webix
    $template = array(
        "view" => "window",
        "id" => "MioModulo_AddNew_Wnd",
        "width" => 600,
        "height" => 500,
        "modal" => true,
        "move" => true,
        "head" => array(
            "view" => "toolbar",
            "cols" => array(
                array("view" => "label", "label" => "Nuovo Oggetto", "align" => "center"),
                array("view" => "icon", "icon" => "wxi-close", "align" => "right", 
                      "click" => "$$('MioModulo_AddNew_Wnd').close();")
            )
        ),
        "body" => array(
            "view" => "form",
            "id" => "MioModulo_AddNew_Form",
            "elements" => array(
                array("view" => "text", "name" => "nome", "label" => "Nome", "required" => true),
                array("view" => "text", "name" => "descrizione", "label" => "Descrizione", "height" => 100),
                array("view" => "combo", "name" => "tipologia", "label" => "Tipologia", 
                      "options" => "server", "url" => "data.php?task=GetMioModuloTipologie"),
                array("view" => "text", "name" => "indirizzo", "label" => "Indirizzo"),
                array("view" => "datepicker", "name" => "data_inizio", "label" => "Data Inizio"),
                array("view" => "datepicker", "name" => "data_fine", "label" => "Data Fine"),
                array("view" => "text", "name" => "importo", "label" => "Importo"),
                array(
                    "view" => "button",
                    "value" => "Salva",
                    "type" => "form",
                    "click" => "callHandler('saveData', {task: 'AddNewMioOggetto', refresh: true, wnd_id: 'MioModulo_AddNew_Wnd'})"
                )
            )
        )
    );
    
    return json_encode($template);
}
```

### 7.2 Template con Dati Oggetto

```php
/**
 * Template per il dialog di modifica oggetto
 */
public function Template_GetMioModuloModifyDlg($object)
{
    $template = array(
        "view" => "window",
        "id" => "MioModulo_Modify_Wnd",
        "width" => 600,
        "height" => 500,
        "modal" => true,
        "head" => array(
            "view" => "toolbar",
            "cols" => array(
                array("view" => "label", "label" => "Modifica: " . $object->GetName(), "align" => "center"),
                array("view" => "icon", "icon" => "wxi-close", "align" => "right",
                      "click" => "$$('MioModulo_Modify_Wnd').close();")
            )
        ),
        "body" => array(
            "view" => "form",
            "id" => "MioModulo_Modify_Form",
            "elements" => array(
                array("view" => "text", "name" => "id", "value" => $object->GetId(), "type" => "hidden"),
                array("view" => "text", "name" => "nome", "label" => "Nome", "value" => $object->GetName(), "required" => true),
                array("view" => "text", "name" => "descrizione", "label" => "Descrizione", "value" => $object->GetDescr()),
                // ... altri campi
                array(
                    "view" => "button",
                    "value" => "Salva",
                    "type" => "form",
                    "click" => "callHandler('saveData', {task: 'UpdateMioOggetto', refresh: true, wnd_id: 'MioModulo_Modify_Wnd'})"
                )
            )
        )
    );
    
    return json_encode($template);
}
```

---

## 8. Sezioni del Modulo

### 8.1 Struttura AA_GenericModuleSection

```php
// Costruttore
new AA_GenericModuleSection(
    $id,                      // ID sezione (es. "mio_desktop")
    $name,                    // Nome visualizzato (es. "Cruscotto")
    $default,                 // Se è la sezione di default
    $viewId,                  // ID view Webix
    $moduleId,                // ID modulo
    $visible = true,          // Visibile
    $enabled = true,          // Abilitata
    $detail = false,          // È una sezione di dettaglio
    $showInNavbar = true,    // Mostra nella navbar
    $icon = "mdi mdi-home",   // Icona
    $templateFunction = ""    // Funzione template
);
```

### 8.2 Esempio Sezione Desktop

```php
$desktop = new AA_GenericModuleSection(
    static::AA_ID_SECTION_DESKTOP,
    static::AA_UI_SECTION_DESKTOP_NAME,
    true,                                     // default
    static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_DESKTOP_BOX,
    $this->GetId(),
    true,                                     // visible
    true,                                     // enabled
    false,                                    // detail
    true,                                     // showInNavbar
    static::AA_UI_SECTION_DESKTOP_ICON,
    "TemplateSection_Desktop"
);
$desktop->SetNavbarTemplate($this->TemplateGenericNavbar_Void(1, true)->toArray());
$this->AddSection($desktop);
```

---

## 9. Workflow degli Oggetti

### 9.1 Stati Oggetto

Gli oggetti gestiti dal modulo possono avere i seguenti stati (definiti in `AA_Const`):

| Stato | Costante | Valore | Descrizione |
|-------|----------|--------|-------------|
| Bozza | `AA_STATUS_BOZZA` | 1 | Oggetto in fase di editing |
| Pubblicata | `AA_STATUS_PUBBLICATA` | 2 | Oggetto pubblicato e visibile |
| Revisionata | `AA_STATUS_REVISIONATA` | 4 | Oggetto in fase di revisione |
| Cestinata | `AA_STATUS_CESTINATA` | 8 | Oggetto cestinato |

### 9.2 Transizioni di Stato

```
Bozza → Pubblicata     (Publish)
Pubblicata → Revisionata (tramite revisione)
Revisionata → Pubblicata (Publish)
Pubblicata → Cestinata (Trash)
Bozza → Cestinata      (Trash)
Cestinata → Bozza      (Resume)
```

### 9.3 Metodi Workflow

```php
// Pubblicazione
$object->Publish($user);

// Cestinazione
$object->Trash($user);

// Ripristino
$object->Resume($user);

// Verifica stato
if ($object->GetStatus() & AA_Const::AA_STATUS_PUBBLICATA) {
    // È pubblicato
}
```

---

## 10. JavaScript Client-Side

### 10.1 File lib.js.php

```php
<?php
session_start();
include_once("lib.php");
header('Content-Type: text/javascript');
?>

// Definizione del modulo JavaScript
var <?php echo AA_MioModuloModule::AA_ID_MODULE ?> = new AA_Module(
    "<?php echo AA_MioModuloModule::AA_ID_MODULE ?>", 
    "Mio Modulo"
);
<?php echo AA_MioModuloModule::AA_ID_MODULE ?>.valid = true;
<?php echo AA_MioModuloModule::AA_ID_MODULE ?>.content = {};
<?php echo AA_MioModuloModule::AA_ID_MODULE ?>.contentType = "json";
<?php echo AA_MioModuloModule::AA_ID_MODULE ?>.ui.module_content_id = "<?php echo AA_MioModuloModule::AA_UI_MODULE_MAIN_BOX ?>";

// Event handler di esempio
<?php echo AA_MioModuloModule::AA_ID_MODULE ?>.eventHandlers['defaultHandlers'].MioHandler = function() {
    try {
        console.log("MioHandler eseguito", arguments);
        // Logica handler
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "MioHandler", msg, this);
    }
};

// Registrazione del modulo
AA_MainApp.registerModule(<?php echo AA_MioModuloModule::AA_ID_MODULE ?>);
```

### 10.2 Struttura AA_Module JavaScript

```javascript
// Proprietà principali
module.id                 // ID modulo
module.name               // Nome modulo
module.valid              // Flag validità
module.sections           // Array sezioni
module.curSection         // Sezione corrente
module.content            // Contenuto UI
module.taskManager        // URL task manager

// Metodi principali
module.initialize()       // Inizializza il modulo
module.dlg(params)        // Mostra un dialog
module.doTask(params)     // Esegue un task
module.refreshCurSection() // Refresh sezione corrente
module.refreshUiObject(id, refresh, reset) // Refresh oggetto UI

// Eventi
module.eventHandlers['defaultHandlers'].saveData = function(params) { ... }
module.eventHandlers['defaultHandlers'].onDetailViewChange = function() { ... }
```

---

## Checklist per Nuovo Modulo

- [ ] Creare cartella in `utils/modules/<id_modulo>/`
- [ ] Creare `config.php` con include
- [ ] Creare `lib.php` con classe modulo
- [ ] Definire costanti modulo (`AA_ID_MODULE`, `AA_UI_PREFIX`, ecc.)
- [ ] Definire costanti task
- [ ] Implementare costruttore con registrazione task
- [ ] Creare `taskmanager.php`
- [ ] Creare `lib.js.php` per client-side
- [ ] Creare classe oggetto che estende `AA_Object_V2`
- [ ] Creare script SQL per tabelle
- [ ] Definire sezioni modulo
- [ ] Implementare template UI
- [ ] Implementare task standard
- [ ] Aggiungere permessi utente (flag)
- [ ] Testare il modulo

---

## Riferimenti

- Classe base: `AA_GenericModule` (`utils/system_lib/AA_GenericModule.php`)
- Task Manager: `AA_GenericModuleTaskManager` (`utils/system_lib/AA_GenericModuleTaskManager.php`)
- Oggetti: `AA_Object_V2` (`utils/system_core/AA_Object_V2.php`)
- Costanti: `AA_Const` (`utils/system_core/AA_Const.php`)
- Modulo di riferimento: `sicar_9` (`utils/modules/sicar_9/`)

---

## 11. Personalizzazione Dati Sezioni (CustomFilter e CustomDataTemplate)

Le funzioni `CustomFilter` e `CustomDataTemplate` permettono di personalizzare il recupero e la visualizzazione dei dati nelle sezioni standard (Bozze, Pubblicate).

### 11.1 Flusso di Esecuzione

```
GetDataSectionPubblicate_List()
        ↓
GetDataGenericSectionPubblicate_List($params, "CustomFilter", "CustomDataTemplate")
        ↓
1. Richiama CustomFilter($params) → modifica parametri ricerca
2. Esegue AA_MioOggetto::Search($parametri) → recupera dati
3. Per ogni oggetto:
   - Richiama CustomDataTemplate($data, $object) → personalizza output
4. Restituisce array(totale, dati)
```

### 11.2 GetDataSection*_CustomFilter

**Scopo**: Personalizzare i parametri di ricerca per filtrare i dati restituiti.

**Firma**:
```php
protected function GetDataSectionPubblicate_CustomFilter($params = array())
protected function GetDataSectionBozze_CustomFilter($params = array())
```

**Parametri in input** (`$params`):
- `immobile`: ID immobile (es. per filtrare alloggi per immobile)
- `comune`: Codice comune
- `indirizzo`: Indirizzo
- `stato_conservazione`: Stato di conservazione
- `ids`: Lista ID specifici
- Altri parametri dalla request

**Parametri che si possono aggiungere**:
- `where`: Array di condizioni WHERE aggiuntive
- `join`: Array di JOIN aggiuntive
- `order`: Ordinamento personalizzato

**Esempio** (da sicar_9/lib.php):
```php
// Personalizza il filtro delle bozze per il modulo corrente
protected function GetDataSectionBozze_CustomFilter($params = array())
{
    // Filtro per immobile
    if(isset($params['immobile']) && $params['immobile'] > 0)
    {
        $params['where'][] = " AND " . AA_SicarAlloggio::AA_DBTABLE_DATA . ".immobile like '%" . addslashes($params['immobile']) . "%'";
    }

    // Filtro per comune (con JOIN)
    if(!empty($params['comune']))
    {
        $params['join'][] = " LEFT JOIN " . AA_SicarImmobile::GetDatatable() . " ON " . 
                           AA_SicarAlloggio::AA_DBTABLE_DATA . ".immobile=" . 
                           AA_SicarImmobile::GetDatatable() . ".id";
        $params['where'][] = " AND " . AA_SicarImmobile::GetDatatable() . ".comune like '" . addslashes($params['comune']) . "'";
    }

    // Filtro per indirizzo
    if(!empty($params['indirizzo']))
    {
        $params['join'][] = " LEFT JOIN " . AA_SicarImmobile::GetDatatable() . " ON " . 
                           AA_SicarAlloggio::AA_DBTABLE_DATA . ".immobile=" . 
                           AA_SicarImmobile::GetDatatable() . ".id";
        $params['where'][] = " AND " . AA_SicarImmobile::GetDatatable() . ".indirizzo like '%" . addslashes($params['indirizzo']) . "%'";
    }

    // Filtro per stato conservazione
    if(!empty($params['stato_conservazione']) && $params['stato_conservazione'] > 0)
    {
        $params['where'][] = " AND " . AA_SicarAlloggio::AA_DBTABLE_DATA . ".stato_conservazione like '" . addslashes($params['stato_conservazione']) . "'";
    }

    // Filtro per ID specifici
    if(isset($params['ids']) && $params['ids'] != "")
    {
        $params['where'][] = " AND " . AA_SicarAlloggio::GetObjectsDbDataTable() . ".id in (" . addslashes($params['ids']) . ")";
    }
    
    return $params;
}
```

### 11.3 GetDataSection*_CustomDataTemplate

**Scopo**: Personalizzare i campi visualizzati nella lista (datatable/dataview).

**Firma**:
```php
protected function GetDataSectionPubblicate_CustomDataTemplate($data = array(), $object = null)
protected function GetDataSectionBozze_CustomDataTemplate($data = array(), $object = null)
```

**Struttura base di `$data`**:
```php
$data = array(
    "id" => 123,                    // ID oggetto
    "tags" => "",                   // Tag HTML
    "aggiornamento" => "2024-01-01", // Data ultimo aggiornamento
    "denominazione" => "Nome",      // Nome oggetto
    "pretitolo" => "",              // Titolo preliminare
    "sottotitolo" => "",            // Sottotitolo
    "stato" => "bozza",             // Stato (bozza/pubblicata)
    "dettagli" => "",               // Dettagli HTML
    "module_id" => "AA_MODULE_XXX"  // ID modulo
);
```

**Campi personalizzabili**:
- `pretitolo`: Titolo visualizzato sopra al nome
- `titolo`: Nome dell'oggetto
- `sottotitolo`: Sottotitolo (es. ente gestore)
- `tags`: Tag HTML aggiuntivi
- `occupazione`: Campo personalizzato (es. stato occupazione alloggio)
- `interventi`: Campo personalizzato (es. interventi effettuati)

**Esempio** (da sicar_9/lib.php):
```php
// Personalizza il template dei dati delle bozze per il modulo corrente
protected function GetDataSectionBozze_CustomDataTemplate($data = array(), $object = null)
{
    if ($object instanceof AA_SicarAlloggio) {
        // Pretitolo: mostra l'immobile di riferimento
        $data['pretitolo'] = $object->GetImmobile(false);
        
        // Titolo: nome display dell'oggetto
        $data['titolo'] = $object->GetDisplayName();
        
        // Sottotitolo: ente gestore
        $gestore = $object->GetGestore();
        if($gestore instanceof AA_SicarEnte)
        {
            $data['sottotitolo'] = " <span class='AA_DataView_Tag AA_Label AA_Label_LightOrange' title='Ente gestore'>
                Ente gestore: <b>" . $gestore->GetDenominazione() . "</b></span>";
        }
        else 
        {
            $data['sottotitolo'] = "Nessun ente gestore definito";
        }
        
        // Tags: tipologia utilizzo e anno ristrutturazione
        $tags = "<span class='AA_DataView_Tag AA_Label AA_Label_LightYellow' title='Tipo di utilizzo'>
            Tipologia di utilizzo: <b>" . $object->GetTipologiaUtilizzo() . "</b></span>";
        if(!empty($object->GetAnnoRistrutturazione()))
        {
            $tags .= " <span class='AA_DataView_Tag AA_Label AA_Label_LightYellow' title='Anno ultima ristrutturazione'>
                Anno ultima ristrutturazione: <b>" . $object->GetAnnoRistrutturazione() . "</b></span>";
        }
        $data['tags'] = $tags;

        // Occupazione: stato corrente con colori
        $occupazione = $object->GetOccupazione();
        $last_occupazione = current($occupazione);
        
        $tipo_occupazione = AA_Sicar_Const::GetListaTipologieOccupazione(true);
        $colors = array(0 => "LightGray", 1 => "LightGreen", 2 => "LightYellow", 3 => "LightOrange", 4 => "LightRed");
        
        // Click per dettagli occupazione
        $clickDetailOccupazione = "AA_MainApp.utils.callHandler('dlg', 
            {task:'GetSicarDetailStatoOccupazioneAlloggioDlg', 
             params: [{id: " . $object->GetId() . "}, {dal: '" . key($occupazione) . "'}]},'$this->id')";
        
        if($last_occupazione['occupazione_tipo'] > 0) 
        {
            $data['occupazione'] = "<span class='AA_Label AA_Label_" . $colors[$last_occupazione['occupazione_tipo']] . "' 
                style='font-size:large; padding:4px;font-weight:900' title='Stato occupazione'>" . 
                $tipo_occupazione[$last_occupazione['occupazione_tipo']] . "</span>";
        }
        else 
        {
            $data['occupazione'] = "<span class='AA_Label AA_Label_LightGray' 
                style='font-size:large; padding:4px;font-weight:900' title='Stato occupazione'>Libero</span>";
        }
        
        // Nucleo assegnatario (se assegnato)
        if($last_occupazione['occupazione_tipo'] == 1)
        {
            $nucleo = new AA_SicarNucleo();
            if($nucleo->Load($last_occupazione['occupazione_id_nucleo']))
            {
                $data['occupazione'] .= "<div><span style='font-size:small'>a: </span><span style='font-weight:600'>" . 
                    $nucleo->GetDescrizione() . "</span></div>";
            }
        }
    }
    
    return $data;
}
```

---

## 12. Template delle Sezioni (TemplateSection_*)

### 12.1 Definizione

Le funzioni `TemplateSection_*` definiscono il layout e la struttura HTML delle sezioni del modulo.

### 12.2 Struttura Base

```php
public function TemplateSection_NomeSezione($params = array())
{
    // $params contiene i parametri dalla request
    
    // Creazione layout base
    $id = static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_NOMESEZIONE_BOX;
    $layout = new AA_JSON_Template_Layout($id, array(
        "type" => "clean",
        "name" => "Nome Sezione"
    ));
    
    // Aggiungi righe e colonne
    $row = new AA_JSON_Template_Layout("", array("type" => "space"));
    $row->AddCol($this->TemplateSubComponente());
    $layout->AddRow($row);
    
    return $layout->toObject();
}
```

### 12.3 Template Sezione Bozze/Pubblicate

```php
// Template della sezione bozze
public function TemplateSection_Bozze($params = array())
{
    // Verifica permessi utente
    $bCanModify = $this->oUser->HasFlag(AA_MioModulo_Const::AA_USER_FLAG_MIOMODULO);
    $params['enableAddNewMultiFromCsv'] = false;

    // Template HTML per ogni item della lista
    $contentBoxTemplate = "<div class='AA_DataViewItem'>"
        . "<div>#pretitolo#</div>"
        . "<div><span class='AA_DataView_ItemTitle'>#denominazione#</span></div>"
        . "<div>#tags#</div>"
        . "<div><span class='AA_DataView_ItemSubTitle'>#sottotitolo#</span></div>"
        . "<div><span class='AA_Label AA_Label_LightBlue' title='Stato elemento'>#stato#</span>&nbsp;
            <span class='AA_DataView_ItemDetails'>#dettagli#</span></div>"
        // Campi personalizzati
        . "<div>#campo_personalizzato#</div>"
        . "</div>";
    
    // Usa il template generico e personalizza il content box
    $content = $this->TemplateGenericSection_Bozze($params, null);
    $content->SetContentBoxTemplate($contentBoxTemplate);

    return $content->toObject();
}
```

### 12.4 Template Sezione Desktop (Cruscotto)

```php
// Template cruscotto
public function TemplateSection_Desktop()
{
    $id = static::AA_UI_PREFIX . "_" . static::AA_UI_SECTION_DESKTOP_BOX;
    
    // Layout principale
    $layout = new AA_JSON_Template_Layout($id, array(
        "type" => "clean",
        "name" => static::AA_UI_SECTION_DESKTOP_NAME
    ));

    // Riga con moduli/sezioni
    $second_row = new AA_JSON_Template_Layout("", array(
        "type" => "space",
        "css" => array("background-color" => "transparent")
    ));
    $second_row->AddCol($this->TemplateSection_News());
    $layout->AddRow($second_row);

    // Calcolo dimensioni
    $minHeightModuliItem = intval(($_REQUEST['vh'] - 180) / 2);
    $WidthModuliItem = intval(($_REQUEST['vw'] - 110) / 4);

    // Definizione moduli
    $modules = array(
        array(
            "id_section" => static::AA_ID_SECTION_PUBBLICATE,
            "icon" => static::AA_UI_SECTION_PUBBLICATE_ICON,
            "label" => "Gestione Oggetti",
            "descrizione" => "Visualizza e gestisci gli oggetti",
            "tooltip" => "Visualizza e gestisci gli oggetti",
            "visible" => true
        ),
        array(
            "id_section" => static::AA_ID_SECTION_IMMOBILI,
            "icon" => static::AA_UI_SECTION_IMMOBILI_ICON,
            "label" => static::AA_UI_SECTION_IMMOBILI_NAME,
            "descrizione" => static::AA_UI_SECTION_IMMOBILI_DESC,
            "tooltip" => static::AA_UI_SECTION_IMMOBILI_TOOLTIP
        )
    );

    // Template item modulo
    $riepilogo_template = "<div class='AA_DataView_Moduli_item' onclick='#onclick#' 
        style='cursor: pointer; border: 1px solid; display: flex; flex-direction: column; 
        justify-content: center; align-items: center; height: 97%; margin:5px;'>";
    $riepilogo_template .= "<div style='display: flex; align-items: center; height: 120px; font-size: 90px;'>
        <span class='#icon#'></span></div>";
    $riepilogo_template .= "<div style='display: flex; align-items: center; justify-content: center; 
        flex-direction: column; font-size: larger; height: 60px'>
        <span style='font-weight:900;font-variant-caps: all-small-caps;font-size:larger'>#name#</span>
        <span>#tooltip#</span></div>";
    $riepilogo_template .= "</div>";

    // Genera layout moduli
    $moduli_box = new AA_JSON_Template_Layout($id . "_ModuliBox", array(
        "type" => "clean",
        "css" => array("background-color" => "transparent")
    ));

    foreach($modules as $curModId => $curMod)
    {
        $name = "<span style='font-weight:900;font-variant-caps: all-small-caps;font-size:larger'>" . 
                $curMod['label'] . "</span><span>" . $curMod['tooltip'] . "</span>";
        $onclick = "AA_MainApp.utils.callHandler('setCurrentSection', '" . $curMod['id_section'] . "', '" . $this->GetId() . "')";
        
        $moduli_data = array(
            "id" => $curMod['id_section'],
            "name" => $name,
            "tooltip" => $curMod['tooltip'],
            "icon" => $curMod['icon'],
            "onclick" => $onclick
        );
        
        $moduli_view = new AA_JSON_Template_Layout($id . "_ModuleBox_" . $curMod['id_section'], array(
            "type" => "clean",
            "css" => array("background-color" => "transparent")
        ));
        $moduli_view->AddCol(new AA_JSON_Template_Template(
            $id . "_ModuleBox_" . $moduli_data['id'],
            array(
                "template" => $riepilogo_template,
                "borderless" => true,
                "data" => array($moduli_data)
            )
        ));
        
        $moduli_box->AddRow($moduli_view);
    }

    $layout->AddRow($moduli_box);

    return $layout->toObject();
}
```

### 12.5 Template Sezione Dettaglio

```php
// Template sezione dettaglio oggetto
public function TemplateSection_Detail($params)
{
    $id = static::AA_UI_PREFIX . "_" . static::AA_UI_DETAIL_BOX;
    
    $layout = new AA_JSON_Template_Layout($id, array(
        "type" => "clean",
        "name" => static::AA_UI_SECTION_DETAIL_NAME
    ));

    // Toolbar
    $toolbar = new AA_JSON_Template_Toolbar("...");
    // Configura toolbar con bottoni modifica, elimina, pubblica, ecc.
    $layout->AddRow($toolbar);

    // Tabbar per sottosezioni
    $tabbar = new AA_JSON_Template_Tabbar($id . "_Tabbar", array(
        "options" => array(
            array("id" => "generale", "value" => "Dati Generali"),
            array("id" => "allegati", "value" => "Allegati"),
            array("id" => "log", "value" => "Log")
        )
    ));
    $layout->AddRow($tabbar);

    // Multiview per contenuti tab
    $multiview = new AA_JSON_Template_Multiview($id . "_Multiview", array());
    
    // Contenuto tab "Dati Generali"
    $generale = new AA_JSON_Template_Template($id . "_Generale", array(
        "template" => "...", // HTML form dettaglio
        "data" => $params['data'] // dati oggetto
    ));
    $multiview->AddCell($generale);
    
    // Contenuto tab "Allegati"
    $allegati = new AA_JSON_Template_Template($id . "_Allegati", array(
        "template" => "..." // Lista allegati
    ));
    $multiview->AddCell($allegati);

    $layout->AddRow($multiview);

    return $layout->toObject();
}
```

---

## 13. Riepilogo: Catena di Personalizzazione

```
┌─────────────────────────────────────────────────────────────────┐
│ 1. Task: GetSectionContent / GetObjectContent                  │
└─────────────────────────────┬───────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 2. GetDataSectionXXX_List($params)                              │
│    - Calls GetDataGenericSectionXXX_List()                      │
│    - Passa nomi funzioni CustomFilter e CustomDataTemplate      │
└─────────────────────────────┬───────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 3. GetDataGenericSectionXXX_List()                              │
│    a) CustomFilter($params) → aggiunge where/join              │
│    b) Object::Search($params) → recupera dati DB                │
│    c) Per ogni oggetto:                                        │
│       - CustomDataTemplate($data, $object) → personalizza      │
└─────────────────────────────┬───────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ 4. TemplateSection_XXX($params)                                │
│    - Definisce layout Webix                                    │
│    - SetContentBoxTemplate() → personalizza HTML item          │
│    - Restituisce JSON layout                                   │
└─────────────────────────────────────────────────────────────────┘
```

### Utilizzo Comune

| Necessità | Funzione da sovrascrivere |
|-----------|---------------------------|
| Filtrare per campi specifici | `GetDataSection*_CustomFilter()` |
| Mostrare campi personalizzati nella lista | `GetDataSection*_CustomDataTemplate()` |
| Modificare layout sezione | `TemplateSection_NomeSezione()` |
| Cambiare template HTML item | `SetContentBoxTemplate()` |
