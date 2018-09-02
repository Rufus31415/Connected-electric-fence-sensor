<?php
// Représente une connexion au backend Sigfox
class SigfoxAPIAccess{
	private $_login;
	private $_password;
	
	public function __construct($login, $password){
		$this->_login=$login;
		$this->_password=$password;
	}
	
	public function get_CURLOPT_USERPWD(){
		return $this->_login.':'.$this->_password;
	}
}
?>