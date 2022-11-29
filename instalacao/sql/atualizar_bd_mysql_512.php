<?php
$sql = new BDConsulta;
$sql->adTabela('plano_acao');	
$sql->adCampo('plano_acao_id');
$lista = $sql->carregarColuna();
$sql->limpar();

foreach ($lista as $plano_acao_id){
	//checagem se todas as linhas tem data de inicio e término
	$sql->adTabela('plano_acao_item');
	$sql->adCampo('count(plano_acao_item_id)');
	$sql->adOnde('plano_acao_item_inicio IS NULL OR plano_acao_item_fim IS NULL');
	$sql->adOnde('plano_acao_item_acao='.(int)$plano_acao_id);
	$tem_vazio=$sql->resultado();
	$sql->limpar();
	
	$sql->adTabela('plano_acao');
	$sql->adAtualizar('plano_acao_data_apenas', ($tem_vazio ? 0 : 1));
	$sql->adOnde('plano_acao_id='.(int)$plano_acao_id);
	$sql->exec();
	$sql->limpar();
	}
?>