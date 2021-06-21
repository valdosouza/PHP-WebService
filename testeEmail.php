<?php
require_once 'class/xmlBuilder.class.php';
require_once 'class/nusoap/nusoap.php';
$wsdl = 'http://www.setes.com.br/ws/index.php?wsdl';
$client = new nusoap_client($wsdl, true);
$xml = '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes" ?>
<MAIL>
	<DE>Valdo Souza=valdo@setes.com.br</DE>
	<PARA>Valdo IG=valdo.souza@ig.com.br;Satannish=satannish@hotmail.com</PARA>
	<CC>deuzi@setes.com.br</CC>
	<HTML>SIM</HTML>
	<ASSUNTO>TESTE CHARSET</ASSUNTO>
	<MENSAGEM><![CDATA[<h1>Mensagem</h1> <span style="color:red;">teste</span> em html <b>negrito</b><hr><em>It�lico</em><div style="display:inline-block;width:250px;min-height:20px;border:2px dashed blue;padding:10px;margin:20px;">Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. </div><br><a href="http://www.setes.com.br">clique aqui</a>t�ste de acentua��o h� �edilha n�o ling�i�a �ie.]]></MENSAGEM>
</MAIL>';
header("Content-Type: text/xml");
$result = $client->call('servico',array(md5('webservice_setes_2011'),'email','x', $xml));
echo $result;