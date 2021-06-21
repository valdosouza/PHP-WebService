<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class CliInterface extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if(!isset($dados['CIF_CODPRJ'])){
			$dados['CIF_CODPRJ'] = 0; 
		}
		if(!isset($dados['CIF_CODCLI'])){
			$dados['CIF_CODCLI'] = 0; 
		}
		if(!isset($dados['CIF_CODIFC'])){
			$dados['CIF_CODIFC'] = 0; 
		}
		/*
		 * COLOQUEI COMO CHAVE PRIMARIA OS 3 PRIMEIROS CAMPOS
		 * E TROQUEI O INSERT POR REPLACE
		 * ENTAO SE NAO TIVER "INSERE" CASO CONTRARIO "ATUALIZA" ;) 
		 */
		$sql = "REPLACE INTO
					tb_cli_interface(
					CIF_CODPRJ,
					CIF_CODCLI,
					CIF_CODIFC,
					CIF_ATIVO,
					CIF_ATUALIZAR
					)
				VALUES(
				".@$dados['CIF_CODPRJ'].",
				".@$dados['CIF_CODCLI'].",
				".@$dados['CIF_CODIFC'].",
				'".$this->escape_string(@$dados['CIF_ATIVO'])."',
				'".$this->escape_string(@$dados['CIF_ATUALIZAR'])."'
				)";
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
		
		$sql = "UPDATE 
					tb_cli_interface
				SET 
					CIF_ATIVO='".$this->escape_string($dados['CIF_ATIVO'])."',
					CIF_ATUALIZAR='".$this->escape_string($dados['CIF_ATUALIZAR'])."'
				WHERE CIF_CODPRJ = ".$dados['CIF_CODPRJ']." AND CIF_CODCLI = ".$dados['CIF_CODCLI']." AND CIF_CODIFC=".$dados['CIF_CODICF'];
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
		$sql = "DELETE FROM tb_cli_interface WHERE CIF_CODPRJ = ".$dados['CIF_CODPRJ']." AND CIF_CODCLI = ".$dados['CIF_CODCLI']." AND CIF_CODIFC=".$dados['CIF_CODICF'];
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
		$sql = "SELECT * FROM tb_cli_interface ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['CIF_CODPRJ'])){
				$sr[] = " CIF_CODPRJ = ".$dados['CIF_COPRJ']." ";
			}
			if(isset($dados['CIF_CODCLI'])){
				$sr[] = " CIF_CODCLI = ".$dados['CIF_CODCLI']." ";
			}
			if(isset($dados['CIF_CODIFC'])){
				$sr[] = " CIF_CODIFC = ".$dados['CIF_CODIFC']." ";
			}
			if(isset($dados['CIF_ATIVO'])){
				$sr[] = " CIF_ATIVO = '".$this->escape_string($dados['CIF_ATIVO'])."' ";
			}
			if(isset($dados['CIF_ATUALIZAR'])){
				$sr[] = " CIF_ATUALIZAR = '".$this->escape_string($dados['CIF_ATUALIZAR'])."' ";
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