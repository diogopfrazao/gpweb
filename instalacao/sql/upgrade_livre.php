<?php
global $config, $bd;

$sql = new BDConsulta;
$sql->adTabela('plano_acao_gestao');	
$sql->adCampo('DISTINCT plano_acao_gestao_acao');
$sql->adOnde('plano_acao_gestao_acao > 0');
$acao_gestao = $sql->carregarColuna();
$sql->limpar();

$sql->adTabela('plano_acao');	
$sql->adCampo('plano_acao.*');
if (count($acao_gestao)) $sql->adOnde('plano_acao_id NOT IN ('.implode(',',$acao_gestao).')');
$lista = $sql->lista();
$sql->limpar();	
		
foreach($lista AS $linha) {
	if (isset($linha['plano_acao_tarefa']) && $linha['plano_acao_tarefa'] && $linha['plano_acao_projeto']) {
		$sql->adTabela('plano_acao_gestao');
		$sql->adInserir('plano_acao_gestao_acao', $linha['plano_acao_id']);
		$sql->adInserir('plano_acao_gestao_tarefa', $linha['plano_acao_tarefa']);
		if ($linha['plano_acao_projeto']) $sql->adInserir('plano_acao_gestao_projeto', $linha['plano_acao_projeto']);
		$sql->exec();
		$sql->limpar();
		}
		
	elseif (isset($linha['plano_acao_projeto']) && $linha['plano_acao_projeto']) {
		$sql->adTabela('plano_acao_gestao');
		$sql->adInserir('plano_acao_gestao_acao', $linha['plano_acao_id']);
		$sql->adInserir('plano_acao_gestao_projeto', $linha['plano_acao_projeto']);
		$sql->exec();
		$sql->limpar();
		}	
		
	elseif (isset($linha['plano_acao_pratica']) && $linha['plano_acao_pratica']) {
		$sql->adTabela('plano_acao_gestao');
		$sql->adInserir('plano_acao_gestao_acao', $linha['plano_acao_id']);
		$sql->adInserir('plano_acao_gestao_pratica', $linha['plano_acao_pratica']);
		$sql->exec();
		$sql->limpar();
		}	

		
	elseif (isset($linha['plano_acao_indicador']) && $linha['plano_acao_indicador']) {
		$sql->adTabela('plano_acao_gestao');
		$sql->adInserir('plano_acao_gestao_acao', $linha['plano_acao_id']);
		$sql->adInserir('plano_acao_gestao_indicador', $linha['plano_acao_indicador']);
		$sql->exec();
		$sql->limpar();
		}	
	
	elseif (isset($linha['plano_acao_perspectiva']) && $linha['plano_acao_perspectiva']) {
		$sql->adTabela('plano_acao_gestao');
		$sql->adInserir('plano_acao_gestao_acao', $linha['plano_acao_id']);
		$sql->adInserir('plano_acao_gestao_perspectiva', $linha['plano_acao_perspectiva']);
		$sql->exec();
		$sql->limpar();
		}	
	
	elseif (isset($linha['plano_acao_tema']) && $linha['plano_acao_tema']) {
		$sql->adTabela('plano_acao_gestao');
		$sql->adInserir('plano_acao_gestao_acao', $linha['plano_acao_id']);
		$sql->adInserir('plano_acao_gestao_tema', $linha['plano_acao_tema']);
		$sql->exec();
		$sql->limpar();
		}	
	
	elseif (isset($linha['plano_acao_objetivo']) && $linha['plano_acao_objetivo']) {
		$sql->adTabela('plano_acao_gestao');
		$sql->adInserir('plano_acao_gestao_acao', $linha['plano_acao_id']);
		$sql->adInserir('plano_acao_gestao_objetivo', $linha['plano_acao_objetivo']);
		$sql->exec();
		$sql->limpar();
		}		
			
	elseif (isset($linha['plano_acao_estrategia']) && $linha['plano_acao_estrategia']) {
		$sql->adTabela('plano_acao_gestao');
		$sql->adInserir('plano_acao_gestao_acao', $linha['plano_acao_id']);
		$sql->adInserir('plano_acao_gestao_estrategia', $linha['plano_acao_estrategia']);
		$sql->exec();
		$sql->limpar();
		}	
		
	elseif (isset($linha['plano_acao_meta']) && $linha['plano_acao_meta']) {
		$sql->adTabela('plano_acao_gestao');
		$sql->adInserir('plano_acao_gestao_acao', $linha['plano_acao_id']);
		$sql->adInserir('plano_acao_gestao_meta', $linha['plano_acao_meta']);
		$sql->exec();
		$sql->limpar();
		}		
	
	elseif (isset($linha['plano_acao_fator']) && $linha['plano_acao_fator']) {
		$sql->adTabela('plano_acao_gestao');
		$sql->adInserir('plano_acao_gestao_acao', $linha['plano_acao_id']);
		$sql->adInserir('plano_acao_gestao_fator', $linha['plano_acao_fator']);
		$sql->exec();
		$sql->limpar();
		}	
	}

























