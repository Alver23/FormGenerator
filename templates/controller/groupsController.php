<?php 
$groupsModel = new groupsModel(); 
if(!empty($_POST)){ 
 
	 #Guardar formulario 
	 if($_POST['act']=='save'){ 
	 	 $groupsModel->cargarPorId($_POST['group_id']); 
	 	 $groupsModel->setValues($_POST); 
	 	 $groupsModel->save(); 
	 	 die(json_encode(array('msg'=>'El registro fue guardado correctamente', 'type'=>'success'))); 
	 } 
	 #Listar datatable 
	 if($_POST['act']=='listar'){ 
	 	 $pager = $groupsModel->getPager(array('group_id','group_name','group_description','is_disabled')); 
	 	 die($pager->getJSON()); 
	 } 
	 #Eliminar registro 
	 if($_POST['act']=='delete'){ 
	 	 $groupsModel->cargarPorId($_POST['id']); 
	 	 $groupsModel->delete(); 
	 	 die(json_encode(array('msg'=>'El registro fue eliminado correctamente', 'type'=>'success'))); 
	 } 
	 #cargar para editar registro 
	 if($_POST['act']=='edit'){ 
	 	 die(json_encode(array('data'=>$groupsModel->cargarPorId($_POST['id'])))); 
	 } 
}else{ 
 
	 echo $engine->render('groups', $aParams); 
 exit();}
?>