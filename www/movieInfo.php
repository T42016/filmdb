<?php

/**
 *	Define your API key below. To obtain one, visit 
 *	http://www.themoviedb.org/account/signup
 */

$api_key = 'c2c73ebd1e25cbc29cf61158c04ad78a';


// If the request was not issued by AJAX, or
// The search term is missing, exit:

if(!$_SERVER["HTTP_X_REQUESTED_WITH"] || !$_GET['term']){
	exit;
}

include 'tmdbAPI/TMDb.php';
$tmdb = new TMDBv3($api_key);


// Send a search API request to TMDb,
// and parse the returned JSON data:

$orgName = $_GET['term'];

$str = "";
$bits = explode('.', $orgName);
foreach ($bits as $b) 
{
	if($b == '720p' || $b == '1080p' || $b == 'BluRay' || $b == 'DVD9' || $b == 'DC' || $b == 'REPACK' || $b == 'PROPER' || $b == 'LIMITED' || $b == "LiMiTED"
		|| $b == 'HDDVD' || $b=='DVD5' || $b == 'HD-DVD' || $b == 'Blu-ray' || $b == 'DirCut' || $b == 'HDDVDRiP' || $b == 'UNRATED')
		break;
	$str .= $b.' ';
}

$data = $tmdb->searchMovie($str);
//$data = $tmdb->movieDetail('578');
$response = array();
$i=0;

foreach($data['results'] as $movie){
	
	// Only movies existing in the IMDB catalog (and are not adult) are shown
	if(!$movie['id'] || $movie['adult']) continue;
	if($i >= 8) break;
	
	
	// The jQuery autocomplete widget shows the label in the drop down,
	// and adds the value property to the text box.
	
	$response[$i]['value'] = $orgName;
	$response[$i]['id'] = $movie['id'];
	$response[$i]['label'] = $movie['original_title'] . ' <small>(' . date('Y',strtotime($movie['release_date'])).')</small>';
	$i++;
}

// Transforming the response as a JSON object:
echo json_encode($response);

?>