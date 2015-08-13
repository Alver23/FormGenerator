<?PHP 
class Tienda{
	private $idtienda;
	private $nombre;
	private $direccion;
	private $telefono;
	private $celular;
	private $web;
	private $ipAddress;
	private $ownerUserId;
	private $updaterUserId;
	private $createdAt;
	private $updatedAt;
	protected $con;
	public function __construct(){
		$this->con = DBNative::get();
	}
	//Getters

	public function getId(){
		return $this->idtienda;
	}	public function getNombreId(){
		return "idtienda";
	}
	public function getIdtienda(){
		return $this->idtienda;
	}
	public function getNombre(){
		return $this->nombre;
	}
	public function getDireccion(){
		return $this->direccion;
	}
	public function getTelefono(){
		return $this->telefono;
	}
	public function getCelular(){
		return $this->celular;
	}
	public function getWeb(){
		return $this->web;
	}
	public function getIpAddress(){
		return $this->ipAddress;
	}
	public function getOwnerUserId(){
		return $this->ownerUserId;
	}
	public function getUpdaterUserId(){
		return $this->updaterUserId;
	}
	public function getCreatedAt(){
		return $this->createdAt;
	}
	public function getUpdatedAt(){
		return $this->updatedAt;
	}
	public function getByUser($owner_user_id){
		return $this->listarObj(array("owner_user_id"=>$owner_user_id));
	}
	public function getUser(){
		$user = new User($this->con);
		$user->cargarPorId($this->ownerUserId);
		return $user;
	}
	public function getByUser1($updater_user_id){
		return $this->listarObj(array("updater_user_id"=>$updater_user_id));
	}
	public function getUser1(){
		$user = new User1($this->con);
		$user->cargarPorId($this->updaterUserId);
		return $user;
	}

	//Setters

	public function setIdtienda($idtienda){
		$this->idtienda = $idtienda;
	}
	public function setNombre($nombre){
		$this->nombre = $nombre;
	}
	public function setDireccion($direccion){
		$this->direccion = $direccion;
	}
	public function setTelefono($telefono){
		$this->telefono = $telefono;
	}
	public function setCelular($celular){
		$this->celular = $celular;
	}
	public function setWeb($web){
		$this->web = $web;
	}
	public function setIpAddress($ipAddress){
		$this->ipAddress = $ipAddress;
	}
	public function setOwnerUserId($ownerUserId){
		$this->ownerUserId = $ownerUserId;
	}
	public function setUpdaterUserId($updaterUserId){
		$this->updaterUserId = $updaterUserId;
	}
	public function setCreatedAt($createdAt){
		$this->createdAt = $createdAt;
	}
	public function setUpdatedAt($updatedAt){
		$this->updatedAt = $updatedAt;
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
		return (count($this->listar(array("idtienda" => $this->getId())))===1);
	}	
	
	//Guarda o actualiza el objeto en la base de datos, la accion se determina por la clave primaria
	public function save(){
		if(empty($this->idtienda) || !$this->exits()){
			$this->setCommonValuesInsert();
			$this->idtienda = $this->con->autoInsert(array(
			"nombre" => $this->nombre,
			"direccion" => $this->direccion,
			"telefono" => $this->telefono,
			"celular" => $this->celular,
			"web" => $this->web,
			"ip_address" => $this->ipAddress,
			"owner_user_id" => $this->ownerUserId,
			"updater_user_id" => $this->updaterUserId,
			"created_at" => $this->createdAt,
			"updated_at" => $this->updatedAt,
			),"tienda");
			return;
		}
		$this->setCommonValuesUpdate();
		return $this->con->autoUpdate(array(
		"nombre" => $this->nombre,
		"direccion" => $this->direccion,
		"telefono" => $this->telefono,
		"celular" => $this->celular,
		"web" => $this->web,
		"ip_address" => $this->ipAddress,
		"owner_user_id" => $this->ownerUserId,
		"updater_user_id" => $this->updaterUserId,
		"created_at" => $this->createdAt,
		"updated_at" => $this->updatedAt,
		),"tienda",$this->getId());
	}
    
	public function cargarPorId($value,$campowhere = "idtienda",$camp = array()){
		if(!empty($value)){
			if(is_array($camp) && !empty($camp)){
				foreach($camp as $key){
					$fields[] = $key;
					$val[] = lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$key))));
				}
				$fieldslistar = implode(",",$fields);
			}else{
				
				$fields[] = "idtienda";
				$fields[] = "nombre";
				$fields[] = "direccion";
				$fields[] = "telefono";
				$fields[] = "celular";
				$fields[] = "web";
				$fields[] = "ip_address";
				$fields[] = "owner_user_id";
				$fields[] = "updater_user_id";
				$fields[] = "created_at";
				$fields[] = "updated_at";
				$val[] = "idtienda";
				$val[] = "nombre";
				$val[] = "direccion";
				$val[] = "telefono";
				$val[] = "celular";
				$val[] = "web";
				$val[] = "ipAddress";
				$val[] = "ownerUserId";
				$val[] = "updaterUserId";
				$val[] = "createdAt";
				$val[] = "updatedAt";
				$fieldslistar = implode(",",array_map(array($this->con,"quoteColumn"),$fields));
			}
			$sql = ("SELECT {$fieldslistar} FROM tienda WHERE {$campowhere} = %s");
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
			$campos = $this->con->query("DESCRIBE tienda");
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
		$rows =$this->con->query("SELECT $fields,idtienda FROM `tienda`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["idtienda"]] = $row;
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
			$obj->cargarPorId($row["idtienda"]);
			$rowsr[$row["idtienda"]] = $obj;
		}
		return $rowsr;
	}
	public function delete($value = null,$key = "idtienda"){
		if(!empty($value)){
			$this->setIdtienda($value);
		}
		$value = $this->getId();
		return $this->con->autoDelete("tienda",$key,$value);
	}
}
?>