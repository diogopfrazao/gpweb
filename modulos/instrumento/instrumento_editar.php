<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $bd;

echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
$instrumento_id = intval(getParam($_REQUEST, 'instrumento_id', 0));

if (!$podeAdicionar && !$instrumento_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$podeEditar && $instrumento_id) $Aplic->redirecionar('m=publico&a=acesso_negado');




$instrumento_campo=intval(getParam($_REQUEST, 'instrumento_campo', 1));


$instrumento_projeto=getParam($_REQUEST, 'instrumento_projeto', null);
$instrumento_tarefa=getParam($_REQUEST, 'instrumento_tarefa', null);
$instrumento_perspectiva=getParam($_REQUEST, 'instrumento_perspectiva', null);
$instrumento_tema=getParam($_REQUEST, 'instrumento_tema', null);
$instrumento_objetivo=getParam($_REQUEST, 'instrumento_objetivo', null);
$instrumento_fator=getParam($_REQUEST, 'instrumento_fator', null);
$instrumento_estrategia=getParam($_REQUEST, 'instrumento_estrategia', null);
$instrumento_meta=getParam($_REQUEST, 'instrumento_meta', null);
$instrumento_pratica=getParam($_REQUEST, 'instrumento_pratica', null);
$instrumento_acao=getParam($_REQUEST, 'instrumento_acao', null);
$instrumento_canvas=getParam($_REQUEST, 'instrumento_canvas', null);
$instrumento_risco=getParam($_REQUEST, 'instrumento_risco', null);
$instrumento_risco_resposta=getParam($_REQUEST, 'instrumento_risco_resposta', null);
$instrumento_indicador=getParam($_REQUEST, 'instrumento_indicador', null);
$instrumento_calendario=getParam($_REQUEST, 'instrumento_calendario', null);
$instrumento_monitoramento=getParam($_REQUEST, 'instrumento_monitoramento', null);
$instrumento_ata=getParam($_REQUEST, 'instrumento_ata', null);
$instrumento_mswot=getParam($_REQUEST, 'instrumento_mswot', null);
$instrumento_swot=getParam($_REQUEST, 'instrumento_swot', null);
$instrumento_operativo=getParam($_REQUEST, 'instrumento_operativo', null);
$instrumento_instrumento=getParam($_REQUEST, 'instrumento_instrumento', null);
$instrumento_recurso=getParam($_REQUEST, 'instrumento_recurso', null);
$instrumento_problema=getParam($_REQUEST, 'instrumento_problema', null);
$instrumento_demanda=getParam($_REQUEST, 'instrumento_demanda', null);
$instrumento_programa=getParam($_REQUEST, 'instrumento_programa', null);
$instrumento_licao=getParam($_REQUEST, 'instrumento_licao', null);
$instrumento_evento=getParam($_REQUEST, 'instrumento_evento', null);
$instrumento_link=getParam($_REQUEST, 'instrumento_link', null);
$instrumento_avaliacao=getParam($_REQUEST, 'instrumento_avaliacao', null);
$instrumento_tgn=getParam($_REQUEST, 'instrumento_tgn', null);
$instrumento_brainstorm=getParam($_REQUEST, 'instrumento_brainstorm', null);
$instrumento_gut=getParam($_REQUEST, 'instrumento_gut', null);
$instrumento_causa_efeito=getParam($_REQUEST, 'instrumento_causa_efeito', null);
$instrumento_arquivo=getParam($_REQUEST, 'instrumento_arquivo', null);
$instrumento_forum=getParam($_REQUEST, 'instrumento_forum', null);
$instrumento_checklist=getParam($_REQUEST, 'instrumento_checklist', null);
$instrumento_agenda=getParam($_REQUEST, 'instrumento_agenda', null);
$instrumento_agrupamento=getParam($_REQUEST, 'instrumento_agrupamento', null);
$instrumento_patrocinador=getParam($_REQUEST, 'instrumento_patrocinador', null);
$instrumento_template=getParam($_REQUEST, 'instrumento_template', null);
$instrumento_painel=getParam($_REQUEST, 'instrumento_painel', null);
$instrumento_painel_odometro=getParam($_REQUEST, 'instrumento_painel_odometro', null);
$instrumento_painel_composicao=getParam($_REQUEST, 'instrumento_painel_composicao', null);
$instrumento_tr=getParam($_REQUEST, 'instrumento_tr', null);
$instrumento_me=getParam($_REQUEST, 'instrumento_me', null);
$instrumento_acao_item=getParam($_REQUEST, 'instrumento_acao_item', null);
$instrumento_beneficio=getParam($_REQUEST, 'instrumento_beneficio', null);
$instrumento_painel_slideshow=getParam($_REQUEST, 'instrumento_painel_slideshow', null);
$instrumento_projeto_viabilidade=getParam($_REQUEST, 'instrumento_projeto_viabilidade', null);
$instrumento_projeto_abertura=getParam($_REQUEST, 'instrumento_projeto_abertura', null);
$instrumento_plano_gestao=getParam($_REQUEST, 'instrumento_plano_gestao', null);
$instrumento_ssti=getParam($_REQUEST, 'instrumento_ssti', null);
$instrumento_laudo=getParam($_REQUEST, 'instrumento_laudo', null);
$instrumento_trelo=getParam($_REQUEST, 'instrumento_trelo', null);
$instrumento_trelo_cartao=getParam($_REQUEST, 'instrumento_trelo_cartao', null);
$instrumento_pdcl=getParam($_REQUEST, 'instrumento_pdcl', null);
$instrumento_pdcl_item=getParam($_REQUEST, 'instrumento_pdcl_item', null);
$instrumento_os=getParam($_REQUEST, 'instrumento_os', null);


require_once BASE_DIR.'/modulos/instrumento/instrumento.class.php';

$Aplic->carregarCKEditorJS();


$Aplic->carregarCalendarioJS();


$sql = new BDConsulta();

$msg = '';
$obj = new CInstrumento();




$numeracao_titulo=0;
$numeracao=0;



