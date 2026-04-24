<?php 
/**
 * @file    PT.php
 * @brief   Portuguese language strings for the WBCE CMS Installation Wizard
 */

$INFO['language_code'] = 'PT';
$INFO['language_name'] = 'Português';

// ─── UI Text ──────────────────────────────────────────────────────────────────
$TXT['page_title']           = 'Assistente de Instalação do WBCE CMS';
$TXT['welcome_heading']      = 'Assistente de Instalação';
$TXT['welcome_sub']          = 'Completa todos os passos abaixo para finalizar a instalação';

$TXT['step1_heading']        = 'Passo 1 — Requisitos do Sistema';
$TXT['step1_desc']           = 'A verificar se o teu servidor cumpre todos os requisitos';
$TXT['step2_heading']        = 'Passo 2 — Definições do Site';
$TXT['step2_desc']           = 'Configura os parâmetros básicos do site e as definições regionais';
$TXT['step3_heading']        = 'Passo 3 — Base de Dados';
$TXT['step3_desc']           = 'Introduz os teus dados de ligação MySQL / MariaDB';
$TXT['step4_heading']        = 'Passo 4 — Conta de Administrador';
$TXT['step4_desc']           = 'Cria as tuas credenciais de acesso ao painel de administração';
$TXT['step5_heading']        = 'Passo 5 — Instalar WBCE CMS';
$TXT['step5_desc']           = 'Revisa a licença e inicia a instalação';

$TXT['req_php_version']      = 'Versão PHP >=';
$TXT['req_php_sessions']     = 'Suporte a Sessões PHP';
$TXT['req_server_charset']   = 'Conjunto de caracteres predefinido do servidor';
$TXT['req_safe_mode']        = 'Modo de Segurança PHP';
$TXT['files_and_dirs_perms'] = 'Permissões de Ficheiros &amp; Pastas';
$TXT['file_perm_descr']      = 'Os seguintes caminhos devem ser graváveis pelo servidor web';

$TXT['lbl_website_title']    = 'Título do Site';
$TXT['lbl_absolute_url']     = 'URL Absoluta';
$TXT['lbl_timezone']         = 'Fuso Horário Predefinido';
$TXT['lbl_language']         = 'Idioma Predefinido';
$TXT['lbl_server_os']        = 'Sistema Operativo do Servidor';
$TXT['lbl_linux']            = 'Linux / Unix';
$TXT['lbl_windows']          = 'Windows';
$TXT['lbl_world_writeable']  = 'Permissões de escrita para todos (777)';

$TXT['lbl_db_host']          = 'Nome do Host';
$TXT['lbl_db_name']          = 'Nome da Base de Dados';
$TXT['lbl_db_prefix']        = 'Prefixo das Tabelas';
$TXT['lbl_db_user']          = 'Nome de Utilizador';
$TXT['lbl_db_pass']          = 'Palavra-passe';
$TXT['btn_test_db']          = 'Testar Ligação';
$TXT['db_testing']           = 'A ligar…';
$TXT['db_retest']            = 'Testar novamente';

$TXT['lbl_admin_login']      = 'Nome de Utilizador';
$TXT['lbl_admin_email']      = 'Endereço de E-mail';
$TXT['lbl_admin_pass']       = 'Palavra-passe';
$TXT['lbl_admin_repass']     = 'Repetir Palavra-passe';
$TXT['btn_gen_password']     = '⚙ Gerar';
$TXT['pw_copy_hint']         = 'Copiar palavra-passe';

$TXT['btn_install']          = '▶  Instalar WBCE CMS';
$TXT['btn_check_settings']   = 'Verifica as tuas definições no Passo 1 e recarrega a página com F5';

$TXT['error_prefix']         = 'Erro';
$TXT['version_prefix']       = 'Versão WBCE';
$TXT['license_link_text']    = 'GNU General Public License';
$TXT['gmt']                  = 'GMT';


// ─── Messages / Warnings ──────────────────────────────────────────────────────
$MSG['session_cookie_warning'] = 'O suporte a sessões PHP pode aparecer desativado se o teu navegador não aceitar cookies.';

$MSG['charset_warning'] =
    'O teu servidor web está configurado para entregar apenas o conjunto de caracteres <b>%1$s</b>. '
    . 'Para exibir corretamente os caracteres especiais, desativa esta predefinição '
    . '(ou pergunta ao teu fornecedor de alojamento). Podes também selecionar <b>%1$s</b> nas definições do WBCE, '
    . 'embora isso possa afetar a saída de alguns módulos.';

$MSG['world_writeable_warning'] =
    'Recomendado apenas para ambientes de teste. '
    . 'Podes alterar esta definição mais tarde no painel de administração.';

$MSG['license_notice'] =
    'O WBCE CMS é distribuído sob a licença <a href="http://www.gnu.org/licenses/gpl.html" '
    . 'target="_blank">%s</a>. Ao clicares no botão de instalação abaixo, confirmas '
    . 'que leste e aceitas os termos da licença.';

// JS validation messages
$MSG['val_required']       = 'Este campo é obrigatório.';
$MSG['val_url']            = 'Por favor, introduz um URL válido (começando com http:// ou https://).';
$MSG['val_email']          = 'Por favor, introduz um endereço de e-mail válido.';
$MSG['val_pw_mismatch']    = 'As palavras-passe não coincidem.';
$MSG['val_pw_short']       = 'A palavra-passe deve ter pelo menos 12 caracteres.';
$MSG['val_db_untested']    = 'Por favor, testa primeiro com sucesso a ligação à base de dados antes de instalar.';

// ─── Database Connection Test Messages ───────────────────────────────────────
$MSG['db_fill_required']      = 'Por favor, preenche primeiro o host, o nome da base de dados e o nome de utilizador.';
$MSG['db_pdo_missing']        = 'A extensão PDO não está disponível neste servidor.';
$MSG['db_success']            = 'Ligação bem-sucedida: MySQL / MariaDB %s';
$MSG['db_access_denied']      = 'Acesso negado. Verifica o nome de utilizador e a palavra-passe.';
$MSG['db_unknown_db']         = 'A base de dados não existe. Cria-a primeiro ou verifica o nome.';
$MSG['db_connection_refused'] = 'Não foi possível ligar ao host. Verifica o nome do host e a porta.';
$MSG['db_connection_failed']  = 'Falha na ligação: %s';