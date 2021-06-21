<?php
$servidor->register('frete',
					array('cepOrigem'=>'xsd:string','cepDestino'=>'xsd:string','peso'=>'xsd:string', 'valorDeclarado'=>'xsd:string','codServico'=>'xsd:string'),
					array('return' => 'xsd:string'),
					'urn:SETES.webservice',
					'urn:SETES.webservice#cep',
					'rpc',
					'encoded',
					'WEBSERVICE'
					);
function frete($cepOrigem, $cepDestino, $peso, $valorDeclarado, $codServico){
	$cepDestino = str_replace("-", "", $cepDestino);
	$cepOrigem = str_replace("-", "", $cepOrigem);
	if(empty($peso)){
		$peso = 1;
	}
	return @file_get_contents("http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$cepOrigem."&sCepDestino=".$cepDestino."&nVlPeso=".$peso."&nCdFormato=1&nVlComprimento=20&nVlAltura=5&nVlLargura=15&sCdMaoPropria=s&nVlValorDeclarado=".$valorDeclarado."&sCdAvisoRecebimento=n&nCdServico=".$codServico."&nVlDiametro=0&StrRetorno=xml");
}