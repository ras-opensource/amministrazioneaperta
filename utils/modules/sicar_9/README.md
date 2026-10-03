# Modulo SICAR - Sistema Informativo Catasto e Amministrazione Risorse

## Descrizione

Il modulo **SICAR** è un sistema completo per la gestione informatizzata della capacita' abitativa Regionale. Permette di gestire in modo integrato:

- **Immobili**: dati catastali, urbanistici e caratteristiche
- **Alloggi**: assegnazione a nuclei familiari, stato occupazione, interventi
- **Nuclei Familiari**: gestione anagrafica e assegnazioni
- **Enti**: operatori e gestione relazioni
- **Finanziamenti**: programmi e richieste
- **Graduatorie**: gestione graduatorie per assegnazione alloggi

## Struttura del Modulo

### File Principali

| File | Funzione |
|------|----------|
| `lib.php` | Autoloader classi (carica automaticamente da `classes/`) |
| `taskmanager.php` | Entry point AJAX per le operazioni del modulo |
| `config.php` | Configurazione (include system_lib) |
| `default.css` | Stili CSS personalizzati |

### Directory

| Path | Contenuto |
|------|-----------|
| `classes/` | Classi PHP del modulo |
| `sql/` | Script SQL per la creazione tabelle |
| `docs/` | Documentazione aggiuntiva (SRS PDF) |

---

## Architettura

### Pattern AA_GenericModule

Il modulo segue l'architettura standard di Amministrazione Aperta:

- **Ereditarietà**: `AA_SicarModule` estende `AA_GenericModule`
- **Task Management**: utilizza `AA_GenericModuleTaskManager` per registrazione ed esecuzione task
- **Sezioni UI**: implementa sezioni custom con navbar e template Webix
- **Permessi**: integrazione con il sistema di permessi tramite flag utente

### Autoloader Classi

Il file `lib.php` registra un autoloader SPL che carica automaticamente le classi dalla directory `classes/`:

```php
spl_autoload_register(function ($className) {
    $filePath = __DIR__ . '/classes/' . $className . '.php';
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});
```

### Sistema di Task

I task sono implementati come metodi nella classe `AA_SicarModule` seguendo la convenzione:
- **Nome metodo**: `Task_[NomeTask]`
- **Parametro**: Oggetto `AA_GenericTask`
- **Ritorno**: `true` per successo, `false` per errore
- **Gestione stato**: `SetStatus()`, `SetContent()`, `SetError()`

I task vengono registrati nel costruttore del modulo tramite `$taskManager->RegisterTask()`.

---

## Classi Principali

### AA_SicarModule (classe principale)

**File**: `classes/AA_SicarModule.php` (~387KB)

Classe principale del modulo che estende `AA_GenericModule`. Gestisce:
- Registrazione task e sezioni UI
- Template navbar e layout
- Rendering dati per sezioni (bozze, pubblicate, dettaglio)
- Integrazione con tutti i sotto-moduli (immobili, alloggi, nuclei, enti, finanziamenti)

**Costanti principali:**
```php
const AA_ID_MODULE = "AA_MODULE_SICAR";
const AA_UI_PREFIX = "AA_Sicar";
const AA_MODULE_OBJECTS_CLASS = "AA_SicarAlloggio";
```

### AA_SicarImmobile

**File**: `classes/AA_SicarImmobile.php` (~17KB)

**Estende**: `AA_GenericParsableDbObject`

Gestisce gli immobili con tutti i dati catastali e urbanistici.

**Tabella database**: `aa_sicar_immobili`

**Proprietà:**
| Proprietà | Tipo | Descrizione |
|-----------|------|-------------|
| `descrizione` | text | Nome/descrizione dell'immobile |
| `tipologia` | int | ID tipologia (tabellato) |
| `comune` | string | Codice ISTAT del Comune |
| `ubicazione` | int | ID ubicazione (tabellato) |
| `indirizzo` | text | Indirizzo completo con numero civico |
| `catasto` | JSON | Dati catastali (foglio, mappale, particella, subalterno, sezione) |
| `zona_urbanistica` | int | ID zona urbanistica |
| `piani` | int | Numero di piani |
| `attributi` | JSON | Caratteristiche (condominio misto, gestione, alloggio_count) |
| `interventi` | JSON | Storico interventi |
| `geolocalizzazione` | text | Coordinate o riferimento geografico |
| `note` | textarea | Note aggiuntive |

