<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');


include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);


if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/patrocinadores/patrocinador_editar_ajax_pro.php');

function exibir_cias($cias){
	global $config;
	$cias_selecionadas=explode(',', $cias);
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0>';
			$saida_cias.= '<tr><td class="texto" style="width:400px;">'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';		
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			} 
	else 	$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_cias',"innerHTML", utf8_encode($saida_cias));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_cias");	
	


function mudar_posicao_gestao($ordem, $patrocinador_gestao_id, $direcao, $patrocinador_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $patrocinador_gestao_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('patrocinador_gestao');
		$sql->adOnde('patrocinador_gestao_id != '.(int)$patrocinador_gestao_id);
		if ($uuid) $sql->adOnde('patrocinador_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('patrocinador_gestao_patrocinador = '.(int)$patrocinador_id);
		$sql->adOrdem('patrocinador_gestao_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('patrocinador_gestao');
			$sql->adAtualizar('patrocinador_gestao_ordem', $novo_ui_ordem);
			$sql->adOnde('patrocinador_gestao_id = '.(int)$patrocinador_gestao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('patrocinador_gestao');
					$sql->adAtualizar('patrocinador_gestao_ordem', $idx);
					$sql->adOnde('patrocinador_gestao_id = '.(int)$acao['patrocinador_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('patrocinador_gestao');
					$sql->adAtualizar('patrocinador_gestao_ordem', $idx + 1);
					$sql->adOnde('patrocinador_gestao_id = '.(int)$acao['patrocinador_gestao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_gestao($patrocinador_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_gestao");


function incluir_relacionado(
	$patrocinador_id=0, 
	$uuid='',  
	
	$patrocinador_projeto=null,
	$patrocinador_tarefa=null,
	$patrocinador_perspectiva=null,
	$patrocinador_tema=null,
	$patrocinador_objetivo=null,
	$patrocinador_fator=null,
	$patrocinador_estrategia=null,
	$patrocinador_meta=null,
	$patrocinador_pratica=null,
	$patrocinador_acao=null,
	$patrocinador_canvas=null,
	$patrocinador_risco=null,
	$patrocinador_risco_resposta=null,
	$patrocinador_indicador=null,
	$patrocinador_calendario=null,
	$patrocinador_monitoramento=null,
	$patrocinador_ata=null,
	$patrocinador_mswot=null,
	$patrocinador_swot=null,
	$patrocinador_operativo=null,
	$patrocinador_instrumento=null,
	$patrocinador_recurso=null,
	$patrocinador_problema=null,
	$patrocinador_demanda=null,
	$patrocinador_programa=null,
	$patrocinador_licao=null,
	$patrocinador_evento=null,
	$patrocinador_link=null,
	$patrocinador_avaliacao=null,
	$patrocinador_tgn=null,
	$patrocinador_brainstorm=null,
	$patrocinador_gut=null,
	$patrocinador_causa_efeito=null,
	$patrocinador_arquivo=null,
	$patrocinador_forum=null,
	$patrocinador_checklist=null,
	$patrocinador_agenda=null,
	$patrocinador_agrupamento=null,
	$patrocinador_patrocinador=null,
	$patrocinador_template=null,
	$patrocinador_painel=null,
	$patrocinador_painel_odometro=null,
	$patrocinador_painel_composicao=null,
	$patrocinador_tr=null,
	$patrocinador_me=null,
	$patrocinador_acao_item=null,
	$patrocinador_beneficio=null,
	$patrocinador_painel_slideshow=null,
	$patrocinador_projeto_viabilidade=null,
	$patrocinador_projeto_abertura=null,
	$patrocinador_plano_gestao=null,
	$patrocinador_ssti=null,
	$patrocinador_laudo=null,
	$patrocinador_trelo=null,
	$patrocinador_trelo_cartao=null,
	$patrocinador_pdcl=null,
	$patrocinador_pdcl_item=null,
	$patrocinador_os=null 
	)
	{
	if (
		$patrocinador_projeto || 
		$patrocinador_tarefa || 
		$patrocinador_perspectiva || 
		$patrocinador_tema || 
		$patrocinador_objetivo || 
		$patrocinador_fator || 
		$patrocinador_estrategia || 
		$patrocinador_meta || 
		$patrocinador_pratica || 
		$patrocinador_acao || 
		$patrocinador_canvas || 
		$patrocinador_risco || 
		$patrocinador_risco_resposta || 
		$patrocinador_indicador || 
		$patrocinador_calendario || 
		$patrocinador_monitoramento || 
		$patrocinador_ata || 
		$patrocinador_mswot || 
		$patrocinador_swot || 
		$patrocinador_operativo || 
		$patrocinador_instrumento || 
		$patrocinador_recurso || 
		$patrocinador_problema || 
		$patrocinador_demanda || 
		$patrocinador_programa || 
		$patrocinador_licao || 
		$patrocinador_evento || 
		$patrocinador_link || 
		$patrocinador_avaliacao || 
		$patrocinador_tgn || 
		$patrocinador_brainstorm || 
		$patrocinador_gut || 
		$patrocinador_causa_efeito || 
		$patrocinador_arquivo || 
		$patrocinador_forum || 
		$patrocinador_checklist || 
		$patrocinador_agenda || 
		$patrocinador_agrupamento || 
		$patrocinador_patrocinador || 
		$patrocinador_template || 
		$patrocinador_painel || 
		$patrocinador_painel_odometro || 
		$patrocinador_painel_composicao || 
		$patrocinador_tr || 
		$patrocinador_me || 
		$patrocinador_acao_item || 
		$patrocinador_beneficio || 
		$patrocinador_painel_slideshow || 
		$patrocinador_projeto_viabilidade || 
		$patrocinador_projeto_abertura || 
		$patrocinador_plano_gestao|| 
		$patrocinador_ssti || 
		$patrocinador_laudo || 
		$patrocinador_trelo || 
		$patrocinador_trelo_cartao || 
		$patrocinador_pdcl || 
		$patrocinador_pdcl_item || 
		$patrocinador_os
		){
		global $Aplic;
		
		$sql = new BDConsulta;
		if (!$Aplic->profissional) {
			$sql->setExcluir('patrocinador_gestao');
			if ($uuid) $sql->adOnde('patrocinador_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('patrocinador_gestao_patrocinador ='.(int)$patrocinador_id);	
			$sql->exec();
			}
		
		//verificar se j? n?o inseriu antes
		$sql->adTabela('patrocinador_gestao');
		$sql->adCampo('count(patrocinador_gestao_id)');
		if ($uuid) $sql->adOnde('patrocinador_gestao_uuid = \''.$uuid.'\'');
		else $sql->adOnde('patrocinador_gestao_patrocinador ='.(int)$patrocinador_id);	
		if ($patrocinador_tarefa) $sql->adOnde('patrocinador_gestao_tarefa='.(int)$patrocinador_tarefa);
		elseif ($patrocinador_projeto) $sql->adOnde('patrocinador_gestao_projeto='.(int)$patrocinador_projeto);
		elseif ($patrocinador_perspectiva) $sql->adOnde('patrocinador_gestao_perspectiva='.(int)$patrocinador_perspectiva);
		elseif ($patrocinador_tema) $sql->adOnde('patrocinador_gestao_tema='.(int)$patrocinador_tema);
		elseif ($patrocinador_objetivo) $sql->adOnde('patrocinador_gestao_objetivo='.(int)$patrocinador_objetivo);
		elseif ($patrocinador_fator) $sql->adOnde('patrocinador_gestao_fator='.(int)$patrocinador_fator);
		elseif ($patrocinador_estrategia) $sql->adOnde('patrocinador_gestao_estrategia='.(int)$patrocinador_estrategia);
		elseif ($patrocinador_acao) $sql->adOnde('patrocinador_gestao_acao='.(int)$patrocinador_acao);
		elseif ($patrocinador_pratica) $sql->adOnde('patrocinador_gestao_pratica='.(int)$patrocinador_pratica);
		elseif ($patrocinador_meta) $sql->adOnde('patrocinador_gestao_meta='.(int)$patrocinador_meta);
		elseif ($patrocinador_canvas) $sql->adOnde('patrocinador_gestao_canvas='.(int)$patrocinador_canvas);
		elseif ($patrocinador_risco) $sql->adOnde('patrocinador_gestao_risco='.(int)$patrocinador_risco);
		elseif ($patrocinador_risco_resposta) $sql->adOnde('patrocinador_gestao_risco_resposta='.(int)$patrocinador_risco_resposta);
		elseif ($patrocinador_indicador) $sql->adOnde('patrocinador_gestao_indicador='.(int)$patrocinador_indicador);
		elseif ($patrocinador_calendario) $sql->adOnde('patrocinador_gestao_calendario='.(int)$patrocinador_calendario);
		elseif ($patrocinador_monitoramento) $sql->adOnde('patrocinador_gestao_monitoramento='.(int)$patrocinador_monitoramento);
		elseif ($patrocinador_ata) $sql->adOnde('patrocinador_gestao_ata='.(int)$patrocinador_ata);
		elseif ($patrocinador_mswot) $sql->adOnde('patrocinador_gestao_mswot='.(int)$patrocinador_mswot);
		elseif ($patrocinador_swot) $sql->adOnde('patrocinador_gestao_swot='.(int)$patrocinador_swot);
		elseif ($patrocinador_operativo) $sql->adOnde('patrocinador_gestao_operativo='.(int)$patrocinador_operativo);
		elseif ($patrocinador_instrumento) $sql->adOnde('patrocinador_gestao_instrumento='.(int)$patrocinador_instrumento);
		elseif ($patrocinador_recurso) $sql->adOnde('patrocinador_gestao_recurso='.(int)$patrocinador_recurso);
		elseif ($patrocinador_problema) $sql->adOnde('patrocinador_gestao_problema='.(int)$patrocinador_problema);
		elseif ($patrocinador_demanda) $sql->adOnde('patrocinador_gestao_demanda='.(int)$patrocinador_demanda);
		elseif ($patrocinador_programa) $sql->adOnde('patrocinador_gestao_programa='.(int)$patrocinador_programa);
		elseif ($patrocinador_licao) $sql->adOnde('patrocinador_gestao_licao='.(int)$patrocinador_licao);
		elseif ($patrocinador_evento) $sql->adOnde('patrocinador_gestao_evento='.(int)$patrocinador_evento);
		elseif ($patrocinador_link) $sql->adOnde('patrocinador_gestao_link='.(int)$patrocinador_link);
		elseif ($patrocinador_avaliacao) $sql->adOnde('patrocinador_gestao_avaliacao='.(int)$patrocinador_avaliacao);
		elseif ($patrocinador_tgn) $sql->adOnde('patrocinador_gestao_tgn='.(int)$patrocinador_tgn);
		elseif ($patrocinador_brainstorm) $sql->adOnde('patrocinador_gestao_brainstorm='.(int)$patrocinador_brainstorm);
		elseif ($patrocinador_gut) $sql->adOnde('patrocinador_gestao_gut='.(int)$patrocinador_gut);
		elseif ($patrocinador_causa_efeito) $sql->adOnde('patrocinador_gestao_causa_efeito='.(int)$patrocinador_causa_efeito);
		elseif ($patrocinador_arquivo) $sql->adOnde('patrocinador_gestao_arquivo='.(int)$patrocinador_arquivo);
		elseif ($patrocinador_forum) $sql->adOnde('patrocinador_gestao_forum='.(int)$patrocinador_forum);
		elseif ($patrocinador_checklist) $sql->adOnde('patrocinador_gestao_checklist='.(int)$patrocinador_checklist);
		elseif ($patrocinador_agenda) $sql->adOnde('patrocinador_gestao_agenda='.(int)$patrocinador_agenda);
		elseif ($patrocinador_agrupamento) $sql->adOnde('patrocinador_gestao_agrupamento='.(int)$patrocinador_agrupamento);
		
		elseif ($patrocinador_patrocinador) $sql->adOnde('patrocinador_gestao_semelhante='.(int)$patrocinador_patrocinador);
		
		elseif ($patrocinador_template) $sql->adOnde('patrocinador_gestao_template='.(int)$patrocinador_template);
		elseif ($patrocinador_painel) $sql->adOnde('patrocinador_gestao_painel='.(int)$patrocinador_painel);
		elseif ($patrocinador_painel_odometro) $sql->adOnde('patrocinador_gestao_painel_odometro='.(int)$patrocinador_painel_odometro);
		elseif ($patrocinador_painel_composicao) $sql->adOnde('patrocinador_gestao_painel_composicao='.(int)$patrocinador_painel_composicao);
		elseif ($patrocinador_tr) $sql->adOnde('patrocinador_gestao_tr='.(int)$patrocinador_tr);
		elseif ($patrocinador_me) $sql->adOnde('patrocinador_gestao_me='.(int)$patrocinador_me);
		elseif ($patrocinador_acao_item) $sql->adOnde('patrocinador_gestao_acao_item='.(int)$patrocinador_acao_item);
		elseif ($patrocinador_beneficio) $sql->adOnde('patrocinador_gestao_beneficio='.(int)$patrocinador_beneficio);
		elseif ($patrocinador_painel_slideshow) $sql->adOnde('patrocinador_gestao_painel_slideshow='.(int)$patrocinador_painel_slideshow);
		elseif ($patrocinador_projeto_viabilidade) $sql->adOnde('patrocinador_gestao_projeto_viabilidade='.(int)$patrocinador_projeto_viabilidade);
		elseif ($patrocinador_projeto_abertura) $sql->adOnde('patrocinador_gestao_projeto_abertura='.(int)$patrocinador_projeto_abertura);
		elseif ($patrocinador_plano_gestao) $sql->adOnde('patrocinador_gestao_plano_gestao='.(int)$patrocinador_plano_gestao);
		elseif ($patrocinador_ssti) $sql->adOnde('patrocinador_gestao_ssti='.(int)$patrocinador_ssti);
		elseif ($patrocinador_laudo) $sql->adOnde('patrocinador_gestao_laudo='.(int)$patrocinador_laudo);
		elseif ($patrocinador_trelo) $sql->adOnde('patrocinador_gestao_trelo='.(int)$patrocinador_trelo);
		elseif ($patrocinador_trelo_cartao) $sql->adOnde('patrocinador_gestao_trelo_cartao='.(int)$patrocinador_trelo_cartao);
		elseif ($patrocinador_pdcl) $sql->adOnde('patrocinador_gestao_pdcl='.(int)$patrocinador_pdcl);
		elseif ($patrocinador_pdcl_item) $sql->adOnde('patrocinador_gestao_pdcl_item='.(int)$patrocinador_pdcl_item);
	  elseif ($patrocinador_os) $sql->adOnde('patrocinador_gestao_os='.(int)$patrocinador_os);
	  $existe = $sql->Resultado();
	  $sql->limpar();
		if (!$existe){
			$sql->adTabela('patrocinador_gestao');
			$sql->adCampo('MAX(patrocinador_gestao_ordem)');
			if ($uuid) $sql->adOnde('patrocinador_gestao_uuid = \''.$uuid.'\'');
			else $sql->adOnde('patrocinador_gestao_patrocinador ='.(int)$patrocinador_id);	
		  $qnt = (int)$sql->Resultado();
		  $sql->limpar();
			$sql->adTabela('patrocinador_gestao');
			if ($uuid) $sql->adInserir('patrocinador_gestao_uuid', $uuid);
			else $sql->adInserir('patrocinador_gestao_patrocinador', (int)$patrocinador_id);
			
			if ($patrocinador_tarefa) $sql->adInserir('patrocinador_gestao_tarefa', (int)$patrocinador_tarefa);
			if ($patrocinador_projeto) $sql->adInserir('patrocinador_gestao_projeto', (int)$patrocinador_projeto);
			elseif ($patrocinador_perspectiva) $sql->adInserir('patrocinador_gestao_perspectiva', (int)$patrocinador_perspectiva);
			elseif ($patrocinador_tema) $sql->adInserir('patrocinador_gestao_tema', (int)$patrocinador_tema);
			elseif ($patrocinador_objetivo) $sql->adInserir('patrocinador_gestao_objetivo', (int)$patrocinador_objetivo);
			elseif ($patrocinador_fator) $sql->adInserir('patrocinador_gestao_fator', (int)$patrocinador_fator);
			elseif ($patrocinador_estrategia) $sql->adInserir('patrocinador_gestao_estrategia', (int)$patrocinador_estrategia);
			elseif ($patrocinador_acao) $sql->adInserir('patrocinador_gestao_acao', (int)$patrocinador_acao);
			elseif ($patrocinador_pratica) $sql->adInserir('patrocinador_gestao_pratica', (int)$patrocinador_pratica);
			elseif ($patrocinador_meta) $sql->adInserir('patrocinador_gestao_meta', (int)$patrocinador_meta);
			elseif ($patrocinador_canvas) $sql->adInserir('patrocinador_gestao_canvas', (int)$patrocinador_canvas);
			elseif ($patrocinador_risco) $sql->adInserir('patrocinador_gestao_risco', (int)$patrocinador_risco);
			elseif ($patrocinador_risco_resposta) $sql->adInserir('patrocinador_gestao_risco_resposta', (int)$patrocinador_risco_resposta);
			elseif ($patrocinador_indicador) $sql->adInserir('patrocinador_gestao_indicador', (int)$patrocinador_indicador);
			elseif ($patrocinador_calendario) $sql->adInserir('patrocinador_gestao_calendario', (int)$patrocinador_calendario);
			elseif ($patrocinador_monitoramento) $sql->adInserir('patrocinador_gestao_monitoramento', (int)$patrocinador_monitoramento);
			elseif ($patrocinador_ata) $sql->adInserir('patrocinador_gestao_ata', (int)$patrocinador_ata);
			elseif ($patrocinador_mswot) $sql->adInserir('patrocinador_gestao_mswot', (int)$patrocinador_mswot);
			elseif ($patrocinador_swot) $sql->adInserir('patrocinador_gestao_swot', (int)$patrocinador_swot);
			elseif ($patrocinador_operativo) $sql->adInserir('patrocinador_gestao_operativo', (int)$patrocinador_operativo);
			elseif ($patrocinador_instrumento) $sql->adInserir('patrocinador_gestao_instrumento', (int)$patrocinador_instrumento);
			elseif ($patrocinador_recurso) $sql->adInserir('patrocinador_gestao_recurso', (int)$patrocinador_recurso);
			elseif ($patrocinador_problema) $sql->adInserir('patrocinador_gestao_problema', (int)$patrocinador_problema);
			elseif ($patrocinador_demanda) $sql->adInserir('patrocinador_gestao_demanda', (int)$patrocinador_demanda);
			elseif ($patrocinador_programa) $sql->adInserir('patrocinador_gestao_programa', (int)$patrocinador_programa);
			elseif ($patrocinador_licao) $sql->adInserir('patrocinador_gestao_licao', (int)$patrocinador_licao);
			elseif ($patrocinador_evento) $sql->adInserir('patrocinador_gestao_evento', (int)$patrocinador_evento);
			elseif ($patrocinador_link) $sql->adInserir('patrocinador_gestao_link', (int)$patrocinador_link);
			elseif ($patrocinador_avaliacao) $sql->adInserir('patrocinador_gestao_avaliacao', (int)$patrocinador_avaliacao);
			elseif ($patrocinador_tgn) $sql->adInserir('patrocinador_gestao_tgn', (int)$patrocinador_tgn);
			elseif ($patrocinador_brainstorm) $sql->adInserir('patrocinador_gestao_brainstorm', (int)$patrocinador_brainstorm);
			elseif ($patrocinador_gut) $sql->adInserir('patrocinador_gestao_gut', (int)$patrocinador_gut);
			elseif ($patrocinador_causa_efeito) $sql->adInserir('patrocinador_gestao_causa_efeito', (int)$patrocinador_causa_efeito);
			elseif ($patrocinador_arquivo) $sql->adInserir('patrocinador_gestao_arquivo', (int)$patrocinador_arquivo);
			elseif ($patrocinador_forum) $sql->adInserir('patrocinador_gestao_forum', (int)$patrocinador_forum);
			elseif ($patrocinador_checklist) $sql->adInserir('patrocinador_gestao_checklist', (int)$patrocinador_checklist);
			elseif ($patrocinador_agenda) $sql->adInserir('patrocinador_gestao_agenda', (int)$patrocinador_agenda);
			elseif ($patrocinador_agrupamento) $sql->adInserir('patrocinador_gestao_agrupamento', (int)$patrocinador_agrupamento);
			
			elseif ($patrocinador_patrocinador) $sql->adInserir('patrocinador_gestao_semelhante', (int)$patrocinador_patrocinador);
			
			elseif ($patrocinador_template) $sql->adInserir('patrocinador_gestao_template', (int)$patrocinador_template);
			elseif ($patrocinador_painel) $sql->adInserir('patrocinador_gestao_painel', (int)$patrocinador_painel);
			elseif ($patrocinador_painel_odometro) $sql->adInserir('patrocinador_gestao_painel_odometro', (int)$patrocinador_painel_odometro);
			elseif ($patrocinador_painel_composicao) $sql->adInserir('patrocinador_gestao_painel_composicao', (int)$patrocinador_painel_composicao);
			elseif ($patrocinador_tr) $sql->adInserir('patrocinador_gestao_tr', (int)$patrocinador_tr);
			elseif ($patrocinador_me) $sql->adInserir('patrocinador_gestao_me', (int)$patrocinador_me);
			elseif ($patrocinador_acao_item) $sql->adInserir('patrocinador_gestao_acao_item', (int)$patrocinador_acao_item);
			elseif ($patrocinador_beneficio) $sql->adInserir('patrocinador_gestao_beneficio', (int)$patrocinador_beneficio);
			elseif ($patrocinador_painel_slideshow) $sql->adInserir('patrocinador_gestao_painel_slideshow', (int)$patrocinador_painel_slideshow);
			elseif ($patrocinador_projeto_viabilidade) $sql->adInserir('patrocinador_gestao_projeto_viabilidade', (int)$patrocinador_projeto_viabilidade);
			elseif ($patrocinador_projeto_abertura) $sql->adInserir('patrocinador_gestao_projeto_abertura', (int)$patrocinador_projeto_abertura);
			elseif ($patrocinador_plano_gestao) $sql->adInserir('patrocinador_gestao_plano_gestao', (int)$patrocinador_plano_gestao);
			elseif ($patrocinador_ssti) $sql->adInserir('patrocinador_gestao_ssti', (int)$patrocinador_ssti);
			elseif ($patrocinador_laudo) $sql->adInserir('patrocinador_gestao_laudo', (int)$patrocinador_laudo);
			elseif ($patrocinador_trelo) $sql->adInserir('patrocinador_gestao_trelo', (int)$patrocinador_trelo);
			elseif ($patrocinador_trelo_cartao) $sql->adInserir('patrocinador_gestao_trelo_cartao', (int)$patrocinador_trelo_cartao);
			elseif ($patrocinador_pdcl) $sql->adInserir('patrocinador_gestao_pdcl', (int)$patrocinador_pdcl);
			elseif ($patrocinador_pdcl_item) $sql->adInserir('patrocinador_gestao_pdcl_item', (int)$patrocinador_pdcl_item);
			elseif ($patrocinador_os) $sql->adInserir('patrocinador_gestao_os', (int)$patrocinador_os);
			
			$sql->adInserir('patrocinador_gestao_ordem', ++$qnt);
			$sql->exec();
			$sql->limpar();
	
			$saida=atualizar_gestao($patrocinador_id, $uuid);
			$objResposta = new xajaxResponse();
			$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
			return $objResposta;
			}
		}
	}
$xajax->registerFunction("incluir_relacionado");	


function excluir_gestao($patrocinador_id=0, $uuid='', $patrocinador_gestao_id=0){	
	$sql = new BDConsulta;
	$sql->setExcluir('patrocinador_gestao');
	$sql->adOnde('patrocinador_gestao_id='.(int)$patrocinador_gestao_id);
	$sql->exec();
	
	$saida=atualizar_gestao($patrocinador_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("excluir_gestao");	

function exibir_gestao($patrocinador_id=0, $uuid=''){	
	$saida=atualizar_gestao($patrocinador_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_gestao","innerHTML", utf8_encode($saida));
	return $objResposta;
	}	
$xajax->registerFunction("exibir_gestao");	


function atualizar_gestao($patrocinador_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->adTabela('patrocinador_gestao');
	$sql->adCampo('patrocinador_gestao.*');
	if ($uuid) $sql->adOnde('patrocinador_gestao_uuid = \''.$uuid.'\'');
	else $sql->adOnde('patrocinador_gestao_patrocinador ='.(int)$patrocinador_id);	
	$sql->adOrdem('patrocinador_gestao_ordem');
  $lista = $sql->Lista();
  $sql->limpar();
  $saida='';
	if (count($lista)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		$saida.= '<tr align="center">';
		$saida.= '<td style="white-space: nowrap" width="40" align="center">';
		$saida.= dica('Mover para Primeira Posi??o', 'Clique neste ?cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['patrocinador_gestao_ordem'].', '.$gestao_data['patrocinador_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ?cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['patrocinador_gestao_ordem'].', '.$gestao_data['patrocinador_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ?cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['patrocinador_gestao_ordem'].', '.$gestao_data['patrocinador_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posi??o', 'Clique neste ?cone '.imagem('icones/2setabaixo.gif').' para mover para a ?ltima posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['patrocinador_gestao_ordem'].', '.$gestao_data['patrocinador_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= '</td>';
			
		if ($gestao_data['patrocinador_gestao_tarefa']) $saida.= '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['patrocinador_gestao_tarefa']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_projeto']) $saida.= '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['patrocinador_gestao_projeto']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_perspectiva']) $saida.= '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['patrocinador_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_tema']) $saida.= '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['patrocinador_gestao_tema']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_objetivo']) $saida.= '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['patrocinador_gestao_objetivo']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_fator']) $saida.= '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['patrocinador_gestao_fator']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_estrategia']) $saida.= '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['patrocinador_gestao_estrategia']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_meta']) $saida.= '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['patrocinador_gestao_meta']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_pratica']) $saida.= '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['patrocinador_gestao_pratica']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_acao']) $saida.= '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['patrocinador_gestao_acao']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_canvas']) $saida.= '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['patrocinador_gestao_canvas']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_risco']) $saida.= '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['patrocinador_gestao_risco']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_risco_resposta']) $saida.= '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['patrocinador_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_indicador']) $saida.= '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['patrocinador_gestao_indicador']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_calendario']) $saida.= '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['patrocinador_gestao_calendario']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_monitoramento']) $saida.= '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['patrocinador_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_ata']) $saida.= '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['patrocinador_gestao_ata']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_mswot']) $saida.= '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['patrocinador_gestao_mswot']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_swot']) $saida.= '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['patrocinador_gestao_swot']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_operativo']) $saida.= '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['patrocinador_gestao_operativo']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_instrumento']) $saida.= '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['patrocinador_gestao_instrumento']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_recurso']) $saida.= '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['patrocinador_gestao_recurso']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_problema']) $saida.= '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['patrocinador_gestao_problema']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_demanda']) $saida.= '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['patrocinador_gestao_demanda']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_programa']) $saida.= '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['patrocinador_gestao_programa']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_licao']) $saida.= '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['patrocinador_gestao_licao']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_evento']) $saida.= '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['patrocinador_gestao_evento']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_link']) $saida.= '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['patrocinador_gestao_link']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_avaliacao']) $saida.= '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['patrocinador_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_tgn']) $saida.= '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['patrocinador_gestao_tgn']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_brainstorm']) $saida.= '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['patrocinador_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_gut']) $saida.= '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['patrocinador_gestao_gut']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_causa_efeito']) $saida.= '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['patrocinador_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_arquivo']) $saida.= '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['patrocinador_gestao_arquivo']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_forum']) $saida.= '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['patrocinador_gestao_forum']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_checklist']) $saida.= '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['patrocinador_gestao_checklist']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_agenda']) $saida.= '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['patrocinador_gestao_agenda']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_agrupamento']) $saida.= '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['patrocinador_gestao_agrupamento']).'</td>';
		
		elseif ($gestao_data['patrocinador_gestao_semelhante']) $saida.= '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['patrocinador_gestao_semelhante']).'</td>';
		
		elseif ($gestao_data['patrocinador_gestao_template']) $saida.= '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['patrocinador_gestao_template']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_painel']) $saida.= '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['patrocinador_gestao_painel']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_painel_odometro']) $saida.= '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['patrocinador_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_painel_composicao']) $saida.= '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['patrocinador_gestao_painel_composicao']).'</td>';		
		elseif ($gestao_data['patrocinador_gestao_tr']) $saida.= '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['patrocinador_gestao_tr']).'</td>';	
		elseif ($gestao_data['patrocinador_gestao_me']) $saida.= '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['patrocinador_gestao_me']).'</td>';	
		elseif ($gestao_data['patrocinador_gestao_acao_item']) $saida.= '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['patrocinador_gestao_acao_item']).'</td>';	
		elseif ($gestao_data['patrocinador_gestao_beneficio']) $saida.= '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['patrocinador_gestao_beneficio']).'</td>';	
		elseif ($gestao_data['patrocinador_gestao_painel_slideshow']) $saida.= '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['patrocinador_gestao_painel_slideshow']).'</td>';	
		elseif ($gestao_data['patrocinador_gestao_projeto_viabilidade']) $saida.= '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['patrocinador_gestao_projeto_viabilidade']).'</td>';	
		elseif ($gestao_data['patrocinador_gestao_projeto_abertura']) $saida.= '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['patrocinador_gestao_projeto_abertura']).'</td>';	
		elseif ($gestao_data['patrocinador_gestao_plano_gestao']) $saida.= '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['patrocinador_gestao_plano_gestao']).'</td>';	
		elseif ($gestao_data['patrocinador_gestao_ssti']) $saida.= '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['patrocinador_gestao_ssti']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_laudo']) $saida.= '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['patrocinador_gestao_laudo']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_trelo']) $saida.= '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['patrocinador_gestao_trelo']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_trelo_cartao']) $saida.= '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['patrocinador_gestao_trelo_cartao']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_pdcl']) $saida.= '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['patrocinador_gestao_pdcl']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_pdcl_item']) $saida.= '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['patrocinador_gestao_pdcl_item']).'</td>';
		elseif ($gestao_data['patrocinador_gestao_os']) $saida.= '<td align=left>'.imagem('icones/os_p.png').link_os($gestao_data['patrocinador_gestao_os']).'</td>';
		$saida.= '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['patrocinador_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ?cone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) $saida.= '</table>';
	return $saida;
	}			
		
		

