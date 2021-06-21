<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class Cotacao extends Mysql implements InterfaceWebService {
	
	public function __construct() {
		parent::__construct ( BD_SERVER, LOGIN, PASSWORD, BD );
	}
	
	public function insert($dados){
		if($this->hasThisPk($dados)){
			$sqlx = "DELETE FROM tb_itens_ctc WHERE ICT_CODTBL=".$dados['CTC_CODTBL']." AND ICT_CODCTC=".$dados['CTC_CODIGO'];
			$this->query($sqlx);
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_cotacao', $dados, null);
		$this->query($sql);
		if($this->affected_rows()){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "1";
	}
	
	public function update($dados){
		$sql = $this->geraSql("update", 'tb_cotacao', $dados, array('CTC_CODIGO', 'CTC_CODTBL'));
		$this->query($sql);
		if($this->affected_rows ()){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "1";
	}
	
	public function delete($dados) {
		$sql = "DELETE FROM tb_cotacao WHERE CTC_CODIGO=" . $dados ['CTC_CODIGO'];
		$this->query ( $sql );
		if ($this->affected_rows ()) {
			return "1";
		}
		$e = $this->error ();
		if (! empty ( $e )) {
			return $e;
		}
		return "registro inexistente";
	}
	public function hasThisPk($dados){
		$sql = "SELECT 1 FROM tb_cotacao WHERE CTC_CODIGO=".$dados['CTC_CODIGO']." AND CTC_CODTBL=".$dados['CTC_CODTBL']." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return 1;
		}
	}
	public function search($dados) {
	}
	
	public function customQuery($dados) {
		$r = $this->query_fetch ( $dados ['QUERY'] );
		if (empty ( $r )) {
			return "consulta vazia";
		}
		return $r;
	}
	public function executeThisOperation($op, $dados) {
		$r = array ();
		switch ($op) {
			default :
				return 'opera��o inv�lida';
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