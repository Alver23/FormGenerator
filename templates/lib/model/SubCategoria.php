<?PHP 
class SubCategoria{
	private $idsubcategoria;
	private $tiendaIdtienda;
	private $nombre;
	private $ipAddress;
	private $ownerUserId;
	private $updaterUserId;
	private $createdAt;
	private $updatedAt;
	private $categoriaIdcategoria;
	protected $con;
	public function __construct(){
		$this->con = DBNative::get();
	}
	//Getters

	public function getId(){
		return $this->idsubcategoria;
	}	public function getNombreId(){
		return "idsubcategoria";
	}
	public function getIdsubcategoria(){
		return $this->idsubcategoria;
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
	public function getCategoriaIdcategoria(){
		return $this->categoriaIdcategoria;
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
	public function getByCategoria($categoria_idcategoria){
		return $this->listarObj(array("categoria_idcategoria"=>$categoria_idcategoria));
	}
	public function getCategoria(){
		$categoria = new Categoria($this->con);
		$categoria->cargarPorId($this->categoriaIdcategoria);
		return $categoria;
	}

	//Setters

	public function setIdsubcategoria($idsubcategoria){
		$this->idsubcategoria = $idsubcategoria;
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
	public function setCategoriaIdcategoria($categoriaIdcategoria){
		$this->categoriaIdcategoria = $categoriaIdcategoria;
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
		return (count($this->listar(array("idsubcategoria" => $this->getId())))===1);
	}	
	
	//Guarda o actualiza el objeto en la base de datos, la accion se determina por la clave primaria
	public function save(){
		if(empty($this->idsubcategoria) || !$this->exits()){
			$this->setCommonValuesInsert();
			$this->idsubcategoria = $this->con->autoInsert(array(
			"tienda_idtienda" => $this->tiendaIdtienda,
			"nombre" => $this->nombre,
			"ip_address" => $this->ipAddress,
			"owner_user_id" => $this->ownerUserId,
			"updater_user_id" => $this->updaterUserId,
			"created_at" => $this->createdAt,
			"updated_at" => $this->updatedAt,
			"categoria_idcategoria" => $this->categoriaIdcategoria,
			),"sub_categoria");
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
		"categoria_idcategoria" => $this->categoriaIdcategoria,
		),"sub_categoria",$this->getId());
	}
    
	public function cargarPorId($value,$campowhere = "idsubcategoria",$camp = array()){
		if(!empty($value)){
			if(is_array($camp) && !empty($camp)){
				foreach($camp as $key){
					$fields[] = $key;
					$val[] = lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$key))));
				}
				$fieldslistar = implode(",",$fields);
			}else{
				
				$fields[] = "idsubcategoria";
				$fields[] = "tienda_idtienda";
				$fields[] = "nombre";
				$fields[] = "ip_address";
				$fields[] = "owner_user_id";
				$fields[] = "updater_user_id";
				$fields[] = "created_at";
				$fields[] = "updated_at";
				$fields[] = "categoria_idcategoria";
				$val[] = "idsubcategoria";
				$val[] = "tiendaIdtienda";
				$val[] = "nombre";
				$val[] = "ipAddress";
				$val[] = "ownerUserId";
				$val[] = "updaterUserId";
				$val[] = "createdAt";
				$val[] = "updatedAt";
				$val[] = "categoriaIdcategoria";
				$fieldslistar = implode(",",array_map(array($this->con,"quoteColumn"),$fields));
			}
			$sql = ("SELECT {$fieldslistar} FROM sub_categoria WHERE {$campowhere} = %s");
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
			$campos = $this->con->query("DESCRIBE sub_categoria");
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
		$rows =$this->con->query("SELECT $fields,idsubcategoria FROM `sub_categoria`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["idsubcategoria"]] = $row;
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
			$obj->cargarPorId($row["idsubcategoria"]);
			$rowsr[$row["idsubcategoria"]] = $obj;
		}
		return $rowsr;
	}
	public function delete($value = null,$key = "idsubcategoria"){
		if(!empty($value)){
			$this->setIdsubcategoria($value);
		}
		$value = $this->getId();
		return $this->con->autoDelete("sub_categoria",$key,$value);
	}
}
?>