<?php
/**
* @author Alver Grisales
* @license GPL
* @version 1.1.0 mejorada
* If you have question, send me a email - alver.grisales@alvergrisalesoft.com
**/
class SEOURL{

	private $mailAlerts = mailsupport;
	private $fromMail;
	private $page;
	private $notifyGoogle = true;
	private $con;
	private $uri;
	private $baseh;
	private $mode;
	private $templatingEngine;
	private $npurl;
	private $idMap_URL;
	private $idLang;
	private $langCode;
	private $site;
	private $uri_codificada;
	private $FormInicio;
	private $FormContent;
	private $Template_Error404;

	public function SEOURL($con, $langCode){
		global $baseh;
		$this->Template_Error404 = "error404.php";
		$this->baseh = $baseh;
		$this->con = $con;
		$this->langCode = $langCode;
		$this->idLang = $this->getLangIdByCode($langCode);
		$this->uri = $_SERVER['REQUEST_URI'];
		$this->fromMail = "seourl@".$_SERVER['HTTP_HOST'];
		$this->page = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$this->site = $_SERVER['HTTP_HOST'];
		if($this->notifyGoogle)
			$this->notifyGoogle();
		$errors = ob_get_clean();
		ob_start(array(&$this,"my_ob_gzhandler"));//fix broken uris		
		echo $errors;
	}
	public function notifyGoogle(){
		if(stripos(@$_SERVER['HTTP_USER_AGENT'],"Googlebot/2.1")!==false && ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == 'sitemap.php')){
			mail($this->mailAlerts,"GoogleBot Hit: ".$this->page,"Page: {$this->page}\r\n".print_r($_SERVER,true),"From: ".$this->fromMail);
		}
	}
	public function my_ob_gzhandler($buffer, $mode){
		if($this->con->ajax || stristr($this->uri, 'administrator'))//Incompatible con Datatable
			return ob_gzhandler($buffer, $mode);
		if (DETECT_BAD_LINKS === true && !stristr($this->uri, 'administrator') && !stristr($this->uri, 'database')){
			$replacements = array(
				'/<\s*(a|link|script|img|iframe)\s[^>]*(href|src)\s*=\s*"([^"]*)"/',
				'/<\s*(a|link|script|img|iframe)\s[^>]*(href|src)\s*=\s*\'([^\'<>]*)\'/',
				);
			$buffer = preg_replace_callback($replacements, array(&$this,"addBaseURL"), $buffer);
		}
		if(in_array("tidyn", get_declared_classes()) && !$this->con->ajax) {
			$config = array(
				'indent'		=> (!stristr($this->uri, 'administrator') ? true : false),
				'tab-size'		=>	1,
				'fix-uri'		=> true,//No es una buena practiva seo codificar las url, pero segÃºn html5 se deben codificar
				'accessibility-check' => 0,
				'clean'         => true,
				'hide-comments' => true,
				'tidy-mark' => false,
				'indent-spaces' => 4,
				'new-blocklevel-tags' => 'article,header,footer,section,nav',
				'new-inline-tags' => 'video,audio,canvas,ruby,rt,rp',
				//'doctype' => '<!DOCTYPE HTML>',
				'doctype' => 'omit',
				'sort-attributes' => 'alpha',
				'vertical-space' => false,
				'output-xhtml' => false,
				//'ashtml' => true,//no work in windows
				'wrap' => 0,
				'wrap-attributes' => false,
				'break-before-br' => false,
				//'hide-comments' => true,
				//'input-xml' => true
			);
			$tidy = new tidy;
			$tidy->parseString($buffer, $config, 'utf8');
			$tidy->cleanRepair();
			$tidy->diagnose();
			$tidy = $tidy."<!-- ".$tidy->errorBuffer." -->";
		}
		if(isset($tidy) && !$this->con->ajax){//Hay tidy y no es una peticion ajax usando xajax
			$tidy = "<!DOCTYPE HTML>\r\n".$tidy;
			$buffer = str_replace("&nbsp;","&#160;",$tidy)/*."<!-- ".tidy_get_error_buffer($tidy)." -->"*/;
			$xhtml = "text/html";
			/*if (stristr(@$_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml')) {
				if (!stristr($uri, 'administrator') && !stristr($uri, 'database') && !stristr($uri, 'openx'))
				$xhtml = "application/xhtml+xml";
			} //Soporta el mime correcto para documentos xhtml con juego de caracteres*/
			header("Content-Type: ".$xhtml."; charset=UTF-8");
		}
		//Fix a base href 2009-02-08
		//return $buffer;
		return ob_gzhandler($buffer, $mode);
	}
	public function manageRedirects(){
		global $nocarga;
		$host = @$_SERVER['HTTP_HOST'];
		if($host == ''){
			die("Your browser do not sent a HTTP HOST Header, please upgrade to Google Chrome or check your Internet quality");
		}
		//Para "modulos" "especiales", se definela variable nocarga como true, para no llamar a arriba y abajo
		$purl = str_replace(array(SUBDIR_LOCAL,SUBDIR_REMOTE),'',$_SERVER['REQUEST_URI']);//27
		if(strpos($purl,"/")===0){
			$purl = substr($purl,1);
		}

		/*$this->npurl =substr($purl,0,strlen($purl)-4);*/
		if(strpos($purl,"?")===false){
			$this->npurl = str_replace(array(".html", ".php"), '', $purl); //si lo que se solicita por alguna razon viene sin extension falla por el metodo de cortar la cadena
		}else{
			$this->npurl = $purl;//no elimino la extension para urls dinamicas
		}
		$this->uri_codificada = urldecode($this->uri);
		$largo = strlen($this->uri_codificada);
		$cadena = substr($this->uri_codificada, $largo - 1);
		$tmp = explode('?',$purl);
		$fileo = array_shift($tmp);
		$file =  basename($fileo);
		$file = substr($file,0,(strpos($file,'?')!==FALSE ? strpos($file,'?') : strlen($file)));
		$file = substr($file,0,(strpos($file,'#')!==FALSE ? strpos($file,'#') : strlen($file)));
		if (strpos($purl,'?')===0 || strpos($cadena,'/')===0 || @$file == 'index.php') { //Podria ser un subdirectorio, pero si estoy aqui, es por que no existe
			$this->npurl =  "index";
		}
		if (stristr($purl, 'administrator')) {
			$this->npurl = "indexAdmin";
		}
		list($res, $url) = $this->existeURL($this->npurl, false);
		$uri = $_SERVER['REQUEST_URI'];
		//Verificar si se trata de una url especial, apuntada a un script
		$seourl = $this->npurl;
		$seourl = $this->npurl;
		$seourl = substr($seourl,0,(strpos($seourl,'?')!==FALSE ? strpos($seourl,'?') : strlen($seourl)));
		$seourl = substr($seourl,0,(strpos($seourl,'#')!==FALSE ? strpos($seourl,'#') : strlen($seourl)));
		$this->npurl = $seourl;
		$especial = false;
		if (!(isset($nocarga) && $nocarga === true)) {
			if ($res === true) {//remuevo la extension al verificar en database
				
				$url = $purl;
				//CARGAMOS EL ID DEL SITE
				$rurl = $this->con->query("SELECT `idMap_URL`, `lang_idLang`,  `seourl`, `script`, `query` FROM Map_URL WHERE seourl = ".$this->con->quote($this->npurl)." AND lang_idLang=".$this->idLang);
				if (count($rurl)<1) {
					$rurl = $this->con->query("SELECT `idMap_URL`, `lang_idLang`, `seourl`, `script`, `query` FROM Map_URL WHERE seourl = ".$this->con->quote($this->npurl)." ORDER BY lang_IdLang ASC LIMIT 1 -- AND lang_idLang=".$this->idLang);
					if(count($rurl)>0){
						$lang = new Lang($this->con);
						$lang->cargarPorId($rurl[0]["lang_idLang"]);
						$loc = $this->site."/".$this->npurl."?langCode=".$lang->getCode();
						header("Location: ".$loc);
						exit();
					}
				}
				if (count($rurl)>0) {
					$queryString = $rurl[0]["query"];
					parse_str($queryString,$get);
					$cvar = count($get);
					$_GET = array_merge($_GET,$get);

					if(count($_GET) < $cvar){
						@mail($this->$mailAlerts,"$_GET don't writable",print_r($get,true)."\n $_GET = ".print_r($_GET,true));
					}
					$script = $rurl[0]["script"];//siempre desde el root
					//$script = $rurl[0]["script"];//relativo a donde se esta llamando
					//exit();
					if ($script != '') {
						$especial = true;
						if (is_readable($script)) {
							if (basename($script) == "buscar.php") {
								if (count($_GET)==0) {
									print_r($get);print_r($_GET);var_dump($_GET);
									exit;
								}
								require $script;
								exit;
							}
							//require_once $script;
							return $script;
							exit();
						}else{
							@mail($this->mailAlerts,"Grave Error $script","Script $script ({$rurl[0]["script"]}) not found, \n URL was $this->npurl ".print_r($GLOBALS,true));
							$this->notFound($uri,$burl, "Not found {$rurl[0]["script"]} (script not found)");//&& exit
						}
					}else{
						@mail($this->mailAlerts,"Grave Error $script","Script $script ({$rurl[0]["script"]}) not found, \n URL was $this->npurl ".print_r($GLOBALS,true));
						$this->notFound($uri,$burl, "URL point to NULL script");//&& exit
					}
					

				}
			}else{
				if (!empty($this->Template_Error404)) {
					$template = $this->Template_Error404;
					//print $template;
					return $template;
				}
			}
			
		}
	}
	// trae el id del lenguaje definido por el navegador 
	public function getLangIdByCode($langCode){
		if($langCode == '')
			return 1;
		$r = $this->con->query("SELECT idLang FROM Lang WHERE code = ".$this->con->quote($langCode));
		if(count($r) == 0)
			return 1;
		return $r[0]["idLang"];
	}
	private function existeURL($url, $ajax = true){
		$tmp = explode('?',$url);
		$url =  array_shift($tmp);
		
		$ourl = $url;
		if ($ajax) {
			$url = $this->clearURL($url); //Limpio la URL, solo es para ajax
		}
		$urlp_Recordset1 = $url;
		$SQL = ("SELECT map.seourl AS url FROM Map_URL map
				WHERE map.seourl = %s ORDER BY url ASC LIMIT 1");
		$query_Recordset1 = sprintf($SQL,$this->con->quote($urlp_Recordset1));
		$row_Recordset1 = $this->con->query($query_Recordset1);
		$totalRows_Recordset1 = count($row_Recordset1);
		if ($ajax == false) {
				return (($totalRows_Recordset1 == 1 || file_exists(dirname(__file__)."/../" . $url)) ? array(true, $url) : array(false, $url));
		}
		ob_start();
	?>
		La URL: <input type="text" value="<?PHP echo $url; ?>" size="127" />
		<?PHP if ($totalRows_Recordset1 >= 1 || file_exists(dirname(__file__)."/../" . $url . ".php")) { ?>
		<span style="color:#FF0000">ya est&aacute; en uso</span>
		<?PHP } else { ?>
		<span style="color:#00FF00">est&aacute; disponible</span>
		<?PHP } ?>
		<?php
		$departs = ob_get_contents();
		ob_clean();
		$objResponse = new xajaxResponse('ISO-8859-1');
		$objResponse->addAssign("capaUrl", "innerHTML", $departs);
		$objResponse->addAssign("url", "value", $url);
		return $objResponse->getXML();
	} //function existeURL

	private function clearURL($url){
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
	private function addBaseURL($matches){
		$buf = $matches[0];
		$url = $matches[3];
		$nurl = $url;
		if((substr($url,0,1) == "/" && substr($url,0,2) != "//") && strpos($url,"http://")===false && strpos($url,"https://")===false && strpos($url,"data:")===false && strpos($url,"mailto:")===false && strpos($url,"#")===FALSE){
			$nurl = $this->baseh.$url;
			$buf = str_replace($url,$nurl,$buf);
		}
		return $buf;
	}
	public function notFound($url, $parseURL, $error = ''){
		$FormInicio = $this->FormInicio;
		if ($this->FormInicio == "") {
			$FormInicio = "index";
		}
		header("HTTP/1.0 404 Not Found");
		header("Status: 404 Not Found");
		//arriba("Not Found: " . $uri);
		//ob_start();
		echo "Not Found: $url <br />";
		echo $error;
		?>
		<script type="text/javascript">
		//$(window).load(function () {
			//setTimeout("top.location.href='./'",4000);//Redirecciono al home despues de 4 segundos, como para que no se pierda la visita
			//top.location.href='./';
			alert("<?PHP echo "Not Found: ".$parseURL;?>");
			top.location.href='./';
		//});
		</script><?PHP /*
		
		$notFoundTxt = ob_get_contents();
		ob_end_clean();
		$this->templatingEngine->display($layout,array("title"=>"Not Found: ".$url, "description"=>"Not Found","keywords"=> "Not Found","content"=>$notFoundTxt));
		//abajo();				
		exit;*/
	}
	public function setTemplatingEngine($templatingEngine){
		$this->templatingEngine = $templatingEngine;	
	}
	public function pseourl($seourl, $siteId, $script,$query=array(),$lang = ''){
		if(in_array(trim($siteId), array("",0))){
			mail($this->$mailAlerts,"No ID Site: ".$this->page,"Page: {$this->page}\r\n".print_r($_SERVER,true),"From: ".$this->fromMail);
			exit();
	  	}
	  	if($lang == '')
			$lang = $this->langCode;//Compatibility fix
		$seourl = $this->clearURL(trim($seourl));
		asort($query);
		$queryString = http_build_query($query);
		$surls = $this->con->query("SELECT COUNT(*) as nrows FROM Map_URL WHERE seourl = '$seourl' AND  lang_idLang=".$this->idLang);
		$nurls = intval($surls[0]["nrows"]);
		if ($nurls>0) {
			$rurl = $this->con->query("SELECT query,script FROM Map_URL WHERE seourl = '$seourl'  AND lang_idLang= ".$this->idLang);
			$oquery = $rurl[0]["query"];
			$oscript = $rurl[0]["script"];
			if (($oquery == $queryString) && ($oscript == $script) && ($ositeId==$siteId)) {
				return $seourl;
			}else{
				$valores = array("script"=>$script,  "query"=>$queryString,"lang_idLang"=>$this->idLang);
				$where = array("seourl"=>$this->con->quote($seourl),"lang_idLang"=>$this->idLang);
				$this->con->autoUpdate($valores,"Map_URL",$where);
				return $seourl;
			}
		}
		$values = array("seourl"=>$seourl,"script"=>$script,"query"=>$queryString,"lang_idLang"=>$this->idLang);
		$this->con->autoInsert($values,"Map_URL");
		@mail($this->mailAlerts,"New URL: $seourl ({$script}?{$queryString})",print_r($_SERVER,true),"From: it@".$_SERVER["HTTP_HOST"]);
		return $seourl;
	}
	//Esta function esta comentanda porque no le veo una buena funcionalidad.
	public function repairURL(){
		$urls = $this->con->query("SELECT Map_URL FROM seourl");
		foreach($urls as $url) {
			$urlt=array_values($url);
			$url = $urlt[0]; 
			$urlN = seoUrl($url);
			if($urlN != $url)
			$this->con->query("UPDATE Map_URL SET seourl = '{$urlN}' WHERE seourl ='{$url}' LIMIT 1");
		}
	}
	public function removeAccents($string){
		$string = html_entity_decode($string,ENT_COMPAT,"UTF-8");
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
		$string = strtr(utf8_decode($string), $replacements);
		$stripCharList = explode('|', $stripthese);
		$string = str_replace($stripCharList, '', $string);
		return $string;
	}
	public function displayContent($params = array()){
		$url = $this->npurl;
		$this->idMap_URL = $this->getIdByURL($url,$this->langCode);
		if(!$this->idMap_URL){
			die("$url not available in language {$this->langCode}");
			return false;
		}
		$sql = ("SELECT a.idArticle,m.Site_idSite,m.idMap_URL,m.script_template,a.idArticle,a.Map_URL_idMap_URL, a.Category_idCategory, a.common_name, a.title, a.description, a.keywords, a.`text`,enable,cat.type_menu,cat.view_menu_inferior,i.latitud,i.longitud,
			    a.view_map_destination,a.view_instituciones,slide1.document_filename AS slideleft,slide2.document_filename AS sliderigth FROM Article a
				INNER JOIN Map_URL m ON m.idMap_URL = a.Map_URL_idMap_URL
				LEFT JOIN Institution i ON i.Map_URL_idMap_URL = idMap_URL
				LEFT JOIN Category cat ON (cat.idCategory = a.Category_idCategory)
				LEFT JOIN Document slide1 ON(slide1.document_id = a.image_botton_slide1)
				LEFT JOIN Document slide2 ON(slide2.document_id = a.image_button_slide2)
				WHERE a.Map_URL_idMap_URL = %d AND m.Site_idSite = %d ");
		
		$query_Recordset12 = sprintf($sql,$this->idMap_URL, $ConUrl[0]['idSite']);
		$rows_Recordset12 = $this->con->query($query_Recordset12);
		$row_Recordset12 = @$rows_Recordset12[0];
		$totalRows_Recordset12 = count($rows_Recordset12);
		if (!empty($row_Recordset12['script_template'])) {
			$this->FormContent = $row_Recordset12['script_template'];
		}
		if ($totalRows_Recordset12>0 && $row_Recordset12['enable'] == 1) {
			$title = $row_Recordset12['title'];
			$description = $row_Recordset12['description'];
			$keywords = $row_Recordset12['keywords'];
			$content = str_replace("../", '', $row_Recordset12['text']);
			$aParams = array("title"=>$title,
			"description" => $description,
			"keywords" => $keywords,
			"content" => $content,
			"baseh" => $this->baseh,
			"latitud"=> $row_Recordset12['latitud'],
			"longitud"=> $row_Recordset12['longitud'],
			"text"=> $row_Recordset12['text'],
			"id_sitio" => $row_Recordset12['Site_idSite'],
			"idMap_URL" => $row_Recordset12['idMap_URL'],
			"type_menu" => $row_Recordset12['type_menu'],
			"view_map_destination" => $row_Recordset12['view_map_destination'],
			"slideleft" => $row_Recordset12['slideleft'],
			"sliderigth" => $row_Recordset12['sliderigth'],
			"menu_inferior" => $row_Recordset12['view_menu_inferior'],
			"view_instituciones" => $row_Recordset12['view_instituciones']
			);
			$paramsTmp = array_merge($params,$aParams);
			$this->templatingEngine->display($this->removeextension($this->FormContent),$paramsTmp);
		}elseif ($row_Recordset12['enable'] == 0 || empty($totalRows_Recordset12)) {
			if (!empty($this->Template_Error404)) {
				$template = FRONTEND_TEMPLATES.$this->Template_Error404;
				//print $template;
				$this->templatingEngine->display($this->removeextension($this->Template_Error404));
			}
			/*?>
			<script>
				alert("The Article this is disable yes");
				location.href = "http://<?php print $this->site;?>";
			</script>
			<?php*/
		}else{
			switch($url){
				case "index":
					$this->templatingEngine->display($this->removeextension($this->FormInicio), $params);
					exit;
				break;				
			}
		}
	}
	/*
	Get id URL by url and language code
	*/	
	public function getIdByURL($url,$langCode, $site){
		$urls = $this->con->query("SELECT idMap_URL FROM Map_URL WHERE seourl = ".$this->con->quote($url));
		if(count($urls) == 0)
			return false;
		return $urls[0]["idMap_URL"];		
	}
	private function removeextension($script){
		$sinext = explode(".",$script);
		return $sinext[0];
	}
	public function remove_url_query_args($url) {
		$seourl = $this->clearURL($url);
		$seourl = substr($seourl,0,(strpos($seourl,'?')!==FALSE ? strpos($seourl,'?') : strlen($seourl)));
		$seourl = substr($seourl,0,(strpos($seourl,'#')!==FALSE ? strpos($seourl,'#') : strlen($seourl)));
        return $seourl;
	}

	
}
?>