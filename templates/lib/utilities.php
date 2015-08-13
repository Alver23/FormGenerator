<?php

function multidimensionalArrayMap() {    
    $aParams = func_get_args();
    $func = $aParams[0];
    $data = $aParams[1];
    unset($aParams[0], $aParams[1]);
    
    $newArr = array();

    foreach( $data as $key => $value) {
        $newsParams = $aParams;    
        $newParams[1] = $aParams[1] = $value;
        $newParams[0] = $func;
        ksort($aParams);
        ksort($newParams);
        //echo "aParams vale : <pre>".print_r($aParams, true)."</pre>";
        //echo "newsParams vale : <pre>".print_r($newsParams, true)."</pre>";
        
        $newArr[$key] = ( is_array( $value ) ? call_user_func_array("multidimensionalArrayMap", $newParams) : call_user_func_array($func, $aParams));
    }

    return $newArr;
}

function slug($string) {
    //Primero definimos nuestro array de caracteres especiales que queremos limpiar en nuestra cadena
    $characters = array(
        "Á" => "A", "Ç" => "c", "É" => "e", "Í" => "i", "Ñ" => "n", "Ó" => "o", "Ú" => "u",
        "á" => "a", "ç" => "c", "é" => "e", "í" => "i", "ñ" => "n", "ó" => "o", "ú" => "u",
        "à" => "a", "è" => "e", "ì" => "i", "ò" => "o", "ù" => "u"
     );
 
     $string = strtr($string, $characters); //Realiza la conversión de los caracteres
     return $string;
}

function clearURL($url){
		$url = slug($url);
		/*
		Funcion que deja purita un cadena, como para una SEF URL
		*/
		//$url = html_entity_decode($url,ENT_COMPAT,"UTF-8");
		$url = trim($url);
		$stripthese = ",|~|!|@|%|^|(|)|<|>|:|;|{|}|[|]|&|`|Ã¯Â¿Â½|Ã¯Â¿Â½|Ã¯Â¿Â½|Ã¯Â¿Â½|Ã¯Â¿Â½|Ã¯Â¿Â½|Ã¯Â¿Â½|Ã¯Â¿Â½|Ã¯Â¿Â½|Ã¯Â¿Â½|Ã¯Â¿Â½|Ã¯Â¿Â½";
		$sreplacements = "Å |S, Å’|O, Å½|Z, Å¡|s, Å“|oe, Å¾|z, Å¸|Y, Â¥|Y, Âµ|u, Ã€|A, Ã�|A, Ã‚|A, Ãƒ|A, Ã„|A, Ã…|A, Ã†|A, Ã‡|C, Ãˆ|E, Ã‰|E, ÃŠ|E, Ã‹|E, ÃŒ|I, Ã�|I, ÃŽ|I, Ã�|I, Ã�|D, Ã‘|N, Ã’|O, Ã“|O, Ã”|O, Ã•|O, Ã–|O, Ã˜|O, Ã™|U, Ãš|U, Ã›|U, Ãœ|U, Ã�|Y, ÃŸ|s, Ã |a, Ã¡|a, Ã¢|a, Ã£|a, Ã¤|a, Ã¥|a, Ã¦|a, Ã§|c, Ã¨|e, Ã©|e, Ãª|e, Ã«|e, Ã¬|i, Ã­|i, Ã®|i, Ã¯|i, Ã°|o, Ã±|n, Ã²|o, Ã³|o, Ã´|o, Ãµ|o, Ã¶|o, Ã¸|o, Ã¹|u, Ãº|u, Ã»|u, Ã¼|u, Ã½|y, Ã¿|y, ÃŸ|ss";
	
		$replacements = array();
		$items = explode(',', $sreplacements);
		foreach ($items as $item) {
			if (!empty($item)) { //  better protection. Returns null array if empty
				@list($src, $dst) = explode('|', trim($item));
				$replacements[trim($src)] = trim($dst);
			}
		}
		$url = strtr(utf8_decode($url), $replacements);//url from utf-8 to iso-8859-1 for strtr (today is PHP 5.4 here is not mb_strtr)
		$stripCharList = explode('|', $stripthese);
		$url = str_replace($stripCharList, '', $url);
		//refinar mas la url
		$nurl = '';
		for ($i = 0; $i < strlen($url); $i++) {
			$rchr = $url{$i};
			$chr = ord($rchr); //ASCII
			if ((!($chr <= 47 || ($chr >= 58 && $chr <= 64) || ($chr >= 91 && $chr <= 96) ||
				$chr >= 123)) || $chr == 32 || $rchr == '-' || $rchr == '/') { //Escrito a mano limpia
				$nurl .= $rchr;
			} //if
	
		} //for
		$nurl = $url;//temporali fix to allow tildes
		$nurl = str_replace(' ', "-", $nurl);
		//Eliminar todos los dobles "-"
		while (strstr($nurl, "--")) {
			$nurl = str_replace("--", "-", $nurl);
		}
		return utf8_encode($nurl);
		return htmlentities($nurl, ENT_COMPAT, "UTF-8");
	}

