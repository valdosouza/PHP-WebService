<?php
define("BD_SERVER","Antonio:");
define("LOGIN","sysdba");
define("PASSWORD","masterkey");
define("BD","C:\\modelos\\StopCar\\Database\\IBGCOM.FDB");

require_once 'comum.class.php';
class FireBird extends Comum{
	private $c;//conexao
	public function __construct(){
		$this->c = ibase_connect(BD_SERVER.BD, LOGIN, PASSWORD);
	}
	public function __destruct(){
		@ibase_close($this->c);
		
	}
	/*
	 * retorna uma string de erro
	 */
	public function error(){
		return @ibase_errmsg();
	}
	/*@TODO
	 * retorna o ultimo id de um insert
	 * Precisa Implementar o gen_id
	 * $nextnumber=ibase_gen_id ("GEN_NAME",1);
	 */
	public function last_inserted_id(){
	}
	/*
	 * seleciona a base de dados
	 */
	public function select_db($b){
		
	}
	/*
	 * faz uma consulta, retorna null se der erro ou um identifier
	 */
	public function query($sql){
		return @ibase_query($this->c, $sql);
	}
	/*
	 * transforma o resource em array
	 */
	public function fetch_array($id){
		return @ibase_fetch_array($id);
	}
	/*
	 * transforma o resource em array associativo
	 */
	public function fetch_assoc($id){
		return @ibase_fetch_assoc($id);
	}
	/*
	 * transforma o resource em objeto
	 */
	public function fetch_object($id){
		return @ibase_fetch_object($id);
	}
	/* @TODO
	 * retoirna o numero de registros
	 */
	public function num_rows($id){
		
	}
	/*
	 * retorna o numero de colunas
	 */
	public function num_fields($id){
		return @ibase_num_fields($id);
	}
	/*
	 * retorna o numero de registros afetados
	 */
	public function affected_rows(){
		return @ibase_affected_rows($id);
	}
	/*
	 * junta o query com os fetchs
	 */
	public function query_fetch($sql, $tipo = 'assoc'){
		if(is_resource($sql)){
			$id = $sql;
		}else{
			$id = $this->query($sql);
		}
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
		@ibase_free_result($id);
		return $d;
	}
	/**
	 * retorna um registri so
	 */
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
	/*
	 * escapa a string
	 */
	public function escape_string($str){
		return $str;
	}
}