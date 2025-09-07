# Modulo SICAR - Sistema Informativo Catasto e Amministrazione Risorse

## Descrizione

Il modulo SICAR è un sistema per la gestione informatizzata del catasto e delle risorse immobiliari dell'Amministrazione. Il modulo permette di gestire in modo completo gli immobili, dalle informazioni base ai dati catastali e urbanistici.

## Struttura del Modulo

### File Principali

- `lib.php` - Libreria principale con le classi `AA_Immobile` e `AA_SicarModule`
- `sicar_ops.php` - Task manager per le operazioni del modulo
- `sicar.css` - Stili CSS personalizzati per il modulo
- `sql/aa_sicar_immobili.sql` - Struttura database per gli immobili
- `config.php` - Configurazione del modulo

### Classi

#### AA_Immobile
Classe derivata da `AA_Object_V2` per la gestione degli immobili.

**Proprietà:**
- `tipologia` - Tipologia di immobile (tabellato)
- `comune` - Codice ISTAT del Comune
- `ubicazione` - Ubicazione nel territorio comunale (tabellato)
- `indirizzo` - Indirizzo completo con numero civico
- `catasto` - Dati catastali (Foglio, mappale, particella, subalterno)
- `zona_urbanistica` - Codice zona urbanistica
- `piani` - Numero di piani dell'immobile
- `note` - Note aggiuntive

**Metodi principali:**
- `Validate()` - Validazione dei dati
- `GetDisplayName()` - Nome visualizzato dell'immobile
- `ToCsv()` - Esportazione in formato CSV

#### AA_SicarModule
Classe principale del modulo che estende `AA_GenericModule`.

**Funzionalità:**
- Gestione delle sezioni del modulo
- Operazioni CRUD sugli immobili
- Ricerca e filtri
- Esportazione dati
- Task management integrato

**Task implementati:**
- `GetSicarTipologie` - Recupero tipologie immobili
- `GetSicarUbicazioni` - Recupero ubicazioni
- `GetSicarZoneUrbanistiche` - Recupero zone urbanistiche
- `GetSicarComuni` - Recupero comuni
- `AddNewSicar` - Aggiunta nuovo immobile
- `UpdateSicar` - Aggiornamento immobile
- `DeleteSicar` - Eliminazione immobile
- `PublishSicar` - Pubblicazione immobile
- `TrashSicar` - Cestinazione immobile
- `ResumeSicar` - Ripristino immobile
- `ReassignSicar` - Riassegnazione immobile
- `ExportSicarCsv` - Esportazione CSV

## Architettura

Il modulo utilizza l'architettura standard di Amministrazione Aperta:

### Pattern AA_GenericModule
- **Ereditarietà**: Estende `AA_GenericModule` per funzionalità standard
- **Task Management**: Utilizza `AA_GenericModuleTaskManager` per la gestione dei task
- **Sezioni**: Implementa sezioni standard (Bozze, Pubblicate, Dettaglio)
- **Permessi**: Integrazione con il sistema di permessi di `AA_Object_V2`

### Task System
I task sono implementati come metodi nella classe `AA_SicarModule` seguendo la convenzione:
- Nome metodo: `Task_[NomeTask]`
- Parametro: Oggetto `AA_GenericTask`
- Ritorno: `true` per successo, `false` per errore
- Gestione stato: `SetStatus()`, `SetContent()`, `SetError()`

## Struttura Database

### Tabelle Principali

#### aa_sicar_immobili
Tabella principale per i dati degli immobili.

#### aa_sicar_tipologie
Tabella per le tipologie di immobile (Ufficio, Magazzino, Officina, etc.).

#### aa_sicar_ubicazioni
Tabella per le ubicazioni nel territorio comunale.

#### aa_sicar_zone_urbanistiche
Tabella per le zone urbanistiche comunali.

## Funzionalità

### Gestione Immobili
- **Inserimento**: Creazione di nuovi immobili con tutti i dati richiesti
- **Modifica**: Aggiornamento dei dati esistenti
- **Eliminazione**: Rimozione logica degli immobili
- **Pubblicazione**: Gestione dello stato di pubblicazione
- **Cestinazione**: Gestione dello stato di cestinazione
- **Ripristino**: Ripristino di immobili cestinati
- **Riassegnazione**: Cambio di struttura organizzativa

