### Class: `AA_AMAAI`

**File:** `utils/system_lib/AA_AMAAI.php`
**SHA256:** `078d932cb49ef3d8a96e8a5daa2ed7c2db1c9daf2e053ad235466254c3e3f4a0`

#### Description
La classe `AA_AMAAI` gestisce la navigazione assistita (AMAAI).

#### Methods
- `GetInstance()`: Restituisce l'istanza singleton della classe.
- `TemplateLayout()`: Restituisce il template per il layout della finestra di navigazione assistita.
- `TemplateStart()`: Restituisce il contenuto HTML per la pagina iniziale della navigazione assistita, con le opzioni principali a disposizione dell'utente.

### Class: `AA_Archivio`

**File:** `utils/system_lib/AA_Archivio.php`
**SHA256:** `65464ba1a8f7d4cabc92e09ea0caa2250927431126d9a2754b02b85c2a423849`

#### Description
La classe `AA_Archivio` fornisce metodi per archiviare e recuperare lo stato degli oggetti nel tempo.

#### Methods
- `Snapshot($date = "", $id_object = 0, $object_type = 0, $content = "", $user = null)`: Archivia lo stato di un oggetto nel database. Prende come parametri la data, l'ID e il tipo dell'oggetto, il contenuto da archiviare e l'utente che esegue l'operazione.
- `Resume($date = "", $id_object = 0, $object_type = 0)`: Recupera l'ultima rappresentazione di un oggetto dall'archivio prima di una data specificata.
- `ResumeMulti($id_object = 0, $object_type = 0, $date = "", $num = 1)`: Recupera un numero specificato di rappresentazioni di un oggetto dall'archivio, ordinate per data.

### Class: `AA_Assessorato`

**File:** `utils/system_lib/AA_Assessorato.php`
**SHA256:** `7bf3c9b4144f057d73f0fc11d5c6aa0b23af7a3467f381793ad2756ed499fc3f`

#### Description
La classe `AA_Assessorato` rappresenta un assessorato, agenzia, ente o commissario. Estende la classe `AA_Struttura`.

#### Constants
- `AA_TIPO_ASSESSORATO`: Tipo per l'assessorato.
- `AA_TIPO_AGENZIA`: Tipo per l'agenzia.
- `AA_TIPO_ENTE`: Tipo per l'ente.
- `AA_TIPO_COMMISSARIO`: Tipo per il commissario.

#### Methods
- `GetTipologie()`: Restituisce un array con le tipologie di assessorato.
- `__construct($params = null)`: Costruttore della classe.
- `GetDirezioni($bAsObjects=false)`: Restituisce un array di direzioni (oggetti `AA_Direzione` o ID) associate all'assessorato.
- `Delete($user=null)`: Elimina l'assessorato e tutte le direzioni associate.

### Class: `AA_DbBind`

**File:** `utils/system_lib/AA_DbBind.php`
**SHA256:** `0ce02f3c43a12263bf7ab09fbfbeddfc21d76d9a28b1fe4b0fb45fd6ee91ed5d`

#### Description
La classe `AA_DbBind` gestisce i collegamenti tra le variabili di un oggetto e i campi di una tabella di database.

#### Methods
- `GetBindings()`: Restituisce un array con tutti i collegamenti correnti.
- `SetTable($table = "")`: Imposta il nome della tabella del database.
- `GetTable()`: Restituisce il nome della tabella del database.
- `AddBind($nomeVariabile = "", $nomeCampo = "")`: Aggiunge un nuovo collegamento tra una variabile e un campo del database.
- `DelBind($nomeVariabile = "")`: Rimuove un collegamento esistente.

### Class: `AA_Direzione`

**File:** `utils/system_lib/AA_Direzione.php`
**SHA256:** `48eb02068564ec8477008582c4447f183c6511e2dc95afabe82c2ccf058350a2`

#### Description
La classe `AA_Direzione` rappresenta una direzione all'interno di un assessorato. Estende la classe `AA_Struttura`.

#### Methods
- `__construct($params = null)`: Costruttore della classe.
- `Delete($user=null)`: Elimina la direzione e tutti i servizi associati.
- `GetServizi($bAsObjects=false)`: Restituisce un array di servizi (oggetti `AA_Servizio` o ID) associati alla direzione.

### Class: `AA_GenericLogDlg`

**File:** `utils/system_lib/AA_GenericLogDlg.php`
**SHA256:** `b5650b9b5221281dd50c4193828006631e7f53717a31ad66f9c792a7632196b2`

#### Description
La classe `AA_GenericLogDlg` crea una finestra di dialogo per la visualizzazione dei log di un oggetto. Estende la classe `AA_GenericWindowTemplate`.

#### Methods
- `__construct($id = "", $title = "Logs", $user = null)`: Costruttore della classe. Inizializza la finestra di dialogo, verifica i permessi dell'utente e carica i log dell'oggetto specificato.
- `Update()`: Metodo protetto per l'aggiornamento della finestra di dialogo.

### Class: `AA_GenericModule`

**File:** `utils/system_lib/AA_GenericModule.php`
**SHA256:** `144fa8dcf305861e31249f2467b5c8ea01bde083c6ffd8b79437c4ea2052872c`

#### Description
La classe `AA_GenericModule` è una classe generica per la creazione di moduli all'interno della piattaforma. Fornisce una struttura di base e funzionalità comuni per la gestione di sezioni, task, template e interazioni con l'utente.

#### Key Responsibilities
- **Gestione delle Sezioni:** Organizza il modulo in sezioni (es. "Bozze", "Pubblicate", "Dettaglio").
- **Gestione dei Task:** Registra e gestisce i task del modulo per azioni come il recupero di layout, dati e la gestione delle azioni dell'utente.
- **Templating:** Fornisce un sistema di templating per la generazione di componenti dell'interfaccia utente come layout, sezioni, barre di navigazione, menu e finestre di dialogo.
- **Gestione dei Dati:** Include metodi generici per recuperare, filtrare e visualizzare i dati per le diverse sezioni.
- **Azioni Utente:** Implementa una vasta gamma di task generici per le azioni comuni dell'utente come la creazione, l'aggiornamento, l'eliminazione, la pubblicazione, la cestinatura, il ripristino e la riassegnazione di oggetti.
- **Esportazione:** Fornisce funzionalità per esportare i dati in formato PDF e CSV.
- **Permessi:** Gestisce i permessi degli utenti per le varie azioni all'interno del modulo.

#### Key Methods
- `__construct($user = null, $bDefaultSections = true)`: Costruttore che inizializza il modulo, registra i task di default e imposta le sezioni predefinite.
- `GetTaskManager()`: Restituisce il gestore di task per il modulo.
- `Task_*`: Numerosi metodi con prefisso `Task_` che gestiscono task specifici (es. `Task_GetLayout`, `Task_GenericAddNew`).
- `Template_*`: Numerosi metodi con prefisso `Template_` che generano template per i componenti dell'interfaccia utente (es. `TemplateLayout`, `TemplateSection_Bozze`).
- `GetData*`: Metodi per il recupero dei dati da visualizzare nelle sezioni del modulo.

