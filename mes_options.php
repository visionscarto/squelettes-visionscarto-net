<?php

define('_PREVIEW_TOKEN', true);

// selection d'articles uniquement sur la page racine
define('_PB_SELECTION_RUBRIQUES_EXEC', 'rubriques');

// autoriser les <style> dans les modeles (sitemap)
define('_PROTEGE_BLOCS', ',<(html|code|cadre|frame|script|math|style)(\s[^>]*)?>(.*)</\1>,UimsS');

/**
 * Surcharge de sanitizer_svg_dist() (safehtml) qui ne nettoie pas deux fois le même SVG
 *
 * _image_valeurs_trans() relance le nettoyage, et réécrit le fichier, chaque fois
 * qu'un SVG distant passe dans un filtre image (adaptive_images sur #TEXTE).
 * Avec des dizaines de SVG d'1 Mo (nebuleuse-fondation-gates), le calcul de la page
 * dépasse max_execution_time. On garde le md5 de chaque SVG nettoyé dans tmp/cache/
 * et on ne refait rien quand on retrouve un contenu déjà nettoyé.
 *
 * @param string $file_or_svg
 * @return bool|string
 */
function sanitizer_svg($file_or_svg) {
	include_spip('inc/svg');
	include_spip('sanitizer/svg');
	if (!$svg = svg_charger($file_or_svg)) {
		return false;
	}
	$file = ($svg === $file_or_svg) ? '' : $file_or_svg;
	$dir = sous_repertoire(_DIR_CACHE, 'svg-sanitized');

	if (!file_exists($dir . md5($GLOBALS['filtrer_javascript'] . ':' . $svg))) {
		if (!$svg = sanitizer_svg_dist($svg)) {
			return false;
		}
		ecrire_fichier($dir . md5($GLOBALS['filtrer_javascript'] . ':' . $svg), '');
		if ($file) {
			ecrire_fichier($file, $svg);
			clearstatcache();
		}
	}

	return $file ? true : $svg;
}
