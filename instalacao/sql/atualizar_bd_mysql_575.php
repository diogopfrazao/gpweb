<?php
global $config, $bd, $Aplic;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
	$sql->adTabela('tarefa_entrega');	
	$sql->esqUnir('tarefas','tarefas','tarefa_id=tarefa_entrega_tarefa');	
	$sql->adCampo('tarefa_entrega_id, tarefa_dono, tarefa_fim');
	$linhas=$sql->Lista();
	$sql->limpar();
		
	foreach($linhas as $linha){
		$sql->adTabela('tarefa_entrega');
		$sql->adAtualizar('tarefa_entrega_responsavel', $linha['tarefa_dono']);
		$sql->adAtualizar('tarefa_entrega_prazo', $linha['tarefa_fim']);
		$sql->adOnde('tarefa_entrega_id='.(int)$linha['tarefa_entrega_id']);
		$sql->exec();
		$sql->limpar();
		}
	}
?>