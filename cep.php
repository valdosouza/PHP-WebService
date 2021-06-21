<?php
$servidor->register('cep',
					array('cep'=>'xsd:string'),
					array('return' => 'xsd:string'),
					'urn:SETES.webservice',
					'urn:SETES.webservice#cep',
					'rpc',
					'encoded',
					'WEBSERVICE'
					);
/**
 * Descobri um servico do pagamentodigital na hora que fui me cadastrar, entao decidi fazer um webservice pois achei util
 * @param String $cep
 * @return Xml;
 */
function cep($cep){
	/*$dados = @file_get_contents("http://www.pagamentodigital.com.br/lib/busca_cep.php?cep=".$cep);
	$tmp = explode("|", $dados);
	$arr = array('RUA'=>'', 'BAIRRO'=>'', 'CIDADE'=>'', 'ESTADO'=>'');
	if(!empty($tmp)){
		$arr['RUA'] = $tmp[0];
		$arr['BAIRRO'] = $tmp[1];
		$arr['CIDADE'] = $tmp[2];
		$arr['ESTADO'] = $tmp[3];
	}
	$xr = xmlBuilder::createFromArray($arr, 'ENDERECO', 'dados');
	return $xr;*/
	$cep = str_replace('-', '', $cep);
	$mysql = new Mysql();
	$sql = "SELECT 
				CEP_TIPO AS TIPO,
				CEP_LOGRAD AS LOGRADOURO,
				CEP_CODUFE AS CODIGO_ESTADO,
				UFE_SIGLA AS SIGLA_ESTADO,
				UFE_DESCRICAO AS DESCRICAO_ESTADO,
				UFE_ALIQ_INTERNA,
				CEP_CODCDD AS CODIGO_CIDADE,
				CDD_DESCRICAO AS DESCRICAO_CIDADE,
				CEP_CODBRR AS CODIGO_BAIRRO,
				BRR_DESCRICAO AS DESCRICAO_BAIRRO
			FROM 
				tb_cep 
			INNER JOIN tb_uf ON UFE_CODIGO=CEP_CODUFE
			INNER JOIN tb_cidade ON CDD_CODIGO=CEP_CODCDD
			INNER JOIN tb_bairro ON BRR_CODIGO=CEP_CODBRR
			WHERE 
				CEP_NUMERO='".$mysql->escape_string($cep)."'
			LIMIT 1";
	$retorno = $mysql->query_fetch($sql, 'assoc');
	if(!$retorno){
		$sql = "SELECT 
					'CIDADE' AS TIPO,
					'CEP PARA A CIDADE TODA' AS LOGRADOURO,
					UFE_CODIGO AS CODIGO_ESTADO,
					UFE_SIGLA AS SIGLA_ESTADO,
					UFE_DESCRICAO AS DESCRICAO_ESTADO,
					UFE_ALIQ_INTERNA,
					CDD_CODIGO AS CODIGO_CIDADE,
					CDD_DESCRICAO AS DESCRICAO_CIDADE,
					'0' AS CODIGO_BAIRRO,
					'' AS DESCRICAO_BAIRRO
				FROM 
					tb_cidade 
				INNER JOIN tb_uf ON UFE_SIGLA LIKE CDD_UF
				WHERE 
					CDD_CEP LIKE '".$mysql->escape_string($cep)."' OR
					CDD_DESCRICAO LIKE '%".$mysql->escape_string($cep)."%'
				LIMIT 1";
		$retorno = $mysql->query_fetch($sql, 'assoc');
	}
	$xr = xmlBuilder::createFromArray($retorno, 'CEP', 'dados');
	return $xr;
}