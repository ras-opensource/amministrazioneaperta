# STATUS — stato e azioni successive

> Sovrascritto integralmente a ogni aggiornamento (non incrementale). Leggere per primo.
**Aggiornato**: 2026-10-29 (rimozione ws.php + suite E2E)

## Stato

- Branch `chore/api-doc-module-scan` (da `main` @ `904a7b7`):
  - `e444be9` chore(scripts): gen_api.py scansione ricorsiva `utils/modules/**` + metodi legacy senza visibilità;
  - `fe04d85` docs(api): API-doc rigenerato — 119 file PHP (core+lib+10 moduli), checksum verificati;
  - working tree: **rimozione `ws.php`** (`git rm`) + doc puliti (README/SPEC/PLAN/API.md,
    gen_api.py) + `.gitignore` e2e + suite E2E `tests/e2e/` — da commit.
- Test: `sha256sum -c docs/api.sha256` tutti OK; grep: zero riferimenti a `ws.php` in doc di progetto.
- Decisione utente: `utils/lib_ws.php` **mantenuto** (libreria funzioni utility, ex ws.php);
  verifica compatibilità/aggiornamento al momento della reingegnerizzazione dell'endpoint.

## Azioni successive

1. Run suite E2E sul branch (`npm install` + `npm run test:e2e`) → calibrare asserzioni.
2. Commits sul branch: rimozione ws.php+doc, suite E2E.
3. **Merge in `main` locale** (già autorizzato dall'utente: "poi merge").
4. Push verso GitHub: **in attesa di conferma esplicita utente** (verificare ahead/behind al merge).
5. Backlog F01: reingegnerizzazione service XML pubblicazioni (ex `ws.php`) + verifica
   compatibilità `utils/lib_ws.php` col framework attuale → `TODO/F01-<slug>.md` + branch dedicato.
