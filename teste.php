<?php
//phpinfo();
//die();
require_once 'class/mysql.class.php';
require_once 'class/Interface.php';
require_once 'class/Cep.php';
require_once 'class/xmlBuilder.class.php';		
require_once 'class/Projeto.php';	


$xml = '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes" ?>
<projeto xmlns="http://www.setes.com.br/">
<QUERY><![CDATA[SELECT CLI_ATUALIZAR  FROM tb_cliente  WHERE (CLI_ATUALIZAR = "S")  AND (CLI_CODETD = "169") LIMIT 0 , 1 ]]></QUERY>
</projeto>';


$dados = xmlBuilder::transformToArray($xml);
$obj = new Projeto();

$r = $obj->executeThisOperation('C', $dados); //contem o switch com as opera??es
var_dump($r);
echo "<br>";
$xr = xmlBuilder::createFromArray($r, $area, 'dados'); //cria o xml 

var_dump($xr);


