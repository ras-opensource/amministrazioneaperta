# Guida alle Sezioni Personalizzate nei Moduli

Questa guida spiega come aggiungere sezioni personalizzate ai moduli in Amministrazione Aperta.

## Concetti Base

Una **sezione** in AA rappresenta una vista/pagina separata all'interno di un modulo. Ogni modulo pu avere:
- Sezioni predefinite: `Bozze`, `Pubblicate`, `Detail`, `Desktop`
- Sezioni personalizzate: create ad-hoc per funzionalita specifiche

## Pattern dei Task

Tutti i metodi che gestiscono i task in un modulo ricevono come primo parametro un oggetto `AA_GenericModuleTask` che deve essere utilizzato per restituire i valori di ritorno.

### Classe AA_GenericModuleTask

La classe `AA_GenericModuleTask` (in `utils/system_lib/AA_GenericModuleTask.php`) mette a disposizione i seguenti metodi:

| Metodo | Descrizione |
|--------|-------------|
| `$task->SetContent($content, $json, $encode)` | Imposta il contenuto della risposta |
| `$task->SetStatus($status)` | Imposta lo stato (0 = successo, -1 = errore, -2 = ...) |
| `$task->SetError($error, $json, $encode)` | Imposta un messaggio di errore |
| `$task->SetStatusAction($action, $params, $json_encode)` | Imposta un'azione da eseguire dopo il task |
| `$task->GetName()` | Restituisce il nome del task |
| `$task->GetLog()` | Restituisce il log completo della risposta |

### Firma dei Metodi Task

Tutti i metodi Task devono seguire questa firma:

```php
public function Task_NomeTask($task)
{
    // Leggi i parametri dalla request globale
    $params = $_REQUEST;
    
    // Implementa la logica del task
    
    // Restituisci il risultato usando i metodi del $task
    $task->SetStatus(0);  // Successo
    $task->SetStatusAction("refreshCurSection", null, true);
    return true;
    
    // In caso di errore:
    $task->SetError("Messaggio di errore");
    $task->SetStatus(-1);
    return false;
}
```

### Metodi SetContent

Per restituire un template UI:

```php
// Restituisce un template (già serializzato come JSON)
$task->SetContent($template, true, false);

// Restituisce un array
$task->SetContent($dataArray, true, false);

// Restituisce contenuto con codifica base64
$task->SetContent($content, false, true);
```

### Metodo SetStatusAction

Per eseguire azioni dopo il task:

```php
// Ricarica la sezione corrente
$task->SetStatusAction("refreshCurSection", null, true);

// Ricarica un oggetto specifico
$task->SetStatusAction("refreshUiObject", array("id" => "mio_oggetto_id"), true);

// Ricarica la sezione
$task->SetStatusAction("setCurrentSection", array("section" => "mia_sezione"), true);
```

## Struttura di una Sezione

### Costanti della Sezione

Ogni sezione richiede la definizione di costanti nella classe del modulo:

```php
// Esempio: sezione "Gestione Utenti"

// ID univoco della sezione
const AA_ID_SECTION_UTENTI = "GestUtenti";

// ID del box UI
const AA_UI_SECTION_UTENTI_BOX = "GestUtentiBox";

// Nome visualizzato
const AA_UI_SECTION_UTENTI_NAME = "Gestione utenti";

// Icona (Material Design Icons)
const AA_UI_SECTION_UTENTI_ICON = "mdi mdi-account-group";

// Descrizione (per desktop/dashboard)
const AA_UI_SECTION_UTENTI_DESC = "Visualizza e gestisci gli utenti";

// Tooltip
const AA_UI_SECTION_UTENTI_TOOLTIP = "Gestisci gli utenti del sistema";
```

### Creazione della Sezione

Nel costruttore del modulo, dopo `parent::__construct()`, aggiungi:

```php
#----------------------- Gestione utenti -------------------------
$section = new AA_GenericModuleSection(
    static::AA_ID_SECTION_UTENTI,           // ID sezione
    static::AA_UI_SECTION_UTENTI_NAME,      // Nome visualizzato
    true,                                    // Visibile nella navbar
    static::AA_UI_PREFIX."_".static::AA_UI_SECTION_UTENTI_BBOX, // View ID
    $this->GetId(),                         // ID modulo
    false,                                   // Non e sezione default
    true,                                    // Abilita refresh view
    false,                                   // Non e sezione dettaglio
    false,                                   // Non richiede validazione
    static::AA_UI_SECTION_UTENTI_ICON,      // Icona
    "TemplateSection_Utenti"                // Nome metodo template
);

// Imposta la navbar (link di navigazione)
$section->SetNavbarTemplate(array(
    $this->TemplateGenericNavbar_Desktop(1, true, true)->toArray()
));

// Aggiungi la sezione al modulo
$this->AddSection($section);
#---------------------------------------------------------------
```

### Firma del Costruttore AA_GenericModuleSection

```php
public function __construct(
    $id,           // ID univoco della sezione
    $name,         // Nome visualizzato
    $navbar,       // Visibile nella navbar (bool)
    $view_id,      // ID del box UI
    $module_id,    // ID del modulo
    $default,      // Sezione default (bool)
    $refresh_view, // Abilita refresh (bool)
    $detail,       // Sezione dettaglio (bool)
    $valid,        // Richiede validazione (bool)
    $icon,         // Icona (string)
    $template      // Nome metodo template (string)
)
```

## Implementazione del Template della Sezione

Ogni sezione deve implementare il metodo template corrispondente:

```php
// Template section utenti
public function TemplateSection_Utenti($params = array())
{
    $id = static::AA_UI_PREFIX."_".static::AA_ID_SECTION_UTENTI;
    $canModify = $this->oUser->HasFlag(AA_User::CAN_ADMIN);
    
    // Cerca i dati
    $utenti = AA_Utente::Search($params);
    
    // Prepara i dati per la datatable
    $data = [];
    foreach ($utenti as $curUtente) {
        $ops = "";
        if ($canModify) {
            $detail = 'AA_MainApp.utils.callHandler("dlg", 
                {task:"GetUtenteDetailDlg", params: [{id:"'.$curUtente->GetProp("id").'"}]},
                "'.$this->id.'")';
            $modify = 'AA_MainApp.utils.callHandler("dlg", 
                {task:"GetUtenteModifyDlg", params: [{id:"'.$curUtente->GetProp("id").'"}]},
                "'.$this->id.'")';
            
            $ops = "<div class='AA_DataTable_Ops'>
                <a class='AA_DataTable_Ops_Button' title='Dettagli' onClick='".$detail."'>
                    <span class='mdi mdi-eye'></span>
                </a>
                <a class='AA_DataTable_Ops_Button' title='Modifica' onClick='".$modify."'>
                    <span class='mdi mdi-pencil'></span>
                </a>
            </div>";
        }
        
        $data[] = array(
            "id" => $curUtente->GetProp("id"),
            "nome" => $curUtente->GetProp("nome"),
            "email" => $curUtente->GetProp("email"),
            "stato" => $curUtente->GetProp("attivo") ? "Attivo" : "Disattivo",
            "ops" => $ops
        );
    }
    
    // Crea il template datatable
    $template = new AA_GenericDatatableTemplate(
        $id,
        "",                              // ID popup
        4,                               // Numero colonne
        array(
            "type" => "clean",
            "name" => static::AA_UI_SECTION_UTENTI_NAME
        ),
        array(
            "css" => "AA_Header_DataTable",
            "filtered" => true,
            "filter_id" => $id
        )
    );
    
    // Configura la datatable
    $template->EnableScroll(false, true);
    $template->EnableRowOver();
    $template->EnableHeader(true);
    $template->SetHeaderHeight(38);
    
    // Abilita aggiunta nuovo record (se permesso)
    if ($canModify) {
        $template->EnableAddNew(true, "GetUtenteAddNewDlg");
        $template->SetAddNewTaskParams(array("postParams" => array("refresh" => 1)));
    }
    
    // Configura le colonne
    $template->SetColumnHeaderInfo(0, "nome", "Nome", 200, "textFilter", "text", "left");
    $template->SetColumnHeaderInfo(1, "email", "Email", 250, "textFilter", "text", "left");
    $template->SetColumnHeaderInfo(2, "stato", "Stato", 100, "selectFilter", "text", "center");
    $template->SetColumnHeaderInfo(3, "ops", "Operazioni", 120, null, null, "center");
    
    // Imposta i dati
    $template->SetData($data);
    
    return $template;
}
```

