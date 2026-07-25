<?php
defined('WB_PATH') or die();

$coreOpfFilters = [

    // ── PAGE ──────────────────────────────────────────────────────
    [
        'name'         => 'Internal Link Replacer',
        'type'         => OPF_TYPE_PAGE,
        'file'         => '{OPF:PLUGIN_PATH}/opf_wblink.php',
        'funcname'     => 'opff_mod_opf_wblink',
        'plugin'       => 'core_outputfilters',
        'pages_parent' => 'all,search',
        'desc'         => [
            'EN' => "Replaces [wblinkXX] shortcodes with the URL of the page with that ID.",
            'DE' => "Ersetzt [wblinkXX]-Kürzel durch die URL der Seite mit dieser ID.",
        ],
    ],

    // ── PAGE_LAST ─────────────────────────────────────────────────
    // Replace Contents runs before Class Insert Helper (AssetQueue injection)
    [
        'name'         => 'Replace Contents',
        'type'         => OPF_TYPE_PAGE_LAST,
        'file'         => '{OPF:PLUGIN_PATH}/opf_replace_stuff.php',
        'funcname'     => 'opff_mod_opf_replace_stuff',
        'plugin'       => 'core_outputfilters',
        'pages_parent' => 'all,backend,search',
        'desc'         => [
            'EN' => "Replaces marked content or code within placeholder blocks.",
            'DE' => "Ersetzt markierten Code innerhalb von Platzhalter-Blöcken.",
        ],
    ],
    [
        'name'         => 'Class Insert Helper',
        'type'         => OPF_TYPE_PAGE_LAST,
        'file'         => '{OPF:PLUGIN_PATH}/opf_insert.php',
        'funcname'     => 'opff_mod_opf_insert',
        'plugin'       => 'core_outputfilters',
        'pages_parent' => 'all,backend,search',
        'desc'         => [
            'EN' => "Triggers AssetQueue injection: moves queued CSS/JS/HTML into the correct head and body positions.",
            'DE' => "Löst die AssetQueue-Injection aus: schreibt eingetragene CSS-/JS-/HTML-Einträge an die korrekten Head- und Body-Positionen.",
        ],
    ],

    // ── PAGE_FINAL ────────────────────────────────────────────────
    [
        'name'         => 'Remove System PH',
        'type'         => OPF_TYPE_PAGE_FINAL,
        'file'         => '{OPF:PLUGIN_PATH}/opf_remove_system_ph.php',
        'funcname'     => 'opff_mod_opf_remove_system_ph',
        'plugin'       => 'core_outputfilters',
        'pages_parent' => 'all,backend,search',
        'desc'         => [
            'EN' => "Removes any remaining <!--(PH)...--> placeholder markers from the final page source.",
            'DE' => "Entfernt verbleibende <!--(PH)...-->-Platzhalter aus dem Quellcode der fertig generierten Seite.",
        ],
    ],
];

foreach ($coreOpfFilters as $filter) {
    $filter['active']    = 1;
    $filter['allowedit'] = in_array($filter['funcname'], ['opff_mod_opf_insert', 'opff_mod_opf_wblink']) ? 0 : 1;
    if (opf_register_filter($filter) == true) {
        $setting = preg_replace('/.*\/(.+?)\.php$/', '$1', $filter['file']);
        Settings::set($setting, 1, false);
        if (str_contains($filter['pages_parent'] ?? '', 'backend')) {
            Settings::set($setting . '_be', 1, false);
        }
    }
}