function exibir_instrumentos($instrumentos){
	global $config;
	$instrumentos_selecionados=explode(',', $instrumentos);
	$saida_instrumentos='';
	if (count($instrumentos_selecionados)) {
			$saida_instrumentos.= '<table cellpadding=0 cellspacing=0>';
			$saida_instrumentos.= '<tr><td class="texto" style="width:400px;">'.link_instrumento($instrumentos_selecionados[0]);
			$qnt_lista_instrumentos=count($instrumentos_selecionados);
			if ($qnt_lista_instrumentos > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_instrumentos; $i < $i_cmp; $i++) $lista.=link_instrumento($instrumentos_selecionados[$i]).'<br>';		
					$saida_instrumentos.= dica('Outr'.$config['genero_instrumento'].'s '.ucfirst($config['instrumentos']), 'Clique para visualizar '.$config['genero_instrumento'].'s demais '.strtolower($config['instrumentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_instrumentos\');">(+'.($qnt_lista_instrumentos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_instrumentos"><br>'.$lista.'</span>';
					}
			$saida_instrumentos.= '</td></tr></table>';
			} 
	else $saida_instrumentos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_instrumentos',"innerHTML", utf8_encode($saida_instrumentos));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_instrumentos");

function exibir_usuarios($usuarios){
	global $config;
	$usuarios_selecionados=explode(',', $usuarios);
	$saida_usuarios='';
	if (count($usuarios_selecionados)) {
			$saida_usuarios.= '<table cellpadding=0 cellspacing=0>';
			$saida_usuarios.= '<tr><td class="texto" style="width:400px;">'.link_usuario($usuarios_selecionados[0],'','','esquerda');
			$qnt_lista_usuarios=count($usuarios_selecionados);
			if ($qnt_lista_usuarios > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';		
					$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
					}
			$saida_usuarios.= '</td></tr></table>';
			} 
	else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_usuarios',"innerHTML", utf8_encode($saida_usuarios));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_usuarios");

function exibir_depts($depts){
	global $config;
	$depts_selecionados=explode(',', $depts);
	$saida_depts='';
	if (count($depts_selecionados)) {
			$saida_depts.= '<table cellpadding=0 cellspacing=0>';
			$saida_depts.= '<tr><td class="texto" style="width:400px;">'.link_dept($depts_selecionados[0]);
			$qnt_lista_depts=count($depts_selecionados);
			if ($qnt_lista_depts > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_dept($depts_selecionados[$i]).'<br>';		
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
					}
			$saida_depts.= '</td></tr></table>';
			} 
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_depts',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_depts");

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");	

function selecionar_cidades_ajax($estado_sigla='', $campo, $posicao, $script, $cidade=''){
	$saida=selecionar_cidades_para_ajax($estado_sigla, $campo, $script, '', $cidade);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("selecionar_cidades_ajax");


$xajax->processRequest();

?>