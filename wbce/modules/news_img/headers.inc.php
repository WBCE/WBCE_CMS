<?php

require_once __DIR__.'/functions.inc.php';

$settings = mod_nwi_settings_get($section);

$mod_headers = array(
    'frontend' => array(
        'css' => array(
            array('file'=>CAT_URL.'/modules/news_img/views/'.$settings['view'].'/frontend.css',),
        ),
    ),
);