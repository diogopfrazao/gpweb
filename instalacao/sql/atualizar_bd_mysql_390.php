<?php
global $config, $bd;


$sql = new BDConsulta;
$sql->adTabela('recurso_tarefas');	
$sql->esqUnir('tarefas','tarefas','recurso_tarefa_id=tarefas.tarefa_id');
$sql->esqUnir('recursos','recursos','recurso_tarefas.recurso_id=recursos.recurso_id');	
$sql->adCampo('recursos.recurso_id, tarefas.tarefa_id, tarefa_inicio, tarefa_fim, tarefa_duracao, tarefa_dono, recurso_tarefas.recurso_quantidade, recurso_hora_custo, recurso_custo, recurso_tarefa_ordem');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha) {
	$sql->adTabela('recurso_tarefa');
	$sql->adInserir('recurso_tarefa_recurso', $linha['recurso_id']);
	$sql->adInserir('recurso_tarefa_tarefa', $linha['tarefa_id']);
	$sql->adInserir('recurso_tarefa_inicio', $linha['tarefa_inicio']);
	$sql->adInserir('recurso_tarefa_fim', $linha['tarefa_fim']);
	$sql->adInserir('recurso_tarefa_duracao', $linha['tarefa_duracao']);
	$sql->adInserir('recurso_tarefa_aprovou', $linha['tarefa_dono']);
	$sql->adInserir('recurso_tarefa_aprovado', 1);
	$sql->adInserir('recurso_tarefa_quantidade', $linha['recurso_quantidade']);
	$sql->adInserir('recurso_tarefa_custo', $linha['recurso_custo']);
	$sql->adInserir('recurso_tarefa_valor_hora', $linha['recurso_hora_custo']);
	$sql->adInserir('recurso_tarefa_corrido', 0);
	$sql->adInserir('recurso_tarefa_percentual', 100);
	$sql->adInserir('recurso_tarefa_ordem', $linha['recurso_tarefa_ordem']);
	$sql->exec();
	$sql->limpar();
	}	


?>