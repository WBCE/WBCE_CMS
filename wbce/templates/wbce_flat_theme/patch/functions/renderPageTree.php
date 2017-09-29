<?php

/**
 * Render page tree
 * @param array $pages
 * @param int $level
 * @param int $levelLimit
 * @return string
 */
function renderPageTree($pages, $level = 1, $levelLimit = 999) {

    global $admin, $database, $TEXT, $HEADING, $MESSAGE;

    $output = '';

    $numberOfSiblingPages = count($pages);

    foreach ($pages as $page) {

        // Get user permessions
        $adminGroupIds = explode(',', str_replace('_', '', $page['admin_groups']));
        $adminUserIds = explode(',', str_replace('_', '', $page['admin_users']));

        $userHasPermission = false;
        if (is_numeric(array_search($admin->get_user_id(), $adminUserIds))) {
            $userHasPermission = true;
        }
        foreach ($admin->get_groups_id() as $groupId) {
            if (in_array($groupId, $adminGroupIds)) {
                $userHasPermission = true;
                break;
            }
        }

        $menu_link = false;
        $canModify = false;
        if ($userHasPermission && (($page['visibility'] === 'deleted' && PAGE_TRASH === 'inline') || $page['visibility'] !== 'deleted')) {
            $canModify = true;
        }

        // Check wether page has child pages
        $hasChildren = (isset($page['children']) && count($page['children']) > 0);

        // Check detailed user permissions
        $canMoveUp = ($page['position'] != 1 && $admin->get_permission('pages_settings') && $canModify);
        $canMoveDown = ($page['position'] != $numberOfSiblingPages && $admin->get_permission('pages_settings') && $canModify);
        $canDeleteAndModify = ($admin->get_permission('pages_delete') && $canModify);
        $canAddChild = ($admin->get_permission('pages_add') && $canModify && $page['visibility'] != 'deleted');
        $canModifyPage = ($admin->get_permission('pages_modify') && $canModify);
        $canModifySettings = ($admin->get_permission('pages_settings') && $canModify);
        $canManageSections = (MANAGE_SECTIONS && $admin->get_permission('pages_modify') && $canModify);

        if ($canManageSections) {
            $querySections = $database->query('SELECT `module` FROM `' . TABLE_PREFIX . 'sections` WHERE `page_id` = ' . $page['page_id'] . ' AND `module` = "menu_link"');
            if ($querySections->numRows() > 0) {
                $canManageSections = false;
                $menu_link = true;
            }
        }

        $placeholders = array(
            '{MENU_TITLE}' => $page['menu_title'],
            '{PAGE_TITLE}' => $page['page_title'],
            '{PAGE_ID}' => $page['page_id'],
            '{pageIDKEY}' => $admin->getIDKEY($page['page_id']),
            '{modifySectionsURL}' => '../pages/sections.php?page_id=' . $page['page_id'],
            '{modifyPageURL}' => '../pages/modify.php?page_id=' . $page['page_id'],
            '{frontendViewURL}' => $admin->page_link($page['link']),
            '{modifySettingsURL}' => '../pages/settings.php?page_id=' . $page['page_id'],
            '{restoreURL}' => '../pages/restore.php?page_id=' . $page['page_id'],
        );

        ob_start();

        ?>
        <li class="p<?= $page['parent'] ?> <?= ($hasChildren ? 'has-children' : '') ?>">
            <table class="table">
                <tr class="is-<?= $page['visibility'] ?>">
                    <td class="toggle">
                        <?php if ($hasChildren) { ?>
                            <a href="#" data-id="p{PAGE_ID}"><i class="fa fa-fw fa-folder-open"></i></a>
                        <?php } ?>
                    </td>
                    <td class="visibility">
                        <?php if ($page['visibility'] === 'public') { ?>
                            <i class="fa fa-eye"></i>
                        <?php } else if ($page['visibility'] === 'private') { ?>
                            <i class="fa fa-eye-slash"></i>
                        <?php } else if ($page['visibility'] === 'registered') { ?>
                            <i class="fa fa-key"></i>
                        <?php } else if ($page['visibility'] === 'hidden') { ?>
                            <i class="fa fa-lock"></i>
                        <?php } else if ($page['visibility'] === 'deleted') { ?>
                            <i class="fa fa-trash-o"></i>
                        <?php } else { ?>
                            <i class="fa fa-ban"></i>
                        <?php } ?>
                    </td>
                    <td class="title">
                        <a <?= ($canModifyPage ? 'href="{modifyPageURL}"' : 'href="#"') ?> title="<?= $HEADING['MODIFY_PAGE'] ?>">{MENU_TITLE}</a>
                        <br />
                        <small>{PAGE_TITLE}</small>
                    </td>
                    <td class="id">{PAGE_ID}</td>
                    <td class="modify">
                        <?php if ($canModifyPage) { ?>
                            <a href="{modifyPageURL}" title="<?= $HEADING['MODIFY_PAGE'] ?>"><i class="fa fa-fw fa-pencil"></i></a>
                            <?php
                        }
                        if ($page['visibility'] != 'deleted' && $canModifySettings) {

                            ?>
                            <a href="{modifySettingsURL}" title="<?= $HEADING['MODIFY_PAGE_SETTINGS'] ?>"><i class="fa fa-fw fa-cog"></i></a>
                        <?php } else if ($page['visibility'] == 'deleted') { ?>
                            <a href="{restoreURL}" title="<?= $TEXT['RESTORE'] ?>"><i class="fa fa-fw fa-recycle"></i></a>
                            <?php
                        }
                        if (isset ($menu_link) && $menu_link == true) { ?>
                        <i class="fa fa-link"></i>
                        <?php } elseif ($canManageSections) { ?>
                            <a href="{modifySectionsURL}" title="<?= $HEADING['MANAGE_SECTIONS'] ?>"><i class="fa fa-list-alt" aria-hidden="true"></i></a>
                        <?php } ?>
                    </td>
                    <td class="btndesk">
                        <?php if ($page['visibility'] != 'deleted' && $page['visibility'] != 'none') { ?>
                            <a href="{frontendViewURL}" target="_blank" title="<?= $TEXT['VIEW'] ?> (Frontend)"><i class="fa fa-desktop" aria-hidden="true"></i></a>
                        <?php } ?>
                    </td>
                    <td class="btnup">
                        <?php if ($canMoveUp) { ?>
                            <a href="../pages/move_up.php?page_id={PAGE_ID}" title="<?= $TEXT['MOVE_UP'] ?>"><i class="fa fa-chevron-circle-up" aria-hidden="true"></i></a>
                        <?php } ?>
                    </td>
                    <td class="btndown">
                        <?php if ($canMoveDown) { ?>
                            <a href="../pages/move_down.php?page_id={PAGE_ID}" title="<?= $TEXT['MOVE_DOWN'] ?>"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i></a>
                        <?php } ?>
                    </td>
                    <td class="btndel">
                        <?php if ($canDeleteAndModify) { ?>
                            <a href="javascript:confirm_link('PageID: {PAGE_ID}\n\n<?= $MESSAGE['PAGES_DELETE_CONFIRM'] ?>?','../pages/delete.php?page_id={pageIDKEY}');" title="<?= $TEXT['DELETE'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                        <?php } ?>
                    </td>
                    <td class="btnaddc">
                        <?php if ($canAddChild && $level < $levelLimit) { ?>
                            <a href="javascript:addChildPage('{PAGE_ID}');" title="<?= $HEADING['ADD_CHILD_PAGE'] ?>"><i class="fa fa-files-o" aria-hidden="true"></i></a>
                        <?php } ?>
                    </td>
                </tr>
            </table>

            <?php if ($hasChildren) { ?>
                <ul id="p<?= $page['page_id'] ?>" class="list-unstyled">
                    <?= renderPageTree($page['children'], $level + 1, $levelLimit) ?>
                </ul>
            <?php } ?>
        </li>
        <?php
        $renderedListItem = ob_get_clean();

        $output .= str_replace(array_keys($placeholders), array_values($placeholders), $renderedListItem);
    }

    return $output;
}
