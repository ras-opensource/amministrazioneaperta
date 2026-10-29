# Analisi di `utils/system_lib.js` — logica core dell'app (frontend)

**Percorso**: `utils/system_lib.js` (~5 000 righe, ~220 KB)
**Ruolo**: runtime frontend completo di Amministrazione Aperta: modello moduli
(`AA_Module`), singleton dell'app (`AA_MainApp`), client del task manager
(`AA_Task`/`AA_VerboseTask`), interfaccia Webix e dialoghi di autenticazione
(credenziali + **email con OTP**).

> Sostituisce la precedente versione schizzo dello stesso file: alcune descrizioni
> erano imprecise (es. `AA_MainApp` è un object literal, non una classe).

---

## 1. Layout del file

| Righe | Elemento | Descrizione |
|-------|----------|------------|
| 2–~2310 | `function AA_Module(id, name)` | Costruttore dei moduli dell'app (§2) |
| 2311 | `var AA_dummy_module = new AA_Module()` | Modulo dummy (verifiche UI) |
| 2314–~3215 | `var AA_MainApp = { … }` | Singleton dell'app (§3) |
| 3216 | `AA_DefaultSystemInitialization(params)` | Funzione di boot default (§4) |
| 3366 / 3382 | `AA_RegisterModule(m)` / `AA_GetModule(id)` | Registry dei moduli |
| 3395 | `AA_SetCurrentModule(id)` | Imposta il modulo corrente e refresha l'UI |
| 3453 / 3559 | `AA_RefreshMainUi(params)` / `AA_SetupMainUi()` | Costruzione/refresh interfaccia principale (sidebar, navbar, contenuto) |
| 3756–3810 | `AA_AlertModalDlg`, `AA_CustomModalDlg`, `AA_Message`, `AA_ShowWaitMessage`, `AA_HideWaitMessage` | Modali e messaggi globali |
| 3819 | `AA_Sidebar_SelectItem(id, refreshModule, force)` | Navigazione sidebar |
| 3871–3880 | `AA_Sidebar_CustomTemplate/CustomIconTemplate` | Template personalizzati sidebar |
| 3891 / 3907 | `AA_Get(url, postParams)` / `AA_Task(task, taskManagerURL, params, postParams, verbose, raw)` | Client HTTP di basso livello (AJAX) |
| 4005 | `AA_VerboseTask(task, taskManagerURL, params, postParams, raw)` | Wrapper ad alto livello (§5) |
| 4019 | `AA_RefreshNavbarContent(idModule, updateUI, updateOnlyUI)` | Refresh navbar |
| 4083 | `AA_ShowActionMenu()` | Menu azioni del modulo corrente |
| 4123 | `AA_StartAMAAI()` | Avvio servizi esterni (AMAAI) |
| 4148 | `AA_UserAuth(params)` | **Dialogo login — credenziali** (§6) |
| 4341 | `AA_MailUserAuth(params)` | **Dialogo login email — richiesta OTP** (§6) |
| 4513 | `AA_MailUserAuthVerify(params)` | **Dialogo verifica codice OTP** (§6) |
| 4636 | `AA_UserResetPwd(params)` | Dialogo recupero credenziali (via email) |
| 4796 | `AA_UserRegister(params)` | Dialogo registrazione utente |
| 4977 | `AA_RefreshApp(bDisableCache)` | Reload pagina (con opzionale disable cache) |

---

## 2. `AA_Module` — modello dei moduli dell'app

Funzione costruttrice (non `class`) che genera l'oggetto-modulo. Ogni modulo
dell'app è un'istanza registrata con `AA_RegisterModule()`.

**Proprietà principali**: `type="AA_MODULE"`, `id`, `name`, `valid`,
`sections[]`, `curSection`, `globals[]`, `runtimeValues[]`,
`eventHandlers`, `taskManager = "utils/system_ops.php"`, `content`,
`contentType` (`json|xml|text`), `bInitialized`.

**Metodi**:

