<?php 
require_once "lib/model/User.php";
require_once "user.inc.php";
class Session {
	var $dbc;
	var $userLogin; // The Username
	var $userIdRnd; // The User ID Rand
	var $userLevel; // The User Profile
	var $userInstu;
	var $time;
	var $logged_in = false;
	var $userInfo = array ();
	var $url;
	var $referrer;
	var $aErrors = array ();
	var $user;
	public $information;
	function Session() {
		global $con;
		$this->dbc = $con;
		$this->user = new usersModel();
		
		$this->time = date ( "Y-m-d H:i:s" );
		$this->startSession();
	}
	function startSession() {
		session_start ();
		$this->logged_in = $this->checkLogin();
		/* Update users last active timestamp */
		if ($this->logged_in)
			$this->user->addActiveusuario ( $this->userLogin, $this->time );
			
			/* Set referrer page */
		if (isset ( $_SESSION ['url'] ))
			$this->referrer = $_SESSION ['url'];
		else
			$this->referrer = "/";
		
		$this->url = $_SESSION ['url'] = $_SERVER ['PHP_SELF'];
	}
	function checkLogin() {
		if (isset ( $_COOKIE ['cookname'] ) && isset ( $_COOKIE ['cookidrnd'] )) {
			$this->userLogin = $_SESSION ['userEmail'] = $_COOKIE ['cookname'];
			$this->userIdRnd = $_SESSION ['useridrnd'] = $_COOKIE ['cookidrnd'];
		}
		
		if (isset ( $_SESSION ['userEmail'] ) && isset ( $_SESSION ['useridrnd'] )) {
			
			if (($resp = $this->user->confirmusuarioID($_SESSION ['userEmail'], $_SESSION ['useridrnd'] )) != 0) {
				// var_dump($resp);exit;
				if ($resp == 2)
					$this->aErrors ["username"] = "* User not logged in";
				if ($resp == 3) {
					$this->information = $this->user->information;
					$this->aErrors ["twice_login"] = $this->information;
				}
				unset($_SESSION ['userEmail'] );
				unset($_SESSION ['useridrnd'] );
				return false;
			}
			/* User is logged in, set class variables */
			$this->userInfo = $this->user->getusuarioInfo($_SESSION['userEmail']);		
			$this->userInfo['sites_ids'] = array();
			$this->userLogin = $this->userInfo['email'];
			$this->userIdRnd = $this->userInfo['user_cookie'];
			return true;
		} else { /* User not logged in */
			return false;
		}
	}
	function login($subuser, $subpass, $subremember) {
		if (!$subuser || strlen ( $subuser = trim ( $subuser ) ) == 0)
			$this->aErrors["username"] = "* Username not entered";
		else {
			$expresion = '/^[A-Z]{2}-[0-9]{4}$/';
			if (preg_match($expresion, $subuser))
				$this->aErrors["username"] = "* Username not alphanumeric";
		}
		
		if (!$subpass)
			$this->aErrors["password"] = "* Password not entered";
		
		if (!empty($this->aErrors))
			return false;
			
		$subuser = stripslashes($subuser);
		$result = $this->user->confirmusuarioPass($subuser, md5($subpass));
		
		/* Check error codes */
		if ($result == 4)
			$this->aErrors["username"] = "Group is disabled";
			
		if ($result == 1)
			$this->aErrors["username"] = "Username not found";
		else if ($result == 2)
			$this->aErrors["password"] = "Invalid password";
		else if ($result == 3)
			$this->aErrors["username"] = "User is disabled";
		else 
			
		$this->userInfo = $this->user->getusuarioInfo($subuser); 
		$randomId = $this->user->generateRandID ();
		
		if (!empty($this->aErrors ))
			return false;
				
		$this->userLogin = $_SESSION['userEmail'] = $this->userInfo['email'];
		$this->userIdRnd = $_SESSION['useridrnd'] = $randomId;
		
		$this->setLoggedIn($this->userInfo["iduser"], 1);
		$this->user->addActiveusuario( $this->userLogin, $this->time );
		$this->user->updateusuarioCookie ( $this->userLogin, $this->userIdRnd );
		if ($subremember) {
			setcookie ( "cookname", $this->userLogin, time () + COOKIE_EXPIRE, COOKIE_PATH );
			setcookie ( "cookidrnd", $this->userIdRnd, time () + COOKIE_EXPIRE, COOKIE_PATH );
		}
		
		return true;
	}
	function setLoggedIn($userId, $status) {
		if (empty ( $userId ))
			die ( "ERROR: User is requerided" );
		
		$sql = "UPDATE user SET is_logged_in = %s WHERE iduser = %s";
		$tSql = sprintf ( $sql, $status, $userId );
		$rst = $this->dbc->query ( $tSql );
		
		return ! empty ( $rst ) ? true : false;
	}
	function logout() {
		if (isset ( $_COOKIE ['cookname'] ) && isset ( $_COOKIE ['cookidrnd'] )) {
			setcookie ( "cookname", "", time () - COOKIE_EXPIRE, COOKIE_PATH );
			setcookie ( "cookidrnd", "", time () - COOKIE_EXPIRE, COOKIE_PATH );
		}
		
		$_SESSION = array();
		session_destroy ();
		
		$this->setLoggedIn ( $this->userInfo ["iduser"], 0);
		$this->logged_in = false;
	}
	function getErrors() {
		return $this->aErrors;
	}
}
$session = new Session ();
?>