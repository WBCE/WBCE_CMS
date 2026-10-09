<?php
/**
 *
 * @category        admintool / preinit / initialize
 * @package         errorlogger
 * @author          Ruud Eisinga · www.dev4me.com (https://dev4me.com/)
 * @author          Christian M. Stefan  (https://www.wbEasy.de)
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE 1.7.x
 *
 */

// Must include code to stop this file being access directly
if (defined('WB_PATH') == false) {
    die("Cannot access this file directly");
}

$module_directory   = 'errorlogger';
$module_name        = 'Errorlog viewer';
$module_version     = '2.0.0';
$module_function    = 'tool, preinit, initialize';
$module_platform    = '1.7.0';
$module_author      = 'Ruud Eisinga - Dev4me, Christian M. Stefan';
$module_license	    = 'GNU General Public License';
$module_description = 'PHP warnings and errors are written to a private logfile and shown in a comfortable backend view — plus a CodeVet tab with the audit log of every Droplet, Outputfilter, Code2 save or add-on upload that CodeVet blocked or flagged for dangerous code.';
$module_icon        = 'fa fa-bug';
$module_level       = 'core';


/**
 * DEVELOPMENT HISTORY (Change Log):
 *
 * v.2.0.0 2026-09-08 Christian M. Stefan
 *         [+] CodeVet tab: surfaces framework/CodeVet.php's audit log
 *             (var/code_vet/codevet.log) — every blocked/flagged Droplet,
 *             Outputfilter, Code2 save and add-on ZIP upload, with per-profile
 *             badges, findings, "×N" collapse, archive rotation and a
 *             "load archives" toggle. Reader is the CodeVetLog class; it
 *             resolves droplet_id / filter_id to the droplet / filter name
 *             (one batched query, falls back to "#<id>").
 *         [c] The "Error-Log" tab was renamed from "Log View" now that a
 *             second log tab exists; module_description (all 9 locales) and
 *             README updated to mention CodeVet.
 * 
 * v.1.2.0 2026-09-08 Christian M. Stefan
 *         [c] Layout ported to the cp_chrome / cp_theme backend design system
 *             (section.cp-main, nav.cp-tabs, cp-toolbar, cp-card) — matches the
 *             rest of the 1.7.0 backend and follows the active theme.
 *         [+] Split into three tabs: "Error-Log", "CodeVet" and "Settings".
 *         [+] Settings tab: WBCE_DEBUG / SQL_DEBUG / PDO_CANONICAL_DEBUG (file
 *             based) + ER_LEVEL (DB) as described switches in one FTAN-protected
 *             POST form — replaces the old unprotected GET toggle links.
 *         [+] Client side live filter for the log output (search box).
 *         [c] Table view rebuilt: regex parser (robust against brackets/quotes
 *             in messages, folds multi-line errors), Type badge column, relative
 *             + exact timestamp, dedicated File/Line columns, consecutive
 *             duplicates collapsed with an "×N" counter. Own badges for SQL
 *             errors and for PDO_CANONICAL_DEBUG legacy-method nudges; the SQL /
 *             PDO badges are also previewed next to their switch in the Settings tab.
 *         [c] Promoted to a core module ($module_level = 'core').
 *         [c] backend.css / backend.js moved into assets/ (auto-loaded from there
 *             for ?tool=errorlogger by Wbce::retrieveModfilesFromDir()).
 *         [c] Logic / layout split (captcha_control pattern): tool.php is now a
 *             thin controller, log parsing/classification lives in the pure
 *             ErrorlogParser class, all markup in twig/ (tool + settings + logview).
 *         [+] Added README.md + a "Module documentation" link in the Settings
 *             tab that opens it via MarkdownWbce (MdReaderLink), when present.
 *         [c] ER_LEVEL is now an include/wbeSelect widget with a blue E0…E3
 *             badge (data-right) and the description text beside it.
 *         [-] Removed include.php — WB-only snippet glue. WBCE loads preinit.php
 *             / initialize.php directly (function keywords), never include.php
 *             for a non-snippet addon.
 *
 * v.1.1.6 2026-05-03 Christian M. Stefan
 *         [c] fully translate the Errorlog viewer into several languages
 * 
 * v.1.1.5 2026-04-26 Christian M. Stefan
 *         ALL CHANGES CONCERNED WITH WBCE 1.7.0
 *         ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
 *         [+] Implementation of Settings to activate 
 *                      WBCE_DEBUG: Used in core and some modules
 *                       SQL_DEBUG: Show SQL related issues  
 *             PDO_CANONICAL_DEBUG: Check Syntax that's not canonical with the new
 *                                  PDO Database class. This is for devs who review
 *                                  the core and modules and are actively looking
 *                                  for old syntax.
 *    
 *         [+] Added languages.php  adds tranlation strings in Admin-Tools overview 
 *    
 *         [c] small changes to the CSS file (links for the above settings)
 * 
 * v.1.1.4.2 2023-11-28 florian
 *         [!] Fix issue with incorrect/incomplete timestamp (reported by hpzaun)
 *
 * v.1.1.4.1 2022-07-30 ruud / dev4me
 *         [!] Fixed a deprecated notice on PHP8.1
 *
 * v.1.1.4 2022-07-27 ruud / dev4me
 *         [+] added the called url when an error is detected. Helps in locating and fixing the error.
 *         [+] The module can now also be installed as snippet in WB. Note that errors in other snippets might not add the extra info.
 *         [!] Fixed some more errors in the table view
 *
 * v.1.1.3 2022-01-04 ruud / dev4me
 *         [!] fix for notices that should be suppressed by @ in php8+
 *         [!] fix for tableview generating errors on some loglines
 *         [!] fix for deleted /var/logs/ directory
 *
 * v.1.1.2 2021-09-05 stefanek
 *         [!] fix for issue 508 (empty log creates issues itself)
 *
 * v.1.1.1 2021-05-29 Colinax
 *         [+] add upgrade.php
 *         [c] cs fixed files
 *
 * v.1.1.0 2020-07-23 Christian M. Stefan (Stefanek)
           [+] Implementation of Table View
           [c] changes to CSS file adding styles for the Table View
 *
 */