function removeAccents($string) {
 $string = html_entity_decode($string,ENT_COMPAT,"UTF-8");
    $stripthese = ",|~|!|@|%|^|(|)|<|>|:|;|{|}|[|]|&|`|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½";
    $sreplacements = "Š|S, Œ|O, Ž|Z, š|s, œ|oe, ž|z, Ÿ|Y, ¥|Y, µ|u, À|A, Á|A, Â|A, Ã|A, Ä|A, Å|A, Æ|A, Ç|C, È|E, É|E, Ê|E, Ë|E, Ì|I, Í|I, Î|I, Ï|I, Ð|D, Ñ|N, Ò|O, Ó|O, Ô|O, Õ|O, Ö|O, Ø|O, Ù|U, Ú|U, Û|U, Ü|U, Ý|Y, ß|s, à|a, á|a, â|a, ã|a, ä|a, å|a, æ|a, ç|c, è|e, é|e, ê|e, ë|e, ì|i, í|i, î|i, ï|i, ð|o, ñ|n, ò|o, ó|o, ô|o, õ|o, ö|o, ø|o, ù|u, ú|u, û|u, ü|u, ý|y, ÿ|y, ß|ss";

    $replacements = array();
    $items = explode(',', $sreplacements);
    foreach ($items as $item) {
        if (!empty($item)) { //better protection. Returns null array if empty
            @list($src, $dst) = explode('|', trim($item));
            $replacements[trim($src)] = trim($dst);
        }
    }
    $string = strtr(utf8_decode($string), $replacements);
 $stripCharList = explode('|', $stripthese);
    $string = str_replace($stripCharList, '', $string);
 return $string;
}

