<?php
require_once 'class/xmlBuilder.class.php';
require_once 'class/nusoap/nusoap.php';
$wsdl = 'http://www.setes.com.br/ws/index.php?wsdl';
$client = new nusoap_client($wsdl, true);
$cep = $_GET['cep'];
$result = $client->call('cep',array($cep));
header("Content-Type: text/xml");
echo $result;