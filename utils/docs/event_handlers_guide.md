# Guida agli Event Handler in Amministrazione Aperta

Questa guida spiega come gestire gli eventi sugli elementi dell'interfaccia in Amministrazione Aperta attraverso tre meccanismi principali:

1. **callHandler**: Funzione per invocare handler dal client
2. **eventHandlers**: Proprieta per collegare eventi UI a handler JavaScript
3. **lib.js.php**: Definizione di handler custom nel modulo

---

## 1. AA_MainApp.utils.callHandler

La funzione `AA_MainApp.utils.callHandler` e il metodo principale per invocare operazioni dal client. Si trova in `utils/system_lib.js`.

### Firma

```javascript
AA_MainApp.utils.callHandler(handler, params, module_id)
```

**Parametri:**
- `handler` (string): Nome dell'handler da invocare
- `params` (object): Parametri da passare all'handler
- `module_id` (string, opzionale): ID del modulo. Se non specificato, usa il modulo corrente

### Handler Integrati

Il sistema supporta handler integrati che vengono automaticamente risolti:

| Handler | Descrizione |
|--------|-------------|
| `dlg` | Apre un dialog/task |
| `setCurrentSection` | Cambia la sezione corrente |
| `wndOpen` | Apre una nuova finestra |
| `pdfPreview` | Anteprima PDF |
| `fileDownload` | Download file |
| `refreshUiObject` | Ricarica un oggetto UI |
| `refreshCurSection` | Ricarica la sezione corrente |
| `goBack` | Torna alla sezione precedente |

### Esempi di Utilizzo

#### Aprire un Dialog

```php
// Da PHP, all'interno di un template
$detail = 'AA_MainApp.utils.callHandler("dlg", 
    {task:"GetMioModuloDetailDlg", params: [{id:"'.$oggetto->GetId().'"}]},
    "'.$this->id.'")';
```

#### Cambiare Sezione

```php
// Navigazione tra sezioni
$onclick = "AA_MainApp.utils.callHandler('setCurrentSection', '".static::AA_ID_SECTION_MIA."', '".$this->GetId()."')";
```

#### Anteprima PDF

```php
// Visualizza PDF
$action = 'AA_MainApp.utils.callHandler("pdfPreview", {url: "storage.php?object='.$fileHash.'"}, "'.$this->id.'")';
```

#### Download File

```php
// Download con nome personalizzato
$downloadAction = 'AA_MainApp.utils.callHandler("fileDownload", {file: "'.$hash.'", name: "'.$nomeFile.'"}, "'.$this->id.'")';
```

#### Aprire Finestra Esterna

```php
// URL esterno in nuova finestra
$viewAction = 'AA_MainApp.utils.callHandler("wndOpen", {url: "'.$curDoc->GetProp("url").'"}, "'.$this->id.'")';
```

#### Combinare con setRuntimeValue

```php
// Imposta filtri e apri dialog
$onclick = "AA_MainApp.curModule.setRuntimeValue('".$id."','filter_data',".json_encode($filter)."); 
AA_MainApp.utils.callHandler('dlg', {task:'GetMioModuloAddNewDlg', postParams: AA_MainApp.curModule.getRuntimeValue('".$id."','filter_data'), module: '".$this->id."'},'".$this->id."')";
```

### Come callHandler Risolve gli Handler

Quando chiami `callHandler`, il sistema cerca l'handler in quest'ordine:

1. `module.eventHandlers.defaultHandlers[handlerName]`
2. `module[handlerName]` (metodo diretto del modulo)
3. `module.eventHandlers[handlerName]`
4. `window[handlerName]` (funzione globale)

---

## 2. Proprieta eventHandlers

La proprieta `eventHandlers` permette di collegare eventi Webix (come `onChange`, `onClick`, `onItemDblClick`) a handler JavaScript definiti nel modulo.

### Struttura