### Class: `AA_GenericModuleSection`

**File:** `utils/system_lib/AA_GenericModuleSection.php`
**SHA256:** `b12f1f9e91f7ee3dff8006ba59850bd79efbe058b085bfbaee810d7e58ef6f28`

#### Description
La classe `AA_GenericModuleSection` rappresenta una sezione all'interno di un modulo generico (`AA_GenericModule`). Contiene le informazioni sulle proprietà della sezione, come ID, nome, icona e visibilità nella barra di navigazione.

#### Methods
- `GetId()`: Restituisce l'ID della sezione.
- `GetName()`: Restituisce il nome della sezione.
- `GetIcon()`: Restituisce l'icona della sezione.
- `IsVisibleInNavbar()`: Indica se la sezione è visibile nella barra di navigazione.
- `GetViewId()`: Restituisce il view ID della sezione.
- `GetModuleId()`: Restituisce il module ID della sezione.
- `isValid()`: Indica se la sezione è valida.
- `IsDefault()`: Indica se la sezione è predefinita.
- `IsDetail()`: Indica se la sezione è di dettaglio.
- `toArray()`: Restituisce una rappresentazione della sezione come array.
- `__construct(...)`: Costruttore della classe.
- `TemplateActionMenu()`: Restituisce il template per il menu azioni della sezione.

### Class: `AA_GenericModuleTaskManager`

**File:** `utils/system_lib/AA_GenericModuleTaskManager.php`
**SHA256:** `06b785d830b8995619368777244cc2754eee370ae85fd4abf73dc22b3f834945`

#### Description
La classe `AA_GenericModuleTaskManager` gestisce i task per un modulo generico (`AA_GenericModule`). È responsabile della registrazione e della gestione dei task associati a un modulo. Estende la classe `AA_GenericTaskManager`.

#### Methods
- `GetModule()`: Restituisce il modulo associato al gestore di task.
- `__construct($module = null, $user = null)`: Costruttore della classe. Associa il gestore di task a un modulo.
- `RegisterTask($task = null, $taskFunction = "")`: Registra un nuovo task per il modulo, verificando l'esistenza della funzione di task corrispondente nel modulo.

### Class: `AA_GenericModuleTask`

**File:** `utils/system_lib/AA_GenericModuleTask.php`
**SHA256:** `64bbeed1b45aedda5653a1c0e7be47a341c1f358daaddb75507efcce4aa86990`

#### Description
La classe `AA_GenericModuleTask` rappresenta un task all'interno di un modulo generico (`AA_GenericModule`). È responsabile dell'esecuzione di un task specifico chiamando un metodo corrispondente nel modulo. Estende la classe `AA_GenericTask`.

#### Methods
- `SetContent($val="", $json=false, $encode=true)`: Imposta il contenuto della risposta del task.
- `SetStatus($val=0)`: Imposta lo stato della risposta del task.
- `SetStatusAction($action="",$params=null,$json_encode=false)`: Imposta un'azione da eseguire sul lato client al termine del task.
- `SetError($val="", $json=false, $encode=true)`: Imposta il messaggio di errore per la risposta del task.
- `Run()`: Esegue il task chiamando il metodo corrispondente nel modulo.
- `GetLog()`: Restituisce il log XML dell'esecuzione del task, includendo stato, contenuto e informazioni di errore.

### Class: `AA_GenericNews`

**File:** `utils/system_lib/AA_GenericNews.php`
**SHA256:** `815b55bcf2d165d8edb750c62b798b92eb1e87f66e00a2979f8865faa72892e0`

#### Description
La classe `AA_GenericNews` rappresenta una notizia. Estende la classe `AA_GenericParsableDbObject`, il che significa che può essere analizzata da un record del database.

#### Methods
- `__construct($params = null)`: Costruttore della classe. Inizializza le proprietà della notizia.

### Class: `AA_GenericPagedSectionTemplate`

**File:** `utils/system_lib/AA_GenericPagedSectionTemplate.php`
**SHA256:** `5481fa8350a458e3b50724990e5aa79ae69fd3841492e68eaa642210cd2a5959`

#### Description
La classe `AA_GenericPagedSectionTemplate` è un template per la creazione di sezioni impaginate all'interno di un modulo. Fornisce un modo strutturato per definire il layout e il comportamento di una sezione che visualizza un elenco di elementi con paginazione, filtri e varie azioni.

#### Key Features
- **Paginazione:** Gestisce la paginazione del contenuto.
- **Filtri:** Supporta il filtraggio del contenuto.
- **Azioni:** Fornisce un set di azioni configurabili (aggiunta, dettaglio, cestinamento, eliminazione, riassegnazione, ripristino, pubblicazione, esportazione in PDF e CSV).
- **Templating:** Consente la personalizzazione del template per la visualizzazione degli elementi.
- **Output JSON:** Può essere renderizzato come un oggetto JSON.

#### Key Methods
- `__construct($id, $module, $content_box)`: Costruttore che inizializza il template.
- `toObject()`: Renderizza il template come un oggetto `AA_JSON_Template_Generic`.
- `SetContentBoxData($data)`: Imposta i dati da visualizzare.
- `EnablePaging()`, `EnablePager()`: Abilitano la paginazione e l'interfaccia del pager.
- `EnableFiltering()`: Abilita la funzionalità di filtro.
- `EnableAddNew()`, `EnableAddNewMulti()`: Abilitano l'aggiunta di nuovi elementi.
- `ViewDetail()`, `ViewTrash()`, `ViewDelete()`, etc.: Metodi per abilitare o disabilitare azioni specifiche.
- `Set...Handler()`, `Set...HandlerParams()`: Metodi per impostare gli handler e i parametri per le varie azioni.

### Class: `AA_GenericParsableDbObject`

**File:** `utils/system_lib/AA_GenericParsableDbObject.php`
**SHA256:** `bd56fe9eee8e36e9b7e74da8241350bd25d6eaab9e7f6e13e566f29b38676f6c`

#### Description
La classe `AA_GenericParsableDbObject` fornisce un framework generico per oggetti che possono essere analizzati da e sincronizzati con una tabella di database. Estende `AA_GenericParsableObject`, aggiungendo funzionalità specifiche del database come il salvataggio, il caricamento, la ricerca e l'eliminazione di record.

#### Key Features
- **Integrazione Database:** Fornisce metodi per interagire con una tabella di database.
- **ORM Lite:** Facilita le operazioni CRUD (Create, Read, Update, Delete) per oggetti che si mappano direttamente alle righe del database.
- **Estensibile:** Progettata per essere estesa da classi concrete che definiscono la propria tabella di database e la struttura dell'oggetto.

