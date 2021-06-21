<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Estabelecimento extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['ETB_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_estabelecimento', $dados, 'ETD_CODIGO');
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
		$sql = $this->geraSql('update', 'tb_estabelecimento', $dados, 'ETB_CODIGO');
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
		$sql = "DELETE FROM tb_estabelecimento WHERE ETB_CODIGO=".$dados['ETB_CODIGO'];
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
		$sql = "SELECT 1 FROM tb_estabelecimento WHERE ETB_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_estabelecimento ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['ETB_CODIGO'])){
				$sr[] = " ETB_CODIGO = ".$dados['ETB_CODIGO']." ";
			}
			if(isset($dados['ETB_CODETD'])){
				$sr[] = " ETB_CODETD = ".$dados['ETB_CODETD']." ";
			}
			if(isset($dados['ETB_INSC_SBT'])){
				$sr[] = " ETB_INSC_SBT = '".$this->escape_string($dados['ETB_INSC_SBT'])."' ";
			}
			if(isset($dados['ETB_INSC_MUN'])){
				$sr[] = " ETB_INSC_MUN = '".$this->escape_string($dados['ETB_INSC_MUN'])."' ";
			}
			if(isset($dados['ETB_COD_FAT'])){
				$sr[] = " ETB_COD_FAT = ".$dados['ETB_COD_FAT']." ";
			}
			if(isset($dados['ETB_COD_CRT'])){
				$sr[] = " ETB_COD_CRT = ".$dados['ETB_COD_CRT']." ";
			}
			if(isset($dados['ETB_NOME'])){
				$sr[] = " ETB_NOME = '".$this->escape_string($dados['ETB_NOME'])."' ";
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