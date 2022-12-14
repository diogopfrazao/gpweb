<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR'))	die('Voc? n?o deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf, $dialogo;
echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();

//ajuste de permiss?o para sub-m?dulo projetos
global $podeAcessar, $podeEditar, $podeAdicionar, $podeExcluir, $podeAprovar;
list($podeAcessar, $podeEditar, $podeAdicionar, $podeExcluir, $podeAprovar)  = listaPermissoes('projetos', 'projetos_lista');

$social=$Aplic->modulo_ativo('social');
if ($social) require_once BASE_DIR.'/modulos/social/social.class.php';

$sql = new BDConsulta;

$projeto_id=getParam($_REQUEST, 'projeto_id', null);
$cia_id=getParam($_REQUEST, 'cia_id', null);
$contato_id=getParam($_REQUEST, 'contato_id', null);


$projsEstrutura = getProjetos();
unset($projsEstrutura[$projeto_id]);
$projetosEstruturados = unirVetores(array('0' => array(0 => 0, 1 => '(Sem superiores)', 2 => '')), $projsEstrutura);
$projStatus = getSisValor('StatusProjeto');
$projTipo = getSisValor('TipoProjeto');



$linha = new CProjeto();
$linha->load($projeto_id, false);

$paises = array('' => '') + getPais('Paises');

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();

$cidades=array(''=>'');
$sql->adTabela('municipios');
$sql->adCampo('municipio_id, municipio_nome');
$sql->adOnde('estado_sigla=\''.$linha->projeto_estado.'\'');
$sql->adOrdem('municipio_nome');
$cidades+= $sql->listaVetorChave('municipio_id', 'municipio_nome');
$sql->limpar();

