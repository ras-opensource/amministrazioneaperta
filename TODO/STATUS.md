# STATUS — stato e azioni successive

> Sovrascritto integralmente a ogni aggiornamento (non incrementale). Leggere per primo.
**Aggiornato**: 2026-10-29 (chore API-doc moduli)

## Stato

- Branch `chore/api-doc-module-scan` (da `main` @ `904a7b7`):
  - `e444be9` chore(scripts): gen_api.py scansione ricorsiva `utils/modules/**` + metodi legacy senza visibilità;
  - `fe04d85` docs(api): API-doc rigenerato — 119 file PHP (core+lib+10 moduli), checksum verificati.
- Test: `sha256sum -c docs/api.sha256` tutti OK; spot-check `AA_SierModule::Task_GetSierModifyDlg()` presente in `docs/API.md`.

## Azioni successive (in attesa di feedback utente)

1. **Conferma merge** branch `chore/api-doc-module-scan` → `main` (fast-forward, 2 commit).
2. Decisione: suite E2E Playwright `tests/e2e/e2e.mjs` alla prima feature UI (oggi assente)?
3. Definizione F01 → `TODO/F01-<slug>.md` + branch dedicato.
