<?php

/**
     * make use of Aldus' x_c_date class for date translations and user defined formatting
   
    $oTwig->addFunction(new Twig_SimpleFunction("date_formatter", 
        function ($sDate, $sFormat = '%d. %B %Y') {
            # H:\WbPortable\root\wb284Test\modules\outputfilter_dashboard\plugins\x_c_date_opf\index.php
            # http://forum.websitebaker.org/index.php?topic=10786.0
            class_exists('c_date') or require __DIR__ . '/x_cdate/x_c_date.class.php';
            
            $oDate = new c_date();
            $oDate->set_wb_lang(LANGUAGE);
            $sStr = strftime("%d.%m.%Y", strtotime($sDate));
            $oDate->format = $sFormat;
            $sRetVal = $oDate->transform($sStr);
            // correct UTF-8 Problems
            if (LANGUAGE == 'PL') {
                $aReplacements = array('en' => 'eń', 'paz' => 'paź');
                $sRetVal = strtr($sRetVal, $aReplacements);
            }
            return $sRetVal;
        }
    ));
    */
    /**
     * insertJsFile
     */