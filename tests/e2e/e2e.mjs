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

test.describe('Amministrazione Aperta — autenticazione email/OTP', () => {
  // Utente di test presente in aa_users con status>0 (verifica: scripts/check_auth_user.php).
  // Con AA_ENABLE_SENDMAIL=false, AA_User::MailOTPAuthChallenge logga il codice a livello 100
  // → leggibile via /session_log.php nello stesso browser context (stesso cookie PHP session).
  const TEST_EMAIL = 'esaiucarta@regione.sardegna.it';

  test('login email + OTP: challenge, verifica da session_log.php, boot autenticato', async ({ page }) => {
    test.setTimeout(90_000);

    // Listener sul lifecycle post-reload (il listener Page sopravvive alla navigazione)
    const jsErrorsAfterReload = [];
    let gateAfterReload = false;
    let reloaded = false;
    page.on('pageerror', (e) => { if (reloaded) jsErrorsAfterReload.push(String(e)); });
    page.on('console', (m) => {
      if (reloaded && /Credenziali non impostate o sessione scaduta/.test(m.text())) gateAfterReload = true;
    });

    await page.goto('/index.2.0.php');

    // Attende il completamento del boot fino all'assegnazione logIn (modalità email è il
    // default: AA_DefaultSystemInitialization → AA_MainApp.logIn = AA_MailUserAuth);
    // senza questo check si rischierebbe di chiamare il default AA_UserAuth (credenziali).
    await page.waitForFunction(
      () => typeof AA_MainApp !== 'undefined' && AA_MainApp.logIn === AA_MailUserAuth,
      null,
      { timeout: 30_000 }
    );

    // Boot anonimo → dialogo di login email
    await page.evaluate(() => AA_MainApp.logIn());
    // Nota: i window webix non espongono l'id nel DOM (solo nel registry interno $$);
    // il nodo finestra ha classe AA_Wnd → targeting per classe.
    await expect(page.locator('div.webix_window.AA_Wnd')).toBeVisible({ timeout: 30_000 });

    // Interstiziale cookie/privacy (widget privacypolicies.com): se presente copre l'intera UI e
    // intercetta i click → va accettato prima di interagire con il dialogo.
    const inter = page.locator('[id^="privacypolicies"]').first();
    if ((await inter.count()) > 0) {
      await inter.getByRole('button', { name: 'Accetto' }).click();
    }

    // Compilazione form via API webix (stabile rispetto al rendering DOM); si verifica il valore letto
    const emailSet = await page.evaluate((email) => {
      const form = $$('AA_MailUserAuth_Form');
      form.setValues({ email });
      return form.getValues().email;
    }, TEST_EMAIL);
    expect(emailSet).toBe(TEST_EMAIL);

    // Bottone Apply "Accedi": primo ruolo button nella finestra (poi Ricordami e "Accedi con credenziali")
    await page.locator('div.webix_window.AA_Wnd').getByRole('button').first().click();

    // Successo task MailUserAuth → il dialogo di verifica si apre automaticamente
    await expect(page.locator('div.webix_window.AA_Wnd')).toBeVisible({ timeout: 30_000 });

    // OTP dal session log: XHR same-origin dalla pagina → stesso cookie PHP session.
    // Nota: l'app shadowa il globale `fetch` (layer task/ajax interno) → si usa XMLHttpRequest.
    const logHtml = await page.evaluate(
      () =>
        new Promise((resolve, reject) => {
          const x = new XMLHttpRequest();
          x.open('GET', '/session_log.php');
          x.onload = () => resolve(x.responseText);
          x.onerror = () => reject(new Error('XHR /session_log.php fallito'));
          x.send();
        })
    );
    const m = logHtml.match(/MailOTPAuthChallenge - OTP: ([a-f0-9]{6})/);
    expect(m, 'codice OTP non trovato nel session log').not.toBeNull();

    const codeSet = await page.evaluate((code) => {
      const form = $$('AA_MailUserAuthVerify_Form');
      form.setValues({ codice: code });
      return form.getValues().codice;
    }, m[1]);
    expect(codeSet).toBe(m[1]);

    // Verifica → task MailUserAuthChallengeVerify → close + window.location.reload()
    const reload = page.waitForEvent('load', { timeout: 60_000 });
    // Bottone Apply del dialogo di verifica (unico/primario nella finestra)
    await page.locator('div.webix_window.AA_Wnd').getByRole('button').first().click();
    await reload;
    reloaded = true;

    // Boot autenticato: overlay di boot nascosto (overlay.hide() nel path di successo);
    // su boot anonimo l'overlay resta visibile e il poll scade.
    // Nota: evaluate deve restituire un valore serializzabile (boolean), non il nodo DOM
    // (Playwright lo wrap-pa in ElementHandle e .style sarebbe undefined).
    // Stesso criterio di visibilità usato dall'app (AA_MainApp.ui.overlay.isVisible):
    // overlay nascosto = display diverso da "block".
    await expect.poll(async () =>
      page.evaluate(
        () =>
          (() => {
            const el = document.getElementById('AA_MainOverlay');
            return !!el && el.style.display !== 'block';
          })()
      ),
      { timeout: 60_000 }
    ).toBeTruthy();

    // Nessun gate anonimo e nessun errore JS nel lifecycle post-reload
    expect(gateAfterReload, 'gate di autenticazione presente dopo il reload').toBeFalsy();
    expect(jsErrorsAfterReload, `error JS non attesi: ${jsErrorsAfterReload.join(' | ')}`).toHaveLength(0);
  });
});
