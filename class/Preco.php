<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Preco extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['PRC_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_preco', $dados, 'PRC_CODIGO');
		$this->query($sql);
		if($this->affected_rows()){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
			//return $sql;
		}
		return "erro";
	}
	
	public function update($dados){
		$sql = $this->geraSql('update', 'tb_preco', $dados, 'PRC_CODIGO');
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
		$sql = "DELETE FROM tb_preco WHERE PRC_CODIGO=".$dados['PRC_CODIGO'];
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
		$sql = "SELECT 1 FROM tb_preco WHERE PRC_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_preco ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['PRC_CODIGO'])){
				$sr[] = " PRC_CODIGO = ".$dados['PRC_CODIGO']." ";
			}
			if(isset($dados['PRC_CODETB'])){
				$sr[] = " PRC_CODETB = ".$dados['PRC_CODETB']." ";
			}
			if(isset($dados['PRC_CODTPR'])){
				$sr[] = " PRC_CODTPR = ".$dados['PRC_CODTPR']." ";
			}
			if(isset($dados['PRC_CODPRO'])){
				$sr[] = " PRC_CODPRO = ".$dados['PRC_CODPRO']." ";
			}
			if(isset($dados['PRC_VL_VDA'])){
				$sr[] = " PRC_VL_VDA = ".$dados['PRC_VL_VDA']." ";
			}
			if(isset($dados['PRC_AQ_COM'])){
				$sr[] = " PRC_AQ_COM = ".$dados['PRC_AQ_COM']." ";
			}
			if(isset($dados['PRC_QT_MIN'])){
				$sr[] = " PRC_QT_MIN = ".$dados['PRC_QT_MIN']." ";
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