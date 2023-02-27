<?php

/**
 * Extend Parsdown to implement Todolist Checkboxes
 */

require __DIR__  . '/Parsedown.php';
class ParsedownWbce extends Parsedown {

    protected function blockListComplete(array $Block) {
        $list = parent::blockListComplete($Block);
        if (!isset($list)) {
            return null;
        }
        if(is_array($list['element'])){
            foreach ($list['element'] as $key => $listItem) {
                if (is_array($listItem)) {
                    foreach ($listItem as $inList => $items) {
                        if(isset($items['text']) && is_array($items['text'])){
                            $CheckFind = substr($items['text'][0], 0, 3);
                            $SourceContent = trim(substr($items['text'][0], 3));
                            if ($CheckFind === '[x]' || $CheckFind === '[ ]') {
                                $isChecked = $CheckFind === '[x]' ? ' checked' : '';
                                $list['element'][$key][$inList]['attributes']['class'] = 'task-list-item';
                                $list['element'][$key][$inList]['text'][0] = '<input type="checkbox" disabled' . $isChecked . '/> ' . $SourceContent;
                            }
                        }
                    }
                }
            }
        }
        return $list;
    }
    
}