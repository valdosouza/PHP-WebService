<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Script extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if(!isset($dados['SCP_CODIGO'])){
			$dados['SCP_CODIGO'] = 0;
		}
		if(!isset($dados['SCP_CODPRJ'])){
			$dados['SCP_CODPRJ'] = 0;
		}
		if(!isset($dados['SCP_CODTRF'])){
			$dados['SCP_CODTRF'] = 0;
		}
		$sql = "INSERT INTO tb_script (SCP_CODIGO, SCP_CODPRJ, SCP_CODTRF, SCP_COMANDO, SCP_TIPO)
				VALUES (".$dados['SCP_CODIGO'].", 
						".$dados['SCP_CODPRJ'].",
						".$dados['SCP_CODTRF'].",
						'".$this->escape_string($dados['SCP_COMANDO'])."',
						'".$this->escape_string($dados['SCP_TIPO'])."')";
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
		if(!isset($dados['SCP_CODIGO'])){
			$dados['SCP_CODIGO'] = 0;
		}
		if(!isset($dados['SCP_CODPRJ'])){
			$dados['SCP_CODPRJ'] = 0;
		}
		if(!isset($dados['SCP_CODTRF'])){
			$dados['SCP_CODTRF'] = 0;
		}
		$sql = "UPDATE 
					tb_script 
				SET 
					SCP_CODPRJ=".$dados['SCP_CODPRJ'].",
					SCP_CODTRF=".$dados['SCP_CODTRF'].",
					SCP_COMANDO='".$this->escape_string($dados['SCP_COMANDO'])."',
					SCP_TIPO='".$this->escape_string($dados['SCP_TIPO'])."'
				WHERE SCP_CODIGO = ".$dados['SCP_CODIGO'];
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
		if(!isset($dados['SCP_CODIGO'])){
			$dados['SCP_CODIGO'] = 0;
		}
		$sql = "DELETE FROM tb_script WHERE SCP_CODIGO=".$dados['SCP_CODIGO'];
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
		$sql = "SELECT 
					* 
				FROM 
					tb_script ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['PRJ_CODIGO'])){
				$sr[] = " SCP_CODPRJ=".$dados['PRJ_CODIGO']." ";
			}
			if(isset($dados['PRJ_NUMERO'])){
				$sr[] = " SCP_CODPRJ=(SELECT PRJ_CODIGO WHERE PRJ_NUMERO='".$this->escape_string($dados['PRJ_NUMERO'])."') ";
			}
			if(isset($dados['TRF_CODIGO'])){
				$sr[] = " TRF_CODIGO=".$dados['TRF_CODIGO']." ";
			}
			if(isset($dados['SCP_TIPO'])){
				$sr[] = " SCP_TIPO='".$this->escape_string($dados['SCP_TIPO'])."' ";
			}
			if(isset($dados['SCP_COMANDO'])){
				$sr[] = " SCP_COMANDO LIKE '%".$this->escape_string($dados['SCP_COMANDO'])."%' ";
			}
			if((isset($dados['DATA_INICIO']))&&(isset($dados['DATA_FINAL']))){
				$sr[] = " SCP_DATATIME BETWEEN '".$dados['DATA_INICIO']."' AND '".$dados['DATA_FINAL']."' ";
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