| Metodo | Ruolo |
|--------|-------|
| `getSection(id)` / `getDetailSection()` | Accesso alle sezioni (dettaglio = quella con `detail == true`) |
| `setCurrentSection(section_id)` | Cambia sezione corrente e refresha la view |
| `doTask(params)` | Esegue un task back-end via `AA_VerboseTask` (§5) e gestisce esito: messaggio, chiusura finestra (`wnd_id`), refresh (oggetto o intera sezione) |
| `dlg(params)` | Apre dialoghi definiti lato server (template Webix + regole di validazione + stato sidemenu) |
| `setGlobal/unsetGlobal/getGlobal` | Variabili globali del modulo |
| `initialize()` | Inizializzazione: carica le sezioni dal back-end, sceglie la sezione default |
| `refreshSectionUi(refreshView, bResetView)` | Refresh dell'interfaccia della sezione corrente |
| `refreshUiObject(idObj, bRefreshContent, bResetView)` | Refresh di un singolo oggetto UI (il metodo più esteso del file: ~420 righe) |
| `refreshCurSection()` | Delega a `refreshSectionUi` per la sezione corrente |
| `getActiveView()` | View Webix attiva |
| `updateSearchParams(params)` / `resetSearchParams(params)` | Stub da sovrascrivere per modulo (parametri di ricerca) |
| `refreshSectionContent/refreshObjectContent/refreshObjectData` | Caricamento dati per sezione/oggetto/dati con `contentType` gestita dal server |
| `menuEventHandler()` / `refreshActionMenuContent()` | Menu azioni del modulo |
| `pagerEventHandler()` | Paginazione (sync `curPage`/`totPages` sui pager) |
| `setRuntimeValue(id, key, value)` / `get` / `unset` | Valori runtime per view (es. `filter_data` passato alle view di dettaglio al cambio pagina) |

**Punto di estensione chiave**: ogni comportamento ha una implementazione
`…Default` assegnata a un alias sovrascrivibile:

```javascript
this.initialize = this.initializeDefault;            // es. riga 311
this.refreshUiObject = this.refreshUiObjectDefault;  // riga 764
```

I moduli (file `lib.js.php` / `*_lib.js` di `utils/modules/<id>/`) sovrascrivono
dopo la creazione dell'istanza. Gli handler predefiniti vivono in
`this.eventHandlers['defaultHandlers']`: `onDetailViewChange`,
`onGenericSectionViewChange`, `adjustRowHeight`, `saveData` (invio form → task),
ecc.

---

## 3. `AA_MainApp` — singleton dell'app

Object literal globale (righe 2314–~3215). Contiene:

- **Config**: `web_url`, `privacy_policy_url`, `bEnableLegacy`, `device.isMobile`,
  `taskManager: "utils/system_ops.php"`, `thisApp`.
- **Boot**: `bootUpFunction: AA_DefaultSystemInitialization` (sostituibile).
- **`utils`**: helper condivisi — `uploader` (wrapper del Webix uploader con
  `fileDialog(params, uploadUrl, cbSuccess, cbError)`), `getMaxZindex()`,
  `isDefined(obj)`, `callHandler(handler, params, module_id)`, alert/message.
- **Parametri di ricerca generici** condivisi tra i moduli
  (`stato_scheda_search`, `stato_cestinata_search`, `goToPage`, …).
- **Hook di autenticazione** (righe ~2573–2578):

```javascript
userAuth: AA_UserAuth,
logIn:    AA_UserAuth,   // riscritti al boot se login via email (§4)
logOut:   AA_LogOut,
resetPwd: AA_UserResetPwd,
register: AA_UserRegister,
```

- **`ui`**: overlay fullscreen `AA_MainOverlay` (logo/branding), più helper
  `alert`, `message`, refresh UI.

---

## 4. Boot — `AA_DefaultSystemInitialization(params)`

1. Legge i parametri URL (`URLSearchParams`).
2. **Modalità login** (righe 3225–3231): se il param `emailLogin` è presente e
   diverso da `"false"` — quindi **di default** — riscrive gli hook:
   `AA_MainApp.logIn = AA_MailUserAuth` e
   `AA_MainApp.userAuth = AA_MailUserAuth`. Con `?emailLogin=false` resta il
   login a credenziali (`AA_UserAuth`).
