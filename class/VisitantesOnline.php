<?php
require_once 'mysql.class.php';
 class VisitantesOnline extends Mysql{
 	private $cookieTempo = 2; //tempo em minutos online
 	private $cookieNome = 'visitantesOnline'; //nome do cookie
 	private $lastIdentifier = '';
 	private $usersOnline = 0;
 	
 	public function __construct($registerVisit = true){
 		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
 		$this->deleteOfflineUsers();
 		if($registerVisit){
 			$this->registerVisit();
 		}else{
 			$this->updateUsersOnline();
 		}
 	}
 	/**
 	 * JUST DUMP
 	 */
 	public function dump(){
 		echo "<br><br><br>--------------DUMP--------------<br>";
 		echo "cookieTempo: "; var_dump($this->cookieTempo);echo "<br>";
 		echo "cookieNome: ";var_dump($this->cookieNome);echo "<br>";
 		echo "lastIdentifier: ";var_dump($this->lastIdentifier);echo "<br>";
 		echo "usersOnline: ";var_dump($this->usersOnline);echo "<br>";echo "<br>";
 		echo "COOKIE: ";var_dump($_COOKIE);
 	}
 	/**
 	 * generate the identifier for user online
 	 */
 	private function generateIdentifier(){
 		$this->setLastIdentifier(sha1($this->cookieNome.$_SERVER['REMOTE_ADDR'].microtime()));
 		return $this->lastIdentifier;
 	}
 	private function generateCookie(){
 		setcookie($this->getCookieNome(), $this->getLastIdentifier(), time()+($this->getCookieTempo() * 60));
 	}
 	private function verifyIfIdentifierExists(){
 		$sql = "SELECT VON_IP FROM tb_visitantes_online WHERE VON_IDENTIFICADOR='".$this->getLastIdentifier()."'";
 		$id = $this->query($sql);
 		if($this->num_rows($id)){
 			return true;
 		}
 		return false;
 	}
 	/**
 	 * register the visit on database
 	 */
 	public function registerVisit(){
 		$new = false;
 		$updated = false;
 		if(isset($_COOKIE[$this->getCookieNome()])){
 			$new = false;
 			$this->setLastIdentifier($_COOKIE[$this->getCookieNome()]);
 		}else{
 			$new = true;
 			$this->setLastIdentifier($this->generateIdentifier());
 			$this->generateCookie();
 		}
 		if($this->verifyIfIdentifierExists()){
 			$sql = "UPDATE 
 						tb_visitantes_online
 					SET
 						VON_TIMESTAMP = NOW(),
 						VON_USERAGENT = '".$this->escape_string($_SERVER['HTTP_USER_AGENT'])."'
 					WHERE
 						VON_IDENTIFICADOR = '".$this->getLastIdentifier()."'";
 			$this->query($sql);
 		}else{
 			$sql = "INSERT INTO
 						tb_visitantes_online (
 							VON_IDENTIFICADOR, 
 							VON_IP, 
 							VON_USERAGENT, 
 							VON_CODCLI, 
 							VON_TIMESTAMP
 						)
 					VALUES(
 						'".$this->getLastIdentifier()."',
 						'".$_SERVER['REMOTE_ADDR']."',
 						'".$this->escape_string($_SERVER['HTTP_USER_AGENT'])."',
 						0,
 						NOW()
 					)";
 			$this->query($sql);
 		}
 		$this->updateUsersOnline();
 	}
 	
	/**
 	 * Deletes offline
 	 */
 	private function deleteOfflineUsers(){
 		$sql = "DELETE FROM 
 					tb_visitantes_online 
 				WHERE
 					(VON_TIMESTAMP <= (NOW() - INTERVAL ".$this->getCookieTempo()." MINUTE)) AND 
 					VON_IDENTIFICADOR != '".$this->getLastIdentifier()."'";
 		$this->query($sql);
 		$af = $this->affected_rows();
 		if($af){
 			$this->updateUsersOnline();
 			return true;
 		}
 		return false;
 	}
 	public function updateUsersOnline(){
 		$sql = "SELECT COUNT(VON_IDENTIFICADOR) AS TOTAL FROM tb_visitantes_online";
 		$r = $this->query_return_one($sql);
 		$this->setUsersOnline((int)$r['TOTAL']);
 		return $this->usersOnline;
 	}
	public function getUsersOnline() {
		return $this->usersOnline;
	}
	private function setUsersOnline($usersOnline) {
		$this->usersOnline = $usersOnline;
	}
	public function getCookieTempo() {
		return $this->cookieTempo;
	}
	public function getCookieNome() {
		return $this->cookieNome;
	}
	public function getLastIdentifier() {
		return $this->lastIdentifier;
	}
	public function setCookieTempo($cookieTempo) {
		$this->cookieTempo = $cookieTempo;
	}
	public function setCookieNome($cookieNome) {
		$this->cookieNome = $cookieNome;
	}
	public function setLastIdentifier($lastIdentifier) {
		$this->lastIdentifier = $lastIdentifier;
	}
 }