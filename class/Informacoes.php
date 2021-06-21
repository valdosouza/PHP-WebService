<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Informacoes extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if(!isset($dados['INF_CODETB'])){
			$dados['INF_CODETB'] = 0;
		}
		$sql = "REPLACE INTO
					tb_informacoes(
					INF_AREANOME,
					INF_CODETB,
					INF_TITULO, 
					INF_TEXTO
					)
				VALUES(
				'".$this->escape_string($dados['INF_AREANOME'])."',
				".$dados['INF_CODETB'].",
				'".$this->escape_string($dados['INF_TITULO'])."',
				'".$this->escape_string(base64_decode($dados['INF_TEXTO']))."'
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
		if(!isset($dados['INF_CODETB'])){
			$dados['INF_CODETB'] = 0;
		}
		$sql = "UPDATE 
					tb_informacoes
				SET 
					INF_TITULO='".$this->escape_string($dados['INF_TITULO'])."', 
					INF_TEXTO='".$this->escape_string(base64_decode($dados['INF_TEXTO']))."'
				WHERE 
					INF_AREANOME='".$this->escape_string($dados['INF_AREANOME'])."' AND
					INF_CODETB=".$dados['INF_CODETB'];
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
		$sql = "DELETE FROM tb_informacoes 
				WHERE 
					INF_AREANOME='".$this->escape_string($dados['INF_AREANOME'])."'";
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
		$sql = "SELECT * FROM tb_informacoes ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['INF_AREANOME'])){
				$sr[] = " INF_AREANOME = '".$this->escape_string($dados['INF_AREANOME'])."' ";
			}
			if(isset($dados['INF_CODETB'])){
				$sr[] = " INF_CODETB = ".$dados['INF_CODETB']." ";
			}
			if(isset($dados['INF_TITULO'])){
				$sr[] = " INF_TITULO = '".$this->escape_string($dados['INF_TITULO'])."' ";
			}
			if(isset($dados['INF_TEXTO'])){
				$sr[] = " INF_TEXTO = '".$this->escape_string(base64_decode($dados['INF_TEXTO']))."' ";
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
				'INF_AREANOME'=>@$rr['INF_AREANOME'],
				'INF_CODETB'=>@$rr['INF_CODETB'],
				'INF_TITULO'=>@$rr['INF_TITULO'],
				'INF_TEXTO'=>base64_encode(@$rr['INF_TEXTO'])
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
				'INF_AREANOME'=>@$rr['INF_AREANOME'],
				'INF_CODETB'=>@$rr['INF_CODETB'],
				'INF_TITULO'=>@$rr['INF_TITULO'],
				'INF_TEXTO'=>base64_encode(@$rr['INF_TEXTO'])
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