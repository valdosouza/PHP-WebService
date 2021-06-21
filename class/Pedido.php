<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Pedido extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['PED_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_pedido', $dados, 'PED_CODIGO');
		$this->query($sql);
		if($this->affected_rows()){
			return $this->last_inserted_id();
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "erro";
	}
	
	public function update($dados){
		$sql = $this->geraSql('update', 'tb_pedido', $dados, 'PED_CODIGO');
		$this->query($sql);
		if($this->affected_rows()){
			return $dados['PED_CODIGO'];
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "dados identicos";
	}
	
	public function delete($dados){
		$sql = "DELETE FROM tb_pedido WHERE PED_CODIGO=".$dados['PED_CODIGO'];
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
		$sql = "SELECT 1 FROM tb_pedido WHERE PED_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_pedido ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['PED_CODIGO'])){
				$sr[] = " PED_CODIGO = ".$dados['PED_CODIGO']." ";
			}
			if(isset($dados['PED_CODETB'])){
				$sr[] = " PED_CODETB = ".$dados['PED_CODETB']." ";
			}
			if(isset($dados['PED_CODETD'])){
				$sr[] = " PED_CODETD = ".$dados['PED_CODETD']." ";
			}
			if(isset($dados['PED_NUMERO'])){
				$sr[] = " PED_NUMERO = ".$dados['PED_NUMERO']." ";
			}
			if(isset($dados['PED_TIPO'])){
				$sr[] = " PED_TIPO = ".$dados['PED_TIPO']." ";
			}
			if(isset($dados['PED_CODUSU'])){
				$sr[] = " PED_CODUSU = ".$dados['PED_CODUSU']." ";
			}
			if(isset($dados['PED_DATA'])){
				$sr[] = " PED_DATA = '".$this->escape_string($dados['PED_DATA'])."' ";
			}
			if(isset($dados['PED_CODVDO'])){
				$sr[] = " PED_CODVDO = ".$dados['PED_CODVDO']." ";
			}
			if(isset($dados['PED_CODFPG'])){
				$sr[] = " PED_CODFPG = ".$dados['PED_CODFPG']." ";
			}
			if(isset($dados['PED_PRAZO'])){
				$sr[] = " PED_PRAZO = ".$this->escape_string($dados['PED_PRAZO'])." ";
			}
			if(isset($dados['PED_CODEND'])){
				$sr[] = " PED_CODEND = ".$dados['PED_CODEND']." ";
			}
			if(isset($dados['PED_VL_FRETE'])){
				$sr[] = " PED_VL_FRETE = ".$dados['PED_VL_FRETE']." ";
			}
			if(isset($dados['PED_ALIQ_DESCONTO'])){
				$sr[] = " PED_ALIQ_DESCONTO = ".$dados['PED_ALIQ_DESCONTO']." ";
			}
			if(isset($dados['PED_VL_DESCONTO'])){
				$sr[] = " PED_VL_DESCONTO = ".$dados['PED_VL_DESCONTO']." ";
			}
			if(isset($dados['PED_VL_PEDIDO'])){
				$sr[] = " PED_VL_PEDIDO = ".$dados['PED_VL_PEDIDO']." ";
			}
			if(isset($dados['PED_FATURADO'])){
				$sr[] = " PED_FATURADO = '".$this->escape_string($dados['PED_FATURADO'])."' ";
			}
			if(isset($dados['PED_DT_ENTREGA'])){
				$sr[] = " PED_DT_ENTREGA = ".$this->escape_string($dados['PED_DT_ENTREGA'])." ";
			}
			if(isset($dados['PED_CODTRP'])){
				$sr[] = " PED_CODTRP = ".$dados['PED_CODTRP']." ";
			}
			if(isset($dados['PED_CTA_FRETE'])){
				$sr[] = " PED_CTA_FRETE = ".$dados['PED_CTA_FRETE']." ";
			}
			if(isset($dados['PED_EMUSO'])){
				$sr[] = " PED_EMUSO = '".$this->escape_string($dados['PED_EMUSO'])."' ";
			}
			if(isset($dados['PED_CODENT'])){
				$sr[] = " PED_CODENT = ".$dados['PED_CODENT']." ";
			}
			if(isset($dados['PED_CODFAT'])){
				$sr[] = " PED_CODFAT = ".$dados['PED_CODFAT']." ";
			}
			if(isset($dados['PED_CODCOB'])){
				$sr[] = " PED_CODCOB = ".$dados['PED_CODCOB']." ";
			}
			if(isset($dados['PED_APROVADO'])){
				$sr[] = " PED_APROVADO = '".$this->escape_string($dados['PED_APROVADO'])."' ";
			}
			if(isset($dados['PED_VL_ODESPESA'])){
				$sr[] = " PED_VL_ODESPESA = ".$dados['PED_VL_ODESPESA']." ";
			}
			if(isset($dados['PED_OBS'])){
				$sr[] = " PED_OBS = '".$this->escape_string($dados['PED_OBS'])."' ";
			}
			if(isset($dados['PED_AUTORIZADO'])){
				$sr[] = " PED_AUTORIZADO = ".$this->escape_string($dados['PED_AUTORIZADO'])." ";
			}
			if(isset($dados['PED_CODCEF'])){
				$sr[] = " PED_CODCEF = ".$dados['PED_CODCEF']." ";
			}
			if(isset($dados['PED_ID_ECF'])){
				$sr[] = " PED_ID_ECF = ".$dados['PED_ID_ECF']." ";
			}
			if(isset($dados['PED_NUM_ORCA'])){
				$sr[] = " PED_NUM_ORCA = ".$dados['PED_NUM_ORCA']." ";
			}
			if(isset($dados['PED_VALIDADE'])){
				$sr[] = " PED_VALIDADE = '".$this->escape_string($dados['PED_VALIDADE'])."' ";
			}
			
			if(isset($dados['PED_CODIGO'])){
				$sr[] = " PED_CODIGO = ".$dados['PED_CODIGO']." ";
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
		}
		return $r;
	}
}