/* Insert Script Plugin
 *
 *
 * Copyright (c) 2008 Kevin Martin (http://synarchydesign.com/insert)
 * Licensed under the GPL license:
 * http://www.gnu.org/licenses/gpl.html
 *
 */
jQuery.insert = function(file)
{
	var data	= [];
	var data2	= [];

	if (typeof file == 'object')
	{
		data = file;
		file = data.src !== undefined ? data.src : false;
		file = file === false && data.href !== undefined ? data.href : file;
		file = file === false ? file2 : false;
	}

	if (typeof file == 'string' && file.length)
	{
		var index	= file.lastIndexOf('.');
		var index2	= file.replace('\\', '/').lastIndexOf('/') + 1;
		var ext		= file.substring(index + 1, file.length);
	}

	switch(ext)
	{
		case 'js':
			data2 = {
				elm:	'script',
				type:	'text/javascript',
				src:	file
			};
		break;

		case 'css':
			data2 = {
				elm:	'link',
				rel:	'stylesheet',
				type:	'text/css',
				href:	file
			};
		break;

		default:
			data2 = {elm: 'link'};
		break;
	}

	data2.id = 'script-' + (typeof file == 'string' && file.length ?
		file.substring(index2, index) : Math.round(Math.rand() * 100));

	for (var i in data)
	{
		data2[i] = data[i];
	}

	data	= data2;
	var tag	= document.createElement(data.elm);

	delete data.elm;

	for (i in data)
	{
		tag.setAttribute(i, data[i]);
	}

	jQuery('head').append(tag);

	return jQuery('#' + data.id);
};