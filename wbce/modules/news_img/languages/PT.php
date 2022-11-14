<?php
/**
 *
 * @category        modules
 * @package         news_img
 * @author          WBCE Community
 * @copyright       2004-2009, Ryan Djurovich
 * @copyright       2009-2010, Website Baker Org. e.V.
 * @copyright       2019-, WBCE Community
 * @link            https://www.wbce.org/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WBCE
 *
 */

// Modul Description
$module_description = 'Módulo para criar itens de notícias com imagem e galeria de itens (opcional).';

// Variables for the backend
$MOD_NEWS_IMG['ACTION'] = 'Postagens marcadas';
$MOD_NEWS_IMG['ACTIVATE'] = 'ativar';
$MOD_NEWS_IMG['ACTIVATE_POST'] = 'ativar postagem';
$MOD_NEWS_IMG['ADD_GROUP'] = 'Adicionar grupo';
$MOD_NEWS_IMG['ADD_POST'] = 'Adicionar postagem';
$MOD_NEWS_IMG['ADD_TAG'] = 'Adicionar etiqueta';
$MOD_NEWS_IMG['ADVANCED_SETTINGS'] = 'Configurações avançadas';
$MOD_NEWS_IMG['ALL'] = 'Tudo';
$MOD_NEWS_IMG['ASSIGN_GROUP'] = 'Atribuir grupo';
$MOD_NEWS_IMG['ASSIGN_TAGS'] = 'Atribuir tags';
$MOD_NEWS_IMG['CONTINUE'] = 'Próximo';
$MOD_NEWS_IMG['COPY'] = 'cópia de';
$MOD_NEWS_IMG['COPY_WITH_TAGS'] = 'cópia (incl. etiquetas)';
$MOD_NEWS_IMG['COPY_POST'] = 'Copiar postagem';
$MOD_NEWS_IMG['CURRENT_SECTION'] = 'Seção atual';
$MOD_NEWS_IMG['DEACTIVATE'] = 'desativar';
$MOD_NEWS_IMG['DEACTIVATE_POST'] = 'desativar postagem';
$MOD_NEWS_IMG['DELETE'] = 'excluir';
$MOD_NEWS_IMG['DELETEIMAGE'] = 'Excluir esta imagem?';
$MOD_NEWS_IMG['DESCENDING'] = 'descendente';
$MOD_NEWS_IMG['EXPERT_MODE'] = 'modo especialista';
$MOD_NEWS_IMG['EXPIRED_NOTE'] = 'O lançamento não é mais exibido no frontend porque a data de validade expirou.';
$MOD_NEWS_IMG['FIRST_EXPIRING_LAST'] = 'primeiro a expirar por último';
$MOD_NEWS_IMG['GALLERY_SETTINGS'] = 'Configurações de galeria / imagem';
$MOD_NEWS_IMG['GALLERYIMAGES'] = 'Imagens da galeria';
$MOD_NEWS_IMG['GENERIC_IMAGE_ERROR'] = 'Problemas com imagens de postagem e / ou galeria. Verifique o nome do arquivo, o tipo de arquivo e o tamanho do arquivo.';
$MOD_NEWS_IMG['GLOBAL'] = 'Etiqueta global';
$MOD_NEWS_IMG['GOBACK'] = 'De volta';
$MOD_NEWS_IMG['GROUP'] = 'Grupo';
$MOD_NEWS_IMG['GROUPS'] = 'Grupos';
$MOD_NEWS_IMG['IMAGE_FILENAME_ERROR'] = 'O nome do arquivo é muito longo (são permitidos no máximo 256 caracteres)';
$MOD_NEWS_IMG['IMAGE_INVALID_TYPE'] = 'Tipo de imagem não suportado';
$MOD_NEWS_IMG['IMAGE_LARGER_THAN'] = 'A imagem é muito grande, máx. Tamanho: ';
$MOD_NEWS_IMG['IMAGE_TOO_SMALL'] = 'A imagem é muito pequena';
$MOD_NEWS_IMG['IMAGEUPLOAD'] = 'Enviar imagens';
$MOD_NEWS_IMG['IMPORT_OPTIONS'] = 'Opções de importação';
$MOD_NEWS_IMG['INFO_GLOBAL'] = 'Tags globais podem ser usadas em todas as notícias com seções de imagens.';
$MOD_NEWS_IMG['INFO_RELOAD_PAGE'] = 'Isso recarregará a página; todos os dados não salvos serão perdidos!';
$MOD_NEWS_IMG['LINK'] = 'Ligação';
$MOD_NEWS_IMG['LOAD_VALUES'] = 'Carregar valores';
$MOD_NEWS_IMG['MANAGE_POSTS'] = 'gerenciar postagens';
$MOD_NEWS_IMG['MOVE'] = 'mover';
$MOD_NEWS_IMG['MOVE_WITH_TAGS'] = 'mover (incl. etiquetas)';
$MOD_NEWS_IMG['NEW_POST'] = 'Criar nova postagem';
$MOD_NEWS_IMG['NEWEST_FIRST'] = 'mais recente no topo';
$MOD_NEWS_IMG['NONE'] = 'Nenhum';
$MOD_NEWS_IMG['OPTIONS'] = 'Opções';
$MOD_NEWS_IMG['OR'] = 'or'; //missing
$MOD_NEWS_IMG['ORDER_CUSTOM_INFO'] = 'A configuração &quot;personalizado&quot; permite a classificação manual de artigos através das setas para cima / para baixo.';
$MOD_NEWS_IMG['ORDERBY'] = 'Ordenar por';
$MOD_NEWS_IMG['OVERVIEW_SETTINGS'] = 'Configurações da página Visão geral';
$MOD_NEWS_IMG['POST_ACTIVE'] = 'A postagem está visível';
$MOD_NEWS_IMG['POST_CONTENT'] = 'Publicar conteúdo';
$MOD_NEWS_IMG['POST_INACTIVE'] = 'Post is not visible'; //missing
$MOD_NEWS_IMG['POST_SETTINGS'] = 'Configurações de postagem';
$MOD_NEWS_IMG['POSTED_BY'] = 'Postado por';
$MOD_NEWS_IMG['POSTS'] = 'Postagens';
$MOD_NEWS_IMG['PREVIEWIMAGE'] = 'Visualizar imagem';
$MOD_NEWS_IMG['SAVEGOBACK'] = 'Salve e volte';
$MOD_NEWS_IMG['SETTINGS'] = 'Configurações de notícias';
$MOD_NEWS_IMG['SHOW_SETTINGS_ONLY_ADMINS'] = 'Settings can be seen/modified by admins only'; //missing
$MOD_NEWS_IMG['TAG'] = 'Etiqueta';
$MOD_NEWS_IMG['TAG_COLOR'] = 'Cor da etiqueta';
$MOD_NEWS_IMG['TAG_EXISTS'] = 'Existe etiqueta';
$MOD_NEWS_IMG['TAGS'] = 'Etiquetas';
$MOD_NEWS_IMG['TAGS_INFO'] = 'Para usar etiquetas, edite uma postagem e selecione as postagens desejadas lá.';
$MOD_NEWS_IMG['TO'] = 'para';
$MOD_NEWS_IMG['UPLOAD'] = 'Envio';
$MOD_NEWS_IMG['USE_SECOND_BLOCK'] = 'Use o segundo bloco';
$MOD_NEWS_IMG['USE_SECOND_BLOCK_HINT'] = 'Deve ser suportado pelo modelo';
$MOD_NEWS_IMG['VIEW'] = 'Apresentação / Visualização';
$MOD_NEWS_IMG['VIEW_INFO'] = 'Depois de alterar a configuração, pressione salvar; as marcações para exibição de loop de postagem e detalhes da postagem serão ajustadas automaticamente.';

