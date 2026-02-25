# Moduli Amministrazione Aperta - Workflow e Analisi

## 1. Struttura di un Modulo

Ogni modulo si trova in `utils/modules/<id_modulo>/` e contiene:

| File | Funzione |
|------|----------|
| `lib.php` | Classe principale che estende `AA_GenericModule` |
| `taskmanager.php` | Entry point per le chiamate AJAX |
| `lib.js.php` | JavaScript client-side |
| `config.php` | Configurazione specifica |
| `default.css` | Stili CSS |

---

## 2. Workflow delle Chiamate

### 2.1 Caricamento iniziale (`index.2.0.php`)

- Carica `AA_Platform::GetInstance($user)` che carica i moduli abilitati
- Per ogni modulo carica CSS/JS specifici (`*.css`, `*.js`)
- Genera overlay iniziale con Webix UI

### 2.2 Frontend → Backend

```javascript
// Esempio da lib.js.php
AA_MainApp.utils.callHandler('dlg', {task: 'GetSierModifyDlg', ...}, 'sier_5');
```

Il client chiama `system_ops.php` o `<modulo>/taskmanager.php` con:
- `task`: nome del task
- `id`: oggetto su cui operare
- Altri parametri

### 2.3 Backend: TaskManager

```php
// taskmanager.php (es. sier_5/taskmanager.php)
$module = AA_SierModule::GetInstance($user);
$taskManager = $module->GetTaskManager($user);
$taskManager->RunTask($task);  // esegue Task_GetSierModifyDlg()
```

### 2.4 Esecuzione Task

Ogni task è una funzione nella classe del modulo:
```php
// lib.php - AA_SierModule
public function Task_GetSierModifyDlg($task) { ... }
public function Task_UpdateSier($task) { ... }
```

---

## 3. Registrazione Task

I task vengono registrati nel costruttore del modulo (`lib.php`):

```php
// AA_SierModule::__construct()
$taskManager = $this->GetTaskManager();
$taskManager->RegisterTask("GetSierModifyDlg");   // → Task_GetSierModifyDlg()
$taskManager->RegisterTask("UpdateSier");         // → Task_UpdateSier()
$taskManager->RegisterTask("PublishSier");        // → Task_PublishSier()
```

Il `TaskManager` cerca automaticamente la funzione `Task_<nome_task>` nella classe.

---

## 4. Workflow Tipico

```
1. Frontend (Webix UI)
      ↓ callHandler('dlg', {task: 'GetSierModifyDlg', id: 123})
2. taskmanager.php
      ↓ verifica sessione utente
3. TaskManager->RunTask('GetSierModifyDlg')
      ↓ cerca Task_GetSierModifyDlg() in AA_SierModule
4. Task_GetSierModifyDlg($task)
      ↓ legge $_REQUEST, valida permessi
      ↓ genera HTML dialog (Webix)
5. Risposta JSON/XML
      ↓ Frontend mostra il dialog
6. Utente conferma → callHandler('action', {task: 'UpdateSier', ...})
      ↓ Task_UpdateSier() → Task_GenericUpdateObject() → DB
```

---

## 5. Sezioni Standard

Ogni modulo definisce **sezioni** (schede UI) che eredita da `AA_GenericModule`:

- **Pubblicate** - oggetti pubblicati
- **Bozze** - draft
- **Revisionate** - in revisione  
- **Dettaglio** - vista dettaglio oggetto

Ogni sezione ha un proprio ID, nome, e box UI.

---

## 6. Oggetti

Gli oggetti gestiti da un modulo estendono `AA_Object_V2` e vengono memorizzati nel database. Le operazioni CRUD sono centralizzate:
- `Task_GenericUpdateObject()`
- `Task_GenericTrashObject()`
- `Task_GenericPublishObject()`

---

## 7. Funzioni Client-Side (system_lib.js)

### 7.1 AA_Task() - Nucleo della comunicazione (linea 3906)

```javascript
async function AA_Task(task, taskManagerURL, params, postParams, verbose, raw)
```

