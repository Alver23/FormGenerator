<?PHP 
class DetalleCompra{
	private $iddetalleCompra;
	private $articulosIdarticulos;
	private $facturaCompraIdfacturaCompra;
	private $unidad;
	private $cantidad;
	private $precioUnidad;
	private $totalProducto;
	protected $con;
	public function __construct(){
		$this->con = DBNative::get();
	}
	//Getters

	public function getId(){
		return $this->iddetalleCompra;
	}	public function getNombreId(){
		return "iddetalle_compra";
	}
	public function getIddetalleCompra(){
		return $this->iddetalleCompra;
	}
	public function getArticulosIdarticulos(){
		return $this->articulosIdarticulos;
	}
	public function getFacturaCompraIdfacturaCompra(){
		return $this->facturaCompraIdfacturaCompra;
	}
	public function getUnidad(){
		return $this->unidad;
	}
	public function getCantidad(){
		return $this->cantidad;
	}
	public function getPrecioUnidad(){
		return $this->precioUnidad;
	}
	public function getTotalProducto(){
		return $this->totalProducto;
	}
	public function getByArticulos($articulos_idarticulos){
		return $this->listarObj(array("articulos_idarticulos"=>$articulos_idarticulos));
	}
	public function getArticulos(){
		$articulos = new Articulos($this->con);
		$articulos->cargarPorId($this->articulosIdarticulos);
		return $articulos;
	}
	public function getByFacturaCompra($factura_compra_idfactura_compra){
		return $this->listarObj(array("factura_compra_idfactura_compra"=>$factura_compra_idfactura_compra));
	}
	public function getFacturaCompra(){
		$factura_compra = new FacturaCompra($this->con);
		$factura_compra->cargarPorId($this->facturaCompraIdfacturaCompra);
		return $factura_compra;
	}

	//Setters

	public function setIddetalleCompra($iddetalleCompra){
		$this->iddetalleCompra = $iddetalleCompra;
	}
	public function setArticulosIdarticulos($articulosIdarticulos){
		$this->articulosIdarticulos = $articulosIdarticulos;
	}
	public function setFacturaCompraIdfacturaCompra($facturaCompraIdfacturaCompra){
		$this->facturaCompraIdfacturaCompra = $facturaCompraIdfacturaCompra;
	}
	public function setUnidad($unidad){
		$this->unidad = $unidad;
	}
	public function setCantidad($cantidad){
		$this->cantidad = $cantidad;
	}
	public function setPrecioUnidad($precioUnidad){
		$this->precioUnidad = $precioUnidad;
	}
	public function setTotalProducto($totalProducto){
		$this->totalProducto = $totalProducto;
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
		return (count($this->listar(array("iddetalle_compra" => $this->getId())))===1);
	}	
	
	//Guarda o actualiza el objeto en la base de datos, la accion se determina por la clave primaria
	public function save(){
		if(empty($this->iddetalleCompra) || !$this->exits()){
			$this->setCommonValuesInsert();
			$this->iddetalleCompra = $this->con->autoInsert(array(
			"articulos_idarticulos" => $this->articulosIdarticulos,
			"factura_compra_idfactura_compra" => $this->facturaCompraIdfacturaCompra,
			"unidad" => $this->unidad,
			"cantidad" => $this->cantidad,
			"precio_unidad" => $this->precioUnidad,
			"total_producto" => $this->totalProducto,
			),"detalle_compra");
			return;
		}
		$this->setCommonValuesUpdate();
		return $this->con->autoUpdate(array(
		"articulos_idarticulos" => $this->articulosIdarticulos,
		"factura_compra_idfactura_compra" => $this->facturaCompraIdfacturaCompra,
		"unidad" => $this->unidad,
		"cantidad" => $this->cantidad,
		"precio_unidad" => $this->precioUnidad,
		"total_producto" => $this->totalProducto,
		),"detalle_compra",$this->getId());
	}
    
	public function cargarPorId($value,$campowhere = "iddetalle_compra",$camp = array()){
		if(!empty($value)){
			if(is_array($camp) && !empty($camp)){
				foreach($camp as $key){
					$fields[] = $key;
					$val[] = lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$key))));
				}
				$fieldslistar = implode(",",$fields);
			}else{
				
				$fields[] = "iddetalle_compra";
				$fields[] = "articulos_idarticulos";
				$fields[] = "factura_compra_idfactura_compra";
				$fields[] = "unidad";
				$fields[] = "cantidad";
				$fields[] = "precio_unidad";
				$fields[] = "total_producto";
				$val[] = "iddetalleCompra";
				$val[] = "articulosIdarticulos";
				$val[] = "facturaCompraIdfacturaCompra";
				$val[] = "unidad";
				$val[] = "cantidad";
				$val[] = "precioUnidad";
				$val[] = "totalProducto";
				$fieldslistar = implode(",",array_map(array($this->con,"quoteColumn"),$fields));
			}
			$sql = ("SELECT {$fieldslistar} FROM detalle_compra WHERE {$campowhere} = %s");
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
			$campos = $this->con->query("DESCRIBE detalle_compra");
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
		$rows =$this->con->query("SELECT $fields,iddetalle_compra FROM `detalle_compra`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["iddetalle_compra"]] = $row;
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
			$obj->cargarPorId($row["iddetalle_compra"]);
			$rowsr[$row["iddetalle_compra"]] = $obj;
		}
		return $rowsr;
	}
	public function delete($value = null,$key = "iddetalle_compra"){
		if(!empty($value)){
			$this->setIddetalleCompra($value);
		}
		$value = $this->getId();
		return $this->con->autoDelete("detalle_compra",$key,$value);
	}
}
?>