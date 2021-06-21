<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Produto extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['PRO_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_produto', $dados, 'PRO_CODIGO');
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
		$sql = $this->geraSql('update', 'tb_produto', $dados, 'PRO_CODIGO');
		$this->query($sql);
		return $sql;
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
		$sql = "DELETE FROM tb_produto WHERE PRO_CODIGO=".$dados['PRO_CODIGO'];
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
		$sql = "SELECT 1 FROM tb_produto WHERE PRO_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_produto ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['PRO_CODIGO'])){
				$sr[] = " PRO_CODIGO = ".$dados['PRO_CODIGO']." ";
			}
			if(isset($dados['PRO_CODETB'])){
				$sr[] = " PRO_CODETB = ".$dados['PRO_CODETB']." ";
			}
			if(isset($dados['PRO_CODIGOFAB'])){
				$sr[] = " PRO_CODIGOFAB = '".$this->escape_string($dados['PRO_CODIGOFAB'])."' ";
			}
			if(isset($dados['PRO_CODIGOBAR'])){
				$sr[] = " PRO_CODIGOBAR = '".$this->escape_string($dados['PRO_CODIGOBAR'])."' ";
			}
			if(isset($dados['PRO_CODIGOFOR'])){
				$sr[] = " PRO_CODIGOFOR = '".$this->escape_string($dados['PRO_CODIGOFOR'])."' ";
			}
			if(isset($dados['PRO_CODIGONCM'])){
				$sr[] = " PRO_CODIGONCM = '".$this->escape_string($dados['PRO_CODIGONCM'])."' ";
			}
			if(isset($dados['PRO_DESCRICAO'])){
				$sr[] = " PRO_DESCRICAO = '".$this->escape_string($dados['PRO_DESCRICAO'])."' ";
			}
			if(isset($dados['PRO_CODMED'])){
				$sr[] = " PRO_CODMED = ".$dados['PRO_CODMED']." ";
			}
			if(isset($dados['PRO_CODEMB'])){
				$sr[] = " PRO_CODEMB = ".$dados['PRO_CODEMB']." ";
			}
			if(isset($dados['PRO_CODCAT'])){
				$sr[] = " PRO_CODCAT = ".$dados['PRO_CODCAT']." ";
			}
			if(isset($dados['PRO_DIVISOR'])){
				$sr[] = " PRO_DIVISOR = ".$dados['PRO_DIVISOR']." ";
			}
			if(isset($dados['PRO_ORIGEM'])){
				$sr[] = " PRO_ORIGEM = '".$this->escape_string($dados['PRO_ORIGEM'])."' ";
			}
			if(isset($dados['PRO_TIPO'])){
				$sr[] = " PRO_TIPO = '".$this->escape_string($dados['PRO_TIPO'])."' ";
			}
			if(isset($dados['PRO_LOCAL'])){
				$sr[] = " PRO_LOCAL = '".$this->escape_string($dados['PRO_LOCAL'])."' ";
			}
			if(isset($dados['PRO_PESO'])){
				$sr[] = " PRO_PESO = '".$this->escape_string($dados['PRO_PESO'])."' ";
			}
			if(isset($dados['PRO_LARGURA'])){
				$sr[] = " PRO_LARGURA = '".$this->escape_string($dados['PRO_LARGURA'])."' ";
			}
			if(isset($dados['PRO_COMPRIMENTO'])){
				$sr[] = " PRO_COMPRIMENTO = '".$this->escape_string($dados['PRO_COMPRIMENTO'])."' ";
			}
			if(isset($dados['PRO_ALTURA'])){
				$sr[] = " PRO_ALTURA = '".$this->escape_string($dados['PRO_ALTURA'])."' ";
			}
			if(isset($dados['PRO_VL_FABRICA'])){
				$sr[] = " PRO_VL_FABRICA = '".$this->escape_string($dados['PRO_VL_FABRICA'])."' ";
			}
			if(isset($dados['PRO_VL_CUSTOMED'])){
				$sr[] = " PRO_VL_CUSTOMED = '".$this->escape_string($dados['PRO_VL_CUSTOMED'])."' ";
			}
			if(isset($dados['PRO_VL_CUSTOMEDANT'])){
				$sr[] = " PRO_VL_CUSTOMEDANT = '".$this->escape_string($dados['PRO_VL_CUSTOMEDANT'])."' ";
			}
			if(isset($dados['PRO_VL_CUSTO'])){
				$sr[] = " PRO_VL_CUSTO = '".$this->escape_string($dados['PRO_VL_CUSTO'])."' ";
			}
			if(isset($dados['PRO_VL_CUSTOANT'])){
				$sr[] = " PRO_VL_CUSTOANT = '".$this->escape_string($dados['PRO_VL_CUSTOANT'])."' ";
			}
			if(isset($dados['PRO_QTDE_MIN'])){
				$sr[] = " PRO_QTDE_MIN = '".$this->escape_string($dados['PRO_QTDE_MIN'])."' ";
			}
			if(isset($dados['PRO_DETALHES'])){
				$sr[] = " PRO_DETALHES = '".$this->escape_string($dados['PRO_DETALHES'])."' ";
			}
			if(isset($dados['PRO_SUB_TRIB'])){
				$sr[] = " PRO_SUB_TRIB = '".$this->escape_string($dados['PRO_SUB_TRIB'])."' ";
			}
			if(isset($dados['PRO_CAMPANHA'])){
				$sr[] = " PRO_CAMPANHA = '".$this->escape_string($dados['PRO_CAMPANHA'])."' ";
			}
			if(isset($dados['PRO_DESTAQUE'])){
				$sr[] = " PRO_DESTAQUE = '".$this->escape_string($dados['PRO_DESTAQUE'])."' ";
			}
			if(isset($dados['PRO_ATIVO'])){
				$sr[] = " PRO_ATIVO = '".$this->escape_string($dados['PRO_ATIVO'])."' ";
			}
			if(isset($dados['PRO_IMPRIME'])){
				$sr[] = " PRO_IMPRIME = '".$this->escape_string($dados['PRO_IMPRIME'])."' ";
			}
			if(isset($dados['PRO_EST_NEG'])){
				$sr[] = " PRO_EST_NEG = '".$this->escape_string($dados['PRO_EST_NEG'])."' ";
			}
			if(isset($dados['PRO_EXCL_REV'])){
				$sr[] = " PRO_EXCL_REV = '".$this->escape_string($dados['PRO_EXCL_REV'])."' ";
			}
			if(isset($dados['PRO_INTERNET'])){
				$sr[] = " PRO_INTERNET = '".$this->escape_string($dados['PRO_INTERNET'])."' ";
			}
			if(isset($dados['PRO_MAISVENDIDO'])){
				$sr[] = " PRO_MAISVENDIDO = '".$this->escape_string($dados['PRO_MAISVENDIDO'])."' ";
			}
			if(isset($dados['PRO_VL_INSTALA'])){
				$sr[] = " PRO_VL_INSTALA = '".$this->escape_string($dados['PRO_VL_INSTALA'])."' ";
			}
			if(isset($dados['PRO_COMPOSICAO'])){
				$sr[] = " PRO_COMPOSICAO = '".$this->escape_string($dados['PRO_COMPOSICAO'])."' ";
			}
			if(isset($dados['PRO_APLICACAO'])){
				$sr[] = " PRO_APLICACAO = '".$this->escape_string($dados['PRO_APLICACAO'])."' ";
			}
			if(isset($dados['PRO_CODMRC'])){
				$sr[] = " PRO_CODMRC = ".$dados['PRO_CODMRC']." ";
			}
			if(isset($dados['PRO_TRIBUTACAO'])){
				$sr[] = " PRO_TRIBUTACAO = '".$this->escape_string($dados['PRO_TRIBUTACAO'])."' ";
			}
			if(isset($dados['PRO_CODIMG_CAPA'])){
				$sr[] = " PRO_CODIMG_CAPA = ".$dados['PRO_CODIMG_CAPA']." ";
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