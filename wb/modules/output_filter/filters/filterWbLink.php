<?php
/*
 * replace all "[wblink{page_id}]" with real links
 * @param string $content : content with tags
 * @return string content with links
 */
    function doFilterWbLink($content)
    {
        global $database;

        $pattern = '/\[wblink([0-9]+)\]/isU';
        if (preg_match_all($pattern, $content, $aMatches, PREG_SET_ORDER))
        {
            $aSearchReplaceList = array();
            foreach ($aMatches as $aMatch) {
                 // collect matches formatted like '[wblink123]' => 123
                $aSearchReplaceList[strtolower($aMatch[0])] = $aMatch[1];
            }
            // build list of PageIds for SQL query
            $sPageIdList = implode(',', $aSearchReplaceList); // '123,124,125'
            // replace all PageIds with '#' (stay on page death link)
            array_walk($aSearchReplaceList, function(&$value, $index){ $value = '#'; });
            $sql = 'SELECT `page_id`, `link` FROM `'.TABLE_PREFIX.'pages` '
                 . 'WHERE `page_id` IN('.$sPageIdList.')';
            if (($oPages = $database->query($sql))) {
                while (($aPage = $oPages->fetchRow(MYSQLI_ASSOC))) {
                    $aPage['link'] = ($aPage['link']
                                     ? PAGES_DIRECTORY.$aPage['link'].PAGE_EXTENSION
                                     : '#');
                    // collect all search-replace pairs with valid links
                    if (is_readable(WB_PATH.$aPage['link'])) {
                        // replace death link with found and valide link
                        $aSearchReplaceList['[wblink'.$aPage['page_id'].']'] =
                            WB_URL.$aPage['link'];
                    }
                }
            }
            // replace all found [wblink**] tags with their urls
            $content = str_ireplace(
                array_keys($aSearchReplaceList),
                $aSearchReplaceList,
                $content
            );
        }
        return $content;
    }
