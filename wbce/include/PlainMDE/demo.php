<?php
/**
 * include/PlainMDE/demo.php
 *
 * Standalone demo — bypasses the full WBCE admin bootstrap/login. Not
 * linked from anywhere in the admin UI.
 */

define('WB_PATH', dirname(__DIR__, 2));
require_once WB_PATH . '/config.php';

I::loadPlugin('include/PlainMDE');

I::insertJsCode(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    PlainMDEDecorations.enableChecklistContinuation();

    var editor = new PlainMDE({
        element: document.getElementById('demo_textarea'),
        toolbar: PlainMDE.defaultToolbar,
        placeholder: 'Los geht\'s …',
        lineWrapping: true,
        maxHeight: '420px',
        autofocus: true
    });

    PlainMDEDecorations.attach(editor.codemirror);
    PlainMDESync.attach(editor);

    window.__demoEditor = editor;
});
JS, 'body_late');

$sampleContent = <<<MD
# PlainMDE

Ein schlanker, selbst geschriebener Markdown-Editor für WBCE — **kein**
EasyMDE-Fork mehr, kein vendortes `marked`, nur was tatsächlich gebraucht wird.

## Features

- Fett, *kursiv*, ~~durchgestrichen~~
- Listen, auch [ ] Checklisten
- `Inline-Code` und Codeblöcke:

```js
console.log('hallo');
```

> Zitat-Block als Beispiel

| Spalte A | Spalte B |
| -------- | -------- |
| Text     | Text     |

---

Vorschau, Nebeneinander-Ansicht und Vollbild über die Toolbar oben rechts.
MD;

ob_start();
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>PlainMDE — Demo</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 30px auto; padding: 0 16px; }
        body > h1 { font-size: 1.3em; }
        .hint { color: #666; font-size: .85em; margin-bottom: 16px; }
    </style>
</head>
<body>
    <h1>PlainMDE — Demo</h1>
    <p class="hint">
        CodeMirror: <code>modules/CodeMirror_Config</code> (kein bundled CM) ·
        kein vendortes easymde.js/marked · ein CSS
    </p>
    <textarea id="demo_textarea"><?php echo htmlspecialchars($sampleContent, ENT_QUOTES); ?></textarea>
</body>
</html>
<?php
$html = ob_get_clean();
I::process($html);
echo $html;
