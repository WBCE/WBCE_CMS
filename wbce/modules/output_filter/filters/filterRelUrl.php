<?php
/**
 * Convert full qualified, local URLs into relative URLs
 * @param string $content
 * @return string
 */
    function doFilterRelUrl($content) {
        $sAppUrl  = rtrim(str_replace('\\', '/', WB_URL), '/').'/';
        $sAppPath = rtrim(str_replace('\\', '/', WB_PATH), '/').'/';
        $content = preg_replace_callback(
            '/((?:href|src)\s*=\s*")([^\?\"]*?)/isU',
            function ($aMatches) use ($sAppUrl, $sAppPath) {
                $sAppRel = preg_replace('/^https?:\/\/[^\/]*(.*)$/is', '$1', $sAppUrl);
                $aMatches[2] = str_replace('\\', '/', $aMatches[2]);
                $aMatches[2] = preg_replace('/^'.preg_quote($sAppUrl, '/').'/is', '', $aMatches[2]);
                $aMatches[2] = preg_replace('/(\.+\/)|(\/+)/', '/', $aMatches[2]);
                if (!is_readable($sAppPath.$aMatches[2])) {
                // in case of death link show original link
                    return $aMatches[0];
                } else {
                    return  preg_replace('/(?<!:)\/+/','/',$aMatches[1].$sAppRel.$aMatches[2]);
                }
            },
            $content
        );
        return $content;
    }
