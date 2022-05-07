<?php require_once '../../../config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
    <title>Media Management</title>
    <style>body { margin: 0; }</style>
    <script data-main="./main.wbce_cke.js" src="./js/require.min.js"></script>
    <script>
        define('elFinderConfig', {
            defaultOpts: {
                url: 'php/connector.wbce.php'
            },
            managers: {
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
                    height: '100%',
                    resizable: false
                }
            }
        });
    </script>
</head>
<body>
    <div id="elfinder"></div>
    <link rel="stylesheet" type="text/css" media="screen" href="themes/material/css/theme-gray.min.css">
</body>
</html>