// Image settings
$MOD_NEWS_IMG['CROP'] = 'Colheita';
$MOD_NEWS_IMG['GALLERY'] = 'Galeria de imagens';
$MOD_NEWS_IMG['GALLERY_INFO'] = 'Depois de alterar a configuração da galeria, pressione salvar; a marcação para o loop da imagem será ajustada automaticamente.';
$MOD_NEWS_IMG['GALLERY_WARNING'] = 'Você tem certeza? Observe que as configurações personalizadas para a marcação do loop de imagem serão perdidas.';
$MOD_NEWS_IMG['IMAGE_MAX_SIZE'] = 'Máx. tamanho da imagem em kilobytes';
$MOD_NEWS_IMG['RESIZE_PREVIEW_IMAGE_TO'] = 'Redimensione a imagem de visualização para';
$MOD_NEWS_IMG['RESIZE_GALLERY_IMAGES_TO'] = 'Redimensione as imagens da galeria para';
$MOD_NEWS_IMG['TEXT_CROP'] = 'Se a proporção do original não corresponder à proporção especificada, a sobreposição da borda mais longa será cortada.';
$MOD_NEWS_IMG['TEXT_DEFAULTS'] = 'Tamanhos padrão';
$MOD_NEWS_IMG['TEXT_DEFAULTS_CLICK'] = 'Clique para escolher entre os padrões';
$MOD_NEWS_IMG['THUMB_SIZE'] = 'Tamanho da miniatura';

// Uploader
$MOD_NEWS_IMG['DRAG_N_DROP_HERE'] = 'Arraste &amp; solte arquivos aqui';
$MOD_NEWS_IMG['CLICK_TO_ADD'] = 'Clique para adicionar arquivos';
$MOD_NEWS_IMG['NO_FILES_UPLOADED'] = 'Nenhum arquivo enviado.';
$MOD_NEWS_IMG['COMPLETE_MESSAGE'] = 'Salve suas alterações para mostrar o upload na galeria';

// Variables for the frontend
$MOD_NEWS_IMG['PAGE_NOT_FOUND'] = 'Página não encontrada';
$MOD_NEWS_IMG['TEXT_AT'] = 'às';
$MOD_NEWS_IMG['TEXT_BACK'] = 'De volta';
$MOD_NEWS_IMG['TEXT_BY'] = 'Por';
$MOD_NEWS_IMG['TEXT_LAST_CHANGED'] = 'Última alteração';
$MOD_NEWS_IMG['TEXT_NEXT_POST'] = 'Próximo post';
$MOD_NEWS_IMG['TEXT_O_CLOCK'] = 'horas';
$MOD_NEWS_IMG['TEXT_ON'] = 'em';
$MOD_NEWS_IMG['TEXT_POSTED_BY'] = 'Postado por';
$MOD_NEWS_IMG['TEXT_PREV_POST'] = 'Previous post';
$MOD_NEWS_IMG['TEXT_READ_MORE'] = 'Consulte mais informação';
$MOD_NEWS_IMG['TEXT_RESET'] = 'Redefinir';
$MOD_NEWS_IMG['TO'] = 'para';
$MOD_NEWS_IMG['IMPORT'] = 'importar';
