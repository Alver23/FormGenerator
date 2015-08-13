<?php 
require_once "ProjectConfiguration.php";
require_once "templating/lib/sfTemplateAutoloader.php";
require_once "_checkAuth.php"; 
sfTemplateAutoloader::register();
$loader = new sfTemplateLoaderFilesystem(BACKEND_TEMPLATES . '/%name%.php');
$engine = new sfTemplateEngine($loader);

$helper = new sfTemplateHelperAssets();
//$helper->setBaseURLs(PROJECT_WEB);
$helperSet = new sfTemplateHelperSet(
		array($helper, new sfTemplateHelperJavascripts(),
				new sfTemplateHelperStylesheets()));
				
if (! empty($_POST["act"]) || (isset($_POST['ajax']) && $_POST['ajax']== 'true'))
	header ( "Content-type: application/json" ); 

$engine->setHelperSet($helperSet);
$engine->set("lang_code", $langCode);
$engine->set("aLangs", $aLangs[$langCode]);
$engine->set("userData",$session->userInfo); 
$engine->set("seourl",$seourl); 
$engine->set("baseh",$baseh);
?>