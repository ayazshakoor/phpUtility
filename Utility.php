<?php
//namespace Admin\library;

class Utility
{

	public function __construct()
	{}

	public function populate_select($arr,$value=null)
	{
		$buff = "";
		for($i=0;$i<count($arr);$i++){
			if($value == $arr[$i][0]){
				$selected=" selected";
			}else{
				$selected="";
			}
			$buff .= '<option value="'.$arr[$i][0].'"'.$selected.'>'.$arr[$i][1].'</option>';
		}
		return $buff;
	}

	public function str_to_kv($get_str)
	{
		$arr=explode('&',$get_str);
		$crr=array();
		for($i=0;$i<count($arr);$i++)
		{
			$brr=explode('=',$arr[$i]);
			if( trim($brr[0])!='' && array_key_exists('1',$brr) )
			$crr[trim($brr[0])]=trim($brr[1]);
		}
		//return
		return $crr;
	}

	public function seems_utf8($str)
	{
		$length = strlen($str);
		for ($i=0; $i < $length; $i++)
		{
			$c = ord($str[$i]);
			if ($c < 0x80) $n = 0; # 0bbbbbbb
			elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
			elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
			elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
			elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
			elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
			else return false; # Does not match any model
			for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
				if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
					return false;
			}
		}
		return true;
	}

	public function utf8_uri_encode( $utf8_string, $length = 0 )
	{
		$unicode = '';
		$values = array();
		$num_octets = 1;
		$unicode_length = 0;
	
		$string_length = strlen( $utf8_string );
		for ($i = 0; $i < $string_length; $i++ ) {
	
			$value = ord( $utf8_string[ $i ] );
	
			if ( $value < 128 ) {
				if ( $length && ( $unicode_length >= $length ) )
					break;
				$unicode .= chr($value);
				$unicode_length++;
			} else {
				if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;
	
				$values[] = $value;
	
				if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
					break;
				if ( count( $values ) == $num_octets ) {
					if ($num_octets == 3) {
						$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
						$unicode_length += 9;
					} else {
						$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
						$unicode_length += 6;
					}
	
					$values = array();
					$num_octets = 1;
				}
			}
		}
	
		return $unicode;
	}

	public function remove_accents($string)
	{
		if ( !preg_match('/[\x80-\xff]/', $string) ){
			return $string;
		}

		if ($this->seems_utf8($string)) {
			$chars = array(
			// Decompositions for Latin-1 Supplement
			chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
			chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
			chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
			chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
			chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
			chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
			chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
			chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
			chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
			chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
			chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
			chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
			chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
			chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
			chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
			chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
			chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
			chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
			chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
			chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
			chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
			chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
			chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
			chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
			chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
			chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
			chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
			chr(195).chr(191) => 'y',
			// Decompositions for Latin Extended-A
			chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
			chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
			chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
			chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
			chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
			chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
			chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
			chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
			chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
			chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
			chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
			chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
			chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
			chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
			chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
			chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
			chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
			chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
			chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
			chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
			chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
			chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
			chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
			chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
			chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
			chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
			chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
			chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
			chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
			chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
			chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
			chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
			chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
			chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
			chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
			chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
			chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
			chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
			chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
			chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
			chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
			chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
			chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
			chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
			chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
			chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
			chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
			chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
			chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
			chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
			chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
			chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
			chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
			chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
			chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
			chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
			chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
			chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
			chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
			chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
			chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
			chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
			chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
			chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
			// Euro Sign
			chr(226).chr(130).chr(172) => 'E',
			// GBP (Pound) Sign
			chr(194).chr(163) => '');

			$string = strtr($string, $chars);
		} else {
			// Assume ISO-8859-1 if not UTF-8
			$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
				.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
				.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
				.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
				.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
				.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
				.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
				.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
				.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
				.chr(252).chr(253).chr(255);

			$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

			$string = strtr($string, $chars['in'], $chars['out']);
			$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
			$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
			$string = str_replace($double_chars['in'], $double_chars['out'], $string);
		}
		return $string;
	}

	public function sanitize_title_with_dashes($title,$tolower=true)
	{
		$title = strip_tags($title);
		// Preserve escaped octets.
		$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
		// Remove percent signs that are not part of an octet.
		$title = str_replace('%', '', $title);
		// Restore octets.
		$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
	
		$title = $this->remove_accents($title);
		if ($this->seems_utf8($title)) {
			if (function_exists('mb_strtolower')) {
				$title = mb_strtolower($title, 'UTF-8');
			}
			$title = $this->utf8_uri_encode($title, 200);
		}
		
		if($tolower)
		$title = strtolower($title);
		
		$title = preg_replace('/&.+?;/', '', $title); // kill entities
		$title = str_replace('.', '-', $title);
		$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
		$title = preg_replace('/\s+/', '-', $title);
		$title = preg_replace('|-+|', '-', $title);
		$title = trim($title, '-');
	
		return $title;
	}

	public function sanitize_title_with_dashes_unique($title,$tolower=true)
	{
		$slug = $this->sanitize_title_with_dashes($title,$tolower=true);
		$slug_arr = explode('-', $slug);
		$arr = array();
		if(is_array($slug_arr) && count($slug_arr)){
			for($i=0; $i<count($slug_arr); $i++){
				if( $i && isset($slug_arr[$i-1]) && $slug_arr[$i] == $slug_arr[$i-1] ){
					continue;
				}
				$arr[] = $slug_arr[$i];
			}
			if( count($arr) ){
				$slug = implode('-', $arr);
			} else {
				$slug = implode('-', $slug_arr);
			}
		}
		return $slug;
	}
	
	public function sanitize_int_array($arr)
	{
		$tmp = array();
		for($i=0;$i<count($arr);$i++)
		if($arr[$i]-0>0)
		$tmp[]=$arr[$i]-0;
		return $tmp;
	}

	function curl_get($url,$post=false)
	{
		$ch = curl_init();
		$header[] = "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
		$header[] = "Cache-Control: max-age=0";
		$header[] = "Connection: keep-alive";
		$header[] = "Keep-Alive: 300";
		$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$header[] = "Accept-Language: en-us,en;q=0.5";
		$header[] = "Pragma: ";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com/');
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		if(is_array($post))
		{
			curl_setopt($ch, CURLOPT_POST,true);
			$fields_string=array();
			foreach($post as $key=>$value)
			$fields_string[]=$key.'='.$value;
			curl_setopt($ch,CURLOPT_POSTFIELDS,implode('&',$fields_string));
		}
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
		$data['response']=curl_exec($ch);
		$info=curl_getinfo($ch);
		$data['http_code']=$info['http_code'];
		curl_close($ch);
		return $data;
	}

	function xml2assoc($contents, $get_attributes = 1)
	{
		if(!$contents) return array();
		if(!function_exists('xml_parser_create'))
		{
			//print "'xml_parser_create()' function not found!";
			return array();
		} 
		//Get the XML parser of PHP - PHP must have this module for the parser to work 
		$parser = xml_parser_create();
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );
		xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );
		xml_parse_into_struct( $parser, $contents, $xml_values );
		xml_parser_free( $parser );
		if(!$xml_values) return;//Hmm...
		//Initializations 
		$xml_array = array();
		$parents = array();
		$opened_tags = array();
		$arr = array();
		$current = &$xml_array;
		//Go through the tags. 
		foreach($xml_values as $data)
		{
			unset($attributes,$value);//Remove existing values, or there will be trouble 
			//This command will extract these variables into the foreach scope 
			// tag(string), type(string), level(int), attributes(array). 
			extract($data);//We could use the array by itself, but this cooler. 
	
			$result = ''; 
			if($get_attributes) {//The second argument of the function decides this. 
				$result = array(); 
				if(isset($value)) $result['value'] = $value; 
	
				//Set the attributes too. 
				if(isset($attributes)) { 
					foreach($attributes as $attr => $val) { 
						if($get_attributes == 1) $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr' 
						/**  :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too */ 
					} 
				} 
			} elseif(isset($value)) { 
				$result = $value; 
			} 
	
			//See tag status and do the needed. 
			if($type == "open") {//The starting of the tag '<tag>' 
				$parent[$level-1] = &$current; 
	
				if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag 
					$current[$tag] = $result; 
					$current = &$current[$tag]; 
	
				} else { //There was another element with the same tag name 
					if(isset($current[$tag][0])) { 
						array_push($current[$tag], $result); 
					} else { 
						$current[$tag] = array($current[$tag],$result); 
					} 
					$last = count($current[$tag]) - 1; 
					$current = &$current[$tag][$last]; 
				} 
	
			} elseif($type == "complete") { //Tags that ends in 1 line '<tag />' 
				//See if the key is already taken. 
				if(!isset($current[$tag])) { //New Key 
					$current[$tag] = $result; 
	
				} else { //If taken, put all things inside a list(array) 
					if((is_array($current[$tag]) and $get_attributes == 0)//If it is already an array... 
							or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1)) { 
						array_push($current[$tag],$result); // ...push the new element into that array. 
					} else { //If it is not an array... 
						$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value 
					} 
				} 
	
			} elseif($type == 'close') { //End of tag '</tag>' 
				$current = &$parent[$level-1]; 
			} 
		} 
		return($xml_array); 
	}

	function getImageResizeDimensions($width,$height,$constraintWidth = 0,$constraintHeight = 0 )
	{
		$constraintWidth = ($constraintWidth === 0) ? $width : $constraintWidth;
		$constraintHeight = ($constraintHeight === 0) ? $height : $constraintHeight;
		if ( ($width > $constraintWidth) || ($height > $constraintHeight) )
		{
			while( ( $width > $constraintWidth ) || ( $height > $constraintHeight ))
			{
				if ( $width > $constraintWidth )
				{
					$height = floor((($constraintWidth * $height) / $width));
					$width = $constraintWidth;
				}
				if ( $height > $constraintHeight )
				{
					$width = floor((($constraintHeight * $width) / $height));
					$height = $constraintHeight;
				}
			}
		}
		return array($width,$height);
	}

	function create_image_from_file($file_name = false)
	{
		$ext = strtolower(substr($file_name, strrpos($file_name, ".") + 1));
		try{
			switch ($ext)
			{
				case 'jpg':
					$img=imagecreatefromjpeg($file_name);
				break;
				case 'jpeg':
					$img=imagecreatefromjpeg($file_name);
				break;
				case 'png':
					$img=imagecreatefrompng($file_name);
				break;
				case 'gif':
					$img=imagecreatefromgif($file_name);
				break;
				case 'bmp':
					$img=imagecreatefromwbmp($file_name);
				break;
				default:
				return -2;
			}
		} catch (\Execption $e){
			
		}
		if($img)
		return $img;
		else
		return -3;
	}

	function create_thumbnail($source_file,$destination_file,$constrained_width=100,$constrained_height=100)
	{
		//create thumbnail
		if(file_exists($source_file) && exif_imagetype($source_file) ){
			//load original image
			$img=$this->create_image_from_file($source_file);
			if( $img && $img!='' )
			{
				//extension of original image
				$ext=strtolower(substr($source_file,strrpos($source_file,".")+ 1));
				//calculate resize
				$resize=$this->getImageResizeDimensions(imagesx($img),imagesy($img),$constrained_width,$constrained_height);
				//create new tumbnail image
				$new_image = imagecreatetruecolor($resize[0], $resize[1]);
				if($ext=='png')
				{
					imagealphablending($img, true);
					imagealphablending($new_image, false);
					imagesavealpha($new_image, true);
				}
				//resize
				imagecopyresampled($new_image, $img, 0, 0, 0, 0, $resize[0], $resize[1], imagesx($img), imagesy($img));
				switch ($ext)
				{
					case 'jpg':
					case 'jpeg':
						imagejpeg($new_image,$destination_file,80);
					break;
					case 'png':
						imagepng($new_image,$destination_file);
					break;
					case 'gif':
						imagegif($new_image,$destination_file);
					break;
					default:
					return -2;
				}
				return true;
			}
			else
			return -3;
		}
		else
		return false;
	}

	function create_thumbnail_cropped($source_file,$destination_file,$constrained_width=100,$constrained_height=100)
	{
		//create thumbnail
		if(file_exists($source_file))
		{
			//load original image
			$source_gdim = $this->create_image_from_file($source_file);
			if( $source_gdim && $source_gdim!='' )
			{
				//extension of original image
				$ext=strtolower(substr($source_file,strrpos($source_file,".")+ 1));
				list( $source_width, $source_height, $source_type ) = getimagesize( $source_file );
				$source_aspect_ratio = $source_width / $source_height;
				$desired_aspect_ratio = $constrained_width / $constrained_height;
				if ( $source_aspect_ratio > $desired_aspect_ratio )
				{
					$temp_height = $constrained_height;
					$temp_width = ( int ) ( $constrained_height * $source_aspect_ratio );
				}
				else
				{
					$temp_width = $constrained_width;
					$temp_height = ( int ) ( $constrained_width / $source_aspect_ratio );
				}
				$temp_gdim = imagecreatetruecolor( $temp_width, $temp_height );
				if($ext=='png')
				{
					imagealphablending($source_gdim, true);
					imagealphablending($temp_gdim, false);
					imagesavealpha($temp_gdim, true);
				}
				imagecopyresampled( $temp_gdim, $source_gdim, 0, 0, 0, 0, $temp_width, $temp_height, $source_width, $source_height );
				$x0 = ( $temp_width - $constrained_width ) / 2;
				$y0 = ( $temp_height - $constrained_height ) / 2;
				$desired_gdim = imagecreatetruecolor( $constrained_width, $constrained_height );
				if($ext=='png')
				{
					imagealphablending($temp_gdim, true);
					imagealphablending($desired_gdim, false);
					imagesavealpha($desired_gdim, true);
				}
				imagecopy( $desired_gdim, $temp_gdim, 0, 0, $x0, $y0, $constrained_width, $constrained_height );
				//to file
				switch ($ext)
				{
					case 'jpg':
					case 'jpeg':
						imagejpeg($desired_gdim,$destination_file,80);
					break;
					case 'png':
						imagepng($desired_gdim,$destination_file);
					break;
					case 'gif':
						imagegif($desired_gdim,$destination_file);
					break;
					default:
					return -2;
					break;
				}
				return true;
			}	
			else
			return -3;
		}
		else
		return false;
	}
	
	/**
	PHP substring without breaking a word 
	*/
	public function truncateStr($str, $maxlen)
	{
 		if (strlen ($str) <= $maxlen){
 		    return $str;
		}

		$newstr = substr ($str, 0, $maxlen);
 		if (substr ($newstr, -1, 1) != ' ')
 		    $newstr = substr ($newstr, 0, strrpos ($newstr, " "));

		return $newstr;
	}
	
	/**
	 * Parse Request Headers
	 */
	public function parseRequestHeaders() {
		$headers = array();
		foreach($_SERVER as $key => $value) {
			if (substr($key, 0, 5) <> 'HTTP_') {
				continue;
			}
			$header = str_replace(' ', '-', str_replace('_', ' ', strtolower(substr($key, 5))));
			$headers[$header] = $value;
		}
		return $headers;
	}
	
	function stripInvalidXml($value)
	{
		$ret = "";
		$current;
		if (empty($value)) {
			return $ret;
		}
		$length = strlen($value);
		for ($i=0; $i < $length; $i++) {
			$current = ord($value{$i});
			if (
				($current == 0x9) ||
				($current == 0xA) ||
				($current == 0xD) ||
				(($current >= 0x20) && ($current <= 0xD7FF)) ||
				(($current >= 0xE000) && ($current <= 0xFFFD)) ||
				(($current >= 0x10000) && ($current <= 0x10FFFF))
			) {
				$ret .= chr($current);
			} else {
				$ret .= " ";
			}
		}
		return $ret;
	}

	/**
	 * Convert CIDR into Range
	 * @param $cidr <String> - CIDR value
	 * @return $range <Array> - Range of given CIDR
	 */
	public function cidrToRange($cidr)
	{
		$range = array();
		$cidr = explode('/', $cidr);
		$range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
		$range[1] = long2ip((ip2long($cidr[0])) + pow(2, (32 - (int)$cidr[1])) - 1);
		return $range;
	}

	/**
	 * Safe Delete directories recursively.
	 * @param $dir <String> - Path to the directory that has to be deleted.
	 * @return <Boolean> - True/False
	 */
	public function deleteDirectory($dir)
	{
	    if (!file_exists($dir)) {
	        return true;
	    }

	    if (!is_dir($dir)) {
	        return unlink($dir);
	    }

	    foreach (scandir($dir) as $item) {
	        if ($item == '.' || $item == '..') {
	            continue;
	        }

	        if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
	            return false;
	        }

	    }

	    return rmdir($dir);
	}
}
?>