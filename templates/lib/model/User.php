<?PHP 
class User{
	private $iduser;
	private $name;
	private $email;
	private $password;
	private $celular;
	private $ipAddress;
	private $createdAt;
	private $updaterAt;
	private $isDisabled;
	private $userCookie;
	private $isLoggedIn;
	private $lastAccess;
	protected $con;
	public function __construct(){
		$this->con = DBNative::get();
	}
	//Getters

	public function getId(){
		return $this->iduser;
	}	public function getNombreId(){
		return "iduser";
	}
	public function getIduser(){
		return $this->iduser;
	}
	public function getName(){
		return $this->name;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getPassword(){
		return $this->password;
	}
	public function getCelular(){
		return $this->celular;
	}
	public function getIpAddress(){
		return $this->ipAddress;
	}
	public function getCreatedAt(){
		return $this->createdAt;
	}
	public function getUpdaterAt(){
		return $this->updaterAt;
	}
	public function getIsDisabled(){
		return $this->isDisabled;
	}
	public function getUserCookie(){
		return $this->userCookie;
	}
	public function getIsLoggedIn(){
		return $this->isLoggedIn;
	}
	public function getLastAccess(){
		return $this->lastAccess;
	}

	//Setters

	public function setIduser($iduser){
		$this->iduser = $iduser;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function setEmail($email){
		$this->email = $email;
	}
	public function setPassword($password){
		$this->password = $password;
	}
	public function setCelular($celular){
		$this->celular = $celular;
	}
	public function setIpAddress($ipAddress){
		$this->ipAddress = $ipAddress;
	}
	public function setCreatedAt($createdAt){
		$this->createdAt = $createdAt;
	}
	public function setUpdaterAt($updaterAt){
		$this->updaterAt = $updaterAt;
	}
	public function setIsDisabled($isDisabled){
		$this->isDisabled = $isDisabled;
	}
	public function setUserCookie($userCookie){
		$this->userCookie = $userCookie;
	}
	public function setIsLoggedIn($isLoggedIn){
		$this->isLoggedIn = $isLoggedIn;
	}
	public function setLastAccess($lastAccess){
		$this->lastAccess = $lastAccess;
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
		return (count($this->listar(array("iduser" => $this->getId())))===1);
	}	
	
	//Guarda o actualiza el objeto en la base de datos, la accion se determina por la clave primaria
	public function save(){
		if(empty($this->iduser) || !$this->exits()){
			$this->setCommonValuesInsert();
			$this->iduser = $this->con->autoInsert(array(
			"name" => $this->name,
			"email" => $this->email,
			"password" => $this->password,
			"celular" => $this->celular,
			"ip_address" => $this->ipAddress,
			"created_at" => $this->createdAt,
			"updater_at" => $this->updaterAt,
			"is_disabled" => $this->isDisabled,
			"user_cookie" => $this->userCookie,
			"is_logged_in" => $this->isLoggedIn,
			"last_access" => $this->lastAccess,
			),"user");
			return;
		}
		$this->setCommonValuesUpdate();
		return $this->con->autoUpdate(array(
		"name" => $this->name,
		"email" => $this->email,
		"password" => $this->password,
		"celular" => $this->celular,
		"ip_address" => $this->ipAddress,
		"created_at" => $this->createdAt,
		"updater_at" => $this->updaterAt,
		"is_disabled" => $this->isDisabled,
		"user_cookie" => $this->userCookie,
		"is_logged_in" => $this->isLoggedIn,
		"last_access" => $this->lastAccess,
		),"user",$this->getId());
	}
    
	public function cargarPorId($value,$campowhere = "iduser",$camp = array()){
		if(!empty($value)){
			if(is_array($camp) && !empty($camp)){
				foreach($camp as $key){
					$fields[] = $key;
					$val[] = lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$key))));
				}
				$fieldslistar = implode(",",$fields);
			}else{
				
				$fields[] = "iduser";
				$fields[] = "name";
				$fields[] = "email";
				$fields[] = "password";
				$fields[] = "celular";
				$fields[] = "ip_address";
				$fields[] = "created_at";
				$fields[] = "updater_at";
				$fields[] = "is_disabled";
				$fields[] = "user_cookie";
				$fields[] = "is_logged_in";
				$fields[] = "last_access";
				$val[] = "iduser";
				$val[] = "name";
				$val[] = "email";
				$val[] = "password";
				$val[] = "celular";
				$val[] = "ipAddress";
				$val[] = "createdAt";
				$val[] = "updaterAt";
				$val[] = "isDisabled";
				$val[] = "userCookie";
				$val[] = "isLoggedIn";
				$val[] = "lastAccess";
				$fieldslistar = implode(",",array_map(array($this->con,"quoteColumn"),$fields));
			}
			$sql = ("SELECT {$fieldslistar} FROM user WHERE {$campowhere} = %s");
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
			$campos = $this->con->query("DESCRIBE user");
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
		$rows =$this->con->query("SELECT $fields,iduser FROM `user`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["iduser"]] = $row;
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
			$obj->cargarPorId($row["iduser"]);
			$rowsr[$row["iduser"]] = $obj;
		}
		return $rowsr;
	}
	public function delete($value = null,$key = "iduser"){
		if(!empty($value)){
			$this->setIduser($value);
		}
		$value = $this->getId();
		return $this->con->autoDelete("user",$key,$value);
	}
}
?>