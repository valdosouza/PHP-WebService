<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class PagamentoDigitalItens extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if(!isset($dados['id_transacao'])){
			$dados['id_transacao'] = 0;
		}
		if(!isset($dados['produto_codigo'])){
			$dados['produto_codigo'] = 0;
		}
		if(!isset($dados['produto_qtde'])){
			$dados['produto_qtde'] = 0;
		}
		if(!isset($dados['produto_valor'])){
			$dados['produto_valor'] = 0;
		}
		$sql = "REPLACE INTO
					tb_pagamento_digital_itens(
						id_transacao,
						produto_codigo,
						produto_descricao,
						produto_qtde,
						produto_valor,
						produto_extra
					)
				VALUES(
					".$dados['id_transacao'].",
					".$dados['produto_codigo'].",
					'".$this->escape_string($dados['produto_descricao'])."',
					".$dados['produto_qtde'].",
					".$dados['produto_valor'].",
					'".$this->escape_string($dados['produto_extra'])."'
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
		if(!isset($dados['id_transacao'])){
			$dados['id_transacao'] = 0;
		}
		if(!isset($dados['produto_codigo'])){
			$dados['produto_codigo'] = 0;
		}
		if(!isset($dados['produto_qtde'])){
			$dados['produto_qtde'] = 0;
		}
		if(!isset($dados['produto_valor'])){
			$dados['produto_valor'] = 0;
		}
		$sql = "UPDATE 
					tb_pagamento_digital_itens
				SET 
					produto_codigo=".$dados['produto_codigo'].",
					produto_descricao='".$this->escape_string($dados['produto_descricao'])."',
					produto_qtde=".$dados['produto_qtde'].",
					produto_valor=".$dados['produto_valor'].",
					produto_extra='".$this->escape_string($dados['produto_extra'])."'
				WHERE id_transacao = ".$dados['id_transacao'];
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
		$sql = "DELETE FROM tb_pagamento_digital_itens WHERE id_transacao=".$dados['id_transacao'];
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
		$sql = "SELECT * FROM tb_pagamento_digital_itens ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['id_transacao'])){
				$sr[] = " id_transacao = ".$dados['id_transacao']." ";
			}
			if(isset($dados['produto_codigo'])){
				$sr[] = " produto_codigo = ".$dados['produto_codigo']." ";
			}
			if(isset($dados['produto_descricao'])){
				$sr[] = " produto_descricao = '".$this->escape_string($dados['produto_descricao'])."' ";
			}
			if(isset($dados['produto_qtde'])){
				$sr[] = " produto_qtde = ".$dados['produto_qtde']." ";
			}
			if(isset($dados['produto_valor'])){
				$sr[] = " produto_valor = ".$dados['produto_valor']." ";
			}
			if(isset($dados['produto_extra'])){
				$sr[] = " produto_extra = '".$this->escape_string($dados['produto_extra'])."' ";
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