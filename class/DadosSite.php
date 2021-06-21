<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class DadosSite extends Mysql implements InterfaceWebService{
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
	}
	
	public function insert($dados){
		if(!isset($dados['DSI_CODETB'])){
			$dados['DSI_CODETB'] = 0;
		}
		$sql = "REPLACE INTO
					tb_dados_site(
					DSI_CODETB,
					DSI_CEP,
					DSI_EMAIL_PGTODIGITAL,
					DSI_TOKEN_PGTODIGITAL,
					DSI_NOMECONTATO,
					DSI_EMAILCONTATO,
					DSI_MSNID,
					DSI_TITULO_SITE,
					DSI_RODAPE_SITE,
					DSI_REVENDA
					)
				VALUES(".$dados['DSI_CODETB'].",
						'".$this->escape_string(str_replace('-', '', $dados['DSI_CEP']))."',
						'".$this->escape_string($dados['DSI_EMAIL_PGTODIGITAL'])."',
						'".$this->escape_string($dados['DSI_TOKEN_PGTODIGITAL'])."',
						'".$this->escape_string($dados['DSI_NOMECONTATO'])."',
						'".$this->escape_string($dados['DSI_EMAILCONTATO'])."',
						'".$this->escape_string($dados['DSI_MSNID'])."',
						'".$this->escape_string($dados['DSI_TITULO_SITE'])."',
						'".$this->escape_string(base64_decode($dados['DSI_RODAPE_SITE']))."',
						'".$this->escape_string($dados['DSI_REVENDA'])."')";
		$this->query($sql);
		if($this->affected_rows()){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e." - ".$sql;
		}
		return "erro";
	}
	
	public function update($dados){
		$sql = "UPDATE 
					tb_dados_site
				SET 
					DSI_CEP='".$this->escape_string($dados['DSI_CEP'])."',
					DSI_EMAIL_PGTODIGITAL='".$this->escape_string($dados['DSI_EMAIL_PGTODIGITAL'])."',
					DSI_TOKEN_PGTODIGITAL='".$this->escape_string($dados['DSI_TOKEN_PGTODIGITAL'])."',
					DSI_NOMECONTATO='".$this->escape_string($dados['DSI_NOMECONTATO'])."',
					DSI_EMAILCONTATO='".$this->escape_string($dados['DSI_EMAILCONTATO'])."',
					DSI_MSNID='".$this->escape_string($dados['DSI_MSNID'])."',
					DSI_TITULO_SITE='".$this->escape_string($dados['DSI_TITULO_SITE'])."',
					DSI_RODAPE_SITE='".$this->escape_string(base64_decode($dados['DSI_RODAPE_SITE']))."',
					DSI_REVENDA='".$this->escape_string($dados['DSI_REVENDA'])."'
				WHERE DSI_CODETB = ".$dados['DSI_CODETB'];
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
		$sql = "DELETE FROM tb_dados_site WHERE DSI_CODETB=".$dados['DSI_CODETB'];
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
		$sql = "SELECT * FROM tb_dados_site ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['DSI_CODETB'])){
				$sr[] = " DSI_CODETB = ".$dados['DSI_CODETB']." ";
			}
			if(isset($dados['DSI_CEP'])){
				$sr[] = " DSI_CEP = ".$dados['DSI_CEP']." ";
			}
			if(isset($dados['DSI_EMAIL_PGTODIGITAL'])){
				$sr[] = " DSI_EMAIL_PGTODIGITAL = ".$dados['DSI_EMAIL_PGTODIGITAL']." ";
			}
			if(isset($dados['DSI_TOKEN_PGTODIGITAL'])){
				$sr[] = " DSI_TOKEN_PGTODIGITAL = ".$dados['DSI_TOKEN_PGTODIGITAL']." ";
			}
			if(isset($dados['DSI_NOMECONTATO'])){
				$sr[] = " DSI_NOMECONTATO = ".$dados['DSI_NOMECONTATO']." ";
			}
			if(isset($dados['DSI_EMAILCONTATO'])){
				$sr[] = " DSI_EMAILCONTATO = ".$dados['DSI_EMAILCONTATO']." ";
			}
			if(isset($dados['DSI_MSNID'])){
				$sr[] = " DSI_MSNID = ".$dados['DSI_MSNID']." ";
			}
			if(isset($dados['DSI_TITULO_SITE'])){
				$sr[] = " DSI_TITULO_SITE = ".$dados['DSI_TITULO_SITE']." ";
			}
			if(isset($dados['DSI_RODAPE_SITE'])){
				$sr[] = " DSI_RODAPE_SITE = ".$dados['DSI_RODAPE_SITE']." ";
			}
			if(isset($dados['DSI_REVENDA'])){
				$sr[] = " DSI_REVENDA = ".$dados['DSI_REVENDA']." ";
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
		$arr = array();
		foreach ($r as $rr){
			$x = array(
				'DSI_CODETB'=>@$rr['DSI_CODETB'],
				'DSI_CEP'=>@$rr['DSI_CEP'],
				'DSI_EMAIL_PGTODIGITAL'=>@$rr['DSI_EMAIL_PGTODIGITAL'],
				'DSI_TOKEN_PGTODIGITAL'=>@$rr['DSI_TOKEN_PGTODIGITAL'],
				'DSI_NOMECONTATO'=>@$rr['DSI_NOMECONTATO'],
				'DSI_EMAILCONTATO'=>@$rr['DSI_EMAILCONTATO'],
				'DSI_MSNID'=>@$rr['DSI_MSNID'],
				'DSI_TITULO_SITE'=>@$rr['DSI_TITULO_SITE'],
				'DSI_RODAPE_SITE'=>base64_encode(@$rr['DSI_RODAPE_SITE']),
				'DSI_REVENDA'=>@$rr['DSI_REVENDA']
			);
			$arr[] = $x;
		}
		return $arr;
	}
	
	public function customQuery($dados){
		$r = $this->query_fetch($dados['QUERY']);
		if(empty($r)){
			return "consulta vazia";
		}
		foreach ($r as $rr){
			$x = array(
				'DSI_CODETB'=>@$rr['DSI_CODETB'],
				'DSI_CEP'=>@$rr['DSI_CEP'],
				'DSI_EMAIL_PGTODIGITAL'=>@$rr['DSI_EMAIL_PGTODIGITAL'],
				'DSI_TOKEN_PGTODIGITAL'=>@$rr['DSI_TOKEN_PGTODIGITAL'],
				'DSI_NOMECONTATO'=>@$rr['DSI_NOMECONTATO'],
				'DSI_EMAILCONTATO'=>@$rr['DSI_EMAILCONTATO'],
				'DSI_MSNID'=>@$rr['DSI_MSNID'],
				'DSI_TITULO_SITE'=>@$rr['DSI_TITULO_SITE'],
				'DSI_RODAPE_SITE'=>base64_encode(@$rr['DSI_RODAPE_SITE']),
				'DSI_REVENDA'=>@$rr['DSI_REVENDA']
			);
			$arr[] = $x;
		}
		return $arr;
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
		}
		return $r;
	}
}