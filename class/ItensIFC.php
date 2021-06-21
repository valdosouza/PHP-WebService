<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class ItensIFC extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if(!isset($dados['IIF_CODIGO'])){
			$dados['IIF_CODIGO'] = 0;
		}
		if(!isset($dados['IIF_CODIFC'])){
			$dados['IIF_CODIFC'] = 0;
		}
		if(!isset($dados['IIF_CODOPF'])){
			$dados['IIF_CODOPF'] = 0;
		}
		
		$sql = "INSERT INTO
					tb_itens_ifc(IIF_CODIGO, IIF_CODIFC, IIF_CODOPF)
				VALUES(IF(".$dados['IIF_CODIGO']."=0, (SELECT MAX(IIF_CODIGO)+1 FROM tb_itens_ifc t), ".$dados['IIF_CODIGO']."),
				".$dados['IIF_CODIFC'].",
				".$dados['IIF_CODOPF']."
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
		if(!isset($dados['IIF_CODIGO'])){
			$dados['IIF_CODIGO'] = 0;
		}
		if(!isset($dados['IIF_CODIFC'])){
			$dados['IIF_CODIFC'] = 0;
		}
		if(!isset($dados['IIF_CODOPF'])){
			$dados['IIF_CODOPF'] = 0;
		}
		$sql = "UPDATE 
					tb_itens_ifc
				SET 
					IIF_CODIFC=".$dados['IIF_CODIFC'].",
					IIF_CODOPF=".$dados['IIF_CODOPF']."
				WHERE IIF_CODIGO = ".$dados['IIF_CODIGO'];
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
		$sql = "DELETE FROM tb_itens_ifc WHERE IIF_CODIFC=".$dados['IIF_CODIFC'];
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
		$sql = "SELECT * FROM tb_itens_ifc ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['IIF_CODIGO'])){
				$sr[] = " IIF_CODIGO = ".$dados['IIF_CODIGO']." ";
			}
			if(isset($dados['IIF_CODIFC'])){
				$sr[] = " IIF_CODIFC = ".$dados['IIF_CODIFC']." ";
			}
			if(isset($dados['IIF_CODOPF'])){
				$sr[] = " IIF_CODOPF = ".$dados['IIF_CODOPF']." ";
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