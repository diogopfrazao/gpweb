<?php 
/*
Copyright [2015] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número BR 51 2015 000171 0 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once (BASE_DIR.'/modulos/praticas/brainstorm.class.php');

$sql = new BDConsulta;
$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$aprovar = intval(getParam($_REQUEST, 'aprovar', 0));


$brainstorm_id=getParam($_REQUEST, 'brainstorm_id', null);
$_REQUEST['brainstorm_ativo']=(isset($_REQUEST['brainstorm_ativo']) ? 1 : 0);


$Aplic->setMsg('Brainstorm');
$obj = new CBrainstorm();

if ($excluir) {
	$obj->load($brainstorm_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);

		$Aplic->redirecionar('m=praticas&a=brainstorm_lista');
		} 
	else {
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=praticas&a=brainstorm_lista');

		} 
	}

if ($brainstorm_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=praticas&a=brainstorm_lista');
	}



if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($brainstorm_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}

if ($dialogo){
	echo '<script type="text/javascript">';
	echo 'if(window.parent && window.parent.gpwebApp && window.parent.gpwebApp._popupCallback) window.parent.gpwebApp._popupCallback(true);';
	echo 'else self.close();';
	echo '</script>';	
	} 
else {
	
if ($Aplic->profissional){
	$sql->adTabela('brainstorm_gestao');
	$sql->adCampo('brainstorm_gestao.*');
	$sql->adOnde('brainstorm_gestao_brainstorm='.(int)$obj->brainstorm_id);
	$sql->adOrdem('brainstorm_gestao_ordem ASC');
	$linha=$sql->linha();
	$sql->limpar();
	
	$sql->adTabela('brainstorm_gestao');
	$sql->adCampo('count(brainstorm_gestao_id)');
	$sql->adOnde('brainstorm_gestao_brainstorm='.(int)$obj->brainstorm_id);
	$qnt=$sql->Resultado();
	$sql->limpar();
	
	if ($linha!=null && $linha['brainstorm_gestao_tarefa'] && $qnt==1 && !$brainstorm_id) $endereco='m=tarefas&a=ver&tarefa_id='.$linha['brainstorm_gestao_tarefa'];
	elseif ($linha!=null && $linha['brainstorm_gestao_projeto'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=ver&projeto_id='.$linha['brainstorm_gestao_projeto'];
	elseif ($linha!=null && $linha['brainstorm_gestao_perspectiva'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['brainstorm_gestao_perspectiva'];
	elseif ($linha!=null && $linha['brainstorm_gestao_tema'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=tema_ver&tema_id='.$linha['brainstorm_gestao_tema'];
	elseif ($linha!=null && $linha['brainstorm_gestao_objetivo'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['brainstorm_gestao_objetivo'];
	elseif ($linha!=null && $linha['brainstorm_gestao_fator'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=fator_ver&fator_id='.$linha['brainstorm_gestao_fator'];
	elseif ($linha!=null && $linha['brainstorm_gestao_estrategia'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['brainstorm_gestao_estrategia'];
	elseif ($linha!=null && $linha['brainstorm_gestao_meta'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=meta_ver&pg_meta_id='.$linha['brainstorm_gestao_meta'];
	elseif ($linha!=null && $linha['brainstorm_gestao_pratica'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=pratica_ver&pratica_id='.$linha['brainstorm_gestao_pratica'];
	elseif ($linha!=null && $linha['brainstorm_gestao_indicador'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['brainstorm_gestao_indicador'];
	elseif ($linha!=null && $linha['brainstorm_gestao_acao'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['brainstorm_gestao_acao'];
	elseif ($linha!=null && $linha['brainstorm_gestao_canvas'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=canvas_pro_ver&canvas_id='.$linha['brainstorm_gestao_canvas'];
	elseif ($linha!=null && $linha['brainstorm_gestao_risco'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=risco_pro_ver&risco_id='.$linha['brainstorm_gestao_risco'];
	elseif ($linha!=null && $linha['brainstorm_gestao_risco_resposta'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$linha['brainstorm_gestao_risco_resposta'];
	elseif ($linha!=null && $linha['brainstorm_gestao_calendario'] && $qnt==1 && !$brainstorm_id) $endereco='m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['brainstorm_gestao_calendario'];
	elseif ($linha!=null && $linha['brainstorm_gestao_monitoramento'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['brainstorm_gestao_monitoramento'];
	elseif ($linha!=null && $linha['brainstorm_gestao_ata'] && $qnt==1 && !$brainstorm_id) $endereco='m=atas&a=ata_ver&ata_id='.$linha['brainstorm_gestao_ata'];
	elseif ($linha!=null && $linha['brainstorm_gestao_mswot'] && $qnt==1 && !$brainstorm_id) $endereco='m=swot&a=mswot_ver&mswot_id='.$linha['brainstorm_gestao_mswot'];
	elseif ($linha!=null && $linha['brainstorm_gestao_swot'] && $qnt==1 && !$brainstorm_id) $endereco='m=swot&a=swot_ver&swot_id='.$linha['brainstorm_gestao_swot'];
	elseif ($linha!=null && $linha['brainstorm_gestao_operativo'] && $qnt==1 && !$brainstorm_id) $endereco='m=operativo&a=operativo_ver&operativo_id='.$linha['brainstorm_gestao_operativo'];
	elseif ($linha!=null && $linha['brainstorm_gestao_instrumento'] && $qnt==1 && !$brainstorm_id) $endereco='m=instrumento&a=instrumento_ver&instrumento_id='.$linha['brainstorm_gestao_instrumento'];
	elseif ($linha!=null && $linha['brainstorm_gestao_recurso'] && $qnt==1 && !$brainstorm_id) $endereco='m=recursos&a=ver&recurso_id='.$linha['brainstorm_gestao_recurso'];
	elseif ($linha!=null && $linha['brainstorm_gestao_problema'] && $qnt==1 && !$brainstorm_id) $endereco='m=problema&a=problema_ver&problema_id='.$linha['brainstorm_gestao_problema'];
	elseif ($linha!=null && $linha['brainstorm_gestao_demanda'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=demanda_ver&demanda_id='.$linha['brainstorm_gestao_demanda'];
	elseif ($linha!=null && $linha['brainstorm_gestao_programa'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=programa_pro_ver&programa_id='.$linha['brainstorm_gestao_programa'];
	elseif ($linha!=null && $linha['brainstorm_gestao_licao'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=licao_ver&licao_id='.$linha['brainstorm_gestao_licao'];
	elseif ($linha!=null && $linha['brainstorm_gestao_evento'] && $qnt==1 && !$brainstorm_id) $endereco='m=calendario&a=ver&evento_id='.$linha['brainstorm_gestao_evento'];
	elseif ($linha!=null && $linha['brainstorm_gestao_link'] && $qnt==1 && !$brainstorm_id) $endereco='m=links&a=ver&link_id='.$linha['brainstorm_gestao_link'];
	elseif ($linha!=null && $linha['brainstorm_gestao_avaliacao'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['brainstorm_gestao_avaliacao'];
	elseif ($linha!=null && $linha['brainstorm_gestao_tgn'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=tgn_pro_ver&tgn_id='.$linha['brainstorm_gestao_tgn'];
	
	elseif ($linha!=null && $linha['brainstorm_gestao_semelhante'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['brainstorm_gestao_semelhante'];
	
	elseif ($linha!=null && $linha['brainstorm_gestao_gut'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=gut_ver&gut_id='.$linha['brainstorm_gestao_gut'];
	elseif ($linha!=null && $linha['brainstorm_gestao_causa_efeito'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['brainstorm_gestao_causa_efeito'];
	elseif ($linha!=null && $linha['brainstorm_gestao_arquivo'] && $qnt==1 && !$brainstorm_id) $endereco='m=arquivos&a=ver&arquivo_id='.$linha['brainstorm_gestao_arquivo'];
	elseif ($linha!=null && $linha['brainstorm_gestao_forum'] && $qnt==1 && !$brainstorm_id) $endereco='m=foruns&a=ver&forum_id='.$linha['brainstorm_gestao_forum'];
	elseif ($linha!=null && $linha['brainstorm_gestao_checklist'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=checklist_ver&checklist_id='.$linha['brainstorm_gestao_checklist'];
	elseif ($linha!=null && $linha['brainstorm_gestao_agenda'] && $qnt==1 && !$brainstorm_id) $endereco='m=email&a=ver_compromisso&agenda_id='.$linha['brainstorm_gestao_agenda'];
	elseif ($linha!=null && $linha['brainstorm_gestao_agrupamento'] && $qnt==1 && !$brainstorm_id) $endereco='m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['brainstorm_gestao_agrupamento'];
	elseif ($linha!=null && $linha['brainstorm_gestao_patrocinador'] && $qnt==1 && !$brainstorm_id) $endereco='m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['brainstorm_gestao_patrocinador'];
	elseif ($linha!=null && $linha['brainstorm_gestao_template'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=template_pro_ver&template_id='.$linha['brainstorm_gestao_template'];
	elseif ($linha!=null && $linha['brainstorm_gestao_painel'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=painel_pro_ver&painel_id='.$linha['brainstorm_gestao_painel'];
	elseif ($linha!=null && $linha['brainstorm_gestao_painel_odometro'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['brainstorm_gestao_painel_odometro'];
	elseif ($linha!=null && $linha['brainstorm_gestao_painel_composicao'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['brainstorm_gestao_painel_composicao'];
	elseif ($linha!=null && $linha['brainstorm_gestao_tr'] && $qnt==1 && !$brainstorm_id) $endereco='m=tr&a=tr_ver&tr_id='.$linha['brainstorm_gestao_tr'];
	elseif ($linha!=null && $linha['brainstorm_gestao_me'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=me_ver_pro&me_id='.$linha['brainstorm_gestao_me'];
	elseif ($linha!=null && $linha['brainstorm_gestao_acao_item'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['brainstorm_gestao_acao_item'];
	elseif ($linha!=null && $linha['brainstorm_gestao_beneficio'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['brainstorm_gestao_beneficio'];
	elseif ($linha!=null && $linha['brainstorm_gestao_painel_slideshow'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['brainstorm_gestao_painel_slideshow'];
	elseif ($linha!=null && $linha['brainstorm_gestao_projeto_viabilidade'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['brainstorm_gestao_projeto_viabilidade'];
	elseif ($linha!=null && $linha['brainstorm_gestao_projeto_abertura'] && $qnt==1 && !$brainstorm_id) $endereco='m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['brainstorm_gestao_projeto_abertura'];
	elseif ($linha!=null && $linha['brainstorm_gestao_plano_gestao'] && $qnt==1 && !$brainstorm_id) $endereco='m=praticas&a=menu&u=gestao&pg_id='.$linha['brainstorm_gestao_plano_gestao'];
	elseif ($linha!=null && $linha['brainstorm_gestao_ssti'] && $qnt==1 && !$brainstorm_id) $endereco='m=ssti&a=ssti_ver&ssti_id='.$linha['brainstorm_gestao_ssti'];
	elseif ($linha!=null && $linha['brainstorm_gestao_laudo'] && $qnt==1 && !$brainstorm_id) $endereco='m=ssti&a=laudo_ver&laudo_id='.$linha['brainstorm_gestao_laudo'];
	elseif ($linha!=null && $linha['brainstorm_gestao_trelo'] && $qnt==1 && !$brainstorm_id) $endereco='m=trelo&a=trelo_ver&trelo_id='.$linha['brainstorm_gestao_trelo'];
	elseif ($linha!=null && $linha['brainstorm_gestao_trelo_cartao'] && $qnt==1 && !$brainstorm_id) $endereco='m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['brainstorm_gestao_trelo_cartao'];
	elseif ($linha!=null && $linha['brainstorm_gestao_pdcl'] && $qnt==1 && !$brainstorm_id) $endereco='m=pdcl&a=pdcl_ver&pdcl_id='.$linha['brainstorm_gestao_pdcl'];
	elseif ($linha!=null && $linha['brainstorm_gestao_pdcl_item'] && $qnt==1 && !$brainstorm_id) $endereco='m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['brainstorm_gestao_pdcl_item'];
	elseif ($linha!=null && $linha['brainstorm_gestao_os'] && $qnt==1 && !$brainstorm_id) $endereco='m=os&a=os_ver&os_id='.$linha['brainstorm_gestao_os'];

	else $endereco='m=praticas&a=brainstorm_ver&brainstorm_id='.(int)$obj->brainstorm_id;
	$Aplic->redirecionar($endereco);
	}	 
else $Aplic->redirecionar('m=praticas&a=brainstorm_ver&brainstorm_id='.(int)$obj->brainstorm_id);	

	
	
}	

















$Aplic->redirecionar('m=praticas&a=brainstorm_ver&brainstorm_id='.$obj->brainstorm_id);

?>