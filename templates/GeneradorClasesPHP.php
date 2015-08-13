<?PHP // Class Generator
error_reporting(E_ALL);
date_default_timezone_set("America/Bogota");
require_once "lib/DBNative.php";
require_once "lib/utilities.php";


$archivo = dirname(__FILE__)."/app/ConfigDatabases/databases.ini";
if (file_exists($archivo) && is_readable($archivo)) {
	if (!$ajustes = parse_ini_file($archivo, true)) throw new exception ('No se puede abrir el archivo ' . $archivo . '.');
	//Conexion al SERVER
	define("DB_SERVER",         $ajustes["remote_database"]["server"]);
    define("DB_NAME",           $ajustes["remote_database"]["name"]);
    define("DB_USER",           $ajustes["remote_database"]["user"]);
    define("DB_PASS",           $ajustes["remote_database"]["password"]);
    define("DB_DRIVER",         $ajustes["remote_database"]["driver"]);
    define("DB_PORT",           $ajustes["remote_database"]["port"]);
    //Conexion Local
    define("DB_SERVER_LOCAL",   $ajustes["local_database"]["server"]);
    define("DB_NAME_LOCAL",     $ajustes["local_database"]["name"]);
    define("DB_USER_LOCAL",     $ajustes["local_database"]["user"]);
    define("DB_PASS_LOCAL",     $ajustes["local_database"]["password"]);
    define("DB_DRIVER_LOCAL",   $ajustes["local_database"]["driver"]);
    define("DB_PORT_LOCAL",     $ajustes["local_database"]["port"]);
}else{
	print "Archivo no encontrado";
}
if (in_array($_SERVER['SERVER_ADDR'], array("127.0.0.1", "localhost"))){
	$mode = "local";
	//define("DSN", "mysql://".DB_USER_LOCAL.":".DB_PASS_LOCAL."@".DB_SERVER_LOCAL."/".DB_NAME_LOCAL);
	define("DSN",DB_DRIVER_LOCAL."://".DB_USER_LOCAL.":".DB_PASS_LOCAL."@".DB_SERVER_LOCAL.":".DB_PORT_LOCAL."/".DB_NAME_LOCAL);
}else{
	$mode = "remote";
	define("DSN",DB_DRIVER."://".DB_USER.":".DB_PASS."@".DB_SERVER.":".DB_PORT."/".DB_NAME);
}

$con =  DBNative::get(DSN);
$inicio = microtime ( true );
function printCode($source_code) {
	if (is_array ( $source_code ))
		return false;
	
	$source_code = explode ( "\n", str_replace ( array (
			"\r\n",
			"\r" 
	), "\n", $source_code ) );
	$line_count = 1;
	$formatted_code = '';
	foreach ( $source_code as $code_line ) {
		$formatted_code .= '<tr><td>' . $line_count . '</td>';
		$line_count ++;
		
		if (preg_match ( '/<\?(php)?[^[:graph:]]/', $code_line ))
			$formatted_code .= '<td>' . str_replace ( array (
					'<code>',
					'</code>' 
			), '', highlight_string ( $code_line, true ) ) . '</td></tr>';
		else
			$formatted_code .= '<td>' . @ereg_replace ( '(&lt;\?php&nbsp;)+', '', str_replace ( array (
					'<code>',
					'</code>' 
			), '', highlight_string ( '<?php ' . $code_line, true ) ) ) . '</td></tr>';
	}
	
	return '<table style="font: 1em Consolas, \'andale mono\', \'monotype.com\', \'lucida console\', monospace;">' . $formatted_code . '</table>';
}
$tablas = $con->query ( "SHOW TABLES" );
$contenidos = array ();
foreach ( $tablas as $tabla ) {
	$tabla = $tabla ["Tables_in_".DB_NAME];
	/*if($tabla =="Statics"){
		continue;
	}*/
	$campos = $con->query ( "DESCRIBE $tabla" );
	
	// Poner en un array los campos pulpitos
	$camposP = array ();
	$primarias = array ();
	foreach ( $campos as $campo ) {
		if ($campo ["Key"] != "PRI")
			$camposP [] = $campo ["Field"];
		else {
			$primarias [] = $campo ["Field"];
			$primary = $campo ["Field"];
		}
	}
	if (empty ( $primary )) {
		echo "Saltando tabla {$tabla}: No tiene una clave primaria<br />";
		continue;
	}
	if (count ( $primarias ) > 1) {
		echo "Saltando tabla {$tabla}: Contiene " . count ( $primarias ) . " campos (" . implode ( ", ", $primarias ) . ") como clave primaria 
		<a href='http://trac.propelorm.org/ticket/359'>Propel</a> - 
		<a href='http://docs.doctrine-project.org/projects/doctrine-orm/en/2.0.x/tutorials/composite-primary-keys.html'>Doctrine</a><br />";
		continue;
	}
	// Inteligencia artificial
	$create = $con->query ( "SHOW CREATE TABLE `$tabla`" );
	if (! isset ( $create [0] ["Create Table"] )) {
		echo "Saltando $tabla por que no es un base tabla<br />";
		continue;
	}
	$lineas = explode ( "\n", $create [0] ["Create Table"] );
	$foraneas = array ();
	// echo "<pre>".print_r($lineas,true)."</pre>";
	foreach ( $lineas as $linea ) {
		if (strpos ( $linea, "CONSTRAINT" ) !== false) 		// posicion cero
		{
			// Parsear
			$pos = strpos ( $linea, "FOREIGN KEY (`" ) + 14;
			$tmp = substr ( $linea, $pos );
			$pos = strpos ( $tmp, "`) REFERENCES `" );
			$campo = substr ( $tmp, 0, $pos ); // Listo el campo
			                              // echo $campo." --> ";
			$tmp = substr ( $tmp, $pos + 15 );
			$pos = strpos ( $tmp, "` (`" );
			$tablatmp = substr ( $tmp, 0, $pos ); // Lista la tabla a la que referencia
			$campor = substr ( $tmp, $pos + 4, strpos ( $tmp, "`) " ) - ($pos + 4) );
			//if reference other database
			if(strpos($campor, "`.`")!==false){
				$tmpcampor = explode("`.`", $campor);
				$campor = $tmpcampor[1];
			}
			if(strpos($tablatmp, "`.`")!==false){
				$tmptablatmp = explode("`.`", $tablatmp);
				$tablatmp = $tmptablatmp[1];
			}
			// echo $campor."<br />";
			$foraneas [$campo] = array (
					"tabla" => $tablatmp,
					"campo" => $campor 
			);
		}
	}
	ob_start ();
	$clase = str_replace ( " ", "", ucwords ( str_replace ( "_", " ", $tabla ) ) );
	?>

class <?PHP echo $clase;?>{
<?PHP
	foreach ( $campos as $campo ) {
		?>
	private $<?PHP echo lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$campo["Field"]))));?>;