## Tipologie di Sezioni

### 1. Sezione con Datatable (CRUD)

La tipologia piu comune. Implementa una tabella con operazioni CRUD.

Vedi esempio: `TemplateSection_Immobili` in `sicar_9/lib.php:7554`

### 2. Sezione Desktop/Dashboard

Sezione che mostra una dashboard con statistiche e link ad altre sezioni.

```php
public function TemplateSection_Desktop()
{
    $id = static::AA_UI_PREFIX."_".static::AA_UI_SECTION_DESKTOP_BOX;
    $layout = new AA_JSON_Template_Layout($id, array(
        "type" => "clean",
        "name" => static::AA_UI_SECTION_DESKTOP_NAME
    ));
    
    // Riga con statistiche
    $stats_row = new AA_JSON_Template_Layout("", array("type" => "space"));
    $stats_row->AddCol($this->TemplateSection_Stats());
    $layout->AddRow($stats_row);
    
    // Riga con link ai moduli
    $modules_row = new AA_JSON_Template_Layout("", array("type" => "space"));
    $modules = array(
        array("id_section" => static::AA_ID_SECTION_UTENTI, "icon" => static::AA_UI_SECTION_UTENTI_ICON, "label" => static::AA_UI_SECTION_UTENTI_NAME),
        // ... altri moduli
    );
    
    foreach ($modules as $curMod) {
        $onclick = "AA_MainApp.utils.callHandler('setCurrentSection', '".$curMod['id_section']."', '".$this->GetId()."')';
        $modules_row->AddCol(new AA_JSON_Template_Template("", array(
            "template" => "<div class='module_card' onclick=\"$onclick\">...</div>",
            "data" => array("icon" => $curMod['icon'], "label" => $curMod['label'])
        )));
    }
    $layout->AddRow($modules_row);
    
    return $layout;
}
```

### 3. Sezione con Layout Complesso

Sezione con piu pannelli e widget.

```php
public function TemplateSection_Report($params = array())
{
    $id = static::AA_UI_PREFIX."_".static::AA_ID_SECTION_REPORT;
    
    // Layout principale
    $layout = new AA_JSON_Template_Layout($id, array(
        "type" => "clean",
        "name" => "Report e Statistiche"
    ));
    
    // Prima riga: filtri
    $filter_row = new AA_JSON_Template_Layout("", array("type" => "line"));
    $filter_row->AddCol($this->TemplateSection_Report_Filters($params));
    $layout->AddRow($filter_row);
    
    // Seconda riga: grafici affiancati
    $charts_row = new AA_JSON_Template_Layout("", array("type" => "space"));
    $charts_row->AddCol($this->TemplateSection_Report_Chart1());
    $charts_row->AddCol($this->TemplateSection_Report_Chart2());
    $layout->AddRow($charts_row);
    
    // Terza riga: tabella dati
    $table_row = new AA_JSON_Template_Layout("", array("type" => "line"));
    $table_row->AddCol($this->TemplateSection_Report_Table($params));
    $layout->AddRow($table_row);
    
    return $layout;
}
```

## Personalizzazione della Navbar

Ogni sezione pu avere una navbar personalizzata che appare nella parte superiore:

```php
// Navbar che mostra solo il link al desktop
$section->SetNavbarTemplate(array(
    $this->TemplateGenericNavbar_Desktop(1, true, true)->toArray()
));

// Navbar con piu link
$section->SetNavbarTemplate(array(
    $this->TemplateGenericNavbar_Section($desktop, 1)->toArray(),
    $this->TemplateGenericNavbar_Pubblicate(2, true)->toArray()
));
```

Metodi disponibili per la navbar:
- `TemplateGenericNavbar_Desktop()` - Link al desktop
- `TemplateGenericNavbar_Void()` - Navbar vuota
- `TemplateGenericNavbar_Section($section, $level)` - Link a sezione
- `TemplateGenericNavbar_Bozze($level, $last)` - Link a bozze
- `TemplateGenericNavbar_Pubblicate($level, $last)` - Link a pubblicate

## Integrazione con Task e Dialog

Per le operazioni CRUD nella sezione, sono necessari i task corrispondenti:

```php
// Registra i task nel costruttore
$taskManager->RegisterTask("GetUtenteAddNewDlg");
$taskManager->RegisterTask("GetUtenteDetailDlg");
$taskManager->RegisterTask("GetUtenteModifyDlg");
$taskManager->RegisterTask("GetUtenteDeleteDlg");
$taskManager->RegisterTask("AddNewUtente");
$taskManager->RegisterTask("ModifyUtente");
$taskManager->RegisterTask("DeleteUtente");
```

Implementa i metodi task:

```php
// Dialog per aggiunta nuovo utente
public function Task_GetUtenteAddNewDlg($task)
{
    $template = new AA_GenericFormDlg(
        static::AA_UI_PREFIX . "_AddNewUtente",
        "Nuovo Utente",
        $this->GetId(),
        "AddNewUtente",
        "1500"
    );
    
    $template->AddElement(array(
        "view" => "text",
        "name" => "nome",
        "label" => "Nome",
        "required" => true
    ));
    
    $template->AddElement(array(
        "view" => "text",
        "name" => "email",
        "label" => "Email",
        "required" => true
    ));
    
    $template->AddElement(array(
        "view" => "switch",
        "name" => "attivo",
        "label" => "Attivo",
        "value" => 1,
        "onLabel" => "Si",
        "offLabel" => "No"
    ));
    
    // Imposta il contenuto nel task
    $task->SetContent($template, true, false);
    
    return true;
}

// Task per inserimento
public function Task_AddNewUtente($task)
{
    $params = $_REQUEST;
    
    // Validazione
    if (empty($params['nome']) || empty($params['email'])) {
        $task->SetError("Campi obbligatori mancanti");
        $task->SetStatus(-1);
        return false;
    }
    
    // Creazione oggetto
    $utente = new AA_Utente();
    $utente->SetProp("nome", $params['nome']);
    $utente->SetProp("email", $params['email']);
    $utente->SetProp("attivo", $params['attivo'] ?? 1);
    
    // Salvataggio
    if ($utente->Save()) {
        $task->SetStatus(0);
        $task->SetStatusAction("refreshCurSection", null, true);
        return true;
    }
    
    $task->SetError("Errore durante il salvataggio");
    $task->SetStatus(-1);
    return false;
}
```

## Sezione Detail Personalizzata

Per personalizzare la sezione dettaglio (quando si visualizza un record specifico):

