<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class TabelaPreco extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['TPR_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_tabela_preco', $dados, 'TPR_CODIGO');
		$this->query($sql);
		if($this->affected_rows()){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "erro";
	}
	
	public function update($dados){
		$sql = $this->geraSql('update', 'tb_tabela_preco', $dados, 'TPR_CODIGO');
		$this->query($sql);
		if($this->affected_rows()){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "dados identicos";
	}
	
	public function delete($dados){
		$sql = "DELETE FROM tb_tabela_preco WHERE TPR_CODIGO=".$dados['TPR_CODIGO'];
		$this->query($sql);
		if($this->affected_rows()){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "registro inexistente";
	}
	
	public function hasThisPk($pk){
		$sql = "SELECT 1 FROM tb_tabela_preco WHERE TPR_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_tabela_preco ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['TPR_CODIGO'])){
				$sr[] = " TPR_CODIGO = ".$dados['TPR_CODIGO']." ";
			}
			if(isset($dados['TPR_CODETB'])){
				$sr[] = " TPR_CODETB = ".$dados['TPR_CODETB']." ";
			}
			if(isset($dados['TPR_CODETD'])){
				$sr[] = " TPR_CODETD = ".$dados['TPR_CODETD']." ";
			}
			if(isset($dados['TPR_NOME'])){
				$sr[] = " TPR_NOME = '".$this->escape_string($dados['TPR_NOME'])."' ";
			}
			if(isset($dados['TPR_VALIDADE'])){
				$sr[] = " TPR_VALIDADE = '".$this->escape_string($dados['TPR_VALIDADE'])."' ";
			}
			if(isset($dados['TPR_MODALIDADE'])){
				$sr[] = " TPR_MODALIDADE = '".$this->escape_string($dados['TPR_MODALIDADE'])."' ";
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
		$r = $this->query_fetch($sql);
		if(empty($r)){
			return "consulta vazia";
		}
		return $r;
	}
	
	public function customQuery($dados){
		$r = $this->query_fetch($dados['QUERY']);
		if(empty($r)){
			return "consulta vazia";
		}
		return $r;
	}
	public function executeThisOperation($op, $dados){
		$r = array();
		switch ($op){
			default:
				return 'operação inválida';
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
		}
		return $r;
	}
}