<?PHP
	} // foreach de campos
	?>
	protected $con;
	public function __construct(){
		$this->con = DBNative::get();
	}
	//Getters

<?PHP
	foreach ( $campos as $campo ) {
		?><?PHP

		if ($campo ["Field"] == $primary) 		// Un lindo "alias"
		{
			if ($campo ["Field"] != "id") {
				?>
	public function getId(){
		return $this-><?PHP echo lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$campo["Field"]))));?>;
	}<?PHP } ?>
	public function getNombreId(){
		return "<?PHP echo $campo["Field"];?>";
	}
<?PHP
		}
		?>
	public function get<?PHP echo str_replace(" ","",ucwords(str_replace("_"," ",$campo["Field"])));?>(){
		return $this-><?PHP echo lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$campo["Field"]))));?>;
	}
<?PHP
	} // foreach de campos
	$tablasForaneas = array ();
	foreach ( $foraneas as $campo => $foranea ) {
		$nombreCampo = str_replace ( " ", "", ucwords ( str_replace ( "_", " ", $foranea ["tabla"] ) ) );
		$c = @$tablasForaneas [$nombreCampo];
		@$tablasForaneas [$nombreCampo] += 1;
		if ($c > 0) {
			$nombreCampo .= $c;
		}
		?>
	public function getBy<?PHP echo $nombreCampo;?>($<?PHP echo $campo;?>){
		return $this->listarObj(array("<?PHP echo $campo;?>"=>$<?PHP echo $campo;?>));
	}
	public function get<?PHP echo $nombreCampo;?>(){
		$<?PHP echo lcfirst($foranea["tabla"])?> = new <?PHP echo $nombreCampo;?>($this->con);
		$<?PHP echo lcfirst($foranea["tabla"])?>->cargarPorId($this-><?PHP echo lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$campo))));?>);
		return $<?PHP echo lcfirst($foranea["tabla"])?>;
	}
<?PHP } ?>

	//Setters

<?PHP
	foreach ( $campos as $campo ) {
		?>
	public function set<?PHP echo str_replace(" ","",ucwords(str_replace("_"," ",$campo["Field"])));?>($<?PHP echo lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$campo["Field"]))));?>){
		$this-><?PHP echo lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$campo["Field"]))));?> = $<?PHP echo lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$campo["Field"]))));?>;
	}
