// Amministrazione Aperta — suite E2E smoke (Playwright)
// Copia sandbox: server PHP built-in su 127.0.0.1:8090 (config playwright webServer)
import { test, expect } from '@playwright/test';

const FATAL_RE = /Fatal error|Parse error|Uncaught Error/;

test.describe('Amministrazione Aperta — boot', () => {
  test('index.2.0.php: HTTP 200, titolo e shell app senza fatal PHP', async ({ page }) => {
    const resp = await page.goto('/index.2.0.php');
    expect(resp).not.toBeNull();
    expect(resp.status()).toBe(200);
    await expect(page).toHaveTitle(/Amministrazione Aperta/);
    // Shell di boot presente nel DOM
    await expect(page.locator('#AA_MainOverlay')).toBeAttached();
    const body = await page.locator('body').innerText();
    expect(body).not.toMatch(FATAL_RE);
  });

  test('index.2.0.php: boot JS completo fino al controllo sessione (accesso anonimo)', async ({ page }) => {
    const jsErrors = [];
    let authGate = false;
    page.on('pageerror', (e) => jsErrors.push(String(e)));
    page.on('console', (m) => {
      if (/Credenziali non impostate o sessione scaduta/.test(m.text())) authGate = true;
    });
    await page.goto('/index.2.0.php');
    // Contratto boot anonimo: l'inizializzazione JS deve arrivare al controllo
    // sessione (AA_RefreshMainUi). Senza sessione valida la UI principale non
    // parte e l'overlay di boot resta visibile: è il comportamento atteso.
    await expect.poll(() => authGate, { timeout: 30_000 }).toBeTruthy();
    expect(jsErrors, `error JS non attesi: ${jsErrors.join(' | ')}`).toHaveLength(0);
  });
});

test.describe('Amministrazione Aperta — asset statici', () => {
  test('CSS e JS core serviti con HTTP 200', async ({ request }) => {
    const assets = [
      ['/utils/webix/codebase/webix.min.css', 'text/css'],
      ['/stili/mdi/css/materialdesignicons.min.css', 'text/css'],
      ['/utils/webix/codebase/webix.min.js', 'javascript'],
    ];
    for (const [path, mime] of assets) {
      const resp = await request.get(path);
      expect(resp.status(), `GET ${path}`).toBe(200);
      expect((resp.headers()['content-type'] ?? '').includes(mime), `content-type GET ${path}`).toBeTruthy();
    }
  });
});

test.describe('Amministrazione Aperta — endpoint pubblici', () => {
  test('storage.php: HTTP 200 senza parametri', async ({ request }) => {
    const resp = await request.get('/storage.php');
    expect(resp.status()).toBe(200);
  });
});
