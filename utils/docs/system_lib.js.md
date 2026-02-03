## Analisi di **system_lib.js**

**Percorso**: /home/elia/progetti/amministrazioneaperta/utils/system_lib.js

**Dimensione**: ~ 220 KB, oltre 4 800 linee di codice

**Tecnologie**: JavaScript ES6+ (async/await), Webix UI, interfaccia di “remote‑task” (`AA_VerboseTask`) e utility di `AA_MainApp`.

Di seguito trovi una panoramica delle parti più importanti, con annotazioni sul **comportamento** e sul **ruolo** di ciascuna sezione.

---

### 1️⃣ Struttura principale

```javascript
class AA_MainApp {
    constructor() { … }
    /* vari metodi di utilità */
    doTask = async params => { … }
    dlg = async params => { … }
    /* altri metodi di gestione UI */
}
```

* La classe esposta (`AA_MainApp`) è il **single‑ton** che gestisce tutto l’applicazione: invio di richieste verso il back‑end (tramite `AA_VerboseTask`), apertura di dialoghi Webix, refresh di componenti UI e gestione degli errori.

---

### 2️⃣ Metodo `doTask()` – esecuzione di operazioni di back‑end

| Elemento | Descrizione |
|----------|-------------|
| `AA_VerboseTask(params.task, taskManager, ...)` | Richiesta API. Restituisce un oggetto con `status.value`, `content.value`, ecc. |
| `if (result.status.value == 0)` | Successo: visualizza messaggi, chiude finestre, refresh UI |
| `refreshCurSection()` / `refreshUiObject(id)` | Ricarica elementi specifici dell’interfaccia |
| `AAA_MainApp.utils.callHandler` | Invoca callback specificato in `result.status.action` |
| `else` | Gestione di errori: log, alert, ritorno `false` |
| `catch (msg)` | Log di eccezione e ritorno `false` |

**Funzione principale**: centralizzare la logica di *invio + riscontro* tra UI e back‑end.

---

### 3️⃣ Metodo `dlg()` – gestione di dialoghi dinamici

| Sezione | Descrizione |
|---------|-------------|
| Parametri | `params`, `taskManager`, `task`, `params`, `postParams` |
| `AA_VerboseTask` | Ottiene il template HTML/JS da mostrare |
| `webix.ui(result.content.value)` | Crea l’interfaccia Webix con il contenuto ricevuto |
| **Validazione** | Se un form ha `validation`, imposta regole (`form.config.rules = { $all: … }`) |
| **Sidemenu state** | Aggiorna le funzioni di stato per eventuali sidemenu nell’interfaccia |
| **Restituisce** | `true` se tutto va bene, altrimenti `false` e log di errore |

In pratica è l’API interna che apre *dialoghi complessi* definendoli sul server e passando la logica/validazione al client.

---

### 4️⃣ Altre funzioni di supporto

| Funzione | Scopo |
|----------|-------|
| `AA_RefreshApp(bDisableCache)` | Ricarica la pagina, controllando se l’URL deve essere salvato con `disable_cache=true` |
| `AA_UserRegisterDlg()` | Mostra la finestra di registrazione utente con Webix form (campo email, nome, cognome, privacy, ecc.) |
| `AA_MainApp.utils` | Gruppo di helper: `isDefined`, `getEventHandler`, `callHandler`, `alert`, `message`, ecc. |
| `AA_VerboseTask` | Wrapper per le chiamate AJAX, gestisce l’affettuoso logging/debugging. |

---

### 5️⃣ Comportamento tipico

1. **Richiedi** un *task* dal servizio (`doTask` o `dlg`).
2. **Ricevi** un oggetto con uno status e un payload.
3. **Mostra** messaggi/alert o chiudi finestre in base al risultato.
4. Se è necessario, **aggiorna** singoli componenti UI tramite `refreshUiObject`.
5. Per i dialoghi, **instanzio** una UI Webix con il markup ricevuto, applico le regole di validazione e i callback di stato.
6. **Invio** eventuali callback (`callHandler`) al server.

---

## 🔍 Semplificando la lettura

- **Funzioni principali**: `doTask`, `dlg`, `AA_RefreshApp`.
- **Utilità di UI**: `webix.ui(...).show()`, `form.setValues(...)`, `form.config.rules`.
- **Gestione errori**: `console.error`, `AA_MainApp.ui.alert(msg)`.
- **Semplifica**: la maggior parte della logica di business è delegata al server; questo file è l’interfaccia “glue” tra UI Webix e API.

---

## Dettagli su **AA_Module**

<details>
<summary>Espandi/collassa informazioni</summary>

### 📗 **Cos’è?**
> Una *classe* che rappresenta un modulo di Amministrazione Aperta. È il “controller” che
> gestisce
tutte le **sezioni** dell’interfaccia, avvia **task** sul back‑end, mostra *dialoghi* in Webix e memorizza lo stato UI.

### 🧩 **Principali componenti**
| Componente | Funzione | Key methods |
|------------|----------|-------------|
| Sezioni | Lista di sezioni per modulo | `getDetailSection()`, `setCurrentSection()` |
| Task | Invoco sul server | `doTask()` |
| Dialoghi | Mostro finestre dinamiche | `dlg()` |
| Variabili globali | Condivise in più moduli | `setGlobal()`, `getGlobal()`, `unsetGlobal()` |
| UI Refresh | Salva/riapplica stato (tab, datatable ecc.) | `refreshSectionUiDefault()`, `refreshUiObjectDefault()` |
| Inizializzazione | Carica sezioni da server | `initializeDefault()` |

### 🧮 **Flow di lavoro**
1. **Creazione** – `new AA_Module(id, name)`
2. **Inizializzazione** – `await mod.initializeDefault()` → carica `sections`
3. **Selezione sezione** – `await mod.setCurrentSection(id)` → renderizza la UI
4. **Task** – `await mod.doTask({task:'...',params:{...}})` → API + UI update
5. **Dialoghi** – `await mod.dlg(...)` → fine‑state UI

### 📦 **Struttura interna** (snippet)
```js
function AA_Module(id='AA_MODULE_DUMMY', name='Modulo generico'){
  this.id=id; this.name=name;
  this.sections=[]; this.curSection={id:'fake_section',name:'fake section',valid:false};
  this.globals=[];
  // … altre proprietà …
}
```

**Nota**: Il modulo si integra con `AA_MainApp`, che funge da *singleton* globale.
</details>
