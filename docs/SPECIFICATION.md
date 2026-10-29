# SPECIFICATION — Amministrazione Aperta

> **Cosa**: requisiti funzionali, superficie API, modello dati.
> Regola di workflow: aggiornare *prima* del codice per ogni feature (feature-checkpoint-workflow §3).
> Fonti di analisi: `README.md`, guide in `utils/docs/` (8 documenti), `docs/API.md` (generato dal codice).

## 1. Scopo e dominio

Applicativo web PHP per gestione amministrativa di ente pubblico, orientato a pubblicazioni
e compliance con Legge Stanca (D.Lgs. 33/2013, D.Lgs. 39/2013). Architettura modulare:
core (`utils/system_core/`), libreria generica per moduli (`utils/system_lib/`), UI Webix
(`utils/system_lib.js`, `utils/system_ui/`) e 10 moduli applicativi in `utils/modules/`.

## 2. Requisiti funzionali

### Area PLAT — Piattaforma
- **FR-PLAT-01** La piattaforma istanzia una singola `AA_Platform::GetInstance($user)` che carica
  i moduli abilitati (tabella `aa_platform_modules`) e fornisce il task manager di sistema.
- **FR-PLAT-02** `index.2.0.php` è l'unico entry point UI: autenticazione, overlay di caricamento,
  caricamento CSS/JS globali (Webix, CKEditor5, jQuery) e per-modulo, rendering menu laterale.
- **FR-PLAT-03** Ogni modulo vive in `utils/modules/<id>/` con struttura fissa:
  `lib.php` (classe modulo extends `AA_GenericModule`), `taskmanager.php` (entry AJAX),
  `lib.js.php` o `*_lib.js` (client-side), `config.php`, `default.css`, `sql/`.
- **FR-PLAT-04** Il task manager di sistema (`utils/system_ops.php` + `AA_SystemTaskManager`)
  espone i task trasversali (`AA_SystemTask_*`: side menu, gallery, news, log, profilo,
  cambio password, upload CKEditor5, session var).

### Area AUTH — Autenticazione e autorizzazione
- **FR-AUTH-01** Autenticazione a sessione PHP; utente corrente via `AA_User::GetCurrentUser()`.
- **FR-AUTH-02** Supporto SSO (phpSAML) e token JWT oltre al login tradizionale.
- **FR-AUTH-03** Autorizzazione a due livelli: flag utente (per sezione/area, es. visibilità
  sezioni specifiche) e permessi oggetto (lettura/scrittura/pubblicazione/eliminazione)
  — vedi `utils/docs/module_builder_guide.md` §6.
- **FR-AUTH-04** I task del modulo verificano i permessi prima di eseguire operazioni CRUD;
  il protocollo risponde status `-2` su autenticazione mancante → overlay login client.

### Area MOD — Moduli e task
- **FR-MOD-01** Un modulo è una classe `AA_<Nome>Module extends AA_GenericModule` con costante
  `AA_ID_MODULE`; i task si registrano nel costruttore: `$taskManager->RegisterTask("X")`
  risolve il metodo `Task_X()` della classe.
- **FR-MOD-02** Ogni modulo espone sezioni standard (Pubblicate, Bozze, Revisionate, Dettaglio)
  e può aggiungere sezioni custom (`AA_GenericModuleSection`): CRUD datatable, dashboard,
  layout complessi, dati relazionali, azioni bulk, permessi specifici per sezione.
- **FR-MOD-03** I dialog UI sono generati server-side con i template
  (`AA_GenericWindowTemplate`, `AA_GenericFormDlg`, `AA_FieldSet`, `AA_JSON_Template_*`)
  e restituiti come HTML Webix nel contenuto del task.
- **FR-MOD-04** Event handler client: lookup in ordine
  `module.eventHandlers.defaultHandlers` → `module[handler]` → `module.eventHandlers`
  → `window[handler]`; i handler custom si definiscono in `lib.js.php` del modulo.

### Area OBJ — Oggetti e workflow
- **FR-OBJ-01** Gli oggetti gestiti estendono `AA_Object_V2` (o `AA_GenericParsableDbObject`);
  CRUD centralizzato nei task generici: `Task_GenericUpdateObject`, `Task_GenericTrashObject`,
  `Task_GenericPublishObject`.
- **FR-OBJ-02** Workflow di stato oggetto: bozza → revisionata → pubblicata (→ cestinata);
  le transizioni sono gestite dai task workflow del modulo.
- **FR-OBJ-03** Tracciamento attività con `AA_Object_Log` e log piattaforma (`AA_Log`).

