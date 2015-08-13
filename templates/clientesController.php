<?php 
if(!empty($_POST)){ 
 
	 #Guardar formulario 
	 if($_POST['act']=='save'){ 
 
	 } 
	 #Listar datatable 
	 if($_POST['act']=='listar'){ 
	 	 $clientesModel = new clientesModel(); 
	 	 $pager = $clientesModel->getPager(array('idclientes','nombre','apellido','telefono','celular','email')); 
	 	 die($pager->getJSON()); 
	 } 
	 #Eliminar registro 
	 if($_POST['act']=='delete'){ 
 
	 } 
}else{ 
 
	 echo $engine->render('clientes', $aParams); 
 exit();}
?>