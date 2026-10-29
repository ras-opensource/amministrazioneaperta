# LOG — Amministrazione Aperta

Log operativo: cronologia per data, problemi risolti (sintomo → causa → fix),
sezione **Stato attuale** con puntatore al lavoro attivo.
Regole di ripresa e convenzioni: `TODO/README.md` (§8 feature-checkpoint-workflow).

## Stato attuale

- **Lavoro attivo**: nessun lavoro aperto. Suite E2E estesa al flusso login email+OTP,
  verde 5/5; tutto merged in `main` locale e pushato a `origin/main`
  (commit test: `9ce1de0`).
- **Puntatore**: leggere per primo `TODO/STATUS.md`; poi il file TODO non-✅ con NN più alto.
- **Backlog**: F01 da definire = reingegnerizzazione service XML pubblicazioni (ex `ws.php`);
  `utils/lib_ws.php` mantenuto come libreria di funzioni utility, da verificare/aggiornare
  per compatibilità col framework attuale nel momento della reingegnerizzazione.

## Cronologia

### 2026-10-30 — README: rimozione impostazioni accesso DB (doc)
- Su richiesta utente: rimossa da `README.md` (§Dati e Database) la riga con le
  impostazioni di accesso al DB relazionale (`AA_DBHOST`/`AA_DBNAME`/
  `AA_DBUSER`/`AA_DBPWD` con valori reali); il bullet resta senza blocco codice,
  riferendo a `config.php`.
- Verifica: nessun'altra occorrenza delle credenziali nel README; `utils/config_sample.php`
  contiene solo placeholder (`dbuser`/`dbpwd`); `config.php` reale non tracciato in git.
- Nota sicurezza: i valori reali sono già nella storia upstream di `origin/main`
  (presenti almeno da `297b820`, lineage dal commit iniziale `828c814`) →
  rimozione completa richiede history rewrite e/o rotazione della password DB
  (decisione utente).
- API-doc: nessun impatto.
- Branch `docs/readme-remove-db-creds` (da `main`); merge/push su conferma utente.