<?PHP
	} // foreach de campos
	?>
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
		return (count($this->listar(array("<?PHP echo $primary; ?>" => $this->getId())))===1);
	}	
	
	//Guarda o actualiza el objeto en la base de datos, la accion se determina por la clave primaria
	public function save(){
		if(empty($this-><?php print lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$primary))));?>) || !$this->exits()){
			$this->setCommonValuesInsert();
			$this-><?php print lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$primary))));?> = $this->con->autoInsert(array(
			<?php foreach($camposP as $campo){?>"<?php print $campo;?>" => $this-><?php print lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$campo))));?>,
			<?php }?>),"<?php print $tabla;?>");
			return;
		}
		$this->setCommonValuesUpdate();
		return $this->con->autoUpdate(array(
		<?php foreach($camposP as $campo){?>"<?php print $campo;?>" => $this-><?php print lcfirst(str_replace(" ", "", ucwords(str_replace("_"," ",$campo))));?>,
		<?php } ?>),"<?php print $tabla;?>",$this->getId());
	}
    
	public function cargarPorId($value,$campowhere = "<?PHP echo $primary;?>",$camp = array()){
		if(!empty($value)){
			if(is_array($camp) && !empty($camp)){
				foreach($camp as $key){
					$fields[] = $key;
					$val[] = lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$key))));
				}
				$fieldslistar = implode(",",$fields);
			}else{
				
<?php
				foreach($campos as $campo){
?>
				$fields[] = "<?php print $campo["Field"];?>";
<?php
				}
				foreach($campos as $campo){
?>
				$val[] = "<?php print lcfirst(str_replace(" ","",ucwords(str_replace("_"," ",$campo["Field"]))));?>";
<?php
				}
?>
				$fieldslistar = implode(",",array_map(array($this->con,"quoteColumn"),$fields));
			}
			$sql = ("SELECT {$fieldslistar} FROM <?php print $tabla;?> WHERE {$campowhere} = %s");
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
		$this->setUpdaterUserId(@$session->userInfo["user_id"]);
		$this->setUpdatedAt(date("Y-m-d H:i:s"));
		$this->setIpAddress($this->getIp());
	}
	public function listar($filtros = array(), $orderBy = '', $limit = "0,30", $exactMatch = false, $fields = '*', $idInKeys = true){
		$whereA = array();
		if(!$exactMatch){
			$campos = $this->con->query("DESCRIBE <?PHP echo $tabla;?>");
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
		$rows =$this->con->query("SELECT $fields,<?PHP echo $primary;?> FROM `<?PHP echo $tabla;?>`  WHERE $where $orderBy LIMIT $limit");
		$rowsI = array();
		foreach($rows as $row){
        	if($idInKeys)
				$rowsI[$row["<?PHP echo $primary;?>"]] = $row;
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
			$obj->cargarPorId($row["<?PHP echo $primary;?>"]);
			$rowsr[$row["<?PHP echo $primary;?>"]] = $obj;
		}
		return $rowsr;
	}
	public function delete($value = null,$key = "<?php print $primary;?>"){
		if(!empty($value)){
			$this->set<?PHP echo str_replace(" ","",ucwords(str_replace("_"," ",$primary)));?>($value);
		}
		$value = $this->getId();
		return $this->con->autoDelete("<?php print $tabla;?>",$key,$value);
	}
}<?PHP
	$contenido = ob_get_contents ();
	ob_end_clean ();
	$contenidos [$clase] = $contenido;
}

$lineas = 0;

foreach ( $contenidos as $clase => $codigo ) {
	echo "<h2>$clase</h2>";
	if (class_exists ( $clase )) {
		//unlink($clase);
		echo "Clase $clase ya existe, saltando<br />";
		continue;
	}
	/*$resp = true;*/
	/*if($clase !="Scholarship" && $clase != "Statics"){
		$resp = eval ( $codigo );
	//exit();
	}*/
	$resp = eval ( $codigo );
	//echo ( "<br /><pre>" . printCode ( "<?PHP " . $codigo ) . "</pre>" );
	if ($resp === false){
		
		die ( "Error al compilar el codigo, el codigo fue <br /><pre>" . printCode ( "<?PHP " . $codigo ) . "</pre>" );
	}
	/*if($clase =="Scholarship"){
		die("llegue");
	//exit();
	}*/
	$codigo = "<" . "?" . "PHP" . " " . $codigo . "\r\n?" . ">";
	$ruta = "lib/model/{$clase}.php";
	file_put_contents ( $ruta, $codigo ) or die ( "Error al grabar $ruta" );
	chmod ( $ruta, 0777 );
	echo "Guardado $ruta <br />";
	$lineas += count ( explode ( "\n", $codigo ) );
	// echo "<pre>".htmlentities($codigo,ENT_COMPAT,"UTF-8")."</pre>";
	echo "<br />";
}
$fin = microtime ( true );
$total = $fin - $inicio;
echo "$lineas lineas generadas<br />";
echo "hecho en $total segundos";
?>