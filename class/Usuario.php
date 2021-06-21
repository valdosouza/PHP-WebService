<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Usuario extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['USU_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_usuario', $dados, 'USU_CODIGO');
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
		$sql = $this->geraSql('update', 'tb_usuario', $dados, 'USU_CODIGO');
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
		$sql = "DELETE FROM tb_usuario WHERE USU_CODIGO=".$dados['USU_CODIGO'];
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
		$sql = "SELECT 1 FROM tb_usuario WHERE USU_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_usuario ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['USU_CODIGO'])){
				$sr[] = " USU_CODIGO = ".$dados['USU_CODIGO']." ";
			}
			if(isset($dados['USU_NOME'])){
				$sr[] = " USU_NOME = '".$this->escape_string($dados['USU_NOME'])."' ";
			}
			if(isset($dados['USU_LOGIN'])){
				$sr[] = " USU_LOGIN = '".$this->escape_string($dados['USU_LOGIN'])."' ";
			}
			if(isset($dados['USU_SENHA'])){
				$sr[] = " USU_SENHA = '".$this->escape_string($dados['USU_SENHA'])."' ";
			}
			if(isset($dados['USU_NIVEL'])){
				$sr[] = " USU_NIVEL = '".$this->escape_string($dados['USU_NIVEL'])."' ";
			}
			if(isset($dados['USU_SRV_SMTP'])){
				$sr[] = " USU_SRV_SMTP = '".$this->escape_string($dados['USU_SRV_SMTP'])."' ";
			}
			if(isset($dados['USU_LGN_EMAIL'])){
				$sr[] = " USU_LGN_EMAIL = '".$this->escape_string($dados['USU_LGN_EMAIL'])."' ";
			}
			if(isset($dados['USU_PWD_EMAIL'])){
				$sr[] = " USU_PWD_EMAIL = '".$this->escape_string($dados['USU_PWD_EMAIL'])."' ";
			}
			if(isset($dados['USU_USU_EMAIL'])){
				$sr[] = " USU_USU_EMAIL = '".$this->escape_string($dados['USU_USU_EMAIL'])."' ";
			}
			if(isset($dados['USU_LBL_EMAIL'])){
				$sr[] = " USU_LBL_EMAIL = '".$this->escape_string($dados['USU_LBL_EMAIL'])."' ";
			}
			if(isset($dados['USU_PORTA_EMAIL'])){
				$sr[] = " USU_PORTA_EMAIL = '".$this->escape_string($dados['USU_PORTA_EMAIL'])."' ";
			}
			if(isset($dados['USU_ATIVO'])){
				$sr[] = " USU_ATIVO = '".$this->escape_string($dados['USU_ATIVO'])."' ";
			}
			if(isset($dados['USU_REQ_AUT_EMAIL'])){
				$sr[] = " USU_REQ_AUT_EMAIL = '".$this->escape_string($dados['USU_REQ_AUT_EMAIL'])."' ";
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