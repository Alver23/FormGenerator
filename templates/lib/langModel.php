<?php 
 
class langModel extends Lang{ 
	 public function _construct(){ 
	 	 parent::__construct(); 
	 }
 	 public function getPager(Array $columns, Array $filters = array()){ 
	 	 $whereA = array(); 
	 	 foreach($filters as $filter => $value) 
	 	 $whereA[] = $filter." = ".$this->con->quote($value); 
 
	 	 $where = implode('AND', $whereA); 
	 	 if($where == '') 
	 	  	 $where = 1; 
	 	 $pager = new Pager($this->con, 
	 	 "(SELECT idlang,name,code 
	 	 	 FROM lang 
	 	 	 WHERE {$where}) a ", $columns, $this->getNombreId()); 
	 	 return $pager; 
	 }
 
}
?>