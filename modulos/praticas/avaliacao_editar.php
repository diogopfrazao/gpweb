<?php

/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';


if (!defined('BASE_DIR'))	die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;
echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();

$avaliacao_id = intval(getParam($_REQUEST, 'avaliacao_id', 0));

$avaliacao_projeto=getParam($_REQUEST, 'avaliacao_projeto', null);
$avaliacao_tarefa=getParam($_REQUEST, 'avaliacao_tarefa', null);
$avaliacao_perspectiva=getParam($_REQUEST, 'avaliacao_perspectiva', null);
$avaliacao_tema=getParam($_REQUEST, 'avaliacao_tema', null);
$avaliacao_objetivo=getParam($_REQUEST, 'avaliacao_objetivo', null);
$avaliacao_fator=getParam($_REQUEST, 'avaliacao_fator', null);
$avaliacao_estrategia=getParam($_REQUEST, 'avaliacao_estrategia', null);
$avaliacao_meta=getParam($_REQUEST, 'avaliacao_meta', null);
$avaliacao_pratica=getParam($_REQUEST, 'avaliacao_pratica', null);
$avaliacao_acao=getParam($_REQUEST, 'avaliacao_acao', null);
$avaliacao_canvas=getParam($_REQUEST, 'avaliacao_canvas', null);
$avaliacao_risco=getParam($_REQUEST, 'avaliacao_risco', null);
$avaliacao_risco_resposta=getParam($_REQUEST, 'avaliacao_risco_resposta', null);
$avaliacao_indicador=getParam($_REQUEST, 'avaliacao_indicador', null);
$avaliacao_calendario=getParam($_REQUEST, 'avaliacao_calendario', null);
$avaliacao_monitoramento=getParam($_REQUEST, 'avaliacao_monitoramento', null);
$avaliacao_ata=getParam($_REQUEST, 'avaliacao_ata', null);
$avaliacao_mswot=getParam($_REQUEST, 'avaliacao_mswot', null);
$avaliacao_swot=getParam($_REQUEST, 'avaliacao_swot', null);
$avaliacao_operativo=getParam($_REQUEST, 'avaliacao_operativo', null);
$avaliacao_instrumento=getParam($_REQUEST, 'avaliacao_instrumento', null);
$avaliacao_recurso=getParam($_REQUEST, 'avaliacao_recurso', null);
$avaliacao_problema=getParam($_REQUEST, 'avaliacao_problema', null);
$avaliacao_demanda=getParam($_REQUEST, 'avaliacao_demanda', null);
$avaliacao_programa=getParam($_REQUEST, 'avaliacao_programa', null);
$avaliacao_licao=getParam($_REQUEST, 'avaliacao_licao', null);
$avaliacao_evento=getParam($_REQUEST, 'avaliacao_evento', null);
$avaliacao_link=getParam($_REQUEST, 'avaliacao_link', null);
$avaliacao_avaliacao=getParam($_REQUEST, 'avaliacao_avaliacao', null);
$avaliacao_tgn=getParam($_REQUEST, 'avaliacao_tgn', null);
$avaliacao_brainstorm=getParam($_REQUEST, 'avaliacao_brainstorm', null);
$avaliacao_gut=getParam($_REQUEST, 'avaliacao_gut', null);
$avaliacao_causa_efeito=getParam($_REQUEST, 'avaliacao_causa_efeito', null);
$avaliacao_arquivo=getParam($_REQUEST, 'avaliacao_arquivo', null);
$avaliacao_forum=getParam($_REQUEST, 'avaliacao_forum', null);
$avaliacao_checklist=getParam($_REQUEST, 'avaliacao_checklist', null);
$avaliacao_agenda=getParam($_REQUEST, 'avaliacao_agenda', null);
$avaliacao_agrupamento=getParam($_REQUEST, 'avaliacao_agrupamento', null);
$avaliacao_patrocinador=getParam($_REQUEST, 'avaliacao_patrocinador', null);
$avaliacao_template=getParam($_REQUEST, 'avaliacao_template', null);
$avaliacao_painel=getParam($_REQUEST, 'avaliacao_painel', null);
$avaliacao_painel_odometro=getParam($_REQUEST, 'avaliacao_painel_odometro', null);
$avaliacao_painel_composicao=getParam($_REQUEST, 'avaliacao_painel_composicao', null);
$avaliacao_tr=getParam($_REQUEST, 'avaliacao_tr', null);
$avaliacao_me=getParam($_REQUEST, 'avaliacao_me', null);
$avaliacao_acao_item=getParam($_REQUEST, 'avaliacao_acao_item', null);
$avaliacao_beneficio=getParam($_REQUEST, 'avaliacao_beneficio', null);
$avaliacao_painel_slideshow=getParam($_REQUEST, 'avaliacao_painel_slideshow', null);
$avaliacao_projeto_viabilidade=getParam($_REQUEST, 'avaliacao_projeto_viabilidade', null);
$avaliacao_projeto_abertura=getParam($_REQUEST, 'avaliacao_projeto_abertura', null);
$avaliacao_plano_gestao=getParam($_REQUEST, 'avaliacao_plano_gestao', null);
$avaliacao_ssti=getParam($_REQUEST, 'avaliacao_ssti', null);
$avaliacao_laudo=getParam($_REQUEST, 'avaliacao_laudo', null);
$avaliacao_trelo=getParam($_REQUEST, 'avaliacao_trelo', null);
$avaliacao_trelo_cartao=getParam($_REQUEST, 'avaliacao_trelo_cartao', null);
$avaliacao_pdcl=getParam($_REQUEST, 'avaliacao_pdcl', null);
$avaliacao_pdcl_item=getParam($_REQUEST, 'avaliacao_pdcl_item', null);
$avaliacao_os=getParam($_REQUEST, 'avaliacao_os', null);

$salvar=getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;

require_once BASE_DIR.'/modulos/praticas/avaliacao.class.php';

