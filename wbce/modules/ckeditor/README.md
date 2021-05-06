# CKEditor Standard Edition

The popular editor for WBCE CMS

***CKEditor versions higher than 4.16.0.1 require WBCE 1.5 !***

## FAQ: Customize CKEditor

### 1. Question:

Which files are available for template developer to customize CKEditor for WBCE?

#### Answer:

+ editor.css:
The default look of the WYSIWYG texttarea and preview.

+ editor.styles.js:
You can select the default styles from a drop-down menu in the CKEditor toolbar.

The configuration files might already be in the templates folder, please have a look inside.

### 2. Question:

What is the search order for these configuration files?

#### Answer:

1) Will be called first, if available, 2) Is next, and so on!

***Do not remove or delete the default files and there content!***

editor.css
```
1) _yourInstallation/templates/_yourDefaultTemplate/editor.css
2) _yourInstallation/templates/_yourDefaultTemplate/css/editor.css
3) _yourInstallation/templates/_yourDefaultTemplate/editor/editor.css
4) _yourInstallation/templates/wb_config/editor.css
5) _yourInstallation/modules/ckeditor/ckeditor/contents.css (default)
```

editor.styles.js
```
1) _yourInstallation/templates/_yourDefaultTemplate/editor.styles.js
2) _yourInstallation/templates/_yourDefaultTemplate/js/editor.styles.js
3) _yourInstallation/templates/_yourDefaultTemplate/editor/editor.styles.js
4) _yourInstallation/templates/wb_config/editor.styles.js
5) _yourInstallation/modules/ckeditor/ckeditor/styles.js (default)
```

### 3. Question:

How can I customize the CKEditor for WBCE?

#### Answer:

To customize, copy the the default files into the folder _yourInstallation/templates/_yourDefaultTemplate/ and rename it, your changes only apply to your template.
Or create a wb_config folder in _yourInstallation/templates/ and copy the default files into this folder and rename it, all changes apply to all templates.
If youe need code examples or how these files schould look like, see the files [here](https://github.com/Colinax/CKEditor/tree/archive/wb_config).