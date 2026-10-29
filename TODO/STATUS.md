# STATUS — stato e azioni successive

> Sovrascritto integralmente a ogni aggiornamento (non incrementale). Leggere per primo.
**Aggiornato**: 2026-10-30 (README: rimozione percorso locale config — branch in attesa di merge)

## Stato

- **In corso su branch `docs/readme-remove-local-path`** (da `main`, non ancora mergiato):
  `README.md` §Configurazione Principale senza più il riferimento al percorso locale
  macchina → ora `utils/config.php` relativo alla root. Nessun altro riferimento a
  percorsi locali nel README. Attesa conferma utente per merge in `main` + push.

- **Password DB ruotata** (su richiesta utente): account MariaDB `aauser@%`, nuova pwd
  casuale 20 char; aggiornati `AA_DBPWD` + `AA_ACCOUNTS_DBPWD` in entrambi i
  `utils/config.php` (sandbox + `/home/elia/progetti/amministrazioneaperta`, file non
  tracciati). Verifiche: nuova pwd OK, vecchia rifiutata (1045), suite E2E **5/5**
  post-rotazione. Script `sandbox/rotate_db_pwd.sh`, log in `sandbox/logs/`.
- `main` sincronizzato con `origin/main`: README senza credenziali DB spinto
  (`bc0b31c`).

- `main` locale: tutto il lavoro merged — API-doc moduli, rimozione `ws.php`, suite E2E
  smoke, convenzione debug `session_log.php`, analisi core frontend `utils/system_lib.js`,
  **suite E2E estesa al flusso login email+OTP** (commit `9ce1de0`). Ahead di 10 commit
  rispetto a `origin/main`; push autorizzato dall'utente ("dopo che hai esteso la suite E2E")
  ed eseguito in questa sessione.
- Suite E2E: **5/5 passed** — boot anonimo, asset statici, endpoint pubblici e flusso
  autenticazione completo: dialogo `AA_MailUserAuth` → task `MailUserAuth` → OTP letto da
  `/session_log.php` nello stesso browser context (XHR) → `AA_MailUserAuthVerify` → task
  `MailUserAuthChallengeVerify` → reload + boot autenticato (overlay nascosto, nessun gate
  anonimo, nessun pageerror post-reload).
- Utente di test: `esaiucarta@regione.sardegna.it`; preflight CLI
  `php scripts/check_auth_user.php [email]` (exit 0 = trovato e abilitato).
- API-doc: invariato e coerente col codice (`docs/API.md` generato; funzioni auth backend
  già documentate — `AA_User::UserAuth()`, `MailOTPAuthChallenge()`,
  `MailOTPAuthChallengeVerify()`). Nessun nuovo endpoint in questa sessione.
- Tooling debug: convenzione `session_log.php` in `LOG.md` §Convenzioni (punto 5);
  note E2E webix/Playwright in punto 6.

## Azioni successive

1. Backlog F01: reingegnerizzazione service XML pubblicazioni (ex `ws.php`) + verifica
   compatibilità `utils/lib_ws.php` col framework attuale → `TODO/F01-<slug>.md` + branch dedicato.
