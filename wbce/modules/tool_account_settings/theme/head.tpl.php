<?php 
// Prevent this file from being accessed directly
defined('WB_PATH') or exit('Cannot access this file directly'); 

// was a Message set somewhere that should be displayed to the user?
if(isset($_GET['msg'])){
    $type = isset($_GET['msgtype']) ? (string) $_GET['msgtype'] : 'infobox';	
    $aMsg[$type] = $_GET['msg'];
}
if(!empty($aMsg)){
    $key = key($aMsg);
    foreach($aMsg as $msg){ ?>
        <div class="be_infobox be_<?=$key ?>"><?=L_($msg) ?></div>
    <?php 
    } //endforeach;
}
?>
<noscript><?=$TOOL_TEXT['NOSCRIPT_MESSAGE']; ?></noscript>
<div class="pane">
    <div class="pageinfo">
        <div class="pagetitle"></div>
    </div>
    <?php 
    // Menu
    $aPositions = array(
        'tool_overview' => $TOOL_TXT['OVERVIEW'],
        'config'        => $TOOL_TXT['CONFIG'],
    );
    ?>
    <nav class="tabs">
        <ul>
            <li class="page-title">
                <span>Admin-Tool: <strong><?=$module_name ?></strong></span>
            </li>
        <?php
        $aPositions = array_reverse($aPositions);
        foreach($aPositions as $key=>$label){
            if($key == 'details') continue;
            $sLiClass = ($sPos == $key) ? ' class="actionSel"' : '';
            $sAnchorClass = ($sPos == $key) ? ' sel' : '';
            $sAnchorClass .= isset($_GET['area']) && $_GET['area'] == 'config' && $key == 'config' ? ' sel' : '';
            $sAnchorClass .= isset($_GET['pos']) && $_GET['pos'] == 'detail' && $key == 'tool_overview' ? ' sel' : '';
            ?>
            <li <?=$sLiClass?>>
                <a class="tabHelp tab<?=$sAnchorClass?>" href="<?=$toolUrl?>&pos=<?=$key?>"><?=$label?></a>
            </li>
            <?php
        }
        ?>
        </ul>					
    </nav>
<div class="editor-container">