function seoUrl($url) {
    /*
    Funcion que deja purita un cadena, como para una SEF URL
    */
 $url = html_entity_decode($url,ENT_COMPAT,"UTF-8");
    $stripthese = ",|~|!|@|%|^|(|)|<|>|:|;|{|}|[|]|&|`|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½|ï¿½";
    $sreplacements = "Š|S, Œ|O, Ž|Z, š|s, œ|oe, ž|z, Ÿ|Y, ¥|Y, µ|u, À|A, Á|A, Â|A, Ã|A, Ä|A, Å|A, Æ|A, Ç|C, È|E, É|E, Ê|E, Ë|E, Ì|I, Í|I, Î|I, Ï|I, Ð|D, Ñ|N, Ò|O, Ó|O, Ô|O, Õ|O, Ö|O, Ø|O, Ù|U, Ú|U, Û|U, Ü|U, Ý|Y, ß|s, à|a, á|a, â|a, ã|a, ä|a, å|a, æ|a, ç|c, è|e, é|e, ê|e, ë|e, ì|i, í|i, î|i, ï|i, ð|o, ñ|n, ò|o, ó|o, ô|o, õ|o, ö|o, ø|o, ù|u, ú|u, û|u, ü|u, ý|y, ÿ|y, ß|ss";

    $replacements = array();
    $items = explode(',', $sreplacements);
    foreach ($items as $item) {
        if (!empty($item)) { //  better protection. Returns null array if empty
            @list($src, $dst) = explode('|', trim($item));
            $replacements[trim($src)] = trim($dst);
        }
    }
    $url = strtr(utf8_decode($url), $replacements);
 $stripCharList = explode('|', $stripthese);
    $url = str_replace($stripCharList, '', $url);
    //refinar mas la url
    $nurl = '';
    for ($i = 0; $i < strlen($url); $i++) {
        $rchr = $url{$i};
        $chr = ord($rchr); //ASCII
        if ((!($chr <= 47 || ($chr >= 58 && $chr <= 64) || ($chr >= 91 && $chr <= 96) ||
            $chr >= 123)) || $chr == 32 || $rchr == '-' || $rchr == '/') { //Escrito a mano limpia
            $nurl .= $rchr;
        } //if

    } //for
    $nurl = str_replace(' ', "-", $nurl);
    //Eliminar todos los dobles "-"
    while (strstr($nurl, "--")) {
        $nurl = str_replace("--", "-", $nurl);
    }
    return htmlentities($nurl, ENT_COMPAT, "UTF-8");
}

function validate_email($str){ 
	$str = strtolower($str); 
	/* Agrega todas las extensiones que quieras
	*/
	if(@ereg("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{2,200}\.[a-zA-Z]{2,6}$",$str)){ 
		return 1; 
	} else { 
		return 0; 
	} 
}

function getIp() {
	$ipAddr = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER["REMOTE_ADDR"];
    $ipAddr = $_SERVER["REMOTE_ADDR"];
	return $ipAddr;
}

function sendMail($address, $subject, $content, $attachments = array(), $isSMTP = true, $from = "info@silletas.com", $from_name = "Silletas", $embeddImages = array()) {
	$mail = new PHPMailer ( true );
	$mail->CharSet = "UTF-8";
	
	try {
		if ($isSMTP) {
			$mail->IsSMTP ();
			// $mail->Mailer = "smtp";
			// $mail->Host = "ssl://smtp.gmail.com";
			$mail->SMTPDebug = 2;
			$mail->SMTPAuth = true;
			$mail->SMTPKeepAlive = true;
			$mail->SMTPSecure = "ssl";
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465;
			$mail->Username = "@latinoaustralia.com";
			$mail->Password = "";
		}
		
		$mail->SetFrom ( $from, $from_name );
		$mail->AddReplyTo ( $from, $from_name );
		
		$mail->AddBCC ( "dlucumi@latinoaustralia.com", "Daniel Felipe Lucumi Marin" );
		$mail->AddAddress ( $address );
		$mail->Subject = $subject;
		$mail->AltBody = strip_tags ( nl2br ( $content ) );
		$mail->MsgHTML ( $content );
		
		if (! empty ( $attachments )) {
			foreach ( $attachments as $file )
				$mail->AddAttachment ( $file );
		}
		
		if (! empty ( $embeddImages )) {
			foreach ( $embeddImages as $file )
				$mail->AddEmbeddedImage($file["path"], $file["cid"], $file["name"]);
		}
		
		if (! $mail->Send ()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
			return false;
		} else
			return true;
	} catch ( phpmailerException $e ) {
		echo $e->errorMessage ();
	} catch ( Exception $e ) {
		echo $e->getMessage ();
	}
}


