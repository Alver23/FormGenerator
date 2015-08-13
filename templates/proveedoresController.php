<?php 
if(!empty($_POST)){ 
 
	 #Guardar formulario 
	 if($_POST['act']=='save'){ 
 
	 } 
	 #Listar datatable 
	 if($_POST['act']=='listar'){ 
	 	 $proveedoresModel = new proveedoresModel(); 
	 	 $pager = $proveedoresModel->getPager(array('idproveedores','nombre','apellido','telefono','celular','email')); 
	 	 die($pager->getJSON()); 
	 } 
	 #Eliminar registro 
	 if($_POST['act']=='delete'){ 
 
	 } 
}else{ 
 
	 echo $engine->render('proveedores', $aParams); 
 exit();}
?>