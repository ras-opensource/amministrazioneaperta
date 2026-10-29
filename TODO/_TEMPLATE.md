# F<NN>-<slug> — <titolo feature>

> Copiare da questo template come `F<NN>-<slug>.md`. Stato: ⬜ aperto / 🔄 in corso / ✅ chiuso.
**Stato**: ⬜

## Obiettivo

_(cosa deve essere vero al termine, in 1-3 frasi)_

## Scope

- **In scope**:
  - …
- **Out of scope** (esplicito):
  - …

## API / superfici toccate

- Endpoints/task: …
- Classi/file: …
- Breaking change? sì/no → se sì, aggiornare `docs/SPECIFICATION.md` e `docs/API.md`
  (`python3 scripts/gen_api.py` + `sha256sum -c docs/api.sha256`)

## Checklist

### Fase 1 — <nome>
- [ ] …
- [ ] …

### Fase 2 — <nome>
- [ ] …
- [ ] Test E2E: scenario aggiunto a `tests/e2e/e2e.mjs` e verde

## Dove sono ora

- **Ultimo step fatto**: …
- **Prossimo da fare**: …
- **Decisioni prese** (con data): …

## Verifica finale (definition of done)

- [ ] Checklist completa
- [ ] Test verdi (unit/E2E secondo superficie)
- [ ] `docs/SPECIFICATION.md` e `docs/PROJECT_PLAN.md` coerenti col codice
- [ ] `LOG.md` aggiornato; stato file → ✅
- [ ] Merge branch `F<NN>-<slug>` su `main`
