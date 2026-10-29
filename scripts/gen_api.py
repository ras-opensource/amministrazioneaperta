import hashlib, os, re, sys

ROOT = "utils"
CORE_DIRS = ["system_core", "system_lib"]
MODULES_DIR = os.path.join(ROOT, "modules")
OUT = "docs/API.md"
SUM = "docs/api.sha256"

class_re = re.compile(r'^\s*(?:abstract\s+)?class\s+(\w+)(?:\s+extends\s+([\w:]+))?', re.M)
func_re  = re.compile(r'^\s*(?:public|private|protected|static|\s)*\s*function\s+(\w+)\s*\(([^)]*)\)', re.M)

def scan(path):
    with open(path, encoding="utf-8", errors="replace") as f:
        lines = f.readlines()
    classes = [(m.group(1), m.group(2) or "", i+1) for i,l in enumerate(lines) if (m:=class_re.search(l))]
    methods = []
    for i,l in enumerate(lines):
        s = l.strip()
        if s.startswith("function"):
            m = re.match(r'function\s+(\w+)\s*\(([^)]*)\)', s)
        elif re.match(r'(public|private|protected|static)\b', s):
            m = func_re.match(s)
        else:
            m = None
        if m:
            params = re.sub(r'\s+',' ', m.group(2)).strip()
            methods.append((m.group(1), params, i+1))
    return classes, methods

def collect_files(base, recursive=False):
    """[(relpath_da_base, abspath)] dei file .php da scansionare."""
    out = []
    if recursive:
        for dirpath, dirnames, filenames in os.walk(base):
            dirnames.sort()
            for f in sorted(filenames):
                if f.endswith(".php"):
                    pth = os.path.join(dirpath, f)
                    out.append((os.path.relpath(pth, base).replace(os.sep, "/"), pth))
    else:
        for f in sorted(os.listdir(base)):
            pth = os.path.join(base, f)
            if f.endswith(".php") and os.path.isfile(pth):
                out.append((f, pth))
    return out

sections = []
sums = []
for d in CORE_DIRS:
    base = os.path.join(ROOT, d)
    sections.append((d, [(rel, apath, scan(apath)) for rel, apath in collect_files(base)]))
for mod in sorted(os.listdir(MODULES_DIR)):
    base = os.path.join(MODULES_DIR, mod)
    if not os.path.isdir(base):
        continue
    files = [(rel, apath, scan(apath)) for rel, apath in collect_files(base, recursive=True)]
    if files:
        sections.append((f"modules/{mod}", files))

with open(OUT,"w",encoding="utf-8") as out:
    out.write("# API Reference - Amministrazione Aperta\n\n")
    out.write("> Generato automaticamente dal codice (script `scripts/gen_api.py`). Non modificare a mano:\n")
    out.write("> Rigenerare dopo ogni cambio di firma in `utils/system_core/`, `utils/system_lib/` e `utils/modules/**`.\n")
    out.write("> Verifica coerenza doc/codice: `sha256sum -c docs/api.sha256`\n\n")
    out.write("## Superficie HTTP (entry point)\n\n")
    out.write("| Endpoint | File | Descrizione |\n|---|---|---|\n")
    out.write("| `GET /index.2.0.php` | `index.2.0.php` | Entry point app: auth, overlay, Webix UI, loop moduli |\n")
    out.write("| `GET/POST /utils/system_ops.php` | `utils/system_ops.php` | Dispatcher task di sistema (AA_SystemTaskManager) |\n")
    out.write("| `GET/POST /utils/modules/<id>/taskmanager.php` | per-modulo | Entry point AJAX dei task del modulo (`?task=<NomeTask>`) |\n")
    out.write("| `GET /storage.php?object=HASH` | `storage.php` | Accesso file pubblico autenticato (hash) |\n")
    out.write("| `POST /ws.php` | `ws.php` | Service XML pubblicazioni (D.Lgs. 33/2013, 39/2013), parametri `<param art=..>` |\n\n")
    out.write("## Protocollo task (frontend → backend)\n\n")
    out.write("- Richiesta: `taskmanager.php?task=<NomeTask>&<params>...` (+ viewport `vw`,`vh`,`mobile`)\n")
    out.write("- Registrazione: `$taskManager->RegisterTask(\"<Nome>\")` → risolve `Task_<Nome>()` nella classe modulo\n")
    out.write("- Risposta XML:\n")
    out.write("  ```xml\n  <root>\n    <status id='status'>0</status>      <!-- 0=ok, -1=errore, -2=auth -->\n    <error id='error'></error>\n    <content id='content' encode='base64' type='json'>...</content>\n  </root>\n  ```\n")
    out.write("- Client: `AA_Task()` / `AA_VerboseTask()` / `AA_Module.doTask()` / `AA_MainApp.utils.callHandler()` in `utils/system_lib.js`\n\n")
    for d, files in sections:
        out.write(f"## `{d}/`\n\n")
        for rel, apath, (classes, methods) in files:
            full = f"{ROOT}/{d}/{rel}"
            out.write(f"### `{full}`\n\n")
            if classes:
                out.write("| Classe | Estende | Linea |\n|---|---|---|\n")
                for name,parent,ln in classes:
                    out.write(f"| `{name}` | {parent} | {ln} |\n")
                out.write("\n")
            if methods:
                out.write("| Metodo | Parametri | Linea |\n|---|---|---|\n")
                for name,params,ln in methods:
                    p = params if params else "—"
                    out.write(f"| `{name}()` | `{p}` | {ln} |\n")
                out.write("\n")
            sums.append((full, hashlib.sha256(open(apath,'rb').read()).hexdigest()))

with open(SUM,"w",encoding="utf-8") as s:
    for rel,h in sums:
        s.write(f"{h}  {rel}\n")

print("files:", len(sums), "| API.md bytes:", os.path.getsize(OUT))
