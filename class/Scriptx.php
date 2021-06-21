<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Scriptx extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		$sql = "INSERT INTO
				    tb_script_exec (SCX_CODSCP, 
				                    SCX_CODCLI, 
				                    SCX_RESULTADO)
				VALUES(
				    ".$dados['SCP_CODIGO'].", 
				    (SELECT 
				            CLI_CODIGO 
				        FROM 
				            tb_cliente cli 
				        INNER JOIN 
				            tb_entidade etd 
				            ON 
				            	etd.ETD_CNPJ_CPF='".$this->escape_string($dados['ETD_CNPJ_CPF'])."' AND 
				            	cli.CLI_CODETD=etd.ETD_CODIGO),
				        '".$this->escape_string($dados['SCX_RESULTADO'])."')";
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
		return "não pode atualizar um script executado";
	}
	
	public function delete($dados){
		return "não pode deletar um script executado";
	}
	
	public function search($dados){
		$sql = "SELECT 
					* 
				FROM 
					tb_script_exec ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['SCX_CODIGO'])){
				$sr[] = " SCX_CODIGO = ".$dados['SCX_CODIGO']." ";
			}
			if(isset($dados['SCX_CODSCP'])){
				$sr[] = " SCX_CODSCP =".$dados['SCX_CODSCP']." ";
			}
			if(isset($dados['SCX_DATATIME'])){
				$sr[] = " SCX_DATATIME = '".$dados['SCX_DATATIME']."' ";
			}
			if(isset($dados['SCX_CODCLI'])){
				$sr[] = " SCX_CODCLI =".$dados['SCX_CODCLI']." ";
			}
			if(isset($dados['SCX_RESULTADO'])){
				$sr[] = " SCX_RESULTADO = '".$dados['SCX_RESULTADO']."' ";
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
			case 'N': //NAO EXECUTADO
				$r = $this->scriptsNaoExecutados($dados);
			break;
			case 'E': //EXECUTADO
				$r = $this->scriptsExecutados($dados);
			break;
		}
		return $r;
	}
	
	public function scriptsNaoExecutados($dados){
		$sql = "SELECT 
				    scp.*
				FROM 
				    tb_script scp
				RIGHT JOIN
				    tb_script_exec scx
				        ON
				            scx.SCX_CODSCP<>scp.SCP_CODIGO AND
				            scx.SCX_CODCLI=(SELECT cli.CLI_CODIGO 
				                            FROM tb_cliente cli
				                            INNER JOIN tb_entidade etd ON 
				                            etd.ETD_CNPJ_CPF='".$this->escape_string($dados['ETD_CNPJ_CPF'])."' AND 
				                            cli.CLI_CODETD=etd.ETD_CODIGO)";
		$r = $this->query_fetch($sql);
		if(empty($r)){
			return "consulta vazia";
		}
		return $r;
	}
	public function scriptsExecutados($dados){
		$sql = "SELECT 
				    *
				FROM 
				    tb_script scp
				RIGHT JOIN
				    tb_script_exec scx
				        ON
				            scx.SCX_CODSCP=scp.SCP_CODIGO AND
				            scx.SCX_CODCLI=(SELECT cli.CLI_CODIGO 
				                            FROM tb_cliente cli
				                            INNER JOIN tb_entidade etd ON 
				                            etd.ETD_CNPJ_CPF='".$this->escape_string($dados['ETD_CNPJ_CPF'])."' AND 
				                            cli.CLI_CODETD=etd.ETD_CODIGO)";
		$r = $this->query_fetch($sql);
		if(empty($r)){
			return "consulta vazia";
		}
		return $r;
	}
}