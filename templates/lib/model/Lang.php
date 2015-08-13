<?PHP 
class Lang{
	private $idlang;
	private $name;
	private $code;
	protected $con;
	public function __construct(){
		$this->con = DBNative::get();
	}
	//Getters

	public function getId(){
		return $this->idlang;
	}	public function getNombreId(){
		return "idlang";
	}
	public function getIdlang(){
		return $this->idlang;
	}
	public function getName(){
		return $this->name;
	}
	public function getCode(){
		return $this->code;
	}

	//Setters

	public function setIdlang($idlang){
		$this->idlang = $idlang;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function setCode($code){
		$this->code = $code;
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
		return (count($this->listar(array("idlang" => $this->getId())))===1);
	}	
	
	//Guarda o actualiza el objeto en la base de datos, la accion se determina por la clave primaria
	public function save(){
		if(empty($this->idlang) || !$this->exits()){
			$this->setCommonValuesInsert();
			$this->idlang = $this->con->autoInsert(array(
			"name" => $this->name,
			"code" => $this->code,
			),"lang");
			return;
		}
		$this->setCommonValuesUpdate();
		return $this->con->autoUpdate(array(
		"name" => $this->name,
		"code" => $this->code,
		),"lang",$this->getId());
	}
    
	public function cargarPorId($value,$campowhere = "idlang",$camp = array()){
		if(!empty($value)){
			if(is_array($camp) && !empty($camp)){
				foreach($camp as $key){
					$fields[] = $key;
					$val[] = lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$key))));
				}
				$fieldslistar = implode(",",$fields);
			}else{
				
				$fields[] = "idlang";
				$fields[] = "name";
				$fields[] = "code";
				$val[] = "idlang";
				$val[] = "name";
				$val[] = "code";
				$fieldslistar = implode(",",array_map(array($this->con,"quoteColumn"),$fields));
			}
			$sql = ("SELECT {$fieldslistar} FROM lang WHERE {$campowhere} = %s");
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
			$campos = $this->con->query("DESCRIBE lang");
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
		$rows =$this->con->query("SELECT $fields,idlang FROM `lang`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["idlang"]] = $row;
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
			$obj->cargarPorId($row["idlang"]);
			$rowsr[$row["idlang"]] = $obj;
		}
		return $rowsr;
	}
	public function delete($value = null,$key = "idlang"){
		if(!empty($value)){
			$this->setIdlang($value);
		}
		$value = $this->getId();
		return $this->con->autoDelete("lang",$key,$value);
	}
}
?>