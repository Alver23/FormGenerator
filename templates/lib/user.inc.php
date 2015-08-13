<?php
class usersModel extends User{
	var $dbc;
	public $information;
	function usersModel() {
		global $con;
		$this->dbc = $con;
	}
	
	function confirmusuarioPass($userLogin, $password){
		$sql = "SELECT password, u.is_disabled
				FROM user u
				WHERE email = %s LIMIT 1";	
				
		if (!get_magic_quotes_gpc())
			$userLogin = addslashes($userLogin);
		$tSql = sprintf ($sql, $this->dbc->quote($userLogin));
		$rst = $this->dbc->query($tSql);
		
		
		if(empty($rst))
			return 1;
			
		$rst[0]["password"] = stripslashes ($rst[0]["password"] );
		$rst[0]["is_disabled"] = intval ($rst[0]["is_disabled"] );
		$password = stripslashes ( $password );
		
		if($rst [0] ["is_disabled"] === 1)
		   return 3;
		
		if($password == $rst [0] ["password"])
			return 0;
			//Login and password confirmed
		else
			return 2; // Password failure
	}
	
	function confirmusuarioID($userLogin, $userIdRnd) {
		$sql = "SELECT user_cookie, is_logged_in, ip_address FROM user WHERE email = %s LIMIT 1";
		
		if (! get_magic_quotes_gpc ())
			$userLogin = addslashes ( $userLogin );
		
		$tSql = sprintf ( $sql, $this->dbc->quote ( $userLogin ) );
		$rst = $this->dbc->query ( $tSql );
		
		if (empty ( $rst ))
			return 1;
		
		$rst [0] ["user_cookie"] = stripslashes ( $rst [0] ["user_cookie"] );
		$userIdRnd = stripslashes ( $userIdRnd );
		
		if (($userIdRnd == $rst [0] ["user_cookie"]) || ($rst [0] ["is_logged_in"] == 0))
			return 0;
			// Login and password confirmed, not logged
		else if (($userIdRnd == $rst [0] ["user_cookie"]) || ($rst [0] ["is_logged_in"] == 1)) { // Login and password confirmed,
			// already logged
			$this->information = "Your are already loggued from other IP: <b>" . $rst [0] ["ip_address"] . "</b><br />Your IP is: ".getIP();
			return 3;
		} else
			return 2; // usuario not logged in
	}
	function usernameTaken($userLogin) {
		$sql = "SELECT email FROM user WHERE email='%s'";
		
		if (! get_magic_quotes_gpc ())
			$userLogin = addslashes ( $userLogin );
		
		$tSql = sprintf ( $sql, $userLogin );
		$rst = $this->dbc->query ( $tSql );
		
		return ! empty ( $rst );
	}
	function getusuarioInfo($userLogin) {
		$userLogin = $this->dbc->quote($userLogin);
		
		$sql = <<<EOT
SELECT %s
FROM user u
WHERE u.email = %s
EOT;
		
		$aSels = array("u.iduser", "u.name", "u.email", "u.user_cookie");
		$tSql2 = sprintf ( $sql, implode (",", $aSels), $userLogin);
		$rst2 = $this->dbc->query ( $tSql2 );
		if (empty($rst2))
			return NULL;
		
		return $rst2[0];
	}
	function addActiveusuario($userLogin, $time) {
		$ipAddr = getIp();
		$sql = "UPDATE user SET last_access = %s, ip_address = '$ipAddr' WHERE email = %s";
		$tSql = sprintf ( $sql, $this->dbc->quote ( $time ), $this->dbc->quote ( $userLogin ) );
		$rst = $this->dbc->query ( $tSql );
	}
	function generateRandID() {
		return md5($this->generateRandStr( 16 ));
	}
	function generateRandStr($length) {
		$randstr = "";
		for($i = 0; $i < $length; $i ++) {
			$randnum = mt_rand ( 0, 61 );
			if ($randnum < 10) {
				$randstr .= chr ( $randnum + 48 );
			} else if ($randnum < 36) {
				$randstr .= chr ( $randnum + 55 );
			} else {
				$randstr .= chr ( $randnum + 61 );
			}
		}
		return $randstr;
	}
	function updateusuarioCookie($userLogin, $value) {
		$sql = "UPDATE user SET user_cookie = %s WHERE email = %s";
		$tSql = sprintf ( $sql, $this->dbc->quote ( $value ), $this->dbc->quote ( $userLogin ) );
		$rst = $this->dbc->query ( $tSql );
		return $rst;
	}
}

?>