**Funzionamento:**
- Costruisce l'URL: `taskManagerURL?task=NOME_TASK&param1=val1&...`
- Aggiunge parametri viewport (`vw`, `vh`, `mobile`)
- Esegue richiesta HTTP GET/POST
- Parsa la risposta XML estraendo:
  - `<status id='status'>`: codice risultato (0=success, -1=errore, -2=auth)
  - `<error id='error'>`: messaggio errore
  - `<content id='content'>`: contenuto (HTML/JSON)
- Supporta codifica base64 e JSON

**Risposta tipica:**
```xml
<root>
  <status id='status'>0</status>
  <error id='error'></error>
  <content id='content' encode='base64' type='json'>...</content>
</root>
```

### 7.2 AA_VerboseTask() - Wrapper con UI (linea 4004)

```javascript
async function AA_VerboseTask(task, taskManagerURL, params, postParams, raw)
```

- Chiama `AA_Task()` 
- Mostra/nasconde messaggio di caricamento ("Caricamento in corso...")
- Se status = -2, mostra overlay autenticazione

### 7.3 AA_Module.doTask() - Esecuzione task (linea 86)

```javascript
this.doTask = async function(params = null)
// params: { task, params, postParams, refresh, refresh_obj_id, wnd_id }
```

**Flusso:**
1. Chiama `AA_VerboseTask()` 
2. Se success (status=0):
   - Mostra messaggio content/error
   - Chiude finestra (`wnd_id`)
   - Refresh UI (`refresh`, `refresh_obj_id`, `refresh_section`)
   - Esegue azione successiva (`result.status.action`)
3. Se errore: alert errore

### 7.4 AA_Module.dlg() - Visualizza dialoghi (linea 147)

```javascript
this.dlg = async function(params)
// params: { task, params, postParams, taskManager }
```

**Flusso:**
1. Chiama `AA_VerboseTask()` per ottenere HTML dialog
2. Crea componente Webix: `webix.ui(result.content.value)`
3. Mostra finestra: `wnd.show()`
4. Setup validazione form (cerca `form.config.validation`)
5. Setup handler campi ricerca (`onTimedKeyPress`, `onChange`)
6. Setup sidemenu state

### 7.5 AA_Module.initializeDefault() - Inizializzazione modulo (linea 274)

```javascript
this.initializeDefault = async function()
```

1. `AA_VerboseTask("GetSections", taskManager)` → carica sezioni modulo
2. `AA_VerboseTask("GetLayout", taskManager)` → carica layout UI

### 7.6 AA_Module.refreshUiObject() - Refresh componente UI (linea 346)

```javascript
this.refreshUiObjectDefault = async function(idObj, bRefreshContent, bResetView)
```

**Funzioni principali:**
- Salva stato componenti (datatable, tabbar, multiview, search, switch, tree, accordion)
- Se `bRefreshContent`: carica nuovo layout via `refreshObjectContent()`
- Ripristina stato componenti dopo refresh
- Setup validazione form, auto-scroll carousel, pager

### 7.7 AA_Module.refreshObjectContent() - Carica contenuto oggetto (linea 817)

```javascript
this.refreshObjectContentDefault = async function(object_id, params, postParams)
// Task: "GetObjectContent"
```

- Costruisce params: section + object_id
- Scarica layout oggetto via `AA_VerboseTask("GetObjectContent", ...)`
- Memorizza in `this.content[object_id] = layout`
- Renderizza: `webix.ui(newObj, parent, oldObj)`

### 7.8 AA_Module.refreshObjectData() - Refresh dati componente (linea 873)

```javascript
this.refreshObjectDataDefault = async function(idObj, params)
// Task: "GetObjectData"
```

- Per datatable/list: carica solo dati (non layout)
- Se filtrato: recupera `filter_data` da runtime values
- `obj.clearAll()` + `obj.parse(dati)` per aggiornare

### 7.9 callHandler() - Invoca handler eventi (linea 2416)

```javascript
AA_MainApp.utils.callHandler = async function(handler, params, module_id)
```

**Lookup handler in ordine:**
1. `module.eventHandlers.defaultHandlers[handler]`
2. `module[handler]` (metodo diretto modulo)
3. `module.eventHandlers[handler]`
4. `window[handler]` (funzione globale)

**Usato per:**
- Click menu: `onClick: "MenuItemClick"`
- Form submit: `on: { onAfterSubmit: saveData }`
- Doppio click lista: `on: { onItemDblClick: ListaDblClick }`