```javascript
eventHandlers: {
    "nomeEvent": {
        "handler": "nomeHandler",
        "module_id": "id_modulo"  // opzionale
    }
}
```

### Esempi in PHP

#### Evento onChange

```php
// Aggiungi un campo checkbox con handler per onChange
$wnd->AddCheckBoxField("Beneficiario_tipo", " ", array(
    "labelRight" => "<b>Persona fisica</b>",
    "eventHandlers" => array(
        "onChange" => array(
            "handler" => "onPersonaFisicaChange",
            "module_id" => $this->GetId()
        )
    )
));
```

#### Evento onItemClick

```php
// Template con evento click
$moduli_view->AddCol(new AA_JSON_Template_Template($id."_ModuleBox_".$moduli_data['id'], array(
    "template" => $riepilogo_template,
    "borderless" => true,
    "data" => array($moduli_data),
    "eventHandlers" => array(
        "onItemClick" => array(
            "handler" => "ModuleBoxClick",
            "module_id" => $this->GetId()
        )
    )
)));
```

#### Evento onItemDblClick

```php
// Doppio click su riga tabella
"eventHandlers" => array(
    "onItemDblClick" => array(
        "handler" => "CoalizioneDblClick",
        "module_id" => $this->GetId()
    )
)
```

#### Evento onResize

```php
// Adjust automatico altezza righe
"eventHandlers" => array(
    "onresize" => array(
        "handler" => "adjustRowHeight",
        "module_id" => $this->GetId()
    )
)
```

#### Evento onTimedKeyPress / onChange (Search)

```php
// Campo ricerca con filtro
$toolbar->AddElement(new AA_JSON_Template_Search("", array(
    "gravity" => 1,
    "filter_id" => $id."_search",
    "placeholder" => "Cerca...",
    "eventHandlers" => array(
        "onTimedKeyPress" => array(
            "handler" => "onFilterStructChange",
            "module_id" => $this->GetId()
        ),
        "onChange" => array(
            "handler" => "onFilterStructChange",
            "module_id" => $this->GetId()
        )
    )
)));
```

---

## 3. File lib.js.php - Handler Custom

Il file `lib.js.php` nella cartella del modulo permette di definire handler JavaScript custom che vengono eseguiti nel contesto del modulo.

### Struttura del File

```php
<?php
// Inclusione del modulo PHP
include_once("lib.php");
header('Content-Type: text/javascript');
?>

// Inizializzazione modulo
var <?php echo MIOMODULO::AA_ID_MODULE?> = new AA_Module("<?php echo MIOMODULO::AA_ID_MODULE?>", "MIOMODULO");
<?php echo MIOMODULO::AA_ID_MODULE?>.valid = true;
<?php echo MIOMODULO::AA_ID_MODULE?>.content = {};
<?php echo MIOMODULO::AA_ID_MODULE?>.contentType = "json";
<?php echo MIOMODULO::AA_ID_MODULE?>.ui.module_content_id = "<?php echo MIOMODULO::AA_UI_MODULE_MAIN_BOX?>";

// Definizione handler custom
<?php echo MIOMODULO::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].nomeHandler = function() {
    try {
        // Logica dell'handler
        // 'this' si riferisce al modulo
        
        // Parametri disponibili: arguments[0], arguments[1], ...
    } catch (msg) {
        console.error("nomeHandler error:", msg, this);
    }
};

// Registrazione modulo
AA_MainApp.registerModule(<?php echo MIOMODULO::AA_ID_MODULE?>);
```

### Esempi di Handler

#### Handler onChange

