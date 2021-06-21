<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class PagamentoDigital extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if(!isset($dados['id_transacao'])){
			$dados['id_transacao'] = 0;
		}
		if(!isset($dados['valor_original'])){
			$dados['valor_original'] = 0;
		}
		if(!isset($dados['valor_loja'])){
			$dados['valor_loja'] = 0;
		}
		if(!isset($dados['valor_total'])){
			$dados['valor_total'] = 0;
		}
		if(!isset($dados['desconto'])){
			$dados['desconto'] = 0;
		}
		if(!isset($dados['acrescimo'])){
			$dados['acrescimo'] = 0;
		}
		if(!isset($dados['cod_status'])){
			$dados['cod_status'] = 0;
		}
		if(!isset($dados['frete'])){
			$dados['frete'] = 0;
		}
		$sql = "REPLACE INTO
					tb_pagamento_digital(
						id_transacao,
						data_transacao,
						data_credito,
						valor_original,
						valor_loja,
						valor_total,
						desconto,
						acrescimo,
						tipo_pagamento,
						parcelas,
						cliente_nome,
						cliente_email,
						cliente_rg,
						cliente_data_emissao_rg,
						cliente_orgao_emissor_rg,
						cliente_estado_emissor_rg,
						cliente_cpf,
						cliente_sexo,
						cliente_data_nascimento,
						cliente_endereco,
						cliente_complemento,
						status,
						cod_status,
						cliente_bairro,
						cliente_cidade,
						cliente_estado,
						cliente_cep,
						frete,
						tipo_frete,
						informacoes_loja,
						id_pedido,
						free,
						email_vendedor
					)
				VALUES(
				".$dados['id_transacao'].",
				'".$this->escape_string($dados['data_transacao'])."',
				'".$this->escape_string($dados['data_credito'])."',
				".$dados['valor_original'].",
				".$dados['valor_loja'].",
				".$dados['valor_total'].",
				".$dados['desconto'].",
				".$dados['acrescimo'].",
				'".$this->escape_string($dados['tipo_pagamento'])."',
				'".$this->escape_string($dados['parcelas'])."',
				'".$this->escape_string($dados['cliente_nome'])."',
				'".$this->escape_string($dados['cliente_email'])."',
				'".$this->escape_string($dados['cliente_rg'])."',
				'".$this->escape_string($dados['cliente_data_emissao_rg'])."',
				'".$this->escape_string($dados['cliente_orgao_emissor_rg'])."',
				'".$this->escape_string($dados['cliente_estado_emissor_rg'])."',
				'".$this->escape_string($dados['cliente_cpf'])."',
				'".$this->escape_string($dados['cliente_sexo'])."',
				'".$this->escape_string($dados['cliente_data_nascimento'])."',
				'".$this->escape_string($dados['cliente_endereco'])."',
				'".$this->escape_string($dados['cliente_complemento'])."',
				'".$this->escape_string($dados['status'])."',
				".$dados['cod_status'].",
				'".$this->escape_string($dados['cliente_bairro'])."',
				'".$this->escape_string($dados['cliente_cidade'])."',
				'".$this->escape_string($dados['cliente_estado'])."',
				'".$this->escape_string($dados['cliente_cep'])."',
				".$dados['frete'].",
				'".$this->escape_string($dados['tipo_frete'])."',
				'".$this->escape_string($dados['informacoes_loja'])."',
				'".$this->escape_string($dados['id_pedido'])."',
				'".$this->escape_string($dados['free'])."',
				'".$this->escape_string($dados['email_vendedor'])."'
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
					tb_pagamento_digital
				SET 
					data_transacao='".$this->escape_string($dados['data_transacao'])."',
					data_credito='".$this->escape_string($dados['data_credito'])."',
					valor_original=".$dados['valor_original'].",
					valor_loja=".$dados['valor_loja'].",
					valor_total=".$dados['valor_total'].",
					desconto=".$dados['desconto'].",
					acrescimo=".$dados['acrescimo'].",
					tipo_pagamento='".$this->escape_string($dados['tipo_pagamento'])."',
					parcelas='".$this->escape_string($dados['parcelas'])."',
					cliente_nome='".$this->escape_string($dados['cliente_nome'])."',
					cliente_email='".$this->escape_string($dados['cliente_email'])."',
					cliente_rg='".$this->escape_string($dados['cliente_rg'])."',
					cliente_data_emissao_rg='".$this->escape_string($dados['cliente_data_emissao_rg'])."',
					cliente_orgao_emissor_rg='".$this->escape_string($dados['cliente_orgao_emissor_rg'])."',
					cliente_estado_emissor_rg='".$this->escape_string($dados['cliente_estado_emissor_rg'])."',
					cliente_cpf='".$this->escape_string($dados['cliente_cpf'])."',
					cliente_sexo='".$this->escape_string($dados['cliente_sexo'])."',
					cliente_data_nascimento='".$this->escape_string($dados['cliente_data_nascimento'])."',
					cliente_endereco='".$this->escape_string($dados['cliente_endereco'])."',
					cliente_complemento='".$this->escape_string($dados['cliente_complemento'])."',
					status='".$this->escape_string($dados['status'])."',
					cod_status=".$dados['cod_status'].",
					cliente_bairro='".$this->escape_string($dados['cliente_bairro'])."',
					cliente_cidade='".$this->escape_string($dados['cliente_cidade'])."',
					cliente_estado='".$this->escape_string($dados['cliente_estado'])."',
					cliente_cep='".$this->escape_string($dados['cliente_cep'])."',
					frete=".$dados['frete'].",
					tipo_frete='".$this->escape_string($dados['tipo_frete'])."',
					informacoes_loja='".$this->escape_string($dados['informacoes_loja'])."',
					id_pedido='".$this->escape_string($dados['id_pedido'])."',
					free='".$this->escape_string($dados['free'])."',
					email_vendedor='".$this->escape_string($dados['email_vendedor'])."'
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
		$sql = "DELETE FROM tb_pagamento_digital WHERE id_transacao=".$dados['id_transacao'];
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
		$sql = "SELECT * FROM tb_pagamento_digital ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['id_transacao'])){
				$sr[] = " id_transacao = ".$dados['id_transacao']." ";
			}
			if(isset($dados['data_transacao'])){
				$sr[] = " data_transacao = '".$this->escape_string($dados['data_transacao'])."' ";
			}
			if(isset($dados['data_credito'])){
				$sr[] = " data_credito = '".$this->escape_string($dados['data_credito'])."' ";
			}
			if(isset($dados['valor_original'])){
				$sr[] = " valor_original = ".$dados['valor_original']." ";
			}
			if(isset($dados['valor_loja'])){
				$sr[] = " valor_loja = ".$dados['valor_loja']." ";
			}
			if(isset($dados['valor_total'])){
				$sr[] = " valor_total = ".$dados['valor_total']." ";
			}
			if(isset($dados['desconto'])){
				$sr[] = " desconto = ".$dados['desconto']." ";
			}
			if(isset($dados['acrescimo'])){
				$sr[] = " acrescimo = ".$dados['acrescimo']." ";
			}
			if(isset($dados['tipo_pagamento'])){
				$sr[] = " tipo_pagamento = '".$this->escape_string($dados['tipo_pagamento'])."' ";
			}
			if(isset($dados['parcelas'])){
				$sr[] = " parcelas = '".$this->escape_string($dados['parcelas'])."' ";
			}
			if(isset($dados['cliente_nome'])){
				$sr[] = " cliente_nome = '".$this->escape_string($dados['cliente_nome'])."' ";
			}
			if(isset($dados['cliente_email'])){
				$sr[] = " cliente_email = '".$this->escape_string($dados['cliente_email'])."' ";
			}
			if(isset($dados['cliente_rg'])){
				$sr[] = " cliente_rg = '".$this->escape_string($dados['cliente_rg'])."' ";
			}
			if(isset($dados['cliente_data_emissao_rg'])){
				$sr[] = " cliente_data_emissao_rg = '".$this->escape_string($dados['cliente_data_emissao_rg'])."' ";
			}
			if(isset($dados['cliente_orgao_emissor_rg'])){
				$sr[] = " cliente_orgao_emissor_rg = '".$this->escape_string($dados['cliente_orgao_emissor_rg'])."' ";
			}
			if(isset($dados['cliente_estado_emissor_rg'])){
				$sr[] = " cliente_estado_emissor_rg = '".$this->escape_string($dados['cliente_estado_emissor_rg'])."' ";
			}
			if(isset($dados['cliente_cpf'])){
				$sr[] = " cliente_cpf = '".$this->escape_string($dados['cliente_cpf'])."' ";
			}
			if(isset($dados['cliente_sexo'])){
				$sr[] = " cliente_sexo = '".$this->escape_string($dados['cliente_sexo'])."' ";
			}
			if(isset($dados['cliente_data_nascimento'])){
				$sr[] = " cliente_data_nascimento = '".$this->escape_string($dados['cliente_data_nascimento'])."' ";
			}
			if(isset($dados['cliente_endereco'])){
				$sr[] = " cliente_endereco = '".$this->escape_string($dados['cliente_endereco'])."' ";
			}
			if(isset($dados['cliente_complemento'])){
				$sr[] = " cliente_complemento = '".$this->escape_string($dados['cliente_complemento'])."' ";
			}
			if(isset($dados['status'])){
				$sr[] = " status = '".$this->escape_string($dados['status'])."' ";
			}
			if(isset($dados['cod_status'])){
				$sr[] = " cod_status = ".$dados['cod_status']." ";
			}
			if(isset($dados['cliente_bairro'])){
				$sr[] = " cliente_bairro = '".$this->escape_string($dados['cliente_bairro'])."' ";
			}
			if(isset($dados['cliente_cidade'])){
				$sr[] = " cliente_cidade = '".$this->escape_string($dados['cliente_cidade'])."' ";
			}
			if(isset($dados['cliente_estado'])){
				$sr[] = " cliente_estado = '".$this->escape_string($dados['cliente_estado'])."' ";
			}
			if(isset($dados['cliente_cep'])){
				$sr[] = " cliente_cep = '".$this->escape_string($dados['cliente_cep'])."' ";
			}
			if(isset($dados['frete'])){
				$sr[] = " frete = ".$dados['frete']." ";
			}
			if(isset($dados['tipo_frete'])){
				$sr[] = " tipo_frete = '".$this->escape_string($dados['tipo_frete'])."' ";
			}
			if(isset($dados['informacoes_loja'])){
				$sr[] = " informacoes_loja = '".$this->escape_string($dados['informacoes_loja'])."' ";
			}
			if(isset($dados['id_pedido'])){
				$sr[] = " id_pedido = '".$this->escape_string($dados['id_pedido'])."' ";
			}
			if(isset($dados['free'])){
				$sr[] = " free = '".$this->escape_string($dados['free'])."' ";
			}
			if(isset($dados['email_vendedor'])){
				$sr[] = " email_vendedor = '".$this->escape_string($dados['email_vendedor'])."' ";
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