```php
// Definisci i tab nel costruttore
$this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL, array(
    array(
        "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX,
        "value" => "Generale",
        "tooltip" => "Dati generali",
        "template" => "TemplateSicarDettaglio_Generale_Tab"
    ),
    array(
        "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_DOCUMENTI_BOX,
        "value" => "Documenti",
        "tooltip" => "Documenti allegati",
        "template" => "TemplateSicarDettaglio_Documenti_Tab"
    )
));

// Implementa il template del tab
public function TemplateSicarDettaglio_Generale_Tab($params)
{
    $id = static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX;
    $object = $this->GetFocusedObject();
    
    $template = new AA_JSON_Template_Layout($id, array("type" => "clean"));
    
    // ... costruisci il layout dei dettagli
    
    return $template;
}
```

## Best Practices

1. **Naming convention**: Usa nomi consistenti per le costanti
   - `AA_ID_SECTION_<NOME>` per l'ID
   - `AA_UI_SECTION_<NOME>_NAME` per il nome
   - `AA_UI_SECTION_<NOME>_ICON` per l'icona
   - `AA_UI_SECTION_<NOME>_BOX` per il box ID

2. **Separazione delle responsabilita**: Mantieni il template della sezione in un metodo separato

3. **Permessi**: Verifica sempre i permessi utente prima di mostrare operazioni di modifica

4. **Performance**: Usa la paginazione e i filtri per dataset grandi

5. **Navbar**: Configura sempre una navbar appropriata per la sezione

## Esempio Completo

Vedi il modulo `sicar_9` per un esempio completo con multiple sezioni:
- `TemplateSection_Desktop()` - Dashboard
- `TemplateSection_Immobili()` - Datatable CRUD
- `TemplateSection_Enti()` - Datatable CRUD
- `TemplateSection_Nuclei()` - Datatable CRUD
- `TemplateSection_Finanziamenti()` - Datatable CRUD
- `TemplateSection_Graduatorie()` - Datatable CRUD

## Riferimenti

- Classe base: `utils/system_lib/AA_GenericModuleSection.php`
- Template datatable: `utils/system_ui/AA_GenericDatatableTemplate.php`
- Template layout: `utils/system_ui/AA_JSON_Template_Layout.php`
- Esempio: `utils/modules/sicar_9/lib.php`

---

## Sezioni con Caricamento Dati AJAX

Le sezioni possono caricare i dati in modo asincrono tramite AJAX invece di renderizzarli direttamente nel template. Questo e utile per dataset grandi.

### Implementazione GetDataSection_XXX_List

Per ogni sezione personalizzata che usa caricamento AJAX, implementa il metodo `GetDataSection_<NomeSezione>_List`:

```php
// Metodo per il caricamento dati AJAX della sezione
public function GetDataSectionUtenti_List($params = array())
{
    // Verifica permessi
    if (!$this->oUser->HasFlag(AA_User::CAN_ADMIN)) {
        AA_Log::Log(__METHOD__ . " - ERRORE: utente non abilitato.", 100);
        return array();
    }

    // Costruisci i parametri di ricerca
    $searchParams = array();
    
    // Filtri dalla request
    if (!empty($params['nome'])) {
        $searchParams['nome'] = $params['nome'];
    }
    if (!empty($params['email'])) {
        $searchParams['email'] = $params['email'];
    }
    if (!empty($params['stato'])) {
        $searchParams['stato'] = $params['stato'];
    }
    
    // Paginazione
    if (!empty($params['page'])) {
        $searchParams['from'] = ($params['page'] - 1) * $params['count'];
    }
    if (!empty($params['count'])) {
        $searchParams['limit'] = $params['count'];
    }
    
    // Ordina
    if (!empty($params['sort'])) {
        $searchParams['order'] = $params['sort'];
    }
    
    // Esegui la ricerca
    $utenti = AA_Utente::Search($searchParams);
    
    // Prepara i dati per il template
    $data = array();
    foreach ($utenti[1] as $curUtente) {
        // Costruisci la riga dati
        $data[] = array(
            "id" => $curUtente->GetProp("id"),
            "nome" => $curUtente->GetProp("nome"),
            "email" => $curUtente->GetProp("email"),
            "stato" => $curUtente->GetProp("attivo") ? "Attivo" : "Disattivo"
        );
    }
    
    // Restituisci: [conteggio_totale, dati]
    return array($utenti[0], $data);
}
```

### Registrazione del Metodo AJAX

Nel template della sezione, usa `SetContentBoxData` per collegare i dati:

```php
public function TemplateSection_Utenti($params = array())
{
    $id = static::AA_UI_PREFIX."_".static::AA_ID_SECTION_UTENTI;
    $canModify = $this->oUser->HasFlag(AA_User::CAN_ADMIN);
    
    // Usa il template generico per sezioni con datatable
    $content = new AA_GenericPagedSectionTemplate($id, "", 4);
    
    // Imposta il nome della sezione
    $content->SetName(static::AA_UI_SECTION_UTENTI_NAME);
    
    // Configura la sezione come datatable
    $content->ViewPublish();
    
    // Carica i dati (in directly o tramite AJAX)
    $_REQUEST['count'] = 10;
    $contentData = $this->GetDataSectionUtenti_List($_REQUEST);
    $content->SetContentBoxData($contentData[1]);
    
    // Imposta il conteggio per la paginazione
    $content->SetPagerItemCount($contentData[0]);
    
    // Abilita funzionalita
    $content->EnableMultiSelect();
    $content->EnableSelect();
    
    // Template per ogni item della lista
    $contentBoxTemplate = "<div class='AA_DataView_Item'>"
        . "<div><span class='AA_DataView_ItemTitle'>#nome#</span></div>"
        . "<div><span class='AA_DataView_ItemDetails'>#email#</span></div>"
        . "</div>";
    $content->SetContentBoxTemplate($contentBoxTemplate);
    
    return $content->toObject();
}
```

---

## CustomFilter e CustomDataTemplate

Questi metodi permettono di personalizzare il comportamento delle sezioni standard (Bozze/Pubblicate) senza dover riscrivere tutto il template.

### CustomFilter

Personalizza i filtri di ricerca per la sezione:

```php
// Personalizza il filtro per la sezione Pubblicate
protected function GetDataSectionPubblicate_CustomFilter($params = array())
{
    // Filtro per campo personalizzato
    if (!empty($params['campo_personalizzato'])) {
        $params['where'][] = " AND campo_personalizzato LIKE '%" . addslashes($params['campo_personalizzato']) . "%'";
    }
    
    // Filtro con join su altra tabella
    if (!empty($params['categoria'])) {
        $params['join'][] = " LEFT JOIN " . AA_AltraTabella::GetDatatable() . " ON "
            . AA_MioOggetto::AA_DBTABLE_DATA . ".categoria_id = "
            . AA_AltraTabella::GetDatatable() . ".id";
        $params['where'][] = " AND " . AA_AltraTabella::GetDatatable() . ".nome LIKE '%" . addslashes($params['categoria']) . "%'";
    }
    
    // Filtro per stato specifico
    if (!empty($params['stato_avanzato'])) {
        $params['where'][] = " AND stato = '" . addslashes($params['stato_avanzato']) . "'";
    }
    
    return $params;
}
```

### CustomDataTemplate

Personalizza la visualizzazione dei dati nella sezione:

