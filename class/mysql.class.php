<?php
 // LOCAL
define("BD_SERVER","localhost");
define("LOGIN","root");
define("PASSWORD","123mudar");
define("BD","scriptwebservice2");

require_once 'comum.class.php';
class Mysql extends Comum{
	private $c;//conexao
	private $b;//base de dados
	public function __construct($s = null, $l = null, $p = null, $b = null){
		if(($s)&&($l)&&($p)){
			try{
				$this->c = @mysql_connect($s, $l, $p);
				if(!$this->c){
					throw new Exception();
				}
				if($b){
					$this->setaBaseDeDados($b);
					$this->select_db();
				}
			}catch (Exception $e){
				print mysql_error($this->c);
				die("Dados de conex&atilde;o incorretos.");
			}
		}else{
			$this->__construct(BD_SERVER, LOGIN, PASSWORD, BD);
		}
	}
	public function __destruct(){
		/**
		 * TODO: por enquanto isso nao faz nada.
		 */
	}
	public function retornaBaseDeDados(){
		return $this->b;
	}
	public function setaBaseDeDados($base){
		$this->b = $base;
	} 
	public function error(){
		return mysql_error($this->c);
	}
	public function last_inserted_id(){
		return mysql_insert_id($this->c);
	}
	public function select_db($b = null){
		$base = $this->b;
		if($b){
			$base = $b;
		}
		try{
			mysql_query("SET NAMES 'utf8'");
			mysql_query('SET character_set_connection=utf8');
			mysql_query('SET character_set_client=utf8');
			mysql_query('SET character_set_results=utf8');		
			$con = mysql_select_db($base, $this->c);
			if(!$con){
				throw new Exception();
			}
		}catch (Exception $e){
			die(mysql_error($this->c));
		}
	} 
	public function query($sql){
		try{
			$q = @mysql_query($sql, $this->c);
			if(!$q){
				throw new Exception();
			}
		}catch (Exception $e){
			return false;
			//die(mysql_error($this->c));
		}
		return $q;
	}
	public function fetch_array($id){
		return mysql_fetch_array($id);
	}
	public function fetch_assoc($id){
		return @mysql_fetch_assoc($id);
	}
	public function fetch_object($id){
		return mysql_fetch_object($id);
	}
	public function num_rows($id){
		try{
			$q = mysql_num_rows($id);
			if(!$q){
				throw new Exception();
			}
		}catch (Exception $e){
			return false;
		}
		return $q;
	}
	public function num_fields($id){
		try{
			$q = mysql_num_fields($id);
			if(!$q){
				throw new Exception();
			}
		}catch (Exception $e){
			return false;
		}
		return $q;
	}
	public function affected_rows(){
		$q = mysql_affected_rows($this->c);
		if($q>0){
			return $q;
		}
		return false;
	}
	public function mysqlError(){
		if(mysql_errno($this->c)){
			return mysql_errno($this->c).' - '.mysql_error($this->c);
		}
	}
	public function query_fetch($sql, $tipo = 'assoc'){
		if(is_resource($sql)){
			$id = $sql;
		}else{
			$id = $this->query($sql);
		}
		//$qtd = $this->num_rows($id);
		$qtd = 2;
		if($qtd > 1){
			$d = array();
			switch($tipo){
				default:
					do{
						$dados = $this->fetch_assoc($id);
						if($dados){
							$d[] = $dados;
						}
					}while ($dados);
				break;
				case 'object':
					do{
						$dados = $this->fetch_object($id);
						if($dados){
							$d[] = $dados;
						}
					}while ($dados);
				break;
				case 'array':
					do{
						$dados = $this->fetch_array($id);
						if($dados){
							$d[] = $dados;
						}
					}while ($dados);
				break;
			}
		}else if($qtd == 1){
			switch($tipo){
				default:
					return $this->fetch_assoc($id);
				break;
				case 'object':
					return $this->fetch_object($id);
				break;
				case 'array':
					return $this->fetch_array($id);
				break;
			}
		}else{
			die(mysql_error($this->c));
		}
		return $d;
	}
	public function query_return_one($sql, $tipo = 'assoc'){
		$id = $this->query($sql);
		switch($tipo){
			default:
				return $this->fetch_assoc($id);
			break;
			case 'object':
				return $this->fetch_object($id);
			break;
			case 'array':
				return $this->fetch_array($id);
			break;
		}
		return false;
	}
	public function listarBasesDeDados(){
		$t = $this->query_fetch(mysql_list_dbs($this->c));
		$bases = array();
		foreach ($t as $n){
			$bases[] = $n['Database'];
		}
		return $bases;
	}
	public function listarTabelas($database = false){
		$bd = $this->retornaBaseDeDados();
		if($database){
			$bd = $database;
		}
		$t = $this->query_fetch(mysql_list_tables($bd));
		$tabelas = array();
		if($t['Tables_in_'.$bd]){
			$tabelas[] = $t['Tables_in_'.$bd]; 
		}else{
			foreach ($t as $n){
				$tabelas[] = $n['Tables_in_'.$bd];
			}
		}
		return $tabelas;
	}
	public function listarColunas($tabela){
		$sql = "SHOW COLUMNS FROM ".$tabela;
		return $this->query_fetch($sql);
	}
	public function escape_string($str){
		return mysql_escape_string(utf8_decode($str));
	}
}