<?php
require_once 'class/xmlBuilder.class.php';
require_once 'class/nusoap/nusoap.php';
$wsdl = 'http://www.setes.com.br/ws/index.php?wsdl';
//$wsdl = 'http://localhost/ws/index.php?wsdl';
$client = new nusoap_client($wsdl, true);

$xml = '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes" ?>       
	<imagem xmlns="http://www.setes.com.br/">
		<IMG_CODIGO>'.$_GET['cod'].'</IMG_CODIGO>
	</imagem>';
$result = $client->call('servico',array(md5('webservice_setes_2011'),'imagem','SP', $xml));
$arr = xmlBuilder::transformToArray($result);
switch ($arr['dados']['IMG_EXT']){
	default:
		header("Content-Type: Image/jpeg");
	break;
	case 'jpg':
		header("Content-Type: Image/jpeg");
	break;
	case 'bmp':
		header("Content-Type: Image/bmp");
	break;
	case 'gif':
		header("Content-Type: Image/gif");
	break;
	case 'png':
		header("Content-Type: Image/png");
	break;
	
}
echo base64_decode($arr['dados']['IMG_IMAGEM']);