```php
// Personalizza il template dei dati per le bozze
protected function GetDataSectionBozze_CustomDataTemplate($data = array(), $object = null)
{
    if ($object instanceof AA_MioOggetto) {
        // Aggiungi campi calcolati
        $data['pretitolo'] = $object->GetCategoria();
        $data['titolo'] = $object->GetDisplayName();
        
        // Tags dinamici
        $tags = "";
        if ($object->IsApprovato()) {
            $tags .= "<span class='AA_Label AA_Label_Green'>Approvato</span>";
        }
        if ($object->IsScaduto()) {
            $tags .= "<span class='AA_Label AA_Label_Red'>Scaduto</span>";
        }
        $data['tags'] = $tags;
        
        // Sottotitolo con dati correlati
        $data['sottotitolo'] = "<span>Stato: <b>" . $object->GetStato() . "</b></span>";
        
        // Dettagli aggiuntivi
        $data['dettagli'] = "<span>Data inizio: " . $object->GetDataInizio() . "</span>";
        
        // Occupazione/Stato con colori
        $colors = array(0 => "Gray", 1 => "Green", 2 => "Yellow", 3 => "Orange", 4 => "Red");
        $stato = $object->GetStatoAvanzato();
        $data['occupazione'] = "<span class='AA_Label AA_Label_Light" . $colors[$stato] . "'>" 
            . $object->GetStatoAvanzatoDesc() . "</span>";
    }
    
    return $data;
}
```

### Utilizzo Combinato

I due metodi possono essere usati insieme con `GetDataGenericSectionBozze_List`:

```php
public function GetDataSectionBozze_List($params = array())
{
    if (!$this->oUser->HasFlag(AA_MioModulo_Const::AA_USER_FLAG_GESTIONE)) {
        return array();
    }
    
    return $this->GetDataGenericSectionBozze_List(
        $params,
        "GetDataSectionBozze_CustomFilter",      // Callback per filtri
        "GetDataSectionBozze_CustomDataTemplate"  // Callback per template
    );
}
```

---

## Sezione con Dati Relazionali Complessi

Esempio di sezione che mostra dati con relazioni multiple:

```php
public function TemplateSection_ReportMensile($params = array())
{
    $id = static::AA_UI_PREFIX."_".static::AA_ID_SECTION_REPORT;
    
    // Layout principale
    $layout = new AA_JSON_Template_Layout($id, array(
        "type" => "clean",
        "name" => "Report Mensile"
    ));
    
    // ==========================================
    // RIGA 1: FILTRI
    // ==========================================
    $filterRow = new AA_JSON_Template_Layout("", array("type" => "line", "height" => 60));
    
    // Dropdown anno
    $filterRow->AddCol(new AA_JSON_Template_Generic("", array(
        "view" => "select",
        "name" => "anno",
        "label" => "Anno",
        "value" => date("Y"),
        "options" => array(
            array("id" => date("Y"), "value" => date("Y")),
            array("id" => date("Y") - 1, "value" => date("Y") - 1),
            array("id" => date("Y") - 2, "value" => date("Y") - 2)
        )
    )));
    
    // Dropdown mese
    $filterRow->AddCol(new AA_JSON_Template_Generic("", array(
        "view" => "select",
        "name" => "mese",
        "label" => "Mese",
        "value" => date("n"),
        "options" => array(
            array("id" => 1, "value" => "Gennaio"),
            array("id" => 2, "value" => "Febbraio"),
            // ... altri mesi
        )
    )));
    
    // Pulsante applica filtri
    $filterRow->AddCol(new AA_JSON_Template_Generic("", array(
        "view" => "button",
        "value" => " Applica",
        "icon" => "mdi mdi-filter",
        "css" => "webix_primary",
        "click" => "function() { 
            var values = this.getFormView().getValues(); 
            AA_MainApp.curModule.refreshCurrentSection(values); 
        }"
    )));
    
    $layout->AddRow($filterRow);
    
    // ==========================================
    // RIGA 2: STATISTICHE
    // ==========================================
    $statsRow = new AA_JSON_Template_Layout("", array("type" => "space", "height" => 100));
    
    // Card 1: Totale record
    $statsRow->AddCol($this->TemplateSection_Report_TotaleRecord($params));
    
    // Card 2: Record approvati
    $statsRow->AddCol($this->TemplateSection_Report_Approvati($params));
    
    // Card 3: Record in attesa
    $statsRow->AddCol($this->TemplateSection_Report_InAttesa($params));
    
    $layout->AddRow($statsRow);
    
    // ==========================================
    // RIGA 3: TABELLA DATI
    // ==========================================
    $tableRow = new AA_JSON_Template_Layout("", array("type" => "line"));
    $tableRow->AddCol($this->TemplateSection_Report_Tabella($params));
    $layout->AddRow($tableRow);
    
    return $layout;
}

// Card statistiche (sotto-metodo)
protected function TemplateSection_Report_TotaleRecord($params)
{
    $count = AA_MioOggetto::GetCount($params);
    
    return new AA_JSON_Template_Template("", array(
        "view" => "template",
        "template" => "<div style='text-align: center; padding: 10px;'>
            <div style='font-size: 2em; font-weight: bold; color: #1ca1c1'>$count</div>
            <div style='color: #666'>Totale Record</div>
        </div>",
        "css" => "AA_Stats_Card",
        "border" => true
    ));
}
```

---

## Gestione Permessi Specifici per Sezione

Ogni sezione pu avere controlli permessi specifici:

```php
public function TemplateSection_Utenti($params = array())
{
    $id = static::AA_UI_PREFIX."_".static::AA_ID_SECTION_UTENTI;
    
    // Permessi: l'utente deve essere admin OPPURE avere un flag specifico
    $canView = $this->oUser->HasFlag(AA_User::CAN_ADMIN) 
               || $this->oUser->HasFlag(AA_MioModulo_Const::AA_USER_FLAG_GESTIONE_UTENTI);
    
    // Se non ha permessi, mostra messaggio
    if (!$canView) {
        $template = new AA_JSON_Template_Layout($id, array("type" => "clean"));
        $template->AddRow(new AA_JSON_Template_Template("", array(
            "view" => "template",
            "template" => "<div style='padding: 20px; text-align: center; color: #666;'>
                <span class='mdi mdi-lock' style='font-size: 48px;'></span>
                <p>Non hai i permessi per visualizzare questa sezione.</p>
            </div>"
        )));
        return $template;
    }
    
    $canModify = $this->oUser->HasFlag(AA_User::CAN_ADMIN);
    
    // ... resto del template
}
```

### Flag Utente per Sezioni

Definisci costanti per i flag nel modulo:

```php
// Costanti per flag utente
const AA_USER_FLAG_GESTIONE_UTENTI = "gestione_utenti";
const AA_USER_FLAG_VISUALIZZA_REPORT = "visualizza_report";
const AA_USER_FLAG_ESPORTA_DATI = "esporta_dati";
```

---

## Sezioni con Azioni Bulk

Per implementare operazioni su record multipli:

