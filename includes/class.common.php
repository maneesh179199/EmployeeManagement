<?php
function sanatizeString($string, $limit='30') {
	$string = strip_tags($string); 
	$words = explode(" ", $string);
	$result = implode(" ", array_slice($words, 0, $limit));
	if(count($words) > $limit) {
		return $result."...";
	} else {
		return $result;
	}
}
function generatePassword($length = 8) {
	$chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789!@#$';
	$count = strlen($chars);
	for ($i = 0, $result = ''; $i < $length; $i++) {
			$index = rand(0, $count - 1);
			$result .= substr($chars, $index, 1);
	}
	return $result;
}
function hashPassword($password) {
  $algo = "$2a$07$";
  $authkey = "dfdc8d423f69fa9eKorDXZ8fgeDVEvpuiVMG2KoicPiKvCy";
	$encode_password = base64_encode($password);
  $md5_password = md5($encode_password);
	$crypt_password = crypt($md5_password, $algo.$authkey);
	$result = substr($crypt_password, 0, 40);
  return $result;
}
function csrfToken() {
	$now = new DateTime('NOW');
	$time=$now->format('U'); 
	$chars = 'abcdefghijklmnopqrstuvwxyz';
	$count = strlen($chars);
	for ($i = 0, $result = ''; $i < 4; $i++) {
		$index = rand(0, $count - 1);
		$result .= substr($chars, $index, 1);
	}
	$time_stamp= md5($time);
	$tokens=hash('sha256', $result);
	$tokens=$tokens.$time_stamp;
	return $tokens;
}
function escapeFile($file) {
	$name = substr($file, 0, strrpos($file,'.'));
	$name = str_replace(".", "_", $name);
	$name = preg_replace('/[^a-z0-9\-_\.]/i','_',$name);
	return $name;
}
function escapeFiles($file) {
	$ext = substr($file, strrpos($file,'.'), strlen($file)-1);
	$count = strrpos($file,'.');
	$nfile = substr($file, 0, $count);
	$nfile = preg_replace('/[^a-z0-9\-_]/i','_',$nfile);
	$nfile = trim($nfile,"_");
	$nfile = preg_replace('#[ _]+#', '_', $nfile);
	$nfile = $nfile.$ext;
	return $nfile;
}
function isJson($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}
function getSlug($string) {
	$string = trim($string);
	$string = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\[\]\\x80-\\xff]|i', '_', $string);
	$string = preg_replace('#[ ~+_.?=!&;,/:%@$\|*\'()\[\]]+#', '_', $string);
	$string = preg_replace('#[ -]+#', '_', $string);
	$string = trim($string, "-");
	$string = mb_strtolower($string, 'UTF-8');
	return $string;
}
function getDates($s) {
	return date("d/m/Y", strtotime($s));
}
function getFullDates($s) {
	return date("d/m/Y H:i A", strtotime($s));
}
function getCurrentDate() {
	$date = date("d,m,Y,H,i");
	$result = explode(',',$date);
	return $result;
}
function getPostPublishDate($date) {
	$date = date("d,m,Y,H,i", strtotime($date));
	$result = explode(',',$date);
	return $result;
}
function getMonthList() {
	$months = array('01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June', '07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
	return $months;
}
function date_to_string($ts) {
	if(!ctype_digit($ts)) {
		$ts = strtotime($ts);
	}
	$diff = time() - $ts;
	if($diff == 0) {
		$result = 'now';
	} elseif($diff > 0) {
		$day_diff = floor($diff / 86400);
		if($day_diff == 0) {
			if($diff < 60) {
				$result = 'just now';
			} else if($diff < 120) {
				$result = '1 minute ago';
			} else if($diff < 3600) {
				$result = floor($diff / 60) . ' minutes ago';
			} else if($diff < 7200) {
				$result = '1 hour ago';
			} else if($diff < 86400) {
				$result = floor($diff / 3600) . ' hours ago';
			} else {
				$result = date('d/m/Y g:i a', $ts);
			}
		} else {
			$result = date('d/m/Y g:i a', $ts);
		}
	} else {
		$result = date('F Y', $ts);
	}
	return $result;
}
function getStatus($s) {
	if($s=='1') {
		return "Active";
	} else if($s=='2') {
		return "Trash";
	} else {
		return "Inactive";
	}
}
function getOrderStatus($s) {
	if($s=='0') {
		return "Pending";
	} else if($s=='1') {
		return "Complete";
	} else {
		return "Failed";
	}
}
function getAuthorType($s) {
	if($s=='1') {
		return "Documart";
	} else if($s=='2') {
		return "Documart Partner";
	}
}
function getComplainStatus($s) {
	if($s=='0') {
		return "Not Responded";
	} else if($s=='1') {
		return "In Progress";
	} else if($s=='2'){
		return "Closed";
	}else{
		return "Reopened";
	}
}
function getViewStatus($s) {
	if($s=='0') {
		return "Not Viewed";
	 }else{
		return "Viewed";
	}
}
function getUserStatus($s) {
	if($s=='1') {
		return "Active";
	} else if($s=='0') {
		return "Trash";
	} else {
		return "Inactive";
	}
}
function getDocumentStatus($s) {
	if($s=='1') {
		return "Active";
	} else if($s=='9') {
		return "Trash";
	} else {
		return "Inactive";
	}
}
function paymentRequestStatus($s) {
	if($s=='1') {
		return "Pending";
	} else if($s=='2') {
		return "Successful";
	} else {
		return "Rejected";
	}
}

function getUserType($s) {
	if($s=='1') {
		return "Admin";
	} else if($s=='2') {
		return "Partner";
	} else if($s=='3') {
		return "Customer";	
	} else {
		return "NA";
	}
}
function getCommentStatus($s) {
	if($s=='1') {
		return "Approve";
	} else if($s=='2') {
		return "Trash";
	} else {
		return "Pending";
	}
}

function hasPermission($module,$page,$allsession) {
	
    $user=$allsession["admin"];	
	global $db;
	$result = false;
	$admin = $db->get_row("SELECT * FROM tbluser WHERE id='".$user."' ");
	if($admin->user_role==1||$admin->user_role==2){
		$result=true;
		return $result;
	}
	$allrights = $db->get_var("SELECT r.permissions FROM tbluser  u JOIN tblrole_master r ON u.user_role= r.id  WHERE u.id='".$user."';");
	$rights = unserialize($allrights);
	if($admin) {
		if(in_array($module.'-'.$page,$rights)) {
		 	$result = true;
		}
	}
	return $result;
}

function getDomainClean($s) {
	$folder = preg_replace('/[^A-Za-z0-9\-]/', '', $s); // Removes special chars.
	return $folder;
}

function hasImage($path,$suffix='') {
	$dir = @pathinfo($path,PATHINFO_DIRNAME);
	if(is_dir($dir)) {
		$parent_dir = '';
	} else {
		$parent_dir = '../';
	}
	$name = @pathinfo($path,PATHINFO_FILENAME);
	$ext = @pathinfo($path,PATHINFO_EXTENSION);
	if($suffix!='') {
		$file = $parent_dir.$dir."/".$name."-".$suffix.".".$ext;
	} else {
		$file = $parent_dir.$dir."/".$name.".".$ext;
	}
	if(@getimagesize($file)) {
		return $file;
	} else {
		if(@getimagesize('../'.$file)) {
			return $file;
		} else {
			return false;
		}
	}
	return false;
}

function hasImageDefault($image,$suffix='') {
	$new_image = hasImage($image,$suffix);
	if($new_image) {
		$img = $new_image;
	} else {
		$img = "assets/img/img-placeholder_".$suffix.".png";
	}
	return $img;
}

function getThumbnail($s) {
	$dotPos = strrpos( $s, '.' );
	$file = substr($s, 0, $dotPos);
	$ext = substr( $s, $dotPos + 1 );
	$thumb = $file."-thumbnail.".$ext;
	return $thumb;	
}

function getFileExtension($files) {
	$extension = pathinfo($files, PATHINFO_EXTENSION);
	return strtolower($extension);
}

function getMediaFileName($file) {
	$file = str_replace("../", "", $file);
	$e = explode("/", $file);
	if(count($e) > 0) {
		$file = end($e);		
	}
	$name = pathinfo($file,PATHINFO_FILENAME);
	return $name;
}
/* Slug Start */
function isSpaceString($str) {	
	$str = trim($str);
	if(strrpos($str, ' ')) {
    return true;
	} else {
		return false;
	}
}

function unique_slug($slug, $table, $column, $id='') {
	global $db;
	$sWhere = "WHERE $column='".$slug."' ";
	if($id!='') {
		$sWhere .= "AND id!='".$id."' ";
	}
	$post = $db->get_row("SELECT {$column} FROM {$table} {$sWhere} ");
	if($post) {
		$count = strrchr($slug, "-");
		$count = str_replace("_", "", $count);
		if(!empty($count) && is_int($count)) {
			$length = count($count) + 1;
			$title = substr($post->$column, 0, -$length);
			$count++;
		} else {
			$count = 1;
			$title = $post->$column;
		}
		$sef = $title."_".$count;
		return unique_slug($sef, $table, $column, $id);
	} else {
		return $slug;
	}
}

function mbstring_binary_safe_encoding( $reset = false ) {
	static $encodings = array();
	static $overloaded = null;
	if ( is_null( $overloaded ) )
		$overloaded = function_exists( 'mb_internal_encoding' ) && ( ini_get( 'mbstring.func_overload' ) & 2 );

	if ( false === $overloaded )
		return;
	if ( ! $reset ) {
		$encoding = mb_internal_encoding();
		array_push( $encodings, $encoding );
		mb_internal_encoding( 'ISO-8859-1' );
	}
	if ( $reset && $encodings ) {
		$encoding = array_pop( $encodings );
		mb_internal_encoding( $encoding );
	}
}

function reset_mbstring_encoding() {
	mbstring_binary_safe_encoding( true );
}

function seems_utf8( $str ) {
	mbstring_binary_safe_encoding();
	$length = strlen($str);
	reset_mbstring_encoding();
	for ($i=0; $i < $length; $i++) {
		$c = ord($str[$i]);
		if ($c < 0x80) $n = 0; // 0bbbbbbb
		elseif (($c & 0xE0) == 0xC0) $n=1; // 110bbbbb
		elseif (($c & 0xF0) == 0xE0) $n=2; // 1110bbbb
		elseif (($c & 0xF8) == 0xF0) $n=3; // 11110bbb
		elseif (($c & 0xFC) == 0xF8) $n=4; // 111110bb
		elseif (($c & 0xFE) == 0xFC) $n=5; // 1111110b
		else return false; // Does not match any model
		for ($j=0; $j<$n; $j++) { // n bytes matching 10bbbbbb follow ?
			if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
				return false;
		}
	}
	return true;
}

function utf8_uri_encode( $utf8_string, $length = 0 ) {
	$unicode = '';
	$values = array();
	$num_octets = 1;
	$unicode_length = 0;
	mbstring_binary_safe_encoding();
	$string_length = strlen( $utf8_string );
	reset_mbstring_encoding();
	for ($i = 0; $i < $string_length; $i++ ) {
		$value = ord( $utf8_string[ $i ] );
		if ( $value < 128 ) {
			if ( $length && ( $unicode_length >= $length ) )
				break;
			$unicode .= chr($value);
			$unicode_length++;
		} else {
			if ( count( $values ) == 0 ) {
				if ( $value < 224 ) {
					$num_octets = 2;
				} elseif ( $value < 240 ) {
					$num_octets = 3;
				} else {
					$num_octets = 4;
				}
			}
			$values[] = $value;
			if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
				break;
			if ( count( $values ) == $num_octets ) {
				for ( $j = 0; $j < $num_octets; $j++ ) {
					$unicode .= '%' . dechex( $values[ $j ] );
				}
				$unicode_length += $num_octets * 3;
				$values = array();
				$num_octets = 1;
			}
		}
	}
	return $unicode;
}

function remove_accents( $string ) {
	if ( !preg_match('/[\x80-\xff]/', $string) )
		return $string;
	if (seems_utf8($string)) {
		$chars = array(
		// Decompositions for Latin-1 Supplement
		'ª' => 'a', 'º' => 'o',
		'À' => 'A', 'Á' => 'A',
		'Â' => 'A', 'Ã' => 'A',
		'Ä' => 'A', 'Å' => 'A',
		'Æ' => 'AE','Ç' => 'C',
		'È' => 'E', 'É' => 'E',
		'Ê' => 'E', 'Ë' => 'E',
		'Ì' => 'I', 'Í' => 'I',
		'Î' => 'I', 'Ï' => 'I',
		'Ð' => 'D', 'Ñ' => 'N',
		'Ò' => 'O', 'Ó' => 'O',
		'Ô' => 'O', 'Õ' => 'O',
		'Ö' => 'O', 'Ù' => 'U',
		'Ú' => 'U', 'Û' => 'U',
		'Ü' => 'U', 'Ý' => 'Y',
		'Þ' => 'TH','ß' => 's',
		'à' => 'a', 'á' => 'a',
		'â' => 'a', 'ã' => 'a',
		'ä' => 'a', 'å' => 'a',
		'æ' => 'ae','ç' => 'c',
		'è' => 'e', 'é' => 'e',
		'ê' => 'e', 'ë' => 'e',
		'ì' => 'i', 'í' => 'i',
		'î' => 'i', 'ï' => 'i',
		'ð' => 'd', 'ñ' => 'n',
		'ò' => 'o', 'ó' => 'o',
		'ô' => 'o', 'õ' => 'o',
		'ö' => 'o', 'ø' => 'o',
		'ù' => 'u', 'ú' => 'u',
		'û' => 'u', 'ü' => 'u',
		'ý' => 'y', 'þ' => 'th',
		'ÿ' => 'y', 'Ø' => 'O',
		// Decompositions for Latin Extended-A
		'Ā' => 'A', 'ā' => 'a',
		'Ă' => 'A', 'ă' => 'a',
		'Ą' => 'A', 'ą' => 'a',
		'Ć' => 'C', 'ć' => 'c',
		'Ĉ' => 'C', 'ĉ' => 'c',
		'Ċ' => 'C', 'ċ' => 'c',
		'Č' => 'C', 'č' => 'c',
		'Ď' => 'D', 'ď' => 'd',
		'Đ' => 'D', 'đ' => 'd',
		'Ē' => 'E', 'ē' => 'e',
		'Ĕ' => 'E', 'ĕ' => 'e',
		'Ė' => 'E', 'ė' => 'e',
		'Ę' => 'E', 'ę' => 'e',
		'Ě' => 'E', 'ě' => 'e',
		'Ĝ' => 'G', 'ĝ' => 'g',
		'Ğ' => 'G', 'ğ' => 'g',
		'Ġ' => 'G', 'ġ' => 'g',
		'Ģ' => 'G', 'ģ' => 'g',
		'Ĥ' => 'H', 'ĥ' => 'h',
		'Ħ' => 'H', 'ħ' => 'h',
		'Ĩ' => 'I', 'ĩ' => 'i',
		'Ī' => 'I', 'ī' => 'i',
		'Ĭ' => 'I', 'ĭ' => 'i',
		'Į' => 'I', 'į' => 'i',
		'İ' => 'I', 'ı' => 'i',
		'Ĳ' => 'IJ','ĳ' => 'ij',
		'Ĵ' => 'J', 'ĵ' => 'j',
		'Ķ' => 'K', 'ķ' => 'k',
		'ĸ' => 'k', 'Ĺ' => 'L',
		'ĺ' => 'l', 'Ļ' => 'L',
		'ļ' => 'l', 'Ľ' => 'L',
		'ľ' => 'l', 'Ŀ' => 'L',
		'ŀ' => 'l', 'Ł' => 'L',
		'ł' => 'l', 'Ń' => 'N',
		'ń' => 'n', 'Ņ' => 'N',
		'ņ' => 'n', 'Ň' => 'N',
		'ň' => 'n', 'ŉ' => 'n',
		'Ŋ' => 'N', 'ŋ' => 'n',
		'Ō' => 'O', 'ō' => 'o',
		'Ŏ' => 'O', 'ŏ' => 'o',
		'Ő' => 'O', 'ő' => 'o',
		'Œ' => 'OE','œ' => 'oe',
		'Ŕ' => 'R','ŕ' => 'r',
		'Ŗ' => 'R','ŗ' => 'r',
		'Ř' => 'R','ř' => 'r',
		'Ś' => 'S','ś' => 's',
		'Ŝ' => 'S','ŝ' => 's',
		'Ş' => 'S','ş' => 's',
		'Š' => 'S', 'š' => 's',
		'Ţ' => 'T', 'ţ' => 't',
		'Ť' => 'T', 'ť' => 't',
		'Ŧ' => 'T', 'ŧ' => 't',
		'Ũ' => 'U', 'ũ' => 'u',
		'Ū' => 'U', 'ū' => 'u',
		'Ŭ' => 'U', 'ŭ' => 'u',
		'Ů' => 'U', 'ů' => 'u',
		'Ű' => 'U', 'ű' => 'u',
		'Ų' => 'U', 'ų' => 'u',
		'Ŵ' => 'W', 'ŵ' => 'w',
		'Ŷ' => 'Y', 'ŷ' => 'y',
		'Ÿ' => 'Y', 'Ź' => 'Z',
		'ź' => 'z', 'Ż' => 'Z',
		'ż' => 'z', 'Ž' => 'Z',
		'ž' => 'z', 'ſ' => 's',
		// Decompositions for Latin Extended-B
		'Ș' => 'S', 'ș' => 's',
		'Ț' => 'T', 'ț' => 't',
		// Euro Sign
		'€' => 'E',
		// GBP (Pound) Sign
		'£' => '',
		// Vowels with diacritic (Vietnamese)
		// unmarked
		'Ơ' => 'O', 'ơ' => 'o',
		'Ư' => 'U', 'ư' => 'u',
		// grave accent
		'Ầ' => 'A', 'ầ' => 'a',
		'Ằ' => 'A', 'ằ' => 'a',
		'Ề' => 'E', 'ề' => 'e',
		'Ồ' => 'O', 'ồ' => 'o',
		'Ờ' => 'O', 'ờ' => 'o',
		'Ừ' => 'U', 'ừ' => 'u',
		'Ỳ' => 'Y', 'ỳ' => 'y',
		// hook
		'Ả' => 'A', 'ả' => 'a',
		'Ẩ' => 'A', 'ẩ' => 'a',
		'Ẳ' => 'A', 'ẳ' => 'a',
		'Ẻ' => 'E', 'ẻ' => 'e',
		'Ể' => 'E', 'ể' => 'e',
		'Ỉ' => 'I', 'ỉ' => 'i',
		'Ỏ' => 'O', 'ỏ' => 'o',
		'Ổ' => 'O', 'ổ' => 'o',
		'Ở' => 'O', 'ở' => 'o',
		'Ủ' => 'U', 'ủ' => 'u',
		'Ử' => 'U', 'ử' => 'u',
		'Ỷ' => 'Y', 'ỷ' => 'y',
		// tilde
		'Ẫ' => 'A', 'ẫ' => 'a',
		'Ẵ' => 'A', 'ẵ' => 'a',
		'Ẽ' => 'E', 'ẽ' => 'e',
		'Ễ' => 'E', 'ễ' => 'e',
		'Ỗ' => 'O', 'ỗ' => 'o',
		'Ỡ' => 'O', 'ỡ' => 'o',
		'Ữ' => 'U', 'ữ' => 'u',
		'Ỹ' => 'Y', 'ỹ' => 'y',
		// acute accent
		'Ấ' => 'A', 'ấ' => 'a',
		'Ắ' => 'A', 'ắ' => 'a',
		'Ế' => 'E', 'ế' => 'e',
		'Ố' => 'O', 'ố' => 'o',
		'Ớ' => 'O', 'ớ' => 'o',
		'Ứ' => 'U', 'ứ' => 'u',
		// dot below
		'Ạ' => 'A', 'ạ' => 'a',
		'Ậ' => 'A', 'ậ' => 'a',
		'Ặ' => 'A', 'ặ' => 'a',
		'Ẹ' => 'E', 'ẹ' => 'e',
		'Ệ' => 'E', 'ệ' => 'e',
		'Ị' => 'I', 'ị' => 'i',
		'Ọ' => 'O', 'ọ' => 'o',
		'Ộ' => 'O', 'ộ' => 'o',
		'Ợ' => 'O', 'ợ' => 'o',
		'Ụ' => 'U', 'ụ' => 'u',
		'Ự' => 'U', 'ự' => 'u',
		'Ỵ' => 'Y', 'ỵ' => 'y',
		// Vowels with diacritic (Chinese, Hanyu Pinyin)
		'ɑ' => 'a',
		// macron
		'Ǖ' => 'U', 'ǖ' => 'u',
		// acute accent
		'Ǘ' => 'U', 'ǘ' => 'u',
		// caron
		'Ǎ' => 'A', 'ǎ' => 'a',
		'Ǐ' => 'I', 'ǐ' => 'i',
		'Ǒ' => 'O', 'ǒ' => 'o',
		'Ǔ' => 'U', 'ǔ' => 'u',
		'Ǚ' => 'U', 'ǚ' => 'u',
		// grave accent
		'Ǜ' => 'U', 'ǜ' => 'u',
		);
		// Used for locale-specific rules
		$locale = "en_US";
		if ( 'de_DE' == $locale || 'de_DE_formal' == $locale || 'de_CH' == $locale || 'de_CH_informal' == $locale ) {
			$chars[ 'Ä' ] = 'Ae';
			$chars[ 'ä' ] = 'ae';
			$chars[ 'Ö' ] = 'Oe';
			$chars[ 'ö' ] = 'oe';
			$chars[ 'Ü' ] = 'Ue';
			$chars[ 'ü' ] = 'ue';
			$chars[ 'ß' ] = 'ss';
		} elseif ( 'da_DK' === $locale ) {
			$chars[ 'Æ' ] = 'Ae';
 			$chars[ 'æ' ] = 'ae';
			$chars[ 'Ø' ] = 'Oe';
			$chars[ 'ø' ] = 'oe';
			$chars[ 'Å' ] = 'Aa';
			$chars[ 'å' ] = 'aa';
		} elseif ( 'ca' === $locale ) {
			$chars[ 'l·l' ] = 'll';
		} elseif ( 'sr_RS' === $locale || 'bs_BA' === $locale ) {
			$chars[ 'Đ' ] = 'DJ';
			$chars[ 'đ' ] = 'dj';
		}
		$string = strtr($string, $chars);
	} else {
		$chars = array();
		// Assume ISO-8859-1 if not UTF-8
		$chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
			."\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
			."\xc3\xc4\xc5\xc7\xc8\xc9\xca"
			."\xcb\xcc\xcd\xce\xcf\xd1\xd2"
			."\xd3\xd4\xd5\xd6\xd8\xd9\xda"
			."\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
			."\xe4\xe5\xe7\xe8\xe9\xea\xeb"
			."\xec\xed\xee\xef\xf1\xf2\xf3"
			."\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
			."\xfc\xfd\xff";
		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";
		$string = strtr($string, $chars['in'], $chars['out']);
		$double_chars = array();
		$double_chars['in'] = array("\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe");
		$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
		$string = str_replace($double_chars['in'], $double_chars['out'], $string);
	}
	return $string;
}

function sanitize_title_with_dashes( $title, $raw_title = '', $context = 'display' ) {
	$title = strip_tags($title);
	// Preserve escaped octets.
	$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	// Remove percent signs that are not part of an octet.
	$title = str_replace('%', '', $title);
	// Restore octets.
	$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
	if (seems_utf8($title)) {
		if (function_exists('mb_strtolower')) {
			$title = mb_strtolower($title, 'UTF-8');
		}
		$title = utf8_uri_encode($title, 200);
	}
	$title = strtolower($title);
	if ( 'save' == $context ) {
		// Convert nbsp, ndash and mdash to hyphens
		$title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );
		// Convert nbsp, ndash and mdash HTML entities to hyphens
		$title = str_replace( array( '&nbsp;', '&#160;', '&ndash;', '&#8211;', '&mdash;', '&#8212;' ), '-', $title );

		// Strip these characters entirely
		$title = str_replace( array(
			// iexcl and iquest
			'%c2%a1', '%c2%bf',
			// angle quotes
			'%c2%ab', '%c2%bb', '%e2%80%b9', '%e2%80%ba',
			// curly quotes
			'%e2%80%98', '%e2%80%99', '%e2%80%9c', '%e2%80%9d',
			'%e2%80%9a', '%e2%80%9b', '%e2%80%9e', '%e2%80%9f',
			// copy, reg, deg, hellip and trade
			'%c2%a9', '%c2%ae', '%c2%b0', '%e2%80%a6', '%e2%84%a2',
			// acute accents
			'%c2%b4', '%cb%8a', '%cc%81', '%cd%81',
			// grave accent, macron, caron
			'%cc%80', '%cc%84', '%cc%8c',
		), '', $title );

		// Convert times to x
		$title = str_replace( '%c3%97', 'x', $title );
	}
	$title = preg_replace('/&.+?;/', '', $title); // kill entities
	$title = str_replace('.', '-', $title);
	$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
	$title = preg_replace('/\s+/', '-', $title);
	$title = preg_replace('|-+|', '-', $title);
	$title = trim($title, '-');
	return $title;
}
function generate_captcha($input, $strength = 5) {
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
  
    return $random_string;
}
function getParentCategory($args_category,$c) {
		$result = "";
	foreach((array)$args_category as $data) {
		if($data->id==$c) {
			$result = $data->category_name;
			break;
		}
	}
	return $result;
}
function getCategoryStatus($s) {
	if($s=='1') {
		return "Active";
	} else if($s=='9') {
		return "Trash";
	} else {
		return "Inactive";
	}
}
?>