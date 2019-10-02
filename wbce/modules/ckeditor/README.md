# CKEditor Standard Edition

## The popular Editor for the WBCE CMS
Includes CKEditor 4.13.0 Standard Package and some other Plugins, CKE allows editing content and can be integrated in modules.

***CKEditor 4.12.0.1 and later require WBCE 1.4 !***


## FAQ: Customizing CKEditor

### Question:

What files are there to customize CKEditor for WBCE?

#### Answer:

+ editor.css:	
The default look of the WYSIWYG textarea and the preview.

+ editor.styles.js:
The default styles you can choose from a dropdown in the CKEditor toolbar.

The configuration files could already be in the template folder, please take a look inside:

### Question:

How can I customize the CKEditor for WBCE?

#### Answer:

For customizing, copy the files of _yourinstallation/modules/ckeditor/ckeditor/ to _yourinstallation/templates/_yourdefaulttemplate folder, your changes are only for _yourdefaulttemplate. 
Or create a wb_config folder in _yourinstallation/templates and copy the files in this folder, all changes are for all templates.
You can also see the old wb_config files from the [Archive Branch](https://github.com/Colinax/CKEditor/tree/archive/wb_config)

### Question:

How is the workflow of reading CKEditor files?

#### Answer:

1) Is called as first if available, 2) Is the next one, and so on!

Search order for configuration files 

editor.css

1) _yourinstallation/templates/_yourdefaulttemplate/editor.css
2) _yourinstallation/templates/_yourdefaulttemplate/css/editor.css
3) _yourinstallation/templates/_yourdefaulttemplate/editor/editor.css
4) _yourinstallation/templates/wb_config/editor.css
5) _yourinstallation/modules/ckeditor/ckeditor/contents.css

editor.styles.js

1) _yourinstallation/templates/_yourdefaulttemplate/editor.styles.js
2) _yourinstallation/templates/_yourdefaulttemplate/js/editor.styles.js
3) _yourinstallation/templates/_yourdefaulttemplate/editor/editor.styles.js
4) _yourinstallation/templates/wb_config/editor.styles.js
5) _yourinstallation/modules/ckeditor/ckeditor/styles.js