```php
// Nel metodo del template section
public function TemplateSection_Utenti($params = array())
{
    // ... setup iniziale ...
    
    // Abilita selezione multipla
    $template->EnableMultiSelect();
    $template->EnableSelect();
    
    // Toolbar con azioni bulk
    $bulkActions = new AA_JSON_Template_Layout("", array(
        "type" => "line", 
        "height" => 50,
        "css" => "AA_BulkActions_Bar"
    ));
    
    // Pulsante "Esporta selezionati"
    $bulkActions->AddCol(new AA_JSON_Template_Generic("", array(
        "view" => "button",
        "value" => "Esporta",
        "icon" => "mdi mdi-download",
        "click" => "function() {
            var grid = $$('" . $id . "');
            var selected = grid.getSelectedId();
            if (!selected) {
                webix.message({type: 'error', text: 'Seleziona almeno un record'});
                return;
            }
            var ids = selected.join(',');
            window.location.href = 'export.php?module=" . $this->GetId() . "&ids=' + ids;
        }"
    )));
    
    // Pulsante "Elimina selezionati"
    $bulkActions->AddCol(new AA_JSON_Template_Generic("", array(
        "view" => "button",
        "value" => "Elimina",
        "icon" => "mdi mdi-delete",
        "css" => "webix_danger",
        "click" => "function() {
            var grid = $$('" . $id . "');
            var selected = grid.getSelectedId();
            if (!selected) {
                webix.message({type: 'error', text: 'Seleziona almeno un record'});
                return;
            }
            if (!confirm('Eliminare ' + selected.length + ' record?')) return;
            
            AA_MainApp.utils.callHandler('dlg', {
                task: 'GetBulkDeleteConfirmDlg',
                params: [{ids: selected.join(',')}]
            }, '" . $this->id . "');
        }"
    )));
    
    // Aggiungi al layout principale
    $layout->AddRow($bulkActions);
    
    return $layout;
}

// Task per conferma eliminazione bulk
public function Task_GetBulkDeleteConfirmDlg($task)
{
    $params = $_REQUEST;
    
    $template = new AA_GenericFormDlg(
        $this->GetId() . "_BulkDeleteConfirm",
        "Conferma Eliminazione",
        $this->GetId(),
        "BulkDeleteUtenti",
        "500"
    );
    
    $template->AddElement(array(
        "view" => "template",
        "template" => "<p>Sei sicuro di voler eliminare i <b>" . count(explode(',', $params['ids'])) . "</b> record selezionati?</p>
                       <p style='color: red; font-size: 0.9em;'>L'operazione non puo essere annullata.</p>"
    ));
    
    $template->AddHiddenField("ids", $params['ids']);
    
    $task->SetContent($template, true, false);
    
    return true;
}

// Task per eliminazione bulk
public function Task_BulkDeleteUtenti($task)
{
    $params = $_REQUEST;
    $ids = explode(',', $params['ids']);
    $deleted = 0;
    
    foreach ($ids as $id) {
        $utente = new AA_Utente($id);
        if ($utente->isValid() && $utente->CanDelete($this->oUser)) {
            $utente->Delete();
            $deleted++;
        }
    }
    
    $task->SetStatus(0);
    $task->SetStatusAction("refreshCurSection", null, true);
    return true;
}
```

---

## Esempio Completo: Sezione Documenti

Ecco un esempio completo che mostra una sezione per la gestione di documenti con upload, preview e download:

```php
// ==========================================
// COSTANTI (nel costruttore o come costanti di classe)
// ==========================================
const AA_ID_SECTION_DOCUMENTI = "GestDocumenti";
const AA_UI_SECTION_DOCUMENTI_BOX = "GestDocumentiBox";
const AA_UI_SECTION_DOCUMENTI_NAME = "Gestione documenti";
const AA_UI_SECTION_DOCUMENTI_ICON = "mdi mdi-file-document";

// ==========================================
// CREAZIONE SEZIONE (nel __construct)
// ==========================================
$gestDocumenti = new AA_GenericModuleSection(
    static::AA_ID_SECTION_DOCUMENTI,
    static::AA_UI_SECTION_DOCUMENTI_NAME,
    true,  // navbar
    static::AA_UI_PREFIX."_".static::AA_UI_SECTION_DOCUMENTI_BOX,
    $this-> false, true,GetId(),
    false, false,
    static::AA_UI_SECTION_DOCUMENTI_ICON,
    "TemplateSection_Documenti"
);
$gestDocumenti->SetNavbarTemplate(array(
    $this->TemplateGenericNavbar_Desktop(1, true, true)->toArray()
));
$this->AddSection($gestDocumenti);

// ==========================================
// TEMPLATE SEZIONE
// ==========================================
public function TemplateSection_Documenti($params = array())
{
    $id = static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DOCUMENTI;
    $canModify = $this->oUser->HasFlag(AA_User::CAN_ADMIN);
    
    $storage = AA_Storage::GetInstance();
    
    // Ricerca documenti
    $documenti = AA_Documento::Search($params);
    $documentiData = array();
    
    foreach ($documenti as $curDoc) {
        // Determina icona e azione in base al tipo file
        $fileExt = strtolower(pathinfo($curDoc->GetProp("nome_file"), PATHINFO_EXTENSION));
        $viewIcon = "mdi-file";
        
        // Preview per PDF
        if ($fileExt === "pdf" && $storage->IsValid()) {
            $file = $storage->GetFileByHash($curDoc->GetProp("file_hash"));
            if ($file->IsValid() && strpos($file->GetMimeType(), "pdf") !== false) {
                $viewAction = 'AA_MainApp.utils.callHandler("pdfPreview", {url: "storage.php?object='.$curDoc->GetProp("file_hash").'"}, "'.$this->id.'")';
                $viewIcon = "mdi-eye";
            }
        } else {
            $viewAction = 'AA_MainApp.utils.callHandler("wndOpen", {url: "storage.php?object='.$curDoc->GetProp("file_hash").'"}, "'.$this->id.'")';
        }
        
        // Download
        $downloadAction = 'AA_MainApp.utils.callHandler("fileDownload", {file: "'.$curDoc->GetProp("file_hash").'", name: "'.$curDoc->GetProp("nome_file").'"}, "'.$this->id.'")';
        
        // Azioni
        $ops = "<div class='AA_DataTable_Ops'>";
        
        // Visualizza
        $ops .= "<a class='AA_DataTable_Ops_Button' title='Visualizza' onClick='".$viewAction."'>";
        $ops .= "<span class='mdi ".$viewIcon."'></span></a>";
        
        // Download
        $ops .= "<a class='AA_DataTable_Ops_Button' title='Scarica' onClick='".$downloadAction."'>";
        $ops .= "<span class='mdi mdi-download'></span></a>";
        
        // Modifica (solo se permesso)
        if ($canModify) {
            $ops .= "<a class='AA_DataTable_Ops_Button' title='Modifica' onClick=\"";
            $ops .= "AA_MainApp.utils.callHandler('dlg', {task:'GetDocumentoModifyDlg', ";
            $ops .= "params: [{id:'".$curDoc->GetProp("id").'"}]},'".$this->id."')\">";
            $ops .= "<span class='mdi mdi-pencil'></span></a>";
            
            // Elimina
            $ops .= "<a class='AA_DataTable_Ops_Button_Red' title='Elimina' onClick=\"";
            $ops .= "AA_MainApp.utils.callHandler('dlg', {task:'GetDocumentoDeleteDlg', ";
            $ops .= "params: [{id:'".$curDoc->GetProp("id").'"}]},'".$this->id."')\">";
            $ops .= "<span class='mdi mdi-trash-can'></span></a>";
        }
        
        $ops .= "</div>";
        
        // Dimensione file formattata
        $size = $curDoc->GetProp("dimensione");
        if ($size > 1024 * 1024) {
            $sizeStr = round($size / (1024 * 1024), 2) . " MB";
        } elseif ($size > 1024) {
            $sizeStr = round($size / 1024, 2) . " KB";
        } else {
            $sizeStr = $size . " B";
        }
        
        $documentiData[] = array(
            "id" => $curDoc->GetProp("id"),
            "nome" => $curDoc->GetProp("nome_file"),
            "tipo" => $curDoc->GetProp("tipo"),
            "dimensione" => $sizeStr,
            "data_caricamento" => date("d/m/Y H:i", strtotime($curDoc->GetProp("data_caricamento"))),
            "autore" => $curDoc->GetAutore()->GetDisplayName(),
            "ops" => $ops
        );
    }
    
    // Crea il template
    $template = new AA_GenericDatatableTemplate(
        $id,
        "Documenti",
        5,  // numero colonne
        array("type" => "clean", "name" => static::AA_UI_SECTION_DOCUMENTI_NAME),
        array("css" => "AA_Header_DataTable", "filtered" => true, "filter_id" => $id)
    );
    
    // Configurazione base
    $template->EnableScroll(false, true);
    $template->EnableRowOver();
    $template->EnableHeader(true);
    $template->SetHeaderHeight(38);
    
    // Abilita aggiunta nuovo documento
    if ($canModify) {
        $template->EnableAddNew(true, "GetDocumentoUploadDlg");
    }
    
    // Configura colonne
    $template->SetColumnHeaderInfo(0, "nome", "Nome File", 250, "textFilter", "text", "left");
    $template->SetColumnHeaderInfo(1, "tipo", "Tipo", 150, "textFilter", "text", "center");
    $template->SetColumnHeaderInfo(2, "dimensione", "Dimensione", 100, null, null, "center");
    $template->SetColumnHeaderInfo(3, "data_caricamento", "Caricato il", 150, "textFilter", "text", "center");
    $template->SetColumnHeaderInfo(4, "autore", "Autore", 150, "textFilter", "text", "left");
    $template->SetColumnHeaderInfo(5, "ops", "Operazioni", 150, null, null, "center");
    
    // Imposta i dati
    $template->SetData($documentiData);
    
    return $template;
}

// ==========================================
// TASK: Dialog upload documento
// ==========================================
public function Task_GetDocumentoUploadDlg($params = array())
{
    $id = $this->GetId() . "_UploadDocumento";
    
    $template = new AA_GenericFormDlg(
        $id,
        "Carica Documento",
        $this->GetId(),
        "UploadDocumento",
        "600"
    );
    
    // Campo nome file
    $template->AddElement(array(
        "view" => "text",
        "name" => "nome_file",
        "label" => "Nome File",
        "required" => true
    ));
    
    // Tipo documento (select)
    $template->AddElement(array(
        "view" => "combo",
        "name" => "tipo",
        "label" => "Tipo Documento",
        "required" => true,
        "options" => array(
            array("id" => "contratto", "value" => "Contratto"),
            array("id" => "determina", "value" => "Determina"),
            array("id" => "verbale", "value" => "Verbale"),
            array("id" => "altro", "value" => "Altro")
        )
    ));
    
    // Upload file (usando campo nascosto o componente file)
    $template->AddElement(array(
        "view" => "text",
        "name" => "file_data",
        "label" => "File",
        "type" => "password",  // workaround per nascondere
        "required" => true,
        "placeholder" => "Trascina il file qui o clicca per selezionarlo"
    ));
    
    // Note
    $template->AddElement(array(
        "view" => "textarea",
        "name" => "note",
        "label" => "Note",
        "height" => 80
    ));
    
    // Abilita uploads multipli
    $template->EnableMultipleUploads(true);
    
    $task->SetContent($template, true, false);
    
    return true;
}

// ==========================================
// TASK: Upload documento
// ==========================================
public function Task_UploadDocumento($task)
{
    $params = $_REQUEST;
    
    // Validazione
    if (empty($params['nome_file']) || empty($params['file_data'])) {
        $task->SetError("Nome file e file sono obbligatori");
        $task->SetStatus(-1);
        return false;
    }
    
    // Gestione upload
    $storage = AA_Storage::GetInstance();
    if (!$storage->IsValid()) {
        $task->SetError("Errore: storage non disponibile");
        $task->SetStatus(-1);
        return false;
    }
    
    // Salva il file
    $fileHash = $storage->Save($params['file_data'], $params['nome_file']);
    if (!$fileHash) {
        $task->SetError("Errore durante il salvataggio del file");
        $task->SetStatus(-1);
        return false;
    }
    
    // Crea il record documento
    $documento = new AA_Documento();
    $documento->SetProp("nome_file", $params['nome_file']);
    $documento->SetProp("tipo", $params['tipo']);
    $documento->SetProp("file_hash", $fileHash);
    $documento->SetProp("dimensione", strlen(base64_decode($params['file_data'])));
    $documento->SetProp("autore_id", $this->oUser->GetId());
    $documento->SetProp("data_caricamento", date("Y-m-d H:i:s"));
    
    if (!empty($params['note'])) {
        $documento->SetProp("note", $params['note']);
    }
    
    if ($documento->Save()) {
        $task->SetStatus(0);
        $task->SetStatusAction("refreshCurSection", null, true);
        return true;
    }
    
    $task->SetError("Errore durante il salvataggio del documento");
    $task->SetStatus(-1);
    return false;
}
```

