<?php

function link_to_geo($country, $state = null, $city = null)
{
  sfLoader::loadHelpers(array('Tag', 'Url'));
  $text = $country;
  $location = array('country' => urlencode($country) );
  if ($state)
  {
    $location['state'] = $state;
    $text              = $state;
  }
  
  if ($city)
  {
    $location['city'] = $city;
    $text             = $city;
  }
  
  $qsa = strtolower(http_build_query($location, null, '&'));
  return link_to($text,'@locations_in?'.$qsa);
  
}
function logo_tag($options = array())
{
	$options = _parse_attributes($options);
	return link_to(image_tag('reviewsby.us.logo.small.png', 'alt=reviewsby.us'), '@homepage', 'id=logo_small size=170x35');
}

function image_for_item(MenuItem $item, $options = array(), Profile $p = null)
{
	$options = _parse_attributes($options);
	$img = $item->getVisibleImage($p);
	if ($img instanceof MenuItemImage) 
	{
		$hash = $img->getMd5Sum();
		
		$absolute = false;
		if (isset($options['absolute']))
		{
		  $absolute = true;
		}
		
		$img_options = array('src'=>url_for('@menu_item_image?hash=' . $img->getMd5sum(),$absolute),'alt' => 'Picture of ' . $item->getName() );
		if (isset($options['longest_side'])) 
		{
			list($h, $w) = $img->getScaledDimensions($options);
			$img_options['height'] = $h;
			$img_options['width']  = $w;
			unset($options['longest_side']);
		}
		$img_options = array_merge($img_options, $options);
		return tag('img', $img_options);
	} 
	else
	{
	  if (isset($options['longest_side'])) 
		{
			$img_options['height'] = min($options['longest_side'], 100);
			$img_options['width']  = min($options['longest_side'], 100);
			unset($options['longest_side']);
		}
		$img_options = array_merge($img_options, $options);
		
		return image_tag('g2/logo/bowl_100.gif', $img_options);
	}
}

function markdown_enabled_link()
{
	return '(' . link_to('Markdown', 'http://daringfireball.net/projects/markdown/syntax') . ' enabled)';
}

function link_to_location(Location $l, $text = null)
{
	return link_to(_or($text,$l->__toString()), url_for_location($l));
}

function url_for_location(Location $l)
{
	return '@location?restaurant=' . $l->getRestaurant()->getStrippedTitle() . '&location=' . $l->getStrippedTitle();
}

function link_to_restaurant(Restaurant $r, $absolute = false)
{
	return link_to($r->__toString(), '@restaurant?stripped_title=' . $r->getStrippedTitle(), array('absolute_url' => $absolute));
}

function link_to_menuitem(MenuItem $i, $options = array(), $text = null)
{
  if (!$text)
  {
    $text = $i->getName();
  }
	return link_to($text, url_for_menuitem($i), $options);
}

function url_for_menuitem(MenuItem $i)
{
	return UrlHelper::url_for_menuitem($i);
}

function link_to_menuitem_edit(MenuItem $i, $text)
{
	return link_to($text, url_for_menuitem_edit($i));
}


function url_for_menuitem_edit(MenuItem $i)
{
	return '@menu_item_edit?stripped_title='.$i->getUrl().'&restaurant='.$i->getRestaurant()->getStrippedTitle();
}

function format_phone($phone)
{

	return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1.$2.$3", $phone); 

}

function is_associative($array)
{
	if (!is_array($array) || empty($array))
	return false;

	$keys = array_keys($array);
	return array_keys($keys) !== $keys;
}

function link_to_user($mixed) 
{
	if ($mixed instanceof Profile) {
		$user = $mixed;
	} 
	else if ($mixed instanceof myUser) {
		$user = $mixed->getProfile();
	}
	else {
		// user isn't there
		return "Anonymous Diner";
	}

	return link_to($user->__toString(), '@profile?username='. $user->getUsernameForURL());
}

/* TLA helpers */


