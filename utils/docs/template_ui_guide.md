# Guida ai Template UI - Amministrazione Aperta

## Indice

1. [Introduzione ai Template UI](#1-introduzione-ai-template-ui)
2. [Gerarchia delle Classi](#2-gerarchia-delle-classi)
3. [AA_GenericWindowTemplate](#3-aa_genericwindowtemplate)
4. [AA_GenericFormDlg](#4-aa_genericformdlg)
_GenericFilterDlg](#5-aa_genericfilterdl5. [AAg)
6. [AA_FieldSet](#6-aa_fieldset)
7. [AA_JSON_Template_* - Componenti Base](#7-aa_json_template---componenti-base)
8. [AA_GenericPagedSectionTemplate](#8-aa_genericpagedsectiontemplate)
9. [Esempi Pratici](#9-esempi-pratici)
10. [Riepilogo Metodi](#10-riepilogo-metodi)

---

## 1. Introduzione ai Template UI

Il sistema di template UI di Amministrazione Aperta permette di costruire interfacce Webix (JavaScript) in modo programmatico dal lato PHP. Le classi seguono un pattern **builder** che consente di creare finestre, form, campi e layout complessi concatenando metodi.

### Vantaggi

- **Type-safe lato server**: La logica PHPisce la gest costruzione dell'interfaccia
- **Riusabilità**: Componenti riutilizzabili (campi, form, finestre)
- **Separazione**: Logica di presentazione separata dalla business logic
- **Consistenza**: Tutte le UI seguono gli stessi pattern

---

## 2. Gerarchia delle Classi

```
AA_JSON_Template_Generic (base)
    ├── AA_JSON_Template_Layout
    ├── AA_JSON_Template_Form
    ├── AA_JSON_Template_Toolbar
    ├── AA_FieldSet
    ├── AA_GenericWindowTemplate
    │       └── AA_GenericFormDlg
    │               └── AA_GenericFilterDlg
    └── AA_GenericPagedSectionTemplate
```

### Classi Specializzate per Campi

| Classe | Tipo Webix | Utilizzo |
|--------|-----------|----------|
| `AA_JSON_Template_Text` | text | Campo di testo |
| `AA_JSON_Template_Textarea` | textarea | Area di testo |
| `AA_JSON_Template_Number` | number | Campo numerico |
| `AA_JSON_Template_Combo` | combo | Combo (search + select) |
| `AA_JSON_Template_Select` | select | Select dropdown |
| `AA_JSON_Template_Checkbox` | checkbox | Checkbox |
| `AA_JSON_Template_Switch` | switch | Toggle switch |
| `AA_JSON_Template_Datepicker` | datepicker | Campo data |
| `AA_JSON_Template_Radio` | radio | Radio button |
| `AA_JSON_Template_Richtext` | richtext | Editor RTF |
| `AA_JSON_Template_Ckeditor5_field` | ckeditor5 | Editor WYSIWYG |
| `AA_JSON_Template_Fileupload` | uploader | Upload file |
| `AA_JSON_Template_Search` | search | Campo ricerca |

---

## 3. AA_GenericWindowTemplate

**Scopo**: Creare finestre di dialogo modali o non-modali.

### Costruttore

```php
public function __construct($id = "", $title = "", $module = "", $bodyProps = null)
```

### Parametri

| Parametro | Descrizione |
|----------|-------------|
| `$id` | Identificativo univoco della finestra |
| `$title` | Titolo visualizzato nella toolbar |
| `$module` | ID modulo di riferimento |
| `$bodyProps` | Proprietà aggiuntive per il body |

### Metodi Principali

```php
// Dimensioni
$wnd->SetWidth(1280);      // Larghezza in pixel
$wnd->SetHeight(600);       // Altezza in pixel

// Modalità
$wnd->EnableModal();        // Finestra modale
$wnd->DisableModal();       // Finestra non-modale

// Contenuto
$wnd->AddView($view);       // Aggiunge un componente

// Output
$wnd->toString();           // Restituisce JSON
$wnd->toBase64();           // Restituisce JSON base64
```

### Esempio Base

```php
$wnd = new AA_GenericWindowTemplate("mia_finestra", "Titolo Finestra", "modulo_id");

$wnd->SetWidth(800);
$wnd->SetHeight(500);
$wnd->EnableModal();

// Aggiungi contenuto
$wnd->AddView(array(
    "view" => "template",
    "template" => "Contenuto della finestra"
));

// Usa in un task
$task->SetContent($wnd->toBase64(), true);
```

---

## 4. AA_GenericFormDlg

**Scopo**: Creare finestre di dialogo con form per inserimento/modifica dati. Estende `AA_GenericWindowTemplate`.

### Costruttore

```php
public function __construct($id, $title, $module, $formData = array(), $resetData = array(), $applyActions = "", $save_formdata_id = "")
```

### Parametri

| Parametro | Descrizione |
|----------|-------------|
| `$formData` | Valori iniziali del form |
| `$resetData` | Valori per il reset |
| `$applyActions` | Azioni personalizzate al salvataggio |
| `$save_formdata_id` | ID per salvataggio stato |

### Metodi di Configurazione

```php
// Labels
$form->SetLabelWidth(150);        // Larghezza label
$form->SetLabelAlign("right");    // Allineamento label
$form->SetLabelPosition("left");  // Posizione label

// Validazione
$form->EnableValidation();        // Abilita validazione form

// Salvataggio
$form->SetSaveTask("Task_Salva");           // Task per salvataggio
$form->enableRefreshOnSuccessfulSave();     // Refresh dopo salvataggio
$form->EnableCloseWndOnSuccessfulSave();    // Chiudi finestra dopo salvataggio

// Bottoni
$form->SetApplyButtonName("Salva");         // Testo bottone applica
$form->SetResetButtonName("Reimposta");    // Testo bottone reset
$form->EnableResetButton(false);           // Disabilita bottone reset
```

### Metodi per Aggiungere Campi

```php
// Campi semplici
$form->AddTextField($name, $label, $props, $newRow);
$form->AddTextareaField($name, $label, $props, $newRow);
$form->AddCheckBoxField($name, $label, $props, $newRow);
$form->AddSwitchBoxField($name, $label, $props, $newRow);
$form->AddDateField($name, $label, $props, $newRow);

// Campi con opzioni
$form->AddSelectField($name, $label, $props, $newRow);  // Dropdown
$form->AddComboField($name, $label, $props, $newRow);  // Combo (search + select)
$form->AddRadioField($name, $label, $props, $newRow);

// Campi avanzati
$form->AddRichtextField($name, $label, $props, $newRow);
$form->AddCkeditor5Field($name, $label, $props, $newRow);
$form->AddFileUploadField($name, $label, $props, $newRow);

// Campi speciali
$form->AddStructField($taskParams, $params, $fieldParams, $newRow);  // Struttura organizzativa
$form->AddSearchField($handler, $handlerParams, $module, $fieldParams, $newRow);  // Ricerca custom

// Altri
$form->AddSection($name, $newRow);    // Intestazione sezione
$form->AddSpacer($newRow);            // Spazio verticale
$form->AddGenericObject($obj, $newRow); // Oggetto generico
```

### Proprietà Comuni dei Campi ($props)

```php
$props = array(
    "required" => true,           // Campo obbligatorio
    "validateFunction" => "IsSelected", // Funzione validazione
    "bottomLabel" => "*Descrizione", // Label sotto il campo
    "labelWidth" => 150,         // Larghezza label
    "width" => 300,              // Larghezza campo
    "readonly" => true,           // Solo lettura
    "disabled" => true,           // Disabilitato
    "hidden" => true,             // Nascosto
    "value" => "valore",          // Valore di default
    "options" => array(           // Opzioni per select/combo
        array("id" => 1, "value" => "Opzione 1"),
        array("id" => 2, "value" => "Opzione 2")
    ),
    "click" => "funzioneJS()"     // Evento click
);
```

---

## 5. AA_GenericFilterDlg

**Scopo**: Creare finestre di dialogo per filtrare i dati nelle liste. Estende `AA_GenericFormDlg`.

### Caratteristiche

- Salva i filtri in sessione
- Applica filtri senza ricaricare la pagina
- Gestione automatica dei valori di filtro

### Metodi Specifici

```php
$filter = new AA_GenericFilterDlg($id, $title, $module);

// Configurazione
$filter->SetSaveFilterId("filter_id");    // ID per salvataggio filtro
$filter->EnableSessionSave();             // Salva in sessione

// Aggiungi campi filtro (come in AA_GenericFormDlg)
$filter->AddTextField("nome", "Nome");
$filter->AddSelectField("stato", "Stato", array("options" => $options));
```

### Comportamento

All'applicazione del filtro:
1. Salva i valori in `module.setRuntimeValue(filter_id, 'filter_data', values)`
2. Chiude la finestra
3. Ricarica i dati della lista con i filtri applicati

---

## 6. AA_FieldSet

**Scopo**: Raggruppare campi correlati in sezioni visive all'interno di un form.

### Costruttore

```php
public function __construct($id = "field_set", $label = "Generic field set", $formId = "", $gravity = 1, $props = array())
```

### Esempio

```php
// Crea il form
$wnd = new AA_GenericFormDlg("id", "Titolo", $module, $formData);

// Crea un fieldset
$anagrafica = new AA_FieldSet("anagrafica_fs", "Dati Anagrafici", $wnd->GetFormId());

// Aggiungi campi al fieldset
$anagrafica->AddTextField("nome", "Nome", array("required" => true));
$anagrafica->AddTextField("cognome", "Cognome", array("required" => true));
$anagrafica->AddDateField("data_nascita", "Data Nascita");

// Aggiungi al form
$wnd->AddGenericObject($anagrafica);

// Crea altro fieldset
$recapiti = new AA_FieldSet("recapiti_fs", "Recapiti", $wnd->GetFormId());
$recapiti->AddTextField("email", "Email");
$recapiti->AddTextField("telefono", "Telefono");

$wnd->AddGenericObject($recapiti, false);  // false = stessa riga
```

### Output HTML Visivo

```
┌────────────────────────────────────────┐
│ Dati Anagrafici                       │
├────────────────────────────────────────┤
│ Nome: [____________]                   │
│ Cognome: [____________]                │
│ Data Nascita: [____________]          │
└────────────────────────────────────────┘
┌────────────────────────────────────────┐
│ Recapiti                               │
├────────────────────────────────────────┤
│ Email: [____________] Telefono: [___] │
└────────────────────────────────────────┘
```

---

## 7. AA_JSON_Template_* - Componenti Base

### AA_JSON_Template_Layout

Layout base per contenitori.

```php
$layout = new AA_JSON_Template_Layout("layout_id", array(
    "type" => "clean",
    "css" => array("background-color" => "#f0f0f0")
));

// Aggiungi righe
$row = new AA_JSON_Template_Layout("", array("type" => "space"));
$layout->AddRow($row);

// Aggiungi colonne
$layout->AddCol(new AA_JSON_Template_Generic("col1", array("view" => "button", "label" => "OK")));
```

### AA_JSON_Template_Form

Wrapper per form Webix.

```php
$form = new AA_JSON_Template_Form("form_id", array(
    "data" => $formData,
    "elementsConfig" => array("labelWidth" => 120)
));
```

### AA_JSON_Template_Toolbar

Toolbar con pulsanti e azioni.

```php
$toolbar = new AA_JSON_Template_Toolbar("toolbar_id", array(
    "css" => "webix_toolbar"
));

$toolbar->AddElement(new AA_JSON_Template_Button("btn_nuovo", array(
    "label" => "Nuovo",
    "icon" => "mdi mdi-plus"
)));
```

---

## 8. AA_GenericPagedSectionTemplate

**Scopo**: Creare sezioni con liste paginate, toolbar e funzionalità CRUD.

### Configurazione Base

```php
$section = new AA_GenericPagedSectionTemplate("section_id", $module->GetId());

// Paginazione
$section->EnablePaging(true);
$section->EnablePager(true);
$section->SetPagerItemForPage(10);
$section->SetPagerItemCount($totalCount);

// Filtraggio
$section->EnableFiltering();
$section->SetFilterDlgTask("GetMioModuloFilterDlg");
$section->SetSaveFilterId("filtro_personalizzato");

// Operazioni
$section->EnableAddNew(true);
$section->SetAddNewDlgTask("GetMioModuloAddNewDlg");

$section->ViewDetail(true);      // Abilita vista dettaglio
$section->ViewPublish(true);      // Abilita pubblicazione
$section->ViewTrash(true);       // Abilita cestinazione
$section->ViewDelete(true);      // Abilita eliminazione
$section->ViewResume(true);      // Abilita ripristino

// Export
$section->EnableSaveAsPdf(true);
$section->EnableSaveAsCsv(true);

// Template contenuto
$section->SetContentBoxTemplate($htmlTemplate);
$section->SetContentBoxData($dataArray);
```

### Template HTML per Item

```php
$contentBoxTemplate = "<div class='AA_DataViewItem'>"
    . "<div>#pretitolo#</div>"
    . "<div><span class='title'>#denominazione#</span></div>"
    . "<div>#tags#</div>"
    . "<div class='subtitle'>#sottotitolo#</div>"
    . "<div><span class='label'>#stato#</span> #dettagli#</div>"
    . "</div>";

$section->SetContentBoxTemplate($contentBoxTemplate);
```

### Metodi Output

```php
$section->toObject();    // Restituisce oggetto
$section->toString();    // Restituisce JSON string
$section->toBase64();    // Restituisce JSON base64
```

---

## 9. Esempi Pratici

### 9.1 Dialog per Inserimento Nuovo Elemento

```php
public function Template_GetMioModuloAddNewDlg()
{
    $id = $this->GetId() . "_AddNew_Dlg_" . uniqid();
    
    // Dati iniziali form
    $form_data = array(
        "nome" => "",
        "descrizione" => "",
        "stato" => 1,
        "data_inizio" => date("Y-m-d")
    );
    
    // Crea il dialog
    $wnd = new AA_GenericFormDlg($id, "Nuovo Elemento", $this->id, $form_data, $form_data);
    
    // Configurazione
    $wnd->SetLabelWidth(150);
    $wnd->SetWidth(800);
    $wnd->SetHeight(500);
    $wnd->EnableValidation();
    $wnd->SetSaveTask("AddNewMioOggetto");
    $wnd->enableRefreshOnSuccessfulSave();
    $wnd->EnableCloseWndOnSuccessfulSave();
    
    // Campi
    $wnd->AddTextField("nome", "Nome", array(
        "required" => true,
        "bottomLabel" => "*Nome identificativo"
    ));
    
    $wnd->AddTextareaField("descrizione", "Descrizione", array(
        "height" => 100,
        "bottomLabel" => "Descrizione dettagliata"
    ));
    
    // Select con opzioni
    $stati = array(
        array("id" => 1, "value" => "Attivo"),
        array("id" => 2, "value" => "Inattivo"),
        array("id" => 3, "value" => "Sospeso")
    );
    $wnd->AddSelectField("stato", "Stato", array(
        "required" => true,
        "options" => $stati
    ));
    
    // Data
    $wnd->AddDateField("data_inizio", "Data Inizio");
    
    // Checkbox
    $wnd->AddCheckBoxField("flag_attivo", "Attivo", array(
        "labelRight" => "Elemento attivo"
    ));
    
    return $wnd;
}
```

### 9.2 Dialog con FieldSet per Dati Complessi

```php
public function Template_GetMioModuloAddNewAlloggioDlg()
{
    $id = $this->GetId() . "_AddNew_Dlg_" . uniqid();
    $form_data = array();
    
    $wnd = new AA_GenericFormDlg($id, "Aggiungi nuovo alloggio", $this->id, $form_data, $form_data);
    $wnd->SetLabelAlign("right");
    $wnd->SetLabelWidth(150);
    $wnd->SetWidth(1280);
    $wnd->SetHeight(600);
    $wnd->EnableValidation();
    $wnd->EnableCloseWndOnSuccessfulSave();
    $wnd->enableRefreshOnSuccessfulSave();
    
    // Campo base
    $wnd->AddTextField("nome", "Denominazione", array(
        "required" => true,
        "bottomLabel" => "*Descrizione dell'alloggio"
    ));
    
    // Ricerca immobile (search field)
    $dlgParams = array(
        "task" => "GetSicarSearchImmobiliDlg", 
        "postParams" => array(
            "form" => $wnd->GetFormId(),
            "field_id" => "immobile",
            "field_desc" => "immobile_desc"
        )
    );
    $wnd->AddSearchField("dlg", $dlgParams, $this->GetId(), array(
        "required" => true,
        "label" => "Immobile",
        "name" => "immobile_desc",
        "bottomLabel" => "*Cerca un immobile esistente"
    ), false);
    
    // Fieldset: Superfici
    $superfici = new AA_FieldSet("superfici_fs", "Dati sulle superfici", $wnd->GetFormId(), 1);
    $superfici->AddTextField("superficie_non_residenziale", "Non residenziale", array(
        "labelWidth" => 120,
        "bottomLabel" => "Valore in metri quadri"
    ));
    $superfici->AddTextField("superficie_utile_abitabile", "Abitabile", array(
        "required" => true,
        "labelWidth" => 120,
        "bottomLabel" => "Superficie utile in mq"
    ), false);
    $wnd->AddGenericObject($superfici);
    
    // Fieldset: Caratteristiche
    $caratt = new AA_FieldSet("caratt_fs", "Caratteristiche", $wnd->GetFormId(), 2);
    $caratt->AddSelectField("fruibile_dis", "Fruibilità disabile", array(
        "required" => true,
        "validateFunction" => "IsSelected",
        "options" => $options
    ));
    $caratt->AddCheckBoxField("ascensore", " ", array(
        "labelWidth" => 10,
        "labelRight" => "Ascensore"
    ), false);
    $wnd->AddGenericObject($caratt, false);
    
    // Note
    $wnd->AddTextareaField("note", "Note", array(
        "bottomLabel" => "Note aggiuntive"
    ));
    
    $wnd->SetSaveTask("AddNewAlloggioSicar");
    return $wnd;
}
```

### 9.3 Dialog Filtro

```php
public function Template_GetMioModuloFilterDlg()
{
    $id = $this->GetId() . "_Filter_Dlg_" . uniqid();
    
    // Recupera valori salvati
    $formData = $this->GetRuntimeValue($this->GetActiveSection(), "filter_data");
    if(!is_array($formData)) $formData = array();
    
    $wnd = new AA_GenericFilterDlg($id, "Filtra elementi", $this->id, $formData, $formData);
    
    $wnd->SetLabelWidth(150);
    $wnd->SetWidth(600);
    $wnd->SetHeight(400);
    $wnd->SetSaveFilterId("mio_filtro");
    $wnd->EnableSessionSave();
    
    // Campi filtro
    $wnd->AddTextField("nome", "Nome");
    $wnd->AddTextField("descrizione", "Descrizione");
    
    // Select stato
    $stati = array(
        array("id" => 1, "value" => "Attivo"),
        array("id" => 2, "value" => "Inattivo")
    );
    $wnd->AddSelectField("stato", "Stato", array("options" => $stati));
    
    // Date
    $wnd->AddDateField("data_dal", "Data dal");
    $wnd->AddDateField("data_al", "Data al");
    
    $wnd->SetApplyButtonName("Applica");
    $wnd->SetResetButtonName("Reimposta");
    
    return $wnd;
}
```

### 9.4 Sezione Paginata

```php
public function TemplateSection_Pubblicate($params = array())
{
    $params['enableAddNewMultiFromCsv'] = false;
    
    // Recupera dati
    $data = $this->GetDataSectionPubblicate_List($params);
    
    // Template HTML per item
    $contentBoxTemplate = "<div class='AA_DataViewItem'>"
        . "<div>#pretitolo#</div>"
        . "<div><span class='AA_DataView_ItemTitle'>#denominazione#</span></div>"
        . "<div>#tags#</div>"
        . "<div><span class='AA_DataView_ItemSubTitle'>#sottotitolo#</span></div>"
        . "<div><span class='AA_Label AA_Label_LightBlue'>#stato#</span> #dettagli#</div>"
        // Campi personalizzati
        . "<div>#campo_personalizzato#</div>"
        . "</div>";
    
    // Usa template generico
    $content = $this->TemplateGenericSection_Pubblicate($params, null);
    $content->SetContentBoxTemplate($contentBoxTemplate);
    
    // Configura funzionalità
    $content->EnableFiltering();
    $content->SetFilterDlgTask("GetMioModuloFilterDlg");
    
    $content->EnableAddNew(true);
    $content->SetAddNewDlgTask("GetMioModuloAddNewDlg");
    
    $content->ViewDetail(true);
    $content->ViewPublish(true);
    $content->ViewTrash(true);
    $content->ViewDelete(true);
    
    $content->EnableSaveAsPdf(true);
    $content->EnableSaveAsCsv(true);
    
    return $content->toObject();
}
```

---

## 10. Riepilogo Metodi

### AA_GenericWindowTemplate

| Metodo | Descrizione |
|--------|-------------|
| `SetId($id)` | Imposta ID finestra |
| `SetWidth($w)` | Imposta larghezza |
| `SetHeight($h)` | Imposta altezza |
| `EnableModal()` | Modalità modale |
| `DisableModal()` | Modalità non-modale |
| `AddView($view)` | Aggiunge componente |
| `toString()` | Output JSON |
| `toBase64()` | Output JSON base64 |

### AA_GenericFormDlg

| Metodo | Descrizione |
|--------|-------------|
| `SetLabelWidth($w)` | Larghezza label |
| `SetLabelAlign($a)` | Allineamento label |
| `EnableValidation()` | Abilita validazione |
| `SetSaveTask($task)` | Task salvataggio |
| `enableRefreshOnSuccessfulSave()` | Refresh dopo save |
| `EnableCloseWndOnSuccessfulSave()` | Chiudi dopo save |
| `AddTextField($name, $label, $props)` | Campo testo |
| `AddTextareaField($name, $label, $props)` | Area testo |
| `AddSelectField($name, $label, $props)` | Select |
| `AddComboField($name, $label, $props)` | Combo |
| `AddCheckBoxField($name, $label, $props)` | Checkbox |
| `AddSwitchBoxField($name, $label, $props)` | Switch |
| `AddDateField($name, $label, $props)` | Data |
| `AddSection($name)` | Sezione |
| `AddSpacer()` | Spazio |
| `AddFieldSet($name, $label, $formId)` | FieldSet |
| `AddStructField(...)` | Campo struttura |
| `AddSearchField(...)` | Campo ricerca |

### AA_GenericPagedSectionTemplate

| Metodo | Descrizione |
|--------|-------------|
| `EnablePaging($b)` | Abilita paginazione |
| `EnableFiltering()` | Abilita filtri |
| `EnableAddNew($b)` | Abilita aggiunta |
| `ViewDetail($b)` | Abilita dettaglio |
| `ViewPublish($b)` | Abilita pubblicazione |
| `ViewTrash($b)` | Abilita cestinazione |
| `ViewDelete($b)` | Abilita eliminazione |
| `ViewResume($b)` | Abilita ripristino |
| `EnableSaveAsPdf($b)` | Abilita export PDF |
| `EnableSaveAsCsv($b)` | Abilita export CSV |
| `SetContentBoxTemplate($t)` | Template item |
| `SetFilterDlgTask($t)` | Task dialog filtro |
| `SetAddNewDlgTask($t)` | Task dialog nuovo |

---

## Riferimento API Webix

I widget Webix vengono definiti come array PHP che vengono poi convertiti in JSON sul client. Di seguito i widget piu comuni usati nelle definizioni dei template.

### ui.layout

Contenitore principale per organizzare altri widget.

```php
$template->AddElement(array(
    "view" => "layout",
    "rows" => array(
        array("template" => "Header", "height" => 50),
        array("cols" => array(
            array("view" => "tree", "width" => 200),
            array("view" => "datatable")
        ))
    )
));
```

**Proprieta principali:**
| Proprieta | Descrizione |
|-----------|-------------|
| `rows` | Array di elementi impilati verticalmente |
| `cols` | Array di elementi affiancati orizzontalmente |
| `width` | Larghezza fissa |
| `height` | Altezza fissa |
| `gravity` | Proporzione relativa |
| `borderless` | Nasconde i bordi |

### ui.text

Campo di testo su singola riga.

```php
$template->AddElement(array(
    "view" => "text",
    "name" => "nome_campo",
    "label" => "Nome",
    "labelWidth" => 100,
    "placeholder" => "Inserisci testo..."
));
```

**Proprieta principali:**
| Proprieta | Descrizione |
|-----------|-------------|
| `name` | Nome del campo (per form) |
| `label` | Etichetta visualizzata |
| `labelWidth` | Larghezza etichetta |
| `labelPosition` | Posizione etichetta ("top" o "left") |
| `placeholder` | Testo segnaposto |
| `value` | Valore predefinito |
| `readonly` | Solo lettura |
| `required` | Campo obbligatorio |
| `validate` | Funzione di validazione |
| `invalidMessage` | Messaggio errore validazione |
| `disabled` | Disabilitato |
| `hidden` | Nascosto |

**Eventi comuni:** `onChange`, `onFocus`, `onBlur`, `onEnter`

### ui.button

Pulsante cliccabile.

```php
$template->AddElement(array(
    "view" => "button",
    "value" => "Salva",
    "css" => "webix_primary",
    "click" => "function() { ... }"
));
```

**Proprieta principali:**
| Proprieta | Descrizione |
|-----------|-------------|
| `value` | Testo del pulsante |
| `css` | Classe CSS (es. "webix_primary") |
| `type` | Tipo ("button", "form", "icon") |
| `icon` | Nome icona (es. "save", "plus") |
| `autowidth` | Adatta larghezza al testo |
| `disabled` | Disabilitato |
| `hotkey` | Scorciatoia tastiera |

**Eventi comuni:** `onItemClick`, `onFocus`, `onBlur`

### ui.combo

Casella combinata con autocomplete.

```php
$template->AddElement(array(
    "view" => "combo",
    "name" => "categoria",
    "label" => "Categoria",
    "options" => array(
        array("id" => 1, "value" => "Opzione 1"),
        array("id" => 2, "value" => "Opzione 2")
    )
));
```

**Proprieta principali:**
| Proprieta | Descrizione |
|-----------|-------------|
| `options` | Array di opzioni (id + value) |
| `value` | Valore predefinito |
| `readonly` | Solo selezione |
| `newValues` | Permette nuovi valori |
| `suggest` | Configurazione suggerimenti |

**Metodi:** `getValue()`, `setValue()`, `getText()`

### ui.checkbox

Casella di selezione.

```php
$template->AddElement(array(
    "view" => "checkbox",
    "name" => "attivo",
    "label" => "Attivo",
    "value" => 1,
    "checkValue" => 1,
    "uncheckValue" => 0
));
```

**Proprieta principali:**
| Proprieta | Descrizione |
|-----------|-------------|
| `label` | Etichetta |
| `labelPosition` | Posizione ("top", "left") |
| `labelRight` | Etichetta a destra |
| `checkValue` | Valore quando selezionato |
| `uncheckValue` | Valore quando deselezionato |
| `customCheckbox` | Tipo checkbox personalizzato |

**Metodi:** `toggle()`, `getValue()`, `setValue()`

### ui.datepicker

Selezione data con calendario.

```php
$template->AddElement(array(
    "view" => "datepicker",
    "name" => "data_inizio",
    "label" => "Data Inizio",
    "format" => "%d/%m/%Y",
    "timepicker" => false
));
```

**Proprieta principali:**
| Proprieta | Descrizione |
|-----------|-------------|
| `format` | Formato data ("%d/%m/%Y") |
| `timepicker` | Include selezione ora |
| `editable` | Input modificabile |
| `readonly` | Solo lettura |
| `multiselect` | Date multiple |
| `stringResult` | Risultato come stringa |
| `icons` | Mostra icone "Oggi" e "Cancella" |

**Metodi:** `getValue()`, `setValue()`, `getText()`

### ui.switch

Interruttore on/off.

```php
$template->AddElement(array(
    "view" => "switch",
    "name" => "abilitato",
    "label" => "Abilitato",
    "value" => 1,
    "onLabel" => "Si",
    "offLabel" => "No"
));
```

**Proprieta principali:**
| Proprieta | Descrizione |
|-----------|-------------|
| `onLabel` | Etichetta stato on |
| `offLabel` | Etichetta stato off |
| `checkValue` | Valore stato on |
| `uncheckValue` | Valore stato off |

### ui.template

Contenitore per HTML personalizzato.

```php
$template->AddElement(array(
    "view" => "template",
    "template" => "<div class='custom-html'>Contenuto HTML</div>",
    "height" => 100
));
```

### ui.datatable

Tabella dati con funzionalita CRUD.

```php
$template->AddElement(array(
    "view" => "datatable",
    "columns" => array(
        array("id" => "id", "header" => "ID", "width" => 50),
        array("id" => "nome", "header" => "Nome", "fillspace" => true),
        array("id" => "azione", "header" => "", "width" => 80)
    ),
    "autowidth" => true,
    "scroll" => "y"
));
```

**Proprieta colonne:**
| Proprieta | Descrizione |
|-----------|-------------|
| `id` | Identificativo campo |
| `header` | Intestazione colonna |
| `width` | Larghezza fissa |
| `fillspace` | Riempie spazio disponibile |
| `sort` | Tipo ordinamento |
| `format` | Formattazione valore |
| `template` | Template cella personalizzato |

### Attributi comuni a tutti i widget

| Proprieta | Descrizione |
|-----------|-------------|
| `id` | Identificativo univoco |
| `name` | Nome per form binding |
| `width` | Larghezza |
| `height` | Altezza |
| `css` | Classe CSS |
| `hidden` | Inizialmente nascosto |
| `disabled` | Inizialmente disabilitato |
| `tooltip` | Messaggio tooltip |
| `on` | Gestori eventi |
| `gravity` | Peso per ridimensionamento |
| `animate` | Animazione visualizzazione |

### Eventi comuni

```php
$template->AddElement(array(
    "view" => "text",
    "name" => "nome",
    "on" => array(
        "onChange" => "function(newv, oldv) { ... }",
        "onFocus" => "function() { ... }",
        "onBlur" => "function() { ... }"
    )
));
```

**Eventi disponibili su piu widget:**
- `onChange` - Valore cambiato
- `onFocus` - Ottenuto focus
- `onBlur` - Perso focus
- `onItemClick` - Cliccato
- `onDestruct` - Componente distrutto
- `onViewShow` - Visualizzato

---

## Riferimenti

- Classi base: `utils/system_ui/`
- Esempi: `utils/modules/sicar_9/lib.php`, `utils/modules/geco_6/lib.php`
- Documentazione Webix: https://docs.webix.com/
