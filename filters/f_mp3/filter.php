<?php




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function f_mp3(&$text){

	//REPLACE FILE DOWNLOAD LINKS
	$regex = '/{(MP3=)\s*(.*?)}/i';
	$matches = array();
	preg_match_all( $regex, $text, $matches, PREG_SET_ORDER );
	foreach ($matches as $elm) {
		$elm[0] = str_replace('{', '', $elm[0]);
		$elm[0] = str_replace('}', '', $elm[0]);
		parse_str( $elm[0], $args );
		$file=@$args['MP3'];
		if ($file){
			$output = InsertMediaPlayer($file);
		} else { $output = ''; }
		$text = str_replace('', $output, $text );

	}

	return true;
}
?>