$sql->adTabela('canvas_log');
$sql->adCampo('canvas_log.*');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	if ($linha['canvas_log_id']){
		$sql->adTabela('log');
		$sql->adInserir('log_canvas', $linha['canvas_log_canvas']);
		$sql->adInserir('log_horas', $linha['canvas_log_horas']);
		$sql->adInserir('log_criador', $linha['canvas_log_criador']);
		$sql->adInserir('log_descricao', $linha['canvas_log_descricao']);
		$sql->adInserir('log_corrigir', $linha['canvas_log_problema']);
		$sql->adInserir('log_referencia', $linha['canvas_log_referencia']);
		$sql->adInserir('log_nome', $linha['canvas_log_nome']);
		$sql->adInserir('log_data', $linha['canvas_log_data']);
		$sql->adInserir('log_url_relacionada', $linha['canvas_log_url_relacionada']);
		$sql->adInserir('log_acesso', $linha['canvas_log_id']);
		$sql->exec();
		$log_id=$bd->Insert_ID('log','log_id');
		$sql->limpar();
		
		if ($linha['canvas_log_custo']>0 && $log_id) {
			$sql->adTabela('custo');
			$sql->adInserir('custo_log', $log_id);
			$sql->adInserir('custo_custo', $linha['canvas_log_custo']);
			$sql->adInserir('custo_nd', $linha['canvas_log_nd']);
			$sql->adInserir('custo_categoria_economica', $linha['canvas_log_categoria_economica']);
			$sql->adInserir('custo_grupo_despesa', $linha['canvas_log_grupo_despesa']);
			$sql->adInserir('custo_modalidade_aplicacao', $linha['canvas_log_modalidade_aplicacao']);
			$sql->adInserir('custo_data', $linha['canvas_log_data']);
			$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
			$sql->adInserir('custo_gasto', 1);
			$sql->adInserir('custo_quantidade', 1);
			$sql->exec();
			$sql->limpar();
			}
		
		}
	}



$sql->adTabela('estrategias_log');
$sql->adCampo('estrategias_log.*');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	if ($linha['pg_estrategia_log_id']){
		$sql->adTabela('log');
		$sql->adInserir('log_estrategia', $linha['pg_estrategia_log_estrategia']);
		$sql->adInserir('log_horas', $linha['pg_estrategia_log_horas']);
		$sql->adInserir('log_criador', $linha['pg_estrategia_log_criador']);
		$sql->adInserir('log_descricao', $linha['pg_estrategia_log_descricao']);
		$sql->adInserir('log_corrigir', $linha['pg_estrategia_log_problema']);
		$sql->adInserir('log_referencia', $linha['pg_estrategia_log_referencia']);
		$sql->adInserir('log_nome', $linha['pg_estrategia_log_nome']);
		$sql->adInserir('log_data', $linha['pg_estrategia_log_data']);
		$sql->adInserir('log_url_relacionada', $linha['pg_estrategia_log_url_relacionada']);
		$sql->adInserir('log_acesso', $linha['pg_estrategia_log_id']);
		$sql->exec();
		$log_id=$bd->Insert_ID('log','log_id');
		$sql->limpar();
		
		if ($linha['pg_estrategia_log_custo']>0 && $log_id) {
			$sql->adTabela('custo');
			$sql->adInserir('custo_log', $log_id);
			$sql->adInserir('custo_custo', $linha['pg_estrategia_log_custo']);
			$sql->adInserir('custo_nd', $linha['pg_estrategia_log_nd']);
			$sql->adInserir('custo_categoria_economica', $linha['pg_estrategia_log_categoria_economica']);
			$sql->adInserir('custo_grupo_despesa', $linha['pg_estrategia_log_grupo_despesa']);
			$sql->adInserir('custo_modalidade_aplicacao', $linha['pg_estrategia_log_modalidade_aplicacao']);
			$sql->adInserir('custo_data', $linha['pg_estrategia_log_data']);
			$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
			$sql->adInserir('custo_gasto', 1);
			$sql->adInserir('custo_quantidade', 1);
			$sql->exec();
			$sql->limpar();
			}
		
		}
	}
	
	
	
