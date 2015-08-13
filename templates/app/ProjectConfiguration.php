<?php
/**
* @author Alver Grisales
* @license GPL
* @version 1.1.0 mejorada
* If Do you have any question, send me a email - alver.grisales@alvergrisalesoft.com
**/
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set("log_errors", "1");
ini_set("memory_limit", "-1");
mb_detect_order("ASCII,UTF-8,ISO-8859-1");
date_default_timezone_set("America/Bogota");

header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Cache-control: private', false); // IE 6 FIX
header('Cache-control: public, max-age=31536000');
header('Pragma: no-cache');
ini_set("include_path", ini_get("include_path").PATH_SEPARATOR.realpath(dirname(__file__).'/..').PATH_SEPARATOR.realpath(dirname(__file__).'/../lib'));
require_once "DBNative.php";
require_once "PHPMailer/class.phpmailer.php";
require_once 'conNeg/PHP5.x/conNeg.inc.php';
require_once "utilities.php";

//CONSTANTS
define("HOST_NO_WWW",str_replace("www.","",@$_SERVER['HTTP_HOST']));
define("HOST",@$_SERVER['HTTP_HOST']);
define("LOCAL_IP",@$_SERVER['SERVER_ADDR']);
define("BASEH_LOCAL","http://".HOST."/formGenerate/templates/");
$BASEH_REMOTE_ES = "http://".HOST."/formGenerate/templates/";
$BASEH_REMOTE_PT = "http://".HOST."/";
define("SUBDIR_LOCAL","");
define("SUBDIR_REMOTE","/".HOST_NO_WWW."/");
define("DETECT_BAD_LINKS",TRUE);//reporta links malos en contenido a enviar
define("FIX_BAD_URL",FALSE);//reporta urls malas en base de datos
define("FIX_UTF8",FALSE);//corrige tíldes (buafff)

define("PROJECT_ROOT",      realpath(dirname(__FILE__)."/../"));
define("PROJECT_URL",       BASEH_LOCAL);
define("PROJECT_WEB",       PROJECT_URL."public");
define("PROJECT_CONFIG",    PROJECT_ROOT."/app");
define("PROJECT_LIB",       PROJECT_ROOT."/lib");
define("FRONTEND_TEMPLATES",PROJECT_ROOT."/views");
define("BACKEND_TEMPLATES", PROJECT_ROOT."/administrator/templates");
define("mailsupport","alver.grisales@alvergrisalesoft.com");//Definimos el correo donde queremos que nos lleguen los errores
$archivo = PROJECT_CONFIG."/ConfigDatabases/databases.ini";
$langConf = $aLangs = array();

//require_once "FrontendConfiguration.php";
if (file_exists($archivo) && is_readable($archivo)) {
	if (!$ajustes = parse_ini_file($archivo, true)) throw new exception ('No se puede abrir el archivo ' . $archivo . '.');
	//Conexion al SERVER
	define("DB_SERVER",         $ajustes["remote_database"]["server"]);
    define("DB_NAME",           $ajustes["remote_database"]["name"]);
    define("DB_USER",           $ajustes["remote_database"]["user"]);
    define("DB_PASS",           $ajustes["remote_database"]["password"]);
    define("DB_DRIVER",         $ajustes["remote_database"]["driver"]);
    define("DB_PORT",           $ajustes["remote_database"]["port"]);
    //Conexion Local
    define("DB_SERVER_LOCAL",   $ajustes["local_database"]["server"]);
    define("DB_NAME_LOCAL",     $ajustes["local_database"]["name"]);
    define("DB_USER_LOCAL",     $ajustes["local_database"]["user"]);
    define("DB_PASS_LOCAL",     $ajustes["local_database"]["password"]);
    define("DB_DRIVER_LOCAL",   $ajustes["local_database"]["driver"]);
    define("DB_PORT_LOCAL",     $ajustes["local_database"]["port"]);
}else{
	print "Archivo o encontrado";
}
//die($archivo);

if (in_array($_SERVER['SERVER_ADDR'], array("127.0.0.1", "localhost"))){
	$mode = "local";
	define("DSN",DB_DRIVER_LOCAL."://".DB_USER_LOCAL.":".DB_PASS_LOCAL."@".DB_SERVER_LOCAL.":".DB_PORT_LOCAL."/".DB_NAME_LOCAL);
}else{
	$mode = "remote";
	define("DSN",DB_DRIVER."://".DB_USER.":".DB_PASS."@".DB_SERVER.":".DB_PORT."/".DB_NAME);
}

