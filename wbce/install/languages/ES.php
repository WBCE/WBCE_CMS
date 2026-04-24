<?php 
/**
 * @file    ES.php
 * @brief   Spanish language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'ES';
$INFO['language_name'] = 'Español';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'Asistente de instalación de WBCE CMS';
$TXT['welcome_heading']      = 'Asistente de instalación';
$TXT['welcome_sub']          = 'Completa todos los pasos siguientes para finalizar la instalación';

$TXT['step1_heading']        = 'Paso 1 — Requisitos del sistema';
$TXT['step1_desc']           = 'Verificando que tu servidor cumple todos los requisitos';
$TXT['step2_heading']        = 'Paso 2 — Configuración del sitio web';
$TXT['step2_desc']           = 'Configura los parámetros básicos del sitio y la configuración regional';
$TXT['step3_heading']        = 'Paso 3 — Base de datos';
$TXT['step3_desc']           = 'Introduce tus datos de conexión MySQL / MariaDB';
$TXT['step4_heading']        = 'Paso 4 — Cuenta de administrador';
$TXT['step4_desc']           = 'Crea tus credenciales de acceso al panel de administración';
$TXT['step5_heading']        = 'Paso 5 — Instalar WBCE CMS';
$TXT['step5_desc']           = 'Revisa la licencia e inicia la instalación';

$TXT['req_php_version']      = 'Versión de PHP >=';
$TXT['req_php_sessions']     = 'Soporte de sesiones PHP';
$TXT['req_server_charset']   = 'Juego de caracteres predeterminado del servidor';
$TXT['req_safe_mode']        = 'Modo seguro de PHP';
$TXT['files_and_dirs_perms'] = 'Permisos de archivos y directorios';
$TXT['file_perm_descr']      = 'Las siguientes rutas deben ser escribibles por el servidor web';

$TXT['lbl_website_title']    = 'Título del sitio web';
$TXT['lbl_absolute_url']     = 'URL absoluta';
$TXT['lbl_timezone']         = 'Zona horaria predeterminada';
$TXT['lbl_language']         = 'Idioma predeterminado';
$TXT['lbl_server_os']        = 'Sistema operativo del servidor';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Permisos de escritura para todos (777)';

$TXT['lbl_db_host']          = 'Nombre del host';
$TXT['lbl_db_name']          = 'Nombre de la base de datos';
$TXT['lbl_db_prefix']        = 'Prefijo de tablas';
$TXT['lbl_db_user']          = 'Nombre de usuario';
$TXT['lbl_db_pass']          = 'Contraseña';
$TXT['btn_test_db']          = 'Probar conexión';
$TXT['db_testing']           = 'Conectando…';
$TXT['db_retest']            = 'Probar de nuevo';

$TXT['lbl_admin_login']      = 'Nombre de usuario';
$TXT['lbl_admin_email']      = 'Dirección de correo electrónico';
$TXT['lbl_admin_pass']       = 'Contraseña';
$TXT['lbl_admin_repass']     = 'Repetir contraseña';
$TXT['btn_gen_password']     = '⚙ Generar';
$TXT['pw_copy_hint']         = 'Copiar contraseña';

$TXT['btn_install']          = '▶  Instalar WBCE CMS';
$TXT['btn_check_settings']   = 'Verifica tus ajustes en el Paso 1 y recarga la página con F5';

$TXT['error_prefix']         = 'Error';
$TXT['version_prefix']       = 'Versión de WBCE';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'El soporte de sesiones PHP puede aparecer desactivado si tu navegador no acepta cookies.';

$MSG['charset_warning'] =
    'Tu servidor web está configurado para entregar solo el juego de caracteres <b>%1$s</b>. '
    . 'Para mostrar correctamente los caracteres especiales, desactiva esta configuración predeterminada '
    . '(o consulta con tu proveedor de hosting). También puedes seleccionar <b>%1$s</b> en los ajustes de WBCE, '
    . 'aunque esto puede afectar la salida de algunos módulos.';

$MSG['world_writeable_warning'] =
    'Recomendado solo para entornos de prueba. '
    . 'Puedes cambiar este ajuste más tarde en el panel de administración.';

$MSG['license_notice'] =
    'WBCE CMS se publica bajo la licencia <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Al hacer clic en el botón de instalar a continuación, confirmas '
    . 'que has leído y aceptas los términos de la licencia.';

// JS validation messages
$MSG['val_required']       = 'Este campo es obligatorio.';
$MSG['val_url']            = 'Introduce una URL válida (que comience con http:// o https://).';
$MSG['val_email']          = 'Introduce una dirección de correo electrónico válida.';
$MSG['val_pw_mismatch']    = 'Las contraseñas no coinciden.';
$MSG['val_pw_short']       = 'La contraseña debe tener al menos 12 caracteres.';
$MSG['val_db_untested']    = 'Prueba primero la conexión a la base de datos con éxito antes de instalar.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Por favor, completa primero el host, el nombre de la base de datos y el nombre de usuario.';
$MSG['db_pdo_missing']        = 'La extensión PDO no está disponible en este servidor.';
$MSG['db_success']            = 'Conexión exitosa: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Acceso denegado. Verifica el nombre de usuario y la contraseña.';
$MSG['db_unknown_db']         = 'La base de datos no existe. Créala primero o verifica el nombre.';
$MSG['db_connection_refused'] = 'No se pudo conectar al host. Verifica el nombre del host y el puerto.';
$MSG['db_connection_failed']  = 'Error de conexión: %s';