```javascript
// Handler per cambio valore checkbox
<?php echo MIOMODULO::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].onPersonaFisicaChange = function() {
    try {
        // arguments[0] = nuovo valore
        // arguments[1] = vecchio valore  
        // arguments[2] = 'user' se cambiato dall'utente
        
        if(arguments[2] == 'user') {
            if(arguments[0] == 1) {
                // Mostro dialog di conferma
                let form = this.getFormView();
                if(form) {
                    let form_id = form.config.id;
                    let params = {
                        task: "GetMioModuloConfirmPrivacyDlg",
                        params: [{form: form_id}]
                    };
                    AA_MainApp.utils.callHandler('dlg', params);
                }
            } else {
                // Deselezionato: azzeramento campi
                let form = this.getFormView();
                if(form) {
                    form.setValues({"campo_relazionato": 0}, true);
                }
            }
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.onPersonaFisicaChange", msg, this);
    }
};
```

#### Handler onSave

```javascript
// Handler per gestire salvataggio con conferma revisione
<?php echo MIOMODULO::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].onSave = async function(params) {
    try {
        // params contiene i dati del form
        // Modifico il task per mostrare dialog revisione
        params.task = "GetMioModuloRevisionDlg";
        params.postParams = params.data;
        
        // Chiudo finestra corrente se presente
        if (AA_MainApp.utils.isDefined(params.wnd_id) && $$(params.wnd_id)) {
            $$(params.wnd_id).close();
        }
        
        // Apro dialog revisione
        AA_MainApp.utils.callHandler('dlg', params, '<?php echo MIOMODULO::AA_ID_MODULE?>');
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.onSave", msg, this);
    }
};
```

#### Handler Custom con Parametri

```javascript
// Handler per flag privacy
<?php echo MIOMODULO::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].flagPrivacy = function() {
    try {
        // arguments[0] contiene i parametri passati
        let form = $$(arguments[0].form);
        if(form) {
            let values = {"nome_campo": arguments[0].value};
            form.setValues(values, true);
        }
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.flagPrivacy", msg, this);
    }
};
```

#### Handler adjustRowHeight

```javascript
// Adjust automatico altezza righe datatable
<?php echo MIOMODULO::AA_ID_MODULE?>.eventHandlers['defaultHandlers'].adjustRowHeight = function() {
    try {
        // this si riferisce al componente Webix
        this.adjustRowHeight(null, true);
    } catch (msg) {
        console.error(AA_MainApp.curModule.name + "eventHandlers.adjustRowHeight", msg, this);
    }
};
```

---

## 4. Catena Completa degli Eventi

Ecco come funziona il flusso completo:

### Scenario: Click su pulsante

1. **PHP**: Definisci l'onClick nel template
   ```php
   $button->AddElement(array(
       "view" => "button",
       "value" => "Apri Dialog",
       "click" => "AA_MainApp.utils.callHandler('dlg', {task:'GetMioModuloDetailDlg', params: [{id: 123}]}, 'mio_modulo')"
   ));
   ```

2. **JavaScript**: L'utente clicca il pulsante

3. **callHandler**: Cerca l'handler 'dlg'
   - Trova `AA_MainApp.utils.dlgHandler` o handler custom

4. **Server**: Esegue il task `GetMioModuloDetailDlg`

5. **Risposta**: Il dialog viene visualizzato

### Scenario: Cambio valore campo

1. **PHP**: Definisci eventHandlers nel campo
   ```php
   $form->AddCheckBoxField("campo", "Etichetta", array(
       "eventHandlers" => array(
           "onChange" => array(
               "handler" => "onCampoChange",
               "module_id" => $this->GetId()
           )
       )
   ));
   ```

2. **lib.js.php**: Definisci l'handler
   ```javascript
   MIO_MODULO.eventHandlers['defaultHandlers'].onCampoChange = function() {
       // Logica custom
       this.refreshUiObject('altro_elemento', true);
   };
   ```

3. **JavaScript**: L'utente cambia il valore

4. **Webix**: Attiva l'evento onChange

5. **Sistema**: Trova l'handler in `module.eventHandlers.defaultHandlers.onCampoChange`

6. **Esecuzione**: L'handler viene eseguito nel contesto del modulo

---

## 5. Best Practices

### Naming Convention

