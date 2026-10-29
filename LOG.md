# LOG — Amministrazione Aperta

Log operativo: cronologia per data, problemi risolti (sintomo → causa → fix),
sezione **Stato attuale** con puntatore al lavoro attivo.
Regole di ripresa e convenzioni: `TODO/README.md` (§8 feature-checkpoint-workflow).

## Stato attuale

- **Lavoro attivo**: branch `chore/api-doc-module-scan` — API-doc moduli + rimozione endpoint
  legacy `ws.php` + suite E2E Playwright; in corso: run E2E, poi commit e merge su `main`.
- **Puntatore**: leggere per primo `TODO/STATUS.md`; poi il file TODO non-✅ con NN più alto.
- **Backlog**: F01 da definire = reingegnerizzazione service XML pubblicazioni (ex `ws.php`);
  `utils/lib_ws.php` mantenuto come libreria di funzioni utility, da verificare/aggiornare
  per compatibilità col framework attuale nel momento della reingegnerizzazione.

## Cronologia

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

_(nessuno)_

## Convenzioni per le sessioni successive

1. Prima di qualsiasi codice: leggere `TODO/STATUS.md`, poi il TODO attivo (NN più alto non-✅).
2. Aggiornare SPEC/PLAN *prima* del codice; spuntare la checklist a ogni step;
   aggiornare "Dove sono ora" nel TODO.
3. Problema risolto → annotarlo qui con sintomo, causa, fix.
4. Ogni variazione tracciata in Git: commit per step completato,
   `<tipo>(<scope>): descrizione` (tipo ∈ `feat|fix|docs|test|chore`).
