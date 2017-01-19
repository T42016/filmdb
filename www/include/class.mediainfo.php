<?php
class MediaInfo
{
	public $info;

	function __construct($str = null)
	{
		if(!is_null($str))
				$this->autodetect($str);
	}

	function autodetect($str)
	{
		// Attempt to cleanup $str in case it's a filename ;-)
		$str = pathinfo($str, PATHINFO_FILENAME);
		$str = $this->normalize($str);

		// Is it a movie or tv show?
		if(preg_match('/s[0-9][0-9]?.?e[0-9][0-9]?/i', $str) == 1)
				$this->info = $this->getEpisodeInfo($str);
		else
				$this->info = $this->getMovieInfo($str);

		return $this->info;
	}

	function getEpisodeInfo($str)
	{
		$arr = array();
		$arr['kind'] = 'tv';
		return $arr;
	}

	function getMovieInfoById($str)
	{
		if( strpos($str, 'tt') === false)
			$str = 'tt'.$str;
			
		$url  = "http://www.imdb.com/title/$str/";
		$html = $this->geturl($url);

		return $this->fillArray($html);
	}
	
	function getMovieInfo($str)
	{
		//$str  = str_ireplace('the ', '', $str);
		//$url  = "http://www.google.com/search?hl=en&q=imdb+" . urlencode($str) . "&btnI=I%27m+Feeling+Lucky";
		$url = "http://www.imdb.com/search/title?title=".urlencode($str);
		$html = $this->geturl($url);

		$url = $this->match('/<td class="number">1\.<\/td>\W*<td class="image">\W*<a href="(.*?)"/ms', $html, 1);
		//echo "http://imdb.com".$url;
		//exit;
		$html = $this->geturl("www.imdb.com".$url);
		//if(stripos($html, "302 Moved") !== false)
			//$html = $this->geturl($this->match('/HREF="(.*?)"/ms', $html, 1));

		return $this->fillArray($html);
	}
	
	function fillArray($html)
	{
		$arr = array();
		$arr['kind'] = 'movie';
		$arr['id'] = $this->match('/poster.*?(tt[0-9]+)/ms', $html, 1);
		$arr['id'] = substr($arr['id'], 2);
		
		$arr['title'] = $this->match('/<span class="title-extra" itemprop="name">(.*?)<i>/ms', $html, 1);
		$arr['title'] = str_replace("\"", '', trim($arr['title']));
		
		if($arr['title'] == '')
			$arr['title'] = $this->match('/<span class="itemprop" itemprop="name">(.*?)<\/span>/ms', $html, 1);
		//$arr['title'] = preg_replace('/\([0-9]+\)/', '', $arr['title']);
		//if($arr['title'] == '')
		//	$arr['title'] = $this->match('/<h1 class="header" itemprop="name">\n(.*?)\n/ms', $html, 1);
		//$arr['title'] = trim($arr['title']);
		
		//$arr['rating'] = $this->match('/([0-9]\.[0-9])\/10/ms', $html, 1);
		//$arr['rating'] = $this->match('/<span class="rating-rating">(.*?)<span>\/10/ms', $html, 1);
		$arr['rating'] = $this->match('/<span itemprop="ratingValue">(.*?)<\/span>/ms', $html, 1);
		$arr['votes'] = $this->match('/([0-9,]+) votes/ms', $html, 1);
		$arr['votes'] = str_replace(',', '', $arr['votes']);
		$arr['director'] = trim(strip_tags($this->match('/Director(s):(.*?)<\/a>/ms', $html, 1)));
		//$arr['release_date'] = $this->match('/([0-9][0-9]? (January|February|March|April|May|June|July|August|September|October|November|December) (19|20)[0-9][0-9])/ms', $html, 1);
		$arr['release_date'] = $this->match('/href="\/year\/([0-9]{4})\//ms', $html, 1);
		//$length = strlen($arr['release_date']);
		//$arr['year'] = substr($arr['release_date'], $length-4);
		$arr['year'] = $arr['release_date'];
		
		$arr['plot'] = trim(strip_tags($this->match('/Plot:(.*?)<a/ms', $html, 1)));
		//$arr['genres'] = $this->match_all('/Sections\/Genres\/(.*?)[\/">]/ms', $html, 1);
		//$arr['genres'] = array_unique($arr['genres']);
		$arr['genres'] = trim(strip_tags($this->match('/&nbsp;-&nbsp;(.*?)&nbsp;-&nbsp;/ms', $html, 1)));
		$arr['genres'] = str_replace(array("\r\n", "\n", "\r"), '', $arr['genres']);
		//$arr['poster'] = $this->match('/(http:\/\/ia.media-imdb.com\/images.*?).jpg" \/><\/a>/ms', $html, 1);
		//$tmpposter = $this->match('/id="img_primary">([^>]*>[^>]*)/', $html, 1);
		$tmpposter = $this->match('/div class="image">(.*?)itemprop="image"/s', $html, 1);
		$arr['poster'] = $this->match('/src="(.*)"/',$tmpposter, 1);
		//$arr['poster'] = $this->match('/<img src="(.*)"\n .* height/',$html, 1);
		
		$arr['cast'] = array();
		foreach($this->match_all('/class="nm">(.*?\.\.\..*?)<\/tr>/ms', $html, 1) as $m)
		{
				list($actor, $character) = explode('...', strip_tags($m));
				$arr['cast'][trim($actor)] = trim($character);
		}
		
		return $arr;
	}

	// ****************************************************************

	function normalize($str)
	{
		$str = str_replace('_', ' ', $str);
		$str = str_replace('.', ' ', $str);
		$str = preg_replace('/ +/', ' ', $str);
		return $str;
	}

	function geturl($url, $username = null, $password = null)
	{
		$ch = curl_init();
		if(!is_null($username) && !is_null($password))
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' .  base64_encode("$username:$password")));
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
	}

	function match_all($regex, $str, $i = 0)
	{
		if(preg_match_all($regex, $str, $matches) === false)
				return false;
		else
				return $matches[$i];

	}

	function match($regex, $str, $i = 0)
	{
		if(preg_match($regex, $str, $match) == 1)
				return $match[$i];
		else
				return false;
	}
}
?>