$sql->adTabela('fator_log');
$sql->adCampo('fator_log.*');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	if ($linha['fator_log_id']){
		$sql->adTabela('log');
		$sql->adInserir('log_fator', $linha['fator_log_fator']);
		$sql->adInserir('log_horas', $linha['fator_log_horas']);
		$sql->adInserir('log_criador', $linha['fator_log_criador']);
		$sql->adInserir('log_descricao', $linha['fator_log_descricao']);
		$sql->adInserir('log_corrigir', $linha['fator_log_problema']);
		$sql->adInserir('log_referencia', $linha['fator_log_referencia']);
		$sql->adInserir('log_nome', $linha['fator_log_nome']);
		$sql->adInserir('log_data', $linha['fator_log_data']);
		$sql->adInserir('log_url_relacionada', $linha['fator_log_url_relacionada']);
		$sql->adInserir('log_acesso', $linha['fator_log_id']);
		$sql->exec();
		$log_id=$bd->Insert_ID('log','log_id');
		$sql->limpar();
		
		if ($linha['fator_log_custo']>0 && $log_id) {
			$sql->adTabela('custo');
			$sql->adInserir('custo_log', $log_id);
			$sql->adInserir('custo_custo', $linha['fator_log_custo']);
			$sql->adInserir('custo_nd', $linha['fator_log_nd']);
			$sql->adInserir('custo_categoria_economica', $linha['fator_log_categoria_economica']);
			$sql->adInserir('custo_grupo_despesa', $linha['fator_log_grupo_despesa']);
			$sql->adInserir('custo_modalidade_aplicacao', $linha['fator_log_modalidade_aplicacao']);
			$sql->adInserir('custo_data', $linha['fator_log_data']);
			$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
			$sql->adInserir('custo_gasto', 1);
			$sql->adInserir('custo_quantidade', 1);
			$sql->exec();
			$sql->limpar();
			}
		
		}
	}
		
$sql->adTabela('instrumento_log');
$sql->adCampo('instrumento_log.*');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	if ($linha['instrumento_log_id']){
		$sql->adTabela('log');
		$sql->adInserir('log_instrumento', $linha['instrumento_log_instrumento']);
		$sql->adInserir('log_horas', $linha['instrumento_log_horas']);
		$sql->adInserir('log_criador', $linha['instrumento_log_criador']);
		$sql->adInserir('log_descricao', $linha['instrumento_log_descricao']);
		$sql->adInserir('log_corrigir', $linha['instrumento_log_problema']);
		$sql->adInserir('log_referencia', $linha['instrumento_log_referencia']);
		$sql->adInserir('log_nome', $linha['instrumento_log_nome']);
		$sql->adInserir('log_data', $linha['instrumento_log_data']);
		$sql->adInserir('log_url_relacionada', $linha['instrumento_log_url_relacionada']);
		$sql->adInserir('log_acesso', $linha['instrumento_log_id']);
		$sql->exec();
		$log_id=$bd->Insert_ID('log','log_id');
		$sql->limpar();
		
		if ($linha['instrumento_log_custo']>0 && $log_id) {
			$sql->adTabela('custo');
			$sql->adInserir('custo_log', $log_id);
			$sql->adInserir('custo_custo', $linha['instrumento_log_custo']);
			$sql->adInserir('custo_nd', $linha['instrumento_log_nd']);
			$sql->adInserir('custo_categoria_economica', $linha['instrumento_log_categoria_economica']);
			$sql->adInserir('custo_grupo_despesa', $linha['instrumento_log_grupo_despesa']);
			$sql->adInserir('custo_modalidade_aplicacao', $linha['instrumento_log_modalidade_aplicacao']);
			$sql->adInserir('custo_data', $linha['instrumento_log_data']);
			$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
			$sql->adInserir('custo_gasto', 1);
			$sql->adInserir('custo_quantidade', 1);
			$sql->exec();
			$sql->limpar();
			}
		}
	}
	
	
	
