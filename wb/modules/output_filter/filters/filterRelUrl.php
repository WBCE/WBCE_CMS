<?php
/**
 * Convert full qualified, local URLs into relative URLs
 * @param string $content
 * @return string
 */
	function doFilterRelUrl($content) {
		$content = preg_replace_callback(
				'/((?:href|src)\s*=\s*")([^\"]*?)(")/iU',
				create_function('$matches',
				    '$retval = $matches[0]; '.
		            '$h = parse_url($matches[2], PHP_URL_HOST); '.
					'if(isset($h) && $h != \'\') { '.
					'if(stripos(WB_URL, $h) !== false) { '.
					'$a = parse_url($matches[2]); '.
					'$p = (isset($a[\'path\']) ? $a[\'path\'] : \'\'); '.
					'$q = (isset($a[\'query\']) ? \'?\'.$a[\'query\'] : \'\'); '.
					'$f = (isset($a[\'fragment\']) ? \'#\'.$a[\'fragment\'] : \'\'); '.
					'$p .= ($q.$f); '.
					'$retval = $matches[1]."/".(isset($p) ? ltrim(str_replace("//", "/", $p), "/") : "").$matches[3]; '.
					'}} return $retval;'),
		        $content);
		return $content;
	}

?>