foreach($langConf as $langConf1){
    $aLangs = array_merge_recursive($aLangs,parse_ini_file($langConf1, true));
}

//require_once "session.php";
$con = DBNative::get(DSN);
$utf81 = DBNative::get()->query("SET character_set_results = 'utf8'");
$utf82 = DBNative::get()->query("SET character_set_client = 'utf8'");
$utf83 = DBNative::get()->query("SET character_set_connection = 'utf8'");
$utf84 = DBNative::get()->query("SET character_set_database = 'utf8'");
$utf85 = DBNative::get()->query("SET character_set_server = 'utf8'");
// Define Language Code
$langs = array("ES","PT", "EN");
$domains = array("es"=>"ES","pt"=>"PT","en"=>"EN");
$langCUser = conNeg::langBest();
$tmpLang = explode("-",$langCUser);
$langUser = strtoupper(@$tmpLang[0]);
$_COOKIE['langCode'] = strtoupper(@$_COOKIE['langCode']);
$tmpDomain = explode(".",@$_SERVER['HTTP_HOST']);
$subDomain = @$tmpDomain[0];
$domain = @$tmpDomain[1];
$tld = @$tmpDomain[2];
//se chequea el idioma en este orden de prioridades, GET, Subdominio, Cookies, Definido por el navegador, predeterminado
if(!empty($_GET["langCode"])){//Esto solo aplicaria en localhost, ya que en remoto siempre hay subdominio
    $langCode = $_REQUEST["langCode"];
    setcookie("langCode",$langCode);//para que al cambiar de pagina sin necesidad de transmitir el idioma por get, se conserve.
}else{
    if(strlen($subDomain)>0 && isset($domains[$subDomain])){//subdominio
        $langCode = $domains[$subDomain];
    }elseif(strlen($_COOKIE['langCode'])>0 && in_array($_COOKIE['langCode'],$langs))//cookie ajustada por get, localhost
    {
        $langCode = $_COOKIE['langCode'];
    }
    elseif(strlen($langUser)>0 && in_array($langUser,$langs))//user-agent accept language
    {
        $langCode = $langUser;
    }else{
        $langCode = "EN";//default
    }
}
if($langCode != "EN"){
    header("Location: ?langCode=EN",302);exit;
}
$array = array();
//$array = require("params.php");//leer valores de configuración desde la base de datos
if(is_array(@$array))
    $aLangs = array_merge_recursive($aLangs,array($langCode => $array));
 
define("BASEH_REMOTE",$BASEH_REMOTE_ES);
define("BASEH_REMOTE_ALTERNATE",$BASEH_REMOTE_PT);

$baseh = BASEH_LOCAL;
if($mode != "local"){
    $baseh = BASEH_REMOTE;
}
if(strpos($_SERVER['REQUEST_URI'],"/administrator")===0)
    $baseh .= "administrator/";

foreach($aLangs[$langCode] as $key => $val) {
    $aTmp = explode(".", $key);
    $suffix = array_pop($aTmp);
    
    if(in_array($suffix, array("req", "err", "suc")))
        define(str_replace(".", "_", strtoupper($key)), $val);
}

foreach (glob(PROJECT_LIB."/model/*.php") as $fileName)
    require_once $fileName;

foreach (glob(PROJECT_LIB."/*.php") as $fileName)
    require_once $fileName;
    
require_once PROJECT_LIB."/maxmind/index.php";

$seourl = new SEOURL(DBNative::get(DSN), $langCode);

$ipinfo = get_ip_info(@$_SERVER['REMOTE_ADDR']);
if($ipinfo === false)
    $ipinfo = get_ip_info("190.145.78.66");
$pais = @$ipinfo["COUNTRY_NAME"];
$ciudad = @$ipinfo["CITY"];
$region = @$ipinfo["REGION"];
$latitud = @$ipinfo["LATITUD"];
$longitud = @$ipinfo["LONGITUD"];

//Pager Options
$pagerOptions = array(
    "rowsPerPage" => 30, 
    "delta" => 10, 
    "urlVar" => "page",    
    "curPageLinkClassName" => "current"
);
?>