### 2026-10-30 — Suite E2E estesa al flusso login email+OTP (test)
- Esteso `tests/e2e/e2e.mjs` con il test "autenticazione email/OTP": boot anonimo →
  dialogo `AA_MailUserAuth` (email via `setValues`/`getValues` API webix) → task
  `MailUserAuth` → OTP letto da `/session_log.php` **nello stesso browser context**
  (XHR, perché l'app shadowa il globale `fetch`) → dialogo `AA_MailUserAuthVerify`
  (campo `codice`) → task `MailUserAuthChallengeVerify` → `window.location.reload()` →
  boot autenticato: overlay `#AA_MainOverlay` nascosto (stesso criterio di
  `AA_MainApp.ui.overlay.isVisible`: `display != "block"`), nessun gate anonimo,
  nessun pageerror post-reload.
- Nuovi: `scripts/check_auth_user.php` (preflight CLI utente di test, default
  `esaiucarta@regione.sardegna.it`; exit 0 = trovato e status>0).
- Calibrazione selettori DOM (vedi §Problemi risolti): window webix senza id nel DOM →
  targeting per classe `div.webix_window.AA_Wnd` + `getByRole('button')`;
  interstiziale cookie/privacy accettato prima delle interazioni.
- Suite: **5/5 passed** (run via `sandbox/run_e2e_suite.sh`, log
  `sandbox/logs/run_e2e_suite.log`).
- API-doc: nessun impatto (nessun nuovo endpoint).
- Commit `9ce1de0` su branch `test/e2e-auth-login`, merge fast-forward in `main`,
  push a `origin/main` (autorizzato dall'utente dopo estensione suite E2E).

### 2026-10-29 — Analisi core frontend `utils/system_lib.js` (doc)
- Su richiesta utente: analisi completa di `utils/system_lib.js` (~5.000 righe, runtime
  frontend) con focus sul dialogo login email.
- Sostituito `utils/docs/system_lib.js.md` (schizzo impreciso, es. `AA_MainApp`
  descritto come classe): doc verificata sul codice — layout del file, modello moduli
  `AA_Module` (metodi + punti di estensione `…Default`), singleton `AA_MainApp`, boot
  `AA_DefaultSystemInitialization` (default login email: hook `logIn`/`userAuth`
  riscritti se `?emailLogin` ≠ `"false"`), client task manager (`AA_Task`/`AA_VerboseTask`
  → POST `utils/system_ops.php`), dialoghi auth `AA_UserAuth` / `AA_MailUserAuth` /
  `AA_MailUserAuthVerify` (task `UserAuth`, `MailUserAuth`, `MailUserAuthChallengeVerify`)
  con ID Webix stabili come selettori E2E.
- API-doc: nessun impatto (`docs/API.md` generato; funzioni backend già documentate:
  `AA_User::UserAuth()`, `MailOTPAuthChallenge()`, `MailOTPAuthChallengeVerify()`).

### 2026-10-29 — Rimozione endpoint legacy `ws.php`
- Su richiesta utente: `ws.php` (service XML pubblicazioni, ereditato da vecchia versione)
  rimosso dal codice (`git rm`) e **escluso da API-doc e documenti di progetto**.
- `scripts/gen_api.py`: rimossa la riga hardcoded `POST /ws.php`; `docs/API.md` +
  `docs/api.sha256` rigenerati (119 file PHP, checksum OK).
- Puliti i riferimenti: `README.md` (struttura cartelle + sezione service XML),
  `docs/SPECIFICATION.md` (FR-API-03 rimosso), `docs/PROJECT_PLAN.md` (§1.4 entry point).
- Verifica pre-eliminazione: nessun caller runtime di `ws.php` in JS/PHP del repo →
  l'app non dipende dall'endpoint.
- **Decisione utente**: `utils/lib_ws.php` (funzioni utility usate da `ws.php`) **non**
  viene eliminato; resta come libreria di funzioni, con verifica compatibilità e
  eventuale aggiornamento da fare in un secondo momento (reingegnerizzazione endpoint).
- `.gitignore`: aggiunti `node_modules/`, `test-results/`, `playwright-report/`.

### 2026-10-29 — Chore: API-doc esteso a `utils/modules/**`
- Branch `chore/api-doc-module-scan`: `scripts/gen_api.py` scansiona ora ricorsivamente
  tutti i `.php` sotto `utils/modules/` (sottocartelle incluse, es. `sicar_9/classes/`)
  e cattura anche metodi legacy senza keyword di visibilità (`function foo(...)`).
- Rigenerati `docs/API.md` (119 file PHP: core + lib + 10 moduli) e `docs/api.sha256`;
  verifica `sha256sum -c docs/api.sha256` tutta OK. Spot-check: `AA_SierModule::Task_GetSierModifyDlg()` presente.
- Commits sul branch: `e444be9` (chore scripts), `fe04d85` (docs rigenerati).

### 2026-10-29 — M0: baseline sandbox e struttura documentale
- Clone repo `ras-opensource/amministrazioneaperta` in sandbox (`sandbox/amministrazioneaperta/`),
  branch `main` sincronizzato con `origin/main` (commit upstream `297b820`).
- Analisi README + guide `utils/docs/` (8 documenti) → catalogati core, system_lib,
  client-side, moduli e entry point HTTP.
- Creata struttura documentale workflow: `docs/SPECIFICATION.md`, `docs/PROJECT_PLAN.md`,
  `docs/API.md` (+ `docs/api.sha256`, verificato), `scripts/gen_api.py`, `TODO/` (README,
  _TEMPLATE, STATUS) e questo `LOG.md`.
- Nessun problema aperto.

## Problemi risolti

### 2026-10-30 — Test E2E autenticazione: fallimenti di calibrazione (4 iterazioni)
- **Sintomo 1**: `toBeVisible('#AA_MailUserAuthDlg')` non risolveva mai.
  **Causa**: i window Webix non espongono l'id nel DOM (l'id vive solo nel registry
  interno `$$()`); il nodo finestra è `<div class="webix_view webix_window AA_Wnd">`.
  **Fix**: targeting per classe `div.webix_window.AA_Wnd` + `getByRole('button')`
  (primo bottone = Apply/Accedi).
- **Sintomo 2**: click sul dialogo bloccato da overlay coprente.
  **Causa**: interstiziale cookie/privacy (`#privacypolicies-com---nb-interstiti`,
  widget privacypolicies.com) sopra tutta la UI.
  **Fix**: se presente, accettare (bottone "Accetto") prima delle interazioni.
- **Sintomo 3**: `TypeError: (intermediate value).then is not a function` nel fetch
  dell'OTP.
  **Causa**: l'app shadowa il globale `fetch` (layer task/ajax interno).
  **Fix**: lettura `/session_log.php` via `XMLHttpRequest` in pagina (same-origin,
  stesso cookie PHP session).
- **Sintomo 4**: `Cannot read properties of undefined (reading 'display')` nel poll
  dell'overlay post-reload.
  **Causa**: `page.evaluate(() => document.getElementById(...))` restituisce il nodo
  wrap-pato in un `ElementHandle` → `.style` undefined sull'handle.
  **Fix**: calcolare il boolean dentro la pagina e restituire il valore serializzabile.

## Convenzioni per le sessioni successive

1. Prima di qualsiasi codice: leggere `TODO/STATUS.md`, poi il TODO attivo (NN più alto non-✅).
2. Aggiornare SPEC/PLAN *prima* del codice; spuntare la checklist a ogni step;
   aggiornare "Dove sono ora" nel TODO.
3. Problema risolto → annotarlo qui con sintomo, causa, fix.
4. Ogni variazione tracciata in Git: commit per step completato,
   `<tipo>(<scope>): descrizione` (tipo ∈ `feat|fix|docs|test|chore`).
5. **Debug lato server**: usare `session_log.php` — rende il log della sessione
   corrente (`AA_Utils::GetSessionLog()`, voci più recenti per prime) e con
   `?reset=1` lo svuota (`AA_Utils::ResetSessionLog()`). Le voci arrivano da
   `AA_Log::Log($msg, $level, ...)` nel codice (es. login fallito): attenzione
   che vengono scritte in `$_SESSION['log']` solo con **livello ≥ 100**
   (soglia `AA_Log::$nLogLevel`) o con backtrace esplicito, max 200 voci.
   In E2E (Playwright): dopo l'azione nel browser, fare `GET /session_log.php`
   **nello stesso context** (stesso cookie di sessione) per leggere i log server.
6. **E2E webix/Playwright**: (a) i componenti Webix non espongono l'id nel DOM —
   targeting per classe (`div.webix_window.AA_Wnd`) o via API `$$('id')` dentro
   `page.evaluate`; (b) valori form: `setValues()`/`getValues()` sull'oggetto webix,
   mai manipolazione DOM degli input; (c) `page.evaluate` deve restituire solo valori
   serializzabili (boolean/primitive), non nodi DOM; (d) il globale `fetch` è
   shadowato dall'app → in pagina usare `XMLHttpRequest`; (e) prima delle interazioni
   verificare/accettare l'interstiziale cookie/privacy (`[id^="privacypolicies"]`).