#### Key Methods
- `GetDatatable()`: Metodo statico per ottenere il nome della tabella del database associata alla classe.
- `GetObjectClass()`: Metodo statico per ottenere il nome della classe dell'oggetto.
- `GetDbClass()`: Metodo statico per ottenere il nome della classe del database utilizzata per le operazioni.
- `__construct($params=null)`: Costruttore, chiama il costruttore padre.
- `Sync()`: Sincronizza le proprietà dell'oggetto con il database. Esegue un'operazione INSERT se l'oggetto è nuovo (ID è 0) o un'operazione UPDATE se l'oggetto esiste già.
- `Update($params=null, $user=null)`: Aggiorna le proprietà dell'oggetto e le sincronizza con il database.
- `Search($params=null)`: Metodo statico per cercare oggetti nel database in base ai parametri forniti (WHERE, ORDER, LIMIT).
- `LoadDataFromDb($id=0)`: Metodo protetto per caricare dati grezzi per un dato ID dal database.
- `Load($id=0,$user=null)`: Carica le proprietà di un oggetto dal database in base al suo ID.
- `Delete($user=null)`: Elimina l'oggetto dal database.
- `DeleteFromDb()`: Metodo protetto che esegue l'effettiva eliminazione dal database.

### Class: `AA_GenericParsableObject`

**File:** `utils/system_lib/AA_GenericParsableObject.php`
**SHA256:** `404568d8b6b3c32c645d1e341975c71e363ca06535e8dbdc5b5fd31b9470d82b`

#### Description
La classe `AA_GenericParsableObject` fornisce una struttura fondamentale per gli oggetti che possono essere analizzati da un array di valori e possono generare una vista template predefinita. È una classe base per oggetti dati che devono archiviare proprietà e visualizzarle in modo strutturato.

#### Key Features
- **Gestione delle Proprietà:** Archivia e gestisce le proprietà dell'oggetto in un array associativo (`$aProps`).
- **Parsing:** Può inizializzare le sue proprietà da un array di valori di input.
- **Generazione della Vista Template:** Fornisce funzionalità per generare una vista template predefinita basata su griglia delle sue proprietà, che può essere personalizzata.

#### Key Methods
- `Parse($values=null)`: Importa i valori da un array associativo nelle proprietà dell'oggetto.
- `SetTemplateViewProps($props=null)`: Imposta la configurazione per come le proprietà devono essere visualizzate nella vista template.
- `GetTemplateViewProps()`: Restituisce la configurazione della vista template per le proprietà.
- `SetDefaultTemplateViewProps()`: Imposta una configurazione predefinita per la visualizzazione di tutte le proprietà nella vista template.
- `GetTemplateView($bRefresh=false)`: Restituisce un oggetto `AA_GenericTemplate_Grid` che rappresenta la vista template delle proprietà dell'oggetto.
- `GetDefaultTemplateView()`: Crea e restituisce la vista template predefinita basata su griglia.
- `SetTemplateView($var = null)`: Imposta un oggetto vista template personalizzato.
- `__construct($params=null)`: Costruttore che inizializza la proprietà `id` a 0 e analizza i parametri iniziali.
- `SetProp($prop="",$value="")`: Imposta il valore di una proprietà specifica.
- `GetProp($prop="")`: Restituisce il valore di una proprietà specifica.
- `GetProps()`: Restituisce tutte le proprietà dell'oggetto come un array associativo.

### Class: `AA_GenericPdfPreviewDlg`

**File:** `utils/system_lib/AA_GenericPdfPreviewDlg.php`
**SHA256:** `73207d9b506d1441ef705406d0ba6d67dcd659c3ed45ace6a619e13d0f0d5c07`

#### Description
La classe `AA_GenericPdfPreviewDlg` rappresenta una finestra di dialogo generica per la visualizzazione di anteprime PDF. Estende `AA_GenericWindowTemplate` e imposta un layout di base per un visualizzatore PDF.

#### Methods
- `__construct($id = "", $title = "Pdf Viewer", $module = "")`: Costruttore della classe. Inizializza la finestra di dialogo con un titolo e un messaggio di caricamento per l'anteprima PDF.
- `Update()`: Metodo protetto per l'aggiornamento della finestra di dialogo.

### Class: `AA_GenericResetPwdDlg`

**File:** `utils/system_lib/AA_GenericResetPwdDlg.php`
**SHA256:** `c881de52d56b24e0e553f878402ae607b863627a2c6f195f68b3b39eb30e388b`

#### Description
La classe `AA_GenericResetPwdDlg` rappresenta una finestra di dialogo generica per il reset della password di un utente. Estende `AA_GenericFormDlg` e fornisce campi per l'inserimento di un OTP (One-Time Password) ricevuto via email.

#### Methods
- `__construct($id = "", $title = "", $formData = array(), $resetData = array(), $applyActions = "", $save_formdata_id = "")`: Costruttore della classe. Inizializza la finestra di dialogo, imposta le sue dimensioni e aggiunge un campo di testo per l'OTP.

### Class: `AA_GenericResources`

**File:** `utils/system_lib/AA_GenericResources.php`
**SHA256:** `a602b6f507b7c85096c62834555f720c7c1b726e51fcdced7462e2046b341a0b`

#### Description
La classe `AA_GenericResources` rappresenta risorse generiche memorizzate nel database. Estende `AA_GenericParsableDbObject` e definisce le proprietà per un timestamp, un modulo e i dati.

#### Methods
- `__construct($params = null)`: Costruttore della classe. Imposta il nome della tabella del database su "aa_resources" e inizializza le proprietà.

### Class: `AA_GenericServerStatusDlg`

**File:** `utils/system_lib/AA_GenericServerStatusDlg.php`
**SHA256:** `63bfdf35e531fe267a65546bb75e02c60f09610c281c3c8fbbc7bf9e960a40ff`

#### Description
La classe `AA_GenericServerStatusDlg` rappresenta una finestra di dialogo generica per la visualizzazione delle informazioni sullo stato del server. Estende `AA_GenericWindowTemplate` e imposta un layout per recuperare e visualizzare lo stato del server, con un controllo per i permessi di superutente.

#### Methods
- `__construct($id = "", $title = "Logs", $user = null)`: Costruttore della classe. Inizializza la finestra di dialogo, imposta le sue dimensioni, verifica se l'utente corrente è un superutente e, in tal caso, imposta un template per recuperare lo stato del server da un URL.
- `Update()`: Metodo protetto per l'aggiornamento della finestra di dialogo.

### Class: `AA_GenericStructDlg`

**File:** `utils/system_lib/AA_GenericStructDlg.php`
**SHA256:** `76d5914f6be9a07790dafe8ca1b1673c65356de598c718345dd6d3cdceb56a90`