function displayDate($strDate, $toFormat = "Y/m/d") {
    if($strDate == "0000-00-00")
        return false;
        
    if($strDate == "0000-00-00 00:00:00")
        return false;
        
    if($strDate == "")
        return false;
        
    if(!in_array($toFormat, array("Y/m/d", "Y-m-d"))) { // From DB to Other Format    
        if (($timestamp = strtotime($strDate)) === false)
            return false;
        else
            return date($toFormat, $timestamp);
    } else {    // To DB Format 
        //2011-03-04, added support for datetime
        $parts = explode(" ",$strDate,2);
        $time = '';
        if(isset($parts[2])){
            $time = date(" H:i:s",strtotime($strDate));
        }
        // Test For Format dd/mm/YYYY, dd-mm-YYYY, dd.mm.YYYY
        $testFormat = '/(\d\d?)[-|.|\/](\d\d?)[-|.|\/](\d\d\d\d)$/';

        if (preg_match($testFormat, $strDate, $aMatches) !== false) {
            if(checkdate($aMatches[2], $aMatches[1], $aMatches[3]))
                return date("{$aMatches[3]}-{$aMatches[2]}-{$aMatches[1]}").$time;
            else
                return false;                                     
        } else {
            return false;
        }        
    }
}

function createHTMLTable($data) {    
    $aHead = array_keys(current($data));    
    $table = "<br /><table border='1' cellpadding='2' cellspacing='2'>";
    $table .= "<thead>";

    foreach ($aHead as $i => $head) {
        if($i==0)
            $table .= "<th x:autofilter='all' x:autofilterrange='\$A\$1:\$X\$1' nowrap>".ucwords(implode(" ", explode("_", $head)))."</th>\n";
        else
            $table .= "<th nowrap>".ucwords(implode(" ", explode("_", $head)))."</th>\n";
    }

    $table .= "</thead>\n<tbody>";

    foreach ($data as $row) {    
        $table .= "<tr>\n";
        
        foreach ($row as $val)
            $table .= "<td class='text' nowrap>".($val ? $val : "&nbsp;")."</td>\n";
        
        $table .= "</tr>\n";    
    }

    $table .= "</tbody></table><br /><br />";        
    return $table;
}
function jsspecialchars( $string = '') {
    $string = preg_replace("/\r*\n/","\\n",$string);
    $string = preg_replace("/\//","\\\/",$string);
    $string = preg_replace("/\"/","\\\"",$string);
    $string = preg_replace("/'/"," ",$string);
    return $string;
}

function eliminarDir($carpeta){
	foreach(glob($carpeta . "/*") as $archivos_carpeta){
			//echo $archivos_carpeta;
	
		if (is_dir($archivos_carpeta)){
			 eliminarDir($archivos_carpeta);
		}else{
			 unlink($archivos_carpeta);
		}
	}
	if(file_exists($carpeta))
		rmdir($carpeta);
}

function gw_printField($name, $value = "") {
	$gw_merchantKeyText = "C7PV4X8G9exSs3SaJmPAyC2J8z8b2G6w";
	static $fields;
 
	// Generate the hash
	if($name == "hash") {
		$stringToHash = implode('|', array_values($fields)) . 
			"|" . $gw_merchantKeyText;
		$value = implode("|", array_keys($fields)) . "|" . md5($stringToHash);
	} else {
		$fields[$name] = $value;
	}
	return "<INPUT TYPE=HIDDEN NAME=\"$name\" VALUE=\"$value\">\n";
}
function setUpdate($array){
	$sql = "";
		foreach($array as $key => $val){
			$sql[] = $key."= '".$val."'";
		}
		$array['updater_user_id'] = 1;
		$array['updated_at'] = date("Y-m-d H:i:s");
		return $sql;
}
function setValue($user, $type=""){
		$array['ip_address'] = getIp();
		if(empty($type)){
			$array['owner_user_id'] = $user;
		}
		$array['updater_user_id'] = $user;
		$array['created_at'] = date("Y-m-d H:i:s");
		$array['updated_at'] = date("Y-m-d H:i:s");
		return $array;
}
//Busca una cadena dentro de otra
function isIn($buscado, $cadena){
    $pos = strpos($cadena, $buscado);
    return !($pos === false);
}
/**
 * Reemplaza todos los acentos por sus equivalentes sin ellos
 *
 * @param $string
 *  string la cadena a sanear
 *
 * @return $string
 *  string saneada
 */
