<?php
//@set_time_limit(0);
require_once 'class/nusoap/nusoap.php';
require_once 'class/xmlBuilder.class.php';
//$wsdl = 'http://localhost/ws/index.php?wsdl';
$wsdl = 'http://www.setes.com.br/ws/index.php?wsdl';
$client = new nusoap_client($wsdl, true);

if(isset($_FILES['file'])){
	$ar = base64_encode(@file_get_contents($_FILES['file']['tmp_name']));
	//header("Content-Type: Image/jpeg");
	//echo $ar;die;
	$xml = '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes" ?>       
		<imagem xmlns="http://www.setes.com.br/">
			<IMG_CODPRO>2</IMG_CODPRO>
			<IMG_CODETB>2</IMG_CODETB>
			<CAPA>S</CAPA>
			<WIDTH>800</WIDTH>
			<HEIGHT>600</HEIGHT>
			<REDIMENSIONA>S</REDIMENSIONA>
			<IMG>'.$ar.'</IMG>
			<NME>'.$_FILES['file']['name'].'</NME>
		</imagem>';
	

	$result = $client->call('servico',array(md5('webservice_setes_2011'),'imagem','IP', $xml));
	echo $result;
}

?>
<form action="enviaimg.php" method="post" enctype="multipart/form-data">
<input type="file" name="file">
<input type="submit" value="OK">
</form>