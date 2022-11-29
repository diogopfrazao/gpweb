<?php
global $config, $bd;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
	$sql->adTabela('tema');	
	$sql->adCampo('tema_id, tema_superior');
	$sql->adOnde('tema_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('tema_gestao');	
		$sql->adCampo('count(tema_gestao_id)');
		$sql->adOnde('tema_gestao_tema = '.(int)$linha['tema_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('tema_gestao');
		$sql->adInserir('tema_gestao_tema', $linha['tema_id']);
		$sql->adInserir('tema_gestao_semelhante', $linha['tema_superior']);
		$sql->adInserir('tema_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}	
		
		
	$sql->adTabela('demandas');	
	$sql->adCampo('demanda_id, demanda_superior');
	$sql->adOnde('demanda_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('demanda_gestao');	
		$sql->adCampo('count(demanda_gestao_id)');
		$sql->adOnde('demanda_gestao_demanda = '.(int)$linha['demanda_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('demanda_gestao');
		$sql->adInserir('demanda_gestao_demanda', $linha['demanda_id']);
		$sql->adInserir('demanda_gestao_semelhante', $linha['demanda_superior']);
		$sql->adInserir('demanda_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}		
		
		
	$sql->adTabela('perspectivas');	
	$sql->adCampo('pg_perspectiva_id, pg_perspectiva_superior');
	$sql->adOnde('pg_perspectiva_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('perspectiva_gestao');	
		$sql->adCampo('count(perspectiva_gestao_id)');
		$sql->adOnde('perspectiva_gestao_perspectiva = '.(int)$linha['pg_perspectiva_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('perspectiva_gestao');
		$sql->adInserir('perspectiva_gestao_perspectiva', $linha['pg_perspectiva_id']);
		$sql->adInserir('perspectiva_gestao_semelhante', $linha['pg_perspectiva_superior']);
		$sql->adInserir('perspectiva_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}			
		
		
	$sql->adTabela('objetivo');	
	$sql->adCampo('objetivo_id, objetivo_superior');
	$sql->adOnde('objetivo_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('objetivo_gestao');	
		$sql->adCampo('count(objetivo_gestao_id)');
		$sql->adOnde('objetivo_gestao_objetivo = '.(int)$linha['objetivo_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('objetivo_gestao');
		$sql->adInserir('objetivo_gestao_objetivo', $linha['objetivo_id']);
		$sql->adInserir('objetivo_gestao_semelhante', $linha['objetivo_superior']);
		$sql->adInserir('objetivo_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}		
		
	$sql->adTabela('fator');	
	$sql->adCampo('fator_id, fator_superior');
	$sql->adOnde('fator_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('fator_gestao');	
		$sql->adCampo('count(fator_gestao_id)');
		$sql->adOnde('fator_gestao_fator = '.(int)$linha['fator_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('fator_gestao');
		$sql->adInserir('fator_gestao_fator', $linha['fator_id']);
		$sql->adInserir('fator_gestao_semelhante', $linha['fator_superior']);
		$sql->adInserir('fator_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}			
		
		
	$sql->adTabela('estrategias');	
	$sql->adCampo('pg_estrategia_id, pg_estrategia_superior');
	$sql->adOnde('pg_estrategia_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('estrategia_gestao');	
		$sql->adCampo('count(estrategia_gestao_id)');
		$sql->adOnde('estrategia_gestao_estrategia = '.(int)$linha['pg_estrategia_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('estrategia_gestao');
		$sql->adInserir('estrategia_gestao_estrategia', $linha['pg_estrategia_id']);
		$sql->adInserir('estrategia_gestao_semelhante', $linha['pg_estrategia_superior']);
		$sql->adInserir('estrategia_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}				
		
		
	$sql->adTabela('metas');	
	$sql->adCampo('pg_meta_id, pg_meta_superior');
	$sql->adOnde('pg_meta_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('meta_gestao');	
		$sql->adCampo('count(meta_gestao_id)');
		$sql->adOnde('meta_gestao_meta = '.(int)$linha['pg_meta_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('meta_gestao');
		$sql->adInserir('meta_gestao_meta', $linha['pg_meta_id']);
		$sql->adInserir('meta_gestao_semelhante', $linha['pg_meta_superior']);
		$sql->adInserir('meta_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}					
		
	
	$sql->adTabela('praticas');	
	$sql->adCampo('pratica_id, pratica_superior');
	$sql->adOnde('pratica_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('pratica_gestao');	
		$sql->adCampo('count(pratica_gestao_id)');
		$sql->adOnde('pratica_gestao_pratica = '.(int)$linha['pratica_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('pratica_gestao');
		$sql->adInserir('pratica_gestao_pratica', $linha['pratica_id']);
		$sql->adInserir('pratica_gestao_semelhante', $linha['pratica_superior']);
		$sql->adInserir('pratica_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}				
		
		
	$sql->adTabela('arquivo');	
	$sql->adCampo('arquivo_id, arquivo_superior');
	$sql->adOnde('arquivo_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('arquivo_gestao');	
		$sql->adCampo('count(arquivo_gestao_id)');
		$sql->adOnde('arquivo_gestao_arquivo = '.(int)$linha['arquivo_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('arquivo_gestao');
		$sql->adInserir('arquivo_gestao_arquivo', $linha['arquivo_id']);
		$sql->adInserir('arquivo_gestao_semelhante', $linha['arquivo_superior']);
		$sql->adInserir('arquivo_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}				
		
	
	$sql->adTabela('eventos');	
	$sql->adCampo('evento_id, evento_superior');
	$sql->adOnde('evento_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('evento_gestao');	
		$sql->adCampo('count(evento_gestao_id)');
		$sql->adOnde('evento_gestao_evento = '.(int)$linha['evento_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('evento_gestao');
		$sql->adInserir('evento_gestao_evento', $linha['evento_id']);
		$sql->adInserir('evento_gestao_semelhante', $linha['evento_superior']);
		$sql->adInserir('evento_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}					
		
		
	$sql->adTabela('links');	
	$sql->adCampo('link_id, link_superior');
	$sql->adOnde('link_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('link_gestao');	
		$sql->adCampo('count(link_gestao_id)');
		$sql->adOnde('link_gestao_link = '.(int)$linha['link_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('link_gestao');
		$sql->adInserir('link_gestao_link', $linha['link_id']);
		$sql->adInserir('link_gestao_semelhante', $linha['link_superior']);
		$sql->adInserir('link_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}						
		
	$sql->adTabela('me');	
	$sql->adCampo('me_id, me_superior');
	$sql->adOnde('me_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('me_gestao');	
		$sql->adCampo('count(me_gestao_id)');
		$sql->adOnde('me_gestao_me = '.(int)$linha['me_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('me_gestao');
		$sql->adInserir('me_gestao_me', $linha['me_id']);
		$sql->adInserir('me_gestao_semelhante', $linha['me_superior']);
		$sql->adInserir('me_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}				
		
	$sql->adTabela('beneficio');	
	$sql->adCampo('beneficio_id, beneficio_superior');
	$sql->adOnde('beneficio_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('beneficio_gestao');	
		$sql->adCampo('count(beneficio_gestao_id)');
		$sql->adOnde('beneficio_gestao_beneficio = '.(int)$linha['beneficio_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('beneficio_gestao');
		$sql->adInserir('beneficio_gestao_beneficio', $linha['beneficio_id']);
		$sql->adInserir('beneficio_gestao_semelhante', $linha['beneficio_superior']);
		$sql->adInserir('beneficio_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}					
		
	$sql->adTabela('programa');	
	$sql->adCampo('programa_id, programa_superior');
	$sql->adOnde('programa_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('programa_gestao');	
		$sql->adCampo('count(programa_gestao_id)');
		$sql->adOnde('programa_gestao_programa = '.(int)$linha['programa_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('programa_gestao');
		$sql->adInserir('programa_gestao_programa', $linha['programa_id']);
		$sql->adInserir('programa_gestao_semelhante', $linha['programa_superior']);
		$sql->adInserir('programa_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}					
			
	$sql->adTabela('tgn');	
	$sql->adCampo('tgn_id, tgn_superior');
	$sql->adOnde('tgn_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('tgn_gestao');	
		$sql->adCampo('count(tgn_gestao_id)');
		$sql->adOnde('tgn_gestao_tgn = '.(int)$linha['tgn_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('tgn_gestao');
		$sql->adInserir('tgn_gestao_tgn', $linha['tgn_id']);
		$sql->adInserir('tgn_gestao_semelhante', $linha['tgn_superior']);
		$sql->adInserir('tgn_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}		
		
	$sql->adTabela('risco');	
	$sql->adCampo('risco_id, risco_superior');
	$sql->adOnde('risco_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('risco_gestao');	
		$sql->adCampo('count(risco_gestao_id)');
		$sql->adOnde('risco_gestao_risco = '.(int)$linha['risco_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('risco_gestao');
		$sql->adInserir('risco_gestao_risco', $linha['risco_id']);
		$sql->adInserir('risco_gestao_semelhante', $linha['risco_superior']);
		$sql->adInserir('risco_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}		
		
	$sql->adTabela('risco_resposta');	
	$sql->adCampo('risco_resposta_id, risco_resposta_superior');
	$sql->adOnde('risco_resposta_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('risco_resposta_gestao');	
		$sql->adCampo('count(risco_resposta_gestao_id)');
		$sql->adOnde('risco_resposta_gestao_risco_resposta = '.(int)$linha['risco_resposta_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('risco_resposta_gestao');
		$sql->adInserir('risco_resposta_gestao_risco_resposta', $linha['risco_resposta_id']);
		$sql->adInserir('risco_resposta_gestao_semelhante', $linha['risco_resposta_superior']);
		$sql->adInserir('risco_resposta_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}		
		
	$sql->adTabela('canvas');	
	$sql->adCampo('canvas_id, canvas_superior');
	$sql->adOnde('canvas_superior > 0');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('canvas_gestao');	
		$sql->adCampo('count(canvas_gestao_id)');
		$sql->adOnde('canvas_gestao_canvas = '.(int)$linha['canvas_id']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('canvas_gestao');
		$sql->adInserir('canvas_gestao_canvas', $linha['canvas_id']);
		$sql->adInserir('canvas_gestao_semelhante', $linha['canvas_superior']);
		$sql->adInserir('canvas_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}					
		
		
	$sql->adTabela('objetivo_composicao');	
	$sql->adCampo('objetivo_composicao_pai, objetivo_composicao_filho');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('objetivo_gestao');	
		$sql->adCampo('count(objetivo_gestao_id)');
		$sql->adOnde('objetivo_gestao_objetivo = '.(int)$linha['objetivo_composicao_filho']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('objetivo_gestao');
		$sql->adInserir('objetivo_gestao_objetivo', $linha['objetivo_composicao_filho']);
		$sql->adInserir('objetivo_gestao_semelhante', $linha['objetivo_composicao_pai']);
		$sql->adInserir('objetivo_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}		
	
	$sql->adTabela('me_composicao');	
	$sql->adCampo('me_composicao_pai, me_composicao_filho');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('me_gestao');	
		$sql->adCampo('count(me_gestao_id)');
		$sql->adOnde('me_gestao_me = '.(int)$linha['me_composicao_filho']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('me_gestao');
		$sql->adInserir('me_gestao_me', $linha['me_composicao_filho']);
		$sql->adInserir('me_gestao_semelhante', $linha['me_composicao_pai']);
		$sql->adInserir('me_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}		
		
	$sql->adTabela('estrategias_composicao');	
	$sql->adCampo('estrategia_pai, estrategia_filho');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('estrategia_gestao');	
		$sql->adCampo('count(estrategia_gestao_id)');
		$sql->adOnde('estrategia_gestao_estrategia = '.(int)$linha['estrategia_filho']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('estrategia_gestao');
		$sql->adInserir('estrategia_gestao_estrategia', $linha['estrategia_filho']);
		$sql->adInserir('estrategia_gestao_semelhante', $linha['estrategia_pai']);
		$sql->adInserir('estrategia_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}			
		
	$sql->adTabela('pratica_composicao');	
	$sql->adCampo('pc_pratica_pai, pc_pratica_filho');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('pratica_gestao');	
		$sql->adCampo('count(pratica_gestao_id)');
		$sql->adOnde('pratica_gestao_pratica = '.(int)$linha['pc_pratica_filho']);
		$qnt = $sql->Resultado();
		$sql->limpar();
		$sql->adTabela('pratica_gestao');
		$sql->adInserir('pratica_gestao_pratica', $linha['pc_pratica_filho']);
		$sql->adInserir('pratica_gestao_semelhante', $linha['pc_pratica_pai']);
		$sql->adInserir('pratica_gestao_ordem', $qnt+1);
		$sql->exec();
		$sql->limpar();
		}		
			
	}
?>