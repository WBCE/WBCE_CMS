/**
 * include/PlainMDE/src/plainmde-toolbar.js
 *
 * Button metadata (icon key + title) for every action PlainMDE ships. No
 * indirection through a third-party button table (the old wbce-mde.js had
 * to reverse-engineer EasyMDE's `toolbarBuiltInButtons` global) — this is
 * the single, direct source of truth PlainMDE-core reads from.
 *
 * `icon` refers to a key in window.PLAINMDE_ICONS (see plainmde-icons.php),
 * which matches the array keys in icons.php.
 */
PlainMDE.buttons = {
    'bold':             { icon: 'bold',                 title: 'Fett' },
    'italic':           { icon: 'italic',               title: 'Kursiv' },
    'strikethrough':     { icon: 'strikethrough',        title: 'Durchgestrichen' },
    'heading':          { icon: 'heading',               title: 'Überschrift' },
    'quote':            { icon: 'quote',                 title: 'Zitat' },
    'unordered-list':   { icon: 'list',                  title: 'Liste' },
    'ordered-list':     { icon: 'list-numbers',          title: 'Nummerierte Liste' },
    'check-list':       { icon: 'list-details',          title: 'Checkliste' },
    'code':             { icon: 'code',                  title: 'Code' },
    'link':             { icon: 'link',                  title: 'Link' },
    'image':            { icon: 'photo',                 title: 'Bild' },
    'table':            { icon: 'table',                 title: 'Tabelle' },
    'horizontal-rule':  { icon: 'separator-horizontal',  title: 'Trennlinie' },
    'preview':          { icon: 'eye',                   title: 'Vorschau' },
    'side-by-side':     { icon: 'columns',               title: 'Nebeneinander' },
    'fullscreen':       { icon: 'maximize',               title: 'Vollbild' },
    'undo':             { icon: 'arrow-back-up',          title: 'Rückgängig' },
    'redo':             { icon: 'arrow-forward-up',       title: 'Wiederholen' },
    'guide':            { icon: 'help',                   title: 'Markdown-Hilfe' }
};

PlainMDE.defaultToolbar = [
    'bold', 'italic', 'strikethrough', 'heading', '|',
    'quote', 'unordered-list', 'ordered-list', 'check-list', 'code', '|',
    'link', 'image', 'table', 'horizontal-rule', '|',
    'undo', 'redo', '|',
    'guide',
    'spacer',
    // View-switching group, deliberately separated from the editing
    // commands above and pinned to the far right via the flexible spacer.
    'preview', 'side-by-side', 'fullscreen'
];
