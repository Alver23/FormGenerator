<?PHP 
class Categoria{
	private $idcategoria;
	private $tiendaIdtienda;
	private $nombre;
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
		return $this->idcategoria;
	}	public function getNombreId(){
		return "idcategoria";
	}
	public function getIdcategoria(){
		return $this->idcategoria;
	}
	public function getTiendaIdtienda(){
		return $this->tiendaIdtienda;
	}
	public function getNombre(){
		return $this->nombre;
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
	public function getByTienda($tienda_idtienda){
		return $this->listarObj(array("tienda_idtienda"=>$tienda_idtienda));
	}
	public function getTienda(){
		$tienda = new Tienda($this->con);
		$tienda->cargarPorId($this->tiendaIdtienda);
		return $tienda;
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

	public function setIdcategoria($idcategoria){
		$this->idcategoria = $idcategoria;
	}
	public function setTiendaIdtienda($tiendaIdtienda){
		$this->tiendaIdtienda = $tiendaIdtienda;
	}
	public function setNombre($nombre){
		$this->nombre = $nombre;
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
		return (count($this->listar(array("idcategoria" => $this->getId())))===1);
	}	
	
	//Guarda o actualiza el objeto en la base de datos, la accion se determina por la clave primaria
	public function save(){
		if(empty($this->idcategoria) || !$this->exits()){
			$this->setCommonValuesInsert();
			$this->idcategoria = $this->con->autoInsert(array(
			"tienda_idtienda" => $this->tiendaIdtienda,
			"nombre" => $this->nombre,
			"ip_address" => $this->ipAddress,
			"owner_user_id" => $this->ownerUserId,
			"updater_user_id" => $this->updaterUserId,
			"created_at" => $this->createdAt,
			"updated_at" => $this->updatedAt,
			),"categoria");
			return;
		}
		$this->setCommonValuesUpdate();
		return $this->con->autoUpdate(array(
		"tienda_idtienda" => $this->tiendaIdtienda,
		"nombre" => $this->nombre,
		"ip_address" => $this->ipAddress,
		"owner_user_id" => $this->ownerUserId,
		"updater_user_id" => $this->updaterUserId,
		"created_at" => $this->createdAt,
		"updated_at" => $this->updatedAt,
		),"categoria",$this->getId());
	}
    
	public function cargarPorId($value,$campowhere = "idcategoria",$camp = array()){
		if(!empty($value)){
			if(is_array($camp) && !empty($camp)){
				foreach($camp as $key){
					$fields[] = $key;
					$val[] = lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$key))));
				}
				$fieldslistar = implode(",",$fields);
			}else{
				
				$fields[] = "idcategoria";
				$fields[] = "tienda_idtienda";
				$fields[] = "nombre";
				$fields[] = "ip_address";
				$fields[] = "owner_user_id";
				$fields[] = "updater_user_id";
				$fields[] = "created_at";
				$fields[] = "updated_at";
				$val[] = "idcategoria";
				$val[] = "tiendaIdtienda";
				$val[] = "nombre";
				$val[] = "ipAddress";
				$val[] = "ownerUserId";
				$val[] = "updaterUserId";
				$val[] = "createdAt";
				$val[] = "updatedAt";
				$fieldslistar = implode(",",array_map(array($this->con,"quoteColumn"),$fields));
			}
			$sql = ("SELECT {$fieldslistar} FROM categoria WHERE {$campowhere} = %s");
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
			$campos = $this->con->query("DESCRIBE categoria");
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
		$rows =$this->con->query("SELECT $fields,idcategoria FROM `categoria`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["idcategoria"]] = $row;
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
			$obj->cargarPorId($row["idcategoria"]);
			$rowsr[$row["idcategoria"]] = $obj;
		}
		return $rowsr;
	}
	public function delete($value = null,$key = "idcategoria"){
		if(!empty($value)){
			$this->setIdcategoria($value);
		}
		$value = $this->getId();
		return $this->con->autoDelete("categoria",$key,$value);
	}
}
?>