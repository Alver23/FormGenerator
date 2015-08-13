<?php 
/**
* Class for MySQL Connections and Queries IN PDO
* @author Alver Grisales
* @license GPL
* @version 1.1.0 mejorada
* If you have question, send me a email - alver.grisales@alvergrisalesoft.com
*/
class DBNative{
	private $driver;
	private $port;
	private $link; // Database connection
	private $db;
	private static $obj;
	private $mailSupport = mailsupport;//Email to send errors
	private $debugIP;//IP for print error details
	private $remoteIP;
	private $userErrorMsg;
	private $lastQuery;
	private $transactionStarted = false;//Prevent nested transaction, mysql don't support it.
	public $DSN;
	public $ajax;//defined into the constructor, if true, is ajax request, else is not.
	public $debug = false;//print query and answer (boolean or rows number)
	public $ajaxDebug = false;//Do debug in ajax request, if is ON, json answer will not working on browser

	private function DBNative($DSN = false, $host = false, $user = false,
			$passwd = false, $db = false,$driver = false, $port = false, $mailSupport = mailsupport,
			$debugIP = "127.0.0.1, 181.52.187.11", $userErrorMsg = "Internal server error silletas"){

		$this->debugIP = $debugIP;
		$this->remoteIP = $this->getIp();
		$this->userErrorMsg = $userErrorMsg;
		$this->db = $db;
		$this->DSN = $DSN;
		$this->mailSupport = $mailSupport;
		if ($this->mailSupport == '') {
			$this->mailSupport = "viga.23@hotmail.com , dflm25@gmail.com"; 
		}
		$this->ajax = (((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') === false) ? false : true);

		if (!empty($DSN)) {
			$aTmp = parse_url($DSN);
			if (empty($aTmp))
				$this->error("The DSN Format is invalid!", "DSN: " . $DSN);
			$aTmp["db"] = trim(str_replace("/", "", $aTmp["path"]));
			$this->driver = $aTmp["scheme"];
			$this->connect($aTmp["host"], $aTmp["user"], $aTmp["pass"],$aTmp["db"],$aTmp["scheme"],$aTmp["port"]);
		}else if (!empty($host) && !empty($user) && !empty($passwd)&& !empty($db) && !empty($driver) && !empty($port)) {
			$this->connect($host, $user, $passwd, $db, $driver, $port);
		}else
			$this->error("The DSN Format is invalid!","Please your must to pass either the DSN String or the Connection Parameters, DSN: ". $DSN);
		$this->db = $aTmp["db"];
	}

	/**
	 * Connect to the Database
	 * 
	 * @param mixed $host Server Name
	 * @param mixed $user User Name 
	 * @param mixed $password Password for the User
	 * @param mixed $db Database Name
	 * @param mixed $driver Driver
	 * @param mixed $port Port
	 */
	
	public function connect($host, $user, $password, $db,$driver,$puerto,$c = 0){

		try{
            	$link = new PDO ("$driver:host=$host;port=$puerto;dbname=$db",$user,$password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_PERSISTENT => true));
            	$this->link = $link;
            }catch(PDOException $e){
            	$this->error("Error en la conexión: ",$e->getMessage());
            	//echo "Error en la conexión: ".$e->getMessage();
            	return $this->connect($host, $user, $password, $db,$driver,$puerto, $c + 1);
            }
	}
	/**
	 * function Display error
	 */
	private function error($subject, $details) {
		if(is_array($details)){
			$details = $details[2];
		}else{
			$details = $details;
		}
		ob_start();
		echo " Last Query: <br />\r\n" . $this->lastQuery . "<br />\r\n";
		echo "BackTrace: ";
		debug_print_backtrace();
		echo "_POST: ";
		print_r($_POST);
		echo "_GET: ";
		print_r($_GET);
		echo "_REQUEST: ";
		print_r($_REQUEST);
		echo "_SERVER: ";
		print_r($_SERVER);
		echo "GLOBALS: ";
		print_r($GLOBALS);
		$systemInfo = ob_get_contents();
		ob_end_clean();
		ob_start();
		mail($this->mailSupport, $subject, $details . $systemInfo,
				"From: " . $this->mailSupport);
		$mailError = ob_get_contents();
		ob_end_clean();

		//if($mailError != ''){
		@file_put_contents(dirname(__FILE__)."/../logs/errors.txt",$details.$systemInfo."\r\n",FILE_APPEND);
		//}
		if (strpos($this->debugIP,$this->remoteIP) !== false) {
			echo $mailError;
			echo "<h1>" . $subject . "</h1>";
			echo "<pre>" . $details . $systemInfo . "</pre>";
		} else {
			@header("HTTP/1.1 503 Service Temporarily Unavailable");
			@header("Status: 503 Service Temporarily Unavailable");
			@header("Retry-After: 30");//Retry 20 seconds after
			?><meta http-equiv="refresh" content="30"><?PHP
			echo $this->userErrorMsg;
		}
		exit(1);//Exit with error
	}
	public static function get($DSN = false, $host = false, $user = false,$passwd = false, $db = false, $driver = false, $port = false) {
		if (!self::$obj)
			self::$obj = new DBNative($DSN, $host, $user, $passwd, $db, $driver, $port);
		return self::$obj;
	}
	//Funcion para Obtener la IP Remote
	function getIp() {
		$ipAddr = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR']
				: $_SERVER["REMOTE_ADDR"];
		//fix multiple
		$tmp = explode(",", $ipAddr);
		$ipAddr = array_shift($tmp);
		return $ipAddr;
	}
	public function query($SQL,$valores = array()){  //funcion principal, ejecuta todas las consultas
		$campo = "";
		$resultado = false;
		$this->lastQuery = $SQL;
		if (empty($SQL)) {
			if ($this->transactionStarted === true) {
				$this->link->rollback();//ROLLBACK TRANSACTION IN COURSE
				$this->transactionStarted = false;
			}
			$this->error("Query must be non-empty!!","Query must be non-empty!!");
		}
		$this->printDebug($SQL);
		if($statement = $this->link->prepare($SQL)){  //prepara la consulta
			/*if(preg_match_all("/(:\w+)/", $SQL, $campo, PREG_PATTERN_ORDER)){ //tomo los nombres de los campos iniciados con :xxxxx
				$campo = array_pop($campo); //inserto en un arreglo
				foreach($campo as $parametro){
					$statement->bindValue($parametro, $valores[substr($parametro,1)]);
				}
			}*/
			try {
				if (!$statement->execute()) { //si no se ejecuta la consulta...
					$this->error("Invalid Query",$statement->errorInfo());
					$statement->rollBack();
					//print_r($statement->errorCode()); //imprimir errores
				}
				$resultado = $statement->fetchAll(PDO::FETCH_ASSOC); //si es una consulta que devuelve valores los guarda en un arreglo.
				$statement->closeCursor();
			}
			catch(PDOException $e){
				echo "Error de ejecución: \n";
				print_r($e->getMessage());
			}	
		}
		return $resultado;
		$this->link = null; //cerramos la conexión
	}
	private function printDebug($text) {
		if (strpos($this->debugIP,$this->remoteIP) !== false && ($this->debug && !$this->ajax)
				|| ($this->ajax && $this->ajaxDebug)) {
			echo "<div class='debugText'>" . $text . "</div>";
		}
	}
	public function getLastQuery() {
		return $this->link->errorInfo();
	}
	public function getFields($table){
		if(!$table) {
			$this->error("Cannot create fields because table name is not properly set","Cannot create fields because table name is not properly set");
	        return false;
	    }
		/* si es postgres */
		if($this->driver == "PDO_PGSQL"){
			/* Obtener Conjunto de Campos de la Tabla */
			$COL = array();
			$sql = "select a.column_name,data_type, constraint_name
					from information_schema.columns a left JOIN information_schema.key_column_usage b on a.COLUMN_NAME = b.column_name
					where a.table_name = '".$table."'";
			
			$rsCol = parent::query($sql);    

			foreach ($rsCol as $i => $row) {
				if(!empty($row['constraint_name'])){
					$COL = array_merge($COL, array("PK" => array($row['column_name'],$row['data_type'])));
				}else{
					$COL = array_merge($COL, array("Field_".$i => array($row['column_name'],$row['data_type'])));
				}
			}
		}else if($this->driver == "mysql"){
			$fields = $this->query("SHOW COLUMNS FROM " . $table);
			if (empty($fields)) {
				$this->error("Invalid Query",$this->link->errorInfo());
			}
			$COL = array("primary"=>array(),"data"=>array());
			$numerics = "/^(int|tinyint|float|bigint)/";
			foreach($fields as $field) {
				$element = array("type"=>(preg_match($numerics,$field["Type"])) ? "number" : "string","null"=>($field["Null"]=="NO") ? false : true, "default"=>$field["Default"], "autonum"=>($field["Extra"]=="auto_increment") ? true : false, "value"=>null, "exists"=>false);
				if($field["Key"]=="PRI") {
					$COL["primary"][$field["Field"]] = $element;
				} else {
					$COL["data"][$field["Field"]] = $element;
				}
			}
		}
	    return $COL;

	}
	public function begin() {
		if ($this->transactionStarted === false) {
			$this->link->beginTransaction();
			$this->transactionStarted = true;//transaction started

		}else{
			$this->error("Transaction already started","Transaction already started");
		}
	}
	//Save transaction
	public function commit() {
		if ($this->transactionStarted === true) {
			$this->link->commit();
			$this->transactionStarted = false;//end of transaction
		} else {
			$this->error("Transaction is not already started (not commit possible)","Transaction is not already started (not commit possible)");
		}
	}
	//Rollback the transaction
	public function rollback() {
		if ($this->transactionStarted === true) {
			$this->link->rollBack();
			$this->transactionStarted = false;//end of transaction
		} else {
			$this->error("Transaction is not already started (not rollback possible)","Transaction is not already started (not rollback possible)");
		}
	}
	// Funcion para Insertar Datos
	public function autoInsert($fields, $table) {
		foreach ($fields as $key => $value) {
			$fiel[] = $this->verificampo($table,$key,$value);
		}
		$values = implode(",",$fiel);
		$cols = implode(",",array_map(array($this, "quoteColumn"), array_keys($fields)));
		$sql = "INSERT INTO $table ($cols) VALUES ($values)";
		$this->query($sql);
		return $this->link->lastInsertId();
	}
	// Funcion para actualizar datos de la BD
	public function autoUpdate($fields, $table, $whereString) {
		foreach ($fields as $key => $value) {
			$fiel[] = $this->verificampo($table,$key,$value);
		}
		$fied = $this->implodefielbyvalue($fields,$fiel,",",TRUE,TRUE);
		$sql = "UPDATE  $table SET ";
		$sqlA = array();
		$pk = $this->getFields($table);
		foreach ($pk['primary'] as $key => $value) {
			$PK = $key;
		}
		if (is_array($whereString)) {
			foreach ($whereString as $key => $value) {
				$whereA[] = $this->verificampo($table,$key,$value);
			}
			$where = $this->implodefielbyvalue($whereString,$whereA);
			$sql .= $fied . " WHERE ".$where;
			
		}else{
			$where = $this->setType($whereString,"integer");
			$sql .= $fied . " WHERE ".$PK ." = ".$where;
		}
		return $this->query($sql);
	}
	//Funcion para eliminar de la BD
	public function autoDelete($table,$key,$value){
		$field = $this->verificampo($table,$key,$value);
		$sql = ("DELETE FROM {$table} WHERE {$key} = {$field} ");
		$this->query($sql);
	}
	//Funcion para poner comillas a una variable
	public function quote($value) {
		if ($value === NULL || trim($value) == '')
			return "NULL";
		$rst = $this->escape($value);
		return $this->link->quote($rst);
	}
	/**
	 * Quote and scape a values
	 * Funcion para escapar de las inyecciones SQL
	 * @param mixed $value The Value to escape
	 */
	public function escape($value) {
		if ($value === NULL || trim($value) == ''){
			return "NULL";
		}
		//if (get_magic_quotes_gpc())
		$valor = stripslashes($value);
		return $valor;
	}

	public function quoteColumn($col) {
		return '`' . $col . '`';
	}
	//Funcion para verificar el tipo de campo en la BD
	private function verificampo($table,$campo,$value){
		$numerics = "/^(int|tinyint|float|bigint)/";
		$sql = ("SELECT DATA_TYPE, IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ".$this->quote($table)." AND COLUMN_NAME = ".$this->quote($campo)." GROUP BY COLUMN_NAME");
		$rst = $this->query($sql);
		if ($rst[0]['IS_NULLABLE'] == "NO" && strlen($value) === 0) {
			$this->error("The field value can not be null {$campo}",$campo);
			return false;
		}

		$valor = preg_match($numerics, $rst[0]['DATA_TYPE']) ?  $this->setType($value,"integer") : $this->setType($this->quote($value),"string");
		//die($value);
		return $valor;
	}
	/**
	 * Get the Last ID for a table
	 * 
	 * @param mixed $table The table name
	 * @return mixed
	 */
	public function getLastID($table) {
		if ($this->transactionStarted === TRUE) {
			return $this->link->lastInsertId(); //only for transactions
		}
		if (empty($table))
			$this->error("Table must be non-empty!","Table must be non-empty!");
		$SQL = "SELECT LAST_INSERT_ID() AS ID FROM $table ORDER BY ID DESC LIMIT 1";
		$rst = $this->query($SQL);
		return $rst[0]["ID"];
	}
	//Funcion para declarar una variable como entera o String
	public function setType($valor,$tipo){
		if (empty($tipo)){
			$this->error("The field value can not be null","The field value can not be null");
			return false;
		}else if (strlen($valor) === 0) {
			return "NULL";
		}else{
			settype($valor,$tipo);
			return $valor;
		}
	}
	public function disconnect() {
		$this->link = NULL;
	}
	public function getLastAffectedRows() {
		return $this->link->rowCount();
	}
	// Function to Return All Possible ENUM Values for a Field
	public function getEnumValues($table, $field) {
		$enum_array = array();
		$query = 'SHOW COLUMNS FROM ' . $this->quoteColumn($table) . ' LIKE "'
				. $field . '"';
		$res = $this->query($query);
		$error = print_r($res, true);
		preg_match_all('/\'(.*?)\'/', $res[0]["Type"], $enum_array);
		if (!empty($enum_array[1])) {
			// Shift array keys to match original enumerated index in MySQL (allows for use of index values instead of strings)
			foreach ($enum_array[1] as $mkey => $mval)
				$enum_fields[$mkey + 1] = $mval;
			return $enum_fields;
		} else
			return array($error); // Return an empty array to avoid possible errors/warnings if array is passed to foreach() without first being checked with !empty().
	}
	// Hacer un implode con array merge o solo el implode dependiendo si $combine es TRUE entonces $valueskey no puede ser vacio
	public function implodefielbyvalue($valueskey = array(), $fields = array(),$implode = "AND",$combine = TRUE,$autoQuote = FALSE){
		if ($combine === TRUE) {
			$WhereA = array_combine(array_keys($valueskey),$fields);
			foreach ($WhereA as $key => $value) {
				$whereF[] = ($autoQuote ? $this->quoteColumn($key) : $key)." = ".$value;
			}
			$where = implode(" {$implode} " , $whereF);
			return $where;
		}else{
			foreach ($fields as $key => $value) {
				$whereF[] = $key." = ".$value;
			}
			$where = implode(" {$implode} " , $whereF);
			return $where;
		}
	}
}
?>