$sql->adTabela('metas_log');
$sql->adCampo('metas_log.*');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	if ($linha['pg_meta_log_id']){
		$sql->adTabela('log');
		$sql->adInserir('log_meta', $linha['pg_meta_log_meta']);
		$sql->adInserir('log_horas', $linha['pg_meta_log_horas']);
		$sql->adInserir('log_criador', $linha['pg_meta_log_criador']);
		$sql->adInserir('log_descricao', $linha['pg_meta_log_descricao']);
		$sql->adInserir('log_corrigir', $linha['pg_meta_log_problema']);
		$sql->adInserir('log_referencia', $linha['pg_meta_log_referencia']);
		$sql->adInserir('log_nome', $linha['pg_meta_log_nome']);
		$sql->adInserir('log_data', $linha['pg_meta_log_data']);
		$sql->adInserir('log_url_relacionada', $linha['pg_meta_log_url_relacionada']);
		$sql->adInserir('log_acesso', $linha['pg_meta_log_id']);
		$sql->exec();
		$log_id=$bd->Insert_ID('log','log_id');
		$sql->limpar();
		
		if ($linha['pg_meta_log_custo']>0 && $log_id) {
			$sql->adTabela('custo');
			$sql->adInserir('custo_log', $log_id);
			$sql->adInserir('custo_custo', $linha['pg_meta_log_custo']);
			$sql->adInserir('custo_nd', $linha['pg_meta_log_nd']);
			$sql->adInserir('custo_categoria_economica', $linha['pg_meta_log_categoria_economica']);
			$sql->adInserir('custo_grupo_despesa', $linha['pg_meta_log_grupo_despesa']);
			$sql->adInserir('custo_modalidade_aplicacao', $linha['pg_meta_log_modalidade_aplicacao']);
			$sql->adInserir('custo_data', $linha['pg_meta_log_data']);
			$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
			$sql->adInserir('custo_gasto', 1);
			$sql->adInserir('custo_quantidade', 1);
			$sql->exec();
			$sql->limpar();
			}
		
		}
	}
	
	
$sql->adTabela('objetivo_log');
$sql->adCampo('objetivo_log.*');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	if ($linha['objetivo_log_id']){
		$sql->adTabela('log');
		$sql->adInserir('log_objetivo', $linha['objetivo_log_objetivo']);
		$sql->adInserir('log_horas', $linha['objetivo_log_horas']);
		$sql->adInserir('log_criador', $linha['objetivo_log_criador']);
		$sql->adInserir('log_descricao', $linha['objetivo_log_descricao']);
		$sql->adInserir('log_corrigir', $linha['objetivo_log_problema']);
		$sql->adInserir('log_referencia', $linha['objetivo_log_referencia']);
		$sql->adInserir('log_nome', $linha['objetivo_log_nome']);
		$sql->adInserir('log_data', $linha['objetivo_log_data']);
		$sql->adInserir('log_url_relacionada', $linha['objetivo_log_url_relacionada']);
		$sql->adInserir('log_acesso', $linha['objetivo_log_id']);
		$sql->exec();
		$log_id=$bd->Insert_ID('log','log_id');
		$sql->limpar();
		
		if ($linha['objetivo_log_custo']>0 && $log_id) {
			$sql->adTabela('custo');
			$sql->adInserir('custo_log', $log_id);
			$sql->adInserir('custo_custo', $linha['objetivo_log_custo']);
			$sql->adInserir('custo_nd', $linha['objetivo_log_nd']);
			$sql->adInserir('custo_categoria_economica', $linha['objetivo_log_categoria_economica']);
			$sql->adInserir('custo_grupo_despesa', $linha['objetivo_log_grupo_despesa']);
			$sql->adInserir('custo_modalidade_aplicacao', $linha['objetivo_log_modalidade_aplicacao']);
			$sql->adInserir('custo_data', $linha['objetivo_log_data']);
			$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
			$sql->adInserir('custo_gasto', 1);
			$sql->adInserir('custo_quantidade', 1);
			$sql->exec();
			$sql->limpar();
			}
		
		}
	}
	
	
