<?php
@header('Content-Type: text/html; charset=utf-8');
define("WBSPASSTKN", "");
function validaToken($token){
	if(md5(base64_decode(WBSPASSTKN))!=$token){
		return 'Permiss�o negada';
	}
}