### Area UI — Interfaccia
- **FR-UI-01** Frontend Webix: datatable, form, tab, dialog; comunicazione via
  `AA_Task()`/`AA_VerboseTask()`/`AA_Module.doTask()`/`callHandler()`. Dopo ogni task a OK:
  refresh UI (`refresh`, `refresh_obj_id`, `refresh_section`) e eventuale azione successiva.
- **FR-UI-02** Le sezioni con dati remoti implementano `GetDataSection_XXX_List` (AJAX) +
  registrazione del metodo AJAX; supportati `CustomFilter`/`CustomDataTemplate` e
  `AddObjectTemplate`/`RefreshUiObject` per aggiornamento dinamico dei componenti.
- **FR-UI-03** Editor rich CKEditor5; upload file via `AA_SessionFileUpload` / gallery.

### Area API — Superficie HTTP (dettaglio in `docs/API.md`)
- **FR-API-01** Protocollo task: richiesta `taskmanager.php?task=<NomeTask>&<params>`
  (+viewport `vw`,`vh`,`mobile`); risposta XML `<status>` (0 ok / -1 errore / -2 auth),
  `<error>`, `<content>` (base64, type json/html).
- **FR-API-02** `storage.php?object=HASH`: accesso a file pubblici con verifica hash,
  header MIME appropriati.
- **FR-API-03** `ws.php`: service XML per pubblicazioni (categorie art. 12/14/20
  D.Lgs. 33/39/2013, es. `14_1b` Dirigenti, `20_39`, `pubblicazioni_dirigenti`).

### Area SEC — Sicurezza
- **FR-SEC-01** Header anti-cache su tutte le risposte (`no-store, no-cache, must-revalidate`).
- **FR-SEC-02** Modalità manutentore configurabile (`AA_MANUTENTION`); cookie-consent;
  file system protetto (hash) e sessioni PHP.

### Area EXT — Servizi esterni
- **FR-EXT-01** MongoDB disponibile per storage non relazionale (composer `mongodb/mongodb`).
- **FR-EXT-02** Invio email SMTP configurabile in `utils/config.php` (`AA_Enable_Sendmail`)
  con PHPMailer.

## 3. Modello dati

- DB relazionale (PostgreSQL/MySQL) configurato in `utils/config.php`
  (`AA_DBHOST`, `AA_DBNAME`, `AA_DBUSER`, `AA_DBPWD`); accesso via `AA_Database`/PDO e
  `AA_AccountsDatabase`.
- Tabelle note da `AA_Const`: `assessorati`, `aa_platform_modules` (piattaforma);
  gli oggetti dei moduli hanno tabelle proprie definite nei `sql/` di ogni modulo
  (`utils/modules/<id>/sql/`).
- Oggetti: classi `AA_Object_V2` / `AA_GenericParsableDbObject` con mapping campi
  (`AA_ObjectVarMapping`), log (`AA_Object_Log`), stati workflow (FR-OBJ-02).
- Struttura organizzativa: `AA_Struct`, `AA_Group`, `AA_Struttura`,
  `AA_Assessorato`, `AA_Direzione`, `AA_Servizio`.

## 4. Moduli in catalogo

| Dir. | Classe | AA_ID_MODULE | Note |
|---|---|---|---|
| `home_1` | `AA_HomeModule` | `AA_MODULE_HOME` | Pagina iniziale (+ `AA_CorsiFormazione`) |
| `sines_2` | `AA_SinesModule` | `AA_MODULE_SINES` | + `art22_lib.php` (`AA_Organismi`, feed XML art.22) |
| `patrimonio_3` | `AA_PatrimonioModule` | `AA_MODULE_PATRIMONIO` | |
| `provvedimenti_4` | `AA_ProvvedimentiModule` | `AA_MODULE_PROVVEDIMENTI` | |
| `geco_6` | `AA_GecoModule` | `AA_MODULE_GECO` | |
| `geser_7` | `AA_GeserModule` | `AA_MODULE_GESER` | |
| `gecop_8` | `AA_GecopModule` | `AA_MODULE_GECOP` | |
| `sier_5` | `AA_SierModule` | `AA_MODULE_SIER` | Oggetto SIER (ID 4189); + `libSierWebApp.js` |
| `sicar_9` | (classe in `classes/`) | — | Modulo SICAR con sottodir. `classes/` e `docs/` |
| `public` | `AA_PublicModule` | `AA_MODULE_PUBLIC` | Disabilitato nel README corrente |

## 5. Vincoli non funzionali

- Ogni feature: doc prima del codice (questo file + `PROJECT_PLAN.md`), branch dedicato,
  test E2E Playwright (`tests/e2e/e2e.mjs`) per la superficie UI, rigenerazione `docs/API.md`
  se cambiano firme in `system_core/`, `system_lib/` o `utils/modules/**`.
- Nessuna modifica al progetto principale senza conferma esplicita dell'utente (sandbox-first).
