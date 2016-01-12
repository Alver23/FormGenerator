<?php 
class conDatabase {
	protected $con;
	public function __construct(){
		global $con;
		$this->con = $con;
	}

	#listamos las tablas
	public function listarTables($db = ""){
		if(empty($db))
			return false;
			
		$sql = $this->con->query("SHOW TABLES FROM ".$db);
		return $sql;
	}
	
	//listar los campos de la base de datos
	public function ListarCampos($table){
		if(empty($table))
			return false;
			
		$sql = $this->con->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table'");	
		return $sql;
	}
	
	public function referencia($tabla){
		if(empty($tabla))
			return false;
			
		$sql = $this->con->query("SHOW COLUMNS FROM $tabla");	
		return $sql;
	}
	
	public function showIndex($tabla){
		if(empty($tabla))
			return false;
		
		return $this->con->query("show columns from $tabla where `Key` = 'PRI'");
	}
	
	public function loadSelect($table){
		if(empty($table))
			return false;
		
		$sql = $this->con->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table'");	
		return $sql;
	}
}
?>