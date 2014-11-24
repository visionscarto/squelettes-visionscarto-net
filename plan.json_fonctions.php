<?php

function get_tags($txt) {
 if (!preg_match_all("/"._REG_HASH."/ui", $txt, $r)) {
   return array();
 }
 return array_map('_tag_cleanup',$r[2]);
}


function _tag_cleanup($tag) {
 return str_replace('_', ' ', translitteration(mb_strtolower(preg_replace(',^#,S', '', $tag), 'UTF-8')));
}
