<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Projeto extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if(!isset($dados['PRJ_CODIGO'])){
			$dados['PRJ_CODIGO'] = 0;
		}
		if(!isset($dados['PRJ_CODETB'])){
			$dados['PRJ_CODETB'] = 0;
		}
		if(!isset($dados['PRJ_CODETD'])){
			$dados['PRJ_CODETD'] = 0;
		}
		$sql = "INSERT INTO
				    tb_projeto(PRJ_CODIGO,
				                PRJ_CODETB, 
				                PRJ_NUMERO, 
				                PRJ_DATA, 
				                PRJ_CODETD, 
				                PRJ_CODSIT, 
				                PRJ_DESCRICAO, 
				                PRJ_DETALHE, 
				                PRJ_DT_INICIAL, 
				                PRJ_DT_FINAL, 
				                PRJ_HR_INICIAL, 
				                PRJ_HR_FINAL,
				                PRJ_PATH_LOCAL,
				                PRJ_PATH_FTP,
				                PRJ_ATUALIZACAO)
				VALUES
				    (".$dados['PRJ_CODIGO'].",
				     ".$dados['PRJ_CODETB'].",
				     '".$this->escape_string($dados['PRJ_NUMERO'])."',
				     '".$this->escape_string($dados['PRJ_NUMERO'])."',
				     ".$dados['PRJ_CODETD'].",
				     ".$dados['PRJ_CODSIT'].",
				     '".$this->escape_string($dados['PRJ_DESCRICAO'])."',
				     '".$this->escape_string($dados['PRJ_DETALHE'])."',
				     '".$this->escape_string($dados['PRJ_DT_INICIAL'])."',
				     '".$this->escape_string($dados['PRJ_DT_FINAL'])."',
				     '".$this->escape_string($dados['PRJ_HR_INICIAL'])."',
				     '".$this->escape_string($dados['PRJ_HR_FINAL'])."',
				     '".$this->escape_string($dados['PRJ_PATH_LOCAL'])."',
				     '".$this->escape_string($dados['PRJ_PATH_FTP'])."',
				     'NOW()'
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
					tb_projeto 
				SET 
					PRJ_NUMERO='".$this->escape_string($dados['PRJ_NUMERO'])."',
					PRJ_DATA='".$this->escape_string($dados['PRJ_DATA'])."',
					PRJ_CODSIT='".$this->escape_string($dados['PRJ_CODSIT'])."',
					PRJ_DESCRICAO='".$this->escape_string($dados['PRJ_DESCRICAO'])."',
					PRJ_DETALHE='".$this->escape_string($dados['PRJ_DETALHE'])."',
					PRJ_DT_INICIAL='".$this->escape_string($dados['PRJ_DT_INICIAL'])."',
					PRJ_DT_FINAL='".$this->escape_string($dados['PRJ_DT_FINAL'])."',
					PRJ_HR_INICIAL='".$this->escape_string($dados['PRJ_HR_INICIAL'])."',
					PRJ_HR_FINAL='".$this->escape_string($dados['PRJ_HR_FINAL'])."',
					PRJ_PATH_LOCAL='".$this->escape_string($dados['PRJ_PATH_LOCAL'])."',
					PRJ_PATH_FTP='".$this->escape_string($dados['PRJ_PATH_FTP'])."',
					PRJ_ATUALIZACAO=NOW()
				WHERE PRJ_CODIGO = ".$dados['PRJ_CODIGO'];
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
		$sql = "DELETE FROM tb_projeto WHERE PRJ_CODIGO=".$dados['PRJ_CODIGO'];
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
					tb_projeto ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['PRJ_CODIGO'])){
				$sr[] = " PRJ_CODIGO = ".$dados["PRJ_CODIGO"]." ";
			}
			if(isset($dados['PRJ_CODETB'])){
				$sr[] = " PRJ_CODETB = ".$dados["PRJ_CODETB"]." ";
			}
			if(isset($dados['PRJ_NUMERO'])){
				$sr[] = " PRJ_NUMERO = '".$this->escape_string($dados["PRJ_NUMERO"])."' ";
			}
			if(isset($dados['PRJ_DATA'])){
				$sr[] = " PRJ_DATA = '".$this->escape_string($dados["PRJ_DATA"])."' ";
			}
			if(isset($dados['PRJ_CODETD'])){
				$sr[] = " PRJ_CODETD = ".$dados["PRJ_CODETD"]." ";
			}
			if(isset($dados['PRJ_CODSIT'])){
				$sr[] = " PRJ_CODSIT = ".$dados["PRJ_CODSIT"]." ";
			}
			if(isset($dados['PRJ_DESCRICAO'])){
				$sr[] = " PRJ_DESCRICAO LIKE '%".$this->escape_string($dados["PRJ_DESCRICAO"])."%' ";
			}
			if(isset($dados['PRJ_DETALHE'])){
				$sr[] = " PRJ_DETALHE LIKE '%".$this->escape_string($dados["PRJ_DETALHE"])."%' ";
			}
			if(isset($dados['PRJ_DT_INICIAL'])){
				$sr[] = " PRJ_DT_INICIAL = '".$this->escape_string($dados["PRJ_DT_INICIAL"])."' ";
			}
			if(isset($dados['PRJ_DT_FINAL'])){
				$sr[] = " PRJ_DT_FINAL = '".$this->escape_string($dados["PRJ_DT_FINAL"])."' ";
			}
			if(isset($dados['PRJ_HR_INICIAL'])){
				$sr[] = " PRJ_HR_INICIAL = '".$this->escape_string($dados["PRJ_HR_INICIAL"])."' ";
			}
			if(isset($dados['PRJ_HR_FINAL'])){
				$sr[] = " PRJ_HR_FINAL = '".$this->escape_string($dados["PRJ_HR_FINAL"])."' ";
			}
			if(isset($dados['PRJ_PATH_LOCAL'])){
				$sr[] = " PRJ_PATH_LOCAL = '".$this->escape_string($dados["PRJ_PATH_LOCAL"])."' ";
			}
			if(isset($dados['PRJ_PATH_FTP'])){
				$sr[] = " PRJ_PATH_FTP = '".$this->escape_string($dados["PRJ_PATH_FTP"])."' ";
			}
			if(isset($dados['PRJ_ATUALIZACAO'])){
				$sr[] = " PRJ_ATUALIZACAO = '".$this->escape_string($dados["PRJ_ATUALIZACAO"])."' ";
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