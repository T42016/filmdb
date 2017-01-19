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
$data = $tmdb->movieDetail($orgName);
$response = array();
$response['imdb'] = $data['imdb_id'];

// Transforming the response as a JSON object:
echo json_encode($response);

?>