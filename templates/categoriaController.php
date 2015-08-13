<?php 
	 	 $categoriaModel = new categoriaModel(); 
if(!empty($_POST)){ 
 
	 #Guardar formulario 
	 if($_POST['act']=='save'){ 
	 	 $categoriaModel->cargarPorId($_POST['idcategoria']); 
	 	 $categoriaModel->setValues($_POST); 
	 	 $categoriaModel->save(); 
	 	 die(json_encode(array('msg'=>'El registro fue guardado correctamente', 'type'=>'success'))); 
	 } 
	 #Listar datatable 
	 if($_POST['act']=='listar'){ 
	 	 $pager = $categoriaModel->getPager(array('idcategoria','tienda_idtienda','nombre')); 
	 	 die($pager->getJSON()); 
	 } 
	 #Eliminar registro 
	 if($_POST['act']=='delete'){ 
	 	 $categoriaModel->cargarPorId($_POST['id']); 
	 	 $categoriaModel->delete(); 
	 	 die(json_encode(array('msg'=>'El registro fue eliminado correctamente', 'type'=>'success'))); 
	 } 
}else{ 
 
	 echo $engine->render('categoria', $aParams); 
 exit();}
?>