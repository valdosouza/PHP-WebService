<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class NotaFiscal extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}

	public function insert($dados){
		if($this->hasThisPk(@$dados['NFL_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_nota_fiscal', $dados, 'NFL_CODIGO');
		$this->query($sql);
		$r = $this->affected_rows();
		if($r>0){
			return 1;
		}else{
			return $this->error();
		}
		return 'erro';
	}
	
	public function update($dados){
		$sql = $this->geraSql('update', 'tb_nota_fiscal', $dados, 'NFL_CODIGO');
		$this->query($sql);
		$r = $this->affected_rows();
		if($r>0){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "dados identicos";
	}
	
	public function delete($dados){
		$sql = "DELETE FROM 
					tb_nota_fiscal 
				WHERE 
					NFL_CODIGO=".$dados['NFL_CODIGO'];
		$this->query($sql);
		$r = $this->affected_rows();
		if($r>0){
			return 1;
		}else{
			return $this->error();
		}
		return 'erro';
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_nota_fiscal ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			foreach ($dados as $index=>$valor){
				$sr[] = $index." = '".$this->escape_string($valor)."' ";
			}
			if(!empty($sr)){
				$sql .= implode(" AND ", $sr);
			}
			if(isset($dados['ORDER'])){
				$sql .= " ORDER BY ".$dados['ORDER']." ";
			}
			if(isset($dados['LIMIT'])){
				$sql .= " LIMIT ".$dados['LIMIT'];
			}
		}
		return $this->query_fetch($sql);
	}
	
	public function customQuery($dados){
		$r = $this->query_fetch($dados['QUERY']);
		if(empty($r)){
			return "consulta vazia";
		}
		return $r;
	}
	/**
	 * Verifica se uma categoria existe
	 * @param Integer $NFL_CODIGO
	 * @return Boolean true|false 
	 */
	public function hasThisPk($pk){
		$sql = "SELECT 1 FROM tb_nota_fiscal WHERE NFL_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	public function pesquisaCDKP($dados){
		$sql = "SELECT NFL_CODIGO FROM tb_nota_fiscal WHERE NFL_CODDKP=".$dados['NFL_CODDKP'];
		$d = $this->query_return_one($sql);
		if(empty($d['NFL_CODIGO'])){return 0;}
		return $d['NFL_CODIGO'];
	}
	public function executeThisOperation($op, $dados){
		$r = array();
		switch ($op){
			default:
				return 'operacao invalida';
			break;
			case 'I': //INSERT
				$r = $this->insert($dados);
			break;
			case 'U': //UPDATE
				$r = $this->update($dados);
			break;
			case 'D': //DELETE
				$r = $this->delete($dados);
			break;
			case 'S': //SEARCH
				$r = $this->search($dados);
			break;
			case 'C': //CUSTOM
				$r = $this->customQuery($dados);
			break;
			case 'CDKP': //CODIGO DESKTOP
				$r = $this->pesquisaCDKP($dados);
			break;
		}
		return $r;
	}
}