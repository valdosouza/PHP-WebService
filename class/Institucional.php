<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Institucional extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if(!isset($dados['INS_CODETB'])){
			$dados['INS_CODETB'] = 0;
		}
		$sql = "INSERT INTO
					tb_institucional(
					INS_AREANOME,
					INS_CODETB,
					INS_TITULO, 
					INS_TEXTO
					)
				VALUES(
				'".$this->escape_string($dados['INS_AREANOME'])."',
				".$dados['INS_CODETB'].",
				'".$this->escape_string($dados['INS_TITULO'])."',
				'".$this->escape_string(base64_decode($dados['INS_TEXTO']))."'
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
		if(!isset($dados['INS_CODETB'])){
			$dados['INS_CODETB'] = 0;
		}
		$sql = "UPDATE 
					tb_institucional
				SET 
					INS_TITULO='".$this->escape_string($dados['INS_TITULO'])."', 
					INS_TEXTO='".$this->escape_string(base64_decode($dados['INS_TEXTO']))."'
				WHERE 
					INS_AREANOME='".$this->escape_string($dados['INS_AREANOME'])."' AND
					INS_CODETB=".$dados['INS_CODETB'];
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
		$sql = "DELETE FROM tb_institucional 
				WHERE 
					INS_AREANOME='".$this->escape_string($dados['INS_AREANOME'])."' AND
					INS_CODETB=".$dados['INS_CODETB'];
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
		$sql = "SELECT * FROM tb_institucional ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['INS_AREANOME'])){
				$sr[] = " INS_AREANOME = '".$this->escape_string($dados['INS_AREANOME'])."' ";
			}
			if(isset($dados['INS_CODETB'])){
				$sr[] = " INS_CODETB = ".$dados['INS_CODETB']." ";
			}
			if(isset($dados['INS_TITULO'])){
				$sr[] = " INS_TITULO = '".$this->escape_string($dados['INS_TITULO'])."' ";
			}
			if(isset($dados['INS_TEXTO'])){
				$sr[] = " INS_TEXTO = '".$this->escape_string(base64_decode($dados['INS_TEXTO']))."' ";
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
		$arr = array();
		foreach ($r as $rr){
			$x = array(
				'INS_AREANOME'=>@$rr['INS_AREANOME'],
				'INS_CODETB'=>@$rr['INS_CODETB'],
				'INS_TITULO'=>@$rr['INS_TITULO'],
				'INS_TEXTO'=>base64_encode(@$rr['INS_TEXTO'])
			);
			$arr[] = $x;
		}
		return $arr;
	}
	
	public function customQuery($dados){
		$r = $this->query_fetch($dados['QUERY']);
		if(empty($r)){
			return "consulta vazia";
		}
		$arr = array();
		foreach ($r as $rr){
			$x = array(
				'INS_AREANOME'=>@$rr['INS_AREANOME'],
				'INS_CODETB'=>@$rr['INS_CODETB'],
				'INS_TITULO'=>@$rr['INS_TITULO'],
				'INS_TEXTO'=>base64_encode(@$rr['INS_TEXTO'])
			);
			$arr[] = $x;
		}
		return $arr;
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