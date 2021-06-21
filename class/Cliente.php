<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Cliente extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['CLI_CODETD'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_cliente', $dados, 'CLI_CODETD');
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
		$sql = $this->geraSql('update', 'tb_cliente', $dados, 'CLI_CODETD');
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
		if(!isset($dados['CLI_CODETD'])){
			$dados['CLI_CODETD'] = 0;
		}
		$sql = "DELETE FROM tb_cliente WHERE CLI_CODETD=".$dados['CLI_CODETD'];
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
		$sql = "SELECT 1 FROM tb_cliente WHERE CLI_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_cliente ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['CLI_CODETD'])){
				$sr[] = " CLI_CODETD = ".$dados['CLI_CODETD']." ";
			}
			if(isset($dados['CLI_CONSUMIDOR'])){
				$sr[] = " CLI_CONSUMIDOR = '".$this->escape_string($dados['CLI_CONSUMIDOR'])."' ";
			}
			if(isset($dados['CLI_MICRO_EMP'])){
				$sr[] = " CLI_MICRO_EMP = '".$this->escape_string($dados['CLI_MICRO_EMP'])."' ";
			}
			if(isset($dados['CLI_ST_CRED'])){
				$sr[] = " CLI_ST_CRED = '".$this->escape_string($dados['CLI_ST_CRED'])."' ";
			}
			if(isset($dados['CLI_VL_CRED'])){
				$sr[] = " CLI_VL_CRED = ".$dados['CLI_VL_CRED']." ";
			}
			if(isset($dados['CLI_ML_DRT'])){
				$sr[] = " CLI_ML_DRT = '".$this->escape_string($dados['CLI_ML_DRT'])."' ";
			}
			if(isset($dados['CLI_CODTRP'])){
				$sr[] = " CLI_CODTRP = ".$dados['CLI_CODTRP']." ";
			}
			if(isset($dados['CLI_DT_ULT_MOV'])){
				$sr[] = " CLI_DT_ULT_MOV = '".$this->escape_string($dados['CLI_DT_ULT_MOV'])."' ";
			}
			if(isset($dados['CLI_CODFPG'])){
				$sr[] = " CLI_CODFPG = ".$dados['CLI_CODFPG']." ";
			}
			if(isset($dados['CLI_ATIVO'])){
				$sr[] = " CLI_ATIVO = '".$this->escape_string($dados['CLI_ATIVO'])."' ";
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
	public function tabletGetAll(){
		$sql = "SELECT 
				    cli.CLI_CODETD AS CLI_CODIGO,
				    IF(etd.ETD_PESSOA='J', etd.ETD_FANTASIA , etd.ETD_NOME) AS CLI_NOME,
				    IFNULL(etd.ETD_EMAIL, '') AS CLI_EMAIL,
				    IFNULL(fne.FNE_NUMERO, '') AS CLI_TELEFONE
				FROM 
				    tb_cliente cli
				    LEFT JOIN 
				        tb_entidade etd
				    ON
				        cli.CLI_CODETD=etd.ETD_CODIGO
				        LEFT JOIN
				            tb_endereco ende
				        ON
				            ende.END_CODETD=etd.ETD_CODIGO AND
				            ende.END_PRINCIPAL='S'
				            LEFT JOIN
				                tb_fone fne
				            ON
				                fne.FNE_CODEND=ende.END_CODIGO";
		$r = $this->query_fetch($sql);
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
			//PARA ANDROID TABLET
			case 'TABLET_GET_ALL': //Retorna todos os dados do webservice para formato cliente do android
				$r = $this->tabletGetAll();
			break;
		}
		return $r;
	}
}