<?php
global $config, $bd;

$sql = new BDConsulta;
	
$sql->adTabela('plano_gestao_pontosfortes');	
$sql->adCampo('plano_gestao_pontosfortes.*');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha) {
	$sql->adTabela('pg_swot');
	$sql->adInserir('pg_swot_pg', $linha['pg_ponto_forte_pg_id']);
	$sql->adInserir('pg_swot_usuario', $linha['pg_ponto_forte_usuario']);
	$sql->adInserir('pg_swot_nome', $linha['pg_ponto_forte_nome']);
	$sql->adInserir('pg_swot_data', $linha['pg_ponto_forte_data']);
	$sql->adInserir('pg_swot_ordem', $linha['pg_ponto_forte_ordem']);
	$sql->adInserir('pg_swot_usuario', $linha['pg_ponto_forte_usuario']);
	$sql->adInserir('pg_swot_tipo', 's');
	$sql->exec();
	$sql->limpar();
	}	
	
$sql->adTabela('plano_gestao_oportunidade_melhorias');	
$sql->adCampo('plano_gestao_oportunidade_melhorias.*');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha) {
	$sql->adTabela('pg_swot');
	$sql->adInserir('pg_swot_pg', $linha['pg_oportunidade_melhoria_pg_id']);
	$sql->adInserir('pg_swot_usuario', $linha['pg_oportunidade_melhoria_usuario']);
	$sql->adInserir('pg_swot_nome', $linha['pg_oportunidade_melhoria_nome']);
	$sql->adInserir('pg_swot_data', $linha['pg_oportunidade_melhoria_data']);
	$sql->adInserir('pg_swot_ordem', $linha['pg_oportunidade_melhoria_ordem']);
	$sql->adInserir('pg_swot_usuario', $linha['pg_oportunidade_melhoria_usuario']);
	$sql->adInserir('pg_swot_tipo', 'w');
	$sql->exec();
	$sql->limpar();
	}		

$sql->adTabela('plano_gestao_oportunidade');	
$sql->adCampo('plano_gestao_oportunidade.*');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha) {
	$sql->adTabela('pg_swot');
	$sql->adInserir('pg_swot_pg', $linha['pg_oportunidade_pg_id']);
	$sql->adInserir('pg_swot_usuario', $linha['pg_oportunidade_usuario']);
	$sql->adInserir('pg_swot_nome', $linha['pg_oportunidade_nome']);
	$sql->adInserir('pg_swot_data', $linha['pg_oportunidade_data']);
	$sql->adInserir('pg_swot_ordem', $linha['pg_oportunidade_ordem']);
	$sql->adInserir('pg_swot_usuario', $linha['pg_oportunidade_usuario']);
	$sql->adInserir('pg_swot_tipo', 'o');
	$sql->exec();
	$sql->limpar();
	}		

$sql->adTabela('plano_gestao_ameacas');	
$sql->adCampo('plano_gestao_ameacas.*');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha) {
	$sql->adTabela('pg_swot');
	$sql->adInserir('pg_swot_pg', $linha['pg_ameaca_pg_id']);
	$sql->adInserir('pg_swot_usuario', $linha['pg_ameaca_usuario']);
	$sql->adInserir('pg_swot_nome', $linha['pg_ameaca_nome']);
	$sql->adInserir('pg_swot_data', $linha['pg_ameaca_data']);
	$sql->adInserir('pg_swot_ordem', $linha['pg_ameaca_ordem']);
	$sql->adInserir('pg_swot_usuario', $linha['pg_ameaca_usuario']);
	$sql->adInserir('pg_swot_tipo', 't');
	$sql->exec();
	$sql->limpar();
	}		
	
?>