**Template View Props**: configurato con aree, colonne e righe per il rendering Webix.

**Metodi principali:**
- `Validate()` - Validazione campi obbligatori
- `GetDisplayName()` - Restituisce "descrizione - indirizzo (comune)"
- `GetCatasto($bAsObject)` - Ritorna oggetto JSON o stringa
- `GetAttributi($bAsObject)` - Ritorna array di attributi
- `GetGestore()` - Ritorna oggetto `AA_SicarEnte` gestore
- `GetNumeroAlloggiTot()` - Conteggio alloggi associati
- `GetListaImmobili($comune)` - Lista statica filtrata per comune
- `GetAlloggi($bBozze, $bCestinate)` - Alloggi associati

### AA_SicarAlloggio

**File**: `classes/AA_SicarAlloggio.php` (~18KB)

**Estende**: `AA_Object_V2`

Gestisce gli alloggi e la loro assegnazione.

**Tabelle database:**
- `aa_sicar_data` (dati principali)
- `aa_sicar_objects` (versionamento)

**Proprietà:**
| Proprietà | Descrizione |
|-----------|-------------|
| `immobile` | ID immobile associato |
| `tipologia_utilizzo` | Tipo di utilizzo (tabellato) |
| `stato_conservazione` | Stato conservativo (tabellato) |
| `anno_ristrutturazione` | Anno ultima ristrutturazione |
| `superficie_utile_abitabile` | Superficie abitabile |
| `superficie_non_residenziale` | Superficie non residenziale |
| `superficie_parcheggi` | Superficie parcheggi |
| `vani_abitabili` | Numero vani |
| `piano` | Piano dell'alloggio |
| `ascensore` | Presenza ascensore |
| `fruibile_dis` | Fruibilità disabilità |
| `note` | Note |
| `gestione` | JSON gestione |
| `proprieta` | JSON proprietà |
| `occupazione` | JSON occupazione attuale |
| `interventi` | JSON interventi storici |

**Metodi principali:**
- `GetImmobile($bAsObject)` - Ritorna l'immobile associato
- `GetTipologiaUtilizzo($asText)` - Tipologia testuale o ID
- `GetStatoConservazione($asText)` - Stato testuale o ID
- `GetOccupazione()` - Gestione occupazione corrente
- `AddNew($params, $user)` - Creazione statica

### AA_SicarNucleo

**File**: `classes/AA_SicarNucleo.php` (~16KB)

**Estende**: `AA_GenericParsableDbObject`

Gestisce i nuclei familiari.

**Tabella database**: `aa_sicar_nuclei`

**Proprietà:**
| Proprietà | Descrizione |
|-----------|-------------|
| `descrizione` | Nome/descrizione nucleo |
| `cf` | Codice fiscale |
| `comune` | Comune di residenza |
| `indirizzo` | Indirizzo di residenza |
| `note` | Note |
| `alloggio_attuale` | ID alloggio assegnato |
| `storico_assegnazioni` | JSON storico |

### AA_SicarEnte

**File**: `classes/AA_SicarEnte.php` (~11KB)

**Estende**: `AA_GenericParsableDbObject`

Gestisce gli enti e i loro operatori.

**Tabella database**: `aa_sicar_enti`

**Proprietà:**
| Proprietà | Descrizione |
|-----------|-------------|
| `denominazione` | Nome ente |
| `tipologia` | ID tipologia ente (tabellato) |
| `indirizzo` | Indirizzo |
| `web` | Sito web |
| `pec` | PEC |
| `geolocalizzazione` | Coordinate |
| `operatori` | JSON contatti/operatori |
| `note` | Note |

### AA_SicarFinanziamento

**File**: `classes/AA_SicarFinanziamento.php` (~7.6KB)

**Estende**: `AA_GenericParsableDbObject`

Gestisce i programmi di finanziamento.