### 7.10 AA_Module.menuEventHandlerDefault() - Gestore click menu (linea 942)

```javascript
this.menuEventHandlerDefault = async function()
```

- Recupera item cliccato da `AA_MainApp.ui.MainUI.activeMenu`
- Cerca handler in:
  1. modulo (`module[handler]` o `module.eventHandlers.defaultHandlers[handler]`)
  2. funzioni globali (`window[handler]`)
- Esegue handler con `handler_params`

---

## 8. Server-Side: Classi Principali

### 8.1 AA_GenericModule (`system_lib/AA_GenericModule.php`)

Classe base per tutti i moduli. Definisce:
- Costanti per sezioni standard (Pubblicate, Bozze, Revisionate, Dettaglio)
- Costanti per task UI standard (GetGenericPubblicateFilterDlg, GetGenericAddNewDlg, ecc.)
- Metodi per gestione sezioni (`AddSection`, `GetSection`, `GetSections`)
- Metodi per recupero dati (`GetDataGenericSectionPubblicate_List`, ecc.)
- Gestione TaskManager (`GetTaskManager`)

### 8.2 AA_GenericModuleTaskManager (`system_lib/AA_GenericModuleTaskManager.php`)

Estende `AA_GenericTaskManager`. Gestisce la registrazione e esecuzione dei task:
- `RegisterTask($task, $taskFunction)`: registra un nuovo task
- cerca automaticamente la funzione `Task_$task` nella classe del modulo

### 8.3 AA_GenericModuleTask (`system_lib/AA_GenericModuleTask.php`)

Rappresenta un singolo task. Estende `AA_GenericTask`.

### 8.4 AA_GenericModuleSection (`system_lib/AA_GenericModuleSection.php`)

Rappresenta una sezione del modulo con:
- ID, nome, box UI
- Flag per visibilità, default, detail view
- Icona

---

## 9. Catena Tipica Frontend → Backend

```
1. UI Event (click, submit)
      ↓
2. callHandler('nomeHandler', {params})
      ↓
3. Handler cerca funzione nel modulo
      ↓
4. modulo.doTask({task: 'Task_NomeTask', ...})
      ↓
5. AA_VerboseTask('Task_NomeTask', taskManager, params, postParams)
      ↓
6. AA_Task() → HTTP request
      ↓
7. Server (taskmanager.php) → Task_NomeTask()
      ↓
8. Risposta XML → Parse
      ↓
9. Refresh UI / Show Dialog
```

---

## 10. Esempio Pratico: Modulo SIER

### File: `utils/modules/sier_5/lib.php`

```php
class AA_SierModule extends AA_GenericModule
{
    const AA_ID_MODULE = "sier_5";
    
    public function __construct($user, $bDefaultSections = true)
    {
        // Registrazione task
        $taskManager = $this->GetTaskManager();
        $taskManager->RegisterTask("GetSierModifyDlg");
        $taskManager->RegisterTask("GetSierAddNewDlg");
        $taskManager->RegisterTask("UpdateSier");
        $taskManager->RegisterTask("AddNewSier");
        $taskManager->RegisterTask("PublishSier");
        // ... altri task
    }
    
    // Task per visualizzare dialog modifica
    public function Task_GetSierModifyDlg($task) { ... }
    
    // Task per aggiornare oggetto
    public function Task_UpdateSier($task) { ... }
}
```

### File: `utils/modules/sier_5/taskmanager.php`

```php
session_start();
$user = AA_User::GetCurrentUser();
$module = AA_SierModule::GetInstance($user);
$taskManager = $module->GetTaskManager($user);

if ($taskManager->IsManaged($task)) {
    $taskManager->RunTask($task);
    die($taskManager->GetTaskLog($task));
}
```

---

## 11. Note

- L'architettura segue un pattern MVC parziale
- Il frontend usa Webix per l'interfaccia UI
- Le risposte del server sono in formato XML con supporto base64
- Il sistema supporta autenticazione tradizionale, JWT e SSO (phpSAML)
- Gli oggetti hanno workflow di stato: bozza → revisionata → pubblicata → cestinata
