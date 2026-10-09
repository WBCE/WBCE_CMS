<?php

/**
 * Extend Parsdown to implement Todolist Checkboxes
 */

if (!class_exists('Parsedown')) {
    require __DIR__  . '/Parsedown.php';
}
class ParsedownWbce extends Parsedown {

    /**
     * ```file-tree and ```page-tree fenced blocks render as
     * <pre class="file-tree"> / <pre class="page-tree"> with <code>…
     * </code></pre> instead of the usual <pre><code class="language-file-
     * tree">…</code></pre> — matches modules/tiptap_editor/assets/
     * filetree.js's `pre.file-tree` selector, so the same client-side
     * icon-tree renderer (vendored into this module's layout/, see
     * reader.htt) turns it into a visual tree exactly like the TipTap
     * editor already does. Keeping the class off <code> also means the
     * viewer's language-badge/copy-button/highlight.js logic (which all key
     * off code[class*="language-"]) never touches these blocks.
     */
    protected function blockFencedCode($Line)
    {
        $Block = parent::blockFencedCode($Line);

        if ($Block === null) {
            return $Block;
        }

        $codeClass = $Block['element']['element']['attributes']['class'] ?? '';

        // Accept every spelling of the two tags: file-tree / filetree /
        // fileTree / file_tree, same for page. Normalising to letters only
        // means an author never has to remember which variant "works".
        $tag = (strpos($codeClass, 'language-') === 0)
             ? strtolower(preg_replace('/[^A-Za-z]/', '', substr($codeClass, 9)))
             : '';

        if ($tag === 'filetree' || $tag === 'pagetree') {
            unset($Block['element']['element']['attributes']);
            $Block['element']['attributes'] = array(
                'class' => $tag === 'pagetree' ? 'page-tree' : 'file-tree',
            );
        }

        return $Block;
    }

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