function tla_ads() {

	// Number of seconds before connection to XML times out
	// (This can be left the way it is)
	$CONNECTION_TIMEOUT = 10;

	// Local file to store XML
	// This file MUST be writable by web server
	// You should create a blank file and CHMOD it to 666
	$LOCAL_XML_FILENAME = sfConfig::get('sf_web_dir')."/local_63147.xml";

	if( !file_exists($LOCAL_XML_FILENAME) ) die("Text Link Ads script error: $LOCAL_XML_FILENAME does not exist. Please create a blank file named $LOCAL_XML_FILENAME.");
	if( !is_writable($LOCAL_XML_FILENAME) ) die("Text Link Ads script error: $LOCAL_XML_FILENAME is not writable. Please set write permissions on $LOCAL_XML_FILENAME.");

	if( filemtime($LOCAL_XML_FILENAME) < (time() - 3600) || filesize($LOCAL_XML_FILENAME) < 20) {
		$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";
		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
		tla_updateLocalXML("http://www.text-link-ads.com/xml.php?inventory_key=0OUOOGKS8JD5EKG338P5&referer=" . urlencode($request_uri) .  "&user_agent=" . urlencode($user_agent), $LOCAL_XML_FILENAME, $CONNECTION_TIMEOUT);
	}

	$xml = tla_getLocalXML($LOCAL_XML_FILENAME);

	$arr_xml = tla_decodeXML($xml);

	if ( is_array($arr_xml) ) {
		echo "\n<ul style=\"list-style: none; margin: 0; overflow: hidden; background-color: #222; \">\n";
		echo "<li style=\"float: left;width: 10%\">&lt;advertisement&gt;</li>";
		for ($i = 0; $i < count($arr_xml['URL']); $i++) {
			echo "<li style=\"width: 20%; margin: 0; clear: none; display: inline; float: left; padding: 0;\"><span style=\"font-size: 12px; display: block; width: 100%; color: #000000; margin: 0; padding: 3px;\">".$arr_xml['BeforeText'][$i]." <a style=\"color: #0066CC; font-size: 12px;\" href=\"".$arr_xml['URL'][$i]."\">".$arr_xml['Text'][$i]."</a> ".$arr_xml['AfterText'][$i]."</span></li>\n";
		}
		echo "<li style=\"float: right;width: 10%\">&lt;/advertisement&gt;</li>";

		echo "</ul>";
	}

}

function tla_updateLocalXML($url, $file, $time_out)
{
	if($handle = fopen($file, "a")){
		fwrite($handle, "\n");
		fclose($handle);
	}
	if($xml = file_get_contents_tla($url, $time_out)) {
		$xml = substr($xml, strpos($xml,'<?'));

		if ($handle = fopen($file, "w")) {
			fwrite($handle, $xml);
			fclose($handle);
		}
	}
}

function tla_getLocalXML($file)
{
	$contents = "";
	if($handle = fopen($file, "r")){
		$contents = fread($handle, filesize($file)+1);
		fclose($handle);
	}
	return $contents;
}

function file_get_contents_tla($url, $time_out)
{
	$result = "";
	$url = parse_url($url);

	if ($handle = @fsockopen ($url["host"], 80)) {
		if(function_exists("socket_set_timeout")) {
			socket_set_timeout($handle,$time_out,0);
		}
		else if(function_exists("stream_set_timeout")) {
			stream_set_timeout($handle,$time_out,0);
		}

		fwrite ($handle, "GET $url[path]?$url[query] HTTP/1.0\r\nHost: $url[host]\r\nConnection: Close\r\n\r\n");
		while (!feof($handle)) {
			$result .= @fread($handle, 40960);
		}
		fclose($handle);
	}

	return $result;
}

function tla_decodeXML($xmlstg)
{

	if( !function_exists('html_entity_decode') ){
		function html_entity_decode($string)
		{
			// replace numeric entities
			$string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\1"))', $string);
			$string = preg_replace('~&#([0-9]+);~e', 'chr(\1)', $string);
			// replace literal entities
			$trans_tbl = get_html_translation_table(HTML_ENTITIES);
			$trans_tbl = array_flip($trans_tbl);
			return strtr($string, $trans_tbl);
		}
	}

	$out = "";
	$retarr = "";

	preg_match_all ("/<(.*?)>(.*?)</", $xmlstg, $out, PREG_SET_ORDER);
	$search_ar = array('&#60;', '&#62;', '&#34;');
	$replace_ar = array('<', '>', '"');
	$n = 0;
	while (isset($out[$n]))
	{
		$retarr[$out[$n][1]][] = str_replace($search_ar, $replace_ar,html_entity_decode(strip_tags($out[$n][0])));
		$n++;
	}
	return $retarr;
}

function _or($a,$b)
{
	return empty($a) ? $b:$a;
}
function rss_link_to($url, $title = null)
{
	$r = link_to(image_tag('feedicon'), $url); 
	if ($title) {
		$r .= link_to($title, $url);
	}
	return $r;
}

function toggle_element($element,$text_element)
{
	$js = "
	$('$text_element').innerHTML = ($('$text_element').innerHTML == 'show') ? 'hide' : 'show';
	";
	
	return visual_effect('toggle_blind', $element) . $js;
}
