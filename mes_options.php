<?php

define('_PREVIEW_TOKEN', true);

// selection d'articles uniquement sur la page racine
define('_PB_SELECTION_RUBRIQUES_EXEC', 'rubriques');

// autoriser les <style> dans les modeles (sitemap)
define('_PROTEGE_BLOCS', ',<(html|code|cadre|frame|script|math|style)(\s[^>]*)?>(.*)</\1>,UimsS');
 
function urls_propres($i, $entite, $args='', $ancre='') {
	$a = func_get_args();
	if (is_string($a[0])
	AND preg_match(',^.*?/tag/(.*),', '/'.$a[0], $r)) {
		$is_qs = false;
		$url_redirect = false;
		$contexte = array('tag' => urldecode($r[1]));
		$entite = 'tag';


		return array($contexte, $entite, $url_redirect, $is_qs?$entite:null);
	}

	include_spip('urls/propres');
	$b = call_user_func_array('urls_propres_dist', $a);

	// probleme pour lier un article depuis une page tag
	if (is_string($b)
	AND $GLOBALS['profondeur_url'] == 1
	AND !_DIR_RACINE
	AND substr($b,0,3) == '../')
		$b = substr($b,3);

	#var_dump($a,$b);echo "<hr>";
	return $b;
}
