<?php
require_once "mysql.class.php";

@set_time_limit(0);
class Imagens extends Mysql{
	private $tmpDir;
	private $lastImage = "";
	
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
		$this->criaDiretorioTemporario();
	}

	/**
	 * @return o caminho da pasta $tmpDir
	 */
	public function getTmpDir() {
		return $this->tmpDir;
	}

	/**
	 * @param caminho da pasta $tmpDir
	 */
	public function setTmpDir($tmpDir) {
		$this->tmpDir = $tmpDir;
	}
	/**
	 * @return o caminho da ultima imagem $lastImage
	 */
	public function getLastImage() {
		return $this->lastImage;
	}

	/**
	 * @param o caminho da ultima imagem $lastImage
	 */
	public function setLastImage($lastImage) {
		$this->lastImage = $lastImage;
	}
	
	/**
	 * Cria diret�rio temporario para manipula��o de imagem
	 * @param String $dir  -> caminho da pasta
	 * @throws Exception
	 */
	public function criaDiretorioTemporario($dir = "./"){
		$this->setTmpDir($dir."tmp/");
		$criou = false;
		try {
			if(is_dir($this->getTmpDir())){
				$criou = @chmod($this->getTmpDir(), 0777);
				if(!$criou){
					throw new Exception("Imposs�vel mudar permiss�es do diret�rio [".$this->getTmpDir()."]", 001);
				}
			}else{
				$criou = @mkdir($this->getTmpDir(), 0777, true);
				if(!$criou){
					throw new Exception("Imposs�vel criar diretorio [".$this->getTmpDir()."]", 002);
				}
			}
		}catch (Exception $e){
			return $e;
		}
		return $criou;
	}
	/**
	 * Cria uma imagem temporaria vinda dos dados de um vetor
	 * @param Array $dados
	 */
	public function createImageFromArray($dados){
		$img = base64_decode($dados['IMG']);
		$nome = time().".".strtolower($this->retorna_extensao($dados['NME']));
		$ret = @file_put_contents($this->getTmpDir().$nome, $img);
		if($ret){
			$this->setLastImage($this->getTmpDir().$nome);
		}
	}
	/**
	 * Faz redimensionamento da imagem
	 * @param String $file
	 * @param Integer $width
	 * @param Integer $height
	 * @throws Exception
	 */
	public function redimensiona($file, $width = 640, $height= 480){
		$type = @mime_content_type($file);
		$filename = $file;
		try {
			if(is_file($file)){
				list($width_orig, $height_orig) = @getimagesize($filename);
			}else{
				throw new Exception("Arquivo desconhecido ou invalido [".$file."]", 003);
			}
		}catch(Exception $e){
			return $e;
		}
		
		$ratio_orig = $width_orig/$height_orig;
		if ($width/$height > $ratio_orig) {
		   $width = $height*$ratio_orig;
		} else {
		   $height = $width/$ratio_orig;
		}
		$image_p = imagecreatetruecolor($width, $height);
		switch($type){
			default:
				$image = imagecreatefromjpeg($filename);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
				$r = imagejpeg($image_p, $file, 100);
			break;
			case 'image/gif';
				$image = imagecreatefromgif($filename);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
				$r = imagegif($image_p, $file);
			break;
			case 'image/jpeg';
				$image = imagecreatefromjpeg($filename);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
				$r = imagejpeg($image_p, $file, 100);
			break;
			case 'image/png';
				$image =imagecreatefrompng($filename);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
				$r = imagepng($image_p, $file);
			break;
			case 'image/bmp';
				$image = imagecreatefromwbmp($filename);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
				$r = imagewbmp($image_p, $file);
			break;
		}
		if($r){
			return true;
		}
		return false;
	}
	/**
	 * Pega a stream da ultima imagem
	 */
	public function getStreamFromLastImage(){
		return @file_get_contents($this->getLastImage());
	}
	/**
	 * Insere uma imagem de produto no banco
	 * @param Array $dados
	 * @throws Exception
	 * @return retorna o ID da imagem ou erro.
	 */
	public function insertInProduto($dados){
		$redim = "S";
		$capa = "N";
		if(!empty($dados['REDIMENSIONA'])){
			$redim = strtoupper($dados['REDIMENSIONA']);
		}
		if(!empty($dados['TIPO'])){
			$capa = strtoupper($dados['TIPO']);
		}
		if(empty($dados['WIDTH'])){
			$dados['WIDTH'] = 640;
		}
		if(empty($dados['HEIGHT'])){
			$dados['HEIGHT'] = 480;
		}
		if(empty($dados['IMG'])){
			@unlink($this->getLastImage());
			return "ERRO - Imagem vazia!";
		}
		if(empty($dados['NME'])){
			@unlink($this->getLastImage());
			return "ERRO - Imagem sem nome!";
		}
		if(!isset($dados['IMG_CODETB'])){
			$dados['IMG_CODETB'] = 0;
		}
		$ext = strtolower($this->retorna_extensao($dados['NME']));
		$this->createImageFromArray($dados);
		if($redim=="S"){
			try{
				$r = $this->redimensiona($this->getLastImage(), $dados['WIDTH'], $dados['HEIGHT']);
				if(!$r){
					throw new Exception("Erro ao redimensionar a imagem [".$this->getLastImage()."] W[".$dados['WIDTH']."]H[".$dados['HEIGHT']."]", 004);
				}
			}catch (Exception $e){
				@unlink($this->getLastImage());
				return $e;
			}
		}
		
		if(!empty($this->lastImage)){
			$img = $this->getStreamFromLastImage();
			//aqui monto um dados2 porque nesse momento o dados  nao tem todos os campos
			if(isset($dados['IMG_CODIGO'])){
				$dados2['IMG_CODIGO']=$dados['IMG_CODIGO'];
			}
			if(isset($dados['IMG_CODETB'])){
				$dados2['IMG_CODETB']=$dados['IMG_CODETB'];
			}
			if(isset($dados['IMG_CODPRO'])){
				$dados2['IMG_CODPRO']=$dados['IMG_CODPRO'];
			}
			if(isset($dados['IMG_TIPO'])){
				$dados2['IMG_TIPO']=$dados['IMG_TIPO'];
			}
			$dados2['IMG_IMAGEM'] = base64_encode($img);
			$dados2['IMG_EXT'] = $ext;
			$lastID = false;
			if($this->hasThisPk(@$dados2['IMG_CODIGO'])){
				$this->update($dados2);
				$lastID = $dados2['IMG_CODIGO'];
			}else{
				$sql = $this->geraSql('insert', 'tb_img_produto', $dados2, 'IMG_CODIGO');
				$this->query($sql);
				$lastID = $this->last_inserted_id();
			}
			if($lastID){
				if($capa == "C"){
					$dcapa['PRO_CODIGO'] = $dados['IMG_CODPRO'];
					$dcapa['IMG_CODIGO'] = $lastID;
					try{
						$rcapa = $this->setaCapa($dcapa);
						if($rcapa!="1"){
							throw new Exception("A imagem [".$dcapa['IMG_CODIGO']."] n�o pode ser setada como capa", 005);
						}
					}catch (Exception $e){
						@unlink($this->getLastImage());
						return $e;
					}
				}
				@unlink($this->getLastImage());
				return $lastID;
			}
			$e = $this->error();
			if(!empty($e)){
				@unlink($this->getLastImage());
				return $e;
				//return $sql;
			}
		}
		@unlink($this->getLastImage());
		return "Erro inesperado";
	}
	public function update($dados){
		$sql = $this->geraSql('update', 'tb_img_produto', $dados, 'IMG_CODIGO');
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
	 * Seta uma imagem como capa
	 * @param Array $dados
	 */
	public function setaCapa($dados){
		$sql = "UPDATE 
					tb_produto
				SET PRO_CODIMG_CAPA=".$dados['IMG_CODIGO']." 
				WHERE PRO_CODIGO=".$dados['PRO_CODIGO'];
		$this->query($sql);
		return $sql;
		$r = $this->affected_rows();
		if($r){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			//return $e;			
		}
		return "erro ao setar a imagem como capa";
	}
	/**
	 * Verifica se a imagem � uma capa
	 * @param Integer $IMG_CODIGO
	 * @param Integer $PRO_CODIGO
	 * @return Boolean true ou false
	 */
	private function isCapa($IMG_CODIGO, $PRO_CODIGO){
		$sql = "SELECT PRO_CODIGO, IMG_CODIGO FROM tb_produto 
				WHERE PRO_CODIGO=".$PRO_CODIGO." AND PRO_CODIMG_CAPA=".$IMG_CODIGO;
		$id = $this->query($sql);
		$nr = $this->num_rows($id);
		if($nr){
			return true;
		}
		return false;
	}
	/**
	 * Insere uma imagem ao banner
	 * @param Array $dados
	 * @throws Exception
	 * @return o codigo da imagem > 0 ou erro;
	 */
	public function insertInBanner($dados){
		$redim = "S";
		if(!empty($dados['REDIMENSIONA'])){
			$redim = strtoupper($dados['REDIMENSIONA']);
		}
		if(empty($dados['WIDTH'])){
			$dados['WIDTH'] = 640;
		}
		if(empty($dados['HEIGHT'])){
			$dados['HEIGHT'] = 480;
		}
		if(empty($dados['IMG'])){
			@unlink($this->getLastImage());
			return "ERRO - Imagem vazia!";
		}
		if(empty($dados['NME'])){
			@unlink($this->getLastImage());
			return "ERRO - Imagem sem nome!";
		}
		$ext = strtolower($this->retorna_extensao($dados['NME']));
		$this->createImageFromArray($dados);
		if($redim=="S"){
			try{
				$r = $this->redimensiona($this->getLastImage(), $dados['WIDTH'], $dados['HEIGHT']);
				if(!$r){
					throw new Exception("Erro ao redimensionar a imagem [".$this->getLastImage()."] W[".$dados['WIDTH']."]H[".$dados['HEIGHT']."]", 004);
				}
			}catch (Exception $e){
				@unlink($this->getLastImage());
				return $e;
			}
		}
		if(!empty($this->lastImage)){
			$img = $this->getStreamFromLastImage();
			$sql = "INSERT INTO tb_banner_imagens (BNI_CODIGO, BNI_CODBNR, BNI_IMAGEM, BNI_MIME)
					VALUES(NULL,
							".$dados['BNI_CODBNR'].",
							'".$this->escape_string(base64_encode($img))."',
							'".$this->escape_string($ext)."')";
			$this->query($sql);
			$lastID = $this->last_inserted_id();
			if($lastID){
				@unlink($this->getLastImage());
				return $lastID;
			}
			$e = $this->error();
			if(!empty($e)){
				@unlink($this->getLastImage());
				return $e;
			}
		}
		@unlink($this->getLastImage());
		return "Erro inesperado";
	}
	/**
	 * Deleta a imagem do banco e se for capa do produto seta a capa como 0
	 * @param Array $dados
	 * @return  1 ou msg de erro
	 */
	public function deleteInProduto($dados){
		$sql = "DELETE FROM tb_img_produto WHERE IMG_CODIGO=".$dados['IMG_CODIGO'];
		$this->query($sql);
		$af = $this->affected_rows();
		if($af){
			if($this->isCapa($dados['IMG_CODIGO'], $dados['PRO_CODIGO'])){
				$dx['IMG_CODIGO'] = 0;
				$dx['PRO_CODIGO'] = $dados['PRO_CODIGO'];
				$this->setaCapa($dx);
			}
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;			
		}
		return "imagem inexistente";
	}
	public function deleteInBanner($dados){
		$sql = "DELETE FROM tb_banner_imagens WHERE BNI_CODIGO=".$dados['BNI_CODIGO'];
		$this->query($sql);
		$af = $this->affected_rows();
		if($af){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;			
		}
		return "imagem inexistente";
	}
	public function searchInProduto($dados){
		$sql = "SELECT * FROM tb_img_produto ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['IMG_CODIGO'])){
				$sr[] = " IMG_CODIGO = ".$dados['IMG_CODIGO']." ";
			}
			if(isset($dados['IMG_CODETB'])){
				$sr[] = " IMG_CODETB = ".$dados['IMG_CODETB']." ";
			}
			if(isset($dados['IMG_CODPRO'])){
				$sr[] = " IMG_CODPRO = ".$dados['IMG_CODPRO']." ";
			}
			if(isset($dados['IMG_TIPO'])){
				$sr[] = " IMG_TIPO = ".$dados['IMG_TIPO']." ";
			}			
			if(isset($dados['IMG_EXT'])){
				$sr[] = " IMG_EXT = ".$dados['IMG_EXT']." ";
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
	public function searchInBanner($dados){
		$sql = "SELECT * FROM tb_banner_imagens ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['BNI_CODIGO'])){
				$sr[] = " BNI_CODIGO = ".$dados['BNI_CODIGO']." ";
			}
			if(isset($dados['BNI_CODBNR'])){
				$sr[] = " BNI_CODBNR = ".$dados['BNI_CODBNR']." ";
			}
			if(isset($dados['BNI_MIME'])){
				$sr[] = " BNI_MIME = ".$dados['BNI_MIME']." ";
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
	public function hasThisPk($pk){
		$sql = "SELECT 1 FROM tb_img_produto WHERE IMG_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
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
			case 'IP': //INSERE NO PRODUTO
				$r = $this->insertInProduto($dados);
			break;
			case 'IB': //INSERE NO BANNER
				$r = $this->insertInBanner($dados);
			break;
			case 'DP': //DELETE IN PRODUTO
				$r = $this->deleteInProduto($dados);
			break;
			case 'DB': //DELETE IN BANNER
				$r = $this->deleteInBanner($dados);
			break;
			case 'SP': //SEARCH IN PRODUTO
				$r = $this->searchInProduto($dados);
			break;
			case 'SB': //SEARCH IN BANNER
				$r = $this->searchInBanner($dados);
			break;
			case 'SC': //SETA CAPA
				$r = $this->setaCapa($dados);
			break;
			case 'C': //CUSTOM
				$r = $this->customQuery($dados);
			break;
		}
		return $r;
	}
}