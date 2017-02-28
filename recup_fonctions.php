<?php


function creer_logo_article($img, $id_article) {
	copy ($img, _DIR_IMG."arton$id_article.jpg");
	
	sql_updateq (
		"spip_articles",
		array("surtitre" => ""),
		"id_article=$id_article"
	);
}

function creer_logo_rubrique($img, $id_rubrique) {
	copy ($img, _DIR_IMG."rubon$id_rubrique.jpg");
	
	sql_updateq (
		"spip_rubriques",
		array("descriptif" => ""),
		"id_rubrique=$id_rubrique"
	);
}