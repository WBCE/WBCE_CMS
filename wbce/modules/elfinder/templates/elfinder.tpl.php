<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
    <title>Media Management</title>
    <style>body { margin: 0; }</style>
    <script data-main="../../modules/elfinder/ef/main.wbce.js" src="../../modules/elfinder/ef/js/require.min.js"></script>
    <script>
        define('elFinderConfig', {
            defaultOpts: {
                url: '../../modules/elfinder/ef/php/connector.wbce.php',
                height: $(window).height() - 250
            },
            managers: {
                'elfinder': {}
            }
        });
    </script>
</head>
<body>
    <div id="elfinder"></div>
</body>
</html>