---

## AddObjectTemplate e RefreshUiObject

Il metodo `AddObjectTemplate` permette di definire template per oggetti UI che possono essere ricaricati dinamicamente senza aggiornare l'intera sezione. Questo e utile per aggiornare porzioni specifiche dell'interfaccia.

### Registrazione di un Object Template

```php
// Nel costruttore del modulo, dopo la registrazione dei task
$this->AddObjectTemplate(
    static::AA_UI_PREFIX."_".static::AA_UI_WND_MIO OGGETTO."_".static::AA_UI_LAYOUT_MIO_OGGETTO,
    "Template_GetMioOggettoViewLayout"
);
```

**Parametri:**
- `$idObject`: ID univoco dell'oggetto (usa il prefisso del modulo per evitare conflitti)
- `$template`: Nome del metodo che restituisce il template

### Implementazione del Template

```php
// Template per l'oggetto
public function Template_GetMioOggettoViewLayout($params = array())
{
    $id = static::AA_UI_PREFIX."_".static::AA_UI_WND_MIO OGGETTO."_".static::AA_UI_LAYOUT_MIO_OGGETTO;
    
    // Costruisci il layout dell'oggetto
    $layout = new AA_JSON_Template_Layout($id, array(
        "type" => "clean",
        "name" => "Mio Oggetto"
    ));
    
    // Aggiungi i componenti
    $row = new AA_JSON_Template_Layout("", array("type" => "space"));
    $row->AddCol($this->Template_GetMioOggetto_DataTable($params));
    $layout->AddRow($row);
    
    return $layout;
}

// Sotto-template per la datatable
protected function Template_GetMioOggetto_DataTable($params = array())
{
    $id = static::AA_UI_PREFIX."_".static::AA_UI_WND_MIO OGGETTO."_Table";
    
    $data = AA_MioOggetto::Search($params);
    $tableData = array();
    
    foreach ($data as $curObj) {
        $tableData[] = array(
            "id" => $curObj->GetId(),
            "nome" => $curObj->GetDisplayName(),
            "stato" => $curObj->GetStato()
        );
    }
    
    $template = new AA_GenericDatatableTemplate($id, "", 2);
    $template->SetColumnHeaderInfo(0, "nome", "Nome", 200);
    $template->SetColumnHeaderInfo(1, "stato", "Stato", 100);
    $template->SetData($tableData);
    
    return $template;
}
```

### RefreshUiObject: Aggiornamento Dinamico

L'aggiornamento di un object template avviene tramite la funzione JavaScript `refreshUiObject`:

```javascript
// Aggiorna l'oggetto specifico
AA_MainApp.curModule.refreshUiObject('mio_id_oggetto', true);

// Con parametri aggiuntivi
AA_MainApp.curModule.refreshUiObject('mio_id_oggetto', true, true);
```

