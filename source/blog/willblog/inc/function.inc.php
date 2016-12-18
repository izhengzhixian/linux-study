<?php
/** 
 *	File name: function.inc.php
 *	Created by: 半世烛光 QQ24203741
 *	Contact: wds00136@163.com,http://www.113344.com
 *	Last modified: 2013-04-30
 */
function get_ip() {
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
		$pos = array_search('unknown',$arr);
		if(false !== $pos) unset($arr[$pos]);
		$ip  =  trim($arr[0]);
	} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
		$ip  = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip  = $_SERVER['REMOTE_ADDR'];
	}
	$cip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
	return $cip;
}

function toHtml($value) {
	return is_array($value) ? array_map('toHtml', $value) : htmlspecialchars(stripslashes($value));
}

//HTML过滤

function UbbToHtml($content) {
	//$content = stripslashes($content);
	$str = htmlspecialchars(stripslashes($content));
	$str = preg_replace("/\ /", "&nbsp;", $str);
	$str = preg_replace("/\t/", "&nbsp;&nbsp;&nbsp;&nbsp;", $str); 
	$str = preg_replace("/\r?\n/",'<br />',$str);
	$mat = array("/(\[B\])(.*)(\[\/B\])/iU", "/(\[I\])(.*)(\[\/I\])/iU", "/(\[U\])(.*)(\[\/U\])/iU");
	$rep = array("<b>\\2</b>", "<i>\\2</i>", "<p class=\"deco\">\\2</p>"); //"<u>\\2</u>"
	$str = preg_replace($mat, $rep, $str);
	$str = preg_replace("/(\[SIZE=(.*)px\])(.*)(\[\/SIZE\])/iU", "<span style=\"font-size:\\2px;\">\\3</span>", $str);
	$str = preg_replace("/(\[FONT=(.*)\])(.*)(\[\/FONT\])/iU", "<span style=\"font-family:\\2;\">\\3</span>", $str);
	$mat = array("/(\[CENTER\])(.*)(\[\/CENTER\])/iU", "/(\[LEFT\])(.*)(\[\/LEFT\])/iU", "/(\[RIGHT\])(.*)(\[\/RIGHT\])/iU");
	$rep = array("<div style=\"text-align:center;\">\\2</div>",
				 "<div style=\"text-align:left;\">\\2</div>",
				 "<div style=\"text-align:right;\">\\2</div>");
	$str = preg_replace($mat, $rep, $str);
	$mat = array("/(\[RED\])(.*)(\[\/RED\])/iU", "/(\[BLUE\])(.*)(\[\/BLUE\])/iU", "/(\[GREEN\])(.*)(\[\/GREEN\])/iU");
	$rep = array("<span style=\"color:red;\">\\2</span>",
				 "<span style=\"color:blue;\">\\2</span>",
				 "<span style=\"color:green;\">\\2</span>");
	$str = preg_replace($mat, $rep, $str);
	$str = preg_replace("/(\[COLOR=(.*)\])(.*)(\[\/COLOR\])/iU", "<font color=\"\\2\">\\3</font>", $str);
	$str = preg_replace("/(\[QUOTE\])(.*)(\[\/QUOTE\])/iU", "<blockquote>\\2</blockquote>", $str);
	$str = preg_replace("/(\[IMG\])(.*)(\[\/IMG\])/iU", "<img src=\\2 class=\"img_left\" />", $str);
	$str = preg_replace("/(\[URL\])(.*)(\[\/URL\])/iU", "<a href=\"\\2\" target=\"new\">\\2</a>", $str);
	$str = preg_replace("/(\[URL=(.*)\])(.*)(\[\/URL\])/iU", "<a href=\"\\2\" target=\"new\">\\3</a>", $str);
	$str = preg_replace("/(\[TAG\])(.*)(\[\/TAG\])/iU", "<u>\\2</u>", $str);
	$str = preg_replace("/(\[MP3\])(.*)(\[\/MP3\])/iU", 
		"<div class=\"media_player\">\n"
		."<object classid=\"clsid:6BF52A52-394A-11d3-B153-00C04F79FAA6\" id=\"PlayerEx2\" width=\"100%\" height=\"62\">\n"
		."<param name=\"autoStart\" value=\"true\" />\n"  
		."<param name=\"URL\" value=\"\\2\" />\n" 
		."<embed autostart=\"true\" src=\"\\2\" type=\"video/x-ms-wmv\" width=\"100%\" " 
		."height=\"62\" controls=\"ImageWindow\" console=\"cons\"></embed>\n" 
		."</object>\n</div>\n", $str);
	$str = preg_replace("/(\[FLASH=(.*),(.*)\])(.*)(\[\/FLASH\])/iU", 
		"<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" "
		."codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\""
		."width=\"\\2\" height=\"\\3\">\n"
		."<param name=\"movie\" value=\"\\4\" />\n"
		."<param name=\"quality\" value=\"high\" />\n"
		."<param name=\"wmode\" value=\"Opaque\" />\n"
		."<embed src=\"\\4\" width=\"\\2\" height=\"\\3\" quality=\"high\" "
		."type=\"application/x-shockwave-flash\" "
		."wmode=\"Opaque\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\"></embed>\n</object>\n", $str);
	return $str;
}
?>