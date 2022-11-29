<?php
global $config, $bd, $Aplic;
$sql = new BDConsulta;

$sql->adTabela('instrumento');
$sql->adCampo('instrumento_id, instrumento_valor');	
$lista=$sql->lista();
$sql->limpar();


foreach($lista as $linha){
	
	$sql->adTabela('instrumento_gestao');
	$sql->esqUnir('instrumento', 'instrumento', 'instrumento_id=instrumento_gestao_semelhante');
	$sql->adCampo('SUM(instrumento_valor)');
	$sql->adOnde('instrumento_gestao_semelhante IS NOT NULL');
	$sql->adOnde('instrumento_gestao_instrumento='.(int)$linha['instrumento_id']);
	$soma_filhos=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('instrumento_gestao');
	$sql->esqUnir('instrumento', 'instrumento', 'instrumento_id=instrumento_gestao_instrumento');
	$sql->adCampo('SUM(instrumento_valor)');
	$sql->adOnde('instrumento_gestao_instrumento IS NOT NULL');
	$sql->adOnde('instrumento_gestao_semelhante='.(int)$linha['instrumento_id']);
	$soma_pais=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('instrumento');
	$sql->adAtualizar('instrumento_valor_atual', $linha['instrumento_valor']+$soma_filhos+$soma_pais);
	$sql->adOnde('instrumento_id='.(int)$linha['instrumento_id']);
	$sql->exec();
	$sql->limpar();
	}

?>