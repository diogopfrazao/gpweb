<?php
global $config;

if(!file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	$sql->adTabela('foruns');	
	$sql->adCampo('foruns.*');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('forum_gestao');
		$sql->adInserir('forum_gestao_forum', $linha['forum_id']);
		if ($linha['forum_tarefa']) $sql->adInserir('forum_gestao_tarefa', $linha['forum_tarefa']);
		else if ($linha['forum_projeto']) $sql->adInserir('forum_gestao_projeto', $linha['forum_projeto']);
		else if ($linha['forum_pratica']) $sql->adInserir('forum_gestao_pratica', $linha['forum_pratica']);
		else if ($linha['forum_acao']) $sql->adInserir('forum_gestao_acao', $linha['forum_acao']);
		else if ($linha['forum_perspectiva']) $sql->adInserir('forum_gestao_perspectiva', $linha['forum_perspectiva']);
		else if ($linha['forum_tema']) $sql->adInserir('forum_gestao_tema', $linha['forum_tema']);
		else if ($linha['forum_objetivo']) $sql->adInserir('forum_gestao_objetivo', $linha['forum_objetivo']);
		else if ($linha['forum_fator']) $sql->adInserir('forum_gestao_fator', $linha['forum_fator']);
		else if ($linha['forum_estrategia']) $sql->adInserir('forum_gestao_estrategia', $linha['forum_estrategia']);
		else if ($linha['forum_meta']) $sql->adInserir('forum_gestao_meta', $linha['forum_meta']);
		else if ($linha['forum_indicador']) $sql->adInserir('forum_gestao_indicador', $linha['forum_indicador']);
		$sql->exec();
		$sql->limpar();
		}
		
		
	$sql->adTabela('arquivo');	
	$sql->adCampo('arquivo.*');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('arquivo_gestao');
		$sql->adInserir('arquivo_gestao_arquivo', $linha['arquivo_id']);
		if ($linha['arquivo_tarefa']) $sql->adInserir('arquivo_gestao_tarefa', $linha['arquivo_tarefa']);
		else if ($linha['arquivo_projeto']) $sql->adInserir('arquivo_gestao_projeto', $linha['arquivo_projeto']);
		else if ($linha['arquivo_pratica']) $sql->adInserir('arquivo_gestao_pratica', $linha['arquivo_pratica']);
		else if ($linha['arquivo_acao']) $sql->adInserir('arquivo_gestao_acao', $linha['arquivo_acao']);
		else if ($linha['arquivo_perspectiva']) $sql->adInserir('arquivo_gestao_perspectiva', $linha['arquivo_perspectiva']);
		else if ($linha['arquivo_tema']) $sql->adInserir('arquivo_gestao_tema', $linha['arquivo_tema']);
		else if ($linha['arquivo_objetivo']) $sql->adInserir('arquivo_gestao_objetivo', $linha['arquivo_objetivo']);
		else if ($linha['arquivo_fator']) $sql->adInserir('arquivo_gestao_fator', $linha['arquivo_fator']);
		else if ($linha['arquivo_estrategia']) $sql->adInserir('arquivo_gestao_estrategia', $linha['arquivo_estrategia']);
		else if ($linha['arquivo_meta']) $sql->adInserir('arquivo_gestao_meta', $linha['arquivo_meta']);
		else if ($linha['arquivo_indicador']) $sql->adInserir('arquivo_gestao_indicador', $linha['arquivo_indicador']);
		else if ($linha['arquivo_usuario']) $sql->adInserir('arquivo_gestao_usuario', $linha['arquivo_usuario']);
		else if ($linha['arquivo_demanda']) $sql->adInserir('arquivo_gestao_demanda', $linha['arquivo_demanda']);
		else if ($linha['arquivo_instrumento']) $sql->adInserir('arquivo_gestao_instrumento', $linha['arquivo_instrumento']);
		else if ($linha['arquivo_calendario']) $sql->adInserir('arquivo_gestao_calendario', $linha['arquivo_calendario']);
		$sql->exec();
		$sql->limpar();
		}	

		
		
	$sql->adTabela('arquivo_pasta');	
	$sql->adCampo('arquivo_pasta.*');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('arquivo_pasta_gestao');
		$sql->adInserir('arquivo_pasta_gestao_pasta', $linha['arquivo_pasta_id']);
		if ($linha['arquivo_pasta_tarefa']) $sql->adInserir('arquivo_pasta_gestao_tarefa', $linha['arquivo_pasta_tarefa']);
		else if ($linha['arquivo_pasta_projeto']) $sql->adInserir('arquivo_pasta_gestao_projeto', $linha['arquivo_pasta_projeto']);
		else if ($linha['arquivo_pasta_pratica']) $sql->adInserir('arquivo_pasta_gestao_pratica', $linha['arquivo_pasta_pratica']);
		else if ($linha['arquivo_pasta_acao']) $sql->adInserir('arquivo_pasta_gestao_acao', $linha['arquivo_pasta_acao']);
		else if ($linha['arquivo_pasta_perspectiva']) $sql->adInserir('arquivo_pasta_gestao_perspectiva', $linha['arquivo_pasta_perspectiva']);
		else if ($linha['arquivo_pasta_tema']) $sql->adInserir('arquivo_pasta_gestao_tema', $linha['arquivo_pasta_tema']);
		else if ($linha['arquivo_pasta_objetivo']) $sql->adInserir('arquivo_pasta_gestao_objetivo', $linha['arquivo_pasta_objetivo']);
		else if ($linha['arquivo_pasta_fator']) $sql->adInserir('arquivo_pasta_gestao_fator', $linha['arquivo_pasta_fator']);
		else if ($linha['arquivo_pasta_estrategia']) $sql->adInserir('arquivo_pasta_gestao_estrategia', $linha['arquivo_pasta_estrategia']);
		else if ($linha['arquivo_pasta_meta']) $sql->adInserir('arquivo_pasta_gestao_meta', $linha['arquivo_pasta_meta']);
		else if ($linha['arquivo_pasta_indicador']) $sql->adInserir('arquivo_pasta_gestao_indicador', $linha['arquivo_pasta_indicador']);
		else if ($linha['arquivo_pasta_usuario']) $sql->adInserir('arquivo_pasta_gestao_usuario', $linha['arquivo_pasta_usuario']);
		else if ($linha['arquivo_pasta_demanda']) $sql->adInserir('arquivo_pasta_gestao_demanda', $linha['arquivo_pasta_demanda']);
		else if ($linha['arquivo_pasta_instrumento']) $sql->adInserir('arquivo_pasta_gestao_instrumento', $linha['arquivo_pasta_instrumento']);
		else if ($linha['arquivo_pasta_calendario']) $sql->adInserir('arquivo_pasta_gestao_calendario', $linha['arquivo_pasta_calendario']);
		$sql->exec();
		$sql->limpar();
		}	
			
	}
?>