<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5 
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR'))	die('Voc? n?o deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;
echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
$Aplic->carregarCKEditorJS();

require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$Aplic->carregarCalendarioJS();

$checklist_id=getParam($_REQUEST, 'checklist_id', null);
require_once (BASE_DIR.'/modulos/praticas/checklist.class.php');





$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'checklist\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();



$checklist_acesso = getSisValor('NivelAcesso','','','sisvalor_id');


$botoesTitulo = new CBlocoTitulo(($checklist_id ? 'Editar Checklist' : 'Criar Checklist'), 'todo_list.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();


$checklist_projeto=getParam($_REQUEST, 'checklist_projeto', null);
$checklist_tarefa=getParam($_REQUEST, 'checklist_tarefa', null);
$checklist_perspectiva=getParam($_REQUEST, 'checklist_perspectiva', null);
$checklist_tema=getParam($_REQUEST, 'checklist_tema', null);
$checklist_objetivo=getParam($_REQUEST, 'checklist_objetivo', null);
$checklist_fator=getParam($_REQUEST, 'checklist_fator', null);
$checklist_estrategia=getParam($_REQUEST, 'checklist_estrategia', null);
$checklist_meta=getParam($_REQUEST, 'checklist_meta', null);
$checklist_pratica=getParam($_REQUEST, 'checklist_pratica', null);
$checklist_acao=getParam($_REQUEST, 'checklist_acao', null);
$checklist_canvas=getParam($_REQUEST, 'checklist_canvas', null);
$checklist_risco=getParam($_REQUEST, 'checklist_risco', null);
$checklist_risco_resposta=getParam($_REQUEST, 'checklist_risco_resposta', null);
$checklist_indicador=getParam($_REQUEST, 'checklist_indicador', null);
$checklist_calendario=getParam($_REQUEST, 'checklist_calendario', null);
$checklist_monitoramento=getParam($_REQUEST, 'checklist_monitoramento', null);
$checklist_ata=getParam($_REQUEST, 'checklist_ata', null);
$checklist_mswot=getParam($_REQUEST, 'checklist_mswot', null);
$checklist_swot=getParam($_REQUEST, 'checklist_swot', null);
$checklist_operativo=getParam($_REQUEST, 'checklist_operativo', null);
$checklist_instrumento=getParam($_REQUEST, 'checklist_instrumento', null);
$checklist_recurso=getParam($_REQUEST, 'checklist_recurso', null);
$checklist_problema=getParam($_REQUEST, 'checklist_problema', null);
$checklist_demanda=getParam($_REQUEST, 'checklist_demanda', null);
$checklist_programa=getParam($_REQUEST, 'checklist_programa', null);
$checklist_licao=getParam($_REQUEST, 'checklist_licao', null);
$checklist_evento=getParam($_REQUEST, 'checklist_evento', null);
$checklist_link=getParam($_REQUEST, 'checklist_link', null);
$checklist_avaliacao=getParam($_REQUEST, 'checklist_avaliacao', null);
$checklist_tgn=getParam($_REQUEST, 'checklist_tgn', null);
$checklist_brainstorm=getParam($_REQUEST, 'checklist_brainstorm', null);
$checklist_gut=getParam($_REQUEST, 'checklist_gut', null);
$checklist_causa_efeito=getParam($_REQUEST, 'checklist_causa_efeito', null);
$checklist_arquivo=getParam($_REQUEST, 'checklist_arquivo', null);
$checklist_forum=getParam($_REQUEST, 'checklist_forum', null);
$checklist_checklist=getParam($_REQUEST, 'checklist_checklist', null);
$checklist_agenda=getParam($_REQUEST, 'checklist_agenda', null);
$checklist_agrupamento=getParam($_REQUEST, 'checklist_agrupamento', null);
$checklist_patrocinador=getParam($_REQUEST, 'checklist_patrocinador', null);
$checklist_template=getParam($_REQUEST, 'checklist_template', null);
$checklist_painel=getParam($_REQUEST, 'checklist_painel', null);
$checklist_painel_odometro=getParam($_REQUEST, 'checklist_painel_odometro', null);
$checklist_painel_composicao=getParam($_REQUEST, 'checklist_painel_composicao', null);
$checklist_tr=getParam($_REQUEST, 'checklist_tr', null);
$checklist_me=getParam($_REQUEST, 'checklist_me', null);
$checklist_acao_item=getParam($_REQUEST, 'checklist_acao_item', null);
$checklist_beneficio=getParam($_REQUEST, 'checklist_beneficio', null);
$checklist_painel_slideshow=getParam($_REQUEST, 'checklist_painel_slideshow', null);
$checklist_projeto_viabilidade=getParam($_REQUEST, 'checklist_projeto_viabilidade', null);
$checklist_projeto_abertura=getParam($_REQUEST, 'checklist_projeto_abertura', null);
$checklist_plano_gestao=getParam($_REQUEST, 'checklist_plano_gestao', null);
$checklist_ssti=getParam($_REQUEST, 'checklist_ssti', null);
$checklist_laudo=getParam($_REQUEST, 'checklist_laudo', null);
$checklist_trelo=getParam($_REQUEST, 'checklist_trelo', null);
$checklist_trelo_cartao=getParam($_REQUEST, 'checklist_trelo_cartao', null);
$checklist_pdcl=getParam($_REQUEST, 'checklist_pdcl', null);
$checklist_pdcl_item=getParam($_REQUEST, 'checklist_pdcl_item', null);
$checklist_os=getParam($_REQUEST, 'checklist_os', null);

$obj= new CChecklist();
if ($checklist_id){
	$obj->load($checklist_id);
	$cia_id=$obj->checklist_cia;
	}
else{
	$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

	if (
		$checklist_projeto || 
		$checklist_tarefa || 
		$checklist_perspectiva || 
		$checklist_tema || 
		$checklist_objetivo || 
		$checklist_fator || 
		$checklist_estrategia || 
		$checklist_meta || 
		$checklist_pratica || 
		$checklist_acao || 
		$checklist_canvas || 
		$checklist_risco || 
		$checklist_risco_resposta || 
		$checklist_indicador || 
		$checklist_calendario || 
		$checklist_monitoramento || 
		$checklist_ata || 
		$checklist_mswot || 
		$checklist_swot || 
		$checklist_operativo || 
		$checklist_instrumento || 
		$checklist_recurso || 
		$checklist_problema || 
		$checklist_demanda || 
		$checklist_programa || 
		$checklist_licao || 
		$checklist_evento || 
		$checklist_link || 
		$checklist_avaliacao || 
		$checklist_tgn || 
		$checklist_brainstorm || 
		$checklist_gut || 
		$checklist_causa_efeito || 
		$checklist_arquivo || 
		$checklist_forum || 
		$checklist_checklist || 
		$checklist_agenda || 
		$checklist_agrupamento || 
		$checklist_patrocinador || 
		$checklist_template || 
		$checklist_painel || 
		$checklist_painel_odometro || 
		$checklist_painel_composicao || 
		$checklist_tr || 
		$checklist_me || 
		$checklist_acao_item || 
		$checklist_beneficio || 
		$checklist_painel_slideshow || 
		$checklist_projeto_viabilidade || 
		$checklist_projeto_abertura || 
		$checklist_plano_gestao|| 
		$checklist_ssti || 
		$checklist_laudo || 
		$checklist_trelo || 
		$checklist_trelo_cartao || 
		$checklist_pdcl || 
		$checklist_pdcl_item || 
		$checklist_os
		){
		$sql->adTabela('cias');
		if ($checklist_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
		elseif ($checklist_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
		elseif ($checklist_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
		elseif ($checklist_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
		elseif ($checklist_objetivo) $sql->esqUnir('objetivo','objetivo','objetivo_cia=cias.cia_id');
		elseif ($checklist_fator) $sql->esqUnir('fator','fator','fator_cia=cias.cia_id');
		elseif ($checklist_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
		elseif ($checklist_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
		elseif ($checklist_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
		elseif ($checklist_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
		elseif ($checklist_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
		elseif ($checklist_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
		elseif ($checklist_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
		elseif ($checklist_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
		elseif ($checklist_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
		elseif ($checklist_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
		elseif ($checklist_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
		elseif ($checklist_mswot) $sql->esqUnir('mswot','mswot','mswot_cia=cias.cia_id');
		elseif ($checklist_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
		elseif ($checklist_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
		elseif ($checklist_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
		elseif ($checklist_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
		elseif ($checklist_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
		elseif ($checklist_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
		elseif ($checklist_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
		elseif ($checklist_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
		elseif ($checklist_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
		elseif ($checklist_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
		elseif ($checklist_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
		elseif ($checklist_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
		elseif ($checklist_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
		elseif ($checklist_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
		elseif ($checklist_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
		elseif ($checklist_arquivo) $sql->esqUnir('arquivo','arquivo','arquivo_cia=cias.cia_id');
		elseif ($checklist_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
		elseif ($checklist_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
		elseif ($checklist_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
		elseif ($checklist_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
		elseif ($checklist_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
		elseif ($checklist_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
		elseif ($checklist_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
		elseif ($checklist_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
		elseif ($checklist_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
		elseif ($checklist_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
		elseif ($checklist_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');
		elseif ($checklist_acao_item) $sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao_item_cia=cias.cia_id');
		elseif ($checklist_beneficio) $sql->esqUnir('beneficio','beneficio','beneficio_cia=cias.cia_id');
		elseif ($checklist_painel_slideshow) $sql->esqUnir('painel_slideshow','painel_slideshow','painel_slideshow_cia=cias.cia_id');
		elseif ($checklist_projeto_viabilidade) $sql->esqUnir('projeto_viabilidade','projeto_viabilidade','projeto_viabilidade_cia=cias.cia_id');
		elseif ($checklist_projeto_abertura) $sql->esqUnir('projeto_abertura','projeto_abertura','projeto_abertura_cia=cias.cia_id');
		elseif ($checklist_plano_gestao) $sql->esqUnir('plano_gestao','plano_gestao','pg_cia=cias.cia_id');
		elseif ($checklist_ssti) $sql->esqUnir('ssti','ssti','ssti_cia=cias.cia_id');
		elseif ($checklist_laudo) $sql->esqUnir('laudo','laudo','laudo_cia=cias.cia_id');
		elseif ($checklist_trelo) $sql->esqUnir('trelo','trelo','trelo_cia=cias.cia_id');
		elseif ($checklist_trelo_cartao) $sql->esqUnir('trelo_cartao','trelo_cartao','trelo_cartao_cia=cias.cia_id');
		elseif ($checklist_pdcl) $sql->esqUnir('pdcl','pdcl','pdcl_cia=cias.cia_id');
		elseif ($checklist_pdcl_item) $sql->esqUnir('pdcl_item','pdcl_item','pdcl_item_cia=cias.cia_id');
		elseif ($checklist_os) $sql->esqUnir('os','os','os_cia=cias.cia_id');
	
		if ($checklist_tarefa) $sql->adOnde('tarefa_id = '.(int)$checklist_tarefa);
		elseif ($checklist_projeto) $sql->adOnde('projeto_id = '.(int)$checklist_projeto);
		elseif ($checklist_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$checklist_perspectiva);
		elseif ($checklist_tema) $sql->adOnde('tema_id = '.(int)$checklist_tema);
		elseif ($checklist_objetivo) $sql->adOnde('objetivo_id = '.(int)$checklist_objetivo);
		elseif ($checklist_fator) $sql->adOnde('fator_id = '.(int)$checklist_fator);
		elseif ($checklist_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$checklist_estrategia);
		elseif ($checklist_meta) $sql->adOnde('pg_meta_id = '.(int)$checklist_meta);
		elseif ($checklist_pratica) $sql->adOnde('pratica_id = '.(int)$checklist_pratica);
		elseif ($checklist_acao) $sql->adOnde('plano_acao_id = '.(int)$checklist_acao);
		elseif ($checklist_canvas) $sql->adOnde('canvas_id = '.(int)$checklist_canvas);
		elseif ($checklist_risco) $sql->adOnde('risco_id = '.(int)$checklist_risco);
		elseif ($checklist_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$checklist_risco_resposta);
		elseif ($checklist_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$checklist_indicador);
		elseif ($checklist_calendario) $sql->adOnde('calendario_id = '.(int)$checklist_calendario);
		elseif ($checklist_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$checklist_monitoramento);
		elseif ($checklist_ata) $sql->adOnde('ata_id = '.(int)$checklist_ata);
		elseif ($checklist_mswot) $sql->adOnde('mswot_id = '.(int)$checklist_mswot);
		elseif ($checklist_swot) $sql->adOnde('swot_id = '.(int)$checklist_swot);
		elseif ($checklist_operativo) $sql->adOnde('operativo_id = '.(int)$checklist_operativo);
		elseif ($checklist_instrumento) $sql->adOnde('instrumento_id = '.(int)$checklist_instrumento);
		elseif ($checklist_recurso) $sql->adOnde('recurso_id = '.(int)$checklist_recurso);
		elseif ($checklist_problema) $sql->adOnde('problema_id = '.(int)$checklist_problema);
		elseif ($checklist_demanda) $sql->adOnde('demanda_id = '.(int)$checklist_demanda);
		elseif ($checklist_programa) $sql->adOnde('programa_id = '.(int)$checklist_programa);
		elseif ($checklist_licao) $sql->adOnde('licao_id = '.(int)$checklist_licao);
		elseif ($checklist_evento) $sql->adOnde('evento_id = '.(int)$checklist_evento);
		elseif ($checklist_link) $sql->adOnde('link_id = '.(int)$checklist_link);
		elseif ($checklist_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$checklist_avaliacao);
		elseif ($checklist_tgn) $sql->adOnde('tgn_id = '.(int)$checklist_tgn);
		elseif ($checklist_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$checklist_brainstorm);
		elseif ($checklist_gut) $sql->adOnde('gut_id = '.(int)$checklist_gut);
		elseif ($checklist_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$checklist_causa_efeito);
		elseif ($checklist_arquivo) $sql->adOnde('arquivo_id = '.(int)$checklist_arquivo);
		elseif ($checklist_forum) $sql->adOnde('forum_id = '.(int)$checklist_forum);
		elseif ($checklist_checklist) $sql->adOnde('checklist_id = '.(int)$checklist_checklist);
		elseif ($checklist_agenda) $sql->adOnde('agenda_id = '.(int)$checklist_agenda);
		elseif ($checklist_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$checklist_agrupamento);
		elseif ($checklist_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$checklist_patrocinador);
		elseif ($checklist_template) $sql->adOnde('template_id = '.(int)$checklist_template);
		elseif ($checklist_painel) $sql->adOnde('painel_id = '.(int)$checklist_painel);
		elseif ($checklist_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$checklist_painel_odometro);
		elseif ($checklist_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$checklist_painel_composicao);
		elseif ($checklist_tr) $sql->adOnde('tr_id = '.(int)$checklist_tr);
		elseif ($checklist_me) $sql->adOnde('me_id = '.(int)$checklist_me);
		elseif ($checklist_acao_item) $sql->adOnde('plano_acao_item_id = '.(int)$checklist_acao_item);
		elseif ($checklist_beneficio) $sql->adOnde('beneficio_id = '.(int)$checklist_beneficio);
		elseif ($checklist_painel_slideshow) $sql->adOnde('painel_slideshow_id = '.(int)$checklist_painel_slideshow);
		elseif ($checklist_projeto_viabilidade) $sql->adOnde('projeto_viabilidade_id = '.(int)$checklist_projeto_viabilidade);
		elseif ($checklist_projeto_abertura) $sql->adOnde('projeto_abertura_id = '.(int)$checklist_projeto_abertura);
		elseif ($checklist_plano_gestao) $sql->adOnde('pg_id = '.(int)$checklist_plano_gestao);
		elseif ($checklist_ssti) $sql->adOnde('ssti_id = '.(int)$checklist_ssti);
		elseif ($checklist_laudo) $sql->adOnde('laudo_id = '.(int)$checklist_laudo);
		elseif ($checklist_trelo) $sql->adOnde('trelo_id = '.(int)$checklist_trelo);
		elseif ($checklist_trelo_cartao) $sql->adOnde('trelo_cartao_id = '.(int)$checklist_trelo_cartao);
		elseif ($checklist_pdcl) $sql->adOnde('pdcl_id = '.(int)$checklist_pdcl);
		elseif ($checklist_pdcl_item) $sql->adOnde('pdcl_item_id = '.(int)$checklist_pdcl_item);
		elseif ($checklist_os) $sql->adOnde('os_id = '.(int)$checklist_os);
		
		$sql->adCampo('cia_id');
		$cia_id = $sql->Resultado();
		$sql->limpar();
		}
	}
	
if($checklist_id && !(permiteEditarChecklist($obj->checklist_acesso,$checklist_id) && $Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'checklist'))) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$checklist_id && !$Aplic->checarModulo('praticas', 'adicionar', $Aplic->usuario_id, 'checklist'))$Aplic->redirecionar('m=publico&a=acesso_negado');


$usuarios_selecionados=array();
$indicadores =array();
$depts_selecionados = array();
$cias_selecionadas = array();
if ($checklist_id) {
	$sql->adTabela('checklist_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('checklist_id = '.(int)$checklist_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('pratica_indicador');
	$sql->adCampo('DISTINCT pratica_indicador_id');
	$sql->adOnde('pratica_indicador_checklist = '.(int)$checklist_id);
	$indicadores = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('checklist_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('checklist_id ='.(int)$checklist_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('checklist_cia');
		$sql->adCampo('checklist_cia_cia');
		$sql->adOnde('checklist_cia_checklist = '.(int)$checklist_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}



echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="checklist_fazer_sql" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="checklist_id" id="checklist_id" value="'.$checklist_id.'" />';
echo '<input name="checklist_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="pratica_indicador_ides" type="hidden" value="'.implode(',', $indicadores).'" />';
echo '<input name="checklist_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="checklist_cias"  id="checklist_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';

echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
$uuid=($checklist_id ? '' : uuid());
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';

echo estiloTopoCaixa();
echo '<table cellspacing=1 cellpadding=1 border=0 width="100%" class="std">';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
echo '<tr><td align="right">'.dica('Nome do Checklist', 'Todo checklist necessita ter um nome para identifica??o.').'Nome:'.dicaF().'</td><td><input type="text" name="checklist_nome" value="'.($obj->checklist_nome ? $obj->checklist_nome : '').'" style="width:400px;" class="texto" />*</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Respons?vel', 'A qual '.$config['organizacao'].' pertence este checklist.').ucfirst($config['organizacao']).' respons?vel:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'checklist_cia', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
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
	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' est?o envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}
if ($Aplic->profissional) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Respons?vel', 'Escolha pressionando o ?cone ? direita qual '.$config['genero_dept'].' '.$config['dept'].' respons?vel por este checklist.').ucfirst($config['departamento']).' respons?vel:'.dicaF().'</td><td><input type="hidden" name="checklist_dept" id="checklist_dept" value="'.($checklist_id ? $obj->checklist_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($checklist_id ? $obj->checklist_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:400px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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
echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' est?o envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap" width="100">'.dica('Respons?vel', 'Toda meta deve ter um respons?vel.').'Respons?vel:'.dicaF().'</td><td colspan="2"><input type="hidden" id="checklist_responsavel" name="checklist_responsavel" value="'.($obj->checklist_responsavel ? $obj->checklist_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->checklist_responsavel ? $obj->checklist_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

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
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' est?o envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';






echo '<tr><td align="right">'.dica('Descri??o', 'Uma breve descri??o do checklist, caso seja pertinente.').'Descri??o</td><td><textarea data-gpweb-cmp="ckeditor" rows="10" name="checklist_descricao" id="checklist_descricao">'.$obj->checklist_descricao.'</textarea></td></tr>';
$sql->adTabela('checklist_modelo');
$sql->adCampo('checklist_modelo_id, checklist_modelo_nome');
$modelos = $sql->listaVetorChave('checklist_modelo_id', 'checklist_modelo_nome');
$sql->limpar();

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Escolha o modelo de checklist.').'Modelo:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($modelos, 'checklist_modelo', 'class="texto" style="width:400px;"', ($obj->checklist_modelo ? $obj->checklist_modelo : '1')).'</td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Checklist Superior', 'Caso este check list tenha um cheklist superior selecione o mesmo.').'Checklist superior:'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="checklist_superior" name="checklist_superior" value="'.($obj->checklist_superior ? $obj->checklist_superior : null).'" /><input type="text" id="nome_checklist" name="nome_checklist" value="'.nome_checklist(($obj->checklist_superior ? $obj->checklist_superior : null)).'" style="width:400px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popChecklist2();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ?cone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';


$sql->adTabela('pratica_indicador');
$sql->adCampo('DISTINCT pratica_indicador_id');
$sql->adOnde('pratica_indicador_checklist = '.(int)$checklist_id);
$lista_indicadores = $sql->carregarColuna();

$sql->limpar();
$saida_indicador='';
if ($lista_indicadores && count($lista_indicadores)) {
		$saida_indicador.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_indicador.= '<tr><td>'.link_indicador($lista_indicadores[0]);
		$qnt_lista_indicadores=count($lista_indicadores);
		if ($qnt_lista_indicadores > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_indicadores; $i < $i_cmp; $i++) $lista.=link_indicador($lista_indicadores[$i]).'<br>';
				$saida_indicador.= dica('Outros Indicadores', 'Clique para visualizar os demais indicadores.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_indicadores\');">(+'.($qnt_lista_indicadores - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_indicadores"><br>'.$lista.'</span>';
				}
		$saida_indicador.= '</td></tr></table>';
		}
if ($saida_indicador) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Qual indicador est? relacionado a este checklist.').'Indicador:'.dicaF().'</td><td width="100%" colspan="2">'.$saida_indicador.'</td></tr>';



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


if ($checklist_tarefa) $tipo='tarefa';
elseif ($checklist_projeto) $tipo='projeto';
elseif ($checklist_perspectiva) $tipo='perspectiva';
elseif ($checklist_tema) $tipo='tema';
elseif ($checklist_objetivo) $tipo='objetivo';
elseif ($checklist_fator) $tipo='fator';
elseif ($checklist_estrategia) $tipo='estrategia';
elseif ($checklist_meta) $tipo='meta';
elseif ($checklist_pratica) $tipo='pratica';
elseif ($checklist_acao) $tipo='acao';
elseif ($checklist_canvas) $tipo='canvas';
elseif ($checklist_risco) $tipo='risco';
elseif ($checklist_risco_resposta) $tipo='risco_resposta';
elseif ($checklist_indicador) $tipo='checklist_indicador';
elseif ($checklist_calendario) $tipo='calendario';
elseif ($checklist_monitoramento) $tipo='monitoramento';
elseif ($checklist_ata) $tipo='ata';
elseif ($checklist_mswot) $tipo='mswot';
elseif ($checklist_swot) $tipo='swot';
elseif ($checklist_operativo) $tipo='operativo';
elseif ($checklist_instrumento) $tipo='instrumento';
elseif ($checklist_recurso) $tipo='recurso';
elseif ($checklist_problema) $tipo='problema';
elseif ($checklist_demanda) $tipo='demanda';
elseif ($checklist_programa) $tipo='programa';
elseif ($checklist_licao) $tipo='licao';
elseif ($checklist_evento) $tipo='evento';
elseif ($checklist_link) $tipo='link';
elseif ($checklist_avaliacao) $tipo='avaliacao';
elseif ($checklist_tgn) $tipo='tgn';
elseif ($checklist_brainstorm) $tipo='brainstorm';
elseif ($checklist_gut) $tipo='gut';
elseif ($checklist_causa_efeito) $tipo='causa_efeito';
elseif ($checklist_arquivo) $tipo='arquivo';
elseif ($checklist_forum) $tipo='forum';
elseif ($checklist_checklist) $tipo='checklist';
elseif ($checklist_agenda) $tipo='agenda';
elseif ($checklist_agrupamento) $tipo='agrupamento';
elseif ($checklist_patrocinador) $tipo='patrocinador';
elseif ($checklist_template) $tipo='template';
elseif ($checklist_painel) $tipo='painel';
elseif ($checklist_painel_odometro) $tipo='painel_odometro';
elseif ($checklist_painel_composicao) $tipo='painel_composicao';
elseif ($checklist_tr) $tipo='tr';
elseif ($checklist_me) $tipo='me';
elseif ($checklist_acao_item) $tipo='acao_item';
elseif ($checklist_beneficio) $tipo='beneficio';
elseif ($checklist_painel_slideshow) $tipo='painel_slideshow';
elseif ($checklist_projeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($checklist_projeto_abertura) $tipo='projeto_abertura';
elseif ($checklist_plano_gestao) $tipo='plano_gestao';
elseif ($checklist_ssti) $tipo='ssti';
elseif ($checklist_laudo) $tipo='laudo';
elseif ($checklist_trelo) $tipo='trelo';
elseif ($checklist_trelo_cartao) $tipo='trelo_cartao';
elseif ($checklist_pdcl) $tipo='pdcl';
elseif ($checklist_pdcl_item) $tipo='pdcl_item';	
elseif ($checklist_os) $tipo='os';	
else $tipo='';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Relacionado', 'A que ?rea o checklist est? relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';

echo '<tr '.($checklist_projeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso seja espec?fico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo dever? constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_projeto" value="'.$checklist_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($checklist_projeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ?cone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso seja espec?fico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo dever? constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_tarefa" value="'.$checklist_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($checklist_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ?cone '.imagem('icones/tarefa_p.gif').' escolher ? qual '.$config['tarefa'].' o arquivo ir? pertencer.<br><br>Caso n?o escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo ser? d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso seja espec?fico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo dever? constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_perspectiva" value="'.$checklist_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($checklist_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ?cone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso seja espec?fico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo dever? constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_tema" value="'.$checklist_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($checklist_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ?cone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso seja espec?fico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo dever? constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_objetivo" value="'.$checklist_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($checklist_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ?cone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso seja espec?fico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo dever? constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_fator" value="'.$checklist_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($checklist_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ?cone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso seja espec?fico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo dever? constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_estrategia" value="'.$checklist_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($checklist_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ?cone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso seja espec?fico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo dever? constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_meta" value="'.$checklist_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($checklist_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ?cone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso seja espec?fico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo dever? constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_pratica" value="'.$checklist_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($checklist_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ?cone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso seja espec?fico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo dever? constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_acao" value="'.$checklist_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($checklist_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar A??o','Clique neste ?cone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de a??o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso seja espec?fico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo dever? constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_canvas" value="'.$checklist_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($checklist_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ?cone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso seja espec?fico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo dever? constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_risco" value="'.$checklist_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($checklist_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ?cone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso seja espec?fico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo dever? constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_risco_resposta" value="'.$checklist_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($checklist_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ?cone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso seja espec?fico de um indicador, neste campo dever? constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_indicador" value="'.$checklist_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($checklist_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ?cone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso seja espec?fico de uma agenda, neste campo dever? constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_calendario" value="'.$checklist_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($checklist_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste ?cone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso seja espec?fico de um monitoramento, neste campo dever? constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_monitoramento" value="'.$checklist_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($checklist_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ?cone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reuni?o', 'Caso seja espec?fico de uma ata de reuni?o neste campo dever? constar o nome da ata').'Ata de Reuni?o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_ata" value="'.(isset($checklist_ata) ? $checklist_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($checklist_ata) ? $checklist_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reuni?o','Clique neste ?cone '.imagem('icones/ata_p.png').' para selecionar uma ata de reuni?o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso seja espec?fico de uma matriz SWOT neste campo dever? constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_mswot" value="'.(isset($checklist_mswot) ? $checklist_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($checklist_mswot) ? $checklist_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste ?cone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso seja espec?fico de um campo de matriz SWOT neste campo dever? constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_swot" value="'.(isset($checklist_swot) ? $checklist_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($checklist_swot) ? $checklist_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste ?cone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso seja espec?fico de um plano operativo, neste campo dever? constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_operativo" value="'.$checklist_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($checklist_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ?cone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso seja espec?fico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo dever? constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_instrumento" value="'.$checklist_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($checklist_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ?cone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso seja espec?fico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo dever? constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_recurso" value="'.$checklist_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($checklist_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['recurso']),'Clique neste ?cone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso seja espec?fico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo dever? constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_problema" value="'.$checklist_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($checklist_problema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ?cone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso seja espec?fico de uma demanda, neste campo dever? constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_demanda" value="'.$checklist_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($checklist_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste ?cone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso seja espec?fico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo dever? constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_programa" value="'.$checklist_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($checklist_programa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ?cone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso seja espec?fico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo dever? constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_licao" value="'.$checklist_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($checklist_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste ?cone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso seja espec?fico de um evento, neste campo dever? constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_evento" value="'.$checklist_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($checklist_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ?cone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso seja espec?fico de um link, neste campo dever? constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_link" value="'.$checklist_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($checklist_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ?cone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avalia??o', 'Caso seja espec?fico de uma avalia??o, neste campo dever? constar o nome da avalia??o.').'Avalia??o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_avaliacao" value="'.$checklist_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($checklist_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avalia??o','Clique neste ?cone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avalia??o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso seja espec?fico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo dever? constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_tgn" value="'.$checklist_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($checklist_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ?cone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso seja espec?fico de um brainstorm, neste campo dever? constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_brainstorm" value="'.$checklist_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($checklist_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ?cone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz GUT', 'Caso seja espec?fico de uma matriz GUT, neste campo dever? constar o nome da matriz GUT.').'Matriz GUT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_gut" value="'.$checklist_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($checklist_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz GUT','Clique neste ?cone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso seja espec?fico de um diagrama de causa-efeito, neste campo dever? constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_causa_efeito" value="'.$checklist_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($checklist_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ?cone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso seja espec?fico de um arquivo, neste campo dever? constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_arquivo" value="'.$checklist_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($checklist_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ?cone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('F?rum', 'Caso seja espec?fico de um f?rum, neste campo dever? constar o nome do f?rum.').'F?rum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_forum" value="'.$checklist_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($checklist_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar F?rum','Clique neste ?cone '.imagem('icones/forum_p.gif').' para selecionar um f?rum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso seja espec?fico de um checklist, neste campo dever? constar o nome do checklist.').'Checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_checklist" value="'.$checklist_checklist.'" /><input type="text" id="gestao_checklist_nome" name="gestao_checklist_nome" value="'.nome_checklist($checklist_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ?cone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso seja espec?fico de um compromisso, neste campo dever? constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_agenda" value="'.$checklist_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($checklist_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste ?cone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso seja espec?fico de um agrupamento, neste campo dever? constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_agrupamento" value="'.$checklist_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($checklist_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste ?cone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso seja espec?fico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo dever? constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_patrocinador" value="'.$checklist_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($checklist_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('icones/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste ?cone '.imagem('icones/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso seja espec?fico de um modelo, neste campo dever? constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_template" value="'.$checklist_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($checklist_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ?cone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso seja espec?fico de um painel de indicador, neste campo dever? constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_painel" value="'.$checklist_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($checklist_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ?cone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Od?metro de Indicador', 'Caso seja espec?fico de um od?metro de indicador, neste campo dever? constar o nome do od?metro.').'Od?metro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_painel_odometro" value="'.$checklist_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($checklist_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Od?metro','Clique neste ?cone '.imagem('icones/odometro_p.png').' para selecionar um od?mtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composi??o de Pain?is', 'Caso seja espec?fico de uma composi??o de pain?is, neste campo dever? constar o nome da composi??o.').'Composi??o de Pain?is:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_painel_composicao" value="'.$checklist_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($checklist_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composi??o de Pain?is','Clique neste ?cone '.imagem('icones/composicao_p.gif').' para selecionar uma composi??o de pain?is.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja espec?fico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo dever? constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_tr" value="'.$checklist_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($checklist_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ?cone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja espec?fico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo dever? constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_me" value="'.$checklist_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($checklist_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ?cone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso seja espec?fico de um item de '.$config['acao'].', neste campo dever? constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_acao_item" value="'.$checklist_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($checklist_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste ?cone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso seja espec?fico de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo dever? constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_beneficio" value="'.$checklist_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($checklist_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste ?cone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composi??es', 'Caso seja espec?fico de um slideshow de composi??es, neste campo dever? constar o nome do slideshow de composi??es.').'Slideshow de composi??es:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_painel_slideshow" value="'.$checklist_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($checklist_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composi??es','Clique neste ?cone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composi??es.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_projeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso seja espec?fico de um estudo de viabilidade, neste campo dever? constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_projeto_viabilidade" value="'.$checklist_projeto_viabilidade.'" /><input type="text" id="projeto_viabilidade_nome" name="projeto_viabilidade_nome" value="'.nome_viabilidade($checklist_projeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste ?cone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_projeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso seja espec?fico de um termo de abertura, neste campo dever? constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_projeto_abertura" value="'.$checklist_projeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($checklist_projeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste ?cone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estrat?gico', 'Caso seja espec?fico de um planejamento estrat?gico, neste campo dever? constar o nome do planejamento estrat?gico.').'Planejamento estrat?gico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_plano_gestao" value="'.$checklist_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($checklist_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estrat?gico','Clique neste ?cone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estrat?gico.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_ssti ? '' : 'style="display:none"').' id="ssti" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['ssti']), 'Caso seja espec?fico de '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].', neste campo dever? constar o nome d'.$config['genero_ssti'].' '.$config['ssti'].'.').ucfirst($config['ssti']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_ssti" value="'.$checklist_ssti.'" /><input type="text" id="ssti_nome" name="ssti_nome" value="'.nome_ssti($checklist_ssti).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSSTI();">'.imagem('icones/ssti_p.png','Selecionar '.ucfirst($config['ssti']),'Clique neste ?cone '.imagem('icones/ssti_p.png').' para selecionar '.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_laudo ? '' : 'style="display:none"').' id="laudo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['laudo']), 'Caso seja espec?fico de '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].', neste campo dever? constar o nome d'.$config['genero_laudo'].' '.$config['laudo'].'.').ucfirst($config['laudo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_laudo" value="'.$checklist_laudo.'" /><input type="text" id="laudo_nome" name="laudo_nome" value="'.nome_laudo($checklist_laudo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLaudo();">'.imagem('icones/laudo_p.png','Selecionar '.ucfirst($config['laudo']),'Clique neste ?cone '.imagem('icones/laudo_p.png').' para selecionar '.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_trelo ? '' : 'style="display:none"').' id="trelo" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo']), 'Caso seja espec?fico de '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].', neste campo dever? constar o nome d'.$config['genero_trelo'].' '.$config['trelo'].'.').ucfirst($config['trelo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_trelo" value="'.$checklist_trelo.'" /><input type="text" id="trelo_nome" name="trelo_nome" value="'.nome_trelo($checklist_trelo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTrelo();">'.imagem('icones/trelo_p.png','Selecionar '.ucfirst($config['trelo']),'Clique neste ?cone '.imagem('icones/trelo_p.png').' para selecionar '.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_trelo_cartao ? '' : 'style="display:none"').' id="trelo_cartao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['trelo_cartao']), 'Caso seja espec?fico de '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].', neste campo dever? constar o nome d'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_trelo_cartao" value="'.$checklist_trelo_cartao.'" /><input type="text" id="trelo_cartao_nome" name="trelo_cartao_nome" value="'.nome_trelo_cartao($checklist_trelo_cartao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTreloCartao();">'.imagem('icones/trelo_cartao_p.png','Selecionar '.ucfirst($config['trelo_cartao']),'Clique neste ?cone '.imagem('icones/trelo_cartao_p.png').' para selecionar '.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_pdcl ? '' : 'style="display:none"').' id="pdcl" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl']), 'Caso seja espec?fico de '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].', neste campo dever? constar o nome d'.$config['genero_pdcl'].' '.$config['pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_pdcl" value="'.$checklist_pdcl.'" /><input type="text" id="pdcl_nome" name="pdcl_nome" value="'.nome_pdcl($checklist_pdcl).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPDCL();">'.imagem('icones/pdcl_p.png','Selecionar '.ucfirst($config['pdcl']),'Clique neste ?cone '.imagem('icones/pdcl_p.png').' para selecionar '.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_pdcl_item ? '' : 'style="display:none"').' id="pdcl_item" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pdcl_item']), 'Caso seja espec?fico de '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].', neste campo dever? constar o nome d'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_pdcl_item" value="'.$checklist_pdcl_item.'" /><input type="text" id="pdcl_item_nome" name="pdcl_item_nome" value="'.nome_pdcl_item($checklist_pdcl_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="pop_pdcl_item();">'.imagem('icones/pdcl_item_p.png','Selecionar '.ucfirst($config['pdcl_item']),'Clique neste ?cone '.imagem('icones/pdcl_item_p.png').' para selecionar '.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($checklist_os ? '' : 'style="display:none"').' id="os" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['os']), 'Caso seja espec?fico de '.($config['genero_os']=='o' ? 'um' : 'uma').' '.$config['os'].', neste campo dever? constar o nome d'.$config['genero_os'].' '.$config['os'].'.').ucfirst($config['os']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="checklist_os" value="'.$checklist_os.'" /><input type="text" id="os_nome" name="os_nome" value="'.nome_os($checklist_os).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="pop_os();">'.imagem('icones/os_p.png','Selecionar '.ucfirst($config['os']),'Clique neste ?cone '.imagem('icones/os_p.png').' para selecionar '.($config['genero_os']=='o' ? 'um' : 'uma').' '.$config['os'].'.').'</a></td></tr></table></td></tr>';
	
$sql->adTabela('checklist_gestao');
$sql->adCampo('checklist_gestao.*');
if ($uuid) $sql->adOnde('checklist_gestao_uuid = \''.$uuid.'\'');
else $sql->adOnde('checklist_gestao_checklist ='.(int)$checklist_id);	
$sql->adOrdem('checklist_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
echo '<tr><td></td><td><div id="combo_gestao">';
if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
foreach($lista as $gestao_data){
	echo '<tr align="center">';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posi??o', 'Clique neste ?cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['checklist_gestao_ordem'].', '.$gestao_data['checklist_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ?cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['checklist_gestao_ordem'].', '.$gestao_data['checklist_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ?cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['checklist_gestao_ordem'].', '.$gestao_data['checklist_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posi??o', 'Clique neste ?cone '.imagem('icones/2setabaixo.gif').' para mover para a ?ltima posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['checklist_gestao_ordem'].', '.$gestao_data['checklist_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	if ($gestao_data['checklist_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['checklist_gestao_tarefa']).'</td>';
	elseif ($gestao_data['checklist_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['checklist_gestao_projeto']).'</td>';
	elseif ($gestao_data['checklist_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['checklist_gestao_perspectiva']).'</td>';
	elseif ($gestao_data['checklist_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['checklist_gestao_tema']).'</td>';
	elseif ($gestao_data['checklist_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['checklist_gestao_objetivo']).'</td>';
	elseif ($gestao_data['checklist_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['checklist_gestao_fator']).'</td>';
	elseif ($gestao_data['checklist_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['checklist_gestao_estrategia']).'</td>';
	elseif ($gestao_data['checklist_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['checklist_gestao_meta']).'</td>';
	elseif ($gestao_data['checklist_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['checklist_gestao_pratica']).'</td>';
	elseif ($gestao_data['checklist_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['checklist_gestao_acao']).'</td>';
	elseif ($gestao_data['checklist_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['checklist_gestao_canvas']).'</td>';
	elseif ($gestao_data['checklist_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['checklist_gestao_risco']).'</td>';
	elseif ($gestao_data['checklist_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['checklist_gestao_risco_resposta']).'</td>';
	elseif ($gestao_data['checklist_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['checklist_gestao_indicador']).'</td>';
	elseif ($gestao_data['checklist_gestao_calendario']) echo '<td align=left>'.imagem('icones/agenda_p.png').link_calendario($gestao_data['checklist_gestao_calendario']).'</td>';
	elseif ($gestao_data['checklist_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['checklist_gestao_monitoramento']).'</td>';
	elseif ($gestao_data['checklist_gestao_ata']) echo '<td align=left>'.imagem('icones/ata_p.png').link_ata_pro($gestao_data['checklist_gestao_ata']).'</td>';
	elseif ($gestao_data['checklist_gestao_mswot']) echo '<td align=left>'.imagem('icones/mswot_p.png').link_mswot($gestao_data['checklist_gestao_mswot']).'</td>';
	elseif ($gestao_data['checklist_gestao_swot']) echo '<td align=left>'.imagem('icones/swot_p.png').link_swot($gestao_data['checklist_gestao_swot']).'</td>';
	elseif ($gestao_data['checklist_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['checklist_gestao_operativo']).'</td>';
	elseif ($gestao_data['checklist_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['checklist_gestao_instrumento']).'</td>';
	elseif ($gestao_data['checklist_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['checklist_gestao_recurso']).'</td>';
	elseif ($gestao_data['checklist_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema($gestao_data['checklist_gestao_problema']).'</td>';
	elseif ($gestao_data['checklist_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['checklist_gestao_demanda']).'</td>';
	elseif ($gestao_data['checklist_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['checklist_gestao_programa']).'</td>';
	elseif ($gestao_data['checklist_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['checklist_gestao_licao']).'</td>';
	elseif ($gestao_data['checklist_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['checklist_gestao_evento']).'</td>';
	elseif ($gestao_data['checklist_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['checklist_gestao_link']).'</td>';
	elseif ($gestao_data['checklist_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['checklist_gestao_avaliacao']).'</td>';
	elseif ($gestao_data['checklist_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['checklist_gestao_tgn']).'</td>';
	elseif ($gestao_data['checklist_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['checklist_gestao_brainstorm']).'</td>';
	elseif ($gestao_data['checklist_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut($gestao_data['checklist_gestao_gut']).'</td>';
	elseif ($gestao_data['checklist_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['checklist_gestao_causa_efeito']).'</td>';
	elseif ($gestao_data['checklist_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['checklist_gestao_arquivo']).'</td>';
	elseif ($gestao_data['checklist_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['checklist_gestao_forum']).'</td>';
	
	elseif ($gestao_data['checklist_gestao_semelhante']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['checklist_gestao_semelhante']).'</td>';
	
	elseif ($gestao_data['checklist_gestao_agenda']) echo '<td align=left>'.imagem('icones/compromisso_p.png').link_agenda($gestao_data['checklist_gestao_agenda']).'</td>';
	elseif ($gestao_data['checklist_gestao_agrupamento']) echo '<td align=left>'.imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['checklist_gestao_agrupamento']).'</td>';
	elseif ($gestao_data['checklist_gestao_patrocinador']) echo '<td align=left>'.imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['checklist_gestao_patrocinador']).'</td>';
	elseif ($gestao_data['checklist_gestao_template']) echo '<td align=left>'.imagem('icones/template_p.gif').link_template($gestao_data['checklist_gestao_template']).'</td>';
	elseif ($gestao_data['checklist_gestao_painel']) echo '<td align=left>'.imagem('icones/painel_p.png').link_painel($gestao_data['checklist_gestao_painel']).'</td>';
	elseif ($gestao_data['checklist_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['checklist_gestao_painel_odometro']).'</td>';
	elseif ($gestao_data['checklist_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['checklist_gestao_painel_composicao']).'</td>';		
	elseif ($gestao_data['checklist_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['checklist_gestao_tr']).'</td>';	
	elseif ($gestao_data['checklist_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['checklist_gestao_me']).'</td>';	
	elseif ($gestao_data['checklist_gestao_acao_item']) echo '<td align=left>'.imagem('icones/acao_item_p.png').link_acao_item($gestao_data['checklist_gestao_acao_item']).'</td>';	
	elseif ($gestao_data['checklist_gestao_beneficio']) echo '<td align=left>'.imagem('icones/beneficio_p.png').link_beneficio($gestao_data['checklist_gestao_beneficio']).'</td>';	
	elseif ($gestao_data['checklist_gestao_painel_slideshow']) echo '<td align=left>'.imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['checklist_gestao_painel_slideshow']).'</td>';	
	elseif ($gestao_data['checklist_gestao_projeto_viabilidade']) echo '<td align=left>'.imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['checklist_gestao_projeto_viabilidade']).'</td>';	
	elseif ($gestao_data['checklist_gestao_projeto_abertura']) echo '<td align=left>'.imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['checklist_gestao_projeto_abertura']).'</td>';	
	elseif ($gestao_data['checklist_gestao_plano_gestao']) echo '<td align=left>'.imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['checklist_gestao_plano_gestao']).'</td>';	
	elseif ($gestao_data['checklist_gestao_ssti']) echo '<td align=left>'.imagem('icones/ssti_p.png').link_ssti($gestao_data['checklist_gestao_ssti']).'</td>';
	elseif ($gestao_data['checklist_gestao_laudo']) echo '<td align=left>'.imagem('icones/laudo_p.png').link_laudo($gestao_data['checklist_gestao_laudo']).'</td>';
	elseif ($gestao_data['checklist_gestao_trelo']) echo '<td align=left>'.imagem('icones/trelo_p.png').link_trelo($gestao_data['checklist_gestao_trelo']).'</td>';
	elseif ($gestao_data['checklist_gestao_trelo_cartao']) echo '<td align=left>'.imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['checklist_gestao_trelo_cartao']).'</td>';
	elseif ($gestao_data['checklist_gestao_pdcl']) echo '<td align=left>'.imagem('icones/pdcl_p.png').link_pdcl($gestao_data['checklist_gestao_pdcl']).'</td>';
	elseif ($gestao_data['checklist_gestao_pdcl_item']) echo '<td align=left>'.imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['checklist_gestao_pdcl_item']).'</td>';
	elseif ($gestao_data['checklist_gestao_os']) echo '<td align=left>'.imagem('icones/os_p.png').link_os($gestao_data['checklist_gestao_os']).'</td>';

	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['checklist_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ?cone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
	}
if (count($lista)) echo '</table>';
echo '</div></td></tr>';



if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_checklist = '.(int)$checklist_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	if (count($indicadores)>1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores relacionados mais representativo da situa??o geral.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'checklist_principal_indicador', 'class="texto" style="width:400px;"', $obj->checklist_principal_indicador).'</td></tr>';
	else echo '<input type="hidden" name="checklist_principal_indicador" value="" />';
	}
else echo '<input type="hidden" name="checklist_principal_indicador" value="" />';


if ($Aplic->profissional && $exibir['moeda']){
	$sql->adTabela('moeda');
	$sql->adCampo('moeda_id, moeda_simbolo');
	$sql->adOrdem('moeda_id');
	$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
	$sql->limpar();
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'Escolha a moeda padr?o utilizada.').'Moeda:'.dicaF().'</td><td>'.selecionaVetor($moedas, 'checklist_moeda', 'class=texto size=1', ($obj->checklist_moeda ? $obj->checklist_moeda : 1)).'</td></tr>';
	}	
else echo '<input type="hidden" name="checklist_moeda" id="checklist_moeda" value="'.($obj->checklist_moeda ? $obj->checklist_moeda : 1).'" />';



echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milh?es poss?veis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inser??o do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="checklist_cor" value="'.($obj->checklist_cor ? $obj->checklist_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('N?vel de Acesso', 'Os checklist podem ter cinco n?veis de acesso:<ul><li><b>P?blico</b> - Todos podem ver e editar o checklist.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o respons?vel e os designados para o checklist podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons?vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o respons?vel pode editar.</li><li><b>Participante I</b> - Somente o respons?vel e os designados para o checklist ver e editar o checklist</li><li><b>Participantes II</b> - Somente o respons?vel e os designados podem ver e apenas o respons?vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o respons?vel e os designados para o checklist podem ver a mesma, e o respons?vel editar.</li></ul>').'N?vel de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($checklist_acesso, 'checklist_acesso', 'class="texto"', ($checklist_id ? $obj->checklist_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso o checklist ainda esteja ativo dever? estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="checklist_ativo" '.($obj->checklist_ativo || !$checklist_id ? 'checked="checked"' : '').' /></td></tr>';

$campos_customizados = new CampoCustomizados('checklist', $checklist_id, 'editar');
$campos_customizados->imprimirHTML();
















echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_perguntas\').style.display) document.getElementById(\'apresentar_perguntas\').style.display=\'\'; else document.getElementById(\'apresentar_perguntas\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Perguntas</b></a></td></tr>';
echo '<tr id="apresentar_perguntas" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width=100%>';

echo '<input type="hidden" id="checklist_lista_id" name="checklist_lista_id" value="" />';
echo '<input type="hidden" id="texto_apoio" name="texto_apoio" value="" />';

	echo '<tr><td><table cellspacing=0 cellpadding=0>';

	echo '<tr><td><table cellspacing=0 cellpadding=0>';
	echo '<tr><td align="right" style="width:85px;">'.dica('Peso', 'O peso da pergunta do checklist.').'Peso:'.dicaF().'</td><td><input type="text" id="checklist_lista_peso" name="checklist_lista_peso" value="1" style="width:200px;" class="texto" onkeypress="return somenteFloat(event)" /></td></tr>';
	echo '<tr><td align="right">'.dica('Descri??o', 'O detalhamento da pergunta do checklist.').'Descri??o:'.dicaF().'</td><td style="width:550px;"><textarea data-gpweb-cmp="ckeditor" rows="2" class="texto" name="checklist_lista_descricao" id="checklist_lista_descricao"></textarea></td></tr>';
	echo '<tr><td align="right">'.dica('Legenda', 'Marque caso esta linha seja apenas uma leganda.').'Legenda:'.dicaF().'</td><td><input type="checkbox" value="1" name="checklist_lista_legenda" id="checklist_lista_legenda" /></td></tr>';
	echo '</table></td>
	<td id="adicionar_pergunta" style="display:"><a href="javascript: void(0);" onclick="incluir_pergunta();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste ?cone '.imagem('icones/adicionar.png').' para incluir a pergunta do checklist.').'</a></td>';
	echo '<td id="confirmar_pergunta" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'checklist_lista_id\').value=0;	document.getElementById(\'checklist_lista_peso\').value=\'1\'; document.getElementById(\'texto_apoio\').value=\'\'; CKEDITOR.instances[\'checklist_lista_descricao\'].setData(\'\'); document.getElementById(\'adicionar_pergunta\').style.display=\'\'; document.getElementById(\'checklist_lista_legenda\').checked=false; document.getElementById(\'confirmar_pergunta\').style.display=\'none\';">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste ?cone '.imagem('icones/cancelar.png').' para cancelar a edi??o da pergunta do checklist.').'</a><a href="javascript: void(0);" onclick="incluir_pergunta();">'.imagem('icones/ok_g.png','Confirmar','Clique neste ?cone '.imagem('icones/ok.png').' para confirmar a edi??o da pergunta do checklist.').'</a></td></tr></table></td></tr>';


	$sql->adTabela('checklist_lista');
	$sql->adOnde('checklist_lista_checklist_id = '.(int)$checklist_id);
	$sql->adCampo('checklist_lista.*');
	$sql->adOrdem('checklist_lista_ordem');
	$perguntas=$sql->ListaChave('checklist_lista_id');
	$sql->limpar();
	echo '<tr><td colspan=20 align=left><table cellspacing=0 cellpadding=0><tr><td style="width:85px;"></td><td><div id="perguntas">';
	if (count($perguntas)) {
		echo '<table cellspacing=0 cellpadding=0><tr><td></td><td><table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>Peso</th><th>Pertgunta</th><th width=32></th></tr>';
		foreach ($perguntas as $checklist_lista_id => $linha) {
			echo '<tr>';
			echo '<td style="white-space: nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posi??o', 'Clique neste ?cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ?cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ?cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posi??o', 'Clique neste ?cone '.imagem('icones/2setabaixo.gif').' para mover para a ?ltima posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_pergunta('.$linha['checklist_lista_ordem'].', '.$linha['checklist_lista_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			if (!$linha['checklist_lista_legenda']) echo '<td align="center">'.((float)$linha['checklist_lista_peso']==(int)$linha['checklist_lista_peso'] ? (int)$linha['checklist_lista_peso']  : number_format((float)$linha['checklist_lista_peso'], 2, ',', '.')).'</td>';
			echo '<td align="left" '.($linha['checklist_lista_legenda'] ? 'colspan=2' : '').'>'.($linha['checklist_lista_descricao'] ? $linha['checklist_lista_descricao'] : '&nbsp;').'</td>';
			echo '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_pergunta('.$linha['checklist_lista_id'].');">'.imagem('icones/editar.gif', 'Editar Fluxo', 'Clique neste ?cone '.imagem('icones/editar.gif').' para editar a pergunta do checklist.').'</a>';
			echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este pergunta?\')) {excluir_pergunta('.$linha['checklist_lista_id'].');}">'.imagem('icones/remover.png', 'Excluir Fluxo', 'Clique neste ?cone '.imagem('icones/remover.png').' para excluir este pergunta de entrada.').'</a></td>';
			echo '</tr>';
			}
		echo '</table></td></tr></table>';
		}

	echo '</div></td></tr>';

	echo '</table></td></tr>';


echo '</table></td></tr>';

echo '<input type="hidden" name="perguntas_quantidade" id="perguntas_quantidade" value="'.count($perguntas).'" />';

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/checklist_editar_pro.php');

echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($checklist_id ? 'edi??o' : 'cria??o').' do pratica.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script type="text/javascript">
	
function somenteFloat(e){
	var tecla=new Number();
	if(window.event) tecla = e.keyCode;
	else if(e.which) tecla = e.which;
	else return true;
	if(((tecla < "48") && tecla !="44") || (tecla > "57")) return false;
	}	
		
function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('checklist_cia').value+'&cias_id_selecionadas='+document.getElementById('checklist_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.checklist_cias.value = organizacao_id_string;
	document.getElementById('checklist_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('checklist_cias').value);
	__buildTooltip();
	}

function mudar_posicao_pergunta(checklist_lista_ordem, checklist_lista_id, direcao){
	xajax_mudar_posicao_pergunta_ajax(checklist_lista_ordem, checklist_lista_id, direcao, document.getElementById('checklist_id').value, document.getElementById('uuid').value);
	}

function editar_pergunta(checklist_lista_id){
	xajax_editar_pergunta(checklist_lista_id);
	document.getElementById('adicionar_pergunta').style.display="none";
	document.getElementById('confirmar_pergunta').style.display="";
	CKEDITOR.instances['checklist_lista_descricao'].setData(document.getElementById('texto_apoio').value);
	}

function incluir_pergunta(){

	var texto=CKEDITOR.instances['checklist_lista_descricao'].getData();
	var peso=document.getElementById('checklist_lista_peso').value;
	var legenda=document.getElementById('checklist_lista_legenda').checked;

	if (peso.length > 0 && texto.length > 0){
		xajax_incluir_pergunta_ajax(document.getElementById('checklist_id').value, document.getElementById('uuid').value, document.getElementById('checklist_lista_id').value, peso, texto, legenda);
		document.getElementById('checklist_lista_id').value=null;
		document.getElementById('checklist_lista_peso').value='1';
		CKEDITOR.instances['checklist_lista_descricao'].setData('');
		document.getElementById('checklist_lista_legenda').checked=false;
		document.getElementById('adicionar_pergunta').style.display='';
		document.getElementById('confirmar_pergunta').style.display='none';
		}
	else if (peso.length < 1) alert('Insira um peso para a pergunta do checklist.');
	else if (texto.length < 1) alert('Insira a descri??o da pergunta do checklist.');
	}

function excluir_pergunta(checklist_lista_id){
	xajax_excluir_pergunta_ajax(checklist_lista_id, document.getElementById('checklist_id').value, document.getElementById('uuid').value);
	}


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Respons?vel', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('checklist_cia').value+'&usuario_id='+document.getElementById('checklist_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('checklist_cia').value+'&usuario_id='+document.getElementById('checklist_responsavel').value, 'Respons?vel','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('checklist_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}


var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('checklist_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('checklist_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.checklist_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('checklist_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('checklist_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.checklist_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}




function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('checklist_dept').value+'&cia_id='+document.getElementById('checklist_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('checklist_dept').value+'&cia_id='+document.getElementById('checklist_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('checklist_cia').value=cia_id;
	document.getElementById('checklist_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}



function mudar_om(){
	var cia_id=document.getElementById('checklist_cia').value;
	xajax_selecionar_om_ajax(cia_id,'checklist_cia','combo_cia', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om();"');
	}

function excluir() {
	if (confirm( "Tem certeza que deseja excluir este checklist?")) {
		var f = document.env;
		f.excluir.value=1;
		f.a.value='fazer_sql';
		f.modulo.value='checklist';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.checklist_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.checklist_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.checklist_nome.value.length < 3) {
		alert('Escreva um nome para o checklist v?lido');
		f.checklist_nome.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}


function popChecklist2() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('checklist_cia').value, window.setChecklist2, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist2&tabela=checklist&cia_id='+document.getElementById('checklist_cia').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}
function setChecklist2(chave, valor){

	document.getElementById('checklist_superior').value=(chave > 0 ? chave : null);
	document.getElementById('nome_checklist').value=valor;
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
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('checklist_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('checklist_cia').value, 'Agrupamento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.checklist_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		incluir_relacionado();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('checklist_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.checklist_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		incluir_relacionado();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('checklist_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('checklist_cia').value, 'Modelo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.checklist_template.value = chave;
		document.env.template_nome.value = valor;
		incluir_relacionado();
		}		
<?php } ?>

function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('checklist_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.checklist_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	incluir_relacionado();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('checklist_cia').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.checklist_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	incluir_relacionado();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('checklist_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.checklist_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	incluir_relacionado();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('checklist_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.checklist_tema.value = chave;
	document.env.tema_nome.value = valor;
	incluir_relacionado();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('checklist_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.checklist_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('checklist_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.checklist_fator.value = chave;
	document.env.fator_nome.value = valor;
	incluir_relacionado();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('checklist_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.checklist_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	incluir_relacionado();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('checklist_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.checklist_meta.value = chave;
	document.env.meta_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('checklist_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.checklist_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	incluir_relacionado();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('checklist_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('checklist_cia').value, 'Indicador','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.checklist_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	incluir_relacionado();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('checklist_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.checklist_acao.value = chave;
	document.env.acao_nome.value = valor;
	incluir_relacionado();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('checklist_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.checklist_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('checklist_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.checklist_risco.value = chave;
	document.env.risco_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('checklist_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('checklist_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.checklist_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	incluir_relacionado();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('checklist_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('checklist_cia').value, 'Agenda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.checklist_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	incluir_relacionado();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('checklist_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('checklist_cia').value, 'Monitoramento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.checklist_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reuni?o', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('checklist_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.checklist_ata.value = chave;
	document.env.ata_nome.value = valor;
	incluir_relacionado();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('checklist_cia').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.checklist_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Cam?po SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('checklist_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.checklist_swot.value = chave;
	document.env.swot_nome.value = valor;
	incluir_relacionado();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('checklist_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('checklist_cia').value, 'Plano Operativo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.checklist_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jur?dico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('checklist_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('checklist_cia').value, 'Instrumento Jur?dico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.checklist_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	incluir_relacionado();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('checklist_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('checklist_cia').value, 'Recurso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.checklist_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('checklist_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.checklist_problema.value = chave;
	document.env.problema_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('checklist_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('checklist_cia').value, 'Demanda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.checklist_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	incluir_relacionado();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('checklist_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.checklist_programa.value = chave;
	document.env.programa_nome.value = valor;
	incluir_relacionado();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('checklist_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.checklist_licao.value = chave;
	document.env.licao_nome.value = valor;
	incluir_relacionado();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('checklist_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('checklist_cia').value, 'Evento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.checklist_evento.value = chave;
	document.env.evento_nome.value = valor;
	incluir_relacionado();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('checklist_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('checklist_cia').value, 'Link','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.checklist_link.value = chave;
	document.env.link_nome.value = valor;
	incluir_relacionado();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avalia??o', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('checklist_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('checklist_cia').value, 'Avalia??o','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.checklist_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	incluir_relacionado();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('checklist_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.checklist_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	incluir_relacionado();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('checklist_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('checklist_cia').value, 'Brainstorm','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.checklist_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	incluir_relacionado();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('checklist_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('checklist_cia').value, 'Matriz GUT','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.checklist_gut.value = chave;
	document.env.gut_nome.value = valor;
	incluir_relacionado();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('checklist_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('checklist_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.checklist_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	incluir_relacionado();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('checklist_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('checklist_cia').value, 'Arquivo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.checklist_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	incluir_relacionado();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('F?rum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('checklist_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('checklist_cia').value, 'F?rum','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.checklist_forum.value = chave;
	document.env.forum_nome.value = valor;
	incluir_relacionado();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('checklist_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('checklist_cia').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.checklist_checklist.value = chave;
	document.env.gestao_checklist_nome.value = valor;
	incluir_relacionado();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('checklist_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('checklist_cia').value, 'Compromisso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.checklist_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	incluir_relacionado();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('checklist_cia').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('checklist_cia').value, 'Painel','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.checklist_painel.value = chave;
	document.env.painel_nome.value = valor;
	incluir_relacionado();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Od?metro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('checklist_cia').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('checklist_cia').value, 'Od?metro','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.checklist_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	incluir_relacionado();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composi??o de Pain?is', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('checklist_cia').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('checklist_cia').value, 'Composi??o de Pain?is','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.checklist_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('checklist_cia').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.checklist_tr.value = chave;
	document.env.tr_nome.value = valor;
	incluir_relacionado();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('checklist_cia').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.checklist_me.value = chave;
	document.env.me_nome.value = valor;
	incluir_relacionado();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('checklist_cia').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('checklist_cia').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.checklist_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	incluir_relacionado();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('checklist_cia').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.checklist_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	incluir_relacionado();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composi??es', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('checklist_cia').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('checklist_cia').value, 'Slideshow de Composi??es','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.checklist_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	incluir_relacionado();
	}	

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('checklist_cia').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('checklist_cia').value, 'Estudo de Viabilidade','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.checklist_projeto_viabilidade.value = chave;
	document.env.projeto_viabilidade_nome.value = valor;
	incluir_relacionado();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('checklist_cia').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('checklist_cia').value, 'Termo de Abertura','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.checklist_projeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	incluir_relacionado();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estrat?gico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('checklist_cia').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('checklist_cia').value, 'Planejamento Estrat?gico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.checklist_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	incluir_relacionado();
	}		

	
function popSSTI() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('checklist_cia').value, window.setSSTI, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setSSTI(chave, valor){
	limpar_tudo();
	document.env.checklist_ssti.value = chave;
	document.env.ssti_nome.value = valor;
	incluir_relacionado();
	}	
				
function popLaudo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('checklist_cia').value, window.setLaudo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLaudo(chave, valor){
	limpar_tudo();
	document.env.checklist_laudo.value = chave;
	document.env.laudo_nome.value = valor;
	incluir_relacionado();
	}		
	
function popTrelo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('checklist_cia').value, window.setTrelo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTrelo(chave, valor){
	limpar_tudo();
	document.env.checklist_trelo.value = chave;
	document.env.trelo_nome.value = valor;
	incluir_relacionado();
	}	
	
function popTreloCartao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('checklist_cia').value, window.setTreloCartao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTreloCartao(chave, valor){
	limpar_tudo();
	document.env.checklist_trelo_cartao.value = chave;
	document.env.trelo_cartao_nome.value = valor;
	incluir_relacionado();
	}	
	
function popPDCL() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('checklist_cia').value, window.setPDCL, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPDCL(chave, valor){
	limpar_tudo();
	document.env.checklist_pdcl.value = chave;
	document.env.pdcl_nome.value = valor;
	incluir_relacionado();
	}				
	
function pop_pdcl_item() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=set_pdcl_item&tabela=pdcl_item&cia_id='+document.getElementById('checklist_cia').value, window.set_pdcl_item, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=set_pdcl_item&tabela=pdcl_item&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function set_pdcl_item(chave, valor){
	limpar_tudo();
	document.env.checklist_pdcl_item.value = chave;
	document.env.pdcl_item_nome.value = valor;
	incluir_relacionado();
	}	

function pop_os() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["os"])?>', 1000, 700, 'm=os&a=os_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=set_os&tabela=os&cia_id='+document.getElementById('checklist_cia').value, window.set_os, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=set_os&tabela=os&cia_id='+document.getElementById('checklist_cia').value, '<?php echo ucfirst($config["os"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function set_os(chave, valor){
	limpar_tudo();
	document.env.checklist_os.value = chave;
	document.env.os_nome.value = valor;
	incluir_relacionado();
	}	

function limpar_tudo(){
	document.env.projeto_nome.value = '';
	document.env.checklist_projeto.value = null;
	document.env.checklist_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.checklist_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.checklist_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.checklist_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.checklist_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.checklist_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.checklist_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.checklist_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.checklist_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.checklist_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.checklist_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.checklist_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.checklist_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.checklist_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.checklist_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.checklist_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.checklist_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.checklist_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.checklist_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.checklist_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.checklist_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.checklist_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.checklist_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.checklist_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.checklist_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.checklist_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.checklist_link.value = null;
	document.env.link_nome.value = '';
	document.env.checklist_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.checklist_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.checklist_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.checklist_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.checklist_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.checklist_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.checklist_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.checklist_checklist.value = null;
	document.env.gestao_checklist_nome.value = '';
	document.env.checklist_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.checklist_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.checklist_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.checklist_template.value = null;
	document.env.template_nome.value = '';
	document.env.checklist_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.checklist_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.checklist_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.checklist_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.checklist_me.value = null;
	document.env.me_nome.value = '';
	document.env.checklist_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.checklist_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.checklist_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	document.env.checklist_projeto_viabilidade.value = null;
	document.env.projeto_viabilidade_nome.value = '';
	document.env.checklist_projeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.checklist_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	document.env.checklist_ssti.value = null;
	document.env.ssti_nome.value = '';
	document.env.checklist_laudo.value = null;
	document.env.laudo_nome.value = '';
	document.env.checklist_trelo.value = null;
	document.env.trelo_nome.value = '';
	document.env.checklist_trelo_cartao.value = null;
	document.env.trelo_cartao_nome.value = '';
	document.env.checklist_pdcl.value = null;
	document.env.pdcl_nome.value = '';
	document.env.checklist_pdcl_item.value = null;
	document.env.pdcl_item_nome.value = '';		
	document.env.checklist_os.value = null;
	document.env.os_nome.value = '';		
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('checklist_id').value,
	document.getElementById('uuid').value,
	f.checklist_projeto.value,
	f.checklist_tarefa.value,
	f.checklist_perspectiva.value,
	f.checklist_tema.value,
	f.checklist_objetivo.value,
	f.checklist_fator.value,
	f.checklist_estrategia.value,
	f.checklist_meta.value,
	f.checklist_pratica.value,
	f.checklist_acao.value,
	f.checklist_canvas.value,
	f.checklist_risco.value,
	f.checklist_risco_resposta.value,
	f.checklist_indicador.value,
	f.checklist_calendario.value,
	f.checklist_monitoramento.value,
	f.checklist_ata.value,
	f.checklist_mswot.value,
	f.checklist_swot.value,
	f.checklist_operativo.value,
	f.checklist_instrumento.value,
	f.checklist_recurso.value,
	f.checklist_problema.value,
	f.checklist_demanda.value,
	f.checklist_programa.value,
	f.checklist_licao.value,
	f.checklist_evento.value,
	f.checklist_link.value,
	f.checklist_avaliacao.value,
	f.checklist_tgn.value,
	f.checklist_brainstorm.value,
	f.checklist_gut.value,
	f.checklist_causa_efeito.value,
	f.checklist_arquivo.value,
	f.checklist_forum.value,
	f.checklist_checklist.value,
	f.checklist_agenda.value,
	f.checklist_agrupamento.value,
	f.checklist_patrocinador.value,
	f.checklist_template.value,
	f.checklist_painel.value,
	f.checklist_painel_odometro.value,
	f.checklist_painel_composicao.value,
	f.checklist_tr.value,
	f.checklist_me.value,
	f.checklist_acao_item.value,
	f.checklist_beneficio.value,
	f.checklist_painel_slideshow.value,
	f.checklist_projeto_viabilidade.value,
	f.checklist_projeto_abertura.value,
	f.checklist_plano_gestao.value,
	f.checklist_ssti.value,
	f.checklist_laudo.value,
	f.checklist_trelo.value,
	f.checklist_trelo_cartao.value,
	f.checklist_pdcl.value,
	f.checklist_pdcl_item.value,
	f.checklist_os.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(checklist_gestao_id){
	xajax_excluir_gestao(document.getElementById('checklist_id').value, document.getElementById('uuid').value, checklist_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, checklist_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, checklist_gestao_id, direcao, document.getElementById('checklist_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$checklist_id && (
	$checklist_tarefa || 
	$checklist_projeto || 
	$checklist_perspectiva || 
	$checklist_tema || 
	$checklist_objetivo || 
	$checklist_fator || 
	$checklist_estrategia || 
	$checklist_meta || 
	$checklist_pratica || 
	$checklist_acao || 
	$checklist_canvas || 
	$checklist_risco || 
	$checklist_risco_resposta || 
	$checklist_indicador || 
	$checklist_calendario || 
	$checklist_monitoramento || 
	$checklist_ata || 
	$checklist_mswot || 
	$checklist_swot || 
	$checklist_operativo || 
	$checklist_instrumento || 
	$checklist_recurso || 
	$checklist_problema || 
	$checklist_demanda || 
	$checklist_programa || 
	$checklist_licao || 
	$checklist_evento || 
	$checklist_link || 
	$checklist_avaliacao || 
	$checklist_tgn || 
	$checklist_brainstorm || 
	$checklist_gut || 
	$checklist_causa_efeito || 
	$checklist_arquivo || 
	$checklist_forum || 
	$checklist_checklist || 
	$checklist_agenda || 
	$checklist_agrupamento || 
	$checklist_patrocinador || 
	$checklist_template || 
	$checklist_painel || 
	$checklist_painel_odometro || 
	$checklist_painel_composicao || 
	$checklist_tr || 
	$checklist_me || 
	$checklist_acao_item || 
	$checklist_beneficio || 
	$checklist_painel_slideshow || 
	$checklist_projeto_viabilidade || 
	$checklist_projeto_abertura || 
	$checklist_plano_gestao|| 
	$checklist_ssti || 
	$checklist_laudo || 
	$checklist_trelo || 
	$checklist_trelo_cartao || 
	$checklist_pdcl || 
	$checklist_pdcl_item || 
	$checklist_os
	)) echo 'incluir_relacionado();';
	?>	
</script>