3. Abilita la gestione cookie (cookie consent).
4. Abilita pull-to-refresh sull'elemento indicato da
   `AA_MainApp.ui.enablePullToRefresh`.

---

## 5. Client del task manager

Flusso UI → back-end:

```
UI (bottone/form) → AA_Module.doTask() / dlg() / eventHandlers.saveData()
    → AA_VerboseTask(task, taskManagerURL, params, postParams, raw)
        → POST utils/system_ops.php  (task = nome operazione)
    ← { status: { value }, content: { value }, error: { value } }
```

- `status.value == 0` → successo: messaggio (`content`/`error`), chiusura
  finestra, refresh UI (`refreshCurSection()` o `refreshUiObject(id)`).
- Diverso da 0 → alert con `error.value`, log console.
- `AA_Task(..., verbose, raw)` e `AA_Get(url, postParams)` sono i livelli bassi;
  `AA_VerboseTask` è il livello usato dai moduli.

---

## 6. Autenticazione — dialoghi login (credenziali e **email + OTP**)

Tre dialoghi Webix modal, tutti POST verso `utils/system_ops.php`. A ogni
successo la pagina viene ricaricata (`window.location.reload()`).

### 6.1 `AA_UserAuth(params)` — credenziali (righe 4148+)

- Window `AA_UserAuthDlg`, form `AA_UserAuth_Form`: campi `user`, `pwd`
  (password), checkbox `remember_me`; link "recupero credenziali" →
  `AA_MainApp.resetPwd()`.
- Bottone **Accedi** (`AA_UserAuth_Apply_btn`, hotkey Enter) → task
  **`UserAuth`** con `{user, pwd, remember_me}`.
- Bottone **Accedi con email** (`AA_UserAuth_EmailLogin_btn`) → chiude il
  dialogo e apre `AA_MailUserAuth()`.

### 6.2 `AA_MailUserAuth(params)` — login email (righe 4341+)

- Window `AA_MailUserAuthDlg`, form `AA_MailUserAuth_Form`: campo `email`
  (regola `webix.rules.isEmail`) + checkbox `remember_me`.
- Bottone **Accedi** (`AA_MailUserAuth_Apply_btn`) → task
  **`MailUserAuth`** con `{email, remember_me}`: il server invia l'email con
  il codice OTP.
- Bottone di ritorno `AA_MailUserAuth_return_btn` ("Accedi con credenziali")
  → torna a `AA_UserAuth()`.

### 6.3 `AA_MailUserAuthVerify(params)` — verifica OTP (righe 4513+)

- Window `AA_MailUserAuthVerifyDlg`, form `AA_MailUserAuthVerify_Form`: campo
  `codice` (regola `webix.rules.isNotEmpty`).
- Bottone **Verifica** (`AA_MailUserAuthVerify_Apply_btn`) → task
  **`MailUserAuthChallengeVerify`** con `{codice}`; a successo → reload.

### 6.4 Note per i test E2E (Playwright)

- Selettori stabili: gli ID Webix sopra (`AA_UserAuthDlg`, `AA_MailUserAuthDlg`,
  `AA_MailUserAuthVerifyDlg`), nomi campi `user`/`pwd`/`email`/`codice`,
  suffisso `_Apply_btn` sui bottone di invio.
- Con `sendmail` disabilitato in config, il codice OTP viene stampato nel log
  server leggibile via `session_log.php` (da interrogare **nello stesso browser
  context** per la stessa sessione): è il caso d'uso standard per testare
  questo flusso senza SMTP.

---

## 7. Schema di sintesi

```
index.2.0.php → system_lib.js
                  ├─ AA_Module (istanza per modulo, registrata con AA_RegisterModule)
                  │    └─ doTask/dlg/eventHandlers → AA_VerboseTask
                  ├─ AA_MainApp (singleton: utils, ui, hook auth, taskManager)
                  │    └─ bootUpFunction = AA_DefaultSystemInitialization
                  │         └─ ?emailLogin≠"false" ⇒ logIn/userAuth = AA_MailUserAuth
                  └─ Auth: AA_UserAuth ⇄ AA_MailUserAuth → AA_MailUserAuthVerify
                          (task: UserAuth / MailUserAuth / MailUserAuthChallengeVerify)
```
