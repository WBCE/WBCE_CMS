###################################################
--- WBCE CKEditor FAQ: How to use customization ---
###################################################
# Question: #

What files are there to customize CKEditor for WBCE?

# Answer:   #

There are two different configuration files (editor.css and editor.styles.js). All two configuration files can be found in the template folder, please take look inside:

+ editor.css:	
The default look of the WYSIWYG textarea and the preview. 

+ editor.styles.js:
The default styles you can choose from a dropdown in the CKEditor toolbar. 

All two configuration files are also available as default files in _wbinstallation/modules/ckeditor/wb_config

IMPORTANT: Do not change any file of this folder! As they are overwritten with any update of CKEditor module and / or WBCE. You never have any problems when upgrading CKEditor module to another version.


###################################################
# Question: #

How can I customize the CKEditor for WBCE?

# Answer:   #

For customizing, copy the folder "wb_config" and insert it in one of the two following possibilities:

1) Copy wb_config to the folder _wbinstallation/templates and all changes in the configuration files are for all templates

2) Copy wb_config to the folder _wbinstallation/templates/_yourdefaulttemplate rename folder wb_config to editor, your changes are only for _yourdefaulttemplate

Change the configuration files with your FTP-Browser /... 


###################################################
# Question: #

How is the workflow of reading CKEditor files?

# Answer:   #

1) Is called as first if available, 2) Is the next one, and so on!

Search order for configuration files 

editor.css

1) _wbinstallation/templates/_yourdefaulttemplate/editor.css
2) _wbinstallation/templates/_yourdefaulttemplate/css/editor.css
3) _wbinstallation/templates/_yourdefaulttemplate/editor/editor.css
4) _wbinstallation/modules/ckeditor/wb_config/editor.css

editor.styles.js

1) _wbinstallation/templates/_yourdefaulttemplate/editor.styles.js
2) _wbinstallation/templates/_yourdefaulttemplate/js/editor.styles.js
3) _wbinstallation/templates/_yourdefaulttemplate/editor/editor.styles.js
4) _wbinstallation/modules/ckeditor/wb_config/editor.styles.js