$sql->adTabela('perspectiva_log');
$sql->adCampo('perspectiva_log.*');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	if ($linha['perspectiva_log_id']){
		$sql->adTabela('log');
		$sql->adInserir('log_perspectiva', $linha['perspectiva_log_perspectiva']);
		$sql->adInserir('log_horas', $linha['perspectiva_log_horas']);
		$sql->adInserir('log_criador', $linha['perspectiva_log_criador']);
		$sql->adInserir('log_descricao', $linha['perspectiva_log_descricao']);
		$sql->adInserir('log_corrigir', $linha['perspectiva_log_problema']);
		$sql->adInserir('log_referencia', $linha['perspectiva_log_referencia']);
		$sql->adInserir('log_nome', $linha['perspectiva_log_nome']);
		$sql->adInserir('log_data', $linha['perspectiva_log_data']);
		$sql->adInserir('log_url_relacionada', $linha['perspectiva_log_url_relacionada']);
		$sql->adInserir('log_acesso', $linha['perspectiva_log_id']);
		$sql->exec();
		$log_id=$bd->Insert_ID('log','log_id');
		$sql->limpar();
		
		if ($linha['perspectiva_log_custo']>0 && $log_id) {
			$sql->adTabela('custo');
			$sql->adInserir('custo_log', $log_id);
			$sql->adInserir('custo_custo', $linha['perspectiva_log_custo']);
			$sql->adInserir('custo_nd', $linha['perspectiva_log_nd']);
			$sql->adInserir('custo_categoria_economica', $linha['perspectiva_log_categoria_economica']);
			$sql->adInserir('custo_grupo_despesa', $linha['perspectiva_log_grupo_despesa']);
			$sql->adInserir('custo_modalidade_aplicacao', $linha['perspectiva_log_modalidade_aplicacao']);
			$sql->adInserir('custo_data', $linha['perspectiva_log_data']);
			$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
			$sql->adInserir('custo_gasto', 1);
			$sql->adInserir('custo_quantidade', 1);
			$sql->exec();
			$sql->limpar();
			}
		
		}
	}
	
	
$sql->adTabela('plano_acao_log');
$sql->adCampo('plano_acao_log.*');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	if ($linha['plano_acao_log_id']){
		$sql->adTabela('log');
		$sql->adInserir('log_acao', $linha['plano_acao_log_plano_acao']);
		$sql->adInserir('log_horas', $linha['plano_acao_log_horas']);
		$sql->adInserir('log_criador', $linha['plano_acao_log_criador']);
		$sql->adInserir('log_descricao', $linha['plano_acao_log_descricao']);
		$sql->adInserir('log_corrigir', $linha['plano_acao_log_problema']);
		$sql->adInserir('log_referencia', $linha['plano_acao_log_referencia']);
		$sql->adInserir('log_nome', $linha['plano_acao_log_nome']);
		$sql->adInserir('log_data', $linha['plano_acao_log_data']);
		$sql->adInserir('log_url_relacionada', $linha['plano_acao_log_url_relacionada']);
		$sql->adInserir('log_acesso', $linha['plano_acao_log_id']);
		$sql->exec();
		$log_id=$bd->Insert_ID('log','log_id');
		$sql->limpar();
		
		if ($linha['plano_acao_log_custo']>0 && $log_id) {
			$sql->adTabela('custo');
			$sql->adInserir('custo_log', $log_id);
			$sql->adInserir('custo_custo', $linha['plano_acao_log_custo']);
			$sql->adInserir('custo_nd', $linha['plano_acao_log_nd']);
			$sql->adInserir('custo_categoria_economica', $linha['plano_acao_log_categoria_economica']);
			$sql->adInserir('custo_grupo_despesa', $linha['plano_acao_log_grupo_despesa']);
			$sql->adInserir('custo_modalidade_aplicacao', $linha['plano_acao_log_modalidade_aplicacao']);
			$sql->adInserir('custo_data', $linha['plano_acao_log_data']);
			$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
			$sql->adInserir('custo_gasto', 1);
			$sql->adInserir('custo_quantidade', 1);
			$sql->exec();
			$sql->limpar();
			}
		
		}
	}
	
	
