<?php 
/**
 * @file    GR.php
 * @brief   Greek language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'GR';
$INFO['language_name'] = 'Ελληνικά';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS Οδηγός εγκατάστασης';
$TXT['welcome_heading']      = 'Οδηγός εγκατάστασης';
$TXT['welcome_sub']          = 'Ολοκλήρωσε όλα τα παρακάτω βήματα για να ολοκληρωθεί η εγκατάσταση';

$TXT['step1_heading']        = 'Βήμα 1 — Απαιτήσεις συστήματος';
$TXT['step1_desc']           = 'Έλεγχος αν ο διακομιστής σου πληροί όλες τις προϋποθέσεις';
$TXT['step2_heading']        = 'Βήμα 2 — Ρυθμίσεις ιστοσελίδας';
$TXT['step2_desc']           = 'Διαμόρφωσε τις βασικές παραμέτρους του site και τις τοπικές ρυθμίσεις';
$TXT['step3_heading']        = 'Βήμα 3 — Βάση δεδομένων';
$TXT['step3_desc']           = 'Εισήγαγε τα στοιχεία σύνδεσης MySQL / MariaDB';
$TXT['step4_heading']        = 'Βήμα 4 — Λογαριασμός διαχειριστή';
$TXT['step4_desc']           = 'Δημιούργησε τα στοιχεία σύνδεσης στο backend';
$TXT['step5_heading']        = 'Βήμα 5 — Εγκατάσταση WBCE CMS';
$TXT['step5_desc']           = 'Έλεγξε την άδεια χρήσης και ξεκίνα την εγκατάσταση';

$TXT['req_php_version']      = 'Έκδοση PHP >=';
$TXT['req_php_sessions']     = 'Υποστήριξη PHP Sessions';
$TXT['req_server_charset']   = 'Προεπιλεγμένο σετ χαρακτήρων διακομιστή';
$TXT['req_safe_mode']        = 'PHP Safe Mode';
$TXT['files_and_dirs_perms'] = 'Δικαιώματα αρχείων &amp; φακέλων';
$TXT['file_perm_descr']      = 'Οι παρακάτω διαδρομές πρέπει να είναι εγγράψιμες από τον web server';

$TXT['lbl_website_title']    = 'Τίτλος ιστοσελίδας';
$TXT['lbl_absolute_url']     = 'Απόλυτο URL';
$TXT['lbl_timezone']         = 'Προεπιλεγμένη ζώνη ώρας';
$TXT['lbl_language']         = 'Προεπιλεγμένη γλώσσα';
$TXT['lbl_server_os']        = 'Λειτουργικό σύστημα διακομιστή';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Δικαιώματα εγγραφής για όλους (777)';

$TXT['lbl_db_host']          = 'Όνομα διακομιστή (Host)';
$TXT['lbl_db_name']          = 'Όνομα βάσης δεδομένων';
$TXT['lbl_db_prefix']        = 'Πρόθεμα πινάκων';
$TXT['lbl_db_user']          = 'Όνομα χρήστη';
$TXT['lbl_db_pass']          = 'Κωδικός πρόσβασης';
$TXT['btn_test_db']          = 'Δοκιμή σύνδεσης';
$TXT['db_testing']           = 'Σύνδεση…';
$TXT['db_retest']            = 'Δοκιμή ξανά';

$TXT['lbl_admin_login']      = 'Όνομα σύνδεσης';
$TXT['lbl_admin_email']      = 'Διεύθυνση e-mail';
$TXT['lbl_admin_pass']       = 'Κωδικός πρόσβασης';
$TXT['lbl_admin_repass']     = 'Επανάληψη κωδικού';
$TXT['btn_gen_password']     = '⚙ Δημιουργία';
$TXT['pw_copy_hint']         = 'Αντιγραφή κωδικού';

$TXT['btn_install']          = '▶  Εγκατάσταση WBCE CMS';
$TXT['btn_check_settings']   = 'Έλεγξε τις ρυθμίσεις σου στο Βήμα 1 και ανανέωσε τη σελίδα με F5';

$TXT['error_prefix']         = 'Σφάλμα';
$TXT['version_prefix']       = 'Έκδοση WBCE';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'Η υποστήριξη PHP Sessions μπορεί να εμφανίζεται ως απενεργοποιημένη αν ο browser σου δεν υποστηρίζει cookies.';

$MSG['charset_warning'] =
    'Ο web server σου είναι ρυθμισμένος να στέλνει μόνο το σετ χαρακτήρων <b>%1$s</b>. '
    . 'Για να εμφανίζονται σωστά οι ειδικοί χαρακτήρες, απενεργοποίησε αυτή την προεπιλογή '
    . '(ή ρώτα τον πάροχο hosting σου). Μπορείς επίσης να επιλέξεις <b>%1$s</b> στις ρυθμίσεις WBCE, '
    . 'αν και αυτό μπορεί να επηρεάσει την έξοδο ορισμένων modules.';

$MSG['world_writeable_warning'] =
    'Συνιστάται μόνο για περιβάλλοντα δοκιμών. '
    . 'Μπορείς να αλλάξεις αυτή τη ρύθμιση αργότερα στο Backend.';

$MSG['license_notice'] =
    'Το WBCE CMS διανέμεται υπό την άδεια <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Κάνοντας κλικ στο κουμπί εγκατάστασης παρακάτω, επιβεβαιώνεις '
    . 'ότι έχεις διαβάσει και αποδέχεσαι τους όρους της άδειας χρήσης.';

// JS validation messages
$MSG['val_required']       = 'Αυτό το πεδίο είναι υποχρεωτικό.';
$MSG['val_url']            = 'Παρακαλώ εισήγαγε ένα έγκυρο URL (που ξεκινά με http:// ή https://).';
$MSG['val_email']          = 'Παρακαλώ εισήγαγε μια έγκυρη διεύθυνση e-mail.';
$MSG['val_pw_mismatch']    = 'Οι κωδικοί πρόσβασης δεν ταιριάζουν.';
$MSG['val_pw_short']       = 'Ο κωδικός πρόσβασης πρέπει να έχει τουλάχιστον 12 χαρακτήρες.';
$MSG['val_db_untested']    = 'Παρακαλώ δοκίμασε πρώτα επιτυχώς τη σύνδεση με τη βάση δεδομένων πριν την εγκατάσταση.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Παρακαλώ συμπλήρωσε πρώτα το host, το όνομα της βάσης και το όνομα χρήστη.';
$MSG['db_pdo_missing']        = 'Η επέκταση PDO δεν είναι διαθέσιμη σε αυτόν τον διακομιστή.';
$MSG['db_success']            = 'Επιτυχής σύνδεση: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Άρνηση πρόσβασης. Έλεγξε το όνομα χρήστη και τον κωδικό πρόσβασης.';
$MSG['db_unknown_db']         = 'Η βάση δεδομένων δεν υπάρχει. Δημιούργησέ την πρώτα ή έλεγξε το όνομα.';
$MSG['db_connection_refused'] = 'Δεν ήταν δυνατή η σύνδεση με τον διακομιστή. Έλεγξε το όνομα host και την πόρτα.';
$MSG['db_connection_failed']  = 'Η σύνδεση απέτυχε: %s';