**Tabella database**: `aa_sicar_finanziamenti`

### AA_SicarGraduatoria

**File**: `classes/AA_SicarGraduatoria.php` (~7KB)

**Estende**: `AA_GenericParsableDbObject`

Gestisce le graduatorie per assegnazione alloggi.

**Tabella database**: `aa_sicar_graduatorie`

### AA_SicarRichiestaFinanziamento

**File**: `classes/AA_SicarRichiestaFinanziamento.php` (~7.4KB)

**Estende**: `AA_GenericParsableDbObject`

Gestisce le richieste di finanziamento.

**Tabella database**: `aa_sicar_richieste_finanziamento`

### AA_Sicar_Const

**File**: `classes/AA_Sicar_Const.php` (~18KB)

Costanti e metodi statici per i dati tabellati:
- Tipologie immobili, enti, programmi finanziamento
- Ubicazioni, zone urbanistiche, comuni ISTAT
- Stati conservazione alloggi, stato lavori interventi
- Flag utente: `AA_USER_FLAG_SICAR = "sicar"`

---

## Sezioni UI

Il modulo definisce le seguenti sezioni principali:

| ID | Nome | Icona | Descrizione |
|----|------|-------|-------------|
| `sicar_desktop` | Cruscotto | `mdi mdi-desktop-classic` | Dashboard principale |
| `GestImmobili` | Gestione immobili | `mdi mdi-office-building-marker` | Lista e dettaglio immobili |
| `GestEnti` | Gestione enti | `mdi mdi-home-group` | Lista e dettaglio enti |
| `GestNuclei` | Gestione nuclei | `mdi mdi-account-group` | Lista e dettaglio nuclei familiari |
| `GestFinanziamenti` | Gestione finanziamenti | `mdi mdi-cash-fast` | Programmi di finanziamento |
| `GestGraduatorie` | Gestione graduatorie | `mdi mdi-format-list-numbered` | Graduatorie assegnazione |
| `GestTables` | Enti, immobili e nuclei | `mdi mdi-table` | Tabelle dati generiche |
| `Bozze` | Alloggi (bozze) | - | Alloggi in stato bozza |
| `Pubblicate` | Alloggi (pubblicate) | `mdi mdi-home-city` | Alloggi pubblicati |

---

## Task Registrati

### Task Standard (Gestione Oggetti)
| Task | Metodo | Descrizione |
|------|--------|-------------|
| `GetSicarPubblicateFilterDlg` | - | Dialog filtro pubblicate |
| `GetSicarBozzeFilterDlg` | - | Dialog filtro bozze |
| `GetSicarReassignDlg` | - | Dialog riassegnazione |
| `GetSicarPublishDlg` | - | Dialog pubblicazione |
| `GetSicarTrashDlg` | - | Dialog cestinazione |
| `GetSicarResumeDlg` | - | Dialog ripristino |
| `GetSicarDeleteDlg` | - | Dialog eliminazione |
| `GetSicarAddNewDlg` | - | Dialog nuovo alloggio |
| `GetSicarModifyDlg` | - | Dialog modifica alloggio |

### Task Immobili
| Task | Descrizione |
|------|-------------|
| `GetSicarAddNewImmobileDlg` | Dialog nuovo immobile |
| `AddNewImmobileSicar` | Creazione immobile |
| `GetSicarModifyImmobileDlg` | Dialog modifica immobile |
| `UpdateImmobileSicar` | Aggiornamento immobile |
| `GetSicarDeleteImmobileDlg` | Dialog eliminazione immobile |
| `DeleteImmobileSicar` | Eliminazione immobile |
| `GetSicarDetailImmobileDlg` | Dettaglio immobile |
| `GetSicarInterventiImmobileDlg` | Interventi immobile |
| `GetSicarAddNewInterventoImmobileDlg` | Nuovo intervento |
| `AddNewInterventoImmobileSicar` | Creazione intervento |
| `GetSicarModifyInterventoImmobileDlg` | Modifica intervento |
| `UpdateInterventoImmobileSicar` | Aggiornamento intervento |
| `GetSicarDeleteInterventoImmobileDlg` | Eliminazione intervento |
| `DeleteInterventoImmobileSicar` | Eliminazione intervento |

