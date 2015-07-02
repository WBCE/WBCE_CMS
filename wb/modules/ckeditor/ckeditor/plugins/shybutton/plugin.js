/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

//(function(){CKEDITOR.plugins.add('shybutton',{lang:['en','de','nl'],init:function(a){a.addCommand('insertShybutton',{exec:function(b){b.insertHtml('&#173;');}});a.ui.addButton('Shy',{label:a.lang.shybutton.insBtn,command:'insertShybutton',icon:this.path+'images/shybutton.png'});}});})();
CKEDITOR.plugins.add('shybutton',{
	lang: 'en,de,nl',
	init:function(editor){
		editor.addCommand('insertShybutton',{exec:function(b){b.insertHtml('&#173;');}});
		editor.ui.addButton('Shy',{
			label: editor.lang.shybutton.insBtn,
			command: 'insertShybutton',
			icon: this.path+'images/shybutton.png'
		});
	}});