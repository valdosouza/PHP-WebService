<?php
require_once "InterfaceWebService.php";
require_once "mysql.class.php";

class GaleriaFotos extends Mysql implements InterfaceWebService{
	private $tmpDir;
	private $lastImage = "";
	public function __construct(){
		parent::__construct(BD_SERVER, LOGIN, PASSWORD, BD);
		$this->criaDiretorioTemporario();
	}
	
	public function insert($dados){
		/*if($this->hasThisPk(@$dados['GLF_CODIGO'])){
			return $this->update($dados);
		}
		$sql = $this->geraSql('insert', 'tb_galeria_fotos', $dados, 'GLF_CODIGO');
		$this->query($sql);
		if($this->affected_rows()){
			return "1";
		}
		$e = $this->error();
		if(!empty($e)){
			return $e;
		}
		return "erro";*/
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
			if(isset($dados['GLF_CODIGO'])){
				$dados2['GLF_CODIGO']=$dados['GLF_CODIGO'];
			}
			if(isset($dados['GLF_CODVCL'])){
				$dados2['GLF_CODVCL']=$dados['GLF_CODVCL'];
			}
			if(isset($dados['GLF_TIPO'])){
				$dados2['GLF_TIPO']=$dados['GLF_TIPO'];
			}
			$dados2['GLF_IMAGEM'] = base64_encode($img);
			$dados2['GLF_EXT'] = $ext;
			$lastID = false;
			if($this->hasThisPk(@$dados2['GLF_CODIGO'])){
				$this->update($dados2);
				$lastID = $dados2['GLF_CODIGO'];
			}else{
				$sql = $this->geraSql('insert', 'tb_galeria_fotos', $dados2, 'GLF_CODIGO');
				$this->query($sql);
				$lastID = $this->last_inserted_id();
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
		$sql = $this->geraSql('update', 'tb_galeria_fotos', $dados, 'GLF_CODIGO');
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
		$sql = "DELETE FROM tb_galeria_fotos WHERE GLF_CODIGO=".$dados['GLF_CODIGO'];
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
		$sql = "SELECT 1 FROM tb_galeria_fotos WHERE GLF_CODIGO=".$pk." LIMIT 1";
		$d = $this->query_return_one($sql);
		if(!isset($d['1'])){
			return 0;
		}else{
			return $pk;
		}
	}
	
	public function search($dados){
		$sql = "SELECT * FROM tb_galeria_fotos ";
		if(!empty($dados)){
			$sql .= " WHERE ";
			$sr = array();
			if(isset($dados['GLF_CODIGO'])){
				$sr[] = " GLF_CODIGO = ".$dados['GLF_CODIGO']." ";
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
	/*
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