<?PHP 
class Articulos{
	private $idarticulos;
	private $nombre;
	private $subIdsubcategoria;
	protected $con;
	public function __construct(){
		$this->con = DBNative::get();
	}
	//Getters

	public function getId(){
		return $this->idarticulos;
	}	public function getNombreId(){
		return "idarticulos";
	}
	public function getIdarticulos(){
		return $this->idarticulos;
	}
	public function getNombre(){
		return $this->nombre;
	}
	public function getSubIdsubcategoria(){
		return $this->subIdsubcategoria;
	}
	public function getBySubCategoria($sub_idsubcategoria){
		return $this->listarObj(array("sub_idsubcategoria"=>$sub_idsubcategoria));
	}
	public function getSubCategoria(){
		$sub_categoria = new SubCategoria($this->con);
		$sub_categoria->cargarPorId($this->subIdsubcategoria);
		return $sub_categoria;
	}

	//Setters

	public function setIdarticulos($idarticulos){
		$this->idarticulos = $idarticulos;
	}
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}
	public function setSubIdsubcategoria($subIdsubcategoria){
		$this->subIdsubcategoria = $subIdsubcategoria;
	}
	//LLena todos los atributos de la clase sacando los valores de un array
	function setValues($array){
		foreach($array as $key => $val){
			$key = lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$key))));
			if(property_exists($this,$key))
				$this->$key = $val;
		}
	}
	
	//Comprobar si el objeto esta en la base de datos
	public function exits(){
		return (count($this->listar(array("idarticulos" => $this->getId())))===1);
	}	
	
	//Guarda o actualiza el objeto en la base de datos, la accion se determina por la clave primaria
	public function save(){
		if(empty($this->idarticulos) || !$this->exits()){
			$this->setCommonValuesInsert();
			$this->idarticulos = $this->con->autoInsert(array(
			"nombre" => $this->nombre,
			"sub_idsubcategoria" => $this->subIdsubcategoria,
			),"articulos");
			return;
		}
		$this->setCommonValuesUpdate();
		return $this->con->autoUpdate(array(
		"nombre" => $this->nombre,
		"sub_idsubcategoria" => $this->subIdsubcategoria,
		),"articulos",$this->getId());
	}
    
	public function cargarPorId($value,$campowhere = "idarticulos",$camp = array()){
		if(!empty($value)){
			if(is_array($camp) && !empty($camp)){
				foreach($camp as $key){
					$fields[] = $key;
					$val[] = lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$key))));
				}
				$fieldslistar = implode(",",$fields);
			}else{
				
				$fields[] = "idarticulos";
				$fields[] = "nombre";
				$fields[] = "sub_idsubcategoria";
				$val[] = "idarticulos";
				$val[] = "nombre";
				$val[] = "subIdsubcategoria";
				$fieldslistar = implode(",",array_map(array($this->con,"quoteColumn"),$fields));
			}
			$sql = ("SELECT {$fieldslistar} FROM articulos WHERE {$campowhere} = %s");
			$tSql = sprintf($sql,$this->con->quote($value));
			$result = $this->con->query($tSql);
			$cant = COUNT($val);
			if(COUNT($result) > 0){
				for($i = 0;$i < $cant;$i++){
					$this->$val[$i] = $result[0]["{$fields[$i]}"];
				}
				return $result[0];
			}else{
				return false;
			}
		}
	}
	public function getIp() {
		if(function_exists("getIp"))
			return getIp();
		$ipAddr = ! empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER ['HTTP_X_FORWARDED_FOR'] : $_SERVER ["REMOTE_ADDR"];
		// fix multiple
		$tmp = explode ( ",", $ipAddr );
		$ipAddr = array_shift ( $tmp );
		if($ipAddr == '')//console, crontab
			$ipAddr = "127.0.0.1";
		return $ipAddr;

	}
	private function setCommonValuesInsert(){
 		global $session;
		if(@$session->userInfo["user_id"] == '')
			$session->userInfo["user_id"] = 1;
		$this->setOwnerUserId($session->userInfo["user_id"]);
		$this->setCreatedAt(date("Y-m-d H:i:s"));
		$this->setIpAddress($this->getIp());
		$this->setCommonValuesUpdate();

	}
	private function setCommonValuesUpdate(){
		global $session;
		if(@$session->userInfo["user_id"] == '')
			$session->userInfo["user_id"] = 1;
		$this->setUpdaterUserId($session->userInfo["user_id"]);
		$this->setUpdatedAt(date("Y-m-d H:i:s"));
		$this->setIpAddress($this->getIp());
	}
	public function listar($filtros = array(), $orderBy = '', $limit = "0,30", $exactMatch = false, $fields = '*', $idInKeys = true){
		$whereA = array();
		if(!$exactMatch){
			$campos = $this->con->query("DESCRIBE articulos");
			$listicos = array();
			foreach($campos as $campo){
				$tmp = explode("(",$campo["Type"]);
				$listicos[$campo["Field"]] = $tmp[0];
			}
			foreach($filtros as $filtro => $valor){
				if($valor === NULL){
					$whereA[] = $filtro." IS NULL";
					continue;
				} 
				if($listicos[$filtro] == "int")
					$whereA[] = $filtro." = ".floatval($valor);
				else
					$whereA[] = $filtro." LIKE '%".$this->con->escape($valor)."%'";			
			}

		}else{
			foreach($filtros as $filtro => $valor){
				if($valor === NULL){
					$whereA[] = $filtro." IS NULL";
					continue;
				}
				$whereA[] = $filtro." = ".$this->con->quote($valor);
			}
		}
		$where = implode(" AND ",$whereA);
		if($where == '')
			$where = 1;
		if ($orderBy != "")
			$orderBy = "ORDER BY $orderBy";
		$rows =$this->con->query("SELECT $fields,idarticulos FROM `articulos`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["idarticulos"]] = $row;
            else
            	$rowsI[] = $row;
		}
		return $rowsI;
	}
	//como listar, pero retorna un array de objetos
	function listarObj($filtros = array(), $orderBy = '', $limit = "0,30", $exactMatch = false, $fields = '*'){
		$rowsr = array();
		$rows = $this->listar($filtros, $orderBy, $limit, $exactMatch, '*');
		foreach($rows as $row){
			$obj = clone $this;
			$obj->cargarPorId($row["idarticulos"]);
			$rowsr[$row["idarticulos"]] = $obj;
		}
		return $rowsr;
	}
	public function delete($value = null,$key = "idarticulos"){
		if(!empty($value)){
			$this->setIdarticulos($value);
		}
		$value = $this->getId();
		return $this->con->autoDelete("articulos",$key,$value);
	}
}
?>