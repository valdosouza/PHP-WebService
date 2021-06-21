<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Entidade extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['ETD_CNPJ_CPF'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_entidade', $dados, 'ETD_CNPJ_CPF');
		$this->query($sql);
		$aff = $this->affected_rows();
		if($aff>0){
			if(0<(int)$dados['ETD_CODIGO']){
				return $dados['ETD_CODIGO'];
			}else{
				return $this->last_inserted_id();
			}
		}else if($aff==0){
			return $dados['ETD_CODIGO'];
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "erro";
	}
	
	public function update($dados){
		
		$sql = $this->geraSql('update', 'tb_entidade', $dados, 'ETD_CNPJ_CPF');
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
		if(!isset($dados['ETD_CODIGO'])){
			$dados['ETD_CODIGO'] = 0;
		}
		$sql = "DELETE FROM tb_entidade WHERE ETD_CNPJ_CPF=".$dados['ETD_CNPJ_CPF'];
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
		$sql = "SELECT 1 FROM tb_entidade WHERE ETD_CNPJ_CPF=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_entidade ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['ETD_CODIGO'])){
				$sr[] = " ETD_CODIGO = ".$dados['ETD_CODIGO']." ";
			}
			if(isset($dados['ETD_CODETB'])){
				$sr[] = " ETD_CODETB = ".$dados['ETD_CODETB']." ";
			}
			if(isset($dados['ETD_CODETD'])){
				$sr[] = " ETD_CODETD = ".$dados['ETD_CODETD']." ";
			}
			if(isset($dados['ETD_PESSOA'])){
				$sr[] = " ETD_PESSOA = '".$this->escape_string($dados['ETD_PESSOA'])."' ";
			}
			if(isset($dados['ETD_CNPJ_CPF'])){
				$sr[] = " ETD_CNPJ_CPF = '".$this->escape_string($dados['ETD_CNPJ_CPF'])."' ";
			}
			if(isset($dados['ETD_INSC_EST_RG'])){
				$sr[] = " ETD_INSC_EST_RG = '".$this->escape_string($dados['ETD_INSC_EST_RG'])."' ";
			}
			if(isset($dados['ETD_NOME'])){
				$sr[] = " ETD_NOME = '".$this->escape_string($dados['ETD_NOME'])."' ";
			}
			if(isset($dados['ETD_FANTASIA'])){
				$sr[] = " ETD_FANTASIA = '".$this->escape_string($dados['ETD_FANTASIA'])."' ";
			}
			if(isset($dados['ETD_RAM_ATIV'])){
				$sr[] = " ETD_RAM_ATIV = ".$this->escape_string($dados['ETD_RAM_ATIV'])." ";
			}
			if(isset($dados['ETD_DT_CADASTRO'])){
				$sr[] = " ETD_DT_CADASTRO = '".$this->escape_string($dados['ETD_DT_CADASTRO'])."' ";
			}
			if(isset($dados['ETD_SITE'])){
				$sr[] = " ETD_SITE = '".$this->escape_string($dados['ETD_SITE'])."' ";
			}
			if(isset($dados['ETD_EMAIL'])){
				$sr[] = " ETD_EMAIL = '".$this->escape_string($dados['ETD_EMAIL'])."' ";
			}
			if(isset($dados['ETD_OBS'])){
				$sr[] = " ETD_OBS = '".$this->escape_string($dados['ETD_OBS'])."' ";
			}
			if(isset($dados['ETD_ATIVA'])){
				$sr[] = " ETD_ATIVA = '".$this->escape_string($dados['ETD_ATIVA'])."' ";
			}
			if(isset($dados['ETD_DT_ANIV'])){
				$sr[] = " ETD_DT_ANIV = '".$dados['ETD_DT_ANIV']."' ";
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