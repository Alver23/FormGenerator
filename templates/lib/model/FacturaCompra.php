<?PHP 
class FacturaCompra{
	private $idfacturaCompra;
	private $tiendaIdtienda;
	private $proveedoresIdproveedores;
	private $fechaFactura;
	private $fechaVencimiento;
	private $formaPago;
	private $totalBruto;
	private $descuento1;
	private $descuento2;
	private $subtotal;
	private $ivaPorcentaje;
	private $ivaValor;
	private $retefuente;
	private $reteiva;
	private $reteica;
	private $totalNeto;
	private $ipAddres;
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
		return $this->idfacturaCompra;
	}	public function getNombreId(){
		return "idfactura_compra";
	}
	public function getIdfacturaCompra(){
		return $this->idfacturaCompra;
	}
	public function getTiendaIdtienda(){
		return $this->tiendaIdtienda;
	}
	public function getProveedoresIdproveedores(){
		return $this->proveedoresIdproveedores;
	}
	public function getFechaFactura(){
		return $this->fechaFactura;
	}
	public function getFechaVencimiento(){
		return $this->fechaVencimiento;
	}
	public function getFormaPago(){
		return $this->formaPago;
	}
	public function getTotalBruto(){
		return $this->totalBruto;
	}
	public function getDescuento1(){
		return $this->descuento1;
	}
	public function getDescuento2(){
		return $this->descuento2;
	}
	public function getSubtotal(){
		return $this->subtotal;
	}
	public function getIvaPorcentaje(){
		return $this->ivaPorcentaje;
	}
	public function getIvaValor(){
		return $this->ivaValor;
	}
	public function getRetefuente(){
		return $this->retefuente;
	}
	public function getReteiva(){
		return $this->reteiva;
	}
	public function getReteica(){
		return $this->reteica;
	}
	public function getTotalNeto(){
		return $this->totalNeto;
	}
	public function getIpAddres(){
		return $this->ipAddres;
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
	public function getByProveedores($proveedores_idproveedores){
		return $this->listarObj(array("proveedores_idproveedores"=>$proveedores_idproveedores));
	}
	public function getProveedores(){
		$proveedores = new Proveedores($this->con);
		$proveedores->cargarPorId($this->proveedoresIdproveedores);
		return $proveedores;
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

	public function setIdfacturaCompra($idfacturaCompra){
		$this->idfacturaCompra = $idfacturaCompra;
	}
	public function setTiendaIdtienda($tiendaIdtienda){
		$this->tiendaIdtienda = $tiendaIdtienda;
	}
	public function setProveedoresIdproveedores($proveedoresIdproveedores){
		$this->proveedoresIdproveedores = $proveedoresIdproveedores;
	}
	public function setFechaFactura($fechaFactura){
		$this->fechaFactura = $fechaFactura;
	}
	public function setFechaVencimiento($fechaVencimiento){
		$this->fechaVencimiento = $fechaVencimiento;
	}
	public function setFormaPago($formaPago){
		$this->formaPago = $formaPago;
	}
	public function setTotalBruto($totalBruto){
		$this->totalBruto = $totalBruto;
	}
	public function setDescuento1($descuento1){
		$this->descuento1 = $descuento1;
	}
	public function setDescuento2($descuento2){
		$this->descuento2 = $descuento2;
	}
	public function setSubtotal($subtotal){
		$this->subtotal = $subtotal;
	}
	public function setIvaPorcentaje($ivaPorcentaje){
		$this->ivaPorcentaje = $ivaPorcentaje;
	}
	public function setIvaValor($ivaValor){
		$this->ivaValor = $ivaValor;
	}
	public function setRetefuente($retefuente){
		$this->retefuente = $retefuente;
	}
	public function setReteiva($reteiva){
		$this->reteiva = $reteiva;
	}
	public function setReteica($reteica){
		$this->reteica = $reteica;
	}
	public function setTotalNeto($totalNeto){
		$this->totalNeto = $totalNeto;
	}
	public function setIpAddres($ipAddres){
		$this->ipAddres = $ipAddres;
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
		return (count($this->listar(array("idfactura_compra" => $this->getId())))===1);
	}	
	
	//Guarda o actualiza el objeto en la base de datos, la accion se determina por la clave primaria
	public function save(){
		if(empty($this->idfacturaCompra) || !$this->exits()){
			$this->setCommonValuesInsert();
			$this->idfacturaCompra = $this->con->autoInsert(array(
			"tienda_idtienda" => $this->tiendaIdtienda,
			"proveedores_idproveedores" => $this->proveedoresIdproveedores,
			"fecha_factura" => $this->fechaFactura,
			"fecha_vencimiento" => $this->fechaVencimiento,
			"forma_pago" => $this->formaPago,
			"total_bruto" => $this->totalBruto,
			"descuento1" => $this->descuento1,
			"descuento2" => $this->descuento2,
			"subtotal" => $this->subtotal,
			"iva_porcentaje" => $this->ivaPorcentaje,
			"iva_valor" => $this->ivaValor,
			"retefuente" => $this->retefuente,
			"reteiva" => $this->reteiva,
			"reteica" => $this->reteica,
			"total_neto" => $this->totalNeto,
			"ip_addres" => $this->ipAddres,
			"owner_user_id" => $this->ownerUserId,
			"updater_user_id" => $this->updaterUserId,
			"created_at" => $this->createdAt,
			"updated_at" => $this->updatedAt,
			),"factura_compra");
			return;
		}
		$this->setCommonValuesUpdate();
		return $this->con->autoUpdate(array(
		"tienda_idtienda" => $this->tiendaIdtienda,
		"proveedores_idproveedores" => $this->proveedoresIdproveedores,
		"fecha_factura" => $this->fechaFactura,
		"fecha_vencimiento" => $this->fechaVencimiento,
		"forma_pago" => $this->formaPago,
		"total_bruto" => $this->totalBruto,
		"descuento1" => $this->descuento1,
		"descuento2" => $this->descuento2,
		"subtotal" => $this->subtotal,
		"iva_porcentaje" => $this->ivaPorcentaje,
		"iva_valor" => $this->ivaValor,
		"retefuente" => $this->retefuente,
		"reteiva" => $this->reteiva,
		"reteica" => $this->reteica,
		"total_neto" => $this->totalNeto,
		"ip_addres" => $this->ipAddres,
		"owner_user_id" => $this->ownerUserId,
		"updater_user_id" => $this->updaterUserId,
		"created_at" => $this->createdAt,
		"updated_at" => $this->updatedAt,
		),"factura_compra",$this->getId());
	}
    
	public function cargarPorId($value,$campowhere = "idfactura_compra",$camp = array()){
		if(!empty($value)){
			if(is_array($camp) && !empty($camp)){
				foreach($camp as $key){
					$fields[] = $key;
					$val[] = lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$key))));
				}
				$fieldslistar = implode(",",$fields);
			}else{
				
				$fields[] = "idfactura_compra";
				$fields[] = "tienda_idtienda";
				$fields[] = "proveedores_idproveedores";
				$fields[] = "fecha_factura";
				$fields[] = "fecha_vencimiento";
				$fields[] = "forma_pago";
				$fields[] = "total_bruto";
				$fields[] = "descuento1";
				$fields[] = "descuento2";
				$fields[] = "subtotal";
				$fields[] = "iva_porcentaje";
				$fields[] = "iva_valor";
				$fields[] = "retefuente";
				$fields[] = "reteiva";
				$fields[] = "reteica";
				$fields[] = "total_neto";
				$fields[] = "ip_addres";
				$fields[] = "owner_user_id";
				$fields[] = "updater_user_id";
				$fields[] = "created_at";
				$fields[] = "updated_at";
				$val[] = "idfacturaCompra";
				$val[] = "tiendaIdtienda";
				$val[] = "proveedoresIdproveedores";
				$val[] = "fechaFactura";
				$val[] = "fechaVencimiento";
				$val[] = "formaPago";
				$val[] = "totalBruto";
				$val[] = "descuento1";
				$val[] = "descuento2";
				$val[] = "subtotal";
				$val[] = "ivaPorcentaje";
				$val[] = "ivaValor";
				$val[] = "retefuente";
				$val[] = "reteiva";
				$val[] = "reteica";
				$val[] = "totalNeto";
				$val[] = "ipAddres";
				$val[] = "ownerUserId";
				$val[] = "updaterUserId";
				$val[] = "createdAt";
				$val[] = "updatedAt";
				$fieldslistar = implode(",",array_map(array($this->con,"quoteColumn"),$fields));
			}
			$sql = ("SELECT {$fieldslistar} FROM factura_compra WHERE {$campowhere} = %s");
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
			$campos = $this->con->query("DESCRIBE factura_compra");
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
		$rows =$this->con->query("SELECT $fields,idfactura_compra FROM `factura_compra`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["idfactura_compra"]] = $row;
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
			$obj->cargarPorId($row["idfactura_compra"]);
			$rowsr[$row["idfactura_compra"]] = $obj;
		}
		return $rowsr;
	}
	public function delete($value = null,$key = "idfactura_compra"){
		if(!empty($value)){
			$this->setIdfacturaCompra($value);
		}
		$value = $this->getId();
		return $this->con->autoDelete("factura_compra",$key,$value);
	}
}
?>