function sanear_string($string)
{

    $string = trim($string);

    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );

    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º","~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "`", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        '',
        $string
    );


    return $string;
}
function array_non_empty_items($input) {
    // If it is an element, then just return it
    if (!is_array($input)) {
      return $input;
    }
 
    $non_empty_items = array();
 
    foreach ($input as $key => $value) {
      // Ignore empty cells
      if($value) {
        // Use recursion to evaluate cells
        $non_empty_items[$key] = array_non_empty_items($value);
      }
    }
 
    // Finally return the array without empty items
    return $non_empty_items;
}
function displayerror($mail,$subject, $details) {
    if(is_array($details)){
        $details = $details[2];
    }else{
        $details = $details;
    }
    ob_start();
    echo "BackTrace: ";
    debug_print_backtrace();
    echo "_POST: ";
    print_r($_POST);
    echo "_GET: ";
    print_r($_GET);
    echo "_REQUEST: ";
    print_r($_REQUEST);
    echo "_SERVER: ";
    print_r($_SERVER);
    echo "GLOBALS: ";
    print_r($GLOBALS);
    $systemInfo = ob_get_contents();
    ob_end_clean();
    ob_start();
    mail($mail, $subject, $details . $systemInfo,"From: " . $mail);
    $mailError = ob_get_contents();
    ob_end_clean();
}
function GroupArray($array,$groupkey){
    if (count($array)>0) {
        $keys = array_keys($array[0]);
        $removekey = array_search($groupkey, $keys);
        if($removekey===false)
            return array("Clave \"$groupkey\" no existe");
        else
            unset($keys[$removekey]);
        $groupcriteria = array();
        $return=array();
        foreach ($array as $value) {
            $item=null;
            foreach ($keys as $key) {
                $item[$key] = $value[$key];
            }
            $busca = array_search($value[$groupkey], $groupcriteria);
            if ($busca === false) {
                $groupcriteria[]=$value[$groupkey];
                $return[]=array($groupkey=>$value[$groupkey],'groupeddata'=>array());
                $busca=count($return)-1;
            }
            $return[$busca]['groupeddata'][]=$item;
        }
        return $return;
    }else
        return array();
}

function get_mime_type($file)
{

        // our list of mime types
        $mime_types = array(
                "pdf"=>"application/pdf"
                ,"exe"=>"application/octet-stream"
                ,"zip"=>"application/zip"
                ,"docx"=>"application/msword"
                ,"doc"=>"application/msword"
                ,"xls"=>"application/vnd.ms-excel"
                ,"ppt"=>"application/vnd.ms-powerpoint"
                ,"gif"=>"image/gif"
                ,"png"=>"image/png"
                ,"jpeg"=>"image/jpg"
                ,"jpg"=>"image/jpg"
                ,"mp3"=>"audio/mpeg"
                ,"wav"=>"audio/x-wav"
                ,"mpeg"=>"video/mpeg"
                ,"mpg"=>"video/mpeg"
                ,"mpe"=>"video/mpeg"
                ,"mov"=>"video/quicktime"
                ,"avi"=>"video/x-msvideo"
                ,"3gp"=>"video/3gpp"
                ,"css"=>"text/css"
                ,"jsc"=>"application/javascript"
                ,"js"=>"application/javascript"
                ,"php"=>"text/html"
                ,"htm"=>"text/html"
                ,"html"=>"text/html"
        );

		$temp = explode(".", $file);
		$ext = end($temp);

        return $mime_types[strtolower ($ext)];
}
?>