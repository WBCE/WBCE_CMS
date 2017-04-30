/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.md or http://ckeditor.com/license
*/

'use strict';

CKEDITOR.plugins.add('wbshybutton', {
	lang: 'de,en,fr,nl',
    
	init: function (editor) {
		editor.addCommand('insertwbshybutton', {exec: function(b) {b.insertHtml('&#173;'); }});
		editor.ui.addButton('wbshybutton', {
			label: editor.lang.wbshybutton.insBtn,
			command: 'insertwbshybutton',
            toolbar: 'basicstyles,50',
			icon: this.path + 'images/wbshybutton.png'
		});
	}});