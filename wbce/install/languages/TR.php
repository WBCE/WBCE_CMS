<?php 
/**
 * @file    TR.php
 * @brief   Turkish language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'TR';
$INFO['language_name'] = 'Türkçe';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'WBCE CMS Kurulum Sihirbazı';
$TXT['welcome_heading']      = 'Kurulum Sihirbazı';
$TXT['welcome_sub']          = 'Kurulumu tamamlamak için aşağıdaki tüm adımları gerçekleştir';

$TXT['step1_heading']        = 'Adım 1 — Sistem Gereksinimleri';
$TXT['step1_desc']           = 'Sunucunun tüm ön koşulları karşılayıp karşılamadığını kontrol ediyor';
$TXT['step2_heading']        = 'Adım 2 — Web Sitesi Ayarları';
$TXT['step2_desc']           = 'Temel site parametrelerini ve yerel ayarları yapılandır';
$TXT['step3_heading']        = 'Adım 3 — Veritabanı';
$TXT['step3_desc']           = 'MySQL / MariaDB bağlantı bilgilerini gir';
$TXT['step4_heading']        = 'Adım 4 — Yönetici Hesabı';
$TXT['step4_desc']           = 'Yönetim paneli giriş bilgilerini oluştur';
$TXT['step5_heading']        = 'Adım 5 — WBCE CMS\'i Kur';
$TXT['step5_desc']           = 'Lisansı incele ve kurulumu başlat';

$TXT['req_php_version']      = 'PHP Sürümü >=';
$TXT['req_php_sessions']     = 'PHP Oturum Desteği';
$TXT['req_server_charset']   = 'Sunucu Varsayılan Karakter Seti';
$TXT['req_safe_mode']        = 'PHP Güvenli Mod';
$TXT['files_and_dirs_perms'] = 'Dosya &amp; Dizin İzinleri';
$TXT['file_perm_descr']      = 'Aşağıdaki yollar web sunucusu tarafından yazılabilir olmalıdır';

$TXT['lbl_website_title']    = 'Web Sitesi Başlığı';
$TXT['lbl_absolute_url']     = 'Mutlak URL';
$TXT['lbl_timezone']         = 'Varsayılan Saat Dilimi';
$TXT['lbl_language']         = 'Varsayılan Dil';
$TXT['lbl_server_os']        = 'Sunucu İşletim Sistemi';
$TXT['lbl_linux']            = 'Linux / Unix tabanlı';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Herkes tarafından yazılabilir dosya izinleri (777)';

$TXT['lbl_db_host']          = 'Sunucu Adı (Host)';
$TXT['lbl_db_name']          = 'Veritabanı Adı';
$TXT['lbl_db_prefix']        = 'Tablo Öneki';
$TXT['lbl_db_user']          = 'Kullanıcı Adı';
$TXT['lbl_db_pass']          = 'Şifre';
$TXT['btn_test_db']          = 'Bağlantıyı Test Et';
$TXT['db_testing']           = 'Bağlanıyor…';
$TXT['db_retest']            = 'Tekrar Test Et';

$TXT['lbl_admin_login']      = 'Giriş Adı';
$TXT['lbl_admin_email']      = 'E-posta Adresi';
$TXT['lbl_admin_pass']       = 'Şifre';
$TXT['lbl_admin_repass']     = 'Şifreyi Tekrarla';
$TXT['btn_gen_password']     = '⚙ Oluştur';
$TXT['pw_copy_hint']         = 'Şifreyi kopyala';

$TXT['btn_install']          = '▶  WBCE CMS\'i Kur';
$TXT['btn_check_settings']   = 'Adım 1\'deki ayarlarını kontrol et ve sayfayı F5 ile yenile';

$TXT['error_prefix']         = 'Hata';
$TXT['version_prefix']       = 'WBCE Sürümü';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'Tarayıcın çerezleri desteklemiyorsa PHP Oturum Desteği devre dışı görünebilir.';

$MSG['charset_warning'] =
    'Web sunucun yalnızca <b>%1$s</b> karakter setini teslim edecek şekilde yapılandırılmış. '
    . 'Özel karakterleri doğru görüntülemek için lütfen bu ön ayarı devre dışı bırak '
    . '(veya hosting sağlayıcına sor). WBCE ayarlarında da <b>%1$s</b> seçebilirsin, '
    . 'ancak bu bazı modüllerin çıktısını etkileyebilir.';

$MSG['world_writeable_warning'] =
    'Yalnızca test ortamları için önerilir. '
    . 'Bu ayarı daha sonra yönetim panelinden değiştirebilirsin.';

$MSG['license_notice'] =
    'WBCE CMS, <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a> lisansı altında yayınlanmaktadır. Aşağıdaki kur butonuna tıklayarak '
    . 'lisans koşullarını okuduğunu ve kabul ettiğini onaylıyorsun.';

// JS validation messages
$MSG['val_required']       = 'Bu alan zorunludur.';
$MSG['val_url']            = 'Lütfen geçerli bir URL gir (http:// veya https:// ile başlamalı).';
$MSG['val_email']          = 'Lütfen geçerli bir e-posta adresi gir.';
$MSG['val_pw_mismatch']    = 'Şifreler eşleşmiyor.';
$MSG['val_pw_short']       = 'Şifre en az 12 karakter uzunluğunda olmalıdır.';
$MSG['val_db_untested']    = 'Lütfen kuruluma geçmeden önce veritabanı bağlantısını başarılı bir şekilde test et.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Lütfen önce sunucu adı, veritabanı adı ve kullanıcı adını doldur.';
$MSG['db_pdo_missing']        = 'PDO uzantısı bu sunucuda mevcut değil.';
$MSG['db_success']            = 'Bağlantı başarılı: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Erişim reddedildi. Kullanıcı adı ve şifreyi kontrol et.';
$MSG['db_unknown_db']         = 'Veritabanı mevcut değil. Önce oluştur veya adını kontrol et.';
$MSG['db_connection_refused'] = 'Sunucuya bağlanılamadı. Sunucu adını ve portu kontrol et.';
$MSG['db_connection_failed']  = 'Bağlantı başarısız: %s';
