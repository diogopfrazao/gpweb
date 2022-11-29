<?php
global $config, $bd, $Aplic;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
			
	$sql->adTabela('instrumento');	
	$sql->adCampo('instrumento_id, instrumento_valor, instrumento_campo');
	
	//$sql->adOnde('instrumento_campo !=6');
	
	$linhas=$sql->lista();
	$sql->limpar();
		
	foreach($linhas as $linha){
		if ($linha['instrumento_campo']!=6){
			$sql->adTabela('instrumento_avulso_custo');
			$sql->esqUnir('instrumento_custo', 'instrumento_custo', 'instrumento_custo_avulso=instrumento_avulso_custo_id');
			$sql->adCampo('SUM(CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_custo_quantidade+instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((100+instrumento_avulso_custo_acrescimo)/100)) END	* (CASE WHEN instrumento_avulso_custo_servico > 0 THEN instrumento_avulso_custo_meses ELSE 1 END)) AS valor');
			$sql->adOnde('instrumento_custo_instrumento ='.(int)$linha['instrumento_id']);
			$total_acrescimo=$sql->Resultado();
			$sql->limpar();
			
			$sql->adTabela('instrumento');
			$sql->adAtualizar('instrumento_valor_atual', $total_acrescimo+$linha['instrumento_valor']);
			$sql->adOnde('instrumento_id='.(int)$linha['instrumento_id']);
			$sql->exec();
			$sql->limpar();
			}
		else {
			$sql->adTabela('instrumento');
			$sql->adAtualizar('instrumento_valor_atual', $linha['instrumento_valor']);
			$sql->adOnde('instrumento_id='.(int)$linha['instrumento_id']);
			$sql->exec();
			$sql->limpar();
			}	
		}	
		
	}
?>