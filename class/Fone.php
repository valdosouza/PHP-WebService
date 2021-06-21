<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Fone extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['FNE_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_fone', $dados, 'FNE_CODIGO');
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
		$sql = $this->geraSql('update', 'tb_fone', $dados, 'FNE_CODIGO');
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
		$sql = "DELETE FROM tb_fone WHERE FNE_CODIGO=".$dados['FNE_CODIGO'];
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
		$sql = "SELECT 1 FROM tb_fone WHERE FNE_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_fone ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['FNE_CODIGO'])){
				$sr[] = " FNE_CODIGO = ".$dados['FNE_CODIGO']." ";
			}
			if(isset($dados['FNE_CODETB'])){
				$sr[] = " FNE_CODETB = ".$dados['FNE_CODETB']." ";
			}
			if(isset($dados['FNE_CODETD'])){
				$sr[] = " FNE_CODETD = ".$dados['FNE_CODETD']." ";
			}
			if(isset($dados['FNE_CODEND'])){
				$sr[] = " FNE_CODEND = ".$dados['FNE_CODEND']." ";
			}
			if(isset($dados['FNE_TIPO'])){
				$sr[] = " FNE_TIPO = '".$this->escape_string($dados['FNE_TIPO'])."' ";
			}
			if(isset($dados['FNE_NUMERO'])){
				$sr[] = " FNE_NUMERO = '".$this->escape_string($dados['FNE_NUMERO'])."' ";
			}
			if(isset($dados['FNE_RAMAL'])){
				$sr[] = " FNE_RAMAL = '".$this->escape_string($dados['FNE_RAMAL'])."' ";
			}
			if(isset($dados['FNE_CONTATO'])){
				$sr[] = " FNE_CONTATO = '".$this->escape_string($dados['FNE_CONTATO'])."' ";
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