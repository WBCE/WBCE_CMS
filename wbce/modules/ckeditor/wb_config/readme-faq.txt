/**
 *  @module         ckeditor
 *  @version        see info.php of this module
 *  @authors        Michael Tenschert, Dietrich Roland Pehlke, Dietmar Woellbrink
 *  @copyright      2010-2012 Michael Tenschert, Dietrich Roland Pehlke, Luisehahne
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *  @requirements   PHP 5.2.x and higher
 */

################################################

---     WebsiteBaker CKEditor module         ---
---     FAQ: How to use customization         --- 

################################################
################################################


# Question: #

What files are there to customize CKEditor for WebsiteBaker?

# Answer:   #

All default files in CKEditor modules are in the folder: _yourwbinstallation_/modules/ckeditor/wb_config

There are four different files. Please look inside each file with your FTP-Browser / AddonFileEditor / on your local computer:

+ wb_ckconfig.js:         Here are most of the configuration issues defined. 
                        Toolbar-Configuration, behavior on Enter / Shift+Enter, default language and so on.

+ editor.css:             The default look of the WYSIWYG textarea and the preview. 
                        You can also put an editor.css in any frontend template, 
                        it will be loaded automatically for each page instead of the default one.

+ editor.styles.js:     The default styles you can choose from a dropdown in the CKEditor toolbar. 
                        You can also put an editor.styles.js in any frontend template, 
                        it will be loaded automatically for each page instead of the default one.

+ editor.templates.js:     The default CKE templates you can choose of a button in CKEditor toolbar. 
                        Please note: We recommend not to use CKE templates, because the WebsiteBaker template
                        should define the different blocks and the template. 
                        
Furthermore this files and some other configurations (we recommend you shouldn't change unless you really know what you are doing) are stored in:
_yourwbinstallation_/modules/ckeditor/include.php


################################################


# Question: #

Why back to the roots and not different folders anymore?

# Answer:   #

All four configuration files (wb_ckconfig.js, editor.css, editor.styles.js, editor.templates.js) are available in 
_yourwbinstallation_/modules/ckeditor/wb_config

The wb_config is loaded and recognized by the CKEditor module unless you copy the folder "wb_config."!

As an example you have two possibilities to call the configuration files
    
    1) copy the wb_config to the folder _yourwbinstallation_/templates and all changes in config files are for all templates
    2) copy the wb_config  to your _yourdefaulttemplate_: _yourwbinstallation_/templates/_yourdefaulttemplate_ 
       rename folder wb_config to editor, your changes are only for _yourdefaulttemplate_
    
    Change the configfiles with your FTP-Browser / AddonFileEditor / ... 

You should never change the files in _yourwbinstallation_/modules/ckeditor/wb_config, as they are overwritten with any update of CKEditor module and / or WebsiteBaker! 
You never have any problems when upgrading CKEditor module to another version.


################################################


# Question: #

How is the workflow of reading CKEditor files?

# Answer:   #

Note: The workflow is defined inside _yourwbinstallation_/modules/ckeditor/include.php

1) is called as first if available, 2) is the next one, and so on.

search order for CKEditor files 

editor.css

    1) _yourwbinstallation_/templates/_yourdefaulttemplate_/editor.css
    2) _yourwbinstallation_/templates/_yourdefaulttemplate_/css/editor.css
    3) _yourwbinstallation_/templates/_yourdefaulttemplate_/editor/editor.css
    4) _yourwbinstallation_/templates/wb_config/editor.css
    5) _yourwbinstallation_/modules/ckeditor/wb_config/editor.css

wb_ckconfig.js,editor.styles.js,editor.templates.js    
    
    1) _yourwbinstallation_/templates/_yourdefaulttemplate_/wb_ckconfig.js
    2) _yourwbinstallation_/templates/_yourdefaulttemplate_/js/wb_ckconfig.js
    3) _yourwbinstallation_/templates/_yourdefaulttemplate_/editor/wb_ckconfig.js
    4) _yourwbinstallation_/templates/wb_config/wb_ckconfig.js
    5) _yourwbinstallation_/modules/ckeditor/wb_config/wb_ckconfig.js

etc...    
    
