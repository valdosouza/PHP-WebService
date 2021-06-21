<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class ItensNFL extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['ITF_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_itens_nfl', $dados, null);
		$this->query($sql);
		if($this->affected_rows()){
			return $this->last_inserted_id();
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "erro";
	}
	
	public function update($dados){
		$sql = $this->geraSql('update', 'tb_itens_nfl', $dados, 'ITF_CODIGO');
		$this->query($sql);
		if($this->affected_rows()){
			return $dados['ITF_CODIGO'];
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "dados identicos";
	}
	
	public function delete($dados){
		$sql = "DELETE FROM tb_itens_nfl WHERE ITF_CODIGO=".$dados['ITF_CODIGO'];
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
		$sql = "SELECT 1 FROM tb_itens_nfl WHERE ITF_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	public function search($dados){
		$sql = "SELECT * FROM tb_itens_nfl ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['ITF_CODIGO'])){
				$sr[] = " ITF_CODIGO = ".$dados['ITF_CODIGO']." ";
			}
			if(isset($dados['ITF_CODETB'])){
				$sr[] = " ITF_CODETB = ".$dados['ITF_CODETB']." ";
			}
			if(isset($dados['ITF_CODPED'])){
				$sr[] = " ITF_CODPED = ".$dados['ITF_CODPED']." ";
			}
			if(isset($dados['ITF_CODNFL'])){
				$sr[] = " ITF_CODNFL = ".$dados['ITF_CODNFL']." ";
			}
			if(isset($dados['ITF_CODPRO'])){
				$sr[] = " ITF_CODPRO = ".$dados['ITF_CODPRO']." ";
			}
			if(isset($dados['ITF_QTDE'])){
				$sr[] = " ITF_QTDE = ".$dados['ITF_QTDE']." ";
			}
			if(isset($dados['ITF_VL_CUSTO'])){
				$sr[] = " ITF_VL_CUSTO = ".$dados['ITF_VL_CUSTO']." ";
			}
			if(isset($dados['ITF_VL_UNIT'])){
				$sr[] = " ITF_VL_UNIT = ".$dados['ITF_VL_UNIT']." ";
			}
			if(isset($dados['ITF_DESPACHO'])){
				$sr[] = " ITF_DESPACHO = '".$this->escape_string($dados['ITF_DESPACHO'])."' ";
			}
			if(isset($dados['ITF_ESTOQUE'])){
				$sr[] = " ITF_ESTOQUE = '".$this->escape_string($dados['ITF_ESTOQUE'])."' ";
			}
			if(isset($dados['ITF_SENTIDO'])){
				$sr[] = " ITF_SENTIDO = '".$this->escape_string($dados['ITF_SENTIDO'])."' ";
			}
			if(isset($dados['ITF_AQ_COM'])){
				$sr[] = " ITF_AQ_COM = ".$dados['ITF_AQ_COM']." ";
			}
			if(isset($dados['ITF_VL_DESC'])){
				$sr[] = " ITF_VL_DESC = ".$dados['ITF_VL_DESC']." ";
			}
			if(isset($dados['ITF_AQ_DESC'])){
				$sr[] = " ITF_AQ_DESC = ".$dados['ITF_AQ_DESC']." ";
			}
			if(isset($dados['ITF_AQ_IPI'])){
				$sr[] = " ITF_AQ_IPI = ".$dados['ITF_AQ_IPI']." ";
			}
			if(isset($dados['ITF_OPER'])){
				$sr[] = " ITF_OPER = '".$this->escape_string($dados['ITF_OPER'])."' ";
			}
			if(isset($dados['ITF_AQ_ICMS'])){
				$sr[] = " ITF_AQ_ICMS = ".$dados['ITF_AQ_ICMS']." ";
			}
			if(isset($dados['ITF_CODEST'])){
				$sr[] = " ITF_CODEST = ".$dados['ITF_CODEST']." ";
			}
			if(isset($dados['ITF_CODTPR'])){
				$sr[] = " ITF_CODTPR = ".$dados['ITF_CODTPR']." ";
			}
			if(isset($dados['ITF_ALTURA'])){
				$sr[] = " ITF_ALTURA = ".$dados['ITF_ALTURA']." ";
			}
			if(isset($dados['ITF_LARGURA'])){
				$sr[] = " ITF_LARGURA = ".$dados['ITF_LARGURA']." ";
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
				return 'opera��o inv�lida';
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