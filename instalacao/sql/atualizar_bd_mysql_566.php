<?php
global $config, $bd, $Aplic;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
	$sql->adTabela('os');	
	$sql->adCampo('os_id, os_valor');
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		$sql->adTabela('os_avulso_custo');
		$sql->esqUnir('os_custo', 'os_custo', 'os_custo_avulso=os_avulso_custo_id');
		$sql->adCampo('SUM(CASE WHEN os_avulso_custo_percentagem=0 THEN (((os_avulso_custo_acrescimo)*(CASE WHEN os_avulso_custo_custo_atual > 0 THEN os_avulso_custo_custo_atual ELSE os_avulso_custo_custo END))*((100+os_avulso_custo_bdi)/100)) ELSE ((os_custo_quantidade*(CASE WHEN os_avulso_custo_custo_atual > 0 THEN os_avulso_custo_custo_atual ELSE os_avulso_custo_custo END))*((100+os_avulso_custo_bdi)/100)*((os_avulso_custo_acrescimo)/100)) END) AS total_acrescimo');	
		$sql->adOnde('os_custo_os ='.(int)$linha['os_id']);
		$total_acrescimo=$sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('os');
		$sql->adAtualizar('os_valor_atual', $total_acrescimo+$linha['os_valor']);
		$sql->adOnde('os_id='.(int)$linha['os_id']);
		$sql->exec();
		$sql->limpar();
		}
	
	
	$sql->adTabela('tr');	
	$sql->adCampo('tr_id, tr_valor');
	$linhas=$sql->Lista();
	$sql->limpar();
	foreach($linhas as $linha){
		$sql->adTabela('tr_avulso_custo');
		$sql->esqUnir('tr_custo', 'tr_custo', 'tr_custo_avulso=tr_avulso_custo_id');
		$sql->adCampo('SUM(CASE WHEN tr_avulso_custo_percentagem=0 THEN (((tr_avulso_custo_acrescimo)*(CASE WHEN tr_avulso_custo_custo_atual > 0 THEN tr_avulso_custo_custo_atual ELSE tr_avulso_custo_custo END))*((100+tr_avulso_custo_bdi)/100)) ELSE ((tr_custo_quantidade*(CASE WHEN tr_avulso_custo_custo_atual > 0 THEN tr_avulso_custo_custo_atual ELSE tr_avulso_custo_custo END))*((100+tr_avulso_custo_bdi)/100)*((tr_avulso_custo_acrescimo)/100)) END) AS total_acrescimo');	
		$sql->adOnde('tr_custo_tr ='.(int)$linha['tr_id']);
		$total_acrescimo=$sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('tr');
		$sql->adAtualizar('tr_valor_atual', $total_acrescimo+$linha['tr_valor']);
		$sql->adOnde('tr_id='.(int)$linha['tr_id']);
		$sql->exec();
		$sql->limpar();
		}
	}
?>