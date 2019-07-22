/**
 * PHP JS Functions
 */

//http://locutus.io/php/array/array_keys/
function array_keys (input, searchValue, argStrict) { 
	var search = typeof searchValue !== 'undefined'
	var tmpArr = []
	var strict = !!argStrict
	var include = true
	var key = ''
	for (key in input) {
		if (input.hasOwnProperty(key)) {
			include = true
			if (search) {
				if (strict && input[key] !== searchValue) 
					include = false
				else if (input[key] !== searchValue) 
					include = false
			}
			if (include) 
				tmpArr[tmpArr.length] = key
		}
	}
	return tmpArr
}
//http://locutus.io/php/array/array_values/
function array_values(input) { 
	var tmpArr = []
	var key = ''
	for (key in input) {
		tmpArr[tmpArr.length] = input[key]
	}
	return tmpArr
}
//http://locutus.io/php/strings/str_replace/
function str_replace (search, replace, subject, countObj) { 
	var i = 0
	var j = 0
	var temp = ''
	var repl = ''
	var sl = 0
	var fl = 0
	var f = [].concat(search)
	var r = [].concat(replace)
	var s = subject
	var ra = Object.prototype.toString.call(r) === '[object Array]'
	var sa = Object.prototype.toString.call(s) === '[object Array]'
	s = [].concat(s)

	var $global = (typeof window !== 'undefined' ? window : global)
	$global.$locutus = $global.$locutus || {}
	var $locutus = $global.$locutus
	$locutus.php = $locutus.php || {}

	if (typeof (search) === 'object' && typeof (replace) === 'string') {
		temp = replace
		replace = []
		for (i = 0; i < search.length; i += 1) {
			replace[i] = temp
		}
		temp = ''
		r = [].concat(replace)
		ra = Object.prototype.toString.call(r) === '[object Array]'
	}

	if (typeof countObj !== 'undefined') 
		countObj.value = 0
	for (i = 0, sl = s.length; i < sl; i++) {
		if (s[i] === '') continue
		for (j = 0, fl = f.length; j < fl; j++) {
			temp = s[i] + ''
			repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0]
			s[i] = (temp).split(f[j]).join(repl)
			if (typeof countObj !== 'undefined') 
				countObj.value += ((temp.split(f[j])).length - 1)
		}
	}
	return sa ? s : s[0]
}

function replace_placeholders (aPlaceholders, sContent) { 
	return str_replace(array_keys(aPlaceholders), array_values(aPlaceholders), sContent)
}