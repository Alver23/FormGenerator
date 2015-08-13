<?php
#Created by Daniel Felipe Lucumi Marin
#E-mail: dflm25@gmail.com
#MSN: dannycali@hotmail.com
#CALI - COLOMBIA
class Model{
	
	function openModel($tabla){
		$query = "<?php \n \n";
		$query .= "class ".$tabla."Model extends ".ucwords($tabla)."{ \n";
		$query .= "\t public function _construct(){ \n";
		$query .= "\t \t parent::__construct(); \n";
		$query .= "\t }";	
		return $query;
	}	
	
	function modelPaginate($tabla, $campos, $opt){
		$cant = count($campos); $salida = $query = "";
		for($x=0; $x<=$cant-1; $x++){
			if($opt[$x]=="si"){
			  $salida[] .= $campos[$x];
			}
		}
		if($salida){
			$query .= "\n \t public function getPager(Array \$columns, Array \$filters = array()){ \n";
			$query .= "\t \t \$whereA = array(); \n";
			$query .= "\t \t foreach(\$filters as \$filter => \$value) \n";
			$query .= "\t \t \$whereA[] = \$filter.\" = \".\$this->con->quote(\$value); \n \n";
			$query .= "\t \t \$where = implode('AND', \$whereA); \n";
		
			$query .= "\t \t if(\$where == '') \n";
			$query .= "\t \t  \t \$where = 1; \n";
				
			$query .= "\t \t \$pager = new Pager(\$this->con, \n";
			
			$query .= "\t \t \"(SELECT ".implode(",", $salida)." \n";
			$query .= "\t \t \t FROM ".$tabla." \n";
			$query .= "\t \t \t WHERE {\$where}) a \", \$columns, \$this->getNombreId()); \n";
			$query .= "\t \t return \$pager; \n";
			$query .= "\t }";
		}
		return $query;
	}
	
	function selectLoad($tabla){
		$query .= "\t public function loadSelect(\$tabla){ \n";
		$query .= "\t \t if(empty($tabla)) \n";
		$query .= " \t \t \t return false; \n";
		$query .= "\t \t return $this->con->query(\"SELECT * FROM $tabla\"); \n";
		$query .= "\t } \n";
	}
	
	function closeModel(){
		return "\n \n}\n?>";
	}
	
}
?>	