<?php

define('_PREVIEW_TOKEN', true);

// selection d'articles uniquement sur la page racine
define('_PB_SELECTION_RUBRIQUES_EXEC', 'rubriques');

// autoriser les <style> dans les modeles (sitemap)
define('_PROTEGE_BLOCS', ',<(html|code|cadre|frame|script|math|style)(\s[^>]*)?>(.*)</\1>,UimsS');

/**
 * Désactive le nettoyage des SVG de safehtml (remplace sanitizer_svg_dist)
 *
 * _image_valeurs_trans() relance ce nettoyage, et réécrit le fichier, chaque fois
 * qu'un SVG distant passe dans un filtre image (adaptive_images sur #TEXTE).
 * Avec des dizaines de SVG d'1 Mo (nebuleuse-fondation-gates), le calcul de la page
 * dépasse max_execution_time. Les SVG de ce site viennent de ses rédacteurs.
 *
 * @param string $file_or_svg
 * @return bool|string le SVG tel quel, ou true pour un fichier
 */
function sanitizer_svg($file_or_svg) {
	return str_contains($file_or_svg, '<svg') ? $file_or_svg : true;
}