**Parametri:**
1. `idObj` - ID dell'oggetto da aggiornare
2. `bRefreshContent` - Se true, ricarica il contenuto dal server
3. `bResetView` - Se true, resetta lo stato della vista (tab, filtri, etc.)

### RefreshUiObject in Azione

Nel PHP, puoi specificare l'azione di refresh dopo un'operazione di salvataggio:

```php
// Nel task di salvataggio, nel dialog o come azione post-save
$applyActions = "AA_MainApp.curModule.refreshUiObject('" . 
    static::AA_UI_PREFIX."_".static::AA_UI_WND_MIO OGGETTO."_".static::AA_UI_LAYOUT_MIO_OGGETTO 
    . "', true)";

// Esempio con FilterDlg
$dlg = new AA_GenericFilterDlg(
    $id . "_Filter",
    "Filtri",
    $this->GetId(),
    $formData,
    $resetData,
    $applyActions,  // Azione dopo applicazione filtri
    $targetObjectId
);
```

### Catena di Chiamata

1. **JavaScript**: `refreshUiObject(id, true)` chiamato
2. **JS**: `refreshObjectContent(object_id, params)` invoca task `GetObjectContent`
3. **PHP**: `Task_GetObjectContent()` chiama `Task_GetGenericObjectContent()`
4. **PHP**: Cerca l'object template registrato e chiama il metodo template
5. **PHP**: Il metodo template restituisce il layout aggiornato
6. **JS**: Il layout viene sostituito nell'interfaccia

---

## TemplateGenericTabbedSection

Il metodo `TemplateGenericTabbedSection` crea una sezione con tab multipli, dove ogni tab puo caricare contenuto dinamicamente. E' utilizzato per le sezioni Detail con piu pannelli.

### Utilizzo Base

```php
// Nel costruttore, definisci i tab della sezione
$this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL, array(
    array(
        "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX,
        "value" => "Generale",
        "tooltip" => "Dati generali",
        "template" => "TemplateMioModuloDettaglio_Generale_Tab"
    ),
    array(
        "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_DOCUMENTI_BOX,
        "value" => "Documenti",
        "tooltip" => "Documenti allegati",
        "template" => "TemplateMioModuloDettaglio_Documenti_Tab",
        "enable_preview" => true
    ),
    array(
        "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_STORICO_BOX,
        "value" => "Storico",
        "tooltip" => "Storico modifiche",
        "template" => "TemplateMioModuloDettaglio_Storico_Tab"
### Struttura    )
));
```

 del Tab

Ogni elemento dell'array di configurazione puo avere:

| Proprieta | Descrizione |
|-----------|-------------|
| `id` | ID univoco del tab |
| `value` | Testo/HTML visualizzato nel tab |
| `tooltip` | Tooltip al passaggio del mouse |
| `template` | Nome del metodo PHP che genera il contenuto |
| `enable_preview` | Se true, abilita anteprima del contenuto |

### Implementazione del Template Tab

```php
// Template per il tab "Generale" del dettaglio
public function TemplateMioModuloDettaglio_Generale_Tab($params)
{
    $id = static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX;
    $object = $this->GetFocusedObject();
    
    $layout = new AA_JSON_Template_Layout($id, array("type" => "clean"));
    
    // Riga informazioni principali
    $infoRow = new AA_JSON_Template_Layout("", array("type" => "line", "height" => 60));
    
    // Campo nome
    $infoRow->AddCol(new AA_JSON_Template_Generic("", array(
        "view" => "text",
        "name" => "nome",
        "label" => "Nome",
        "value" => $object->GetProp("nome"),
        "readonly" => true
    )));
    
    // Campo data
    $infoRow->AddCol(new AA_JSON_Template_Generic("", array(
        "view" => "datepicker",
        "name" => "data_inserimento",
        "label" => "Data Inserimento",
        "value" => $object->GetProp("data_inserimento"),
        "readonly" => true
    )));
    
    $layout->AddRow($infoRow);
    
    // Altre righe...
    
    return $layout;
}

// Template per il tab "Documenti"
public function TemplateMioModuloDettaglio_Documenti_Tab($params)
{
    $id = static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_DOCUMENTI_BOX;
    $object = $this->GetFocusedObject();
    
    // Carica i documenti associati
    $documenti = $object->GetDocumenti();
    $docData = array();
    
    foreach ($documenti as $curDoc) {
        $docData[] = array(
            "id" => $curDoc->GetId(),
            "nome" => $curDoc->GetDisplayName(),
            "tipo" => $curDoc->GetTipo(),
            "data" => $curDoc->GetData()
        );
    }
    
    // Crea la datatable
    $template = new AA_GenericDatatableTemplate($id, "", 3);
    $template->SetColumnHeaderInfo(0, "nome", "Nome Documento", 250);
    $template->SetColumnHeaderInfo(1, "tipo", "Tipo", 150);
    $template->SetColumnHeaderInfo(2, "data", "Data", 120);
    $template->SetData($docData);
    
    return $template;
}
```

---

## TemplateGenericSection_Detail

Il metodo `TemplateGenericSection_Detail` e una variante di `TemplateGenericTabbedSection` specifica per la visualizzazione del dettaglio di un oggetto. Viene chiamato quando si accede alla sezione Detail di un record.

### Come Funziona

```php
// Nel metodo TemplateSection_Detail
public function TemplateSection_Detail($params)
{
    // Usa il metodo generico che costruisce automaticamente:
    // - Header con stato e dettagli
    // - Toolbar con azioni (pubblica, elimina, etc.)
    // - Tabbar con i tab definiti in SetSectionItemTemplate
    
    return $this->TemplateGenericSection_Detail($params);
}
```

### Configurazione Automatica

Il metodo `TemplateGenericSection_Detail`:

1. **Carica l'oggetto** dai parametri (id dell'oggetto)
2. **Costruisce l'header** con stato (bozza/pubblicata), data aggiornamento, utente
3. **Crea la toolbar** con pulsanti per pubblicazione, eliminazione, etc.
4. **Genera la tabbar** con i tab definiti in `SetSectionItemTemplate`
5. **Inserisce i contenuti** di ogni tab nel multiview

### Esempio di Configurazione Completa (sier_5)

```php
// Definizione dei tab nel costruttore
$this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL, array(
    array(
        "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX, 
        "value" => "Generale",
        "tooltip" => "Dati generali",
        "template" => "TemplateSierDettaglio_Generale_Tab"
    ),
    array(
        "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_LISTE_BOX, 
        "value" => "<span style='font-size: smaller'>Coalizioni e Liste</span>",
        "tooltip" => "Gestione coalizioni e liste",
        "template" => "TemplateSierDettaglio_Coalizioni_Tab",
        "enable_preview" => true
    ),
    array(
        "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_CANDIDATI_BOX, 
        "value" => "Candidati",
        "tooltip" => "Gestione dei Candidati",
        "template" => "TemplateSierDettaglio_Candidati_Tab",
        "enable_preview" => true
    ),
    array(
        "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_COMUNI_BOX, 
        "value" => "Comuni",
        "tooltip" => "Gestione dei Comuni",
        "template" => "TemplateSierDettaglio_Comuni_Tab",
        "enable_preview" => true
    ),
    array(
        "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_ALLEGATI_BOX, 
        "value" => "<span style='font-size: smaller'>Documenti</span>",
        "tooltip" => "Gestione degli allegati e links",
        "template" => "TemplateSierDettaglio_Allegati_Tab",
        "enable_preview" => true
    ),
));
```

---

## Caricamento Dinamico dei Contenuti

I tab nelle sezioni Detail possono caricare i contenuti dinamicamente quando vengono selezionati. Questo avviene tramite il meccanismo di `refreshUiObject`.

