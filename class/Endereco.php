<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Endereco extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['END_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_endereco', $dados, 'END_CODIGO');
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
		$sql = $this->geraSql('update', 'tb_endereco', $dados, 'END_CODIGO');
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
		$sql = "DELETE FROM tb_endereco WHERE END_CODIGO=".$dados['END_CODIGO'];
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
		$sql = "SELECT 1 FROM tb_endereco WHERE END_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_endereco ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['END_CODIGO'])){
				$sr[] = " END_CODIGO = ".$dados['END_CODIGO']." ";
			}
			if(isset($dados['END_CODETB'])){
				$sr[] = " END_CODETB = ".$dados['END_CODETB']." ";
			}
			if(isset($dados['END_CODETD'])){
				$sr[] = " END_CODETD = ".$dados['END_CODETD']." ";
			}
			if(isset($dados['END_TIPO'])){
				$sr[] = " END_TIPO = '".$this->escape_string($dados['END_TIPO'])."' ";
			}
			if(isset($dados['END_LOGRAD'])){
				$sr[] = " END_LOGRAD = '".$this->escape_string($dados['END_LOGRAD'])."' ";
			}
			if(isset($dados['END_COMPLEM'])){
				$sr[] = " END_COMPLEM = '".$this->escape_string($dados['END_COMPLEM'])."' ";
			}
			if(isset($dados['END_BAIRRO'])){
				$sr[] = " END_BAIRRO = '".$this->escape_string($dados['END_BAIRRO'])."' ";
			}
			if(isset($dados['END_CEP'])){
				$sr[] = " END_CEP = '".$this->escape_string($dados['END_CEP'])."' ";
			}
			if(isset($dados['END_CONTATO'])){
				$sr[] = " END_CONTATO = '".$this->escape_string($dados['END_CONTATO'])."' ";
			}
			if(isset($dados['END_PRINCIPAL'])){
				$sr[] = " END_PRINCIPAL = '".$this->escape_string($dados['END_PRINCIPAL'])."' ";
			}
			if(isset($dados['END_REGIAO'])){
				$sr[] = " END_REGIAO = '".$this->escape_string($dados['END_REGIAO'])."' ";
			}
			if(isset($dados['END_NUMERO'])){
				$sr[] = " END_NUMERO = '".$this->escape_string($dados['END_NUMERO'])."' ";
			}
			if(isset($dados['END_CODPAI'])){
				$sr[] = " END_CODPAI = ".$this->escape_string($dados['END_CODPAI'])." ";
			}
			if(isset($dados['END_CODCDD'])){
				$sr[] = " END_CODCDD = ".$this->escape_string($dados['END_CODCDD'])." ";
			}
			if(isset($dados['END_CODUFE'])){
				$sr[] = " END_CODUFE = ".$this->escape_string($dados['END_CODUFE'])." ";
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