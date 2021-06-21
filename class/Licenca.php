<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Licenca extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	/**
	 * @see InterfaceWebService::insert()
	 */
	public function insert($dados){
		$sql = "INSERT INTO tb_licenca (LCS_CODCLI, 
										LCS_VOLUME_HD, 
										LCS_TIME_INSTALA, 
										LCS_IP_INSTALA, 
										LCS_LIBERADO,
										LCS_SOLICITA,
										LCS_ACESSOS)
				VALUES ('0',
						'".$this->escape_string($dados['LCS_VOLUME_HD'])."',
						'".$this->escape_string($dados['LCS_TIME_INSTALA'])."',
						'".$this->escape_string($dados['LCS_IP_INSTALA'])."',
						'".$this->escape_string($dados['LCS_LIBERADO'])."',
						'".$this->escape_string($dados['LCS_SOLICITA'])."',
						'1')";
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
	/**
	 * @see InterfaceWebService::update()
	 */
	public function update($dados){
		if(!isset($dados['LCS_CODCLI'])){
			$dados['LCS_CODCLI'] = 0;
		}
		if(!isset($dados['LCS_CODIGO'])){
			$dados['LCS_CODIGO'] = 0;
		}
		$sql = "UPDATE 
					tb_licenca 
				SET 
					LCS_CODCLI=".$dados['LCS_CODCLI'].",
					LCS_VOLUME_HD='".$this->escape_string($dados['SCP_CODPRJ'])."',
					LCS_TIME_INSTALA='".$this->escape_string($dados['LCS_TIME_INSTALA'])."',
					LCS_IP_INSTALA='".$this->escape_string($dados['LCS_IP_INSTALA'])."',
					LCS_LIBERADO='".$this->escape_string($dados['SCP_CODTRF'])."',
					LCS_SOLICITA='".$this->escape_string($dados['LCS_SOLICITA'])."'
				WHERE 
					LCS_VOLUME_HD=".$dados['LCS_VOLUME_HD'];
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
	/**
	 * @see InterfaceWebService::delete()
	 */
	public function delete($dados){
		if(!isset($dados['LCS_CODIGO'])){
			$dados['LCS_CODIGO'] = 0;
		}
		$sql = "DELETE FROM tb_licenca 
				WHERE 
					LCS_CODIGO=".$dados['LCS_CODIGO'];
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
	/**
	 * @see InterfaceWebService::search()
	 */
	public function search($dados){
		$sql = "SELECT 
					* 
				FROM 
					tb_licenca ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['LCS_CODIGO'])){
				$sr[] = " LCS_CODIGO = ".$dados["LCS_CODIGO"]." ";
			}
			if(isset($dados['LCS_CODCLI'])){
				$sr[] = " LCS_CODCLI = ".$dados["LCS_CODCLI"]." ";
			}
			if(isset($dados['LCS_VOLUME_HD'])){
				$sr[] = " LCS_VOLUME_HD = '".$dados["LCS_VOLUME_HD"]."' ";
			}
			if(isset($dados['LCS_TIME_INSTALA'])){
				$sr[] = " LCS_TIME_INSTALA = '".$dados["LCS_TIME_INSTALA"]."' ";
			}
			if(isset($dados['LCS_IP_INSTALA'])){
				$sr[] = " LCS_IP_INSTALA = '".$dados["LCS_IP_INSTALA"]."' ";
			}
			if(isset($dados['LCS_LIBERADO'])){
				$sr[] = " LCS_LIBERADO = '".$dados["LCS_LIBERADO"]."' ";
			}
			if(isset($dados['LCS_TIME_USO'])){
				$sr[] = " LCS_TIME_USO = '".$dados["LCS_TIME_USO"]."' ";
			}
			if(isset($dados['LCS_SOLICITA'])){
				$sr[] = " LCS_SOLICITA = '".$dados["LCS_SOLICITA"]."' ";
			}
			if(!empty($sr)){
				$sql .= implode(" AND ", $sr);
			}
		}
		
		$r = $this->query_fetch($sql);
		if(empty($r)){
			return "consulta vazia";
		}
		return $r;
	}
	/**
	 * @see InterfaceWebService::customQuery()
	 */
	public function customQuery($query){
		$r = $this->query_fetch($query['QUERY']);
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
			case 'L': //LIBERADO
				$r = $this->liberado($dados['LCS_VOLUME_HD']);
			break;
			case 'A': //ATUALIZA
				$r = $this->atualizaCadastro($dados['LCS_VOLUME_HD']);
			break;
		}
		return $r;
	}
	
	/**
	 * Atualiza cada acesso feito 
	 * @param String $LCS_VOLUME_HD
	 * @return não tem retorno
	 */
	public function atualizaAcesso($LCS_VOLUME_HD){
		$sql = "UPDATE 
					tb_licenca 
				SET 
					LCS_ACESSOS= LCS_ACESSOS + 1,
					LCS_TIME_USO = NOW()
				WHERE 
					LCS_VOLUME_HD='".$LCS_VOLUME_HD."'";
		$this->query($sql);
	}	
	/**
	 * retorna se a licenca do volume do hd esta liberada
	 * @param String $LCS_VOLUME_HD
	 * @return S - sim ou N - não
	 */
	public function liberado($LCS_VOLUME_HD){
		//Registra mais um acesso
		$this->atualizaAcesso($LCS_VOLUME_HD);
		$sql = "SELECT LCS_LIBERADO, LCS_SOLICITA FROM tb_licenca WHERE LCS_VOLUME_HD='".$LCS_VOLUME_HD."'";
		return $this->query_return_one($sql);
	}
	/**
	 * retorna se foi solicitada uma atualização no cadastro
	 * @param String $LCS_VOLUME_HD
	 * @return S - sim ou N - não
	 */
	public function atualizaCadastro($LCS_VOLUME_HD){
		$sql = "SELECT LCS_SOLICITA FROM tb_licenca WHERE LCS_VOLUME_HD='".$LCS_VOLUME_HD."'";
		return $this->query_return_one($sql);
	}
}