<?php
$sql = new BDConsulta;

$sql->adTabela('pratica_indicador_valor');	
$sql->adCampo('pratica_indicador_valor.*');
$sql->adOnde('pratica_indicador_valor_vetor IS NOT NULL');
$valores = $sql->lista();
$sql->limpar();
foreach($valores as $linha){
	$vetor=array();
	$vetor=unserialize($linha['pratica_indicador_valor_vetor']);
	foreach($vetor as $chave => $valor) {
		$sql->adTabela('pratica_indicador_valor_simples');
		$sql->adInserir('pratica_indicador_valor_simples_formula_simples_id', $chave);
		$sql->adInserir('pratica_indicador_valor_simples_valor_id', (int)$linha['pratica_indicador_valor_id']);
		$sql->adInserir('pratica_indicador_valor_simples_valor', $valor);
		$sql->exec();
		$sql->limpar();
		}
	}
?>