### Task Enti
| Task | Descrizione |
|------|-------------|
| `GetSicarAddNewEnteDlg` | Dialog nuovo ente |
| `AddNewEnteSicar` | Creazione ente |
| `GetSicarModifyEnteDlg` | Dialog modifica ente |
| `UpdateEnteSicar` | Aggiornamento ente |
| `GetSicarOperatoriEnteDlg` | Operatori ente |
| `GetSicarSearchEntiDlg` | Ricerca enti |

### Task Nuclei
| Task | Descrizione |
|------|-------------|
| `GetSicarAddNewNucleoDlg` | Dialog nuovo nucleo |
| `AddNewNucleoSicar` | Creazione nucleo |
| `GetSicarModifyNucleoDlg` | Dialog modifica nucleo |
| `UpdateNucleoSicar` | Aggiornamento nucleo |
| `GetSicarSearchNucleiDlg` | Ricerca nuclei |
| `GetSicarDeleteNucleoDlg` | Dialog eliminazione nucleo |
| `DeleteNucleoSicar` | Eliminazione nucleo |

### Task Stato Occupazione Alloggio
| Task | Descrizione |
|------|-------------|
| `GetSicarAddNewStatoOccupazioneAlloggioDlg` | Nuovo stato occupazione |
| `AddNewStatoOccupazioneAlloggioSicar` | Creazione stato occupazione |
| `GetSicarModifyStatoOccupazioneAlloggioDlg` | Modifica stato occupazione |
| `UpdateStatoOccupazioneAlloggioSicar` | Aggiornamento stato occupazione |
| `GetSicarDeleteStatoOccupazioneAlloggioDlg` | Eliminazione stato occupazione |
| `DeleteStatoOccupazioneAlloggioSicar` | Eliminazione stato occupazione |
| `GetSicarDetailStatoOccupazioneAlloggioDlg` | Dettaglio stato occupazione |

### Task Stato Interventi Alloggio
| Task | Descrizione |
|------|-------------|
| `GetSicarAddNewStatoInterventiAlloggioDlg` | Nuovo stato interventi |
| `AddNewStatoInterventiAlloggioSicar` | Creazione stato interventi |
| `GetSicarModifyStatoInterventiAlloggioDlg` | Modifica stato interventi |
| `UpdateStatoInterventiAlloggioSicar` | Aggiornamento stato interventi |
| `GetSicarDeleteStatoInterventiAlloggioDlg` | Eliminazione stato interventi |
| `DeleteStatoInterventiAlloggioSicar` | Eliminazione stato interventi |
| `GetSicarDetailStatoInterventiAlloggioDlg` | Dettaglio stato interventi |

### Task CRUD Alloggi
| Task | Descrizione |
|------|-------------|
| `AddNewAlloggioSicar` | Creazione alloggio |
| `UpdateSicar` | Aggiornamento alloggio |
| `DeleteSicar` | Eliminazione alloggio |
| `PublishSicar` | Pubblicazione alloggio |
| `TrashSicar` | Cestinazione alloggio |
| `ResumeSicar` | Ripristino alloggio |
| `ReassignSicar` | Riassegnazione alloggio |

### Task Dati Tabellati e Utilità
| Task | Descrizione |
|------|-------------|
| `GetSicarTipologie` | Recupero tipologie immobili |
| `GetSicarUbicazioni` | Recupero ubicazioni |
| `GetSicarZoneUrbanistiche` | Recupero zone urbanistiche |
| `GetSicarComuni` | Recupero comuni (codici ISTAT) |
| `ExportSicarCsv` | Esportazione CSV |
| `GetSicarListaCodiciIstat` | Lista codici ISTAT |
| `GetSicarSearchImmobiliDlg` | Dialog ricerca immobili |

---

## Relazioni tra Entità

