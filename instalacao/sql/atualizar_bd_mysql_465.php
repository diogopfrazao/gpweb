<?php
global $config;


$sql = new BDConsulta;

	
$sql->adTabela('usuarios');	
$sql->adCampo('usuario_id, usuario_assinatura_nome');
$sql->adOnde('usuario_assinatura_nome IS NOT NULL');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha) {
	$sql->adTabela('usuarios');
	$sql->adAtualizar('usuario_assinatura_local', 'assinaturas/');
	$sql->adOnde('usuario_id='.$linha['usuario_id']);
	$sql->exec();
	$sql->limpar();	
	}	

?>