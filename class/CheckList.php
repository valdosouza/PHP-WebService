<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class CheckList extends Mysql implements InterfaceWebService {
	
	public function __construct() {
		parent::__construct ( BD_SERVER, LOGIN, PASSWORD, BD );
	}
	
	public function insert($dados) {
		$sql = $this->geraSql('insert', 'tb_checklist', $dados, null);
		$this->query($sql);
		if ($this->affected_rows()) {
			return "1";
		}
		return "0";
	}
	
	public function update($dados) {
//		$sql = "UPDATE 
//					tb_checklist
//				SET 
//					TPV_RENAVAM='" . $this->escape_string ( $dados ['TPV_RENAVAM'] ) . "',
//					TPV_DESCRICAO='" . $this->escape_string ( $dados ['TPV_DESCRICAO'] ) . "'
//				WHERE TPV_CODIGO = " . $dados ['TPV_CODIGO'];
//		$this->query ( $sql );
//		if ($this->affected_rows ()) {
//			return "1";
//		}
//		$e = $this->error ();
//		if (! empty ( $e )) {
//			return $e;
//		}
//		return "dados identicos";
	}
	
	public function delete($dados) {
//		$sql = "DELETE FROM tb_checklist WHERE TPV_CODIGO=" . $dados ['TPV_CODIGO'];
//		$this->query ( $sql );
//		if ($this->affected_rows ()) {
//			return "1";
//		}
//		$e = $this->error ();
//		if (! empty ( $e )) {
//			return $e;
//		}
//		return "registro inexistente";
		return "Função não implementada";
	}
	
	public function search($dados) {
//		$sql = "SELECT * FROM tb_checklist ";
//		if (! empty ( $dados )) {
//			$sql .= " WHERE ";
//			$sr = array ();
//			if (isset ( $dados ['TPV_CODIGO'] )) {
//				$sr [] = " TPV_CODIGO = " . $dados ['TPV_CODIGO'] . " ";
//			}
//			if (isset ( $dados ['TPV_RENAVAM'] )) {
//				$sr [] = " TPV_RENAVAM = '" . $this->escape_string ( $dados ['TPV_RENAVAM'] ) . "' ";
//			}
//			if (isset ( $dados ['TPV_DESCRICAO'] )) {
//				$sr [] = " TPV_DESCRICAO = '" . $this->escape_string ( $dados ['TPV_DESCRICAO'] ) . "' ";
//			}
//			if (! empty ( $sr )) {
//				$sql .= implode ( " AND ", $sr );
//			}
//			if (isset ( $dados ['ORDER'] )) {
//				$sql .= " ORDER BY " . $dados ['ORDER'] . " ";
//			}
//			if (isset ( $dados ['LIMIT'] )) {
//				$sql .= " LIMIT " . $dados ['LIMIT'];
//			}
//		}
//		$r = $this->query_fetch ( $sql );
//		if (empty ( $r )) {
//			return "consulta vazia";
//		}
//		return $r;
		return "Função não implementada";
	}
	
	public function customQuery($dados) {
//		$r = $this->query_fetch ( $dados ['QUERY'] );
//		if (empty ( $r )) {
//			return "consulta vazia";
//		}
//		return $r;
		return "Função não implementada";
	}
	public function executeThisOperation($op, $dados) {
		$r = array ();
		switch ($op) {
			default :
				return 'operação inválida';
				break;
			case 'I' : //INSERT
				$r = $this->insert ( $dados );
				break;
			case 'U' : //UPDATE
				$r = $this->update ( $dados );
				break;
			case 'D' : //DELETE
				$r = $this->delete ( $dados );
				break;
			case 'S' : //SEARCH
				$r = $this->search ( $dados );
				break;
			case 'C' : //CUSTOM
				$r = $this->customQuery ( $dados );
				break;
			case 'TABLET_GET_ALL' :
				$r = $this->search ( $dados );
				break;
		}
		return $r;
	}
}