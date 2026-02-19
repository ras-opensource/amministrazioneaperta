# Amministrazione Aperta - Documentazione Architetturale

## Analisi Architetturale

### Struttura del Progetto

```
amministrazioneaperta/
├── utils/                      # Librerie e core del sistema
│   ├── config.php             # Configurazione principale
│   ├── system_core/           # Classi principali (DB, User, Object, Platform)
│   ├── system_lib/            # Classi generiche per moduli
│   ├── system_ui/             # Template UI interattivi
│   ├── modules/               # 10 moduli specifici dell'app
│   └── [varie librerie]       # Webix, CKEditor, jQuery, ecc.
├── index.2.0.php             # Main entry point
├── storage.php                # Servizio file pubblico
├── ws.php                     # Service web/XML per pubblicazioni
├── composer.json             # Dipendenze (MongoDB)
├── LICENSE, README.md, .gitignore
```

---

## Architettura Generale

### Tecnologie Principali
- **PHP** - Back-end con PDO per DB relazionali e MongoDB (via composer)
- **Webix JS** - Framework UI per interfacce interattive
- **CKEditor 5** - Editor di contenuti rich
- **jQuery** - Libreria JS base
- **Material Design Icons & FontAwesome** - Iconografia

### Pattern Architetturali

1. **MVC Parziale**
   - Model: Classi `AA_Object_*`, `AA_Database`
   - View: HTML+Webix + Template JS (`AA_JSON_Template_*`)
   - Controller: `AA_GenericModule` + Task Manager

2. **Pattern Module-based**
   - Ogni modulo in `utils/modules/` con struttura indipendente
   - Istanza `AA_Platform` globale
   - Sistema di template riutilizzabili

3. **Task Manager**
   - Backend AJAX via `system_core.php` chiamato dal frontend
   - API REST-like con parametri JSON

---

## Flusso di Funzionamento

1. **Autenticazione**
   ```php
   AA_User::GetCurrentUser() → AA_Platform::GetInstance()
   ```
   Supporta login tradizionale, token JWT, e SSO con phpSAML

2. **Rendering (index.2.0.php)**
   - Carica CSS/JS globali (Webix, MaterialDesign, ecc.)
   - Loop sui moduli caricati per includere CSS/JS specifici
   - Mostra overlay di caricamento iniziale + logo regione
   - Caricamento manutentore se configurato

3. **Interazione Frontend→Backend**
   - Usano Webix `ext/server()` con payload JSON
   - Task: `AA_SystemTask_GetSideMenuContent`, `AA_GenericModuleTaskManager`
   - Risposte restituite come `{"data": [...]}` JSON

4. **Accesso File Pubblici (storage.php)**
   ```php
   GET /storage.php?object=HASH
   ```
   - Verifica autenticazione tramite hash
   - Restituisce file con header MIME appropriato

5. **Service XML XML (ws.php)**
   - API XML per pubblicazioni (Art.12-14-20 D.Lgs.33/39/2013)
   - Gestisce parametri XML con tag `<param>` e attributo `art`
   - Esempi di categorie:
     - `14_1b`: Dirigenti (art. 14)
     - `14_1d_1e`: Pubblicazioni (art. 14)
     - `20_39`: Dirigenti (art. 20)
     - `pubblicazioni_dirigenti`: Dirigenti (art. 14 e 20)

---

## Moduli Rilevanti

| ID/Nome | Percorso | Funzione |
|---------|----------|----------|
| `sier_5` | `modules/sier_5/` | Module di lavoro con oggetto SIER (ID 4189) |
| `home_1` | `modules/home_1/` | Pagina iniziale |
| `geser_7` | `modules/geser_7/` | ... |
| `sines_2` | `modules/sines_2/` | ... |
| `gecop_8` | `modules/gecop_8/` | ... |
| `patrimonio_3` | `modules/patrimonio_3/` | ... |
| `public` | `modules/public/` | Modulo pubblico (attualmente disabilitato) |

---

## Dati e Database

- Database relazionale (PostgreSQL/MySQL) configurato in `config.php`:
  ```php
  AA_DBHOST="localhost"; AA_DBNAME="amministrazioneaperta"; AA_DBUSER="aauser"; AA_DBPWD="Ab123456";
  ```
- Supporto MongoDB (via `mongodb/mongodb` >= 1.16)
- Almeno 6 tabelle rilevate in `AA_Const.php`:
  - `AA_DBTABLE_ASSESSORATI` = "assessorati"
  - `AA_DBTABLE_MODULES` = "aa_platform_modules"

---

## Sicurezza

- Sessione PHP (`session_start()`)
- Header anti-cache (`Cache-Control: no-store, no-cache, must-revalidate, max-age=0`)
- Caricamento manutentore configurabile con `AA_MANUTENTION = true`
- File system protetto con check hash in `storage.php`
- Support SSO (phpSAML)
- Cookie-consent integrato via `cookie-consent` library

---

## API e Servizi Esterni

- MongoDB storage
- SMTP mail (configurabile in `config.php` con `AA_Enable_Sendmail`
- Frontend responsivo con viewport meta tag

---

## Modello Interazione Usuale

1. Utente si autentica → login tradizionale o SSO
2. Carica `index.2.0.php` → mostra overlay e menu in Webix
3. Interagisce con UI (datatables, form, tab) → chiamate AJAX al backend
4. Backend esegue task (`system_core.php` / taskmanager) → DB
5. Frontend renderizza JSON di risposta → aggiornamento UI
6. Download/copia file pubblico tramite `storage.php`

---

## Configurazione Principale

Percorso: `/home/elia/progetti/amministrazioneaperta/utils/config.php`

Parametri chiave:
- Database connessione
- Permessi utente e livelli
- Stato oggetti (bozza, pubblicata, revisionata, cestinata)
- Permessi (lettura, scrittura, pubblicazione, eliminazione)
- Configurazione SSO
- Parametri SMTP per invio email

---

## Note di Analisi

- Applicativo per gestione amministrativa con focus su pubblicazioni e dirigenti
- Progettato per compliance con Legge Stanca (D.Lgs. 33/2013 e D.Lgs. 39/2013)
- Utilizza pattern architetturali a moduli basati su template generici
- Sistema di logging interno per tracciamento attività
- Supporto per versionamento e workflow di approvazione (bozza → revisionata → pubblicata)
- Architettura modulare per facilitare espansioni future

---

*Generato da Goose AI Assistant*
*Data: 2026-02-17*