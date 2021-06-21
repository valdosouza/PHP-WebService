<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class ProjetoCliente extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		$sql = "REPLACE INTO
					tb_proj_cliente(PJC_CODPRJ, PJC_CODCLI)
				VALUES(".$dados['PJC_CODPRJ'].",".$dados['PJC_CODCLI'].")";
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
		return 'operação indisponivel';
	}
	
	public function delete($dados){
		return 'operação indisponivel';
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_proj_cliente ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['PJC_CODPRJ'])){
				$sr[] = " PJC_CODPRJ = ".$dados['PJC_CODPRJ']." ";
			}
			if(isset($dados['PJC_CODCLI'])){
				$sr[] = " PJC_CODCLI = '".$this->escape_string($dados['PJC_CODCLI'])."' ";
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