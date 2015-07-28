/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.dialog.add( 'wbdroplets', function( editor ) {
	
	// Function called in onShow to load selected element.
	var loadElements = function( element ) {
		this._.selectedElement = element;
		var attributeValue = element.data( 'cke-saved-name' );
		this.setValueOf( 'info', 'txtName', attributeValue || '' );
	};

	return {
		title: editor.lang.wbdroplets.wbdroplets.title,
		minWidth: 300,
		minHeight: 60,
		onOk: function() {
			var name = CKEDITOR.tools.trim( this.getValueOf( 'info', 'droplet-edit' ) );
			editor.insertHtml(name);
		},			
		
		onShow: function(){
			var dropletText = CKEDITOR.plugins.wbdroplets.getSelectedDroplet( editor );
			var dropletName = DropletSelectBox[0][1];
			var isListed = false;
			if (dropletText) {
				dropletParts = (dropletText+' ').split("?");
				for (i = 0; i < DropletSelectBox.length; i++) {
					if (dropletParts[0] === DropletSelectBox[i][1]) {
	  				dropletName = dropletParts[0];
	  				isListed = true;
	 			}
				};
			}
			this.setValueOf( 'info', 'txtName', dropletName );
			if (isListed)
				this.setValueOf( 'info', 'droplet-edit', '[['+dropletText+']]' );
		},

		contents: [
			{
			id: 'info',
			label: editor.lang.wbdroplets.wbdroplets.title,
			accessKey: 'I',
			elements: [
			{
				type: 'select',
				id: 'txtName',
				items:DropletSelectBox,
				label: editor.lang.wbdroplets.wbdroplets.name,
				required: true,
				onChange:function(){
					var e=editor.lang.wbdroplets,
					droplet=this.getValue(),
					g=window.DropletInfoBox,
					h,
					info=document.getElementById('droplet-info'),
					use=document.getElementById('droplet-usage');
					var dialog = this.getDialog();
					
					for(h in DropletInfoBox){
						if(droplet==''){
							info.innerHTML=e.empty;
							use.innerHTML='';
							dialog.setValueOf( 'info', 'droplet-edit','' );
							break;
						}
						if(droplet==DropletInfoBox[h].slice(0,1)){
							info.innerHTML=DropletInfoBox[h].slice(-1);
							use.innerHTML=DropletUsageBox[h].slice(-1);
							if ((DropletUsageBox[h].slice(-1) + '').search(/.*\[\[.*\]\].*/) > -1) {
								droplet = (DropletUsageBox[h].slice(-1) + '').match(/.*\[\[(.*)\]\].*/);
								droplet = droplet[1];
							}
							droplet = '[['+droplet+']]';
							dialog.setValueOf( 'info', 'droplet-edit', droplet );
							break;
						}
					}
				},
				onLoad: function( data ) {
					this.onChange();
				},
			},
						
			{
				type: 'text',
				id: 'droplet-edit',
				label: editor.lang.wbdroplets.editDroplet,
				required: true,
				onLoad: function() {
					this.allowOnChange = true;
				},

				validate: function() {
					var func = CKEDITOR.dialog.validate.notEmpty( editor.lang.wbdroplets.noUrl );
					return func.apply( this );
				},
			},
					
			{
				id:'infoPreview',
				type:'html',
				expand:true,
				label:'',
				widths:['100%'],
				style:'font-weight:normal;',
				html:'<div style="overflow:hidden;display:inline; width:350px; height:22px;"><div id="droplet-info" style="overflow:hidden;width:350px; white-space:normal;">Here description...</div></div>'
			},
			{
				id:'infoUsage',
				type:'html',
				expand:true,
				label:'',widths:['100%'],
				style:'font-weight:normal;',
				html:'<div style="overflow:hidden;display:inline; width:350px; height:40px;"><div id="droplet-usage" style="overflow:hidden;width:350px; white-space:normal;">Here usage...</div></div>'
			}		
			]
		}
		]
	};
});
