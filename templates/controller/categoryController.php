<?php 
$categoryModel = new categoryModel(); 
if(!empty($_POST)){ 
 
	 #Guardar formulario 
	 if($_POST['act']=='save'){ 
	 	 $categoryModel->cargarPorId($_POST['idcategory']); 
	 	 $categoryModel->setValues($_POST); 
	 	 $categoryModel->save(); 
	 	 die(json_encode(array('msg'=>'El registro fue guardado correctamente', 'type'=>'success'))); 
	 } 
	 #Listar datatable 
	 if($_POST['act']=='listar'){ 
	 	 $pager = $categoryModel->getPager(array('idcategory','name')); 
	 	 die($pager->getJSON()); 
	 } 
	 #Eliminar registro 
	 if($_POST['act']=='delete'){ 
	 	 $categoryModel->cargarPorId($_POST['id']); 
	 	 $categoryModel->delete(); 
	 	 die(json_encode(array('msg'=>'El registro fue eliminado correctamente', 'type'=>'success'))); 
	 } 
	 #cargar para editar registro 
	 if($_POST['act']=='edit'){ 
	 	 die(json_encode(array('data'=>$categoryModel->cargarPorId($_POST['id'])))); 
	 } 
}else{ 
 
	 echo $engine->render('category', $aParams); 
 exit();}
?>