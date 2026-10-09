<?php

defined('WB_PATH') or die('No direct access.');


$readmeLink = MdReaderLink::file(__DIR__ . '/README.md')
            ->title('README')
            ->linkHtml('README');


$oTwig     = getTwig(__DIR__ . '/twig/');
$toTwig    = [
    'readmeLink'             => $readmeLink,
    'returnToTools'          => $returnToTools,
];
$oTemplate = $oTwig->load('tool.twig');
$oTemplate->display($toTwig);