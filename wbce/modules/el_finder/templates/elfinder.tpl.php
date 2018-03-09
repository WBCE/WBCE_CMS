<?php /*
For automated detection if form has benn sent the submit button needs to have 
name+id ="save_settings". (Optional ($_POST['action']) == 'save')
For return to admintools the responsible button must have name+id="admin_tools".
And to activate default setting it heed to have name+id="save_default".

$returnUrl      Is used as form Action it sends the form to itself(apeform)

Language vars whit preceding MOD_ can be found in the launguage file of this module
Other language vars are from the default WB (e.g. $TEXT or $HEADING are from the 
WBCE language files)

The default button uses a simple Javascript return confirm()for a simple "Are you sure?"
*/?>


<!--(MOVE) CSS HEAD TOP- -->

    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="<?php echo WB_URL."/modules/el_finder/ef/"?>css/elfinder.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo WB_URL."/modules/el_finder/ef/"?>css/theme.css">
	<link rel="stylesheet" type="text/css" href="<?php echo WB_URL; ?>/include/jquery/jquery-ui.min.css">
<!--(END)-->

<!--(MOVE) JS HEAD BTM- -->
    <!-- elFinder JS (REQUIRED) -->
    <script src="<?php echo WB_URL; ?>/include/jquery/jquery-ui-min.js" type="text/javascript"></script>
    <script src="<?php echo WB_URL."/modules/el_finder/ef/"?>js/elfinder.min.js"></script>

    <!-- Extra contents editors (OPTIONAL) -->
    <script src="<?php echo WB_URL."/modules/el_finder/ef/"?>js/extras/editors.default.js"></script>
<!--(END)-->


<!--(MOVE) JS HEAD BTM- -->
    <!-- elFinder initialization (REQUIRED) -->
    <script type="text/javascript" charset="utf-8">
        // Documentation for client options:
        // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
        $(document).ready(function() {
            $('#elfinder').elfinder(
                // 1st Arg - options
                {
                    cssAutoLoad : false,               // Disable CSS auto loading
                    baseUrl : './',                    // Base URL to css/*, js/*
                    url : '<?php echo WB_URL ?>/admin/admintools/tool.php?tool=el_finder&no_page=no_page'  // connector URL (REQUIRED)
                    // , lang: 'ru'                    // language (OPTIONAL)
                },
                // 2nd Arg - before boot up function
                function(fm, extraObj) {
                    // `init` event callback function
                    fm.bind('init', function() {
                        // Optional for Japanese decoder "extras/encoding-japanese.min"
                        delete fm.options.rawStringDecoder;
                        if (fm.lang === 'jp') {
                            fm.loadScript(
                                [ fm.baseUrl + 'js/extras/encoding-japanese.min.js' ],
                                function() {
                                    if (window.Encoding && Encoding.convert) {
                                        fm.options.rawStringDecoder = function(s) {
                                            return Encoding.convert(s,{to:'UNICODE',type:'string'});
                                        };
                                    }
                                },
                                { loadType: 'tag' }
                            );
                        }
                    });
                    // Optional for set document.title dynamically.
                    var title = document.title;
                    fm.bind('open', function() {
                        var path = '',
                            cwd  = fm.cwd();
                        if (cwd) {
                            path = fm.path(cwd.hash) || null;
                        }
                        document.title = path? path + ':' + title : title;
                    }).bind('destroy', function() {
                        document.title = title;
                    });
                }
            );
        });
    </script>
<!--(END)-->        
        
<!-- Element where elFinder will be created (REQUIRED) -->
        <div id="elfinder"></div>
