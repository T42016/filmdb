<?php

function getIcon($format)
{
	switch($format)
	{
		//SVCD
		case 8:
			return "<img src='img/icon/icon_disc.gif' alt='' title='SVCD'/>";	
			break;
		//DivX
		case 9:
			return "<img src='img/icon/icon_disc.gif' alt='' title='DivX'/>";	
			break;
		//DVD
		case 10:
			return "<img src='img/icon/icon_dvd.gif' alt='' title='DVD'/>";
			break;
		//DVD-r
		case 11:
			return "<img src='img/icon/icon_dvd.gif' alt='' title='DVD-r'/>";
			break;
		//HD
		case 16:
			return "<img src='img/icon/icon_hd.gif' alt='' title='HD' />";
			break;
		//unknowns
		default:
			return "<img src='img/icon/icon_disc.gif' alt='' title='unknown'/>";	
			break;
	}
}
function getFormat($format)
{
	switch($format)
	{
		//SVCD
		case 8:
			return "SVCD";	
			break;
		//DivX
		case 9:
			return "DivX";	
			break;
		//DVD
		case 10:
			return "DVD";
			break;
		//DVD-r
		case 11:
			return "DVD-r";
			break;
		//HD
		case 16:
			return "HD";
			break;
		//unknowns
		default:
			return "???";	
			break;
	}
}

function getToolTipText($line)
{
	if($line['name'] != '')
		$title = trim($line['name']);
	else
		$title = trim($line['title']);
	$title = str_replace(' ', '&nbsp;', $title);
	$genre = str_replace(' ', '&nbsp;', $line['genre']);
	$title = addslashes($title);
	$text = "";
	
	$rows = 6;
	if($line['added'] != '0000-00-00 00:00:00')
		$rows=7;
		
	/*if(posterExists($line['imdb']))
		$text .= '<div class="toolleft"><img src="'.getPoster($line['imdb']).'" alt="" /></div>';
	$text .= '<div class="toolright"><table><tr>';*/
	
	$text .= '<table><tr><td rowspan="'.$rows.'">';
	if(posterExists($line['imdb']))
		$text .= '<img src="'.getPoster($line['imdb']).'" alt="" width="96" height="140"/>';
	$text .= '</td>';
	
	$text .= '<td colspan="2"><b>'.$title.'&nbsp;('.$line['year'].')</b></td></tr>';
	$text .= '<tr><td>&nbsp;&nbsp;Lagrad</td><td>&nbsp;&nbsp;&nbsp;<i>'.$line['places_name'].'</i></td></tr>';
	$text .= '<tr><td>&nbsp;&nbsp;Ägare</td><td>&nbsp;&nbsp;&nbsp;<i>'.$line['user_name'].'</i></td></tr>';
	if($line['added'] != '0000-00-00 00:00:00')
		$text .= '<tr><td>&nbsp;&nbsp;Skapad</td><td>&nbsp;&nbsp;&nbsp;<i>'.substr($line['added'], 0, 10).'</i></td></tr>';
	$text .= '<tr><td>&nbsp;&nbsp;Imdb</td><td>&nbsp;&nbsp;&nbsp;<i>'.$line['rating'].' ('.$line['votes'].')'.'</i></td></tr>';
	$text .= '<tr><td>&nbsp;&nbsp;Format</td><td>&nbsp;&nbsp;&nbsp;<i>'.getFormat($line['format']).'</i></td></tr>';
	$text .= '<tr><td>&nbsp;&nbsp;Genre</td><td>&nbsp;&nbsp;&nbsp;<i>'.$genre.'</i></td></tr>';
	$text .= '</table>';

		
	return $text;
}

function posterExists($imdb)
{
	return file_exists('cache/'.$imdb.'.jpg');
}

function getPoster($imdb)
{
	if(file_exists('cache/'.$imdb.'.jpg'))
		return 'cache/'.$imdb.'.jpg';
	else
		return 'img/noposter.jpg';
}

function cachePoster($imdb, $poster)
{
	if(posterExists($imdb))
		return;
	
	file_put_contents('cache/'.$imdb.'.jpg', file_get_contents($poster));
}

function getImdbLink($str)
{
	if($str == '')
		return '';
	
	if( strpos($str, 'tt') === false)
			$str = 'tt'.$str;		
	$url  = "http://www.imdb.com/title/$str/";
	
	return "<a href='$url' target='new'><img src='img/icon/icon_imdb.gif' alt='imdb' /></a>";
}

function getQueryUrl($base, $param, $val)
{	
	$str = $_SERVER['QUERY_STRING'];
	
	if( $str == '')
	{
		return $base.'?'.$param.'='.$val;
	}
		
	if(strpos($str, $param.'=') === false)
	{
		return "$base?$str&$param=$val";
	}
	//replace existing
	else
	{
		$parts = explode("&", $str);
        $newParts = "";
        foreach ($parts as $v) 
		{
            if(strpos($v, $param.'=') === false)
				$newParts .= $v.'&';
			else
				$newParts .= $param.'='.$val.'&';
        } 
		return rtrim("$base?$newParts", '&');
	}
}

?>