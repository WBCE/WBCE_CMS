<?php

// $Id: EN.php 1179 2009-11-24 18:15:00Z Luisehahne $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2009, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

 -----------------------------------------------------------------------------------------
  PORTUGUESE LANGUAGE FILE FOR THE CAPTCHA-CONTROL ADMIN TOOL
 -----------------------------------------------------------------------------------------
*/

// Headings and text outputs
$MOD_CAPTCHA_CONTROL['HEADING']           = 'Controle Captcha e ASP';
$MOD_CAPTCHA_CONTROL['HOWTO']             = 'Aqui você pode controlar o comportamento de "CAPTCHA" e "Advanced Spam Protection" (ASP). Para que o ASP funcione com um determinado módulo, este módulo especial deve ser adaptado para fazer uso do ASP.';

// Text and captions of form elements
$MOD_CAPTCHA_CONTROL['CAPTCHA_CONF']      = 'Configuração CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TYPE']      = 'Tipo de CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_EXP']       = 'As configurações do CAPTCHA para módulos estão localizadas nas respectivas configurações do módulo';
$MOD_CAPTCHA_CONTROL['USE_SIGNUP_CAPTCHA']= 'Ativar CAPTCHA para inscrição';
$MOD_CAPTCHA_CONTROL['ENABLED']           = 'ativado';
$MOD_CAPTCHA_CONTROL['DISABLED']          = 'Desativado';
$MOD_CAPTCHA_CONTROL['ASP_CONF']          = 'Configuração avançada de proteção contra spam';
$MOD_CAPTCHA_CONTROL['ASP_TEXT']          = 'Ativar ASP (se disponível)';
$MOD_CAPTCHA_CONTROL['ASP_EXP']           = 'O ASP tenta determinar se uma entrada de formulário foi originada de um humano ou de um bot de spam.';
$MOD_CAPTCHA_CONTROL['CALC_TEXT']         = 'Cálculo como texto';
$MOD_CAPTCHA_CONTROL['CALC_IMAGE']        = 'Cálculo como imagem';
$MOD_CAPTCHA_CONTROL['CALC_TTF_IMAGE']    = 'Cálculo como imagem com fontes e fundos variados'; 
$MOD_CAPTCHA_CONTROL['TTF_IMAGE']         = 'Imagem com fontes e planos de fundo variados';
$MOD_CAPTCHA_CONTROL['OLD_IMAGE']         = 'Estilo antigo (não recomendado)';
$MOD_CAPTCHA_CONTROL['TEXT']              = 'Text-CAPTCHA';
$MOD_CAPTCHA_CONTROL['CAPTCHA_ENTER_TEXT']= 'Perguntas e respostas';
$MOD_CAPTCHA_CONTROL['CAPTCHA_TEXT_DESC'] = 'Exclua tudo isso para adicionar suas próprias entradas'."\n".'ou suas alterações não serão salvas!'."\n".'### example ###'."\n".'Here you can enter Questions and Answers.'."\n".'Use:'."\n".'?What\'s Claudia Schiffer\'s first name?'."\n".'!Claudia'."\n".'?Question 2'."\n".'!Answer 2'."\n".''."\n".'if language doesn\'t matter.'."\n".' ... '."\n".'Or, if language do matter, use:'."\n".'?EN:What\'s Claudia Schiffer\'s first name?'."\n".'!Claudia'."\n".'?EN:Question 2'."\n".'!Answer 2'."\n".'?DE:Wie ist der Vorname von Claudia Schiffer?'."\n".'!Claudia'."\n".' ... '."\n".'### example ###'."\n".'';
$MOD_CAPTCHA['VERIFICATION']           = 'Verificação';
$MOD_CAPTCHA['ADDITION']               = 'adicionar';
$MOD_CAPTCHA['SUBTRAKTION']            = 'subtrair';
$MOD_CAPTCHA['MULTIPLIKATION']         = 'multiplicar';
$MOD_CAPTCHA['VERIFICATION_INFO_RES']  = 'Preencha o resultado';
$MOD_CAPTCHA['VERIFICATION_INFO_TEXT'] = 'Preencha o texto';
$MOD_CAPTCHA['VERIFICATION_INFO_QUEST'] = 'Responda à pergunta';
$MOD_CAPTCHA['INCORRECT_VERIFICATION'] = 'Falha na verificação';

?>