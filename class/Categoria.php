<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Categoria extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}

	public function insert($dados){
		if($this->hasThisPk(@$dados['CAT_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_categorias', $dados, 'CAT_CODIGO');
		$this->query($sql);
		$r = $this->affected_rows();
		if($r>0){
			return 1;
		}else{
			return $this->error();
		}
		return 'erro';
	}
	
	public function update($dados){
		$sql = $this->geraSql('update', 'tb_categorias', $dados, 'CAT_CODIGO');
		$this->query($sql);
		$r = $this->affected_rows();
		if($r>0){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "dados identicos";
	}
	
	public function delete($dados){
			if(is_null(@$dados['CAT_CODIGO'])){
				$dados['CAT_CODIGO'] = 0;
			}
			if(is_null(@$dados['CAT_CODETB'])){
				$dados['CAT_CODETB'] = 0;
			}
			if(is_null(@$dados['CAT_CODPAI'])){
				$dados['CAT_CODPAI'] = 0;
			}
			if(is_null(@$dados['CAT_CODDKP'])){
				$dados['CAT_CODDKP'] = 0;
			}
			if($dados['CAT_CODDKP']==0){
				$where = " CAT_CODIGO=".$dados['CAT_CODIGO']." ";
			}else{
				$where = " CAT_CODPAI=".$dados['CAT_CODPAI']." AND CAT_CODDKP=".$dados['CAT_CODDKP'];
			}
			$sql = "DELETE FROM 
						tb_categorias 
					WHERE 
						".$where." ";
			$this->query($sql);
			$r = $this->affected_rows();
			if($r>0){
				return 1;
			}else{
				return $this->error();
			}
		
		return 'erro';
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_categorias ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['CAT_CODIGO'])){
				$sr[] = " CAT_CODIGO = ".$dados['CAT_CODIGO']." ";
			}
			if(isset($dados['CAT_CODPAI'])){
				$sr[] = " CAT_CODPAI = ".$dados['CAT_CODPAI']." ";
			}
			if(isset($dados['CAT_DESCRICAO'])){
				$sr[] = " CAT_DESCRICAO = '".$this->escape_string($dados['CAT_DESCRICAO'])."' ";
			}
			if(isset($dados['CAT_NIVEL'])){
				$sr[] = " CAT_NIVEL = ".$dados['CAT_NIVEL']." ";
			}
			if(isset($dados['CAT_CODDKP'])){
				$sr[] = " CAT_CODDKP = ".$dados['CAT_CODDKP']." ";
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
		return $this->query_fetch($sql);
	}
	
	public function customQuery($dados){
		$r = $this->query_fetch($dados['QUERY']);
		if(empty($r)){
			return "consulta vazia";
		}
		return $r;
	}
	/**
	 * Verifica se uma categoria existe
	 * @param Integer $CAT_CODIGO
	 * @return Boolean true|false 
	 */
	public function hasThisPk($pk){
		$sql = "SELECT 1 FROM tb_categorias WHERE CAT_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	public function categoriaExists($CAT_CODIGO = 0){
		$sql = "SELECT CAT_CODETB FROM tb_categorias WHERE CAT_CODIGO=".$CAT_CODIGO;
		$id = $this->query($sql);
		$r=0;
		if(!is_resource($id)){
			return false;
		}else{
			$r =  $this->num_rows($id);
		}
		if($r>0){
			return true;
		}
		return false;
	}
	public function pesquisaCDKP($dados){
		$sql = "SELECT CAT_CODIGO FROM tb_categorias WHERE CAT_CODDKP=".$dados['CAT_CODDKP']." AND CAT_CODPAI = 0 ";
		$d = $this->query_return_one($sql);
		if(empty($d['CAT_CODIGO'])){return 0;}
		return $d['CAT_CODIGO'];
	}
	public function pesquisaSDKP($dados){
		$sql = "SELECT IFNULL(CAT_CODIGO,0) FROM tb_categorias WHERE CAT_CODDKP=".$dados['CAT_CODIGO']." AND CAT_CODPAI > 0 ";
		$d = $this->query_return_one($sql);
		if(empty($d['CAT_CODIGO'])){return 0;}
		return $d['CAT_CODIGO'];
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
			case 'CDKP': //Categoria desktop
				$r = $this->pesquisaCDKP($dados);
			break;
			case 'SDKP': //Subcategoria Desktop
				$r = $this->pesquisaSDKP($dados);
			break;
		}
		return $r;
	}
}