<?php
require_once "phpmailer/class.phpmailer.php";
class Email extends PHPMailer{
	public function __construct($dados){
		$this->CharSet = "UTF-8";
		$this->IsMail();
		$this->IsHTML(false);
		$this->constroiDe($dados['DE']);
		$this->constroiPara($dados['PARA']);
		if(isset($dados['CC'])){
			$this->constroiCc($dados['CC']);
		}
		if(@$dados['HTML']=="SIM"){
			$this->IsHTML(true);
		}
		$this->Subject = @$dados['ASSUNTO'];
		$this->Body = @$dados['MENSAGEM'];
		
	}
	public function constroiDe($DE){
		$tmpDe = explode(";", $DE);
		if(is_array($tmpDe)){
			foreach ($tmpDe as $de){
				$tmp = explode("=", $de);
				if(is_array($tmp)){
					$this->SetFrom($tmp[1], $tmp[0]);
				}else{
					$this->From=$de;
				}
			}
		}else{
			$de = $tmpDe;
			$tmp = explode("=", $de);
			if(is_array($tmp)){
				$this->SetFrom($tmp[1], $tmp[0]);
			}else{
				$this->From=$de;
			}
		}
	}
	public function constroiPara($PARA){
		$tmpPara = explode(";", $PARA);
		if(is_array($tmpPara)){
			foreach ($tmpPara as $para){
				$tmp = explode("=", $para);
				if(is_array($tmp)){
					$this->AddAddress($tmp[1], $tmp[0]);
				}else{
					$this->AddAddress($para);
				}
			}
		}else{
			$para = $tmpPara;
			$tmp = explode("=", $para);
			if(is_array($tmp)){
				$this->AddAddress($tmp[1], $tmp[0]);
			}else{
				$this->AddAddress($para);
			}
		}
	}
	public function constroiCc($CC){
		$tmpCc = explode(";", $CC);
		if(is_array($tmpCc)){
			foreach ($tmpCc as $cc){
				$tmp = explode("=", $cc);
				if(is_array($tmp)){
					$this->AddCC($tmp[1], $tmp[0]);
				}else{
					$this->AddCC($cc);
				}
			}
		}else{
			$cc = $tmpCc;
			$tmp = explode("=", $cc);
			if(is_array($tmp)){
				$this->AddCC($tmp[1], $tmp[0]);
			}else{
				$this->AddCC($cc);
			}
		}
	}
	public function enviaEmail(){
		return $this->Send();
	}
}