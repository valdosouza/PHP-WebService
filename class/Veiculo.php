<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Veiculo extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['VEI_PLACA'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_veiculo', $dados, null);
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
		$sql = $this->geraSql('update', 'tb_veiculo', $dados, 'VEI_PLACA');
		$this->query($sql);
		if($this->affected_rows()){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "1";
	}
	
	public function delete($dados){
		$sql = "DELETE FROM tb_veiculo WHERE VEI_CODIGO=".@$dados['VEI_CODIGO'];
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
		$sql = "SELECT 1 FROM tb_veiculo WHERE VEI_PLACA=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	public function search($dados){
		$sql = "SELECT * FROM tb_veiculo ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['VEI_CODIGO'])){
				$sr[] = " VEI_CODIGO = ".@$dados['VEI_CODIGO']." ";
			}
			if(isset($dados['VEI_CODCLI'])){
				$sr[] = " VEI_CODCLI = ".@$dados['VEI_CODCLI']." ";
			}
			if(isset($dados['VEI_PLACA'])){
				$sr[] = " VEI_PLACA = '".$this->escape_string(@$dados['VEI_PLACA'])."' ";
			}
			if(isset($dados['VEI_FROTA'])){
				$sr[] = " VEI_FROTA = ".@$dados['VEI_FROTA']." ";
			}
			if(isset($dados['VEI_CODTPV'])){
				$sr[] = " VEI_CODTPV = ".@$dados['VEI_CODTPV']." ";
			}
			if(isset($dados['VEI_CODMRC'])){
				$sr[] = " VEI_CODMRC = ".@$dados['VEI_CODMRC']." ";
			}
			if(isset($dados['VEI_CODMOD'])){
				$sr[] = " VEI_CODMOD = ".@$dados['VEI_CODMOD']." ";
			}
			if(isset($dados['VEI_ANO'])){
				$sr[] = " VEI_ANO = '".$this->escape_string(@$dados['VEI_ANO'])."' ";
			}
			if(isset($dados['VEI_CILINDRADA'])){
				$sr[] = " VEI_CILINDRADA = '".$this->escape_string(@$dados['VEI_CILINDRADA'])."' ";
			}
			if(isset($dados['VEI_CODCOR'])){
				$sr[] = " VEI_CODCOR = ".@$dados['VEI_CODCOR']." ";
			}
			if(isset($dados['VEI_CODDKP'])){
				$sr[] = " VEI_CODDKP = ".@$dados['VEI_CODDKP']." ";
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
				return 'opera��o inv�lida';
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
			case 'CDKP': //PEGA CODDKP
				$r = $this->pesquisaCDKP($dados);
			break;
		}
		return $r;
	}
	public function pesquisaCDKP($dados){
		$sql = "SELECT VEI_CODIGO FROM tb_veiculo WHERE VEI_CODDKP=".$dados['VEI_CODWEB'];
		$d = $this->query_return_one($sql);
		if(empty($d['VEI_CODIGO'])){return 0;}
		return $d['VEI_CODIGO'];
	}
}