- Usa nomi descriptivi: `onPersonaFisicaChange`, `onFilterChange`
- Prefissa con `on` per eventi: `onSave`, `onDelete`
- Usa verbi per azioni: `adjustRowHeight`, `flagPrivacy`

### Gestione Errori

```javascript
handlerName: function() {
    try {
        // Logica handler
    } catch (msg) {
        console.error("handlerName error:", msg, this);
    }
}
```

### Accesso al Modulo

```javascript
// 'this' si riferisce al modulo
this.getId();           // ID modulo
this.oUser;             // Utente corrente
this.curSection;        // Sezione corrente

// Accesso ai metodi del modulo
this.refreshUiObject(id, true);
this.setRuntimeValue(id, key, value);
```

### Accesso al Form/Componente

```javascript
let form = this.getFormView();  // Form che ha generato l'evento
let value = form.getValues();    // Valori correnti
form.setValues({campo: valore}); // Imposta valori
```

---

## 6. Esempio Completo: Dialog con Handler Custom

### PHP - Definizione del Dialog

```php
public function Task_GetMioModuloDetailDlg($task)
{
    $template = new AA_GenericFormDlg(
        static::AA_UI_PREFIX . "_DetailDlg",
        "Dettaglio",
        $this->GetId(),
        "UpdateMioOggetto",
        "800"
    );
    
    // Campo con event handler
    $template->AddCheckBoxField("attivo", "Attivo", array(
        "eventHandlers" => array(
            "onChange" => array(
                "handler" => "onAttivoChange",
                "module_id" => $this->GetId()
            )
        )
    ));
    
    // Campo condizionale
    $template->AddTextField("note", "Note", array(
        "gravity" => 1
    ));
    
    $task->SetContent($template, true, false);
    return true;
}
```

### lib.js.php - Handler

```javascript
// Handler per cambio stato attivo
MIO_MODULO.eventHandlers['defaultHandlers'].onAttivoChange = function() {
    try {
        // Nuovo valore: arguments[0]
        // Vecchio valore: arguments[1]
        // Fonte: arguments[2] ('user' o 'code')
        
        let form = this.getFormView();
        if (!form) return;
        
        if (arguments[0] === 1 && arguments[2] === 'user') {
            // Se attivato, mostra conferma
            webix.confirm({
                title: "Conferma",
                text: "Sei sicuro di voler attivare l'elemento?",
                ok: "Si",
                cancel: "No",
                callback: function(result) {
                    if (!result) {
                        // Annulla: ripristina valore precedente
                        form.setValues({attivo: arguments[1]}, true);
                    }
                }
            });
        } else if (arguments[0] === 0) {
            // Se disattivato, nascondi campo note
            let noteField = form.getChildViews().find(function(view) {
                return view.name === 'note';
            });
            if (noteField) {
                noteField.hide();
            }
        }
    } catch (msg) {
        console.error("onAttivoChange error:", msg, this);
    }
};
```

---

## 7. Riferimenti

### File Analizzati

- `utils/system_lib.js:2416` - Funzione callHandler
- `utils/modules/geco_6/lib.js.php` - Esempio handler custom
- `utils/modules/geco_6/lib.php` - Esempi eventHandlers

### Handler Utili

| Handler | Descrizione |
|--------|-------------|
| `adjustRowHeight` | Ricalcola altezza righe datatable |
| `onPersonaFisicaChange` | Gestione cambio persona fisica/giuridica |
| `onSave` | Pre/post salvataggio con conferma |
| `ModuleBoxClick` | Click su box modulo desktop |
| `onFilterChange` | Cambio filtri di ricerca |
| `onStructSuppress` | Gestione strutture soppresse |

### Eventi Webix Supportati

- `onChange` - Cambio valore
- `onClick` - Click
- `onItemClick` - Click su item
- `onItemDblClick` - Doppio click
- `onresize` - Ridimensionamento
- `onTimedKeyPress` - Input con delay
- `onFocus` - Ottenimento focus
- `onBlur` - Perdita focus