if ($instrumento_id){
	$obj->load($instrumento_id);
	$cia_id=$obj->instrumento_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

	if (
		$instrumento_projeto || 
		$instrumento_tarefa || 
		$instrumento_perspectiva || 
		$instrumento_tema || 
		$instrumento_objetivo || 
		$instrumento_fator || 
		$instrumento_estrategia || 
		$instrumento_meta || 
		$instrumento_pratica || 
		$instrumento_acao || 
		$instrumento_canvas || 
		$instrumento_risco || 
		$instrumento_risco_resposta || 
		$instrumento_indicador || 
		$instrumento_calendario || 
		$instrumento_monitoramento || 
		$instrumento_ata || 
		$instrumento_mswot || 
		$instrumento_swot || 
		$instrumento_operativo || 
		$instrumento_instrumento || 
		$instrumento_recurso || 
		$instrumento_problema || 
		$instrumento_demanda || 
		$instrumento_programa || 
		$instrumento_licao || 
		$instrumento_evento || 
		$instrumento_link || 
		$instrumento_avaliacao || 
		$instrumento_tgn || 
		$instrumento_brainstorm || 
		$instrumento_gut || 
		$instrumento_causa_efeito || 
		$instrumento_arquivo || 
		$instrumento_forum || 
		$instrumento_checklist || 
		$instrumento_agenda || 
		$instrumento_agrupamento || 
		$instrumento_patrocinador || 
		$instrumento_template || 
		$instrumento_painel || 
		$instrumento_painel_odometro || 
		$instrumento_painel_composicao || 
		$instrumento_tr || 
		$instrumento_me || 
		$instrumento_acao_item || 
		$instrumento_beneficio || 
		$instrumento_painel_slideshow || 
		$instrumento_projeto_viabilidade || 
		$instrumento_projeto_abertura || 
		$instrumento_plano_gestao|| 
		$instrumento_ssti || 
		$instrumento_laudo || 
		$instrumento_trelo || 
		$instrumento_trelo_cartao || 
		$instrumento_pdcl || 
		$instrumento_pdcl_item || 
		$instrumento_os
		){
		$sql->adTabela('cias');
		if ($instrumento_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
		elseif ($instrumento_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
		elseif ($instrumento_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
		elseif ($instrumento_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
		elseif ($instrumento_objetivo) $sql->esqUnir('objetivo','objetivo','objetivo_cia=cias.cia_id');
		elseif ($instrumento_fator) $sql->esqUnir('fator','fator','fator_cia=cias.cia_id');
		elseif ($instrumento_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
		elseif ($instrumento_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
		elseif ($instrumento_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
		elseif ($instrumento_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
		elseif ($instrumento_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
		elseif ($instrumento_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
		elseif ($instrumento_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
		elseif ($instrumento_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
		elseif ($instrumento_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
		elseif ($instrumento_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
		elseif ($instrumento_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
		elseif ($instrumento_mswot) $sql->esqUnir('mswot','mswot','mswot_cia=cias.cia_id');
		elseif ($instrumento_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
		elseif ($instrumento_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
		elseif ($instrumento_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
		elseif ($instrumento_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
		elseif ($instrumento_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
		elseif ($instrumento_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
		elseif ($instrumento_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
		elseif ($instrumento_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
		elseif ($instrumento_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
		elseif ($instrumento_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
		elseif ($instrumento_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
		elseif ($instrumento_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
		elseif ($instrumento_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
		elseif ($instrumento_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
		elseif ($instrumento_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
		elseif ($instrumento_arquivo) $sql->esqUnir('arquivo','arquivo','arquivo_cia=cias.cia_id');
		elseif ($instrumento_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
		elseif ($instrumento_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
		elseif ($instrumento_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
		elseif ($instrumento_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
		elseif ($instrumento_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
		elseif ($instrumento_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
		elseif ($instrumento_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
		elseif ($instrumento_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
		elseif ($instrumento_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
		elseif ($instrumento_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
		elseif ($instrumento_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');
		elseif ($instrumento_acao_item) $sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao_item_cia=cias.cia_id');
		elseif ($instrumento_beneficio) $sql->esqUnir('beneficio','beneficio','beneficio_cia=cias.cia_id');
		elseif ($instrumento_painel_slideshow) $sql->esqUnir('painel_slideshow','painel_slideshow','painel_slideshow_cia=cias.cia_id');
		elseif ($instrumento_projeto_viabilidade) $sql->esqUnir('projeto_viabilidade','projeto_viabilidade','projeto_viabilidade_cia=cias.cia_id');
		elseif ($instrumento_projeto_abertura) $sql->esqUnir('projeto_abertura','projeto_abertura','projeto_abertura_cia=cias.cia_id');
		elseif ($instrumento_plano_gestao) $sql->esqUnir('plano_gestao','plano_gestao','pg_cia=cias.cia_id');
		elseif ($instrumento_ssti) $sql->esqUnir('ssti','ssti','ssti_cia=cias.cia_id');
		elseif ($instrumento_laudo) $sql->esqUnir('laudo','laudo','laudo_cia=cias.cia_id');
		elseif ($instrumento_trelo) $sql->esqUnir('trelo','trelo','trelo_cia=cias.cia_id');
		elseif ($instrumento_trelo_cartao) $sql->esqUnir('trelo_cartao','trelo_cartao','trelo_cartao_cia=cias.cia_id');
		elseif ($instrumento_pdcl) $sql->esqUnir('pdcl','pdcl','pdcl_cia=cias.cia_id');
		elseif ($instrumento_pdcl_item) $sql->esqUnir('pdcl_item','pdcl_item','pdcl_item_cia=cias.cia_id');
		elseif ($instrumento_os) $sql->esqUnir('os','os','os_cia=cias.cia_id');

	
		if ($instrumento_tarefa) $sql->adOnde('tarefa_id = '.(int)$instrumento_tarefa);
		elseif ($instrumento_projeto) $sql->adOnde('projeto_id = '.(int)$instrumento_projeto);
		elseif ($instrumento_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$instrumento_perspectiva);
		elseif ($instrumento_tema) $sql->adOnde('tema_id = '.(int)$instrumento_tema);
		elseif ($instrumento_objetivo) $sql->adOnde('objetivo_id = '.(int)$instrumento_objetivo);
		elseif ($instrumento_fator) $sql->adOnde('fator_id = '.(int)$instrumento_fator);
		elseif ($instrumento_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$instrumento_estrategia);
		elseif ($instrumento_meta) $sql->adOnde('pg_meta_id = '.(int)$instrumento_meta);
		elseif ($instrumento_pratica) $sql->adOnde('pratica_id = '.(int)$instrumento_pratica);
		elseif ($instrumento_acao) $sql->adOnde('plano_acao_id = '.(int)$instrumento_acao);
		elseif ($instrumento_canvas) $sql->adOnde('canvas_id = '.(int)$instrumento_canvas);
		elseif ($instrumento_risco) $sql->adOnde('risco_id = '.(int)$instrumento_risco);
		elseif ($instrumento_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$instrumento_risco_resposta);
		elseif ($instrumento_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$instrumento_indicador);
		elseif ($instrumento_calendario) $sql->adOnde('calendario_id = '.(int)$instrumento_calendario);
		elseif ($instrumento_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$instrumento_monitoramento);
		elseif ($instrumento_ata) $sql->adOnde('ata_id = '.(int)$instrumento_ata);
		elseif ($instrumento_mswot) $sql->adOnde('mswot_id = '.(int)$instrumento_mswot);
		elseif ($instrumento_swot) $sql->adOnde('swot_id = '.(int)$instrumento_swot);
		elseif ($instrumento_operativo) $sql->adOnde('operativo_id = '.(int)$instrumento_operativo);
		elseif ($instrumento_instrumento) $sql->adOnde('instrumento_id = '.(int)$instrumento_instrumento);
		elseif ($instrumento_recurso) $sql->adOnde('recurso_id = '.(int)$instrumento_recurso);
		elseif ($instrumento_problema) $sql->adOnde('problema_id = '.(int)$instrumento_problema);
		elseif ($instrumento_demanda) $sql->adOnde('demanda_id = '.(int)$instrumento_demanda);
		elseif ($instrumento_programa) $sql->adOnde('programa_id = '.(int)$instrumento_programa);
		elseif ($instrumento_licao) $sql->adOnde('licao_id = '.(int)$instrumento_licao);
		elseif ($instrumento_evento) $sql->adOnde('evento_id = '.(int)$instrumento_evento);
		elseif ($instrumento_link) $sql->adOnde('link_id = '.(int)$instrumento_link);
		elseif ($instrumento_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$instrumento_avaliacao);
		elseif ($instrumento_tgn) $sql->adOnde('tgn_id = '.(int)$instrumento_tgn);
		elseif ($instrumento_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$instrumento_brainstorm);
		elseif ($instrumento_gut) $sql->adOnde('gut_id = '.(int)$instrumento_gut);
		elseif ($instrumento_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$instrumento_causa_efeito);
		elseif ($instrumento_arquivo) $sql->adOnde('arquivo_id = '.(int)$instrumento_arquivo);
		elseif ($instrumento_forum) $sql->adOnde('forum_id = '.(int)$instrumento_forum);
		elseif ($instrumento_checklist) $sql->adOnde('checklist_id = '.(int)$instrumento_checklist);
		elseif ($instrumento_agenda) $sql->adOnde('agenda_id = '.(int)$instrumento_agenda);
		elseif ($instrumento_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$instrumento_agrupamento);
		elseif ($instrumento_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$instrumento_patrocinador);
		elseif ($instrumento_template) $sql->adOnde('template_id = '.(int)$instrumento_template);
		elseif ($instrumento_painel) $sql->adOnde('painel_id = '.(int)$instrumento_painel);
		elseif ($instrumento_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$instrumento_painel_odometro);
		elseif ($instrumento_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$instrumento_painel_composicao);
		elseif ($instrumento_tr) $sql->adOnde('tr_id = '.(int)$instrumento_tr);
		elseif ($instrumento_me) $sql->adOnde('me_id = '.(int)$instrumento_me);
		elseif ($instrumento_acao_item) $sql->adOnde('plano_acao_item_id = '.(int)$instrumento_acao_item);
		elseif ($instrumento_beneficio) $sql->adOnde('beneficio_id = '.(int)$instrumento_beneficio);
		elseif ($instrumento_painel_slideshow) $sql->adOnde('painel_slideshow_id = '.(int)$instrumento_painel_slideshow);
		elseif ($instrumento_projeto_viabilidade) $sql->adOnde('projeto_viabilidade_id = '.(int)$instrumento_projeto_viabilidade);
		elseif ($instrumento_projeto_abertura) $sql->adOnde('projeto_abertura_id = '.(int)$instrumento_projeto_abertura);
		elseif ($instrumento_plano_gestao) $sql->adOnde('pg_id = '.(int)$instrumento_plano_gestao);
		elseif ($instrumento_ssti) $sql->adOnde('ssti_id = '.(int)$instrumento_ssti);
		elseif ($instrumento_laudo) $sql->adOnde('laudo_id = '.(int)$instrumento_laudo);
		elseif ($instrumento_trelo) $sql->adOnde('trelo_id = '.(int)$instrumento_trelo);
		elseif ($instrumento_trelo_cartao) $sql->adOnde('trelo_cartao_id = '.(int)$instrumento_trelo_cartao);
		elseif ($instrumento_pdcl) $sql->adOnde('pdcl_id = '.(int)$instrumento_pdcl);
		elseif ($instrumento_pdcl_item) $sql->adOnde('pdcl_item_id = '.(int)$instrumento_pdcl_item);
		elseif ($instrumento_os) $sql->adOnde('os_id = '.(int)$instrumento_os);
		$sql->adCampo('cia_id');
		$cia_id = $sql->Resultado();
		$sql->limpar();
		}
	}

$sql->adTabela('instrumento_campo');
$sql->adCampo('instrumento_campo.*');
$sql->adOnde('instrumento_campo_id ='.(int)($instrumento_id ? $obj->instrumento_campo : $instrumento_campo));
$exibir=$sql->linha();
$sql->limpar();



if ($instrumento_id && !$obj->instrumento_nome) {
	$Aplic->setMsg(ucfirst($config['instrumento']));
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=instrumento&a=instrumento_lista');
	}
$ttl = ($instrumento_id ? 'Editar '.$exibir['instrumento_campo_nome'] : 'Adicionar '.$exibir['instrumento_campo_nome']);
$botoesTitulo = new CBlocoTitulo($ttl, 'instrumento.png', $m, $m.'.'.$a);

$botoesTitulo->mostrar();

$cias_selecionadas = array();
$usuarios_selecionados=array();
$depts_selecionados=array();
$contatos_selecionados=array();
$recursos_selecionados=array();
if ($instrumento_id) {
	$sql->adTabela('instrumento_designados');
	$sql->adCampo('usuario_id');
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('instrumento_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('instrumento_id ='.(int)$instrumento_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();


	$sql->adTabela('instrumento_contatos');
	$sql->adCampo('contato_id');
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id);
	$contatos_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('instrumento_recursos');
	$sql->adCampo('DISTINCT recurso_id');
	$sql->adOnde('instrumento_id = '.(int)$instrumento_id);
	$recursos_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('instrumento_cia');
		$sql->adCampo('instrumento_cia_cia');
		$sql->adOnde('instrumento_cia_instrumento = '.(int)$instrumento_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_instrumento_aed" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="instrumento_id" id="instrumento_id" value="'.$instrumento_id.'" />';
echo '<input name="instrumento_usuarios" id="instrumento_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="instrumento_depts" id="instrumento_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="instrumento_contatos" id="instrumento_contatos" type="hidden" value="'.implode(',', $contatos_selecionados).'" />';
echo '<input name="instrumento_recursos" id="instrumento_recursos" type="hidden" value="'.implode(',', $recursos_selecionados).'" />';
echo '<input name="instrumento_cias" id="instrumento_cias"  id="instrumento_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
$uuid=($instrumento_id ? null : uuid());
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';
//avisar se houve projeto com mesmo nome
echo '<input type="hidden" id="existe_instrumento" name="existe_instrumento" value="0" />';




echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';


if ($exibir['instrumento_identificacao']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_identificacao_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';

echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_nome_leg'].'</td><td align="left"><input type="text" class="texto" name="instrumento_nome" style="width:400px" value="'.(isset($obj->instrumento_nome) ? $obj->instrumento_nome : '').'"></td></tr>';
if ($exibir['instrumento_entidade']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_entidade_leg'].':</td><td align="left"><input type="text" class="texto" name="instrumento_entidade" style="width:400px" value="'.(isset($obj->instrumento_entidade) ? $obj->instrumento_entidade : '').'"></td></tr>';
else echo '<input type="hidden" name="instrumento_entidade" id="instrumento_entidade" value="'.$obj->instrumento_entidade.'" />';
if ($exibir['instrumento_entidade_cnpj']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_entidade_cnpj_leg'].':</td><td align="left"><input type="text" class="texto" name="instrumento_entidade_cnpj" style="width:400px" value="'.(isset($obj->instrumento_entidade_cnpj) ? $obj->instrumento_entidade_cnpj : '').'"></td></tr>';
else echo '<input type="hidden" name="instrumento_entidade_cnpj" id="instrumento_entidade_cnpj" value="'.$obj->instrumento_entidade_cnpj.'" />';
if ($exibir['instrumento_entidade_codigo']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_entidade_codigo_leg'].':</td><td align="left"><input type="text" class="texto" name="instrumento_entidade_codigo" style="width:400px" value="'.(isset($obj->instrumento_entidade_codigo) ? $obj->instrumento_entidade_codigo : '').'"></td></tr>';
else echo '<input type="hidden" name="instrumento_entidade_codigo" id="instrumento_entidade_codigo" value="'.$obj->instrumento_entidade_codigo.'" />';

$sql->adTabela('instrumento_campo');
$sql->adCampo('instrumento_campo_id, instrumento_campo_nome');
$campos_vetor=$sql->listaVetorChave('instrumento_campo_id','instrumento_campo_nome');
$sql->limpar();


if ($exibir['instrumento_tipo']) {
	echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_tipo_leg'].':</td><td align="left">'.selecionaVetor($campos_vetor, 'instrumento_campo', 'class=texto size=1 style="width:395px;"', ($instrumento_id ? $obj->instrumento_campo : $instrumento_campo)).'</td></tr>';
	}
else {
	echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$exibir['instrumento_tipo_leg'].':</td><td align="left">'.selecionaVetor($campos_vetor, 'instrumento_campo', 'class=texto size=1 style="width:395px;"', ($instrumento_id ? $obj->instrumento_campo : $instrumento_campo)).'</td></tr>';
	//echo '<input type="hidden" name="instrumento_campo" id="instrumento_campo" value="'.($instrumento_id ? $obj->instrumento_campo : $instrumento_campo).'" />';

	}

if ($exibir['instrumento_numero']) {
	$proximo='';
	if (!$instrumento_id){
		$sql->adTabela('instrumento');
		$sql->adCampo('count(instrumento_id)');
		$sql->adOnde('instrumento_campo ='.(int)$instrumento_campo);
		$sql->adOnde('ano(instrumento_data_inicio) ='.date('Y'));
		$sql->adOnde('instrumento_cia ='.(int)$cia_id);
		$qnt = $sql->resultado();
		$sql->limpar();
		$proximo=$qnt+1;
		$proximo=($proximo+1 < 100 ? '0' : '').($proximo+1 < 10 ? '0' : '').$proximo;
		}
	echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_numero_leg'].':</td><td align="left"><input type="text" class="texto" name="instrumento_numero" style="width:400px" value="'.($obj->instrumento_id ? $obj->instrumento_numero : $proximo).'"></td></tr>';
	}
else echo '<input type="hidden" name="instrumento_numero" id="instrumento_numero" value="'.$obj->instrumento_numero.'" />';
if ($exibir['instrumento_ano']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_ano_leg'].':</td><td align="left"><input type="text" class="texto" name="instrumento_ano" style="width:400px" value="'.(isset($obj->instrumento_ano) ? $obj->instrumento_ano : date('Y')).'"></td></tr>';
else echo '<input type="hidden" name="instrumento_ano" id="instrumento_ano" value="'.$obj->instrumento_ano.'" />';
if ($exibir['instrumento_prorrogavel']) echo '<tr><td align="right" nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_prorrogavel_leg'].':</td><td><input type="checkbox" value="1" name="instrumento_prorrogavel" '.($obj->instrumento_prorrogavel ? 'checked="checked"' : '').' /></td></tr>';
else echo '<input type="hidden" name="instrumento_prorrogavel" id="instrumento_prorrogavel" value="'.$obj->instrumento_prorrogavel.'" />';
if ($exibir['instrumento_situacao']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_situacao_leg'].':</td><td align="left" >'.selecionaVetor(getSisValor('SituacaoInstrumento','','','',true,0), 'instrumento_situacao', 'class="texto" style="width:400px"', (isset($obj->instrumento_situacao) ? $obj->instrumento_situacao : '')).'</td></tr>';
else echo '<input type="hidden" name="instrumento_situacao" id="instrumento_situacao" value="'.$obj->instrumento_situacao.'" />';
if ($exibir['instrumento_valor']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_valor_leg'].':</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="instrumento_valor" id="instrumento_valor" value="'.(isset($obj->instrumento_valor) ? number_format($obj->instrumento_valor,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.'):'').'" size="22" onChange="recalculo_instrumento_valor();" /></td></tr>';
else echo '<input type="hidden" name="instrumento_valor" id="instrumento_valor" value="'.(isset($obj->instrumento_valor) ? number_format($obj->instrumento_valor,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.'):'').'" />';


echo '<input type="hidden" name="instrumento_valor_atual" id="instrumento_valor_atual" value="'.$obj->instrumento_valor_atual.'" />';


if ($exibir['instrumento_valor_contrapartida']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_valor_contrapartida_leg'].':</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="instrumento_valor_contrapartida" id="instrumento_valor_contrapartida" value="'.(isset($obj->instrumento_valor_contrapartida) ? number_format($obj->instrumento_valor_contrapartida,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.'):'').'" size="22" /></td></tr>';
else echo '<input type="hidden" name="instrumento_valor_contrapartida" id="instrumento_valor_contrapartida" value="'.(isset($obj->instrumento_valor_contrapartida) ? number_format($obj->instrumento_valor_contrapartida,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.'):'').'" />';
if ($exibir['instrumento_valor_repasse']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_valor_repasse_leg'].':</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="instrumento_valor_repasse" id="instrumento_valor_repasse" value="'.(isset($obj->instrumento_valor_repasse) ? number_format($obj->instrumento_valor_repasse,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.'):'').'" size="22" /></td></tr>';
else echo '<input type="hidden" name="instrumento_valor_repasse" id="instrumento_valor_repasse" value="'.(isset($obj->instrumento_valor_repasse) ? number_format($obj->instrumento_valor_repasse,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.'):'').'" />';


if ($exibir['instrumento_fim_contrato']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_fim_contrato_leg'].':</td><td><input type="hidden" name="instrumento_fim_contrato" id="instrumento_fim_contrato" value="'.(isset($obj->instrumento_fim_contrato) ? $obj->instrumento_fim_contrato : '').'" /><input type="text" name="instrumento_fim_contrato_texto" style="width:70px;" id="instrumento_fim_contrato_texto" onchange="setData(\'env\', \'instrumento_fim_contrato_texto\' , \'instrumento_fim_contrato\');" value="'.(isset($obj->instrumento_fim_contrato) ? retorna_data($obj->instrumento_fim_contrato, false) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data.').'<a href="javascript: void(0);" ><img id="botao_instrumento_fim_contrato" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
else echo '<input type="hidden" name="instrumento_fim_contrato" id="instrumento_fim_contrato" value="'.$obj->instrumento_fim_contrato.'" />';


if ($Aplic->profissional && $Aplic->modulo_ativo('financeiro') && $Aplic->checarModulo('financeiro', 'acesso')) {
	$sql->adTabela('financeiro_config');
	$sql->adCampo('financeiro_config_campo, financeiro_config_valor');
	$configuracao_financeira = $sql->listaVetorChave('financeiro_config_campo','financeiro_config_valor');
	$sql->limpar();
	
	if ($configuracao_financeira['organizacao']=='sema_mt') {
		$resultado = $bd->Execute("SHOW COLUMNS FROM financeiro_ne LIKE 'NUMR_EMP'");
		$existe = ($resultado->RecordCount() ? TRUE : FALSE);
		if (!$existe)$configuracao_financeira['organizacao']=null;
		}
	}


if ($Aplic->profissional && $Aplic->modulo_ativo('financeiro') && $Aplic->checarModulo('financeiro', 'acesso') && $configuracao_financeira['organizacao']=='sema_mt') {
		
	//Saldo de cada empenho
	$sql->adTabela('financeiro_rel_ne');
	$sql->esqUnir('financeiro_ne','financeiro_ne', 'financeiro_rel_ne_ne=financeiro_ne_id');
	$sql->adOnde('financeiro_rel_ne_instrumento = '.(int)$instrumento_id);
	$sql->adCampo('financeiro_ne_id, NUMR_EMP, VALR_EMP, financeiro_rel_ne_valor');
	if ($configuracao_financeira['organizacao']=='sema_mt') $sql->adCampo('(SELECT SUM(VALR_ESTORNO) FROM financeiro_estorno_ne_fiplan WHERE NUMR_DOCUMENTO_ESTORNADO=financeiro_ne.NUMR_EMP AND NUMR_EMP_ESTORNO IS NOT NULL) AS estorno');
	else $sql->adCampo('0 AS estorno');
	if ($configuracao_financeira['organizacao']=='sema_mt') $sql->adCampo('(SELECT SUM(VALR_GCV) FROM financeiro_gcv WHERE financeiro_gcv.NUMR_EMP=financeiro_ne.NUMR_EMP AND NUMR_GCV_ESTORNO IS NULL) AS gcv');
	else $sql->adCampo('0 AS gcv');
	
	$sql->adCampo('formatar_data(DATA_EMP, \'%d/%m/%Y\') AS data');
	
	$sql->adOrdem('DATA_EMP DESC, NUMR_EMP ASC');
	
	$lista_ne=$sql->lista();
	$sql->limpar();
	
	if (count($lista_ne)){
		echo '<tr><td align=right width=50 style="white-space: nowrap;">Saldo(Empenho):</td><td ><table cellspacing=0 cellpadding=0 class="tbl1">';
		echo '<tr>
		<th >Data</th>
		<th >Nº Empenho</th>
		<th >Valor</th>
		<th >Estorno</th>
		<th >GVC</th>
		<th >Valor Atual</th>
		<th >Alocado</th>
		<th >Saldo</th>
		</tr>';
		
		$total_VALR_EMP=0;
		$total_estorno=0;
		$total_gcv=0;
		$total_soma_ne=0;
	
		foreach ($lista_ne as $ne) {
			
			$soma_ne=$ne['financeiro_rel_ne_valor'];
			echo '<tr>
			<td align=right >'.$ne['data'].'</td>
			<td ><a href="javascript: void(0);" onclick="popNE('.$ne['financeiro_ne_id'].')" width=250>'.substr($ne['NUMR_EMP'], 0, 5).'.'.substr($ne['NUMR_EMP'], 5, 4).'.'.substr($ne['NUMR_EMP'], 9, 2).'.'.substr($ne['NUMR_EMP'], 11, 6).'-'.substr($ne['NUMR_EMP'], 17, 1).'</a></td>
			<td align=right >'.number_format($ne['VALR_EMP'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right >'.number_format($ne['estorno'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right >'.number_format($ne['gcv'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right >'.number_format($ne['VALR_EMP']-$ne['estorno'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right >'.number_format($ne['financeiro_rel_ne_valor'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right ><a href="javascript: void(0);" onclick="popExtrato(\'ne\','.$ne['financeiro_ne_id'].')">'.number_format($ne['VALR_EMP']-$ne['estorno']+$ne['gcv']-$ne['financeiro_rel_ne_valor'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</a></td>';
			echo '</tr>';
			
			$total_VALR_EMP+=$ne['VALR_EMP'];
			$total_estorno+=$ne['estorno'];
			$total_gcv+=$ne['gcv'];
			$total_soma_ne+=$ne['financeiro_rel_ne_valor'];
			}
		
		
		echo '<tr>
			<td colspan=2  align=center><b>Total</b></td>
			<td align=right ><b>'.number_format($total_VALR_EMP,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_estorno,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_gcv,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_VALR_EMP-$total_estorno,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_soma_ne,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_VALR_EMP-$total_estorno+$total_gcv-$total_soma_ne,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>';
		echo '</tr>';
			
		echo '</table></td></tr>';
		
		
		echo '<tr><td align=right width=50 style="white-space: nowrap;">Empenhos alocados:</td><td >'.number_format($total_soma_ne,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
		
		}
	
	//CHECAR SE TEM liquidação
	//Saldo de cada liquidação
	$sql->adTabela('financeiro_rel_ns');
	$sql->esqUnir('financeiro_ns','financeiro_ns', 'financeiro_rel_ns_ns=financeiro_ns_id');
	$sql->adOnde('financeiro_rel_ns_instrumento = '.(int)$instrumento_id);
	$sql->adCampo('financeiro_ns_id, NUMR_LIQ, VALR_LIQ, financeiro_rel_ns_valor');
	if ($configuracao_financeira['organizacao']=='sema_mt') $sql->adCampo('(SELECT SUM(VALOR_ESTORNO) FROM financeiro_estorno_ns_fiplan WHERE financeiro_estorno_ns_fiplan.NUMR_LIQ=financeiro_ns.NUMR_LIQ AND NUMR_ESTORNO_LIQ IS NOT NULL) AS estorno');
	else $sql->adCampo('0 AS estorno');
	
	if ($configuracao_financeira['organizacao']=='sema_mt') $sql->adCampo('(SELECT SUM(VALR_GCV) FROM financeiro_gcv WHERE financeiro_gcv.NUMR_LIQ=financeiro_ns.NUMR_LIQ AND NUMR_GCV_ESTORNO IS NULL) AS gcv');
	else $sql->adCampo('0 AS gcv');
	
	$sql->adCampo('formatar_data(DATA_LIQ, \'%d/%m/%Y\') AS data');
	
	$sql->adOrdem('DATA_LIQ DESC, NUMR_LIQ ASC');
	
	$lista_ns=$sql->lista();
	$sql->limpar();
	if (count($lista_ns)){
		echo '<tr><td align=right width=50 style="white-space: nowrap;">Saldo(Liquidação):</td><td ><table cellspacing=0 cellpadding=0 class="tbl1">';
		echo '<tr>
		<th >Data</th>
		<th >Nº Liquidação</th>
		<th >Valor</th>
		<th >Estorno</th>
		<th >GVC</th>
		<th >Valor Atual</th>
		<th >Alocado</th>
		<th >Saldo</th></tr>';
		
		$total_VALR_LIQ=0;
		$total_estorno=0;
		$total_gcv=0;
		$total_soma_ns=0;
		
		foreach ($lista_ns as $ns) {		
			$sql->limpar();
			echo '<tr>
			<td align=right >'.$ns['data'].'</td>
			<td ><a href="javascript: void(0);" onclick="popNS('.$ns['financeiro_ns_id'].')" width=250>'.substr($ns['NUMR_LIQ'], 0, 5).'.'.substr($ns['NUMR_LIQ'], 5, 4).'.'.substr($ns['NUMR_LIQ'], 9, 2).'.'.substr($ns['NUMR_LIQ'], 11, 6).'-'.substr($ns['NUMR_LIQ'], 17, 1).'</a></td>
			<td >'.number_format($ns['VALR_LIQ'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right >'.number_format($ns['estorno'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right >'.number_format($ns['gcv'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right >'.number_format($ns['VALR_LIQ']-$ns['estorno'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right >'.number_format($ns['financeiro_rel_ns_valor'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right ><a href="javascript: void(0);" onclick="popExtrato(\'ns\','.$ns['financeiro_ns_id'].')">'.number_format($ns['VALR_LIQ']-$ns['estorno']+$ns['gcv']-$ns['financeiro_rel_ns_valor'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</a></td></tr>';
			
			$total_VALR_LIQ+=$ns['VALR_LIQ'];
			$total_estorno+=$ns['estorno'];
			$total_gcv+=$ns['gcv'];
			$total_soma_ns+=$ns['financeiro_rel_ns_valor'];
			
			}
			
		echo '<tr>
			<td colspan=2  align=center><b>Total</b></td>
			<td align=right ><b>'.number_format($total_VALR_LIQ,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_estorno,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_gcv,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_VALR_LIQ-$total_estorno,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_soma_ns,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_VALR_LIQ-$total_estorno+$total_gcv-$total_soma_ns,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>';
		echo '</tr>';
			
		echo '</table></td></tr>';
		
		
		echo '<tr><td align=right width=50 style="white-space: nowrap;">Liquidações alocadas:</td><td >'.number_format($total_soma_ns,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
		}
		
	//CHECAR SE TEM pagamento
	//Saldo de cada OB
	$sql->adTabela('financeiro_rel_ob');
	$sql->esqUnir('financeiro_ob','financeiro_ob', 'financeiro_rel_ob_ob=financeiro_ob_id');
	$sql->adOnde('financeiro_rel_ob_instrumento = '.(int)$instrumento_id);
	$sql->adCampo('financeiro_ob_id, NUMR_NOB, VALR_NOB, financeiro_rel_ob_valor');
	
	if ($configuracao_financeira['organizacao']=='sema_mt') $sql->adCampo('(SELECT SUM(VALR_NOB) FROM financeiro_estorno_ob_fiplan WHERE financeiro_estorno_ob_fiplan.NUM_NOB=financeiro_ob.NUMR_NOB AND NUMR_NOB_ESTORNO IS NOT NULL) AS estorno');
	else $sql->adCampo('0 AS estorno');
	
	if ($configuracao_financeira['organizacao']=='sema_mt') $sql->adCampo('(SELECT SUM(VALR_GCV) FROM financeiro_gcv WHERE financeiro_gcv.NUMR_NOB=financeiro_ob.NUMR_NOB AND NUMR_GCV_ESTORNO IS NULL) AS gcv');
	else $sql->adCampo('0 AS gcv');
	
	$sql->adCampo('formatar_data(DATA_EMISSAO, \'%d/%m/%Y\') AS data');
	
	$sql->adOrdem('DATA_EMISSAO DESC, NUMR_NOB ASC');

	$lista_ob=$sql->lista();
	$sql->limpar();

	if (count($lista_ob)){
		echo '<tr><td align=right width=50 style="white-space: nowrap;">Saldo(Pagamento):</td><td ><table cellspacing=0 cellpadding=0 class="tbl1">';
		echo '<tr>
		<th >Data</th>
		<th >Nº Pagamento</th>
		<th >Valor</th>
		<th >Estorno</th>
		<th >GVC</th>
		<th >Valor Atual</th>
		<th >Alocado</th>
		<th >Saldo</th>
		</tr>';
		
		$total_VALR_NOB=0;
		$total_estorno=0;
		$total_gcv=0;
		$total_soma_ob=0;
		
		foreach ($lista_ob as $ob) {
			echo '<tr>
			<td align=right >'.$ob['data'].'</td>
			<td ><a href="javascript: void(0);" onclick="popOB('.$ob['financeiro_ob_id'].')" width=250>'.substr($ob['NUMR_NOB'], 0, 5).'.'.substr($ob['NUMR_NOB'], 5, 4).'.'.substr($ob['NUMR_NOB'], 9, 2).'.'.substr($ob['NUMR_NOB'], 11, 6).'-'.substr($ob['NUMR_NOB'], 17, 1).'</td>
			<td >'.number_format($ob['VALR_NOB'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right >'.number_format($ob['estorno'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right >'.number_format($ob['gcv'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right >'.number_format($ob['VALR_NOB']-$ob['estorno'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right >'.number_format($ob['financeiro_rel_ob_valor'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>
			<td align=right ><a href="javascript: void(0);" onclick="popExtrato(\'ob\','.$ob['financeiro_ob_id'].')">'.number_format($ob['VALR_NOB']-$ob['estorno']+$ob['gcv']-$ob['financeiro_rel_ob_valor'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</a></td></tr>';
			$total_VALR_NOB+=$ob['VALR_NOB'];
			$total_estorno+=$ob['estorno'];
			$total_gcv+=$ob['gcv'];
			$total_soma_ob+=$ob['financeiro_rel_ob_valor'];
			}
			
		echo '<tr>
			<td colspan=2  align=center><b>Total</b></td>
			<td align=right ><b>'.number_format($total_VALR_NOB,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_estorno,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_gcv,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_VALR_NOB-$total_estorno,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_soma_ob,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>
			<td align=right ><b>'.number_format($total_VALR_NOB-$total_estorno+$total_gcv-$total_soma_ob,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td>';
		echo '</tr>';
			
		echo '</table></td></tr>';
		
		
		echo '<tr><td align=right width=50 style="white-space: nowrap;">Ordens bancárias alocadas:</td><td >'.number_format($total_soma_ob,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
		}
	}













if ($exibir['instrumento_identificacao']) echo '</table></fieldset></td></tr>';




$numeracao=0;
if ($exibir['instrumento_demandante']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_demandante_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';


echo '<tr><td align=right style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['organizacao']).' Responsável', 'Selecione '.$config['genero_organizacao'].' '.$config['organizacao'].' d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'instrumento_cia', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om();"').'</div></td></tr>';


if ($exibir['instrumento_cias'] && $Aplic->profissional) {
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
	echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}

if ($Aplic->profissional) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="instrumento_dept" id="instrumento_dept" value="'.$obj->instrumento_dept.'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($obj->instrumento_dept).'" style="width:400px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';


if ($exibir['instrumento_depts'] && $Aplic->profissional) {
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
	else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:400px;"><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';
	}

echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Responsável pel'.$config['genero_instrumento'].' '.$config['instrumento'], 'Tod'.$config['genero_instrumento'].' '.$config['instrumento'].' deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="instrumento_responsavel" name="instrumento_responsavel" value="'.($obj->instrumento_responsavel ? $obj->instrumento_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->instrumento_responsavel ? $obj->instrumento_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';


$saida_usuarios='';
if (count($usuarios_selecionados)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($usuarios_selecionados[0],'','','esquerda');
		$qnt_lista_usuarios=count($usuarios_selecionados);
		if ($qnt_lista_usuarios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s Designados', 'Clique para visualizar '.$config['genero_usuario'].'s demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		}
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:400px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';


if ($exibir['instrumento_supervisor']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['supervisor']), ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' poderá ter '.($config['genero_supervisor']=='o' ? 'um' : 'uma').' '.$config['supervisor'].' relacionad'.$config['genero_supervisor'].'.').ucfirst($config['supervisor']).':'.dicaF().'</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="instrumento_supervisor" name="instrumento_supervisor" value="'.$obj->instrumento_supervisor.'" /><input type="text" id="nome_supervisor" name="nome_supervisor" value="'.nome_om($obj->instrumento_supervisor,$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popSupervisor();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="instrumento_supervisor" id="instrumento_supervisor" value="'.$obj->instrumento_supervisor.'" />';
if ($exibir['instrumento_autoridade']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['autoridade']), ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' poderá ter '.($config['genero_autoridade']=='o' ? 'um' : 'uma').' '.$config['autoridade'].' relacionad'.$config['genero_autoridade'].'.').ucfirst($config['autoridade']).':'.dicaF().'</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="instrumento_autoridade" name="instrumento_autoridade" value="'.$obj->instrumento_autoridade.'" /><input type="text" id="nome_autoridade" name="nome_autoridade" value="'.nome_om($obj->instrumento_autoridade,$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popAutoridade();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="instrumento_autoridade" id="instrumento_autoridade" value="'.$obj->instrumento_autoridade.'" />';
if ($exibir['instrumento_cliente']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['cliente']), ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' poderá ter '.($config['genero_cliente']=='o' ? 'um' : 'uma').' '.$config['cliente'].' relacionad'.$config['genero_cliente'].'.').ucfirst($config['cliente']).':'.dicaF().'</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="instrumento_cliente" name="instrumento_cliente" value="'.$obj->instrumento_cliente.'" /><input type="text" id="nome_cliente" name="nome_cliente" value="'.nome_om($obj->instrumento_cliente,$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popCliente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="instrumento_cliente" id="instrumento_cliente" value="'.$obj->instrumento_cliente.'" />';
if ($exibir['instrumento_fiscal']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_fiscal_leg'].':</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="instrumento_fiscal" name="instrumento_fiscal" value="'.$obj->instrumento_fiscal.'" /><input type="text" id="nome_instrumento_fiscal" name="nome_instrumento_fiscal" value="'.nome_om($obj->instrumento_fiscal,$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popFiscal();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="instrumento_fiscal" id="instrumento_fiscal" value="'.$obj->instrumento_fiscal.'" />';
if ($exibir['instrumento_fiscal_substituto']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_fiscal_substituto_leg'].':</td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="instrumento_fiscal_substituto" name="instrumento_fiscal_substituto" value="'.$obj->instrumento_fiscal_substituto.'" /><input type="text" id="nome_instrumento_fiscal_substituto" name="nome_instrumento_fiscal_substituto" value="'.nome_om($obj->instrumento_fiscal_substituto,$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popFiscalSubstituto();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="instrumento_fiscal_substituto" id="instrumento_fiscal_substituto" value="'.$obj->instrumento_fiscal_substituto.'" />';

if ($exibir['instrumento_demandante']) echo '</table></fieldset></td></tr>';



$numeracao=0;	
if ($exibir['instrumento_adtivo']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_adtivo_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';

$prorrogacao_tipo=array(0=>'dias', 1=>'meses', 2=>'anos');
if ($exibir['instrumento_prazo_prorrogacao']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_prazo_prorrogacao_leg'].':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="instrumento_prazo_prorrogacao" id="instrumento_prazo_prorrogacao" value="'.(isset($obj->instrumento_prazo_prorrogacao) ? $obj->instrumento_prazo_prorrogacao : '').'" size="10"></td><td> '.selecionaVetor($prorrogacao_tipo, 'instrumento_prazo_prorrogacao_tipo', 'class="texto" style="width:70px"', (isset($obj->instrumento_prazo_prorrogacao_tipo) ? $obj->instrumento_prazo_prorrogacao_tipo : '')).'</td></tr></table></td></tr>';
else echo '<input type="hidden" name="instrumento_prazo_prorrogacao" id="instrumento_prazo_prorrogacao" value="'.$obj->instrumento_prazo_prorrogacao.'" />';
if ($exibir['instrumento_acrescimo']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_acrescimo_leg'].':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="recalculo_instrumento_acrescimo();" name="instrumento_acrescimo" id="instrumento_acrescimo" value="'.($obj->instrumento_acrescimo ? number_format($obj->instrumento_acrescimo,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.'):'').'" size="10" />%</td><td>&nbsp;&nbsp;&nbsp;R$&nbsp;</td><td><div id="calculo_acrescimo">'.($obj->instrumento_acrescimo ? number_format($obj->instrumento_valor*($obj->instrumento_acrescimo/100),($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') : '').'</div></td></tr></table></td></tr>';
else echo '<input type="hidden" name="instrumento_acrescimo" id="instrumento_acrescimo" value="'.($obj->instrumento_acrescimo ? number_format($obj->instrumento_acrescimo,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.'):'').'" />';
if ($exibir['instrumento_supressao']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_supressao_leg'].':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="recalculo_instrumento_supressao();" name="instrumento_supressao" id="instrumento_supressao" value="'.($obj->instrumento_supressao ? number_format($obj->instrumento_supressao,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.'):'').'" size="10" />%</td><td>&nbsp;&nbsp;&nbsp;R$&nbsp;</td><td><div id="calculo_supressao">'.($obj->instrumento_supressao ? number_format($obj->instrumento_valor*($obj->instrumento_supressao/100),($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') : '').'</div></td></tr></table></td></tr>';
else echo '<input type="hidden" name="instrumento_supressao" id="instrumento_supressao" value="'.($obj->instrumento_supressao ? number_format($obj->instrumento_valor*($obj->instrumento_supressao/100),($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') : '').'" />';

if ($exibir['instrumento_adtivo']) echo '</table></fieldset></td></tr>';
	


$data_texto = new CData();
$numeracao=0;	
if ($exibir['instrumento_avulso_custo']){ 
	echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_avulso_custo_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';
	
	$unidade=getSisValor('TipoUnidade');
	
	echo '<input type="hidden" name="instrumento_avulso_custo_id" id="instrumento_avulso_custo_id" value="" />';
	echo '<input type="hidden" name="apoio1" id="apoio1" value="" />';
	
	
	echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0>';
	echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;"></legend><table cellspacing=1 cellpadding=0>';
		
    echo '<input type="hidden" name="instrumento_avulso_custo_servico" id="instrumento_avulso_custo_servico" value="0" />';
    echo '<tr><td align="right" style="white-space: nowrap" width=150>'.dica('Tipo', 'Tipo de item.').'Tipo:'.dicaF().'</td><td><input type="radio" name="custo_servico" id="custo_servico_0" value="0" checked onChange="mudar_tipo(this);">Material<input type="radio" name="custo_servico" id="custo_servico_1" value="1" onChange="mudar_tipo(this);">Serviço</td></tr>';

    echo '<tr><td align="right" style="white-space: nowrap" width=150>'.dica('Nome', 'Escreva o nome deste item.').'Nome:'.dicaF().'</td><td><input type="text" class="texto" name="instrumento_avulso_custo_nome" id="instrumento_avulso_custo_nome" value="" maxlength="50" style="width:300;" /></td></tr>';
    if ($exibir['instrumento_avulso_custo_tipo']) echo '<tr><td align="right" style="white-space: nowrap">'.$exibir['instrumento_avulso_custo_tipo_leg'].':</td><td>'.selecionaVetor($unidade, 'instrumento_avulso_custo_tipo', 'class=texto size=1 style="width:395px;"').'</td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_tipo" id="instrumento_avulso_custo_tipo" value="" />';

    echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quantidade', 'Insira a quantidade deste item.').'Quantidade:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="instrumento_avulso_custo_quantidade" id="instrumento_avulso_custo_quantidade" value="" maxlength="50" style="width:300;" /></td></tr>';
    echo '<tr><td align="right" style="white-space: nowrap">'.dica('Valor Unitário', 'Insira o valor unitário deste item.').'Valor unitário:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="instrumento_avulso_custo_custo" id="instrumento_avulso_custo_custo" value="" style="width:300;" /></td></tr>';

    if ($exibir['instrumento_avulso_custo_custo_atual']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Valor Unitário Atual', 'Insira o valor unitário atual deste item.').'Valor unitário atual:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="instrumento_avulso_custo_custo_atual" id="instrumento_avulso_custo_custo_atual" value="" style="width:300;" /></td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_custo_atual" id="instrumento_avulso_custo_custo_atual" value="" />';



    echo '<tr id="campo_instrumento_avulso_meses" style="display:none"><td align="right" style="white-space: nowrap">'.dica('Quantidade de Meses', 'Insira a quantidade de meses do serviço.').'Quantidade de meses:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="instrumento_avulso_custo_meses" id="instrumento_avulso_custo_meses" value="" style="width:300;" /></td></tr>';

    if ($exibir['instrumento_avulso_custo_moeda']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'Escolha a moeda utilizada neste item.').'Moeda:'.dicaF().'</td><td>'.selecionaVetor($moedas, 'instrumento_avulso_custo_moeda', 'class=texto size=1 style="width:395px;" onchange="mudar_moeda(this.value)"', 1).'</td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_moeda" id="instrumento_avulso_custo_moeda" value="1" />';

    if ($exibir['instrumento_avulso_custo_moeda']) echo '<tr id="combo_data_moeda"><td align="right">'.dica('Data da Cotação','Data da cotação da moeda.').'Data da cotação:</td><td><table cellpadding=0 cellspacing=0><tr><td><td><input type="hidden" name="instrumento_avulso_custo_data_moeda" id="instrumento_avulso_custo_data_moeda" value="'.($data_texto ? $data_texto->format('%Y-%m-%d') : '').'" /><input type="text" name="instrumento_avulso_custo_data_moeda_texto"  id="instrumento_avulso_custo_data_moeda_texto" style="width:70px;" onchange="setData(\'env\', \'instrumento_avulso_custo_data_moeda_texto\', \'instrumento_avulso_custo_data_moeda\');" value="'.($data_texto ? $data_texto->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data da Cotação', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data da cotação da moeda estrangeira.').'<a href="javascript: void(0);" ><img id="botao_instrumento_avulso_custo_data_moeda" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário2" border=0 /></a>'.dicaF().'</td></tr></table></td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_data_moeda" id="instrumento_avulso_custo_data_moeda" value="'.$data_texto->format('%Y-%m-%d').'" />';

    if ($exibir['instrumento_avulso_custo_bdi']) echo '<tr><td align="right" style="white-space: nowrap">'.$exibir['instrumento_avulso_custo_bdi_leg'].':</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="instrumento_avulso_custo_bdi" id="instrumento_avulso_custo_bdi" value="" style="width:300;" /></td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_bdi" id="instrumento_avulso_custo_bdi" value="0" />';


    if ($exibir['instrumento_avulso_custo_nd']) {
        $categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
        echo '<tr><td align="right" style="white-space: nowrap">'.$exibir['instrumento_avulso_custo_categoria_economica_leg'].':</td><td>'.selecionaVetor($categoria_economica, 'instrumento_avulso_custo_categoria_economica', 'class=texto size=1 style="width:395px;" onchange="env.instrumento_avulso_custo_nd.value=\'\'; mudar_nd();"').'</td></tr>';
        $GrupoND=array(''=>'')+getSisValor('GrupoND');
        echo '<tr><td align="right" style="white-space: nowrap">'.$exibir['instrumento_avulso_custo_grupo_despesa_leg'].':</td><td>'.selecionaVetor($GrupoND, 'instrumento_avulso_custo_grupo_despesa', 'class=texto size=1 style="width:395px;"  onchange="env.instrumento_avulso_custo_nd.value=\'\'; mudar_nd();"').'</td></tr>';
        $ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
        echo '<tr><td align="right" style="white-space: nowrap">'.$exibir['instrumento_avulso_custo_modalidade_aplicacao_leg'].':</td><td>'.selecionaVetor($ModalidadeAplicacao, 'instrumento_avulso_custo_modalidade_aplicacao', 'class=texto size=1 style="width:395px;"  onchange="env.instrumento_avulso_custo_nd.value=\'\'; mudar_nd();"').'</td></tr>';
        $nd=vetor_nd('', null, null, 3 ,'', '');
        echo '<tr><td align="right" style="white-space: nowrap">'.$exibir['instrumento_avulso_custo_nd_leg'].':</td><td><div id="combo_nd">'.selecionaVetor($nd, 'instrumento_avulso_custo_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"').'</div></td></tr>';
        }
    else {
        echo '<input type="hidden" name="instrumento_avulso_custo_categoria_economica" id="instrumento_avulso_custo_categoria_economica" value="" />';
        echo '<input type="hidden" name="instrumento_avulso_custo_grupo_despesa" id="instrumento_avulso_custo_grupo_despesa" value="" />';
        echo '<input type="hidden" name="instrumento_avulso_custo_modalidade_aplicacao" id="instrumento_avulso_custo_modalidade_aplicacao" value="" />';
        echo '<input type="hidden" name="instrumento_avulso_custo_nd" id="instrumento_avulso_custo_nd" value="" />';
        }

    echo '<input type="hidden" name="instrumento_avulso_custo_percentual" id="instrumento_avulso_custo_percentual" value="'.($exibir['instrumento_avulso_custo_percentual'] ? 1 : 0).'" />';

    if ($exibir['instrumento_avulso_custo_acrescimo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(($exibir['instrumento_avulso_custo_percentual'] ? 'Percentual' : 'Quantitativo').' de Acréscimo/Supressão', 'Insira o '.($exibir['instrumento_avulso_custo_percentual'] ? 'percentual' : 'quantitativo').' de acréscimo/supressão do item.').($exibir['instrumento_avulso_custo_percentual'] ? 'Perc.' : 'Quant.').' acréscimo/supressão:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="instrumento_avulso_custo_acrescimo" id="instrumento_avulso_custo_acrescimo" value="" style="width:300;" /></td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_acrescimo" id="instrumento_avulso_custo_acrescimo" value="" />';


    if ($exibir['instrumento_avulso_custo_acrescimo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Valor Total com Acréscimo', 'O valor total é o preço unitário multiplicado pela quantidade e pelo acréscimo.').'Total com acréscimo:'.dicaF().'</td><td><div id="total"></div></td></tr>';
    else echo '<input type="hidden" name="total" id="total" value="" />';

    echo '<tr><td align="right" style="white-space: nowrap">'.dica('Descrição', 'Insira a descrição deste item.').'Descrição:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" cols="70" rows="5" class="textarea" name="instrumento_avulso_custo_descricao" id="instrumento_avulso_custo_descricao"></textarea></td></tr>';

    if ($exibir['instrumento_avulso_custo_codigo']) echo '<tr><td align="right" style="white-space: nowrap">'.$exibir['instrumento_avulso_custo_codigo_leg'].':</td><td><input type="text" class="texto"  name="instrumento_avulso_custo_codigo" id="instrumento_avulso_custo_codigo" value="" maxlength="50" style="width:300;" /></td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_codigo" id="instrumento_avulso_custo_codigo" value="" />';

    if ($exibir['instrumento_avulso_custo_fonte']) echo '<tr><td align="right" style="white-space: nowrap">'.$exibir['instrumento_avulso_custo_fonte_leg'].':</td><td><input type="text" class="texto"  name="instrumento_avulso_custo_fonte" id="instrumento_avulso_custo_fonte" value="" maxlength="50" style="width:300;" /></td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_fonte" id="instrumento_avulso_custo_fonte" value="" />';

    if ($exibir['instrumento_avulso_custo_regiao']) echo '<tr><td align="right" style="white-space: nowrap">'.$exibir['instrumento_avulso_custo_regiao_leg'].':</td><td><input type="text" class="texto"  name="instrumento_avulso_custo_regiao" id="instrumento_avulso_custo_regiao" value="" maxlength="50" style="width:300;" /></td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_regiao" id="instrumento_avulso_custo_regiao" value="" />';


    if ($exibir['instrumento_avulso_custo_pi']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$exibir['instrumento_avulso_custo_pi_leg'].':</td><td><input type="text" class="texto" name="instrumento_avulso_custo_pi" id="instrumento_avulso_custo_pi" value="" maxlength="100" style="width:300;" /></td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_pi" id="instrumento_avulso_custo_pi" value="" />';

    if ($exibir['instrumento_avulso_custo_ptres']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$exibir['instrumento_avulso_custo_ptres_leg'].':</td><td><input type="text" class="texto" name="instrumento_avulso_custo_ptres" id="instrumento_avulso_custo_ptres" value="" maxlength="100" style="width:300;" /></td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_ptres" id="instrumento_avulso_custo_ptres" value="" />';

    if ($exibir['instrumento_avulso_custo_exercicio']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$exibir['instrumento_avulso_custo_exercicio_leg'].':</td><td><input type="text" class="texto" name="instrumento_avulso_custo_exercicio" id="instrumento_avulso_custo_exercicio" onkeypress="return entradaNumerica(event, this, true, true);" value="" maxlength="4" style="width:300;" /></td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_exercicio" id="instrumento_avulso_custo_exercicio" value="" />';



    if ($exibir['instrumento_avulso_custo_data_limite']) echo '<tr><td align="right">'.$exibir['instrumento_avulso_custo_data_limite_leg'].':</td><td><table cellpadding=0 cellspacing=0><tr><td><td><input type="hidden" name="instrumento_avulso_custo_data_limite" id="instrumento_avulso_custo_data_limite" value="'.($data_texto ? $data_texto->format('%Y%m%d') : '').'" /><input type="text" name="instrumento_avulso_custo_data_limite_texto"  id="instrumento_avulso_custo_data_limite_texto" style="width:70px;" onchange="setData(\'env\', \'instrumento_avulso_custo_data_limite_texto\', \'instrumento_avulso_custo_data_limite\');" value="'.($data_texto ? $data_texto->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Limite', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data limite para o recebimento do ítem.').'<a href="javascript: void(0);" ><img id="botao_instrumento_avulso_custo_data_limite" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr></table></td></tr>';
    else echo '<input type="hidden" name="instrumento_avulso_custo_data_limite" id="instrumento_avulso_custo_data_limite" value="'.$data_texto->format('%Y-%m-%d').'" />';


    echo '</table></fieldset></td>';
    echo '<td id="adicionar_custo" style="display:"><a href="javascript: void(0);" onclick="incluir_custo();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir um custo avulso.').'</a></td>';
    echo '<td id="confirmar_custo" style="display:none"><a href="javascript: void(0);" onclick="limpar_custo();">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do custo avulso.').'</a><a href="javascript: void(0);" onclick="incluir_custo();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do custo.').'</a></td>';
    echo '</tr></table></td></tr>';
	
	$sql->adTabela('instrumento_avulso_custo');
	$sql->esqUnir('instrumento_custo', 'instrumento_custo', 'instrumento_custo_avulso=instrumento_avulso_custo_id');
	$sql->adCampo('instrumento_avulso_custo.*, instrumento_custo_aprovado');
	$sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_custo_quantidade+instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((100+instrumento_avulso_custo_acrescimo)/100)) END AS valor');	
	$sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((instrumento_avulso_custo_acrescimo)/100)) END AS acrescimo');	
	$sql->adOnde('instrumento_custo_instrumento ='.(int)$instrumento_id);
	$sql->adOrdem('instrumento_custo_ordem');
	$linhas=$sql->Lista();
	$sql->limpar();
	$qnt=0;
	
	$total_geral=0;
	$total_acrescimo=0;
	
	echo '<tr><td align="right" style="white-space: nowrap" width=15>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Itens', 'Itens do serviço/entrega de materiais d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Itens:'.dicaF().'</td><td><div id="combo_custo">';
	if (is_array($linhas) && count($linhas)) {
		echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
		echo '<tr>';
		echo '<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>';
		echo '<th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>';
		if ($exibir['instrumento_avulso_custo_tipo']) echo '<th width=50>'.$exibir['instrumento_avulso_custo_tipo_leg'].'</th>';
		echo '<th>'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th>';
		echo '<th>'.dica('Quantidade de Meses', 'A quantidade de meses, para o caso de serviço.').'Qnt. Meses'.dicaF().'</th>';
		echo '<th>'.dica('Valor Unitário', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>';
		echo '<th>'.dica('Valor Unitário Atualizado', 'O valor de uma unidade do item atualizado.').'Unit. Atual'.dicaF().'</th>';
		if ($exibir['instrumento_avulso_custo_bdi']) echo '<th>'.$exibir['instrumento_avulso_custo_bdi_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_nd']) echo '<th width=50>'.$exibir['instrumento_avulso_custo_nd_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_acrescimo']) echo '<th width=50>'.($exibir['instrumento_avulso_custo_percentual'] ? $exibir['instrumento_avulso_custo_acrescimo_leg2'] : $exibir['instrumento_avulso_custo_acrescimo_leg']).'</th>';
		if ($exibir['instrumento_avulso_custo_acrescimo']) echo '<th>'.dica('Valor Total com Acréscimo', 'O valor total é o preço unitário multiplicado pela quantidade e pelo acréscimo.').'Total com Acréscimo'.dicaF().'</th>';
		else echo '<th>'.dica('Valor Total', 'O valor total é o preço unitário multiplicado pela quantidade.').'Total'.dicaF().'</th>';
		if ($exibir['instrumento_avulso_custo_codigo']) echo '<th>'.$exibir['instrumento_avulso_custo_codigo_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_fonte']) echo '<th>'.$exibir['instrumento_avulso_custo_fonte_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_regiao']) echo '<th>'.$exibir['instrumento_avulso_custo_regiao_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_usuario']) echo '<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>';
		if ($exibir['instrumento_avulso_custo_data_limite']) echo '<th width=50>'.$exibir['instrumento_avulso_custo_data_limite_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_pi']) echo '<th>'.$exibir['instrumento_avulso_custo_pi_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_ptres']) echo '<th>'.$exibir['instrumento_avulso_custo_ptres_leg'].'</th>';
		if ($exibir['instrumento_avulso_custo_exercicio']) echo '<th>'.$exibir['instrumento_avulso_custo_exercicio_leg'].'</th>';
		echo '<th></th></tr>';
		
		$total=array();
		$custo=array();
		
		foreach ($linhas as $linha) {
			echo '<tr align="center">';
			echo '<td align="left">'.++$qnt.' - '.$linha['instrumento_avulso_custo_nome'].'</td>';
			echo '<td align="left">'.($linha['instrumento_avulso_custo_descricao'] ? $linha['instrumento_avulso_custo_descricao'] : '&nbsp;').'</td>';
			if ($exibir['instrumento_avulso_custo_tipo']) echo '<td>'.$unidade[$linha['instrumento_avulso_custo_tipo']].'</td>';
			echo '<td style="white-space: nowrap">'.($linha['instrumento_avulso_custo_quantidade'] > 0  ? number_format($linha['instrumento_avulso_custo_quantidade'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') : '0').'</td>';
			echo '<td>'.($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses'] : ' - ').'</td>';
			echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['instrumento_avulso_custo_moeda']].' '.number_format($linha['instrumento_avulso_custo_custo'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
			echo '<td align="right" style="white-space: nowrap">'.($linha['instrumento_avulso_custo_custo_atual'] > 0 ? $moedas[$linha['instrumento_avulso_custo_moeda']].' '.number_format($linha['instrumento_avulso_custo_custo_atual'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') : '').'</td>';
			if ($exibir['instrumento_avulso_custo_bdi']) echo '<td align="right" style="white-space: nowrap">'.number_format($linha['instrumento_avulso_custo_bdi'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
			$nd=($linha['instrumento_avulso_custo_categoria_economica'] && $linha['instrumento_avulso_custo_grupo_despesa'] && $linha['instrumento_avulso_custo_modalidade_aplicacao'] ? $linha['instrumento_avulso_custo_categoria_economica'].'.'.$linha['instrumento_avulso_custo_grupo_despesa'].'.'.$linha['instrumento_avulso_custo_modalidade_aplicacao'].'.' : '').$linha['instrumento_avulso_custo_nd'];
			if ($exibir['instrumento_avulso_custo_nd']) echo '<td>'.$nd.'</td>';
			if ($exibir['instrumento_avulso_custo_acrescimo']) echo '<td align="right" style="white-space: nowrap">'.number_format($linha['instrumento_avulso_custo_acrescimo'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
			echo '<td align="right" style="white-space: nowrap">'.$moedas[$linha['instrumento_avulso_custo_moeda']].' '.number_format(($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']),($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
			if ($exibir['instrumento_avulso_custo_codigo']) echo '<td align="center">'.($linha['instrumento_avulso_custo_codigo'] ? $linha['instrumento_avulso_custo_codigo'] : '&nbsp;').'</td>';
			if ($exibir['instrumento_avulso_custo_fonte']) echo '<td align="center">'.($linha['instrumento_avulso_custo_fonte'] ? $linha['instrumento_avulso_custo_fonte'] : '&nbsp;').'</td>';
			if ($exibir['instrumento_avulso_custo_regiao']) echo '<td align="center">'.($linha['instrumento_avulso_custo_regiao'] ? $linha['instrumento_avulso_custo_regiao'] : '&nbsp;').'</td>'; 
			if ($exibir['instrumento_avulso_custo_usuario']) echo '<td align="left" style="white-space: nowrap">'.link_usuario($linha['instrumento_avulso_custo_usuario'],'','','esquerda').'</td>';
			if ($exibir['instrumento_avulso_custo_data_limite']) echo '<td>'.($linha['instrumento_avulso_custo_data_limite']? retorna_data($linha['instrumento_avulso_custo_data_limite'],false) : '&nbsp;').'</td>';
			if ($exibir['instrumento_avulso_custo_pi']) echo '<td align="center">'.$linha['instrumento_avulso_custo_pi'].'</td>';
			if ($exibir['instrumento_avulso_custo_ptres']) echo '<td align="center">'.$linha['instrumento_avulso_custo_ptres'].'</td>';
			if ($exibir['instrumento_avulso_custo_exercicio']) echo '<td align="center">'.$linha['instrumento_avulso_custo_exercicio'].'</td>';
	
			echo '<td width="72" align="left">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['instrumento_avulso_custo_ordem'].', '.$linha['instrumento_avulso_custo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['instrumento_avulso_custo_ordem'].', '.$linha['instrumento_avulso_custo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['instrumento_avulso_custo_ordem'].', '.$linha['instrumento_avulso_custo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['instrumento_avulso_custo_ordem'].', '.$linha['instrumento_avulso_custo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			if ($linha['instrumento_custo_aprovado']!=1 || !$config['aprova_custo']) {
				echo dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o item '.$linha['instrumento_avulso_custo_nome'].'.').'<a href="javascript:void(0);" onclick="javascript:editar_custo('.$linha['instrumento_avulso_custo_id'].');">'.imagem('icones/editar.gif').'</a>'.dicaF();
				echo dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o item '.$linha['instrumento_avulso_custo_nome'].'.').'<a href="javascript:void(0);" onclick="javascript:excluir_custo('.$linha['instrumento_avulso_custo_id'].');">'.imagem('icones/remover.png').'</a>'.dicaF();
				}
			echo '</td>';
			
			echo '</tr>';
			
			if (isset($custo[$linha['instrumento_avulso_custo_moeda']][$nd])) $custo[$linha['instrumento_avulso_custo_moeda']][$nd] += (float)($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
			else $custo[$linha['instrumento_avulso_custo_moeda']][$nd]=(float)($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
			if (isset($total[$linha['instrumento_avulso_custo_moeda']])) $total[$linha['instrumento_avulso_custo_moeda']]+=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
			else $total[$linha['instrumento_avulso_custo_moeda']]=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']); 
			$total_acrescimo+=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['acrescimo'] : $linha['acrescimo']);
			}
		
		$tem_total=false;
		foreach($total as $chave => $valor)	if ($valor) $tem_total=true;
		
		$total_colunas=6;
		if ($exibir['instrumento_avulso_custo_tipo']) $total_colunas++;
		if ($exibir['instrumento_avulso_custo_bdi']) $total_colunas++;
		if ($exibir['instrumento_avulso_custo_nd']) $total_colunas++;
		if ($exibir['instrumento_avulso_custo_acrescimo']) $total_colunas++;
			
		if ($tem_total) {
			foreach ($custo as $tipo_moeda => $linha) {
				echo '<tr><td colspan="'.$total_colunas.'" class="std" align="right">';
				if ($exibir['instrumento_avulso_custo_nd']) foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
				echo '<br><b>Total</td><td align="right" style="white-space: nowrap">';	
				if ($exibir['instrumento_avulso_custo_nd']) foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.$moedas[$tipo_moeda].' '.number_format($somatorio,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.');
				echo '<br><b>'.$moedas[$tipo_moeda].' '.number_format($total[$tipo_moeda],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
				}	
			}
		echo '</table>';
		}
	echo '</div></td></tr>';
	
	if ($exibir['instrumento_avulso_custo_acrescimo']) echo '<tr><td align=right width=100 style="white-space: nowrap; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Valor Total do Contrato com Acréscimo', 'Soma do valor do contrato ascrescentado da planilha de custo dos itens d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Total com acréscimo:'.dicaF().'</td><td style="vertical-align:top;"><div id="total_acrescimo">'.number_format($obj->instrumento_valor+$total_acrescimo,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</div></td></tr>';
	else echo '<tr style="display:none"><td></td><td><div id="total_acrescimo"></div></td></tr>';

	if ($exibir['instrumento_local_entrega']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Local de Prestação do Serviço/Entrega de Materiais', 'Preencha neste campo o local de prestação do serviço/entrega de materiais d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Local de entrega:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" rows="3" name="instrumento_local_entrega" id="instrumento_local_entrega">'.(isset($obj->instrumento_local_entrega) ? $obj->instrumento_local_entrega : '').'</textarea></td></tr>';
	else echo '<input type="hidden" name="instrumento_local_entrega" id="instrumento_local_entrega" value="" />';
	echo '</table></fieldset></td></tr>';
	}






$numeracao=0;	
if ($exibir['instrumento_detalhamento']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_detalhamento_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';

if ($exibir['instrumento_objeto']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_objeto_leg'].':</td><td><textarea data-gpweb-cmp="ckeditor" rows="3" name="instrumento_objeto" id="instrumento_objeto">'.(isset($obj->instrumento_objeto) ? $obj->instrumento_objeto : '').'</textarea></td></tr>';
else echo '<input type="hidden" name="instrumento_objeto" id="instrumento_objeto" value="'.$obj->instrumento_objeto.'" />';
if ($exibir['instrumento_justificativa']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_justificativa_leg'].':</td><td><textarea data-gpweb-cmp="ckeditor" rows="3" name="instrumento_justificativa" id="instrumento_justificativa">'.(isset($obj->instrumento_justificativa) ? $obj->instrumento_justificativa : '').'</textarea></td></tr>';
else echo '<input type="hidden" name="instrumento_justificativa" id="instrumento_justificativa" value="'.$obj->instrumento_justificativa.'" />';
if ($exibir['instrumento_resultado_esperado']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_resultado_esperado_leg'].':</td><td><textarea data-gpweb-cmp="ckeditor" rows="3" name="instrumento_resultado_esperado" id="instrumento_resultado_esperado">'.(isset($obj->instrumento_resultado_esperado) ? $obj->instrumento_resultado_esperado : '').'</textarea></td></tr>';
else echo '<input type="hidden" name="instrumento_resultado_esperado" id="instrumento_resultado_esperado" value="'.$obj->instrumento_resultado_esperado.'" />';
if ($exibir['instrumento_situacao_atual']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_situacao_atual_leg'].':</td><td><textarea data-gpweb-cmp="ckeditor" rows="3" name="instrumento_situacao_atual" id="instrumento_situacao_atual">'.(isset($obj->instrumento_situacao_atual) ? $obj->instrumento_situacao_atual : '').'</textarea></td></tr>';
else echo '<input type="hidden" name="instrumento_situacao_atual" id="instrumento_situacao_atual" value="'.$obj->instrumento_situacao_atual.'" />';
if ($exibir['instrumento_vantagem_economica']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_vantagem_economica_leg'].':</td><td><textarea data-gpweb-cmp="ckeditor" rows="3" name="instrumento_vantagem_economica" id="instrumento_vantagem_economica">'.(isset($obj->instrumento_vantagem_economica) ? $obj->instrumento_vantagem_economica : '').'</textarea></td></tr>';
else echo '<input type="hidden" name="instrumento_vantagem_economica" id="instrumento_vantagem_economica" value="'.$obj->instrumento_vantagem_economica.'" />';

if ($exibir['instrumento_detalhamento']) echo '</table></fieldset></td></tr>';


$numeracao=0;	
if ($exibir['instrumento_financeiro']){ 

	echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_financeiro_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';
	
	echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0>';
	echo '<tr><td><fieldset><legend class=texto style="color: black;"></legend><table cellspacing=1 cellpadding=0>';
	echo '<tr><td align=right width=150>'.$exibir['instrumento_financeiro_projeto_leg'].':</td><td><input type="text" id="instrumento_financeiro_projeto" name="instrumento_financeiro_projeto" value="" style="width:400px;" class="texto" /></td></tr>';
	if ($exibir['instrumento_financeiro_tarefa']) echo '<tr><td align=right width=150>'.$exibir['instrumento_financeiro_tarefa_leg'].':</td><td><input type="text" id="instrumento_financeiro_tarefa" name="instrumento_financeiro_tarefa" value="" style="width:400px;" class="texto" /></td></tr>';
	else echo '<input type="hidden" name="instrumento_financeiro_tarefa" id="instrumento_financeiro_tarefa" value="" />';
	$instrumentoFonte = getSisValor('instrumento_fonte');
	$trAno=array();
	for ($i=(int)date('Y')-5; $i < (int)date('Y')+30; $i++) $trAno[$i]=$i;
	//if ($exibir['instrumento_financeiro_fonte']) echo '<tr><td align=right width=150>'.$exibir['instrumento_financeiro_fonte_leg'].':</td><td>'.selecionaVetor($instrumentoFonte, 'instrumento_financeiro_fonte', 'class="texto" style="width:400px;"').'</td></tr>';
	//else echo '<input type="hidden" name="instrumento_financeiro_fonte" id="instrumento_financeiro_fonte" value="" />';
	
	if ($exibir['instrumento_financeiro_fonte']) echo '<tr><td align=right width=150>'.$exibir['instrumento_financeiro_fonte_leg'].':</td><td><input type="text" id="instrumento_financeiro_fonte" name="instrumento_financeiro_fonte" value="" style="width:400px;" class="texto" /></td></tr>';
	else echo '<input type="hidden" name="instrumento_financeiro_fonte" id="instrumento_financeiro_fonte" value="" />';

	
	if ($exibir['instrumento_financeiro_regiao']) echo '<tr><td align=right width=150>'.$exibir['instrumento_financeiro_regiao_leg'].':</td><td><input type="text" id="instrumento_financeiro_regiao" name="instrumento_financeiro_regiao" value="" style="width:400px;" class="texto" /></td></tr>';
	else echo '<input type="hidden" name="instrumento_financeiro_regiao" id="instrumento_financeiro_regiao" value="" />';
	if ($exibir['instrumento_financeiro_classificacao']) echo '<tr><td align=right width=150>'.$exibir['instrumento_financeiro_classificacao_leg'].':</td><td><input type="text" id="instrumento_financeiro_classificacao" name="instrumento_financeiro_classificacao" value="" style="width:400px;" class="texto" /></td></tr>';
	else echo '<input type="hidden" name="instrumento_financeiro_classificacao" id="instrumento_financeiro_classificacao" value="" />';
	echo '<tr><td align=right width=150>'.dica('Valor(R$)', 'Valor(R$) a ser incluído n'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'&nbsp;Valor(R$):'.dicaF().'</td><td><input type="text" id="instrumento_financeiro_valor" name="instrumento_financeiro_valor" value="" style="width:400px;" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" /></td></tr>';
	echo '<tr><td align=right width=150>'.dica('Ano', 'Ano a ser incluído n'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'&nbsp;Ano:'.dicaF().'</td><td>'.selecionaVetor($trAno, 'instrumento_financeiro_ano', 'class="texto" style="width:400px;"', date('Y')).'</td></tr>';
	echo '</table></fieldset></td>';
	echo '<td id="adicionar_financeiro" style="display:"><a href="javascript: void(0);" onclick="incluir_financeiro();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir.').'</a></td>';
	echo '<td id="confirmar_financeiro" style="display:none"><a href="javascript: void(0);" onclick="cancelar_financeiro();">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição.').'</a><a href="javascript: void(0);" onclick="incluir_financeiro();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição.').'</a></td>';
	echo '</tr>';
	echo '</table></td></tr>';
	
	
	echo '<input type="hidden" name="instrumento_financeiro_id" id="instrumento_financeiro_id" value="" />';
	
	if ($obj->instrumento_id) {
		$sql->adTabela('instrumento_financeiro');
		if ($uuid) $sql->adOnde('instrumento_financeiro_uuid = \''.$uuid.'\'');
		else $sql->adOnde('instrumento_financeiro_instrumento = '.(int)$instrumento_id);
		$sql->adCampo('instrumento_financeiro.*');
		$sql->adOrdem('instrumento_financeiro_ordem');
		$financeiros=$sql->ListaChave('instrumento_financeiro_id');
		$sql->limpar();
		}
	else $financeiros=null;
	
	echo '<tr><td></td><td align=left><div id="combo_financeiro">';
	if (is_array($financeiros) && count($financeiros)) {
		echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th>';
		echo '<th>'.$exibir['instrumento_financeiro_projeto_leg'].'</th>';
		if ($exibir['instrumento_financeiro_tarefa']) echo '<th>'.$exibir['instrumento_financeiro_tarefa_leg'].'</th>';
		if ($exibir['instrumento_financeiro_fonte']) echo '<th>'.$exibir['instrumento_financeiro_fonte_leg'].'</th>';
		if ($exibir['instrumento_financeiro_regiao']) echo '<th>'.$exibir['instrumento_financeiro_regiao_leg'].'</th>';
		if ($exibir['instrumento_financeiro_classificacao']) echo '<th>'.$exibir['instrumento_financeiro_classificacao_leg'].'</th>';
		echo '<th>'.dica('Valor(R$)', 'Valor a ser incluído n'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Valor(R$)'.dicaF().'</th>';
		echo '<th>'.dica('Ano', 'Ano a ser incluído n'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Ano'.dicaF().'</th>';
		
		echo '<th></th></tr>';
		foreach ($financeiros as $instrumento_financeiro_id => $financeiro) {
			echo '<tr>';
			echo '<td>';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_financeiro('.$financeiro['instrumento_financeiro_ordem'].', '.$financeiro['instrumento_financeiro_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_financeiro('.$financeiro['instrumento_financeiro_ordem'].', '.$financeiro['instrumento_financeiro_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_financeiro('.$financeiro['instrumento_financeiro_ordem'].', '.$financeiro['instrumento_financeiro_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_financeiro('.$financeiro['instrumento_financeiro_ordem'].', '.$financeiro['instrumento_financeiro_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();		
			echo '</td>';
			echo '<td align="left">'.$financeiro['instrumento_financeiro_projeto'].'</td>';
			if ($exibir['instrumento_financeiro_tarefa']) echo '<td align="left">'.$financeiro['instrumento_financeiro_tarefa'].'</td>';
			//if ($exibir['instrumento_financeiro_fonte']) echo '<td align="left">'.(isset($instrumentoFonte[$financeiro['instrumento_financeiro_fonte']]) ? $instrumentoFonte[$financeiro['instrumento_financeiro_fonte']] : '').'</td>';
			if ($exibir['instrumento_financeiro_fonte']) echo '<td align="left">'.$financeiro['instrumento_financeiro_fonte'].'</td>';
			
			if ($exibir['instrumento_financeiro_regiao']) echo '<td align="left">'.$financeiro['instrumento_financeiro_regiao'].'</td>';
			if ($exibir['instrumento_financeiro_classificacao']) echo '<td align="left">'.$financeiro['instrumento_financeiro_classificacao'].'</td>';
			echo '<td align="right">'.number_format($financeiro['instrumento_financeiro_valor'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
			echo '<td align="left">'.$financeiro['instrumento_financeiro_ano'].'</td>';
			echo '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_financeiro('.$financeiro['instrumento_financeiro_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar.').'</a>';
			echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_financeiro('.$financeiro['instrumento_financeiro_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
			echo '</tr>';
			}
		echo '</table>';
		}
	
	echo '</div></td></tr>';
	
	
	echo '</table></fieldset></td></tr>';
	}


$numeracao=0;	
if ($exibir['instrumento_datas']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_datas_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';

if ($exibir['instrumento_data_celebracao']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_data_celebracao_leg'].':</td><td><input type="hidden" name="instrumento_data_celebracao" id="instrumento_data_celebracao" value="'.(isset($obj->instrumento_data_celebracao) ? $obj->instrumento_data_celebracao : '').'" /><input type="text" name="instrumento_data_celebracao_texto" style="width:70px;" id="instrumento_data_celebracao_texto" onchange="setData(\'env\', \'instrumento_data_celebracao_texto\' , \'instrumento_data_celebracao\');" value="'.(isset($obj->instrumento_data_celebracao) ? retorna_data($obj->instrumento_data_celebracao, false) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data.').'<a href="javascript: void(0);" ><img id="botao_instrumento_data_celebracao" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
else echo '<input type="hidden" name="instrumento_data_celebracao" id="instrumento_data_celebracao" value="'.$obj->instrumento_data_celebracao.'" />';
if ($exibir['instrumento_data_inicio']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_data_inicio_leg'].':</td><td><input type="hidden" name="instrumento_data_inicio" id="instrumento_data_inicio" value="'.(isset($obj->instrumento_data_inicio) ? $obj->instrumento_data_inicio : '').'" /><input type="text" name="instrumento_data_inicio_texto" style="width:70px;" id="instrumento_data_inicio_texto" onchange="setData(\'env\', \'instrumento_data_inicio_texto\' , \'instrumento_data_inicio\');" value="'.(isset($obj->instrumento_data_inicio) ? retorna_data($obj->instrumento_data_inicio, false) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data.').'<a href="javascript: void(0);" ><img id="botao_instrumento_data_inicio" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
else echo '<input type="hidden" name="instrumento_data_inicio" id="instrumento_data_inicio" value="'.$obj->instrumento_data_inicio.'" />';
if ($exibir['instrumento_data_termino']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_data_termino_leg'].':</td><td><input type="hidden" name="instrumento_data_termino" id="instrumento_data_termino" value="'.(isset($obj->instrumento_data_termino) ? $obj->instrumento_data_termino : '').'" /><input type="text" name="instrumento_data_termino_texto" style="width:70px;" id="instrumento_data_termino_texto" onchange="setData(\'env\', \'instrumento_data_termino_texto\' , \'instrumento_data_termino\');" value="'.(isset($obj->instrumento_data_termino) ? retorna_data($obj->instrumento_data_termino, false) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data.').'<a href="javascript: void(0);" ><img id="botao_instrumento_data_termino" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
else echo '<input type="hidden" name="instrumento_data_termino" id="instrumento_data_termino" value="'.$obj->instrumento_data_termino.'" />';
if ($exibir['instrumento_data_publicacao']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_data_publicacao_leg'].':</td><td><input type="hidden" name="instrumento_data_publicacao" id="instrumento_data_publicacao" value="'.(isset($obj->instrumento_data_publicacao) ? $obj->instrumento_data_publicacao : '').'" /><input type="text" name="instrumento_data_publicacao_texto" style="width:70px;" id="instrumento_data_publicacao_texto" onchange="setData(\'env\', \'instrumento_data_publicacao_texto\' , \'instrumento_data_publicacao\');" value="'.(isset($obj->instrumento_data_publicacao) ? retorna_data($obj->instrumento_data_publicacao, false) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data.').'<a href="javascript: void(0);" ><img id="botao_instrumento_data_publicacao" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
else echo '<input type="hidden" name="instrumento_data_publicacao" id="instrumento_data_publicacao" value="'.$obj->instrumento_data_publicacao.'" />';

if ($exibir['instrumento_datas']) echo '</table></fieldset></td></tr>';










$numeracao=0;	
if ($exibir['instrumento_garantia_contratual']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_garantia_contratual_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';

if ($exibir['instrumento_garantia_contratual_modalidade']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. Modalidade escolhida:</td><td><input type="text" class="texto" name="instrumento_garantia_contratual_modalidade" id="instrumento_garantia_contratual_modalidade" value="'.$obj->instrumento_garantia_contratual_modalidade.'" size="50" /></td></tr>';
else echo '<input type="hidden" name="instrumento_garantia_contratual_modalidade" id="instrumento_garantia_contratual_modalidade" value="'.$obj->instrumento_garantia_contratual_modalidade.'" />';

if ($exibir['instrumento_garantia_contratual_percentual']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. Percentual(%):</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="instrumento_garantia_contratual_percentual" id="instrumento_garantia_contratual_percentual" value="'.($obj->instrumento_garantia_contratual_percentual ? number_format($obj->instrumento_garantia_contratual_percentual,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.'):'').'" size="10" />%</td></tr>';
else echo '<input type="hidden" name="instrumento_garantia_contratual_percentual" id="instrumento_garantia_contratual_percentual" value="'.($obj->instrumento_garantia_contratual_percentual ? number_format($obj->instrumento_garantia_contratual_percentual,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.'):'').'" />';

if ($exibir['instrumento_garantia_contratual_vencimento']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. Vencimento:</td><td><input type="hidden" name="instrumento_garantia_contratual_vencimento" id="instrumento_garantia_contratual_vencimento" value="'.(isset($obj->instrumento_garantia_contratual_vencimento) ? $obj->instrumento_garantia_contratual_vencimento : '').'" /><input type="text" name="instrumento_garantia_contratual_vencimento_texto" style="width:70px;" id="instrumento_garantia_contratual_vencimento_texto" onchange="setData(\'env\', \'instrumento_garantia_contratual_vencimento_texto\' , \'instrumento_garantia_contratual_vencimento\');" value="'.(isset($obj->instrumento_garantia_contratual_vencimento) ? retorna_data($obj->instrumento_garantia_contratual_vencimento, false) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data.').'<a href="javascript: void(0);" ><img id="botao_instrumento_garantia_contratual_vencimento" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
else echo '<input type="hidden" name="instrumento_garantia_contratual_vencimento" id="instrumento_garantia_contratual_vencimento" value="'.$obj->instrumento_garantia_contratual_vencimento.'" />';

if ($exibir['instrumento_garantia_contratual']) echo '</table></fieldset></td></tr>';













$numeracao=0;	
if ($exibir['instrumento_protocolo']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_protocolo_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';

if ($exibir['instrumento_licitacao']){
    echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_licitacao_leg'].':</td><td align="left" >'.selecionaVetor(getSisValor('ModalidadeLicitacao','','','',true,0), 'instrumento_licitacao', 'class="texto" style="width:400px"', (isset($obj->instrumento_licitacao) ? $obj->instrumento_licitacao : '')).'</td></tr>';
    }
else echo '<input type="hidden" name="instrumento_licitacao" id="instrumento_licitacao" value="'.$obj->instrumento_licitacao.'" />';



if ($exibir['instrumento_edital_nr']) {
	echo '<input type=hidden name="instrumento_edital_id" id="instrumento_edital_id" value="">';
	
	echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_edital_nr_leg'].':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" id="instrumento_edital_edital" name="instrumento_edital_edital" value="" style="width:200px;" class="texto" maxlength=255 /></td>';
	echo '<td id="adicionar_edital" style="display:"><a href="javascript: void(0);" onclick="incluir_edital();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir.').'</a></td>';
	echo '<td id="confirmar_edital" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'instrumento_edital_id\').value=0;document.getElementById(\'instrumento_edital_edital\').value=\'\';	document.getElementById(\'adicionar_edital\').style.display=\'\';	document.getElementById(\'confirmar_edital\').style.display=\'none\';">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição.').'</a><a href="javascript: void(0);" onclick="incluir_edital();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição.').'</a></td>';
	echo '</tr></table></td></tr>';
	if ($instrumento_id) {
		$sql->adTabela('instrumento_edital');
		$sql->adOnde('instrumento_edital_instrumento = '.(int)$instrumento_id);
		$sql->adCampo('instrumento_edital_id, instrumento_edital_edital, instrumento_edital_ordem');
		$sql->adOrdem('instrumento_edital_ordem');
		$editals=$sql->ListaChave('instrumento_edital_id');
		$sql->limpar();
		}
	else $editals=null;
	echo '<tr><td></td><td colspan=20 align=left><div id="combo_edital">';
	if (is_array($editals) && count($editals)) {
		echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>'.$exibir['instrumento_edital_nr_leg'].'</th><th></th></tr>';
		foreach ($editals as $edital_id => $edital) {
			echo '<tr align="center">';
			echo '<td style="white-space: nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_edital('.$edital['instrumento_edital_ordem'].', '.$edital['instrumento_edital_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'"/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_edital('.$edital['instrumento_edital_ordem'].', '.$edital['instrumento_edital_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'"/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_edital('.$edital['instrumento_edital_ordem'].', '.$edital['instrumento_edital_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'"/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_edital('.$edital['instrumento_edital_ordem'].', '.$edital['instrumento_edital_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'"/></a>'.dicaF();
			echo '</td>';
			echo '<td align="left">'.$edital['instrumento_edital_edital'].'</td>';
			echo '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_edital('.$edital['instrumento_edital_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar.').'</a>';
			echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esto edital?\')) {excluir_edital('.$edital['instrumento_edital_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
			echo '</tr>';
			}
		echo '</table>';
		}
	echo '</div></td></tr>';
	}


else echo '<input type="hidden" name="instrumento_edital_nr" id="instrumento_edital_nr" value="'.$obj->instrumento_edital_nr.'" />';






if ($exibir['instrumento_processo']) {
	echo '<input type=hidden name="instrumento_processo_id" id="instrumento_processo_id" value="">';

	echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_processo_leg'].':</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" id="instrumento_processo_processo" name="instrumento_processo_processo" value="" style="width:200px;" class="texto" maxlength=255 /></td>';
	echo '<td id="adicionar_processo" style="display:"><a href="javascript: void(0);" onclick="incluir_processo();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir.').'</a></td>';
	echo '<td id="confirmar_processo" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'instrumento_processo_id\').value=0;document.getElementById(\'instrumento_processo_processo\').value=\'\';	document.getElementById(\'adicionar_processo\').style.display=\'\';	document.getElementById(\'confirmar_processo\').style.display=\'none\';">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição.').'</a><a href="javascript: void(0);" onclick="incluir_processo();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição.').'</a></td>';
	echo '</tr></table></td></tr>';
	if ($instrumento_id) {
		$sql->adTabela('instrumento_processo');
		$sql->adOnde('instrumento_processo_instrumento = '.(int)$instrumento_id);
		$sql->adCampo('instrumento_processo_id, instrumento_processo_processo, instrumento_processo_ordem');
		$sql->adOrdem('instrumento_processo_ordem');
		$processos=$sql->ListaChave('instrumento_processo_id');
		$sql->limpar();
		}
	else $processos=null;
	echo '<tr><td></td><td colspan=20 align=left><div id="combo_processo">';
	if (is_array($processos) && count($processos)) {
		echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>'.$exibir['instrumento_processo_leg'].'</th><th></th></tr>';
		foreach ($processos as $processo_id => $processo) {
			echo '<tr align="center">';
			echo '<td style="white-space: nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_processo('.$processo['instrumento_processo_ordem'].', '.$processo['instrumento_processo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'"/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_processo('.$processo['instrumento_processo_ordem'].', '.$processo['instrumento_processo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'"/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_processo('.$processo['instrumento_processo_ordem'].', '.$processo['instrumento_processo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'"/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_processo('.$processo['instrumento_processo_ordem'].', '.$processo['instrumento_processo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'"/></a>'.dicaF();
			echo '</td>';
			echo '<td align="left">'.$processo['instrumento_processo_processo'].'</td>';
			echo '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_processo('.$processo['instrumento_processo_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar.').'</a>';
			echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esto processo?\')) {excluir_processo('.$processo['instrumento_processo_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
			echo '</tr>';
			}
		echo '</table>';
		}
	echo '</div></td></tr>';
	}


else echo '<input type="hidden" name="instrumento_processo" id="instrumento_processo" value="'.$obj->instrumento_processo.'" />';






if ($exibir['instrumento_protocolo']) echo '</table></fieldset></td></tr>';


$numeracao=0;	
if ($exibir['instrumento_dados']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.++$numeracao_titulo.'. '.$exibir['instrumento_dados_leg'].'</legend><table cellspacing=1 cellpadding=0 width=100%>';

if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_instrumento = '.(int)$instrumento_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	if (count($indicadores)>1) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Indicador Principal', 'Escolha dentre os indicadores relacionados mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td colspan="2">'.selecionaVetor($indicadores, 'instrumento_principal_indicador', 'class="texto" style="width:400px;"', $obj->instrumento_principal_indicador).'</td></tr>';
	}
echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Cor', 'Cor selecionada dentre as 16 milhões possíveis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inserção do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="instrumento_cor" value="'.($obj->instrumento_cor ? $obj->instrumento_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Nível de Acesso', ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o responsável e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante I</b> - Somente o responsável e os designados podem ver e editar</li><li><b>Participantes II</b> - Somente o responsável e os designados podem ver e apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o responsável  e os designados podem ver, e o responsável editar.</li></ul>').'Nível de Acesso'.dicaF().'</td><td>'.selecionaVetor($niveis_acesso, 'instrumento_acesso', 'class="texto"', ($instrumento_id ? $obj->instrumento_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
for($i=0; $i<=100; $i++) $percentual[$i]=$i;
if ($exibir['instrumento_porcentagem']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_porcentagem_leg'].':</td><td>'.selecionaVetor($percentual, 'instrumento_porcentagem', 'size="1" class="texto"', (isset($obj->instrumento_porcentagem) ? (int)$obj->instrumento_porcentagem : '')).'%'.'</td></tr>';
else echo '<input type="hidden" name="instrumento_porcentagem" id="instrumento_porcentagem" value="'.$obj->instrumento_porcentagem.'" />';

if ($exibir['instrumento_contatos']) {
	$saida_contatos='';
	if (count($contatos_selecionados)) {
			$saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_contatos.= '<tr><td>'.link_contato($contatos_selecionados[0],'','','esquerda');
			$qnt_lista_contatos=count($contatos_selecionados);
			if ($qnt_lista_contatos > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';
					$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.$config['contatos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
					}
			$saida_contatos.= '</td></tr></table>';
			}
	else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:400px;"><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Contatos', 'Quais '.strtolower($config['contatos']).' estão envolvid'.$config['genero_contato'].'s.').ucfirst($config['contatos']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_contatos">'.$saida_contatos.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['contatos'].'.','popContatos()').'</td></tr></table></td></tr>';
	}



if ($exibir['instrumento_recursos']) {
	$saida_recursos='';
	if (count($recursos_selecionados)) {
			$saida_recursos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_recursos.= '<tr><td>'.link_recurso($recursos_selecionados[0]);
			$qnt_lista_recursos=count($recursos_selecionados);
			if ($qnt_lista_recursos > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_recursos; $i < $i_cmp; $i++) $lista.=link_recurso($recursos_selecionados[$i]).'<br>';
					$saida_recursos.= dica('Outr'.$config['genero_recurso'].'s '.ucfirst($config['recursos']), 'Clique para visualizar '.$config['genero_recurso'].'s demais '.$config['recursos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_recursos\');">(+'.($qnt_lista_recursos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_recursos"><br>'.$lista.'</span>';
					}
			$saida_recursos.= '</td></tr></table>';
			}
	else $saida_recursos.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:400px;"><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Recursos', 'Quais '.strtolower($config['recursos']).' estão envolvid'.$config['genero_recurso'].'s.').ucfirst($config['recursos']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_recursos">'.$saida_recursos.'</div></td><td>'.botao_icone('recursos_p.gif','Selecionar', 'selecionar '.$config['recursos'].'.','popRecursos()').'</td></tr></table></td></tr>';
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
if ($Aplic->checarModulo('praticas', 'editar', null, 'avaliacao_indicador')) $tipos['avaliacao']='Avaliação';
if ($Aplic->checarModulo('praticas', 'editar', null, 'brainstorm')) $tipos['brainstorm']='Brainstorm';
if ($Aplic->checarModulo('praticas', 'editar', null, 'gut')) $tipos['gut']='Matriz GUT';
if ($Aplic->checarModulo('praticas', 'editar', null, 'causa_efeito')) $tipos['causa_efeito']='Diagrama de causa-efeito';
if ($Aplic->checarModulo('arquivos', 'editar', null,  null)) $tipos['arquivo']='Arquivo';
if ($Aplic->checarModulo('foruns', 'editar', null, null)) $tipos['forum']='Fórum';
if ($Aplic->checarModulo('praticas', 'editar', null, 'checklist')) $tipos['checklist']='Checklist';
if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'editar', null, null)) $tipos['patrocinador']=ucfirst($config['patrocinador']);
if ($Aplic->checarModulo('praticas', 'editar', null, 'plano_acao_item')) $tipos['acao_item']='Item de '.ucfirst($config['acao']);
if ($Aplic->checarModulo('projetos', 'editar', null, 'viabilidade')) $tipos['projeto_viabilidade']='Estudo de viabilidade';
if ($Aplic->checarModulo('projetos', 'editar', null, 'abertura')) $tipos['projeto_abertura']='Termo de abertura';
if ($Aplic->checarModulo('praticas', 'editar', null, 'planejamento')) $tipos['plano_gestao']='Planejamento estratégico';
if ($Aplic->profissional) {
	$tipos['agenda']='Compromisso';
	if ($Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'editar', null, null)) $tipos['operativo']='Plano operativo';
	if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'editar', null, null)) $tipos['ata']='Ata de reunião';	
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
	if ($Aplic->checarModulo('praticas', 'editar', null, 'odometro_indicador')) $tipos['painel_odometro']='Odômetro de indicador';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'composicao_painel')) $tipos['painel_composicao']='Composição de painéis';
	if ($Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'editar', null, null)) $tipos['tr']=ucfirst($config['tr']);
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'editar', null, 'me')) $tipos['me']=ucfirst($config['me']);
	if ($Aplic->checarModulo('projetos', 'editar', null, 'beneficio')) $tipos['beneficio']=ucfirst($config['beneficio']).' de '.$config['programa'];
	if ($Aplic->checarModulo('projetos', 'editar', null, 'slideshow_painel')) $tipos['painel_slideshow']='Slideshow de composições';
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


if ($instrumento_tarefa) $tipo='tarefa';
elseif ($instrumento_projeto) $tipo='projeto';
elseif ($instrumento_perspectiva) $tipo='perspectiva';
elseif ($instrumento_tema) $tipo='tema';
elseif ($instrumento_objetivo) $tipo='objetivo';
elseif ($instrumento_fator) $tipo='fator';
elseif ($instrumento_estrategia) $tipo='estrategia';
elseif ($instrumento_meta) $tipo='meta';
elseif ($instrumento_pratica) $tipo='pratica';
elseif ($instrumento_acao) $tipo='acao';
elseif ($instrumento_canvas) $tipo='canvas';
elseif ($instrumento_risco) $tipo='risco';
elseif ($instrumento_risco_resposta) $tipo='risco_resposta';
elseif ($instrumento_indicador) $tipo='instrumento_indicador';
elseif ($instrumento_calendario) $tipo='calendario';
elseif ($instrumento_monitoramento) $tipo='monitoramento';
elseif ($instrumento_ata) $tipo='ata';
elseif ($instrumento_mswot) $tipo='mswot';
elseif ($instrumento_swot) $tipo='swot';
elseif ($instrumento_operativo) $tipo='operativo';
elseif ($instrumento_instrumento) $tipo='instrumento';
elseif ($instrumento_recurso) $tipo='recurso';
elseif ($instrumento_problema) $tipo='problema';
elseif ($instrumento_demanda) $tipo='demanda';
elseif ($instrumento_programa) $tipo='programa';
elseif ($instrumento_licao) $tipo='licao';
elseif ($instrumento_evento) $tipo='evento';
elseif ($instrumento_link) $tipo='link';
elseif ($instrumento_avaliacao) $tipo='avaliacao';
elseif ($instrumento_tgn) $tipo='tgn';
elseif ($instrumento_brainstorm) $tipo='brainstorm';
elseif ($instrumento_gut) $tipo='gut';
elseif ($instrumento_causa_efeito) $tipo='causa_efeito';
elseif ($instrumento_arquivo) $tipo='arquivo';
elseif ($instrumento_forum) $tipo='forum';
elseif ($instrumento_checklist) $tipo='checklist';
elseif ($instrumento_agenda) $tipo='agenda';
elseif ($instrumento_agrupamento) $tipo='agrupamento';
elseif ($instrumento_patrocinador) $tipo='patrocinador';
elseif ($instrumento_template) $tipo='template';
elseif ($instrumento_painel) $tipo='painel';
elseif ($instrumento_painel_odometro) $tipo='painel_odometro';
elseif ($instrumento_painel_composicao) $tipo='painel_composicao';
elseif ($instrumento_tr) $tipo='tr';
elseif ($instrumento_me) $tipo='me';
elseif ($instrumento_acao_item) $tipo='acao_item';
elseif ($instrumento_beneficio) $tipo='beneficio';
elseif ($instrumento_painel_slideshow) $tipo='painel_slideshow';
elseif ($instrumento_projeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($instrumento_projeto_abertura) $tipo='projeto_abertura';
elseif ($instrumento_plano_gestao) $tipo='plano_gestao';
elseif ($instrumento_ssti) $tipo='ssti';
elseif ($instrumento_laudo) $tipo='laudo';
elseif ($instrumento_trelo) $tipo='trelo';
elseif ($instrumento_trelo_cartao) $tipo='trelo_cartao';
elseif ($instrumento_pdcl) $tipo='pdcl';
elseif ($instrumento_pdcl_item) $tipo='pdcl_item';	
elseif ($instrumento_os) $tipo='os';	
else $tipo='';
echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Relacionad'.$config['genero_instrumento'], 'A que área '.$config['genero_instrumento'].' '.$config['instrumento'].' está relacionad'.$config['genero_instrumento'].'.').'Relacionad'.$config['genero_instrumento'].':'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';

echo '<tr '.($instrumento_projeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_projeto" value="'.$instrumento_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($instrumento_projeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_tarefa" value="'.$instrumento_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($instrumento_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso seja específico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_perspectiva" value="'.$instrumento_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($instrumento_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso seja específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_tema" value="'.$instrumento_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($instrumento_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso seja específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_objetivo" value="'.$instrumento_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($instrumento_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso seja específico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_fator" value="'.$instrumento_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($instrumento_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso seja específico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_estrategia" value="'.$instrumento_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($instrumento_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso seja específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_meta" value="'.$instrumento_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($instrumento_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_pratica" value="'.$instrumento_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($instrumento_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso seja específico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_acao" value="'.$instrumento_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($instrumento_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso seja específico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_canvas" value="'.$instrumento_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($instrumento_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso seja específico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_risco" value="'.$instrumento_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($instrumento_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso seja específico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_risco_resposta" value="'.$instrumento_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($instrumento_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso seja específico de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_indicador" value="'.$instrumento_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($instrumento_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso seja específico de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_calendario" value="'.$instrumento_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($instrumento_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste ícone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso seja específico de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_monitoramento" value="'.$instrumento_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($instrumento_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reunião', 'Caso seja específico de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_ata" value="'.(isset($instrumento_ata) ? $instrumento_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($instrumento_ata) ? $instrumento_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('icones/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso seja específico de uma matriz SWOT neste campo deverá constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_mswot" value="'.(isset($instrumento_mswot) ? $instrumento_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($instrumento_mswot) ? $instrumento_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste ícone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso seja específico de um campo de matriz SWOT neste campo deverá constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_swot" value="'.(isset($instrumento_swot) ? $instrumento_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($instrumento_swot) ? $instrumento_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso seja específico de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_operativo" value="'.$instrumento_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($instrumento_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso seja específico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_instrumento" value="'.$instrumento_instrumento.'" /><input type="text" id="gestao_instrumento_nome" name="gestao_instrumento_nome" value="'.nome_instrumento($instrumento_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso seja específico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_recurso" value="'.$instrumento_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($instrumento_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['recurso']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso seja específico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_problema" value="'.$instrumento_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($instrumento_problema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso seja específico de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_demanda" value="'.$instrumento_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($instrumento_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_programa" value="'.$instrumento_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($instrumento_programa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso seja específico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo deverá constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_licao" value="'.$instrumento_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($instrumento_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso seja específico de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_evento" value="'.$instrumento_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($instrumento_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso seja específico de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_link" value="'.$instrumento_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($instrumento_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avaliação', 'Caso seja específico de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_avaliacao" value="'.$instrumento_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($instrumento_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso seja específico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_tgn" value="'.$instrumento_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($instrumento_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso seja específico de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_brainstorm" value="'.$instrumento_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($instrumento_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz GUT', 'Caso seja específico de uma matriz GUT, neste campo deverá constar o nome da matriz GUT.').'Matriz GUT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_gut" value="'.$instrumento_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($instrumento_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz GUT','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso seja específico de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_causa_efeito" value="'.$instrumento_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($instrumento_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso seja específico de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_arquivo" value="'.$instrumento_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($instrumento_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('Fórum', 'Caso seja específico de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_forum" value="'.$instrumento_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($instrumento_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso seja específico de um checklist, neste campo deverá constar o nome do checklist.').'Checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_checklist" value="'.$instrumento_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($instrumento_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso seja específico de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_agenda" value="'.$instrumento_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($instrumento_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso seja específico de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_agrupamento" value="'.$instrumento_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($instrumento_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso seja específico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo deverá constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_patrocinador" value="'.$instrumento_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($instrumento_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('icones/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste ícone '.imagem('icones/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso seja específico de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_template" value="'.$instrumento_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($instrumento_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso seja específico de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_painel" value="'.$instrumento_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($instrumento_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Odômetro de Indicador', 'Caso seja específico de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_painel_odometro" value="'.$instrumento_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($instrumento_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composição de Painéis', 'Caso seja específico de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_painel_composicao" value="'.$instrumento_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($instrumento_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/composicao_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja específico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_tr" value="'.$instrumento_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($instrumento_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja específico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_me" value="'.$instrumento_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($instrumento_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso seja específico de um item de '.$config['acao'].', neste campo deverá constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_acao_item" value="'.$instrumento_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($instrumento_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste ícone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso seja específico de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_beneficio" value="'.$instrumento_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($instrumento_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composições', 'Caso seja específico de um slideshow de composições, neste campo deverá constar o nome do slideshow de composições.').'Slideshow de composições:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_painel_slideshow" value="'.$instrumento_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($instrumento_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composições','Clique neste ícone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composições.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_projeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso seja específico de um estudo de viabilidade, neste campo deverá constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_projeto_viabilidade" value="'.$instrumento_projeto_viabilidade.'" /><input type="text" id="projeto_viabilidade_nome" name="projeto_viabilidade_nome" value="'.nome_viabilidade($instrumento_projeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste ícone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_projeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso seja específico de um termo de abertura, neste campo deverá constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_projeto_abertura" value="'.$instrumento_projeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($instrumento_projeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste ícone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estratégico', 'Caso seja específico de um planejamento estratégico, neste campo deverá constar o nome do planejamento estratégico.').'Planejamento estratégico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_plano_gestao" value="'.$instrumento_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($instrumento_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estratégico','Clique neste ícone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estratégico.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_ssti ? '' : 'style="display:none"').' id="ssti" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['ssti']), 'Caso seja específico de '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].', neste campo deverá constar o nome d'.$config['genero_ssti'].' '.$config['ssti'].'.').ucfirst($config['ssti']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_ssti" value="'.$instrumento_ssti.'" /><input type="text" id="ssti_nome" name="ssti_nome" value="'.nome_ssti($instrumento_ssti).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSSTI();">'.imagem('icones/ssti_p.png','Selecionar '.ucfirst($config['ssti']),'Clique neste ícone '.imagem('icones/ssti_p.png').' para selecionar '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_laudo ? '' : 'style="display:none"').' id="laudo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['laudo']), 'Caso seja específico de '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].', neste campo deverá constar o nome d'.$config['genero_laudo'].' '.$config['laudo'].'.').ucfirst($config['laudo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_laudo" value="'.$instrumento_laudo.'" /><input type="text" id="laudo_nome" name="laudo_nome" value="'.nome_laudo($instrumento_laudo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLaudo();">'.imagem('icones/laudo_p.png','Selecionar '.ucfirst($config['laudo']),'Clique neste ícone '.imagem('icones/laudo_p.png').' para selecionar '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_trelo ? '' : 'style="display:none"').' id="trelo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo']), 'Caso seja específico de '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].', neste campo deverá constar o nome d'.$config['genero_trelo'].' '.$config['trelo'].'.').ucfirst($config['trelo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_trelo" value="'.$instrumento_trelo.'" /><input type="text" id="trelo_nome" name="trelo_nome" value="'.nome_trelo($instrumento_trelo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTrelo();">'.imagem('icones/trelo_p.png','Selecionar '.ucfirst($config['trelo']),'Clique neste ícone '.imagem('icones/trelo_p.png').' para selecionar '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_trelo_cartao ? '' : 'style="display:none"').' id="trelo_cartao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo_cartao']), 'Caso seja específico de '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].', neste campo deverá constar o nome d'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_trelo_cartao" value="'.$instrumento_trelo_cartao.'" /><input type="text" id="trelo_cartao_nome" name="trelo_cartao_nome" value="'.nome_trelo_cartao($instrumento_trelo_cartao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTreloCartao();">'.imagem('icones/trelo_cartao_p.png','Selecionar '.ucfirst($config['trelo_cartao']),'Clique neste ícone '.imagem('icones/trelo_cartao_p.png').' para selecionar '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_pdcl ? '' : 'style="display:none"').' id="pdcl" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl']), 'Caso seja específico de '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].', neste campo deverá constar o nome d'.$config['genero_pdcl'].' '.$config['pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_pdcl" value="'.$instrumento_pdcl.'" /><input type="text" id="pdcl_nome" name="pdcl_nome" value="'.nome_pdcl($instrumento_pdcl).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCL();">'.imagem('icones/pdcl_p.png','Selecionar '.ucfirst($config['pdcl']),'Clique neste ícone '.imagem('icones/pdcl_p.png').' para selecionar '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_pdcl_item ? '' : 'style="display:none"').' id="pdcl_item" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl_item']), 'Caso seja específico de '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].', neste campo deverá constar o nome d'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_pdcl_item" value="'.$instrumento_pdcl_item.'" /><input type="text" id="pdcl_item_nome" name="pdcl_item_nome" value="'.nome_pdcl_item($instrumento_pdcl_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="pop_pdcl_item();">'.imagem('icones/pdcl_item_p.png','Selecionar '.ucfirst($config['pdcl_item']),'Clique neste ícone '.imagem('icones/pdcl_item_p.png').' para selecionar '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($instrumento_os ? '' : 'style="display:none"').' id="os" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['os']), 'Caso seja específico de '.($config['genero_os']=='o' ? 'um' : 'uma').' '.$config['os'].', neste campo deverá constar o nome d'.$config['genero_os'].' '.$config['os'].'.').ucfirst($config['os']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="instrumento_os" value="'.$instrumento_os.'" /><input type="text" id="os_nome" name="os_nome" value="'.nome_os($instrumento_os).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="pop_os();">'.imagem('icones/os_p.png','Selecionar '.ucfirst($config['os']),'Clique neste ícone '.imagem('icones/os_p.png').' para selecionar '.($config['genero_os']=='o' ? 'um' : 'uma').' '.$config['os'].'.').'</a></td></tr></table></td></tr>';
	
$sql->adTabela('instrumento_gestao');
$sql->adCampo('instrumento_gestao.*');
if ($uuid) $sql->adOnde('instrumento_gestao_uuid = \''.$uuid.'\'');
else $sql->adOnde('instrumento_gestao_instrumento ='.(int)$instrumento_id);	
$sql->adOrdem('instrumento_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
echo '<tr><td></td><td><div id="combo_gestao">';
if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
foreach($lista as $gestao_data){
	echo '<tr align="center">';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['instrumento_gestao_ordem'].', '.$gestao_data['instrumento_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	if ($gestao_data['instrumento_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['instrumento_gestao_tarefa']).'</td>';
	elseif ($gestao_data['instrumento_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['instrumento_gestao_projeto']).'</td>';
	elseif ($gestao_data['instrumento_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['instrumento_gestao_perspectiva']).'</td>';
	elseif ($gestao_data['instrumento_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['instrumento_gestao_tema']).'</td>';
	elseif ($gestao_data['instrumento_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['instrumento_gestao_objetivo']).'</td>';
	elseif ($gestao_data['instrumento_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['instrumento_gestao_fator']).'</td>';
	elseif ($gestao_data['instrumento_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['instrumento_gestao_estrategia']).'</td>';
	elseif ($gestao_data['instrumento_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['instrumento_gestao_meta']).'</td>';
	elseif ($gestao_data['instrumento_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['instrumento_gestao_pratica']).'</td>';
	elseif ($gestao_data['instrumento_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['instrumento_gestao_acao']).'</td>';
	elseif ($gestao_data['instrumento_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['instrumento_gestao_canvas']).'</td>';
	elseif ($gestao_data['instrumento_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['instrumento_gestao_risco']).'</td>';
	elseif ($gestao_data['instrumento_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['instrumento_gestao_risco_resposta']).'</td>';
	elseif ($gestao_data['instrumento_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['instrumento_gestao_indicador']).'</td>';
	elseif ($gestao_data['instrumento_gestao_calendario']) echo '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['instrumento_gestao_calendario']).'</td>';
	elseif ($gestao_data['instrumento_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['instrumento_gestao_monitoramento']).'</td>';
	elseif ($gestao_data['instrumento_gestao_ata']) echo '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['instrumento_gestao_ata']).'</td>';
	elseif ($gestao_data['instrumento_gestao_mswot']) echo '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['instrumento_gestao_mswot']).'</td>';
	elseif ($gestao_data['instrumento_gestao_swot']) echo '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['instrumento_gestao_swot']).'</td>';
	elseif ($gestao_data['instrumento_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['instrumento_gestao_operativo']).'</td>';
	
	elseif ($gestao_data['instrumento_gestao_semelhante']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['instrumento_gestao_semelhante']).'</td>';
	
	elseif ($gestao_data['instrumento_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['instrumento_gestao_recurso']).'</td>';
	elseif ($gestao_data['instrumento_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['instrumento_gestao_problema']).'</td>';
	elseif ($gestao_data['instrumento_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['instrumento_gestao_demanda']).'</td>';
	elseif ($gestao_data['instrumento_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['instrumento_gestao_programa']).'</td>';
	elseif ($gestao_data['instrumento_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['instrumento_gestao_licao']).'</td>';
	elseif ($gestao_data['instrumento_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['instrumento_gestao_evento']).'</td>';
	elseif ($gestao_data['instrumento_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['instrumento_gestao_link']).'</td>';
	elseif ($gestao_data['instrumento_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['instrumento_gestao_avaliacao']).'</td>';
	elseif ($gestao_data['instrumento_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['instrumento_gestao_tgn']).'</td>';
	elseif ($gestao_data['instrumento_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['instrumento_gestao_brainstorm']).'</td>';
	elseif ($gestao_data['instrumento_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['instrumento_gestao_gut']).'</td>';
	elseif ($gestao_data['instrumento_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['instrumento_gestao_causa_efeito']).'</td>';
	elseif ($gestao_data['instrumento_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['instrumento_gestao_arquivo']).'</td>';
	elseif ($gestao_data['instrumento_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['instrumento_gestao_forum']).'</td>';
	elseif ($gestao_data['instrumento_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['instrumento_gestao_checklist']).'</td>';
	elseif ($gestao_data['instrumento_gestao_agenda']) echo '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['instrumento_gestao_agenda']).'</td>';
	elseif ($gestao_data['instrumento_gestao_agrupamento']) echo '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['instrumento_gestao_agrupamento']).'</td>';
	elseif ($gestao_data['instrumento_gestao_patrocinador']) echo '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['instrumento_gestao_patrocinador']).'</td>';
	elseif ($gestao_data['instrumento_gestao_template']) echo '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['instrumento_gestao_template']).'</td>';
	elseif ($gestao_data['instrumento_gestao_painel']) echo '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['instrumento_gestao_painel']).'</td>';
	elseif ($gestao_data['instrumento_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['instrumento_gestao_painel_odometro']).'</td>';
	elseif ($gestao_data['instrumento_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['instrumento_gestao_painel_composicao']).'</td>';		
	elseif ($gestao_data['instrumento_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['instrumento_gestao_tr']).'</td>';	
	elseif ($gestao_data['instrumento_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['instrumento_gestao_me']).'</td>';	
	elseif ($gestao_data['instrumento_gestao_acao_item']) echo '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['instrumento_gestao_acao_item']).'</td>';	
	elseif ($gestao_data['instrumento_gestao_beneficio']) echo '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['instrumento_gestao_beneficio']).'</td>';	
	elseif ($gestao_data['instrumento_gestao_painel_slideshow']) echo '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['instrumento_gestao_painel_slideshow']).'</td>';	
	elseif ($gestao_data['instrumento_gestao_projeto_viabilidade']) echo '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['instrumento_gestao_projeto_viabilidade']).'</td>';	
	elseif ($gestao_data['instrumento_gestao_projeto_abertura']) echo '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['instrumento_gestao_projeto_abertura']).'</td>';	
	elseif ($gestao_data['instrumento_gestao_plano_gestao']) echo '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['instrumento_gestao_plano_gestao']).'</td>';	
	elseif ($gestao_data['instrumento_gestao_ssti']) echo '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['instrumento_gestao_ssti']).'</td>';
	elseif ($gestao_data['instrumento_gestao_laudo']) echo '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['instrumento_gestao_laudo']).'</td>';
	elseif ($gestao_data['instrumento_gestao_trelo']) echo '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['instrumento_gestao_trelo']).'</td>';
	elseif ($gestao_data['instrumento_gestao_trelo_cartao']) echo '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['instrumento_gestao_trelo_cartao']).'</td>';
	elseif ($gestao_data['instrumento_gestao_pdcl']) echo '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['instrumento_gestao_pdcl']).'</td>';
	elseif ($gestao_data['instrumento_gestao_pdcl_item']) echo '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['instrumento_gestao_pdcl_item']).'</td>';
	elseif ($gestao_data['instrumento_gestao_os']) echo '<td align=left>'.imagem('icones/os_p.png').link_os($gestao_data['instrumento_gestao_os']).'</td>';

	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['instrumento_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
	}
if (count($lista)) echo '</table>';
echo '</div></td></tr>';




echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Casas Signfiicativas', 'Insira o número de casas significativas dos números d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Casas significativas:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" name="instrumento_casa_significativa" id="instrumento_casa_significativa" value="'.($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']).'" size="22" /></td></tr>';

if ($Aplic->profissional && $exibir['instrumento_moeda']) echo '<tr><td align="right" style="white-space: nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_moeda_leg'].':</td><td>'.selecionaVetor($moedas, 'instrumento_moeda', 'class=texto size=1', ($obj->instrumento_moeda ? $obj->instrumento_moeda : 1)).'</td></tr>';
else echo '<input type="hidden" name="instrumento_moeda" id="instrumento_moeda" value="'.($obj->instrumento_moeda ? $obj->instrumento_moeda : 1).'" />';


echo '<tr><td align="right" nowrap" width=150>'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Ativ'.$config['genero_instrumento'], 'Caso '.$config['genero_instrumento'].' '.$config['instrumento'].' ainda esteja ativ'.$config['genero_instrumento'].' deverá estar marcado este campo.').'Ativ'.$config['genero_instrumento'].':'.dicaF().'</td><td><input type="checkbox" value="1" name="instrumento_ativo" '.($obj->instrumento_ativo || !$instrumento_id ? 'checked="checked"' : '').' /></td></tr>';


require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('instrumento', $instrumento_id, 'editar');
$campos_customizados->imprimirHTML();


if ($exibir['instrumento_dados']) echo '</table></fieldset></td></tr>';










if ($Aplic->profissional) include_once BASE_DIR.'/modulos/instrumento/instrumento_editar_pro.php';


echo '<tr><td style="height:1px;"></td></tr>';
echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_notificar\').style.display) document.getElementById(\'apresentar_notificar\').style.display=\'\'; else document.getElementById(\'apresentar_notificar\').style.display=\'none\';"><a class="aba" href="javascript: void(0);"><b>Notificar</b></a></td></tr>';
echo '<tr id="apresentar_notificar" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';



echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($instrumento_id > 0 ? 'modificação' : 'criação').' d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável pel'.$config['genero_instrumento'].' '.$config['instrumento'].'', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável por '.($config['genero_instrumento']=='o' ? 'este' : 'esta').' '.$config['instrumento'].'.').'<label for="email_responsavel">Responsável pel'.$config['genero_instrumento'].' '.$config['instrumento'].'</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_instrumento'].' '.$config['instrumento'].'', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados para '.($config['genero_instrumento']=='o' ? 'este' : 'esta').' '.$config['instrumento'].'.').'<label for="email_designados">Designados para '.$config['genero_instrumento'].' '.$config['instrumento'].'</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro d'.$config['genero_instrumento'].' '.$config['instrumento'].'.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="50" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';



echo '</table></td></tr>';


echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','salvar()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($instrumento_id ? 'edição' : 'criação').' d'.$config['genero_instrumento'].' '.$config['instrumento'].'.','','cancelar();').'</td></tr></table></td></tr>';

echo '</table>';
echo estiloFundoCaixa();

$instrumento_data_celebracao = ($obj->instrumento_data_celebracao ? new CData($obj->instrumento_data_celebracao) : new CData());
$instrumento_data_publicacao = ($obj->instrumento_data_publicacao ? new CData($obj->instrumento_data_publicacao) : new CData());
$instrumento_data_inicio = ($obj->instrumento_data_inicio ? new CData($obj->instrumento_data_inicio) : new CData());
$instrumento_data_termino = ($obj->instrumento_data_termino ? new CData($obj->instrumento_data_termino) : new CData());
$instrumento_prazo_prorrogacao = ($obj->instrumento_prazo_prorrogacao ? new CData($obj->instrumento_prazo_prorrogacao) : new CData());
$instrumento_fim_contrato = ($obj->instrumento_fim_contrato ? new CData($obj->instrumento_fim_contrato) : new CData());

$instrumento_garantia_contratual_vencimento = ($obj->instrumento_garantia_contratual_vencimento ? new CData($obj->instrumento_garantia_contratual_vencimento) : new CData());
echo '</form>';


echo '
<menu class="menu">
  <li class="menu-item"><button type="button" class="menu-btn" onClick="salvar()"><span class=".menu-texto">Salvar</span></button></li>
  <li class="menu-item"><button type="button" class="menu-btn" onClick="cancelar()"><span class=".menu-texto">Cancelar</span></button></li>
</menu>';

?>

<script type="text/javascript">

var menu = document.querySelector('.menu');

function mostraMenu(x, y){
  menu.style.left = x + 'px';
  menu.style.top = y + 'px';
  menu.classList.add('mostra-menu');
	}

function escondeMenu(){
  menu.classList.remove('mostra-menu');
	}

function onContextoMenu(e){
  e.preventDefault();
  mostraMenu(e.pageX, e.pageY);
  document.addEventListener('click', onClick, false);
	}

function onClick(e){
  escondeMenu();
  document.removeEventListener('click', onClick);
	}

document.addEventListener('contextmenu', onContextoMenu, false);


function cancelar(){
	if(confirm('Tem certeza que deseja cancelar?')){
		url_passar(0, "<?php echo $Aplic->getPosicao()?>")
		}
	}







function recalculo_instrumento_supressao(){
	var supressao=moeda2float(document.getElementById('instrumento_supressao').value);
	var valor=moeda2float(document.getElementById('instrumento_valor').value);
	var total = valor*(supressao/100);
	document.getElementById('calculo_supressao').innerHTML=float2moeda(total);
	}


function recalculo_instrumento_acrescimo(){
	var acrescimo=moeda2float(document.getElementById('instrumento_acrescimo').value);
	var valor=moeda2float(document.getElementById('instrumento_valor').value);
	var total = valor*(acrescimo/100);
	document.getElementById('calculo_acrescimo').innerHTML=float2moeda(total);
	}

function recalculo_instrumento_valor(){
	recalculo_instrumento_supressao();
	recalculo_instrumento_acrescimo();
	}



function mudar_tipo(tipo){
	if (tipo.value==0) {
		document.getElementById('instrumento_avulso_custo_servico').value=0;
		document.getElementById('campo_instrumento_avulso_meses').style.display='none';
		document.getElementById('instrumento_avulso_custo_meses').value=0;
		}
	else {
		document.getElementById('instrumento_avulso_custo_servico').value=1;
		document.getElementById('campo_instrumento_avulso_meses').style.display='';
		document.getElementById('instrumento_avulso_custo_meses').value=1;
		}
	}

function mudar_moeda(moeda){
	//if (moeda > 1) document.getElementById('combo_data_moeda').style.display='';
	//else document.getElementById('combo_data_moeda').style.display='none';
	}
	

function editar_custo(instrumento_avulso_custo_id){
	xajax_editar_custo(instrumento_avulso_custo_id, document.getElementById('instrumento_casa_significativa').value);
	<?php if ($Aplic->profissional) { ?>
		CKEDITOR.instances['instrumento_avulso_custo_descricao'].setData(document.getElementById('apoio1').value);

	<?php } ?>
	

	if (document.getElementById('instrumento_avulso_custo_servico').value==0) {
		document.getElementById('custo_servico_0').checked=true;
		
		document.getElementById('campo_instrumento_avulso_meses').style.display='none';
		}
	else {
		document.getElementById('custo_servico_1').checked=true;
		document.getElementById('campo_instrumento_avulso_meses').style.display='';
		}
		
		

		
		
	
	document.getElementById('adicionar_custo').style.display="none";
	document.getElementById('confirmar_custo').style.display="";
	}


function limpar_custo(){
	CKEDITOR.instances['instrumento_avulso_custo_descricao'].setData('');
	document.getElementById('instrumento_avulso_custo_nome').value='';
	document.getElementById('instrumento_avulso_custo_descricao').value='';
	document.getElementById('instrumento_avulso_custo_quantidade').value='';
	document.getElementById('instrumento_avulso_custo_custo').value='';
	document.getElementById('instrumento_avulso_custo_custo_atual').value='';
	document.getElementById('instrumento_avulso_custo_bdi').value='';
	document.getElementById('instrumento_avulso_custo_acrescimo').value='';
	document.getElementById('instrumento_avulso_custo_codigo').value='';
	document.getElementById('instrumento_avulso_custo_fonte').value='';
	document.getElementById('instrumento_avulso_custo_regiao').value='';
	document.getElementById('instrumento_avulso_custo_pi').value='';
	document.getElementById('instrumento_avulso_custo_ptres').value='';
		
	
	document.getElementById('instrumento_avulso_custo_id').value=null;
	document.getElementById('adicionar_custo').style.display='';
	document.getElementById('confirmar_custo').style.display='none';
	document.getElementById('total').innerHTML='';
	}

function incluir_custo(){
	xajax_incluir_custo_ajax(
		document.getElementById('instrumento_id').value,
		document.getElementById('uuid').value,
		document.getElementById('instrumento_casa_significativa').value,
		document.getElementById('instrumento_campo').value,
		moeda2float(document.getElementById('instrumento_valor').value),
		document.getElementById('instrumento_avulso_custo_id').value,
		document.getElementById('instrumento_avulso_custo_nome').value,
		document.getElementById('instrumento_avulso_custo_tipo').value,
		document.getElementById('instrumento_avulso_custo_quantidade').value,
		document.getElementById('instrumento_avulso_custo_custo').value,
		document.getElementById('instrumento_avulso_custo_custo_atual').value,
		CKEDITOR.instances['instrumento_avulso_custo_descricao'].getData(),
		document.getElementById('instrumento_avulso_custo_nd').value,
		document.getElementById('instrumento_avulso_custo_categoria_economica').value,
		document.getElementById('instrumento_avulso_custo_grupo_despesa').value,
		document.getElementById('instrumento_avulso_custo_modalidade_aplicacao').value,
		document.getElementById('instrumento_avulso_custo_data_limite').value,
		document.getElementById('instrumento_avulso_custo_codigo').value,
		document.getElementById('instrumento_avulso_custo_fonte').value,
		document.getElementById('instrumento_avulso_custo_regiao').value,
		document.getElementById('instrumento_avulso_custo_bdi').value,
		document.getElementById('instrumento_avulso_custo_moeda').value,
		document.getElementById('instrumento_avulso_custo_data_moeda').value,
		document.getElementById('instrumento_avulso_custo_meses').value,
		document.getElementById('instrumento_avulso_custo_servico').value,
		document.getElementById('instrumento_avulso_custo_acrescimo').value,
		document.getElementById('instrumento_avulso_custo_percentual').value,
		document.getElementById('instrumento_avulso_custo_pi').value,
		document.getElementById('instrumento_avulso_custo_ptres').value,
		document.getElementById('instrumento_avulso_custo_exercicio').value
		);
	__buildTooltip();
	limpar_custo();
	}

function excluir_custo(instrumento_avulso_custo_id){
	if (confirm('Tem certeza que deseja excluir?')) {
		xajax_excluir_custo_ajax(
			instrumento_avulso_custo_id, 
			document.getElementById('instrumento_id').value, 
			document.getElementById('uuid').value, 
			document.getElementById('instrumento_casa_significativa').value,
			document.getElementById('instrumento_campo').value, 
			moeda2float(document.getElementById('instrumento_valor').value)
			);
		__buildTooltip();
		}
	}

function mudar_posicao_custo(ordem, instrumento_avulso_custo_id, direcao){
	xajax_mudar_posicao_custo(
		ordem, instrumento_avulso_custo_id, 
		direcao, 
		document.getElementById('instrumento_id').value, 
		document.getElementById('uuid').value, 
		document.getElementById('instrumento_casa_significativa').value,
		document.getElementById('instrumento_campo').value
		);
	__buildTooltip();
	}


function mudar_nd(){
	xajax_mudar_nd_ajax(env.instrumento_avulso_custo_nd.value, 'instrumento_avulso_custo_nd', 'combo_nd','class=texto size=1 style="width:395px;" onchange="mudar_nd();"', 3, env.instrumento_avulso_custo_categoria_economica.value, env.instrumento_avulso_custo_grupo_despesa.value, env.instrumento_avulso_custo_modalidade_aplicacao.value);
	}

function float2moeda(num){
	x=0;
	if (num<0){
		num=Math.abs(num);
		x=1;
		}
	if(isNaN(num))num="0";
	cents=Math.floor((num*100+0.5)%100);
	num=Math.floor((num*100+0.5)/100).toString();
	if(cents<10) cents="0"+cents;
	for (var i=0; i< Math.floor((num.length-(1+i))/3); i++) num=num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
	ret=num+','+cents;
	if(x==1) ret = ' - '+ret;
	return ret;
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
	
	
function valor(){
	var custo=moeda2float(document.getElementById('instrumento_avulso_custo_custo').value);
	var custo_atual=moeda2float(document.getElementById('instrumento_avulso_custo_custo_atual').value);
	var qnt=moeda2float(document.getElementById('instrumento_avulso_custo_quantidade').value);
	var bdi=moeda2float(document.getElementById('instrumento_avulso_custo_bdi').value);
	var acrescimo=moeda2float(document.getElementById('instrumento_avulso_custo_acrescimo').value);
	var ser_percentual=document.getElementById('instrumento_avulso_custo_percentual').value;
	var is_servico=document.getElementById('instrumento_avulso_custo_servico').value*1;
	var parcelas=document.getElementById('instrumento_avulso_custo_meses').value*1;
	var valor_final = 0;

	if (!custo) custo=0;
	if (!custo_atual) custo_atual=0;
	if (!bdi) bdi=0;
	if (!acrescimo) acrescimo=0;
	if (!parcelas) parcelas = 0;

	if(!is_servico) parcelas = 1;

	custo = custo_atual > 0 ? custo_atual : custo;

	valor_final = (qnt * custo) * ((100+bdi)/100);
	valor_final *= parcelas;

	if(acrescimo){
        if (!ser_percentual){
            valor_final += custo * acrescimo;
        }
        else{
            valor_final += valor_final * (acrescimo/100);
        }
	}
	
	document.getElementById('total').innerHTML ='<b>'+float2moeda(valor_final)+'</b>';
	}
	
	
//financeiro
function mudar_posicao_financeiro(ordem, instrumento_financeiro_id, direcao){
	xajax_mudar_posicao_financeiro_ajax(
	ordem, 
	instrumento_financeiro_id, 
	direcao, 
	document.getElementById('instrumento_id').value, 
	document.getElementById('uuid').value, 
	document.getElementById('instrumento_casa_significativa').value, 
	document.getElementById('instrumento_campo').value
	);
	__buildTooltip();
	}

function editar_financeiro(instrumento_financeiro_id){
	xajax_editar_financeiro(instrumento_financeiro_id, document.getElementById('instrumento_casa_significativa').value);
	document.getElementById('adicionar_financeiro').style.display="none";
	document.getElementById('confirmar_financeiro').style.display="";
	}

function incluir_financeiro(){
	if (document.getElementById('instrumento_financeiro_projeto').value.length > 0){
		xajax_incluir_financeiro_ajax(
			document.getElementById('instrumento_financeiro_id').value, 
			document.getElementById('instrumento_id').value, 
			document.getElementById('uuid').value, 
			document.getElementById('instrumento_casa_significativa').value,
			document.getElementById('instrumento_financeiro_projeto').value, 
			document.getElementById('instrumento_financeiro_tarefa').value, 
			document.getElementById('instrumento_financeiro_fonte').value, 
			document.getElementById('instrumento_financeiro_regiao').value, 
			document.getElementById('instrumento_financeiro_classificacao').value, 
			document.getElementById('instrumento_financeiro_valor').value, 
			document.getElementById('instrumento_financeiro_ano').value, 
			document.getElementById('instrumento_campo').value
			);
		document.getElementById('instrumento_financeiro_id').value=null;
		document.getElementById('instrumento_financeiro_projeto').value='';
		document.getElementById('instrumento_financeiro_tarefa').value='';
		document.getElementById('instrumento_financeiro_regiao').value='';
		document.getElementById('instrumento_financeiro_classificacao').value='';
		document.getElementById('instrumento_financeiro_valor').value='';

		document.getElementById('adicionar_financeiro').style.display='';
		document.getElementById('confirmar_financeiro').style.display='none';
		__buildTooltip();
		}
	else alert('Insira informação financeira.');
	}

function cancelar_financeiro(){
	document.getElementById('instrumento_financeiro_id').value=0;
	
	document.getElementById('instrumento_financeiro_projeto').value='';	
	document.getElementById('instrumento_financeiro_tarefa').value='';	
	document.getElementById('instrumento_financeiro_regiao').value='';	
	document.getElementById('instrumento_financeiro_classificacao').value='';	
	document.getElementById('instrumento_financeiro_valor').value='';	

	document.getElementById('adicionar_financeiro').style.display='';	
	document.getElementById('confirmar_financeiro').style.display='none';
	
	}


function excluir_financeiro(instrumento_financeiro_id){
	xajax_excluir_financeiro_ajax(
	instrumento_financeiro_id, 
	document.getElementById('instrumento_id').value, 
	document.getElementById('uuid').value, 
	document.getElementById('instrumento_casa_significativa').value, 
	document.getElementById('instrumento_campo').value
	);
	__buildTooltip();
	}

















function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('instrumento_cia').value+'&cias_id_selecionadas='+document.getElementById('instrumento_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.instrumento_cias.value = organizacao_id_string;
	document.getElementById('instrumento_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('instrumento_cias').value);
	__buildTooltip();
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
	var instrumento_emails = document.getElementById('instrumento_usuarios');
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

var cal1 = Calendario.setup({
	trigger    : "botao_instrumento_data_celebracao",
  inputField : "instrumento_data_celebracao",
	date :  <?php echo $instrumento_data_celebracao->format("%Y%m%d")?>,
	selection: <?php echo $instrumento_data_celebracao->format("%Y%m%d")?>,
  onSelect: function(cal1) {
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("instrumento_data_celebracao_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("instrumento_data_celebracao").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal1.hide();
	}
});

var cal2 = Calendario.setup({
	trigger    : "botao_instrumento_data_publicacao",
  inputField : "instrumento_data_publicacao",
	date :  <?php echo $instrumento_data_publicacao->format("%Y%m%d")?>,
	selection: <?php echo $instrumento_data_publicacao->format("%Y%m%d")?>,
  onSelect: function(cal2) {
  var date = cal2.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("instrumento_data_publicacao_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("instrumento_data_publicacao").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal2.hide();
	}
});

var cal3 = Calendario.setup({
	trigger    : "botao_instrumento_data_inicio",
  inputField : "instrumento_data_inicio",
	date :  <?php echo $instrumento_data_inicio->format("%Y%m%d")?>,
	selection: <?php echo $instrumento_data_inicio->format("%Y%m%d")?>,
  onSelect: function(cal3) {
  var date = cal3.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("instrumento_data_inicio_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("instrumento_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal3.hide();
	}
});

var cal4 = Calendario.setup({
	trigger    : "botao_instrumento_data_termino",
  inputField : "instrumento_data_termino",
	date :  <?php echo $instrumento_data_termino->format("%Y%m%d")?>,
	selection: <?php echo $instrumento_data_termino->format("%Y%m%d")?>,
  onSelect: function(cal4) {
  var date = cal4.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("instrumento_data_termino_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("instrumento_data_termino").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal4.hide();
	}
});



var cal5 = Calendario.setup({
	trigger    : "botao_instrumento_avulso_custo_data_moeda",
  inputField : "instrumento_avulso_custo_data_moeda",
	date :  <?php echo $data_texto->format("%Y%m%d")?>,
	selection: <?php echo $data_texto->format("%Y%m%d")?>,
  onSelect: function(cal5) {
  var date = cal5.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("instrumento_avulso_custo_data_moeda_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("instrumento_avulso_custo_data_moeda").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal5.hide();
	}
});

var cal6 = Calendario.setup({
	trigger    : "botao_instrumento_avulso_custo_data_limite",
  inputField : "instrumento_avulso_custo_data_limite",
	date :  <?php echo $data_texto->format("%Y%m%d")?>,
	selection: <?php echo $data_texto->format("%Y%m%d")?>,
  onSelect: function(cal6) {
  var date = cal6.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("instrumento_avulso_custo_data_limite_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("instrumento_avulso_custo_data_limite").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal6.hide();
	}
});


var cal7 = Calendario.setup({
	trigger    : "botao_instrumento_fim_contrato",
  inputField : "instrumento_fim_contrato",
	date :  <?php echo $instrumento_fim_contrato->format("%Y%m%d")?>,
	selection: <?php echo $instrumento_fim_contrato->format("%Y%m%d")?>,
  onSelect: function(cal7) {
  var date = cal7.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("instrumento_fim_contrato_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("instrumento_fim_contrato").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal7.hide();
	}
});





var cal8 = Calendario.setup({
	trigger    : "botao_instrumento_garantia_contratual_vencimento",
  inputField : "instrumento_garantia_contratual_vencimento",
	date :  <?php echo $instrumento_garantia_contratual_vencimento->format("%Y%m%d")?>,
	selection: <?php echo $instrumento_garantia_contratual_vencimento->format("%Y%m%d")?>,
  onSelect: function(cal8) {
  var date = cal8.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("instrumento_garantia_contratual_vencimento_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("instrumento_garantia_contratual_vencimento").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal8.hide();
	}
});



function setData( frm_nome, f_data , f_data_real) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real);
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
    	}
    else {
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			}
		}
	else campo_data_real.value = '';	
	}




function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.instrumento_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.instrumento_cor.value;
	}


function salvar() {
	var f = document.env;
	xajax_instrumento_existe(f.instrumento_nome.value, document.getElementById('instrumento_id').value);
	if (f.instrumento_nome.value.length < 3) {
		alert("Insira um nome para <?php echo $config['genero_instrumento'].' '.$config['instrumento']?>" );
		f.instrumento_nome.focus();
		}
	else if (document.getElementById("existe_instrumento").value > 0) {
		alert('Já existe <?php echo $config["instrumento"]?> com este nome');
		f.instrumento_nome.focus();
		}	
	else {
		f.instrumento_valor.value=moeda2float(f.instrumento_valor.value);
		f.instrumento_valor_contrapartida.value=moeda2float(f.instrumento_valor_contrapartida.value);
		f.instrumento_valor_repasse.value=moeda2float(f.instrumento_valor_repasse.value);
		f.instrumento_supressao.value=moeda2float(f.instrumento_supressao.value);
		f.instrumento_acrescimo.value=moeda2float(f.instrumento_acrescimo.value);
		f.instrumento_garantia_contratual_percentual.value=moeda2float(f.instrumento_garantia_contratual_percentual.value);

		if (f.instrumento_campo.value==6) f.instrumento_valor_atual.value=f.instrumento_valor.value;
		
		f.submit();
		}
	}

function excluir() {
	if (confirm( "Excluir <?php echo ($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento']?>?" )) {
		var f = document.env;
		f.del.value='1';
		f.submit();
		}
	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('instrumento_cia').value,'instrumento_cia','combo_cia', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om();"');
	}


function popRecursos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["recursos"])?>', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=2&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('instrumento_cia').value+'&selecionado='+document.getElementById('instrumento_recursos').value, window.setRecursos, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&tabela=recursos&dialogo=1&chamar_volta=setRecursos&cia_id='+document.getElementById('instrumento_cia').value+'&valores='+document.getElementById('instrumento_recursos').value, '<?php echo ucfirst($config["recursos"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function setRecursos(recurso_id_string){
	if(!recurso_id_string) recurso_id_string = '';
	document.env.instrumento_recursos.value = recurso_id_string;
	xajax_exibir_recursos(recurso_id_string);
	__buildTooltip();
	}




function popContatos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('instrumento_cia').value+'&contatos_id_selecionados='+document.getElementById('instrumento_contatos').value, window.setContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('instrumento_cia').value+'&contatos_id_selecionados='+document.getElementById('instrumento_contatos').value, '<?php echo ucfirst($config["contatos"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function setContatos(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.instrumento_contatos.value = contato_id_string;
	xajax_exibir_contatos(contato_id_string);
	__buildTooltip();
	}



function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('instrumento_cia').value+'&usuarios_id_selecionados='+document.getElementById('instrumento_usuarios').value, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('instrumento_cia').value+'&usuarios_id_selecionados='+document.getElementById('instrumento_usuarios').value, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.instrumento_usuarios.value = usuario_id_string;
	xajax_exibir_usuarios(usuario_id_string);
	__buildTooltip();
	}


function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('instrumento_cia').value+'&depts_id_selecionados='+document.getElementById('instrumento_depts').value, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('instrumento_cia').value+'&depts_id_selecionados='+document.getElementById('instrumento_depts').value, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.instrumento_depts.value = departamento_id_string;
	xajax_exibir_depts(departamento_id_string);
	__buildTooltip();
	}




function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('instrumento_dept').value+'&cia_id='+document.getElementById('instrumento_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('instrumento_dept').value+'&cia_id='+document.getElementById('instrumento_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('instrumento_cia').value=cia_id;
	document.getElementById('instrumento_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('instrumento_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}





function popSupervisor() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['supervisor']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_supervisor').value, window.setSupervisor, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_supervisor').value, 'Supervisor','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setSupervisor(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('instrumento_supervisor').value=usuario_id;
		document.getElementById('nome_supervisor').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}

function popAutoridade() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['autoridade']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_autoridade').value, window.setAutoridade, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_autoridade').value, 'Autoridade','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setAutoridade(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('instrumento_autoridade').value=usuario_id;
		document.getElementById('nome_autoridade').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function popCliente() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['cliente']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_cliente').value, window.setCliente, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCliente&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_cliente').value, "<?php echo ucfirst($config['cliente']) ?>",'height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setCliente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('instrumento_cliente').value=usuario_id;
		document.getElementById('nome_cliente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}




function popFiscal() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("Fiscal", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setFiscal&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_cliente').value, window.setFiscal, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setFiscal&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_fiscal').value, "Fiscal",'height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setFiscal(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('instrumento_fiscal').value=usuario_id;
		document.getElementById('nome_instrumento_fiscal').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function popFiscalSubstituto() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("Fiscal Substituto", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setFiscalSubstituto&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_cliente').value, window.setFiscalSubstituto, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setFiscalSubstituto&cia_id='+document.getElementById('instrumento_cia').value+'&usuario_id='+document.getElementById('instrumento_fiscal_substituto').value, "Fscal Substituto",'height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setFiscalSubstituto(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('instrumento_fiscal_substituto').value=usuario_id;
		document.getElementById('nome_instrumento_fiscal_substituto').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
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
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('instrumento_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('instrumento_cia').value, 'Agrupamento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.instrumento_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		incluir_relacionado();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('instrumento_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.instrumento_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		incluir_relacionado();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('instrumento_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('instrumento_cia').value, 'Modelo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.instrumento_template.value = chave;
		document.env.template_nome.value = valor;
		incluir_relacionado();
		}		
<?php } ?>

function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('instrumento_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.instrumento_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	incluir_relacionado();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('instrumento_cia').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.instrumento_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	incluir_relacionado();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('instrumento_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.instrumento_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	incluir_relacionado();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('instrumento_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.instrumento_tema.value = chave;
	document.env.tema_nome.value = valor;
	incluir_relacionado();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('instrumento_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.instrumento_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('instrumento_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.instrumento_fator.value = chave;
	document.env.fator_nome.value = valor;
	incluir_relacionado();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('instrumento_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.instrumento_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	incluir_relacionado();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('instrumento_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.instrumento_meta.value = chave;
	document.env.meta_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('instrumento_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.instrumento_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	incluir_relacionado();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('instrumento_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('instrumento_cia').value, 'Indicador','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.instrumento_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	incluir_relacionado();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('instrumento_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.instrumento_acao.value = chave;
	document.env.acao_nome.value = valor;
	incluir_relacionado();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('instrumento_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.instrumento_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('instrumento_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.instrumento_risco.value = chave;
	document.env.risco_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('instrumento_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.instrumento_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('instrumento_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('instrumento_cia').value, 'Agenda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.instrumento_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	incluir_relacionado();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('instrumento_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('instrumento_cia').value, 'Monitoramento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.instrumento_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('instrumento_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.instrumento_ata.value = chave;
	document.env.ata_nome.value = valor;
	incluir_relacionado();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('instrumento_cia').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.instrumento_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Camçpo SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('instrumento_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.instrumento_swot.value = chave;
	document.env.swot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('instrumento_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('instrumento_cia').value, 'Plano Operativo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.instrumento_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('instrumento_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('instrumento_cia').value, 'Instrumento Jurídico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.instrumento_instrumento.value = chave;
	document.env.gestao_instrumento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('instrumento_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('instrumento_cia').value, 'Recurso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.instrumento_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('instrumento_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.instrumento_problema.value = chave;
	document.env.problema_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('instrumento_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('instrumento_cia').value, 'Demanda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.instrumento_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('instrumento_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.instrumento_programa.value = chave;
	document.env.programa_nome.value = valor;
	incluir_relacionado();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('instrumento_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.instrumento_licao.value = chave;
	document.env.licao_nome.value = valor;
	incluir_relacionado();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('instrumento_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('instrumento_cia').value, 'Evento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.instrumento_evento.value = chave;
	document.env.evento_nome.value = valor;
	incluir_relacionado();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('instrumento_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('instrumento_cia').value, 'Link','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.instrumento_link.value = chave;
	document.env.link_nome.value = valor;
	incluir_relacionado();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('instrumento_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('instrumento_cia').value, 'Avaliação','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.instrumento_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	incluir_relacionado();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('instrumento_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.instrumento_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('instrumento_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('instrumento_cia').value, 'Brainstorm','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.instrumento_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	incluir_relacionado();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('instrumento_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('instrumento_cia').value, 'Matriz GUT','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.instrumento_gut.value = chave;
	document.env.gut_nome.value = valor;
	incluir_relacionado();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('instrumento_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('instrumento_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.instrumento_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	incluir_relacionado();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('instrumento_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('instrumento_cia').value, 'Arquivo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.instrumento_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	incluir_relacionado();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('instrumento_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('instrumento_cia').value, 'Fórum','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.instrumento_forum.value = chave;
	document.env.forum_nome.value = valor;
	incluir_relacionado();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('instrumento_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('instrumento_cia').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.instrumento_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	incluir_relacionado();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('instrumento_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('instrumento_cia').value, 'Compromisso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.instrumento_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	incluir_relacionado();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('instrumento_cia').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('instrumento_cia').value, 'Painel','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.instrumento_painel.value = chave;
	document.env.painel_nome.value = valor;
	incluir_relacionado();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('instrumento_cia').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('instrumento_cia').value, 'Odômetro','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.instrumento_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	incluir_relacionado();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('instrumento_cia').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('instrumento_cia').value, 'Composição de Painéis','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.instrumento_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('instrumento_cia').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.instrumento_tr.value = chave;
	document.env.tr_nome.value = valor;
	incluir_relacionado();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('instrumento_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.instrumento_me.value = chave;
	document.env.me_nome.value = valor;
	incluir_relacionado();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('instrumento_cia').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('instrumento_cia').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.instrumento_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	incluir_relacionado();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('instrumento_cia').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.instrumento_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	incluir_relacionado();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composições', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('instrumento_cia').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('instrumento_cia').value, 'Slideshow de Composições','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.instrumento_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	incluir_relacionado();
	}	

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('instrumento_cia').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('instrumento_cia').value, 'Estudo de Viabilidade','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.instrumento_projeto_viabilidade.value = chave;
	document.env.projeto_viabilidade_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('instrumento_cia').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('instrumento_cia').value, 'Termo de Abertura','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.instrumento_projeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	incluir_relacionado();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estratégico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('instrumento_cia').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('instrumento_cia').value, 'Planejamento Estratégico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.instrumento_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	incluir_relacionado();
	}		

function popSSTI() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('instrumento_cia').value, window.setSSTI, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSSTI(chave, valor){
	limpar_tudo();
	document.env.instrumento_ssti.value = chave;
	document.env.ssti_nome.value = valor;
	incluir_relacionado();
	}	
				
function popLaudo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('instrumento_cia').value, window.setLaudo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLaudo(chave, valor){
	limpar_tudo();
	document.env.instrumento_laudo.value = chave;
	document.env.laudo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popTrelo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('instrumento_cia').value, window.setTrelo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTrelo(chave, valor){
	limpar_tudo();
	document.env.instrumento_trelo.value = chave;
	document.env.trelo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTreloCartao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('instrumento_cia').value, window.setTreloCartao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTreloCartao(chave, valor){
	limpar_tudo();
	document.env.instrumento_trelo_cartao.value = chave;
	document.env.trelo_cartao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPDCL() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('instrumento_cia').value, window.setPDCL, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCL(chave, valor){
	limpar_tudo();
	document.env.instrumento_pdcl.value = chave;
	document.env.pdcl_nome.value = valor;
	incluir_relacionado();
	}				
	
function pop_pdcl_item() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=set_pdcl_item&tabela=pdcl_item&cia_id='+document.getElementById('instrumento_cia').value, window.set_pdcl_item, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=set_pdcl_item&tabela=pdcl_item&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function set_pdcl_item(chave, valor){
	limpar_tudo();
	document.env.instrumento_pdcl_item.value = chave;
	document.env.pdcl_item_nome.value = valor;
	incluir_relacionado();
	}	

function pop_os() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["os"])?>', 1000, 700, 'm=os&a=os_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=set_os&tabela=os&cia_id='+document.getElementById('instrumento_cia').value, window.set_os, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=set_os&tabela=os&cia_id='+document.getElementById('instrumento_cia').value, '<?php echo ucfirst($config["os"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function set_os(chave, valor){
	limpar_tudo();
	document.env.instrumento_os.value = chave;
	document.env.os_nome.value = valor;
	incluir_relacionado();
	}	

function limpar_tudo(){
	document.env.projeto_nome.value = '';
	document.env.instrumento_projeto.value = null;
	document.env.instrumento_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.instrumento_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.instrumento_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.instrumento_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.instrumento_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.instrumento_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.instrumento_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.instrumento_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.instrumento_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.instrumento_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.instrumento_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.instrumento_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.instrumento_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.instrumento_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.instrumento_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.instrumento_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.instrumento_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.instrumento_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.instrumento_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.instrumento_instrumento.value = null;
	document.env.gestao_instrumento_nome.value = '';
	document.env.instrumento_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.instrumento_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.instrumento_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.instrumento_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.instrumento_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.instrumento_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.instrumento_link.value = null;
	document.env.link_nome.value = '';
	document.env.instrumento_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.instrumento_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.instrumento_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.instrumento_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.instrumento_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.instrumento_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.instrumento_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.instrumento_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.instrumento_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.instrumento_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.instrumento_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.instrumento_template.value = null;
	document.env.template_nome.value = '';
	document.env.instrumento_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.instrumento_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.instrumento_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.instrumento_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.instrumento_me.value = null;
	document.env.me_nome.value = '';
	document.env.instrumento_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.instrumento_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.instrumento_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	document.env.instrumento_projeto_viabilidade.value = null;
	document.env.projeto_viabilidade_nome.value = '';
	document.env.instrumento_projeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.instrumento_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	document.env.instrumento_ssti.value = null;
	document.env.ssti_nome.value = '';
	document.env.instrumento_laudo.value = null;
	document.env.laudo_nome.value = '';
	document.env.instrumento_trelo.value = null;
	document.env.trelo_nome.value = '';
	document.env.instrumento_trelo_cartao.value = null;
	document.env.trelo_cartao_nome.value = '';
	document.env.instrumento_pdcl.value = null;
	document.env.pdcl_nome.value = '';
	document.env.instrumento_pdcl_item.value = null;
	document.env.pdcl_item_nome.value = '';	
	document.env.instrumento_os.value = null;
	document.env.os_nome.value = '';				
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('instrumento_id').value,
	document.getElementById('uuid').value,
	f.instrumento_projeto.value,
	f.instrumento_tarefa.value,
	f.instrumento_perspectiva.value,
	f.instrumento_tema.value,
	f.instrumento_objetivo.value,
	f.instrumento_fator.value,
	f.instrumento_estrategia.value,
	f.instrumento_meta.value,
	f.instrumento_pratica.value,
	f.instrumento_acao.value,
	f.instrumento_canvas.value,
	f.instrumento_risco.value,
	f.instrumento_risco_resposta.value,
	f.instrumento_indicador.value,
	f.instrumento_calendario.value,
	f.instrumento_monitoramento.value,
	f.instrumento_ata.value,
	f.instrumento_mswot.value,
	f.instrumento_swot.value,
	f.instrumento_operativo.value,
	f.instrumento_instrumento.value,
	f.instrumento_recurso.value,
	f.instrumento_problema.value,
	f.instrumento_demanda.value,
	f.instrumento_programa.value,
	f.instrumento_licao.value,
	f.instrumento_evento.value,
	f.instrumento_link.value,
	f.instrumento_avaliacao.value,
	f.instrumento_tgn.value,
	f.instrumento_brainstorm.value,
	f.instrumento_gut.value,
	f.instrumento_causa_efeito.value,
	f.instrumento_arquivo.value,
	f.instrumento_forum.value,
	f.instrumento_checklist.value,
	f.instrumento_agenda.value,
	f.instrumento_agrupamento.value,
	f.instrumento_patrocinador.value,
	f.instrumento_template.value,
	f.instrumento_painel.value,
	f.instrumento_painel_odometro.value,
	f.instrumento_painel_composicao.value,
	f.instrumento_tr.value,
	f.instrumento_me.value,
	f.instrumento_acao_item.value,
	f.instrumento_beneficio.value,
	f.instrumento_painel_slideshow.value,
	f.instrumento_projeto_viabilidade.value,
	f.instrumento_projeto_abertura.value,
	f.instrumento_plano_gestao.value,
	f.instrumento_ssti.value,
	f.instrumento_laudo.value,
	f.instrumento_trelo.value,
	f.instrumento_trelo_cartao.value,
	f.instrumento_pdcl.value,
	f.instrumento_pdcl_item.value,
	f.instrumento_os.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(instrumento_gestao_id){
	xajax_excluir_gestao(document.getElementById('instrumento_id').value, document.getElementById('instrumento_casa_significativa').value, instrumento_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, instrumento_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, instrumento_gestao_id, direcao, document.getElementById('instrumento_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$instrumento_id && (
	$instrumento_tarefa || 
	$instrumento_projeto || 
	$instrumento_perspectiva || 
	$instrumento_tema || 
	$instrumento_objetivo || 
	$instrumento_fator || 
	$instrumento_estrategia || 
	$instrumento_meta || 
	$instrumento_pratica || 
	$instrumento_acao || 
	$instrumento_canvas || 
	$instrumento_risco || 
	$instrumento_risco_resposta || 
	$instrumento_indicador || 
	$instrumento_calendario || 
	$instrumento_monitoramento || 
	$instrumento_ata || 
	$instrumento_mswot || 
	$instrumento_swot || 
	$instrumento_operativo || 
	$instrumento_instrumento || 
	$instrumento_recurso || 
	$instrumento_problema || 
	$instrumento_demanda || 
	$instrumento_programa || 
	$instrumento_licao || 
	$instrumento_evento || 
	$instrumento_link || 
	$instrumento_avaliacao || 
	$instrumento_tgn || 
	$instrumento_brainstorm || 
	$instrumento_gut || 
	$instrumento_causa_efeito || 
	$instrumento_arquivo || 
	$instrumento_forum || 
	$instrumento_checklist || 
	$instrumento_agenda || 
	$instrumento_agrupamento || 
	$instrumento_patrocinador || 
	$instrumento_template || 
	$instrumento_painel || 
	$instrumento_painel_odometro || 
	$instrumento_painel_composicao || 
	$instrumento_tr || 
	$instrumento_me || 
	$instrumento_acao_item || 
	$instrumento_beneficio || 
	$instrumento_painel_slideshow || 
	$instrumento_projeto_viabilidade || 
	$instrumento_projeto_abertura || 
	$instrumento_plano_gestao|| 
	$instrumento_ssti || 
	$instrumento_laudo || 
	$instrumento_trelo || 
	$instrumento_trelo_cartao || 
	$instrumento_pdcl || 
	$instrumento_pdcl_item || 
	$instrumento_os
	)) echo 'incluir_relacionado();';
	?>	

//processo
function mudar_posicao_processo(ordem, instrumento_processo_id, direcao){
	xajax_mudar_posicao_processo(ordem, instrumento_processo_id, direcao, document.getElementById('instrumento_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function editar_processo(instrumento_processo_id){

	xajax_editar_processo(instrumento_processo_id);
	document.getElementById('adicionar_processo').style.display="none";
	document.getElementById('confirmar_processo').style.display="";

	}

function incluir_processo(){
	if (document.getElementById('instrumento_processo_processo').value !=''){
		xajax_incluir_processo(
		document.getElementById('instrumento_id').value,
		document.getElementById('uuid').value, 
		document.getElementById('instrumento_processo_id').value, 
		document.getElementById('instrumento_processo_processo').value);
		
		document.getElementById('instrumento_processo_id').value=null;
		document.getElementById('instrumento_processo_processo').value='';
		document.getElementById('adicionar_processo').style.display='';
		document.getElementById('confirmar_processo').style.display='none';
		__buildTooltip();
		}
	else alert('Insira uma processo.');
	}

function excluir_processo(instrumento_processo_id){
	xajax_excluir_processo(instrumento_processo_id, document.getElementById('instrumento_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}		
	
	
	
	
	
//edital
function mudar_posicao_edital(ordem, instrumento_edital_id, direcao){
	xajax_mudar_posicao_edital(ordem, instrumento_edital_id, direcao, document.getElementById('instrumento_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function editar_edital(instrumento_edital_id){

	xajax_editar_edital(instrumento_edital_id);
	document.getElementById('adicionar_edital').style.display="none";
	document.getElementById('confirmar_edital').style.display="";

	}

function incluir_edital(){
	if (document.getElementById('instrumento_edital_edital').value !=''){
		xajax_incluir_edital(document.getElementById('instrumento_id').value, 
		document.getElementById('uuid').value, 
		document.getElementById('instrumento_edital_id').value, 
		document.getElementById('instrumento_edital_edital').value);
		
		document.getElementById('instrumento_edital_id').value=null;
		document.getElementById('instrumento_edital_edital').value='';
		document.getElementById('adicionar_edital').style.display='';
		document.getElementById('confirmar_edital').style.display='none';
		__buildTooltip();
		}
	else alert('Insira uma edital.');
	}

function excluir_edital(instrumento_edital_id){
	xajax_excluir_edital(instrumento_edital_id, document.getElementById('instrumento_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}		


</script>