#### Description
La classe `AA_GenericStructDlg` rappresenta una finestra di dialogo generica per la selezione di una struttura gerarchica (es. Assessorato -> Direzione -> Servizio). Estende `AA_GenericWindowTemplate` e fornisce una visualizzazione ad albero per sfogliare le strutture, insieme a funzionalità di filtraggio.

#### Key Features
- **Selezione Struttura Gerarchica:** Consente agli utenti di selezionare un elemento da una struttura ad albero.
- **Filtraggio:** Fornisce un campo di ricerca per filtrare le strutture visualizzate.
- **Toggle Strutture Soppresse:** Include un interruttore per mostrare o nascondere le strutture soppresse.
- **Azioni Dinamiche:** L'azione del pulsante "Applica" viene generata dinamicamente in base al form di destinazione.

#### Key Methods
- `GetTargetForm()`: Restituisce l'ID del form di destinazione con cui questa finestra di dialogo interagisce.
- `SetApplyActions($actions = "")`: Imposta le azioni JavaScript da eseguire quando si fa clic sul pulsante "Applica".
- `__construct($id = "", $title = "", $options = null, $applyActions = "", $module = "", $user = null)`: Costruttore della classe. Inizializza la finestra di dialogo, imposta le sue dimensioni e costruisce la visualizzazione ad albero della struttura con opzioni di filtraggio e attivazione/disattivazione.
- `Update()`: Metodo protetto che aggiorna dinamicamente l'azione di clic del pulsante "Applica" per passare i dati della struttura selezionata al form di destinazione.

### Class: `AA_GenericTaskManager`

**File:** `utils/system_lib/AA_GenericTaskManager.php`
**SHA256:** `927eee49494f4690df5f75147bd612a76bc2ddfae230e3adb219d10e48cfe1da`

#### Description
La classe `AA_GenericTaskManager` fornisce un sistema generico di gestione dei task. Consente la registrazione, il recupero e l'esecuzione dei task. Ogni task è rappresentato da un oggetto `AA_GenericTask` o da un nome di classe che può essere istanziato in un `AA_GenericTask`.

#### Key Features
- **Registrazione Task:** I task possono essere registrati come istanze di `AA_GenericTask` o come nomi di classe.
- **Esecuzione Task:** Fornisce un metodo per eseguire un task registrato.
- **Logging Task:** Memorizza e recupera i log e i messaggi di errore per i task eseguiti.
- **Associazione Utente:** Ogni task manager è associato a un utente.

