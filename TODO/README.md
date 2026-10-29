# TODO — nomenclatura e workflow obbligatorio

## Nomenclatura

- Un file per feature/fix: `F<NN>-<slug>.md` (es. `F01-login-sso-fix.md`).
- `NN` = contatore feature a due cifre, mai riusato; il file **non-✅ con NN più alto**
  è il lavoro attivo.
- `_TEMPLATE.md` = modello da cui copiare (mai modificarlo direttamente per una feature).
- `STATUS.md` = file di stato/azioni successive in attesa di feedback: **leggere sempre
  per primo** a inizio sessione/ripresa; contenuto sovrascritto integralmente a ogni
  aggiornamento (non incrementale) con timestamp.

## Workflow obbligatorio per ogni feature/fix non banale

1. **Prima del codice**:
   - aggiornare `docs/SPECIFICATION.md` (cosa: FR, API, modello dati);
   - aggiornare `docs/PROJECT_PLAN.md` (come: classi/file da toccare, milestone);
   - vincolo di granularità: ogni voce di checklist completabile in una singola sessione;
     attività più grandi → sotto-milestone nel piano + step granulari nella checklist;
   - creare `TODO/F<NN>-<slug>.md` da `_TEMPLATE.md`; aprire branch omonimo (`F<NN>-<slug>`).
2. **Durante**: spuntare la checklist a ogni step completato; tenere aggiornata la
   sezione "Dove sono ora" (ultimo step fatto / prossimo da fare / decisioni prese);
   commit per step verificato: `<tipo>(<scope>): descrizione`.
3. **Fine feature**: test verdi (E2E Playwright `tests/e2e/e2e.mjs` se superficie UI) +
   `LOG.md` aggiornato + stato TODO → ✅ + merge branch su `main`.
4. **Checkpoint in attesa di feedback dell'utente** (es. conferma deploy/sync/merge/pull):
   annotare stato e azioni successive in `STATUS.md` con timestamp.

## Stato

| File | Feature | Stato |
|---|---|---|
| — | nessun TODO aperto (M0 baseline chiusa) | — |
