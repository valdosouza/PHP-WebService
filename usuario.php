<?php
echo md5('webservice_setes_2011');
require_once 'class/Usuario.php';
$usu = new Usuario();
$dados = array();
if(!empty($_POST)){
	
	if(!empty($_POST['USU_CODIGO'])){
		$r = $usu->update($_POST);
	}else{
		$r = $usu->insert($_POST);
	}
	if($r!='1'){
		echo $r;
	}else{
		echo "OK - DADOS INSERIDOS";
	}
}
$d = array();
if(isset($_GET['e'])){
	$p['USU_CODIGO'] = $_GET['e'];
	$dx = $usu->search($p);
	$d = $dx[0];
}
if(isset($_GET['d'])){
	$p['USU_CODIGO'] = $_GET['d'];
	$dx = $usu->delete($p);
}
$dados = $usu->search(null); 
?>
<form action="usuario.php" method="post"> 
<label for="USU_CODIGO">USU_CODIGO</label><br>
<input type="text" name="USU_CODIGO" id="USU_CODIGO" value="<?php echo @$d['USU_CODIGO']; ?>"><br>
<label for="USU_NOME">USU_NOME</label><br>
<input type="text" name="USU_NOME" id="USU_NOME" value="<?php echo @$d['USU_NOME']; ?>"><br>
<label for="USU_LOGIN">USU_LOGIN</label><br>
<input type="text" name="USU_LOGIN" id="USU_LOGIN" value="<?php echo @$d['USU_LOGIN']; ?>"><br>
<label for="USU_SENHA">USU_SENHA</label><br>
<input type="text" name="USU_SENHA" id="USU_SENHA" value="<?php echo @$d['USU_SENHA']; ?>"><br>
<label for="USU_NIVEL">USU_NIVEL</label><br>
<input type="text" name="USU_NIVEL" id="USU_NIVEL" value="<?php echo @$d['USU_NIVEL']; ?>"><br>
<label for="USU_SRV_SMTP">USU_SRV_SMTP</label><br>
<input type="text" name="USU_SRV_SMTP" id="USU_SRV_SMTP" value="<?php echo @$d['USU_SRV_SMTP']; ?>"><br>
<label for="USU_LGN_EMAIL">USU_LGN_EMAIL</label><br>
<input type="text" name="USU_LGN_EMAIL" id="USU_LGN_EMAIL" value="<?php echo @$d['USU_LGN_EMAIL']; ?>"><br>
<label for="USU_PWD_EMAIL">USU_PWD_EMAIL</label><br>
<input type="text" name="USU_PWD_EMAIL" id="USU_PWD_EMAIL" value="<?php echo @$d['USU_PWD_EMAIL']; ?>"><br>
<label for="USU_USU_EMAIL">USU_USU_EMAIL</label><br>
<input type="text" name="USU_USU_EMAIL" id="USU_USU_EMAIL" value="<?php echo @$d['USU_USU_EMAIL']; ?>"><br>
<label for="USU_LBL_EMAIL">USU_LBL_EMAIL</label><br>
<input type="text" name="USU_LBL_EMAIL" id="USU_LBL_EMAIL" value="<?php echo @$d['USU_LBL_EMAIL']; ?>"><br>
<label for="USU_PORTA_EMAIL">USU_PORTA_EMAIL</label><br>
<input type="text" name="USU_PORTA_EMAIL" id="USU_PORTA_EMAIL" value="<?php echo @$d['USU_PORTA_EMAIL']; ?>"><br>
<label for="USU_ATIVO">USU_ATIVO</label><br>
<input type="text" name="USU_ATIVO" id="USU_ATIVO" value="<?php echo @$d['USU_ATIVO']; ?>"><br>
<label for="USU_REQ_AUT_EMAIL">USU_REQ_AUT_EMAIL</label><br>
<input type="text" name="USU_REQ_AUT_EMAIL" id="USU_REQ_AUT_EMAIL" value="<?php echo @$d['USU_REQ_AUT_EMAIL']; ?>"><br>
<input type="submit" value="GRAVAR">
</form>

<?php 
if($dados!='consulta vazia'): ?>
<table>
<tr>
	<th>USU_CODIGO</th>
	<th>USU_NOME</th>
	<th>USU_LOGIN</th>
	<th>USU_SENHA</th>
	<th>USU_NIVEL</th>
	<th>USU_SRV_SMTP</th>
	<th>USU_LGN_EMAIL</th>
	<th>USU_PWD_EMAIL</th>
	<th>USU_USU_EMAIL</th>
	<th>USU_LBL_EMAIL</th>
	<th>USU_PORTA_EMAIL</th>
	<th>USU_ATIVO</th>
	<th>USU_REQ_AUT_EMAIL</th>
	<th>ACOES</th>
</tr>
<?php foreach ($dados as $d): ?>
<tr>
	<td><?php echo $d['USU_CODIGO']; ?></td>
	<td><?php echo $d['USU_NOME']; ?></td>
	<td><?php echo $d['USU_LOGIN']; ?></td>
	<td><?php echo $d['USU_SENHA']; ?></td>
	<td><?php echo $d['USU_NIVEL']; ?></td>
	<td><?php echo $d['USU_SRV_SMTP']; ?></td>
	<td><?php echo $d['USU_LGN_EMAIL']; ?></td>
	<td><?php echo $d['USU_PWD_EMAIL']; ?></td>
	<td><?php echo $d['USU_USU_EMAIL']; ?></td>
	<td><?php echo $d['USU_LBL_EMAIL']; ?></td>
	<td><?php echo $d['USU_PORTA_EMAIL']; ?></td>
	<td><?php echo $d['USU_ATIVO']; ?></td>
	<td><?php echo $d['USU_REQ_AUT_EMAIL']; ?></td>
	<td>
	<a href="usuario.php?e=<?php echo $d['USU_CODIGO']; ?>">[E]</a>
	<a href="usuario.php?d=<?php echo $d['USU_CODIGO']; ?>">[D]</a></td>
</tr>
<?php endforeach;?>
</table>
<?php endif;?>