#### Constants
- `AA_STATUS_FAILED`: -1 (Indica il fallimento del task)
- `AA_STATUS_SUCCESS`: 0 (Indica il completamento del task con successo)
- `AA_STATUS_UNAUTH`: -2 (Indica un accesso o un'operazione non autorizzati)

#### Key Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task manager e lo associa a un utente.
- `RegisterTask($task = null, $class = null)`: Registra un nuovo task. Accetta un'istanza di `AA_GenericTask` o un nome di classe.
- `Clear()`: Cancella tutti i task registrati.
- `GetTask($name = "")`: Recupera un task registrato per nome. Se il task è stato registrato come nome di classe, istanzia la classe.
- `UnregisterTask($name = "")`: Rimuove un task registrato.
- `RunTask($taskName = "")`: Esegue un task per nome.
- `GetTaskLog($taskName = "")`: Recupera il log di un task specifico.
- `GetTaskError($taskName = "")`: Recupera il log di errore di un task specifico.
- `IsManaged($taskName = "")`: Controlla se un task è registrato e gestito da questo task manager.

### Class: `AA_GenericTask`

**File:** `utils/system_lib/AA_GenericTask.php`
**SHA256:** `d1a0d2a3951336ac811f8b47f867fb90bbbf6e45a18853455e6ddb3b7ab36ce2`

#### Description
La classe `AA_GenericTask` è una classe base generica per i task all'interno del sistema. Fornisce proprietà e metodi fondamentali per la gestione dei task, inclusi nome del task, associazione utente, logging e gestione degli errori. È progettata per essere estesa da classi di task più specifiche.

#### Key Features
- **Denominazione Task:** Memorizza e recupera il nome del task.
- **Associazione Utente:** Associa il task a un utente specifico.
- **Logging:** Fornisce meccanismi per memorizzare e recuperare i log di esecuzione del task e i messaggi di errore.
- **Integrazione Task Manager:** Può essere associata a un task manager.

#### Constants
- `AA_STATUS_FAILED`: -1 (Indica il fallimento del task)
- `AA_STATUS_SUCCESS`: 0 (Indica il completamento del task con successo)
- `AA_STATUS_UNAUTH`: -2 (Indica un accesso o un'operazione non autorizzati)

#### Key Methods
- `GetTaskManager()`: Restituisce il task manager associato.
- `SetTaskManager($taskManager = null)`: Imposta il task manager per il task.
- `GetName()`: Restituisce il nome del task.
- `SetName($name = "")`: Imposta il nome del task.
- `GetError()`: Restituisce il messaggio di errore, se presente.
- `SetError($error)`: Imposta il messaggio di errore.
- `Run()`: Il metodo principale per eseguire la logica del task. Questo metodo è destinato ad essere sovrascritto dalle classi derivate.
- `__construct($taskName = "", $user = null, $taskManager = null)`: Costruttore che inizializza il task con il suo nome, utente e task manager.
- `GetLog()`: Restituisce il log di esecuzione del task.
- `SetLog($log)`: Imposta il log di esecuzione del task.

### Class: `AA_NewsTags`

**File:** `utils/system_lib/AA_NewsTags.php`
**SHA256:** `11fbccf78b5dc6d600d354447a24484b0ebddcc1bcda4494d0a2ee065dad0c48`

#### Description
La classe `AA_NewsTags` fornisce un semplice meccanismo per la gestione dei tag delle notizie. È implementata come singleton per garantire che esista una sola istanza del gestore di tag. Definisce un set di tag predefiniti.

#### Key Features
- **Pattern Singleton:** Garantisce che esista una sola istanza di `AA_NewsTags` a livello globale.
- **Tag Predefiniti:** Contiene un set fisso di tag di notizie (es. "esterna", "interna").

#### Methods
- `Initialize()`: Metodo statico protetto per inizializzare l'istanza singleton di `AA_NewsTags`.
- `__construct()`: Costruttore privato che inizializza i tag predefiniti.
- `GetTags($params=null)`: Metodo statico che restituisce l'array di tag predefiniti. Garantisce che l'istanza singleton sia inizializzata prima di restituire i tag.

### Class: `AA_ObjectVarMapping`

**File:** `utils/system_lib/AA_ObjectVarMapping.php`
**SHA256:** `527e669e84ea371713ee30c47ba85819ef8fb3acee19bb158ae917e92a50385d`

#### Description
La classe `AA_ObjectVarMapping` gestisce la mappatura tra le variabili di un oggetto e le loro proprietà di visualizzazione (nome, tipo, etichetta). Può essere associata a un'istanza di `AA_Object`.

#### Key Features
- **Mappatura Variabili:** Definisce come le variabili di un oggetto sono rappresentate in termini di nome, tipo e etichetta.
- **Associazione Oggetto:** Permette di collegare un oggetto `AA_Object` al mapping.

#### Methods
- `GetObject()`: Restituisce l'oggetto `AA_Object` associato.
- `SetObject($object = null)`: Imposta l'oggetto `AA_Object` da associare.
- `__construct($object = null)`: Costruttore che inizializza il mapping con un oggetto `AA_Object` facoltativo.
- `AddVar($var_name = "", $name = "", $type = "", $label = "")`: Aggiunge un nuovo mapping per una variabile, specificandone il nome interno, il nome visualizzato, il tipo e l'etichetta.
- `DelVar($var_name = "")`: Rimuove un mapping per una variabile.
- `GetName($var_name = "")`: Restituisce il nome visualizzato di una variabile mappata.
- `GetType($var_name = "")`: Restituisce il tipo di una variabile mappata.
- `GetLabel($var_name = "")`: Restituisce l'etichetta di una variabile mappata.

### Class: `AA_Risorse`

**File:** `utils/system_lib/AA_Risorse.php`
**SHA256:** `a178241b5a4df207046830afb35d749ec0d49256646aa9431a922ada1b041041`

#### Description
La classe `AA_Risorse` gestisce le risorse (file) all'interno dell'applicazione, fornendo funzionalità per la persistenza, la manipolazione e la gestione dei file. Estende `AA_GenericParsableDbObject`.

#### Key Features
- **Persistenza nel Database:** Gestisce il salvataggio e il recupero delle informazioni sulle risorse dal database.
- **Caricamento da URL:** Permette di caricare risorse tramite il loro nome URL.
- **Gestione Informazioni File:** Memorizza e recupera dettagli come nome, tipo, dimensione e hash del file.
- **Aggiunta e Eliminazione File:** Fornisce metodi per aggiungere nuovi file (da percorsi locali o dallo storage) ed eliminate risorse esistenti.
- **Controllo Permessi:** Applica controlli sui permessi utente per le operazioni sensibili.

#### Methods
- `__construct($params = null)`: Costruttore della classe. Inizializza le proprietà della risorsa.
- `LoadFromUrlName($url_name)`: Carica una risorsa dal database in base al suo nome URL.
- `Parse($values = null)`: Analizza un array di valori per inizializzare le proprietà della risorsa, con una gestione specifica per le informazioni del file.
- `GetFileInfo()`: Restituisce le informazioni dettagliate sul file associato alla risorsa.
- `SetFileInfo($val=null)`: Imposta le informazioni dettagliate sul file per la risorsa.
- `AddFile($params=null,$user=null)`: Metodo statico per aggiungere un file allo storage e creare una risorsa nel database.
- `AddFileFromStorage($hash="",$url_name="",$categorie="",$user=null)`: Metodo statico per aggiungere una risorsa basata su un file già presente nello storage (identificato da hash).
- `AddGenericFileFromUpload($url_name="",$categorie="",$user=null)`: Gestisce l'upload di un file generico e la creazione di una risorsa.
- `GetFile($user=null)`: Recupera l'oggetto file dallo storage.
- `Delete($user=null)`: Elimina la risorsa dal database e il file associato dallo storage, con controllo dei permessi.

### Class: `AA_Servizio`

**File:** `utils/system_lib/AA_Servizio.php`
**SHA256:** `2ca661b0e735748dff6adc4c55bef13d29ad8a6edf521abe9eff05b358a62646`

#### Description
La classe `AA_Servizio` rappresenta un servizio all'interno di una struttura organizzativa. Estende la classe base `AA_Struttura`, ereditando le sue funzionalità generiche per la gestione delle strutture.

#### Properties
- `$dbDataTable`: (static protected) Il nome della tabella del database associata a questa classe, "servizi".
- `$ObjectClass`: (static protected) Riferimento al nome della classe corrente, `AA_Servizio`.
- `descrizione`: Una stringa che descrive il servizio.
- `id_direzione`: L'ID della direzione a cui questo servizio è associato.
- `data_istituzione`: La data di istituzione del servizio (formato `YYYY-MM-DD`).
- `data_soppressione`: La data in cui il servizio è stato soppresso (valore predefinito `9999-12-31`).

#### Methods
- `__construct($params = null)`: Costruttore della classe. Inizializza le proprietà specifiche del servizio e chiama il costruttore della classe padre (`AA_Struttura`) per inizializzare le proprietà ereditate.

### Class: `AA_Struttura`

**File:** `utils/system_lib/AA_Struttura.php`
**SHA256:** `427f4178e927046acaf101a8ba31adc48e56931d022e318e5b84b5842bb3d6ee`

#### Description
La classe `AA_Struttura` è una classe base generica per la gestione delle strutture organizzative (come assessorati, direzioni, servizi) all'interno del sistema. Estende `AA_GenericParsableDbObject` per fornire funzionalità di persistenza nel database e include logica per la gestione dei permessi di eliminazione.

#### Properties
- `$dbDataTable`: (static protected) Il nome della tabella del database associata a questa classe, "assessorati". Questo suggerisce che `AA_Struttura` serve come base per entità come gli assessorati, che a loro volta contengono direzioni e servizi.
- `$dbClass`: (static protected) La classe del database utilizzata per le operazioni, "AA_AccountsDatabase".
- `$ObjectClass`: (static protected) Riferimento al nome della classe corrente, `AA_Struttura`.
- `descrizione`: Una stringa che descrive la struttura.
- `aggiornamento`: La data dell'ultimo aggiornamento della struttura (formato `YYYY-MM-DD`).
- `data_istituzione`: La data di istituzione della struttura (formato `YYYY-MM-DD`).
- `data_soppressione`: La data in cui la struttura è stata soppressa (valore predefinito `9999-12-31`).
- `web`: Informazioni o link web associati alla struttura.

#### Methods
- `__construct($params = null)`: Costruttore della classe. Inizializza le proprietà predefinite della struttura e chiama il costruttore della classe padre (`AA_GenericParsableDbObject`).
- `Delete($user=null)`: Gestisce la logica di eliminazione di una struttura. Include complessi controlli sui permessi dell'utente corrente (`AA_User`) per assicurarsi che l'utente sia autorizzato a eliminare quella specifica struttura. Se tutti i controlli sui permessi passano, delega l'effettiva eliminazione dal database al metodo `DeleteFromDb()` della classe padre.

### Class: `AA_SystemTask_AMAAI_Start`

**File:** `utils/system_lib/AA_SystemTask_AMAAI_Start.php`
**SHA256:** `c77ec490618ae6b5ff07be8d8870aa0c8e8f4044849c062f5b7e8df226d8113e`

#### Description
La classe `AA_SystemTask_AMAAI_Start` rappresenta un task di sistema che avvia l'interfaccia AMAAI (Navigazione Assistita). Estende `AA_GenericTask`.

#### Key Features
- **Avvio Interfaccia AMAAI:** Inizializza e configura la visualizzazione iniziale della navigazione assistita.
- **Integrazione Modulo:** Utilizza l'istanza di `AA_AMAAI` per ottenere il layout e il contenuto da visualizzare.
- **Logging Task:** Registra le azioni del task.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "AMAAI_Start" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Ottiene l'istanza di `AA_AMAAI`, configura le dimensioni della finestra del template, aggiunge la vista iniziale e imposta il log del task con il contenuto codificato in base64.

### Class: `AA_SystemTask_ChangeCurrentUserProfile`

**File:** `utils/system_lib/AA_SystemTask_ChangeCurrentUserProfile.php`
**SHA256:** `c162f506eaa994d2832672bddedca42dacfe3295028a6bab2c74392a4fe1ac99`

#### Description
La classe `AA_SystemTask_ChangeCurrentUserProfile` gestisce il task di modifica del profilo dell'utente corrente. Estende `AA_GenericTask`.

#### Key Features
- **Verifica Utente:** Assicura che l'utente corrente sia valido e non sia un utente ospite prima di procedere con la modifica del profilo.
- **Modifica Profilo:** Tenta di modificare il profilo dell'utente utilizzando la logica definita in `AA_User::ChangeProfile()`.
- **Feedback Dinamico:** In caso di successo, imposta un'azione per ricaricare l'applicazione; in caso di errore, registra un messaggio di errore nel log.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "ChangeCurrentUserProfile" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Include la verifica dell'utente e la chiamata al metodo di modifica del profilo. Imposta il log del task con lo stato e, se applicabile, un'azione di ricarica o un messaggio di errore.

### Class: `AA_SystemTask_GetAppStatus`

**File:** `utils/system_lib/AA_SystemTask_GetAppStatus.php`
**SHA256:** `4e38628d87fcb51821224ce7046477c7617ae62645ada654119729b77262ba18`

#### Description
La classe `AA_SystemTask_GetAppStatus` è un task di sistema che recupera lo stato attuale dell'applicazione. Estende `AA_GenericTask`.

#### Key Features
- **Gestione Stato Sessione:** Aggiorna i parametri di sessione relativi al dispositivo mobile (`mobile`), larghezza (`viewport_width`) e altezza (`viewport_height`) del viewport in base alle richieste.
- **Dati Utente:** Recupera e include le informazioni sull'utente corrente nel log del task.
- **Informazioni Moduli Registrati:** Recupera un elenco dei moduli registrati all'interno della piattaforma, filtra quelli visibili all'utente corrente e include i dettagli per la costruzione della sidebar dell'interfaccia utente.
- **Output XML:** Il risultato del task è formattato come XML, contenente lo stato dell'applicazione, i dati dell'utente e le configurazioni dei moduli.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetAppStatus" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Aggiorna le variabili di sessione, recupera le informazioni dell'utente e i moduli registrati, quindi costruisce e imposta il log del task con le informazioni sullo stato dell'applicazione.

### Class: `AA_SystemTask_GetChangeCurrentUserProfileDlg`

**File:** `utils/system_lib/AA_SystemTask_GetChangeCurrentUserProfileDlg.php`
**SHA256:** `f653b096c1edcc030d657451131092ecee50aba66ffffcf58dffecf9a268f01b`

#### Description
La classe `AA_SystemTask_GetChangeCurrentUserProfileDlg` è un task di sistema che gestisce la visualizzazione di una finestra di dialogo per consentire all'utente corrente di cambiare il proprio profilo. Estende `AA_GenericTask`.

#### Key Features
- **Verifica Utente:** Assicura che l'utente corrente sia valido e non sia un utente ospite prima di mostrare la finestra di dialogo.
- **Generazione Finestra di Dialogo:** Istanzia e configura un oggetto `AA_SystemChangeCurrentUserProfileDlg` per creare la finestra di dialogo.
- **Output Codificato:** L'output del task è la rappresentazione codificata in base64 della finestra di dialogo, incapsulata in un XML di stato e contenuto.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetChangeCurrentUserProfileDlg" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Verifica l'utente, genera la finestra di dialogo per il cambio profilo e imposta il log del task con la rappresentazione base64 della finestra di dialogo.

### Class: `AA_SystemTask_GetChangeCurrentUserPwdDlg`

**File:** `utils/system_lib/AA_SystemTask_GetChangeCurrentUserPwdDlg.php`
**SHA256:** `316f7e5551e6e98dfeee40ac39d5bbdcdba29fadcc628d640b7cbc5dac1ae823`

#### Description
La classe `AA_SystemTask_GetChangeCurrentUserPwdDlg` è un task di sistema che gestisce la visualizzazione di una finestra di dialogo per consentire all'utente corrente di cambiare la propria password. Estende `AA_GenericTask`.

#### Key Features
- **Verifica Utente:** Assicura che l'utente corrente sia valido e non sia un utente ospite prima di procedere.
- **Invio OTP:** Invia un One-Time Password (OTP) all'utente via email per la verifica, utilizzando `AA_User::MailOTPChangePwdChallenge()`.
- **Generazione Finestra di Dialogo:** Istanzia e configura un oggetto `AA_SystemChangeCurrentUserPwdDlg` per creare la finestra di dialogo di reimpostazione della password.
- **Output Codificato:** L'output del task è la rappresentazione codificata in base64 della finestra di dialogo, incapsulata in un XML di stato e contenuto.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetChangeCurrentUserPwdDlg" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Verifica l'utente, invia l'OTP per la sfida di cambio password, genera la finestra di dialogo di cambio password e imposta il log del task con la rappresentazione base64 della finestra di dialogo.

### Class: `AA_SystemTask_GetCurrentUserProfileDlg`

**File:** `utils/system_lib/AA_SystemTask_GetCurrentUserProfileDlg.php`
**SHA256:** `c0da10479204b5830a343352e7f5ea37071a1cb1c6e2fbc5c65f961a4bdc90c4`

#### Description
La classe `AA_SystemTask_GetCurrentUserProfileDlg` è un task di sistema che gestisce la visualizzazione di una finestra di dialogo per consentire all'utente corrente di visualizzare e potenzialmente modificare le informazioni del proprio profilo. Estende `AA_GenericTask`.

#### Key Features
- **Verifica Utente:** Assicura che l'utente corrente sia valido e non sia un utente ospite prima di mostrare la finestra di dialogo.
- **Generazione Finestra di Dialogo:** Istanzia e configura un oggetto `AA_SystemCurrentUserProfileDlg` per creare la finestra di dialogo del profilo utente.
- **Output Codificato:** L'output del task è la rappresentazione codificata in base64 della finestra di dialogo, incapsulata in un XML di stato e contenuto.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetCurrentUserProfileDlg" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Verifica l'utente, genera la finestra di dialogo del profilo utente e imposta il log del task con la rappresentazione base64 della finestra di dialogo.

### Class: `AA_SystemTask_GetGalleryData`

**File:** `utils/system_lib/AA_SystemTask_GetGalleryData.php`
**SHA256:** `d47a1f922f7c109e7bb07736342f5d59033097502f60fc3d3adc954a5ce95ba8`

#### Description
La classe `AA_SystemTask_GetGalleryData` è un task di sistema che recupera i dati delle immagini per una galleria. Estende `AA_GenericTask`.

#### Key Features
- **Recupero Immagini con Paginazione:** Interroga il database per recuperare un subset di immagini (`AA_Risorse` con categoria 'galleria') basandosi su parametri di paginazione (start e count).
- **Output JSON:** Restituisce i dati delle immagini in formato JSON, includendo la posizione corrente, il conteggio totale e i dettagli di ciascuna immagine (ID, URL dell'immagine, nome URL).
- **Gestione Errori Database:** Logga gli errori relativi alle query del database.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetGalleryData" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Include la logica per il conteggio totale delle immagini, il recupero dei dati delle immagini con paginazione e la formattazione dell'output in JSON. Il task termina con una `die()` che stampa direttamente l'output JSON.

### Class: `AA_SystemTask_GetGalleryDlg`

**File:** `utils/system_lib/AA_SystemTask_GetGalleryDlg.php`
**SHA256:** `4692cbf997e18b7356dac83bb38009d03115ab062967d7848889cdf8d0ba6721`

#### Description
La classe `AA_SystemTask_GetGalleryDlg` è un task di sistema che gestisce la visualizzazione di una finestra di dialogo per la selezione di immagini da una galleria. Estende `AA_GenericTask`.

#### Key Features
- **Visualizzazione Galleria:** Istanzia e configura un oggetto `AA_GalleryDlg` per presentare una finestra di dialogo della galleria di immagini.
- **Output Codificato:** L'output del task è la rappresentazione codificata in base64 della finestra di dialogo, incapsulata in un XML di stato e contenuto.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetGalleryDlg" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Crea la finestra di dialogo della galleria immagini, passando il target dal parametro `$_REQUEST`, e imposta il log del task con la rappresentazione base64 della finestra di dialogo.

### Class: `AA_SystemTask_GetGalleryTrashDlg`

**File:** `utils/system_lib/AA_SystemTask_GetGalleryTrashDlg.php`
**SHA256:** `ad446cdb9e6e1b027e747211486be6d022c5dc352fdfe15466265c5995096da1`

#### Description
La classe `AA_SystemTask_GetGalleryTrashDlg` è un task di sistema che gestisce la visualizzazione di una finestra di dialogo per la gestione del cestino delle immagini (`AA_Risorse`) presenti nella galleria. Estende `AA_GenericTask`.

#### Key Features
- **Verifica Permessi Utente:** Assicura che l'utente corrente sia un "SuperUser" prima di procedere.
- **Validazione Input:** Verifica che un ID immagine sia fornito nella richiesta.
- **Caricamento Risorsa Immagine:** Tenta di caricare l'oggetto `AA_Risorse` corrispondente all'ID fornito.
- **Generazione Finestra di Dialogo Cestino:** Istanzia e configura un oggetto `AA_GalleryTrashDlg` per presentare la finestra di dialogo del cestino, passando l'immagine caricata.
- **Output Codificato:** L'output del task è la rappresentazione codificata in base64 della finestra di dialogo, incapsulata in un XML di stato e contenuto.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetGalleryTrashDlg" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Include i controlli sui permessi e sull'input, il caricamento della risorsa immagine, la creazione della finestra di dialogo del cestino e l'impostazione del log del task con la rappresentazione base64 della finestra di dialogo.

### Class: `AA_SystemTask_GetLogDlg`

**File:** `utils/system_lib/AA_SystemTask_GetLogDlg.php`
**SHA256:** `1ba7f17a46c6a484e5e175874e98cbb558d264b81249efedb0efdc3158588e2e`

#### Description
La classe `AA_SystemTask_GetLogDlg` è un task di sistema che gestisce la visualizzazione di una finestra di dialogo per mostrare i log relativi a un oggetto specifico. Estende `AA_GenericTask`.

#### Key Features
- **Visualizzazione Log:** Istanzia e configura un oggetto `AA_GenericLogDlg` per presentare una finestra di dialogo che mostra i log. La finestra di dialogo è identificata da un ID basato sul parametro `$_REQUEST['id']`.
- **Output Codificato:** L'output del task è la rappresentazione codificata in base64 della finestra di dialogo, incapsulata in un XML di stato e contenuto.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetLogDlg" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Crea la finestra di dialogo per i log, utilizzando un ID dinamico e l'utente corrente, e imposta il log del task con la rappresentazione base64 della finestra di dialogo.

### Class: `AA_SystemTask_GetNews`

**File:** `utils/system_lib/AA_SystemTask_GetNews.php`
**SHA256:** `aa33b2c5e75843e61c9750618c53b93167e16e240937af1c9315ce73266ffdc6`

#### Description
La classe `AA_SystemTask_GetNews` è un task di sistema che recupera lo stato del server e altre "notizie" (in questo contesto, sembra riferirsi alle statistiche del server) e le presenta all'utente. Estende `AA_GenericTask`.

#### Key Features
- **Verifica Permessi Utente:** Controlla se l'utente corrente è un "SuperUser" per determinare se può accedere alle statistiche del server.
- **Recupero Statistiche Server:** Se autorizzato, tenta di recuperare le statistiche del server da `http://localhost/server-status` utilizzando cURL.
- **Gestione Errori cURL:** Gestisce eventuali errori HTTP o di connessione durante il recupero delle statistiche.
- **Output JSON:** Restituisce le statistiche del server (o un messaggio di errore) in formato JSON.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetNews" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Controlla i permessi dell'utente, esegue la richiesta cURL per le statistiche del server e prepara l'output JSON con lo stato e il contenuto (statistiche o messaggio di errore). Il task termina con una `die()` che stampa direttamente l'output JSON.

### Class: `AA_SystemTask_GetPdfPreviewDlg`

**File:** `utils/system_lib/AA_SystemTask_GetPdfPreviewDlg.php`
**SHA256:** `57f02ff695448bc956a4ceed34676cac6022183d06e4fab5ee619867f318e94b`

#### Description
La classe `AA_SystemTask_GetPdfPreviewDlg` è un task di sistema che gestisce la visualizzazione di una finestra di dialogo per l'anteprima di un documento PDF. Estende `AA_GenericTask`.

#### Key Features
- **Visualizzazione Anteprima PDF:** Istanzia e configura un oggetto `AA_GenericPdfPreviewDlg` per presentare una finestra di dialogo dedicata all'anteprima PDF.
- **Output Codificato:** L'output del task è la rappresentazione codificata in base64 della finestra di dialogo, incapsulata in un XML di stato e contenuto.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetPdfPreviewDlg" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Crea la finestra di dialogo per l'anteprima PDF e imposta il log del task con la rappresentazione base64 della finestra di dialogo.

### Class: `AA_SystemTask_GetServerStatusDlg`

**File:** `utils/system_lib/AA_SystemTask_GetServerStatusDlg.php`
**SHA256:** `a16913789df5b0ae88e1a88d0da4dcfb82be3f04a4cd750d1ee44bee67b2fdcb`

#### Description
La classe `AA_SystemTask_GetServerStatusDlg` è un task di sistema che gestisce la visualizzazione di una finestra di dialogo per mostrare lo stato del server. Estende `AA_GenericTask`.

#### Key Features
- **Visualizzazione Stato Server:** Istanzia e configura un oggetto `AA_GenericServerStatusDlg` per presentare una finestra di dialogo che mostra lo stato del server. La finestra di dialogo è identificata da un ID basato sul parametro `$_REQUEST['id']`.
- **Output Codificato:** L'output del task è la rappresentazione codificata in base64 della finestra di dialogo, incapsulata in un XML di stato e contenuto.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetServerStatusDlg" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Crea la finestra di dialogo per lo stato del server, utilizzando un ID dinamico e l'utente corrente, e imposta il log del task con la rappresentazione base64 della finestra di dialogo.

### Class: `AA_SystemTask_GetServerStatus`

**File:** `utils/system_lib/AA_SystemTask_GetServerStatus.php`
**SHA256:** `26befd652c113d5fbc765c0fa1545bfc62e1fee5e7977255a9662a3121ea0699`

#### Description
La classe `AA_SystemTask_GetServerStatus` è un task di sistema che recupera e restituisce lo stato del server. Estende `AA_GenericTask`.

#### Key Features
- **Verifica Permessi Utente:** Controlla se l'utente corrente è un "SuperUser" per determinare se può accedere alle statistiche del server.
- **Recupero Statistiche Server:** Se autorizzato, tenta di recuperare le statistiche del server da `http://localhost/server-status` utilizzando cURL.
- **Gestione Errori cURL:** Gestisce eventuali errori HTTP o di connessione durante il recupero delle statistiche.
- **Output JSON:** Restituisce le statistiche del server (o un messaggio di errore) in formato JSON.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetServerStatus" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Controlla i permessi dell'utente, esegue la richiesta cURL per le statistiche del server e prepara l'output JSON con lo stato e il contenuto (statistiche o messaggio di errore). Il task termina con una `die()` che stampa direttamente l'output JSON.

### Class: `AA_SystemTask_GetSideMenuContent`

**File:** `utils/system_lib/AA_SystemTask_GetSideMenuContent.php`
**SHA256:** `6c62d8d7f0f9cd512515bb08d2163ed4c772e99dd8c0b83583eaf5e6cc661ca3`

#### Description
La classe `AA_SystemTask_GetSideMenuContent` è un task di sistema che recupera e formatta il contenuto del menu laterale (sidebar) per l'utente corrente. Estende `AA_GenericTask`.

#### Key Features
- **Recupero Moduli:** Utilizza l'istanza di `AA_Platform` per ottenere i moduli registrati e visibili all'utente.
- **Costruzione SideMenu:** Costruisce un array di dati che rappresenta gli elementi del menu laterale, includendo ID, icona, valore (nome), tooltip, tipo ("section"), modulo associato e sezione (id_modulo).
- **Output Codificato:** Il contenuto del menu laterale è codificato in JSON e poi in base64, e viene inserito nel log del task.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetSideMenuContent" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Recupera i moduli, costruisce il contenuto della sidebar e imposta il log del task con la rappresentazione base64 del JSON del menu laterale.

### Class: `AA_SystemTask_GetStructDlg`

**File:** `utils/system_lib/AA_SystemTask_GetStructDlg.php`
**SHA256:** `98a96807d6b642820db4394a8c03538994c04692c7a443d7a7485d98744a8445`

#### Description
La classe `AA_SystemTask_GetStructDlg` è un task di sistema che gestisce la visualizzazione di una finestra di dialogo per la selezione di una struttura organizzativa gerarchica. Estende `AA_GenericTask`.

#### Key Features
- **Visualizzazione Struttura Gerarchica:** Istanzia e configura un oggetto `AA_GenericStructDlg` per presentare una finestra di dialogo che consente la navigazione e la selezione all'interno di una struttura organizzativa (es. assessorati, direzioni, servizi).
- **Parametri Dinamici:** La finestra di dialogo può essere configurata con parametri dalla richiesta (ad esempio, un modulo specifico) e con i dettagli dell'utente corrente.
- **Output Codificato:** L'output del task è la rappresentazione codificata in base64 della finestra di dialogo, incapsulata in un XML di stato e contenuto.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetStructDlg" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Crea la finestra di dialogo per la struttura, gestendo i parametri della richiesta e l'utente corrente, e imposta il log del task con la rappresentazione base64 della finestra di dialogo.

### Class: `AA_SystemTask_GetStructTreeData`

**File:** `utils/system_lib/AA_SystemTask_GetStructTreeData.php`
**SHA256:** `94b9d038c84ae684b5a3f78046f5a20e0c120e9bb5cd92c721d2834149ec7590`

#### Description
La classe `AA_SystemTask_GetStructTreeData` è un task di sistema che recupera e formatta i dati di una struttura organizzativa gerarchica, tipicamente per essere visualizzati in un controllo ad albero. Estende `AA_GenericTask`.

#### Key Features
- **Recupero Dati Struttura:** Ottiene i dati della struttura organizzativa tramite la classe `AA_Struct`, tenendo conto dei permessi dell'utente corrente e di vari parametri di filtro (`showAll`, `showAllDir`, `showAllServ`, `show_suppressed`) provenienti dalla richiesta.
- **Supporto Filtri:** Consente di filtrare la visualizzazione della struttura per mostrare tutti gli elementi, solo le direzioni, solo i servizi, o nascondere gli elementi soppressi.
- **Output JSON:** I dati della struttura sono formattati come un array JSON, rendendoli facilmente consumabili da componenti frontend che visualizzano alberi.

#### Methods
- `__construct($user = null)`: Costruttore della classe. Inizializza il task con il nome "GetStructTreeData" e l'utente corrente.
- `Run()`: Il metodo principale che esegue la logica del task. Recupera le informazioni sulla struttura organizzativa in base ai parametri della richiesta e all'utente, e imposta il log del task con la rappresentazione JSON di questi dati.