### Abilitare il Caricamento Dinamico

Nel tab, imposta `enable_preview => true` per abilitare il caricamento lazy:

```php
array(
    "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_COMUNI_BOX,
    "value" => "Comuni",
    "tooltip" => "Gestione dei Comuni",
    "template" => "TemplateSierDettaglio_Comuni_Tab",
    "enable_preview" => true  // Carica solo quando il tab viene attivato
)
```

### Refresh di un Tab Specifico

Per aggiornare un tab specifico dopo un'operazione:

```php
// Dopo un'operazione di salvataggio nel tab
$applyActions = "AA_MainApp.curModule.refreshUiObject('" . 
    static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_COMUNI_BOX 
    . "', true)";

// Usa in un FilterDlg
$dlg = new AA_GenericFilterDlg(
    $id . "_Filter",
    "Filtri Comuni",
    $this->GetId(),
    $formData,
    $resetData,
    $applyActions,
    static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_COMUNI_BOX
);
```

---

## Esempio Completo: Modulo con Sezioni Tabbed

Ecco un esempio completo che mostra come creare un modulo con sezioni Detail tabbed e object template:

```php
// ==========================================
// COSTANTI
// ==========================================
const AA_ID_SECTION_DETAIL = "Detail";
const AA_UI_DETAIL_GENERALE_BOX = "DetailGenerale";
const AA_UI_DETAIL_DOCUMENTI_BOX = "DetailDocumenti";
const AA_UI_DETAIL_STORICO_BOX = "DetailStorico";

// Object template
const AA_UI_WND_RENDICONTI = "RendicontiWnd";
const AA_UI_LAYOUT_RENDICONTI = "RendicontiLayout";

// ==========================================
// COSTRUTTORE
// ==========================================
public function __construct($user = null, $bDefaultSections = true)
{
    parent::__construct($user, $bDefaultSections);
    
    // ... altre inizializzazioni ...
    
    // Registra task per object template
    $taskManager->RegisterTask("GetMioModuloRendiconti");
    
    // Registra object template
    $this->AddObjectTemplate(
        static::AA_UI_PREFIX."_".static::AA_UI_WND_RENDICONTI."_".static::AA_UI_LAYOUT_RENDICONTI,
        "Template_GetMioModuloRendicontiViewLayout"
    );
    
    // Definisci i tab per la sezione Detail
    $this->SetSectionItemTemplate(static::AA_ID_SECTION_DETAIL, array(
        array(
            "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX,
            "value" => "Generale",
            "tooltip" => "Dati generali",
            "template" => "TemplateMioModuloDettaglio_Generale_Tab"
        ),
        array(
            "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_DOCUMENTI_BOX,
            "value" => "Documenti",
            "tooltip" => "Documenti allegati",
            "template" => "TemplateMioModuloDettaglio_Documenti_Tab",
            "enable_preview" => true
        ),
        array(
            "id" => static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_STORICO_BOX,
            "value" => "Storico",
            "tooltip" => "Storico modifiche",
            "template" => "TemplateMioModuloDettaglio_Storico_Tab"
        )
    ));
}

// ==========================================
// TEMPLATE SEZIONE DETAIL
// ==========================================
public function TemplateSection_Detail($params)
{
    // Usa il metodo generico che costruisce automaticamente
    // header, toolbar e tabbar
    return $this->TemplateGenericSection_Detail($params);
}

// ==========================================
// TEMPLATE TAB GENERALE
// ==========================================
public function TemplateMioModuloDettaglio_Generale_Tab($params)
{
    $id = static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_GENERALE_BOX;
    $object = $this->GetFocusedObject();
    
    $layout = new AA_JSON_Template_Layout($id, array("type" => "clean"));
    
    // ... contenuto del tab ...
    
    return $layout;
}

// ==========================================
// TEMPLATE TAB DOCUMENTI (con object template refresh)
// ==========================================
public function TemplateMioModuloDettaglio_Documenti_Tab($params)
{
    $id = static::AA_UI_PREFIX."_".static::AA_ID_SECTION_DETAIL."_".static::AA_UI_DETAIL_DOCUMENTI_BOX;
    $object = $this->GetFocusedObject();
    
    // Il contenuto viene caricato dinamicamente
    // quando l'utente clicca sul tab
    $template = new AA_JSON_Template_Layout($id, array("type" => "clean"));
    
    // Verifica se ci sono parametri (caricamento dinamico)
    if (!empty($params)) {
        $documenti = $object->GetDocumenti();
        // ... costruisci la datatable ...
    } else {
        // Mostra messaggio di caricamento o placeholder
        $template->AddRow(new AA_JSON_Template_Template($id."_Placeholder", array(
            "view" => "template",
            "template" => "<div style='text-align:center;padding:20px;color:#999'>
                <span class='mdi mdi-loading mdi-spin' style='font-size:24px'></span>
                <p>Caricamento documenti...</p>
            </div>"
        )));
    }
    
    return $template;
}

// ==========================================
// OBJECT TEMPLATE PER RENDICONTI
// ==========================================
public function Template_GetMioModuloRendicontiViewLayout($params = array())
{
    $id = static::AA_UI_PREFIX."_".static::AA_UI_WND_RENDICONTI."_".static::AA_UI_LAYOUT_RENDICONTI;
    
    $layout = new AA_JSON_Template_Layout($id, array(
        "type" => "clean",
        "name" => "Rendiconti"
    ));
    
    // Toolbar con filtri
    $toolbar = new AA_JSON_Template_Layout($id."_Toolbar", array("type" => "line", "height" => 50));
    
    $applyActions = "AA_MainApp.curModule.refreshUiObject('" . $id . "', true)";
    $resetActions = "AA_MainApp.curModule.refreshUiObject('" . $id . "', true, true)";
    
    $toolbar->AddCol(new AA_JSON_Template_Generic("", array(
        "view" => "button",
        "value" => "Applica Filtri",
        "click" => "function() { 
            var form = this.getFormView(); 
            var values = form.getValues(); 
            " . $applyActions . ";
        }"
    )));
    
    $layout->AddRow($toolbar);
    
    // Datatable con dati
    $table = new AA_GenericDatatableTemplate($id."_Table", "", 4);
    // ... configura colonne e dati ...
    $layout->AddRow($table);
    
    return $layout;
}

// ==========================================
// TASK PER RENDICONTI
// ==========================================
public function Task_GetMioModuloRendiconti($task)
{
    // Il task viene chiamato automaticamente da refreshUiObject
    // quando l'utente interagisce con il layout
    $content = $this->Template_GetMioModuloRendicontiViewLayout($_REQUEST);
    $task->SetContent($content, true, false);
    
    return true;
}
```

---

## Riferimenti Aggiuntivi

- **Modulo sicar_9**: Esempio completo con multiple sezioni (Immobili, Enti, Nuclei, Finanziamenti, Graduatorie)
- **Modulo geco_6**: Esempio con sezione Criteri e documenti
- **Modulo sines_2**: Esempio con sezione Scadenzario e gestione dati AJAX
- **Modulo sier_5**: Esempio avanzato con TemplateGenericSection_Detail e SetSectionItemTemplate con tab multipli
- **Classe AA_GenericPagedSectionTemplate**: Template base per sezioni con datatable
- **Classe AA_GenericDatatableTemplate**: Template avanzato per datatable
- **Classe AA_GenericModule**: Metodi Task_GetObjectContent, Task_GetGenericObjectContent
- **File system_lib.js**: Metodi refreshUiObject, refreshObjectContent
