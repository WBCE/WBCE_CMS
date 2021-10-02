<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
	<title>Media Management</title>
	<style>body { margin: 0; }</style>

<link rel="stylesheet" type="text/css" media="screen" href="../../modules/elfinder/ef/themes/material/css/theme-gray.css">

	<!-- Require JS (REQUIRED) -->
	<script data-main="../../modules/elfinder/ef/main.wbce.js" src="../../modules/elfinder/ef/js/require.min.js">
	</script>

	<!-- elFinder Basic Auth JS -->
	<!-- <script src="js/elfinderBasicAuth.js"></script>  -->

	<!-- elFinder initialization (REQUIRED) -->

	<script>
		define('elFinderConfig', {
			// elFinder options (REQUIRED)
			// Documentation for client options:
			// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
			defaultOpts: {
				url: '../../modules/elfinder/ef/php/connector.wbce.php', // or connector.maximal.php : connector URL (REQUIRED)
				height: $(window).height() - 200
			},
			managers: {
				// 'DOM Element ID': { /* elFinder options of this DOM Element */ }
				'elfinder': {}
			}
		});
	</script>
</head>
<body>
	<!-- Element where elFinder will be created (REQUIRED) -->
	<div id="elfinder"></div>
</body>
</html>