### Ricerca e Filtri
- Ricerca testuale su descrizione e indirizzo
- Filtri per tipologia, comune, ubicazione
- Paginazione dei risultati
- Filtri per stato (bozza, pubblicato, cestinato)

### Esportazione
- Esportazione in formato CSV
- Inclusione di tutti i campi principali
- Filtri per stato di pubblicazione

### Validazione
- Controllo campi obbligatori
- Validazione formato dati catastali
- Verifica numeri di piani
- Validazione codici ISTAT comuni

## Interfaccia Utente

### Sezioni Standard
1. **Bozze**: Vista tabellare degli immobili in stato bozza
2. **Pubblicate**: Vista tabellare degli immobili pubblicati
3. **Dettaglio**: Form per la modifica dei dati

### Componenti UI
- DataTable per la lista degli immobili
- Form con validazione per i dettagli
- Toolbar con azioni principali
- Campo di ricerca con filtro automatico
- Paginazione
- Menu contestuali per azioni

## Installazione

### 1. Creazione Tabelle Database
Eseguire lo script SQL:
```sql
source utils/modules/sicar_9/sql/aa_sicar_immobili.sql
```

### 2. Configurazione Modulo
Il modulo si integra automaticamente nel sistema esistente utilizzando il pattern standard dei moduli AA.

### 3. Permessi
Il modulo utilizza il sistema di permessi standard di AA_Object_V2:
- **Lettura**: Visualizzazione degli immobili
- **Scrittura**: Modifica dei dati
- **Eliminazione**: Rimozione degli immobili
- **Pubblicazione**: Gestione dello stato

## Utilizzo

### Accesso al Modulo
Il modulo è accessibile tramite il menu principale dell'applicazione con l'icona "home-city".

### Operazioni Base
1. **Visualizzare gli immobili**: Selezionare la sezione "Bozze" o "Pubblicate"
2. **Aggiungere un immobile**: Cliccare su "Nuovo" nella toolbar
3. **Modificare un immobile**: Selezionare l'immobile e cliccare "Modifica"
4. **Eliminare un immobile**: Selezionare l'immobile e cliccare "Elimina"
5. **Pubblicare un immobile**: Selezionare l'immobile e cliccare "Pubblica"
6. **Cestinare un immobile**: Selezionare l'immobile e cliccare "Cestina"

### Ricerca
Utilizzare il campo di ricerca per filtrare gli immobili per descrizione o indirizzo.

### Esportazione
Cliccare su "Esporta CSV" per scaricare tutti gli immobili pubblicati in formato CSV.

## Personalizzazione

### Aggiungere Nuove Tipologie
Inserire nuovi record nella tabella `aa_sicar_tipologie`:
```sql
INSERT INTO aa_sicar_tipologie (codice, descrizione, ordine) 
VALUES ('011', 'Nuova Tipologia', 11);
```

### Aggiungere Nuove Ubicazioni
Inserire nuovi record nella tabella `aa_sicar_ubicazioni`:
```sql
INSERT INTO aa_sicar_ubicazioni (codice, descrizione, comune, ordine) 
VALUES ('011', 'Nuova Ubicazione', '092009', 11);
```

### Modificare gli Stili
Personalizzare il file `sicar.css` per modificare l'aspetto del modulo.

### Aggiungere Nuovi Task
Per aggiungere nuovi task:

1. **Registrare il task** nel costruttore:
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

## Estensioni Future

### Possibili Sviluppi
- Gestione delle foto degli immobili
- Integrazione con mappe geografiche
- Gestione delle manutenzioni
- Reportistica avanzata
- Integrazione con sistemi catastali esterni
- Gestione delle planimetrie
- Sistema di notifiche per scadenze

### API
Il modulo è predisposto per l'integrazione con API esterne per:
- Verifica dati catastali
- Geocoding degli indirizzi
- Integrazione con sistemi urbanistici
- Sincronizzazione con sistemi esterni

## Supporto

Per problemi o richieste di modifica, contattare il team di sviluppo del sistema Amministrazione Aperta.

## Versioni

- **v1.0** - Versione iniziale con gestione base degli immobili
- **v1.1** - Migrazione a AA_GenericModule per standardizzazione
