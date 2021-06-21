<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class InterfaceS extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['IFC_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_interface', $dados, 'IFC_CODIGO');
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
		$sql = $this->geraSql('update', 'tb_interface', $dados, 'IFC_CODIGO');
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
		if(!isset($dados['IFC_CODIGO'])){
			$dados['IFC_CODIGO'] = 0;
		}
		$sql = "DELETE FROM tb_interface WHERE IFC_CODIGO=".$dados['IFC_CODIGO'];
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
	
	public function search($dados){
		$sql = "SELECT * FROM tb_interface ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['IFC_CODIGO'])){
				$sr[] = " IFC_CODIGO = ".$dados['IFC_CODIGO']." ";
			}
			if(isset($dados['IFC_DESCRICAO'])){
				$sr[] = " IFC_DESCRICAO = '".$this->escape_string($dados['IFC_DESCRICAO'])."' ";
			}
			if(isset($dados['IFC_FR_NAME'])){
				$sr[] = " IFC_FR_NAME = '".$this->escape_string($dados['IFC_FR_NAME'])."' ";
			}
			if(isset($dados['IFC_CODMNU'])){
				$sr[] = " IFC_CODMNU = ".$dados['IFC_CODMNU']." ";
			}
			if(isset($dados['IFC_SISTEMA'])){
				$sr[] = " IFC_SISTEMA = '".$this->escape_string($dados['IFC_SISTEMA'])."' ";
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
	
	public function hasThisPk($pk){
		$sql = "SELECT 1 FROM tb_interface WHERE IFC_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
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
			case 'A': //addClienteToInterface
				$r = $this->addClienteToInterface($dados);
			break;
			case 'R': //rmvClienteFromInterface
				$r = $this->rmvClienteFromInterface($dados);
			break;
			case 'F': //retorna interfaces do cliente
				$r = $this->searchClienteInterfaces($dados);
			break;
		}
		return $r;
	}
	public function addClienteToInterface($dados){
		if(!isset($dados['CIF_CODCLI'])){
			$dados['CIF_CODCLI'] = 0;
		}
		if(!isset($dados['CIF_CODCLI'])){
			$dados['CIF_CODIFC'] = 0;
		}
		$sql  = "INSERT INTO 
					tb_cli_interface(CIF_CODCLI, CIF_CODIFC)
				VALUES(
					".$dados['CIF_CODCLI'].",
			      	".$dados['CIF_CODIFC']."
				)";
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
	public function rmvClienteFromInterface($dados){
		$sql = "DELETE FROM 
					tb_cli_interface 
				WHERE 
					CIF_CODCLI=".$dados['CIF_CODCLI']." AND
					CIF_CODIFC=".$dados['CIF_CODIFC'];
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
	public function searchClienteInterfaces($dados){
		$sql = "SELECT * FROM tb_interface 
				WHERE 
				IFC_CODIGO IN 
							(SELECT CIF_CODIFC
							FROM
								tb_cli_interface 
							WHERE 
								CIF_CODCLI=".$dados['CIF_CODCLI'].")";
		$r = $this->query_fetch($sql);
		if(empty($r)){
			return "consulta vazia";
		}
		return $r;
	}
}