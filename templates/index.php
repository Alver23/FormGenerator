<?PHP 
require_once "app/FrontendConfiguration.php";
if ($_POST) {
	//$mapURL = new MapURLModel();
	//Validar URL
	if (@$_POST['act'] == "URLvalidate") {
		//die($_POST['a']);
		$rst = $mapURL->validateURL($_POST['a'],$_POST['b']);
		if (!empty($rst)){ 
			$aResp = array("resp"=>true,"data"=>$rst);
		}else{
			$aResp = array("resp"=>false,"data"=>"");
		}
		$json = json_encode($aResp);
		die($json);	
	}
}else{

}
echo $engine->render ('index');
exit();
?>