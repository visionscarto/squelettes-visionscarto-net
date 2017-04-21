<?php

#$a = debug_backtrace();
#var_dump($a);exit;
#var_dump($GLOBALS['meta']);exit;


/*
 *  Extraits de seenthis, pour gestion des tags et urls;
 *
 */
// a passer dans apres-typo
// avant le passage des CAPS auto
define (_REG_CHARS, "a-z0-9\pN\pL\pM\'‘’°\&\+–\_");
define (_REG_HASH, "(^|[\s>])(\#["._REG_CHARS."\@\.\/-]*["._REG_CHARS."])");
function pretty_hashtags($texte) {
	$texte = echappe_html($texte, '', true);
	// annuler les span class=caps sur #PNUD
	$texte = preg_replace(',\#<span class="caps">([A-Z]+)</span>,S', '#\1', $texte);
	$texte = preg_replace_callback("/"._REG_HASH."/ui", "_traiter_hash", $texte);
	return echappe_retour($texte);
}

function _traiter_hash ($regs) {
	$tag = substr($regs[2],1); // supprimer le '#'

	$url = 'tag/'.mb_strtolower($tag,'UTF-8');
	$url = urlencode_1738_plus($url);
	$tag2 = str_replace('_', '<span style="color:transparent">_</span>', $tag);
	return $le_hash = $regs[1]."<span class='lien_tag'><span class='lien_hash'>#</span><a href=\"$url\">$tag2</a></span>";
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


function doublons_auteurs($name) {
	static $vu = array();
	static $last = '';

	# deux fois de suite, c'est une de trop
	if ($name == $last) return '';
	$last = $name;

	# les suspects repetitifs
	if ($vu[$name]++) {
		switch($name) {
			case 'Philippe Rekacewicz':
				return 'Reka';
			case 'Philippe Rivière':
				return 'Fil';
			case 'Agnès Stienne':
				return 'Odilon';
		}
	}

	return $name;
}



function filtrer_rediriger_css($reg) {
	$lien = $reg[1];
	$lien = supprimer_timestamp($lien);
	
	$css = file_get_contents($lien);
	//return "<link rel='stylesheet' href='$lien'>" ;
	
	return "<style>$css</style>";
}



function mini_html($texte) {
	$texte = str_replace("application/mp4", "video/mp4", $texte);

	$texte = preg_replace(",\ +,", " ", $texte);
	$texte = preg_replace(",\n[\t|\ ]*,", "\n", $texte);
	$texte = preg_replace(",\n+,", "\n", $texte);

	$texte = preg_replace(",style='width:[0-9]+px;',", "", $texte);
	
	$texte = str_replace("<script type='text/javascript' src=''></script>", "", $texte);
	$texte = str_replace("<script type='text/javascript' src='", "<script async  type='text/javascript' src='", $texte);
	
	
	//$texte = preg_replace(",(png|jpg|css|gif|js)\?[0-9]*,", "$1", $texte);
	$texte = str_replace("max-width: 100%; height: auto;", "width: 100%; height: auto;", $texte);
	
	$texte = str_replace("<p><br class='manualbr' /></p>\n<ul", "\n<div style='display: none;'></div><ul", $texte);


	$texte = preg_replace_callback(",<link rel=\'stylesheet\' href=[\"\']([^\"\']*)[\"\'] type=\'text\/css\' \/>,", "filtrer_rediriger_css", $texte );

	
	return trim($texte);
}



function include_svg($file) {
	if (file_exists($file)) {
		$texte = file_get_contents($file);
		
		if (preg_match(",<svg(.*)\/svg>,s", $texte, $m)) {
			$texte  = "<svg".$m[1]."/svg>";
		}
		
		return $texte;
	}
}

// filtre couleur_rgba converti une mention de couleur hexadecimale
// en couleur semi_transparente rgba
// [(#COULEUR_HEX|couleur_rgba{0.5})]
	function couleur_rgba($couleur, $alpha) {
		include_spip("inc/filtres_images_lib_mini");
		$couleurs = _couleur_hex_to_dec($couleur);

		$red = $couleurs["red"];
		$green = $couleurs["green"];
		$blue = $couleurs["blue"];
		
		return "rgba($red, $green, $blue, $alpha)";
	}

function get_tags($txt) {
 if (!preg_match_all("/"._REG_HASH."/ui", $txt, $r)) {
   return false;
 }
 return array_map('_tag_cleanup',$r[2]);
}


function _tag_cleanup($tag) {
 return str_replace('_', ' ', translitteration(mb_strtolower(preg_replace(',^#,S', '', $tag), 'UTF-8')));
}


function stocker_langue($lang) {
	global $liste_langues;
	
	
	$liste_langues[$lang] ++;
}

function sortir_langues($rem) {
	global $liste_langues;
	arsort($liste_langues);

	foreach($liste_langues as $k=>$v) {
		$lang = traduire_nom_langue($k);

		$ret .= " <a class='item' href='?page=langue&lang=$k'><span class='principal'>$lang ($v)</span></a>";
	}
	return $ret;
}

function stocker_tags($liste) {
	global $liste_tags;	
	if ($liste) {
		foreach($liste as $k) {
			$liste_tags["$k"] ++;
		}
	}
	
}

function sortir_tags($rien) {
	global $liste_tags;	
	
		$max = max($liste_tags);
	
		ksort ($liste_tags);
		$lettre_precedente = "";
		foreach($liste_tags as $k=>$v) {
			$ponderation = round(4*($v/$max));
			if ($ponderation > 0) {
				$lettre = substr($k, 0, 1);
				
				if ($lettre != $lettre_precedente) $ret .= "<li class='intertitre'><span>$lettre</span></li>\n";
				$ret .= "<li><a href='tag/".str_replace(" ", "_", $k)."' class='ponderation$ponderation'>$k ($v)</a></li>\n";
				$lettre_precedente = $lettre;
			}
		}
		
		return "<ul id='liste_tags'>$ret</ul>";
	
}
