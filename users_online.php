<?php 
require_once 'class/VisitantesOnline.php';
?>
<h1>Teste da classe VisitantesOnline.php</h1>
<i>Usuarios Online: </i>
<strong>
<?php 
$vo = new VisitantesOnline();
echo $vo->getUsersOnline();
?>
</strong>
<?php 
$vo->dump();
?>