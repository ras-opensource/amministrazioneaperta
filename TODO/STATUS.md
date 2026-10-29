# STATUS — stato e azioni successive

> Sovrascritto integralmente a ogni aggiornamento (non incrementale). Leggere per primo.
**Aggiornato**: 2026-10-29 (analisi core frontend `utils/system_lib.js`)

## Stato

- `main` locale: lavoro precedente già merged (API-doc moduli, rimozione `ws.php`,
  suite E2E smoke, convenzione debug `session_log.php`) — **8 commit ahead / 0 behind**
  rispetto a `origin/main`; push in attesa di conferma utente.
- Branch attivo `docs/system-lib-core-analysis` (da `main` @ `a6ad7c7`):
  - `utils/docs/system_lib.js.md` riscritto: analisi verificata del runtime frontend
    (`AA_Module`, `AA_MainApp`, boot, client task manager, dialoghi login credenziali +
    email/OTP con ID Webix stabili per E2E). Working tree da commit.
- API-doc: invariato e coerente col codice (`docs/API.md` generato; funzioni auth backend
  già documentate — `AA_User::UserAuth()`, `MailOTPAuthChallenge()`,
  `MailOTPAuthChallengeVerify()`).
- Tooling debug: convenzione `session_log.php` in `LOG.md` §Convenzioni (punto 5);
  in E2E da leggere nello stesso browser context.

## Azioni successive

1. Commit sul branch: `docs(system_lib): analisi core frontend — MainApp, Module,
   task manager, dialoghi login email+OTP`.
2. **Merge in `main` locale** — attende conferma utente.
3. Push verso GitHub (`origin/main`): **in attesa di conferma esplicita utente**
   (verificare ahead/behind al merge).
4. E2E: estendere la suite al caso autenticato (login email/OTP leggendo il codice OTP
   da `session_log.php` nello stesso browser context) + run smoke completo.
5. Backlog F01: reingegnerizzazione service XML pubblicazioni (ex `ws.php`) + verifica
   compatibilità `utils/lib_ws.php` col framework attuale → `TODO/F01-<slug>.md` + branch dedicato.