$podeEditar=$linha->podeEditar();
if ((!$podeEditar && $projeto_id) || (!$podeAdicionar && !$projeto_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!$linha->projeto_id && $projeto_id) {
	$Aplic->setMsg('ID d'.$config['genero_projeto'].' '.$config['projeto'].' inv?lid'.$config['genero_projeto'], UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=projetos');
	}
if (!$projeto_id && $cia_id) $linha->projeto_cia = $cia_id;

$tarefasCriticas = ($projeto_id  ? $linha->getTarefasCriticas() : null);
$PrioridadeProjeto = getSisValor('PrioridadeProjeto');
$projeto_encerramento = intval($linha->projeto_encerramento) ? new CData($linha->projeto_encerramento) :  new CData(date("Y-m-d"));
$data_inicio = intval($linha->projeto_data_inicio) ? new CData($linha->projeto_data_inicio) :  new CData(date("Y-m-d"));
$data_fim = intval($linha->projeto_data_fim) ? new CData($linha->projeto_data_fim) : new CData(date("Y-m-d"));
$data_fim_atual = intval(isset($tarefasCriticas[0]['tarefa_fim']) && $tarefasCriticas[0]['tarefa_fim']) ? new CData($tarefasCriticas[0]['tarefa_fim']) : null;
$estilo = (($data_fim_atual > $data_fim) && !empty($data_fim)) ? 'style="color:red; font-weight:bold"' : '';
$ttl = $projeto_id ? 'Editar '.ucfirst($config['projeto']) : 'Criar '.ucfirst($config['projeto']);

if (!$dialogo){
	$botoesTitulo = new CBlocoTitulo($ttl, 'projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	}


$cia_id = $linha->projeto_cia;

$depts_selecionados = array();
$municipios_selecionados = array();
$cias_selecionadas = array();
if ($projeto_id) {
	$sql->adTabela('projeto_depts');
	$sql->adCampo('departamento_id');
	$sql->adOnde('projeto_id = '.(int)$projeto_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('projeto_cia');
	$sql->adCampo('projeto_cia_cia');
	$sql->adOnde('projeto_cia_projeto = '.(int)$projeto_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('municipio_lista');
	$sql->adCampo('DISTINCT municipio_lista_municipio');
	$sql->adOnde('municipio_lista_projeto = '.(int)$projeto_id);
	$municipios_selecionados = $sql->carregarColuna();
	$sql->limpar();
	}
$depts_contagem = 0;



$projeto_projeto=getParam($_REQUEST, 'projeto_projeto', null);
$projeto_tarefa=getParam($_REQUEST, 'projeto_tarefa', null);
$projeto_perspectiva=getParam($_REQUEST, 'projeto_perspectiva', null);
$projeto_tema=getParam($_REQUEST, 'projeto_tema', null);
$projeto_objetivo=getParam($_REQUEST, 'projeto_objetivo', null);
$projeto_fator=getParam($_REQUEST, 'projeto_fator', null);
$projeto_estrategia=getParam($_REQUEST, 'projeto_estrategia', null);
$projeto_meta=getParam($_REQUEST, 'projeto_meta', null);
$projeto_pratica=getParam($_REQUEST, 'projeto_pratica', null);
$projeto_acao=getParam($_REQUEST, 'projeto_acao', null);
$projeto_canvas=getParam($_REQUEST, 'projeto_canvas', null);
$projeto_risco=getParam($_REQUEST, 'projeto_risco', null);
$projeto_risco_resposta=getParam($_REQUEST, 'projeto_risco_resposta', null);
$projeto_indicador=getParam($_REQUEST, 'projeto_indicador', null);
$projeto_calendario=getParam($_REQUEST, 'projeto_calendario', null);
$projeto_monitoramento=getParam($_REQUEST, 'projeto_monitoramento', null);
$projeto_ata=getParam($_REQUEST, 'projeto_ata', null);
$projeto_mswot=getParam($_REQUEST, 'projeto_mswot', null);
$projeto_swot=getParam($_REQUEST, 'projeto_swot', null);
$projeto_operativo=getParam($_REQUEST, 'projeto_operativo', null);
$projeto_instrumento=getParam($_REQUEST, 'projeto_instrumento', null);
$projeto_recurso=getParam($_REQUEST, 'projeto_recurso', null);
$projeto_problema=getParam($_REQUEST, 'projeto_problema', null);
$projeto_demanda=getParam($_REQUEST, 'projeto_demanda', null);
$projeto_programa=getParam($_REQUEST, 'projeto_programa', null);
$projeto_licao=getParam($_REQUEST, 'projeto_licao', null);
$projeto_evento=getParam($_REQUEST, 'projeto_evento', null);
$projeto_link=getParam($_REQUEST, 'projeto_link', null);
$projeto_avaliacao=getParam($_REQUEST, 'projeto_avaliacao', null);
$projeto_tgn=getParam($_REQUEST, 'projeto_tgn', null);
$projeto_brainstorm=getParam($_REQUEST, 'projeto_brainstorm', null);
$projeto_gut=getParam($_REQUEST, 'projeto_gut', null);
$projeto_causa_efeito=getParam($_REQUEST, 'projeto_causa_efeito', null);
$projeto_arquivo=getParam($_REQUEST, 'projeto_arquivo', null);
$projeto_forum=getParam($_REQUEST, 'projeto_forum', null);
$projeto_checklist=getParam($_REQUEST, 'projeto_checklist', null);
$projeto_agenda=getParam($_REQUEST, 'projeto_agenda', null);
$projeto_agrupamento=getParam($_REQUEST, 'projeto_agrupamento', null);
$projeto_patrocinador=getParam($_REQUEST, 'projeto_patrocinador', null);
$projeto_template=getParam($_REQUEST, 'projeto_template', null);
$projeto_painel=getParam($_REQUEST, 'projeto_painel', null);
$projeto_painel_odometro=getParam($_REQUEST, 'projeto_painel_odometro', null);
$projeto_painel_composicao=getParam($_REQUEST, 'projeto_painel_composicao', null);
$projeto_tr=getParam($_REQUEST, 'projeto_tr', null);
$projeto_me=getParam($_REQUEST, 'projeto_me', null);
$projeto_acao_item=getParam($_REQUEST, 'projeto_acao_item', null);
$projeto_beneficio=getParam($_REQUEST, 'projeto_beneficio', null);
$projeto_painel_slideshow=getParam($_REQUEST, 'projeto_painel_slideshow', null);
$projeto_projeto_viabilidade=getParam($_REQUEST, 'projeto_projeto_viabilidade', null);
$projeto_projeto_abertura=getParam($_REQUEST, 'projeto_projeto_abertura', null);
$projeto_plano_gestao=getParam($_REQUEST, 'projeto_plano_gestao', null);
$projeto_ssti=getParam($_REQUEST, 'projeto_ssti', null);
$projeto_laudo=getParam($_REQUEST, 'projeto_laudo', null);
$projeto_trelo=getParam($_REQUEST, 'projeto_trelo', null);
$projeto_trelo_cartao=getParam($_REQUEST, 'projeto_trelo_cartao', null);
$projeto_pdcl=getParam($_REQUEST, 'projeto_pdcl', null);
$projeto_pdcl_item=getParam($_REQUEST, 'projeto_pdcl_item', null);
$projeto_os=getParam($_REQUEST, 'projeto_os', null);

if (
		$projeto_projeto || 
		$projeto_tarefa || 
		$projeto_perspectiva || 
		$projeto_tema || 
		$projeto_objetivo || 
		$projeto_fator || 
		$projeto_estrategia || 
		$projeto_meta || 
		$projeto_pratica || 
		$projeto_acao || 
		$projeto_canvas || 
		$projeto_risco || 
		$projeto_risco_resposta || 
		$projeto_indicador || 
		$projeto_calendario || 
		$projeto_monitoramento || 
		$projeto_ata || 
		$projeto_mswot || 
		$projeto_swot || 
		$projeto_operativo || 
		$projeto_instrumento || 
		$projeto_recurso || 
		$projeto_problema || 
		$projeto_demanda || 
		$projeto_programa || 
		$projeto_licao || 
		$projeto_evento || 
		$projeto_link || 
		$projeto_avaliacao || 
		$projeto_tgn || 
		$projeto_brainstorm || 
		$projeto_gut || 
		$projeto_causa_efeito || 
		$projeto_arquivo || 
		$projeto_forum || 
		$projeto_checklist || 
		$projeto_agenda || 
		$projeto_agrupamento || 
		$projeto_patrocinador || 
		$projeto_template || 
		$projeto_painel || 
		$projeto_painel_odometro || 
		$projeto_painel_composicao || 
		$projeto_tr || 
		$projeto_me || 
		$projeto_acao_item || 
		$projeto_beneficio || 
		$projeto_painel_slideshow || 
		$projeto_projeto_viabilidade || 
		$projeto_projeto_abertura || 
		$projeto_plano_gestao|| 
		$projeto_ssti || 
		$projeto_laudo || 
		$projeto_trelo || 
		$projeto_trelo_cartao || 
		$projeto_pdcl || 
		$projeto_pdcl_item ||
		$projeto_os
	){
	$sql->adTabela('cias');
	if ($projeto_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
	elseif ($projeto_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
	elseif ($projeto_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
	elseif ($projeto_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
	elseif ($projeto_objetivo) $sql->esqUnir('objetivo','objetivo','objetivo_cia=cias.cia_id');
	elseif ($projeto_fator) $sql->esqUnir('fator','fator','fator_cia=cias.cia_id');
	elseif ($projeto_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
	elseif ($projeto_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
	elseif ($projeto_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
	elseif ($projeto_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
	elseif ($projeto_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
	elseif ($projeto_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
	elseif ($projeto_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
	elseif ($projeto_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
	elseif ($projeto_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
	elseif ($projeto_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
	elseif ($projeto_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
	elseif ($projeto_mswot) $sql->esqUnir('mswot','mswot','mswot_cia=cias.cia_id');
	elseif ($projeto_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
	elseif ($projeto_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
	elseif ($projeto_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
	elseif ($projeto_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
	elseif ($projeto_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
	elseif ($projeto_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
	elseif ($projeto_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
	elseif ($projeto_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
	elseif ($projeto_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
	elseif ($projeto_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
	elseif ($projeto_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
	elseif ($projeto_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
	elseif ($projeto_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
	elseif ($projeto_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
	elseif ($projeto_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
	elseif ($projeto_arquivo) $sql->esqUnir('arquivo','arquivo','arquivo_cia=cias.cia_id');
	elseif ($projeto_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
	elseif ($projeto_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
	elseif ($projeto_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
	elseif ($projeto_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
	elseif ($projeto_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
	elseif ($projeto_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
	elseif ($projeto_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
	elseif ($projeto_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
	elseif ($projeto_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
	elseif ($projeto_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
	elseif ($projeto_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');
	elseif ($projeto_acao_item) $sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao_item_cia=cias.cia_id');
	elseif ($projeto_beneficio) $sql->esqUnir('beneficio','beneficio','beneficio_cia=cias.cia_id');
	elseif ($projeto_painel_slideshow) $sql->esqUnir('painel_slideshow','painel_slideshow','painel_slideshow_cia=cias.cia_id');
	elseif ($projeto_projeto_viabilidade) $sql->esqUnir('projeto_viabilidade','projeto_viabilidade','projeto_viabilidade_cia=cias.cia_id');
	elseif ($projeto_projeto_abertura) $sql->esqUnir('projeto_abertura','projeto_abertura','projeto_abertura_cia=cias.cia_id');
	elseif ($projeto_plano_gestao) $sql->esqUnir('plano_gestao','plano_gestao','pg_cia=cias.cia_id');
	elseif ($projeto_ssti) $sql->esqUnir('ssti','ssti','ssti_cia=cias.cia_id');
	elseif ($projeto_laudo) $sql->esqUnir('laudo','laudo','laudo_cia=cias.cia_id');
	elseif ($projeto_trelo) $sql->esqUnir('trelo','trelo','trelo_cia=cias.cia_id');
	elseif ($projeto_trelo_cartao) $sql->esqUnir('trelo_cartao','trelo_cartao','trelo_cartao_cia=cias.cia_id');
	elseif ($projeto_pdcl) $sql->esqUnir('pdcl','pdcl','pdcl_cia=cias.cia_id');
	elseif ($projeto_pdcl_item) $sql->esqUnir('pdcl_item','pdcl_item','pdcl_item_cia=cias.cia_id');
	elseif ($projeto_os) $sql->esqUnir('os','os','os_cia=cias.cia_id');

	if ($projeto_tarefa) $sql->adOnde('tarefa_id = '.(int)$projeto_tarefa);
	elseif ($projeto_projeto) $sql->adOnde('projeto_id = '.(int)$projeto_projeto);
	elseif ($projeto_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$projeto_perspectiva);
	elseif ($projeto_tema) $sql->adOnde('tema_id = '.(int)$projeto_tema);
	elseif ($projeto_objetivo) $sql->adOnde('objetivo_id = '.(int)$projeto_objetivo);
	elseif ($projeto_fator) $sql->adOnde('fator_id = '.(int)$projeto_fator);
	elseif ($projeto_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$projeto_estrategia);
	elseif ($projeto_meta) $sql->adOnde('pg_meta_id = '.(int)$projeto_meta);
	elseif ($projeto_pratica) $sql->adOnde('pratica_id = '.(int)$projeto_pratica);
	elseif ($projeto_acao) $sql->adOnde('plano_acao_id = '.(int)$projeto_acao);
	elseif ($projeto_canvas) $sql->adOnde('canvas_id = '.(int)$projeto_canvas);
	elseif ($projeto_risco) $sql->adOnde('risco_id = '.(int)$projeto_risco);
	elseif ($projeto_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$projeto_risco_resposta);
	elseif ($projeto_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$projeto_indicador);
	elseif ($projeto_calendario) $sql->adOnde('calendario_id = '.(int)$projeto_calendario);
	elseif ($projeto_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$projeto_monitoramento);
	elseif ($projeto_ata) $sql->adOnde('ata_id = '.(int)$projeto_ata);
	elseif ($projeto_mswot) $sql->adOnde('mswot_id = '.(int)$projeto_mswot);
	elseif ($projeto_swot) $sql->adOnde('swot_id = '.(int)$projeto_swot);
	elseif ($projeto_operativo) $sql->adOnde('operativo_id = '.(int)$projeto_operativo);
	elseif ($projeto_instrumento) $sql->adOnde('instrumento_id = '.(int)$projeto_instrumento);
	elseif ($projeto_recurso) $sql->adOnde('recurso_id = '.(int)$projeto_recurso);
	elseif ($projeto_problema) $sql->adOnde('problema_id = '.(int)$projeto_problema);
	elseif ($projeto_demanda) $sql->adOnde('demanda_id = '.(int)$projeto_demanda);
	elseif ($projeto_programa) $sql->adOnde('programa_id = '.(int)$projeto_programa);
	elseif ($projeto_licao) $sql->adOnde('licao_id = '.(int)$projeto_licao);
	elseif ($projeto_evento) $sql->adOnde('evento_id = '.(int)$projeto_evento);
	elseif ($projeto_link) $sql->adOnde('link_id = '.(int)$projeto_link);
	elseif ($projeto_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$projeto_avaliacao);
	elseif ($projeto_tgn) $sql->adOnde('tgn_id = '.(int)$projeto_tgn);
	elseif ($projeto_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$projeto_brainstorm);
	elseif ($projeto_gut) $sql->adOnde('gut_id = '.(int)$projeto_gut);
	elseif ($projeto_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$projeto_causa_efeito);
	elseif ($projeto_arquivo) $sql->adOnde('arquivo_id = '.(int)$projeto_arquivo);
	elseif ($projeto_forum) $sql->adOnde('forum_id = '.(int)$projeto_forum);
	elseif ($projeto_checklist) $sql->adOnde('checklist_id = '.(int)$projeto_checklist);
	elseif ($projeto_agenda) $sql->adOnde('agenda_id = '.(int)$projeto_agenda);
	elseif ($projeto_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$projeto_agrupamento);
	elseif ($projeto_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$projeto_patrocinador);
	elseif ($projeto_template) $sql->adOnde('template_id = '.(int)$projeto_template);
	elseif ($projeto_painel) $sql->adOnde('painel_id = '.(int)$projeto_painel);
	elseif ($projeto_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$projeto_painel_odometro);
	elseif ($projeto_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$projeto_painel_composicao);
	elseif ($projeto_tr) $sql->adOnde('tr_id = '.(int)$projeto_tr);
	elseif ($projeto_me) $sql->adOnde('me_id = '.(int)$projeto_me);
	elseif ($projeto_acao_item) $sql->adOnde('plano_acao_item_id = '.(int)$projeto_acao_item);
	elseif ($projeto_beneficio) $sql->adOnde('beneficio_id = '.(int)$projeto_beneficio);
	elseif ($projeto_painel_slideshow) $sql->adOnde('painel_slideshow_id = '.(int)$projeto_painel_slideshow);
	elseif ($projeto_projeto_viabilidade) $sql->adOnde('projeto_viabilidade_id = '.(int)$projeto_projeto_viabilidade);
	elseif ($projeto_projeto_abertura) $sql->adOnde('projeto_abertura_id = '.(int)$projeto_projeto_abertura);
	elseif ($projeto_plano_gestao) $sql->adOnde('pg_id = '.(int)$projeto_plano_gestao);
	elseif ($projeto_ssti) $sql->adOnde('ssti_id = '.(int)$projeto_ssti);
	elseif ($projeto_laudo) $sql->adOnde('laudo_id = '.(int)$projeto_laudo);
	elseif ($projeto_trelo) $sql->adOnde('trelo_id = '.(int)$projeto_trelo);
	elseif ($projeto_trelo_cartao) $sql->adOnde('trelo_cartao_id = '.(int)$projeto_trelo_cartao);
	elseif ($projeto_pdcl) $sql->adOnde('pdcl_id = '.(int)$projeto_pdcl);
	elseif ($projeto_pdcl_item) $sql->adOnde('pdcl_item_id = '.(int)$projeto_pdcl_item);
	elseif ($projeto_os) $sql->adOnde('os_id = '.(int)$projeto_os);
	$sql->adCampo('cia_id');
	$cia_id = $sql->Resultado();
	$sql->limpar();
	}


$sql->adTabela('pratica_indicador');


if ($Aplic->profissional) {
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adOnde('pratica_indicador_gestao_projeto = '.(int)$projeto_id);
	}
else 	$sql->adOnde('pratica_indicador_projeto = '.(int)$projeto_id);
$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
$sql->limpar();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="projetos" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_projeto_aed" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="projeto_criador" value="'.(!$linha->projeto_criador ? $Aplic->usuario_id : $linha->projeto_criador).'" />';
echo '<input name="projeto_depts" id="projeto_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="projeto_cias"  id="projeto_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input name="projeto_municipios" type="hidden" value="'.implode(',', $municipios_selecionados).'" />';
echo '<input name="wbs" type="hidden" value="'.($dialogo ? 1 : 0).'" />';
echo '<input name="editar_gantt" id="editar_gantt" type="hidden" value="0" />';
echo '<input name="projeto_sequencial" type="hidden" value="'.$linha->projeto_sequencial.'" />';
echo '<input name="projeto_percentagem" type="hidden" value="'.$linha->projeto_percentagem.'" />';
$uuid=($projeto_id ? null : uuid());
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';
//avisar se houve projeto com mesmo nome
echo '<input type="hidden" id="existe_projeto" name="existe_projeto" value="0" />';


echo '<input type="hidden" name="apoio1" id="apoio1" value="" />';
echo '<input type="hidden" name="apoio2" id="apoio2" value="" />';
echo '<input type="hidden" name="apoio3" id="apoio3" value="" />';

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'projeto\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

echo estiloTopoCaixa();
echo '<table cellspacing=1 cellpadding=0 width="100%" class="std">';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Tod'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' necessita ter um nome para identifica??o pelo '.$config['usuarios'].' do Sistema.').'Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']).':'.dicaF().'</td><td width="100%" colspan="2"><input type="text" name="projeto_nome" value="'.$linha->projeto_nome.'" style="width:400px;" onblur="setCurto();" class="texto" '.($dialogo ? 'READONLY': '' ).' />*</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Respons?vel', 'Selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' que ser? encarregad'.$config['genero_organizacao'].' d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['organizacao']).' respons?vel:'.dicaF().'</td><td width="100%" style="white-space: nowrap" colspan="2"><div id="combo_cia">'.selecionar_om($linha->projeto_cia, 'projeto_cia', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om();"').'</div></td></tr>';


if ($Aplic->profissional && isset($exibir['cias']) && $exibir['cias']) {
	$saida_cias='';
	if (is_array($cias_selecionadas) && count($cias_selecionadas)) {
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
	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' est?o envolvid'.$config['genero_organizacao'].' com '.($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}

echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Respons?vel', 'Escolha pressionando o ?cone ? direita qual '.$config['genero_dept'].' '.$config['dept'].' respons?vel por '.($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto'].'.').ucfirst($config['departamento']).' respons?vel:'.dicaF().'</td><td><input type="hidden" name="projeto_dept" id="projeto_dept" value="'.($projeto_id ? $linha->projeto_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($projeto_id ? $linha->projeto_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:400px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

if ($exibir['depts']) {
	$saida_depts='';
	if (is_array($depts_selecionados) && count($depts_selecionados)) {
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
	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' est?o envolvid'.$config['genero_dept'].' com '.($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto'].'.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';
	}


echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['gerente']), 'Tod'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' deve ter '.($config['genero_gerente']=='o' ? 'um' : 'uma').' '.$config['gerente'].'.').ucfirst($config['gerente']).':'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_responsavel" name="projeto_responsavel" value="'.($linha->projeto_responsavel ? $linha->projeto_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($linha->projeto_responsavel ? $linha->projeto_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
if ($exibir['supervisor']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['supervisor']), ucfirst($config['genero_projeto']).' '.$config['projeto'].' poder? ter '.($config['genero_supervisor']=='o' ? 'um' : 'uma').' '.$config['supervisor'].' relacionad'.$config['genero_supervisor'].'.').ucfirst($config['supervisor']).':'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_supervisor" name="projeto_supervisor" value="'.$linha->projeto_supervisor.'" /><input type="text" id="nome_supervisor" name="nome_supervisor" value="'.nome_om($linha->projeto_supervisor,$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popSupervisor();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
if ($exibir['autoridade']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['autoridade']), ucfirst($config['genero_projeto']).' '.$config['projeto'].' poder? ter '.($config['genero_autoridade']=='o' ? 'um' : 'uma').' '.$config['autoridade'].' relacionad'.$config['genero_autoridade'].'.').ucfirst($config['autoridade']).':'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_autoridade" name="projeto_autoridade" value="'.$linha->projeto_autoridade.'" /><input type="text" id="nome_autoridade" name="nome_autoridade" value="'.nome_om($linha->projeto_autoridade,$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popAutoridade();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
if ($exibir['cliente']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['cliente']), ucfirst($config['genero_projeto']).' '.$config['projeto'].' poder? ter '.($config['genero_cliente']=='o' ? 'um' : 'uma').' '.$config['cliente'].' relacionad'.$config['genero_cliente'].'.').ucfirst($config['cliente']).':'.dicaF().'</td><td colspan="2"><input type="hidden" id="projeto_cliente" name="projeto_cliente" value="'.$linha->projeto_cliente.'" /><input type="text" id="nome_cliente" name="nome_cliente" value="'.nome_om($linha->projeto_cliente,$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popCliente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

if ($exibir['projeto_superior']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Superior', 'De quem '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ? um sub-'.$config['projeto'].'.').ucfirst($config['projeto']).' Superior:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="projeto_superior" name="projeto_superior" value="'.($linha->projeto_superior != $linha->projeto_id ? $linha->projeto_superior : null).'" /><input type="text" name="nome_projeto_superior" value="'.nome_projeto($linha->projeto_superior != $linha->projeto_id ? $linha->projeto_superior : null).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjetoSuperior();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ?cone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
else echo '<input name="projeto_superior" id="projeto_superior" type="hidden" value="'.($linha->projeto_superior != $linha->projeto_id ? $linha->projeto_superior : null).'" />';


if ($exibir['codigo']) echo '<tr><td align="right">'.dica('C?digo', 'Escreva, caso exista, o c?digo d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'C?digo:'.dicaF().'</td><td><input type="text" style="width:400px;" class="texto" name="projeto_codigo" value="'.(isset($linha->projeto_codigo) ? $linha->projeto_codigo : '').'" size="30" maxlength="255" /></td></tr>';
if ($exibir['ano']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Ano', 'A qual ano dever? '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' estar relacionad'.$config['genero_projeto'].'.').'Ano:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" name="projeto_ano" value="'.($linha->projeto_ano ? $linha->projeto_ano : date('Y')).'" style="width:400px" class="texto" /></td></tr>';

$setor = array('' => '') + getSisValor('Setor');
if ($exibir['setor']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['setor']).':'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'projeto_setor', 'style="width:400px;" class="texto" onchange="mudar_segmento();"', $linha->projeto_setor).'</td></tr>';
else echo '<input type="hidden" name="projeto_setor" id="projeto_setor" value="'.$linha->projeto_setor.'" />';
$segmento=array('' => '');
if ($linha->projeto_setor){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="Segmento"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$linha->projeto_setor.'"');
	$sql->adOrdem('sisvalor_valor');
	$segmento+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['segmento']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['segmento']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'projeto_segmento', 'style="width:400px;" class="texto" onchange="mudar_intervencao();"', $linha->projeto_segmento).'</div></td></tr>';
else echo '<input type="hidden" name="projeto_segmento" id="projeto_segmento" value="'.$linha->projeto_segmento.'" />';
$intervencao=array('' => '');
if ($linha->projeto_segmento){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="Intervencao"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$linha->projeto_segmento.'"');
	$sql->adOrdem('sisvalor_valor');
	$intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['intervencao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['intervencao']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'projeto_intervencao', 'style="width:400px;" class="texto" onchange="mudar_tipo_intervencao();"', $linha->projeto_intervencao).'</div></td></tr>';
else echo '<input type="hidden" name="projeto_intervencao" id="projeto_intervencao" value="'.$linha->projeto_intervencao.'" />';
$tipo_intervencao=array('' => '');
if ($linha->projeto_intervencao){
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$linha->projeto_intervencao.'"');
	$sql->adOrdem('sisvalor_valor');
	$tipo_intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
	$sql->limpar();
	}
if ($exibir['tipo_intervencao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['tipo']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'projeto_tipo_intervencao', 'style="width:400px;" class="texto"', $linha->projeto_tipo_intervencao).'</div></td></tr>';
else echo '<input type="hidden" name="projeto_tipo_intervencao" id="projeto_tipo_intervencao" value="'.$linha->projeto_tipo_intervencao.'" />';
if (!$cia_id)$companhia=$Aplic->usuario_cia;
else $companhia=$cia_id;


echo '<tr><td align="right" style="white-space: nowrap">'.dica('Data de In?cio', 'Digite ou escolha no calend?rio a data prov?vel de in?cio d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Data de in?cio:'.dicaF().'</td><td style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_data_inicio" id="projeto_data_inicio" value="'.($data_inicio ? $data_inicio->format('%Y-%m-%d') : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\', \'projeto_data_inicio\');" value="'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data de In?cio', 'Clique neste ?cone '.imagem('icones/calendario.gif').'  para abrir um calend?rio onde poder? selecionar a data prov?vel de in?cio d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'<a href="javascript: void(0);" ><img src="'.acharImagem('calendario.gif').'" id="f_btn1" style="vertical-align:middle" width="18" height="12" alt="Calend?rio" />'.dicaF().'</a></td><td>'.botao('expediente', 'Expediente para '.nome_cia($companhia),'Visualizar o expediente para '.strtolower($config['organizacao']).' ? qual pertence '.($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto'].'.','','if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Expediente\', 820, 500, \'m=calendario&a=jornada&'.($projeto_id ? 'projeto_id='.(int)$projeto_id : 'cia_id='.(int)$companhia).'&dialogo=1\', null, window); else window.open(\'./index.php?m=calendario&a=expediente&cia_id='.(int)$companhia.'&dialogo=1\', \'expediente\',\'height=560,width=820,resizable,scrollbars=yes\')').'</td></tr></table></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Meta de T?rmino', 'Digite ou escolha no calend?rio a data prov?vel de t?rmino d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.</p>Caso n?o saiba a data prov?vel de t?rmino d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).', deixe em branco.').'Meta de t?rmino:</td><td style="white-space: nowrap"><input type="hidden" name="projeto_data_fim" id="projeto_data_fim" value="'.($data_fim ? $data_fim->format('%Y-%m-%d') : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'data_fim\', \'projeto_data_fim\');" value="'.($data_fim ? $data_fim->format('%d/%m/%Y') : '').'" class="texto" /><a href="javascript: void(0);" >'.dica('Meta de T?rmino', 'Clique neste ?cone '.imagem('icones/calendario.gif').'  para abrir um calend?rio onde poder? selecionar a data prov?vel de t?rmino d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'<img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend?rio" />'.dicaF().'</a></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Previs?o T?rmino', 'O sistema calcula automaticamente, baseado na prov?vel data de t?rmino d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Previs?o t?rmino:'.dicaF().'</td><td style="white-space: nowrap">'.($projeto_id > 0 ? ($data_fim_atual ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$tarefasCriticas[0]['tarefa_id'].'\');"><span '.$estilo.'>'.$data_fim_atual->format('%d/%m/%Y').'</span></a>' : '&nbsp;') : 'Calculada dinamicamente').'</td></tr>';



if ($exibir['projeto_encerramento']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Dataa de Encerramento', 'Digite ou escolha no calend?rio a data de encerramento d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Data de encerramento:</td><td style="white-space: nowrap"><input type="hidden" name="projeto_encerramento" id="projeto_encerramento" value="'.($projeto_encerramento ? $projeto_encerramento->format('%Y-%m-%d') : '').'" /><input type="text" name="encerramento" id="encerramento" style="width:70px;" onchange="setData(\'env\', \'encerramento\', \'projeto_encerramento\');" value="'.($linha->projeto_encerramento ? $projeto_encerramento->format('%d/%m/%Y') : '').'" class="texto" /><a href="javascript: void(0);" >'.dica('Data de Encerramento', 'Clique neste ?cone '.imagem('icones/calendario.gif').'  para abrir um calend?rio onde poder? selecionar a data de encarremto d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'<img id="f_btn4" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend?rio" />'.dicaF().'</a></td></tr>';
else echo '<input type="hidden" name="projeto_encerramento" id="projeto_encerramento" value="" /><input type="hidden" name="encerramento" id="encerramento" value="" />';




echo '<tr><td align="right" style="white-space: nowrap">'.dica('Prioridade', 'A prioridade para fins de filtragem.').'Prioridade:'.dicaF().'</td><td nowrap ="nowrap">'.selecionaVetor($PrioridadeProjeto, 'projeto_prioridade', 'size="1" class="texto" style="width:400px;"', ($linha->projeto_prioridade ? $linha->projeto_prioridade : 0)).'</td></tr>';


if ($exibir['categoria']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['categoria']), 'Qual '.$config['genero_categoria'].' '.$config['categoria'].' d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['categoria']).':'.dicaF().'</td><td colspan="3">'.selecionaVetor($projTipo, 'projeto_tipo', 'style="width:400px;" size="1" class="texto"', $linha->projeto_tipo).'</td></tr>';
if ($exibir['url']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Link URL para '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'O endere?o URL '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.').'URL:'.dicaF().'</td><td colspan="2"><input type="text" style="width:400px;" name="projeto_url" value="'.$linha->projeto_url.'" maxlength="255" class="texto" /></td></tr>';
if ($exibir['www']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Endere?o Web de Acesso', 'O endere?o WWW '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].' para ser visito pelo p?blico externo.').'Endere?o Web:'.dicaF().'</td><td colspan="2"><input type="Text" name="projeto_url_externa" value="'.$linha->projeto_url_externa.'" style="width:400px;" maxlength="255" class="texto" /></td></tr>';

//if ($exibir['projeto_fonte']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto_fonte']), ucfirst($config['projeto_fonte']).' '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($config['projeto_fonte']).':'.dicaF().'</td><td colspan="2"><input type="Text" name="projeto_fonte" value="'.$linha->projeto_fonte.'" style="width:400px;" maxlength="255" class="texto" /></td></tr>';

if ($exibir['observacao']) echo '<tr><td align="right">'.dica('Observa??es', 'Observa??es sobre '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Observa??es:'.dicaF().'</td><td><textarea name="projeto_observacao" data-gpweb-cmp="ckeditor" style="width:400px;" rows="3" class="textarea">'.$linha->projeto_observacao.'</textarea></td></tr>';


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
if ($Aplic->checarModulo('praticas', 'editar', null, 'avaliacao_indicador')) $tipos['avaliacao']='Avalia??o';
if ($Aplic->checarModulo('praticas', 'editar', null, 'brainstorm')) $tipos['brainstorm']='Brainstorm';
if ($Aplic->checarModulo('praticas', 'editar', null, 'gut')) $tipos['gut']='Matriz GUT';
if ($Aplic->checarModulo('praticas', 'editar', null, 'causa_efeito')) $tipos['causa_efeito']='Diagrama de causa-efeito';
if ($Aplic->checarModulo('arquivos', 'editar', null,  null)) $tipos['arquivo']='Arquivo';
if ($Aplic->checarModulo('foruns', 'editar', null, null)) $tipos['forum']='F?rum';
if ($Aplic->checarModulo('praticas', 'editar', null, 'checklist')) $tipos['checklist']='Checklist';
if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'editar', null, null)) $tipos['patrocinador']=ucfirst($config['patrocinador']);
if ($Aplic->checarModulo('praticas', 'editar', null, 'plano_acao_item')) $tipos['acao_item']='Item de '.ucfirst($config['acao']);
if ($Aplic->checarModulo('projetos', 'editar', null, 'viabilidade')) $tipos['projeto_viabilidade']='Estudo de viabilidade';
if ($Aplic->checarModulo('projetos', 'editar', null, 'abertura')) $tipos['projeto_abertura']='Termo de abertura';
if ($Aplic->checarModulo('praticas', 'editar', null, 'planejamento')) $tipos['plano_gestao']='Planejamento estrat?gico';
if ($Aplic->profissional) {
	$tipos['agenda']='Compromisso';
	if ($Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'editar', null, null)) $tipos['operativo']='Plano operativo';
	if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'editar', null, null)) $tipos['ata']='Ata de reuni?o';	
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
	if ($Aplic->checarModulo('praticas', 'editar', null, 'odometro_indicador')) $tipos['painel_odometro']='Od?metro de indicador';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'composicao_painel')) $tipos['painel_composicao']='Composi??o de pain?is';
	if ($Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'editar', null, null)) $tipos['tr']=ucfirst($config['tr']);
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'editar', null, 'me')) $tipos['me']=ucfirst($config['me']);
	if ($Aplic->checarModulo('projetos', 'editar', null, 'beneficio')) $tipos['beneficio']=ucfirst($config['beneficio']).' de '.$config['programa'];
	if ($Aplic->checarModulo('projetos', 'editar', null, 'slideshow_painel')) $tipos['painel_slideshow']='Slideshow de composi??es';
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


if ($projeto_tarefa) $tipo='tarefa';
elseif ($projeto_projeto) $tipo='projeto';
elseif ($projeto_perspectiva) $tipo='perspectiva';
elseif ($projeto_tema) $tipo='tema';
elseif ($projeto_objetivo) $tipo='objetivo';
elseif ($projeto_fator) $tipo='fator';
elseif ($projeto_estrategia) $tipo='estrategia';
elseif ($projeto_meta) $tipo='meta';
elseif ($projeto_pratica) $tipo='pratica';
elseif ($projeto_acao) $tipo='acao';
elseif ($projeto_canvas) $tipo='canvas';
elseif ($projeto_risco) $tipo='risco';
elseif ($projeto_risco_resposta) $tipo='risco_resposta';
elseif ($projeto_indicador) $tipo='projeto_indicador';
elseif ($projeto_calendario) $tipo='calendario';
elseif ($projeto_monitoramento) $tipo='monitoramento';
elseif ($projeto_ata) $tipo='ata';
elseif ($projeto_mswot) $tipo='mswot';
elseif ($projeto_swot) $tipo='swot';
elseif ($projeto_operativo) $tipo='operativo';
elseif ($projeto_instrumento) $tipo='instrumento';
elseif ($projeto_recurso) $tipo='recurso';
elseif ($projeto_problema) $tipo='problema';
elseif ($projeto_demanda) $tipo='demanda';
elseif ($projeto_programa) $tipo='programa';
elseif ($projeto_licao) $tipo='licao';
elseif ($projeto_evento) $tipo='evento';
elseif ($projeto_link) $tipo='link';
elseif ($projeto_avaliacao) $tipo='avaliacao';
elseif ($projeto_tgn) $tipo='tgn';
elseif ($projeto_brainstorm) $tipo='brainstorm';
elseif ($projeto_gut) $tipo='gut';
elseif ($projeto_causa_efeito) $tipo='causa_efeito';
elseif ($projeto_arquivo) $tipo='arquivo';
elseif ($projeto_forum) $tipo='forum';
elseif ($projeto_checklist) $tipo='checklist';
elseif ($projeto_agenda) $tipo='agenda';
elseif ($projeto_agrupamento) $tipo='agrupamento';
elseif ($projeto_patrocinador) $tipo='patrocinador';
elseif ($projeto_template) $tipo='template';
elseif ($projeto_painel) $tipo='painel';
elseif ($projeto_painel_odometro) $tipo='painel_odometro';
elseif ($projeto_painel_composicao) $tipo='painel_composicao';
elseif ($projeto_tr) $tipo='tr';
elseif ($projeto_me) $tipo='me';
elseif ($projeto_acao_item) $tipo='acao_item';
elseif ($projeto_beneficio) $tipo='beneficio';
elseif ($projeto_painel_slideshow) $tipo='painel_slideshow';
elseif ($projeto_projeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($projeto_projeto_abertura) $tipo='projeto_abertura';
elseif ($projeto_plano_gestao) $tipo='plano_gestao';
elseif ($projeto_ssti) $tipo='ssti';
elseif ($projeto_laudo) $tipo='laudo';
elseif ($projeto_trelo) $tipo='trelo';
elseif ($projeto_trelo_cartao) $tipo='trelo_cartao';
elseif ($projeto_pdcl) $tipo='pdcl';
elseif ($projeto_pdcl_item) $tipo='pdcl_item';	
elseif ($projeto_os) $tipo='os';	
else $tipo='';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Relacionado','A qual parte do sistema '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' est? relacionad'.$config['genero_projeto'].'.').'Relacionad'.$config['genero_projeto'].':'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';
echo '<tr '.($projeto_projeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso seja espec?fico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo dever? constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_projeto" value="'.$projeto_projeto.'" /><input type="text" id="projeto_gestao_nome" name="projeto_gestao_nome" value="'.nome_projeto($projeto_projeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ?cone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso seja espec?fico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo dever? constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_tarefa" value="'.$projeto_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($projeto_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ?cone '.imagem('icones/tarefa_p.gif').' escolher ? qual '.$config['tarefa'].' o arquivo ir? pertencer.<br><br>Caso n?o escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo ser? d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso seja espec?fico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo dever? constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_perspectiva" value="'.$projeto_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($projeto_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ?cone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso seja espec?fico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo dever? constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_tema" value="'.$projeto_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($projeto_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ?cone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso seja espec?fico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo dever? constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="gestao_projeto_objetivo" value="'.$projeto_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($projeto_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ?cone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso seja espec?fico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo dever? constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_fator" value="'.$projeto_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($projeto_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ?cone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso seja espec?fico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo dever? constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_estrategia" value="'.$projeto_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($projeto_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ?cone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso seja espec?fico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo dever? constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_meta" value="'.$projeto_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($projeto_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ?cone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso seja espec?fico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo dever? constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_pratica" value="'.$projeto_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($projeto_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ?cone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso seja espec?fico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo dever? constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_acao" value="'.$projeto_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($projeto_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar A??o','Clique neste ?cone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de a??o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso seja espec?fico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo dever? constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_canvas" value="'.$projeto_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($projeto_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ?cone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso seja espec?fico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo dever? constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_risco" value="'.$projeto_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($projeto_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ?cone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso seja espec?fico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo dever? constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_risco_resposta" value="'.$projeto_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($projeto_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ?cone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso seja espec?fico de um indicador, neste campo dever? constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_indicador" value="'.$projeto_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($projeto_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ?cone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso seja espec?fico de uma agenda, neste campo dever? constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_calendario" value="'.$projeto_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($projeto_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste ?cone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso seja espec?fico de um monitoramento, neste campo dever? constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_monitoramento" value="'.$projeto_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($projeto_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ?cone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reuni?o', 'Caso seja espec?fico de uma ata de reuni?o neste campo dever? constar o nome da ata').'Ata de Reuni?o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_ata" value="'.(isset($projeto_ata) ? $projeto_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($projeto_ata) ? $projeto_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reuni?o','Clique neste ?cone '.imagem('icones/ata_p.png').' para selecionar uma ata de reuni?o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso seja espec?fico de uma matriz SWOT neste campo dever? constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_mswot" value="'.(isset($projeto_mswot) ? $projeto_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($projeto_mswot) ? $projeto_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste ?cone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso seja espec?fico de um campo de matriz SWOT neste campo dever? constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_swot" value="'.(isset($projeto_swot) ? $projeto_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($projeto_swot) ? $projeto_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste ?cone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso seja espec?fico de um plano operativo, neste campo dever? constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_operativo" value="'.$projeto_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($projeto_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ?cone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso seja espec?fico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo dever? constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_instrumento" value="'.$projeto_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($projeto_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ?cone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso seja espec?fico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo dever? constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_recurso" value="'.$projeto_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($projeto_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['recurso']),'Clique neste ?cone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso seja espec?fico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo dever? constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_problema" value="'.$projeto_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($projeto_problema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ?cone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso seja espec?fico de uma demanda, neste campo dever? constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_demanda" value="'.$projeto_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($projeto_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste ?cone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso seja espec?fico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo dever? constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_programa" value="'.$projeto_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($projeto_programa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ?cone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso seja espec?fico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo dever? constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_licao" value="'.$projeto_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($projeto_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste ?cone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso seja espec?fico de um evento, neste campo dever? constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_evento" value="'.$projeto_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($projeto_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ?cone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso seja espec?fico de um link, neste campo dever? constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_link" value="'.$projeto_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($projeto_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ?cone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avalia??o', 'Caso seja espec?fico de uma avalia??o, neste campo dever? constar o nome da avalia??o.').'Avalia??o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_avaliacao" value="'.$projeto_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($projeto_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avalia??o','Clique neste ?cone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avalia??o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso seja espec?fico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo dever? constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_tgn" value="'.$projeto_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($projeto_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ?cone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso seja espec?fico de um brainstorm, neste campo dever? constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_brainstorm" value="'.$projeto_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($projeto_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ?cone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz GUT', 'Caso seja espec?fico de uma matriz GUT, neste campo dever? constar o nome da matriz GUT.').'Matriz GUT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_gut" value="'.$projeto_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($projeto_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz GUT','Clique neste ?cone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso seja espec?fico de um diagrama de causa-efeito, neste campo dever? constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_causa_efeito" value="'.$projeto_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($projeto_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ?cone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso seja espec?fico de um arquivo, neste campo dever? constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_arquivo" value="'.$projeto_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($projeto_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ?cone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('F?rum', 'Caso seja espec?fico de um f?rum, neste campo dever? constar o nome do f?rum.').'F?rum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_forum" value="'.$projeto_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($projeto_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar F?rum','Clique neste ?cone '.imagem('icones/forum_p.gif').' para selecionar um f?rum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso seja espec?fico de um checklist, neste campo dever? constar o nome do checklist.').'Checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_checklist" value="'.$projeto_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($projeto_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ?cone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso seja espec?fico de um compromisso, neste campo dever? constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_agenda" value="'.$projeto_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($projeto_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste ?cone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso seja espec?fico de um agrupamento, neste campo dever? constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_agrupamento" value="'.$projeto_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($projeto_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste ?cone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso seja espec?fico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo dever? constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_patrocinador" value="'.$projeto_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($projeto_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('icones/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste ?cone '.imagem('icones/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso seja espec?fico de um modelo, neste campo dever? constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_template" value="'.$projeto_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($projeto_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ?cone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso seja espec?fico de um painel de indicador, neste campo dever? constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_painel" value="'.$projeto_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($projeto_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ?cone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Od?metro de Indicador', 'Caso seja espec?fico de um od?metro de indicador, neste campo dever? constar o nome do od?metro.').'Od?metro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_painel_odometro" value="'.$projeto_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($projeto_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Od?metro','Clique neste ?cone '.imagem('icones/odometro_p.png').' para selecionar um od?mtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composi??o de Pain?is', 'Caso seja espec?fico de uma composi??o de pain?is, neste campo dever? constar o nome da composi??o.').'Composi??o de Pain?is:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_painel_composicao" value="'.$projeto_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($projeto_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composi??o de Pain?is','Clique neste ?cone '.imagem('icones/composicao_p.gif').' para selecionar uma composi??o de pain?is.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja espec?fico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo dever? constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_tr" value="'.$projeto_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($projeto_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ?cone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja espec?fico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo dever? constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_me" value="'.$projeto_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($projeto_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ?cone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso seja espec?fico de um item de '.$config['acao'].', neste campo dever? constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_acao_item" value="'.$projeto_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($projeto_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste ?cone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso seja espec?fico de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo dever? constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_gestao_beneficio" value="'.$projeto_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($projeto_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste ?cone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composi??es', 'Caso seja espec?fico de um slideshow de composi??es, neste campo dever? constar o nome do slideshow de composi??es.').'Slideshow de composi??es:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_painel_slideshow" value="'.$projeto_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($projeto_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composi??es','Clique neste ?cone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composi??es.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_projeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso seja espec?fico de um estudo de viabilidade, neste campo dever? constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_projeto_viabilidade" value="'.$projeto_projeto_viabilidade.'" /><input type="text" id="projeto_viabilidade_nome" name="projeto_viabilidade_nome" value="'.nome_viabilidade($projeto_projeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste ?cone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_projeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso seja espec?fico de um termo de abertura, neste campo dever? constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_projeto_abertura" value="'.$projeto_projeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($projeto_projeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste ?cone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estrat?gico', 'Caso seja espec?fico de um planejamento estrat?gico, neste campo dever? constar o nome do planejamento estrat?gico.').'Planejamento estrat?gico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_plano_gestao" value="'.$projeto_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($projeto_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estrat?gico','Clique neste ?cone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estrat?gico.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_ssti ? '' : 'style="display:none"').' id="ssti" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['ssti']), 'Caso seja espec?fico de '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].', neste campo dever? constar o nome d'.$config['genero_ssti'].' '.$config['ssti'].'.').ucfirst($config['ssti']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_ssti" value="'.$projeto_ssti.'" /><input type="text" id="ssti_nome" name="ssti_nome" value="'.nome_ssti($projeto_ssti).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSSTI();">'.imagem('icones/ssti_p.png','Selecionar '.ucfirst($config['ssti']),'Clique neste ?cone '.imagem('icones/ssti_p.png').' para selecionar '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_laudo ? '' : 'style="display:none"').' id="laudo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['laudo']), 'Caso seja espec?fico de '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].', neste campo dever? constar o nome d'.$config['genero_laudo'].' '.$config['laudo'].'.').ucfirst($config['laudo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_laudo" value="'.$projeto_laudo.'" /><input type="text" id="laudo_nome" name="laudo_nome" value="'.nome_laudo($projeto_laudo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLaudo();">'.imagem('icones/laudo_p.png','Selecionar '.ucfirst($config['laudo']),'Clique neste ?cone '.imagem('icones/laudo_p.png').' para selecionar '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_trelo ? '' : 'style="display:none"').' id="trelo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo']), 'Caso seja espec?fico de '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].', neste campo dever? constar o nome d'.$config['genero_trelo'].' '.$config['trelo'].'.').ucfirst($config['trelo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_trelo" value="'.$projeto_trelo.'" /><input type="text" id="trelo_nome" name="trelo_nome" value="'.nome_trelo($projeto_trelo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTrelo();">'.imagem('icones/trelo_p.png','Selecionar '.ucfirst($config['trelo']),'Clique neste ?cone '.imagem('icones/trelo_p.png').' para selecionar '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_trelo_cartao ? '' : 'style="display:none"').' id="trelo_cartao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo_cartao']), 'Caso seja espec?fico de '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].', neste campo dever? constar o nome d'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_trelo_cartao" value="'.$projeto_trelo_cartao.'" /><input type="text" id="trelo_cartao_nome" name="trelo_cartao_nome" value="'.nome_trelo_cartao($projeto_trelo_cartao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTreloCartao();">'.imagem('icones/trelo_cartao_p.png','Selecionar '.ucfirst($config['trelo_cartao']),'Clique neste ?cone '.imagem('icones/trelo_cartao_p.png').' para selecionar '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_pdcl ? '' : 'style="display:none"').' id="pdcl" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl']), 'Caso seja espec?fico de '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].', neste campo dever? constar o nome d'.$config['genero_pdcl'].' '.$config['pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_pdcl" value="'.$projeto_pdcl.'" /><input type="text" id="pdcl_nome" name="pdcl_nome" value="'.nome_pdcl($projeto_pdcl).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCL();">'.imagem('icones/pdcl_p.png','Selecionar '.ucfirst($config['pdcl']),'Clique neste ?cone '.imagem('icones/pdcl_p.png').' para selecionar '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_pdcl_item ? '' : 'style="display:none"').' id="pdcl_item" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl_item']), 'Caso seja espec?fico de '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].', neste campo dever? constar o nome d'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_pdcl_item" value="'.$projeto_pdcl_item.'" /><input type="text" id="pdcl_item_nome" name="pdcl_item_nome" value="'.nome_pdcl_item($projeto_pdcl_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="pop_pdcl_item();">'.imagem('icones/pdcl_item_p.png','Selecionar '.ucfirst($config['pdcl_item']),'Clique neste ?cone '.imagem('icones/pdcl_item_p.png').' para selecionar '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($projeto_os ? '' : 'style="display:none"').' id="os" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['os']), 'Caso seja espec?fico de '.($config['genero_os']=='o' ? 'um' : 'uma').' '.$config['os'].', neste campo dever? constar o nome d'.$config['genero_os'].' '.$config['os'].'.').ucfirst($config['os']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="projeto_os" value="'.$projeto_os.'" /><input type="text" id="os_nome" name="os_nome" value="'.nome_os($projeto_os).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="pop_os();">'.imagem('icones/os_p.png','Selecionar '.ucfirst($config['os']),'Clique neste ?cone '.imagem('icones/os_p.png').' para selecionar '.($config['genero_os']=='o' ? 'um' : 'uma').' '.$config['os'].'.').'</a></td></tr></table></td></tr>';
	



$sql->adTabela('projeto_gestao');
$sql->adCampo('projeto_gestao.*');
if ($uuid) $sql->adOnde('projeto_gestao_uuid = \''.$uuid.'\'');
else $sql->adOnde('projeto_gestao_projeto ='.(int)$projeto_id);	
$sql->adOrdem('projeto_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
echo '<tr><td></td><td><div id="combo_gestao">';
if (is_array($lista) && count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
foreach($lista as $gestao_data){
	echo '<tr align="center">';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posi??o', 'Clique neste ?cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_gestao_ordem'].', '.$gestao_data['projeto_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ?cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_gestao_ordem'].', '.$gestao_data['projeto_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ?cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_gestao_ordem'].', '.$gestao_data['projeto_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posi??o', 'Clique neste ?cone '.imagem('icones/2setabaixo.gif').' para mover para a ?ltima posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['projeto_gestao_ordem'].', '.$gestao_data['projeto_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	if ($gestao_data['projeto_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['projeto_gestao_tarefa']).'</td>';
	elseif ($gestao_data['projeto_gestao_semelhante']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['projeto_gestao_semelhante']).'</td>';
	elseif ($gestao_data['projeto_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_gestao_perspectiva']).'</td>';
	elseif ($gestao_data['projeto_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['projeto_gestao_tema']).'</td>';
	elseif ($gestao_data['projeto_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_gestao_objetivo']).'</td>';
	elseif ($gestao_data['projeto_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_gestao_fator']).'</td>';
	elseif ($gestao_data['projeto_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_gestao_estrategia']).'</td>';
	elseif ($gestao_data['projeto_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_gestao_meta']).'</td>';
	elseif ($gestao_data['projeto_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_gestao_pratica']).'</td>';
	elseif ($gestao_data['projeto_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_gestao_acao']).'</td>';
	elseif ($gestao_data['projeto_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_gestao_canvas']).'</td>';
	elseif ($gestao_data['projeto_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['projeto_gestao_risco']).'</td>';
	elseif ($gestao_data['projeto_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_gestao_risco_resposta']).'</td>';
	elseif ($gestao_data['projeto_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_gestao_indicador']).'</td>';
	elseif ($gestao_data['projeto_gestao_calendario']) echo '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['projeto_gestao_calendario']).'</td>';
	elseif ($gestao_data['projeto_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_gestao_monitoramento']).'</td>';
	elseif ($gestao_data['projeto_gestao_ata']) echo '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['projeto_gestao_ata']).'</td>';
	elseif ($gestao_data['projeto_gestao_mswot']) echo '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['projeto_gestao_mswot']).'</td>';
	elseif ($gestao_data['projeto_gestao_swot']) echo '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['projeto_gestao_swot']).'</td>';
	elseif ($gestao_data['projeto_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_gestao_operativo']).'</td>';
	elseif ($gestao_data['projeto_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_gestao_instrumento']).'</td>';
	elseif ($gestao_data['projeto_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_gestao_recurso']).'</td>';
	elseif ($gestao_data['projeto_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['projeto_gestao_problema']).'</td>';
	elseif ($gestao_data['projeto_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_gestao_demanda']).'</td>';
	elseif ($gestao_data['projeto_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['projeto_gestao_programa']).'</td>';
	elseif ($gestao_data['projeto_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_gestao_licao']).'</td>';
	elseif ($gestao_data['projeto_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_gestao_evento']).'</td>';
	elseif ($gestao_data['projeto_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['projeto_gestao_link']).'</td>';
	elseif ($gestao_data['projeto_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_gestao_avaliacao']).'</td>';
	elseif ($gestao_data['projeto_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_gestao_tgn']).'</td>';
	elseif ($gestao_data['projeto_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['projeto_gestao_brainstorm']).'</td>';
	elseif ($gestao_data['projeto_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['projeto_gestao_gut']).'</td>';
	elseif ($gestao_data['projeto_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['projeto_gestao_causa_efeito']).'</td>';
	elseif ($gestao_data['projeto_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_gestao_arquivo']).'</td>';
	elseif ($gestao_data['projeto_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_gestao_forum']).'</td>';
	elseif ($gestao_data['projeto_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_gestao_checklist']).'</td>';
	elseif ($gestao_data['projeto_gestao_agenda']) echo '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['projeto_gestao_agenda']).'</td>';
	elseif ($gestao_data['projeto_gestao_agrupamento']) echo '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['projeto_gestao_agrupamento']).'</td>';
	elseif ($gestao_data['projeto_gestao_patrocinador']) echo '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_gestao_patrocinador']).'</td>';
	elseif ($gestao_data['projeto_gestao_template']) echo '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['projeto_gestao_template']).'</td>';
	elseif ($gestao_data['projeto_gestao_painel']) echo '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['projeto_gestao_painel']).'</td>';
	elseif ($gestao_data['projeto_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_gestao_painel_odometro']).'</td>';
	elseif ($gestao_data['projeto_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['projeto_gestao_painel_composicao']).'</td>';		
	elseif ($gestao_data['projeto_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['projeto_gestao_tr']).'</td>';	
	elseif ($gestao_data['projeto_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['projeto_gestao_me']).'</td>';	
	elseif ($gestao_data['projeto_gestao_acao_item']) echo '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['projeto_gestao_acao_item']).'</td>';	
	elseif ($gestao_data['projeto_gestao_beneficio']) echo '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['projeto_gestao_beneficio']).'</td>';	
	elseif ($gestao_data['projeto_gestao_painel_slideshow']) echo '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['projeto_gestao_painel_slideshow']).'</td>';	
	elseif ($gestao_data['projeto_gestao_projeto_viabilidade']) echo '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['projeto_gestao_projeto_viabilidade']).'</td>';	
	elseif ($gestao_data['projeto_gestao_projeto_abertura']) echo '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['projeto_gestao_projeto_abertura']).'</td>';	
	elseif ($gestao_data['projeto_gestao_plano_gestao']) echo '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['projeto_gestao_plano_gestao']).'</td>';	
	elseif ($gestao_data['projeto_gestao_ssti']) echo '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['projeto_gestao_ssti']).'</td>';
	elseif ($gestao_data['projeto_gestao_laudo']) echo '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['projeto_gestao_laudo']).'</td>';
	elseif ($gestao_data['projeto_gestao_trelo']) echo '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['projeto_gestao_trelo']).'</td>';
	elseif ($gestao_data['projeto_gestao_trelo_cartao']) echo '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['projeto_gestao_trelo_cartao']).'</td>';
	elseif ($gestao_data['projeto_gestao_pdcl']) echo '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['projeto_gestao_pdcl']).'</td>';
	elseif ($gestao_data['projeto_gestao_pdcl_item']) echo '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['projeto_gestao_pdcl_item']).'</td>';
	elseif ($gestao_data['projeto_gestao_os']) echo '<td align=left>'.imagem('icones/os_p.png').link_os($gestao_data['projeto_gestao_os']).'</td>';
	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['projeto_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ?cone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
	}
if (is_array($lista) && count($lista)) echo '</table>';
echo '</div></td></tr>';



echo '<tr><td align="right">'.dica('Status', 'Definir o Status d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Status:'.dicaF().'</td><td>'.selecionaVetor($projStatus, 'projeto_status', 'style="width:400px;" size="1" class="texto"', $linha->projeto_status).'</td></tr>';

if ($exibir['projeto_fase']){
	$projetoFase = getSisValor('projetoFase');
	echo '<tr><td align="right">'.dica('Fase', 'Definir a fase d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Fase:'.dicaF().'</td><td>'.selecionaVetor($projetoFase, 'projeto_fase', 'style="width:400px;" size="1" class="texto"', $linha->projeto_fase).'</td></tr>';
	}

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Importar '.ucfirst($config['tarefa']), 'Utilize esta op??o caso deseje importar '.$config['genero_tarefa'].'s '.$config['tarefas'].' de outr'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Importar de:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="importarTarefa_projetoId" value="" /><input type="text" name="nome_projeto_importar" value="" style="width:286px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto2();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ?cone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td><td>&nbsp;'.($Aplic->profissional ? '<input type="hidden" name="importar_data_inicio" id="importar_data_inicio" value="'.($data_inicio ? $data_inicio->format('%Y-%m-%d') : '').'" /><input type="text" name="importar_data_inicio_texto" style="width:70px;" id="importar_data_inicio_texto" onchange="setData(\'env\', \'importar_data_inicio_texto\', \'importar_data_inicio\');" value="'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data de In?cio', 'Clique neste ?cone '.imagem('icones/calendario.gif').' para abrir um calend?rio onde poder? selecionar a data inicial das tarefas importadas.').'<a href="javascript: void(0);" ><img src="'.acharImagem('calendario.gif').'" id="f_btn3" style="vertical-align:middle" width="18" height="12" alt="Calend?rio" />'.dicaF().'</a>' : '').'</td></tr></table></td></tr>';

if ($Aplic->profissional && $exibir['moeda']){
	$sql->adTabela('moeda');
	$sql->adCampo('moeda_id, moeda_simbolo');
	$sql->adOrdem('moeda_id');
	$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
	$sql->limpar();
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'Escolha a moeda padr?o utilizada.').'Moeda:'.dicaF().'</td><td>'.selecionaVetor($moedas, 'projeto_moeda', 'class=texto size=1 style="width:400px;"', ($linha->projeto_moeda ? $linha->projeto_moeda : 1)).'</td></tr>';
	}	
else echo '<input type="hidden" name="projeto_moeda" id="projeto_moeda" value="'.($linha->projeto_moeda ? $linha->projeto_moeda : 1).'" />';



echo '<tr><td align="right" style="white-space: nowrap">'.dica('Meta de Custo', 'Previs?o inicial de custo '.($config['genero_projeto']=='o' ? 'neste' : 'nesta').' '.$config['projeto'].'. Servir? de compara??o com o custo efetivo que ? a soma de tod'.$config['genero_tarefa'].'s '.$config['genero_tarefa'].'s '.$config['tarefas'].' d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Meta de custo:'.dicaF().'</td><td><input type="text" style="text-align:right; width:400px" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="projeto_meta_custo" value="'.($linha->projeto_meta_custo ? number_format($linha->projeto_meta_custo, 2, ',', '.') : '').'" /></td></tr>';



if ($exibir['oque']) echo '<tr><td align="right">'.dica('O Que', 'Muito importante escrever um breve resumo d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).', para servir de guia '.($config['genero_tarefa']=='o'? 'aos' : '?s').' '.$config['tarefas'].' e auxiliar na compreens?o d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'O Que:'.dicaF().'</td><td><textarea name="projeto_descricao" data-gpweb-cmp="ckeditor" style="width:400px;" rows="3" class="textarea">'.$linha->projeto_descricao.'</textarea></td></tr>';
if ($exibir['porque']) echo '<tr><td align="right">'.dica('Por Que', 'Por que '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ser? executad'.$config['genero_projeto'].'.').'Por Que:'.dicaF().'</td><td><textarea name="projeto_objetivos" data-gpweb-cmp="ckeditor" style="width:400px;" rows="3" class="textarea">'.$linha->projeto_objetivos.'</textarea></td></tr>';
if ($exibir['como']) echo '<tr><td align="right">'.dica('Como', 'Muito importante escrever um breve resumo do como '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ser? executad'.$config['genero_projeto'].', para servir de guia '.($config['genero_tarefa']=='o'? 'aos' : '?s').' '.$config['tarefas'].' e auxiliar na compreens?o d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Como:'.dicaF().'</td><td><textarea name="projeto_como" data-gpweb-cmp="ckeditor" style="width:400px;" rows="3" class="textarea">'.$linha->projeto_como.'</textarea></td></tr>';
if ($exibir['onde']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Onde', 'Localiza??o onde '.$config['genero_projeto'].' '.ucfirst($config['projeto']).' ser? executado, ou a equipe d'.$config['genero_projeto'].' mesm'.$config['genero_projeto'].' reunida ou ainda a ?rea que se beneficiar? d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Onde:'.dicaF().'</td><td width="100%" colspan="2"><textarea name="projeto_localizacao" data-gpweb-cmp="ckeditor" class="textarea" style="width:400px;" rows="3">'.$linha->projeto_localizacao.'</textarea></td></tr>';
if ($exibir['projeto_beneficiario']) echo '<tr><td align="right">'.dica('Benefici?rio', 'O p?blico atendido pel'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Benefici?rio:'.dicaF().'</td><td><textarea name="projeto_beneficiario" data-gpweb-cmp="ckeditor" style="width:400px;" rows="3" class="textarea">'.$linha->projeto_beneficiario.'</textarea></td></tr>';


if (is_array($indicadores) && count($indicadores)>1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' mais representativo da situa??o geral d'.$config['genero_projeto'].' mesm'.$config['genero_projeto'].'.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'projeto_principal_indicador', 'class="texto" style="width:400px;"', $linha->projeto_principal_indicador).'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('N?vel de Acesso', ($linha->projeto_portfolio ? ucfirst($config['genero_portfolio']).'s '.$config['portfolios'] : ucfirst($config['genero_projeto']).'s '.$config['projetos']).' podem ter cinco n?veis de acesso:<ul><li><b>P?blico</b> - Todos podem ver e editar '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].', '.$config['cliente'].' e os integrantes d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].' e '.$config['cliente'].' podem editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].' e '.$config['cliente'].' pel'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' podem editar.</li><li><b>Participante I</b> - Somente o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].', '.$config['cliente'].' e os integrantes d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' podem ver e editar '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'</li><li><b>Participantes II</b> - Somente o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].', '.$config['cliente'].' e os integrantes podem ver e apenas o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].' e '.$config['cliente'].' podem editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].', '.$config['cliente'].' e os integrantes d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' podem ver '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).', e o '.$config['gerente'].', '.$config['supervisor'].', '.$config['autoridade'].' e '.$config['cliente'].' editarem.</li></ul>').'N?vel de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($projeto_acesso, 'projeto_acesso', 'style="width:400px;" class="texto"', ($projeto_id ? $linha->projeto_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milh?es poss?veis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inser??o do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="projeto_cor" value="'.($linha->projeto_cor ? $linha->projeto_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';



if ($Aplic->profissional) {
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Alerta Ativo', 'Caso esteja marcado, '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ser? inclu?d'.$config['genero_projeto'].' no sistema de alertas autom?ticos (precisa ser executado em background o arquivo server/alertas/alertas_pro.php).').'Alerta ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="projeto_alerta" '.($linha->projeto_alerta ? 'checked="checked"' : '').' /></td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('F?sico Atrav?s de Registro', 'Caso esteja marcado a execu??o f?sica d'.$config['genero_tarefa'].'s '.$config['tarefas'].' s? se modificam atrav?s de registros de ocorr?ncias.').'F?sico atrav?s de registro:'.dicaF().'</td><td><input type="checkbox" value="1" name="projeto_fisico_registro" '.($linha->projeto_fisico_registro ? 'checked="checked"' : '').' /></td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Travar Datas', 'Caso esteja marcado as datas de in?cio e t?rrmino d'.$config['genero_tarefa'].'s '.$config['tarefas'].' s? poder?o ser editadas por quem tem permiss?o de editar '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Travar datas:'.dicaF().'</td><td><input type="checkbox" value="1" name="projeto_trava_data" '.($linha->projeto_trava_data ? 'checked="checked"' : '').' /></td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Aprovar Registro', 'Caso esteja marcado as mudan?as de status, execu??o f?sica, datas de in?cio e t?rmino d'.$config['genero_tarefa'].'s '.$config['tarefas'].' efetuadas em registro de ocorr?ncia s? se efetivar?o ap?s a aprova??o dos registros.').'Aprovar registro:'.dicaF().'</td><td><input type="checkbox" value="1" name="projeto_aprova_registro" '.($linha->projeto_aprova_registro ? 'checked="checked"' : '').' /></td></tr>';
	}




echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Ativ'.($linha->projeto_portfolio ? $config['genero_portfolio'] : $config['genero_projeto']), 'Caso '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' n?o tenha se encerrado, nem se encontra suspenso e j? tenha iniciado os trabalhos, dever? ser marcado como ativ'.($linha->projeto_portfolio ? $config['genero_portfolio'] : $config['genero_projeto']).'.').'Ativ'.($linha->projeto_portfolio ? $config['genero_portfolio'] : $config['genero_projeto']).':'.dicaF().'</td><td><input type="checkbox" value="1" name="projeto_ativo" '.($linha->projeto_ativo || $projeto_id == 0 ? 'checked="checked"' : '').' /></td></tr>';

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados($m, $linha->projeto_id, 'editar');
$campos_customizados->imprimirHTML();


echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'enderecamento\').style.display) document.getElementById(\'enderecamento\').style.display=\'\'; else document.getElementById(\'enderecamento\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Localiza??o e ?reas</b></a></td></tr>';
echo '<tr id="enderecamento" style="display:none"><td colspan=20><table width="100%" cellspacing=1 cellpadding=0>';


if ($exibir['endereco']) {
	echo '<tr><td align="right" width=140>'.dica('Endere?o', 'Escreva o ender?o d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Endere?o:'.dicaF().'</td><td><input type="text" class="texto" name="projeto_endereco1" value="'.$linha->projeto_endereco1.'" style="width:400px;" maxlength="255" /></td></tr>';
	echo '<tr><td align="right">'.dica('Complemento do Endere?o', 'Escreva o complemento do ender?o d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Complemento:'.dicaF().'</td><td><input type="text" class="texto" name="projeto_endereco2" value="'.$linha->projeto_endereco2.'" style="width:400px;" maxlength="255" /></td></tr>';
	if (!$social) echo '<tr><td align="right">'.dica('Estado', 'Escolha na caixa de op??o ? direita o Estado d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'projeto_estado', 'size="1" class="texto" style="width:400px;" onchange="mudar_cidades();"', $linha->projeto_estado).'</td></tr>';
	if (!$social) echo '<tr><td align="right">'.dica('Munic?pio', 'Escreva o munic?pio d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Munic?pio:'.dicaF().'</td><td><div id="combo_cidade">'.selecionaVetor($cidades,'projeto_cidade', 'class="texto" style="width:400px;"', $linha->projeto_cidade).'</div></td></tr>';
	echo '<tr><td align="right">'.dica('CEP', 'Escreva o CEP d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'CEP:'.dicaF().'</td><td><input type="text" class="texto" style="width:400px;" name="projeto_cep" value="'.$linha->projeto_cep.'" maxlength="15" /></td></tr>';
	echo '<tr><td align="right">'.dica('Pa?s', 'Escolha na caixa de op??o ? direita o Pa?s d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Pa?s:'.dicaF().'</td><td>'.selecionaVetor($paises, 'projeto_pais', 'size="1" class="texto" style="width:400px;"', ($linha->projeto_pais ? $linha->projeto_pais : 'BR')).'</td></tr>';
	}

if ($social) {
	$comunidades=array(''=>'');
	if (!$exibir['endereco']) echo '<tr><td align="right">'.dica('Estado', 'Escolha na caixa de op??o ? direita o Estado d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'projeto_estado', 'size="1" class="texto" onchange="mudar_cidades();" style="width:400px;"', $linha->projeto_estado).'</td></tr>';
	if (!$exibir['endereco']) echo '<tr><td align="right">'.dica('Munic?pio', 'Escreva o munic?pio d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Munic?pio:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($linha->projeto_estado, 'projeto_cidade', 'class="texto" onchange="mudar_comunidades()" style="width:400px;"', '', $linha->projeto_cidade, true, false).'</div></td></tr>';
	echo '<tr><td align="right">'.dica('Comunidade', 'A comunidade onde se aplica '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Comunidade:'.dicaF().'</td><td><div id="combo_comunidade">'.selecionar_comunidade_para_ajax($linha->projeto_cidade,'projeto_comunidade', 'class="texto" style="width:400px;"', '', $linha->projeto_comunidade, false).'</div></td></tr>';
	$lista_programas=array('' => '');
	$q->adTabela('social');
	$q->adCampo('social_id, social_nome');
	$q->adOrdem('social_nome');
	$lista_programas+= $q->listaVetorChave('social_id', 'social_nome');
	$q->limpar();
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Programa Social', 'A qual programa social pertence '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Programa:'.dicaF().'</td><td align="left" style="white-space: nowrap">'. selecionaVetor($lista_programas, 'projeto_social', 'size="1" style="width:400px;" class="texto" onchange="mudar_acao()"', $linha->projeto_social) .'</td></tr>';
	echo '<tr><td align="right">'.dica('A??o Social', 'Escolha a a??o social d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'A??o:'.dicaF().'</td><td align="left" style="white-space: nowrap"><div id="acao_combo">'.selecionar_acao_para_ajax($linha->projeto_social, 'projeto_social_acao', 'size="1" style="width:400px;" class="texto"', '', $linha->projeto_social_acao, false).'</div></td></tr>';
	}

if ($exibir['municipios']){
	$saida_municipios='';
	if (is_array($municipios_selecionados) && count($municipios_selecionados)) {
			$saida_municipios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_municipios.= '<tr><td>'.link_municipio($municipios_selecionados[0]);
			$qnt_lista_municipios=count($municipios_selecionados);
			if ($qnt_lista_municipios > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_municipios; $i < $i_cmp; $i++) $lista.=link_municipio($municipios_selecionados[$i]).'<br>';
					$saida_municipios.= dica('Outros Munic?pios', 'Clique para visualizar os demais munic?pios.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_municipios\');">(+'.($qnt_lista_municipios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_municipios"><br>'.$lista.'</span>';
					}
			$saida_municipios.= '</td></tr></table>';
			}
	else $saida_municipios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Munic?pios Envolvidos', 'Quais mnic?pios est?o envolvidos '.($config['genero_projeto']=='a' ? 'nesta ': 'neste ').$config['projeto'].'.').'Munic?pios:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_municipios">'.$saida_municipios.'</div></td><td>'.botao_icone('municipio_p.gif','Selecionar Munic?pio', 'Clique neste ?cone '.botao_icone('municipio_p.gif').' para selecionar munic?pios envolvidos.','popMunicipios()').'</td></tr></table></td></tr>';
	}

if ($exibir['latitude'] || $exibir['longitude']){

	echo '<tr><td align="right">'.dica('Coordenadas', 'As coordenadas geogr?ficas da localiza??o d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Coordenadas:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0>';
	echo '<tr><td colspan=2 align=center>Geogr?fica</td><td colspan=2 align=center>UTM</td></tr>';
	echo '<tr><td align=right>Lon:</td><td><input class="texto" type=text size=15 id="projeto_longitude" name="projeto_longitude" value="'.($linha->projeto_longitude ? $linha->projeto_longitude : 0).'" onChange="converter_decimal()"></td><td align=right>X:</td><td><input class="texto" type=text size=15 name="txtX" value=""></td></tr>';
	echo '<tr><td align=right>Lat:</td><td><input class="texto" type=text size=15 id="projeto_latitude" name="projeto_latitude" value="'.($linha->projeto_latitude ? $linha->projeto_latitude : 0).'"  onChange="converter_decimal()"></td><td align=right>Y:</td><td><input class="texto" type=text size=15 name="txtY" value=""></td></tr>';
	echo '<tr><td align=right>Lon:</td><td><input class="texto" type="text" name="txtlongraus" size="2" onChange="btnToUTM_OnClick()" value="0">?<input class="texto" type="text" name="txtlonmin" size="2" onChange="btnToUTM_OnClick()" value="0">\'<input class="texto" type="text" name="txtlonsec" size="2" onChange="btnToUTM_OnClick()" value="0">\'\'</td><td align=right>Zona:</td><td><input class="texto" type=text size=4 name="txtZone" value="22" value="0"></td></tr>';
	echo '<tr><td align=right>Lat:</td><td><input class="texto" type="text" name="txtlatgraus" size="2" onChange="btnToUTM_OnClick()" value="0">?<input class="texto" type="text" name="txtlatmin" size="2" onChange="btnToUTM_OnClick()" value="0">\'<input class="texto" type="text" name="txtlatsec" size="2" onChange="btnToUTM_OnClick()" value="0">\'\'&nbsp;&nbsp;</td><td colspan=2>Hemisf?rio:<input class="texto" type=radio name="rbtnHemisphere" value="N" OnClick="0">N<input class="texto" type=radio name="rbtnHemisphere" value="S" OnClick="0" checked>S</td></tr>';
	echo '<tr><td></td><td align=center>'.botao('>>', 'Transformar em UTM', 'Clique neste bot?o para converter as coordenadas de grau para UTM.','','btnToUTM_OnClick()').'</td><td></td><td align=center>'.botao('<<', 'Transformar em Grau', 'Clique neste bot?o para converter as coordenadas de UTM para grau.','','btnToGeographic_OnClick()').'</td></tr>';
	echo '</table></td></tr>';
	}


if ($exibir['area'] && $projeto_id) echo '<tr><td align="right" style="white-space: nowrap"></td><td valign="top"><table cellspacing=0 cellpadding=0><tr><td>'.botao('?rea', '?rea','Abrir uma janela onde poder? selecionar a ?rea '.($config['genero_projeto']=='a' ? 'desta ': 'deste ').$config['projeto'].' baseado nas coordenadas de pol?gonos cadastrados.','','popEditarPoligono()').'</td>'.($Aplic->profissional ? '<td>'.botao('importar ?rea', 'Importar ?rea KML','Abrir uma janela onde poder? selecionar a ?rea '.($config['genero_projeto']=='a' ? 'desta ': 'deste ').$config['projeto'].' s partir de arquivo KML.','','popImportarKML()').'</td>' : '').'</tr></table></td></tr>';

echo '</div></td></tr>';
echo '</table></td></tr>';


if ($exibir['partes']){
	//contatos do projeto

	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_envolvidos\').style.display) document.getElementById(\'apresentar_envolvidos\').style.display=\'\'; else document.getElementById(\'apresentar_envolvidos\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Contatos</b></a></td></tr>';
	echo '<tr id="apresentar_envolvidos" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0>';

	echo '<tr><td><table cellspacing=0 cellpadding=0>';
	echo '<tr><td><fieldset><legend class=texto style="color: black;">'.dica('Contato','Contato a ser inserido ou editado.').'&nbsp;<b>Contato</b>&nbsp'.dicaF().'</legend><table cellspacing=1 cellpadding=0>';


	echo '<td align="right">'.dica('Contato', 'Nome do contato d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' que tem envolvimento. No caso de inser??o de dados n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' poder?o ser informados automaticamente por mensagem.').'Contato:'.dicaF().'</td><td><input type="hidden" id="envolvido_id" name="envolvido_id" value="" /><input type="text" id="nome_envolvido" name="nome_envolvido" value="" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popEnvolvido();">'.imagem('icones/usuarios.gif','Selecionar Contato','Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar um contato.').'</a></td></tr>';
	echo '<tr><td align="right">'.dica('Relev?ncia', 'Estabelecer a relev?ncia do contato n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Relev?ncia:'.dicaF().'</td><td><input type="text" id="envolvimento" name="envolvimento" value="" style="width:400px;" class="texto" /></td>';
	echo '<tr><td align="right">'.dica('Caracter?sticas/Perfil', 'A caracter?sticas/perfil do contato n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Caracter?sticas/Perfil:'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0 style="width:400px"><tr><td><textarea rows="5" name="perfil" id="perfil" data-gpweb-cmp="ckeditor" class="textarea"></textarea></td></tr></table></td></tr>';

	echo '</table></fieldset></td>';

	echo '<td id="adicionar_envolvido" style="display:"><a href="javascript: void(0);" onclick="incluir_envolvido();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ?cone '.imagem('icones/adicionar.png').' para incluir um contato como envolvido n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'</a></td>';
	echo '<td id="confirmar_envolvido" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'envolvido_id\').value=0;document.getElementById(\'envolvimento\').value=\'\';	document.getElementById(\'nome_envolvido\').value=\'\'; document.getElementById(\'adicionar_envolvido\').style.display=\'\';	CKEDITOR.instances[\'perfil\'].setData(\'\'); document.getElementById(\'confirmar_envolvido\').style.display=\'none\';">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ?cone '.imagem('icones/cancelar.png').' para cancelar a edi??o do contato como envolvido n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'</a><a href="javascript: void(0);" onclick="incluir_envolvido();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ?cone '.imagem('icones/ok.png').' para confirmar a edi??o do contato como envolvido n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'</a></td>';
	echo '</tr>';

	echo '</table></td></tr>';


	if ($linha->projeto_id){
		$sql->adTabela('projeto_contatos', 'pc');
		$sql->esqUnir('contatos', 'c', 'c.contato_id = pc.contato_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = c.contato_cia');
		$sql->adOnde('pc.projeto_id = '.(int)$linha->projeto_id);
		$sql->adCampo('cia_nome, projeto_contato_id, contato_funcao, envolvimento, perfil, pc.contato_id, ordem, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_contato');
		$sql->adOrdem('ordem');
		$contatos=$sql->ListaChave('contato_id');
		$sql->limpar();
		}
	else $contatos=null;

	echo '<tr><td colspan=20 align=left><div id="envolvidos">';
	if (is_array($contatos) && count($contatos)) {
		echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>'.dica('Nome', 'Nome do contato d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' que tem envolvimento. No caso de inser??o de dados n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' poder?o ser informados automaticamente por e-mail.').'Nome'.dicaF().'</th><th>'.ucfirst($config['organizacao']).'</th><th>Fun??o</th><th>Relev?ncia</th><th>Caracter?stica/Perfil</th><th></th></tr>';
		foreach ($contatos as $contato_id => $contato_data) {
			echo '<tr align="center">';

			echo '<td style="white-space: nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posi??o', 'Clique neste ?cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_envolvido('.$contato_data['ordem'].', '.$contato_data['projeto_contato_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'"/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ?cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_envolvido('.$contato_data['ordem'].', '.$contato_data['projeto_contato_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'"/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ?cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_envolvido('.$contato_data['ordem'].', '.$contato_data['projeto_contato_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'"/></a>'.dicaF();
			echo dica('Mover para a Ultima Posi??o', 'Clique neste ?cone '.imagem('icones/2setabaixo.gif').' para mover para a ?ltima posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_envolvido('.$contato_data['ordem'].', '.$contato_data['projeto_contato_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'"/></a>'.dicaF();
			echo '</td>';

			echo '<td align="left" style="white-space: nowrap">'.$contato_data['nome_contato'].'</td>';
			echo '<td align="left">'.$contato_data['cia_nome'].'</td>';
			echo '<td align="left">'.$contato_data['contato_funcao'].'</td>';
			echo '<td align="left">'.$contato_data['envolvimento'].'</td>';
			echo '<td align="left">'.$contato_data['perfil'].'</td>';
			echo '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_envolvido('.$contato_data['projeto_contato_id'].');">'.imagem('icones/editar.gif', 'Editar Envolvido', 'Clique neste ?cone '.imagem('icones/editar.gif').' para editar o contato envolvido com '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'</a>';
			echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este envolvido?\')) {excluir_envolvido('.$contato_data['projeto_contato_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ?cone '.imagem('icones/remover.png').' para excluir o contato envolvido com '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'</a></td>';

			echo '</tr>';
			}
		echo '</table>';
		}
	echo '</div></td></tr>';

	echo '</table></td></tr>';
	}




//integrantes

echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_integrantes\').style.display) document.getElementById(\'apresentar_integrantes\').style.display=\'\'; else document.getElementById(\'apresentar_integrantes\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Integrantes</b></a></td></tr>';
echo '<tr id="apresentar_integrantes" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0>';


echo '<tr><td><table cellspacing=0 cellpadding=0>';

echo '<tr><td><fieldset><legend class=texto style="color: black;">'.dica('Integrante','Integrante a ser inserido ou editado.').'&nbsp;<b>Integrante</b>&nbsp'.dicaF().'</legend><table cellspacing=1 cellpadding=0>';
echo '<tr><td align=right>'.dica('Integrante', 'Nome do integrante que tem envolvimento n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. No caso de inser??o de dados n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' poder?o ser informados automaticamente por mensagem.').'Integrante:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="integrante_id" name="integrante_id" value="" /><input type="text" id="nome_integrante" name="nome_integrante" value="" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIntegrante();">'.imagem('icones/usuarios.gif','Selecionar Integrante','Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar um integrante.').'</a></td></tr></table></td></tr>';
echo '<tr><td align=right>'.dica('Compet?ncia', 'Estabelecer a compet?ncia do indiv?duo relacionado (ex: gest?o de risco, planejar a log?stia, etc.) n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'&nbsp;Compet?ncia:'.dicaF().'</td><td><input type="text" id="projeto_integrante_competencia" name="projeto_integrante_competencia" value="" style="width:400px;" class="texto" /></td></tr>';
echo '<tr><td align=right>'.dica('Atributos', 'Relacionar a descri??o detalhada dos atributos desenvolvidos n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Atributos:'.dicaF().'</td><td style="width:400px;"><textarea rows="10" name="projeto_integrante_atributo" id="projeto_integrante_atributo" data-gpweb-cmp="ckeditor" class="textarea" ></textarea></td></tr>';
echo '<tr><td align=right>'.dica('Situa??o', 'Descrever a situa??o atual deste integrante n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' (ex: disposi??o em tempo parcial, integral, etc.).').'Situa??o:'.dicaF().'</td><td style="width:400px;"><textarea rows="10" name="projeto_integrantes_situacao" id="projeto_integrantes_situacao" data-gpweb-cmp="ckeditor" class="textarea" ></textarea></td></tr>';
echo '<tr><td align=right>'.dica('Necessidades', 'Relacionar as necessidades do integrante para realiza??o dos trabalhos n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Necessidades:'.dicaF().'</td><td style="width:400px;"><textarea rows="3" name="projeto_integrantes_necessidade" id="projeto_integrantes_necessidade" data-gpweb-cmp="ckeditor" class="textarea" ></textarea></td></tr>';
echo '</table></fieldset></td>';

echo '<td id="adicionar_integrante" style="display:"><a href="javascript: void(0);" onclick="incluir_integrante();">'.imagem('icones/adicionar_g.png','Incluir Integrante','Clique neste ?cone '.imagem('icones/adicionar.png').' para incluir um contato como integrante n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'</a></td>';
echo '<td id="confirmar_integrante" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'integrante_id\').value=0;document.getElementById(\'projeto_integrante_competencia\').value=\'\';	document.getElementById(\'nome_integrante\').value=\'\'; CKEDITOR.instances[\'projeto_integrante_atributo\'].setData(\'\'); CKEDITOR.instances[\'projeto_integrantes_situacao\'].setData(\'\'); CKEDITOR.instances[\'projeto_integrantes_necessidade\'].setData(\'\'); document.getElementById(\'adicionar_integrante\').style.display=\'\';	document.getElementById(\'confirmar_integrante\').style.display=\'none\';">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ?cone '.imagem('icones/cancelar.png').' para cancelar a edi??o do contato como integrante n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'</a><a href="javascript: void(0);" onclick="incluir_integrante();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ?cone '.imagem('icones/ok.png').' para confirmar a edi??o do contato como integrante n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'</a></td>';
echo '</tr>';

echo '</table></td></tr>';

if ($linha->projeto_id) {
	$sql->adTabela('projeto_integrantes', 'pc');
	$sql->esqUnir('contatos', 'c', 'c.contato_id = pc.contato_id');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = c.contato_cia');
	$sql->adOnde('pc.projeto_id = '.(int)$linha->projeto_id);
	$sql->adCampo('cia_nome, projeto_integrantes_id, projeto_integrante_atributo, projeto_integrantes_situacao, projeto_integrantes_necessidade, contato_funcao, projeto_integrante_competencia, pc.contato_id, ordem, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_contato');
	$sql->adOrdem('ordem');
	$integrantes=$sql->ListaChave('contato_id');
	$sql->limpar();
	}
else $integrantes=null;

echo '<tr><td colspan=20 align=left><div id="integrantes">';
if (is_array($integrantes) && count($integrantes)) {
	echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>'.dica('Nome', 'Nome do contato d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' que tem envolvimento. No caso de inser??o de dados n'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' poder?o ser informados automaticamente por e-mail.').'Nome'.dicaF().'</th><th>'.$config['organizacao'].'</th><th>Fun??o</th><th>Compet?ncia</th><th>Atributos</th><th>Situa??o</th><th>Necessidade</th><th></th></tr>';
	foreach ($integrantes as $contato_id => $integrante) {
		echo '<tr align="center">';
		echo '<td style="white-space: nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posi??o', 'Clique neste ?cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_integrante('.$integrante['ordem'].', '.$integrante['projeto_integrantes_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'"/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ?cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_integrante('.$integrante['ordem'].', '.$integrante['projeto_integrantes_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'"/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ?cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_integrante('.$integrante['ordem'].', '.$integrante['projeto_integrantes_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'"/></a>'.dicaF();
		echo dica('Mover para a Ultima Posi??o', 'Clique neste ?cone '.imagem('icones/2setabaixo.gif').' para mover para a ?ltima posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_integrante('.$integrante['ordem'].', '.$integrante['projeto_integrantes_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'"/></a>'.dicaF();
		echo '</td>';
		echo '<td align="left" style="white-space: nowrap">'.$integrante['nome_contato'].'</td>';
		echo '<td align="left">'.$integrante['cia_nome'].'</td>';
		echo '<td align="left">'.$integrante['contato_funcao'].'</td>';
		echo '<td align="left">'.$integrante['projeto_integrante_competencia'].'</td>';
		echo '<td align="left">'.$integrante['projeto_integrante_atributo'].'</td>';
		echo '<td align="left">'.$integrante['projeto_integrantes_situacao'].'</td>';
		echo '<td align="left">'.$integrante['projeto_integrantes_necessidade'].'</td>';
		echo '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_integrante('.$integrante['projeto_integrantes_id'].');">'.imagem('icones/editar.gif', 'Editar Integrante', 'Clique neste ?cone '.imagem('icones/editar.gif').' para editar o contato integrante com '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este integrante?\')) {excluir_integrante('.$integrante['projeto_integrantes_id'].');}">'.imagem('icones/remover.png', 'Excluir Integrante', 'Clique neste ?cone '.imagem('icones/remover.png').' para excluir o contato integrante com '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}

echo '</div></td></tr>';
echo '</table></td></tr>';

//detalhamento
if ($exibir['projeto_justificativa'] ||	$exibir['projeto_objetivo'] ||	$exibir['projeto_objetivo_especifico'] || $exibir['projeto_escopo'] ||	$exibir['projeto_nao_escopo'] || $exibir['projeto_premissas'] || $exibir['projeto_restricoes'] ||	$exibir['projeto_orcamento'] ||	$exibir['projeto_beneficio'] ||	$exibir['projeto_produto'] ||	$exibir['projeto_requisito']){
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'detalhamento\').style.display) document.getElementById(\'detalhamento\').style.display=\'\'; else document.getElementById(\'detalhamento\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Detalhamento</b></a></td></tr>';
	echo '<tr id="detalhamento" style="display:none"><td colspan=20><table width="100%" cellspacing=1 cellpadding=0>';

	if ($exibir['projeto_justificativa']) echo '<tr><td align="right" style="white-space: nowrap" width=100>'.dica('Justificativa', 'Descrever de forma clara a justificativa contendo um breve hist?rico e as motiva??es d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Justificativa:'.dicaF().'</td><td width="100%"><textarea name="projeto_justificativa" data-gpweb-cmp="ckeditor" style="width:800px;" class="textarea" rows="3">'.$linha->projeto_justificativa.'</textarea></td></tr>';
	if ($exibir['projeto_objetivo']) echo '<tr><td align="right" style="white-space: nowrap" width=100>'.dica('Objetivo', 'Descrever qual o objetivo para a qual ?rg?o est? realizando '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).', que pode ser: descri??o concreta de que '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' quer alcan?ar, uma posi??o estrat?gica a ser alcan?ada, um resultado a ser obtido, um produto a ser produzido ou um servi?o a ser realizado. Os objetivos devem ser espec?ficos, mensur?veis, realiz?veis, real?sticos, e baseados no tempo.').'Objetivo:'.dicaF().'<td width="100%"><textarea name="projeto_objetivo" data-gpweb-cmp="ckeditor" style="width:800px;" class="textarea">'.$linha->projeto_objetivo.'</textarea></td></tr>';
	if ($exibir['projeto_objetivo_especifico']) echo '<tr><td align="right" style="white-space: nowrap" width=120>'.dica('Objetivos Espec?ficos', 'Descrever quais s?o os objetivos espec?ficos d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'').'Objetivos espec?ficos:'.dicaF().'<td width="100%"><textarea name="projeto_objetivo_especifico" data-gpweb-cmp="ckeditor" style="width:800px;" class="textarea">'.$linha->projeto_objetivo_especifico.'</textarea></td></tr>';
	if ($exibir['projeto_escopo']) echo '<tr><td align="right" width=100>'.dica('Escopo', 'Descrever o escopo, que inclui as principais entregas, fornece uma base documentada para futuras decis?es d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' e para confirmar ou desenvolver um entendimento comum do escopo d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' entre as partes interessadas.').'Escopo:'.dicaF().'<td width="100%"><textarea name="projeto_escopo" data-gpweb-cmp="ckeditor" style="width:800px;" class="textarea">'.$linha->projeto_escopo.'</textarea></td></tr>';
	if ($exibir['projeto_nao_escopo']) echo '<tr><td align="right" style="white-space: nowrap" width=100>'.dica('N?o escopo', 'Descrever de forma expl?cita o que est? exclu?do d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).', para evitar que uma parte interessada possa supor que um produto, servi?o ou resultado espec?fico ? um produto d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'N?o escopo:'.dicaF().'<td width="100%"><textarea name="projeto_nao_escopo" data-gpweb-cmp="ckeditor" style="width:800px;" class="textarea">'.$linha->projeto_nao_escopo.'</textarea></td></tr>';
	if ($exibir['projeto_premissas']) echo '<tr><td align="right" style="white-space: nowrap" width=100>'.dica('Premissas', 'Descrever as premissas d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. As premissas s?o fatores que, para fins de planejamento, s?o considerados verdadeiros, reais ou certos sem prova ou demonstra??o. As premissas afetam todos os aspectos do planejamento d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' e fazem parte da elabora??o progressiva d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. Frequentemente, as equipes d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' identificam, documentam e validam as premissas durante o processo de planejamento. Geralmente, as premissas envolvem um grau de risco.').'Premissas:'.dicaF().'<td width="100%"><textarea name="projeto_premissas" data-gpweb-cmp="ckeditor" style="width:800px;" class="textarea">'.$linha->projeto_premissas.'</textarea></td></tr>';
	if ($exibir['projeto_restricoes']) echo '<tr><td align="right" style="white-space: nowrap" width=100>'.dica('Restri??es', 'Descrever as restri??es d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. Uma restri??o ? uma limita??o aplic?vel, interna ou externa a'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).', que afetar? o desempenho d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ou de um processo. Por exemplo, uma restri??o do cronograma ? qualquer limita??o ou condi??o colocada em rela??o ao cronograma d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' que afeta o momento em que uma atividade do cronograma pode ser agendada e geralmente est? na forma de datas impostas fixas.').'Restri??es:'.dicaF().'<td width="100%"><textarea name="projeto_restricoes" data-gpweb-cmp="ckeditor" style="width:800px;" class="textarea">'.$linha->projeto_restricoes.'</textarea></td></tr>';
	if ($exibir['projeto_orcamento']) echo '<tr><td align="right"  width=100>'.dica('Custos Estimados e Fontes de Recursos', 'Descrever a estimativa de custos e fontes de recursos d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Custos e fontes:'.dicaF().'<td width="100%"><textarea name="projeto_orcamento" data-gpweb-cmp="ckeditor" style="width:800px;" class="textarea">'.$linha->projeto_orcamento.'</textarea></td></tr>';
	if ($exibir['projeto_beneficio']) echo '<tr><td align="right"  width=100>'.dica('Benef?cios', 'Descrever os benef?cios advindo d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. Declara??es que mostram como o produto, sua caracter?stica ou vantagem satisfaz uma necessidade expl?cita.').'Benef?cios:'.dicaF().'<td width="100%"><textarea name="projeto_beneficio" data-gpweb-cmp="ckeditor" style="width:800px;" class="textarea">'.$linha->projeto_beneficio.'</textarea></td></tr>';
	if ($exibir['projeto_produto']) echo '<tr><td align="right"  width=100>'.dica('Produtos', 'Descrever os produtos advindo d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Produtos:'.dicaF().'<td width="100%"><textarea name="projeto_produto" data-gpweb-cmp="ckeditor" style="width:800px;" class="textarea">'.$linha->projeto_produto.'</textarea></td></tr>';
	if ($exibir['projeto_requisito']) echo '<tr><td align="right"  width=100>'.dica('Requisitos', 'Descrever os requisitos para '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'. Os requisitos refletem as necessidades e as expectativas das partes interessadas no projeto. Eles devem ser analisados e registrados com detalhes suficientes para serem medidos, uma vez que v?o ser a base para definir as alternativas de condu??o do projeto e se transformar?o na funda??o da EAP. Custo, Cronograma e o planejamento da qualidade s?o baseados no requisitos.').'Requisitos:'.dicaF().'<td width="100%"><textarea name="projeto_requisito" data-gpweb-cmp="ckeditor" style="width:800px;" class="textarea">'.$linha->projeto_requisito.'</textarea></td></tr>';
	echo '</table></td></tr>';
	}


//Portf?lio, Stakeholder, priorizacao
if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/editar_pro.php';



echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'notificar\').style.display) document.getElementById(\'notificar\').style.display=\'\'; else document.getElementById(\'notificar\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Notificar</b></a></td></tr>';
echo '<tr id="notificar" style="display:'.($Aplic->getPref('informa_aberto') ? '' : 'none').'"><td colspan=20><table width="100%" cellspacing=1 cellpadding=0>';

echo '<tr><td valign="middle" align="right" width=130>'.dica('Notificar', 'Marque esta caixa para avisar da '.($projeto_id > 0 ? 'modifica??o' : 'cria??o').' '.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').'Notificar:'.dicaF().'</td><td>';
echo '<input type="checkbox" name="email_projeto_responsavel_box" id="email_projeto_responsavel_box" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'": '').' />'.dica(ucfirst($config['gerente']), 'Ao selecionar esta op??o, o '.$config['gerente'].' d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ser? informado '.($projeto_id > 0 ? 'das altera??es realizadas.' : 'da cria??o.')).ucfirst($config['gerente']).dicaF().'&nbsp;&nbsp;&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_projeto_supervisor_box" id="email_projeto_supervisor_box" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'" : '').' />'.dica(ucfirst($config['supervisor']), 'Ao selecionar esta op??o, o '.$config['supervisor'].' d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ser? informado '.($projeto_id > 0 ? 'das altera??es realizadas.' : 'da cria??o.')).ucfirst($config['supervisor']).dicaF().'&nbsp;&nbsp;&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_projeto_autoridade_box" id="email_projeto_autoridade_box" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'" : '').' />'.dica(ucfirst($config['autoridade']), 'Ao selecionar esta op??o, o '.$config['autoridade'].' d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ser? informado '.($projeto_id > 0 ? 'das altera??es realizadas.' : 'da cria??o.')).ucfirst($config['autoridade']).dicaF().'&nbsp;&nbsp;&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_projeto_cliente_box" id="email_projeto_cliente_box" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'" : '').' />'.dica(ucfirst($config['cliente']), 'Ao selecionar esta op??o, o '.$config['cliente'].' d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ser? informado '.($projeto_id > 0 ? 'das altera??es realizadas.' : 'da cria??o.')).ucfirst($config['cliente']).dicaF().'&nbsp;&nbsp;&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_projeto_designados_box" id="email_projeto_designados_box" '.($Aplic->getPref('informa_designados') ? "checked='checked'" : '').' />'.dica('Integrantes', 'Ao selecionar esta op??o, os integrantes d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ser?o informados '.($projeto_id > 0 ? 'das altera??es realizadas.' : 'da cria??o.')).'Integrantes'.dicaF().'&nbsp;&nbsp;&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_projeto_stakeholder_box" id="email_projeto_stakeholder_box" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'" : '').' />'.dica('Stakeholders', 'Ao selecionar esta op??o, os stakeholders d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ser?o informados '.($projeto_id > 0 ? 'das altera??es realizadas.' : 'da cria??o.')).'Stakeholders'.dicaF().'&nbsp;&nbsp;&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_projeto_contatos_box" id="email_projeto_contatos_box" '.($Aplic->getPref('informa_contatos') ? "checked='checked'" : '').' />'.dica('Contatos', 'Ao selecionar esta op??o, os contatos d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).' ser?o informados '.($projeto_id > 0 ? 'das altera??es realizadas.' : 'da cria??o.')).'Contatos'.dicaF();
echo '</td></tr>';

echo '<input type="hidden" name="email_contatos" id="email_contatos" value="" />';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Outros '.ucfirst($config['contatos']), ucfirst($config['contatos']).' extras para receberem notifica??o.').'Outros '.$config['contatos'].':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_contatos"><table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table></div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['contatos'].'.','popContatos()').'</td></tr></table></td></tr>';
echo ($config['email_ativo'] ? ''.($config['email_ativo'] ? '<tr><td align="right">'.dica('Destinat?rios Extra', 'Preencha neste campo os e-mail, separados por v?rgula, dos destinat?rios extras que ser?o avisados.').'Destinat?rios extra:'.dicaF().'</td><td><input type="text" class="texto" name="email_extras" maxlength="255" style="width:400px;" /></td></tr>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'' : '<input type="hidden" name="email_extras" id="email_extras" value="" />');
echo '<tr><td align="right">'.dica('Texto', 'Texto a ser enviado junto com a notifica??o.').'Texto:'.dicaF().'</td><td><textarea name="email_texto" data-gpweb-cmp="ckeditor" style="width:400px;" rows="3" class="textarea"></textarea></td></tr>';



echo '</table></td></tr>';


echo '<tr><td colspan=20><table width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($projeto_id > 0 ? 'edi??o' : 'cria??o').' d'.($linha->projeto_portfolio ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';
echo '</form></table></table>';
echo estiloFundoCaixa();


echo selecao_calendarios($data_inicio, $data_fim,(isset($projeto_id) ? $projeto_id :''),'','projeto_data_inicio', 'projeto_data_fim','CompararDatas();');

?>
<script type="text/javascript">

var contatos_id_selecionados = '';

function popContatos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('projeto_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, window.setContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('projeto_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setContatos(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.email_contatos.value = contato_id_string;
	contatos_id_selecionados = contato_id_string;
	xajax_exibir_contatos(contatos_id_selecionados);
	__buildTooltip();
	}


function acao_stakeholder(tipo){
	xajax_acao_stakeholder(
	tipo,
	document.getElementById('projeto_id').value,
	document.getElementById('uuid').value,
	document.getElementById('projeto_stakeholder_perfil').value,
	document.getElementById('projeto_stakeholder_autoridade').value,
	document.getElementById('projeto_stakeholder_interesse').value,
	document.getElementById('projeto_stakeholder_influencia').value,
	document.getElementById('projeto_stakeholder_impacto').value
	);
	}

function popStackholder() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Stackholder", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setStackholder&cia_id='+document.getElementById('projeto_cia').value+'&contato=1&contato_id='+document.getElementById('projeto_stakeholder_contato').value, window.setStackholder, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setStackholder&cia_id='+document.getElementById('projeto_cia').value+'&contato=1&contato_id='+document.getElementById('projeto_stakeholder_contato').value, 'Gerente','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setStackholder(contato_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('projeto_stakeholder_contato').value=contato_id;
	document.getElementById('nome_stakeholder').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


function mudar_posicao_stakeholder(projeto_stakeholder_ordem, projeto_stakeholder_id, direcao){
	xajax_mudar_posicao_stakeholder_ajax(projeto_stakeholder_ordem, projeto_stakeholder_id, direcao, document.getElementById('projeto_id').value, document.getElementById('uuid').value);
	}

function editar_stakeholder(projeto_stakeholder_id){
	xajax_editar_stakeholder(projeto_stakeholder_id);

	CKEDITOR.instances['projeto_stakeholder_descricao'].setData(document.getElementById('apoio1').value);

	document.getElementById('adicionar_stakeholder').style.display="none";
	document.getElementById('confirmar_stakeholder').style.display="";
	}

function incluir_stakeholder(){
	if (document.getElementById('projeto_stakeholder_contato').value > 0){
		xajax_incluir_stakeholder_ajax(
			document.getElementById('projeto_id').value,
			document.getElementById('uuid').value,
			document.getElementById('projeto_stakeholder_id').value,
			document.getElementById('projeto_stakeholder_contato').value,
			document.getElementById('projeto_stakeholder_perfil').value,
			document.getElementById('projeto_stakeholder_autoridade').value,
			document.getElementById('projeto_stakeholder_interesse').value,
			document.getElementById('projeto_stakeholder_influencia').value,
			document.getElementById('projeto_stakeholder_impacto').value,
			CKEDITOR.instances['projeto_stakeholder_descricao'].getData()
			);
		limpar_stakeholder();
		}
	else alert('Escolha um stakeholder.');
	}

function excluir_stakeholder(projeto_stakeholder_id){
	xajax_excluir_stakeholder_ajax(projeto_stakeholder_id, document.getElementById('projeto_id').value, document.getElementById('uuid').value);
	}

function limpar_stakeholder(){
	document.getElementById('projeto_stakeholder_id').value=null;
	document.getElementById('projeto_stakeholder_contato').value=null;
	document.getElementById('nome_stakeholder').value='';
	CKEDITOR.instances['projeto_stakeholder_descricao'].setData('');
	document.getElementById('adicionar_stakeholder').style.display='';
	document.getElementById('confirmar_stakeholder').style.display='none';
	}

function setData( frm_nome, f_data, f_data_real ){
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada n?o corresponde ao formato padr?o. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		}
		else {
	  	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
	  	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
	    campo_data.style.backgroundColor = '';

			//data final fazer ao menos no mesmo dia da inicial
			if (f_data_real!='importar_data_inicio') CompararDatas();
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
      document.getElementById("projeto_data_fim").value=document.getElementById("projeto_data_inicio").value;
    	}
   }

var cal3 = Calendario.setup({
	trigger    : "f_btn3",
  inputField : "importar_data_inicio",
	date :  <?php echo $data_inicio->format("%Y-%m-%d")?>,
	selection: <?php echo $data_inicio->format("%Y-%m-%d")?>,
  onSelect: function(cal3) {
	  var date = cal3.selection.get();
	  if (date){
	  	date = Calendario.intToDate(date);
	    document.getElementById("importar_data_inicio_texto").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("importar_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
	    }
		cal3.hide();
		}
	});


var cal4 = Calendario.setup({
	trigger    : "f_btn4",
  inputField : "projeto_encerramento",
	date :  <?php echo $projeto_encerramento->format("%Y-%m-%d")?>,
	selection: <?php echo $projeto_encerramento->format("%Y-%m-%d")?>,
  onSelect: function(cal4) {
	  var date = cal4.selection.get();
	  if (date){
	  	date = Calendario.intToDate(date);
	    document.getElementById("encerramento").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("projeto_encerramento").value = Calendario.printDate(date, "%Y-%m-%d");
	    }
		cal4.hide();
		}
	});
	
	
function mudar_segmento(){
	document.getElementById('projeto_intervencao').length=0;
	document.getElementById('projeto_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('projeto_setor').value, 'Segmento', 'projeto_segmento','combo_segmento', 'style="width:400px;" class="texto" size=1 onchange="mudar_intervencao();"');
	}

function mudar_intervencao(){
	document.getElementById('projeto_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('projeto_segmento').value, 'Intervencao', 'projeto_intervencao','combo_intervencao', 'style="width:400px;" class="texto" size=1 onchange="mudar_tipo_intervencao();"');
	}

function mudar_tipo_intervencao(){
	xajax_mudar_ajax(document.getElementById('projeto_intervencao').value, 'TipoIntervencao', 'projeto_tipo_intervencao','combo_tipo_intervencao', 'style="width:400px;" class="texto" size=1');
	}

function mudar_acao(){
	xajax_acao_ajax(document.getElementById('projeto_social').value, 0);
	}

function mudar_cidades(){
	document.getElementById('projeto_cidade').length=0;
	var estado=document.getElementById('projeto_estado').value;
	<?php
	echo "if (estado) {xajax_selecionar_cidades_ajax(estado,'projeto_cidade','combo_cidade', \"class='texto' size=1 style='width:400px;' ".($social ? "onchange='mudar_comunidades()'" : '')."\",'');}";
	if ($social) echo "document.getElementById('projeto_comunidade').length=0;";
	?>
	}

<?php	if ($social){	?>
function mudar_comunidades(){
	var municipio_id=document.getElementById('projeto_cidade').value;
	xajax_selecionar_comunidade_ajax(municipio_id, 'projeto_comunidade', 'combo_comunidade', 'class="texto" size=1 style="width:400px;"', '', '');
	}
<?php } ?>



function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode;
  var unicode1 = event.keyCode;
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 39 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true;
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
      	return false;
      	}
    	}
  	}
	}


function moeda2float(moeda){
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	if (moeda=="") moeda='0';
	return parseFloat(moeda);
	}

function popImportarKML(){
	parent.gpwebApp.popUp('Importar ?rea', 1024, 500, 'm=projetos&a=editar_poligono_pro&dialogo=1&uuid='+document.getElementById('uuid').value+'&projeto_id='+document.getElementById('projeto_id').value, null, window);
	}

function popEditarPoligono() {
	if(parent && parent.gpwebApp && parent.gpwebApp.editarAreaProjeto) parent.gpwebApp.editarAreaProjeto(<?php echo $projeto_id ?>);
	else window.open('./index.php?m=projetos&a=editar_poligono&dialogo=1&chamar_volta=setCoordenadas&uuid='+document.getElementById('uuid').value+'&projeto_id='+document.getElementById('projeto_id').value, 'Coordenadas','height=500,width=800,resizable,scrollbars=yes, left=0, top=0');
	}



function popGerente(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Gerente", 600, 600, "m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id="+document.getElementById('projeto_cia').value+'&usuario_id='+document.getElementById('projeto_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('projeto_cia').value+'&usuario_id='+document.getElementById('projeto_responsavel').value, 'Gerente','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('projeto_responsavel').value=(usuario_id > 0 ? usuario_id : null);
		document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function popProjeto2() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('projeto_cia').value, window.setProjeto2, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto2&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto2(chave, valor){
	env.importarTarefa_projetoId.value=(chave > 0 ? chave : null);
	env.nome_projeto_importar.value=valor;
	}

function popProjetoSuperior() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['projeto']) ?> Superior", 610, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjetoSuperior&aceita_portfolio=1&tabela=projetos&projeto_id='+document.getElementById('projeto_superior').value, window.setProjetoSuperior, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjetoSuperior&aceita_portfolio=1&tabela=projetos&projeto_id='+document.getElementById('projeto_superior').value, 'Projetos','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProjetoSuperior(chave, valor){
	env.projeto_superior.value=(chave > 0 ? chave : null);
	env.nome_projeto_superior.value=valor;
	}




//integrantes
function mudar_posicao_integrante(ordem, projeto_integrantes_id, direcao){
	xajax_mudar_posicao_integrante_ajax(ordem, projeto_integrantes_id, direcao, document.getElementById('projeto_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function editar_integrante(projeto_integrantes_id){
	xajax_editar_integrante(projeto_integrantes_id);
	CKEDITOR.instances['projeto_integrante_atributo'].setData(document.getElementById('apoio1').value);
	CKEDITOR.instances['projeto_integrantes_situacao'].setData(document.getElementById('apoio2').value);
	CKEDITOR.instances['projeto_integrantes_necessidade'].setData(document.getElementById('apoio3').value);
	document.getElementById('adicionar_integrante').style.display="none";
	document.getElementById('confirmar_integrante').style.display="";
	}

function incluir_integrante(){
	if (document.getElementById('integrante_id').value > 0){
		xajax_incluir_integrante_ajax(
			document.getElementById('projeto_id').value, 
			document.getElementById('uuid').value, 
			document.getElementById('integrante_id').value, 
			document.getElementById('projeto_integrante_competencia').value, 
			CKEDITOR.instances['projeto_integrante_atributo'].getData(), 
			CKEDITOR.instances['projeto_integrantes_situacao'].getData(),
			CKEDITOR.instances['projeto_integrantes_necessidade'].getData()
			);
		document.getElementById('integrante_id').value=null;
		document.getElementById('projeto_integrante_competencia').value='';
		document.getElementById('nome_integrante').value='';
		CKEDITOR.instances['projeto_integrante_atributo'].setData('');
		CKEDITOR.instances['projeto_integrantes_situacao'].setData('');
		CKEDITOR.instances['projeto_integrantes_necessidade'].setData('');
		document.getElementById('adicionar_integrante').style.display='';
		document.getElementById('confirmar_integrante').style.display='none';
		__buildTooltip();
		}
	else alert('Escolha um integrante.');
	}

function excluir_integrante(projeto_integrantes_id){
	xajax_excluir_integrante_ajax(projeto_integrantes_id, document.getElementById('projeto_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function popIntegrante() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Integrante", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setIntegrante&cia_id='+document.getElementById('projeto_cia').value+'&contato=1&contato_id='+document.getElementById('integrante_id').value, window.setIntegrante, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setIntegrante&cia_id='+document.getElementById('projeto_cia').value+'&contato=1&contato_id='+document.getElementById('integrante_id').value, 'Integrante','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setIntegrante(contato_id, posto, nome, funcao, campo, nome_cia, projeto_integrante_atributo){
	document.getElementById('integrante_id').value=contato_id;
	document.getElementById('nome_integrante').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	CKEDITOR.instances['projeto_integrante_atributo'].setData(projeto_integrante_atributo);
	}


//envolvidos
function popEnvolvido() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Contato", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setEnvolvido&cia_id='+document.getElementById('projeto_cia').value+'&contato=1&contato_id='+document.getElementById('envolvido_id').value, window.setEnvolvido, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setEnvolvido&cia_id='+document.getElementById('projeto_cia').value+'&contato=1&contato_id='+document.getElementById('envolvido_id').value, 'Gerente','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');

	}

function setEnvolvido(contato_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('envolvido_id').value=contato_id;
	document.getElementById('nome_envolvido').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

function mudar_posicao_envolvido(ordem, projeto_contato_id, direcao){
	xajax_mudar_posicao_envolvido_ajax(ordem, projeto_contato_id, direcao, document.getElementById('projeto_id').value, document.getElementById('uuid').value);
	}

function editar_envolvido(projeto_contato_id){
	xajax_editar_envolvido(projeto_contato_id);
	CKEDITOR.instances['perfil'].setData(document.getElementById('apoio1').value);
	document.getElementById('adicionar_envolvido').style.display="none";
	document.getElementById('confirmar_envolvido').style.display="";
	}

function incluir_envolvido(){
	if (document.getElementById('envolvido_id').value > 0){
		xajax_incluir_envolvido_ajax(document.getElementById('projeto_id').value, document.getElementById('uuid').value, document.getElementById('envolvido_id').value, document.getElementById('envolvimento').value, CKEDITOR.instances['perfil'].getData());
		document.getElementById('envolvido_id').value=null;
		document.getElementById('envolvimento').value='';
		document.getElementById('nome_envolvido').value='';
		CKEDITOR.instances['perfil'].setData('');
		document.getElementById('adicionar_envolvido').style.display='';
		document.getElementById('confirmar_envolvido').style.display='none';
		}
	else alert('Selecione alguem primeiro!');
	}

function excluir_envolvido(projeto_contato_id){
	xajax_excluir_envolvido_ajax(projeto_contato_id, document.getElementById('projeto_id').value, document.getElementById('uuid').value);
	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('projeto_cia').value,'projeto_cia','combo_cia', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om();"');
	}

function popSupervisor() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['supervisor']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('projeto_cia').value+'&usuario_id='+document.getElementById('projeto_supervisor').value, window.setSupervisor, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('projeto_cia').value+'&usuario_id='+document.getElementById('projeto_supervisor').value, 'Supervisor','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setSupervisor(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('projeto_supervisor').value=(usuario_id > 0 ? usuario_id : null);
		document.getElementById('nome_supervisor').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}

function popAutoridade() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['autoridade']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('projeto_cia').value+'&usuario_id='+document.getElementById('projeto_autoridade').value, window.setAutoridade, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('projeto_cia').value+'&usuario_id='+document.getElementById('projeto_autoridade').value, 'Autoridade','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setAutoridade(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('projeto_autoridade').value=(usuario_id > 0 ? usuario_id : null);
		document.getElementById('nome_autoridade').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function popCliente() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['cliente']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('projeto_cia').value+'&usuario_id='+document.getElementById('projeto_cliente').value, window.setCliente, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCliente&cia_id='+document.getElementById('projeto_cia').value+'&usuario_id='+document.getElementById('projeto_cliente').value, "<?php echo ucfirst($config['cliente']) ?>",'height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setCliente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('projeto_cliente').value=(usuario_id > 0 ? usuario_id : null);
		document.getElementById('nome_cliente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}



function setCor(cor) {
	var f = document.env;
	if (cor) f.projeto_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.projeto_cor.value;
	}

function setCurto() {
	var f = document.env;
	var x = 20;
	if (f.projeto_nome.value.length < 21) x = f.projeto_nome.value.length;
	}


function enviarDados() {
	var f = document.env;

	xajax_projeto_existe(f.projeto_nome.value, document.getElementById('projeto_id').value);

	if (f.projeto_nome.value.length < 3) {
		alert('Escreva um nome de <?php echo $config["projeto"]?> v?lido');
		f.projeto_nome.focus();
		}

	else if (document.getElementById("existe_projeto").value > 0) {
		alert('J? existe <?php echo $config["projeto"]?> com este nome');
		f.projeto_nome.focus();
		}

	else if (f.projeto_cor.value.length < 3){
		alert('Escolha uma cor para <?php echo $config["genero_projeto"]." ".$config["projeto"]?> v?lida');
		f.projeto_cor.focus();
		}
	else if (f.projeto_cia.options[f.projeto_cia.selectedIndex].value < 1){
		alert('Escolha a <?php echo $config["om"]?> d<?php echo $config["genero_projeto"]." ".$config["projeto"]?>');
		f.projeto_cia.focus();
		}
	else {

		if (f.encerramento.value.length < 4) f.projeto_encerramento.value=null;
		f.projeto_meta_custo.value=moeda2float(f.projeto_meta_custo.value);

        if(window.parent.gpwebApp && !f.projeto_id.value){
            window.parent.gpwebApp.askYesNoQuestion(
                'Editar',
                'Voc? deseja continuar a edi??o utilizando o <b>Gantt Interativo</b>?',
                window.salvarEditarGantt,
                window
                );
            }
		else {
            f.submit();
            }
		}
	}

function salvarEditarGantt(btn){
    var f = document.env;

    f.editar_gantt.value= btn==='yes' ? 1 : 0;

    f.submit();
}

function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('projeto_dept').value+'&cia_id='+document.getElementById('projeto_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('projeto_dept').value+'&cia_id='+document.getElementById('projeto_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('projeto_cia').value=cia_id;
	document.getElementById('projeto_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}


function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamentos']) ?>", 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('projeto_cia').value+'&depts_id_selecionados='+document.getElementById('projeto_depts').value, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('projeto_cia').value+'&depts_id_selecionados='+document.getElementById('projeto_depts').value, "<?php echo ucfirst($config['departamentos']) ?>",'height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.projeto_depts.value = departamento_id_string;
	document.getElementById('projeto_depts').value = departamento_id_string;
	xajax_exibir_depts(document.getElementById('projeto_depts').value);
	__buildTooltip();
	}



function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('projeto_cia').value+'&cias_id_selecionadas='+document.getElementById('projeto_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.projeto_cias.value = organizacao_id_string;
	document.getElementById('projeto_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('projeto_cias').value);
	__buildTooltip();
	}

var municipios_selecionados = '<?php echo implode(',', $municipios_selecionados)?>';

function popMunicipios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Munic?pios", 1000, 700, 'm=publico&a=selecionar_municipios&dialogo=1&chamar_volta=setMunicipios&valores='+municipios_selecionados, window.setMunicipios, window);
	else window.open('./index.php?m=publico&a=selecionar_municipios&dialogo=1&chamar_volta=setMunicipios&valores='+municipios_selecionados, 'Munic?pios','height=500,width=500,resizable,scrollbars=yes');
	}

function setMunicipios(municipios_id_string){
	if(!municipios_id_string) municipios_id_string = '';
	document.env.projeto_municipios.value = municipios_id_string;
	municipios_selecionados = municipios_id_string;
	xajax_exibir_municipios(municipios_selecionados);
	__buildTooltip();
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}



<?php if ($Aplic->profissional){ ?>

	function popProjetoPortfolio() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&selecao=2&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('projeto_cia').value+'&projeto_id='+document.getElementById('projeto_id').value, window.setProjetoPortfolio, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setProjetoPortfolio&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('projeto_id').value+'&projeto_id='+document.getElementById('projeto_id').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setProjetoPortfolio(chave, valor){

		if (chave) xajax_incluir_portfolio_ajax(document.getElementById('projeto_id').value, document.getElementById('uuid').value, chave);
		}

	function mudar_posicao_portfolio(ordem, projeto_portfolio_filho, direcao){
		xajax_mudar_posicao_portfolio_ajax(ordem, projeto_portfolio_filho, direcao, document.getElementById('projeto_id').value, document.getElementById('uuid').value);
		}

	function excluir_portfolio(projeto_portfolio_filho){
		xajax_excluir_portfolio_ajax(projeto_portfolio_filho, document.getElementById('projeto_id').value, document.getElementById('uuid').value);
		}



<?php } ?>


<?php if ($exibir['latitude'] || $exibir['longitude']){ ?>

var pi = 3.14159265358979;
/* Ellipsoide (WGS84) */
/* var sm_a = 6378137.0; */
var sm_a = 6378160.0;
var sm_b = 6356752.314;
var sm_EccSquared = 6.69437999013e-03;
var wnumero = 0
var wgrau = 0
var wmin = 0
var wsec = 0
var UTMScaleFactor = 0.9996;



function DegToRad (deg){
  return (deg / 180.0 * pi);
	}


function RadToDeg (rad){
  return (rad / pi * 180.0);
	}


function ArcLengthOfMeridian (phi){
  var alpha, beta, gamma, delta, epsilon, n;
  var result;
  n = (sm_a - sm_b) / (sm_a + sm_b);
  alpha = ((sm_a + sm_b) / 2.0) * (1.0 + (Math.pow (n, 2.0) / 4.0) + (Math.pow (n, 4.0) / 64.0));
  beta = (-3.0 * n / 2.0) + (9.0 * Math.pow (n, 3.0) / 16.0) + (-3.0 * Math.pow (n, 5.0) / 32.0);
  gamma = (15.0 * Math.pow (n, 2.0) / 16.0) + (-15.0 * Math.pow (n, 4.0) / 32.0);
  delta = (-35.0 * Math.pow (n, 3.0) / 48.0) + (105.0 * Math.pow (n, 5.0) / 256.0);
  epsilon = (315.0 * Math.pow (n, 4.0) / 512.0);
	result = alpha * (phi + (beta * Math.sin (2.0 * phi)) + (gamma * Math.sin (4.0 * phi)) + (delta * Math.sin (6.0 * phi)) + (epsilon * Math.sin (8.0 * phi)));
	return result;
	}

function UTMCentralMeridian (zone){
  var cmeridian;
  cmeridian = DegToRad (-183.0 + (zone * 6.0));
  return cmeridian;
	}

function FootpointLatitude (y){
  var y_, alpha_, beta_, gamma_, delta_, epsilon_, n;
  var result;
  n = (sm_a - sm_b) / (sm_a + sm_b);
  alpha_ = ((sm_a + sm_b) / 2.0) * (1 + (Math.pow (n, 2.0) / 4) + (Math.pow (n, 4.0) / 64));
  y_ = y / alpha_;
  beta_ = (3.0 * n / 2.0) + (-27.0 * Math.pow (n, 3.0) / 32.0) + (269.0 * Math.pow (n, 5.0) / 512.0);
  gamma_ = (21.0 * Math.pow (n, 2.0) / 16.0) + (-55.0 * Math.pow (n, 4.0) / 32.0);
  delta_ = (151.0 * Math.pow (n, 3.0) / 96.0) + (-417.0 * Math.pow (n, 5.0) / 128.0);
  epsilon_ = (1097.0 * Math.pow (n, 4.0) / 512.0);
  result = y_ + (beta_ * Math.sin (2.0 * y_))  + (gamma_ * Math.sin (4.0 * y_)) + (delta_ * Math.sin (6.0 * y_))  + (epsilon_ * Math.sin (8.0 * y_));
  return result;
	}

function MapLatLonToXY (phi, lambda, lambda0, xy){
  var N, nu2, ep2, t, t2, l;
  var l3coef, l4coef, l5coef, l6coef, l7coef, l8coef;
  var tmp;
  ep2 = (Math.pow (sm_a, 2.0) - Math.pow (sm_b, 2.0)) / Math.pow (sm_b, 2.0);
  nu2 = ep2 * Math.pow (Math.cos (phi), 2.0);
  N = Math.pow (sm_a, 2.0) / (sm_b * Math.sqrt (1 + nu2));
  t = Math.tan (phi);
  t2 = t * t;
  tmp = (t2 * t2 * t2) - Math.pow (t, 6.0);
  l = lambda - lambda0;
  l3coef = 1.0 - t2 + nu2;
  l4coef = 5.0 - t2 + 9 * nu2 + 4.0 * (nu2 * nu2);
  l5coef = 5.0 - 18.0 * t2 + (t2 * t2) + 14.0 * nu2 - 58.0 * t2 * nu2;
  l6coef = 61.0 - 58.0 * t2 + (t2 * t2) + 270.0 * nu2 - 330.0 * t2 * nu2;
  l7coef = 61.0 - 479.0 * t2 + 179.0 * (t2 * t2) - (t2 * t2 * t2);
  l8coef = 1385.0 - 3111.0 * t2 + 543.0 * (t2 * t2) - (t2 * t2 * t2);
  xy[0] = N * Math.cos (phi) * l   + (N / 6.0 * Math.pow (Math.cos (phi), 3.0) * l3coef * Math.pow (l, 3.0)) + (N / 120.0 * Math.pow (Math.cos (phi), 5.0) * l5coef * Math.pow (l, 5.0)) + (N / 5040.0 * Math.pow (Math.cos (phi), 7.0) * l7coef * Math.pow (l, 7.0));
  xy[1] = ArcLengthOfMeridian (phi) + (t / 2.0 * N * Math.pow (Math.cos (phi), 2.0) * Math.pow (l, 2.0)) + (t / 24.0 * N * Math.pow (Math.cos (phi), 4.0) * l4coef * Math.pow (l, 4.0)) + (t / 720.0 * N * Math.pow (Math.cos (phi), 6.0) * l6coef * Math.pow (l, 6.0)) + (t / 40320.0 * N * Math.pow (Math.cos (phi), 8.0) * l8coef * Math.pow (l, 8.0));
  return;
	}


function MapXYToLatLon (x, y, lambda0, philambda){
  var phif, Nf, Nfpow, nuf2, ep2, tf, tf2, tf4, cf;
  var x1frac, x2frac, x3frac, x4frac, x5frac, x6frac, x7frac, x8frac;
  var x2poly, x3poly, x4poly, x5poly, x6poly, x7poly, x8poly;
  phif = FootpointLatitude (y);
  ep2 = (Math.pow (sm_a, 2.0) - Math.pow (sm_b, 2.0)) / Math.pow (sm_b, 2.0);
  cf = Math.cos (phif);
  nuf2 = ep2 * Math.pow (cf, 2.0);
  Nf = Math.pow (sm_a, 2.0) / (sm_b * Math.sqrt (1 + nuf2));
  Nfpow = Nf;
  tf = Math.tan (phif);
  tf2 = tf * tf;
  tf4 = tf2 * tf2;
  x1frac = 1.0 / (Nfpow * cf);
  Nfpow *= Nf;   /* now equals Nf**2) */
  x2frac = tf / (2.0 * Nfpow);
  Nfpow *= Nf;   /* now equals Nf**3) */
  x3frac = 1.0 / (6.0 * Nfpow * cf);
  Nfpow *= Nf;   /* now equals Nf**4) */
  x4frac = tf / (24.0 * Nfpow);
  Nfpow *= Nf;   /* now equals Nf**5) */
  x5frac = 1.0 / (120.0 * Nfpow * cf);
  Nfpow *= Nf;   /* now equals Nf**6) */
  x6frac = tf / (720.0 * Nfpow);
  Nfpow *= Nf;   /* now equals Nf**7) */
  x7frac = 1.0 / (5040.0 * Nfpow * cf);
  Nfpow *= Nf;   /* now equals Nf**8) */
  x8frac = tf / (40320.0 * Nfpow);
  x2poly = -1.0 - nuf2;
  x3poly = -1.0 - 2 * tf2 - nuf2;
  x4poly = 5.0 + 3.0 * tf2 + 6.0 * nuf2 - 6.0 * tf2 * nuf2	- 3.0 * (nuf2 *nuf2) - 9.0 * tf2 * (nuf2 * nuf2);
  x5poly = 5.0 + 28.0 * tf2 + 24.0 * tf4 + 6.0 * nuf2 + 8.0 * tf2 * nuf2;
  x6poly = -61.0 - 90.0 * tf2 - 45.0 * tf4 - 107.0 * nuf2	+ 162.0 * tf2 * nuf2;
  x7poly = -61.0 - 662.0 * tf2 - 1320.0 * tf4 - 720.0 * (tf4 * tf2);
  x8poly = 1385.0 + 3633.0 * tf2 + 4095.0 * tf4 + 1575 * (tf4 * tf2);
  philambda[0] = phif + x2frac * x2poly * (x * x)	+ x4frac * x4poly * Math.pow (x, 4.0)	+ x6frac * x6poly * Math.pow (x, 6.0)	+ x8frac * x8poly * Math.pow (x, 8.0);
  philambda[1] = lambda0 + x1frac * x	+ x3frac * x3poly * Math.pow (x, 3.0)	+ x5frac * x5poly * Math.pow (x, 5.0)	+ x7frac * x7poly * Math.pow (x, 7.0);
  return;
	}





function LatLonToUTMXY (lat, lon, zone, xy){
  MapLatLonToXY (lat, lon, UTMCentralMeridian (zone), xy);
  /* Adjust easting and northing for UTM system. */
  xy[0] = xy[0] * UTMScaleFactor + 500000.0;
  xy[1] = xy[1] * UTMScaleFactor;
  if (xy[1] < 0.0) xy[1] = xy[1] + 10000000.0;
  return zone;
	}


function UTMXYToLatLon (x, y, zone, southhemi, latlon){
  var cmeridian;
  x -= 500000.0;
  x /= UTMScaleFactor;
  /* If in southern hemisphere, adjust y accordingly. */
  if (southhemi)
  y -= 10000000.0;
  y /= UTMScaleFactor;
 	cmeridian = UTMCentralMeridian (zone);
  MapXYToLatLon (x, y, cmeridian, latlon);
  return;
	}

function btnToUTM_OnClick (){
  var xy = new Array(2);
  if (document.env.txtlongraus.value!=null) {
   	wgrau = parseFloat (document.env.txtlongraus.value);
   	wmin = parseFloat (document.env.txtlonmin.value) / 60;
  	wsec = parseFloat (document.env.txtlonsec.value) / 3600;
   	wnumero = wgrau + wmin + wsec

   	if (wmin <0) wmin=wmin*-1;
   	if (wsec <0) wsec=wsec*-1;

		if (wgrau >= 0) wnumero = wgrau + wmin + wsec ;
		if (wgrau < 0) wnumero = wgrau - wmin - wsec ;

   	document.env.projeto_longitude.value = wnumero;
		}
  if (isNaN (parseFloat (document.env.projeto_longitude.value))) {
    alert ("Entre com uma longitude v?lida.");
    return false;
		}
  lon = parseFloat (document.env.projeto_longitude.value);
  if ((lon < -180.0) || (180.0 <= lon)) {
    alert ("Entre com um n?mero para latitude entre -180, 180.");
    return false;
		}
	if (document.env.txtlatgraus.value!=null) {
    wgrau = parseFloat (document.env.txtlatgraus.value);
    wmin = parseFloat (document.env.txtlatmin.value) / 60;
    wsec = parseFloat (document.env.txtlatsec.value) / 3600;

   	wnumero = wgrau + wmin + wsec

   	if (wmin <0) wmin=wmin*-1;
   	if (wsec <0) wsec=wsec*-1;

		if (wgrau >= 0) wnumero = wgrau + wmin + wsec ;
		if (wgrau < 0) wnumero = wgrau - wmin - wsec ;


    document.env.projeto_latitude.value = wnumero;
  	}
  if (isNaN (parseFloat (document.env.projeto_latitude.value))) {
    alert ("Entre com uma latitude v?lida.");
    return false;
		}
  lat = parseFloat (document.env.projeto_latitude.value);
  if ((lat < -90.0) || (90.0 < lat)) {
    alert ("Entre com um n?mero para latitude entre -90, 90.");
    return false;
		}
  zone = Math.floor ((lon + 180.0) / 6) + 1;
  zone = LatLonToUTMXY (DegToRad (lat), DegToRad (lon), zone, xy);
  document.env.txtX.value = xy[0];
  document.env.txtY.value = xy[1];
  document.env.txtZone.value = zone;
  if (lat < 0) document.env.rbtnHemisphere[1].checked = true;
  else document.env.rbtnHemisphere[0].checked = true;
  return true;
	}

function btnToGeographic_OnClick (){
  latlon = new Array(2);
  var x, y, zone, southhemi;
  if (isNaN (parseFloat (document.env.txtX.value))) {
    alert ("Entre com uma Coordenada v?ida para X.");
    return false;
		}
  x = parseFloat (document.env.txtX.value);
  x = x - 75;
  if (isNaN (parseFloat (document.env.txtY.value))) {
    alert ("Entre com uma Coordenada v?ida para Y.");
    return false;
		}
  y = parseFloat (document.env.txtY.value);
  y = y - 25;
  if (isNaN (parseInt (document.env.txtZone.value))) {
    alert ("Entre com uma Zona v?lida.");
    return false;
		}
  zone = parseFloat (document.env.txtZone.value);
  if ((zone < 1) || (60 < zone)) {
    alert ("Zona Inv?lida entre com um n?mero de 1 ? 60");
    return false;
		}
  if (document.env.rbtnHemisphere[1].checked == true) southhemi = true;
  else southhemi = false;
  UTMXYToLatLon (x, y, zone, southhemi, latlon);
  document.env.projeto_longitude.value = RadToDeg (latlon[1]);
  document.env.projeto_latitude.value = RadToDeg (latlon[0]);
  wnumero = Math.abs(RadToDeg (latlon[1]));
  wgrau = Math.floor(wnumero);
  wmin = Math.floor((wnumero - wgrau) * 60);
  wsec = Math.floor((((wnumero - wgrau) * 60) - wmin) * 60);
  document.env.txtlongraus.value = wgrau;
  document.env.txtlonmin.value = wmin;
  document.env.txtlonsec.value = wsec;
  wnumero = Math.abs(RadToDeg (latlon[0]));
  wgrau = Math.floor(wnumero);
  wmin = Math.floor((wnumero - wgrau) * 60);
  wsec = Math.floor((((wnumero - wgrau) * 60) - wmin) * 60);
  document.env.txtlatgraus.value = wgrau;
  document.env.txtlatmin.value = wmin;
  document.env.txtlatsec.value = wsec;
  return true;
	}

function converter_decimal(){
	var long=env.projeto_longitude.value;
	grau_long = parseInt(long);
	minuto=long-grau_long;
	minuto=minuto*60;
	if (minuto < 0) minuto=minuto*-1;
	minuto_long=parseInt(minuto);
	segundo=minuto-minuto_long;
	segundo=segundo*60;
	segundo_long=parseInt(segundo);
	env.txtlongraus.value=grau_long;
	env.txtlonmin.value=minuto_long;
	env.txtlonsec.value=segundo_long;

	var lat=env.projeto_latitude.value;
	grau_lat = parseInt(lat);
	minuto=lat-grau_lat;
	minuto=minuto*60;
	if (minuto < 0) minuto=minuto*-1;
	minuto_lat=parseInt(minuto);
	segundo=minuto-minuto_lat;
	segundo=segundo*60;
	segundo_lat=parseInt(segundo);

	env.txtlatgraus.value=grau_lat;
	env.txtlatmin.value=minuto_lat;
	env.txtlatsec.value=segundo_lat;
	}


converter_decimal();

<?php } ?>







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
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('projeto_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('projeto_cia').value, 'Agrupamento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.projeto_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		incluir_relacionado();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('projeto_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.projeto_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		incluir_relacionado();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('projeto_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('projeto_cia').value, 'Modelo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.projeto_template.value = chave;
		document.env.template_nome.value = valor;
		incluir_relacionado();
		}		
<?php } ?>

function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('projeto_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.projeto_projeto.value = chave;
	document.env.projeto_gestao_nome.value = valor;
	incluir_relacionado();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('projeto_cia').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.projeto_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	incluir_relacionado();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('projeto_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.projeto_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	incluir_relacionado();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('projeto_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.projeto_tema.value = chave;
	document.env.tema_nome.value = valor;
	incluir_relacionado();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('projeto_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.gestao_projeto_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('projeto_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.projeto_fator.value = chave;
	document.env.fator_nome.value = valor;
	incluir_relacionado();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('projeto_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.projeto_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	incluir_relacionado();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('projeto_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.projeto_meta.value = chave;
	document.env.meta_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('projeto_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.projeto_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	incluir_relacionado();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('projeto_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('projeto_cia').value, 'Indicador','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.projeto_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	incluir_relacionado();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('projeto_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.projeto_acao.value = chave;
	document.env.acao_nome.value = valor;
	incluir_relacionado();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('projeto_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.projeto_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('projeto_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.projeto_risco.value = chave;
	document.env.risco_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('projeto_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('projeto_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.projeto_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('projeto_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('projeto_cia').value, 'Agenda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.projeto_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	incluir_relacionado();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('projeto_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('projeto_cia').value, 'Monitoramento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.projeto_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reuni?o', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('projeto_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.projeto_ata.value = chave;
	document.env.ata_nome.value = valor;
	incluir_relacionado();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('projeto_cia').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.projeto_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Cam?po SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('projeto_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.projeto_swot.value = chave;
	document.env.swot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('projeto_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('projeto_cia').value, 'Plano Operativo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.projeto_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jur?dico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('projeto_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('projeto_cia').value, 'Instrumento Jur?dico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.projeto_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('projeto_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('projeto_cia').value, 'Recurso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.projeto_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('projeto_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.projeto_problema.value = chave;
	document.env.problema_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('projeto_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('projeto_cia').value, 'Demanda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.projeto_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('projeto_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.projeto_programa.value = chave;
	document.env.programa_nome.value = valor;
	incluir_relacionado();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('projeto_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.projeto_licao.value = chave;
	document.env.licao_nome.value = valor;
	incluir_relacionado();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('projeto_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('projeto_cia').value, 'Evento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.projeto_evento.value = chave;
	document.env.evento_nome.value = valor;
	incluir_relacionado();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('projeto_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('projeto_cia').value, 'Link','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.projeto_link.value = chave;
	document.env.link_nome.value = valor;
	incluir_relacionado();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avalia??o', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('projeto_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('projeto_cia').value, 'Avalia??o','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.projeto_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	incluir_relacionado();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('projeto_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.projeto_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('projeto_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('projeto_cia').value, 'Brainstorm','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.projeto_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	incluir_relacionado();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('projeto_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('projeto_cia').value, 'Matriz GUT','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.projeto_gut.value = chave;
	document.env.gut_nome.value = valor;
	incluir_relacionado();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('projeto_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('projeto_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.projeto_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	incluir_relacionado();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('projeto_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('projeto_cia').value, 'Arquivo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.projeto_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	incluir_relacionado();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('F?rum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('projeto_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('projeto_cia').value, 'F?rum','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.projeto_forum.value = chave;
	document.env.forum_nome.value = valor;
	incluir_relacionado();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('projeto_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('projeto_cia').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.projeto_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	incluir_relacionado();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('projeto_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('projeto_cia').value, 'Compromisso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.projeto_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	incluir_relacionado();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('projeto_cia').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('projeto_cia').value, 'Painel','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.projeto_painel.value = chave;
	document.env.painel_nome.value = valor;
	incluir_relacionado();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Od?metro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('projeto_cia').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('projeto_cia').value, 'Od?metro','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.projeto_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	incluir_relacionado();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composi??o de Pain?is', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('projeto_cia').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('projeto_cia').value, 'Composi??o de Pain?is','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.projeto_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('projeto_cia').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.projeto_tr.value = chave;
	document.env.tr_nome.value = valor;
	incluir_relacionado();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('projeto_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.projeto_me.value = chave;
	document.env.me_nome.value = valor;
	incluir_relacionado();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('projeto_cia').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('projeto_cia').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.projeto_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	incluir_relacionado();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('projeto_cia').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.projeto_gestao_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	incluir_relacionado();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composi??es', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('projeto_cia').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('projeto_cia').value, 'Slideshow de Composi??es','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.projeto_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	incluir_relacionado();
	}	

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('projeto_cia').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('projeto_cia').value, 'Estudo de Viabilidade','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.projeto_projeto_viabilidade.value = chave;
	document.env.projeto_viabilidade_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('projeto_cia').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('projeto_cia').value, 'Termo de Abertura','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.projeto_projeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	incluir_relacionado();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estrat?gico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('projeto_cia').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('projeto_cia').value, 'Planejamento Estrat?gico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.projeto_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	incluir_relacionado();
	}		

	
function popSSTI() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('projeto_cia').value, window.setSSTI, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSSTI(chave, valor){
	limpar_tudo();
	document.env.projeto_ssti.value = chave;
	document.env.ssti_nome.value = valor;
	incluir_relacionado();
	}	
				
function popLaudo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('projeto_cia').value, window.setLaudo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLaudo(chave, valor){
	limpar_tudo();
	document.env.projeto_laudo.value = chave;
	document.env.laudo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popTrelo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('projeto_cia').value, window.setTrelo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTrelo(chave, valor){
	limpar_tudo();
	document.env.projeto_trelo.value = chave;
	document.env.trelo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTreloCartao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('projeto_cia').value, window.setTreloCartao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTreloCartao(chave, valor){
	limpar_tudo();
	document.env.projeto_trelo_cartao.value = chave;
	document.env.trelo_cartao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPDCL() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('projeto_cia').value, window.setPDCL, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCL(chave, valor){
	limpar_tudo();
	document.env.projeto_pdcl.value = chave;
	document.env.pdcl_nome.value = valor;
	incluir_relacionado();
	}				
	
function pop_pdcl_item() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=set_pdcl_item&tabela=pdcl_item&cia_id='+document.getElementById('projeto_cia').value, window.set_pdcl_item, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=set_pdcl_item&tabela=pdcl_item&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function set_pdcl_item(chave, valor){
	limpar_tudo();
	document.env.projeto_pdcl_item.value = chave;
	document.env.pdcl_item_nome.value = valor;
	incluir_relacionado();
	}	
	
function pop_os() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["os"])?>', 1000, 700, 'm=os&a=os_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=set_os&tabela=os&cia_id='+document.getElementById('projeto_cia').value, window.set_os, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=set_os&tabela=os&cia_id='+document.getElementById('projeto_cia').value, '<?php echo ucfirst($config["os"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function set_os(chave, valor){
	limpar_tudo();
	document.env.projeto_os.value = chave;
	document.env.os_nome.value = valor;
	incluir_relacionado();
	}	

function limpar_tudo(){
	document.env.projeto_gestao_nome.value = '';
	document.env.projeto_projeto.value = null;
	document.env.projeto_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.projeto_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.projeto_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.gestao_projeto_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.projeto_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.projeto_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.projeto_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.projeto_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.projeto_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.projeto_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.projeto_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.projeto_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.projeto_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.projeto_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.projeto_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.projeto_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.projeto_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.projeto_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.projeto_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.projeto_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.projeto_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.projeto_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.projeto_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.projeto_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.projeto_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.projeto_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.projeto_link.value = null;
	document.env.link_nome.value = '';
	document.env.projeto_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.projeto_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.projeto_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.projeto_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.projeto_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.projeto_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.projeto_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.projeto_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.projeto_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.projeto_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.projeto_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.projeto_template.value = null;
	document.env.template_nome.value = '';
	document.env.projeto_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.projeto_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.projeto_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.projeto_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.projeto_me.value = null;
	document.env.me_nome.value = '';
	document.env.projeto_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.projeto_gestao_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.projeto_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	document.env.projeto_projeto_viabilidade.value = null;
	document.env.projeto_viabilidade_nome.value = '';
	document.env.projeto_projeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.projeto_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	document.env.projeto_ssti.value = null;
	document.env.ssti_nome.value = '';
	document.env.projeto_laudo.value = null;
	document.env.laudo_nome.value = '';
	document.env.projeto_trelo.value = null;
	document.env.trelo_nome.value = '';
	document.env.projeto_trelo_cartao.value = null;
	document.env.trelo_cartao_nome.value = '';
	document.env.projeto_pdcl.value = null;
	document.env.pdcl_nome.value = '';
	document.env.projeto_pdcl_item.value = null;
	document.env.pdcl_item_nome.value = '';	
	document.env.projeto_os.value = null;
	document.env.os_nome.value = '';		
	}

function incluir_relacionado(){
	var f=document.env;
	
	xajax_incluir_relacionado(
	document.getElementById('projeto_id').value,
	document.getElementById('uuid').value,
	f.projeto_projeto.value,
	f.projeto_tarefa.value,
	f.projeto_perspectiva.value,
	f.projeto_tema.value,
	f.gestao_projeto_objetivo.value,
	f.projeto_fator.value,
	f.projeto_estrategia.value,
	f.projeto_meta.value,
	f.projeto_pratica.value,
	f.projeto_acao.value,
	f.projeto_canvas.value,
	f.projeto_risco.value,
	f.projeto_risco_resposta.value,
	f.projeto_indicador.value,
	f.projeto_calendario.value,
	f.projeto_monitoramento.value,
	f.projeto_ata.value,
	f.projeto_mswot.value,
	f.projeto_swot.value,
	f.projeto_operativo.value,
	f.projeto_instrumento.value,
	f.projeto_recurso.value,
	f.projeto_problema.value,
	f.projeto_demanda.value,
	f.projeto_programa.value,
	f.projeto_licao.value,
	f.projeto_evento.value,
	f.projeto_link.value,
	f.projeto_avaliacao.value,
	f.projeto_tgn.value,
	f.projeto_brainstorm.value,
	f.projeto_gut.value,
	f.projeto_causa_efeito.value,
	f.projeto_arquivo.value,
	f.projeto_forum.value,
	f.projeto_checklist.value,
	f.projeto_agenda.value,
	f.projeto_agrupamento.value,
	f.projeto_patrocinador.value,
	f.projeto_template.value,
	f.projeto_painel.value,
	f.projeto_painel_odometro.value,
	f.projeto_painel_composicao.value,
	f.projeto_tr.value,
	f.projeto_me.value,
	f.projeto_acao_item.value,
	f.projeto_gestao_beneficio.value,
	f.projeto_painel_slideshow.value,
	f.projeto_projeto_viabilidade.value,
	f.projeto_projeto_abertura.value,
	f.projeto_plano_gestao.value,
	f.projeto_ssti.value,
	f.projeto_laudo.value,
	f.projeto_trelo.value,
	f.projeto_trelo_cartao.value,
	f.projeto_pdcl.value,
	f.projeto_pdcl_item.value,
	f.projeto_os.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(projeto_gestao_id){
	xajax_excluir_gestao(document.getElementById('projeto_id').value, document.getElementById('uuid').value, projeto_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, projeto_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, projeto_gestao_id, direcao, document.getElementById('projeto_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$projeto_id && (
	$projeto_tarefa || 
	$projeto_projeto || 
	$projeto_perspectiva || 
	$projeto_tema || 
	$projeto_objetivo || 
	$projeto_fator || 
	$projeto_estrategia || 
	$projeto_meta || 
	$projeto_pratica || 
	$projeto_acao || 
	$projeto_canvas || 
	$projeto_risco || 
	$projeto_risco_resposta || 
	$projeto_indicador || 
	$projeto_calendario || 
	$projeto_monitoramento || 
	$projeto_ata || 
	$projeto_mswot || 
	$projeto_swot || 
	$projeto_operativo || 
	$projeto_instrumento || 
	$projeto_recurso || 
	$projeto_problema || 
	$projeto_demanda || 
	$projeto_programa || 
	$projeto_licao || 
	$projeto_evento || 
	$projeto_link || 
	$projeto_avaliacao || 
	$projeto_tgn || 
	$projeto_brainstorm || 
	$projeto_gut || 
	$projeto_causa_efeito || 
	$projeto_arquivo || 
	$projeto_forum || 
	$projeto_checklist || 
	$projeto_agenda || 
	$projeto_agrupamento || 
	$projeto_patrocinador || 
	$projeto_template || 
	$projeto_painel || 
	$projeto_painel_odometro || 
	$projeto_painel_composicao || 
	$projeto_tr || 
	$projeto_me || 
	$projeto_acao_item || 
	$projeto_beneficio || 
	$projeto_painel_slideshow || 
	$projeto_projeto_viabilidade || 
	$projeto_projeto_abertura || 
	$projeto_plano_gestao|| 
	$projeto_ssti || 
	$projeto_laudo || 
	$projeto_trelo || 
	$projeto_trelo_cartao || 
	$projeto_pdcl || 
	$projeto_pdcl_item || 
	$projeto_os
	)) echo 'incluir_relacionado();';
	?>	
		
</script>