```
                    ┌──────────────┐
                    │ AA_SicarEnte │
                    └──────┬───────┘
                           │ gestisce (1:n)
                    ┌──────▼───────┐     ┌──────────────────┐
                    │AA_SicarImmobile│◄────│ AA_SicarAlloggio │
                    └──────┬───────┘     └──────────────────┘
                           │ contiene (1:n)         │ occupa (1:1)
                    ┌──────▼───────┐                │
                    │ Interventi   │◄───────────────┘
                    └──────────────┘

                    ┌──────────────────┐
                    │AA_SicarNucleo    │
                    └──────────────────┘
                           │ assegna (1:1)
                    ┌──────▼───────┐
                    │AA_SicarAlloggio│
```

### Relazioni Principali
- **Immobile → Alloggi**: 1:n (un immobile contiene molti alloggi)
- **Ente → Immobili**: 1:n (un ente gestisce molti immobili)
- **Nucleo → Alloggio**: 1:1 (un nucleo ha un alloggio assegnato)
- **Alloggio → Occupazione**: 1:1 (stato occupazione corrente)
- **Immobile → Interventi**: 1:n (storico interventi)
- **Alloggio → Interventi**: 1:n (storico interventi sull'alloggio)

---

## Struttura Database

### Tabelle Dati Principali

| Tabella | Classe | Descrizione |
|---------|--------|-------------|
| `aa_sicar_immobili` | `AA_SicarImmobile` | Dati immobili |
| `aa_sicar_data` | `AA_SicarAlloggio` | Dati alloggi (con versionamento) |
| `aa_sicar_objects` | `AA_SicarAlloggio` | Versioni oggetti alloggio |
| `aa_sicar_nuclei` | `AA_SicarNucleo` | Nuclei familiari |
| `aa_sicar_enti` | `AA_SicarEnte` | Enti e organizzazioni |
| `aa_sicar_finanziamenti` | `AA_SicarFinanziamento` | Programmi finanziamento |
| `aa_sicar_graduatorie` | `AA_SicarGraduatoria` | Graduatorie |
| `aa_sicar_richieste_finanziamento` | `AA_SicarRichiestaFinanziamento` | Richieste finanziamento |

### Tabelle Tabellati (Dizionario)

| Tabella | Utilizzato da |
|---------|---------------|
| `aa_sicar_tipologie_immobile` | Tipologie immobili |
| `aa_sicar_tipologie_ente` | Tipologie enti |
| `aa_sicar_tipologie_finanziamenti` | Tipologie programmi finanziamento |
| `aa_sicar_ubicazioni` | Ubicazioni comunali |
| `aa_sicar_zone_urbanistiche` | Zone urbanistiche |
| `aa_sicar_stati_conservazione_alloggio` | Stati conservazione alloggi |
| `aa_sicar_stato_lavori` | Stato lavori interventi |

---

## Workflow Tipico

### 1. Creazione Nuovo Immobile

```
Frontend (Webix UI)
    ↓ click "Nuovo"
callHandler('dlg', {task: 'GetSicarAddNewImmobileDlg'})
    ↓
taskmanager.php → TaskManager->RunTask()
    ↓
Task_GetSicarAddNewImmobileDlg($task)
    ↓ renderizza form HTML
Frontend → webix.ui(result.content.value)
    ↓ utente compila e conferma
callHandler('action', {task: 'AddNewImmobileSicar'})
    ↓
Task_AddNewImmobileSicar() → AA_SicarImmobile::AddNew()
    ↓ validazione + DB insert
Risposta XML/JSON → Refresh UI
```

### 2. Assegnazione Alloggio a Nucleo

```
Frontend → seleziona nucleo e alloggio
callHandler('action', {task: 'AddNewStatoOccupazioneAlloggioSicar'})
    ↓
Task_AddNewStatoOccupazioneAlloggioSicar()
    ↓ crea relazione nucleo↔alloggio
    aggiorna aa_sicar_nuclei.alloggio_attuale
Risposta → Refresh UI nuclei/alloggi
```

---

## Template UI

### Template View (Dettaglio Oggetti)

Ogni oggetto definisce `aTemplateViewProps` per il rendering del dettaglio:

```php
$this->aTemplateViewProps['descrizione'] = array(
    "label" => "Descrizione",
    "type" => "text",
    "maxlength" => 255,
    "required" => true,
    "visible" => true
);

// Layout areas e grid
$this->aTemplateViewProps['__areas'] = array(
    array("prop1", "prop1", "prop2"),
    array("prop3", "prop4", "prop5"),
);
$this->aTemplateViewProps['__cols'] = array("1fr","1fr","1fr");
$this->aTemplateViewProps['__rows'] = array("1fr","1fr","1fr");
```

### Template Datatable

Definiti per ogni tabella di ricerca:
- `Template_DatatableSearchImmobili`
- `Template_DatatableSearchEnti`
- `Template_DatatableSearchNuclei`
- `Template_DatatableOperatoriEnte`
- `Template_DatatableInterventiImmobile`

---

## Permessi

### Flag Utente

```php
const AA_USER_FLAG_SICAR = "sicar";
```

Gli utenti con questo flag hanno accesso completo (`AA_PERMS_ALL`). Senza flag, accesso in sola lettura (`AA_PERMS_READ`).

### Verifica Permessi

Ogni classe oggetto implementa `GetUserCaps($user)`:

```php
public function GetUserCaps($user = null)
{
    $perms = AA_Const::AA_PERMS_READ;
    
    if ($user->HasFlag(AA_Sicar_Const::AA_USER_FLAG_SICAR)) {
        $perms = AA_Const::AA_PERMS_ALL;
    }
    
    return $perms;
}
```

---

## Installazione

### 1. Creazione Tabelle Database

Eseguire gli script SQL nella directory `sql/`:

```bash
source utils/modules/sicar_9/sql/*.sql
```

### 2. Popolazione Tabelle Tabellati

Inserire i dati nei dizionari:

```sql
-- Tipologie immobili
INSERT INTO aa_sicar_tipologie_immobile (descrizione) VALUES ('Ufficio');
INSERT INTO aa_sicar_tipologie_immobile (descrizione) VALUES ('Magazzino');

-- Ubicazioni
INSERT INTO aa_sicar_ubicazioni (codice, descrizione, comune, ordine) 
VALUES ('01', 'Centro', '092009', 1);

-- Zone urbanistiche
INSERT INTO aa_sicar_zone_urbanistiche (id, denominazione) VALUES (1, 'A');
```

### 3. Configurazione Permessi

Assegnare il flag `sicar` agli utenti abilitati nel sistema di autenticazione.

---

## Personalizzazione

### Aggiungere Nuove Tipologie

```sql
INSERT INTO aa_sicar_tipologie_immobile (descrizione) VALUES ('Nuova Tipologia');
```

### Aggiungere Nuovo Task

1. **Registrare il task** nel costruttore `AA_SicarModule::__construct()`:
```php
$taskManager->RegisterTask("NuovoTask");
```

2. **Implementare il metodo** nella classe:
```php
public function Task_NuovoTask($task)
{
    AA_Log::Log(__METHOD__ . "() - task: " . $task->GetName());
    
    // Logica del task
    
    $task->SetStatus(AA_GenericTask::AA_STATUS_SUCCESS);
    $task->SetContent("Risultato", true);
    return true;
}
```

### Personalizzare Template View

Modificare `aTemplateViewProps` nel costruttore della classe:

```php
$this->aTemplateViewProps['nuovo_campo'] = array(
    "label" => "Nuovo Campo",
    "type" => "text",
    "required" => true,
    "visible" => true
);
```

---

## Estensioni Future

### Possibili Sviluppi
- Gestione foto/pianimetrie degli immobili
- Integrazione con mappe geografiche (GIS)
- Gestione manutenzioni programmate
- Reportistica avanzata con grafici
- Integrazione con sistemi catastali esterni (ACI, AGENZIA TERRITORIO)
- Sistema di notifiche per scadenze
- API REST per integrazioni esterne
- Mobile app per ispezioni sul campo

---

## Versioni

- **v1.0** - Versione iniziale con gestione base degli immobili
- **v1.1** - Migrazione a `AA_GenericModule` per standardizzazione
- **v2.0** - Introduzione autoloader classi, gestione alloggi, nuclei, enti, finanziamenti
