/*
Templates definitions created by Sgt.Nops and Yetiie
Copyright (c) 2010, Sgt.Nops  and Yetiie
Released under same license as Websitebaker:  GPLv2

All Templates should be functional down to IE5 (thats why the sometimes have extra container Divs)
Exept the 2 and 3 column templates whith only divs(made by Yetiie) the are only tested on a few modern browsers.
*/

// Register a templates definition set named "default".
CKEDITOR.addTemplates( 'default',
{
	// The name of sub folder which hold the shortcut preview images of the
	// templates.
	imagesPath : CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + '/templates/images/' ),

	// The templates definitions.
	templates :
		[

			{
				title: 'End element float DIV',
				image: 'template9.gif',
				description: 'Stops text from floating around an element and creates a new paragraph',
				html:
					'<div style="clear:both; height:1px; overflow:hidden;">&nbsp</div>' +
					'<p>...</p>'
			},
			{
				title: 'End element float BR',
				image: 'template9.gif',
				description: 'Creates a newline, thats stops text from floating around an element.',
				html:
					'<br style="clear:both;" />...'
			},
			{
				title: 'Image floating left',
				image: 'template1.gif',
				description: 'Image floating left whith headline and subtitle. The Box adapts to the size of the Image.',
				html:
					'<div>' +
						'<div style="border:1px solid #ccc; float: left; margin-right:10px; margin-bottom:5px;padding:5px; background-color:#ffffff; text-align:center; font-size:80%;">' +
							'Headline<br />' +
							'<img src="/modules/ckeditor/ckeditor/plugins/templates/templates/images/no_image.jpg" border="0" align="center"/>' +
							'<br />Subtitle' +
						'</div>' +
						'<p>' +
							'Text' +
						'</p>' +
					'<div style="clear:left; height:1px; overflow:hidden;">&nbsp</div>' +
					'</div>'+
					'<p>...</p>'
			},
			{
				title: 'Image floating right',
				image: 'template4.gif',
				description: 'Image floating right whith headline and subtitle. The Box adapts to the size of the Image.',
				html:
					'<div>' +
						'<div style="border:1px solid #ccc; float: right; margin-left:10px; margin-bottom:5px;padding:5px; background-color:#ffffff; text-align:center; font-size:80%;">' +
							'Headline<br />' +
							'<img src="/modules/ckeditor/ckeditor/plugins/templates/templates/images/no_image.jpg" border="0" align="center"/>' +
							'<br />Subtitle' +
						'</div>' +
						'<p>' +
							'Text' +
						'</p>' +
					'<div style="clear:right; height:1px; overflow:hidden;">&nbsp</div>' +
					'</div>'+
					'<p>...</p>'
			},
			{
				title: '2 column area',
				image: 'template2.gif',
				description: '2 column area whith no Tables just Div. Width is not flexible and content floats over box borders if its to big. One small disadvantage is that it does look a bit crowded if you activate boxview in editor',
				html:
					'<div style="float: left; width: 47%;">' +
						'<p>Text1</p>' +
					'</div>' +
					'<div style="float: left; width: 47%; margin-left: 5.9%;">' +
					'	<p>Text2</p>' +
					'</div>' +
					'<div style="clear:both; height:1px; overflow:hidden;">&nbsp</div>' +
					'<p>...</p>'
			},
			{
				title: '2 column template',
				image: 'template2.gif',
				description: '2 column template whith table. Tries to adapt if content is to big for one column even though this adaption has its limits.',
				html:
					'<table cellspacing="0" cellpadding="0" style="width:100%" border="0">' +
						'<tr>' +
							'<td style="width:49%" valign="top">' +
								'<h3>Headline 1</h3>' +
							'</td>' +
							'<td style="width:2%"></td>' +
							'<td style="width:49%"valign="top" >' +
								'<h3>Headline 2</h3>' +
							'</td>' +
						'</tr>' +
						'<tr>' +
							'<td valign="top">' +
								'Text 1' +
							'</td>' +
							'<td></td>' +
							'<td valign="top">' +
								'Text 2' +
							'</td>' +
						'</tr>' +
					'</table>' +
					'<p>...</p>'
			},

			{
				title: '3 column area',
				image: 'template13.gif',
				description: '3 column area no tables only DIV. Width is not flexible and content floats over box borders if its to big. One small disadvantage is that it does look a bit crowded if you activate boxview in editor.',
				html:
					'<div style="float: left; width: 31%;">' +
						'<p>Text1</p>' +
					'</div>' +
					'<div style="float: left; width: 31%; margin-left: 3.4%">' +
						'<p>Text2</p>' +
					'</div>' +
					'<div style="float: left; width: 31%; margin-left: 3.4%;">' +
						'<p>Text3</p>' +
					'</div>' +
					'<div style="clear:both; height:1px; overflow:hidden;">&nbsp</div>' +
					'<p>...</p>'
			},
			{
				title: 'Infocolumn right',
				image: 'template15.gif',
				description: 'Info column on right side, width is 2/3 to 1/3. Width is fixed and content floats over box borders if its to big. One small disadvantage is that it does look a bit crowded if you activate boxview in editor.',
				html:
					'<div style="float: left; width:60%;">' +
						'<p>left</p>' +
					'</div>' +
					'<div style="float: left; width: 35%; margin-left: 4.9%; font-size: 0.8em;">' +
						'<p>right</p>' +
					'</div>' +
					'<div style="clear:both; height:1px; overflow:hidden;">&nbsp</div>' +
					'<p>...</p>'
			},
			{
				title: 'Infocolumn left',
				image: 'template16.gif',
				description: 'Info column on left side, width is 2/3 to 1/3. Width is fixed and content floats over box borders if its to big. One small disadvantage is that it does look a bit crowded if you activate boxview in editor.',
				html:
					'<div style="float: left; width:35%;">' +
						'<p>left</p>' +
					'</div>' +
					'<div style="float: left; width: 60%; margin-left: 4.9%; font-size: 0.8em;">' +
						'<p>right</p>' +
					'</div>' +
					'<div style="clear:both; height:1px; overflow:hidden;">&nbsp</div>' +
					'<p>...</p>'
			},
			{
				title: 'Text whith table left',
				image: 'template5.gif',
				description: 'Text whith table floating left. The table got a headline and a subtitle like the image templates. Table properties can be set via normal table dialog. Don`t use percentage setting for width.',
				html:
					'<div>' +
						'<div style="border:1px solid #ccc; float: left; margin-right:10px; margin-bottom:5px;padding:5px; background-color:#ffffff; text-align:center; font-size:80%;">' +
							'Headline' +
							'<table style="width:250px;" cellspacing="0" cellpadding="0" border="1">' +
								'<tbody>' +
									'<tr>' +
										'<td>...</td>' +
										'<td>...</td>' +
										'<td>...</td>' +
									'</tr>' +
									'<tr>' +
										'<td>...</td>' +
										'<td>...</td>' +
										'<td>...</td>' +
									'</tr>' +
									'<tr>' +
										'<td>...</td>' +
										'<td>...</td>' +
										'<td>...</td>' +
									'</tr>' +
								'</tbody>' +
							'</table>' +
							'Text' +
						'</div>' +
						'<div style="clear:left; height:1px; overflow:hidden;">&nbsp</div>' +
					'</div>'+
					'<p>...</p>'
			},
			{
				title: 'Text whith table right',
				image: 'template3.gif',
				description: 'Text whith table floating right. The table got a headline and a subtitle like the image templates. Table properties can be set via normal table dialog. Don`t use percentage setting for width.',
				html:
					'<div>' +
						'<div style="border:1px solid #ccc; float: right; margin-left:10px; margin-bottom:5px;padding:5px; background-color:#ffffff; text-align:center; font-size:80%;">' +
							'Headline' +
							'<table style="width:250px;" cellspacing="0" cellpadding="0" border="1">' +
								'<tbody>' +
									'<tr>' +
										'<td>...</td>' +
										'<td>...</td>' +
										'<td>...</td>' +
									'</tr>' +
									'<tr>' +
										'<td>...</td>' +
										'<td>...</td>' +
										'<td>...</td>' +
									'</tr>' +
									'<tr>' +
										'<td>...</td>' +
										'<td>...</td>' +
										'<td>...</td>' +
									'</tr>' +
								'</tbody>' +
							'</table>' +
							'Text' +
						'</div>' +
						'<div style="clear:right; height:1px; overflow:hidden;">&nbsp</div>' +
					'</div>'+
					'<p>...</p>'
			},
			{
				title: 'Text whith textbox left',
				image: 'template7.gif',
				description: 'Text whith textbox floating left. The textbox got a headline and a subtitle like the image templates. Box adapts to content width. If you want a defined width you can define it viea the inner or outer divbox.(Don`t use percentage setting for inner divbox).',
				html:
					'<div>' +
						'<div style="border:1px solid #ccc; float: left; margin-right:10px; margin-bottom:5px;padding:5px; background-color:#ffffff; text-align:center; font-size:80%;">' +
							'Headline' +
							'<div style="border:1px solid #ccc; padding:3px;">' +
									'<p>Textfeld<br />Inhalt</p>' +
							'</div>' +
							'Subtitle' +
						'</div>' +
						'<p>' +
							'Text' +
						'</p>' +
						'<div style="clear:left; height:1px; overflow:hidden;">&nbsp</div>' +
					'</div>'+
					'<p>...</p>'
			},
			{
				title: 'Text whith textbox right',
				image: 'template8.gif',
				description: 'Text whith textbox floating right. The textbox got a headline and a subtitle like the image templates. Box adapts to content width. If you want a defined width you can define it viea the inner or outer divbox.(Don`t use percentage setting for inner divbox).',
				html:
					'<div>' +
						'<div style="border:1px solid #ccc; float: right; margin-left:10px; margin-bottom:5px;padding:5px; background-color:#ffffff; text-align:center; font-size:80%;">' +
							'Headline' +
							'<div style="border:1px solid #ccc; padding:3px;">' +
									'<p>Textfeld<br />Inhalt</p>' +
							'</div>' +
							'Subtitle' +
						'</div>' +
						'<p>' +
							'Text' +
						'</p>' +
						'<div style="clear:right; height:1px; overflow:hidden;">&nbsp</div>' +
					'</div>'+
					'<p>...</p>'
			},
			{
				title: 'Centered table for misc. content.',
				image: 'template11.gif',
				description: 'A centered 1 column table. Free to use for miscellaneous content that needs a centered box that scales whith the content size and still stay centered.',
				html:
					'<div style="text-align: center;">' +
						'<table align="center" style="width: 20%; margin: 0pt auto; background:#ffffff;" border="1" cellpadding="5" cellspacing="0" style="">' +
							'<tbody>' +
							'<tr><td>Content</td></tr>' +
							'</tbody>' +
						'</table>' +
					'</div>'+
					'<p>...</p>' 
			},
			{
				title: 'Centered Image',
				image: 'template14.gif',
				description: 'A centered table whith an image including headline and subtitle. Table grows whith image size so its easy to handle for user.',
				html:
					'<div style="text-align: center;">' +
						'<table align="center" style="width: 20%; margin: 0pt auto; background:#ffffff;" border="1" cellpadding="5" cellspacing="0" style="">' +
							'<tbody>' +
							'<tr><td style="font-size:80%;" align="center">' +
								'Headline<br />' +
								'<img src="/modules/ckeditor/ckeditor/plugins/templates/templates/images/no_image.jpg" border="0" align="center"/>' +
								'<br />Subtitle' +
							'</td></tr>' +
							'</tbody>' +
						'</table>' +
					'</div>'+
					'<p>...</p>' 			},
			{
				title: 'Content Div centered.',
				image: 'template12.gif',
				description: 'A centered DIV box whith headline and subtitle. Size needs to be defined via the outer of the 2 centered boxes. This makes it a bit complicated to use whith Images.',
				html:
					'<div style="text-align: center;">' +
						'<div style="border:1px solid #ccc; margin: 0pt auto;padding:5px; background:#ffffff;width:50%;text-align:center; font-size:80%;" >' +
							'Headline' +
							'<div style="border:1px solid #ccc; padding:3px;">Dies ist ein Textfeld.<br />blablabla</div>' +
							'Subtitle' +
						'</div>' +
					'</div>'+
					'<p>...</p>'
			}
		]
});