$obj = new CAvaliacao();
if ($avaliacao_id){
	$obj->load($avaliacao_id);
	$cia_id=$obj->avaliacao_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

	if (
		$avaliacao_projeto || 
		$avaliacao_tarefa || 
		$avaliacao_perspectiva || 
		$avaliacao_tema || 
		$avaliacao_objetivo || 
		$avaliacao_fator || 
		$avaliacao_estrategia || 
		$avaliacao_meta || 
		$avaliacao_pratica || 
		$avaliacao_acao || 
		$avaliacao_canvas || 
		$avaliacao_risco || 
		$avaliacao_risco_resposta || 
		$avaliacao_indicador || 
		$avaliacao_calendario || 
		$avaliacao_monitoramento || 
		$avaliacao_ata || 
		$avaliacao_mswot || 
		$avaliacao_swot || 
		$avaliacao_operativo || 
		$avaliacao_instrumento || 
		$avaliacao_recurso || 
		$avaliacao_problema || 
		$avaliacao_demanda || 
		$avaliacao_programa || 
		$avaliacao_licao || 
		$avaliacao_evento || 
		$avaliacao_link || 
		$avaliacao_avaliacao || 
		$avaliacao_tgn || 
		$avaliacao_brainstorm || 
		$avaliacao_gut || 
		$avaliacao_causa_efeito || 
		$avaliacao_arquivo || 
		$avaliacao_forum || 
		$avaliacao_checklist || 
		$avaliacao_agenda || 
		$avaliacao_agrupamento || 
		$avaliacao_patrocinador || 
		$avaliacao_template || 
		$avaliacao_painel || 
		$avaliacao_painel_odometro || 
		$avaliacao_painel_composicao || 
		$avaliacao_tr || 
		$avaliacao_me || 
		$avaliacao_acao_item || 
		$avaliacao_beneficio || 
		$avaliacao_painel_slideshow || 
		$avaliacao_projeto_viabilidade || 
		$avaliacao_projeto_abertura || 
		$avaliacao_plano_gestao|| 
		$avaliacao_ssti || 
		$avaliacao_laudo || 
		$avaliacao_trelo || 
		$avaliacao_trelo_cartao || 
		$avaliacao_pdcl || 
		$avaliacao_pdcl_item || 
		$avaliacao_os
		){
		$sql->adTabela('cias');
		if ($avaliacao_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
		elseif ($avaliacao_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
		elseif ($avaliacao_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
		elseif ($avaliacao_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
		elseif ($avaliacao_objetivo) $sql->esqUnir('objetivo','objetivo','objetivo_cia=cias.cia_id');
		elseif ($avaliacao_fator) $sql->esqUnir('fator','fator','fator_cia=cias.cia_id');
		elseif ($avaliacao_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
		elseif ($avaliacao_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
		elseif ($avaliacao_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
		elseif ($avaliacao_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
		elseif ($avaliacao_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
		elseif ($avaliacao_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
		elseif ($avaliacao_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
		elseif ($avaliacao_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
		elseif ($avaliacao_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
		elseif ($avaliacao_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
		elseif ($avaliacao_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
		elseif ($avaliacao_mswot) $sql->esqUnir('mswot','mswot','mswot_cia=cias.cia_id');
		elseif ($avaliacao_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
		elseif ($avaliacao_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
		elseif ($avaliacao_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
		elseif ($avaliacao_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
		elseif ($avaliacao_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
		elseif ($avaliacao_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
		elseif ($avaliacao_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
		elseif ($avaliacao_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
		elseif ($avaliacao_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
		elseif ($avaliacao_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
		elseif ($avaliacao_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
		elseif ($avaliacao_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
		elseif ($avaliacao_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
		elseif ($avaliacao_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
		elseif ($avaliacao_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
		elseif ($avaliacao_arquivo) $sql->esqUnir('arquivo','arquivo','arquivo_cia=cias.cia_id');
		elseif ($avaliacao_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
		elseif ($avaliacao_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
		elseif ($avaliacao_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
		elseif ($avaliacao_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
		elseif ($avaliacao_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
		elseif ($avaliacao_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
		elseif ($avaliacao_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
		elseif ($avaliacao_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
		elseif ($avaliacao_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
		elseif ($avaliacao_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
		elseif ($avaliacao_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');
		elseif ($avaliacao_acao_item) $sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao_item_cia=cias.cia_id');
		elseif ($avaliacao_beneficio) $sql->esqUnir('beneficio','beneficio','beneficio_cia=cias.cia_id');
		elseif ($avaliacao_painel_slideshow) $sql->esqUnir('painel_slideshow','painel_slideshow','painel_slideshow_cia=cias.cia_id');
		elseif ($avaliacao_projeto_viabilidade) $sql->esqUnir('projeto_viabilidade','projeto_viabilidade','projeto_viabilidade_cia=cias.cia_id');
		elseif ($avaliacao_projeto_abertura) $sql->esqUnir('projeto_abertura','projeto_abertura','projeto_abertura_cia=cias.cia_id');
		elseif ($avaliacao_plano_gestao) $sql->esqUnir('plano_gestao','plano_gestao','pg_cia=cias.cia_id');
		elseif ($avaliacao_ssti) $sql->esqUnir('ssti','ssti','ssti_cia=cias.cia_id');
		elseif ($avaliacao_laudo) $sql->esqUnir('laudo','laudo','laudo_cia=cias.cia_id');
		elseif ($avaliacao_trelo) $sql->esqUnir('trelo','trelo','trelo_cia=cias.cia_id');
		elseif ($avaliacao_trelo_cartao) $sql->esqUnir('trelo_cartao','trelo_cartao','trelo_cartao_cia=cias.cia_id');
		elseif ($avaliacao_pdcl) $sql->esqUnir('pdcl','pdcl','pdcl_cia=cias.cia_id');
		elseif ($avaliacao_pdcl_item) $sql->esqUnir('pdcl_item','pdcl_item','pdcl_item_cia=cias.cia_id');
		elseif ($avaliacao_os) $sql->esqUnir('os','os','os_cia=cias.cia_id');
	
		if ($avaliacao_tarefa) $sql->adOnde('tarefa_id = '.(int)$avaliacao_tarefa);
		elseif ($avaliacao_projeto) $sql->adOnde('projeto_id = '.(int)$avaliacao_projeto);
		elseif ($avaliacao_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$avaliacao_perspectiva);
		elseif ($avaliacao_tema) $sql->adOnde('tema_id = '.(int)$avaliacao_tema);
		elseif ($avaliacao_objetivo) $sql->adOnde('objetivo_id = '.(int)$avaliacao_objetivo);
		elseif ($avaliacao_fator) $sql->adOnde('fator_id = '.(int)$avaliacao_fator);
		elseif ($avaliacao_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$avaliacao_estrategia);
		elseif ($avaliacao_meta) $sql->adOnde('pg_meta_id = '.(int)$avaliacao_meta);
		elseif ($avaliacao_pratica) $sql->adOnde('pratica_id = '.(int)$avaliacao_pratica);
		elseif ($avaliacao_acao) $sql->adOnde('plano_acao_id = '.(int)$avaliacao_acao);
		elseif ($avaliacao_canvas) $sql->adOnde('canvas_id = '.(int)$avaliacao_canvas);
		elseif ($avaliacao_risco) $sql->adOnde('risco_id = '.(int)$avaliacao_risco);
		elseif ($avaliacao_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$avaliacao_risco_resposta);
		elseif ($avaliacao_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$avaliacao_indicador);
		elseif ($avaliacao_calendario) $sql->adOnde('calendario_id = '.(int)$avaliacao_calendario);
		elseif ($avaliacao_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$avaliacao_monitoramento);
		elseif ($avaliacao_ata) $sql->adOnde('ata_id = '.(int)$avaliacao_ata);
		elseif ($avaliacao_mswot) $sql->adOnde('mswot_id = '.(int)$avaliacao_mswot);
		elseif ($avaliacao_swot) $sql->adOnde('swot_id = '.(int)$avaliacao_swot);
		elseif ($avaliacao_operativo) $sql->adOnde('operativo_id = '.(int)$avaliacao_operativo);
		elseif ($avaliacao_instrumento) $sql->adOnde('instrumento_id = '.(int)$avaliacao_instrumento);
		elseif ($avaliacao_recurso) $sql->adOnde('recurso_id = '.(int)$avaliacao_recurso);
		elseif ($avaliacao_problema) $sql->adOnde('problema_id = '.(int)$avaliacao_problema);
		elseif ($avaliacao_demanda) $sql->adOnde('demanda_id = '.(int)$avaliacao_demanda);
		elseif ($avaliacao_programa) $sql->adOnde('programa_id = '.(int)$avaliacao_programa);
		elseif ($avaliacao_licao) $sql->adOnde('licao_id = '.(int)$avaliacao_licao);
		elseif ($avaliacao_evento) $sql->adOnde('evento_id = '.(int)$avaliacao_evento);
		elseif ($avaliacao_link) $sql->adOnde('link_id = '.(int)$avaliacao_link);
		elseif ($avaliacao_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$avaliacao_avaliacao);
		elseif ($avaliacao_tgn) $sql->adOnde('tgn_id = '.(int)$avaliacao_tgn);
		elseif ($avaliacao_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$avaliacao_brainstorm);
		elseif ($avaliacao_gut) $sql->adOnde('gut_id = '.(int)$avaliacao_gut);
		elseif ($avaliacao_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$avaliacao_causa_efeito);
		elseif ($avaliacao_arquivo) $sql->adOnde('arquivo_id = '.(int)$avaliacao_arquivo);
		elseif ($avaliacao_forum) $sql->adOnde('forum_id = '.(int)$avaliacao_forum);
		elseif ($avaliacao_checklist) $sql->adOnde('checklist_id = '.(int)$avaliacao_checklist);
		elseif ($avaliacao_agenda) $sql->adOnde('agenda_id = '.(int)$avaliacao_agenda);
		elseif ($avaliacao_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$avaliacao_agrupamento);
		elseif ($avaliacao_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$avaliacao_patrocinador);
		elseif ($avaliacao_template) $sql->adOnde('template_id = '.(int)$avaliacao_template);
		elseif ($avaliacao_painel) $sql->adOnde('painel_id = '.(int)$avaliacao_painel);
		elseif ($avaliacao_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$avaliacao_painel_odometro);
		elseif ($avaliacao_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$avaliacao_painel_composicao);
		elseif ($avaliacao_tr) $sql->adOnde('tr_id = '.(int)$avaliacao_tr);
		elseif ($avaliacao_me) $sql->adOnde('me_id = '.(int)$avaliacao_me);
		elseif ($avaliacao_acao_item) $sql->adOnde('plano_acao_item_id = '.(int)$avaliacao_acao_item);
		elseif ($avaliacao_beneficio) $sql->adOnde('beneficio_id = '.(int)$avaliacao_beneficio);
		elseif ($avaliacao_painel_slideshow) $sql->adOnde('painel_slideshow_id = '.(int)$avaliacao_painel_slideshow);
		elseif ($avaliacao_projeto_viabilidade) $sql->adOnde('projeto_viabilidade_id = '.(int)$avaliacao_projeto_viabilidade);
		elseif ($avaliacao_projeto_abertura) $sql->adOnde('projeto_abertura_id = '.(int)$avaliacao_projeto_abertura);
		elseif ($avaliacao_plano_gestao) $sql->adOnde('pg_id = '.(int)$avaliacao_plano_gestao);
		elseif ($avaliacao_ssti) $sql->adOnde('ssti_id = '.(int)$avaliacao_ssti);
		elseif ($avaliacao_laudo) $sql->adOnde('laudo_id = '.(int)$avaliacao_laudo);
		elseif ($avaliacao_trelo) $sql->adOnde('trelo_id = '.(int)$avaliacao_trelo);
		elseif ($avaliacao_trelo_cartao) $sql->adOnde('trelo_cartao_id = '.(int)$avaliacao_trelo_cartao);
		elseif ($avaliacao_pdcl) $sql->adOnde('pdcl_id = '.(int)$avaliacao_pdcl);
		elseif ($avaliacao_pdcl_item) $sql->adOnde('pdcl_item_id = '.(int)$avaliacao_pdcl_item);
		elseif ($avaliacao_os) $sql->adOnde('os_id = '.(int)$avaliacao_os);
		
		$sql->adCampo('cia_id');
		$cia_id = $sql->Resultado();
		$sql->limpar();
		}
	}

if($avaliacao_id && !(permiteEditarAvaliacao($obj->avaliacao_acesso,$avaliacao_id) && $Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'avaliacao_indicador'))) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$avaliacao_id && !$Aplic->checarModulo('praticas', 'adicionar', $Aplic->usuario_id, 'avaliacao_indicador')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$avaliacao_acesso = getSisValor('NivelAcesso','','','sisvalor_id');



$ttl = ($avaliacao_id ? 'Editar Avalia��o' : 'Criar Avalia��o');
$botoesTitulo = new CBlocoTitulo($ttl, 'avaliacao.gif', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$usuarios_selecionados=array();
$depts_selecionados=array();
$cias_selecionadas = array();
if ($avaliacao_id) {
	$sql->adTabela('avaliacao_usuarios', 'avaliacao_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('avaliacao_id = '.$avaliacao_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('avaliacao_dept');
	$sql->adCampo('avaliacao_dept_dept');
	$sql->adOnde('avaliacao_dept_avaliacao ='.(int)$avaliacao_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();
	
	if ($Aplic->profissional){
		$sql->adTabela('avaliacao_cia');
		$sql->adCampo('avaliacao_cia_cia');
		$sql->adOnde('avaliacao_cia_avaliacao = '.(int)$avaliacao_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}



echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="avaliacao_fazer_sql" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="avaliacao_id" id="avaliacao_id" value="'.$avaliacao_id.'" />';
echo '<input name="avaliacao_usuarios" id="avaliacao_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="avaliacao_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="avaliacao_cias"  id="avaliacao_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
$uuid=($avaliacao_id ? null : uuid());
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td align="right">'.dica('Nome da Avalia��o', 'Toda avalia��o necessita ter um nome para identifica��o.').'Nome:'.dicaF().'</td><td><input type="text" name="avaliacao_nome" value="'.$obj->avaliacao_nome.'" style="width:400px;" class="texto" />*</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Respons�vel', 'A qual '.$config['organizacao'].' pertence esta avalia��o.').ucfirst($config['organizacao']).' respons�vel:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'avaliacao_cia', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
if ($Aplic->profissional) {
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			}
	else $saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' est�o envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}
if ($Aplic->profissional) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Respons�vel', 'Escolha pressionando o �cone � direita qual '.$config['genero_dept'].' '.$config['dept'].' respons�vel por esta avalia��o.').ucfirst($config['departamento']).' respons�vel:'.dicaF().'</td><td><input type="hidden" name="avaliacao_dept" id="avaliacao_dept" value="'.$obj->avaliacao_dept.'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($obj->avaliacao_dept).'" style="width:400px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';
$saida_depts='';
if (count($depts_selecionados)) {
		$saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_depts.= '<tr><td>'.link_dept($depts_selecionados[0]);
		$qnt_lista_depts=count($depts_selecionados);
		if ($qnt_lista_depts > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_dept($depts_selecionados[$i]).'<br>';
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		}
else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' est�o envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';




echo '<tr><td align="right" style="white-space: nowrap">'.dica('Respons�vel pela Avalia��o', 'Toda avalia��o deve ter um respons�vel.').'Respons�vel:'.dicaF().'</td><td colspan="2"><input type="hidden" id="avaliacao_responsavel" name="avaliacao_responsavel" value="'.($obj->avaliacao_responsavel ? $obj->avaliacao_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->avaliacao_responsavel ? $obj->avaliacao_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

$saida_usuarios='';
if (count($usuarios_selecionados)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($usuarios_selecionados[0],'','','esquerda');
		$qnt_lista_usuarios=count($usuarios_selecionados);
		if ($qnt_lista_usuarios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		}
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' est�o envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';


if ($Aplic->profissional)  echo '<tr><td align="right" style="white-space: nowrap" >'.dica('Descri��o', 'Descri��o sobre esta avalia��o.').'Descri��o:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="avaliacao_descricao" style="width:400px;" rows="2" class="textarea">'.$obj->avaliacao_descricao.'</textarea></td></tr>';
else {
	echo '<tr><td colspan=2 align="center">Descri��o</td></tr>';
	echo '<tr><td colspan=20 align="left" style="background:#ffffff; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="avaliacao_descricao" id="avaliacao_descricao">'.$obj->avaliacao_descricao.'</textarea></td></tr>';
	}


$tipos=array(''=>'');
if ($Aplic->checarModulo('projetos', 'editar', null, 'projetos_lista')) $tipos['projeto']=ucfirst($config['projeto']); 
if ($Aplic->checarModulo('tarefas', 'editar', null, null)) $tipos['tarefa']=ucfirst($config['tarefa']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'perspectiva')) $tipos['perspectiva']=ucfirst($config['perspectiva']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'tema')) $tipos['tema']=ucfirst($config['tema']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'objetivo')) $tipos['objetivo']=ucfirst($config['objetivo']); 
if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'editar', null, 'fator')) $tipos['fator']=ucfirst($config['fator']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'iniciativa')) $tipos['estrategia']=ucfirst($config['iniciativa']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'meta')) $tipos['meta']=ucfirst($config['meta']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'plano_acao')) $tipos['acao']=ucfirst($config['acao']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'pratica')) $tipos['pratica']=ucfirst($config['pratica']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'indicador')) $tipos['indicador']='Indicador'; 
if ($Aplic->checarModulo('agenda', 'editar', null, null)) $tipos['calendario']='Agenda';
if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'editar', null, null)) $tipos['instrumento']=ucfirst($config['instrumento']);
if ($Aplic->checarModulo('recursos', 'editar', null, null)) $tipos['recurso']=ucfirst($config['recurso']);
if ($Aplic->checarModulo('projetos', 'editar', null, 'demanda')) $tipos['demanda']='Demanda';
if ($Aplic->checarModulo('projetos', 'editar', null, 'licao')) $tipos['licao']=ucfirst($config['licao']);
if ($Aplic->checarModulo('eventos', 'editar', null, null)) $tipos['evento']='Evento';
if ($Aplic->checarModulo('links', 'editar', null, null)) $tipos['link']='Link';
if ($Aplic->checarModulo('praticas', 'editar', null, 'avaliacao_indicador')) $tipos['avaliacao']='Avalia��o';
if ($Aplic->checarModulo('praticas', 'editar', null, 'brainstorm')) $tipos['brainstorm']='Brainstorm';
if ($Aplic->checarModulo('praticas', 'editar', null, 'gut')) $tipos['gut']='Matriz GUT';
if ($Aplic->checarModulo('praticas', 'editar', null, 'causa_efeito')) $tipos['causa_efeito']='Diagrama de causa-efeito';
if ($Aplic->checarModulo('arquivos', 'editar', null,  null)) $tipos['arquivo']='Arquivo';
if ($Aplic->checarModulo('foruns', 'editar', null, null)) $tipos['forum']='F�rum';
if ($Aplic->checarModulo('praticas', 'editar', null, 'checklist')) $tipos['checklist']='Checklist';
if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'editar', null, null)) $tipos['patrocinador']=ucfirst($config['patrocinador']);
if ($Aplic->checarModulo('praticas', 'editar', null, 'plano_acao_item')) $tipos['acao_item']='Item de '.ucfirst($config['acao']);
if ($Aplic->checarModulo('projetos', 'editar', null, 'viabilidade')) $tipos['projeto_viabilidade']='Estudo de viabilidade';
if ($Aplic->checarModulo('projetos', 'editar', null, 'abertura')) $tipos['projeto_abertura']='Termo de abertura';
if ($Aplic->checarModulo('praticas', 'editar', null, 'planejamento')) $tipos['plano_gestao']='Planejamento estrat�gico';
if ($Aplic->profissional) {
	$tipos['agenda']='Compromisso';
	if ($Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'editar', null, null)) $tipos['operativo']='Plano operativo';
	if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'editar', null, null)) $tipos['ata']='Ata de reuni�o';	
	if ($Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'editar', null, null)) {
		$tipos['mswot']='Matriz SWOT';
		$tipos['swot']='Campo SWOT';
		}
	if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'editar', null, null)) $tipos['problema']=ucfirst($config['problema']);
	if ($Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'editar', null, null)) $tipos['agrupamento']='Agrupamento';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'canvas')) $tipos['canvas']=ucfirst($config['canvas']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'risco')) $tipos['risco']=ucfirst($config['risco']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'resposta_risco')) $tipos['risco_resposta']=ucfirst($config['risco_resposta']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'monitoramento')) $tipos['monitoramento']='Monitoramento';
	if ($Aplic->checarModulo('projetos', 'editar', null, 'programa')) $tipos['programa']=ucfirst($config['programa']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'tgn')) $tipos['tgn']=ucfirst($config['tgn']);
	if ($Aplic->checarModulo('projetos', 'editar', null, 'modelo')) $tipos['template']='Modelo';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'painel_indicador')) $tipos['painel']='Painel de indicador';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'odometro_indicador')) $tipos['painel_odometro']='Od�metro de indicador';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'composicao_painel')) $tipos['painel_composicao']='Composi��o de pain�is';
	if ($Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'editar', null, null)) $tipos['tr']=ucfirst($config['tr']);
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'editar', null, 'me')) $tipos['me']=ucfirst($config['me']);
	if ($Aplic->checarModulo('projetos', 'editar', null, 'beneficio')) $tipos['beneficio']=ucfirst($config['beneficio']).' de '.$config['programa'];
	if ($Aplic->checarModulo('projetos', 'editar', null, 'slideshow_painel')) $tipos['painel_slideshow']='Slideshow de composi��es';
	if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'editar', null, 'ssti')) $tipos['ssti']=ucfirst($config['ssti']);
	if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'editar', null, 'laudo')) $tipos['laudo']=ucfirst($config['laudo']);
	if ($Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'editar', null, null)) {
		$tipos['trelo']=ucfirst($config['trelo']);
		$tipos['trelo_cartao']=ucfirst($config['trelo_cartao']);
		}
	if ($Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'editar', null, null)) {
		$tipos['pdcl']=ucfirst($config['pdcl']);
		$tipos['pdcl_item']=ucfirst($config['pdcl_item']);
		}
	if ($Aplic->modulo_ativo('os') && $Aplic->checarModulo('os', 'editar', null, null)) $tipos['os']=ucfirst($config['os']);	
	}	
asort($tipos);


if ($avaliacao_tarefa) $tipo='tarefa';
elseif ($avaliacao_projeto) $tipo='projeto';
elseif ($avaliacao_perspectiva) $tipo='perspectiva';
elseif ($avaliacao_tema) $tipo='tema';
elseif ($avaliacao_objetivo) $tipo='objetivo';
elseif ($avaliacao_fator) $tipo='fator';
elseif ($avaliacao_estrategia) $tipo='estrategia';
elseif ($avaliacao_meta) $tipo='meta';
elseif ($avaliacao_pratica) $tipo='pratica';
elseif ($avaliacao_acao) $tipo='acao';
elseif ($avaliacao_canvas) $tipo='canvas';
elseif ($avaliacao_risco) $tipo='risco';
elseif ($avaliacao_risco_resposta) $tipo='risco_resposta';
elseif ($avaliacao_indicador) $tipo='avaliacao_indicador';
elseif ($avaliacao_calendario) $tipo='calendario';
elseif ($avaliacao_monitoramento) $tipo='monitoramento';
elseif ($avaliacao_ata) $tipo='ata';
elseif ($avaliacao_mswot) $tipo='mswot';
elseif ($avaliacao_swot) $tipo='swot';
elseif ($avaliacao_operativo) $tipo='operativo';
elseif ($avaliacao_instrumento) $tipo='instrumento';
elseif ($avaliacao_recurso) $tipo='recurso';
elseif ($avaliacao_problema) $tipo='problema';
elseif ($avaliacao_demanda) $tipo='demanda';
elseif ($avaliacao_programa) $tipo='programa';
elseif ($avaliacao_licao) $tipo='licao';
elseif ($avaliacao_evento) $tipo='evento';
elseif ($avaliacao_link) $tipo='link';
elseif ($avaliacao_avaliacao) $tipo='avaliacao';
elseif ($avaliacao_tgn) $tipo='tgn';
elseif ($avaliacao_brainstorm) $tipo='brainstorm';
elseif ($avaliacao_gut) $tipo='gut';
elseif ($avaliacao_causa_efeito) $tipo='causa_efeito';
elseif ($avaliacao_arquivo) $tipo='arquivo';
elseif ($avaliacao_forum) $tipo='forum';
elseif ($avaliacao_checklist) $tipo='checklist';
elseif ($avaliacao_agenda) $tipo='agenda';
elseif ($avaliacao_agrupamento) $tipo='agrupamento';
elseif ($avaliacao_patrocinador) $tipo='patrocinador';
elseif ($avaliacao_template) $tipo='template';
elseif ($avaliacao_painel) $tipo='painel';
elseif ($avaliacao_painel_odometro) $tipo='painel_odometro';
elseif ($avaliacao_painel_composicao) $tipo='painel_composicao';
elseif ($avaliacao_tr) $tipo='tr';
elseif ($avaliacao_me) $tipo='me';
elseif ($avaliacao_acao_item) $tipo='acao_item';
elseif ($avaliacao_beneficio) $tipo='beneficio';
elseif ($avaliacao_painel_slideshow) $tipo='painel_slideshow';
elseif ($avaliacao_projeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($avaliacao_projeto_abertura) $tipo='projeto_abertura';
elseif ($avaliacao_plano_gestao) $tipo='plano_gestao';
elseif ($avaliacao_ssti) $tipo='ssti';
elseif ($avaliacao_laudo) $tipo='laudo';
elseif ($avaliacao_trelo) $tipo='trelo';
elseif ($avaliacao_trelo_cartao) $tipo='trelo_cartao';
elseif ($avaliacao_pdcl) $tipo='pdcl';
elseif ($avaliacao_pdcl_item) $tipo='pdcl_item';	
elseif ($avaliacao_os) $tipo='os';	
else $tipo='';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Relacionada', 'A que �rea a avalia��o est� relacionada.').'Relacionada:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';

echo '<tr '.($avaliacao_projeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso seja espec�fico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo dever� constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_projeto" value="'.$avaliacao_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($avaliacao_projeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste �cone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso seja espec�fico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo dever� constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_tarefa" value="'.$avaliacao_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($avaliacao_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste �cone '.imagem('icones/tarefa_p.gif').' escolher � qual '.$config['tarefa'].' o arquivo ir� pertencer.<br><br>Caso n�o escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo ser� d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso seja espec�fico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo dever� constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_perspectiva" value="'.$avaliacao_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($avaliacao_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste �cone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso seja espec�fico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo dever� constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_tema" value="'.$avaliacao_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($avaliacao_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste �cone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso seja espec�fico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo dever� constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_objetivo" value="'.$avaliacao_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($avaliacao_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste �cone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso seja espec�fico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo dever� constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_fator" value="'.$avaliacao_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($avaliacao_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste �cone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso seja espec�fico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo dever� constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_estrategia" value="'.$avaliacao_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($avaliacao_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste �cone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso seja espec�fico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo dever� constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_meta" value="'.$avaliacao_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($avaliacao_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste �cone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso seja espec�fico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo dever� constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_pratica" value="'.$avaliacao_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($avaliacao_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste �cone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso seja espec�fico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo dever� constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_acao" value="'.$avaliacao_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($avaliacao_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar A��o','Clique neste �cone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de a��o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso seja espec�fico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo dever� constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_canvas" value="'.$avaliacao_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($avaliacao_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste �cone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso seja espec�fico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo dever� constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_risco" value="'.$avaliacao_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($avaliacao_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste �cone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso seja espec�fico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo dever� constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_risco_resposta" value="'.$avaliacao_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($avaliacao_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste �cone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso seja espec�fico de um indicador, neste campo dever� constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_indicador" value="'.$avaliacao_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($avaliacao_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste �cone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso seja espec�fico de uma agenda, neste campo dever� constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_calendario" value="'.$avaliacao_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($avaliacao_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste �cone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso seja espec�fico de um monitoramento, neste campo dever� constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_monitoramento" value="'.$avaliacao_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($avaliacao_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste �cone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reuni�o', 'Caso seja espec�fico de uma ata de reuni�o neste campo dever� constar o nome da ata').'Ata de Reuni�o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_ata" value="'.(isset($avaliacao_ata) ? $avaliacao_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($avaliacao_ata) ? $avaliacao_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reuni�o','Clique neste �cone '.imagem('icones/ata_p.png').' para selecionar uma ata de reuni�o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso seja espec�fico de uma matriz SWOT neste campo dever� constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_mswot" value="'.(isset($avaliacao_mswot) ? $avaliacao_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($avaliacao_mswot) ? $avaliacao_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste �cone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso seja espec�fico de um campo de matriz SWOT neste campo dever� constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_swot" value="'.(isset($avaliacao_swot) ? $avaliacao_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($avaliacao_swot) ? $avaliacao_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste �cone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso seja espec�fico de um plano operativo, neste campo dever� constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_operativo" value="'.$avaliacao_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($avaliacao_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste �cone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso seja espec�fico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo dever� constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_instrumento" value="'.$avaliacao_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($avaliacao_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste �cone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso seja espec�fico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo dever� constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_recurso" value="'.$avaliacao_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($avaliacao_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste �cone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso seja espec�fico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo dever� constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_problema" value="'.$avaliacao_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($avaliacao_problema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste �cone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso seja espec�fico de uma demanda, neste campo dever� constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_demanda" value="'.$avaliacao_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($avaliacao_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste �cone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso seja espec�fico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo dever� constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_programa" value="'.$avaliacao_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($avaliacao_programa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste �cone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso seja espec�fico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo dever� constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_licao" value="'.$avaliacao_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($avaliacao_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste �cone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso seja espec�fico de um evento, neste campo dever� constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_evento" value="'.$avaliacao_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($avaliacao_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste �cone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso seja espec�fico de um link, neste campo dever� constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_link" value="'.$avaliacao_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($avaliacao_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste �cone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avalia��o', 'Caso seja espec�fico de uma avalia��o, neste campo dever� constar o nome da avalia��o.').'Avalia��o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_avaliacao" value="'.$avaliacao_avaliacao.'" /><input type="text" id="gestao_avaliacao_nome" name="gestao_avaliacao_nome" value="'.nome_avaliacao($avaliacao_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avalia��o','Clique neste �cone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avalia��o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso seja espec�fico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo dever� constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_tgn" value="'.$avaliacao_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($avaliacao_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste �cone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso seja espec�fico de um brainstorm, neste campo dever� constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_brainstorm" value="'.$avaliacao_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($avaliacao_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste �cone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz G.U.T.', 'Caso seja espec�fico de uma matriz G.U.T., neste campo dever� constar o nome da matriz G.U.T..').'Matriz G.U.T.:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_gut" value="'.$avaliacao_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($avaliacao_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz G.U.T.','Clique neste �cone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso seja espec�fico de um diagrama de causa-efeito, neste campo dever� constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_causa_efeito" value="'.$avaliacao_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($avaliacao_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste �cone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso seja espec�fico de um arquivo, neste campo dever� constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_arquivo" value="'.$avaliacao_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($avaliacao_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste �cone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('F�rum', 'Caso seja espec�fico de um f�rum, neste campo dever� constar o nome do f�rum.').'F�rum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_forum" value="'.$avaliacao_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($avaliacao_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar F�rum','Clique neste �cone '.imagem('icones/forum_p.gif').' para selecionar um f�rum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso seja espec�fico de um checklist, neste campo dever� constar o nome do checklist.').'checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_checklist" value="'.$avaliacao_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($avaliacao_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste �cone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso seja espec�fico de um compromisso, neste campo dever� constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_agenda" value="'.$avaliacao_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($avaliacao_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste �cone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso seja espec�fico de um agrupamento, neste campo dever� constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_agrupamento" value="'.$avaliacao_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($avaliacao_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste �cone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso seja espec�fico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo dever� constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_patrocinador" value="'.$avaliacao_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($avaliacao_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste �cone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso seja espec�fico de um modelo, neste campo dever� constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_template" value="'.$avaliacao_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($avaliacao_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste �cone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso seja espec�fico de um painel de indicador, neste campo dever� constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_painel" value="'.$avaliacao_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($avaliacao_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste �cone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Od�metro de Indicador', 'Caso seja espec�fico de um od�metro de indicador, neste campo dever� constar o nome do od�metro.').'Od�metro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_painel_odometro" value="'.$avaliacao_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($avaliacao_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Od�metro','Clique neste �cone '.imagem('icones/odometro_p.png').' para selecionar um od�mtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composi��o de Pain�is', 'Caso seja espec�fico de uma composi��o de pain�is, neste campo dever� constar o nome da composi��o.').'Composi��o de Pain�is:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_painel_composicao" value="'.$avaliacao_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($avaliacao_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composi��o de Pain�is','Clique neste �cone '.imagem('icones/composicao_p.gif').' para selecionar uma composi��o de pain�is.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja espec�fico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo dever� constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_tr" value="'.$avaliacao_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($avaliacao_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste �cone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja espec�fico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo dever� constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_me" value="'.$avaliacao_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($avaliacao_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste �cone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso seja espec�fico de um item de '.$config['acao'].', neste campo dever� constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_acao_item" value="'.$avaliacao_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($avaliacao_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste �cone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso seja espec�fico de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo dever� constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_beneficio" value="'.$avaliacao_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($avaliacao_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste �cone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composi��es', 'Caso seja espec�fico de um slideshow de composi��es, neste campo dever� constar o nome do slideshow de composi��es.').'Slideshow de composi��es:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_painel_slideshow" value="'.$avaliacao_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($avaliacao_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composi��es','Clique neste �cone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composi��es.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_projeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso seja espec�fico de um estudo de viabilidade, neste campo dever� constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_projeto_viabilidade" value="'.$avaliacao_projeto_viabilidade.'" /><input type="text" id="projeto_viabilidade_nome" name="projeto_viabilidade_nome" value="'.nome_viabilidade($avaliacao_projeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste �cone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_projeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso seja espec�fico de um termo de abertura, neste campo dever� constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_projeto_abertura" value="'.$avaliacao_projeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($avaliacao_projeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste �cone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estrat�gico', 'Caso seja espec�fico de um planejamento estrat�gico, neste campo dever� constar o nome do planejamento estrat�gico.').'Planejamento estrat�gico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_plano_gestao" value="'.$avaliacao_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($avaliacao_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estrat�gico','Clique neste �cone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estrat�gico.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_ssti ? '' : 'style="display:none"').' id="ssti" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['ssti']), 'Caso seja espec�fico de '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].', neste campo dever� constar o nome d'.$config['genero_ssti'].' '.$config['ssti'].'.').ucfirst($config['ssti']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_ssti" value="'.$avaliacao_ssti.'" /><input type="text" id="ssti_nome" name="ssti_nome" value="'.nome_ssti($avaliacao_ssti).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSSTI();">'.imagem('icones/ssti_p.png','Selecionar '.ucfirst($config['ssti']),'Clique neste �cone '.imagem('icones/ssti_p.png').' para selecionar '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_laudo ? '' : 'style="display:none"').' id="laudo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['laudo']), 'Caso seja espec�fico de '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].', neste campo dever� constar o nome d'.$config['genero_laudo'].' '.$config['laudo'].'.').ucfirst($config['laudo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_laudo" value="'.$avaliacao_laudo.'" /><input type="text" id="laudo_nome" name="laudo_nome" value="'.nome_laudo($avaliacao_laudo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLaudo();">'.imagem('icones/laudo_p.png','Selecionar '.ucfirst($config['laudo']),'Clique neste �cone '.imagem('icones/laudo_p.png').' para selecionar '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_trelo ? '' : 'style="display:none"').' id="trelo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo']), 'Caso seja espec�fico de '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].', neste campo dever� constar o nome d'.$config['genero_trelo'].' '.$config['trelo'].'.').ucfirst($config['trelo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_trelo" value="'.$avaliacao_trelo.'" /><input type="text" id="trelo_nome" name="trelo_nome" value="'.nome_trelo($avaliacao_trelo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTrelo();">'.imagem('icones/trelo_p.png','Selecionar '.ucfirst($config['trelo']),'Clique neste �cone '.imagem('icones/trelo_p.png').' para selecionar '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_trelo_cartao ? '' : 'style="display:none"').' id="trelo_cartao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo_cartao']), 'Caso seja espec�fico de '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].', neste campo dever� constar o nome d'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_trelo_cartao" value="'.$avaliacao_trelo_cartao.'" /><input type="text" id="trelo_cartao_nome" name="trelo_cartao_nome" value="'.nome_trelo_cartao($avaliacao_trelo_cartao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTreloCartao();">'.imagem('icones/trelo_cartao_p.png','Selecionar '.ucfirst($config['trelo_cartao']),'Clique neste �cone '.imagem('icones/trelo_cartao_p.png').' para selecionar '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_pdcl ? '' : 'style="display:none"').' id="pdcl" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl']), 'Caso seja espec�fico de '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].', neste campo dever� constar o nome d'.$config['genero_pdcl'].' '.$config['pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_pdcl" value="'.$avaliacao_pdcl.'" /><input type="text" id="pdcl_nome" name="pdcl_nome" value="'.nome_pdcl($avaliacao_pdcl).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCL();">'.imagem('icones/pdcl_p.png','Selecionar '.ucfirst($config['pdcl']),'Clique neste �cone '.imagem('icones/pdcl_p.png').' para selecionar '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_pdcl_item ? '' : 'style="display:none"').' id="pdcl_item" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl_item']), 'Caso seja espec�fico de '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].', neste campo dever� constar o nome d'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_pdcl_item" value="'.$avaliacao_pdcl_item.'" /><input type="text" id="pdcl_item_nome" name="pdcl_item_nome" value="'.nome_pdcl_item($avaliacao_pdcl_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="pop_pdcl_item();">'.imagem('icones/pdcl_item_p.png','Selecionar '.ucfirst($config['pdcl_item']),'Clique neste �cone '.imagem('icones/pdcl_item_p.png').' para selecionar '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($avaliacao_os ? '' : 'style="display:none"').' id="os" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['os']), 'Caso seja espec�fico de '.($config['genero_os']=='o' ? 'um' : 'uma').' '.$config['os'].', neste campo dever� constar o nome d'.$config['genero_os'].' '.$config['os'].'.').ucfirst($config['os']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="avaliacao_os" value="'.$avaliacao_os.'" /><input type="text" id="os_nome" name="os_nome" value="'.nome_os($avaliacao_os).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="pop_os();">'.imagem('icones/os_p.png','Selecionar '.ucfirst($config['os']),'Clique neste �cone '.imagem('icones/os_p.png').' para selecionar '.($config['genero_os']=='o' ? 'um' : 'uma').' '.$config['os'].'.').'</a></td></tr></table></td></tr>';
		
	

$sql->adTabela('avaliacao_gestao');
$sql->adCampo('avaliacao_gestao.*');
if ($uuid) $sql->adOnde('avaliacao_gestao_uuid = \''.$uuid.'\'');
else $sql->adOnde('avaliacao_gestao_avaliacao ='.(int)$avaliacao_id);	
$sql->adOrdem('avaliacao_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
echo '<tr><td></td><td><div id="combo_gestao">';
if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
foreach($lista as $gestao_data){
	echo '<tr align="center">';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['avaliacao_gestao_ordem'].', '.$gestao_data['avaliacao_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['avaliacao_gestao_ordem'].', '.$gestao_data['avaliacao_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['avaliacao_gestao_ordem'].', '.$gestao_data['avaliacao_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['avaliacao_gestao_ordem'].', '.$gestao_data['avaliacao_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	if ($gestao_data['avaliacao_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['avaliacao_gestao_tarefa']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['avaliacao_gestao_projeto']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['avaliacao_gestao_perspectiva']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['avaliacao_gestao_tema']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['avaliacao_gestao_objetivo']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['avaliacao_gestao_fator']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['avaliacao_gestao_estrategia']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['avaliacao_gestao_meta']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['avaliacao_gestao_pratica']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['avaliacao_gestao_acao']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['avaliacao_gestao_canvas']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['avaliacao_gestao_risco']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['avaliacao_gestao_risco_resposta']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['avaliacao_gestao_indicador']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_calendario']) echo '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['avaliacao_gestao_calendario']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['avaliacao_gestao_monitoramento']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_ata']) echo '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['avaliacao_gestao_ata']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_mswot']) echo '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['avaliacao_gestao_mswot']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_swot']) echo '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['avaliacao_gestao_swot']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['avaliacao_gestao_operativo']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['avaliacao_gestao_instrumento']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['avaliacao_gestao_recurso']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['avaliacao_gestao_problema']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['avaliacao_gestao_demanda']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['avaliacao_gestao_programa']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['avaliacao_gestao_licao']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['avaliacao_gestao_evento']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['avaliacao_gestao_link']).'</td>';
	
	elseif ($gestao_data['avaliacao_gestao_semelhante']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['avaliacao_gestao_semelhante']).'</td>';
	
	elseif ($gestao_data['avaliacao_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['avaliacao_gestao_tgn']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['avaliacao_gestao_brainstorm']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['avaliacao_gestao_gut']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['avaliacao_gestao_causa_efeito']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['avaliacao_gestao_arquivo']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['avaliacao_gestao_forum']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['avaliacao_gestao_checklist']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_agenda']) echo '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['avaliacao_gestao_agenda']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_agrupamento']) echo '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['avaliacao_gestao_agrupamento']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_patrocinador']) echo '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['avaliacao_gestao_patrocinador']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_template']) echo '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['avaliacao_gestao_template']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_painel']) echo '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['avaliacao_gestao_painel']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['avaliacao_gestao_painel_odometro']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['avaliacao_gestao_painel_composicao']).'</td>';		
	elseif ($gestao_data['avaliacao_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['avaliacao_gestao_tr']).'</td>';	
	elseif ($gestao_data['avaliacao_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['avaliacao_gestao_me']).'</td>';	
	elseif ($gestao_data['avaliacao_gestao_acao_item']) echo '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['avaliacao_gestao_acao_item']).'</td>';	
	elseif ($gestao_data['avaliacao_gestao_beneficio']) echo '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['avaliacao_gestao_beneficio']).'</td>';	
	elseif ($gestao_data['avaliacao_gestao_painel_slideshow']) echo '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['avaliacao_gestao_painel_slideshow']).'</td>';	
	elseif ($gestao_data['avaliacao_gestao_projeto_viabilidade']) echo '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['avaliacao_gestao_projeto_viabilidade']).'</td>';	
	elseif ($gestao_data['avaliacao_gestao_projeto_abertura']) echo '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['avaliacao_gestao_projeto_abertura']).'</td>';	
	elseif ($gestao_data['avaliacao_gestao_plano_gestao']) echo '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['avaliacao_gestao_plano_gestao']).'</td>';	
	elseif ($gestao_data['avaliacao_gestao_ssti']) echo '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['avaliacao_gestao_ssti']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_laudo']) echo '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['avaliacao_gestao_laudo']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_trelo']) echo '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['avaliacao_gestao_trelo']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_trelo_cartao']) echo '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['avaliacao_gestao_trelo_cartao']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_pdcl']) echo '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['avaliacao_gestao_pdcl']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_pdcl_item']) echo '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['avaliacao_gestao_pdcl_item']).'</td>';
	elseif ($gestao_data['avaliacao_gestao_os']) echo '<td align=left>'.imagem('icones/os_p.png').link_os($gestao_data['avaliacao_gestao_os']).'</td>';
	
	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['avaliacao_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
	}
if (count($lista)) echo '</table>';
echo '</div></td></tr>';



$inicio = 0;
$fim = 24;
$inc = 1;

$horas = array();
for ($atual = $inicio; $atual < $fim + 1; $atual++) {
	if ($atual < 10) $chave_atual = "0".$atual;
	else $chave_atual = $atual;
	$horas[$chave_atual] = $atual;
	}
$minutos = array();
$minutos['00'] = '00';
for ($atual = 0 + $inc; $atual < 60; $atual += $inc) $minutos[($atual < 10 ? '0' : '').$atual] = ($atual < 10 ? '0' : '').$atual;


$data_inicio = intval($obj->avaliacao_inicio) ? new CData($obj->avaliacao_inicio) : false;
$data_fim = intval($obj->avaliacao_fim) ? new CData( $obj->avaliacao_fim ) : false;
if($Aplic->profissional && (!$data_inicio || !$data_fim)){
	require_once BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php';
	$cache = CTarefaCache::getInstance();
	$exped = $cache->getExpedienteParaHoje((int)$cia_id);
	if(!$data_inicio){
		$data_inicio = $exped['inicio'];
		}
	if(!$data_fim){
		$desloc = $config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8;
		$data_fim = $cache->deslocaDataPraFrente($data_inicio, $desloc, (int)$cia_id);
		$data_fim = $cache->ajustaInicioPeriodo($data_fim, (int)$cia_id);
		}
	$obj->avaliacao_duracao = $cache->horasPeriodo($data_inicio, $data_fim, (int)$cia_id);
	if(is_string($data_inicio)) $data_inicio = new CData($data_inicio);
	if(is_string($data_fim)) $data_fim = new CData($data_fim);
	}
	
if (!$data_inicio) $data_inicio= new CData();
if (!$data_fim) $data_fim = new CData();

echo '<input name="avaliacao_inicio" id="avaliacao_inicio" type="hidden" value="'.$data_inicio->format('%Y-%m-%d %H:%M:%S').'" />';
echo '<input name="avaliacao_fim" id="avaliacao_fim" type="hidden" value="'.$data_fim->format('%Y-%m-%d %H:%M:%S').'" />';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Data de In�cio', 'Digite ou escolha no calend�rio a data prov�vel de in�cio.').'Data de in�cio:'.dicaF().'</td><td style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="oculto_avaliacao_inicio" id="oculto_avaliacao_inicio" value="'.($data_inicio ? $data_inicio->format("%Y-%m-%d") : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\', \'oculto_avaliacao_inicio\');" value="'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data de In�cio', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data prov�vel de in�cio.').'<a href="javascript: void(0);" ><img src="'.acharImagem('calendario.gif').'" id="f_btn1" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" />'.dicaF().'</a>'.dica('Hora do In�cio', 'Selecione na caixa de sele��o a hora do �nicio d'.$config['genero_tarefa'].' '.$config['tarefa']). selecionaVetor($horas, 'inicio_hora', 'size="1" onchange="CompararDatas();" class="texto"', $data_inicio->getHour()).' : '.dica('Minutos do In�cio', 'Selecione na caixa de sele��o os minutos do �nicio.').selecionaVetor($minutos, 'inicio_minuto', 'size="1" class="texto" onchange="CompararDatas();"', $data_inicio->getMinute()).'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Data de t�rmino', 'Digite ou escolha no calend�rio a data prov�vel de t�rmino').'Data de t�rmino:</td><td style="white-space: nowrap"><input type="hidden" name="oculto_avaliacao_fim" id="oculto_avaliacao_fim" value="'.($data_fim ? $data_fim->format("%Y-%m-%d") : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'data_fim\', \'oculto_avaliacao_fim\');" value="'.($data_fim ? $data_fim->format('%d/%m/%Y') : '').'" class="texto" /><a href="javascript: void(0);" >'.dica('Meta de T�rmino', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data prov�vel de t�rmino.').'<img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" />'.dicaF().'</a>'.dica('Hora do T�rmino', 'Selecione na caixa de sele��o a hora do t�rmino.</p>Caso n�o saiba a hora prov�vel de t�rmino, deixe em branco este campo e clique no bot�o <b>Data de T�rmino</b>').selecionaVetor($horas, 'fim_hora', 'size="1" onchange="CompararDatas();" class="texto"', $data_fim ? $data_fim->getHour() : $fim).' : '.dica('Minutos do T�rmino', 'Selecione na caixa de sele��o os minutos do t�rmino. </p>Caso n�o saiba os minutos prov�veis de t�rmino, deixe em branco este campo e clique no bot�o <b>Data de T�rmino</b>').selecionaVetor($minutos, 'fim_minuto', 'size="1" class="texto" onchange="CompararDatas();"', $data_fim ? $data_fim->getMinute() : '00').'</td></tr>';




echo '<tr><td align="right" width="100">'.dica('Ativa', 'Caso a avalia��o ainda esteja ativa dever� estar marcado este campo.').'Ativa:'.dicaF().'</td><td><input type="checkbox" value="1" name="avaliacao_ativa" '.($obj->avaliacao_ativa || !$avaliacao_id ? 'checked="checked"' : '').' /></td></tr>';


echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milh�es poss�veis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inser��o do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="avaliacao_cor" value="'.($obj->avaliacao_cor ? $obj->avaliacao_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('N�vel de Acesso', 'A avalia��o pode ter cinco n�veis de acesso:<ul><li><b>P�blico</b> - Todos podem ver e editar a avalia��o.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o respons�vel e os designados para a avalia��o podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons�vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o respons�vel pode editar.</li><li><b>Participante I</b> - Somente o respons�vel e os designados para a avalia��o ver e editar a avalia��o</li><li><b>Participantes II</b> - Somente o respons�vel e os designados podem ver e apenas o respons�vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o respons�vel e os designados para a avalia��o podem ver a mesma, e o respons�vel editar.</li></ul>').'N�vel de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($avaliacao_acesso, 'avaliacao_acesso', 'class="texto"', ($avaliacao_id ? $obj->avaliacao_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

$campos_customizados = new CampoCustomizados('avaliacao', $avaliacao_id, 'editar');
$campos_customizados->imprimirHTML();

echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'area_notificar\').style.display) document.getElementById(\'area_notificar\').style.display=\'\'; else document.getElementById(\'area_notificar\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Notificar</b></a></td></tr>';
echo '<tr id="area_notificar" style="display:'.($Aplic->getPref('informa_aberto') ? '' : 'none').'"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';

echo '<tr><td align="right" valign="top" style="white-space: nowrap" width=100>'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($avaliacao_id > 0 ? 'modifica��o' : 'cria��o').' da avalia��o.').'Notificar:'.dicaF().'</td>';
echo '<td>';
echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Respons�vel', 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para o respons�vel.').'<label for="email_responsavel">Respons�vel</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados', 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para os designados.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de di�logo onde poder� selecionar outras pessoas que ser�o informadas por e-mail sobre este registro da avalia��o.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinat�rios Extra', 'Preencha neste campo os e-mail, separados por v�rgula, dos destinat�rios extras que ser�o avisados.').'Destinat�rios extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';


echo '</table></fieldset></td></tr>';

echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($avaliacao_id ? 'edi��o' : 'cria��o').' da avalia��o.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script type="text/javascript">

function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('avaliacao_cia').value+'&cias_id_selecionadas='+document.getElementById('avaliacao_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.avaliacao_cias.value = organizacao_id_string;
	document.getElementById('avaliacao_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('avaliacao_cias').value);
	__buildTooltip();
	}

var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "oculto_avaliacao_inicio",
	date :  <?php echo $data_inicio->format("%Y-%m-%d")?>,
	selection: <?php echo $data_inicio->format("%Y-%m-%d")?>,
  onSelect: function(cal1) {
	  var date = cal1.selection.get();
	  if (date){
	  	date = Calendario.intToDate(date);
	    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("oculto_avaliacao_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
	    CompararDatas();
	    }
		cal1.hide();
		}
	});

var cal2 = Calendario.setup({
	trigger : "f_btn2",
  inputField : "oculto_avaliacao_fim",
	date : <?php echo $data_fim->format("%Y-%m-%d")?>,
	selection : <?php echo $data_fim->format("%Y-%m-%d")?>,
  onSelect : function(cal2) {
	  var date = cal2.selection.get();
	  if (date){
	    date = Calendario.intToDate(date);
	    document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("oculto_avaliacao_fim").value = Calendario.printDate(date, "%Y-%m-%d");
	    CompararDatas();
	    }
		cal2.hide();
		}
	});


function setData( frm_nome, f_data,  f_data_real){
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada n�o corresponde ao formato padr�o. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		}
		else {
	  	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
	  	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
	    campo_data.style.backgroundColor = '';

			//data final fazer ao menos no mesmo dia da inicial
			CompararDatas();
			}
		}
	else campo_data_real.value = '';
	}


function CompararDatas(){
    var str1 = document.getElementById("data_inicio").value;
    var str2 = document.getElementById("data_fim").value;
    var dt1  = parseInt(str1.substring(0,2),10);
    var mon1 = parseInt(str1.substring(3,5),10);
    var yr1  = parseInt(str1.substring(6,10),10);
    var dt2  = parseInt(str2.substring(0,2),10);
    var mon2 = parseInt(str2.substring(3,5),10);
    var yr2  = parseInt(str2.substring(6,10),10);
    var date1 = new Date(yr1, mon1, dt1);
    var date2 = new Date(yr2, mon2, dt2);
    if(date2 < date1){
      document.getElementById("data_fim").value=document.getElementById("data_inicio").value;
      document.getElementById("oculto_avaliacao_fim").value=document.getElementById("oculto_avaliacao_inicio").value;
    	}
   }

function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(contato_id_string) {
	if (!contato_id_string) contato_id_string = '';
	document.getElementById('email_outro').value = contato_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var objetivo_emails = document.getElementById('avaliacao_usuarios');
	var lista_email = email_outro.value.split(',');
	lista_email.sort();
	var vetor_saida = new Array();
	var ultimo_elem = -1;
	for (var i = 0, i_cmp = lista_email.length; i < i_cmp; i++) {
		if (lista_email[i] == ultimo_elem) continue;
		ultimo_elem = lista_email[i];
		vetor_saida.push(lista_email[i]);
		}
	email_outro.value = vetor_saida.join();
	}


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Respons�vel', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('avaliacao_cia').value+'&usuario_id='+document.getElementById('avaliacao_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('avaliacao_cia').value+'&usuario_id='+document.getElementById('avaliacao_responsavel').value, 'Respons�vel','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}


function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('avaliacao_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function mudar_om(){
	var cia_id=document.getElementById('avaliacao_cia').value;
	xajax_selecionar_om_ajax(cia_id,'avaliacao_cia','combo_cia', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om();"');
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir esta avalia��o?")) {
		var f = document.env;
		f.excluir.value=1;
		f.a.value='fazer_sql';
		f.modulo.value='avaliacao';
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.avaliacao_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.avaliacao_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.avaliacao_nome.value.length < 3) {
		alert('Escreva um nome v�lido');
		f.avaliacao_nome.focus();
		}
	else {
		document.getElementById('avaliacao_inicio').value=document.getElementById('oculto_avaliacao_inicio').value+' '+document.getElementById('inicio_hora').value+':'+document.getElementById('inicio_minuto').value+':00';
		document.getElementById('avaliacao_fim').value=document.getElementById('oculto_avaliacao_fim').value+' '+document.getElementById('fim_hora').value+':'+document.getElementById('fim_minuto').value+':00';
		f.salvar.value=1;
		f.submit();
		}
	}



var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('avaliacao_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('avaliacao_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.avaliacao_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}

var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('avaliacao_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('avaliacao_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.avaliacao_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}




function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('avaliacao_dept').value+'&cia_id='+document.getElementById('avaliacao_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('avaliacao_dept').value+'&cia_id='+document.getElementById('avaliacao_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('avaliacao_cia').value=cia_id;
	document.getElementById('avaliacao_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}



function mostrar(){
	limpar_tudo();
	esconder_tipo();
	if (document.getElementById('tipo_relacao').value){
		document.getElementById(document.getElementById('tipo_relacao').value).style.display='';
		}
	}

function esconder_tipo(){
	document.getElementById('projeto').style.display='none';
	document.getElementById('tarefa').style.display='none';
	document.getElementById('perspectiva').style.display='none';
	document.getElementById('tema').style.display='none';
	document.getElementById('objetivo').style.display='none';	
	document.getElementById('fator').style.display='none';	
	document.getElementById('estrategia').style.display='none';
	document.getElementById('meta').style.display='none';
	document.getElementById('pratica').style.display='none';
	document.getElementById('acao').style.display='none';
	document.getElementById('canvas').style.display='none';
	document.getElementById('risco').style.display='none';
	document.getElementById('risco_resposta').style.display='none';
	document.getElementById('indicador').style.display='none';
	document.getElementById('calendario').style.display='none';
	document.getElementById('monitoramento').style.display='none';
	document.getElementById('ata').style.display='none';
	document.getElementById('mswot').style.display='none';
	document.getElementById('swot').style.display='none';
	document.getElementById('operativo').style.display='none';
	document.getElementById('instrumento').style.display='none';
	document.getElementById('recurso').style.display='none';
	document.getElementById('problema').style.display='none';
	document.getElementById('demanda').style.display='none';
	document.getElementById('programa').style.display='none';
	document.getElementById('licao').style.display='none';
	document.getElementById('evento').style.display='none';
	document.getElementById('link').style.display='none';
	document.getElementById('avaliacao').style.display='none';
	document.getElementById('tgn').style.display='none';
	document.getElementById('brainstorm').style.display='none';
	document.getElementById('gut').style.display='none';
	document.getElementById('causa_efeito').style.display='none';
	document.getElementById('arquivo').style.display='none';
	document.getElementById('forum').style.display='none';
	document.getElementById('checklist').style.display='none';
	document.getElementById('agenda').style.display='none';
	document.getElementById('agrupamento').style.display='none';
	document.getElementById('patrocinador').style.display='none';
	document.getElementById('template').style.display='none';
	document.getElementById('painel').style.display='none';
	document.getElementById('painel_odometro').style.display='none';
	document.getElementById('painel_composicao').style.display='none';
	document.getElementById('tr').style.display='none';
	document.getElementById('me').style.display='none';
	document.getElementById('acao_item').style.display='none';
	document.getElementById('beneficio').style.display='none';
	document.getElementById('painel_slideshow').style.display='none';
	document.getElementById('projeto_viabilidade').style.display='none';
	document.getElementById('projeto_abertura').style.display='none';
	document.getElementById('plano_gestao').style.display='none';
	document.getElementById('ssti').style.display='none';
	document.getElementById('laudo').style.display='none';
	document.getElementById('trelo').style.display='none';
	document.getElementById('trelo_cartao').style.display='none';
	document.getElementById('pdcl').style.display='none';
	document.getElementById('pdcl_item').style.display='none';
	document.getElementById('os').style.display='none';
	
	}
	
	

<?php  if ($Aplic->profissional) { ?>
	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('avaliacao_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('avaliacao_cia').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.avaliacao_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		incluir_relacionado();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('avaliacao_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.avaliacao_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		incluir_relacionado();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('avaliacao_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('avaliacao_cia').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.avaliacao_template.value = chave;
		document.env.template_nome.value = valor;
		incluir_relacionado();
		}		
<?php } ?>

function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('avaliacao_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.avaliacao_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	incluir_relacionado();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('avaliacao_cia').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.avaliacao_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	incluir_relacionado();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('avaliacao_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.avaliacao_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	incluir_relacionado();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('avaliacao_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.avaliacao_tema.value = chave;
	document.env.tema_nome.value = valor;
	incluir_relacionado();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('avaliacao_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.avaliacao_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('avaliacao_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.avaliacao_fator.value = chave;
	document.env.fator_nome.value = valor;
	incluir_relacionado();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('avaliacao_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.avaliacao_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	incluir_relacionado();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('avaliacao_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.avaliacao_meta.value = chave;
	document.env.meta_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('avaliacao_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.avaliacao_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	incluir_relacionado();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('avaliacao_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('avaliacao_cia').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.avaliacao_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	incluir_relacionado();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('avaliacao_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.avaliacao_acao.value = chave;
	document.env.acao_nome.value = valor;
	incluir_relacionado();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('avaliacao_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.avaliacao_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('avaliacao_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.avaliacao_risco.value = chave;
	document.env.risco_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('avaliacao_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.avaliacao_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('avaliacao_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('avaliacao_cia').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.avaliacao_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	incluir_relacionado();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('avaliacao_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('avaliacao_cia').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.avaliacao_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reuni�o', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('avaliacao_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.avaliacao_ata.value = chave;
	document.env.ata_nome.value = valor;
	incluir_relacionado();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 500, 500, 'm=swot&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('avaliacao_cia').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.avaliacao_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Cam�po SWOT', 500, 500, 'm=swot&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('avaliacao_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.avaliacao_swot.value = chave;
	document.env.swot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('avaliacao_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('avaliacao_cia').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.avaliacao_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jur�dico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('avaliacao_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('avaliacao_cia').value, 'Instrumento Jur�dico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.avaliacao_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('avaliacao_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('avaliacao_cia').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.avaliacao_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('avaliacao_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.avaliacao_problema.value = chave;
	document.env.problema_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('avaliacao_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('avaliacao_cia').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.avaliacao_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('avaliacao_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.avaliacao_programa.value = chave;
	document.env.programa_nome.value = valor;
	incluir_relacionado();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('avaliacao_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.avaliacao_licao.value = chave;
	document.env.licao_nome.value = valor;
	incluir_relacionado();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('avaliacao_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('avaliacao_cia').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.avaliacao_evento.value = chave;
	document.env.evento_nome.value = valor;
	incluir_relacionado();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('avaliacao_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('avaliacao_cia').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.avaliacao_link.value = chave;
	document.env.link_nome.value = valor;
	incluir_relacionado();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avalia��o', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('avaliacao_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('avaliacao_cia').value, 'Avalia��o','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.avaliacao_avaliacao.value = chave;
	document.env.gestao_avaliacao_nome.value = valor;
	incluir_relacionado();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('avaliacao_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.avaliacao_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('avaliacao_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('avaliacao_cia').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.avaliacao_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	incluir_relacionado();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz G.U.T.', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('avaliacao_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('avaliacao_cia').value, 'Matriz G.U.T.','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.avaliacao_gut.value = chave;
	document.env.gut_nome.value = valor;
	incluir_relacionado();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('avaliacao_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('avaliacao_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.avaliacao_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	incluir_relacionado();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('avaliacao_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('avaliacao_cia').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.avaliacao_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	incluir_relacionado();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('F�rum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('avaliacao_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('avaliacao_cia').value, 'F�rum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.avaliacao_forum.value = chave;
	document.env.forum_nome.value = valor;
	incluir_relacionado();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('avaliacao_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('avaliacao_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.avaliacao_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	incluir_relacionado();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('avaliacao_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('avaliacao_cia').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.avaliacao_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	incluir_relacionado();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('avaliacao_cia').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('avaliacao_cia').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.avaliacao_painel.value = chave;
	document.env.painel_nome.value = valor;
	incluir_relacionado();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Od�metro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('avaliacao_cia').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('avaliacao_cia').value, 'Od�metro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.avaliacao_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	incluir_relacionado();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composi��o de Pain�is', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('avaliacao_cia').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('avaliacao_cia').value, 'Composi��o de Pain�is','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.avaliacao_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('avaliacao_cia').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.avaliacao_tr.value = chave;
	document.env.tr_nome.value = valor;
	incluir_relacionado();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('avaliacao_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.avaliacao_me.value = chave;
	document.env.me_nome.value = valor;
	incluir_relacionado();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('avaliacao_cia').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('avaliacao_cia').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.avaliacao_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	incluir_relacionado();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('avaliacao_cia').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.avaliacao_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	incluir_relacionado();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composi��es', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('avaliacao_cia').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('avaliacao_cia').value, 'Slideshow de Composi��es','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.avaliacao_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	incluir_relacionado();
	}	

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('avaliacao_cia').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('avaliacao_cia').value, 'Estudo de Viabilidade','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.avaliacao_projeto_viabilidade.value = chave;
	document.env.projeto_viabilidade_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('avaliacao_cia').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('avaliacao_cia').value, 'Termo de Abertura','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.avaliacao_projeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	incluir_relacionado();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estrat�gico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('avaliacao_cia').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('avaliacao_cia').value, 'Planejamento Estrat�gico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.avaliacao_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	incluir_relacionado();
	}		

	
function popSSTI() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('avaliacao_cia').value, window.setSSTI, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSSTI(chave, valor){
	limpar_tudo();
	document.env.avaliacao_ssti.value = chave;
	document.env.ssti_nome.value = valor;
	incluir_relacionado();
	}	
				
function popLaudo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('avaliacao_cia').value, window.setLaudo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLaudo(chave, valor){
	limpar_tudo();
	document.env.avaliacao_laudo.value = chave;
	document.env.laudo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popTrelo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('avaliacao_cia').value, window.setTrelo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTrelo(chave, valor){
	limpar_tudo();
	document.env.avaliacao_trelo.value = chave;
	document.env.trelo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTreloCartao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('avaliacao_cia').value, window.setTreloCartao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTreloCartao(chave, valor){
	limpar_tudo();
	document.env.avaliacao_trelo_cartao.value = chave;
	document.env.trelo_cartao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPDCL() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('avaliacao_cia').value, window.setPDCL, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCL(chave, valor){
	limpar_tudo();
	document.env.avaliacao_pdcl.value = chave;
	document.env.pdcl_nome.value = valor;
	incluir_relacionado();
	}				
	
function pop_pdcl_item() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=set_pdcl_item&tabela=pdcl_item&cia_id='+document.getElementById('avaliacao_cia').value, window.set_pdcl_item, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=set_pdcl_item&tabela=pdcl_item&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function set_pdcl_item(chave, valor){
	limpar_tudo();
	document.env.avaliacao_pdcl_item.value = chave;
	document.env.pdcl_item_nome.value = valor;
	incluir_relacionado();
	}	
	
function pop_os() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["os"])?>', 1000, 700, 'm=os&a=os_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=set_os&tabela=os&cia_id='+document.getElementById('avaliacao_cia').value, window.set_os, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=set_os&tabela=os&cia_id='+document.getElementById('avaliacao_cia').value, '<?php echo ucfirst($config["os"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function set_os(chave, valor){
	limpar_tudo();
	document.env.avaliacao_os.value = chave;
	document.env.os_nome.value = valor;
	incluir_relacionado();
	}		
	
function limpar_tudo(){
	document.env.projeto_nome.value = '';
	document.env.avaliacao_projeto.value = null;
	document.env.avaliacao_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.avaliacao_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.avaliacao_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.avaliacao_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.avaliacao_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.avaliacao_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.avaliacao_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.avaliacao_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.avaliacao_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.avaliacao_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.avaliacao_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.avaliacao_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.avaliacao_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.avaliacao_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.avaliacao_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.avaliacao_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.avaliacao_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.avaliacao_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.avaliacao_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.avaliacao_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.avaliacao_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.avaliacao_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.avaliacao_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.avaliacao_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.avaliacao_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.avaliacao_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.avaliacao_link.value = null;
	document.env.link_nome.value = '';
	document.env.avaliacao_avaliacao.value = null;
	document.env.gestao_avaliacao_nome.value = '';
	document.env.avaliacao_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.avaliacao_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.avaliacao_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.avaliacao_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.avaliacao_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.avaliacao_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.avaliacao_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.avaliacao_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.avaliacao_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.avaliacao_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.avaliacao_template.value = null;
	document.env.template_nome.value = '';
	document.env.avaliacao_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.avaliacao_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.avaliacao_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.avaliacao_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.avaliacao_me.value = null;
	document.env.me_nome.value = '';
	document.env.avaliacao_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.avaliacao_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.avaliacao_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	document.env.avaliacao_projeto_viabilidade.value = null;
	document.env.projeto_viabilidade_nome.value = '';
	document.env.avaliacao_projeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.avaliacao_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	document.env.avaliacao_ssti.value = null;
	document.env.ssti_nome.value = '';
	document.env.avaliacao_laudo.value = null;
	document.env.laudo_nome.value = '';
	document.env.avaliacao_trelo.value = null;
	document.env.trelo_nome.value = '';
	document.env.avaliacao_trelo_cartao.value = null;
	document.env.trelo_cartao_nome.value = '';
	document.env.avaliacao_pdcl.value = null;
	document.env.pdcl_nome.value = '';
	document.env.avaliacao_pdcl_item.value = null;
	document.env.pdcl_item_nome.value = '';	
	document.env.avaliacao_os.value = null;
	document.env.os_nome.value = '';			
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('avaliacao_id').value,
	document.getElementById('uuid').value,
	f.avaliacao_projeto.value,
	f.avaliacao_tarefa.value,
	f.avaliacao_perspectiva.value,
	f.avaliacao_tema.value,
	f.avaliacao_objetivo.value,
	f.avaliacao_fator.value,
	f.avaliacao_estrategia.value,
	f.avaliacao_meta.value,
	f.avaliacao_pratica.value,
	f.avaliacao_acao.value,
	f.avaliacao_canvas.value,
	f.avaliacao_risco.value,
	f.avaliacao_risco_resposta.value,
	f.avaliacao_indicador.value,
	f.avaliacao_calendario.value,
	f.avaliacao_monitoramento.value,
	f.avaliacao_ata.value,
	f.avaliacao_mswot.value,
	f.avaliacao_swot.value,
	f.avaliacao_operativo.value,
	f.avaliacao_instrumento.value,
	f.avaliacao_recurso.value,
	f.avaliacao_problema.value,
	f.avaliacao_demanda.value,
	f.avaliacao_programa.value,
	f.avaliacao_licao.value,
	f.avaliacao_evento.value,
	f.avaliacao_link.value,
	f.avaliacao_avaliacao.value,
	f.avaliacao_tgn.value,
	f.avaliacao_brainstorm.value,
	f.avaliacao_gut.value,
	f.avaliacao_causa_efeito.value,
	f.avaliacao_arquivo.value,
	f.avaliacao_forum.value,
	f.avaliacao_checklist.value,
	f.avaliacao_agenda.value,
	f.avaliacao_agrupamento.value,
	f.avaliacao_patrocinador.value,
	f.avaliacao_template.value,
	f.avaliacao_painel.value,
	f.avaliacao_painel_odometro.value,
	f.avaliacao_painel_composicao.value,
	f.avaliacao_tr.value,
	f.avaliacao_me.value,
	f.avaliacao_acao_item.value,
	f.avaliacao_beneficio.value,
	f.avaliacao_painel_slideshow.value,
	f.avaliacao_projeto_viabilidade.value,
	f.avaliacao_projeto_abertura.value,
	f.avaliacao_plano_gestao.value,
	f.avaliacao_ssti.value,
	f.avaliacao_laudo.value,
	f.avaliacao_trelo.value,
	f.avaliacao_trelo_cartao.value,
	f.avaliacao_pdcl.value,
	f.avaliacao_pdcl_item.value,
	f.avaliacao_os.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(avaliacao_gestao_id){
	xajax_excluir_gestao(document.getElementById('avaliacao_id').value, document.getElementById('uuid').value, avaliacao_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, avaliacao_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, avaliacao_gestao_id, direcao, document.getElementById('avaliacao_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$avaliacao_id && (
	$avaliacao_tarefa || 
	$avaliacao_projeto || 
	$avaliacao_perspectiva || 
	$avaliacao_tema || 
	$avaliacao_objetivo || 
	$avaliacao_fator || 
	$avaliacao_estrategia || 
	$avaliacao_meta || 
	$avaliacao_pratica || 
	$avaliacao_acao || 
	$avaliacao_canvas || 
	$avaliacao_risco || 
	$avaliacao_risco_resposta || 
	$avaliacao_indicador || 
	$avaliacao_calendario || 
	$avaliacao_monitoramento || 
	$avaliacao_ata || 
	$avaliacao_mswot || 
	$avaliacao_swot || 
	$avaliacao_operativo || 
	$avaliacao_instrumento || 
	$avaliacao_recurso || 
	$avaliacao_problema || 
	$avaliacao_demanda || 
	$avaliacao_programa || 
	$avaliacao_licao || 
	$avaliacao_evento || 
	$avaliacao_link || 
	$avaliacao_avaliacao || 
	$avaliacao_tgn || 
	$avaliacao_brainstorm || 
	$avaliacao_gut || 
	$avaliacao_causa_efeito || 
	$avaliacao_arquivo || 
	$avaliacao_forum || 
	$avaliacao_checklist || 
	$avaliacao_agenda || 
	$avaliacao_agrupamento || 
	$avaliacao_patrocinador || 
	$avaliacao_template || 
	$avaliacao_painel || 
	$avaliacao_painel_odometro || 
	$avaliacao_painel_composicao || 
	$avaliacao_tr || 
	$avaliacao_me || 
	$avaliacao_acao_item || 
	$avaliacao_beneficio || 
	$avaliacao_painel_slideshow || 
	$avaliacao_projeto_viabilidade || 
	$avaliacao_projeto_abertura || 
	$avaliacao_plano_gestao|| 
	$avaliacao_ssti || 
	$avaliacao_laudo || 
	$avaliacao_trelo || 
	$avaliacao_trelo_cartao || 
	$avaliacao_pdcl || 
	$avaliacao_pdcl_item || 
	$avaliacao_os
	)) echo 'incluir_relacionado();';
	?>	

</script>

