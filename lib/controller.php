<?php
#Created by Daniel Felipe Lucumi Marin
#E-mail: dflm25@gmail.com
#MSN: dannycali@hotmail.com
#CALI - COLOMBIA
class Controller{
	#Function: <ready Dom>
	function open_php($tabla) {
		@$jquery .= "<?php \n";
		$jquery .= "\$".$tabla."Model = new ".$tabla."Model(); \n";
		return $jquery;
	}
	
	function valPost(){
		@$jquery .= "if(!empty(\$_POST)){ \n \n";
		return $jquery;
	}
	
	function saveController($tabla,$primary){
		@$jquery .= "\t #Guardar formulario \n";
		 $jquery .= "\t if(\$_POST['act']=='save'){ \n";
		 $jquery .= "\t \t \$".$tabla."Model->cargarPorId(\$_POST['$primary']); \n";
		 $jquery .= "\t \t \$".$tabla."Model->setValues(\$_POST); \n";
		 $jquery .= "\t \t \$".$tabla."Model->save(); \n";
		 $jquery .= "\t \t die(json_encode(array('msg'=>'El registro fue guardado correctamente', 'type'=>'success'))); \n";
		 $jquery .= "\t } \n";
		return $jquery;
	}
	
	function listarController($campos, $opt, $tabla){
		$cant = count($campos); $salida = "";
		@$jquery .= "\t #Listar datatable \n";
		$jquery .= "\t if(\$_POST['act']=='listar'){ \n";
		for($x=0; $x<=$cant-1; $x++){
			if($opt[$x]=="si"){
			  $salida[] .= $campos[$x];
			}
		}
		if($salida){
			$jquery .= "\t \t \$pager = \$".$tabla."Model->getPager(array("."'" . implode("','", $salida) . "'".")); \n";
			$jquery .= "\t \t die(\$pager->getJSON()); \n";
		}
		$jquery .= "\t } \n";
		return $jquery;
	}
	
	function deleteController($tabla){
		@$jquery .= "\t #Eliminar registro \n";
		 $jquery .= "\t if(\$_POST['act']=='delete'){ \n";
		 $jquery .= "\t \t \$".$tabla."Model->cargarPorId(\$_POST['id']); \n";
		 $jquery .= "\t \t \$".$tabla."Model->delete(); \n";
		 $jquery .= "\t \t die(json_encode(array('msg'=>'El registro fue eliminado correctamente', 'type'=>'success'))); \n";
		 $jquery .= "\t } \n";
		return $jquery;
	}
	
	function editController($tabla){
		@$jquery .= "\t #cargar para editar registro \n";
		 $jquery .= "\t if(\$_POST['act']=='edit'){ \n";
		 $jquery .= "\t \t die(json_encode(array('data'=>\$".$tabla."Model->cargarPorId(\$_POST['id'])))); \n";
		 $jquery .= "\t } \n";
		return $jquery;
	}
	
	function elsePost(){
		@$jquery .= "}else{ \n \n";
		return $jquery;
	}
	
	function renderTemplate($tabla){
		@$jquery .= "\t echo \$engine->render('".$tabla."', \$aParams); \n exit();";
		return $jquery;
	}
	
	function endPost(){
		@$jquery .= "}";
		return $jquery;
	}
	
	function close_php() {
		@$jquery .= "\n?>";
		return $jquery;
	}
}
?>