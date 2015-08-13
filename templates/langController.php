<?php 
if(!empty($_POST)){ 
 
	 #Guardar formulario 
	 if($_POST['act']=='save'){ 
 
	 } 
	 #Listar datatable 
	 if($_POST['act']=='listar'){ 
	 	 $langModel = new langModel(); 
	 	 $pager = $langModel->getPager(array('idlang','name','code')); 
	 	 die($pager->getJSON()); 
	 } 
	 #Eliminar registro 
	 if($_POST['act']=='delete'){ 
 
	 } 
}else{ 
 
	 echo $engine->render('lang', $aParams); 
 exit();}
?>