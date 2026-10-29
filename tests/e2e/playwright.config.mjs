// Suite E2E Amministrazione Aperta — Playwright
// Precondizioni sandbox (non versionate):
//   1. utils/config.php presente nella copia sandbox (gitignored) con DB locale raggiungibile
//   2. npm install (devDependency @playwright/test) + npx playwright install chromium
// Avvio:  npm run test:e2e   (da root del repo)
import { defineConfig } from '@playwright/test';

export default defineConfig({
  testDir: '.',
  testMatch: 'e2e.mjs',
  timeout: 45_000,
  expect: { timeout: 10_000 },
  retries: 0,
  workers: 1,
  fullyParallel: false,
  reporter: [['list'], ['html', { open: 'never' }]],
  outputDir: '../test-results/artifacts',
  use: {
    baseURL: process.env.E2E_BASE_URL ?? 'http://127.0.0.1:8090',
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
  },
  webServer: {
    // Server PHP built-in sulla root del repo (docroot = root, come in produzione)
    command: 'php -S 127.0.0.1:8090 -t .',
    cwd: '../..',
    url: 'http://127.0.0.1:8090/index.2.0.php',
    reuseExistingServer: true,
    timeout: 30_000,
  },
});
