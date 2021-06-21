<?php
if(!class_exists('Comum')){
	class Comum {
		public $meses;
		public $estados;
		public function __construct(){
			$this->constroiMeses();
			$this->constroiEstados();
		}
		public function __destruct(){
	
		}
		public function constroiMeses(){
			$this->meses[1]  = 'janeiro';
			$this->meses[2]  = 'fevereiro';
			$this->meses[3]  = 'mar&ccedil;o';
			$this->meses[4]  = 'abril';
			$this->meses[5]  = 'maio';
			$this->meses[6]  = 'junho';
			$this->meses[7]  = 'julho';
			$this->meses[8]  = 'agosto';
			$this->meses[9]  = 'setembro';
			$this->meses[10] = 'outubro';
			$this->meses[11] = 'novembro';
			$this->meses[12] = 'dezembro';
		}
		public function constroiEstados(){
			$this->estados['AC'] = 'Acre';
			$this->estados['AL'] = 'Alagoas';
			$this->estados['AP'] = 'Amap&aacute;';
			$this->estados['AM'] = 'Amazonas';
			$this->estados['BA'] = 'Bahia';
			$this->estados['CE'] = 'Cear&aacute;';
			$this->estados['DF'] = 'Distrito Federal';
			$this->estados['ES'] = 'Esp&iacute;rito Santo';
			$this->estados['GO'] = 'Goi&aacute;s';
			$this->estados['MA'] = 'Maranh&atilde;o';
			$this->estados['MT'] = 'Mato Grosso';
			$this->estados['MS'] = 'Mato Grosso do Sul';
			$this->estados['MG'] = 'Minas Gerais';
			$this->estados['PA'] = 'Par&aacute;';
			$this->estados['PB'] = 'Para&iacute;ba';
			$this->estados['PR'] = 'Paran&aacute;';
			$this->estados['PE'] = 'Pernambuco';
			$this->estados['PI'] = 'Piau&iacute;';
			$this->estados['RJ'] = 'Rio de Janeiro';
			$this->estados['RN'] = 'Rio Grande do Norte';
			$this->estados['RS'] = 'Rio Grande do Sul';
			$this->estados['RO'] = 'Rond&ocirc;nia';
			$this->estados['RR'] = 'Roraima';
			$this->estados['SC'] = 'Santa Catarina';
			$this->estados['SP'] = 'S&atilde;o Paulo';
			$this->estados['SE'] = 'Sergipe';
			$this->estados['TO'] = 'Tocantins';
		}
		public function redireciona($url){
			echo "<script>window.location='".$url."';</script>";
		}
		public function alerta($texto, $tipo='default'){
			if($tipo=='default'){
				echo "<script charset='utf-8'>alert('".$texto."');</script>";
			}else{
				echo "<script charset='utf-8'>alert('".$texto."', 'Mensagem do Sistema');</script>";
			}
		}
		public function envolveStrComTag($texto, $string, $tag = array('<b>','</b>'), $case_sensitive = false){
			$str = $tag[0].$string.$tag[1];
			if($case_sensitive){
				return str_replace($string, $str, $texto);
			}else{
				return str_ireplace($string, $str, $texto);
			}
		}
		public function dataPorExtenso($data){
			$tmp = explode("/", $data);
			if(count($tmp)==3){
				$tmp2 = $tmp[2]."-".$tmp[1]."-".$tmp[0];
				$d = explode("-", $tmp2);	
			}else{
				$d = explode("-", $data);		
			}
			return $d[2]." de ".$this->meses[(int)$d[1]]." de ".$d[0];
		}
		public function converteData($data){
			$hr = "";
			$x = explode(" ", $data);
			if(count($x)==2){
				$hr = " ".$x[1];
				$data = $x[0];
			}
			if(($data=='--')||($data=='//')){
				return false;
			}
			try{
				$tmp = explode("-", $data);
				if(count($tmp)==3){
					return $tmp[2]."/".$tmp[1]."/".$tmp[0]."".$hr;
				}else{
					throw new Exception();
				}
			}catch (Exception $e){
				try{
					$tmp = explode("/", $data);
					if(count($tmp)==3){
						return $tmp[2]."-".$tmp[1]."-".$tmp[0]."".$hr;
					}else{
						throw new Exception();
					}
				}catch (Exception $e){
					return false;
				}
			}
		}
		public function converteDataToEn($data){
			$hr = "";
			$x = explode(" ", $data);
			if(count($x)==2){
				$hr = " ".$x[1];
				$data = $x[0];
			}
			$tmp = explode("/", $data);
			if(count($tmp)==3){
				return $tmp[2]."-".$tmp[1]."-".$tmp[0]."".$hr;
			}
			return $data;
		}
		public function comparaDatas($data1, $data2){
			$d1t = new DateTime($this->converteData($data1));
			$d2t = new DateTime($this->converteData($data2));
			$d1 = date("d/m/Y", strtotime($d1t->format('Y-m-d')));
			$d2 = date("d/m/Y", strtotime($d2t->format('Y-m-d')));
			if($d1==$d2){
				return 0;
			}else if($d1 > $d2){
				return -1;
			}else if($d1 < $d2){
				return 1;
			}
			return false;
		}
		public function removeAcentos($string){
		/*	$str = strtolower($string);
			$p = array('�','Ã©','Ã­','Ã³','Ãº','Ã£','Ãµ','Ã¢','Ãª','Ã®','Ã´','Ã»','Ã ','Ã¨','Ã¬','Ã²','Ã¹','Ã¤','Ã«','Ã¯','Ã¶','Ã¼','!','@','#','$','%','&','*','(',')','-','+','=','^','~','Â´','`','[',']','{','}','/','?','|','\\',';',':','<','>',',','Ã§');
			$r = array('a','e','i','o','u','a','o','a','e','i','o','u','a','e','i','o','u','a','e','i','o','u','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_', '_','_','_','_','_','c');
			return str_replace($p, $r, $str);
			*/
			$var = strtolower($string);
	
	$string = preg_replace("[����]","a",$string);	
	$string = preg_replace("[���]","e",$string);	
	$string = preg_replace("[�����]","o",$string);	
	$string = preg_replace("[���]","u",$string);	
	$string = str_replace("�","c",$string);
	
	return $string;
		}
		public function paginaChamada(){
			$nome = $_SERVER["REQUEST_URI"];
			$tmp = explode("/", $nome);
			$pgtmp = $tmp[count($tmp)-1];
			$tmp2 = explode("?", $pgtmp);
			if(count($tmp2)>0){
				return $tmp2[0];
			}
			return $pgtmp;
		}
		public function getDaPaginaChamada($string = false){
			$nome = $_SERVER["REQUEST_URI"];
			$tmp = explode("?", $nome);
			if($string){
				if(count($tmp)>0){
					return $tmp[1];
				}
			}else{
				$get = array();
				$tmp2 = explode("&", $tmp[1]);
				foreach ($tmp2 as $t){
					$tmp3 = explode("=",$t);
					$get[$tmp3[0]] = $tmp3[1];
				}
				return $get;
			}
			return "";
		}
		public function limitaTexto($string, $qtd_palavras = 5){
			$str = strip_tags($string);
			$txt = explode(" ", $str);
			$s = array();
			for($x = 0; $x<$qtd_palavras; $x++){
				$s[] = @$txt[$x];
			}
			return implode(" ", $s);
		}
		public function array_to_json( $array ){
			if( !is_array( $array ) ){
				return false;
			}
			$associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
			if( $associative ){
				$construct = array();
				foreach( $array as $key => $value ){
					if( is_numeric($key) ){
						$key = "key_$key";
					}
					$key = "\"".addslashes($key)."\"";
					if( is_array( $value )){
						$value = $this->array_to_json( $value );
					} else if( !is_numeric( $value ) || is_string( $value ) ){
						$value = "\"".addslashes($value)."\"";
					}
					$construct[] = "$key: $value";
				}
				$result = "{ " . implode( ", ", $construct ) . " }";
			} else {
				$construct = array();
				foreach( $array as $value ){
					if( is_array( $value )){
						$value = $this->array_to_json( $value );
					} else if( !is_numeric( $value ) || is_string( $value ) ){
						$value = "'".addslashes($value)."'";
					}
					$construct[] = $value;
				}
				$result = "[ " . implode( ", ", $construct ) . " ]";
			}
			return $result;
		}
		public function retorna_extensao($str){
			$tmp = explode(".", $str);
			return $tmp[count($tmp)-1];
		}
		public function formataMoeda($valor, $tipo='BRL'){
			if($tipo=='BRL'){
				return number_format((float)$valor, 2, ',', '.');
			}else{
				return str_replace(array('.',','), array('','.'), $valor);
			}
		}
		public function calculaFrete($cod_servico,$cep_origem,$cep_destino,$peso,$valor_declarado='0.50',$altura='2',$largura='11',$comprimento='16'){
			############################################
			# Código dos Serviços dos Correios
			# 41106 PAC sem contrato
			# 40010 SEDEX sem contrato
			# 40045 SEDEX a Cobrar, sem contrato
			# 40215 SEDEX 10, sem contrato
			############################################
		
			$correios = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$cep_origem."&sCepDestino=".$cep_destino."&nVlPeso=".$peso."&nCdFormato=1&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=n&nVlValorDeclarado=".$valor_declarado."&sCdAvisoRecebimento=n&nCdServico=".$cod_servico."&nVlDiametro=0&StrRetorno=xml";
			$xml = simplexml_load_file($correios);
			if($xml->cServico->Erro == '0'){
				return "R$ ".$xml->cServico->Valor;
			}
			else{
				$r = "<div id='cep-erro'>";
				switch($xml->cServico->Erro){
					default:
						//$r .= $xml->cServico->Erro;
					break;
					case -1:
						$r .= 'Código de serviço inválido';
					break;
					case -2:
						$r .= 'CEP de origem inválido';
					break;
					case -3:
						$r .= 'CEP de destino inválido';
					break;
					case -4:
						$r .= 'Peso excedido';
					break;
					case -5:
						$r .= 'O Valor Declarado não deve exceder R$ 10.000,00';
					break;
					case -6:
						$r .= 'Serviço indisponível para o trecho informado';
					break;
					case -7:
						$r .= 'O Valor Declarado é obrigatório para este serviço';
					break;
					case -8:
						$r .= 'Este serviço não aceita Mão Própria';
					break;
					case -9:
						$r .= 'Este serviço não aceita Aviso de Recebimento';
					break;
					case -10:
						$r .= 'Precificação indisponível para o trecho informado';
					break;
					case -11:
						$r .= 'Para definição do preço deverão ser informados, também, o comprimento, a largura e altura do objeto em centímetros (cm).';
					break;
				}
				$r .= "</div>";
				return $r;
			}
		}
		
	
		function convertXmlObjToArr($xml, $arr) { 
			$obj = simplexml_load_string($xml); 
			$children = $obj->children(); 
			foreach ($children as $elementName => $node) { 
				$nextIdx = count($arr); 
				$arr[$nextIdx] = array(); 
				$arr[$nextIdx]['@name'] = strtolower((string)$elementName); 
				$arr[$nextIdx]['@attributes'] = array(); 
				$attributes = $node->attributes(); 
				foreach ($attributes as $attributeName => $attributeValue) { 
					$attribName = strtolower(trim((string)$attributeName)); 
					$attribVal = trim((string)$attributeValue); 
					$arr[$nextIdx]['@attributes'][$attribName] = $attribVal; 
				} 
				$text = (string)$node; 
				$text = trim($text); 
				if (strlen($text) > 0) { 
					$arr[$nextIdx]['@text'] = $text; 
				} 
				$arr[$nextIdx]['@children'] = array(); 
				$this->convertXmlObjToArr($node, $arr[$nextIdx]['@children']); 
			} 
	    return $arr; 
		}  
		public function msnStatus($msnid, $returnAllData = false){
			$pageurl = "http://messenger.services.live.com/users/".$msnid."@apps.messenger.live.com/presence?";
			$data = @file_get_contents($pageurl);
			$arr = array();
			$status = 'Offline';
			if(!empty($data)){
				$arr = json_decode($data, true);
				if(!empty($arr)){
					$status = $arr['status'];
					if($returnAllData){
						return $arr;
					}
				}
			}
			return $status;
		}
		public function insertHeader($dados){
			$tmp  = array();
			foreach ($dados as $k=>$i){	
				if($i!=''){
					$tmp[]=$k;
				}
			}
			return ''.implode(',', $tmp);
		}
		public function insertFooter($dados){
			$tmp  = array();
			foreach ($dados as $k=>$i){
				if($i!=''){
					$tmp[]="'".utf8_decode(mysql_escape_string($i))."'";
				}
			}
			return ''.implode(',', $tmp);
		}
		public function updateHeader($dados, $pk){
			$tmp  = array();
			foreach ($dados as $k=>$i){
				if($pk!=$k){
					$tmp[]=$k."='".utf8_decode(mysql_escape_string($i))."'";
				}
			}
			return ''.implode(',', $tmp);
		}
		public function geraSql($operacao, $tabela, $dados, $pk){
			$sql = '';
			$operacao = strtolower($operacao);
			switch($operacao){
				default:
					return "opera��o invalida";
				break;
				case 'insert':
					$sql .= 'INSERT INTO '.$tabela.' ('.$this->insertHeader($dados).')VALUES('.$this->insertFooter($dados).')';
				break;
				case 'update':
					$sql .= 'UPDATE '.$tabela.' SET '.$this->updateHeader($dados, $pk);
					if(!is_null($pk)){
						if(is_array($pk)){
							$sql .= ' WHERE ';
							$where = array();
							foreach ($pk as $fpk){
								$where[] = $fpk.'='.@$dados[$fpk];
							}
							$sql .= implode(' AND ', $where);
						}else{
							$sql .= ' WHERE '.$pk.'='.@$dados[$pk];
						}
					}
				break;
			}
			//return utf8_decode($sql);
			return $sql;
		}
		public function sluggify($url){
		    # Prep string with some basic normalization
		    $url = strtolower($url);
		    $url = strip_tags($url);
		    $url = stripslashes($url);
		    $url = html_entity_decode($url);
		
		    # Remove quotes (can't, etc.)
		    $url = str_replace('\'', '', $url);
		
		    # Replace non-alpha numeric with hyphens
		    $match = '/[^a-z0-9]+/';
		    $replace = '-';
		    $url = preg_replace($match, $replace, $url);
		
		    $url = trim($url, '-');
		
		    return $url;
		}
	}
	
}