<?php
global $config, $bd;


$sql = new BDConsulta;
$sql->adTabela('contatos');	
$sql->adCampo('contato_id, contato_dddtel, contato_tel, contato_dddtel2, contato_tel2, contato_dddcel, contato_cel');
$sql->adOnde('contato_dddtel IS NOT NULL OR contato_dddtel2 IS NOT NULL OR contato_dddcel IS NOT NULL');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha) {
	$sql->adTabela('contatos');
	if ($linha['contato_dddtel']) $sql->adAtualizar('contato_tel', '('.$linha['contato_dddtel'].') '.$linha['contato_tel']);
	if ($linha['contato_dddtel2']) $sql->adAtualizar('contato_tel2', '('.$linha['contato_dddtel2'].') '.$linha['contato_tel2']);
	if ($linha['contato_dddcel']) $sql->adAtualizar('contato_cel', '('.$linha['contato_dddcel'].') '.$linha['contato_cel']);
	$sql->adOnde('contato_id ='.(int)$linha['contato_id']);
	$sql->exec();
	$sql->limpar();
	}


?>