$sql->adTabela('pratica_indicador_log');
$sql->adCampo('pratica_indicador_log.*');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	if ($linha['pratica_indicador_log_id']){
		$sql->adTabela('log');
		$sql->adInserir('log_indicador', $linha['pratica_indicador_log_pratica_indicador']);
		$sql->adInserir('log_horas', $linha['pratica_indicador_log_horas']);
		$sql->adInserir('log_criador', $linha['pratica_indicador_log_criador']);
		$sql->adInserir('log_descricao', $linha['pratica_indicador_log_descricao']);
		$sql->adInserir('log_corrigir', $linha['pratica_indicador_log_problema']);
		$sql->adInserir('log_referencia', $linha['pratica_indicador_log_referencia']);
		$sql->adInserir('log_nome', $linha['pratica_indicador_log_nome']);
		$sql->adInserir('log_data', $linha['pratica_indicador_log_data']);
		$sql->adInserir('log_url_relacionada', $linha['pratica_indicador_log_url_relacionada']);
		$sql->adInserir('log_acesso', $linha['pratica_indicador_log_id']);
		$sql->exec();
		$log_id=$bd->Insert_ID('log','log_id');
		$sql->limpar();
		
		if ($linha['pratica_indicador_log_custo']>0 && $log_id) {
			$sql->adTabela('custo');
			$sql->adInserir('custo_log', $log_id);
			$sql->adInserir('custo_custo', $linha['pratica_indicador_log_custo']);
			$sql->adInserir('custo_nd', $linha['pratica_indicador_log_nd']);
			$sql->adInserir('custo_categoria_economica', $linha['pratica_indicador_log_categoria_economica']);
			$sql->adInserir('custo_grupo_despesa', $linha['pratica_indicador_log_grupo_despesa']);
			$sql->adInserir('custo_modalidade_aplicacao', $linha['pratica_indicador_log_modalidade_aplicacao']);
			$sql->adInserir('custo_data', $linha['pratica_indicador_log_data']);
			$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
			$sql->adInserir('custo_gasto', 1);
			$sql->adInserir('custo_quantidade', 1);
			$sql->exec();
			$sql->limpar();
			}
		
		}
	}
	
	
$sql->adTabela('pratica_log');
$sql->adCampo('pratica_log.*');
$lista = $sql->lista();
$sql->limpar();

foreach($lista as $linha){
	if ($linha['pratica_log_id']){
		$sql->adTabela('log');
		$sql->adInserir('log_pratica', $linha['pratica_log_pratica']);
		$sql->adInserir('log_horas', $linha['pratica_log_horas']);
		$sql->adInserir('log_criador', $linha['pratica_log_criador']);
		$sql->adInserir('log_descricao', $linha['pratica_log_descricao']);
		$sql->adInserir('log_corrigir', $linha['pratica_log_problema']);
		$sql->adInserir('log_referencia', $linha['pratica_log_referencia']);
		$sql->adInserir('log_nome', $linha['pratica_log_nome']);
		$sql->adInserir('log_data', $linha['pratica_log_data']);
		$sql->adInserir('log_url_relacionada', $linha['pratica_log_url_relacionada']);
		$sql->adInserir('log_acesso', $linha['pratica_log_id']);
		$sql->exec();
		$log_id=$bd->Insert_ID('log','log_id');
		$sql->limpar();
		
		if ($linha['pratica_log_custo']>0 && $log_id) {
			$sql->adTabela('custo');
			$sql->adInserir('custo_log', $log_id);
			$sql->adInserir('custo_custo', $linha['pratica_log_custo']);
			$sql->adInserir('custo_nd', $linha['pratica_log_nd']);
			$sql->adInserir('custo_categoria_economica', $linha['pratica_log_categoria_economica']);
			$sql->adInserir('custo_grupo_despesa', $linha['pratica_log_grupo_despesa']);
			$sql->adInserir('custo_modalidade_aplicacao', $linha['pratica_log_modalidade_aplicacao']);
			$sql->adInserir('custo_data', $linha['pratica_log_data']);
			$sql->adInserir('custo_nome', 'Gasto no registro de ocorrência');
			$sql->adInserir('custo_gasto', 1);
			$sql->adInserir('custo_quantidade', 1);
			$sql->exec();
			$sql->limpar();
			}
		
		}
	}
?>