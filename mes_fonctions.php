<?php

#$a = debug_backtrace();
#var_dump($a);exit;
#var_dump($GLOBALS['meta']);exit;


/*
 *  Extraits de seenthis, pour gestion des tags et urls;
 *
 */
// a passer dans apres-typo
define (_REG_CHARS, "a-z0-9\pN\pL\pM\'‘’°\&\+–\_");
define (_REG_HASH, "(\#["._REG_CHARS."\@\.\/-]*["._REG_CHARS."])");
function pretty_hashtags($texte) {
	return preg_replace_callback("/"._REG_HASH."/ui", "_traiter_hash", $texte);
}

function _traiter_hash ($regs) {
	$tag = substr($regs[0],1); // supprimer le '#'

	$url = 'tag/'.mb_strtolower($tag,'UTF-8');
	$url = urlencode_1738_plus($url);
	$tag2 = str_replace('_', '<span style="color:transparent">_</span>', $tag);
	return $le_hash = "<span class='lien_tag'>#<a href=\"$url\">$tag2</a></span>";
	$GLOBALS["num_hash"] ++;
	$GLOBALS["les_hashs"][$GLOBALS["num_hash"]] = $le_hash;
	return "XXXHASH".$GLOBALS["num_hash"]."HASHXXX";
	
}

// Transformer les caracteres utf8 d'une URL (farsi par ex) selon la RFC 1738
// Transformer aussi les caracteres de controle 00-1F, et l'espace 20
// la fonction urlencode_1738() du core ne suffit pas,
// car elle n'encode ni espaces, ni guillemets, etc.
function urlencode_1738_plus($url) {
	$uri = '';

	# nom de domaine accentué ?
	if (preg_match(',^https?://[^/]*,u', $url, $r)) {
		
	}

	$l = strlen($url);
	for ($i=0; $i < $l; $i++) {
		$u = ord($a = $url[$i]);
		if ($u <= 0x20 OR $u >= 0x7F OR in_array($a, array("'",'"','+')))
			$a = rawurlencode($a);
		// le % depend : s'il est suivi d'un code hex, ou pas
		if ($a == '%'
		AND !preg_match('/^[0-9a-f][0-9a-f]$/i', $url[$i+1].$url[$i+2]))
			$a = rawurlencode($a);
		$uri .= $a;
	}
	return quote_amp($uri);
}

