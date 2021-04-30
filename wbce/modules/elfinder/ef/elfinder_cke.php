<?php require_once '../../../config.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
	<title>Media Management</title>
	<style>body { margin: 0; }</style>

	<!-- Require JS (REQUIRED) -->
	<script data-main="./main.wbce_cke.js" src="./js/require.min.js"></script>

	<!-- elFinder Basic Auth JS -->
	<!-- <script src="js/elfinderBasicAuth.js"></script>  -->

	<!-- elFinder initialization (REQUIRED) -->
	<script>
		define('elFinderConfig', {
			// elFinder options (REQUIRED)
			// Documentation for client options:
			// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
			defaultOpts: {
				url: 'php/connector.wbce.php', // or connector.maximal.php : connector URL (REQUIRED)
			},
			managers: {
				// 'DOM Element ID': { /* elFinder options of this DOM Element */ }
				'elfinder': {
					getFileCallback: function (file, fm) {
						window.opener.CKEDITOR.tools.callFunction((function () {
							var reParam = new RegExp('(?:[\?&]|&amp;)CKEditorFuncNum=([^&]+)', 'i');
							var match = window.location.search.match(reParam);
							return (match && match.length > 1) ? match[1] : '';
						})(), fm.convAbsUrl(file.url));
						fm.destroy();
						window.close();
					},
					height: '100%' // optional
						,
					resizable: false // optional
				}
			}
		});
	</script>
</head>
<body>
	<!-- Element where elFinder will be created (REQUIRED) -->
	<div id="elfinder"></div>
</body>
</html>