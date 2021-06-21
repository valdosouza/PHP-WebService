<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class PerguntaProduto extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if($this->hasThisPk(@$dados['PPR_CODIGO'])){
			return $this->update($dados);
		}
		$sql = "INSERT INTO
					tb_pergunta_produto(
						PPR_CODPRO,
						PPR_NOME,
						PPR_EMAIL,
						PPR_DATA_PERGUNTA,
						PPR_PERGUNTA,
						PPR_DATA_RESPOSTA,
						PPR_RESPOSTA,
						PPR_PUBLICADO,
						PPR_CODDKP
					)
				VALUES(
					".$dados['PPR_CODPRO'].",
					'".$this->escape_string($dados['PPR_NOME'])."',
					'".$this->escape_string($dados['PPR_EMAIL'])."',
					NOW(),
					'".$this->escape_string($dados['PPR_PERGUNTA'])."',
					'0000-00-00 00:00:00',
					'',
					'N',
					null
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
					tb_pergunta_produto
				SET 
					PPR_DATA_RESPOSTA='".$this->escape_string($dados['PPR_DATA_RESPOSTA'])."',
					PPR_RESPOSTA='".$this->escape_string($dados['PPR_RESPOSTA'])."'
				WHERE PPR_CODIGO = ".$dados['PPR_CODIGO'];
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
		$sql = "DELETE FROM tb_pergunta_produto WHERE PPR_CODIGO=".$dados['PPR_CODIGO'];
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
		$sql = "SELECT 1 FROM tb_pergunta_produto WHERE PPR_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_pergunta_produto ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['PPR_CODIGO'])){
				$sr[] = " PPR_CODIGO = ".$dados['PPR_CODIGO']." ";
			}
			if(isset($dados['PPR_CODPRO'])){
				$sr[] = " PPR_CODPRO = ".$dados['PPR_CODPRO']." ";
			}
			if(isset($dados['PPR_CODCLI'])){
				$sr[] = " PPR_CODCLI = ".$dados['PPR_CODCLI']." ";
			}
			if(isset($dados['PPR_DATA_PERGUNTA'])){
				$sr[] = " PPR_DATA_PERGUNTA = '".$this->escape_string($dados['PPR_DATA_PERGUNTA'])."' ";
			}
			if(isset($dados['PPR_PERGUNTA'])){
				$sr[] = " PPR_PERGUNTA = '".$this->escape_string($dados['PPR_PERGUNTA'])."' ";
			}
			if(isset($dados['PPR_DATA_RESPOSTA'])){
				$sr[] = " PPR_DATA_RESPOSTA = '".$this->escape_string($dados['PPR_DATA_RESPOSTA'])."' ";
			}
			if(isset($dados['PPR_RESPOSTA'])){
				$sr[] = " PPR_RESPOSTA = '".$this->escape_string($dados['PPR_RESPOSTA'])."' ";
			}
			if(isset($dados['PPR_PUBLICADO'])){
				$sr[] = " PPR_PUBLICADO = '".$this->escape_string($dados['PPR_PUBLICADO'])."' ";
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
			case 'P': //SETA PUBLICADO OU NAO PUBLICADO
				$r = $this->setPprPublicado($dados);
			break;
			case 'CDKP': //PEGA CODDKP
				$r = $this->pesquisaCDKP($dados);
			break;
		}
		return $r;
	}
	public function pesquisaCDKP($dados){
		$sql = "SELECT PPR_CODIGO FROM tb_pergunta_produto WHERE PPR_CODDKP=".$dados['PPR_CODWEB'];
		$d = $this->query_return_one($sql);
		if(empty($d['PPR_CODIGO'])){return 0;}
		return $d['PPR_CODIGO'];
	}
	public function setPprPublicado($dados){
		$sql = "UPDATE 
					tb_pergunta_produto
				SET
					PPR_PUBLICADO='".$this->escape_string($dados['PPR_PUBLICADO'])."'
				WHERE
					PPR_CODIGO=".$dados['PPR_CODIGO'];
		if($this->affected_rows()){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "registro inexistente";
	}
}