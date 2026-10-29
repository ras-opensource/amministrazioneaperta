# PROJECT_PLAN — Amministrazione Aperta

> **Come**: catalogo classi/servizi/file, milestone con stato, registro operativo.
> Regola di workflow: dettagliare *come* implementare ogni feature prima del codice (§3).

## 1. Catalogo del sistema

### 1.1 Core — `utils/system_core/` (15 file)
| File | Classe | Ruolo |
|---|---|---|
| `AA_Database.php` | `AA_Database` | Connessione PDO al DB relazionale |
| `AA_AccountsDatabase.php` | `AA_AccountsDatabase` | DB conti/utenze (estende `AA_Database`) |
| `AA_Const.php` | `AA_Const` | Costanti: tabelle (`assessorati`, `aa_platform_modules`), stati, permessi |
| `AA_Struct.php` / `AA_Group.php` | omonime | Struttura organizzativa e gruppi |
| `AA_User.php` | `AA_User` | Utente corrente (`GetCurrentUser()`), flag, permessi, SSO/JWT |
| `AA_Object.php` / `AA_Object_V2.php` | omonime | Oggetto generico e V2 (CRUD centralizzato) |
| `AA_Object_Log.php` | `AA_Object_Log` | Log modifiche oggetto |
| `AA_Platform.php` | `AA_Platform` | Singleton piattaforma: moduli abilitati, task manager di sistema |
| `AA_SessionVar.php` / `AA_SessionFileUpload.php` | omonime | Variabili e upload in sessione |
| `AA_Log.php` | `AA_Log` | Log piattaforma |
| `AA_Utils.php` | `AA_Utils` | Utility trasversali |

### 1.2 Libreria moduli — `utils/system_lib/` (56 file)
- **Moduli**: `AA_GenericModule`, `AA_GenericModuleSection`, `AA_GenericModuleTask`,
  `AA_GenericModuleTaskManager` (registrazione/esecuzione task).
- **Task di sistema**: `AA_SystemTask_*` (25+ classi: side menu, gallery, news, log,
  profilo utente, password, upload CKEditor5, struttura organizzativa, status server) +
  `AA_SystemTaskManager`.
- **Template UI**: `AA_GenericWindowTemplate`, `AA_GenericFormDlg`, `AA_GenericFilterDlg`,
  `AA_FieldSet`, `AA_JSON_Template_*`, `AA_GenericPagedSectionTemplate`, dialog generici
  (log, PDF preview, reset password, status server, struttura).
- **Oggetti/entità**: `AA_GenericParsableDbObject`, `AA_GenericParsableObject`,
  `AA_ObjectVarMapping`, `AA_Structura`, `AA_Assessorato`, `AA_Direzione`, `AA_Servizio`.
- **Feed XML**: `AA_XML_FEED*` (pubblicazioni D.Lgs. 33/2013 e s.m.i.).
- Segue la doc di dettaglio: `utils/docs/system_lib.md` e `docs/API.md` (generato).

### 1.3 Client-side
| File | Ruolo |
|---|---|
| `utils/system_lib.js` | Nucleo client: `AA_Task()`, `AA_VerboseTask()`, `AA_Module.*` (`doTask`, `dlg`, `initializeDefault`, `refreshUiObject`, `refreshObjectContent/Data`, `callHandler`) |
| `utils/custom_webix_widgets.js`, `utils/system_custom.js` | Widget e estensioni Webix |
| `utils/modules/<id>/lib.js.php` (o `*_lib.js`) | Handler custom e logica per-modulo |

Guide: `utils/docs/system_lib.js.md`, `event_handlers_guide.md`, `template_ui_guide.md`,
`custom_sections_guide.md`, `module_builder_guide.md`, `modules_workflow.md`, `system_core.md`.

### 1.4 Entry point HTTP
`index.2.0.php` (UI), `utils/system_ops.php` (task di sistema),
`utils/modules/<id>/taskmanager.php` (task modulo), `storage.php` (file pubblici).
Contratti: `docs/SPECIFICATION.md` §Area API + `docs/API.md`.

## 2. Milestone

| ID | Descrizione | Stato |
|---|---|---|
| **M0** | Baseline sandbox: clone repo, struttura documentale workflow (SPEC/PLAN/LOG/TODO/API-doc), generatore API-doc | ✅ fatta (2026-10-29) |
| M1+ | Da definire a ogni feature con `TODO/F<NN>-<slug>.md` | — |

Regola di granularità: ogni voce di checklist TODO deve essere completabile in una
singola sessione; se l'attività è più grande, suddividerla in sotto-milestone.

## 3. Registro operativo (convenzioni)

- **Sandbox-first**: sviluppo e test solo nella copia sandbox; sync sul progetto
  principale solo dopo conferma esplicita dell'utente; `diff -rq` pre-sync con sole
  differenze attese.
- **Branch first**: una branch per feature, nominata come il file TODO (`F<NN>-<slug>`);
  `main` contiene solo stati verificati. Commit: uno per step di checklist completato,
  `<tipo>(<scope>): descrizione` con tipo ∈ `feat|fix|docs|test|chore`.
- **API-doc-first**: prima del codice consultare `docs/API.md` (riutilizzare classi/metodi
  esistenti); dopo ogni cambio di firme in `system_core/`, `system_lib/` o
  `utils/modules/**` rigenerare
  `python3 scripts/gen_api.py` e verificare `sha256sum -c docs/api.sha256`.
- **Test E2E**: suite unica Playwright headless `tests/e2e/e2e.mjs`
  (default `BASE_URL=http://localhost:8081`); estendere la suite a ogni feature UI,
  assert zero console error/pageerror; coprire anche i ruoli secondari.
  ⚠️ La suite non esiste ancora nel repo: da creare alla prima feature con superficie UI
  (o in una milestone dedicata se richiesta).
- **Chiusura feature**: test verdi + `LOG.md` aggiornato + TODO → ✅ + merge branch su `main`.
